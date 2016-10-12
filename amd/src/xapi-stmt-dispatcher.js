// Capture H5P (hvp) dispatched xAPI statement,
// And send them (ajax) to Moodle log by triggering a Moodle log event.
define(['jquery'], function ($) {
    /*jshint -W117 */

    return {
        init: function (params) {
            if (params.debug !== 0) {
                console.log('Module hvp xAPI event dispatcher initialising');
            }
            H5P.externalDispatcher.on('xAPI', function (event) {
                $.post(M.cfg.wwwroot + '/mod/hvp/ajax.php', {
                    'token': params.token,
                    'action': 'logxapievent',
                    'hvpid': params.hvpid,
                    'courseid': params.courseid,
                    'xapistatement': JSON.stringify(event)
                }, function (data) {
                    if (params.debug !== 0) {
                        console.log('Result : "' + data.data + '" status=' + data.success);
                    }
                });
            });
        }
    };

});
