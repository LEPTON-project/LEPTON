<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released special license.
 * License can be seen in the info.php of this module.
 *
 * @module          lib Responsive Filemanager
 * @author          LEPTON Project, Alberto Peripolli (http://responsivefilemanager.com/)
 * @copyright       2016-2017 LEPTON Project, Alberto Peripolli
 * @link            https://www.LEPTON-cms.org
 * @license         please see info.php of this module
 * @license_terms   please see info.php of this module
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

$module_directory	= 'lib_r_filemanager';
$module_name		= 'Responsive Filemanager';
$module_function	= 'library';
$module_version		= '9.11.0.1';
$module_platform	= '2.x';
$module_delete		=  false;
$module_author		= 'Alberto Peripolli, LEPTON team';
$module_license		= 'special license, see <a href="' .LEPTON_URL .'/modules/lib_r_filemanager/license.txt" target="_blank">included license file</a>.';
$module_description	= 'Filemanager for use with LEPTON';
$module_home		= ' https://www.lepton-cms.org/';
$module_guid		= '071e6320-e081-4d50-a3a7-e84bd8080f2d';

?>
