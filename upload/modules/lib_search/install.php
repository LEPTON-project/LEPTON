<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          lib_search
 * @author          LEPTON Project
 * @copyright       2013 LEPTON Project
 * @link            http://www.lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
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

global $database;
global $admin;

$error = '';

$SQL = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'search` ('
    . ' `search_id` INT NOT NULL auto_increment,'
    . ' `name` VARCHAR(255) NOT NULL DEFAULT \'\' ,'
    . ' `value` TEXT NOT NULL ,'
    . ' `extra` TEXT NOT NULL ,'
    . ' PRIMARY KEY (`search_id`) '
    . ' )';
if (!$database->query($SQL)) {
    $error .= sprintf('[CREATE TABLE] %s', $database->get_error());
}

// delete existing configuration settings
$SQL = "DELETE FROM `".TABLE_PREFIX."search` WHERE name='header' OR name='footer'"
    ." OR name='results_header' OR name='results_loop' OR name='results_footer'"
    ." OR name='no_results' OR name='cfg_enable_old_search' OR name='cfg_enable_flush'"
    ." OR name='module_order' OR name='max_excerpt' OR name='time_limit'"
    ." OR name='cfg_search_keywords' OR name='cfg_search_description'"
    ." OR name='cfg_search_non_public_content' OR name='cfg_show_description'"
    ." OR name='template' OR name='cfg_link_non_public_content'"
    ." OR name='cfg_search_images' OR name='cfg_thumbs_width' OR name='cfg_content_image'"
    ." OR name='cfg_search_library' OR name='cfg_search_droplep'"
    ." OR name='cfg_search_use_page_id'";
if (!$database->query($SQL)) {
    $error .= sprintf('[DELETE VALUES] %s', $database->get_error());
}

// set default values for the LEPTON search
$database->query("INSERT INTO `".TABLE_PREFIX."search` (name, value) VALUES ('module_order', 'wysiwyg')");
$database->query("INSERT INTO `".TABLE_PREFIX."search` (name, value) VALUES ('max_excerpt', '15')");
$database->query("INSERT INTO `".TABLE_PREFIX."search` (name, value) VALUES ('time_limit', '0')");
$database->query("INSERT INTO `".TABLE_PREFIX."search` (name, value) VALUES ('cfg_search_keywords', 'true')");
$database->query("INSERT INTO `".TABLE_PREFIX."search` (name, value) VALUES ('cfg_search_description', 'true')");
$database->query("INSERT INTO `".TABLE_PREFIX."search` (name, value) VALUES ('cfg_search_non_public_content', 'false')");
$database->query("INSERT INTO `".TABLE_PREFIX."search` (name, value) VALUES ('cfg_link_non_public_content', '')");
$database->query("INSERT INTO `".TABLE_PREFIX."search` (name, value) VALUES ('cfg_show_description', 'true')");
$database->query("INSERT INTO `".TABLE_PREFIX."search` (name, value) VALUES ('template', '')");
$database->query("INSERT INTO `".TABLE_PREFIX."search` (name, value) VALUES ('cfg_search_images', 'true')");
$database->query("INSERT INTO `".TABLE_PREFIX."search` (name, value) VALUES ('cfg_thumbs_width', '100')");
$database->query("INSERT INTO `".TABLE_PREFIX."search` (name, value) VALUES ('cfg_content_image', 'first')");
$database->query("INSERT INTO `".TABLE_PREFIX."search` (name, value) VALUES ('cfg_search_library', 'lib_search')");
$database->query("INSERT INTO `".TABLE_PREFIX."search` (name, value) VALUES ('cfg_search_droplep', 'LEPTON_SearchResults')");
$database->query("INSERT INTO `".TABLE_PREFIX."search` (name, value) VALUES ('cfg_search_use_page_id', '-1')");

// import dropleps
if (!function_exists('droplep_install')) {
    include_once LEPTON_PATH.'/modules/dropleps/functions.php';
}

if (file_exists(dirname(__FILE__) . '/install/droplep_LEPTON_SearchBox.zip')) {
droplep_install(dirname(__FILE__) . '/install/droplep_LEPTON_SearchBox.zip', LEPTON_PATH . '/temp/unzip/');

/**
 *  switch to new lib_search if upgrading from 1series to 2series using upgrade package
 *  can be deleted with next release of this lib
 */
if (file_exists(LEPTON_PATH . '/search/search_convert.php')) {
    	rm_full_dir( LEPTON_PATH.'/search' );
}

if (file_exists(LEPTON_PATH . '/search_new/index.php')) {
      rename(LEPTON_PATH.'/search_new', LEPTON_PATH.'/search');      
} 
}
?>