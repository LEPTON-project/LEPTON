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
    while ($value = $res_addons->fetchRow())
    {
        if (!file_exists(LEPTON_PATH . '/modules/' . $value['directory']))
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
 *	@notice: we're using the function 'scan_current_dir', so we are not in the need 
 *			 to test for file- or foldernames like ".", ".git" or "index.php".
 */
$templates = scan_current_dir(LEPTON_PATH . '/templates');
if (count($templates['path']) >0)
{
    // Delete not existing templates from database
    $sql = 'DELETE FROM  `' . TABLE_PREFIX . 'addons`  WHERE `type` = \'template\'';
    $database->query($sql);
    
    // Load all templates
    foreach($templates['path'] as &$template_folder)
    {
		require(LEPTON_PATH . '/templates/' . $template_folder . "/info.php");
		load_template(LEPTON_PATH . '/templates/' . $template_folder);
    }
}

/**
 *  Reload Languages
 *	@notice: we're using the function 'scan_current_dir', so we are not in the need 
 *			 to test for file- or foldernames like ".", ".git" or "index.php".
 */
$languages = scan_current_dir(LEPTON_PATH . '/languages/');
if (count($languages['filename']) > 0)
{
    // Delete  not existing languages from database
    $sql = 'DELETE FROM  `' . TABLE_PREFIX . 'addons`  WHERE `type` = \'language\'';
    $database->query($sql);
    
    // Load all languages
    foreach($languages['filename'] as &$lang_file)
    {
		load_language(LEPTON_PATH . '/languages/' . $lang_file);
    }
}
 
echo "<h3>All addons successfully reloaded!</h3>";
?>