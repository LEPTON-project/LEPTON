<?php

/**
 *  @module         form
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Rudolph Lartey, John Maats, Dietrich Roland Pehlke 
 *  @copyright      2004-2011 Ryan Djurovich, Rudolph Lartey, John Maats, Dietrich Roland Pehlke 
 *  @license        see info.php of this module
 *  @license terms  see info.php of this module
 *  @requirements   PHP 5.2.x and higher
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

// check if frontend.css file needs to be included into the <body></body> of view.php
if((!function_exists('register_frontend_modfiles') || !defined('MOD_FRONTEND_CSS_REGISTERED')) &&
	file_exists(WB_PATH .'/modules/form/frontend.css')) {
	echo '<style type="text/css">';
	include(WB_PATH .'/modules/form/frontend.css');
	echo "\n</style>\n";
} 

require_once(WB_PATH.'/include/captcha/captcha.php');

// obtain the settings of the output filter module
if(file_exists(WB_PATH.'/modules/output_filter/filter-routines.php')) {
	include_once(WB_PATH.'/modules/output_filter/filter-routines.php');
	$filter_settings = get_output_filter_settings();
} else {
	// no output filter used, define default settings
	$filter_settings['email_filter'] = 0;
}

// Function for generating an optionsfor a select field
if (!function_exists('make_option')) {
function make_option(&$n, $k, $values) {
	// start option group if it exists
	if (substr($n,0,2) == '[=') {
	 	$n = '<optgroup label="'.substr($n,2,strlen($n)).'">';
	} elseif ($n == ']') {
		$n = '</optgroup>';
	} else {
		if(in_array($n, $values)) {
			$n = '<option selected="selected" value="'.$n.'">'.$n.'</option>';
		} else {
			$n = '<option value="'.$n.'">'.$n.'</option>';
		}
	}
}
}
// Function for generating a checkbox
if (!function_exists('make_checkbox')) {
function make_checkbox(&$n, $idx, $params) {
	$field_id = $params[0][0];
	$seperator = $params[0][1];
	$label_id = 'wb_'.str_replace(" ", "_", $n);
	if(in_array($n, $params[1])) {
		$n = '<input class="field_checkbox" type="checkbox" id="'.$label_id.'" name="field'.$field_id.'['.$idx.']" value="'.$n.'" checked="checked" />'.'<label for="'.$label_id.'" class="checkbox_label">'.$n.'</lable>'.$seperator;
	} else {
		$n = '<input class="field_checkbox" type="checkbox" id="'.$label_id.'" name="field'.$field_id.'['.$idx.']" value="'.$n.'" />'.'<label for="'.$label_id.'" class="checkbox_label">'.$n.'</label>'.$seperator;
	}	
}
}
// Function for generating a radio button
if (!function_exists('make_radio')) {
function make_radio(&$n, $idx, $params) {
	$field_id = $params[0];
	$group = $params[1];
	$seperator = $params[2];
	$label_id = 'wb_'.str_replace(" ", "_", $n);
	if($n == $params[3]) { 
		$n = '<input class="field_radio" type="radio" id="'.$label_id.'" name="field'.$field_id.'" value="'.$n.'" checked="checked" />'.'<label for="'.$label_id.'" class="radio_label">'.$n.'</label>'.$seperator;
	} else {
		$n = '<input class="field_radio" type="radio" id="'.$label_id.'" name="field'.$field_id.'" value="'.$n.'" />'.'<label for="'.$label_id.'" class="radio_label">'.$n.'</label>'.$seperator;
	}
}
}

if (!function_exists("new_submission_id") ) {
	function new_submission_id() {
		$submission_id = '';
		$salt = "abchefghjkmnpqrstuvwxyz0123456789";
		srand((double)microtime()*1000000);
		$i = 0;
		while ($i <= 7) {
			$num = rand() % 33;
			$tmp = substr($salt, $num, 1);
			$submission_id = $submission_id . $tmp;
			$i++;
		}
		return $submission_id;
	}
}

// Work-out if the form has been submitted or not
if($_POST == array()) {

// Set new submission ID in session
$_SESSION['form_submission_id'] = new_submission_id();

// Get settings
$query_settings = $database->query("SELECT header,field_loop,footer,use_captcha FROM ".TABLE_PREFIX."mod_form_settings WHERE section_id = '$section_id'");
if($query_settings->numRows() > 0) {
	$fetch_settings = $query_settings->fetchRow();
	$header = str_replace('{WB_URL}',WB_URL,$fetch_settings['header']);
	$field_loop = $fetch_settings['field_loop'];
	$footer = str_replace('{WB_URL}',WB_URL,$fetch_settings['footer']);
	$use_captcha = $fetch_settings['use_captcha'];
	$form_name = 'form';
	$use_xhtml_strict = false;
} else {
	$header = '';
	$field_loop = '';
	$footer = '';
	$form_name = 'form';
	$use_xhtml_strict = false;
}

?>
<form <?php echo ( ( (strlen($form_name) > 0) AND (false == $use_xhtml_strict) ) ? "name=\"".$form_name."\"" : ""); ?> action="<?php echo htmlspecialchars(strip_tags($_SERVER['SCRIPT_NAME'])); ?>#wb_<?PHP echo $section_id;?>" method="post">
<div>
<input type="hidden" name="submission_id" value="<?php echo $_SESSION['form_submission_id']; ?>" />
</div>
<?php
if(ENABLED_ASP) { // first add some honeypot-fields
?>
<div>
<input type="hidden" name="submitted_when" value="<?php $t=time(); echo $t; $_SESSION['submitted_when']=$t; ?>" />
</div>
<p class="nixhier">
email address:
<label for="email">Leave this field email-address blank:</label>
<input id="email" name="email" size="56" value="" /><br />
Homepage:
<label for="homepage">Leave this field homepage blank:</label>
<input id="homepage" name="homepage" size="55" value="" /><br />
URL:
<label for="url">Leave this field url blank:</label>
<input id="url" name="url" size="61" value="" /><br />
Comment:
<label for="comment">Leave this field comment blank:</label>
<textarea id="comment" name="comment" cols="50" rows="10"></textarea><br />
</p>

<?php }

// Print header
echo $header;

// Get list of fields
$query_fields = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_form_fields WHERE section_id = '$section_id' ORDER BY position ASC");

if($query_fields->numRows() > 0) {
	while($field = $query_fields->fetchRow()) {
		// Set field values
		$field_id = $field['field_id'];
		$value = $field['value'];
		// Print field_loop after replacing vars with values
		$vars = array('{TITLE}', '{REQUIRED}');
		if (($field['type'] == "radio") || ($field['type'] == "checkbox")) {
			$field_title = $field['title'];
		} else {
			$field_title = '<label for="field'.$field_id.'">'.$field['title'].'</label>';
		}
		$values = array($field_title);
		if ($field['required'] == 1) {
			$values[] = '<span class="required">*</span>';
		} else {
			$values[] = '';
		}
		if($field['type'] == 'textfield') {
			$vars[] = '{FIELD}';
			$values[] = '<input type="text" name="field'.$field_id.'" id="field'.$field_id.'" maxlength="'.$field['extra'].'" value="'.(isset($_SESSION['field'.$field_id])?$_SESSION['field'.$field_id]:$value).'" class="textfield" />';
		} elseif($field['type'] == 'textarea') {
			$vars[] = '{FIELD}';
			$values[] = '<textarea name="field'.$field_id.'" id="field'.$field_id.'" class="textarea" cols="25" rows="5">'.(isset($_SESSION['field'.$field_id])?$_SESSION['field'.$field_id]:$value).'</textarea>';
		} elseif($field['type'] == 'select') {
			$vars[] = '{FIELD}';
			$options = explode(',', $value);
			array_walk($options, 'make_option', (isset($_SESSION['field'.$field_id])?$_SESSION['field'.$field_id]:array()));
			$field['extra'] = explode(',',$field['extra']);
			$values[] = '<select name="field'.$field_id.'[]" id="field'.$field_id.'" size="'.$field['extra'][0].'" '.$field['extra'][1].' class="select">'.implode($options).'</select>';		
		} elseif($field['type'] == 'heading') {
			$vars[] = '{FIELD}';
			$str = '<input type="hidden" name="field'.$field_id.'" id="field'.$field_id.'" value="===['.$field['title'].']===" />';
			$values[] = ( true == $use_xhtml_strict) ? "<div>".$str."</div>" : $str;
			$tmp_field_loop = $field_loop;		// temporarily modify the field loop template
			$field_loop = $field['extra'];
		} elseif($field['type'] == 'checkbox') {
			$vars[] = '{FIELD}';
			$options = explode(',', $value);
			array_walk($options, 'make_checkbox', array(array($field_id,$field['extra']),(isset($_SESSION['field'.$field_id])?$_SESSION['field'.$field_id]:array())));
			$options[count($options)-1]=substr($options[count($options)-1],0,strlen($options[count($options)-1])-strlen($field['extra']));
			$values[] = implode($options);
		} elseif($field['type'] == 'radio') {
			$vars[] = '{FIELD}';
			$options = explode(',', $value);
			array_walk($options, 'make_radio', array($field_id,$field['title'],$field['extra'], (isset($_SESSION['field'.$field_id])?$_SESSION['field'.$field_id]:'')));
			$options[count($options)-1]=substr($options[count($options)-1],0,strlen($options[count($options)-1])-strlen($field['extra']));
			$values[] = implode($options);
		} elseif($field['type'] == 'email') {
			$vars[] = '{FIELD}';
			$values[] = '<input type="text" name="field'.$field_id.'" id="field'.$field_id.'" value="'.(isset($_SESSION['field'.$field_id])?$_SESSION['field'.$field_id]:'').'" maxlength="'.$field['extra'].'" class="email" />';
		}
		if(isset($_SESSION['field'.$field_id])) unset($_SESSION['field'.$field_id]);
		if($field['type'] != '') {
			echo str_replace($vars, $values, $field_loop);
		}
		if (isset($tmp_field_loop)) $field_loop = $tmp_field_loop;
	}
}

// Captcha
if($use_captcha) { ?>
	<tr>
	<td class="field_title"><?php echo $TEXT['VERIFICATION']; ?>:</td>
	<td><?php call_captcha(); ?></td>
	</tr>
	<?php
}

// Print footer
echo $footer;

/**
	NOTE: comment out the line ob_end_flush() if you indicate problems (e.g. when using ob_start in the index.php of your template)
	With ob_end_flush(): output filter will be disabled for this page (and all sections embedded on this page)
	Without ob_end_flush(): emails are rewritten (e.g. name@domain.com --> name(at)domain(dot)com) if output filter is enabled
	All replacements made by the Output-Filter module will be reverted before the email is send out
*/
if($filter_settings['email_filter'] && !($filter_settings['at_replacement']=='@' && $filter_settings['dot_replacement']=='.')) {
  /* 	ob_end_flush(); */
}

// Add form end code
?>
</form>
<?php

} else {

	// Check that submission ID matches
	if(isset($_SESSION['form_submission_id']) AND isset($_POST['submission_id']) AND $_SESSION['form_submission_id'] == $_POST['submission_id']) {
		
		// Set new submission ID in session
		$_SESSION['form_submission_id'] = new_submission_id();
		
		if(ENABLED_ASP && ( // form faked? Check the honeypot-fields.
			(!isset($_POST['submitted_when']) OR !isset($_SESSION['submitted_when'])) OR 
			($_POST['submitted_when'] != $_SESSION['submitted_when']) OR
			(!isset($_POST['email']) OR $_POST['email']) OR
			(!isset($_POST['homepage']) OR $_POST['homepage']) OR
			(!isset($_POST['comment']) OR $_POST['comment']) OR
			(!isset($_POST['url']) OR $_POST['url'])
		)) {
			exit(header("Location: ".WB_URL.PAGES_DIRECTORY.""));
		}

		// Submit form data
		// First start message settings
		$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_form_settings WHERE section_id = '$section_id'");
		if($query_settings->numRows() > 0) {
			$fetch_settings = $query_settings->fetchRow();
			$email_to = $fetch_settings['email_to'];
			$email_from = $fetch_settings['email_from'];
			if(substr($email_from, 0, 5) == 'field') {
				// Set the email from field to what the user entered in the specified field
				$email_from = htmlspecialchars($wb->add_slashes($_POST[$email_from]));
			}
			$email_fromname = $fetch_settings['email_fromname'];
			$email_subject = $fetch_settings['email_subject'];
			$success_page = $fetch_settings['success_page'];
			$success_email_to = $fetch_settings['success_email_to'];
			if(substr($success_email_to, 0, 5) == 'field') {
				// Set the success_email to field to what the user entered in the specified field
				$success_email_to = htmlspecialchars($wb->add_slashes($_POST[$success_email_to]));
			}
			$success_email_from = $fetch_settings['success_email_from'];
			$success_email_fromname = $fetch_settings['success_email_fromname'];
			$success_email_text = $fetch_settings['success_email_text'];
			$success_email_subject = $fetch_settings['success_email_subject'];		
			$max_submissions = $fetch_settings['max_submissions'];
			$stored_submissions = $fetch_settings['stored_submissions'];
			$use_captcha = $fetch_settings['use_captcha'];
		} else {
			exit($TEXT['UNDER_CONSTRUCTION']);
		}
		$email_body = '';
		
		// Create blank "required" array
		$required = array();
		
		// Captcha
		if($use_captcha) {
			if(isset($_POST['captcha']) AND $_POST['captcha'] != ''){
				// Check for a mismatch
				if(!isset($_POST['captcha']) OR !isset($_SESSION['captcha']) OR $_POST['captcha'] != $_SESSION['captcha']) {
					$captcha_error = $MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'];
				}
			} else {
				$captcha_error = $MESSAGE['MOD_FORM_INCORRECT_CAPTCHA'];
			}
		}
		if(isset($_SESSION['captcha'])) { unset($_SESSION['captcha']); }

		// Loop through fields and add to message body
		// Get list of fields
		$query_fields = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_form_fields` WHERE section_id = '$section_id' ORDER BY position ASC");
		if($query_fields->numRows() > 0) {
			while($field = $query_fields->fetchRow( MYSQL_ASSOC )) {
				// Add to message body
				if($field['type'] != '') {
					if(!empty($_POST['field'.$field['field_id']])) {
						if (is_array($_POST['field'.$field['field_id']])) {
							$_SESSION['field'.$field['field_id']] = $_POST['field'.$field['field_id']];
						} else {
							$_SESSION['field'.$field['field_id']] = htmlspecialchars($_POST['field'.$field['field_id']]);
						}
						// if the output filter is active, we need to revert (dot) to . and (at) to @ (using current filter settings)
						// otherwise the entered mail will not be accepted and the recipient would see (dot), (at) etc.
						if ($filter_settings['email_filter']) {
							$field_value = $_POST['field'.$field['field_id']];
							$field_value = str_replace($filter_settings['at_replacement'], '@', $field_value);
							$field_value = str_replace($filter_settings['dot_replacement'], '.', $field_value);
							$_POST['field'.$field['field_id']] = $field_value;
						}
						if($field['type'] == 'email' AND $admin->validate_email($_POST['field'.$field['field_id']]) == false) {
							$email_error = $MESSAGE['USERS']['INVALID_EMAIL'];
						}
						if($field['type'] == 'heading') {
							$email_body .= $_POST['field'.$field['field_id']]."\n\n";
						} elseif (!is_array($_POST['field'.$field['field_id']])) {
							//$email_body .= $field['title'].': '.$_POST['field'.$field['field_id']]."\n\n";
							$email_body .= $field['title'].': '.str_replace(array("[[", "]]"), array("&#91;&#91;", "&#93;&#93;"), htmlspecialchars($admin->get_post_escaped('field'.$field['field_id']), ENT_QUOTES))."\n\n";
						} else {
							$email_body .= $field['title'].": \n";
							foreach ($_POST['field'.$field['field_id']] as $k=>$v) {
								$email_body .= $v."\n";
							}
							$email_body .= "\n";
						}
					} elseif($field['required'] == 1) {
						$required[] = $field['title'];
					}
				}
			}
		}
	
		// Check if the user forgot to enter values into all the required fields
		if($required != array()) {
			if(!isset($MESSAGE['MOD_FORM_REQUIRED_FIELDS'])) {
				echo 'You must enter details for the following fields';
			} else {
				echo $MESSAGE['MOD_FORM_REQUIRED_FIELDS'];
			}
			echo ':<br /><ul>';
			foreach($required AS $field_title) {
				echo '<li>'.$field_title;
			}
			if(isset($email_error)) {
				echo '<li>'.$email_error.'</li>';
			}
			if(isset($captcha_error)) {
				echo '<li>'.$captcha_error.'</li>';
			}
			echo '</ul><a href="'.htmlspecialchars(strip_tags($_SERVER['SCRIPT_NAME'])).'">'.$TEXT['BACK'].'</a>';
		} else {
			if(isset($email_error)) {
				echo '<br /><ul>';
				echo '<li>'.$email_error.'</li>';
				echo '</ul><a href="'.htmlspecialchars(strip_tags($_SERVER['SCRIPT_NAME'])).'">'.$TEXT['BACK'].'</a>';
			} elseif(isset($captcha_error)) {
				echo '<br /><ul>';
				echo '<li>'.$captcha_error.'</li>';
				echo '</ul><a href="'.htmlspecialchars(strip_tags($_SERVER['SCRIPT_NAME'])).'">'.$TEXT['BACK'].'</a>';
			} else {
				// Check how many times form has been submitted in last hour
				$last_hour = time()-3600;
				$query_submissions = $database->query("SELECT submission_id FROM ".TABLE_PREFIX."mod_form_submissions WHERE submitted_when >= '$last_hour'");
				if($query_submissions->numRows() > $max_submissions) {
					// Too many submissions so far this hour
					echo $MESSAGE['MOD_FORM_EXCESS_SUBMISSIONS'];
					$success = false;
				} else {
					/**	
					 *	Adding the IP to the body and try to send the email
					 */
					$email_body .= "\n\nIP: ".$_SERVER['REMOTE_ADDR'];
					
					if($email_to != '') {
						if($email_from != '') {
							if($wb->mail($email_from,$email_to,$email_subject,$email_body,$email_fromname)) {
								$success = true;
							}
						} else {
							if($wb->mail('',$email_to,$email_subject,$email_body,$email_fromname)) { 
								$success = true; 
							}
						}
					}				
					if($success_email_to != '') {
						if($success_email_from != '') {
							if($wb->mail($success_email_from,$success_email_to,$success_email_subject,$success_email_text,$success_email_fromname)) {
								$success = true;
							}
						} else {
							if($wb->mail('',$success_email_to,$success_email_subject,$success_email_text,$success_email_fromname)) {
								$success = true;
							}
						}
					}				
			
					// Write submission to database
					if(isset($admin) AND $admin->is_authenticated() AND $admin->get_user_id() > 0) {
						$submitted_by = $admin->get_user_id();
					} else {
						$submitted_by = 0;
					}
					$email_body = $wb->add_slashes($email_body);
					
					$query 	= "INSERT INTO `".TABLE_PREFIX."mod_form_submissions`";
					$query .= "(`page_id`,`section_id`,`submitted_when`,`submitted_by`,`body`) ";
					$query .= "VALUES ('".PAGE_ID."','".$section_id."','".time()."','";
					$query .= mysql_real_escape_string($submitted_by)."','".mysql_real_escape_string($email_body)."')";
					
					$database->query( $query );
					
					// Make sure submissions table isn't too full
					$query_submissions = $database->query("SELECT submission_id FROM ".TABLE_PREFIX."mod_form_submissions ORDER BY submitted_when");
					$num_submissions = $query_submissions->numRows();
					if($num_submissions > $stored_submissions) {
						// Remove excess submission
						$num_to_remove = $num_submissions-$stored_submissions;
						while($submission = $query_submissions->fetchRow()) {
							if($num_to_remove > 0) {
								$submission_id = $submission['submission_id'];
								$database->query("DELETE FROM ".TABLE_PREFIX."mod_form_submissions WHERE submission_id = '$submission_id'");
								$num_to_remove = $num_to_remove-1;
							}
						}
					}
					if(!$database->is_error()) {
						$success = true;
					}
				}
			}	
		}
	}
	
	// Now check if the email was sent successfully
	if(isset($success) AND $success == true) {
	   if ($success_page=='none') {
			echo str_replace("\n","<br />",$success_email_text);
  		} else {
			$query_menu = $database->query("SELECT link,target FROM ".TABLE_PREFIX."pages WHERE `page_id` = '$success_page'");
			if($query_menu->numRows() > 0) {
  	        	$fetch_settings = $query_menu->fetchRow();
			   $link = WB_URL.PAGES_DIRECTORY.$fetch_settings['link'].PAGE_EXTENSION;
			   echo "<script type='text/javascript'>location.href='".$link."';</script>";
			}    
		}
		// clearing session on success
		$query_fields = $database->query("SELECT field_id FROM ".TABLE_PREFIX."mod_form_fields WHERE section_id = '$section_id'");
		while($field = $query_fields->fetchRow()) {
			$field_id = $field[0];
			if(isset($_SESSION['field'.$field_id])) unset($_SESSION['field'.$field_id]);
		}
	} else {
		if(isset($success) AND $success == false) {
			echo $TEXT['ERROR'];
		}
	}
}

?>