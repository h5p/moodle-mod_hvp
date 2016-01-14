<?php
require_once("../../config.php");
require_once($CFG->libdir.'/adminlib.php');
require_once("lib.php");

// No guest autologin.
require_login(0, false);

$pageurl = new moodle_url('/mod/hvp/toolproxies.php');
$PAGE->set_url($pageurl);

// Inform moodle which menu entry currently is active!
admin_externalpage_setup('h5plibraries');

$PAGE->set_title("{$SITE->shortname}: " . get_string('libraries', 'hvp'));

// Any equivalent in moodle?
// _h5p_check_settings();

$core = hvp_get_instance('core');
$numNotFiltered = $core->h5pF->getNumNotFiltered();
$libraries = $core->h5pF->loadLibraries();

// Add settings for each library
$settings = array();
$i = 0;
foreach ($libraries as $versions) {
  foreach ($versions as $library) {
    $usage = $core->h5pF->getLibraryUsage($library->id, $numNotFiltered ? TRUE : FALSE);
    if ($library->runnable) {
      $upgrades = $core->getUpgrades($library, $versions);
      // TODO
      //$upgradeUrl = empty($upgrades) ? FALSE : url('admin/content/h5p/libraries/' . $library->id . '/upgrade', array('query' => drupal_get_destination()));

      $restricted = (isset($library->restricted) && $library->restricted == 1 ? TRUE : FALSE);
      $restricted_url = (new moodle_url('/mod/hvp/ajax.php', array(
        'action' => 'restrict_library',
        'token' => hvp_get_token('library_' . $library->id),
        'restrict' => ($restricted ? 0 : 1),
        'library_id' => $library->id
      )))->out(false);
    }
    else {
      $upgradeUrl = NULL;
      $restricted = NULL;
      $restricted_url = NULL;
    }

    $settings['libraryList']['listData'][] = array(
      'title' => $library->title . ' (' . H5PCore::libraryVersion($library) . ')',
      'restricted' => $restricted,
      'restrictedUrl' => $restricted_url,
      'numContent' => $core->h5pF->getNumContent($library->id),
      'numContentDependencies' => $usage['content'] === -1 ? '' : $usage['content'],
      'numLibraryDependencies' => $usage['libraries'],
      'upgradeUrl' => $upgradeUrl,
      'detailsUrl' => NULL, // Not implemented in Moodle
      'deleteUrl' => NULL // Not implemented in Moodle
    );

    $i++;
  }
}

// All translations are made server side
$settings['libraryList']['listHeaders'] = array(
  get_string('librarylisttitle', 'hvp'),
  get_string('librarylistrestricted', 'hvp'),
  get_string('librarylistinstances', 'hvp'),
  get_string('librarylistinstancedependencies', 'hvp'),
  get_string('librarylistlibrarydependencies', 'hvp'),
  get_string('librarylistactions', 'hvp')
);


//if ($numNotFiltered) {
  // Not implemented in Moodle
  // $settings['libraryList']['notCached'] = h5p_get_not_cached_settings($numNotFiltered);
//}

// Add js
$lib_url = $CFG->httpswwwroot . '/mod/hvp/library/';

hvp_admin_add_generic_css_and_js($PAGE, $lib_url, $settings);
$PAGE->requires->js(new moodle_url($lib_url . 'js/h5p-library-list.js' . hvp_get_cache_buster()), true);

echo $OUTPUT->header();
echo '<h2>H5P Libraries</h2><h3 class="h5p-admin-header">' . 'Add libraries' . '</h3>' . /* TODO  - add upload form  */ '<h3 class="h5p-admin-header">' . 'Installed libraries' . '</h3><div id="h5p-admin-container"></div>';
echo $OUTPUT->footer();
