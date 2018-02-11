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


// get twig instance
$admin = LEPTON_admin::getInstance();
$oTWIG = lib_twig_box::getInstance();


//	Get all templates
$all_templates = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."addons` WHERE `type` = 'template' order by `name`",
	true,
	$all_templates,
	true
);


/**
 *	Collecting all page values here
 */
$page_values = array(
	'all_templates'	=> $all_templates,
	'url_modules'	=> $admin->get_permission('modules'),
	'url_languages'	=> $admin->get_permission('languages'), 
	'perm_admin'	=> $admin->get_permission('admintools'),
	'perm_install'	=> $admin->get_permission('templates_install'),
	'perm_uninstall'=> $admin->get_permission('templates_uninstall'),
	'perm_view'		=> $admin->get_permission('templates_view'),
	'action_url'	=> ADMIN_URL."/templates/",
	'alternative_url'	=> THEME_URL."/backend/backend/templates/"
);

$oTWIG->registerPath( THEME_PATH."/templates","templates" );
echo $oTWIG->render(
	"@theme/templates.lte",
	$page_values
);

// Print admin footer
$admin->print_footer();

?>