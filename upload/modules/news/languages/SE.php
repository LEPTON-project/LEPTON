<?php

/**
 *  @module         news
 *  @version        see info.php of this module
 *  @author         Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos), LEPTON Project
 *  @copyright      2004-2010 Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos) 
 * 	@copyright      2010-2018 LEPTON Project 
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

//	Modul Description
$module_description = 'Den h&auml;r sidtypen &auml;r designad f&ouml;r att skapa en nyhetssida.';

$MOD_NEWS = array(
	//	Variables for the backend
	'SETTINGS'	=> 'Inst&auml;llningar';
	'CONFIRM_DELETE'	=> 'Are you sure you want to delete the news-text \n&quot;%s&quot;?',

	//	Variables for the frontend
	'TEXT_READ_MORE'	=> 'L&auml;s mer',
	'TEXT_POSTED_BY'	=> 'Postat av',
	'TEXT_ON'	=> 'den',
	'TEXT_LAST_CHANGED'	=> 'Senaste &auml;ndring',
	'TEXT_AT'	=> 'kl.',
	'TEXT_BACK'	=> 'Tillbaka',
	'TEXT_COMMENTS'	=> 'Kommentarer',
	'TEXT_COMMENT'	=> 'kommentar',
	'TEXT_ADD_COMMENT'	=> 'Kommentera',
	'TEXT_BY'	=> 'Av',
	'TEXT_PAGE_NOT_FOUND'	=> 'Sidan kunde inte hittas',
	'TEXT_UNKNOWN'	=> 'Guest',
	'TEXT_NO_COMMENT'	=> 'none available'
);
?>