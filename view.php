<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * View H5P Content
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS <contact@joubel.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");
require_once("locallib.php");

$id = required_param('id', PARAM_INT);

$url = new \moodle_url('/mod/hvp/view.php', array('id' => $id));
$PAGE->set_url($url);

if (! $cm = get_coursemodule_from_id('hvp', $id)) {
    print_error('invalidcoursemodule');
}
if (! $course = $DB->get_record('course', array('id' => $cm->course))) {
    print_error('coursemisconf');
}

require_course_login($course, false, $cm);

// Load H5P Core.
$core = \mod_hvp\framework::instance();

// Load H5P Content.
$content = $core->loadContent($cm->instance);
if ($content === null) {
    print_error('invalidhvp');
}

// Log view
new \mod_hvp\event(
        'content', NULL,
        $content['id'], $content['title'],
        $content['library']['name'],
        $content['library']['majorVersion'] . '.' . $content['library']['minorVersion']
);

$PAGE->set_title(format_string($content['title']));
$PAGE->set_heading($course->fullname);

// Mark viewed by user (if required).
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

// Attach scripts, styles, etc. from core.
$settings = hvp_get_core_assets();

// Add global disable settings.
if (!isset($content['disable'])) {
    $content['disable'] = $core->getGlobalDisable();
} else {
    $content['disable'] |= $core->getGlobalDisable();
}

// Embed is not supported in Moodle.
$content['disable'] |= \H5PCore::DISABLE_EMBED;

// Filter content parameters.
$safeparameters = $core->filterParameters($content);

$export = '';
if (!isset($CFG->mod_hvp_export) || $CFG->mod_hvp_export === true) {
    // Find course context.
    $context = \context_course::instance($course->id);
    if (has_capability('mod/hvp:getexport', $context)) {
        $hvppath = "{$CFG->httpswwwroot}/pluginfile.php/{$context->id}/mod_hvp";
        $exportfilename = ($content['slug'] ? $content['slug'] . '-' : '') . $content['id'] . '.h5p';
        $export = "{$hvppath}/exports/{$exportfilename}";
    }
}
if (empty($export)) {
    // Remove Download button when there's no export URL
    $content['disable'] |= \H5PCore::DISABLE_DOWNLOAD;
}

// Find cm context
$context = \context_module::instance($cm->id);

// Add JavaScript settings for this content.
$cid = 'cid-' . $content['id'];
$settings['contents'][$cid] = array(
    'library' => \H5PCore::libraryToString($content['library']),
    'jsonContent' => $safeparameters,
    'fullScreen' => $content['library']['fullscreen'],
    'exportUrl' => $export,
    'title' => $content['title'],
    'disable' => $content['disable'],
    'url' => "{$CFG->httpswwwroot}/mod/hvp/view.php?id={$id}",
    'contentUrl' => "{$CFG->httpswwwroot}/pluginfile.php/{$context->id}/mod_hvp/content/" . $content['id'],
    'contentUserData' => array(
        0 => \mod_hvp\content_user_data::load_pre_loaded_user_data($content['id'])
    )
);

// Get assets for this content.
$preloadeddependencies = $core->loadContentDependencies($content['id'], 'preloaded');
$files = $core->getDependenciesFiles($preloadeddependencies);

// Determine embed type.
$embedtype = \H5PCore::determineEmbedType($content['embedType'], $content['library']['embedTypes']);
if ($embedtype === 'div') {
    $context = \context_system::instance();
    $hvppath = "/pluginfile.php/{$context->id}/mod_hvp";

    // Schedule JavaScripts for loading through Moodle.
    foreach ($files['scripts'] as $script) {
        $url = $hvppath . $script->path . $script->version;
        $settings['loadedJs'][] = $url;
        $PAGE->requires->js(new moodle_url($CFG->httpswwwroot . $url), true);
    }

    // Schedule stylesheets for loading through Moodle.
    foreach ($files['styles'] as $style) {
        $url = $hvppath . $style->path . $style->version;
        $settings['loadedCss'][] = $url;
        $PAGE->requires->css(new moodle_url($CFG->httpswwwroot . $url));
    }
} else {
    // JavaScripts and stylesheets will be loaded through h5p.js.
    $settings['contents'][$cid]['scripts'] = $core->getAssetsUrls($files['scripts']);
    $settings['contents'][$cid]['styles'] = $core->getAssetsUrls($files['styles']);
}

// Print JavaScript settings to page.
$PAGE->requires->data_for_js('H5PIntegration', $settings, true);

// Print page HTML.
echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($content['title']));
echo '<div class="clearer"></div>';

// Print any messages.
\mod_hvp\framework::printMessages('info', \mod_hvp\framework::messages('info'));
\mod_hvp\framework::printMessages('error', \mod_hvp\framework::messages('error'));

// Print intro.
if (trim(strip_tags($content['intro']))) {
    echo $OUTPUT->box_start('mod_introbox', 'hvpintro');
    echo format_module_intro('hvp', (object) array(
      'intro' => $content['intro'],
      'introformat' => $content['introformat'],
    ), $cm->id);
    echo $OUTPUT->box_end();
}

// Print H5P Content
if ($embedtype === 'div') {
    echo '<div class="h5p-content" data-content-id="' .  $content['id'] . '"></div>';
} else {
    echo '<div class="h5p-iframe-wrapper"><iframe id="h5p-iframe-' . $content['id'] .
        '" class="h5p-iframe" data-content-id="' . $content['id'] .
        '" style="height:1px" src="about:blank" frameBorder="0" scrolling="no"></iframe></div>';
}

// Find cm context
$context = \context_module::instance($cm->id);

// Trigger module viewed event.
$event = \mod_hvp\event\course_module_viewed::create(array(
    'objectid' => $cm->instance,
    'context' => $context
));
$event->add_record_snapshot('course_modules', $cm);
$event->trigger();

echo $OUTPUT->footer();
