<?php

// TODO: Document
define('AJAX_SCRIPT', true);
require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/classes/content_user_data.php');

$action = required_param('action', PARAM_ALPHA);

switch($action) {
    case 'contentsuserdata':
        $contentId = required_param('content_id', PARAM_INT);
        $dataType = required_param('data_type', PARAM_ALPHA);
        $subContentId = required_param('sub_content_id', PARAM_INT);
        \mod_hvp\Content_User_Data::save_user_data($contentId, $dataType, $subContentId);
        break;
    default:
        throw new coding_exception('Unhandled AJAX');
        break;
}
