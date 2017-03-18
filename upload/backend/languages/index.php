<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 *
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */
 
// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {	
	include(LEPTON_PATH.'/framework/class.secure.php'); 
} else {
	$oneback = "../";
	$root = $oneback;
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= $oneback;
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php

require_once(LEPTON_PATH.'/framework/class.admin.php');
$admin = new admin('Addons', 'languages');

/**	*******************************
 *	Get the template-engine.
 */
global $parser, $loader;
if (!isset($parser))
{
	require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
}
if(file_exists(THEME_PATH."/globals/lte_globals.php")) require_once(THEME_PATH."/globals/lte_globals.php");
$loader->prependPath( THEME_PATH."/templates/", "theme" );	// namespace for the Twig-Loader is "theme"

/**
 *	Get all languages from the database
 */
$all_languages = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."addons` WHERE `type` = 'language' order by `name`",
	true,
	$all_languages
);

/**
 *	Try to get to the language-code via the language-file
 */
foreach($all_languages as &$lang) {
	$temp_filename = LEPTON_PATH."/languages/".$lang['directory'].".php";
	if (file_exists($temp_filename)) {
		$langugae_code = "gb";
		
		// Keep in mind the var is overwritten by the language-file!
		require( $temp_filename );
		
		$lang['code'] = $language_code;
	}
}

//	Restore the language.
require(LEPTON_PATH."/languages/".LANGUAGE.".php");

/**
 *	Build secure-hash for the js-calls
 */
if(!function_exists("random_string")) require_once( LEPTON_PATH."/framework/functions/function.random_string.php");
$hash = array(
	'h_name' => random_string(16),
	'h_value' => random_string(24)
);
$_SESSION['backend_language_h'] = $hash['h_name'];
$_SESSION['backend_language_v'] = $hash['h_value'];

$page_values = array(
	'all_languages'	=> $all_languages,
	'hash'	=> $hash
);

echo $parser->render(
	"@theme/languages.lte",
	$page_values
);

// Print admin footer
$admin->print_footer();

?>