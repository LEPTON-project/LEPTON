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

require_once('../../config.php');
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
		 *  update to LEPTON 3.0.1 , check release
		 */		 
		$lepton_version = $database->get_one("SELECT `value` from `" . TABLE_PREFIX . "settings` where `name`='lepton_version'");
		if (version_compare($lepton_version, "3.0.0", "="))
		{
			echo("<h3 class='good'>Your LEPTON Version : $lepton_version </h3>");
		    include 'scripts/301_update.php';
			
		}

		/**
		 *  update to LEPTON 3.0.2 , check release
		 */		 
		$lepton_version = $database->get_one("SELECT `value` from `" . TABLE_PREFIX . "settings` where `name`='lepton_version'");
		if (version_compare($lepton_version, "3.0.1", "="))
		{
			echo("<h3 class='good'>Your LEPTON Version : $lepton_version </h3>");
		    include 'scripts/302_update.php';
			
		} 	else {
					echo ("<h3 class='good'>You don't have to update, you are running current LEPTON release.</h3>");
						// delete install directory
						if ( file_exists(LEPTON_PATH.'/install/')) {
							require_once (LEPTON_PATH.'/framework/functions/function.rm_full_dir.php');
							rm_full_dir(LEPTON_PATH.'/install/');
						} 						
					die ("<div class='ui compact info message'><i class='big announcement icon'></i>Your install directory has been deleted!</div>");

		}		
		/**
		 *  reload all addons
		 */
		if (file_exists(dirname(__FILE__).'/reload.php')) {
			require_once dirname(__FILE__).'/reload.php';
		} ?>
		<div class="spacer"></div>
		<?php
		/**
		 *  success message
		 */
		echo ("<h3 class='good'>Congratulation, update procedure complete!</h3>");
		?>				
		<div class="spacer"></div>		
	</div>
		<?php
			/**
			 * delete install directory
			 */
			if ( file_exists(LEPTON_PATH.'/install/')) {
				require_once (LEPTON_PATH.'/framework/functions/function.rm_full_dir.php');
				rm_full_dir(LEPTON_PATH.'/install/');
			} 	
		?>	
	<div class="ui attached segment">
		<div class="spacer"></div>
		<h4 class="ui header">Please consider a donation to support LEPTON</h4>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input name="cmd" type="hidden" value="_s-xclick" /> 
			<input name="hosted_button_id" type="hidden" value="DF6TFNAE7F7DJ" /> 
			<input alt="PayPal &mdash; The safer, easier way to donate online." name="submit" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" type="image" /> 
			<img src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" border="0" alt="" width="1" height="1" />
		</form>		
		<div class="spacer"></div>	
		<div class="spacer"></div>	
		<div class="spacer"></div>			
		<div class="column">
			<div class="ui buttons">
				<a href='https://www.lepton-cms.org/english/contact.php' target='_blank'><button class="ui orange button">support LEPTON</button></a>
				<div class="or" data-text=" and "> </div>
				<a href="<?php echo ADMIN_URL; ?>/login/index.php"><button class="ui positive button">login to check installation</button></a>
			</div>
		</div>			

		<div class="spacer"></div>		
	</div>
	
	<div class="ui bottom attached center alligned segment">
		<div class="ui icon message lepton_footer">
			<div class="content">
			<!-- Please note: the below reference to the GNU GPL should not be removed, as it provides a link for users to read about warranty, etc. -->
			<a href="https://lepton-cms.org" title="LEPTON CMS">LEPTON Core</a> is released under the
			<a href="http://www.gnu.org/licenses/gpl.html" title="LEPTON Core is GPL">GNU General Public License</a>.
			<!-- Please note: the above reference to the GNU GPL should not be removed, as it provides a link for users to read about warranty, etc. -->
			<br /><a href="https://lepton-cms.org" title="LEPTON CMS">LEPTON CMS Package</a> is released under several different licenses.
			</div>
		</div>
	</div>	
	
</div> <!-- end id="update_form" -->
</body>
</html>