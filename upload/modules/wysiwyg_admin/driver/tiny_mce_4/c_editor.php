<?php

/**
 *	@module			wysiwyg Admin
 *	@version		see info.php of this module
 *	@authors		Dietrich Roland Pehlke
 *	@copyright		2010-2013 Dietrich Roland Pehlke
 *	@license		GNU General Public License
 *	@license terms	see info.php of this module
 *	@platform		see info.php of this module
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

  
 
require_once ( dirname(__FILE__)."/../c_wysiwyg_driver.php" );

class c_editor extends wysiwyg_driver
{
	private $name = "tiny_mce_4";
	
	private $guid = "357931d7-fac4-40ef-8148-a6cf4039a176";
	
	public $skins = array();
	
	public $toolbars = array();
	
	public $toolbar_sets = array();
	
	public function __construct() {

		$this->__define_toolbar_sets();
	
		$this->skins[] = "lightgray";
		
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
  	
	private function __define_toolbar_sets() {
		
		/**
		 *	Default full toolbar
		 *
		 */
		$this->toolbar_sets['Full'] = array(
			'toolbar'	=> "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons" 
		);

		/**
		 *	Smart toolbar within only first two rows.
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
}

?>