<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2015 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 */

// set error level
 ini_set('display_errors', 1);
 error_reporting(E_ALL|E_STRICT);

echo '<h3>Current process : updating to LEPTON 2.0.1</h3>';

/**
 *  database modifications
 */


/**
 *  delete not needed files
 */
$temp_path = LEPTON_PATH."/framework/functions/function.register_frontend_modfiles_body.php";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}
 
$temp_path = LEPTON_PATH."/framework/functions/function.register_frontend_modfiles.php";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}
 
echo "<h3>delete files: successfull</h3>"; 
 
/**
 *  run upgrade.php of all modified modules
 *
 */
$upgrade_modules = array(
	"addon_file_editor",
	"captcha_control",
	"code2",
	"jsadmin",
    "lib_jquery",
    "lib_lepton",
	"lib_twig",
	"news",
	"output_interface",
    "tiny_mce_4",
    "wrapper",
	"wysiwyg",
	"wysiwyg_admin"
		

);

foreach ($upgrade_modules as $module)
{
    $temp_path = LEPTON_PATH . "/modules/" . $module . "/upgrade.php";

    if (file_exists($temp_path))
        require($temp_path);
}
echo "<h3>run upgrade.php of modified modules: successfull</h3>";


// at last: set db to current release-no
$database->query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'2.0.1\' WHERE `name` =\'lepton_version\'');


/**
 *  success message
 */
echo "<h3>update to LEPTON 2.0.1 successfull!</h3><br />"; 

?>
