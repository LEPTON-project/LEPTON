<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

/*
 * A part of this file is based on 'utf8.php' from the DokuWiki-project.
 * (http://www.splitbrain.org/projects/dokuwiki):
 **
 * UTF8 helper functions
 * @license    LGPL (http://www.gnu.org/copyleft/lesser.html)
 * @author     Andreas Gohr <andi@splitbrain.org>
 **
 * modified from thorn, Jan. 2008
 *
 * most of the original functions appeared to be to slow with large strings, so i replaced them with my own ones
 * thorn, Mar. 2008
 */

// Functions we use:
//   entities_to_7bit()
//   entities_to_umlauts2()
//   umlauts_to_entities2() 

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
 * check for mb_string support
 */
//define('UTF8_NOMBSTRING',1); // uncomment this to forbid use of mb_string-functions
if ( !defined( 'UTF8_MBSTRING' ) )
{
	if ( function_exists( 'mb_substr' ) && !defined( 'UTF8_NOMBSTRING' ) )
	{
		define( 'UTF8_MBSTRING', 1 );
	} //function_exists( 'mb_substr' ) && !defined( 'UTF8_NOMBSTRING' )
	else
	{
		define( 'UTF8_MBSTRING', 0 );
	}
} //!defined( 'UTF8_MBSTRING' )

if ( UTF8_MBSTRING )
{
	mb_internal_encoding( 'UTF-8' );
} //UTF8_MBSTRING


//call needed functions
require_once (LEPTON_PATH.'/framework/functions/function.utf8_isASCII.php');

require_once (LEPTON_PATH.'/framework/functions/function.utf8_check.php');

require_once (LEPTON_PATH.'/framework/functions/function.utf8_romanize.php');

require_once (LEPTON_PATH.'/framework/functions/function.utf8_stripspecials.php');

require_once (LEPTON_PATH.'/framework/functions/function.utf8_fast_entities_to_umlauts.php');

require_once (LEPTON_PATH.'/framework/functions/function.utf8_fast_umlauts_to_entities.php');

require_once (LEPTON_PATH.'/framework/functions/function.charset_to_utf8.php');

require_once (LEPTON_PATH.'/framework/functions/function.utf8_to_charset.php');

require_once (LEPTON_PATH.'/framework/functions/function.entities_to_7bit.php');


?>
