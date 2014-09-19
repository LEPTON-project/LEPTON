<?php

/**
 *  @module         news
 *  @version        see info.php of this module
 *  @author         Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos), LEPTON Project
 *  @copyright      2004-2010 Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos) 
 * 	@copyright      2010-2014 LEPTON Project 
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

if(defined('LEPTON_URL'))
{

  // first copy content of original news_tables to xsik_tables	
  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."xsik_news_posts`");
  $database->query("RENAME TABLE `".TABLE_PREFIX."mod_news_posts` TO `".TABLE_PREFIX."xsik_news_posts`"); 

  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."xsik_news_groups`");  
  $database->query("RENAME TABLE `".TABLE_PREFIX."mod_news_groups` TO `".TABLE_PREFIX."xsik_news_groups`");    
  
  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."xsik_news_comments`");  
  $database->query("RENAME TABLE `".TABLE_PREFIX."mod_news_comments` TO `".TABLE_PREFIX."xsik_news_comments`");    
  
  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."xsik_news_settings`");
  $database->query("RENAME TABLE `".TABLE_PREFIX."mod_news_settings` TO `".TABLE_PREFIX."xsik_news_settings`");     
  
}
?>