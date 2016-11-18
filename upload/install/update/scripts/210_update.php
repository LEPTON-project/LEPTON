<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2015 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 */

// set error level
 ini_set('display_errors', 1);
 error_reporting(E_ALL|E_STRICT);

echo '<h3>Current process : updating to LEPTON 2.1.0</h3>';

/**
 *  database modifications
 */
// add new entry link_charset 
$database->query( "INSERT INTO `".TABLE_PREFIX."settings` (`setting_id`, `name`, `value`) VALUES (NULL, 'link_charset', 'utf-8')");


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

$temp_path = LEPTON_PATH."/framework/charsets_table.php";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}

// class.login.php is moved back to framework
$temp_path = ADMIN_PATH."/login/class.login.php";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}

$temp_path = LEPTON_PATH."/languages/DA.php";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}
 
echo "<h3>delete files: successfull</h3>"; 

// delete obsolete module output_interface
require_once(LEPTON_PATH . '/framework/summary.functions.php');
rm_full_dir(LEPTON_PATH . '/modules/output_interface');

echo "<h3>delete obsolete module output_interface: successfull</h3>";


/**
 *  install new modules
 *
 */
if (!function_exists('load_module')) require_once( LEPTON_PATH."/framework/summary.functions.php");

$install = array (
"/modules/lib_semantic"
);

// install new modules
foreach ($install as $module)
{
    $temp_path = LEPTON_PATH . $module ;

require ($temp_path.'/info.php');
load_module( $temp_path, true );

foreach(
array(
'module_license', 'module_author'  , 'module_name', 'module_directory',
'module_version', 'module_function', 'module_description',
'module_platform', 'module_guid'
) as $varname )
{
if (isset(  ${$varname} ) ) unset( ${$varname} );
}
}
echo "<h3>install new modules: successfull</h3>"; 
 
/**
 *  run upgrade.php of all modified modules
 *
 */
$upgrade_modules = array(
	"addon_file_editor",
	"captcha_control",
	"code2",
	"droplets",	
	"initial_page",	
	"jsadmin",
    "lib_jquery",
    "lib_lepton",
    "lib_semantic",	
    "lib_search",	
	"lib_twig",
	"news",
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
$database->query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'2.1.0\' WHERE `name` =\'lepton_version\'');


/**
 *  success message
 */
echo "<h3>update to LEPTON 2.1.0 successfull!</h3><br />"; 

?>
