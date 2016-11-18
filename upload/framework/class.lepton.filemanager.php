<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author		Website Baker Project, LEPTON Project
 * @copyright	2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license		http://www.gnu.org/licenses/gpl.html
 * @license_terms	please see LICENSE and COPYING files in your package
 * @reformatted 2013-05-30
 */

class lepton_filemanager
{
    
    /**
     *	Private var that holds the guid of the class
     *
     */
    private $guid = "17FE7112-8D31-4823-8E03-D0DD1EE21EDB";
    
    /**
     *	Private array that holds the reg. filenames.
     *
     */
    private $filenames = array();
    
    /**
     *	Constructor of the class
     *
     *	@param	mixed	String or array - within the filename(-s).
     *
     */
    public function __construct($file_or_array = NULL)
    {
        if ($file_or_array != NULL)
            $this->register_file($file_or_array);
    }
    
    /**
     *	Destructor of the class
     *
     */
    public function __destruct()
    {
        unset($this->filenames);
    }
    
    /**
     *	Public function/method to "register" files.
     *
     *	@param	mixed	Could be a single filename (string) or an array within the names.
     *
     */
    public function register($filename)
    {
        if (true === is_array($filename))
        {
            $this->filenames = array_merge($filename, $this->filenames);
        }
        else
        {
            $this->filenames[] = $filename;
        }
    }
    
    /**
     *	Public function/method to "unregister" files.
     *	
     *	@param	string	A single filename.
     *
     */
    public function unregister($filename)
    {
        if (in_array($filename, $this->filenames))
            unset($this->filenames[$filename]);
    }
    
    /**
     *	As the internal filename-array is private we have to merge it inside the class.
     *
     *	@param	array	Any array - pass by reference!
     *
     */
    public function merge_filenames(&$storrage = array())
    {
        $storrage = array_merge($this->filenames, $storrage);
    }
    
    /**
     *	Handel call for unkown methods/functions.
     *
     */
    public function __call($name, $arg_array)
    {
        
    }
    
    /**
     *
     *	@param	string	The filename we're looking for.
     *	@param	string	A default directory.
     *	@param	bool	Return only the path without the filename
     *	@return	mixed	The path, the dirname or NULL if nowhere found.
     *
     */
    public function resolve_path($file_name, $base_path = "", $only_path = false)
    {
        $temp = explode(".", $file_name);
        $type = strtolower(array_pop($temp));
        switch ($type)
        {
            case 'css':
                $look_up = array(
                    "/templates/" . DEFAULT_TEMPLATE . '/frontend/login/css/' . $file_name,
                    $base_path . $file_name
                );
                break;
            
            case 'lte':
            case 'htt':
                $look_up = array(
                    "/templates/" . DEFAULT_TEMPLATE . "/frontend/login/templates/" . $file_name,
                    $base_path . $file_name
                );
                break;
            
            case 'js':
                $look_up = array(
                    "/templates/" . DEFAULT_TEMPLATE . '/frontend/login/js/' . $file_name,
                    $base_path . $file_name
                );
                break;
            
            default:
                return NULL;
        }
        foreach ($look_up as &$p)
        {
            if (file_exists(LEPTON_PATH . $p))
            {
                if (true === $only_path)
                    $p = dirname($p);
                return $p;
            }
        }
        return NULL;
    }
}

global $lepton_filemanager;
if (!is_object($lepton_filemanager))
    $lepton_filemanager = new lepton_filemanager();

?>