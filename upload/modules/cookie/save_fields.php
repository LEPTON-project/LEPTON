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

$debug = true;

if (true === $debug) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}


if(!isset($_POST['job']) ) {
	header("Location: ".ADMIN_URL."/admintools/tool.php?tool=formbuilder&leptoken=".$_POST['leptoken']."");
	exit(0);
}

// check $_POST
if(isset($_POST['job']) && $_POST['job'] == 'save') {

	//get instance of own module class
	$oCO = cookie::getInstance();
	$admin = new LEPTON_admin('admintools', 'admintools');

	//	check, if overwrite is set	
	if(!isset($_POST['overwrite'])) {
		$_POST['overwrite'] = 0;
	} else {
		$_POST['overwrite'] = 1;
	}	
	
	//	save elements
	$request = new LEPTON_request();

	$all_values = array (
	    'position' => $_POST['position'],
		'layout' => $_POST['layout'],
		'pop_bg' => $_POST['pop_bg'],
		'pop_text' => $_POST['pop_text'],
		'but_bg' => $_POST['but_bg'],
		'but_text' => $_POST['but_text'],
		'but_border' => $_POST['but_border'],
		'type' => $_POST['type'],
		'href' => $_POST['href'],
		'message' => $_POST['message'],
		'dismiss' => $_POST['dismiss'],
		'allow' => $_POST['allow'],
		'link' => $_POST['link'],
		'overwrite' => $_POST['overwrite']
	);	

	$table = TABLE_PREFIX."mod_cookie";
	
	$result = $database->build_and_execute( 'UPDATE', $table, $all_values, 'cookie_id ='.$_POST['cookie_id'].'');

	// Check if there is a db error, else success
	if($database->is_error()) {
		$admin->print_error($database->get_error(),'save_fields.php?cookie_id='.$_POST['cookie_id']);
	} else {
		$admin->print_success($oCO->language['record_saved'], ADMIN_URL."/admintools/tool.php?tool=cookie");
	}

	// Print admin footer
	$admin->print_footer();	
}

else {	
	die($oCO->language['record_not_saved']);	
}


?>