<?php
/**
 * Admin tool: Addon File Editor
 *
 * This tool allows you to "edit", "delete", "create", "upload" or "backup" files of installed 
 * Add-ons such as modules, templates and languages via the Website Baker backend. This enables
 * you to perform small modifications on installed Add-ons without downloading the files first.
 *
 * This file contains the French text outputs of the module.
 * 
 * LICENSE: GNU General Public License 3.0
 * 
 * @author		Christian Sommer (doc)
 * @copyright	(c) 2008-2010
 * @license		http://www.gnu.org/licenses/gpl.html
 * @version		1.1.0
 * @language	French
 * @translation	quinto (WB forum user name)
 * @platform	Website Baker 2.8
*/

// French module description
$module_description = 'Cet outil vous permets de "modifier", "supprimer", "uploader" ou "sauvegarder" les fichiers des Ajouts install&eacute;s (Modules, Mod&egrave;les et Langages) en utilisant l&apos;interface d&apos;administration. Gr&acirc;ce &agrave; cet outil vous pourrez effectuer de petites modifications sur les Ajouts install&eacute;s sans avoir besoin de t&eacute;l&eacute;charger les fichiers.';

// declare module language array
$LANG = array();

// Text outputs for the version check
$LANG[0] = array(
	'TXT_VERSION_ERROR'			=> 'Erreur: "Addon File Editor" n&eacute;c&eacute;ssite Website Baker 2.7 ou sup&eacute;rieur.',
);

// Text outputs overview page (htt/addons_overview.htt)
$LANG[1] = array(
	'TXT_DESCRIPTION'			=> 'La liste ci-dessous affiche tous les Ajouts lisibles par PHP. Vous pouvez modifier les fichiers ' . 
								   'en cliquant sur le nom de l\'Ajout. L\'ic&ocirc;ne de t&eacute;l&eacute;chargement vous permets de ' .
								   'cr&eacute;er une sauvegarde r&eacute;installable facilement.',
	'TXT_FTP_NOTICE'			=> 'Les Ajouts/fichiers soulign&eacute;s en rouge ne sont pas modifiable via PHP. Cela peut &ecirc;tre caus&eacute; par l\'installation ' . 
	                               'd\'Ajouts par FTP. Vous devez <a class="link" target="_blank" href="{URL_ASSISTANT}">' .
								   'activer le support du FTP</a> afin de pouvoir modifier ces fichiers.',
	'TXT_HEADING'				=> 'Ajouts Install&eacute;s (Modules, Mod&egrave;les, Fichiers de Langage)',
	'TXT_HELP'					=> 'Aide',

	'TXT_RELOAD'				=> 'Recharger',
	'TXT_ACTION_EDIT'			=> 'Modifier',
	'TXT_ACTION_DELETE'			=> 'Supprimer',
	'TXT_FTP_SUPPORT'			=> ' (acc&egrave;s FTP en &eacute;criture requis pour modifier)',

	'TXT_MODULES'				=> 'Modules',
	'TXT_LIST_OF_MODULES'		=> 'Liste des Modules install&eacute;s',
	'TXT_EDIT_MODULE_FILES'		=> 'Modifier les fichier du module',
	'TXT_ZIP_MODULE_FILES'		=> 'Sauvegarder et t&eacute;l&eacute;charger les fichiers du module',

	'TXT_TEMPLATES'				=> 'Mod&egrave;les',
	'TXT_LIST_OF_TEMPLATES'		=> 'Liste des Mod&egrave;les install&eacute;s',
	'TXT_EDIT_TEMPLATE_FILES'	=> 'Modifier les fichiers du mod&egrave;les',
	'TXT_ZIP_TEMPLATE_FILES'	=> 'Sauvegarder et t&eacute;l&eacute;charger fichier de langage',
	
	'TXT_LANGUAGES'				=> 'Fichiers de Langage',
	'TXT_LIST_OF_LANGUAGES'		=> 'Liste des fichiers de langage de WB install&eacute;s',
	'TXT_EDIT_LANGUAGE_FILES'	=> 'Modifier le fichier de langage',
	'TXT_ZIP_LANGUAGE_FILES'	=> 'T&eacute;l&eacute;charger fichier de langage',
);

// Text outputs filemanager page (htt/filemanager.htt)
$LANG[2] = array(
	'TXT_EDIT_DESCRIPTION'		=> 'Le gestionnaire de fichiers vous permet de modifier, cr&eacute;er, supprimer et uploader les fichiers. un clic sur le nom du ' .
								   'fichier (sur le texte ou l\'image) permet d\'ouvrir le fichier pour la visualisation ou la modification.',
	'TXT_BACK_TO_OVERVIEW'		=> 'Retour à la vue générale des Ajouts',

	'TXT_MODULE'				=> 'Module',
	'TXT_TEMPLATE'				=> 'Mod&egrave;le',
	'TXT_LANGUAGE'				=> 'Fichier Langage de WB',
	'TXT_FTP_SUPPORT'			=> ' (acc&egrave;s FTP en &eacute;criture requis pour modifier)',

	'TXT_RELOAD'				=> 'Recharger',
	'TXT_CREATE_FILE_FOLDER'	=> 'Cr&eacute;er Fichier/Dossier',
	'TXT_UPLOAD_FILE'			=> 'Uploader Fichier',
	'TXT_VIEW'					=> 'Visualisation',
	'TXT_EDIT'					=> 'Modifier',
	'TXT_RENAME'				=> 'Renommer',
	'TXT_DELETE'				=> 'Supprimer',

	'TXT_FILE_INFOS'			=> 'Information sur le fichier',
	'TXT_FILE_INFOS'			=> 'Action',
	'TXT_FILE_TYPE_TEXTFILE'	=> 'Texte',
	'TXT_FILE_TYPE_FOLDER'		=> 'Dossier',
	'TXT_FILE_TYPE_IMAGE'		=> 'Image',
	'TXT_FILE_TYPE_ARCHIVE'		=> 'Archive',
	'TXT_FILE_TYPE_OTHER'		=> 'Inconnu',

	'DATE_FORMAT'				=> 'd/m/y / h:m',
);

// General text outputs for the file handler templates
$LANG[3] = array(
	'ERR_WRONG_PARAMETER'		=> 'Les param&egrave;tres spécifi&eacute;s sont erron&eacute;s ou incomplets.',
	'TXT_MODULE'				=> 'Module',
	'TXT_TEMPLATE'				=> 'Mod&egrave;le',
	'TXT_LANGUAGE'				=> 'Fichier Langage de WB',
	'TXT_ACTION'				=> 'Action',
	'TXT_ACTUAL_FILE'			=> 'Fichier en cours d\'édition',
	'TXT_SUBMIT_CANCEL'			=> 'Annuler',
);	

// Text outputs file handler (htt/action_handler_edit_textfile.htt)
$LANG[4] = array(
	'TXT_ACTION_EDIT_TEXTFILE'	=> 'Modifier le fichier texte',
	'TXT_SUBMIT_SAVE'			=> 'Sauvegarder',
	'TXT_SUBMIT_SAVE_BACK'		=> 'Sauvegarder &amp; Retour',
	'TXT_ACTUAL_FILE'			=> 'Fichier en cours d\'&eacute;dition',
	'TXT_SAVE_SUCCESS'			=> 'Mofidication du fichier appliqu&eacute;e avec succ&egrave;s.',
	'TXT_SAVE_ERROR'			=> 'Impossible de modifier le fichier. Veuillez v&eacute;rifier les permissions.',
);

// Text outputs file handler (htt/action_handler_rename_file_folder.htt)
$LANG[5] = array(
	'TXT_ACTION_RENAME_FILE'	=> 'Renommer fichier/dossier',
	'TXT_OLD_FILE_NAME'			=> 'Fichier/Dossier (ancien)',
	'TXT_NEW_FILE_NAME'			=> 'Fichier/Dossier (nouveau)',
	'TXT_SUBMIT_RENAME'			=> 'Renommer',
	'TXT_RENAME_SUCCESS'		=> 'Fichier/dossier renomm&eacute; avec succ&egrave;s.',
	'TXT_RENAME_ERROR'			=> 'Impossible de renommer fichier/dossier. Veuillez v&eacute;rifier les permissions.',
	'TXT_ALLOWED_FILE_CHARS'	=> '[a-zA-Z0-9.-_]',
);

// Text outputs file handler (htt/action_handler_delete_file_folder.htt)
$LANG[6] = array(
	'TXT_ACTION_DELETE_FILE'	=> 'Supprimer fichier/dossier',
	'TXT_SUBMIT_DELETE'			=> 'Supprimer',
	'TXT_ACTUAL_FOLDER'			=> 'Dossier en cours',
	'TXT_DELETE_WARNING'		=> '<strong>Note: </strong>La suppression des fichiers et dossiers ne peut pas &ecirc;tre annul&eacute;s. N\'oubliez pas que lors ' .
								   'de la suppression d\'un dossier, tous les fichiers et sous-dossiers qu\'il contient seront supprim&eacute;s aussi.',
	'TXT_DELETE_SUCCESS'		=> 'Fichier/dossier supprim&eacute; avec succ&egrave;s.',
	'TXT_DELETE_ERROR'			=> 'Impossible de supprimer fichier/dossier. Veuillez v&eacute;rifier les permissions.<br />em>Note: pour supprimer un dossier ' .
								   'par FTP, assurez vous que le dossier ne contient par d\'autres dossiers ou fichiers.</em>'
);

// Text outputs file handler (htt/action_handler_create_file_folder.htt)
$LANG[7] = array(
	'TXT_ACTION_CREATE_FILE'	=> 'Cr&eacute;er fichier/dossier',
	'TXT_CREATE'				=> 'Cr&eacute;er',
	'TXT_FILE'					=> 'Fichier',
	'TXT_FOLDER'				=> 'Dossier',
	'TXT_FILE_NAME'				=> 'Nom de fichier',
	'TXT_ALLOWED_FILE_CHARS'	=> '[a-zA-Z0-9.-_]',
	'TXT_TARGET_FOLDER'			=> 'Dossier cible',
	'TXT_SUBMIT_CREATE'			=> 'Cr&eacute;er',
	'TXT_CREATE_SUCCESS'		=> 'Fichier/dossier Cr&eacute;&eacute; avec succ&egrave;s.',
	'TXT_CREATE_ERROR'			=> 'Impossible de cr&eacute;er fichier/dossier. Veuillez v&eacute;rifier les permissions et le nom de fichier sp&eacute;cifi&eacute;.',
);

// Text outputs file handler (htt/action_handler_upload_file.htt)
$LANG[8] = array(
	'TXT_ACTION_UPLOAD_FILE'	=> 'Uploader fichier',
	'TXT_SUBMIT_UPLOAD'			=> 'Upload',

	'TXT_FILE'					=> 'Fichier',
	'TXT_TARGET_FOLDER'			=> 'Dossier cible',

	'TXT_UPLOAD_SUCCESS'		=> 'Fichier upload&eacute; avec succ&egrave;s.',
	'TXT_UPLOAD_ERROR'			=> 'Impossible d\'uploader le fichier. Veuillez v&eacute;rifier les permissions et la taille du fichier sp&eacute;cifi&eacute;',
);

// Text outputs for the download handler
$LANG[9] = array(
	'ERR_TEMP_PERMISSION'		=> 'PHP n\'a pas les droits d\'&eacute;criture sur le dossier temporaire WB (/temp).',
	'ERR_ZIP_CREATION'			=> 'Impossible de cr&eacute;er l\'archive.',
	'ERR_ZIP_DOWNLOAD'			=> 'Erreur lors du t&eacute;l&eacute;chargement du fichier de sauvegarde.<br /><a href="{URL}">T&eacute;l&eacute;chargez</a> manuellement.',
);

// Text outputs for the FTP checking (htt/ftp_connection_check.htt)
$LANG[10] = array(
	'TXT_FTP_HEADING'			=> 'Assistant de Configuration FTP',
	'TXT_FTP_DESCRIPTION'		=> 'L\'assistant FTP aide &agrave; la mise en place au support FTP pour Addon File Editor.',

	'TXT_FTP_SETTINGS'			=> 'R&eacute;glages FTP actuels',
	'TXT_FTP_SUPPORT'			=> 'Support FTP',
	'TXT_ENABLE'				=> 'Activ&eacute;',
	'TXT_DISABLE'				=> 'D&eacute;sactiv&eacute;',
	'TXT_FTP_SERVER'			=> 'Serveur',
	'TXT_FTP_USER'				=> 'Utilisateur',
	'TXT_FTP_PASSWORD'			=> 'Mot de passe',
	'TXT_FTP_PORT'				=> 'Port',
	'TXT_FTP_START_DIR'			=> 'Dossier de d&eacute;part',

	'TXT_FTP_CONNECTION_TEST'	=> 'V&eacute;rifier la Connexion FTP',
	'TXT_CHECK_FTP_CONNECTION'	=> 'Cliquez sur le bouton ci-dessous pour v&eacute;rifier la connexion &agrave; votre serveur FTP.',
	'TXT_FTP_CHECK_NOTE'		=> '<strong>Note: </strong>Le test de connexion peut prendre jusqu\'&agrave; 15 secondes.',
	'TXT_SUBMIT_CHECK'			=> 'Connecter',
	'TXT_FTP_LOGIN_OK'			=> 'La connexion au serveur FTP &agrave; r&eacute;ussie. Le support FTP est activ&eacute;.',
	'TXT_FTP_LOGIN_FAILED'		=> 'La connexion au serveur FTP &agrave; &eacute;chou&eacute;e. Veuillez v&eacute;rifier vos r&eacute;glages FTP. ' .
								   '<br /><strong>Note: </strong>Le dossier de d&eacute;part doit pointer vers votre installation WB.',
);

?>