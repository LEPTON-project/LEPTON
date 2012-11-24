<?php

/**
 *
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2012 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

if ( ! class_exists( 'LEPTON_Object', false ) ) {
     include LEPTON_PATH . '/framework/lepton/object.php'; 
}

if ( ! class_exists( 'LEPTON_Helper_Directory', false ) ) {
	class LEPTON_Helper_Directory extends LEPTON_Object
	{
	
	    protected $recurse = true;
	    protected $prefix  = NULL;
	    protected $suffix_filter = array();
	    protected $skip_dirs     = array();
	    
	    /**
	     * shortcut method for scanDirectory( $dir, $remove_prefix, true, true )
	     **/
		public function getFiles( $dir, $remove_prefix = NULL )
		{
		    return $this->scanDirectory( $dir, true, true, $remove_prefix );
		}   // end function getFiles()
		
		/**
	     * shortcut method for scanDirectory( $dir, $remove_prefix, false, false )
	     **/
		public function getDirectories( $dir, $remove_prefix = NULL )
		{
		    return $this->scanDirectory( $dir, false, false, $remove_prefix );
		}   // end function getFiles()
		
	    /**
	     * shortcut method for scanDirectory( $dir, $remove_prefix, true, true, array('php') )
	     **/
		public function getPHPFiles( $dir, $remove_prefix = NULL )
		{
		    return $this->scanDirectory( $dir, true, true, $remove_prefix, array('php') );
		}   // end function getPHPFiles()

		/**
	     * shortcut method for scanDirectory( $dir, $remove_prefix, true, true, array('lte','htt','tpl') )
	     **/
		public function getTemplateFiles( $dir, $remove_prefix = NULL )
		{
		    return $this->scanDirectory( $dir, true, true, $remove_prefix, array('lte','htt','tpl') );
		}   // end function getTemplateFiles()

		/**
		 * fixes a path by removing //, /../ and other things
		 *
		 * @access public
		 * @param  string  $path - path to fix
		 * @return string
		 **/
		public function sanitizePath( $path )
		{
		
		    // remove / at end of string; this will make sanitizePath fail otherwise!
		    $path       = preg_replace( '~/$~', '', $path );
		    
		    // make all slashes forward
			$path       = str_replace( '\\', '/', $path );

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

	        $new_path = implode("/", $parts);
	        // windows
	        if ( ! preg_match( '/^[a-z]\:/i', $new_path ) ) {
				$new_path = '/' . $new_path;
			}

	        return $new_path;
		
		}   // end function sanitizePath()
		
		/**
		 * scans a directory
		 *
		 * @access public
		 * @param  string  $dir - directory to scan
		 * @param  boolean $with_files    - list files too (true) or not (false); default: false
		 * @param  boolean $files_only    - list files only (true) or not (false); default: false
		 * @param  string  $remove_prefix - will be removed from the path names; default: NULL
		 * @param  array   $suffixes      - list of suffixes; only if $with_files = true
		 * @param  array   $skip_dirs     - list of directories to skip
		 *
		 * Examples:
		 *   - get a list of all subdirectories (no files)
		 *     $dirs = $obj->scanDirectory( <DIR> );
		 *
		 *   - get a list of files only
		 *     $files = $obj->scanDirectory( <DIR>, NULL, true, true );
		 *
		 *   - get a list of files AND directories
		 *     $list = $obj->scanDirectory( <DIR>, NULL, true );
		 *
		 *   - remove a path prefix
		 *     $list = $obj->scanDirectory( '/my/abs/path/to', '/my/abs/path' );
		 *     => result is /to/subdir1, /to/subdir2, ...
		 *
		 **/
		function scanDirectory( $dir, $with_files = false, $files_only = false, $remove_prefix = NULL, $suffixes = array(), $skip_dirs = array() ) {

			$dirs = array();

			// make sure $suffixes is an array
            if ( $suffixes && is_scalar($suffixes) ) {
                $suffixes = array( $suffixes );
			}
			if ( ! count($suffixes) && count( $this->suffix_filter ) )
			{
			    $suffixes = $this->suffix_filter;
			}
			// make sure $skip_dirs is an array(
			if ( $skip_dirs && is_scalar($skip_dirs) ) {
			    $skip_dirs = array( $skip_dirs );
			}
			if ( ! count($skip_dirs) && count( $this->skip_dirs ) )
			{
			    $skip_dirs = $this->skip_dirs;
			}
			if ( ! $remove_prefix && $this->prefix )
			{
			    $remove_prefix = $this->prefix;
			}

			if (false !== ($dh = opendir( $dir ))) {
                while( false !== ($file = readdir($dh))) {
                    if ( ! preg_match( '#^\.#', $file ) ) {
						if ( count($skip_dirs) && in_array( pathinfo($dir.'/'.$file,PATHINFO_DIRNAME), $skip_dirs) )
						{
						    continue;
						}
                        if ( is_dir( $dir.'/'.$file ) ) {
                            if ( ! $files_only ) {
                                $dirs[]  = str_ireplace( $remove_prefix, '', $dir.'/'.$file );
                            }
                            if ( $this->recurse )
                            {
                            	// recurse
                            	$subdirs = $this->scanDirectory( $dir.'/'.$file, $with_files, $files_only, $remove_prefix, $suffixes, $skip_dirs );
                            	$dirs    = array_merge( $dirs, $subdirs );
							}
                        }
                        elseif ( $with_files ) {
                            if ( ! count($suffixes) || in_array( pathinfo($file,PATHINFO_EXTENSION), $suffixes ) )
                            {
                            	$dirs[]  = str_ireplace( $remove_prefix, '', $dir.'/'.$file );
							}
                        }
                    }
                }
            }
            return $dirs;
        }   // end function scanDirectory()

		/**
		 *
		 **/
		public function setPrefix( $prefix )
		{
		    if ( is_scalar($prefix) )
		    {
		        $this->prefix = $prefix;
		        return;
			}
			// reset
			if ( is_null($prefix) )
			{
			    $this->prefix = NULL;
			}
		}   // end function setPrefix()

        /**
         *
         **/
		public function setRecursion( $bool )
		{
		    if ( is_bool($bool) )
		    {
		        $this->recurse = $bool;
			}
		}   // end function setRecursion()
		
		/**
		 *
		 **/
		public function setSkipDirs( $dirs )
		{
		    // reset
		    if ( is_null( $dirs ) )
		    {
		        $this->skip_dirs = array();
		        return;
			}
		    // make sure $dirs is an array
            if ( $dirs && is_scalar($dirs) ) {
                $dirs = array( $dirs );
			}
			if ( is_array($dirs) )
			{
			    $this->skip_dirs = $dirs;
			}
		}   // end function setSkipDirs()
		
		/**
		 *
		 **/
		public function setSuffixFilter( $suffixes )
		{
		    // reset
		    if ( is_null( $suffixes ) )
		    {
		        $this->suffix_filter = array();
		        return;
			}
		    // make sure $suffixes is an array
            if ( $suffixes && is_scalar($suffixes) ) {
                $suffixes = array( $suffixes );
			}
			if ( is_array($suffixes) )
			{
			    $this->suffix_filter = $suffixes;
			}
		}   // end function setSuffixFilter()
		
		/**
		 * remove directory recursively
		 *
		 * @access public
		 * @param  string  $directory
		 * @return boolean
		 *
		 **/
	    public function removeDirectory($directory)
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
	                if ($entry == '.' || $entry == '..')
	                {
	                    continue;
	                }
	                // recursive delete
	                if (is_dir($directory . '/' . $entry))
	                {
	                    $this->removeDirectory($directory . '/' . $entry);
	                }
	                else
	                {
	                    unlink($directory . '/' . $entry);
	                }
	            }
	            // Now delete the folder
	            $dir->close();
	            return rmdir($directory);
	        }
	    }   // end function removeDirectory()
	    
	    /**
	     * check if directory is world-writable
	     * hopefully more secure than is_writable()
	     *
	     * @access public
	     * @param  string  $directory
	     * @return boolean
	     *
	     **/
		public function is_world_writable($directory)
		{
		    if ( ! is_dir( $directory ) )
		    {
		        return false;
			}
		    return ( substr(sprintf('%o', fileperms($directory)), -1) == 7 ? true : false );
		}   // end function is_world_writable()

	}
}

?>