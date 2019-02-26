<?php

namespace mod_hvp\output;

defined('MOODLE_INTERNAL') || die();

use context_module;
use mod_hvp;

class mobile {

  public static function mobile_course_view($args) {
    global $DB, $CFG, $OUTPUT, $USER;

    $cmid = $args['cmid'];

    // Verify course context.
    $cm = get_coursemodule_from_id('hvp', $cmid);
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

    $data = [
      'cmid' => $cmid,
      'wwwroot' => $CFG->wwwroot,
      'username' => $USER->username,
    ];

    return array(
      'templates'  => array(
        array(
          'id'   => 'main',
          'html' => $OUTPUT->render_from_template('mod_hvp/mobile_view_page',$data ),
        ),
      ),
      'javascript' => file_get_contents($CFG->dirroot . '/mod/hvp/library/js/h5p-resizer.js'),
    );
  }
}
