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


// get twig instance
$admin = LEPTON_admin::getInstance();
$oTWIG = lib_twig_box::getInstance();

//	As we are "calling" a static method more than two times we are using an instance here for the reference for the class
$LEPTON_basics = LEPTON_basics::getInstance();

//	temp. hack to get page to run	
if (!isset($_POST['modify']) && ($_POST['group_id'] = ''))
{
	header("Location: index.php");
	exit(0);
} else {
	$group_id = intval($_POST['group_id']);
}

if (isset($_POST['modify']) && ($_POST['group_id'] > 1))
{
	// Get group values
	$current_group = array();
	$database->execute_query(
		"SELECT * FROM `".TABLE_PREFIX."groups` WHERE `group_id` = '".$group_id."'",
		true,
		$current_group,
		false
	);
	echo(LEPTON_tools::display($current_group,'pre','ui message'));
} 


//	Get the system permissions
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
	
$group_system_permissions = explode(',', $current_group['system_permissions']);
	
$system_permissions = array();
	
foreach($system_lookups as $sys_key => $subkeys) {
		
	$sub_keys = array();
		
	foreach($subkeys as $item) {
		$sub_keys[] = array(
			'name' => $sys_key."_".$item,
			'label'	=> $LEPTON_basics->get_backend_translation( strtoupper($item) ),
			'checked' => in_array( $sys_key."_".$item, $group_system_permissions ) ? 1 : 0
		);
	}
		
	$system_permissions[] = array(
		'name'	=> $sys_key,
		'label'	=> $MENU[ (strtoupper($sys_key)) ],
		'checked' => in_array( $sys_key, $group_system_permissions ) ? 1 : 0,
		'sub_keys'	=> $sub_keys
	);
}
	

//	Get the module permissions
$all_modules = array();
$database->execute_query(
	'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "module" AND `function` = "page" ORDER BY `name`',
	true,
	$all_modules,
	true
);
	
$group_module_permissions = explode(',', $current_group['module_permissions']);
	
$module_permissions = array();
foreach($all_modules as &$module) {
	$module_permissions[] = array(
		'name'	=> $module['name'],
		'directory' => $module['directory'],
		'permission' => in_array($module['directory'], $group_module_permissions) ? 1 : 0
	);
}
	

//	Get the admin-tools permissions
$all_tools = array();	
$database->execute_query(
	'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "module" AND `function` = "tool" ORDER BY `name`',
	true,
	$all_tools,
	true
);
	
$admintools_permissions = array();
foreach($all_tools as &$tool) {
	$admintools_permissions[] = array(
		'name'	=> $tool['name'],
		'directory' => $tool['directory'],
		'permission' => in_array($tool['directory'], $group_module_permissions) ? 1 : 0
	);
}
	
//	Get the templates permissions
$all_templates = array();
$database->execute_query(
	'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "template" ORDER BY `name`',
	true,
	$all_templates,
	true
);
	
$group_template_permissions = explode(',', $current_group['template_permissions']);

$template_permissions = array();
foreach($all_templates as &$template) {
	$template_permissions[] = array(
		'name'	=> $template['name'],
		'directory' => $template['directory'],
		'permission' => in_array($template['directory'], $group_template_permissions) ? 1 : 0
	);
}
	

//	Get the language permissions
$all_languages = array();
$database->execute_query(
	'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "language" ORDER BY `name`',
	true,
	$all_languages,
	true
);
	
$group_language_permissions = explode(',', $current_group['language_permissions']);

$language_permissions = array();
foreach($all_languages as &$language) {
	$language_permissions[] = array(
		'name'	=> $language['name'],
		'directory' => $language['directory'],
		'permission' => in_array($language['directory'], $group_language_permissions) ? 1 : 0
	);
}	

//	Get/Build secure-hash for the js-calls
if(!function_exists("random_string")) require_once( LEPTON_PATH."/framework/functions/function.random_string.php");
$hash = array(
	'h_name' => random_string(16),
	'h_value' => random_string(24)
);
$_SESSION['backend_group_h'] = $hash['h_name'];
$_SESSION['backend_group_v'] = $hash['h_value'];

$page_values = array(
		'perm_modify'	=> $admin->get_permission('groups_modify'),
		'THEME' => $THEME,
//		'all_groups' => $all_groups,
		'current_group' => $current_group,
		'ACTION_URL' => ADMIN_URL.'/groups/save.php',
		'system_permissions' => $system_permissions,
		'module_permissions' => $module_permissions,
		'admintools_permissions' => $admintools_permissions,
		'template_permissions'	=> $template_permissions,
		'language_permissions'	=> $language_permissions,		
		'hash'	=> $hash,
		'FORM_NAME' => "groups_".random_string(12)
);

$oTWIG->registerPath( THEME_PATH."theme","groups_modify" );
echo $oTWIG->render(
	"@theme/groups_modify.lte",
	$page_values
);

// Print admin footer
$admin->print_footer();