<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
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

$leptoken = (isset($_GET['leptoken'])) ? $_GET['leptoken'] : "";

// Get module name and stay on page if nothing is selected
if(!isset($_POST['file']) OR $_POST['file'] == "")
{
	header("Location: index.php?leptoken=".$leptoken);
	exit(0);
} else {
	$file = preg_replace("/\W/", "", addslashes($_POST['file']));  // fix secunia 2010-92-1
}

// Check if the module exists
if(!file_exists(LEPTON_PATH.'/modules/'.$file)) {
	header("Location: index.php");
	exit(0);
}

// Print admin header
$admin = new admin('Addons', 'modules_view');

// Setup module object
$template = new Template(THEME_PATH.'/templates');
$template->set_file('page', 'modules_details.htt');
$template->set_block('page', 'main_block', 'main');

// Insert values
$result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND directory = '$file'");
if($result->numRows() > 0) {
	$module = $result->fetchRow();
}

// check if a module description exists for the displayed backend language
$tool_description = false;
if(function_exists('file_get_contents') && file_exists(LEPTON_PATH.'/modules/'.$file.'/languages/'.LANGUAGE .'.php')) {
	// read contents of the module language file into string
	$data = file_get_contents(LEPTON_PATH .'/modules/' .$file .'/languages/' .LANGUAGE .'.php');
	// use regular expressions to fetch the content of the variable from the string
	$tool_description = get_variable_content('module_description', $data, false, false);
	// replace optional placeholder {LEPTON_URL} with value stored in config.php
	if($tool_description !== false && strlen(trim($tool_description)) != 0) {
		$tool_description = str_replace('{LEPTON_URL}', LEPTON_URL, $tool_description);
	} else {
		$tool_description = false;
	}
}		
if($tool_description !== false) {
	// Override the module-description with correct desription in users language
	$module['description'] = $tool_description;
}

$template->set_var(array(
	'NAME' => $module['name'],
	'AUTHOR' => $module['author'],
	'DESCRIPTION' => $module['description'],
	'VERSION' => $module['version'],
	'DESIGNED_FOR' => $module['platform'],
	'ADMIN_URL' => ADMIN_URL,
	'LEPTON_URL' => LEPTON_URL,
	'LEPTON_PATH' => LEPTON_PATH,
	'THEME_URL' => THEME_URL,
	'LICENSE'	=> $module['license']
	)
);
						
switch ($module['function']) {
	case NULL:
		$type_name = $TEXT['UNKNOWN'];
		break;
	case 'page':
		$type_name = $TEXT['PAGE'];
		break;
	case 'wysiwyg':
		$type_name = $TEXT['WYSIWYG_EDITOR'];
		break;
	case 'tool':
		$type_name = $TEXT['ADMINISTRATION_TOOL'];
		break;
	case 'admin':
		$type_name = $TEXT['ADMIN'];
		break;
	case 'administration':
		$type_name = $TEXT['ADMINISTRATION'];
		break;
	case 'snippet':
		$type_name = $TEXT['CODE_SNIPPET'];
		break;
	case 'library':
		$type_name = $TEXT['LIBRARY'];	
		break;
	default:
		$type_name = $TEXT['UNKNOWN'];
}
$template->set_var('TYPE', $type_name);

// Insert language headings
$template->set_var(array(
	'HEADING_MODULE_DETAILS' => $HEADING['MODULE_DETAILS']
	)
);
// Insert language text and messages
$template->set_var(array(
	'TEXT_NAME' => $TEXT['NAME'],
	'TEXT_TYPE' => $TEXT['TYPE'],
	'TEXT_AUTHOR' => $TEXT['AUTHOR'],
	'TEXT_VERSION' => $TEXT['VERSION'],
	'TEXT_DESIGNED_FOR' => $TEXT['DESIGNED_FOR'],
	'TEXT_DESCRIPTION' => $TEXT['DESCRIPTION'],
	'TEXT_BACK' => $TEXT['BACK'],
	'TEXT_LICENSE'	=> $TEXT['LICENSE']
	)
);

// Parse module object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print admin footer
$admin->print_footer();

?>