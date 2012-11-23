<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          dropleps
 * @author          LEPTON Project
 * @copyright       2010-2012, LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

 // copy droplets table for security reasons
  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."xsik_droplets`");
  $database->query("CREATE TABLE `".TABLE_PREFIX."xsik_droplets` SELECT * FROM `".TABLE_PREFIX."mod_droplets`");  
  
 // now delete already installed dropleps table
  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_dropleps`");  

 // recreate dropleps table from droplets table to keep old droplets
  $database->query("CREATE TABLE `".TABLE_PREFIX."mod_dropleps` SELECT * FROM `".TABLE_PREFIX."mod_droplets`");

// import default dropleps
if (file_exists(dirname(__FILE__) . '/install/droplep_year.zip')) {
include_once (WB_PATH . '/modules/dropleps/import.php');
wb_unpack_and_import(dirname(__FILE__) . '/install/droplep_check-css.zip', WB_PATH . '/temp/unzip/');
wb_unpack_and_import(dirname(__FILE__) . '/install/droplep_EditThisPage.zip', WB_PATH . '/temp/unzip/');
wb_unpack_and_import(dirname(__FILE__) . '/install/droplep_EmailFilter.zip', WB_PATH . '/temp/unzip/');
wb_unpack_and_import(dirname(__FILE__) . '/install/droplep_LoginBox.zip', WB_PATH . '/temp/unzip/');
wb_unpack_and_import(dirname(__FILE__) . '/install/droplep_Lorem.zip', WB_PATH . '/temp/unzip/');
wb_unpack_and_import(dirname(__FILE__) . '/install/droplep_year.zip', WB_PATH . '/temp/unzip/');
}
  
/**
 *  remove old droplets module 
 */
 
if (file_exists(LEPTON_PATH . '/modules/droplets/info.php')) {
    	rm_full_dir( LEPTON_PATH.'/modules/droplets' );
}   
  
   
echo "<h3>Switch to Dropleps was successful!</h3>";
?>