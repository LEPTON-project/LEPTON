<?php

/**
 *  @script		    upgrade LEPTON 3series to LEPTON 4.0.0
 *  @version        0.1.0
 *  @author         cms-lab
 *  @copyright      2018 CMS-LAB
 *  @license        http://creativecommons.org/licenses/by/3.0/
 *  @license terms  none
 *  @platform       LEPTON 3.0.2
 */ 


define('DEBUG', true);

// set error level
 ini_set('display_errors', 1);
 error_reporting(E_ALL|E_STRICT);

// Include config file
$config_file = '../../../config.php';
if(file_exists($config_file))
{
	require_once($config_file);

} else {
	die("<h4 style='color:red;text-align:center;font-size:20px;'> cannot find any config.php </h4>");	// make sure that the code below will not be executed
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>LEPTON Upgrade Script</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<script type='text/javascript' src='<?php echo LEPTON_URL; ?>/modules/lib_semantic/dist/semantic.min.js' ></script>
<link rel="stylesheet" type="text/css" href="<?php echo LEPTON_URL; ?>/modules/lib_semantic/dist/semantic.min.css" media="screen,projection" />	
<link href="https://doc.lepton-cms.org/_packinstall/style_300.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="update_form">

	<div class="ui top attached segment">
		<div id="logo">
			<img src="https://doc.lepton-cms.org/_packinstall/img/logo.png" alt="Logo" />
		</div>
		<div id="form_title">
			<h2>LEPTON 3.0.1/3.0.2 to LEPTON 4.0.0</h2>
		</div>	
	</div>
	
	<div class="ui basic segment">
		<div class="spacer"></div>
		<?php
		 
		echo ('<h3>Current process : updating to LEPTON 4.0.0</h3>');

		echo ('<h5>Current process : move config and ini file to new location</h5>'); 

		if(file_exists ('../../../config.php')) {
			copy ('../../../framework/classes/setup.ini.php', '../../../config/lepton.ini.php');
			copy ('../../../config.php', '../../../config/config.php');
			// modify LEPTON_PATH
			$old_string = file_get_contents ('../../../config/config.php');
			$new_string = str_replace ("define('LEPTON_PATH', dirname(__FILE__));","define('LEPTON_PATH', dirname(dirname(__FILE__)));",$old_string);
			file_put_contents('../../../config/config.php',$new_string);
		} else {
			// make sure that the code below will not be executed
			die("<div class='ui compact negative message'><i class='big announcement icon'></i>cannot find config.php in the root of installation</div>");	
		}

		echo "<h5>Move files: successfull</h5>"; 

		echo ('<h5>Current process : delete unneeded files</h5>'); 

		$file_names = array (
			"/framework/class.admin.php",
			"/framework/class.admin_phplib.php",
			"/framework/class.admin_twig.php",
			"/framework/class.frontend.php",
			"/framework/class.login.php",
			"/framework/class.order.php",
			"/framework/class.securecms.php",
			"/framework/class.validate.request.php",
			"/framework/var.date_formats.php",
			"/framework/var.time_formats.php",
			"/framework/var.timezones.php",
			"/framework/class.wb.php"
		);
		LEPTON_handle::delete_obsolete_files($file_names);

		echo "<h5>Delete files: successfull</h5>"; 

		/**
		 *  run upgrade.php of all modified modules
		 *
		 */
		 echo '<h5>Current process : run modules upgrade.php</h5>';  
		 
		$module_names = array(
			"captcha_control",
			"code2",
			"droplets",
			"initial_page",	
			"lib_jquery",	
			"lib_lepton",	
			"lib_phpmailer",
			"lib_r_filemanager",	
			"lib_search",	
			"lib_twig",	
			"menu_link",
			"news",
			"quickform",	
			"show_menu2",	
			"tinymce",
			"wrapper",	
			"wysiwyg"
		);
		LEPTON_handle::upgrade_modules($module_names);

		echo "<h5>run upgrade.php of modified modules: successfull</h5>";

		
		echo '<h5>reload all addons</h5>';
		
		if (file_exists (LEPTON_PATH.'/install/update/reload.php')) {
					require_once(LEPTON_PATH . '/install/update/reload.php');
		}	
		
		echo "<h5>reload all addons: successfull</h5>";
		
		// at last: set db to current release-no
		echo '<h5>set database to new release</h5>';
		// delete not needed entry
		$database->simple_query('DELETE FROM `'.TABLE_PREFIX.'settings` WHERE `name` =\'enable_old_language_definitions\'');
		$database->simple_query('UPDATE `'.TABLE_PREFIX.'settings` SET `value` =\'4.0.0\' WHERE `name` =\'lepton_version\'');

		$file_names = array (
		'/framework/classes/setup.ini.php',
		'/config.php',
		'/config.sik.php'
		);
		LEPTON_handle::delete_obsolete_files($file_names);

		?>
		<div class="spacer"></div>
		<?php
		/**
		 *  success message
		 */
		echo "<h3>update to LEPTON 4.0.0 successfull!</h3><br />"; 
		?>			
		<div class="spacer"></div>		
	</div><!-- end basic segment -->

	<?php

	// get the buttons					
	include('../login.php');
	// get the footer				
	include('../footer.php');	
	// delete install directory
	LEPTON_handle::delete_obsolete_directories('/install');		
	?>		
	
</div> <!-- end id="update_form" -->
</body>
</html>
?>
