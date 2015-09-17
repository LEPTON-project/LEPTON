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
 * Version FR elarifr accedinfo.com
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
$MF['SETTINGS'] = 'Param&eacute;tres';
$MF['SUBTITLE'] = '';
$MF['TEXT_EMAIL'] = 'Email d&eacute;stinataire';
$MF['TEXT_FORM'] = 'Choisir form.';
$MF['TEXT_SUBJECT'] = 'Sujet';
$MF['TEXT_SUCCESS'] = 'Redirection Page Envoi R&eacute;ussi';
$MF['TEXT_NOPAGE'] = 'pas de redirection de Page, utiliser le texte standard seulement';
$MF['TEXT_CANCEL'] = 'Annuller';
$MF['TEXT_SAVE'] = 'Sauvegarder';
$MF['MANAGE'] = 'Modifier template';
$MF['SUBJECT'] = 'Nouveau message Site ';
$MF['INTRO'] = 'Merci de compl&eacute;ter ce formulaire.'; 				// intro form text
$MF['MANDATORY'] = 'Les champs marqu&eacute;s * sont obligatoires';		// mandatory field
$MF['THANKYOU'] = 'Merci. Votre demande est prise en compte. Nous vous contacterons dans les plus br&eacute;fs d&eacute;lais';
$MF['NOTALL'] = 'merci de remplir tous les champs.';
$MF['INVALID'] = 'Invalid filetype, upload not accepted!';

$MF['HISTORY'] = 'Afficher historique';
$MF['RECEIVED'] = 'Messages recus (50 derniers)';
$MF['MSGID'] = 'ID';
$MF['TIMESTAMP'] = 'Horodatage';
$MF['REMOVE'] = 'Supprimmer';
$MF['SAVEAS'] = 'Enregistrer le template sous';
