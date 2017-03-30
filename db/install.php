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

    // Check that plugin is set up correctly
    $core->checkSetupForRequirements();

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

    // Notify of communication with H5P Hub
    echo '<p>H5P fetches content types directly from the H5P Hub. In order to do this the H5P plugin will communicate with the Hub once a day to fetch information about new and updated content types. It will send in anonymous data to the Hub about H5P usage. Read more at <a href="https://h5p.org/tracking-the-usage-of-h5p">the plugin communication page at H5P.org</a>.</p>';

    \mod_hvp\framework::printMessages('info', \mod_hvp\framework::messages('info'));
    \mod_hvp\framework::printMessages('error', \mod_hvp\framework::messages('error'));
}
