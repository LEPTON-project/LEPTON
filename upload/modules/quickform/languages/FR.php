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

$MOD_QUICKFORM = array(
	'QUICKFORM' => 'QuickForm',
	'SETTINGS' => 'Param&eacute;tres',
	'SUBTITLE' => '',
	'TEXT_EMAIL' => 'Email d&eacute;stinataire',
	'TEXT_FORM' => 'Choisir form.',
	'TEXT_SUBJECT' => 'Sujet',
	'TEXT_SUCCESS' => 'Redirection Page Envoi R&eacute;ussi',
	'TEXT_NOPAGE' => 'pas de redirection de Page, utiliser le texte standard seulement',
	'TEXT_CANCEL' => 'Annuller',
	'TEXT_SAVE' => 'Sauvegarder',
	'MANAGE' => 'Modifier template',
	'SUBJECT' => 'Nouveau message Site ',
	'INTRO' => 'Merci de compl&eacute;ter ce formulaire.', 				// intro form text
	'MANDATORY' => 'Les champs marqu&eacute;s * sont obligatoires',		// mandatory field
	'THANKYOU' => 'Merci. Votre demande est prise en compte.<br />Nous vous contacterons dans les plus br&eacute;fs d&eacute;lais',
	'NOTALL' => 'merci de remplir tous les champs.',
	'INVALID' => 'Invalid filetype, upload not accepted!',
	'HISTORY' => 'Afficher historique',
	'RECEIVED' => 'Messages recus (50 derniers)',
	'MSGID' => 'ID',
	'TIMESTAMP' => 'Horodatage',
	'REMOVE' => 'Supprimmer',
	'SAVEAS' => 'Enregistrer le template sous',
	'E-MAIL_HEADER'	=> 	'Notification'
);
