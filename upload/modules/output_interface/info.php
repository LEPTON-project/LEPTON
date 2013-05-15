<?php

 /**
 *  @module         outputInterface
 *  @version        see info.php of this module
 *  @authors        LEPTON project
 *  @copyright      2011-2013 LEPTON project 
 *  @license        see info.php of this module
 *  @license terms  see info.php of this module
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



$module_directory	= 'output_interface';
$module_name		= 'outputInterface';
$module_function	= 'snippet';
$module_version		= '0.1.1';
$module_status		= 'Beta';
$module_platform	= '1.0.0'; 
$module_delete 		= 'false';
$module_author		= 'Ralf Hertsch, Dietrich Roland Pehlke (last)';
$module_license		= 'GNU General Public License';
$module_description	= 'Frontend output interface for LEPTON CMS';
$module_home		= 'http://www.lepton-cms.org';
$module_guid		= 'C833EA29-0678-4E4E-9C24-C37F3D65A5C3';

?>
