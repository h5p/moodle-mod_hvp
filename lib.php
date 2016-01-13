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

/**
 * Checks whether hvp supports a specified feature.
 *
 * @param string $feature Feature code (FEATURE_xx constant)
 * @return mixed Feature result (true if supported, false if not, null if unknown feature)
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
 * Create a hvp instance, executed when user creates a hvp instance.
 *
 * @param object $moduleinfo the module data
 * @return int $cmid Course module instance id (id of 'hvp' table)
 */
function hvp_add_instance($moduleinfo) {
    $disable_settings = array(
        H5PCore::$disable[H5PCore::DISABLE_FRAME] => isset($moduleinfo->frame) ? $moduleinfo->frame: 0,
        H5PCore::$disable[H5PCore::DISABLE_DOWNLOAD] => isset($moduleinfo->download) ? $moduleinfo->download : 0,
        H5PCore::$disable[H5PCore::DISABLE_COPYRIGHT] => isset($moduleinfo->copyright) ? $moduleinfo->copyright: 0
    );

    $core = hvp_get_instance('core');
    $default_disable_value = 0;
    $disable_value = $core->getDisable($disable_settings, $default_disable_value);

    $cmcontent = array(
        'name' => $moduleinfo->name,
        'course' => $moduleinfo->course,
        'disable' => $disable_value
    );

    $h5pStorage = hvp_get_instance('storage');
    $h5pStorage->savePackage($cmcontent);

    return $h5pStorage->contentId;
}

  /**
   * Update a hvp instance, executed when user has edited a hvp instance.
   *
   * @param object $hvp Hvp instance
   */
function hvp_update_instance($hvp) {
    $disable_settings = array(
        H5PCore::$disable[H5PCore::DISABLE_FRAME] => isset($hvp->frame) ? $hvp->frame: 0,
        H5PCore::$disable[H5PCore::DISABLE_DOWNLOAD] => isset($hvp->download) ? $hvp->download: 0,
        H5PCore::$disable[H5PCore::DISABLE_COPYRIGHT] => isset($hvp->copyright) ? $hvp->copyright: 0
    );

    $core = hvp_get_instance('core');
    $default_disable_value = 0;
    $disable_value = $core->getDisable($disable_settings, $default_disable_value);

    // Updated $hvp values used in $DB
    $hvp->disable = $disable_value;
    $hvp->id = $hvp->instance;

    $h5pStorage = hvp_get_instance('storage');
    $h5pStorage->savePackage((array)$hvp);
}

  /**
   * Delete a hvp instance, executed when user has deleted a hvp instance.
   *
   * @param int $id Hvp instance id
   * @return bool $result Success
   */
function hvp_delete_instance($id) {
    global $DB;

    if (! $hvp = $DB->get_record('hvp', array('id' => "$id"))) {
        return false;
    }

    $result = true;
    $h5pStorage = hvp_get_instance('storage');
    $h5pStorage->deletePackage(array('id' => $hvp->id, 'slug' => $hvp->slug));

    if (! $DB->delete_records('hvp', array('id' => "$hvp->id"))) {
        $result = false;
    }

    debugging('Deleted h5p ' . $hvp->id . ': ' . $result, DEBUG_DEVELOPER);
    return $result;
}

/**
 * Serve files that belongs to the plugin
 * TODO: Doc hook
 */
function hvp_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, $options = array()) {
  switch ($filearea) {
    default:
      return false; // Invalid file area

    case 'libraries':
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
