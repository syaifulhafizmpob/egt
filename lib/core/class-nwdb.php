<?php
/**
 * MySQL DB Class
 *
 * This Class will handle MySQL database operations.<br/><br />
 * Original code from {@link http://php.justinvincent.com Justin Vincent (justin@visunet.ie)}<br />
 * Original code from {@link http://wordpress.org Wordpress}
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category library
 */

/**
 * update:
 * nawawi: 23-Jun-2011 - fallback to mysql extension instead of mysqli
 * nawawi: 25-Nov-2011 - get_full_field_info
 * nawawi: 29-Nov-2011 - code cleanup, cachetimeout minute calculation
 * nawawi: 03-Jan-2012 - add start transaction,commit and rollback
 * nawawi: 12-Jun-2012 - add option to save cols info
 * nawawi: 13-Jun-2012 - enable SET at query() function
 * nawawi: 15-Jun-2012 - code cleanup: cache, col_info, close handle, db_version()
 */


/**
 * Object constant
 */
define('OBJECT', 'OBJECT', true);

/**
 * Object key constant
 */
define('OBJECT_K', 'OBJECT_K', false);

/**
 * Array assoc constant
 */
define('ARRAY_A', 'ARRAY_A', false);

/**
 * Array numeric constant
 */
define('ARRAY_N', 'ARRAY_N', false);

if ( !defined('IS_MYSQLI') ) define('IS_MYSQLI', class_exists('mysqli') ? true : false );

class nwdb {
	/**
	 * The last error during query.
	 *
	 * @var string
	 */
	public $last_error = '';

	/**
	 * Amount of queries made
	 *
	 * @access public
	 * @var int
	 */
	public $num_queries = 0;

	/**
	 * Saved result of the last query made
	 *
	 * @access public
	 * @var string
	 */
	public $last_query = null;

	/**
	 * Saved last result
	 *
	 * @access public
	 * @var array
	 */
	public $last_result = array();

	/**
	 * Saved info on the table column
	 *
	 * @access public
	 * @var array
	 */
	public $col_info = array();

	/**
	 * Whether the database queries are ready to start executing.
	 *
	 * @access public
	 * @var bool
	 */
	public $dbready = false;

	/**
	 * Saved database name
	 *
	 * @access public
	 * @var string
	 */
	public $dbname = null;

	/**
	 * Whether to use disk cache
	 *
	 * @access public
	 * @var bool
	 */
	public $dbcache = false;

	/**
	 * Saved cache dir
	 *
	 * @access public
	 * @var string
	 */
	public $dbcache_dir = null;

	/**
	 * Wheter data is from cache or not
	 *
	 * @access public
	 * @var string
	 */
	public $dbcache_is_cached = false;

	/**
	 * Cache timeout in hour
	 *
	 * @access public
	 * @var string
	 */
	public $dbcache_timeout = 60; // minutes

	/**
	 * Cache store timestamp
	 *
	 * @access public
	 * @var string
	 */
	public $dbcache_timestamp = null;

	/**
	 * Maximum data can cache
	 *
	 * @access public
	 * @var string
	 */
	public $dbcache_max_data = 500;

	/**
	 * Get/Save cols info
	 *
	 * @access public
	 * @var string
	 */
	public $savecols = false;

	/**
	 * Exit on query error
	 *
	 * @access public
	 * @var string
	 */
	public $exit_on_query_error = true;

	public function _close($msg, $exit = true) {
		if ( $exit ) {
			if ( is_dir(CACHEPATH) ) _file_put(CACHEPATH."/db-error","[".date('d/m/Y H:i:s')."] ".$msg."\n",true,0666);
			exit;
			//_exit($msg);
		}
		//echo $this->last_error."\n";
		$this->dbready = false;
		if ( is_dir(CACHEPATH) ) _file_put(CACHEPATH."/db-error","[".date('d/m/Y H:i:s')."] ".$this->last_error."\n",true,0666);
		return false;
	}

	/**
	 * Connects to the database server and selects a database
	 *
	 * @param string $dbhost MySQL database host
	 * @param string $dbuser MySQL database user
	 * @param string $dbpassword MySQL database password
	 * @param string $dbname MySQL database name
	 */
	public function __construct($dbhost,$dbuser,$dbpassword,$dbname) {
		if ( defined('CACHEPATH') ) {
			$this->dbcache_dir = CACHEPATH.'/db/'.$dbname;
			if ( !is_dir($this->dbcache_dir) ) {
				if ( file_exists($this->dbcache_dir) ) @unlink($this->dbcache_dir);
				_mkdir($this->dbcache_dir,0777,true);
			} else {
				_chmod($this->dbcache_dir, 0777);
			}
		}

		if ( IS_MYSQLI ) {
			$this->dbh = @new mysqli($dbhost, $dbuser, $dbpassword, $dbname);
			if (is_object($this->dbh) && $this->dbh->connect_error) {
		    		$this->_close("CONNECT: ".$this->dbh->connect_error);
			}
		} else {
			$this->dbh = @mysql_connect($dbhost, $dbuser, $dbpassword, true);
			if ( !$this->dbh ) {
		    		$this->_close("CONNECT: ".mysql_error());
			}
		}

		if ( IS_MYSQLI ) {
			if ( is_object($this->dbh) ) {
				$this->dbready = true;
				$this->dbname = $dbname;
			}
		} else {
			if ( $this->dbh ) {
				$this->dbready = true;
				$this->select($dbname);
			}
		}
	}

	/**
	 * Destructor: Will run when database object is destroyed.
	 *
	 * @return bool Always true
	 */
	public function __destruct() { 
		if ( IS_MYSQLI ) {
			if ( is_object($this->dbh) ) @$this->dbh->close();
		} else {
			if ( $this->dbh ) @mysql_close($this->dbh);
		}
		return true;
	}

	/**
	 * Kill cached query results.
	 *
	 */
	public function flush() {
		$this->last_result = array();
		$this->col_info = array();
		$this->last_query = null;
		$this->dbcache_is_cached = false;
		$this->dbcache_timestamp = null;
	}

	/**
	 * Selects a database using the current database connection.
	 *
	 * @param string $db MySQL database name
	 * @return null Always null.
	 */
	public function select($db) {
		if ( !$this->dbready ) return;
		if ( IS_MYSQLI ) {
			if ( !@$this->dbh->select_db($db) ) {
				$this->dbready = false;
				$this->_close("selecting database failed!");
			}
		} else {
			if (!@mysql_select_db($db, $this->dbh)) {
				$this->ready = false;
				$this->_close("selecting database failed!");
			}
		}
		$this->dbname = $dbname;
	}

	public function escape($string) {
		if ( IS_MYSQLI ) {
			if ( is_object($this->dbh) ) {
				return $this->dbh->real_escape_string( $string );
			}
		} else {
			if ( $this->dbh ) {
				return mysql_real_escape_string( $string, $this->dbh );
			}
		}
		return addslashes( $string );
	}

	/**
	 * Escapes content by reference for insertion into the database, for security
	 *
	 * @param string $s
	 */
	public function escape_by_ref(&$string) {
		$string = $this->escape( $string );
	}

	/**
	 * Prepares a SQL query for safe execution.  Uses sprintf()-like syntax.
	 *
	 * This function only supports a small subset of the sprintf syntax; it only supports %d (decimal number), %s (string).
	 * Does not support sign, padding, alignment, width or precision specifiers.
	 * Does not support argument numbering/swapping.
	 *
	 * May be called like {@link http://php.net/sprintf sprintf()} or like {@link http://php.net/vsprintf vsprintf()}.
	 *
	 * Both %d and %s should be left unquoted in the query string.
	 *
	 * <code>
	 * nwdb::prepare( "SELECT * FROM `table` WHERE `column` = %s AND `field` = %d", "foo", 1337 )
	 * </code>
	 *
	 * @link http://php.net/sprintf Description of syntax.
	 *
	 * @param string $query Query statement with sprintf()-like placeholders
	 * @param array|mixed $args The array of variables to substitute into the query's placeholders if being called like {@link http://php.net/vsprintf vsprintf()}, or the first variable to substitute into the query's placeholders if being called like {@link http://php.net/sprintf sprintf()}.
	 * @param mixed $args,... further variables to substitute into the query's placeholders if being called like {@link http://php.net/sprintf sprintf()}.
	 * @return null|string Sanitized query string
	 */
	public function prepare($query = null) {
		if ( _null($query) ) return null;
		$args = func_get_args();
		array_shift($args);
		// If args were passed as an array (as in vsprintf), move them up
		if ( isset($args[0]) && is_array($args[0]) ) $args = $args[0];
		$query = str_replace("'%s'", '%s', $query); // in case someone mistakenly already singlequoted it
		$query = str_replace('"%s"', '%s', $query); // doublequote unquoting
		$query = str_replace('%s', "'%s'", $query); // quote the strings
		array_walk($args, array(&$this, 'escape_by_ref'));
		return @vsprintf($query, $args);
	}

	/**
	 * Query wrapper
	 */
	public function _rquery($query) {
		return ( IS_MYSQLI ? $this->dbh->query($query) : @mysql_query($query, $this->dbh) );
	}

	/**
	 * Memory free wrapper
	 */
	public function _free() {
		return ( IS_MYSQLI ? @$this->result->free() : @mysql_free_result($this->result) );
	}

	/**
	 * Perform a MySQL database query, using current database connection.
	 *
	 * @param string $query
	 * @return int|false Number of rows affected/selected or false on error
	 */
	public function query($query, $countonly = false) {
		// initialise return
		$return_val = 0;
		$this->flush();

		// Use cache
		if ( !$countonly && $this->dbcache ) {
			$cache = $this->dbcache_get($query);
			if ( !_null($cache) ) return $cache;
		}

		if ( !$this->dbready ) return false;

		// Keep track of the last query for debug..
		$this->last_query = $query;

		$this->result = $this->_rquery($query);

		++$this->num_queries;

		if ( $this->last_error = ( IS_MYSQLI ? $this->dbh->error : mysql_error($this->dbh) ) ) {
			return $this->_close($this->last_error, $this->exit_on_query_error);
		}

		if ( preg_match( '/^\s*(create|alter|truncate|drop|set) /i', $query ) ) {
			$return_val = $this->result;
		} elseif ( preg_match( '/^\s*(insert|delete|update|replace) /i', $query ) ) {
			$this->rows_affected = ( IS_MYSQLI ? $this->dbh->affected_rows : mysql_affected_rows($this->dbh) );
			// Take note of the insert_id
			if ( preg_match("/^\s*(insert|replace) /i",$query) ) {
				$this->insert_id = ( IS_MYSQLI ? $this->dbh->insert_id : mysql_insert_id($this->dbh) );
			}

			// Return number of rows affected
			$return_val = $this->rows_affected;

		} else {
			$i = 0;
			if ( !$countonly ) {
				if ( $this->savecols ) {
					while ($i < ( IS_MYSQLI ? $this->result->field_count : @mysql_num_fields($this->result) ) ) {
						$this->col_info[$i] = ( IS_MYSQLI ? @$this->result->fetch_field() : @mysql_fetch_field($this->result) );
						$i++;
					}
					// compatible
					if ( !IS_MYSQLI ) {
						foreach($this->col_info as $i => $v) {
							$this->col_info[$i]->orgname = $v->name;
							$this->col_info[$i]->orgtable = $v->table;
						}
					}
				}
			}
			
			$num_rows = 0;
			while ( $row = ( IS_MYSQLI ? @$this->result->fetch_object() : @mysql_fetch_object($this->result) ) ) {
				if ( !$countonly ) {
					$this->last_result[$num_rows] = $row;
				}
				$num_rows++;
			}

			// free memory
			$this->_free();


			// Log number of rows the query returned
			$this->num_rows = $num_rows;

			// Return number of rows selected
			$return_val = $this->num_rows;

			// cache query
			if ( !$countonly ) $this->dbcache_store($query);
		}

		return $return_val;
	}

	/**
	 * Insert a row into a table.
	 *
	 * <code>
	 * nwdb::insert( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( '%s', '%d' ) )
	 * </code>
	 *
	 * @see nwdb::prepare()
	 *
	 * @param string $table table name
	 * @param array $data Data to insert (in column => value pairs).  Both $data columns and $data values should be "raw" (neither should be SQL escaped).
	 * @param array|string $format (optional) An array of formats to be mapped to each of the value in $data.  If string, that format will be used for all of the values in $data.  A format is one of '%d', '%s' (decimal number, string).  If omitted, all values in $data will be treated as strings.
	 * @return int|false The number of rows inserted, or false on error.
	 */
	public function insert($table, $data, $format = null) {
		return $this->_insert_replace_helper( $table, $data, $format, 'INSERT' );
	}

	/**
	 * Replace a row into a table.
	 *
	 * <code>
	 * nwdb::replace( 'table', array( 'column' => 'foo', 'field' => 'bar' ) )
	 * nwdb::replace( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( '%s', '%d' ) )
	 * </code>
	 *
	 * @see nwdb::prepare()
	 *
	 * @param string $table table name
	 * @param array $data Data to insert (in column => value pairs). Both $data columns and $data values should be "raw" (neither should be SQL escaped).
	 * @param array|string $format Optional. An array of formats to be mapped to each of the value in $data. If string, that format will be used for all of the values in $data.
	 * 	A format is one of '%d', '%s' (decimal number, string). If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
	 * @return int|false The number of rows affected, or false on error.
	 */
	function replace( $table, $data, $format = null ) {
		return $this->_insert_replace_helper( $table, $data, $format, 'REPLACE' );
	}

	/**
	 * Helper function for insert and replace.
	 *
	 * Runs an insert or replace query based on $type argument.
	 *
	 * @see nwdb::prepare()
	 *
	 * @param string $table table name
	 * @param array $data Data to insert (in column => value pairs).  Both $data columns and $data values should be "raw" (neither should be SQL escaped).
	 * @param array|string $format Optional. An array of formats to be mapped to each of the value in $data. If string, that format will be used for all of the values in $data.
	 * 	A format is one of '%d', '%s' (decimal number, string). If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
	 * @return int|false The number of rows affected, or false on error.
	 */
	private function _insert_replace_helper( $table, $data, $format = null, $type = 'INSERT' ) {
		if ( ! in_array( strtoupper( $type ), array( 'REPLACE', 'INSERT' ) ) ) return false;
		$formats = $format;
		$fields = array_keys($data);
		$formatted_fields = array();
		foreach ( $fields as $field ) {
			if ( _array($format) ) {
				$form = ( $form = array_shift($formats) ) ? $form : $format[0];
			} else {
				$form = '%s';
			}
			$formatted_fields[] = $form;
		}
		$sql = "{$type} INTO `$table` (`" . implode( '`,`', $fields ) . "`) VALUES ('" . implode( "','", $formatted_fields ) . "')";
		return $this->query( $this->prepare( $sql, $data) );
	}

	/**
	 * Update a row in the table
	 *
	 * <code>
	 * nwdb::update( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( 'ID' => 1 ), array( '%s', '%d' ), array( '%d' ) )
	 * </code>
	 *
	 * @see nwdb::prepare()
	 *
	 * @param string $table table name
	 * @param array $data Data to update (in column => value pairs).  Both $data columns and $data values should be "raw" (neither should be SQL escaped).
	 * @param array $where A named array of WHERE clauses (in column => value pairs).  Multiple clauses will be joined with ANDs.  Both $where columns and $where values should be "raw".
	 * @param array|string $format (optional) An array of formats to be mapped to each of the values in $data.  If string, that format will be used for all of the values in $data.  A format is one of '%d', '%s' (decimal number, string).  If omitted, all values in $data will be treated as strings.
	 * @param array|string $format_where (optional) An array of formats to be mapped to each of the values in $where.  If string, that format will be used for all of  the items in $where.  A format is one of '%d', '%s' (decimal number, string).  If omitted, all values in $where will be treated as strings.
	 * @return int|false The number of rows updated, or false on error.
	 */
	public function update($table, $data, $where, $format = null, $where_format = null) {
		if ( !_array( $where ) ) return false;

		$formats = $format;
		$bits = $wheres = array();
		foreach ( array_keys($data) as $field ) {
			if ( _array($format) ) {
				$form = ( $form = array_shift($formats) ) ? $form : $format[0];
			} else {
				$form = '%s';
			}
			$bits[] = "`$field` = {$form}";
		}

		$where_formats = $where_format;
		foreach ( array_keys($where) as $field ) {
			if ( _array($where_format) ) {
				$form = ( $form = array_shift($where_formats) ) ? $form : $where_format[0];
			} else {
				$form = '%s';
			}
			$wheres[] = "`$field` = {$form}";
		}

		$sql = "UPDATE `$table` SET " . implode( ', ', $bits ) . ' WHERE ' . implode( ' AND ', $wheres );  
		return $this->query( $this->prepare( $sql, array_merge(array_values($data), array_values($where))) );
	}
  
	/**
	 * Retrieve one variable from the database.
	 *
	 * Executes a SQL query and returns the value from the SQL result.
	 * If the SQL result contains more than one column and/or more than one row, this function returns the value in the column and row specified.
	 * If $query is null, this function returns the value in the specified column and row from the previous SQL result.
	 *
	 * @param string|null $query SQL query.  If null, use the result from the previous query.
	 * @param int $x (optional) Column of value to return.  Indexed from 0.
	 * @param int $y (optional) Row of value to return.  Indexed from 0.
	 * @return string Database query result
	 */
	public function get_var($query=null, $x = 0, $y = 0) {
		if ( !_null($query) ) $this->query($query);

		$values=array();

		// Extract var out of cached results based x,y vals
		if ( !empty( $this->last_result[$y] ) ) {
			$values = array_values(get_object_vars($this->last_result[$y]));
		}

		// If there is a value return it else return null
		return (isset($values[$x]) && $values[$x]!=='') ? $values[$x] : null;
	}

	/**
	 * Retrieve one row from the database.
	 *
	 * Executes a SQL query and returns the row from the SQL result.
	 * If $query is null, this function returns the value in the specified column and row from the previous SQL result.
	 *
	 * @param string|null $query SQL query.
	 * @param string $output (optional) one of ARRAY_A | ARRAY_N | OBJECT constants.  Return an associative array (column => value, ...), a numerically indexed array (0 => value, ...) or an object ( ->column = value ), respectively.
	 * @param int $y (optional) Row to return.  Indexed from 0.
	 * @return mixed Database query result in format specifed by $output
	 */
	public function get_row($query = null, $output = OBJECT, $y = 0) {
		
		if ( !_null($query) ) $this->query($query);

		if ( !isset($this->last_result[$y]) ) return null;

		if ( $output == OBJECT ) {
			return $this->last_result[$y] ? (object)$this->last_result[$y] : null;
		} elseif ( $output == ARRAY_A ) {
			return $this->last_result[$y] ? get_object_vars($this->last_result[$y]) : null;
		} elseif ( $output == ARRAY_N ) {
			return $this->last_result[$y] ? array_values(get_object_vars($this->last_result[$y])) : null;
		}
		return null;
	}

	/**
	 * Retrieve one column from the database.
	 *
	 * Executes a SQL query and returns the column from the SQL result.
	 * If the SQL result contains more than one column, this function returns the column specified.
	 * If $query is null, this function returns the specified column from the previous SQL result.
	 *
	 * @param string|null $query SQL query.  If null, use the result from the previous query.
	 * @param int $x Column to return.  Indexed from 0.
	 * @return array Database query result.  Array indexed from 0 by SQL result row number.
	 */
	public function get_col($query = null, $x = 0) {
		if ( !_null($query) ) $this->query($query);
		$new_array = array();
		// Extract the column values
		for ( $i=0; $i < count($this->last_result); $i++ ) {
			$new_array[$i] = $this->get_var(null, $x, $i);
		}
		return $new_array;
	}

	/**
	 * Retrieve an entire SQL result set from the database (i.e., many rows)
	 *
	 * Executes a SQL query and returns the entire SQL result.
	 *
	 * @param string $query SQL query.
	 * @param string $output (optional) ane of ARRAY_A | ARRAY_N | OBJECT | OBJECT_K constants.  With one of the first three, return an array of rows indexed from 0 by SQL result row number.  Each row is an associative array (column => value, ...), a numerically indexed array (0 => value, ...), or an object. ( ->column = value ), respectively.  With OBJECT_K, return an associative array of row objects keyed by the value of each row's first column's value.  Duplicate keys are discarded.
	 * @return mixed Database query results
	 */
	public function get_results($query = null, $output = OBJECT) {
		if ( _null($query) ) return null;

		$this->query($query);
		$new_array=array();

		if ( $output == OBJECT ) {
			// Return an integer-keyed array of row objects
			return (object)$this->last_result;
		} elseif ( $output == OBJECT_K ) {
			// Return an array of row objects with keys from column 1
			// (Duplicates are discarded)
			foreach ( $this->last_result as $row ) {
				// prepend @:  PHP Strict Standards:  Only variables should be passed by reference
				$key = @array_shift( get_object_vars( $row ) );
				if ( _null( $new_array[ $key ] ) ) $new_array[ $key ] = $row;
			}
			return (object)$new_array;
		} elseif ( $output == ARRAY_A || $output == ARRAY_N ) {
			// Return an integer-keyed array of...
			if ( $this->last_result ) {
				$i = 0;
				foreach( $this->last_result as $row ) {
					if ( $output == ARRAY_N ) {
						// ...integer-keyed row arrays
						$new_array[$i] = array_values( get_object_vars( $row ) );
					} else {
						// ...column name-keyed row arrays
						$new_array[$i] = get_object_vars( $row );
					}
					$i++;
				}
				return (array)$new_array;
			}
		}
	}

	/**
	 * Retrieve column metadata from the last query.
	 *
	 * @param string $info_type one of name, table, def, max_length, not_null, primary_key, multiple_key, unique_key, numeric, blob, type, unsigned, zerofill
	 * @param int $col_offset 0: col name. 1: which table the col's in. 2: col's max length. 3: if the col is numeric. 4: col's type
	 * @return mixed Column Results
	 */
	public function get_col_info($info_type = 'name', $col_offset = -1) {
		if ( $this->col_info ) {
			if ( $col_offset == -1 ) {
				$i = 0;
				foreach( $this->col_info as $col ) {
					$new_array[$i] = $col->{$info_type};
					$i++;
				}
				return $new_array;
			} else {
				return $this->col_info[$col_offset]->{$info_type};
			}
		}
	}

	/**
	 * Get tables size.
	 *
	 * @param string $dbname database name. If null default database will use.
	 * @return mixed array|false
	 */
	public function get_table_size($dbname=null) {
		if ( !$this->dbready ) return false;
		$query = "SHOW TABLE STATUS";
		if ( !_null($dbname) ) $query .= " FROM ".$dbname;
		$tables = array();
		$data = $this->get_results($query,ARRAY_A);
		if ( !_array($data) ) return false;
		while( $row = @array_shift($data) ) {
			$total_size = ($row[ "Data_length" ] + $row[ "Index_length" ]);
			$tables[$row['Name']] = _byteconvert($total_size,null,'%01.1f %s');
		}
		return $tables;
	}

	/**
	 * Get table list.
	 *
	 * @return mixed array|false
	 */
	public function get_table_list($db = null) {
		if ( !$this->dbready ) return false;
		$tables=array();
		$query = "SHOW TABLES";
		if ( !_null($db) ) $query .= " FROM `".$db."`";
		$data = $this->get_results($query,ARRAY_A);
		$this->dbcache = $thiscache;
		if ( !_array($data) ) return false;
		while( $row = @array_shift($data) ) {
			$tables[] = $row['Tables_in_'.DB_NAME];
		}
		return $tables;
	}

	/**
	 * The database version number
	 * @return false|string false on failure, version number on success
	*/
	public function db_version() {
		if ( IS_MYSQLI ) {
			if ( is_object($this->dbh) ) {
				return preg_replace('/[^0-9.].*/', '', $this->dbh->server_info );
			}
		} else {
			if ( $this->dbh ) {
				return preg_replace( '/[^0-9.].*/', '', mysql_get_server_info( $this->dbh ) );
			}
		}
		return false;
	}

	/**
	 * Database cache file
	 * @return var
	*/
	private function dbcache_file($query) {
		return $this->dbcache_dir."/".md5($query);
	}

	/**
	 * Save query result to file.
	 *
	 * @param string $query
	 * @return bool true|false
	 */
	public function dbcache_store($query=null) {
		if ( $this->dbcache ){
			if ( $this->num_rows > $this->dbcache_max_data ) {
				return false;
			}
			if ( !_null($this->dbcache_dir) && is_dir($this->dbcache_dir) ) {
				$file = $this->dbcache_file($query);
				$fileq = $file.".data";
				$filem = $file.".meta";
				$data = array(
					"col_info" => $filem,
					"last_result" => $this->last_result,
					"num_rows" => $this->num_rows,
					"return_value" => $this->num_rows,
					"timestamp" => time()
				);
				if ( $this->col_info ) @_file_put($filem,$this->col_info,false,0666);
				return _file_put($fileq,$data,false,0666);
			}
		}
		return false;
	}

	/**
	 * Get query result from file.
	 *
	 * @param string $query
	 * @return mixed null | number of rows
	 */
	public function dbcache_get($query) {
		if ( $this->dbcache && !_null($this->dbcache_dir) && is_dir($this->dbcache_dir) ) {
			$file = $this->dbcache_file($query);
			$fileq = $file.".data";
			$filem = $file.".meta";
			clearstatcache();
			if ( file_exists($fileq) && _num($this->dbcache_timeout) && (time() - filemtime($fileq)) > ($this->dbcache_timeout*60) ) {
				@unlink($fileq);
				@unlink($filem);
			} else {
				$cache = _file_get($fileq);
				if ( _array($cache) ) {
					$this->dbcache_is_cached = true;
					$this->col_info = ( file_exists($cache['col_info']) ? _file_get($cache['col_info']) : null );
					$this->last_result = $cache['last_result'];
					$this->num_rows = $cache['num_rows'];
					$this->dbcache_timestamp = $cache['timestamp'];
					return $cache['return_value'];
				}
			}
		}
		$this->dbcache_is_cached = false;
		return null;
	}

	/**
	 * Delete cache.
	 *
	 * @return bool true|false, default always true
	 */
	public function dbcache_clean($all = false) {
		clearstatcache();
		if ( !_null($this->dbcache_dir) && is_dir($this->dbcache_dir) && basename($this->dbcache_dir) != '/' ) {
			if ( $all ) {
				return _unlink("{$this->dbcache_dir}/*");
			}
			$files = _glob($this->dbcache_dir."/*");
			if ( _array($files) ) {
				while( $file = @array_shift($files) ) {
					if ( file_exists($file) && _num($this->dbcache_timeout) && (time() - filemtime($file)) > ($this->dbcache_timeout*60) ) {
						@unlink($file);
					}
				}
			}
		}
		return true;
	}

	/**
	 * Check if data already insert.
	 *
	 * @param string $table table name.
	 * @param string $field field name.
	 * @param string $where where statement.
	 * @param string $cond Condition OR|AND
	 * @return bool true|false
	 */
	public function check_field($table, $field, $where, $cond = 'AND') {
		if ( !_array( $where ) ) return false;
		$wheres = array();
		foreach ( $where as $key => $value ) {
			$value = $this->escape($value);
			$wheres[] = "`$key` = '$value'";
		}
		$sql = "select `".$field."` from `".$table."` where ". implode(" ".$cond." ", $wheres );
		return $this->query($sql);
	}

	/**
	 * Get tables increment number.
	 *
	 * @param string $table table name.
	 * @return bool true|false
	 */
	public function get_nextid($table) {
                $data = $this->get_row(
                        $this->prepare("show table status like '%s';", $table),
                ARRAY_A);
                return $data['Auto_increment'];
        }

	/**
	 * Get table field info.
	 *
	 * @return mixed array|false
	 */
	public function get_full_field_info($table, $db = null, $field = null) {
		if ( !$this->dbready ) return false;
		$query = "SHOW FULL FIELDS FROM `".$table."`";
		if ( !_null($db) ) {
			$query .= " FROM `".$db."`";
		}

		if ( !_null($field) ) {
			$query .= " WHERE `Field`='".$field."'";
		}
		return $this->get_results($query, ARRAY_A);
	}

	/**
	 * Start transaction.
	 */
	public function start_transaction() {
		$this->_rquery("START TRANSACTION");
	}

	/**
	 * Stop transaction.
	 */
	public function stop_transaction() {
		$this->commit();
	}

	/**
	 * Restart transaction.
	 */
	public function restart_transaction() {
		$this->stop_transaction();
		$this->start_transaction();
	}

	/**
	 * Commit - Save changes.
	 */
	public function commit() {
		$this->_rquery("COMMIT");
	}

	/**
	 * Rollback - Undo changes.
	 */
	public function rollback() {
		$this->_rquery("ROLLBACK");
	}
}

?>
