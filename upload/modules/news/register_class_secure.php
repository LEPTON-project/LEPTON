<?php

/**
 *  @module         news
 *  @version        see info.php of this module
 *  @author         Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos), LEPTON Project
 *  @copyright      2004-2010 Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos) 
 * 	@copyright      2010-2018 LEPTON Project 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 * 
 */


$files_to_register = array(
	'add_group.php',	
	'add_post.php',
	'delete_comment.php',
	'delete_group.php',
	'modify_post.php',
	'modify_settings.php',
	'move_up.php',      
	'move_down.php',            
	'delete_post.php',
	'comment.php',
	'submit_comment.php',
	'modify_comment.php',
	'modify_group.php',
	'rss.php',
	'save_comment.php',
	'save_group.php',
	'save_post.php',
	'save_settings.php',
	'update_news_order.php'
);

LEPTON_secure::getInstance()->accessFiles( $files_to_register );

?>