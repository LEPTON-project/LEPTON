//:Emailfiltering on your output - output filtering with the options below - Mailto links can be encrypted by a Javascript
//:usage:  [[EmailFilter]] 
 
// You can configure the output filtering with the options below.
// Tip: Mailto links can be encrypted by a Javascript function. 
// To make use of this option, one needs to add the PHP code 
//       register_frontend_modfiles('js');
// into the <head> section of the index.php of your template. 
// Without this modification, only the @ character in the mailto part will be replaced.

// Basic Email Configuration: 
// Filter Email addresses in text 0 = no, 1 = yes - default 1
$filter_settings['email_filter'] = '1';

// Filter Email addresses in mailto links 0 = no, 1 = yes - default 1
$filter_settings['mailto_filter'] = '1';

// Email Replacements, replace the '@' and the '.' by default (at) and (dot)
$filter_settings['at_replacement']  = '(at)';
$filter_settings['dot_replacement'] = '(dot)';

// No need to change stuff underneatch unless you know what you are doing.

// work out the defined output filter mode: possible output filter modes: [0], 1, 2, 3, 6, 7
// 2^0 * (0.. disable, 1.. enable) filtering of mail addresses in text
// 2^1 * (0.. disable, 1.. enable) filtering of mail addresses in mailto links
// 2^2 * (0.. disable, 1.. enable) Javascript mailto encryption (only if mailto filtering enabled)

// only filter output if we are supposed to
if($filter_settings['email_filter'] != '1' && $filter_settings['mailto_filter'] != '1'){
	// nothing to do ...
	return true;
}

// check if non mailto mail addresses needs to be filtered
$output_filter_mode = ($filter_settings['email_filter'] == '1') ? 1 : 0;		// 0|1
	
// check if mailto mail addresses needs to be filtered
if($filter_settings['mailto_filter'] == '1')
{
	$output_filter_mode = $output_filter_mode + 2;								// 0|2
					
        // check if Javascript mailto encryption is enabled (call register_frontend_functions in the template)
        $search_pattern = '/<.*src=\".*\/mdcr.js.*>/iU';
        if(preg_match($search_pattern, $wb_page_data))
        {
          $output_filter_mode = $output_filter_mode + 4;       // 0|4
        }
}
		
// define some constants so we do not call the database in the callback function again
define('OUTPUT_FILTER_MODE', (int) $output_filter_mode);
define('OUTPUT_FILTER_AT_REPLACEMENT', $filter_settings['at_replacement']);
define('OUTPUT_FILTER_DOT_REPLACEMENT', $filter_settings['dot_replacement']);
	
// function to filter mail addresses embedded in text or mailto links before outputing them on the frontend
if (!function_exists('filter_mail_addresses')) {
	function filter_mail_addresses($match) { 
		
	// check if required output filter mode is defined
		if(!(defined('OUTPUT_FILTER_MODE') && defined('OUTPUT_FILTER_MODE') && defined('OUTPUT_FILTER_MODE'))) {
			return $match[0];
		}
		
		$search = array('@', '.');
		$replace = array(OUTPUT_FILTER_AT_REPLACEMENT ,OUTPUT_FILTER_DOT_REPLACEMENT);
		
		// check if the match contains the expected number of subpatterns (6|8)
		if(count($match) == 8) {
			/**
				OUTPUT FILTER FOR EMAIL ADDRESSES EMBEDDED IN TEXT
			**/
			
			// 1.. text mails only, 3.. text mails + mailto (no JS), 7 text mails + mailto (JS)
			if(!in_array(OUTPUT_FILTER_MODE, array(1,3,7))) return $match[0];

			// do not filter mail addresses included in input tags (<input ... value = "test@mail)
			if (strpos($match[6], 'value') !== false) return $match[0];
			
			// filtering of non mailto email addresses enabled
			return str_replace($search, $replace, $match[0]);
				
		} elseif(count($match) == 6) {
			/**
				OUTPUT FILTER FOR EMAIL ADDRESSES EMBEDDED IN MAILTO LINKS
			**/

			// 2.. mailto only (no JS), 3.. text mails + mailto (no JS), 6.. mailto only (JS), 7.. all filters active
			if(!in_array(OUTPUT_FILTER_MODE, array(2,3,6,7))) return $match[0];
			
			// check if last part of the a href link: >xxxx</a> contains a email address we need to filter
			$pattern = '#[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}#i';
			if(preg_match_all($pattern, $match[5], $matches)) {
				foreach($matches as $submatch) {
					foreach($submatch as $value) {
						// replace all . and all @ in email address parts by (dot) and (at) strings
						$match[5] = str_replace($value, str_replace($search, $replace, $value), $match[5]);
					}
				}
			}

			// check if Javascript encryption routine is enabled
			if(in_array(OUTPUT_FILTER_MODE, array(6,7))) {
				/** USE JAVASCRIPT ENCRYPTION FOR MAILTO LINKS **/
				
				// extract possible class and id attribute from ahref link
				preg_match('/class\s*?=\s*?("|\')(.*?)\1/ix', $match[0], $class_attr);
				$class_attr = empty($class_attr) ? '' : 'class="' . $class_attr[2] . '" ';
				preg_match('/id\s*?=\s*?("|\')(.*?)\1/ix', $match[0], $id_attr);
				$id_attr = empty($id_attr) ? '' : 'id="' . $id_attr[2] . '" ';
				
				// preprocess mailto link parts for further usage
				$search = array('@', '.', '_', '-'); $replace = array('F', 'Z', 'X', 'K');
				$email_address = str_replace($search, $replace, strtolower($match[2]));
				$email_subject = rawurlencode(html_entity_decode($match[3]));
				
				// create a random encryption key for the Caesar cipher
				mt_srand((double)microtime()*1000000);	// (PHP < 4.2.0)
				$shift = mt_rand(1, 25);
				
				// encrypt the email using an adapted Caesar cipher
		  		$encrypted_email = "";
				for($i = strlen($email_address) -1; $i > -1; $i--) {
					if(preg_match('#[FZXK0-9]#', $email_address[$i], $characters)) {
						$encrypted_email .= $email_address[$i];
					} else {	
						$encrypted_email .= chr((ord($email_address[$i]) -97 + $shift) % 26 + 97);
					}
				}
				$encrypted_email .= chr($shift + 97);

				// build the encrypted Javascript mailto link
				$mailto_link  = "<a {$class_attr}{$id_attr}href=\"javascript:mdcr('$encrypted_email','$email_subject')\">" .$match[5] ."</a>";
				
				return $mailto_link;	

			} else {
				/** DO NOT USE JAVASCRIPT ENCRYPTION FOR MAILTO LINKS **/

				// as minimum protection, replace replace @ in the mailto part by (at)
				// dots are not transformed as this would transform my.name@domain.com into: my(dot)name(at)domain(dot)com
				
				// rebuild the mailto link from the subpatterns (at the missing characters " and </a>")
				return $match[1] .str_replace('@', OUTPUT_FILTER_AT_REPLACEMENT, $match[2]) .$match[3] .'"' .$match[4] .$match[5] .'</a>';
				// if you want to protect both, @ and dots, comment out the line above and remove the comment from the line below
				// return $match[1] .str_replace($search, $replace, $match[2]) .$match[3] .'"' .$match[4] .$match[5] .'</a>';
			}
		
		}
		
		// number of subpatterns do not match the requirements ... do nothing
		return $match[0];
	}		
}
	
// first search part to find all mailto email addresses
$pattern = '#(<a[^<]*href\s*?=\s*?"\s*?mailto\s*?:\s*?)([A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4})([^"]*?)"([^>]*>)(.*?)</a>';
// second part to find all non mailto email addresses
$pattern .= '|(value\s*=\s*"|\')??\b([A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4})\b#i';

// Sub 1:\b(<a.[^<]*href\s*?=\s*?"\s*?mailto\s*?:\s*?)		-->	"<a id="yyy" class="xxx" href = " mailto :" ignoring white spaces
// Sub 2:([A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4})		-->	the email address in the mailto: part of the mail link
// Sub 3:([^"]*?)"							--> possible ?Subject&cc... stuff attached to the mail address
// Sub 4:([^>]*>)							--> all class or id statements after the mailto but before closing ..>
// Sub 5:(.*?)</a>\b						--> the mailto text; all characters between >xxxxx</a>
// Sub 6:|\b([A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4})\b		--> email addresses which may appear in the text (require word boundaries)
$content = $wb_page_data;			
// find all email addresses embedded in the content and filter them using a callback function
$content = preg_replace_callback($pattern, 'filter_mail_addresses', $content);
$wb_page_data = $content;
return true;
		
