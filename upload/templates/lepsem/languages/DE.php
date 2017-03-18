<?php

/**
 *  @template       LEPSem
 *  @version        see info.php of this template
 *  @author         cms-lab
 *  @copyright      2014-2017 cms-lab
 *  @license        GNU General Public License
 *  @license terms  see info.php of this template
 *  @platform       see info.php of this template
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

//	Template/Theme Description
$template_description 	= 'Ein erweitertes Backend-Theme für LEPTON CMS.';

$THEME = array(
	'ADMIN_ONLY' 		=> 'Diese Optionen nur Administratoren zugänglich machen!',
	'NO_SHOW_THUMBS' 	=> 'Vorschaubilder verstecken',
	'TEXT_HEADER' 		=> 'Maximale Bildergröße fürr Ordner festlegen</b><br><small><i>(Änderung nur beim Hochladen)</i></small>',
	'ADDON_PERMISSIONS' => 'Zugriffsrechte für Addons',
	'ADD_LEVEL_0' 		=> 'auch Ebene 0',
	'UPLOAD' 			=> 'hochladen',
	'CREATE' 			=> 'erstellen',		
	'DASHBOARD'			=> 'Überblick über die Installation',
	'SITE_INFOS' 		=> 'Seiten-Statistik',
	'HELP_LINKS' 		=> 'Hilfreiche Links',			
	'PAGE' 				=> 'Anzahl Seiten',
	'PAGE_ID' 			=> 'ID',	
	'PAGE_DETAILS' 		=> 'Seiten Details',
	'PAGE_PERMISSION' 	=> 'Seiten Berechtigung',	
	'SECTIONS' 			=> 'Anzahl Sektionen',
	'MODIFIED_WHEN'		=> 'Letzte Änderung',
	'NEED_CURRENT_PASSWORD'	=> 'Änderungen mit aktuellem Passwort bestätigen',	
	'LINK_FE' 			=> 'Link Frontend',
	'LINK_BE' 			=> 'Link Backend',
	'UPDATE' 			=> 'Eine neuere LEPTON Version ist verfügbar! Aktuelle Version: ',
	'LINK_HOME' 		=> 'Weitere Informationen auf der ',
	'HOMEPAGE' 			=> 'LEPTON Homepage',
	'MODULES' 			=> 'Installierte Module',
	'LANGUAGES' 		=> 'Installierte Sprachen',
	'TEMPLATES' 		=> 'Installierte Templates',
	'USERS' 			=> 'Eingerichtete User',
	'GROUPS' 			=> 'Eingerichtete Gruppen'
); 

?>