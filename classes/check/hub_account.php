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

namespace mod_hvp\check;

use core\check\check;
use core\check\result;
use moodle_url;

/**
 * Check displaying the H5P Content Hub account registered for this site.
 *
 * The hub is contacted when the result is requested (via ajax from the admin settings page),
 * never while the admin tree is being built.
 *
 * @package    mod_hvp
 * @copyright  2026 Catalyst IT Australia
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hub_account extends check {

    /**
     * Check name.
     *
     * @return string
     */
    public function get_name(): string {
        return get_string('contenthub:checkname', 'hvp');
    }

    /**
     * Link to the hub registration page.
     *
     * @return \action_link|null
     */
    public function get_action_link(): ?\action_link {
        return new \action_link(
            new moodle_url('/mod/hvp/content_hub_registration.php'),
            get_string('contenthub:checkactionlink', 'hvp')
        );
    }

    /**
     * Fetch the account info from the hub and describe it.
     *
     * @return result
     */
    public function get_result(): result {
        global $CFG;
        require_once($CFG->dirroot . '/mod/hvp/lib.php');

        try {
            $core = \mod_hvp\framework::instance('core');
            $info = $core->hubAccountInfo();
        } catch (\Exception $e) {
            return new result(result::ERROR, get_string('contenthub:checkerror', 'hvp'), s($e->getMessage()));
        }

        if (empty($info) || empty($info->name)) {
            return new result(result::INFO, get_string('contenthub:checknotregistered', 'hvp'));
        }

        $lines = array_filter([
            $info->contactPerson ?? '',
            $info->email ?? '',
            trim(($info->address ?? '') . ' ' . ($info->zip ?? '') . ' ' . ($info->city ?? '')),
            $info->country ?? '',
            $info->phone ?? '',
        ]);
        $details = implode('<br>', array_map('s', $lines)) . '<br>';

        return new result(result::OK, get_string('contenthub:checkregistered', 'hvp', s($info->name)), $details);
    }
}
