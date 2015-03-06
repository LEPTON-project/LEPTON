<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		register_frontend_modfiles_body
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2015 LEPTON Project
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

// Function to add optional module Javascript into the <body> section of the frontend   MARKED DEPRECATED, will be removed after 2.0.0

	function register_frontend_modfiles_body( $file_id = "js" )
	{
		// sanity check of parameter passed to the function
		$file_id = strtolower( $file_id );
		if ( $file_id !== "css" && $file_id !== "javascript" && $file_id !== "js" && $file_id !== "jquery" )
		{
			return;
		}
		
		// define constant indicating that the register_frontent_files was invoked
		if ( !defined( 'MOD_FRONTEND_BODY_JAVASCRIPT_REGISTERED' ) )
			define( 'MOD_FRONTEND_BODY_JAVASCRIPT_REGISTERED', true );
		global $wb, $database, $include_body_links;
		// define default baselink and filename for optional module javascript files
		$body_links = "";
		
		/* include the Javascript jquery api  */
		$body_links .= bind_jquery( $file_id );
		
		if ( $file_id !== "css" && $file_id == "js" && $file_id !== "jquery" )
		{
			$base_link = '<script src="' . LEPTON_URL . '/modules/{MODULE_DIRECTORY}/frontend_body.js" type="text/javascript"></script>';
			$base_file = "frontend_body.js";
			
			// ensure that frontend_body.js is only added once per module type
			if ( !empty( $include_body_links ) )
			{
				if ( strpos( $body_links, $include_body_links ) === false )
				{
					$body_links .= $include_body_links;
				}
				$include_body_links = '';
			}
			
			// gather information for all models embedded on actual page
			$page_id       = $wb->page_id;
			$query_modules = $database->query( "SELECT module FROM " . TABLE_PREFIX . "sections WHERE page_id=$page_id AND module<>'wysiwyg'" );
			
			while ( $row = $query_modules->fetchRow() )
			{
				// check if page module directory contains a frontend_body.js file
				if ( file_exists( LEPTON_PATH . "/modules/" . $row[ 'module' ] . "/$base_file" ) )
				{
					// create link with frontend_body.js source for the current module
					$tmp_link = str_replace( "{MODULE_DIRECTORY}", $row[ 'module' ], $base_link );
					
					// define constant indicating that the register_frontent_files_body was invoked
					if ( !defined( 'MOD_FRONTEND_BODY_JAVASCRIPT_REGISTERED' ) )
					{
						define( 'MOD_FRONTEND_BODY_JAVASCRIPT_REGISTERED', true );
					}
					
					// ensure that frontend_body.js is only added once per module type
					if ( strpos( $body_links, $tmp_link ) === false )
					{
						$body_links .= $tmp_link;
					}
				}
			}
		}
		
		print $body_links . "\n";
	}
?>