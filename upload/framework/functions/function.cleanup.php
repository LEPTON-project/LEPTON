<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function        cleanup
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

if ( !defined( 'LEPTON_PATH' ) ) die();

/**
 *	Remove all files or directories given as arguments to the function.
 *	Typical use of this function is to delete temp files inside the the temp-directory
 *	during any module-, or template- or language-installation.
 * 
 *	@usage	cleanup( $file1, $dir1, $file, ...)
 *
 *	@params	any	Any file, directory
 *	@return bool	true
 *
 *	@notice	Be aware not to call "cleanup(LEPTON_PATH."/temp");" the temp-folder itself;
 *			the temp-folder itself will be deleted!
 *
 */

if (!function_exists("rm_full_dir")) require_once( dirname(__FILE__)."/function.rm_full_dir.php");

function cleanup() {
	if ( 0 == func_num_args() ) return true;
	
	$all_args = func_get_args();
	
	foreach($all_args as &$file) {
		if (true === file_exists($file)) {
			if (true === is_dir($file)) {
				rm_full_dir( $file );
			} else {
				unlink( $file );
			}
		}
	}
	
	return true;
}
?>