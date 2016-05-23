<?php

function xmldb_hvp_install() {

    // Try to install all the default content types
    require_once(__DIR__ . '/../autoloader.php');

    // Override permission check for the install process, since caps hasn't
    // been set yet.
    $interface = \mod_hvp\framework::instance('interface');
    $interface->mayUpdateLibraries(true);

    // Fetch info about library updates
    $core = \mod_hvp\framework::instance('core');
    $core->fetchLibrariesMetadata();

    // Download default libraries and try to install
    $error = \mod_hvp\framework::downloadH5pLibraries();
    if ($error !== null) {
      \mod_hvp\framework::messages('error', $error);
    }

    // Print any messages
    echo '<h3>' . get_string('welcomeheader', 'hvp') . '</h3>' .
         '<p>' .
         get_string('welcomegettingstarted', 'hvp', array(
             'moodle_tutorial' => 'href="https://h5p.org/moodle" target="_blank"',
             'example_content' => 'href="https://h5p.org/content-types-and-applications" target="_blank"'
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

    \mod_hvp\framework::printMessages('info', \mod_hvp\framework::messages('info'));
    \mod_hvp\framework::printMessages('error', \mod_hvp\framework::messages('error'));
}
