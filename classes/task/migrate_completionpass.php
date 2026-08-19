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

namespace mod_hvp\task;

/**
 * Migrate from hvp.completionpass to course_modules.completionpassgrade on Moodle 4.0+.
 *
 * The plugin upgrade step runs one time, when the plugin is upgraded, so a site
 * that upgrades the plugin while on Moodle < 4.0 then later upgrades to Moodle
 * 4.0+ would never migrate its data. This ad-hoc task is queued by the upgrade
 * step (and re-queues itself) until it runs on Moodle 4.0+ and performs the
 * migration.
 *
 * @package    mod_hvp
 * @copyright  2026 H5P Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class migrate_completionpass extends \core\task\adhoc_task {
    public function get_name() {
        return get_string('migratecompletionpass', 'mod_hvp');
    }

    public function execute() {
        self::migrate();
    }

    /**
     * Migrate legacy hvp.completionpass values to course_modules.completionpassgrade.
     *
     * On Moodle < 4.0 the core field does not exist yet, so an ad-hoc task is
     * queued to retry the migration later instead.
     */
    public static function migrate() {
        global $CFG, $DB;

        // Moodle too old, try again later.
        if ($CFG->branch < 400) {
            $task = new self();
            $task->set_next_run_time(time() + HOURSECS);
            \core\task\manager::queue_adhoc_task($task, true);
            return;
        }

        $moduleid = $DB->get_field('modules', 'id', ['name' => 'hvp']);
        if (!$moduleid) {
            return;
        }

        $sql = "UPDATE {course_modules} cm
                   SET cm.completionpassgrade = 1
                 WHERE cm.module = :moduleid
                   AND cm.instance IN (
                       SELECT h.id
                         FROM {hvp} h
                        WHERE h.completionpass = 1
                   )";
        $DB->execute($sql, ['moduleid' => $moduleid]);
    }
}
