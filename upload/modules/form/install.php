<?php

/**
 *  @module         form
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Rudolph Lartey, John Maats, Dietrich Roland Pehlke, LEPTON project
 *  @copyright      2004-2010 Ryan Djurovich, Rudolph Lartey, John Maats, Dietrich Roland Pehlke 
 *  @copyright      2010-2014 LEPTON project  
 *  @license        see info.php of this module
 *  @license terms  see info.php of this module
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


 

if(defined('LEPTON_URL'))
{

	// Create tables
	$mod_form = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_form_fields` ( `field_id` INT NOT NULL AUTO_INCREMENT,'
		. ' `section_id` INT NOT NULL DEFAULT \'0\' ,'
		. ' `page_id` INT NOT NULL DEFAULT \'0\' ,'
		. ' `position` INT NOT NULL DEFAULT \'0\' ,'
		. ' `title` VARCHAR(255) NOT NULL DEFAULT \'\' ,'
		. ' `type` VARCHAR(255) NOT NULL DEFAULT \'\' ,'
		. ' `required` INT NOT NULL DEFAULT \'0\' ,'
		. ' `value` TEXT NOT NULL ,'
		. ' `extra` TEXT NOT NULL ,'
		. ' PRIMARY KEY ( `field_id` ) '
		. ' )';
	$database->query($mod_form);

	$mod_form = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_form_settings` ('
		. ' `section_id` INT NOT NULL DEFAULT \'0\' ,'
		. ' `page_id` INT NOT NULL DEFAULT \'0\' ,'
		. ' `header` TEXT NOT NULL ,'
		. ' `field_loop` TEXT NOT NULL ,'
		. ' `footer` TEXT NOT NULL ,'
		. ' `email_to` TEXT NOT NULL ,'
		. ' `email_from` VARCHAR(255) NOT NULL DEFAULT \'\' ,'
		. ' `email_fromname` VARCHAR(255) NOT NULL DEFAULT \'\' ,'
		. ' `email_subject` VARCHAR(255) NOT NULL DEFAULT \'\' ,'
		. ' `success_page` TEXT NOT NULL ,'
		. ' `success_email_to` TEXT NOT NULL ,'
		. ' `success_email_from` VARCHAR(255) NOT NULL DEFAULT \'\' ,'
		. ' `success_email_fromname` VARCHAR(255) NOT NULL DEFAULT \'\' ,'
		. ' `success_email_text` TEXT NOT NULL ,'
		. ' `success_email_subject` VARCHAR(255) NOT NULL DEFAULT \'\' ,'
		. ' `stored_submissions` INT NOT NULL DEFAULT \'0\' ,'
		. ' `max_submissions` INT NOT NULL DEFAULT \'0\' ,'
		. ' `use_captcha` INT NOT NULL DEFAULT \'0\' ,'
		. ' PRIMARY KEY ( `section_id` ) '
		. ' )';
	$database->query($mod_form);
	
	$mod_form = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_form_submissions` ( `submission_id` INT NOT NULL AUTO_INCREMENT,'
		. ' `section_id` INT NOT NULL DEFAULT \'0\' ,'
		. ' `page_id` INT NOT NULL DEFAULT \'0\' ,'
		. ' `submitted_when` INT NOT NULL DEFAULT \'0\' ,'
		. ' `submitted_by` INT NOT NULL DEFAULT \'0\','
		. ' `body` TEXT NOT NULL,'
		. ' PRIMARY KEY ( `submission_id` ) '
		. ' )';
	$database->query($mod_form);
		
    $mod_search = "SELECT * FROM ".TABLE_PREFIX."search  WHERE value = 'form'";
    $insert_search = $database->query($mod_search);
    if( $insert_search->numRows() == 0 )
    {
    	// Insert info into the search table
    	// Module query info
    	$field_info = array();
    	$field_info['page_id'] = 'page_id';
    	$field_info['title'] = 'page_title';
    	$field_info['link'] = 'link';
    	$field_info['description'] = 'description';
    	$field_info['modified_when'] = 'modified_when';
    	$field_info['modified_by'] = 'modified_by';
    	$field_info = serialize($field_info);
    	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('module', 'form', '$field_info')");
    	// Query start
    	$query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title,	[TP]pages.link, [TP]pages.description, [TP]pages.modified_when, [TP]pages.modified_by	FROM [TP]mod_form_fields, [TP]mod_form_settings, [TP]pages WHERE ";
    	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_start', '$query_start_code', 'form')");
    	// Query body
    	$query_body_code = " [TP]pages.page_id = [TP]mod_form_settings.page_id AND [TP]mod_form_settings.header LIKE \'%[STRING]%\'
    	OR [TP]pages.page_id = [TP]mod_form_settings.page_id AND [TP]mod_form_settings.footer LIKE \'%[STRING]%\'
    	OR [TP]pages.page_id = [TP]mod_form_fields.page_id AND [TP]mod_form_fields.title LIKE \'%[STRING]%\' ";
    	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_body', '$query_body_code', 'form')");
    	// Query end
    	$query_end_code = "";
    	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_end', '$query_end_code', 'form')");

    	// Insert blank row (there needs to be at least on row for the search to work)
    	$database->query("INSERT INTO ".TABLE_PREFIX."mod_form_fields (`page_id`, `section_id`, `value`, `extra`) VALUES ('0','0', '', '')");

    }
}

?>