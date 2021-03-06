<?php

/**
 *
 * @module          initial_page
 * @author          LEPTON project 
 * @copyright       2010-2018 LEPTON project 
 * @link            https://lepton-cms.org
 * @license         copyright, all rights reserved
 * @license_terms   please see info.php of this module
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



class initial_page extends LEPTON_abstract
{
    // Own instance for this class!
    static $instance;

    private $db = NULL;
	
	private $table = "mod_initial_page";
	
	private $backend_pages = array ();
		
	public function initialize( &$db_ref=NULL, $aUser_id=NULL, $aPath_ref= NULL ) {
		global $MENU;
		
		if(NULL === $db_ref) {
		    $db_ref = LEPTON_database::getInstance();
		}
		
		$this->db = $db_ref;
		
		$this->backend_pages = array (
			'Start'		=> 'start/index.php',
			$MENU['ADDON']		=> 'addons/index.php',
			$MENU['ADMINTOOLS']	=> 'admintools/index.php',
			$MENU['GROUPS']		=> 'groups/index.php',
			$MENU['LANGUAGES']		=> 'languages/indes.php',
			$MENU['MEDIA']			=> 'media/index.php',
			$MENU['MODULES']		=> 'modules/index.php',
			$MENU['PAGES']			=> 'pages/index.php',
			$MENU['PREFERENCES']	=> 'preferences/index.php',
			$MENU['SETTINGS']		=> 'settings/index.php',
			$MENU['TEMPLATES']		=> 'templates/index.php',
			$MENU['USERS']			=> 'users/index.php'
		);
		
		$this->table = TABLE_PREFIX.$this->table;
		
		if ( ($aUser_id != NULL) && ($aPath_ref != NULL) ) {
			$this->internalTestUser($aUser_id, $aPath_ref);
		} else {
			$this->internalTestEntries();
		}
	}
	
	public function get_backend_pages_select($name="init_page_select", $selected = "") {
		global $MENU;
		
		$values = array();
		
		/**
		 *	first: add pages ...
		 *
		 */
		require_once( LEPTON_PATH."/framework/functions/function.page_tree.php" );
		$all_pages = array();
		page_tree(0, $all_pages);

		$temp_storage = array();
		$this->__get_pagetree_menulevel( $all_pages, $temp_storage);

		foreach($temp_storage as $key => $value) 	$values[ $MENU['PAGES'] ][ $key ] = $value;
			
		/**
		 *	second: add tools
		 *
		 */
		$temp = $this->db->query("SELECT `name`,`directory` from `".TABLE_PREFIX."addons` where `function`='tool' order by `name`");
		if ($temp) {
			while( false != ($data = $temp->fetchRow() ) ) {
					$values[ $MENU['ADMINTOOLS'] ][ $data['name'] ] = "admintools/tool.php?tool=".$data['directory'];
			}
		}
		
		/**
		 *	At last the backend-pages
		 *
		 */
		$values['Backend'] = &$this->backend_pages;
		$options = array(
			'name' => $name,
			'class' => "init_page_select"
		);
		
		return $this->internalBuildSelect($options, $values, $selected);
	}
	
	private function internalBuildSelect(&$options, &$values, &$selected) {
		$s = "<select ".$this->internalBuildArgs($options).">\n";
		foreach( $values as $theme=>$sublist ) {
			$s .= "<optgroup label='".$theme."'>";
			foreach($sublist as $item=>$val) {
				$sel = ($val == $selected) ? " selected='selected'" : "";
				$s .= "<option value='".$val."'".$sel.">".$item."</option>\n";
			}
			$s .= "</optgroup>";
		}
		$s.= "</select>\n";
		return $s;
	}
	
	private function internalBuildArgs(&$aArgs) {
		$s = "";
		foreach($aArgs as $name=>$value) $s .= " ".$name."='".$value."'";
		return $s;
	}

	public function get_user_info( &$aUserId=0 ) {
		$aUser = array();
		$this->db->execute_query(
		    "SELECT `init_page`, `page_param` from `".$this->table."` where `user_id`='".$aUserId."'",
		    true,
		    $aUser,
		    false
		);

		if (count($aUser) == 0)
		{
			    if($aUserId > 0)
			    {
				    $this->db->simple_query("INSERT into `".$this->table."` (`user_id`, `init_page`,`page_param`) VALUES ('".$aUserId."', 'start/index.php', '')");
				}
				
				return array('init_page' => "start/index.php", 'page_param' => '') ;
			
        } else {
            return $aUser;
        }
		return NULL;
	}
	
	public function update_user(&$aId, &$aValue, &$aParam = -1) {
		/**
		 *	M.f.i.	Aldus:	- 1 [-] does the params make sence at all? E.g. as for a internal page only the section makes sense,
		 *							but for a tool-page we're in the need to get more, e.g. details about the correct params.
		 *					- 2 [+] if the aParam parameter is not set - we'll ignore it.
		 */
		$temp_param = ($aParam == -1)  ? "" : ", `page_param`='".$aParam."' " ;
		$q = "UPDATE `".$this->table."` set `init_page`='".$aValue."'".$temp_param." where `user_id`='".$aId."'";
		$this->db->query( $q ) ;
	}
	
	private function internalTestUser( $aID, $path_ref ) {
		$info = $this->get_user_info( $aID );
		$path = ADMIN_URL."/".$info['init_page'];
		if (( $path <> $path_ref ) && ($info['init_page'] != "start/index.php" ) && ($info['init_page'] != "") ) {
			if (strlen($info['page_param']) > 0) $path .= $info['page_param'];
			$this->internalAddLeptoken( $path );
			header('Location: '.$path );
			die();
		}
	}
	
	private function internalAddLeptoken( &$aURL ) {
		if (isset($_GET['leptoken'])) {
			$temp_test = explode("?", $aURL );
			$aURL .= (count($temp_test) == 1) ? "?" : "&amp;";
			
			$aURL .= "leptoken=".$_GET['leptoken'];
		}
	}
	
	public function get_single_user_select (
		$aUserId,
		$aName,
		$selected="", 
		&$options=array(
			'backend_pages'=>true,
			'tools' => true,
			'pages' => true
			)
		) {
	
		global $MENU;
		
		$values = Array();
		
		if (array_key_exists('backend_pages', $options) && ($options['backend_pages'] == true))
			$values['Backend'] = $this->backend_pages;
		
		/**
		 *	Add tools
		 *
		 */
		if (array_key_exists('tools', $options) && ($options['tools'] == true)) {

			$temp = $this->db->query("SELECT `name`,`directory` from `".TABLE_PREFIX."addons` where `function`='tool' order by `name`");
			if ($temp) {
				while( false != ($data = $temp->fetchRow() ) ) {
						$values[ $MENU['ADMINTOOLS'] ][ $data['name'] ] = "admintools/tool.php?tool=".$data['directory'];
				}
			}
		}

		/**
		 *	Add pages
		 *
		 */
		if (array_key_exists('pages', $options) && ($options['pages'] == true)) {

			require_once( LEPTON_PATH."/framework/functions/function.page_tree.php" );
			$all_pages = array();
			page_tree(0, $all_pages);

			$temp_storage = array();
			$this->__get_pagetree_menulevel( $all_pages, $temp_storage);

			foreach($temp_storage as $key => $value) 	$values[ $MENU['PAGES'] ][ $key ] = $value;
			
		}
		
		$options = array(
			'name' => $aName,
			'class' => "init_page_select"
		);
		
		return $this->internalBuildSelect($options, $values, $selected);
	}
	
	/**
	 *	Internal private function for the correct displax of the page(-tree)
	 *
	 *	@param	array	Array within the pages. (Pass by reference)
	 *	@param	array	A storage array for the result. (Pass by Reference)
	 *	@param	int		Counter for the recursion deep, correspondence with the menu-level of the page(-s)
	 *
	 */
	private function __get_pagetree_menulevel( &$all, &$storage = array(), $deep = 0 ){
		//	Menu-data is empty, nothing to do
		if(count($all) == 0) return false;
		
		//	Recursions are more than 50 ... break
		if($deep > 50) return false;
		
		//	Build the 'select-(menu)title prefix
		$prefix = "";
		for($i=0;$i<$deep; $i++) $prefix .= "-";
		if($deep > 0) $prefix .= " ";
		
		foreach($all as $ref) {
			$storage[ $prefix.$ref['page_title'] ] = "pages/modify.php?page_id=".$ref['page_id'];
			
			// Recursive call for the subpages
			$this->__get_pagetree_menulevel( $ref['subpages'], $storage, $deep+1 );
		}
		
		return true;
	} 
	
	/**
	 *	Protected function to test the users and delete entries for	
	 *	not existing ones.
	 *
	 */
	protected function internalTestEntries () {
		$q="SELECT `user_id` from `".TABLE_PREFIX."users` order by `user_id`";
		$r= $this->db->query( $q );
		if ($r) {
			$ids = array();
			while(false !== ($data = $r->fetchRow())) {
				$ids[] = $data['user_id'];
			}
			
			$q = "DELETE from `".TABLE_PREFIX."mod_initial_page` where `user_id` not in (".implode (",",$ids).")";
			$this->db->query( $q );
		}
	}
	
	public function get_language() {
		$lang = (dirname(__FILE__))."/../languages/". LANGUAGE .".php";
		require_once ( !file_exists($lang) ? (dirname(__FILE__))."/../languages/EN.php" : $lang );
		return $MOD_INITIAL_PAGE;
	}
}
?>