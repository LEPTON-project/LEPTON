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
 * @version         $Id: class.wb.php 1444 2011-12-04 08:34:04Z frankh $
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



include_once(WB_PATH.'/framework/class.securecms.php'); 

// Include PHPLIB template class
require_once(WB_PATH."/include/phplib/template.inc");

require_once(WB_PATH.'/framework/class.database.php');

// Include new wbmailer class (subclass of PHPmailer)
require_once(WB_PATH."/framework/class.wbmailer.php");

class wb extends SecureCMS
{

	public $password_chars = 'a-zA-Z0-9\_\-\!\#\*\+';

	// General initialization public function
	// performed when frontend or backend is loaded.

	public function __construct() {

	}

	// Check whether a page is visible or not.
	// This will check page-visibility and user- and group-rights.
	/* page_is_visible() returns
		false: if page-visibility is 'none' or 'deleted', or page-vis. is 'registered' or 'private' and user isn't allowed to see the page.
		true: if page-visibility is 'public' or 'hidden', or page-vis. is 'registered' or 'private' and user _is_ allowed to see the page.
	*/
	public function page_is_visible($page)
    {
		// First check if visibility is 'none', 'deleted'
		$show_it = false; // shall we show the page?
		switch( $page['visibility'] )
		{
			case 'none':
			case 'deleted':
				$show_it = false;
				break;
			case 'hidden':
			case 'public':
				$show_it = true;
				break;
			case 'private':
			case 'registered':
				if($this->is_authenticated() == true)
				{
					$show_it = ( $this->is_group_match($this->get_groups_id(), $page['viewing_groups']) ||
								 $this->is_group_match($this->get_user_id(), $page['viewing_users']) );
				}
		}

		return($show_it);
	}

	public function section_is_active($section_id)
	{
		global $database;
		$now = time();
		$sql  = 'SELECT COUNT(*) FROM `'.TABLE_PREFIX.'sections` ';
		$sql .= 'WHERE ('.$now.' BETWEEN `publ_start` AND `publ_end`) OR ';
		$sql .=       '('.$now.' > `publ_start` AND `publ_end`=0) ';
		$sql .=       'AND `section_id`='.$section_id;
		return ($database->get_one($sql) != false);
	}
	// Check if there is at least one active section on this page
	public function page_is_active($page)
    {
		global $database;
		$now = time();
		$sql  = 'SELECT COUNT(*) FROM `'.TABLE_PREFIX.'sections` ';
		$sql .= 'WHERE ('.$now.' BETWEEN `publ_start` AND `publ_end`) OR ';
		$sql .=       '('.$now.' > `publ_start` AND `publ_end`=0) ';
		$sql .=       'AND `page_id`='.(int)$page['page_id'];
		return ($database->get_one($sql) != false);
	}

	// Check whether we should show a page or not (for front-end)
	public function show_page($page)
    {
		if( !is_array($page) )
		{
			$sql  = 'SELECT `page_id`, `visibility`, `viewing_groups`, `viewing_users` ';
			$sql .= 'FROM `'.TABLE_PREFIX.'pages` WHERE `page_id`='.(int)$page;
			if( ($res_pages = $database->query($sql))!= null )
			{
				if( !($page = $res_pages->fetchRow()) ) { return false; }
			}
		}
		return ($this->page_is_visible($page) && $this->page_is_active($page));
	}

	// Check if the user is already authenticated or not
	public function is_authenticated() {
		if(isset($_SESSION['USER_ID']) && $_SESSION['USER_ID'] != "" && is_numeric($_SESSION['USER_ID']))
        {
			return true;
		} else {
			return false;
		}
	}

	// Modified addslashes public function which takes into account magic_quotes
	public function add_slashes($input) {
		if ( get_magic_quotes_gpc() || ( !is_string($input) ) ) {
			return $input;
		}
		$output = addslashes($input);
		return $output;
	}

	// Ditto for stripslashes
	// Attn: this is _not_ the counterpart to $this->add_slashes() !
	// Use stripslashes() to undo a preliminarily done $this->add_slashes()
	// The purpose of $this->strip_slashes() is to undo the effects of magic_quotes_gpc==On
	public function strip_slashes($input) {
		if ( !get_magic_quotes_gpc() || ( !is_string($input) ) ) {
			return $input;
		}
		$output = stripslashes($input);
		return $output;
	}

	// Escape backslashes for use with mySQL LIKE strings
	public function escape_backslashes($input) {
		return str_replace("\\","\\\\",$input);
	}

	public function page_link($link){
		// Check for :// in the link (used in URL's) as well as mailto:
		if(strstr($link, '://') == '' && substr($link, 0, 7) != 'mailto:') {
			return WB_URL.PAGES_DIRECTORY.$link.PAGE_EXTENSION;
		} else {
			return $link;
		}
	}
	
	// Get POST data
	public function get_post($field) {
        return isset($_POST[$field]) ? $_POST[$field] : null;
	}

	// Get POST data and escape it
	public function get_post_escaped($field) {
		$result = $this->get_post($field);
		return (is_null($result)) ? null : $this->add_slashes($result);
	}
	
	// Get GET data
	public function get_get($field) {
        return isset($_GET[$field]) ? $_GET[$field] : null;
	}

	// Get SESSION data
	public function get_session($field) {
        return isset($_SESSION[$field]) ? $_SESSION[$field] : null;
	}

	// Get SERVER data
	public function get_server($field) {
        return isset($_SERVER[$field]) ? $_SERVER[$field] : null;
	}

	// Get the current users id
	public function get_user_id() {
		return isset($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : '';
	}

	// Get the current users group id (deprecated)
	public function get_group_id() {
		return isset($_SESSION['GROUP_ID']) ? $_SESSION['GROUP_ID'] : '';
	}

	// Get the current users group ids
	public function get_groups_id() {
	    return explode(",", isset($_SESSION['GROUPS_ID']) ? $_SESSION['GROUPS_ID'] : '');
	}

	// Get the current users group name
	public function get_group_name() {
		return implode(",", $_SESSION['GROUP_NAME']);
	}

	// Get the current users group name
	public function get_groups_name() {
		return isset($_SESSION['GROUP_NAME']) ? $_SESSION['GROUP_NAME'] : '';
	}

	// Get the current users username
	public function get_username() {
		return isset($_SESSION['USERNAME']) ? $_SESSION['USERNAME'] : '';
	}

	// Get the current users display name
	public function get_display_name() {
		return isset($_SESSION['DISPLAY_NAME']) ? $_SESSION['DISPLAY_NAME'] : '';
	}

	// Get the current users email address
	public function get_email() {
		return isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : '';
	}

	// Get the current users home folder
	public function get_home_folder() {
		return isset($_SESSION['HOME_FOLDER']) ? $_SESSION['HOME_FOLDER'] : '';
	}

	// Get the current users timezone
	public function get_timezone_string() {
        return  isset($_SESSION['TIMEZONE_STRING']) ? $_SESSION['TIMEZONE_STRING'] : DEFAULT_TIMEZONESTRING;   
	}

/* ****************
 * check if one or more group_ids are in both group_lists
 *
 * @access public
 * @param mixed $groups_list1: an array or a coma seperated list of group-ids
 * @param mixed $groups_list2: an array or a coma seperated list of group-ids
 * @return bool: true there is a match, otherwise false
 */
	public function is_group_match( $groups_list1 = '', $groups_list2 = '' )
	{
		if( $groups_list1 == '' ) { return false; }
		if( $groups_list2 == '' ) { return false; }
		if( !is_array($groups_list1) )
		{
			$groups_list1 = explode(',', $groups_list1);
		}
		if( !is_array($groups_list2) )
		{
			$groups_list2 = explode(',', $groups_list2);
		}

		return ( sizeof(array_intersect( $groups_list1, $groups_list2)) != 0 );
	}

/* ****************
 * check if current user is member of at least one of given groups
 * ADMIN (uid=1) always is treated like a member of any groups
 *
 * @access public
 * @param mixed $groups_list: an array or a coma seperated list of group-ids
 * @return bool: true if current user is member of one of this groups, otherwise false
 */
	public function ami_group_member( $groups_list = '' )
	{
		if( $this->get_user_id() == 1 ) { return true; }
		return $this->is_group_match( $groups_list, $this->get_groups_id() );
	}

	/**
	 *	Validate supplied email address
	 *
	 */
	public function validate_email($email) {
		if(preg_match('/^([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z-_]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}$/', $email)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 *	Print a success message which then automatically redirects the user to another page
	 *
	 *	@param	mixed	A string within the message, or an array with a couple of messages.
	 *	@param	string	A redirect url. Default is "index.php".
	 *	@param	bool	An optional flag to 'print' the footer. Default is true.
	 *
	 */
	public function print_success( $message, $redirect = 'index.php', $auto_footer = true ) {
	    global $TEXT;
	    
	    if (true === is_array( $message ) ) $message = implode("<br />", $message);
	    
	    // add template variables
	    $tpl = new Template( THEME_PATH.'/templates' );
	    $tpl->set_file( 'page', 'success.htt' );
	    $tpl->set_block( 'page', 'main_block', 'main' );
	    $tpl->set_var( 'NEXT', $TEXT['NEXT'] );
	    $tpl->set_var( 'BACK', $TEXT['BACK'] );
 	    $tpl->set_var( 'MESSAGE', $message );
 	    $tpl->set_var( 'THEME_URL', THEME_URL );

	    $tpl->set_block( 'main_block', 'show_redirect_block', 'show_redirect' );
	    $tpl->set_var( 'REDIRECT', $redirect );

	    if (REDIRECT_TIMER == -1)
		{
	        $tpl->set_block( 'show_redirect', '' );
	    } else {
		    $tpl->set_var( 'REDIRECT_TIMER', REDIRECT_TIMER );
	        $tpl->parse( 'show_redirect', 'show_redirect_block', true );
	    }
	    $tpl->parse( 'main', 'main_block', false );
	    $tpl->pparse( 'output', 'page' );
		if ( $auto_footer == true )
		{
			if ( method_exists($this, "print_footer") )
			{
				$this->print_footer();
			}
		}
		exit();
	}

	/**
	 *	Print an error message
	 *
	 *	@param	mixed	A string or an array within the error messages.
	 *	@param	string	A redirect url. Default is "index.php".
	 *	@param	bool	An optional boolean to 'print' the footer. Default is true;
	 *
	 */
	public function print_error( $message, $link = 'index.php', $auto_footer = true ) {
		global $TEXT;

	    if (true === is_array( $message ) ) $message = implode("<br />", $message);
		
		$success_template = new Template(THEME_PATH.'/templates');
		$success_template->set_file('page', 'error.htt');
		$success_template->set_block('page', 'main_block', 'main');
		$success_template->set_var('MESSAGE', $message);
		$success_template->set_var('LINK', $link);
		$success_template->set_var('BACK', $TEXT['BACK']);
 	    $success_template->set_var( 'THEME_URL', THEME_URL );
		$success_template->parse('main', 'main_block', false);
		$success_template->pparse('output', 'page');
		if ( $auto_footer == true ) {
			if ( method_exists($this, "print_footer") ) {
				$this->print_footer();
			}
		}
		exit();
	}

	// Validate send email
	public function mail($fromaddress, $toaddress, $subject, $message, $fromname='') {
		/* 
			INTEGRATED OPEN SOURCE PHPMAILER CLASS FOR SMTP SUPPORT AND MORE
			SOME SERVICE PROVIDERS DO NOT SUPPORT SENDING MAIL VIA PHP AS IT DOES NOT PROVIDE SMTP AUTHENTICATION
			NEW WBMAILER CLASS IS ABLE TO SEND OUT MESSAGES USING SMTP WHICH RESOLVE THESE ISSUE (C. Sommer)

			NOTE:
			To use SMTP for sending out mails, you have to specify the SMTP host of your domain
			via the Settings panel in the backend of Website Baker
		*/ 

		$fromaddress = preg_replace('/[\r\n]/', '', $fromaddress);
		$toaddress = preg_replace('/[\r\n]/', '', $toaddress);
		$subject = preg_replace('/[\r\n]/', '', $subject);
		$message = preg_replace('/\r\n?|\n/', '<br \>', $message);
		
		// create PHPMailer object and define default settings
		$myMail = new wbmailer();

		// set user defined from address
		if ($fromaddress!='') {
			if($fromname!='') $myMail->FromName = $fromname;         // FROM-NAME
			$myMail->From = $fromaddress;                            // FROM:
			$myMail->AddReplyTo($fromaddress);                       // REPLY TO:
		}
		
		// define recepient and information to send out
		$myMail->AddAddress($toaddress);            // TO:
		$myMail->Subject = $subject;                // SUBJECT
		$myMail->Body = $message;                   // CONTENT (HTML)
		$myMail->AltBody = strip_tags($message);		// CONTENT (TEXT)
		
		// check if there are any send mail errors, otherwise say successful
		if (!$myMail->Send()) {
			return false;
		} else {
			return true;
		}
	}

}
?>