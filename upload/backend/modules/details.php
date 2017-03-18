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

require_once(LEPTON_PATH .'/framework/summary.functions.php');
require_once(LEPTON_PATH.'/framework/class.admin.php');
// No print admin header
$admin = new admin('Addons', 'modules_view', false);

// Get module name
if(!isset($_POST['file']) OR $_POST['file'] == "")
{
	header("Location: index.php");
	exit(0);
}
else
{
	$file = preg_replace("/\W/", "", addslashes($_POST['file']));  // fix secunia 2010-92-1
}

// Check if the module exists
if(!file_exists(LEPTON_PATH.'/modules/'.$file)) {
	header("Location: index.php");
	exit(0);
}

// Print admin header
$admin = new admin('Addons', 'modules_view');

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

// Get module values
$module = array();
$database->execute_query(
	"SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND directory = '$file'",
	true,
	$module,
	false
);

/**
 *	Check if a module description exists for the displayed backend language
 *
 */
$module_description = false;

if(file_exists(LEPTON_PATH.'/modules/'.$file.'/languages/'.LANGUAGE .'.php')) {
	require(LEPTON_PATH.'/modules/'.$file.'/languages/'.LANGUAGE .'.php');
}

if($module_description !== false) {
	/**
	 *	Override the module-description with correct desription in users language
	 */
	$module['description'] = $module_description;
}

$module['description'] = str_replace(
		array('{LEPTON_URL}', '{{ LEPTON_URL }}'),
		LEPTON_URL,
		$module['description']
);
								
switch ($module['function']) {
	case NULL:
		$module['type'] = $TEXT['UNKNOWN'];
		break;
	case 'page':
		$module['type'] = $TEXT['PAGE'];
		break;
	case 'wysiwyg':
		$module['type'] = $TEXT['WYSIWYG_EDITOR'];
		break;
	case 'tool':
		$module['type'] = $TEXT['ADMINISTRATION_TOOL'];
		break;
	case 'admin':
		$module['type'] = $TEXT['ADMIN'];
		break;
	case 'administration':
		$module['type'] = $TEXT['ADMINISTRATION'];
		break;
	case 'snippet':
		$module['type'] = $TEXT['CODE_SNIPPET'];
		break;
	case 'library':
		$module['type'] = $TEXT['LIBRARY'];	
		break;
	default:
		$module['type'] = $TEXT['UNKNOWN'];
}

echo $parser->render(
	'@theme/modules_details.lte',
	array(
		'module' => $module
	)
);

// Print admin footer
$admin->print_footer();

?>