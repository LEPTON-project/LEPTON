<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2016 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 */

// set error level
 ini_set('display_errors', 1);
 error_reporting(E_ALL|E_STRICT);

echo '<h3>Current process : updating to LEPTON 2.3.1</h3>';

/**
 *  database modifications
 */

/**
 *  install new modules
 *
 */
 echo '<h5>Current process : install new modules</h5>'; 
if (!function_exists('load_module')) require_once( LEPTON_PATH."/framework/summary.functions.php");

$install = array (
"/modules/lib_r_filemanager"
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
echo "<h5>install new modules: successfull</h5>";  

/**
 *  run upgrade.php of all modified modules
 *
 */
 echo '<h5>Current process : run modules upgrade.php</h5>';  
$upgrade_modules = array(
    "droplets",
    "lib_twig",	
    "lib_semantic",	
    "tiny_mce_4"	
		

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
$database->simple_query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'2.3.1\' WHERE `name` =\'lepton_version\'');


/**
 *  success message
 */
echo "<h3>update to LEPTON 2.3.1 successfull!</h3><br />"; 

?>
