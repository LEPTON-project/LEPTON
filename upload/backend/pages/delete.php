<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 *
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @version         $Id: delete.php 1664 2012-01-22 06:15:37Z phpmanufaktur $
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

// Get page id
if(!isset($_GET['page_id']) OR !is_numeric($_GET['page_id'])) {
	header("Location: index.php");
	exit(0);
} else {
	$page_id = $_GET['page_id'];
}

require_once(LEPTON_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_delete');

// Include the functions file
// require_once(LEPTON_PATH.'/framework/summary.functions.php');

// Get perms
if (!$admin->get_page_permission($page_id,'admin')) {
	$admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
}

global $database;

// Find out more about the page
$results_array = array();
$database->execute_query(
	"SELECT `visibility` FROM `".TABLE_PREFIX."pages` WHERE `page_id` = '".$page_id."'",
	true,
	$results_array,
	false
);

if($database->is_error()) {
	$admin->print_error($database->get_error());
}
if( count($results_array) == 0) {
	$admin->print_error($MESSAGE['PAGES_NOT_FOUND']);
}

$visibility = $results_array['visibility'];

// Check if we should delete it or just set the visibility to 'deleted'
if(PAGE_TRASH != 'disabled' AND $visibility != 'deleted') {
	// Page trash is enabled and page has not yet been deleted
	// Function to change all child pages visibility to deleted
	function trash_subs($parent = 0) {
		global $database;
		
		// Query pages
		$temp_pages = array();
		$database->execute_query(
			"SELECT `page_id` FROM `".TABLE_PREFIX."pages` WHERE `parent` = '".$parent."' ORDER BY `position` ASC",
			true,
			$temp_pages
		);
		
		// Loop through pages
		foreach($temp_pages as $page) {		
			// Update the page visibility to 'deleted'
			$database->simple_query("UPDATE `".TABLE_PREFIX."pages` SET `visibility` = 'deleted' WHERE page_id = '".$page['page_id']."' LIMIT 1");

			if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

			// Run this function again for all sub-pages
			trash_subs($page['page_id']);
		}
	}
	
	// Update the page visibility to 'deleted'
	$database->simple_query("UPDATE `".TABLE_PREFIX."pages` SET `visibility` = 'deleted' WHERE `page_id` = '".$page_id."' LIMIT 1");
	
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);
	
	// Run trash subs for this page
	trash_subs($page_id);

} else {

	LEPTON_tools::register("get_subs");
	
	// Really dump the page
	// Delete page subs
	$sub_pages = array();
	get_subs( $page_id, $sub_pages );
	
	foreach($sub_pages AS $sub_page_id) {
		delete_page($sub_page_id);
	}
	// Delete page
	delete_page($page_id);
}	

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error());
} else {
	$admin->print_success($MESSAGE['PAGES_DELETED']);
}

// Print admin footer
$admin->print_footer();

?>