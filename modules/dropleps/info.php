<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          dropleps
 * @author          LEPTON Project
 * @copyright       2010-2011, LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 * @version         $Id: info.php 1733 2012-01-30 14:09:38Z webbird $
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {
	include(WB_PATH.'/framework/class.secure.php');
} else {
	$root = "../";
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= "../";
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) {
		include($root.'/framework/class.secure.php');
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php

$module_directory = 'dropleps';
$module_name = 'Dropleps';
$module_function = 'tool';
$module_version = '2.01';
$module_platform = '1.x';
$lepton_platform = '1.x';
$module_author = 'LEPTON Project';
$module_license = 'GNU General Public License';
$module_description = 'This tool allows you to manage your local Dropleps.';

$module_home = 'http://www.lepton-cms.org/';
$module_guid = '8b5b5074-993e-421a-9aff-2e32ae1601d5';

/**
 * Version history
 *
 * 2.01 - First step to replace the old Droplets module on upgrade
 *
 **/
?>