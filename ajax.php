<?php

// TODO: Document

define('AJAX_SCRIPT', true);
require(__DIR__ . '/../../../config.php');

$action = required_param('action', PARAM_ALPHA);

throw new coding_exception('Unhandled AJAX');
