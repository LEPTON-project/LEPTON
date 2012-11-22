<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010, Website Baker Project
 * @copyright       2010-2011, LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @version         $Id: class.admin.php 1462 2011-12-12 16:31:23Z frankh $
 *
 */


// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
} else {
	$oneback = "../";
	$root = $oneback;
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= $oneback;
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php

require_once(WB_PATH.'/framework/class.wb.php');

// Include PHPLIB template class
require_once(WB_PATH."/include/phplib/template.inc");

// Get WB version
require_once(ADMIN_PATH.'/interface/version.php');

// Include EditArea wrapper functions
require_once(WB_PATH . '/include/editarea/wb_wrapper_edit_area.php');

class admin extends wb {
	
	private $db_handle = NULL;
	
	public $header_storrage = array(
		'css'	=> array(),
		'js'	=> array(),
		'html'	=> array(),
		'modules' => array()
	);
	
	/**
	 *
	 */
	private $html_output_storage = "";
	
	/**
	 *
	 *
	 */
	private $droplets_ok = false;
	
	/**
	 *	Constructor of the class
	 *
	 *	Authenticate user then auto print the header
	 *
	 */
	public function __construct($section_name, $section_permission = 'start', $auto_header = true, $auto_auth = true) {
		global $database;
		global $MESSAGE;
		
		/**
		 *	Droplet support
		 *
		 */
		ob_start();
		
		$this->db_handle = &$database;
		
		// Specify the current applications name
		$this->section_name = $section_name;
		$this->section_permission = $section_permission;
		// Authenticate the user for this application
		if($auto_auth == true) {
			// First check if the user is logged-in
			if($this->is_authenticated() == false) {
				header('Location: '.ADMIN_URL.'/login/index.php');
				exit(0);
			}
			
			// Now check whether he has a valid token
			if (!$this->checkToken()) {
				unset($_SESSION['USER_ID']);
				header('Location: '.ADMIN_URL.'/login/index.php');
				exit(0);
			}
						
			// Now check if they are allowed in this section
			if($this->get_permission($section_permission) == false) {
				die($MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES']);
			}
		}
		
		// Check if the backend language is also the selected language. If not, send headers again.

		$get_user_language = $this->db_handle->query("SELECT language FROM ".TABLE_PREFIX.
			"users WHERE user_id = '" .(int) $this->get_user_id() ."'");
		$user_language = ($get_user_language) ? $get_user_language->fetchRow() : '';
		// prevent infinite loop if language file is not XX.php (e.g. DE_du.php)
		$user_language = substr($user_language[0],0,2);
		// obtain the admin folder (e.g. /admin)
		$admin_folder = str_replace(WB_PATH, '', ADMIN_PATH);
		if((LANGUAGE != $user_language) && file_exists(WB_PATH .'/languages/' .$user_language .'.php')
			&& strpos($_SERVER['SCRIPT_NAME'],$admin_folder.'/') !== false) {
			// check if page_id is set
			$page_id_url = (isset($_GET['page_id'])) ? '&page_id=' .(int) $_GET['page_id'] : '';
			$section_id_url = (isset($_GET['section_id'])) ? '&section_id=' .(int) $_GET['section_id'] : '';
			if(isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '') { // check if there is an query-string
				header('Location: '.$_SERVER['SCRIPT_NAME'] .'?lang='.$user_language .$page_id_url .$section_id_url.'&'.$_SERVER['QUERY_STRING']);
			} else {
				header('Location: '.$_SERVER['SCRIPT_NAME'] .'?lang='.$user_language .$page_id_url .$section_id_url);
			}
			exit();
		}

		// Auto header code
		if($auto_header == true) {
			$this->print_header();
		}
		/**
		 *	Droplet support
		 *
		 */
		if ( file_exists(WB_PATH .'/modules/droplets/droplets.php') ) {
			/**
			 *	avoid loading on the Droplets Admin Tool itself and on the
			 *	Settings page (this would compile Droplets added to the
			 *	page footer)
			 */
			if (
         		( !isset( $_GET['tool'] ) || 
         		( $_GET['tool'] !== 'droplets' && $_GET['tool'] !== 'jqueryadmin' ) )
        		 && $this->section_name !== 'Settings' && $this->section_name !== 'Page'
    		) {
				require_once(WB_PATH .'/modules/droplets/droplets.php');
				# $this->droplets_ok = true;
			}
		}
	}
	
	/**
	 *	Print the admin header
	 *
	 */
	public function print_header($body_tags = '') {
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
		
		/**
		 *	Try to get the actual version of the backend-theme from the database
		 *
		 */
		$backend_theme_version = "";
		if (defined('DEFAULT_THEME')) {
			$backend_theme_version = $this->db_handle->get_one("SELECT `version` from `".TABLE_PREFIX."addons` where `directory`='".DEFAULT_THEME."'");	
		}
		
		$header_template->set_var(	array(
				'SECTION_NAME' => $MENU[strtoupper($this->section_name)],
				'BODY_TAGS' => $body_tags,
				'WEBSITE_TITLE' => $title,
				'BACKEND_TITLE' => BACKEND_TITLE,
				'TEXT_ADMINISTRATION' => $TEXT['ADMINISTRATION'],
				'CURRENT_USER' => $MESSAGE['START_CURRENT_USER'],
				'DISPLAY_NAME' => $this->get_display_name(),
				'CHARSET' => $charset,
				'LANGUAGE' => strtolower(LANGUAGE),
				'VERSION' => VERSION,
				'SUBVERSION' => SUBVERSION,
				'CORE' => CORE,
				'WB_URL' => WB_URL,
				'ADMIN_URL' => ADMIN_URL,
				'THEME_URL' => THEME_URL,
				'TITLE_START' => $MENU['START'],
				'TITLE_VIEW' => $MENU['VIEW'],
				'TITLE_HELP' => $MENU['HELP'],
				'TITLE_LOGOUT' =>  $MENU['LOGOUT'],
				'URL_VIEW' => $view_url,
				'URL_HELP' => 'http://www.lepton-cms.org/',
				'BACKEND_MODULE_FILES' => $this->__admin_register_backend_modfiles(),
				'THEME_VERSION'	=> $backend_theme_version,
				'THEME_NAME'	=> DEFAULT_THEME
			)
		);

		// Create the menu
		$menu = array(
			array(ADMIN_URL.'/pages/index.php', '', $MENU['PAGES'], 'pages', 1),
			array(ADMIN_URL.'/media/index.php', '', $MENU['MEDIA'], 'media', 1),
			array(ADMIN_URL.'/addons/index.php', '', $MENU['ADDONS'], 'addons', 1),
			array(ADMIN_URL.'/preferences/index.php', '', $MENU['PREFERENCES'], 'preferences', 0),
			array(ADMIN_URL.'/settings/index.php', '', $MENU['SETTINGS'], 'settings', 1),
			array(ADMIN_URL.'/admintools/index.php', '', $MENU['ADMINTOOLS'], 'admintools', 1),
			array(ADMIN_URL.'/access/index.php', '', $MENU['ACCESS'], 'access', 1)
		);
		if ( (true === defined("LEPTON_SERVICE_ACTIVE")) && ( 1 == LEPTON_SERVICE_ACTIVE )) {
				$menu[] = array(ADMIN_URL.'/service/index.php', '', $MENU['SERVICE'], 'service', 1);
		}
		$header_template->set_block('header_block', 'linkBlock', 'link');
		foreach($menu AS $menu_item) {
			$link = $menu_item[0];
			$target = ($menu_item[1] == '') ? '_self' : $menu_item[1];
			$title = $menu_item[2];
			$permission_title = $menu_item[3];
			$required = $menu_item[4];
			$replace_old = array(ADMIN_URL, WB_URL, '/', 'index.php');
			if($required == false OR $this->get_link_permission($permission_title)) {
				$header_template->set_var('LINK', $link);
				$header_template->set_var('TARGET', $target);
				// If link is the current section apply a class name
				if($permission_title == strtolower($this->section_name)) {
					$header_template->set_var('CLASS', $menu_item[3] . ' current');
				} else {
					$header_template->set_var('CLASS', $menu_item[3]);
				}
				$header_template->set_var('TITLE', $title);
				// Print link
				$header_template->parse('link', 'linkBlock', true);
			}
		}
		$header_template->parse('header', 'header_block', false);
		$header_template->pparse('output', 'page');
	}
	
	// Print the admin footer
	public function print_footer() {
		$footer_template = new Template(THEME_PATH.'/templates');
		$footer_template->set_file('page', 'footer.htt');
		$footer_template->set_block('page', 'footer_block', 'header');
		$footer_template->set_var(array(
						'BACKEND_BODY_MODULE_JS' => $this->register_backend_modfiles_body('js'),
						'WB_URL' => WB_URL,
						'WB_PATH' => WB_PATH,
						'ADMIN_URL' => ADMIN_URL,
						'THEME_URL' => THEME_URL
			 			));
		$footer_template->parse('header', 'footer_block', false);
		$footer_template->pparse('output', 'page');
		
		/**
		 *	Droplet support
		 *
		 */
		$this->html_output_storage = ob_get_clean();
		if ( true === $this->droplets_ok ) {
			$this->html_output_storage = evalDroplets($this->html_output_storage);
		}
		
		// CSRF protection - add tokens to internal links
		if ($this->is_authenticated()) {
			if (file_exists(WB_PATH .'/framework/tokens.php')) {
				include_once(WB_PATH .'/framework/tokens.php');
				if (function_exists('addTokens')) addTokens($this->html_output_storage, $this);
			}
		}
		
		echo $this->html_output_storage;
	}

	// Return a system permission
	public function get_permission($name, $type = 'system') {
		// Append to permission type
		$type .= '_permissions';
		// Check if we have a section to check for
		if($name == 'start') {
			return true;
		} else {
			// Set system permissions var
			$system_permissions = $this->get_session('SYSTEM_PERMISSIONS');
			// Set module permissions var
			$module_permissions = $this->get_session('MODULE_PERMISSIONS');
			// Set template permissions var
			$template_permissions = $this->get_session('TEMPLATE_PERMISSIONS');
			// Return true if system perm = 1
			if (isset($$type) && is_array($$type) && is_numeric(array_search($name, $$type))) {
				if($type == 'system_permissions') {
					return true;
				} else {
					return false;
				}
			} else {
				if($type == 'system_permissions') {
					return false;
				} else {
					return true;
				}
			}
		}
	}
		
	public function get_user_details($user_id) {
		$query_user = "SELECT username,display_name FROM ".TABLE_PREFIX."users WHERE user_id = '$user_id'";
		$get_user = $this->db_handle->query($query_user);
		if($get_user->numRows() != 0) {
			$user = $get_user->fetchRow( MYSQL_ASSOC );
		} else {
			$user['display_name'] = 'Unknown';
			$user['username'] = 'unknown';
		}
		return $user;
	}	
	
	public function get_page_details($page_id) {
		$query = "SELECT page_id,page_title,menu_title,modified_by,modified_when FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'";
		$results = $this->db_handle->query($query);
		if($this->db_handle->is_error()) {
			$this->print_header();
			$this->print_error($database->get_error());
		}
		if($results->numRows() == 0) {
			$this->print_header();
			$this->print_error($MESSAGE['PAGES_NOT_FOUND']);
		}
		$results_array = $results->fetchRow( MYSQL_ASSOC );
		return $results_array;
	}	
	
	/** 
	 *	Function get_page_permission takes either a numerical page_id,
	 *	upon which it looks up the permissions in the database,
	 *	or an array with keys admin_groups and admin_users  
	 */
	public function get_page_permission($page,$action='admin') {
		if ($action!='viewing') $action='admin';
		$action_groups=$action.'_groups';
		$action_users=$action.'_users';
		if (is_array($page)) {
				$groups=$page[$action_groups];
				$users=$page[$action_users];
		} else {				
			$results = $this->db_handle->query("SELECT $action_groups,$action_users FROM ".TABLE_PREFIX."pages WHERE page_id = '$page'");
			$result = $results->fetchRow( MYSQL_ASSOC );
			$groups = explode(',', str_replace('_', '', $result[$action_groups]));
			$users = explode(',', str_replace('_', '', $result[$action_users]));
		}

		$in_group = FALSE;
		foreach($this->get_groups_id() as $cur_gid){
		    if (in_array($cur_gid, $groups)) {
		        $in_group = TRUE;
		    }
		}
		if((!$in_group) AND !is_numeric(array_search($this->get_user_id(), $users))) {
			return false;
		}
		return true;
	}
		

	// Returns a system permission for a menu link
	public function get_link_permission($title) {
		$title = str_replace('_blank', '', $title);
		$title = strtolower($title);
		// Set system permissions var
		$system_permissions = $this->get_session('SYSTEM_PERMISSIONS');
		// Set module permissions var
		$module_permissions = $this->get_session('MODULE_PERMISSIONS');
		if($title == 'start') {
			return true;
		} else {
			// Return true if system perm = 1
			if(is_numeric(array_search($title, $system_permissions))) {
				return true;
			} else {
				return false;
			}
		}
	}

	// Function to add optional module Javascript or CSS stylesheets into the <body> section of the backend
	public function register_backend_modfiles_body($file_id="js") {
		// sanity check of parameter passed to the function
		$file_id = strtolower($file_id);
		if($file_id !== "javascript" && $file_id !== "js")
		{
			return;
		}
		
        $body_links = "";
		// define default baselink and filename for optional module javascript and stylesheet files
		if($file_id == "js") {
			$base_link = '<script type="text/javascript" src="'.WB_URL.'/modules/{MODULE_DIRECTORY}/backend_body.js"></script>';
			$base_file = "backend_body.js";
		}
		// check if backend_body.js files needs to be included to the <body></body> section of the backend
		if(isset($_GET['tool']))
			{
			// check if displayed page contains a installed admin tool
			$result = $this->db_handle->query("SELECT * FROM " .TABLE_PREFIX ."addons
				WHERE type = 'module' AND function = 'tool' AND directory = '".addslashes($_GET['tool'])."'");
			if($result->numRows())
				{
				// check if admin tool directory contains a backend_body.js file to include
				$tool = $result->fetchRow( MYSQL_ASSOC );
				if(file_exists(WB_PATH ."/modules/" .$tool['directory'] ."/$base_file"))
				{
					// return link to the backend_body.js file
					return str_replace("{MODULE_DIRECTORY}", $tool['directory'], $base_link);
				}
			}
		} elseif(isset($_GET['page_id']) or isset($_POST['page_id']))
		{
			// check if displayed page in the backend contains a page module
			if (isset($_GET['page_id']))
			{
				$page_id = (int) addslashes($_GET['page_id']);
			} else {
				$page_id = (int) addslashes($_POST['page_id']);
			}
			// gather information for all models embedded on actual page
			$query_modules = $this->db_handle->query("SELECT module FROM " .TABLE_PREFIX ."sections
				WHERE page_id=$page_id");
			while(false !== ($row = $query_modules->fetchRow( MYSQL_ASSOC ) ) ) {
				// check if page module directory contains a backend_body.js file
				if(file_exists(WB_PATH ."/modules/" .$row['module'] ."/$base_file")) {
					// create link with backend_body.js source for the current module
					$tmp_link = str_replace("{MODULE_DIRECTORY}", $row['module'], $base_link);
					// ensure that backend_body.js is only added once per module type
					if(strpos($body_links, $tmp_link) === false) {
						$body_links .= $tmp_link ."\n";
					}
				}
			}
			// write out links with all external module javascript/CSS files, remove last line feed
			return rtrim($body_links);
		}
	}


	// Function to add optional module Javascript or CSS stylesheets into the <head> section of the backend
	public function register_backend_modfiles($file_id="css") {
		// sanity check of parameter passed to the function
		$file_id = strtolower($file_id);
		if($file_id !== "css" && $file_id !== "javascript" && $file_id !== "js") {
			return;
		}
		// define default baselink and filename for optional module javascript and stylesheet files
		$head_links = "";
		if($file_id == "css") {
      	$base_link = '<link href="'.WB_URL.'/modules/{MODULE_DIRECTORY}/backend.css"';
			$base_link.= ' rel="stylesheet" type="text/css" media="screen" />';
			$base_file = "backend.css";
		} else {
			$base_link = '<script type="text/javascript" src="'.WB_URL.'/modules/{MODULE_DIRECTORY}/backend.js"></script>';
			$base_file = "backend.js";
		}

		// check if backend.js or backend.css files needs to be included to the <head></head> section of the backend
		if(isset($_GET['tool'])) {
			// check if displayed page contains a installed admin tool
			$result = $this->db_handle->query("SELECT * FROM " .TABLE_PREFIX ."addons
				WHERE type = 'module' AND function = 'tool' AND directory = '".addslashes($_GET['tool'])."'");

			if($result->numRows()) {
				// check if admin tool directory contains a backend.js or backend.css file to include
				$tool = $result->fetchRow( MYSQL_ASSOC );
				if(file_exists(WB_PATH ."/modules/" .$tool['directory'] ."/$base_file")) {
        			// return link to the backend.js or backend.css file
					return str_replace("{MODULE_DIRECTORY}", $tool['directory'], $base_link);
				}
			}
		} elseif(isset($_GET['page_id']) or isset($_POST['page_id'])) {
			// check if displayed page in the backend contains a page module
			if (isset($_GET['page_id'])) {
				$page_id = (int)$_GET['page_id'];
			} else {
				$page_id = (int)$_POST['page_id'];
			}

    		// gather information for all models embedded on actual page
			$query_modules = $this->db_handle->query("SELECT module FROM " .TABLE_PREFIX ."sections
				WHERE page_id=$page_id");

    		while(false !== ($row = $query_modules->fetchRow( MYSQL_ASSOC ) ) ) {
				// check if page module directory contains a backend.js or backend.css file
      		if(file_exists(WB_PATH ."/modules/" .$row['module'] ."/$base_file")) {
					// create link with backend.js or backend.css source for the current module
					$tmp_link = str_replace("{MODULE_DIRECTORY}", $row['module'], $base_link);
        			// ensure that backend.js or backend.css is only added once per module type
        			if(strpos($head_links, $tmp_link) === false) {
						$head_links .= $tmp_link ."\n";
					}
				}
    		}
    		// write out links with all external module javascript/CSS files, remove last line feed
			return rtrim($head_links);
		}
	}
	
	private function __admin_register_backend_modfiles() {

		$files = array(
			'css'	=> array(
				'backend.css',
				'css/backend.css'
				), 
			'js'	=> array(
				'backend.js',
				'js/backend.js',
				'scripts/backend.js'
				)
		);
		
		$html = "\n<!-- addons backend files -->\n";
		$html_results = array( "css" => array(), "js" => array() );
		
		if (strpos( $_SERVER['REQUEST_URI'],'admins/pages/sections.php') > 0) {
			$s = WB_URL."/modules/jsadmin/backend.css";
			$html .=  "<link href=\"".$s."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\" />\n";
			$html .= "<!-- end addons backend files -->\n";
			return $html;
		}

		if (isset($_REQUEST['page_id'])) {
			$look_up_field = "module";
			$look_up_table = "sections";
			$look_up_where = "`page_id`='".$_REQUEST['page_id']."'";
		} elseif (isset($_REQUEST['tool'])) {
			$look_up_field = "directory";
			$look_up_table = "addons";
			$look_up_where = "`type`='module' AND `function`='tool' AND directory='".$_REQUEST['tool']."'";
		} else {
			if (strpos( $_SERVER['REQUEST_URI'],'admins/pages/index.php') > 0) {
				$s = WB_URL."/modules/jsadmin/backend.css";
				$html .=  "<link href=\"".$s."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\" />\n";
				$html .= "<!-- end addons backend files -->\n";
			}
			return $html;
		}
		
		$query = "SELECT `".$look_up_field."` from `".TABLE_PREFIX.$look_up_table."` where ".$look_up_where;
		
		$result = $this->db_handle->query( $query );
		
		if ($result) {

			while( false !== ($data = $result->fetchRow( MYSQL_ASSOC ) ) ) {
				
				if (in_array($data[$look_up_field], $this->header_storrage['modules'] ) ) continue;
				
				$this->header_storrage['modules'][] = $data[$look_up_field];
				
				$basepath = "/modules/".$data[$look_up_field]."/";
				
				foreach($files as $type=>$temp_files) {
					foreach($temp_files as $filename) {
						$f = $basepath.$filename;
						if (true == file_exists(WB_PATH.$f)) {
							$html_results[ $type ][] = $this->__admin_build_link($f, $type);
						}
					}
				}
			}
		}

		foreach($html_results as $ref=>$data) {
			$html .= implode("\n", $data)."\n";
		}
				
		$html .= "<!-- end addons backend files -->\n";
		
		return $html;
	}
	
	private function __admin_build_link( $aPath, $aType="css") {
		
		$s = WB_URL.$aPath;
		
		switch(strtolower($aType) ) {
			
			case "css":
				$s = "<link href=\"".$s."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\" />";
				break;
				
			case "js":
				$s = "<script src=\"".$s."\" type=\"text/javascript\"></script>";
				break;
		}
		
		return $s;
	}
}

?>