<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		umlauts_to_entities
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2014 LEPTON Project
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
 * Convert a string from mixed html-entities/umlauts to pure ASCII with HTML-entities
 * 
 * Will convert a string in $charset_in encoding to a pure ASCII string with HTML-entities.
 * @author thorn
 */
function umlauts_to_entities( $string, $charset_in = DEFAULT_CHARSET )
{
	$string = charset_to_utf8( $string, $charset_in, false );
	//if(utf8_check($string)) // this check is to much time-consuming (this may fail only if AddDefaultCharset is set)
	$string = utf8_fast_umlauts_to_entities( $string, false );
	return ( $string );
}

?>