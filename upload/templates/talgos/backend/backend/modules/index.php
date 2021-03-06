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


//	Get all Modules
$all_modules = array();
$database->execute_query(
	"SELECT `addon_id`,`name`,`directory` FROM `".TABLE_PREFIX."addons` WHERE `type` = 'module' order by `name`",
	true,
	$all_modules,
	true
);


//	Look into the modules directory for manual_install/upgrade
$module_files = glob(LEPTON_PATH . '/modules/*', GLOB_ONLYDIR );

$modules_found = array();
foreach($module_files as &$mod) {
	$temp_install = $mod."/install.php";
	$temp_upgrade = $mod."/upgrade.php";
	
	if (file_exists($temp_install)) {
		$modules_found[] = array(
			'name' => basename( $mod ),
			'install'	=> $temp_install,
			'upgrade'	=> (file_exists($temp_upgrade)) ? $temp_upgrade : ''
		);
	}
}


//	Build secure-hash for the js-calls
if(!function_exists("random_string")) require_once( LEPTON_PATH."/framework/functions/function.random_string.php");
$hash = array(
	'h_name' => random_string(16),
	'h_value' => random_string(24)
);
$_SESSION['backend_module_h'] = $hash['h_name'];
$_SESSION['backend_module_v'] = $hash['h_value'];

	
$page_values = array(
	'url_templates'	=> $admin->get_permission('templates'),
	'url_languages'	=> $admin->get_permission('languages'), 
	'perm_admin'	=> $admin->get_permission('admintools'),
	'perm_install'	=> $admin->get_permission('modules_install'),
	'perm_uninstall'=> $admin->get_permission('modules_uninstall'),
	'perm_view'		=> $admin->get_permission('modules_view'),
	'action_url'	=> ADMIN_URL."/modules/",
	'all_modules' 	=> $all_modules,
	'alternative_url'	=> THEME_URL."/backend/backend/modules/",
	'modules_found' => $modules_found,	// All modules inside the modules directory.
	'hash'	=> $hash,
	'is_advanced'   => (isset($_GET['advanced']))
);

$oTWIG->registerPath( THEME_PATH."/templates","modules" );
echo $oTWIG->render(
	"@theme/modules.lte",
	$page_values
);

// Print admin footer
$admin->print_footer();

?>