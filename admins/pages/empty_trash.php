<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010, Website Baker Project
 * @copyright       2010-2011, LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @version         $Id: empty_trash.php 1172 2011-10-04 15:26:26Z frankh $
 *
 */
 
// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
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



require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages');

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Get page list from database
// $database = new database();
$query = "SELECT * FROM ".TABLE_PREFIX."pages WHERE visibility = 'deleted' ORDER BY level DESC";
$get_pages = $database->query($query);

// Insert values into main page list
if($get_pages->numRows() > 0)	{
	while($page = $get_pages->fetchRow()) {
		// Delete page subs
		$sub_pages = get_subs($page['page_id'], array());
		foreach($sub_pages AS $sub_page_id) {
			delete_page($sub_page_id);
		}	
		// Delete page
		delete_page($page['page_id']);
	}
}

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error());
} else {
	$admin->print_success($TEXT['TRASH_EMPTIED']);
}

// Print admin 
$admin->print_footer();

?>