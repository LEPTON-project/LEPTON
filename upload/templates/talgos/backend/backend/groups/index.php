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


//	Get all groups
$all_groups = array();
$database->execute_query(
	"SELECT `group_id`,`name` FROM `".TABLE_PREFIX."groups` WHERE `group_id` != '1' ORDER BY `name`",
	true,
	$all_groups,
	true
);

//	Get all templates
$all_templates = array();
$database->execute_query(
	'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "template" ORDER BY `name`',
	true,
	$all_templates,
	true
);

//	Get all modules
$all_modules = array();
$database->execute_query(
	'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "module" AND `function` = "page" ORDER BY `name`',
	true,
	$all_modules,
	true
);

//	Get all admin-tools
$all_tools = array();	
$database->execute_query(
	'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "module" AND `function` = "tool" ORDER BY `name`',
	true,
	$all_tools,
	true
);

$page_values = array(
	'alternative_url'	=> THEME_URL."/backend/backend/groups/",
	'action_url'	=> ADMIN_URL."/groups/",	
	'perm_modify'	=> $admin->get_permission('groups_modify'),
	'perm_delete'	=> $admin->get_permission('groups_delete'),
	'perm_add'		=> $admin->get_permission('groups_add'),
	'group_id'		=> -1,
	'all_groups'	=> $all_groups,
	'all_tools'		=> $all_tools,
	'all_modules'	=> $all_modules,	
	'all_templates'	=> $all_templates
);

$oTWIG->registerPath( THEME_PATH."theme","groups_add" );
echo $oTWIG->render(
	"@theme/groups_add.lte",
	$page_values
);
 
$admin->print_footer();
?>