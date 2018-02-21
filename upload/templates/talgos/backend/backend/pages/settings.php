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


// get twig instance
$oTWIG = lib_twig_box::getInstance();
$admin = LEPTON_admin::getInstance();
$oTALG = talgos::getInstance();

// Get perms and page_details
$current_page = array();
$database->execute_query(
	'SELECT * FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$page_id,
	true,
	$current_page,
	false
);

if($database->is_error()) {
	$admin->print_header();
	$admin->print_error($database->get_error());
}

if(count($current_page) == 0) {
	$admin->print_header();
	$admin->print_error($MESSAGE['PAGES_NOT_FOUND']);
}

$old_admin_groups = explode(',', $current_page['admin_groups']);
$old_admin_users = explode(',', $current_page['admin_users']);

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

// Get display name of person who last modified the page
$user=$admin->get_user_details($current_page['modified_by']);

// Convert the unix ts for modified_when to human a readable form
$modified_ts = ($current_page['modified_when'] != 0)
	? date(TIME_FORMAT.', '.DATE_FORMAT, $current_page['modified_when'])
	: 'Unknown'	;


$admin_groups = explode(',', str_replace('_', '', $current_page['admin_groups']));

$lepton_core_all_groups = array();
$database->execute_query(
	'SELECT * FROM `'.TABLE_PREFIX.'groups`',
	true,
	$lepton_core_all_groups,
	true
);

/**
 *	Get all pages as (array-) tree
 */
LEPTON_handle::register("page_tree");

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
	$all_languages,
	true
);

/**
 *	Get all installed templates
 */
$all_templates = array();
$database->execute_query(
	'SELECT `directory`,`name`,`function` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "template" AND (`function` = "template" OR `function`="") order by `name`',
	true,
	$all_templates,
	true
);

/**
 *	Try to get the correct Menu
 */
$temp = ($current_page['template'] == "") ? DEFAULT_TEMPLATE : $current_page['template'];
$temp_path = LEPTON_PATH."/templates/".$temp."/info.php";
require_once $temp_path;
$all_menus = (isset($menu) ? $menu : array() );
 
$page_values = array(
	'oTALG' 	=> $oTALG,	
	'PAGE_ID' 	=> $page_id,
	'current_page'	=> $current_page,
	'page_link'		=> substr($current_page['link'],strripos($current_page['link'],'/')+1),
	'PAGE_EXTENSION'=> PAGE_EXTENSION,
	'LANGUAGE' 		=> LANGUAGE,
	'PAGE_LANGUAGES'=> PAGE_LANGUAGES,	
	'leptoken'		=> get_leptoken(),
	'all_groups'	=> $lepton_core_all_groups,
	'all_pages'		=> $all_pages,
	'all_languages' => $all_languages,
	'all_templates' => $all_templates,
	'all_menus'		=> $all_menus,

	


	'DESCRIPTION' => $current_page['description'],
	'KEYWORDS' => $current_page['keywords'],
    'PAGE_CODE' => $current_page['page_code'],
	'MODIFIED_BY' => $user['display_name'],
	'MODIFIED_BY_USERNAME' => $user['username'],
	'MODIFIED_WHEN' => $modified_ts,

	'PAGE_LANGUAGE' => $current_page['language']	
);

$oTWIG->registerPath( THEME_PATH."theme","pages_settings" );
echo $oTWIG->render(
	"@theme/pages_settings.lte",
	$page_values
);

$admin->print_footer();

?>