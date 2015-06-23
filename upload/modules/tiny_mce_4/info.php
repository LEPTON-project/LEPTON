<?php

/**
 *  @module         TinyMCE-4-jQ
 *  @version        see info.php of this module
 *  @authors        erpe, Dietrich Roland Pehlke (Aldus)
 *  @copyright      2012-2014 erpe, Dietrich Roland Pehlke (Aldus)
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 *  Please note: TINYMCE is distibuted under the <a href="http://tinymce.moxiecode.com/license.php">(LGPL) License</a> 
 *  Responsive Filemanager is distributed by <a href="http://www.responsivefilemanager.com/">http://www.responsivefilemanager.com/</a> and is licensed under the <a href="http://creativecommons.org/licenses/by-nc/3.0/">Creative Commons Attribution-NonCommercial 3.0</a>  Unported License
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

$module_directory     = 'tiny_mce_4';
$module_name          = 'TinyMCE-4-jq';
$module_function      = 'WYSIWYG';
$module_version       = '4.1.10.1';
$module_platform      = '2.x';
$module_author        = 'erpe, Aldus';
$module_home          = 'http://lepton-cms.org';
$module_guid          = '0ad7e8dd-2f6b-4525-b4bf-db326b0f5ae8';
$module_license       = 'GNU General Public License, TINYMCE is LGPL, Ajax Filemanager is also open source license.';
$module_license_terms  = '-';
$module_description   = 'TinyMCE 4.1.10 - build date:(2015-05-05)<br />with Ajax Image File Manager and image editor<br />allows you to edit the content of a page and see media image folder.<br />To link your template css file to the styles in tinymce you need to edit the <b>include.php</b> file inside this module.';

/**
 * changelog details on https://github.com/LEPTON-project/LEPTON_2
 * and for tiny_mce only on http://www.tinymce.com/develop/changelog/index.php?type=tinymce
 */
?>