<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          phpmailer
 * @author          LEPTON Project
 * @copyright       2010-2013 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {	
	include(LEPTON_PATH.'/framework/class.secure.php'); 
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



$module_directory    = 'lib_phpmailer';
$module_name         = 'PHPMailer Library';
$module_function     = 'library';
$module_version      = '5.2.6';
$module_platform     = '2.x';
$module_author 		 = 'Andy Prevost, Marcus Bointon, Brent R. Matzelle';
$module_home		 = 'http://phpmailer.sourceforge.net';
$module_license 	 = 'GNU General Public License';
$module_description  = 'PHP Mailer for LEPTON';
$module_guid         = '5BF5013A-1204-4AE7-88B2-2E2662AF0E4D';

?>