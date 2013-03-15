<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author		  Website Baker Project, LEPTON Project
 * @copyright	   2004-2010 Website Baker Project
 * @copyright	   2010-2013 LEPTON Project
 * @link			http://www.LEPTON-cms.org
 * @license		 http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
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

// references to objects and variables that changed their names

$admin = &$wb;

$default_link=&$wb->default_link;

$page_trail=&$wb->page_trail;
$page_description=&$wb->page_description;
$page_keywords=&$wb->page_keywords;
$page_link=&$wb->link;

$include_head_link_css = '';
$include_body_links = '';
$include_head_links = '';
// workout to included frontend.css, fronten.js and frontend_body.js in snippets
$query="SELECT directory FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND function = 'snippet'";
$query_result=$database->query($query);
if ($query_result->numRows()>0) {
	while ($row = $query_result->fetchRow( MYSQL_ASSOC )) {
		$module_dir = $row['directory'];
		if (file_exists(WB_PATH.'/modules/'.$module_dir.'/include.php')) {
			include(WB_PATH.'/modules/'.$module_dir.'/include.php');
			/* check if frontend.css file needs to be included into the <head></head> of index.php
			*/
			if( file_exists(WB_PATH .'/modules/'.$module_dir.'/frontend.css')) {
				$include_head_link_css .= '<link href="'.WB_URL.'/modules/'.$module_dir.'/frontend.css"';
				$include_head_link_css .= ' rel="stylesheet" type="text/css" media="screen" />'."\n";
				$include_head_file = 'frontend.css';
			}
			// check if frontend.js file needs to be included into the <body></body> of index.php
			if(file_exists(WB_PATH .'/modules/'.$module_dir.'/frontend.js')) {
				$include_head_links .= '<script src="'.WB_URL.'/modules/'.$module_dir.'/frontend.js" type="text/javascript"></script>'."\n";
				$include_head_file = 'frontend.js';
			}
			// check if frontend_body.js file needs to be included into the <body></body> of index.php
			if(file_exists(WB_PATH .'/modules/'.$module_dir.'/frontend_body.js')) {
				$include_body_links .= '<script src="'.WB_URL.'/modules/'.$module_dir.'/frontend_body.js" type="text/javascript"></script>'."\n";
				$include_body_file = 'frontend_body.js';
			}
		}
	}
}

// Frontend functions
if (!function_exists('page_link'))
{
	function page_link($link) {
		global $wb;
		return $wb->page_link($link);
	}
}

if (!function_exists('get_page_link'))
{
	function get_page_link( $id )
	{
		global $database;
		// Get link
		$sql = 'SELECT `link` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$id;
		$link = $database->get_one( $sql );
		return $link;
	}
}

//function to highlight search results
if(!function_exists('search_highlight')) {
	function search_highlight($foo='', $arr_string=array()) {
		require_once(WB_PATH.'/framework/functions.php');
		static $string_ul_umlaut = FALSE;
		static $string_ul_regex = FALSE;
		if($string_ul_umlaut===FALSE || $string_ul_regex===FALSE)
			require(WB_PATH.'/search/search_convert.php');
		$foo = entities_to_umlauts($foo, 'UTF-8');
		array_walk($arr_string, create_function('&$v,$k','$v = preg_quote($v, \'~\');'));
		$search_string = implode("|", $arr_string);
		$string = str_replace($string_ul_umlaut, $string_ul_regex, $search_string);
		// the highlighting
		// match $string, but not inside <style>...</style>, <script>...</script>, <!--...--> or HTML-Tags
		// Also droplet tags are now excluded from highlighting.
		// split $string into pieces - "cut away" styles, scripts, comments, HTML-tags and eMail-addresses
		// we have to cut <pre> and <code> as well.
		// for HTML-Tags use <(?:[^<]|<.*>)*> which will match strings like <input ... value="<b>value</b>" >
		$matches = preg_split("~(\[\[.*\]\]|<style.*</style>|<script.*</script>|<pre.*</pre>|<code.*</code>|<!--.*-->|<(?:[^<]|<.*>)*>|\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,8}\b)~iUs",$foo,-1,(PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY));
		if(is_array($matches) && $matches != array()) {
			$foo = "";
			foreach($matches as $match) {
				if($match{0}!="<" && !preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,8}$/i', $match) && !preg_match('~\[\[.*\]\]~', $match)) {
					$match = str_replace(array('&lt;', '&gt;', '&amp;', '&quot;', '&#039;', '&nbsp;'), array('<', '>', '&', '"', '\'', "\xC2\xA0"), $match);
					$match = preg_replace('~('.$string.')~ui', '_span class=_highlight__$1_/span_',$match);
					$match = str_replace(array('&', '<', '>', '"', '\'', "\xC2\xA0"), array('&amp;', '&lt;', '&gt;', '&quot;', '&#039;', '&nbsp;'), $match);
					$match = str_replace(array('_span class=_highlight__', '_/span_'), array('<span class="highlight">', '</span>'), $match);
				}
				$foo .= $match;
			}
		}
	
		if(DEFAULT_CHARSET != 'utf-8') {
			$foo = umlauts_to_entities($foo, 'UTF-8');
		}
		return $foo;
	}
}

if (!function_exists('page_content')) {
	function page_content($block = 1) {
		// Get outside objects
		global $TEXT,$MENU,$HEADING,$MESSAGE;
		global $globals;
		global $database;
		global $wb;
		$admin = & $wb;
		if ($wb->page_access_denied==true)
		{
			echo $MESSAGE['FRONTEND_SORRY_NO_VIEWING_PERMISSIONS'];
			return;
		}
		if ($wb->page_no_active_sections==true)
		{
			echo $MESSAGE['FRONTEND_SORRY_NO_ACTIVE_SECTIONS'];
			return;
		}
		if(isset($globals) AND is_array($globals))
		{
			foreach($globals AS $global_name)
			{
				global $$global_name;
				}
		}
		// Make sure block is numeric
		if(!is_numeric($block)) { $block = 1; }
		// Include page content
		if(!defined('PAGE_CONTENT') OR $block!=1)
		{
			$page_id = intval($wb->page_id);
			// set session variable to save page_id only if PAGE_CONTENT is empty
			$_SESSION['PAGE_ID'] = !isset($_SESSION['PAGE_ID']) ? $page_id : $_SESSION['PAGE_ID'];
			// set to new value if page_id changed and not 0
			if(($page_id != 0) && ($_SESSION['PAGE_ID'] <> $page_id))
			{
			$_SESSION['PAGE_ID'] = $page_id;
			}

			// First get all sections for this page
			$query_sections = $database->query("SELECT section_id,module,publ_start,publ_end FROM ".TABLE_PREFIX."sections WHERE page_id = '".$page_id."' AND block = '$block' ORDER BY position");
			// If none were found, check if default content is supposed to be shown
			if($query_sections->numRows() == 0) {
				if ($wb->default_block_content=='none') {
					return;
				}
				if (is_numeric($wb->default_block_content)) {
					$page_id=$wb->default_block_content;
				} else {
					$page_id=$wb->default_page_id;
				}				
				$query_sections = $database->query("SELECT section_id,module,publ_start,publ_end FROM ".TABLE_PREFIX."sections WHERE page_id = '".$page_id."' AND block = '$block' ORDER BY position");
				// Still no cotent found? Give it up, there's just nothing to show!
				if($query_sections->numRows() == 0) {
					return;
				}
			}
			// Loop through them and include their module file
			while($section = $query_sections->fetchRow( MYSQL_ASSOC )) {
				// skip this section if it is out of publication-date
				$now = time();
				if( !(($now<=$section['publ_end'] || $section['publ_end']==0) && ($now>=$section['publ_start'] || $section['publ_start']==0)) ) {
					continue;
				}
				$section_id = $section['section_id'];
				$module = $section['module'];
				// make a anchor for every section.
				if(defined('SEC_ANCHOR') && SEC_ANCHOR!='') {
					echo '<a class="section_anchor" id="'.SEC_ANCHOR.$section_id.'"></a>';
				}
		 // check if module exists - feature: write in errorlog
				if(file_exists(WB_PATH.'/modules/'.$module.'/view.php')) {
				// fetch content -- this is where to place possible output-filters (before highlighting)
					ob_start(); // fetch original content
					require(WB_PATH.'/modules/'.$module.'/view.php');
					$content = ob_get_contents();
					ob_end_clean();
				} else {
					continue;
				}

				// highlights searchresults
				if(isset($_GET['searchresult']) && is_numeric($_GET['searchresult']) && !isset($_GET['nohighlight']) && isset($_GET['sstring']) && !empty($_GET['sstring'])) {
					$arr_string = explode(" ", $_GET['sstring']);
					if($_GET['searchresult']==2) { // exact match
						$arr_string[0] = str_replace("_", " ", $arr_string[0]);
					}
					echo search_highlight($content, $arr_string);
				} else {
					echo $content;
				}
			}
		}
		else
		{
			require(PAGE_CONTENT);
		}
	}
}

if (!function_exists('show_content')) {
	function show_content($block=1) {
		page_content($block);
	}
}

/**
 *	Function to get or display the current page title
 *
 *	@param	string	Spacer between the items; default is "-"
 *	@param	string	The template-string itself
 *	@param	boolean	The return-mode: 'true' will return the value, false will direct echo the string
 *
 */
if (!function_exists('page_title')) {
	function page_title( $spacer = ' - ', $template = '[WEBSITE_TITLE][SPACER][PAGE_TITLE]', $mode=false ) {
		$vars = array('[WEBSITE_TITLE]', '[PAGE_TITLE]', '[MENU_TITLE]', '[SPACER]');
		$values = array(WEBSITE_TITLE, PAGE_TITLE, MENU_TITLE, $spacer);
		$temp = str_replace($vars, $values, $template);
		if ( true === $mode ) {
			return $temp;
		} else {
			echo $temp;
			return true;
		}
	}
}

/**
 *	Function to get the current page description
 *
 *	@param	bool	false == direct echo of the page-description
 *					true == return the page-description
 *
 */
if (!function_exists('page_description')) {
	function page_description( $mode = false ) {
		global $wb;
		$temp = ($wb->page_description!='') ? $wb->page_description : WEBSITE_DESCRIPTION;
		if ( true === $mode ) {
			return $temp;
		} else {
			echo $temp;
			return true;
		}
	}
}

/**
 *	Function to get the page keywords
 *
 *	@param	bool	mode: true for returning the value, false for direct echo
 *
 */
if (!function_exists('page_keywords')) {
	function page_keywords( $mode = false ) {
		global $wb;
		$temp = ($wb->page_keywords!='') ? $wb->page_keywords : WEBSITE_KEYWORDS;
		if ( true === $mode ) {
			return $temp;
		} else {
			echo $temp;
			return true;
		}
	}
}

/**
 *	Function to get the page header
 *
 *	@param	bool	Return-Mode: true returns the value, false will direct output the string.
 *
 */
if (!function_exists('page_header')) {
	function page_header( $mode = false ) {
		if ( true === $mode ) {
			return WEBSITE_HEADER;
		} else {
			echo WEBSITE_HEADER;
			return true;
		}
	}
}

/**
 *	Function to get the page footer
 *
 *	@param	string	A date-format for the processtime.
 *	@param	bool	Return-mode: true returns the value, false will direct output the string.
 *
 */
if (!function_exists('page_footer')) {
	function page_footer( $date_format = 'Y', $mode = false) {
		global $starttime;
		$vars = array('[YEAR]', '[PROCESS_TIME]');
		$processtime=array_sum(explode(" ",microtime()))-$starttime;
		$values = array(date($date_format),$processtime);	
		$temp = str_replace($vars, $values, WEBSITE_FOOTER);
		if ( true === $mode ) {
			return $temp;
		} else {
			echo $temp;
			return true;
		}
	}
}

function bind_jquery ($file_id='jquery')
{

	$jquery_links = '';
	/* include the Javascript jquery api  */
	if( $file_id == 'jquery' AND file_exists(WB_PATH .'/modules/lib_jquery/jquery-core/jquery-core.min.js'))
	{
		$wbpath = str_replace('\\','/',WB_PATH);  // fixed localhost problem with ie
		$jquery_links .= "<script type=\"text/javascript\">\n"
			."var URL = '".WB_URL."';\n"
		   /* ."var WB_PATH = '".$wbpath."';\n" */
			."var WB_URL = '".WB_URL."';\n"
			."var TEMPLATE_DIR = '".TEMPLATE_DIR."';\n"
			."</script>\n";

		$jquery_links .= '<script src="'.WB_URL.'/modules/lib_jquery/jquery-core/jquery-core.min.js" type="text/javascript"></script>'."\n";
		$jquery_frontend_file = TEMPLATE_DIR.'/jquery_frontend.js';
		$jquery_links .= file_exists(str_replace( WB_URL, WB_PATH, $jquery_frontend_file))
			? '<script src="'.$jquery_frontend_file.'" type="text/javascript"></script>'."\n"
			: '';
	}
	return $jquery_links;
}


// Function to add optional module Javascript into the <body> section of the frontend
if(!function_exists('register_frontend_modfiles_body'))
{
	function register_frontend_modfiles_body($file_id="js")
	{
		// sanity check of parameter passed to the function
		$file_id = strtolower($file_id);
		if($file_id !== "css" && $file_id !== "javascript" && $file_id !== "js" && $file_id !== "jquery")
		{
			return;
		}

	   // define constant indicating that the register_frontent_files was invoked
	   if(!defined('MOD_FRONTEND_BODY_JAVASCRIPT_REGISTERED')) define('MOD_FRONTEND_BODY_JAVASCRIPT_REGISTERED', true);
		global $wb, $database, $include_body_links;
		// define default baselink and filename for optional module javascript files
		$body_links = "";

		/* include the Javascript jquery api  */
		$body_links .= bind_jquery($file_id);

		if($file_id !== "css" && $file_id == "js" && $file_id !== "jquery")
		{
			$base_link = '<script src="'.WB_URL.'/modules/{MODULE_DIRECTORY}/frontend_body.js" type="text/javascript"></script>';
			$base_file = "frontend_body.js";

			// ensure that frontend_body.js is only added once per module type
			if(!empty($include_body_links))
			{
				if(strpos($body_links, $include_body_links) === false)
				{
					$body_links .= $include_body_links;
				}
				$include_body_links = '';
			}

			// gather information for all models embedded on actual page
			$page_id = $wb->page_id;
			$query_modules = $database->query("SELECT module FROM " .TABLE_PREFIX ."sections
					WHERE page_id=$page_id AND module<>'wysiwyg'");

			while($row = $query_modules->fetchRow())
			{
				// check if page module directory contains a frontend_body.js file
				if(file_exists(WB_PATH ."/modules/" .$row['module'] ."/$base_file"))
				{
				// create link with frontend_body.js source for the current module
					$tmp_link = str_replace("{MODULE_DIRECTORY}", $row['module'], $base_link);

					// define constant indicating that the register_frontent_files_body was invoked
						if(!defined('MOD_FRONTEND_BODY_JAVASCRIPT_REGISTERED')) { define('MOD_FRONTEND_BODY_JAVASCRIPT_REGISTERED', true);}

					// ensure that frontend_body.js is only added once per module type
					if(strpos($body_links, $tmp_link) === false)
					{
						$body_links .= $tmp_link;
					}
				}
			}
		}

		print $body_links."\n"; ;
	}
}


// Function to add optional module Javascript or CSS stylesheets into the <head> section of the frontend
if(!function_exists('register_frontend_modfiles'))
{
	function register_frontend_modfiles($file_id="css")
	{
		// sanity check of parameter passed to the function
		$file_id = strtolower($file_id);
		if($file_id !== "css" && $file_id !== "javascript" && $file_id !== "js" && $file_id !== "jquery")
		{
			return;
		}

		global $wb, $database, $include_head_link_css, $include_head_links;
		// define default baselink and filename for optional module javascript and stylesheet files
		$head_links = "";

		switch ($file_id)
		{
			case 'css':
			$base_link = '<link href="'.WB_URL.'/modules/{MODULE_DIRECTORY}/frontend.css"';
			$base_link.= ' rel="stylesheet" type="text/css" media="screen" />';
			$base_file = "frontend.css";
			if(!empty($include_head_link_css))
			{
			  $head_links .=  !strpos($head_links, $include_head_link_css) ? $include_head_link_css : '';
			  $include_head_link_css = '';
			}
			break;
			case 'jquery':
			$head_links .= bind_jquery($file_id);
			break;
			case 'js':
			$base_link = '<script src="'.WB_URL.'/modules/{MODULE_DIRECTORY}/frontend.js" type="text/javascript"></script>';
			$base_file = "frontend.js";
			if(!empty($include_head_links))
			{
			  $head_links .= !strpos($head_links, $include_head_links) ? $include_head_links : '';
			  $include_head_links = '';
			}
			break;
			default:
			break;
		}

		if( $file_id != 'jquery')
		{
			// gather information for all models embedded on actual page
			$page_id = $wb->page_id;
			$query_modules = $database->query("SELECT module FROM " .TABLE_PREFIX ."sections
					WHERE page_id=$page_id AND module<>'wysiwyg'");

			while($row = $query_modules->fetchRow())
			{
				// check if page module directory contains a frontend.js or frontend.css file
				if(file_exists(WB_PATH ."/modules/" .$row['module'] ."/$base_file"))
				{
				// create link with frontend.js or frontend.css source for the current module
					$tmp_link = str_replace("{MODULE_DIRECTORY}", $row['module'], $base_link);

					// define constant indicating that the register_frontent_files was invoked
					if($file_id == 'css')
					{
						if(!defined('MOD_FRONTEND_CSS_REGISTERED')) define('MOD_FRONTEND_CSS_REGISTERED', true);
					} else
					{
						if(!defined('MOD_FRONTEND_JAVASCRIPT_REGISTERED')) define('MOD_FRONTEND_JAVASCRIPT_REGISTERED', true);
					}
					// ensure that frontend.js or frontend.css is only added once per module type
					if(strpos($head_links, $tmp_link) === false)
					{
						$head_links .= $tmp_link."\n";
					}
				};
			}
				// include the Javascript email protection function
				if( $file_id != 'css' && file_exists(WB_PATH .'/modules/droplets/js/mdcr.js'))
				{
					$head_links .= '<script src="'.WB_URL.'/modules/droplets/js/mdcr.js" type="text/javascript"></script>'."\n";
				}
				elseif( $file_id != 'css' && file_exists(WB_PATH .'/modules/output_filter/js/mdcr.js'))
				{
					$head_links .= '<script src="'.WB_URL.'/modules/output_filter/js/mdcr.js" type="text/javascript"></script>'."\n";
				}
		}
		print $head_links;
	}
}
?>