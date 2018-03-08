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
$oTALG = talgos::getInstance();

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



// start the page search
$search_result = array();
$title_checked   = 1;
$page_checked    = 0;	
$section_checked = 0;
$search_performed = false;

if ( isset($_POST['search_scope']))
{
	$search_performed = true;
	
	if ( $_POST['search_scope'] == 'section' )	{
		
		$title_checked   = 0;
		$page_checked    = 0;	
		$section_checked = 1;
		//  Section_id as to be an integer.
		$iSearchTerm = intval( $_POST['terms'] );
		// find result
		$temp_page_id = $database->get_one("SELECT page_id FROM ".TABLE_PREFIX."sections WHERE section_id = ".$iSearchTerm );
		$database->execute_query(
			"SELECT * FROM `".TABLE_PREFIX."pages` WHERE `page_id` = ".$temp_page_id." ", 
			true,
			$search_result,
			true
		);		
	}

	elseif( $_POST['search_scope'] == 'page' ) {
		$title_checked   = 0;
		$page_checked    = 1;	
		$section_checked = 0;
		//  PageID as to ba an integer:
		$iSearchTerm = intval( $_POST['terms'] );
		// find result
		$database->execute_query(
			"SELECT * from `".TABLE_PREFIX."pages` WHERE `page_id` = ".$iSearchTerm, 
			true,
			$search_result,
			true
		);
	}	

	elseif( $_POST['search_scope'] == 'title' ) {
		$title_checked   = 1;
		$page_checked    = 0;	
		$section_checked = 0;
		// Page-title has to be a string
		$sSearchTerm = strip_tags( trim($_POST['terms']), "" );
		// find result
		$database->execute_query(
			"SELECT * from `".TABLE_PREFIX."pages` WHERE `page_title` LIKE '%".$sSearchTerm."%' ", 
			true,
			$search_result,
			true
		);
	}	
}


//	Get all pages as (array-) tree
LEPTON_handle::register( "page_tree" );

//	Storage for all infos in an array
$all_pages = array();

//	Determinate what fields/keys we want to get in our 'page_tree'-array
$fields = array('page_id','page_title','level','menu_title','parent','position','visibility','link');

//	Get the tree here
page_tree( 0, $all_pages, $fields );

$oTALG->setRememberState( $all_pages );

// preselect a page_id?
$preselect_page = (isset($_GET['page_id']) ? $_GET['page_id'] : 0 );

//$parser->addGlobal('preselect_page',$preselect_page);

$oTWIG->parser->addGlobal('alternative_url',THEME_URL.'/backend/backend/pages/');
$oTWIG->parser->addGlobal('action_url',ADMIN_URL.'/pages/');

// echo(LEPTON_tools::display($_COOKIE,'pre','ui message'));

$page_values = array(
	'oTALG' 	=> $oTALG,
	'section_check' => $section_checked,
	'page_check' 	=> $page_checked,
	'title_check'	=> $title_checked,
	'search_values'	=>  ($_POST['terms'] ?? ""),
	'perm_pages_add'=> $admin->get_permission('pages_add'),
	'all_groups'	=> $all_groups,
	'all_page_modules' => $all_page_modules,
	'leptoken'		=> get_leptoken(),
	'all_pages'	=> $all_pages,
	'search_result'	=> $search_result,
	'search_performed' => $search_performed
);
//section_active

$oTWIG->registerPath( THEME_PATH."theme","pages" );
echo $oTWIG->render(
	"@theme/pages.lte",
	$page_values
);

$admin->print_footer();

?>