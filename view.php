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

// Attach scripts, styles, etc. from core
$settings = hvp_get_core_assets();

// TODO: Load library record
$library = $DB->get_record('hvp_libraries', array('id' => $hvp->main_library_id));

// Detemine embed type
$embedtype = H5PCore::determineEmbedType($hvp->embed_type, $library->embed_types);

// Load H5P Core
$core = hvp_get_instance('core');

// Add global disable settings
if (!isset($hvp->disable)) {
  $hvp->disable = $core->getGlobalDisable();
}
else {
  $hvp->disable |= $core->getGlobalDisable();
}

$content = $core->loadContent($hvp->id);

// Filter content parameters
$safe_parameters = $core->filterParameters($content);

// TODO: Insert hook/event to alter safe_parameters?

// Add JavaScript settings for this content
$cid = 'cid-' . $hvp->id;
$settings['contents'][$cid] = array(
    'library' => $library->machine_name . ' ' . $library->major_version . '.' . $library->minor_version,
    'jsonContent' => $safe_parameters,
    'fullScreen' => $library->fullscreen,
    'exportUrl' => '', // TODO: Fix export
    'title' => $hvp->name,
    'disable' => $hvp->disable,
    'contentUserData' => array(
      0 => array(
        'state' => '{}'
      )
    )
);
// TODO: Load preloaded content user data state?

// Get assets for this content
$preloaded_dependencies = $core->loadContentDependencies($hvp->id, 'preloaded');

$files = $core->getDependenciesFiles($preloaded_dependencies);
// TODO:Insert hook/event for altering assets?

if ($embedtype === 'div') {
  foreach ($files['scripts'] as $script) {
    $url = '/mod/hvp/files/' . $script->path . $script->version;
    $settings['loadedJs'][] = $url;
    $PAGE->requires->js($url, true);
  }
  foreach ($files['styles'] as $style) {
    $url = '/mod/hvp/files/' . $style->path . $style->version;
    $settings['loadedCss'][] = $url;
    $PAGE->requires->css($url);
  }
}
else {
  $settings['contents'][$cid]['scripts'] = $core->getAssetsUrls($files['scripts']);
  $settings['contents'][$cid]['styles'] = $core->getAssetsUrls($files['styles']);
}

$PAGE->requires->data_for_js('H5PIntegration', $settings, true);

echo $OUTPUT->header();
echo '<div class="clearer"></div>';

if ($embedtype === 'div') {
    echo '<div class="h5p-content" data-content-id="' .  $hvp->id . '"></div>';
}
else {
    echo '<div class="h5p-iframe-wrapper"><iframe id="h5p-iframe-' . $hvp->id . '" class="h5p-iframe" data-content-id="' . $hvp->id . '" style="height:1px" src="about:blank" frameBorder="0" scrolling="no"></iframe></div>';
}

echo $OUTPUT->footer();
