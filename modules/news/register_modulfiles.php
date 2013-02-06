<?php
/**
 *	Register module spezific files to the backend.
 *
 */
 
if(count( get_included_files() ) != 3 ) die();

global $lepton_filemanager;
if (!is_object($lepton_filemanager)) require_once( "../../framework/class.lepton.filemanager.php" );

/**
 *	List of the files to register
 *
 */
$form_backend_files = array(
	'/modules/news/add_group.php',
	'/modules/news/modify_group.php',
	'/modules/news/save_group.php',
	'/modules/news/save_settings.php',
	'/modules/news/delete_group.php',
	'/modules/news/modify_post.php',
	'/modules/news/move_up.php',      
	'/modules/news/move_down.php',            
	'/modules/news/save_post.php',
	'/modules/news/delete_post.php',
	'/modules/news/comment.php',
	'/modules/news/submit_comment.php',
	'/modules/news/modify_comment.php',
	'/modules/news/save_comment.php',
	'/modules/news/delete_comment.php',
	'/modules/news/add_post.php',
	'/modules/news/modify_settings.php',
	'/modules/news/rss.php'
);

$lepton_filemanager->register( $form_backend_files );

?>