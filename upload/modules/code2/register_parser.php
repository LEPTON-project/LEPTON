<?php
/**
 *  @module         code2
 *  @version        see info.php of this module
 *  @author         LEPTON Project
 * @copyright       2010-2017 LEPTON Project 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 * 
 */
 
/**	*******************************
 *	Try to get the template-engine.
 *
 *	Make your basic settings for your module-backend interface(-s) here.
 *	Keep in mind, that the paths-settings belongs to the backend only!
 *
 */
global $parser, $loader;
if (!isset($parser))
{
	require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
}

require(dirname(__FILE__)."/info.php");

$loader->prependPath( dirname(__FILE__)."/templates/", $module_directory );

$backend_template_path = LEPTON_PATH."/templates/".DEFAULT_THEME."/backend/".$module_directory."/";
$module_template_path = dirname(__FILE__)."/templates/";

require_once (LEPTON_PATH."/modules/lib_twig/classes/class.twig_utilities.php");
$twig_util = new twig_utilities( $parser, $loader, $module_template_path, $backend_template_path );
$twig_util->template_namespace = $module_directory;

$twig_modul_namespace = "@".$module_directory."/"

?>