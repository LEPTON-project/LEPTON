<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2013 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 */

// set error level
// ini_set('display_errors', 1);
// error_reporting(E_ALL|E_STRICT);

require_once('../config.php');
global $admin;
if (!is_object($admin))
{
    require_once(WB_PATH . '/framework/class.admin.php');
    $admin = new admin('Addons', 'modules', false, false);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>LEPTON Update Script</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<link href="http://lepton-cms.org/_packinstall/update.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="top">
  <div id="top-logo"></div>
  <div id="top-text">LEPTON update script</div>
</div>
<div id="update-script">
<?php

/**
 *  LEPTON 2series , check release
 */
$lepton_version = $database->get_one("SELECT `value` from `" . TABLE_PREFIX . "settings` where `name`='lepton_version'");
if (version_compare($lepton_version, "1.2.1", "<"))
{
    die("<h4>ERROR:NO UPGRADE POSSIBLE, your LEPTON Version is : $lepton_version but you need 1.2.1 as a minimum</h4>
         <h4>Please update to <a href='http://www.lepton-cms.org/english/download/stable.php' target='_blank'>current LEPTON stable 1series </a> first</h4>");
}

/**
 *  check if database has charset utf-8
 */
$sql_query = "SELECT * FROM ".TABLE_PREFIX."settings WHERE name='default_charset'";
$result = mysql_query ($sql_query);
$charset = mysql_fetch_assoc ($result);

if ($charset['value'] != 'utf-8')
{
    echo("<h4>Your charset is <strong>$charset[value]</strong>, no upgrade possible </h4>");
    echo('<h4>LEPTON 2series need a <i>"utf8"</i> database</h4>');
    echo('<h4>please modify your database to <i>"utf8"</i> first</h4>');
    die('<h4>there are lots of tutorials on the net</h4>');
}

echo("<h3>Your database charset is $charset[value], upgrade to 2.0.0 possible </h3>");

/**
 *  UPGRADE to LEPTON 2series from latest release 1series , check release
 */
$lepton_version = $database->get_one("SELECT `value` from `" . TABLE_PREFIX . "settings` where `name`='lepton_version'");
if (version_compare($lepton_version, "2.0", "<"))
{
    echo("<h3>Your LEPTON Version : $lepton_version </h3>");
    include 'scripts/2_upgrade.php';
}

/**
 *  update to LEPTON 2.1.0 , check release
 */
$lepton_version = $database->get_one("SELECT `value` from `" . TABLE_PREFIX . "settings` where `name`='lepton_version'");
if (version_compare($lepton_version, "2.0.0", "="))
{
    echo("<h3>Your LEPTON Version : $lepton_version </h3>");
//    include 'scripts/210_update.php';
}


/**
 *  reload all addons
 */
if (file_exists('reload.php')) {
    include 'reload.php';
} 


/**
 *  success message
 */
echo "<br /><h3>Congratulation, updgrade procedure complete!</h3><br /><hr /><br />";

/**
 *  login message
 */

echo "<br /><h3><a href=\"../admins/login/index.php\">please login and check update</></h3>";
?>
</div>
<div id="update-footer">
      <!-- Please note: the below reference to the GNU GPL should not be removed, as it provides a link for users to read about warranty, etc. -->
      <a href="http://wwww.lepton-cms.org" title="Lepton CMS">Lepton Core</a> is released under the
      <a href="http://www.gnu.org/licenses/gpl.html" title="Lepton Core is GPL">GNU General Public License</a>.
      <!-- Please note: the above reference to the GNU GPL should not be removed, as it provides a link for users to read about warranty, etc. -->
	    <br /><a href="http://wwww.lepton-cms.org" title="Lepton CMS">Lepton CMS Package</a> is released under several different licenses.
</div>
</body>
</html>