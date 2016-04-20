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
$string['modulename'] = 'Interaktiver Inhalt';
$string['modulename_help'] = 'The H5P activity module enables you to create interactive content such as Interactive Videos, Question Sets, Drag and Drop Questions, Multi-Choice Questions, Presentations and much more.
In addition to being an authoring tool for rich content, H5P enables you to import and export H5P files for effective reuse and sharing of content.
User interactions and scores are tracked using xAPI and are available through the Moodle Gradebook.
You add interactive H5P content by uploading a .h5p file. You can create and download .h5p files on h5p.org';
$string['modulename_link'] = 'https://h5p.org/moodle-more-help';
$string['modulenameplural'] = 'H5Ps';
$string['pluginadministration'] = 'H5P';
$string['pluginname'] = 'H5P';
$string['hvp:addinstance'] = 'Add a new H5P Activity';
$string['intro'] = 'Hello awesome!';
$string['h5pfile'] = 'H5P Datei';
$string['fullscreen'] = 'Fullscreen';
$string['disablefullscreen'] = 'Disable fullscreen';
$string['download'] = 'Heruterladen';
$string['copyright'] = 'Rights of use';
$string['embed'] = 'Einbinden';
$string['showadvanced'] = 'Erweitert anzeigen';
$string['hideadvanced'] = 'Erweitert ausblenden';
$string['resizescript'] = 'Include this script on your website if you want dynamic sizing of the embedded content:';
$string['size'] = 'Größe';
$string['close'] = 'Schließen';
$string['title'] = 'Titel';
$string['author'] = 'Autor';
$string['year'] = 'Jahr';
$string['source'] = 'Quelle';
$string['license'] = 'Lizenz';
$string['thumbnail'] = 'Thumbnail';
$string['nocopyright'] = 'No copyright information available for this content.';
$string['downloadtitle'] = 'Diesen Inhalt als H5P Datei herunterladen';
$string['copyrighttitle'] = 'View copyright information for this content.';
$string['embedtitle'] = 'View the embed code for this content.';
$string['h5ptitle'] = 'Visit H5P.org to check out more cool content.';
$string['contentchanged'] = 'This content has changed since you last used it.';
$string['startingover'] = "You'll be starting over.";
$string['lookforupdates'] = 'Nach H5P updates suchen';
// Admin settings.
$string['displayoptions'] = 'Optionen anzeigen';
$string['enableframe'] = 'Display action bar and frame';
$string['enabledownload'] = 'Download button';
$string['enableembed'] = 'Embed button';
$string['enablecopyright'] = 'Copyright button';
$string['enableabout'] = 'Über H5P button';
$string['enablesavecontentstate'] = 'Save content state';
$string['enablesavecontentstate_help'] = 'Automatically save the current state of interactive content for each user. This means that the user may pick up where he left off.';
$string['contentstatefrequency'] = 'Save content state frequency';
$string['contentstatefrequency_help'] = 'In seconds, how often do you wish the user to auto save their progress. Increase this number if you\'re having issues with many ajax requests';
// Admin menu.
$string['settings'] = 'H5P Einstellungen';
$string['libraries'] = 'H5P Bibliotheken';
// Upload libraries section.
$string['uploadlibraries'] = 'Bibliotheken hochladen';
$string['options'] = 'Optionen';
$string['onlyupdate'] = 'Nur bereits bestehende Bibliotheken aktualisieren';
$string['disablefileextensioncheck'] = 'Disable file extension check';
$string['disablefileextensioncheckwarning'] = "Warning! Disabling the file extension check may have security implications as it allows for uploading of php files. That in turn could make it possible for attackers to execute malicious code on your site. Please make sure you know exactly what you're uploading.";
$string['upload'] = 'Hochladen';
// Installed libraries section.
$string['installedlibraries'] = 'Installierte Bibliotheken';
$string['invalidtoken'] = 'Invalid security token.';
// H5P library list headers on admin page.
$string['librarylisttitle'] = 'Titel';
$string['librarylistrestricted'] = 'Eingeschränkt';
$string['librarylistinstances'] = 'Instances';
$string['librarylistinstancedependencies'] = 'Instance dependencies';
$string['librarylistlibrarydependencies'] = 'Library dependencies';
$string['librarylistactions'] = 'Aktionen';
// H5P library page labels.
$string['addlibraries'] = 'Bibliotheken hinzufügen';
$string['installedlibraries'] = 'Installierte Bibliotheken';
$string['notapplicable'] = 'N/A';
$string['upgradelibrarycontent'] = 'Upgrade library content';
// Upgrade H5P content page.
$string['upgrade'] = 'Upgrade H5P';
$string['upgradeheading'] = 'Upgrade {$a} content';
$string['upgradenoavailableupgrades'] = 'There are no available upgrades for this library.';
$string['enablejavascript'] = 'Bitte JavaScript aktivieren.';
$string['upgrademessage'] = 'You are about to upgrade {$a} content instance(s). Please select upgrade version.';
$string['upgradeinprogress'] = 'Aktualisieren auf %ver...';
$string['upgradeerror'] = 'An error occurred while processing parameters:';
$string['upgradeerrordata'] = 'Konnte die Daten der Bibliothek %lib nicht laden.';
$string['upgradeerrorscript'] = 'Could not load upgrades script for %lib.';
$string['upgradeerrorcontent'] = 'Could not upgrade content %id:';
$string['upgradeerrorparamsbroken'] = 'Parameters are broken.';
$string['upgradedone'] = 'You have successfully upgraded {$a} content instance(s).';
$string['upgradereturn'] = 'Zurück';
$string['upgradenothingtodo'] = "There's no content instances to upgrade.";
$string['upgradebuttonlabel'] = 'Aktualisieren';
$string['upgradeinvalidtoken'] = 'Error: Invalid security token!';
$string['upgradelibrarymissing'] = 'Fehler: Die Bibliothek fehlt!';
// Results / report page.
$string['user'] = 'Nutzer';
$string['score'] = 'Score';
$string['maxscore'] = 'Maximum Score';
$string['finished'] = 'Beendet';
$string['loadingdata'] = 'Lade Daten.';
$string['ajaxfailed'] = 'Fehler beim Laden der Daten.';
$string['nodata'] = "Es sind keine Daten vorhanden, die den Kriterien entsprechen.";
$string['currentpage'] = 'Seite $current von $total';
$string['nextpage'] = 'Nächste Seite';
$string['previouspage'] = 'Vorherige Seite';
$string['search'] = 'Suchen';
$string['empty'] = 'Keine Ergebnisse verfügbar';
$string['javascriptloading'] = 'Warte auf JavaScript';
