<?php

/**
 *  @module         TinyMCE-4-jQ
 *  @version        see info.php of this module
 *  @authors        erpe, Dietrich Roland Pehlke (Aldus)
 *  @copyright      2012-2016 erpe, Dietrich Roland Pehlke (Aldus)
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
$module_version       = '4.3.2.0';
$module_platform      = '2.x';
$module_author        = 'erpe, Aldus';
$module_home          = 'http://lepton-cms.org';
$module_guid          = '0ad7e8dd-2f6b-4525-b4bf-db326b0f5ae8';
$module_license       = 'GNU General Public License, TINYMCE is LGPL, Responsive Filemanager is Creative Commons License.';
$module_license_terms  = '-';
$module_description   = '<a href="http://www.tinymce.com/" target="_blank">Current TinyMCE </a><br />with <a href="http://www.responsivefilemanager.com/" target="_blank">current Responsive Filemanager and image editor</a><br />allows you to edit the content of a page and see media image folder.';

/**
 * changelog details on https://github.com/LEPTON-project/LEPTON_2
 * and for tiny_mce only on https://www.tinymce.com/docs/changelog/
 */
?>