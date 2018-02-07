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
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @version         $Id: restore.php 1172 2011-10-04 15:26:26Z frankh $
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

$admin = new LEPTON_admin('Pages', 'pages_delete');

// Include the functions file
require_once(LEPTON_PATH.'/framework/summary.functions.php');

// Get perms
// $results = $database->query("SELECT admin_groups,admin_users FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'");
// $results_array = $results->fetchRow();

// Find out more about the page
$aPageInfo = array();

$database->execute_query(
    "SELECT * FROM `".TABLE_PREFIX."pages` WHERE `page_id` = ".$page_id,
    true,
    $aPageInfo,
    false
);

if($database->is_error())
{
	$admin->print_error($database->get_error());
}
elseif( count($aPageInfo) == 0)
{
	$admin->print_error($MESSAGE['PAGES_NOT_FOUND']);
}

$old_admin_groups = explode(',', str_replace('_', '', $aPageInfo['admin_groups']));
$old_admin_users = explode(',', str_replace('_', '', $aPageInfo['admin_users']));

$in_old_group = FALSE;
foreach($admin->get_groups_id() as $cur_gid)
{
    if (in_array($cur_gid, $old_admin_groups))
    {
        $in_old_group = TRUE;
    }
}

if((!$in_old_group) AND !is_numeric(array_search($admin->get_user_id(), $old_admin_users)))
{
    $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
}

/**
 *  [1]
 *  Function to change all child pages visibility to deleted
 */
function restore_subs($parent = 0) {
    // global $database;
    
    // Query pages
	$aSubpages = array();
	$database->execute_query(
	    "SELECT `page_id` FROM `".TABLE_PREFIX."pages` WHERE `parent` = '".$parent."' ORDER BY position ASC",
	    true,
	    $aSubpages,
	    true;
	);

    foreach($aSubpages as $page)
    {
        // Update the page visibility to 'deleted'
        $database->simple_query("UPDATE `".TABLE_PREFIX."pages` SET `visibility` = 'public' WHERE page_id = '".$page['page_id']."'");
        
        // Run function for all sub-pages ... See [1]
        restore_subs($page['page_id']);
    }
}
		
$visibility = $aPageInfo['visibility'];

if(PAGE_TRASH)
{
	if($visibility == 'deleted')
	{	
        // Update the page visibility to 'public'
        $database->simple_query("UPDATE `".TABLE_PREFIX."pages` SET `visibility` = 'public' WHERE page_id = ".$page_id);
		
        // Run trash subs for this page
        restore_subs($page_id);
	}
}

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error());
} else {
	$admin->print_success($MESSAGE['PAGES_RESTORED']);
}

// Print admin footer
$admin->print_footer();

?>