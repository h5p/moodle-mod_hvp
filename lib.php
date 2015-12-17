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
 * @package    mod
 * @subpackage hvp
 * @copyright  2013 Amendor
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once ($CFG->dirroot . '/mod/hvp/hvp.php');

function hvp_supports($feature) {
    switch($feature) {
        case FEATURE_GROUPS:                  return true;
        case FEATURE_GROUPINGS:               return true;
        case FEATURE_GROUPMEMBERSONLY:        return true;
        case FEATURE_MOD_INTRO:               return false;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return false;
        case FEATURE_COMPLETION_HAS_RULES:    return false;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_BACKUP_MOODLE2:          return false;
        case FEATURE_SHOW_DESCRIPTION:        return false;

        default: return null;
    }
}

/**
 * Implements Moodle's MODULENAME_add_instance()
 *
 * When a new activity is added on a course, Moodle creates course module,
 * module instance, add the module to the correct section. Moodle calls
 * this function to create the module instance.
 *
 * @param object $moduleinfo the module data
 * @return int $cmid Course module instance id (id of 'hvp' table)
 */
function hvp_add_instance($moduleinfo) {
    global $DB;

    // Moodle expects the MODULENAME_add_instance() to return the id, so we
    // need to save the data here manually because savePackage() does not
    // return the id.
    $cmid = $DB->insert_record('hvp', (object) array(
        'name' => $moduleinfo->name,
        'course' => $moduleinfo->course,
        'json_content' => '',
        'main_library_id' => '',
    ));

    $h5pStorage = hvp_get_instance('storage');
    $h5pStorage->savePackage(array('id' => $cmid));

    return $cmid;
}

function hvp_update_instance($hvp) {
    global $DB;

    $hvp->id = $hvp->instance;
    $result = $DB->update_record('hvp', $hvp);

    $h5pStorage = hvp_get_instance('storage');
    $library_updated = $h5pStorage->updatePackage($hvp->id);

    return $result;
}

function hvp_delete_instance($id) {
    global $DB;

    if (! $hvp = $DB->get_record('hvp', array('id' => "$id"))) {
        return false;
    }

    $result = true;

    $h5pStorage = hvp_get_instance('storage');
    $h5pStorage->deletePackage($hvp->id);

    if (! $DB->delete_records('hvp', array('id' => "$hvp->id"))) {
        $result = false;
    }

    debugging('Deleted h5p ' . $hvp->id . ': ' . $result, DEBUG_DEVELOPER);
    return $result;
}

function hvp_get_hvp($hvpid) {
    global $DB;

    $hvp = $DB->get_record_sql('
      SELECT
        h.id,
        h.name,
        h.json_content,
        h.embed_type,
        h.main_library_id,
        hl.machine_name,
        hl.major_version,
        hl.minor_version,
        hl.embed_types,
        hl.fullscreen
      FROM {hvp} h
      JOIN {hvp_libraries} hl ON hl.id = h.main_library_id
      WHERE h.id = ?', array($hvpid));

    if ($hvp) {
        return $hvp;
    }

    return false;
}

function hvp_get_file_paths($hvp) {
    global $CFG, $DB;

    $filepaths = array(
        'preloadedJs' => array(),
        'preloadedCss' => array(),
    );

    $libraries = $DB->get_records_sql(
      'SELECT
        hl.id,
        hl.machine_name,
        hl.major_version,
        hl.minor_version,
        hl.preloaded_css,
        hl.preloaded_js,
        hcl.drop_css
      FROM {hvp_contents_libraries} hcl
      JOIN {hvp_libraries} hl ON hcl.library_id = hl.id
      WHERE hcl.id = ?', array($hvp->id));

    $path = '/mod/hvp/files';
    $h5pcore = hvp_get_instance('core');
    foreach ($libraries as $library) {
        // core only supports assoc. arrays.
        $librarydata = array(
          'machineName' => $library->machine_name,
          'majorVersion' => $library->major_version,
          'minorVersion' => $library->minor_version,
        );
        if (!empty($library->preloaded_js)) {
            foreach (explode(',', $library->preloaded_js) as $scriptpath) {
                $filepaths['preloadedJs'][] = $path . '/libraries/' . $h5pcore->libraryToString($librarydata, TRUE) . '/' . trim($scriptpath);
            }
        }
        if (!empty($library->preloaded_css) && !$library->drop_css) {
            foreach (explode(',', $library->preloaded_css) as $stylepath) {
                $filepaths['preloadedCss'][] = $path . '/libraries/' . $h5pcore->libraryToString($librarydata, TRUE) . '/' . trim($stylepath);
            }
        }

    }

    return $filepaths;
}

/**
 * TODO: Document
 */
function hvp_get_core_settings() {
  global $USER, $CFG;

  $basePath = '/'; // TODO: Unsure about this, can we get it from somewhere?
  $ajaxPath = $basePath . 'mod/hvp/ajax.php?action=';

  $settings = array(
    'baseUrl' => $basePath,
    'url' => $CFG->wwwroot,
    'postUserStatistics' => FALSE, // TODO: Add when grades are implemented
    'ajaxPath' => $ajaxPath,
    'ajax' => array(
      'contentUserData' => $ajaxPath . 'contents_user_data&content_id=:contentId&data_type=:dataType&sub_content_id=:subContentId'
    ),
    'saveFreq' => FALSE, // TODO: Add when user state settings are added
    'siteUrl' => $CFG->wwwroot,
    'l10n' => array(
      'H5P' => array(
        'fullscreen' => get_string('fullscreen', 'hvp'),
        'disableFullscreen' => get_string('disablefullscreen', 'hvp'),
        'download' => get_string('download', 'hvp'),
        'copyrights' => get_string('copyright', 'hvp'),
        'embed' => get_string('embed', 'hvp'),
        'size' => get_string('size', 'hvp'),
        'showAdvanced' => get_string('showadvanced', 'hvp'),
        'hideAdvanced' => get_string('hideadvanced', 'hvp'), // TODO: Remove embed func?
        'advancedHelp' => get_string('resizescript', 'hvp'),
        'copyrightInformation' => get_string('copyright', 'hvp'), // TODO: Why do we need this multiple times?
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
        'embedDescription' => get_string('embedtitle', 'hvp'),
        'h5pDescription' => get_string('h5ptitle', 'hvp'),
        'contentChanged' => get_string('contentchanged', 'hvp'),
        'startingOver' => get_string('startingover', 'hvp'),
        'user' => array(
          'name' => $USER->firstname . ' ' . $USER->lastname,
          'mail' => $USER->email
        )
      )
    )
  );

  return $settings;
}

/**
 * TODO: Document
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
  $lib_url = $CFG->wwwroot . '/mod/hvp/library/';
  $rel_path = '/' . preg_replace('/^[^:]+:\/\/[^\/]+\//', '', $lib_url);

  // Add core stylesheets
  foreach (H5PCore::$styles as $style) {
    $settings['core']['styles'][] = $rel_path . $style . $cache_buster;
    $PAGE->requires->css('/mod/hvp/library/' . $style);
  }
  // Add core JavaScript
  foreach (H5PCore::$scripts as $script) {
    $settings['core']['scripts'][] = $rel_path . $script . $cache_buster;
    $PAGE->requires->js('/mod/hvp/library/' . $script, true);
  }

  return $settings;
}
