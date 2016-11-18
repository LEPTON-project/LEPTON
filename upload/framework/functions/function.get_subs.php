<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		get_subs
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
 *	Function to get all sub pages id's from a given page id.
 *
 *	@param	int		Any valid page_id as 'root'.
 *	@param	array	A given linear array to store the results. Pass-by-reference!
 *
 *	@return	nothing	Keep in mind, that param "subs" is pass-by-reference!
 *
 *	@example	$all_subpages_ids = array();
 *				get_subs( 5, $all_subpages_ids);
 *
 *				Will result in a linear list like e.g. "[5,6,8,9,11,7]" as the subids
 *				are sorted by position (in the page-tree);
 *
 */
function get_subs( $parent, &$subs )
{
	// Global reference to the database-instance.
	global $database;

	// Get id's
	$all = array();
	$database->execute_query( 
		'SELECT `page_id` FROM `' . TABLE_PREFIX . 'pages` WHERE `parent` = ' . $parent." ORDER BY `position`",
		true,
		$all
	);
	
	foreach($all as &$fetch)
	{
		$subs[] = $fetch[ 'page_id' ];
		
		// Get subs of this sub - recursive call!
		get_subs( $fetch[ 'page_id' ], $subs );
	}
}

?>