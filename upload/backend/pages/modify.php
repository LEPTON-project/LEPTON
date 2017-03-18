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
if(!isset($_GET['page_id']) || !is_numeric($_GET['page_id'])) {
	#header("Location: index.php");
	#exit(0);
	$page_id = NULL;
	$display_details = false;
} else {
	$page_id = $_GET['page_id'];
	$display_details = true;
}

/**
 *	If the page_id is NULL we try to get a valid one from the db; the first inside the tree
 *	M.f.i.: Aldus - 27.11.2016
 */
if(NULL === $page_id)
{
	$temp = $database->get_one(	"SELECT `page_id` FROM `".TABLE_PREFIX."pages` order by `position` LIMIT 1" );
	if(is_numeric($temp))
	{
		$page_id = $temp;
		$display_details = true;
	}
}
	
require_once(LEPTON_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_modify');

if (true === $display_details) {
	// Get perms
	if(!$admin->get_page_permission($page_id,'admin')) {
		$admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
	}


	$sectionId = isset($_GET['wysiwyg']) ? htmlspecialchars($admin->get_get('wysiwyg')) : NULL;

	// Get page details
	$results_array=$admin->get_page_details($page_id);

	// Get display name of person who last modified the page
	$user=$admin->get_user_details($results_array['modified_by']);

	// Convert the unix ts for modified_when to human a readable form
	$modified_ts = ($results_array['modified_when'] != 0)
		? $modified_ts = date(TIME_FORMAT.', '.DATE_FORMAT, $results_array['modified_when'])
		: 'Unknown' ;

}

/** ************************
 *	Get the template-engine.
 */
global $parser, $loader;
if (!isset($parser))
{
	require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
}
if(file_exists(THEME_PATH."/globals/lte_globals.php")) require_once(THEME_PATH."/globals/lte_globals.php");
$loader->prependPath( THEME_PATH."/templates/", "theme" );	// namespace for the Twig-Loader is "theme"

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

// get template used for the displayed page (for displaying block details)
if (SECTION_BLOCKS)
{
	$sql = "SELECT `template` from `" . TABLE_PREFIX . "pages` WHERE `page_id` = '$page_id' ";
	$result = $database->query($sql);
	if ($result && $result->numRows() == 1) {
		$row = $result->fetchRow();
		$page_template = ($row['template'] == '') ? DEFAULT_TEMPLATE : $row['template'];
		// include template info file if exists
		if (file_exists(LEPTON_PATH . '/templates/' . $page_template . '/info.php'))
		{
			include_once(LEPTON_PATH . '/templates/' . $page_template . '/info.php');
		}
	}
}

// Get sections for this page
$module_permissions = $_SESSION['MODULE_PERMISSIONS'];

$lepton_core_all_sections = array();

$database->execute_query(
	'SELECT `section_id`, `module`, `block`, `name` FROM `'.TABLE_PREFIX.'sections` WHERE `page_id` = '.intval($page_id).' ORDER BY `position` ASC',
	true,
	$lepton_core_all_sections
);

if(count($lepton_core_all_sections) > 0) {
    foreach($lepton_core_all_sections as &$lepton_core_section) {
		global $section_id;
		$section_id = $lepton_core_section['section_id'];
		$module = $lepton_core_section['module'];

		// Have permission?
		if(!is_numeric(array_search($module, $module_permissions))) {
			
			// Include the modules editing script if it exists
			if(file_exists(LEPTON_PATH.'/modules/'.$module.'/modify.php')) {
				
				if (isset($block[$lepton_core_section['block']]) && trim(strip_tags(($block[$lepton_core_section['block']]))) != '') {
					$lepton_core_section['block_name'] = htmlentities(strip_tags($block[$lepton_core_section['block']]));
				} else {
					if ($lepton_core_section['block'] == 1) {
						$lepton_core_section['block_name'] = $TEXT['MAIN'];
					} else {
						$lepton_core_section['block_name'] = '#' . (int) $lepton_core_section['block'];
					}
				}

				ob_start();
				require(LEPTON_PATH.'/modules/'.$module.'/modify.php');
				$lepton_core_section['content'] = ob_get_clean();
			}
		}
	}
}

$page_values = array(
	'PAGE_ID' => $results_array['page_id'],
	'PAGE_TITLE' => ($results_array['page_title']),
	'MENU_TITLE' => ($results_array['menu_title']),
	'MODIFIED_BY' => $user['display_name'],
	'MODIFIED_BY_USERNAME' => $user['username'],
	'MODIFIED_WHEN' => $modified_ts,
	'LAST_MODIFIED' => $MESSAGE['PAGES_LAST_MODIFIED'],
	'SEC_ANCHOR' => SEC_ANCHOR,
	'all_pages' => $all_pages,
	'all_sections'	=> $lepton_core_all_sections,
	'display_details' => $display_details
);

echo $parser->render(
	"@theme/pages_modify.lte",
	$page_values
);

// Print admin footer
$admin->print_footer();

?>