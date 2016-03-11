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

require_once($CFG->dirroot . '/backup/moodle2/backup_activity_task.class.php');

/**
 * Define all the backup steps that will be used by the backup_hvp_activity_task
 */

/**
 * Define the complete hvp structure for backup, with file and id annotations
 */
class backup_hvp_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // To know if we are including userinfo
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated
        $hvp = new backup_nested_element('hvp', array('id'), array(
            'course',
            'name',
            'json_content',
            'embed_type',
            'disable',
            'main_library_id',
            'content_type',
            'author',
            'license',
            'meta_keywords',
            'meta_description',
            'filtered',
            'slug',
            'timecreated',
            'timemodified',
        ));

        $hvp_libraries = new backup_nested_element('libraries');
        $hvp_library = new backup_nested_element('library', null, array(
            'machine_name',
            'major_version',
            'minor_version',
            'patch_version',
            'dependency_type',
            'drop_css',
            'weight',
        ));

        $content_user_data_entries = new backup_nested_element('content_user_data');
        $content_user_data = new backup_nested_element('entry', array('id'), array(
            'user_id',
            'hvp_id',
            'sub_content_id',
            'data_id',
            'data',
            'preloaded',
            'delete_on_content_change',
        ));

        // Build the tree
        $hvp->add_child($content_user_data_entries);
        $content_user_data_entries->add_child($content_user_data);

        $hvp->add_child($hvp_libraries);
        $hvp_libraries->add_child($hvp_library);

        // Define sources
        $hvp->set_source_table('hvp', array('id' => backup::VAR_ACTIVITYID));

        $lib_sql = "SELECT
                machine_name,
                major_version,
                minor_version,
                patch_version,
                dependency_type,
                drop_css,
                weight
            FROM {hvp_libraries} hl
            JOIN {hvp_contents_libraries} hcl
                ON hl.id = hcl.library_id
            JOIN {hvp} h
                ON h.id = hcl.hvp_id
            WHERE h.id = ?";

        $hvp_library->set_source_sql($lib_sql, array(backup::VAR_ACTIVITYID));

        // All the rest of elements only happen if we are including user info
        if ($userinfo) {
            $content_user_data->set_source_table('hvp_content_user_data', array('hvp_id' => backup::VAR_PARENTID), 'id ASC');
        }

        // Define id annotations
        $content_user_data->annotate_ids('user', 'user_id');

        // Define file annotations
        $hvp->annotate_files('mod_hvp', 'content', null);

        // Return the root element (hvp), wrapped into standard activity structure
        return $this->prepare_activity_structure($hvp);
    }
}
