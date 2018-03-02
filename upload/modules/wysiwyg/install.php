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
 * @copyright       2010-2018 LEPTON Project 
 * @link            https://lepton-cms.org
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

// Create table
$table_fields="
    `section_id` INT NOT NULL DEFAULT '0',
    `page_id` INT NOT NULL DEFAULT '0' ,
    `content` LONGTEXT NOT NULL,
    `text` LONGTEXT NOT NULL ,
    PRIMARY KEY (`section_id`)
";
LEPTON_handle::install_table('mod_wysiwyg', $table_fields);


$mod_search = "SELECT * FROM ".TABLE_PREFIX."search  WHERE value = 'wysiwyg' and name = 'module' ";
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
		
	// Query start
	$query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title,	[TP]pages.link, [TP]pages.description, [TP]pages.modified_when, [TP]pages.modified_by	FROM [TP]mod_wysiwyg, [TP]pages WHERE ";
	$query_body_code = " [TP]pages.page_id = [TP]mod_wysiwyg.page_id AND [TP]mod_wysiwyg.text [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\'";

	$field_values=[
	    [ 'module', 'wysiwyg', "'".$field_info."'" ],	
	    [ 'query_start', "'".$query_start_code."'", 'wysiwyg'],
	    [ 'query_body', "'".$query_body_code."'", 'wysiwyg'],
	    [ 'query_end', '', 'wysiwyg']
	];
	LEPTON_database::getInstance()->simple_query(
	    "INSERT INTO `".TABLE_PREFIX."mod_search` (`name`,`value`,`extra`) VALUES( ?, ?, ? )",
	    $field_values
	);
	//LEPTON_handle::insert_values('search', $field_values);

    
}

?>