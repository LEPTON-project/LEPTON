<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		unregister_droplet
 * @author          LEPTON Project
 * @copyright       2012-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if ( defined( 'LEPTON_PATH' ) )
{
	include( LEPTON_PATH . '/framework/class.secure.php' );
} //defined( 'LEPTON_PATH' )
else
{
	$oneback = "../";
	$root    = $oneback;
	$level   = 1;
	while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
	{
		$root .= $oneback;
		$level += 1;
	} //( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) )
	if ( file_exists( $root . '/framework/class.secure.php' ) )
	{
		include( $root . '/framework/class.secure.php' );
	} //file_exists( $root . '/framework/class.secure.php' )
	else
	{
		trigger_error( sprintf( "[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER[ 'SCRIPT_NAME' ] ), E_USER_ERROR );
	}
}
// end include class.secure.php

/**
 * Unregister the Droplet $droplet_name from the $page_id with the settings
 * $module_directory, $file_type and $file_name
 * 
 * @param integer $page_id
 * @param string $droplet_name
 * @param sring $module_directory
 * @param string $file_type - 'css' or 'js'
 * @param string $file_name
 */
function unregister_droplet($page_id, $droplet_name, $module_directory, $file_type, $file_name) {
    global $database;    
    if (is_registered_droplet($page_id, $droplet_name, $module_directory, $file_type)) {
        $table = TABLE_PREFIX.'mod_droplets_load';
        $SQL = "DELETE FROM `$table` WHERE `page_id`='$page_id' AND `register_name`='$droplet_name' AND ".
            "`module_directory`='$module_directory' AND `file_type`='$file_type' AND `file_name`='$file_name'";
        if (!$database->query($SQL)) {
            trigger_error(sprintf('[%s - %s] %s', __FUNCTION__, __LINE__, $database->get_error()));
        }
    }
    return true;
} // unregister_droplet()

?>