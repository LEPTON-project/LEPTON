<?php

/**
 * @module          Cookie
 * @author          cms-lab
 * @copyright       2017-2018 cms-lab
 * @link            http://www.cms-lab.com
 * @license         custom license: http://cms-lab.com/_documentation/cookie/license.php
 * @license_terms   see: http://cms-lab.com/_documentation/cookie/license.php
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

//	create table
$table = TABLE_PREFIX .'mod_cookie'; 
$database->simple_query("CREATE TABLE IF NOT EXISTS `".$table."`  (
  `cookie_id` int NOT NULL AUTO_INCREMENT,
  `pop_bg` varchar(16) NOT NULL DEFAULT '#aaa',
  `pop_text` varchar(16) NOT NULL DEFAULT '#fff',
  `but_bg` varchar(16) NOT NULL DEFAULT 'transparent',
  `but_text` varchar(16) NOT NULL DEFAULT '#fff',
  `but_border` varchar(16) NOT NULL DEFAULT '#fff',
  `position` varchar(32) NOT NULL DEFAULT 'bottom-left',
  `layout` varchar(32) NOT NULL DEFAULT 'classic',  
  `type` varchar(32) NOT NULL DEFAULT 'show',
  `overwrite` tinyint(1) NOT NULL DEFAULT '0',
  `message` varchar(512) NOT NULL DEFAULT 'here the message text',
  `dismiss` varchar(128) NOT NULL DEFAULT 'Agree',
  `allow` varchar(128) NOT NULL DEFAULT 'Accept',
  `deny` varchar(128) NOT NULL DEFAULT 'Deny',  
  `link` varchar(64) NOT NULL DEFAULT 'policy link',
  `href` varchar(256) NOT NULL DEFAULT 'http://cms-lab.com',
	PRIMARY KEY ( `cookie_id` )
		)"
);

// check for errors
if ($database->is_error()) {
	$admin->print_error($database->get_error());
}

// insert some default values
$database->simple_query("INSERT INTO ".TABLE_PREFIX."mod_cookie
VALUES (NULL, '#aaa', '#fff', 'transparent', '#fff', '#fff', 'bottom-left', 'classic', 'show', 0, 'here the message text', 'Agree', 'Accept', 'Deny', 'policy link','http://cms-lab.com')");


// import default droplets
if (!function_exists('droplet_install'))
{
	include_once LEPTON_PATH.'/modules/droplets/functions.php';
}
if (file_exists(dirname(__FILE__) . '/install/droplet_site_cookie.zip'))
{
	droplet_install(dirname(__FILE__) . '/install/droplet_site_cookie.zip', LEPTON_PATH . '/temp/unzip/');

}

?>
