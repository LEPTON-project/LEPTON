<?php

/**
 * @module   	    edit_area
 * @version        	see info.php of this module
 * @author			Christophe Dolivet (EditArea), Christian Sommer (wrapper), LEPTON Project
 * @copyright		2009-2010 Christian Sommer
 * @copyright 		2010-2015 LEPTON Project
 * @license        	GNU General Public License
 * @license terms  	see info.php of this module
 * @platform       	see info.php of this module
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

 
require_once( dirname(__FILE__)."/class.editorinfo.php");

$edit_area_info = new editorinfo_EDIT_AREA();
$edit_area_info->wysiwyg_admin_init( $database );

?>