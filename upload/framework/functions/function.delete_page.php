<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		delete_page
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

/**
 * delete a page
 *
 * @access public
 * @param  integer $page_id
 * @return void
 *
 **/
function delete_page( $page_id )
{
	global $admin, $database, $MESSAGE;
	
	// Find out more about the page
	$results_array = array();
	$database->execute_query(
		'SELECT `page_id`, `link`, `parent` FROM `' . TABLE_PREFIX . 'pages` WHERE `page_id` = ' . $page_id,
		true,
		$results_array,
		false
	);
	
	if ( $database->is_error() )
	{
		$admin->print_error( $database->get_error() );
	}
	if ( count($results_array) == 0 )
	{
		$admin->print_error( $MESSAGE[ 'PAGES_NOT_FOUND' ] );
	}
	$parent		= $results_array[ 'parent' ];
	$link		= $results_array[ 'link' ];
	
	// Get the sections that belong to the page
	$all_sections = array();;
	$database->execute_query(
		'SELECT `section_id`, `module` FROM `' . TABLE_PREFIX . 'sections` WHERE `page_id` = ' . $page_id,
		true,
		$all_sections
	);

	foreach($all_sections as &$section)
	{
		// Set section id
		$section_id = $section[ 'section_id' ];
			
		// Include the modules delete file if it exists
		if ( file_exists( LEPTON_PATH . '/modules/' . $section[ 'module' ] . '/delete.php' ) )
		{
			include( LEPTON_PATH . '/modules/' . $section[ 'module' ] . '/delete.php' );
		}
	}
	
	// Update the pages table
	$sql = 'DELETE FROM `' . TABLE_PREFIX . 'pages` WHERE `page_id` = ' . $page_id;
	$database->query( $sql );
	
	if ( $database->is_error() )
	{
		$admin->print_error( $database->get_error() );
	}
	
	// Update the sections table
	$sql = 'DELETE FROM `' . TABLE_PREFIX . 'sections` WHERE `page_id` = ' . $page_id;
	$database->query( $sql );
	if ( $database->is_error() )
	{
		$admin->print_error( $database->get_error() );
	}
	
	// Include the ordering class or clean-up ordering
	include_once( LEPTON_PATH . '/framework/class.order.php' );
	$order = new order( TABLE_PREFIX . 'pages', 'position', 'page_id', 'parent' );
	$order->clean( $parent );
	
	// Unlink the page access file and directory
	$directory = LEPTON_PATH . PAGES_DIRECTORY . $link;
	$filename  = $directory . PAGE_EXTENSION;
	$directory .= '/';
	if ( file_exists( $filename ) )
	{
		if ( !is_writable( LEPTON_PATH . PAGES_DIRECTORY . '/' ) )
		{
			$admin->print_error( $MESSAGE[ 'PAGES_CANNOT_DELETE_ACCESS_FILE' ] );
		}
		else
		{
			unlink( $filename );
			if ( file_exists( $directory ) && ( rtrim( $directory, '/' ) != LEPTON_PATH . PAGES_DIRECTORY ) && ( substr( $link, 0, 1 ) != '.' ) )
			{
				rm_full_dir( $directory );
			}
		}
	}
}

?>