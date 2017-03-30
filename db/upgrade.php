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
 * @copyright  2016 Joubel AS <contact@joubel.com>
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

    if ($oldversion < 2016050600) {

        // Define table hvp_events to be created.
        $table = new xmldb_table('hvp_events');

        // Adding fields to table hvp_events.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('user_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('created_at', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('type', XMLDB_TYPE_CHAR, '63', null, XMLDB_NOTNULL, null, null);
        $table->add_field('sub_type', XMLDB_TYPE_CHAR, '63', null, XMLDB_NOTNULL, null, null);
        $table->add_field('content_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('content_title', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('library_name', XMLDB_TYPE_CHAR, '127', null, XMLDB_NOTNULL, null, null);
        $table->add_field('library_version', XMLDB_TYPE_CHAR, '31', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table hvp_events.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for hvp_events.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table hvp_counters to be created.
        $table = new xmldb_table('hvp_counters');

        // Adding fields to table hvp_counters.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('type', XMLDB_TYPE_CHAR, '63', null, XMLDB_NOTNULL, null, null);
        $table->add_field('library_name', XMLDB_TYPE_CHAR, '127', null, XMLDB_NOTNULL, null, null);
        $table->add_field('library_version', XMLDB_TYPE_CHAR, '31', null, XMLDB_NOTNULL, null, null);
        $table->add_field('num', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table hvp_counters.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Adding indexes to table hvp_counters.
        $table->add_index('realkey', XMLDB_INDEX_NOTUNIQUE, array('type', 'library_name', 'library_version'));

        // Conditionally launch create table for hvp_counters.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Hvp savepoint reached.
        upgrade_mod_savepoint(true, 2016050600, 'hvp');
    }

    if ($oldversion < 2016051000) {

        $table = new xmldb_table('hvp');

        // Define field intro to be added to hvp.
        $intro = new xmldb_field('intro', XMLDB_TYPE_TEXT, null, null, null, null, null, 'name');

        // Add field intro if not defined already.
        if (!$dbman->field_exists($table, $intro)) {
            $dbman->add_field($table, $intro);
        }

        // Define field introformat to be added to hvp.
        $introformat = new xmldb_field('introformat', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '0', 'intro');

        // Add field introformat if not defined already.
        if (!$dbman->field_exists($table, $introformat)) {
            $dbman->add_field($table, $introformat);
        }

        // Hvp savepoint reached.
        upgrade_mod_savepoint(true, 2016051000, 'hvp');
    }

    if ($oldversion < 2016110100) {

        // Change context of activity files from COURSE to MODULE.

        $filearea = 'content';
        $component = 'mod_hvp';

        // Find activity ID and correct context ID
        $hvpsresult = $DB->get_records_sql(
                "SELECT f.id AS fileid, f.itemid, c.id, f.filepath, f.filename, f.pathnamehash
                   FROM {files} f
                   JOIN {course_modules} cm ON f.itemid = cm.instance
                   JOIN {modules} md ON md.id = cm.module
                   JOIN {context} c ON c.instanceid = cm.id
                  WHERE md.name = 'hvp'
                    AND f.filearea = 'content'
                    AND c.contextlevel = " . CONTEXT_MODULE
        );

        foreach ($hvpsresult as $hvp) {
            // Need to re-hash pathname after changing context
            $pathnamehash = file_storage::get_pathname_hash($hvp->id, $component, $filearea, $hvp->itemid, $hvp->filepath, $hvp->filename);

            // Double check that hash doesn't exist (avoid duplicate entries)
            if (!$DB->get_field_sql("SELECT contextid FROM {files} WHERE pathnamehash = '{$pathnamehash}'")) {
              // Update context ID and pathname hash for files
              $DB->execute("UPDATE {files} SET contextid = {$hvp->id}, pathnamehash = '{$pathnamehash}' WHERE pathnamehash = '{$hvp->pathnamehash}'");
            }
        }

        // Hvp savepoint reached.
        upgrade_mod_savepoint(true, 2016110100, 'hvp');
    }

    if ($oldversion < 2016122800) {
        \mod_hvp\framework::messages('info', '<span style="font-weight: bold;">Upgrade your H5P content types!</span> Old content types will still work, but the authoring tool will look and feel much better if you <a href="https://h5p.org/update-all-content-types">upgrade the content types</a>.');
        \mod_hvp\framework::printMessages('info', \mod_hvp\framework::messages('info'));

        upgrade_mod_savepoint(true, 2016122800, 'hvp');
    }

    /**
     * Add content type cache database
     */
    if ($oldversion < 2017021900) {
        // Define table hvp_libraries_hub_cache to be created.
        $table = new xmldb_table('hvp_libraries_hub_cache');

        // Adding fields to table hvp_libraries_hub_cache.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', NULL, XMLDB_NOTNULL, XMLDB_SEQUENCE, NULL);
        $table->add_field('machine_name', XMLDB_TYPE_CHAR, '255', NULL, XMLDB_NOTNULL, NULL, NULL);
        $table->add_field('major_version', XMLDB_TYPE_INTEGER, '4', NULL, XMLDB_NOTNULL, NULL, NULL);
        $table->add_field('minor_version', XMLDB_TYPE_INTEGER, '4', NULL, XMLDB_NOTNULL, NULL, NULL);
        $table->add_field('patch_version', XMLDB_TYPE_INTEGER, '4', NULL, XMLDB_NOTNULL, NULL, NULL);
        $table->add_field('h5p_major_version', XMLDB_TYPE_INTEGER, '4', NULL, NULL, NULL, NULL);
        $table->add_field('h5p_minor_version', XMLDB_TYPE_INTEGER, '4', NULL, NULL, NULL, NULL);
        $table->add_field('title', XMLDB_TYPE_CHAR, '255', NULL, XMLDB_NOTNULL, NULL, NULL);
        $table->add_field('summary', XMLDB_TYPE_TEXT, NULL, NULL, XMLDB_NOTNULL, NULL, NULL);
        $table->add_field('description', XMLDB_TYPE_TEXT, NULL, NULL, XMLDB_NOTNULL, NULL, NULL);
        $table->add_field('icon', XMLDB_TYPE_CHAR, '511', NULL, XMLDB_NOTNULL, NULL, NULL);
        $table->add_field('created_at', XMLDB_TYPE_INTEGER, '11', NULL, XMLDB_NOTNULL, NULL, NULL);
        $table->add_field('updated_at', XMLDB_TYPE_INTEGER, '11', NULL, XMLDB_NOTNULL, NULL, NULL);
        $table->add_field('is_recommended', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, XMLDB_NOTNULL, NULL, NULL);
        $table->add_field('popularity', XMLDB_TYPE_INTEGER, '10', NULL, XMLDB_NOTNULL, NULL, NULL);
        $table->add_field('screenshots', XMLDB_TYPE_TEXT, NULL, NULL, NULL, NULL, NULL);
        $table->add_field('license', XMLDB_TYPE_CHAR, '511', NULL, NULL, NULL, NULL);
        $table->add_field('example', XMLDB_TYPE_CHAR, '511', NULL, XMLDB_NOTNULL, NULL, NULL);
        $table->add_field('tutorial', XMLDB_TYPE_CHAR, '511', NULL, NULL, NULL, NULL);
        $table->add_field('keywords', XMLDB_TYPE_TEXT, NULL, NULL, NULL, NULL, NULL);
        $table->add_field('categories', XMLDB_TYPE_TEXT, NULL, NULL, NULL, NULL, NULL);
        $table->add_field('owner', XMLDB_TYPE_CHAR, '511', NULL, NULL, NULL, NULL);

        // Adding keys to table hvp_libraries_hub_cache.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally create table for hvp_libraries_hub_cache.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Update the content type cache
        $core = \mod_hvp\framework::instance();
        $core->updateContentTypeCache();

        // Print messages
        \mod_hvp\framework::printMessages('info', \mod_hvp\framework::messages('info'));
        \mod_hvp\framework::printMessages('error', \mod_hvp\framework::messages('error'));

        upgrade_mod_savepoint(TRUE, 2017021900, 'hvp');
    }

    /**
     * Add has_icon to libraries folder
     */
    if ($oldversion < 2017030200) {
        $table = new xmldb_table('hvp_libraries');

        // Define field has_icon to be added to hvp_libraries.
        $has_icon = new xmldb_field('has_icon', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0');

        // Add field has_icon if it does not exist
        if (!$dbman->field_exists($table, $has_icon)) {
            $dbman->add_field($table, $has_icon);
        }

        // Display hub communication info
        if (!get_config('mod_hvp', 'external_communication')) {
            \mod_hvp\framework::messages('info', 'H5P now fetches content types directly from the H5P Hub. In order to do this the H5P plugin will communicate with H5P.org once per day to fetch information about new and updated content types. It will send in anonymous data to the hub about H5P usage. If you do not want to use the new Hub client and get information about new content types automatically, you may disable the H5P Hub in the H5P settings.');
            \mod_hvp\framework::printMessages('info', \mod_hvp\framework::messages('info'));
        }

        // Enable hub and delete old communication variable
        set_config('hub_is_enabled', true, 'mod_hvp');
        unset_config('hub_is_enabled', 'mod_hvp');

        upgrade_mod_savepoint(TRUE, 2017030200, 'hvp');
    }

    return true;
}
