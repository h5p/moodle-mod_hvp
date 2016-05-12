<?php

/**
 * The mod_hvp user grades
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_hvp;
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
            exit;
        }

        // Content parameters
        $content_id = required_param('contentId', PARAM_INT);
        $score = required_param('score', PARAM_INT);
        $max_score = required_param('maxScore', PARAM_INT);

        // Time values not usable by gradebook
        // $opened = required_param('opened', PARAM_INT);
        // $finished = required_param('finished', PARAM_INT);

        // Get hvp data from contentId
        $hvp = $DB->get_record('hvp', array('id' => $content_id));

        // Check permissions
        $context = \context_course::instance($hvp->course);
        if (!has_capability('mod/hvp:saveresults', $context)) {
            \H5PCore::ajaxError(get_string('nopermissiontosaveresult', 'hvp'));
            http_response_code(403);
            exit;
        }

        // Create grade object and set grades
        $grade = (object) array(
            'userid' => $USER->id,
            'rawgrade' => $score,
        );

        // Get course module id from db, required for grade item
        $cm_id_sql = "SELECT cm.id, h.name
            FROM {course_modules} cm, {hvp} h, {modules} m
            WHERE cm.instance = h.id AND h.id = ? AND m.name = 'hvp' AND m.id = cm.module";
        $result = $DB->get_record_sql($cm_id_sql, array($content_id));

        // Set grade using Gradebook API
        $hvp->cmidnumber = $result->id;
        $hvp->name = $result->name;
        $hvp->rawgrademax = $max_score;
        hvp_grade_item_update($hvp, $grade);

        // Get content info for log
        $content = $DB->get_record_sql(
                "SELECT c.name AS title, l.machine_name AS name, l.major_version, l.minor_version
                   FROM {hvp} c
                   JOIN {hvp_libraries} l ON l.id = c.main_library_id
                  WHERE c.id = ?",
                array($content_id)
        );

        // Log view
        new \mod_hvp\event(
                'results', 'set',
                $content_id, $content->title,
                $content->name, $content->major_version . '.' . $content->minor_version
        );

        \H5PCore::ajaxSuccess();
        exit;
    }
}
