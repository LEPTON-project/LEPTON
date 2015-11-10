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
 * @copyright       2010-2015 LEPTON Project
 * @link            http://www.LEPTON-cms.org
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
if(!defined('LANGUAGE_LOADED')) {
	define('LANGUAGE_LOADED', true);
}

// Set the language information
$language_directory = 'DE';
$language_code = 'de';
$language_name = 'Deutsch';
$language_version = '2.1';
$language_platform = '2.x';
$language_author = 'Stefan Braunewell, Matthias Gallas, LEPTON project';
$language_license = 'GNU General Public License';
$language_guid = 'f49419c8-eb27-4a69-bffb-af61fce6b0c9';

$MENU = array(
	'ACCESS' 				=> 'Benutzerverwaltung',
	'ADDON' 				=> 'Add-on',
	'ADDONS' 				=> 'Erweiterungen',
	'ADMINTOOLS' 			=> 'Admin-Tools',
	'BREADCRUMB' 			=> 'Sie sind hier: ',
	'FORGOT' 				=> 'Anmelde-Daten anfordern',
	'GROUP' 				=> 'Group',
	'GROUPS' 				=> 'Gruppen',
	'HELP' 					=> 'Hilfe',
	'LANGUAGES' 			=> 'Sprachen',
	'LOGIN' 				=> 'Anmeldung',
	'LOGOUT' 				=> 'Abmelden',
	'MEDIA' 				=> 'Medien',
	'MODULES' 				=> 'Module',
	'PAGES' 				=> 'Seiten',
	'PREFERENCES' 			=> 'Einstellungen',
	'SETTINGS' 				=> 'Optionen',
	'START' 				=> 'Start',
	'TEMPLATES' 			=> 'Designvorlagen',
	'USERS' 				=> 'Benutzer',
	'VIEW' 					=> 'Ansicht',
	'SERVICE'				=> 'Service'
);

$TEXT = array(
	'ACCOUNT_SIGNUP' 		=> 'Registrierung',
	'ACTION_NOT_SUPPORTED'	=> 'Action not supported',
	'ACTIONS' 				=> 'Aktionen',
	'ACTIVE' 				=> 'Aktiv',
	'ADD' 					=> 'Hinzufügen',
	'ADDON' 				=> 'Addon',
	'ADD_SECTION' 			=> 'Abschnitt hinzufügen',
	'ADMIN' 				=> 'Admin',
	'ADMINISTRATION' 		=> 'Verwaltung',
	'ADMINISTRATION_TOOL' 	=> 'Verwaltungsprogramme',
	'ADMINISTRATOR' 		=> 'Administrator',
	'ADMINISTRATORS' 		=> 'Administratoren',
	'ADVANCED' 				=> 'Erweitert',
	'ALLOWED_FILETYPES_ON_UPLOAD' => 'Erlaubte Dateitypen für Hochladen',
	'ALLOWED_VIEWERS' 		=> 'genehmigte Besucher',
	'ALLOW_MULTIPLE_SELECTIONS' => 'Mehrfachauswahl',
	'ALL_WORDS' 			=> 'Alle W&ouml;rter',
	'ANCHOR' 				=> 'Anker',
	'ANONYMOUS' 			=> 'Anonym',
	'ANY_WORDS' 			=> 'Einzelne Worte',
	'APP_NAME' 				=> 'Verwaltungswerkzeuge',
	'ARE_YOU_SURE' 			=> 'Sind Sie sicher?',
	'AUTHOR' 				=> 'Autor',
	'BACK' 					=> 'Zurück',
	'BACKUP' 				=> 'Sichern',
	'BACKUP_ALL_TABLES' 	=> 'komplette Datenbank sichern',
	'BACKUP_DATABASE' 		=> 'Datenbank sichern',
	'BACKUP_MEDIA' 			=> 'Dateien sichern',
	'BACKUP_WB_SPECIFIC' 	=> 'nur Tabellen sichern',
	'BASIC' 				=> 'Einfach',
	'BLOCK' 				=> 'Block',
	'BACKEND_TITLE'			=> 'Backendtitel',
	'CALENDAR' 				=> 'Kalender',
	'CANCEL' 				=> 'Abbrechen',
	'CAN_DELETE_HIMSELF' 	=> 'Kann sich selber löschen',
	'CAPTCHA_VERIFICATION' 	=> 'Captcha Prüfziffer',
	'CAP_EDIT_CSS' 			=> 'Bearbeite CSS',
	'CHANGE' 				=> 'Ändern',
	'CHANGES' 				=> 'Änderungen',
	'CHANGE_SETTINGS' 		=> 'Einstellungen ändern',
	'CHARSET' 				=> 'Zeichensatz',
	'CHECKBOX_GROUP' 		=> 'Kontrollkästchen',
	'CLOSE' 				=> 'Schließen',
	'CODE' 					=> 'Code',
	'CODE_SNIPPET' 			=> 'Funktionserweiterung',
	'COLLAPSE' 				=> 'Reduzieren',
	'COMMENT' 				=> 'Kommentar',
	'COMMENTING' 			=> 'kommentieren',
	'COMMENTS' 				=> 'Kommentare',
	'CREATE_FOLDER' 		=> 'Ordner anlegen',
	'CURRENT' 				=> 'Aktuell',
	'CURRENT_FOLDER' 		=> 'Aktueller Ordner',
	'CURRENT_PAGE' 			=> 'Aktuelle Seite',
	'CURRENT_PASSWORD' 		=> 'Bisheriges Passwort',
	'CUSTOM' 				=> 'Benutzerdefiniert',
	'DATABASE' 				=> 'Datenbank',
	'DATE' 					=> 'Datum',
	'DATE_FORMAT' 			=> 'Datumsformat',
	'DEFAULT' 				=> 'Standard',
	'DEFAULT_CHARSET' 		=> 'Standard Zeichensatz',
	'DEFAULT_TEXT' 			=> 'Standardtext',
	'DELETE' 				=> 'Entfernen',
	'DELETED' 				=> 'Gelöscht',
	'DELETE_DATE' 			=> 'Datum löschen',
	'DELETE_ZIP' 			=> 'Zip-Archiv nach dem entpacken löschen',
	'DESCRIPTION' 			=> 'Beschreibung',
	'DESIGNED_FOR' 			=> 'Entworfen für',
	'DIRECTORIES' 			=> 'Verzeichnisse',
	'DIRECTORY_MODE' 		=> 'Verzeichnismodus',
	'DISABLED' 				=> 'Ausgeschaltet',
	'DISPLAY_NAME' 			=> 'Angezeigter Name',
	'EMAIL' 				=> 'E-Mail',
	'EMAIL_ADDRESS' 		=> 'E-Mail Adresse',
	'EMPTY_TRASH' 			=> 'Mülleimer leeren',
	'ENABLE_JAVASCRIPT'		=> "Bitte JavaScript einschalten.",
	'ENABLED' 				=> 'Eingeschaltet',
	'END' 					=> 'Ende',
	'ERROR' 				=> 'Fehler',
	'EXACT_MATCH' 			=> 'Genaue Wortfolge',
	'EXECUTE' 				=> 'Ausführen',
	'EXPAND' 				=> 'Erweitern',
	'EXTENSION' 			=> 'Extension',
	'FIELD' 				=> 'Feld',
	'FILE' 					=> 'Datei',
	'FILES' 				=> 'Dateien',
	'FILESYSTEM_PERMISSIONS' => 'Zugriffsrechte',
	'FILE_MODE' 			=> 'Dateimodus',
	'FINISH_PUBLISHING' 	=> 'Ende der Veröffentlichung',
	'FOLDER' 				=> 'Ordner',
	'FOLDERS' 				=> 'Ordner',
	'FOOTER' 				=> 'Fu&szlig;zeile',
	'FORGOTTEN_DETAILS' 	=> 'Haben Sie Ihre persönlichen Daten vergessen?',
	'FORGOT_DETAILS' 		=> 'Haben Sie Ihre persönlichen Daten vergessen?',
	'FROM' 					=> 'von',
	'FRONTEND' 				=> 'Frontend',
	'FULL_NAME' 			=> 'Voller Name',
	'FUNCTION' 				=> 'Funktion',
	'GROUP' 				=> 'Gruppe',
	'HEADER' 				=> 'Kopfzeile',
	'HEADING' 				=> 'Überschrift',
	'HEADING_CSS_FILE' 		=> 'Aktuelle Moduldatei: ',
	'HEIGHT' 				=> 'Höhe',
	'HELP_LEPTOKEN_LIFETIME'=> 'in Sekunden, 0 bedeutet kein CSRF-Schutz!',
	'HELP_MAX_ATTEMPTS'		=> 'Bei Überschreiten der angegebenen Anzahl werden weitere Versuche für diese Session verhindert.',
	'HIDDEN' 				=> 'Versteckt',
	'HIDE' 					=> 'verstecken',
	'HIDE_ADVANCED' 		=> 'Erweiterte Optionen verdecken',
	'HOME' 					=> 'Home',
	'HOMEPAGE_REDIRECTION' 	=> 'URL Umleitung zur Homepage',
	'HOME_FOLDER' 			=> 'Persönlicher Ordner',
	'HOME_FOLDERS' 			=> 'Persönliche Ordner',
	'HOST' 					=> 'Host',
	'ICON' 					=> 'Symbol',
	'IMAGE' 				=> 'Bild',
	'INLINE' 				=> 'Integriert',
	'INSTALL' 				=> 'Installieren',
	'INSTALLATION' 			=> 'Installation',
	'INSTALLATION_PATH' 	=> 'Installationspfad',
	'INSTALLATION_URL' 		=> 'Installationsadresse(URL)',
	'INSTALLED' 			=> 'installiert',
	'INTRO' 				=> 'Eingangs',
	'INTRO_PAGE' 			=> 'Eingangsseite',
	'INVALID_SIGNS' 		=> 'muss mit einem Buchstaben beginnen oder hat ungültige Zeichen',
	'KEYWORDS' 				=> 'Schlüsselworte',
	'LANGUAGE' 				=> 'Sprache',
	'LAST_UPDATED_BY' 		=> 'zuletzt geändert von',
	'LENGTH' 				=> 'Länge',
	'LEPTOKEN_LIFETIME'		=> 'Leptoken-Lebensdauer',
	'LEVEL' 				=> 'Ebene',
	'LIBRARY'				=> 'Bibliothek',
	'LICENSE'				=> 'Lizenz',
	'LINK' 					=> 'Link',
	'LINUX_UNIX_BASED' 		=> 'Linux/Unix basierend',
	'LIST_OPTIONS' 			=> 'Auswahlliste',
	'LOGGED_IN' 			=> 'Angemeldet',
	'LOGIN' 				=> 'Anmeldung',
	'LONG' 					=> 'Lang',
	'LONG_TEXT' 			=> 'Langtext',
	'LOOP' 					=> 'Schleife',
	'MAIN' 					=> 'Hauptblock',
	'MANAGE' 				=> 'Verwalten Sie',
	'MANAGE_GROUPS' 		=> 'Gruppen verwalten',
	'MANAGE_USERS' 			=> 'Benutzer verwalten',
	'MATCH' 				=> 'Übereinstimmung',
	'MATCHING' 				=> 'passende',
	'MAX_ATTEMPTS'			=> 'Erlaubte Anzahl Anmeldeversuche',
	'MAX_EXCERPT' 			=> 'Max Anzahl Zitate pro Seite',
	'MAX_SUBMISSIONS_PER_HOUR' => 'Max. Eintragungen pro Stunde',
	'MEDIA_DIRECTORY' 		=> 'Medienverzeichnis',
	'MENU' 					=> 'Menü',
	'MENU_ICON_0' 			=> 'Menü-Icon normal',
	'MENU_ICON_1' 			=> 'Menü;-Icon hover',
	'MENU_TITLE' 			=> 'Menütitel',
	'MESSAGE' 				=> 'Nachricht',
	'MODIFY' 				=> 'Ändern',
	'MODIFY_CONTENT' 		=> 'Inhalt ändern',
	'MODIFY_SETTINGS'	 	=> 'Optionen ändern',
	'MODULE_ORDER' 			=> 'Modulreihenfolge für die Suche',
	'MODULE_PERMISSIONS' 	=> 'Modulberechtigungen',
	'MORE' 					=> 'Mehr',
	'MOVE_DOWN' 			=> 'Abwärts verschieben',
	'MOVE_UP' 				=> 'Aufwärts verschieben',
	'MULTIPLE_MENUS' 		=> 'Mehrere Menüs',
	'MULTISELECT' 			=> 'Mehrfachauswahl',
	'NAME' 					=> 'Name',
	'NEED_CURRENT_PASSWORD' => 'mit aktuellem Passwort bestätigen',
	'NEED_PASSWORD_TO_CONFIRM' => 'Bitte die Änderungen mit aktuellem Passwort bestätigen',
	'NEED_TO_LOGIN' 		=> 'Möchten Sie sich einloggen?',
	'NEW_PASSWORD' 			=> 'Neues Passwort',
	'NEW_USER_HINT'			=> 'Mindestlänge Benutzername: %d Buchstaben, Mindestlänge Passwort: %d Buchstaben!',
	'NEW_WINDOW' 			=> 'Neues Fenster',
	'NEXT' 					=> 'nächste',
	'NEXT_PAGE' 			=> 'nächste Seite',
	'NO' 					=> 'Nein',
	'NO_LEPTON_ADDON'  		=> 'Dieses Addon ist nicht für LEPTON geeignet',
	'NONE' 					=> 'Keine',
	'NONE_FOUND' 			=> 'Keine gefunden',
	'NOT_FOUND' 			=> 'Nicht gefunden',
	'NOT_INSTALLED' 		=> 'nicht installiert',
	'NO_RESULTS' 			=> 'Keine Ergebnisse',
	'OF' 					=> 'von',
	'ON' 					=> 'Am',
	'OPEN' 					=> 'Öffnen',
	'OPTION' 				=> 'Option',
	'OTHERS' 				=> 'Alle',
	'OUT_OF' 				=> 'von',
	'OVERWRITE_EXISTING' 	=> 'Überschreibe bestehende',
	'PAGE' 					=> 'Seite',
	'PAGES_DIRECTORY' 		=> 'Seitenverzeichnis',
	'PAGES_PERMISSION' 		=> 'Seitenberechtigung',
	'PAGES_PERMISSIONS' 	=> 'Seitenberechtigungen',
	'PAGE_EXTENSION' 		=> 'Dateiendungen',
	'PAGE_ICON' 			=> 'Seitenbild',
	'PAGE_ID'      			=> 'Seiten-ID',
	'PAGE_LANGUAGES' 		=> 'Seitensprache',
	'PAGE_LEVEL_LIMIT' 		=> 'Limit der Seitenebenen',
	'PAGE_SPACER' 			=> 'Leerzeichen',
	'PAGE_TITLE' 			=> 'Seitentitel',
	'PAGE_TRASH' 			=> 'Seiten Mülleimer',
	'PARENT' 				=> 'Übergeordnete Datei',
	'PASSWORD' 				=> 'Passwort',
	'PATH' 					=> 'Pfad',
	'PHP_ERROR_LEVEL' 		=> 'PHP Fehlerberichte',
	'PLEASE_LOGIN' 			=> 'Bitte anmelden',
	'PLEASE_SELECT' 		=> 'Bitte auswählen',
	'POST' 					=> 'Beitrag',
	'POSTS_PER_PAGE' 		=> 'Nachrichten pro Seite',
	'POST_FOOTER' 			=> 'Nachricht Fußzeile',
	'POST_HEADER' 			=> 'Nachricht Kopfzeile',
	'PREVIOUS' 				=> 'vorherige',
	'PREVIOUS_PAGE' 		=> 'vorherige Seite',
	'PRIVATE' 				=> 'Privat',
	'PRIVATE_VIEWERS' 		=> 'Private Nutzer',
	'PROFILES_EDIT' 		=> 'Profil ändern',
	'PUBLIC' 				=> 'Öffentlich',
	'PUBL_END_DATE' 		=> 'End Datum',
	'PUBL_START_DATE' 		=> 'Start Datum',
	'RADIO_BUTTON_GROUP' 	=> 'Optionsfeld',
	'READ' 					=> 'Lesen',
	'READ_MORE' 			=> 'Weiterlesen',
	'REDIRECT_AFTER' 		=> 'Weiterleitung nach',
	'REGISTERED' 			=> 'registriert',
	'REGISTERED_VIEWERS' 	=> 'registrierter Besucher',
	'REGISTERED_CONTENT'	=> 'Auf diese Inhalte können nur registrierte Besucher der Website zugreifen',
	'RELOAD' 				=> 'Neu laden',
	'REMEMBER_ME' 			=> 'Passwort speichern',
	'RENAME' 				=> 'Umbenennen',
	'RENAME_FILES_ON_UPLOAD' => 'Datei nach Hochladen umbenennen',
	'REQUIRED' 				=> 'Erforderlich',
	'REQUIREMENT' 			=> 'Voraussetzung',
	'RESET' 				=> 'Zurücksetzen',
	'RESIZE' 				=> 'Größe ändern',
	'RESIZE_IMAGE_TO' 		=> 'Bildgöße verändern auf',
	'RESTORE' 				=> 'Wiederherstellen',
	'RESTORE_DATABASE' 		=> 'Datenbank wiederherstellen',
	'RESTORE_MEDIA' 		=> 'Dateien wiederherstellen',
	'RESULTS' 				=> 'Resultate',
	'RESULTS_FOOTER' 		=> 'Ergebnisse Fußzeile',
	'RESULTS_FOR' 			=> 'Ergebnisse für',
	'RESULTS_HEADER' 		=> 'Ergebnisse Überschrift',
	'RESULTS_LOOP' 			=> 'Ergebnisse Schleife',
	'RETYPE_NEW_PASSWORD' 	=> 'Neues Passwort wiederholen',
	'RETYPE_PASSWORD' 		=> 'Geben Sie bitte Ihr Passwort nochmal ein',
	'SAME_WINDOW' 			=> 'Gleiches Fenster',
	'SAVE' 					=> 'Speichern',
	'SEARCH' 				=> 'Suche',
	'SEARCH_FOR'  			=> 'Suche nach',
	'SEARCHING' 			=> 'suchen',
	'SECTION' 				=> 'Abschnitt',
	'SECTION_BLOCKS' 		=> 'Blöcke',
	'SECTION_ID' 			=> 'Sektion ID',
	'SEC_ANCHOR' 			=> 'Abschnitts-Anker Text',
	'SELECT_BOX' 			=> 'Auswahlliste',
	'SEND_DETAILS' 			=> 'Anfrage senden',
	'SEPARATE' 				=> 'Separat',
	'SEPERATOR' 			=> 'Separator',
	'SERVER_EMAIL' 			=> 'Server E-Mail',
	'SERVER_OPERATING_SYSTEM' => 'Server Betriebssystem',
	'SESSION_IDENTIFIER' 	=> 'Sitzungs ID',
	'SETTINGS' 				=> 'Optionen',
	'SHORT' 				=> 'Kurz',
	'SHORT_TEXT' 			=> 'Kurztext',
	'SHOW' 					=> 'zeigen',
	'SHOW_ADVANCED'	 		=> 'Erweiterte Optionen anzeigen',
	'SIGNUP' 				=> 'Registrierung',
	'SIZE' 					=> 'Größe',
	'SMART_LOGIN' 			=> 'Intelligente Anmeldung',
	'START' 				=> 'Start',
	'START_PUBLISHING' 		=> 'Beginn der Veröffentlichung',
	'SUBJECT' 				=> 'Betreff',
	'SUBMISSIONS' 			=> 'Eintragungen',
	'SUBMISSIONS_STORED_IN_DATABASE' => 'Max. gespeicherte Eintragungen',
	'SUBMISSION_ID' 		=> 'Eintragungs-ID',
	'SUBMITTED' 			=> 'eingetragen',
	'SUCCESS' 				=> 'Erfolgreich',
	'SYSTEM_DEFAULT' 		=> 'Standardeinstellung',
	'SYSTEM_PERMISSIONS' 	=> 'Zugangsberechtigungen',
	'TABLE_PREFIX' 			=> 'TabellenPräfix',
	'TARGET' 				=> 'Ziel',
	'TARGET_FOLDER' 		=> 'Zielordner',
	'TEMPLATE' 				=> 'Template',
	'TEMPLATE_PERMISSIONS' 	=> 'Zugriffsrechte für Vorlagen',
	'TEXT' 					=> 'Text',
	'TEXTAREA' 				=> 'Langtext',
	'TEXTFIELD' 			=> 'Kurztext',
	'THEME' 				=> 'Backend-Theme',
	'TIME' 					=> 'Zeit',
	'TIMEZONE' 				=> 'Zeitzone',
	'TIME_FORMAT' 			=> 'Zeitformat',
	'TIME_LIMIT' 			=> 'Zeitlimit zur Erstellung der Zitate pro Modul',
	'TITLE' 				=> 'Titel',
	'TO' 					=> 'an',
	'TOP_FRAME' 			=> 'Frameset sprengen',
	'TRASH_EMPTIED' 		=> 'Mülleimer geleert',
	'TXT_EDIT_CSS_FILE' 	=> 'Bearbeite die CSS Definitionen im nachfolgenden Textfeld.',
	'TYPE' 					=> 'Art',
	'UNINSTALL' 			=> 'Deinstallieren',
	'UNKNOWN' 				=> 'Unbekannt',
	'UNLIMITED' 			=> 'Unbegrenzt',
	'UNZIP_FILE' 			=> 'Zip-Archiv hochladen und entpacken',
	'UP' 					=> 'Aufwärts',
	'UPGRADE' 				=> 'Aktualisieren',
	'UPLOAD_FILES' 			=> 'Datei(en) übertragen',
	'URL' 					=> 'URL',
	'USER' 					=> 'Besitzer',
	'USERNAME' 				=> 'Benutzername',
	'USERS_ACTIVE' 			=> 'Benutzer ist aktiv',
	'USERS_CAN_SELFDELETE' 	=> 'Selbstlöschung möglich',
	'USERS_CHANGE_SETTINGS' => 'Benutzer kann eigene Einstellungen ändern',
	'USERS_DELETED' 		=> 'Benutzer ist als gelöscht markiert',
	'USERS_FLAGS' 			=> 'Benutzerflags',
	'USERS_PROFILE_ALLOWED' => 'Benutzer kann erweitertes Profil anlegen',
	'VERIFICATION' 			=> 'Spamschutz',
	'VERSION' 				=> 'Version',
	'VIEW' 					=> 'Ansicht',
	'VIEW_DELETED_PAGES' 	=> 'gelöschte Seiten anschauen',
	'VIEW_DETAILS' 			=> 'Details',
	'VISIBILITY' 			=> 'Sichtbarkeit',
	'WBMAILER_DEFAULT_SENDER_MAIL' => 'Standard "VON" Adresse',
	'WBMAILER_DEFAULT_SENDER_NAME' => 'Standard Absender Name',
	'WBMAILER_DEFAULT_SETTINGS_NOTICE' => 'Bitte geben Sie eine Standard "VON" Adresse und einen Sendernamen an. Als Absender Adresse empfiehlt sich ein Format wie: <strong>admin@IhreWebseite.de</strong>. Manche E-Mail Provider (z.B. <em>mail.de</em>) stellen keine E-Mails zu, die nicht &uuml;ber den Provider selbst verschickt wurden, in der Absender Adresse aber den Namen des E-Mail Providers <em>name@mail.de</em> enthalten. Die Standard Werte werden nur verwendet, wenn keine anderen Werte von LEPTON gesetzt wurden. Wenn Ihr Service Provider <acronym title="Simple Mail Transfer Protocol">SMTP</acronym> anbietet, sollten Sie diese Option f&uuml;r ausgehende E-Mails verwenden.',
	'WBMAILER_FUNCTION' 	=> 'E-Mail Routine',
	'WBMAILER_NOTICE' 		=> '<strong>SMTP Maileinstellungen:</strong><br />Die nachfolgenden Einstellungen m&uuml;ssen nur angepasst werden, wenn Sie E-Mail &uuml;ber <acronym title="Simple Mail Transfer Protocol">SMTP</acronym> verschicken wollen. Wenn Sie Ihren SMTP Server nicht kennen, oder Sie sich unsicher bei den Einstellungen sind, verwenden Sie einfach die Standard E-Mail Routine: PHP MAIL.',
	'WBMAILER_PHP' 			=> 'PHP MAIL',
  'WBMAILER_SEND_TESTMAIL' => 'Testmail verschicken',
	'WBMAILER_SMTP' 		=> 'SMTP',
	'WBMAILER_SMTP_AUTH' 	=> 'SMTP Authentifikation',
	'WBMAILER_SMTP_AUTH_NOTICE' => 'nur aktivieren, wenn SMTP Authentifikation benötigt wird',
	'WBMAILER_SMTP_HOST' 	=> 'SMTP Host',
	'WBMAILER_SMTP_PASSWORD' => 'SMTP Passwort',
	'WBMAILER_SMTP_USERNAME' => 'SMTP Benutzername',
  'WBMAILER_TESTMAIL_FAILED' => 'Das Versenden der Testmail ist fehlgeschlagen! Bitte die Einstellungen prüfen!',
	'WBMAILER_TESTMAIL_SUCCESS' => 'Die Testmail wurde erfolgreich verschickt.',
  'WBMAILER_TESTMAIL_TEXT' => 'Dies ist die angeforderte Testmail: Die Maileinstellungen funktionieren',
	'WEBSITE' 				=> 'Webseite',
	'WEBSITE_DESCRIPTION' 	=> 'Webseitenbeschreibung',
	'WEBSITE_FOOTER' 		=> 'Fußzeile',
	'WEBSITE_HEADER' 		=> 'Kopfzeile',
	'WEBSITE_KEYWORDS' 		=> 'Schlüsselworte',
	'WEBSITE_TITLE' 		=> 'Webseitentitel',
	'WELCOME_BACK' 			=> 'Willkommen zurück',
	'WIDTH' 				=> 'Breite',
	'WINDOW' 				=> 'Fenster',
	'WINDOWS' 				=> 'Windows',
	'WORLD_WRITEABLE_FILE_PERMISSIONS' => 'Einstellungen für Schreibrechte',
	'WRITE' 				=> 'Schreiben',
	'WYSIWYG_EDITOR' 		=> 'WYSIWYG Editor',
	'WYSIWYG_STYLE' 		=> 'WYSIWYG Stil',
	'YES' 					=> 'Ja',
	'BASICS'	=> array(
		'day'		=> "Tag",		# day, singular
		'day_pl'	=> "Tage",		# day, plural
		'hour'		=> "Stunde", 	# hour, singular
		'hour_pl'	=> "Stunden", 	# hour, plural
		'minute'	=> "Minute",	# minute, singular
		'minute_pl'	=> "Minuten",	# minute, plural
	)
);

$HEADING = array(
	'ADDON_PRECHECK_FAILED' => 'Add-On Voraussetzungen nicht erfüllt',
	'ADD_CHILD_PAGE' 		=> 'Unterseite hinzuf&uuml;gen',
	'ADD_GROUP' 			=> 'Gruppe hinzufügen',
	'ADD_GROUPS' 			=> 'Gruppen hinzufügen',
	'ADD_HEADING' 			=> 'Kopf hinzufügen',
	'ADD_PAGE' 				=> 'Seite hinzufügen',
	'ADD_USER' 				=> 'Benutzer hinzufügen',
	'ADMINISTRATION_TOOLS' 	=> 'Verwaltungsprogramme',
	'BROWSE_MEDIA' 			=> 'Medien durchsuchen',
	'CREATE_FOLDER' 		=> 'Ordner erstellen',
	'DEFAULT_SETTINGS' 		=> 'Standardeinstellungen',
	'DELETED_PAGES' 		=> 'gel&ouml;schte Seiten',
	'FILESYSTEM_SETTINGS' 	=> 'Dateisystemoptionen',
	'GENERAL_SETTINGS' 		=> 'Allgemeine Optionen',
	'INSTALL_LANGUAGE' 		=> 'Sprache hinzufügen',
	'INSTALL_MODULE' 		=> 'Modul installieren',
	'INSTALL_TEMPLATE' 		=> 'Designvorlage installieren',
	'INVOKE_MODULE_FILES' 	=> 'Moduldateien manuell aufrufen',
	'LANGUAGE_DETAILS' 		=> 'Details zur Sprache',
	'MANAGE_SECTIONS' 		=> 'Abschnitte verwalten',
	'MODIFY_ADVANCED_PAGE_SETTINGS' => 'Erweiterte Seitenoptionen ändern',
	'MODIFY_DELETE_GROUP' 	=> 'Ändern/Löschen von Gruppen',
	'MODIFY_DELETE_PAGE' 	=> 'Seite andern / Seite löschen',
	'MODIFY_DELETE_USER' 	=> 'Ändern / Löschen von Benutzern',
	'MODIFY_GROUP' 			=> 'Gruppe ändern',
	'MODIFY_GROUPS' 		=> 'Gruppen ändern',
	'MODIFY_INTRO_PAGE' 	=> 'Eingangsseite ändern',
	'MODIFY_PAGE' 			=> 'Seite ändern',
	'MODIFY_PAGE_SETTINGS' 	=> 'Seitenoptionen ändern',
	'MODIFY_USER' 			=> 'Benutzer ändern',
	'MODULE_DETAILS' 		=> 'Details zum Modul',
	'MY_EMAIL' 				=> 'E-Mail Adresse',
	'MY_PASSWORD' 			=> 'Passwort',
	'MY_SETTINGS' 			=> 'Einstellungen',
	'SEARCH_SETTINGS' 		=> 'Suchoptionen',
	'SEARCH_PAGE' 			=> 'Seite suchen',
	'SECURITY_SETTINGS'		=> 'Sicherheitseinstellungen',
	'SERVER_SETTINGS' 		=> 'Servereinstellungen',
	'TEMPLATE_DETAILS' 		=> 'Details zur Designvorlage',
	'UNINSTALL_LANGUAGE' 	=> 'Sprache löschen',
	'UNINSTALL_MODULE' 		=> 'Modul deinstallieren',
	'UNINSTALL_TEMPLATE' 	=> 'Designvorlage deinstallieren',
	'UPGRADE_LANGUAGE' 		=> 'Sprache registrieren/aktualisieren',
	'UPLOAD_FILES' 			=> 'Datei(en) übertragen',
	'VISIBILITY' 			=> 'Sichtbarkeit',
	'WBMAILER_SETTINGS' 	=> 'Maileinstellungen'
); // $HEADING

$MESSAGE = array(
	'ADDON_ERROR_RELOAD' 		=> 'Fehler beim Abgleich der Addon Informationen.',
	'ADDON_GROUPS_MARKALL' 		=> 'Alle markieren',
	'ADDON_LANGUAGES_RELOADED' 	=> 'Sprachen erfolgreich geladen',
	'ADDON_MANUAL_FTP_LANGUAGE' => '<strong>ACHTUNG!</strong> Überspielen Sie Sprachdateien aus Sicherheitsgründen nur über FTP in den Ordner /languages/ und benutzen die Upgrade Funktion zum Registrieren oder Aktualisieren.',
	'ADDON_MANUAL_FTP_WARNING' 	=> 'Warnung: <br />Eventuell vorhandene Datenbankeinträge eines Moduls gehen verloren. ',
	'ADDON_MANUAL_INSTALLATION' => 'Beim Hochladen oder Löschen von Modulen per FTP (nicht empfohlen), werden eventuell vorhandene Modulfunktionen <tt>install</tt>, <tt>upgrade</tt> oder <tt>uninstall</tt> nicht automatisch ausgeführt. Solche Module funktionieren daher meist nicht richtig, oder hinterlassen Datenbankeinträge beim Löschen per FTP.<br /><br /> Nachfolgend können die Modulfunktionen von per FTP hochgeladenen Modulen manuell ausgeführt werden.',
	'ADDON_MANUAL_INSTALLATION_WARNING' => 'Warnung: Eventuell vorhandene Datenbankeinträge eines Moduls gehen verloren.<br />Bitte nur bei Problemen mit per FTP hochgeladenen Modulen verwenden. ',
	'ADDON_MANUAL_RELOAD_WARNING' => 'Warnung: Eventuell vorhandene Datenbankeinträge eines Moduls gehen verloren. ',
	'ADDON_MODULES_RELOADED' 	=> 'Module erfolgreich geladen',
	'ADDON_PRECHECK_FAILED' 	=> 'Installation fehlgeschlagen. Ihr System erfüllt nicht alle Voraussetzungen die für diese Erweiterung benötigt werden. Um diese Erweiterung nutzen zu können, müssen nachfolgende Updates durchgeführt werden.',
	'ADDON_RELOAD' 				=> 'Abgleich der Datenbank mit den Informationen aus den Addon-Dateien (z.B. nach FTP Upload).',
	'ADDON_TEMPLATES_RELOADED' 	=> 'Designvorlagen erfolgreich geladen',
	'ADMIN_INSUFFICIENT_PRIVELLIGES' => 'Ungenügende Zugangsberechtigungen',
	'FORGOT_PASS_ALREADY_RESET' => 'Das Passwort kann nur einmal pro Stunde zurückgesetzt werden',
	'FORGOT_PASS_CANNOT_EMAIL' 	=> 'Das Passwort konnte nicht versendet werden, bitte kontaktieren Sie den Systemadministrator',
	'FORGOT_PASS_EMAIL_NOT_FOUND' => 'Die angegebene E-Mail Adresse wurde nicht in der Datenbank gefunden',
	'FORGOT_PASS_NO_DATA' 		=> 'Bitte geben Sie nachfolgend Ihre E-Mail Adresse an',
	'FORGOT_PASS_PASSWORD_RESET' => 'Ihr Benutzername und Ihr Passwort wurden an Ihre E-Mail Adresse gesendet',
	'FRONTEND_SORRY_NO_ACTIVE_SECTIONS' => 'Kein aktiver Inhalt auf dieser Seite vorhanden',
	'FRONTEND_SORRY_NO_VIEWING_PERMISSIONS' => 'Sie sind nicht berechtigt, diese Seite zu sehen',
	'GENERIC_ALREADY_INSTALLED' => 'Bereits installiert.',
	'GENERIC_BAD_PERMISSIONS' 	=> 'Kann im Zielverzeichnis nicht schreiben.',
	'GENERIC_CANNOT_UNINSTALL' 	=> 'Deinstallation fehlgeschlagen.',
	'GENERIC_CANNOT_UNINSTALL_IN_USE' => 'Deinstallation nicht möglich: Datei wird benutzt.',
	'GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL' => '<br /><br />Das {{type}} <b>{{type_name}}</b> kann nicht deinstalliert werden, weil es auf {{pages}} benutzt wird:<br /><br />',
	'GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES' => 'folgender Seite;folgenden Seiten',
	'GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE' => 'Das Template <b>{{name}}</b> kann nicht deinstalliert werden, weil es das Standardtemplate ist!',
	'GENERIC_CANNOT_UNZIP' 		=> 'Fehler beim Entpacken!',
	'GENERIC_CANNOT_UPLOAD' 	=> 'Die Datei kann nicht übertragen werden!',
	'GENERIC_COMPARE' 			=> ' erfolgreich',
	'GENERIC_ERROR_OPENING_FILE' => 'Fehler beim öffnen der Datei.',
	'GENERIC_FAILED_COMPARE' 	=> ' fehlgeschlagen',
	'GENERIC_FILE_TYPE' 		=> 'Bitte beachten Sie, dass Sie nur den folgenden Dateityp auswählen können:',
	'GENERIC_FILE_TYPES' 		=> 'Bitte beachten Sie, dass Sie nur folgende Dateitypen auswählen können:',
	'GENERIC_FILL_IN_ALL' 		=> 'Bitte alle Felder ausfüllen.',
	'GENERIC_INSTALLED' 		=> 'Erfolgreich installiert.',
	'GENERIC_INVALID' 			=> 'Die übertragene Datei ist ungültig.',
	'GENERIC_INVALID_ADDON_FILE' => 'Ungültige LEPTON Installationsdatei. Bitte *.zip Format prüfen.',
	'GENERIC_INVALID_LANGUAGE_FILE' => 'Ungültige LEPTON Sprachdatei. Bitte Textdatei prüfen.',
	'GENERIC_IN_USE' 			=> ' aber benutzt in ',
	'GENERIC_MODULE_VERSION_ERROR' => 'Das Modul ist nicht ordnungsgemäss installiert!',
	'GENERIC_NOT_COMPARE' 		=> ' nicht möglich',
	'GENERIC_NOT_INSTALLED' 	=> 'Nicht installiert.',
	'GENERIC_NOT_UPGRADED' 		=> 'Aktualisierung nicht möglich.',
	'GENERIC_PLEASE_BE_PATIENT' => 'Die Datenbanksicherung kann je nach Größe der Datenbank einige Zeit dauern.',
	'GENERIC_PLEASE_CHECK_BACK_SOON' => 'Bitte versuchen Sie es später noch einmal ...',
	'GENERIC_SECURITY_ACCESS'	=> 'Sicherheitsverletzung!! Zugriff wurde verweigert!',
	'GENERIC_SECURITY_OFFENSE' 	=> 'Sicherheitsverletzung!! Das Speichern der Daten wurde verweigert!!',
	'GENERIC_UNINSTALLED' 		=> 'Erfolgreich deinstalliert.',
	'GENERIC_UPGRADED' 			=> 'Erfolgreich aktualisiert.',
	'GENERIC_VERSION_COMPARE' 	=> 'Versionsabgleich',
	'GENERIC_VERSION_GT' 		=> 'Upgrade erforderlich!',
	'GENERIC_VERSION_LT' 		=> 'Downgrade',
	'GROUPS_ADDED' 				=> 'Die Gruppe wurde erfolgreich hinzugefügt.',
	'GROUPS_CONFIRM_DELETE' 	=> 'Sind Sie sicher, dass Sie die ausgewählte Gruppe löschen möchten (und alle Benutzer, die dazugehören)?',
	'GROUPS_DELETED' 			=> 'Die Gruppe wurde erfolgreich gelöscht.',
	'GROUPS_GROUP_NAME_BLANK' 	=> 'Der Gruppenname wurde nicht angegeben.',
	'GROUPS_GROUP_NAME_EXISTS' 	=> 'Der Gruppenname existiert bereits.',
	'GROUPS_NO_GROUPS_FOUND' 	=> 'Keine Gruppen gefunden.',
	'GROUPS_SAVED' 				=> 'Die Gruppe wurde erfolgreich gespeichert.',
	'LANG_MISSING_PARTS_NOTICE'	=> 'Sprachdatei installation fehlgeschlagen, eine oder mehrere der folgenden Variablen fehlen:<br />language_code<br />language_name<br />language_version<br />language_license',
	'LOGIN_AUTHENTICATION_FAILED' => 'Der Benutzername oder das Passwort ist nicht korrekt.',
	'LOGIN_BOTH_BLANK' 			=> 'Bitte geben Sie unten Ihren Benutzernamen und Passwort ein.',
	'LOGIN_PASSWORD_BLANK' 		=> 'Bitte geben Sie Ihr Passwort ein.',
	'LOGIN_PASSWORD_TOO_LONG' 	=> 'Das angegebene Passwort ist zu lang!',
	'LOGIN_PASSWORD_TOO_SHORT' 	=> 'Das angegebene Passwort ist zu kurz!',
	'LOGIN_USERNAME_BLANK' 		=> 'Bitte geben Sie Ihren Benutzernamen ein.',
	'LOGIN_USERNAME_TOO_LONG' 	=> 'Der angegebene Benutzername ist zu lang!',
	'LOGIN_USERNAME_TOO_SHORT' 	=> 'Der angegebene Benutzername ist zu kurz!',
	'MEDIA_BLANK_EXTENSION' 	=> 'Sie haben keine Dateiendung angegeben!',
	'MEDIA_BLANK_NAME' 			=> 'Sie haben keinen neuen Namen angegeben!',
	'MEDIA_CANNOT_DELETE_DIR' 	=> 'Das ausgewählte Verzeichnis konnte nicht gelöscht werden.',
	'MEDIA_CANNOT_DELETE_FILE' 	=> 'Die ausgewählte Datei konnte nicht gelöscht werden',
	'MEDIA_CONFIRM_DELETE' 		=> 'Sind Sie sicher, dass Sie die folgende Datei oder Verzeichnis löschen möchten?',
	'MEDIA_CONFIRM_DELETE_FILE'	=> 'Sind Sie sicher, dass Sie die Datei \n\n{name}\n\nlöschen möchten?',
	'MEDIA_CONFIRM_DELETE_DIR'	=> 'Sind Sie sicher, dass Sie das Verzeichnis \n\n{name}\n\nlöschen möchten?',
	'MEDIA_DELETED_DIR' 		=> 'Das Verzeichnis wurde gelöscht.',
	'MEDIA_DELETED_FILE' 		=> 'Die Datei wurde gelöscht.',
	'MEDIA_DIR_ACCESS_DENIED' 	=> 'Verzeichnis existiert nicht oder Zugriff verweigert.',
	'MEDIA_DIR_DOES_NOT_EXIST' 	=> 'Verzeichnis existiert nicht!',
	'MEDIA_DIR_DOT_DOT_SLASH' 	=> 'Der Verzeichnisname darf nicht ../ enthalten!',
	'MEDIA_DIR_EXISTS' 			=> 'Ein Verzeichnis mit dem angegebenen Namen existiert bereits.',
	'MEDIA_DIR_MADE' 			=> 'Das Verzeichnis wurde erfolgreich angelegt.',
	'MEDIA_DIR_NOT_MADE' 		=> 'Das Verzeichnis konnte nicht angelegt werden.',
	'MEDIA_FILE_EXISTS' 		=> 'Eine Datei mit dem angegebenen Namen existiert bereits.',
	'MEDIA_FILE_NOT_FOUND' 		=> 'Die Datei konnte nicht gefunden werden.',
	'MEDIA_NAME_DOT_DOT_SLASH' 	=> 'Der Name darf nicht ../ enthalten!',
	'MEDIA_NAME_INDEX_PHP' 		=> 'Der Dateiname index.php kann nicht verwendet werden.',
	'MEDIA_NONE_FOUND' 			=> 'Keine Dateien im aktuellen Verzeichnis',
	'MEDIA_RENAMED' 			=> 'Das Umbenennen war erfolgreich',
	'MEDIA_SINGLE_UPLOADED' 	=> 'Datei wurde erfolgreich übertragen',
	'MEDIA_TARGET_DOT_DOT_SLASH' => 'Der Name des Zielverzeichnisses darf nicht ../ enthalten',
	'MEDIA_UPLOADED' 			=> 'Dateien wurden erfolgreich übertragen',
	'MOD_MISSING_PARTS_NOTICE'	=> 'Installation vom Modul "%s" fehlgeschlagen, eine oder mehrere der folgenden Variablen fehlen:<br />module_directory<br />module_name<br />module_version<br />module_author<br />module_license<br />module_guid<br />module_function',
	'MOD_FORM_EXCESS_SUBMISSIONS' => 'Dieses Formular wurde zu oft aufgerufen. Bitte versuchen Sie es in einer Stunde noch einmal.',
	'MOD_FORM_INCORRECT_CAPTCHA' => 'Die eingegebene Prüfziffer stimmt nicht &uuml;berein. Wenn Sie Probleme mit dem Lesen der Prüfziffer haben, bitte schreiben Sie eine E-Mail an uns: <a href="mailto:'.SERVER_EMAIL.'">'.SERVER_EMAIL.'</a>',
	'MOD_FORM_REQUIRED_FIELDS' 	=> 'Bitte folgende Angaben ergänzen',
	'PAGES_ADDED' 				=> 'Die Seite wurde erfolgreich hinzugefügt',
	'PAGES_ADDED_HEADING' 		=> 'Seitenkopf erfolgreich hinzugefügt',
	'PAGES_BLANK_MENU_TITLE' 	=> 'Bitte geben Sie einen Menütitel ein',
	'PAGES_BLANK_PAGE_TITLE' 	=> 'Bitte geben Sie einen Titel für die Seite ein',
	'PAGES_CANNOT_CREATE_ACCESS_FILE' => 'Beim Anlegen der Zugangsdatei im Seitenverzeichnis (page) ist ein Fehler aufgetreten (Ungenügende Zugangsrechte)',
	'PAGES_CANNOT_DELETE_ACCESS_FILE' => 'Beim Löschen der Zugangsdatei im Seitenverzeichnis (page) ist ein Fehler aufgetreten (Ungenügende Zugangsrechte)',
	'PAGES_CANNOT_REORDER' 		=> 'Bei der Zusammenstellung der Seite ist ein Fehler aufgetreten',
	'PAGES_DELETED' 			=> 'Die Seite wurde erfolgreich gelöscht',
	'PAGES_DELETE_CONFIRM' 		=> 'Sind Sie sicher, dass Sie die ausgewählte Seite &raquo;%s&laquo; löschen möchten',
	'PAGES_INSUFFICIENT_PERMISSIONS' => 'Sie haben keine Berechtigung, diese Seite zu ändern',
	'PAGES_INTRO_EMPTY' 		=> 'Bitte Content einfügen, eine leere Introseite kann nicht gespeichert werden.',
	'PAGES_INTRO_LINK' 			=> 'Bitte klicken Sie HIER um die Eingangsseite zu ändern',
	'PAGES_INTRO_NOT_WRITABLE' 	=> 'Es konnte nicht in die Datei intro.php im Seitenverzeichnis (page) geschrieben werden (ungenügende Zugangsrechte)',
	'PAGES_INTRO_SAVED' 		=> 'Eingangsseite wurde erfolgreich gespeichert',
	'PAGES_LAST_MODIFIED' 		=> 'Letzte Änderung:',
	'PAGES_NOT_FOUND' 			=> 'Die Seite konnte nicht gefunden werden',
	'PAGES_NOT_SAVED' 			=> 'Beim Speichern der Seite ist ein Fehler aufgetreten',
	'PAGES_PAGE_EXISTS' 		=> 'Eine Seite mit einem &auml;hnlichen oder demselben Titel existiert bereits',
	'PAGES_REORDERED' 			=> 'Die Seite wurde erfolgreich neu zusammengestellt',
	'PAGES_RESTORED' 			=> 'Die Seite wurde erfolgreich wiederhergestellt',
	'PAGES_RETURN_TO_PAGES' 	=> 'Zurück zum Seitenmenü',
	'PAGES_SAVED' 				=> 'Die Seite wurde erfolgreich gespeichert',
	'PAGES_SAVED_SETTINGS' 		=> 'Die Seiteneinstellungen wurden erfolgreich gespeichert',
	'PAGES_SECTIONS_PROPERTIES_SAVED' => 'Einstellungen für diesen Abschnitt erfolgreich gespeichert',
	'PREFERENCES_CURRENT_PASSWORD_INCORRECT' => 'Das alte Passwort, das Sie angegeben haben, ist ungültig',
	'PREFERENCES_DETAILS_SAVED' => 'Persönliche Daten wurden erfolgreich gespeichert',
	'PREFERENCES_EMAIL_UPDATED' => 'E-Mail Einstellung geändert',
	'PREFERENCES_INVALID_CHARS' => 'Es wurden ung&uuml;ltige Zeichen für des Passwort verwendet, gültig sind: a-z\A-Z\0-9\_\-\!\#\*\+',
	'PREFERENCES_PASSWORD_CHANGED' => 'Das Passwort wurde erfolgreich geändert',
	'PREFERENCES_PASSWORD_MATCH' => 'Die Passworte stimmen nicht überein',
	'RECORD_MODIFIED_FAILED' 	=> 'Änderung des Datensatzes ist fehlgeschlagen.',
	'RECORD_MODIFIED_SAVED' 	=> 'Geänderter Datensatz wurde erfolgreich aktualisiert.',
	'RECORD_NEW_FAILED' 		=> 'Hinzufügen eines neuen Datensatzes ist fehlgeschlagen.',
	'RECORD_NEW_SAVED' 			=> 'Neuer Datensatz wurde erfolgreich hinzugefügt.',
	'SETTINGS_MODE_SWITCH_WARNING' => 'Bitte beachten Sie: Wenn Sie dieses Feld anklicken, werden alle ungespeicherten Änderungen zurückgesetzt',
	'SETTINGS_SAVED' 			=> 'Die Optionen wurden erfolgreich gespeichert',
	'SETTINGS_UNABLE_OPEN_CONFIG' => 'Konfigurationsdatei konnte nicht geöffnet werden',
	'SETTINGS_UNABLE_WRITE_CONFIG' => 'Die Konfigurationsdatei konnte nicht geschrieben werden',
	'SETTINGS_WORLD_WRITEABLE_WARNING' => 'Bitte beachten Sie: Dies wird nur zu Testzwecken empfohlen',
	'SIGNUP2_ADMIN_INFO' 		=> '
Es wurde ein neuer User regisriert.

Username: {LOGIN_NAME}
UserId: {LOGIN_ID}
E-Mail: {LOGIN_EMAIL}
IP-Adresse: {LOGIN_IP}
Anmeldedatum: {SIGNUP_DATE}
----------------------------------------
Diese E-Mail wurde automatisch erstellt!

',
	'SIGNUP2_BODY_LOGIN_FORGOT' => '
Hallo {LOGIN_DISPLAY_NAME},

Sie erhalten diese E-Mail, weil sie ein neues Passwort angefordert haben.

Ihre neuen Logindaten für {LOGIN_WEBSITE_TITLE} lauten:

Benutzername: {LOGIN_NAME}
Passwort: {LOGIN_PASSWORD}

Das bisherige Passwort wurde durch das neue Passwort oben ersetzt.

Sollten Sie kein neues Kennwort angefordert haben, löschen Sie bitte diese E-Mail.

Mit freundlichen Grüßen
----------------------------------------
Diese E-Mail wurde automatisch erstellt!
',



	'SIGNUP2_BODY_LOGIN_INFO' => '
Hallo {LOGIN_DISPLAY_NAME},

Herzlich willkommen bei \'{LOGIN_WEBSITE_TITLE}\'

Ihre Logindaten f&uuml;r \'{LOGIN_WEBSITE_TITLE}\' lauten:
Benutzername: {LOGIN_NAME}
Passwort: {LOGIN_PASSWORD}

Vielen Dank für Ihre Registrierung

Wenn Sie dieses E-Mail versehentlich erhalten haben, löschen Sie bitte diese E-Mail.
----------------------------------------
Diese E-Mail wurde automatisch erstellt!
',

	'SIGNUP2_SUBJECT_LOGIN_INFO' => 'Deine LEPTON Logindaten ...',
	'SIGNUP_NO_EMAIL' 			=> 'Bitte geben Sie Ihre E-Mail Adresse an',
	'START_CURRENT_USER' 		=> 'Sie sind momentan angemeldet als:',
	'START_INSTALL_DIR_EXISTS' 	=> 'Das Installations-Verzeichnis "/install" existiert noch! Dies stellt ein Sicherheitsrisiko dar. Bitte löschen.',
	'START_WELCOME_MESSAGE' 	=> 'Willkommen in der LEPTON Verwaltung',
	'STATUSFLAG_32'				 => 'User kann nicht gelöscht werden, da er in der User Tabelle mit statusflags = 32 eingetragen ist.',	
	'SYSTEM_FUNCTION_DEPRECATED'=> 'Die Funktion <b>%s</b> ist veraltet, verwenden Sie die aktuelle Funktion <b>%s</b>!',
	'SYSTEM_FUNCTION_NO_LONGER_SUPPORTED' => 'Die Funktion <b>%s</b> ist veraltet und wird nicht mehr unterstützt!',
	'SYSTEM_SETTING_NO_LONGER_SUPPORTED' => 'Die Einstellung <b>%s</b> wird nicht mehr unterstützt und deshalb ignoriert!',
	'TEMPLATES_CHANGE_TEMPLATE_NOTICE' => 'Bitte beachten Sie: Um eine andere Designvorlage auszuwählen, benutzen Sie den Bereich "Optionen"',
	'TEMPLATES_MISSING_PARTS_NOTICE' => 'Template Installation fehlgeschlagen, ein oder mehrere der folgenden Variablen fehlen:<br />template_directory<br />template_name<br />template_version<br />template_author<br />template_license<br />template_function ("theme" oder "template")',
	'USERS_ADDED' 				=> 'Der Benutzer wurde erfolgreich hinzugefügt',
	'USERS_CANT_SELFDELETE' 	=> 'Funktion abgelehnt, Sie k&ouml;nnen sich nicht selbst löschen!',
	'USERS_CHANGING_PASSWORD' 	=> 'Bitte beachten Sie: Sie sollten in die obigen Felder nur Werte eingeben, wenn Sie das Passwort dieses Benutzers ändern möchten',
	'USERS_CONFIRM_DELETE' 		=> 'Sind Sie sicher, dass Sie den ausgewählten Benutzer löschen möchten?',
	'USERS_DELETED' 			=> 'Der Benutzer wurde erfolgreich gelöscht',
	'USERS_EMAIL_TAKEN' 		=> 'Die angegebene E-Mail Adresse wird bereits verwendet',
	'USERS_INVALID_EMAIL' 		=> 'Die angegebene E-Mail Adresse ist ungültig',
	'USERS_NAME_INVALID_CHARS' 	=> 'Es wurden ungültige Zeichen für den Benutzernamen verwendet',
	'USERS_NO_GROUP' 			=> 'Es wurde keine Gruppe ausgewählt',
	'USERS_PASSWORD_MISMATCH' 	=> 'Das angegebene Passwort ist ungültig',
	'USERS_PASSWORD_TOO_SHORT' 	=> 'Das eingegebene Passwort war zu kurz',
	'USERS_SAVED' 				=> 'Der Benutzer wurde erfolgreich gespeichert',
	'USERS_USERNAME_TAKEN' 		=> 'Der angegebene Benutzername wird bereits verwendet',
	'USERS_USERNAME_TOO_SHORT' 	=> 'Der eingegebene Benutzername war zu kurz'
); // $MESSAGE

$OVERVIEW = array(
	'ADMINTOOLS' 				=> 'Zugriff auf die LEPTON Verwaltungsprogramme...',
	'GROUPS' 					=> 'Verwaltung von Gruppen und Ihrer Zugangsberechtigungen...',
	'HELP' 						=> 'Noch Fragen? Hier finden Sie Antworten',
	'LANGUAGES' 				=> 'Sprachen verwalten...',
	'MEDIA' 					=> 'Verwaltung der im Medienordner gespeicherten Dateien...',
	'MODULES' 					=> 'Verwaltung der LEPTON Module...',
	'PAGES' 					=> 'Verwaltung Ihrer Webseiten...',
	'PREFERENCES' 				=> '&Auml;ndern pers&ouml;nlicher Einstellungen wie E-Mail Adresse, Passwort, usw.... ',
	'SETTINGS' 					=> '&Auml;ndern der Optionen für LEPTON...',
	'START' 					=> 'Überblick über die Verwaltung',
	'TEMPLATES' 				=> 'Ändern des Designs Ihrer Webseite mit Vorlagen...',
	'USERS' 					=> 'Verwaltung von Benutzern, die sich in LEPTON einloggen dürfen...',
	'VIEW' 						=> 'Ansicht Ihrer Webseite in einem neuen Fenster...'
); // OVERVIEW

/*
 * Create the old languages definitions only if specified in settings
 */
if (ENABLE_OLD_LANGUAGE_DEFINITIONS) {
	foreach ($MESSAGE as $key => $value) {
		$x = strpos($key, '_');
		$MESSAGE[substr($key, 0, $x)][substr($key, $x+1)] = $value;
	}
}
?>