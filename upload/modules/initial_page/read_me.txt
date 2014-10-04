/**
 *
 * @module          initial_page
 * @author          Ralf Hertsch, Dietrich Roland Pehlke, LEPTON project 
 * @copyright       2010-2013 Ralf Hertsch, Dietrich Roland Pehlke
 * @copyright       2012-2014 LEPTON project 
 * @link            http://www.LEPTON-cms.org
 * @license         copyright, all rights reserved
 * @license_terms   please see info.php of this module
 *
 */

--------------------------------------------------------------------
0.	Typogafische Konventionen:
	Php-Code Schnippsel sind immer innerhalb von [php] ... [/php] eingefasst. d.h. 
	das die beiden Markierungen __nicht__ zum eigentlichen Codeblock mit gehören.

--------------------------------------------------------------------
1.	Anpassen der "index.php" innerhalb von "admin/start":

	Folgenden Codeblock unterhalb des ersten "require" ab Zeile 21 einfügen:

[php]
// exec initial_page 
if(file_exists(LEPTON_PATH .'/modules/initial_page/classes/c_init_page.php') && isset($_SESSION['USER_ID'])) { 
	require_once (LEPTON_PATH .'/modules/initial_page/classes/c_init_page.php'); 
	$ins = new c_init_page($database, $_SESSION['USER_ID'], $_SERVER['PHP_SELF']);
}
[/php]

	That's it.

--------------------------------------------------------------------
Dietrich Roland Pehlke
Ralf Hertsch 