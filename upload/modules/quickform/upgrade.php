<?php

/**
 *
 *	@module			quickform
 *	@version		see info.php of this module
 *	@authors		Ruud Eisinga, LEPTON project
 *	@copyright		2012-2016 Ruud Eisinga, LEPTON project
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

// first copy content of original table to xsik_table
$database->simple_query("DROP TABLE IF EXISTS `".TABLE_PREFIX."xsik_quickform`");
$database->simple_query("RENAME TABLE `".TABLE_PREFIX."mod_quickform` TO `".TABLE_PREFIX."xsik_quickform`");

// Create new table
$database->simple_query('CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_quickform` ('
	. ' `section_id` INT NOT NULL DEFAULT \'0\','
	. ' `email` VARCHAR(128) NOT NULL DEFAULT \'\',' 
	. ' `subject` VARCHAR(128) NOT NULL DEFAULT \'\',' 
	. ' `template` VARCHAR(64) NOT NULL DEFAULT \'form\',' 
	. ' `successpage` INT NOT NULL DEFAULT \'0\',' 
	. ' PRIMARY KEY ( `section_id` ) '
	. ' )'
);

// insert content from sik_table to original table
$database->simple_query("INSERT INTO `".TABLE_PREFIX."mod_quickform` SELECT * FROM `".TABLE_PREFIX."xsik_quickform`");	

// first copy content of original table to xsik_table
$database->simple_query("DROP TABLE IF EXISTS `".TABLE_PREFIX."xsik_quickform_data`");
$database->simple_query("RENAME TABLE `".TABLE_PREFIX."mod_quickform_data` TO `".TABLE_PREFIX."xsik_quickform_data`");

// Create new table
$database->simple_query('CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_quickform_data` ('
	. ' `message_id` INT NOT NULL NOT NULL auto_increment,'
	. ' `section_id` INT NOT NULL DEFAULT \'0\','
	. ' `data` TEXT NOT NULL,'
	. ' `submitted_when` INT NOT NULL DEFAULT \'0\',' 
	. ' PRIMARY KEY ( `message_id` ) '
	. ' )'
	);

// insert content from sik_table to original table
$database->simple_query("INSERT INTO `".TABLE_PREFIX."mod_quickform_data` SELECT * FROM `".TABLE_PREFIX."xsik_quickform_data`");	  

?>