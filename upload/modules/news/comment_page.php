<?php

/**
 *  @module         news
 *  @version        see info.php of this module
 *  @author         Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos)
 *  @copyright      2004-2013 Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos) 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *  @requirements   PHP 5.2.x and higher
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

/* check if frontend.css file needs to be included into the <body></body> of page  */
if ( (!function_exists('register_frontend_modfiles') || !defined('MOD_FRONTEND_CSS_REGISTERED')) && file_exists(WB_PATH .'/modules/news/frontend.css')) {
	echo '<style type="text/css">';
	include(WB_PATH .'/modules/news/frontend.css');
	echo "\n</style>\n";
}

// check if module language file exists for the language set by the user (e.g. DE, EN)
if(!file_exists(WB_PATH .'/modules/news/languages/'.LANGUAGE .'.php'))
{
	// no module language file exists for the language set by the user, include default module language file EN.php
	require_once(WB_PATH .'/modules/news/languages/EN.php');
}
else
{
	// a module language file exists for the language defined by the user, load it
	require_once(WB_PATH .'/modules/news/languages/'.LANGUAGE .'.php');
}

/**
 *	Try to get the template-engine.
 *
 */
global $parser, $loader;
if (!isset($parser))
{
	require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
}

$loader->prependPath( dirname(__FILE__)."/templates/", "news" );

$frontend_template_path = LEPTON_PATH."/templates/".DEFAULT_TEMPLATE."/frontend/news/";
$module_template_path = dirname(__FILE__)."/templates/";

// End of template-engines settings.

require_once(WB_PATH.'/include/captcha/captcha.php');

// Get comments page template details from db
$query_settings = $database->query("SELECT comments_page,use_captcha,commenting FROM ".TABLE_PREFIX."mod_news_settings WHERE section_id = '".SECTION_ID."'");
if($query_settings->numRows() == 0)
{
	header("Location: ".WB_URL.PAGES_DIRECTORY."");
	exit( 0 );
}
else
{
	$settings = $query_settings->fetchRow( MYSQL_ASSOC );

	// Print comments page
	$vars = array(
		'POST_TITLE'	=> POST_TITLE,
		'TEXT_COMMENT'	=> $MOD_NEWS['TEXT_COMMENT']
	);
	
	// Aldus: oh my goodness - will it be end someday?
	// echo str_replace($vars, $values, $settings['comments_page'] );
	
	if (file_exists($module_template_path."comments_page.lte")) {
		if (file_exists($frontend_template_path."comments_page.lte")) $loader->prependPath( $frontend_template_path, 'news' );
		
		echo $parser->render(
			'@news/comments_page.lte',
			$vars
		);
			
	} else {
		$str = $settings['comments_page'];
		foreach($vars as $key => $val) $str = str_replace( "[".$key."]", $val, $str);
		echo $str;
	}
	
	?>
	<form name="comment" action="<?php echo WB_URL.'/modules/news/submit_comment.php?page_id='.PAGE_ID.'&amp;section_id='.SECTION_ID.'&amp;post_id='.POST_ID; ?>" method="post">
	<?php if(ENABLED_ASP) { // add some honeypot-fields
	?>
	<input type="hidden" name="submitted_when" value="<?php $t=time(); echo $t; $_SESSION['submitted_when']=$t; ?>" />
	<p class="nixhier">
	email address:
	<label for="email">Leave this field email blank:</label>
	<input id="email" name="email" size="60" value="" /><br />
	Homepage:
	<label for="homepage">Leave this field homepage blank:</label>
	<input id="homepage" name="homepage" size="60" value="" /><br />
	URL:
	<label for="url">Leave this field url blank:</label>
	<input id="url" name="url" size="60" value="" /><br />
	Comment:
	<label for="comment">Leave this field comment blank:</label>
	<input id="comment" name="comment" size="60" value="" /><br />
	</p>
	<?php }
	?>
	<?php echo $TEXT['TITLE']; ?>:
	<br />
	<input type="text" name="title" maxlength="255" style="width: 90%;"<?php if(isset($_SESSION['comment_title'])) { echo ' value="'.$_SESSION['comment_title'].'"'; unset($_SESSION['comment_title']); } ?> />
	<br /><br />
	<?php echo $TEXT['COMMENT']; 
	?>:
	<br />
	<?php if(ENABLED_ASP) { ?>
		<textarea name="comment_<?php echo date('W'); ?>" rows="10" cols="1" style="width: 90%; height: 150px;"><?php if(isset($_SESSION['comment_body'])) { echo $_SESSION['comment_body']; unset($_SESSION['comment_body']); } ?></textarea>
	<?php } else { ?>
		<textarea name="comment" rows="10" cols="1" style="width: 90%; height: 150px;"><?php if(isset($_SESSION['comment_body'])) { echo $_SESSION['comment_body']; unset($_SESSION['comment_body']); } ?></textarea>
	<?php } ?>
	<br /><br />
	<?php
	if(isset($_SESSION['captcha_error'])) {
		echo '<font color="#FF0000">'.$_SESSION['captcha_error'].'</font><br />';
		$_SESSION['captcha_retry_news'] = true;
	}
	// Captcha
	if($settings['use_captcha']) {
	?>
	<table cellpadding="2" cellspacing="0" border="0">
	<tr>
		<td><?php echo $TEXT['VERIFICATION']; ?>:</td>
		<td><?php call_captcha(); ?></td>
	</tr>
    </table>
	<?php
	if(isset($_SESSION['captcha_error'])) {
		unset($_SESSION['captcha_error']);
		?><script>document.comment.captcha.focus();</script><?php
	}?>
	<?php
	}
	?>
	<table class="news-table">
	<tr>
	    <td>
            <input type="submit" name="submit" value="<?php echo $MOD_NEWS['TEXT_ADD_COMMENT']; ?>" />
        </td>
        <td>
		    <input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="history.go(-1)"  />
        </td>
	</tr>
    </table>
	</form>
	<?php
}

?>