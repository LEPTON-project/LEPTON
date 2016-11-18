<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		__addItems
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

	function __addItems( $for, $path, $footer = false )
	{
		global $HEADERS, $FOOTERS;
		$trail  = explode( '/', $path );
		$subdir = array_pop( $trail );
		
		$mod_headers = array();
		$mod_footers = array();
		
		if ( $footer )
		{
			$add_to =& $FOOTERS;
			$to_load = 'footers.inc.php';
		} //$footer
		else
		{
			$add_to =& $HEADERS;
			$to_load = 'headers.inc.php';
		}
		
		require( $path . '/' . $to_load );
		
		if ( $footer )
		{
			$array =& $mod_footers;
		} //$footer
		else
		{
			$array =& $mod_headers;
		}
		
		if ( count( $array ) )
		{
			foreach ( array(
				'css',
				'meta',
				'js',
				'jquery' 
			) as $key )
			{
				if ( !isset( $array[ $for ][ $key ] ) )
				{
					continue;
				} //!isset( $array[ $for ][ $key ] )
				foreach ( $array[ $for ][ $key ] as &$item )
				{
					// let's see if the path is relative (i.e., does not contain the current subdir)
					if ( isset( $item[ 'file' ] ) && !preg_match( "#/$subdir/#", $item[ 'file' ] ) )
					{
						if ( file_exists( $path . '/' . $item[ 'file' ] ) )
						{
							// treat path as relative, add modules subfolder
							$item[ 'file' ] = str_ireplace( LEPTON_PATH, '', $path ) . '/' . $item[ 'file' ];
						}
					}
				}
				$add_to[ $for ][ $key ] = array_merge( $add_to[ $for ][ $key ], $array[ $for ][ $key ] );
			}
		} //count( $array )
		
		if ( $footer && file_exists( $path . $for . '_body.js' ) )
		{
			$FOOTERS[ $for ][ 'js' ][] = '/modules/' . $subdir . '_body.js';
		}
		
	} // end function __addItems()

?>