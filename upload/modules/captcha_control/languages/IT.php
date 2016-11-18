<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 *
 * @author          Thomas Hornik (thorn),LEPTON Project
 * @copyright       2008-2010, Thomas Hornik (thorn)
 * @copyright       2010-2017  LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

/*
 -----------------------------------------------------------------------------------------
  LINGUA ITALIANA PER IL CAPTCHA-CONTROL ADMINISTRATIONS TOOL
 -----------------------------------------------------------------------------------------
*/

// Descrizione italiana del modulo
$module_description 	= 'Admin-Tool per controllare il comportamento di CAPTCHA e ASP';

// Capitoli e testo
$MOD_CAPTCHA_CONTROL['HEADING']           = 'Controllo Captcha e ASP';
$MOD_CAPTCHA_CONTROL['HOWTO']             = 'Qui si pu&ograve; controllare il comportamento di "CAPTCHA" e "Advanced Spam Protection" (ASP). Per rendere operativo ASP in un modulo, il modulo stesso dovr&agrave; essere adattato di conseguenza.';

// Text and captions of form elements
$MOD_CAPTCHA_CONTROL['CAPTCHA_CONF']      = 'Impostazioni CAPTCHA';
$MOD_CAPTCHA_CONTROL['CAPTCHA_TYPE']      = 'Tipo di CAPTCHA';
$MOD_CAPTCHA_CONTROL['CAPTCHA_EXP']       = 'Le impostazioni CAPTCHA per il modulo si trovano nelle opzioni relative al modulo stesso.';
$MOD_CAPTCHA_CONTROL['USE_SIGNUP_CAPTCHA']= 'Attivare il CAPTCHA per la registrazione';
$MOD_CAPTCHA_CONTROL['ENABLED']           = 'Attivato';
$MOD_CAPTCHA_CONTROL['DISABLED']          = 'Disattivato';
$MOD_CAPTCHA_CONTROL['ASP_CONF']          = 'Impostazioni avanzate per la protezione Spam (ASP)';
$MOD_CAPTCHA_CONTROL['ASP_TEXT']          = 'Utilizzare ASP (se presente nel modulo)';
$MOD_CAPTCHA_CONTROL['ASP_EXP']           = 'ASP prova a riconoscere in base al comportamento, se un inserimento nel formulario deriva da un umano o da un Bot (Spam).';
$MOD_CAPTCHA_CONTROL['CALC_TEXT']         = 'Calcolo come testo';
$MOD_CAPTCHA_CONTROL['CALC_IMAGE']        = 'Calcolo come immagine';
$MOD_CAPTCHA_CONTROL['CALC_TTF_IMAGE']    = 'Calcolo come immagine con schritte e sfondi variabili';
$MOD_CAPTCHA_CONTROL['TTF_IMAGE']         = 'Immagine con schritte e sfondi variabili';
$MOD_CAPTCHA_CONTROL['OLD_IMAGE']         = 'Vecchio stile (non consigliato)';
$MOD_CAPTCHA_CONTROL['TEXT']              = 'Testo del CAPTCHA';
$MOD_CAPTCHA_CONTROL['CAPTCHA_ENTER_TEXT']= 'Domande e risposte';
$MOD_CAPTCHA_CONTROL['CAPTCHA_TEXT_DESC'] = 'Prego, cancellare tutto'."\n".'altrimenti non vengono salvati i tuoi cambiamenti!'."\n".'### Esempio ###'."\n".'Qui puoi inserire le tue domande e risposte.'."\n".'Oppure:'."\n".'?Come &egrave; il nome di Claudia Schiffer?'."\n".'!Claudia'."\n".'?Domanda 2'."\n".'!Risposta 2'."\n".' ... '."\n".'quando viene utilizzata soltanto una lingua.'."\n".''."\n".'o quando la lingua è rilevante:'."\n".'?EN:What\'s Claudia Schiffer\'s first name?'."\n".'!Claudia'."\n".'?EN:Question 2'."\n".'!Answer 2'."\n".'?IT:Come &egrave; il nome di Claudia Schiffer?'."\n".'!Claudia'."\n".' ... '."\n".'### Esempio ###'."\n".'';
$MOD_CAPTCHA['VERIFICATION']           = 'Protezione Spam';
$MOD_CAPTCHA['ADDITION']               = 'pi&ugrave;';
$MOD_CAPTCHA['SUBTRAKTION']            = 'meno';
$MOD_CAPTCHA['MULTIPLIKATION']         = 'per';
$MOD_CAPTCHA['VERIFICATION_INFO_RES']  = 'Per favore inserire il risultato';
$MOD_CAPTCHA['VERIFICATION_INFO_TEXT'] = 'Per favore inserire il testo';
$MOD_CAPTCHA['VERIFICATION_INFO_QUEST'] = 'Per favore rispondere alla domanda';
$MOD_CAPTCHA['INCORRECT_VERIFICATION'] = 'Il risultato &egrave; sbagliato; si prega di inserirlo nuovamente';

?>
