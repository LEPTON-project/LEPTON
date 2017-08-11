<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the BSD License.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          jsadmin 
 * @author          WebsiteBaker Project
 * @author          LEPTON Project
 * @copyright       2004-2010, Ryan Djurovich,WebsiteBaker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         BSD License
 * @license_terms   please see info.php of this module
 *
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

/**
 *  delete obsolete files
 */
$temp_path = LEPTON_PATH."/modules/jsadmin/headers.inc.php";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}

$temp_path = LEPTON_PATH."/modules/jsadmin/README.txt";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}

?>