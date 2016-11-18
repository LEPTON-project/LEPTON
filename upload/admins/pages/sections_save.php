<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
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

// Make sure people are allowed to access this page
if(MANAGE_SECTIONS != 'enabled') {
	header('Location: '.ADMIN_URL.'/pages/index.php');
	exit(0);
}

require_once(LEPTON_PATH."/include/jscalendar/jscalendar-functions.php");

// Get page id
if(!isset($_GET['page_id']) OR !is_numeric($_GET['page_id'])) {
	header("Location: index.php");
	exit(0);
} else {
	$page_id = $_GET['page_id'];
}

// Create new admin object
require_once(LEPTON_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_modify');

// Get perms
$results = $database->query("SELECT `admin_groups`,`admin_users` FROM `".TABLE_PREFIX."pages` WHERE `page_id`= '".$page_id."'");
$results_array = $results->fetchRow();
$old_admin_groups = explode(',', $results_array['admin_groups']);
$old_admin_users = explode(',', $results_array['admin_users']);
$in_old_group = FALSE;
foreach($admin->get_groups_id() as $cur_gid){
    if (in_array($cur_gid, $old_admin_groups)) {
        $in_old_group = TRUE;
    }
}
if((!$in_old_group) AND !is_numeric(array_search($admin->get_user_id(), $old_admin_users))) {
	$admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
}

// Get page details
$query = "SELECT count(*) FROM `".TABLE_PREFIX."pages` WHERE `page_id`='".$page_id."'";
$results = $database->query($query);
if($database->is_error()) {
	$admin->print_header();
	$admin->print_error($database->get_error());
}
if($results->numRows() == 0) {
	$admin->print_header();
	$admin->print_error($MESSAGE['PAGES_NOT_FOUND']);
}
$results_array = $results->fetchRow();

// Set module permissions
$module_permissions = $_SESSION['MODULE_PERMISSIONS'];

// Loop through sections
//	Aldus: 2014-10-24 - M.f.i. for new DB functions.
$query_sections = $database->query("SELECT `section_id`,`module`,`position` FROM `".TABLE_PREFIX."sections` WHERE `page_id`= '".$page_id."' ORDER BY `position` ASC");
if($query_sections->numRows() > 0) {
	$num_sections = $query_sections->numRows();
	while( false != ( $section = $query_sections->fetchRow() ) ) {
		if(!is_numeric(array_search($section['module'], $module_permissions))) {
			// Update the section record with properties
			$section_id = $section['section_id'];
			$sql = '';
			$publ_start = 0;
			$publ_end = 0;
			$dst = date("I")?" DST":""; // daylight saving time?
			if(isset($_POST['block'.$section_id]) AND $_POST['block'.$section_id] != '') {
				$sql = "block = '".addslashes($_POST['block'.$section_id])."'";
			}
			// update publ_start and publ_end, trying to make use of the strtotime()-features like "next week", "+1 month", ...
			if(isset($_POST['start_date'.$section_id]) AND isset($_POST['end_date'.$section_id])) {
				if(trim($_POST['start_date'.$section_id]) == '0' OR trim($_POST['start_date'.$section_id]) == '') {
					$publ_start = 0;
				} else {
					$publ_start = jscalendar_to_timestamp($_POST['start_date'.$section_id]);
				}
				if(trim($_POST['end_date'.$section_id]) == '0' OR trim($_POST['end_date'.$section_id]) == '') {
					$publ_end = 0;
				} else {
					$publ_end = jscalendar_to_timestamp($_POST['end_date'.$section_id], $publ_start);
				}
				if($sql != '')
					$sql .= ",";
				$sql .= " publ_start = '".addslashes($publ_start)."'";
				$sql .= ", publ_end = '".addslashes($publ_end)."'";
			}
			if (isset($_POST['section_name'][$section_id])) {
				if (strlen($sql) > 0) $sql .= ",";
				$sql .= " `name`='".addslashes($_POST['section_name'][$section_id])."'";
			}
			$query = "UPDATE ".TABLE_PREFIX."sections SET ".$sql." WHERE section_id = '".$section_id."' LIMIT 1";
			if($sql != '') {
				$oStatement = $database->db_handle->prepare( $query );
				$oStatement->execute();
			}
		}
	}
}
// Check for error or print success message
if($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/sections.php?page_id='.$page_id);
} else {
	$admin->print_success($MESSAGE['PAGES_SECTIONS_PROPERTIES_SAVED'], ADMIN_URL.'/pages/sections.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>