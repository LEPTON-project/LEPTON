<?php
/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            http://www.LEPTON-cms.org
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

require_once(LEPTON_PATH.'/framework/class.admin.php');
$admin = new admin('Access', 'groups_modify');
include_once(LEPTON_PATH.'/framework/summary.functions.php');
// die(print_r($_POST));
// Create a javascript back link
$js_back = "javascript: history.go(-1);";

// Check if group group_id is a valid number and doesnt equal 1
if(!isset($_POST['group_id']) || !is_numeric($_POST['group_id']) || $_POST['group_id'] == 1)
{
	header("Location: index.php");
	exit(0);
} else {
	$group_id = intval($_POST['group_id']);
}

// Gather details entered
$group_name = $admin->get_post_escaped('group_name');

// Check values
if($group_name == "")
{
	$admin->print_error($MESSAGE['GROUPS_GROUP_NAME_BLANK']);
}

if (isset($_POST['job'])) {
	if ($_POST['job'] == "delete") {
	
		$database->query("DELETE FROM `".TABLE_PREFIX."groups` WHERE `group_id` = '".$group_id."' LIMIT 1");
	
		if($database->is_error())
		{
			$admin->print_error($database->get_error());
		} else {
			// Delete users in the group
			$database->query("DELETE FROM `".TABLE_PREFIX."users` WHERE `group_id` = '".$group_id."'");
			if($database->is_error()) {
				$admin->print_error($database->get_error());
			} else {
				$admin->print_success($MESSAGE['GROUPS_DELETED'], ADMIN_URL.'/groups/groups.php');
			}
		}
		
		$admin->print_footer();
		return true;
	}
}

/**	**************************
 *	Get the system permissions
 */
$system_lookups = array(
	'pages'		=> array('view', 'add', 'add_level_0','settings', 'modify','delete'),
	'media'		=> array('view','upload','rename','delete','create'),
	'modules'	=> array('view','install','uninstall'),
	'templates' => array('view','install','uninstall'),
	'languages' => array('view','install','uninstall'),
	'settings'	=> array('modify'),
	'users'		=> array('view','add','modify','delete'),
	'groups'	=> array('view','add','modify','delete'),
	'admintools' => array('settings')
);

$group_system_permissions = array();
foreach($system_lookups as $key=>$subkeys) {
	$one_is_set = false;
	foreach($subkeys as &$sub) {
		$temp_name = $key."_".$sub;
		
		if (isset($_POST[ $temp_name])) {
			if (intval($_POST[ $temp_name]) == 1) {
				$group_system_permissions[] = $temp_name;
				$one_is_set = true;
			}
		}
	}
	if (true === $one_is_set) $group_system_permissions[] = $key;
}
$system_permissions = implode(",", $group_system_permissions);

/**	**************************
 *	Get the module permissions
 */
$all_modules = array();
$database->execute_query(
	'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "module" AND (`function` = "page" OR `function`="tool") ORDER BY `name`',
	true,
	$all_modules
);
$group_module_permissions = array();
foreach($all_modules as &$module) {
	if (isset($_POST[ $module['directory'] ])) {
		if (intval($_POST[ $module['directory'] ]) == 1) {
			$group_module_permissions[] = $module['directory'];
		}
	}
}
$module_permissions = implode(",", $group_module_permissions);

/**	**************************
 *	Get the language permissions
 */
$all_languages = array();
$database->execute_query(
	'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "language"  ORDER BY `name`',
	true,
	$all_languages
);
$group_language_permissions = array();
foreach($all_languages as &$language) {
	if (isset($_POST[ $language['directory'] ])) {
		if (intval($_POST[ $language['directory'] ]) == 1) {
			$group_language_permissions[] = $language['directory'];
		}
	}
}
$language_permissions = implode(",", $group_language_permissions);

/**	*****************************
 *	Get the templates permissions
 */
$all_templates = array();
$database->execute_query(
	'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "template" ORDER BY `name`',
	true,
	$all_templates
);

$group_template_permissions = array();
foreach($all_templates as &$template) {
	if (isset( $_POST[ $template['directory'] ] )) {
		if (intval($_POST[ $template['directory'] ]) == 1) {
			$group_template_permissions[] = $template['directory'];
		}
	}
}
$template_permissions = implode(",", $group_template_permissions);

$fields = array(
	'name' => $group_name,
	'system_permissions' => $system_permissions,
	'module_permissions' => $module_permissions,
	'template_permissions' => $template_permissions,
	'language_permissions' => $language_permissions	
);

if ($group_id === -1) {
	
	// Insert a new group
	$database->build_and_execute(
		'insert',
		TABLE_PREFIX."groups",
		$fields
	);
		
} else {
	
	// Update an existing group
	$database->build_and_execute(
		'update',
		TABLE_PREFIX."groups",
		$fields,
		"`group_id` = ".$group_id
	);
}

if($database->is_error())
{
	$admin->print_error($database->get_error());
} else {
	$admin->print_success($MESSAGE['GROUPS_SAVED'], ADMIN_URL.'/groups/groups.php');
}

// Print admin footer
$admin->print_footer();

?>