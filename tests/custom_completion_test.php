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

declare(strict_types=1);

namespace mod_hvp;

use advanced_testcase;
use cm_info;
use coding_exception;
use grade_item;
use mod_hvp\completion\custom_completion;
use moodle_exception;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->libdir . '/gradelib.php');

/**
 * Class for unit testing mod_hvp/custom_completion.
 *
 * @package    mod_hvp
 * @copyright  Luca Bösch <luca.boesch@bfh.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \mod_hvp\completion\custom_completion
 */
final class custom_completion_test extends advanced_testcase {

    /**
     * Create a course, an H5P activity and return its cm_info.
     *
     * @param int $completionpass Whether the instance requires a passing grade (the completionpass rule).
     * @param int $completion The completion tracking mode for the course module.
     * @return cm_info
     */
    protected function create_hvp_cm(int $completionpass, int $completion = COMPLETION_TRACKING_AUTOMATIC): cm_info {
        global $CFG;
        $CFG->enablecompletion = 1;

        $course = $this->getDataGenerator()->create_course(['enablecompletion' => 1]);
        $hvp = $this->getDataGenerator()->create_module('hvp', [
            'course' => $course->id,
            'completion' => $completion,
            'completionpass' => $completionpass,
            'gradepass' => 50.0,
        ]);

        return get_fast_modinfo($course)->get_cm($hvp->cmid);
    }

    /**
     * Award the given final grade to a user for the activity's grade item.
     *
     * @param cm_info $cm The course module.
     * @param int $userid The user id.
     * @param float $finalgrade The final grade to award.
     */
    protected function award_grade(cm_info $cm, int $userid, float $finalgrade): void {
        $gradeitem = grade_item::fetch([
            'courseid' => $cm->course,
            'itemtype' => 'mod',
            'itemmodule' => 'hvp',
            'iteminstance' => $cm->instance,
            'outcomeid' => null,
        ]);
        $gradeitem->update_final_grade($userid, $finalgrade);
    }

    /**
     * Test that get_state() throws when the rule is not defined by the module.
     */
    public function test_get_state_undefined_rule(): void {
        $this->resetAfterTest();

        $cm = $this->create_hvp_cm(1);
        $customcompletion = new custom_completion($cm, 1);

        $this->expectException(coding_exception::class);
        $customcompletion->get_state('somenonexistentrule');
    }

    /**
     * Test that get_state() throws when the rule is defined but not enabled for the activity.
     */
    public function test_get_state_rule_not_available(): void {
        $this->resetAfterTest();

        // With completionpass disabled, the rule is not registered on the course module.
        $cm = $this->create_hvp_cm(0);
        $customcompletion = new custom_completion($cm, 1);

        $this->expectException(moodle_exception::class);
        $customcompletion->get_state('completionpass');
    }

    /**
     * Data provider for test_get_state().
     *
     * @return array[]
     */
    public static function get_state_provider(): array {
        return [
            'Passing grade achieved' => [75.0, COMPLETION_COMPLETE],
            'Passing grade not achieved' => [25.0, COMPLETION_INCOMPLETE],
            'No grade yet' => [null, COMPLETION_INCOMPLETE],
        ];
    }

    /**
     * Test for get_state() covering the completionpass rule against real grade data.
     *
     * @dataProvider get_state_provider
     * @param float|null $finalgrade The final grade to award the user, or null for no grade.
     * @param int $expectedstate The expected completion state.
     */
    public function test_get_state(?float $finalgrade, int $expectedstate): void {
        $this->resetAfterTest();

        $user = $this->getDataGenerator()->create_user();
        $cm = $this->create_hvp_cm(1);

        if ($finalgrade !== null) {
            $this->award_grade($cm, (int) $user->id, $finalgrade);
        }

        $customcompletion = new custom_completion($cm, (int) $user->id);
        $this->assertEquals($expectedstate, $customcompletion->get_state('completionpass'));
    }

    /**
     * Test for get_defined_custom_rules().
     */
    public function test_get_defined_custom_rules(): void {
        $rules = custom_completion::get_defined_custom_rules();
        $this->assertCount(1, $rules);
        $this->assertEquals('completionpass', reset($rules));
    }

    /**
     * Test for get_custom_rule_descriptions().
     */
    public function test_get_custom_rule_descriptions(): void {
        $rules = custom_completion::get_defined_custom_rules();

        $mockcminfo = $this->getMockBuilder(cm_info::class)
            ->disableOriginalConstructor()
            ->getMock();

        $customcompletion = new custom_completion($mockcminfo, 1);
        $ruledescriptions = $customcompletion->get_custom_rule_descriptions();

        // Confirm that defined rules and rule descriptions are consistent with each other.
        $this->assertEquals(count($rules), count($ruledescriptions));
        foreach ($rules as $rule) {
            $this->assertArrayHasKey($rule, $ruledescriptions);
        }
    }

    /**
     * Test for is_defined().
     */
    public function test_is_defined(): void {
        $mockcminfo = $this->getMockBuilder(cm_info::class)
            ->disableOriginalConstructor()
            ->getMock();

        $customcompletion = new custom_completion($mockcminfo, 1);

        $this->assertTrue($customcompletion->is_defined('completionpass'));
        $this->assertFalse($customcompletion->is_defined('somerandomrule'));
    }

    /**
     * Data provider for test_get_available_custom_rules().
     *
     * @return array[]
     */
    public static function get_available_custom_rules_provider(): array {
        return [
            'Completion pass available' => [1, ['completionpass']],
            'Completion pass not available' => [0, []],
        ];
    }

    /**
     * Test for get_available_custom_rules().
     *
     * @dataProvider get_available_custom_rules_provider
     * @param int $status Whether the rule is enabled on the activity.
     * @param array $expected The expected list of available rules.
     */
    public function test_get_available_custom_rules(int $status, array $expected): void {
        $customdata = [
            'customcompletionrules' => [
                'completionpass' => $status,
            ],
        ];

        $mockcminfo = $this->getMockBuilder(cm_info::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get_custom_data'])
            ->getMock();

        $mockcminfo->method('get_custom_data')->willReturn($customdata);

        $customcompletion = new custom_completion($mockcminfo, 1);
        $this->assertEquals($expected, $customcompletion->get_available_custom_rules());
    }

    /**
     * Test for get_sort_order().
     */
    public function test_get_sort_order(): void {
        $mockcminfo = $this->getMockBuilder(cm_info::class)
            ->disableOriginalConstructor()
            ->getMock();

        $customcompletion = new custom_completion($mockcminfo, 1);
        $this->assertContains('completionpass', $customcompletion->get_sort_order());
    }
}
