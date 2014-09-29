<?php

/**
 *  @module         form
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Rudolph Lartey, John Maats, Dietrich Roland Pehlke, LEPTON project
 *  @copyright      2004-2010 Ryan Djurovich, Rudolph Lartey, John Maats, Dietrich Roland Pehlke 
 *  @copyright      2010-2014 LEPTON project  
 *  @license        see info.php of this module
 *  @license terms  see info.php of this module
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



//Modul Description
$module_description = 'Med denne modulen kan du lage d&iacute;ne egne spesialtilpassede elektroniske skjemaer, som for eksempe et tilbakemeldings skjema. En stor takk til Rudolph Lartey for hjelpen med &aring; videreutvikkle denne modulen, og for bidrag med koding av ekstra felt typer , osv.';
  
//Variables for the  backend
$MOD_FORM['SETTINGS'] = 'Skjema Innstillinger';
  
?>