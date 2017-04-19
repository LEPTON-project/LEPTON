<?php

/**
 *  @module         news
 *  @version        see info.php of this module
 *  @author         Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos), LEPTON Project
 *  @copyright      2004-2010 Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos) 
 * 	@copyright      2010-2017 LEPTON Project 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 * 
 */

class news
{
	/**
     * @var Singleton The reference to *Singleton* instance of this class
     */
    private static $instance;
    
    public $display_details = false;
    public $displayed_news = 0;
    
    /**
	 *	Return the »internal« instance of the class
	 *
	 */
	public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }
}
?>