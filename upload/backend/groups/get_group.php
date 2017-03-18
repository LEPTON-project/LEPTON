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

if (!isset($_SESSION['backend_group_h'])) die();
if (!isset($_SESSION['backend_group_v'])) die();

if( $_POST[ $_SESSION['backend_group_h'] ] != $_SESSION['backend_group_v']) die();

if (!isset($_POST['id'])) die();

$gid = intval($_POST['id']);
if ($gid == 0) die();

$group = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."groups` WHERE `group_id`=".$gid,
	true,
	$group,
	false
);

/**	**************************
 *	Get the system permissions
 *
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

$group_values = array(
	'group_name' => $group['name'],
	'group_id' => $group['group_id']
);

$group_system_permissions = explode(',', $group['system_permissions']);

foreach($system_lookups as $key => &$sub_keys) {
	foreach($sub_keys as &$sub) {
		$temp_name = $key."_".$sub;
		$group_values[ $temp_name ] = (in_array($temp_name, $group_system_permissions) ) ? 1 : 0;
	}
}

/**	***********
 *	Get modules
 *
 */
$all_modules = array();
$database->execute_query(
	'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "module" AND `function` = "page" ORDER BY `name`',
	true,
	$all_modules
);

$group_module_permissions = explode(',', $group['module_permissions']);

foreach($all_modules as &$module) {
	$group_values[ $module['directory'] ] = (in_array($module['directory'], $group_module_permissions) ) ? 1 : 0;
}

/** **************
 *	Get Admintools
 *
 */
$all_tools = array();
$database->execute_query(
	'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "module" AND `function` = "tool" ORDER BY `name`',
	true,
	$all_tools
);

foreach($all_tools as &$tool) { 
	$group_values[ $tool['directory'] ] = (in_array($tool['directory'], $group_module_permissions) ) ? 1 : 0;
}

/**	*************
 *	Get templates
 *
 */
$all_templates = array();
$database->execute_query(
	'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "template" ORDER BY `name`',
	true,
	$all_templates
);
	
$group_template_permissions = explode(',', $group['template_permissions']);

foreach($all_templates as &$template) {
	$group_values[ $template['directory'] ] = (in_array($template['directory'], $group_template_permissions) ) ? 1 : 0;
}

/**	*************
 *	Get languages
 *
 */
$all_languages = array();
$database->execute_query(
	'SELECT `name`,`directory` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "language" ORDER BY `name`',
	true,
	$all_languages
);
	
$group_language_permissions = explode(',', $group['language_permissions']);

foreach($all_languages as &$language) {
	$group_values[ $language['directory'] ] = (in_array($language['directory'], $group_language_permissions) ) ? 1 : 0;
}

/**
 *	Return the result-array in JSON (JavaScript Object-Notation)
 *
 */
echo json_encode( $group_values );

?>