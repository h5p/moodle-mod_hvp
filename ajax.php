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

    /*
     * Handle user data reporting
     *
     * Type: HTTP POST
     *
     * Parameters:
     *  - content_id
     *  - data_type
     *  - sub_content_id
     */
    case 'contentsuserdata':
        \mod_hvp\content_user_data::handle_ajax();
        break;

    /*
     * Handle restricting H5P libraries
     *
     * Type: HTTP GET
     *
     * Parameters:
     *  - library_id
     *  - restrict (0 or 1)
     *  - token
     */
    case 'restrictlibrary':

        // Check permissions
        $context = \context_system::instance();
        if (!has_capability('mod/hvp:restrictlibraries', $context)) {
            \H5PCore::ajaxError(get_string('nopermissiontorestrict', 'hvp'));
            http_response_code(403);
            break;
        }

        $library_id = required_param('library_id', PARAM_INT);
        $restrict = required_param('restrict', PARAM_INT);

        if (!\H5PCore::validToken('library_' . $library_id, required_param('token', PARAM_RAW))) {
            \H5PCore::ajaxError(get_string('invalidtoken', 'hvp'));
            exit;
        }

        hvp_restrict_library($library_id, $restrict);
        header('Cache-Control: no-cache');
        header('Content-Type: application/json');
        echo json_encode(array(
            'url' => (new moodle_url('/mod/hvp/ajax.php', array(
                'action' => 'restrict_library',
                'token' => \H5PCore::createToken('library_' . $library_id),
                'restrict' => ($restrict === '1' ? 0 : 1),
                'library_id' => $library_id
            )))->out(false)));
        break;

    /*
     * Collecting data needed by H5P content upgrade
     *
     * Type: HTTP GET
     *
     * Parameters:
     *  - library (Format: /<machine-name>/<major-version>/<minor-version>)
     */
    case 'getlibrarydataforupgrade':

        // Check permissions
        $context = \context_system::instance();
        if (!has_capability('mod/hvp:updatelibraries', $context)) {
            \H5PCore::ajaxError(get_string('nopermissiontoupgrade', 'hvp'));
            http_response_code(403);
            break;
        }

        $library = required_param('library', PARAM_TEXT);
        $library = explode('/', substr($library, 1));

        if (count($library) !== 3) {
            http_response_code(422);
            return;
        }

        $library = hvp_get_library_upgrade_info($library[0], $library[1], $library[2]);

        header('Cache-Control: no-cache');
        header('Content-Type: application/json');
        print json_encode($library);

        break;

    /*
     * Saving upgraded content, and returning next batch to process
     *
     * Type: HTTP POST
     *
     * Parameters:
     *  - library_id
     */
    case 'libraryupgradeprogress':
        // Check upgrade permissions
        $context = \context_system::instance();
        if (!has_capability('mod/hvp:updatelibraries', $context)) {
            \H5PCore::ajaxError(get_string('nopermissiontoupgrade', 'hvp'));
            http_response_code(403);
            break;
        }

        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
            $library_id = required_param('library_id', PARAM_INT);
            $out = hvp_content_upgrade_progress($library_id);
            header('Cache-Control: no-cache');
            header('Content-Type: application/json');
            print json_encode($out);
        } else {
            // Only allow POST.
            http_response_code(405);
        }
        break;

    /*
     * Handle set finished / storing grades
     *
     * Type: HTTP GET
     *
     * Parameters:
     *  - contentId
     *  - score
     *  - maxScore
     */
    case 'setfinished':
        \mod_hvp\user_grades::handle_ajax();
        break;

    /*
     * Provide data for results view
     *
     * Type: HTTP GET
     *
     * Parameters:
     *  int content_id
     *  int offset
     *  int limit
     *  int sortBy
     *  int sortDir
     *  string[] filters
     */
    case 'results':
        $results = new \mod_hvp\results();
        $results->print_results();
        break;

    /*
     * Load list of libraries or details for library.
     *
     * Parameters:
     *  string machineName
     *  int majorVersion
     *  int minorVersion
     */
    case 'libraries':
        /// Get parameters
        $name = optional_param('machineName', '', PARAM_TEXT);
        $major = optional_param('majorVersion', 0, PARAM_INT);
        $minor = optional_param('minorVersion', 0, PARAM_INT);

        $editor = \mod_hvp\framework::instance('editor');

        header('Cache-Control: no-cache');
        header('Content-type: application/json');

        if (!empty($name)) {
            print $editor->getLibraryData($name, $major, $minor, \mod_hvp\framework::get_language());
            new \mod_hvp\event(
                    'library', NULL,
                    NULL, NULL,
                    $name, $major . '.' . $minor
            );
        }
        else {
            print $editor->getLibraries();
        }

        break;

    /*
     * Handle file upload through the editor.
     *
     * Parameters:
     *  int contentId
     *  int contextId
     */
    case 'files':
        global $DB;
        // TODO: Check permissions

        if (!\H5PCore::validToken('editorajax', required_param('token', PARAM_RAW))) {
            \H5PCore::ajaxError(get_string('invalidtoken', 'hvp'));
            exit;
        }

        // Get Content ID and Context ID for upload
        $contentid = required_param('contentId', PARAM_INT);
        $contextid = required_param('contextId', PARAM_INT);

        // Create file
        $file = new H5peditorFile(\mod_hvp\framework::instance('interface'));
        if (!$file->isLoaded()) {
            H5PCore::ajaxError(get_string('filenotfound', 'hvp'));
            break;
        }

        // Make sure file is valid
        if ($file->validate()) {
            $core = \mod_hvp\framework::instance('core');
            // Save the valid file
            $file_id = $core->fs->saveFile($file, $contentid, $contextid);

            // Track temporary files for later cleanup
            $DB->insert_record_raw('hvp_tmpfiles', array(
                'id' => $file_id
            ), false, false, true);
        }

        $file->printResult();
        break;

    /*
     * Throw error if AJAX isnt handeled
     */
    default:
        throw new coding_exception('Unhandled AJAX');
        break;
}
