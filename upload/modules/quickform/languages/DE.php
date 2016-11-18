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
	'SETTINGS' => 'Einstellungen',
	'SUBTITLE' => 'Untertitel',
	'TEXT_EMAIL' => 'E-mail Empf&auml;nger',
	'TEXT_FORM' => 'Formular ausw&auml;hlen',
	'TEXT_SUBJECT' => 'E-Mail Betreff',
	'TEXT_SUCCESS' => 'Erfolgreich-Seite',
	'TEXT_NOPAGE' => 'Keine separate Erfolgreich-Seite, Standardtext anzeigen',
	'TEXT_CANCEL' => 'Abbrechen',
	'TEXT_SAVE' => 'Speichern',
	'MANAGE' => 'Template anpassen',
	'SUBJECT' => 'Formular &uuml;ber die Website verschickt',
	'THANKYOU' => 'Vielen Dank f&uuml;r das Ausf&uuml;llen des Formulars.<br />Wir werden Sie schnellstm&ouml;glich kontaktieren.',
	'NOTALL' => 'Bitte f&uuml;llen Sie alle erfolderlichen Felder aus.',
	'INVALID' => 'Ung&uuml;ltiger Dateityp, kein Upload erlaubt!',
	'HISTORY' => 'Historie anzeigen',
	'RECEIVED' => 'Empfangene Nachrichten (letzte 50)',
	'MSGID' => 'ID',
	'TIMESTAMP' => 'Zeitstempel (Timestamp)',
	'REMOVE' => 'L&ouml;schen',
	'SAVEAS' => 'Vorlage speichern als',
	'E-MAIL_HEADER'	=> 	'Mitteilung'
);