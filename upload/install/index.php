<?php

 /**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

// check wether to call update.php or start installation
if (file_exists('../config.php')) {
    include 'update/update.php';
    die();
}

// Start a session
if(!defined('SESSION_STARTED')) {
	session_name('lepton_session_id');
	session_start();
	define('SESSION_STARTED', true);
}

// Function to highlight input fields which contain wrong/missing data
function field_error($field_name='') {
	if(!defined('SESSION_STARTED') || $field_name == '') return;
	if(isset($_SESSION['ERROR_FIELD']) && $_SESSION['ERROR_FIELD'] == $field_name) {
		return ' class="wrong"';
	}
}

// Check if the page has been reloaded
if(!isset($_GET['sessions_checked']) OR $_GET['sessions_checked'] != 'true') {
	// Set session variable
	$_SESSION['session_support'] = '<font class="good">Enabled</font>';
	// Reload page
	header('Location: index.php?sessions_checked=true');
	exit(0);
} else {
	// Check if session variable has been saved after reload
	if(isset($_SESSION['session_support'])) {
		$session_support = $_SESSION['session_support'];
	} else {
		$session_support = '<font class="bad">Disabled</font>';
	}
}

// Check if AddDefaultCharset is set
$e_adc=false;
$sapi=php_sapi_name();
if(strpos($sapi, 'apache')!==FALSE || strpos($sapi, 'nsapi')!==FALSE) {
	flush();
	$apache_rheaders=apache_response_headers();
	foreach($apache_rheaders AS $h) {
		if(strpos($h, 'html; charset')!==FALSE && (!strpos(strtolower($h), 'utf-8')) ) {
			preg_match('/charset\s*=\s*([a-zA-Z0-9- _]+)/', $h, $match);
			$apache_charset=$match[1];
			$e_adc=$apache_charset;
		}
	}
}

// --> FrankH: Detect OS
$ctrue = " checked='checked'";
$cfalse = "";
if (substr(php_uname('s'), 0, 7) == "Windows") {
	$osw = $ctrue;
	$osl = $cfalse;
	$startstyle = "none";
} else {
	$osw = $cfalse;
	$osl = $ctrue;
	$startstyle = "block";
}
// <-- FrankH: Detect OS

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>LEPTON Installation Wizard</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<script language="javascript" type="text/javascript">
function confirm_link(message, url) {
	if(confirm(message)) location.href = url;
}
function change_os(type) {
	if(type == 'linux') {
		document.getElementById('operating_system_linux').checked = true;
		document.getElementById('operating_system_windows').checked = false;
		document.getElementById('file_perms_box').style.display = 'block';
	} else if(type == 'windows') {
		document.getElementById('operating_system_linux').checked = false;
		document.getElementById('operating_system_windows').checked = true;
		document.getElementById('file_perms_box').style.display = 'none';
	}
}

function test_pass_length() {
	var ref = document.getElementById("admin_password");
	if (ref) {
		if (ref.value.length < 6) {
			alert("Sorry - the password has have to contain min. 6 chars!");
			ref.focus();
			return false;
		} else {
			return true;
		}
	} else { alert("call");
		return false;
	}
}

</script>
<?php
/**
 *	Keep in mind that we have the modules right here in the installation!
 * 	So there is no need to load them from the git-server!
 *	Some browsers, e.g. 'Iron', will not accept the external links e.g. as the mime-type doesn't match,
 *	('raw' - instead of 'text/javascript') as for security-reasons.
 *
 */
?>
<script type="text/javascript" src="../modules/lib_jquery/jquery-core/jquery-core.min.js"></script>
<script type="text/javascript" src="../modules/lib_jquery/jquery-core/jquery-migrate.min.js"></script>

<script type="text/javascript" src="../modules/lib_semantic/dist/semantic.min.js" ></script>
<link href="../modules/lib_semantic/dist/semantic.min.css" rel="stylesheet" type="text/css">

<link href="https://doc.lepton-cms.org/_packinstall/style_300.css" rel="stylesheet" type="text/css" />

</head>
<body>
<div id="install">

	<div class="ui top attached segment">
		<div class="ui stackable two column grid">	
			<div id="column">
				<div id="logo">
					<img src="https://doc.lepton-cms.org/_packinstall/img/logo.png" alt="Logo" />
				</div>
			</div>
			<div id="column">
				<div id="form_title">
					<h2>Step-by-Step Installation</h2>
				</div>
			</div>
		</div>
	</div>

	<div class="ui attached segment">
		<h3 div style="text-align:center">Fill in the data if required</h3>
		
		<?php
		if(isset($_SESSION['message']) AND $_SESSION['message'] != '') {
		?>
		<div class="ui negative fluid message">
			<div class="header">ERROR!</div>
			<p><?php echo $_SESSION['message']; ?></p>
		</div>
		<?php
		}
		?>		
		<form name="lepton_installation_wizard" id="install_form" class="ui form" action="save.php" method="post" onsubmit="return test_pass_length();">	
			<input type="hidden" name="url" value="" />
			<input type="hidden" name="username_fieldname" value="admin_username" />
			<input type="hidden" name="password_fieldname" value="admin_password" />
			<input type="hidden" name="remember" id="remember" value="true" />
			<input type="hidden" name="guid" value="E610A7F2-5E4A-4571-9391-C947152FDFB0" />
		
			<div class="ui top attached tabular menu lepton_menu">
				<a class="active item" data-tab="first">Step 1</a>
				<a class="item" data-tab="second">Step 2</a>
				<a class="item" data-tab="third">Step 3</a>
				<a class="item" data-tab="fourth">Step 4</a>
				<a class="item" data-tab="fifth">Step 5</a>
			</div>
			<div class="ui bottom attached tab active segment" data-tab="first">
				<div class="spacer"></div>
			    <h3 class="ui header">step 1 - php-information</h3>
				<h4 class="ui header">Please check the following requirements:</h4>
				<div class="ui red warning message">Note: PHP Session Support may appear disabled if your browser does not support cookies.</div>
				<div class="spacer"></div>			
				<div class="ui four column grid">

					<div class="column">
					</div>				
					<div class="column">
						<div class="ui horizontal segment">
						<p>PHP Version min 7.0.0</p>
						</div>
					</div>
					<div class="column">
						<div class="ui horizontal segment">
							<?php
							if (version_compare(PHP_VERSION, '7.0.0', '>='))
								{
							?><font class="good">Yes</font><?php
							} else {
								?><font class="bad">No</font><?php
							}
							?>
						</div>
					</div>
					<div class="column">
					</div>
					
					<div class="column">
					</div>			
					<div class="column">
						<div class="ui horizontal segment">
						<p>PHP Session Support</p>
						</div>
					</div>
					<div class="column">
						<div class="ui horizontal segment">
							<?php echo $session_support; ?>
						</div>
					</div>
					<div class="column">
					</div>
					
					<div class="column">
					</div>
					<div class="column">
						<div class="ui horizontal segment">
						<p>PHP Safe Mode</p>
						</div>
					</div>
					<div class="column">
						<div class="ui horizontal segment">
							<?php
							if(ini_get('safe_mode')=='' || strpos(strtolower(ini_get('safe_mode')), 'off')!==FALSE || ini_get('safe_mode')==0) {
								?><font class="good">Disabled</font><?php
							} else {
								?><font class="bad">Enabled</font><?php
							}
							?>
						</div>
					</div>
					<div class="column">
					</div>
					
					<div class="column">
					</div>
					<div class="column">
						<div class="ui horizontal segment">
						<p>AddDefaultCharset unset</p>
						</div>
					</div>
					<div class="column">
						<div class="ui horizontal segment">
							<?php
								if($e_adc) {
									?><font class="bad">No</font><?php
								} else {
									?><font class="good">Yes</font><?php
								}
							?>
						</div>
					</div>
					<div class="column">
					</div>					
				</div>

			
				<div class="ui two column grid">	
					<?php if (version_compare(PHP_VERSION, '7.0.0', '<')){ ?>				
					<div class="column">
						<div class="ui horizontal segment">
							<p>Your current PHP Version is: <?php print PHP_VERSION;  ?></p>
						</div>
					</div>
					<div class="column">
						<div class="ui horizontal segment">
							<h4 class="warning">Please upgrade your PHP Release to 7.0.0 or higher</h4>
						</div>
					</div>
					<?php } ?>					
					
					<?php if($e_adc) { ?>					
					<div class="column">
						<div class="ui horizontal segment bad">
							Please note: AddDefaultCharset is set to <?php echo $e_adc;?> in apache.conf, .htaccess file or in your php config file.
						</div>
					</div>
					<div class="column">
						<div class="ui horizontal segment bad">
							If you have to use umlauts (e.g. &auml; &aacute;) please change this to Off. - Or use <?php echo $e_adc;?> inside LEPTON, too.
						</div>
					</div>
					<?php } ?>					
				</div>
				<div class="spacer"></div>				
			</div>
			
			<div class="ui bottom attached tab segment" data-tab="second">
				<div class="spacer"></div>
			    <h3 class="ui header">step 2 - file permissions</h3> 
				<h4 class="ui header">Please check if the following files/folders are writeable before continuing...</h4>
				<div class="spacer"></div>			
				<div class="ui four column grid">
					
					<div class="column">
					</div>					
					<div class="column">
						<div class="ui horizontal segment">/page/</div>
					</div>
					<div class="column">
						<div class="ui horizontal segment">
							<?php if(is_writable('../page/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../page/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?>
						</div>
					</div>
					<div class="column">
					</div>
						
			
					<div class="column">
					</div>					
					<div class="column">
						<div class="ui horizontal segment">/media/</div>
					</div>
					<div class="column">
						<div class="ui horizontal segment">
							<?php if(is_writable('../media/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../page/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?>
						</div>
					</div>
					<div class="column">
					</div>
						

					<div class="column">
					</div>					
					<div class="column">
						<div class="ui horizontal segment">/templates/</div>
					</div>
					<div class="column">
						<div class="ui horizontal segment">
							<?php if(is_writable('../templates/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../page/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?>
						</div>
					</div>
					<div class="column">
					</div>
						

					<div class="column">
					</div>					
					<div class="column">
						<div class="ui horizontal segment">modules/</div>
					</div>
					<div class="column">
						<div class="ui horizontal segment">
							<?php if(is_writable('../modules/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../page/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?>
						</div>
					</div>
					<div class="column">
					</div>
						
					
					<div class="column">
					</div>
					<div class="column">
						<div class="ui horizontal segment">/languages/</div>
					</div>
					<div class="column">
						<div class="ui horizontal segment">
							<?php if(is_writable('../languages/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../page/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?>
						</div>
					</div>
					<div class="column">
					</div>
						
					
					
					<div class="column">
					</div>	
					<div class="column">
						<div class="ui horizontal segment">/install/</div>
					</div>
					<div class="column">
						<div class="ui horizontal segment">
							<?php if(is_writable('../install/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../page/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?>
						</div>
					</div>
					<div class="column">
					</div>
					
					
					<div class="column">
					</div>
					<div class="column">
						<div class="ui horizontal segment">LEPTON root directory/</div>
					</div>
					<div class="column">
						<div class="ui horizontal segment">
							<?php if(is_writable('../')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../page/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?>
						</div>
					</div>
					<div class="column">
					</div>
						

					<div class="column">
					</div>
					<div class="column">
						<div class="ui horizontal segment">/temp/</div>
					</div>
					<div class="column">
						<div class="ui horizontal segment">
							<?php if(is_writable('../temp/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../page/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?>
						</div>
					</div>
					<div class="column">
					</div>
	
				</div>
				<div class="spacer"></div>	
			</div>
			
			<div class="ui bottom attached tab segment" data-tab="third">
				<div class="spacer"></div>
			    <h3 class="ui header">step 3 - path, language and OS settings</h3> 
				<h4 class="ui header">Please check your path settings, and select a default timezone and a default backend language...</h4>
				<div class="spacer"></div>			
				<div class="ui four column grid">

					<div class="two wide column"></div>				
					<div class="four wide column">Absolute URL:</div>
					<div class="six wide column">
							<div class="ui fluid input">
							<?php
							   // Try to guess installation URL
							   $protocol = ( isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? "https" : "http";  
							   $guessed_url = $protocol.'://' . $_SERVER[ "SERVER_NAME" ] .( ( ( $_SERVER['SERVER_PORT'] != 80 ) && ( $protocol != 'https'))  ? ':'.$_SERVER['SERVER_PORT'] : '' ) . $_SERVER[ "SCRIPT_NAME" ];
							   $guessed_url = rtrim(dirname($guessed_url), 'install');   
							?>
								<input <?php echo field_error('lepton_url');?> type="text" name="lepton_url"  value="<?php if(isset($_SESSION['lepton_url'])) { echo $_SESSION['lepton_url']; } else { echo $guessed_url; } ?>" />
							</div>
					</div>
					<div class="four wide column"></div>

					<div class="two wide column"></div>				
					<div class="four wide column">Default Timezone:</div>
					<div class="six wide column">
							<div class="field">
								<select class="ui selection dropdown" <?php echo field_error('default_timezone');?> name="default_timezone_string" >
								<?php
								$timezone_table = array(
									"Pacific/Kwajalein",
									"Pacific/Samoa",
									"Pacific/Honolulu",
									"America/Anchorage",
									"America/Los_Angeles",
									"America/Phoenix",
									"America/Mexico_City",
									"America/Lima",
									"America/Caracas",
									"America/Halifax",
									"America/Buenos_Aires",
									"Atlantic/Reykjavik",
									"Atlantic/Azores",
									"Europe/London",
									"Europe/Berlin",
									"Europe/Kaliningrad",
									"Europe/Moscow",
									"Asia/Tehran",
									"Asia/Baku",
									"Asia/Kabul",
									"Asia/Tashkent",
									"Asia/Calcutta",
									"Asia/Colombo",
									"Asia/Bangkok",
									"Asia/Hong_Kong",
									"Asia/Tokyo",
									"Australia/Adelaide",
									"Pacific/Guam",
									"Etc/GMT+10",
									"Pacific/Fiji"
								);

								if (!isset($_SESSION['default_timezone_string'])) $_SESSION['default_timezone_string'] = "Europe/Berlin";
								foreach ($timezone_table AS $title) {
									$sel = ($_SESSION['default_timezone_string'] == $title) ? " selected='selected'" : '';
									echo "<option $sel>$title</option>";
								}
								?>
							</select>
						</div>
					</div>
					<div class="four wide column"></div>

					<div class="two wide column"></div>				
					<div class="four wide column">Default Language:</div>
					<div class="six wide column">
							<div class="field">
								<select class="ui selection dropdown" <?php echo field_error('default_language');?> name="default_language">
									<?php

										$DEFAULT_LANGUAGE = array();
										$lang_dir = "../languages/";
										$dir = dir( $lang_dir );
										while( $temp_file = $dir->read() ) {
											if ($temp_file[0] == ".") continue;
											if ($temp_file == "index.php" ) continue;
											
											$str = file( $lang_dir.$temp_file );
											
											$language_name = "";
											foreach($str as $line) {
												if (strpos( $line, "language_name") != false) {
													eval ($line);
													break;
												}
											}
											
											$temp = explode(".", $temp_file);
											array_pop($temp);
											$temp_file_name = implode(".", $temp);
											$DEFAULT_LANGUAGE[ $temp_file_name ] = $language_name;
											
										}
										$dir->close();
										ksort($DEFAULT_LANGUAGE);
					
										foreach($DEFAULT_LANGUAGE as $lang_id => $lang_title) {
											?>
												<option value="<?php echo $lang_id; ?>"<?php if(isset($_SESSION['default_language']) AND $_SESSION['default_language'] == $lang_id) { echo ' selected="selected"'; } elseif(!isset($_SESSION['default_language']) AND $lang_id == 'EN') { echo ' selected="selected"'; } ?>><?php echo $lang_title; ?></option>
											<?php
										}
										?>
								</select>
							</div>
					</div>
					<div class="four wide column"></div>					
	
				</div>
				
			    <h3 class="ui header"></h3> 
				<h4 class="ui header">Please specify your operating system information below...</h4>
				<div class="spacer"></div>			
				<div class="ui four column grid">

					<div class="two wide column"></div>				
					<div class="four wide column">Server Operating System:</div>
					<div class="six wide column">
						<div class="inline fields">
							<div class="field">
								<div class="ui radio checkbox">
									<input type="radio" name="operating_system" id="operating_system_linux" onclick="document.getElementById('file_perms_box').style.display = 'inline';" value="linux"<?php echo $osl; ?> />
									<label><span style="cursor: pointer;" onclick="javascript: change_os('linux');">Linux/Unix based</span></label>
								</div>
							</div>
							<div class="field">
								<div class="ui radio checkbox">
									<input type="radio" name="operating_system" id="operating_system_windows" onclick="document.getElementById('file_perms_box').style.display = 'none';" value="windows"<?php echo $osw; ?> />
									<label><span style="cursor: pointer;" onclick="javascript: change_os('windows');">Windows</span></label>
								</div>
							</div>
						</div>
					</div>
					<div class="four wide column"></div>

					<div class="two wide column"></div>				
					<div class="four wide column">World-writeable file permissions (777)</div>
					<div class="two wide column">
						<div display: <?php if(isset($_SESSION['operating_system']) AND $_SESSION['operating_system'] == 'windows') { echo 'none'; } else { echo 'block'; } ?>;">
							<input type="checkbox" name="world_writeable" id="world_writeable" value="true"<?php if(isset($_SESSION['world_writeable']) AND $_SESSION['world_writeable'] == true) { echo 'checked'; } ?> />
						</div>
					</div>
					<div class="eight wide column" style="color:red;">Please note: only recommended for testing environments</div>				
				</div>
				<div class="spacer"></div>
			</div>
			
			<div class="ui bottom attached tab segment" data-tab="fourth">
				<div class="spacer"></div>
			    <h3 class="ui header">step 4 - database settings</h3> 
				<h4 class="ui header">Please enter your MySQL database server details below...</h4>
				<div class="spacer"></div>			
				<div class="ui four column grid">

					<div class="two wide column"></div>				
					<div class="four wide column">Host Name:</div>
					<div class="six wide column">
							<div class="ui fluid input">
							<input <?php echo field_error('database_host');?> type="text" name="database_host" value="<?php if(isset($_SESSION['database_host'])) { echo $_SESSION['database_host']; } else { echo 'localhost'; } ?>" />
							</div>
					</div>
					<div class="four wide column"></div>
					
					<div class="two wide column"></div>				
					<div class="four wide column">DB Port:</div>
					<div class="six wide column">
							<div class="ui fluid input">
							<input <?php echo field_error('database_port');?> type="text" name="database_port" value="3306" />
							</div>
					</div>
					<div class="four wide column"></div>

					<div class="two wide column"></div>				
					<div class="four wide column">Database Name:</div>
					<div class="six wide column">
					<div class="field">
							<div class="ui fluid input">
							<input <?php echo field_error('database_name');?> type="text" name="database_name" value="<?php if(isset($_SESSION['database_name'])) { echo $_SESSION['database_name']; } else { echo 'my-sql-db-name'; } ?>" />
							</div>
					</div>
					</div>
					<div class="four wide column">allowed chars: [a-z A-Z 0-9_-]</div>

					<div class="two wide column"></div>				
					<div class="four wide column">Database User:</div>
					<div class="six wide column">
							<div class="ui fluid input">
							<input <?php echo field_error('database_username');?> type="text"  name="database_username"  value="<?php if(isset($_SESSION['database_username'])) { echo $_SESSION['database_username']; } else { echo 'my-user-name'; } ?>" />
							</div>
					</div>
					<div class="four wide column"></div>	

					<div class="two wide column"></div>				
					<div class="four wide column">Database Password:</div>
					<div class="six wide column">
							<div class="ui fluid input">
							<input type="password" name="database_password" <?php if(isset($_SESSION['database_password'])) { echo ' value = "'.$_SESSION['database_password'].'"'; } ?> />
							</div>
					</div>
					<div class="four wide column"></div>

					<div class="two wide column"></div>				
					<div class="four wide column">Table Prefix:</div>
					<div class="six wide column">
							<div class="ui fluid input">
							<input <?php echo field_error('table_prefix');?> type="text" name="table_prefix" <?php if(isset($_SESSION['table_prefix'])) { echo ' value = "'.$_SESSION['table_prefix'].'"'; }  else { echo " value='lep_'"; }?> />
							</div>
					</div>
					<div class="four wide column">allowed chars: [a-z A-Z 0-9_]</div>

					<div class="two wide column"></div>				
					<div class="four wide column">Option:</div>
					<div class="four wide column">
						<input type="checkbox" name="install_tables" id="install_tables" value="true"<?php if(!isset($_SESSION['install_tables'])) { echo ' checked="checked"'; } elseif($_SESSION['install_tables'] == 'true') { echo ' checked="checked"'; } ?> />
						<label for="install_tables" style="color: #666666;">Install Tables</label>
					</div>
					<div class="six wide column" style="color:red;">Please note: May remove existing tables and data</div>					

					<div class="spacer"></div>
				</div>					
			</div>

			<div class="ui bottom attached tab segment" data-tab="fifth">
				<div class="spacer"></div>
			    <h3 class="ui header">step 5 - Site settings</h3> 
				<h4 class="ui header">Please enter your website data below...</h4>
				<div class="spacer"></div>			
				<div class="ui four column grid">

					<div class="two wide column"></div>				
					<div class="four wide column">Website Title:</div>
					<div class="six wide column">
							<div class="ui fluid input">
							<input <?php echo field_error('website_title');?> type="text" name="website_title" value="<?php if(isset($_SESSION['website_title'])) { echo $_SESSION['website_title']; } else { echo 'LEPTON CMS 2series'; } ?>" />
							</div>
					</div>
					<div class="four wide column"></div>
					
					<div class="two wide column"></div>				
					<div class="four wide column">Username:</div>
					<div class="six wide column">
							<div class="ui fluid input">
							<input <?php echo field_error('admin_username');?> type="text" name="admin_username" value="<?php if(isset($_SESSION['admin_username'])) { echo $_SESSION['admin_username']; } else { echo 'admin'; } ?>" />
							</div>
					</div>
					<div class="four wide column">minimum 3 chars</div>

					<div class="two wide column"></div>				
					<div class="four wide column">Password:</div>
					<div class="six wide column">
							<div class="ui fluid input">
							<input <?php echo field_error('admin_password');?> onblur="test_pass_length();" type="password" name="admin_password" id="admin_password" <?php if(isset($_SESSION['admin_password'])) { echo ' value = "'.$_SESSION['admin_password'].'"'; } ?> />
							</div>
					</div>
					<div class="four wide column">minimum 6 chars</div>
					
					<div class="two wide column"></div>				
					<div class="four wide column">Retype Password:</div>
					<div class="six wide column">
							<div class="ui fluid input">
							<input <?php echo field_error('admin_repassword');?> type="password" name="admin_repassword" <?php if(isset($_SESSION['admin_repassword'])) { echo ' value = "'.$_SESSION['admin_repassword'].'"'; } ?> />
							</div>
					</div>
					<div class="four wide column"></div>					

					<div class="two wide column"></div>				
					<div class="four wide column">Email:</div>
					<div class="six wide column">
							<div class="ui fluid input">
							<input <?php echo field_error('admin_email');?> type="email" name="admin_email" <?php if(isset($_SESSION['admin_email'])) { echo ' value = "'.$_SESSION['admin_email'].'"'; } ?> />
							</div>
					</div>
					<div class="four wide column"></div>	
				</div>
				
				<div class="ui positive message lepton_message">
					<div class="header">Please note:</div>
					<ul class="list">
						<li><strong>LEPTON Core </strong>is released under the <a href="http://www.gnu.org/licenses/gpl.html" target="_blank">GNU General Public License</a></li>
						<li><strong>LEPTON CMS Package </strong>is released under different licenses. Please check file headers or info.php of addons for exact license.</li>
						<li>By clicking <strong>Install LEPTON</strong> you agree to all licenses.</li>
					</ul>
				</div>

				<div class="spacer"></div>
				
				<div id="submit_area">			
					<div class="ui green button">
						<input type="submit" id="SaveAccount" name="submit" value="Install LEPTON" class="submit" />
					</div>				
				</div>

				<div class="spacer"></div>
				
			</div>	
						
			<div class="ui menu lepton_menu">
				<a class="item" data-tab="first">go to Step 1</a>
				<a class="item" data-tab="second">go to Step 2</a>
				<a class="item" data-tab="third">go to Step 3</a>
				<a class="item" data-tab="fourth">go to Step 4</a>
				<a class="item" data-tab="fifth">go to Step 5</a>
			</div>					
		</form>	
	
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



</div> <!-- end id="install_form -->

<script type="text/javascript">

$('.menu .item')
  .tab()
;

$('.ui.dropdown')
  .dropdown()
;

$('select.dropdown')
  .dropdown()
;

$('.ui.checkbox')
  .checkbox()
;

$('.ui.radio.checkbox')
  .checkbox()
;

$('#install_form.form')
  .form({
    on: 'blur',
    fields: {
      regex: {
        identifier  : 'database_username',
        rules: [
          {
            type   : 'regExp[/^[a-z0-9_-@]{4,16}$/]',
            prompt : 'Please enter a 4-16 database username'
          },
          {
            type   : 'empty',
            prompt : 'Please enter a database username'
          }
        ]
      }
	}
  });
</script>
</body>
</html>
