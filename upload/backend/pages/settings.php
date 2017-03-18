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
if(!isset($_GET['page_id']) OR !is_numeric($_GET['page_id']))
{
	header("Location: index.php");
	exit(0);
} else {
	$page_id = intval($_GET['page_id']);
}

require_once(LEPTON_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_settings');

// Include the functions file
// require_once(LEPTON_PATH.'/framework/summary.utf8.php');

// Get perms
$results_array = array();
$database->execute_query(
	'SELECT `admin_groups`,`admin_users` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$page_id,
	true,
	$results_array,
	false
);

$old_admin_groups = explode(',', $results_array['admin_groups']);
$old_admin_users = explode(',', $results_array['admin_users']);

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

// Get page details
$database->execute_query(
	'SELECT * FROM `'.TABLE_PREFIX.'pages` WHERE `page_id`='.$page_id,
	true,
	$results_array,
	false
);

if($database->is_error()) {
	$admin->print_header();
	$admin->print_error($database->get_error());
}

if(count($results_array) == 0) {
	$admin->print_header();
	$admin->print_error($MESSAGE['PAGES_NOT_FOUND']);
}

// Get display name of person who last modified the page
$user=$admin->get_user_details($results_array['modified_by']);

// Convert the unix ts for modified_when to human a readable form
$modified_ts = ($results_array['modified_when'] != 0)
	? date(TIME_FORMAT.', '.DATE_FORMAT, $results_array['modified_when'])
	: 'Unknown'	;


$admin_groups = explode(',', str_replace('_', '', $results_array['admin_groups']));

$lepton_core_all_groups = array();
$database->execute_query(
	'SELECT * FROM `'.TABLE_PREFIX.'groups`',
	true,
	$lepton_core_all_groups
);

/**
 *	Get all pages as (array-) tree
 */
if (!function_exists("page_tree")) require_once( LEPTON_PATH."/framework/functions/function.page_tree.php");

//	Storage for all infos in an array
$all_pages = array();

//	Determinate what fields/keys we want to get in our 'page_tree'-array
$fields = array('page_id','page_title','menu_title','parent','position','visibility','link');

//	Get the tree here
page_tree( 0, $all_pages, $fields );

/**
 *	Get all installed languages
 */
$all_languages = array();
$database->execute_query(
	'SELECT `directory`,`name` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "language" ORDER BY `name`',
	true,
	$all_languages
);

/**
 *	Get all installed templates
 */
$all_templates = array();
$database->execute_query(
	'SELECT `directory`,`name`,`function` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "template" AND (`function` = "template" OR `function`="") order by `name`',
	true,
	$all_templates
);

$page_values = array(
	'PAGE_ID' => $page_id,
	'PAGE_TITLE' => $results_array['page_title'],
	'MENU_TITLE' => $results_array['menu_title'],
	'PAGE_LINK' => substr($results_array['link'],strripos($results_array['link'],'/')+1),
	'PAGE_EXTENSION' => PAGE_EXTENSION,
	'PAGE_LANGUAGE' => $results_array['language'],
	'LANGUAGE' => LANGUAGE,
	'DESCRIPTION' => $results_array['description'],
	'KEYWORDS' => $results_array['keywords'],
    'PAGE_CODE' => $results_array['page_code'],
	'MODIFIED_BY' => $user['display_name'],
	'MODIFIED_BY_USERNAME' => $user['username'],
	'MODIFIED_WHEN' => $modified_ts,
	'ADMIN_URL' => ADMIN_URL,
	'LEPTON_URL' => LEPTON_URL,
	'LEPTON_PATH' => LEPTON_PATH,
	'THEME_URL' => THEME_URL,
	//	Aldus - addtions
	'all_groups' => $lepton_core_all_groups,
	'all_pages'	=> $all_pages,
	'all_languages' => $all_languages,
	'all_templates' => $all_templates,
	'LEPTOKEN'		=> LEPTON_tools::get_leptoken(),
	'PAGE_PARENT'	=> $results_array['parent'],
	'page_values'	=> $results_array
);

if(file_exists(THEME_PATH."/globals/lte_globals.php")) require_once(THEME_PATH."/globals/lte_globals.php");

echo $parser->render(
	'@theme/pages_settings.lte',
	$page_values
);

// Print admin footer
$admin->print_footer();

?>