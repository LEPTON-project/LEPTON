<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released special license.
 * License can be seen in the info.php of this module.
 *
 * @module          lib Responsive Filemanager
 * @author          LEPTON Project, Alberto Peripolli (http://responsivefilemanager.com/)
 * @copyright       2016-2018 LEPTON Project, Alberto Peripolli
 * @link            https://lepton-cms.org
 * @license         please see info.php of this module
 * @license_terms   please see info.php of this module
 *
 */

class lib_r_filemanager extends LEPTON_abstract
{
    public static $instance;
    
    public function initialize()
    {
    
    }
    
    /**
     *  Returns a valid language "key"/name for the filemanager.
     *  See: ~filemanager/config/config.php line ~245, settings for the 'default_language'.
     *
     *  @return string   A language "key", if non match "en_EN" as defult is returned. 
     *
     */
    static public function getSystemLanguage()
    {
        $language = strtolower( LANGUAGE );
       
        $basePath = dirname( __DIR__)."/lang/";
       
        if(file_exists($basePath.$language.".php"))
        {
            return $language;
        
        } else {
            $language .= "_".strtoUpper( $language );
            if(file_exists($basePath.$language.".php"))
            {
                return $language;
            }
            else {
                // nothing match ...
                return "en_EN";
            }
        }   
    }
}