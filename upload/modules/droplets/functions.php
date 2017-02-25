<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          Droplets
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH'))
{
    include(LEPTON_PATH . '/framework/class.secure.php');
}
else
{
    $oneback = "../";
    $root    = $oneback;
    $level   = 1;
    while (($level < 10) && (!file_exists($root . '/framework/class.secure.php')))
    {
        $root .= $oneback;
        $level += 1;
    }
    if (file_exists($root . '/framework/class.secure.php'))
    {
        include($root . '/framework/class.secure.php');
    }
    else
    {
        trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
    }
}
// end include class.secure.php

require_once(LEPTON_PATH . '/modules/edit_area/register.php');

/**
 * this function may be called by modules to handle a droplet upload
 **/
function droplets_upload( $input ) {

    global $database, $MOD_DROPLET;
    
    // Set temp vars
    $temp_dir   = LEPTON_PATH.'/temp/';
    $temp_file  = $temp_dir . $_FILES[$input]['name'];
    $temp_unzip = LEPTON_PATH.'/temp/unzip/';
    $errors     = array();

    // Try to upload the file to the temp dir
    if( ! move_uploaded_file( $_FILES[$input]['tmp_name'], $temp_file ) )
    {
   	    return array( 'error', $MOD_DROPLET['Upload failed'] );
    }

    $result = droplet_install( $temp_file, $temp_unzip );

    // Delete the temp zip file
    if( file_exists( $temp_file) )
    {
        unlink( $temp_file );
    }
    rm_full_dir($temp_unzip);

    // show errors
    if ( isset( $result['errors'] ) && is_array( $result['errors'] ) && count( $result['errors'] ) > 0 ) {
        return array( 'error', $result['errors'], NULL );
    }
    
    // return success
    return array( 'success', $result['count'] );
    
}   // end function droplets_upload()


/**
 * this function may be called by modules to install a droplet 
 **/
function droplet_install( $temp_file, $temp_unzip ) {

    global $admin, $database;

	$errors  = array();
    $count   = 0;
	
	$zip = new ZipArchive;
	if ( true === $zip->open( $temp_file) ) {
    	$zip->extractTo( $temp_unzip );
    	$zip->close();
    }
    
    // now, open all *.php files and search for the header;
    // an exported droplet starts with "//:"
    if ( $dh = opendir($temp_unzip) ) {
        while ( false !== ( $file = readdir($dh) ) )
        {
            if ( $file != "." && $file != ".." )
            {
                if ( preg_match( '/^(.*)\.php$/i', $file, $name_match ) ) {
                    // Name of the Droplet = Filename
                    $name  = $name_match[1];
                    // Slurp file contents
                    $lines = file( $temp_unzip.'/'.$file );
                    // First line: Description
                    if ( preg_match( '#^//\:(.*)$#', $lines[0], $match ) ) {
                        $description = $match[1];
                    }
                    // Second line: Usage instructions
                    if ( preg_match( '#^//\:(.*)$#', $lines[1], $match ) ) {
                        $usage       = addslashes( $match[1] );
                    }
                    // Remaining: Droplet code
                    $code = implode( '', array_slice( $lines, 2 ) );
                    // replace 'evil' chars in code
                    $tags = array('<?php', '?>' , '<?');
                    $code = addslashes(str_replace($tags, '', $code));
                    // Already in the DB?
                    $stmt  = 'INSERT';
                    $id    = NULL;
                    $found = $database->get_one("SELECT * FROM ".TABLE_PREFIX."mod_droplets WHERE name='$name'");
                    if ( $found && $found > 0 ) {
                        $stmt = 'REPLACE';
                        $id   = $found;
                    }
                    // execute
                    $result = $database->simple_query("$stmt INTO ".TABLE_PREFIX."mod_droplets VALUES(" . ( $id!==NULL ? "'".$id."'" : 'NULL' ). ",'$name','$code','$description','".time()."','".(isset( $_SESSION[ 'USER_ID' ] ) ? $_SESSION[ 'USER_ID' ] : '1')."',1,0,0,0,'$usage')");
                    if( ! $database->is_error() ) {
                        $count++;
                        $imports[$name] = 1;
                    }
                    else {
                        $errors[$name] = $database->get_error();
                    }
                    // try to remove the temp file
                    unlink( $temp_unzip.'/'.$file);
                }
            }
        }
        closedir($dh);
    }
    
    return array( 'count' => $count, 'errors' => $errors, 'imported'=> $imports );
    
}   // end function droplet_install()

/**
 * get a list of all droplets and show them
 **/
function list_droplets( $info = NULL )
{
    global $admin, $parser, $database, $settings, $MOD_DROPLET;

    // check for global read perms
    $groups = $admin->get_groups_id();

	$backups = 1;
    $rows = array();

    $fields = 't1.id, name, code, description, active, comments, view_perm, edit_perm';
    $query  = $database->query( "SELECT $fields FROM " . TABLE_PREFIX . "mod_droplets AS t1 LEFT OUTER JOIN " . TABLE_PREFIX . "mod_droplets_permissions AS t2 ON t1.id=t2.id ORDER BY name ASC" );

    if ( $query->numRows() )
    {
        while ( $droplet = $query->fetchRow() )
        {
            // the current user needs global edit permissions, or specific edit permissions to see this droplet
            if ( !is_allowed( 'modify_droplets', $groups ) )
            {
                // get edit groups for this droplet
                if ( $droplet[ 'edit_perm' ] )
                {
                    if ( $admin->get_user_id() != 1 && !is_in_array( $droplet[ 'edit_perm' ], $groups ) )
                    {
                        continue;
                    }
                    else
                    {
                        $droplet[ 'user_can_modify_this' ] = true;
                    }
                }
            }
            $comments = str_replace( array(
                "\r\n",
                "\n",
                "\r"
            ), '<br />', $droplet[ 'comments' ] );
            if ( !strpos( $comments, "[[" ) ) //
            {
                $comments = '<span class="usage">' . $MOD_DROPLET[ 'Use' ] . ": [[" . $droplet[ 'name' ] . "]]</span><br />" . $comments;
            }
            $comments = str_replace( array(
                "[[",
                "]]"
            ), array(
                '<b>[[',
                ']]</b>'
            ), $comments );
            $droplet[ 'valid_code' ] = check_syntax( $droplet[ 'code' ] );
            $droplet[ 'comments' ] = $comments;
            // droplet included in search?
	        $droplet['is_in_search'] = true;

			$droplet['delete_message'] = sprintf( $MOD_DROPLET['Are you sure'], $droplet[ 'name' ] );

            array_push( $rows, $droplet );
        }
    }

    echo $parser->render( 
    	'@droplets/modify.lte', 
    	array(
			'rows'       => $rows,
			'num_rows'	=> count($rows),
			'info'       => $info,
			'backups'    => ( ( count( $backups ) && is_allowed( 'Manage_backups', $groups ) ) ? 1 : NULL ),
			'can_export' => ( is_allowed( 'Export_droplets', $groups ) ? 1 : NULL ),
			'can_import' => ( is_allowed( 'Import_droplets', $groups ) ? 1 : NULL ),
			'can_delete' => ( is_allowed( 'Delete_droplets', $groups ) ? 1 : NULL ),
			'can_modify' => ( is_allowed( 'Modify_droplets', $groups ) ? 1 : NULL ),
			'can_perms'  => ( is_allowed( 'Manage_perms', $groups ) ? 1 : NULL ),
			'can_add'    => ( is_allowed( 'Add_droplets', $groups ) ? 1 : NULL )
    ) );

} // end function list_droplets()

/**
 *
 **/
function manage_backups()
{
    global $admin, $parser, $database, $settings, $MOD_DROPLET;

    $groups = $admin->get_groups_id();
    if ( !is_allowed( 'Manage_backups', $groups ) )
    {
        $admin->print_error( $MOD_DROPLET[ "You don't have the permission to do this" ] );
    }

    $rows = array();
    $info = NULL;

    // recover
    if ( isset( $_REQUEST[ 'recover' ] ) && file_exists( dirname( __FILE__ ) . '/export/' . $_REQUEST[ 'recover' ] ) )
    {
        $temp_unzip = LEPTON_PATH . '/temp/unzip/';
        $result     = droplet_install( dirname( __FILE__ ) . '/export/' . $_REQUEST[ 'recover' ], $temp_unzip );
        $info       = str_replace("{{count}}", $result[ 'count' ], $MOD_DROPLET[ 'Successfully imported Droplet(s)'] );
    }

    // delete single backup
    if ( isset( $_REQUEST[ 'delbackup' ] ) && file_exists( dirname( __FILE__ ) . '/export/' . $_REQUEST[ 'delbackup' ] ) )
    {
        unlink( dirname( __FILE__ ) . '/export/' . $_REQUEST[ 'delbackup' ] );
		$info = str_replace("{{file}}", $_REQUEST[ 'delbackup' ], $MOD_DROPLET[ 'Backup file deleted: {{file}}']);
    }

    // delete a list of backups
    // get all marked droplets
    $marked = isset( $_POST[ 'markeddroplet' ] ) ? $_POST[ 'markeddroplet' ] : array();

    if ( count( $marked ) )
    {
        $deleted = array();
        foreach ( $marked as $file )
        {
            $file = dirname( __FILE__ ) . '/export/' . $file ;
            if ( file_exists( $file ) )
            {
                unlink( $file );
				$deleted[] = str_replace("{{file}}", basename( $file ) , $MOD_DROPLET[ 'Backup file deleted: {{file}}'] );
            }
        }
        if ( count( $deleted ) )
        {
            $info = implode( '<br />', $deleted );
        }
    }

	LEPTON_tools::register( "file_list" );
    $backups = file_list( dirname( __FILE__ ) . '/export' , array('index.php') );

    if ( count( $backups ) > 0 )
    {
        // sort by name
        // sort( $backups );
        foreach ( $backups as $file )
        {
            // stat
            $stat   = stat( $file );
            
            // get zip contents
			require_once(LEPTON_PATH.'/modules/lib_lepton/pclzip/pclzip.lib.php');
            $oZip = new PclZip( $file );            
            $count  = $oZip->listContent();
            $rows[] = array(
                'name' => basename( $file ),
                'size' => $stat[ 'size' ],
                'date' => date( DATE_FORMAT." - ".TIME_FORMAT , $stat[ 'ctime' ] ),
                'files' => count( $count ),
                'listfiles' => "- ".implode( ",<br />- ", array_map( create_function( '$cnt', 'return $cnt["filename"];' ), $count ) ),
                'download' =>  LEPTON_URL . '/modules/droplets/export/' . basename( $file )
            );
        }
    }

    echo $parser->render(
    	'@droplets/backups.lte',
    	array(
        	'rows' => $rows,
        	'info' => $info,
        	'backups' => ( count( $backups ) ? 1 : NULL ),
        	'num_rows' => count( $rows )
    	)
    );

} // end function manage_backups()

/**
 *
 **/
function manage_perms()
{
    global $admin, $parser, $database, $settings, $MOD_DROPLET;
    $info   = NULL;
    $groups = array();
    $rows   = array();

    $this_user_groups = $admin->get_groups_id();
    if ( !is_allowed( 'Manage_perms', $this_user_groups ) )
    {
        $admin->print_error( $MOD_DROPLET[ "You don't have the permission to do this" ] );
    }

    // get available groups
    $query = $database->query( 'SELECT group_id, name FROM ' . TABLE_PREFIX . 'groups ORDER BY name' );
    if ( $query->numRows() )
    {
        while ( $row = $query->fetchRow() )
        {
            $groups[ $row[ 'group_id' ] ] = $row[ 'name' ];
        }
    }

    if ( isset( $_REQUEST[ 'save' ] ) || isset( $_REQUEST[ 'save_and_back' ] ) )
    {
        foreach ( $settings as $key => $value )
        {
            if ( isset( $_REQUEST[ $key ] ) )
            {
                $database->query( 'UPDATE ' . TABLE_PREFIX . "mod_droplets_settings SET value='" . implode( '|', $_REQUEST[ $key ] ) . "' WHERE attribute='" . $key . "';" );
            }
        }
        // reload settings
        $settings = get_settings();
        $info     = $MOD_DROPLET[ 'Permissions saved' ];
        if ( isset( $_REQUEST[ 'save_and_back' ] ) )
        {
            return list_droplets( $info );
        }
    }

    foreach ( $settings as $key => $value )
    {
        $line = array();
        foreach ( $groups as $id => $name )
        {
            $line[] = '<input type="checkbox" name="'.$key.'[]" id="'.$key.'_'.$id.'" value="'.$id .'"'. ( is_in_array( $value, $id ) ? ' checked="checked"' : NULL ) . ' /><label for="'.$key.'_'.$id.'">'. $name.'</label>' . "\n";
        }
        $rows[] = array(
            'groups' => implode( '', $line ),
            'name' => $MOD_DROPLET[ $key ]
        );
    }

    // sort rows by permission name (=text)
	sort($rows);
	
    echo $parser->render(
    	'@droplets/permissions.lte',
    	array(
        'rows' => $rows,
        'info' => $info,
        'num_rows' => count($rows)
    ) );

} // end function manage_perms()

/**
 *	Exporting marked droplets int a zip archive
 *	
 **/
function export_droplets()
{
    global $admin, $parser, $database, $MOD_DROPLET;

    $groups = $admin->get_groups_id();
    if ( !is_allowed( 'Export_droplets', $groups ) )
    {
        $admin->print_error( $MOD_DROPLET[ "You don't have the permission to do this" ] );
    }

    $info = array();

    // get all marked droplets
    $marked = isset( $_POST[ 'markeddroplet' ] ) ? $_POST[ 'markeddroplet' ] : array();

    if ( isset( $marked ) && !is_array( $marked ) )
    {
        $marked = array(
             $marked
        );
    }

    if ( !count( $marked ) )
    {
        return $MOD_DROPLET[ 'Please mark some droplets to export' ];
    }

    $temp_dir = LEPTON_PATH . '/temp/droplets/';

    // make the temporary working directory
    if(!file_exists($temp_dir))
    {
    	mkdir( $temp_dir );
	}
	
	$all_marked_droplets = array();
	$database->execute_query(
		"SELECT * FROM `".TABLE_PREFIX."mod_droplets` WHERE id IN (".implode(",", $marked).")",
		true,
		$all_marked_droplets,
		true
	);
	
	foreach ( $all_marked_droplets as $droplet )
	{
		$name    = $droplet[ "name" ];
		$info[]  = 'Droplet: ' . $name . '.php<br />';
		$sFile   = $temp_dir . $name . '.php';
		$fh      = fopen( $sFile, 'w' );
		fwrite( $fh, '//:' . $droplet[ 'description' ] . "\n" );
		fwrite( $fh, '//:' . str_replace( "\n", " ", $droplet[ 'comments' ] ) . "\n" );
		fwrite( $fh, $droplet[ 'code' ] );
		fclose( $fh );
	}

    $filename = 'droplets';

    // if there's only a single droplet to export, name the zip-file after this droplet
    if ( count( $marked ) === 1 )
    {
        $filename = 'droplet_' . $name;
    }

    // add current date to filename
    $filename .= '_' . date( 'Y-m-d' );

    // while there's an existing file, add a number to the filename
    if ( file_exists( LEPTON_PATH . '/modules/droplets/export/' . $filename . '.zip' ) )
    {
        $n = 1;
        while ( file_exists( LEPTON_PATH . '/modules/droplets/export/' . $filename . '_' . $n . '.zip' ) )
        {
            $n++;
        }
        $filename .= '_' . $n;
    }

    $temp_file = LEPTON_PATH . '/temp/' . $filename . '.zip';

    // create zip
    $zip = new ZipArchive();
    if ($zip->open($temp_file, ZipArchive::CREATE) !== true)
    {
    	die("Error: cannot open <$filename>\n".$zip->getStatusString() );
	}
	LEPTON_tools::register( "file_list" );
	$all_files_for_archive = file_list( substr($temp_dir, 0, -1), array("index.php"), false, "php");

    if (count($all_files_for_archive) == 0)
    {
    	  echo "Packaging error: ", $zip->getStatusString(), "<br />";
    	  die("Error : ".$zip->getStatusString());
    }
    else {
    
    	foreach( $all_files_for_archive as $temp_filename)
    	{
    		$zip->addFile( $temp_filename, str_replace( $temp_dir, "", $temp_filename ) );
    	}
    	$zip->close();
    
        // create the export folder if it doesn't exist
        if ( ! file_exists( LEPTON_PATH.'/modules/droplets/export' ) )
        {
            mkdir(LEPTON_PATH.'/modules/droplets/export');
        }
        
        if ( ! copy( $temp_file, LEPTON_PATH.'/modules/droplets/export/'.$filename.'.zip' ) )
        {
            echo '<div class="drfail">Unable to move the exported ZIP-File!</div>';
            $download = LEPTON_URL.'/temp/'.$filename.'.zip';
        }
        else {
            unlink( $temp_file );
            $download = LEPTON_URL.'/modules/droplets/export/'.$filename.'.zip';
        }
		echo '<div class="drok">Backup created - <a href="'.$download.'">Download</a></div>';
    }
    rm_full_dir($temp_dir);

    return $MOD_DROPLET[ 'Backup created' ] . '<br /><br />' . implode( "\n", $info ) . '<br /><br /><a href="' . $download . '">Download</a>';

} // end function export_droplets()

/**
 *
 **/
function import_droplets()
{
    global $admin, $parser, $database, $MOD_DROPLET;

    $groups = $admin->get_groups_id();
    if ( !is_allowed( 'Import_droplets', $groups ) )
    {
        $admin->print_error( $MOD_DROPLET[ "You don't have the permission to do this" ] );
    }

    $problem = NULL;

    if ( count( $_FILES ) )
    {
        list( $result, $data ) = droplets_upload( 'file' );
        $info = NULL;
        if ( is_array( $data ) )
        {
            $isIndexed = array_values( $data ) === $data;
            if ( $isIndexed )
            {
                $info .= implode( '<br />', $data );
            }
            else
            {
                foreach ( $data as $key => $value )
                {
                    $info .= $key . ' -> ' . $value . "<br />";
                }
            }
        }
        if ( $result == 'error' )
        {
            $problem = $MOD_DROPLET[ 'An error occurred when trying to import the Droplet(s)' ] . '<br /><br />' . $info;
        }
        else
        {
			List_droplets( str_replace("{{count}}", count($data), $MOD_DROPLET[ 'Successfully imported Droplet(s)'] ));
            return;
        }
    }

    echo $parser->render(
    	'@droplets/import.lte',
    	array(
        	 'problem' => $problem
    	)
    );

} // end function import_droplets()

/**
 *
 **/
function delete_droplets()
{
    global $admin, $parser, $database, $MOD_DROPLET;

    $groups = $admin->get_groups_id();
    if ( !is_allowed( 'Delete_droplets', $groups ) )
    {
        $admin->print_error( $MOD_DROPLET[ "You don't have the permission to do this" ] );
    }

    $errors = array();

    // get all marked droplets
    $marked = isset( $_POST[ 'markeddroplet' ] ) ? $_POST[ 'markeddroplet' ] : array();

    if ( isset( $marked ) && !is_array( $marked ) )
    {
        $marked = array(
             $marked
        );
    }

    if ( !count( $marked ) )
    {
        list_droplets( $MOD_DROPLET[ 'Please mark some droplets to delete' ] );
        return; // should never be reached
    }

    foreach ( $marked as $id )
    {
        // get the name; needed to delete data file
        $query = $database->query( "SELECT name FROM " . TABLE_PREFIX . "mod_droplets WHERE id = '$id'" );
        $data  = $query->fetchRow();
        $database->query( "DELETE FROM " . TABLE_PREFIX . "mod_droplets WHERE id = '$id'" );
        if ( $database->is_error() )
        {
            $errors[] = sprintf($MOD_DROPLET[ 'Unable to delete droplet: {{id}}'], array(
                 'id' => $id
            ) );
        }
    }

    list_droplets( implode( "<br />", $errors ) );
    return;

} // end function delete_droplets()

/**
 * copy a droplet
 **/
function copy_droplet( $id )
{
    global $database, $admin, $MOD_DROPLET;

    $groups = $admin->get_groups_id();
    if ( !is_allowed( 'Modify_droplets', $groups ) )
    {
        $admin->print_error( $MOD_DROPLET[ "You don't have the permission to do this" ] );
    }

    $query    = $database->query( "SELECT * FROM " . TABLE_PREFIX . "mod_droplets WHERE id = '$id'" );
    $data     = $query->fetchRow();
    $tags     = array(
        '<?php',
        '?>',
        '<?'
    );
    $code     = addslashes( str_replace( $tags, '', $data[ 'code' ] ) );
    $new_name = $data[ 'name' ] . "_copy";
    $i        = 1;

    // look for doubles
    $found = $database->query( 'SELECT * FROM ' . TABLE_PREFIX . "mod_droplets WHERE name='$new_name'" );
    while ( $found->numRows() > 0 )
    {
        $new_name = $data[ 'name' ] . "_copy" . $i;
        $found    = $database->query( 'SELECT * FROM ' . TABLE_PREFIX . "mod_droplets WHERE name='$new_name'" );
        $i++;
    }

    // generate query
    $query = "INSERT INTO " . TABLE_PREFIX . "mod_droplets VALUES "
    //         ID      NAME         CODE              DESCRIPTION                            MOD_WHEN                     MOD_BY
		   . "(''," . "'$new_name', " . "'$code', " . "'" . $data[ 'description' ] . "', " . "'" . time() . "', " . "'" . $admin->get_user_id() . "', " . "1,1,1,0,'" . $data[ 'comments' ] . "' )";

    // add new droplet
    $result = $database->query( $query );
    if ( !$database->is_error() )
    {
        $new_id = $database->db_handle->lastInsertId();
        return edit_droplet( $new_id );
    }
    else
    {
        echo "ERROR: ", $database->get_error();
    }
}

/**
 * edit a droplet
 **/
function edit_droplet( $id )
{
    global $admin, $parser, $database, $MOD_DROPLET, $TEXT;

    $groups = $admin->get_groups_id();

    if ( $id == 'new' && !is_allowed( 'Add_droplets', $groups ) )
    {
        $admin->print_error( $MOD_DROPLET[ "You don't have the permission to do this" ] );
    }
    else
    {
        if ( !is_allowed( 'Modify_droplets', $groups ) )
        {
            $admin->print_error( $MOD_DROPLET[ "You dont have the permission to do this" ] );
        }
    }

    $problem  = NULL;
    $info     = NULL;
    $problems = array();

    if ( isset( $_POST[ 'cancel' ] ) )
    {
        return list_droplets();
    }

    if ( $id != 'new' )
    {
        $query        = $database->query( "SELECT * FROM " . TABLE_PREFIX . "mod_droplets WHERE id = '$id'" );
        $data         = $query->fetchRow();
    }
    else
    {
        $data = array(
            'name' => '',
            'active' => 1,
            'description' => '',
            'code' => '',
            'comments' => ''
        );
    }

    if ( isset( $_POST[ 'save' ] ) || isset( $_POST[ 'save_and_back' ] ) )
    {
        // check the code before saving
        if ( !check_syntax( stripslashes( $_POST[ 'code' ] ) ) )
        {
            $problem      = $MOD_DROPLET['Please check the syntax!'];
            $data         = $_POST;
            $data['code'] = htmlspecialchars($data['code']);
        }
        else
        {
            // syntax okay, check fields and save
            if ( $admin->get_post( 'name' ) == '' )
            {
                $problems[] = $MOD_DROPLET['Please enter a name!'];
            }
            if ( $admin->get_post( 'code' ) == '' )
            {
                $problems[] = $MOD_DROPLET['You have entered no code!'];
            }

            if ( !count( $problems ) )
            {
                $continue      = true;
                $title         = addslashes( $admin->get_post( 'name' ) );
                $active        = $admin->get_post( 'active' );
                $show_wysiwyg  = $admin->get_post( 'show_wysiwyg' );
                $description   = addslashes( $admin->get_post( 'description' ) );
                $tags          = array(
                    '<?php',
                    '?>',
                    '<?'
                );
                $content       = str_replace( $tags, '', $admin->get_post( 'code' ) );
                $comments      = addslashes( $admin->get_post( 'comments' ) );
                $modified_when = time();
                $modified_by   = $admin->get_user_id();
                if ( $id == 'new' )
                {
                    // check for doubles
                    $query = $database->query( "SELECT * FROM " . TABLE_PREFIX . "mod_droplets WHERE name = '$title'" );
                    if ( $query->numRows() > 0 )
                    {
                        $problem  = $MOD_DROPLET['There is already a droplet with the same name!'];
                        $continue = false;
                        $data     = $_POST;
                        $data['code'] = stripslashes( $_POST[ 'code' ] );
                    }
                    else
                    {
						// Generate query for a new insert
						$fields = array(
							'name'	=> $title,
							'code'	=> $content,
							'description'	=> stripslashes( $description ),  // we are using pdo here!
							'modified_when'	=> $modified_when,
							'modified_by'	=> $modified_by,
							'active'		=> $active,
							'admin_edit'	=> 1,
							'admin_view'	=> 1,
							'show_wysiwyg'	=> (!isset($show_wysiwyg) ? 0 : $show_wysiwyg),
							'comments'		=>stripslashes( $comments )  // we are using pdo here!
						);
						
						$database->build_and_execute(
							'insert',
							TABLE_PREFIX . "mod_droplets",
							$fields
						);
						
					    if ( $database->is_error() )
					    {
					        echo "ERROR: ", $database->get_error();
					    }
                        
                    }
                }
                else
                {
                    // Update row
                    $database->query( "UPDATE " . TABLE_PREFIX . "mod_droplets SET name = '$title', active = '$active', show_wysiwyg = '$show_wysiwyg', description = '$description', code = '"
                                    . addslashes( $content )
                                    . "', comments = '$comments', modified_when = '$modified_when', modified_by = '$modified_by' WHERE id = '$id'"
                    );
                    // reload Droplet data
                    $query = $database->query( "SELECT * FROM " . TABLE_PREFIX . "mod_droplets WHERE id = '$id'" );
                    $data  = $query->fetchRow();
                }
                if ( $continue )
                {
                    // Check if there is a db error
                    if ( $database->is_error() )
                    {
                        $problem = $database->get_error();
                    }
                    else
                    {
                        if ( $id == 'new' || isset( $_POST[ 'save_and_back' ] ) )
                        {
                            list_droplets( $MOD_DROPLET['The Droplet was saved'] );
                            return; // should never be reached
                        }
                        else
                        {
                            $info = $MOD_DROPLET['The Droplet was saved'];
                        }
                    }
                }
            }
            else
            {
                $problem = implode( "<br />", $problems );
            }
        }
    }

    echo $parser->render(
    	'@droplets/edit.lte',
    	array(
    	'LANG'	=> $MOD_DROPLET,
        'problem' => $problem,
        'info' => $info,
        'data' => $data,
        'id'   => $id,
        'name' => $data[ 'name' ],
		'register_area' => registerEditArea( 'code'),
        'TEXT' => $TEXT
    ) );
} // end function edit_droplet()

/**
 *
 **/
function edit_droplet_perms( $id )
{
    global $admin, $parser, $database, $MOD_DROPLET;
    // look if user can set permissions
    $this_user_groups = $admin->get_groups_id();
    if ( !is_allowed( 'Manage_perms', $this_user_groups ) )
    {
        $admin->print_error( $MOD_DROPLET["You don't have the permission to do this"] );
    }

    $info = NULL;

    // get available groups
    $query = $database->query( 'SELECT group_id, name FROM ' . TABLE_PREFIX . 'groups ORDER BY name' );
    if ( $query->numRows() )
    {
        while ( $row = $query->fetchRow() )
        {
            $groups[ $row[ 'group_id' ] ] = $row[ 'name' ];
        }
    }

    // save perms
    if ( isset( $_REQUEST[ 'save' ] ) || isset( $_REQUEST[ 'save_and_back' ] ) )
    {
        $edit = (
			isset($_REQUEST['edit_perm'])
				? ( is_array($_REQUEST['edit_perm']) ? implode('|',$_REQUEST['edit_perm']) : $_REQUEST['edit_perm'] )
				: NULL
				);
        $view = (
				isset($_REQUEST['view_perm'])
				? ( is_array($_REQUEST['view_perm']) ? implode('|',$_REQUEST['view_perm']) : $_REQUEST['view_perm'] )
				: NULL
				);
        $database->query( 'REPLACE INTO ' . TABLE_PREFIX . "mod_droplets_permissions VALUES( '$id', '$edit', '$view' );" );
        $info = $MOD_DROPLET['The Droplet was saved'];
        if ( isset( $_REQUEST[ 'save_and_back' ] ) )
        {
            return list_droplets( $info );
        }
    }

    // get droplet data
    $query = $database->query( "SELECT * FROM " . TABLE_PREFIX . "mod_droplets AS t1 LEFT OUTER JOIN ".TABLE_PREFIX."mod_droplets_permissions AS t2 ON t1.id=t2.id WHERE t1.id = '$id'" );
    $data  = $query->fetchRow();

    foreach ( array(
        'Edit_perm',
        'view_perm'
    ) as $key )
    {
        $allowed_groups = ( isset( $data[ $key ] ) ? explode( '|', $data[ $key ] ) : array ());
        $line           = array();
        foreach ( $groups as $gid => $name )
        {
            $line[] = '<input type="checkbox" name="' . $key . '[]" id="' . $key . '_' . $gid . '" value="' . $gid . '"' . ( ( is_in_array( $allowed_groups, $gid ) || !count( $allowed_groups ) ) ? ' checked="checked"' : NULL ) . '>' . '<label for="' . $key . '_' . $gid . '">' . $name . '</label>' . "\n";
        }
        $rows[] = array(
            'groups' => implode( '', $line ),
            'name' => $MOD_DROPLET[ $key ]
        );
    }

    echo $parser->render(
    	'@droplets/droplet_permissions.lte',
    	array(
    	    'rows' => $rows,
    	    'info' => $info,
    	    'id'   => $id,
    	    'num_rows' => count($rows)
    	)
    );

} // end function edit_droplet_perms()

/**
 *	Aldus: switch between active/inactive
 **/
function toggle_active( $id )
{
    global $admin, $parser, $database;

    $groups = $admin->get_groups_id();
    if ( !is_allowed( 'Modify_droplets', $groups ) )
    {
        $admin->print_error( $MOD_DROPLET[ "You don't have the permission to do this" ] );
    }

    $query = $database->query( "SELECT `active` FROM " . TABLE_PREFIX . "mod_droplets WHERE id = '$id'" );
    $data  = $query->fetchRow();

    $new = ( $data[ 'active' ] == 1 ) ? 0 : 1;

    $database->query( 'UPDATE ' . TABLE_PREFIX . "mod_droplets SET active='$new' WHERE id = '$id'" );

} // end function toggle_active()

/**
 * checks if any item of $allowed is in $current
 **/
function is_in_array( $allowed, $current )
{
    if ( !is_array( $allowed ) )
    {
        if ( substr_count( $allowed, '|' ) )
        {
            $allowed = explode( '|', $allowed );
        }
        else
        {
            $allowed = array(
                 $allowed
            );
        }
    }
    if ( !is_array( $current ) )
    {
        if ( substr_count( $current, '|' ) )
        {
            $current = explode( '|', $current );
        }
        else
        {
            $current = array(
                 $current
            );
        }
    }
    foreach ( $allowed as $gid )
    {
        if ( in_array( $gid, $current ) )
        {
            return true;
        }
    }
    return false;
} // end function is_in_array()

/**
 *
 **/
function is_allowed( $perm, $gid )
{
    global $admin, $settings;
    // admin is always allowed to do all
    if ( $admin->get_user_id() == 1 )
    {
        return true;
    }
    if ( !array_key_exists( $perm, $settings ) )
    {
        return false;
    }
    else
    {
        $value = $settings[ $perm ];
        if ( !is_array( $value ) )
        {
            $value = array(
                 $value
            );
        }
        return is_in_array( $value, $gid );
    }
    return false;
} // end function is_allowed()

/**
 * check the syntax of given code
 **/
function check_syntax( $code )
{
   return eval( 'return true;' . $code );
}


/**
 * get the module settings from the DB; returns array
 **/
function get_settings()
{
    global $admin, $database;
    $settings = array();
    $query    = $database->query( 'SELECT * FROM ' . TABLE_PREFIX . 'mod_droplets_settings' );
    if ( $query->numRows() )
    {
        while ( $row = $query->fetchRow() )
        {
            if ( substr_count( $row[ 'value' ], '|' ) )
            {
                $row[ 'value' ] = explode( '|', $row[ 'value' ] );
            }
            $settings[ $row[ 'attribute' ] ] = $row[ 'value' ];
        }
    }
    return $settings;
} // end function get_settings()


?>