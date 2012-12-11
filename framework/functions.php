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
 * @copyright       2010-2012 LEPTON Project
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

/**
 *  Define that this file has been loaded
 *
 *  To avoid double function-declarations (inside LEPTON) and to avoid a massiv use
 *  of "if(!function_exists('any_function_name_here_since_wb_2.5.0')) {" we've to place it
 *  inside this condition-body!
 *
 */
if (!defined('LEPTON_PATH')) define( 'LEPTON_PATH', WB_PATH ); 
if (!defined('FUNCTIONS_FILE_LOADED'))
{
    define('FUNCTIONS_FILE_LOADED', true);
	if (!defined('LEPTON_PATH')) require_once( dirname(__FILE__)."/../config.php");
    if (!defined('WB_PATH')) define( 'WB_PATH', LEPTON_PATH );
    // global array to catch header files
    $HEADERS = array(
        'frontend' => array(
            'css'    => array(),
            'meta'   => array(),
            'js'     => array(),
            'jquery' => array(),
        ),
        'backend' => array(
            'css'    => array(),
            'meta'   => array(),
            'js'     => array(),
            'jquery' => array(),
        ),
    );
    
    $FOOTERS = array(
        'frontend' => array(
            'script' => array(),
            'js'     => array(),
        ),
        'backend' => array(
            'script' => array(),
            'js'     => array(),
        ),
    );
    
    // set debug level here; see LEPTON_Helper_KLogger for available levels
    // 7 = debug, 8 = off
  	//$debug_level  = 8;

    // include helpers
	global $lhd, $array;
    include LEPTON_PATH . '/framework/lepton/helper/directory.php';
    include LEPTON_PATH . '/framework/lepton/helper/array.php';
	$lhd   = new LEPTON_Helper_Directory();
	$array = new LEPTON_Helper_Array();
    
    /**
     *  Function to remove a non-empty directory
     *  The function was moved to Directory helper class
     *  
     *  @param string $directory
     *  @return boolean
     */
    function rm_full_dir($directory) {
        global $lhd;
        return $lhd->removeDirectory($directory);
    }   // end function rm_full_dir()
    
    /**
     *    This function returns a recursive list of all subdirectories from a given directory
     *
     *    @access  public
     *    @param   string  $directory: from this dir the recursion will start.
     *    @param   bool    $show_hidden (optional): if set to TRUE also hidden dirs (.dir) will be shown.
     *    @param   int     $recursion_deep (optional): An optional integer to test the recursions-deep at all.
     *    @param   array   $aList (optional): A simple storage list for the recursion.
     *    @param   string  $ignore (optional): This is the part of the "path" to be "ignored"
     *
     *    @return  array
     *
     *    example:
     *        /srv/www/httpdocs/wb/media/a/b/c/
     *        /srv/www/httpdocs/wb/media/a/b/d/
     *
     *        if &ignore is set - directory_list('/srv/www/httpdocs/wb/media/') will return:
     *        /a
     *        /a/b
     *        /a/b/c
     *        /a/b/d
     *
     *
     */
    function directory_list($directory, $show_hidden = false, $recursion_deep = 0, &$aList = null, &$ignore = "")
    {
        if ($aList == null)
        {
            $aList = array();
        }
        //# if ($recursion_deep == 0) $ignore= $directory;
        if (is_dir($directory))
        {
            // Open the directory
			$dir = dir($directory);
			if ($dir != NULL)
            {
				while (false !== ($entry = $dir->read())) 
                {
                    // loop through the directory
                    // Skip pointers
                    if ($entry == '.' || $entry == '..')
                    {
                        continue;
                    }
                    // Skip hidden files
                    if ($entry[0] == '.' && $show_hidden == false)
                    {
                        continue;
                    }
                    $temp_dir = $directory . "/" . $entry;
                    if (is_dir($temp_dir))
                    {
                        // Add dir and contents to list
                        $aList[] = str_replace($ignore, "", $temp_dir);
                        $temp_result = directory_list($temp_dir, $show_hidden, $recursion_deep + 1, $aList, $ignore);
                    }
                }
                $dir->close();
            }
        }
        if ($recursion_deep == 0)
        {
            natcasesort($aList);
            // Now return the list
            return $aList;
        }
    }   // end function directory_list()
        
    /**
     * Scan a given directory for dirs and files.
     *
     * usage: scan_current_dir ($root = '' )
     *
     * Used by admins/reload.php, for example
     *
     * @access    public
     * @param     $root    (optional) path to be scanned; defaults to current working directory (getcwd())
     * @return    array    returns a natsort-ed array with keys 'path' and 'filename'
     *
     */
    function scan_current_dir($root = '')
    {
        $FILE = array();
        clearstatcache();
        $root = empty($root) ? getcwd() : $root;
        if (false !== ($handle = opendir($root)))
        {
            // Loop through the files and dirs an add to list  DIRECTORY_SEPARATOR
            while (false !== ($file = readdir($handle)))
            {
                if (substr($file, 0, 1) != '.' && $file != 'index.php')
                {
                    if (is_dir($root . '/' . $file))
                    {
                        $FILE['path'][] = $file;
                    }
                    else
                    {
                        $FILE['filename'][] = $file;
                    }
                }
            }
            $close_verz = closedir($handle);
        }
        if (isset($FILE['path']) && natcasesort($FILE['path']))
        {
            $tmp = array();
            $FILE['path'] = array_merge($tmp, $FILE['path']);
        }
        if (isset($FILE['filename']) && natcasesort($FILE['filename']))
        {
            $tmp = array();
            $FILE['filename'] = array_merge($tmp, $FILE['filename']);
        }
        return $FILE;
    }   // end function scan_current_dir()
    
    /**
     *  Function to list all files in a given directory.
     *
     *  @param  string  $directory   - directory to list
     *  @param  array   $skip        - An array with directories to skip, e.g. '.svn' or '.git'
     *  @param  bool    $show_hidden - Show also hidden files, e.g. ".htaccess".
     *
     *  @retrun  array  Natsorted array within the files.
     *
     */
    function file_list($directory, $skip = array(), $show_hidden = false)
    {
        $result_list = array();
        if (is_dir($directory))
        {
            $use_skip = (count($skip) > 0);
            // Open the directory
            $dir = dir($directory);
            while (false !== ($entry = $dir->read()))
            {
                // loop through the directory
                // Skip hidden files
                if (($entry[0] == '.') && (false == $show_hidden))
                {
                    continue;
                }
                // Check if we to skip anything else
                if ((true === $use_skip) && (in_array($entry, $skip)))
                {
                    continue;
                }
                if (is_file($directory . '/' . $entry))
                {
                    // Add files to list
                    $result_list[] = $directory . '/' . $entry;
                }
            }
            // closing the folder-object
            $dir->close();
        }
        natcasesort($result_list);
        return $result_list;
    }   // end function file_list()

    // Function to get a list of home folders not to show
    /**
     *  M.f.i.!  Dietrich Roland Pehlke
     *      I would like to keep the original comment unless i understand this one!
     *      E.g. 'ami' is for me nothing more and nothing less than an 'admim'!
     *
     *      I'm also not acceppt the declaration of a function inside a function at all!
     *      E.g. what happend if the function "get_home_folders" twice? Bang!
     *
     * 2011-08-22
     *      Bianka Martinovic
     *      The only file where this is used seems to be admins/media/index.php,
     *      so in my opinion, it should be moved there
     *
     */
    function get_home_folders()
    {
        global $database, $admin;
        $home_folders = array();
        // Only return home folders is this feature is enabled
        // and user is not admin
        //if(HOME_FOLDERS AND ($_SESSION['GROUP_ID']!='1'))
        if (HOME_FOLDERS && (!$admin->ami_group_member('1')))
        {
            $sql = 'SELECT `home_folder` FROM `' . TABLE_PREFIX . 'users` WHERE `home_folder` != \'' . $admin->get_home_folder() . '\'';
            $query_home_folders = $database->query($sql);
            if ($query_home_folders->numRows() > 0)
            {
                while (false !== ($folder = $query_home_folders->fetchRow()))
                {
                    $home_folders[$folder['home_folder']] = $folder['home_folder'];
                }
            }
            function remove_home_subs($directory = '/', $home_folders = '')
            {
                if (false !== ($handle = opendir(LEPTON_PATH . MEDIA_DIRECTORY . $directory)))
                {
                    // Loop through the dirs to check the home folders sub-dirs are not shown
                    while (false !== ($file = readdir($handle)))
                    {
                        if ($file[0] != '.' && $file != 'index.php')
                        {
                            if (is_dir(LEPTON_PATH . MEDIA_DIRECTORY . $directory . '/' . $file))
                            {
                                if ($directory != '/')
                                {
                                    $file = $directory . '/' . $file;
                                }
                                else
                                {
                                    $file = '/' . $file;
                                }
                                foreach ($home_folders as $hf)
                                {
                                    $hf_length = strlen($hf);
                                    if ($hf_length > 0)
                                    {
                                        if (substr($file, 0, $hf_length + 1) == $hf)
                                        {
                                            $home_folders[$file] = $file;
                                        }
                                    }
                                }
                                $home_folders = remove_home_subs($file, $home_folders);
                            }
                        }
                    }
                }
                return $home_folders;
            }
            $home_folders = remove_home_subs('/', $home_folders);
        }
        return $home_folders;
    }   // end function get_home_folders()

    /*
     * @param object &$wb: $wb from frontend or $admin from backend
     * @return array: list of new entries
     * @description: callback remove path in files/dirs stored in array
     * @example: array_walk($array,'remove_path',PATH);
     */
    /**
     *  M.f.o.!  MARKED FOR OBSOLETE
     *      As this one belongs to the results of the function 'directory_list'
     *
     */
    function remove_path(&$path, $key, $vars = '')
    {
        $path = str_replace($vars, '', $path);
    }

    /*
     * @param object &$wb: $wb from frontend or $admin from backend
     * @return array: list of ro-dirs
     * @description: returns a list of directories beyound /wb/media which are ReadOnly for current user
     *
     *  M.f.i.!  Copy and paste crap
     *
     */
    function media_dirs_ro(&$wb)
    {
        /**
    		 * @deprecated media_dirs_ro() is deprecated and will be removed in LEPTON 1.2
    		 */
    		trigger_error('The function media_dirs_ro() is deprecated and will be removed in LEPTON 1.3.', E_USER_NOTICE);
        global $database;
        // if user is admin or home-folders not activated then there are no restrictions
        $allow_list = array();
        if ($wb->get_user_id() == 1 || !HOME_FOLDERS)
        {
            return array();
        }
        // at first read any dir and subdir from /media
        $full_list = directory_list(LEPTON_PATH . MEDIA_DIRECTORY);
        // add own home_folder to allow-list
        if ($wb->get_home_folder())
        {
            // old: $allow_list[] = get_home_folder();
            $allow_list[] = $wb->get_home_folder();
        }
        // get groups of current user
        $curr_groups = $wb->get_groups_id();
        // if current user is in admin-group
        if (($admin_key = array_search('1', $curr_groups)) !== false)
        {
            // remove admin-group from list
            unset($curr_groups[$admin_key]);
            // search for all users where the current user is admin from
            foreach ($curr_groups as $group)
            {
                $sql = 'SELECT `home_folder` FROM `' . TABLE_PREFIX . 'users` ';
                $sql .= 'WHERE (FIND_IN_SET(\'' . $group . '\', `groups_id`) > 0) AND `home_folder` <> \'\' AND `user_id` <> ' . $wb->get_user_id();
                if (($res_hf = $database->query($sql)) != null)
                {
                    while (false !== ($rec_hf = $res_hf->fetchrow(MYSQL_ASSOC)))
                    {
                        $allow_list[] = $rec_hf['home_folder'];
                    }
                }
            }
        }
        $tmp_array = $full_list;
        // create a list for readonly dir
        $array = array();
        while (sizeof($tmp_array) > 0)
        {
            $tmp = array_shift($tmp_array);
            $x = 0;
            while ($x < sizeof($allow_list))
            {
                if (strpos($tmp, $allow_list[$x]))
                {
                    $array[] = $tmp;
                }
                $x++;
            }
        }
        $full_list = array_diff($full_list, $array);
        $tmp = array();
        $full_list = array_merge($tmp, $full_list);
        return $full_list;
    }   // end function media_dirs_ro()

    /*
     * @param object &$wb: $wb from frontend or $admin from backend
     * @return array: list of rw-dirs
     * @description: returns a list of directories beyound /wb/media which are ReadWrite for current user
     *
     *  M.f.i.!  Copy and paste crap!
     *
     *  2011-08-22 Bianka Martinovic
     *      used only in admins/media/index.php, should be moved there
     */
    function media_dirs_rw(&$wb)
    {
        global $database;
        // if user is admin or home-folders not activated then there are no restrictions
        // at first read any dir and subdir from /media
        $full_list = directory_list(LEPTON_PATH . MEDIA_DIRECTORY);
        $allow_list = array();
        if (($wb->get_user_id() == 1) || !HOME_FOLDERS)
        {
            return $full_list;
        }
        // add own home_folder to allow-list
        if ($wb->get_home_folder())
        {
            $allow_list[] = $wb->get_home_folder();
        }
        // get groups of current user
        $curr_groups = $wb->get_groups_id();
        // if current user is in admin-group
        if (($admin_key = array_search('1', $curr_groups)) !== false)
        {
            // remove admin-group from list
            unset($curr_groups[$admin_key]);
            // search for all users where the current user is admin from
            foreach ($curr_groups as $group)
            {
                $sql = 'SELECT `home_folder` FROM `' . TABLE_PREFIX . 'users` ';
                $sql .= 'WHERE (FIND_IN_SET(\'' . $group . '\', `groups_id`) > 0) AND `home_folder` <> \'\' AND `user_id` <> ' . $wb->get_user_id();
                if (($res_hf = $database->query($sql)) != null)
                {
                    while (false !== ($rec_hf = $res_hf->fetchrow()))
                    {
                        $allow_list[] = $rec_hf['home_folder'];
                    }
                }
            }
        }
        $tmp_array = $full_list;
        // create a list for readwrite dir
        $array = array();
        while (sizeof($tmp_array) > 0)
        {
            $tmp = array_shift($tmp_array);
            $x = 0;
            while ($x < sizeof($allow_list))
            {
                if (strpos($tmp, $allow_list[$x]))
                {
                    $array[] = $tmp;
                }
                $x++;
            }
        }
        $tmp = array();
        $full_list = array_merge($tmp, $array);
        return $full_list;
    }   // end function media_dirs_rw()

    /**
     * Create directories recursive
     *
     * @param string   $dir_name - directory to create
     * @param ocatal   $dir_mode - access mode
     * @return boolean result of operation
     *
     * @internal ralf 2011-08-05 - added recursive parameter for mkdir()
     * @todo ralf 2011-08-05     - checking for !is_dir() is not a good idea, perhaps $dirname
     * is not a valid path, i.e. a file - any better ideas?
     */
    function make_dir($dir_name, $dir_mode = OCTAL_DIR_MODE)
    {
        if (!is_dir($dir_name))
        {
            $umask = umask(0);
            mkdir($dir_name, $dir_mode, true);
            umask($umask);
            return true;
        }
        return false;
    }   // end function make_dir()

    /**
     * chmod to octal access mode defined by initialize.php;
     * not used on Windows Systems
     *
     * @access public
     * @param  string   $name - directory or file
     * @return boolean
     *
     **/
    function change_mode($name)
    {
        if (OPERATING_SYSTEM != 'windows')
        {
            // Only chmod if os is not windows
            if (is_dir($name))
            {
                $mode = OCTAL_DIR_MODE;
            }
            else
            {
                $mode = OCTAL_FILE_MODE;
            }
            if (file_exists($name))
            {
                $umask = umask(0);
                chmod($name, $mode);
                umask($umask);
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return true;
        }
    }   // function change_mode()

    /**
     * check if the page with the given id has children
     *
     * @access public
     * @param  integer $page_id - page ID
     * @return mixed   (false if page hasn't children, parent id if not)
     *
     * 2011-08-22 Bianka Martinovic
     *    Should be moved to new page object when ready
     *    I don't understand why this returns the parent page, as methods
     *    beginning with is* should always return boolean only IMHO
     **/
    function is_parent($page_id)
    {
        global $database;
        // Get parent
        $sql = 'SELECT `parent` FROM `' . TABLE_PREFIX . 'pages` WHERE `page_id` = ' . $page_id;
        $parent = $database->get_one($sql);
        // If parent isnt 0 return its ID
        if (is_null($parent))
        {
            return false;
        }
        else
        {
            return $parent;
        }
    }   // end function is_parent()

    /**
     * counts the levels from given page_id to root
     *
     * @access public
     * @param  integer  $page_id
     * @return integer  level (>=0)
     *
     **/
    function level_count($page_id)
    {
        global $database;
        // Get page parent
        $sql = 'SELECT `parent` FROM `' . TABLE_PREFIX . 'pages` WHERE `page_id` = ' . $page_id;
        $parent = $database->get_one($sql);
        if ($parent > 0)
        {
            // Get the level of the parent
            $sql = 'SELECT `level` FROM `' . TABLE_PREFIX . 'pages` WHERE `page_id` = ' . $parent;
            $level = $database->get_one($sql);
            return $level + 1;
        }
        else
        {
            return 0;
        }
    }   // function level_count()

    /**
     * Function to work out root parent
     *
     * @access public
     * @param  integer $page_id
     * @return integer ID of the root page
     *
     **/
    function root_parent($page_id)
    {
        global $database;
        // Get page details
        $sql = 'SELECT `parent`, `level` FROM `' . TABLE_PREFIX . 'pages` WHERE `page_id` = ' . $page_id;
        $query_page = $database->query($sql);
        $fetch_page = $query_page->fetchRow();
        $parent = $fetch_page['parent'];
        $level = $fetch_page['level'];
        if ($level == 1)
        {
            return $parent;
        }
        elseif ($parent == 0)
        {
            return $page_id;
        }
        else
        {
            // Figure out what the root parents id is
            $parent_ids = array_reverse(get_parent_ids($page_id));
            return $parent_ids[0];
        }
    }   // end root_parent()

    /**
     * get additions for page header (css, js, meta)
     *
     * + gets all active sections for a page;
     * + scans module directories for file headers.inc.php;
     * + includes that file if it is available
     * + includes automatically if exists:
     *   + module dirs:
     *     + frontend.css / backend.css              (media: all)
     *     + ./css/frontend.css / backend.css        (media: all)
     *     + frontend_print.css / backend_print.css  (media: print)
     *     + ./css/frontend_print.css / backend_print.css  (media: print)
     *     + frontend.js / backend.js
	 *     + ./js/frontend.js / backend.js
     *   + template dir:
     *     + <PAGE_ID>.css 							 (media: all)
     *     + ./css/<PAGE_ID>.css					 (media: all)
	 *   + pages_directory:
	 *     + <PAGE_ID>.css                           (media: all)
	 *     + ./css/<PAGE_ID>.css                     (media: all)
     *
     * @access public
     * @param  string  $for - 'frontend' (default) / 'backend'
     * @param  boolean $print_output
     * @param  boolean $current_section
     * @return void (echo's result)
     *
     **/
	function get_page_headers( $for = 'frontend', $print_output = true, $individual = false )
	{

		global $HEADERS, $array, $lhd, $logger;
		// don't do this twice
		if (defined('LEP_HEADERS_SENT'))
		{
			return;
		}
		if ( ! $for || $for == '' || ( $for != 'frontend' && $for != 'backend' ) ) {
			$for = 'frontend';
		}
		$page_id = defined( 'PAGE_ID' )
			? PAGE_ID
			: (
				( isset($_GET['page_id']) && is_numeric($_GET['page_id']) )
					? $_GET['page_id']
					: NULL
			);

		// load headers.inc.php for backend theme
		if ( $for == 'backend' )
		{
			if (file_exists(LEPTON_PATH . '/templates/' . DEFAULT_THEME . '/headers.inc.php'))
			{
				__addItems( $for, LEPTON_PATH . '/templates/' . DEFAULT_THEME );
			}
		}
		// load headers.inc.php for backend theme
		else
		{
			if (file_exists(LEPTON_PATH . '/templates/' . DEFAULT_TEMPLATE . '/headers.inc.php'))
			{
				__addItems( $for, LEPTON_PATH . '/templates/' . DEFAULT_TEMPLATE );
			}
		}
		// handle search
        if (($page_id == 0) && ($for == 'frontend')) {
            $caller = debug_backtrace();
            if (isset($caller[2]['file']) && (strpos($caller[2]['file'], DIRECTORY_SEPARATOR.'search'.DIRECTORY_SEPARATOR.'index.php') !== false)) {
                // the page is called from the LEPTON SEARCH
                foreach (array(
                    '/modules/'.SEARCH_LIBRARY.'/templates/custom',
                    '/modules/'.SEARCH_LIBRARY.'/templates/default'
                    ) as $directory) {
                    $file = $lhd->sanitizePath( $directory.'/'.$for.'.css' );
                    if (file_exists(LEPTON_PATH.'/'.$file)) {
                        $HEADERS[$for]['css'][] = array(
                            'media' => 'all',
							'file'  => $file
						);
                        // load only once
                        break;
					}
                }
            }
        }
        
        // load CSS and JS for DropLEPs
        if (($for == 'frontend') && $page_id && is_numeric($page_id)) {
            if (file_exists(LEPTON_PATH.'/modules/lib_lepton/pages_load/library.php')) {
                require_once LEPTON_PATH.'/modules/lib_lepton/pages_load/library.php';
                get_droplep_headers($page_id);
            }
        }
        
        $css_subdirs = array();
        $js_subdirs  = array();
        
        // it's an admin tool...
        if ( $for == 'backend' && isset($_REQUEST['tool']) && file_exists( LEPTON_PATH.'/modules/'.$_REQUEST['tool'].'/tool.php' ) )
        {
            $css_subdirs = array(
		        '/modules/'   . $_REQUEST['tool']          ,
		        '/modules/'   . $_REQUEST['tool']  . '/css',
			);
			$js_subdirs = array(
		        '/modules/'   . $_REQUEST['tool']         ,
		        '/modules/'   . $_REQUEST['tool']  . '/js',
			);
			if (file_exists(LEPTON_PATH . '/modules/' . $_REQUEST['tool'] . '/headers.inc.php'))
			{
				__addItems( $for, LEPTON_PATH . '/modules/' . $_REQUEST['tool'] );
			}
        }
        // if we have a page id...
		elseif ( $page_id && is_numeric($page_id) )
		{
            // ...get active sections
		    if ( ! class_exists( 'LEPTON_Sections' ) )
		    {
		        require_once dirname(__FILE__).'/LEPTON/Sections.php';
			}
			$sec_h    = new LEPTON_Sections();
			$sections = $sec_h->get_active_sections($page_id);
			
			if (count($sections))
			{
				global $current_section;
				global $mod_headers;
				foreach ($sections as $section)
				{
					$module = $section['module'];
					$headers_path = sanitize_path(LEPTON_PATH.'/modules/'. $module);
					// special case: 'wysiwyg'
					if ( $for == 'backend' && ! strcasecmp($module,'wysiwyg') ) {
					    // get the currently used WYSIWYG module
					    if ( defined('WYSIWYG_EDITOR') && WYSIWYG_EDITOR != "none" ) {
                            $headers_path = sanitize_path(LEPTON_PATH.'/modules/'.WYSIWYG_EDITOR);
					    }
					}
					// find header definition file
					if (file_exists($headers_path . '/headers.inc.php'))
					{
						$current_section = $section['section_id'];
						__addItems( $for, $headers_path );
					}
					else {

					}
					$css_subdirs = array(
					        '/modules/'   . $module          ,
					        '/modules/'   . $module  . '/css',
					);
					$js_subdirs = array(
				        '/modules/'   . $module         ,
				        '/modules/'   . $module  . '/js',
					);
				}   // foreach ($sections as $section)
			}       // if (count($sections))
			
			// add css/js search subdirs for frontend only; page based CSS/JS
			// does not make sense in BE
			if ( $for == 'frontend' )
			{
				array_push(
					$css_subdirs,
			        PAGES_DIRECTORY,
					PAGES_DIRECTORY . '/css'
				);
				array_push(
					$js_subdirs,
			        PAGES_DIRECTORY,
			        PAGES_DIRECTORY . '/js'
				);
			}

		}           // if ( $page_id )

		// add template css
		// note: defined() is just to avoid warnings, the NULL does not really
		// make sense!
		$subdir = ( $for == 'backend' )
				? ( defined('DEFAULT_THEME') ? DEFAULT_THEME : NULL )
				: ( defined('TEMPLATE')      ? TEMPLATE      : NULL )
				;
				
		array_push(
			$css_subdirs,
			'/templates/' . $subdir,
	        '/templates/' . $subdir . '/css'
		);
		array_push(
			$js_subdirs,
			'/templates/' . $subdir,
	        '/templates/' . $subdir . '/js'
							);
					
		// automatically add CSS files
		foreach( $css_subdirs as $directory )
		{
			// frontend.css / backend.css
			$file = $lhd->sanitizePath( $directory.'/'.$for.'.css' );
			if ( file_exists(LEPTON_PATH.'/'.$file) )
			{
				$HEADERS[$for]['css'][] = array(
					'media' => 'all',
					'file'  => $file
				);
			}
			// frontend_print.css / backend_print.css
		    $file = $lhd->sanitizePath( $directory.'/'.$for.'_print.css' );
						    if ( file_exists(LEPTON_PATH.'/'.$file) ) {
						        $HEADERS[$for]['css'][] = array(
									'media' => 'print',
									'file'  => $file
								);
						    }
						}
		// automatically add JS files
		foreach( $js_subdirs as $directory ) {
			$file = $lhd->sanitizePath( $directory.'/'.$for.'.js' );
						if ( file_exists(LEPTON_PATH.'/'.$file) ) {
							$HEADERS[$for]['js'][] = $file;
						}
					}

			$output = null;
			foreach ( array( 'meta', 'css', 'jquery', 'js' ) as $key )
			{
				if ( ! isset($HEADERS[$for][$key]) || ! is_array($HEADERS[$for][$key]) )
				{
					continue;
				}
				// make array unique

				$HEADERS[$for][$key] = $array->ArrayUniqueRecursive($HEADERS[$for][$key]);
				foreach ($HEADERS[$for][$key] as $i => $arr)
				{
					switch ($key)
					{
						case 'meta':
							if (is_array($arr))
							{
								foreach ($arr as $item)
								{
									$output .= $item . "\n";
								}
							}
							break;
						case 'css':
							// make sure we have an URI (LEPTON_URL included)
							$file	= (preg_match('#' . LEPTON_URL . '#i', $arr['file'])
									? $arr['file']
									: LEPTON_URL . '/' . $arr['file']);
							$output .= '<link rel="stylesheet" type="text/css" href="' . sanitize_url($file) . '" media="' . (isset($arr['media']) ? $arr['media'] : 'all') . '" />' . "\n";
							break;
						case 'jquery':
							// make sure that we load the core if needed, even if the
							// author forgot to set the flags
							if (
								( isset($arr['ui']) && $arr['ui'] === true )
								|| ( isset($arr['ui-effects']) && is_array($arr['ui-effects']) )
								|| ( isset($arr['ui-components']) && is_array($arr['ui-components']) )
							) {
								$arr['core'] = true;
							}
							// make sure we load the ui core if needed
							if ( isset($arr['ui-components']) && is_array($arr['ui-components'])
								|| ( isset($arr['ui-effects']) && is_array($arr['ui-effects']) )
							) {
								$arr['ui'] = true;
							}
							if ( isset($arr['ui-effects']) && is_array($arr['ui-effects']) && ( !in_array( 'core' , $arr['ui-effects'] ) ) )
							{
								array_unshift( $arr['ui-effects'] , 'core' );
							}
							// load the components
							if ( isset($arr['ui-theme']) && file_exists(LEPTON_PATH.'/modules/lib_jquery/jquery-ui/themes/'.$arr['ui-theme']) ) {
								$output .= '<link rel="stylesheet" type="text/css" href="' . sanitize_url(LEPTON_URL.'/modules/lib_jquery/jquery-ui/themes/'.$arr['ui-theme'].'/jquery-ui.css').'" media="all" />' . "\n";
							}
							if ( isset($arr['core']) && $arr['core'] === true ) {
								$output .= '<script type="text/javascript" src="' . sanitize_url(LEPTON_URL.'/modules/lib_jquery/jquery-core/jquery-core.min.js').'"></script>' . "\n";
							}
							if ( isset($arr['ui']) && $arr['ui'] === true ) {
								$output .= '<script type="text/javascript" src="' . sanitize_url(LEPTON_URL.'/modules/lib_jquery/jquery-ui/ui/jquery.ui.core.min.js').'"></script>' . "\n";
							}
							if ( isset($arr['ui-effects']) && is_array($arr['ui-effects']) ) {
								foreach( $arr['ui-effects'] as $item ) {
									$output .= '<script type="text/javascript" src="' . sanitize_url(LEPTON_URL.'/modules/lib_jquery/jquery-ui/ui/jquery.effects.'.$item.'.min.js').'"></script>' . "\n";
								}
							}
							if ( isset($arr['ui-components']) && is_array($arr['ui-components']) ) {
								foreach( $arr['ui-components'] as $item ) {
									$output .= '<script type="text/javascript" src="' . sanitize_url(LEPTON_URL.'/modules/lib_jquery/jquery-ui/ui/jquery.ui.'.$item.'.min.js').'"></script>' . "\n";
								}
							}
							if ( isset($arr['all']) && is_array($arr['all']) ) {
								foreach( $arr['all'] as $item ) {
									$output .= '<script type="text/javascript" src="' . sanitize_url( LEPTON_URL . '/modules/lib_jquery/plugins/' . $item . '/' . $item . '.js' ) . '"></script>' . "\n";
								}
							}
							if ( isset($arr['individual']) && is_array( $arr['individual'] ) ) {
								foreach( $arr['individual'] as $section_name => $item ) {
									if ( $section_name == strtolower($individual) )
									{
										$output .= '<script type="text/javascript" src="' . sanitize_url( LEPTON_URL . '/modules/lib_jquery/plugins/' . $item . '/' . $item . '.js' ) . '"></script>' . "\n";
									}
								}
							}
							break;
						case 'js':
							if ( is_array($arr) )
							{
								if ( isset($arr['all']) )
								{
									foreach ( $arr['all'] as $item )
									{
										$output .= '<script type="text/javascript" src="' . sanitize_url( LEPTON_URL . '/templates/' . DEFAULT_THEME . '/js/' . $item ) . '"></script>' . "\n";
									}
								}
								if ( isset($arr['individual']) )
								{
									foreach ( $arr['individual'] as $section_name => $item )
									{
										if ( $section_name == strtolower($individual) )
										{
											$output .= '<script type="text/javascript" src="' . sanitize_url( LEPTON_URL . '/templates/' . DEFAULT_THEME . '/js/' . $item ) . '"></script>' . "\n";
										}
									}
								}
							}
							else
							{
								$output .= '<script type="text/javascript" src="' . sanitize_url(LEPTON_URL . '/' . $arr) . '"></script>' . "\n";
							}
							break;
						default:
							trigger_error('Unknown header type ['.$key.']!', E_USER_NOTICE);
							break;
					}
				}
			}
			// foreach( array( 'meta', 'css', 'js' ) as $key )
		if ( $print_output )
		{
			echo $output;
			define('LEP_HEADERS_SENT', true);
		}
		else
		{
			return $output;
		}

    }   // end function get_page_headers()
    
    /**
     * get additions for page footer (js, script)
     *
     * + gets all active sections for a page;
     * + scans module directories for file footers.inc.php;
     * + includes that file if it is available
     * + includes automatically if exists:
     *   + module dirs:
     *     + frontend.css / backend.css              (media: all)
     *     + frontend_print.css / backend_print.css  (media: print)
     *   + template dir:
     *     + <PAGE_ID>.css in template dir           (media: all)
     *
     * @access public
     * @param  string  $for - 'frontend' (default) / 'backend'
     * @return void (echo's result)
     *
     **/
    function get_page_footers($for = 'frontend')
    {
        global $FOOTERS, $array, $lhd;
        // don't do this twice
        if (defined('LEP_FOOTERS_SENT'))
        {
            return;
        }
        if ( ! $for || $for == '' || ( $for != 'frontend' && $for != 'backend' ) ) {
            $for = 'frontend';
        }
        $page_id = defined( 'PAGE_ID' )
                 ? PAGE_ID
                 : (
                       ( isset($_GET['page_id']) && is_numeric($_GET['page_id']) )
                     ? $_GET['page_id']
                     : NULL
                   );

        $js_subdirs  = array();

        // it's an admin tool...
        if ( $for == 'backend' && isset($_REQUEST['tool']) && file_exists( LEPTON_PATH.'/modules/'.$_REQUEST['tool'].'/tool.php' ) )
        {
			$js_subdirs = array(
		        '/modules/'   . $_REQUEST['tool']         ,
		        '/modules/'   . $_REQUEST['tool']  . '/js',
			);
			if (file_exists(LEPTON_PATH . '/modules/' . $_REQUEST['tool'] . '/footers.inc.php'))
			{
				__addItems( $for, LEPTON_PATH . '/modules/' . $_REQUEST['tool'], true );
			}
        }
        
        elseif ( $page_id && is_numeric($page_id) )
        {
            // ...get active sections
		    if ( ! class_exists( 'LEPTON_Sections' ) )
		    {
		        @require_once dirname(__FILE__).'/LEPTON/Sections.php';
			}
			$sec_h    = new LEPTON_Sections();
			$sections = $sec_h->get_active_sections($page_id);
            if ( is_array($sections) && count($sections) )
            {
                global $current_section;
                foreach ($sections as $section)
                {
                    $module = $section['module'];
					// find header definition file
                    if (file_exists(LEPTON_PATH . '/modules/' . $module . '/footers.inc.php'))
                    {
                        $current_section = $section['section_id'];
						__addItems( $for, LEPTON_PATH . '/modules/' . $module );
					}
					$js_subdirs = array(
				        '/modules/'   . $module         ,
				        '/modules/'   . $module  . '/js',
					);
				}   // foreach ($sections as $section)
			}       // if (count($sections))

			// add css/js search subdirs for frontend only; page based CSS/JS
			// does not make sense in BE
			if ( $for == 'frontend' )
                        {
				array_push(
					$js_subdirs,
			        PAGES_DIRECTORY,
			        PAGES_DIRECTORY . '/js'
				);
                                        }
                                    }

		// add template JS
		// note: defined() is just to avoid warnings, the NULL does not really
		// make sense!
		$subdir = ( $for == 'backend' )
				? ( defined('DEFAULT_THEME') ? DEFAULT_THEME : NULL )
				: ( defined('TEMPLATE')      ? TEMPLATE      : NULL )
				;

		array_push(
			$js_subdirs,
			'/templates/' . $subdir,
	        '/templates/' . $subdir . '/js'
		);
		
		// automatically add JS files
		foreach( $js_subdirs as $directory ) {
			$file = $lhd->sanitizePath( $directory.'/'.$for.'_body.js' );
			if ( file_exists(LEPTON_PATH.'/'.$file) ) {
				$FOOTERS[$for]['js'][] = $file;
                    }
                }
                $output = null;
                foreach (array('js','script') as $key)
                {
                    if ( ! isset($FOOTERS[$for][$key]) || ! is_array($FOOTERS[$for][$key]) )
                    {
                        continue;
                    }
                    // make array unique
                    $FOOTERS[$for][$key] = $array->ArrayUniqueRecursive($FOOTERS[$for][$key]);
                    foreach ($FOOTERS[$for][$key] as $i => $arr)
                    {
                        switch ($key)
                        {
                            case 'js':
                                $output .= '<script type="text/javascript" src="' . sanitize_url( LEPTON_URL . '/' . $arr ) . '"></script>' . "\n";
                                break;
                            case 'script':
                                $output .= '<script type="text/javascript">' . implode( "\n", $arr ) . '</script>' . "\n";
                                break;
                            default:
                                trigger_error('Unknown footer type ['.$key.']!', E_USER_NOTICE);
                                break;
                        }
                    }
                }
                // foreach( array( 'meta', 'css', 'js' ) as $key )
                echo $output;
                define('LEP_FOOTERS_SENT', true);
        
    }   // end function get_page_footers()

    // Function to get page title
    function get_page_title($id)
    {
        global $database;
        // Get title
        $sql = 'SELECT `page_title` FROM `' . TABLE_PREFIX . 'pages` WHERE `page_id` = ' . $id;
        $page_title = $database->get_one($sql);
        return $page_title;
    }
    // Function to get a pages menu title
    function get_menu_title($id)
    {
        global $database;
        // Get title
        $sql = 'SELECT `menu_title` FROM `' . TABLE_PREFIX . 'pages` WHERE `page_id` = ' . $id;
        $menu_title = $database->get_one($sql);
        return $menu_title;
    }
    // Function to get all parent page titles
    function get_parent_titles($parent_id)
    {
        $titles[] = get_menu_title($parent_id);
        if (is_parent($parent_id) != false)
        {
            $parent_titles = get_parent_titles(is_parent($parent_id));
            $titles = array_merge($titles, $parent_titles);
        }
        return $titles;
    }
    // Function to get all parent page id's
    function get_parent_ids($parent_id)
    {
        $ids[] = $parent_id;
        if (is_parent($parent_id) != false)
        {
            $parent_ids = get_parent_ids(is_parent($parent_id));
            $ids = array_merge($ids, $parent_ids);
        }
        return $ids;
    }
    // Function to generate page trail
    function get_page_trail($page_id)
    {
        return implode(',', array_reverse(get_parent_ids($page_id)));
    }
    
    // Function to get all sub pages id's
    function get_subs($parent, $subs)
    {
        // Connect to the database
        global $database;
        // Get id's
        $sql = 'SELECT `page_id` FROM `' . TABLE_PREFIX . 'pages` WHERE `parent` = ' . $parent;
        $query = $database->query($sql);
        if ($query->numRows() > 0)
        {
            while (false !== ($fetch = $query->fetchRow()))
            {
                $subs[] = $fetch['page_id'];
                // Get subs of this sub
                $subs = get_subs($fetch['page_id'], $subs);
            }
        }
        // Return subs array
        return $subs;
    }
    // Convert a string from mixed html-entities/umlauts to pure $charset_out-umlauts
    // Will replace all numeric and named entities except &gt; &lt; &apos; &quot; &#039; &nbsp;
    // In case of error the returned string is unchanged, and a message is emitted.
    function entities_to_umlauts($string, $charset_out = DEFAULT_CHARSET)
    {
        require_once(LEPTON_PATH . '/framework/functions-utf8.php');
        return entities_to_umlauts2($string, $charset_out);
    }
    // Will convert a string in $charset_in encoding to a pure ASCII string with HTML-entities.
    // In case of error the returned string is unchanged, and a message is emitted.
    function umlauts_to_entities($string, $charset_in = DEFAULT_CHARSET)
    {
        require_once(LEPTON_PATH . '/framework/functions-utf8.php');
        return umlauts_to_entities2($string, $charset_in);
    }
    
    /**
     * sanitize path (remove '/./', '/../', '//')
     *
     *
     *
     **/
    function sanitize_path( $path )
    {
        global $lhd;
		return $lhd->sanitizePath($path);
    }   // end function sanitize_path()
    
    /**
     * sanitize URL (remove '/./', '/../', '//')
     *
     *
     *
     **/
    function sanitize_url( $href )
    {
        // href="http://..." ==> href isn't relative
        $rel_parsed = parse_url($href);
        $path       = $rel_parsed['path'];

        // bla/./bloo ==> bla/bloo
        $path       = preg_replace('~/\./~', '/', $path);

        // resolve /../
        // loop through all the parts, popping whenever there's a .., pushing otherwise.
        $parts      = array();
        foreach ( explode('/', preg_replace('~/+~', '/', $path)) as $part )
        {
            if ($part === ".." || $part == '')
            {
                array_pop($parts);
            }
            elseif ($part!="")
            {
                $parts[] = $part;
            }
        }

        return
        (
              ( is_array($rel_parsed) && array_key_exists( 'scheme', $rel_parsed ) )
            ? $rel_parsed['scheme'] . '://' . $rel_parsed['host'] . ( isset($rel_parsed['port']) ? ':'.$rel_parsed['port'] : NULL )
            : ""
        ) . "/" . implode("/", $parts);
        
    }   // end function sanitize_url()
     
    // @internal webbird - moved this function from admins/modules/uninstall.php and admins/templates/uninstall.php
    function replace_all($aStr = "", &$aArray)
    {
        foreach ($aArray as $k => $v)
        {
            $aStr = str_replace("{{" . $k . "}}", $v, $aStr);
        }
        return $aStr;
    }   // end function replace_all()

    // Function to convert a page title to a page filename
    function page_filename($string)
    {
        require_once(LEPTON_PATH . '/framework/functions-utf8.php');
        $string = entities_to_7bit($string);
        // Now remove all bad characters
        $bad = array('\'', '"', '`', '!', '@', '#', '$', '%', '^', '&', '*', '=', '+', '|', '/', '\\', ';', ':', ',', '?');
        $string = str_replace($bad, '', $string);
        // replace multiple dots in filename to single dot and (multiple) dots at the end of the filename to nothing
        $string = preg_replace(array('/\.+/', '/\.+$/'), array('.', ''), $string);
        // Now replace spaces with page spcacer
        $string = trim($string);
        $string = preg_replace('/(\s)+/', PAGE_SPACER, $string);
        // Now convert to lower-case
        $string = strtolower($string);
        // If there are any weird language characters, this will protect us against possible problems they could cause
        $string = str_replace(array('%2F', '%'), array('/', ''), urlencode($string));
        // Finally, return the cleaned string
        return $string;
    }
    // Function to convert a desired media filename to a clean filename
    function media_filename($string)
    {
        require_once(LEPTON_PATH . '/framework/functions-utf8.php');
        $string = entities_to_7bit($string);
        // Now remove all bad characters
        $bad = array('\'', '"', '`', '!', '@', '#', '$', '%', '^', '&', '*', '=', '+', '|', '/', '\\', ';', ':', ',', '?');
        $string = str_replace($bad, '', $string);
        // replace multiple dots in filename to single dot and (multiple) dots at the end of the filename to nothing
        $string = preg_replace(array('/\.+/', '/\.+$/', '/\s/'), array('.', '', '_'), $string);
        // Clean any page spacers at the end of string
        $string = trim($string);
        // Finally, return the cleaned string
        return $string;
    }

    /**
     * wrapper to $admin->page_link()
     **/
    function page_link($link)
    {
        global $admin;
        return $admin->page_link($link);
    }   // end function page_link()

    /*
     * create_access_file
     * @param string $filename: full path and filename to the new access-file
     * @param int $page_id: ID of the page for which the file should created
     * @param int $level: never needed argument, for compatibility only
     * @param mixed $opt_cmds: a string or an array with one or more additional statements
     *                         to include in accessfile.
     *                         Example: $opt_cmds = "$section_id = '.$section_id"
     *                                  $opt_cmds = array(
     *                                       '$section_id = '.$section_id,
     *                                       '$mod_var_txt = \''.$mod_var_txt.'\'',
     *                                       '$mod_var_int = '.$mod_var_int
     *                                  );
     * @description: Create a new access file in the pages directory ans subdirectory also if needed
     * @warning: the params $level and $opt_cmds should NOT be used for new developments!! It will
     *           be removed in one of the next releases !!!!!!!!!!!!!
     */
    // M.f.i.   2011-02-17  drp: this one is worse ...
    //        a) test where call from
    //        b) test the circumstances
    //        c) optimize the code as it is .. even the two params at the end!
    function create_access_file($filename, $page_id, $level, $opt_cmds = null)
    {
        global $admin, $MESSAGE;
        $pages_path = LEPTON_PATH . PAGES_DIRECTORY;
        $rel_pages_dir = str_replace($pages_path, '', dirname($filename));
        $rel_filename = str_replace($pages_path, '', $filename);
        // root_check prevent system directories and importent files from being overwritten if PAGES_DIR = '/'
        $denied = false;
        if (PAGES_DIRECTORY == '')
        {
            $forbidden = array('account', 'admin', 'framework', 'include', 'install', 'languages', 'media', 'modules', 'pages', 'search', 'temp', 'templates', 'index.php', 'config.php', 'upgrade-script.php');
            $search = explode('/', $rel_filename);
            //! 6 -- why only the first level?
            $denied = in_array($search[1], $forbidden);
        }
        if ((true === is_writable($pages_path)) && (false == $denied))
        {
            // First make sure parent folder exists
            $parent_folders = explode('/', $rel_pages_dir);
            $parents = '';
            foreach ($parent_folders as $parent_folder)
            {
                if ($parent_folder != '/' && $parent_folder != '')
                {
                    $parents .= '/' . $parent_folder;
                    if (!file_exists($pages_path . $parents))
                    {
                        make_dir($pages_path . $parents);
                        change_mode($pages_path . $parents);
                    }
                }
            }
            $step_back = str_repeat('../', substr_count($rel_pages_dir, '/') + (PAGES_DIRECTORY == "" ? 0 : 1));
            $content = '<?php' . "\n";
            $content .= "/**\n *\tThis file is autogenerated by LEPTON - Version: " . VERSION . "\n";
            $content .= " *\tDo not modify this file!\n */\n";
            $content .= "\t" . '$page_id = ' . $page_id . ';' . "\n";
            if ($opt_cmds)
            {
                // #! 3 -- not clear what this meeans at all! and in witch circumstances this 'param' will be make sence?
                if (!is_array($opt_cmds))
                {
                    $opt_cmds = explode('!', $opt_cmds);
                }
                foreach ($opt_cmds as $command)
                {
                    $new_cmd = rtrim(trim($command), ';');
                    $content .= (preg_match('/include|require/i', $new_cmd) ? '// *not allowed command >> * ' : "\t");
                    $content .= $new_cmd . ';' . "\n";
                }
            }
            //! 4 -- should be require once ...
            $content .= "\t" . 'require(\'' . $step_back . 'index.php\');' . "\n";
            $content .= '?>';
            /**
             *  write the file
             *
             */
            $fp = fopen($filename, 'w');
            if ($fp)
            {
                fwrite($fp, $content, strlen($content));
                fclose($fp);
                /**
                 *  Chmod the file
                 *
                 */
                change_mode($filename);
                /**
				 *	Looking for the index.php inside the current directory.
				 *	If not found - we're just copy the master_index.php from the admin/pages
				 *
				 */
				$temp_index_path = dirname($filename)."/index.php";
				if (!file_exists($temp_index_path))
				{
					$origin = ADMIN_PATH."/pages/master_index.php";
					if (file_exists($origin)) copy( $origin, $temp_index_path);
				}

            }
            else
            {
                /**
                 *  M.f.i  drp:  as long as we've got no key for this situation inside the languagefiles
                 *          we're in the need to make a little addition to the given one, to get it unique for trouble-shooting.
                 */
                $admin->print_error($MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE'] . "<br />Problems while trying to open the file!");
                return false;
            }
            return true;
        }
        else
        {
            $admin->print_error($MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE']);
            return false;
        }
    }
    
    /**
     *	Get the mime-type of a given file.
     *
     *	@param	string	A filename within the complete local path.
     *	@return	string	Returns the content type in MIME format, e.g. 'image/gif', 'text/plain', etc.
     *	@notice			If nothing match, the function will return 'application/octet-stream'.
     *
     *	2011-10-04	Aldus:	The function has been marked as 'deprecated' by PHP/Zend.
     *						For details please take a look at:
     *						http://php.net/manual/de/function.mime-content-type.php
     *
     */
    if (!function_exists("mime_content_type"))
    {
		function mime_content_type($filename)
		{
			$mime_types = array('txt' => 'text/plain', 'htm' => 'text/html', 'html' => 'text/html', 'php' => 'text/html', 'css' => 'text/css', 'js' => 'application/javascript', 'json' => 'application/json', 'xml' => 'application/xml', 'swf' => 'application/x-shockwave-flash', 'flv' => 'video/x-flv', // images
			'png' => 'image/png', 'jpe' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'jpg' => 'image/jpeg', 'gif' => 'image/gif', 'bmp' => 'image/bmp', 'ico' => 'image/vnd.microsoft.icon', 'tiff' => 'image/tiff', 'tif' => 'image/tiff', 'svg' => 'image/svg+xml', 'svgz' => 'image/svg+xml', // archives
			'zip' => 'application/zip', 'rar' => 'application/x-rar-compressed', 'exe' => 'application/x-msdownload', 'msi' => 'application/x-msdownload', 'cab' => 'application/vnd.ms-cab-compressed', // audio/video
			'mp3' => 'audio/mpeg', 'mp4' => 'audio/mpeg', 'qt' => 'video/quicktime', 'mov' => 'video/quicktime', // adobe
			'pdf' => 'application/pdf', 'psd' => 'image/vnd.adobe.photoshop', 'ai' => 'application/postscript', 'eps' => 'application/postscript', 'ps' => 'application/postscript', // ms office
			'doc' => 'application/msword', 'rtf' => 'application/rtf', 'xls' => 'application/vnd.ms-excel', 'ppt' => 'application/vnd.ms-powerpoint', // open office
			'odt' => 'application/vnd.oasis.opendocument.text', 'ods' => 'application/vnd.oasis.opendocument.spreadsheet', );
			$temp = explode('.', $filename);
			$ext = strtolower(array_pop($temp));
			if (array_key_exists($ext, $mime_types))
			{
				return $mime_types[$ext];
			}
			elseif (function_exists('finfo_open'))
			{
				$finfo = finfo_open(FILEINFO_MIME);
				$mimetype = finfo_file($finfo, $filename);
				finfo_close($finfo);
				return $mimetype;
			}
			else
			{
				return 'application/octet-stream';
			}
		}   // end function mime_content_type()
	}
    // Generate a thumbnail from an image
    function make_thumb($source, $destination, $size)
    {
        return $this->get_helper('Image')->make_thumb( $source, $destination, $size );
    }   // end make_thumb()
    
    /*
     * Function to work-out a single part of an octal permission value
     *
     * @param mixed $octal_value: an octal value as string (i.e. '0777') or real octal integer (i.e. 0777 | 777)
     * @param string $who: char or string for whom the permission is asked( U[ser] / G[roup] / O[thers] )
     * @param string $action: char or string with the requested action( r[ead..] / w[rite..] / e|x[ecute..] )
     * @return boolean
     */
    function extract_permission($octal_value, $who, $action)
    {
        // Make sure that all arguments are set and $octal_value is a real octal-integer
        if (($who == '') || ($action == '') || (preg_match('/[^0-7]/', (string)$octal_value)))
        {
            // invalid argument, so return false
            return false;
        }
        // convert into a decimal-integer to be sure having a valid value
        $right_mask = octdec($octal_value);
        switch (strtolower($action[0]))
        {
            // get action from first char of $action
            // set the $action related bit in $action_mask
            case 'r':
                // set read-bit only (2^2)
                $action_mask = 4;
                break;
            case 'w':
                // set write-bit only (2^1)
                $action_mask = 2;
                break;
            case 'e':
            case 'x':
                // set execute-bit only (2^0)
                $action_mask = 1;
                break;
            default:
                // undefined action name, so return false
                return false;
        }
        switch (strtolower($who[0]))
        {
            // get who from first char of $who
            // shift action-mask into the right position
            case 'u':
                // shift left 3 bits
                $action_mask <<= 3;
            case 'g':
                // shift left 3 bits
                $action_mask <<= 3;
            case 'o':
                /* NOP */
                break;
            default:
                // undefined who, so return false
                return false;
        }
        // return result of binary-AND
        return(($right_mask & $action_mask) != 0);
    }
    
    /**
     * delete a page
     *
     * @access public
     * @param  integer $page_id
     * @return void
     *
     **/
    function delete_page($page_id)
    {
        global $admin, $database, $MESSAGE;
        // Find out more about the page
        // $database = new database();
        $sql = 'SELECT `page_id`, `menu_title`, `page_title`, `level`, `link`, `parent`, `modified_by`, `modified_when` ';
        $sql .= 'FROM `' . TABLE_PREFIX . 'pages` WHERE `page_id` = ' . $page_id;
        $results = $database->query($sql);
        if ($database->is_error())
        {
            $admin->print_error($database->get_error());
        }
        if ($results->numRows() == 0)
        {
            $admin->print_error($MESSAGE['PAGES_NOT_FOUND']);
        }
        $results_array = $results->fetchRow( MYSQL_ASSOC );
        $parent = $results_array['parent'];
        $level = $results_array['level'];
        $link = $results_array['link'];
        $page_title = $results_array['page_title'];
        $menu_title = $results_array['menu_title'];
        // Get the sections that belong to the page
        $sql = 'SELECT `section_id`, `module` FROM `' . TABLE_PREFIX . 'sections` WHERE `page_id` = ' . $page_id;
        $query_sections = $database->query($sql);
        if ($query_sections->numRows() > 0)
        {
            while (false !== ($section = $query_sections->fetchRow( MYSQL_ASSOC )))
            {
                // Set section id
                $section_id = $section['section_id'];
                // Include the modules delete file if it exists
                if (file_exists(LEPTON_PATH . '/modules/' . $section['module'] . '/delete.php'))
                {
                    include(LEPTON_PATH . '/modules/' . $section['module'] . '/delete.php');
                }
            }
        }
        // Update the pages table
        $sql = 'DELETE FROM `' . TABLE_PREFIX . 'pages` WHERE `page_id` = ' . $page_id;
        $database->query($sql);
        if ($database->is_error())
        {
            $admin->print_error($database->get_error());
        }
        // Update the sections table
        $sql = 'DELETE FROM `' . TABLE_PREFIX . 'sections` WHERE `page_id` = ' . $page_id;
        $database->query($sql);
        if ($database->is_error())
        {
            $admin->print_error($database->get_error());
        }
        // Include the ordering class or clean-up ordering
        include_once(LEPTON_PATH . '/framework/class.order.php');
        $order = new order(TABLE_PREFIX . 'pages', 'position', 'page_id', 'parent');
        $order->clean($parent);
        // Unlink the page access file and directory
        $directory = LEPTON_PATH . PAGES_DIRECTORY . $link;
        $filename = $directory . PAGE_EXTENSION;
        $directory .= '/';
        if (file_exists($filename))
        {
            if (!is_writable(LEPTON_PATH . PAGES_DIRECTORY . '/'))
            {
                $admin->print_error($MESSAGE['PAGES_CANNOT_DELETE_ACCESS_FILE']);
            }
            else
            {
                unlink($filename);
                if (file_exists($directory) && (rtrim($directory, '/') != LEPTON_PATH . PAGES_DIRECTORY) && (substr($link, 0, 1) != '.'))
                {
                    rm_full_dir($directory);
                }
            }
        }
    }   // end function delete_page()
    
    /**
     *  Load module-info into the current DB
     *
     *  @param  string  Any valid directory(-path)
     *  @param  boolean Call the install-script of the module? Default: false
     *
     *  THIS METHOD WAS MOVED TO LEPTON_Helper_Addons!
     *
     */
    function load_module($directory, $install = false)
    {
        if ( ! class_exists( 'LEPTON_Helper_Addons' ) )
                {
	        @require_once dirname(__FILE__).'/LEPTON/Helper/Addons.php';
        }
		$addons_helper = new LEPTON_Helper_Addons();
		return $addons_helper->installModule($directory, $install);
    }   // end function load_module()
    
    /**
     *  Load template-info into the DB.
     *
     *  @param  string  Any valid directory
     *
     *  @notice  Keep in mind, that the variable-check is here the same as in
     *      File: admins/templates/install.php.
     *      The reason is, that it could be possible to call this function from
     *      another script/codeblock direct. So in thees circumstances where would no
     *      test at all, and the possibility to entry wrong data is given.
     *
     */
    function load_template($directory)
    {
        global $database, $admin, $logger, $MESSAGE;

        if (is_dir($directory) && file_exists($directory . '/info.php'))
        {
            global $template_license, $template_directory, $template_author, $template_version, $template_function, $template_description, $template_platform, $template_name, $template_guid;
			require($directory . "/info.php");
            // Check that it doesn't already exist
            $sqlwhere = "WHERE `type` = 'template' AND `directory` = '" . $template_directory . "'";
            $sql = "SELECT COUNT(*) FROM `" . TABLE_PREFIX . "addons` " . $sqlwhere;
            if ($database->get_one($sql))
            {
                $sql = "UPDATE `" . TABLE_PREFIX . "addons` SET ";
            }
            else
            {
                $sql = "INSERT INTO `" . TABLE_PREFIX . "addons` SET ";
                $sqlwhere = "";
            }
            $sql .= "`directory` = '" . mysql_real_escape_string($template_directory) . "',";
            $sql .= "`name` = '" . mysql_real_escape_string($template_name) . "',";
            $sql .= "`description`= '" . mysql_real_escape_string($template_description) . "',";
            $sql .= "`type`= 'template',";
            $sql .= "`function` = '" . mysql_real_escape_string($template_function) . "',";
            $sql .= "`version` = '" . mysql_real_escape_string($template_version) . "',";
            $sql .= "`platform` = '" . mysql_real_escape_string($template_platform) . "',";
            $sql .= "`author` = '" . mysql_real_escape_string($template_author) . '\', ';
            $sql .= "`license` = '" . mysql_real_escape_string($template_license) . "' ";
            if (isset($template_guid)) {
			    $sql .= ", `guid` = '".mysql_real_escape_string($template_guid)."' ";
			}
			else {
			    $sql .= ", `guid` = '' ";
			}
		    $sql .= $sqlwhere;
            $database->query($sql);
            if ($database->is_error())
            {
                $admin->print_error($database->get_error());
			}
        }
    }   // end function load_template()
    
    /**
     *  Load language-info into the current DB
     *
     *  @param  string  Any valid path.
     *
     */
    function load_language($file)
    {
        global $database, $admin, $MESSAGE;
        if (file_exists($file) && preg_match('#^([A-Z]{2}.php)#', basename($file)))
        {
            $language_license = null;
            $language_code = null;
            $language_version = null;
            $language_guid = null;
            $language_name = null;
            $language_author = null;
            $language_platform = null;
            require($file);
            if (isset($language_name))
            {
                if ((!isset($language_license)) || (!isset($language_code)) || (!isset($language_version)) || (!isset($language_guid)))
                {
                    $admin->print_error($MESSAGE["LANG_MISSING_PARTS_NOTICE"], $language_name);
                }
                // Check that it doesn't already exist
                $sqlwhere = 'WHERE `type` = \'language\' AND `directory` = \'' . $language_code . '\'';
                $sql = 'SELECT COUNT(*) FROM `' . TABLE_PREFIX . 'addons` ' . $sqlwhere;
                if ($database->get_one($sql))
                {
                    $sql = 'UPDATE `' . TABLE_PREFIX . 'addons` SET ';
                }
                else
                {
                    $sql = 'INSERT INTO `' . TABLE_PREFIX . 'addons` SET ';
                    $sqlwhere = '';
                }
                $sql .= '`directory` = \'' . $language_code . '\', ';
                $sql .= '`name` = \'' . $language_name . '\', ';
                $sql .= '`type`= \'language\', ';
                $sql .= '`version` = \'' . $language_version . '\', ';
                $sql .= '`platform` = \'' . $language_platform . '\', ';
                $sql .= '`author` = \'' . addslashes($language_author) . '\', ';
                $sql .= '`license` = \'' . addslashes($language_license) . '\', ';
                $sql .= '`guid` = \''.$language_guid.'\', ';
				$sql .= '`description` = \'\'  ';
				$sql .= $sqlwhere;
                $database->query($sql);
                if ($database->is_error())
                    $admin->print_error($database->get_error());
            }
        }
    }
    /**
     *  Update the module informations in the DB
     *
     *  @param  string  Name of the modul-directory
     *  @param  bool  Optional boolean to run the upgrade-script of the module.
     *
     *  @return  nothing
     *
     */
    function upgrade_module($directory, $upgrade = false)
    {
        global $database, $admin, $MESSAGE;
        global $module_license, $module_author, $module_name, $module_directory, $module_version, $module_function, $module_guid, $module_description, $module_platform;
        $fields = array('version' => $module_version, 'description' => mysql_real_escape_string($module_description), 'platform' => $module_platform, 'author' => mysql_real_escape_string($module_author), 'license' => mysql_real_escape_string($module_license), 'guid' => mysql_real_escape_string($module_guid));
        $sql = 'UPDATE `' . TABLE_PREFIX . 'addons` SET ';
        foreach ($fields as $key => $value)
            $sql .= "`" . $key . "`='" . $value . "',";
        $sql = substr($sql, 0, -1) . " WHERE `directory`= '" . $module_directory . "'";
        $database->query($sql);
        if ($database->is_error())
            $admin->print_error($database->get_error());
    }  // end function upgrade_module()
    
    function get_variable_content($search, $data, $striptags = true, $convert_to_entities = true)
    {
        $match = '';
        // search for $variable followed by 0-n whitespace then by = then by 0-n whitespace
        // then either " or ' then 0-n characters then either " or ' followed by 0-n whitespace and ;
        // the variable name is returned in $match[1], the content in $match[3]
        if (preg_match('/(\$' . $search . ')\s*=\s*("|\')(.*)\2\s*;/', $data, $match))
        {
            if (strip_tags(trim($match[1])) == '$' . $search)
            {
                // variable name matches, return it's value
                $match[3] = ($striptags == true) ? strip_tags($match[3]) : $match[3];
                $match[3] = ($convert_to_entities == true) ? htmlentities($match[3]) : $match[3];
                return $match[3];
            }
        }
        return false;
    }   // end function get_variable_content()

    /**
     *  Try to get the current version of a given Modul.
     *
     *  @param  string  $modulname: like saved in addons directory
     *  @param  boolean  $source: true reads from database, false from info.php
     *  @return  string  the version as string, if not found returns null
     *
     */
    function get_modul_version($modulname, $source = true)
    {
        global $database;
        $version = null;
        if ($source != true)
        {
            $sql = "SELECT `version` FROM `" . TABLE_PREFIX . "addons` WHERE `directory`='" . $modulname . "'";
            $version = $database->get_one($sql);
        }
        else
        {
            $info_file = LEPTON_PATH . '/modules/' . $modulname . '/info.php';
            if (file_exists($info_file))
            {
                $module_version = null;
                require($info_file);
                $version = &$module_version;
            }
        }
        return $version;
    }
    
    /**
     *
     *
     *
     *
     **/
	function valid_lepton_template($file)
	{
		if ( ! file_exists( $file ) )
		{
			return false;
		}
		$suffix = pathinfo( $file, PATHINFO_EXTENSION );
		if ( $suffix == 'php' )
		{
			$string = implode( '', file($file) );
			if ( $string )
			{
				$tokens  = token_get_all($string);
				foreach( $tokens as $i => $token )
				{
					if ( is_array($token) )
					{
						if ( strcasecmp( $token[1], 'register_frontend_modfiles' ) == 0 )
						{
							return false;
						}
					}
				}
				return true;
			}
		}
		return false;
	}	// end function valid_lepton_template()

    /**
     * Generate a globally unique identifier (GUID)
     * Uses COM extension under Windows otherwise
     * create a random GUID in the same style
     * @return STR GUID
     */
    function createGUID()
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
    
    function checkIPv4address($ip_addr)
    {
        if (preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/", $ip_addr))
        {
            $parts = explode(".", $ip_addr);
            foreach ($parts as $ip_parts)
            {
                if (intval($ip_parts) > 255 || intval($ip_parts) < 0)
                    return false;
            }
            return true;
        }
        else
            return false;
    }   // end function checkIPv4address()
    
    /**
     *  As for some special chars, e.g. german-umlauts, inside js-alerts we are in the need to escape them.
     *  Keep in mind, that you will to have unescape them befor you use them inside a js!
     *
     */
    function js_alert_encode($string)
    {
        $entities = array('&auml;' => "%E4", '&Auml;' => "%C4", '&ouml;' => "%F6", '&Ouml;' => "%D6", '&uuml;' => "%FC", '&Uuml;' => "%DC", '&szlig;' => "%DF", '&euro;' => "%u20AC", '$' => "%24");
        return str_replace(array_keys($entities), array_values($entities), $string);
    }
    
    function __addItems( $for, $path, $footer = false )
	{
		global $lhd, $HEADERS, $FOOTERS;
		$path   = $lhd->sanitizePath( $path );
		$trail  = explode( '/', $path );
		$subdir = array_pop($trail);
		
		$mod_headers = array();
		$mod_footers = array();
		
		if ( $footer )
		{
		    $add_to  =& $FOOTERS;
		    $to_load =  'footers.inc.php';
		}
		else
		{
		    $add_to  =& $HEADERS;
		    $to_load =  'headers.inc.php';
		}

		require( $lhd->sanitizePath( $path.'/'.$to_load) );
		
		if ( $footer )
		{
		    $array =& $mod_footers;
		}
		else
		{
		    $array =& $mod_headers;
		}
		
		if (count($array))
		{
			
			foreach (array('css', 'meta', 'js', 'jquery') as $key)
			{
			    if ( ! isset($array[$for][$key]) ) {
			        continue;
				}
				foreach( $array[$for][$key] as &$item ) {
				    // let's see if the path is relative (i.e., does not contain the current subdir)
					if ( isset( $item['file'] ) && ! preg_match( "#/$subdir/#", $item['file'] ) ) {
					    if ( file_exists( $path.'/'.$item['file'] ) ) {
						// treat path as relative, add modules subfolder
						$item['file'] = str_ireplace( $lhd->sanitizePath(LEPTON_PATH), '', $path ).'/'.$item['file'];
						}
						if ( file_exists( $lhd->sanitizePath( LEPTON_PATH.'/'.$item['file'] ) ) ) {
							$item['file'] = $lhd->sanitizePath( $item['file'] );
						}
					}
				}
				$add_to[$for][$key] = array_merge($add_to[$for][$key], $array[$for][$key]);
			}
		}
		
  		if ( $footer && file_exists( $lhd->sanitizePath( $path.$for.'_body.js') ) )
        {
            $FOOTERS[$for]['js'][]  = '/modules/'.$subdir.'_body.js';
        }
        
	}   // end function __addItems()

}
// end .. if functions is loaded 
?>