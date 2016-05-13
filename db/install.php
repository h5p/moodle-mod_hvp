<?php

function xmldb_hvp_install() {
    echo '<h3>' . get_string('welcomeheader', 'hvp') . '</h3>' .
         '<p>' .
         get_string('welcomegettingstarted', 'hvp', array(
            'moodle_tutorial' => 'href="https://h5p.org/moodle" target="_blank"',
            'example_content' => 'href="https://h5p.org/content-types-and-applications" target="_blank"',
            'update_all_content_types' => 'href="https://h5p.org/update-all-content-types" target="_blank"'
         )) .
         '</p>' .
         '<p>' .
         get_string('welcomecommunity', 'hvp', array(
             'forums' => 'href="https://h5p.org/forum" target="_blank"',
             'gitter' => 'href="https://gitter.im/h5p/CommunityChat" target="_blank"'
         )) .
         '</p>' .
         '<p>' . get_string('welcomecontactus', 'hvp',
            'href="https://h5p.org/contact" target="_blank"') .
         '</p>';
}
