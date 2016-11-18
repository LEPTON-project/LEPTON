<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          wrapper
 * @author          WebsiteBaker Project
 * @author          LEPTON Project
 * @copyright       2004-2010 WebsiteBaker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
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

// check if module language file exists for the language set by the user (e.g. DE, EN)
if(!file_exists(LEPTON_PATH .'/modules/wrapper/languages/'.LANGUAGE .'.php')) {
	// no module language file exists for the language set by the user, include default module language file EN.php
	require_once(LEPTON_PATH .'/modules/wrapper/languages/EN.php');
} else {
	// a module language file exists for the language defined by the user, load it
	require_once(LEPTON_PATH .'/modules/wrapper/languages/'.LANGUAGE .'.php');
}

global $parser, $loader;
if (!isset($parser))
{
	require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
}

$loader->prependPath( dirname(__FILE__)."/templates/" );

/**
 *	Looks like a little bit oversized, but if the current frontend-template
 *	got a template for this module - we're trying to use that one!
 */
$lookup_folder = LEPTON_PATH."/templates/".DEFAULT_TEMPLATE."/frontend/wrapper/templates/";
if (file_exists($lookup_folder."view.lte")) $loader->prependPath( $lookup_folder );

// Get values from the DB
$fields = array(
	"url",
	"height"
);

$query = $database->build_mysql_query(
	'select',
	TABLE_PREFIX."mod_wrapper",
	$fields,
	"section_id = '".$section_id."'"
);

$oStatement = $database->db_handle->prepare( $query );
$oStatement->execute();
$data = $oStatement->fetch();

// Append notice-message to the data-array
$data['wrappernotice'] = $MOD_WRAPPER['NOTICE'];

echo $parser->render( 
	"view.lte",	//	template-filename
	$data	//	template-data
);

?>