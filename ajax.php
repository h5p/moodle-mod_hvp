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
 * Responsible for handling AJAX requests related to H5P.
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS <contact@joubel.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('AJAX_SCRIPT', true);
require(__DIR__ . '/../../config.php');
require_once("locallib.php");

$action = required_param('action', PARAM_ALPHA);
switch($action) {

    /**
     * Handle user data reporting
     *
     * Type: HTTP POST
     *
     * Parameters:
     * 	- content_id
     *  - data_type
     *  - sub_content_id
     */
    case 'contentsuserdata':
        \mod_hvp\content_user_data::handle_ajax();
        break;

    /**
     * Handle restricting H5P libraries
     *
     * Type: HTTP GET
     *
     * Parameters:
     * 	- library_id
     *  - restrict (0 or 1)
     *  - token
     */
    case 'restrictlibrary':
        require_once ($CFG->dirroot . '/mod/hvp/locallib.php');

        // TODO - check permissions
        $library_id = required_param('library_id', PARAM_INT);
        $restrict = required_param('restrict', PARAM_INT);
        $token = required_param('token', PARAM_ALPHANUMEXT);

        if (hvp_verify_token('library_' . $library_id, $token)) {
            hvp_restrict_library($library_id, $restrict);
            header('Cache-Control', 'no-cache');
            header('Content-Type: application/json');
            echo json_encode(array(
                'url' => (new moodle_url('/mod/hvp/ajax.php', array(
                    'action' => 'restrict_library',
                    'token' => hvp_get_token('library_' . $library_id),
                    'restrict' => ($restrict === '1' ? 0 : 1),
                    'library_id' => $library_id
                )))->out(false)));
        }
        else {
            http_response_code(403);
        }
        break;

    /**
     * Collecting data needed by H5P content upgrade
     *
     * Type: HTTP GET
     *
     * Parameters:
     * 	- library (Format: /<machine-name>/<major-version>/<minor-version>)
     */
    case 'getlibrarydataforupgrade':
        $library = required_param('library', PARAM_TEXT);
        $library = explode('/', substr($library, 1));

        if (sizeof($library) !== 3) {
            http_response_code(422);
            return;
        }

        $library = hvp_get_library_upgrade_info($library[0], $library[1], $library[2]);

        header('Cache-Control', 'no-cache');
        header('Content-Type: application/json');
        print json_encode($library);

        break;

    /**
     * Saving upgraded content, and returning next batch to process
     *
     * Type: HTTP POST
     *
     * Parameters:
     * 	- library_id
     */
    case 'libraryupgradeprogress':
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
            $library_id = required_param('library_id', PARAM_INT);
            $out = hvp_content_upgrade_progress($library_id);
            header('Cache-Control', 'no-cache');
            header('Content-Type: application/json');
            print json_encode($out);
        }
        else {
            // Only allow POST
            http_response_code(405);
        }
        break;

    /**
     * Handle set finished / storing grades
     *
     * Type: HTTP GET
     *
     * Parameters:
     * 	- contentId
     * 	- score
     * 	- maxScore
     */
    case 'setFinished':
        \mod_hvp\user_grades::handle_ajax();
        break;

    default:
        throw new coding_exception('Unhandled AJAX');
        break;
}
