<?php

/**
 *  @module         news
 *  @version        see info.php of this module
 *  @author         Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos), LEPTON Project
 *  @copyright      2004-2010 Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos) 
 * 	@copyright      2010-2017 LEPTON Project 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
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

// get and remove all php files created for the news section
$query_details = array();
$database->execute_query(
	"SELECT `link` FROM `".TABLE_PREFIX."mod_news_posts` WHERE `section_id` = '".$section_id."'",
	true,
	$query_details
);

foreach($query_details as $link) {
	if(is_writable(LEPTON_PATH.PAGES_DIRECTORY.$link['link'].PAGE_EXTENSION)) {
		unlink(LEPTON_PATH.PAGES_DIRECTORY.$link['link'].PAGE_EXTENSION);
	}
}

/* Aldus [23.09.2014]: absolute UNCLEAR!

//	check to see if any other sections are part of the news page, if only 1 news is there delete it
$query_details = $database->query("SELECT * FROM ".TABLE_PREFIX."sections WHERE page_id = '$page_id'");
if($query_details->numRows() == 1) {
	$query_details2 = $database->query("SELECT * FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'");
	$link = $query_details2->fetchRow();
	if(is_writable(LEPTON_PATH.PAGES_DIRECTORY.$link['link'].PAGE_EXTENSION)) {
		unlink(LEPTON_PATH.PAGES_DIRECTORY.$link['link'].PAGE_EXTENSION);
	}
}
*/

$database->execute_query("DELETE FROM `".TABLE_PREFIX."mod_news_posts` WHERE `section_id` = '".$section_id."'");
$database->execute_query("DELETE FROM `".TABLE_PREFIX."mod_news_groups` WHERE `section_id` = '".$section_id."'");
$database->execute_query("DELETE FROM `".TABLE_PREFIX."mod_news_comments` WHERE `section_id` = '".$section_id."'");
$database->execute_query("DELETE FROM `".TABLE_PREFIX."mod_news_settings` WHERE `section_id` = '".$section_id."'");

?>