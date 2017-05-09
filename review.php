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

require_once(dirname(__FILE__) . '/reporting/h5p-report.class.php');
require_once(dirname(__FILE__) . '/reporting/h5p-report-xapi-data.class.php');
require_once(dirname(__FILE__) . '/reporting/type-processors/type-processor.class.php');
require_once(dirname(__FILE__) . '/reporting/type-processors/choice-processor.class.php');
require_once(dirname(__FILE__) . '/reporting/type-processors/compound-processor.class.php');
require_once(dirname(__FILE__) . '/reporting/type-processors/fill-in-processor.class.php');
require_once(dirname(__FILE__) . '/reporting/type-processors/long-choice-processor.class.php');
require_once(dirname(__FILE__) . '/reporting/type-processors/matching-processor.class.php');
require_once(dirname(__FILE__) . '/reporting/type-processors/true-false-processor.class.php');


$id = required_param('id', PARAM_INT);
$courseid = optional_param('course', SITEID, PARAM_INT); // course id (defaults to Site).
$userid = optional_param('user', 0, PARAM_INT);


if (! $cm = get_coursemodule_from_instance('hvp', $id)) {
    print_error('invalidcoursemodule');
}
if (! $course = $DB->get_record('course', array('id' => $cm->course))) {
    print_error('coursemisconf');
}
require_course_login($course, false, $cm);

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
$title = "Results for {$hvp->title}";
$PAGE->set_title('title');
$PAGE->set_heading('heading');

$test_statement = json_decode('{"actor":{"name":"Admin User","mbox":"mailto:thomas.marstrander@joubel.com","objectType":"Agent"},"verb":{"id":"http://adlnet.gov/expapi/verbs/answered","display":{"en-US":"answered"}},"object":{"id":"http://localhost/mod/hvp/view.php?id=26","objectType":"Activity","definition":{"extensions":{"http://h5p.org/x-api/h5p-local-content-id":22},"name":{"en-US":"fill-in-the-blanks-837 (1)"},"description":{"en-US":"<p>Insert the missing words in this text about berries found in Norwegian forests and mountainous regions.</p>\n<p>Bilberries <em>(Vaccinium myrtillus)</em>,&nbsp;also known as __________berries are edible, nearly black berries found in nutrient-poor soils.</p>\n<p>__________berries <em>(Rubus chamaemorus)</em>&nbsp;are edible orange berries similar to raspberries or blackberries found in alpine and arctic tundra.&nbsp;</p>\n<p>Redcurrant <em>(Ribes rubrum) </em>are red translucent berries with a diameter of 8–10 mm, and are closely related to its black colored relative __________currant.&nbsp;</p>\n"},"type":"http://adlnet.gov/expapi/activities/cmi.interaction","interactionType":"fill-in","correctResponsesPattern":["{case_matters=false}blue[,]Cloud[,]black"]}},"context":{"contextActivities":{"category":[{"id":"http://h5p.org/libraries/H5P.Blanks-1.7","objectType":"Activity"}]}},"result":{"score":{"min":0,"max":3,"raw":2,"scaled":0.6667},"completion":true,"duration":"PT8.65S","response":"blue[,]jeje[,]black"}}');

// Initialize reporter
$reporter = H5PReport::getInstance();

$statement_object = (object) array(
    'statement' => $test_statement
);

$report = new H5PReportXAPIData($statement_object);

// TODO: Fetch from db
$xapiData = (object) array (
    'interaction_type' => $report->getInteractionType(),
    'description' => $report->getDescription(),
    'correct_responses_pattern' => $report->getCorrectResponsesPattern(),
    'additionals' => $report->getAdditionals(),
    'children' => null, # $report->getChildren(),
    'response' => $report->getResponse(),
);

$html = $reporter->generateReport($xapiData);
$styles = $reporter->getStylesUsed();
foreach ($styles as $style) {
    $PAGE->requires->css(new moodle_url($CFG->httpswwwroot . '/mod/hvp/reporting/' . $style));
}

// Print page HTML
echo $OUTPUT->header();
echo '<div class="clearer"></div>';

// Print H5P Content
echo "<h2>Hello kitty</h2>";
echo "<div>" . $html . "</div>";

echo $OUTPUT->footer();
