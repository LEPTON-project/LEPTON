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
 * @version         $Id: search_convert.php 1172 2011-10-04 15:26:26Z frankh $
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



if (! isset ( $search_lang ))
	$search_lang = LANGUAGE;

/**
 * umlaut to '(upper|lower)' for preg_match()
 * this is UTF-8-encoded
 * there is no need for a translation-table anymore since we use u-switch (utf-8) for preg-functions
 * remember that we use the i-switch, too. [No need for (ae|Ae)]
 */

$string_ul_umlaut = array ();
$string_ul_regex = array ();

// but add some national stuff
if ($search_lang == 'DE') {
	// add special handling for german umlauts (Ã¤==ae, ...)
	$string_ul_umlaut_add = array (
		"\xc3\x9f", // german SZ-Ligatur
		"\xc3\xa4", // german ae
		"\xc3\xb6", // german oe
		"\xc3\xbc", // german ue
		"\xc3\x84", // german Ae
		"\xc3\x96", // german Oe
		"\xc3\x9c", // german Ue
		// these are not that usual
		"\xEF\xAC\x84", // german ffl-ligatur
		"ffl", // german ffl-ligatur
		"\xEF\xAC\x83", // german ffi-ligatur
		"ffi", // german ffi-ligatur
		"0xEF\xAC\x80", // german ff-Ligatur
		"ff", // german ff-Ligatur
		"\xEF\xAC\x81", // german fi-ligatur
		"fi", // german fi-ligatur
		"\xEF\xAC\x82", // german fl-ligatur
		"fl", // german fl-ligatur
		"\xEF\xAC\x85", // german st-Ligatur (long s)
		"st", // german st-Ligatur
		"\xEF\xAC\x86" // german st-ligatur (round-s) 
	); 
	
	$string_ul_regex_add = array (
		"(\xc3\x9f|ss)", // german SZ.Ligatur
		"(\xc3\xa4|ae)", // german ae
		"(\xc3\xb6|oe)", // german oe
		"(\xc3\xbc|ue)", // german ue
		"(\xc3\x84|Ae)", // german Ae
		"(\xc3\x96|Oe)", // german Oe
		"(\xc3\x9c|Ue)", // german Ue
		// these are not that usual
		"(\xEF\xAC\x84|ffl)", // german ffl-ligatur
		"(\xEF\xAC\x84|ffl)", // german ffl-ligatur
		"(\xEF\xAC\x83|ffi)", // german ffi-ligatur
		"(\xEF\xAC\x83|ffi)", // german ffi-ligatur
		"(\xEF\xAC\x80|ff)", // german ff-Ligatur
		"(\xEF\xAC\x80|ff)", // german ff-Ligatur
		"(\xEF\xAC\x81|fi)", // german fi-Ligatur
		"(\xEF\xAC\x81|fi)", // german fi-Ligatur
		"(\xEF\xAC\x82|fl)", // german fl-ligatur
		"(\xEF\xAC\x82|fl)", // german fl-ligatur
		"(\xEF\xAC\x85|st)", // german st-Ligatur (long s)
		"(\xEF\xAC\x85|st|\xEF\xAC\x86)", // german st-Ligaturs
		"(\xEF\xAC\x86|st)" // german st-ligatur (round-s) 
	); 

	$string_ul_umlaut = array_merge ( $string_ul_umlaut_add, $string_ul_umlaut );
	$string_ul_regex = array_merge ( $string_ul_regex_add, $string_ul_regex );
}

?>