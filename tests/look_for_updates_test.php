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

namespace mod_hvp;

/**
 * Unit tests for look_for_updates scheduled task.
 *
 * @package    mod_hvp
 * @copyright  2026
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \mod_hvp\task\look_for_updates
 */
final class look_for_updates_test extends \advanced_testcase {
    /**
     * Verifies
     * - that the task exits early exactly when both hub_is_enabled and send_usage_statistics_setting are disabled
     * - that the task does proper error handling and logging
     *
     * @dataProvider execute_provider
     * @param int $hubisenabled Hub setting value.
     * @param int $sendusagestatistics Usage statistics setting value.
     * @param bool $expectearlyexit Whether execute() is expected to return via early-exit.
     */
    public function test_execute(
        int $hubisenabled,
        int $sendusagestatistics,
        bool $expectearlyexit,
    ): void {
        $this->resetAfterTest();

        set_config('hub_is_enabled', $hubisenabled, 'mod_hvp');
        set_config('send_usage_statistics', $sendusagestatistics, 'mod_hvp');
        // Keep site unregistered so all non-early-exit paths fail deterministically at registration.
        set_config('site_uuid', '', 'mod_hvp');

        if ($hubisenabled || $sendusagestatistics) {
            // Force deterministic no-network behavior for all non-early-exit cases.
            \curl::mock_response('');
        }

        $task = new \mod_hvp\task\look_for_updates();

        ob_start();
        try {
            $task->execute();
            $output = ob_get_clean();
            if (!$expectearlyexit) {
                $this->fail('Expected moodle_exception for failed metadata fetch. Output: ' . $output);
            }
        } catch (\moodle_exception $exception) {
            $output = ob_get_clean();
            if ($expectearlyexit) {
                $this->fail('Did not expect moodle_exception. Got: ' . $exception->errorcode);
            }
            $this->assertEquals('fetchlibrariesmetadatafailed', $exception->errorcode);
        }

        if ($expectearlyexit) {
            $this->assertStringContainsString(get_string('lookforupdatesskippeddisabledlog', 'mod_hvp'), $output);
        } else {
            $this->assertStringContainsString(get_string('fetchlibrariesmetadatafailedlog', 'mod_hvp'), $output);
            $this->assertStringContainsString('registration-failed-hub-disabled', $output);
            $this->assertStringNotContainsString(get_string('lookforupdatesskippeddisabledlog', 'mod_hvp'), $output);
        }
    }

    /**
     * Data provider for test_execute_setting_combinations.
     *
     * @return array
     */
    public static function execute_provider(): array {
        return [
            'both_disabled' => [
                'hubisenabled' => 0,
                'sendusagestatistics' => 0,
                'expectearlyexit' => true,
            ],
            'hub_enabled_only' => [
                'hubisenabled' => 1,
                'sendusagestatistics' => 0,
                'expectearlyexit' => false,
            ],
            'usage_statistics_enabled_only' => [
                'hubisenabled' => 0,
                'sendusagestatistics' => 1,
                'expectearlyexit' => false,
            ],
            'both_enabled' => [
                'hubisenabled' => 1,
                'sendusagestatistics' => 1,
                'expectearlyexit' => false,
            ],
        ];
    }
}
