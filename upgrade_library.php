<?php
require_once("../../config.php");
require_once($CFG->libdir.'/adminlib.php');
require_once("locallib.php");

// library param is in the following format:
// /<machine-name>/<major-version>/<minor-version>
$library = required_param('library', PARAM_TEXT);

//new moodle_url('/mod/hvp/upgrade_library.php');
//$PAGE->set_url($page_url);
//admin_externalpage_setup('h5plibraries');

function hvp_upgrade_library($name, $major, $minor) {
  global $CFG;

  $library = (object) array(
    'name' => $name,
    'version' => (object) array(
      'major' => $major,
      'minor' => $minor
    )
  );

  $core = \mod_hvp\framework::instance();

  // echo $name;
  // echo $major;
  // echo $minor;
  // die;


  $library->semantics = $core->loadLibrarySemantics($library->name, $library->version->major, $library->version->minor);
  if ($library->semantics === NULL) {
    http_response_code(404);
    return;
  }

  $basePath = $CFG->sessioncookiepath;
  $ajaxPath = $basePath . 'mod/hvp/ajax.php?action=';
  $system_context = \context_system::instance();


  //$upgrades_script = (new moodle_url()) _h5p_get_h5p_path() . (isset($dev_lib) ? '/'. $dev_lib['path'] : '/libraries/' . $library->name . '-' . $library->version->major . '.' . $library->version->minor) . '/upgrades.js';
  $upgrades_script = "{$basePath}pluginfile.php/{$system_context->id}/mod_hvp/libraries/{$library->name}-{$library->version->major}.{$library->version->minor}/upgrades.js";
  //if (file_exists($upgrades_script)) { TODO
  $library->upgradesScript = $upgrades_script;
  //}

  // drupal_add_http_header('Cache-Control', 'no-cache');
  // drupal_add_http_header('Content-type', 'application/json');
  print json_encode($library);

  //return $library;

}

$library = explode('/', substr($library, 1));

hvp_upgrade_library($library[0], $library[1], $library[2]);
die;
//print json_encode((object)array('kk' => 'OK'));
//die;
