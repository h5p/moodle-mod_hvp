<?php
if (!defined('MOODLE_INTERNAL')) {
  die('Direct access to this script is forbidden.');
}

require_once ($CFG->dirroot . '/mod/hvp/library/h5p.classes.php');

function hvp_get_instance($type) {
  global $CFG;
  static $interface, $core;

  if (!isset($interface)) {    
    $interface = new H5PDrupal();
    $core = new H5PCore($interface);
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

class H5PDrupal implements H5PFrameworkInterface {
  /**
   * Implements setErrorMessage
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
    // TODO: Use $OUTPUT->notification(get_string('choicesaved', 'choice'),'notifysuccess'); ???
    echo $message;
  }

  /**
   * Implements t
   */
  public function t($message, $replacements = array()) {
    // TODO; Change core, use keywords for translation.
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
    // TODO: Throw error if path isn't set.
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
    // TODO: Throw error if path isn't set.
    return $path;
  }

  /**
   * Implements getLibraryId
   */
  public function getLibraryId($machineName, $majorVersion, $minorVersion) {
    global $DB;
    
    $library = $DB->get_record('hvp_libraries', array('machine_name' => $machineName, 'major_version' => $majorVersion, 'minor_version' => $minorVersion));
                                    
    return $library ? $library->id : FALSE;
  }

  /**
   * Implements isPatchedLibrary
   */
  public function isPatchedLibrary($library) {
    global $DB;
    
    $operator = $this->isInDevMode() ? '<=' : '<';  
    $library = $DB->get_record_sql('SELECT id FROM {hvp_libraries} WHERE machine_name = ? AND major_version = ? AND minor_version = ? AND patch_version ' . $operator . ' ?', 
                                   array($library['machineName'], $library['majorVersion'], $library['minorVersion'], $library['patchVersion']));
                                   
    return $library ? TRUE : FALSE;
  }
  
  /**
   * Implements isInDevMode
   */
  public function isInDevMode() {
    return FALSE; // TODO: Not supported
  }

  /**
   * Implements mayUpdateLibraries
   */
  public function mayUpdateLibraries() {
    return TRUE; // TODO: Add permission support
  }

  /**
   * Implements saveLibraryData
   */
  public function saveLibraryData(&$libraryData, $new = TRUE) {
    global $DB;
    
    if ($new) {
      // Create new library
      $library = (object) array(
        'machine_name' => $libraryData['machineName'],
        'title' => $libraryData['title'],
        'major_version' => $libraryData['majorVersion'],
        'minor_version' => $libraryData['minorVersion'],
        'patch_version' => $libraryData['patchVersion'],
        'runnable' => $libraryData['runnable']
      );
    }
    else {
      // Load library
      $library = $DB->get_record('hvp_libraries', array('id' => $libraryData['libraryId']));
      
      $library->title = $libraryData['title'];
      $library->patch_version = $libraryData['patchVersion'];
      $library->runnable = $libraryData['runnable'];
    }
    
    // Some special properties needs some checking and converting before they can be saved.
    $library->preloaded_js = $this->pathsToCsv($libraryData, 'preloadedJs');
    $library->preloaded_css = $this->pathsToCsv($libraryData, 'preloadedCss');
    
    if (isset($libraryData['dropLibraryCss'])) {
      $libs = array();
      foreach ($libraryData['dropLibraryCss'] as $lib) {
        $libs[] = $lib['machineName'];
      }
      $library->drop_library_css = implode(', ', $libs);
    }
    else {
      $library->drop_library_css = '';
    }
    
    $library->fullscreen = (! isset($libraryData['fullscreen']) ? 0 : $libraryData['fullscreen']);
    $library->embed_types = (isset($libraryData['embedTypes']) ? implode(', ', $libraryData['embedTypes']) : '');
    $library->semantics = (! isset($libraryData['semantics']) ? '' : $libraryData['semantics']);
    
    if ($new) {
      $library->id = $DB->insert_record('hvp_libraries', $library);
      $libraryData['libraryId'] = $library->id;
    }
    else {
      $DB->update_record('hvp_libraries', $library);
      $this->deleteLibraryDependencies($library->id);
    }
    
    // Update languages
    $DB->delete_records('hvp_libraries_languages', array('id' => "$library->id"));
    if (isset($libraryData['language'])) {
      foreach ($libraryData['language'] as $languageCode => $languageJson) {
        $DB->insert_record_raw('hvp_libraries_languages', array(
          'id' => $library->id,
          'language_code' => $languageCode,
          'translation' => $languageJson
        ), false, false, true);
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
   * Implements deleteLibraryDependencies
   */
  public function deleteLibraryDependencies($libraryId) {
    global $DB;
    
    $DB->delete_records('hvp_libraries_libraries', array('id' => "$libraryId"));
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
      $DB->insert_record_raw('hvp_libraries_libraries', array(
        'id' => $libraryId,
        'required_library_id' => $dependencyLibrary->id,
        'dependency_type' => $dependency_type
      ), false, false, true);
    }
  }

  /**
   * Implements saveContentData
   */
  public function saveContentData($contentId, $contentJson, $mainJsonData, $mainLibraryId, $contentMainId = NULL) {
    global $DB;
    
    $DB->insert_record_raw('hvp_contents', array(
      'id' => $contentId,
      'content' => $contentJson,
      'embed_type' => (isset($mainJsonData['embedTypes']) ? implode(', ', $mainJsonData['embedTypes']) : ''),
      'library_id' => $mainLibraryId
    ), false, false, true);
  }
  
  /**
   * Implement getWhitelist
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
  }

  /**
   * Implements deleteContentData
   */
  public function deleteContentData($contentId) {
    global $DB;
  
    $DB->delete_records('hvp_contents', array('id' => "$contentId"));
    $this->deleteLibraryUsage($contentId);
  }

  /**
   * Implements deleteLibraryUsage
   */
  public function deleteLibraryUsage($contentId) {
    global $DB;
    
    $DB->delete_records('hvp_contents_libraries', array('id' => "$contentId"));
  }

  /**
   * Implements saveLibraryUsage
   */
  public function saveLibraryUsage($contentId, $librariesInUse) {
    global $DB;
    
    $dropLibraryCssList = array();
    foreach ($librariesInUse as $machineName => $library) {
      if (!empty($library['library']['dropLibraryCss'])) {
        $dropLibraryCssList = array_merge($dropLibraryCssList, explode(', ', $library['library']['dropLibraryCss']));
      }
    }
    foreach ($librariesInUse as $machineName => $library) {
      $conditions = array(
        'id' => $contentId, 
        'library_id' => $library['library']['libraryId']
      );
      
      $contentLibrary = $DB->get_record('hvp_contents_libraries', $conditions);
    
      if (!$contentLibrary) {
        $contentLibrary = $conditions;
      }
      else {
        $contentLibrary = (array) $contentLibrary;
      }
      
      $contentLibrary['preloaded'] = $library['preloaded'];
      $contentLibrary['drop_css'] = in_array($machineName, $dropLibraryCssList) ? 1 : 0;
    
      $DB->insert_record_raw('hvp_contents_libraries', $contentLibrary, false, false, true);
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
                                          WHERE hll.id = ?', array("$library->id"));
    foreach ($dependencies as $dependency) {
      $libraryData[$dependency->dependency_type . 'Dependencies'][] = array(
        'machineName' => $dependency->machine_name,
        'majorVersion' => $dependency->major_version,
        'minorVersion' => $dependency->minor_version
      );
    }
    
    if ($this->isInDevMode()) {
      // TODO: Get semantics from file.
    }
    
    return $libraryData;
  }

  /**
   * Implements getLibrarySemantics
   *
   * Calls modules implementing hook_alter_h5p_semantics().
   */
  public function getLibrarySemantics($machineName, $majorVersion, $minorVersion) {
    if ($this->isInDevMode()) {
      // TODO: Get semantics from file.
    }
    else {
      $library = $DB->get_record('hvp_libraries', array(
        'machine_name' => $machineName, 
        'major_version' => $majorVersion, 
        'minor_version' => $minorVersion
      ));
      $semantics = $library->semantics;
    }
    return json_decode($semantics);
  }
  
  private function getSemanticsFromFile($machineName, $majorVersion, $minorVersion) {
    // TODO
  }
  
  /**
   * Implements getExportData
   **/
  public function getExportData($contentId, $title, $language) {
    return NULL;
  }
  
  /**
   * Check if h5p export is enabled.
   *
   * @return bool
   */
  public function isExportEnabled() {
    return FALSE;
  }
}
