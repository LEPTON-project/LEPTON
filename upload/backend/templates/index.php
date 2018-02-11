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
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @version         $Id: index.php 1172 2011-10-04 15:26:26Z frankh $
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


// enable custom files
//LEPTON_handle::require_alternative('/templates/'.DEFAULT_THEME.'/backend/backend/templates/index.php');
if(file_exists(THEME_PATH .'/backend/backend/templates/index.php')) {
	require_once (THEME_PATH .'/backend/backend/templates/index.php');
	die();
}

// get twig instance
$admin = LEPTON_admin::getInstance();
$oTWIG = lib_twig_box::getInstance();


//	Get all templates
$all_templates = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."addons` WHERE `type` = 'template' order by `name`",
	true,
	$all_templates,
	true
);

/**	**********************************
 *	Build secure-hash for the js-calls
 */
if(!function_exists("random_string")) require_once( LEPTON_PATH."/framework/functions/function.random_string.php");
$hash = array(
	'h_name' => random_string(16),
	'h_value' => random_string(24)
);
$_SESSION['backend_templates_h'] = $hash['h_name'];
$_SESSION['backend_templates_v'] = $hash['h_value'];

/**
 *	Collecting all page values here
 */
$page_values = array(
	'all_templates'	=> $all_templates,
	'hash'	=> $hash,
	'ACTION_URL'  => ADMIN_URL."/templates/",
	'RELOAD_URL'	=> ADMIN_URL."/addons/reload.php"
);

$oTWIG->registerPath( THEME_PATH."/templates","theme" );
echo $oTWIG->render(
	"@theme/templates.lte",
	$page_values
);

// Print admin footer
$admin->print_footer();

?>