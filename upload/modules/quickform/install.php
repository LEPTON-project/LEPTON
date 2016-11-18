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

/**
 *	[1] Looking for the tables if there is an existing 'xsik' copy of this module from an old installation
 */
$strip = TABLE_PREFIX;
$all_tables = $database->list_tables( $strip );

/**
 *	[2]	Table 'mod_quickform'
 */
if(in_array("mod_quickform", $all_tables)) {
	
	// Table still exists - what to do?
	die("Table 'mod_quickform' still exists! Please check database");

} else {

	// Try to create the table
	$database->simple_query('CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_quickform` ('
		. ' `section_id` INT NOT NULL DEFAULT \'0\','
		. ' `email` VARCHAR(128) NOT NULL DEFAULT \'\',' 
		. ' `subject` VARCHAR(128) NOT NULL DEFAULT \'\',' 
		. ' `template` VARCHAR(64) NOT NULL DEFAULT \'form\',' 
		. ' `successpage` INT NOT NULL DEFAULT \'0\',' 
		. ' PRIMARY KEY ( `section_id` ) '
		. ' )'
		);
}

/**
 *	[3]	Table 'mod_quickform_data'
 */
if(in_array("mod_quickform_data", $all_tables)) {

	// Table still exists - what to do?
	die("Table 'mod_quickform_data' still exists! Please check database");
	
} else {

	// Try to create the table
	$database->simple_query('CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_quickform_data` ('
		. ' `message_id` INT NOT NULL NOT NULL auto_increment,'
		. ' `section_id` INT NOT NULL DEFAULT \'0\','
		. ' `data` TEXT NOT NULL,'
		. ' `submitted_when` INT NOT NULL DEFAULT \'0\',' 
		. ' PRIMARY KEY ( `message_id` ) '
		. ' )'
		);
}
?>