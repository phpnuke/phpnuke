<?php

/**
 * please don't remove this comment block
 *
 * @author phptricks Team - Mohammad Anzawi
 * @author_uri https://phptricks.org
 * @uri https://github.com/anzawi/php-database-class
 * @version 3.2.0
 * @licence MIT -> https://opensource.org/licenses/MIT
 * @package PHPtricks\Database
 */
 
//namespace PHPnuke\Database;

/**
 * Class Database
 * @package PHPnuke\Database
 */
class Database implements \IteratorAggregate, \ArrayAccess
{
    /**
     * @var $_instance object
     * store DB class object to allow one connection with database (deny duplicate)
     * @access private
     */
    private static $_instance;
    private static $_db_config;

    private
	    /**
	     *  @var $_query string store sql statement
	     */
        $_query = '',
	    /**
	     *  @var $_params string store sql parameters
	     */
        $_params = [],
	    /**
	     *  @var $_count int store row count for _results variable
	     */
        $_count,
	    /**
	     *  @var $_columnCount int store column count for _results variable
	     */
        $_columnCount,
	    /**
	     *  @var $_error bool if cant fetch sql statement = true otherwise = false
	     */
		/*$_error = false,*/
	    /**
	     *  @var $_error array if cant fetch sql statement
	     */
        $_errors = [],
	    /**
	     *  @var $_schema string store DDL sql query
	     */
        $_schema,
	    /**
	     *  @var $_where string where type to using by default = WHERE
	     */
        $_where = "WHERE",
	    /**
	     *  @var $_lastWhere string to store last were statement
	     */
        $_lastWhere = "",
	    /**
	     *  @var $_lastParams string to store last sql parameters
	     */
        $_lastParams = "",
	    /**
	     *  @var $_sql string save query string
	     */
        $_sql,
	    /**
	     *  @var $_colsCount integer columns count for query results
	     * using into dataView() method to generate columns
	     */
	    $_colsCount = -1,
	    /**
	     * @var $_newValues null to save new value to use save() method
	     */
		$_newValues = null,
	    /**
	     * @var $config array to save database config
	     */
		$_config = [],
	    /**
	     * @var $_lastInsertedId array to save last inserted id
	     */		
		$_lastInsertedId = 0,
	    /**
	     * @var $__whereTypes array to parse where conditions
	     */	
		$__whereTypes = ['AND', 'OR'];


    protected
        /**
         *  @var $_pdo object PDO object
         */
        $_pdo,
	    /**
	     * @var $_table string current table name
	     */
	    $_table,
	    /**
	     * @var $_results array store sql statement result
	     */
	    $_results,
	    /**
	     * @var $_idColumn string|null id columns name for current table by default is id
	     */
	    $_idColumn = "id";

	/**
	 * Database constructor.
	 * @param array $data query results if exists
	 * @param array $info store current table name and id columns name
	 *
	 * DON'T pass parameters to __construct.
	 */
    protected function __construct($data = [], $info = [])
    {		
		$this->_config = [
			'fetch' => self::$_db_config['pn_dbfetch'],
			'default' => self::$_db_config['pn_dbtype'],
			'dbcharset' => self::$_db_config['pn_dbcharset'],
			'connections' => [
				// MySQL 3.x/4.x/5.x
				'mysql' => [
					'driver' => 'mysql',
					'host_name' => self::$_db_config['pn_dbhost'],
					'db_name' => self::$_db_config['pn_dbname'],
					'db_user' => self::$_db_config['pn_dbuname'],
					'db_password' => self::$_db_config['pn_dbpass']
				],

				// PostgreSQL
				'pgsql' => [
					'driver' => 'pgsql',
					'host_name' => self::$_db_config['pn_dbhost'],
					'db_name' => self::$_db_config['pn_dbname'],
					'db_user' => self::$_db_config['pn_dbuname'],
					'db_password' => self::$_db_config['pn_dbpass']
				],

				// SQLite
				'sqlite' => [
					'db_path' => 'my/database/path/database.db',
				],

				//	MS SQL Server
				'mssql' => [
					'driver' => 'mssql',
					'host_name' => self::$_db_config['pn_dbhost'],
					'db_name' => self::$_db_config['pn_dbname'],
					'db_user' => self::$_db_config['pn_dbuname'],
					'db_password' => self::$_db_config['pn_dbpass']
				],

				//	MS SQL Server
				'sybase' => [
					'driver' => 'sybase',
					'host_name' => self::$_db_config['pn_dbhost'],
					'db_name' => self::$_db_config['pn_dbname'],
					'db_user' => self::$_db_config['pn_dbuname'],
					'db_password' => self::$_db_config['pn_dbpass']
				],

				// Oracle Call Interface
				'oci' => [
					'tns' => '
						DESCRIPTION =
							(ADDRESS_LIST =
							  (ADDRESS = (PROTOCOL = TCP)(HOST = yourip)(PORT = 1521))
							)
							(CONNECT_DATA =
							  (SERVICE_NAME = orcl)
							)
						  )',

					'db_user' => self::$_db_config['pn_dbuname'],
					'db_password' => self::$_db_config['pn_dbpass']
				]
			],


			"pagination" => [
				"no_data_found_message" => "Oops, No Data Found to show ..",
				"records_per_page"      => 10,
				"link_query_key"        => "page"
			],

			"search" => [
				"key" => "search",
				"method" => "get"
			]
		];
		
	    // class correct method as database driver selected in config data
	    call_user_func_array([$this, self::config()], [null]);
		
	    // check if data is sent
	    if($data)
	    {
		    // set id ,table name and results after that return sent data
		    $this->_idColumn = $info['id'];
		    $this->_table = $info['table'];
		    return $this->_results = $data;
	    }
    }
	
	protected function config($path = '')
	{
		if(strpos($path, ".") !== false)
			$path = explode(".", $path);
			
		if(is_array($path) && count($path))
		{
			foreach ($path as $setting)
			{
				if (isset($this->_config[$setting]))
				{
					$config = $this->_config[$setting];
				}
			}

			return $config;
		}
		else
		{
			if(isset($this->_config[$path]))
				return $this->_config[$path];

			$configValue = isset($this->_config['connections'][$this->_config['default']][$path]) ?
				$this->_config['connections'][$this->_config['default']][$path] : null;
			if($path)
			{
				if(!is_null($configValue))
				{
					return $configValue;
				}
			}
		}

		return $this->_config['default'];
	}

	/**
	 * Generate Column Name when use Database::dataView() method
	 * 
	 * convert column_name and columnName to Column Name 
	 */
	public function getColumnName($columnName = '')
	{
		return ucwords(str_replace("_", "", implode(" ", preg_split('/(?=[A-Z]|_)/', $columnName))));
	}
	
	/**
	 * @param $prop
	 * @return mixed
	 */
    public function __get($prop)
    {
        return isset($this->_results->$prop) ? $this->_results->$prop : null;
    }

    public function __set($prop, $value)
    {
		if (isset($this->_results->$prop))
	    {
		    if(!is_null($this->_newValues))
		        $this->_newValues->$prop = $value;
		    else
		    {
			    $this->_newValues = new \stdClass();
			    $this->_newValues->$prop = $value;
		    }
	    }
    }

    // foreach results
	public function getIterator()
	{
		$o = new \ArrayObject($this->_results);
		return $o->getIterator();
	}

	/**
	 * Connect database with mysql driver
	 * @param $null
	 */
	protected function mysql($null)
    {
        try
        {
            $this->_pdo = new \PDO("mysql:host=" . $this->config('host_name') . ";dbname=" .
                $this->config('db_name'), $this->config('db_user'), $this->config('db_password'));
            $this->_pdo->exec("set names " . $this->config('dbcharset'));
            $this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        } catch(\PDOException $e) {
            die($e->getMessage());
        }
    }
	/**
	 * Connect database with sqlite driver
	 * @param $null
	 */
    protected function sqlite($null)
    {
        try
        {
            $this->_pdo = new \PDO("sqlite:" . $this->config('db_path'));
            $this->_pdo->exec("set names " . $this->config('dbcharset'));
            $this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        } catch(\PDOException $e) {
            die($e->getMessage());
        }
    }

	/**
	 * Connect database with pgsql driver
	 * @param $null
	 */
    protected function pgsql($null)
    {
        try
        {
            $this->_pdo = new \PDO('pgsql:user='. $this->config('db_user') .'
          dbname=' . $this->config('db_name') . ' password='.$this->config('db_password'));
            $this->_pdo->exec("set names " . $this->config('dbcharset'));
            $this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        } catch(\PDOException $e) {
            die($e->getMessage());
        }
    }

	/**
	 * Connect database with mssql driver
	 * @param $null
	 */
    protected function mssql($null)
    {
        try
        {
            $this->_pdo = new \PDO("mssql:host=" . $this->config('host_name') . ";dbname=" .
                $this->config('db_name'), $this->config('db_user'), $this->config('db_password'));
            $this->_pdo->exec("set names " . $this->config('dbcharset'));
            $this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        } catch(\PDOException $e) {
            die($e->getMessage());
        }
    }

	/**
	 * Connect database with sybase driver
	 * @param $null
	 */
    protected function sybase($null)
    {
        try
        {
            $this->_pdo = new \PDO("sybase:host=" . $this->config('host_name') . ";dbname=" .
                $this->config('db_name'), $this->config('db_user'), $this->config('db_password'));
            $this->_pdo->exec("set names " . $this->config('dbcharset'));
            $this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        } catch(\PDOException $e) {
            die($e->getMessage());
        }
    }

	/**
	 * Connect database with oci driver
	 * @param $null
	 */
    protected function oci($null)
    {
        try{
            $conn = new \PDO("oci:dbname=".$this->config('tns'),
                $this->config('db_user'), $this->config('db_password'));
            $this->_pdo->exec("set names " . $this->config('dbcharset'));
            $this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        }catch(\PDOException $e){
            die ($e->getMessage());
        }
    }

    /**
     * DB::connect()
     * return instace
     * @return object
     */
    public static function connect($pn_dbhost = "localhost", $pn_dbuname = "root" , $pn_dbpass = "", $pn_dbname = "", $pn_dbtype ="mysql", $pn_dbfetch = "PDO::FETCH_ASSOC", $pn_dbcharset = "utf8mb4", $persistency = false)
    {
	    // do deny duplicate connection
	    // check if $_instance is null or not
	    // if null so connect database
	    // otherwise return current connection object
		
        if(!isset(self::$_instance) || self::$_instance == null || $persistency) {
			self::$_db_config = array(
				"pn_dbhost" => $pn_dbhost, 
				"pn_dbuname" => $pn_dbuname, 
				"pn_dbpass" => $pn_dbpass, 
				"pn_dbname" => $pn_dbname, 
				"pn_dbtype" => $pn_dbtype, 
				"pn_dbfetch" => $pn_dbfetch,
				"pn_dbcharset" => $pn_dbcharset
			);
            self::$_instance = new Database();
        }
		
        return self::$_instance;
    }

	public function setFetchType($fetchtype)
	{
		$this->_config['fetch'] = $fetchtype;
		return $this;
	}
   
	private function sql_params_check(&$sql, &$params)
	{
		if(is_array($params) && !empty($params))
		{
			$i = 0;
			$new_param_key = 0;
			$newparams = array();
			foreach($params as $param_key => $param_val)
			{
				$new_param_key = (!is_numeric($param_key) && substr($param_key, 0, 1) == ':') ? $param_key:$i;
				$newparams[$new_param_key] = $param_val;
				$i++;
			}
			
			$params = (sizeof($newparams) == sizeof($params)) ? $newparams:$params;

			$i = -1;
			while (strpos($sql, "?")) {
				$i++;
				if(!array_key_exists($i, $params)) continue;
				$params[":_p$i"] = $params[$i];
				unset($params[$i]);
				$sql = preg_replace("/[?]/", ":_p$i", $sql, 1);
			}
			
			//remove extra vars
			preg_match_all("#:([a-zA-Z0-9\_\-]*)#is", $sql, $matchs1);
			if(!empty($matchs1[1]))
			{
				foreach($params as $param_key => $param_val)
				{
					if(!in_array(str_replace(":","", $param_key), $matchs1[1]))
						unset($params[$param_key]);
				}
			}
		}
	}
   
   /**
     * DB::query()
     * check if sql statement is prepare
     * append value for sql statement if $params is set
     * fetch results
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function query($sql, $params = [])
    {
        $this->_query = "";
		$this->_count = null;
        $this->_where = "WHERE";
		$this->_results = null;
		$this->_lastInsertedId = null;
        // set _error. true to that if they can not be false for this function to work properly, this function makes the
	    // value of _error false if there is no implementation of the sentence correctly
        //$this->_error = false;
		
        // check if sql statement is prepared
		$this->sql_params_check($sql, $params);
		
        $query = $this->_pdo->prepare($sql);
		
		//print_r($query);
		//print_r($params);
		
        // if $params isset
        if(count($params)) {
            /**
             * @var $x int
             * counter
             */
            $x = 1;
            foreach($params as $param_key => &$param_val)
			{
                // append values to sql statement
				//echo" $param_key => $param_val => ".gettype($param_val)." \n";
				
				$pdo_param_type = \PDO::PARAM_STR;
				// now let's see if there is something more appropriate
				if (is_bool($param_val))
				  $pdo_param_type = \PDO::PARAM_BOOL;
				elseif (is_null($param_val))
				  $pdo_param_type = \PDO::PARAM_NULL;
				elseif (is_int($param_val))
				  $pdo_param_type = \PDO::PARAM_INT;

				if (is_null($param_val))
					$param_val = '';
					
				if(!is_numeric($param_key) && substr($param_key, 0, 1) == ':')
				{
					$query->bindParam($param_key, $param_val, $pdo_param_type);
				}
				else
				{
					$query->bindValue($x, $param_val, $pdo_param_type);
					$x++;
				}
            }
        }

        // check if sql statement executed
		try
		{
			//$bef = number_format((microtime(true)-_START_TIME), 8);
			$query->execute();
			//$aft = number_format((microtime(true)-_START_TIME), 8);
			//echo ($aft-$bef)."\n$sql\n---------------------------------------\n";
			
			$sql_method = strtoupper(substr(str_replace(array("\n","\r","\t"," "),"", $sql), 0, stripos($sql," ")));

			if($sql_method == "INSERT")
				$this->_lastInsertedId = $this->_pdo->lastInsertId();
			elseif($sql_method != "UPDATE")
				$this->_results = $query->fetchAll($this->config('fetch'));
			
			$this->_sql = $query;
			// set _results = data comes
			
			// set _count = count rows comes
			$this->_count = $query->rowCount();
			
			// set _columnCount = count column comes
			$this->_columnCount = $query->columnCount();
		}
		catch (\PDOException $e) {
			//$this->_error = true;
			if(count($e->errorInfo) == 3)
				$this->_errors[] = array_merge($e->errorInfo, array($sql));
		}

		$this->_lastParams = $this->_params;
		$this->_params = [];

        return $this;
    }

    /**
     * DB::sql_query()
     * alias of query
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function sql_query($sql, $params = [])
    {
		return $this->query($sql, $params);
    }

    /**
     * DB::insert()
     * insert into database tables
     * @param string $table
     * @param array $values
     * @return bool
     */
    public function insert($values = [])
    {
        // check if $values set
        if(count($values)) {
            /**
             * @var $fields type array
             * store fields user want insert value for them
             */
            $fields = array_keys($values);
            /**
             * @var $value type string
             * store value for fields user want inserted
             */
            $value = '';
            /**
             * @var $x type int
             * counter
             */
            $x = 1;
            foreach($values as $field) {
                // add new value
                $value .="?";

                if($x < count($values)) {
                    // add comma between values
                    $value .= ", ";
                }
                $x++;
            }
            // generate sql statement
            $sql = "INSERT INTO {$this->_table} (`" . implode('`,`', $fields) ."`)";
            $sql .= " VALUES({$value})";
            // check if query is not have an error
            if(!$this->query($sql, $values)->error()) {
                return $this->lastInsertId();
            }
        }

        return false;
    }
    /**
     * DB::multiinsert()
     * insert multi values into database tables
     * @param string $table
     * @param array $rows
     * @return bool
     */
    public function multiinsert($fields = [], $rows = [])
    {
	    // check if $rows set
        if(count($rows)) {
			$rows_values = [];
			$rows_statements = [];
			foreach($rows as $values)
			{
				// check if $values set
				if(count($values)) {
					/**
					 * @var $value type string
					 * store value for fields user want inserted
					 */
					$value = '';
					/**
					 * @var $x type int
					 * counter
					 */
					$x = 1;
					foreach($values as $field) {
						// add new value
						$value .="?";

						if($x < count($values)) {
							// add comma between values
							$value .= ", ";
						}
						$x++;
						$rows_values[] = $field;
					}
					
					$rows_statements[] = "($value)";
				}
			}

			// generate sql statement
			$sql = "INSERT INTO {$this->_table} (`" . implode('`,`', $fields) ."`)";
			$sql .= " VALUES ".implode(",\n", $rows_statements)."";
			// check if query is not have an error
			if(!$this->query($sql, $rows_values)->error())
				return true;
		}
		
        return false;
    }
	
	/**
	 * insert or update if exists
	 * @param $values
	 * @param array $conditionColumn
	 * @return bool|mixed
	 */
	public function createOrUpdate($values, $conditionColumn = [])
	{
		// check if we have condition for update
		// the condition must be ([column_name, value])
		if(count($conditionColumn))
		{
			$column = $conditionColumn[0];
			$value  = $conditionColumn[1];
		}
		else // if no condition so search by ID
		{
			$column = $this->_idColumn;
			$value  = isset($value[$this->_ColumnsId]) ? $value[$this->_ColumnsId] : null;
		}

		// check if any records exists by condition
		$exists = $this->where($column, $value)->first()->results();
		// if exist so update the record's
		if(count($exists))
		{
			return $this->where($column, $value)
				->update($values);
		}
		// insert new record
		return $this->insert($values);
	}
	
    /**
     * DB::update()
     *
     * @param string $table
     * @param array $values
     * @param array $where
     * @return bool
     */
    public function update($values = [], $_lastWhere = '', $_lastParams = '')
    {
        /**
         * @var $set type string
         * store update value
         * @example "column = value"
         */
        $set = array(); // initialize $set
        // initialize fields and values
        foreach($values as $i => $row) {
            $set[] = "{$i} = ".((is_array($row) && !empty($row)) ? "{$i}".$row[0]."".$row[1]."":"?");
			if(is_array($row) && !empty($row))
				unset($values[$i]);
        }
		
        // add comma between values
		$set = implode(", ", $set);

        // generate sql statement
        $sql = "UPDATE {$this->_table} SET {$set} " . (($this->_query != '') ? $this->_query:$_lastWhere);

        // check if query is not have an error
		$this->_params = (isset($this->_params) && !empty($this->_params)) ? $this->_params:$_lastParams;
        if(!$this->query($sql, array_merge($values, $this->_params))->error()) {
            return true;
        }

        return false;
    }

    public function save()
    {
	    $x = 1;
        
        /*foreach($this->results() as $i => $row)
        {
            if(!is_numeric($row))
                $this->_query .= " {$i} = '{$row}'";
            else
		      $this->_query .= " {$i} = {$row}";
		    // add comma between values
		    if($x < count((array)$this->results())) {
			    $this->_query .= " AND";
		    }

		    $x++;
	    }*/
		
	    return $this->update((array)$this->getNewValues(), self::$_instance->_lastWhere, self::$_instance->_lastParams);
    }

    /**
     * select from database
     * @param  array  $fields fields we need to select
     * @return Database result of select as Database object
     */
	public function select($fields = ['*'])
    {
	    /*if($fields != ['*'] && !is_null($this->_idColumn))
	    {
			if(!in_array($this->_idColumn, $fields))
			{
				$fields[] = $this->_idColumn;
			}
	    }*/
		
		$cols = array();
		
		foreach($fields as $as => $col)
		{
			$cols[] = (is_string($as) && $as != $col) ? "$col as $as":$col;
		}
		
		if(!empty($cols))
		{
			$sql = "SELECT " . implode(', ', $cols)
				. " FROM {$this->_table} {$this->_query}";

			$this->_query = $sql;
			
			return $this->collection([
				'results' => $this->query($sql, $this->_params)->results(),
				'table'   => $this->_table,
				'where'   => $this->_where,
				'id'      => $this->_idColumn
			]);
		}
		
		return false;
		
        // return new Database($this->query($sql)->results(), ['table' => $this->_table, 'id' => $this->_idColumn]);
    }

    protected function collection($collection)
    {
        return new Collection($collection, self::$_instance); 
    }

    protected function getCollection($table)
    {
        if(isset($this->__cach[md5($table)]))
        {
            return true;
        }

        return false;
    }

    /**
     * delete from table
     * @return bool
     */
    public function delete()
    {
	    $sql = "DELETE FROM ".$this->_table." " . $this->_query;
		return $this->query($sql, $this->_params);
			
		/*$results = (array)$this->_results;

		if($this->count() == 1)
		{
			return $this->remove($results);
		}

		for($i = 0; $this->count() > $i; $i++)
		{
			$this->remove( $results[$i]);
		}*/		
	    return true;
    }

    private function remove($data)
    {
	    $this->_where = "WHERE";
	    $x = 1;
	    foreach($data as $i => $row)
	    {
            if(!is_numeric($row))
		      $this->_where .= " {$i} = '{$row}'";
            else
                $this->_where .= " {$i} = {$row}";
		    // add comma between values
		    if($x < count((array)$data)) {
			    $this->_where .= " AND";
		    }
		    $x++;
	    }

		$this->_lastWhere .= $this->_where;
	    $sql = "DELETE FROM ".$this->_table." " . $this->_lastWhere;
	    return $this->query($sql, $this->_params);
    }
    /**
     * find single row from table via id
     * @param  int $id [description]
     * @return Database or object (as you choice from config file)  results or empty
     */
    public function find($id)
    {
        return $result = $this->where($this->_idColumn, $id)
            ->first();
    }

    /**
     * add where condition to sql statement
     * @param  string  $field    field name from table
     * @param  string  $operator operator (= , <>, .. etc)
     * @param  mix $value    the value
     * @return object        this class
     */
    public function where($field, $operator, $value = false)
    {
    	/**
    	 * if $field is array 
    	 */
        if(is_array($field) && !empty($field))
        {
			$_query = " ".$this->_where." (";
			
			$__query = array();
			
			foreach($field as $param_key => $param_val)
			{
				$this->_params[] = $param_val;
				$__query[] = "$param_key = ?";
			}
			
			if(!empty($__query))
				$_query .= implode(" AND ", $__query);
			
			$_query .= ")";
			
			$this->_lastWhere .= $_query;
			$this->_query .= $_query;
        }
		else
		{
			/**
			 * if $value is not set then set $operator to (=) and
			 * $value to $operator
			 */
			if($value === false)
			{
				$value = $operator;
				$operator = "=";
			}

		   // if(!is_numeric($value))
				//$value = "'$value'";
			$this->_params[] = $value;
			$where = " $this->_where $field $operator ?";
			$this->_lastWhere .= $where;
			$this->_query .= $where;
		}
		
        $this->_where = "AND";
        return $this;
    }
	
	/**
	 * How to use
	 * $con = [
	 *  [
	 *      'sex', '=', 'female'
	 *  ],
	 * 'AND' => [
	 *      'position', '=', 'manager'
	 *  ]
	 * ];
	 * $db->table('table_name')->parseWhere($con)->select();
	 */
	public function parseWhere(array $cons, $type = "AND")
	{

		$this->_query .= " {$type} (";

		foreach ($cons as $con => $st)
		{
			if(is_array($st))
			{
				if(!is_numeric($st[2]))
					$st[2] = "'$st[2]'";
				else
					$st[2] = "`$st[2]`";

				if (strtolower($con) === 'none' || $con === 0)
				{
					$this->_query .= " `{$st[0]}` $st[1] $st[2] ";
				}
				else
				{
					if ($this->con($con))
					{
						$this->_query .= " {$con} `{$st[0]}` $st[1] $st[2] ";
					}
				}
			}
			else
			{
				$this->_query .= " `{$cons[0]}` $cons[1] $cons[2] ";
				break;
			}
			
		}

		$this->_query .= ')';

		return $this;
	}

	private function con($con)
	{
		return in_array(strtoupper($con), $this->__whereTypes);
	}
	
    /**
     * between condition
     * @param  string $field  table field name
     * @param  arrya $values ['from', 'to']
     * @return object        this class
     */
    public function whereBetween($field, $values = [])
    {
    	if(count($values))
    	{
			$this->_params[] = $values[0];
			$this->_params[] = $values[1];			
			$where = " $this->_where $field BETWEEN ? and ?";
			$this->_lastWhere .= $where;
			$this->_query .= $where;
       		$this->_where = "AND";
    	}

        return $this;
    }

    /**
     * Like whare
     * @param  string $field database field name
     * @param  string $value value
     * @return object 	this class
     */
    /**
     * we can do that with where() methode
     * $db->table('test')->where('name', 'LIKE', '%moha%');
     */
    public function likeWhere($field, $value, $where = "AND")
    {
		if($value != '')
		{
			$this->_params[] = "%$value%";
			$where = " $this->_where $field LIKE ?";
			$this->_lastWhere .= $where;
			$this->_query .= $where;
			$this->_where = $where;
		}
		
        return $this;
    }
	
    /**
     * use functions in whare
     * @param  string $function_value database function code
     * @return object 	this class
     */
    public function whereFunction($function_value)
    {
		if($function_value != '')
		{
			$where = " $this->_where $function_value";
			$this->_lastWhere .= $where;
			$this->_query .= $where;
			$this->_where = "AND";
		}
        return $this;
    }

	/**
	 * add OR condition to sql statement
	 * @param  string  $field    field name from table
	 * @param  string  $operator operator (= , <>, .. etc)
	 * @param  mix $value    the value
	 * @return object        this class
	 */
    public function orWhere($field, $operator, $value = false)
    {
	    /**
	     * if $value is not set then set $operator to (=) and
	     * $value to $operator
	     */
		if($operator != '')
		{
			if($value === false)
			{
				$value = $operator;
				$operator = "=";
			}
			$this->_params[] = $value;

			$where = " OR $field $operator ?";
			$this->_lastWhere .= $where;
			$this->_query .= $where;
			$this->_where = "AND";
		}
        return $this;
    }

    /**
     * add in condition to query
     * @param  string  $field    field name from table
     * @param  array $value   the values
     * @return object        this class
     */
    public function in($field, $values = [])
    {
    	if(count($values))
    	{
			foreach($values as $value)
			{
				$this->_params[] = $value;
				$params_value[] = "?";
			}
			
			$where = " $this->_where $field IN (".implode(",", $params_value).")";
			$this->_lastWhere .= $where;
			$this->_query .= $where;
            $this->_where = "AND";
    	}

    	return $this;
    }

	/**
	 * add not in condition to query
	 * @param  string  $field    field name from table
	 * @param  array $value   the values
	 * @return object        this class
	 */
    public function notIn($field, $values = [])
    {
    	if(count($values))
    	{
			$this->_params[] = implode(",", $values);
			$where = " $this->_where $field NOT IN (?)";
			$this->_lastWhere .= $where;
			$this->_query .= $where;
            $this->_where = "AND";
    	}

    	return $this;
    }

	/**
	 * get first row from query results
	 * @return Database
	 */

	public function first($fields = ['*'])
	{
		$results = $this->select($fields)->results();
		
		if(count((array)$results))
		{
            return $this->collection([
                'results'			=> $results[0],
                'table'   			=> $this->_table,
                'id'     			=> $this->_idColumn,
            ]);
		}

        return $this->collection([
            'results' => [],
            'table'   => $this->_table,
            'id'      => $this->_idColumn
        ]);
	}

	public function last($fields = ['*'], $count = 0)
	{
		$results = $this->select($fields)->results();
		
		if(!empty($results))
		{
		
			$reverse = array_reverse($results);
			
			if(!$count)
			{
				$lastRecords = isset($reverse[0]) ? $reverse[0] : null;
			}
			else
			{
			
				$lastRecords = [];
				$j = 0;
				for($i = 0; $i < $count; $i++)
				{
					$lastRecords[$j] = $reverse[$i];
					$j++;
				}
			}
			
			if(count((array)$lastRecords))
			{
				return $this->collection([
					'results' => $lastRecords,
					'table'   => $this->_table,
					'id'      => $this->_idColumn
				]);
			}
		}
		
        return $this->collection([
            'results' => [],
            'table'   => $this->_table,
            'id'      => $this->_idColumn
        ]);
	}
	
	/**
	 * get last row from query results
	 * @return Database
	 */
	
	public function firstRecord()
	{
		$results = (array)$this->_results;

		if(count($results))
		{
			return isset($results[0]) ? $results[0] : $results;
		}

		return [];
	}

	/**
	 * add group by to query
	 * @param string $orders
	 * @return $this
	 */
    public function group_by($id)
    {
    	 $this->_query .= " GROUP BY {$id}";
    	return $this;
    }
	
	/**
	 * add order by to query
	 * @param string $orders
	 * @return $this
	 */
    public function order_by($orders = [])
    {
    	if(!empty($orders))
		{
			$order_query = array();
			$this->_query .= " ORDER BY ";
			foreach($orders as $order_key => $order_type)
				$order_query[] = "$order_key ".strtoupper($order_type)."";
			if(!empty($order_query))
				$this->_query .= implode(", ", $order_query);
		}
    	return $this;
    }

	/**
	 * add limit rows to query
	 * @param int $from
	 * @param int $to
	 * @return $this
	 */
    public function limit($from = 0, $to = 15)
    {
		$from = intval($from);
		$to = intval($to);
		
		if(($from == 0 && $to == 0))
			return $this;
		
    	$this->_query .= " LIMIT {$from}".(($to != 0) ? ", {$to}":"");
    	return $this;
    }

	/**
	 * @param $offset
	 * @return $this
	 */
    public function offset($offset)
    {
    	$this->_query .=" OFFSET " .$offset;
        return $this;
    }

    /**
     * DB::lastInsertId()
     * return _lastInsertedId variable
     * @return integer
     */
    public function lastInsertId()
    {
        return $this->_lastInsertedId;
    }
	
    /**
     * DB::error()
     * return _error variable
     * @return bool
     */
    public function error()
    {
        return (!empty($this->_errors)) ? true:false;
    }
	
    /**
     * DB::getErrors()
     * return _errors data
     * @return bool
     */
    public function getErrors($mode='all')
    {
        if(!empty($this->_errors))
		{
			foreach($this->_errors as $key => $error_data)
			{
				$errors[] = array(
					"SQLSTATE"	=> $error_data[0],
					"code"		=> $error_data[1],
					"message"	=> $error_data[2],
					"query"		=> $error_data[3],
				);
			}
			if($mode == 'last')
				return end($errors);
			else
				return $errors;
		}
		return [];
    }

    /**
     * set _table var value
     * @param  string $table the table name
     * @return object - DBContent
     */
    public function table($table)
    {
		$this->_count = 0;
        $this->_lastWhere = "";
        $this->_where = "WHERE";
		$this->_results = null;
		$this->_lastInsertedId = 0;
        $this->_table = $table;
        return $this;
    }

	/**
	 * change id columns name
	 * @param string $idName
	 */
    public function idName($idName = "id")
    {
	    $this->_idColumn = $idName;

	    return $this;
    }

    public function results()
    {
        return $this->_results;
    }

    /**
     * Show last query
     * @return string
     */
    public function showMeQuery()
    {
    	return $this->_sql;
    }

	/**
	 *
	 * New In V.2.1.0
	 *
	 */

	/**
	 * @sense v.2.1.0
	 * pagination functionality
	 * @param int $recordsCount count records per page
	 * @return array
	 */
	/**
	 * How to Use:
	 *
	 * $db = PHPnuke\Database\Database::connect();
	 * $results = $db->table("blog")->paginate(15);
	 *
	 * var_dump($results);
	 *
	 * now add to url this string query (?page=2 or 3 or 4 .. etc)
	 * see (link() method to know how to generate navigation automatically)
	 */
	public function paginate($recordsCount = 0)
	{
		if($recordsCount === 0)
			$recordsCount = config("pagination.records_per_page");

		// this method accept one argument must be an integer number .
		if(!is_integer($recordsCount))
		{
			trigger_error("Oops, the records count must be an integer number"
					. "<br> <p><strong>paginate method</strong> accept one argument must be"
					." an <strong>Integer Number</strong> , " . gettype($recordsCount) . " given!</p>"
					. "<br><pre>any question? contact me on team@phptricks.org</pre>", E_USER_ERROR);
		}
		// check current page
		$startFrom = isset($_GET[config("pagination.link_query_key")]) ?
			($_GET[config("pagination.link_query_key")] - 1) * $recordsCount : 0;

		// get pages count rounded up to the next highest integer
		$this->_colsCount = ceil(count($this->select()->results()) / $recordsCount);

		// return query results
		return $this->limit($startFrom, $recordsCount)->select();
	}

	/**
	 * view query results in table
	 * we need to create a simple table to view results of query
	 * @return string (html)
	 */
	/**
	 * How to Use:
	 *
	 * $db = PHPnuke\Database\Database::connect();
	 * $db->table("blog")->where("vote", ">", 2)->select();
	 * echo $db->dataView();
	 */
	public function dataView()
	{
		// get columns count to create the table
		$colsCount = count($this->firstRecord());
		// if no data received so return no data found!
		if($colsCount <= 0)
		{
			return config("pagination.no_data_found_message");
		}

		// to fix for counter -> on array we want to counter from columns count -1
		// on object we want the records count
		if(is_array($this->_results) && isset($this->_results[0]) && is_array($this->_results[0])) $colsCount -= 1;
		// get Columns name's
		$colsName = array_keys((array)$this->firstRecord());

		// init html <table> tag
		$html = "<table border=1><thead><tr>";

		/**
		 * create table header
		 * its contain table columns names
		 */
		foreach ($colsName as $colName)
		{
			$html .= "<th>";
			// get column name
			/**
			 * the getColumnName() function define in (config_function.php) file
			 * this function replace (_) to space for example (column_name -> Column Name)
			 * of separate words (columnName -> Column Name)
			 */
			$html .= getColumnName($colName);
			$html .= "</th>";
		}

		// end table header tag and open table body tag
		$html .= "</tr></thead><tbody>";

		// loop all results to create the table (tr's and td's)
		foreach ((array)$this->results() as $row)
		{
			$row = (array)$row; // make sure the $row is array and not an object

			if(count($row) > 1)
			{
				$html .= "<tr>"; // open tr tag
				// loop all columns in row to create <td>'s tags
				for ($i = 0; $i <= $colsCount; $i++)
				{
					$html .= "<td>";
					$html .= $row[$colsName[$i]]; // get current data from the row
					$html .= "</td>";
				}

				$html .= "</tr>";
			}
			else // first method is called not select
			{
				$html .= "<td>";
				$html .= $row[0]; // get current data from the row
				$html .= "</td>";
			}
		}

		$html .= "</tbody></table>";

		return $html; // return created table
	}

	/**
	 * create pagination list to navigate between pages
	 * @return string (html)
	 */
	/**
	 * How to Use:
	 *
	 * $db = PHPnuke\Database\Database::connect();
	 * $db->table("blog")->where("vote", ">", 2)->paginate(5);
	 * echo $db->link();
	 */
	public function link()
	{
		// get current url
		$link = $_SERVER['PHP_SELF'];

		// current page
		$currentPage =
			(isset($_GET[config("pagination.link_query_key")]) ?
			$_GET[config("pagination.link_query_key")]
			: 1);
		/**
		 * $html var to store <ul> tag
		 */
		$html = '';
		if($this->_colsCount > 0) // check if columns count is not 0 or less
		{
			$operator = $this->checkAndGetUriQuery();

			$html = "<ul class=\"pagination\">";
			// loop to get all pages
			for ($i = 1; $i <= $this->_colsCount; $i++)
			{
				// we need other pages link only ..
				if($i == $currentPage)
				{
					$html .= "<li>{$i}</li>";
				}
				else
				{
					$html .= "<li><a href=\"{$link}{$operator}" .
						config("pagination.link_query_key") .
						"={$i}\">{$i}</a></li>";
				}
			}

			 $html .= "</ul>";
		}

		return $html;
	}

	/**
	 * check if we have a string query in current uri other (pagination key)
	 * if not so return (?) otherwise we want to reorder a string query to keep other keys
	 * @return string
	 */
	private function checkAndGetUriQuery()
	{
		$get = $_GET;
		// remove pagination key from query string
		unset($get[config("pagination.link_query_key")]);
		// init query string and set init value (?)
		$queryString = "?";
		// check if we have other pagination key in query string
		if(count($get))
		{
			// reorder query string to keep other keys
			foreach ($get as $key => $value)
			{
				$queryString .= "{$key}" .
					(!myempty($value) ? "=" : "") . $value . "&";
			}

			return $queryString;
		}


		return "?";
	}

	/**
	 * @return int pages count when use paginate() method
	 */
	public function pagesCount()
	{
		if($this->_colsCount < 0)
			return null;

		return $this->_colsCount;
	}

	/**
	 * get count of columns for last select query
	 * @return int
	 */
	public function columnCount()
	{
		if(isset($this->_columnCount))
			return $this->_columnCount;
		else
		{
			$results = (array)$this->results();
			return isset($results[0]) ? count($this->_results[0]) : 0;
		}
	}
	/**
	 * get count of rows for last select query
	 * @return int
	 */
	public function count()
	{
		if(isset($this->_count))
			return $this->_count;
		else
		{
			$results = (array)$this->results();
			return (isset($results[0]) || count($results)) ? count($this->_results) : 0;
		}
	}
	
	/**
	 * get count of rows for last select query
	 * @return int
	 */
	public function columnNames()
	{
		if(isset($this->_columnCount))
		{
			$results = (array)$this->results();
			return isset($results[0]) ? array_keys($this->_results[0]) : '';
		}
	}
	/**
	 * alias of count()
	 * @return int
	 */
	public function sql_numrows()
	{
		return $this->count();
	}
	/**
	 * Join's
	 */
	/**
	 * make join between tables
	 * @param string $table
	 * @param array $condition
	 * @param string $join
	 * @return $this
	 */
	/**
	 * How to use :
	 * $db = PHPnuke\Database\Database::connect();
	 * $db->table("blog")->join("comments", [["comments.id", "=", blog.id]], "left");
	 *
	 * sql = SELECT * FROM blog LEFT JOIN comments ON comments.id = blog.id
	 */
	public function join($table, $conditions = [], $join = '')
	{
		if(!empty($conditions))
		{
			$this->_query .= strtoupper($join) . // convert $join to upper case (left -> LEFT)
					" JOIN {$table} ON ";
			foreach($conditions as $condition)
				// make sure the $condition has 3 indexes (`table_one.field`, operator, `table_tow.field`)
				
				if(count($condition) == 3)
				{
					//$this->_params[] = $condition[2];
					$this->_query .= "{$condition[0]} {$condition[1]} {$condition[2]}";
				}
				elseif(count($condition) == 4)
				{
					//$this->_params[] = $condition[3];
					$this->_query .= " {$condition[0]} {$condition[1]} {$condition[2]} {$condition[3]}";
				}
		}
		// that's it now return object from this class
		return $this;
	}

	/**
	 * check if table is exist in database
	 * @param string $table
	 * @return bool
	 */
	public function tableExist($table = '')
	{
		$table = $this->query("SHOW TABLES LIKE '{$table}'")->results();

		if(!is_null($table) && count($table))
			return true;

		return false;
	}

	/**
	 * End Added in V.2.1.0
	 */

	// create table
    // alter table [
    //      add column
    //      remove column
    //      rename column
    // ]
    // delete table
    //

    /*
            table('table')->schema([
                'column_name' => 'type',
                'column_name' => 'type|constraint',
                'column_name' => 'type|constraint,more_constraint,other_constraint',

            ])->create();

         */

    /*
        'id' => 'increments'
        mean -> this field is primary key, auto increment not null,  and unsigned
     */

    /**
     * set _schema var value
     * @param  array  $structures the structer od table
     * @return object   retrun DB object
     */
    public function schema($structures = [])
    {
        if(count($structures)) // check if isset $structers
        {
            /**
             * to store columns structers
             * @var array
             */
            $schema = [];

            foreach($structures as $column => $options)
            {
                $type = $options; // the type is the prototype of column
                $constraints = ''; // store all constraints for one column

                // check if we have a constraints
                if(!strpos($options, '|') === false)
                {

                    $constraints = explode('|', $options); // the separator to constraints is --> | <--
                    $type = $constraints[0]; // the type is first key
                    unset($constraints[0]); // remove type from constraints
                    $constraints = implode(' ', $constraints); // convert constraints to string
                    $constraints = strtr($constraints, [
                        'primary' => 'PRIMARY KEY', // change (primary to PRIMARY KEY -> its valid constraint in sql)
                        'increment' => 'AUTO_INCREMENT', // same primary
                        'not_null' => 'NOT NULL', // same primary
                    ]);
                }

                // check if type is 'increments' we want to change it to integer and add some constraints like primary key ,not null, unsigned and auto increment
                ($type == 'increments'? $type = "INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL": null);

                // check if type of column is string change it to valid sql type (VARCHAR and set length)
                // ['username' => 'string:255'] convert to username VARCHAR(255)
                if(strpos($type, 'string') !== false)
                {
                    $type = explode(':', $type);
                    $type = "VARCHAR({$type[1]})";
                }

                // check if column has a default value
                // ['username' => 'string:255|default:no-name'] convert to username VARCHAR(255) DEFAULT 'no name'
                if(strpos($constraints, 'default') !== false)
                {
                    preg_match("/(:)[A-Za-z0-9](.*)+/", $constraints, $match);

                    $match[0] = str_replace(':', '', $match[0]);
                    $temp = str_replace('-', ' ', $match[0]);
                    $constraints = str_replace(":" . $match[0] , " '{$temp}' ", $constraints);
                }

                // add key to schema var contains column _type constraints
                // ex: username VARCHAR(255) DEFUALT 'no name' NOT NULL
                $schema[] = "$column $type " . $constraints;

            }

            // set _schema the all columns structure
            $this->_schema = '(' . implode(",", $schema) . ')';

            return $this; // return DB object
        }

        return null; // return null
    }

    /**
     * this method to run sql statement and create table
     * @param  string $createStatement its create statement -> i mean you can change it to ->  CREATE :table IF NOT EXIST
     * @return bool
     */
    public function create($createStatement = "CREATE TABLE") // you can use (CREATE TABLE IF NOT EXIST)
    {
    	$createStatement = $createStatement . " :table ";
	    // check if table is not exist
	    // by default in (try catch) block we can detect this problem
	    // but if you want to display a custom error message you can uncomment
	    // this (if) block and set your error message
	    /*if($this->tableExist($this->_table))
	    {
	    	print ("Oops.. the table {$this->_table} already Exists in "
			    . config('host_name') . "/" . config("db_name"));
		    die;
	    }*/

        $createStatement = str_replace(':table', $this->_table, $createStatement);

        try
        {
            $this->_pdo->exec($createStatement . $this->_schema);
        }
        catch(\PDOException $e)
        {
            print $e->getMessage();
            return false;
        }

        return true;
    }

    public function drop()
    {
        try
        {
            $this->_pdo->exec("DROP TABLE {$this->_table}");
        }
        catch(\PDOException $e)
        {
            die($e->getMessage());
        }

        return true;
    }

	// "ALTER TABLE ADD COLUMN (COLUMN_NAME TYPE AND CONSTRAINT)"
	// "ALTER TABLE DROP COLUMN COLUMN_NAME"
	// "ALTER TABLE RENAME COLUMN (COLUMN_NAME TYPE AND CONSTRAINT)"
	//
	// table('table')->alterSchema(['add', 'column_name', 'type'])->alter();
	// table('table')->alterSchema(['drop', 'column_name'])->alter();
	// table('table')->alterSchema(['rename', 'column_name','new_name' ,'type'])->alter();
	// table('table')->alterSchema(['modify', 'column_name', 'new_type'])->alter();

    public function alterSchema($schema = [])
    {
        if(count($schema))
        {

            $function = $schema[0]."Column";

            unset($schema[0]);

            call_user_func_array([$this, $function], [$schema]);

            return $this;
        }

        return null;
    }

    public function alter()
    {
	    // check if table is not exist
	    // by default in (try catch) block we can detect this problem
	    // but if you want to display a custom error message you can uncomment
	    // this (if) block and set your error message
	    /*if(!$this->tableExist($this->_table))
	    {
	    	print ("Oops.. cant alter table {$this->_table} because is not Exists in "
			    . config('host_name') . "/" . config("db_name"));
		    die;
	    }*/
        try
        {
            $this->_pdo->exec("ALTER TABLE {$this->_table} {$this->_schema}");
        }
        catch(\PDOException $e)
        {
            die($e->getMessage());
        }
    }

    public function setSchema($schema)
    {
		$this->_schema = "$schema";
		
		return $this;
    }

    public function addColumn($options = [])
    {
        if(count($options) === 2)
            $this->_schema = "ADD COLUMN {$options[1]} {$options[2]}";
    }

    public function dropColumn($options = [])
    {
        if(count($options) === 1)
            $this->_schema = "DROP COLUMN {$options[1]}";
    }

    public function renameColumn($options = [])
    {
        if(count($options) === 3)
            $this->_schema = "CHANGE {$options[1]} {$options[2]} {$options[3]}";
    }

    public function typeColumn($options = [])
    {
        if(count($options) === 2)
            $this->_schema = "MODIFY {$options[1]} {$options[2]}";
    }

    public function showMeSchema()
    {
        return $this->_schema;
    }

	/**
	 * Whether a offset exists
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset <p>
	 * An offset to check for.
	 * </p>
	 * @return boolean true on success or false on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 * @since 5.0.0
	 */
	public function offsetExists($offset)
	{
		return isset($this->_results[$offset]);
	}

	/**
	 * Offset to retrieve
	 * @link http://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset <p>
	 * The offset to retrieve.
	 * </p>
	 * @return mixed Can return all value types.
	 * @since 5.0.0
	 */
	public function offsetGet($offset)
	{
		return $this->_results[$offset];
	}

	/**
	 * Offset to set
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset <p>
	 * The offset to assign the value to.
	 * </p>
	 * @param mixed $value <p>
	 * The value to set.
	 * </p>
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetSet($offset, $value)
	{
		if (isset($this->_results[$offset]))
		{
			if(!is_null($this->_newValues))
				$this->_newValues[$offset] = $value;
			else
			{
				$this->_newValues = [];
				$this->_newValues[$offset]= $value;
			}
		}
	}

	/**
	 * Offset to unset
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset <p>
	 * The offset to unset.
	 * </p>
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetUnset($offset)
	{
		return null;
	}

	private function getNewValues()
	{
		return $this->_newValues;
	}
}

class Collection extends Database
{
    public function __construct($data, $connection = null)
    {
        if(isset($connection))
         {
            $this->_table				= $data['table'];
            $this->_results				= $data['results'];
            $this->_idColumn			= $data['id'];
            $this->_pdo					= $connection->_pdo;
        }
        else
            $this->_results = $data;
    }

    public function all()
    {
        return $this->results();
    }

    public function myempty()
    {
        return myempty($this->_results);
    }

    public function first($fields = ['*'])
    {
        return isset($this->_results[0]) ? $this->_results[0] : null;
    }

	public function last($fields = ['*'], $count = 0)
	{
		$reverse = array_reverse($this->results());
		
		if(!$count)
		{
			return isset($reverse[0]) ? $reverse[0] : null;
		}
		
		$lastRecords = [];
		$j = 0;
		for($i = 0; $i < $count; $i++)
		{
			$lastRecords[$j] = $reverse[$i];
			$j++;
		}
		return $lastRecords;
	}

    public function each(callable $callback)
    {
        foreach ($this->results() as $key => $value)
        {
            $callback($value, $key);
        }

        return $this;
    }

    public function filter(callable $callback = null)
    {
        if($callback)
        {
            return new static(array_filter($this->results(), $callback));
        }

        // exclude null and empty
        return new static(array_filter($this->results()));
    }

    public function keys()
    {
        return new static(array_keys($this->results()));
    }

    public function map(callable $callback)
    {
        $keys = $this->keys()->all();
        $results = array_map($callback, $this->results(), $keys);

        return new static(array_combine($keys, $results));
    }

    public function toJson()
    {
        return json_encode($this->results());
    }

    public function __toString()
    {
        return $this->toJson();
    }

    public function merge($items)
    {
        return new static(
            array_merge(
                    $this->results(), 
                    $this->toArray($items)
                )
            );
    }

    protected function toArray($items)
    {
        if(!is_array($items) && $items instanceof Collection)
            return $items->all();

        return $items;
    }
}

?>