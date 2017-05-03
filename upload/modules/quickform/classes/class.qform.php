<?php

/**
 *
 *	@module			quickform
 *	@version		see info.php of this module
 *	@authors		Ruud Eisinga, LEPTON project
 *	@copyright		2012-2017 Ruud Eisinga, LEPTON project
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {
	include(LEPTON_PATH.'/framework/class.secure.php');
} else {
	$root = "../";
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= "../";
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) {
		include($root.'/framework/class.secure.php');
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php 

class qForm {
	
	public $templates = array();
	public $attachements = array();
	public $isArray = false;
	public $fieldGetSeen = false;


	public $upload_whitelist = "jpg,jpeg,gif,png,zip,rar,7z,pdf,doc,docx,xls,xlsx,csv";
	public $error;
	public $current = '';
	public $next = '';

	public function __construct($section_id = 0) {
		
		
	}
	
	public function build_filename_list( $aList ) {
		$return_list = array();
		foreach($aList as &$ref) {
			$temp = explode(".", $ref);
			$s = array_pop($temp);
			$return_list[ $ref ] = str_replace("_", " ", implode(".", $temp));
		}
		return $return_list;
	}

	public function getSelectTemplate($current = null, $all_files) {
		$listarray = $this->build_filename_list( $all_files );
		$list = '<select class="templates" name="template">';
		foreach ($listarray as $org_name => $display_name) {
			$s = $org_name == $current ? ' selected="selected"' : '';
			$list .= '<option '.$s.' value="'.$org_name.'">'.$display_name.'</option>';
		}
		$list .= '</select>';
		return $list;
	}
	
	public function getTemplates($pattern = '') {
		if(!$pattern) $pattern = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'form_*.htt';
        foreach(glob($pattern) as $filename) {
			$name = substr( basename($filename,".htt") , 5);
			$title = ucwords (str_replace(array("_","-")," ", $name));
			$this->templates[$title] = $name;
			
		}
        return $this->templates;
    }

	public function get_template($name) {
		global $wb;
		if(!file_exists(dirname(dirname(__FILE__)).'/templates/'.$name.'.htt')) return '';
		$this->current = substr($name,5);
		$tmp = file_get_contents(dirname(dirname(__FILE__)).'/templates/'.$name.'.htt');
		$var[] = "{URL}";				$value[] = $this->page(PAGE_ID);
		$var[] = "{LEPTON_URL}";			$value[] = LEPTON_URL;
		$var[] = "{WEBSITE_TITLE}";		$value[] = WEBSITE_TITLE;
		$var[] = "{MEDIA_DIRECTORY}";	$value[] = MEDIA_DIRECTORY;
		$var[] = "{SERVER_EMAIL}";		$value[] = SERVER_EMAIL;
		$var[] = "{UPLOAD_LIMIT}";		$value[] = $this->get_upload_limit();
		$var[] = "{UPLOAD_WHITELIST}";	$value[] = $this->get_upload_whitelist($tmp);
		$tmp = str_replace($var,$value,$tmp);
		return $tmp;
	}
	
	public function get_upload_limit() {
		$max_upload = (int)(ini_get('upload_max_filesize'));
		$max_post = (int)(ini_get('post_max_size'));
		$memory_limit = (int)(ini_get('memory_limit'));
		$upload_mb = min($max_upload, $max_post, $memory_limit);
		return $upload_mb;
	}
	
	public function get_upload_whitelist($template) {
		if(preg_match('/{WHITELIST (.*)}/i',$template,$name)) {
			$this->upload_whitelist = strtolower($name[1]);
		}
		return $this->upload_whitelist;
	}
	
	public function check_whitelist($filename) {
		$file_ext=strtolower(end(explode('.',$filename)));
		return(in_array($file_ext,explode(',',$this->upload_whitelist)));
	}
	
	public function add_template($template, $var, $value) {
		$tmp = str_ireplace($var,$value,$template);
		return $tmp;
	}	
	
	public function page($pid = 0) {
		global $database, $wb;
		$link = $database->get_one("SELECT link FROM ".TABLE_PREFIX."pages WHERE `page_id` = '".$pid."'");
		return $wb->page_link($link);
	}
	
	public function safe_get_post($postfield) {
		global $MOD_QUICKFORM;
		$this->isArray = false;
		$val = null;
		$getfield = substr($postfield,0,5)=='qf_r_' ? substr($postfield,5) : substr($postfield,3);
		if (get_magic_quotes_gpc()) {
			if(isset($_POST[$postfield])) $_POST[$postfield] = $this->stripslashes_deep($_POST[$postfield]);
			if(isset($_GET[$getfield])) $_GET[$getfield] = $this->stripslashes_deep($_GET[$getfield]);
		}
		if(isset($_FILES[$postfield]['name']) && $_FILES[$postfield]['name']) {
			if ($this->check_whitelist($_FILES[$postfield]['name'])) {
				if($_FILES[$postfield]['error'] == 0) {
					$this->attachements[$_FILES[$postfield]['name']] = $_FILES[$postfield]['tmp_name'];
					$val = $_FILES[$postfield]['name'];
				}
			} else {
				$this->error = $_FILES[$postfield]['name'].' - '.$MOD_QUICKFORM['INVALID'];
			}
		}elseif(isset($_POST[$postfield])) {
			$val =  $_POST[$postfield];
		} elseif(isset($_GET[$getfield])) {
			$val = $_GET[$getfield];
			$this->fieldGetSeen = true;
		} else {
			$val = isset($_SESSION['form'][$postfield]) ? $_SESSION['form'][$postfield] : null;
		}
		if(substr($postfield,0,3) == 'qf_') $_SESSION['form'][$postfield] = $val;
		if(is_array($val)) {
			$val = join(" | ",$val);
			$this->isArray = true;
		}
		$val = htmlspecialchars($val);
		$val = preg_replace("/(\n)/","",$val);
		return $val?$val:'';
	}
	
	public function stripslashes_deep($value) {
		$value = is_array($value) ? array_map(array($this,'stripslashes_deep'), $value) : stripslashes($value);
		return $value;
	}

	//	Get a catcha
	public function captcha($section_id = 0) {
		
		if(file_exists( LEPTON_PATH."/modules/quickform/recaptcha.php" ))
		{
			require_once LEPTON_PATH."/modules/quickform/recaptcha.php";
			return quickform_recaptcha::build_captcha();
		}
		
		require_once(LEPTON_PATH.'/modules/captcha_control/captcha/captcha.php');
		ob_start();
			call_captcha("all","",$section_id);
			$captcha = ob_get_clean();
		return $captcha;
	}
	
	// Insert or Update table with posted fields in array
	public function update_record ( $table, $id_field, $data ) {
		global $database;
		$set = '';
		$val = '';
		if ($data[$id_field] <= 0) {
			foreach ( $data as $key => $value ) {
				if ($key != $id_field) {
					$set .= $set ? ',`'.$key.'`':'`'.$key.'`';
					$val .= $val ? ",'$value'" : "'$value'";
				}
			}
			$query = "INSERT INTO ".TABLE_PREFIX.$table." ($set) VALUES ($val)";
			$database->query($query);
			if($database->is_error()) die($database->get_error());
			return $database->get_one("SELECT LAST_INSERT_ID()");
		} else {
			$id = $data[$id_field];
			foreach ( $data as $key => $value ) {
				if ($key != $id_field) {
					$set .= $set ? ',':'';
					$set .= "`$key`='$value'";
				}
			}
			$query = "UPDATE ".TABLE_PREFIX.$table." SET $set where `$id_field` = '$id'";
			$database->query($query);
			if($database->is_error()) die($database->get_error());
			return $id;
		}
	}

	public function get_record ( $table, $id_field, $id) {
		global $database;
		$result = array();
		$res = $database->query("SELECT * FROM ".TABLE_PREFIX.$table." WHERE `$id_field` = '$id'");
		if($res) {
			while ($row = $res->fetchRow()) {
				$result[] = $row;
			}
		}
		return $result;
	}

	public function delete_record ( $id) {
		global $database;
		$result = array();
		$res = $database->query("DELETE FROM ".TABLE_PREFIX."mod_quickform_data WHERE `message_id` = '$id'");
		return;
	}

	public function get_history ( $id, $max = 20) {
		global $database;
		$result = array();
		$database->execute_query(
			"SELECT * FROM `".TABLE_PREFIX."mod_quickform_data` WHERE `section_id` = '".$id."' order by `message_id` desc limit 0,".$max,
			true,
			$result,
			true
		);
		return $result;
	}
	
	public function build_pagelist($parent, $this_page) {
		global $database, $links;
		$iterated_parents = array(); // keep count of already iterated parents to prevent duplicates

		$table_pages = TABLE_PREFIX."pages";
		if ( $query = $database->query("SELECT link, menu_title, page_title, page_id, level 
			FROM ".$table_pages." 
			WHERE parent = ".$parent." 
			ORDER BY level, position ASC")) {
			while($res = $query->fetchRow()) {
				$links[$res['page_id']] = $res['page_id'].'|'.str_repeat("  -  ",$res['level']).$res['menu_title'].' ('.$res['page_title'].')';
				if (!in_array($res['page_id'], $iterated_parents)) {
					$this->build_pagelist($res['page_id'], $this_page);
					$iterated_parents[] = $res['page_id'];
				}
			}
		}
	}
 
	
	
	//HTML Email funtionality
	public function mail_old_version ($to = NULL, $from = NULL, $subject = NULL, $html_message = NULL, $plain_message = NULL) {
		if (!$to || !$from || !$subject || !$html_message) return false;
		if (!$plain_message) $plain_message = strip_tags($html_message);
		$random_hash = 'boundary-'.md5(date('r', time()));
		$headers = "From: ".$from."\r\n";
		$headers .= "Reply-To: ".$from."\r\n";
		$headers .= "Content-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";
		$lf = "\n";
		$mailbody = '--PHP-mixed-'.$random_hash.$lf.
		'Content-Type: multipart/alternative; boundary="PHP-alt-'.$random_hash.'"'.$lf.$lf.
		'--PHP-alt-'.$random_hash.$lf.
		'Content-Type: text/plain; charset="iso-8859-1"'.$lf.
		'Content-Transfer-Encoding: 7bit'.$lf.$lf.
		$plain_message.$lf.$lf.
		'--PHP-alt-'.$random_hash.$lf.
		'Content-Type: text/html; charset="iso-8859-1"'.$lf.
		'Content-Transfer-Encoding: 7bit'.$lf.$lf.
		$html_message.$lf.$lf.
		'--PHP-alt-'.$random_hash.'--'.$lf;
		$mailbody .= '--PHP-mixed-'.$random_hash.'--'.$lf;
		return @mail( $to, $subject, $mailbody, $headers );
	}
	
	/**
	 *	@param	string	To_address
	 *	@param	string	Subject
	 *	@param	string	Message
	 *	@param	string	From name
	 *	@param	string	Reply to
	 *	@param	array	Attachments
	 */	
	public function mail($toaddress, $subject, $message, $fromname='', $replyto = '', $attachments=array() ) {
		
		require_once( LEPTON_PATH . "/modules/lib_phpmailer/library.php" );	
		
		$toArray = explode(',',$toaddress);
		$fromaddress = $toArray[0];
	
		$myMail = new phpmailer();
		// set user defined from address
		if ($fromaddress!='') {
			if($fromname!='') $myMail->FromName = $fromname;  	// FROM-NAME
			$myMail->From = $fromaddress;                     	// FROM:
		}
		if ($replyto!='') {
			$myMail->AddReplyTo($replyto);              	  	// REPLY TO:
		}
		// define recepient and information to send out
		
		foreach ($toArray as $toAddr) {							// TO:
			$myMail->AddAddress($toAddr);
		}
		$myMail->Subject = $subject;                          	// SUBJECT
		$myMail->Body = $message; 		                     	// CONTENT (HTML)
		$textbody = strip_tags($message);  
		$textbody = str_replace("\t","",$textbody);  
		while (strpos($textbody,"\n\n\n") !== false) 
			$textbody = str_replace("\n\n\n","\n\n",$textbody);  
		while (strpos($textbody,"\r\n\r\n\r\n") !== false) 
			$textbody = str_replace("\r\n\r\n\r\n","\r\n\r\n",$textbody);  
		$myMail->AltBody = $textbody;			              	// CONTENT (TEXT)
		
		foreach($this->attachements as $filename => $file) {
			$myMail->AddAttachment($file, $filename);
		}
		
		foreach( $attachments as &$ref ) {
			$myMail->addAttachment($ref['path'], $ref['name']);
		}
		
		// check if there are any send mail errors, otherwise say successful
		if (!$myMail->Send()) {
			return false;
		} else {
			return true;
		}
	}
} //end class