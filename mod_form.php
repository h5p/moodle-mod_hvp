<?php
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once ($CFG->dirroot . '/course/moodleform_mod.php');

class mod_hvp_mod_form extends moodleform_mod {

    function definition() {
        global $CFG, $DB, $OUTPUT, $COURSE;

        $mform =& $this->_form;

        // Name.
        $mform->addElement('text', 'name', get_string('name'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        // H5P
        $mform->addElement('filepicker', 'h5pfile', get_string('h5pfile', 'hvp'), null, array('maxbytes' => $COURSE->maxbytes, 'accepted_types' => '*'));
        $mform->addRule('h5pfile', null, 'required', null, 'client');

        // Display options group.
        $mform->addElement('header', 'displayoptions', get_string('displayoptions', 'hvp'));

        $mform->addElement('checkbox', 'frame', get_string('enableframe', 'hvp'));
        $mform->setType('frame', PARAM_BOOL);
        $mform->setDefault('frame', true);

        $mform->addElement('checkbox', 'download', get_string('enabledownload', 'hvp'));
        $mform->setType('download', PARAM_BOOL);
        $mform->setDefault('download', true);

        $mform->addElement('checkbox', 'copyright', get_string('enablecopyright', 'hvp'));
        $mform->setType('copyright', PARAM_BOOL);
        $mform->setDefault('copyright', true);

        $this->standard_coursemodule_elements();

        $this->add_action_buttons();
    }

    function data_preprocessing(&$default_values) {
        // Aaah.. we meet again h5pfile!
        $draftitemid = file_get_submitted_draft_itemid('h5pfile');
        file_prepare_draft_area($draftitemid, $this->context->id, 'mod_hvp', 'package', 0);
        $default_values['h5pfile'] = $draftitemid;

        // Individual display options are not stored, must be extracted from disable.
        if (isset($default_values['disable'])) {
            // Extract disable options
            \mod_hvp\framework::instance();
            foreach (\H5PCore::$disable as $bit => $option) {
                if ($default_values['disable'] & $bit) {
                    // Disable
                    $default_values[$option] = 0;
                }
                else {
                    // Enable
                    $default_values[$option] = 1;
                }
            }
        }
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

        $interface = \mod_hvp\framework::instance('interface');

        $path = $CFG->tempdir . uniqid('/hvp-');
        $interface->getUploadedH5pFolderPath($path);
        $path .= '.h5p';
        $interface->getUploadedH5pPath($path);
        $file->copy_content_to($path);

        $h5pValidator = \mod_hvp\framework::instance('validator');

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
