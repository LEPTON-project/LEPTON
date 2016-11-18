<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		entities_to_7bit
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
 * convert Filenames to ASCII
 *
 * Convert all non-ASCII characters and all HTML-entities to their plain 7bit equivalents
 * Characters without an equivalent will be converted to hex-values.
 * The name entities_to_7bit() is somewhat misleading, but kept for compatibility-reasons.
 *
 * @param  string  Filename to convert (all encodings from charset_to_utf8() are allowed)
 * @return string  ASCII encoded string, to use as filename in page_filename() and media_filename
 * @author thorn
 */
function entities_to_7bit( $str )
{
	require_once(LEPTON_PATH.'/framework/summary.utf8.php');

	 // convert to UTF-8
    $str = charset_to_utf8($str);
    
    if(!utf8_check($str))
        return($str);
    
    // replace some specials
    $str = utf8_stripspecials($str, '_');
    
    // translate non-ASCII characters to ASCII
    $str = utf8_romanize($str);
    
    // missed some? - Many UTF-8-chars can't be romanized
    // convert to HTML-entities, and replace entites by hex-numbers
    $str = utf8_fast_umlauts_to_entities($str, false);
    $str = str_replace('&#039;', '&apos;', $str);

    $str = preg_replace_callback('/&#([0-9]+);/', create_function('$aMatches', 'return dechex($aMatches[1]);'),  $str);

    // maybe there are some &gt; &lt; &apos; &quot; &amp; &nbsp; left, replace them too
    $str = str_replace(array('&gt;', '&lt;', '&apos;', '\'', '&quot;', '&amp;'), '', $str);
    $str = str_replace('&amp;', '', $str);
    
    return($str);
}

?>