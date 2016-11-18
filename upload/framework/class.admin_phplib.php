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
 * @reformatted 2013-05-30
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

// Include template parser 
if (file_exists(LEPTON_PATH . '/templates/' . DEFAULT_THEME . '/backend/index.php'))
{
    require_once(LEPTON_PATH . '/templates/' . DEFAULT_THEME . '/backend/index.php');
}
require_once(LEPTON_PATH . '/include/phplib/template.inc');

// Get version
require_once(ADMIN_PATH . '/interface/version.php');

// Include EditArea wrapper functions
require_once(LEPTON_PATH . '/modules/edit_area/register.php');

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
     *	Privat function to build a HTML tag for links.
     *
     *	@param	str	The path to the file. Normaly absolute.
     *	@param	str	The type of the link, css or (java-)script.
     *	@return	str	The generated HTML code.
     *
     */
    private function __admin_build_link($aPath, $aType = "css")
    {
        
        $s = LEPTON_URL . $aPath;
        
        switch (strtolower($aType))
        {
            
            case "css":
                $s = "<link href=\"" . $s . "\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\" />";
                break;
            
            case "js":
                $s = "<script src=\"" . $s . "\" type=\"text/javascript\"></script>";
                break;
        }
        
        return $s;
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
        
        // Connect to database and get website title
        $title           = $this->db_handle->get_one("SELECT `value` FROM `" . TABLE_PREFIX . "settings` WHERE `name`='website_title'");
        $header_template = new Template(THEME_PATH . '/templates');
        $header_template->set_file('page', 'header.htt');
        $header_template->set_block('page', 'header_block', 'header');
        
        $charset = (true === defined('DEFAULT_CHARSET')) ? DEFAULT_CHARSET : 'utf-8';
        
        // work out the URL for the 'View menu' link in the WB backend
        // if the page_id is set, show this page otherwise show the root directory of WB
        $view_url = LEPTON_URL;
        if (isset($_GET['page_id']))
        {
            // extract page link from the database
            $result = $this->db_handle->query("SELECT `link` FROM `" . TABLE_PREFIX . "pages` WHERE `page_id`= '" . (int) addslashes($_GET['page_id']) . "'");
            $row    = $result->fetchRow();
            if ($row)
                $view_url .= PAGES_DIRECTORY . $row['link'] . PAGE_EXTENSION;
        }
        
        /**
         *	Get the current version of the backend-theme from the database
         */
        $backend_theme_version = "";
        if (defined('DEFAULT_THEME'))
        {
            $backend_theme_version = $this->db_handle->get_one("SELECT `version` from `" . TABLE_PREFIX . "addons` where `directory`='" . DEFAULT_THEME . "'");
        }
        
        $header_template->set_var(array(
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
			'MENU' => $TEXT['MENU'],
// end additional marks				
            'URL_VIEW' => $view_url,
            'URL_HELP' => ' https://www.lepton-cms.org/',
            'BACKEND_MODULE_FILES' => get_page_headers('backend', false),
            'THEME_VERSION' => $backend_theme_version,
            'THEME_NAME' => DEFAULT_THEME
        ));
        
        // Create the menu
        $menu = array(
            array(
                ADMIN_URL . '/pages/index.php',
                '',
                $MENU['PAGES'],
                'pages',
                1
            ),
            array(
                ADMIN_URL . '/media/index.php',
                '',
                $MENU['MEDIA'],
                'media',
                1
            ),
            array(
                ADMIN_URL . '/addons/index.php',
                '',
                $MENU['ADDONS'],
                'addons',
                1
            ),
            array(
                ADMIN_URL . '/preferences/index.php',
                '',
                $MENU['PREFERENCES'],
                'preferences',
                0
            ),
            array(
                ADMIN_URL . '/settings/index.php',
                '',
                $MENU['SETTINGS'],
                'settings',
                1
            ),
            array(
                ADMIN_URL . '/admintools/index.php',
                '',
                $MENU['ADMINTOOLS'],
                'admintools',
                1
            ),
            array(
                ADMIN_URL . '/access/index.php',
                '',
                $MENU['ACCESS'],
                'access',
                1
            )
        );
        if ((true === defined("LEPTON_SERVICE_ACTIVE")) && (1 == LEPTON_SERVICE_ACTIVE))
        {
            $menu[] = array(
                ADMIN_URL . '/service/index.php',
                '',
                $MENU['SERVICE'],
                'service',
                1
            );
        }
        $header_template->set_block('header_block', 'linkBlock', 'link');
        foreach ($menu AS $menu_item)
        {
            $link             = $menu_item[0];
            $target           = ($menu_item[1] == '') ? '_self' : $menu_item[1];
            $title            = $menu_item[2];
            $permission_title = $menu_item[3];
            $required         = $menu_item[4];
            $replace_old      = array(
                ADMIN_URL,
                LEPTON_URL,
                '/',
                'index.php'
            );
            if ($required == false OR $this->get_link_permission($permission_title))
            {
                $header_template->set_var('LINK', $link);
                $header_template->set_var('TARGET', $target);
                // If link is the current section apply a class name
                if ($permission_title == strtolower($this->section_name))
                {
                    $header_template->set_var('CLASS', $menu_item[3] . ' current');
                }
                else
                {
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
    
    /**
     *	Print the admin backend footer
     *
     */
    public function print_footer()
    {
        $footer_template = new Template(THEME_PATH . '/templates');
        $footer_template->set_file('page', 'footer.htt');
        $footer_template->set_block('page', 'footer_block', 'header');
        $footer_template->set_var(array(
            'BACKEND_BODY_MODULE_JS' => get_page_footers('backend'),
            'LEPTON_URL' => LEPTON_URL,
            'LEPTON_PATH' => LEPTON_PATH,
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
        $tpl = new Template(THEME_PATH . '/templates');
        $tpl->set_file('page', 'success.htt');
        $tpl->set_block('page', 'main_block', 'main');
        $tpl->set_var('NEXT', $TEXT['NEXT']);
        $tpl->set_var('BACK', $TEXT['BACK']);
        $tpl->set_var('MESSAGE', $message);
        $tpl->set_var('THEME_URL', THEME_URL);
        
        $tpl->set_block('main_block', 'show_redirect_block', 'show_redirect');
        $tpl->set_var('REDIRECT', $redirect);
        
        if (REDIRECT_TIMER == -1)
        {
            $tpl->set_block('show_redirect', '');
        }
        else
        {
            $tpl->set_var('REDIRECT_TIMER', REDIRECT_TIMER);
            $tpl->parse('show_redirect', 'show_redirect_block', true);
        }
        $tpl->parse('main', 'main_block', false);
        $tpl->pparse('output', 'page');
        
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
        
        $success_template = new Template(THEME_PATH . '/templates');
        $success_template->set_file('page', 'error.htt');
        $success_template->set_block('page', 'main_block', 'main');
        $success_template->set_var('MESSAGE', $message);
        $success_template->set_var('LINK', $link);
        $success_template->set_var('BACK', $TEXT['BACK']);
        $success_template->set_var('THEME_URL', THEME_URL);
        $success_template->parse('main', 'main_block', false);
        $success_template->pparse('output', 'page');
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