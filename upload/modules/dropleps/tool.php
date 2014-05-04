<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          dropleps
 * @author          LEPTON Project
 * @copyright       2010-2014 LEPTON Project
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

require_once(LEPTON_PATH.'/modules/dropleps/functions.php');
    
global $parser;
global $loader;

if (!is_object($parser) ) require_once( LEPTON_PATH."/modules/lib_twig/library.php" );

$loader->prependPath( dirname(__FILE__)."/templates/" );

$parser->addGlobal('ADMIN_URL', ADMIN_URL);
$parser->addGlobal('IMGURL', LEPTON_URL . '/modules/dropleps/css/images');
$parser->addGlobal('DOCURL', LEPTON_URL . '/modules/dropleps/docs/readme.html');
$parser->addGlobal('action', ADMIN_URL . '/admintools/tool.php?tool=dropleps');
$parser->addGlobal('TEXT', $TEXT);

global $settings;
$settings = get_settings();

/**
 *	Load Language file
 */
$langfile = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($langfile) ? (dirname(__FILE__))."/languages/EN.php" : $langfile );

$parser->addGlobal('MOD_DROPLEP', $MOD_DROPLEP);

if ( isset( $_REQUEST[ 'del' ] ) && is_numeric( $_REQUEST[ 'del' ] ) )
{
    $_POST[ 'markeddroplet' ] = $_REQUEST[ 'del' ];
    $_REQUEST[ 'delete' ]     = 1;
}
if ( isset( $_REQUEST[ 'toggle' ] ) && is_numeric( $_REQUEST[ 'toggle' ] ) )
{
    toggle_active( $_REQUEST[ 'toggle' ] );
}
elseif ( isset( $_REQUEST[ 'add' ] ) )
{
    edit_droplep( 'new' );
}
elseif ( isset( $_REQUEST[ 'edit' ] ) && !isset( $_REQUEST[ 'cancel' ] ) )
{
    edit_droplep( $_REQUEST[ 'edit' ] );
}
elseif ( isset( $_REQUEST[ 'copy' ] ) && is_numeric( $_REQUEST[ 'copy' ] ) )
{
    copy_droplep( $_REQUEST[ 'copy' ] );
}
elseif ( isset( $_REQUEST[ 'backups' ] ) && !isset( $_REQUEST[ 'cancel' ] ) )
{
    manage_backups();
}
elseif ( isset( $_REQUEST[ 'export' ] ) && !isset( $_REQUEST[ 'cancel' ] ) )
{
    $info = export_dropleps();
    list_dropleps( $info );
}
elseif ( isset( $_REQUEST[ 'import' ] ) && !isset( $_REQUEST[ 'cancel' ] ) )
{
    import_dropleps();
}
elseif ( isset( $_REQUEST[ 'delete' ] ) && !isset( $_REQUEST[ 'cancel' ] ) )
{
    export_dropleps();
    delete_dropleps();
}
elseif ( isset( $_REQUEST[ 'datafile' ] ) && is_numeric( $_REQUEST[ 'datafile' ] ) )
{
    edit_datafile( $_REQUEST[ 'datafile' ] );
}
elseif ( isset( $_REQUEST[ 'droplep_perms' ] ) && is_numeric( $_REQUEST[ 'droplep_perms' ] ) && !isset( $_REQUEST[ 'cancel' ] ) )
{
    edit_droplep_perms( $_REQUEST[ 'droplep_perms' ] );
}
elseif ( isset( $_REQUEST[ 'perms' ] ) && !isset( $_REQUEST[ 'cancel' ] ) )
{
    manage_perms();
}
else
{
    list_dropleps();
}

?>