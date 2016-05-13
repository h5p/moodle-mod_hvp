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
 * Defines the task which looks for H5P updates.
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS <contact@joubel.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp\task;

defined('MOODLE_INTERNAL') || die();

/**
 * The mod_hvp look for updates task class
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS <contact@joubel.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class look_for_updates extends \core\task\scheduled_task {
    public function get_name() {
        return get_string('lookforupdates', 'mod_hvp');
    }

    public function execute() {
        global $USER;

        // Check to make sure external communications hasn't been disabled
        $extcom = !!get_config('mod_hvp', 'external_communication');
        $extcomnotify = !!get_config('mod_hvp', 'external_communication_notify');
        if ($extcom || !$extcomnotify) {
            $core = \mod_hvp\framework::instance();
            $core->fetchLibrariesMetadata(!$extcom);
            set_config('external_communication_notify', $extcom ? false : time(), 'mod_hvp');

            // Notify admin if there are updates available!
            $update_available = \get_config('mod_hvp', 'update_available');
            $current_update = \get_config('mod_hvp', 'current_update');
            $admin_notified = \get_config('mod_hvp', 'admin_notified');
            if ($admin_notified !== $update_available &&  // Admin has not been notified about this update
                $update_available !== false &&
                $current_update !== false &&
                $current_update < $update_available) { // New update is available

                // Send message
                $updatesurl = new \moodle_url('/mod/hvp/library_list.php');
                $message = new \stdClass();
                $message->component         = 'mod_hvp';
                $message->name              = 'updates';
                $message->userfrom          = $USER;
                $message->userto            = get_admin();
                $message->subject           = get_string('updatesavailabletitle', 'mod_hvp');
                $message->fullmessage       = get_string('updatesavailablemsgpt1', 'mod_hvp') . ' ' .
                                              get_string('updatesavailablemsgpt2', 'mod_hvp') . "\n\n" .
                                              get_string('updatesavailablemsgpt3', 'mod_hvp', date('Y-m-d', $update_available)) . "\n" .
                                              get_string('updatesavailablemsgpt4', 'mod_hvp', date('Y-m-d', $current_update)) . "\n\n" .
                                              $updatesurl;
                $message->fullmessageformat = FORMAT_PLAIN;
                $message->fullmessagehtml   = '<p>' . get_string('updatesavailablemsgpt1', 'mod_hvp') . '<br/>' .
                                              get_string('updatesavailablemsgpt2', 'mod_hvp') . '</p>' .
                                              '<p>' . get_string('updatesavailablemsgpt3', 'mod_hvp', '<b>' . date('Y-m-d', $update_available) . '</b>') . '<br/>' .
                                              get_string('updatesavailablemsgpt4', 'mod_hvp', '<b>' . date('Y-m-d', $current_update) . '</b>') . '</p>' .
                                              '<a href="' . $updatesurl .  '" target="_blank">' . $updatesurl . '</a>';
                $message->smallmessage      = '';
                $message->notification      = 1;
                message_send($message);

                // Keep track of which version we've notfied about
                \set_config('admin_notified', $update_available, 'mod_hvp');
            }
        }
    }
}
