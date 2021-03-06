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
    /**
     *  Array with the language(-array) of the child-object.
     *  @type   array
     *
     */
    public $language = array();
    
    /**
     *  Array with the names of all parents (desc. order)
     *  @type   array
     *
     */
    public $parents = array();
    
    /**
     *  The module directory from the info.php of the child.
     *  @type   string
     *
     */
    public $module_directory = "";

    /**
     *  The module name from the info.php of the child.
     *  @type   string
     *
     */
    public $module_name = "";
    
    /**
     *  The module function from the info.php of the child.
     *  @type   string
     *
     */
    public $module_function = "";

    /**
     *  The module version from the info.php of the child.
     *  @type   string
     *
     */
    public $module_version = "";
    
    /**
     *  The module platform from the info.php of the child.
     *  @type   string
     *
     */
    public $module_platform = "";
    
    /**
     *  The module author from the info.php of the child.
     *  @type   string
     *
     */
    public $module_author = "";

    /**
     *  The module license from the info.php of the child.
     *  @type   string
     *
     */
    public $module_license = "";
    
    /**
     *  The module license terms from the info.php of the child.
     *  @type   string
     *
     */
    public $module_license_terms = "";
    
    /**
     *  The module description from the info.php of the child.
     *  @type   string
     *
     */
    public $module_description = "";
    
    /**
     *  The module guid from the info.php of the child.
     *  @type   string
     *
     */
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
            static::$instance->getParents();
            static::$instance->getModuleInfo();
            static::$instance->getLanguageFile();
            static::$instance->initialize();
        }
        return static::$instance;
    }

    /**
     *  Try to get all parents form the current instance as a simple linear list.
     */
    private function getParents()
    {
        // First the class itself
        static::$instance->parents[] = get_class(static::$instance);
        
        // Now the parents        
        $aTempParents = class_parents( static::$instance, true );
        foreach($aTempParents as $sParentname)
        {
            static::$instance->parents[] = $sParentname;
        }
    }
    
    /**
     *  Try to read the module specific info.php from the module-Directory
     *  and update the current class-properties.
     *
     */
    final private function getModuleInfo()
    {
        
        foreach(static::$instance->parents as $sModuleDirectory)
        {
            //  strip namespace
            $aTemp = explode("\\", $sModuleDirectory);
            $sModuleDirectory = array_pop($aTemp);
            
            $sLookUpPath = __DIR__."/../../modules/".$sModuleDirectory."/info.php";
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
    final private function getLanguageFile()
    {
        if(defined("LEPTON_PATH"))
        {
            foreach( static::$instance->parents as $sClassName)
            {
                //  strip namespace
                $aTemp = explode("\\", $sClassName);
                $sClassName = array_pop($aTemp);

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