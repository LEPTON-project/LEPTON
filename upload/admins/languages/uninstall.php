<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
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

$leptoken = (isset($_GET['leptoken'])) ? $_GET['leptoken'] : "";

// Check if user selected language
if(!isset($_POST['code']) OR $_POST['code'] == "") {
	header("Location: index.php?leptoken=".$leptoken);
	exit(0);
}

// Extra protection
if(trim($_POST['code']) == '') {
	header("Location: index.php?leptoken=".$leptoken);
	exit(0);
}

require_once(LEPTON_PATH.'/framework/class.admin.php');
$admin = new admin('Addons', 'languages_uninstall');

// Include the functions file
require_once(LEPTON_PATH.'/framework/summary.functions.php');

// Check if the language exists
if(!file_exists(LEPTON_PATH.'/languages/'.$_POST['code'].'.php')) {
	$admin->print_error($MESSAGE['GENERIC_NOT_INSTALLED']);
}

// Check if the language is in use
if($_POST['code'] == DEFAULT_LANGUAGE OR $_POST['code'] == LANGUAGE) {
	$admin->print_error($MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE']);
} else {
	$query_users = $database->query("SELECT user_id FROM ".TABLE_PREFIX."users WHERE language = '".addslashes($_POST['code'])."' LIMIT 1");
	if($query_users->numRows() > 0) {
		$admin->print_error($MESSAGE['GENERIC_CANNOT_UNINSTALL_IN_USE']);
	}
}

// Try to delete the language code
if(!unlink(LEPTON_PATH.'/languages/'.$_POST['code'].'.php')) {
	$admin->print_error($MESSAGE['GENERIC_CANNOT_UNINSTALL']);
} else {
	// Remove entry from DB
	$database->query("DELETE FROM ".TABLE_PREFIX."addons WHERE directory = '".$_POST['code']."' AND type = 'language'");
}

// Print success message
$admin->print_success($MESSAGE['GENERIC_UNINSTALLED']);

// Print admin footer
$admin->print_footer();

?>