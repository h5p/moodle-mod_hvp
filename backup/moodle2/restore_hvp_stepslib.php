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
 * @package mod_hvp
 * @copyright 2016 Mediamaisteri Oy {@link http://www.mediamaisteri.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the restore steps that will be used by the restore_hvp_activity_task
 */

/**
 * Structure step to restore one H5P activity
 */
class restore_hvp_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();
        $userinfo = $this->get_setting_value('userinfo');

        $paths[] = new restore_path_element('hvp', '/activity/hvp');
        $paths[] = new restore_path_element('library', '/activity/hvp/libraries/library');

        if ($userinfo) {
            $paths[] = new restore_path_element('content_user_data', '/activity/hvp/content_user_data/entry');
        }

        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }

    protected function process_hvp($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // insert the hvp record
        $newitemid = $DB->insert_record('hvp', $data);
        // immediately after inserting "activity" record, call this
        $this->apply_activity_instance($newitemid);
    }

    /**
     * Connects the restored H5P content to content libraries
     *
     * This assumes that the libraries have already been installed
     * into the system.
     *
     * @param array $data Library dependency defitinions of the content
     */
    protected function process_library($data) {
        global $DB;

        $data = (object)$data;
        //$oldid = $data->id;

        // TODO Cache a list of existing libraries somewhere, so we won't
        // have to check them multiple times in case restoring more than
        // one H5P activity.
        $library = $DB->get_record('hvp_libraries', array(
            'machine_name' => $data->machine_name,
            'major_version' => $data->major_version,
            'minor_version' => $data->minor_version,
            'patch_version' => $data->patch_version,
        ));

        if (empty($library)) {
            // TODO What to do now? It isn't possible to continue restoring
            // the activity without the needed libraries.

            print_error("Your system is missing the library {$data->machine_name} version {$data->major_version}.{$data->minor_version}.{$data->patch_version}");
        }

        $content_library = (object) array(
            'id' => null,
            'hvp_id' => $this->get_new_parentid('hvp'), // From hvp table
            'library_id' => $library->id, // From hvp_libraries table
            'dependency_type' => $data->dependency_type,
            'drop_css' => $data->drop_css,
            'weight' => $data->weight,
        );

        // Note that we're not in fact adding a new library but a connection
        // between the restored H5P activity and an existing library.
        $newitemid = $DB->insert_record('hvp_contents_libraries', $content_library);

        // TODO
        // $this->set_mapping('hvp_todo', $oldid, $newitemid);
    }

    protected function process_content_user_data($data) {

    }

    protected function after_execute() {
        // TODO
        // Add hvp related files, no need to match by itemname (just internally handled context)
        $this->add_related_files('mod_hvp', 'content', 'id');
    }
}
