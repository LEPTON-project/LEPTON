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

global $lepton_filemanager;
if (!is_object($lepton_filemanager)) require_once( "../../framework/class.lepton.filemanager.php" );


$files_to_register = array(
	'/modules/news/add_group.php',	
	'/modules/news/add_post.php',
	'/modules/news/delete_comment.php',
	'/modules/news/delete_group.php',
	'/modules/news/modify_post.php',
	'/modules/news/modify_settings.php',
	'/modules/news/move_up.php',      
	'/modules/news/move_down.php',            
	'/modules/news/delete_post.php',
	'/modules/news/comment.php',
	'/modules/news/submit_comment.php',
	'/modules/news/modify_comment.php',
	'/modules/news/modify_group.php',
	'/modules/news/rss.php',
	'/modules/news/save_comment.php',
	'/modules/news/save_group.php',
	'/modules/news/save_post.php',
	'/modules/news/save_settings.php'
);

$lepton_filemanager->register( $files_to_register );

?>