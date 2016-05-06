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
$string['modulename_help'] = 'Das H5P Aktivitätenmodul ermöglicht das Erstellen von interaktiven Inhalten, wie interaktive videos, Fragebögen, Drag and Drop Fragen, Multiple-Choice Fragen, Präsentationen und vieles mehr.
Zusätzlich zu den Funktionen als Tool für rich content, ermöglicht H5P es H5P Datein zu importieren und exportieren, um die Inhalte effektiv wiederverwenden und teilen zu können.
Nutzerinteraktion und Punkte werden mittels der xAPI verfolgt und sind im Moodle Notenbuch verfügbar.
Interaktive H5P Inhalte können durch das hochladen von .h5p Dateien hinzugefügt werden. Das Erstellen und Herunterladen von .h5p Dateien ist unter h5p.org möglich.';
$string['modulename_link'] = 'https://h5p.org/moodle-more-help';
$string['modulenameplural'] = 'H5Ps';
$string['pluginadministration'] = 'H5P';
$string['pluginname'] = 'H5P';
$string['hvp:addinstance'] = 'Neue H5P Aktivität erstellen';
$string['intro'] = 'Hallo fantstisch!';
$string['h5pfile'] = 'H5P Datei';
$string['fullscreen'] = 'Vollbild';
$string['disablefullscreen'] = 'Vollbild beenden';
$string['download'] = 'Heruterladen';
$string['copyright'] = 'Nutzungsrechte';
$string['embed'] = 'Einbinden';
$string['showadvanced'] = 'Erweitert anzeigen';
$string['hideadvanced'] = 'Erweitert ausblenden';
$string['resizescript'] = 'Für dynamische Größenänderungen:';
$string['size'] = 'Größe';
$string['close'] = 'Schließen';
$string['title'] = 'Titel';
$string['author'] = 'Autor';
$string['year'] = 'Jahr';
$string['source'] = 'Quelle';
$string['license'] = 'Lizenz';
$string['thumbnail'] = 'Vorschau';
$string['nocopyright'] = 'Für diesen Inhalt sind keine Informationen zu Nutzungsrechten verfügbar.';
$string['downloadtitle'] = 'Diesen Inhalt als H5P Datei herunterladen';
$string['copyrighttitle'] = 'Informationen zu Nutzungsrechten für diesen Inhalt anzeigen.';
$string['embedtitle'] = 'Code zur Einbettung dieses Inhalts anzeigen.';
$string['h5ptitle'] = 'Besuche H5P.org um mehr coole Inhalte zu sehen.';
$string['contentchanged'] = 'Dieser Inhalt hat sich seit der letzten Nutzung verändert.';
$string['startingover'] = "Jetzt geht\'s los.";
$string['lookforupdates'] = 'Nach H5P Aktualisierungen suchen';
// Admin settings.
$string['displayoptions'] = 'Optionen anzeigen';
$string['enableframe'] = 'Menüleiste und Rahmen anzeigen.';
$string['enabledownload'] = 'Download button';
$string['enableembed'] = 'Einbettung button';
$string['enablecopyright'] = 'Copyright button';
$string['enableabout'] = 'Über H5P button';
$string['enablesavecontentstate'] = 'Status des Inhalts speichern';
$string['enablesavecontentstate_help'] = 'Automatisch den Status des interaktiven Inhalts für jeden Nutzer speichern. Das bedeutet, dass die Nutzer da weitermachen können, wo sie aufgehört haben.';
$string['contentstatefrequency'] = 'Häufigkeit des Speicherns';
$string['contentstatefrequency_help'] = 'Wie oft soll der Nutzer den Inhalt (in Sekunden) speichern? Bei Problemen mit zu vielen AJAX Anfragen erhöhen.';
// Admin menu.
$string['settings'] = 'H5P Einstellungen';
$string['libraries'] = 'H5P Bibliotheken';
// Upload libraries section.
$string['uploadlibraries'] = 'Bibliotheken hochladen';
$string['options'] = 'Optionen';
$string['onlyupdate'] = 'Nur bereits bestehende Bibliotheken aktualisieren';
$string['disablefileextensioncheck'] = 'Prüfung der Dateiendung deaktivieren';
$string['disablefileextensioncheckwarning'] = "Warnung! Das deaktivieren der Prüfung kann zu Sicherheitsproblemen führen, da das Hochladen von php-Dateien möglich wird. Dadurch könnten Hacker in der Lage sein, schadhaften Code in die Website einzuschleusen. Bitte stellen Sie sicher, dass Sie genau wissen, was sie tun.";
$string['upload'] = 'Hochladen';
// Installed libraries section.
$string['installedlibraries'] = 'Installierte Bibliotheken';
$string['invalidtoken'] = 'Ungültiger Sicherheitsschlüssel.';
// H5P library list headers on admin page.
$string['librarylisttitle'] = 'Titel';
$string['librarylistrestricted'] = 'Eingeschränkt';
$string['librarylistinstances'] = 'Instanzen';
$string['librarylistinstancedependencies'] = 'Instanzabhägigkeiten';
$string['librarylistlibrarydependencies'] = 'Bibliothekabhängigkeiten';
$string['librarylistactions'] = 'Aktionen';
// H5P library page labels.
$string['addlibraries'] = 'Bibliotheken hinzufügen';
$string['installedlibraries'] = 'Installierte Bibliotheken';
$string['notapplicable'] = 'Nicht verfügbar';
$string['upgradelibrarycontent'] = 'Inhalt der Bibliothek aktualisieren';
// Upgrade H5P content page.
$string['upgrade'] = 'Aktualisiere H5P';
$string['upgradeheading'] = 'Aktualisiere {$a} Inhalt';
$string['upgradenoavailableupgrades'] = 'Für diese Bibliothek sind keine Aktualisierungen verfügbar.';
$string['enablejavascript'] = 'Bitte JavaScript aktivieren.';
$string['upgrademessage'] = 'Es sollen {$a} Inhaltinstanzen aktualisiert werden. Bitte die Version der Aktualisierung festlegen.';
$string['upgradeinprogress'] = 'Aktualisieren auf %ver...';
$string['upgradeerror'] = 'Ein Fehler trat beim Auswerten der Parameter auf:';
$string['upgradeerrordata'] = 'Konnte die Daten der Bibliothek %lib nicht laden.';
$string['upgradeerrorscript'] = 'Konnte das Aktualiserungsskript für %lib nicht laden.';
$string['upgradeerrorcontent'] = 'Konnte den Inhalt %id nicht aktualisieren:';
$string['upgradeerrorparamsbroken'] = 'Falsche Parameter.';
$string['upgradedone'] = '{$a} Inhaltsinstanzen wurde(n) erfolgreich aktualisiert.';
$string['upgradereturn'] = 'Zurück';
$string['upgradenothingtodo'] = "Es gibt keine aktualisierbaren Inhaltsinstanzen.";
$string['upgradebuttonlabel'] = 'Aktualisieren';
$string['upgradeinvalidtoken'] = 'Fehler: Ungültiger Sicherheitsschlüssel!';
$string['upgradelibrarymissing'] = 'Fehler: Die Bibliothek fehlt!';

// Results / report page.
$string['user'] = 'Nutzer';
$string['score'] = 'Punkte';
$string['maxscore'] = 'Maximale Punkte';
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
