<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		get_leptoken
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

if ( !defined( 'LEPTON_PATH' ) ) die();

/**
 *	Try to geht the current leptoken.
 *
 *	@param	int		Any root-(page) id. Default = 0.
 *
 */
function get_leptoken() {

	$leptoken = "";
	if(isset($_POST['leptoken'])) {
		$leptoken = $_POST['leptoken'];
	} elseif (isset($_GET['leptoken'])) {
		$leptoken = $_GET['leptoken'];
	} elseif ( isset($_GET['amp;leptoken']) ) {
		$leptoken = $_GET['amp;leptoken'];
	}
	
	return $leptoken;
}
 