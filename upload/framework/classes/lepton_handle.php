<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */
 
class LEPTON_handle
{
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
	 *	$result = LEPTON_handle::install_table($table_name, $table_fields);
	 *
	 *	@endcode
	 *	@return boolean true if successful
	 */	
	static public function install_table ($table_name='',$table_fields='') {
		if (($table_name == '') || ($table_fields == '')) {
			return false;
		}
		$database = LEPTON_database::getInstance();
		$table = TABLE_PREFIX .$table_name; 
		$database->simple_query("CREATE TABLE `".$table."`  (" .$table_fields. ") " );
		
		// check for errors
		if ($database->is_error())
		{
			die($database->get_error() );
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 *	uninstall table
	 *	@param string for table_name 
	 *
	 *	@code{.php}
	 *	$table_name = 'mod_test';
	 *	$result = LEPTON_handle::uninstall_table($table_name);
	 *
	 *	@endcode
	 *	@return boolean true if successful
	 */	
	static public function uninstall_table ($table_name='') {
		if ($table_name == '') {
			return false;
		}
		$database = LEPTON_database::getInstance();
		$table = TABLE_PREFIX .$table_name; 
		$database->simple_query("DROP TABLE `".$table."` ");
		
		// check for errors
		if ($database->is_error())
		{
			die($database->get_error() );
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 *	rename table
	 *	@param string for table_name source
	 *	@param string for table_name target	 
	 *
	 *	@code{.php}
	 *	$table_name = 'mod_test';
	 *	$result = LEPTON_handle::rename_table($table_name);
	 *
	 *	@endcode
	 *	@return boolean true if successful
	 */	
	static public function rename_table ($table_name ='') {
		if (($table_name == '') ) {
			return false;
		}
		$database = LEPTON_database::getInstance();
		$table_source = TABLE_PREFIX .$table_name; 
		$table_target = TABLE_PREFIX .'xsik_'.$table_name; 
		$database->simple_query("RENAME TABLE `".$table_source."` TO `".$table_target."` ");
		
		// check for errors
		if ($database->is_error())
		{
			die($database->get_error() );
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
	static public function delete_obsolete_files ($file_names=array()) {
		foreach ($file_names as $del)
		{
			$temp_path = LEPTON_PATH . $del;
			if (file_exists($temp_path)) 
			{
				$result = unlink ($temp_path);
				if (false === $result) {
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
	 *	LEPTON_handle::delete_obsolete_directory ($directory_names);
	 *
	 *	@endcode
	 *	@return nothing
	 */	
	static public function delete_obsolete_directories ($directory_names=array()) {
		LEPTON_tools::register('rm_full_dir');
		foreach ($directory_names as $del)
		{
			$temp_path = LEPTON_PATH . $del;
			if (file_exists($temp_path)) 
			{
				$result = rm_full_dir ($temp_path);
				if (false === $result) {
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
	static public function rename_directories ($directory_names=array()) {
		LEPTON_tools::register('rename_recursive_dirs');
		foreach ($directory_names as $rename)
		{
			$source_path = LEPTON_PATH . $rename['source'];
			$target_path = LEPTON_PATH . $rename['target'];
			if (file_exists($source_path)) 
			{			

				$result = rename_recursive_dirs( $source_path, $target_path );
				if (false === $result) {
				echo "Cannot rename file ".$source_path.". Please check directory permissions and ownership manually.";
				}			
				
			}			
		}
	}

	
	
	
	
} // end class
