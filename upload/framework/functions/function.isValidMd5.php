<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		isValidMd5
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

if (!defined("LEPTON_PATH")) die();

/**
 *	A simple check if a given string is a MD% hash
 *
 *	@param	string	A given hash-string to test.
 *	@return	boolean	True if not empty, only chars a..f and numbers 0..9 are used and 32 chars
 *	@since	2.2.2
 *
 */
function isValidMd5($md5)
{
	return !empty($md5) && preg_match('/^[a-f0-9]{32}$/', $md5);
}
?>