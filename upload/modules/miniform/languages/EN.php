<?php

/**
 *
 *	@module			miniform
 *	@version		see info.php of this module
 *	@authors		Ruud Eisinga, LEPTON project
 *	@copyright		2012-2016 Ruud Eisinga, LEPTON project
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



$MF['MINIFORM'] = 'MiniForm';
$MF['SETTINGS'] = 'Settings';
$MF['SUBTITLE'] = '';
$MF['TEXT_EMAIL'] = 'Email receiver';
$MF['TEXT_FORM'] = 'Select form';
$MF['TEXT_SUBJECT'] = 'Email Subject';
$MF['TEXT_SUCCESS'] = 'Success page';
$MF['TEXT_NOPAGE'] = 'No successpage, standard text only';
$MF['TEXT_CANCEL'] = 'Cancel';
$MF['TEXT_SAVE'] = 'Save';
$MF['MANAGE'] = 'Modify template';
$MF['SUBJECT'] = 'Form sent by the website';
$MF['THANKYOU'] = 'Thank you for filling out this form. We will contact you ASAP';
$MF['NOTALL'] = 'Please fill all required fields.';
$MF['INVALID'] = 'Invalid filetype, upload not accepted!';

$MF['HISTORY'] = 'Show history';
$MF['RECEIVED'] = 'Received messages (last 50)';
$MF['MSGID'] = 'ID';
$MF['TIMESTAMP'] = 'Timestamp';
$MF['REMOVE'] = 'Delete';
$MF['SAVEAS'] = 'Save template as';