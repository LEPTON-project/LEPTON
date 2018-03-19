<?php

/**
 *	@module			addon_info
 *	@version		see info.php of this module
 *	@author			cms-lab
 *	@copyright		2017-2018 cms-lab
 *	@license		GNU General Public License
 *	@license_terms	please see info.php of this module 
 *	@platform		see info.php of this module
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


$module_directory	= 'addon_info';
$module_name		= 'Addon Info';
$module_function	= 'tool';
$module_version		= '1.0.0';
$module_platform	= '4.x';
$module_delete		=  true;
$module_author		= 'CMS-LAB';
$module_license		= '<a href="http://cms-lab.com/_documentation/addon_info/license.php" target="_blank">GNU General Public License</a>';
$module_license_terms	= '<a href="http://cms-lab.com/_documentation/addon_info/license.php" target="_blank">License terms</a>';
$module_description	= 'Get all addons listed on LEPAdoR.';
$module_guid		= 'ec2a7bfc-610d-4f82-b31b-41fafa7ce8ff';


?>
