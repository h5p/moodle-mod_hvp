<?php

/**
 * Hvp specific lib functions and H5P Framework Interface implementation.
 *
 * @package    mod_hvp
 * @subpackage hvp
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp;

/**
 * Class content_user_data handles user data operations.
 *
 * @package mod_hvp
 */
class Content_User_Data {

    public static function save_user_data($content_id, $data_id, $sub_content_id) {
        global $DB, $USER;

        if ($content_id === NULL ||
            $data_id === NULL ||
            $sub_content_id === NULL) {
            return; // Missing parameters
        }

        $response = (object) array(
            'success' => TRUE
        );

        // Wash values to ensure 0 or 1.
        $pre_load = 1;
        $invalidate = 1;

        // Determine if we should update or insert
        $update = $DB->get_record('hvp_content_user_data', array(
                'user_id' => $USER->id,
                'hvp_id' => $content_id,
                'sub_content_id' => $sub_content_id,
                'data_id' => $data_id
            )
        );

        // New data to be inserted
        $new_data = (object)array(
            'user_id' => $USER->id,
            'hvp_id' => $content_id,
            'sub_content_id' => $sub_content_id,
            'data_id' => $data_id,
            'preloaded' => $pre_load,
            'delete_on_content_change' => $invalidate
        );

        // Does not exist
        if ($update === FALSE) {
            // Insert new data
            $DB->insert_record('hvp_content_user_data', $new_data);
        }
        else {
            // Get old data id
            $new_data->id = $update->id;

            // Update old data
            $DB->update_record('hvp_content_user_data', $new_data);
        }

        print json_encode($response);
        exit;
    }
}
