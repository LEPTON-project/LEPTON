<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          Droplets
 * @author          LEPTON Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
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

$oDroplets = droplets::getInstance();

$oTwig = lib_twig_box::getInstance();
$oTwig->registerModule( "droplets");
$oTwig->registerGlobals( array(
    'IMGURL'    => LEPTON_URL . '/modules/droplets/css/images',
    'DOCURL'    => LEPTON_URL . '/modules/droplets/docs/readme.php?url='.LEPTON_URL.'/modules/droplets/docs',
    'action'    => ADMIN_URL . '/admintools/tool.php?tool=droplets',
    'MOD_DROPLETS'   => $oDroplets->language // ! attention
));

require_once LEPTON_PATH.'/modules/droplets/functions.php';

if ( isset( $_POST[ 'del' ] ) && is_numeric( $_POST[ 'del' ] ) )
{
    $_POST[ 'markeddroplet' ] = $_POST[ 'del' ];
    $_POST[ 'delete' ]  = 1; // aldus?
}

switch( true )
{
    case ( isset( $_POST[ 'toggle' ] ) && is_numeric( $_POST[ 'toggle' ] ) ):
        toggle_active( $_POST[ 'toggle' ] );
        list_droplets();
        break;
        
    case ( isset( $_POST[ 'add' ] ) ):
        edit_droplet( 'new' );
        break;
        
    case ( isset( $_POST[ 'edit' ] ) && !isset( $_POST[ 'cancel' ] ) ):
        edit_droplet( $_POST[ 'edit' ] );
        break;
         
    case ( isset( $_POST[ 'copy' ] ) && is_numeric( $_POST[ 'copy' ] ) ):
        copy_droplet( $_POST[ 'copy' ] );
        break;
     
    case ( isset( $_POST[ 'backups' ] ) && !isset( $_POST[ 'cancel' ] ) ):
        manage_backups();
        break;
    
    case ( isset( $_POST[ 'export' ] ) && !isset( $_POST[ 'cancel' ] ) ):
        $info = export_droplets();
        list_droplets( $info );      
        break;
        
    case ( isset( $_POST[ 'import' ] ) && !isset( $_POST[ 'cancel' ] ) ):
        import_droplets();
        break;
        
    case ( isset( $_POST[ 'delete' ] ) && !isset( $_POST[ 'cancel' ] ) ):
        export_droplets();
        delete_droplets();
        break;
     
    case ( isset( $_POST[ 'droplet_perms' ] ) && is_numeric( $_POST[ 'droplet_perms' ] ) && !isset( $_POST[ 'cancel' ] ) ):
        edit_droplet_perms( $_POST[ 'droplet_perms' ] );
        break;
         
    case ( isset( $_POST[ 'perms' ] ) && !isset( $_POST[ 'cancel' ] ) ):
        manage_perms();
        break;
         
    default:
        list_droplets();
        break;
}

?>