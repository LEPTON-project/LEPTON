<?php

/**
 *  @module         form
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Rudolph Lartey, John Maats, Dietrich Roland Pehlke 
 *  @copyright      2004-2013 Ryan Djurovich, Rudolph Lartey, John Maats, Dietrich Roland Pehlke 
 *  @license        see info.php of this module
 *  @license terms  see info.php of this module
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

$module_directory = 'form';
$module_name      = 'Form';
$module_function  = 'page';
$module_version   = '3.0.5';
$module_platform  = '1.x';
$module_author    = 'Ryan Djurovich, Rudolph Lartey, John Maats, Dietrich Roland Pehlke ';
$module_license   = 'GNU General Public License';
$module_description = 'This module allows you to create customised online forms, such as a feedback form.';
$module_guid      = 'ad71cc7f-8c40-4b53-812c-4594ec0129aa';
$module_home      = 'http://lepton-cms.org';

?>