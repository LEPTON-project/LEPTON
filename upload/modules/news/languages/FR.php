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

// Module Description
$module_description = 'Ce type de page est conçu &agrave faire une page de nouvelles.';

$MOD_NEWS = array(
	// Variables for the  backend
	'SETTINGS'	=> 'Configurations Nouvelles',
	'CONFIRM_DELETE'	=> 'Are you sure you want to delete the news-text \n&laquo;%s&raquo;?',

	// Variables for the frontend
	'TEXT_READ_MORE'	=> 'En savoir plus',
	'TEXT_POSTED_BY'	=> 'Post&eacute; par',
	'TEXT_ON'	=> '&agrave;',
	'TEXT_LAST_CHANGED'	=> 'Derni&egrave;re modification',
	'TEXT_AT'	=> '&agrave;',
	'TEXT_BACK'	=> 'Retour',
	'TEXT_COMMENTS'	=> 'Commentaires',
	'TEXT_COMMENT'	=> 'Commentaire',
	'TEXT_ADD_COMMENT'	=> 'Ajouter un commentaire',
	'TEXT_BY'	=> 'Par',
	'TEXT_PAGE_NOT_FOUND'	=> 'Page non trouv&eacute;e',
	'TEXT_UNKNOWN'	=> 'Invit&eacute;',
	'TEXT_NO_COMMENT'	=> 'none available'
);
?>