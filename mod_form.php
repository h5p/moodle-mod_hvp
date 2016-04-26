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
 * Form for creating new H5P Content
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS <contact@joubel.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

class mod_hvp_mod_form extends moodleform_mod {

    public function definition() {
        global $CFG, $DB, $OUTPUT, $COURSE;

        $mform =& $this->_form;

        // Name.
        $mform->addElement('text', 'name', get_string('name'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        // Action.
        $h5paction = array();
        $h5paction[] = $mform->createElement('radio', 'h5paction', '', get_string('upload', 'hvp'), 'upload');
        $h5paction[] = $mform->createElement('radio', 'h5paction', '', get_string('create', 'hvp'), 'create');
        $mform->addGroup($h5paction, 'h5pactiongroup', get_string('action', 'hvp'), array('<br/>'), false);
        $mform->setDefault('h5paction', 'create');

        // Upload.
        $mform->addElement('filepicker', 'h5pfile', get_string('h5pfile', 'hvp'), null,
            array('maxbytes' => $COURSE->maxbytes, 'accepted_types' => '*'));
        if (!is_number($this->current->id)) {
            $mform->addRule('h5pfile', null, 'required', null, 'client');
        }

        // Editor placeholder.
        $mform->addElement('static', 'h5peditor', get_string('editor', 'hvp'), '<div class="h5p-editor">' . get_string('javascriptloading', 'hvp') .  '</div>');

        // Hidden fields.
        $mform->addElement('hidden', 'h5plibrary', '');
        $mform->setType('h5plibrary', PARAM_RAW);
        $mform->addElement('hidden', 'h5pparams', '');
        $mform->setType('h5pparams', PARAM_RAW);

        // Display options group.
        $mform->addElement('header', 'displayoptions', get_string('displayoptions', 'hvp'));

        $mform->addElement('checkbox', 'frame', get_string('enableframe', 'hvp'));
        $mform->setType('frame', PARAM_BOOL);
        $mform->setDefault('frame', true);

        $mform->addElement('checkbox', 'download', get_string('enabledownload', 'hvp'));
        $mform->setType('download', PARAM_BOOL);
        $mform->setDefault('download', true);
        $mform->disabledIf('download', 'frame');

        $mform->addElement('checkbox', 'copyright', get_string('enablecopyright', 'hvp'));
        $mform->setType('copyright', PARAM_BOOL);
        $mform->setDefault('copyright', true);
        $mform->disabledIf('copyright', 'frame');

        $this->standard_coursemodule_elements();

        $this->add_action_buttons();
    }

    public function data_preprocessing(&$defaultvalues) {
        $content = null;
        if (!empty($defaultvalues['id'])) {
            // Load Content
            $core = \mod_hvp\framework::instance();
            $content = $core->loadContent($defaultvalues['id']);
            if ($content === null) {
                print_error('invalidhvp');
            }
        }

        // Aaah.. we meet again h5pfile!
        $draftitemid = file_get_submitted_draft_itemid('h5pfile');
        file_prepare_draft_area($draftitemid, $this->context->id, 'mod_hvp', 'package', 0);
        $defaultvalues['h5pfile'] = $draftitemid;

        // Individual display options are not stored, must be extracted from disable.
        if (isset($defaultvalues['disable'])) {
            // Extract disable options.
            \mod_hvp\framework::instance();
            foreach (\H5PCore::$disable as $bit => $option) {
                if ($defaultvalues['disable'] & $bit) {
                    // Disable.
                    $defaultvalues[$option] = 0;
                } else {
                    // Enable.
                    $defaultvalues[$option] = 1;
                }
            }
        }

        // Determine default action
        if ($content === null) { // TODO: && !libraries exists && previous action != 'upload'
          $defaultvalues['h5paction'] = 'upload';
        }

        // Set editor defaults
        $defaultvalues['h5plibrary'] = ($content === null ? 0 : H5PCore::libraryToString($content['library']));
        $defaultvalues['h5pparams'] = ($content === null ? '{}' : $defaultvalues['filtered']);

        // Add required editor assets.
        require_once 'locallib.php';
        \hvp_add_editor_assets($content === null ? null : $defaultvalues['id']);
    }

    public function validation($data, $files) {
        global $CFG;

        $errors = parent::validation($data, $files);

        if (empty($data['h5pfile'])) {
            $errors['h5pfile'] = get_string('required');
            return $errors;
        }

        $files = $this->get_draft_files('h5pfile');

        // No file uploaded.
        if (count($files) < 1) {

            // No previous content to update.
            if (!is_number($this->current->id)) {
                $errors['h5pfile'] = get_string('required');
            }
            return $errors;
        }

        $file = reset($files);

        $interface = \mod_hvp\framework::instance('interface');

        $path = $CFG->tempdir . uniqid('/hvp-');
        $interface->getUploadedH5pFolderPath($path);
        $path .= '.h5p';
        $interface->getUploadedH5pPath($path);
        $file->copy_content_to($path);

        $h5pvalidator = \mod_hvp\framework::instance('validator');

        if (! $h5pvalidator->isValidPackage()) {
            $messages = \mod_hvp\framework::messages('error');
            $errors['h5pfile'] = implode('<br/>', $messages);
        }

        return $errors;
    }

    public function get_data() {
        $data = parent::get_data();
        if (!$data) {
            return false;
        }
        return $data;
    }
}
