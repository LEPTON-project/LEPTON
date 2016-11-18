<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		is_parent
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
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
	 * check if the page with the given id has children
	 *
	 * @access public
	 * @param  integer $page_id - page ID
	 * @return mixed   (false if page hasn't children, parent id if not)
	 *
	 * 2011-08-22 Bianka Martinovic
	 *    Should be moved to new page object when ready
	 *    I don't understand why this returns the parent page, as methods
	 *    beginning with is* should always return boolean only IMHO
	 **/
	function is_parent( $page_id )
	{
		global $database;
		// Get parent
		$sql    = 'SELECT `parent` FROM `' . TABLE_PREFIX . 'pages` WHERE `page_id` = ' . $page_id;
		$parent = $database->get_one( $sql );
		// If parent isnt 0 return its ID
		if ( is_null( $parent ) )
		{
			return false;
		} //is_null( $parent )
		else
		{
			return $parent;
		}
	} // end function is_parent()

?>