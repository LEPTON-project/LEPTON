<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          phpmailer
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
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


//  remove phpmailer directories 
if (file_exists (LEPTON_PATH.'/modules/lib_phpmailer/docs/index.php')) {

	$to_delete = array (
	"/docs",
	"/extras",
	"/language"
	);
	 
	foreach ($to_delete as $del)
	{
		$temp_path = LEPTON_PATH ."/modules/lib_phpmailer". $del . "/index.php";


		if (file_exists($temp_path)) 
			{
			rm_full_dir( LEPTON_PATH ."/modules/lib_phpmailer".$del);
			}
	}		




	//  remove phpmailer unneeded files 
	$delete_files = array (
	"/.scrutinizer.yml",
	"/.travis.yml",
	"/changelog.md",
	"/class.phpmailer.php",
	"/class.pop3.php",
	"/class.smtp.php",
	"/composer.json",
	"/.travis.yml",
	"/docs.ini",
	"/PHPMailerAutoload.php",
	"/travis.phpunit.xml.dist",
	"/README.md",	
	"/LICENSE"	
	);
	
	foreach ($delete_files as $del_file)
	{
		$temp_path = LEPTON_PATH ."/modules/lib_phpmailer".$del_file;


		if (file_exists($temp_path)) 
			{
			rm_full_dir( LEPTON_PATH ."/modules/lib_phpmailer".$del_file );
			}
	}	
	
} // end if
?>