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

    if ($oldversion < 2016042500) {
        // Define table hvp_tmpfiles to be created.
        $table = new xmldb_table('hvp_tmpfiles');

        // Adding fields to table hvp_tmpfiles.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table hvp_tmpfiles.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for hvp_tmpfiles.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Hvp savepoint reached.
        upgrade_mod_savepoint(true, 2016042500, 'hvp');
    }

    return true;
}
