<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author		LEPTON Project
 * @copyright	2010-2014 LEPTON Project
 * @link		http://www.LEPTON-cms.org
 * @license		http://www.gnu.org/licenses/gpl.html
 * @license_terms	please see LICENSE and COPYING files in your package
 * @reformatted 2013-05-30
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH'))
{
    include(LEPTON_PATH . '/framework/class.secure.php');
}
else
{
    $oneback = "../";
    $root    = $oneback;
    $level   = 1;
    while (($level < 10) && (!file_exists($root . '/framework/class.secure.php')))
    {
        $root .= $oneback;
        $level += 1;
    }
    if (file_exists($root . '/framework/class.secure.php'))
    {
        include($root . '/framework/class.secure.php');
    }
    else
    {
        trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
    }
}
// end include class.secure.php

class password
{
    
    public function __construct()
    {
        
    }
    
    public function __destruct()
    {
        
    }
    
    /**
     *	Generating a new random password
     *
     *	@param	int	The length of the pass
     *	@return	str The generated password
     */
    public static function generate_password($length = 8)
    {
        $r = array_merge(range("a", "z"), range("A", "Z"), range(1, 9), range(1, 9), array(
            'Â§',
            '!',
            '&',
            '_',
            '-',
            '@',
            '.',
            '|'
        ));
        $r = array_diff($r, array(
            'i',
            'l',
            'o'
        ));
        for ($i = 0; $i < 3; $i++)
            $r = array_merge($r, $r);
        shuffle($r);
        $r = array_reverse($r);
        return implode("", array_slice($r, mt_rand(0, 100), intval($length)));
    } // generate_password()
    
}

?>