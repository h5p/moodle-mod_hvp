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
 
require_once ($CFG->dirroot . '/mod/hvp/h5p.php');

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

function hvp_add_instance($hvp) {
    global $DB;
    
    $hvp->id = $DB->insert_record('hvp', $hvp);
    
    $h5pCore = hvp_get_instance('storage');
    $library_updated = $h5pCore->savePackage($hvp->id);
  
    debugging('Added h5p ' . $hvp->id . ': ' . $library_updated , DEBUG_DEVELOPER);
    return $hvp->id;
}

function hvp_update_instance($hvp) {
    global $DB;

    $hvp->id = $hvp->instance;
    $result = $DB->update_record('hvp', $hvp);
    
    // TODO: Update other datas.
    
    debugging('Updated h5p ' . $hvp->id . ': ' . $result, DEBUG_DEVELOPER);
    return $result;
}

function hvp_delete_instance($id) {
    global $DB;

    if (! $hvp = $DB->get_record('hvp', array('id' => "$id"))) {
        return false;
    }

    $result = true;

    // TODO: Delete other datas.

    if (! $DB->delete_records('hvp', array('id' => "$hvp->id"))) {
        $result = false;
    }

    debugging('Deleted h5p ' . $hvp->id . ': ' . $result, DEBUG_DEVELOPER);
    return $result;
}
