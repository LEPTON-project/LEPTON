<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		get_subs
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2015 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

if ( !defined( 'LEPTON_PATH' ) ) die();

/**
 *	Recursive function to copy all subdirectories and contents
 *
 *	@param	str		A valid path to the source directory
 *	@param	str		A valid path to the destination directory
 *	@return	bool
 */
function copy_recursive_dirs( $dirsource, $dirdest ) {
	if ( is_dir($dirsource) ) {
		$dir_handle=opendir($dirsource);
	}
	while ( $file = readdir($dir_handle) ) {
		if( $file != "." && $file != ".." ) {
			if( ! is_dir($dirsource."/".$file) ) {
				copy ($dirsource."/".$file, $dirdest.'/'.$file);
				if ( $file != '.svn' ) {
					change_mode($dirdest."/".$file, 'file');
				}
			}
			else {
				make_dir($dirdest."/".$file);
				copy_recursive_dirs($dirsource."/".$file, $dirdest.'/'.$file);
			}
	}
  }
  closedir($dir_handle);
  return true;
}
?>