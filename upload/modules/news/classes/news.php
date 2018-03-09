<?php

/**
 *  @module         news
 *  @version        see info.php of this module
 *  @author         Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos), LEPTON Project
 *  @copyright      2004-2010 Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos) 
 * 	@copyright      2010-2018 LEPTON Project 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 * 
 */

class news extends LEPTON_abstract
{
	static $instance;
    
    public $display_details = false;
    
    public $displayed_news = 0;
    
    public $allGroups = array();
    
    public function initialize()
    {
        self::$instance->getAllGroups();
    }
    
    private function getAllGroups()
    {
        $aTemp = array();
        LEPTON_database::getInstance()->execute_query(
            "SELECT * FROM `".TABLE_PREFIX."mod_news_groups`",
            true,
            $aTemp,
            true
        );
        
        foreach($aTemp as $group)
        {
            self::$instance->allGroups[ $group['group_id'] ] = $group;
        }
    }
}
?>