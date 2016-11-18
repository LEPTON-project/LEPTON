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

/**	*******************************
 *	Try to get the template-engine.
 */
global $parser, $loader;
require( dirname(__FILE__)."/register_parser.php" );

// Get page content
$fields = array(
	'url',
	'height'
);

$query = $database->build_mysql_query(
	'select',
	TABLE_PREFIX."mod_wrapper",
	$fields,
	"section_id = '".$section_id."'"
);

$oStatement = $database->db_handle->prepare( $query );
$oStatement->execute();
$settings = $oStatement->fetch();

// Insert vars
$data = array(
	'PAGE_ID' => $page_id,
	'SECTION_ID' => $section_id,
	'LEPTON_URL' => LEPTON_URL,
	'URL' => $settings['url'],
	'HEIGHT' => $settings['height'],
	'TEXT_URL' => $TEXT['URL'],
	'TEXT_HEIGHT' => $TEXT['HEIGHT'],
	'TEXT_SAVE' => $TEXT['SAVE'],
	'TEXT_CANCEL' => $TEXT['CANCEL']
);

$twig_util->resolve_path("modify.lte");

echo $parser->render( 
 $twig_modul_namespace.'modify.lte',
 $data // template-data
);
?>