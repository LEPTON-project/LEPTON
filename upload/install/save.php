<?php

 /**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker
 * @copyright       2010-2017 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

$debug = true;

if (true === $debug) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}

// Start a session
if(!defined('SESSION_STARTED')) {
	session_name('lepton_session_id');
	session_start();
	define('SESSION_STARTED', true);
}
// get random-part for session_name()
list($usec,$sec) = explode(' ',microtime());
srand((float)$sec+((float)$usec*100000));
$session_rand = rand(1000,9999);

// load and register the LEPTON autoloader
require_once("../framework/functions/function.lepton_autoloader.php" );
spl_autoload_register( "lepton_autoloader", true);

// check if guid is from index.php
if ( ( isset($_POST['guid']) ) && ($_POST['guid'] == "E610A7F2-5E4A-4571-9391-C947152FDFB0") ) {
	define("LEPTON_INSTALL", true);
	if (!defined ("PROMPT_MYSQL_ERRORS") ) define("PROMPT_MYSQL_ERRORS", true);
}

/**
 *	Function to set up an error-message and leave the installation-process.
 *
 *	@param	string	Any error-message.
 *	@param	string	Any field-name. Could be an empty string.
 *
 */
function set_error($message, $field_name = '') {

	if(isset($message) && $message != '') {

		/**
		 *	Copy values entered into session so user doesn't have to re-enter everything
		 *
		 */
		if(isset($_POST['website_title'])) {

			$keys = array(
				'lepton_url',
				'default_timezone_string',
				'default_language',
				'database_host',
				'database_username',
				'database_password',
				'database_name',
				'website_title',
				'admin_username',
				'admin_email',
				'admin_password',
				'admin_repassword',
				'table_prefix'
			);

			copy_post_to_session( $keys );

			$_SESSION['operating_system'] = (!isset($_POST['operating_system']))
				? 'linux'
				: $_POST['operating_system'] ;

			$_SESSION['world_writeable'] = (!isset($_POST['world_writeable']))
				? false
				: true ;

			$_SESSION['install_tables'] = (!isset($_POST['install_tables']))
				? false
				: true ;

		}

		// Set the message
		$_SESSION['message'] = $message;

		// Set the element(s) to highlight
		if($field_name != '') $_SESSION['ERROR_FIELD'] = $field_name;

		// Specify that session support is enabled
		$_SESSION['session_support'] = '<font class="good">Enabled</font>';

		// Redirect to first page again and exit
		header('Location: index.php?sessions_checked=true');
		exit();
	}
}

/**
 *	Function to copy some fields from the $_POST array to the $_SESSION array.
 *
 *	@param	array	An array within the keys. Pass by reference.
 *
 */
function copy_post_to_session(&$names) {
	foreach($names as $key) $_SESSION[ $key ] = (isset( $_POST[ $key ] ) )
		? $_POST[ $key ]
		: '' ;
}

/**
 *	Dummy class to allow modules' install scripts to call $admin->print_error
 *
 */
class admin_dummy
{
	/**
	 *	Public var that holds the message
	 *
	 */
	public $error='';

	/**
	 *	Public function to "setup" the message.
	 *
	 *	@param	string	Any message-string.
	 *	@return	nothing
	 */
	public function print_error($message)
	{
		$this->error=$message;
	}
	/**
	 *	Fake the admin-id
	 */
	public function get_user_id() {
		return 1;
	}
}

// Function to workout what the default permissions are for files created by the webserver
function default_file_mode($temp_dir) {
	$v = explode(".",PHP_VERSION);
	$v = $v[0].$v[1];
	if($v > 41 && is_writable($temp_dir)) {
		$filename = $temp_dir.'/test_permissions.txt';
		$handle = fopen($filename, 'w');
		fwrite($handle, 'This file is to get the default file permissions');
		fclose($handle);
		$default_file_mode = '0'.substr(sprintf('%o', fileperms($filename)), -3);
		unlink($filename);
	} else {
		$default_file_mode = '0777';
	}
	return $default_file_mode;
}

// Function to workout what the default permissions are for directories created by the webserver
function default_dir_mode($temp_dir) {
	$v = explode(".",PHP_VERSION);
	$v = $v[0].$v[1];
	if($v > 41 && is_writable($temp_dir)) {
		$dirname = $temp_dir.'/test_permissions/';
		mkdir($dirname);
		$default_dir_mode = '0'.substr(sprintf('%o', fileperms($dirname)), -3);
		rmdir($dirname);
	} else {
		$default_dir_mode = '0777';
	}
	return $default_dir_mode;
}

function add_slashes($input) {
	if ( get_magic_quotes_gpc() || ( !is_string($input) ) ) {
		return $input;
	}
	$output = addslashes($input);
	return $output;
}

function install_createGUID()
{
	if (function_exists('com_create_guid'))
	{
		$guid = com_create_guid();
		$guid = strtolower($guid);
		if (strpos($guid, '{') == 0)
		{
			$guid = substr($guid, 1);
		}
		if (strpos($guid, '}') == strlen($guid) - 1)
		{
			$guid = substr($guid, 0, strlen($guid) - 1);
		}
		return $guid;
	}
	else
	{
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
	}
}   // end function createGUID()

// Begin check to see if form was even submitted
// Set error if no post vars found
if(!isset($_POST['website_title'])) {
	set_error('Please fill-in the form below');
}
// End check to see if form was even submitted

// Begin path and timezone details code

// Check if user has entered the installation url
if(!isset($_POST['lepton_url']) || $_POST['lepton_url'] == '') {
	set_error('Please enter an absolute URL', 'lepton_url');
} else {
	$lepton_url = $_POST['lepton_url'];
}
// Remove any slashes at the end of the URL
if(substr($lepton_url, strlen($lepton_url)-1, 1) == "/") {
	$lepton_url = substr($lepton_url, 0, strlen($lepton_url)-1);
}
if(substr($lepton_url, strlen($lepton_url)-1, 1) == "\\") {
	$lepton_url = substr($lepton_url, 0, strlen($lepton_url)-1);
}
if(substr($lepton_url, strlen($lepton_url)-1, 1) == "/") {
	$lepton_url = substr($lepton_url, 0, strlen($lepton_url)-1);
}
if(substr($lepton_url, strlen($lepton_url)-1, 1) == "\\") {
	$lepton_url = substr($lepton_url, 0, strlen($lepton_url)-1);
}
// Get the default time zone
$timezone_table =  LEPTON_basics::get_timezones();
if (isset($_POST['default_timezone_string']) && in_array($_POST['default_timezone_string'], $timezone_table)) {
	$default_timezone_string = $_POST['default_timezone_string'];
	date_default_timezone_set($default_timezone_string);
} else {
	set_error('Please select a valid default timezone', 'default_timezone_string');
}
// End path and timezone details code

// Get the default language
$allowed_languages = array('BG','CA', 'CS', 'DA', 'DE', 'EN', 'ES', 'ET', 'FI', 'FR', 'HR', 'HU', 'IT', 'LV', 'NL', 'NO', 'PL', 'PT', 'RU','SE','SK','TR');
if(!isset($_POST['default_language']) || !in_array($_POST['default_language'], $allowed_languages)) {
	set_error('Please select a valid default backend language','default_language');
} else {
	$default_language = $_POST['default_language'];
	// make sure the selected language file exists in the language folder
	if(!file_exists('../languages/' .$default_language .'.php')) {
		set_error('The language file: \'' .$default_language .'.php\' is missing. Upload file to language folder or choose another language','default_language');
	}
}
// End default language details code

// Begin operating system specific code
// Get operating system
if(!isset($_POST['operating_system']) || $_POST['operating_system'] != 'linux' && $_POST['operating_system'] != 'windows') {
	set_error('Please select a valid operating system');
} else {
	$operating_system = $_POST['operating_system'];
}
// Work-out file permissions
if($operating_system == 'windows') {
	$file_mode = '0644';
	$dir_mode = '0755';
} elseif(isset($_POST['world_writeable']) && $_POST['world_writeable'] == 'true') {
	$file_mode = '0666';
	$dir_mode = '0777';
} else {
	$file_mode = default_file_mode('../temp');
	$dir_mode = default_dir_mode('../temp');
}
// End operating system specific code

// Begin database details code
// Check if user has entered a database host
if(!isset($_POST['database_host']) || $_POST['database_host'] == '') {
	set_error('Please enter a database host name', 'database_host');
} else {
	$database_host = $_POST['database_host'];
}
/**
 *	Try to get the database port.
 */
$database_port = isset($_POST['database_port']) ? $_POST['database_port']: "3306";
// Check if user has entered a database username
if(!isset($_POST['database_username']) || $_POST['database_username'] == '') {
	set_error('Please enter a database username','database_username');
} else {
	if(preg_match('/^[a-z0-9][a-z0-9_\-\@]+$/i', $_POST['database_username'])) {
		$database_username = $_POST['database_username'];
	} else {
		set_error('Only characters a-z, A-Z, 0-9, @, - and _ allowed in database username.', 'database_username');
	}
}
// Check if user has entered a database password
if(!isset($_POST['database_password'])) {
	set_error('Please enter a database password', 'database_password');
} else {
	$database_password = $_POST['database_password'];
}
// Check if user has entered a database name
if(!isset($_POST['database_name']) || $_POST['database_name'] == '') {
	set_error('Please enter a database name', 'database_name');
} else {
	// make sure only allowed characters are specified
	if(preg_match('/^[a-z0-9][a-z0-9_-]+$/i', $_POST['database_name'])) {
		$database_name = $_POST['database_name'];
	}else{
		// contains invalid characters (only a-z, A-Z, 0-9 and _ allowed to avoid problems with table/field names)
		set_error('Only characters a-z, A-Z, 0-9, - and _ allowed in database name.', 'database_name');
	}
}
// Get table prefix
if(preg_match('/^[a-z0-9_]+$/i', $_POST['table_prefix']) || $_POST['table_prefix'] == '' ) {
	$table_prefix = $_POST['table_prefix'];
} else {
	// contains invalid characters (only a-z, A-Z, 0-9 and _ allowed to avoid problems with table/field names)
	set_error('Only characters a-z, A-Z, 0-9 and _ allowed in table_prefix.', 'table_prefix');
}
// End database details code

// Begin website title code
// Get website title
if(!isset($_POST['website_title']) || $_POST['website_title'] == '') {
	set_error('Please enter a website title', 'website_title');
} else {
	$website_title = add_slashes($_POST['website_title']);
}
// End website title code

// Begin admin user details code
// Get admin username
if(!isset($_POST['admin_username']) || $_POST['admin_username'] == '') {
	set_error('Please enter a username for the Administrator account','admin_username');
} else {
	$admin_username = $_POST['admin_username'];
}
// Get admin email and validate it
if(!isset($_POST['admin_email']) || $_POST['admin_email'] == '') {
	set_error('Please enter an email for the Administrator account','admin_email');
} else {
	$admin_email = $_POST['admin_email'];
	if( false == filter_var( $admin_email, FILTER_VALIDATE_EMAIL) ) {
		set_error('Please enter a valid email address for the Administrator account','admin_email');
	}
}
// Get the two admin passwords entered, and check that they match
if(!isset($_POST['admin_password']) || $_POST['admin_password'] == '') {
	set_error('Please enter a password for the Administrator account','admin_password');
} else {
	$admin_password = $_POST['admin_password'];
}
if(!isset($_POST['admin_repassword']) || $_POST['admin_repassword'] == '') {
	set_error('Please make sure you re-enter the password for the Administrator account','admin_repassword');
} else {
	$admin_repassword = $_POST['admin_repassword'];
}
if($admin_password != $admin_repassword) {
	set_error('Sorry, the two Administrator account passwords you entered do not match','admin_repassword');
}
// End admin user details code

// Include  functions file
$lepton_path = str_replace(array('/install','\install'), '', dirname(__FILE__));

// create a new GUID for this installation
$lepton_guid = install_createGUID();

define('DB_TYPE', 'mysql');
define('DB_HOST', $database_host);
define('DB_PORT', $database_port);
define('DB_USERNAME', $database_username);
define('DB_PASSWORD', $database_password);
define('DB_NAME', $database_name);
define('TABLE_PREFIX', $table_prefix);
define('LEPTON_PATH', str_replace( array("\install", "/install"), "", dirname(__FILE__)));
define('LEPTON_URL', $lepton_url);
define('ADMIN_PATH', LEPTON_PATH.'/admins');
define('ADMIN_URL', $lepton_url.'/admins');
define('LEPTON_GUID', $lepton_guid);
define('WB_URL', LEPTON_URL);
define('WB_PATH', LEPTON_PATH);

require_once($lepton_path.'/framework/summary.functions.php');
include($lepton_path.'/framework/sys.constants.php');

// Try and write settings to config file
$config_content = "" .
"<?php\n".
"\n".
"if(defined('LEPTON_PATH')) { die('By security reasons it is not permitted to load \'config.php\' twice!! ".
"Forbidden call from \''.\$_SERVER['SCRIPT_NAME'].'\'!'); }\n\n".
"// config file created by ".CORE." ".VERSION."\n".
"\n".
"define('LEPTON_PATH', dirname(__FILE__));\n".
"define('LEPTON_URL', '$lepton_url');\n".
"define('ADMIN_PATH', LEPTON_PATH.'/admins');\n".
"define('ADMIN_URL', LEPTON_URL.'/admins');\n".
"\n".
"define('LEPTON_GUID', '".$lepton_guid."');\n".
"\n".
"define('WB_URL', LEPTON_URL);\n".
"define('WB_PATH', LEPTON_PATH);\n".
"\n".
"if (!defined('LEPTON_INSTALL')) require_once(LEPTON_PATH.'/framework/initialize.php');\n".
"\n".
"?>";

$config_filename = '../config.php';

// Check if the file exists and is writable first.

if(($handle = fopen($config_filename, 'w')) === false) {
	set_error("Cannot open the configuration file ($config_filename)");
} else {
	if (fwrite($handle, $config_content, strlen($config_content) ) === false) {
		fclose($handle);
		set_error("Cannot write to the configuration file ($config_filename)");
	}
	// Close file
	fclose($handle);
}

/**
 *  delete setup.ini file if installation has failed before
 */
$temp_path = LEPTON_PATH."/config/lepton.ini.php";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}
/**
 *	Write the db setup.ini file
 */
$ini_filepath = "../config/lepton.ini.php";
$s = ";
; <?php die(); ?>
; This file is part of LEPTON Core, released under the GNU GPL
; Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
;
; NOTICE:LEPTON CMS Package has several different licenses.
; Please see the individual license in the header of each single file or info.php of modules and templates.
;
; @author          LEPTON Project
; @copyright       2010-2017 LEPTON Project
; @link            https://www.LEPTON-cms.org
; @license         http://www.gnu.org/licenses/gpl.html
; @license_terms   please see LICENSE and COPYING files in your package
;
;

; DataBase-setup for LEPTON-CMS\n

[database]\n
type = 'mysql'
host = '".$database_host."'
port = '".$database_port."'
user = '".$database_username."'
pass = '".$database_password."'
name = '".$database_name."'
prefix = '".$table_prefix."'
";

$fp = fopen($ini_filepath, 'w');
if($fp) {
	fwrite( $fp , $s );
	fclose( $fp);
} else {
	set_error("Cannot open the setup file for the db!");
}

// Check if the user has entered a correct path
if(!file_exists(LEPTON_PATH.'/framework/classes/lepton_admin.php')) {
	set_error('It seems that the absolute path you entered is incorrect');
}

// Re-connect to the database, this time using built-in database class
require_once(LEPTON_PATH.'/framework/classes/lepton_database.php');
$database=new LEPTON_database();

// install tables
	if (!defined('WB_INSTALL_PROCESS')) define ('WB_INSTALL_PROCESS', true);

	// Remove tables if they exist
	$tables = array(
		"pages",
		"sections",
		"settings",
		"users",
		"groups",
		"search",
		"temp",		
		"addons"
	);
	
	foreach($tables as $table) {
		$database->simple_query( "DROP TABLE IF EXISTS `".TABLE_PREFIX.$table."`" );
	}
	
//force db to utf-8
$database->simple_query("ALTER DATABASE `".DB_NAME."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

	// Try installing tables
	// Pages table
	$table_fields ="
		  `page_id` int(11) NOT NULL auto_increment,
		  `parent` int(11) NOT NULL DEFAULT '0',
		  `root_parent` int(11) NOT NULL DEFAULT '0',
		  `level` int(11) NOT NULL DEFAULT '0',
		  `link` text NOT NULL,
		  `target` varchar(7) NOT NULL DEFAULT '',
		  `page_title` varchar(255) NOT NULL DEFAULT '',
		  `menu_title` varchar(255) NOT NULL DEFAULT '',
		  `description` text NOT NULL,
		  `keywords` text NOT NULL,
		  `page_trail` text NOT NULL,
		  `template` varchar(255) NOT NULL DEFAULT '',
		  `visibility` varchar(255) NOT NULL DEFAULT '',
		  `position` int(11) NOT NULL DEFAULT '0',
		  `menu` int(11) NOT NULL DEFAULT '0',
		  `language` varchar(5) NOT NULL DEFAULT '',
		  `page_code` varchar(100) NOT NULL DEFAULT '',
		  `searching` int(11) NOT NULL DEFAULT '0',
		  `admin_groups` text NOT NULL,
		  `admin_users` text NOT NULL,
		  `viewing_groups` text NOT NULL,
		  `viewing_users` text NOT NULL,
		  `modified_when` int(11) NOT NULL DEFAULT '0',
		  `modified_by` int(11) NOT NULL DEFAULT '0',
	       PRIMARY KEY ( `page_id` )
	       ";
	LEPTON_handle::install_table('pages', $table_fields);

	// Sections table
	$table_fields ="
		  `section_id` int(11) NOT NULL auto_increment,
		  `page_id` int(11) NOT NULL DEFAULT '0',
		  `position` int(11) NOT NULL DEFAULT '0',
		  `module` varchar(255) NOT NULL DEFAULT '',
		  `block` varchar(255) NOT NULL DEFAULT '',
		  `publ_start` varchar(255) NOT NULL DEFAULT '0',
		  `publ_end` varchar(255) NOT NULL DEFAULT '0',
		  `name` varchar(255) NOT NULL DEFAULT 'no name',
	       PRIMARY KEY ( `section_id` )
	       ";
	LEPTON_handle::install_table('sections', $table_fields);

	// Settings table
	$table_fields ="
			`setting_id` int(11) NOT NULL auto_increment,
			`name` varchar(255) NOT NULL DEFAULT '',
			`value` TEXT NOT NULL,
			PRIMARY KEY ( `setting_id` )
		";
	LEPTON_handle::install_table('settings', $table_fields);
	
	// insert standard settings
	$field_values =	"
			(1, 'lepton_version', '".VERSION."'),
			(2, 'website_title', '".$website_title."'),
			(3, 'website_description', ''),
			(4, 'website_keywords', ''),
			(5, 'website_header', 'LEPTON CMS 3series'),
			(6, 'website_footer', 'settings/website footer'),
			(7, 'backend_title', 'LEPTON CMS 3series'),
			(8, 'upload_whitelist', 'jpg,jpeg,gif,gz,png,pdf,tif,zip'),
			(9, 'er_level', '-1'),
			(10, 'prompt_mysql_errors', 'false'),
			(11, 'default_language', '".$default_language."'),
			(12, 'app_name', 'lep".$session_rand."'),
			(13, 'sec_anchor', 'lep_'),
			(14, 'default_timezone_string', '".$default_timezone_string."'),
			(15, 'default_date_format', 'M d Y'),
			(16, 'default_time_format', 'g:i A'),
			(17, 'redirect_timer', '1500'),
			(18, 'leptoken_lifetime', '1800'),
			(19, 'max_attempts', '6'),
			(20, 'home_folders', 'true'),
			(21, 'default_template', 'lepton3'),
			(22, 'default_theme', 'algos'),
			(23, 'default_charset', 'utf-8'),
			(24, 'link_charset', 'utf-8'),
			(25, 'multiple_menus', 'true'),
			(26, 'page_level_limit', '4'),
			(27, 'page_trash', 'inline'),
			(28, 'homepage_redirection', 'false'),
			(29, 'page_languages', 'false'),
			(30, 'wysiwyg_editor', 'tinymce'),
			(31, 'manage_sections', 'true'),
			(32, 'section_blocks', 'true'),
			(33, 'frontend_login', 'true'),
			(34, 'frontend_signup', '0'),
			(35, 'search', 'public'),
			(36, 'page_extension', '.php'),
			(37, 'page_spacer', '-'),
			(38, 'pages_directory', '/page'),
			(39, 'media_directory', '/media'),
			(40, 'operating_system', '".$operating_system."'),
			(41, 'string_file_mode', '".$file_mode."'),
			(42, 'string_dir_mode', '".$dir_mode."'),
			(43, 'mailer_routine', 'phpmail'),
			(44, 'server_email', '".$admin_email."'),
			(45, 'mailer_default_sendername', 'LEPTON Mailer'),
			(46, 'mailer_smtp_host', ''),
			(47, 'mailer_smtp_auth', ''),
			(48, 'mailer_smtp_secure', 'tls'),
			(49, 'mailer_smtp_port', '587'),
			(50, 'mailer_smtp_username', ''),
			(51, 'mailer_smtp_password', ''),
			(52, 'mediasettings', ''),
			(53, 'enable_old_language_definitions', 'true')
		";		
LEPTON_handle::insert_values('settings', $field_values);	
	

	// temp table
	$table_fields =" 
			`temp_id` int(2) NOT NULL auto_increment,
			`temp_browser` varchar(64) NOT NULL DEFAULT '',
			`temp_ip` varchar(64) NOT NULL DEFAULT '',
			`temp_time` int(24) NOT NULL DEFAULT '0',
			`temp_count` int(2) NOT NULL DEFAULT '0',
			`temp_active` tinyint(1) NOT NULL DEFAULT '0',	
			PRIMARY KEY ( `temp_id` )
		";
	LEPTON_handle::install_table('temp', $table_fields);
	
	// Users table
	$table_fields =" 
		  `user_id` int(11) NOT NULL auto_increment,
		  `group_id` int(11) NOT NULL DEFAULT '0',
		  `groups_id` varchar(255) NOT NULL DEFAULT '0',
		  `active` int(11) NOT NULL DEFAULT '0',
		  `statusflags` int(11) NOT NULL DEFAULT '6',
		  `username` varchar(255) NOT NULL DEFAULT '',
		  `password` varchar(255) NOT NULL DEFAULT '',
		  `last_reset` int(11) NOT NULL DEFAULT '0',
		  `display_name` varchar(255) NOT NULL DEFAULT '',
		  `email` varchar(128) NOT NULL,
		  `timezone_string` varchar(50) NOT NULL DEFAULT 'Europe/Berlin',
		  `date_format` varchar(255) NOT NULL DEFAULT '',
		  `time_format` varchar(255) NOT NULL DEFAULT '',
		  `language` varchar(5) NOT NULL DEFAULT 'EN',
		  `home_folder` text NOT NULL,
		  `login_when` int(11) NOT NULL DEFAULT '0',
		  `login_ip` varchar(15) NOT NULL DEFAULT '',
	       PRIMARY KEY ( `user_id` ),
	       UNIQUE KEY ( `email` ),
	       UNIQUE KEY ( `username` )
		";
	LEPTON_handle::install_table('users', $table_fields);

	// Groups table
	$table_fields =" 
		  `group_id` int(11) NOT NULL auto_increment,
		  `name` varchar(255) NOT NULL DEFAULT '',
		  `system_permissions` text NOT NULL,
		  `module_permissions` text NOT NULL,
		  `template_permissions` text NOT NULL,
		  `language_permissions` text NOT NULL,	
	       PRIMARY KEY ( `group_id` ),
	       UNIQUE KEY ( `name` )	
		";
	LEPTON_handle::install_table('groups', $table_fields);

	// Addons table
	$table_fields =" 
		  `addon_id` int(11) NOT NULL auto_increment,
		  `type` varchar(128) NOT NULL DEFAULT '',
		  `directory` varchar(128) NOT NULL DEFAULT '',
		  `name` varchar(255) NOT NULL DEFAULT '',
		  `description` text NOT NULL,
		  `function` varchar(255) NOT NULL DEFAULT '',
		  `version` varchar(255) NOT NULL DEFAULT '',
		  `guid` varchar(50) DEFAULT NULL,
		  `platform` varchar(255) NOT NULL DEFAULT '',
		  `author` varchar(255) NOT NULL DEFAULT '',
		  `license` varchar(255) NOT NULL DEFAULT '',
		  PRIMARY KEY (`addon_id`)
		";
	LEPTON_handle::install_table('addons', $table_fields);
	
	// Insert default data
	// Admin and Register group
	$full_system_permissions = 'pages,pages_view,pages_add,pages_add_l0,pages_settings,pages_modify,pages_delete,media,media_view,media_upload,media_rename,media_delete,media_create,addons,modules,modules_view,modules_install,modules_uninstall,templates,templates_view,templates_install,templates_uninstall,languages,languages_view,languages_install,languages_uninstall,settings,settings_basic,settings_advanced,access,users,users_view,users_add,users_modify,users_delete,groups,groups_view,groups_add,groups_modify,groups_delete,admintools,service';
	$insert_admin_group = "INSERT INTO `".TABLE_PREFIX."groups` VALUES ('1', 'Administrators', '$full_system_permissions', '', '', ''), ('2', 'Register', 'pages_view,pages,languages_view,languages', '', '', '')";
	$database->simple_query($insert_admin_group);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	// Admin user
	$insert_admin_user = "INSERT INTO `".TABLE_PREFIX."users` (user_id,group_id,groups_id,active,username,password,email,display_name,`home_folder`) VALUES ('1','1','1','1','$admin_username','".password_hash( $admin_password, PASSWORD_DEFAULT)."','$admin_email','Administrator', '')";
	$database->simple_query($insert_admin_user);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);


	require_once(LEPTON_PATH.'/framework/initialize.php');

	$admin=new admin_dummy();
	// Load addons into DB
	$dirs = array(
		'modules'	=> LEPTON_PATH.'/modules/',
		'templates'	=> LEPTON_PATH.'/templates/',
		'languages'	=>  LEPTON_PATH.'/languages/'
	);
	$ignore_files= array(
		'admin.php',
		'index.php',
		'edit_module_files.php'
	);

	foreach($dirs AS $type => $dir) {
		if(false !== ($handle = opendir($dir))) {
			$temp_list = array();
			while(false !== ($file = readdir($handle))) {
				if(($file != '') && (substr($file, 0, 1) != '.') && (!in_array($file, $ignore_files))) {
					$temp_list[] = $file;
				}
			}
			natsort($temp_list);

			foreach($temp_list as $file) {
				// Get addon type

				if($type == 'modules') {
					require ($dir.'/'.$file.'/info.php');
					load_module($dir.'/'.$file, true);

					foreach(
						array(
							'module_license', 'module_author'  , 'module_name', 'module_directory',
							'module_version', 'module_function', 'module_description',
							'module_platform', 'module_guid'
						) as $varname
					) {
						if (isset(  ${$varname} ) ) unset( ${$varname} );
					}

					// Pretty ugly hack to let modules run $admin->set_error
					// See dummy class definition admin_dummy above
					if ($admin->error!='') {
						set_error($admin->error);
					}
				} elseif($type == 'templates') {
					require ($dir.'/'.$file.'/info.php');
					load_template($dir.'/'.$file);

					foreach(
						array(
							'template_license', 'template_author'  , 'template_name', 'template_directory',
							'template_version', 'template_function', 'template_description',
							'template_platform', 'template_guid'
						) as $varname
					) {
						if (isset(  ${$varname} ) ) unset( ${$varname} );
					}

				} elseif($type == 'languages') {
					load_language($dir.'/'.$file);
				}
			}
			closedir($handle);
			unset($temp_list);
		}
	}

	// Check if there was a database error
	if($database->is_error()) {
		set_error($database->get_error());
	}

// end of if install_tables

// create first standard page
	require_once("init_page.php");
	$p = new init_page( $database );
	$p->language = $default_language;
	$p->build_page();

// redirect to the backend login
header("Location: ../install/support.php" );

?>