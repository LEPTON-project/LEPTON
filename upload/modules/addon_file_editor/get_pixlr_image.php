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
 * Make sanity check of PIXLR parameters
 */
// check if required GET parameter are defined
if (!(isset($_GET['img_path']) && isset($_GET['image']) && isset($_GET['type']) && isset($_GET['state']) && isset($_GET['title']))) 
	die(header('Location: ../../index.php'));

// include WB configuration file (restarts sessions) and WB admin class
require_once('../../config.php');
require_once('../../framework/class.admin.php');

// check if image URL points to the pixlr.com server and the image exists on the own server
if (strpos($_GET['image'], 'pixlr.com') == false || !file_exists(LEPTON_PATH . $_GET['img_path'])) 
	die(header('Location: ../../index.php'));

/**
 * Ensure that only users with permissions to Admin-Tools section can access this file
 */
// check user permissions for admintools (redirect users with wrong permissions)
$admin = new admin('Admintools', 'admintools', false, false);
if ($admin->get_permission('admintools') == false) die(header('Location: ../../index.php'));

// include module configuration and function file
require_once('config.inc.php');

// work out file extension for the image to be saved
$type = in_array($_GET['type'], $image_extensions) ? $_GET['type'] : 'jpg';

// work out file name and path for the image to save
$file_info = pathinfo(LEPTON_PATH . $_GET['img_path']);
$save_path = $file_info['dirname'] . '/' . str_replace($file_info['extension'], '', $file_info['basename']) . 'pixlr.' . $type;

// delete possible existing image on the server
@unlink($save_path);

// copy modified images to the own server (requires fopen_wrappers enabled)
$file = file_get_contents($_GET['image']);
$handle = fopen($save_path, 'w');

if (fwrite($handle, $file) !== false) {
	fclose($handle);
	echo '<p style="color: green;">Modified image saved as: "' . str_replace(LEPTON_PATH, '', $save_path) . '".<br />';
	echo 'Remember to reload the Addon File Editor to update the file list.</p>';
} else {
	echo '<p style="color: red;">Unable to fetch and store the modified image from pixlr.com.</p>';
}

echo '<p><a href="" onClick="JavaScript:self.close()">close window</a></p>';

?>