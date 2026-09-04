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
 * Defines the task which looks for H5P updates.
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS <contact@joubel.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class look_for_updates extends \core\task\scheduled_task {
    #[\Override]
    public function get_name() {
        return get_string('lookforupdates', 'mod_hvp');
    }

    #[\Override]
    public function execute() {
        // Check to make sure external communications have not been disabled.
        if (!get_config('mod_hvp', 'hub_is_enabled') && !get_config('mod_hvp', 'send_usage_statistics')) {
            mtrace(get_string('lookforupdatesskippeddisabledlog', 'mod_hvp'));
            return;
        }

        $core = \mod_hvp\framework::instance();
        $result = $core->fetchLibrariesMetadata();

        if ($result === false) {
            mtrace(get_string('fetchlibrariesmetadatafailedlog', 'mod_hvp'));
        } else if (is_object($result)) {
            $librariescount = 0;
            if (isset($result->libraries) && is_array($result->libraries)) {
                $librariescount = count($result->libraries);
            }
            mtrace(get_string('fetchlibrariesmetadatasuccesslog', 'mod_hvp', $librariescount));
        } else {
            mtrace(get_string('fetchlibrariesmetadataunexpectedlog', 'mod_hvp'));
        }

        $errors = \mod_hvp\framework::messages('error');
        foreach ($errors as $error) {
            $code = $error->code ?? 'N/A';
            $text = $error->message ?? 'N/A';
            mtrace(get_string('fetchlibrariesmetadataerrorlog', 'mod_hvp', (object) [
                'code' => $code,
                'message' => $text,
            ]));
        }

        $infos = \mod_hvp\framework::messages('info');
        foreach ($infos as $info) {
            mtrace(get_string('fetchlibrariesmetadatainfolog', 'mod_hvp', $info));
        }

        if ($result === false) {
            throw new \moodle_exception('fetchlibrariesmetadatafailed', 'mod_hvp');
        }
    }
}
