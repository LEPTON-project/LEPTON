<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 */

// set error level
 ini_set('display_errors', 1);
 error_reporting(E_ALL|E_STRICT);
 require_once('../config/config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>LEPTON Installation</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="<?php echo LEPTON_URL; ?>/modules/lib_semantic/dist/semantic.min.js" ></script>

<link href="<?php echo LEPTON_URL; ?>/modules/lib_semantic/dist/semantic.min.css" rel="stylesheet" type="text/css">
<link href="https://doc.lepton-cms.org/_packinstall/style_300.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="update_form">
	<div class="ui top attached segment">
		<div id="logo">
			<img src="https://doc.lepton-cms.org/_packinstall/img/logo.png" alt="Logo" />
		</div>
		<div id="form_title">
			<h2>LEPTON Installation</h2>
		</div>	
	</div>
	
	<div class="ui basic segment">
		<div class="spacer"></div>

		<h3 class='good'>Congratulation, you have successfully installed LEPTON</h3>
		<br />
		<h3 class='good'>Help us to maintain and develop this CMS</h3>
		
		<div class="spacer"></div>		
	</div>	

	<?php
	// get the buttons					
		include('update/login.php');
		
	// get the footer				
		include('update/footer.php');		
	?>	
	
</div> <!-- end id="update_form" -->
</body>
</html>
