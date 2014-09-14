<?php
/**
 *  @module         news
 *  @version        see info.php of this module
 *  @author         Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos), LEPTON Project
 *  @copyright      2004-2010 Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos) 
 * 	@copyright      2010-2014 LEPTON Project 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 * 
 */
 
/**	*******************************
 *	Try to get the template-engine.
 *
 *	Make your basic settings for your module-backend interface(-s) here.
 */
global $parser, $loader;
if (!isset($parser))
{
	require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
}

$loader->prependPath( dirname(__FILE__)."/templates/backend/", "news" );

$backend_template_path = LEPTON_PATH."/templates/".DEFAULT_THEME."/backend/news/";
$module_template_path = dirname(__FILE__)."/templates/backend/";

require_once (LEPTON_PATH."/modules/lib_twig/classes/class.twig_utilities.php");
$twig_util = new twig_utilities( $parser, $loader, $module_template_path, $backend_template_path );
$twig_util->template_namespace = "news";

?>