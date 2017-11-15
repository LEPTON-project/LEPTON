<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @reformatted 2013-05-30
 */

// THIS IS A TEMPORARY AND SMALL SOLUTION!
// @todo integration of IP check and check of requested params!

if (!defined('LEPTON_PATH'))
{
    if (!defined('LEPTON_INSTALL'))
    {
        // try to load config.php
        if (strpos(__FILE__, '/framework/class.secure.php') !== false)
        {
            $config_path = str_replace('/framework/class.secure.php', '', __FILE__);
        }
        else
        {
            $config_path = str_replace('\framework\class.secure.php', '', __FILE__);
        }
        if (!file_exists($config_path . '/config.php'))
        {
            if (file_exists($config_path . '/install/index.php'))
            {
                header("Location: ../install/index.php");
                exit();
            }
            else
            {
                // Problem: no config.php nor installation files...
                exit('<p><b>Sorry, but this installation seems to be damaged! Please contact your webmaster!</b></p>');
            }
        }
        
        //  1.0 The important parts:
        //  1.1 load and register the LEPTON autoloader
        require_once( __DIR__."/functions/function.lepton_autoloader.php" );
        spl_autoload_register( "lepton_autoloader", true);

        //  1.2 Get an instance of the class secure
        $oSecure = LEPTON_secure::getInstance();

        //  1.3 Is the script called inside a module directory - and if so: is thera 
        //      file named "register_class_secure"?
        $temp_path = (dirname($_SERVER['SCRIPT_FILENAME'])) . "/register_class_secure.php";
        
        if (file_exists($temp_path))
        {
            require_once $temp_path;
            
            //  Backward compatibility to modules for L* < 3.1
            if( false === $oSecure->bCalledByModule )
            { 
                // Mit einem Fuß im Grab.
                global $lepton_filemanager;
            
                $direct_access_allowed = array();
                $lepton_filemanager->merge_filenames($direct_access_allowed);
            
                $oSecure->register_filenames( $direct_access_allowed );
            }
            //  No "else" here!
            //  Module have use
            //
            //      LEPTON_secure::getInstance()->register_filenames( $files_to_register );
            //
            //  inside there own "register_class_secure.php".
        }
        
        //  2.0 Testing the filename
        //      @notice: $_SERVER['SCRIPT_NAME'] holds the path to the script witch include this file!
        $allowed = $oSecure->testFile( $_SERVER['SCRIPT_NAME'] );
                
        //  2.1 All failed - we look for some special ones
        if (!$allowed)
        {
            $admin_dir = $oSecure->getAdminDir();
            
            if (((strpos($_SERVER['SCRIPT_NAME'], $admin_dir . '/media/index.php')) !== false) || ((strpos($_SERVER['SCRIPT_NAME'], $admin_dir . '/preferences/index.php')) !== false) || ((strpos($_SERVER['SCRIPT_NAME'], $admin_dir . '/support/index.php')) !== false))
            {
                // special: do absolute nothing!
            }
            elseif ((strpos($_SERVER['SCRIPT_NAME'], $admin_dir . '/index.php') !== false) || (strpos($_SERVER['SCRIPT_NAME'], $admin_dir . '/interface/index.php') !== false))
            {
                // special: call start page of admins directory
                $leptoken = isset($_GET['leptoken']) ? "?leptoken=" . $_GET['leptoken'] : "";
                header("Location: ../" . $admin_dir . '/start/index.php' . $leptoken);
                exit();
            }
            elseif (strpos($_SERVER['SCRIPT_NAME'], '/index.php') !== false)
            {
                // call the main page
                header("Location: ../index.php");
                exit();
            }
            else
            {
                if (!headers_sent())
                {
                    // set header to 403
                    header($_SERVER['SERVER_PROTOCOL'] . " 403 Forbidden");
                }
                // stop program execution
                exit('<p><b>ACCESS DENIED! [L3]</b> - Invalid call of <i>' . $_SERVER['SCRIPT_NAME'] . '</i></p>');
            }
        }
        
        //  3.0 At last - all ok - get the config.php (and process the initialize.php)
        require_once($config_path . '/config.php');
    }
}

/**
 * strip droplets
 **/
if (!function_exists('__lep_sec_formdata'))
{
    function __lep_sec_formdata(&$arr)
    {
        foreach ($arr as $key => $value)
        {
            if (is_array($value))
            {
                __lep_sec_formdata($value);
            }
            else
            {
                // remove <script> tags
                $value     = str_replace(array(
                    '<script',
                    '</script'
                ), array(
                    '&lt;script',
                    '&lt;/script'
                ), $value);
                $value     = preg_replace('#(\&lt;script.+?)>#i', '$1&gt;', $value);
                $value     = preg_replace('#(\&lt;\/script)>#i', '$1&gt;', $value);
                //$arr[$key] = preg_replace( '#\[\[.+?\]\]#', '', __strip($value) );
                $arr[$key] = str_replace(array(
                    '[',
                    ']'
                ), array(
                    '&#91;',
                    '&#93;'
                ), $value);
            }
        }
    }
}

// secure form input
if (isset($_SESSION) && !defined('LEP_SEC_FORMDATA') && !isset($_SESSION['USER_ID']))
{
    if (count($_GET))
    {
        __lep_sec_formdata($_GET);
    }
    if (count($_POST))
    {
        __lep_sec_formdata($_POST);
    }
    if (count($_REQUEST))
    {
        __lep_sec_formdata($_REQUEST);
    }
    define('LEP_SEC_FORMDATA', true);
}

?>