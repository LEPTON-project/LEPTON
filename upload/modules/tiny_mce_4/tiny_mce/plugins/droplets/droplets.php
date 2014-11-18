<?php
/**
 *	Droplet's dialog
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

$all_droplets = array();
$database->execute_query(
	"SELECT `name`,`description` from `".TABLE_PREFIX."mod_droplets` ORDER By `name`",
	true,
	$all_droplets
);

$str = "var dropletsvalues = [\n";
foreach($all_droplets as &$d){
	$str .= "{ text:'".$d['name']."', value:'".$d['name']."'},\n";
}
$str = substr($str, 0, -2)."\n];\n";

echo $str;

?>