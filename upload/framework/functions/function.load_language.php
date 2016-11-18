<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		load_language
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
	 *  Load language information from a given language-file into the current DB
	 *
	 *  @param  string  Any valid path.
	 *
	 */
	function load_language( $file )
	{
		global $database, $admin, $MESSAGE;
		if ( file_exists( $file ) && preg_match( '#^([A-Z]{2}.php)#', basename( $file ) ) )
		{
			$language_license  = null;
			$language_code     = null;
			$language_version  = null;
			$language_guid     = null;
			$language_name     = null;
			$language_author   = null;
			$language_platform = null;
			require( $file );
			if ( isset( $language_name ) )
			{
				if ( ( !isset( $language_license ) ) || ( !isset( $language_code ) ) || ( !isset( $language_version ) ) || ( !isset( $language_guid ) ) )
				{
					$admin->print_error( $MESSAGE[ "LANG_MISSING_PARTS_NOTICE" ], $language_name );
				}
				
				// Check that it doesn't already exist
				$sqlwhere = '`type` = \'language\' AND `directory` = \'' . $language_directory . '\'';
				$sql      = 'SELECT COUNT(*) FROM `' . TABLE_PREFIX . 'addons` WHERE ' . $sqlwhere;
				if ( $database->get_one( $sql ) )
				{
					$sql_job = "update";
				}
				else
				{
					$sql_job = "insert";
					$sqlwhere = '';
				}

				$fields = array(
					'directory'	=> $language_directory,
					'name'		=> $language_name,
					'type'		=> 'language',
					'version'	=> $language_version,
					'platform'	=> $language_platform,
					'author'	=> addslashes( $language_author ),
					'license'	=> addslashes( $language_license ),
					'guid'		=> $language_guid,
					'description' => ""
				);

				$database->build_and_execute(
					$sql_job,
					TABLE_PREFIX . "addons",
					$fields,
					$sqlwhere
				);
				
				if ( $database->is_error() )
					$admin->print_error( $database->get_error() );
			}
		}
	}

?>