<?php
$addons = array(
  "mod_hvp" => array( // Plugin identifier
    'handlers' => array( // Different places where the plugin will display content.
      'coursehvp' => array( // Handler unique name (alphanumeric).
        'delegate'    => 'CoreCourseModuleDelegate', // Delegate (where to display the link to the plugin)
        'method'      => 'mobile_course_view', // Main function in \mod_certificate\output\mobile
        'displaydata' => array(
          'icon'  => $CFG->wwwroot . '/mod/hvp/pix/icon.svg',
          'class' => '',
        ),
      )
    )
  )
);
