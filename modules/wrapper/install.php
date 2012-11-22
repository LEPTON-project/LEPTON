<?php
/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          wrapper
 * @author          WebsiteBaker Project
 * @author          LEPTON Project
 * @copyright       2004-2010, WebsiteBaker Project
 * @copyright       2010-2011, LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 * @version         $Id: install.php 1785 2012-03-01 11:14:57Z erpe $
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



if(defined('WB_URL')) {
	
	// Create table
	// $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_wrapper`");
	$mod_wrapper = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_wrapper` ('
		. ' `section_id` INT NOT NULL DEFAULT \'0\','
		. ' `page_id` INT NOT NULL DEFAULT \'0\','
		. ' `url` TEXT NOT NULL,'
		. ' `height` INT NOT NULL DEFAULT \'0\','
		. ' `width` INT NOT NULL DEFAULT \'0\','
		. ' `wtype` VARCHAR(50) NOT NULL DEFAULT \'0\','
		. ' PRIMARY KEY ( `section_id` ) '
		. ' )';
	$database->query($mod_wrapper);
}

?>