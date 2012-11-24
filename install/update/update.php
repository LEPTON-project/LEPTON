<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2012, LEPTON Project
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
 *  update LEPTON to 2series from latest release 1series , check release
 */
$lepton_version = $database->get_one("SELECT `value` from `" . TABLE_PREFIX . "settings` where `name`='lepton_version'");
if (version_compare($lepton_version, "1.2.1", "=>"))
{
    die("<h4>'>ERROR:UNABLE TO UPDATE, LEPTON Version : " . LEPTON_VERSION . " </h4>");
}
echo '<h3>Current process : updating to LEPTON 2.0.0</h3>';

/**
 *  database modification
 */
echo '<h3>Currently no database modifications</h3>';

/**
 *  run install.php of all new modules
 *
 */
$install_modules = array(
    "lib_dwoo", 
    "lib_lepton",         
    "dropleps"                  
);

foreach ($install_modules as $module)
{
    $temp_path = WB_PATH . "/modules/" . $module . "/install.php";

    if (file_exists($temp_path))
        require($temp_path);
} 
echo "<h3>all new modules install: successfull</h3>"; 
 

/**
 *  run upgrade.php of all modified modules
 *
 */
$upgrade_modules = array(
    "lib_jquery", 
    "lib_dwoo", 
    "lib_lepton",         
    "dropleps",                  
    "tiny_mce_jq"

);

foreach ($upgrade_modules as $module)
{
    $temp_path = WB_PATH . "/modules/" . $module . "/upgrade.php";

    if (file_exists($temp_path))
        require($temp_path);
} 
echo "<h3>all modified and new modules update: successfull</h3>";

/**
 *  switch droplets module to dropleps module | can be deleted, because file is included during install process of dropleps
 */
if (file_exists(LEPTON_PATH . '/modules/droplets/info.php')) {
    include LEPTON_PATH . '/modules/dropleps/switch_dropleps.php';
}
echo "<h3>switch to dropleps module: successfull</h3>";

/**
 *  remove include/pclzip dir 
 */
 
if (file_exists(LEPTON_PATH . '/include/pclzip/pclzip.php')) {
    	rm_full_dir( LEPTON_PATH.'/include/pclzip' );
} 
echo "<h3>delete pclzip directory: successfull</h3>";

/**
 *  reload all addons
 */
if (file_exists('reload.php')) {
    include 'reload.php';
} 
echo "<h3>reload all addons: successfull</h3>";

// at last: set db to current release-no
$database->query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'2.0.0\' WHERE `name` =\'lepton_version\'');

/**
 *  success message
 */
echo "<h3>update to LEPTON 2.0.0 successfull!</h3>"; 
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