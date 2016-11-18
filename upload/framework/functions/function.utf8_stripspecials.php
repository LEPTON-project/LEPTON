<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		utf8_stripspecials
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
 * Removes special characters (nonalphanumeric) from a UTF-8 string
 *
 * This function adds the controlchars 0x00 to 0x19 to the array of
 * stripped chars (they are not included in $UTF8_SPECIAL_CHARS2)
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @param  string $string     The UTF8 string to strip of special chars
 * @param  string $repl       Replace special with this string
 * @param  string $additional Additional chars to strip (used in regexp char class)
 */
function utf8_stripspecials( $string, $repl = '', $additional = '' )
{
	global $UTF8_SPECIAL_CHARS2;

  static $specials = null;
  if(is_null($specials)){
    $specials = preg_quote($UTF8_SPECIAL_CHARS2, '/');
  }

  return preg_replace('/['.$additional.'\x00-\x19'.$specials.']/u',$repl,$string);
}

?>