<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010, Website Baker Project
 * @copyright       2010-2013 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @version         $Id: login_form.php 1299 2011-11-02 17:04:34Z frankh $
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
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

$username_fieldname = 'username';
$password_fieldname = 'password';
?>
<h1>&nbsp;Login</h1>
&nbsp;<?php echo $thisApp->message; ?>
<br />
<br />

<form action="<?php echo WB_URL.'/account/login.php'; ?>" method="post">
<p style="display:none;"><input type="hidden" name="username_fieldname" value="<?php echo $username_fieldname; ?>" /></p>
<p style="display:none;"><input type="hidden" name="password_fieldname" value="<?php echo $password_fieldname; ?>" /></p>
<p style="display:none;"><input type="hidden" name="redirect" value="<?php echo $thisApp->redirect_url;?>" /></p>

<table cellpadding="5" cellspacing="0" border="0" width="90%">
<tr>
	<td style="width:100px"><?php echo $TEXT['USERNAME']; ?>:</td>
	<td class="value_input">
		<input type="text" name="<?php echo $username_fieldname; ?>" maxlength="30" style="width:220px;"/>
    	<script type="text/javascript">
    	// document.login.<?php echo $username_fieldname; ?>.focus();
    	var ref= document.getElementById("<?php echo $username_fieldname; ?>");
    	if (ref) ref.focus();
    	</script>
	</td>
</tr>
<tr>
	<td style="width:100px"><?php echo $TEXT['PASSWORD']; ?>:</td>
	<td class="value_input">
		<input type="password" name="<?php echo $password_fieldname; ?>" maxlength="30" style="width:220px;"/>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="submit" name="submit" value="<?php echo $TEXT['LOGIN']; ?>"  />
		<input type="reset" name="reset" value="<?php echo $TEXT['RESET']; ?>"  />
	</td>
</tr>
</table>

</form>

<br />

<a href="<?php echo WB_URL; ?>/account/forgot.php"><?php echo $TEXT['FORGOTTEN_DETAILS']; ?></a>