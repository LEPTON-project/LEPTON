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
 * @version         $Id: class.login.php 1808 2012-03-21 12:21:53Z aldus $
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




define('LOGIN_CLASS_LOADED', true);

// Load the other required class files if they are not already loaded
require_once(WB_PATH."/framework/class.admin.php");
// Get WB version
require_once(ADMIN_PATH.'/interface/version.php');

class login extends admin {
	function __construct( $config_array ) {
		// Get language vars
		global $MESSAGE, $database;

		// Get configuration values
		$this->USERS_TABLE = $config_array['USERS_TABLE'];
		$this->GROUPS_TABLE = $config_array['GROUPS_TABLE'];
		$this->username_fieldname = $config_array['USERNAME_FIELDNAME'];
		$this->password_fieldname = $config_array['PASSWORD_FIELDNAME'];
		$this->max_attemps = $config_array['MAX_ATTEMPS'];
		$this->warning_url = $config_array['WARNING_URL'];
		$this->login_url = $config_array['LOGIN_URL'];
		$this->template_dir = $config_array['TEMPLATE_DIR'];
		$this->template_file = $config_array['TEMPLATE_FILE'];
		$this->frontend = $config_array['FRONTEND'];
		$this->forgotten_details_app = $config_array['FORGOTTEN_DETAILS_APP'];
		$this->max_username_len = $config_array['MAX_USERNAME_LEN'];
		$this->max_password_len = $config_array['MAX_PASSWORD_LEN'];
		if (array_key_exists('REDIRECT_URL',$config_array))
			$this->redirect_url = $config_array['REDIRECT_URL'];
		else
			$this->redirect_url = '';
		// Get the supplied username and password
		if ($this->get_post('username_fieldname') != ''){
			$username_fieldname = $this->get_post('username_fieldname');
			$password_fieldname = $this->get_post('password_fieldname');
		} else {
			$username_fieldname = 'username';
			$password_fieldname = 'password';
		}
		$this->username = htmlspecialchars (strtolower($this->get_post($username_fieldname)), ENT_QUOTES);

		$this->password = $this->get_post($password_fieldname);

		// Get the length of the supplied username and password
		if($this->get_post($username_fieldname) != '') {
			$this->username_len = strlen($this->username);
			$this->password_len = strlen($this->password);
		}
		// If the url is blank, set it to the default url
		$this->url = $this->get_post('url');
		if ($this->redirect_url!='') {
			$this->url = $this->redirect_url;
		}		
		if(strlen($this->url) < 2) {
			$this->url = $config_array['DEFAULT_URL'];
			$token = (!LEPTOKEN_LIFETIME) ? '' : '?leptoken=' . $this->getToken();
			$this->url = $config_array['DEFAULT_URL'] . $token;
		}
		if ($this->is_authenticated() == true) {
			// User already logged-in, so redirect to default url
			header('Location: '.$this->url);
			exit();
		} elseif($this->username == '' AND $this->password == '') {
			$this->message = $MESSAGE['LOGIN_BOTH_BLANK'];
			$this->increase_attemps();
		} elseif($this->username == '') {
			$this->message = $MESSAGE['LOGIN_USERNAME_BLANK'];
			$this->increase_attemps();
		} elseif($this->password == '') {
			$this->message = $MESSAGE['LOGIN_PASSWORD_BLANK'];
			$this->increase_attemps();
		} elseif($this->username_len < $config_array['MIN_USERNAME_LEN']) {
			$this->message = $MESSAGE['LOGIN_USERNAME_TOO_SHORT'];
			$this->increase_attemps();
		} elseif($this->password_len < $config_array['MIN_PASSWORD_LEN']) {
			$this->message = $MESSAGE['LOGIN_PASSWORD_TOO_SHORT'];
			$this->increase_attemps();
		} elseif($this->username_len > $config_array['MAX_USERNAME_LEN']) {
			$this->message = $MESSAGE['LOGIN_USERNAME_TOO_LONG'];
			$this->increase_attemps();
		} elseif($this->password_len > $config_array['MAX_PASSWORD_LEN']) {
			$this->message = $MESSAGE['LOGIN_PASSWORD_TOO_LONG'];
			$this->increase_attemps();
		} else {
			// Check if the user exists (authenticate them)
			$this->password = md5($this->password);
			if($this->authenticate()) {
				// Authentication successful
				$token = (!LEPTOKEN_LIFETIME) ? '' : '?leptoken=' . $this->getToken();
				header("Location: ".$this->url . $token);
				exit(0);
			} else {
				$this->message = $MESSAGE['LOGIN_AUTHENTICATION_FAILED'];
				$this->increase_attemps();
			}
		}
	}

	// Authenticate the user (check if they exist in the database)
	function authenticate() {
		global $database;
		// Get user information
		$loginname = ( preg_match('/[\;\=\&\|\<\> ]/',$this->username) ? '' : $this->username );
		$query = 'SELECT * FROM `'.$this->USERS_TABLE.'` WHERE `username` = "'.$loginname.'" AND `password` = "'.$this->password.'" AND `active` = 1';
		$results = $database->query($query);
		$num_rows = $results->numRows();
		if($num_rows == 1) {
			$results_array = $results->fetchRow( MYSQL_ASSOC );
		
			$user_id = $results_array['user_id'];
			$this->user_id = $user_id;
			$_SESSION['USER_ID'] = $user_id;
			$_SESSION['GROUP_ID'] = $results_array['group_id'];
			$_SESSION['GROUPS_ID'] = $results_array['groups_id'];
			$_SESSION['USERNAME'] = $results_array['username'];
			$_SESSION['DISPLAY_NAME'] = $results_array['display_name'];
			$_SESSION['EMAIL'] = $results_array['email'];
			$_SESSION['HOME_FOLDER'] = $results_array['home_folder'];

			// Set language
			if($results_array['language'] != '') {
				$_SESSION['LANGUAGE'] = $results_array['language'];
			}

			// Set timezone
			if ($results_array['timezone_string'] != '') {
				$_SESSION['TIMEZONE_STRING'] = $results_array['timezone_string'];
			}
			$timezone_string = (isset ($_SESSION['TIMEZONE_STRING']) ? $_SESSION['TIMEZONE_STRING'] : DEFAULT_TIMEZONESTRING );
			date_default_timezone_set($timezone_string);
			
			// Set date format
			if($results_array['date_format'] != '') {
				$_SESSION['DATE_FORMAT'] = $results_array['date_format'];
			} else {
				// Set a session var so apps can tell user is using default date format
				$_SESSION['USE_DEFAULT_DATE_FORMAT'] = true;
			}
			// Set time format
			if($results_array['time_format'] != '') {
				$_SESSION['TIME_FORMAT'] = $results_array['time_format'];
			} else {
				// Set a session var so apps can tell user is using default time format
				$_SESSION['USE_DEFAULT_TIME_FORMAT'] = true;
			}

			// Get group information
			$_SESSION['SYSTEM_PERMISSIONS'] = array();
			$_SESSION['MODULE_PERMISSIONS'] = array();
			$_SESSION['TEMPLATE_PERMISSIONS'] = array();
			$_SESSION['GROUP_NAME'] = array();

			$first_group = true;
			foreach (explode(",", $this->get_session('GROUPS_ID')) as $cur_group_id)
            {
				$query = "SELECT * FROM ".$this->GROUPS_TABLE." WHERE group_id = '".$cur_group_id."'";
				$results = $database->query($query);
				$results_array = $results->fetchRow( MYSQL_ASSOC );
				$_SESSION['GROUP_NAME'][$cur_group_id] = $results_array['name'];
			
				// Set system permissions
				$_SESSION['SYSTEM_PERMISSIONS'] = array_merge($_SESSION['SYSTEM_PERMISSIONS'], explode(',', $results_array['system_permissions']));

				// Set module permissions
				if ($first_group) {
					$_SESSION['MODULE_PERMISSIONS'] = explode(',', $results_array['module_permissions']);
				} else {
					$_SESSION['MODULE_PERMISSIONS'] = array_intersect($_SESSION['MODULE_PERMISSIONS'], explode(',', $results_array['module_permissions']));
				}
				
				// Set template permissions
				if ($first_group) {
					$_SESSION['TEMPLATE_PERMISSIONS'] = explode(',', $results_array['template_permissions']);
				} else {
					$_SESSION['TEMPLATE_PERMISSIONS'] = array_intersect($_SESSION['TEMPLATE_PERMISSIONS'], explode(',', $results_array['template_permissions']));
				}
				$first_group = false;
			}	

			// Update the users table with current ip and timestamp
			$get_ts = time();
			$get_ip = $_SERVER['REMOTE_ADDR'];
			$query = "UPDATE ".$this->USERS_TABLE." SET login_when = '$get_ts', login_ip = '$get_ip' WHERE user_id = '$user_id'";
			$database->query($query);
		} else {
		  $num_rows = 0;
		}
		// Return if the user exists or not
		return $num_rows;
	}
	
	// Increase the count for login attemps
	function increase_attemps() {
		if(!isset($_SESSION['ATTEMPS'])) {
			$_SESSION['ATTEMPS'] = 0;
		} else {
			$_SESSION['ATTEMPS'] = $this->get_session('ATTEMPS')+1;
		}
		$this->display_login();
	}
	
	// Display the login screen
	function display_login() {
		// Get language vars
		global $MESSAGE;
		global $MENU;
		global $TEXT;
		// If attemps more than allowed, warn the user
		if($this->get_session('ATTEMPS') > $this->max_attemps) {
			$this->warn();
		}
		// Show the login form
		if($this->frontend != true) {
			require_once(WB_PATH.'/include/phplib/template.inc');
			$template = new Template($this->template_dir);
			$template->set_file('page', $this->template_file);
			$template->set_block('page', 'mainBlock', 'main');
			
			$template->set_var(array(
					'ACTION_URL' => $this->login_url,
					'ATTEMPS' => $this->get_session('ATTEMPS'),
					'USERNAME' => $this->username,
					'USERNAME_FIELDNAME' => $this->username_fieldname,
					'PASSWORD_FIELDNAME' => $this->password_fieldname,
					'MESSAGE' => $this->message,
					'INTERFACE_DIR_URL' =>  ADMIN_URL.'/interface',
					'MAX_USERNAME_LEN' => $this->max_username_len,
					'MAX_PASSWORD_LEN' => $this->max_password_len,
					'WB_URL' => WB_URL,
					'THEME_URL' => THEME_URL,
					'VERSION' => VERSION,
					/**
					 *	maked as deprecated
					 */
					# 'REVISION' => REVISION,
					'LANGUAGE' => strtolower(LANGUAGE),
					'FORGOTTEN_DETAILS_APP' => $this->forgotten_details_app,
					'TEXT_FORGOTTEN_DETAILS' => $TEXT['FORGOTTEN_DETAILS'],
					'TEXT_USERNAME' => $TEXT['USERNAME'],
					'TEXT_PASSWORD' => $TEXT['PASSWORD'],
					'TEXT_LOGIN' => $MENU['LOGIN'],
					'TEXT_HOME' => $TEXT['HOME'],
					'PAGES_DIRECTORY' => PAGES_DIRECTORY,
					'SECTION_LOGIN' => $MENU['LOGIN']
					)
			);
			
			$charset= (defined('DEFAULT_CHARSET')) ? DEFAULT_CHARSET : "utf-8";
			
			$template->set_var('CHARSET', $charset);	
									
			$template->parse('main', 'mainBlock', false);
			$template->pparse('output', 'page');
		}
	}


	// Warn user that they have had to many login attemps
	function warn() {
		header('Location: '.$this->warning_url);
		exit(0);
	}
	
}

?>