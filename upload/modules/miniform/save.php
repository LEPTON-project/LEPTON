<?php

/**
 *
 *	@module			miniform
 *	@version		see info.php of this module
 *	@authors		Ruud Eisinga, LEPTON project
 *	@copyright		2012-2015 Ruud Eisinga, LEPTON project
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
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

require_once (LEPTON_PATH.'/framework/summary.functions.php');
require(LEPTON_PATH.'/modules/admin.php');
$update_when_modified = true; 

if(isset($_POST['section_id'])) {
	$email = addslashes(strip_tags($_POST['email']));
	$subject = addslashes(strip_tags($_POST['subject']));
	$template = addslashes(strip_tags($_POST['template']));
	$success = intval($_POST['successpage']);
	
	$query = "UPDATE ".TABLE_PREFIX."mod_miniform SET 
			`email` = '$email', 
			`subject` = '$subject', 
			`successpage` = '$success', 
			`template` = '$template' 
			WHERE `section_id` = '$section_id'";
	$database->query($query);	
}

// Check if there is a database error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), $js_back);
} else {
	$admin->print_success($MESSAGE['PAGES']['SAVED'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>