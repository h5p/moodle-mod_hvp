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
require_once("locallib.php");

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
    // Update cache and reload page
    hvp_update_content_type_cache();
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
