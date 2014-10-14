<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		get_subs
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

	// Function to get all sub pages id's
	function get_subs( $parent, $subs )
	{
		// Connect to the database
		global $database;
		// Get id's
		$sql   = 'SELECT `page_id` FROM `' . TABLE_PREFIX . 'pages` WHERE `parent` = ' . $parent;
		$query = $database->query( $sql );
		if ( $query->numRows() > 0 )
		{
			while ( false !== ( $fetch = $query->fetchRow() ) )
			{
				$subs[] = $fetch[ 'page_id' ];
				// Get subs of this sub
				$subs   = get_subs( $fetch[ 'page_id' ], $subs );
			} //false !== ( $fetch = $query->fetchRow() )
		} //$query->numRows() > 0
		// Return subs array
		return $subs;
	}

?>