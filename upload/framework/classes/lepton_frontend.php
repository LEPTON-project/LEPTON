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
if ( defined( 'LEPTON_PATH' ) )
{
	include( LEPTON_PATH . '/framework/class.secure.php' );
}
else
{
	$oneback = "../";
	$root    = $oneback;
	$level   = 1;
	while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
	{
					$root .= $oneback;
					$level += 1;
	}
	if ( file_exists( $root . '/framework/class.secure.php' ) )
	{
		include( $root . '/framework/class.secure.php' );
	}
	else
	{
		trigger_error( sprintf( "[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER[ 'SCRIPT_NAME' ] ), E_USER_ERROR );
	}
}
// end include class.secure.php


/**
 *	@date		2017-10-07
 *	@notice		Please keep in mind, that class "frontend" extends class "LEPTON_core"
 *
 */
class LEPTON_frontend extends LEPTON_core
{
	// defaults
	var $default_link, $default_page_id;
	// when multiple blocks are used, show home page blocks on 
	// pages where no content is defined (search, login, ...)
	var $default_block_content = true;
	
	// page details
	// page database row
	var $page;
	var $page_id, $page_title, $menu_title, $parent, $root_parent, $level, $position, $visibility;
	var $page_description, $page_keywords, $page_link;
	var $page_trail = array();
	
	var $page_access_denied;
	var $page_no_active_sections;
	
	// website settings
	var $website_title, $website_description, $website_keywords, $website_header, $website_footer;
	
	// ugly database stuff
	var $extra_where_sql, $sql_where_language;
	
	function page_select()
	{
		global $page_id, $database ;

		// Check if we should add page language sql code
		if ( PAGE_LANGUAGES )
		{
			$this->sql_where_language = " AND language = '" . LANGUAGE . "'";
		}
		// Get default page
		// Check for a page id
		$table_p          = TABLE_PREFIX . 'pages';
		$table_s          = TABLE_PREFIX . 'sections';
		$now              = time();
		$query_default    = "SELECT `p`.`page_id`, `link` FROM `$table_p` AS `p` INNER JOIN `$table_s` USING(`page_id`) WHERE `parent` = '0' 
		AND `visibility` = 'public'	AND (($now>=`publ_start` OR `publ_start`=0) AND ($now<=`publ_end` OR `publ_end`=0))	$this->sql_where_language	ORDER BY `p`.`position` ASC LIMIT 1";
		$get_default      = $database->query( $query_default );
		$default_num_rows = $get_default->numRows();
		if ( !isset( $page_id ) OR !is_numeric( $page_id ) )
		{
			// Go to or show default page
			if ( $default_num_rows > 0 )
			{
				$fetch_default         = $get_default->fetchRow();
				$this->default_link    = $fetch_default[ 'link' ];
				$this->default_page_id = $fetch_default[ 'page_id' ];
				// Check if we should redirect or include page inline
				if ( HOMEPAGE_REDIRECTION )
				{
					// Redirect to page
					header( "Location: " . $this->page_link( $this->default_link ) );
					exit();
				}
				else
				{
					// Include page inline
					$this->page_id = $this->default_page_id;
				}
			}
			else
			{
				exit();
			}
		}
		else
		{
			$this->page_id = $page_id;
		}
		// Get default page link
		if ( !isset( $fetch_default ) )
		{
			$fetch_default         = $get_default->fetchRow();
			$this->default_link    = $fetch_default[ 'link' ];
			$this->default_page_id = $fetch_default[ 'page_id' ];
		}
		return true;
	}
	
	function get_page_details()
	{
		global $database;
		if ( $this->page_id != 0 )
		{
			// Query page details
			$query_page = "SELECT * FROM " . TABLE_PREFIX . "pages WHERE page_id = '{$this->page_id}'";
			$get_page   = $database->query( $query_page );
			// Make sure page was found in database
			if ( $get_page->numRows() == 0 )
			{
				// Print page not found message
				exit( "Page not found" );
			}
			// Fetch page details
			$this->page = $get_page->fetchRow();
			// Check if the page language is also the selected language. If not, send headers again.
			if ( $this->page[ 'language' ] != LANGUAGE )
			{
				if ( isset( $_SERVER[ 'QUERY_STRING' ] ) && $_SERVER[ 'QUERY_STRING' ] != '' ) // check if there is an query-string
				{
					header( 'Location: ' . $this->page_link( $this->page[ 'link' ] ) . '?' . $_SERVER[ 'QUERY_STRING' ] . '&lang=' . $this->page[ 'language' ] );
				}
				else
				{
					header( 'Location: ' . $this->page_link( $this->page[ 'link' ] ) . '?lang=' . $this->page[ 'language' ] );
				}
				exit();
			}
			// Begin code to set details as either variables of constants
			// Page ID
			if ( !defined( 'PAGE_ID' ) )
			{
				define( 'PAGE_ID', $this->page[ 'page_id' ] );
			}
			// Page Title
			if ( !defined( 'PAGE_TITLE' ) )
			{
				define( 'PAGE_TITLE', $this->page[ 'page_title' ] );
			}
			$this->page_title = PAGE_TITLE;
			// Menu Title
			$menu_title       = $this->page[ 'menu_title' ];
			if ( $menu_title != '' )
			{
				if ( !defined( 'MENU_TITLE' ) )
				{
					define( 'MENU_TITLE', $menu_title );
				}
			}
			else
			{
				if ( !defined( 'MENU_TITLE' ) )
				{
					define( 'MENU_TITLE', PAGE_TITLE );
				}
			}
			$this->menu_title = MENU_TITLE;
			// Page parent
			if ( !defined( 'PARENT' ) )
			{
				define( 'PARENT', $this->page[ 'parent' ] );
			}
			$this->parent = $this->page[ 'parent' ];
			// Page root parent
			if ( !defined( 'ROOT_PARENT' ) )
			{
				define( 'ROOT_PARENT', $this->page[ 'root_parent' ] );
			}
			$this->root_parent = $this->page[ 'root_parent' ];
			// Page level
			if ( !defined( 'LEVEL' ) )
			{
				define( 'LEVEL', $this->page[ 'level' ] );
			}
			$this->level    = $this->page[ 'level' ];
			$this->position = $this->page[ 'position' ];
			// Page visibility
			if ( !defined( 'VISIBILITY' ) )
			{
				define( 'VISIBILITY', $this->page[ 'visibility' ] );
			}
			$this->visibility = $this->page[ 'visibility' ];
			// Page trail
			foreach ( explode( ',', $this->page[ 'page_trail' ] ) AS $pid )
			{
				$this->page_trail[ $pid ] = $pid;
			}
			// Page description
			$this->page_description = $this->page[ 'description' ];
			if ( $this->page_description != '' )
			{
				define( 'PAGE_DESCRIPTION', $this->page_description );
			}
			else
			{
				define( 'PAGE_DESCRIPTION', WEBSITE_DESCRIPTION );
			}
			// Page keywords
			$this->page_keywords = $this->page[ 'keywords' ];
			// Page link
			$this->link          = $this->page_link( $this->page[ 'link' ] );
					
			// End code to set details as either variables of constants
		}
					
		// Figure out what template to use
		if ( !defined( 'TEMPLATE' ) )
		{
			if ( isset( $this->page[ 'template' ] ) AND $this->page[ 'template' ] != '' )
			{
				if ( file_exists( LEPTON_PATH . '/templates/' . $this->page[ 'template' ] . '/index.php' ) )
				{
					define( 'TEMPLATE', $this->page[ 'template' ] );
				}
				else
				{
					define( 'TEMPLATE', DEFAULT_TEMPLATE );
				}
			}
			else
			{
				define( 'TEMPLATE', DEFAULT_TEMPLATE );
			}
		}
		// Set the template dir
		define( 'TEMPLATE_DIR', LEPTON_URL . '/templates/' . TEMPLATE );
			
		// Check if user is allowed to view this page
		if ( $this->page && $this->page_is_visible( $this->page ) == false )
		{
			if ( VISIBILITY == 'deleted' OR VISIBILITY == 'none' )
			{
				// User isnt allowed on this page so tell them
				$this->page_access_denied = true;
			}
			elseif ( VISIBILITY == 'private' OR VISIBILITY == 'registered' )
			{
				// Check if the user is authenticated
				if ( $this->is_authenticated() == false )
				{
					// User needs to login first
					header( "Location: " . LEPTON_URL . "/account/login.php?redirect=" . $this->link );
					exit( 0 );
				}
				else
				{
					// User isnt allowed on this page so tell them
					$this->page_access_denied = true;
				}
													
			}
		}
		// check if there is at least one active section
		if ( $this->page && $this->page_is_active( $this->page ) == false )
		{
			$this->page_no_active_sections = true;
		}
	}
	
	function get_website_settings()
	{
		global $database;
					
		// set visibility SQL code
		// never show no-vis, hidden or deleted pages
		$this->extra_where_sql = "visibility != 'none' AND visibility != 'hidden' AND visibility != 'deleted'";
		// Set extra private sql code
		if ( $this->is_authenticated() == false )
		{
			// if user is not authenticated, don't show private pages either
			$this->extra_where_sql .= " AND visibility != 'private'";
			// and 'registered' without frontend login doesn't make much sense!
			if ( FRONTEND_LOGIN == false )
			{
				$this->extra_where_sql .= " AND visibility != 'registered'";
			}
		}
		$this->extra_where_sql .= $this->sql_where_language;
				
		// Work-out if any possible in-line search boxes should be shown
		if ( SEARCH == 'public' )
		{
			define( 'SHOW_SEARCH', true );
		}
		elseif ( SEARCH == 'private' AND VISIBILITY == 'private' )
		{
			define( 'SHOW_SEARCH', true );
		}
		elseif ( SEARCH == 'private' AND $this->is_authenticated() == true )
		{
			define( 'SHOW_SEARCH', true );
		}
		elseif ( SEARCH == 'registered' AND $this->is_authenticated() == true )
		{
			define( 'SHOW_SEARCH', true );
		}
		else
		{
			define( 'SHOW_SEARCH', false );
		}
		// Work-out if menu should be shown
		if ( !defined( 'SHOW_MENU' ) )
		{
			define( 'SHOW_MENU', true );
		}
		// Work-out if login menu constants should be set
		if ( FRONTEND_LOGIN )
		{
			// Set login menu constants
			define( 'LOGIN_URL', LEPTON_URL . '/account/login.php' );
			define( 'LOGOUT_URL', LEPTON_URL . '/account/logout.php' );
			define( 'FORGOT_URL', LEPTON_URL . '/account/forgot.php' );
			define( 'PREFERENCES_URL', LEPTON_URL . '/account/preferences.php' );
			define( 'SIGNUP_URL', LEPTON_URL . '/account/signup.php' );
		}
	}
	
	/**
	 *	replace all "[wblink{page_id}]" with real links
	 *	@param	string &$content : reference to global $content
	 *	@return	nothing
	 */
	function preprocess( &$content )
	{
		global $database;
		if ( preg_match_all( '/\[wblink([0-9]+)\]/isU', $content, $ids ) )
		{
			$new_ids = array_unique( $ids[ 1 ] );
			foreach ( $new_ids as $key => &$page_id )
			{
				$link = $database->get_one( 'SELECT `link` FROM `' . TABLE_PREFIX . 'pages` WHERE `page_id` = ' . $page_id );
				if ( !is_null( $link ) )
				{
					$content = str_replace( $ids[ 0 ][ $key ], $this->page_link( $link ), $content );
				}
			}
		}
	}
}

?>