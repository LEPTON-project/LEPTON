<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */


// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {	
	include(LEPTON_PATH.'/framework/class.secure.php'); 
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



// Byte convert for filesize
function byte_convert($bytes)
{
    $symbol = array(' bytes', ' KB', ' MB', ' GB', ' TB');
    $exp = 0;
    $converted_value = 0;
    if ($bytes > 0)
    {
        $exp = floor( log($bytes) / log(1024));
        $converted_value = ($bytes / pow( 1024, floor($exp)));
    }
    return sprintf('%.2f '.$symbol[$exp], $converted_value);
}

// Get file extension
function get_filetype($fname)
{
    $pathinfo = pathinfo($fname);
    $extension = (isset ($pathinfo['extension'])) ? strtolower($pathinfo['extension']) : 'unknown';
    return $extension;
}

// Get file extension for icons
function get_filetype_icon($fname)
{
    $pathinfo = pathinfo($fname);
    $extension = (isset ($pathinfo['extension'])) ? strtolower($pathinfo['extension']) : 'unknown';

    if (file_exists(THEME_PATH.'/images/files/'.$extension.'.png'))
    {
        return $extension;
    } else {
        return 'unknown';
    }
}

function ShowTip($name, $detail = '')
{
    $parts = explode(".", $name);
    $ext = strtolower( end($parts));
    if (strpos('.gif.jpg.jpeg.png.bmp.', $ext))
	{
       return 'onmouseover="overlib(\'&lt;img src=\\\''.$name.'\\\' maxwidth=\\\'200\\\'  maxheight=\\\'200\\\'>\',VAUTO, WIDTH)" onmouseout="nd()" ';
    } else {
       return '';
    }
}

function fsize($size)
{
    if ($size == 0)
        return ("0 Bytes");
    $filesizename = array(" bytes", " kB", " MB", " GB", " TB");
    return round( $size / pow( 1024, ($i = floor( log($size, 1024)))), 1 ).$filesizename[$i];
}

function get_media_settings()
{
    global $database;
    $pathsettings = array();
    $sql = 'SELECT `name`,`value` FROM `'.TABLE_PREFIX.'settings` WHERE `name` = \'mediasettings\' ';
    if (($result = $database->query($sql)) && ($result->numRows() > 0))
    {
        $settings = $result->fetchRow();
        $pathsettings = unserialize($settings['value']);
    } else {
        $sql = 'INSERT INTO ".TABLE_PREFIX."settings ';
        $sql .= '(`name`,`value`) VALUES (\'mediasettings\', \'\')';
        if($database->query($sql))
		{
            /*  nothing */
		}
    }

	$admin_only = isset($pathsettings['global']['admin_only']) ? $pathsettings['global']['admin_only'] : '';
	$pathsettings['global']['admin_only'] = ($admin_only != '') ? true : false;
	$show_thumbs = isset($pathsettings['global']['show_thumbs']) ? $pathsettings['global']['show_thumbs'] : '';
	$pathsettings['global']['show_thumbs'] = ($show_thumbs != '') ? true : false;

    return $pathsettings;
}

function save_media_settings($pathsettings)
{
    global $database, $admin;
	$retvalue = 0;

    include_once(get_include(LEPTON_PATH.'/framework/summary.functions.php'));
    if (!is_null( $admin->get_post_escaped("save")))
    {

        $sql = 'SELECT COUNT(`name`) FROM `'.TABLE_PREFIX.'settings` ';
		$where_sql = 'WHERE `name` = \'mediasettings\' ';
        $sql = $sql.$where_sql;
	    //Check for existing settings entry, if not existing, create a record first!
        if (($row = $database->get_one($sql)) == 0 )
        {
			$sql  = 'INSERT INTO `'.TABLE_PREFIX.'settings` SET ';
			$where_sql = '';
            
        } else {
			$sql  = 'UPDATE `'.TABLE_PREFIX.'settings` SET ';
        }
		
		/**
		 *	directory_list has been modify.
		 */
		$dirs = array();
		$skip = LEPTON_PATH;
        directory_list(
        	LEPTON_PATH.MEDIA_DIRECTORY,
        	false,
        	0,
        	$dirs,
        	$skip
        );

        foreach ($dirs AS &$name)
        {
            $r = str_replace( array('/', ' '), '_', $name );

            if($admin->get_post_escaped($r.'-w') != null)
			{
	            $w = (int) $admin->get_post_escaped($r.'-w');
	            $retvalue++;
			} else {
                $w = isset($pathsettings[$r]['width']) ? $pathsettings[$r]['width'] : '-';
			}
			$pathsettings[$r]['width'] = $w;

            if($admin->get_post_escaped($r.'-h') != null)
			{
	            $h = (int) $admin->get_post_escaped($r.'-h');
	            $retvalue++;
			} else {
				$h = isset($pathsettings[$r]['height']) ? $pathsettings[$r]['height'] : '-';
			}
			$pathsettings[$r]['height'] = $h;
        }

		$pathsettings['global']['admin_only'] = ($admin->get_post_escaped('admin_only') != null)
			? (bool)$admin->get_post_escaped('admin_only') 
			: false
			;

		$pathsettings['global']['show_thumbs'] = ($admin->get_post_escaped('show_thumbs') != null)
			? (bool)$admin->get_post_escaped('show_thumbs')
			: false
			;

        $fieldSerialized = serialize($pathsettings);
        $sql .= '`value` = \''.$fieldSerialized.'\' ';
        $sql .= $where_sql;
        if($database->query($sql))
		{
			return $retvalue;
		}
    }
    return $retvalue;
}

/*
* @param object &$wb: $wb from frontend or $admin from backend
* @return array: list of new entries
* @description: callback remove path in files/dirs stored in array
* @example: array_walk($array,'remove_path',PATH);
*/
//

if (!function_exists('remove_path'))
{

    function remove_path(& $path, $key, $vars = '')
    {
        $path = str_replace($vars, '', $path);
    }

}


?>