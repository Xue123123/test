<?php
/*  another class or mysqil*/
class mysql_db {
	var $row;
	var $SQL;
	var $query_result;
	var $connect_id;
	var $table;
	function mysql_db($cf, $table = null) {

		$this->_connect($cf['host'], $cf['user'], $cf['passwd'], $cf['dbname']);
		$this->table = $table;
	}
	//+======================================================+
	function _connect($server, $user, $password, $database) {

		$this->connect_id = mysql_connect($server, $user, $password);
		#echo $this->connect_id ;
		if ($this->connect_id) {
			mysql_select_db($database, $this->connect_id);
			return $this->connect_id;
		} else {
			return $this->error();
		}
	}

	//+======================================================+
	function error() {
		if (mysql_error() != '') {
			echo $this->SQL . "\n";
			echo '<b>MySQL Error</b>: ' . mysql_error() . '<br/>';
		}
	}
	//+======================================================+
	function query($query = NULL) {

		if ($query != NULL) {
			$this->SQL = $query;
			$this->query_result = mysql_query($this->SQL, $this->connect_id);
			#print_r($this->query_result);

			if (!$this->query_result) {
				return $this->error();
			} else {
				return true;
			}
		} else {
			return '<b>MySQL Error</b>: Empty Query!';
		}
	}
	//+======================================================+
	function get_renum($query_id = NULL) {
		if ($query_id == NULL) {
			$return = mysql_num_rows($this->result);
		} else {
			$return = mysql_num_rows($query_id);
		}
		if (!$return) {
			$this->error();
		} else {
			return $return;
		}
	}
	//+======================================================+
	function fetch_row() {
		if (!$this->query_result) {
			return false;
		}
		if ($this->row = @mysql_fetch_assoc($this->query_result)) {
			return true;
		} else {
			return false;
		}
	}
	// last insert id
	function last_id() {
		$id = mysql_insert_id($this->connect_id);
		return $id;
	}
	//+======================================================+
	function get_affected_rows($query_id = "") {
		if ($query_id == NULL) {
			$return = mysql_affected_rows($this->query_result);
		} else {
			$return = mysql_affected_rows($query_id);
		}
		if (!$return) {
			$this->error();
		} else {
			return $return;
		}
	}
	//last  call
	function __call($method, $args) {
		echo 'wrong mysql method!';
	}
}