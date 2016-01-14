<?php
require_once("../../config.php");
require_once($CFG->libdir.'/adminlib.php');

// No guest autologin.
require_login(0, false);

$pageurl = new moodle_url('/mod/hvp/toolproxies.php');
$PAGE->set_url($pageurl);

// Inform moodle which menu entry currently is active!
admin_externalpage_setup('h5plibraries');

$PAGE->set_title("{$SITE->shortname}: " . get_string('libraries', 'hvp'));

echo $OUTPUT->header();
echo "JALLA";
echo $OUTPUT->footer();
