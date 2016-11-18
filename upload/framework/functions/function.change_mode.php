<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		change_mode
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
	 * chmod to octal access mode defined by initialize.php;
	 * not used on Windows Systems
	 *
	 * @access public
	 * @param  string   $name - directory or file
	 * @return boolean
	 *
	 **/
	function change_mode( $name )
	{
		if ( OPERATING_SYSTEM != 'windows' )
		{
			// Only chmod if os is not windows
			if ( is_dir( $name ) )
			{
				$mode = OCTAL_DIR_MODE;
			} //is_dir( $name )
			else
			{
				$mode = OCTAL_FILE_MODE;
			}
			if ( file_exists( $name ) )
			{
				$umask = umask( 0 );
				chmod( $name, $mode );
				umask( $umask );
				return true;
			} //file_exists( $name )
			else
			{
				return false;
			}
		} //OPERATING_SYSTEM != 'windows'
		else
		{
			return true;
		}
	} // function change_mode()

?>