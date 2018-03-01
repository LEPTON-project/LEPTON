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
     *  Holds a list of current backup-filenames.
     *  @type   array
     *  @access public
     */
    public $backups = array();
    
    /**
     *  Holds the current values from the db.
     *  @type   array
     *  @access public
     */
    public $settings = array();
    
    /**
     *  Own instance of this module
     *  @type   object
     */
    public static $instance;
    
    /**
     *  Initialize own module settings, values, methods.
     *
     */
    public function initialize()
    {
        self::$instance->getSettings();
        self::$instance->getBackups();
    }
    
    /**
     *  Read the /export/ dir for existing *.zip backup files
     *
     */
    public function getBackups()
    {
        $sLookUpPath = dirname(__DIR__)."/export/";
        LEPTON_handle::register( "file_list" );
        self::$instance->backups = file_list( $sLookUpPath, array(), false, "zip", $sLookUpPath."/");
    }

    /**
     *  Get the current values from the db
     *
     */
    public function getSettings()
    {
        $aTempSettings = array();
        LEPTON_database::getInstance()->execute_query(
            "SELECT * FROM `". TABLE_PREFIX . "mod_droplets_settings`",
            true,
            $aTempSettings,
            true
        );
        
        foreach($aTempSettings as &$ref)
        {
            self::$instance->settings[ $ref['attribute'] ] = explode("|", $ref['value']);
        }
        
    } 
    
    /**
     *  Test if a given droplet is installed.
     *    
     *  @param  mixed   $sNameOrID  String (DropletName) or integer (droplet_id).
     *  @return boolean True if exists - false if not.
     *
     *  @code{.php}
     *
     *      // by name
     *      $bIsItInstalled = droplets::dropletExists( 'hello_world' );
     *
     *      // by id
     *      $bIsItInstalled = droplets::dropletExists( 231 );
     *
     *  @endcode
     */
    public static function dropletExists( $sNameOrID = NULL )
    {
        if( NULL === $sNameOrID)
        {
            return false;
        }
        
        $database = LEPTON_database::getInstance();
        
        $id = $database->get_one("SELECT `id` FROM `".TABLE_PREFIX."mod_droplets` WHERE (`id`='".$sNameOrID."' OR `name` = '".$sNameOrID."')");
        return ($id != NULL);
    }
       
    /**
     *  Try to test the dropletCode for a given droplet id or name
     *
     *  @param  mixed   String (DropletName) or integer (droplet_id).
     *  
     *  @return bool    True if success, otherwise false;
     *
     *  @code{.php}
     *
     *      // by name
     *      $bIsCodeOk = droplets::testDroplet( 'hello_world' );
     *
     *      // by id
     *      $bIsCodeOk = droplets::testDroplet( 231 );
     *
     *  @endcode
     *
     *
     */
    static public function testDroplet( $sNameOrID = NULL)
    {
        if( NULL === $sNameOrID)
        {
            return false;
        }
        
        if( false === self::dropletExists( $sNameOrID ) )
        {
            return false;
        
        } else {
            $database = LEPTON_database::getInstance();
            
            $aDroplet = array();
            $database->execute_query(
                "SELECT `code`,`name` FROM `".TABLE_PREFIX."mod_droplets` WHERE (`id`='".$sNameOrID."' OR `name` = '".$sNameOrID."')",
                true,
                $aDroplet,
                false
            );
            if($database->is_error())
            {
                echo LEPTON_tools::display( $database->get_Error() , "div", "ui message red");
            }
            if( 0 === count($aDroplet))
            { 
                return false;
            } else {
                
                try{

                    eval("return NULL;".$aDroplet['code'].";");

                } catch(ParseError $oError) {
                
                    echo (LEPTON_tools::display( $oError->getMessage() , "div", "ui message red"));
                    return false;
                }
                return true;
            }
        }
    }
}