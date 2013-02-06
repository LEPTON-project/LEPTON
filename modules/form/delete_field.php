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



// Get id
if(!isset($_GET['field_id']) OR !is_numeric($_GET['field_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$field_id = $_GET['field_id'];
}

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

// Delete row
$database->query("DELETE FROM ".TABLE_PREFIX."mod_form_fields WHERE field_id = '$field_id'");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>