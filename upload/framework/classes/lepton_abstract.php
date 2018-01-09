<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

/**
 *  This is only an abstract class for LEPTON specific classes and inside modules.
 *
 */
abstract class LEPTON_abstract
{
    public $language = array();
    
    public $parents = array();
    
    public $module_directory = "";
    public $module_name = "";
    public $module_function = "";
    public $module_version = "";
    public $module_platform = "";
    public $module_author = "";
    public $module_license = "";
    public $module_license_terms = "";
    public $module_description = "";
    public $module_guid = "";

    /**
     *  @var    object  The reference to the *Singleton* instance of this class.
     *  @notice         Keep in mind that a child-object has to have his own one!
     */
    static $instance;

    /**
     *  Return the instance of this class.
     *
     */
    public static function getInstance()
    {
        if (null === static::$instance)
        {
            static::$instance = new static();
            static::$instance->__getPatents();
            static::$instance->__getModuleInfo();
            static::$instance->__getLanguageFile();
            static::$instance->initialize();
        }
        return static::$instance;
    }

    /**
     *  Try to get all parents form the current instance.
     */
    final private function __getPatents()
    {
        static::$instance->parents[] = get_class(static::$instance);
        
        $bFoundRoot = false;
        do{
            $sTempName = get_parent_class( end (static::$instance->parents) );
            if($sTempName === false){
                $bFoundRoot = true;
            } else {
                static::$instance->parents[] = $sTempName;
            }
        } while ($bFoundRoot === false);
    }
    
    /**
     *  Try to read the module specific info.php from the module-Directory
     *  and update the current class-properties.
     *
     */
    final private function __getModuleInfo()
    {
        foreach(static::$instance->parents as $sModuleDirectory)
        {
            $sLookUpPath = LEPTON_PATH."/modules/".$sModuleDirectory."/info.php";
            if( file_exists($sLookUpPath) )
            {
                require $sLookUpPath;

                if(isset($module_name)) static::$instance->module_name = $module_name;
                if(isset($module_directory)) static::$instance->module_directory = $module_directory;
                if(isset($module_function)) static::$instance->module_function = $module_function;
                if(isset($module_version)) static::$instance->module_version = $module_version;
                if(isset($module_platform)) static::$instance->module_platform = $module_platform;
                if(isset($module_author)) static::$instance->module_author = $module_author;
                if(isset($module_license)) static::$instance->module_license = $module_license;
                if(isset($module_license_terms)) static::$instance->module_license_terms = $module_license_terms;
                if(isset($module_description)) static::$instance->module_description = $module_description;
                if(isset($module_guid)) static::$instance->module_guid = $module_guid;

                break;
            }
        }
    }
    
    /**
     *  Try to get a module-spezific language file.
     */
    final private function __getLanguageFile()
    {
        if(defined("LEPTON_PATH"))
        {
            foreach( static::$instance->parents as $sClassName)
            {
                $lookUpPath = LEPTON_PATH."/modules/".$sClassName."/languages/";
                if(file_exists($lookUpPath.LANGUAGE.".php"))
                {
                    require $lookUpPath.LANGUAGE.".php";
                } elseif ( file_exists($lookUpPath."EN.php"))
                {
                    require $lookUpPath."EN.php";
                } else {
                    
                    continue;
                }
            
                $tempName = "MOD_".strtoupper($sClassName);
                if(isset(${$tempName}))
                {
                    static::$instance->language = ${$tempName}; 
                    break;
                }
            }
        }
    }

    /**
     *  Abstact declarations - to be overwrite by the child-instance.
     */
    abstract protected function initialize();

}