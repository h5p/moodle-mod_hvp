<?php
/**
 * Responsible for displaying the content type cache page
 *
 * @package    mod_hvp
 * @copyright  2017 Joubel AS <contact@joubel.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_hvp;

global $PAGE, $SITE, $OUTPUT, $CFG;

require_once("../../config.php");
require_once($CFG->libdir . '/adminlib.php');

// No guest autologin.
require_login(0, FALSE);

$page_url = new \moodle_url('/mod/hvp/content_type_cache.php');
$PAGE->set_url($page_url);

// Inform moodle which menu entry currently is active!
admin_externalpage_setup('h5pctcache');

$PAGE->set_title(
    "{$SITE->shortname}: " . get_string('contenttypecacheheader', 'hvp')
);

// Create upload libraries form
$ct_cache_form = new content_type_cache_form();

// On form submit
if ($ct_cache_form->get_data()) {
    // Get content type cache
    $endpoint = 'http://hubendpoints';

    $data      = file_get_contents($endpoint);
    $interface = framework::instance('interface');

    // No data received
    if (!$data) {
        $interface->setErrorMessage(get_string('ctcacheconnectionfailed', 'hvp'));
        redirect($page_url);
    }

    $json = json_decode($data);

    // No libraries received
    if (!isset($json->libraries) || empty($json->libraries)) {
        $interface->setErrorMessage(get_string('ctcachenolibraries', 'hvp'));
        redirect($page_url);
    }

    global $DB;

    // Replace existing cache
    $DB->delete_records('hvp_libraries_hub_cache');
    foreach ($json->libraries as $library) {
        $DB->insert_record('hvp_libraries_hub_cache', (object) array(
            'library_id'        => $library->library_id,
            'machine_name'      => $library->machine_name,
            'title'             => $library->title,
            'major_version'     => $library->major_version,
            'minor_version'     => $library->minor_version,
            'patch_version'     => $library->patch_version,
            'h5p_version'       => $library->h5p_version,
            'short_description' => $library->short_description,
            'long_description'  => $library->long_description,
            'icon'              => $library->icon,
            'created'           => $library->created,
            'updated'           => $library->updated,
            'is_recommended'    => $library->is_recommended,
            'is_reviewed'       => $library->is_reviewed,
            'times_downloaded'  => $library->times_downloaded,
            'example_content'   => $library->example_content
        ), FALSE, TRUE);
    }

    $interface->setInfoMessage(get_string('ctcachesuccess', 'hvp'));
    set_config('content_type_cache_updated', time(), 'mod_hvp');

    // reload page
    redirect($page_url);
}


// Render page output

echo $OUTPUT->header();

// Print any messages
framework::printMessages('info',
    framework::messages('info'));

framework::printMessages('error',
    framework::messages('error'));

// Page Header
echo '<h1>' . get_string('contenttypecacheheader', 'hvp') . '</h1>';

// Display content type cache form
$ct_cache_form->display();

echo $OUTPUT->footer();
