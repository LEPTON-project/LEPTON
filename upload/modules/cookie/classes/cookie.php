<?php

/**
 * @module          Cookie
 * @author          cms-lab
 * @copyright       2017-2018 cms-lab
 * @link            http://www.cms-lab.com
 * @license         custom license: http://cms-lab.com/_documentation/cookie/license.php
 * @license_terms   see: http://cms-lab.com/_documentation/cookie/license.php
 *
 */

class cookie extends LEPTON_abstract
{
	public $cookie_settings = array();
	public $database = 0;
	public $admin = 0;	
	public $addon_color = 'blue';
	public $readme_link = "<a href=\"http://cms-lab.com/_documentation/cookie/readme.php \" class=\"info\"target=\"_blank\">Readme</a>";	
	public $action = LEPTON_URL.'/modules/cookie/';	
	public $cookie_js = LEPTON_URL.'/modules/cookie/js/cookieconsent.min.js';
	public $cookie_css = LEPTON_URL.'/modules/cookie/css/cookieconsent.min.css';	

	public static $instance;

	public function initialize() 
	{
		$this->database = LEPTON_database::getInstance();		
		$this->init_tool();
	}
	
	public function init_tool()
	{
		// Get current settings form the db as array
		$this->database->execute_query(
			"SELECT * FROM ".TABLE_PREFIX."mod_cookie",
			true,
			$this->cookie_settings,
			false
		);		
	}

	public function list_settings()
	{
		// data for twig template engine	
		$data = array(
			'oCO'		=> $this,
			'leptoken'	=> get_leptoken(),
			'read_me'	=> "http://cms-lab.com/_documentation/cookie/readme.php",
			'example_link'=> "https://cookieconsent.insites.com/demos/"
        );

		/**	
		 *	get the template-engine.
		 */
		$oTwig = lib_twig_box::getInstance();
		$oTwig->registerModule('cookie');
			
		echo $oTwig->render( 
			"@cookie/form.lte",	//	template-filename
			$data				//	template-data
		);		
	}

	public function show_info() 
	{
		// build links
		
		$readme_link = "<a href=\"http://cms-lab.com/_documentation/cookie/readme.php \" class=\"info\"target=\"_blank\">Readme</a>";	

		// data for twig template engine	
		$data = array(
			'oCO'		=> $this,	
			'image_url'	=> 'http://cms-lab.com/_documentation/media/cookie/cookie.jpg'
			);

		/**	
		 *	get the template-engine.
		 */
		$oTwig = lib_twig_box::getInstance();
		$oTwig->registerModule('cookie');
			
		echo $oTwig->render( 
			"@cookie/info.lte",	//	template-filename
			$data				//	template-data
		);		
		
	}

	public function build_js()
	{
		// data for twig template engine	
		$data = array(
			'oCO'		=> $this,
			'js_class'	=>'{{classes}}',			
			'read_me'	=> "http://cms-lab.com/_documentation/cookie/readme.php",
			'example_link'=> "https://cookieconsent.insites.com/demos/"
        );

		/**	
		 *	get the template-engine.
		 */
		$oTwig = lib_twig_box::getInstance();
		$oTwig->registerModule('cookie');
			
		return $oTwig->render( 
			"@cookie/output.lte",	//	template-filename
			$data						//	template-data
		);	

	}		
} // end of class
?>