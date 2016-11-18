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

//	Modul Description
$module_description = 'This page type is designed for making a news page.';

$MOD_NEWS = array (
	//	Variables for the backend
	'SETTINGS' => 'News Settings',
	'CONFIRM_DELETE'	=> 'Are you sure you want to delete the news-text \n&laquo;%s&raquo;?',
	
	//	Variables for the frontend
	'TEXT_READ_MORE' => 'Read More',
	'TEXT_POSTED_BY' => 'Posted by',
	'TEXT_ON' => 'on',
	'TEXT_LAST_CHANGED' => 'Last changed',
	'TEXT_AT' => 'at',
	'TEXT_BACK' => 'Back',
	'TEXT_COMMENTS' => 'Comments',
	'TEXT_COMMENT' => 'Comment',
	'TEXT_ADD_COMMENT' => 'Add Comment',
	'TEXT_BY' => 'By',
	'TEXT_PAGE_NOT_FOUND' => 'Page not found',
	'TEXT_UNKNOWN' => 'Guest',
	'TEXT_NO_COMMENT' => 'none available'
);
?>