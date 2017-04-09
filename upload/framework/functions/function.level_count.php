<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		level_count
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
	 * counts the levels from given page_id to root
	 *
	 * @access public
	 * @param  integer  $page_id
	 * @return integer  level (>=0)
	 *
	 **/
	function level_count( $page_id )
	{
		global $database;
		// Get page parent
		$sql    = 'SELECT `parent` FROM `' . TABLE_PREFIX . 'pages` WHERE `page_id` = ' . $page_id;
		$parent = $database->get_one( $sql );
		if ( $parent > 0 )
		{
			// Get the level of the parent
			$sql   = 'SELECT `level` FROM `' . TABLE_PREFIX . 'pages` WHERE `page_id` = ' . $parent;
			$level = $database->get_one( $sql );
			return $level + 1;
		} //$parent > 0
		else
		{
			return 0;
		}
	} // function level_count()

?>