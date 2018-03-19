<?php

/**
 *	@module			addon_info
 *	@version		see info.php of this module
 *	@author			cms-lab
 *	@copyright		2017-2018 cms-lab
 *	@license		GNU General Public License
 *	@license_terms	please see info.php of this module 
 *	@platform		see info.php of this module
 */

class addon_info extends LEPTON_abstract {
	
	public $database = 0;
	public $admin = 0;
	public $addon_color = 'blue';
	public $support_link = "<a href=\"#\">NO Live-Support / FAQ</a>";
	public $readme_link =  "<a href=\"http://cms-lab.com/_documentation/addon-info/readme.php \" class=\"info\"target=\"_blank\">Readme</a>";
	public $action_url = ADMIN_URL . '/admintools/tool.php?tool=addon_info';	

	public static $instance;

	public function initialize() 
	{
		$this->database = LEPTON_database::getInstance();		
		$this->init_tool();
	}

	public function init_tool( $sToolname = '' )
	{
		
	}

    public function display($id) 
	{

		if ( $id == 'alpha') 
		{		
			$content = file_get_contents('http://www.lepton-cms.com/lepador_alpha_content.txt');
		}

		if ( $id == 'last') 
		{		
			$content = file_get_contents('http://www.lepton-cms.com/lepador_last_content.txt');
		}		
		// data for twig template engine	
		$data = array(
			'oAOI'		=> $this,
			'content'	=> $content,			
			'readme_link'	=> "http://cms-lab.com/_documentation/addon-info/readme.php",				
			'leptoken'	=> get_leptoken()		

		);

		//	get the template-engine
		$oTwig = lib_twig_box::getInstance();
		$oTwig->registerModule('addon_info');
				
		echo $oTwig->render( 
			"@addon_info/display.lte",	//	template-filename
			$data						//	template-data
		);		
	
	}	

    public function show_info() 
	{
		// data for twig template engine	
		$data = array(
			'oAOI'			=> $this,
			'image_url'		=> 'http://cms-lab.com/_documentation/media/addon_info/lepador.jpg'
			);

		/**	
		 *	get the template-engine.
		 */
		$oTwig = lib_twig_box::getInstance();
		$oTwig->registerModule('addon_info');
			
		echo $oTwig->render( 
			"@addon_info/info.lte",	//	template-filename
			$data						//	template-data
		);		
		
	}

}

  

?>