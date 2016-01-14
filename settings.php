<?php

/**
 * Administration settings definitions for the hvp module.
 *
 * @package   mod_hvp
 * @copyright Joubel
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
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
    $settings->add(new admin_setting_configcheckbox('hvp/enable_save_content_state', get_string('enable_save_content_state', 'hvp'),
        get_string('content_state_description', 'hvp'), 0));
    $settings->add(new admin_setting_configtext('hvp/content_state_frequency', get_string('content_state_frequency', 'hvp'),
        get_string('content_state_frequency_description', 'hvp'), 30, PARAM_INT));

    // Display options for H5P frame
    $settings->add(new admin_setting_heading('hvp/display_options', get_string('display_options', 'hvp'), ''));
    $settings->add(new admin_setting_configcheckbox('hvp/frame', get_string('enable_frame', 'hvp'), '', 1));
    $settings->add(new admin_setting_configcheckbox('hvp/export', get_string('enable_download', 'hvp'), '', 1));
    //$settings->add(new admin_setting_configcheckbox('hvp/embed', get_string('enable_embed', 'hvp'), '', 1));
    $settings->add(new admin_setting_configcheckbox('hvp/copyright', get_string('enable_copyright', 'hvp'), '', 1));
    $settings->add(new admin_setting_configcheckbox('hvp/icon', get_string('enable_about', 'hvp'), '', 1));
}

// Prevent Moodle from adding settings block in standard location.
$settings = null;
