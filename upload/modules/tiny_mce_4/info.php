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

$module_directory     = 'tiny_mce_4';
$module_name          = 'TinyMCE-4-jq';
$module_function      = 'WYSIWYG';
$module_version       = '4.1.0.0';
$module_platform      = '2.x';
$module_author        = 'erpe, Aldus';
$module_home          = 'http://lepton-cms.org';
$module_guid          = '0ad7e8dd-2f6b-4525-b4bf-db326b0f5ae8';
$module_license       = 'GNU General Public License, TINYMCE is LGPL, Ajax Filemanager is also open source license.';
$module_license_terms  = '-';
$module_description   = 'TinyMCE 4.1.0 - build date:(2014-06-18)<br>with Ajax Image File Manager and image editor<BR> allows you to edit the content of a page and see media image folder.<BR>To link your template css file to the styles in tinymce you need to edit the <b>include.php</b> file inside this module.';

/**
 *	2014-06-18	- update to 4.1.0
 *
 *	2014-05-27	- update to 4.0.27
 * 
 *	2014-04-30	- update to 4.0.24
 * 
 *	2014-04-23	- update to 4.0.23
 *
 *	2014-04-16	- update to 4.0.22
 *
 *	2014-04-01	- update to 4.0.21
 * 
 *	2014-03-18	- update to 4.0.20
 *
 *	2014-03-11	- update to 4.0.19
 *
  *	2014-02-26	- update to 4.0.17
 *
 *	2014-01-30	- update to 4.0.13
 * 
 *	2013-12-18	- update to 4.0.12
 *
 *	2013-11-20	- update to 4.0.11
 *
 *	2013-10-28	- update to 4.0.10
 * 
 *	2013-10-24	- update to 4.0.9
 * 
 *	2013-10-10	- update to 4.0.8
 *
 *	2013-10-02	- update to 4.0.7
 * 
 *	2013-09-12	- update to 4.0.6
 * 
 *	2013-08-27	- update to 4.0.5
 * 
 *	2013-08-21	- update to 4.0.4
 * 
 *	2013-08-08	- update to 4.0.3
 * 
 *	2013-07-18	- update to 4.0.2
 * 
 *	2013-06-26	- update to 4.0.1
 * 
 *	2013-06-13	- update to 4.0
 *
 *	2013-05-15	- update to 4.0b3
 * 
 *	2013-05-04	- start integration tiny_mce 4series
 *
 */
?>