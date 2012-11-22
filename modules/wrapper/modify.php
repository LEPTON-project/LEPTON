<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          wrapper
 * @author          WebsiteBaker Project
 * @author          LEPTON Project
 * @copyright       2004-2010, WebsiteBaker Project
 * @copyright       2010-2011, LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 * @version         $Id: modify.php 1172 2011-10-04 15:26:26Z frankh $
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
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



// Setup template object
$template = new Template(WB_PATH.'/modules/wrapper');
$template->set_file('page', 'htt/modify.htt');
$template->set_block('page', 'main_block', 'main');

// Get page content
$query = "SELECT url,height FROM ".TABLE_PREFIX."mod_wrapper WHERE section_id = '$section_id'";
$get_settings = $database->query($query);
$settings = $get_settings->fetchRow();
$url = ($settings['url']);
$height = $settings['height'];

// Insert vars
$template->set_var(array(
		'PAGE_ID' => $page_id,
		'SECTION_ID' => $section_id,
		'WB_URL' => WB_URL,
		'URL' => $url,
		'HEIGHT' => $height,
		'TEXT_URL' => $TEXT['URL'],
		'TEXT_HEIGHT' => $TEXT['HEIGHT'],
		'TEXT_SAVE' => $TEXT['SAVE'],
		'TEXT_CANCEL' => $TEXT['CANCEL']
		)
);

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

?>