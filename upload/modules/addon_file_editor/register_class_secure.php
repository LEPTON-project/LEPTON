<?php

/**
 * Admin tool: Addon File Editor
 *
 * This tool allows you to "edit", "delete", "create", "upload" or "backup" files of installed 
 * Add-ons such as modules, templates and languages via LEPTON backend. This enables
 * you to perform small modifications on installed Add-ons without downloading the files first.
 *
 * 
 * @author		Christian Sommer (doc), Bianka Martinovic (BlackBird), Dietrich Roland Pehlke (aldus), LEPTON Project
 * @copyright	2008-2012 Christian Sommer (doc), Bianka Martinovic (BlackBird), Dietrich Roland Pehlke (aldus)
 * @copyright	2010-2015 LEPTON Project
 * @license     GNU General Public License
 * @version		see info.php
 * @platform	see info.php
 *
*/

global $lepton_filemanager;
if (!is_object($lepton_filemanager)) require_once( "../../framework/class.lepton.filemanager.php" );


$files_to_register = array(
	'/modules/addon_file_editor/action_handler.php',
	'/modules/addon_file_editor/download.php'
);

$lepton_filemanager->register( $files_to_register );

?>