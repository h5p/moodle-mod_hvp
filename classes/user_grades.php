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
 * The mod_hvp user grades
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_hvp;

defined('MOODLE_INTERNAL') || die();

require(__DIR__ . '/../lib.php');

/**
 * Handles grade storage for users
 * @package mod_hvp
 */
class user_grades {

    public static function handle_ajax() {
        global $DB, $USER;

        if (!\H5PCore::validToken('result', required_param('token', PARAM_RAW))) {
            \H5PCore::ajaxError(get_string('invalidtoken', 'hvp'));
            return;
        }

        // Content parameters.
        $contentid = required_param('contentId', PARAM_INT);
        $score = required_param('score', PARAM_INT);
        $maxscore = required_param('maxScore', PARAM_INT);

        // Get hvp data from contentId.
        $hvp = $DB->get_record('hvp', array('id' => $contentid));

        // Check permissions.
        $context = \context_course::instance($hvp->course);
        if (!has_capability('mod/hvp:saveresults', $context)) {
            \H5PCore::ajaxError(get_string('nopermissiontosaveresult', 'hvp'));
            http_response_code(403);
            return;
        }

        // Create grade object and set grades.
        $grade = (object) array(
            'userid' => $USER->id
        );

        // Get course module id from db, required for grade item.
        $cmidsql = "SELECT cm.id, h.name, cm.idnumber
            FROM {course_modules} cm, {hvp} h, {modules} m
            WHERE cm.instance = h.id AND h.id = ? AND m.name = 'hvp' AND m.id = cm.module";
        $result = $DB->get_record_sql($cmidsql, array($contentid));

        // Set grade using Gradebook API.
        $hvp->cmidnumber = $result->idnumber;
        $hvp->name = $result->name;
        $hvp->rawgrade = $score;
        $hvp->rawgrademax = $maxscore;
        hvp_grade_item_update($hvp, $grade);

        // Get content info for log.
        $content = $DB->get_record_sql(
                "SELECT c.name AS title, l.machine_name AS name, l.major_version, l.minor_version
                   FROM {hvp} c
                   JOIN {hvp_libraries} l ON l.id = c.main_library_id
                  WHERE c.id = ?",
                array($contentid)
        );

        // Log view.
        new \mod_hvp\event(
                'results', 'set',
                $contentid, $content->title,
                $content->name, $content->major_version . '.' . $content->minor_version
        );

        \H5PCore::ajaxSuccess();
    }
}
