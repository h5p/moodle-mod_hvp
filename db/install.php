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
    \mod_hvp\framework::printMessages('info', \mod_hvp\framework::messages('info'));
    \mod_hvp\framework::printMessages('error', \mod_hvp\framework::messages('error'));
}
