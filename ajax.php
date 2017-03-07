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
require_once($CFG->libdir . '/filelib.php');
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

        // Because of a confirmed bug in PHP, filter_input(INPUT_SERVER, ...) will return null on some versions of FCGI/PHP (5.4 and probably older versions as well), ref. https://bugs.php.net/bug.php?id=49184
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    /**
     * Load content type cache list to display available libraries in hub
     */
    case 'contenttypecache':
        global $DB;

        header('Cache-Control: no-cache');
        header('Content-type: application/json');

        // Update content type cache if enabled and too old
        $core = \mod_hvp\framework::instance('core');

        // Check if hub is enabled
        if (!$core->h5pF->getOption('hub_is_enabled', TRUE)) {
            http_response_code(403);
            $core::ajaxError(
                $core->h5pF->t('The hub is disabled. You can re-enable it in the H5P settings.'),
                'HUB_DISABLED'
            );
            break;
        }

        $ct_cache_last_update = $core->h5pF->getOption('content_type_cache_updated_at', 0);
        $outdated_cache = $ct_cache_last_update + (60 * 60 * 24 * 7); // 1 week
        if (time() > $outdated_cache) {
            $success = $core->updateContentTypeCache();
            if (!$success) {
                http_response_code(404);
                $core::ajaxError(
                    $core->h5pF->t('Could not connect to the H5P Content Type Hub. Please try again later.'),
                    'NO_RESPONSE'
                );
                break;
            }
        }

        // Determine access
        $context = \context_system::instance();
        $caninstallany = has_capability('mod/hvp:updatelibraries', $context);
        $caninstallrecommended = has_capability('mod/hvp:installrecommendedh5plibraries', $context);


        // Get latest version of local libraries
        $max_major_version_sql = "
            SELECT hl.machine_name, MAX(hl.major_version) AS major_version
            FROM {hvp_libraries} hl
            WHERE hl.runnable = 1
            GROUP BY hl.machine_name";

        $max_minor_version_sql = "
            SELECT hl2.machine_name, hl2.major_version, MAX(hl2.minor_version) AS minor_version
            FROM ({$max_major_version_sql}) hl1
            JOIN {hvp_libraries} hl2
            ON hl1.machine_name = hl2.machine_name
            AND hl1.major_version = hl2.major_version
            GROUP BY hl2.machine_name";

        $local_libraries = $DB->get_records_sql("
            SELECT hl4.id AS library_id, hl4.machine_name, hl4.major_version,
                hl4.minor_version, hl4.patch_version, hl4.has_icon
            FROM {hvp_libraries} hl4
            JOIN ({$max_minor_version_sql}) hl3
            ON hl4.machine_name = hl3.machine_name
            AND hl4.major_version = hl3.major_version
            AND hl4.minor_version = hl3.minor_version
            GROUP BY hl4.machine_name");

        $cached_libraries = $DB->get_records("hvp_libraries_hub_cache");

        $libraries = array();
        foreach ($cached_libraries as &$result) {
            if ($caninstallany) {
                $result->restricted = FALSE;
            }
            elseif ($result->is_recommended && $caninstallrecommended) {
                $result->restricted = FALSE;
            }
            else {
                $result->restricted = TRUE;
            }

            $libraries[] = $core->getCachedLibsMap($result);
        }

        $core->mergeLocalLibsIntoCachedLibs($local_libraries, $libraries);

        http_response_code(200);
        print json_encode(array(
            'libraries' => $libraries
        ));
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

    /**
     * Handle file upload through the editor.
     *
     * Parameters:
     *  raw token
     *  raw contentTypeUrl
     */
    case 'libraryinstall':
        global $DB;

        // Require post to install
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            break;
        }

        // Verify token
        if (!\H5PCore::validToken('h5p_editor_ajax', required_param('token', PARAM_RAW))) {
            \H5PCore::ajaxError(get_string('invalidtoken', 'hvp'), 'INVALID_TOKEN');
            break;
        }

        // Determine which content type to install
        $name = required_param('id', PARAM_RAW);
        if (!$name) {
            H5PCore::ajaxError(get_string('nocontenttype', 'hvp'), 'NO_CONTENT_TYPE');
            break;
        }

        // Look up content type to ensure it's valid(and to check permissions)
        $content_type = $DB->get_record_sql(
                "SELECT id, is_recommended
                   FROM {hvp_libraries_hub_cache}
                  WHERE machine_name = ?",
                array($name)
        );
        if (!$content_type) {
            H5PCore::ajaxError(get_string('invalidcontenttype', 'hvp'), 'INVALID_CONTENT_TYPE');
            break;
        }

        // Check if the user has access to install or update content types
        $context = \context_system::instance();
        $caninstallany = has_capability('mod/hvp:updatelibraries', $context);
        $caninstallrecommended = has_capability('mod/hvp:installrecommendedh5plibraries', $context);
        if (!$caninstallany && !$caninstallrecommended) {
            H5PCore::ajaxError(get_string('installdenied', 'hvp'), 'INSTALL_DENIED');
            break;
        }

        if (!$caninstallany && $caninstallrecommended) {
            // Override core permission check
            $core = \mod_hvp\framework::instance('core');
            $core->mayUpdateLibraries(TRUE);
        }

        // Get content type url
        $protocol = (extension_loaded('openssl') ? 'https' : 'http');
        $endpoint = H5PCore::$hubEndpoints[H5PCore::CONTENT_TYPES];

        // Generate local tmp file path
        $local_folder = $CFG->tempdir . uniqid('/hvp-');
        $local_file   = $local_folder . '.h5p';

        if (!\download_file_content("{$protocol}://{$endpoint}{$name}", NULL, NULL, FALSE, 300, 20, FALSE, $local_file)) {
            H5PCore::ajaxError(get_string('downloadfailed', 'hvp'), 'DOWNLOAD_FAILED');
            break;
        }

        // Add folder and file paths to H5P Core
        $interface = \mod_hvp\framework::instance('interface');
        $interface->getUploadedH5pFolderPath($local_folder);
        $interface->getUploadedH5pPath($local_file);

        // Validate package
        $h5pValidator = \mod_hvp\framework::instance('validator');
        if (!$h5pValidator->isValidPackage(TRUE)) {
            @unlink($local_file);
            $errors = \mod_hvp\framework::messages('error');
            if (empty($errors)) {
                $errors = get_string('validationfailed', 'hvp');
            }
            H5PCore::ajaxError($errors, 'VALIDATION_FAILED');
            break;
        }

        // Install H5P file into Moodle
        $storage = \mod_hvp\framework::instance('storage');
        $storage->savePackage(NULL, NULL, TRUE);

        // Successfully installed.
        H5PCore::ajaxSuccess();
        break;

    /**
     * Install libraries from h5p and retrieve content json
     *
     * Parameters:
     *  file h5p
     */
    case 'libraryupload':

        // Require post to upload/install h5ps
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            break;
        }

        // Verify token
        if (!\H5PCore::validToken('h5p_editor_ajax', required_param('token', PARAM_RAW))) {
            \H5PCore::ajaxError(get_string('invalidtoken', 'hvp'), 'INVALID_TOKEN');
            break;
        }

        // Verify h5p upload
        if (!$_FILES['h5p']) {
            H5PCore::ajaxError(get_string('invalidh5ppost', 'hvp'), 'NO_CONTENT_TYPE');
            exit;
        }

        // Generate local tmp file path
        $local_folder = $CFG->tempdir . uniqid('/hvp-');
        $local_file   = $local_folder . '.h5p';

        // Move so core can validate the H5P
        move_uploaded_file($_FILES['h5p']['tmp_name'], $local_file);

        // Add folder and file paths to H5P Core
        $interface = \mod_hvp\framework::instance('interface');
        $interface->getUploadedH5pFolderPath($local_folder);
        $interface->getUploadedH5pPath($local_file);

        // Validate package
        $h5pValidator = \mod_hvp\framework::instance('validator');
        if (!$h5pValidator->isValidPackage()) {
            @unlink($local_file);
            $errors = \mod_hvp\framework::messages('error');
            if (empty($errors)) {
                $errors = get_string('validationfailed', 'hvp');
            }
            H5PCore::ajaxError($errors, 'VALIDATION_FAILED');
            break;
        }

        // Install H5P file into Moodle
        $storage = \mod_hvp\framework::instance('storage');
        $storage->savePackage(NULL, NULL, TRUE);

        // Retrieve json
        $json = file_get_contents($local_folder . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'content.json');

        // clean up
        @unlink($local_folder);

        // Successfully installed.
        H5PCore::ajaxSuccess($json);
        break;

    /*
     * Throw error if AJAX isnt handeled
     */
    default:
        throw new coding_exception('Unhandled AJAX');
        break;
}
