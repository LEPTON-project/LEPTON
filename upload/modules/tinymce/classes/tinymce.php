<?php

/**
 *  @module         TinyMCE
 *  @version        see info.php of this module
 *  @authors        erpe, Dietrich Roland Pehlke (Aldus)
 *  @copyright      2012-2018 erpe, Dietrich Roland Pehlke (Aldus)
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 *  Please note: TINYMCE is distibuted under the <a href="http://tinymce.moxiecode.com/license.php">(LGPL) License</a> 
 *
 *
 */

class tinymce extends LEPTON_abstract {
	
	static $instance;
	public function initialize() {

	}
	/**
	 *	returns the template name of the current displayed page
	 * 
	 *	@param string	A path to the editor.css - if there is one. Default is an empty string. Pass by reference!
	 *	@return STR $tiny_template_dir
	 */
	static public function get_template_name( &$css_path = "") {
		$database = LEPTON_database::getInstance();	
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
	static public function show_wysiwyg_editor( $name, $id, $content, $width=NULL, $height=NULL, $prompt=true) {
		global $id_list;
		$database = LEPTON_database::getInstance();		
		/**
		 *	0.1	Get Twig
		 */
		$oTWIG = lib_twig_box::getInstance();
		$oTWIG->registerModule('tinymce');
		
		/**
		 *	0.2 Get the "defaults" from the editorinfo.php
		 *
		 */
		require_once( dirname(__DIR__)."/class.editorinfo.php" );
		$oTinyMCE_info = new editorinfo_TINYMCE();
		
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
		if (!defined("tinymce_loaded")) {
			
			define("tinymce_loaded", true);

			$tinymce_url = LEPTON_URL."/modules/tinymce/tinymce";
			
			$temp_css_path = "/editor.css";
			$template_name = self::get_template_name( $temp_css_path );
			
			/**
			 *	If editor.css file exists in default template folder or template folder of current page
			 *  See: http://www.tinymce.com/wiki.php/Configuration:content_css
			 */
			$css_file = '"'.LEPTON_URL .'/templates/' .$template_name .$temp_css_path.'"';
			
			if ( !file_exists (LEPTON_PATH .'/templates/' .$template_name .$temp_css_path) ) 
			{
				$css_file = "''";
			}
			
			/**
			 *	If backend.css file exists in default theme folder overwrite module backend.css
			 */
			$backend_css = LEPTON_URL.'/templates/'.DEFAULT_THEME.'/backend/tinymce/backend.css';
			if(!file_exists(LEPTON_PATH.'/templates/'.DEFAULT_THEME.'/backend/tinymce/backend.css')) {
				$backend_css = LEPTON_URL.'/modules/tinymce/css/backend.css';
			}
			 
			
			/**
			 *	Include language file
			 *	If the file is not found (local) we use an empty string,
			 *	TinyMCE will use english as the defaut language in this case.
			 */
			$lang = strtolower( LANGUAGE );
			$language = (file_exists( dirname(__FILE__)."/tinymce/langs/". $lang .".js" )) ? $lang	: "";
		
			/**
			 *	Get wysiwyg-admin information for this editor.
			 *
			 */
			$strip = TABLE_PREFIX;
			$all_tables= $database->list_tables( $strip  );
			if (in_array("mod_wysiwyg_admin", $all_tables)) {
				$wysiwyg_admin_editor_settings = array();
				$database->execute_query(
					"SELECT `skin`, `menu`,`width`,`height` from `".TABLE_PREFIX."mod_wysiwyg_admin` where `editor` ='tinymce'",
					true,
					$wysiwyg_admin_editor_settings,
					false
				);
				if (count($wysiwyg_admin_editor_settings) > 0) {
					$width = $wysiwyg_admin_editor_settings['width'];
					$height = $wysiwyg_admin_editor_settings['height'];
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
				'tinymce_url'	=> $tinymce_url,
				'backend_css'	=> $backend_css,			
				'selector'		=> 'textarea[id!=no_wysiwyg]',
				'language'		=> $language,      
				'width'		=> $width,
				'height'	=> $height,
				'css_file'	=> $css_file,
				'toolbar'	=> $toolbar,
				'skin'		=> $skin
			);
			
			echo $oTWIG->render( 
				"@tinymce/tinymce.lte",	//	template-filename
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

		$result = $oTWIG->render(
			'@tinymce/textarea.lte',	// template-filename
			$data			// template-data
		);
		
		if ($prompt) {
			echo $result;
			return true;
		}
		return $result;

	} // show_wysiwyg_editor()
	
}



?>