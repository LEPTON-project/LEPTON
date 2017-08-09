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
 * @version         $Id: sections.php 1601 2012-01-07 11:11:01Z erpe $
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

header('Content-Type: application/javascript');

if(!isset($_POST['pages'])) die();

$sPageList = $_POST['pages'];

$aPages = explode(",", $_POST['pages']);
$aNewList = array();
foreach($aPages as $ref)
{
    if($ref != "") $aNewList[] = intval($ref);
}

if(0 === count($aNewList))
{
    return "Error [1]: no Items in list.";
}

//  get the (page-)level from the first item of the list
$current_level = $database->get_one("SELECT `level` FROM `".TABLE_PREFIX."pages` WHERE `page_id`=".$aNewList[0]);

$errors = array();
$position = 1;
foreach($aNewList as $page_id)
{
    $fields = array(
        'position'  => $position++
    );
    
    $database->build_and_execute(
        'update',
        TABLE_PREFIX."pages",
        $fields,
        "`page_id`=".$page_id." AND `level`=".$current_level   
    );

    if($database->is_error()) $errors[]=$database->get_error();
}

if( 0 > count($errors) )
{
    echo "Error [3]: ". implode("\n", $errors);
} else {
    echo "Reorder to: ".json_encode( $aNewList )."\nF5 erpe, please!";
}