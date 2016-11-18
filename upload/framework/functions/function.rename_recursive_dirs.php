<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function        rename_recursive_dirs
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

if ( !defined( 'LEPTON_PATH' ) ) die();

/**
 *	Recursive function to rename all subdirectories and contents
 *  (PHP-function "rename" did not work on all windows installations)
 *
 *	@param	str		A valid path to the source directory
 *	@param	str		A valid path to the destination directory
 *	@param	int		Counter for the recursion-deep. Default is 0.
 *	@return	bool
 *
 */

if (!function_exists("make_dir")) require_once( dirname(__FILE__)."/function.make_dir.php");
if (!function_exists("change_mode")) require_once( dirname(__FILE__)."/function.change_mode.php");
if (!function_exists("rm_full_dir")) require_once( dirname(__FILE__)."/function.rm_full_dir.php");

function rename_recursive_dirs( $dirsource, $dirdest, $deep=0 ) {
	if ( true === is_dir($dirsource) ) {
		$dir= dir($dirsource);
		while ( $file = $dir->read() ) {
			if( $file[0] != "." ) {
				if( !is_dir($dirsource."/".$file) ) {
					copy ($dirsource."/".$file, $dirdest."/".$file);
					change_mode($dirdest."/".$file);
				} else {
					make_dir($dirdest."/".$file);
					rename_recursive_dirs($dirsource."/".$file, $dirdest.'/'.$file, $deep+1);
				}
			}
  		}
		$dir->close();
	}
	if ($deep == 0) rm_full_dir( $dirsource );
	return true;
}
?>