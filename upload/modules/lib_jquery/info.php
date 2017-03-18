<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 *
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
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



$module_directory = 'lib_jquery';
$module_name      = 'jQuery Initial Library';
$module_function  = 'library';
$module_version   = '0.3.2.1';
$module_platform  = '3.x';
$module_delete	  =  false;
$module_author    = 'LEPTON Project';
$module_license   = 'GNU General Public License';
$module_license_terms   = '-';
$module_description = 'This module installs basic files of jQuery JavaScript Library. You may use it as a lib for your own JavaScripts and modules.';
$module_guid      = '8FB09FFD-B11C-4B75-984E-F54082B4DEEA';
$module_home      = ' https://www.lepton-cms.org';

?>