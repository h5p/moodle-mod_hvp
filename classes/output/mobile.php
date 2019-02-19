<?php

namespace mod_hvp\output;

defined('MOODLE_INTERNAL') || die();

use context_module;
use mod_hvp;

class mobile {

  public static function mobile_course_view($args) {
    global $DB, $CFG;

    $id = $args['cmid'];

    // Verify course context.
    $cm = get_coursemodule_from_id('hvp', $id);
    if (!$cm) {
      print_error('invalidcoursemodule');
    }
    $course = $DB->get_record('course', array('id' => $cm->course));
    if (!$course) {
      print_error('coursemisconf');
    }
    require_course_login($course, true, $cm);
    $context = context_module::instance($cm->id);
    require_capability('mod/hvp:view', $context);

    // Set up view assets.
    $view = new mod_hvp\view_assets($cm, $course);
    $view->validatecontent();

    $html = '';

    // Print any messages.
    // $html .= \mod_hvp\framework::printMessages('info', \mod_hvp\framework::messages('info'));
    // $html .= \mod_hvp\framework::printMessages('error', \mod_hvp\framework::messages('error'));

    $html .= $view->outputview();

    // TODO: Import js from other file
    $scripts     = $view->getjsassets();
    $corescripts = $view->getcorejsassets();

    return array(
      'templates'  => array(
        array(
          'id'   => 'main',
          'html' => $html,
        ),
      ),
      'javascript' => 'console.log("Add JS here..")',
    );
  }
}
