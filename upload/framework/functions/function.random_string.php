<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		random-string
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

if ( !defined( 'LEPTON_PATH' ) ) die();

/**
 *	Function to generate a random-string.
 *	Random-Strings are often used inside LEPTON-CMS, e.g. Captcha, Modul-Form, User-Accout, Forgott-Password, etc.
 *
 *	@notice	This function is not(!) included in the function.summary!
 *
 *	@param	int		Number of chars to generate. Default is 8 (chars).
 *
 *	@param	mixed	Type, default is 'alphanum'.
 *					Possible values are:
 *					'alphanum'	= Generates a alpha-numeric string with chars and numbers.
 *					'alpha'		= Generates only chars.
 *					'chars'		= Also generates only chars.
 *					'lower'		= Only lower cases are used.
 *					'num'		= Generates only numbers.
 *					'pass'		= Generates an alpha-numeric string within some special chars e.g. '&', '$' or '|'.
 *					'<anyString>'	= Generates a shuffled string with theese chars.
 *
 *
 *	@return str		A shuffled string within the chars.
 *
 *	@examples		random_string()
 *						- Will generate something like 'abC2puwm' (8 chars).
 *
 *					random_string(5)
 *						- The same, but only 5 chars, e.g. 'abc56' or '2wsd4'.
 *
 *					random_string(8, 'num')
 *						- Will result in a random number-string e.g. '0898124'
 *
 *					random_string(8, 'Aldus')
 *						- Will generate a shuffled-string with thees chars like 'sAdludsA'.
 *
 */
function random_string( $iNumOfChars= 8, $aType="alphanum") {
		
		switch(strtolower($aType)) {
			case 'alphanum':
				$salt = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
				break;
			
			case 'alpha':
			case 'chars':
				$salt = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				break;
			
			case 'lower':
				$salt = 'abcefghijklmnopqrstuvwxyz';
				break;
				
			case 'num':
				$salt = '1234567890';
				break;
			
			case 'pass':
				$salt = "abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890?!&_-*%@:.|";
				break;
				
			default:
				$salt = (is_array($aType)) ? implode("", $aType) : (string) $aType ;
				break;
		}
		
		$max = strlen($salt);
		if ($iNumOfChars > $max) {
			do {
				$salt .= $salt;
			} while (strlen($salt) < $iNumOfChars);
		}
		
   		return substr( str_shuffle($salt) , 0, $iNumOfChars);
}

?>