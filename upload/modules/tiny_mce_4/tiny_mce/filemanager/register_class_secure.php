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

global $lepton_filemanager;
if (!is_object($lepton_filemanager)) require_once( "../../../../framework/class.lepton.filemanager.php" );


$files_to_register = array(
	'/modules/tiny_mce_4/tiny_mce/filemanager/config/config.php',
	'/modules/tiny_mce_4/tiny_mce/filemanager/dialog.php',
	'/modules/tiny_mce_4/tiny_mce/filemanager/upload.php',
	'/modules/tiny_mce_4/tiny_mce/filemanager/force_download.php',
	'/modules/tiny_mce_4/tiny_mce/filemanager/execute.php',
	'/modules/tiny_mce_4/tiny_mce/filemanager/ajax_calls.php'
	
);

$lepton_filemanager->register( $files_to_register );

?>