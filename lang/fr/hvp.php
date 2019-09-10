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

$string['modulename'] = 'Contenu interactif';
$string['modulename_help'] = 'L\'activité H5P vous permet de créer des contenus interactifs tels que des vidéos interactives, des jeux de questions, des questions en glisser/déposer, des QCM, des présentations et bien plus encore.

En plus d\'être un outil auteur pour générer des contenus riches, H5P vous permet d\'importer ou d\'exporter vos ressources pour les réutiliser et les partager.

Les interactions utilisateur et les scores sont gérés par xAPI et disponibles dans le carnet de notes de moodle.

Vous pouvez ajouter des contenus interactifs H5P en uploadant des fichiers avec l\'extension .h5p. Vous pouvez créer et télécharger de tels fichiers sur le site h5p.org';
$string['modulename_link'] = 'https://h5p.org/moodle-more-help';
$string['modulenameplural'] = 'Contenus interactifs';
$string['pluginadministration'] = 'H5P';
$string['pluginname'] = 'H5P';
$string['intro'] = 'Introduction';
$string['h5pfile'] = 'Fichier H5P';
$string['fullscreen'] = 'Plein écran';
$string['disablefullscreen'] = 'Désactiver le plein écran';
$string['download'] = 'Télécharger';
$string['copyright'] = 'Droits d\'utilisation';
$string['embed'] = 'Embed';
$string['showadvanced'] = 'Afficher les options avancées';
$string['hideadvanced'] = 'Masquer les options avancées';
$string['resizescript'] = 'Incluez ce script dans votre site web si vous voulez bénéficier du redimensionnement dynamique de votre contenu embarqué:';
$string['size'] = 'Taille';
$string['close'] = 'Fermer';
$string['title'] = 'Titre';
$string['author'] = 'Auteur';
$string['year'] = 'Année';
$string['source'] = 'Source';
$string['license'] = 'Licence';
$string['thumbnail'] = 'Miniatures';
$string['nocopyright'] = 'Aucune information de copyright disponible pour ce contenu.';
$string['downloadtitle'] = 'Télécharger ce contenu au format H5P.';
$string['copyrighttitle'] = 'Voir les informations de droit d\'auteur pour ce contenu.';
$string['embedtitle'] = 'Voir le code embarqué pour ce contenu.';
$string['h5ptitle'] = 'Visitez H5P.org pour accéder à d\'autres ressources aussi cools.';
$string['contentchanged'] = 'Ce contenu a changé depuis votre dernière utilisation.';
$string['startingover'] = "Vous allez recommencer.";
$string['confirmdialogheader'] = 'Confirmez l\'action';
$string['confirmdialogbody'] = 'Merci de confirmer votre action. Cette opération est irréversible.';
$string['cancellabel'] = 'Annuler';
$string['confirmlabel'] = 'Confirmer';
$string['noh5ps'] = 'Il n\'y a aucune ressource interactive disponible pour ce cours.';

$string['lookforupdates'] = 'Rechercher des mises à jour H5P';
$string['removetmpfiles'] = 'Supprimer les anciens fichiers temporaires H5P';
$string['removeoldlogentries'] = 'Supprimer les anciennes entrées de logs H5P';
$string['removeoldmobileauthentries'] = 'Supprimer les anciennes entrées d\'authentification mobile H5P';

// Admin settings.
$string['displayoptiondownloadnever'] = 'Jamais';
$string['displayoptiondownloadalways'] = 'Toujours';
$string['displayoptiondownloadpermission'] = 'Seulement si l\'utilisateur a la permission d\'exporter H5P';
$string['displayoptionnevershow'] = 'Ne jamais afficher';
$string['displayoptionalwaysshow'] = 'Toujours afficher';
$string['displayoptionpermissions'] = 'Afficher seulement si l\'utilisateur a la permission d\'exporter H5P';
$string['displayoptionpermissionsembed'] = 'Afficher seulement si l\'utilisateur a la permission d\'intégrer H5P';
$string['displayoptionauthoron'] = 'Contrôlé par auteur, par défaut activé';
$string['displayoptionauthoroff'] = 'Contrôlé par auteur, par défaut désactivé';
$string['displayoptions'] = 'Afficher les options';
$string['enableframe'] = 'Afficher la barre de menu des actions';
$string['enabledownload'] = 'Autoriser le téléchargement';
$string['enableembed'] = 'Bouton d\'intégration';
$string['enablecopyright'] = 'Bouton de copyright';
$string['enableabout'] = 'Bouton à propos de H5P';

$string['sendusagestatistics'] = 'Contribuer aux statistiques d\'utilisation';
$string['sendusagestatistics_help'] = 'Les statistiques d\'utilisation seront automatiquement envoyées pour aider les développeurs à comprendre les usages d\'H5P et à déterminer les améliorations potentielles. En savoir plus sur <a {$a}>les données collectées sur h5p.org</a>.';
$string['enablesavecontentstate'] = 'Sauvegarder l\'état du contenu actuel';
$string['enablesavecontentstate_help'] = 'Sauvegarder automatiquement l\'état actuel du contenu interactif pour chaque utilisateur. Ceci signifie que l\'utilisateur pourra reprendre là où il en est resté la fois précédente.';
$string['contentstatefrequency'] = 'Fréquence des sauvegardes d\'état de vos contenus';
$string['contentstatefrequency_help'] = 'Fréquence des sauvegardes automatiques de la progression des utilisateurs en secondes. Augmentez ce nombre si vous rencontrez des problèmes avec des requêtes ajax';

// Admin menu.
$string['settings'] = 'Paramètres H5P';
$string['libraries'] = 'Bibliothèques H5P';

// Upload libraries section.
$string['uploadlibraries'] = 'Uploader les bibliothèques';
$string['options'] = 'Options';
$string['onlyupdate'] = 'Ne mettre à jour que les bibliothèques existantes';
$string['disablefileextensioncheck'] = 'Désactiver la vérification des extensions de fichiers';
$string['disablefileextensioncheckwarning'] = "Attention! Désactiver la vérification des extensions de fichiers peut entrainer des failles de sécurité puisqu\'il est alors possible de télécharger des fichiers php. Ceci permettrait à un attaquant d\'executer du code malicieux sur vos site. Vérifiez bien que vous savez ce que vous uploadez.";
$string['upload'] = 'Uploader';

// Installed libraries section.
$string['installedlibraries'] = 'Bibliothèques installées';
$string['invalidtoken'] = 'Token de sécurité invalide.';
$string['missingparameters'] = 'Paramètres manquants';
$string['nocontenttype'] = 'Aucun type de contenu n\'a été spécifié.';
$string['invalidcontenttype'] = 'Le type de contenu choisi est invalide.';
$string['installdenied'] = 'Vous n\'avez pas la permission d\'installer des types de contenu. Contacter l\administrateur de votre site.';
$string['downloadfailed'] = 'Le téléchargement de la bibliothèque demandée a échoué.';
$string['validationfailed'] = 'Le H5P demandé n\'est pas valide.';
$string['validatingh5pfailed'] = 'La validation du package H5P a échoué.';

// H5P library list headers on admin page.
$string['librarylisttitle'] = 'Titre';
$string['librarylistrestricted'] = 'Restreint';
$string['librarylistinstances'] = 'Instances';
$string['librarylistinstancedependencies'] = 'Dépendances d\'instance';
$string['librarylistlibrarydependencies'] = 'Dépendances des bibliothèques';
$string['librarylistactions'] = 'Actions';

// H5P library page labels.
$string['addlibraries'] = 'Ajouter des bibliothèques';
$string['installedlibraries'] = 'Bibliothèques installées';
$string['notapplicable'] = 'N/A';
$string['upgradelibrarycontent'] = 'Mettre à jour les contenus des bibliothèques';

// Upgrade H5P content page.
$string['upgrade'] = 'Mettre à jour H5P';
$string['upgradeheading'] = 'Mettre à jour le contenu {$a}';
$string['upgradenoavailableupgrades'] = 'Il n\'y a aucune mise à jour disponible pour cette bibliothèque.';
$string['enablejavascript'] = 'Merci d\'activer JavaScript.';
$string['upgrademessage'] = 'Vous êtes sur le point de mettre à jour {$a} instance(s). Sélectionnez la version.';
$string['upgradeinprogress'] = 'Mise à jour vers la version %ver en cours...';
$string['upgradeerror'] = 'Une erreur est survenue durant l\'analyse des paramètres:';
$string['upgradeerrordata'] = 'Impossible de charger les données pour la bibliothèque %lib.';
$string['upgradeerrorscript'] = 'Impossible de charger les scripts de mise à jour pour %lib.';
$string['upgradeerrorcontent'] = 'Impossible de mettre à jour le contenu %id:';
$string['upgradeerrorparamsbroken'] = 'Les paramètres sont invalides.';
$string['upgradedone'] = 'Vous avez mis à jour {$a} instance(s) avec succès.';
$string['upgradereturn'] = 'Retour';
$string['upgradenothingtodo'] = "Il n\'y a aucune instance à mettre à jour.";
$string['upgradebuttonlabel'] = 'Mettre à jour';
$string['upgradeinvalidtoken'] = 'Erreur : Token de sécruité invalide!';
$string['upgradelibrarymissing'] = 'Erreur : Votre bibliothèque est manquante!';
$string['upgradeerrormissinglibrary'] = 'Bibliothèque requise %lib manquante.';
$string['upgradeerrortoohighversion'] = 'Les paramètres contiennent %used. Seules %supported ou antérieures sont supportées.';
$string['upgradeerrornotsupported'] = 'Les paramètres contiennent %used qui n\'est pas supporté.';

// Results / report page.
$string['user'] = 'Utilisateur';
$string['score'] = 'Score';
$string['maxscore'] = 'Score Maximum';
$string['finished'] = 'Terminé';
$string['loadingdata'] = 'Chargement des données.';
$string['ajaxfailed'] = 'Le chargement des données a échoué.';
$string['nodata'] = "Il n\'y a aucune donnée correspondant à vos critères.";
$string['currentpage'] = 'Page $current sur $total';
$string['nextpage'] = 'Page suivante';
$string['previouspage'] = 'Page précédente';
$string['search'] = 'Rechercher';
$string['empty'] = 'Aucun résultat disponible';
$string['viewreportlabel'] = 'Rapport';
$string['dataviewreportlabel'] = 'Voir les réponses';
$string['invalidxapiresult'] = 'Aucun résultat xAPI n\'a été trouvé pour ce contenu combiné à cet identifiant utiliateur.';
$string['reportnotsupported'] = 'Non supporté';
$string['reportingscorelabel'] = 'Score:';
$string['reportingscaledscorelabel'] = 'Bulletin de notes:';
$string['reportingscoredelimiter'] = 'out of';
$string['reportingscaledscoredelimiter'] = ',';
$string['reportingquestionsremaininglabel'] = 'questions restantes à noter';
$string['reportsubmitgradelabel'] = 'Soumettre la notation';
$string['noanswersubmitted'] = 'Cet utilisateur n\'a pas encore soumis de réponse pour ce H5P';


// Editor.
$string['javascriptloading'] = 'Chargement de JavaScript...';
$string['action'] = 'Action';
$string['upload'] = 'Uploader';
$string['create'] = 'Créer';
$string['editor'] = 'Editeur';

$string['invalidlibrary'] = 'Bibliothèque invalide';
$string['nosuchlibrary'] = 'Aucune bibliothèque de ce type';
$string['noparameters'] = 'Pas de paramètres';
$string['invalidparameters'] = 'Paramètres invalides';
$string['missingcontentuserdata'] = 'Erreur : impossible de trouver les données utilisateur';

$string['maximumgrade'] = 'Note maximum';
$string['maximumgradeerror'] = 'Merci d\'entrer un nombre entier positif comme maximum de points disponibles pour cette activité.';

// Capabilities.
$string['hvp:view'] = 'Voir et interagir avec les activités H5P';
$string['hvp:addinstance'] = 'Ajouter une nouvelle activité H5P';
$string['hvp:manage'] = 'Editer une activité H5P existante';
$string['hvp:getexport'] = 'Récupérer un fichier H5P dans un cours';
$string['hvp:getembedcode'] = 'Voir le code embed H5P quand l\'option \'controllé par permission\' est activée.';
$string['hvp:saveresults'] = 'Sauvegarder les résultats';
$string['hvp:savecontentuserdata'] = 'Sauvegarder les données utilisateur H5P';
$string['hvp:viewresults'] = 'Visualiser les résultats';
$string['hvp:viewallresults'] = 'Voir les résultats pour tous les utilisateurs du cours';
$string['hvp:restrictlibraries'] = 'Restreindre une bibliothèque H5P';
$string['hvp:userestrictedlibraries'] = 'Utiliser des bibliothèques H5P restreintes';
$string['hvp:updatelibraries'] = 'Mettre à jour la version d\'une bibliothèque H5P';
$string['hvp:getcachedassets'] = 'Récupérer les assets mis en cache';
$string['hvp:installrecommendedh5plibraries'] = 'Installer de nouveaux types de contenu H5P sécurisés et recommandés par h5p.org';

// Capabilities error messages.
$string['nopermissiontogettranslations'] = 'Vous n\'avez pas les droits pour récupérer les traductions';
$string['nopermissiontoupgrade'] = 'Vous n\'avez pas les droits pour mettre à jour les bibliothèques.';
$string['nopermissiontorestrict'] = 'Vous n\'avez pas les droits pour restreindre les biliothèques.';
$string['nopermissiontosavecontentuserdata'] = 'Vous n\'avez pas les droits pour sauvegarder les données utilisateur.';
$string['nopermissiontosaveresult'] = 'Vous n\'avez pas les droits pour sauvegarder les résultats pour ce contenu.';
$string['nopermissiontoviewresult'] = 'Vous n\'avez pas les droits pour visualiser les résultats de ce contenu.';

// Editor translations.
$string['noziparchive'] = 'Votre version de PHP ne supporte pas ZipArchive.';
$string['noextension'] = 'Le fichier que vous avez uploadé n\'est pas un package HTML5 valide (il n\'a pas l\'extension .h5p)';
$string['nounzip'] = 'Le fichier que vous avez uploadé n\'est pas un package HTML5 valide (impossible de le décompresser)';
$string['noparse'] = 'Impossible de parser le fichier h5p.json';
$string['nojson'] = 'Le fichier h5p.json n\'est pas valide';
$string['invalidcontentfolder'] = 'Répertoire de contenu invalide';
$string['nocontent'] = 'Impossible de trouver ou de parser le fichier content.json';
$string['librarydirectoryerror'] = 'Le nom du répertoire des bibliothèques doit être de la forme machineName ou machineName-majorVersion.minorVersion (issue de library.json). (Répertoire: {$a->%directoryName} , machineName: {$a->%machineName}, majorVersion: {$a->%majorVersion}, minorVersion: {$a->%minorVersion})';
$string['missingcontentfolder'] = 'Un répertoire valide de contenu est manquant';
$string['invalidmainjson'] = 'Un fichier h5p.json valide est manquant';
$string['missinglibrary'] = 'La bibliothèque requise {$a->@library} est manquante';
$string['missinguploadpermissions'] = "Notez que les bibliothèques doivent exister dans le fichier uploadé, mais vous n\'avez pas autorisé l\'upload de nouvelles bibliothèques. Contactez votre administrateur.";
$string['invalidlibraryname'] = 'Nom de bibliothèque non valide: {$a->%name}';
$string['missinglibraryjson'] = 'Impossible de trouver le fichier library.json file avec un format json valide pour la bibliothèque {$a->%name}';
$string['invalidsemanticsjson'] = 'Le fichier semantics.json inclu dans la bibliothèque {$a->%name} n\'est pas valide';
$string['invalidlanguagefile'] = 'Le fichier de langue {$a->%file} de la bibliothèque {$a->%library} n\'est pas valide';
$string['invalidlanguagefile2'] = 'Le fichier de langue {$a->%languageFile} inclu dans la bibliothèque {$a->%name} n\'est pas valide';
$string['missinglibraryfile'] = 'Le fichier "{$a->%file}" de la bibliothèque "{$a->%name}" est manquant.';
$string['missingcoreversion'] = 'Le système n\'est pas en mesure d\'installer le composant <em>{$a->%component}</em> depuis le package, ceci nécessite une version plus récente du plugin H5P. Ce site utilise actuellement la version {$a->%current}, alors que la version requise est {$a->%required} ou supérieur. Vous devriez faire une mise à jour et essayer à nouveau.';
$string['invalidlibrarydataboolean'] = 'La donnée fournie pour la proriété {$a->%property} de la bibliothèque {$a->%library} n\'est pas valide. Booléen attendu.';
$string['invalidlibrarydata'] = 'La donnée fournie pour la proriété {$a->%property} de la bibliothèque {$a->%library} n\'est pas valide';
$string['invalidlibraryproperty'] = 'Impossible de lire la propriété {$a->%property} de la bibliothèque {$a->%library}';
$string['missinglibraryproperty'] = 'La propriété requise {$a->%property} de la bibliothèque {$a->%library} est manquante';
$string['invalidlibraryoption'] = 'L\'option {$a->%option} de la bibliothèque {$a->%library} n\'est pas autorisée';
$string['addedandupdatedss'] = 'Ajout de la nouvelle bibliothèque H5P {$a->%new} et mise à jour de l\'ancienne {$a->%old}.';
$string['addedandupdatedsp'] = 'Ajout de la nouvelle bibliothèque H5P {$a->%new} et mise à jour des anciennes {$a->%old}.';
$string['addedandupdatedps'] = 'Ajout des nouvelles bibliothèques H5P {$a->%new} et mise à jour de l\'ancienne {$a->%old}.';
$string['addedandupdatedpp'] = 'Ajout des nouvelles bibliothèques H5P {$a->%new} et mise à jour des anciennes {$a->%old}.';
$string['addednewlibrary'] = 'Ajout de la nouvelle bibliothèque H5P {$a->%new}.';
$string['addednewlibraries'] = 'Ajout des nouvelles bibliothèques H5P {$a->%new}.';
$string['updatedlibrary'] = 'Mise à jour de l\'ancienne bibliothèque H5P {$a->%old}.';
$string['updatedlibraries'] = 'Mise à jour des anciennes bibliothèquse H5P {$a->%old}.';
$string['missingdependency'] = 'la dépendance {$a->@dep} requise par {$a->@lib} est manquante.';
$string['invalidstring'] = 'La chaine fournie n\'est pas valide selon l\'expression régulière suivante : (value: \"{$a->%value}\", regexp: \"{$a->%regexp}\")';
$string['invalidfile'] = 'Le fichier "{$a->%filename}" n\est pas autorisé. Seuls les fichiers avec les extensions suivantes sont autorisés : {$a->%files-allowed}.';
$string['invalidmultiselectoption'] = 'éléments selectionnés dans un multi-select non valides.';
$string['invalidselectoption'] = 'élément sélectionné dans un select non valide.';
$string['invalidsemanticstype'] = 'Erreur interne H5P: Type de contenu "{$a->@type}" non valide. Supprimez ce contenu!';
$string['invalidsemantics'] = 'La bibliothèque utilisée dans cette ressource n\'est pas valide';
$string['unabletocreatedir'] = 'Impossible de créer le répertoire.';
$string['unabletogetfieldtype'] = 'Impossible de récupérer le type de champ.';
$string['filetypenotallowed'] = 'Type de fichier non autorisé.';
$string['invalidfieldtype'] = 'Type de champ invalide.';
$string['invalidimageformat'] = 'Format de fichier image non valide. Utilisez jpg, png ou gif.';
$string['filenotimage'] = 'Ce fichier n\'est pas une image.';
$string['invalidaudioformat'] = 'Format de fichier audio non valide. Utilisez mp3 ou wav.';
$string['invalidvideoformat'] = 'Format de fichier vidéo non valide. Utilisez mp4 ou webm.';
$string['couldnotsave'] = 'Impossible de sauvegarder le fichier.';
$string['couldnotcopy'] = 'Impossible de copier le fichier.';
$string['librarynotselected'] = 'Vous devez sélectionner un type de contenu.';

// Welcome messages.
$string['welcomeheader'] = 'Bienvenue dans le monde H5P!';
$string['welcomegettingstarted'] = 'Pour démarrer avec H5P et Moodle, consultez nos tutoriels <a {$a->moodle_tutorial}>tutorial</a> et testez <a {$a->example_content}>nos exemples</a> sur le site H5P.org pour vous en inspirer.';
$string['welcomecommunity'] = 'Nous espérons que vous allez apprécier H5P et rejoindre notre communauté en constante augmentation au travers de nos <a {$a->forums}>forums</a>.';
$string['welcomecontactus'] = 'Si vous avez des suggestions, n\'hésitez pas à <a {$a}>nous contacter</a>. Nous prenons toutes les suggestions très sérieuseusement en considération pour rendre H5P meilleur chaque jour !';
$string['invalidlibrarynamed'] = 'La bibliothèque H5P {$a->%library} utilisée dans le contenu n\'est pas valide';

// Licensing.
$string['copyrightinfo'] = 'Information copyright';
$string['years'] = 'Année(s)';
$string['undisclosed'] = 'Masqué';
$string['attribution'] = 'Attribution 4.0';
$string['attributionsa'] = 'Attribution - Partage dans les Mêmes Conditions 4.0';
$string['attributionnd'] = 'Attribution - Pas de Modification 4.0';
$string['attributionnc'] = 'Attribution - Non Commercial 4.0';
$string['attributionncsa'] = 'Attribution - Non Commercial- Partage dans les Mêmes Conditions 4.0';
$string['attributionncnd'] = 'Attribution - Non Commercial- Pas de Modification 4.0';
$string['gpl'] = 'Licence GPL v3';
$string['pd'] = 'Domaine Public';
$string['pddl'] = 'Transfert dans le Domaine Public et Licence';
$string['pdm'] = 'Marque du domaine public';
$string['copyrightstring'] = 'Copyright';
$string['by'] = 'by';
$string['showmore'] = 'Voir plus';
$string['showless'] = 'Voir moins';
$string['sublevel'] = 'Sous-niveau';
$string['noversionattribution'] = 'Attribution';
$string['noversionattributionsa'] = 'Attribution - Partage dans les Mêmes Conditions';
$string['noversionattributionnd'] = 'Attribution - Pas de Modification';
$string['noversionattributionnc'] = 'Attribution - Non Commercial';
$string['noversionattributionncsa'] = 'Attribution - Non Commercial- Partage dans les Mêmes Conditions';
$string['noversionattributionncnd'] = 'Attribution - Non Commercial- Pas de Modification';
$string['licenseCC40'] = '4.0 International';
$string['licenseCC30'] = '3.0 Unported';
$string['licenseCC25'] = '2.5 Generic';
$string['licenseCC20'] = '2.0 Generic';
$string['licenseCC10'] = '1.0 Generic';
$string['licenseGPL'] = 'General Public License';
$string['licenseV3'] = 'Version 3';
$string['licenseV2'] = 'Version 2';
$string['licenseV1'] = 'Version 1';
$string['licenseCC010'] = 'CC0 1.0 Universal (CC0 1.0) Public Domain Dedication';
$string['licenseCC010U'] = 'CC0 1.0 Universal';
$string['licenseversion'] = 'License Version';

// Embed.
$string['embedloginfailed'] = 'Vous n\'avez pas accès à ce contenu. Essayez de vous connecter.';

// Reuse.
$string['reuse'] = 'Réutiliser';
$string['reuseContent'] = 'Réutiliser le contenu';
$string['reuseDescription'] = 'Réutiliser ce contenu.';
$string['contentCopied'] = 'Le contenu a été copié dans le presse-papier';

// Offline
$string['connectionLost'] = 'Connection lost. Results will be stored and sent when you regain connection.';
$string['connectionReestablished'] = 'Connection reestablished.';
$string['resubmitScores'] = 'Attempting to submit stored results.';
$string['offlineDialogHeader'] = 'Your connection to the server was lost';
$string['offlineDialogBody'] = 'We were unable to send information about your completion of this task. Please check your internet connection.';
$string['offlineDialogRetryMessage'] = 'Retrying in :num....';
$string['offlineDialogRetryButtonLabel'] = 'Retry now';
$string['offlineSuccessfulSubmit'] = 'Successfully submitted results.';

// Error messages.
$string['fileExceedsMaxSize'] = 'One of the files inside the package exceeds the maximum file size allowed. (%file %used > %max)';
$string['unpackedFilesExceedsMaxSize'] = 'The total size of the unpacked files exceeds the maximum size allowed. (%used > %max)';
$string['couldNotReadFileFromZip'] = 'Unable to read file from the package: %fileName';
$string['couldNotParseJSONFromZip'] = 'Unable to parse JSON from the package: %fileName';
$string['couldNotParsePostData'] = 'Could not parse post data.';