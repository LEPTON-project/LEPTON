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



// require_once(LEPTON_PATH.'/framework/class.admin.php');
$admin = new LEPTON_admin('admintools', 'admintools');

// Include the functions file
require_once(LEPTON_PATH.'/framework/summary.functions.php');

// Create new template object
$tpl = new Template(THEME_PATH.'/templates');
$tpl->set_file('page', 'admintools.htt');
$tpl->set_block('page', 'main_block', 'main');

// Insert required template variables
$tpl->set_var('ADMIN_URL', ADMIN_URL);
$tpl->set_var('THEME_URL', THEME_URL);
$tpl->set_var('HEADING_ADMINISTRATION_TOOLS', $HEADING['ADMINISTRATION_TOOLS']);

// Insert tools into tool list
$tpl->set_block('main_block', 'tool_list_block', 'tool_list');

// test for admin-rights
$temp = $admin->get_groups_id();
if(!is_array($temp))
{
    $bIsAdmin = ($temp == 1) ? true : false;
} else {
    $bIsAdmin = ( true == in_array( 1, $temp ) ) ? true : false;
}

$results = $database->query("SELECT `directory`,`name`,`description` FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND function = 'tool' AND `directory` ".($bIsAdmin == true ? 'not' : '')." in ('".(implode("','",$_SESSION['MODULE_PERMISSIONS']))."') order by name");
if($results->numRows() > 0) {
	while(false != ($tool = $results->fetchRow() ) ) {
		$tpl->set_var('TOOL_NAME', $tool['name']);
		$tpl->set_var('TOOL_DIR', $tool['directory']);
		// check if a module description exists for the displayed backend language
		$module_description = false;
		$language_file = LEPTON_PATH.'/modules/'.$tool['directory'].'/languages/'.LANGUAGE .'.php';
		if(true === file_exists($language_file)) {
			require( $language_file );
		}		
		$tpl->set_var('TOOL_DESCRIPTION', ($module_description === false)? $tool['description'] : $module_description);
    $tpl->set_var('LEPTON_URL', LEPTON_URL);
		$tpl->parse('tool_list', 'tool_list_block', true);
	}
} else {
	$tpl->set_var('TOOL_LIST', $TEXT['NONE_FOUND']);
}

// Parse template objects output
$tpl->parse('main', 'main_block', false);
$tpl->pparse('output', 'page');

$admin->print_footer();

?>