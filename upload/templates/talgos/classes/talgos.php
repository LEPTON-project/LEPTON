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


class talgos extends LEPTON_template
{
	public $alternative_url = THEME_URL.'/backend/backend/pages/';
	public $action_url = ADMIN_URL.'/pages/';
	
    static $instance;
    
    public function initialize()
    {
    
    }
    
    public function setRememberState( &$allPages )
    {
        foreach($allPages as &$ref)
        {
            $ref['tree_status'] = ($_COOKIE["p".$ref['page_id']] ?? 0);
            
            $this->setRememberState( $ref['subpages'] );
        }
    }

}