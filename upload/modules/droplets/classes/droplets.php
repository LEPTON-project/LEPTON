<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          Droplets
 * @author          LEPTON Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

class droplets extends LEPTON_abstract
{
    /**
     *  Own instance of this module
     */
    public static $instance;
    
    /**
     *  Initialize own module settings, values, methods.
     */
    public function initialize()
    {
    
    }

    /**
     *  Test if a given droplet exists.
     *  
     *  @param  mixed   String (DropletName) or integer (droplet_id).
     *
     *  @return bool    True if exists - false if not.
     *
     */
    public function dropletExists( $sNameOrID = NULL )
    {
        if( NULL === $sNameOrID)
        {
            return false;
        }
        
        $database = LEPTON_database::getInstance();
        
        $test = intval($sNameOrID);
        if(0 === $test)
        {
            if( true === is_string($sNameOrID))
            {
                $id = $database->get_one("SELECT `id` FROM `".TABLE_PREFIX."mod_droplets` WHERE `name` = '".$sNameOrID."'");
                return ($id != NULL);
            }
        } else {
            $id = $database->get_one("SELECT `id` FROM `".TABLE_PREFIX."mod_droplets` WHERE `id` = '".$test."'");
            return ($id != NULL);
        }
    }
}