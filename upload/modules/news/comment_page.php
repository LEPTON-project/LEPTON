<?php

/**
 *  @module         news
 *  @version        see info.php of this module
 *  @author         Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos), LEPTON Project
 *  @copyright      2004-2010 Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos) 
 * 	@copyright      2010-2017 LEPTON Project 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
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

/* check if frontend.css file needs to be included into the <body></body> of page  */
if ( (!function_exists('register_frontend_modfiles') || !defined('MOD_FRONTEND_CSS_REGISTERED')) && file_exists(LEPTON_PATH .'/modules/news/frontend.css')) {
	echo '<style type="text/css">';
	include(LEPTON_PATH .'/modules/news/frontend.css');
	echo "\n</style>\n";
}

// check if module language file exists for the language set by the user (e.g. DE, EN)
$lang = (dirname(__FILE__)) . '/languages/' . LANGUAGE . '.php';
require_once(!file_exists($lang) ? (dirname(__FILE__)) . '/languages/EN.php' : $lang );

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

require_once (LEPTON_PATH."/modules/lib_twig/classes/class.twig_utilities.php");
$twig_util = new twig_utilities( $parser, $loader, $module_template_path, $frontend_template_path );
$twig_util->template_namespace = "news";

// End of template-engines settings.

require_once(LEPTON_PATH.'/modules/captcha_control/captcha/captcha.php');

// Get comments page template details from db
$query_settings = $database->query("SELECT `use_captcha`,`commenting` FROM `".TABLE_PREFIX."mod_news_settings` WHERE `section_id` = '".SECTION_ID."'");
if($query_settings->numRows() == 0)
{
	header("Location: ".LEPTON_URL.PAGES_DIRECTORY."");
	exit( 0 );
}
else
{
	$settings = $query_settings->fetchRow();

	// Print comments page
	$vars = array(
		'POST_TITLE'	=> POST_TITLE,
		'TEXT_COMMENT'	=> $MOD_NEWS['TEXT_COMMENT']
	);
	
	$twig_util->resolve_path("comments_page.lte");
	
	echo $parser->render(
		'@news/comments_page.lte',
		$vars
	);
	
	$current_time=time(); 
	$_SESSION['submitted_when']=$current_time;
	
	/**
	 *	Here we go:
	 */
	$form_data = array(
		'LEPTON_URL'	=> LEPTON_URL,
		'SECTION_ID'	=> SECTION_ID,
		'PAGE_ID'	=> PAGE_ID,
		'POST_ID'	=> POST_ID,
		'ENABLED_ASP' => ( ENABLED_ASP ? 1 : 0 ),
		'TEXT'	=> $TEXT,
		'MOD_NEWS' => $MOD_NEWS,
		'captcha_error' => isset($_SESSION['captcha_error']) ? 1 : 0,
		'captcha_error_message' => isset($_SESSION['captcha_error']) ? $_SESSION['captcha_error'] : "",
		'use_captcha'	=> $settings['use_captcha'],
		'call_captcha'	=> $twig_util->capture_echo("call_captcha();"),
		'comment_title'	=> isset($_SESSION['comment_title']) ? $_SESSION['comment_title'] : "",
		'comment_body'	=> isset($_SESSION['comment_body']) ? $_SESSION['comment_body'] : "",
		'leptoken'	=> isset($_GET['leptoken']) ? $_GET['leptoken'] : "",
		'date_w'	=> date('W'),
		'form_submitted_when' => $current_time 
	);
	
	echo $parser->render(
		'@news/comments_form.lte',
		$form_data
	);

	if(isset($_SESSION['comment_title'])) unset($_SESSION['comment_title']);
	if(isset($_SESSION['comment_body'])) unset($_SESSION['comment_body']);
	if(isset($_SESSION['captcha_error'])) unset($_SESSION['captcha_error']);
}
?>