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
        // Libraries are stored in a system context.
        $context = \context_system::instance();
        $options = array(
            'contextid' => $context->id,
            'component' => 'mod_hvp',
            'filearea' => 'libraries',
            'itemid' => 0,
            'filepath' => '/' . \H5PCore::libraryToString($library, true) . '/',
        );

        // Remove any old existing library files.
        self::deleteFileTree($context->id, $options['filearea'], $options['filepath']);

        // Move library folder.
        self::readFileTree($library['uploadDirectory'], $options);
    }

    /**
     * Store the content folder.
     *
     * @param string $source
     *  Path on file system to content directory.
     * @param array $content
     *  Content properties
     */
    public function saveContent($source, $content) {
        // Remove any old content.
        $this->deleteContent($content);

        // Contents are stored in a course context.
        $context = \context_module::instance($content['coursemodule']);
        $options = array(
            'contextid' => $context->id,
            'component' => 'mod_hvp',
            'filearea' => 'content',
            'itemid' => $content['id'],
            'filepath' => '/',
        );

        // Move content folder.
        self::readFileTree($source, $options);
    }

    /**
     * Remove content folder.
     *
     * @param array $content
     *  Content properties
     */
    public function deleteContent($content) {
        $context = \context_module::instance($content['coursemodule']);
        self::deleteFileTree($context->id, 'content', '/', $content['id']);
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
        // Not implemented in Moodle.
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
        $cm = \get_coursemodule_from_instance('hvp', $id);
        $context = \context_module::instance($cm->id);
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
        $folder = \H5PCore::libraryToString($library, true);
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

        // Remove old export.
        $this->deleteExport($filename);

        // Create record.
        $context = \context_course::instance($COURSE->id);
        $record = array(
            'contextid' => $context->id,
            'component' => 'mod_hvp',
            'filearea' => 'exports',
            'itemid' => 0,
            'filepath' => '/',
            'filename' => $filename
        );

        // Store new export.
        $fs = get_file_storage();
        $fs->create_file_from_pathname($record, $source);
    }

    /**
     * Get file object for given export file.
     *
     * @param string $filename
     * @return stdClass Moodle file object
     */
    private function getExportFile($filename) {
        global $COURSE;
        $context = \context_course::instance($COURSE->id);

        // Check if file exists.
        $fs = get_file_storage();
        return $fs->get_file($context->id, 'mod_hvp', 'exports', 0, '/', $filename);
    }

    /**
     * Removes given export file
     *
     * @param string $filename
     */
    public function deleteExport($filename) {
        $file = $this->getExportFile($filename);
        if ($file) {
            // Remove old export.
            $file->delete();
        }
    }

    /**
     * Check if the given export file exists
     *
     * @param string $filename
     * @return boolean
     */
    public function hasExport($filename) {
      return !! $this->getExportFile($filename);
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
                // Find location of asset.
                $location = array();
                preg_match('/^\/(libraries|development)(.+\/)([^\/]+)$/', $asset->path, $location);

                // Locate file.
                $file = $fs->get_file($context->id, 'mod_hvp', $location[1], 0, $location[2], $location[3]);

                // Get file content and concatenate.
                if ($type === 'scripts') {
                    $content .= $file->get_content() . ";\n";
                } else {
                    // Rewrite relative URLs used inside stylesheets.
                    $content .= preg_replace_callback(
                            '/url\([\'"]?([^"\')]+)[\'"]?\)/i',
                            function ($matches) use ($location) {
                                if (preg_match("/^(data:|([a-z0-9]+:)?\/)/i", $matches[1]) === 1) {
                                    return $matches[0]; // Not relative, skip.
                                }
                                return 'url("../' . $location[1] . $location[2] . $matches[1] . '")';
                            },
                            $file->get_content()) . "\n";
                }
            }

            // Create new file for cached assets.
            $ext = ($type === 'scripts' ? 'js' : 'css');
            $fileinfo = array(
                'contextid' => $context->id,
                'component' => 'mod_hvp',
                'filearea' => 'cachedassets',
                'itemid' => 0,
                'filepath' => '/',
                'filename' => "{$key}.{$ext}"
            );

            // Store concatenated content.
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

        return empty($files) ? null : $files;
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
     * Read file content of given file and then return it.
     *
     * @param string $file_path
     * @return string
     */
    public function getContent($file_path) {
      // Grab context and file storage
      $context = \context_system::instance();
      $fs = get_file_storage();

      // Find location of file
      $location = array();
      preg_match('/^\/(libraries|development|cachedassets)(.*\/)([^\/]+)$/', $file_path, $location);

      // Locate file
      $file = $fs->get_file($context->id, 'mod_hvp', $location[1], 0, $location[2], $location[3]);

      // Return content
      return $file->get_content();
    }

    /**
     * Save files uploaded through the editor.
     *
     * @param \H5peditorFile $file
     * @param int $contentid
     * @param \stdClass $context Course Context ID
     */
    public function saveFile($file, $contentid, $contextid = null) {
        if ($contentid !== 0) {
            // Grab cm context
            $cm = \get_coursemodule_from_instance('hvp', $contentid);
            $context = \context_module::instance($cm->id);
            $contextid = $context->id;
        }

        // Files not yet related to any activities are stored in a course context
        // (These are temporary files and should not be part of backups.)

        $record = array(
            'contextid' => $contextid,
            'component' => 'mod_hvp',
            'filearea' => $contentid === 0 ? 'editor' : 'content',
            'itemid' => $contentid,
            'filepath' => '/' . $file->getType() . 's/',
            'filename' => $file->getName()
        );
        $fs = get_file_storage();
        $filedata = $file->getData();
        if ($filedata) {
            $stored_file = $fs->create_file_from_string($record, $filedata);
        }
        else {
            $stored_file = $fs->create_file_from_pathname($record, $_FILES['file']['tmp_name']);
        }

        return $stored_file->get_id();
    }

    /**
     * Copy a file from another content or editor tmp dir.
     * Used when copy pasting content in H5P.
     *
     * @param string $file path + name
     * @param string|int $fromid Content ID or 'editor' string
     * @param stdClass $tocontent Target Content
     */
    public function cloneContentFile($file, $fromid, $tocontent) {
      // Determine source file area and item id
      $sourcefilearea = ($fromid === 'editor' ? $fromid : 'content');
      $sourceitemid = ($fromid === 'editor' ? 0 : $fromid);

      // Check to see if source exist
      $sourcefile = $this->getFile($sourcefilearea, $sourceitemid, $file);
      if ($sourcefile === false) {
          return; // Nothing to copy from
      }

      // Check to make sure source doesn't exist already
      if ($this->getFile('content', $tocontent, $file) !== false) {
          return; // File exists, no need to copy
      }

      // Grab context for CM
      $context = \context_module::instance($tocontent->coursemodule);

      // Create new file record
      $record = array(
          'contextid' => $context->id,
          'component' => 'mod_hvp',
          'filearea' => 'content',
          'itemid' => $tocontent->id,
          'filepath' => $this->getFilepath($file),
          'filename' => $this->getFilename($file)
      );
      $fs = get_file_storage();
      $fs->create_file_from_storedfile($record, $sourcefile);
    }

    /**
     * Checks to see if content has the given file.
     * Used when saving content.
     *
     * @param string $file path + name
     * @param stdClass $content
     * @return string|int File ID or NULL if not found
     */
    public function getContentFile($file, $content) {
        $file = $this->getFile('content', $content, $file);
        return ($file === false ? null : $file->get_id());
    }

    /**
     * Remove content files that are no longer used.
     * Used when saving content.
     *
     * @param string $file path + name
     * @param stdClass $content
     */
    public function removeContentFile($file, $content) {
        $file = $this->getFile('content', $content, $file);
        if ($file !== false) {
            $file->delete();
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
        if ($dir === false) {
            trigger_error('Unable to open directory ' . $source, E_USER_WARNING);
            throw new \Exception('unabletocopy');
        }

        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..') && $file != '.git' && $file != '.gitignore') {
                if (is_dir($source . DIRECTORY_SEPARATOR . $file)) {
                    $suboptions = $options;
                    $suboptions['filepath'] .= $file . '/';
                    self::readFileTree($source . '/' . $file, $suboptions);
                } else {
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
        // Make sure target folder exists.
        if (!file_exists($target)) {
            mkdir($target, 0777, true);
        }

        // Read source files.
        $fs = get_file_storage();
        $files = $fs->get_directory_files($contextid, 'mod_hvp', $filearea, $itemid, $filepath, true);

        foreach ($files as $file) {
            // Correct target path for file.
            $path = $target . str_replace($filepath, '/', $file->get_filepath());

            if ($file->is_directory()) {
                // Create directory.
                $path = rtrim($path, '/');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
            } else {
                // Copy file.
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
            // Remove complete file area.
            $fs->delete_area_files($contextid, 'mod_hvp', $filearea, $itemid);
            return;
        }

        // Look up files and remove.
        $files = $fs->get_directory_files($contextid, 'mod_hvp', $filearea, $itemid, $filepath, true);
        foreach ($files as $file) {
            $file->delete();
        }

        // Remove root dir.
        $file = $fs->get_file($contextid, 'mod_hvp', $filearea, $itemid, $filepath, '.');
        if ($file) {
            $file->delete();
        }
    }

    /**
     * Help make it easy to load content files.
     *
     * @param string $filearea
     * @param int|stdClass $itemid
     * @param string $file path + name
     */
    private function getFile($filearea, $itemid, $file) {
        global $COURSE;

        if ($filearea === 'editor') {
            // Use Course context
            $context = \context_course::instance($COURSE->id);
        }
        elseif (is_object($itemid)) {
            // Grab CM context from item
            $context = \context_module::instance($itemid->coursemodule);
            $itemid = $itemid->id;
        }
        else {
            // Use item ID to find CM context
            $cm = \get_coursemodule_from_instance('hvp', $itemid);
            $context = \context_module::instance($cm->id);
        }

        // Load file
        $fs = get_file_storage();
        return $fs->get_file($context->id, 'mod_hvp', $filearea, $itemid, $this->getFilepath($file), $this->getFilename($file));
    }

    /**
     * Extract Moodle compatible filepath
     *
     * @param string $file
     * @return string With slashes
     */
    private function getFilepath($file) {
        return '/' . dirname($file) . '/';
    }

    /**
     * Extract filename from filepath string
     *
     * @param string $file
     * @return string Without slashes
     */
    private function getFilename($file) {
        return basename($file);
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
        // Check if file exists.
        $fs = get_file_storage();
        return ($fs->get_file($contextid, 'mod_hvp', $filearea, 0, $filepath, $filename) !== false);
    }
}
