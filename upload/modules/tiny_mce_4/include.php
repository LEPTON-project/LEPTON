<?php

/**
 *  @module         TinyMCE-4-jQ
 *  @version        see info.php of this module
 *  @authors        erpe, Dietrich Roland Pehlke (Aldus)
 *  @copyright      2012-2013 erpe, Dietrich Roland Pehlke (Aldus)
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 *  Please note: TINYMCE is distibuted under the <a href="http://tinymce.moxiecode.com/license.php">(LGPL) License</a> 
 *  Ajax Filemanager is distributed under the <a href="http://www.gnu.org/licenses/gpl.html)">GPL </a> and <a href="http://www.mozilla.org/MPL/MPL-1.1.html">MPL</a> open source licenses 
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

global $id_list;
global $database;

unset($_SESSION['TINY_MCE_INIT']);

/**
 * Decode HTML Special chars
 * 
 * @param STR $mixed
 * @return STR
 * @deprecated - why not use the standard function "htmlspecialchars_decode()"? 
 */
function reverse_htmlentities($mixed) {
	$mixed = str_replace(array('&gt;','&lt;','&quot;','&amp;'), array('>','<','"','&'), $mixed);
	return $mixed;
}

/**
 *	returns the template name of the current displayed page
 * 
 *	@param string	A path to the editor.css - if there is one. Default is an empty string. Pass by reference!
 *	@return STR $tiny_template_dir
 */
function get_template_name( &$css_path = "") {
	global $database;
	
	$lookup_paths = array(
		'/css/editor.css',
		'/editor.css'
	);
	
	$tiny_template_dir = "none";

	/**
	 *	Looking up for an editor.css file for TinyMCE
	 *
	 */
	foreach($lookup_paths as $temp_path) {
		if (file_exists(LEPTON_PATH .'/templates/' .DEFAULT_TEMPLATE .$temp_path ) ) {
			$css_path = $temp_path; // keep in mind, that this one is pass_by_reference
			$tiny_template_dir = DEFAULT_TEMPLATE;
			break;
		}
	}
		
	// check if a editor.css file exists in the specified template directory of current page
	if (isset($_GET["page_id"]) && ((int) $_GET["page_id"] > 0)) {
		$pageid = (int) $_GET["page_id"];
		// obtain template folder of current page from the database
		$query_page = "SELECT `template` FROM `" .TABLE_PREFIX ."pages` WHERE `page_id`='".$pageid."'";
		$pagetpl = $database->get_one($query_page);
		
		/**
		 *	check if a specific template is defined for current page
		 *
		 */
		if (isset($pagetpl) && ($pagetpl != '')) {	
			/**
			 *	check if a specify editor.css file is contained in that folder
			 *
			 */
			foreach($lookup_paths as $temp_path) {
				if (file_exists(LEPTON_PATH.'/templates/'.$pagetpl.$temp_path)) {
					$css_path = $temp_path; // keep in mind, that this one is pass_by_reference
					$tiny_template_dir = $pagetpl;
					break;
				}
			}
		}
	}
	return $tiny_template_dir;
} // get_template_name()


/**
 * Initialize Tiny MCE and create an textarea
 * 
 * @param STR $name		Name of the textarea.
 * @param STR $id		Id of the textarea.
 * @param STR $content	The content to edit.
 * @param INT $width	The width of the editor, not overwritten by wysiwyg-admin.
 * @param INT $height	The height of the editor, not overwritten by wysiwyg-admin.
 * @param BOOL $prompt	Direct output to the client via echo (true) or returnd as HTML-textarea (false)?
 * @return MIXED		Could be a BOOL or STR (textarea-tags).
 *
 */
function show_wysiwyg_editor($name, $id, $content, $width, $height, $prompt=true) {
	global $id_list;
	global $database;
	global $parser;		# 1 twig parser
	global $loader;		# 2 twig file manager
	
	if (!is_object($parser)) require_once( LEPTON_PATH."/modules/lib_twig/library.php" );

	// prependpath to make sure twig is looking in this module template folder first
	$loader->prependPath( dirname(__FILE__)."/templates/" );
	
	/**	*****
	 *	1. tinyMCE main script part
	 *
	 */
	$tiny_mce_url = LEPTON_URL."/modules/tiny_mce_4/tiny_mce";
	
	$temp_css_path = "editor.css";
	$template_name = get_template_name( $temp_css_path );
		
	/**
	 *	work out default CSS file to be used for TINY textarea
	 *	no editor.css file exists in default template folder, or template folder of current page
	 *	editor.css file exists in default template folder or template folder of current page
	 */
	$css_file = ($template_name == "none")
		?	$tiny_mce_url .'/themes/skins/lightgray/content.min.css'
		:	WB_URL .'/templates/' .$template_name .$temp_css_path;

	$data = array(
		'tiny_mce_url'	=> $tiny_mce_url,
		'id'		=> $id,
		'width'		=> $width,
		'height'	=> $height,
		'css_file'	=> $css_file
	);
	
	echo $parser->render( 
		"tiny_mce.lte",	//	template-filename
		$data			//	template-data
	);
	
	/**	*****
	 *	2. textarea part
	 *
	 */
	 	
	//	values for the textarea
	$data = array(
		'id'		=> $id,
		'name'		=> $name,
		'content'	=> reverse_htmlentities( $content ),
		'width'		=> $width,
		'height'	=> $height
	);

	$result = $parser->render(
		'textarea.lte',	// template-filename
		$data			// template-data
	);
	
	if ($prompt) {
		echo $result;
		return true;
	}
	return $result;

} // show_wysiwyg_editor()

?>