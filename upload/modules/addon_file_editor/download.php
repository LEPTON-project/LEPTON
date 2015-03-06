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

require_once('../../framework/class.admin.php');

// include module configuration and function file
require_once('config.inc.php');
require_once('functions.inc.php');

set_include_path( dirname(__FILE__)."/lib/");

// load module language file
$lang = (dirname(__FILE__)) . '/languages/' . LANGUAGE . '.php';
require_once(!file_exists($lang) ? (dirname(__FILE__)) . '/languages/EN.php' : $lang );

/**
 * Ensure that only users with permissions to Admin-Tools section can access this file
 */
// check user permissions for admintools (redirect users with wrong permissions)
$admin = new admin('Admintools', 'admintools', false, false);
if ($admin->get_permission('admintools') == false) die(header('Location: ../../index.php'));

// check if the referer URL if available
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 
	(isset($HTTP_SERVER_VARS['HTTP_REFERER']) ? $HTTP_SERVER_VARS['HTTP_REFERER'] : '');

// if referer is set, check if script was invoked from "tool.php"
if ($referer != '' && (!(strpos($referer, $url_admintools) !== false))) 
	die(header('Location: ' . $url_admintools));

// create new instance this time showing the admin panel (no headers possible anymore)
ob_start();
$admin = new admin('Admintools', 'admintools', true, false);

// ensure that user specified addon id is valid (if not redirect user)
if (!(isset($_GET['aid']) && is_numeric($_GET['aid']) && getAddonInfos($_GET['aid']))) 
	$admin->print_error($LANG[3]['ERR_WRONG_PARAMETER'], $url_admintools);

/**
 * Remove old zip files and create new one
 */
removeFileOrFolder($temp_zip_path);
@mkdir($temp_zip_path);

// check permissions of temporary folder
if (!is_writeable($temp_zip_path)) {
	ob_end_flush();
	$admin->print_error($LANG[9]['ERR_TEMP_PERMISSION'], $url_admintools);
}

/**
 * Create a ZIP archive for the specified add-on
 */
$info = getAddonInfos($_GET['aid']);

if ($info['type'] == 'language') {
	$addon_path = LEPTON_PATH . $path_sep . $info['type'] . 's' . $path_sep . $info['directory'] . '.php';

	$path_to_download_file = LEPTON_PATH . $path_sep . 'languages' . $path_sep . $info['directory'] . '.php';
	$content_type = 'application/text';
	$download_file_name = $info['directory'] . '.txt';

} else {
	$addon_path = LEPTON_PATH . $path_sep . $info['type'] . 's' . $path_sep . $info['directory'] . $path_sep;

	// create a zip archive using the PclZip
	require_once(LEPTON_PATH . '/modules/lib_lepton/pclzip/pclzip.lib.php');
	$archive = new PclZip($temp_zip_path . $info['directory'] . '.zip');
			
	// remove leading path information to achieve a installable *.zip file
	$strip_path = strstr($addon_path, $path_sep);

	$list = $archive->create($addon_path, PCLZIP_OPT_REMOVE_PATH, $strip_path);
	if ($list == 0) {
		ob_end_flush();
		$admin->print_error($LANG[9]['ERR_ZIP_CREATION'], $url_admintools);
	}

	$path_to_download_file = $temp_zip_path . $info['directory'] . '.zip';
	$content_type = 'application/zip';
	$download_file_name = $info['directory'] . '.zip';
}

/**
 * Send the add-on backup to the browser using PEAR Download class
 */
ob_end_clean();
require('./lib/Download.php');
$dl = new HTTP_Download();
$dl->setContentType($content_type);
$dl->setFile($path_to_download_file);
$dl->setContentDisposition(HTTP_DOWNLOAD_ATTACHMENT, $download_file_name);
$status = $dl->send();
if (PEAR::isError($status)) {
	$url_download_file = str_replace(array(LEPTON_PATH, $path_sep), array(LEPTON_URL, '/'), $path_to_download_file);
	$admin = new admin('Admintools', 'admintools', true, false);
	$admin->print_error(str_replace('{URL}', $url_download_file, $LANG[9]['ERR_ZIP_DOWNLOAD']), $url_admintools);
}
	
?>