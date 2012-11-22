<?php

/**
 *  @module         TinyMCE-jQ
 *  @version        see info.php of this module
 *  @authors        erpe, Dietrich Roland Pehlke (Aldus)
 *  @copyright      2010-2012 erpe, Dietrich Roland Pehlke (Aldus)
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *  @requirements   PHP 5.2.x and higher
 *
 *  Please Notice: TINYMCE is distibuted under the <a href="http://tinymce.moxiecode.com/license.php">(LGPL) License</a> 
 *                 Ajax Filemanager is distributed under the <a href="http://www.gnu.org/licenses/gpl.html)">GPL </a> and <a href="http://www.mozilla.org/MPL/MPL-1.1.html">MPL</a> open source licenses 
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
} else {
	$root = "../";
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= "../";
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php

// Delete obsolete plugins wbmodules and wbdroplets
if (file_exists(WB_PATH . '/modules/tiny_mce_jq/tiny_mce/plugins/wbdroplets/wbdroplets.php')) {
   rm_full_dir(WB_PATH . '/modules/tiny_mce_jq/tiny_mce/plugins/wbdroplets');
}
if (file_exists(WB_PATH . '/modules/tiny_mce_jq/tiny_mce/plugins/wbmodules/wbmodules.php')) {
   rm_full_dir(WB_PATH . '/modules/tiny_mce_jq/tiny_mce/plugins/wbmodules');
}

/**
 *	Delete obsolete language-files in some plugins
 */
$temp_files = array(
	WB_PATH . '/modules/tiny_mce_jq/tiny_mce/plugins/dropleps/langs/de_dlg.js',
	WB_PATH . '/modules/tiny_mce_jq/tiny_mce/plugins/dropleps/langs/en_dlg.js',
	WB_PATH . '/modules/tiny_mce_jq/tiny_mce/plugins/pagelink/langs/de_dlg.js',
	WB_PATH . '/modules/tiny_mce_jq/tiny_mce/plugins/pagelink/langs/en_dlg.js'
);
foreach($temp_files as $temp_filename) {
	if (file_exists($temp_filename)) unlink($temp_filename);
}
?>