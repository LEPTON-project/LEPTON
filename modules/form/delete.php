<?php

/**
 *  @module         form
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Rudolph Lartey, John Maats, Dietrich Roland Pehlke 
 *  @copyright      2004-2011 Ryan Djurovich, Rudolph Lartey, John Maats, Dietrich Roland Pehlke 
 *  @license        see info.php of this module
 *  @license terms  see info.php of this module
 *  @requirements   PHP 5.2.x and higher
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



// Delete page
$database->query("DELETE FROM ".TABLE_PREFIX."mod_form_fields WHERE page_id = '$page_id' AND section_id='$section_id'");
$database->query("DELETE FROM ".TABLE_PREFIX."mod_form_settings WHERE page_id = '$page_id' AND section_id='$section_id'");

?>