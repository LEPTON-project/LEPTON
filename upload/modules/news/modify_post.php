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
if(!isset($_GET['post_id']) OR !is_numeric($_GET['post_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$post_id = $_GET['post_id'];
}

// Include admin wrapper script
require(LEPTON_PATH.'/modules/admin.php');

// Get header and footer
$fetch_content = array();
$query_content = $database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."mod_news_posts` WHERE `post_id` = '".$post_id."'",
	true,
	$fetch_content,
	false
);

if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none" OR !file_exists(LEPTON_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
	function show_wysiwyg_editor($name,$id,$content,$width,$height) {
		echo '<textarea name="'.$name.'" id="'.$id.'" rows="10" cols="1" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
	}
} else {
	$id_list=array("short","long");
	require(LEPTON_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
}

/**
 * Use images? Since version 3.7.0 for LEPTON-CMS we're always using images!
*/
$use_images = TRUE;

// include jscalendar-setup
$jscal_use_time = true; // whether to use a clock, too
require_once(LEPTON_PATH."/include/jscalendar/wb-setup.php");

/**
 * Get the groups for this page/section
 */
$groups = array();
$query = $database->execute_query(
	"SELECT `group_id`,`title` FROM `".TABLE_PREFIX."mod_news_groups` WHERE `section_id` = '".$section_id."' ORDER BY `position` ASC",
	true,
	$groups
);

foreach($groups as &$ref) {
	$ref['selected'] = ($ref['group_id'] == $fetch_content['group_id'])
		? "selected='selected'"
		: ""
		;
}
/**
 *	Get the comments for this post
 */
$comments = array();
$query_comments = $database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."mod_news_comments` WHERE `section_id` = '".$section_id."' AND `post_id` = '".$post_id."' ORDER BY commented_when DESC",
	true,
	$comments
);

/**	*******************************
 *	Here we go ....
 */

/**	*******************************
 *	Try to get the template-engine.
 */
global $parser, $loader;
require_once( dirname(__FILE__)."/register_parser.php" );

$form_values = array(
	'LEPTON_URL'	=> LEPTON_URL,
	'LEPTON_PATH'	=> LEPTON_PATH,
	'THEME_URL'		=> THEME_URL,
	'ADMIN_URL'		=> ADMIN_URL,
	'MEDIA_DIRECTORY' => MEDIA_DIRECTORY,
	'page_id'		=> $page_id,
	'section_id'	=> $section_id,
	'post_id'		=> $post_id,
	'link'			=> $fetch_content['link'],
	'title'			=> htmlspecialchars($fetch_content['title']),
	'groups'		=> $groups,
	'commenting'	=> $fetch_content['commenting'],
	'active'		=> $fetch_content['active'],
	'published_when'	=> ( $fetch_content['published_when'] != 0 ) ?  date($jscal_format, $fetch_content['published_when']) : "",
	'published_until'	=> ( $fetch_content['published_until'] != 0 ) ?  date($jscal_format, $fetch_content['published_until']) : "",
	'use_images'			=> $use_images,
	'got_image'			=> file_exists(LEPTON_PATH.MEDIA_DIRECTORY.'/newspics/image'.$post_id.'.jpg') ? 1 : 0,
	'show_wysiwyg_editor_short'	=> $twig_util->capture_echo("show_wysiwyg_editor('short','short',\"".htmlspecialchars($fetch_content['content_short'])."\",'100%','250px');"),
	'show_wysiwyg_editor_long'	=> $twig_util->capture_echo("show_wysiwyg_editor('long','long',\"".htmlspecialchars($fetch_content['content_long'])."\",'100%','550px');" ),	
	'jscal_ifformat'	=> $jscal_ifformat,
	'jscal_use_time'	=> $jscal_use_time,
	'jscal_today'		=> $jscal_today,
	'jscal_firstday'	=> $jscal_firstday,
	'num_of_comments'	=> count($comments),
	'comments'			=> $comments,
	'row'	=> 'a',
	'TEXT'	=> $TEXT
);

$twig_util->resolve_path("modify_post.lte");

echo $parser->render(
	$twig_modul_namespace.'modify_post.lte',
	$form_values
);

// Print admin footer
$admin->print_footer();

?>