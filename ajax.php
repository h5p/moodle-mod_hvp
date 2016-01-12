<?php

// TODO: Document
define('AJAX_SCRIPT', true);
require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/classes/content_user_data.php');

$action = required_param('action', PARAM_ALPHA);

switch($action) {
    case 'contentsuserdata':
        \mod_hvp\Content_User_Data::handle_ajax();
        break;
    default:
        throw new coding_exception('Unhandled AJAX');
        break;
}
