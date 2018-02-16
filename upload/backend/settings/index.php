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

// prevent users to access url directly
if(!in_array('settings',$_SESSION['SYSTEM_PERMISSIONS']))  {
	header("Location: ".ADMIN_URL."");
	exit(0);
}

// enable custom files
//LEPTON_handle::require_alternative('/templates/'.DEFAULT_THEME.'/backend/backend/settings/index.php');
if(file_exists(THEME_PATH .'/backend/backend/settings/index.php')) {
	require_once (THEME_PATH .'/backend/backend/settings/index.php');
	die();
}
// get twig instance
$oTWIG = lib_twig_box::getInstance();
$admin = LEPTON_admin::getInstance();
	
// check if current user is admin
$curr_user_is_admin = ( in_array( 1, $admin->get_groups_id() ) );


$all_settings = array();
$database->execute_query(
	"SELECT `name`,`value` from `".TABLE_PREFIX."settings`",
	true,
	$all_settings,
	true
);

$settings = array();
foreach($all_settings as &$ref) $settings[ $ref['name'] ] = $ref['value'];
	


//	get an instance from LEPTON_basics as we "call" this more than once
$oLEPTON = LEPTON_basics::getInstance();
	
$page_values = array(
	'FORM_NAME'	=> 'settings',	
	'ACTION_URL'	=> ADMIN_URL.'/settings/save.php',
	'leptoken'		=> get_leptoken(),
	'error_levels'	=> $oLEPTON->get_errorlevels(),
	'timezones'		=> $oLEPTON->get_timezones(),
	'date_formats'	=> $oLEPTON->get_dateformats(),	
	'time_formats'	=> $oLEPTON->get_timeformats()
);

//	[2.0] db fields of settings
foreach($settings as $key => $value) $page_values[ strtoupper( $key ) ] = $value;

//	[2.1] Languages
$database->execute_query(
	"SELECT `name`,`directory` FROM `" . TABLE_PREFIX . "addons` WHERE `type` = 'language' ORDER BY `name`",
	true,
	$page_values['languages'],
	true
);
	
//	[2.2] installed editors
$database->execute_query(
	"SELECT `name`,`directory` FROM `" . TABLE_PREFIX . "addons` WHERE `type` = 'module' AND `function`='wysiwyg' ORDER BY `name`",
	true,
	$page_values['editors'],
	true
);

//	[2.3.1] template list
$database->execute_query(
	"SELECT `name`,`directory` FROM `" . TABLE_PREFIX . "addons` WHERE `type` = 'template' AND `function` != 'theme' ORDER BY `name`",
	true,
	$page_values['templates'],
	true
);

//	[2.3.2] backend theme list
$database->execute_query(
	"SELECT `name`,`directory` FROM `" . TABLE_PREFIX . "addons` WHERE `type` = 'template' AND `function` = 'theme' ORDER BY `name`",
	true,
	$page_values['themes'],
	true
);

//	[2.4.0] search table
$temp_search_values = array();
$database->execute_query(
	"SELECT `name`, `value` FROM `".TABLE_PREFIX."search` WHERE `extra` = '' ",
	true,
	$temp_search_values,
	true
);

$search_settings = array();
foreach($temp_search_values as $ref)
{
	$search_settings[ $ref['name'] ] = $ref['value'];
}
$page_values['search'] = $search_settings;
	
//	[2.5.0] groups
$database->execute_query(
	"SELECT `group_id`,`name` FROM `" . TABLE_PREFIX . "groups` WHERE `group_id` > 1 ORDER BY `name`",
	true,
	$page_values['groups'],
	true
);


$oTWIG->registerPath( THEME_PATH."theme","settings" );
echo $oTWIG->render(
	"@theme/settings.lte",
	$page_values
);

$admin->print_footer();

?>