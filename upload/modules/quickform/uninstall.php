<?php

/**
 *
 *	@module			quickform
 *	@version		see info.php of this module
 *	@authors		Ruud Eisinga, LEPTON project
 *	@copyright		2012-2017 Ruud Eisinga, LEPTON project
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {
	include(LEPTON_PATH.'/framework/class.secure.php');
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

//	Try to delete an existing 'old' copy of the table
$database->simple_query("DROP TABLE IF EXISTS `".TABLE_PREFIX."xsik_quickform`");
//	Try to rename the table as a copy
$database->simple_query("RENAME TABLE `".TABLE_PREFIX."mod_quickform` TO `".TABLE_PREFIX."xsik_quickform`");

$database->simple_query("DROP TABLE IF EXISTS `".TABLE_PREFIX."xsik_quickform_data`");
$database->simple_query("RENAME TABLE `".TABLE_PREFIX."mod_quickform_data` TO `".TABLE_PREFIX."xsik_quickform_data`");

?>