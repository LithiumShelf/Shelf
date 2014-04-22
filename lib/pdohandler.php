<?php

class PDOhandler {
    protected $_dbh;
    protected $_result;
	protected $_query;
	protected $_table;
	
    function connect(){
        $this->_dbh = new PDO("mysql:dbname=" . DB_NAME . ";host=" . DB_HOST, DB_USER, DB_PASSWORD);
        // Make any SQL syntax errors result in PHP errors.
        $this->_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    function disconnect(){
        $this->_dbh = null;
    }
    
    //select * from $_table
        function selectAll() {
        $query = 'SELECT * FROM :thistable';
        return $this->query($query);
    }
    
    //select $id from $_table
    function select($id) {
        $query = 'SELECT * FROM :thistable WHERE id = :id';
        $params = array(':id' => $id);
	return $this->query($query, $params);   
    }
    
    //delete by id
    function delete($id) {
	$query = 'DELETE FROM :table WHERE id = :id';
	$params = array(':id' => $id);
	return $this->query($query, $params);  
    }
    
    
    // calls a custom query, with the option of including parameters.
    // will return all data as an associative array
    // insert command will also return the insert id for display
    // $query: The mysql query or command (ex.->'SELECT * FROM tweets WHERE id = :i;';)
    // $params: associative arrays that associate the query variable with a variable object (ex.-> array(':i' => $id);)
    function query($query, $params){
		if(!$params[":thistable"]){
		    $params[":thistable"] = $this->_table;
		}

		try {
			$q = $dbh->prepare($query);
			$q->execute($params);
			$id = $dbh->lastInsertId(); // will be 0 if query wasn't an INSERT
			if ($id && stristr($query, 'insert')) {
			  return $id;
			} else {
			  return $q->fetchAll(PDO::FETCH_ASSOC);
			}
		} catch (PDOException $e) {
		  // Oops, something went wrong.
		  header("HTTP/1.1 500 Internal Server Error");
		  header("Content-type: text/plain");
		  if ($DEBUG) {
			// Detailed error for testing/debugging
			die("There was a SQL error:\n\n" . $e->getMessage());
		  } else {
			// Non-specific error for production
			die("Something went wrong. Sorry.");
			}
		}
	}

}
?>