<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		register_droplet
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
 * Register the Droplet $droplet_name for the $page_id for the $file_type 'css' or 'js'
 * with the specified $file_name.
 * If $file_path is specified the file will be loaded from $file_path, otherwise the
 * file will be loaded from the desired $module_directory.
 * If $page_id is set to -1 the CSS/JS file will be loaded at every page (for usage 
 * in templates)
 * 
 * @param integer $page_id
 * @param string $droplet_name
 * @param string $module_directory - only the directory name
 * @param string $file_type - may be 'css' or 'js'
 * @param string $file_name - the filename with extension
 * @param string $file_path - relative to the root
 * @return boolean on success
 */
function register_droplet($page_id, $droplet_name, $module_directory, $file_type, $file_name='frontend.css', $file_path='') {
    global $database;
    
    $option = array();
    if (defined('POST_ID')) $option['POST_ID'] = POST_ID;
    if (defined('TOPIC_ID')) $option['TOPIC_ID'] = TOPIC_ID;
    $option_str = serialize($option);
    
    if (is_registered_droplet($page_id, $droplet_name, $module_directory, $file_type)) return true;
    
    $table = TABLE_PREFIX.'mod_droplets_load';
    $SQL = "INSERT INTO `$table` (page_id, register_name, register_type, file_type, module_directory, file_name, file_path, options) ".
        "VALUES ('$page_id', '$droplet_name', 'droplet', '$file_type', '$module_directory', '$file_name', '$file_path', '$option_str')";
    if (!$database->query($SQL)) {
        trigger_error(sprintf('[%s - %s] %s', __FUNCTION__, __LINE__, $database->get_error()));
    }
    return true;    
} // register_css

?>