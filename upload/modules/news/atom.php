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

// Check that GET values have been supplied
if(isset($_GET['page_id']) AND is_numeric($_GET['page_id'])) {
	$page_id = $_GET['page_id'];
} else {
	header('Location: /');
	exit(0);
}

if (isset($_GET['group_id']) AND is_numeric($_GET['group_id'])) {
	$group_id = (int)$_GET['group_id'];
} else {
	$group_id = -1;	// Keep in mind, that $group_id could be 0 (no group)
}
define('GROUP_ID', $group_id);

// Include files
require_once(LEPTON_PATH.'/framework/class.frontend.php');

$wb = new frontend();
$wb->page_id = $page_id;
$wb->get_page_details();
$wb->get_website_settings();

//checkout if a charset is defined otherwise use UTF-8
if(defined('DEFAULT_CHARSET')) {
	$charset=DEFAULT_CHARSET;
} else {
	$charset='utf-8';
}

// get last published_when from the posts table
$last_item_date = $database->get_one("select published_when FROM ".TABLE_PREFIX."mod_news_posts ORDER BY published_when DESC ");

ob_start();

// Header info, sending XML header
header("Content-type:text/xml; charset=$charset" );
echo('<?xml version="1.0" encoding="utf-8"?>');
/* See for details
1. https://tools.ietf.org/html/rfc4287 (official) 
2. https://validator.w3.org/feed/docs/atom.html
3. https://www.data2type.de/xml-xslt-xslfo/newsfeeds-rss-atom/   (de)
*/
?>
<feed xmlns="http://www.w3.org/2005/Atom">
	<title><?php echo PAGE_TITLE; ?></title>
	<author>
      <name><?php echo WEBSITE_HEADER; ?></name>
	</author>
	<updated><?php echo date(DATE_RFC3339,$last_item_date); ?></updated>
	<id><?php echo  LEPTON_URL; ?>/</id>
	<subtitle><?php echo PAGE_DESCRIPTION; ?></subtitle>
	<link rel="self" href="<?php echo LEPTON_URL; ?>/modules/news/atom.php?page_id=<?php echo $page_id; ?>" type="application/atom+xml" />		
	<generator uri="https://lepton-cms.org/" version="<?php echo VERSION; ?>">LEPTON CMS</generator>
	<rights>Copyright (c) [[year]], <?php echo WEBSITE_HEADER; ?></rights>	
<?php
// Get news items from database
$t = TIME();
$time_check_str= "(published_when = '0' OR published_when <= ".$t.") AND (published_until = 0 OR published_until >= ".$t.")";

//	Query
if ( $group_id > -1 ) {
	$query = "SELECT * FROM ".TABLE_PREFIX."mod_news_posts WHERE group_id=".$group_id." AND page_id = ".$page_id." AND active=1 AND ".$time_check_str." ORDER BY posted_when DESC";
} else {
	$query = "SELECT * FROM ".TABLE_PREFIX."mod_news_posts WHERE page_id=".$page_id." AND active=1 AND ".$time_check_str." ORDER BY posted_when DESC";	
}
$result = $database->query($query);

 // Get group titles
$group_titles = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."mod_news_groups` where page_id='".$page_id ."' ",
	true,
	$group_titles,
	true
);

$group_title= array();
foreach ($group_titles as $ref) {
	$group_title[ $ref['group_id']] = $ref['title'];
}


// Generating the news items
while($item = $result->fetchRow()){ ?>
		<entry>
			<title><?php echo stripslashes($item["title"]); ?></title>
			<category term="<?php echo ($item['group_id']);?>" label="<?php echo ($group_title[ $item['group_id'] ]);?>" />		
			<summary type="xhtml">
				<div xmlns="http://www.w3.org/1999/xhtml"><?php echo stripslashes($item["content_short"]); ?></div>
			</summary>
			<id><?php echo LEPTON_URL.PAGES_DIRECTORY.$item["link"].PAGE_EXTENSION; ?></id>
			<link rel="alternate" href="<?php echo LEPTON_URL.PAGES_DIRECTORY.$item["link"].PAGE_EXTENSION; ?>" />
			<published><?php echo date(DATE_RFC3339,$item["published_when"]); ?></published>
			<updated><?php echo date(DATE_RFC3339,$item["posted_when"]); ?></updated>
		</entry>
<?php } ?> 
</feed>

<?php  
$output = ob_get_contents();
if(ob_get_length() > 0) { ob_end_clean(); }

// wb->preprocess() -- replace all [wblink123] with real, internal links
$wb->preprocess($output);
// Load Droplet engine and process
if(file_exists(LEPTON_PATH .'/modules/droplets/droplets.php'))
{
    include_once(LEPTON_PATH .'/modules/droplets/droplets.php');
    if(function_exists('evalDroplets'))
    {
    evalDroplets($output); 
    }
}

echo $output;
?>
