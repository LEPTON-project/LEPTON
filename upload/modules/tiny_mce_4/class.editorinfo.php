<?php

/**
 *	First experimental version of a (new) WYSIWYG-Admin support (-class).
 *	Some informations about skin(-s) and used toolbar(-s) and there definations inside this file.
 *
 *	@version	0.1.3
 *	@date		2014-09-05
 *	@author		Dietrich Roland Pehlke (CMS-LAB)
 *
 *	@notice		LEPTON-CMS 2.x
 *
 */
 
class editorinfo
{

	protected $name		= "tiny_mce_4";
	
	protected $guid		= "838FA3CA-4519-4404-8EF3-5FF015056086";

	protected $version	= "0.1.3";

	protected $author	= "Dietrich Roland Pehkle (Aldus)";
	
	public $skins = array(
		'lightgray'
	);
	
	public $toolbars = array(
		
		'Full'	=> "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage",

		/**
		 *	Smart toolbar within only first two rows.
		 *
		 */
		'Smart' => "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | link image ",
		
		/**
		 *	Simple toolbar within only one row.
		 *
		 */
		'Simple' => "bold italic | alignleft aligncenter alignright alignjustify | link image ",
		
		/**
		 *	Simple toolbar for private use, e.g. if you want to test own written plugins/tools for the tinyMCE.
		 *
		 */
		'Custom' => "alignleft aligncenter alignright alignjustify | image | pagelink "

	);
	
	public $default_width = "100%";
	
	public $default_height = "250px";
	
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
				
				$query = "SELECT `id`,`skin`,`menu`,`height`,`width` from `".$table."` where `editor`='".$this->name."'limit 0,1";
				$result = $db_handle->query ($query );
				if ($result->numRows() == 0) {
									
					$toolbars = array_keys( $this->toolbars );
					
					$fields = array(
						'editor'	=> "tiny_mce_4",
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