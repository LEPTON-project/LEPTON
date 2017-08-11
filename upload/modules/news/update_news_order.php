<?php

/**
 *  @module         news
 *  @version        see info.php of this module
 *  @author         Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos), LEPTON Project
 *  @copyright      2004-2010 Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos) 
 * 	@copyright      2010-2017 LEPTON Project 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
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

if(!isset($_POST['news'])) die("E1");

$sNewsList = trim( $_POST['news'] );

if( $sNewsList == "" ) die("E2");

$aNewsIds = explode(",", $sNewsList);

if(0 === count($aNewsIds))
{
    return "Error [1]: no Items in list.";
}

/**
 * Get the correct offset.
 * Keep in mind that this could be > 1 if there are more than one 
 * pages are displayed in the backend-interface! 
 *
 */
$offset = intval( $database->get_one( "SELECT MIN(`position`) from `".TABLE_PREFIX."mod_news_posts` WHERE `post_id` IN (".(implode(",",$aNewsIds)).")" ) );

// Make sure that the offset is > 0 (e.g. the query above faild and the offset is 0)
if($offset < 1)
{
    $offset = 1;
}

$errors = array();
$position = $offset; // by default 1;

foreach($aNewsIds as $post_id)
{
    $fields = array(
        'position'  => $position++
    );

    $database->build_and_execute(
        'update',
        TABLE_PREFIX."mod_news_posts",
        $fields,
        "`post_id`=".intval( $post_id )
    );

    if(true == $database->is_error())
    {
        $errors[]=$database->get_error();
    }
}

if( 0 > count($errors) )
{
    echo "Error [3]: ". implode("\n", $errors);

} else {

    echo "Order if the news has been successfully changed: ".json_encode( $aNewsIds ).".\n";

}
