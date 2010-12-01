<?php


class db {
	var $queries = array();
	var $query_count = 0;

	function db( $server, $user, $password, $database ) {
		$this->server	= $server;
		$this->user		= $user;
		$this->password	= $password;
		$this->database	= $database;
		$this->_connect();
	}
	
	function _connect() {
		// make sure that the core variables are set
		if (!$this->server		||
			!$this->user		||
			#!$this->password	||
			!$this->database	)
				die(p('Can\'t connect to database: missing information!'));
		
		// attempt a connection
		$this->link = mysql_pconnect( $this->server, $this->user, $this->password );
		if (!$this->link) {
			die(p('Could not connect: ' . mysql_error()));
		}
		//select DB
		$db_selected = mysql_select_db($this->database, $this->link);
		if ( !$db_selected ) {
			die(p("Can't use $this->database : " . mysql_error()));
		}
	}
	
	function query( $sql, $compact = true ) {
		global $debug;
		if ($debug === true) $this->queries[] = h($sql);
		$this->query_count++;
		$result = mysql_query( $sql, $this->link );
		// after running the query, we decide what to do about the result
		if ($result === false) {
			if ($debug === true) echo pre('MySQL Error: ' . mysql_error() . "<br />\n\nSQL Statement: " . $sql);
			return false;
		}
		
		switch ( substr( strtoupper( $sql ), 0, 4 ) ) {
			case "SELE":
			case "SHOW":
			case "DESC":
			case "EXPL":
				return $this->get_result( $result, 1, $compact );
				break;

			case "UPDA":
			case "DELE":
			case "INSE":
			case "DROP":
			case "REPL":
				return $this->get_result( $result, 2 );
				break;
		}
	}
	
	function array_query( $sql ) {
		return $this->query($sql, false);
	}
	
	function get_result( $result, $type, $compact = true ) {
		if ( $type == 1 ) {			// Database query
			$numrows = mysql_num_rows( $result );
			if ( $numrows == 1 && $compact) {
				return mysql_fetch_assoc($result);
			}
			elseif ( $numrows > 1 || ($numrows == 1 && !$compact)) {
				$ret = array();
				while ($row = mysql_fetch_assoc($result)) {
					$ret[] = $row;
				}
				return $ret;
			}
			elseif($numrows === 0) {
				return 0;
			}
			else {	// query failed
				# return "There was an error with the query:" . mysql_error();
				return false;
			}
		}
		elseif ( $type == 2 ) {		// Database updates
			$numrows = mysql_affected_rows( $this->link );
			if ( $result === true ) {
				# return "Query successful; $numrows rows were affected in the database";
				return $numrows;
			}
			else {
				# return "There was an error updating the database!" . mysql_error();
				return false;
			}		
		}
	}
	
	function info_dump() {
		global $debug;
		echo p('This script required ' . $this->query_count . ' queries');
		if ($debug) echo ul($this->queries);
	}
}
?>