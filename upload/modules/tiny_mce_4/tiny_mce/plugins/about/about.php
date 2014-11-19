<?php
/**
 *	about dialog
 *
 */

// header("Content-type: text/javascript; charset=utf-8");

if (defined('LEPTON_PATH')) {   
   include(LEPTON_PATH.'/framework/class.secure.php');
} else {
   $oneback = "../";
   $root = $oneback;
   $level = 1;
   while (($level < 15) && (!file_exists($root.'/framework/class.secure.php'))) {
      $root .= $oneback;
      $level += 1;
   }
   if (file_exists($root.'/framework/class.secure.php')) {
      include($root.'/framework/class.secure.php');
   } else {
      trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
   }
}

global $database;

/**
 *	try to read the info.php of this module.
 */
require_once( LEPTON_PATH."/modules/tiny_mce_4/info.php");

/**
 *	try to get the (twig-) parser
 */
require_once( LEPTON_PATH."/modules/lib_twig/library.php");

global $parser;
global $loader;

$loader->prependPath( dirname(__FILE__)."/templates/" );

$page_content = array(
	'plugin_path'	=> LEPTON_URL."/modules/tiny_mce_4/tiny_mce/plugins/about",
	'module_name'	=> $module_name,
	'module_version' => $module_version,
	'module_description' => $module_description
);

echo $parser->render(
	'about.lte',
	$page_content
);

?>