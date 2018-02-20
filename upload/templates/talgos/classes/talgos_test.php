<?php

/**
 *  @template       Talgos  Backend-Theme
 *  @version        see info.php of this template
 *  @author         LEPTON project, (Jurgen Nijhuis & Ruud Eisinga, Dietrich Roland Pehlke, Bernd Michna, LEPTON project for algos theme)
 *	@copyright      2010-2018 LEPTON project 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this template
 *
 */


class talgos_test extends talgos
{
	public $alternative_url = THEME_URL.'/backend/backend/pages/';
	public $action_url = ADMIN_URL.'/pages/';
	
    static $instance;
    
    public function initialize()
    {
    
    }

}