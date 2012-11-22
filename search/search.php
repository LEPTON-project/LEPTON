<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010, Website Baker Project
 * @copyright       2010-2011, LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @version         $Id: search.php 1172 2011-10-04 15:26:26Z frankh $
 *
 */


// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
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

global $TEXT;
global $MESSAGE;
global $database;
global $wb;
global $admin;
 	
// Check if search is enabled
if (SHOW_SEARCH != true) {
	echo $TEXT ['SEARCH'] . ' ' . $TEXT ['DISABLED'];
	return;
}

// Include the WB functions file
require_once (WB_PATH . '/framework/functions.php');
// search-module-extension: get helper-functions
require_once (WB_PATH . '/search/search_modext.php');

// Get search settings
$table = TABLE_PREFIX . 'search';
$query = $database->query ( "SELECT value FROM $table WHERE name = 'header' LIMIT 1" );
$fetch_header = $query->fetchRow ( MYSQL_ASSOC );
$query = $database->query ( "SELECT value FROM $table WHERE name = 'footer' LIMIT 1" );
$fetch_footer = $query->fetchRow ( MYSQL_ASSOC );
$query = $database->query ( "SELECT value FROM $table WHERE name = 'results_header' LIMIT 1" );
$fetch_results_header = $query->fetchRow ( MYSQL_ASSOC );
$query = $database->query ( "SELECT value FROM $table WHERE name = 'results_footer' LIMIT 1" );
$fetch_results_footer = $query->fetchRow ( MYSQL_ASSOC );
$query = $database->query ( "SELECT value FROM $table WHERE name = 'results_loop' LIMIT 1" );
$fetch_results_loop = $query->fetchRow ( MYSQL_ASSOC );
$query = $database->query ( "SELECT value FROM $table WHERE name = 'no_results' LIMIT 1" );
$fetch_no_results = $query->fetchRow ( MYSQL_ASSOC );
$query = $database->query ( "SELECT value FROM $table WHERE name = 'module_order' LIMIT 1" );
if ($query->numRows () > 0) {
	$res = $query->fetchRow ( MYSQL_ASSOC );
} else {
	$res ['value'] = 'faqbaker,manual,wysiwyg';
}
$search_module_order = $res ['value'];
$query = $database->query ( "SELECT value FROM $table WHERE name = 'max_excerpt' LIMIT 1" );
if ($query->numRows () > 0) {
	$res = $query->fetchRow ( MYSQL_ASSOC );
} else {
	$res ['value'] = '15';
}
$search_max_excerpt = ( int ) ($res ['value']);
if (! is_numeric ( $search_max_excerpt )) {
	$search_max_excerpt = 15;
}
$query = $database->query ( "SELECT value FROM $table WHERE name = 'cfg_show_description' LIMIT 1" );
if ($query->numRows () > 0) {
	$res = $query->fetchRow ( MYSQL_ASSOC );
} else {
	$res ['value'] = 'true';
}
if ($res ['value'] == 'false') {
	$cfg_show_description = false;
} else {
	$cfg_show_description = true;
}
$query = $database->query ( "SELECT value FROM $table WHERE name = 'cfg_search_description' LIMIT 1" );
if ($query->numRows () > 0) {
	$res = $query->fetchRow ( MYSQL_ASSOC );
} else {
	$res ['value'] = 'true';
}
if ($res ['value'] == 'false') {
	$cfg_search_description = false;
} else {
	$cfg_search_description = true;
}
$query = $database->query ( "SELECT value FROM $table WHERE name = 'cfg_search_keywords' LIMIT 1" );
if ($query->numRows () > 0) {
	$res = $query->fetchRow ( MYSQL_ASSOC );
} else {
	$res ['value'] = 'true';
}
if ($res ['value'] == 'false') {
	$cfg_search_keywords = false;
} else {
	$cfg_search_keywords = true;
}

// check, if the old search function is enabled
$query = $database->query ( "SELECT value FROM $table WHERE name = 'cfg_enable_old_search' LIMIT 1" );
($query->numRows () > 0) ? $res = $query->fetchRow ( MYSQL_ASSOC ) : $res ['value'] = 'false'; // default is FALSE!
if ($res ['value'] == 'true') {
	// the old search function is no longer supported, so prompt a deprecated notice
	$prompt = sprintf($MESSAGE['SYSTEM_SETTING_NO_LONGER_SUPPORTED'], 'cfg_enable_old_search');
	trigger_error($prompt, E_USER_NOTICE);
}

$query = $database->query ( "SELECT value FROM $table WHERE name = 'cfg_search_non_public_content' LIMIT 1" );
($query->numRows () > 0) ? $res = $query->fetchRow () : $res ['value'] = 'false'; // default is FALSE!
$cfg_search_non_public_content = ($res['value'] == 'true') ? true : false;

$cfg_link_non_public_content = '';
if ($cfg_search_non_public_content) {
	$query = $database->query ( "SELECT value FROM $table WHERE name = 'cfg_link_non_public_content' LIMIT 1" );
	($query->numRows () > 0) ? $res = $query->fetchRow ( MYSQL_ASSOC ) : $res ['value'] = ''; 
	$cfg_link_non_public_content = $res['value']; 
}

$query = $database->query ( "SELECT value FROM $table WHERE name = 'cfg_enable_flush' LIMIT 1" );
if ($query->numRows () > 0) {
	$res = $query->fetchRow ( MYSQL_ASSOC );
} else {
	$res ['value'] = 'false';
}
if ($res ['value'] == 'false') {
	$cfg_enable_flush = false;
} else {
	$cfg_enable_flush = true;
}
$query = $database->query ( "SELECT value FROM $table WHERE name = 'time_limit' LIMIT 1" ); // time-limit per module
if ($query->numRows () > 0) {
	$res = $query->fetchRow ( MYSQL_ASSOC );
} else {
	$res ['value'] = '0';
}
$search_time_limit = ( int ) ($res ['value']);
if ($search_time_limit < 1)
	$search_time_limit = 0;
	
// search-module-extension: Get "search.php" for each module, if present
// looks in modules/module/ and modules/module_searchext/
$search_funcs = array ();
$search_funcs ['__before'] = array ();
$search_funcs ['__after'] = array ();
$query = $database->query ( "SELECT DISTINCT directory FROM " . TABLE_PREFIX . "addons WHERE type = 'module' AND directory NOT LIKE '%_searchext'" );
if ($query->numRows () > 0) {
	while ( false !== ($module = $query->fetchRow ( MYSQL_ASSOC )) ) {
		$file = WB_PATH . '/modules/' . $module ['directory'] . '/search.php';
		if (! file_exists ( $file )) {
			$file = WB_PATH . '/modules/' . $module ['directory'] . '_searchext/search.php';
			if (! file_exists ( $file )) {
				$file = '';
			}
		}
		if ($file != '') {
			include_once ($file);
			if (function_exists ( $module ['directory'] . "_search" )) {
				$search_funcs [$module ['directory']] = $module ['directory'] . "_search";
			}
			if (function_exists ( $module ['directory'] . "_search_before" )) {
				$search_funcs ['__before'] [] = $module ['directory'] . "_search_before";
			}
			if (function_exists ( $module ['directory'] . "_search_after" )) {
				$search_funcs ['__after'] [] = $module ['directory'] . "_search_after";
			}
		}
	}
}

// Get list of usernames and display names
$query = $database->query ( "SELECT user_id,username,display_name FROM " . TABLE_PREFIX . "users" );
$users = array ('0' => array ('display_name' => $TEXT ['UNKNOWN'], 'username' => strtolower ( $TEXT ['UNKNOWN'] ) ) );
if ($query->numRows () > 0) {
	while (false !== ( $user = $query->fetchRow( MYSQL_ASSOC ) )) {
		$users [$user ['user_id']] = array ('display_name' => $user ['display_name'], 'username' => $user ['username'] );
	}
}

// Get search language, used for special umlaut handling (DE: ÃŸ=ss, ...)
$search_lang = '';
if (isset ( $_REQUEST ['search_lang'] )) {
	$search_lang = $_REQUEST ['search_lang'];
	if (! preg_match ( '~^[A-Z]{2}$~', $search_lang ))
		$search_lang = LANGUAGE;
} else {
	$search_lang = LANGUAGE;
}

// Get the path to search into. Normally left blank
// ATTN: since wb2.7.1 the path is evaluated as SQL: LIKE "/path%" - which will find "/path.php", "/path/info.php", ...; But not "/de/path.php"
// Add a '%' in front of each path to get SQL: LIKE "%/path%"
/* possible values:
 * - a single path: "/en/" - search only pages whose link contains 'path' ("/en/machinery/bender-x09")
 * - a single path not to search into: "-/help" - search all, exclude /help...
 * - a bunch of alternative pathes: "/en/,%/machinery/,/docs/" - alternatives paths, seperated by comma
 * - a bunch of paths to exclude: "-/about,%/info,/jp/,/light" - search all, exclude these.
 * These different styles can't be mixed.
 */
// ATTN: in wb2.7.0 "/en/" matched all links with "/en/" somewhere in the link: "/info/en/intro.php", "/en/info.php", ...
// since wb2.7.1 "/en/" matches only links _starting_  with "/en/": "/en/intro/info.php"
// use "%/en/" (or "%/en/, %/info", ...) to get the old behavior
$search_path_SQL = '';
$search_path = '';
if (isset ( $_REQUEST ['search_path'] )) {
	$search_path = addslashes ( htmlspecialchars ( strip_tags ( $wb->strip_slashes ( $_REQUEST ['search_path'] ) ), ENT_QUOTES ) );
	if (! preg_match ( '~^%?[-a-zA-Z0-9_,/ ]+$~', $search_path ))
		$search_path = '';
	if ($search_path != '') {
		$search_path_SQL = 'AND ( ';
		$not = '';
		$op = 'OR';
		if ($search_path [0] == '-') {
			$not = 'NOT';
			$op = 'AND';
			$paths = explode ( ',', substr ( $search_path, 1 ) );
		} else {
			$paths = explode ( ',', $search_path );
		}
		$i = 0;
		foreach ( $paths as $p ) {
			if ($i ++ > 0) {
				$search_path_SQL .= ' $op';
			}
			$search_path_SQL .= " link $not LIKE '" . $p . "%'";
		}
		$search_path_SQL .= ' )';
	}
}

// use page_languages?
if (PAGE_LANGUAGES) {
	$table = TABLE_PREFIX . "pages";
	$search_language_SQL_t = "AND $table.`language` = '" . LANGUAGE . "'";
	$search_language_SQL = "AND `language` = '" . LANGUAGE . "'";
} else {
	$search_language_SQL_t = '';
	$search_language_SQL = '';
}

// Get the search type
$match = '';
if (isset ( $_REQUEST ['match'] )) {
	if ($_REQUEST ['match'] == 'any')
		$match = 'any';
	elseif ($_REQUEST ['match'] == 'all')
		$match = 'all';
	elseif ($_REQUEST ['match'] == 'exact')
		$match = 'exact';
	else
		$match = 'all';
} else {
	$match = 'all';
}

// Get search string
$search_normal_string = '';
$search_entities_string = ''; // for SQL's LIKE
$search_display_string = ''; // for displaying
$search_url_string = ''; // for $_GET -- ATTN: unquoted! Will become urldecoded later
$string = '';
if (isset ( $_REQUEST ['string'] )) {
	if ($match != 'exact') // $string will be cleaned below 
{
		$string = str_replace ( ',', '', $_REQUEST ['string'] );
	} else {
		$string = $_REQUEST ['string'];
	}
	// redo possible magic quotes
	$string = $wb->strip_slashes ( $string );
	$string = preg_replace ( '/[ \r\n\t]+/', ' ', $string );
	$string = trim ( $string );
	// remove some bad chars
	$string = str_replace ( array ('[[', ']]' ), '', $string );
	$string = preg_replace ( '/(^|\s+)[|.]+(?=\s+|$)/', '', $string );
	$search_display_string = htmlspecialchars ( $string );
	$search_entities_string = addslashes ( umlauts_to_entities ( htmlspecialchars ( $string ) ) );
	// mySQL needs four backslashes to match one in LIKE comparisons)
	$search_entities_string = str_replace ( '\\\\', '\\\\\\\\', $search_entities_string );
	// convert string to utf-8
	$string = entities_to_umlauts ( $string, 'UTF-8' );
	$search_url_string = $string;
 	$search_entities_string = addslashes(htmlentities($string, ENT_COMPAT, 'UTF-8'));
	// mySQL needs four backslashes to match one in LIKE comparisons)
	$search_entities_string = str_replace('\\\\', '\\\\\\\\', $search_entities_string);
	$string = preg_quote ( $string );
	// quote ' " and /  -we need quoted / for regex
	$search_normal_string = str_replace ( array ('\'', '"', '/' ), array ('\\\'', '\"', '\/' ), $string );
}
// make arrays from the search_..._strings above
if ($match == 'exact')
	$search_url_array [] = $search_url_string;
else
	$search_url_array = explode ( ' ', $search_url_string );
$search_normal_array = array ();
$search_entities_array = array ();
if ($match == 'exact') {
	$search_normal_array [] = $search_normal_string;
	$search_entities_array [] = $search_entities_string;
} else {
	$exploded_string = explode ( ' ', $search_normal_string );
	// Make sure there is no blank values in the array
	foreach ( $exploded_string as $each_exploded_string ) {
		if ($each_exploded_string != '') {
			$search_normal_array [] = $each_exploded_string;
		}
	}
	$exploded_string = explode ( ' ', $search_entities_string );
	// Make sure there is no blank values in the array
	foreach ( $exploded_string as $each_exploded_string ) {
		if ($each_exploded_string != '') {
			$search_entities_array [] = $each_exploded_string;
		}
	}
}

// make an extra copy of search_normal_array for use in regex
$search_words = array();
require_once(WB_PATH.'/search/search_convert.php');
global $search_table_umlauts_local;
require_once(WB_PATH.'/search/search_convert_ul.php');
global $search_table_ul_umlauts;
foreach($search_normal_array AS $str) {
  if ( is_array( $search_table_umlauts_local ) ) {
	  $str = strtr($str, $search_table_umlauts_local);
  }
  if ( is_array( $search_table_ul_umlauts ) ) {
	  $str = strtr($str, $search_table_ul_umlauts);
  }
	$search_words[] = $str;
}

// Work-out what to do (match all words, any words, or do exact match), and do relevant with query settings
$all_checked = '';
$any_checked = '';
$exact_checked = '';
if ($match == 'any') {
	$any_checked = ' checked="checked"';
	$logical_operator = ' OR';
} elseif ($match == 'all') {
	$all_checked = ' checked="checked"';
	$logical_operator = ' AND';
} else {
	$exact_checked = ' checked="checked"';
}

// Replace vars in search settings with values
$vars = array ('[SEARCH_STRING]', '[WB_URL]', '[PAGE_EXTENSION]', '[TEXT_RESULTS_FOR]' );
$values = array ($search_display_string, WB_URL, PAGE_EXTENSION, $TEXT ['RESULTS_FOR'] );
$search_footer = str_replace ( $vars, $values, ($fetch_footer ['value']) );
$search_results_header = str_replace ( $vars, $values, ($fetch_results_header ['value']) );
$search_results_footer = str_replace ( $vars, $values, ($fetch_results_footer ['value']) );

// Do extra vars/values replacement
$vars = array ('[SEARCH_STRING]', '[WB_URL]', '[PAGE_EXTENSION]', '[TEXT_SEARCH]', '[TEXT_ALL_WORDS]', '[TEXT_ANY_WORDS]', '[TEXT_EXACT_MATCH]', '[TEXT_MATCH]', '[TEXT_MATCHING]', '[ALL_CHECKED]', '[ANY_CHECKED]', '[EXACT_CHECKED]', '[REFERRER_ID]', '[SEARCH_PATH]' );
$values = array ($search_display_string, WB_URL, PAGE_EXTENSION, $TEXT ['SEARCH'], $TEXT ['ALL_WORDS'], $TEXT ['ANY_WORDS'], $TEXT ['EXACT_MATCH'], $TEXT ['MATCH'], $TEXT ['MATCHING'], $all_checked, $any_checked, $exact_checked, REFERRER_ID, $search_path );
$search_header = str_replace ( $vars, $values, ($fetch_header ['value']) );
$vars = array ('[TEXT_NO_RESULTS]' );
$values = array ($TEXT ['NO_RESULTS'] );
$search_no_results = str_replace ( $vars, $values, ($fetch_no_results ['value']) );

/*
 * Start of output
 */

// Show search header
echo $search_header;
// Show search results_header
echo $search_results_header;

// Work-out if the user has already entered their details or not
if ($search_normal_string != '') {
	
	// Get modules
	$table = TABLE_PREFIX . "sections";
	$get_modules = $database->query ( "SELECT DISTINCT module FROM $table WHERE module != '' " );
	$modules = array ();
	if ($get_modules->numRows () > 0) {
		while (false !== ($module = $get_modules->fetchRow ( MYSQL_ASSOC )) ) {
			$modules [] = $module ['module'];
		}
	}
	// sort module search-order
	// get the modules from $search_module_order first ...
	$sorted_modules = array ();
	$m = count ( $modules );
	$search_modules = explode ( ',', $search_module_order );
	foreach ( $search_modules as $item ) {
		$item = trim ( $item );
		for($i = 0; $i < $m; $i ++) {
			if (isset ( $modules [$i] ) && $modules [$i] == $item) {
				$sorted_modules [] = $modules [$i];
				unset ( $modules [$i] );
				break;
			}
		}
	}
	// ... then add the rest
	foreach ( $modules as $item ) {
		$sorted_modules [] = $item;
	}
	
	// Use the module's search-extensions.
	// This is somewhat slower than the orginial method.
	

	// call $search_funcs['__before'] first
	$search_func_vars = array ('database' => $database, // database-handle
'page_id' => 0, 'section_id' => 0, 'page_title' => '', 'page_menu_title' => '', 'page_description' => '', 'page_keywords' => '', 'page_link' => '', 'page_modified_when' => 0, 'page_modified_by' => 0, 'users' => $users, // array of known user-id/user-name
'search_words' => $search_words, // array of strings, prepared for regex
'search_match' => $match, // match-type
'search_url_array' => $search_url_array, // array of strings from the original search-string. ATTN: strings are not quoted!
'search_entities_array' => $search_entities_array, // entities
'results_loop_string' => $fetch_results_loop ['value'], 'default_max_excerpt' => $search_max_excerpt, 'time_limit' => $search_time_limit, // time-limit in secs
'search_path' => $search_path )// see docu
;
	foreach ( $search_funcs ['__before'] as $func ) {
		$uf_res = call_user_func ( $func, $search_func_vars );
	}
	// now call module-based $search_funcs[]
	$seen_pages = array (); // seen pages per module.
	$pages_listed = array (); // seen pages.
	if ($search_max_excerpt != 0) { 
		// skip this search if $search_max_excerpt==0
		foreach ( $sorted_modules as $module_name ) {
			$start_time = time (); // get start-time to check time-limit; not very accurate, but ok
			$seen_pages [$module_name] = array ();
			if (! isset ( $search_funcs [$module_name] )) {
				continue; // there is no search_func for this module
			}
			// get each section for $module_name
			$table_s = TABLE_PREFIX . "sections";
			$table_p = TABLE_PREFIX . "pages";
			$sections_query = $database->query ( "
				SELECT s.section_id, s.page_id, s.module, s.publ_start, s.publ_end,
							 p.page_title, p.menu_title, p.link, p.description, p.keywords, p.modified_when, p.modified_by,
							 p.visibility, p.viewing_groups, p.viewing_users
				FROM $table_s AS s INNER JOIN $table_p AS p ON s.page_id = p.page_id
				WHERE s.module = '$module_name' AND p.visibility NOT IN ('none','deleted') AND p.searching = '1' $search_path_SQL $search_language_SQL
				ORDER BY s.page_id, s.position ASC
			" );
			if ($sections_query->numRows () > 0) {
				while ( false !== ($res = $sections_query->fetchRow ( MYSQL_ASSOC )) ) {
					// check if time-limit is exceeded for this module
					if ($search_time_limit > 0 && (time () - $start_time > $search_time_limit)) {
						break;
					}
					// Only show this section if it is not "out of publication-date"
					$now = time ();
					if (! ($now < $res ['publ_end'] && ($now > $res ['publ_start'] || $res ['publ_start'] == 0) || $now > $res ['publ_start'] && $res ['publ_end'] == 0)) {
						continue;
					}
					$search_func_vars = array ('database' => $database, 'page_id' => $res ['page_id'], 'section_id' => $res ['section_id'], 'page_title' => $res ['page_title'], 'page_menu_title' => $res ['menu_title'], 'page_description' => ($cfg_show_description ? $res ['description'] : ""), 'page_keywords' => $res ['keywords'], 'page_link' => $res ['link'], 'page_modified_when' => $res ['modified_when'], 'page_modified_by' => $res ['modified_by'], 'users' => $users, 'search_words' => $search_words, // needed for preg_match
'search_match' => $match, 'search_url_array' => $search_url_array, // needed for url-string only
'search_entities_array' => $search_entities_array, // entities
'results_loop_string' => $fetch_results_loop ['value'], 'default_max_excerpt' => $search_max_excerpt, 'enable_flush' => $cfg_enable_flush, 'time_limit' => $search_time_limit )// time-limit in secs
;
					// Only show this page if we are allowed to see it
					if ($admin->page_is_visible ( $res ) == false) {
						if ($res ['visibility'] == 'registered') {
							if (!$cfg_search_non_public_content) { 
								// don't show excerpt
								$search_func_vars ['default_max_excerpt'] = 0;
								$search_func_vars ['page_description'] = $TEXT ['REGISTERED_CONTENT'];
							}
							else {
								// show non public content so set $_SESSIONs for print_excerpt2()
								$_SESSION['search_non_public_content'] = true;
								$_SESSION['link_non_public_content'] = $cfg_link_non_public_content;
							}
						} else { // private
							continue;
						}
					}
					$uf_res = call_user_func ( $search_funcs [$module_name], $search_func_vars );
					if ($uf_res) {
						$pages_listed [$res ['page_id']] = true;
						$seen_pages [$module_name] [$res ['page_id']] = true;
					} else {
						$seen_pages [$module_name] [$res ['page_id']] = true;
					}
				}
			}
		}
	}
	// now call $search_funcs['__after']
	$search_func_vars = array ('database' => $database, // database-handle
'page_id' => 0, 'section_id' => 0, 'page_title' => '', 'page_menu_title' => '', 'page_description' => '', 'page_keywords' => '', 'page_link' => '', 'page_modified_when' => 0, 'page_modified_by' => 0, 'users' => $users, // array of known user-id/user-name
'search_words' => $search_words, // array of strings, prepared for regex
'search_match' => $match, // match-type
'search_url_array' => $search_url_array, // array of strings from the original search-string. ATTN: strings are not quoted!
'search_entities_array' => $search_entities_array, // entities
'results_loop_string' => $fetch_results_loop ['value'], 'default_max_excerpt' => $search_max_excerpt, 'time_limit' => $search_time_limit, // time-limit in secs
'search_path' => $search_path )// see docu
;
	foreach ( $search_funcs ['__after'] as $func ) {
		$uf_res = call_user_func ( $func, $search_func_vars );
	}
	
	// Search page details only, such as description, keywords, etc, but only of unseen pages.
	$max_excerpt_num = 0; // we don't want excerpt here
	$divider = ".";
	$table = TABLE_PREFIX . "pages";
	$query_pages = $database->query ( "
		SELECT page_id, page_title, menu_title, link, description, keywords, modified_when, modified_by,
		       visibility, viewing_groups, viewing_users
		FROM $table
		WHERE visibility NOT IN ('none','deleted') AND searching = '1' $search_path_SQL $search_language_SQL
	" );
	if ($query_pages->numRows () > 0) {
		while ( false !== ($page = $query_pages->fetchRow ( MYSQL_ASSOC )) ) {
			if (isset ( $pages_listed [$page ['page_id']] )) {
				continue;
			}
			$func_vars = array ('database' => $database, 'page_id' => $page ['page_id'], 'page_title' => $page ['page_title'], 'page_menu_title' => $page ['menu_title'], 'page_description' => ($cfg_show_description ? $page ['description'] : ""), 'page_keywords' => $page ['keywords'], 'page_link' => $page ['link'], 'page_modified_when' => $page ['modified_when'], 'page_modified_by' => $page ['modified_by'], 'users' => $users, 'search_words' => $search_words, // needed for preg_match_all
'search_match' => $match, 'search_url_array' => $search_url_array, // needed for url-string only
'search_entities_array' => $search_entities_array, // entities
'results_loop_string' => $fetch_results_loop ['value'], 'default_max_excerpt' => $max_excerpt_num, 'enable_flush' => $cfg_enable_flush );
			// Only show this page if we are allowed to see it
			if ($admin->page_is_visible ( $page ) == false) {
				if ($page ['visibility'] != 'registered') {
					continue;
				} elseif ($cfg_search_non_public_content) { 
					// show non public content so set $_SESSIONs for print_excerpt2()
					$_SESSION['search_non_public_content'] = true;
					$_SESSION['link_non_public_content'] = $cfg_link_non_public_content;
				}
				else {
					// page: registered, user: access denied
					$func_vars ['page_description'] = $TEXT ['REGISTERED_CONTENT'];
				}
			}
			if ($admin->page_is_active ( $page ) == false) {
				continue;
			}
			$text = $func_vars ['page_title'] . $divider . $func_vars ['page_menu_title'] . $divider . ($cfg_search_description ? $func_vars ['page_description'] : "") . $divider . ($cfg_search_keywords ? $func_vars ['page_keywords'] : "") . $divider;
			$mod_vars = array ('page_link' => $func_vars ['page_link'], 'page_link_target' => "", 'page_title' => $func_vars ['page_title'], 'page_description' => $func_vars ['page_description'], 'page_modified_when' => $func_vars ['page_modified_when'], 'page_modified_by' => $func_vars ['page_modified_by'], 'text' => $text, 'max_excerpt_num' => $func_vars ['default_max_excerpt'] );
			if (print_excerpt2 ( $mod_vars, $func_vars )) {
				$pages_listed [$page ['page_id']] = true;
			}
		}
	}
	
	// Now use the old method for pages not displayed by the new method above
	// in case someone has old modules without search.php.
	

	// Get modules
	$table_search = TABLE_PREFIX . "search";
	$table_sections = TABLE_PREFIX . "sections";
	$get_modules = $database->query ( "
		SELECT DISTINCT s.value, s.extra
		FROM $table_search AS s INNER JOIN $table_sections AS sec
			ON s.value = sec.module
		WHERE s.name = 'module'
	" );
	$modules = array ();
	if ($get_modules->numRows () > 0) {
		while ( false !== ($module = $get_modules->fetchRow ( MYSQL_ASSOC ) ) ) {
			$modules [] = $module; // $modules in an array of arrays
		}
	}
	// sort module search-order
	// get the modules from $search_module_order first ...
	$sorted_modules = array ();
	$m = count ( $modules );
	$search_modules = explode ( ',', $search_module_order );
	foreach ( $search_modules as $item ) {
		$item = trim ( $item );
		for($i = 0; $i < $m; $i ++) {
			if (isset ( $modules [$i] ) && $modules [$i] ['value'] == $item) {
				$sorted_modules [] = $modules [$i];
				unset ( $modules [$i] );
				break;
			}
		}
	}
	// ... then add the rest
	foreach ( $modules as $item ) {
		$sorted_modules [] = $item;
	}
	
	// Say no items found if we should
	if (count ( $pages_listed ) == 0) {
		echo $search_no_results;
	}
} else {
	echo $search_no_results;
}

// Show search results_footer
echo $search_results_footer;
// Show search footer
echo $search_footer;

?>