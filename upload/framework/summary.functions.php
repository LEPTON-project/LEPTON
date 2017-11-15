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
		"/framework/functions/function.rm_full_dir.php",
		"/framework/functions/function.directory_list.php",
		"/framework/functions/function.scan_current_dir.php",
		"/framework/functions/function.file_list.php",
		"/framework/functions/function.make_dir.php",
		"/framework/functions/function.change_mode.php",
		"/framework/functions/function.is_parent.php",
		"/framework/functions/function.level_count.php",
		"/framework/functions/function.root_parent.php",
		"/framework/functions/function.get_page_headers.php",
		"/framework/functions/function.get_page_footers.php",
		"/framework/functions/function.get_page_title.php",
		"/framework/functions/function.get_menu_title.php",
		"/framework/functions/function.get_parent_titles.php",
		"/framework/functions/function.get_parent_ids.php",
		"/framework/functions/function.get_page_trail.php",
		"/framework/functions/function.get_subs.php",
		"/framework/functions/function.save_filename.php",
		"/framework/functions/function.page_link.php",
		"/framework/functions/function.create_access_file.php",
		"/framework/functions/function.make_thumb.php",
		"/framework/functions/function.extract_permission.php",
		"/framework/functions/function.delete_page.php",
		"/framework/functions/function.load_module.php",
		"/framework/functions/function.load_template.php",
		"/framework/functions/function.load_language.php",
		"/framework/functions/function.upgrade_module.php",
		"/framework/functions/function.get_variable_content.php",
		"/framework/functions/function.get_modul_version.php",
		"/framework/functions/function.createGUID.php",
		"/framework/functions/function.js_alert_encode.php",
		"/framework/functions/function.addItems.php",
		"/framework/functions/function.get_active_sections.php",
		"/framework/functions/function.load_module_language.php"   // New in LEPTON 2 - load the module specific language file (used in backend/frontend)
	);
	 
	LEPTON_handle::include_files ($file_names);

}
?>