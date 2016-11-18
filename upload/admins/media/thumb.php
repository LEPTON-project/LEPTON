<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
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



include_once('resize_img.php');

if (isset($_GET['img']) && isset($_GET['t'])) {
	$image = addslashes($_GET['img']);
	$type = addslashes($_GET['t']);
	$media = LEPTON_PATH.MEDIA_DIRECTORY;
	$img=new RESIZEIMAGE($media.$image);
	if ($img->imgWidth) {
		if ($type == 1) {
			$img->resize_limitwh(50,50);
		} else if ($type == 2) {
			$img->resize_limitwh(400,400);
		} 
		$img->close();
	} else {
		header ("Content-type: image/jpeg");
		readfile ( "nopreview.jpg" );
	}
}
?>