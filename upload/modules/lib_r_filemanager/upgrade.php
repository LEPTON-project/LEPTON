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
LEPTON_tools::register('rename_recursive_dirs');
if (file_exists (LEPTON_PATH.'/modules/lib_r_filemanager/filemanager/thumbs/index.php')) {	
	rename_recursive_dirs( LEPTON_PATH.'/modules/lib_r_filemanager/filemanager/thumbs' , LEPTON_PATH.'/modules/lib_r_filemanager/thumbs' );
}

// delete obsolete directory
LEPTON_tools::register('rm_full_dir');
if (file_exists (LEPTON_PATH.'/modules/lib_r_filemanager/filemanager/uploader/index.php')) {	
	rm_full_dir( LEPTON_PATH.'/modules/lib_r_filemanager/filemanager/uploader' ); 
}

if (file_exists (LEPTON_PATH.'/modules/lib_r_filemanager/filemanager/js/ViewerJS/pdf.js')) {	
	rm_full_dir( LEPTON_PATH.'/modules/lib_r_filemanager/filemanager/js/ViewerJS' ); 
}

// delete obsolete file
$to_delete = array(
LEPTON_PATH.'/modules/lib_r_filemanager/filemanager/js/ZeroClipboard.swf'
);

foreach ($to_delete as $ref)  {
	if (file_exists($ref)) {
		$result = unlink ($ref);
		if (false === $result) {
			echo "Cannot delete file ".$ref.". Please check file permissions and ownership or delete file manually.";
		}
	}
}
?>