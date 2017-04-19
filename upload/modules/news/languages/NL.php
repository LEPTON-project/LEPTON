<?php

/**
 *  @module         news
 *  @version        see info.php of this module
 *  @author         Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos), LEPTON Project
 *  @copyright      2004-2010 Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos) 
 * 	@copyright      2010-2017 LEPTON Project 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
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

// Modul Description
$module_description = 'Met deze module maak je een nieuwspagina.';

$MOD_NEWS	= array(
	// Variables for the backend
	'SETTINGS'	=> 'Instellingen van de Nieuwsmodule',
	'CONFIRM_DELETE'	=> 'Are you sure you want to delete the news-text \n&laquo;%s&raquo;?',

	// Variables for the frontend
	'TEXT_READ_MORE'	=> 'Lees verder',
	'TEXT_POSTED_BY'	=> 'Geplaatst door',
	'TEXT_ON'	=> 'op',
	'TEXT_LAST_CHANGED'	=> 'Laatst vernieuwd',
	'TEXT_AT'	=> 'om',
	'TEXT_BACK'	=> 'Terug',
	'TEXT_COMMENTS'	=> 'Reacties',
	'TEXT_COMMENT'	=> 'Reactie',
	'TEXT_ADD_COMMENT'	=> 'Toevoegen reactie',
	'TEXT_BY'	=> 'door',
	'TEXT_PAGE_NOT_FOUND'	=> 'Pagina niet gevonden',
	'TEXT_UNKNOWN'	=> 'Gast',
	'TEXT_NO_COMMENT'	=> 'niet aanwezig'
);
?>