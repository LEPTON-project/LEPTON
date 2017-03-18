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
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * 
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH'))
{
    include(LEPTON_PATH . '/framework/class.secure.php');
}
else
{
    $oneback = "../";
    $root    = $oneback;
    $level   = 1;
    while (($level < 10) && (!file_exists($root . '/framework/class.secure.php')))
    {
        $root .= $oneback;
        $level += 1;
    }
    if (file_exists($root . '/framework/class.secure.php'))
    {
        include($root . '/framework/class.secure.php');
    }
    else
    {
        trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
    }
}
// end include class.secure.php

require_once(LEPTON_PATH . '/framework/class.wb.php');

//	At this time required by backend/start
//require_once(LEPTON_PATH . '/include/phplib/template.inc');

// Get version
require_once(ADMIN_PATH . '/interface/version.php');

class admin extends wb
{
    
    /**
     *	The db-handle of this class.
     *
     *	@access	private
     *
     */
    private $db_handle = NULL;
    
    /**
     *	Public header storrage for external/internal files of the used modules.
     *
     *	@access	public
     *
     */
    public $header_storrage = array('css' => array(), 'js' => array(), 'html' => array(), 'modules' => array());
    
    /**
     *	Output storage, needed e.g. inside method print_footer for the leptoken-hashes and/or droplets.
     *
     *	@access	private
     *
     */
    private $html_output_storage = "";
    
    /**
     *	Private flag for the droplets.
     *
     *	@access	private
     *
     */
    private $droplets_ok = false;
    
    /**
     *	The template-engine
     *
     */
    public $parser = NULL;
    public $loader = NULL;
    
    /**
     *	Constructor of the class
     *
     *	Authenticate user then auto print the header
     *
     *	@param	str		The section name.
     *	@param	str		The section permissions belongs too.
     *	@param	bool	Boolean to print out the header. Default is 'true'.
     *	@param	bool	Boolean for the auto authentification. Default is 'true'.
     *
     */
    public function __construct($section_name, $section_permission = 'start', $auto_header = true, $auto_auth = true)
    {
        global $database;
        global $MESSAGE;
        
        parent::__construct();
        
        /**	*********************
         *	TWIG Template Engine
         */
        global $TEXT;
		global $MENU;
		global $OVERVIEW;
		global $HEADING;
		
		lib_twig::register();
		
		$this->loader = new Twig_Loader_Filesystem( LEPTON_PATH.'/' );
		$this->loader->prependPath( THEME_PATH."/templates/", "theme" );	// namespace for the Twig-Loader is "theme"
		
		$this->parser = new Twig_Environment( $this->loader, array(
			'cache' => false,
			'debug' => true
		) );

		$this->parser->addGlobal("TEXT", $TEXT);
		$this->parser->addGlobal("MENU", $MENU);
		$this->parser->addGlobal("OVERVIEW", $OVERVIEW);
		$this->parser->addGlobal("HEADING", $HEADING);
		
		/**	********
		 *	End Twig
		 */
        
        /**
         *	Droplet support
         *
         */
        ob_start();
        
        $this->db_handle = clone ($database);
        
        // Specify the current applications name
        $this->section_name       = $section_name;
        $this->section_permission = $section_permission;
        // Authenticate the user for this application
        if ($auto_auth == true)
        {
            // First check if the user is logged-in
            if ($this->is_authenticated() == false)
            {
                header('Location: ' . ADMIN_URL . '/login/index.php');
                exit(0);
            }
            
            // Now check whether he has a valid token
            if (!$this->checkToken())
            {
                unset($_SESSION['USER_ID']);
                header('Location: ' . ADMIN_URL . '/login/index.php');
                exit(0);
            }
            
            // Now check if they are allowed in this section
            if ($this->get_permission($section_permission) == false)
            {
                die($MESSAGE['ADMIN_INSUFFICIENT_PRIVELLIGES']);
            }
        }
        
        // Check if the backend language is also the selected language. If not, send headers again.
        $user_language = array();
        $this->db_handle->execute_query(
        	"SELECT `language` FROM `" . TABLE_PREFIX . "users` WHERE `user_id` = '" . (int) $this->get_user_id() . "'",
        	true,
        	$user_language,
        	false
        );	
		// prevent infinite loop if language file is not XX.php (e.g. DE_du.php)
        $user_language     = (!isset($user_language['language'])) ? "" : substr($user_language['language'], 0,2);
        
        // obtain the admin folder (e.g. /admin)
        $admin_folder      = str_replace(LEPTON_PATH, '', ADMIN_PATH);
        if ((LANGUAGE != $user_language) && file_exists(LEPTON_PATH . '/languages/' . $user_language . '.php') && strpos($_SERVER['SCRIPT_NAME'], $admin_folder . '/') !== false)
        {
            // check if page_id is set
            $page_id_url    = (isset($_GET['page_id'])) ? '&page_id=' . (int) $_GET['page_id'] : '';
            $section_id_url = (isset($_GET['section_id'])) ? '&section_id=' . (int) $_GET['section_id'] : '';
            if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '') // check if there is an query-string
            {
                header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?lang=' . $user_language . $page_id_url . $section_id_url . '&' . $_SERVER['QUERY_STRING']);
            }
            else
            {
                header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?lang=' . $user_language . $page_id_url . $section_id_url);
            }
            exit();
        }
        
        // Auto header code
        if ($auto_header == true)
        {
            $this->print_header();
        }
    }
    
    /**
     *	Return a system permission
     *
     *	@param	str	A name.
     *	@param	str	A type - default is 'system'
     *
     */
    public function get_permission($name, $type = 'system')
    {
        // Append to permission type
        $type .= '_permissions';
        // Check if we have a section to check for
        if ($name == 'start')
        {
            return true;
        }
        else
        {
            // Set system permissions var
            $system_permissions   = $this->get_session('SYSTEM_PERMISSIONS');
            // Set module permissions var
            $module_permissions   = $this->get_session('MODULE_PERMISSIONS');
            // Set template permissions var
            $template_permissions = $this->get_session('TEMPLATE_PERMISSIONS');
            // Return true if system perm = 1
            if (isset($$type) && is_array($$type) && is_numeric(array_search($name, $$type)))
            {
                if ($type == 'system_permissions')
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                if ($type == 'system_permissions')
                {
                    return false;
                }
                else
                {
                    return true;
                }
            }
        }
    }
    
    public function get_user_details($user_id)
    {
    	$user = array();
    	$this->db_handle->execute_query(
    		"SELECT `username`,`display_name` FROM `" . TABLE_PREFIX . "users` WHERE `user_id` = '".$user_id."'",
    		true,
    		$user,
    		false
    	);
		if (count($user) == 0)
        {
            $user['display_name'] = 'Unknown';
            $user['username']     = 'unknown';
        }
        return $user;
    }
    
    public function get_page_details(&$page_id)
    {
    	$results_array = array();
    	$fields = array(
    		'page_id', 'page_title', 'menu_title' , 'modified_by' , 'modified_when'
    	);
    	$query = $this->db_handle->build_mysql_query(
    		'select',
    		TABLE_PREFIX."pages",
    		$fields,
    		"page_id = '".$page_id."'"
    	);
        
        $this->db_handle->execute_query( $query, true, $results_array, false );
        
        if ($this->db_handle->is_error())
        {
            $this->print_header();
            $this->print_error($this->db_handle->get_error());
        }
        if (count($results_array) == 0)
        {
            $this->print_header();
            $this->print_error($MESSAGE['PAGES_NOT_FOUND']);
        }
        return $results_array;
    }
    
    /** 
     *	Function get_page_permission takes either a numerical page_id,
     *	upon which it looks up the permissions in the database,
     *	or an array with keys admin_groups and admin_users  
     */
    public function get_page_permission($page, $action = 'admin')
    {
        if ($action != 'viewing')
            $action = 'admin';
        $action_groups = $action . '_groups';
        $action_users  = $action . '_users';
        if (is_array($page))
        {
            $groups = $page[$action_groups];
            $users  = $page[$action_users];
        }
        else
        {
            $results = $this->db_handle->query("SELECT $action_groups,$action_users FROM " . TABLE_PREFIX . "pages WHERE page_id = '$page'");
            $result  = $results->fetchRow();
            $groups  = explode(',', str_replace('_', '', $result[$action_groups]));
            $users   = explode(',', str_replace('_', '', $result[$action_users]));
        }
        
        $in_group = FALSE;
        foreach ($this->get_groups_id() as $cur_gid)
        {
            if (in_array($cur_gid, $groups))
            {
                $in_group = TRUE;
            }
        }
        if ((!$in_group) AND !is_numeric(array_search($this->get_user_id(), $users)))
        {
            return false;
        }
        return true;
    }
    
    /**
     *	Returns a system permission for a menu link
     *
     */
    public function get_link_permission($title)
    {
        $title              = str_replace('_blank', '', $title);
        $title              = strtolower($title);
        // Set system permissions var
        $system_permissions = $this->get_session('SYSTEM_PERMISSIONS');
        // Set module permissions var
        $module_permissions = $this->get_session('MODULE_PERMISSIONS');
        if ($title == 'start')
        {
            return true;
        }
        else
        {
            // Return true if system perm = 1
            if (is_numeric(array_search($title, $system_permissions)))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
    
    /**
     *	Print the admin header
     *
     */
    
    public function print_header()
    {
        // Get vars from the language file
        global $MENU;
        global $MESSAGE;
        global $TEXT;
        
        // Get website title
        $title = $this->db_handle->get_one("SELECT `value` FROM `" . TABLE_PREFIX . "settings` WHERE `name`='website_title'");
        
        $charset = (true === defined('DEFAULT_CHARSET')) ? DEFAULT_CHARSET : 'utf-8';
        
        // Work out the URL for the 'View menu' link in the WB backend
        // if the page_id is set, show this page otherwise show the root directory of WB
        $view_url = LEPTON_URL;
        if (isset($_GET['page_id']))
        {
            // Extract page link from the database
            $result = $this->db_handle->query("SELECT `link` FROM `" . TABLE_PREFIX . "pages` WHERE `page_id`= '" . (int) addslashes($_GET['page_id']) . "'");
            $row    = $result->fetchRow();
            if ($row)
                $view_url .= PAGES_DIRECTORY . $row['link'] . PAGE_EXTENSION;
        }
        
        /**
         *	Try to get the current version of the backend-theme from the database
         *
         */
        $backend_theme_version = "";
        if (defined('DEFAULT_THEME'))
        {
            $backend_theme_version = $this->db_handle->get_one("SELECT `version` from `" . TABLE_PREFIX . "addons` where `directory`='" . DEFAULT_THEME . "'");
        }
 
 		$header_vars = array(
            'SECTION_NAME' => $MENU[strtoupper($this->section_name)],
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
            'LEPTON_URL' => LEPTON_URL,
            'ADMIN_URL' => ADMIN_URL,
            'THEME_URL' => THEME_URL,
            'TITLE_START' => $MENU['START'],
            'TITLE_VIEW' => $MENU['VIEW'],
            'TITLE_HELP' => $MENU['HELP'],
            'TITLE_LOGOUT' => $MENU['LOGOUT'],
// additional marker links/text in semantic BE-header		
			'PAGES' => $MENU['PAGES'],
			'MEDIA'	 => $MENU['MEDIA'],
			'ADDONS' => $MENU['ADDONS'],
			'PREFERENCES' => $MENU['PREFERENCES'],
			'SETTINGS' => $MENU['SETTINGS'],
			'ADMINTOOLS' => $MENU['ADMINTOOLS'],
			'ACCESS' => $MENU['ACCESS'],
// end additional marks				
            'URL_VIEW' => $view_url,
            'URL_HELP' => ' https://www.lepton-cms.org/',
            'BACKEND_MODULE_FILES' => get_page_headers('backend', false),
            'THEME_VERSION' => $backend_theme_version,
            'THEME_NAME' => DEFAULT_THEME,
			
			//	permissions
			'p_pages'	=> $this->get_link_permission('pages'),
			'p_media'	=> $this->get_link_permission('media'),
			'p_addons'	=> $this->get_link_permission('addons'),
			'p_preferences' => true, // Keep in mind: preferences are always 'shown' as managed from the login of the user.
			'p_settings'	=> $this->get_link_permission('settings'),
			'p_admintools'	=> $this->get_link_permission('admintools'),
			'p_access'		=> $this->get_link_permission('access')			
		);

        echo $this->parser->render(
        	'@theme/header.lte',
        	$header_vars
        );
    }
    
    /**
     *	Print the admin backend footer
     *
     */
    public function print_footer()
    {
        $footer_vars = array(
            'BACKEND_BODY_MODULE_JS' => get_page_footers('backend'),
            'LEPTON_URL' => LEPTON_URL,
            'LEPTON_PATH' => LEPTON_PATH,
            'ADMIN_URL' => ADMIN_URL,
            'THEME_URL' => THEME_URL
        );
        
        echo $this->parser->render(
        	"@theme/footer.lte",
        	$footer_vars
        );
        
        /**
         *	Droplet support
         *
         */
        $this->html_output_storage = ob_get_clean();
        if (true === $this->droplets_ok)
        {
            $this->html_output_storage = evalDroplets($this->html_output_storage);
        }
        
        // CSRF protection - add tokens to internal links
        if ($this->is_authenticated())
        {
            if (file_exists(LEPTON_PATH . '/framework/functions/function.addTokens.php'))
            {
                include_once(LEPTON_PATH . '/framework/functions/function.addTokens.php');
                if (function_exists('addTokens'))
                    addTokens($this->html_output_storage, $this);
            }
        }
        
        echo $this->html_output_storage;
    }
    
    /**
     *	Print a success message which then automatically redirects the user to another page
     *
     *	@param	mixed	A string within the message, or an array with a couple of messages.
     *	@param	string	A redirect url. Default is "index.php".
     *	@param	bool	An optional flag to 'print' the footer. Default is true.
     *
     */
    public function print_success($message, $redirect = 'index.php', $auto_footer = true)
    {
        global $TEXT;
        
        if (true === is_array($message))
            $message = implode("<br />", $message);
        
        // add template variables
        $page_vars = array(
        	'NEXT' => $TEXT['NEXT'],
        	'BACK' => $TEXT['BACK'],
        	'MESSAGE' => $message,
        	'THEME_URL' => THEME_URL,
        	'REDIRECT' => $redirect,
        	'REDIRECT_TIMER' => REDIRECT_TIMER
        	
        );
        
        echo $this->parser->render(
        	'@theme/success.lte',
        	$page_vars
        );
        
        if ($auto_footer == true)
            $this->print_footer();
        
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
    public function print_error($message, $link = 'index.php', $auto_footer = true)
    {
        global $TEXT;
        
        if (true === is_array($message))
            $message = implode("<br />", $message);
        
        $page_vars = array(
        	'MESSAGE' => $message,
        	'LINK'	=> $link,
        	'BACK'	=> $TEXT['BACK'],
        	'THEME_URL' => THEME_URL
        );
        
        echo $this->parser->render(
        	'@theme/error.lte',
        	$page_vars
        );

        if ($auto_footer == true)
        {
            if (method_exists($this, "print_footer"))
            {
                $this->print_footer();
            }
        }
        exit();
    }
}
?>