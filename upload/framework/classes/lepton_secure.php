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

class LEPTON_secure extends LEPTON_class
{
	//  Boolean for the "state"
	public $bCalledByModule = false;
	
	// for the filepaths
	private $direct_access_allowed = array();
	
	//  list of allowed fils by default
	private $files_access_allowed = array(
        'pages' => array(
            '/index.php'
        ),
		'admin' => array(
            '/access/index.php',
            '/addons/index.php',
            '/addons/reload.php',
            '/admintools/index.php',
            '/admintools/tool.php',
            '/groups/add.php',
            '/groups/groups.php',
            '/groups/index.php',
            '/groups/save.php',
            '/languages/details.php',
            '/languages/index.php',
            '/languages/install.php',
            '/languages/uninstall.php',
            '/login/index.php',
            '/login/forgot/index.php',
            '/logout/index.php',
            '/media/thumb.php',
            '/modules/details.php',
            '/modules/index.php',
            '/modules/install.php',
            '/modules/manual_install.php',
            '/modules/uninstall.php',
            '/modules/save_permissions.php',
            '/pages/add.php',
            '/pages/delete.php',
            '/pages/empty_trash.php',
            '/pages/index.php',
            '/pages/modify.php',
            '/pages/move_down.php',
            '/pages/move_up.php',
            '/pages/restore.php',
            '/pages/save.php',
            '/pages/sections_save.php',
            '/pages/sections.php',
            '/pages/settings.php',
            '/pages/settings2.php',
            '/pages/trash.php',
            '/preferences/save.php',
            '/profiles/index.php',
            '/settings/ajax_testmail.php',
            '/settings/index.php',
            '/settings/save.php',
            '/start/index.php',
            '/templates/details.php',
            '/templates/index.php',
            '/templates/install.php',
            '/templates/uninstall.php',
            '/users/add.php',
            '/users/index.php',
            '/users/save.php',
            '/users/users.php'
        ),
        'account'   => array(
            '/forgot.php',
            '/login.php',
            '/logout.php',
            '/new_password.php',
            '/save_new_password.php',
            '/preferences.php',
            '/signup.php'
        ),
        'modules'   => array(
            '/captcha_control/captcha/captchas/calc_image.php',
            '/captcha_control/captcha/captchas/calc_ttf_image.php',
            '/captcha_control/captcha/captchas/old_image.php',
            '/captcha_control/captcha/captchas/ttf_image.php',
            '/captcha_control/captcha/captcha.php',
            '/edit_module_files.php',
            '/menu_link/save.php',
            '/wrapper/save.php',
            '/jsadmin/move_to.php'
        ),
        'search' => array(
            '/index.php'
        ) 
    );
    
    /**
     *  Prepare/fill the internal "direct_access_allowed"
     *
     */
	protected function initialize()
	{
		
		foreach( self::$instance->files_access_allowed as $key => $value)
		{
		    switch($key) {
		        case 'admin':
		            $dirname = str_replace(LEPTON_PATH, '', ADMIN_PATH);
		            break;
		        case 'pages':
		            $dirname = PAGES_DIRECTORY;
		            break;
		        default:
		            $dirname = "/".$key;
		            break;
		    }
		    foreach($value as $filename)
		    {
		        static::$instance->direct_access_allowed[] = $dirname.$filename;
		    }
		}
		// die(LEPTON_tools::display( static::$instance->direct_access_allowed ) );
	}
	
	/**
	 *  Replace the current internal list of allowed files with new ones.
	 *
	 *  @param array    A list of filenames (by default from the "called" module inside 'register_class_secure.php'. 
	 *
	 *  @notice call inside "register_class_secure.php" like e.g.
	 *
	 *          LEPTON_secure::getInstance()->register_filenames( $files_to_register );
     *
	 *
	 */
	public function register_filenames( $newFileNames = array())
    {
		static::$instance->direct_access_allowed = $newFileNames;
		static::$instance->bCalledByModule = true;
    }
    
    /**
     *  Returns the internal list of direct_access_allowed
     *
     *  @return array List of direct access allowed filepaths.
     *
     */
    public function get_files() {
    	return static::$instance->direct_access_allowed;
    }
}