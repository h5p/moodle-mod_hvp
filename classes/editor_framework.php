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
 * \mod_hvp\framework class
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp;

defined('MOODLE_INTERNAL') || die();

require_once __DIR__ . '/../autoloader.php';

/**
 * Moodle's implementation of the H5P Editor framework interface.
 * Makes it possible for the editor's core library to communicate with the
 * database used by Moodle.
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class editor_framework implements \H5peditorStorage {

    /**
     * Load language file(JSON) from database.
     * This is used to translate the editor fields(title, description etc.)
     *
     * @param string $name The machine readable name of the library(content type)
     * @param int $major Major part of version number
     * @param int $minor Minor part of version number
     * @param string $lang Language code
     * @return string Translation in JSON format
     */
    public function getLanguage($name, $major, $minor, $lang) {
        global $DB;

        // Load translation field from DB
        return $DB->get_field_sql(
            "SELECT hlt.language_json
               FROM {hvp_libraries_languages} hlt
               JOIN {hvp_libraries} hl ON hl.id = hlt.library_id
              WHERE hl.machine_name = ?
                AND hl.major_version = ?
                AND hl.minor_version = ?
                AND hlt.language_code = ?
            ", array(
                $name,
                $major,
                $minor,
                $lang
            )
        );
    }

    /**
     * "Callback" for mark the given file as a permanent file.
     * Used when saving content that has new uploaded files.
     *
     * @param int $fileid
     */
    public function keepFile($fileid) {
        global $DB;

        // Remove from tmpfiles
        $DB->delete_records('hvp_tmpfiles', array(
            'id' => $fileid
        ));
    }

    /**
     * Decides which content types the editor should have.
     *
     * Two usecases:
     * 1. No input, will list all the available content types.
     * 2. Libraries supported are specified, load additional data and verify
     * that the content types are available. Used by e.g. the Presentation Tool
     * Editor that already knows which content types are supported in its
     * slides.
     *
     * @param array $libraries List of library names + version to load info for
     * @return array List of all libraries loaded
     */
    public function getLibraries($libraries = null) {
        global $DB;

        $context_id = required_param('contextId', PARAM_RAW);
        $super_user = has_capability('mod/hvp:userestrictedlibraries',
            \context::instance_by_id($context_id));

        if ($libraries !== null) {
            // Get details for the specified libraries only.
            $librarieswithdetails = array();
            foreach ($libraries as $library) {
                // Look for library
                $details = $DB->get_record_sql(
                        "SELECT title,
                                runnable,
                                restricted,
                                tutorial_url
                           FROM {hvp_libraries}
                          WHERE machine_name = ?
                            AND major_version = ?
                            AND minor_version = ?
                            AND semantics IS NOT NULL
                        ", array(
                            $library->name,
                            $library->majorVersion,
                            $library->minorVersion
                        )
                );
                if ($details) {
                    // Library found, add details to list
                    $library->tutorialUrl = $details->tutorial_url;
                    $library->title = $details->title;
                    $library->runnable = $details->runnable;
                    $library->restricted = $super_user ? false : ($details->restricted === '1' ? true : false);
                    $librarieswithdetails[] = $library;
                }
            }

            // Done, return list with library details
            return $librarieswithdetails;
        }

        // Load all libraries
        $libraries = array();
        $librariesresult = $DB->get_records_sql(
                "SELECT id,
                        machine_name AS name,
                        title,
                        major_version,
                        minor_version,
                        tutorial_url,
                        restricted
                   FROM {hvp_libraries}
                  WHERE runnable = 1
                    AND semantics IS NOT NULL
               ORDER BY title"
        );
        foreach ($librariesresult as $library) {
            // Remove unique index
            unset($library->id);

            // Convert snakes to camels
            $library->majorVersion = (int) $library->major_version;
            unset($library->major_version);
            $library->minorVersion = (int) $library->minor_version;
            unset($library->minor_version);
            if (!empty($library->tutorial_url)) {
              $library->tutorialUrl = $library->tutorial_url;
            }
            unset($library->tutorial_url);

            // Make sure we only display the newest version of a library.
            foreach ($libraries as $key => $existinglibrary) {
                if ($library->name === $existinglibrary->name) {
                    // Found library with same name, check versions
                    if ( ( $library->majorVersion === $existinglibrary->majorVersion &&
                           $library->minorVersion > $existinglibrary->minorVersion ) ||
                         ( $library->majorVersion > $existinglibrary->majorVersion ) ) {
                        // This is a newer version
                        $existinglibrary->isOld = true;
                    }
                    else {
                        // This is an older version
                        $library->isOld = true;
                    }
                }
            }

            // Check to see if content type should be restricted
            $library->restricted = $super_user ? false : ($library->restricted === '1' ? true : false);

            // Add new library
            $libraries[] = $library;
        }
        return $libraries;
    }

    /**
     * Allow for other plugins to decide which styles and scripts are attached.
     * This is useful for adding and/or modifing the functionality and look of
     * the content types.
     *
     * @param array $files
     *  List of files as objects with path and version as properties
     * @param array $libraries
     *  List of libraries indexed by machineName with objects as values. The objects
     *  have majorVersion and minorVersion as properties.
     */
    public function alterLibraryFiles(&$files, $libraries) {
        // TODO: h5p/h5p-moodle-plugin#12
    }
}
