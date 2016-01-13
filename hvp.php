<?php

/**
 * Hvp specific lib functions and H5P Framework Interface implementation.
 *
 * @package    mod
 * @subpackage hvp
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// TODO: Can we rename this file to something more suiting? Possible confusion

if (!defined('MOODLE_INTERNAL')) {
  die('Direct access to this script is forbidden.');
}

require_once($CFG->dirroot . '/mod/hvp/library/h5p-file-storage.interface.php');
require_once($CFG->dirroot . '/mod/hvp/classes/file_storage.php');
require_once($CFG->dirroot . '/mod/hvp/library/h5p.classes.php');
require_once($CFG->dirroot . '/mod/hvp/library/h5p-development.class.php');
require_once(__DIR__ . '/classes/content_user_data.php');

/**
 * Get type of hvp instance
 *
 * @param string $type Type of hvp instance to get
 * @return \H5PContentValidator|\H5PCore|\H5PMoodle|\H5PStorage|\H5PValidator
 */
function hvp_get_instance($type) {
  global $CFG;
  static $interface, $core;

  if (!isset($interface)) {
    $interface = new H5PMoodle();

    $fs = new \mod_hvp\file_storage();

    $context = \context_system::instance();
    $url = "/pluginfile.php/{$context->id}/mod_hvp";

    $language = current_language();

    $export = !(isset($CFG->mod_hvp_export) && $CFG->mod_hvp_export === '0');

    $core = new H5PCore($interface, $fs, $url, $language, $export);
    $core->aggregateAssets = !(isset($CFG->mod_hvp_aggregate_assets) && $CFG->mod_hvp_aggregate_assets === '0');
  }

  switch ($type) {
    case 'validator':
      return new H5PValidator($interface, $core);
    case 'storage':
      return new H5PStorage($interface, $core);
    case 'contentvalidator':
      return new H5PContentValidator($interface, $core);
    case 'interface':
      return $interface;
    case 'core':
      return $core;
  }
}

/**
 * Get array with settings for hvp core
 *
 * @return array Settings
 */
function hvp_get_core_settings() {
  global $USER, $CFG, $COURSE;

  $basePath = $CFG->sessioncookiepath;
  $ajaxPath = $basePath . 'mod/hvp/ajax.php?action=';

  $system_context = \context_system::instance();
  $course_context = \context_course::instance($COURSE->id);
  $settings = array(
    'baseUrl' => $basePath,
    'url' => "{$basePath}pluginfile.php/{$course_context->id}/mod_hvp",
    'libraryUrl' => "{$basePath}pluginfile.php/{$system_context->id}/mod_hvp/libraries",
    'postUserStatistics' => FALSE, // TODO: Add when grades are implemented
    'ajaxPath' => $ajaxPath,
    'ajax' => array(
      'contentUserData' => $ajaxPath . 'contents_user_data&content_id=:contentId&data_type=:dataType&sub_content_id=:subContentId'
    ),
    'saveFreq' => get_config('hvp', 'enable_save_content_state') ? get_config('hvp', 'content_state_frequency') : FALSE,
    'siteUrl' => $CFG->wwwroot,
    'l10n' => array(
      'H5P' => array(
        'fullscreen' => get_string('fullscreen', 'hvp'),
        'disableFullscreen' => get_string('disablefullscreen', 'hvp'),
        'download' => get_string('download', 'hvp'),
        'copyrights' => get_string('copyright', 'hvp'),
        'copyrightInformation' => get_string('copyright', 'hvp'),
        'close' => get_string('close', 'hvp'),
        'title' => get_string('title', 'hvp'),
        'author' => get_string('author', 'hvp'),
        'year' => get_string('year', 'hvp'),
        'source' => get_string('source', 'hvp'),
        'license' => get_string('license', 'hvp'),
        'thumbnail' => get_string('thumbnail', 'hvp'),
        'noCopyrights' =>  get_string('nocopyright', 'hvp'),
        'downloadDescription' => get_string('downloadtitle', 'hvp'),
        'copyrightsDescription' => get_string('copyrighttitle', 'hvp'),
        'h5pDescription' => get_string('h5ptitle', 'hvp'),
        'contentChanged' => get_string('contentchanged', 'hvp'),
        'startingOver' => get_string('startingover', 'hvp')
      )
    ),
    'user' => array(
      'name' => $USER->firstname . ' ' . $USER->lastname,
      'mail' => $USER->email
    )
  );

  return $settings;
}

/**
 * Get assets (scripts and styles) for hvp core.
 *
 * @return array
 */
function hvp_get_core_assets() {
  global $CFG, $PAGE;

  // Get core settings
  $settings = hvp_get_core_settings();
  $settings['core'] = array(
    'styles' => array(),
    'scripts' => array()
  );
  $settings['loadedJs'] = array();
  $settings['loadedCss'] = array();

  // Make sure files are reloaded for each plugin update
  $cache_buster = '?ver=1'; // TODO: . get_component_version('mod_hvp'); ?

  // Use relative URL to support both http and https.
  $lib_url = $CFG->httpswwwroot . '/mod/hvp/library/';
  $rel_path = '/' . preg_replace('/^[^:]+:\/\/[^\/]+\//', '', $lib_url);

  // Add core stylesheets
  foreach (H5PCore::$styles as $style) {
    $settings['core']['styles'][] = $rel_path . $style . $cache_buster;
    $PAGE->requires->css(new moodle_url($lib_url . $style . $cache_buster));
  }
  // Add core JavaScript
  foreach (H5PCore::$scripts as $script) {
    $settings['core']['scripts'][] = $rel_path . $script . $cache_buster;
    $PAGE->requires->js(new moodle_url($lib_url . $script . $cache_buster), true);
  }

  return $settings;
}

/**
 * Implementation of the H5P framework interface.
 */
class H5PMoodle implements H5PFrameworkInterface {
  /**
   * Implements getPlatformInfo
   */
  public function getPlatformInfo() {
    global $CFG;

    return array(
      'name' => 'Moodle',
      'version' => $CFG->version,
      'h5pVersion' => get_component_version('mod_hvp'),
    );
  }

  /**
   * Implements fetchExternalData
   *
   * @param $url file url starting with http(s)://
   * @return bool|null|\stdClass|string Data object if successful fetch
   */
  public function fetchExternalData($url) {
    $data = download_file_content($url);
    return ($data === false ? NULL : $data);
  }

  /**
   * Implements setLibraryTutorialUrl
   *
   * Set the tutorial URL for a library. All versions of the library is set
   *
   * @param string $library_name
   * @param string $url
   */
  public function setLibraryTutorialUrl($library_name, $url) {
    global $DB;

    $DB->execute("UPDATE {hvp_libraries} SET tutorial_url = ? WHERE machine_name = ?", array($url, $library_name));
  }

  /**
   * Implements setErrorMessage
   *
   * @param string $message translated error message
   */
  public function setErrorMessage($message) {
    // TODO: Change core, do not send in translated error messages.
    debugging($message, DEBUG_DEVELOPER);
    //print_error($message, 'hvp');
  }

  /**
   * Implements setInfoMessage
   */
  public function setInfoMessage($message) {
    // TODO: ???
    //$OUTPUT->notification($message, 'notifysuccess');
    //echo $message;
  }

  /**
   * Implements t
   */
  public function t($message, $replacements = array()) {
    // TODO: Change core, use keywords for translation.
    return str_replace(array_keys($replacements), $replacements, $message);
    //debugging($message, DEBUG_DEVELOPER);
    //return get_string($message, 'hvp', $replacements);
  }

  /**
   * Implements getH5PPath
   */
  public function getH5pPath() {
    global $CFG;

    return $CFG->dirroot . '/mod/hvp/files';
  }

  /**
   * Implements getUploadedH5PFolderPath
   */
  public function getUploadedH5pFolderPath($setPath = NULL) {
    static $path;

    if ($setPath !== NULL) {
      $path = $setPath;
    }

    if (!isset($path)) {
      throw new coding_exception('Using getUploadedH5pFolderPath() before path is set');
    }

    return $path;
  }

  /**
   * Implements getUploadedH5PPath
   */
  public function getUploadedH5pPath($setPath = NULL) {
    static $path;

    if ($setPath !== NULL) {
      $path = $setPath;
    }

    if (!isset($path)) {
      throw new coding_exception('Using getUploadedH5pPath() before path is set');
    }

    return $path;
  }

  /**
   * Implements loadLibraries
   */
  public function loadLibraries() {
    global $DB;

    $results = $DB->get_records_sql(
      "SELECT id, machine_name, title, major_version, minor_version, patch_version, runnable
      FROM {hvp_libraries}
      ORDER BY title ASC, major_version ASC, minor_version ASC"
    );

    $libraries = array();
    foreach ($results as $library) {
      $libraries[$library->machine_name][] = $library;
    }

    return $libraries;
  }

  /**
   * Implements setUnsupportedLibraries.
   */
  public function setUnsupportedLibraries($libraries) {
    // Not supported
  }

  /**
   * Implements getUnsupportedLibraries.
   */
  public function getUnsupportedLibraries() {
    // Not supported
  }

  /**
   * Implements getAdminUrl.
   */
  public function getAdminUrl() {
    // Not supported
  }

  /**
   * Implements getLibraryId
   */
  public function getLibraryId($machineName, $majorVersion, $minorVersion) {
    global $DB;

    $library = $DB->get_record('hvp_libraries', array(
      'machine_name' => $machineName,
      'major_version' => $majorVersion,
      'minor_version' => $minorVersion
    ));

    return $library ? $library->id : FALSE;
  }

  /**
   * Implements isPatchedLibrary
   */
  public function isPatchedLibrary($library) {
    global $DB, $CFG;

    if (isset($CFG->mod_hvp_dev) && $CFG->mod_hvp_dev) {
      // Makes sure libraries are updated, patch version does not matter.
      return TRUE;
    }

    $operator = $this->isInDevMode() ? '<=' : '<';
    $library = $DB->get_record_sql(
        'SELECT id
          FROM {hvp_libraries}
            WHERE machine_name = ?
            AND major_version = ?
            AND minor_version = ?
            AND patch_version ' . $operator . ' ?',
          array($library['machineName'],
          $library['majorVersion'],
          $library['minorVersion'],
          $library['patchVersion'])
    );

    return $library ? TRUE : FALSE;
  }

  /**
   * Implements isInDevMode
   */
  public function isInDevMode() {
    return FALSE; // Not supported (Files in moodle not editable)
  }

  /**
   * Implements mayUpdateLibraries
   */
  public function mayUpdateLibraries() {
    return TRUE; // TODO: Add capability to manage libraries
  }

  /**
   * Implements getLibraryUsage
   *
   * Get number of content/nodes using a library, and the number of
   * dependencies to other libraries
   *
   * @param int $libraryId
   * @return array The array contains two elements, keyed by 'content' and 'libraries'.
   *               Each element contains a number
   */
  public function getLibraryUsage($id, $skipContent = FALSE) {
    global $DB;

    if ($skipContent) {
      $content = -1;
    }
    else {
      $content = intval($DB->get_field_sql(
        "SELECT COUNT(distinct c.id)
        FROM {hvp_libraries} l
        JOIN {hvp_contents_libraries} cl ON l.id = cl.library_id
        JOIN {hvp} c ON cl.hvp_id = c.id
        WHERE l.id = $id"
      ));
    }

    $libraries = intval($DB->get_field_sql(
      "SELECT COUNT(*)
      FROM {hvp_libraries_libraries}
      WHERE required_library_id = $id"
    ));

    return array(
      'content' => $content,
      'libraries' => $libraries,
    );
  }

  /**
   * Implements saveLibraryData
   */
  public function saveLibraryData(&$libraryData, $new = TRUE) {
    global $DB;

    // Some special properties needs some checking and converting before they can be saved.
    $preloadedJs = $this->pathsToCsv($libraryData, 'preloadedJs');
    $preloadedCss = $this->pathsToCsv($libraryData, 'preloadedCss');
    $dropLibraryCss = '';

    if (isset($libraryData['dropLibraryCss'])) {
      $libs = array();
      foreach ($libraryData['dropLibraryCss'] as $lib) {
        $libs[] = $lib['machineName'];
      }
      $dropLibraryCss = implode(', ', $libs);
    }

    $embedTypes = '';
    if (isset($libraryData['embedTypes'])) {
      $embedTypes = implode(', ', $libraryData['embedTypes']);
    }
    if (!isset($libraryData['semantics'])) {
      $libraryData['semantics'] = '';
    }
    if (!isset($libraryData['fullscreen'])) {
      $libraryData['fullscreen'] = 0;
    }
    // TODO: Can we move the above code to H5PCore? It's the same for multiple
    // implementations. Perhaps core can update the data objects before calling
    // this function?

    if ($new) {
      // Create new library
      $library = (object) array(
        'machine_name' => $libraryData['machineName'],
        'title' => $libraryData['title'],
        'major_version' => $libraryData['majorVersion'],
        'minor_version' => $libraryData['minorVersion'],
        'patch_version' => $libraryData['patchVersion'],
        'runnable' => $libraryData['runnable'],
        'fullscreen' => $libraryData['fullscreen'],
        'embed_types' => $embedTypes,
        'preloaded_js' => $preloadedJs,
        'preloaded_css' => $preloadedCss,
        'drop_library_css' => $dropLibraryCss,
        'semantics' => $libraryData['semantics'],
      );

      // Save new library and keep track of id
      $library->id = $DB->insert_record('hvp_libraries', $library);
      $libraryData['libraryId'] = $library->id;
    }
    else {
      // Update library data
      $library['id'] = $libraryData['libraryId'];
      $library['title'] = $libraryData['title'];
      $library['patch_version'] = $libraryData['patchVersion'];
      $library['runnable'] = $libraryData['runnable'];
      $library['fullscreen'] = $libraryData['fullscreen'];
      $library['embed_types'] = $embedTypes;
      $library['preloaded_js'] = $preloadedJs;
      $library['preloaded_css'] = $preloadedCss;
      $library['drop_library_css'] = $dropLibraryCss;
      $library['semantics'] = $libraryData['semantics'];

      // Save library data
      $DB->update_record('hvp_libraries', (object) $library);

      // Remove old dependencies
      $this->deleteLibraryDependencies($libraryData['libraryId']);
    }

    // Update library translations
    $DB->delete_records('hvp_libraries_languages', array('library_id' => $libraryData['libraryId']));
    if (isset($libraryData['language'])) {
      foreach ($libraryData['language'] as $languageCode => $languageJson) {
        $DB->insert_record('hvp_libraries_languages', array(
          'library_id' => $libraryData['libraryId'],
          'language_code' => $languageCode,
          'language_json' => $languageJson,
        ));
      }
    }
  }

  /**
   * Convert list of file paths to csv
   *
   * @param array $libraryData
   *  Library data as found in library.json files
   * @param string $key
   *  Key that should be found in $libraryData
   * @return string
   *  file paths separated by ', '
   */
  private function pathsToCsv($libraryData, $key) {
    // TODO: Move to core?
    if (isset($libraryData[$key])) {
      $paths = array();
      foreach ($libraryData[$key] as $file) {
        $paths[] = $file['path'];
      }
      return implode(', ', $paths);
    }
    return '';
  }

  /**
   * Implements lockDependencyStorage
   */
  public function lockDependencyStorage() {
    // Library development mode not supported
  }

  /**
   * Implements unlockDependencyStorage
   */
  public function unlockDependencyStorage() {
    // Library development mode not supported
  }

  /**
   * Implements deleteLibrary
   */
  public function deleteLibrary($library) {
    global $DB;

    // Delete library files
    H5PCore::deleteFileTree($this->getH5pPath() . '/libraries/' . $library->name . '-' . $library->major_version . '.' . $library->minor_version);

    // Remove library data from database
    $DB->delete('hvp_libraries_libraries', array('library_id' => $library->id));
    $DB->delete('hvp_libraries_languages', array('library_id' => $library->id));
    $DB->delete('hvp_libraries', array('id' => $library->id));
  }

  /**
   * Implements saveLibraryDependencies
   */
  public function saveLibraryDependencies($libraryId, $dependencies, $dependency_type) {
    global $DB;

    foreach ($dependencies as $dependency) {
      // Find dependency library.
      $dependencyLibrary = $DB->get_record('hvp_libraries', array(
        'machine_name' => $dependency['machineName'],
        'major_version' => $dependency['majorVersion'],
        'minor_version' => $dependency['minorVersion']
      ));

      // Create relation.
      $DB->insert_record('hvp_libraries_libraries', array(
        'library_id' => $libraryId,
        'required_library_id' => $dependencyLibrary->id,
        'dependency_type' => $dependency_type
      ));
    }
  }

  /**
   * Implements updateContent
   *
   * Inserts or updates H5P content.
   *
   * @param array $content
   *   An associative array containing:
   *   - id: The content id
   *   - params: The content in json format
   *   - library: An associative array containing:
   *     - libraryId: The id of the main library for this content
   * @param int $contentMainId
   *   Main id for the content if this is a system that supports versioning
   */
  public function updateContent($content, $contentMainId = NULL) {
    global $DB;

    if (!isset($content['disable'])) {
      $content['disable'] = H5PCore::DISABLE_NONE;
      // TODO: Can be removed when this has been fixed:
      // https://github.com/h5p/h5p-moodle-plugin/issues/16
    }

    $data = array(
      'id' => $content['id'],
      'json_content' => $content['params'],
      'embed_type' => 'div',
      'main_library_id' => $content['library']['libraryId'],
      'filtered' => '',
      'disable' => $content['disable'],
    );

    if (!isset($content['id'])) {
      $data['slug'] = '';
      return $DB->insert_record('hvp', $data);
    }
    else {
      $DB->update_record('hvp', $data);
      return $data['id'];
    }
  }

  /**
   * Implements insertContent
   */
  public function insertContent($content, $contentMainId = NULL) {
    return $this->updateContent($content);
  }

  /**
   * Implements resetContentUserData
   */
  public function resetContentUserData($contentId) {
    global $DB;

    // Reset user data for this content
    /*$DB->execute("UPDATE {hvp_content_user_data}
      SET data = 'RESET'
      WHERE hvp_id = ? AND delete_on_content_change = 1", array($contentId)
    );*/
    // TODO: Fix table def. See WP
  }

  /**
   * Implements getWhitelist
   */
  public function getWhitelist($isLibrary, $defaultContentWhitelist, $defaultLibraryWhitelist) {
    return $defaultContentWhitelist . ($isLibrary ? ' ' . $defaultLibraryWhitelist : '');
  }

  /**
   * Implements copyLibraryUsage
   */
  public function copyLibraryUsage($contentId, $copyFromId, $contentMainId = NULL) {
    global $DB;

    $libraryUsage = $DB->get_record('hvp_contents_libraries', array(
      'id' => $copyFromId
    ));

    $libraryUsage->id = $contentId;
    $DB->insert_record_raw('hvp_contents_libraries', (array)$libraryUsage, false, false, true);

    // TODO: This must be verified at a later time.
    // Currently in Moodle copyLibraryUsage() will never be called.
  }

  /**
   * Implements loadLibrarySemantics
   */
  public function loadLibrarySemantics($name, $majorVersion, $minorVersion) {
    global $DB;

    $semantics = $DB->get_field_sql(
        "SELECT semantics
        FROM {hvp_libraries}
        WHERE machine_name = ?
        AND major_version = ?
        AND minor_version = ?",
        array($name, $majorVersion, $minorVersion)
      );

    return ($semantics === FALSE ? NULL : $semantics);
  }

  /**
   * Implements alterLibrarySemantics
   */
  public function alterLibrarySemantics(&$semantics, $name, $majorVersion, $minorVersion) {
    // TODO: Implement some way to alter semantics
  }

  /**
   * Implements loadContent
   */
  public function loadContent($id) {
    global $DB;

    $data = $DB->get_record_sql(
        "SELECT hc.id
              , hc.name
              , hc.json_content
              , hc.filtered
              , hc.slug
              , hc.embed_type
              , hc.disable
              , hl.id AS library_id
              , hl.machine_name
              , hl.major_version
              , hl.minor_version
              , hl.embed_types
              , hl.fullscreen
        FROM {hvp} hc
        JOIN {hvp_libraries} hl ON hl.id = hc.main_library_id
        WHERE hc.id = ?", array($id)
    );
    // TODO: We cannot use the AS keyword ! !

    // Return NULL if not found
    if ($data === false) {
      return NULL;
    }

    // Some databases do not support camelCase, so we need to manually
    // map the values to the camelCase names used by the H5P core.
    $content = array(
        'id' => $data->id,
        'title' => $data->name,
        'params' => $data->json_content,
        'filtered' => $data->filtered,
        'slug' => $data->slug,
        'embedType' => $data->embed_type,
        'disable' => $data->disable,
        'libraryId' => $data->library_id,
        'libraryName' => $data->machine_name,
        'libraryMajorVersion' => $data->major_version,
        'libraryMinorVersion' => $data->minor_version,
        'libraryEmbedTypes' => $data->embed_types,
        'libraryFullscreen' => $data->fullscreen,
    );

    return $content;
  }

  /**
   * Implements loadContentDependencies
   */
  public function loadContentDependencies($id, $type = NULL) {
    global $DB;

    $query =
        "SELECT hl.id
              , hl.machine_name
              , hl.major_version
              , hl.minor_version
              , hl.patch_version
              , hl.preloaded_css
              , hl.preloaded_js
              , hcl.drop_css
              , hcl.dependency_type
        FROM {hvp_contents_libraries} hcl
        JOIN {hvp_libraries} hl ON hcl.library_id = hl.id
        WHERE hcl.hvp_id = ?";
    $queryArgs = array($id);

    if ($type !== NULL) {
      $query .= " AND hcl.dependency_type = ?";
      $queryArgs[] = $type;
    }

    $query .= " ORDER BY hcl.weight";
    $data = $DB->get_records_sql($query, $queryArgs);

    $dependencies = array();
    foreach ($data as $dependency) {
      $dependencies[] = H5PCore::snakeToCamel($dependency);
    }

    return $dependencies;
  }

  /**
   * Implements getOption().
   */
  public function getOption($name, $default = FALSE) {
    $value = get_config('mod_hvp', $name);
    if ($value === FALSE) {
      return $default;
    }
    return $value;
  }

  /**
   * Implements setOption().
   */
  public function setOption($name, $value) {
    set_config($name, $value, 'mod_hvp');
  }

  /**
   * Implements updateContentFields().
   */
  public function updateContentFields($id, $fields) {
    global $DB;

    $content = new stdClass();
    $content->id = $id;

    foreach ($fields as $name => $value) {
        $content->$name = $value;
    }

    $DB->update_record('hvp', $content);
  }

  /**
   * Implements deleteLibraryDependencies
   */
  public function deleteLibraryDependencies($libraryId) {
    global $DB;

    $DB->delete_records('hvp_libraries_libraries', array('library_id' => "$libraryId"));
  }

  /**
   * Implements deleteContentData
   */
  public function deleteContentData($contentId) {
    global $DB;

    // Remove content
    $DB->delete_records('hvp', array('id' => "$contentId"));

    // Remove content library dependencies
    $this->deleteLibraryUsage($contentId);

    // Remove user data for content
    $DB->delete_records('hvp_content_user_data', array('hvp_id' => "$contentId"));
  }

  /**
   * Implements deleteLibraryUsage
   */
  public function deleteLibraryUsage($contentId) {
    global $DB;

    $DB->delete_records('hvp_contents_libraries', array('hvp_id' => "$contentId"));
  }

  /**
   * Implements saveLibraryUsage
   */
  public function saveLibraryUsage($contentId, $librariesInUse) {
    global $DB;

    $dropLibraryCssList = array();
    foreach ($librariesInUse as $dependency) {
      if (!empty($dependency['library']['dropLibraryCss'])) {
        $dropLibraryCssList = array_merge($dropLibraryCssList, explode(', ', $dependency['library']['dropLibraryCss']));
      }
    }
    // TODO: Consider moving the above code to core. Same for all impl.

    foreach ($librariesInUse as $dependency) {
      $dropCss = in_array($dependency['library']['machineName'], $dropLibraryCssList) ? 1 : 0;
      $DB->insert_record('hvp_contents_libraries', array(
        'hvp_id' => $contentId,
        'library_id' => $dependency['library']['libraryId'],
        'dependency_type' => $dependency['type'],
        'drop_css' => $dropCss,
        'weight' => $dependency['weight']
      ));
    }
  }

  /**
   * Implements loadLibrary
   */
  public function loadLibrary($machineName, $majorVersion, $minorVersion) {
    global $DB;

    $library = $DB->get_record('hvp_libraries', array(
      'machine_name' => $machineName,
      'major_version' => $majorVersion,
      'minor_version' => $minorVersion
    ));

    $libraryData = array(
      'libraryId' => $library->id,
      'machineName' => $library->machine_name,
      'title' => $library->title,
      'majorVersion' => $library->major_version,
      'minorVersion' => $library->minor_version,
      'patchVersion' => $library->patch_version,
      'embedTypes' => $library->embed_types,
      'preloadedJs' => $library->preloaded_js,
      'preloadedCss' => $library->preloaded_css,
      'dropLibraryCss' => $library->drop_library_css,
      'fullscreen' => $library->fullscreen,
      'runnable' => $library->runnable,
      'semantics' => $library->semantics
    );

    $dependencies = $DB->get_records_sql('SELECT hl.machine_name, hl.major_version, hl.minor_version, hll.dependency_type
                                          FROM {hvp_libraries_libraries} hll
                                          JOIN {hvp_libraries} hl ON hll.required_library_id = hl.id
                                          WHERE hll.library_id = ?', array("$library->id"));
    foreach ($dependencies as $dependency) {
      $libraryData[$dependency->dependency_type . 'Dependencies'][] = array(
        'machineName' => $dependency->machine_name,
        'majorVersion' => $dependency->major_version,
        'minorVersion' => $dependency->minor_version
      );
    }

    return $libraryData;
  }

  /**
   * Implements clearFilteredParameters().
   */
  public function clearFilteredParameters($library_id) {
    global $DB;

    $DB->execute("UPDATE {hvp} SET filtered = NULL WHERE main_library_id = ?", array($library_id));
  }

  /**
   * Implements getNumNotFiltered().
   */
  public function getNumNotFiltered() {
    global $DB;

    return (int) $DB->get_field_sql(
      "SELECT COUNT(id)
        FROM {hvp}
        WHERE filtered = ''"
    );
  }

  /**
   * Implements getNumContent().
   */
  public function getNumContent($library_id) {
    global $DB;

    return (int) $DB->get_field_sql("SELECT COUNT(id) FROM {hvp}",
      array('library_id' => $library_id)
    );
  }

  /**
   * Implements isContentSlugAvailable
   */
  public function isContentSlugAvailable($slug) {
    global $DB;

    return !$DB->get_field_sql("SELECT slug FROM {hvp} WHERE slug = ?", array($slug));
  }

  /**
   * Implements saveCachedAssets
   */
  public function saveCachedAssets($key, $libraries) {
    global $DB;

    foreach ($libraries as $library) {
      $cachedAsset = (object) array(
        'library_id' => $library['id'],
        'hash' => $key
      );
      $DB->insert_record('hvp_libraries_cachedassets', $cachedAsset);
    }
  }

  /**
   * Implements deleteCachedAssets
   */
  public function deleteCachedAssets($library_id) {
    global $DB;

    // Get all the keys so we can remove the files
    $results = $DB->get_records_sql(
      'SELECT hash FROM {hvp_libraries_cachedassets} WHERE library_id = ?',
      array("$library_id"));

    // Remove all invalid keys
    $hashes = array();
    foreach ($results as $key) {
      $hashes[] = $key->hash;
      $DB->delete_records('hvp_libraries_cachedassets', array('hash' => $key->hash));
    }

    return $hashes;
  }
}
