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

        // Content parameters
        $content_id = required_param('contentId', PARAM_INT);
        $score = required_param('score', PARAM_INT);
        $max_score = required_param('maxScore', PARAM_INT);

        // Time values not usable by gradebook
        //$opened = required_param('opened', PARAM_INT);
        //$finished = required_param('finished', PARAM_INT);

        // Get hvp data from contentId
        $hvp = $DB->get_record('hvp', array('id' => $content_id));

        // Create grade object and set grades
        $grade = (object) array(
            'userid' => $USER->id,
            'rawgrade' => $score,
        );

        // Get course module id from db, required for grade item
        $cm_id_sql = 'SELECT cm.id, h.name
            FROM {course_modules} cm, {hvp} h, {modules} m
            WHERE cm.instance = h.id AND h.id = ? AND m.name = "hvp" AND m.id = cm.module';
        $result = $DB->get_record_sql($cm_id_sql, array($content_id));

        // Set grade using Gradebook API
        $hvp->cmidnumber = $result->id;
        $hvp->name = $result->name;
        $hvp->rawgrademax = $max_score;
        $gradeResult = hvp_grade_item_update($hvp, $grade);

        print json_encode($gradeResult);
        exit;
    }
}
