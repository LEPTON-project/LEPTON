<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          Droplets
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
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
    
// upgrade droplet EmailFilter
if (!function_exists('droplet_install')) {
    include_once LEPTON_PATH.'/modules/droplets/functions.php';
}
if (file_exists(dirname(__FILE__) . '/install/droplet_EditThisPage.zip')) {
droplet_install(dirname(__FILE__) . '/install/droplet_EditThisPage.zip', LEPTON_PATH . '/temp/unzip/');
}

if (file_exists(dirname(__FILE__) . '/install/droplet_droplet_LoginBox.zip')) {
droplet_install(dirname(__FILE__) . '/install/droplet_droplet_LoginBox.zip', LEPTON_PATH . '/temp/unzip/');
}

// delete default droplets  
if (!function_exists('rm_full_dir')) {
    include_once LEPTON_PATH.'/framework/functions/function.rm_full_dir.php';
}
rm_full_dir( LEPTON_PATH.'/modules/droplets/install' ); 

?>