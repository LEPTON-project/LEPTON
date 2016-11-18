<?php

/**
 *
 *	@module			quickform
 *	@version		see info.php of this module
 *	@authors		Ruud Eisinga, LEPTON project
 *	@copyright		2012-2017 Ruud Eisinga, LEPTON project
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 *	Based on the miniform addon
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {
	include(LEPTON_PATH.'/framework/class.secure.php');
} else {
	$root = "../";
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= "../";
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) {
		include($root.'/framework/class.secure.php');
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php 
 
$module_directory	= 'quickform';
$module_name		= 'QuickForm';
$module_function	= 'page';
$module_version		= '1.0.3';
$module_platform	= '2.x';
$module_author		= 'Ruud, LEPTON project';
$module_license		= '<a href="http://www.gnu.org/licenses/lgpl.html" target="_blank">lgpl</a>';
$module_license_terms	= '-';
$module_description	= 'This module allows you to create a quick and simple form without complicated settings using TWIG template engine.';
$module_guid		= '6d1a4304-88f0-4356-a1bf-7b5fcb69cf9f';

?>