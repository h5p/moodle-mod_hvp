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
 * mod_hvp test data generator.
 *
 * @package    mod_hvp
 * @copyright  Luca Bösch <luca.boesch@bfh.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * mod_hvp test data generator class.
 *
 * The real mod_hvp instance creation (via hvp_add_instance()) runs the full H5P content
 * pipeline, which requires installed H5P libraries and an uploaded/created H5P package.
 * That is impractical in a test environment, so this generator inserts a minimal H5P
 * instance and its course module directly, which is enough to exercise activity-level
 * behaviour such as grading and completion.
 *
 * @package    mod_hvp
 * @copyright  Luca Bösch <luca.boesch@bfh.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_hvp_generator extends testing_module_generator {

    /**
     * Creates a new instance of the H5P activity for testing purposes.
     *
     * In addition to the standard course module options, the following record fields are
     * recognised: 'json_content', 'embed_type', 'main_library_id', 'slug', 'completionpass',
     * 'maximumgrade' and 'gradepass'.
     *
     * @param array|stdClass|null $record Data for the module being generated. Requires 'course'.
     * @param array|null $options General options for the course module.
     * @return stdClass The hvp record with an additional 'cmid' field.
     */
    public function create_instance($record = null, ?array $options = null) {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/course/lib.php');
        require_once($CFG->dirroot . '/mod/hvp/lib.php');

        $this->instancecount++;
        $i = $this->instancecount;

        $record = (object) (array) $record;
        $options = (array) $options;

        if (empty($record->course)) {
            throw new coding_exception('mod_hvp generator requires $record->course.');
        }
        $courseid = is_object($record->course) ? $record->course->id : $record->course;

        // Default values for the fields of the hvp instance record.
        $instancedefaults = [
            'name' => 'H5P activity ' . $i,
            'intro' => 'Test H5P activity ' . $i,
            'introformat' => FORMAT_MOODLE,
            'json_content' => '{}',
            'embed_type' => 'div',
            'main_library_id' => 0,
            'slug' => 'h5p-activity-' . $i,
            'completionpass' => 0,
            'shared' => 0,
        ];
        foreach ($instancedefaults as $key => $value) {
            if (!isset($record->$key)) {
                $record->$key = $value;
            }
        }

        $record->course = $courseid;
        $record->timecreated = time();
        $record->timemodified = time();

        // Persist only the columns that belong to the hvp table.
        $instance = clone $record;
        unset($instance->cmidnumber, $instance->gradepass, $instance->maximumgrade);
        $record->id = $DB->insert_record('hvp', $instance);

        // Resolve the course module options.
        $idnumber = $options['idnumber'] ?? ($record->cmidnumber ?? '');
        $cm = (object) [
            'course' => $courseid,
            'module' => $DB->get_field('modules', 'id', ['name' => 'hvp'], MUST_EXIST),
            'instance' => $record->id,
            'section' => $options['section'] ?? 0,
            'idnumber' => $idnumber,
            'added' => time(),
            'visible' => $options['visible'] ?? 1,
            'visibleoncoursepage' => 1,
            'visibleold' => 1,
            'groupmode' => $options['groupmode'] ?? 0,
            'groupingid' => $options['groupingid'] ?? 0,
            'completion' => $record->completion ?? ($options['completion'] ?? COMPLETION_TRACKING_NONE),
            'completionview' => $record->completionview ?? ($options['completionview'] ?? 0),
            'completionexpected' => $record->completionexpected ?? ($options['completionexpected'] ?? 0),
            'completionpassgrade' => $record->completionpassgrade ?? ($options['completionpassgrade'] ?? 0),
        ];

        $cmid = add_course_module($cm);
        course_add_cm_to_section($courseid, $cmid, $cm->section);
        $record->cmid = $cmid;

        // Create the activity's grade item so that grade-based completion can be exercised.
        $gradeitemdata = clone $record;
        $gradeitemdata->cmidnumber = $idnumber;
        $gradeitemdata->maximumgrade = $record->maximumgrade ?? 100;
        hvp_grade_item_update($gradeitemdata);

        // Apply a passing grade threshold to the grade item if one was requested.
        if (isset($record->gradepass)) {
            $gradeitem = grade_item::fetch([
                'courseid' => $courseid,
                'itemtype' => 'mod',
                'itemmodule' => 'hvp',
                'iteminstance' => $record->id,
                'outcomeid' => null,
            ]);
            if ($gradeitem) {
                $gradeitem->gradepass = $record->gradepass;
                $gradeitem->update();
            }
        }

        $result = $DB->get_record('hvp', ['id' => $record->id], '*', MUST_EXIST);
        $result->cmid = $cmid;

        return $result;
    }
}
