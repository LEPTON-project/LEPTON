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

if(file_exists(THEME_PATH."/globals/lte_globals.php"))
{
    require_once(THEME_PATH."/globals/lte_globals.php");
}

//	Get all languages
$all_languages = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."addons` WHERE `type` = 'language' order by `name`",
	true,
	$all_languages,
	true
);

$page_values = array(
	'THEME' => $THEME,
	'url_templates'	=> $admin->get_permission('templates'),
	'url_modules'	=> $admin->get_permission('modules'),
	'action_url'	=> ADMIN_URL."/languages/",
	'perm_install'	=> $admin->get_permission('languages_install'),
	'perm_uninstall'=> $admin->get_permission('languages_uninstall'),
	'perm_view'		=> $admin->get_permission('languages_view'),
	'all_languages'	=> $all_languages
);

$oTWIG->registerPath( THEME_PATH."/templates","languages" );
echo $oTWIG->render(
	"@theme/languages.lte",
	$page_values
);

// Print admin footer
$admin->print_footer();

?>