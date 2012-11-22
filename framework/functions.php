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
 * @version         $Id: functions.php 1668 2012-01-22 06:31:39Z phpmanufaktur $
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



/**
 *	Define that this file has been loaded
 *
 *	To avoid double function-declarations (inside LEPTON) and to avoid a massiv use
 *	of "if(!function_exists('any_function_name_here_since_wb_2.5.0')) {" we've to place it
 *	inside this condition-body!
 *
 */
if (!defined('FUNCTIONS_FILE_LOADED')) {
	define('FUNCTIONS_FILE_LOADED', true);

	/**
	 *	Function to remove a non-empty directory
	 *
	 *	2011-01-24	M.f.i!	Dietrich Roland Pehlke
	 *		If this file is part of an .svn or .git we should be able to give a message &| warning
	 *		to the user at all. As for the developers it could be tricky if the screen is mess up within
	 *		warning messages that doesn't belong to the package itself!
	 *
	 */
	if ( !function_exists('rm_full_dir') ) {
		function rm_full_dir($directory)
		{
			// If suplied dirname is a file then unlink it
			if (is_file($directory))
			{
				return unlink($directory);
			}
			// Empty the folder
			if (is_dir($directory))
			{
				$dir = dir($directory);
				while (false !== $entry = $dir->read())
				{
					// Skip pointers
					if ($entry == '.' || $entry == '..') { continue; }
					// Deep delete directories
					if (is_dir($directory.'/'.$entry))
					{
						rm_full_dir($directory.'/'.$entry);
					}
					else
					{
						unlink($directory.'/'.$entry);
					}
				}
				// Now delete the folder
				$dir->close();
				return rmdir($directory);
			}
		}
	} // end if function 'rm_full_dir' exists
	
	/**
	 *    This function returns a recursive list of all subdirectories from a given directory
	 *
	 *    @access  public
	 *    @param   string  $directory: from this dir the recursion will start.
	 *    @param   bool    $show_hidden: if set to TRUE also hidden dirs (.dir) will be shown.
	 *    @param   int     $recursion_deep: An optional integer to test the recursions-deep at all.
	 *    @param   array   $aList: A simple storrage list for the recursion.
	 *    @param   string  $ignore: This is the part of the "path" to be "ignored" - not implanted in WB
	 *						you will have to remove the '##' comment-signuature to get this available to users &| developers.
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
	if( !function_exists('directory_list')) {
		function directory_list($directory, $show_hidden = false, $recursion_deep = 0, &$aList=NULL, &$ignore="" )
		{
			if ($aList == NULL) $aList = array();
			## if ($recursion_deep == 0) $ignore= $directory;
			
			if (is_dir($directory))
			{
				$dir = dir($directory); // Open the directory
				if ($dir != NULL) {
					while ($entry = $dir->read())  // loop through the directory
					{
						if($entry == '.' || $entry == '..') continue;// Skip pointers
						if($entry[0] == '.' && $show_hidden == false) continue; // Skip hidden files
						
						$temp_dir = $directory."/".$entry;
						
						if (is_dir($temp_dir)) // Add dir and contents to list
						{
							$aList[] = str_replace($ignore, "", $temp_dir);
							$temp_result = directory_list( $temp_dir, $show_hidden, $recursion_deep+1, $aList, $ignore );
						}
					}
					$dir->close();
				}
			}
		
			if ($recursion_deep == 0)
			{
				natcasesort($aList);
				return $aList; // Now return the list
			}
		}
	} // end if function 'directory_list' exists
	
	/**
	 *	
	 *	2011-01-25:	M.f.i! Dietrich Roland Pehlke
	 *				This is one of the core functions i realy don't understand, as the second param
	 *				seems not to be use in any way! As for me it only looks for directorys and only change
	 *				the mod within the WB/LEPTON settings.
	 *				Also: there is no recursion-deep and there are no additional test if the change
	 *				has been successfull at all.
	 *
	 *	@param	string	Path to a given directory.
	 *	@param	string	FileMode to set - but not used in any way here!
	 *
	 */
	if( !function_exists( 'chmod_directory_contents' ) ) {
		function chmod_directory_contents( $directory, $file_mode )
		{
			if (is_dir($directory))
			{
				// Set the umask to 0
				$umask = umask(0);
				// Open the directory then loop through its contents
				$dir = dir($directory);
				while (false !== $entry = $dir->read())
				{
					// Skip pointers
					if($entry[0] == '.') { continue; }
					// Chmod the sub-dirs contents
					if(is_dir("$directory/$entry"))
					{
						chmod_directory_contents($directory.'/'.$entry, $file_mode);
					}
					change_mode($directory.'/'.$entry);
				}
				$dir->close();
				// Restore the umask
				umask($umask);
			}
		}
	}
	
	/**
	* Scan a given directory for dirs and files.
	*
	* usage: scan_current_dir ($root = '' )
	*
	* @param     $root   set a absolute rootpath as string. if root is empty the current path will be scan
	* @access    public
	* @return    array    returns a natsort array with keys 'path' and 'filename'
	*
	*/
	if(!function_exists('scan_current_dir'))
	{
		function scan_current_dir($root = '')
		{
			$FILE = array();
			clearstatcache();
			$root = empty ($root) ? getcwd() : $root;
			if (($handle = opendir($root)))
			{
			// Loop through the files and dirs an add to list  DIRECTORY_SEPARATOR
				while (false !== ($file = readdir($handle)))
				{
					if (substr($file, 0, 1) != '.' && $file != 'index.php')
					{
						if (is_dir($root.'/'.$file))
						{
							$FILE['path'][] = $file;
						} else {
							$FILE['filename'][] = $file;
						}
					}
				}
				$close_verz = closedir($handle);
			}
			if (isset ($FILE['path']) && natcasesort($FILE['path']))
			{
				$tmp = array();
				$FILE['path'] = array_merge($tmp, $FILE['path']);
			}
			if (isset ($FILE['filename']) && natcasesort($FILE['filename']))
			{
				$tmp = array();
				$FILE['filename'] = array_merge($tmp, $FILE['filename']);
			}
			return $FILE;
		}
	}
	
	/**
	 *	Function to list all files in a given directory.
	 *	
	 *	@param	string	A given directory
	 *	@param	array	A array within directorys to skip, e.g. '.svn' or '.git'
	 *	@param	bool	Show also hidden files, e.g. ".htaccess".
	 *
	 *	@retrun	array	Natsorted array within the files.
	 *
	 */
	if ( !function_exists('file_list' ) ) {
		function file_list( $directory, $skip = array(), $show_hidden = false )
		{
			$result_list = array();
			if (is_dir($directory))
			{
				$use_skip = ( count($skip) > 0 );
				
				$dir = dir($directory); // Open the directory
				while (false !== ($entry = $dir->read())) // loop through the directory
				{
					if( ($entry[0] == '.') && (false == $show_hidden) ) continue; // Skip hidden files
					
					if( (true === $use_skip) && (in_array($entry, $skip) ) ) continue; // Check if we to skip anything else
					
					if(is_file( $directory.'/'.$entry)) // Add files to list
					{
						$result_list[] = $directory.'/'.$entry;
					}
				}
				$dir->close(); // closing the folder-object
			}
		
			natcasesort($result_list);
	
			return $result_list;
		}
	}
	
	// Function to get a list of home folders not to show
	/**
	 *	M.f.i.!	Dietrich Roland Pehlke
	 *			I would like to keep the original comment unless i understand this one!
	 *			E.g. 'ami' is for me nothing more and nothing less than an 'admim'!
	 *
	 *			I'm also not acceppt the declaration of a function inside a function at all!
	 *			E.g. what happend if the function "get_home_folders" twice? Bang!
	 *
	 */
	if (!function_exists('get_home_folders')) {
		function get_home_folders()
		{
			global $database, $admin;
			$home_folders = array();
			// Only return home folders is this feature is enabled
			// and user is not admin
			//if(HOME_FOLDERS AND ($_SESSION['GROUP_ID']!='1'))
			if(HOME_FOLDERS && (!$admin->ami_group_member('1')))
			{
				$sql = 'SELECT `home_folder` FROM `'.TABLE_PREFIX.'users` WHERE `home_folder` != \''.$admin->get_home_folder().'\'';
				$query_home_folders = $database->query($sql);
				if($query_home_folders->numRows() > 0)
				{
					while($folder = $query_home_folders->fetchRow())
					{
						$home_folders[$folder['home_folder']] = $folder['home_folder'];
					}
				}
		
				function remove_home_subs($directory = '/', $home_folders = '')
				{
					if( ($handle = opendir(WB_PATH.MEDIA_DIRECTORY.$directory)) )
					{
						// Loop through the dirs to check the home folders sub-dirs are not shown
						while(false !== ($file = readdir($handle)))
						{
							if($file[0] != '.' && $file != 'index.php')
							{
								if(is_dir(WB_PATH.MEDIA_DIRECTORY.$directory.'/'.$file))
								{
									if($directory != '/')
									{
										$file = $directory.'/'.$file;
									}
									else
									{
										$file = '/'.$file;
									}
									foreach($home_folders AS $hf)
									{
										$hf_length = strlen($hf);
										if($hf_length > 0)
										{
											if(substr($file, 0, $hf_length+1) == $hf)
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
		}
	}
	/*
	 * @param object &$wb: $wb from frontend or $admin from backend
	 * @return array: list of new entries
	 * @description: callback remove path in files/dirs stored in array
	 * @example: array_walk($array,'remove_path',PATH);
	 */
	/**
	 *	M.f.o.!	MARKED FOR OBSOLETE
	 *			As this one belongs to the results of the function 'directory_list' 
	 *
	 */
	if (!function_exists('remove_path')) {
		function remove_path(&$path, $key, $vars = '')
		{
			$path = str_replace($vars, '', $path);
		}
	}
	/*
	 * @param object &$wb: $wb from frontend or $admin from backend
	 * @return array: list of ro-dirs
	 * @description: returns a list of directories beyound /wb/media which are ReadOnly for current user
	 *
	 *	M.f.i.!	Copy and paste crap
	 *
	 */
	if(!function_exists('media_dirs_ro')) {
	
		function media_dirs_ro( &$wb )
		{
			global $database;
			// if user is admin or home-folders not activated then there are no restrictions
			$allow_list = array();
			if( $wb->get_user_id() == 1 || !HOME_FOLDERS )
			{
				return array();
			}
			// at first read any dir and subdir from /media
			$full_list = directory_list( WB_PATH.MEDIA_DIRECTORY );
			// add own home_folder to allow-list
			if( $wb->get_home_folder() )
			{
				// old: $allow_list[] = get_home_folder();
				$allow_list[] = $wb->get_home_folder();
			}
			// get groups of current user
			$curr_groups = $wb->get_groups_id();
			// if current user is in admin-group
			 if( ($admin_key = array_search('1', $curr_groups)) !== false)
			{
				// remove admin-group from list
				unset($curr_groups[$admin_key]);
				// search for all users where the current user is admin from
				foreach( $curr_groups as $group)
				{
					$sql  = 'SELECT `home_folder` FROM `'.TABLE_PREFIX.'users` ';
					$sql .= 'WHERE (FIND_IN_SET(\''.$group.'\', `groups_id`) > 0) AND `home_folder` <> \'\' AND `user_id` <> '.$wb->get_user_id();
					if( ($res_hf = $database->query($sql)) != null )
					{
						while( $rec_hf = $res_hf->fetchrow( MYSQL_ASSOC ) )
						{
							$allow_list[] = $rec_hf['home_folder'];
						}
					}
				}
			}
			$tmp_array = $full_list;
			// create a list for readonly dir
			$array = array();
			while( sizeof($tmp_array) > 0)
			{
				$tmp = array_shift($tmp_array);
				$x = 0;
				while($x < sizeof($allow_list))
				{
					if(strpos ($tmp,$allow_list[$x])) {
						$array[] = $tmp;
					}
					$x++;
				}
			}
		
			$full_list = array_diff( $full_list, $array );
			$tmp = array();
			$full_list = array_merge($tmp,$full_list);
		
			return $full_list;
		}
	}
	/*
	 * @param object &$wb: $wb from frontend or $admin from backend
	 * @return array: list of rw-dirs
	 * @description: returns a list of directories beyound /wb/media which are ReadWrite for current user
	 *
	 *	M.f.i.!	Copy and paste crap!
	 */
	if (!function_exists('media_dirs_rw')) {
		function media_dirs_rw ( &$wb )
		{
			global $database;
			// if user is admin or home-folders not activated then there are no restrictions
			// at first read any dir and subdir from /media
			$full_list = directory_list( WB_PATH.MEDIA_DIRECTORY );
			$allow_list = array();
			if( ($wb->get_user_id() == 1) || !HOME_FOLDERS )
			{
				return $full_list;
			}
			// add own home_folder to allow-list
			if( $wb->get_home_folder() )
			{
				$allow_list[] = $wb->get_home_folder();
			}
			// get groups of current user
			$curr_groups = $wb->get_groups_id();
			// if current user is in admin-group
			if( ($admin_key = array_search('1', $curr_groups)) !== false)
			{
				// remove admin-group from list
				unset($curr_groups[$admin_key]);
				// search for all users where the current user is admin from
				foreach( $curr_groups as $group)
				{
					$sql  = 'SELECT `home_folder` FROM `'.TABLE_PREFIX.'users` ';
					$sql .= 'WHERE (FIND_IN_SET(\''.$group.'\', `groups_id`) > 0) AND `home_folder` <> \'\' AND `user_id` <> '.$wb->get_user_id();
					if( ($res_hf = $database->query($sql)) != null )
					{
						while( $rec_hf = $res_hf->fetchrow() )
						{
							$allow_list[] = $rec_hf['home_folder'];
						}
					}
				}
			}
			$tmp_array = $full_list;
			// create a list for readwrite dir
			$array = array();
			while( sizeof($tmp_array) > 0)
			{
				$tmp = array_shift($tmp_array);
				$x = 0;
				while($x < sizeof($allow_list))
				{
					if(strpos ($tmp,$allow_list[$x])) {
						$array[] = $tmp;
					}
					$x++;
				}
			}
		
			$tmp = array();
			$full_list = array_merge($tmp,$array);
		
			return $full_list;
		}
	}
	
	// Function to create directories
	if (!function_exists('make_dir')) {
		/**
		 * Create directories recursive
		 * 
		 * @param STR $dir_name - directory that should be created
		 * @param OCTAL $dir_mode - access mode
		 * @return BOOL result of operation
		 *
		 * @internal ralf 2011-08-05 - added recursive parameter for mkdir()
		 * @todo ralf 2011-08-05 - checking for !is_dir() is not a good idea, perhaps $dirname 
		 * is not a valid path, i.e. a file - any better ideas? 
		 */
		function make_dir($dir_name, $dir_mode = OCTAL_DIR_MODE) {
			if (!is_dir($dir_name)) {
				$umask = umask(0);
				mkdir($dir_name, $dir_mode, true);
				umask($umask);
				return true;
			} 
			return false;
		} // make_dir()
	}
	
	// Function to chmod files and directories
	/**
	 *	What ever ... i'll give up ...
	 */
	if (!function_exists('change_mode')) {
		function change_mode($name)
		{
			if(OPERATING_SYSTEM != 'windows')
			{
				// Only chmod if os is not windows
				if(is_dir($name))
				{
					$mode = OCTAL_DIR_MODE;
				}
				else
				{
					$mode = OCTAL_FILE_MODE;
				}
		
				if(file_exists($name))
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
		}
	}
	
	// Function to figure out if a parent exists
	if (!function_exists('is_parent')) {
		function is_parent($page_id)
		{
			global $database;
			// Get parent
			$sql = 'SELECT `parent` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$page_id;
			$parent = $database->get_one($sql);
			// If parent isnt 0 return its ID
			if(is_null($parent))
			{
				return false;
			}
			else
			{
				return $parent;
			}
		}
	}
	
	// Function to work out level
	if (!function_exists('level_count')) {
		function level_count($page_id)
		{
			global $database;
			// Get page parent
			$sql = 'SELECT `parent` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$page_id;
			$parent = $database->get_one($sql);
			if($parent > 0)
			{	// Get the level of the parent
				$sql = 'SELECT `level` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$parent;
				$level = $database->get_one($sql);
				return $level+1;
			}
			else
			{
				return 0;
			}
		}
	}
	
	// Function to work out root parent
	function root_parent($page_id)
	{
		global $database;
		// Get page details
		$sql = 'SELECT `parent`, `level` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$page_id;
		$query_page = $database->query($sql);
		$fetch_page = $query_page->fetchRow();
		$parent = $fetch_page['parent'];
		$level = $fetch_page['level'];
		if($level == 1)
		{
			return $parent;
		}
		elseif($parent == 0)
		{
			return $page_id;
		}
		else
		{	// Figure out what the root parents id is
			$parent_ids = array_reverse(get_parent_ids($page_id));
			return $parent_ids[0];
		}
	}
	
	// Function to get page title
	function get_page_title($id)
	{
		global $database;
		// Get title
		$sql = 'SELECT `page_title` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$id;
		$page_title = $database->get_one($sql);
		return $page_title;
	}
	
	// Function to get a pages menu title
	function get_menu_title($id)
	{
		global $database;
		// Get title
		$sql = 'SELECT `menu_title` FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$id;
		$menu_title = $database->get_one($sql);
		return $menu_title;
	}
	
	// Function to get all parent page titles
	function get_parent_titles($parent_id)
	{
		$titles[] = get_menu_title($parent_id);
		if(is_parent($parent_id) != false)
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
		if(is_parent($parent_id) != false)
		{
			$parent_ids = get_parent_ids(is_parent($parent_id));
			$ids = array_merge($ids, $parent_ids);
		}
		return $ids;
	}
	
	// Function to generate page trail
	function get_page_trail($page_id) {
		return implode(',', array_reverse(get_parent_ids($page_id)));
	}
	
	// Function to get all sub pages id's
	function get_subs($parent, $subs)
	{
		// Connect to the database
		global $database;
		// Get id's
		$sql = 'SELECT `page_id` FROM `'.TABLE_PREFIX.'pages` WHERE `parent` = '.$parent;
		$query = $database->query($sql);
		if($query->numRows() > 0)
		{
			while($fetch = $query->fetchRow())
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
	function entities_to_umlauts($string, $charset_out=DEFAULT_CHARSET)
	{
		require_once(WB_PATH.'/framework/functions-utf8.php');
		return entities_to_umlauts2($string, $charset_out);
	}
	
	// Will convert a string in $charset_in encoding to a pure ASCII string with HTML-entities.
	// In case of error the returned string is unchanged, and a message is emitted.
	function umlauts_to_entities($string, $charset_in=DEFAULT_CHARSET)
	{
		require_once(WB_PATH.'/framework/functions-utf8.php');
		return umlauts_to_entities2($string, $charset_in);
	}
	
	// Function to convert a page title to a page filename
	function page_filename($string)
	{
		require_once(WB_PATH.'/framework/functions-utf8.php');
		$string = entities_to_7bit($string);
		// Now remove all bad characters
		$bad = array('\'','"','`','!','@','#','$','%','^','&','*','=','+','|','/','\\',';',':',',','?');
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
		require_once(WB_PATH.'/framework/functions-utf8.php');
		$string = entities_to_7bit($string);
		// Now remove all bad characters
		$bad = array('\'','"','`','!','@','#','$','%','^','&','*','=','+','|','/','\\',';',':',',','?');
		$string = str_replace($bad, '', $string);
		// replace multiple dots in filename to single dot and (multiple) dots at the end of the filename to nothing
		$string = preg_replace(array('/\.+/', '/\.+$/', '/\s/'), array('.', '', '_'), $string);
		// Clean any page spacers at the end of string
		$string = trim($string);
		// Finally, return the cleaned string
		return $string;
	}
	
	// Function to work out a page link
	if(!function_exists('page_link'))
	{
		function page_link($link)
		{
			global $admin;
			return $admin->page_link($link);
		}
	}
	
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
	
	// M.f.i. 	2011-02-17	drp: this one is worse ...
	//				a) test where call from
	//				b) test the circumstances
	//				c) optimize the code as it is .. even the two params at the end!
	
	function create_access_file($filename, $page_id, $level, $opt_cmds = null)
	{
		global $admin, $MESSAGE;
		$pages_path = WB_PATH.PAGES_DIRECTORY;
		$rel_pages_dir = str_replace($pages_path, '', dirname($filename));
		$rel_filename = str_replace($pages_path, '', $filename);
	
	// root_check prevent system directories and importent files from being overwritten if PAGES_DIR = '/'
		$denied = false;
		if(PAGES_DIRECTORY == '') {
			$forbidden  = array(
				'account','admin','framework','include','install',
				'languages','media','modules','pages','search',
				'temp','templates',
				'index.php','config.php','upgrade-script.php'
			);
			$search = explode('/', $rel_filename);
			$denied = in_array($search[1], $forbidden); #! 6 -- why only the first level?
		}
		if( (true === is_writable($pages_path) ) && (false == $denied) ) {

			// First make sure parent folder exists
			$parent_folders = explode('/',$rel_pages_dir);
			$parents = '';
			foreach($parent_folders as $parent_folder)
			{
				if($parent_folder != '/' && $parent_folder != '')
				{
					$parents .= '/'.$parent_folder;
					if(!file_exists($pages_path.$parents))
					{
						make_dir($pages_path.$parents);
						change_mode($pages_path.$parents);
					}
				}
			}
			
			$step_back = str_repeat(
				'../',
				substr_count( $rel_pages_dir, '/')
				+
				(PAGES_DIRECTORY =="" ? 0 : 1)
			);
				
			$content  = '<?php'."\n";
			$content .= "/**\n *\tThis file is autogenerated by LEPTON - Version: ".VERSION."\n";
			$content .= " *\tDo not modify this file!\n */\n";
			$content .= "\t".'$page_id = '.$page_id.';'."\n";
			if($opt_cmds) // #! 3 -- not clear what this meeans at all! and in witch circumstances this 'param' will be make sence?
			{
				if(!is_array($opt_cmds))
				{
					$opt_cmds = explode('!', $opt_cmds);
				}
				foreach($opt_cmds as $command)
				{
					$new_cmd = rtrim(trim($command),';');
					$content .= (preg_match('/include|require/i', $new_cmd)
								? '// *not allowed command >> * '
								: "\t");
					$content .= $new_cmd.';'."\n";
				}
			}
			$content .= "\t".'require(\''.$step_back.'index.php\');'."\n"; #! 4 -- should be require once ...
			$content .= '?>';

			/**
			 *	write the file
			 *
			 */
			$fp = fopen($filename, 'w');
			if ($fp) {
				fwrite($fp, $content, strlen($content) );
				fclose($fp);
				/**
				 *	Chmod the file
				 *
				 */
				change_mode($filename);
				
				/**
				 *	Looking for the index.php inside the current directory.
				 *	If not found - we're just copy the master_index.php from the admin/pages
				 *
				 */
				$temp_index_path = dirname($filename)."/index.php";
				if (!file_exists($temp_index_path)) {
					$origin = ADMIN_PATH."/pages/master_index.php";
					if (file_exists($origin)) copy( $origin, $temp_index_path);
				}
				
			} else {
				
				/**
				 *	M.f.i	drp:	as long as we've got no key for this situation inside the languagefiles
				 *					we're in the need to make a little addition to the given one, to get it unique for trouble-shooting.
				 */
				$admin->print_error($MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE']."<br />Problems while trying to open the file!");
				return false;
			}
			return true;
		
		} else {
			$admin->print_error($MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE']);
			return false;
		}
	}
	
	// Function for working out a file mime type (if the in-built PHP one is not enabled)
	if(!function_exists('mime_content_type'))
	{
		function mime_content_type($filename)
		{
			$mime_types = array(
				'txt'	=> 'text/plain',
				'htm'	=> 'text/html',
				'html'	=> 'text/html',
				'php'	=> 'text/html',
				'css'	=> 'text/css',
				'js'	=> 'application/javascript',
				'json'	=> 'application/json',
				'xml'	=> 'application/xml',
				'swf'	=> 'application/x-shockwave-flash',
				'flv'	=> 'video/x-flv',
	
				// images
				'png'	=> 'image/png',
				'jpe'	=> 'image/jpeg',
				'jpeg'	=> 'image/jpeg',
				'jpg'	=> 'image/jpeg',
				'gif'	=> 'image/gif',
				'bmp'	=> 'image/bmp',
				'ico'	=> 'image/vnd.microsoft.icon',
				'tiff'	=> 'image/tiff',
				'tif'	=> 'image/tiff',
				'svg'	=> 'image/svg+xml',
				'svgz'	=> 'image/svg+xml',
	
				// archives
				'zip'	=> 'application/zip',
				'rar'	=> 'application/x-rar-compressed',
				'exe'	=> 'application/x-msdownload',
				'msi'	=> 'application/x-msdownload',
				'cab'	=> 'application/vnd.ms-cab-compressed',
	
				// audio/video
				'mp3'	=> 'audio/mpeg',
				'mp4'	=> 'audio/mpeg',
				'qt'	=> 'video/quicktime',
				'mov'	=> 'video/quicktime',
	
				// adobe
				'pdf'	=> 'application/pdf',
				'psd'	=> 'image/vnd.adobe.photoshop',
				'ai'	=> 'application/postscript',
				'eps'	=> 'application/postscript',
				'ps'	=> 'application/postscript',
	
				// ms office
				'doc'	=> 'application/msword',
				'rtf'	=> 'application/rtf',
				'xls'	=> 'application/vnd.ms-excel',
				'ppt'	=> 'application/vnd.ms-powerpoint',
	
				// open office
				'odt'	=> 'application/vnd.oasis.opendocument.text',
				'ods'	=> 'application/vnd.oasis.opendocument.spreadsheet',
			);
	
			$temp = explode('.',$filename);
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
		}
	}
	
	// Generate a thumbnail from an image
	function make_thumb($source, $destination, $size)
	{
		// Check if GD is installed
		if(extension_loaded('gd') && function_exists('imageCreateFromJpeg'))
		{
			// First figure out the size of the thumbnail
			list($original_x, $original_y) = getimagesize($source);
			if ($original_x > $original_y)
			{
				$thumb_w = $size;
				$thumb_h = $original_y*($size/$original_x);
			}
			if ($original_x < $original_y)
			{
				$thumb_w = $original_x*($size/$original_y);
				$thumb_h = $size;
			}
			if ($original_x == $original_y)
			{
				$thumb_w = $size;
				$thumb_h = $size;	
			}
			// Now make the thumbnail
			$source = imageCreateFromJpeg($source);
			$dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);
			imagecopyresampled($dst_img,$source,0,0,0,0,$thumb_w,$thumb_h,$original_x,$original_y);
			imagejpeg($dst_img, $destination);
			// Clear memory
			imagedestroy($dst_img);
			imagedestroy($source);
		   // Return true
			return true;
		} else {
			return false;
		}
	}
	
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
		if( ($who == '') || ($action == '') || (preg_match( '/[^0-7]/', (string)$octal_value )) )
		{
			return false; // invalid argument, so return false
		}
		$right_mask = octdec($octal_value);  // convert into a decimal-integer to be sure having a valid value
		switch(strtolower($action[0])) // get action from first char of $action
		{  // set the $action related bit in $action_mask
			case 'r':
				$action_mask = 4; // set read-bit only (2^2)
				break;
			case 'w':
				$action_mask = 2; // set write-bit only (2^1)
				break;
			case 'e':
			case 'x':
				$action_mask = 1; // set execute-bit only (2^0)
				break;
			default:
				return false; // undefined action name, so return false
		}
		switch(strtolower($who[0])) // get who from first char of $who
		{  // shift action-mask into the right position
			case 'u':
				$action_mask <<= 3; // shift left 3 bits
			case 'g':
				$action_mask <<= 3; // shift left 3 bits
			case 'o':
				/* NOP */
				break;
			default:
				return false; // undefined who, so return false
		}
		return( ($right_mask & $action_mask) != 0 ); // return result of binary-AND
	}
	
	// Function to delete a page
	function delete_page($page_id)
	{
		global $admin, $database, $MESSAGE;
		// Find out more about the page
		// $database = new database();
		$sql  = 'SELECT `page_id`, `menu_title`, `page_title`, `level`, `link`, `parent`, `modified_by`, `modified_when` ';
		$sql .= 'FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$page_id;
		$results = $database->query($sql);
		if($database->is_error())    { $admin->print_error($database->get_error()); }
		if($results->numRows() == 0) { $admin->print_error($MESSAGE['PAGES_NOT_FOUND']); }
		$results_array = $results->fetchRow();
		$parent     = $results_array['parent'];
		$level      = $results_array['level'];
		$link       = $results_array['link'];
		$page_title = $results_array['page_title'];
		$menu_title = $results_array['menu_title'];
		
		// Get the sections that belong to the page
		$sql = 'SELECT `section_id`, `module` FROM `'.TABLE_PREFIX.'sections` WHERE `page_id` = '.$page_id;
		$query_sections = $database->query($sql);
		if($query_sections->numRows() > 0)
		{
			while($section = $query_sections->fetchRow())
			{
				// Set section id
				$section_id = $section['section_id'];
				// Include the modules delete file if it exists
				if(file_exists(WB_PATH.'/modules/'.$section['module'].'/delete.php'))
				{
					include(WB_PATH.'/modules/'.$section['module'].'/delete.php');
				}
			}
		}
		// Update the pages table
		$sql = 'DELETE FROM `'.TABLE_PREFIX.'pages` WHERE `page_id` = '.$page_id;
		$database->query($sql);
		if($database->is_error())
		{
			$admin->print_error($database->get_error());
		}
		// Update the sections table
		$sql = 'DELETE FROM `'.TABLE_PREFIX.'sections` WHERE `page_id` = '.$page_id;
		$database->query($sql);
		if($database->is_error()) {
			$admin->print_error($database->get_error());
		}
		// Include the ordering class or clean-up ordering
		include_once(WB_PATH.'/framework/class.order.php');
		$order = new order(TABLE_PREFIX.'pages', 'position', 'page_id', 'parent');
		$order->clean($parent);
		// Unlink the page access file and directory
		$directory = WB_PATH.PAGES_DIRECTORY.$link;
		$filename = $directory.PAGE_EXTENSION;
		$directory .= '/';
		if(file_exists($filename))
		{
			if(!is_writable(WB_PATH.PAGES_DIRECTORY.'/'))
			{
				$admin->print_error($MESSAGE['PAGES_CANNOT_DELETE_ACCESS_FILE']);
			}
			else
			{
				unlink($filename);
				if( file_exists($directory) &&
				   (rtrim($directory,'/') != WB_PATH.PAGES_DIRECTORY) &&
				   (substr($link, 0, 1) != '.'))
				{
					rm_full_dir($directory);
				}
			}
		}
	}
	
	/**
	 *	Load module-info into the current DB
	 *
	 *	@param	string	Any valid directory(-path)
	 *	@param	bool	Call the install-script of the module? Default: false
	 *
	 */
	function load_module($directory, $install = false)
	{
		global $database, $admin, $MESSAGE ;

		if(is_dir($directory) && file_exists($directory."/info.php"))
		{
			global $module_name , $module_license , $module_author , $module_directory,
            $module_version, $module_function, $module_description, $module_platform,
            $module_guid, $lepton_platform;
 /**
  * @internal frankH 2011-08-02 - added $lepton_platform, can be removed when addons are built only for LEPTON
  */
			if (isset($lepton_platform) && ($lepton_platform != '')) $module_platform = $lepton_platform;
			
			require_once($directory."/info.php");
			if(isset($module_name))
			{
				
				$module_function = strtolower($module_function);
				
				// Check that it doesn't already exist
				$sqlwhere = "WHERE `type` = 'module' AND `directory` = '".$module_directory."'";
				$sql  = "SELECT COUNT(*) FROM `".TABLE_PREFIX."addons` ".$sqlwhere;
				if( $database->get_one($sql) ) {
					$sql  = "UPDATE `".TABLE_PREFIX."addons` SET ";
				} else {
					$sql  = "INSERT INTO `".TABLE_PREFIX."addons` SET ";
					$sqlwhere = '';
				}
				
				$sql .= "`directory` = '".mysql_real_escape_string($module_directory)."',";
				$sql .= "`name` = '".mysql_real_escape_string($module_name)."',";
				$sql .= "`description`= '".mysql_real_escape_string($module_description)."',";
				$sql .= "`type`= 'module',";
				$sql .= "`function` = '".mysql_real_escape_string(strtolower($module_function))."',";
				$sql .= "`version` = '".mysql_real_escape_string($module_version)."',";
				$sql .= "`platform` = '".mysql_real_escape_string($module_platform)."',";
				$sql .= "`author` = '".mysql_real_escape_string($module_author)."',";
				$sql .= "`license` = '".mysql_real_escape_string($module_license)."'";
				if ( isset( $module_guid ) ) {
				    $sql .= ", `guid` = '".mysql_real_escape_string($module_guid)."'";
        }
				$sql .= $sqlwhere;
				
				$database->query($sql);
				
				if($database->is_error()) $admin->print_error( $database->get_error() );
				
				/**
				 *	Run installation script
				 *
				 */
				if($install == true) {
					if(file_exists($directory.'/install.php')) require($directory.'/install.php');
				}
			}
		}
	}
	
	/**
	 *	Load template-info into the DB.
	 *
	 *	@param	string	Any valid directory
	 *
	 *	@notice	Keep in mind, that the variable-check is here the same as in
	 *			File: admins/templates/install.php. 
	 *			The reason is, that it could be possible to call this function from 
	 *			another script/codeblock direct. So in thees circumstances where would no
	 *			test at all, and the possibility to entry wrong data is given.
	 *
	 */
	function load_template($directory)
	{
		global $database, $admin, $MESSAGE;
		
		if(is_dir($directory) && file_exists($directory.'/info.php'))
		{
			global $template_license, $template_directory, $template_author, $template_version,
			$template_function, $template_description, $template_platform, $template_name, $template_guid;
				
				// Check that it doesn't already exist
				$sqlwhere = "WHERE `type` = 'template' AND `directory` = '".$template_directory."'";
				$sql  = "SELECT COUNT(*) FROM `".TABLE_PREFIX."addons` ".$sqlwhere;
				if( $database->get_one($sql) ) {
					$sql  = "UPDATE `".TABLE_PREFIX."addons` SET ";
				} else {
					$sql  = "INSERT INTO `".TABLE_PREFIX."addons` SET ";
					$sqlwhere = "";
				}
				$sql .= "`directory` = '".mysql_real_escape_string( $template_directory )."',";
				$sql .= "`name` = '".mysql_real_escape_string( $template_name) ."',";
				$sql .= "`description`= '".mysql_real_escape_string($template_description)."',";
				$sql .= "`type`= 'template',";
				$sql .= "`function` = '".mysql_real_escape_string($template_function)."',";
				$sql .= "`version` = '".mysql_real_escape_string($template_version)."',";
				$sql .= "`platform` = '".mysql_real_escape_string($template_platform)."',";
				$sql .= "`author` = '".mysql_real_escape_string($template_author).'\', ';
				$sql .= "`license` = '".mysql_real_escape_string($template_license)."', ";
				if (isset($template_guid)) {
				    $sql .= "`guid` = '".mysql_real_escape_string($template_guid)."' ";
				}
				else {
				    $sql .= "`guid` = '' ";
				}
				$sql .= $sqlwhere;
				
				$database->query($sql);
				
				if($database->is_error()) $admin->print_error( $database->get_error() );

			}
	}
	
	/**
	 *	Load language-info into the current DB
	 *
	 *	@param	string	Any valid path.
	 *
	 */
	function load_language($file)
	{
		global $database,$admin;
		if (file_exists($file) && preg_match('#^([A-Z]{2}.php)#', basename($file)))
		{
			require($file);
			if(isset($language_name))
			{
				if(	(!isset($language_license))		||
					(!isset($language_code))		||
					(!isset($language_version))		||
					(!isset($language_guid))
					)
				{
				  $admin->print_error( $MESSAGE["LANG_MISSING_PARTS_NOTICE"], $language_name );
				}
				
				// Check that it doesn't already exist
				$sqlwhere = 'WHERE `type` = \'language\' AND `directory` = \''.$language_code.'\'';
				$sql  = 'SELECT COUNT(*) FROM `'.TABLE_PREFIX.'addons` '.$sqlwhere;
				if( $database->get_one($sql) ) {
					$sql  = 'UPDATE `'.TABLE_PREFIX.'addons` SET ';
				} else {
					$sql  = 'INSERT INTO `'.TABLE_PREFIX.'addons` SET ';
					$sqlwhere = '';
				}
				$sql .= '`directory` = \''.$language_code.'\', ';
				$sql .= '`name` = \''.$language_name.'\', ';
				$sql .= '`type`= \'language\', ';
				$sql .= '`version` = \''.$language_version.'\', ';
				$sql .= '`platform` = \''.$language_platform.'\', ';
				$sql .= '`author` = \''.addslashes($language_author).'\', ';
				$sql .= '`license` = \''.addslashes($language_license).'\', ';
				$sql .= '`guid` = \''.$language_guid.'\', ';
				$sql .= '`description` = \'\'  ';
				$sql .= $sqlwhere;
				$database->query($sql);
				
				if($database->is_error()) $admin->print_error( $database->get_error() );

			}
		}
	}

	/**
	 *	Update the module informations in the DB
	 *
	 *	@param	string	Name of the modul-directory
	 *	@param	bool	Optional boolean to run the upgrade-script of the module.
	 *
	 *	@return	nothing
	 *
	 */
	function upgrade_module( $directory, $upgrade = false ) {
	
		global $database, $admin, $MESSAGE;
		global $module_license, $module_author  , $module_name, $module_directory,
           $module_version, $module_function, $module_guid, $module_description,
           $module_platform;

    $fields = array(
				'version'	    => $module_version,
				'description' => mysql_real_escape_string($module_description),
				'platform'	  => $module_platform,
				'author'	    => mysql_real_escape_string($module_author),
				'license'	    => mysql_real_escape_string($module_license),
				'guid'		    => mysql_real_escape_string($module_guid)
			);

			$sql  = 'UPDATE `'.TABLE_PREFIX.'addons` SET ';
			foreach($fields as $key=>$value) $sql .= "`".$key."`='".$value."',";
			$sql = substr($sql, 0, -1)." WHERE `directory`= '".$module_directory."'";

			$database->query($sql);

			if($database->is_error()) $admin->print_error( $database->get_error() );
			
	}	// end of function 'upgrade_module'

	// extracts the content of a string variable from a string (save alternative to including files)
	if(!function_exists('get_variable_content'))
	{
		function get_variable_content($search, $data, $striptags=true, $convert_to_entities=true)
		{
			$match = '';
			// search for $variable followed by 0-n whitespace then by = then by 0-n whitespace
			// then either " or ' then 0-n characters then either " or ' followed by 0-n whitespace and ;
			// the variable name is returned in $match[1], the content in $match[3]
			if (preg_match('/(\$' .$search .')\s*=\s*("|\')(.*)\2\s*;/', $data, $match))
			{
				if(strip_tags(trim($match[1])) == '$' .$search)
				{
					// variable name matches, return it's value
					$match[3] = ($striptags == true) ? strip_tags($match[3]) : $match[3];
					$match[3] = ($convert_to_entities == true) ? htmlentities($match[3]) : $match[3];
					return $match[3];
				}
			}
			return false;
		}
	}

	/**
	 *	Try to get the current version of a given Modul.
	 *	
	 *	@param	string	$modulname: like saved in addons directory
	 *	@param	boolean	$source: true reads from database, false from info.php
	 *	@return	string	the version as string, if not found returns null
	 *
	 */
	function get_modul_version($modulname, $source = true) {
		global $database;
		
		$version = null;
		
		if( $source != true ) {
		
			$sql = "SELECT `version` FROM `".TABLE_PREFIX."addons` WHERE `directory`='".$modulname."'";
			$version = $database->get_one( $sql );
		
		} else {
		
			$info_file = WB_PATH.'/modules/'.$modulname.'/info.php';
			if(file_exists($info_file)) {
				require($info_file);
				$version = &$module_version;
			}
		}
		return $version;
	}
	
	/**
	  * Generate a globally unique identifier (GUID)
	  * Uses COM extension under Windows otherwise
	  * create a random GUID in the same style
	  * @return STR GUID
	  */
	function createGUID() {
	  if (function_exists('com_create_guid')) {
		$guid = com_create_guid();
		$guid = strtolower($guid); 
		if (strpos($guid, '{') == 0) {
		  $guid = substr($guid, 1); 
		}
		if (strpos($guid, '}') == strlen($guid)-1) {
		  $guid = substr($guid, 0, strlen($guid)-1);
		}
		return $guid;
	  } else {
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		  mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
		  mt_rand( 0, 0x0fff ) | 0x4000,
		  mt_rand( 0, 0x3fff ) | 0x8000,
		  mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
	  }
	} // createGUID()
	
	function checkIPv4address($ip_addr) {
	  if(preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/",$ip_addr)) {
		$parts=explode(".",$ip_addr);
		foreach($parts as $ip_parts) {
		  if(intval($ip_parts)>255 || intval($ip_parts)<0)
		  return false; 
		}
		return true;
	  }
	  else
		return false;	
	} // checkIPv4address()
	
	/**
	 *	As for some special chars, e.g. german-umlauts, inside js-alerts we are in the need to escape them.
	 *	Keep in mind, that you will to have unescape them befor you use them inside a js!
	 *
	 */
	function js_alert_encode($string) {
		
		$entities = array(
			'&auml;'	=> "%E4",
			'&Auml;'	=> "%C4",
			'&ouml;'	=> "%F6",
			'&Ouml;'	=> "%D6",
			'&uuml;'	=> "%FC",
			'&Uuml;'	=> "%DC",
			'&szlig;'	=> "%DF",
			'&euro;'	=> "%u20AC",
			'$'			=> "%24"
		);
		
		return str_replace(
			array_keys( $entities ),
			array_values( $entities ),
			$string
		);
	}

} // end .. if functions is loaded ... keep in mind, that we've gone a lone way since line 38 of this code!

?>