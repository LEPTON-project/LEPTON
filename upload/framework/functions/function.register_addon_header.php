<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		register_addon_header
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
 * Register a addon in $module_directory for sending 'title', 'description' or
 * 'keywords'
 * 
 * @param integer $page_id
 * @param string $module_name
 * @param string $module_directory
 * @param string $header_type - 'title', 'description', 'keywords'
 */
function register_addon_header($page_id, $module_name, $module_directory, $header_type) {
    global $database;
    
    if (is_registered_addon_header($page_id, $module_directory, $header_type)) return true;
    
    $table = TABLE_PREFIX.'mod_droplets_load';
    $SQL = "INSERT INTO `$table` (page_id, file_type, module_directory, register_name, register_type) ".
    "VALUES ('$page_id', '$header_type', '$module_directory', '$module_name', 'addon')";
    if (!$database->query($SQL)) {
        trigger_error(sprintf('[%s - %s] %s', __FUNCTION__, __LINE__, $database->get_error()));
    }
    return true;
} // register_addon_header()

?>