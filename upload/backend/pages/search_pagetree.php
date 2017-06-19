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

$search_text = isset($_POST['searchtext']) ? addslashes(trim($_POST['searchtext'])) : "";
$search_type = isset($_POST['searchtype']) ? strtolower(trim($_POST['searchtype'])) : "";

$results = array();
if($search_text != "")
{
	switch( $search_type )
	{
		case 'title':
			$query = "SELECT `page_title`,`menu_title`,`page_id`,`visibility` from `".TABLE_PREFIX."pages` WHERE `page_title` LIKE '%".$search_text."%'";
			break;
			
		case 'page_id':
			$search_text = intval($search_text);
			$query = ( 0 <> $search_text )
				? "SELECT `page_title`,`menu_title`,`page_id`,`visibility` from `".TABLE_PREFIX."pages` WHERE `page_id` LIKE '%".intval($search_text)."%'"
				: -1
				;
			break;
			
		case 'section_id':
			$search_text = intval($search_text);
			$query = ( 0 <> $search_text )
				? "SELECT * from `".TABLE_PREFIX."pages` AS p JOIN `".TABLE_PREFIX."sections` as s WHERE (`section_id` LIKE '%".intval($search_text)."%') AND (s.page_id = p.page_id)"
				: -1
				;
			break;
			
		default:
			$query = -1;
	}
	
	if($query !== -1)
	{
		$database->execute_query( $query, true, $results, true );
	}
}

$oTwig = lib_twig_box::getInstance();

//	Keep in mind that this file is called via ajax and we have to return a json encoded string here
echo json_encode(
	$oTwig->render(
		"@theme/pages_search_results.lte",
		array(
			'db_error'	=> $database->get_error(),
			'leptoken'	=> get_leptoken(),
			'TEXT'		=> $TEXT,	// as we call this via ajax
			'results'	=> $results
		)
	)
);
