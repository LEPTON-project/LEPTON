<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released special license.
 * License can be seen in the info.php of this module.
 *
 * @module          lib Responsive Filemanager
 * @author          LEPTON Project, Alberto Peripolli (http://responsivefilemanager.com/)
 * @copyright       2016-2018 LEPTON Project, Alberto Peripolli
 * @link            https://lepton-cms.org
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

if(!in_array('media',$_SESSION['SYSTEM_PERMISSIONS']))  {
	header("Location: ".ADMIN_URL."");
	exit(0);
}

$akey = password_hash( LEPTON_GUID, PASSWORD_DEFAULT);
$akey = str_replace(array('$','/'),'',$akey);
$akey = substr($akey, -30);	
$_SESSION['rfkey'] = $akey;

$_SESSION['RF']["verify"] = "RESPONSIVEfilemanager";

?>