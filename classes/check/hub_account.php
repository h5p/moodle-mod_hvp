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
 * Check for the H5P Content Hub account.
 *
 * @package    mod_hvp
 * @author     Brendan Heywood <brendan@catalyst-au.net>
 * @copyright  2026 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp\check;

defined('MOODLE_INTERNAL') || die();

use core\check\check;
use core\check\result;

/**
 * Fetches the Content Hub account info from the H5P hub.
 *
 * This makes an external request so must only be run via ajax or on a check page,
 * never while building the admin tree.
 */
class hub_account extends check {

    /**
     * Get the short check name
     *
     * @return string
     */
    public function get_name(): string {
        return get_string('hubaccountcheck:name', 'mod_hvp');
    }

    /**
     * Return result
     *
     * @return result
     */
    public function get_result(): result {
        global $OUTPUT, $CFG;
        require_once($CFG->dirroot . '/mod/hvp/lib.php');

        $registrationurl = (new \moodle_url('/mod/hvp/content_hub_registration.php'))->out(false);
        $hubinfo = false;
        $error = null;

        try {
            $core = \mod_hvp\framework::instance('core');
            $hubinfo = $core->hubAccountInfo();
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        // Errors are queued in the session for display on the next page, collect them here instead.
        $messages = array_map(function ($error) {
            return $error->message;
        }, \mod_hvp\framework::messages('error'));
        if ($error !== null) {
            $messages[] = $error;
        }

        if ($hubinfo === false) {
            $hubinfo = new \stdClass();
        }
        $hubinfo->registrationurl = $registrationurl;
        $details = $OUTPUT->render_from_template('mod_hvp/hub_account_info', $hubinfo);

        if (!empty($messages)) {
            $status = result::WARNING;
            $summary = get_string('hubaccountcheck:error', 'mod_hvp');
            $details = \html_writer::div(s(implode(' ', $messages))) . $details;
        } else if (empty($hubinfo->name)) {
            $status = result::INFO;
            $summary = get_string('hubaccountcheck:unregistered', 'mod_hvp');
        } else {
            $status = result::OK;
            $summary = get_string('hubaccountcheck:registered', 'mod_hvp', s($hubinfo->name));
        }

        return new result($status, $summary, $details);
    }
}
