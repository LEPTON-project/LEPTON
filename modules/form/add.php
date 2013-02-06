<?php

/**
 *  @module         form
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Rudolph Lartey, John Maats, Dietrich Roland Pehlke 
 *  @copyright      2004-2011 Ryan Djurovich, Rudolph Lartey, John Maats, Dietrich Roland Pehlke 
 *  @license        see info.php of this module
 *  @license terms  see info.php of this module
 *  @requirements   PHP 5.2.x and higher
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
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



// Insert an extra rows into the database
$header = '<table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" width=\"98%\">';
$field_loop = '<tr><td class=\"field_title\">{TITLE}{REQUIRED}:</td><td>{FIELD}</td></tr>';
$footer = '<tr><td>&nbsp;</td>
<td>
<input type=\"submit\" name=\"submit\" value=\"Submit Form\" />
</td>
</tr>
</table>';
$email_to = $admin->get_email();
$email_from = '';
$email_fromname = '';
$email_subject = 'Results from form on website...';
$success_page = 'none';
$success_email_to = '';
$success_email_from = $admin->get_email();
$success_email_fromname = '';
$success_email_text = 'Thank you for submitting your form on '.WEBSITE_TITLE;
$success_email_text = addslashes($success_email_text);
$success_email_subject = 'You have submitted a form';
$max_submissions = 50;
$stored_submissions = 50;
$use_captcha = true;
$database->query("INSERT INTO ".TABLE_PREFIX."mod_form_settings (page_id,section_id,header,field_loop,footer,email_to,email_from,email_fromname,email_subject,success_page,success_email_to,success_email_from,success_email_fromname,success_email_text,success_email_subject,max_submissions,stored_submissions,use_captcha) VALUES ('$page_id','$section_id','$header','$field_loop','$footer','$email_to','$email_from','$email_fromname','$email_subject','$success_page','$success_email_to','$success_email_from','$success_email_fromname','$success_email_text','$success_email_subject','$max_submissions','$stored_submissions','$use_captcha')");

?>