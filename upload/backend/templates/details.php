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
 * @version         $Id: details.php 1172 2011-10-04 15:26:26Z frankh $
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

// Print admin header
$admin = new admin('Addons', 'templates_view',true);

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

/**
 *	Get template values
 */
$template = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."addons` WHERE `type` = 'template' AND `directory` = '".$file."'",
	true,
	$template,
	false
);

/**
 *	check if a template description exists for the displayed backend language
 */
$template_description = false;
if(file_exists(LEPTON_PATH.'/templates/'.$file.'/languages/'.LANGUAGE .'.php')) {
	require(LEPTON_PATH.'/templates/'.$file.'/languages/'.LANGUAGE .'.php');
}

if( $template_description !== false) {
	$template['description'] = $template_description;
}

$template['description'] = str_replace(
	array('{LEPTON_URL}', '{{ LEPTON_URL }}'),
	LEPTON_URL,
	$template['description']
);

echo $parser->render(
	'@theme/templates_details.lte',
	array(
		'template'	=> $template
	)
);

// Print admin footer
$admin->print_footer();

?>