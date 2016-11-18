<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		get_addon_page_description
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
 * Check for individual page description from the addons NEWS, TOPICs and for 
 * registered Addons - return an empty string or individual description
 * 
 * @param integer $page_id
 * @return string - title on success or empty string if search fail
 */
function get_addon_page_description($page_id) {
    global $database;
    
    if (defined('POST_ID')) {
        $table = TABLE_PREFIX.'mod_news_posts';
        $SQL = "SELECT `content_short` FROM `$table` WHERE `post_id`='".POST_ID."'";
        if (false === ($query = $database->query($SQL))) {
            trigger_error(sprintf('[%s - %s] %s', __FUNCTION__, __LINE__, $database->get_error()));
        }
        if ($query->numRows() > 0) {
            $result = $query->fetchRow();
            return strip_tags($result['content_short']);
        }
    }
    elseif (defined('TOPIC_ID')) {
        $table = TABLE_PREFIX.'mod_topics';
        $SQL = "SELECT `description` FROM `$table` WHERE `topic_id`='".TOPIC_ID."'";
        if (false === ($query = $database->query($SQL))) {
            trigger_error(sprintf('[%s - %s] %s', __FUNCTION__, __LINE__, $database->get_error()));
        }
        if ($query->numRows() > 0) {
            $result = $query->fetchRow();
            return $result['description'];
        }
    }
    else {
        // check for addons which will set the page description
        $table = TABLE_PREFIX.'mod_droplets_load';
        $SQL = "SELECT `id`, `module_directory` FROM `$table` WHERE `register_type`='addon' AND `file_type`='description'";
        if (false === ($query = $database->query($SQL))) {
            trigger_error(sprintf('[%s - %s] %s', __FUNCTION__, __LINE__, $database->get_error()));
        }
        if ($query->numRows() > 0) {
            $addon = $query->fetchRow();
            $file = LEPTON_PATH.'/modules/'.$addon['module_directory'].'/headers.load.php';
            if (file_exists($file)) {
                include_once $file;
                $function = $addon['module_directory'].'_get_page_description';
                if (function_exists($function)) {
                    // return individual page description
                    return call_user_func($function, $page_id);
                }
                else {
                    // function does not exists - unregister the addon!
                    unregister_addon_header($page_id, $addon['module_directory'], 'description');
                    return '';
                }
            }
            else {
                // function does not exists - unregister the addon!
                unregister_addon_header($page_id, $addon['module_directory'], 'description');
                return '';
            }
        }
    }
    return '';
} // get_addon_page_description()

?>