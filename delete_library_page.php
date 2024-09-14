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
 * Responsible for handling the deletion of a library
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS <contact@joubel.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");
require_once($CFG->libdir.'/adminlib.php');
require_once("locallib.php");

// No guest autologin.
require_login(0, false);

$libraryid = required_param('library_id', PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);  // Confirmation flag
$pageurl = new moodle_url('/mod/hvp/delete_library_page.php', array('library_id' => $libraryid));
$PAGE->set_url($pageurl);
admin_externalpage_setup('h5plibraries');
$PAGE->set_title("{$SITE->shortname}: " . get_string('deletelibrary', 'hvp'));

// Inform Moodle which menu entry currently is active!
$core = \mod_hvp\framework::instance();
global $DB;

// Check if the library exists and has no content dependencies
$library = $DB->get_record('hvp_libraries', array('id' => $libraryid), '*', MUST_EXIST);
$usage = $core->h5pF->getLibraryUsage($libraryid);

$PAGE->set_heading(get_string('deleteheading', 'hvp', $library->title . ' (' . \H5PCore::libraryVersion($library) . ')'));

if ($confirm) {
    if ($usage['content'] === 0) {
        $dependencies = $DB->get_records('hvp_libraries_libraries', array('required_library_id' => $library->id));

        foreach ($dependencies as $dependency) {
            //Get all dependet libraries
            $dependentLibrary = $DB->get_record('hvp_libraries', array('id' => $dependency->library_id));

            if ($dependentLibrary) {
                // Delete dependent library
                \H5PCore::deleteFileTree($core->h5pF->getH5pPath() . '/libraries/' . "{$dependentLibrary->name}-{$dependentLibrary->major_version}.{$dependentLibrary->minor_version}");

                $DB->delete_records('hvp_libraries_libraries', array('library_id' => $dependentLibrary->id));
                $DB->delete_records('hvp_libraries_languages', array('library_id' => $dependentLibrary->id));
                $DB->delete_records('hvp_libraries', array('id' => $dependentLibrary->id));
            }
        }

        // Delete inital library
        $librarybase = $core->h5pF->getH5pPath() . '/libraries/';
        $libname = "{$library->name}-{$library->major_version}.{$library->minor_version}";
        \H5PCore::deleteFileTree("{$librarybase}{$libname}");

        // Remove library data from database.
        $DB->delete_records('hvp_libraries_libraries', array('library_id' => $library->id));
        $DB->delete_records('hvp_libraries_languages', array('library_id' => $library->id));
        $DB->delete_records('hvp_libraries', array('id' => $library->id));

        redirect(new moodle_url('/mod/hvp/library_list.php'), get_string('librarydeleted',  'hvp', $library->title . ' (' . \H5PCore::libraryVersion($library) . ')'), null, \core\output\notification::NOTIFY_SUCCESS);
    } else {
        // Library cannot be deleted, show error message
        echo $OUTPUT->header();
        echo $OUTPUT->notification(get_string('cannotdeletelibrary',  'hvp', $library->title . ' (' . \H5PCore::libraryVersion($library) . ')'), 'notifyproblem');
        echo $OUTPUT->continue_button(new moodle_url('/mod/hvp/library_list.php'));
        echo $OUTPUT->footer();
    }
} else {
    // Ask for confirmation
    echo $OUTPUT->header();
    echo $OUTPUT->confirm(
        get_string('confirmlibrary',  'hvp', $usage['libraries']),
        new moodle_url('/mod/hvp/delete_library_page.php', array('library_id' => $libraryid, 'confirm' => 1)),
        new moodle_url('/mod/hvp/library_list.php')
    );
    echo $OUTPUT->footer();
}
