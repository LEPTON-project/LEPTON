<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2014 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 */


echo '<h3>Current process : upgrading to LEPTON 2.0.0</h3>';

/**
 *  database core modification
 */
	//add field to pages table for easymultilanguage
	$pt = "select `page_code` from `".TABLE_PREFIX."pages` limit 0";
	if (false == $database->query($pt)) {
		//add the column
		$pt = 'ALTER TABLE `'.TABLE_PREFIX.'pages` ADD `page_code` VARCHAR( 100 ) NOT NULL';
		if ($database->query($pt)) {
      echo '<h3>Database Field page_code added successfully</h3>';
		} else {
			echo '<h4>'.mysql_error().'</h4><br />';
		}
	} 
echo '<h3>All database modifications successfull</h3>';

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
    "lib_twig", 
    "lib_lepton",  
    "lib_search", 
    "lib_phpmailer",                  
    "dropleps",
    "tiny_mce_4"                 
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
    "tiny_mce_4"

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
