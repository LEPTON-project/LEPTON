<?php
/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2014 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

/**
 *  reload all addons
 *  Modules first
 */
// first remove addons entrys for modules that don't exist
$sql = 'SELECT `directory` FROM `' . TABLE_PREFIX . 'addons` WHERE `type` = \'module\' ';
if ($res_addons = $database->query($sql))
{
    while ($value = $res_addons->fetchRow(MYSQL_ASSOC))
    {
        if (!file_exists(WB_PATH . '/modules/' . $value['directory']))
        {
            $sql = "DELETE FROM `" . TABLE_PREFIX . "addons` WHERE `directory` = '" . $value['directory'] . "'";
            $database->query($sql);
        }
    }
}

/**
 *	Now check modules folder with entries in addons
 */
$modules = scan_current_dir(LEPTON_PATH . '/modules');
if (count($modules['path']) > 0)
{
    foreach ($modules['path'] as &$value)
    {
        $code_version = get_modul_version($value);
        $db_version   = get_modul_version($value, false);
        if (($db_version != null) && ($code_version != null))
        {
            require(LEPTON_PATH . '/modules/' . $value . "/info.php");
            load_module(LEPTON_PATH . '/modules/' . $value);
        }
    }
}

/**
 *  Reload Templates
 *
 */
if ($handle = dir(LEPTON_PATH . '/templates'))
{
    // Delete all existing templates from database
    $sql = 'DELETE FROM  `' . TABLE_PREFIX . 'addons`  WHERE `type` = \'template\'';
    $database->query($sql);
    // loop over all templates
    while (false !== ($file = $handle->read() ) )
    {
        if ($file != '' && $file[0] != '.' && $file != 'index.php')
        {
            require(LEPTON_PATH . '/templates/' . $file . "/info.php");
            load_template(LEPTON_PATH . '/templates/' . $file);
        }
    }
    $handle->close();
}

/**
 *  Reload Languages
 *
 */
if ($handle = dir(LEPTON_PATH . '/languages/'))
{
    // delete  not existing languages from database
    $sql = 'DELETE FROM  `' . TABLE_PREFIX . 'addons`  WHERE `type` = \'language\'';
    $database->query($sql);
    // loop over all languages
    while (false !== ($file = $handle->read() ) )
    {
        if ($file != '' && $file[0] != '.' && $file != 'index.php')
        {
            load_language(LEPTON_PATH . '/languages/' . $file);
        }
    }
    $handle->close();
}
 
echo "<h3>All addons successfully reloaded!</h3>";
?>