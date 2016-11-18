<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		mod_file_exists
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @reformatted 2013-05-31
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
 *	Checks if the specified optional module file exists.
 *
 */
if ( !function_exists( 'mod_file_exists' ) )
{
	function mod_file_exists( $mod_dir, $mod_file = 'frontend.css' )
	{
		$found = false;
		$paths = array(
			 "/",
			"/css/",
			"/js/"
		);
		foreach ( $paths as &$p )
		{
			if ( true == file_exists( LEPTON_PATH . '/modules/' . $mod_dir . $p . $mod_file ) )
			{
				$found = true;
				break;
			}
		}
		return $found;
	}
}

?>