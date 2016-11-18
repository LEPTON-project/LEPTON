<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		edit_module_css
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @reformatted 2013-05-31
 *
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
			$loader->prependPath( LEPTON_PATH."/templates/".DEFAULT_THEME."/templates/" );
			
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

?>