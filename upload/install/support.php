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
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 */

// set error level
 ini_set('display_errors', 1);
 error_reporting(E_ALL|E_STRICT);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>LEPTON Installation</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="https://raw.githubusercontent.com/labby/lib_semantic/master/dist/semantic.min.js" ></script>

<link href="http://lepton-cms.org/modules/lib_semantic/dist/semantic.min.css" rel="stylesheet" type="text/css">
<link href="https://doc.lepton-cms.org/_packinstall/style_200.css" rel="stylesheet" type="text/css" />
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
	
	<div class="ui attached segment">
		<div class="spacer"></div>

		<h3 class='good'>Congratulation, you have successfully installed LEPTON</h3>
		<br />
		<h3 class='good'>Help us to maintain and develop this CMS</h3>
		
		<div class="spacer"></div>		
	</div>	

	<div class="ui attached segment">
		<div class="spacer"></div>
		<h4 class="ui header">Please consider a donation to support LEPTON</h4>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input name="cmd" type="hidden" value="_s-xclick" /> 
			<input name="hosted_button_id" type="hidden" value="DF6TFNAE7F7DJ" /> 
			<input alt="PayPal &mdash; The safer, easier way to donate online." name="submit" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" type="image" /> 
			<img src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" border="0" alt="" width="1" height="1" />
		</form>		

		<h3><a class='update_link1' href=' https://www.lepton-cms.org/english/contact.php' target='_blank'><h3>or support LEPTON in another way</a> </h3><br />
			
		<h3><a class='update_link2' href='../admins/login/index.php'>please login and check installation</></h3>

		<div class="spacer"></div>		
	</div>

	<div class="ui bottom attached center alligned segment">
		<div class="ui icon message lepton_footer">
			<div class="content">
			<!-- Please note: the below reference to the GNU GPL should not be removed, as it provides a link for users to read about warranty, etc. -->
			<a href="http://wwww.lepton-cms.org" title="LEPTON CMS">LEPTON Core</a> is released under the
			<a href="http://www.gnu.org/licenses/gpl.html" title="LEPTON Core is GPL">GNU General Public License</a>.
			<!-- Please note: the above reference to the GNU GPL should not be removed, as it provides a link for users to read about warranty, etc. -->
			<br /><a href="http://wwww.lepton-cms.org" title="LEPTON CMS">LEPTON CMS Package</a> is released under several different licenses.
			</div>
		</div>
	</div>	
	
</div> <!-- end id="update_form" -->
</body>
</html>
