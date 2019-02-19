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
 * The mod_hvp view assets convenience class for viewing and embedding H5Ps
 *
 * @package    mod_hvp
 * @copyright  2019 Joubel
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp;

defined('MOODLE_INTERNAL') || die();

/**
 * Handles finding and attaching assets for view
 * @package mod_hvp
 */
class external_functions {

  static function hvp_get_core_assets($context) {
    global $CFG;

    // Get core settings.
    $settings = self::hvp_get_core_settings($context);
    $settings['core'] = array(
      'styles' => array(),
      'scripts' => array()
    );
    $settings['loadedJs'] = array();
    $settings['loadedCss'] = array();

    // Make sure files are reloaded for each plugin update.
    $cachebuster = self::hvp_get_cache_buster();

    // Use relative URL to support both http and https.
    $liburl = $CFG->httpswwwroot . '/mod/hvp/library/';
    $relpath = '/' . preg_replace('/^[^:]+:\/\/[^\/]+\//', '', $liburl);

    // Add core stylesheets.
    foreach (\H5PCore::$styles as $style) {
      $settings['core']['styles'][] = $relpath . $style . $cachebuster;
    }
    // Add core JavaScript.
    foreach (\H5PCore::$scripts as $script) {
      $settings['core']['scripts'][] = $relpath . $script . $cachebuster;
    }

    return $settings;
  }

  static function hvp_get_core_settings($context) {
    global $USER, $CFG;

    $systemcontext = \context_system::instance();
    $basepath = $CFG->httpswwwroot . '/';

    // Check permissions and generate ajax paths.
    $ajaxpaths = array();
    $savefreq = false;
    $ajaxpath = "{$basepath}mod/hvp/ajax.php?contextId={$context->instanceid}&token=";
    if ($context->contextlevel == CONTEXT_MODULE && has_capability('mod/hvp:saveresults', $context)) {
      $ajaxpaths['setFinished'] = $ajaxpath . \H5PCore::createToken('result') . '&action=set_finished';
      $ajaxpaths['xAPIResult'] = $ajaxpath . \H5PCore::createToken('xapiresult') . '&action=xapiresult';
    }
    if (has_capability('mod/hvp:savecontentuserdata', $context)) {
      $ajaxpaths['contentUserData'] = $ajaxpath . \H5PCore::createToken('contentuserdata') .
                                      '&action=contents_user_data&content_id=:contentId&data_type=:dataType&sub_content_id=:subContentId';

      if (get_config('mod_hvp', 'enable_save_content_state')) {
        $savefreq = get_config('mod_hvp', 'content_state_frequency');
      }
    }

    $core = \mod_hvp\framework::instance('core');

    $settings = array(
      'baseUrl' => $basepath,
      'url' => "{$basepath}pluginfile.php/{$context->instanceid}/mod_hvp",
      'libraryUrl' => "{$basepath}pluginfile.php/{$systemcontext->id}/mod_hvp/libraries",
      'postUserStatistics' => true,
      'ajax' => $ajaxpaths,
      'saveFreq' => $savefreq,
      'siteUrl' => $CFG->wwwroot,
      'l10n' => array('H5P' => $core->getLocalization()),
      'user' => array(
        'name' => $USER->firstname . ' ' . $USER->lastname,
        'mail' => $USER->email
      ),
      'hubIsEnabled' => get_config('mod_hvp', 'hub_is_enabled') ? true : false,
      'reportingIsEnabled' => true,
      'crossorigin' => isset($CFG->mod_hvp_crossorigin) ? $CFG->mod_hvp_crossorigin : null,
      'libraryConfig' => $core->h5pF->getLibraryConfig(),
      'pluginCacheBuster' => self::hvp_get_cache_buster(),
      'libraryUrl' => $basepath . 'mod/hvp/library/js'
    );

    return $settings;
  }


  static function hvp_get_cache_buster() {
    return '?ver=' . get_config('mod_hvp', 'version');
  }
}
