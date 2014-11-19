<?php
/**
 *	about dialog
 *
 */

// header("Content-type: text/javascript; charset=utf-8");

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

/**
 *	try to read the info.php of this module.
 */
require_once( LEPTON_PATH."/modules/tiny_mce_4/info.php");

?>
<html>
<head>
<style>
h3, p {
	font-family: Verdana, sans-serif;
	font-size: 12px;
	line-height: 1.4em;
	margin-bottom: 10px;
}
.info {
	color: #990000;
	font-style: italic;
	font-weight: bold;
}
</style>
</head>
<body>
<h3>About this WYSIWYG-Editor.</h3>
<p>Module name: <span class="info"><?php echo $module_name; ?></span></p>
<p>Module version: <span class="info"><?php echo $module_version; ?></span></p>
<p>Modul description: <?php echo $module_description; ?></p>
</body>
</html>