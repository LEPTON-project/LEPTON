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
 *
 */
 
/**
 *	Include custom settings for toolbar
 *
 */	
if (file_exists( LEPTON_PATH."/modules/tiny_mce_4/class.editorinfo.custom.php")) {
	include_once( LEPTON_PATH."/modules/tiny_mce_4/class.editorinfo.custom.php");
} else {

	class editorinfo_TINY_MCE_4
	{
		/**
		 *	@var	string	Holds the name of the editor - display inside wysiwyg-admin.	
		 */
		protected $name		= "tiny_mce_4";

		/**
		 *	@var	string	Holds the guid of this class.
		 */
		protected $guid		= "838FA3CA-4519-4404-8EF3-5FF015056086";

		/**
		 *	@var	string	Holds the current version of this class.
		 */
		protected $version	= "0.2.0";

		/**
		 *	@var	string	Holds the (last) author of this class.
		 */
		protected $author	= "Dietrich Roland Pehkle (Aldus)";
		
		/**
		 *	@var	array	List of supported skins of this wysiwyg-editor
		 *	@access	public
		 *
		 */
		public $skins = array(
			'lightgray'
		);
		
		/**
		 *	@var	string	The name of the default skin of this wysiwyg-editor
		 *	@access	public
		 *
		 */
		public $default_skin= "lightgray";
		
		/**
		 *	@var	array	Holds the toolbar-definitions of this wysiwyg-editor.
		 *	@access	public
		 *
		 */
		public $toolbars = array(
				
			'Full'	=> "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | pagelink droplets",

			/**
			 *	Smart toolbar within only first two rows.
			 *
			 */
			'Smart' => "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | link image | pagelink droplets",
				
			/**
			 *	Simple toolbar within only one row.
			 *
			 */
			'Simple' => "bold italic | alignleft aligncenter alignright alignjustify | link image | pagelink droplets",
				
		);
			
		/**
		 *	@var	string	The name of the default toolbar of this wysiwyg-editor
		 *	@access	public
		 *
		 */
		public $default_toolbar = "Full";
		
		/**
		 *	@var	string	Holds the default width of this editor.
		 */
		public $default_width = "100%";
		
		/**
		 *	@var	string	Holds the default height of the editor.
		 */	
		public $default_height = "250px";
		
		/**
		 *	The constructor of the class.
		 */
		public function __construct() {
	
		}
	
		/**
		 *	The destructor of this class.
		 */
		public function __destruct() {
	
		}
			
		/**
		 *	@param	string	What (toolbars or skins)
		 *	@param	string	Name of the select
		 *	@param	string	Name of the selected item.
		 *	@return	string	The generated (HTML-) select tag.
		 *
		 */
		public function build_select( $what="toolbars", $name="menu", $selected_item) {
			switch( $what ) {
				case "toolbars":
					$data_ref = array_keys($this->toolbars);
					break;
					
				case 'skins':
					$data_ref = &$this->skins;
					break;
						
				default:
					return "";
			}
				
			$s = "\n<select name='".$name."'>\n";
			foreach($data_ref as &$key) {
				$s .= "<option name='".$key."' ".( $key == $selected_item ? "selected='selected'" : "" )."'>".$key."</option>\n";
			}
			$s .= "</select>\n";
				
			return $s;
		}
			
		/**
		 *	Looking for entries in the table of the wysiwyg-admin,
		 *	if nothing found we fill it up within the "default" values of this editor.
		 *	This function is called from the install.php and upgrade.php of this module.
		 *
		 *	@param	object	A valid DB handle object. In LEPTON-CMS 1.3 it's an instance of PDO.
		 *					DB connector has at last to support some methods:
		 *					- list_tables (list of all installed tables inside the current database).
		 *					- query (to execute a given query).
		 *					- numRows (number of results of the last query).
		 *					- build_mysql_query (for building MySQL queries)
		 *
		 *					see class.database.php inside framework of LEPTON-CMS for details!.
		 *
		 */
		public function wysiwyg_admin_init( &$db_handle= NULL ) {
				
			// Only execute if first param is set
			if (NULL !== $db_handle) {
				
				$ignore = TABLE_PREFIX;
				$all_fields = $db_handle->list_tables( $ignore );
							
				if (true == in_array("mod_wysiwyg_admin", $all_fields)) {
						
					$table = TABLE_PREFIX."mod_wysiwyg_admin";
						
					$query = "SELECT `id`,`skin`,`menu`,`height`,`width` from `".$table."` where `editor`='".$this->name."' limit 0,1";
					$result = $db_handle->query ($query );
					if ($result->numRows() == 0) {
											
						$fields = array(
							'editor'	=> $this->name,
							'skin'		=> $this->default_skin,
							'menu'		=> $this->default_toolbar,
							'width'		=> $this->default_width,
							'height'	=> $this->default_height
						);
							
						$db_handle->build_and_execute(
							'insert',
							TABLE_PREFIX."mod_wysiwyg_admin",
							$fields
						);
					}
				}
			}
		}
	}
}
?>