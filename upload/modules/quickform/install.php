<?php

/**
 *
 *	@module			quickform
 *	@version		see info.php of this module
 *	@authors		Ruud Eisinga, LEPTON project
 *	@copyright		2012-2018 Ruud Eisinga, LEPTON project
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


// create the table
$table_fields="
	`section_id` INT NOT NULL DEFAULT '0',
	`email` VARCHAR(128) NOT NULL DEFAULT '',
	`subject` VARCHAR(128) NOT NULL DEFAULT '',
	`template` VARCHAR(64) NOT NULL DEFAULT 'form',
	`successpage` INT NOT NULL DEFAULT '0',
	PRIMARY KEY (`section_id`)	
";
LEPTON_handle::install_table("mod_quickform", $table_fields);



// create the table
$table_fields="
	`message_id` INT NOT NULL auto_increment,
	`section_id` INT NOT NULL DEFAULT '0',
	`data` TEXT NOT NULL,
	`submitted_when` INT NOT NULL DEFAULT '0', 
	PRIMARY KEY (`message_id`)
";
LEPTON_handle::install_table("mod_quickform_data", $table_fields);

?>