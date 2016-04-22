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
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class editor_framework implements \H5peditorStorage {

    /**
     * Empty contructor.
     */
    function __construct() { }

    /**
     * Implements getLanguage().
     */
    public function getLanguage($name, $major, $minor, $lang) {
        global $DB;

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
     * Implements addTmpFile().
     */
    public function addTmpFile($file) {
        // TODO: Keep track of tmp files.
    }

    /**
     * Implements keepFile().
     */
    public function keepFile($oldpath, $newpath) {
        // TODO: No longer a tmp file.
    }

    /**
     * Implements removeFile().
     */
    public function removeFile($path) {
        // TODO: Removed from file tracking.
    }

    /**
     * Implements getLibraries().
     */
    public function getLibraries($libraries = null) {
        global $DB;
        $super_user = false; // TODO: Check if user can has manage h5p libraries

        if ($libraries !== null) {
            // Get details for the specified libraries only.
            $librarieswithdetails = array();
            foreach ($libraries as $library) {
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
                    $library->tutorialUrl = $details->tutorial_url;
                    $library->title = $details->title;
                    $library->runnable = $details->runnable;
                    $library->restricted = $super_user ? false : ($details->restricted === '1' ? true : false);
                    $librarieswithdetails[] = $library;
                }
            }

            return $librarieswithdetails;
        }

        $libraries = array();

        $librariesresult = $DB->get_records_sql(
                "SELECT machine_name AS name,
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
            // Convert snakes to camels
            $library->majorVersion = (int) $library->major_version;
            $library->minorVersion = (int) $library->minor_version;
            $library->tutorialUrl = $library->tutorial_url;

            // Make sure we only display the newest version of a library.
            foreach ($libraries as $key => $existinglibrary) {
                if ($library->name === $existinglibrary->name) {
                    // Mark old ones
                    // This is the newest
                    if ( ( $library->majorVersion === $existinglibrary->majorVersion &&
                           $library->minorVersion > $existinglibrary->minorVersion ) ||
                         ( $library->majorVersion > $existinglibrary->majorVersion ) ) {
                        $existinglibrary->isOld = true;
                    }
                    else {
                        $library->isOld = true;
                    }
                }
            }

            $library->restricted = $super_user ? false : ($library->restricted === '1' ? true : false);

            // Add new library
            $libraries[] = $library;
        }
        return $libraries;
    }

    /**
     * Implements alterLibraryFiles().
     */
    public function alterLibraryFiles(&$files, $libraries) {
        // TODO: Fill with code
    }
}
