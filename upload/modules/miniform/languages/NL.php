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
$MF['SETTINGS'] = 'Instellingen';
$MF['SUBTITLE'] = '';
$MF['TEXT_EMAIL'] = 'Email ontvanger';
$MF['TEXT_FORM'] = 'Kies formulier';
$MF['TEXT_SUBJECT'] = 'Email Onderwerp';
$MF['TEXT_SUCCESS'] = 'Succes pagina';
$MF['TEXT_NOPAGE'] = 'Geen succespagina, gebruik de standaard tekst.';
$MF['TEXT_CANCEL'] = 'Afbreken';
$MF['TEXT_SAVE'] = 'Opslaan';
$MF['MANAGE'] = 'Bewerk template';
$MF['SUBJECT'] = 'Formulier vanuit de website';
$MF['THANKYOU'] = 'Bedankt voor uw reaktie. We zullen zo spoedig mogelijk contact met u opnemen.';
$MF['NOTALL'] = 'Vul a.u.b. de verplichte velden in.';
$MF['INVALID'] = 'Ongeldig bestandstype, bestand niet geaccepteerd!';

$MF['HISTORY'] = 'Bekijk eerdere inzendingen';
$MF['RECEIVED'] = 'Inzendingen (Laatste 50)';
$MF['MSGID'] = 'ID';
$MF['TIMESTAMP'] = 'Datum / Tijd';
$MF['REMOVE'] = 'Verwijderen';
$MF['SAVEAS'] = 'Sla template op als';