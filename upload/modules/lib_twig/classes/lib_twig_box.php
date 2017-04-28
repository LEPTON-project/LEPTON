<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module			Twig Template Engine
 * @author			LEPTON Project
 * @copyright		2012-2017 LEPTON  
 * @link			https://www.LEPTON-cms.org
 * @license			http://www.gnu.org/licenses/gpl.html
 * @license_terms	please see info.php of this module
 *
 */

class lib_twig_box extends lib_twig
{

	/**
	 *	Public var that holds the instance of the TWIG-loader
	 */
	public $loader = NULL;
	
	/**
	 *	Public var that holds the instance of the TWIG-parser
	 */
	public $parser = NULL;
	
	/**
     * @var Singleton The reference to *Singleton* instance of this class
     */
    private static $instance;
	
	/**
	 *	Return the »internal« instance of the object
	 *	and intialize some basic (LEPtON-CMS specific) values.
	 *
	 */
	public static function getInstance()
    {
        if (null === static::$instance)
        {
            static::$instance = new static();
            self::register();
            
            static::$instance->loader = new Twig_Loader_Filesystem( LEPTON_PATH.'/' );

            static::$instance->loader->prependPath( LEPTON_PATH."/templates/".DEFAULT_THEME."/templates/", "theme" );
        	static::$instance->loader->prependPath( LEPTON_PATH."/templates/".DEFAULT_TEMPLATE."/templates/", "frontend" );
        	
        	static::$instance->parser = new Twig_Environment( 
        		static::$instance->loader,
        		array(
				'cache' => false,
				'debug' => true
			) );

			static::$instance->parser->addGlobal("LEPTON_PATH", LEPTON_PATH);
			static::$instance->parser->addGlobal("LEPTON_URL", LEPTON_URL);
			static::$instance->parser->addGlobal("ADMIN_URL", ADMIN_URL);
			static::$instance->parser->addGlobal("THEME_PATH", THEME_PATH);
			static::$instance->parser->addGlobal("THEME_URL", THEME_URL);

        }
        
        return static::$instance;
    }
	
	/**
	 *	Public function to register a path to the current instance.
	 *	If the path doesn't exists he will not be added to avoid Twig-imternal warnings.  
	 *
	 *	@param	string	A path to any local template directory. 
	 *	@param	string	An optional namspace (-identifier),
	 *					by default "__main__", normaly e.g. the namespace of a module.
	 *					See the Twig documentation for details about using "template" namespaces
	 *	@return bool	Trueif succes, false if file doesn't exists or the param is empty.
	 *
	 */    
    public function registerPath( $sPath = "", $sNamespace="__main__" )
    {
    	if($sPath === "") return false;
    	if(true === file_exists( $sPath ))
    	{
    		static::$instance->loader->prependPath( $sPath,  $sNamespace );
    		return true;
    	}
    	return false;
    }
    
    /**
     *	Register one or more values global to the instance via an assoc. array.
     *
     */
    public function registerGlobals( $aArray )
    {
    	foreach( $aArray as $key => $value)
    	{
    		static::$instance->parser->AddGlobal( $key , $value);
    	}
    }
    
    /**
     *	Public shortcut to the internal loader->render method.
     *
     *	@param	string	A valid templatename (to use) incl. the namespace.
     *	@param	array	The values to parse.
     *	@return string	The parsed template string.
     *
     */
    public function render( $sTemplateName, $aMixed)
    {
    	return static::$instance->parser->render( $sTemplateName, $aMixed );
    }
    
    /**
     *	public function to "register" all module specific paths at once
     *	
     *	@param	string	A valid module-directory (also used as namespace)
     */
    public function registerModule( $sModuleDir )
    {
    	static::$instance->registerPath( LEPTON_PATH."/templates/".DEFAULT_THEME."/backend/".$sModuleDir."/", $sModuleDir );

    	$basepath = LEPTON_PATH."/modules/".$sModuleDir;
    	static::$instance->registerPath( $basepath."/templates/backend", $sModuleDir );
    	static::$instance->registerPath( $basepath."/templates/", $sModuleDir );
    	
    	// for the fronend
		if(defined("PAGE_ID"))
		{
			$page_template = LEPTON_database::getInstance()->get_one("SELECT `template` FROM `".TABLE_PREFIX."pages` WHERE `page_id`=".PAGE_ID);
			static::$instance->registerPath( LEPTON_PATH."/templates/".( $page_template == "" ? DEFAULT_TEMPLATE : $page_template)."/frontend/".$sModuleDir."/", $sModuleDir );
		}
    }
}