<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author		Website Baker Project, LEPTON Project
 * @copyright	2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license		http://www.gnu.org/licenses/gpl.html
 * @license_terms	please see LICENSE and COPYING files in your package
 * @reformatted		2013-07-14
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH'))
{
    include LEPTON_PATH . '/framework/class.secure.php';
}
else
{
    $oneback = "../";
    $root    = $oneback;
    $level   = 1;
    while (($level < 10) && (!file_exists($root . '/framework/class.secure.php')))
    {
        $root .= $oneback;
        $level += 1;
    }
    if (file_exists($root . '/framework/class.secure.php'))
    {
        include $root . '/framework/class.secure.php';
    }
    else
    {
        trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
    }
}
// end include class.secure.php

/**
 *	Define that this file has been loaded
 *
 *	To avoid double function-declarations (inside LEPTON) and to avoid a massiv use
 *	of "if(!function_exists('any_function_name_here_since_wb_2.5.0')) {" we've to place it
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
			'js' => array(),
			'jquery' => array() 
		),
		'backend' => array(
			'css' => array(),
			'meta' => array(),
			'js' => array(),
			'jquery' => array() 
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
	
	require_once LEPTON_PATH .'/framework/functions/function.rm_full_dir.php';
	 
	require_once LEPTON_PATH .'/framework/functions/function.directory_list.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.scan_current_dir.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.file_list.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.make_dir.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.change_mode.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.is_parent.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.level_count.php';

	require_once LEPTON_PATH .'/framework/functions/function.root_parent.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.get_page_headers.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.get_page_footers.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.get_page_title.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.get_menu_title.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.get_parent_titles.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.get_parent_ids.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.get_page_trail.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.get_subs.php';
		
	require_once LEPTON_PATH .'/framework/functions/function.page_filename.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.media_filename.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.page_link.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.create_access_file.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.mime_content_type.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.make_thumb.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.extract_permission.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.delete_page.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.load_module.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.load_template.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.load_language.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.upgrade_module.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.get_variable_content.php';
	
	require_once LEPTON_PATH .'/framework/functions/function.get_modul_version.php';

	require_once LEPTON_PATH .'/framework/functions/function.createGUID.php';

	require_once LEPTON_PATH .'/framework/functions/function.js_alert_encode.php';

	require_once LEPTON_PATH .'/framework/functions/function.addItems.php';

	require_once LEPTON_PATH .'/framework/functions/function.get_active_sections.php';
	
	// New in LEPTON 2 - load the module specific language file (used in backend/frontend)
	require_once LEPTON_PATH .'/framework/functions/function.load_module_language.php';
	
    
} //!defined( 'FUNCTIONS_FILE_LOADED' )
// end .. if functions is loaded 
?>