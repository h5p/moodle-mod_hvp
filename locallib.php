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
 * Internal library of functions for module hvp
 *
 * All the hvp specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS <contact@joubel.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Get array with settings for hvp core
 *
 * @return array Settings
 */
function hvp_get_core_settings() {
    global $USER, $CFG, $COURSE;

    $basePath = $CFG->sessioncookiepath;
    $ajaxPath = $basePath . 'mod/hvp/ajax.php?action=';

    $system_context = \context_system::instance();
    $course_context = \context_course::instance($COURSE->id);
    $settings = array(
        'baseUrl' => $basePath,
        'url' => "{$basePath}pluginfile.php/{$course_context->id}/mod_hvp",
        'libraryUrl' => "{$basePath}pluginfile.php/{$system_context->id}/mod_hvp/libraries",
        'postUserStatistics' => TRUE,
        'ajaxPath' => $ajaxPath,
        'ajax' => array(
            'contentUserData' => $ajaxPath . 'contents_user_data&content_id=:contentId&data_type=:dataType&sub_content_id=:subContentId'
        ),
        'saveFreq' => get_config('mod_hvp', 'enable_save_content_state') ? get_config('mod_hvp', 'content_state_frequency') : FALSE,
        'siteUrl' => $CFG->wwwroot,
        'l10n' => array(
            'H5P' => array(
                'fullscreen' => get_string('fullscreen', 'hvp'),
                'disableFullscreen' => get_string('disablefullscreen', 'hvp'),
                'download' => get_string('download', 'hvp'),
                'copyrights' => get_string('copyright', 'hvp'),
                'copyrightInformation' => get_string('copyright', 'hvp'),
                'close' => get_string('close', 'hvp'),
                'title' => get_string('title', 'hvp'),
                'author' => get_string('author', 'hvp'),
                'year' => get_string('year', 'hvp'),
                'source' => get_string('source', 'hvp'),
                'license' => get_string('license', 'hvp'),
                'thumbnail' => get_string('thumbnail', 'hvp'),
                'noCopyrights' =>  get_string('nocopyright', 'hvp'),
                'downloadDescription' => get_string('downloadtitle', 'hvp'),
                'copyrightsDescription' => get_string('copyrighttitle', 'hvp'),
                'h5pDescription' => get_string('h5ptitle', 'hvp'),
                'contentChanged' => get_string('contentchanged', 'hvp'),
                'startingOver' => get_string('startingover', 'hvp')
            )
        ),
        'user' => array(
            'name' => $USER->firstname . ' ' . $USER->lastname,
            'mail' => $USER->email
        )
    );

    return $settings;
}

/**
 * Get assets (scripts and styles) for hvp core.
 *
 * @return array
 */
function hvp_get_core_assets() {
    global $CFG, $PAGE;

    // Get core settings
    $settings = hvp_get_core_settings();
    $settings['core'] = array(
        'styles' => array(),
        'scripts' => array()
    );
    $settings['loadedJs'] = array();
    $settings['loadedCss'] = array();

    // Make sure files are reloaded for each plugin update
    $cache_buster = hvp_get_cache_buster();

    // Use relative URL to support both http and https.
    $lib_url = $CFG->httpswwwroot . '/mod/hvp/library/';
    $rel_path = '/' . preg_replace('/^[^:]+:\/\/[^\/]+\//', '', $lib_url);

    // Add core stylesheets
    foreach (H5PCore::$styles as $style) {
        $settings['core']['styles'][] = $rel_path . $style . $cache_buster;
        $PAGE->requires->css(new moodle_url($lib_url . $style . $cache_buster));
    }
    // Add core JavaScript
    foreach (H5PCore::$scripts as $script) {
        $settings['core']['scripts'][] = $rel_path . $script . $cache_buster;
        $PAGE->requires->js(new moodle_url($lib_url . $script . $cache_buster), true);
    }

    return $settings;
}

/**
 * Add core JS and CSS to page.
 *
 * @param moodle_page $page
 * @param moodle_url|string $lib_url
 * @param array|null $settings
 * @throws \coding_exception
 */
function hvp_admin_add_generic_css_and_js($page, $lib_url, $settings = NULL) {
    foreach (H5PCore::$adminScripts as $script) {
        $page->requires->js(new moodle_url($lib_url . $script . hvp_get_cache_buster()), true);
    }

    if ($settings === NULL) {
        $settings = array();
    }

    $settings['containerSelector'] = '#h5p-admin-container';
    $settings['l10n'] = array(
        'NA' => get_string('notapplicable', 'hvp'),
        'viewLibrary' => '',
        'deleteLibrary' => '',
        'upgradeLibrary' => get_string('upgradelibrarycontent', 'hvp')
    );

    $page->requires->data_for_js('H5PAdminIntegration', $settings, true);
    $page->requires->css(new moodle_url($lib_url . 'styles/h5p.css' . hvp_get_cache_buster()));
    $page->requires->css(new moodle_url($lib_url . 'styles/h5p-admin.css' . hvp_get_cache_buster()));

    // Add settings:
    $page->requires->data_for_js('h5p', hvp_get_core_settings(), true);
}

/**
 * Get a query string with the plugin version number to include at the end
 * of URLs. This is used to force the browser to reload the asset when the
 * plugin is updated.
 *
 * @return string
 */
function hvp_get_cache_buster() {
    return '?ver=' . get_config('mod_hvp', 'version');
}

/**
 * Get a new H5P security token.
 *
 * @param string $key
 * @return string
 */
function hvp_get_token($key) {
    return $_SESSION['h5p_' . $key] = uniqid('h5p-');
}

/**
 * Verifiy a given H5P security token.
 *
 * @param string $key
 * @param string $token
 * @return string
 */
function hvp_verify_token($key, $token) {
    return $_SESSION['h5p_' . $key] === $token;
}

/**
 * Restrict access to a given content type.
 *
 * @param int $library_id
 * @param bool $restrict
 */
function hvp_restrict_library($library_id, $restrict) {
  global $DB;
  $DB->update_record('hvp_libraries', (object) array(
    'id' => $library_id,
    'restricted' => $restrict ? 1 : 0
  ));
}

/**
 * Handle content upgrade progress
 *
 * @method hvp_content_upgrade_progress
 * @param  int $library_id
 * @return object An object including the json content for the H5P instances
 *                (maximum 40) that should be upgraded.
 */
function hvp_content_upgrade_progress($library_id) {
    global $DB;

    $to_library_id = filter_input(INPUT_POST, 'libraryId');

    // Verify security token
    if (!hvp_verify_token('content_upgrade', filter_input(INPUT_POST, 'token'))) {
        print get_string('upgradeinvalidetoken', 'hvp');
        return;
    }

    // Get the library we're upgrading to
    $to_library = $DB->get_record('hvp_libraries', array(
        'id' => $to_library_id
    ));
    if (!$to_library) {
        print get_string('upgradelibrarymissing', 'hvp');
        return;
    }

    // Prepare response
    $out = new stdClass();
    $out->params = array();
    $out->token = hvp_get_token('content_upgrade');

    // Prepare our interface
    $interface = \mod_hvp\framework::instance('interface');

    // Get updated params
    $params = filter_input(INPUT_POST, 'params');
    if ($params !== NULL) {
        // Update params.
        $params = json_decode($params);
        foreach ($params as $id => $param) {
            $DB->update_record('hvp', (object) array(
                'id' => $id,
                'main_library_id' => $to_library->id,
                'json_content' => $param,
                'filtered' => ''
            ));
        }
    }

    // Get number of contents for this library
    $out->left = $interface->getNumContent($library_id);

    if ($out->left) {
        // Find the 40 first contents using this library version and add to params
        $results = $DB->get_records_sql(
            "SELECT id, json_content as params
               FROM {hvp}
              WHERE main_library_id = ?
           ORDER BY name ASC", array($library_id), 0 , 40
        );

        foreach ($results as $content) {
            $out->params[$content->id] = $content->params;
        }
    }

    return $out;
}

/**
 * Gets the information needed when content is upgraded
 *
 * @method hvp_get_library_upgrade_info
 * @param  string $name
 * @param  int $major
 * @param  int $minor
 * @return object Library metadata including name, version, semantics and path
 *                to upgrade script
 */
function hvp_get_library_upgrade_info($name, $major, $minor) {
    global $CFG;

    $library = (object) array(
        'name' => $name,
        'version' => (object) array(
            'major' => $major,
            'minor' => $minor
        )
    );

    $core = \mod_hvp\framework::instance();

    $library->semantics = $core->loadLibrarySemantics($library->name, $library->version->major, $library->version->minor);
    if ($library->semantics === NULL) {
        http_response_code(404);
        return;
    }

    $context = \context_system::instance();
    $libraryFolderName = "{$library->name}-{$library->version->major}.{$library->version->minor}";
    if (\mod_hvp\file_storage::fileExists($context->id, 'libraries', '/' . $libraryFolderName . '/', 'upgrades.js')) {
        $basePath = $CFG->sessioncookiepath;
        $library->upgradesScript = "{$basePath}pluginfile.php/{$context->id}/mod_hvp/libraries/{$libraryFolderName}/upgrades.js";
    }

    return $library;
}
