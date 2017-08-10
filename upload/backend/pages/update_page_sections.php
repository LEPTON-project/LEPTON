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

header('Content-Type: application/javascript');

if(!isset($_POST['sections'])) die("E1");

$sSectionList = trim( $_POST['sections'] );

if( $sSectionList == "" ) die("E2");

$aSections = explode(",", $sSectionList);
$aNewList = array();
foreach($aSections as $ref)
{
    if($ref != "") $aNewList[] = intval($ref);
}

if(0 === count($aNewList))
{
    return "Error [1]: no Items in list.";
}

$errors = array();
$position = 1;

foreach($aNewList as $section_id)
{
    $fields = array(
        'position'  => $position++
    );
    
    $database->build_and_execute(
        'update',
        TABLE_PREFIX."sections",
        $fields,
        "`section_id`=".$section_id
    );

    if(true === $database->is_error())
    {
        $errors[]=$database->get_error();
    }
}

if( 0 > count($errors) )
{
    echo "Error [3]: ". implode("\n", $errors);

} else {

    echo "Sectionorder has been successfully changed: ".json_encode( $aNewList ).".\n";

}
