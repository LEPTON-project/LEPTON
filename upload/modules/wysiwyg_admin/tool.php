<?php

/**
 *	@module			wysiwyg Admin
 *	@version		see info.php of this module
 *	@authors		Dietrich Roland Pehlke
 * @copyright       2010-2017 Dietrich Roland Pehlke
 *	@license		GNU General Public License
 *	@license terms	see info.php of this module
 *	@platform		see info.php of this module
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
 
$debug = true;

if (true === $debug) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL|E_STRICT);
}

if (!isset($admin) || !is_object($admin)) die();

$lang = dirname(__FILE__)."/languages/".LANGUAGE.".php";
include( file_exists($lang) ? $lang : dirname(__FILE__)."/languages/EN.php" );

/**
 *	New way to get information about the used editor.
 */
if (!defined("LEPTON_PATH") ) define("LEPTON_PATH", LEPTON_PATH);

/**	*******************************
 *	Try to get the template-engine.
 */
global $parser, $loader;
require( dirname(__FILE__)."/register_parser.php" );

$look_up = LEPTON_PATH."/modules/".WYSIWYG_EDITOR."/class.editorinfo.php";
if (file_exists($look_up)) {
	require_once( $look_up );
	if (!isset($editor_ref) || !is_object($editor_ref)) eval( "\$editor_ref = new editorinfo_".strtoupper(WYSIWYG_EDITOR)."();" );

} else {
	echo "<p><h3 style='color:#FF0000;'>WARNING: use of obsolete drivers!</h3></p>";
	echo "<p style='color:#FF0000;'>Please add a class.editorinfo.php to the current wysiwyg-editor!</p>";
	// Backwards compatible to 0.2.x
	require_once( dirname(__FILE__)."/driver/".WYSIWYG_EDITOR."/c_editor.php");
	if (!isset($editor_ref) || !is_object($editor_ref)) $editor_ref = new c_editor();
}

$table = TABLE_PREFIX."mod_wysiwyg_admin";

/**
 *	Something to save or delete?
 *
 */
if (isset($_POST['job'])) {
	if ($_POST['job']=="save") {
		if (isset($_SESSION['wysiwyg_admin']) && $_POST['salt'] === $_SESSION['wysiwyg_admin']) {
			
			$values =  array_map('strip_tags', $_POST);
			
			/**
			 *	Time?
			 *
			 */
			$test_time = time() - $_POST['t'];
			
			if ($test_time <= (60*5)) {
				
				$fields = array(
					'skin'	=> $values['skin'],
					'menu'	=> $values['menu'],
					'width' => $values['width'],
					'height' => $values['height']
				);
				
				$database->build_and_execute(
					'update',
					$table,
					$fields,
					"id='".$values['id']."'"
				);
			}
		}
	}
}

$query = "SELECT `id`,`skin`,`menu`,`height`,`width` from `".$table."` where `editor`='".WYSIWYG_EDITOR."'limit 0,1";
$result = $database->query ($query );
if ($result->numRows() == 1) {
	$data = $result->fetchRow();
} else {

	$lookup = LEPTON_PATH."/modules/".WYSIWYG_EDITOR."/class.editorinfo.php";
	if (file_exists($lookup)) {
	
		require_once( $lookup );
		eval( "\$editor_info = new editorinfo_".strtoupper(WYSIWYG_EDITOR)."();" );
		$editor_info->wysiwyg_admin_init( $database );
		
		$last_insert_id = (true === $database->db_handle instanceof PDO )
			? $database->db_handle->lastInsertId()
			: $database->getOne("SELECT LAST_INSERT_ID()")
			;
		
		$toolbars = array_keys( $editor_info->toolbars );
		
		$data = array(
			'id'	=> $last_insert_id,
			'skin' => $editor_info->skins[0],
			'menu' => $toolbars[0],
			'width' => $editor_info->default_width,
			'height' => $editor_info->default_height
		);

	} else {
		// no editor-info avaible - so we have to use empty values
		$database->query("INSERT into ".$table." (editor, skin, menu, width, height) values ('".WYSIWYG_EDITOR."','','', '100%', '250px')");
	
		$last_insert_id = (true === $database->db_handle instanceof PDO )
			? $database->db_handle->lastInsertId()
			: $database->getOne("SELECT LAST_INSERT_ID()")
			;
		
		$data = array(
			'id'	=> $last_insert_id,
			'skin' => '',
			'menu' => '',
			'width' => '100%',
			'height' => '250px'
		);
	}
}

$primes = array(
	'176053', '176063', '176081', '176087', '176089', '176123', '176129', '176153', '176159',
	'176161', '176179', '176191', '176201', '176207', '176213', '176221', '176227', '176237', 
    '176299', '176303', '176317', '176321', '176327', '176243', '176261'
);
shuffle($primes);
$s = array_shift($primes)."-".array_shift($primes);

$salt = sha1( $s.time()." Und Schlag auf Schlag werd' ich zum Augenblicke sagen: du bist so schön!".$_SERVER['HTTP_USER_AGENT'].microtime().$_SESSION['session_started']);

if (isset($_SESSION['wysiwyg_admin'])) unset($_SESSION['wysiwyg_admin']);
$_SESSION['wysiwyg_admin'] = $salt;

$leptoken = (isset($_GET['leptoken']) ? "?leptoken=".$_GET['leptoken'] : "" );

$interface_values = array(
	'ADMIN_URL'	=> ADMIN_URL,
	'salt' => $salt,
	'time' => TIME(),
	'id'	=> $data['id'],
	'label_SKINS' => $MOD_WYSIWYG_ADMIN['SKINS'],
	'label_TOOL'	=> $MOD_WYSIWYG_ADMIN['TOOL'], // Toolbar!
	'select_SKIN'	=> $editor_ref->build_select("skins", "skin", $data['skin']),
	'select_TOOL'	=> $editor_ref->build_select("toolbars", "menu", $data['menu']),
	'label_WIDTH'	=> $MOD_WYSIWYG_ADMIN['WIDTH'],
	'width'	=> $data['width'],
	'label_HEIGHT'	=> $MOD_WYSIWYG_ADMIN['HEIGHT'],
	'height'	=> $data['height'],
	'SAVE'	=> $TEXT['SAVE'],
	'CANCEL' => $TEXT['CANCEL'],
	'leptoken'	=> $leptoken
);

$twig_util->resolve_path("modify.lte");

echo $parser->render( 
	$twig_modul_namespace. "modify.lte", // template-filename
	$interface_values	//	template-data
);

// Preview section:
	$section_id = -1;
	$page_id = -120;
	$_GET['page_id'] = $page_id;
	$preview = true;
	$h = $data['height'];
	$w = $data['width'];

	global $id_list;
	$id_list= array( 1 );
	
	require_once(LEPTON_PATH."/modules/wysiwyg/modify.php");

	$section_id *= -1;
	
	show_wysiwyg_editor('content'.$section_id,'content'.$section_id, $content, $w, $h);

?>