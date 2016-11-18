<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		upgrade_module
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
 *  Update the module informations in the DB
 *
 *  @param  string  Name of the modul-directory
 *  @param  bool  Optional boolean to run the upgrade-script of the module.
 *
 *  @return  nothing
 *
 */
function upgrade_module( $directory, $upgrade = false )
{
	global $database, $admin, $MESSAGE;
	global $module_license, $module_author, $module_name, $module_directory, $module_version, $module_function, $module_guid, $module_description, $module_platform;
	
	$fields = array(
		'version'	=> $module_version,
		'description'	=> $module_description,
		'platform'	=> $module_platform,
		'author' 	=>  $module_author,
		'license'	=> $module_license ,
		'guid'		=> $module_guid 
	);
	
	$statement = $database->build_and_execute(
		'update',
		TABLE_PREFIX . "addons",
		$fields,
		"`directory`= '" . $module_directory . "'"
	);
	
	if ( $database->is_error() )
		$admin->print_error( $database->get_error() );

	if (true === $upgrade) {
		$temp_filename = LEPTON_PATH."/modules/".$module_directory."/upgrade.php";
	
		if (file_exists($temp_filename)) {
			require( $temp_filename );
		}
	}
}

?>