<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

/**
 *  The LEPTON database connector.
 *
 */
class LEPTON_database
{
	
	/**
	 *	Private var for the current version of this class.
	 *  @var    string  $version
	 */
	private	$version = "4.0.0.1";
	
	/**
	 *	Protected var that holds the guid of this class.
	 *  @var    string  $guid
	 */
	protected $guid = "AE4BC01E-5CA2-4A87-BE8A-758837E6E552";

	/**
     *  Singleton The reference to *Singleton* instance of this class
     *  @var    object  $instance
     */
    private static $instance;
	
	/**
	 *	Private var for the error-messages.
	 *  @var    string  $error;
	 *  @access private
	 */
    private	$error = '';
    
    /**
     *	Public db handle.
     *  @var    object  
     */
    public $db_handle = false;
    
    /**
     *	Private var (bool) to handle the errors.
     *  @var    boolean $prompt_on_error
     *  @access private
     */
    private	$prompt_on_error = false;
    
    /**
     *	Private var to handle the session check.
     *  @var    boolean $override_session_check
     */
    private	$override_session_check = false;
    
    /**
	 *	Return the (singelton) instance of this class.
	 *
	 *	@param	array	$settings   Optional params - see "connect" for details
	 *
	 *  @see    connect
	 */
	public static function getInstance( &$settings=array() )
    {
        if (null === static::$instance) {
            static::$instance = new static();
            
            static::$instance->connect($settings);
        }
        
        return static::$instance;
    }
    
    /**
     * Constructor of the class database
     *
     *	@param	array	Assoc. array with the connection-settings. Pass by reference!
     *
     *	@seealso		Method "connect" for details.		
     */
    public function __construct(&$settings = array())
    {
        $this->connect($settings);
    }
    
    /**
     * Destructor of the class database
     */
    public function __destruct()
    {

    }
    
    /**
     * Set error
     * @param string
     */
    protected function set_error($error = '')
    {
        $this->error = $error;
    }
    
    /**
     * Return the last error
     * @return string
     */
    public function get_error()
    {
        return $this->error;
    }
    
    /**
     * Check if there occured any error
     * @return boolean
     */
    public function is_error()
    {
        return (!empty($this->error)) ? true : false;
    }
        
    /**
     * Get the MySQL DB handle
     * 
     * @return resource or boolean false if no connection is established
     */
    public function get_db_handle()
    {
        return $this->db_handle;
    }
        
    /**
     *	Establish the connection to the desired database defined in /config.php.
     *
     *	This function does not connect multiple times, if the connection is
     *	already established the existing database handle will be used.
     *
     *	@param	array	Assoc. array within optional settings. Pass by reference!
     *	@return nothing
     *
     *	@notice	Param 'settings' is an assoc. array with the connection-settins, e.g.:
     *			$settings = array(
     *				'host'	=> "example.tld",
     *				'user'	=> "example_user_string",
     *				'pass'	=> "example_user_password",
     *				'name'	=> "example_database_name",
     *				'port'	=>	"1003",
     *				'charset' => "latin1"
     *			);
     *
     *			To set up the connection to another charset as 'utf8' you can
     *			also define another one inside the config.php e.g.
     *				define('DB_CHARSET', 'latin1');
     *
     */
    final function connect(&$settings = array())
    {
        
        if(!defined("DB_USER")) {
        	$ini_file_name = LEPTON_PATH."/config/lepton.ini.php";
			if(!file_exists($ini_file_name)) { //patch for releases < 3.1.0 
				$ini_file_name = LEPTON_PATH."/framework/classes/setup.ini.php";
			}
        	if( true == file_exists( $ini_file_name ) ) {
        		$config = parse_ini_string(";".file_get_contents($ini_file_name), true );
        		if(!isset($settings['host'])) $settings['host'] = $config['database']['host'];
        		if(!isset($settings['user'])) $settings['user'] = $config['database']['user'];
        		if(!isset($settings['pass'])) $settings['pass'] = $config['database']['pass'];
        		if(!isset($settings['name'])) $settings['name'] = $config['database']['name'];
        		if(!isset($settings['port'])) $settings['port'] = $config['database']['port'];
				
				if(isset($config['database']['charset']))
				{
					if(!isset($settings['charset'])) $settings['charset'] = $config['database']['charset'];
        		}
        		
        		if(!defined("TABLE_PREFIX")) define("TABLE_PREFIX", $config['database']['prefix']);
        	
        		
        	} else {
                // Problem: no lepton.ini.php 
                exit('<p><b>Sorry, but this installation seems to be damaged! Please contact your webmaster!</b></p>');
			}
        }
        
        $setup = array(
            'host' => (array_key_exists('host', $settings) ? $settings['host'] : DB_HOST),
            'user' => (array_key_exists('user', $settings) ? $settings['user'] : DB_USERNAME),
            'pass' => (array_key_exists('pass', $settings) ? $settings['pass'] : DB_PASSWORD),
            'name' => (array_key_exists('name', $settings) ? $settings['name'] : DB_NAME),
            'port' => (array_key_exists('port', $settings) ? $settings['port'] : DB_PORT),
            'charset' => (array_key_exists('charset', $settings) ? $settings['charset'] : (defined('DB_CHARSET') ? DB_CHARSET : "utf8"))
        );
        
        // use DB_PORT only if it differ from the standard port 3306
        if ($setup['port'] !== '3306')
            $setup['host'] .= ';port=' . $setup['port'];
       
		$dsn = "mysql:dbname=".$setup['name'].";host=".$setup['host'].";charset=".$setup['charset'];
		
		try {
		
			$this->db_handle = new PDO(
				$dsn,
				$setup['user'],
				$setup['pass'],
				array(
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					PDO::ATTR_PERSISTENT => true,
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
				)
			);
			
			/**
			 *	Try to set the charset.
			 */
			$this->query("SET NAMES '".$setup['charset']."'");
			
			/**
			 *	Since 4.0 where could be also a setting for the MYSQL-Mode in the setup.ini.
			 *	
			 *	@see https://dev.mysql.com/doc/refman/5.7/en/sql-mode.html#sql-mode-important
			 *
			 */
			if(isset($config)) { 
			    if(isset( $config['database']['port'] ))
			    {
			        $this->query("SET GLOBAL sql_mode='".$config['database']['port']."'"); 
			    }
			    unset($config); 
		    }
		    		    		    
		} catch (PDOException $oError) {
			$this->set_error( $oError->getMessage() );
			echo 'Connection failed: ' . $oError->getMessage();
		}
		
    }
    
    /**
     * Switch prompting of errors on or off
     * If $switch=true the database will trigger each error.
     * 
     * @param boolean $switch	Default is 'true'.
     *
     */
    public function prompt_on_error($switch = true)
    {
        $this->prompt_on_error = $switch;
    }
    
    /**
     *	Execute a SQL query and return a handle to queryMySQL
     *
     *	@param	str     The query string to be executed
     *	@return	mixed   RESOURCE or NULL for error
     *
     */
    public function query( $sSqlQuery = "" )
    {
    	$this->error = "";
    	try{
		    $result = new queryMySQL( $this->db_handle );
    		return $result->query( $sSqlQuery );

		} catch( PDOException $error ) {
		    $this->set_error( $error->getMessage() );
		    return NULL;
		}
    }
        
    /**
     *	Exec a mysql-query without returning a result.
     *	A typical use is e.g. "DROP TABLE IF EXIST" or "SET NAMES ..."
     *	
     *	@param	string	Any (MySQL-) Query
     *	@param	array	Optional array within the values if place-holders are used.
     *
     *	@return	bool	True if success, otherwise false.
     *
     *  @code{.php} 
     *      LEPTON_database::getInstance()->simple_query("DROP TABLE `xxxxx` IF EXIST");
     *      // or
     *      LEPTON_database::getInstance()->simple_query(
     *          "INSERT into `TABLE_xxxxx` (`name`,`state`) VALUES('?','?');",
     *              array(
     *                  ['example','none'],
     *                  ['master', 'confirmed']
     *              )
     *      );
     *      // or
     *      LEPTON_database::getInstance()->simple_query(
     *          "INSERT into `TABLE_xxxxx` (`name`,`state`) VALUES( :name , :sta );",
     *              array(
     *                  "name"  => "Aldus",
     *                  "sta"   => "internal"
     *              )
     *      );
     *  @endcode
     */
    public function simple_query( $sMySQL_Query="", $aParams=array() )
    {
    	$this->error = "";
    	try
    	{
			$oStatement=$this->db_handle->prepare( $sMySQL_Query );
			if (count($aParams) > 0)
			{
				if(is_array($aParams[0]))
				{
					foreach($aParams as &$temp_params)
					{
						$oStatement->execute( $temp_params );
					}
				} else {
					$oStatement->execute( $aParams );
				}
			} else {
				$oStatement->execute();
			}
			return true;
		} catch( PDOException $error) {
			$this->error = $error->getMessage()."\n<p>Query: ".$sMySQL_Query."\n</p>\n";
			return false;
		}
    }
    
    /**
     *	Execute a SQL query and return the first row of the result array
     *
     *	@param	string	Any SQL-Query or statement
     *
     *	@return	mixed 	Value of the table-field or NULL for error
     */
    public function get_one($SQL)
    {
        $query = $this->query($SQL);
        if (($query !== null) && ($query->numRows() > 0))
        {
        	$temp = $query->fetchRow( PDO::FETCH_ASSOC );
            return array_shift($temp);
        }
        return null;
    }
   
    /**
     *	Get more than one result in an assoc. array. E.g. "Select * form [TP]pages" if you want
     *	to get all informations about all pages.
     *
     *	@param	string	The query itself
     *	@param	array	The array to store the results. Pass by reference!
     *	@return	bool	True if success, otherwise false.
     *
     */
    public function get_all($query, &$storrage = array())
    {
    	try{
			$result = new queryMySQL( $this->db_handle );
			$temp = $result->fetchAll( $query );
			
			foreach($temp as $data) $storrage[] = $data;
			
			return true;
		}
		catch (Exception $error) 
		{
			$this->error = $error->getMessage();
			return false;
		}
    }
    
    /**
     *	Returns a linear array within the tablenames of the current database
     *
     *	@param	string	Optional string to 'strip' chars from the tablenames, e.g. the prefix.
     *	@return	mixed	An array within the tablenames of the current database, or FALSE (bool).
     *
     */
    public function list_tables( $strip = "" )
    {
    	$this->error = "";
    	try
    	{
	    	$oStatement=$this->db_handle->prepare( "SHOW tables" );
			$oStatement->execute();
        	
        	$data = $oStatement->fetchAll();
        }
        catch (Exception $error) 
		{
			$this->error = $error->getMessage();
			return false;
		}
		
		$ret_value = array();
        foreach($data as &$ref)
        {
            $ret_value[] = array_shift( $ref );
        }
        if ($strip != "")
        {
            foreach ($ret_value as &$ref)
                $ref = str_replace($strip, "", $ref);
        }
        return $ret_value;
    }
    
	/**
	 *	Placed for all fields from a given table(-name) an assocc. array
	 *	inside a given storage-array.
	 *
	 *	@param	string	The tablename.
	 *	@param	array	An array to store the results. Pass by reference!
	 *	@return	bool	True if success, otherwise false.
	 *
	 */
	public function describe_table($tablename, &$storage = array())
	{
		$this->error = "";
		try
		{
			$oStatement=$this->db_handle->prepare( "DESCRIBE `" . $tablename . "`" );
			$oStatement->execute();
			$storage = $oStatement->fetchAll();
			return true;
		}
		catch (Exception $error) 
		{
			$this->error = $error->getMessage();
			return false;
		}
	}
    
    /**
     *
     *	Build a query-string.
     *
     *	@param	string	The type, e.g. "update", "insert" or "delete"
     *	@param	string	The tablename
     *	@param	array	Assoc. array, that holds the field names corr. to the values
     *	@param	string	The condition
     *
     *	@return	string	The mySQL query-string or NULL if no type march.
     *
     *	@example
     *	$values = 	array(
     *		'page_title' => 'example',
     *		'menu_title' => 'example'
     *	);
     *		
     *	$query = $database_instance->build_mysql_query(
     *		'update',
     *		TABLE_PREFIX."pages",
     *		$values,
     *		'page_id = '.$page_id
     *	);
     */
    public function build_mysql_query($type, $table_name, &$table_values, $condition = "")
    {
        
        switch (strtolower($type))
        {
            
            case 'update':
                $q = "UPDATE `" . $table_name . "` set ";
                foreach ($table_values as $field => $value)
                    $q .= "`" . $field . "`='" . $this->mysql_escape( $value ) . "',";
                $q = substr($q, 0, -1) . (($condition != "") ? " WHERE " . $condition : "");
                
                break;
            
            case 'insert':
                $q = "INSERT into `" . $table_name . "` (`";
                $q .= implode("`,`", array_keys($table_values)) . "`) VALUES ('";
                $q .= implode("','", $this->mysql_escape( array_values($table_values) ) ) . "')";
                
                break;
            
            case 'delete':
                $q = "DELETE from `" . $table_name . "`" . (($condition != "") ? " WHERE " . $condition : "");
                
                break;
            
            case 'select':
                $q = "SELECT `";
                $q .= implode("`,`", $table_values) . "` ";
                $q .= "FROM `" . $table_name . "`" . (($condition != "") ? " WHERE " . $condition : "");
                
                break;
            
            /**
             *	Alter a table
             *
             *	On this one, we're using the $table_values in a differ way than normal:
             *	The array has to be formed as
             * 
             *	'fieldname' => array(
             *		'operation'	=> add | delete | set	!required
             *		'type'		=> field type e.g. VARCHAR(255)	!required
             *		'charset'	=> optional charset
             *		'collate'	=> optional collate
             *		'params'	=> optional any additional params like "not NULL"
             *	);
             *
             *	e.g.:
             *
             *	$upgrade_fields = array(
             *		'plan'	=> array(
             *			'operation'	=> "add",
             *			'type'		=> "varchar(255)",
             *			'charset'	=> "utf8",
             *			'collate'	=> "utf8_general_ci",
             *			'params'	=> "not NULL default ''"
             *		),
             *		'sort'	=> array(
             *			'operation' => "add",
             *			'type'		=> "varchar(255)",
             *			'charset'	=> "utf8",
             *			'collate'	=> "utf8_general_ci",
             *			'params'	=> "not NULL default ''"
             *		)
             *	);
             *
             */
            case 'alter':
                $q = "ALTER TABLE `" . $table_name . "`";
                
                foreach ($table_values as $name => &$options)
                {
                    
                    if ((!isset($options['operation'])) || (!isset($options['type'])))
                        continue;
                    
                    $q .= " " . strtoupper($options['operation']) . " `" . $name . "` " . $options['type'];
                    
                    if (isset($options['charset']))
                        $q .= " CHARACTER SET " . $options['charset'];
                    if (isset($options['collate']))
                        $q .= " COLLATE " . $options['collate'];
                    if (isset($options['params']))
                        $q .= " " . $options['params'];
                    
                    $q .= ",";
                }
                $q = substr($q, 0, -1);
                
                break;
            
            default:
                $q = NULL; // "Error: type doesn't match to 'update', 'insert', or 'delete'!";
                
        }
        
        return $q;
    }
    
    /**
     *	A 'helper' function for method 'build_mysql_query'.
     *
     *	@param	mixed	Any string to be escaped, or an array wthin strings.
     *	@return	mixed	The escaped string or an array within theese.
     *
     */
	static public function mysql_escape($str) { 
		if(is_array($str))
			return array_map(__METHOD__, $str); 

		if(!empty($str) && is_string($str)) { 
			return str_replace(
				array('\\', "\0", "\n", "\r", "'", '"', "\x1a"),
				array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'),
				$str
			); 
		} 

		return $str; 
	}
	
    /**
     *	Public "shortcut" for executeing a single mySql-query without passing values.
     *
     *	@since	LEPTON 2.0
     *
     *	@param	string	A valid mySQL query.
     *	@param	bool	Fetch the result - default is false.
     *	@param	array	A storage array for the fetched results. Pass by reference!
     *	@param	bool	Try to get all entries. Default is true.
     *	@return	bool	False if fails, otherwise true.
     *
     */
    public function execute_query( $aQuery="", $bFetch=false, &$aStorage=array(), $bFetchAll=true ) {
		$this->error = "";
		try{
	    	$oStatement=$this->db_handle->prepare($aQuery);
	    	$oStatement->execute();
		
			if ($oStatement->rowCount() > 0) {
		    	if ( true === $bFetch ){
		    		$aStorage = (true === $bFetchAll)
	    				? $oStatement->fetchAll()
	    				: $oStatement->fetch()
	    				;
		    	}
		    }
		    return true;
    	} catch( PDOException $error) {
			$this->error = $error->getMessage();
			return false;
		}
    }
    
    /**
     *	Public function for prepare a given query within marker and execute it.
     *
     *	@see	execute_query, build_and_execute
     *
     *	@param	string	A valid mySQL query string within marker ('?' or ':name').
     *	@param	array	A valid array within the values. Type depending on the query. Pass by reference.
     *	@param	bool	Boolean for fetching the result(-s). Default is false.
     *	@param	array	Optional array to store the results. Pass by reference.
     *	@param	bool	Try to get all entries. Default is true.
     *	@return	bool	False if fails, otherwise true.
     *
     */
    public function prepare_and_execute( $sQuery="", &$aValues=array(), $bFetch=false, &$aStorage=array(), $bFetchAll=true ) {
		$this->error = "";
		try{
			$oStatement=$this->db_handle->prepare($sQuery);
	    	$oStatement->execute( $aValues );
    	
	    	if ($oStatement->rowCount() > 0) {
				if ( true === $bFetch ){
	    			$aStorage = (true === $bFetchAll)
	    				? $oStatement->fetchAll()
	    				: $oStatement->fetch()
	    				;
	    		}
	    	}
	    	return true;
	    } catch( PDOException $error) {
			$this->error = $error->getMessage();
			return false;
		}
    }
    
    /**
     *	Public function to build and execute a mySQL query direct.
     *	Use this function/method for update and insert values only.
     *	As for a simple select you can use "prepare_and_execute" above.
     *
     *	@param	string	$type           A "job"-type: this time only "update" and "insert" are supported.
     *	@param	string	$table_name     A valid tablename (incl. table-prefix).
     *	@param	array	$table_values   An array within the table-field-names and values. Pass by reference!
     *	@param	string	$condition      An optional condition for "update" - this time a simple string.
     *
     *	@return	bool	False if fails, otherwise true.
     *
     */
    public function build_and_execute( $type, $table_name, &$table_values, $condition="" ) {
    	$this->error = "";
    	switch( strtolower($type) ) {
    		case 'update':
    			$q = "UPDATE `". $table_name ."` SET ";
    			foreach($table_values as $field => $value) {
    				$q .= "`". $field ."`= :".$field.", ";
    			}
    			$q = substr($q, 0, -2) . (($condition != "") ? " WHERE " . $condition : "");
    			break;
   			
   			case 'insert':
   				$keys = array_keys($table_values);
                $q = "INSERT into `" . $table_name . "` (`";
                $q .= implode("`,`", $keys) . "`) VALUES (:";
                $q .= implode(", :", $keys) . ")";
               
                break;
            
   			default:
   				die("<build_and_execute>:: type unknown!");
   				break; 
    	}

    	try {
		
			$oStatement=$this->db_handle->prepare($q);
	    	$oStatement->execute( $table_values );

	    } catch( PDOException $error) {
			$this->error = $error->getMessage()."\n<p>Query: ".$q."\n</p>\n";
			return false;
		}
		
        return true;
    }
    
}

/**
 *  "Old" core-class to store/hold the mySQL results
 *
 */
final class queryMySQL
{
    /**
     *	Internal storrage for the results.
     *	Since we're using PDO this is type of PDOStatement(-object)!
     *
     */
    private $query_result = false;
    
    /**
     *	Internal PDO handle.
     *
     */
    private $pdo = NULL;
    
    /**
     *	Construtor of the class.
     *
     *	@param	object	A valid PDO Handle
     *
     */
    public function __construct( $pdo_handle )
    {
    	$this->pdo = &$pdo_handle;
    }
    
    /**
     *	Destructor of the class.
     *
     *	@notice	Free all 'Sub'-Objects.
     *
     */
    public function __destruct()
    {
    	// if this class var is an object we've to call the __destruct method by our self
    	if ($this->query_result != false) unset( $this->query_result );
    }
    
    /**
     * Execute a MySQL query statement and return the resource or false on error
     * 
     * @param string	$SQL query
     * @return object	This
     *
     */
    public function query($SQL)
    {
        $this->query_result = $this->pdo->query($SQL);
        return $this;
    }
    
    /**
     * Return the number of rows of the query result
     *
     * @return INT	The number of rows of the last query result.
     *
     */
    public function numRows()
    {
        return $this->query_result->rowCount();
    }
    
    /**
     *	Fetch a Row from the result array
     * 
     *	Specify $array_type you want to get back: PDO::FETCH_ASSOC, PDO::FETCH_NUM or PDO::FETCH_BOTH.
     *	Since we're using PDO there are also PDO constants supported.
     *	See http://www.php.net/manual/en/pdostatement.fetch.php for details.
     *
     *	This function return FALSE if there is no further row.
     *
     *	@param	INT		Typicaly a PHP Constant for the requestet type. Default is PDO::FETCH_ASSOC.
     *	@return	ARRAY	The result-array or false if there is no further row.
     *
     */
    public function fetchRow($array_type = PDO::FETCH_ASSOC)
    {
    	if (!in_array($array_type, array(PDO::FETCH_ASSOC, PDO::FETCH_NUM, PDO::FETCH_BOTH))) $array_type = PDO::FETCH_BOTH;
    	
        return $this->query_result->fetch( $array_type );
    }
    
    /**
     *	Public function to return all results of the query as
     *	an assoc. array.
     *
     *	@param	string	The query-string to execute.
     *	@return array	The results as assoc. array.
     *
     */
    public function fetchAll( $SQL ) {
    	$this->query_result = $this->pdo->query($SQL);
    	return $this->query_result->fetchAll( PDO::FETCH_ASSOC );
    }
}
?>