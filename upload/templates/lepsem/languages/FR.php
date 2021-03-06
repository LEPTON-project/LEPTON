<?php

/**
 *  @template       LEPSem
 *  @version        see info.php of this template
 *  @author         cms-lab
 *  @copyright      2014-2018 cms-lab
 *  @license        GNU General Public License
 *  @license terms  see info.php of this template
 *  @platform       see info.php of this template
 *  @requirements   PHP 5.2.x and higher
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
$template_description 	= 'Enhanced backend theme for Lepton CMS';

$THEME = array(
	'ADMIN_ONLY'		=> "Seul l'administrateur peut modifier ces réglages",
	'NO_SHOW_THUMBS'	=> 'Cacher les vignettes',
	'TEXT_HEADER'		=> "Définir la taille des images par dossier</b><br><small><i>(Redimensionnement seulement lors d'un nouvel upload)</i></small>",
	'ADDON_PERMISSIONS' => 'Addon Permissions',
	'ADDON_RELOAD' 		=> 'All addon-infos are reloaded from database.',
	'ADD_LEVEL_0' 		=> 'add level 0',
	'UPLOAD' 			=> 'upload',
	'CANNOT_DELETE'		=> 'Cannot delete User - User got statusflags 32.',
	'CREATE' 			=> 'create',	
	'DASHBOARD'			=> 'Installation Overview',
	'DISPLAY'			=> 'Display',		
	'OVERVIEW'			=> 'Pages Overview',
	'SITE_INFOS' 		=> 'Site-Statistics',
	'HELP_LINKS' 		=> 'Helpful Links',		
	'PAGE' 				=> 'Count pages',
	'PAGE_ID' 			=> 'ID',		
	'PAGE_DETAILS' 		=> 'Page Details',
	'PAGE_PERMISSION' 	=> 'Page Permissions',		
	'SECTIONS' 			=> 'Count sections',
	'MAILER_SMTP_SECURE' => 'SMTP Secure',
	'MAILER_SMTP_PORT'	=> 'SMTP Port',		
	'MODIFIED_WHEN'		=> 'Last update',
	'NEED_CURRENT_PASSWORD'	=> 'confirm changes with current password',		
	'LINK_FE' 			=> 'Link frontend',
	'LINK_BE' 			=> 'Link backend',
	'UPDATE' 			=> 'A later LEPTON version is released! Current Version: ',
	'LINK_HOME' 		=> 'For details please see ',
	'HOMEPAGE' 			=> 'LEPTON Homepage',
	'MODULES' 			=> 'Installed Modules',
	'LANGUAGES' 		=> 'Installed Languages',
	'TEMPLATES' 		=> 'Installed Templates',
	'USERS' 			=> 'Registered Users',
	'GROUPS' 			=> 'Registered Groups'	
); 
?>