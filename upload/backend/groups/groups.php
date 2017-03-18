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
$admin = new admin('Access', 'groups_modify', true);

/**	*******************************
 *	Get the template-engine.
 */
global $parser, $loader;
if (!isset($parser))
{
	require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
}

//	As we are "calling" a static method more than two times we are 
//	using an instance here for the reference for the class
$LEPTON_core = LEPTON_core::getInstance();
$LEPTON_core->get_backend_translation();
	
if(file_exists(THEME_PATH."/globals/lte_globals.php")) require_once(THEME_PATH."/globals/lte_globals.php");
$loader->prependPath( THEME_PATH."/templates/", "theme" );	// namespace for the Twig-Loader is "theme"

//	temp. hack to get page to run	
if (!isset($_POST['action'])) $_POST['action'] = "modify";

if($_POST['action'] == 'modify')
{

	/**
	 *	Get all groups for the groups-tree left
	 *	exept the group 1 (Administrators)!
	 *
	 */
	$all_groups = array();
	$database->execute_query(
		"SELECT `group_id`,`name` from `". TABLE_PREFIX."groups` WHERE `group_id` <> 1",
		true,
		$all_groups
	);
	
	if (!isset($_POST['group_id']) || ($_POST['group_id'] == 1) ) {
		
		$group = array(
			'group_id'	=> -1,
			'name'	=> "",
			'system_permissions' => "",
			'module_permissions' => "",
			'template_permissions' => "",
			'language_permissions' => ""			
		);
		
	} else {
	
		// Get group values
		$group = array();
		$database->execute_query(
			"SELECT * FROM `".TABLE_PREFIX."groups` WHERE `group_id` = '".intval($_POST['group_id'])."'",
			true,
			$group,
			false
		);
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
	
	$group_system_permissions = explode(',', $group['system_permissions']);
	
	$system_permissions = array();
	
	foreach($system_lookups as $sys_key => $subkeys) {
		
		$sub_keys = array();
		
		foreach($subkeys as $item) {
			$sub_keys[] = array(
				'name' => $sys_key."_".$item,
				'label'	=> $LEPTON_core->get_backend_translation( strtoupper($item) ),
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
	
	/**	**************************
	 *	Get the module permissions
	 */
	$all_modules = array();
	$database->execute_query(
		'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "module" AND `function` = "page" ORDER BY `name`',
		true,
		$all_modules
	);
	
	$group_module_permissions = explode(',', $group['module_permissions']);
	
	$module_permissions = array();
	foreach($all_modules as &$module) {
		$module_permissions[] = array(
			'name'	=> $module['name'],
			'directory' => $module['directory'],
			'permission' => in_array($module['directory'], $group_module_permissions) ? 1 : 0
		);
	}
	
	/**	*******************************
	 *	Get the admin-tools permissions
	 */
	$all_tools = array();
	
	$database->execute_query(
		'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "module" AND `function` = "tool" ORDER BY `name`',
		true,
		$all_tools
	);
	
	$admintools_permissions = array();
	foreach($all_tools as &$tool) {
		$admintools_permissions[] = array(
			'name'	=> $tool['name'],
			'directory' => $tool['directory'],
			'permission' => in_array($tool['directory'], $group_module_permissions) ? 1 : 0
		);
	}
	
	/**	*****************************
	 *	Get the templates permissions
	 */
	$all_templates = array();
	$database->execute_query(
		'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "template" ORDER BY `name`',
		true,
		$all_templates
	);
	
	$group_template_permissions = explode(',', $group['template_permissions']);
	
	$template_permissions = array();
	foreach($all_templates as &$template) {
		$template_permissions[] = array(
			'name'	=> $template['name'],
			'directory' => $template['directory'],
			'permission' => in_array($template['directory'], $group_template_permissions) ? 1 : 0
		);
	}
	
	/**	*****************************
	 *	Get the language permissions
	 */
	$all_languages = array();
	$database->execute_query(
		'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "language" ORDER BY `name`',
		true,
		$all_languages
	);
	
	$group_language_permissions = explode(',', $group['language_permissions']);

	$language_permissions = array();
	foreach($all_languages as &$language) {
		$language_permissions[] = array(
			'name'	=> $language['name'],
			'directory' => $language['directory'],
			'permission' => in_array($language['directory'], $group_language_permissions) ? 1 : 0
		);
	}	
//die(print_r($language_permissions));		
	/**
	 *	Get/Build secure-hash for the js-calls
	 */
	if(!function_exists("random_string")) require_once( LEPTON_PATH."/framework/functions/function.random_string.php");
	$hash = array(
		'h_name' => random_string(16),
		'h_value' => random_string(24)
	);
	$_SESSION['backend_group_h'] = $hash['h_name'];
	$_SESSION['backend_group_v'] = $hash['h_value'];

	$page_values = array(
		'all_groups' => $all_groups,
		'group' => $group,
		'ACTION_URL' => ADMIN_URL.'/groups/save.php',
		'system_permissions' => $system_permissions,
		'module_permissions' => $module_permissions,
		'admintools_permissions' => $admintools_permissions,
		'template_permissions'	=> $template_permissions,
		'language_permissions'	=> $language_permissions,		
		'hash'	=> $hash,
		'FORM_NAME' => "groups_".random_string(12),
		'GROUPS_CONFIRM_DELETE' => $MESSAGE['GROUPS_CONFIRM_DELETE']
	);
	

	echo $parser->render(
		'@theme/groups.lte',
		$page_values
	);

} elseif($_POST['action'] == 'delete') {
	// Create new admin object
	$admin = new admin('Access', 'groups_delete', false);
	// Print header
	$admin->print_header();
	// Delete the group
	$database->query("DELETE FROM ".TABLE_PREFIX."groups WHERE group_id = '".$_POST['group_id']."' LIMIT 1");
	if($database->is_error())
	{
		$admin->print_error($database->get_error());
	} else {
		// Delete users in the group
		$database->query("DELETE FROM ".TABLE_PREFIX."users WHERE group_id = '".$_POST['group_id']."'");
		if($database->is_error()) {
			$admin->print_error($database->get_error());
		} else {
			$admin->print_success($MESSAGE['GROUPS_DELETED']);
		}
	}
}

// Print admin footer
$admin->print_footer();

?>