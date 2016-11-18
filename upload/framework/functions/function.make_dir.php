<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		media_dirs_rw
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
	 * Create directories recursive
	 *
	 * @param string   $dir_name - directory to create
	 * @param ocatal   $dir_mode - access mode
	 * @return boolean result of operation
	 *
	 * @internal ralf 2011-08-05 - added recursive parameter for mkdir()
	 * @todo ralf 2011-08-05     - checking for !is_dir() is not a good idea, perhaps $dirname
	 * is not a valid path, i.e. a file - any better ideas?
	 */
	function make_dir( $dir_name, $dir_mode = OCTAL_DIR_MODE )
	{
		if ( !is_dir( $dir_name ) )
		{
			$umask = umask( 0 );
			mkdir( $dir_name, $dir_mode, true );
			umask( $umask );
			return true;
		} //!is_dir( $dir_name )
		return false;
	} // end function make_dir()

?>