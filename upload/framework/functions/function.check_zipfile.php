<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		check_zipfile
 * @author          LEPTON Project
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
 *	Checking a zip archive (e.g. before installing a module or template)
 *
 *	@param	string	A valid filename (path).
 *
 *	@return	bool	True if "ok", false if e.g. archive include "bad" filenames/paths to extract.
 *
 */
function check_zipfile ( $aFileName="" )
{

	if($aFileName === "") return false;
	
	if (!class_exists("PclZip")) {
		require_once(LEPTON_PATH.'/modules/lib_lepton/pclzip/pclzip.lib.php');
	}
	
	$archive = new PclZip($aFileName);
	
	$all_files_in_archive = $archive->listContent();
	
	if($all_files_in_archive === 0) return false;
	
	$matches = array();
	foreach($all_files_in_archive as $file){
		
		$result = preg_match_all(
			"/^[\.]{1,2}[\/|\\\](.*)$/s", // filename start with one or two dots ".", followed by a slash or backslash
			$file['filename'],
			$matches,
			PREG_SET_ORDER
		);
		
		if($result === 1) return false;
	}
	
	return true;
} 

?>