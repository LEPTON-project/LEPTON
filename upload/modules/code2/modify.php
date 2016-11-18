<?php

/**
 *  @module         code2
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @copyright      2004-2017 Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
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

/**
 *	Load Language file - new LEPTON-CMS 2 way
 */
require_once(LEPTON_PATH."/framework/functions/function.load_module_language.php");
load_module_language("code2");

/**	*******************************
 *	Try to get the template-engine.
 */
global $parser, $loader;
require( dirname(__FILE__)."/register_parser.php" );

/**
 *	Get page content
 */
$fields = array(
	'content',
	'whatis'
);

$query = $database->build_mysql_query(
	"select",
	TABLE_PREFIX."mod_code2",
	$fields,
	"`section_id`= '".$section_id."'"
);

$oStatement = $database->db_handle->prepare( $query );
$oStatement->execute();
$content = $oStatement->fetch();

$whatis = (int)$content['whatis'];

$mode = ($whatis >= 10) ? (int)($whatis / 10) : 0;
$whatis = $whatis % 10;

$groups = $admin->get_groups_id();

if ( ( $whatis == 4) AND (!in_array(1, $groups)) ) {
	$content = $content['content'];
	echo '<div class="code2_admin">'.$content.'</div>';
} else {	
	$content = htmlspecialchars($content['content']);
	$whatis_types = array('PHP', 'HTML', 'Javascript', 'Internal');
	if (in_array(1, $groups)) $whatis_types[]="Admin";
	$whatisarray = array();
	foreach($whatis_types as $item) $whatisarray[] = $MOD_CODE2[strtoupper($item)];
	
	$whatisselect = '';
	for($i=0; $i < count($whatisarray); $i++) {
   		$select = ($whatis == $i) ? " selected='selected'" : "";
   		$whatisselect .= '<option value="'.$i.'"'.$select.'>'.$whatisarray[$i].'</option>'."\n";
  	}
    
    $modes_names = array('smart', 'full');
    $modes = array();
    foreach($modes_names as $item) $modes[] = $MOD_CODE2[strtoupper($item)];
    $mode_options = "";
    $counter = 0;
    foreach($modes as $item) {
    	$mode_options .= "<option value='".$counter."' ".(($counter==$mode)?" selected='selected'":"").">".$item."</option>";
		$counter++;
	}
	
	// Insert vars
	$data = array(
		'PAGE_ID' => $page_id,
		'SECTION_ID' => $section_id,
		'LEPTON_URL' => LEPTON_URL,
		'CONTENT' => $content,
		'WHATIS' => $whatis,
		'WHATISSELECT' => $whatisselect,
		'TEXT_SAVE' => $TEXT['SAVE'],
		'TEXT_CANCEL' => $TEXT['CANCEL'],
		'TEXT'	=> $TEXT,
		'MODE'	=> $mode_options,
		'MODE_' => $mode,
		'LANGUAGE' => LANGUAGE,
		'MODES'	=> $MOD_CODE2['MODE'],
		'THEME_URL' => THEME_URL
	);

	$twig_util->resolve_path("modify.lte");
	
	echo $parser->render( 
		$twig_modul_namespace.'modify.lte',// template-filename
		$data	//	template-data
	);
}
?>