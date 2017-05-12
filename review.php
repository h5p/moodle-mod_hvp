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

global $PAGE, $DB, $CFG, $OUTPUT, $COURSE;
require_once(dirname(__FILE__) . '/../../config.php');

$id       = required_param('id', PARAM_INT);
$userid   = optional_param('user', 0, PARAM_INT);

if (!$cm = get_coursemodule_from_instance('hvp', $id)) {
    print_error('invalidcoursemodule');
}
if (!$course = $DB->get_record('course', array('id' => $cm->course))) {
    print_error('coursemisconf');
}
require_login($course, FALSE, $cm);

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

if ($hvp === FALSE) {
    print_error('invalidhvp');
}

// Set page properties.
$pageurl = new moodle_url('/mod/hvp/review.php', array(
    'id' => $hvp->id
));
$PAGE->set_url($pageurl);
$PAGE->set_title($hvp->title);
$PAGE->set_heading($COURSE->fullname);

$xAPIResults = $DB->get_records('hvp_xapi_results', array(
    'content_id' => $id,
    'user_id'    => $userid
));

if (!$xAPIResults) {
    print_error('invalidxapiresult', 'hvp');
}

// Assemble our question tree
$baseQuestion = NULL;
foreach ($xAPIResults as $question) {
    if ($question->parent_id === NULL) {
        // This is the root of our tree
        $baseQuestion = $question;
    }
    elseif (isset($xAPIResults[$question->parent_id])) {
        // Add to parent
        $xAPIResults[$question->parent_id]->children[] = $question;
    }
}

// Initialize reporter
$reporter   = H5PReport::getInstance();
$reportHtml = $reporter->generateReport($baseQuestion);
$styles     = $reporter->getStylesUsed();
foreach ($styles as $style) {
    $PAGE->requires->css(new moodle_url($CFG->httpswwwroot . '/mod/hvp/reporting/' . $style));
}

// Print page HTML
echo $OUTPUT->header();
echo '<div class="clearer"></div>';

// Print title and report
echo "<h2>" . $hvp->title . "</h2>";
echo "<div>" . $reportHtml . "</div>";

echo $OUTPUT->footer();
