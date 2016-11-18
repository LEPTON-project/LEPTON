<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		utf8_fast_entities_to_umlauts
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
 * faster replacement for utf8_entities_to_umlauts()
 * not all features of utf8_entities_to_umlauts() --> utf8_unhtml() are supported!
 * @author thorn
 */
function utf8_fast_entities_to_umlauts( $str )
{
	if ( UTF8_MBSTRING )
	{
		// we need this for use with mb_convert_encoding
		$str = str_replace( array(
			 '&amp;',
			'&gt;',
			'&lt;',
			'&quot;',
			'&#039;',
			'&nbsp;' 
		), array(
			 '&amp;amp;',
			'&amp;gt;',
			'&amp;lt;',
			'&amp;quot;',
			'&amp;#39;',
			'&amp;nbsp;' 
		), $str );
		// we need two mb_convert_encoding()-calls - is this a bug?
		// mb_convert_encoding("ÃƒÂ¶&ouml;", 'UTF-8', 'HTML-ENTITIES'); // with string in utf-8-encoding doesn't work. Result: "ÃƒÆ’Ã‚Â¶ÃƒÂ¶"
		// Work-around: convert all umlauts to entities first ("ÃƒÂ¶&ouml;"->"&ouml;&ouml;"), then all entities to umlauts ("&ouml;&ouml;"->"ÃƒÂ¶ÃƒÂ¶")
		return ( mb_convert_encoding( mb_convert_encoding( $str, 'HTML-ENTITIES', 'UTF-8' ), 'UTF-8', 'HTML-ENTITIES' ) );
	} //UTF8_MBSTRING
	else
	{
		global $named_entities;
		global $numbered_entities;
		$str = str_replace( $named_entities, $numbered_entities, $str );
		$str = preg_replace( "/&#([0-9]+);/e", "code_to_utf8($1)", $str );
	}
	return ( $str );
}

?>