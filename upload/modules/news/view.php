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

// load module language file
$lang = (dirname(__FILE__)) . '/languages/' . LANGUAGE . '.php';
require_once(!file_exists($lang) ? (dirname(__FILE__)) . '/languages/EN.php' : $lang );

//overwrite php.ini on Apache servers for valid SESSION ID Separator
if(function_exists('ini_set'))
{
	ini_set('arg_separator.output', '&amp;');
}

/**	*******************************
 *	Try to get the template-engine.
 */
global $parser, $loader;
if (!isset($parser))
{
	require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
}

$loader->prependPath( dirname(__FILE__)."/templates/", "news" );

$frontend_template_path = LEPTON_PATH."/templates/".DEFAULT_TEMPLATE."/frontend/news/templates/";
$module_template_path = dirname(__FILE__)."/templates/";

require_once (LEPTON_PATH."/modules/lib_twig/classes/class.twig_utilities.php");
$twig_util = new twig_utilities( $parser, $loader, $module_template_path, $frontend_template_path );
$twig_util->template_namespace = "news";

// End of template-engines settings.

// Check if there is a start point defined
if(isset($_GET['p']) AND is_numeric($_GET['p']) AND $_GET['p'] >= 0)
{
	$position = $_GET['p'];
} else {
	$position = 0;
}

// Get user's username, display name, email, and id - needed for insertion into post info
$users = array();
$all_users = array();
$database->execute_query(
	"SELECT `user_id`,`username`,`display_name`,`email` FROM `".TABLE_PREFIX."users`",
	true,
	$all_users
);

if(count($all_users) > 0) {
	foreach( $all_users as &$user)
	{
		// Insert user info into users array
		$user_id = $user['user_id'];
		$users[$user_id]['username'] = $user['username'];
		$users[$user_id]['display_name'] = $user['display_name'];
		$users[$user_id]['email'] = $user['email'];
	}
}

// Get groups (title, if they are active, and their image [if one has been uploaded])
if (isset($groups))  unset($groups);

$groups[0]['title'] = '';
$groups[0]['active'] = true;
$groups[0]['image'] = '';

$all_groups = array();
$database->execute_query(
	"SELECT `group_id`,`title`,`active` FROM `".TABLE_PREFIX."mod_news_groups` WHERE `section_id` = '".$section_id."' ORDER BY position ASC",
	true,
	$all_groups
);
if( count($all_groups) > 0)
{
	foreach($all_groups as &$group)
    {
		// Insert user info into users array
		$group_id = $group['group_id'];
		$groups[$group_id]['title'] = $group['title'];
		$groups[$group_id]['active'] = $group['active'];
		if(file_exists(LEPTON_PATH.MEDIA_DIRECTORY.'/.news/image'.$group_id.'.jpg'))
        {
			$groups[$group_id]['image'] = LEPTON_URL.MEDIA_DIRECTORY.'/.news/image'.$group_id.'.jpg';
		} else {
			$groups[$group_id]['image'] = '';
		}
	}
}

/**
 *	Timebased activation or deactivation of the news posts.
 *	Keep in mind that the database class will return an object-instance each time a query.
 *
 */
$t = time();
$database->execute_query("UPDATE `".TABLE_PREFIX."mod_news_posts` SET `active`= '0' WHERE (`published_until` > '0') AND (`published_until` <= '".$t."')");
$database->execute_query("UPDATE `".TABLE_PREFIX."mod_news_posts` SET `active`= '1' WHERE (`published_when` > '0') AND (`published_when` <= '".$t."') AND (`published_until` > '0') AND (`published_until` >= '".$t."')");

// Check if we should show the main page or a post itself
if(!defined('POST_ID') OR !is_numeric(POST_ID))
{

	// Check if we should only list posts from a certain group
	if(isset($_GET['g']) AND is_numeric($_GET['g']))
    {
		$query_extra = " AND group_id = '".$_GET['g']."'";
	} else {
		$query_extra = '';
	}

	// Check if we should only list posts from a certain group
	if(isset($_GET['g']) AND is_numeric($_GET['g']))
    {
		$query_extra = " AND group_id = '".$_GET['g']."'";
	} else {
		$query_extra = '';
	}

	// Get settings
	$fetch_settings = array();
	$query_settings = $database->execute_query(
		"SELECT `posts_per_page` FROM ".TABLE_PREFIX."mod_news_settings WHERE section_id = '$section_id'",
		true,
		$fetch_settings,
		false
	);
	if(count($fetch_settings) > 0)
    {
		$setting_posts_per_page = $fetch_settings['posts_per_page'];
	} else {
		$setting_posts_per_page = '';
	}

	// Get total number of posts
	$query_total_num = $database->query("SELECT post_id, section_id FROM ".TABLE_PREFIX."mod_news_posts
		WHERE section_id = '$section_id' AND active = '1' AND title != '' $query_extra
		AND (published_when = '0' OR published_when <= $t) AND (published_until = 0 OR published_until >= $t)");
	$total_num = $query_total_num->numRows();

	// Work-out if we need to add limit code to sql
	if($setting_posts_per_page != 0)
    {
		$limit_sql = " LIMIT ".$position.", ".$setting_posts_per_page;
	} else {
		$limit_sql = "";
	}

	// Query posts (for this page)
	$query_posts = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_news_posts
		WHERE section_id = '$section_id' AND active = '1' AND title != ''$query_extra
		AND (published_when = '0' OR published_when <= $t) AND (published_until = 0 OR published_until >= $t)
		ORDER BY position DESC".$limit_sql);
	$num_posts = $query_posts->numRows();

	// Create previous and next links
	if($setting_posts_per_page != 0)
    {
		if($position > 0)
        {
			if(isset($_GET['g']) AND is_numeric($_GET['g']))
            {
				$pl_prepend = '<a href="?p='.($position-$setting_posts_per_page).'&amp;g='.$_GET['g'].'">&lt;&lt; ';
			} else {
				$pl_prepend = '<a href="?p='.($position-$setting_posts_per_page).'">&lt;&lt; ';
			}
			$pl_append = '</a>';
			$previous_link = $pl_prepend.$TEXT['PREVIOUS'].$pl_append;
			$previous_page_link = $pl_prepend.$TEXT['PREVIOUS_PAGE'].$pl_append;
		} else {
			$previous_link = '';
			$previous_page_link = '';
		}
		if($position + $setting_posts_per_page >= $total_num)
        {
			$next_link = '';
			$next_page_link = '';
		} else {
			if(isset($_GET['g']) AND is_numeric($_GET['g']))
            {
				$nl_prepend = '<a href="?p='.($position+$setting_posts_per_page).'&amp;g='.$_GET['g'].'"> ';
			} else {
				$nl_prepend = '<a href="?p='.($position+$setting_posts_per_page).'"> ';
			}
			$nl_append = ' &gt;&gt;</a>';
			$next_link = $nl_prepend.$TEXT['NEXT'].$nl_append;
			$next_page_link = $nl_prepend.$TEXT['NEXT_PAGE'].$nl_append;
		}
		if($position+$setting_posts_per_page > $total_num)
        {
			$num_of = $position+$num_posts;
		} else {
			$num_of = $position+$setting_posts_per_page;
		}

		$out_of = ($position+1).'-'.$num_of.' '.strtolower($TEXT['OUT_OF']).' '.$total_num;
		$of = ($position+1).'-'.$num_of.' '.strtolower($TEXT['OF']).' '.$total_num;
		$display_previous_next_links = '';
	} else {
		$display_previous_next_links = 'none';
	}

	if ($num_posts === 0)
    {
		$setting_header = '';
		$setting_post_loop = '';
		$setting_footer = '';
		$setting_posts_per_page = '';
	}

	// Print header	
	$header_vars = array(
		'NEXT_PAGE_LINK'	=> (($display_previous_next_links == 'none') ? '' : $next_page_link),
		'NEXT_LINK'			=> (($display_previous_next_links == 'none') ? '' : $next_link),
		'PREVIOUS_PAGE_LINK' => (($display_previous_next_links == 'none') ? '' : $previous_page_link),
		'PREVIOUS_LINK'		=> (($display_previous_next_links == 'none') ? '' : $previous_link),
		'OUT_OF'			=> (($display_previous_next_links == 'none') ? '' : $out_of),
		'OF'				=> (($display_previous_next_links == 'none') ? '' : $of),
		'DISPLAY_PREVIOUS_NEXT_LINKS' => $display_previous_next_links
	);
	
	if (true === $twig_util->resolve_path("header.lte") ) {

		echo $parser->render(
			"@news/header.lte",
			$header_vars
		);
	
	}
	
	if($num_posts > 0)
    {
		if($query_extra != '')
        {
			?>
			<div class="selected-group-title">
				<?php print '<a href="'.htmlspecialchars(strip_tags($_SERVER['SCRIPT_NAME'])).'">'.PAGE_TITLE.'</a> &gt;&gt; '.$groups[$_GET['g']]['title']; ?>
			</div>
			<?php
		}
		/**
		 *	Aldus!
		 */
		$use_parser = $twig_util->resolve_path("post_loop.lte");
		
		$vars = array('PICTURE', 'PIC_URL', 'PAGE_TITLE', 'GROUP_ID', 'GROUP_TITLE', 'GROUP_IMAGE', 'DISPLAY_GROUP', 'DISPLAY_IMAGE', 'TITLE',
					  'SHORT', 'LINK', 'MODI_DATE', 'MODI_TIME', 'CREATED_DATE', 'CREATED_TIME', 'PUBLISHED_DATE', 'PUBLISHED_TIME', 'USER_ID',
					  'USERNAME', 'DISPLAY_NAME', 'EMAIL', 'TEXT_READ_MORE','SHOW_READ_MORE', 'COM_COUNT');

		while( false != ($post = $query_posts->fetchRow()) )
        {
			if(isset($groups[$post['group_id']]['active']) AND $groups[$post['group_id']]['active'] != false)
            { // Make sure parent group is active
				$uid = $post['posted_by']; // User who last modified the post
				// Workout date and time of last modified post
				if ($post['published_when'] === '0') $post['published_when'] = time();
				if ($post['published_when'] > $post['posted_when'])
                {
					$post_date = date(DATE_FORMAT, $post['published_when']);
					$post_time = date(TIME_FORMAT, $post['published_when']);
				} else {
					$post_date = date(DATE_FORMAT, $post['posted_when']);
					$post_time = date(TIME_FORMAT, $post['posted_when']);
				}

				$publ_date = date(DATE_FORMAT,$post['published_when']);
				$publ_time = date(TIME_FORMAT,$post['published_when']);

				// Work-out the post link
				$post_link = page_link($post['link']);

                $post_link_path = str_replace(LEPTON_URL, LEPTON_PATH,$post_link);
                if(file_exists($post_link_path))
                {
    				$create_date = date(DATE_FORMAT, filemtime ( $post_link_path ));
    				$create_time = date(TIME_FORMAT, filemtime ( $post_link_path ));
                } else {
                    $create_date = $publ_date;
                    $create_time = $publ_time;
                }

				if(isset($_GET['p']) AND $position > 0)
                {
					$post_link .= '?p='.$position;
				}
				if(isset($_GET['g']) AND is_numeric($_GET['g']))
                {
					if(isset($_GET['p']) AND $position > 0) { $post_link .= '&amp;'; } else { $post_link .= '?'; }
                    {
					$post_link .= 'g='.$_GET['g'];
                    }
				}

				// Get group id, title, and image
				$group_id = $post['group_id'];
				$group_title = $groups[$group_id]['title'];
				$group_image = $groups[$group_id]['image'];
				$display_image = ($group_image == '') ? "none" : "inherit";
				$display_group = ($group_id == 0) ? 'none' : 'inherit';

				if ($group_image != "") $group_image= "<img src='".$group_image."' alt='".$group_title."' />";

				// Replace [wblink--PAGE_ID--] with real link
				$short = ($post['content_short']);
				$wb->preprocess($short);

				// Loop Post Image
				$post_pic_url = '';
				$post_picture = '';
				if(file_exists(LEPTON_PATH.MEDIA_DIRECTORY.'/newspics/image'.$post['post_id'].'.jpg')){
					$post_pic_url = LEPTON_URL.MEDIA_DIRECTORY.'/newspics/image'.$post['post_id'].'.jpg';
					$post_picture = '<img src="'.$post_pic_url.'" alt="'.$post['title'].'" class="news_loop_image" />';
				}
				
			   // number of comments:
			   $com_count = '';
			   $pid = $post['post_id'];
			   $qc = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_news_comments WHERE section_id = '$section_id' AND post_id = '$pid'");
			   if ($qc->numRows() == 1) {
				  $com_count = "1 Kommentar";
			   } 
			   if ($qc->numRows() > 1) {
				  $com_count = $qc->numRows() . " Kommentare";
			   } 

				// Replace vars with values
				$post_long_len = strlen($post['content_long']);

				if(isset($users[$uid]['username']) AND $users[$uid]['username'] != '')
                {
					if($post_long_len < 9)
                    {
						$values = array($post_picture, $post_pic_url, PAGE_TITLE, $group_id, $group_title, $group_image, $display_group, $display_image, $post['title'],
										$short, '#" onclick="javascript:void(0);return false;" style="cursor:no-drop;', $post_date, $post_time, $create_date, $create_time,
										$publ_date, $publ_time, $uid, $users[$uid]['username'], $users[$uid]['display_name'], $users[$uid]['email'], '', 'hidden', $com_count);
					} else {
					   	$values = array($post_picture, $post_pic_url, PAGE_TITLE, $group_id, $group_title, $group_image, $display_group, $display_image, $post['title'],
										$short, $post_link, $post_date, $post_time, $create_date, $create_time, $publ_date, $publ_time, $uid, $users[$uid]['username'],
										$users[$uid]['display_name'], $users[$uid]['email'], $MOD_NEWS['TEXT_READ_MORE'], 'visible', $com_count);
					}
				} else {
					if($post_long_len < 9)
                    {
						$values = array($post_picture, $post_pic_url, PAGE_TITLE, $group_id, $group_title, $group_image, $display_group, $display_image, $post['title'],
										$short, '#" onclick="javascript:void(0);return false;" style="cursor:no-drop;', $post_date, $post_time, $create_date, $create_time,
										$publ_date, $publ_time, '', '', '', '', '','hidden', $com_count);
					} else {
						$values = array($post_picture, $post_pic_url, PAGE_TITLE, $group_id, $group_title, $group_image, $display_group, $display_image, $post['title'],
										$short, $post_link, $post_date, $post_time, $create_date, $create_time, $publ_date, $publ_time, '', '', '', '',
										$MOD_NEWS['TEXT_READ_MORE'],'visible', $com_count);
					}
				}
				
				if (true === $use_parser) {
					$temp_vars = array_combine ( $vars, $values );
					
					echo $parser->render(
						'@news/post_loop.lte',
						$temp_vars
					);
					
				}
			}
		}
	}
    // Print footer
	$use_parser = $twig_util->resolve_path("footer.lte");
	
	$footer_vars = array(
		'NEXT_PAGE_LINK' => (($display_previous_next_links == 'none') ? '' : $next_page_link),
		'NEXT_LINK'		 => (($display_previous_next_links == 'none') ? '' : $next_link),
		'PREVIOUS_PAGE_LINK'	=> (($display_previous_next_links == 'none') ? '' : $previous_page_link),
		'PREVIOUS_LINK' => (($display_previous_next_links == 'none') ? '' : $previous_link),
		'OUT_OF' => (($display_previous_next_links == 'none') ? '' : $out_of),
		'OF' => (($display_previous_next_links == 'none') ? '' : $of),
		'DISPLAY_PREVIOUS_NEXT_LINKS' => $display_previous_next_links
	); 

	if (true === $use_parser) {
		
		echo $parser->render(
			'@news/footer.lte',
			$footer_vars
		);
		
	}
}
elseif(defined('POST_ID') AND is_numeric(POST_ID))
{

  {	// no idea where this breaked belong to ...

	// Get page info
	$query_page = $database->query("SELECT `link` FROM `".TABLE_PREFIX."pages` WHERE `page_id` = '".PAGE_ID."'");
	if($query_page->numRows() > 0)
    {
		$page = $query_page->fetchRow();
		$page_link = page_link($page['link']);
		if(isset($_GET['p']) AND $position > 0)
        {
			$page_link .= '?p='.$_GET['p'];
		}
		if(isset($_GET['g']) AND is_numeric($_GET['g']))
        {
			if(isset($_GET['p']) AND $position > 0) { $page_link .= '&amp;'; } else { $page_link .= '?'; }
			$page_link .= 'g='.$_GET['g'];
		}
	} else {
		exit($MESSAGE['PAGES']['NOT_FOUND']);
	}

	// Get post info
	$t = time();
	$query_post = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_news_posts
		WHERE post_id = '".POST_ID."' AND active = '1'
		AND (published_when = '0' OR published_when <= $t) AND (published_until = 0 OR published_until >= $t)");

	if($query_post->numRows() > 0)
    {
    
		$post = $query_post->fetchRow();
		if(isset($groups[$post['group_id']]['active']) AND $groups[$post['group_id']]['active'] != false)
        { // Make sure parent group is active
			$uid = $post['posted_by']; // User who last modified the post
			// Workout date and time of last modified post
			if ($post['published_when'] === '0') $post['published_when'] = time();
			if ($post['published_when'] > $post['posted_when'])
            {
				$post_date = date(DATE_FORMAT, $post['published_when']);
				$post_time = date(TIME_FORMAT, $post['published_when']);
			}
            else
            {
				$post_date = date(DATE_FORMAT, $post['posted_when']);
				$post_time = date(TIME_FORMAT, $post['posted_when']);
			}

			$publ_date = date(DATE_FORMAT,$post['published_when']);
			$publ_time = date(TIME_FORMAT,$post['published_when']);

			// Work-out the post link
			$post_link = page_link($post['link']);

			$post_link_path = str_replace(LEPTON_URL, LEPTON_PATH,$post_link);
            if(file_exists($post_link_path))
            {
    			$create_date = date(DATE_FORMAT, filemtime ( $post_link_path ));
				$create_time = date(TIME_FORMAT, filemtime ( $post_link_path ));
			} else {
            	$create_date = $publ_date;
                $create_time = $publ_time;
			}
			
			// Get group id, title, and group image
			$group_id = $post['group_id'];
			$group_title = $groups[$group_id]['title'];
			$group_image = $groups[$group_id]['image'];
			$display_image = ($group_image == '') ? "none" : "inherit";
			$display_group = ($group_id == 0) ? 'none' : 'inherit';

			if ($group_image != "") $group_image= "<img src='".$group_image."' alt='".$group_title."' />";
			
			// Post Image
			$post_pic_url = '';
			$post_picture = '';
			if(file_exists(LEPTON_PATH.MEDIA_DIRECTORY.'/newspics/image'.POST_ID.'.jpg')){
				$post_pic_url = LEPTON_URL.MEDIA_DIRECTORY.'/newspics/image'.POST_ID.'.jpg';
				$post_picture = '<img src="'.$post_pic_url.'" alt="'.$post['title'].'" class="news_post_image" />';
			}
		
			$display_user = (isset($users[$uid]['username']) AND $users[$uid]['username'] != '') ? true : false;

			$post_short = $post['content_short'];
			$wb->preprocess($post_short);

			$post_long = ($post['content_long'] != '') ? $post['content_long'] : $post['content_short'];
			$wb->preprocess($post_long);
						
			$vars = array(
				'PICTURE'		=> $post_picture,
				'PIC_URL'		=> $post_pic_url,
				'PAGE_TITLE'	=> PAGE_TITLE,
				'GROUP_ID'	=> $group_id,
				'GROUP_TITLE'	=> $group_title,
				'GROUP_IMAGE'	=> $group_image,
				'DISPLAY_GROUP'	=> $display_group,
				'DISPLAY_IMAGE'	=> $display_image,
				'TITLE'		=> $post['title'],
				'SHORT'		=> $post_short, // *
				'BACK'		=> $page_link,
				'TEXT_BACK'	=> $MOD_NEWS['TEXT_BACK'],
				'TEXT_LAST_CHANGED'	=> $MOD_NEWS['TEXT_LAST_CHANGED'],
				'MODI_DATE'	=> $post_date,
				'TEXT_AT'		=> $MOD_NEWS['TEXT_AT'],
				'MODI_TIME'	=> $post_time,
				'CREATED_DATE'	=> $create_date,
				'CREATED_TIME'	=> $create_time,
				'PUBLISHED_DATE'	=> $publ_date,
				'PUBLISHED_TIME'	=> $publ_time,
				'TEXT_POSTED_BY'	=> $MOD_NEWS['TEXT_POSTED_BY'],
				'TEXT_ON'			=> $MOD_NEWS['TEXT_ON'],
				'USER_ID'			=> ( (true === $display_user) ? $uid : ""),
				'USERNAME'		=> ( (true === $display_user) ? $users[$uid]['username'] : "" ),
				'DISPLAY_NAME'	=> ( (true === $display_user) ? $users[$uid]['display_name'] : "" ),
				'EMAIL'			=> ( (true === $display_user) ? $users[$uid]['email'] : "" )
			);
		}
	} else {
	    $wb->print_error($MESSAGE['FRONTEND']['SORRY_NO_ACTIVE_SECTIONS'], 'view.php', false);
	}

	// Print post header
	if (true === $twig_util->resolve_path("post_header.lte") ) {
	
		echo $parser->render(
			"@news/post_header.lte",
			$vars
		);
	
	}
	}	// Aldus: no idea where this belongs to ...
	
	print $post_long;

	// Print post footer
	if (true === $twig_util->resolve_path("post_footer.lte") ) {
		
		echo $parser->render(
			"@news/post_footer.lte",
			$vars
		);
	
	}
	
	// Show comments section if we have to
	if(($post['commenting'] == 'private' AND isset($wb) AND $wb->is_authenticated() == true) OR $post['commenting'] == 'public')
    {
		/**
		 *	Comments header
		 *
		 */
		$vars = array(
			'ADD_COMMENT_URL' => LEPTON_URL.'/modules/news/comment.php?post_id='.POST_ID.'&amp;section_id='.$section_id,
			'TEXT_COMMENTS'	=> $MOD_NEWS['TEXT_COMMENTS']
		);
		
		if (true === $twig_util->resolve_path("comments_header.lte") ) {
		
			echo $parser->render(
				"@news/comments_header.lte",
				$vars
			);
	
		}
	
		// Query for comments
		$query_comments = $database->query("SELECT title,comment,commented_when,commented_by FROM ".TABLE_PREFIX."mod_news_comments WHERE post_id = '".POST_ID."' ORDER BY commented_when ASC");
		if($query_comments->numRows() > 0)
        {
        	$use_parser = $twig_util->resolve_path("comments_loop.lte");
        	
        	while( false != ($comment = $query_comments->fetchRow() ) )
            {
				// Display Comments without slashes, but with new-line characters
				$comment['comment'] = nl2br(stripslashes($comment['comment']));
				$comment['title'] = stripslashes($comment['title']);
				// Print comments loop
				$commented_date = date(DATE_FORMAT, $comment['commented_when']);
				$commented_time = date(TIME_FORMAT, $comment['commented_when']);
				$uid = $comment['commented_by'];
				
				$display_user = (isset($users[$uid]['username']) AND $users[$uid]['username'] != '') ? true : false;
				
				$vars = array(
					'TITLE'	=> $comment['title'],
					'COMMENT'	=> $comment['comment'],
					'TEXT_ON'	=> $MOD_NEWS['TEXT_ON'],
					'DATE'	=> $commented_date,
					'TEXT_AT'	=> $MOD_NEWS['TEXT_AT'],
					'TIME'	=> $commented_time,
					'TEXT_BY'	=> $MOD_NEWS['TEXT_BY'],
					'USER_ID'	=> ( true === $display_user ) ? $uid : '0',
					'USERNAME'=> ( true === $display_user ) ? $users[$uid]['username'] : $MOD_NEWS['TEXT_UNKNOWN'],
					'DISPLAY_NAME' => ( true === $display_user ) ? $users[$uid]['display_name'] : $MOD_NEWS['TEXT_UNKNOWN'],
					'EMAIL'	=> ( true === $display_user ) ? $users[$uid]['email'] : ""
				);
				
				if (true === $use_parser) {
					
					echo $parser->render(
						'@news/comments_loop.lte',
						$vars
					);
					
				}
			}
		} else {
			/**
			 *	No comments found
			 *
			 */
			echo (isset($MOD_NEWS['TEXT_NO_COMMENT'])) ? $MOD_NEWS['TEXT_NO_COMMENT'].'<br />' : 'None found<br />';
		}

		/**
		 *	Print comments footer
		 *
		 */
		$vars = array(
			'ADD_COMMENT_URL'	=> LEPTON_URL.'/modules/news/comment.php?post_id='.POST_ID.'&amp;section_id='.$section_id,
			'TEXT_ADD_COMMENT' => $MOD_NEWS['TEXT_ADD_COMMENT']
		);
		
		if (true === $twig_util->resolve_path("comments_footer.lte") ) {
		
			echo $parser->render(
				"@news/comments_footer.lte",
				$vars
			);
	
		}	

    }

	if(ENABLED_ASP)
    {
		$_SESSION['comes_from_view'] = POST_ID;
		$_SESSION['comes_from_view_time'] = time();
	}

}
?>