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

// Get id
if(!isset($_GET['group_id']) OR !is_numeric($_GET['group_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$group_id = $_GET['group_id'];
}

// Include admin wrapper script
require(LEPTON_PATH.'/modules/admin.php');

/**	*******************************
 *	Try to get the template-engine.
 */
global $parser, $loader;
require_once( dirname(__FILE__)."/register_parser.php" );

// Get header and footer
$fetch_content = array();
$values = array( $group_id );
$query_content = $database->prepare_and_execute(
	"SELECT * FROM ".TABLE_PREFIX."mod_news_groups WHERE group_id = ?",
	$values,
	true,
	$fetch_content,
	false
);

/**
 * Use images? Since version 3.7.0 for LEPTON-CMS we do!
 */
$use_images = TRUE;

$form_values = array(
	'TEXT_ADD'		=> $TEXT['ADD'],
	'TEXT_MODIFY'	=> $TEXT['MODIFY'],
	'TEXT_GROUP'	=> $TEXT['GROUP'],
	'section_id'	=> $section_id,
	'page_id'		=> $page_id,
	'group_id'		=> $group_id,
	'TEXT_TITLE'	=> $TEXT['TITLE'],
	'title'			=> htmlspecialchars($fetch_content['title']),
	'use_images'	=> 1,
	'TEXT_IMAGE'	=> $TEXT['IMAGE'],
	'file_exists'	=> (file_exists(LEPTON_PATH.MEDIA_DIRECTORY.'/.news/image'.$group_id.'.jpg')) ? 1 : 0,
	'LEPTON_URL'	=> LEPTON_URL,
	'MEDIA_DIRECTORY' => MEDIA_DIRECTORY,
	'TEXT_VIEW'		=> $TEXT['VIEW'],
	'TEXT_DELETE'	=> $TEXT['DELETE'],
	'TEXT_ACTIVE'	=> $TEXT['ACTIVE'],
	'active'		=> $fetch_content['active'],
	'TEXT_YES'		=> $TEXT['YES'],
	'TEXT_NO'		=> $TEXT['NO'],
	'TEXT_DELETE'	=> $TEXT['DELETE'],
	'TEXT_SAVE'		=> $TEXT['SAVE'],
	'TEXT_CANCEL'	=> $TEXT['CANCEL'],
	'ADMIN_URL'		=> ADMIN_URL	
);

$twig_util->resolve_path("modify_comment.lte");
echo $parser->render(
	$twig_modul_namespace.'modify_group.lte',
	$form_values
);

// Print admin footer
$admin->print_footer();

?>