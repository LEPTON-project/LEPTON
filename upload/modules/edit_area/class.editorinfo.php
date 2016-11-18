<?php

/**
 * @module   	    edit_area
 * @version        	see info.php of this module
 * @copyright       2010-2017 LEPTON Project
 * @license        	GNU General Public License
 * @license terms  	see info.php of this module
 * @platform       	see info.php of this module
 *
 */ 
 
class editorinfo_EDIT_AREA
{

	protected $name		= "edit_area";
	
	protected $guid		= "B7892CE7-0A1D-493D-8026-2981631276EC";

	protected $version	= "0.1.3";

	protected $author	= "Dietrich Roland Pehkle (Aldus)";
	
	public $skins = array(
		'default'
	);
	
	public $toolbars = array(
		
		'Default'	=> 	'search, fullscreen, |, undo, redo, |, select_font, syntax_selection, |, highlight, reset_highlight, |, help',
		'Simple' => 'fullscreen, |, undo, redo, |, help',
		
		/**
		 *	This one is for experimental use only. Use this one for your own studies and
		 *	development e.g. own plugins, icons, tools, etc.
		 *
		 */
		'Experimental' => 'search, go_to_line, fullscreen, |, undo, redo, |, select_font, syntax_selection, |, highlight, reset_highlight, |, help'
	);
	
	public $default_width = "100%";
	
	public $default_height = "350px";
	
	public function __construct() {
	
	}
	
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
									
					$toolbars = array_keys( $this->toolbars );
					
					$fields = array(
						'editor'	=> $this->name,
						'skin'		=> $this->skins[0],		// first entry
						'menu'		=> $toolbars[0],		// first entry
						'width'		=> $this->default_width,
						'height'	=> $this->default_height
					);
					
					$db_handle->query( 
						$db_handle->build_mysql_query(
							'INSERT',
							TABLE_PREFIX."mod_wysiwyg_admin",
							$fields
						)
					);
				}
			}
		}
	}
}
?>