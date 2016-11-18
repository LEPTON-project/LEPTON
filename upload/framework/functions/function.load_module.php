<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		load_module
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
	 *	Load module information from the info.php of a given module into the current DB.
	 *
	 *	@param	string	Any valid directory(-path)
	 *	@param	bool	Call the install-script of the module? Default: false
	 *
	 */
	function load_module( $directory, $install = false )
	{
		global $database, $admin, $MESSAGE;
		
		if ( is_dir( $directory ) && file_exists( $directory . "/info.php" ) )
		{
			global $module_name, $module_license, $module_author, $module_directory, $module_version, $module_function, $module_description, $module_platform, $module_guid, $lepton_platform;
			/**
			 * @internal frankH 2011-08-02 - added $lepton_platform, can be removed when addons are built only for LEPTON
			 */
			if ( isset( $lepton_platform ) && ( $lepton_platform != '' ) )
				$module_platform = $lepton_platform;
			
			require_once( $directory . "/info.php" );
			if ( isset( $module_name ) )
			{
				
				$module_function = strtolower( $module_function );
				
				// Check that it doesn't already exist
				$sqlwhere = "`type` = 'module' AND `directory` = '" . $module_directory . "'";
				$sql      = "SELECT COUNT(*) FROM `" . TABLE_PREFIX . "addons` WHERE " . $sqlwhere;
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
					'directory' => $module_directory,
					'name'		=> $module_name,
					'description' => $module_description,
					'type'		=> 'module',
					'function'	=> strtolower( $module_function ),
					'version'	=> $module_version,
					'platform'	=> $module_platform,
					'author'	=> $module_author,
					'license'	=> $module_license				
				);

				if ( isset( $module_guid ) ) $fields['guid'] = $module_guid;
				
				$database->build_and_execute(
					$sql_job,
					TABLE_PREFIX . "addons",
					$fields,
					$sqlwhere
				);

				if ( $database->is_error() )
					$admin->print_error( $database->get_error() );
				
				/**
				 *	Run installation script
				 *
				 */
				if ( $install == true )
				{
					if ( file_exists( $directory . '/install.php' ) )
						require( $directory . '/install.php' );
				}
			}
		}
	}

?>