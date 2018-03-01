<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

/**
 *  Core class for some basic actions to handle, e.g. install or drop tables, 'register' framework-functions etc.
 *
 */ 
class LEPTON_handle
{
	/**
	 *  Display errors (e.g. from LEPTON_database query results)?
	 *
	 *	@property bool	For the use of LEPTON_tools::display and try to go on?.
	 *
	 */
	static public $display_errors = true;
	
	/**
	 *	Method to change the display_errors.
	 *  As the property is static we can't set it "outside" direct." 
	 *
	 *	@param	boolean True to use LEPTON_tools::display.
	 *
	 */
	static public function setDisplay( $bUseDisplay=true )
	{
		self::$display_errors = (bool) $bUseDisplay;
	}
	
	/**
	 *	install table
	 *	@param string for table_name
	 *	@param string for table_fields	 
	 *
	 *	@code{.php}
	 *	$table_name = 'mod_test';
	 *	$table_fields='
	 *		`id` INT(10) UNSIGNED NOT NULL,
	 *		`edit_perm` VARCHAR(50) NOT NULL,
	 *		`view_perm` VARCHAR(50) NOT NULL,
	 *		PRIMARY KEY ( `id` )
	 *	';
	 *	LEPTON_handle::install_table($table_name, $table_fields);
	 *
	 *	@endcode
	 *	@return boolean True if successful, otherwise false.
	 *
	 */	
	static public function install_table($table_name='',$table_fields='') {
		if (($table_name == '') || ($table_fields == '')) {
			return false;
		}
		$database = LEPTON_database::getInstance();
		$table = TABLE_PREFIX .$table_name; 
		$database->simple_query("CREATE TABLE `".$table."`  (" .$table_fields. ") " );
		
		// check for errors
		if ($database->is_error())
		{
			if( true === self::$display_errors )
			{
			    echo LEPTON_tools::display( $database->get_error(), "div", "ui message red" );
			}
			
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 *	insert data into table
	 *	@param string for table_name
	 *	@param string for table_fields	 
	 *
	 *	@code{.php}
	 *	$table_name = 'mod_test';
	 *	$field_values="
	 *		(1, 'module_order', 'wysiwyg', ''),
	 *		(2, 'max_excerpt', '15', ''),
	 *		(3, 'time_limit', '0', '')
	 *	";
	 *	LEPTON_handle::insert_values($table_name, $field_values);
	 *
	 *	@endcode
	 *	@return boolean True if successful, otherwise false.
	 *
	 */	
	static public function insert_values($table_name='',$field_values ='') {
		if (($table_name == '') || ($field_values =='')) {
			return false;
		}
		$database = LEPTON_database::getInstance();
		$table = TABLE_PREFIX .$table_name; 
		$database->simple_query("INSERT INTO `".$table."`  VALUES ".$field_values." ");
		
		// check for errors
		if ($database->is_error())
		{
			if( true === self::$display_errors )
			{
			    echo LEPTON_tools::display( $database->get_error(), "div", "ui message red" );
			}
			
			return false;
		} else {
			return true;
		}
	}	
	
	
	/**
	 *	drop table
	 *	@param string for table_name 
	 *
	 *	@code{.php}
	 *	$table_name = 'mod_test';
	 *	LEPTON_handle::drop_table($table_name);
	 *
	 *	@endcode
	 *	@return boolean True if successful.
	 *
	 */	
	static public function drop_table($table_name='') {
		if ($table_name == '') {
			return false;
		}
		$database = LEPTON_database::getInstance();
		$table = TABLE_PREFIX .$table_name; 

		$database->simple_query("DROP TABLE IF EXISTS `".$table."` ");
		
		// check for errors
		if ($database->is_error())
		{
			if( true === self::$display_errors )
			{
			    echo LEPTON_tools::display( $database->get_error(), "div", "ui message red" );
			}

			return false;

		} else {

			return true;
		}
	}
	
	/**
	 *	rename table
	 *	@param string for table_name
	 *
	 *	@code{.php}
	 *	LEPTON_handle::rename_table($table_name);
	 *	@endcode
	 *	@return boolean true if successful
	 *
	 */	
	static public function rename_table($table_name ='') {
		if (($table_name == '') ) {
			return false;
		}
		$database = LEPTON_database::getInstance();
		$table_source = TABLE_PREFIX .$table_name; 
		$table_target = TABLE_PREFIX .'xsik_'.$table_name; 
		self::drop_table('xsik_'.$table_name);
		$database->simple_query("RENAME TABLE `".$table_source."` TO `".$table_target."` ");
		
		// check for errors
		if ($database->is_error())
		{
			if( true === self::$display_errors )
			{
			    echo LEPTON_tools::display( $database->get_error(), "div", "ui message red" );
			}

			return false;
		} else {
			return true;
		}
	}

	/**
	 *	create sik table
	 *	@param string for table_name
	 *
	 *	@code{.php}
	 *	LEPTON_handle::create_sik_table($table_name);
	 *	@endcode
	 *	@return boolean true if successful
	 */	
	static public function create_sik_table($table_name ='') {
		if (($table_name == '') ) {
			return false;
		}
		$database = LEPTON_database::getInstance();
		$table_source = TABLE_PREFIX .$table_name; 
		$table_target = TABLE_PREFIX .'xsik_'.$table_name; 
		self::drop_table('xsik_'.$table_name);  // keep in mind, that drop_table adds the table_prefix
		$database->simple_query("CREATE TABLE `".$table_target."` LIKE `".$table_source."`");
		$database->simple_query("INSERT INTO `".$table_target."` SELECT * FROM `".$table_source."`");		
		
		// check for errors
		if ($database->is_error())
		{
			if( true === self::$display_errors )
			{
			    echo LEPTON_tools::display( $database->get_error(), "div", "ui message red" );
			}

			return false;
		} else {
			return true;
		}
	}

	/**
	 *	delete obsolete files
	 *	@param array linear array with filenames to delete 
	 *
	 *	@code{.php}
	 *	$file_names = array(
	 *	'/modules/lib_r_filemanager/filemanager/js/ZeroClipboard.swf'
	 *	);
	 *	LEPTON_handle::delete_obsolete_files ($file_names);
	 *
	 *	@endcode
	 *	@return nothing
	 */	
	static public function delete_obsolete_files($file_names=array()) {
		if(is_string($file_names)) {
			$file_names = array($file_names);
		}
		foreach ($file_names as $del)
		{
			$temp_path = LEPTON_PATH . $del;
			if (file_exists($temp_path)) 
			{
				$result = unlink($temp_path);
				if (false === $result)
				{
                    echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
				}
			}
		}			
	}

	/**
	 *	delete obsolete directories
	 *	@param array linear array with directory_names to delete 
	 *
	 *	@code{.php}
	 *	$directory_names = array(
	 *	'/modules/lib_r_filemanager/thumbs'
	 *	);
	 *	LEPTON_handle::delete_obsolete_directories($directory_names);
	 *
	 *	@endcode
	 *	@return nothing
	 */	
	static public function delete_obsolete_directories($directory_names=array()) {
		self::register('rm_full_dir');
		if(is_string($directory_names)) {
			$directory_names = array($directory_names);
		}
		foreach ($directory_names as $del)
		{
			$temp_path = LEPTON_PATH . $del;
			if (file_exists($temp_path)) 
			{
				$result = rm_full_dir($temp_path);
				if (false === $result)
				{
                    echo "Cannot delete directory ".$temp_path.". Please check directory permissions and ownership or deleted directories manually.";
				}
			}
		}			
	} 

	/**
	 *	rename recursive directories
	 *	@param array linear array with directory_names to rename as assoziative array
	 *
	 *	@code{.php}
	 *	$directory_names = array(
	 *		array ('source' =>'old_path1', 'target'=>'new_path1'),
	 *		array ('source' =>'old_path2', 'target'=>'new_path2')
	 *	);
	 *	LEPTON_handle::rename_directory ($directory_names);
	 *
	 *	@endcode
	 *	@return nothing
	 */	
	static public function rename_directories($directory_names=array()) {
		self::register('rename_recursive_dirs');
		foreach ($directory_names as $rename)
		{
			$source_path = LEPTON_PATH . $rename['source'];
			$target_path = LEPTON_PATH . $rename['target'];
			if (file_exists($source_path)) 
			{			

				$result = rename_recursive_dirs($source_path, $target_path);
				if (false === $result)
				{
				    echo "Cannot rename file ".$source_path.". Please check directory permissions and ownership manually.";
				}			
				
			}			
		}
	}

	/**
	 *	include files
	 *	@param array linear array with filenames to include 
	 *
	 *	@code{.php}
	 *	$file_names = array(
	 *	'/framework/summary.frontend_functions.php'
	 *	);
	 *	LEPTON_handle::include_files ($file_names);
	 *
	 *	@endcode
	 *	@return nothing
	 */	
	static public function include_files($file_names=array()) {
		if(is_string($file_names)) {
			$file_names = array($file_names);
		}		
		foreach ($file_names as $req)
		{
			$temp_path = LEPTON_PATH . $req;
			if (file_exists($temp_path)) 
			{
				$result = require_once $temp_path;
				if (false === $result)
				{
				    die ("<pre class='ui message'>\nCan't include: ".$temp_path."\n</pre>");
				}
			}
		}			
	}	

	
	/**
	 *	include files if exist and end process of start file
	 *	@param array linear array with filenames to include 
	 *
	 *	@code{.php}
	 *	$file_names = array(
	 *	'/backend/access/index.php'
	 *	);
	 *	LEPTON_handle::require_alternative($file_names);
	 *
	 *	@endcode
	 *	@return nothing
	 */	
	static public function require_alternative($file_names=array()) {
		if(is_string($file_names)) {
			$file_names = array($file_names);
		}		
		foreach ($file_names as $req)
		{
			$temp_path = LEPTON_PATH . $req;
			if (file_exists($temp_path)) 
			{
				$result = require_once $temp_path;
				die();
			}
		}			
	}	
	
	/**
	 *	upgrade modules
	 *	@param array linear array with moduel_names to update 
	 *
	 *	@code{.php}
	 *	$module_names = array(
	 *	'code2'
	 *	);
	 *	LEPTON_handle::upgrade_modules($module_names);
	 *
	 *	@endcode
	 *	@return nothing
	 */	
	static public function upgrade_modules($module_names=array()) {
		if(is_string($module_names)) {
			$module_names = array($module_names);
		}			
		foreach ($module_names as $update)	
		{
			$temp_path = LEPTON_PATH . "/modules/" . $update . "/upgrade.php";
			if (file_exists($temp_path)) 
			{
				$result = require_once $temp_path;
				if (false === $result)
				{
				    die ('ERROR: file is missing: <b> '.$temp_path.' </b>.');
				}
			}
		}			
	}

	
	/**
	 *	install droplets
	 *	@param string for module name
	 *	@param mixed string/array for zip name
	 *
	 *	@code{.php}
	 *	$module_name = 'droplets';
	 *	$zip_names = array(
	 *	'droplet_LoginBox'
	 *	);	 
	 *	LEPTON_handle::install_droplets($module_name, $zip_names);
	 *
	 *	@endcode
	 *	@return nothing
	 */	
	static public function install_droplets($module_name='',$zip_names=array()) {
		require_once LEPTON_PATH.'/modules/droplets/functions.php';
		if(is_string($zip_names)) {
			$zip_names = array($zip_names);
		}			
		foreach ($zip_names as $to_install)	
		{
			$temp_path = LEPTON_PATH . "/modules/" . $module_name . "/install/".$to_install.".zip";
			if (file_exists($temp_path)) 
			{
				$result = droplet_install($temp_path, LEPTON_PATH . '/temp/unzip/');
				if(count ($result['errors']) > 0)
				{
                    die ('ERROR: file is missing: <b> '.(implode('<br />\n', $result['errors'])).' </b>.');
				}
			}
		}
		self::delete_obsolete_directories("/modules/" . $module_name . "/install");		
	}	


	/**
	 *	uninstall droplets
	 *	@param string for module name
	 *	@param mixed string/array for zip name
	 *
	 *	@code{.php}
	 *	$droplet_names = array(
	 *	'check-css'
	 *	);	 
	 *	LEPTON_handle::uninstall_droplets($droplet_names);
	 *
	 *	@endcode
	 *	@return nothing
	 */	
	static public function uninstall_droplets($droplet_names=array()) {
		if(is_string($droplet_names)) {
			$droplet_names = array($droplet_names);
		}
		$database = LEPTON_database::getInstance();
		
		foreach ($droplet_names as $to_uninstall)
		{
			$to_delete = array();
			$database->execute_query(
				"SELECT `id` FROM ".TABLE_PREFIX."mod_droplets WHERE `name` = '".$to_uninstall."' ",
				true,
				$to_delete,
				false
            );
			$database->simple_query("DELETE FROM `".TABLE_PREFIX."mod_droplets` WHERE `id` = ".$to_delete['id'] );
			$database->simple_query("DELETE FROM `".TABLE_PREFIX."mod_droplets_permissions` WHERE `id` = ".$to_delete['id'] );
		}
	}
	
    /**
	 *	Static method to "require" a (LEPTON-) internal function file 
	 *
	 *	@param	string	A function name, and/or an array with function-names
	 *
	 *	example given:
	 *	@code{.php}
	 *
	 *		//	one single function
	 *		LEPTON_handle::register( "get_menu_title" );
	 *
	 *		//	a set of functions
	 *		LEPTON_handle::register( "get_menu_title", "page_tree", "js_alert_encode" );
	 *
	 *		//	a set of function names inside an array
	 *		$all_needed= array("get_menu_title", "page_tree", "js_alert_encode" );
	 *		LEPTON_handle::register( $all_needed, "rm_full_dir" );
	 *
	 *	@endcode
	 *
	 */
	static function register() {
		
		if( 0 === func_num_args() ) return false;
		
		$all_args = func_get_args();
		foreach($all_args as &$param)
		{	
			if( true === is_array($param) )
			{
				foreach($param as $ref) self::register( $ref );
			} else {
			
				if(!function_exists($param))
				{
					$lookUpPath = LEPTON_PATH."/framework/functions/function.".$param.".php";
					if(file_exists($lookUpPath)) require_once $lookUpPath;
				}
			}
		}
		return true;
	}	
	
} // end class
