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
if(!isset($_GET['comment_id']) OR !is_numeric($_GET['comment_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$comment_id = $_GET['comment_id'];
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
$values = array($comment_id);
$query_content = $database->prepare_and_execute(
	"SELECT `post_id`,`title`,`comment` FROM `".TABLE_PREFIX."mod_news_comments` WHERE comment_id = ?",
	$values,
	true,
	$fetch_content,
	false
);

$form_values = array(
	'TEXT_MODIFY'	=> $TEXT['MODIFY'],
	'LEPTON_URL'	=> LEPTON_URL,
	'section_id'	=> $section_id,
	'page_id'		=> $page_id,
	'post_id'		=> $fetch_content['post_id'],
	'comment_id'	=> $comment_id,
	'TEXT_TITLE'	=> $TEXT['TITLE'],
	'title'			=> htmlspecialchars($fetch_content['title']),
	'TEXT_COMMENT'	=> $TEXT['COMMENT'],
	'comment'		=> htmlspecialchars($fetch_content['comment']),
	'SAVE'			=> $TEXT['SAVE'],
	'CANCEL'		=> $TEXT['CANCEL']
);

$twig_util->resolve_path("modify_comment.lte");
echo $parser->render(
	$twig_modul_namespace.'modify_comment.lte',
	$form_values
);

// Print admin footer
$admin->print_footer();

?>