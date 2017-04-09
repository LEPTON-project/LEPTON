<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 *
 * @author          Thomas Hornik (thorn),LEPTON Project
 * @copyright       2008-2010  Thomas Hornik (thorn)
 * @copyright       2010-2017  LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
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

$module_directory 	= 'captcha_control';
$module_name 		  	= 'Captcha and Advanced-Spam-Protection (ASP) Control';
$module_function 		= 'tool';
$module_version 		= '2.0.2';
$module_platform 		= '1.x';
$module_delete 		  =  false;
$module_author 	  	= 'Thomas Hornik (thorn), LEPTON Project';
$module_license 		= 'GNU General Public License';
$module_license_terms = 'GNU General Public License';
$module_description 	= 'Admin-Tool to control CAPTCHA and ASP';
$module_guid     		= 'c29c5f1a-a72a-4137-b5cd-62982809bd38';

?>