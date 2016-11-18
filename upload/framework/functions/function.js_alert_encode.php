<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		js_alert_encode
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
	 *  As for some special chars, e.g. german-umlauts, inside js-alerts we are in the need to escape them.
	 *  Keep in mind, that you will to have unescape them befor you use them inside a js!
	 *
	 */
	function js_alert_encode( $string )
	{
		$entities = array(
			'&auml;' => "%E4",
			'&Auml;' => "%C4",
			'&ouml;' => "%F6",
			'&Ouml;' => "%D6",
			'&uuml;' => "%FC",
			'&Uuml;' => "%DC",
			'&szlig;' => "%DF",
			'&euro;' => "%u20AC",
			'$' => "%24" 
		);
		return str_replace( array_keys( $entities ), array_values( $entities ), $string );
	}

?>