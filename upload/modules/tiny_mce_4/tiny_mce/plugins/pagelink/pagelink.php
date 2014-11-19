<?php
/**
 *	Pagelink
 *
 */

header("Content-type: text/javascript; charset=utf-8");

if (defined('LEPTON_PATH')) {   
   include(LEPTON_PATH.'/framework/class.secure.php');
} else {
   $oneback = "../";
   $root = $oneback;
   $level = 1;
   while (($level < 15) && (!file_exists($root.'/framework/class.secure.php'))) {
      $root .= $oneback;
      $level += 1;
   }
   if (file_exists($root.'/framework/class.secure.php')) {
      include($root.'/framework/class.secure.php');
   } else {
      trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
   }
}

global $database;

require_once( LEPTON_PATH."/framework/functions/function.page_tree.php" );

$all_pages = array();
page_tree( 0, $all_pages);

/*
echo "<pre>";
print_r($all_pages);
echo "</pre>";
*/

function pagelink_makePageLink( &$aList, &$aStr, $rec_deep) {
	$prefix = "";
	for($i=0; $i<$rec_deep; $i++) $prefix .= "- ";
	 
	foreach($aList as $ref) {
		$aStr .= "{ text: '".$prefix.addslashes($ref['page_title'])."', value: '".$ref['page_id']."'},\n";
		
		pagelink_makePageLink( $ref['subpages'], $aStr, $rec_deep+1);
	}
}

$page_list = "var pagelist = [\n";

pagelink_makePageLink( $all_pages, $page_list, 0 );

$page_list = substr($page_list, 0, -2)."\n];\n";

echo $page_list;

/*
$all_droplets = array();
$database->execute_query(
	"SELECT `name`,`description`,`comments` from `".TABLE_PREFIX."mod_droplets` ORDER By `name`",
	true,
	$all_droplets
);

$droplets_values = "var dropletsvalues = [\n";
$droplets_info = "var dropletsinfo = [\n";

foreach($all_droplets as &$d){
	$droplets_values .= "{ text:'".$d['name']."', value:'".$d['name']."'},\n";
	$droplets_info .= "{ text:'".$d['name']."', desc:'".$d['description']."', comment:'".$d['comments']."'},\n";
}

$droplets_values = substr($droplets_values, 0, -2)."\n];\n";
$droplets_info = substr($droplets_info, 0, -2)."\n];\n";

echo $droplets_values;
echo $droplets_info;
*/
?>