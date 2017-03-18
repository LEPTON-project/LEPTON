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

// exec initial_page
if(file_exists(LEPTON_PATH .'/modules/initial_page/classes/class.init_page.php') && isset($_SESSION['USER_ID'])) {
	require_once (LEPTON_PATH .'/modules/initial_page/classes/class.init_page.php');
	$ins = new class_init_page($database, $_SESSION['USER_ID'], $_SERVER['SCRIPT_NAME']);
}
require_once(LEPTON_PATH.'/framework/class.admin.php');
$admin = new admin('Start','start');

if(file_exists(THEME_PATH."/globals/lte_globals.php")) require_once(THEME_PATH."/globals/lte_globals.php");

// get current release no
$url = "https://raw.githubusercontent.com/LEPTON-project/LEPTON/master/upload/admins/interface/version.php";
$current_release_source = file_get_contents($url, false);

$matches = array();
$result = preg_match ( "/define\(\'VERSION\'\, \'(.*)\'\)/i" , $current_release_source, $matches); 
$current_release = $matches[1];
$is_uptodate = (version_compare( LEPTON_VERSION, $current_release, "=" )) ? 1 : 0;

//die(print_r($current_release_source));

// get pages and sections info
$pages = array();
$database->execute_query(
"SELECT * FROM `".TABLE_PREFIX."pages` ORDER BY `modified_when` DESC ",
true,
$pages,
true
);

$last_pmodified = date("d.m.Y - H:i", $pages[0]['modified_when']);
$page_link_fe = LEPTON_URL.PAGES_DIRECTORY.$pages[0]['link'].PAGE_EXTENSION;
$page_link_be = ADMIN_URL.'/pages/modify.php?page_id='.$pages[0]['page_id'];

$count_pages = $database->get_one("SELECT COUNT(*) FROM `".TABLE_PREFIX."pages`");
$count_section = $database->get_one("SELECT COUNT(*) FROM `".TABLE_PREFIX."sections` ");
$count_sections = ($count_section -1);

//get addons info
$count_modules = $database->get_one("SELECT COUNT(*) FROM `".TABLE_PREFIX."addons` WHERE `type`='module' ");
$count_templates = $database->get_one("SELECT COUNT(*) FROM `".TABLE_PREFIX."addons` WHERE `type`='template' ");
$count_languages = $database->get_one("SELECT COUNT(*) FROM `".TABLE_PREFIX."addons` WHERE `type`='language' ");

//get users and groups info
$count_users = $database->get_one("SELECT COUNT(*) FROM `".TABLE_PREFIX."users` ");
$count_groups = $database->get_one("SELECT COUNT(*) FROM `".TABLE_PREFIX."groups` ");

$page_values = array(
	'ADMIN_URL'		=> ADMIN_URL,
	'THEME_URL'		=> THEME_PATH,
	'LEPTON_URL' 	=> LEPTON_URL,
	'count_sections'=> $count_sections,
	'count_pages'	=> $count_pages,
	'count_modules' => $count_modules,
	'count_templates'=> $count_templates,
	'count_languages'=> $count_languages,
	'count_users' 	=> $count_users,
	'count_groups' 	=> $count_groups,	
	'last_pmodified'=> $last_pmodified,
	'lepton_link' 	=> 'https://lepton-cms.org',
	'page_link_fe' 	=> $page_link_fe,
	'page_link_be' 	=> $page_link_be,	
	'current_release'=> $current_release,
	'is_uptodate' 	=> $is_uptodate

);

$loader->prependPath( dirname(__FILE__), "start" );

echo $parser->render(
	'@start/dashboard.lte',
	$page_values
);

$admin->print_footer();

?>