(function ($) {

  /**
   * @private
   */
  var createDataView = function (dataView, wrapper, loaded) {
    new H5PDataView(
      wrapper,
      dataView.source,
      dataView.headers,
      dataView.l10n,
      undefined,
      dataView.filters,
      loaded,
      dataView.order
    );
  };

  // Create data views when page is done loading
  $(document).ready(function () {
    for (var id in H5PIntegration.dataViews) {
      if (!H5PIntegration.dataViews.hasOwnProperty(id)) {
        continue;
      }

      var wrapper = $('#' + id).get(0);
      if (wrapper !== undefined) {
        createDataView(H5PIntegration.dataViews[id], wrapper);
      }
    }
  });
})(H5P.jQuery);
