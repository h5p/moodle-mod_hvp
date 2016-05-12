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
 * \mod_hvp\update_libraries_form class
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp;
defined('MOODLE_INTERNAL') || die();

// Load moodleform class
require_once("$CFG->libdir/formslib.php");

/**
 * Form for automatically downloading and installing updates.
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class update_libraries_form extends \moodleform {

    /**
     * Define form elements
     */
    public function definition() {
        global $CFG, $OUTPUT;

        // Get form
        $mform = $this->_form;

        // Add intro text
        $mform->addElement('static', 'h5pintromsg', '', get_string('updatesavailable', 'hvp'));
        $mform->addElement('static', 'h5pwhyupdatemsg', '', get_string('whyupdatepart1', 'hvp', 'href="https://h5p.org/why-update" target="_blank"') . '<br/>' .
                                                   get_string('whyupdatepart2', 'hvp'));

        // Inform about current version
        $current_update = \get_config('mod_hvp', 'current_update');
        if ($current_update !== false && $current_update > 1) {
            $mform->addElement('static', 'h5pcurrentversionmsg', get_string('currentversion', 'hvp'), date('Y-m-d', $current_update));
        }

        // Inform about available version
        $update_available = \get_config('mod_hvp', 'update_available');
        $mform->addElement('static', 'h5pavailableversionmsg', get_string('availableversion', 'hvp'),  date('Y-m-d', $update_available));

        // Further instrcutions
        $mform->addElement('static', 'h5pusebuttonbelowmsg', '', get_string('usebuttonbelow', 'hvp'));

        // Upload button
        $this->add_action_buttons(false, get_string('downloadandupdate', 'hvp'));
    }

    /**
     * Preprocess incoming data
     *
     * @param array $default_values default values for form
     */
    function data_preprocessing(&$default_values) {

    }

    /**
     * Validate incoming data
     *
     * @param array $data array of ("fieldname"=>value) of submitted data
     * @param array $files array of uploaded files "element_name"=>tmp_file_path
     * @return array of "element_name"=>"error_description" if there are errors,
     *         or an empty array if everything is OK (true allowed for backwards compatibility too).
     */
    function validation($data, $files) {
        global $CFG;
        require_once($CFG->libdir . '/filelib.php');
        $errors = array();

        $error = \mod_hvp\framework::downloadH5pLibraries(true);
        if ($error !== null) {
          $errors['h5pintromsg'] = $error;
        }

        return $errors;
    }
}
