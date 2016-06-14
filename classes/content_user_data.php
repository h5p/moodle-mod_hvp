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
 * The mod_hvp content user data.
 *
 * @package    mod_hvp
 * @since      Moodle 2.7
 * @copyright  2016 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp;

/**
 * Class content_user_data handles user data and corresponding db operations.
 *
 * @package mod_hvp
 */
class content_user_data {

    /**
     * Retrieves ajax parameters for content and update or delete
     * user data depending on params.
     *
     * @throws \coding_exception
     */
    public static function handle_ajax() {
        global $DB;

        // Query String Parameters.
        $content_id = required_param('content_id', PARAM_INT);
        $data_id = required_param('data_type', PARAM_RAW);
        $sub_content_id = required_param('sub_content_id', PARAM_INT);

        // Form Data.
        $data = optional_param('data', null, PARAM_RAW);
        $pre_load = optional_param('preload', null, PARAM_INT);
        $invalidate = optional_param('invalidate', null, PARAM_INT);

        if ($content_id === null || $data_id === null || $sub_content_id === null) {
            \H5PCore::ajaxError(get_string('missingparameters', 'hvp'));
            exit; // Missing parameters.
        }

        // Saving data
        if ($data !== NULL && $pre_load !== NULL && $invalidate !== NULL) {

            // Validate token
            if (!\H5PCore::validToken('contentuserdata', required_param('token', PARAM_RAW))) {
                \H5PCore::ajaxError(get_string('invalidtoken', 'hvp'));
                exit;
            }

            // Use context id if supplied
            $context_id = optional_param('contextId', null, PARAM_INT);
            if ($context_id) {
                $context = \context::instance_by_id($context_id);
            }
            else { // Otherwise try to find it from content id
                $context = \context_course::instance($DB->get_field('hvp', 'course', array('id' => $content_id)));
            }

            // Check permissions
            if (!has_capability('mod/hvp:savecontentuserdata', $context)) {
                \H5PCore::ajaxError(get_string('nopermissiontosavecontentuserdata', 'hvp'));
                http_response_code(403);
                exit;
            }

            if ($data === '0') {
                // Delete user data.
                self::delete_user_data($content_id, $sub_content_id, $data_id);
            } else {
                // Save user data.
                self::save_user_data($content_id, $sub_content_id, $data_id, $pre_load, $invalidate, $data);
            }
            \H5PCore::ajaxSuccess();
        }
        else {
            // Fetch user data
            $user_data = self::get_user_data($content_id, $sub_content_id, $data_id);

            if ($user_data === false) {
                // Did not find data, return nothing
                \H5PCore::ajaxSuccess();
            }
            else {
                // Found data, return encoded data
                \H5PCore::ajaxSuccess($user_data->data);
            }
        }
        exit;
    }

    /**
     * Get user data for content.
     *
     * @param $content_id
     * @param $sub_content_id
     * @param $data_id
     *
     * @return mixed
     */
    public static function get_user_data($content_id, $sub_content_id, $data_id) {
        global $DB, $USER;

        $result = $DB->get_record('hvp_content_user_data', array(
                'user_id' => $USER->id,
                'hvp_id' => $content_id,
                'sub_content_id' => $sub_content_id,
                'data_id' => $data_id
            )
        );

        return $result;
    }

    /**
     * Save user data for specific content in database.
     *
     * @param $content_id
     * @param $sub_content_id
     * @param $data_id
     * @param $pre_load
     * @param $invalidate
     * @param $data
     */
    public static function save_user_data($content_id, $sub_content_id, $data_id, $pre_load, $invalidate, $data) {
        global $DB, $USER;

        // Determine if we should update or insert.
        $update = self::get_user_data($content_id, $sub_content_id, $data_id);

        // Wash values to ensure 0 or 1.
        $pre_load = ($pre_load === '0' || $pre_load === 0) ? 0 : 1;
        $invalidate = ($invalidate === '0' || $invalidate === 0) ? 0 : 1;

        // New data to be inserted.
        $new_data = (object)array(
            'user_id' => $USER->id,
            'hvp_id' => $content_id,
            'sub_content_id' => $sub_content_id,
            'data_id' => $data_id,
            'data' => $data,
            'preloaded' => $pre_load,
            'delete_on_content_change' => $invalidate
        );

        // Does not exist.
        if ($update === false) {
            // Insert new data.
            $DB->insert_record('hvp_content_user_data', $new_data);
        } else {
            // Get old data id.
            $new_data->id = $update->id;

            // Update old data.
            $DB->update_record('hvp_content_user_data', $new_data);
        }
    }

    /**
     * Delete user data with specific content from database
     *
     * @param $content_id
     * @param $sub_content_id
     * @param $data_id
     */
    public static function delete_user_data($content_id, $sub_content_id, $data_id) {
        global $DB, $USER;

        $DB->delete_records('hvp_content_user_data', array(
            'user_id' => $USER->id,
            'hvp_id' => $content_id,
            'sub_content_id' => $sub_content_id,
            'data_id' => $data_id
        ));
    }

    /**
     * Load user data for specific content
     *
     * @param $content_id
     * @return mixed User data for specific content if found, else NULL
     */
    public static function load_pre_loaded_user_data($content_id) {
        global $DB, $USER;

        $pre_loaded_user_data = array();

        $results = $DB->get_records('hvp_content_user_data', array(
            'user_id' => $USER->id,
            'hvp_id' => $content_id,
            'sub_content_id' => 0,
            'preloaded' => 1
        ));

        // Get data for data ids
        foreach ($results as $content_user_data) {
            $pre_loaded_user_data[$content_user_data->data_id] = $content_user_data->data;
        }

        return $pre_loaded_user_data;
    }
}
