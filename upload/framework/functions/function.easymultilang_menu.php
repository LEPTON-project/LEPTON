<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		easymultilang_menu
 * @author          LEPTON Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {
	include(LEPTON_PATH.'/framework/class.secure.php');
} else {
	$root = "../";
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= "../";
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) {
		include($root.'/framework/class.secure.php');
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php

	/**
	 *	Function easymultilang_menu.
	 *
	 *	@param	bool returns 
	 * 			case true = a list of langauge and name
	 *			case false = generates default string 
	 *	@return	str	The generated menu HTML code	 
	 *
	 */
	function easymultilang_menu($list = false )
	{
		global $database;
		
		$langarr  = array( );
		$classarr = array( );
		
		$query = "select * FROM " . TABLE_PREFIX . "pages where page_id = '" . PAGE_ID . "'";
		$erg   = $database->query( $query );
		if ( $erg->numRows() > 0 )
		{
			$cp                = $erg->fetchRow();
			$lang              = $cp[ "language" ];
			$code              = $cp[ "page_code" ];
			$langarr[ $lang ]  = "";
			$classarr[ $lang ] = "easymultilang_current";
		}
		elseif ( isset( $_SESSION[ 'LANGUAGE' ] ) and strlen( $_SESSION[ 'LANGUAGE' ] ) == 2 )
		{
			$lang = ""; // dummy language for search page
			$code = "home";
		}
		else
		{
			$lang = ""; // dummy language for search page
			$code = "home";
		}
		
		//	in some cases (for instance multi page form) we do not want a language menu at all
		//	with page_code: 'none' we switch the language menu off
		if ( $code == "none" )
		{
			return;
		}
		
		// 1. call home  --> all languages
		$query = "select * FROM " . TABLE_PREFIX . "pages where page_code = 'home' and language != '$lang'";
		$erg   = $database->query( $query );
		if ( $erg->numRows() > 0 )
		{
			while ( $cp = $erg->fetchRow() )
			{
				$l              = $cp[ "language" ];
				$langarr[ $l ]  = $cp[ "link" ];
				$classarr[ $l ] = "easymultilang";
			}
		}
		// 2. call current page and replace result
		$query = "select * FROM " . TABLE_PREFIX . "pages where page_code = '$code' and language != '$lang'";
		$erg   = $database->query( $query );
		if ( $erg->numRows() > 0 )
		{
			while ( $cp = $erg->fetchRow() )
			{
				$l              = $cp[ "language" ];
				$langarr[ $l ]  = $cp[ "link" ];
				$classarr[ $l ] = "easymultilang";
			}
		}
		
		// sort array to always have the same language at the same position
		ksort( $langarr );

		$html_template_str = "<span class='{CLASS}'>{ASTART}<img src='{IMG}' alt='{TXT}' /> {TXT}{AEND}</span>";
		
		$html = "";

		// returns a blank list of items      
		if ($list == true) {
			$return_array = array();
			foreach ( $langarr as $key => $value )
			{	
			 $real_language = $database->get_one("select name FROM " . TABLE_PREFIX . "addons where type = 'language' and directory = '".$key."' ");
			 $return_array[$key] = array('value'=>$value, 'language'=>$real_language);
			}		
			return $return_array ;
		}
		
		// loop
		foreach ( $langarr as $key => $value )
		{
			$query  = "select * FROM " . TABLE_PREFIX . "addons where type = 'language' and directory = '$key'";
			$result = $database->query( $query );
			if ( $result->numRows() > 0 )
			{
				$cp   = $result->fetchRow();
				$txt  = $cp[ "name" ];
				$link = LEPTON_URL . PAGES_DIRECTORY . $value . ".php?lang=$key";
				if (file_exists(LEPTON_PATH ."/modules/lib_lepton/flags/custom/" . strtolower( $key ) . ".png")) 
				{
					$flag = LEPTON_URL ."/modules/lib_lepton/flags/custom/" . strtolower( $key ) . ".png";
				} else {
					$flag = LEPTON_URL ."/modules/lib_lepton/flags/" . strtolower( $key ) . ".png";
				}

				$values = array(
					 '{CLASS}' => $classarr[ $key ],
					'{IMG}' => $flag,
					'{TXT}' => $txt 
				);
				
				if ( $classarr[ $key ] == "easymultilang_current" )
				{
					$values[ '{ASTART}' ] = '';
					$values[ '{AEND}' ]   = '';
				}
				else
				{
					$values[ '{ASTART}' ] = "<a href='$link' title='$txt'>";
					$values[ '{AEND}' ]   = '</a>';
				}
				$html .= str_replace( array_keys( $values ), array_values( $values ), $html_template_str );
			}
		}
		return $html;
	}
?>