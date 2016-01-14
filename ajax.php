<?php

// TODO: Document
define('AJAX_SCRIPT', true);
require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/classes/content_user_data.php');
require_once ($CFG->dirroot . '/mod/hvp/lib.php');

$action = required_param('action', PARAM_ALPHA);

switch($action) {
    case 'contentsuserdata':
        \mod_hvp\Content_User_Data::handle_ajax();
        break;
    case 'restrictlibrary':
        global $DB;

        // TODO - check permissions
        $library_id = required_param('library_id', PARAM_INT);
        $restrict = required_param('restrict', PARAM_INT);
        $token = required_param('token', PARAM_ALPHANUMEXT);

        if (hvp_verify_token('library_' . $library_id, $token)) {
          hvp_restrict_library($library_id, $restrict);
          // TODO - need to check access - using e.g. tokens  + permissions!
          echo json_encode(array(
            'url' => (new moodle_url('/mod/hvp/ajax.php', array(
              'action' => 'restrict_library',
              'token' => hvp_get_token('library_' . $library_id),
              'restrict' => ($restrict === '1' ? 0 : 1),
              'library_id' => $library_id
            )))->out(false)
          ));
          die;
        }
        else {
          http_response_code(403);
        }
        break;
    default:
        throw new coding_exception('Unhandled AJAX');
        break;
}
