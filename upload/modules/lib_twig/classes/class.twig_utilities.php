<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          Twig Template Engine
 * @author          LEPTON Project
 * @copyright       2012-2014 LEPTON Project
 * @link            http://www.lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
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