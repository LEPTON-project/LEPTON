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
 * @version         $Id: index.php 1172 2011-10-04 15:26:26Z frankh $
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

require_once(LEPTON_PATH.'/framework/class.admin.php');
$admin = new admin('Addons', 'templates');

/**	************************
 *	Get the template-engine.
 */
global $parser, $loader;
if (!isset($parser))
{
	require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
}
if(file_exists(THEME_PATH."/globals/lte_globals.php")) require_once(THEME_PATH."/globals/lte_globals.php");
$loader->prependPath( THEME_PATH."/templates/", "theme" );	// namespace for the Twig-Loader is "theme"

/**	*****************
 *	Get all templates
 */
$all_templates = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."addons` WHERE `type` = 'template' order by `name`",
	true,
	$all_templates
);

/**	**********************************
 *	Build secure-hash for the js-calls
 */
if(!function_exists("random_string")) require_once( LEPTON_PATH."/framework/functions/function.random_string.php");
$hash = array(
	'h_name' => random_string(16),
	'h_value' => random_string(24)
);
$_SESSION['backend_templates_h'] = $hash['h_name'];
$_SESSION['backend_templates_v'] = $hash['h_value'];

/**
 *	Collecting all page values here
 */
$page_values = array(
	'all_templates'	=> $all_templates,
	'hash'	=> $hash
);

echo $parser->render(
	"@theme/templates.lte",
	$page_values
);

/*
// Insert language text and messages
$template->set_var(array(
	'URL_MODULES' => $admin->get_permission('modules') ? 
		'<a class="button" href="' . ADMIN_URL . '/modules/index.php">' . $MENU['MODULES'] . '</a>' : '',
	'URL_LANGUAGES' => $admin->get_permission('languages') ? 
		'<a class="button" href="' . ADMIN_URL . '/languages/index.php">' . $MENU['LANGUAGES'] . '</a>' : '',
	'URL_ADVANCED' => $admin->get_permission('admintools') ? 
	'<a class="button" href="' . ADMIN_URL . '/modules/index.php?advanced">' . $TEXT['ADVANCED'] . '</a>' : '',
	'TEXT_INSTALL' => $TEXT['INSTALL'],
	'TEXT_UNINSTALL' => $TEXT['UNINSTALL'],
	'TEXT_VIEW_DETAILS' => $TEXT['VIEW_DETAILS'],
	'TEXT_PLEASE_SELECT' => $TEXT['PLEASE_SELECT'],
	'CHANGE_TEMPLATE_NOTICE' => $MESSAGE['TEMPLATES_CHANGE_TEMPLATE_NOTICE']
	)
);
*/
// Print admin footer
$admin->print_footer();

?>