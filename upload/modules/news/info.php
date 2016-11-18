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
 
$module_directory = 'news';
$module_name      = 'News';
$module_function  = 'page';
$module_version   = '3.7.3';
$module_platform  = '2.x';
$module_author    = 'Ryan Djurovich, Rob Smith, Christian M. Stefan, Jurgen Nijhuis, Dietrich Roland Pehlke (last)';
$module_license   = 'GNU General Public License';
$module_license_terms  = '-';
$module_guid      = '200a3816-e0f6-4fb9-aea8-8e7749896a34';
$module_description = 'This page type is designed for making a news page (including patch with backend pagination and image upload).';
$module_home      = 'http://lepton-cms.org';

  
?>