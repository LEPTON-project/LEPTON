<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          Twig Template Engine
 * @author          LEPTON Project
 * @copyright       2012-2017 LEPTON  
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if ( defined( 'LEPTON_PATH' ) )
{
	include( LEPTON_PATH . '/framework/class.secure.php' );
} //defined( 'LEPTON_PATH' )
else
{
	$oneback = "../";
	$root    = $oneback;
	$level   = 1;
	while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
	{
		$root .= $oneback;
		$level += 1;
	} //( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) )
	if ( file_exists( $root . '/framework/class.secure.php' ) )
	{
		include( $root . '/framework/class.secure.php' );
	} //file_exists( $root . '/framework/class.secure.php' )
	else
	{
		trigger_error( sprintf( "[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER[ 'SCRIPT_NAME' ] ), E_USER_ERROR );
	}
}
// end include class.secure.php

global $parser;
global $loader;

//	Use the internal LEPTON autoloader
lib_twig::register();

$loader = new Twig_Loader_Filesystem( LEPTON_PATH.'/' );
$loader->prependPath( LEPTON_PATH."/templates/".DEFAULT_THEME."/templates/", "theme" );

$parser = new Twig_Environment( $loader, array(
	'cache' => false,
	'debug' => true
) );

$parser->addGlobal("LEPTON_URL", LEPTON_URL);
$parser->addGlobal("LEPTON_PATH", LEPTON_PATH);
$parser->addGlobal("ADMIN_URL", ADMIN_URL);
$parser->addGlobal("THEME_PATH", THEME_PATH);
$parser->addGlobal("THEME_URL", THEME_URL);

?>