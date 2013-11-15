<?php
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once ($CFG->dirroot . '/course/moodleform_mod.php');
//require_once ($CFG->dirroot . '/mod/hvp/h5p.php');

class mod_hvp_mod_form extends moodleform_mod {

    function definition() {
        global $CFG, $DB, $OUTPUT;
 
        $mform =& $this->_form;
 
        // Name.
        $mform->addElement('text', 'name', get_string('name'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
 
        // H5P
        // TODO: Use $course->maxbytes ? 
        $mform->addElement('filepicker', 'h5pfile', get_string('h5pfile', 'hvp'), null, array('maxbytes' => $CFG->maxbytes, 'accepted_types' => '*'));
        $mform->addRule('h5pfile', null, 'required', null, 'client');

        $this->standard_coursemodule_elements();
 
        $this->add_action_buttons();
    }
    
    function data_preprocessing(&$default_values) {
        // Aaah.. we meet again h5pfile!
        $draftitemid = file_get_submitted_draft_itemid('h5pfile');
        file_prepare_draft_area($draftitemid, $this->context->id, 'mod_hvp', 'package', 0);
        $default_values['h5pfile'] = $draftitemid;
    }
    
    function validation($data, $files) {
        global $CFG;
        
        $errors = parent::validation($data, $files);
        
        if (empty($data['h5pfile'])) {
            $errors['h5pfile'] = get_string('required');
            return $errors;
        }
        
        $files = $this->get_draft_files('h5pfile');
        if (count($files) < 1) {
            $errors['h5pfile'] = get_string('required');
            return $errors;
        }
        
        $file = reset($files);
        
        $interface = hvp_get_instance('interface');
        
        $path = $CFG->dirroot . '/mod/hvp/files/tmp/' . uniqid('hvp-');
        $interface->getUploadedH5pFolderPath($path);
        $path .= '.h5p';
        $interface->getUploadedH5pPath($path);
        $file->copy_content_to($path);
        
        $h5pValidator = hvp_get_instance('validator');
        
        if (! $h5pValidator->isValidPackage()) {
          $errors['h5pfile'] = get_string('noth5pfile', 'hvp');
        }
        
        return $errors;
    }
    
    function get_data() {
        $data = parent::get_data();
        if (!$data) {
            return false;
        }
        return $data;
    }
}

