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
if(!defined('LANGUAGE_LOADED')) {
	define('LANGUAGE_LOADED', true);
}

// Set the language information
$language_directory = 'FI';
$language_code = 'fi';
$language_name = 'Finnish';
$language_version = '2.3';
$language_platform = '3.x';
$language_author = 'Jouni Reivolahti';
$language_license = 'GNU General Public License';
$language_guid = '2a4f8878-0b11-4715-8910-bfc719024727';

$MENU = array(
	'ACCESS' 				=> 'Käyttöoikeudet', //Access
	'ADDON' 				=> 'Liitännäinen', //Add-on
	'ADDONS' 				=> 'Liitännäiset', //Add-ons
	'ADMINTOOLS' 			=> 'Hallinnointi', //Admin-Tools
	'BREADCRUMB' 			=> 'Sijaintisi: ', //You are here:
	'FORGOT' 				=> 'Käyttäjätunnukset hukassa...', //Retrieve Login Details
	'GROUP' 				=> 'Ryhmä', //Group
	'GROUPS' 				=> 'Ryhmät', //Groups
	'HELP' 					=> 'Ohje', //Help
	'LANGUAGES' 			=> 'Kielet', //Languages
	'LOGIN' 				=> 'Kirjaudu', //Login
	'LOGOUT' 				=> 'Kirjaudu ulos', //Log-out
	'MEDIA' 				=> 'Media', //Media
	'MODULES' 				=> 'Moduulit', //Modules
	'PAGES' 				=> 'Sivut', //Pages
	'PREFERENCES' 			=> 'Omat tiedot', //Preferences
	'SETTINGS' 				=> 'Asetukset', //Settings
	'START' 				=> 'Hallinnointinäkymä', //Start
	'TEMPLATES' 			=> 'Sivumallit', //Templates
	'USERS' 				=> 'Käyttäjät', //Users
	'VIEW' 					=> 'Sivusto', //View
	'SERVICE'				=> 'Huoltotoimet' //Service
); // $MENU

$TEXT = array(
	'ACCOUNT_SIGNUP' 		=> 'Rekisteröityminen', //Account Sign-Up
	'ACTION_NOT_SUPPORTED'	=> 'Toimintoa ei tueta', //Action not supported
	'ACTIONS' 				=> 'Toiminnot', //Actions
	'ACTIVE' 				=> 'Käytössä', //Active
	'ADD' 					=> 'Lisää', //Add
	'ADDON' 				=> 'Liitännäinen', //Add-on
	'ADD_SECTION' 			=> 'Lisää lohko', //Add Section
	'ADMIN' 				=> 'Admin', //Admin
	'ADMINISTRATION' 		=> 'Hallinnointi', //Adminstration
	'ADMINISTRATION_TOOL' 	=> 'Hallinnointityökalut', //Administration tool
	'ADMINISTRATOR' 		=> 'Järjestelmänvalvoja', //Adminstrator
	'ADMINISTRATORS' 		=> 'Järjestelmänvalvojat', //Adminstrators
	'ADVANCED' 				=> 'Lisäasetukset', //Advanced
	'ALLOWED_FILETYPES_ON_UPLOAD' => 'Palvelimelle ladattavissa olevat tiedostotyypit', //Allowed filetypes on upload
	'ALLOWED_VIEWERS' 		=> 'Sallitut käyttäjät', //Allowed Viewers
	'ALLOW_MULTIPLE_SELECTIONS' => 'Salli monivalinta', //Allow Multiple Selections
	'ALL_WORDS' 			=> 'Kaikki sanat', //All Words
	'ANCHOR' 				=> 'Ankkuri', //Anchor
	'ANONYMOUS' 			=> 'Tuntematon', //Anonymous
	'ANY_WORDS' 			=> 'Mikä tahansa sanoista', //Any Words
	'APP_NAME' 				=> 'Sovelluksen nimi', //Application Name
	'ARE_YOU_SURE' 			=> 'Oletko varma?', //Are you sure?
	'AUTHOR' 				=> 'Tekijä', //Author
	'BACK' 					=> 'Takaisin', //Back
	'BACKUP' 				=> 'Varmuuskopiointi', //Backup
	'BACKUP_ALL_TABLES' 	=> 'Varmista kaikki tietokannan taulut', //Backup all tables in database
	'BACKUP_DATABASE' 		=> 'Varmista tietokanta', //Backup Database
	'BACKUP_MEDIA' 			=> 'Varmista Media-tiedostot', //Backup Media
	'BACKUP_WB_SPECIFIC' 	=> 'Varmista vain järjestelmän taulut', //Backup only WB-specific tables
	'BASIC' 				=> 'Perusnäkymä', //Basic
	'BLOCK' 				=> 'Lohko', //Block
	'BACKEND_TITLE'	=>	'Backendtitle',
	'CALENDAR' 				=> 'Kalenteri', //Calendar
	'CANCEL' 				=> 'Peruuta', //Cancel
	'CAN_DELETE_HIMSELF' 	=> 'Voi poistaa itsensä', //Can delete himself
	'CAPTCHA_VERIFICATION' 	=> 'Captcha -varmistus', //Captcha Verification
	'CAP_EDIT_CSS' 			=> 'CSS-muokkaus', //Edit CSS
	'CHANGE' 				=> 'Muokkaa', //Change
	'CHANGES' 				=> 'Muutokset', //Changes
	'CHANGE_SETTINGS' 		=> 'Muokkaa asetuksia', //Change Settings
	'CHARSET' 				=> 'Merkistö', //Charset
	'CHECKBOX_GROUP' 		=> 'Valintaruutujen ryhmä', //Checkbox Group
	'CLOSE' 				=> 'Sulje', //Close
	'CODE' 					=> 'Koodi', //Code
	'CODE_SNIPPET' 			=> 'Koodileike', //Code-snippet
	'COLLAPSE' 				=> 'Tiivistä', //Collapse
	'COMMENT' 				=> 'Palaute', //Commen
	'COMMENTING' 			=> 'Palautteen anto', //Commenting
	'COMMENTS' 				=> 'Palautteet', //Comments
	'CREATE_FOLDER' 		=> 'Luo uusi kansio', //Create Folder
	'CURRENT' 				=> 'Nykyinen', //Current
	'CURRENT_FOLDER' 		=> 'Nykyinen kansio', //Current Folder
	'CURRENT_PAGE' 			=> 'Nykyinen sivu', //Current Page
	'CURRENT_PASSWORD' 		=> 'Nykyinen salasana', //Current Password
	'CUSTOM' 				=> 'Muokattu', //Custom
	'DATABASE' 				=> 'Tietokanta', //Database
	'DATE' 					=> 'Päiväys', //Date
	'DATE_FORMAT' 			=> 'Päiväyksen muoto', //Date Format
	'DEFAULT' 				=> 'Oletusarvo', //Default
	'DEFAULT_CHARSET' 		=> 'Oletusmerkistö', //Default Charset
	'DEFAULT_TEXT' 			=> 'Oletusteksti', //Default Text
	'DELETE' 				=> 'Poista', //Delete
	'DELETED' 				=> 'Poistettu', //Deleted
	'DELETE_DATE' 			=> 'Poista päiväys', //Delete date
	'DELETE_ZIP' 			=> 'Poista zip-tiedosto purkamisen jälkeen', // Delete zip archive after unpacking
	'DESCRIPTION' 			=> 'Kuvaus', //Description
	'DESIGNED_FOR' 			=> 'Kohde', //Designed For
	'DIRECTORIES' 			=> 'Hakemistot', //Directories
	'DIRECTORY_MODE' 		=> 'Hakemistotila', //Directory Mode
	'DISABLED' 				=> 'Ei käytössä', //Disabled
	'DISPLAY_NAME' 			=> 'Näkyvä nimi', //Display Name
	'EMAIL' 				=> 'Sähköposti', //Email
	'EMAIL_ADDRESS' 		=> 'Sähköpostiosoite', //Email Address
	'EMPTY_TRASH' 			=> 'Tyhjennä roskakori', //Empty Trash
	'ENABLE_JAVASCRIPT'		=> "Tämän lomakkeen käytö edellyttää JavaScript-tukea. Ole hyvä ja muokkaa selaimesi asetuksia.", //Please enable your JavaScript to use this form.
	'ENABLED' 				=> 'Käytössä', //Enabled
	'END' 					=> 'Loppu', //End
	'ERROR' 				=> 'Virhe', //Error
	'EXACT_MATCH' 			=> 'Täsmälleen sama', //Exact Match
	'EXECUTE' 				=> 'Suorita', //Execute
	'EXPAND' 				=> 'Laajenna', //Expand
	'EXTENSION' 			=> 'Tiedostotyypin tunnus', //Extension
	'FIELD' 				=> 'Kenttä', //Field
	'FILE' 					=> 'Tiedosto', //File
	'FILES' 				=> 'Tiedostot', //Files
	'FILESYSTEM_PERMISSIONS' => 'Tiedostojärjestelmän oikeudet', //Filesystem Permissions
	'FILE_MODE' 			=> 'Tiedostotila', //File Mode
	'FINISH_PUBLISHING' 	=> 'Lopeta julkaisu', //Finish Publishing
	'FOLDER' 				=> 'Kansio', //Folder
	'FOLDERS' 				=> 'Kansiot', //Folders
	'FOOTER' 				=> 'Alatunniste', //Footer
	'FORGOTTEN_DETAILS' 	=> 'Käyttäjätunnukset hukassa?', //Forgotten your details?
	'FORGOT_DETAILS' 		=> 'Käyttäjätunnukset hukassa?', //Forgot Details?
	'FROM' 					=> 'Kohteesta', //From
	'FRONTEND' 				=> 'Julkisivu', //Front-end
	'FULL_NAME' 			=> 'Koko nimi', //Full Name
	'FUNCTION' 				=> 'Toiminto', //Function
	'GROUP' 				=> 'Ryhmä', //Group
	'HEADER' 				=> 'Ylätunniste', //Header
	'HEADING' 				=> 'Otsikko', //Heading
	'HEADING_CSS_FILE' 		=> 'Moduulin css-tiedosto: ', //Actual module file:
	'HEIGHT' 				=> 'Korkeus', //Height
	'HELP_LEPTOKEN_LIFETIME'		=> 'sekunneissa, 0 = ei CSRF-suojausta!', //in seconds, 0 means no CSRF protection!
	'HELP_MAX_ATTEMPTS'		=> 'Tämä on enimmäismäärä kirjautumisyrityksiä yhden istunnon aikana.', //When reaching this number, more login attempts are not possible for this session.
	'HIDDEN' 				=> 'Piilotettu', //Hidden
	'HIDE' 					=> 'Piilota', //Hide
	'HIDE_ADVANCED' 		=> 'Piilota lisäasetukset', //Hide Advanced Options
	'HOME' 					=> 'Pääsivu', //Home
	'HOMEPAGE_REDIRECTION' 	=> 'Edelleenohjaus kotisivulle', //Homepage Redirection
	'HOME_FOLDER' 			=> 'Käyttäjäkohtainen kansio', //Personal Folder
	'HOME_FOLDERS' 			=> 'Käyttäjäkohtaiset kansiot', //Personal Folders
	'HOST' 					=> 'Isäntä', //Host
	'ICON' 					=> 'Kuvake', //Icon
	'IMAGE' 				=> 'Kuva', //Image
	'INLINE' 				=> 'Kaksivaiheinen', //In-line
	'INSTALL' 				=> 'Asenna', //Install
	'INSTALLATION' 			=> 'Asennus', //Installation
	'INSTALLATION_PATH' 	=> 'Asennuspolku', //Asennuspolku
	'INSTALLATION_URL' 		=> 'Asennuksen URL', //Asennuksen URL
	'INSTALLED' 			=> 'asennettu', //installed
	'INTRO' 				=> 'Intro', //Intro
	'INTRO_PAGE' 			=> 'Introsivu', //Intro Page
	'INVALID_SIGNS' 		=> 'täytyy aloittaa kirjaimella tai se sisältää merkkejä, jotka eivät ole sallittuja', //must begin with a letter or has invalid signs
	'KEYWORDS' 				=> 'Avainsanat', //Keywords
	'LANGUAGE' 				=> 'Kieli', //Language
	'LAST_UPDATED_BY' 		=> 'Viimeisen muutoksen tehnyt ', //Last Updated By
	'LENGTH' 				=> 'Pituus', //Length
	'LEPTOKEN_LIFETIME'		=> 'Leptokenin voimassaoloaika', //Leptoken Lifetime
	'LEVEL' 				=> 'Taso', //Level
	'LIBRARY'				=> 'Kirjasto', //Library
	'LICENSE'				=> 'Lisenssi', //License
	'LINK' 					=> 'Linkki', //Link
	'LINUX_UNIX_BASED' 		=> 'Linux/Unix -pohjainen', //Linux/Unix based
	'LIST_OPTIONS' 			=> 'Valintalista', //List Options
	'LOGGED_IN' 			=> 'Kirjautuneena', //Logged-In
	'LOGIN' 				=> 'Kirjaudu', //Login
	'LONG' 					=> 'Pitkä', //Long
	'LONG_TEXT' 			=> 'Pitkä teksti', //Long Text
	'LOOP' 					=> 'Silmukka', //Loop
	'MAIN' 					=> 'Päälohko', //Main
	'MANAGE' 				=> 'Hallinnoi', //Manage
	'MANAGE_GROUPS' 		=> 'Ryhmien hallinnointi', //Manage Groups
	'MANAGE_USERS' 			=> 'Käyttäjien hallinnointi', //Manage Users
	'MATCH' 				=> 'Vastaavuus', //Match
	'MATCHING' 				=> 'Vastaava', //Matching
	'MAX_ATTEMPTS'		=> 'Sallittu epäonnistuneiden kirjautumisten määrä', //Allowed wrong login attempts
	'MAX_EXCERPT' 			=> 'Tulosten enimmäismäärä', //Max lines of excerpt
	'MAX_SUBMISSIONS_PER_HOUR' => 'Lisäysten enimmäismäärä tunnissa', //Max. Submissions Per Hour
	'MEDIA_DIRECTORY' 		=> 'Mediahakemisto', //Media Directory
	'MENU' 					=> 'Valikko', //Menu
	'MENU_ICON_0' 			=> 'Valikkokuvake normaalitilassa', //Menu-Icon normal
	'MENU_ICON_1' 			=> 'Valikkokuvake hiirellä osoitettaessa', //Menu-Icon hover
	'MENU_TITLE' 			=> 'Valikkoteksti', //Menu Title
	'MESSAGE' 				=> 'Viesti', //Message
	'MODIFY' 				=> 'Muokkaa', //Modify
	'MODIFY_CONTENT' 		=> 'Muokkaa sisältöä', //Modify Content
	'MODIFY_SETTINGS' 		=> 'Muokkaa asetuksia', //Modify Settings
	'MODULE_ORDER' 			=> 'Moduulien hakujärjestys', //Module-order for searching
	'MODULE_PERMISSIONS' 	=> 'Moduulien käyttöoikeudet', //Module Permissions
	'MORE' 					=> 'Lisää...', //More
	'MOVE_DOWN' 			=> 'Siirrä alas', //Move Down
	'MOVE_UP' 				=> 'Siirrä ylös', //Move Up
	'MULTIPLE_MENUS' 		=> 'Useita valikkoja', //Multiple Menus
	'MULTISELECT' 			=> 'Monivalinta', //Multi-select
	'NAME' 					=> 'Nimi', //Name
	'NEED_CURRENT_PASSWORD' => 'varmista antamalla voimassa oleva salasanasi', //confirm with current password
	'NEED_PASSWORD_TO_CONFIRM' => 'Ole hyvä ja varmista muutokset antamalla voimassa oleva salasanasi', //Please confirm the changes with your current password
	'NEED_TO_LOGIN' 		=> 'Tarvitaanko kirjautuminen?', //Need to log-in?
	'NEW_PASSWORD' 			=> 'Uusi salasana', //New Password
	'NEW_USER_HINT'			=> 'Käyttäjätunnuksen vähimmäispituus on %d merkkiä. Salasanan vähimmäispituus on %d merkkiä.', //Minimum length for user name: %d chars, Minimum length for Password: %d chars!
	'NEW_WINDOW' 			=> 'Uusi ikkuna', //New Window
	'NEXT' 					=> 'Seuraava', //Next
	'NEXT_PAGE' 			=> 'Seuraava sivu', //Next Page
	'NO' 					=> 'Ei', //No
	'NO_LEPTON_ADDON'  => 'Tätä liitännäistä ei voi käyttää LEPTONin kanssa', //This addon cannot be used with LEPTON
	'NONE' 					=> 'Ei yhtään', //None
	'NONE_FOUND' 			=> 'Ei löytyneitä', //None Found
	'NOT_FOUND' 			=> 'Ei löytynyt', //Not Found
	'NOT_INSTALLED' 		=> 'ei asennettu', //not installed
	'NO_RESULTS' 			=> 'Ei tuloksia', //No Results
	'OF' 					=> '/', //Of
	'ON' 					=> 'Päällä', //On
	'OPEN' 					=> 'Avaa', //Open
	'OPTION' 				=> 'Vaihtoehto', //Option
	'OTHERS' 				=> 'Muut', //Others
	'OUT_OF' 				=> '/', //Out Of
	'OVERWRITE_EXISTING' 	=> 'Korvaa nykyinen', //Overwrite existing
	'PAGE' 					=> 'Sivu', //Page
	'PAGES_DIRECTORY' 		=> 'Sivuhakemisto', //Pages Directory
	'PAGES_PERMISSION' 		=> 'Sivujen käyttöoikeus', //Pages Permission
	'PAGES_PERMISSIONS' 	=> 'Sivujen käyttöoikeudet', //Pages Permissions
	'PAGE_EXTENSION' 		=> 'Sivun tiedostotyyppi', //Page Extension
	'PAGE_ICON' 			=> 'Sivun kuvake', //Page Image
	'PAGE_ID'      => 'Sivun ID', //Page ID
	'PAGE_LANGUAGES' 		=> 'Sivun kielet', //Page Languages
	'PAGE_LEVEL_LIMIT' 		=> 'Sivun tasojen enimmäismäärä', //Page Level Limit
	'PAGE_SPACER' 			=> 'Sivun erotinmerkki', //Page Spacer
	'PAGE_TITLE' 			=> 'Sivun otsikko', //Page Title
	'PAGE_TRASH' 			=> 'Sivujen arkistointi', //Page Trash
	'PARENT' 				=> 'Isäntä', //Parent
	'PASSWORD' 				=> 'Salasana', //Password
	'PATH' 					=> 'Polku', //Path
	'PHP_ERROR_LEVEL' 		=> 'PHP-virheilmoitusten taso', //PHP Error Reporting Level
	'PLEASE_LOGIN' 			=> 'Ole hyvä ja kirjaudu', //Please login
	'PLEASE_SELECT' 		=> 'Ole hyvä ja valitse', //Please select
	'POST' 					=> 'Viesti', //Post
	'POSTS_PER_PAGE' 		=> 'Viestejä sivulla', //Posts Per Page
	'POST_FOOTER' 			=> 'Viestin alatunniste', //Post Footer
	'POST_HEADER' 			=> 'Viestin ylätunniste', //Post Header
	'PREVIOUS' 				=> 'Edellinen', //Previous
	'PREVIOUS_PAGE' 		=> 'Edellinen sivu', //Previous Page
	'PRIVATE' 				=> 'Yksityinen', //Private
	'PRIVATE_VIEWERS' 		=> 'Yksityiset kyttäjät', //Private Viewers
	'PROFILES_EDIT' 		=> 'Muokkaa profiilia', //Change the profile
	'PUBLIC' 				=> 'Julkinen', //Public
	'PUBL_END_DATE' 		=> 'Julkaisun poistopäivä', //End date
	'PUBL_START_DATE' 		=> 'Julkaisupäivä', //Start date
	'RADIO_BUTTON_GROUP' 	=> 'Valintapainikeryhmä', //Radio Button Group
	'READ' 					=> 'Lue', //Read
	'READ_MORE' 			=> 'Lue lisää...', //Read More
	'REDIRECT_AFTER' 		=> 'Uudelleenohjaa kun kulunut', //Redirect after
	'REGISTERED' 			=> 'Rekisteröitynyt', //Registered
	'REGISTERED_VIEWERS' 	=> 'Rekisteröityneet käyttäjät', //Registered Viewers
	'REGISTERED_CONTENT'	=> 'Tähän osioon pääsevät vain sivustolle rekisteröityneet käyttäjät', //Only registered visitors of this website have access to this content
	'RELOAD' 				=> 'Lataa uudelleen', //Reload
	'REMEMBER_ME' 			=> 'Muista minut', //Remember Me
	'RENAME' 				=> 'Nimeä uudelleen', //Rename
	'RENAME_FILES_ON_UPLOAD' => 'Uudelleennimeä ladatut tiedostot', //Rename Files On Upload
	'REQUIRED' 				=> 'Pakollinen', //Required
	'REQUIREMENT' 			=> 'Vaatimus', //Requirement
	'RESET' 				=> 'Palauta alkutila', //Reset
	'RESIZE' 				=> 'Muuta kokoa', //Re-size
	'RESIZE_IMAGE_TO' 		=> 'Aseta kuvan uudeksi kooksi', //Resize Image To
	'RESTORE' 				=> 'Palauta', //Restore
	'RESTORE_DATABASE' 		=> 'Palauta tietokanta varmuuskopiosta', //Restore Database
	'RESTORE_MEDIA' 		=> 'Palauta Mediatiedostot varmuuskopiosta', //Restore Media
	'RESULTS' 				=> 'Tulokset', //Results
	'RESULTS_FOOTER' 		=> 'Tulosten alatunniste', //Results Footer
	'RESULTS_FOR' 			=> 'Tulokset kyselylle', //Results For
	'RESULTS_HEADER' 		=> 'Tulosten ylätunniste', //Results Header
	'RESULTS_LOOP' 			=> 'Tulossilmukka', //Results Loop
	'RETYPE_NEW_PASSWORD' 	=> 'Kirjoita vielä uusi salasanasi', //Re-type New Password
	'RETYPE_PASSWORD' 		=> 'Kirjoita salasanasi uudelleen', //Re-type Password
	'SAME_WINDOW' 			=> 'Sama ikkuna', //Same Window
	'SAVE' 					=> 'Tallenna', //Save
	'SEARCH' 				=> 'Haku', //Search
	'SEARCH_FOR'  			=> 'Hakuehto', //Search by
	'SEARCHING' 			=> 'Haku käynnissä', //Searching
	'SECTION' 				=> 'Lohko', //Section
	'SECTION_BLOCKS' 		=> 'Lohkon osat', //Section Blocks
	'SECTION_ID' 			=> 'Lohkon ID', //Sektion ID
	'SEC_ANCHOR' 			=> 'Lohkon ankkuriteksti', //Section-Anchor text
	'SELECT_BOX' 			=> 'Valintalista', //Select Box
	'SEND_DETAILS' 			=> 'Lähetä tiedot', //Send Details
	'SEPARATE' 				=> 'Erottele', //Separate
	'SEPERATOR' 			=> 'Erotin', //Separator
	'SERVER_EMAIL' 			=> 'Palvelimen sähköposti', //Server Email
	'SERVER_OPERATING_SYSTEM' => 'Palvelimen käyttöjärjestelmä', //Server Operating System
	'SESSION_IDENTIFIER' 	=> 'Istunnon tunniste', //Session Identifier
	'SETTINGS' 				=> 'Asetukset', //Settings
	'SHORT' 				=> 'Lyhyt', //Short
	'SHORT_TEXT' 			=> 'Lyhyt teksti', //Short Text
	'SHOW' 					=> 'Näytä', //Show
	'SHOW_ADVANCED' 		=> 'Näytä lisäasetukset', //Show Advanced Options
	'SIGNUP' 				=> 'Rekisteröityminen', //Sign-up
	'SIZE' 					=> 'Koko', //Size
	'SMART_LOGIN' 			=> 'älykäs kirjautuminen', //Smart Login
	'START' 				=> 'Aloita', //Start
	'START_PUBLISHING' 		=> 'Aloita julkaisu', //Start Publishing
	'SUBJECT' 				=> 'Aihe', //Subject
	'SUBMISSIONS' 			=> 'Tallennetut vastaukset', //Submissions
	'SUBMISSIONS_STORED_IN_DATABASE' => 'Tallennettu tietokantaan', //Submissions Stored In Database
	'SUBMISSION_ID' 		=> 'Tallennuksen ID', //Submission ID
	'SUBMITTED' 			=> 'Tallennusaika', //Submitted
	'SUCCESS' 				=> 'Onnistui', //Success
	'SYSTEM_DEFAULT' 		=> 'Järjestelmän oletusarvo', //System Default
	'SYSTEM_PERMISSIONS' 	=> 'Järjestelmän käyttöoikeudet', //System Permissions
	'TABLE_PREFIX' 			=> 'Tietokantataulun etuliite', //Table prefix
	'TARGET' 				=> 'Kohde', //Target
	'TARGET_FOLDER' 		=> 'Kohdekansio', //Target folder
	'TEMPLATE' 				=> 'Sivumalli', //Template
	'TEMPLATE_PERMISSIONS' 	=> 'Sivumallien käyttöoikeudet', //Template Permissions
	'TEXT' 					=> 'Teksti', //Text
	'TEXTAREA' 				=> 'Tekstialue', //Textarea
	'TEXTFIELD' 			=> 'Tekstikenttä', //Textfield
	'THEME' 				=> 'Hallintanäkymän sivumalli', //Backend-Theme
	'TIME' 					=> 'Aika', //Time
	'TIMEZONE' 				=> 'Aikavyöhyke', //Timezone
	'TIME_FORMAT' 			=> 'Ajan esitysmuoto', //Time Format
	'TIME_LIMIT' 			=> 'Enimmäisaika moduulikohtaiselle tulosten etsinnälle', //Max time to gather excerpts per module
	'TITLE' 				=> 'Otsikko', //Title
	'TO' 					=> 'Kohteeseen', //To
	'TOP_FRAME' 			=> 'Ylin kehys', //Top Frame
	'TRASH_EMPTIED' 		=> 'Roskakori on tyhjennetty', //Trash Emptied
	'TXT_EDIT_CSS_FILE' 	=> 'Muokkaa CSS-määrityksiä alla olevalla tekstialueella.', //Edit the CSS definitions in the textarea below.
	'TYPE' 					=> 'Tyyppi', //Type
	'UNINSTALL' 			=> 'Poista asennus', //Uninstall
	'UNKNOWN' 				=> 'Tuntematon', //Unknown
	'UNLIMITED' 			=> 'Rajoittamaton', //Unlimited
	'UNZIP_FILE' 			=> 'Lataa ja pura zip-tiedosto', //Upload and unpack a zip archive
	'UP' 					=> 'Ylös', //Up
	'UPGRADE' 				=> 'Päivitä', //Upgrade
	'UPLOAD_FILES' 			=> 'Lataa tiedosto(ja) palvelimelle', //Upload File(s)
	'URL' 					=> 'URL', //URL
	'USER' 					=> 'Käyttäjä', //User
	'USERNAME' 				=> 'Käyttäjätunnus', //Username
	'USERS_ACTIVE' 			=> 'Käyttäjätunnus on käytössä', //User is set active
	'USERS_CAN_SELFDELETE' 	=> 'Käyttäjä voi poistaa itsensä', //User can delete himself
	'USERS_CHANGE_SETTINGS' => 'Käyttäjä voi muuttaa omia asetuksiaan', //User can change his own settings
	'USERS_DELETED' 		=> 'Käyttäjätunnus on merkitty poistetuksi', //User is marked as deleted
	'USERS_FLAGS' 			=> 'Käyttäjäoptiot', //User-Flags
	'USERS_PROFILE_ALLOWED' => 'Käyttäjä voi luoda laajennetun profiilin', //User can create extended profile
	'VERIFICATION' 			=> 'Varmistus', //Verification
	'VERSION' 				=> 'Versio', //Version
	'VIEW' 					=> 'Näytä', //View
	'VIEW_DELETED_PAGES' 	=> 'Näytä roskakorissa olevat sivut', //View Deleted Pages
	'VIEW_DETAILS' 			=> 'Näytä yksityiskohdat', //View Details
	'VISIBILITY' 			=> 'Näkyvyys', //Visibility
	'MAILER_DEFAULT_SENDER_MAIL' => 'Lähettäjän oletussähköpostiosoite', //Default From Mail
	'MAILER_DEFAULT_SENDER_NAME' => 'Lähettäjän oletusnimi', //Default From Name
	'MAILER_DEFAULT_SETTINGS_NOTICE' => 'Ole hyvä ja määrittele lähettäjän sähköpostiosoitteelle ja nimelle oletusarvot. Lähetysosoitteeksi suosittelemme muotoa: <strong>admin@omadomain.com</strong>. Jotkin postipalveluiden tarjoajat (esim. <em>mail.com</em>) saattavat roskapostitusten välttämiseksi hylätä sähköpostit, joiden lähettäjän osoite on muotoa <em>name@mail.com</em> silloin kun ne välitetään ulkomaisten palvelimien kautta.<br /><br />Oletusarvot ovat käytössä vain silloin kun LEPTONissa ei ole määritelty muita arvoja. Jos palvelimessasi on <acronym title="Simple mail transfer protocol">SMTP</acronym>-tuki, saatat haluta käyttää sitä.',
									//'Please specify a default "FROM" address and "SENDER" name below. It is recommended to use a FROM address like: <strong>admin@yourdomain.com</strong>. Some mail provider (e.g. <em>mail.com</em>) may reject mails with a FROM: address like <em>name@mail.com</em> sent via a foreign relay to avoid spam.<br /><br />The default values are only used if no other values are specified by LEPTON. If your server supports <acronym title="Simple mail transfer protocol">SMTP</acronym>, you may want use this option for outgoing mails.'
	'MAILER_FUNCTION' 	=> 'Sähköpostin käsittelytapa', //Mail Routine
	'MAILER_NOTICE' 		=> '<strong>SMTP-palvelinasetukset:</strong><br />Alla olevia asetuksia tarvitaan vain kun käytät sähköpostien lähettämiseen <acronym title="Simple mail transfer protocol">SMTP</acronym>-protokollaa. Jos et ole varma SMTP-palvelimesta tai sen asetuksista, on varminta käyttää sähköpostin oletuskäsittelytapaa: PHP MAIL.',
									//'<strong>SMTP Mailer Settings:</strong><br />The settings below are only required if you want to send mails via <acronym title="Simple mail transfer protocol">SMTP</acronym>. If you do not know your SMTP host or you are not sure about the required settings, simply stay with the default mail routine: PHP MAIL.'
	'MAILER_PHP' 			=> 'PHP MAIL', //PHP MAIL
	'MAILER_SEND_TESTMAIL' => 'Lähetä testiposti', //Send test eMail
	'MAILER_SMTP' 		=> 'SMTP', //SMTP
	'MAILER_SMTP_AUTH' 	=> 'SMTP-todennus', //SMTP Authentification
	'MAILER_SMTP_AUTH_NOTICE' => 'aseta päälle vain, jos SMTP-palvelimesi vaatii autentikointia', //only activate if your SMTP host requires authentification
	'MAILER_SMTP_HOST' 	=> 'SMTP-palvelin', //SMTP Host
	'MAILER_SMTP_PASSWORD' => 'SMTP-salasana', //SMTP Password
	'MAILER_SMTP_USERNAME' => 'SMTP-käyttäjätunnus', //SMTP Username
  'MAILER_TESTMAIL_FAILED' => 'Testipostin lähetys epäonnistui! Tarkista postiasetukset!', //The test eMail could not be sent! Please check your settings!
	'MAILER_TESTMAIL_SUCCESS' => 'Testiposti lähetettiin onnistuneesti. Tarkista sähköpostisi saapuneiden postien kansio.', //The test eMail was sent successfully. Please check your inbox.
  'MAILER_TESTMAIL_TEXT' => 'Tämä on testiposti: PHP-postitus on toimintakunnossa', //This is the required test mail: php mailer is working
	'WEBSITE' 				=> 'Sivusto', //Website
	'WEBSITE_DESCRIPTION' 	=> 'Sivuston kuvaus', //Website Description
	'WEBSITE_FOOTER' 		=> 'Sivuston alatunniste', //Website Footer
	'WEBSITE_HEADER' 		=> 'Sivuston ylätunniste', //Website Header
	'WEBSITE_KEYWORDS' 		=> 'Sivuston avainsanat', //Website Keywords
	'WEBSITE_TITLE' 		=> 'Sivuston otsikko', //Website Title
	'WELCOME_BACK' 			=> 'Tervetuloa takaisin', //Welcome back
	'WIDTH' 				=> 'Leveys', //Width
	'WINDOW' 				=> 'Ikkuna', //Window
	'WINDOWS' 				=> 'Windows', //Windows
	'WORLD_WRITEABLE_FILE_PERMISSIONS' => 'Avoimet tiedostojen kirjoitusoikeudet', //World-writeable file permissions
	'WRITE' 				=> 'Kirjoita', //Write
	'WYSIWYG_EDITOR' 		=> 'WYSIWYG-editori',  //WYSIWYG Editor
	'WYSIWYG_STYLE'	 		=> 'WYSIWYG-tyyli', //WYSIWYG Style
	'YES' 					=> 'Kyllä', //Yes
	'BASICS'	=> array(
		'day'		=> "päivä",		# day, singular
		'day_pl'	=> "päivää",	# day, plural
		'hour'		=> "tunti", 	# hour, singular
		'hour_pl'	=> "tuntia",	# hour, plural
		'minute'	=> "minuutti",	# minute, singular
		'minute_pl'	=> "minuuttia",	# minute, plural
	)
); // $TEXT

$HEADING = array(
	'ADDON_PRECHECK_FAILED' => 'Liitännäisen minimivaatimukset eivät täyty', //Add-On requirements not met
	'ADD_CHILD_PAGE' 		=> 'Lisää alisivu', //Add child page
	'ADD_GROUP' 			=> 'Lisää ryhmä', //Add Group
	'ADD_GROUPS' 			=> 'Lisää ryhmiä', //Add Groups
	'ADD_HEADING' 			=> 'Lisää otsikko', //Add Heading
	'ADD_PAGE' 				=> 'Lisää sivu', //Add Page
	'ADD_USER' 				=> 'Lisää käyttäjä', //Add User
	'ADMINISTRATION_TOOLS' 	=> 'Hallinnointityökalut', //Administration Tools
	'BROWSE_MEDIA' 			=> 'Selaa Mediakansiota', //Browse Media
	'CREATE_FOLDER' 		=> 'Luo uusi kansio', //Create Folder
	'DEFAULT_SETTINGS' 		=> 'Oletusasetukset', //Default settings
	'DELETED_PAGES' 		=> 'Poistetut sivut', //Deleted Pages
	'FILESYSTEM_SETTINGS' 	=> 'Tiedostojärjestelmän asetukset', //Filesystem Settings
	'GENERAL_SETTINGS' 		=> 'Yleiset asetukset', //General settings
	'INSTALL_LANGUAGE' 		=> 'Asenna kieli', //Install Language
	'INSTALL_MODULE' 		=> 'Asenna moduuli', //Install Module
	'INSTALL_TEMPLATE' 		=> 'Asenna sivumalli', //Install Template
	'INVOKE_MODULE_FILES' 	=> 'Suorita moduuli manuaalisesti', //Execute module files manually
	'LANGUAGE_DETAILS' 		=> 'Kielen tiedot', //Language Details
	'MANAGE_SECTIONS' 		=> 'Hallinnoi lohkoja', //Manage Sections
	'MODIFY_ADVANCED_PAGE_SETTINGS' => 'Muokkaa sivun lisäasetuksia', //Modify Advanced Page Settings
	'MODIFY_DELETE_GROUP' 	=> 'Muokkaa/Poista ryhmä', //Modify/Delete Group
	'MODIFY_DELETE_PAGE' 	=> 'Muokkaa/Poista sivu', //Modify/Delete Page
	'MODIFY_DELETE_USER' 	=> 'Muokkaa/Poista käyttäjä', //Modify/Delete User
	'MODIFY_GROUP' 			=> 'Muokkaa ryhmää', //Modify Group
	'MODIFY_GROUPS' 		=> 'Muokkaa ryhmiä', //Modify Groups
	'MODIFY_INTRO_PAGE' 	=> 'Muokkaa introsivua', //Modify Intro Page
	'MODIFY_PAGE' 			=> 'Muokkaa sivua', //Modify Page
	'MODIFY_PAGE_SETTINGS' 	=> 'Muokkaa sivun asetuksia', //Modify Page Settings
	'MODIFY_USER' 			=> 'Muokkaa käyttäjätietoja', //Modify User
	'MODULE_DETAILS' 		=> 'Moduulin tiedot', //Module Details
	'MY_EMAIL' 				=> 'Sähköpostiosoite', //My Email
	'MY_PASSWORD' 			=> 'Salasana', //My Password
	'MY_SETTINGS' 			=> 'Omat asetukset', //My Settings
	'SEARCH_SETTINGS' 		=> 'Hakuasetukset', //Search Settings
	'SEARCH_PAGE' 			=> 'Search Page',
	'SECURITY_SETTINGS'		=> 'Turva-asetukset', //Security Setting
	'SERVER_SETTINGS' 		=> 'Palvelinasetukset', //Server Settings
	'TEMPLATE_DETAILS' 		=> 'Sivumallin tiedot', //Template Details
	'UNINSTALL_LANGUAGE' 	=> 'Poista kieli', //Uninstall Language
	'UNINSTALL_MODULE' 		=> 'Poista moduuli', //Uninstall Module
	'UNINSTALL_TEMPLATE' 	=> 'Poista sivumalli', //Uninstall Template
	'UPGRADE_LANGUAGE' 		=> 'Kielen rekisteröinti/päivitys', //Language register/updating
	'UPLOAD_FILES' 			=> 'Lataa tiedosto(ja) palvelimelle', //Upload File(s)
	'VISIBILITY' 			=> 'Näkyvyys', //Visibility
	'MAILER_SETTINGS' 	=> 'Postiasetukset' //Mailer Settings
); // $HEADING

$MESSAGE = array(
	'ADDON_ERROR_RELOAD' 	=> 'Liitännäisen päivityksessä tapahtui virhe', //Error while updating the Add-On information.
	'ADDON_GROUPS_MARKALL' => 'Valitse kaikki/poista valinnat', //Mark / unmark all
	'ADDON_LANGUAGES_RELOADED' => 'Kielten lataus onnistui', //Languages reloaded successfully
	'ADDON_MANUAL_FTP_LANGUAGE' => '<strong>HUOMIO!</strong> Tietoturvasyistä kielitiedostot tulee ladata FTP:tä käyttäen kansioon /languages/ ja rekisteröidä tai päivittää Päivitä -toiminnolla.',
								// <strong>ATTENTION!</strong> For safety reasons uploading languages files in the folder/languages/ only by FTP and use the Upgrade function for registering or updating.
	'ADDON_MANUAL_FTP_WARNING' => 'Varoitus: Moduuliin liittyvät tietokantataulut tyhjennetään. ', //Warning: Existing module database entries will get lost.
	'ADDON_MANUAL_INSTALLATION' => 'Uusien moduulien lataamista FTP:llä ei suositella, koska moduulien asennukseen liittyvät toiminnot <tt>install</tt>, <tt>upgrade</tt> ja <tt>uninstall</tt> eivät tällöin tapahdu automaattisesti. Manuaalisesti asennettuna moduulin toiminta tai sen poistaminen välttämättä toimi suunnitellusti.<br /><br />Alla voit erityistapauksessa käynnistää FTP:llä ladattujen moduulien toiminnot manuaalisesti.',
								// When modules are uploaded via FTP (not recommended), the module installation functions <tt>install</tt>, <tt>upgrade</tt> or <tt>uninstall</tt> will not be executed automatically. Those modules may not work correct or do not uninstall properly.<br /><br />You can execute the module functions manually for modules uploaded via FTP below.
	'ADDON_MANUAL_INSTALLATION_WARNING' => 'Varoitus: Moduuliin liittyvät tietokantataulut tyhjennetään. Käytä tätä toimintoa vain, jos olet ladannut moduulin FTP:llä ja havaitset siinä ongelmia.',
								// Warning: Existing module database entries will get lost. Only use this option if you experience problems with modules uploaded via FTP.
	'ADDON_MANUAL_RELOAD_WARNING' => 'Varoitus: Moduuliin liittyvät tietokantataulut tyhjennetään. ', //Warning: Existing module database entries will get lost.
	'ADDON_MODULES_RELOADED' => 'Moduulien lataus onnistui', // Modules reloaded successfully
	'ADDON_PRECHECK_FAILED' => 'Liitännäisen asentaminen epäonnistui. Järjestelmä ei täytä liitännäisen minimivaatimuksia. Korjaa alla mainitut epäkohdat liitännäisen oikean toiminnan varmistamiseksi:',
								//Add-on installation failed. Your system does not fulfill the requirements of this Add-on. To make this Add-on working on your system, please fix the issues summarized below.
	'ADDON_RELOAD' 			=> 'Päivitä tietokanta liitännäisen tiedoilla (esim. kun liitännäinen on ladattu FTP:llä)', //Update database with information from Add-on files (e.g. after FTP upload).
	'ADDON_TEMPLATES_RELOADED' => 'Sivumallien lataus onnistui', // Templates reloaded successfully
	'ADMIN_INSUFFICIENT_PRIVELLIGES' => 'Käyttäjäoikeutesi eivät riitä', // Insufficient privelliges to be here
	'FORGOT_PASS_ALREADY_RESET' => 'Salasanan resetointi on mahdollista korkeintaan kerran tunnissa', // Password cannot be reset more than once per hour, sorry
	'FORGOT_PASS_CANNOT_EMAIL' => 'Salasanan lähettäminen sähköpostitse ei onnistu. Ota yhteyttä sivuston ylläpitoon', // Unable to email password, please contact system administrator
	'FORGOT_PASS_EMAIL_NOT_FOUND' => 'Syöttämääsi sähköpostiosoitetta ei löydy järjestelmästä', // The email that you entered cannot be found in the database
	'FORGOT_PASS_NO_DATA' 	=> 'Ole hyvä ja kirjoita sähköpostiosoitteesi alla olevaan kenttään', // Please enter your email address below
	'FORGOT_PASS_PASSWORD_RESET' => 'Käyttäjätunnus ja salasana on lähetetty sähköpostiosoitteeseesi', // Your username and password have been sent to your email address
	'FORGOT_CONFIRM_OLD' 	=> 'Sorry, you are too late, link is disabled',	
	'FORGOT_PASS_PASSWORD_CONFIRM' => 'You want to reset your password. Please use this link to enter your new password.<br /> If you do not want to reset your password please ignore this mail.<br /><br /><a href="%s">%s</a><br /><br /> Thanks',	
	'FORGOT_PASSWORD_SUCCESS' 	=> 'Hello %s,<br /><br />just for your information:<br /><br />your password has been successfully modified.',	
	'FRONTEND_SORRY_NO_ACTIVE_SECTIONS' => 'Sivulla ei ole voimassa olevaa sisältöä', // Sorry, no active content to display
	'FRONTEND_SORRY_NO_VIEWING_PERMISSIONS' => 'Sinulla ei valitettavasti ole riittäviä oikeuksia tälle sivulle', //Sorry, you do not have permissions to view this page
	'GENERIC_ALREADY_INSTALLED' => 'Asennettu aiemmin', // Already installed
	'GENERIC_BAD_PERMISSIONS' => 'Kohdekansioon kirjoittaminen ei onnistu', // Unable to write to the target directory
	'GENERIC_CANNOT_UNINSTALL' => 'Poistaminen ei onnistu', // Cannot uninstall
	'GENERIC_CANNOT_UNINSTALL_IN_USE' => 'Poistaminen ei onnistu: valittu tiedosto on käytössä', // Cannot Uninstall: the selected file is in use
	'GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL' => '<br /><br />{{type}} <b>{{type_name}}</b> ei ole poistettavissa, koska se on edelleen käytössä {{pages}}.<br /><br />',
								// <br /><br />{{type}} <b>{{type_name}}</b> could not be uninstalled, because it is still in use on {{pages}}.<br /><br />
	'GENERIC_CANNOT_UNINSTALL_IN_USE_TMPL_PAGES' => 'sivulla;sivuilla', // this page;these pages
	'GENERIC_CANNOT_UNINSTALL_IS_DEFAULT_TEMPLATE' => 'Sivumallia <b>{{name}}</b> ei voi poistaa, koska se on sivuston oletusmalli!',
								// Can\'t uninstall the template <b>{{name}}</b>, because it is the default template!
	'GENERIC_CANNOT_UNZIP' 	=> 'Tiedoston purkaminen ei onnistu', // Cannot unzip file
	'GENERIC_CANNOT_UPLOAD' => 'Tiedoston lataaminen ei onnistu', // Cannot upload file
	'GENERIC_COMPARE' 		=> ' onnistui', //  successfully
	'GENERIC_ERROR_OPENING_FILE' => 'Virhe avattaessa tiedostoa.', // Error opening file.
	'GENERIC_FAILED_COMPARE' => ' ei onnistunut', //  failed
	'GENERIC_FILE_TYPE' 	=> 'Huomioi, että palvelimelle ladattavan tiedostotyypin pitää olla:', // Please note that the file you upload must be of the following format:
	'GENERIC_FILE_TYPES' 	=> 'Huomioi, että palvelimelle ladattavan tiedostotyypin pitää olla jokin seuraavista:', // Please note that the file you upload must be in one of the following formats:
	'GENERIC_FILL_IN_ALL' 	=> 'Ole hyvä, palaa takaisin ja täytä kaikki kentät.', // Please go back and fill-in all fields
	'GENERIC_INSTALLED' 	=> 'Asennus onnistui', // Installed successfully
	'GENERIC_INVALID' 		=> 'Ladattu tiedosto on virheellinen', // The file you uploaded is invalid
	'GENERIC_INVALID_ADDON_FILE' => 'LEPTON-asennustiedosto on virheellinen. Ole hyvä ja tarkista pakattu tiedosto.', // Invalid LEPTON installation file. Please check the *.zip format.
	'GENERIC_INVALID_LANGUAGE_FILE' => 'LEPTON-kielitiedosto on virheellinen. Ole hyvä ja tarkista tekstitiedosto.',
	'GENERIC_IN_USE' 		=> ' mutta on käytössä kohteessa ', // but used in
	'GENERIC_MODULE_VERSION_ERROR' => 'Moduulia ei ole asennettu oikein!', // The module is not installed properly!
	'GENERIC_NOT_COMPARE' 	=> 'ei ole mahdollinen', //  not possibly
	'GENERIC_NOT_INSTALLED' => 'Ei asennettu', // Not installed
	'GENERIC_NOT_UPGRADED' 	=> 'Päivitys ei ole mahdollinen', // Actualization not possibly
	'GENERIC_PLEASE_BE_PATIENT' => 'Tämä saattaa kestää hetken. Ole hyvä ja odota!', // Please be patient, this might take a while.
	'GENERIC_PLEASE_CHECK_BACK_SOON' => 'Tervetuloa piakkoin uudelleen...', // Please check back soon...
	'GENERIC_SECURITY_ACCESS'	=> 'Käyttöoikeusrike!! Pääsy kielletty', // Security offense!! Access denied
	'GENERIC_SECURITY_OFFENSE'	=> 'Käyttöoikeusrike!! Tietojen tallennusta ei voitu tehdä!!', // Security offense!! data storing was refused!!
	'GENERIC_UNINSTALLED' 	=> 'Poisto onnistui', // Uninstalled successfully
	'GENERIC_UPGRADED' 		=> 'Päivitys onnistui', // Upgraded successfully
	'GENERIC_VERSION_COMPARE' => 'Version tarkistus', // Version comparison
	'GENERIC_VERSION_GT' 	=> 'Päivitys tarvitaan', // Upgrade necessary!
	'GENERIC_VERSION_LT' 	=> 'Palauta aiempi versio', // Downgrade
	'GENERIC_WEBSITE_UNDER_CONSTRUCTION' => 'Sivusto työn alla', //Website Under Construction
	'GROUPS_ADDED' 			=> 'Ryhmän lisäys onnistui', // Group added successfully
	'GROUPS_CONFIRM_DELETE' => 'Haluatko varmasti poistaa valitun ryhmän (ja kaikki ryhmään kuuluvat käyttäjät)?', // Are you sure you want to delete the selected group (and any users that belong to it)?
	'GROUPS_DELETED' => 'Ryhmän poistaminen onnistui', // Group deleted successfully
	'GROUPS_GROUP_NAME_BLANK' => 'Ryhmän nimi on tyhjä', // Group name is blank
	'GROUPS_GROUP_NAME_EXISTS' => 'Saman niminen ryhmä on jo olemassa', // Group name already exists
	'GROUPS_NO_GROUPS_FOUND' => 'Ei ryhmiä', // No groups found
	'GROUPS_SAVED' 			=> 'Ryhmän tallennus onnistui', // Group saved successfully
	'LANG_MISSING_PARTS_NOTICE' => 'Kielen asennus ei onnistu. Yksi tai useampia seuraavista muuttujista puuttuu:<br />language_code<br />language_name<br />language_version<br />language_license',
								// Language installation failed, one (or more) of the following variables is missing:<br />language_code<br />language_name<br />language_version<br />language_license
	'LOGIN_AUTHENTICATION_FAILED' => 'Käyttäjätunnus tai salasana on virheellinen', // Username or password incorrect
	'LOGIN_BOTH_BLANK' 		=> 'Ole hyvä ja kirjoita käyttäjätunnuksesi ja salasanasi', // Please enter your username and password below
	'LOGIN_PASSWORD_BLANK' 	=> 'Ole hyvä ja kirjoita salasana', // Please enter a password
	'LOGIN_PASSWORD_TOO_LONG' => 'Antamasi salasana on liian pitkä', // Supplied password to long
	'LOGIN_PASSWORD_TOO_SHORT' => 'Antamasi salasana on liian lyhyt', // Supplied password to short
	'LOGIN_USERNAME_BLANK' 	=> 'Ole hyvä ja kirjoita käyttäjätunnus', // Please enter a username
	'LOGIN_USERNAME_TOO_LONG' => 'Antamasi käyttäjätunnus on liian pitkä', // Supplied username to long
	'LOGIN_USERNAME_TOO_SHORT' => 'Antamasi käyttäjätunnus on liian lyhyt', // Supplied username to short
	'MEDIA_BLANK_EXTENSION' => 'Et antanut tiedoston päätettä',  // You did not enter a file extension
	'MEDIA_BLANK_NAME' 		=> 'Et antanut uutta nimeä', // You did not enter a new name
	'MEDIA_CANNOT_DELETE_DIR' => 'Valittua kansiota ei voi poistaa', // Cannot delete the selected folder
	'MEDIA_CANNOT_DELETE_FILE' => 'Valittua tiedostoa ei voi poistaa', // Cannot delete the selected file
	'MEDIA_CANNOT_RENAME' 	=> 'Uudelleennimeäminen epäonnistui', // Rename unsuccessful
	'MEDIA_CONFIRM_DELETE' 	=> 'Haluatko varmasti poistaa seuraavan tiedoston tai kansion?', // Are you sure you want to delete the following file or folder?
	'MEDIA_CONFIRM_DELETE_FILE'	=> 'Haluatko varmasti poistaa tiedoston {name}?', // Are you sure you want to delete file {name}?
	'MEDIA_CONFIRM_DELETE_DIR'	=> 'Haluatko varmasti poistaa kansion {name}?', // Are you sure you want to delete the directory {name}?
	'MEDIA_DELETED_DIR' 	=> 'Kansion poisto onnistui', // Folder deleted successfully
	'MEDIA_DELETED_FILE' 	=> 'Tiedoston poisto onnistui', //File deleted successfully
	'MEDIA_DIR_ACCESS_DENIED' => 'Annettua hakemistoa ei ole olemassa tai siihen ei ole tarvittavia oikeuksia.', // Specified directory does not exist or is not allowed.
	'MEDIA_DIR_DOES_NOT_EXIST' => 'Hakemistoa ei löydy', // Directory does not exist
	'MEDIA_DIR_DOT_DOT_SLASH' => 'Kansion nimessä ei saa olla ../', // Cannot include ../ in the folder name
	'MEDIA_DIR_EXISTS' 		=> 'Antamasi nimen mukainen kansio on jo olemassa', // A folder matching the name you entered already exists
	'MEDIA_DIR_MADE' 		=> 'Kansion luonti onnistui', // Folder created successfully
	'MEDIA_DIR_NOT_MADE' 	=> 'Kansiota ei voi luoda', // Unable to create folder
	'MEDIA_FILE_EXISTS' 	=> 'Antamasi nimen mukainen tiedosto on jo olemassa', // A file matching the name you entered already exists
	'MEDIA_FILE_NOT_FOUND' 	=> 'Tiedostoa ei löydy', // File not found
	'MEDIA_NAME_DOT_DOT_SLASH' => 'Nimessä ei saa olla ../', // Cannot include ../ in the name
	'MEDIA_NAME_INDEX_PHP' 	=> 'Index.php ei ole sallittu nimi', // Cannot use index.php as the name
	'MEDIA_NONE_FOUND' 		=> 'Nykyisestä kansiosta ei löydy mediatiedostoja', // No media found in the current folder
	'MEDIA_RENAMED' 		=> 'Uudelleennimeäminen onnistui', // Rename successful
	'MEDIA_SINGLE_UPLOADED' => ' tiedoston lataus onnistui', //  file was successfully uploaded
	'MEDIA_TARGET_DOT_DOT_SLASH' => 'Kohdekansion nimessä ei saa olla ../', // Cannot have ../ in the folder target
	'MEDIA_UPLOADED' 		=> ' tiedostojen lataus onnistui', //  files were successfully uploaded
	'MOD_MISSING_PARTS_NOTICE' => 'Moduulin "%s" asennus ei onnistu. Yksi tai useampia seuraavista muuttujista puuttuu:<br />module_directory<br />module_name<br />module_version<br />module_author<br />module_license<br />module_guid<br />module_function',
							// The installation of module "%s" failed, one (or more) of the following variables is missing:<br />module_directory<br />module_name<br />module_version<br />module_author<br />module_license<br />module_guid<br />module_function
	'MOD_FORM_EXCESS_SUBMISSIONS' => 'Tähän lomakkeeseen on liitetty tietty enimmäismäärä lähetyksiä saman tunnin aikana. Enimmäismäärä lomakkeen lähetyksiä on ylittynyt. Ole hyvä ja yritä myöhemmin uudelleen.', // Sorry, this form has been submitted too many times so far this hour. Please retry in the next hour.
	'MOD_FORM_INCORRECT_CAPTCHA' => 'Syöttämäsi lomakkeen varmistustunnus (Captcha) on virheellinen. Jos Captcha-varmistuksessa on ongelmia, voit ottaa yhteyttä sähköpostilla: <a href="mailto:'.SERVER_EMAIL.'">'.SERVER_EMAIL.'</a>',
							// The verification number (also known as Captcha) that you entered is incorrect. If you are having problems reading the Captcha, please email: <a href="mailto:'.SERVER_EMAIL.'">'.SERVER_EMAIL.'</a>
	'MOD_FORM_REQUIRED_FIELDS' => 'Seuraavin kenttiin tulee syöttää tieto', // You must enter details for the following fields
	'PAGES_ADDED' 			=> 'Sivun lisäys onnistui', // Page added successfully
	'PAGES_ADDED_HEADING' 	=> 'Sivun otsikon lisäys onnistui', // Page heading added successfully
	'PAGES_BLANK_MENU_TITLE' => 'Ole hyvä ja syötä valikkoteksti', // Please enter a menu title
	'PAGES_BLANK_PAGE_TITLE' => 'Ole hyvä ja syötä sivun otsikko', // Please enter a page title
	'PAGES_CANNOT_CREATE_ACCESS_FILE' => 'Suojaustiedoston luonti sivuhakemistoon (page) epäonnistui, käyttöoikeudet eivät riitä', //
	'PAGES_CANNOT_DELETE_ACCESS_FILE' => 'Suojaustiedoston poisto sivuhakemistosta (page) epäonnistui, käyttöoikeudet eivät riitä', //
	'PAGES_CANNOT_REORDER' 	=> 'Sivujen uudelleenjärjestely epäonnistui', // Error re-ordering page
	'PAGES_DELETED' 		=> 'Sivun poisto onnistui', // Page deleted successfully
	'PAGES_DELETE_CONFIRM' 	=> 'Haluatko varmasti poistaa valitun sivun «%s» (ja kaikki sen alisivut)', // Are you sure you want to delete the selected page «%s» (and all of its sub-pages)
	'PAGES_INSUFFICIENT_PERMISSIONS' => 'Käyttäjäoikeutesi eivät riitä tämän sivun muokkaamiseen', // You do not have permissions to modify this page
	'PAGES_DIRECTORY_EMPTY' 	=> 'Please delete directory manually!',
	'PAGES_DIRECTORY_NEW' 		=> 'Please create directory manually!',
	'PAGES_LAST_MODIFIED' 	=> 'Viimeksi muokannut', // Last modification by
	'PAGES_NOT_FOUND' 		=> 'Sivua ei löydy', // Page not found
	'PAGES_NOT_SAVED' 		=> 'Sivun tallennuksessa tapahtui virhe', // Error saving page
	'PAGES_PAGE_EXISTS' 	=> 'Sivu vastaavalla nimellä on jo olemassa', // A page with the same or similar title exists
	'PAGES_REORDERED' 		=> 'Sivujen uudelleenjärjestely onnistui', // Page re-ordered successfully
	'PAGES_RESTORED' 		=> 'Sivun palautus onnistui', // Page restored successfully
	'PAGES_RETURN_TO_PAGES' => 'Palaa sivuille', // Return to pages
	'PAGES_SAVED' 			=> 'Sivun tallennus onnistui', // Page saved successfully
	'PAGES_SAVED_SETTINGS' 	=> 'Sivun asetusten tallennus onnistui', // Page settings saved successfully
	'PAGES_SECTIONS_PROPERTIES_SAVED' => 'Lohkon asetusten tallennus onnistui', // Section properties saved successfully
	'PREFERENCES_CURRENT_PASSWORD_INCORRECT' => 'Syöttämäsi nykyinen salasana on virheellinen', // The (current) password you entered is incorrect
	'PREFERENCES_DETAILS_SAVED' => 'Tietojen tallennus onnistui', // Details saved successfully
	'PREFERENCES_EMAIL_UPDATED' => 'Sähköpostitietojen päivitys onnistui', // Email updated successfully
	'PREFERENCES_INVALID_CHARS' => 'Salasanassa on virheellisiä merkkejä. Salasanassa voi käyttää seuraavia merkkejä: a-z\A-Z\0-9\_\-\!\#\*\+ ', // Invalid password chars used, vailid chars are: a-z\A-Z\0-9\_\-\!\#\*\+
	'PREFERENCES_PASSWORD_CHANGED' => 'Salasanan vaihto onnistui', // Password changed successfully
	'PREFERENCES_PASSWORD_MATCH' => 'Passwords do not match',	
	'RECORD_MODIFIED_FAILED' => 'Tietueen päivityksessä tapahtui virhe.', // The change of the record has missed.
	'RECORD_MODIFIED_SAVED' => 'Muutetun tietueen päivitys onnistui', // The changed record was updated successfully.
	'RECORD_NEW_FAILED' 	=> 'Tietueen lisäyksessä tapahtui virhe.', // Adding a new record has missed.
	'RECORD_NEW_SAVED' 		=> 'Tietueen lisäys onnistui.', // New record was added successfully.
	'SETTINGS_MODE_SWITCH_WARNING' => 'Huom! Kaikki tallentamattomat muutokset katoavat kun painat tätä nappia', // Please Note: Pressing this button resets all unsaved changes
	'SETTINGS_SAVED' 		=> 'Asetusten tallennus onnistui', // Settings saved successfully
	'SETTINGS_UNABLE_OPEN_CONFIG' => 'Asetustiedoston avaaminen ei onnistu', // Unable to open the configuration file
	'SETTINGS_UNABLE_WRITE_CONFIG' => 'Asetustiedostoon ei pysty kirjoittamaan', // Cannot write to configuration file
	'SETTINGS_WORLD_WRITEABLE_WARNING' => 'Huom! Tätä suositellaan käytettäväksi vain testitarkoituksessa', // Please note: this is only recommended for testing environments
	'SIGNUP2_ADMIN_INFO' 	=> '
Sivustolle rekisteröityi uusi käyttäjä.

Käyttäjänimi: {LOGIN_NAME}
Käyttäjä-ID: {LOGIN_ID}
Sähköposti: {LOGIN_EMAIL}
IP-osoite: {LOGIN_IP}
Rekisteröitymispäivä: {SIGNUP_DATE}
--------------------------------------------
Tämä on automaattinen viesti järjestelmältä!

',
	'SIGNUP2_ADMIN_SUBJECT' => 'New user has signed up',
	'SIGNUP2_BODY_CONFIRM' => '
Hello {LOGIN_DISPLAY_NAME},

Signup is now complete.
Your account is active and you can login to \'{LOGIN_WEBSITE_TITLE}\'.

Regards
------------------------------------
This message was system built!

',
	'SIGNUP2_BODY_LOGIN_INFO' => '
Hello {LOGIN_DISPLAY_NAME},

You have registered on \'{LOGIN_WEBSITE_TITLE}\'.

To activate your account please use this link and enter a password.

<a href="{ENTER_PW_LINK}">{ENTER_PW_LINK}</a>

Thank you

Please:
if you have received this message by an error, please delete it immediately!
-------------------------------------
This message was system built!
',
	'SIGNUP2_SUBJECT_LOGIN_INFO' =>	 '
Tervehdys {LOGIN_DISPLAY_NAME},

Tervetuloa sivustollemme \'{LOGIN_WEBSITE_TITLE}\'.

Tunnuksesi sivustolle \'{LOGIN_WEBSITE_TITLE}\' ovat:
Käyttäjä: {LOGIN_NAME}
Salasana: {LOGIN_PASSWORD}

Terveisin

Voit poistaa viestin ilman lisätoimenpiteitä, jos
se on saapunut sinulle virheen tai erehdyksen vuoksi!
--------------------------------------------
Tämä on automaattinen viesti järjestelmältä!
',
//Hello {LOGIN_DISPLAY_NAME},
//
//Welcome to our \'{LOGIN_WEBSITE_TITLE}\'.
//
//Your \'{LOGIN_WEBSITE_TITLE}\' login details are:
//Username: {LOGIN_NAME}
//Password: {LOGIN_PASSWORD}
//
//Regards
//
//Please:
//if you have received this message by an error, please delete it immediately!
//-------------------------------------
//This message was automatic generated!
	'SIGNUP2_SUBJECT_LOGIN_INFO' => 'LEPTON -kirjautumistietosi...', // Your LEPTON login details...
	'SIGNUP_NO_EMAIL' 		=> 'Ole hyvä ja kirjoita sähköpostiosoitteesi', // You must enter an email address
	'START_CURRENT_USER' 	=> 'Olet tällä hetkellä kirjautuneena tunnuksella:', // You are currently logged in as:
	'START_INSTALL_DIR_EXISTS' => 'Varoitus! Asennushakemisto "/install" on edelleen olemassa! Tietoturvan vuoksi sen poistaminen on erittäin suositeltavaa.', // Warning, Installation Directory Still Exists!
	'START_WELCOME_MESSAGE' => 'Tervetuloa LEPTON-ylläpitoon', // Welcome to LEPTON Administration
	'STATUSFLAG_32'			=> 'Cannot delete User, User got statusflags 32 in table users.',	
	'SYSTEM_FUNCTION_DEPRECATED'=> 'Toimintoa <b>%s</b> ei enää tueta, käytä sen sijasta toimintoa <b>%s</b>!', // The function <b>%s</b> is deprecated, use the function <b>%s</b> instead!
	'SYSTEM_FUNCTION_NO_LONGER_SUPPORTED' => 'Toiminto <b>%s</b> on vanhentunut, eikä sitä enää tueta!', // The function <b>%s</b> is out of date and no longer supported!
	'SYSTEM_SETTING_NO_LONGER_SUPPORTED' => 'Asetus <b>%s</b> ei ole enää käytössä eikä sitä näin ollen oteta huomioon!', // The setting <b>%s</b> is no longer supported and will be ignored!
	'TEMPLATES_CHANGE_TEMPLATE_NOTICE' => 'Huom! Käytettävän sivumallin voi vaihtaa Asetukset-osiosta', // Please note: to change the template you must go to the Settings section
	'TEMPLATES_MISSING_PARTS_NOTICE' => 'Sivumallin asennus ei onnistu. Yksi tai useampia seuraavista muuttujista puuttuu:<br />template_directory<br />template_name<br />template_version<br />template_author<br />template_license<br />template_function',
								// Template installation failed, one (or more) of the following variables is missing:<br />template_directory<br />template_name<br />template_version<br />template_author<br />template_license<br />template_function ("theme" oder "template")
	'USERS_ADDED' 			=> 'Käyttäjän lisäys onnistui', // User added successfully
	'USERS_CANT_SELFDELETE' => 'Toimintoa ei voi suorittaa. Et voi poistaa itseäsi!', // Function rejected, You can not delete yourself!
	'USERS_CHANGING_PASSWORD' => 'Huom! älä muuta ylläolevia tietoja ellet halua muuttaa valitun käyttäjän salasanaa',
								// Please note: You should only enter values in the above fields if you wish to change this users password
	'USERS_CONFIRM_DELETE' 	=> 'Haluatko varmasti poistaa valitun käyttäjän?', // Are you sure you want to delete the selected user?
	'USERS_DELETED' 		=> 'Käyttäjän poisto onnistui', // User deleted successfully
	'USERS_EMAIL_TAKEN' 	=> 'Antamasi sähköpostiosoite on jo käytössä', // The email you entered is already in use
	'USERS_INVALID_EMAIL' 	=> 'Antamasi sähköpostiosoite on virheellinen', // The email address you entered is invalid
	'USERS_NAME_INVALID_CHARS' => 'Käyttäjänimessä on kirjaimia, jotka eivät ole sallittuja', // Invalid chars for username found
	'USERS_NO_GROUP' 		=> 'Ryhmää ei ole valittu', // No group was selected
	'USERS_PASSWORD_MISMATCH' => 'Antamasi salasanat ovat erilaiset', // The passwords you entered do not match
	'USERS_PASSWORD_TOO_SHORT' => 'Antamasi salasana on liian lyhyt', // The password you entered was too short
	'USERS_SAVED' 			=> 'Käyttäjän tallennus onnistui', // User saved successfully
	'USERS_USERNAME_TAKEN' 	=> 'Antamasi käyttäjätunnus on jo käytössä', // The username you entered is already taken
	'USERS_USERNAME_TOO_SHORT' => 'Antamasi käyttäjätunnus on liian lyhyt' // The username you entered was too short
); // $MESSAGE

$OVERVIEW = array(
	'ADMINTOOLS' 			=> 'LEPTON-järjestelmähallinnan työkalut', // Access the LEPTON administration tools...
	'GROUPS' 				=> 'Käyttäjäryhmien ja niiden oikeuksien ylläpito...', // Manage user groups and their system permissions...
	'HELP' 					=> 'Kysyttävää? Täältä löydät vastaukset...', // Got a questions? Find your answer...
	'LANGUAGES' 			=> 'Kielten ylläpito...', // Manage LEPTON languages...
	'MEDIA' 				=> 'Mediakansiossa olevien tiedostojen ylläpito...', // Manage files stored in the media folder...
	'MODULES' 				=> 'LEPTON-moduulien ylläpito...', // Manage LEPTON modules...
	'PAGES' 				=> 'Sivuston rakenteen ylläpito...', // Manage your websites pages...
	'PREFERENCES' 			=> 'Omien tietojen (sähköposti, salasana jn.) ylläpito...', // Change preferences such as email address, password, etc...
	'SETTINGS' 				=> 'LEPTON-sivuston asetusten ylläpito...', // Changes settings for LEPTON...
	'START' 				=> 'Ylläpitosivuston aloitusnäkymä', // Administration overview
	'TEMPLATES' 			=> 'Muokkaa sivuston ulkoasua erilaisten sivumallien avulla...', // Change the look and feel of your website with templates...
	'USERS' 				=> 'Sivuston käyttäjien ylläpito...', // Manage users who can log-in to LEPTON...
	'VIEW' 					=> 'Avaa sivusto uudessa ikkunassa...' // Quickly view and browse your website in a new window...
);

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