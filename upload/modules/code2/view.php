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
 
/**
 *	Get content
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
$fetch_content = $oStatement->fetch();

$whatis = (int)($fetch_content['whatis'] % 10);

$content = $fetch_content['content'];

switch ($whatis) {

	case 0:	// PHP 
		eval($content);
		break;

	case 1:	// HTML
		$wb->preprocess($content);
		echo $content;
		break;

	case 2:	// JS
		echo "\n<script type=\"text/javascript\">\n<!--\n".$content."\n// -->\n</script>\n";
		break;

	case 3:
	case 4:
		echo "";	// Keine Ausgabe: Kommentar
		break;
}

?>