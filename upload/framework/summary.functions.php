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


/**
 *	Define that this file has been loaded
 *
 *	To avoid double function-declarations (inside LEPTON) and to avoid a massiv use
 *	of "if(!function_exists('any_function_name_here')) {" we've to place it
 *	inside this condition-body!
 *
 */
if ( !defined( 'FUNCTIONS_FILE_LOADED' ) )
{
	define( 'FUNCTIONS_FILE_LOADED', true );

	// global array to catch header files
	global $HEADERS, $FOOTERS;
	$HEADERS = array(
		'frontend' => array(
			'css' => array(),
			'meta' => array(),
			'js' => array()
		),
		'backend' => array(
			'css' => array(),
			'meta' => array(),
			'js' => array()
		) 
	);
	
	$FOOTERS = array(
		'frontend' => array(
			'script' => array(),
			'js' => array() 
		),
		'backend' => array(
			'script' => array(),
			'js' => array() 
		) 
	);

	// include function files
	$file_names = array (
		"rm_full_dir",
		"directory_list",
		"scan_current_dir",
		"file_list",
		"make_dir",
		"change_mode",
		"is_parent",
		"level_count",
		"root_parent",
		"get_page_headers",
		"get_page_footers",
		"get_page_title",
		"get_menu_title",
		"get_parent_titles",
		"get_parent_ids",
		"get_page_trail",
		"get_subs",
		"save_filename",
		"page_link",
		"create_access_file",
		"make_thumb",
		"extract_permission",
		"delete_page",
		"load_module",
		"load_template",
		"load_language",
		"upgrade_module",
		"get_variable_content",
		"get_modul_version",
		"createGUID",
		"js_alert_encode",
		"addItems",
		"get_active_sections",
		"load_module_language"   // New in LEPTON 2 - load the module specific language file (used in backend/frontend)
	);
	 
	LEPTON_handle::register($file_names);

}
?>