<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		media_filename
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

	// Function to convert a desired media filename to a clean filename
	function media_filename( $string )
	{
		require_once( LEPTON_PATH . '/framework/summary.utf8.php' );
		$string = entities_to_7bit( $string );
		// Now remove all bad characters
		$bad    = array(
			 '\'',
			'"',
			'`',
			'!',
			'@',
			'#',
			'$',
			'%',
			'^',
			'&',
			'*',
			'=',
			'+',
			'|',
			'/',
			'\\',
			';',
			':',
			',',
			'?' 
		);
		$string = str_replace( $bad, '', $string );
		// replace multiple dots in filename to single dot and (multiple) dots at the end of the filename to nothing
		$string = preg_replace( array(
			 '/\.+/',
			'/\.+$/',
			'/\s/' 
		), array(
			 '.',
			'',
			'_' 
		), $string );
		// Clean any page spacers at the end of string
		$string = trim( $string );
		// Finally, return the cleaned string
		return $string;
	}

?>