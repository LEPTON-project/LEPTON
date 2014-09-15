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
 * @copyright	2010-2014 LEPTON Project
 * @license     GNU General Public License
 * @version		see info.php
 * @platform	see info.php
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
 * ADJUST THE FOLLOWING SETTINGS ACCORDING YOUR NEEDS
*/
// add extension of text files you want to be editable (will be displayed with a text icon)
$text_extensions = array('txt', 'htm', 'html', 'htt', 'lte','tmpl', 'tpl', 'xml', 'css', 'js', 'php', 'php3', 'php4', 'php5', 'jquery', 'preset');

// add extension for image files (will be displayed with a image icon)
$image_extensions = array('bmp', 'gif', 'jpg', 'jpeg', 'png');

// add extension for zip archives (will be displayed with a zip icon)
$archive_extensions = array('zip', 'rar', 'tar', 'gz');

// module/template folders (e.g. 'addon_file_editor') or languages (e.g. 'en') you want not to show (all loser case)
$hidden_addons = array();	

// true:=show all files (false:= only show files registered in text, image or archive array)
$show_all_files = false;

// maximum allowed file upload size in MB
$max_upload_size = 2;

// activate experimental support for the online Flash image editor service http://pixlr.com/
$pixlr_support = false;

#########################################################################################################
# NOTE: DO NOT CHANGE ANYTHING BELOW THIS LINE UNLESS YOU NOW WHAT YOU ARE DOING
#########################################################################################################
// extract path seperator and detect this module name
$path_sep = strtoupper(substr(PHP_OS, 0, 3) == 'WIN') ? '\\' : '/';
$module_folder = str_replace(LEPTON_PATH . $path_sep . 'modules' . $path_sep, '', dirname(__FILE__));

/**
 * PATH AND URL VARIABLES USED BY THE MODULE
*/
$table = TABLE_PREFIX . 'addons';
$url_icon_folder = LEPTON_URL . '/modules/' . $module_folder . '/icons';
$url_admintools = ADMIN_URL . '/admintools/tool.php?tool=' . $module_folder;
$url_action_handler = LEPTON_URL . '/modules/' . $module_folder . '/action_handler.php';
$url_ftp_assistant = LEPTON_URL . '/modules/' . $module_folder . '/ftp_assistant.php';

$temp_zip_path = LEPTON_PATH . $path_sep . 'temp' . $path_sep . $module_folder . $path_sep;
$url_mod_path = LEPTON_URL . '/modules/' . $module_folder;

// version check
if (!isset($no_check) && !file_exists(ADMIN_PATH . '/admintools/tool.php')) {
	// load module language file
	$lang = (dirname(__FILE__)) . '/languages/' . LANGUAGE . '.php';
	require_once(!file_exists($lang) ? (dirname(__FILE__)) . '/languages/EN.php' : $lang );
	$admin->print_error('<br /><strong style="color: red;">' . $LANG[0]['TXT_VERSION_ERROR'] . '</strong>');
}

?>