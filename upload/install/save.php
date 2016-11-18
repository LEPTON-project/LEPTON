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
 * @link            https://www.LEPTON-cms.org
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

/**
 *
 *
 *
 */
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
$timezone_table = array(
	"Pacific/Kwajalein",
	"Pacific/Samoa",
	"Pacific/Honolulu",
	"America/Anchorage",
	"America/Los_Angeles",
	"America/Phoenix",
	"America/Mexico_City",
	"America/Lima",
	"America/Caracas",
	"America/Halifax",
	"America/Buenos_Aires",
	"Atlantic/Reykjavik",
	"Atlantic/Azores",
	"Europe/London",
	"Europe/Berlin",
	"Europe/Kaliningrad",
	"Europe/Moscow",
	"Asia/Tehran",
	"Asia/Baku",
	"Asia/Kabul",
	"Asia/Tashkent",
	"Asia/Calcutta",
	"Asia/Colombo",
	"Asia/Bangkok",
	"Asia/Hong_Kong",
	"Asia/Tokyo",
	"Australia/Adelaide",
	"Pacific/Guam",
	"Etc/GMT+10",
	"Pacific/Fiji"
);
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

// Find out if the user wants to install tables and data
if(isset($_POST['install_tables']) && $_POST['install_tables'] == 'true') {
	$install_tables = true;
} else {
	$install_tables = false;
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
	if(preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$/i', $_POST['admin_email'])) {
		$admin_email = $_POST['admin_email'];
	} else {
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
include($lepton_path.'/admins/interface/version.php');

// Try and write settings to config file
$config_content = "" .
"<?php\n".
"\n".
"if(defined('LEPTON_PATH')) { die('By security reasons it is not permitted to load \'config.php\' twice!! ".
"Forbidden call from \''.\$_SERVER['SCRIPT_NAME'].'\'!'); }\n\n".
"// config file created by ".CORE." ".VERSION."\n".
"define('DB_TYPE', 'mysql');\n".
"define('DB_HOST', '$database_host');\n".
"define('DB_PORT', '$database_port');\n".
"define('DB_USERNAME', '$database_username');\n".
"define('DB_PASSWORD', '$database_password');\n".
"define('DB_NAME', '$database_name');\n".
"define('TABLE_PREFIX', '$table_prefix');\n".
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

if(($handle = @fopen($config_filename, 'w')) === false) {
	set_error("Cannot open the configuration file ($config_filename)");
} else {
	if (fwrite($handle, $config_content, strlen($config_content) ) === false) {
		fclose($handle);
		set_error("Cannot write to the configuration file ($config_filename)");
	}
	// Close file
	fclose($handle);
}

// Check if the user has entered a correct path
if(!file_exists(LEPTON_PATH.'/framework/class.admin.php')) {
	set_error('It seems that the absolute path you entered is incorrect');
}

// Re-connect to the database, this time using built-in database class
require_once(LEPTON_PATH.'/framework/class.database.php');
$database=new database();

// Check if we should install tables
if($install_tables == true) {
	if (!defined('WB_INSTALL_PROCESS')) define ('WB_INSTALL_PROCESS', true);

	// Remove tables if they exist
	$tables = array(
		"pages",
		"sections",
		"settings",
		"users",
		"groups",
		"search",
		"addons"
	);
	
	foreach($tables as $table) {
		$database->query( "DROP TABLE IF EXISTS `".TABLE_PREFIX.$table."`" );
	}
	
//force db to utf-8
$database->query("ALTER DATABASE `".DB_NAME."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

	// Try installing tables
	// Pages table
	$pages = 'CREATE TABLE `'.TABLE_PREFIX.'pages` ( `page_id` INT NOT NULL auto_increment,'
	       . ' `parent` INT NOT NULL DEFAULT \'0\','
	       . ' `root_parent` INT NOT NULL DEFAULT \'0\','
	       . ' `level` INT NOT NULL DEFAULT \'0\','
	       . ' `link` TEXT NOT NULL,'
	       . ' `target` VARCHAR( 7 ) NOT NULL DEFAULT \'\' ,'
	       . ' `page_title` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
	       . ' `menu_title` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
	       . ' `description` TEXT NOT NULL ,'
	       . ' `keywords` TEXT NOT NULL ,'
	       . ' `page_trail` TEXT NOT NULL  ,'
	       . ' `template` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
	       . ' `visibility` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
	       . ' `position` INT NOT NULL DEFAULT \'0\','
	       . ' `menu` INT NOT NULL DEFAULT \'0\','
	       . ' `language` VARCHAR( 5 ) NOT NULL DEFAULT \'\' ,'
	       . ' `page_code` VARCHAR( 100 ) NOT NULL DEFAULT \'\' ,'         
	       . ' `searching` INT NOT NULL DEFAULT \'0\','
	       . ' `admin_groups` TEXT NOT NULL ,'
	       . ' `admin_users` TEXT NOT NULL ,'
	       . ' `viewing_groups` TEXT NOT NULL ,'
	       . ' `viewing_users` TEXT NOT NULL ,'
	       . ' `modified_when` INT NOT NULL DEFAULT \'0\','
	       . ' `modified_by` INT NOT NULL  DEFAULT \'0\','
	       . ' PRIMARY KEY ( `page_id` ) '
	       . ' )';
	$database->query($pages);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	// Sections table
	$sections = 'CREATE TABLE `'.TABLE_PREFIX.'sections` ( `section_id` INT NOT NULL auto_increment,'
	       . ' `page_id` INT NOT NULL DEFAULT \'0\','
	       . ' `position` INT NOT NULL DEFAULT \'0\','
	       . ' `module` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
	       . ' `block` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
	       . ' `publ_start` VARCHAR( 255 ) NOT NULL DEFAULT \'0\' ,'
	       . ' `publ_end` VARCHAR( 255 ) NOT NULL DEFAULT \'0\' ,'
	       . ' `name` VARCHAR( 255 ) NOT NULL DEFAULT \'no name\' ,'
	       . ' PRIMARY KEY ( `section_id` ) '
	       . ' )';
	$database->query($sections);
    if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	// Settings table
	$settings='CREATE TABLE `'.TABLE_PREFIX.'settings` ( `setting_id` INT NOT NULL auto_increment,'
		. ' `name` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
		. ' `value` TEXT NOT NULL ,'
		. ' PRIMARY KEY ( `setting_id` ) '
		. ' )';
	$database->query($settings);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	$settings_rows=	"INSERT INTO `".TABLE_PREFIX."settings` "
	." (name, value) VALUES "
	." ('lepton_version', '".VERSION."'),"
	." ('website_title', '$website_title'),"
	." ('website_description', ''),"
	." ('website_keywords', ''),"
	." ('website_header', 'LEPTON CMS 2series'),"
	." ('website_footer', 'settings/website footer'),"
	." ('backend_title', 'LEPTON CMS 2series'),"
	." ('rename_files_on_upload', 'jpg,jpeg,gif,gz,png,pdf,tif,zip'),"
	." ('er_level', '-1'),"
	." ('prompt_mysql_errors', 'false'),"
	." ('default_language', '$default_language'),"
	." ('app_name', 'lep$session_rand'),"
	." ('sec_anchor', 'lep_'),"
	." ('default_timezone_string', '$default_timezone_string'),"
	." ('default_date_format', 'M d Y'),"
	." ('default_time_format', 'g:i A'),"
	." ('redirect_timer', '1500'),"
	." ('leptoken_lifetime', '1800'),"
	." ('max_attempts', '3'),"
	." ('home_folders', 'true'),"
	." ('default_template', 'lepton2'),"
	." ('default_theme', 'algos'),"
	." ('default_charset', 'utf-8'),"
	." ('link_charset', 'utf-8'),"	
	." ('multiple_menus', 'true'),"
	." ('page_level_limit', '4'),"
	." ('page_trash', 'inline'),"
	." ('homepage_redirection', 'false'),"
	." ('page_languages', 'false'),"
	." ('wysiwyg_editor', 'tiny_mce_4'),"
	." ('manage_sections', 'true'),"
	." ('section_blocks', 'true'),"
	." ('frontend_login', 'false'),"
	." ('frontend_signup', 'false'),"
	." ('search', 'public'),"
	." ('page_extension', '.php'),"
	." ('page_spacer', '-'),"
	." ('pages_directory', '/page'),"
	." ('media_directory', '/media'),"
	." ('operating_system', '$operating_system'),"
	." ('string_file_mode', '$file_mode'),"
	." ('string_dir_mode', '$dir_mode'),"
	." ('wbmailer_routine', 'phpmail'),"
	." ('server_email', '$admin_email'),"		// avoid that mail provider (e.g. mail.com) reject mails like yourname@mail.com
	." ('wbmailer_default_sendername', 'LEPTON Mailer'),"
	." ('wbmailer_smtp_host', ''),"
	." ('wbmailer_smtp_auth', ''),"
	." ('wbmailer_smtp_username', ''),"
	." ('wbmailer_smtp_password', ''),"
	." ('mediasettings', ''),"
	." ('enable_old_language_definitions','true')";
	$database->query($settings_rows);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	// temp table
	$temp_table='CREATE TABLE `'.TABLE_PREFIX.'temp` ( 
		   `temp_id` INT( 2 ) NOT NULL auto_increment,'
		. '`temp_browser` varchar(64) NOT NULL DEFAULT "",'
		. '`temp_ip` varchar(64) NOT NULL DEFAULT "",'
		. '`temp_time` int(24) NOT NULL DEFAULT "0",'
		. '`temp_count` int(2) NOT NULL DEFAULT "0",'
		. '`temp_active` tinyint(1) NOT NULL DEFAULT "0",'		
		. ' PRIMARY KEY ( `temp_id` ) '
		. ' )';
	$database->query( $temp_table );
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);	
	
	// Users table
	$users = 'CREATE TABLE `'.TABLE_PREFIX.'users` ( `user_id` INT NOT NULL auto_increment,'
	       . ' `group_id` INT NOT NULL DEFAULT \'0\','
	       . ' `groups_id` VARCHAR( 255 ) NOT NULL DEFAULT \'0\','
	       . ' `active` INT NOT NULL DEFAULT \'0\','
		   . ' `statusflags` INT NOT NULL DEFAULT \'6\','
	       . ' `username` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
	       . ' `password` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
	       . ' `last_reset` INT NOT NULL DEFAULT \'0\','
	       . ' `display_name` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
	       . ' `email` VARCHAR( 128 ) NOT NULL ,'
	       . ' `timezone_string` VARCHAR( 50 ) NOT NULL DEFAULT \'' .$default_timezone_string.'\' ,'
	       . ' `date_format` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
	       . ' `time_format` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
	       . ' `language` VARCHAR( 5 ) NOT NULL DEFAULT \'' .$default_language .'\' ,'
	       . ' `home_folder` TEXT NOT NULL ,'
	       . ' `login_when` INT NOT NULL  DEFAULT \'0\','
	       . ' `login_ip` VARCHAR( 15 ) NOT NULL DEFAULT \'\' ,'
	       . ' PRIMARY KEY ( `user_id` ) ,'
	       . ' UNIQUE KEY ( `email` ) ,'
	       . ' UNIQUE KEY ( `username` ) '
	       . ' )';
	$database->query($users);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	// Groups table
	$groups = 'CREATE TABLE `'.TABLE_PREFIX.'groups` ( `group_id` INT NOT NULL auto_increment,'
	        . ' `name` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
	        . ' `system_permissions` TEXT NOT NULL ,'
	        . ' `module_permissions` TEXT NOT NULL ,'
	        . ' `template_permissions` TEXT NOT NULL ,'
	        . ' PRIMARY KEY ( `group_id` ), '
	        . ' UNIQUE KEY ( `name` ) '			
	        . ' )';
	$database->query($groups);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	// Search settings table
	$search = 'CREATE TABLE `'.TABLE_PREFIX.'search` ( 
	          `search_id` INT NOT NULL auto_increment,'
	        . ' `name` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
	        . ' `value` TEXT NOT NULL ,'
	        . ' `extra` TEXT NOT NULL ,'
	        . ' PRIMARY KEY ( `search_id` ) '
	        . ' )';
	$database->query($search);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	// Addons table
	$addons = 'CREATE TABLE `'.TABLE_PREFIX.'addons` ( '
			.'`addon_id` INT NOT NULL auto_increment,'
			.'`type` VARCHAR( 128 ) NOT NULL DEFAULT \'\' ,'
			.'`directory` VARCHAR( 128 ) NOT NULL DEFAULT \'\' ,'
			.'`name` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
			.'`description` TEXT NOT NULL ,'
			.'`function` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
			.'`version` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
			.'`guid` VARCHAR( 50 ) NULL,'
			.'`platform` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
			.'`author` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
			.'`license` VARCHAR( 255 ) NOT NULL DEFAULT \'\' ,'
			.' PRIMARY KEY (`addon_id`)'
			.' )';
	$database->query($addons);

	// error reporting for problems while installing the tables
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);
	
	// Insert default data
	// Admin group
	$full_system_permissions = 'pages,pages_view,pages_add,pages_add_l0,pages_settings,pages_modify,pages_delete,media,media_view,media_upload,media_rename,media_delete,media_create,addons,modules,modules_view,modules_install,modules_uninstall,templates,templates_view,templates_install,templates_uninstall,languages,languages_view,languages_install,languages_uninstall,settings,settings_basic,settings_advanced,access,users,users_view,users_add,users_modify,users_delete,groups,groups_view,groups_add,groups_modify,groups_delete,admintools,service';
	$insert_admin_group = "INSERT INTO `".TABLE_PREFIX."groups` VALUES ('1', 'Administrators', '$full_system_permissions', '', '')";
	$database->query($insert_admin_group);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	// Admin user
	// function compatibility from php 5.3.7 to php 5.5
	// can be removed if php 5.5 is required as a minimum 
	if (!function_exists('password_hash')) {
		require_once (LEPTON_PATH.'/modules/lib_lepton/hash/password.php');
	} 
	$insert_admin_user = "INSERT INTO `".TABLE_PREFIX."users` (user_id,group_id,groups_id,active,username,password,email,display_name,`home_folder`) VALUES ('1','1','1','1','$admin_username','".password_hash( $admin_password, PASSWORD_DEFAULT)."','$admin_email','Administrator', '')";
	$database->query($insert_admin_user);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	// Search header
	$search_header = addslashes('
<h1>[TEXT_SEARCH]</h1>

<form name="searchpage" action="[LEPTON_URL]/search/index.php" method="get">
<table cellpadding="3" cellspacing="0" border="0" width="500">
<tr>
<td>
<input type="hidden" name="search_path" value="[SEARCH_PATH]" />
<input type="text" name="string" value="[SEARCH_STRING]" style="width: 100%;" />
</td>
<td width="150">
<input type="submit" value="[TEXT_SEARCH]" style="width: 100%;" />
</td>
</tr>
<tr>
<td colspan="2">
<input type="radio" name="match" id="match_all" value="all"[ALL_CHECKED] />
<label for="match_all">[TEXT_ALL_WORDS]</label>
<input type="radio" name="match" id="match_any" value="any"[ANY_CHECKED] />
<label for="match_any">[TEXT_ANY_WORDS]</label>
<input type="radio" name="match" id="match_exact" value="exact"[EXACT_CHECKED] />
<label for="match_exact">[TEXT_EXACT_MATCH]</label>
</td>
</tr>
</table>

</form>

<hr />
	');
	$insert_search_header = "INSERT INTO `".TABLE_PREFIX."search` VALUES (NULL, 'header', '$search_header', '')";
	$database->query($insert_search_header);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	// Search footer
	$search_footer = addslashes('');
	$insert_search_footer = "INSERT INTO `".TABLE_PREFIX."search` VALUES (NULL, 'footer', '$search_footer', '')";
	$database->query($insert_search_footer);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	// Search results header
	$search_results_header = addslashes(''.
'[TEXT_RESULTS_FOR] \'<b>[SEARCH_STRING]</b>\':
<table cellpadding="2" cellspacing="0" border="0" width="100%" style="padding-top: 10px;">');
	$insert_search_results_header = "INSERT INTO `".TABLE_PREFIX."search` VALUES (NULL, 'results_header', '$search_results_header', '')";
	$database->query($insert_search_results_header);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	// Search results loop
	$search_results_loop = addslashes(''.
'<tr style="background-color: #F0F0F0;">
<td>[LOCK]<a href="[LINK]">[TITLE]</a></td>
<td align="right">[TEXT_LAST_UPDATED_BY] [DISPLAY_NAME] ([USERNAME]) [TEXT_ON] [DATE]</td>
</tr>
<tr><td colspan="2" style="text-align: justify; padding-bottom: 5px;">[DESCRIPTION]</td></tr>
<tr><td colspan="2" style="text-align: justify; padding-bottom: 10px;">[EXCERPT]</td></tr>');

	$insert_search_results_loop = "INSERT INTO `".TABLE_PREFIX."search` VALUES (NULL, 'results_loop', '$search_results_loop', '')";
	$database->query($insert_search_results_loop);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	// Search results footer
	$search_results_footer = addslashes("</table>");
	$insert_search_results_footer = "INSERT INTO `".TABLE_PREFIX."search` VALUES (NULL, 'results_footer', '$search_results_footer', '')";
	$database->query($insert_search_results_footer);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	// Search no results
	$search_no_results = addslashes('<tr><td><p>[TEXT_NO_RESULTS]</p></td></tr>');
	$insert_search_no_results = "INSERT INTO `".TABLE_PREFIX."search` VALUES (NULL, 'no_results', '$search_no_results', '')";
	$database->query($insert_search_no_results);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	// Search module-order
	$search_module_order = addslashes('wysiwyg');
	$insert_search_module_order = "INSERT INTO `".TABLE_PREFIX."search` VALUES (NULL, 'module_order', '$search_module_order', '')";
	$database->query($insert_search_module_order);
	// Search max lines of excerpt
	$search_max_excerpt = addslashes('15');
	$insert_search_max_excerpt = "INSERT INTO `".TABLE_PREFIX."search` VALUES (NULL, 'max_excerpt', '$search_max_excerpt', '')";
	$database->query($insert_search_max_excerpt);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	// max time to search per module
	$search_time_limit = addslashes('0');
	$insert_search_time_limit = "INSERT INTO `".TABLE_PREFIX."search` VALUES (NULL, 'time_limit', '$search_time_limit', '')";
	$database->query($insert_search_time_limit);
	if ($database->is_error()) trigger_error(sprintf('[%s - %s] %s', __FILE__, __LINE__, $database->get_error()), E_USER_ERROR);

	// some config-elements
	$database->query("INSERT INTO `".TABLE_PREFIX."search` VALUES (NULL, 'cfg_enable_old_search', 'false', '')");
	$database->query("INSERT INTO `".TABLE_PREFIX."search` VALUES (NULL, 'cfg_search_keywords', 'true', '')");
	$database->query("INSERT INTO `".TABLE_PREFIX."search` VALUES (NULL, 'cfg_search_description', 'true', '')");
	// allow the search function to search and show non-public content (registered or private)
	$database->query("INSERT INTO `".TABLE_PREFIX."search` VALUES (NULL, 'cfg_search_non_public_content', 'false', '')");
	// link for search results with non-public content
	$database->query("INSERT INTO `".TABLE_PREFIX."search` VALUES (NULL, 'cfg_link_non_public_content', '', '')");
	$database->query("INSERT INTO `".TABLE_PREFIX."search` VALUES (NULL, 'cfg_show_description', 'true', '')");
	$database->query("INSERT INTO `".TABLE_PREFIX."search` VALUES (NULL, 'cfg_enable_flush', 'false', '')");
	// Search template
	$database->query("INSERT INTO `".TABLE_PREFIX."search` VALUES (NULL, 'template', '', '')");

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
} else {
	/**
	 *	DB - Exists
	 *	Tables also?
	 *
	 */
	$requested_tables = array("pages","sections","settings","users","groups","search","addons");
	for($i=0;$i<count($requested_tables);$i++) $requested_tables[$i] = $table_prefix.$requested_tables[$i];

	$result = mysql_query("SHOW TABLES FROM ".DB_NAME);
	$all_tables = array();
	for($i=0; $i < mysql_num_rows($result); $i++) $all_tables[] = mysql_table_name($result, $i);

	$missing_tables = array();
	foreach($requested_tables as $temp_table) {
		if (!in_array($temp_table, $all_tables)) {
			$missing_tables[] = $temp_table;
		}
	}

	/**
	 *	If one or more needed tables are missing, so
	 *	we can't go on and have to display an error
	 */
	if ( count($missing_tables) > 0 ) {
		$error_message  = "One or more tables are missing in the selected database <b><font color='#990000'>".DB_NAME."</font></b>.<br />";
		$error_message .= "Please install the missing tables or choose 'install tables' as recommend.<br />";
		$error_message .= "Missing tables are: <b>".implode(", ", $missing_tables)."</b>";

		set_error( $error_message );
	}

	/**
	 *	Try to get some default settings ...
	 *	Keep in Mind, that the values are only used as default, if an entry isn't found.
	 */
	$vars = array(
		'DEFAULT_THEME'	=> "algos",
		'THEME_URL'		=> LEPTON_URL."/templates/algos",
		'THEME_PATH'	=> LEPTON_PATH."/templates/algos",
		'LANGUAGE'		=> $_POST['default_language'],
		'SERVER_EMAIL'	=> "admin@yourdomain.tld",
		'PAGES_DIRECTORY' => '/page',
		'ENABLE_OLD_LANGUAGE_DEFINITIONS' => true
	);
	foreach($vars as $k => $v) {
		if (!defined($k)) {
			$temp_val = $database->get_one("SELECT `value` from `".$table_prefix."settings` where `name`='".strtolower($k)."'");
			if ( $temp_val ) $v = $temp_val;
			define($k, $v);
		}
	}

	if (!isset($MESSAGE)) include (LEPTON_PATH."/languages/".LANGUAGE.".php");

	/**
	 *	The important part ...
	 *	Is there an valid user?
	 */
	$result = $database->query("SELECT * from `".$table_prefix."users` where `username`='".$_POST['admin_username']."'");
	if ( $database->is_error() ) {
		set_error ($database->get_error() );
	}
	if ($result->numRows() == 0) {
		/**
		 *	No matches found ... user properly unknown
	 	 */
	 	set_error ("Unkown user. Please use a valid username.");
	} else {

		$data = $result->fetchRow();
	 	/**
	 	 *	Does the password match?
	 	 */
		$check = password_verify($_POST['admin_password'], $data['password']);		
	 	if ($check != 1) {
	 		set_error ("Password didn't match");
	 	}
	}
}

if($install_tables == true) {
	require_once("c_wb_init_page.php");
	$p = new wb_init_page( $database );
	$p->url = "https://doc.lepton-cms.org/_packinstall/start-package2.html";
	$p->language = $default_language;
	$p->build_page();
}

// redirect to the backend login
header("Location: ../install/support.php" );

?>