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
        'postUserStatistics' => FALSE, // TODO: Add when grades are implemented
        'ajaxPath' => $ajaxPath,
        'ajax' => array(
            'contentUserData' => $ajaxPath . 'contents_user_data&content_id=:contentId&data_type=:dataType&sub_content_id=:subContentId'
        ),
        'saveFreq' => get_config('hvp', 'enable_save_content_state') ? get_config('hvp', 'content_state_frequency') : FALSE,
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
    $cache_buster = '?ver=1'; // TODO: . get_component_version('mod_hvp'); ?

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
