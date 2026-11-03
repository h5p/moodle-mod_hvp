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

use core_privacy\local\request\approved_userlist;

/**
 * Unit tests for deleting user data through the privacy provider.
 *
 * @package    mod_hvp
 * @copyright  2026
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \mod_hvp\privacy\provider
 */
final class provider_delete_test extends \core_privacy\tests\provider_testcase {
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
     * Add one xAPI result and one content user data row for a user.
     *
     * @param \stdClass $cm The hvp course module.
     * @param int $userid The user id.
     */
    private function add_user_data(\stdClass $cm, int $userid): void {
        global $DB;

        $DB->insert_record('hvp_xapi_results', (object) [
            'content_id' => $cm->instance,
            'user_id' => $userid,
            'parent_id' => null,
            'interaction_type' => 'choice',
            'description' => 'Question',
            'correct_responses_pattern' => '["0"]',
            'response' => '0',
            'additionals' => '{}',
            'raw_score' => 1,
            'max_score' => 1,
        ]);
        $DB->insert_record('hvp_content_user_data', (object) [
            'user_id' => $userid,
            'hvp_id' => $cm->instance,
            'sub_content_id' => 0,
            'data_id' => 'state',
            'data' => '{}',
            'preloaded' => 1,
            'delete_on_content_change' => 0,
        ]);
    }

    /**
     * Bulk deletion removes the selected users' xAPI results and content user data
     * in the given activity only.
     */
    public function test_delete_data_for_users(): void {
        global $DB;
        $this->resetAfterTest();

        $course = $this->getDataGenerator()->create_course();
        $user1 = $this->getDataGenerator()->create_user();
        $user2 = $this->getDataGenerator()->create_user();
        $cm1 = $this->create_hvp($course);
        $cm2 = $this->create_hvp($course);

        foreach ([$cm1, $cm2] as $cm) {
            foreach ([$user1, $user2] as $user) {
                $this->add_user_data($cm, $user->id);
            }
        }

        $userlist = new approved_userlist($cm1->context, 'mod_hvp', [$user1->id]);
        provider::delete_data_for_users($userlist);

        // Selected user in the selected activity: deleted.
        $this->assertFalse($DB->record_exists('hvp_xapi_results',
            ['content_id' => $cm1->instance, 'user_id' => $user1->id]));
        $this->assertFalse($DB->record_exists('hvp_content_user_data',
            ['hvp_id' => $cm1->instance, 'user_id' => $user1->id]));

        // Other user in the selected activity: kept.
        $this->assertTrue($DB->record_exists('hvp_xapi_results',
            ['content_id' => $cm1->instance, 'user_id' => $user2->id]));
        $this->assertTrue($DB->record_exists('hvp_content_user_data',
            ['hvp_id' => $cm1->instance, 'user_id' => $user2->id]));

        // Selected user in another activity: kept.
        $this->assertTrue($DB->record_exists('hvp_xapi_results',
            ['content_id' => $cm2->instance, 'user_id' => $user1->id]));
        $this->assertTrue($DB->record_exists('hvp_content_user_data',
            ['hvp_id' => $cm2->instance, 'user_id' => $user1->id]));

        $this->assertEquals(3, $DB->count_records('hvp_xapi_results'));
        $this->assertEquals(3, $DB->count_records('hvp_content_user_data'));
    }
}
