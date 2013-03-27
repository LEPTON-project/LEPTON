<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2013 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 */

// set error level
// ini_set('display_errors', 1);
// error_reporting(E_ALL|E_STRICT);


echo '<h3>Current process : upgrading to LEPTON 2.0.0</h3>';

/**
 *  database core modification
 */
echo '<h3>Currently no database core modifications</h3>';

/**
 *  create a copy of config.php and then add content
 */
$filename = WB_PATH.'/config.php';
$newfile = WB_PATH.'/config_sik.php';

if (!copy($filename, $newfile)) {
    echo '<strong>creating of backup file '.$filename.' failed...</strong><br />';
}
echo '<strong>backup file '.$filename.' created!</strong><br />';

$add_content = ''.
"<?php
define('LEPTON_URL', WB_URL);
define('LEPTON_PATH', WB_PATH);
?>";

// make sure that config.php is writeable
if (is_writable($filename)) {

    // open file 
    if (!$handle = fopen($filename, "a")) {
         echo '<p>cannot open '.$filename.'! Please add content manually</p>';
    }

    // and add content
    if (!fwrite($handle, $add_content)) {
         echo '<p>cannot write content into '.$filename.'! Please add content manually</p>';
    }

    echo '<strong> added content into '.$filename.' successful</strong><br />';

    fclose($handle);

} else {
    echo '<p>File '.$filename.' is not writable</p>';
}

// load new config.php after editing
  require_once(LEPTON_PATH . '/config.php');
  require_once(LEPTON_PATH . '/framework/functions.php');  
  
 //delete class.secure2
$temp_path = LEPTON_PATH."/framework/class.secure2.php";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}

 //delete charsets_table
$temp_path = LEPTON_PATH."/framework/charsets_table.php";
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
 *  remove old phpmailer module 
 */
 
if (file_exists(LEPTON_PATH . '/modules/phpmailer/info.php')) {
    	rm_full_dir( LEPTON_PATH.'/modules/phpmailer' );
} 
echo "<h3>delete phpmailer: successfull</h3>";
/**
 *  remove include/pclzip dir 
 */
 
if (file_exists(LEPTON_PATH . '/include/pclzip/pclzip.php')) {
    	rm_full_dir( LEPTON_PATH.'/include/pclzip' );
} 
echo "<h3>delete pclzip: successfull</h3>";


/**
 *  keep in mind:
 *  switch to new search is done via install.php of lib_search
 *  and switch to dropleps via install.php of module dropleps
 */
 
/**
 *  create new admin objrct to get new modules installed
 */ 
unset ($admin);

if (!is_object($admin))
{
    require_once(LEPTON_PATH . '/framework/class.admin.php');
    $admin = new admin('Addons', 'modules', false, false);
} 
/**
 *  run install.php of all new modules
 *
 */
$install_modules = array(
    "lib_dwoo", 
    "lib_lepton",  
    "lib_search", 
    "lib_phpmailer",                  
    "dropleps"                  
);

foreach ($install_modules as $module)
{
    $temp_path = LEPTON_PATH . "/modules/" . $module . "/install.php";

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
    "form",                   
    "news",     
    "lib_phpmailer",  
    "addon_file_editor",        
    "tiny_mce_jq"

);

foreach ($upgrade_modules as $module)
{
    $temp_path = LEPTON_PATH . "/modules/" . $module . "/upgrade.php";

    if (file_exists($temp_path))
        require($temp_path);
} 
echo "<h3>run upgrade.php of modified modules: successfull</h3>";


// at last: set db to current release-no
$database->query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'2.0.0\' WHERE `name` =\'lepton_version\'');

/**
 *  success message
 */
echo "<h3>update to LEPTON 2.0.0 successfull!</h3><br /><hr /><br />"; 

?>
