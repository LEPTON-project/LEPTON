<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
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

// get twig instance
$admin = LEPTON_admin::getInstance();
$oTWIG = lib_twig_box::getInstance();

// test for admin-rights
$temp = $admin->get_groups_id();
if(!is_array($temp))
{
    $bIsAdmin = ($temp == 1) ? true : false;
} else {
    $bIsAdmin = ( true == in_array( 1, $temp ) ) ? true : false;
}

$all_tools = array();
$database->execute_query(
	"SELECT `directory`,`name`,`description` FROM `".TABLE_PREFIX."addons` WHERE `type` = 'module' AND `function` = 'tool' AND `directory` ".($bIsAdmin == true ? 'not' : '')." in ('".(implode("','",$_SESSION['MODULE_PERMISSIONS']))."') order by `name`",
	true,
	$all_tools,
	true	
);

foreach($all_tools as &$tool) {
	// check if a module description exists for the displayed backend language
	$module_description = false;
	$language_file = LEPTON_PATH.'/modules/'.$tool['directory'].'/languages/'.LANGUAGE .'.php';
	if(true === file_exists($language_file)) {
		require( $language_file );
	}		
	if ($module_description !== false) {
		$module_description = str_replace(
			array("{LEPTON_URL}", "{{ LEPTON_URL }}"),
			LEPTON_URL,
			$module_description
		);
		$tool['description'] = $module_description;
	}
}

$page_values = array(
	'TOOL_NONE_FOUND' => (count($all_tools) == 0) ? $TEXT['NONE_FOUND'] : "",
	'all_tools'	=> $all_tools
);

$oTWIG->registerPath( THEME_PATH."theme","admintools" );
echo $oTWIG->render(
	"@theme/admintools.lte",
	$page_values
);
 
$admin->print_footer();

?>