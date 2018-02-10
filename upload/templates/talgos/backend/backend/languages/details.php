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


$leptoken = (isset($_GET['leptoken'])) ? $_GET['leptoken'] : "";

// Get language name
if(!isset($_POST['code']) OR $_POST['code'] == "") {
	header("Location: index.php?leptoken=".$leptoken);
	exit(0);
} else {
	$code = $_POST['code'];
}

// fix secunia 2010-93-2
if (!preg_match('/^[A-Z]{2}$/', $code)) {
	header("Location: index.php?leptoken=".$leptoken);
	exit(0);
}

// get twig instance
$admin = LEPTON_admin::getInstance();
$oTWIG = lib_twig_box::getInstance();


// get values
$current_language = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."addons` WHERE type = 'language' AND directory = '".$code."'",
	true,
	$current_language,
	false
);
	
// Insert language text and messages
$page_values = array(
	'current'	=>$current_language
);

$oTWIG->registerPath( THEME_PATH."/templates","languages_details" );
echo $oTWIG->render(
	"@theme/languages_details.lte",
	$page_values
);

// Print admin footer
$admin->print_footer();

?>