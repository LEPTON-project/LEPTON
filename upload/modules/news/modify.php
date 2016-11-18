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

//overwrite php.ini on Apache servers for valid SESSION ID Separator
if(function_exists('ini_set')) {
	ini_set('arg_separator.output', '&amp;');
}

/**
 *	Load Language file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

/**	*******************************
 *	Try to get the template-engine.
 */
global $parser, $loader;
require_once( dirname(__FILE__)."/register_parser.php" );

/**
 *	JavaScript
 */
$js_delete_msg = (array_key_exists( 'CONFIRM_DELETE', $MOD_NEWS))
	? $MOD_NEWS['CONFIRM_DELETE']
	: $TEXT['ARE_YOU_SURE']
	;

/**
 *	Check if there is a start point defined
 */
if(isset($_GET['p']) AND is_numeric($_GET['p']) AND $_GET['p'] >= 0) {
	$position = $_GET['p'];
} else {
	$position = 0;
}

/**
 *	Get settings
 */
$fetch_settings = array();
$query_settings = $database->execute_query(
	"SELECT `posts_per_page` FROM `".TABLE_PREFIX."mod_news_settings` WHERE `section_id` = '".$section_id."'",
	true,
	$fetch_settings,
	false
);

$setting_posts_per_page = isset($fetch_settings['posts_per_page'])
	? $fetch_settings['posts_per_page']
	: ''
	;

/**
 *	Timebased activation or deactivation of the news posts.
 *	Keep in mind that the database class will return an object-instance each time a query.
 *
 */
$t = time();
$database->execute_query("UPDATE `".TABLE_PREFIX."mod_news_posts` SET `active`= '0' WHERE (`published_until` > '0') AND (`published_until` <= '".$t."')");
$database->execute_query("UPDATE `".TABLE_PREFIX."mod_news_posts` SET `active`= '1' WHERE (`published_when` > '0') AND (`published_when` <= '".$t."') AND (`published_until` > '0') AND (`published_until` >= '".$t."')");

/**
 *	Get total number of posts
 */
$result = array();
$database->execute_query(
	"SELECT count(`post_id`) FROM `".TABLE_PREFIX."mod_news_posts` WHERE `section_id` = '".$section_id."'",
	true,
	$result,
	false
);
$total_num = $result["count(`post_id`)"];

/**
 *	Work-out if we need to add limit code to sql
 */
$limit_sql = ($setting_posts_per_page != 0)
	? " LIMIT ".$position.",".$setting_posts_per_page
	: ""
	;
	
/**
 *	Query posts (for this page)
 */
$all_posts = array();
$database->execute_query(
	"SELECT * FROM ".TABLE_PREFIX."mod_news_posts WHERE section_id = '$section_id' ORDER BY position DESC".$limit_sql,
	true,
	$all_posts	
);
$num_posts = count($all_posts); // $query_posts->numRows();
	
/**
 *	Create previous and next links
 */
if($setting_posts_per_page != 0) {
	/**
	 *	Patch, as the JS-redirect makes it nessesary to look for botth "leptoken" AND "amp;leptoken"
	 *
	 */
	if (array_key_exists('amp;leptoken', $_GET) ) $_GET['leptoken'] = $_GET['amp;leptoken'];
	$leptoken_add = (isset($_GET['leptoken']) ? "&amp;leptoken=".$_GET['leptoken'] : "");
	if (strlen( $leptoken_add) == 0) {
		if (isset($_POST['leptoken']) ) $leptoken_add =  "&amp;leptoken=".$_POST['leptoken'];
	}
	if($position > 0) {
		$pl_prepend = '<a href="?p='.($position-$setting_posts_per_page).'&amp;page_id='.$page_id.$leptoken_add.'">&lt;&lt; ';
		$pl_append = '</a>';
		$previous_link = $pl_prepend.$TEXT['PREVIOUS'].$pl_append;
		$previous_page_link = $pl_prepend.$TEXT['PREVIOUS_PAGE'].$pl_append;
	} else {
		$previous_link = '';
		$previous_page_link = '';
	}
	if($position+$setting_posts_per_page >= $total_num) {
		$next_link = '';
		$next_page_link = '';
	} else {
		$nl_prepend = '<a href="?p='.($position+$setting_posts_per_page).'&amp;page_id='.$page_id.$leptoken_add.'"> ';
		$nl_append = ' &gt;&gt;</a>';
		$next_link = $nl_prepend.$TEXT['NEXT'].$nl_append;
		$next_page_link = $nl_prepend.$TEXT['NEXT_PAGE'].$nl_append;
	}
	if($position+$setting_posts_per_page > $total_num) {
		$num_of = $position+$num_posts;
	} else {
		$num_of = $position+$setting_posts_per_page;
	}
	$out_of = ($position+1).'-'.$num_of.' '.strtolower($TEXT['OUT_OF']).' '.$total_num;
	$of = ($position+1).'-'.$num_of.' '.strtolower($TEXT['OF']).' '.$total_num;
	$display_previous_next_links = '';
} else {
	$display_previous_next_links = 'none';
}

// Groups
$all_groups = array();
$query_groups = $database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."mod_news_groups` WHERE section_id = '".$section_id."' ORDER BY position ASC",
	true,
	$all_groups
);
$num_groups = count($all_groups);

// get all group-titles ...
$group_titles = array( '0' => $TEXT['NONE'] );
foreach($all_groups as &$ref) {
	$group_titles[ $ref['group_id'] ] = $ref['title'];
}

/**
 *	Counting the comments
 *
 */
$counted_comments = array( '0' => 0 );
$post_ids = array();
foreach($all_posts as &$ref){
	$counted_comments[ $ref['post_id'] ] = 0;
	$post_ids[] = $ref['post_id'];
}

// Patch to avoid unexpected behavior if there is no post_id avaible - News are empty.
if (count($post_ids) == 0) $post_ids[] = -99;

$all_comments = array();
$database->execute_query(
	"SELECT `post_id` FROM `".TABLE_PREFIX."mod_news_comments` WHERE `post_id` in (".implode(",", $post_ids).")",
	true,
	$all_comments
);
foreach($all_comments as &$ref) $counted_comments[ $ref['post_id'] ]++;

/**
 *	Get the correct 'icon'
 */
$t = time();
foreach($all_posts as &$ref) {
	$start = $ref['published_when'];
	$end = $ref['published_until'];
	$icon = '';
	if($start<=$t && $end==0)
		$icon=THEME_URL.'/images/noclock_16.png';
	elseif(($start<=$t || $start==0) && $end>=$t)
		$icon=THEME_URL.'/images/clock_16.png';
	else
		$icon=THEME_URL.'/images/clock_red_16.png';

	$ref['icon'] = $icon;
}

$js_delete_msg = (array_key_exists( 'CONFIRM_DELETE', $MOD_NEWS))
	? $MOD_NEWS['CONFIRM_DELETE']
	: $TEXT['ARE_YOU_SURE']
	;
	
foreach($all_posts as &$ref) {
	$ref['js_delete_msg'] = sprintf($js_delete_msg, $ref['title']);
}

$form_values = array(
	'TEXT'			=> $TEXT,
	'LEPTON_URL'	=> LEPTON_URL,
	'THEME_URL'		=> THEME_URL,
	'page_id'		=> $page_id,
	'section_id'	=> $section_id,
	'num_posts'		=> $num_posts,
	'posts'			=> $all_posts,
	'counted_comments' => $counted_comments,
	'display_previous_next_links' => $display_previous_next_links,
		'NEXT_PAGE_LINK'		=> isset($next_page_link) ? $next_page_link : "",
		'PREVIOUS_PAGE_LINK'	=> isset($previous_page_link) ? $previous_page_link : "",
		'OF'					=> isset($of) ? $of : "",
	'num_groups'	=> $num_groups,
	'groups'		=> $all_groups,
	'group_titles'	=> $group_titles,
	'row'	=> 'a'
);

$twig_util->resolve_path("modify.lte");

echo $parser->render(
	$twig_modul_namespace.'modify.lte',
	$form_values
);

?>