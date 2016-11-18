<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		valid_lepton_template
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

	/**
	 *	Function to 'validate' a lepton-template for out-dated functioncall.
	 *
	 *	@param	string	A valid path to a given template-file.
	 *	@return	bool	True, if the outdated function is NOT in the index.php of 
	 *					the frontend-template. FALSE if it is found.
	 **/
	function valid_lepton_template( $file )
	{
		if ( !file_exists( $file ) )
		{
			return false;
		}
		$suffix = pathinfo( $file, PATHINFO_EXTENSION );
		if ( $suffix == 'php' )
		{
			$string = implode( '', file( $file ) );
			if ( $string )
			{
				$tokens = token_get_all( $string );
				foreach ( $tokens as $i => $token )
				{
					if ( is_array( $token ) )
					{
						if ( strcasecmp( $token[ 1 ], 'register_frontend_modfiles' ) == 0 )
						{
							return false;
						}
					}
				}
				return true;
			}
		}
		return false;
	}

?>