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


// Get template name
if(!isset($_POST['file']) OR $_POST['file'] == "") {
	$add = (isset($_GET['leptoken']) ? "?leptoken=".$_GET['leptoken'] : "" );
	die( header("Location: index.php".$add) );

} else {
	$file = preg_replace("/\W-_/i", "", addslashes($_POST['file']));  // fix secunia 2010-92-1
}

// Check if the template exists
if(!file_exists(LEPTON_PATH.'/templates/'.$file)) {
	$add = (isset($_GET['leptoken']) ? "?leptoken=".$_GET['leptoken'] : "" );
	die( header("Location: index.php".$add) );
}


// get twig instance
$admin = LEPTON_admin::getInstance();
$oTWIG = lib_twig_box::getInstance();


// get values
$current_template = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."addons` WHERE type = 'template' AND directory = '".$file."'",
	true,
	$current_template,
	false
);

// check if a module description exists for the displayed backend language
$tool_description = false;
if(function_exists('file_get_contents') && file_exists(LEPTON_PATH.'/templates/'.$file.'/languages/'.LANGUAGE .'.php')) {
	// read contents of the module language file into string
	$data = file_get_contents(LEPTON_PATH .'/templates/' .$file .'/languages/' .LANGUAGE .'.php');
	// use regular expressions to fetch the content of the variable from the string
	$tool_description = get_variable_content('template_description', $data, false, false);
	// replace optional placeholder {LEPTON_URL} with value stored in config.php
	if($tool_description !== false && strlen(trim($tool_description)) != 0) {
		$tool_description = str_replace('{LEPTON_URL}', LEPTON_URL, $tool_description);
	} else {
		$tool_description = false;
	}
}		
if($tool_description !== false) {
	// Override the module-description with correct desription in users language
	$current_template['description'] = $tool_description;
}


// Insert language text and messages
$page_values = array(
	'current'	=>$current_template
);

$oTWIG->registerPath( THEME_PATH."/templates","templates_details" );
echo $oTWIG->render(
	"@theme/templates_details.lte",
	$page_values
);

// Print admin footer
$admin->print_footer();

?>