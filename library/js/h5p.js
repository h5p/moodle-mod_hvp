var H5P = H5P || {};

// This needs to be determined before init is run.
H5P.isFramed = (window.self !== window.top); // (window.parent !== window);

// Initialize H5P content
// Scans for ".h5p-content"
H5P.init = function () {
  if (H5P.$window === undefined) {
    H5P.$window = H5P.jQuery(window);
  }
  if (H5P.$body === undefined) {
    H5P.$body = H5P.jQuery('body');
  }

  // Is this H5P being run in a frame?
  if (H5P.isFramed) {
    H5P.$body.addClass('h5p-iframe-content');
  }

  if (H5P.fullScreenBrowserPrefix === undefined) {
    if (document.documentElement.requestFullScreen) {
      H5P.fullScreenBrowserPrefix = '';
    }
    else if (document.documentElement.webkitRequestFullScreen
             && navigator.userAgent.indexOf('Android') === -1 // Skip Android
             ) {
      // Safari has stopped working as of v6.0.3.  (Specifying keyboard input
      // makes webkitRequestFullScreen silently fail.)  The following code
      // assumes that the Safari developers figure out how to properly handle
      // their own extension before reaching version 6.0.10.  Until then, we
      // treat Safari as an old IE.  (Please note: Just looking for Safari in
      // the UA string will also match Chrome.)
      if (navigator.userAgent.match(/Version\/6\.0\.[3-9].*Safari/)) {
        H5P.fullScreenBrowserPrefix = undefined;
      }
      else {
        H5P.fullScreenBrowserPrefix = 'webkit';
      }
    }
    else if (document.documentElement.mozRequestFullScreen) {
      H5P.fullScreenBrowserPrefix = 'moz';
    }
    else if (document.documentElement.msRequestFullScreen) {
      H5P.fullScreenBrowserPrefix = 'ms';
    }
  }

  // H5Ps added in normal DIV.
  H5P.jQuery(".h5p-content").each(function (idx, el) {
    var $el = H5P.jQuery(el),
      contentId = $el.data('content-id'),
      mainLibrary = $el.data('class'),
      obj = new (H5P.classFromName(mainLibrary))(H5P.jQuery.parseJSON(H5PIntegration.getJsonContent(contentId)), contentId);

    // Render H5P in container.
    obj.attach($el);

    // Add Fullscreen button if relevant.
    if (H5PIntegration.getFullscreen(contentId)) {
      H5P.jQuery('<div class="h5p-content-controls"><a href="#" class="h5p-enable-fullscreen">' + H5PIntegration.fullscreenText + '</a></div>').insertBefore($el).children().click(function () {
        H5P.fullScreen($el, obj);
        return false;
      });
    };
  });

  // H5Ps living in iframes. Note: Fullscreen button will be added
  // inside iFrame if relevant
  var $h5pIframes = H5P.jQuery(".h5p-iframe");
  if ($h5pIframes.length !== 0) {
    $h5pIframes.each(function (idx, iframe) {
      var $iframe = H5P.jQuery(iframe),
        contentId = $iframe.data('content-id'),
        mainLibrary = $iframe.data('class');

      iframe.contentDocument.open();
      iframe.contentDocument.write('<!doctype html><html><head>' + H5PIntegration.getHeadTags(contentId) + '</head><body><div class="h5p-content" data-class="' + mainLibrary + '" data-content-id="' + contentId + '"/></body></html>');
      iframe.contentDocument.close();
    });

    // TODO: This seems very hacky... why can't we just use the resize event? What happens if we ain't done before the next interval starts?
    setInterval(function () {
      $h5pIframes.each(function (idx, iframe) {
        var $iframe = H5P.jQuery(iframe);
        var contentHeight = $iframe.contents().height();
        var frameHeight = $iframe.innerHeight();

        if (frameHeight !== contentHeight) {
          H5P.resizeIframe($iframe.data('content-id'), contentHeight);
        }
      });
    }, 250);
  }
};

H5P.fullScreenIframe = function (contentId, obj, exitCallback) {
  H5P.fullScreen(H5P.jQuery('#iframe-wrapper-' + contentId), obj, exitCallback);
};

H5P.resizeIframe = function (contentId, height) {
  var iframe = document.getElementById('iframe-' + contentId);
  // Don't allow iFrame to grow beyond window height;
  if (height > window.innerHeight) {
    height = window.innerHeight;
  }
  iframe.style.height = (H5P.isFullscreen) ? '100%' : height + 'px';
};

/**
 * Enable full screen for the given h5p.
 *
 * @param {jQuery} $el Container
 * @param {object} obj H5P
 * @returns {undefined}
 */
H5P.fullScreen = function ($el, obj, exitCallback) {
  if (H5P.isFramed) {
    var $classes = H5P.jQuery('html').add(H5P.$body).add($el);
    $classes.addClass('h5p-fullscreen');
    window.parent.H5P.fullScreenIframe($el.data('content-id'), obj, function () {
      $classes.removeClass('h5p-fullscreen');
    });

    return;
  }

  if (H5P.fullScreenBrowserPrefix === undefined) {
    // Create semi fullscreen.
    $el.add(H5P.$body).addClass('h5p-semi-fullscreen');
    // Move H5P content to top of body to make sure it is above other page
    // content.  Insert placeholder in original position to be able to move it
    // back.
    // THIS DOES NOT WORK WITH IFRAMED CONTENT, iframe will reload/fail.
    // $el.after('<div id="h5pfullscreenreplacementplaceholder"></div>').prependTo(H5P.$body);

    H5P.isFullscreen = true;

    var $disable = H5P.jQuery('<a href="#" class="h5p-disable-fullscreen">Disable fullscreen</a>').appendTo($el);
    var keyup, disableSemiFullscreen = function () {
      $el.add(H5P.$body).removeClass('h5p-semi-fullscreen');
      // H5P.jQuery('#h5pfullscreenreplacementplaceholder').before($el).remove();
      $disable.remove();
      H5P.isFullscreen = false;
      H5P.$body.unbind('keyup', keyup);

      H5P.jQuery(".h5p-iframe").each(function (idx, el) {
        H5P.resizeIframe(H5P.jQuery(el).data('content-id'), 0);
      });

      if (exitCallback) {
        exitCallback();
      }

      if (obj.resize !== undefined) {
        obj.resize(false);
      }

      return false;
    };
    keyup = function (event) {
      if (event.keyCode === 27) {
        disableSemiFullscreen();
      }
    };
    $disable.click(disableSemiFullscreen);
    H5P.$body.keyup(keyup);
  }
  else {
    var first, eventName = H5P.fullScreenBrowserPrefix + 'fullscreenchange';
    H5P.isFullscreen = true;
    document.addEventListener(eventName, function () {
      if (first === undefined) {
        first = false;
        return;
      }
      H5P.isFullscreen = false;
      $el.add(H5P.$body).removeClass('h5p-fullscreen');

      H5P.jQuery(".h5p-iframe").each(function (idx, el) {
        H5P.resizeIframe(H5P.jQuery(el).data('content-id'), 0);
      });

      if (exitCallback) {
        exitCallback();
      }

      if (obj.resize !== undefined) {
        obj.resize(false);
      }
      document.removeEventListener(eventName, arguments.callee, false);
    });

    if (H5P.fullScreenBrowserPrefix === '') {
      $el[0].requestFullScreen();
    }
    else {
      $el[0][H5P.fullScreenBrowserPrefix + 'RequestFullScreen'](H5P.fullScreenBrowserPrefix === 'webkit' ? Element.ALLOW_KEYBOARD_INPUT : undefined);
    }

    $el.add(H5P.$body).addClass('h5p-fullscreen');
  }
  H5P.jQuery(".h5p-iframe").each(function (idx, el) {
    H5P.resizeIframe(H5P.jQuery(el).data('content-id'), 0);
  });
  if (obj.resize !== undefined) {
    obj.resize(true);
  }
};

/**
 * Find the path to the content files based on the id of the content
 *
 * Also identifies and returns absolute paths
 *
 * @param string path
 *  Absolute path to a file, or relative path to a file in the content folder
 * @param contentId
 *  Id of the content requesting a path
 */
H5P.getPath = function (path, contentId) {
  if (path.substr(0, 7) === 'http://' || path.substr(0, 8) === 'https://') {
    return path;
  }

  return H5PIntegration.getContentPath(contentId) + path;
};

/**
 * THIS FUNCTION IS DEPRECATED, USE getPath INSTEAD
 *
 *  Find the path to the content files folder based on the id of the content
 *
 *  @param contentId
 *  Id of the content requesting a path
 */
H5P.getContentPath = function (contentId) {
  return H5PIntegration.getContentPath(contentId);
};


//
// Used from libraries to construct instances of other libraries' objects by
// name.
//
H5P.classFromName = function(name) {
  var arr = name.split(".");
  return this[arr[arr.length-1]];
};

// Helper object for keeping coordinates in the same format all over.
H5P.Coords = function(x, y, w, h) {
  if ( !(this instanceof H5P.Coords) )
    return new H5P.Coords(x, y, w, h);

  this.x = 0;
  this.y = 0;
  this.w = 1;
  this.h = 1;

  if (typeof(x) === 'object') {
    this.x = x.x;
    this.y = x.y;
    this.w = x.w;
    this.h = x.h;
  } else {
    if (x !== undefined) {
      this.x = x;
    }
    if (y !== undefined) {
      this.y = y;
    }
    if (w !== undefined) {
      this.w = w;
    }
    if (h !== undefined) {
      this.h = h;
    }
  }
  return this;
};

/**
 *@param {string} library
 *  library in the format machineName majorVersion.minorVersion
 * @returns
 *  library as an object with machineName, majorVersion and minorVersion properties
 *  return false if the library parameter is invalid
 */
H5P.libraryFromString = function (library) {
  var regExp = /(.+)\s(\d)+\.(\d)$/g;
  var res = regExp.exec(library);
  if (res !== null) {
    return {
      'machineName': res[1],
      'majorVersion': res[2],
      'minorVersion': res[3]
    };
  }
  else {
    return false;
  }
};

/**
 * Get the path to the library
 *
 * @param {string} machineName The machine name of the library
 * @returns {string} The full path to the library
 */
H5P.getLibraryPath = function(machineName) {
  return H5PIntegration.getLibraryPath(machineName);
};

/**
 * Recursivly clone the given object.
 *
 * @param {object} object Object to clone.
 * @param {type} recursive
 * @returns {object} A clone of object.
 */
H5P.cloneObject = function (object, recursive) {
  var clone = object instanceof Array ? [] : {};

  for (var i in object) {
    if (object.hasOwnProperty(i)) {
      if (recursive !== undefined && recursive && typeof object[i] === 'object') {
        clone[i] = H5P.cloneObject(object[i], recursive);
      }
      else {
        clone[i] = object[i];
      }
    }
  }

  return clone;
};

/**
 * Remove all empty spaces before and after the value.
 *
 * @param {String} value
 * @returns {@exp;value@call;replace}
 */
H5P.trim = function (value) {
  return value.replace(/^\s+|\s+$/g, '');
};

/**
 * Check if javascript path/key is loaded.
 *
 * @param {String} path
 * @returns {Boolean}
 */
H5P.jsLoaded = function (path) {
  for (var i = 0; i < H5P.loadedJs.length; i++) {
    if (H5P.loadedJs[i] === path) {
      return true;
    }
  }

  return false;
};

/**
 * Check if styles path/key is loaded.
 *
 * @param {String} path
 * @returns {Boolean}
 */
H5P.cssLoaded = function (path) {
  for (var i = 0; i < H5P.loadedCss.length; i++) {
    if (H5P.loadedCss[i] === path) {
      return true;
    }
  }

  return false;
};

// We have several situations where we want to shuffle an array, extend array
// to do so.
H5P.shuffleArray = function(array) {
  var i = array.length, j, tempi, tempj;
  if ( i === 0 ) return false;
  while ( --i ) {
    j       = Math.floor( Math.random() * ( i + 1 ) );
    tempi   = array[i];
    tempj   = array[j];
    array[i] = tempj;
    array[j] = tempi;
  }
  return array;
};

/**
 * Post finished results for user.
 *
 * @param {Number} contentId
 * @param {Number} points
 * @param {Number} maxPoints
 */
H5P.setFinished = function (contentId, points, maxPoints) {
  if (H5P.postUserStatistics === true) {
    H5P.jQuery.post(H5P.ajaxPath + 'setFinished', {contentId: contentId, points: points, maxPoints: maxPoints});
  }
};

// Add indexOf to browsers that lack them. (IEs)
if (!Array.prototype.indexOf) {
  Array.prototype.indexOf = function (needle) {
    for (var i = 0; i < this.length; i++) {
      if (this[i] === needle) {
        return i;
      }
    }
    return -1;
  };
}

// Need to define trim() since this is not available on older IEs,
// and trim is used in several libs
if (String.prototype.trim === undefined) {
  String.prototype.trim = function () {
    return H5P.trim(this);
  };
}

// Finally, we want to run init when document is ready. But not if we're
// in an iFrame. Then we wait for parent to start init().
if (H5P.jQuery) {
  H5P.jQuery(document).ready(function () {
    if (!H5P.initialized) {
      H5P.initialized = true;
      H5P.init();
    }
  });
}