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
 * Responsible for displaying the library list page
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS <contact@joubel.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $PAGE, $SITE, $OUTPUT, $CFG;

require_once("../../config.php");
require_once($CFG->libdir.'/adminlib.php');
require_once("locallib.php");


// No guest autologin.
require_login(0, false);

$pageurl = new moodle_url('/mod/hvp/content_type_cache.php');
$PAGE->set_url($pageurl);

// Inform moodle which menu entry currently is active!
admin_externalpage_setup('h5pctcache');

$PAGE->set_title("{$SITE->shortname}: " . get_string('contenttypecacheheader', 'hvp'));

// Create upload libraries form
$ctcacheform = new \mod_hvp\content_type_cache_form();
if ($formdata = $ctcacheform->get_data()) {
    // Download CT cache and update db
    set_config('content_type_cache_updated', time(), 'mod_hvp');
}


// RENDER PAGE OUTPUT

echo $OUTPUT->header();

// Print any messages
\mod_hvp\framework::printMessages('info', \mod_hvp\framework::messages('info'));
\mod_hvp\framework::printMessages('error', \mod_hvp\framework::messages('error'));

// Page Header
echo '<h1>' . get_string('contenttypecacheheader', 'hvp') . '</h1>';

// Display content type cache form
$ctcacheform->display();

echo $OUTPUT->footer();
