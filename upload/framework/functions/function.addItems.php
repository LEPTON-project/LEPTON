<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		addItems
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
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

	function addItems( $for, $path, $footer = false )
	{
		global $HEADERS, $FOOTERS;
		$trail  = explode( '/', $path );
		$subdir = array_pop( $trail );
		
		$mod_headers = array();
		$mod_footers = array();
		
		if ( $footer )
		{
			$add_to = &$FOOTERS;
			$to_load = 'footers.inc.php';
		}
		else
		{
			$add_to = &$HEADERS;
			$to_load = 'headers.inc.php';
		}
		
		require( $path . '/' . $to_load );
		
		if ( true == $footer )
		{
			$aRefArray = &$mod_footers;
		}
		else
		{
			$aRefArray = &$mod_headers;
		}
		
		if ( count( $aRefArray ) )
		{
			foreach ( array(
				'css',
				'js'
			) as $key )
			{
				if ( !isset( $aRefArray[ $for ][ $key ] ) )
				{
					continue;
				}
				foreach ( $aRefArray[ $for ][ $key ] as &$item )
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
					
					$is_ok = true;
					if( $key === "css" ) {
					    foreach($add_to[ $for ][ $key ] as $temp_ref)
					    {
					        if($temp_ref['file'] == $item['file'])
					        {
					            $is_ok = false;
					        }
					    }
					} elseif ($key === "js" )
					{
					    foreach($add_to[ $for ][ $key ] as $temp_ref)
					    {
					        if($item === $temp_ref)
					        {
					            $is_ok = false;
					        }
					    }
					
					}
					
					if(true === $is_ok) $add_to[ $for ][ $key ][] = $item;
				}
				// $add_to[ $for ][ $key ] = array_merge( $add_to[ $for ][ $key ], $aRefArray[ $for ][ $key ] );
			}
		} //count( $aRefArray )
		
		if ( $footer && file_exists( $path . $for . '_body.js' ) )
		{
			$FOOTERS[ $for ][ 'js' ][] = '/modules/' . $subdir . '_body.js';
		}
		
	} // end function addItems()

?>