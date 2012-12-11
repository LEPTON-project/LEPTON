<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          dropleps
 * @author          LEPTON Project
 * @copyright       2010-2012 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

 // copy droplets table for security reasons
  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."xsik_droplets`");
  $database->query("RENAME TABLE `".TABLE_PREFIX."mod_droplets` TO `".TABLE_PREFIX."xsik_droplets`");   
  
 // now delete already installed dropleps table
  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_dropleps`");

 // recreate dropleps table from droplets table to keep old droplets
  $database->query("CREATE TABLE `".TABLE_PREFIX."mod_dropleps` SELECT * FROM `".TABLE_PREFIX."xsik_droplets`");


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
  
  
//  reload droplets out of addons table
// remove addons entrys for modules that don't exist
$sql = 'SELECT `directory` FROM `' . TABLE_PREFIX . 'addons` WHERE `type` = \'module\' ';
if ($res_addons = $database->query($sql))
{
    while ($value = $res_addons->fetchRow(MYSQL_ASSOC))
    {
        if (!file_exists(WB_PATH . '/modules/' . $value['directory']))
        {
            $sql = "DELETE FROM `" . TABLE_PREFIX . "addons` WHERE `directory` = '" . $value['directory'] . "'";
            $database->query($sql);
        }
    }
}


echo "<h3>Switch to Dropleps: successful!</h3>";
?>