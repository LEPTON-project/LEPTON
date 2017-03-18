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


// put all inside a function to prevent global vars
function build_page(&$admin, &$database)
{
    global $HEADING, $TEXT, $MENU, $MESSAGE;
	global $parser, $loader;
	
	LEPTON_tools::load(
		LEPTON_PATH."/modules/lib_twig/library.php",
		LEPTON_PATH."/modules/lib_r_filemanager/library.php"
	);
	
	if(file_exists(THEME_PATH."/globals/lte_globals.php")) require_once(THEME_PATH."/globals/lte_globals.php");

	$loader->prependPath( THEME_PATH."/templates/", "theme" );	// namespace for the Twig-Loader is "theme"

	$page_values = array(
		'source'	=> 	LEPTON_URL.'/modules/lib_r_filemanager/filemanager/dialog.php?akey='.$_SESSION['rfkey'],
		'image'		=> 	LEPTON_URL.'/modules/lib_r_filemanager/filemanager/img/blank.png',	
		'id'		=>	"id='r_filemanager'",	
//	'style'		=> 	"style=''",	
		'seamless'	=>	"seamless='seamless'"
	);

	echo $parser->render(
		"@theme/media.lte",
		$page_values
	);
}
// Print admin footer
require_once(LEPTON_PATH.'/framework/class.admin.php');
$admin = new admin('Media', 'media'); 

print build_page($admin, $database);
  
$admin->print_footer();
?>