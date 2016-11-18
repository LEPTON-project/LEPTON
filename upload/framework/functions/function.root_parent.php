<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		root_parent
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
	 * Function to work out root parent
	 *
	 * @access public
	 * @param  integer $page_id
	 * @return integer ID of the root page
	 *
	 **/
	function root_parent( $page_id )
	{
		global $database;
		// Get page details
		$sql        = 'SELECT `parent`, `level` FROM `' . TABLE_PREFIX . 'pages` WHERE `page_id` = ' . $page_id;
		$query_page = $database->query( $sql );
		$fetch_page = $query_page->fetchRow();
		$parent     = $fetch_page[ 'parent' ];
		$level      = $fetch_page[ 'level' ];
		if ( $level == 1 )
		{
			return $parent;
		} //$level == 1
		elseif ( $parent == 0 )
		{
			return $page_id;
		} //$parent == 0
		else
		{
			// Figure out what the root parents id is
			$parent_ids = array_reverse( get_parent_ids( $page_id ) );
			return $parent_ids[ 0 ];
		}
	} // end root_parent()

?>