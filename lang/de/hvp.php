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
$string['modulename_help'] = 'Das H5P Aktivitätenmodul ermöglicht das Erstellen von interaktiven Inhalten, wie interaktive Videos, Fragebögen, Drag-and-Drop-Fragen, Multiple-Choice-Fragen, Präsentationen und vieles mehr.
Zusätzlich zu den Funktionen als Tool für rich content, ermöglicht H5P es, H5P-Dateien zu importieren und exportieren, um die Inhalte effektiv wiederverwenden und teilen zu können.
Nutzerinteraktion und Punkte werden mittels der xAPI verfolgt und sind im Moodle Notenbuch verfügbar.
Interaktive H5P-Inhalte können durch das Hochladen von .h5p-Dateien hinzugefügt werden. Das Erstellen und Herunterladen von .h5p-Dateien ist unter h5p.org möglich.';
$string['modulename_link'] = 'https://h5p.org/moodle-more-help';
$string['modulenameplural'] = 'H5Ps';
$string['pluginadministration'] = 'H5P';
$string['pluginname'] = 'H5P';
$string['hvp:addinstance'] = 'Neue H5P-Aktivität erstellen';
$string['intro'] = 'Einleitung';
$string['h5pfile'] = 'H5P-Datei';
$string['fullscreen'] = 'Vollbild';
$string['disablefullscreen'] = 'Vollbild beenden';
$string['download'] = 'Herunterladen';
$string['copyright'] = 'Nutzungsrechte';
$string['embed'] = 'Einbinden';
$string['showadvanced'] = 'Erweitert anzeigen';
$string['hideadvanced'] = 'Erweitert ausblenden';
$string['resizescript'] = 'Fügen Sie dieses Skript auf Ihrer Website ein, um die Größe des Inhalts dynamisch ändern zu können:';
$string['size'] = 'Größe';
$string['close'] = 'Schließen';
$string['title'] = 'Titel';
$string['author'] = 'Autor';
$string['year'] = 'Jahr';
$string['source'] = 'Quelle';
$string['license'] = 'Lizenz';
$string['thumbnail'] = 'Vorschau';
$string['nocopyright'] = 'Für diesen Inhalt sind keine Informationen zu Urheberrechten verfügbar.';
$string['downloadtitle'] = 'Diesen Inhalt als H5P-Datei herunterladen';
$string['copyrighttitle'] = 'Informationen zum Urheberrecht für diesen Inhalt anzeigen.';
$string['embedtitle'] = 'Code zur Einbettung dieses Inhalts anzeigen.';
$string['h5ptitle'] = 'Besuche H5P.org um mehr coole Inhalte zu sehen.';
$string['contentchanged'] = 'Dieser Inhalt hat sich seit der letzten Nutzung verändert.';
$string['startingover'] = "Jetzt geht\'s los.";
$string['confirmdialogheader'] = 'Bestätigen';
$string['confirmdialogbody'] = 'Zum Fortfahren bestätigen. Dieser Vorgang kann nicht rückgängig gemacht werden.';
$string['cancellabel'] = 'Abbrechen';
$string['confirmlabel'] = 'Bestätigen';

// Update message email for admin
$string['messageprovider:updates'] = 'Benachrichtigung über verfügbare H5P-Aktualisierungen';
$string['updatesavailabletitle'] = 'Neue H5P-Aktualisierungen sind verfügbar';
$string['updatesavailablemsgpt1'] = 'Für die auf dieser Moodle-Seite installierten H5P-Inhaltstypen sind Aktualisierungen verfügbar.';
$string['updatesavailablemsgpt2'] = 'Für weitere Informationen bitte dem Link unten folgen.';
$string['updatesavailablemsgpt3'] = 'Das letzte Update wurde freigegeben am: {$a}';
$string['updatesavailablemsgpt4'] = 'Installiert ist die Verion vom: {$a}';

$string['lookforupdates'] = 'Nach H5P-Aktualisierungen suchen';
$string['removetmpfiles'] = 'Entfernen alter temporärer H5P-Dateien';
$string['removeoldlogentries'] = 'Entfernen alter H5P-Logdateien';

// Admin settings.
$string['displayoptions'] = 'Optionen anzeigen';
$string['enableframe'] = 'Menüleiste und Rahmen anzeigen.';
$string['enabledownload'] = 'Download-Knopf';
$string['enableembed'] = 'Einbetten-Knopf';
$string['enablecopyright'] = 'Urheberrecht-Knopf';
$string['enableabout'] = 'Über-H5P-Knopf';

$string['externalcommunication'] = 'Externe Kommunikation';
$string['externalcommunication_help'] = 'Die Entwicklung von H5P durch die Übermittlung von anonymen Nutzungsdaten unterstützen. Wenn diese Option ausgeschalten wird, wird diese Seite nicht mehr die akutellsten H5P-Updates erhalten. Mehr Informationen darüber,<a {$a}>welche Daten gesammelt werden</a> sind auf h5p.org verfügbar.';
$string['enablesavecontentstate'] = 'Inhalte automatisch speichern';
$string['enablesavecontentstate_help'] = 'Automatisch den Status des interaktiven Inhalts für jeden Nutzer speichern. Das bedeutet, dass die Nutzer da weitermachen können, wo sie aufgehört haben.';
$string['contentstatefrequency'] = 'Speicherhäufigkeit';
$string['contentstatefrequency_help'] = 'Wie oft (in Sekunden) soll der Inhalt des Nutzers automatisch gespeichert werden? Bei Problemen mit zu vielen AJAX-Anfragen erhöhen.';

// Admin menu.
$string['settings'] = 'H5P-Einstellungen';
$string['libraries'] = 'H5P-Bibliotheken';

// Update libraries section.
$string['updatelibraries'] = 'Alle Bibliotheken installieren';
$string['updatesavailable'] = 'Es sind Aktualisierungen für die H5P-Inhaltstypen vorhanden.';
$string['whyupdatepart1'] = 'Informationen, warum es wichtig ist und welche Vorteile Aktualisierungen bringen, sind unter "<a {$a}>Warum H5P aktualisieren?</a>" verfügbar.';
$string['whyupdatepart2'] = 'Auf dieser Seite befinden sich außerdem auch die verschiedenen Änderungsprotokolle. Darin werden die neuesten Features und die behobenen Fehler aufgelistet.';
$string['currentversion'] = 'Aktuelle Version';
$string['availableversion'] = 'Verfügbare Aktualisierung';
$string['usebuttonbelow'] = 'Mit dem Knopf unten können automatisch alle Inhaltstypen heruntergeladen und aktualisert werden.';
$string['downloadandupdate'] = 'Herunterladen & Aktualisieren';
$string['missingh5purl'] = 'Die URL für die H5P-Datei fehlt';
$string['unabletodownloadh5p'] = 'Herunterladen der H5P-Datei nicht möglich';

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
$string['missingparameters'] = 'Fehlende Parameter';

// H5P library list headers on admin page.
$string['librarylisttitle'] = 'Titel';
$string['librarylistrestricted'] = 'Eingeschränkt';
$string['librarylistinstances'] = 'Instanzen';
$string['librarylistinstancedependencies'] = 'Instanzabhägigkeiten';
$string['librarylistlibrarydependencies'] = 'Bibliotheksabhängigkeiten';
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
$string['upgrademessage'] = 'Es sollen {$a} Inhaltsinstanzen aktualisiert werden. Bitte die Version der Aktualisierung festlegen.';
$string['upgradeinprogress'] = 'Aktualisieren auf %ver...';
$string['upgradeerror'] = 'Ein Fehler trat beim Auswerten der Parameter auf:';
$string['upgradeerrordata'] = 'Konnte die Daten der Bibliothek %lib nicht laden.';
$string['upgradeerrorscript'] = 'Konnte das Aktualisierungsskript für %lib nicht laden.';
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
$string['maxscore'] = 'Maximale Punktzahl';
$string['finished'] = 'Beendet';
$string['loadingdata'] = 'Lade Daten.';
$string['ajaxfailed'] = 'Fehler beim Laden der Daten.';
$string['nodata'] = "Es sind keine Daten vorhanden, die den Kriterien entsprechen.";
$string['currentpage'] = 'Seite $current von $total';
$string['nextpage'] = 'Nächste Seite';
$string['previouspage'] = 'Vorherige Seite';
$string['search'] = 'Suchen';
$string['empty'] = 'Keine Ergebnisse verfügbar';

//Editor
$string['javascriptloading'] = 'Warte auf JavaScript';
$string['action'] = 'Aktion';
$string['upload'] = 'Hochladen';
$string['create'] = 'Erstellen';
$string['editor'] = 'Bearbeiten';

$string['invalidlibrary'] = 'Ungültige Bibliothek';
$string['nosuchlibrary'] = 'Bibliothek nicht vorhanden';
$string['noparameters'] = 'Keine Parameter';
$string['invalidparameters'] = 'Ungültige Parameter';
$string['missingcontentuserdata'] = 'Fehler: Konnte den Nutzerinhalt nicht finden';

// Capabilities
$string['hvp:addinstance'] = 'Neue H5P-Aktivität hinzufügen';
$string['hvp:restrictlibraries'] = 'H5P-Bibliothek beschränken';
$string['hvp:updatelibraries'] = 'Aktualisieren einer H5P-Bibliothek';
$string['hvp:userestrictedlibraries'] = 'Verwendung eingeschränkter H5P-Bibliotheken';
$string['hvp:savecontentuserdata'] = 'H5P-Nutzerinhalt speichern';
$string['hvp:saveresults'] = 'Ergebnis des H5P-Inhalts speichern';
$string['hvp:viewresults'] = 'Ergebnis des H5P-Inhalts ansehen';
$string['hvp:getcachedassets'] = 'Zwischengespeicherte H5P-Inhaltswerte erhalten';
$string['hvp:getcontent'] = 'H5P-Dateiinhalt im Kurs verwenden/ansehen';
$string['hvp:getexport'] = 'Exportierte H5P Datei im Kurs verwenden';
$string['hvp:updatesavailable'] = 'Nachricht erhalten, wenn H5P-Aktualisierungen verfügbar sind';

// Capabilities error messages
$string['nopermissiontoupgrade'] = 'Die nötigen Rechte, um die Bibliothek zu aktualisieren, sind nicht vorhanden.';
$string['nopermissiontorestrict'] = 'Die nötigen Rechte, um Bibliotheken zu beschränken, sind nicht vorhanden.';
$string['nopermissiontosavecontentuserdata'] = 'Die nötigen Rechte, um Nutzerinhalte zu speichern, sind nicht vorhanden.';
$string['nopermissiontosaveresult'] = 'Die nötigen Rechte, um die Ergebnisse dieses Inhalts zu speichern, sind nicht vorhanden.';
$string['nopermissiontoviewresult'] = 'Die nötigen Rechte, um die Ergebnisse dieses Inhalts anzusehen, sind nicht vorhanden.';

// Editor translations
$string['noziparchive'] = 'Diese PHP-Version unterstützt ZipArchive nicht.';
$string['noextension'] = 'Die hochgeladene Datei ist kein gültiges HTML5-Paket (Keine .h5p Dateiendung)';
$string['nounzip'] = 'Die hochgeladene Datei ist kein gültiges HTML5-Paket (Kann nicht entpackt werden)';
$string['noparse'] = 'Konnte die zentrale h5p.json-Datei nicht analysieren';
$string['nojson'] = 'Die zentrale h5p.json-Datei ist ungültig';
$string['invalidcontentfolder'] = 'Ungültiger Inhaltsordner';
$string['nocontent'] = 'Konnte die content.json-Datei nicht finden oder analysieren';
$string['librarydirectoryerror'] = 'Der Name des Bibliotheksverzeichnisses muss machineName oder machineName-majorVersion.minorVersion (aus library.json) entsprechen. (Verzeichnis: {$a->%directoryName} , machineName: {$a->%machineName}, majorVersion: {$a->%majorVersion}, minorVersion: {$a->%minorVersion})';
$string['missingcontentfolder'] = 'Ein gültiger Inhaltsordner fehlt';
$string['invalidmainjson'] = 'Eine gültige zentrale h5p.json-Datei fehlt';
$string['missinglibrary'] = 'Die benötigte Bibliothek {$a->@library} fehlt';
$string['missinguploadpermissions'] = "Hinweis: Die Bibliotheken mögen in den hochgeladenen Dateien zwar enthalten sein, aber die nötigen Rechte, um neue Bibliotheken hochzuladen, fehlen. Dazu bitte den Seitenadministrator kontaktieren.";
$string['invalidlibraryname'] = 'Ungültiger Bibliotheksname: {$a->%name}';
$string['missinglibraryjson'] = 'Konnte die Datei library.json mit gültigem json-format für die Bibliothek {$a->%name} nicht finden.';
$string['invalidsemanticsjson'] = 'Ungültige semantics.json Datei wurde in die Bibliothek {$a->%name} eingefügt';
$string['invalidlanguagefile'] = 'Ungültige Sprachdatei {$a->%file} in Bibliothek {$a->%library}';
$string['invalidlanguagefile2'] = 'Ungültige Sprachdatei {$a->%languageFile} wurde in die Bibliothek {$a->%name} eingefügt';
$string['missinglibraryfile'] = 'Die Datei "{$a->%file}" fehlt in der Bibliothek "{$a->%name}"';
$string['invalidlibrarydataboolean'] = 'Ungültige Daten für {$a->%property} in {$a->%library}. Boolean wurde erwartet.';
$string['invalidlibrarydata'] = 'Ungültige Daten für {$a->%property} in {$a->%library}';
$string['invalidlibraryproperty'] = 'Kann das Merkmal {$a->%property} in {$a->%library} nicht lesen';
$string['missinglibraryproperty'] = 'Das benötigte Merkmal {$a->%property} fehlt in {$a->%library}';
$string['invalidlibraryoption'] = 'Nicht erlaubte Option {$a->%option} in {$a->%library}';
$string['addedandupdatelibraries'] = '{$a->%new} neue H5P-Bibliotheken hinzugefügt und {$a->%old} alte aktualisiert.';
$string['addednewlibraries'] = '{$a->%new} neue H5P-Bibliotheken hinzugefügt.';
$string['updatedlibraries'] = '{$a->%old} H5P-Bibliotheken aktualisiert.';
$string['missingdependency'] = 'Fehlende Abhängigkeit {$a->@dep} wird von {$a->@lib} benötigt.';
$string['invalidstring'] = 'Der übergebene string ist nicht gültig gemäß des regulären Ausdrucks in semantics. (value: \"{$a->%value}\", regexp: \"{$a->%regexp}\")';
$string['invalidfile'] = 'Datei "{$a->%filename}" nicht erlaubt. Es sind nur Dateien mit den folgenden Endungen erlaubt: {$a->%files-allowed}.';
$string['invalidmultiselectoption'] = 'Ungültige Option bei der Mehrfachauswahl ausgewählt.';
$string['invalidselectoption'] = 'Ungültige Option bei der Auswahl ausgewählt.';
$string['invalidsemanticstype'] = 'Interner H5P-Fehler: Unbekannter Inhaltstyp "{$a->@type}" in semantics. Inhalt wird entfernt!';
$string['invalidsemantics'] = 'Laut semantics ist die im Inhalt verwendete Bibliothek keine gültige.';
$string['copyrightinfo'] = 'Urheberrechtsinformationen';
$string['years'] = 'Jahr(e)';
$string['undisclosed'] = 'Unbestimmt';
$string['attribution'] = 'Namensnennung 4.0 (CC BY)';
$string['attributionsa'] = 'Namensnennung-Weitergabe unter gleichen Bedingungen 4.0 (CC BY-SA)';
$string['attributionnd'] = 'Namensnennung-KeineBearbeitung 4.0 (CC BY-ND)';
$string['attributionnc'] = 'Namensnennung-NichtKommerziell 4.0 (CC BY-NC)';
$string['attributionncsa'] = 'Namensnennung-NichtKommerziell-Weitergabe unter gleichen Bedingungen 4.0 (CC BY-NC-SA)';
$string['attributionncnd'] = 'Namensnennung-NichtKommerziell-KeineBearbeitung 4.0 (CC BY-NC-ND)';
$string['gpl'] = 'General Public License v3';
$string['pd'] = 'Gemeingut';
$string['pddl'] = 'Gemeingut Einsatz und Lizenz';
$string['pdm'] = 'Gemeingut Zeichen';
$string['copyrightstring'] = 'Urheberrecht';
$string['unabletocreatedir'] = 'Erstellen des Verzeichnisses nicht möglich.';
$string['unabletogetfieldtype'] = 'Bestimmen des Feldtyps nicht möglich.';
$string['filetypenotallowed'] = 'Dateityp nicht erlaubt.';
$string['invalidfieldtype'] = 'Ungültiger Feldtyp.';
$string['invalidimageformat'] = 'Ungültiges Bild-Dateiformat. Verwende jpg, png oder gif.';
$string['filenotimage'] = 'Die Datei ist kein Bild.';
$string['invalidaudioformat'] = 'Ungültiges Audio-Dateiformat. Verwende mp3 oder wav.';
$string['invalidvideoformat'] = 'Ungültiges Video-Dateiformat. Verwende mp4 oder webm.';
$string['couldnotsave'] = 'Konnte die Datei nicht speichern.';
$string['couldnotcopy'] = 'Konnte die Datei nicht kopieren.';

// Welcome messages
$string['welcomeheader'] = 'Willkommen in der Welt von H5P!';
$string['welcomegettingstarted'] = 'Um mit H5P und Moodle loszulegen, befindet sich hier ein <a {$a->moodle_tutorial}>Tutorial</a> und es gibt<a {$a->example_content}>Beispielinhalte</a> auf H5P.org als Inspiration.<br>Für das bestmögliche Erlebnis wurden die beliebtesten Inhaltstypen installiert!';
$string['welcomecommunity'] = 'Wir hoffen, dass Ihnen H5P gefällt und bieten die Möglichkeit, im <a {$a->forums}>Forum</a> und im Chat-Room <a {$a->gitter}>H5P bei Gitter</a> aktiv zu werden.';
$string['welcomecontactus'] = 'Für Feedback bitte nicht zögern, uns zu <a {$a}>kontaktieren</a>. Wir nehmen Feedback sehr ernst und bemühen uns, H5P jeden Tag besser zu machen!';
