<?php

/**
 * @module          Cookie
 * @author          cms-lab
 * @copyright       2017-2018 cms-lab
 * @link            http://www.cms-lab.com
 * @license         custom license: http://cms-lab.com/_documentation/cookie/license.php
 * @license_terms   see: http://cms-lab.com/_documentation/cookie/license.php
 *
 */

 // include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {   
   include(LEPTON_PATH.'/framework/class.secure.php');
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

$MOD_COOKIE = array(
	'allow'				=> "Ich stimme zu",
	'allow_label'		=> "Button Erlaubnis",	
	'banner_background'	=> "Banner Farbe",
	'banner_text'		=> "Banner Text-Farbe",
	'button_background'	=> "Button Farbe",
	'button_text'		=> "Button Text-Farbe",	
	'button_border'		=> "Button Linien-Farbe",
	'dismiss'			=> "Akzeptiert!",
	'dismiss_label'		=> "Button Notiz",	
	'info'				=> "Addon Info",	
	'layout'			=> "Layout",
	'learn_more'		=> "Weitere Infos",
	'learn_more_label'	=> "Info-link",	
	'message'			=> "Diese Webseite benutzt Cookies. Wenn Sie auf dieser Seite browsen, akzeptieren sie die Nutzung von Cookies!",
	'message_label'		=> "Information",	
	'overwrite'			=> "Sprachdatei überschreiben (nicht bei mehrsprachigen Seiten)",
	'policy_link'		=> "Link zu Datenschutzinfo",
	'position'			=> "Position",
	'record_not_saved'	=> "Datensatz wurde nicht gespeichert!",		
	'record_saved'		=> "Datensatz wurde gespeichert",	
	'type'				=> "Typ",
	'type_text1'		=> "User informieren, dass wir Cookies setzen",
	'type_text2'		=> "User können Cookies verhindern (Erweitert)",
	'type_text3'		=> "User sollen der Nutzung von Cookies zustimmen (Erweitert)",
	'type_text_message1'	=> "Link für weitere Infos",	
	'type_text_message2'	=> "Du musst die Seite anpassen, wenn du die 'erweiterten Optionen' nutzen willst!"	
);



?>