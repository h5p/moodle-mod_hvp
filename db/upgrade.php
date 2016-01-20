<?php

// This file keeps track of upgrades to
// the choice module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installation to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the methods of database_manager class
//
// Please do not forget to use upgrade_set_timeout()
// before any action that may take longer time to finish.

/**
 * Upgrade definitions for the hvp module.
 *
 * @package    mod_hvp
 * @copyright  2013 Amendor
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Hvp module upgrade function.
 *
 * @param string $oldversion The version we are upgrading from
 * @return bool Success
 */
function xmldb_hvp_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2016011300) {

        $table = new xmldb_table('hvp');

        // Define field timecreated to be added to hvp.
        $timecreated = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'slug');

        // Conditionally launch add field timecreated.
        if (!$dbman->field_exists($table, $timecreated)) {
            $dbman->add_field($table, $timecreated);
        }

        // Define field timemodified to be added to hvp.
        $timemodified = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'timecreated');

        // Conditionally launch add field timemodified.
        if (!$dbman->field_exists($table, $timemodified)) {
            $dbman->add_field($table, $timemodified);
        }

        // Hvp savepoint reached.
        upgrade_mod_savepoint(true, 2016011300, 'hvp');
    }

    return true;
}


