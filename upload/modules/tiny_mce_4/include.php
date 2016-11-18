<?php

/**
 *  @module         TinyMCE-4-jQ
 *  @version        see info.php of this module
 *  @authors        erpe, Dietrich Roland Pehlke (Aldus)
 *  @copyright      2012-2017 erpe, Dietrich Roland Pehlke (Aldus)
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 *  Please note: TINYMCE is distibuted under the <a href="http://tinymce.moxiecode.com/license.php">(LGPL) License</a> 
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
 * @param INT $width	The width of the editor, overwritten by wysiwyg-admin.
 * @param INT $height	The height of the editor, overwritten by wysiwyg-admin.
 * @param BOOL $prompt	Direct output to the client via echo (true) or returnd as HTML-textarea (false)?
 * @return MIXED		Could be a BOOL or STR (textarea-tags).
 *
 */
function show_wysiwyg_editor( $name, $id, $content, $width=NULL, $height=NULL, $prompt=true) {
	global $id_list;
	global $database;
	global $parser;		// twig parser
	global $loader;		// twig file manager
	
	/**
	 *	0.1	Get Twig
	 */
	if (!is_object($parser)) require_once( LEPTON_PATH."/modules/lib_twig/library.php" );

	// prependpath to make sure twig is looking in this module template folder first
	$loader->prependPath( dirname(__FILE__)."/templates/" );
	
	/**
	 *	0.2 Get the "defaults" from the editorinfo.php
	 *
	 */
	require_once( dirname(__FILE__)."/class.editorinfo.php" );
	$oTinyMCE_info = new editorinfo_TINY_MCE_4();
	
	$toolbar = $oTinyMCE_info->toolbars[ $oTinyMCE_info->default_toolbar ];
	$skin = $oTinyMCE_info->default_skin;

	if( $width === NULL ) $width = $oTinyMCE_info->default_width;
	if( $height === NULL ) $height = $oTinyMCE_info->default_height;
		
	/**	*****
	 *	1. tinyMCE main script part
	 *
	 */

	/**
	 *	make sure that the script-part for the tinyMCE is only load/generated once
	 *
	 */
	if (!defined("tiny_mce_loaded")) {
		
		define("tiny_mce_loaded", true);

		$tiny_mce_url = LEPTON_URL."/modules/tiny_mce_4/tiny_mce";
		
		$temp_css_path = "editor.css";
		$template_name = get_template_name( $temp_css_path );
			
		/**
		 *	Work out default CSS file to be used for TINY_MCE textareas.
		 *	If editor.css file exists in default template folder or template folder of current page
		 */
		$css_file = ($template_name == "none")
			?	$tiny_mce_url .'/skins/'.$skin.'/content.min.css'
			:	LEPTON_URL .'/templates/' .$template_name .$temp_css_path;

		/**
		 * See: http://www.tinymce.com/wiki.php/Configuration:content_css
		 *
		 */
		$temp_css_file = "/modules/tiny_mce_4/tiny_mce/skins/skin.custom.css";
		if (file_exists(LEPTON_PATH.$temp_css_file)) $css_file = "['".$css_file."','".LEPTON_URL.$temp_css_file."']";
		
		/**
		 *	Include language file
		 *	If the file is not found (local) we use an empty string,
		 *	TinyMCE will use english as the defaut language in this case.
		 */
		$lang = strtolower( LANGUAGE );
		$language = (file_exists( dirname(__FILE__)."/tiny_mce/langs/". $lang .".js" )) ? $lang	: "";
    
		/**
		 *	Get wysiwyg-admin information for this editor.
		 *
		 */
		$strip = TABLE_PREFIX;
		$all_tables= $database->list_tables( $strip  );
		if (in_array("mod_wysiwyg_admin", $all_tables)) {
			$wysiwyg_admin_editor_settings = array();
			$database->execute_query(
				"SELECT `skin`, `menu`,`width`,`height` from `".TABLE_PREFIX."mod_wysiwyg_admin` where `editor` ='tiny_mce_4'",
				true,
				$wysiwyg_admin_editor_settings,
				false
			);
			if (count($wysiwyg_admin_editor_settings) > 0) {
				$width = $wysiwyg_admin_editor_settings['width'];
				$height = $wysiwyg_admin_editor_settings['height'];
				$skin = $wysiwyg_admin_editor_settings['skin'];
				$toolbar = $oTinyMCE_info->toolbars[ $wysiwyg_admin_editor_settings['menu'] ];
			}
		}

// define filemanager url and access keys
$filemanager_url = LEPTON_URL."/modules/lib_r_filemanager";
$akey = password_hash( LEPTON_GUID, PASSWORD_DEFAULT);
$akey = str_replace(array('$','/'),'',$akey);
$akey = substr($akey, -30);	
$_SESSION['rfkey'] = $akey;
		
		$data = array(
			'filemanager_url'=> $filemanager_url,
			'LEPTON_URL'	=> LEPTON_URL,
			'ACCESS_KEY'	=> $akey,			
			'tiny_mce_url'	=> $tiny_mce_url,			
			'selector'		=> 'textarea[id!=no_wysiwyg]',
			'language'		=> $language,      
			'width'		=> $width,
			'height'	=> $height,
			'css_file'	=> $css_file,
			'toolbar'	=> $toolbar,
			'skin'		=> $skin
		);
		
		echo $parser->render( 
			"tiny_mce.lte",	//	template-filename
			$data			//	template-data
		);
	}
	
	/**	*****
	 *	2. textarea part
	 *
	 */
	 	
	//	values for the textarea
	$data = array(
		'id'		=> $id,
		'name'		=> $name,
		'content'	=> htmlspecialchars_decode( $content ),
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