<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		register_frontend_modfiles
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2014 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
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

// MARKED DEPRECATED, will be removed after 2.0.0
// Function to add optional module Javascript or CSS stylesheets into the <head> section of the frontend

	function register_frontend_modfiles( $file_id = "css" )
	{
		// sanity check of parameter passed to the function
		$file_id = strtolower( $file_id );
		if ( $file_id !== "css" && $file_id !== "javascript" && $file_id !== "js" && $file_id !== "jquery" )
		{
			return;
		}
		
		global $wb, $database, $include_head_link_css, $include_head_links;
		// define default baselink and filename for optional module javascript and stylesheet files
		$head_links = "";
		
		switch ( $file_id )
		{
			case 'css':
				$base_link = '<link href="' . LEPTON_URL . '/modules/{MODULE_DIRECTORY}/frontend.css"';
				$base_link .= ' rel="stylesheet" type="text/css" media="screen" />';
				$base_file = "frontend.css";
				if ( !empty( $include_head_link_css ) )
				{
					$head_links .= !strpos( $head_links, $include_head_link_css ) ? $include_head_link_css : '';
					$include_head_link_css = '';
				}
				break;
			case 'jquery':
				$head_links .= bind_jquery( $file_id );
				break;
			case 'js':
				$base_link = '<script src="' . LEPTON_URL . '/modules/{MODULE_DIRECTORY}/frontend.js" type="text/javascript"></script>';
				$base_file = "frontend.js";
				if ( !empty( $include_head_links ) )
				{
					$head_links .= !strpos( $head_links, $include_head_links ) ? $include_head_links : '';
					$include_head_links = '';
				}
				break;
			default:
				break;
		}
		
		if ( $file_id != 'jquery' )
		{
			// gather information for all models embedded on actual page
			$page_id       = $wb->page_id;
			$query_modules = $database->query( "SELECT module FROM " . TABLE_PREFIX . "sections	WHERE page_id=$page_id AND module<>'wysiwyg'" );
			
			while ( $row = $query_modules->fetchRow() )
			{
				// check if page module directory contains a frontend.js or frontend.css file
				if ( file_exists( LEPTON_PATH . "/modules/" . $row[ 'module' ] . "/$base_file" ) )
				{
					// create link with frontend.js or frontend.css source for the current module
					$tmp_link = str_replace( "{MODULE_DIRECTORY}", $row[ 'module' ], $base_link );
					
					// define constant indicating that the register_frontent_files was invoked
					if ( $file_id == 'css' )
					{
						if ( !defined( 'MOD_FRONTEND_CSS_REGISTERED' ) )
							define( 'MOD_FRONTEND_CSS_REGISTERED', true );
					}
					else
					{
						if ( !defined( 'MOD_FRONTEND_JAVASCRIPT_REGISTERED' ) )
							define( 'MOD_FRONTEND_JAVASCRIPT_REGISTERED', true );
					}
					// ensure that frontend.js or frontend.css is only added once per module type
					if ( strpos( $head_links, $tmp_link ) === false )
					{
						$head_links .= $tmp_link . "\n";
					}
				}
			}
			// include the Javascript email protection function
			if ( $file_id != 'css' && file_exists( LEPTON_PATH . '/modules/droplets/js/mdcr.js' ) )
			{
				$head_links .= '<script src="' . LEPTON_URL . '/modules/droplets/js/mdcr.js" type="text/javascript"></script>' . "\n";
			}
			elseif ( $file_id != 'css' && file_exists( LEPTON_PATH . '/modules/output_filter/js/mdcr.js' ) )
			{
				$head_links .= '<script src="' . LEPTON_URL . '/modules/output_filter/js/mdcr.js" type="text/javascript"></script>' . "\n";
			}
		}
		echo $head_links;
	}
?>