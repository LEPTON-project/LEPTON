<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          Twig Template Engine
 * @author          LEPTON Project
 * @copyright       2012-2017 LEPTON  Project
 * @link            https://www.LEPTON-cms.org
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
	 *	Public var that holds the "namespace" for the templates. E.g. "news" for 
	 *	the news-module. Default is "main".
	 */
	public $template_namespace = "main";
	
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
		
		if(isset($GLOBALS['wb'])) {
		
			$temp_wb = &$GLOBALS['wb'];
			$frontend_class_name = "frontend";
			if( true === $temp_wb instanceof $frontend_class_name) {
				
				$namespaces = $this->loader->getNamespaces();
				if(count($namespaces) > 1) {
					$module_directory= $namespaces[1];
			
					$temp_path = LEPTON_PATH."/templates/";
					$temp_path .= ( $temp_wb->page['template'] == "" ? DEFAULT_TEMPLATE : $temp_wb->page['template']);
					$temp_path .= "/frontend/".$module_directory."/templates/";
		
					if(file_exists($temp_path)) {
						$this->frontend_template_path = $temp_path;
					}
				}			
			}
		}
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
			$this->loader->prependPath( $this->frontend_template_path, $this->template_namespace );
			return true;
		} else {
			return file_exists($this->modul_template_path.$aTemplateFile);
		}
	}
	
	/**
	 *	Public function for transforming keys in an assoc. array and return
	 *	them as linear list.
	 *	
	 *	@param	array	A valid assoc. array.
	 *	@param	string	A valid string/char placed before the key; default is "[".
	 *	@param 	string	A VALID string/char placed after the key; default is "]".
	 *	@return array	A linear list within the transformed keys.
	 *
	 */
	public function transform_keys(&$aArray=NULL, $sBefore="[", $sAfter="]") {
		$aResult = array();
		foreach($aArray as $key => $val) $aResult[] = $sBefore.$key.$sAfter;
		return $aResult;
	}
	
	/**
	 *	Public function for buffering function-calls within "wild" echo/print.
	 *
	 *	@param	string	Any valid function within params.
	 *	@return	string	The captured result.
	 */
	public function capture_echo($aJobStr="") {
		ob_start();
			global $wb;
			global $database;
			global $TEXT;
			global $parser;
			global $loader;
			
			eval ($aJobStr);
			$result_str = ob_get_clean();
		return $result_str;
	}
}

?>