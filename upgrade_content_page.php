<?php
require_once("../../config.php");
require_once($CFG->libdir.'/adminlib.php');
require_once("locallib.php");

// No guest autologin.
require_login(0, false);

$library_id = required_param('library_id', PARAM_INT);

// Check if this is a post
if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
    $out = hvp_content_upgrade_progress($library_id);
    header('Cache-Control', 'no-cache');
    header('Content-Type: application/json');
    print json_encode($out);
    exit();
}
else {
    $page_url = new moodle_url('/mod/hvp/upgrade_content_page.php', array('library_id' => $library_id));
    $PAGE->set_url($page_url);
    admin_externalpage_setup('h5plibraries');
    $PAGE->set_title("{$SITE->shortname}: " . get_string('upgrade', 'hvp'));

    // Inform moodle which menu entry currently is active!
    $core = \mod_hvp\framework::instance();
    global $DB;
    $results = $DB->get_records_sql('SELECT hl2.id as id, hl2.machine_name as name, hl2.title, hl2.major_version, hl2.minor_version, hl2.patch_version
                                     FROM {hvp_libraries} hl1 JOIN {hvp_libraries} hl2 ON hl1.machine_name = hl2.machine_name
                                     WHERE hl1.id = ?
                                     ORDER BY hl2.title ASC, hl2.major_version ASC, hl2.minor_version ASC', array($library_id));
    $versions = array();
    foreach ($results as $result) {
        $versions[$result->id] = $result;
    }
    $library = $versions[$library_id];
    $upgrades = $core->getUpgrades($library, $versions);

    $PAGE->set_heading(get_string('upgradeheading', 'hvp', $library->title . ' (' . H5PCore::libraryVersion($library) . ')'));

    // Get num of contents that can be upgraded
    $num_contents = $core->h5pF->getNumContent($library_id);
    if (count($versions) < 2) {
        echo $OUTPUT->header();
        echo get_string('upgradenoavailableupgrades', 'hvp');
    }
    else if ($num_contents === 0) {
        echo $OUTPUT->header();
        echo get_string('upgradenothingtodo', 'hvp');
    }
    else {
        $settings = array(
            'libraryInfo' => array(
                'message' => get_string('upgrademessage', 'hvp', $num_contents),
                'inProgress' => get_string('upgradeinprogress', 'hvp'),
                'error' => get_string('upgradeerror', 'hvp'),
                'errorData' => get_string('upgradeerrordata', 'hvp'),
                'errorScript' => get_string('upgradeerrorscript', 'hvp'),
                'errorContent' => get_string('upgradeerrorcontent', 'hvp'),
                'errorParamsBroken' => get_string('upgradeerrorparamsbroken', 'hvp'),
                'done' => get_string('upgradedone', 'hvp', $num_contents) . ' <a href="' . (new moodle_url('/mod/hvp/library_list.php'))->out(false) . '">' . get_string('upgradereturn', 'hvp') . '</a>',
                'library' => array(
                    'name' => $library->name,
                    'version' => $library->major_version . '.' . $library->minor_version,
                ),
                'libraryBaseUrl' => (new moodle_url('/mod/hvp/upgrade_library.php'))->out(false) . '?library=',
                'scriptBaseUrl' => (new moodle_url('/mod/hvp/library/js'))->out(false),
                'buster' => hvp_get_cache_buster(),
                'versions' => $upgrades,
                'contents' => $num_contents,
                'buttonLabel' => get_string('upgradebuttonlabel', 'hvp'),
                'infoUrl' => $page_url->out(false),
                'total' => $num_contents,
                'token' => hvp_get_token('content_upgrade'), // Use token to avoid unauthorized updating
            )
        );

        // Add JavaScripts
        $lib_url = $CFG->httpswwwroot . '/mod/hvp/library/';
        hvp_admin_add_generic_css_and_js($PAGE, $lib_url, $settings);
        $PAGE->requires->js(new moodle_url($lib_url . 'js/h5p-version.js' . hvp_get_cache_buster()), true);
        $PAGE->requires->js(new moodle_url($lib_url . 'js/h5p-content-upgrade.js' . hvp_get_cache_buster()), true);
        echo $OUTPUT->header();
        echo '<div id="h5p-admin-container">' . get_string('enablejavascript', 'hvp') . '</div>';
    }

    echo $OUTPUT->footer();
}
