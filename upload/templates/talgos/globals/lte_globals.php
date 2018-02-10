<?php

/**
 *  @template       Talgos  Backend-Theme
 *  @version        see info.php of this template
 *  @author         LEPTON project, (Jurgen Nijhuis & Ruud Eisinga, Dietrich Roland Pehlke, Bernd Michna, LEPTON project for algos theme)
 *	@copyright      2010-2018 LEPTON project 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this template
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

global $parser;
global $loader;

global $TEXT;
global $MENU;
global $OVERVIEW;
global $HEADING;
global $MESSAGE;
global $THEME;

if(file_exists(THEME_PATH."/languages/index.php"))
{
	$lang = THEME_PATH."/languages/". LANGUAGE .".php";
	require_once ( !file_exists($lang) ? THEME_PATH."/languages/EN.php" : $lang );
}

$parser->addGlobal("TEXT", $TEXT);
$parser->addGlobal("MENU", $MENU);
$parser->addGlobal("OVERVIEW", $OVERVIEW);
$parser->addGlobal("HEADING", $HEADING);
$parser->addGlobal("MESSAGE", $MESSAGE);
$parser->addGlobal("THEME", $THEME);

?>