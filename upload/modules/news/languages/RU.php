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
$module_description = '&#1052;&#1086;&#1076;&#1091;&#1083;&#1100; &#1087;&#1088;&#1077;&#1076;&#1085;&#1072;&#1079;&#1085;&#1072;&#1095;&#1077;&#1085; &#1076;&#1083;&#1103; &#1089;&#1086;&#1079;&#1076;&#1072;&#1085;&#1080;&#1103; &#1083;&#1077;&#1085;&#1090;&#1099; &#1085;&#1086;&#1074;&#1086;&#1089;&#1090;&#1077;&#1081;';

$MOD_NEWS	= array(
	//	Variables for the backend
	'SETTINGS'	=> '&#1053;&#1072;&#1089;&#1090;&#1088;&#1086;&#1081;&#1082;&#1080; &#1085;&#1086;&#1074;&#1086;&#1089;&#1090;&#1085;&#1086;&#1081; &#1083;&#1077;&#1085;&#1090;&#1099;',
	'CONFIRM_DELETE'	=> 'Are you sure you want to delete the news-text \n&quot;%s&quot;?',

	//	Variables for the frontend
	'TEXT_READ_MORE'	=> '&#1063;&#1080;&#1090;&#1072;&#1090;&#1100; &#1076;&#1072;&#1083;&#1100;&#1096;&#1077;',
	'TEXT_POSTED_BY'	=> 'Posted by',
	'TEXT_ON'	=> 'on',
	'TEXT_LAST_CHANGED'	=> '&#1055;&#1086;&#1089;&#1083;&#1077;&#1076;&#1085;&#1077;&#1077; &#1086;&#1073;&#1085;&#1086;&#1074;&#1083;&#1077;&#1085;&#1080;&#1077;',
	'TEXT_AT'	=> 'at',
	'TEXT_BACK'	=> '&#1053;&#1072;&#1079;&#1072;&#1076;',
	'TEXT_COMMENTS'	=> '&#1050;&#1086;&#1084;&#1084;&#1077;&#1085;&#1090;&#1072;&#1088;&#1080;&#1080;',
	'TEXT_COMMENT'	=> '&#1050;&#1086;&#1084;&#1084;&#1077;&#1085;&#1090;&#1080;&#1088;&#1086;&#1074;&#1072;&#1090;&#1100;',
	'TEXT_ADD_COMMENT'	=> '&#1044;&#1086;&#1073;&#1072;&#1074;&#1080;&#1090;&#1100; &#1050;&#1086;&#1084;&#1084;&#1077;&#1085;&#1090;&#1080;&#1088;&#1086;&#1074;&#1072;&#1090;&#1100;',
	'TEXT_BY'	=> 'By',
	'TEXT_PAGE_NOT_FOUND'	=> 'Page not found',
	'TEXT_UNKNOWN'	=> 'Guest',
	'TEXT_NO_COMMENT'	=> 'none available'
);

?>