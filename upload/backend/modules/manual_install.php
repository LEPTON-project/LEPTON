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

/**
 * check if there is anything to do
 */

$leptoken = (isset($_GET['leptoken'])) ? "?leptoken=".$_GET['leptoken'] : "";
$backlink = "../modules/index.php".$leptoken;

if (!(isset($_POST['action']) && in_array($_POST['action'], array('install', 'upgrade')))) { 
	die(header('Location: '.$backlink));
}
if (!(isset($_POST['file']) && $_POST['file'] != '')) {
	die(header('Location: '.backlink));
}

/**
 * check if user has permissions to access this file
 */
require_once('../../framework/class.admin.php');

// check user permissions for admintools (redirect users with wrong permissions)
$admin = new admin('Admintools', 'admintools', false, false);
if ($admin->get_permission('admintools') == false) {
	die(header('Location: ../../index.php'));
}

// create Admin object with admin header
$admin = new admin('Addons', '', true, false);
$js_back = ADMIN_URL . '/modules/index.php'.$leptoken;

/**
 * Manually execute the specified module file (install.php, upgrade.php)
 */
$module_file = $_POST['file'];

if (!file_exists($module_file))
{
    $admin->print_error($TEXT['NOT_FOUND'].': <tt>"'.$module_file.'"</tt> ', $js_back);
}

// include modules install.php/upgrade.php script
require($module_file);

// load module info into database and output status message
$mod_path = dirname($module_file);
require( $mod_path."/info.php");
if (!function_exists("load_module")) require_once(LEPTON_PATH."/framework/functions/function.load_module.php");
load_module($mod_path, false);

$msg = $TEXT['EXECUTE'] . ': <tt>"' . htmlentities(basename($mod_path)) . '/' . $_POST['action'] . '.php"</tt>';

switch ($_POST['action'])
{
	case 'install':
	case 'upgrade':
		$admin->print_success($msg, $js_back);
		break;
		
	default:
		$admin->print_error( $TEXT["ACTION_NOT_SUPPORTED"], $js_back);
}

?>