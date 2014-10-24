<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		page_header
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2014 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

if ( !defined( 'LEPTON_PATH' ) ) die();

/**
 *	Function to generate a random-string.
 *	Random-Strings are often used inside LEPTON-CMS, e.g. Captcha, Modul-Form, User-Accout, Forgott-Password, etc.
 *
 *	@notice	This function is not(!) include in the function.summery!
 *
 *	@param	int		Number of chars to generate. Default is 8 (chars).
 *
 *	@param	mixed	Type, default is AlphaNum.
 *					Possible values are:
 *					'alphanum'	== generates a alpha-numeric string with chars and numbers.
 *					'alpha'		== generates only chars.
 *					'chars'		== also generates only chars.
 *					'lower'		== Only lower cases are used.
 *					'num'		== generates only numbers.
 *					'<anyString>'	== generates a shuffled string with theese chars.
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
 *						- Will generate a shuffled-string with thees chars like 'sAdlu'.
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
			
			default:
				$salt = $aType;
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