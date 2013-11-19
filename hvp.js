var H5PIntegration = H5PIntegration || {};
var H5P = H5P || {};

// If run in an iframe, use parent version of globals.
if (window.parent !== window) {
  hvp = window.parent.hvp;
  YUI = window.parent.YUI;
}

YUI().use('node', function(Y) {
  Y.on('domready', function () {
    if (M !== undefined) {
      H5PIntegration.fullscreenText =  M.util.get_string('fullscreen', 'hvp');
    }
    H5P.loadedJs = hvp !== undefined && hvp.loadedJs !== undefined ? hvp.loadedJs : [];
    H5P.loadedCss = hvp !== undefined && hvp.loadedCss !== undefined ? hvp.loadedCss : [];
  }); 
});

H5PIntegration.getJsonContent = function (contentId) {
  return hvp.content['cid-' + contentId].jsonContent;
};

// Window parent is always available.
var locationOrigin = window.parent.location.protocol + "//" + window.parent.location.host;
H5PIntegration.getContentPath = function (contentId) {
  if (hvp !== undefined && contentId !== undefined) {
    return locationOrigin + hvp.contentPath + contentId + '/';
  }
  else if (hvpeditor !== undefined)  {
    return hvpeditor.filesPath + '/h5peditor/';
  }
};

/**
 * Get the path to the library
 *
 * TODO: Make this use machineName instead of machineName-majorVersion-minorVersion
 *
 * @param {string} library
 *  The library identifier as string, for instance 'downloadify-1.0'
 * @returns {string} The full path to the library
 */
H5PIntegration.getLibraryPath = function (library) {
  var libraryPath = (hvp !== undefined ? hvp.libraryPath : hvpeditor.libraryPath);

  return libraryPath + library;
};

/**
 * Get Fullscreenability setting.
 */
H5PIntegration.getFullscreen = function (contentId) {
  return hvp.content['cid-' + contentId].fullScreen === '1';
};

/**
 * Loop trough styles and create a set of tags for head.
 * TODO: Cache base tags or something to improve performance.
 *
 * @param {Array} styles List of stylesheets
 * @returns {String} HTML
 */
H5PIntegration.getHeadTags = function (contentId) {
  var basePath = window.location.protocol + '//' + window.location.host + '/';

  var createStyleTags = function (styles) {
    var tags = '';
    for (var i = 0; i < styles.length; i++) {
      tags += '<link rel="stylesheet" href="' + basePath + styles[i] + '">';
    }
    return tags;
  };

  var createScriptTags = function (scripts) {
    var tags = '';
    for (var i = 0; i < scripts.length; i++) {
      tags += '<script src="' + basePath + scripts[i] + '"></script>';
    }
    return tags;
  };

  return createStyleTags(hvp.core.styles)
       + createStyleTags(hvp.content['cid-' + contentId].styles)
       + createScriptTags(hvp.core.scripts)
       + createScriptTags(hvp.content['cid-' + contentId].scripts);
};
