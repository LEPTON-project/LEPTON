<?php

 /**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010, Website Baker
 * @copyright       2010-2012 LEPTON Project
 * @link            http://www.LEPTON-cms.org
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
	session_name('wb_session_id');
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
<title>Lepton Installation Wizard</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<link href="http://lepton-cms.org/_packinstall/style.css" rel="stylesheet" type="text/css" />
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
</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
<script type="text/javascript" src="http://lepton-cms.org/_packinstall/formtowizard.js"></script>
<link rel="stylesheet" href="http://lepton-cms.org/_packinstall/formtowizard.css" type="text/css" />
<script type="text/javascript">

        $(document).ready(function(){
            $("#SignupForm").formToWizard({ submitButton: 'SaveAccount' })
        });
</script>
</head>
<body>

<table cellpadding="0" cellspacing="0" border="0" width="750" align="center" style="margin-bottom:20px;margin-top:20px;">
<tr>
	<td width="60" valign="top">
		<img src="http://lepton-cms.org/_packinstall/img/logo.png" alt="Logo" />
	</td>
	<td width="5">&nbsp;</td>
	<td style="font-size: 20px; text-align:center;">
		<font style="color: #838280;">Step-by-Step Installation Wizard</font>
	</td>
</tr>
</table>
<div id="wizzard">
<form name="website_baker_installation_wizard" id="SignupForm" action="save.php" method="post">
<input type="hidden" name="url" value="" />
<input type="hidden" name="username_fieldname" value="admin_username" />
<input type="hidden" name="password_fieldname" value="admin_password" />
<input type="hidden" name="remember" id="remember" value="true" />
<input type="hidden" name="guid" value="E610A7F2-5E4A-4571-9391-C947152FDFB0" />
<table cellpadding="0" cellspacing="0" border="0" width="750" align="center" style="margin-top: 10px;">
<tr>
	<td class="content">

		<center style="padding: 5px;">Fill in the data if required</center>

		<?php
		if(isset($_SESSION['message']) AND $_SESSION['message'] != '') {
			?><div style="width: 700px; padding: 10px; margin-bottom: 5px; border: 1px solid #FF0000; background-color: #FFDBDB;"><b>Error:</b> <?php echo $_SESSION['message']; ?></div><?php
		}
		?>
    <fieldset> <legend>php-information</legend>
		<table cellpadding="3" cellspacing="0" width="100%" align="center">
		<tr>
			<td colspan="6" style="padding-bottom:10px;">Please check the following requirements:</td>
		</tr>
		<?php if($session_support != '<font class="good">Enabled</font>') { ?>
		<tr>
			<td colspan="6" style="font-size: 10px;" class="bad">Please note: PHP Session Support may appear disabled if your browser does not support cookies.</td>
		</tr>
		<?php } ?>
		<tr>
			<td width="160" style="color: #666666;">PHP Version min 5.2.2</td>
			<td width="60">
				<?php
				if (version_compare(PHP_VERSION, '5.2.2', '>='))
        {
					?><font class="good">Yes</font><?php
				} else {
					?><font class="bad">No</font><?php
				}
				?>
			</td>
    </tr>
    <tr>
			<td width="140" style="color: #666666;">PHP Session Support</td>
			<td width="105"><?php echo $session_support; ?></td>
   </tr>
   <tr>
			<td width="115" style="color: #666666;">PHP Safe Mode</td>
			<td>
				<?php
				if(ini_get('safe_mode')=='' || strpos(strtolower(ini_get('safe_mode')), 'off')!==FALSE || ini_get('safe_mode')==0) {
					?><font class="good">Disabled</font><?php
				} else {
					?><font class="bad">Enabled</font><?php
				}
				?>
			</td>
		</tr>
		<tr>
			<td width="160" style="color: #666666;">AddDefaultCharset unset</td>
			<td width="60">
				<?php
					if($e_adc) {
						?><font class="bad">No</font><?php
					} else {
						?><font class="good">Yes</font><?php
					}
				?>
			</td>
			<td colspan="4">&nbsp;</td>
      <?php if (version_compare(PHP_VERSION, '5.2.2', '<')){ ?>
 		              <tr>
 		                <td colspan="7">
 		            <div class="warning">
 		            <p>Your current PHP Version is: <?php print PHP_VERSION;  ?></p><h4>Please upgrade your PHP Release to 5.2.2 or higher</h4>
 		            <p>PHP 4 is no longer under development. Security updates will not be released.</p>
 		            </div>
 		                </td>
 		             </tr>
 		        <?php } ?>
		</tr>
		<?php if($e_adc) { ?>
		<tr>
			<td colspan="6" style="font-size: 10px;" class="bad">Please note: AddDefaultCharset is set to <?php echo $e_adc;?> in apache.conf, .htaccess file or in your php config file.<br />If you have to use umlauts (e.g. &auml; &aacute;) please change this to Off. - Or use <?php echo $e_adc;?> inside LEPTON, too.</td>
		</tr>
		<?php } ?>
		</table>
    </fieldset>
    <fieldset> <legend>file permissions</legend>
		<table cellpadding="3" cellspacing="0" width="100%" align="center">
		<tr>
			<td colspan="8" style="padding-bottom:10px;">Please check the following files/folders are writeable before continuing...</td>
		</tr>
		<tr>
			<td style="color: #666666;">/page/</td>
			<td><?php if(is_writable('../page/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../page/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?></td>
		</tr>
		<tr>
			<td style="color: #666666;">/media/</td>
			<td><?php if(is_writable('../media/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../media/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?></td>
		</tr>
		<tr>
			<td style="color: #666666;">/templates/</td>
			<td><?php if(is_writable('../templates/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../templates/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?></td>
		</tr>
		<tr>
			<td style="color: #666666;">/modules/</td>
			<td><?php if(is_writable('../modules/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../modules/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?></td>
		</tr>
		<tr>
			<td style="color: #666666;">/languages/</td>
			<td><?php if(is_writable('../languages/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../languages/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?></td>
		</tr>
		<tr>
			<td style="color: #666666;">/install/</td>
			<td><?php if(is_writable('../install/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../install/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?></td>
		</tr>
		<tr>
			<td style="color: #666666;">Lepton root directory</td>
			<td><?php if(is_writable('../')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?></td>
		</tr>
		<tr>
			<td style="color: #666666;">/temp/</td>
			<td><?php if(is_writable('../temp/')) { echo '<font class="good">Writeable</font>'; } elseif(!file_exists('../temp/')) { echo '<font class="bad">Directory Not Found</font>'; } else { echo '<font class="bad">Unwriteable</font>'; } ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		</table>
    </fieldset>
    <fieldset> <legend>path settings</legend>
		<table cellpadding="3" cellspacing="0" width="100%" align="center">
		<tr>
			<td colspan="2" style="padding-bottom:10px;">Please check your path settings, and select a default timezone and a default backend language...</td>
		</tr>
		<tr>
			<td width="125" style="color: #666666;">
				Absolute URL:
			</td>
			<td>
				<?php
				// Try to guess installation URL
				$guessed_url = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"];
				$guessed_url = rtrim(dirname($guessed_url), 'install');
				?>
				<input <?php echo field_error('wb_url');?> type="text" tabindex="1" name="wb_url" style="width: 97%;" value="<?php if(isset($_SESSION['wb_url'])) { echo $_SESSION['wb_url']; } else { echo $guessed_url; } ?>" />
			</td>
		</tr>
		<tr>
			<td style="color: #666666;">
				Default Timezone:
			</td>
			<td>
				<select <?php echo field_error('default_timezone');?> tabindex="3" name="default_timezone_string" style="width: 100%;">
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
			</td>
		</tr>
		<tr>
			<td style="color: #666666;">
				Default Language:
			</td>
			<td>
				<select <?php echo field_error('default_language');?> tabindex="3" name="default_language" style="width: 100%;">
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
			</td>
		</tr>
		</table>
    </fieldset>
    <fieldset> <legend>OS settings</legend>
		<table cellpadding="5" cellspacing="0" width="100%" align="center">
		<tr>
			<td colspan="3" style="padding-bottom:10px;">Please specify your operating system information below...</td>
		</tr>
		<tr>
			<td width="380">
				Server Operating System:
			</td>
			<td width="180">
				<input type="radio" tabindex="4" name="operating_system" id="operating_system_linux" onclick="document.getElementById('file_perms_box').style.display = 'block';" value="linux"<?php echo $osl; ?> />
				<span style="cursor: pointer;" onclick="javascript: change_os('linux');">Linux/Unix based</span>
				<br />
				<input type="radio" tabindex="5" name="operating_system" id="operating_system_windows" onclick="document.getElementById('file_perms_box').style.display = 'none';" value="windows"<?php echo $osw; ?> />
				<span style="cursor: pointer;" onclick="javascript: change_os('windows');">Windows</span>
			</td>
   	</tr>
		<tr id="file_perms_box" style="display: <?php echo $startstyle; ?>" >
      <td width="380">
				<label for="world_writeable">World-writeable file permissions (777)</label>
        <span style="font-size: 10px; color: #666666;">(Please note: only recommended for testing environments)</span>
      </td>
			<td>
				<div style="margin: 0; padding: 0; display: <?php if(isset($_SESSION['operating_system']) AND $_SESSION['operating_system'] == 'windows') { echo 'none'; } else { echo 'block'; } ?>;">
					<input type="checkbox" tabindex="6" name="world_writeable" id="world_writeable" value="true"<?php if(isset($_SESSION['world_writeable']) AND $_SESSION['world_writeable'] == true) { echo 'checked'; } ?> />
				</div>
      </td>
		</tr>
		</table>
    </fieldset>
    <fieldset> <legend>database settings</legend>
		<table cellpadding="5" cellspacing="0" width="100%" align="center">
    		<tr>
    			<td colspan="5" style="padding-bottom:10px;">Please enter your MySQL database server details below...</td>
    		</tr>
    		<tr>
    			<td width="120" style="color: #666666;">Host Name:</td>
    			<td width="230">
    				<input <?php echo field_error('database_host');?> type="text" tabindex="7" name="database_host" style="width: 98%;" value="<?php if(isset($_SESSION['database_host'])) { echo $_SESSION['database_host']; } else { echo 'localhost'; } ?>" />
    			</td>
        </tr>
      	<tr>
    			<td style="color: #666666;">Database Name:<br />[a-zA-Z0-9_-]</td>
    			<td>
    				<input <?php echo field_error('database_name');?> type="text" tabindex="8" name="database_name" style="width: 98%;" value="<?php if(isset($_SESSION['database_name'])) { echo $_SESSION['database_name']; } else { echo 'my-sql-db-name'; } ?>" />
    			</td>
        </tr>
    		<tr>
    			<td width="70" style="color: #666666;">Database User:</td>
    			<td>
    				<input <?php echo field_error('database_username');?> type="text" tabindex="9" name="database_username" style="width: 98%;" value="<?php if(isset($_SESSION['database_username'])) { echo $_SESSION['database_username']; } else { echo 'my-user-name'; } ?>" />
    			</td>
    		</tr>

    		<tr>
    			<td style="color: #666666;">Database Password:</td>
    			<td>
    				<input type="password" tabindex="10" name="database_password" style="width: 98%;"<?php if(isset($_SESSION['database_password'])) { echo ' value = "'.$_SESSION['database_password'].'"'; } ?> />
    			</td>
    </tr>
		<tr>
			<td style="color: #666666;">Table Prefix:<br />[a-zA-Z0-9_]</td>
			<td>
       <input <?php echo field_error('table_prefix');?> type="text" tabindex="11" name="table_prefix" style="width:98%;"<?php if(isset($_SESSION['table_prefix'])) { echo ' value = "'.$_SESSION['table_prefix'].'"'; }  else { echo " value='lep_'"; }?> />
	<!-- <input <?php echo field_error('table_prefix');?> type="text" tabindex="11" name="table_prefix" style="width:98%;"<?php if(isset($_SESSION['table_prefix'])) { echo ' value = "'.$_SESSION['table_prefix'].'"'; }  else { echo 'lep_'; }?> /> -->
			</td>
    </tr>
		<tr>
       <td style="color: #666666;">Option:</td>
      <td colspan="6">
				<input type="checkbox" tabindex="12" name="install_tables" id="install_tables" value="true"<?php if(!isset($_SESSION['install_tables'])) { echo ' checked="checked"'; } elseif($_SESSION['install_tables'] == 'true') { echo ' checked="checked"'; } ?> />
				<label for="install_tables" style="color: #666666;">Install Tables</label>
        <span style="font-size: 10px; color: #666666;">(Please note: May remove existing tables and data)</span>
      </td>
		</tr>
    </table>
    </fieldset>
    <fieldset><legend>Site settings</legend>
   	<table cellpadding="5" cellspacing="0" width="100%" align="center">
   	<tr>
			<td colspan="5">Please enter your website title below...</td>
		</tr>
		<tr>
			<td style="color: #666666;" colspan="1">Website Title:</td>
			<td colspan="4">
				<input <?php echo field_error('website_title');?> type="text" tabindex="13" name="website_title" style="width: 97%;" value="<?php if(isset($_SESSION['website_title'])) { echo $_SESSION['website_title']; } else { echo 'LEPTON CMS'; } ?>" />
			</td>
		</tr>
		<tr>
			<td colspan="5">&#160;</td>
		</tr>
     <tr>
			<td colspan="5"><h1>Please enter your Administrator account details below...</h1></td>
		</tr>
		<tr>
			<td style="color: #666666;width:10%;">Username:</td>
			<td style="width:30%;">
				<input <?php echo field_error('admin_username');?> type="text" tabindex="14" name="admin_username" style="width: 90%;" value="<?php if(isset($_SESSION['admin_username'])) { echo $_SESSION['admin_username']; } else { echo 'admin'; } ?>" />
			</td>
			<td style="width:3%;">&nbsp;</td>
			<td style="color: #666666;width:10%;">Password:</td>
			<td style="width:30%;">
				<input <?php echo field_error('admin_password');?> type="password" tabindex="16" name="admin_password" style="width: 90%;"<?php if(isset($_SESSION['admin_password'])) { echo ' value = "'.$_SESSION['admin_password'].'"'; } ?> />
			</td>
		</tr>
		<tr>
			<td style="color: #666666;">Email:</td>
			<td>
				<input <?php echo field_error('admin_email');?> type="text" tabindex="15" name="admin_email" style="width: 90%;"<?php if(isset($_SESSION['admin_email'])) { echo ' value = "'.$_SESSION['admin_email'].'"'; } ?> />
			</td>
			<td>&nbsp;</td>
			<td style="color: #666666;">Re-Password:</td>
			<td>
				<input <?php echo field_error('admin_repassword');?> type="password" tabindex="17" name="admin_repassword" style="width: 90%;"<?php if(isset($_SESSION['admin_repassword'])) { echo ' value = "'.$_SESSION['admin_repassword'].'"'; } ?> />
			</td>
		</tr>
		<tr>
			<td colspan="5" style="padding: 10px; padding-bottom: 0;"><h1 style="font-size: 0px;">&nbsp;</h1></td>
		</tr>
		<tr>
			<td colspan="8">
				<table cellpadding="0" cellspacing="0" width="100%" border="0">
				<tr valign="top">
					<td style="color:#f00;width:20%">Please note: &nbsp;</td>
					<td>
						<strong>Lepton Core </strong>is released under the
						<a href="http://www.gnu.org/licenses/gpl.html" target="_blank" tabindex="19" style="color:#333;text-decoration:underline;">GNU General Public License</a>
            <br />
            <strong>Lepton CMS Package </strong>is released under different licenses. Please check file headers or info.php of modules for exact license.
						<br />
            <br />
						By clicking <strong>Install Lepton</strong> you agree to all licenses.
					</td>
				</tr>
				</table>
			</td>
      </tr>
      <tr>
      <td></td>
			<td colspan="1">
				<input type="submit" id="SaveAccount" tabindex="20" name="submit" value="Install Lepton" class="submit" />
			</td>
		</tr>
		</table>
		</fieldset>
	</td>
</tr>
</table>
</form>
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding: 10px 0px 10px 0px;">
<tr>
	<td style="font-size: 10px; text-align:center; color:#fff;">
      <!-- Please note: the below reference to the GNU GPL should not be removed, as it provides a link for users to read about warranty, etc. -->
      <a href="http://wwww.lepton-cms.org" title="Lepton CMS">Lepton Core</a> is released under the
      <a href="http://www.gnu.org/licenses/gpl.html" title="Lepton Core is GPL">GNU General Public License</a>.
      <!-- Please note: the above reference to the GNU GPL should not be removed, as it provides a link for users to read about warranty, etc. -->
	    <br /><a href="http://wwww.lepton-cms.org" title="Lepton CMS">Lepton CMS Package</a> is released under several different licenses.
  </td>
</tr>
</table>

</body>
</html>
