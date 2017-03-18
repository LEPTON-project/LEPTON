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

// Get language name
if(!isset($_POST['code']) OR $_POST['code'] == "") {
	header("Location: index.php");
	exit(0);
} else {
	$code = $_POST['code'];
}

// fix secunia 2010-93-2
if (!preg_match('/^[A-Z]{2}$/', $code)) {
	header("Location: index.php");
	exit(0);
}

// Check whether the language exists
if(!file_exists(LEPTON_PATH.'/languages/'.$code.'.php')) {
	header("Location: index.php");
	exit(0);
}

// Print admin header
require_once(LEPTON_PATH.'/framework/class.admin.php');
$admin = new admin('Addons', 'languages_view');

/**	*******************************
 *	Try to get the template-engine.
 */
global $parser, $loader;
if (!isset($parser))
{
	require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
}
if(file_exists(THEME_PATH."/globals/lte_globals.php")) require_once(THEME_PATH."/globals/lte_globals.php");
$loader->prependPath( THEME_PATH."/templates/", "theme" );	// namespace for the Twig-Loader is "theme"

// Insert values
require(LEPTON_PATH.'/languages/'.$code.'.php');

$page_values = array(
	'CODE' => $language_code,
	'NAME' => $language_name,
	'AUTHOR' => $language_author,
	'VERSION' => $language_version,
	'DESIGNED_FOR' => $language_platform,
	'LICENSE'	=> $language_license
);

// Restore language to original code
require(LEPTON_PATH.'/languages/'.LANGUAGE.'.php');

echo $parser->render(
	'@theme/languages_details.lte',
	$page_values
);

// Print admin footer
$admin->print_footer();

?>