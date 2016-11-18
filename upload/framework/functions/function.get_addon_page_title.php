<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		get_addon_page_title
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
 * Check for individual page titles from the addons NEWS, TOPICs and for registered
 * Addons - return an empty string or individual title
 * 
 * @param integer $page_id
 * @return string - title on success or empty string if search fail
 */
function get_addon_page_title($page_id) {
    global $database;
    
    if (defined('POST_ID')) {
        // special handling for the NEWS module
        $table = TABLE_PREFIX.'mod_news_posts';
        $SQL = "SELECT `title` FROM `$table` WHERE `post_id`='".POST_ID."'";
        if (false === ($query = $database->query($SQL))) {
            trigger_error(sprintf('[%s - %s] %s', __FUNCTION__, __LINE__, $database->get_error()));
        }
        if ($query->numRows() > 0) {
            $result = $query->fetchRow();
            return $result['title'];
        }
    }
    elseif (defined('TOPIC_ID')) {
        // special handling for the TOPICS module
        $table = TABLE_PREFIX.'mod_topics';
        $SQL = "SELECT `title` FROM `$table` WHERE `topic_id`='".TOPIC_ID."'";
        if (false === ($query = $database->query($SQL))) {
            trigger_error(sprintf('[%s - %s] %s', __FUNCTION__, __LINE__, $database->get_error()));
        }
        if ($query->numRows() > 0) {
            $result = $query->fetchRow();
            return $result['title'];
        }
    }
    else {
        // check for addons which will set the page title
        $table = TABLE_PREFIX.'mod_droplets_load';
        $SQL = "SELECT `id`, `module_directory` FROM `$table` WHERE `register_type`='addon' AND `file_type`='title'";
        if (false === ($query = $database->query($SQL))) {
            trigger_error(sprintf('[%s - %s] %s', __FUNCTION__, __LINE__, $database->get_error()));
        }
        if ($query->numRows() > 0) {
            $addon = $query->fetchRow();
            $file = LEPTON_PATH.'/modules/'.$addon['module_directory'].'/headers.load.php';
            if (file_exists($file)) {
                include_once $file;
                $function = $addon['module_directory'].'_get_page_title';
                if (function_exists($function)) {
                    // return individual page title
                    return call_user_func($function, $page_id);
                }
                else {
                    // function does not exists - unregister the addon!
                    unregister_addon_header($page_id, $addon['module_directory'], 'title');
                    return '';
                }
            }
            else {
                // function does not exists - unregister the addon!
                unregister_addon_header($page_id, $addon['module_directory'], 'title');
                return '';
            }
        }
    }    
    return '';    
} // get_addon_page_title()

?>