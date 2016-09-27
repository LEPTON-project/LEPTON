<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2016 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {	
	include(LEPTON_PATH.'/framework/class.secure.php'); 
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

// Load the other required class files if they are not already loaded
require_once(LEPTON_PATH."/framework/class.admin.php");
// Get version
require_once(ADMIN_PATH.'/interface/version.php');

class login extends admin {
	
	private $USERS_TABLE = "users";	//	see [1]
	private $GROUPS_TABLE = "groups"; //	see [1]
	
	private $username_fieldname = "";
	private $password_fieldname = "";
	
	private $max_attemps = 10; //	see [1]
	
	private $warning_url = "";
	private $login_url = "";
	public $redirect_url = '';	// must be public
	
	private $template_dir = "/templates"; //	see [1]
	private $template_file = "";
	
	private $frontend = false;	// bool!
	private $forgotten_details_app = "/admins/login/forgot/index.php"; //	see [1]
	
	//	Private var that holds the length of the given username
	private $username_len = 0;
	
	//	Private var that holds the length of the given password
	private $password_len = 0;
	
	public function __construct( $config_array=array() ) {
		// Get language vars
		global $MESSAGE, $database;
	
		/**
		 *	[1] As it looks like PHP <= 5.4 is not able to //see// the constants before the constructor
		 *	needs them (parsing error!), we have to "fill" up them here with the constvalues first
		 */
		$this->USERS_TABLE = TABLE_PREFIX."users";
		$this->GROUPS_TABLE = TABLE_PREFIX."groups";
		$this->max_attemps = MAX_ATTEMPTS;
		$this->template_dir = THEME_PATH."/templates";
		$this->forgotten_details_app = LEPTON_URL."/admins/login/forgot/index.php";
	
		// Get configuration values
		if(isset($config_array['USERS_TABLE'])) $this->USERS_TABLE = $config_array['USERS_TABLE'];
		if(isset($config_array['GROUPS_TABLE'])) $this->GROUPS_TABLE = $config_array['GROUPS_TABLE'];
		if(isset($config_array['USERNAME_FIELDNAME'])) $this->username_fieldname = $config_array['USERNAME_FIELDNAME'];
		if(isset($config_array['PASSWORD_FIELDNAME'])) $this->password_fieldname = $config_array['PASSWORD_FIELDNAME'];
		
		if(isset($config_array['MAX_ATTEMPS'])) $this->max_attemps = $config_array['MAX_ATTEMPS'];
		if(isset($config_array['WARNING_URL'])) $this->warning_url = $config_array['WARNING_URL'];
		if(isset($config_array['LOGIN_URL'])) $this->login_url = $config_array['LOGIN_URL'];
		if(isset($config_array['TEMPLATE_DIR'])) $this->template_dir = $config_array['TEMPLATE_DIR'];
		if(isset($config_array['TEMPLATE_FILE'])) $this->template_file = $config_array['TEMPLATE_FILE'];
		
		if(isset($config_array['FRONTEND'])) $this->frontend = $config_array['FRONTEND'];
		if(isset($config_array['FORGOTTEN_DETAILS_APP'])) $this->forgotten_details_app = $config_array['FORGOTTEN_DETAILS_APP'];
		
		if (array_key_exists('REDIRECT_URL',$config_array)) {
			$this->redirect_url = $config_array['REDIRECT_URL'];
		} else {
			$this->redirect_url = '';
		}
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
		} else {
		
			// Check if the user exists (authenticate them)
			require_once(LEPTON_PATH.'/framework/functions/function.encrypt_password.php');	
			$this->password = encrypt_password( md5($this->password), LEPTON_GUID);		

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
	public function authenticate() {
		global $database;
		// Get user information
		$loginname = ( preg_match('/[\;\=\&\|\<\> ]/',$this->username) ? '' : $this->username );
		$results_array = array();
		$database->execute_query(
			'SELECT * FROM `'.$this->USERS_TABLE.'` WHERE `username` = "'.$loginname.'" AND `password` = "'.$this->password.'" AND `active` = 1',
			true,
			$results_array,
			false
		);
		
		if( count($results_array) > 0) {
		
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
            	$results_array_2 = array();
				$database->execute_query(
					"SELECT * FROM ".$this->GROUPS_TABLE." WHERE group_id = '".$cur_group_id."'",
					true,
					$results_array_2,
					false
				);
				
				if(count($results_array_2) == 0) continue;
				
				$_SESSION['GROUP_NAME'][$cur_group_id] = $results_array_2['name'];
			
				// Set system permissions
				$_SESSION['SYSTEM_PERMISSIONS'] = array_merge($_SESSION['SYSTEM_PERMISSIONS'], explode(',', $results_array_2['system_permissions']));

				// Set module permissions
				if ($first_group) {
					$_SESSION['MODULE_PERMISSIONS'] = explode(',', $results_array_2['module_permissions']);
				} else {
					$_SESSION['MODULE_PERMISSIONS'] = array_intersect($_SESSION['MODULE_PERMISSIONS'], explode(',', $results_array_2['module_permissions']));
				}
				
				// Set template permissions
				if ($first_group) {
					$_SESSION['TEMPLATE_PERMISSIONS'] = explode(',', $results_array_2['template_permissions']);
				} else {
					$_SESSION['TEMPLATE_PERMISSIONS'] = array_intersect($_SESSION['TEMPLATE_PERMISSIONS'], explode(',', $results_array_2['template_permissions']));
				}
				$first_group = false;
			}	

			// Update the users table with current ip and timestamp
			$fields= array(
				"login_when" => time(),
				"login_ip"	=> $_SERVER['REMOTE_ADDR']
			);
			
			$database->build_and_execute(
				"update",
				$this->USERS_TABLE,
				$fields,
				"user_id = ".$user_id
			);
			
			return true;
		
		} else {
			// User does'n exists
			return false;
		}
	}
	
	// Increase the count for login attemps
	public function increase_attemps() {
		if(!isset($_SESSION['ATTEMPS'])) {
			$_SESSION['ATTEMPS'] = 0;
		} else {
			$_SESSION['ATTEMPS']++;
		}
		$this->display_login();
	}
	
	// Display the login screen
	public function display_login() {
		// Get language vars
		global $MESSAGE;
		global $MENU;
		global $TEXT;

		// If attemps more than allowed, warn the user
		if($_SESSION['ATTEMPS'] > $this->max_attemps) {
			$this->warn();
		}

		// Show the login form
		if($this->frontend != true) {

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
					'LEPTON_URL' => LEPTON_URL,
					'THEME_URL' => THEME_URL,
					'VERSION' => VERSION,
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
	public function warn() {
		header('Location: '.$this->warning_url);
		exit();
	}
	
}

?>