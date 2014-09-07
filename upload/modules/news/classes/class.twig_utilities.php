<?php

/**
 *	Experimental class for module "news" for LEPTON-CMS 2.x
 *
 *	@author		Dietrich Roland Pehlke
 *	@license	GNU General Public License
 *
 *	@notice		This is an experimental one! Strict ALPHA!
 *				WITHOUT ANY WARRANTY
 */

class twig_utilities
{
	/**
	 *	Public internal "shortcut" to the twig-parser.
	 */
	public $parser = NULL;
	
	/**
	 *	Public internal "shortcut" to the twig-loader.
	 */
	public $loader = NULL;
	
	/**
	 *	Public internal "shortcut" to the template-dir of the used module.
	 */
	public $modul_template_path = "";
	
	/**
	 *	Public internal "shortcut" to the template-dir of the used frontend-template.
	 */
	public $frontend_template_path = "";
	
	/**
	 *	Public constructor of the class
	 *
	 *	@param	object	A valid Twig-Parser instance.
	 *	@param	object	A valid Twig-Loader instance.
	 *	@param	string	Path to the template-directory of the module.
	 *	@param	string	Path to the template-directory of the default-frontend template.
	 *
	 */
	public function __construct( &$oParser=NULL, &$oLoader=NULL, $sModulTemplatePath="", $sFrontendTemplatePath="" ) {
		$this->parser = $oParser;
		$this->loader = $oLoader;
		$this->modul_template_path = $sModulTemplatePath;
		$this->frontend_template_path = $sFrontendTemplatePath;
	}
	
	public function __destruct() {
	
	}
	
	/**
	 *	Looks for a template-file in both folders and returns
	 *	a boolean if or if not found.
	 *
	 *	@param	string	Any valid template-file-name.
	 *	@return bool	True, if file exists, false if not.
	 *
	 */
	public function resolve_path($aTemplateFile="") {
		if( file_exists($this->frontend_template_path.$aTemplateFile)) {
			$this->loader->prependPath( $this->frontend_template_path );
			return true;
		} else {
			return file_exists($this->modul_template_path.$aTemplateFile);
		}
	}
}

?>