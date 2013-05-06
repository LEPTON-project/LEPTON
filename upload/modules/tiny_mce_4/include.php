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
function show_wysiwyg_editor( $name, $id, $content, $width="100%", $height="250px", $prompt=true) {
	global $id_list;
	global $database;
	global $parser;		// twig parser
	global $loader;		// twig file manager
	
	if (!is_object($parser)) require_once( LEPTON_PATH."/modules/lib_twig/library.php" );

	// prependpath to make sure twig is looking in this module template folder first
	$loader->prependPath( dirname(__FILE__)."/templates/" );
	
	/**	*****
	 *	1. tinyMCE main script part
	 *
	 */

	/**
	 *	make sure that the script-part is only load/generated ones
	 *
	 */
	if (!defined("tiny_mce_loaded")) {
		
		define("tiny_mce_loaded", true);
		
		/**
		 *	Find out how many wysiwyg sections we've got and whoat section id's we
		 *	need to collect
		 */
		if (!isset($id_list)) $id_list = array('short','long');
		
		if (!in_array($name, $id_list)) {
			$id_list = array($name);
		}
		if (is_array($id_list) and (count($id_list)>0)) { // get all sections we want ... in page...
		  foreach ($id_list as &$ref) $ref = "#".$ref;
		  $elements = implode(',',$id_list);
		} 
		else { 
			/**
			 *	Try to get all wysiwyg sections... on the page...
			 *	Keep in Mind that there could be also a wysiwyg inside an admin-tool!
			 */
			$elements = "";
			if (isset($page_id)) {
				$qs = $database->query("SELECT section_id FROM ".TABLE_PREFIX."sections WHERE page_id = '$page_id' AND module = 'wysiwyg' ORDER BY position ASC");
				if ($qs->numRows() > 0) {
					while($sw = $qs->fetchRow( MYSQL_ASSOC )) {
						$elements .= 'textarea#content'.$sw['section_id'].',';
					}
					$elements = substr($elements,0,-1);
				}
			}
		}
		
		$tiny_mce_url = LEPTON_URL."/modules/tiny_mce_4/tiny_mce";
		
		$temp_css_path = "editor.css";
		$template_name = get_template_name( $temp_css_path );
			
		/**
		 *	work out default CSS file to be used for TINY_MCE textareas
		 *	if editor.css file exists in default template folder or template folder of current page
		 */
		$css_file = ($template_name == "none")
			?	$tiny_mce_url .'/skins/lightgray/content.min.css'
			:	LEPTON_URL .'/templates/' .$template_name .$temp_css_path;



//	include language file
$language = (dirname(__FILE__))."/tiny_mce/langs/". LANGUAGE .".js";
      
	
		/**
		 *	Try to get wysiwyg-admin informations for this editor.
		 *
		 */
		$toolbar = "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage";
		
		$strip = TABLE_PREFIX;
		$all_tables= $database->list_tables( $strip  );
		if (in_array("mod_wysiwyg_admin", $all_tables)) {
			
			require_once( dirname(__FILE__)."/register_wysiwyg_admin.php" );

			$editor = new c_editor();

			$editor->get_info( 
				$database,
				$width,
				$height,
				$toolbar
			);
		}
		
		$data = array(
			'tiny_mce_url'	=> $tiny_mce_url,
			'elements'		=> $elements,
			'language'		=> $language,      
			'width'		=> $width,
			'height'	=> $height,
			'css_file'	=> $css_file,
			'toolbar'	=> $toolbar
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