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

// get twig instance
$oTWIG = lib_twig_box::getInstance();
$admin = LEPTON_admin::getInstance();

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
				// since 3.0.1 we use  LEPTON_order
				$order = new LEPTON_order(TABLE_PREFIX.'sections', 'position', 'section_id', 'page_id');
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

			/**
			 *	Got the current user the rights to "use" this module?
			 *
			 */
			if (true === in_array($module, $_SESSION['MODULE_PERMISSIONS'] ) )
			{
				$admin->print_error($MESSAGE['GENERIC_NOT_UPGRADED']);
			}

			// Include the ordering class
			// since 3.0.1 we use  LEPTON_order
			// Get new order
			$order = new LEPTON_order(TABLE_PREFIX.'sections', 'position', 'section_id', 'page_id');
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

// Get display name of person who last modified the page
$user=$admin->get_user_details($page_info['modified_by']);

// Convert the unix ts for modified_when to human a readable form
$modified_ts = ($page_info['modified_when'] != 0)
	? date(TIME_FORMAT.', '.DATE_FORMAT, $page_info['modified_when'])
	: 'Unknown'	;



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
//$jscal_use_time = true; // whether to use a clock, too
//require_once(LEPTON_PATH."/include/jscalendar/wb-setup.php");

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
$current_sections = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."sections` WHERE `page_id`= ".$page_id." ORDER BY `position` ASC",
	true,
	$current_sections,
	true
);

/**	********************
 *	Get all page-modules
 */
$all_page_modules = array();
$database->execute_query(
	"SELECT `name`,`addon_id` FROM `".TABLE_PREFIX."addons` WHERE `function`='page' ORDER BY `name`",
	true,
	$all_page_modules,
	true
);

/**
 *	Get all 'blocks' from the current frontend-template of the page
 */
require LEPTON_PATH."/templates/".(( $page_info['template'] == "" ) ? DEFAULT_TEMPLATE : $page_info['template'])."/info.php";
//  Make sure that the var #block exists and there is at least one entry
if(!isset($block))
{
    $block = array( $TEXT["MAIN"] );
}
$all_blocks = array();
foreach($block as $id => $name)
{
    $all_blocks[ $id ] = $name;
}
//$oTalg = talgos::getInstance();
//echo(LEPTON_tools::display($oTalg,'pre','ui message'));


/** ****************************
 *	Collect vars and render page
 */
$page_values = array(
	'alternative_url'=> THEME_URL.'/backend/backend/pages/',
	'action_url'	 => ADMIN_URL.'/pages/',
	'current_sections' => $current_sections,
	'page_info'	=> $page_info,
	'MODIFIED_BY' => $user['display_name'],
	'MODIFIED_BY_USERNAME' => $user['username'],
	'MODIFIED_WHEN' => $modified_ts,
	'leptoken'		=> get_leptoken(),	
	'SEC_ANCHOR'	=> SEC_ANCHOR,
	'section_blocks'	=> SECTION_BLOCKS,	
	
	
	
	'SETTINGS_LINK' => ADMIN_URL.'/pages/settings.php?page_id='.$page_info['page_id'],
	'MODIFY_LINK' => ADMIN_URL.'/pages/modify.php?page_id='.$page_info['page_id'],

	

	'all_pages'	=> $all_pages,
	'all_page_modules' => $all_page_modules,
	'blocks'	=> $all_blocks,
	'SEC_ANCHOR'	=> SEC_ANCHOR
);

$oTWIG->registerPath( THEME_PATH."theme","pages_sections" );
echo $oTWIG->render(
	"@theme/pages_sections.lte",
	$page_values
);

$admin->print_footer();
?>