var H5PIntegration = H5PIntegration || {};
var H5P = H5P || {};

// If run in an iframe, use parent version of globals.
if (window.parent !== window) {
  hvp = window.parent.hvp;
  YUI = window.parent.YUI;
  M = window.parent.M;
}

YUI().use('node', function(Y) {
  Y.on('domready', function () {
    // Not sure if these actually work, mostly used in the editor i belive.
    H5P.loadedJs = hvp !== undefined && hvp.loadedJs !== undefined ? hvp.loadedJs : [];
    H5P.loadedCss = hvp !== undefined && hvp.loadedCss !== undefined ? hvp.loadedCss : [];
  }); 
});

if (Object.defineProperty) {
  // Define translations using getters since jquery ready runs before YUI. (a bit hacky yes)
  Object.defineProperty(H5PIntegration, 'fullscreenText', {
    get: function () {
       return M.util.get_string('fullscreen', 'hvp');
    }
  });
}
else {
  // Fallback for those who do not support defining properties 
  H5PIntegration.fullscreenText = 'Fullscreen';
}

H5PIntegration.getJsonContent = function (contentId) {
  return hvp.content['cid-' + contentId].jsonContent;
};

// Window parent is always available.
H5PIntegration.getContentPath = function (contentId) {
  if (hvp !== undefined && contentId !== undefined) {
    return hvp.contentPath + contentId + '/';
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
  var basePath = M.cfg.wwwroot;

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
