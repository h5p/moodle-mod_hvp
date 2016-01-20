<?php
require_once("../../config.php");
require_once($CFG->libdir.'/adminlib.php');
require_once("locallib.php");

// library param is in the following format:
// /<machine-name>/<major-version>/<minor-version>
$library = required_param('library', PARAM_TEXT);
$library = explode('/', substr($library, 1));

if (sizeof($library) !== 3) {
    http_response_code(422);
    return;
}

$library = hvp_get_library_upgrade_info($library[0], $library[1], $library[2]);

header('Cache-Control', 'no-cache');
header('Content-Type: application/json');
print json_encode($library);
die;
