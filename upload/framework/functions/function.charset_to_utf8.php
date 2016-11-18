<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		charset_to_utf8
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
 * Converts from various charsets to UTF-8
 *
 * Will convert a string from various charsets to UTF-8.
 * HTML-entities may be converted, too.
 * In case of error the returned string is unchanged, and a message is emitted.
 * Supported charsets are:
 * direct: iso_8859_1 iso_8859_2 iso_8859_3 iso_8859_4 iso_8859_5
 *         iso_8859_6 iso_8859_7 iso_8859_8 iso_8859_9 iso_8859_10 iso_8859_11
 * mb_convert_encoding: all wb charsets (except those from 'direct'); but not GB2312
 * iconv:  all wb charsets (except those from 'direct')
 *
 * @param  string  A string in supported encoding
 * @param  string  The charset to convert from, defaults to DEFAULT_CHARSET
 * @return string  A string in UTF-8-encoding, with all entities decoded, too.
 *                 String is unchanged in case of error.
 * @author thorn
 */
function charset_to_utf8( $str, $charset_in = DEFAULT_CHARSET, $decode_entities = true )
{
global $iso_8859_2_to_utf8, $iso_8859_3_to_utf8, $iso_8859_4_to_utf8, $iso_8859_5_to_utf8, $iso_8859_6_to_utf8, $iso_8859_7_to_utf8, $iso_8859_8_to_utf8, $iso_8859_9_to_utf8, $iso_8859_10_to_utf8, $iso_8859_11_to_utf8;
    $charset_in = strtoupper($charset_in);
    if ($charset_in == "") { $charset_in = 'UTF-8'; }
    $wrong_ISO8859 = false;
    $converted = false;

    if((!function_exists('iconv') && !UTF8_MBSTRING && ($charset_in=='BIG5' || $charset_in=='ISO-2022-JP' || $charset_in=='ISO-2022-KR')) || (!function_exists('iconv') && $charset_in=='GB2312')) {
        // Nothing we can do here :-(
        // Charset is one of those obscure ISO-2022... or BIG5, GB2312 or something
        // and we can't use mb_convert_encoding() or iconv();
        // Emit an error-message.
        trigger_error("Can't convert from $charset_in without mb_convert_encoding() or iconv(). Use UTF-8 instead.", E_USER_WARNING);
        return($str);
    }

    // check if we have UTF-8 or a plain ASCII string
    if($charset_in == 'UTF-8' || utf8_isASCII($str)) {
        // we have utf-8. Just replace HTML-entities and return
        if($decode_entities && preg_match('/&[#0-9a-zA-Z]+;/',$str))
            return(utf8_fast_entities_to_umlauts($str));
        else // nothing to do
            return($str);
    }
    
    // Convert $str to utf8
    if(substr($charset_in,0,8) == 'ISO-8859') {
        switch($charset_in) {
            case 'ISO-8859-1': $str=utf8_encode($str); break;
            case 'ISO-8859-2': $str=strtr($str, $iso_8859_2_to_utf8); break;
            case 'ISO-8859-3': $str=strtr($str, $iso_8859_3_to_utf8); break;
            case 'ISO-8859-4': $str=strtr($str, $iso_8859_4_to_utf8); break;
            case 'ISO-8859-5': $str=strtr($str, $iso_8859_5_to_utf8); break;
            case 'ISO-8859-6': $str=strtr($str, $iso_8859_6_to_utf8); break;
            case 'ISO-8859-7': $str=strtr($str, $iso_8859_7_to_utf8); break;
            case 'ISO-8859-8': $str=strtr($str, $iso_8859_8_to_utf8); break;
            case 'ISO-8859-9': $str=strtr($str, $iso_8859_9_to_utf8); break;
            case 'ISO-8859-10': $str=strtr($str, $iso_8859_10_to_utf8); break;
            case 'ISO-8859-11': $str=strtr($str, $iso_8859_11_to_utf8); break;
            default: $wrong_ISO8859 = true;
        }
        if(!$wrong_ISO8859)
            $converted = true;
    }
    if(!$converted && UTF8_MBSTRING && $charset_in != 'GB2312') {
        // $charset is neither UTF-8 nor a known ISO-8859...
        // Try mb_convert_encoding() - but there's no GB2312 encoding in php's mb_* functions
        $str = mb_convert_encoding($str, 'UTF-8', $charset_in);
        $converted = true;
    } elseif(!$converted) { // Try iconv
        if(function_exists('iconv')) {
            $str = iconv($charset_in, 'UTF-8', $str);
            $converted = true;
        }
    }
    if($converted) {
        // we have utf-8, now replace HTML-entities and return
        if($decode_entities && preg_match('/&[#0-9a-zA-Z]+;/',$str))
            $str = utf8_fast_entities_to_umlauts($str);
        return($str);
    }
    
    // Nothing we can do here :-(
    // Charset is one of those obscure ISO-2022... or BIG5, GB2312 or something
    // and we can't use mb_convert_encoding() or iconv();
    // Emit an error-message.
    trigger_error("Can't convert from $charset_in without mb_convert_encoding() or iconv(). Use UTF-8 instead.", E_USER_WARNING);
    
    return $str;
}

?>