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
 * \mod_hvp\upload_libraries_form class
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp;

global $CFG;

defined('MOODLE_INTERNAL') || die();

// Load moodleform class
require_once($CFG->libdir . '/formslib.php');


/**
 * Form to upload new H5P libraries and upgrade existing once
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class content_type_cache_form extends \moodleform {

    /**
     * Define form elements
     */
    public function definition() {
        // Get form
        $mform = $this->_form;

        $last_update = get_config('mod_hvp', 'content_type_cache_updated');


        // Information on last update
        $mform->addElement('static', 'description', 'full description',
            'Last update was at: ' . $last_update);

        // Update cache button
        $this->add_action_buttons(false, 'Update content type cache');
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
        // Download CT cache and update db

        return array();
    }
}
