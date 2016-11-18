<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		extract_permission
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
	 * Function to work-out a single part of an octal permission value
	 *
	 * @param mixed $octal_value: an octal value as string (i.e. '0777') or real octal integer (i.e. 0777 | 777)
	 * @param string $who: char or string for whom the permission is asked( U[ser] / G[roup] / O[thers] )
	 * @param string $action: char or string with the requested action( r[ead..] / w[rite..] / e|x[ecute..] )
	 * @return boolean
	 */
	function extract_permission( $octal_value, $who, $action )
	{
		// Make sure that all arguments are set and $octal_value is a real octal-integer
		if ( ( $who == '' ) || ( $action == '' ) || ( preg_match( '/[^0-7]/', (string) $octal_value ) ) )
		{
			// invalid argument, so return false
			return false;
		} //( $who == '' ) || ( $action == '' ) || ( preg_match( '/[^0-7]/', (string) $octal_value ) )
		// convert into a decimal-integer to be sure having a valid value
		$right_mask = octdec( $octal_value );
		switch ( strtolower( $action[ 0 ] ) )
		{
			// get action from first char of $action
			// set the $action related bit in $action_mask
			case 'r':
				// set read-bit only (2^2)
				$action_mask = 4;
				break;
			case 'w':
				// set write-bit only (2^1)
				$action_mask = 2;
				break;
			case 'e':
			case 'x':
				// set execute-bit only (2^0)
				$action_mask = 1;
				break;
			default:
				// undefined action name, so return false
				return false;
		} //strtolower( $action[ 0 ] )
		switch ( strtolower( $who[ 0 ] ) )
		{
			// get who from first char of $who
			// shift action-mask into the right position
			case 'u':
				// shift left 3 bits
				$action_mask <<= 3;
			case 'g':
				// shift left 3 bits
				$action_mask <<= 3;
			case 'o':
				/* NOP */
				break;
			default:
				// undefined who, so return false
				return false;
		} //strtolower( $who[ 0 ] )
		// return result of binary-AND
		return ( ( $right_mask & $action_mask ) != 0 );
	}

?>