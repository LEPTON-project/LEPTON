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

// Get id
if(!isset($_GET['post_id']) OR !is_numeric($_GET['post_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$post_id = $_GET['post_id'];
}

// Include admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(LEPTON_PATH.'/modules/admin.php');

// Get post details
$get_details = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."mod_news_posts` WHERE `post_id` = '".$post_id."'",
	true,
	$get_details,
	false
);

if (count($get_details) == 0) {
	$admin->print_error($TEXT['NOT_FOUND'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Unlink post access file
if(is_writable(LEPTON_PATH.PAGES_DIRECTORY.$get_details['link'].PAGE_EXTENSION)) {
	unlink(LEPTON_PATH.PAGES_DIRECTORY.$get_details['link'].PAGE_EXTENSION);
}

// Delete post and comments.
$database->execute_query("DELETE FROM `".TABLE_PREFIX."mod_news_posts` WHERE `post_id` = '".$post_id."' LIMIT 1");
$database->execute_query("DELETE FROM `".TABLE_PREFIX."mod_news_comments` WHERE `post_id` = '".$post_id."'");

// Clean up ordering
require(LEPTON_PATH.'/framework/class.order.php');
$order = new order(TABLE_PREFIX.'mod_news_posts', 'position', 'post_id', 'section_id');
$order->clean($section_id); 

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), LEPTON_URL.'/modules/news/modify_post.php?page_id='.$page_id.'&post_id='.$post_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>