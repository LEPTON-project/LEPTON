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
	"SELECT `name`,`description`,`comments` from `".TABLE_PREFIX."mod_droplets` ORDER By `name`",
	true,
	$all_droplets
);

function droplets_cleanup_str(&$str) {
	$str = addslashes($str);
	$str = str_replace(
		array("\n", "\n\r", "\r"),
		"\\n",
		$str
	);
}

$droplets_values = "var dropletsvalues = [\n";
$droplets_info = "var dropletsinfo = [\n";

foreach($all_droplets as &$d){
	droplets_cleanup_str($d['description']);
	droplets_cleanup_str($d['comments']);
	
	$droplets_values .= "{ text:'".$d['name']."', value:'".$d['name']."'},\n";
	$droplets_info .= "{ text:'".$d['name']."', desc:'".$d['description']."', comment:'".$d['comments']."'},\n";
}

$droplets_values = substr($droplets_values, 0, -2)."\n];\n";
$droplets_info = substr($droplets_info, 0, -2)."\n];\n";

echo $droplets_values;
echo $droplets_info;

?>