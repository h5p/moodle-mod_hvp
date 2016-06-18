// Capture H5P (hvp) dispatched xAPI statement,
// And send the (ajax) to Moodle log by triggering a Moodle log event.
define(['jquery', 'core/log'], function ($, log) {
    "use strict"; // jshint ;_;

    log.debug('Module hvp xAPI event dispatcher initialising');

    return {
        init: function (params) {
            H5P.externalDispatcher.on('xAPI', function (event) {
                $.post(M.cfg.wwwroot + '/mod/hvp/ajax.php', {
                    'action': 'logxapievent',
                    'hvpid': params['hvpid'],
                    'courseid': params['courseid'],
                    'xapistatement': JSON.stringify(event)
                }, function (data) {
                    console.log('sended');
                });
            });
        }
    };

});
