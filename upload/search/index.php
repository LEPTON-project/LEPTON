<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2015 LEPTON Project
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

global $database;
global $TEXT;

// Required page details
$page_id = 0;
$page_description = '';
$page_keywords = '';
define('PAGE_ID', 0);
define('ROOT_PARENT', 0);
define('PARENT', 0);
define('LEVEL', 0);
define('PAGE_TITLE', $TEXT['SEARCH']);
define('MENU_TITLE', $TEXT['SEARCH']);
define('MODULE', '');
define('VISIBILITY', 'public');
define('SEARCH_LIBRARY', 'lib_search');
define('PAGE_CONTENT', LEPTON_PATH.'/modules/'.SEARCH_LIBRARY.'/index.php');

// Find out what the search template is
$query_template = $database->query("SELECT value FROM " . TABLE_PREFIX . "search WHERE name = 'template' LIMIT 1");
$fetch_template = $query_template->fetchRow( MYSQL_ASSOC );
$template = $fetch_template['value'];
if ($template != '') {
	define('TEMPLATE', $template);
}
unset($template);

//Get the referrer page ID if it exists
if (isset($_REQUEST['referrer']) && is_numeric($_REQUEST['referrer']) && intval($_REQUEST['referrer']) > 0) {
	define('REFERRER_ID', intval($_REQUEST['referrer']));
} else {
	define('REFERRER_ID', 0);
}

// Include index (wrapper) file
require (LEPTON_PATH . '/index.php');

?>