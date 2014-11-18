<?php
/**
 *	Droplet's dialog
 *
 */

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

$str = "";
foreach($all_droplets as &$d){
	$str .= "<p>".$d['name']."</p>";
}

echo $str;

?>