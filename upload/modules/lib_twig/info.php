<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          Twig Template Engine
 * @author          LEPTON Project
 * @copyright       2012-2013 LEPTON 
 * @link            http://www.lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

/**
 * Copyright (c) 2009-2013 by the Twig Team, see AUTHORS for more details.
 * Please see attached LICENSE FILE for Twig License
 * Documentation: http://twig.sensiolabs.org/documentation
 *
 */ 
 
// try to include LEPTON class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	if (defined('LEPTON_VERSION')) include(WB_PATH.'/framework/class.secure.php');
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php')) {
	include($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php'); 
} else {
	$subs = explode('/', dirname($_SERVER['SCRIPT_NAME']));	$dir = $_SERVER['DOCUMENT_ROOT'];
	$inc = false;
	foreach ($subs as $sub) {
		if (empty($sub)) continue; $dir .= '/'.$sub;
		if (file_exists($dir.'/framework/class.secure.php')) { 
			include($dir.'/framework/class.secure.php'); $inc = true;	break; 
		} 
	}
	if (!$inc) trigger_error(sprintf("[ <b>%s</b> ] Can't include LEPTON class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
}
// end include LEPTON class.secure.php

$module_directory     = 'lib_twig';
$module_name          = 'Twig Library for LEPTON';
$module_function      = 'library';
$module_version       = '0.1.12.3';
$module_platform      = '1.x';
$module_author        = 'twig.sensiolabs.org, LEPTON team';
$module_license       = 'GNU General Public License for LEPTON Addon';
$module_description   = 'Twig PHP5 Template Engine';
$module_home          = 'http://lepton-cms.org/';
$module_guid          = '19fb9aba-7f31-4fee-81ea-1db03e83c6cc';

?>
