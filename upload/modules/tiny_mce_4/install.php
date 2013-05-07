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
 *	Update wysiwyg-admin
 *
 */
$strip = TABLE_PREFIX;
$all_tables= $database->list_tables( $strip  );
if (in_array("mod_wysiwyg_admin", $all_tables)) {
	$result= $database->query("SELECT id from `". TABLE_PREFIX ."mod_wysiwyg_admin` where `editor`='tiny_mce_4'");
	if ( ($result) && ($result->numRows() == 0) ) {
		$register_file = dirname(__FILE__)."/register_wysiwyg_admin.php";
		if (file_exists( $register_file ) ) {
			require_once( $register_file );
			
			$editor_info = new c_editor();
			
			$query = $database->build_mysql_query(
				'insert',							// job
				TABLE_PREFIX."mod_wysiwyg_admin",	// what table
				$editor_info->defaults				// the data to insert
			);
			
			$database->query( $query );
		}
	}
}
?>