<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		entities_to_umlauts
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
 * Convert a string from mixed html-entities/umlauts to pure $charset_out-umlauts
 * 
 * Will replace all numeric and named entities except
 * &gt; &lt; &apos; &quot; &#039; &nbsp;
 * @author thorn
 */
function entities_to_umlauts( $string, $charset_out = DEFAULT_CHARSET )
{
	$string = charset_to_utf8( $string, /*DEFAULT_CHARSET*/ LINK_CHARSET, true );
	//if(utf8_check($string)) // this check is to much time-consuming (this may fail only if AddDefaultCharset is set)
	$string = utf8_to_charset( $string, $charset_out );
	return ( $string );
}

?>