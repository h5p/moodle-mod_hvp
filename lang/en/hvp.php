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

$string['modulename'] = 'Interactive Content';
$string['modulename_help'] = 'The H5P activity module enables you to create interactive content such as Interactive Videos, Question Sets, Drag and Drop Questions, Multi-Choice Questions, Presentations and much more.

In addition to being an authoring tool for rich content, H5P enables you to import and export H5P files for effective reuse and sharing of content.

User interactions and scores are tracked using xAPI and are available through the Moodle Gradebook.

You add interactive H5P content by uploading a .h5p file. You can create and download .h5p files on h5p.org';
$string['modulename_link'] = 'https://h5p.org/moodle-more-help';
$string['modulenameplural'] = 'H5Ps';
$string['pluginadministration'] = 'H5P';
$string['pluginname'] = 'H5P';
$string['intro'] = 'Hello awesome!';
$string['h5pfile'] = 'H5P File';
$string['fullscreen'] = 'Fullscreen';
$string['disablefullscreen'] = 'Disable fullscreen';
$string['download'] = 'Download';
$string['copyright'] = 'Rights of use';
$string['embed'] = 'Embed';
$string['showadvanced'] = 'Show advanced';
$string['hideadvanced'] = 'Hide advanced';
$string['resizescript'] = 'Include this script on your website if you want dynamic sizing of the embedded content:';
$string['size'] = 'Size';
$string['close'] = 'Close';
$string['title'] = 'Title';
$string['author'] = 'Author';
$string['year'] = 'Year';
$string['source'] = 'Source';
$string['license'] = 'License';
$string['thumbnail'] = 'Thumbnail';
$string['nocopyright'] = 'No copyright information available for this content.';
$string['downloadtitle'] = 'Download this content as a H5P file.';
$string['copyrighttitle'] = 'View copyright information for this content.';
$string['embedtitle'] = 'View the embed code for this content.';
$string['h5ptitle'] = 'Visit H5P.org to check out more cool content.';
$string['contentchanged'] = 'This content has changed since you last used it.';
$string['startingover'] = "You'll be starting over.";

$string['lookforupdates'] = 'Look for H5P updates';
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
$string['user'] = 'User';
$string['score'] = 'Score';
$string['maxscore'] = 'Maximum Score';
$string['finished'] = 'Finished';
$string['loadingdata'] = 'Loading data.';
$string['ajaxfailed'] = 'Failed to load data.';
$string['nodata'] = "There's no data available that matches your criteria.";
$string['currentpage'] = 'Page $current of $total';
$string['nextpage'] = 'Next page';
$string['previouspage'] = 'Previous page';
$string['search'] = 'Search';
$string['empty'] = 'No results available';

// Editor
$string['javascriptloading'] = 'Waiting for javascript...';
$string['action'] = 'Action';
$string['upload'] = 'Upload';
$string['create'] = 'Create';
$string['editor'] = 'Editor';

// Capabilities
$string['hvp:addinstance'] = 'Add a new H5P Activity';
$string['hvp:restrictlibraries'] = 'Restrict a H5P library';
$string['hvp:updatelibraries'] = 'Update the version of an H5P library';
$string['hvp:deletecontentuserdata'] = 'Delete content user data';
$string['hvp:savecontentuserdata'] = 'Save content user data';
$string['hvp:saveresults'] = 'Save result for H5P content';
$string['hvp:viewresults'] = 'View result for H5P content';

// Capabilities error messages
$string['nopermissiontoupgrade'] = 'You do not have permission to upgrade libraries.';
$string['nopermissiontorestrict'] = 'You do not have permission to restrict libraries.';
$string['nopermissiontodeletecontentuserdata'] = 'You do not have permission to delete content user data.';
$string['nopermissiontosavecontentuserdata'] = 'You do not have permission to save content user data.';
$string['nopermissiontosaveresult'] = 'You do not have permission to save result for this content.';
$string['nopermissiontoviewresult'] = 'You do not have permission to view results for this content.';
