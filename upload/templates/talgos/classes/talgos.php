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
	
	/**
	 *  Default state of a subfolder/subpage: 
	 *  1 = display open, 0 = closed/collapsed.  
	 *  @var    integer $defaultState   The default-status of a subdirectory im the view.
	 */
	public $defaultState = 0;
	
    static $instance;
    
    public function initialize()
    {
    
    }
    
    public function setRememberState( &$allPages )
    {
        foreach($allPages as &$ref)
        {
            $ref['tree_status'] = $this->defaultState; // ($_COOKIE["p".$ref['page_id']] ?? 1);
            
            $this->setRememberState( $ref['subpages'] );
        }
    }

}