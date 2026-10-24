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

namespace mod_hvp\privacy;

use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\writer;

/**
 * Unit tests for exporting user data through the privacy provider.
 *
 * @package    mod_hvp
 * @copyright  2026
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \mod_hvp\privacy\provider
 */
final class provider_export_test extends \core_privacy\tests\provider_testcase {
    /**
     * Create an hvp activity without going through the H5P editor.
     *
     * @param \stdClass $course The course.
     * @return \stdClass The course module, with an extra context property.
     */
    private function create_hvp(\stdClass $course): \stdClass {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/course/lib.php');

        $hvpid = $DB->insert_record('hvp', (object) [
            'course' => $course->id,
            'name' => 'Test content',
            'json_content' => '{}',
            'embed_type' => 'div',
            'main_library_id' => 0,
            'slug' => 'test-content',
            'timecreated' => time(),
            'timemodified' => time(),
        ]);
        $cmid = add_course_module((object) [
            'course' => $course->id,
            'module' => $DB->get_field('modules', 'id', ['name' => 'hvp']),
            'instance' => $hvpid,
            'section' => 0,
        ]);
        course_add_cm_to_section($course, $cmid, 0);

        $cm = get_coursemodule_from_id('hvp', $cmid, 0, false, MUST_EXIST);
        $cm->context = \context_module::instance($cmid);
        return $cm;
    }

    /**
     * Add an xAPI result row.
     *
     * @param \stdClass $cm The hvp course module.
     * @param int $userid The user id.
     * @param string $description Used to identify the row in the export.
     * @param int|null $rawscore Raw score.
     * @param int|null $maxscore Max score.
     */
    private function add_xapi_result(\stdClass $cm, int $userid, string $description,
            ?int $rawscore, ?int $maxscore): void {
        global $DB;

        $DB->insert_record('hvp_xapi_results', (object) [
            'content_id' => $cm->instance,
            'user_id' => $userid,
            'parent_id' => null,
            'interaction_type' => 'choice',
            'description' => $description,
            'correct_responses_pattern' => '["0[,]1"]',
            'response' => '0[,]1',
            'additionals' => '{}',
            'raw_score' => $rawscore,
            'max_score' => $maxscore,
        ]);
    }

    /**
     * The export contains interaction_type, correct_responses_pattern and zero scores,
     * and only the requested user's results in the approved contexts.
     */
    public function test_export_user_data_xapi_results(): void {
        $this->resetAfterTest();

        $course = $this->getDataGenerator()->create_course();
        $user1 = $this->getDataGenerator()->create_user();
        $user2 = $this->getDataGenerator()->create_user();
        $cm1 = $this->create_hvp($course);
        $cm2 = $this->create_hvp($course);

        $this->add_xapi_result($cm1, $user1->id, 'zero', 0, 0);
        $this->add_xapi_result($cm1, $user1->id, 'nonzero', 3, 5);
        $this->add_xapi_result($cm1, $user1->id, 'null', null, null);
        $this->add_xapi_result($cm1, $user2->id, 'otheruser', 2, 5);
        $this->add_xapi_result($cm2, $user1->id, 'othercontext', 2, 5);

        $contextlist = new approved_contextlist($user1, 'mod_hvp', [$cm1->context->id]);
        provider::export_user_data($contextlist);

        $data = writer::with_context($cm1->context)->get_data([]);
        $this->assertCount(3, $data->xapiresults);

        $results = [];
        foreach ($data->xapiresults as $result) {
            $results[$result->description] = $result;
        }
        $this->assertEqualsCanonicalizing(['zero', 'nonzero', 'null'], array_keys($results));

        foreach ($results as $result) {
            $this->assertEquals('choice', $result->interaction_type);
            $this->assertEquals('["0[,]1"]', $result->correct_responses_pattern);
        }

        $this->assertTrue(property_exists($results['zero'], 'raw_score'));
        $this->assertEquals(0, $results['zero']->raw_score);
        $this->assertTrue(property_exists($results['zero'], 'max_score'));
        $this->assertEquals(0, $results['zero']->max_score);

        $this->assertEquals(3, $results['nonzero']->raw_score);
        $this->assertEquals(5, $results['nonzero']->max_score);

        $this->assertFalse(property_exists($results['null'], 'raw_score'));
        $this->assertFalse(property_exists($results['null'], 'max_score'));

        $this->assertFalse(writer::with_context($cm2->context)->has_any_data());
    }
}
