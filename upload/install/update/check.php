<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 */

// set error level
 ini_set('display_errors', 1);
 error_reporting(E_ALL|E_STRICT);

require_once('../config.php');
global $admin;
if (!is_object($admin))
{
    require_once(LEPTON_PATH . '/framework/class.admin.php');
    $admin = new admin('Addons', 'modules', false, false);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>LEPTON Update Script</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<script type='text/javascript' src='<?php echo LEPTON_URL; ?>/modules/lib_semantic/dist/semantic.min.js' ></script>
<link rel="stylesheet" type="text/css" href="<?php echo LEPTON_URL; ?>/modules/lib_semantic/dist/semantic.min.css" media="screen,projection" />	
<link href="https://doc.lepton-cms.org/_packinstall/style_200.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="update_form">

	<div class="ui top attached segment">
		<div id="logo">
			<img src="https://doc.lepton-cms.org/_packinstall/img/logo.png" alt="Logo" />
		</div>
		<div id="form_title">
			<h2>LEPTON update script</h2>
		</div>	
	</div>
	
	<div class="ui attached segment">
		<div class="spacer"></div>
		<?php
		/**
		 *  check php version
		 */
		echo("<h3>Check PHP Version</h3>");		
		if (version_compare(PHP_VERSION, "7.0", "<"))
		{ 
			echo ("<div class='ui compact negative message'><i class='big announcement icon'></i>No update possible, please update your PHP version to 7.0.0. or greater <br />Your PHP Version : ". PHP_VERSION ." !</div>");					
		} else {	
			echo("<h3 class='good'>Your PHP Version : ". PHP_VERSION ." !</h3>");
			echo("<h3 class='good'>Update possible, please push button to start.</h3>");			
			echo ("<div class='ui compact info message'><i class='big idea icon'></i>Don't forget to backup your files and your database!</div>");
			?>
			<div class="spacer"></div>			
			<a href="update/update.php"><button class="ui positive button">Start Update</button></a>
	<?php	}	?>

		<div class="spacer"></div>		
	</div>
	
	<?php	
	// get the footer				
		include('footer.php');		
	?>		
	
</div> <!-- end id="update_form" -->
</body>
</html>