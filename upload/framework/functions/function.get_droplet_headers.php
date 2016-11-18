<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		get_droplet_headers
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
 * Check for entries for the desired $page_id or for entries which should be loaded
 * at every page, load the specified CSS and JS files in the global $HEADER array
 * 
 * @param integer $page_id
 * @return boolean true on success
 */
function get_droplet_headers($page_id) {
    global $HEADERS, $lhd, $database;

    $table = TABLE_PREFIX.'mod_droplets_load';
    
    $SQL = "SELECT * FROM `$table` WHERE (`page_id`='$page_id' OR `page_id`='-1') AND (`file_type`='css' OR `file_type`='js')";
    if (false === ($query = $database->query($SQL))) {
        trigger_error(sprintf('[%s - %s] %s', __FUNCTION__, __LINE__, $database->get_error()));
    }
    if ($query->numRows() > 0) {
        while (false !== ($droplet = $query->fetchRow())) {
            // use the module_directory if no path is set ...
            $directory = (!empty($droplet['file_path'])) ? $droplet['file_path'] : 'modules/'.$droplet['module_directory'];
            $file = $lhd->sanitizePath($directory.'/'.$droplet['file_name']);
            if (file_exists(LEPTON_PATH.'/'.$file)) {
                $options = unserialize($droplet['options']);
                
                if (isset($options['POST_ID']) && !defined('POST_ID')) continue;
                if (isset($options['TOPIC_ID']) && !defined('TOPIC_ID')) continue;
                
                if (!droplet_exists($droplet['register_name'], $page_id, $options)) {
                    // the Droplet does no longer exists at the $page_id, so remove it!
                    if (!$database->query("DELETE FROM `$table` WHERE `id`='".$droplet['id']."'")) {
                        trigger_error(sprintf('[%s - %s] %s', __FUNCTION__, __LINE__, $database->get_error()));
                    }
                }
                if ($droplet['file_type'] == 'css') {
                    // add the CSS file to the global $HEADERS
                    $HEADERS['frontend']['css'][] = array(    
                        'media' => 'all',
                        'file' => $file
                    );
                }
                else {
                    // add the JS file to the global $HEADERS
                    $HEADERS['frontend']['js'][] = $file;
                }
            }
            else {
                // if the file does not exists unregister the Droplet to avoid overhead!
                if (!$database->query("DELETE FROM `$table` WHERE `id`='".$droplet['id']."'")) {
                    trigger_error(sprintf('[%s - %s] %s', __FUNCTION__, __LINE__, $database->get_error()));
                }
            }
        }
    }
    return true;
} // get_droplet_headers()

?>