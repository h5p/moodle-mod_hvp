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

$string['modulename'] = 'Interaktivni sadržaj';
$string['modulename_help'] = 'H5P modul aktivnosti omogućava kreiranje interaktivnog sadržaja kao što su Interaktivni video, Set sa pitanjima, Pitanja Uzmi i spusti, Pitanja sa višestrukim izborom, Prezentacije i još mnogo toga.

Kao dodatak, H5P pruža mogućnost da se kreirani sadržaji importuju ili eksportuju u cilju efektivne i ponovne upotrebe ili razmjene sadržaja.

Korisničke interakcije i rezultati se prate uz pomoć xAPI i dostupni su u okviru Moodlove knjige ocjena (Moodle Gradebook).

Interaktivni H5P sadržaj se dodaje uploadovanjem .h5p fajla. Fajl .h5p možete kreirati i downloadovati na h5p.org';
$string['modulename_link'] = 'https://h5p.org/moodle-more-help';
$string['modulenameplural'] = 'Interaktivni sadržaj';
$string['pluginadministration'] = 'H5P';
$string['pluginname'] = 'H5P';
$string['intro'] = 'Uvod';
$string['h5pfile'] = 'H5P File';
$string['fullscreen'] = 'Cijeli ekran';
$string['disablefullscreen'] = 'Onemogući cijeli ekran';
$string['download'] = 'Download';
$string['copyright'] = 'Prava korištenja';
$string['embed'] = 'Embedovanje';
$string['showadvanced'] = 'Prikaži napredno';
$string['hideadvanced'] = 'Sakrij napredno';
$string['resizescript'] = 'Uključi ovu skriptu na vašoj web-stranici ako želite dinamičnu veličinu embedovanog sadržaja:';
$string['size'] = 'Veličina';
$string['close'] = 'Zatvori';
$string['title'] = 'Naslov';
$string['author'] = 'Autor';
$string['year'] = 'Godina';
$string['source'] = 'Izvor';
$string['license'] = 'Licenca';
$string['thumbnail'] = 'Sličica';
$string['nocopyright'] = 'Informacija o autorskim pravima nije dostupna za ovaj sadržaj.';
$string['downloadtitle'] = 'Download ovaj sadržaj kao H5P fajl.';
$string['copyrighttitle'] = 'Pregledaj informacije o autorskim pravima za ovaj sadržaj.';
$string['embedtitle'] = 'Pregledaj kod za embedovanje ovog sadržaja.';
$string['h5ptitle'] = 'Posjeti H5P.org i pronađi još mnogo zanimljivih sadržaja.';
$string['contentchanged'] = 'Ovaj sadržaj je promijenjen od vašeg zadnjeg korištenja.';
$string['startingover'] = "Ponovo ćete početi.";
$string['confirmdialogheader'] = 'Potvrdi akciju';
$string['confirmdialogbody'] = 'Molimo vas da potvrdite da želite nastaviti. Ovaj postupak nije moguće vratiti.';
$string['cancellabel'] = 'Otkaži';
$string['confirmlabel'] = 'Potvrdi';
$string['noh5ps'] = 'Nema dostupnih interaktivnih sadržaja za ovaj kurs.';

// Update message email for admin
$string['messageprovider:updates'] = 'Notification of available H5P updates';
$string['updatesavailabletitle'] = 'New H5P updates are available';
$string['updatesavailablemsgpt1'] = 'There are updates available for the H5P content types you\'ve installed on your Moodle site.';
$string['updatesavailablemsgpt2'] = 'Head over to the page linked to below for further instructions.';
$string['updatesavailablemsgpt3'] = 'The latest update was released on: {$a}';
$string['updatesavailablemsgpt4'] = 'Your are running a version from: {$a}';

$string['lookforupdates'] = 'Look for H5P updates';
$string['removetmpfiles'] = 'Remove old H5P temporary files';
$string['removeoldlogentries'] = 'Remove old H5P log entries';

// Admin settings.
$string['displayoptions'] = 'Display Options';
$string['enableframe'] = 'Display action bar and frame';
$string['enabledownload'] = 'Download button';
$string['enableembed'] = 'Embed button';
$string['enablecopyright'] = 'Copyright button';
$string['enableabout'] = 'About H5P button';

$string['externalcommunication'] = 'External communication';
$string['externalcommunication_help'] = 'Aid in the development of H5P by contributing anonymous usage data. Disabling this option will prevent your site from fetching the newest H5P updates. You can read more about <a {$a}>which data is collected</a> on h5p.org.';
$string['enablesavecontentstate'] = 'Save content state';
$string['enablesavecontentstate_help'] = 'Automatically save the current state of interactive content for each user. This means that the user may pick up where he left off.';
$string['contentstatefrequency'] = 'Save content state frequency';
$string['contentstatefrequency_help'] = 'In seconds, how often do you wish the user to auto save their progress. Increase this number if you\'re having issues with many ajax requests';

// Admin menu.
$string['settings'] = 'H5P Settings';
$string['libraries'] = 'H5P Libraries';

// Update libraries section.
$string['updatelibraries'] = 'Update All Libraries';
$string['updatesavailable'] = 'There are updates available for your H5P content types.';
$string['whyupdatepart1'] = 'You can read about why it\'s important to update and the benefits from doing so on the <a {$a}>Why Update H5P</a> page.';
$string['whyupdatepart2'] = 'The page also list the different changelogs, where you can read about the new features introduced and the issues that have been fixed.';
$string['currentversion'] = 'You are running';
$string['availableversion'] = 'Available update';
$string['usebuttonbelow'] = 'You can use the button below to automatically download and update all of your content types.';
$string['downloadandupdate'] = 'Download & Update';
$string['missingh5purl'] = 'Missing URL for H5P file';
$string['unabletodownloadh5p'] = 'Unable to download H5P file';

// Upload libraries section.
$string['uploadlibraries'] = 'Upload Libraries';
$string['options'] = 'Options';
$string['onlyupdate'] = 'Only update existing libraries';
$string['disablefileextensioncheck'] = 'Disable file extension check';
$string['disablefileextensioncheckwarning'] = "Warning! Disabling the file extension check may have security implications as it allows for uploading of php files. That in turn could make it possible for attackers to execute malicious code on your site. Please make sure you know exactly what you're uploading.";
$string['upload'] = 'Upload';

// Installed libraries section.
$string['installedlibraries'] = 'Installed Libraries';
$string['invalidtoken'] = 'Invalid security token.';
$string['missingparameters'] = 'Missing parameters';

// H5P library list headers on admin page.
$string['librarylisttitle'] = 'Title';
$string['librarylistrestricted'] = 'Restricted';
$string['librarylistinstances'] = 'Instances';
$string['librarylistinstancedependencies'] = 'Instance dependencies';
$string['librarylistlibrarydependencies'] = 'Library dependencies';
$string['librarylistactions'] = 'Actions';

// H5P library page labels.
$string['addlibraries'] = 'Add libraries';
$string['installedlibraries'] = 'Installed libraries';
$string['notapplicable'] = 'N/A';
$string['upgradelibrarycontent'] = 'Upgrade library content';

// Upgrade H5P content page.
$string['upgrade'] = 'Upgrade H5P';
$string['upgradeheading'] = 'Upgrade {$a} content';
$string['upgradenoavailableupgrades'] = 'There are no available upgrades for this library.';
$string['enablejavascript'] = 'Please enable JavaScript.';
$string['upgrademessage'] = 'You are about to upgrade {$a} content instance(s). Please select upgrade version.';
$string['upgradeinprogress'] = 'Upgrading to %ver...';
$string['upgradeerror'] = 'An error occurred while processing parameters:';
$string['upgradeerrordata'] = 'Could not load data for library %lib.';
$string['upgradeerrorscript'] = 'Could not load upgrades script for %lib.';
$string['upgradeerrorcontent'] = 'Could not upgrade content %id:';
$string['upgradeerrorparamsbroken'] = 'Parameters are broken.';
$string['upgradedone'] = 'You have successfully upgraded {$a} content instance(s).';
$string['upgradereturn'] = 'Return';
$string['upgradenothingtodo'] = "There's no content instances to upgrade.";
$string['upgradebuttonlabel'] = 'Upgrade';
$string['upgradeinvalidtoken'] = 'Error: Invalid security token!';
$string['upgradelibrarymissing'] = 'Error: Your library is missing!';

// Results / report page.
$string['user'] = 'Učenik';
$string['score'] = 'Rezultat';
$string['maxscore'] = 'Maksimalni rezultat';
$string['finished'] = 'Završeno';
$string['loadingdata'] = 'Učitavanje podataka.';
$string['ajaxfailed'] = 'Učitavanje podataka nije uspjelo.';
$string['nodata'] = "Nema dostupnih podataka koji odgovaraju vašim kriterijima.";
$string['currentpage'] = 'Stranica $current od $total';
$string['nextpage'] = 'Sljedeća stranica';
$string['previouspage'] = 'Prethodna stranica';
$string['search'] = 'Pretraži';
$string['empty'] = 'Rezultati nisu dostupni';

// Editor
$string['javascriptloading'] = 'Čekanje na JavaScript...';
$string['action'] = 'Akcija';
$string['upload'] = 'Upload';
$string['create'] = 'Napravi';
$string['editor'] = 'Editor';

$string['invalidlibrary'] = 'Invalid library';
$string['nosuchlibrary'] = 'No such library';
$string['noparameters'] = 'No parameters';
$string['invalidparameters'] = 'Invalid Parameters';
$string['missingcontentuserdata'] = 'Error: Could not find content user data';

// Capabilities
$string['hvp:addinstance'] = 'Dodaj novu H5P aktivnost';
$string['hvp:restrictlibraries'] = 'Restrict a H5P library';
$string['hvp:updatelibraries'] = 'Update the version of an H5P library';
$string['hvp:userestrictedlibraries'] = 'Use restricted H5P libraries';
$string['hvp:savecontentuserdata'] = 'Sačuvaj korisničke podatke za H5P sadržaj';
$string['hvp:saveresults'] = 'Sačuvajte rezultate za H5P sadržaj';
$string['hvp:viewresults'] = 'Vidi rezultate za H5P sadržaj';
$string['hvp:getcachedassets'] = 'Get cached H5P content assets';
$string['hvp:getcontent'] = 'Uzmi/pregledaj fajl H5P sadržaja na kursu';
$string['hvp:getexport'] = 'Get export file from H5P in course';
$string['hvp:updatesavailable'] = 'Get notification when H5P updates are available';

// Capabilities error messages
$string['nopermissiontoupgrade'] = 'You do not have permission to upgrade libraries.';
$string['nopermissiontorestrict'] = 'You do not have permission to restrict libraries.';
$string['nopermissiontosavecontentuserdata'] = 'You do not have permission to save content user data.';
$string['nopermissiontosaveresult'] = 'You do not have permission to save result for this content.';
$string['nopermissiontoviewresult'] = 'You do not have permission to view results for this content.';

// Editor translations
$string['noziparchive'] = 'Vaš PHP verzija ne podržava ZIP arhivu.';
$string['noextension'] = 'Fajl koji ste uploadovali nije važeći HTML5 paket (Nema .h5p fajl eksteniziju)';
$string['nounzip'] = 'Fajl koji ste uploadovali nije važeći HTML5 paket (Nije ga moguće unzipovati/otpakovati)';
$string['noparse'] = 'Nije moguće raščlaniti glavni h5p.json fajl';
$string['nojson'] = 'Glavni h5p.json fajl nije ispravan';
$string['invalidcontentfolder'] = 'Pogrešan folder sadržaja';
$string['nocontent'] = 'Nije moguće pronaći ili raščlaniti sadržaj .json fajla';
$string['librarydirectoryerror'] = 'Library directory name must match machineName or machineName-majorVersion.minorVersion (from library.json). (Directory: {$a->%directoryName} , machineName: {$a->%machineName}, majorVersion: {$a->%majorVersion}, minorVersion: {$a->%minorVersion})';
$string['missingcontentfolder'] = 'A valid content folder is missing';
$string['invalidmainjson'] = 'A valid main h5p.json file is missing';
$string['missinglibrary'] = 'Missing required library {$a->@library}';
$string['missinguploadpermissions'] = "Note that the libraries may exist in the file you uploaded, but you're not allowed to upload new libraries. Contact the site administrator about this.";
$string['invalidlibraryname'] = 'Invalid library name: {$a->%name}';
$string['missinglibraryjson'] = 'Could not find library.json file with valid json format for library {$a->%name}';
$string['invalidsemanticsjson'] = 'Invalid semantics.json file has been included in the library {$a->%name}';
$string['invalidlanguagefile'] = 'Invalid language file {$a->%file} in library {$a->%library}';
$string['invalidlanguagefile2'] = 'Invalid language file {$a->%languageFile} has been included in the library {$a->%name}';
$string['missinglibraryfile'] = 'The file "{$a->%file}" is missing from library: "{$a->%name}"';
$string['missingcoreversion'] = 'The system was unable to install the <em>{$a->%component}</em> component from the package, it requires a newer version of the H5P plugin. This site is currently running version {$a->%current}, whereas the required version is {$a->%required} or higher. You should consider upgrading and then try again.';
$string['invalidlibrarydataboolean'] = 'Invalid data provided for {$a->%property} in {$a->%library}. Boolean expected.';
$string['invalidlibrarydata'] = 'Invalid data provided for {$a->%property} in {$a->%library}';
$string['invalidlibraryproperty'] = 'Can\'t read the property {$a->%property} in {$a->%library}';
$string['missinglibraryproperty'] = 'The required property {$a->%property} is missing from {$a->%library}';
$string['invalidlibraryoption'] = 'Illegal option {$a->%option} in {$a->%library}';
$string['addedandupdatelibraries'] = 'Added {$a->%new} new H5P libraries and updated {$a->%old} old.';
$string['addednewlibraries'] = 'Added {$a->%new} new H5P libraries.';
$string['updatedlibraries'] = 'Updated {$a->%old} H5P libraries.';
$string['missingdependency'] = 'Missing dependency {$a->@dep} required by {$a->@lib}.';
$string['invalidstring'] = 'Provided string is not valid according to regexp in semantics. (value: \"{$a->%value}\", regexp: \"{$a->%regexp}\")';
$string['invalidfile'] = 'File "{$a->%filename}" not allowed. Only files with the following extensions are allowed: {$a->%files-allowed}.';
$string['invalidmultiselectoption'] = 'Invalid selected option in multi-select.';
$string['invalidselectoption'] = 'Invalid selected option in select.';
$string['invalidsemanticstype'] = 'H5P internal error: unknown content type "{$a->@type}" in semantics. Removing content!';
$string['invalidsemantics'] = 'Library used in content is not a valid library according to semantics';
$string['copyrightinfo'] = 'Copyright information';
$string['years'] = 'Godina(e)';
$string['undisclosed'] = 'Undisclosed';
$string['attribution'] = 'Attribution 4.0';
$string['attributionsa'] = 'Attribution-ShareAlike 4.0';
$string['attributionnd'] = 'Attribution-NoDerivs 4.0';
$string['attributionnc'] = 'Attribution-NonCommercial 4.0';
$string['attributionncsa'] = 'Attribution-NonCommercial-ShareAlike 4.0';
$string['attributionncnd'] = 'Attribution-NonCommercial-NoDerivs 4.0';
$string['gpl'] = 'General Public License v3';
$string['pd'] = 'Public Domain';
$string['pddl'] = 'Public Domain Dedication and Licence';
$string['pdm'] = 'Public Domain Mark';
$string['copyrightstring'] = 'Autorsko pravo';
$string['unabletocreatedir'] = 'Nije moguće kreirati direktorij.';
$string['unabletogetfieldtype'] = 'Unable to get field type.';
$string['filetypenotallowed'] = 'Tip fajla nije dozvoljen.';
$string['invalidfieldtype'] = 'Pogrešan tip fajla.';
$string['invalidimageformat'] = 'Invalid image file format. Use jpg, png or gif.';
$string['filenotimage'] = 'File is not an image.';
$string['invalidaudioformat'] = 'Invalid audio file format. Use mp3 or wav.';
$string['invalidvideoformat'] = 'Invalid video file format. Use mp4 or webm.';
$string['couldnotsave'] = 'Could not save file.';
$string['couldnotcopy'] = 'Could not copy file.';

// Welcome messages
$string['welcomeheader'] = 'Dobrodošli u svijet H5P!';
$string['welcomegettingstarted'] = 'To get started with H5P and Moodle take a look at our <a {$a->moodle_tutorial}>tutorial</a> and check out the <a {$a->example_content}>example content</a> at H5P.org for inspiration.<br>The most popuplar content types have been installed for your convenience!';
$string['welcomecommunity'] = 'We hope you will enjoy H5P and get engaged in our growing community through our <a {$a->forums}>forums</a> and chat room <a {$a->gitter}>H5P at Gitter</a>';
$string['welcomecontactus'] = 'If you have any feedback, don\'t hesitate to <a {$a}>contact us</a>. We take feedback very seriously and are dedicated to making H5P better every day!';
