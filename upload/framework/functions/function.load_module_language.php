<?php
/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		load_modul_language
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */
 
 if (!defined("LEPTON_PATH")) die();

/**
 *	Function to try to load the language file of a module, e.g. 'code2' or 'topics'.
 *
 *	@param	string	Any given valid module directory. E.g. "code2" or "any_other_dirname".
 *	@return	nothing
 *
 *	@notice	Keep in mind that you will have to declare the language-reference-array as global
 *			inside the languagefile, e.g. "global $MOD_CODE2;" for code2.
 *
 */ 
 function load_module_language( $sModule="" ) {
 
 	$base_path = LEPTON_PATH."/modules/".$sModule."/languages/";
	$lang = $base_path. LANGUAGE .".php";
	require_once ( !file_exists($lang) ? $base_path."/EN.php" : $lang );

 }
 ?>