<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          wysiwyg
 * @author          Ryan Djurovich
 * @author          LEPTON Project
 * @copyright       2004-2010 WebsiteBaker Project
 * @copyright       2010-2012 LEPTON Project 
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 * @version         $Id: info.php 1687 2012-01-23 09:25:47Z erpe $
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

 

$module_directory	= 'wysiwyg';
$module_name		  = 'WYSIWYG';
$module_function	= 'page';
$module_version		= '3.0.7';
$module_platform	= '1.x';
$module_author		= 'Ryan Djurovich, Dietrich Roland Pehlke (last)';
$module_license		= 'GNU General Public License';
$module_license_terms	= '-';
$module_description	  = 'This module allows you to edit the contents of a page using a graphical editor.';
$module_guid		= 'DA07DFA3-7592-4781-89C2-D549DD77B017';

?>