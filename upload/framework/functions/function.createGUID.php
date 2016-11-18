<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		createGUID
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
	 * Generate a globally unique identifier (GUID)
	 * Uses COM extension under Windows otherwise
	 * create a random GUID in the same style
	 * @return STR GUID
	 */
	function createGUID()
	{
		if ( function_exists( 'com_create_guid' ) )
		{
			$guid = com_create_guid();
			$guid = strtolower( $guid );
			if ( strpos( $guid, '{' ) == 0 )
			{
				$guid = substr( $guid, 1 );
			} //strpos( $guid, '{' ) == 0
			if ( strpos( $guid, '}' ) == strlen( $guid ) - 1 )
			{
				$guid = substr( $guid, 0, strlen( $guid ) - 1 );
			} //strpos( $guid, '}' ) == strlen( $guid ) - 1
			return $guid;
		} //function_exists( 'com_create_guid' )
		else
		{
			return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0x0fff ) | 0x4000, mt_rand( 0, 0x3fff ) | 0x8000, mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
		}
	} // end function createGUID()

?>