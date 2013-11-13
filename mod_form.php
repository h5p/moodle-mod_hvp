<?php
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once ($CFG->dirroot.'/course/moodleform_mod.php');

class mod_hvp_mod_form extends moodleform_mod {

    function definition() {
        global $CFG, $DB, $OUTPUT;
 
        $mform =& $this->_form;
 
        $mform->addElement('filepicker', 'h5pfile', get_string('h5pfile', 'hvp'), null, array('accepted_types' => '*.h5p'));
        $mform->addRule('h5pfile', null, 'required', null, 'client');
 
        $this->standard_coursemodule_elements();
 
        $this->add_action_buttons();
    }
}

