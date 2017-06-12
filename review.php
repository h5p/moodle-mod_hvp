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

$id       = required_param('id', PARAM_INT);
$userid   = optional_param('user', (int)$USER->id, PARAM_INT);

if (!$cm = get_coursemodule_from_instance('hvp', $id)) {
    print_error('invalidcoursemodule');
}
if (!$course = $DB->get_record('course', array('id' => $cm->course))) {
    print_error('coursemisconf');
}
require_login($course, false, $cm);

// Check permission.
$coursecontext = context_course::instance($COURSE->id);
hvp_require_view_results_permission($userid, $coursecontext, $cm->id);

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
    array($id));

if ($hvp === false) {
    print_error('invalidhvp');
}

// Set page properties.
$pageurl = new moodle_url('/mod/hvp/review.php', array(
    'id' => $hvp->id
));
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
    AND x.content_id = ?", array($userid, $id)
);

if (!$xapiresults) {
    print_error('invalidxapiresult', 'hvp');
}

// Assemble our question tree.
$basequestion = null;
foreach ($xapiresults as $question) {
    if ($question->grademax && $question->max_score) {
        $question->score_scale = $question->grademax / $question->max_score;
    }
    if ($question->parent_id === null) {
        // This is the root of our tree.
        $basequestion = $question;
    } else if (isset($xapiresults[$question->parent_id])) {
        // Add to parent.
        $xapiresults[$question->parent_id]->children[] = $question;
    }
}

// Initialize reporter.
$reporter   = H5PReport::getInstance();
$reporthtml = $reporter->generateReport($basequestion);
$styles     = $reporter->getStylesUsed();
foreach ($styles as $style) {
    $PAGE->requires->css(new moodle_url($CFG->httpswwwroot . '/mod/hvp/reporting/' . $style));
}

// Print page HTML.
echo $OUTPUT->header();
echo '<div class="clearer"></div>';

// Print title and report.
$title = $hvp->title;

// Show user name if other then self.
if ($userid !== (int) $USER->id) {
    $userresult = $DB->get_record('user', ["id" => $userid], 'username');
    if (isset($userresult) && isset($userresult->username)) {
        $title .= ": {$userresult->username}";
    }
}
echo "<div class='h5p-report-container'>
        <h2>{$title}</h2>
        <div class='h5p-report-view'>{$reporthtml}</div>
      </div>";

echo $OUTPUT->footer();
