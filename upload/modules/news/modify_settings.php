<?php

/**
 *  @module         news
 *  @version        see info.php of this module
 *  @author         Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos), LEPTON Project
 *  @copyright      2004-2010 Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos) 
 * 	@copyright      2010-2017 LEPTON Project 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
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

global $database;

// Include admin wrapper script
require(LEPTON_PATH.'/modules/admin.php');

// include core functions of WB 2.7 to edit the optional module CSS files (frontend.css, backend.css)
include_once(LEPTON_PATH .'/framework/summary.module_edit_css.php');

// load module language file
$lang = (dirname(__FILE__)) . '/languages/' . LANGUAGE . '.php';
require_once(!file_exists($lang) ? (dirname(__FILE__)) . '/languages/EN.php' : $lang );

/**	*******************************
 *	Try to get the template-engine.
 */
global $parser, $loader;
require_once( dirname(__FILE__)."/register_parser.php" );

// Get settings from the DB
$fetch_content = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."mod_news_settings` WHERE `section_id` = '".$section_id."'",
	true,
	$fetch_content,
	false
);

/*	**********
 *	Here we go 
 */
$form_values = array(
	'LEPTON_PATH' => LEPTON_PATH,
	'LEPTON_URL' => LEPTON_URL,
	'ADMIN_URL' => ADMIN_URL,
	'TEXT'	=> $TEXT,
	'HEADING' => $HEADING,
	'MOD_NEWS'	=> $MOD_NEWS,
	'leptoken' => (isset($_GET['leptoken']) ? $_GET['leptoken'] : ""),
	'page_id'	=> $page_id,
	'section_id'	=> $section_id,
	'posts_per_page' => $fetch_content['posts_per_page'],
	'extension_loaded_gd' => extension_loaded('gd') ? 1 : 0,
	'imageCreateFromJpeg' => function_exists('imageCreateFromJpeg') ? 1 : 0,
	'commenting' => $fetch_content['commenting'],
	'use_captcha' => $fetch_content['use_captcha'],
	'resize' => $fetch_content['resize'],
	'edit_module_css' => $twig_util->capture_echo("edit_module_css('news');")
);

$twig_util->resolve_path("modify_settings.lte");

echo $parser->render(
	$twig_modul_namespace.'modify_settings.lte',
	$form_values
);

// Print admin footer
$admin->print_footer();

?>