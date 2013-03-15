<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010, Website Baker Project
 * @copyright       2010-2013 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 *
 */


// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
} else {
	$oneback = "../";
	$root = $oneback;
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= $oneback;
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php

    /**
     *  Function to remove a non-empty directory
     *  
     *  @param string $directory
     *  @return boolean
     */
     
		// If suplied dirname is a file then unlink it
		if (is_file($directory))
		{
			return unlink($directory);
		}
		// Empty the folder
		if (is_dir($directory))
		{
			$dir = dir($directory);
			while (false !== $entry = $dir->read())
			{
				// Skip pointers
				if ($entry == '.' || $entry == '..') { continue; }
				// Deep delete directories
				if (is_dir($directory.'/'.$entry))
				{
					rm_full_dir($directory.'/'.$entry);
				}
				else
				{
					unlink($directory.'/'.$entry);
				}
			}
			// Now delete the folder
			$dir->close();
			return rmdir($directory);
		}
  // end function rm_full_dir()



?>