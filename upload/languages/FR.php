<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */


// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {	
	include(LEPTON_PATH.'/framework/class.secure.php'); 
} else {
	$oneback = "../";
	$root = $oneback;
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= $oneback;
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php



// Define that this file is loaded
if(!defined("LANGUAGE_LOADED")) {
	define("LANGUAGE_LOADED", true);
}

// Set the language information
$language_directory = "FR";
$language_code = "fr";
$language_name = "Française";
$language_version = "2.3";
$language_platform = "3.x";
$language_author = "Frédéric Bonain";
$language_license = "GNU General Public License";
$language_guid = "32E0F6E0-2FA3-4033-9F9D-77E0EA3B4745";

$MENU = array(
	"ACCESS" 				=> "Accès",
	"ADDON" 				=> "Module",
	"ADDONS" 				=> "Modules",
	"ADMINTOOLS" 			=> "Outils d'administration",
	"BREADCRUMB" 			=> "Vous êtes ici: ",
	"FORGOT" 				=> "Retrouver vos identifiants de connexion",
	"GROUP" 				=> "Groupe",
	"GROUPS" 				=> "Groupes",
	"HELP" 					=> "Aide",
	"LANGUAGES" 			=> "Languages",
	"LOGIN" 				=> "Connexion",
	"LOGOUT" 				=> "Déconnexion",
	"MEDIA" 				=> "Media",
	"MODULES" 				=> "Modules",
	"PAGES" 				=> "Pages",
	"PREFERENCES" 			=> "Préférences",
	"SETTINGS" 				=> "Réglages",
	"START" 				=> "Accueil",
	"TEMPLATES" 			=> "Thèmes",
	"USERS" 				=> "Utilisateurs",
	"VIEW" 					=> "Voir le site",
	"SERVICE"				=> "Services"
); // $MENU

$TEXT = array(
	"ACCOUNT_SIGNUP" 		=> "Créer un compte",
	"ACTION_NOT_SUPPORTED"	=> "Action not supported",
	"ACTIONS" 				=> "Actions",
	"ACTIVE" 				=> "Actif",
	"ADD" 					=> "Ajouter",
	"ADDON" 				=> "Module",
	"ADD_SECTION" 			=> "Ajouter une section",
	"ADMIN" 				=> "Admin",
	"ADMINISTRATION" 		=> "Administration",
	"ADMINISTRATION_TOOL" 	=> "Outils d'Administration",
	"ADMINISTRATOR" 		=> "Administrateur",
	"ADMINISTRATORS" 		=> "Administrateurs",
	"ADVANCED" 				=> "Avancé",
	"ALLOWED_FILETYPES_ON_UPLOAD" => "Fichiers autorisés à l'upload",
	"ALLOWED_VIEWERS" 		=> "Visiteurs autorisés",
	"ALLOW_MULTIPLE_SELECTIONS" => "Autoriser la sélection multiple",
	"ALL_WORDS" 			=> "Tous les mots",
	"ANCHOR" 				=> "Ancre",
	"ANONYMOUS" 			=> "Anonyme",
	"ANY_WORDS" 			=> "Chaque mot",
	"APP_NAME" 				=> "Nom de l'application",
	"ARE_YOU_SURE" 			=> "Etes-vous sûr ?",
	"AUTHOR" 				=> "Auteur",
	"BACK" 					=> "Retour",
	"BACKUP" 				=> "Sauvegarde",
	"BACKUP_ALL_TABLES" 	=> "Sauvegarder toutes les tables de la base de données",
	"BACKUP_DATABASE" 		=> "Sauvegarde de la base de données",
	"BACKUP_MEDIA" 			=> "Sauvegarde des fichiers media",
	"BACKUP_WB_SPECIFIC" 	=> "Sauvegarder uniquement les tables liées à LEPTON",
	"BASIC" 				=> "Classique",
	"BLOCK" 				=> "Bloc",
	"BACKEND_TITLE"	=>	"Backendtitle",
	"CALENDAR" 				=> "Calendrier",
	"CANCEL" 				=> "Annuler",
	"CAN_DELETE_HIMSELF" 	=> "Peut se supprimer",
	"CAPTCHA_VERIFICATION" 	=> "Vérification par captcha",
	"CAP_EDIT_CSS" 			=> "Editer la CSS",
	"CHANGE" 				=> "Changer",
	"CHANGES" 				=> "Modifications",
	"CHANGE_SETTINGS" 		=> "Modifier les réglages",
	"CHARSET" 				=> "Charset",
	"CHECKBOX_GROUP" 		=> "Groupe de checkbox",
	"CLOSE" 				=> "Fermer",
	"CODE" 					=> "Code",
	"CODE_SNIPPET" 			=> "Code-snippet",
	"COLLAPSE" 				=> "Contracter",
	"COMMENT" 				=> "Commentaire",
	"COMMENTING" 			=> "Commentaire en cours",
	"COMMENTS" 				=> "Commentaires",
	"CREATE_FOLDER" 		=> "Créer un dossier",
	"CURRENT" 				=> "Courant",
	"CURRENT_FOLDER" 		=> "Dossier courant",
	"CURRENT_PAGE" 			=> "Page courante",
	"CURRENT_PASSWORD" 		=> "Mot de passe actuel",
	"CUSTOM" 				=> "Adapter",
	"DATABASE" 				=> "Base de données",
	"DATE" 					=> "Date",
	"DATE_FORMAT" 			=> "Format de la date ",
	"DEFAULT" 				=> "Défaut",
	"DEFAULT_CHARSET" 		=> "Défaut Charset",
	"DEFAULT_TEXT" 			=> "Texte par défaut",
	"DELETE" 				=> "Supprimer",
	"DELETED" 				=> "Supprimé",
	"DELETE_DATE" 			=> "Supprimé date",
	"DELETE_ZIP" 			=> "Effacer l'archive zip après décompression",
	"DESCRIPTION" 			=> "Déscription",
	"DESIGNED_FOR" 			=> "Créé par",
	"DIRECTORIES" 			=> "Répertoires",
	"DIRECTORY_MODE" 		=> "Propriétés des répertoires",
	"DISABLED" 				=> "Désactivé",
	"DISPLAY_NAME" 			=> "Afficher le nom",
	"EMAIL" 				=> "Email",
	"EMAIL_ADDRESS" 		=> "Adresse email",
	"EMPTY_TRASH" 			=> "Vider la corbeille",
	"ENABLE_JAVASCRIPT"		=> "S'il vous plaît activer votre javascript pour utiliser ce formulaire.",
	"ENABLED" 				=> "Activé",
	"END" 					=> "Fin",
	"ERROR" 				=> "Erreur",
	"EXACT_MATCH" 			=> "Terme exact",
	"EXECUTE" 				=> "Executer",
	"EXPAND" 				=> "Déployer",
	"EXTENSION" 			=> "Extension",
	"FIELD" 				=> "Champs",
	"FILE" 					=> "Fichier",
	"FILES" 				=> "Fichiers",
	"FILESYSTEM_PERMISSIONS" => "Permissions des fichiers système",
	"FILE_MODE" 			=> "Propriétés des fichiers",
	"FINISH_PUBLISHING" 	=> "Fin de publication",
	"FOLDER" 				=> "Dossier",
	"FOLDERS" 				=> "Dossiers",
	"FOOTER" 				=> "Pied de page",
	"FORGOTTEN_DETAILS" 	=> "Identifiants oubliés?",
	"FORGOT_DETAILS" 		=> "Identifiants oubliés ?",
	"FROM" 					=> "De",
	"FRONTEND" 				=> "Page d'accueil",
	"FULL_NAME" 			=> "Nom complet",
	"FUNCTION" 				=> "Fonction",
	"GROUP" 				=> "Groupe",
	"HEADER" 				=> "Entête",
	"HEADING" 				=> "Haut de page",
	"HEADING_CSS_FILE" 		=> "Feuille css actuelle:",
	"HEIGHT" 				=> "Hauteur",
	"HELP_LEPTOKEN_LIFETIME"		=> "en secondes, 0 signifie pas de protection CSRF!",
	"HELP_MAX_ATTEMPTS"		=> "En atteignant ce nombre, plus aucune tentatives de connexion ne sont possibles pour cette session.",
	"HIDDEN" 				=> "Caché",
	"HIDE" 					=> "Cacher",
	"HIDE_ADVANCED" 		=> "Cacher les options avancées",
	"HOME" 					=> "Accueil",
	"HOMEPAGE_REDIRECTION" 	=> "Redirection de la page d'accueil",
	"HOME_FOLDER" 			=> "Répertoire de départ",
	"HOME_FOLDERS" 			=> "Répertoires de départ",
	"HOST" 					=> "Hôte",
	"ICON" 					=> "Icone",
	"IMAGE" 				=> "Image",
	"INLINE" 				=> "En ligne",
	"INSTALL" 				=> "Installer",
	"INSTALLATION" 			=> "Installation",
	"INSTALLATION_PATH" 	=> "Chemin d'installation",
	"INSTALLATION_URL" 		=> "Adresse d'installation (URL)",
	"INSTALLED" 			=> "installé",
	"INTRO" 				=> "Intro",
	"INTRO_PAGE" 			=> "Page d'installation",
	"INVALID_SIGNS" 		=> "doit commencer par une lettre ou contient des signes invalides",
	"KEYWORDS" 				=> "Mots-clés",
	"LANGUAGE" 				=> "Language",
	"LAST_UPDATED_BY" 		=> "Dernière mise à jour par",
	"LENGTH" 				=> "Longueur",
	"LEPTOKEN_LIFETIME"		=> "LEPTOKEN Lifetime",
	"LEVEL" 				=> "Niveau",
	"LIBRARY"				=> "Bibliothèque",
	"LICENSE"				=> "License",
	"LINK" 					=> "Lien",
	"LINUX_UNIX_BASED" 		=> "Basé sur linux/unix",
	"LIST_OPTIONS" 			=> "Liste des options",
	"LOGGED_IN" 			=> "Connecté",
	"LOGIN" 				=> "Connexion",
	"LONG" 					=> "Long",
	"LONG_TEXT" 			=> "Long Texte",
	"LOOP" 					=> "Boucle",
	"MAIN" 					=> "Principal",
	"MANAGE" 				=> "Gérer",
	"MANAGE_GROUPS" 		=> "Gestion des groupes",
	"MANAGE_USERS" 			=> "Gestion des utilisateurs",
	"MATCH" 				=> "Correspond",
	"MATCHING" 				=> "Correspondance",
	"MAX_ATTEMPTS"			=> "Tentatives de connexion erronées autorisées",
	"MAX_EXCERPT" 			=> "Nombre maximum de ligne à retourner",
	"MAX_SUBMISSIONS_PER_HOUR" => "Maximum de soumissions par heure",
	"MEDIA_DIRECTORY" 		=> "Répertoire des medias",
	"MENU" 					=> "Menu",
	"MENU_ICON_0" 			=> "Menu-Icone normal",
	"MENU_ICON_1" 			=> "Menu-Icone hover",
	"MENU_TITLE" 			=> "Titre du menu",
	"MESSAGE" 				=> "Message",
	"MODIFY" 				=> "Modifier",
	"MODIFY_CONTENT" 		=> "Modifier le contenu",
	"MODIFY_SETTINGS" 		=> "Modifier les réglages",
	"MODULE_ORDER" 			=> "Ordre de recherche dans les modules",
	"MODULE_PERMISSIONS" 	=> "Permissions sur les modules",
	"MORE" 					=> "Plus",
	"MOVE_DOWN" 			=> "Déplacer vers le bas",
	"MOVE_UP" 				=> "Déplacer vers le haut",
	"MULTIPLE_MENUS" 		=> "Menus multiples",
	"MULTISELECT" 			=> "Multi-sélection",
	"NAME" 					=> "Nom",
	"NEED_CURRENT_PASSWORD" => "confirmer avec le mot de passe actuel",
	"NEED_PASSWORD_TO_CONFIRM" => "S'il vous plaît confirmer les modifications avec votre mot de passe actuel",
	"NEED_TO_LOGIN" 		=> "Identification obligatoire",
	"NEW_PASSWORD" 			=> "Nouveau mot de passe",
	"NEW_USER_HINT"			=> "Minimum length for user name: %d chars, Minimum length for Password: %d chars!",
	"NEW_USER_HINT"			=> "Longueur minimale pour nom d'utilisateur: %d caractères, la longueur minimale pour le mot de passe: %d caractères!",
	"NEW_WINDOW" 			=> "Nouvelle fenêtre",
	"NEXT" 					=> "Suivant",
	"NEXT_PAGE" 			=> "Page suivante",
	"NO" 					=> "Non",
	"NO_LEPTON_ADDON"		=> "Ce module ne peut pas être utilisé avec LEPTON cms",
	"NONE" 					=> "Aucun",
	"NONE_FOUND" 			=> "Aucune occurence trouvée",
	"NOT_FOUND" 			=> "Introuvable",
	"NOT_INSTALLED" 		=> "non installé",
	"NO_RESULTS" 			=> "Aucun résultat",
	"OF" 					=> "De",
	"ON" 					=> "Sur",
	"OPEN" 					=> "Ouvert",
	"OPTION" 				=> "Option",
	"OTHERS" 				=> "Autres",
	"OUT_OF" 				=> "Hors de",
	"OVERWRITE_EXISTING" 	=> "Ecraser les données (si déjà existantes)",
	"PAGE" 					=> "Page",
	"PAGES_DIRECTORY" 		=> "Répertoire des pages",
	"PAGES_PERMISSION" 		=> "Permission de la pages",
	"PAGES_PERMISSIONS" 	=> "Permission des pages",
	"PAGE_EXTENSION" 		=> "Extension des pages",
	"PAGE_ICON" 			=> "Image de la page",//or "icone de la page"
	"PAGE_ID"				=> "Page ID",
	"PAGE_LANGUAGES" 		=> "Langues des pages",
	"PAGE_LEVEL_LIMIT" 		=> "Limite de niveau de page",
	"PAGE_SPACER" 			=> "Espacement de page",
	"PAGE_TITLE" 			=> "Titre de la page",
	"PAGE_TRASH" 			=> "Corbeille pour les pages supprimées",
	"PARENT" 				=> "Parent",
	"PASSWORD" 				=> "Mot de passe",
	"PATH" 					=> "Chemin",
	"PHP_ERROR_LEVEL" 		=> "Niveau d'erreur PHP",
	"PLEASE_LOGIN" 			=> "Merci de vous identifier",
	"PLEASE_SELECT" 		=> "Sélectionnez",
	"POST" 					=> "Actualité",
	"POSTS_PER_PAGE" 		=> "Nombre d'actualité par page",
	"POST_FOOTER" 			=> "Pied de page de l'actualité",
	"POST_HEADER" 			=> "Entête de l'actualité",
	"PREVIOUS" 				=> "Précédent",
	"PREVIOUS_PAGE" 		=> "Page précédente",
	"PRIVATE" 				=> "Privée",
	"PRIVATE_VIEWERS" 		=> "Utilisateurs privés",
	"PROFILES_EDIT" 		=> "Editer le profil",
	"PUBLIC" 				=> "Publique",
	"PUBL_END_DATE" 		=> "Date de fin",
	"PUBL_START_DATE" 		=> "Date de début",
	"RADIO_BUTTON_GROUP" 	=> "Groupe de boutons radio",
	"READ" 					=> "Lire",
	"READ_MORE" 			=> "En savoir plus",
	"REDIRECT_AFTER" 		=> "Redirection après coup",
	"REGISTERED" 			=> "Enregistré",
	"REGISTERED_VIEWERS" 	=> "Utilisateurs enregistrés",
	"REGISTERED_CONTENT"	=> "Seuls les visiteurs inscrits sur ce site ont accès à ce contenu",
	"RELOAD" 				=> "Actualiser",
	"REMEMBER_ME" 			=> "Se souvenir de moi",
	"RENAME" 				=> "Renommer",
	"RENAME_FILES_ON_UPLOAD" => "Renommer les fichiers lors de l'upload",
	"REQUIRED" 				=> "Obligatoire",
	"REQUIREMENT" 			=> "Paramètres requis",
	"RESET" 				=> "Réinitialiser",
	"RESIZE" 				=> "Redimensionner",
	"RESIZE_IMAGE_TO" 		=> "Redimensionner l'image",
	"RESTORE" 				=> "Restaurer",
	"RESTORE_DATABASE" 		=> "Restauration de la base de données",
	"RESTORE_MEDIA" 		=> "Restauration des fichiers media",
	"RESULTS" 				=> "Résultats",
	"RESULTS_FOOTER" 		=> "Results Footer",
	"RESULTS_FOR" 			=> "Results For",
	"RESULTS_HEADER" 		=> "Entête du modèle de recherche",
	"RESULTS_LOOP" 			=> "Modèle d'affichage de la boucle de recherche",
	"RETYPE_NEW_PASSWORD" 	=> "Saisissez à nouveau votre nouveau mot de passe",
	"RETYPE_PASSWORD" 		=> "Saisissez à nouveau votre mot de passe",
	"SAME_WINDOW" 			=> "Même fenêtre",
	"SAVE" 					=> "Sauvegarder",
	"SEARCH" 				=> "Rechercher",
	"SEARCH_FOR"  => "Rechercher par",
	"SEARCHING" 			=> "Rechercher",
	"SECTION" 				=> "c",
	"SECTION_BLOCKS" 		=> "Bloc de rubrique",
	"SECTION_ID" => "Rubrique ID",
	"SEC_ANCHOR" 			=> "Section d'ancre",
	"SELECT_BOX" 			=> "Sélection des boîtes",
	"SEND_DETAILS" 			=> "Valider",
	"SEPARATE" 				=> "Séparer",
	"SEPERATOR" 			=> "Sépareur",
	"SERVER_EMAIL" 			=> "Serveur de mail",
	"SERVER_OPERATING_SYSTEM" => "Système d'exploitation du serveur",
	"SESSION_IDENTIFIER" 	=> "Identifiant de session",
	"SETTINGS" 				=> "Réglages",
	"SHORT" 				=> "Court",
	"SHORT_TEXT" 			=> "Court Text",
	"SHOW" 					=> "Afficher",
	"SHOW_ADVANCED" 		=> "Afficher les options avancées",
	"SIGNUP" 				=> "Créer un compte",
	"SIZE" 					=> "Taille",
	"SMART_LOGIN" 			=> "Identification rapide",
	"START" 				=> "Accueil",
	"START_PUBLISHING" 		=> "Début de publication",
	"SUBJECT" 				=> "Sujet",
	"SUBMISSIONS" 			=> "Soumissions",
	"SUBMISSIONS_STORED_IN_DATABASE" => "Enregistrement des soumissions dans la base de données",
	"SUBMISSION_ID" 		=> "Soumissions ID",
	"SUBMITTED" 			=> "Envoyé",
	"SUCCESS" 				=> "Opération réussie",
	"SYSTEM_DEFAULT" 		=> "Système par défaut",
	"SYSTEM_PERMISSIONS" 	=> "Permissions système",
	"TABLE_PREFIX" 			=> "Préfixe du nom des tables",
	"TARGET" 				=> "Cible",
	"TARGET_FOLDER" 		=> "Dossier de destination",
	"TEMPLATE" 				=> "Thèmes",
	"TEMPLATE_PERMISSIONS" 	=> "Permissions sur les thèmes",
	"TEXT" 					=> "Texte",
	"TEXTAREA" 				=> "Zone de texte",
	"TEXTFIELD" 			=> "Champ de texte",
	"THEME" 				=> "Thème graphique de l'interface d'administration",
	"TIME" 					=> "Heure",
	"TIMEZONE" 				=> "Fuseau horaire",
	"TIME_FORMAT" 			=> "Format de l'heure",
	"TIME_LIMIT" 			=> "Délai maximal de recherche par module",
	"TITLE" 				=> "Titre",
	"TO" 					=> "De",
	"TOP_FRAME" 			=> "Fenêtre actuelle complète (top frame)",
	"TRASH_EMPTIED" 		=> "Corbeille vidée",
	"TXT_EDIT_CSS_FILE" 	=> "Editer les styles CSS dans la zone de texte ci-dessous.",
	"TYPE" 					=> "Type",
	"UNINSTALL" 			=> "Désinstaller",
	"UNKNOWN" 				=> "Inconnu",
	"UNLIMITED" 			=> "Illimité",
	"UNZIP_FILE" 			=> "Uploader et décompresser l'archive zip",
	"UP" 					=> "Haut",
	"UPGRADE" 				=> "Mise à jour",
	"UPLOAD_FILES" 			=> "Uploader un/des fichier(s)",
	"URL" 					=> "URL",
	"USER" 					=> "Utilisateur",
	"USERNAME" 				=> "Nom d'utilisateur",
	"USERS_ACTIVE" 			=> "L'utilisateur est mis en actifs",
	"USERS_CAN_SELFDELETE" 	=> "L'utilisateur peut se supprimer",
	"USERS_CHANGE_SETTINGS" => "L'utilisateur peut changer ses réglages",
	"USERS_DELETED" 		=> "L'utilisateur est marqué comme supprimé",
	"USERS_FLAGS" 			=> "Drapeaux utilisateurs",
	"USERS_PROFILE_ALLOWED" => "L'utilisateur peut créer un profil étendu",
	"VERIFICATION" 			=> "Vérification",
	"VERSION" 				=> "Version",
	"VIEW" 					=> "Voir le site",
	"VIEW_DELETED_PAGES" 	=> "Voir les pages supprimées",
	"VIEW_DETAILS" 			=> "Voir les Détails",
	"VISIBILITY" 			=> "Visibilité",
	"MAILER_DEFAULT_SENDER_MAIL" => "Adresse d'expéditeur par défaut",
	"MAILER_DEFAULT_SENDER_NAME" => "Nom d'expéditeur par défaut",
	"MAILER_DEFAULT_SETTINGS_NOTICE" => "Merci d'indiquer un nom et une adresse d'expéditeur par défaut. Il est recommandé d'utiliser une adresse d'expéditeur de la forme : <strong>admin@yourdomain.com</strong>. Certains opérateurs de mail (comme <em>mail.com</em>) peuvent rejeter les mails dont l'adresse d'expéditeur est de la forme <em>name@mail.com</em> envoyés via un relai, c'est leur manière de lutter contre le spam.<br /><br />Les valeur par défaut sont uniquement utilisées si aucune autre valeur n'est spécifiée par WebsiteBaker. Si votre serveur supporte <acronym title='Simple mail transfer protocol'>SMTP</acronym>, vous pouvez utiliser cette option pour l'expédition d'emails.",
	"MAILER_FUNCTION" 	=> "Mécanisme d'envoi de mail",
	"MAILER_NOTICE" 		=> "<strong>Paramètres du serveur SMTP :</strong><br />Les paramètres ci-dessous sont uniquement requis si vous souhaitez envoyer des mails via <acronym title='Simple mail transfer protocol'>SMTP</acronym>. Si vous ne connaissez pas votre serveur SMTP ou si vous n'êtes pas sûr de la valeur des paramètres requis, conservez simplement le mécanisme par défaut : PHP MAIL.",
	"MAILER_PHP" 			=> "PHP MAIL",
	"MAILER_SEND_TESTMAIL" => "Envoyer un email de test",
	"MAILER_SMTP" 		=> "SMTP",
	"MAILER_SMTP_AUTH" 	=> "Authentification SMTP",
	"MAILER_SMTP_AUTH_NOTICE" => "n'utilisez l'authentification que si votre seveur SMTP ne l'exige",
	"MAILER_SMTP_HOST" 	=> "Hôte SMTP",
	"MAILER_SMTP_PASSWORD" => "mot de passe SMTP",
	"MAILER_SMTP_USERNAME" => "nom d'utilisateur SMTP",
  "MAILER_TESTMAIL_FAILED" => "L'email de test ne peut pas être envoyé! Vérifiez vos réglage s.v.p. !",
	"MAILER_TESTMAIL_SUCCESS" => "L'email de test a être envoyé avec succè. Vérifiez votre boite mail.",
  "MAILER_TESTMAIL_TEXT" => "Ceci est le mail de test: PHP Mailer est opérationnel",
	"WEBSITE" 				=> "Site internet",
	"WEBSITE_DESCRIPTION" 	=> "Description du site",
	"WEBSITE_FOOTER" 		=> "Pied de page du site",
	"WEBSITE_HEADER" 		=> "Entête du site",
	"WEBSITE_KEYWORDS" 		=> "Mots clés du site",
	"WEBSITE_TITLE" 		=> "Titre du site",
	"WELCOME_BACK" 			=> "Bienvenue",
	"WIDTH" 				=> "Largeur",
	"WINDOW" 				=> "Fenêtre",
	"WINDOWS" 				=> "Windows",
	"WORLD_WRITEABLE_FILE_PERMISSIONS" => "Permissions d'écriture sur fichier",
	"WRITE" 				=> "Ecrire",
	"WYSIWYG_EDITOR" 		=> "Editeur WYSIWYG",
	"WYSIWYG_STYLE"	 		=> "Style WYSIWYG",
	"YES" 					=> "Oui",
	"BASICS"	=> array(
		"day"		=> "Jour",		# day, singular
		"day_pl"	=> "Jours",		# day, plural
		"hour"		=> "heure", 		# hour, singular
		"hour_pl"	=> "heures",		# hour, plural
		"minute"	=> "minute",	# minute, singular
		"minute_pl"	=> "minutes",	# minute, plural
	)
); // $TEXT

$HEADING = array(
	"ADDON_PRECHECK_FAILED" => "Echec de l'installation de l'extension. Votre système ne respecte pas les pré-requis de cette extension. Pour la faire fonctionner, merci de solutionner les erreurs listées ci-dessous.",
	"ADD_CHILD_PAGE" 		=> "Ajouter une page enfant",
	"ADD_GROUP" 			=> "Ajouter un groupe",
	"ADD_GROUPS" 			=> "Ajouter des groupes",
	"ADD_HEADING" 			=> "Ajouter un entête",
	"ADD_PAGE" 				=> "Ajouter une page",
	"ADD_USER" 				=> "Ajouter un utilisateur",
	"ADMINISTRATION_TOOLS" 	=> "Outils d'administration",
	"BROWSE_MEDIA" 			=> "Parcourir le dossier media",
	"CREATE_FOLDER" 		=> "Créer un dossier",
	"DEFAULT_SETTINGS" 		=> "Réglages par défaut",
	"DELETED_PAGES" 		=> "Pages effacées",
	"FILESYSTEM_SETTINGS" 	=> "Réglages des fichiers systèmes",
	"GENERAL_SETTINGS" 		=> "Réglages",
	"INSTALL_LANGUAGE" 		=> "Installer une langue",
	"INSTALL_MODULE" 		=> "Installer un module",
	"INSTALL_TEMPLATE" 		=> "Installer un thème",
	"INVOKE_MODULE_FILES" 	=> "Exécuter manuellement les fichiers module",
	"LANGUAGE_DETAILS" 		=> "Propriétés de la langue",
	"MANAGE_SECTIONS" 		=> "Gestion des rubriques",
	"MODIFY_ADVANCED_PAGE_SETTINGS" => "Modifier les propriétés avancées de la page",
	"MODIFY_DELETE_GROUP" 	=> "Modifier/Supprimer un groupe",
	"MODIFY_DELETE_PAGE" 	=> "Modifier/Supprimer une page",
	"MODIFY_DELETE_USER" 	=> "Modifier/Supprimer un utilisateur",
	"MODIFY_GROUP" 			=> "Modifier un groupe",
	"MODIFY_GROUPS" 		=> "Modifier des groupes",
	"MODIFY_INTRO_PAGE" 	=> "Modifier la page d'accueil",
	"MODIFY_PAGE" 			=> "Modifier une page",
	"MODIFY_PAGE_SETTINGS" 	=> "Modifier les propriétés de la page",
	"MODIFY_USER" 			=> "Modifier un utilisateur",
	"MODULE_DETAILS" 		=> "Propriétés du module",
	"MY_EMAIL" 				=> "Mon email",
	"MY_PASSWORD" 			=> "Mon mot de passe",
	"MY_SETTINGS" 			=> "Mes préférences",
	"SEARCH_SETTINGS" 		=> "Réglages de la recherche",
	"SEARCH_PAGE" 			=> "Search Page",
	"SECURITY_SETTINGS"		=> "Réglages de la sécurité",
	"SERVER_SETTINGS" 		=> "Réglages du serveur",
	"TEMPLATE_DETAILS" 		=> "Propriétés du thème",
	"UNINSTALL_LANGUAGE" 	=> "Désinstaller une langue",
	"UNINSTALL_MODULE" 		=> "Désinstaller un Module",
	"UNINSTALL_TEMPLATE" 	=> "Désinstaller un thème",
	"UPGRADE_LANGUAGE" 		=> "Language enregistré/mis à jour",
	"UPLOAD_FILES" 			=> "Uploader un/des fichier(s)",
	"VISIBILITY" 			=> "Visibilité",
	"MAILER_SETTINGS" 	=> "Réglages de l'envoi de mail"
); // $HEADING

$MESSAGE = array(
	"ADDON_ERROR_RELOAD" 	=> "Erreur pendant la mise à jour des informations des modules.",
	"ADDON_GROUPS_MARKALL" => "Cocher / Décocher tous",
	"ADDON_LANGUAGES_RELOADED" => "Langues rechargé avec succès",
	"ADDON_MANUAL_FTP_LANGUAGE" => "<strong>ATTENTION!</strong> pour des résons de sécurités, envoyez vos fichiers de langues par FTP et utilisez la fonction 'mise à jour' pour faire les changements.",
	"ADDON_MANUAL_FTP_WARNING" => "Attention: les entrées existantes du module seront perdues. ",
	"ADDON_MANUAL_INSTALLATION" => "Quand les extensions sont uploadés via ftp (ce qui n'est pas recommandé), les fichiers d'installation du module <tt>install.php</tt>, <tt>upgrade.php</tt> ou <tt>uninstall.php</tt> ne seront pas exécutés automatiquement. Ces modules peuvent ne pas fonctionner ou ne pas se désinstaller correctement.<br /><br />Vous pouvez exécuter les fichiers d'extension manuellement pour les extensions uploadées via ftp ci-dessous.",
	"ADDON_MANUAL_INSTALLATION_WARNING" => "Attention: les données de la base de données de l'extension existante vont être perdues. Utilisez cette option si vous rencontrez des problèmes avec des modules uploadés via ftp.",
	"ADDON_MANUAL_RELOAD_WARNING" => "Attention: les données de la base de données de l'extension existante vont être perdues. ",
	"ADDON_MODULES_RELOADED" => "Modules rechargé avec succès",
	"ADDON_PRECHECK_FAILED" => "Echec de l'installation de l'extension. Votre système ne respecte pas les pré-requis de cette extension. Pour la faire fonctionner, merci de solutionner les erreurs listées ci-dessous.",
	"ADDON_RELOAD" 			=> "Mise à jour de la base de données avec les informations des extensions (ou après l'upload via ftp).",
	"ADDON_TEMPLATES_RELOADED" => "Les thèmes ont été correctement rechargés",
	"ADMIN_INSUFFICIENT_PRIVELLIGES" => "Droits insuffisants pour être ici",
	"FORGOT_PASS_ALREADY_RESET" => "Désolé, vous ne pouvez pas modifier votre mot de passe plus d'une fois par heure",
	"FORGOT_PASS_CANNOT_EMAIL" => "Impossible de vous renvoyer vos identifiants, merci de contacter l'administrateur du site",
	"FORGOT_PASS_EMAIL_NOT_FOUND" => "L'adresse email que vous avez saisi est introuvable dans la base de données",
	"FORGOT_PASS_NO_DATA" 	=> "Merci de saisir votre adresse email",
	"FORGOT_PASS_PASSWORD_RESET" => "Vos identifiants vous ont été envoyé par email",
	"FORGOT_CONFIRM_OLD" 	=> "Sorry, you are too late, link is disabled",	
	"FORGOT_PASS_PASSWORD_CONFIRM" => "You want to reset your password. Please use this link to enter your new password.<br /> If you do not want to reset your password please ignore this mail.<br /><br /><a href='%s'>%s</a><br /><br /> Thanks",	
	"FORGOT_PASSWORD_SUCCESS" 	=> "Hello %s,<br /><br />just for your information:<br /><br />your password has been successfully modified.",	
	"FRONTEND_SORRY_NO_ACTIVE_SECTIONS" => "Désolé, aucun contenu actif à afficher",
	"FRONTEND_SORRY_NO_VIEWING_PERMISSIONS" => "Désolé, vous n'avez pas les droits pour visualiser cette page",
	"GENERIC_ALREADY_INSTALLED" => "Déjà installé",
	"GENERIC_BAD_PERMISSIONS" => "Impossible d'écrire dans le répertoire cible",
	"GENERIC_CANNOT_UNINSTALL" => "Impossible de désinstaller",
	"GENERIC_CANNOT_UNINSTALL_IN_USE" => "Désinstallation impossible : fichier en cours d'utilisation",
	"GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL" => "<br /><br />{{type}} <b>{{type_name}}</b> ne peut pas être dÈinstallé car il est actuellement en cours d'utilisation dans les pages {{pages}}.<br /><br />",
	"GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES" => "cette page; ces pages",
	"GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE" => "Impossible de désinstaller le modèle <b>{{name}}</b> parce que c'est le modèle par défaut !",
	"GENERIC_CANNOT_UNZIP" 	=> "Impossible de dézipper le fichier",
	"GENERIC_CANNOT_UPLOAD" => "Impossible d'uploader le fichier",
	"GENERIC_COMPARE" 		=> " avec succès",
	"GENERIC_ERROR_OPENING_FILE" => "Erreur lors de l'ouverture du fichier.",
	"GENERIC_FAILED_COMPARE" => " échoué",
	"GENERIC_FILE_TYPE" 	=> "Les fichiers chargés doivent avoir les extensions suivantes: ",
	"GENERIC_FILE_TYPES" 	=> "Les fichiers chargés doivent être aux formats suivants: ",
	"GENERIC_FILL_IN_ALL" 	=> "Merci de remplir tous les champs",
	"GENERIC_INSTALLED" 	=> "Installation réussie",
	"GENERIC_INVALID" 		=> "Le fichier chargé est invalide",
	"GENERIC_INVALID_ADDON_FILE" => "Fichier d'extension incorrect. Vérifiez le fichier zip.",
	"GENERIC_INVALID_LANGUAGE_FILE" => "Fichier de langue incorrect. Vérifiez le fichier de langue.",
	"GENERIC_IN_USE" 		=> " mais utilisé dans ",
	"GENERIC_MODULE_VERSION_ERROR" => "Le module n'est pas installé correctement!",
	"GENERIC_NOT_COMPARE" 	=> " pas possible",
	"GENERIC_NOT_INSTALLED" => "Pas installé",
	"GENERIC_NOT_UPGRADED" 	=> "Actualisation impossible",
	"GENERIC_PLEASE_BE_PATIENT" => "S'il vous plaît soyez patient, cela pourrait prendre un certain temps.",
	"GENERIC_PLEASE_CHECK_BACK_SOON" => "Merci de revenir plus tard...",
	"GENERIC_SECURITY_ACCESS"	=> "Atteinte à la sécurité! accès refusé",
	"GENERIC_SECURITY_OFFENSE"	=> "Atteinte à la sécurité! le stockage de données a été refusée!!",
	"GENERIC_UNINSTALLED" 	=> "Désinstallation réussie",
	"GENERIC_UPGRADED" 		=> "Mise à jour réussie",
	"GENERIC_VERSION_COMPARE" => "Comparaison de versions",
	"GENERIC_VERSION_GT" 	=> "Mise à jour nécessaires!",
	"GENERIC_VERSION_LT" 	=> "Rétrograder",
	"GENERIC_WEBSITE_UNDER_CONSTRUCTION" => "Site en construction",
	"GROUPS_ADDED" 			=> "Groupe ajouté avec succès",
	"GROUPS_CONFIRM_DELETE" => "Etes-vous sûr de vouloir supprimer ce groupe (ainsi que tous les utilisateurs de ce groupe) ?",
	"GROUPS_DELETED" => "Groupe supprimé avec succès",
	"GROUPS_GROUP_NAME_BLANK" => "Le nom du groupe est vide",
	"GROUPS_GROUP_NAME_EXISTS" => "Le nom du groupe est déja existant",
	"GROUPS_NO_GROUPS_FOUND" => "Groupe introuvable",
	"GROUPS_SAVED" 			=> "Groupe sauvegardé avec succès",
	"LANG_MISSING_PARTS_NOTICE" => "Fichier de langue incorrect. Vérifiez le fichier de langue. Une ou plusieurs variables sont manquantes:<br />language_code<br />language_name<br />language_version<br />language_license",
	"LOGIN_AUTHENTICATION_FAILED" => "Votre nom d'utilisateur ou votre mot de passe est incorrect",
	"LOGIN_BOTH_BLANK" 		=> "Merci de saisir vos identifiants de connexion",
	"LOGIN_PASSWORD_BLANK" 	=> "Merci de saisir votre mot de passe",
	"LOGIN_PASSWORD_TOO_LONG" => "Votre mot de passe est trop long",
	"LOGIN_PASSWORD_TOO_SHORT" => "Votre mot de passe est trop court",
	"LOGIN_USERNAME_BLANK" 	=> "Merci de saisir votre nom d'utilisateur",
	"LOGIN_USERNAME_TOO_LONG" => "Votre nom d'utilisateur est trop long",
	"LOGIN_USERNAME_TOO_SHORT" => "Votre nom d'utilisateur est trop court",
	"MEDIA_BLANK_EXTENSION" => "Vous n'avez pas entré d'extension",
	"MEDIA_BLANK_NAME" 		=> "Vous n'avezpas entré de nouveau nom",
	"MEDIA_CANNOT_DELETE_DIR" => "Impossible de supprimer le dossier sélctionné",
	"MEDIA_CANNOT_DELETE_FILE" => "Impossible de supprimer le fichier sélectionné",
	"MEDIA_CANNOT_RENAME" 	=> "Impossible de renommer",
	"MEDIA_CONFIRM_DELETE" 	=> "Etes-vous sûr de vouloir supprimer ce fichier ou dossier ?",
	"MEDIA_CONFIRM_DELETE_FILE"	=> "Etes-vous sûr de vouloir supprimer le fichier {name}?",
	"MEDIA_CONFIRM_DELETE_DIR"	=> "Etes-vous sûr de vouloir supprimer le dossier {name}?",
	"MEDIA_DELETED_DIR" 	=> "Dossier supprimé avec succès",
	"MEDIA_DELETED_FILE" 	=> "Fichier supprimé avec succès",
	"MEDIA_DIR_ACCESS_DENIED" => "Répertoire spécifié n'existe pas ou n'est pas autorisé.",
	"MEDIA_DIR_DOES_NOT_EXIST" => "Le répertoire n'existe pas",
	"MEDIA_DIR_DOT_DOT_SLASH" => "Impossible d'inclure ../ dans le nom du dossier",
	"MEDIA_DIR_EXISTS" 		=> "Un dossier portant ce nom est déjà existant",
	"MEDIA_DIR_MADE" 		=> "Dossier créé avec succès",
	"MEDIA_DIR_NOT_MADE" 	=> "Impossible de créer le dossier",
	"MEDIA_FILE_EXISTS" 	=> "Un fichier portant ce nom est déjà existant",
	"MEDIA_FILE_NOT_FOUND" 	=> "Fichier introuvable",
	"MEDIA_NAME_DOT_DOT_SLASH" => "Impossible d'inclure ../ dans le nom",
	"MEDIA_NAME_INDEX_PHP" 	=> "Vous ne pouvez pas utiliser index.php comme nom",
	"MEDIA_NONE_FOUND" 		=> "Aucun media trouvé dans ce dossier",
	"MEDIA_RENAMED" 		=> "Renommage réussi avec succès",
	"MEDIA_SINGLE_UPLOADED" => " Le fichier a été uploadé avec succès",
	"MEDIA_TARGET_DOT_DOT_SLASH" => "Impossible d'avoir ../ dans le nom du dossier cible",
	"MEDIA_UPLOADED" 		=> " Les fichiers ont été uploadés avec succès",
	"MOD_MISSING_PARTS_NOTICE" => "L'installation du module '%s' à échoué, une ou plusieurs variables  sont manquantes:<br />module_directory<br />module_name<br />module_version<br />module_author<br />module_license<br />module_guid<br />module_function",
	"MOD_FORM_EXCESS_SUBMISSIONS" => "Désolé mais ce formulaire est utilisé trop fréquemment en ce moment. Merci de réessayer plus tard",
	"MOD_FORM_INCORRECT_CAPTCHA" => "Le numéro de vérification (Captcha) que vous avez entré est incorrect. Si vous rencontrez des problèmes quant à la lecture de ce numéro, merci d'envoyer un email à : <a href='mailto:".SERVER_EMAIL."'>'.SERVER_EMAIL.'</a>",
	"MOD_FORM_REQUIRED_FIELDS" => "Vous devez renseigner les champs suivants",
	"PAGES_ADDED" 			=> "Page ajoutée avec succès",
	"PAGES_ADDED_HEADING" 	=> "L'entête de la page a été ajouté avec succès",
	"PAGES_BLANK_MENU_TITLE" => "Entrez un titre de menu",
	"PAGES_BLANK_PAGE_TITLE" => "Entrez un titre de page",
	"PAGES_CANNOT_CREATE_ACCESS_FILE" => "Erreur lors de la création d'un fichier dans le répertoire des pages (privilèges insuffisants)",
	"PAGES_CANNOT_DELETE_ACCESS_FILE" => "Erreur lors de la suppression d'un fichier dans le répertoire des pages (privilèges insuffisants)",
	"PAGES_CANNOT_REORDER" 	=> "Erreur lors du réagencement des pages",
	"PAGES_DELETED" 		=> "Page supprimée avec succès",
	"PAGES_DELETE_CONFIRM" 	=> "Etes-vous sûr de vouloir supprimer la page sélectionnée (ainsi que ses sous-rubriques)",
	"PAGES_INSUFFICIENT_PERMISSIONS" => "Vous n'avez pas les droits pour modifier cette pages",
	"PAGES_DIRECTORY_EMPTY" 	=> "Please delete directory manually!",
	"PAGES_DIRECTORY_NEW" 		=> "Please create directory manually!",
	"PAGES_LAST_MODIFIED" 	=> "Dernière mise à jour par",
	"PAGES_NOT_FOUND" 		=> "Page introuvable",
	"PAGES_NOT_SAVED" 		=> "Erreur lors de la sauvegarde de la page",
	"PAGES_PAGE_EXISTS" 	=> "Une page avec le même nom existe déjà",
	"PAGES_REORDERED" 		=> "Page réordonnée avec succès",
	"PAGES_RESTORED" 		=> "Page restaurée avec succès",
	"PAGES_RETURN_TO_PAGES" => "Retour au contenu",
	"PAGES_SAVED" 			=> "Page sauvegardée avec succès",
	"PAGES_SAVED_SETTINGS" 	=> "Paramètres de la page sauvegardés avec succès",
	"PAGES_SECTIONS_PROPERTIES_SAVED" => "Les propriétés de la rubrique ont été sauvegardées avec succès",
	"PREFERENCES_CURRENT_PASSWORD_INCORRECT" => "Le mot de passe entré est incorrect",
	"PREFERENCES_DETAILS_SAVED" => "Données sauvegardées avec succès",
	"PREFERENCES_EMAIL_UPDATED" => "Adresse email sauvegardée avec succès",
	"PREFERENCES_INVALID_CHARS" => "Caractères invalides pour le mot de passe",
	"PREFERENCES_PASSWORD_CHANGED" => "Mot de passe modifié avec succès",
	"PREFERENCES_PASSWORD_MATCH" => "Passwords do not match",	
	"RECORD_MODIFIED_FAILED" => "Le changement de l'enregistrement a échoué.",
	"RECORD_MODIFIED_SAVED" => "Le changement de l'enregistrement a été modifié avec succès.",
	"RECORD_NEW_FAILED" 	=> "L'ajout de l'enregistrement a échoué.",
	"RECORD_NEW_SAVED" 		=> "Le nouvel enregistrement a été ajouté avec succès.",
	"SETTINGS_MODE_SWITCH_WARNING" => "Attention : en cliquant sur ce bouton, vous ne sauvegardez pas vos modifications",
	"SETTINGS_SAVED" 		=> "Réglages sauvegardés avec succès",
	"SETTINGS_UNABLE_OPEN_CONFIG" => "Impossible de lire le fichier de configuration",
	"SETTINGS_UNABLE_WRITE_CONFIG" => "Impossible d'écrire dans le fichier de configuration",
	"SETTINGS_WORLD_WRITEABLE_WARNING" => "Recommandé uniquement pour les environnement de test",
	"SIGNUP2_ADMIN_INFO" 	=> "
Un nouvel utilisateur a été enregistré.

Nom d'utilisateur: {LOGIN_NAME}
ID utilisateur: {LOGIN_ID}
E-Mail: {LOGIN_EMAIL}
Adresse IP : {LOGIN_IP}
Date d'enregistrement: {SIGNUP_DATE}
----------------------------------------
Ce message a été généré automatiquement!

",
	"SIGNUP2_ADMIN_SUBJECT" => "New user has signed up",
	"SIGNUP2_BODY_CONFIRM" => "
Hello {LOGIN_DISPLAY_NAME},

Signup is now complete.
Your account is active and you can login to \'{LOGIN_WEBSITE_TITLE}\'.

Regards
------------------------------------
This message was system built!

",
	"SIGNUP2_BODY_LOGIN_INFO" => "
Hello {LOGIN_DISPLAY_NAME},

You have registered on \'{LOGIN_WEBSITE_TITLE}\'.

To activate your account please use this link and enter a password.

<a href='{ENTER_PW_LINK}'>{ENTER_PW_LINK}</a>

Thank you

Please:
if you have received this message by an error, please delete it immediately!
-------------------------------------
This message was system built!
",
	"SIGNUP2_SUBJECT_LOGIN_INFO" =>	 "Paramètres de votre connexion ...",
	"SIGNUP_NO_EMAIL" 		=> "L'adresse email est obligatoire",
	"START_CURRENT_USER" 	=> "Vous êtes connecté en tant que: ",
	"START_INSTALL_DIR_EXISTS" => "Attention : le répertoire d'installation existe toujours!",
	"START_WELCOME_MESSAGE" => "Bienvenue dans la zone d'administration",
	"STATUSFLAG_32"			=> "Cannot delete User, User got statusflags 32 in table users.",	
	"SYSTEM_FUNCTION_DEPRECATED"=> "La fonction <b>%s</b> est obsolète, utilisez la fonction <b>%s</b> !",
	"SYSTEM_FUNCTION_NO_LONGER_SUPPORTED" => "La fonction <b>%s</b> est périmée  et n'est plus supporté!!",
	"SYSTEM_SETTING_NO_LONGER_SUPPORTED" => "Le réglage <b>%s</ b> n'est plus supporté et sera ignoré!",
	"TEMPLATES_CHANGE_TEMPLATE_NOTICE" => "Pour modifier le thème du site, vous devez vous rendre dans la rubrique Réglages",
	"TEMPLATES_MISSING_PARTS_NOTICE" => "L'installation du thème a échoué, un ou plusieurs variables suivantes sont manquantes:<br />template_directory<br />template_name<br />template_version<br />template_author<br />template_license<br />template_function ('theme' ou 'template')",
	"USERS_ADDED" 			=> "Utilisateur ajouté avec succès",
	"USERS_CANT_SELFDELETE" => "Fonction rejetée, vous ne pouvez pas vous supprimer!",
	"USERS_CHANGING_PASSWORD" => "Vous ne devez modifier les valeurs ci-dessus uniquement lors d'une modification de mot de passe",
	"USERS_CONFIRM_DELETE" 	=> "Etes-vous sûr de vouloir supprimer cet utilisateur?",
	"USERS_DELETED" 		=> "Utilisateur supprimé avec succès",
	"USERS_EMAIL_TAKEN" 	=> "L'adresse email est déja utilisée",
	"USERS_INVALID_EMAIL" 	=> "L'adresse email n'est pas valide",
	"USERS_NAME_INVALID_CHARS" => "Caractères invalides pour le nom d'utilisateur",
	"USERS_NO_GROUP" 		=> "Aucun groupe n'a été sélectionné",
	"USERS_PASSWORD_MISMATCH" => "Le mot de passe est introuvable",
	"USERS_PASSWORD_TOO_SHORT" => "Le mot de passe est trop court",
	"USERS_SAVED" 			=> "Utilisateur sauvegardé avec succès",
	"USERS_USERNAME_TAKEN" 	=> "Le nom d'utilisateur est déja utilisé",
	"USERS_USERNAME_TOO_SHORT" => "Le nom d'utilisateur est trop court"
); // $MESSAGE

$OVERVIEW = array(
	"ADMINTOOLS" 			=> "Accès aux outils d'administration de LEPTON...",
	"GROUPS" 				=> "Gestions des groupes d'utilisateurs et des permissions...",
	"HELP" 					=> "Aide et FAQ sur l'utilisation du site...",
	"LANGUAGES" 			=> "Gestion des langues du site...",
	"MEDIA" 				=> "Gestion des fichiers media (images, téléchargements...)",
	"MODULES" 				=> "Gestion des modules du site...",
	"PAGES" 				=> "Gestion du contenu du site...",
	"PREFERENCES" 			=> "Gestion de vos préférences (email, mot de passe...) ",
	"SETTINGS" 				=> "Configuration du site...",
	"START" 				=> "Présentation de l'administration",
	"TEMPLATES" 			=> "Gestion des thèmes et modification de l'apparence du site...",
	"USERS" 				=> "Gestion des accès au site...",
	"VIEW" 					=> "Aperçu du site dans une nouvelle fenêtre..."
);

?>