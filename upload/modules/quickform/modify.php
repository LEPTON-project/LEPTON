<?php

/**
 *
 *	@module			quickform
 *	@version		see info.php of this module
 *	@authors		Ruud Eisinga, LEPTON project
 *	@copyright		2012-2017 Ruud Eisinga, LEPTON project
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {
	include(LEPTON_PATH.'/framework/class.secure.php');
} else {
	$root = "../";
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= "../";
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) {
		include($root.'/framework/class.secure.php');
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php 

//	load the correct language-file
require_once (LEPTON_PATH."/modules/quickform/register_language.php");

/**	
 *	get the template-engine.
 */
global $parser, $loader, $TEXT, $MOD_QUICKFORM;
require( dirname(__FILE__)."/register_parser.php" );

require_once (LEPTON_PATH."/modules/quickform/classes/class.qform.php");

$qform = new qForm();
$d = 0;
if(isset($_GET['delete'])) {
	$d = intval($_GET['delete']);
	$qform->delete_record($d);
}
if (!isset($links)) {
	$links = array();
	$qform->build_pagelist(0,$page_id);
	$all_links = array();
	foreach($links as $l){
		$temp = explode("|", $l);
		$all_links [] = array(
			'page_id'	 => $temp[0] ,
			'page_titel' => $temp[1]
		);
	}
}
$sel = ' selected="selected"';

$settings = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."mod_quickform` WHERE `section_id` = ".$section_id,
	true,
	$settings,
	false
);

if(!isset($settings['email'])) $settings['email'] = SERVER_EMAIL;
if(!isset($settings['subject'])) $settings['subject'] = $MOD_QUICKFORM['SUBJECT'];

$leptoken = (isset($_GET['leptoken']) ? $_GET['leptoken'] : NULL);
if(NULL === $leptoken) {
	$leptoken = (isset($_GET['amp;leptoken']) ? $_GET['amp;leptoken'] : NULL);
}

$manage_url = LEPTON_URL.'/modules/quickform/modify_template.php?page_id='.$page_id.'&section_id='.$section_id.'&leptoken='.$leptoken.'&name=';
$delete_url = ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id.'&delete=';

/**
 *	Get the page-language
 */
$page_language = $database->get_one("SELECT `language` FROM `".TABLE_PREFIX."pages` WHERE `page_id`=".$page_id);

/**
 *	Find all "sub" directorys with exact 2 chars.
 *	aldus:	experimental for next use - get alternative "template"-directory
 */

$look_up_dir = __DIR__."/templates/".strtolower($page_language);
$use_template_dir = is_dir($look_up_dir)
	? $look_up_dir
	: __DIR__."/templates/en"
	;

if(!function_exists('file_list')) require_once( LEPTON_PATH."/framework/functions/function.file_list.php");
$all_template_files = file_list( 
	$use_template_dir,	//	Pfad! (Aldus, 2016-05-13)
	NULL,
	NULL,
	"lte",
	$use_template_dir."/"	// Pfad!
);

// leptoken
$get_leptoken = get_leptoken();

// Additional marker settings
$form_values = array(
	'action'		=> LEPTON_URL."/modules/quickform/save.php",
	'del'			=> $d,		
	'manage_url'	=> $manage_url,
	'delete_url'	=> $delete_url,
	'section_id'	=> $section_id,
	'page_id'		=> $page_id,
	'leptoken'		=> $get_leptoken,
	'manage_url_settings'	=> $manage_url.$settings['template'],
	'settings_email'		=> $settings['email'],
	'settings_subject'		=> $settings['subject'],
	'template'				=> $settings['template'],
	'successpage'			=> $settings['successpage'],
	'THEME_URL' 	=> THEME_URL,	
	'ADMIN_URL' 	=> ADMIN_URL,
	'MOD_QUICKFORM'	=> $MOD_QUICKFORM,
	'history'		=> $qform->get_history($section_id,50),
	'all_template_files'	=> $all_template_files,
	'all_links'	=> $all_links
);

$twig_util->resolve_path("modify.lte");

echo $parser->render(
	$twig_modul_namespace.'modify.lte',
	$form_values
);
?>