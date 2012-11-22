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
 * @version         $Id: search_modext.php 1172 2011-10-04 15:26:26Z frankh $
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



/**
 * Create the URL String for highlighting the matches
 * @param STR $search_match
 * @param ARRAY $search_url_array
 * @return STR
 */
function make_url_searchstring($search_match, $search_url_array) {
	$link = "";
	if ($search_match != 'exact') {
		$str = implode ( " ", $search_url_array );
		$link = "?searchresult=1&amp;sstring=" . urlencode ( $str );
	} else {
		$str = str_replace ( ' ', '_', $search_url_array [0] );
		$link = "?searchresult=2&amp;sstring=" . urlencode ( $str );
	}
	return $link;
} // make_url_searchstring()


/**
 * Create date and time for "last modified by..."
 * @param INT $page_modified_when
 * @return ARRAY
 */
function get_page_modified($page_modified_when) {
	global $TEXT;
	if ($page_modified_when > 0) {
		$date = date ( DATE_FORMAT, $page_modified_when );
		$time = date ( TIME_FORMAT, $page_modified_when );
	} else {
		$date = $TEXT ['UNKNOWN'] . ' ' . $TEXT ['DATE'];
		$time = $TEXT ['UNKNOWN'] . ' ' . $TEXT ['TIME'];
	}
	return array ($date, $time );
} // get_page_modified()

/**
 * Return an array containing user- and displayname for "last modified by..."
 * @param INT $page_modified_by
 * @param ARRAY $users
 * @return ARRAY ($username, $displayname)
 */
function get_page_modified_by($page_modified_by, $users) {
	global $TEXT;
	// check for existing user-id
	if (! isset ( $users [$page_modified_by] )) {
		$page_modified_by = 0;
	}
	$username = $users [$page_modified_by] ['username'];
	$displayname = $users [$page_modified_by] ['display_name'];
	return array ($username, $displayname );
} // get_page_modified_by()

/**
 * Check if really all searchwords are matching
 * @param STR $text
 * @param ARRAY $search_words
 * @return BOOL
 */
function is_all_matched($text, $search_words) {
	$all_matched = true;
	foreach ( $search_words as $word ) {
		if (! preg_match ( '/' . $word . '/ui', $text )) {
			$all_matched = false;
			break;
		}
	}
	return $all_matched;
} // is_all_matched()

/**
 * Check if any of the searchwords are matching
 * @param STR $text
 * @param ARRAY $search_words
 * @return BOOL
 */
function is_any_matched($text, $search_words) {
	$any_matched = false;
	$word = '(' . implode ( '|', $search_words ) . ')';
	if (preg_match ( '/' . $word . '/ui', $text )) {
		$any_matched = true;
	}
	return $any_matched;
} // is_any_matched()

/**
 * Collect the matches from the TEXT and return an $excerpt_array
 * @param STR $text
 * @param ARRAY $search_words
 * @param INT $max_excerpt_num
 * @return ARRAY $excerpt_array
 */
function get_excerpts($text, $search_words, $max_excerpt_num) {
	$match_array = array ();
	$excerpt_array = array ();
	$word = '(' . implode ( '|', $search_words ) . ')';
	
	//Filter droplets from the page data
	preg_match_all ( '~\[\[(.*?)\]\]~', $text, $matches );
	foreach ( $matches [1] as $match ) {
		$text = str_replace ( '[[' . $match . ']]', '', $text );
	}
	
	// Build the regex-string
	if (strpos ( strtoupper ( PHP_OS ), 'WIN' ) === 0) {
		// Windows OS
		$str1 = ".!?;";
		$str2 = ".!?;";
	} else { 
		// Linux and other *nix OS
		// start-sign: .!?; + INVERTED EXCLAMATION MARK - INVERTED QUESTION MARK - DOUBLE EXCLAMATION MARK - INTERROBANG - EXCLAMATION QUESTION MARK - QUESTION EXCLAMATION MARK - DOUBLE QUESTION MARK - HALFWIDTH IDEOGRAPHIC FULL STOP - IDEOGRAPHIC FULL STOP - IDEOGRAPHIC COMMA
		$str1 = ".!?;" . "\xC2\xA1" . "\xC2\xBF" . "\xE2\x80\xBC" . "\xE2\x80\xBD" . "\xE2\x81\x89" . "\xE2\x81\x88" . "\xE2\x81\x87" . "\xEF\xBD\xA1" . "\xE3\x80\x82" . "\xE3\x80\x81";
		// stop-sign: .!?; + DOUBLE EXCLAMATION MARK - INTERROBANG - EXCLAMATION QUESTION MARK - QUESTION EXCLAMATION MARK - DOUBLE QUESTION MARK - HALFWIDTH IDEOGRAPHIC FULL STOP - IDEOGRAPHIC FULL STOP - IDEOGRAPHIC COMMA
		$str2 = ".!?;" . "\xE2\x80\xBC" . "\xE2\x80\xBD" . "\xE2\x81\x89" . "\xE2\x81\x88" . "\xE2\x81\x87" . "\xEF\xBD\xA1" . "\xE3\x80\x82" . "\xE3\x80\x81";
	}
	$regex = '/(?:^|\b|[' . $str1 . '])([^' . $str1 . ']{0,200}?' . $word . '[^' . $str2 . ']{0,200}(?:[' . $str2 . ']|\b|$))/uis';
	
	// this may crash windows server, so skip if on windows
	if (version_compare ( PHP_VERSION, '4.3.3', '>=' ) && strpos ( strtoupper ( PHP_OS ), 'WIN' ) !== 0) { 
		// jump from match to match, get excerpt, stop if $max_excerpt_num is reached
		$last_end = 0;
		$offset = 0;
		while ( preg_match ( '/' . $word . '/uis', $text, $match_array, PREG_OFFSET_CAPTURE, $last_end ) ) {
			$offset = ($match_array [0] [1] - 206 < $last_end) ? $last_end : $match_array [0] [1] - 206;
			if (preg_match ( $regex, $text, $matches, PREG_OFFSET_CAPTURE, $offset )) {
				$last_end = $matches [1] [1] + strlen ( $matches [1] [0] ) - 1;
				
				// skip excerpts with email-addresses
				if (! preg_match ( '/\b[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\./', $matches [1] [0] )) {
					$excerpt_array [] = trim ( $matches [1] [0] );
				}
				if (count ( $excerpt_array ) >= $max_excerpt_num) {
					$excerpt_array = array_unique ( $excerpt_array );
					if (count ( $excerpt_array ) >= $max_excerpt_num) {
						break;
					}
				}
			} else { 
				// problem: preg_match failed - can't find a start- or stop-sign
				$last_end += 201; // jump forward and try again
			}
		}
	} else { 
		// compatible, but may be very slow with large pages
		if (preg_match_all ( $regex, $text, $match_array )) {
			foreach ( $match_array [1] as $string ) {
				// skip excerpts with email-addresses
				if (! preg_match ( '/\b[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\./', $string )) {
					$excerpt_array [] = trim ( $string );
				}
			}
		}
	}
	return $excerpt_array;
} // get_excerpts()

/**
 * Create a $excerpt String ready for output from the $excerpt_array
 * @param ARRAY $excerpt_array
 * @param ARRAY $search_words
 * @param INT $max_excerpt_num
 * @return STR $excerpt
 */
function prepare_excerpts($excerpt_array, $search_words, $max_excerpt_num) {
	// excerpts: text before and after a single excerpt, html-tag for markup
	$EXCERPT_BEFORE 		= '...&nbsp;';
	$EXCERPT_AFTER 			= '&nbsp;...<br />';
	$EXCERPT_MARKUP_START 	= '<b class="highlight">';
	$EXCERPT_MARKUP_END 	= '</b>';
	// remove duplicate matches from $excerpt_array, if any.
	$excerpt_array = array_unique ( $excerpt_array );
	// use the first $max_excerpt_num excerpts only
	if (count ( $excerpt_array ) > $max_excerpt_num) {
		$excerpt_array = array_slice ( $excerpt_array, 0, $max_excerpt_num );
	}
	// prepare search-string
	$string = "(" . implode ( "|", $search_words ) . ")";
	// we want markup on search-results page,
	// but we need some 'magic' to prevent <br />, <b>... from being highlighted
	$excerpt = '';
	foreach ( $excerpt_array as $str ) {
		$excerpt .= '#,,#' . preg_replace ( "/($string)/iu", "#,,,,#$1#,,,,,#", $str ) . '#,,,#';
	}
	$excerpt = str_replace ( array ('&', '<', '>', '"', '\'', "\xC2\xA0" ), array ('&amp;', '&lt;', '&gt;', '&quot;', '&#039;', '&nbsp;' ), $excerpt );
	$excerpt = str_replace ( array ('#,,,,#', '#,,,,,#' ), array ($EXCERPT_MARKUP_START, $EXCERPT_MARKUP_END ), $excerpt );
	$excerpt = str_replace ( array ('#,,#', '#,,,#' ), array ($EXCERPT_BEFORE, $EXCERPT_AFTER ), $excerpt );
	// prepare to write out
	if (DEFAULT_CHARSET != 'utf-8') {
		$excerpt = umlauts_to_entities ( $excerpt, 'UTF-8' );
	}
	return $excerpt;
} // prepare_excerpts()

/**
 * Work out what the link-anchor should be
 * 
 * Usage samples:
 * 1. e.g. $page_link_target=="&monthno=5&year=2007" - module-dependent target. Do nothing.
 * 2. $page_link_target=="#!wb_section_..." - the user wants the section-target, so do nothing.
 * 3. $page_link_target=="#wb_section_..." - try to find a better target, use the section-target as fallback.
 * 4. $page_link_target=="" - do nothing	 
 * @param STR $page_link_target
 * @param STR $text
 * @param ARRAY $search_words
 * @return STR $page_link_target
 */
function make_url_target($page_link_target, $text, $search_words) {
	if (version_compare ( PHP_VERSION, '4.3.3', ">=" ) && substr ( $page_link_target, 0, 12 ) == '#wb_section_') {
		$word = '(' . implode ( '|', $search_words ) . ')';
		preg_match ( '/' . $word . '/ui', $text, $match, PREG_OFFSET_CAPTURE );
		if ($match && is_array ( $match [0] )) {
			$x = $match [0] [1]; // position of first match
			// is there an anchor nearby?
			if (preg_match_all ( '/<(?:[^>]+id|\s*a[^>]+name)\s*=\s*"(.*)"/iU', substr ( $text, 0, $x ), $match, PREG_OFFSET_CAPTURE )) {
				$anchor = '';
				foreach ( $match [1] as $array ) {
					if ($array [1] > $x) {
						break;
					}
					$anchor = $array [0];
				}
				if ($anchor != '') {
					$page_link_target = '#' . $anchor;
				}
			}
		}
	} elseif (substr ( $page_link_target, 0, 13 ) == '#!wb_section_') {
		$page_link_target = '#' . substr ( $page_link_target, 2 );
	}
	
	// since wb 2.7.1 the section-anchor is configurable - SEC_ANCHOR holds the anchor name
	if (substr ( $page_link_target, 0, 12 ) == '#wb_section_') {
		if (defined ( 'SEC_ANCHOR' ) && SEC_ANCHOR != '') {
			$sec_id = substr ( $page_link_target, 12 );
			$page_link_target = '#' . SEC_ANCHOR . $sec_id;
		} else { // section-anchors are disabled
			$page_link_target = '';
		}
	}
	
	return $page_link_target;
} // make_url_target()

/**
 * Wrapper for compatibility with the old print_excerpt() function.
 * This function is no longer used and deprecated.
 * @deprecated
 * @param STR $page_link
 * @param STR $page_link_target
 * @param STR $page_title
 * @param STR $page_description
 * @param INT $page_modified_when
 * @param STR $page_modified_by
 * @param STR $text
 * @param INT $max_excerpt_num
 * @param ARRAY $func_vars
 * @param STR $pic_link
 * @return FUNC print_excerpt()
 */
function print_excerpt($page_link, $page_link_target, $page_title, $page_description, $page_modified_when, $page_modified_by, $text, $max_excerpt_num, $func_vars, $pic_link = "") {
	global $MESSAGE;
	// prompt E_USER_NOTICE OR E_USER_DEPRECATED
	$prompt = sprintf($MESSAGE['SYSTEM_FUNCTION_DEPRECATED'], 'print_excerpt()', 'print_excerpt2()');
	trigger_error($prompt, E_USER_NOTICE);
	$mod_vars = array (
		'page_link'				=> $page_link, 
		'page_link_target' 		=> $page_link_target, 
		'page_title' 			=> $page_title, 
		'page_description' 		=> $page_description, 
		'page_modified_when' 	=> $page_modified_when, 
		'page_modified_by' 		=> $page_modified_by, 
		'text' 					=> $text, 
		'max_excerpt_num' 		=> $max_excerpt_num, 
		'pic_link' 				=> $pic_link 
	);
	print_excerpt2 ( $mod_vars, $func_vars );
} // print_excerpt()



/**
 * Echo the excerpts for one section
 * @param ARRAY $mod_vars
 * @param ARRAY $func_vars
 * @return BOOL
 */
function print_excerpt2($mod_vars, $func_vars) {
	extract ( $func_vars, EXTR_PREFIX_ALL, 'func' );
	extract ( $mod_vars, EXTR_PREFIX_ALL, 'mod' );
	global $TEXT;
	// check $mod_...vars
	if (! isset ( $mod_page_link)) 			$mod_page_link = $func_page_link;
	if (! isset ( $mod_page_link_target )) 	$mod_page_link_target = '';
	if (! isset ( $mod_page_title )) 		$mod_page_title = $func_page_title;
	if (! isset ( $mod_page_description )) 	$mod_page_description = $func_page_description;
	if (! isset ( $mod_page_modified_when ))$mod_page_modified_when = $func_page_modified_when;
	if (! isset ( $mod_page_modified_by )) 	$mod_page_modified_by = $func_page_modified_by;
	if (! isset ( $mod_text )) 				$mod_text = '';
	if (! isset ( $mod_max_excerpt_num )) 	$mod_max_excerpt_num = $func_default_max_excerpt;
	if (! isset ( $mod_pic_link )) 			$mod_pic_link = ''; 
	if (! isset ( $mod_no_highlight )) 		$mod_no_highlight = false;
	// set this in db: wb_search.cfg_enable_flush [READ THE DOC BEFORE]
	if (! isset ( $func_enable_flush )) 	$func_enable_flush = false;
	if (isset ( $mod_ext_charset )) {
		$mod_ext_charset = strtolower ( $mod_ext_charset );
	} else {
		$mod_ext_charset = '';
	}
	
	$content_locked = '';
	$add_anchor = true;
	if (isset($_SESSION['search_non_public_content'])) { 
		// show non-public contents, so add some extra informations
		if (isset($_SESSION['link_non_public_content']) && !empty($_SESSION['link_non_public_content'])) {
			// link to a special page, defined in search as CFG_LINK_NON_PUBLIC_CONTENT
			$mod_page_link = WB_URL.$_SESSION['link_non_public_content'];
		}
		$src = WB_PATH.'/search/images/content-locked.gif';
		list($width, $height) = getimagesize($src);
		$src = str_replace(WB_PATH, WB_URL, $src);
		$content_locked = sprintf('<img src="%s" width="%s" height="%s" alt="%s" title="%4$s" />', $src, $width, $height, $TEXT['REGISTERED_CONTENT']);		
		// $_SESSION reset
		unset($_SESSION['search_non_public_content']);
		unset($_SESSION['link_non_public_content']);
		$add_anchor = false;
	}
	// nothing to do?
	if ($mod_text == "") return false;
	
	// no highlighting
	if ($mod_no_highlight) {
		$mod_page_link_target = "&amp;nohighlight=1" . $mod_page_link_target;
	}
	// clean the text:
	$mod_text = preg_replace ( '#<(!--.*--|style.*</style|script.*</script)>#iU', ' ', $mod_text );
	$mod_text = preg_replace ( '#<(br( /)?|dt|/dd|/?(h[1-6]|tr|table|p|li|ul|pre|code|div|hr))[^>]*>#i', '.', $mod_text );
	// $mod_text = preg_replace('/(\v\s?|\s\s)+/', ' ', $mod_text);
	$mod_text = preg_replace ( '/\s\./', '.', $mod_text );
	// data from external database may have a different charset
	if ($mod_ext_charset != '') { 
		require_once (WB_PATH . '/framework/functions-utf8.php');
		switch ($mod_ext_charset) {
			case 'latin1' :
			case 'cp1252' :
				$mod_text = charset_to_utf8 ( $mod_text, 'CP1252' );
				break;
			case 'cp1251' :
				$mod_text = charset_to_utf8 ( $mod_text, 'CP1251' );
				break;
			case 'latin2' :
				$mod_text = charset_to_utf8 ( $mod_text, 'ISO-8859-2' );
				break;
			case 'hebrew' :
				$mod_text = charset_to_utf8 ( $mod_text, 'ISO-8859-8' );
				break;
			case 'greek' :
				$mod_text = charset_to_utf8 ( $mod_text, 'ISO-8859-7' );
				break;
			case 'latin5' :
				$mod_text = charset_to_utf8 ( $mod_text, 'ISO-8859-9' );
				break;
			case 'latin7' :
				$mod_text = charset_to_utf8 ( $mod_text, 'ISO-8859-13' );
				break;
			case 'utf8' :
			default :
				$mod_text = charset_to_utf8 ( $mod_text, 'UTF-8' );
		}
	} else {
		$mod_text = entities_to_umlauts ( $mod_text, 'UTF-8' );
	}
	// make an copy containing html-tags
	$anchor_text = $mod_text; 
	$mod_text = strip_tags ( $mod_text );
	$mod_text = str_replace ( array ('&gt;', '&lt;', '&amp;', '&quot;', '&#039;', '&apos;', '&nbsp;' ), array ('>', '<', '&', '"', '\'', '\'', "\xC2\xA0" ), $mod_text );
	$mod_text = '.' . trim ( $mod_text ) . '.';
	// Do a fast scan over $mod_text first. This will speedup things a lot.
	if ($func_search_match == 'all') {
		if (! is_all_matched ( $mod_text, $func_search_words ))
			return false;
	} elseif (! is_any_matched ( $mod_text, $func_search_words )) {
		return false;
	}
	// search for an better anchor - this have to be done before strip_tags() (may fail if search-string contains <, &, amp, gt, lt, ...)
	$anchor = make_url_target ( $mod_page_link_target, $anchor_text, $func_search_words );
	
	// make the link from $mod_page_link, add anchor
	$link = "";
	$link = page_link ( $mod_page_link );
	if (strpos ( $mod_page_link, 'http:' ) === FALSE)
		$link .= make_url_searchstring ( $func_search_match, $func_search_url_array );
	
	// add anchor only if content is not locked!
	if ($add_anchor) $link .= $anchor;
	
	// now get the excerpt
	$excerpt = "";
	$excerpt_array = array ();
	if ($mod_max_excerpt_num > 0) {
		if (! $excerpt_array = get_excerpts ( $mod_text, $func_search_words, $mod_max_excerpt_num )) {
			return false;
		}
		$excerpt = prepare_excerpts ( $excerpt_array, $func_search_words, $mod_max_excerpt_num );
	}
	
	// handle thumbs - to deactivate this look in the module's search.php: $show_thumb (or maybe in the module's settings-page)
	if ($mod_pic_link != "") {
		$excerpt = '<table class="excerpt_thumb" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td width="110" valign="top"><a href="' . $link . '"><img src="' . WB_URL . '/' . MEDIA_DIRECTORY . $mod_pic_link . '" alt="" /></a></td><td>' . $excerpt . '</td></tr></tbody></table>';
	}
	
	// print-out the excerpt
	$vars = array ();
	$values = array ();
	list ( $date, $time ) = get_page_modified ( $mod_page_modified_when );
	list ( $username, $displayname ) = get_page_modified_by ( $mod_page_modified_by, $func_users );
	$vars = array ('[LINK]', '[TITLE]', '[DESCRIPTION]', '[USERNAME]', '[DISPLAY_NAME]', '[DATE]', '[TIME]', '[TEXT_LAST_UPDATED_BY]', '[TEXT_ON]', '[EXCERPT]', '[LOCK]');
	$values = array ($link, $mod_page_title, $mod_page_description, $username, $displayname, $date, $time, $TEXT ['LAST_UPDATED_BY'], $TEXT ['ON'], $excerpt, $content_locked );
	echo str_replace ( $vars, $values, $func_results_loop_string );
	// Attention: this will bypass output-filters and may break template-layout or -filters
	if ($func_enable_flush) { 
		ob_flush ();
		flush ();
	}
	return true;
} // print_excerpt2()

/**
 * List all files and directories in $dir recursive,
 * omits '.', '..', and hidden files/dirs
 * usage: list($files,$dirs) = list_files_dirs($directory);
 * @param STR $dir - path to the directory
 * @param BOOL $depth - true = read subdirectories too
 * @param ARRAY $files - for recursive usage only
 * @param ARRAY $dirs - for recursive usage only
 * @return ARRAY array($files, $dirs)
 */
function list_files_dirs($dir, $depth = true, $files = array(), $dirs = array()) {
	$dh = opendir ( $dir );
	while ( ($file = readdir ( $dh )) !== false ) {
		if ($file {0} == '.' || $file == '..') {
			continue;
		}
		if (is_dir ( $dir . '/' . $file )) {
			if ($depth) {
				$dirs [] = $dir . '/' . $file;
				list ( $files, $dirs ) = list_files_dirs ( $dir . '/' . $file, $depth, $files, $dirs );
			}
		} else {
			$files [] = $dir . '/' . $file;
		}
	}
	closedir ( $dh );
	natcasesort ( $files );
	natcasesort ( $dirs );
	return (array ($files, $dirs ));
} // list_files_dirs()

?>