<?php

/**
 * Admin tool: Addon File Editor
 *
 * This tool allows you to "edit", "delete", "create", "upload" or "backup" files of installed 
 * Add-ons such as modules, templates and languages via LEPTON backend. This enables
 * you to perform small modifications on installed Add-ons without downloading the files first.
 *
 * 
 * @author		Christian Sommer (doc), Bianka Martinovic (BlackBird), Dietrich Roland Pehlke (aldus), LEPTON Project
 * @copyright	2008-2012 Christian Sommer (doc), Bianka Martinovic (BlackBird), Dietrich Roland Pehlke (aldus)
 * @copyright	2010-2015 LEPTON Project
 * @license     GNU General Public License
 * @version		see info.php
 * @platform	see info.php
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

// drop existing module tables
$table = TABLE_PREFIX . 'mod_addon_file_editor';
$database->query("DROP TABLE IF EXISTS `$table`");

// create new module table
$sql = "CREATE TABLE `$table` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ftp_enabled` INT(1) NOT NULL,
  `ftp_server` VARCHAR(255) collate latin1_general_ci NOT NULL,
  `ftp_user` VARCHAR(255) collate latin1_general_ci NOT NULL,
  `ftp_password` VARCHAR(255) collate latin1_general_ci NOT NULL,
  `ftp_port` INT NOT NULL,
  `ftp_start_dir` VARCHAR(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
)";

$database->query($sql);

// add table with default values
$sql = "INSERT INTO `$table`
	(`ftp_enabled`, `ftp_server`, `ftp_user`, `ftp_password`, `ftp_port`, `ftp_start_dir`)
	VALUES ('0', '-', '-', '', '21', '/')
";	

$database->query($sql);

?>