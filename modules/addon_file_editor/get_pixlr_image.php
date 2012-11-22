<?php
/**
 * Admin tool: Addon File Editor
 *
 * This tool allows you to "edit", "delete", "create", "upload" or "backup" files of installed 
 * Add-ons such as modules, templates and languages via the Website Baker backend. This enables
 * you to perform small modifications on installed Add-ons without downloading the files first.
 *
 * This file contains the routines to download the modified image from http://pixlr.com
 * 
 * LICENSE: GNU General Public License 3.0
 * 
 * @author		Christian Sommer (doc)
 * @copyright	(c) 2008-2010
 * @license		http://www.gnu.org/licenses/gpl.html
 * @version		1.0.0
 * @platform	Website Baker 2.8
*/

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
if (strpos($_GET['image'], 'pixlr.com') == false || !file_exists(WB_PATH . $_GET['img_path'])) 
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
$file_info = pathinfo(WB_PATH . $_GET['img_path']);
$save_path = $file_info['dirname'] . '/' . str_replace($file_info['extension'], '', $file_info['basename']) . 'pixlr.' . $type;

// delete possible existing image on the server
@unlink($save_path);

// copy modified images to the own server (requires fopen_wrappers enabled)
$file = file_get_contents($_GET['image']);
$handle = fopen($save_path, 'w');

if (fwrite($handle, $file) !== false) {
	fclose($handle);
	echo '<p style="color: green;">Modified image saved as: "' . str_replace(WB_PATH, '', $save_path) . '".<br />';
	echo 'Remember to reload the Addon File Editor to update the file list.</p>';
} else {
	echo '<p style="color: red;">Unable to fetch and store the modified image from pixlr.com.</p>';
}

echo '<p><a href="" onClick="JavaScript:self.close()">close window</a></p>';

?>