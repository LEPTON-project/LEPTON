<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
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
$admin = new admin('Addons', 'modules');

/**	*******************************
 *	Get the template-engine.
 */
global $parser, $loader;
if (!isset($parser))
{
	require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
}
if(file_exists(THEME_PATH."/globals/lte_globals.php")) require_once(THEME_PATH."/globals/lte_globals.php");
$loader->prependPath( THEME_PATH."/templates/", "theme" );	// namespace for the Twig-Loader is "theme"

/**
 *	Get all Modules
 */
$all_modules = array();
$database->execute_query(
	"SELECT `addon_id`,`name`,`directory` FROM `".TABLE_PREFIX."addons` WHERE `type` = 'module' order by `name`",
	true,
	$all_modules
);

/**
 *	Look into the modules directory for manual_install/upgrade
 */
$module_files = glob(LEPTON_PATH . '/modules/*');

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

/**
 *	Build secure-hash for the js-calls
 */
if(!function_exists("random_string")) require_once( LEPTON_PATH."/framework/functions/function.random_string.php");
$hash = array(
	'h_name' => random_string(16),
	'h_value' => random_string(24)
);
$_SESSION['backend_module_h'] = $hash['h_name'];
$_SESSION['backend_module_v'] = $hash['h_value'];
	
$page_values = array(
	'ACTION_URL_I'	=> ADMIN_URL."/modules/install.php",
	'ACTION_URL_U'	=> ADMIN_URL."/modules/uninstall.php",
	'ACTION_URL_MI'	=> ADMIN_URL."/modules/manual_install.php",
	'all_modules' => $all_modules,
	'modules_found' => $modules_found,	// All modules inside the modules directory.
	'hash'	=> $hash
);

echo $parser->render(
	"@theme/modules.lte",
	$page_values
);

// Print admin footer
$admin->print_footer();

?>