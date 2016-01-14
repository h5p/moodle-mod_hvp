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
 * Library of interface functions and constants for module hvp.
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 *
 * All the hvp specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod_hvp
 * @copyright  2013 Amendor
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

 /* Moodle core API */

/**
 * Returns the information on whether the module supports a feature
 *
 * See {@link plugin_supports()} for more info.
 *
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
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
 * Saves a new instance of the hvp into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param stdClass $moduleinfo Submitted data from the form in mod_form.php
 * @return int The id of the newly inserted newmodule record
 */
function hvp_add_instance($moduleinfo) {
    $disable_settings = array(
        \H5PCore::$disable[\H5PCore::DISABLE_FRAME] => isset($moduleinfo->frame) ? $moduleinfo->frame: 0,
        \H5PCore::$disable[\H5PCore::DISABLE_DOWNLOAD] => isset($moduleinfo->download) ? $moduleinfo->download : 0,
        \H5PCore::$disable[\H5PCore::DISABLE_COPYRIGHT] => isset($moduleinfo->copyright) ? $moduleinfo->copyright: 0
    );

    $core = \mod_hvp\framework::instance();
    $default_disable_value = 0;
    $disable_value = $core->getDisable($disable_settings, $default_disable_value);

    $cmcontent = array(
        'name' => $moduleinfo->name,
        'course' => $moduleinfo->course,
        'disable' => $disable_value
    );

    $h5pStorage = \mod_hvp\framework::instance('storage');
    $h5pStorage->savePackage($cmcontent);

    return $h5pStorage->contentId;
}

/**
 * Updates an instance of the hvp in the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param stdClass $hvp An object from the form in mod_form.php
 * @return boolean Success/Fail
 */
function hvp_update_instance($hvp) {
    $disable_settings = array(
        \H5PCore::$disable[\H5PCore::DISABLE_FRAME] => isset($hvp->frame) ? $hvp->frame: 0,
        \H5PCore::$disable[\H5PCore::DISABLE_DOWNLOAD] => isset($hvp->download) ? $hvp->download: 0,
        \H5PCore::$disable[\H5PCore::DISABLE_COPYRIGHT] => isset($hvp->copyright) ? $hvp->copyright: 0
    );

    $core = \mod_hvp\framework::instance();
    $default_disable_value = 0;
    $disable_value = $core->getDisable($disable_settings, $default_disable_value);

    // Updated $hvp values used in $DB
    $hvp->disable = $disable_value;
    $hvp->id = $hvp->instance;

    $h5pStorage = \mod_hvp\framework::instance('storage');
    $h5pStorage->savePackage((array)$hvp);

    return TRUE;
}

/**
 * Removes an instance of the hvp from the database
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function hvp_delete_instance($id) {
    global $DB;

    if (! $hvp = $DB->get_record('hvp', array('id' => "$id"))) {
        return false;
    }

    $result = true;
    $h5pStorage = \mod_hvp\framework::instance('storage');
    $h5pStorage->deletePackage(array('id' => $hvp->id, 'slug' => $hvp->slug));

    if (! $DB->delete_records('hvp', array('id' => "$hvp->id"))) {
        $result = false;
    }

    return $result;
}

/**
 * Serves the files from the hvp file areas
 *
 * @package mod_hvp
 * @category files
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the newmodule's context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 */
function hvp_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, $options = array()) {
    switch ($filearea) {
        default:
            return false; // Invalid file area

        case 'libraries':
        case 'cachedassets':
            if ($context->contextlevel != CONTEXT_SYSTEM) {
              return false; // Invalid context
            }

            // TODO: Check permissions?

            $itemid = 0;
            break;

        case 'content':
            if ($context->contextlevel != CONTEXT_COURSE) {
              return false; // Invalid context
            }

            // TODO: Check permissions?

            $itemid = array_shift($args);
            break;

        case 'exports':
            if ($context->contextlevel != CONTEXT_COURSE) {
              return false; // Invalid context
            }

            // TODO: Check permissions?

            $itemid = 0;
            break;
    }

    $filename = array_pop($args);
    $filepath = (!$args ? '/' : '/' .implode('/', $args) . '/');

    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'mod_hvp', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false; // No such file
    }

    send_stored_file($file, 86400, 0, $forcedownload, $options);
}
