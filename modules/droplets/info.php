<?php
/**
 *
 * @category        module
 * @package         droplet
 * @author          Ruud Eisinga (Ruud) John (PCWacht) Bianka (WebBird)
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @link			      http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 4.4.9 and higher
 * @version         $Id$
 * @filesource		  $HeadURL$
 * @lastmodified    $Date$
 *
 */

$module_directory = 'droplets';
$module_name = 'Droplets';
$module_function = 'tool';
$module_version = '1.5.4';
$module_platform = '1.x';
$module_author = 'Ruud, pcwacht, WebBird, Dietrich Roland Pehlke (last)';
$module_license = 'GNU General Public License';
$module_description = 'This tool allows you to manage your local Droplets.';

$module_home = 'http://www.websitebakers.com/pages/droplets/about-droplets.php';
$module_guid = '9F2AC2DF-C3E1-4E15-BA4C-2A86E37FE6E5';

/**
 * Version history
 *
 *	1.5.3	2012-11-14	remove return in evalDroplets as the content is passed by reference.
 *
 * v1.51 - Bianka Martinovic ("WebBird")
 *       - updated NO language file
 *       - added RU language file (thanks to forum user "Eugene"!)
 *       - new: Error message when no droplet is marked while clicking on
 *              'Export' or 'Delete' button
 *
 * v1.5  - Bianka Martinovic ("WebBird")
 *       - fix: hardcoded admin path
 *
 * v1.4  - Bianka Martinovic ("WebBird")
 *       - new: Duplicate a droplet with one click
 *       - new: Show module version
 *       - new: added links to AMASP module and droplets download pages
 *       - new: Show droplets count
 *
 **/
?>