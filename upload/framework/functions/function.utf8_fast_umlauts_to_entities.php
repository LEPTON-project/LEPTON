<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		utf8_fast_umlauts_to_entities
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

/*
 * faster replacement for utf8_umlauts_to_entities()
 * not all features of utf8_umlauts_to_entities() --> utf8_tohtml() are supported!
 * @author thorn
 */
function utf8_fast_umlauts_to_entities( $string, $named_entities = true )
{
	if ( UTF8_MBSTRING )
		return ( mb_convert_encoding( $string, 'HTML-ENTITIES', 'UTF-8' ) );
	else
	{
		global $named_entities;
		global $numbered_entities;
		$new = "";
		$i   = 0;
		$len = strlen( $string );
		if ( $len == 0 )
			return $string;
		do
		{
			if ( ord( $string{$i} ) <= 127 )
				$ud = $string{$i++};
			elseif ( ord( $string{$i} ) <= 223 )
				$ud = ( ord( $string{$i++} ) - 192 ) * 64 + ( ord( $string{$i++} ) - 128 );
			elseif ( ord( $string{$i} ) <= 239 )
				$ud = ( ord( $string{$i++} ) - 224 ) * 4096 + ( ord( $string{$i++} ) - 128 ) * 64 + ( ord( $string{$i++} ) - 128 );
			elseif ( ord( $string{$i} ) <= 247 )
				$ud = ( ord( $string{$i++} ) - 240 ) * 262144 + ( ord( $string{$i++} ) - 128 ) * 4096 + ( ord( $string{$i++} ) - 128 ) * 64 + ( ord( $string{$i++} ) - 128 );
			else
				$ud = ord( $string{$i++} ); // error!
			if ( $ud > 127 )
			{
				$new .= "&#$ud;";
			} //$ud > 127
			else
			{
				$new .= $ud;
			}
		} while ( $i < $len );
		$string = $new;
		if ( $named_entities )
			$string = str_replace( $numbered_entities, $named_entities, $string );
	}
	return ( $string );
}

?>