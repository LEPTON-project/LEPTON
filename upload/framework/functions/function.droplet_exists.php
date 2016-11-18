<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		droplet_exists
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
 * Check if the Droplet $droplet_name exists in a WYSIWYG section of $page_id or
 * if the Droplet is placed in a NEWs or TOPICs article.
 * 
 * @param string $droplet_name
 * @param integer $page_id
 * @param array $option
 * @return boolean true on success
 */
function droplet_exists($droplet_name, $page_id, &$option=array()) {
    global $database;
    
    if (isset($option['POST_ID']) || defined('POST_ID')) {
        // Droplet may be placed at a NEWs article
        $post_id = defined('POST_ID') ? POST_ID : $option['POST_ID'];
        $table = TABLE_PREFIX.'mod_news_posts';
        $SQL = "SELECT `page_id` FROM `$table` WHERE `post_id`='$post_id' AND ((`content_long` LIKE '%[[$droplet_name?%') OR (`content_long` LIKE '%[[$droplet_name]]%'))";
        if (false === ($query = $database->query($SQL))) {
            trigger_error(sprintf('[%s - %s] %s', __FUNCTION__, __LINE__, $database->get_error()));
        }
        if ($query->numRows() > 0) return true;
    }
    
    if (isset($option['TOPIC_ID']) || defined('TOPIC_ID')) {
        // Droplet may be placed at a TOPICs article
        $topic_id = defined('TOPIC_ID') ? TOPIC_ID : $option['TOPIC_ID'];
        $table = TABLE_PREFIX.'mod_topics';
        $SQL = "SELECT `page_id` FROM `$table` WHERE `topic_id`='$topic_id' AND ((`content_long` LIKE '%[[$droplet_name?%') OR (`content_long` LIKE '%[[$droplet_name]]%'))";
        if (false === ($query = $database->query($SQL))) {
            trigger_error(sprintf('[%s - %s] %s', __FUNCTION__, __LINE__, $database->get_error()));
        }
        if ($query->numRows() > 0) return true;
    }

    $table = TABLE_PREFIX.'mod_wysiwyg';
    $SQL = "SELECT `section_id` FROM `$table` WHERE `page_id`='$page_id' AND ((`text` LIKE '%[[$droplet_name?%') OR (`text` LIKE '%[[$droplet_name]]%'))";
    if (false === ($query = $database->query($SQL))) {
        trigger_error(sprintf('[%s - %s] %s', __FUNCTION__, __LINE__, $database->get_error()));
    }
    if ($query->numRows() > 0) return true;
    
    return false;
} // droplet_exists()

?>