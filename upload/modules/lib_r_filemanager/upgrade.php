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


// move thumbs directory
$directory_names = array(
	array ('source'=>'/modules/lib_r_filemanager/filemanager/thumbs', 'target'=>'/modules/lib_r_filemanager/thumbs')
);
LEPTON_handle::rename_directories($directory_names);


// delete obsolete directory
$directory_names = array(
	'/modules/lib_r_filemanager/filemanager/uploader',
	'/modules/lib_r_filemanager/filemanager/js/ViewerJS'
);
LEPTON_handle::delete_obsolete_directories($directory_names);

// delete obsolete file
$file_names = array(
	'/modules/lib_r_filemanager/filemanager/js/ZeroClipboard.swf'
);
LEPTON_handle::delete_obsolete_files($file_names);
?>