/**
 * Collect results from xAPI events
 */
(function ($) {
  $(document).ready(function () {
    // No external dispatcher
    if (!H5P || !H5P.externalDispatcher) {
      console.debug('External dispatcher not found');
      return;
    }

    // No ajax path
    if (!H5PIntegration || !H5PIntegration.ajax || !H5PIntegration.ajax.xAPIResult) {
      console.debug('No ajax path found');
      return;
    }

    // Get emitted xAPI data
    H5P.externalDispatcher.on('xAPI', function (event) {
      // Skip malformed events
      if (!event || !event.data || !event.data.statement) {
        return;
      }

      var statement = event.data.statement;
      var isCompleted = statement.verb.display['en-US'] === 'answered' ||
        statement.verb.display['en-US'] === 'completed';

      // Ship data
      if (isCompleted) {
        $.post(H5PIntegration.ajax.xAPIResult, {
          contentId: this.contentId,
          xAPIResult: JSON.stringify({
            statement: statement
          })
        }).done(function (data) {
          if (data.error) {
            console.debug('Storing xAPI results failed with error message:', data);
          }
        });
      }
    });
  });
})(H5P.jQuery);
