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

$MOD_QUICKFORM = array(
	'QUICKFORM' => 'QuickForm',
	'SETTINGS' => 'Settings',
	'SUBTITLE' => '',
	'TEXT_EMAIL' => 'Email receiver',
	'TEXT_FORM' => 'Select form',
	'TEXT_SUBJECT' => 'Email Subject',
	'TEXT_SUCCESS' => 'Success page',
	'TEXT_NOPAGE' => 'No successpage, standard text only',
	'TEXT_CANCEL' => 'Cancel',
	'TEXT_SAVE' => 'Save',
	'MANAGE' => 'Modify template',
	'SUBJECT' => 'Form sent by the website',
	'THANKYOU' => 'Thank you for filling out this form.<br />We will contact you ASAP',
	'NOTALL' => 'Please fill all required fields.',
	'INVALID' => 'Invalid filetype, upload not accepted!',

	'HISTORY' => 'Show history',
	'RECEIVED' => 'Received messages (last 50)',
	'MSGID' => 'ID',
	'TIMESTAMP' => 'Timestamp',
	'REMOVE' => 'Delete',
	'SAVEAS' => 'Save template as',
	'E-MAIL_HEADER'	=> 	'Message from Website'
);