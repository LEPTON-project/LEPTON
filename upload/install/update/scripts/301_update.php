<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2017 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 */

 /* only for direct test purposes 
 require_once('../../../config.php');
global $admin;
if (!is_object($admin))
{
    require_once(LEPTON_PATH . '/framework/classes/lepton_admin.php');
    $admin = new LEPTON_admin('Addons', 'modules', false, false);
}
 */
// set error level
 ini_set('display_errors', 1);
 error_reporting(E_ALL|E_STRICT);

echo ('<h3>Current process : updating to LEPTON 3.0.1</h3>');

echo ('<h5>Current process : delete unneeded files</h5>'); 
$file = array (
"/config_sik.php"
);
 
foreach ($file as $del)
{
    $temp_path = LEPTON_PATH . $del;
    if (file_exists($temp_path)) 
	{
		$result = unlink ($temp_path);
		if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
		}
	}
}	
echo "<h5>Delete files: successfull</h5>"; 

// rename editor tiny_mce_4
echo '<h5>install tinymce again</h5>';
if (!function_exists('load_module')) require_once( LEPTON_PATH."/framework/summary.functions.php");

$install = array (
	"/modules/tinymce"
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

echo '<h5>move tinymce files</h5>';
if (file_exists (LEPTON_PATH.'/modules/tiny_mce_4/tiny_mce/skins/skin.custom.css')) {
	rename( LEPTON_PATH.'/modules/tiny_mce_4/tiny_mce/skins/skin.custom.css',LEPTON_PATH .'/modules/tinymce/css/backend_sik.css');
}

if (file_exists (LEPTON_PATH.'/modules/tiny_mce_4/templates/custom.lte')) {
	rename( LEPTON_PATH.'/modules/tiny_mce_4/templates/custom.lte',LEPTON_PATH .'/modules/tinymce/templates/custom.lte');
}

if (file_exists (LEPTON_PATH.'/modules/tiny_mce_4/class.editorinfo.custom.php')) {
	rename( LEPTON_PATH.'/modules/tiny_mce_4/class.editorinfo.custom.php',LEPTON_PATH .'/modules/tinymce/class.editorinfo.custom_sik.php');
}

echo '<h5>set editor in settings table</h5>';
$database->simple_query("UPDATE `". TABLE_PREFIX ."settings` SET `value` ='tinymce' WHERE `name` ='wysiwyg_editor' ");

echo '<h5>delete obsolete tiny_mce_4</h5>';
if (file_exists (LEPTON_PATH.'/modules/tiny_mce_4/info.php')) {	
		rm_full_dir( LEPTON_PATH.'/modules/tiny_mce_4' ); 
}

/**
 *  run upgrade.php of all modified modules
 *
 */
 echo '<h5>Current process : run modules upgrade.php</h5>';  
$upgrade_modules = array(
    "droplets",
    "lib_lepton",
    "lib_phpmailer",	
	"lib_twig",
	"news",
	"quickform"
);

foreach ($upgrade_modules as $module)
{
    $temp_path = LEPTON_PATH . "/modules/" . $module . "/upgrade.php";

    if (file_exists($temp_path))
        require($temp_path);
}
echo "<h5>run upgrade.php of modified modules: successfull</h5>";


// at last: set db to current release-no
 echo '<h5>set database to new release</h5>';
$database->simple_query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'3.0.1\' WHERE `name` =\'lepton_version\'');


/**
 *  success message
 */
echo "<h3>update to LEPTON 3.0.1 successfull!</h3><br />"; 

?>
