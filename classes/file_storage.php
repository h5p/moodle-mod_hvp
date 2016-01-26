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
 * The mod_hvp file storage
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp;
defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/mod/hvp/library/h5p-file-storage.interface.php');

/**
 * The mod_hvp file storage class.
 *
 * @package    mod_hvp
 * @since      Moodle 2.7
 * @copyright  2016 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class file_storage implements \H5PFileStorage {

    /**
     * Store the library folder.
     *
     * @param array $library
     *  Library properties
     */
    public function saveLibrary($library) {
        // Libraries are stored in a system context
        $context = \context_system::instance();
        $options = array(
            'contextid' => $context->id,
            'component' => 'mod_hvp',
            'filearea'=> 'libraries',
            'itemid' => 0,
            'filepath' => '/' . \H5PCore::libraryToString($library, TRUE) . '/',
        );

        // Remove any old existing library files
        self::deleteFileTree($context->id, $options['filearea'], $options['filepath']);

        // Move library folder
        self::readFileTree($library['uploadDirectory'], $options);
    }

    /**
     * Store the content folder.
     *
     * @param string $source
     *  Path on file system to content directory.
     * @param int $id
     *  What makes this content unique.
     */
    public function saveContent($source, $id) {
        global $COURSE;

        // Remove any old content
        $this->deleteContent($id);

        // Contents are stored in a course context
        $context = \context_course::instance($COURSE->id);
        $options = array(
            'contextid' => $context->id,
            'component' => 'mod_hvp',
            'filearea'=> 'content',
            'itemid' => $id,
            'filepath' => '/',
        );

        // Move content folder
        self::readFileTree($source, $options);
    }

    /**
     * Remove content folder.
     *
     * @param int $id
     *  Content identifier
     */
    public function deleteContent($id) {
        global $COURSE;

        $context = \context_course::instance($COURSE->id);
        self::deleteFileTree($context->id, 'content', '/', $id);
    }

    /**
     * Creates a stored copy of the content folder.
     *
     * @param string $id
     *  Path on file system to content directory.
     * @param int $newId
     *  What makes this content unique.
     */
    public function cloneContent($id, $newId) {
        // Not implemented in Moodle
    }

    /**
     * Get path to a new unique tmp folder.
     *
     * @return string Path
     */
    public function getTmpPath() {
        global $CFG;

        return $CFG->tempdir . uniqid('/hvp-');
    }

    /**
     * Fetch content folder and save in target directory.
     *
     * @param int $id
     *  Content identifier
     * @param string $target
     *  Where the content folder will be saved
     */
    public function exportContent($id, $target) {
        global $COURSE;

        $context = \context_course::instance($COURSE->id);
        self::exportFileTree($target, $context->id, 'content', '/', $id);
    }

    /**
     * Fetch library folder and save in target directory.
     *
     * @param array $library
     *  Library properties
     * @param string $target
     *  Where the library folder will be saved
     */
    public function exportLibrary($library, $target) {
        $folder = \H5PCore::libraryToString($library, TRUE);
        $context = \context_system::instance();
        self::exportFileTree("{$target}/{$folder}", $context->id, 'libraries', "/{$folder}/");
    }

    /**
     * Save export in file system
     *
     * @param string $source
     *  Path on file system to temporary export file.
     * @param string $filename
     *  Name of export file.
     */
    public function saveExport($source, $filename) {
        global $COURSE;

        // Remove old export
        $this->deleteExport($filename);

        // Create record
        $context = \context_course::instance($COURSE->id);
        $record = array(
            'contextid' => $context->id,
            'component' => 'mod_hvp',
            'filearea'=> 'exports',
            'itemid' => 0,
            'filepath' => '/',
            'filename' => $filename
        );

        // Store new export
        $fs = get_file_storage();
        $fs->create_file_from_pathname($record, $source);
    }

    /**
     * Removes given export file
     *
     * @param string $filename
     */
    public function deleteExport($filename) {
        global $COURSE;
        $context = \context_course::instance($COURSE->id);

        // Check if file exists
        $fs = get_file_storage();
        $file = $fs->get_file($context->id, 'mod_hvp', 'exports', 0, '/', $filename);
        if ($file) {
            // Remove old export
            $file->delete();
        }
    }

    /**
     * Will concatenate all JavaScrips and Stylesheets into two files in order
     * to improve page performance.
     *
     * @param array $files
     *  A set of all the assets required for content to display
     * @param string $key
     *  Hashed key for cached asset
     */
    public function cacheAssets(&$files, $key) {
        $context = \context_system::instance();
        $fs = get_file_storage();

        foreach ($files as $type => $assets) {
            if (empty($assets)) {
              continue;
            }

            $content = '';
            foreach ($assets as $asset) {
                // Find location of asset
                $location = array();
                preg_match('/^\/(libraries|development)(.+\/)([^\/]+)$/', $asset->path, $location);

                // Locate file
                $file = $fs->get_file($context->id, 'mod_hvp', $location[1], 0, $location[2], $location[3]);

                // Get file content and concatenate
                if ($type === 'scripts') {
                    $content .= $file->get_content() . ";\n";
                }
                else {
                    // Rewrite relative URLs used inside stylesheets
                    $content .= preg_replace_callback(
                            '/url\([\'"]?([^"\')]+)[\'"]?\)/i',
                            function ($matches) use ($location) {
                                if (preg_match("/^(data:|([a-z0-9]+:)?\/)/i", $matches[1]) === 1) {
                                  return $matches[0]; // Not relative, skip
                                }
                                return 'url("../' . $location[1] . $location[2] . $matches[1] . '")';
                            },
                            $file->get_content()) . "\n";
                }
            }

            // Create new file for cached assets
            $ext = ($type === 'scripts' ? 'js' : 'css');
            $fileinfo = array(
                'contextid' => $context->id,
                'component' => 'mod_hvp',
                'filearea' => 'cachedassets',
                'itemid' => 0,
                'filepath' => '/',
                'filename' => "{$key}.{$ext}"
            );

            // Store concatenated content
            $fs->create_file_from_string($fileinfo, $content);
            $files[$type] = array((object) array(
                'path' => "/cachedassets/{$key}.{$ext}",
                'version' => ''
            ));
        }
    }

    /**
     * Will check if there are cache assets available for content.
     *
     * @param string $key
     *  Hashed key for cached asset
     * @return array
     */
    public function getCachedAssets($key) {
        $context = \context_system::instance();
        $fs = get_file_storage();

        $files = array();

        $js = $fs->get_file($context->id, 'mod_hvp', 'cachedassets', 0, '/', "{$key}.js");
        if ($js) {
            $files['scripts'] = array((object) array(
                'path' => "/cachedassets/{$key}.js",
                'version' => ''
            ));
        }

        $css = $fs->get_file($context->id, 'mod_hvp', 'cachedassets', 0, '/', "{$key}.css");
        if ($css) {
            $files['styles'] = array((object) array(
                'path' => "/cachedassets/{$key}.css",
                'version' => ''
            ));
        }

        return empty($files) ? NULL : $files;
    }

    /**
     * Remove the aggregated cache files.
     *
     * @param array $keys
     *   The hash keys of removed files
     */
    public function deleteCachedAssets($keys) {
        $context = \context_system::instance();
        $fs = get_file_storage();

        foreach ($keys as $hash) {
            foreach (array('js', 'css') as $type) {
                $cachedasset = $fs->get_file($context->id, 'mod_hvp', 'cachedassets', 0, '/', "{$hash}.{$type}");
                if ($cachedasset) {
                    $cachedasset->delete();
                }
            }
        }
    }

    /**
     * Copies files from tmp folder to Moodle storage.
     *
     * @param string $source
     *  Path to source directory
     * @param array $options
     *  For Moodle's file record
     * @throws \Exception Unable to copy
     */
    private static function readFileTree($source, $options) {
        $dir = opendir($source);
        if ($dir === FALSE) {
            trigger_error('Unable to open directory ' . $source, E_USER_WARNING);
            throw new \Exception('unabletocopy');
        }

        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..') && $file != '.git' && $file != '.gitignore') {
                if (is_dir($source . DIRECTORY_SEPARATOR . $file)) {
                    $sub_options = $options;
                    $sub_options['filepath'] .= $file . '/';
                    self::readFileTree($source . '/' . $file, $sub_options);
                }
                else {
                    $record = $options;
                    $record['filename'] = $file;
                    $fs = get_file_storage();
                    $fs->create_file_from_pathname($record, $source . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    /**
     * Copies files from Moodle storage to temporary folder.
     *
     * @param string $target
     *  Path to temporary folder
     * @param int $contextid
     *  Moodle context where the files are found
     * @param string $filearea
     *  Moodle file area
     * @param string $filepath
     *  Moodle file path
     * @param int $itemid
     *  Optional Moodle item ID
     */
    private static function exportFileTree($target, $contextid, $filearea, $filepath, $itemid = 0) {
        // Make sure target folder exists
        if (!file_exists($target)) {
            mkdir($target, 0777, true);
        }

        // Read source files
        $fs = get_file_storage();
        $files = $fs->get_directory_files($contextid, 'mod_hvp', $filearea, $itemid, $filepath, true);

        foreach ($files as $file) {
            // Correct target path for file
            $path = $target . str_replace($filepath, '/', $file->get_filepath());

            if ($file->is_directory()) {
                // Create directory
                $path = rtrim($path, '/');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
            }
            else {
                // Copy file
                $file->copy_content_to($path . $file->get_filename());
            }
        }
    }

    /**
     * Recursive removal of given filepath.
     *
     * @param int $contextid
     * @param string $filearea
     * @param string $filepath
     * @param int $itemid
     */
    private static function deleteFileTree($contextid, $filearea, $filepath, $itemid = 0) {
        $fs = get_file_storage();
        if ($filepath === '/') {
            // Remove complete file area
            $fs->delete_area_files($contextid, 'mod_hvp', $filearea, $itemid);
            return;
        }

        // Look up files and remove
        $files = $fs->get_directory_files($contextid, 'mod_hvp', $filearea, $itemid, $filepath, true);
        foreach ($files as $file) {
            $file->delete();
        }

        // Remove root dir
        $file = $fs->get_file($contextid, 'mod_hvp', $filearea, $itemid, $filepath, '.');
        if ($file) {
            $file->delete();
        }
    }

    /**
     * Checks if a file exists
     *
     * @method fileExists
     * @param  string     $filearea [description]
     * @param  string     $filepath [description]
     * @param  string     $filename [description]
     * @return boolean
     */
    public static function fileExists($contextid, $filearea, $filepath, $filename) {
        // Check if file exists
        $fs = get_file_storage();
        return ($fs->get_file($contextid, 'mod_hvp', $filearea, 0, $filepath, $filename) !== false);
    }
}
