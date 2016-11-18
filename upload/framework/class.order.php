<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @reformatted 2013-05-30
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

class order
{
    var $table = '';
    var $order_field = '';
    var $id_field = '';
    var $common_field = '';
    
    // Get the db values
    function __construct($table, $order_field = 'position', $id_field = 'id', $common_field = '')
    {
        $this->table        = $table;
        $this->order_field  = $order_field;
        $this->id_field     = $id_field;
        $this->common_field = $common_field;
    }
    
    // Move a row up
    function move_up($id)
    {
        global $database;
        // get current record
        
        $sql = 'SELECT `' . $this->order_field . '`';
        if ($this->common_field != '')
        {
            $sql .= ',`' . $this->common_field . '`';
        }
        $sql .= ' FROM `' . $this->table . '` WHERE `' . $this->id_field . '`=' . (int) $id;
        if (($res_current = $database->query($sql)) != false)
        {
            if (($rec_current = $res_current->fetchRow()) != false)
            {
                // get previous record
                $sql = 'SELECT `' . $this->id_field . '`,`' . $this->order_field . '` ';
                $sql .= 'FROM `' . $this->table . '` ';
                $sql .= 'WHERE (`' . $this->order_field . '`<' . $rec_current[$this->order_field] . ') ';
                if ($this->common_field != '')
                {
                    $sql .= 'AND (`' . $this->common_field . '`=\'' . $rec_current[$this->common_field] . '\') ';
                }
                $sql .= 'ORDER BY `' . $this->order_field . '` DESC';
                if (($res_prev = $database->query($sql)) != false)
                {
                    if (($rec_prev = $res_prev->fetchRow()) != false)
                    {
                        // update current record
                        $sql = 'UPDATE `' . $this->table . '` ';
                        $sql .= 'SET `' . $this->order_field . '`=' . $rec_prev[$this->order_field] . ' ';
                        $sql .= 'WHERE `' . $this->id_field . '`=' . $id;
                        if ($database->query($sql))
                        {
                            // update previous record
                            $sql = 'UPDATE `' . $this->table . '` ';
                            $sql .= 'SET `' . $this->order_field . '`=' . $rec_current[$this->order_field] . ' ';
                            $sql .= 'WHERE `' . $this->id_field . '`=' . $rec_prev[$this->id_field];
                            if ($database->query($sql))
                            {
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }
    // Move a row up
    function move_down($id)
    {
        global $database;
        // get current record
        $sql = 'SELECT `' . $this->order_field . '`';
        if ($this->common_field != '')
        {
            $sql .= ',`' . $this->common_field . '`';
        }
        $sql .= ' FROM `' . $this->table . '` WHERE `' . $this->id_field . '`=' . (int) $id;
        if (($res_current = $database->query($sql)) != false)
        {
            if (($rec_current = $res_current->fetchRow()) != false)
            {
                // get next record
                $sql = 'SELECT `' . $this->id_field . '`,`' . $this->order_field . '` ';
                $sql .= 'FROM `' . $this->table . '` ';
                $sql .= 'WHERE (`' . $this->order_field . '`>' . $rec_current[$this->order_field] . ') ';
                if ($this->common_field != '')
                {
                    $sql .= 'AND (`' . $this->common_field . '`=\'' . $rec_current[$this->common_field] . '\') ';
                }
                $sql .= 'ORDER BY `' . $this->order_field . '` ASC';
                if (($res_next = $database->query($sql)) != false)
                {
                    if (($rec_next = $res_next->fetchRow()) != false)
                    {
                        // update current record
                        $sql = 'UPDATE `' . $this->table . '` ';
                        $sql .= 'SET `' . $this->order_field . '`=' . $rec_next[$this->order_field] . ' ';
                        $sql .= 'WHERE `' . $this->id_field . '`=' . $id;
                        if ($database->query($sql))
                        {
                            // update next record
                            $sql = 'UPDATE `' . $this->table . '` ';
                            $sql .= 'SET `' . $this->order_field . '`=' . $rec_current[$this->order_field] . ' ';
                            $sql .= 'WHERE `' . $this->id_field . '`=' . $rec_next[$this->id_field];
                            if ($database->query($sql))
                            {
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }
    
    // Get new number for order
    function get_new($cf_value = '')
    {
        global $database;
        // Get last order
        $sql = 'SELECT MAX(`' . $this->order_field . '`) FROM `' . $this->table . '` ';
        if ($cf_value != '')
        {
            $sql .= 'WHERE `' . $this->common_field . '`=\'' . $cf_value . '\'';
        }
        return intval($database->get_one($sql)) + 1;
    }
    
    // Clean ordering (should be called time by time if rows in the middle have been deleted)
    function clean($cf_value = '')
    {
        global $database;
        // Loop through all records and give new order
        $sql = 'SELECT `' . $this->id_field . '` FROM `' . $this->table . '` ';
        if ($cf_value != '')
        {
            $sql .= 'WHERE `' . $this->common_field . '`=\'' . $cf_value . '\'';
        }
        $sql .= 'ORDER BY `' . $this->order_field . '` ASC';
        if (($res_ids = $database->query($sql)))
        {
            $count = 1;
            while (($rec_id = $res_ids->fetchRow()))
            {
                $sql = 'UPDATE `' . $this->table . '` SET `' . $this->order_field . '`=' . ($count++) . ' ';
                $sql .= 'WHERE `' . $this->id_field . '`=' . $rec_id[$this->id_field];
                if (!$database->query($sql))
                {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
    
}

?>