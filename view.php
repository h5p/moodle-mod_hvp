<?php

require_once("../../config.php");
require_once("lib.php");

$id = required_param('id', PARAM_INT);  

$url = new moodle_url('/mod/hvp/view.php', array('id' => $id));
$PAGE->set_url($url);

if (! $cm = get_coursemodule_from_id('hvp', $id)) {
    print_error('invalidcoursemodule');
}
if (! $course = $DB->get_record('course', array('id' => $cm->course))) {
    print_error('coursemisconf');
}

require_course_login($course, false, $cm);

if (! $hvp = hvp_get_hvp($cm->instance)) {
    print_error('invalidcoursemodule');
}

$PAGE->set_title(format_string($hvp->name));
$PAGE->set_heading($course->fullname);

// Mark viewed by user (if required)
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

$embedtype = isset($hvp->embed_types) && !empty($hvp->embed_types) ? $hvp->embed_types : $hvp->embed_type;
$embedtype = strpos(strtolower($embedtype), 'div') !== FALSE ? 'div' : 'iframe';

hvp_add_scripts_and_styles($hvp, $embedtype);

echo $OUTPUT->header();
echo '<div class="clearer"></div>';

if ($embedtype === 'div') {
    echo '<div class="h5p-content" data-class="' . $hvp->machine_name . '" data-content-id="' .  $hvp->id . '"></div>';
}
else {
    echo '<div class="h5p-iframe-wrapper" id="iframe-wrapper-' . $hvp->id . '">';
    echo '<iframe id="iframe-' . $hvp->id . '" class="h5p-iframe" data-class="' . $hvp->machine_name . '" data-content-id="##h5pId##" style="width: 100%; height: 400px; border: none;" src="about:blank" frameBorder="0"></iframe>';
    echo '</div>';
}

echo $OUTPUT->footer();
