<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		utf8_isASCII
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

/*
 * Checks if a string contains 7bit ASCII only
 *
 * @author thorn
 */
function utf8_isASCII( $str )
{
	if ( preg_match( '/[\x80-\xFF]/', $str ) )
		return false;
	else
		return true;
}

?>