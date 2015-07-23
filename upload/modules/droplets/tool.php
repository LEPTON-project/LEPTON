<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          Droplets
 * @author          LEPTON Project
 * @copyright       2010-2015 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH'))
{
    include(LEPTON_PATH . '/framework/class.secure.php');
}
else
{
    $oneback = "../";
    $root    = $oneback;
    $level   = 1;
    while (($level < 10) && (!file_exists($root . '/framework/class.secure.php')))
    {
        $root .= $oneback;
        $level += 1;
    }
    if (file_exists($root . '/framework/class.secure.php'))
    {
        include($root . '/framework/class.secure.php');
    }
    else
    {
        trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
    }
}
// end include class.secure.php

require_once(LEPTON_PATH.'/modules/droplets/functions.php');
    
global $parser;
global $loader;

if (!is_object($parser) ) require_once( LEPTON_PATH."/modules/lib_twig/library.php" );

//$loader->prependPath( dirname(__FILE__)."/templates/" );
$loader->prependPath( dirname(__FILE__)."/templates/".((DEFAULT_THEME == "lepsem") ? "backend/" : ""));

$parser->addGlobal('ADMIN_URL', ADMIN_URL);
$parser->addGlobal('IMGURL', LEPTON_URL . '/modules/droplets/css/images');
$parser->addGlobal('DOCURL', LEPTON_URL . '/modules/droplets/docs/readme.php?url='.LEPTON_URL.'/modules/droplets/docs');
$parser->addGlobal('action', ADMIN_URL . '/admintools/tool.php?tool=droplets');
$parser->addGlobal('TEXT', $TEXT);

global $settings;
$settings = get_settings();

/**
 *	Load Language file
 */
$langfile = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($langfile) ? (dirname(__FILE__))."/languages/EN.php" : $langfile );

$parser->addGlobal('MOD_DROPLET', $MOD_DROPLET);

if ( isset( $_REQUEST[ 'del' ] ) && is_numeric( $_REQUEST[ 'del' ] ) )
{
    $_POST[ 'markeddroplet' ] = $_REQUEST[ 'del' ];
    $_REQUEST[ 'delete' ]     = 1;
}
if ( isset( $_REQUEST[ 'toggle' ] ) && is_numeric( $_REQUEST[ 'toggle' ] ) )
{
    toggle_active( $_REQUEST[ 'toggle' ] );
    list_droplets();
}
elseif ( isset( $_REQUEST[ 'add' ] ) )
{
    edit_droplet( 'new' );
}
elseif ( isset( $_REQUEST[ 'edit' ] ) && !isset( $_REQUEST[ 'cancel' ] ) )
{
    edit_droplet( $_REQUEST[ 'edit' ] );
}
elseif ( isset( $_REQUEST[ 'copy' ] ) && is_numeric( $_REQUEST[ 'copy' ] ) )
{
    copy_droplet( $_REQUEST[ 'copy' ] );
}
elseif ( isset( $_REQUEST[ 'backups' ] ) && !isset( $_REQUEST[ 'cancel' ] ) )
{
    manage_backups();
}
elseif ( isset( $_REQUEST[ 'export' ] ) && !isset( $_REQUEST[ 'cancel' ] ) )
{
    $info = export_droplets();
    list_droplets( $info );
}
elseif ( isset( $_REQUEST[ 'import' ] ) && !isset( $_REQUEST[ 'cancel' ] ) )
{
    import_droplets();
}
elseif ( isset( $_REQUEST[ 'delete' ] ) && !isset( $_REQUEST[ 'cancel' ] ) )
{
    export_droplets();
    delete_droplets();
}
elseif ( isset( $_REQUEST[ 'droplet_perms' ] ) && is_numeric( $_REQUEST[ 'droplet_perms' ] ) && !isset( $_REQUEST[ 'cancel' ] ) )
{
    edit_droplet_perms( $_REQUEST[ 'droplet_perms' ] );
}
elseif ( isset( $_REQUEST[ 'perms' ] ) && !isset( $_REQUEST[ 'cancel' ] ) )
{
    manage_perms();
}
else
{
    list_droplets();
}

?>