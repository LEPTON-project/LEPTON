<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2013 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */


	/**
	 *	Print the admin header
	 * 	public function print_header()
	 */

	{
		// Get vars from the language file
		global $MENU;
		global $MESSAGE;
		global $TEXT;
    
		// Connect to database and get website title
		$title = $this->db_handle->get_one("SELECT `value` FROM `".TABLE_PREFIX."settings` WHERE `name`='website_title'");
		$header_template = new Template(THEME_PATH.'/templates');
		$header_template->set_file('page', 'header.htt');
		$header_template->set_block('page', 'header_block', 'header');
		
		$charset= (true === defined('DEFAULT_CHARSET')) ? DEFAULT_CHARSET : 'utf-8';
		
		// work out the URL for the 'View menu' link in the WB backend
		// if the page_id is set, show this page otherwise show the root directory of WB
		$view_url = WB_URL;
		if(isset($_GET['page_id'])) {
			// extract page link from the database
			$result = $this->db_handle->query("SELECT `link` FROM `" .TABLE_PREFIX ."pages` WHERE `page_id`= '" .(int) addslashes($_GET['page_id']) ."'");
			$row = $result->fetchRow( MYSQL_ASSOC );
			if($row) $view_url .= PAGES_DIRECTORY .$row['link']. PAGE_EXTENSION;
		}



?>