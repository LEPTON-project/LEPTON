<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010, Website Baker Project
 * @copyright       2010-2011, LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @version         $Id: calc_text.php 1172 2011-10-04 15:26:26Z frankh $
 *
 */


// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
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



// Must include code to stop this file being accessed directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

if(!file_exists(WB_PATH.'/modules/captcha_control/languages/'.LANGUAGE .'.php')) {
	// no module language file exists for the language set by the user, include default module language file EN.php
	require_once(WB_PATH.'/modules/captcha_control/languages/EN.php');
} else {
	// a module language file exists for the language defined by the user, load it
	require_once(WB_PATH.'/modules/captcha_control/languages/'.LANGUAGE .'.php');
}

$_SESSION['captcha'.$sec_id] = '';
mt_srand((double)microtime()*1000000);
$n = mt_rand(1,3);
switch ($n) {
	case 1:
		$x = mt_rand(1,9);
		$y = mt_rand(1,9);
		$_SESSION['captcha'.$sec_id] = $x + $y;
		$cap = "$x {$MOD_CAPTCHA['ADDITION']} $y"; 
		break; 
	case 2:
		$x = mt_rand(10,20);
		$y = mt_rand(1,9);
		$_SESSION['captcha'.$sec_id] = $x - $y; 
		$cap = "$x {$MOD_CAPTCHA['SUBTRAKTION']} $y"; 
		break;
	case 3:
		$x = mt_rand(2,10);
		$y = mt_rand(2,5);
		$_SESSION['captcha'.$sec_id] = $x * $y; 
		$cap = "$x {$MOD_CAPTCHA['MULTIPLIKATION']} $y"; 
		break;
}
echo $cap;
?>