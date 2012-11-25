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

// include class.secure.php to protect this file and the whole CMS!
if ( defined( 'WB_PATH' ) )
{
    include( WB_PATH . '/framework/class.secure.php' );
}
else
{
    $root  = "../";
    $level = 1;
    while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
    {
        $root .= "../";
        $level += 1;
    }
    if ( file_exists( $root . '/framework/class.secure.php' ) )
    {
        include( $root . '/framework/class.secure.php' );
    }
    else
    {
        trigger_error( sprintf( "[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER[ 'SCRIPT_NAME' ] ), E_USER_ERROR );
    }
}
// end include class.secure.php

/**
 *  switch droplets to dropleps module during upgrade to LEPTON 2series
 *  or delete not needed upgrade_directory
 */
if (file_exists(LEPTON_PATH . '/modules/droplets/info.php')) {
    include LEPTON_PATH . '/modules/dropleps/2upgrade/switch_dropleps.php';
}
    	rm_full_dir( LEPTON_PATH.'/modules/dropleps/2upgrade' );

// delete not needed sik_tables
  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."xsik_droplets`");
  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."xsik_dropleps`");
  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."xsik_dropleps_settings`");
  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."xsik_dropleps_permissions`");  
  
 // create  dropleps sik_tables for security reasons
  $database->query("CREATE TABLE `".TABLE_PREFIX."xsik_dropleps` SELECT * FROM `".TABLE_PREFIX."mod_dropleps`");   
  $database->query("CREATE TABLE `".TABLE_PREFIX."xsik_dropleps_settings` SELECT * FROM `".TABLE_PREFIX."mod_dropleps_settings`"); 
  $database->query("CREATE TABLE `".TABLE_PREFIX."xsik_dropleps_permissions` SELECT * FROM `".TABLE_PREFIX."mod_dropleps_permissions`");      

?>