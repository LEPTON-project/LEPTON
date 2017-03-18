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
 * @version         $Id: sections.php 1601 2012-01-07 11:11:01Z erpe $
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


// Make sure people are allowed to access this page
if(MANAGE_SECTIONS != 'enabled')
{
	header('Location: '.ADMIN_URL.'/pages/index.php');
	exit(0);
}

// Get page id
if(!isset($_GET['page_id']) OR !is_numeric($_GET['page_id']))
{
	header("Location: index.php");
	exit(0);
} else {
	$page_id = intval($_GET['page_id']);
	
	/**
	 *	Does this page realy exists?
	 *
	 */
	$temp_result = array();
	$database->execute_query(
		"SELECT `page_id` from `".TABLE_PREFIX."pages` where `page_id`='".$page_id."'",
		true,
		$temp_result,
		false
	);
	if ( 0 === count($temp_result) ) {
		die( header("Location: index.php") );
	} 
}

// Create new admin object
require_once(LEPTON_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_modify');

/**
 *	Any "jobs" to do?
 */
if( isset($_POST['job']) )
{
	$job = trim($_POST['job']);
	
	if( $job == "delete")
	{
		if ( (isset($_POST['section_id'])) && ($_POST['section_id'] != ''))
		{

			$section_id = intval($_POST['section_id']);
		
			$section_info = array();
			$database->execute_query(
				"SELECT `module` FROM `".TABLE_PREFIX."sections` WHERE `section_id` =".$section_id,
				true,
				$section_info,
				false
			);
		
			if( 0 === count($section_info) )
			{
				$admin->print_error('Section not found');
			}
		
			/**
			 *	Call "delete.php" of the module
			 */
			$look_for_path = LEPTON_PATH.'/modules/'.$section_info['module'].'/delete.php';
		 
			if(file_exists($look_for_path))
			{
				global $page_id, $section_id; // aldus: it is not realy clear if we need this for the delete procedere of the modules!
			
				require( $look_for_path );
			}
		
			$database->simple_query("DELETE FROM `".TABLE_PREFIX.'sections` WHERE `section_id` ='.$section_id);
			if($database->is_error())
			{
				$admin->print_error($database->get_error());
			} else {
				require(LEPTON_PATH.'/framework/class.order.php');
				$order = new order(TABLE_PREFIX.'sections', 'position', 'section_id', 'page_id');
				$order->clean($page_id);
				$admin->print_success($TEXT['SUCCESS']."\nDelete section ".$section_id, ADMIN_URL.'/pages/sections.php?page_id='.$page_id);
				$admin->print_footer();
				exit();
			}
		}
	}
	//		End: delete section
	
	if( $job == "add" )
	{
		if ( (isset($_POST['module'])) && ($_POST['module'] != ''))
		{

			// Get module name
			/**
			 *	MOTICE!
			 *	LEPTON 2.3.µ+ and newer: we are submitting the "ADDON_ID" instead of the Name!
			 */
			$module = preg_replace("/\W/", "", addslashes($_POST['module']));  // fix secunia 2010-91-4

			/**
			 *	Is the module-id valide? Or in other words: does the module(-name) exists?
			 *
			 */
			$temp_result = array();
			$database->execute_query(
				"SELECT `name`, `directory` from `".TABLE_PREFIX."addons` where `addon_id`='".$module."'",
				true,
				$temp_result,
				false
			);
			if (true===$database->is_error())
			{
				$admin->print_error($database->get_error());
			}
			else 
			{
				if ( 0 === count($temp_result) )
				{
					$admin->print_error($MESSAGE['GENERIC_MODULE_VERSION_ERROR']." [1]");
				}
			}
			
			// echo (LEPTON_tools::display($temp_result));
			
			$module = $temp_result['directory'];
			unset($temp_result);	
			echo LEPTON_tools::display( $module );

			/**
			 *	Got the current user the rights to "use" this module?
			 *
			 */
			if (true === in_array($module, $_SESSION['MODULE_PERMISSIONS'] ) )
			{
				$admin->print_error($MESSAGE['GENERIC_NOT_UPGRADED']);
			}

			// Include the ordering class
			require(LEPTON_PATH.'/framework/class.order.php');
			// Get new order
			$order = new order(TABLE_PREFIX.'sections', 'position', 'section_id', 'page_id');
			$position = $order->get_new($page_id);	
	
			// Insert 'module' into DB
			$fields = array(
				'page_id'	=> $page_id,
				'module'	=> $module,
				'position'	=> $position,
				'block'		=> 1			// Attention: insert a new module-section here at block 1
			);

			$database->build_and_execute(
				'insert',
				TABLE_PREFIX.'sections',
				$fields
			);

			// Get the section id
			$section_id = $database->get_one("SELECT LAST_INSERT_ID()");	
			// Include the selected modules add file if it exists
			if(file_exists(LEPTON_PATH.'/modules/'.$module.'/add.php'))
			{
				require(LEPTON_PATH.'/modules/'.$module.'/add.php');
			}	
		}
	}
	//	END: insert a new section
}

/**
 *	Get page details
 */
$page_info = array();
$database->execute_query(
	'SELECT * FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$page_id,
	true,
	$page_info,
	false
);

/**
 *	Get permissions
 *
 */

$old_admin_groups = explode(',', $page_info['admin_groups']);
$old_admin_users = explode(',', $page_info['admin_users']);
$in_old_group = FALSE;
foreach($admin->get_groups_id() as $cur_gid)
{
	if (in_array($cur_gid, $old_admin_groups))
    {
		$in_old_group = TRUE;
	}
}
if((!$in_old_group) && !is_numeric(array_search($admin->get_user_id(), $old_admin_users)))
{
	$admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
}

// Get module permissions
$module_permissions = $_SESSION['MODULE_PERMISSIONS'];

// Unset block var - Aldus - 2015-07-28: why?
unset($block);

/** 
 *	Try to include the info.php from the current (page-) template
 */
require( LEPTON_PATH.'/templates/'.(($page_info['template'] != '') ? $page_info['template'] : DEFAULT_TEMPLATE).'/info.php' );

// Check if $menu is set
if(!isset($block[1]) OR $block[1] == '')
{
	// Make our own menu list
	$block[1] = $TEXT['MAIN'];
}

// include jscalendar-setup
$jscal_use_time = true; // whether to use a clock, too
require_once(LEPTON_PATH."/include/jscalendar/wb-setup.php");

/**	******************************
 *	Get all pages as (array-) tree
 */
if (!function_exists("page_tree")) require_once( LEPTON_PATH."/framework/functions/function.page_tree.php");

//	Storage for all infos in an array
$all_pages = array();

//	Determinate what fields/keys we want to get in our 'page_tree'-array
$fields = array('page_id','page_title','menu_title','parent','position','visibility','link');

//	Get the tree here
page_tree( 0, $all_pages, $fields );

/**	*****************************
 *	Get all sections of this page
 */
$all_sections = array();

$sql  = 'SELECT `section_id`,`module`,`position`,`block`,`publ_start`,`publ_end`,`name` ';
$sql .= 'FROM `'.TABLE_PREFIX.'sections` ';
$sql .= 'WHERE `page_id` = '.$page_id.' ';
$sql .= 'ORDER BY `position` ASC';

$database->execute_query(
	$sql,
	true,
	$all_sections
);

/**	********************
 *	Get all page-modules
 */
$all_page_modules = array();
$database->execute_query(
	"SELECT `name`,`addon_id` FROM `".TABLE_PREFIX."addons` WHERE `function`='page' ORDER BY `name`",
	true,
	$all_page_modules
);

/**
 *	Get all 'blocks' from the current-template of the page
 */
$block = array( $TEXT["MAIN"] );
require( LEPTON_PATH."/templates/".(( $page_info['template'] == "" ) ? DEFAULT_TEMPLATE : $page_info['template'])."/info.php" );

if(file_exists(THEME_PATH."/globals/lte_globals.php")) require_once(THEME_PATH."/globals/lte_globals.php");

/** ****************************
 *	Collect vars and render page
 */
$page_vars = array(
	'PAGE_ID' => $page_info['page_id'],
	'TEXT_PAGE' => $TEXT['PAGE'],
	'PAGE_TITLE' => $page_info['page_title'],
	'MENU_TITLE' => $page_info['menu_title'],
	'SETTINGS_LINK' => ADMIN_URL.'/pages/settings.php?page_id='.$page_info['page_id'],
	'MODIFY_LINK' => ADMIN_URL.'/pages/modify.php?page_id='.$page_info['page_id'],
	
	'page_info'	=> $page_info,
	'all_pages'	=> $all_pages,
	'all_sections' => $all_sections,
	'all_page_modules' => $all_page_modules,
	'blocks'	=> $block, // Notice: no '-s' (plural) here!
	'SEC_ANCHOR'	=> SEC_ANCHOR
);

echo $parser->render(
	"@theme/pages_sections.lte",
	$page_vars
);


// Print admin footer
$admin->print_footer();

?>