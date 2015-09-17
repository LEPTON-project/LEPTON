<?php

/**
 *
 *	@module			miniform
 *	@version		see info.php of this module
 *	@authors		Ruud Eisinga, LEPTON project
 *	@copyright		2012-2015 Ruud Eisinga, LEPTON project
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
$MF['SETTINGS'] = 'Einstellungen';
$MF['SUBTITLE'] = 'Untertitel';
$MF['TEXT_EMAIL'] = 'E-mail Empf&auml;nger';
$MF['TEXT_FORM'] = 'Formular ausw&auml;hlen';
$MF['TEXT_SUBJECT'] = 'E-Mail Betreff';
$MF['TEXT_SUCCESS'] = 'Erfolgreich-Seite';
$MF['TEXT_NOPAGE'] = 'Keine separate Erfolgreich-Seite, Standardtext anzeigen';
$MF['TEXT_CANCEL'] = 'Abbrechen';
$MF['TEXT_SAVE'] = 'Speichern';
$MF['MANAGE'] = 'Template anpassen';
$MF['SUBJECT'] = 'Formular &uuml;ber die Website verschickt';
$MF['THANKYOU'] = 'Vielen Dank f&uuml;r das Ausf&uuml;llen des Formulars. Wir werden Sie schnellstm&ouml;glich kontaktieren.';
$MF['NOTALL'] = 'Bitte f&uuml;llen Sie alle erfolderlichen Felder aus.';
$MF['INVALID'] = 'Ung&uuml;ltiger Dateityp, kein Upload erlaubt!';

$MF['HISTORY'] = 'Historie anzeigen';
$MF['RECEIVED'] = 'Empfangene Nachrichten (letzte 50)';
$MF['MSGID'] = 'ID';
$MF['TIMESTAMP'] = 'Zeitstempel (Timestamp)';
$MF['REMOVE'] = 'L&ouml;schen';
$MF['SAVEAS'] = 'Vorlage speichern als';