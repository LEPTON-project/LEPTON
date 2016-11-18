<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * @class			module_edit_css
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

/**
 *	FILE REQUIRED TO EDIT THE OPTIONAL MODULE CSS FILES
 *
 * This file contains routines to edit optional module files (frontend.css and backend.css) and provides a global solution for all modules.
 * To use this function, include this file from your module (e.g. from modify.php) and simply call the function edit_css('your_module_directory') - that's it.
 */

// include class.secure.php to protect this file and the whole CMS!
if ( defined( 'LEPTON_PATH' ) )
{
	include( LEPTON_PATH . '/framework/class.secure.php' );
} //defined( 'LEPTON_PATH' )
else
{
	$oneback = "../";
	$root    = $oneback;
	$level   = 1;
	while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
	{
		$root .= $oneback;
		$level += 1;
	} //( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) )
	if ( file_exists( $root . '/framework/class.secure.php' ) )
	{
		include( $root . '/framework/class.secure.php' );
	} //file_exists( $root . '/framework/class.secure.php' )
	else
	{
		trigger_error( sprintf( "[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER[ 'SCRIPT_NAME' ] ), E_USER_ERROR );
	}
}
// end include class.secure.php

/**
 *	Checks if the specified optional module file exists.
 *
 */
 require_once (LEPTON_PATH .'/framework/functions/function.mod_file_exists.php');


/** 
 *	This function displays the "Edit CSS" button in modify.php
 *
 */ 
 require_once (LEPTON_PATH .'/framework/functions/function.edit_module_css.php');


/**
 *	This function displays a button to toggle between CSS files (invoked from edit_css.php)
 */
 require_once (LEPTON_PATH .'/framework/functions/function.toggle_css_file.php'); 

?>