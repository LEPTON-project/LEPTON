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

/**
 *	Introdution
 *
 *	This file hold the informations for the wysiwyg-admin modul to manage e.g. what
 *	toolbar to use, or the basic settings for the skin, width and height.
 *
 */

/**
 *	1. 	wysiwyg driver
 *
 */
require_once ( dirname(__FILE__)."/../wysiwyg_admin/driver/c_wysiwyg_driver.php" );

class c_editor extends wysiwyg_driver
{
	private $name = "tiny_mce_4";
	
	private $guid = "357931d7-fac4-40ef-8148-a6cf4039a176";

	/**
	 *	Public array for the avaible skins for this this wysiwyg editor.
	 *
	 */
	public $skins = array();
	
	/**
	 *	Public array for the avaible toolbars-names for this wysiwyg editor.
	 *
	 */
	public $toolbars = array();

	/**
	 *	Public array for the toolbar-settings for this wysiwyg editor.
	 *
	 */
	public $toolbar_sets = array();
	
	/**
	 *	Public array for the 'default' settings of this wysiwyg editor.
	 *
	 */
	public $defaults = array(
		'editor' 	=> "tiny_mce_4", 
		'width'		=> "100%",
		'height'	=> "250px",
		'skin'		=> "lightgray",
		'menu'		=> "Smart"
	);
	
	/**
	 *	The constructor of this class. Called within 'new'.
	 *  prepare, execute, finish is marked deprecated
	 */
	public function __construct() {

		$this->__define_toolbar_sets();
	
    $this->skins[] = $this->defaults['skin'];
		
		$this->toolbars = array_keys( $this->toolbar_sets );
	}
	
	public function prepare(&$db, $what) {
		return "call prepare";
	}
	
	public function execute(&$db, $what) {
		return "call execute";
	}
	
	public function finish(&$db, $what) {
		return "call finish";
	}
  	
  	/**
  	 *	Private internal function/method to define the toolbarsettings.
  	 *
  	 */
	private function __define_toolbar_sets() {
		
		/**
		 *	Default full toolbar
		 *
		 */
		$this->toolbar_sets['Full'] = array(
			'toolbar'	=> "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage" 
		);

		/**
		 *	Smart toolbar within only some menu-items.
		 *
		 */
		$this->toolbar_sets['Smart'] = array(
			'toolbar'	=> "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor" 
		);
		
		/**
		 *	Simple toolbar within only one row.
		 *
		 */
		$this->toolbar_sets['Simple'] = array(
			'toolbar'	=> "undo redo | bold italic | preview" 
		);
	}
	
	public function get_info( &$db, &$width, &$height, &$toolbar ) {
	
		$result = $db->query ("SELECT * from `".TABLE_PREFIX."mod_wysiwyg_admin` where `editor`='tiny_mce_4'");
		if ($result) {
			if ($result->numRows() > 0) {
				$info = $result->fetchRow( MYSQL_ASSOC );
				
					$width = $info['width'];
					$height = $info['height'];
					$toolbar = $this->toolbar_sets[ $info['menu'] ]['toolbar'];
			}
		}
	}
}


?>