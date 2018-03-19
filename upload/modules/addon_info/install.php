<?php

/**
 *	@module			addon_info
 *	@version		see info.php of this module
 *	@author			cms-lab
 *	@copyright		2017-2018 cms-lab
 *	@license		GNU General Public License
 *	@license_terms	please see info.php of this module 
 *	@platform		see info.php of this module
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

// droplet for frontend output
LEPTON_handle::install_droplets('pw_generator', 'droplet_pw-frontend');

//enable custom frontend styling for droplet output
if(file_exists(LEPTON_PATH.'/modules/pw_generator/css/frontend_default.css')) {
	rename(LEPTON_PATH.'/modules/pw_generator/css/frontend_default.css', LEPTON_PATH.'/modules/pw_generator/css/frontend.css');
}
?>