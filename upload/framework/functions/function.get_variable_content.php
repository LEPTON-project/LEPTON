<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		get_variable_content
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

	function get_variable_content( $search, $data, $striptags = true, $convert_to_entities = true )
	{
		$match = '';
		// search for $variable followed by 0-n whitespace then by = then by 0-n whitespace
		// then either " or ' then 0-n characters then either " or ' followed by 0-n whitespace and ;
		// the variable name is returned in $match[1], the content in $match[3]
		if ( preg_match( '/(\$' . $search . ')\s*=\s*("|\')(.*)\2\s*;/', $data, $match ) )
		{
			if ( strip_tags( trim( $match[ 1 ] ) ) == '$' . $search )
			{
				// variable name matches, return it's value
				$match[ 3 ] = ( $striptags == true ) ? strip_tags( $match[ 3 ] ) : $match[ 3 ];
				$match[ 3 ] = ( $convert_to_entities == true ) ? htmlentities( $match[ 3 ] ) : $match[ 3 ];
				return $match[ 3 ];
			} //strip_tags( trim( $match[ 1 ] ) ) == '$' . $search
		} //preg_match( '/(\$' . $search . ')\s*=\s*("|\')(.*)\2\s*;/', $data, $match )
		return false;
	} // end function get_variable_content()

?>