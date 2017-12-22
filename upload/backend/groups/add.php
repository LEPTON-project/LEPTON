<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
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


$admin = new LEPTON_admin('Access', 'groups_add');
include_once(LEPTON_PATH.'/framework/summary.functions.php');

// Gather details entered
$group_name = $admin->get_post_escaped('group_name');

// Create a javascript back link
$js_back = "javascript: history.go(-1);";

// Check values
if($group_name == "") {
	$admin->print_error($MESSAGE['GROUPS_GROUP_NAME_BLANK'], $js_back);
}
$results = $database->query("SELECT * FROM ".TABLE_PREFIX."groups WHERE name = '$group_name'");  
if($results->numRows()>0) {
	$admin->print_error($MESSAGE['GROUPS_GROUP_NAME_EXISTS'], $js_back);
}

// Get system and module permissions
require(ADMIN_PATH.'/groups/get_permissions.php');

// Update the database
$query = "INSERT INTO ".TABLE_PREFIX."groups (name,system_permissions,module_permissions,template_permissions,language_permissions) VALUES ('$group_name','$system_permissions','$module_permissions','$template_permissions','$language_permissions')";

$database->query($query);
if($database->is_error()) {
	$admin->print_error($database->get_error());
} else {
	$admin->print_success($MESSAGE['GROUPS_ADDED'], ADMIN_URL.'/groups/index.php');
}

// Print admin footer
$admin->print_footer();

?>