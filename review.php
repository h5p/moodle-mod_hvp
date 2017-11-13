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
 * View all results for H5P Content
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS <contact@joubel.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../config.php');
require_once("locallib.php");
global $USER, $PAGE, $DB, $CFG, $OUTPUT, $COURSE;

$id     = required_param('id', PARAM_INT);
$userid = optional_param('user', (int) $USER->id, PARAM_INT);

if (!$cm = get_coursemodule_from_instance('hvp', $id)) {
    print_error('invalidcoursemodule');
}
if (!$course = $DB->get_record('course', ['id' => $cm->course])) {
    print_error('coursemisconf');
}
require_login($course, false, $cm);

// Check permission.
$context = \context_module::instance($cm->id);
hvp_require_view_results_permission($userid, $context, $cm->id);

// Load H5P Content.
$hvp = $DB->get_record_sql(
    "SELECT h.id,
                h.name AS title,
                hl.machine_name,
                hl.major_version,
                hl.minor_version
           FROM {hvp} h
           JOIN {hvp_libraries} hl ON hl.id = h.main_library_id
          WHERE h.id = ?",
    [$id]);

if ($hvp === false) {
    print_error('invalidhvp');
}

// Set page properties.
$pageurl = new moodle_url('/mod/hvp/review.php', [
    'id' => $hvp->id,
]);
$PAGE->set_url($pageurl);
$PAGE->set_title($hvp->title);
$PAGE->set_heading($COURSE->fullname);
$PAGE->requires->css(new moodle_url($CFG->httpswwwroot . '/mod/hvp/xapi-custom-report.css'));

// We have to get grades from gradebook as well.
$xapiresults = $DB->get_records_sql("
    SELECT x.*, i.grademax
    FROM {hvp_xapi_results} x
    JOIN {grade_items} i ON i.iteminstance = x.content_id
    WHERE x.user_id = ?
    AND x.content_id = ?
    AND i.itemtype = 'mod'
    AND i.itemmodule = 'hvp'", [$userid, $id]
);

if (!$xapiresults) {
    print_error('invalidxapiresult', 'hvp');
}

$totalrawscore       = 0;
$totalmaxscore       = 0;
$totalscaledscore    = 0;
$scaledscoreperscore = 0;

// Assemble our question tree.
$basequestion = null;

// Find base question.
foreach ($xapiresults as $question) {
    if ($question->parent_id === null) {
        // This is the root of our tree.
        $basequestion = $question;

        if (isset($question->raw_score) && isset($question->grademax) && isset($question->max_score)) {
            $scaledscoreperscore   = $question->max_score ? ($question->grademax / $question->max_score) : 0;
            $question->score_scale = round($scaledscoreperscore, 2);
            $totalrawscore         = $question->raw_score;
            $totalmaxscore         = $question->max_score;
            if ($question->max_score && $question->raw_score === $question->max_score) {
                $totalscaledscore = round($question->grademax, 2);
            } else {
                $totalscaledscore = round($question->score_scale * $question->raw_score, 2);
            }
        }
        break;
    }
}

foreach ($xapiresults as $question) {
    if ($question->parent_id === null) {
        // Already processed.
        continue;
    } else if (isset($xapiresults[$question->parent_id])) {
        // Add to parent.
        $xapiresults[$question->parent_id]->children[] = $question;
    }

    // Set scores.
    if (isset($question->raw_score) && isset($question->grademax) && isset($question->max_score)) {
        $question->score_scale = round($question->raw_score * $scaledscoreperscore, 2);
    }

    // Set score labels.
    $question->score_label            = get_string('reportingscorelabel', 'hvp');
    $question->scaled_score_label     = get_string('reportingscaledscorelabel', 'hvp');
    $question->score_delimiter        = get_string('reportingscoredelimiter', 'hvp');
    $question->scaled_score_delimiter = get_string('reportingscaledscoredelimiter', 'hvp');
}

// Initialize reporter.
$reporter   = H5PReport::getInstance();
$reporthtml = $reporter->generateReport($basequestion, null, count($xapiresults) <= 1);
$styles     = $reporter->getStylesUsed();
foreach ($styles as $style) {
    $PAGE->requires->css(new moodle_url($CFG->httpswwwroot . '/mod/hvp/reporting/' . $style));
}

$renderer = $PAGE->get_renderer('mod_hvp');

// Print title and report.
$title = $hvp->title;

// Show user name if other then self.
if ($userid !== (int) $USER->id) {
    $userresult = $DB->get_record('user', ["id" => $userid], 'username');
    if (isset($userresult) && isset($userresult->username)) {
        $title .= ": {$userresult->username}";
    }
}

// Create title.
$reviewcontext = [
    'title'          => $title,
    'report'         => $reporthtml,
    'rawScore'       => $totalrawscore,
    'maxScore'       => $totalmaxscore,
    'scaledScore'    => $totalscaledscore,
    'maxScaledScore' => round($basequestion->grademax, 2),
];

// Print page HTML.
echo $OUTPUT->header();
echo $renderer->render_from_template('hvp/review', $reviewcontext);
echo $OUTPUT->footer();
