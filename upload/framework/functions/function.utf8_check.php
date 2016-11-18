<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		utf8_check
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
 * Tries to detect if a string is in Unicode encoding
 *
 * @author <bmorel@ssi.fr>
 * @link   http://www.php.net/manual/en/function.utf8-encode.php
 */
function utf8_check( $Str )
{
	for ($i=0; $i<strlen($Str); $i++) {
  $b = ord($Str[$i]);
  if ($b < 0x80) continue; # 0bbbbbbb
  elseif (($b & 0xE0) == 0xC0) $n=1; # 110bbbbb
  elseif (($b & 0xF0) == 0xE0) $n=2; # 1110bbbb
  elseif (($b & 0xF8) == 0xF0) $n=3; # 11110bbb
  elseif (($b & 0xFC) == 0xF8) $n=4; # 111110bb
  elseif (($b & 0xFE) == 0xFC) $n=5; # 1111110b
  else return false; # Does not match any model
  for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
   if ((++$i == strlen($Str)) || ((ord($Str[$i]) & 0xC0) != 0x80))
   return false;
  }
 }
 return true;
}

?>