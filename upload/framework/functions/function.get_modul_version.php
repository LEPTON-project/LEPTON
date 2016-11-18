<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		get_modul_version
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
	 *  Try to get the current version of a given Modul.
	 *
	 *  @param  string  $modulname: like saved in addons directory
	 *  @param  boolean  $source: true reads from database, false from info.php
	 *  @return  string  the version as string, if not found returns null
	 *
	 */
	function get_modul_version( $modulname, $source = true )
	{
		global $database;
		$version = null;
		if ( $source != true )
		{
			$sql     = "SELECT `version` FROM `" . TABLE_PREFIX . "addons` WHERE `directory`='" . $modulname . "'";
			$version = $database->get_one( $sql );
		}
		else
		{
			$info_file = LEPTON_PATH . '/modules/' . $modulname . '/info.php';
			if ( file_exists( $info_file ) )
			{
				$module_version = null;
				require( $info_file );
				$version =& $module_version;
			} //file_exists( $info_file )
		}
		return $version;
	}

?>