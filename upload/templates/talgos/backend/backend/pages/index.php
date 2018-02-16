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
$oTWIG = lib_twig_box::getInstance();
$admin = LEPTON_admin::getInstance();

// Get all groups (inkl. 1 == Administrators
$all_groups = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."groups`",
	true,
	$all_groups,
	true
);
 

// Get all page-modules
$all_page_modules = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."addons` WHERE `type` = 'module' AND `function` = 'page' order by `name`",
	true,
	$all_page_modules,
	true
);
echo(LEPTON_tools::display($_POST,'pre','ui message'));
// start the page search
  if ( isset($_POST['search_scope']) && $_POST['search_scope'] == 'section' ) {
    $section_checked = 1;
	$page_checked    = 0;
	$title_checked   = 0;
  }
  elseif( isset($_POST['search_scope']) && $_POST['search_scope'] == 'page' ) {
    $page_checked    = 1;
	$section_checked = 0;
	$title_checked   = 0;
  }
  else {
    $title_checked   = 1;
	$section_checked = 0;
	$page_checked    = 0;
  }


//	Get all pages as (array-) tree
if (!function_exists("page_tree")) require_once( LEPTON_PATH."/framework/functions/function.page_tree.php");

//	Storage for all infos in an array
$all_pages = array();

//	Determinate what fields/keys we want to get in our 'page_tree'-array
$fields = array('page_id','page_title','menu_title','parent','position','visibility','link');

//	Get the tree here
page_tree( 0, $all_pages, $fields );

// preselect a page_id?
$preselect_page = (isset($_GET['page_id']) ? $_GET['page_id'] : 0 );

$parser->addGlobal('preselect_page',$preselect_page);

$page_values = array(
	'action_url' => ADMIN_URL.'/pages/',
	'section_check' => $section_checked,
	'page_check' 	=> $page_checked,
	'title_check'	=> $title_checked,
	'search_values'	=>  $_POST['terms'],	
	'alternative_url'=> THEME_URL."/backend/backend/pages/",
	'perm_pages_add'=> $admin->get_permission('pages_add'),
	'theme_url'		=> THEME_URL,	
	'all_groups'	=> $all_groups,
	'all_page_modules' => $all_page_modules,
	'leptoken'		=> get_leptoken(),	
	'all_pages'	=> $all_pages
);


$oTWIG->registerPath( THEME_PATH."theme","pages" );
echo $oTWIG->render(
	"@theme/pages.lte",
	$page_values
);

$admin->print_footer();

?>