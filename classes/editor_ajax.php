<?php

/**
 * \mod_hvp\editor_ajax class
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp;

defined('MOODLE_INTERNAL') || die();

require_once __DIR__ . '/../autoloader.php';

/**
 * Moodle's implementation of the H5P Editor Ajax interface.
 * Makes it possible for the editor's core ajax functionality to communicate with the
 * database used by Moodle.
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class editor_ajax implements \H5PEditorAjaxInterface {

    /**
     * Gets latest library versions that exists locally
     *
     * @return array Latest version of all local libraries
     */
    public function getLatestLibraryVersions() {
        global $DB;

        $max_major_version_sql = "
            SELECT hl.machine_name, MAX(hl.major_version) AS major_version
            FROM {hvp_libraries} hl
            WHERE hl.runnable = 1
            GROUP BY hl.machine_name";

        $max_minor_version_sql = "
            SELECT hl2.machine_name, hl2.major_version, MAX(hl2.minor_version) AS minor_version
            FROM ({$max_major_version_sql}) hl1
            JOIN {hvp_libraries} hl2
            ON hl1.machine_name = hl2.machine_name
            AND hl1.major_version = hl2.major_version
            GROUP BY hl2.machine_name, hl2.major_version";

        return $DB->get_records_sql("
            SELECT hl4.id, hl4.machine_name, hl4.title, hl4.major_version,
                hl4.minor_version, hl4.patch_version, hl4.has_icon, hl4.restricted
            FROM {hvp_libraries} hl4
            JOIN ({$max_minor_version_sql}) hl3
            ON hl4.machine_name = hl3.machine_name
            AND hl4.major_version = hl3.major_version
            AND hl4.minor_version = hl3.minor_version"
        );
    }

    /**
     * Get locally stored Content Type Cache. If machine name is provided
     * it will only get the given content type from the cache
     *
     * @param $machineName
     *
     * @return array|object|null Returns results from querying the database
     */
    public function getContentTypeCache($machineName = NULL) {
        global $DB;

        if ($machineName) {
            return $DB->get_record_sql(
                "SELECT id, is_recommended
                   FROM {hvp_libraries_hub_cache}
                  WHERE machine_name = ?",
                array($machineName)
            );
        }

        return $DB->get_records("hvp_libraries_hub_cache");
    }

    /**
     * Gets recently used libraries for the current author
     *
     * @return array machine names. The first element in the array is the
     * most recently used.
     */
    public function getAuthorsRecentlyUsedLibraries() {
        global $DB;
        global $USER;
        $recently_used = array();

        $results = $DB->get_records_sql(
            "SELECT library_name, max(created_at) AS max_created_at
            FROM {hvp_events}
           WHERE type='content' AND sub_type = 'create' AND user_id = ?
        GROUP BY library_name
        ORDER BY max_created_at DESC", array($USER->id));

        foreach ($results as $row) {
            $recently_used[] = $row->library_name;
        }

        return $recently_used;
    }

    /**
     * Checks if the provided token is valid for this endpoint
     *
     * @param string $token The token that will be validated for.
     *
     * @return bool True if successful validation
     */
    public function validateEditorToken($token) {
        return \H5PCore::validToken('editorajax', $token);
    }
}
