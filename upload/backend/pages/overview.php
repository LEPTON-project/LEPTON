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

if(file_exists(THEME_PATH."/globals/lte_globals.php"))
{
    require_once(THEME_PATH."/globals/lte_globals.php");
}

//	Get all groups (inkl. 1 == Administrators
$all_groups = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."groups`",
	true,
	$all_groups,
	true
);
 

//	Get all page-modules
$all_page_modules = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."addons` WHERE `type` = 'module' AND `function` = 'page' order by `name`",
	true,
	$all_page_modules,
	true
);

//	Get all pages as (array-) tree
if (!function_exists("page_tree")) require_once( LEPTON_PATH."/framework/functions/function.page_tree.php");

//	Storage for all infos in an array
$all_pages = array();

//	Determinate what fields/keys we want to get in our 'page_tree'-array
$fields = array('page_id','page_title','menu_title','parent','position','visibility','link');

//	Get the tree here
page_tree( 0, $all_pages, $fields );

$page_values = array(
	'all_groups' => $all_groups,
	'all_page_modules' => $all_page_modules,
	'all_pages'	=> $all_pages,
	'open_tree'	=> 1,
	'leptoken'	=> get_leptoken()
);

$oTWIG->registerPath( THEME_PATH."theme","pages_overview" );
echo $oTWIG->render(
	"@theme/pages_overview.lte",
	$page_values
);

$admin->print_footer();

?>