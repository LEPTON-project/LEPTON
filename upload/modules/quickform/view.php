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

 /*
todo: file uploads

- upload one or more files 
- save the files to a temp location with random name
- keep original name and temp name as sessions
- when sending mail, add temp file and set name to original name
- after sending, clean temp files 
- clean old files (orphans that did not send)
  
 
 */

/**
 *	Load Language file
 */
$langfile = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($langfile) ? (dirname(__FILE__))."/languages/EN.php" : $langfile );

/**
 *	Get the page-language
 */
$page_language = $database->get_one("SELECT `language` FROM `".TABLE_PREFIX."pages` WHERE `page_id`=".$page_id);
$page_language = strtolower($page_language);

/**	*******************************
 *	Try to get the template-engine.
 */
global $parser, $loader;
if (!isset($parser))
{
	require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
}

$loader->prependPath( dirname(__FILE__)."/templates/".$page_language."/", "quickform" );	// !path

$frontend_template_path = LEPTON_PATH."/templates/".DEFAULT_TEMPLATE."/frontend/quickform/templates/".$page_language."/";	// !path
$module_template_path = dirname(__FILE__)."/templates/".$page_language."/";	// !path

require_once (LEPTON_PATH."/modules/lib_twig/classes/class.twig_utilities.php");
$twig_util = new twig_utilities( $parser, $loader, $module_template_path, $frontend_template_path );
$twig_util->template_namespace = "quickform";

// End of template-engines settings.

require_once (dirname(__FILE__).'/classes/class.qform.php');

$qform = new qForm($section_id);

/**
 *	Get the settings for this section
 */
$quickform_settings = array();
$database->execute_query(
	"SELECT * FROM ".TABLE_PREFIX."mod_quickform WHERE `section_id` = ".$section_id,
	true,
	$quickform_settings,
	false
);

/**
 *	[2]	Page_link for url
 */
global $wb;
$link = $database->get_one("SELECT `link` FROM `".TABLE_PREFIX."pages` WHERE `page_id` = '".PAGE_ID."'");
$url_link = $wb->page_link($link);

/**
 *	[2.1]	Success page link
 */
$succsess_link = $quickform_settings['successpage'];
if( $succsess_link == 0) {
	$succsess_link = $url_link;
} else {
	$link = $database->get_one("SELECT `link` FROM `".TABLE_PREFIX."pages` WHERE `page_id` = '".$succsess_link."'");
	$succsess_link = $wb->page_link($link);
}

/**
 *	[3]	Is the form send? (something in the $_POST and/or 
 *		are the values correct - also: email send?
 */
$all_submitted = -1;
$statusmessage = "";
$posted_data = array();
$file_white_list = "";
$uploaded_filetypes_ok = true;	// if something goes wrong we'll set this one to false!

/**
 *	Any captcha?
 *
 */
$captcha_ok = false;
$use_captcha = false;

if( isset($_POST['captcha']) ) {
	if(isset($_SESSION['captcha'.$section_id])) {
		$use_captcha = true;
		if( $_SESSION['captcha'.$section_id] == $_POST['captcha']) {
			$captcha_ok = true;
		} else {
			$captcha_ok = false;
		}
	}
}
	
if ( (isset($_POST['quickform'])) && ($_POST['quickform'] === $section_id) ) {

	//	get the template file content
	$template_string = file_get_contents(dirname(__FILE__)."/templates/".$page_language."/".$quickform_settings['template']);
	
	//	find all fields in template
	$fields = array();
	$matches = array();
	preg_match_all('/<input [^>]*>|<select [^>]*>|<textarea [^>]*>/', $template_string, $matches);
	foreach($matches[0] as $match){
		if(preg_match('/name="([^"]*)"/i',$match,$name)) {
			$name = str_replace("[]","",$name[1]);
			$fields[$name] = '-';
		}
	}

	/**
	 *	Try to get the white-list
	 */
	$temp_list_result = array();
	
	if(preg_match('/\{\# upload_white_list (.*) \#\}/i',$template_string,$temp_list_result)) {
		$file_white_list = strtolower($temp_list_result[1]);
	}
		
	// 	Any files ? We try to "add" the filename to the $_POST
	//	if there was no error during upload. 
	if(isset($_FILES) && (count($_FILES) > 0)) {
		foreach($_FILES as $key=>$f_data) {
			if( 0 === $f_data['error'] ) $_POST[ $key ] = $f_data['name'];
		}
	}
	
	/**
	 *	Looking for the submitted fields of the form:
	 *	the names starts with "qf_r_" 
	 */
	$look_up_fields = array();
	foreach($fields as $look_up_name => $value) {
		if(isset($_POST[$look_up_name])) {
			$fields[ $look_up_name ] = $_POST[$look_up_name];
		}
	}
	
	$all_submitted = true;
	$required_and_empty = array();
	foreach($fields as $key=>$value) {
		/**
		 *	Test name for "required"
		 */
		if( strpos($key, "_r_") !== false ) {
			if (($value == "") || ($value == "-")) {
				$all_submitted = false;
				$required_and_empty[] = $key;
			}
		}
		$temp = explode("_", $key);
		$name = array_pop($temp);
		$posted_data[ strtoupper($name) ] = $value;
	}
	
	/**
	 *	Subtest for captcha
	 */
	if( $use_captcha==true && $captcha_ok==false) {
		$all_submitted = false;
	}
	
	/**
	 *	All submitted ok ... we are lokking for uploaded files now!
	 *
	 */
	$got_attachments = false;
	$attachments = array();
	if (true === $all_submitted) {
		if(isset($_FILES) && (count($_FILES) > 0)) {

			$tagret_folder = LEPTON_PATH."/temp/";
			$allowed_file_types = explode(",", str_replace(" ", "", $file_white_list) );
			
/*
			if( false === is_dir($tagret_folder)) {
				//	Try to create the folder
				if(!function_exists("make_dir")) require_once( LEPTON_PATH."/functions/function.make_dir.php" );
				make_dir($tagret_folder);
			}
*/			
			foreach($_FILES as $key => &$f_data) {
				
				if( $f_data['error'] !== 0 ) continue;
				
				//	Get the filename
				$temp_name = $f_data['name'];
				
				$aNameTerms = explode(".", $temp_name);
				
				//	Strip off the last element of the array
				$extention = strtolower(array_pop($aNameTerms));
				
				//	Test file_type/extention
				if(!in_array( $extention, $allowed_file_types)) {
					// failed
					$all_submitted = false;
					$uploaded_filetypes_ok = false;
					
					continue;
				}
				
				//	Buid new filename
				$new_file_name = implode(".", $aNameTerms)."_".( date("Y-m-d-H-m", time())).".".$extention;
			
				if( move_uploaded_file( $f_data['tmp_name'], $tagret_folder.$new_file_name)) {
					// Ok
					$got_attachments = true;
					$attachments[] = array(
						'name'	=> $new_file_name,
						'path'	=> $tagret_folder.$new_file_name
					);
				}
			}
		}
	}
	
	/**
	 *	All required fields all_submitted? Try to send the email
	 */
	if (true === $all_submitted) {
	 	$email_to		= $quickform_settings['email'];
	 	$email_subject	= $quickform_settings['subject'];
	 	$email_from		= MAILER_DEFAULT_SENDERNAME;
	 	$email_replyto	= "";
	 	
	 	$email_message = "";
	 	
	 	$email_template_string = file_get_contents(dirname(__FILE__)."/templates/backend/email.lte");
	 	$email_template =  $parser->createTemplate( $email_template_string );
	 	
	 	$email_message .= $email_template->render(
	 		array(
	 			'WEBSITE_TITLE'	=> WEBSITE_TITLE,
	 			'LEPTON_URL'	=> LEPTON_URL,
	 			'HEADER'		=> $MOD_QUICKFORM["E-MAIL_HEADER"],
	 			'posted_data'	=> $posted_data
	 		)
	 	);
	 	
	 	$result = $qform->mail( $email_to, $email_subject, $email_message, $email_from, $email_replyto , $attachments );
	 	
	 	if( false === $result ) {
	 		// Display error messages from "mail" ...
	 	}
	 	
	 	/**
	 	 *	Delete the uploaded files ...
	 	 */
	 	foreach( $attachments as &$file_ref) {
	 		if( true === file_exists($file_ref['path']) ) unlink( $file_ref['path'] );
	 	}
	 	
	 	/**
	 	 *	Store the message into the database
	 	 */
	 	$fields = array(
	 		'section_id'	=> $section_id,
	 		'data'	=> $email_message,
	 		'submitted_when'	=> time()
	 	);
	 	
	 	$result = $database->build_and_execute(
	 		'insert',
	 		TABLE_PREFIX."mod_quickform_data",
	 		$fields
	 	);
	 	
	 	if(false === $result) {
	 		die( $database->get_error() );
	 	}
	 	
	 	if($url_link !== $succsess_link) {
	 		die( header( "location: ".$succsess_link) );
	 	}
	 }
} else {

	// pdf,rtf,txt
	
	$template_string = file_get_contents(dirname(__FILE__)."/templates/".$page_language."/".$quickform_settings['template']);
	$temp_list_result = array();
	
	if(preg_match('/\{\# upload_white_list (.*) \#\}/i',$template_string,$temp_list_result)) {
		$file_white_list = strtolower($temp_list_result[1]);
	}
}

/**
 *	Collect all the data first
 */
$pagecontent = array(
	'quickform_settings'	=> $quickform_settings,
	
	'PAGE_ID'		=> $page_id,
	'SECTION_ID'	=> $section_id,
	'DATE'	=> date( DATE_FORMAT , time() ),
	'TIME'	=> date( TIME_FORMAT , time() ),

	'URL'		=> $url_link,	// see [2] -- full path to the current frontend-page - aka. $wb->page_link() for a given page_id
	'SUBJECT'	=> $quickform_settings['subject'],
	'EMAIL'		=> "", //$quickform_settings['email'],
	
	'STATUSMESSAGE'	=> $statusmessage,	// also used for "thank you"
	'MESSAGE_CLASS'	=> "",	//	hidden, 'ok', 'error', or classname?
	
	'NAME_ERROR'	=> "",
	'PHONE_ERROR'	=> "",
	'CAPTCHA'		=> $qform->captcha( $section_id ),
	'required_and_empty'	=> "",
	'WHITELIST'	=> "",
	'UPLOAD_WHITELIST' => $file_white_list,	// this one is a little bit tricky!
	'UPLOAD_LIMIT'	=> '2'
);

if($all_submitted === false) {

	$pagecontent['MESSAGE_CLASS'] = "error";
	$pagecontent['STATUSMESSAGE'] = $MOD_QUICKFORM["NOTALL"];

	//	Wrong captcha!
	if( ( $use_captcha == true ) && ( $captcha_ok == false ) ){
		$pagecontent['STATUSMESSAGE'] .= " (".$TEXT['CAPTCHA_VERIFICATION'].")";
	}
	//	Filetype does not match! 
	if ( false === $uploaded_filetypes_ok ) {
		$pagecontent['STATUSMESSAGE'] .= "\n(".$MESSAGE['GENERIC_FILE_TYPE']." ".$file_white_list.")";
	}
	
	foreach($posted_data as $key=>$value) {
		$pagecontent[ $key ] = $value;
	}
	$pagecontent['required_and_empty'] = $required_and_empty;
	
} else if($all_submitted === true) {
	$pagecontent['MESSAGE_CLASS'] = "ok";
	$pagecontent['STATUSMESSAGE'] = $MOD_QUICKFORM['THANKYOU'];
	$pagecontent['FORM_CLASS'] = "hidden";
}

echo $parser->render(
	'@quickform/'.$quickform_settings['template'],
	$pagecontent
);
