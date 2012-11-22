<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010, Website Baker Project
 * @copyright       2010-2011, LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @version         $Id: time_formats.php 1172 2011-10-04 15:26:26Z frankh $
 *
 */
 
// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
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

// Define that this file is loaded
if(!defined('TIME_FORMATS_LOADED')) {
	define('TIME_FORMATS_LOADED', true);
}

// Get the current time (in the users timezone if required)
$actual_time = time();

// Get "System Default"
$s = "";
if(isset($user_time) AND $user_time == true) {
	$s = date(DEFAULT_TIME_FORMAT, $actual_time).' (';
	$s .= (isset($TEXT['SYSTEM_DEFAULT']) ? $TEXT['SYSTEM_DEFAULT'] : 'System Default').')';
}

// Add values to list
$TIME_FORMATS = array(
	'system_default' => $s,
	'H:i' =>date('H:i', $actual_time),
	'H:i:s' => date('H:i:s', $actual_time),
	'g:i|a' => date('g:i a', $actual_time),
	'g:i|A' => date('g:i A', $actual_time)
);
?>