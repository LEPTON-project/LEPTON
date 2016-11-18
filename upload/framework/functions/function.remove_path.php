<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		remove_path
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

	/*
	 * @param object &$wb: $wb from frontend or $admin from backend
	 * @return array: list of new entries
	 * @description: callback remove path in files/dirs stored in array
	 * @example: array_walk($array,'remove_path',PATH);
	 */
	/**
	 *  M.f.o.!  MARKED FOR OBSOLETE
	 *      As this one belongs to the results of the function 'directory_list'
	 *
	 */
	function remove_path( &$path, $key, $vars = '' )
	{
		$path = str_replace( $vars, '', $path );
	}

?>