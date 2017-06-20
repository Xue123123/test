<?php
/*  another class or mysqil*/
class mysqli_db {
	var $row;
	var $_row;
	var $SQL;
	var $query_result;
	var $connect_id;
	var $table;
	function mysqli_db($cf, $table = null) {

		$this->_connect($cf['host'], $cf['user'], $cf['passwd'], $cf['dbanme']);
		$this->table = $table;
	}
	//+======================================================+
	function _connect($server, $user, $password, $database) {

		$this->connect_id = mysqli_connect($server, $user, $password, $database);
		if ($this->connect_id) {
			return $this->connect_id;
		} else {
			return $this->error();
		}
	}

	//+======================================================+
	function error() {
		if (mysqli_connect_error() != '') {
			echo $this->SQL . "\n";
			echo '<b>MySQL Error</b>: ' . mysqli_connect_error() . '<br/>';
		}
	}
	//+======================================================+
	function query($query = NULL) {
		echo "-------" . $query . "------<br>";
		if ($query != NULL) {
			$this->SQL = 'select * from `session`';
			$this->query_result = mysqli_query($this->connect_id, $this->SQL, MYSQLI_STORE_RESULT);
			#print_r($this->query_result);
			echo '++++' . $this->query_result . '++++++<br>';
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
			$return = mysqli_num_rows($this->result);
		} else {
			$return = mysqli_num_rows($query_id);
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

		if ($this->row = mysqli_fetch_assoc($this->query_result)) {
			return true;
		} else {
			return false;
		}

	}
	//+======================================================+
	function set_data($array) {
		foreach ($array as $key => $value) {
			$this->_row[$key] = $value;
		}
	}
	function reset_data($array) {
		$this->_row = array();
		if (!$array) {
			return false;
		}

		foreach ($array as $key => $value) {
			$this->_row[$key] = $value;
		}
	}
	//+======================================================+
	function insert($a = NULL) {

		if (strlen($a) > 1) {
			$this->table = $a;
		}

		if (count($this->_row) == 0) {
			return false;
		}

		$tname = implode('`,`', array_keys($this->_row));
		$tname = '`' . $tname . '`';
		$tvals = implode('\',\'', str_replace("'", "''", array_values($this->_row)));
		$tvals = "'" . $tvals . "'";

		$sql = "INSERT INTO `" . $this->table . "` (" . $tname . ")   values(" . $tvals . ") ;";

		$this->query($sql);
		return $this->last_id();
	}
	// last insert id
	function last_id() {
		$id = mysqli_insert_id($this->connect_id);
		return $id;
	}
	//+======================================================+
	function update($a = NULL, $pk = 'id') {

		if (strlen($a) > 1) {
			$table = $a;
		} else {
			$table = $this->table;
		}

		if (count($this->_row) == 0) {
			return false;
		}

		foreach ($this->_row as $k => $v) {
			$set .= '`' . $k . '`=\'' . str_replace("'", "''", $v) . "'";
		}

		$sets = implode(',', $set);
		$sql = "UPDATE  `$table`  SET " . $sets . "   WHERE `" . $pk . "` ='" . $this->_row[$pk] . "'";
		return $this->query($sql);
	}
	//+======================================================+
	function get_affected_rows($query_id = "") {
		if ($query_id == NULL) {
			$return = mysqli_affected_rows($this->query_result);
		} else {
			$return = mysqli_affected_rows($query_id);
		}
		if (!$return) {
			$this->error();
		} else {
			return $return;
		}
	}
	//+======================================================+

	function set_names($ss = 'set names utf8 ') {
		$sql = $ss;
		$a = $this->query($sql);
		return $a;
	}
	//+======================================================+
	function clean() {
		$this->row = array();
		$this->_row = array();
		return true;
	}

	function getOne($sql) {
		$this->query($sql);
		return $this->fetch_row();
	}

	function autocommit($T = TRUE) {
		mysqli_autocommit($this->connect_id, $T);
	}
	function commit() {
		mysqli_commit($this->connect_id);
	}
	function roll_back() {
		mysqli_rollback($this->connect_id);
	}
	function __call($method, $args) {
		echo 'wrong mysql method!';
	}
}
