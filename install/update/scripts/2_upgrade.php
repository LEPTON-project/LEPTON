<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2012 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 */

// set error level
// ini_set('display_errors', 1);
// error_reporting(E_ALL|E_STRICT);


echo '<h3>Current process : upgrading to LEPTON 2.0.0</h3>';

/**
 *  check if database is utf-8
 */
echo '<h3>Currently no database modifications</h3>';

/**
 *  database modification
 */
echo '<h3>Currently no database modifications</h3>';

 //delete class.secure2
$temp_path = WB_PATH."/framework/class.secure2.php";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}

 //delete charsets_table
$temp_path = WB_PATH."/framework/charsets_table.php";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}

//delete date_formats
$temp_path = ADMIN_PATH."/interface/date_formats.php";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}


/**
 *  run install.php of all new modules
 *
 */
$install_modules = array(
    "lib_dwoo", 
    "lib_lepton",  
    "lib_search",             
    "dropleps"                  
);

foreach ($install_modules as $module)
{
    $temp_path = WB_PATH . "/modules/" . $module . "/install.php";

    if (file_exists($temp_path))
        require($temp_path);
} 
echo "<h3>run install.php of new modules: successfull</h3>"; 
 

/**
 *  run upgrade.php of all modified modules
 *
 */
$upgrade_modules = array(
    "lib_jquery",                    
    "tiny_mce_jq"

);

foreach ($upgrade_modules as $module)
{
    $temp_path = WB_PATH . "/modules/" . $module . "/upgrade.php";

    if (file_exists($temp_path))
        require($temp_path);
} 
echo "<h3>run upgrade.php of modified modules: successfull</h3>";


/**
 *  remove include/pclzip dir 
 */
 
if (file_exists(LEPTON_PATH . '/include/pclzip/pclzip.php')) {
    	rm_full_dir( LEPTON_PATH.'/include/pclzip' );
} 
echo "<h3>delete pclzip directory: successfull</h3>";

// at last: set db to current release-no
$database->query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'2.0.0\' WHERE `name` =\'lepton_version\'');

/**
 *  success message
 */
echo "<h3>update to LEPTON 2.0.0 successfull!</h3><br /><hr /><br />"; 

?>
