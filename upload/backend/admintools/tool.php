<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 *
 * @author		  LEPTON Project
 * @copyright	   2010-2017 LEPTON Project
 * @link			http://www.LEPTON-cms.org
 * @license		 http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
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

require_once(LEPTON_PATH.'/framework/class.admin.php');
require_once(LEPTON_PATH.'/framework/summary.functions.php');

$admin = new admin('admintools', 'admintools');

if(!isset($_GET['tool'])) {
	header("Location: index.php");
	exit(0);
}

// Check if tool is installed
$tool = array();
$values = array(
	'type' => 'module',
	'function' => 'tool',
	'directory' => addslashes($_GET['tool'])
);
$result = $database->prepare_and_execute(
	"SELECT `name`,`directory` FROM `".TABLE_PREFIX."addons` WHERE type = :type AND function = :function AND directory = :directory",
	$values,
	true,
	$tool,
	false
);

if( count($tool) == 0) {
	header("Location: index.php");
	exit(0);
}

?>
<h4>
	<a href="<?php echo ADMIN_URL; ?>/admintools/index.php"><?php echo $HEADING['ADMINISTRATION_TOOLS']; ?></a>
	&raquo;
	<?php echo $tool['name']; ?>
</h4>
<?php

if(file_exists(LEPTON_PATH.'/modules/'.$tool['directory'].'/tool.php'))
{
	require(LEPTON_PATH.'/modules/'.$tool['directory'].'/tool.php');
	$admin->print_footer();
} else {
	$admin->print_error($MESSAGE['GENERIC_ERROR_OPENING_FILE'] );
}


?>