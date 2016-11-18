<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		utf8_to_charset
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
 * Converts from UTF-8 to various charsets
 *
 * Will convert a string from UTF-8 to various charsets.
 * HTML-entities will not! be converted.
 * In case of error the returned string is unchanged, and a message is emitted.
 * Supported charsets are:
 * direct: iso_8859_1 iso_8859_2 iso_8859_3 iso_8859_4 iso_8859_5
 *         iso_8859_6 iso_8859_7 iso_8859_8 iso_8859_9 iso_8859_10 iso_8859_11
 * mb_convert_encoding: all wb charsets (except those from 'direct'); but not GB2312
 * iconv:  all wb charsets (except those from 'direct')
 *
 * @param  string  An UTF-8 encoded string
 * @param  string  The charset to convert to, defaults to DEFAULT_CHARSET
 * @return string  A string in a supported encoding, with all entities decoded, too.
 *                 String is unchanged in case of error.
 * @author thorn
 */
function utf8_to_charset( $str, $charset_out = DEFAULT_CHARSET )
{
	global $utf8_to_iso_8859_2, $utf8_to_iso_8859_3, $utf8_to_iso_8859_4, $utf8_to_iso_8859_5, $utf8_to_iso_8859_6, $utf8_to_iso_8859_7, $utf8_to_iso_8859_8, $utf8_to_iso_8859_9, $utf8_to_iso_8859_10, $utf8_to_iso_8859_11;
	$charset_out   = strtoupper( $charset_out );
	$wrong_ISO8859 = false;
	$converted     = false;
	
	if ( ( !function_exists( 'iconv' ) && !UTF8_MBSTRING && ( $charset_out == 'BIG5' || $charset_out == 'ISO-2022-JP' || $charset_out == 'ISO-2022-KR' ) ) || ( !function_exists( 'iconv' ) && $charset_out == 'GB2312' ) )
	{
		// Nothing we can do here :-(
		// Charset is one of those obscure ISO-2022... or BIG5, GB2312 or something
		// and we can't use mb_convert_encoding() or iconv();
		// Emit an error-message.
		trigger_error( "Can't convert into $charset_out without mb_convert_encoding() or iconv(). Use UTF-8 instead.", E_USER_WARNING );
		return ( $str );
	} //( !function_exists( 'iconv' ) && !UTF8_MBSTRING && ( $charset_out == 'BIG5' || $charset_out == 'ISO-2022-JP' || $charset_out == 'ISO-2022-KR' ) ) || ( !function_exists( 'iconv' ) && $charset_out == 'GB2312' )
	
	// the string comes from charset_to_utf8(), so we can skip this
	// replace HTML-entities first
	//if(preg_match('/&[#0-9a-zA-Z]+;/',$str))
	//	$str = utf8_entities_to_umlauts($str);
	
	// check if we need to convert
	if ( $charset_out == 'UTF-8' || utf8_isASCII( $str ) )
	{
		// Nothing to do. Just return
		return ( $str );
	} //$charset_out == 'UTF-8' || utf8_isASCII( $str )
	
	// Convert $str to $charset_out
	if ( substr( $charset_out, 0, 8 ) == 'ISO-8859' )
	{
		switch ( $charset_out )
		{
			case 'ISO-8859-1':
				$str = utf8_decode( $str );
				break;
			case 'ISO-8859-2':
				$str = strtr( $str, $utf8_to_iso_8859_2 );
				break;
			case 'ISO-8859-3':
				$str = strtr( $str, $utf8_to_iso_8859_3 );
				break;
			case 'ISO-8859-4':
				$str = strtr( $str, $utf8_to_iso_8859_4 );
				break;
			case 'ISO-8859-5':
				$str = strtr( $str, $utf8_to_iso_8859_5 );
				break;
			case 'ISO-8859-6':
				$str = strtr( $str, $utf8_to_iso_8859_6 );
				break;
			case 'ISO-8859-7':
				$str = strtr( $str, $utf8_to_iso_8859_7 );
				break;
			case 'ISO-8859-8':
				$str = strtr( $str, $utf8_to_iso_8859_8 );
				break;
			case 'ISO-8859-9':
				$str = strtr( $str, $utf8_to_iso_8859_9 );
				break;
			case 'ISO-8859-10':
				$str = strtr( $str, $utf8_to_iso_8859_10 );
				break;
			case 'ISO-8859-11':
				$str = strtr( $str, $utf8_to_iso_8859_11 );
				break;
			default:
				$wrong_ISO8859 = true;
		} //$charset_out
		if ( !$wrong_ISO8859 )
			$converted = true;
	} //substr( $charset_out, 0, 8 ) == 'ISO-8859'
	if ( !$converted && UTF8_MBSTRING && $charset_out != 'GB2312' )
	{
		// $charset is neither UTF-8 nor a known ISO-8859...
		// Try mb_convert_encoding() - but there's no GB2312 encoding in php's mb_* functions
		$str       = mb_convert_encoding( $str, $charset_out, 'UTF-8' );
		$converted = true;
	} //!$converted && UTF8_MBSTRING && $charset_out != 'GB2312'
	elseif ( !$converted ) // Try iconv
	{
		if ( function_exists( 'iconv' ) )
		{
			$str       = iconv( 'UTF-8', $charset_out, $str );
			$converted = true;
		} //function_exists( 'iconv' )
	} //!$converted
	if ( $converted )
	{
		return ( $str );
	} //$converted
	
	// Nothing we can do here :-(
	// Charset is one of those obscure ISO-2022... or BIG5, GB2312 or something
	// and we can't use mb_convert_encoding() or iconv();
	// Emit an error-message.
	trigger_error( "Can't convert into $charset_out without mb_convert_encoding() or iconv(). Use UTF-8 instead.", E_USER_WARNING );
	
	return $str;
}

?>