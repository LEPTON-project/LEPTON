<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		preCheckAddon
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if ( defined( 'LEPTON_PATH' ) )
{
	include( LEPTON_PATH . '/framework/class.secure.php' );
} //defined( 'LEPTON_PATH' )
else
{
	$oneback = "../";
	$root    = $oneback;
	$level   = 1;
	while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
	{
		$root .= $oneback;
		$level += 1;
	} //( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) )
	if ( file_exists( $root . '/framework/class.secure.php' ) )
	{
		include( $root . '/framework/class.secure.php' );
	} //file_exists( $root . '/framework/class.secure.php' )
	else
	{
		trigger_error( sprintf( "[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER[ 'SCRIPT_NAME' ] ), E_USER_ERROR );
	}
}
// end include class.secure.php

function preCheckAddon($temp_addon_file, $temp_path = NULL)
{
    /**
     * This funtion performs pretest upfront of the Add-On installation process.
     * The requirements can be specified via the array $PRECHECK which needs to
     * be defined in the optional Add-on file precheck.php.
     */
    global $database, $admin, $TEXT, $HEADING, $MESSAGE;
    // path to the temporary Add-on folder
    if ($temp_path == '')
    {
        $temp_path = LEPTON_PATH . '/temp/unzip';
    }
    
    // check if file precheck.php exists for the Add-On uploaded via WB installation routine
    if (!file_exists($temp_path . '/precheck.php'))
        return;
    
    // unset any previous declared PRECHECK array
    unset($PRECHECK);
    
    // include Add-On precheck.php file
    include($temp_path . '/precheck.php');
    
    // check if there are any Add-On requirements to check for
    if (!(isset($PRECHECK) && count($PRECHECK) > 0))
        return;
    
    // sort precheck array
    $PRECHECK = sortPreCheckArray($PRECHECK);
    
    $failed_checks = 0;
    $msg           = array();
    // check if specified addon requirements are fullfilled
    foreach ($PRECHECK as $key => $value)
    {
        switch ($key)
        {
            
            case 'LEPTON_VERSION':
                if (isset($value['VERSION']))
                {
                    // obtain operator for string comparison if exist
                    $operator = (isset($value['OPERATOR']) && trim($value['OPERATOR']) != '') ? $value['OPERATOR'] : '>=';
                    // compare versions and extract actual status
                    $status   = versionCompare(LEPTON_VERSION, $value['VERSION'], $operator);
                    $msg[]    = array(
                        'check' => sprintf('LEPTON-%s: ', $TEXT['VERSION']),
                        'required' => sprintf('%s %s', htmlentities($operator), $value['VERSION']),
                        'actual' => LEPTON_VERSION,
                        'status' => $status
                    );
                    // increase counter if required
                    if (!$status)
                        $failed_checks++;
                }
                break;
            
            case 'WB_VERSION':
                if (isset($value['VERSION']))
                {
                    // obtain operator for string comparison if exist
                    $operator = (isset($value['OPERATOR']) && trim($value['OPERATOR']) != '') ? $value['OPERATOR'] : '>=';
                    
                    // compare versions and extract actual status
                    $status = versionCompare(WB_VERSION, $value['VERSION'], $operator);
                    if (!$status)
                    {
                        $msg[] = array(
                            'check' => $TEXT['NO_LEPTON_ADDON'],
                            'required' => sprintf('%s %s', htmlentities($operator), $value['VERSION']),
                            'actual' => WB_VERSION,
                            'status' => $status
                        );
                        
                        // increase counter if required
                        $failed_checks++;
                    }
                }
                break;
            
            case 'WB_ADDONS':
            case 'ADDONS':			
                $ref = isset($PRECHECK['WB_ADDONS']) ? $PRECHECK['WB_ADDONS'] : $PRECHECK['ADDONS'];
            	foreach( $ref as $addon => $values)
                {
                        if (is_array($values))
                        {
                            // extract module version and operator
                            $version  = (isset($values['VERSION']) && trim($values['VERSION']) != '') ? $values['VERSION'] : '';
                            $operator = (isset($values['OPERATOR']) && trim($values['OPERATOR']) != '') ? $values['OPERATOR'] : '>=';
                        }
                        else
                        {
                            // no version and operator specified (only check if addon exists)
                            $addon    = strip_tags($values);
                            $version  = '';
                            $operator = '';
                        }
                        
                        // check if addon is listed in WB database
                        $table   = TABLE_PREFIX . 'addons';
                        $sql     = "SELECT * FROM `$table` WHERE `directory` = '" . addslashes($addon) . "'";
                        $results = $database->query($sql);
                        
                        $status       = false;
                        $addon_status = $TEXT['NOT_INSTALLED'];
                        if ($results && $row = $results->fetchRow())
                        {
                            $status       = true;
                            $addon_status = $TEXT['INSTALLED'];
                            
                            // compare version if required
                            if ($version != '')
                            {
                                $status       = versionCompare($row['version'], $version, $operator);
                                $addon_status = $row['version'];
                            }
                        }
                        
                        // provide addon status
                        $msg[] = array(
                            'check' => '&nbsp; ' . $TEXT['ADDON'] . ': ' . htmlentities($addon),
                            'required' => ($version != '') ? $operator . '&nbsp;' . $version : $TEXT['INSTALLED'],
                            'actual' => $addon_status,
                            'status' => $status
                        );
                        
                        // increase counter if required
                        if (!$status)
                            $failed_checks++;
                    }
                
                break;
            
            case 'PHP_VERSION':
                if (isset($value['VERSION']))
                {
                    // obtain operator for string comparison if exist
                    $operator = (isset($value['OPERATOR']) && trim($value['OPERATOR']) != '') ? $value['OPERATOR'] : '>=';
                    
                    // compare versions and extract actual status
                    $status = versionCompare(PHP_VERSION, $value['VERSION'], $operator);
                    $msg[]  = array(
                        'check' => 'PHP-' . $TEXT['VERSION'] . ': ',
                        'required' => htmlentities($operator) . '&nbsp;' . $value['VERSION'],
                        'actual' => PHP_VERSION,
                        'status' => $status
                    );
                    
                    // increase counter if required
                    if (!$status)
                        $failed_checks++;
                    
                }
                break;
            
            case 'PHP_EXTENSIONS':
                if (is_array($PRECHECK['PHP_EXTENSIONS']))
                {
                    foreach ($PRECHECK['PHP_EXTENSIONS'] as $extension)
                    {
                        $status = extension_loaded(strtolower($extension));
                        $msg[]  = array(
                            'check' => '&nbsp; ' . $TEXT['EXTENSION'] . ': ' . htmlentities($extension),
                            'required' => $TEXT['INSTALLED'],
                            'actual' => ($status) ? $TEXT['INSTALLED'] : $TEXT['NOT_INSTALLED'],
                            'status' => $status
                        );
                        
                        // increase counter if required
                        if (!$status)
                            $failed_checks++;
                    }
                }
                break;
            
            case 'PHP_SETTINGS':
                if (is_array($PRECHECK['PHP_SETTINGS']))
                {
                    foreach ($PRECHECK['PHP_SETTINGS'] as $setting => $value)
                    {
                        $actual_setting = ($temp = ini_get($setting)) ? $temp : 0;
                        $status         = ($actual_setting == $value);
                        
                        $msg[] = array(
                            'check' => '&nbsp; ' . ($setting),
                            'required' => $value,
                            'actual' => $actual_setting,
                            'status' => $status
                        );
                        
                        // increase counter if required
                        if (!$status)
                            $failed_checks++;
                    }
                }
                break;
            
            case 'CUSTOM_CHECKS':
                if (is_array($PRECHECK['CUSTOM_CHECKS']))
                {
                    foreach ($PRECHECK['CUSTOM_CHECKS'] as $key => $values)
                    {
                        $status = (true === array_key_exists('STATUS', $values)) ? $values['STATUS'] : false;
                        $msg[]  = array(
                            'check' => $key,
                            'required' => $values['REQUIRED'],
                            'actual' => $values['ACTUAL'],
                            'status' => $status
                        );
                    }
                    
                    // increase counter if required
                    if (!$status)
                        $failed_checks++;
                }
                break;
        }
    }
    
    // leave if all requirements are fullfilled
    if ($failed_checks == 0)
        return;
    
    // output summary table with requirements not fullfilled
    echo "
	<h2>".$HEADING['ADDON_PRECHECK_FAILED']."</h2>
	<p>".$MESSAGE['ADDON_PRECHECK_FAILED']."</p> 

	<table width='700px' cellpadding='4' border='0' style='margin: 0.5em; border-collapse: collapse; border: 1px solid silver;'>
	<tr>
		<th>".$TEXT['REQUIREMENT'].":</th>
		<th>".$TEXT['REQUIRED'].":</th>
		<th>".$TEXT['CURRENT'].":</th>
	</tr>
";
    
    foreach ($msg as $check)
    {
        echo '<tr>';
        $style = $check['status'] ? 'color: #46882B;' : 'color: #C00;';
        foreach ($check as $key => $value)
        {
            if ($key == 'status')
                continue;
            
            echo '<td style="' . $style . '">' . $value . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    
    // delete the temp unzip directory
    rm_full_dir($temp_path);
    
    // delete the temporary zip file of the Add-on
    if (file_exists($temp_addon_file))
    {
        unlink($temp_addon_file);
    }
    
    // output status message and die
    $admin->print_error('');
}

?>