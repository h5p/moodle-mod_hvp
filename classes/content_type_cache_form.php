<?php

/**
 * \mod_hvp\content_type_cache_form class
 *
 * @package    mod_hvp
 * @copyright  2017 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp;

global $CFG;

defined('MOODLE_INTERNAL') || die();

// Load moodleform class
require_once($CFG->libdir . '/formslib.php');


/**
 * Form to update the content type cache that mirrors the available
 * libraries in the H5P hub.
 *
 * @package    mod_hvp
 * @copyright  2017 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class content_type_cache_form extends \moodleform {

    /**
     * Define form elements
     */
    public function definition() {
        // Get form
        $mform = $this->_form;

        // Get and format date
        $last_update = get_config('mod_hvp', 'content_type_cache_updated_at');

        $date_formatted = $last_update ? \userdate($last_update) :
            get_string('ctcacheneverupdated', 'hvp');

        // Add last update info
        $mform->addElement('static', 'lastupdate',
            get_string('ctcachelastupdatelabel', 'hvp'), $date_formatted
        );

        // Update cache button
        $this->add_action_buttons(FALSE, get_string('ctcachebuttonlabel', 'hvp'));
    }
}
