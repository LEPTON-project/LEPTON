<?php

/**
 *  @module         code2
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @copyright      2004-2017 Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if ( defined( 'LEPTON_PATH' ) )
{
	include( LEPTON_PATH . '/framework/class.secure.php' );
}
else
{
	$oneback = "../";
	$root    = $oneback;
	$level   = 1;
	while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
	{
		$root .= $oneback;
		$level += 1;
	}
	if ( file_exists( $root . '/framework/class.secure.php' ) )
	{
		include( $root . '/framework/class.secure.php' );
	}
	else
	{
		trigger_error( sprintf( "[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER[ 'SCRIPT_NAME' ] ), E_USER_ERROR );
	}
}
// end include class.secure.php

header('Content-Type: application/javascript');

if(!isset($_GET['pid'])) die();

$page_id = intval( $_GET['pid'] );
if( $page_id < 1 ) die();

// echo 'console.log("Aldus:: code2 for '.$_GET['pid'].' start");';

$all_code2_sections = array();
$database->execute_query(
	"SELECT `section_id` FROM `".TABLE_PREFIX."sections` WHERE `page_id`=".$page_id." AND `module`='code2'",
	true,
	$all_code2_sections,
	true
);

$oTwig = lib_twig_box::getInstance();
$oTwig->registerModule("code2");

echo $oTwig->render(
	"@code2/backend_footer_js.lte",
	array(
		'all_sections' => $all_code2_sections,
		'LANGUAGE'		=> (LANGUAGE == "DE") ? "DE" : "EN"
	)
);
