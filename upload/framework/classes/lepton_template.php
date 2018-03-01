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
 *  Keep in mind that this one looks similar to the LEPTON_abstract class but it's slightly different.
 *
 */
abstract class LEPTON_template
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
     *  The template directory from the info.php of the child.
     *  @type   string
     *
     */    
    public $template_directory = "";
    
    /**
     *  The template name from the info.php of the child.
     *  @type   string
     *
     */
    public $template_name = "";
    
    /**
     *  The template function from the info.php of the child.
     *  @type   string
     *
     */
    public $template_function = "";
    
    /**
     *  The template version from the info.php of the child.
     *  @type   string
     *
     */
    public $template_version = "";

    /**
     *  The template platform from the info.php of the child.
     *  @type   string
     *
     */
    public $template_platform = "";

    /**
     *  The template author from the info.php of the child.
     *  @type   string
     *
     */
    public $template_author = "";

    /**
     *  The template license from the info.php of the child.
     *  @type   string
     *
     */
    public $template_license = "";

    /**
     *  The template license terms from the info.php of the child.
     *  @type   string
     *
     */
    public $template_license_terms = "";

    /**
     *  The template description from the info.php of the child.
     *  @type   string
     *
     */
    public $template_description = "";

    /**
     *  The template guid from the info.php of the child.
     *  @type   string
     *
     */
    public $template_guid = "";

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
            static::$instance->getTemplateInfo();
            static::$instance->getLanguageFile();
            static::$instance->initialize();
        }
        return static::$instance;
    }
 
    /**
     *  Try to get all parents form the current instance as a simple linear list.
     */
    final private function getParents()
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
    final private function getTemplateInfo()
    {
        
        foreach(static::$instance->parents as $sModuleDirectory)
        {
            //  strip namespace
            $aTemp = explode("\\", $sModuleDirectory);
            $sModuleDirectory = array_pop($aTemp);
            
            $sLookUpPath = __DIR__."/../../templates/".$sModuleDirectory."/info.php";
            if( file_exists($sLookUpPath) )
            {
                require $sLookUpPath;

                if(isset($template_name)) static::$instance->template_name = $template_name;
                if(isset($template_directory)) static::$instance->template_directory = $template_directory;
                if(isset($template_function)) static::$instance->template_function = $template_function;
                if(isset($template_version)) static::$instance->template_version = $template_version;
                if(isset($template_platform)) static::$instance->template_platform = $template_platform;
                if(isset($template_author)) static::$instance->template_author = $template_author;
                if(isset($template_license)) static::$instance->template_license = $template_license;
                if(isset($template_license_terms)) static::$instance->template_license_terms = $template_license_terms;
                if(isset($template_description)) static::$instance->template_description = $template_description;
                if(isset($template_guid)) static::$instance->template_guid = $template_guid;

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
                //  Strip the namespace
                $aTemp = explode("\\", $sClassName);
                $sClassName = array_pop($aTemp);

                $lookUpPath = LEPTON_PATH."/templates/".$sClassName."/languages/";
                if(file_exists($lookUpPath.LANGUAGE.".php"))
                {
                    require $lookUpPath.LANGUAGE.".php";
                } elseif ( file_exists($lookUpPath."EN.php"))
                {
                    require $lookUpPath."EN.php";
                } else {
                    
                    continue;
                }
            
                $tempName = (static::$instance->template_function == "theme" 
                    ? "THEME" 
                    : "TEMPLATE_".strtoupper($sClassName)
                );
                
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