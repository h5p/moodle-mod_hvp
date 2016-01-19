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
 * Administration settings definitions for the hvp module.
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS <contact@joubel.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Make sure we are called from an internal Moodle site.
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/hvp/lib.php');

// Redefine the H5P admin menu entry to be expandable
$modltifolder = new admin_category('modhvpfolder', new lang_string('pluginname', 'mod_hvp'), $module->is_enabled() === false);
// Add the Settings admin menu entry
$ADMIN->add('modsettings', $modltifolder);
$settings->visiblename = new lang_string('settings', 'mod_hvp');
// Add the Libraries admin menu entry:
$ADMIN->add('modhvpfolder', $settings);
$ADMIN->add('modhvpfolder', new admin_externalpage('h5plibraries', get_string('libraries', 'hvp'), new moodle_url('/mod/hvp/library_list.php')));

if ($ADMIN->fulltree) {
    // Settings is stored on the global $CFG object

    // Content state
    $settings->add(
            new admin_setting_configcheckbox('mod_hvp/enable_save_content_state',
            get_string('enablesavecontentstate', 'hvp'),
            get_string('enablesavecontentstate_help', 'hvp'), 0));
    $settings->add(
            new admin_setting_configtext('mod_hvp/content_state_frequency',
            get_string('contentstatefrequency', 'hvp'),
            get_string('contentstatefrequency_help', 'hvp'), 30, PARAM_INT));

    // Display options for H5P frame
    $settings->add(new admin_setting_heading('mod_hvp/display_options', get_string('displayoptions', 'hvp'), ''));
    $settings->add(new admin_setting_configcheckbox('mod_hvp/frame', get_string('enableframe', 'hvp'), '', 1));
    $settings->add(new admin_setting_configcheckbox('mod_hvp/export', get_string('enabledownload', 'hvp'), '', 1));
    //$settings->add(new admin_setting_configcheckbox('hvp/embed', get_string('enable_embed', 'hvp'), '', 1));
    $settings->add(new admin_setting_configcheckbox('mod_hvp/copyright', get_string('enablecopyright', 'hvp'), '', 1));
    $settings->add(new admin_setting_configcheckbox('mod_hvp/icon', get_string('enableabout', 'hvp'), '', 1));
}

// Prevent Moodle from adding settings block in standard location.
$settings = null;
