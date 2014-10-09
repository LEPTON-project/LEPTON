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
 * @copyright       2010-2014 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @reformatted 2013-05-31
 *
 */

/**
This file contains routines to edit optional module files (frontend.css and backend.css) and provides a global solution for all modules.
To use this function, include this file from your module (e.g. from modify.php) and simply call the function edit_css('your_module_directory') - that's it.
NOTE: Some functions were added for module developers to make the creation of own modules easier
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

/*
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
FUNCTIONS REQUIRED TO EDIT THE OPTIONAL MODULE CSS FILES
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
*/

/**
 *	Checks if the specified optional module file exists.
 *
 */
if ( !function_exists( 'mod_file_exists' ) )
{
	function mod_file_exists( $mod_dir, $mod_file = 'frontend.css' )
	{
		$found = false;
		$paths = array(
			 "/",
			"/css/",
			"/js/"
		);
		foreach ( $paths as &$p )
		{
			if ( true == file_exists( LEPTON_PATH . '/modules/' . $mod_dir . $p . $mod_file ) )
			{
				$found = true;
				break;
			}
		}
		return $found;
	}
}

/** 
 *	This function displays the "Edit CSS" button in modify.php
 *
 */ 
if ( !function_exists( 'edit_module_css' ) )
{
	function edit_module_css( $mod_dir )
	{
		global $page_id, $section_id, $TEXT;
		global $parser, $loader;
		
		// check if the required edit_module_css.php file exists
		if ( !file_exists( LEPTON_PATH . '/modules/edit_module_files.php' ) ) return;
				
		// check if frontend.css or backend.css exist
		$frontend_css = mod_file_exists( $mod_dir, 'frontend.css' );
		$backend_css  = mod_file_exists( $mod_dir, 'backend.css' );
		
		// output the "edit CSS" form
		if ( $frontend_css || $backend_css )
		{
			if (!isset($parser)) require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
			$loader->prependPath( dirname(__FILE__)."/../templates/".DEFAULT_THEME."/templates/" );
			
			$fields = array(
				'LEPTON_URL'	=> LEPTON_URL,
				'page_id'	=> $page_id,
				'section_id'	=> $section_id,
				'mod_dir'	=> $mod_dir,
				'edit_file'	=> ( $frontend_css ) ? 'frontend.css' : 'backend.css',
				'label_submit'	=> $TEXT['CAP_EDIT_CSS']
			);
			
			echo $parser->render(
				'edit_module_css_form.lte',
				$fields
			);
		}
	}
}

/**
 *	This function displays a button to toggle between CSS files (invoked from edit_css.php)
 */
if ( !function_exists( 'toggle_css_file' ) )
{
	function toggle_css_file( $mod_dir, $base_css_file = 'frontend.css' )
	{
		global $page_id, $section_id, $TEXT;
		// check if the required edit_module_css.php file exists
		if ( !file_exists( LEPTON_PATH . '/modules/edit_module_files.php' ) ) return false;
		
		// do sanity check of specified css file
		if ( !in_array( $base_css_file, array(
			'frontend.css',
			'css/frontend.css',
			'backend.css',
			'css/backend.css'
		) ) )
			return;
		
		// display button to toggle between the two CSS files: frontend.css, backend.css
		// Patch Aldus
		switch($base_css_file) {
			case 'frontend.css': 
				$toggle_file = 'backend.css';
				break;
			case 'backend.css':
				$toggle_file = 'frontend.css';
				break;
			case 'css/frontend.css':
				$toggle_file = 'css/backend.css';
				break;
			case 'css/backend.css':
				$toggle_file = 'css/frontend.css';
				break;
		}
		// Aldus: another patch for the css-paths.
		$toggle_file_label = str_replace("css/", "", $toggle_file);
		
		if ( mod_file_exists( $mod_dir, $toggle_file ) ) {
		
			if (!isset($parser)) require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
			$loader->prependPath( dirname(__FILE__)."/../templates/".DEFAULT_THEME."/templates/" );
			
			$fields = array(
				'LEPTON_URL'	=> LEPTON_URL,
				'page_id'	=> $page_id,
				'section_id'	=> $section_id,
				'mod_dir'	=> $mod_dir,
				'edit_file'	=> $toggle_file_label,
				'label_submit'	=> ucfirst($toggle_file_label)
			);
			
			echo $parser->render(
				'edit_module_css_form.lte',
				$fields
			);
			
			return true;
		} else {
			return false;
		}
	}
}
?>