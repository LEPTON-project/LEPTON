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
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @reformatted 2013-05-31
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

include_once( LEPTON_PATH . '/framework/class.securecms.php' );

// Include mailer class (subclass of PHPmailer)
//require_once( LEPTON_PATH . "/framework/class.lepmailer.php" );

class wb extends SecureCMS
{
	
	public $password_chars = 'a-zA-Z0-9\_\-\!\#\*\+';
	
	// General initialization public function
	// performed when frontend or backend is loaded.
	
	public function __construct()
	{
		
	}
	
	// Check whether a page is visible or not.
	// This will check page-visibility and user- and group-rights.
	/* page_is_visible() returns
	false: if page-visibility is 'none' or 'deleted', or page-vis. is 'registered' or 'private' and user isn't allowed to see the page.
	true: if page-visibility is 'public' or 'hidden', or page-vis. is 'registered' or 'private' and user _is_ allowed to see the page.
	*/
	public function page_is_visible( $page )
	{
		// First check if visibility is 'none', 'deleted'
		$show_it = false; // shall we show the page?
		switch ( $page[ 'visibility' ] )
		{
			case 'none':
			case 'deleted':
				$show_it = false;
				break;
			case 'hidden':
			case 'public':
				$show_it = true;
				break;
			case 'private':
			case 'registered':
				if ( $this->is_authenticated() == true )
				{
					$show_it = ( $this->is_group_match( $this->get_groups_id(), $page[ 'viewing_groups' ] ) || $this->is_group_match( $this->get_user_id(), $page[ 'viewing_users' ] ) );
				} //$this->is_authenticated() == true
		} //$page[ 'visibility' ]
		
		return ( $show_it );
	}
	
	public function section_is_active( $section_id )
	{
		global $database;
		$now = time();
		$sql = 'SELECT COUNT(*) FROM `' . TABLE_PREFIX . 'sections` ';
		$sql .= 'WHERE (' . $now . ' BETWEEN `publ_start` AND `publ_end`) OR ';
		$sql .= '(' . $now . ' > `publ_start` AND `publ_end`=0) ';
		$sql .= 'AND `section_id`=' . $section_id;
		return ( $database->get_one( $sql ) != false );
	}
	// Check if there is at least one active section on this page
	public function page_is_active( $page )
	{
		global $database;
		$now = time();
		$sql = 'SELECT COUNT(*) FROM `' . TABLE_PREFIX . 'sections` ';
		$sql .= 'WHERE (' . $now . ' BETWEEN `publ_start` AND `publ_end`) OR ';
		$sql .= '(' . $now . ' > `publ_start` AND `publ_end`=0) ';
		$sql .= 'AND `page_id`=' . (int) $page[ 'page_id' ];
		return ( $database->get_one( $sql ) != false );
	}
	
	// Check whether we should show a page or not (for front-end)
	public function show_page( $page )
	{
		if ( !is_array( $page ) )
		{
			$sql = 'SELECT `page_id`, `visibility`, `viewing_groups`, `viewing_users` ';
			$sql .= 'FROM `' . TABLE_PREFIX . 'pages` WHERE `page_id`=' . (int) $page;
			if ( ( $res_pages = $database->query( $sql ) ) != null )
			{
				if ( !( $page = $res_pages->fetchRow() ) )
				{
					return false;
				} //!( $page = $res_pages->fetchRow() )
			} //( $res_pages = $database->query( $sql ) ) != null
		} //!is_array( $page )
		return ( $this->page_is_visible( $page ) && $this->page_is_active( $page ) );
	}
	
	// Check if the user is already authenticated or not
	public function is_authenticated()
	{
		if ( isset( $_SESSION[ 'USER_ID' ] ) && $_SESSION[ 'USER_ID' ] != "" && is_numeric( $_SESSION[ 'USER_ID' ] ) )
		{
			return true;
		} //isset( $_SESSION[ 'USER_ID' ] ) && $_SESSION[ 'USER_ID' ] != "" && is_numeric( $_SESSION[ 'USER_ID' ] )
		else
		{
			return false;
		}
	}
	
	
	public function page_link( $link )
	{
		// Check for :// in the link (used in URL's) as well as mailto:
		if ( strstr( $link, '://' ) == '' && substr( $link, 0, 7 ) != 'mailto:' )
		{
			return LEPTON_URL . PAGES_DIRECTORY . $link . PAGE_EXTENSION;
		} //strstr( $link, '://' ) == '' && substr( $link, 0, 7 ) != 'mailto:'
		else
		{
			return $link;
		}
	}
	
	// Get POST data
	public function get_post( $field )
	{
		return isset( $_POST[ $field ] ) ? $_POST[ $field ] : null;
	}
	
	/**
	 *	Get data from $_POST and try to escape it.
	 *	When 'field' as an arry - each element will be escaped.
	 */
	public function get_post_escaped( $field )
	{
		$result = $this->get_post( $field );
		if (is_array($result)){
			return array_map("addslashes", $result);
		}
		return ( is_null( $result ) ) ? null : addslashes( $result );
	}
	
	// Get GET data
	public function get_get( $field )
	{
		return isset( $_GET[ $field ] ) ? $_GET[ $field ] : null;
	}
	
	// Get SESSION data
	public function get_session( $field )
	{
		return isset( $_SESSION[ $field ] ) ? $_SESSION[ $field ] : null;
	}
	
	// Get SERVER data
	public function get_server( $field )
	{
		return isset( $_SERVER[ $field ] ) ? $_SERVER[ $field ] : null;
	}
	
	// Get the current users id
	public function get_user_id()
	{
		return isset( $_SESSION[ 'USER_ID' ] ) ? $_SESSION[ 'USER_ID' ] : '';
	}
	
	// Get the current users group id (deprecated)
	public function get_group_id()
	{
		return isset( $_SESSION[ 'GROUP_ID' ] ) ? $_SESSION[ 'GROUP_ID' ] : '';
	}
	
	// Get the current users group ids
	public function get_groups_id()
	{
		return explode( ",", isset( $_SESSION[ 'GROUPS_ID' ] ) ? $_SESSION[ 'GROUPS_ID' ] : '' );
	}
	
	// Get the current users group name
	public function get_group_name()
	{
		return implode( ",", $_SESSION[ 'GROUP_NAME' ] );
	}
	
	// Get the current users group name
	public function get_groups_name()
	{
		return isset( $_SESSION[ 'GROUP_NAME' ] ) ? $_SESSION[ 'GROUP_NAME' ] : '';
	}
	
	// Get the current users username
	public function get_username()
	{
		return isset( $_SESSION[ 'USERNAME' ] ) ? $_SESSION[ 'USERNAME' ] : '';
	}
	
	// Get the current users display name
	public function get_display_name()
	{
		return isset( $_SESSION[ 'DISPLAY_NAME' ] ) ? $_SESSION[ 'DISPLAY_NAME' ] : '';
	}
	
	// Get the current users email address
	public function get_email()
	{
		return isset( $_SESSION[ 'EMAIL' ] ) ? $_SESSION[ 'EMAIL' ] : '';
	}
	
	// Get the current users home folder
	public function get_home_folder()
	{
		return isset( $_SESSION[ 'HOME_FOLDER' ] ) ? $_SESSION[ 'HOME_FOLDER' ] : '';
	}
	
	// Get the current users timezone
	public function get_timezone_string()
	{
		return isset( $_SESSION[ 'TIMEZONE_STRING' ] ) ? $_SESSION[ 'TIMEZONE_STRING' ] : DEFAULT_TIMEZONESTRING;
	}
	
	/* ****************
	 * check if one or more group_ids are in both group_lists
	 *
	 * @access public
	 * @param mixed $groups_list1: an array or a coma seperated list of group-ids
	 * @param mixed $groups_list2: an array or a coma seperated list of group-ids
	 * @return bool: true there is a match, otherwise false
	 */
	public function is_group_match( $groups_list1 = '', $groups_list2 = '' )
	{
		if ( $groups_list1 == '' )
		{
			return false;
		} //$groups_list1 == ''
		if ( $groups_list2 == '' )
		{
			return false;
		} //$groups_list2 == ''
		if ( !is_array( $groups_list1 ) )
		{
			$groups_list1 = explode( ',', $groups_list1 );
		} //!is_array( $groups_list1 )
		if ( !is_array( $groups_list2 ) )
		{
			$groups_list2 = explode( ',', $groups_list2 );
		} //!is_array( $groups_list2 )
		
		return ( sizeof( array_intersect( $groups_list1, $groups_list2 ) ) != 0 );
	}
	
	/* ****************
	 * check if current user is member of at least one of given groups
	 * ADMIN (uid=1) always is treated like a member of any groups
	 *
	 * @access public
	 * @param mixed $groups_list: an array or a coma seperated list of group-ids
	 * @return bool: true if current user is member of one of this groups, otherwise false
	 */
	public function ami_group_member( $groups_list = '' )
	{
		if ( $this->get_user_id() == 1 )
		{
			return true;
		} //$this->get_user_id() == 1
		return $this->is_group_match( $groups_list, $this->get_groups_id() );
	}
	
	
	/**
	 *	Validate supplied email address
	 *
	 */
	public function validate_email( $email )
	{
		if ( preg_match( '/^([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z-_]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}$/', $email ) )
		{
			return true;
		} 
		else
		{
			return false;
		}
	}

}
?>