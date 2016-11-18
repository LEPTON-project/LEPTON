<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		is_registered_droplet
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
 * Check wether the Droplet $droplet_name is registered for setting CSS/JS Headers
 * 
 * @param integer $page_id
 * @param string $droplet_name
 * @param string $module_directory
 * @param string $file_type - may be 'css' or 'js'
 * @return boolean true if the Droplet is registered
 */
function is_registered_droplet($page_id, $droplet_name, $module_directory, $file_type, $droplet_option=array()) {
    global $database;
    
    $table = TABLE_PREFIX.'mod_droplets_load';
    $SQL = "SELECT `id`, `options` FROM `$table` WHERE `page_id`='$page_id' AND `register_name`='$droplet_name' ".
        "AND `file_type`='$file_type' AND `module_directory`='$module_directory'";
    if (false === ($query = $database->query($SQL))) {
        trigger_error(sprintf('[%s - %s] %s', __FUNCTION__, __LINE__, $database->get_error()));
    }
    while (false !== ($droplet = $query->fetchRow())) {
        $option = unserialize($droplet['options']);
        if (isset($droplet_option['POST_ID'])) {
            if (isset($option['POST_ID']) && ($droplet_option['POST_ID'] == $option['POST_ID'])) return true;
        }
        elseif (isset($droplet_option['TOPIC_ID'])) {
            if (isset($option['TOPIC_ID']) && ($droplet_option['TOPIC_ID'] == $option['TOPIC_ID'])) return true;
        }
        else {
            return true;
        }
    } // while
    return false;
} // is_registered_droplet()

?>