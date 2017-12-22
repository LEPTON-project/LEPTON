<?php

/**
 *
 *	@module			quickform
 *	@version		see info.php of this module
 *	@authors		Ruud Eisinga, LEPTON project
 *	@copyright		2012-2018 Ruud Eisinga, LEPTON project
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
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
	'SETTINGS' => 'Instellingen',
	'SUBTITLE' => '',
	'TEXT_EMAIL' => 'Email ontvanger',
	'TEXT_FORM' => 'Kies formulier',
	'TEXT_SUBJECT' => 'Email Onderwerp',
	'TEXT_SUCCESS' => 'Succes pagina',
	'TEXT_NOPAGE' => 'Geen succespagina, gebruik de standaard tekst.',
	'TEXT_CANCEL' => 'Afbreken',
	'TEXT_SAVE' => 'Opslaan',
	'MANAGE' => 'Bewerk template',
	'SUBJECT' => 'Formulier vanuit de website',
	'THANKYOU' => 'Bedankt voor uw reaktie.<br />We zullen zo spoedig mogelijk contact met u opnemen.',
	'NOTALL' => 'Vul a.u.b. de verplichte velden in.',
	'INVALID' => 'Ongeldig bestandstype, bestand niet geaccepteerd!',
	'HISTORY' => 'Bekijk eerdere inzendingen',
	'RECEIVED' => 'Inzendingen (Laatste 50)',
	'MSGID' => 'ID',
	'TIMESTAMP' => 'Datum / Tijd',
	'REMOVE' => 'Verwijderen',
	'SAVEAS' => 'Sla template op als',
	'E-MAIL_HEADER'	=> 	'Bericht'
);