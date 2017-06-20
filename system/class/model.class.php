<?php
/**
 *  model  for  db link
 */
class model {
	var $db;
	var $name;
	var $result;
	var $pk = 'id';
	var $pre;
	function __construct($name = null, $pre = null) {
		$this->db = db::connect();
		if (is_null($pre)) {
			$this->pre = _TAB_FIX_;
		} else {
			$this->pre = $pre;
		}
		if ($name) {
			$this->name = $this->pre . $name;
		}
	}
	//set table primary key
	function setPk($a) {
		$this->pk = $a;
	}
	//+======================================================+
	function update($data, $pk = '', $table = "") {
		if ($table == '') {
			$table = $this->name;
		}
		if (!$pk) {
			$pk = $this->pk;
		}
		if (count($data) == 0) {
			return false;
		}
		foreach ($data as $k => $v) {
			$set[] = '`' . $k . '`=\'' . str_replace("'", "''", $v) . "'";
		}
		$sets = implode(',', $set);
		$sql = "UPDATE  `$table`  SET " . $sets . " WHERE `" . $pk . "` ='" . $data[$pk] . "'";
		return $this->query($sql);
	}
	//+======================================================+
	function insert($data, $REPLACE = 0, $table = "") {
		if (count($data) == 0) {
			return false;
		}
		if ($table == '') {
			$table = $this->name;
		}
		$tname = implode('`,`', array_keys($data));
		$tname = '`' . $tname . '`';
		$tvals = implode('\',\'', str_replace("'", "''", array_values($data)));
		$tvals = "'" . $tvals . "'";
		$sql = " INTO `" . $table . "` (" . $tname . ")   values(" . $tvals . ") ;";
		if ($REPLACE) {
			$sql = "REPLACE" . $sql;
		} else {
			$sql = "INSERT" . $sql;
		}
		#echo $sql ;
		$this->query($sql);
		return $this->db->last_id();
	}
	// select  a short road
	function select($where, $table = "", $sle = "*", $ext = "") {
		if ($where) {
			$this->where($where);
		}
		if (!$table) {
			$table = $this->name;
		}
		return $this->getAll("select " . $sle . " from `" . $table . "` " . $this->where . ' ' . $ext);
	}
	//get sql array
	function db_get_array($sql) {
		return $this->getOne($sql);

	}
	//get sql arrays
	function db_get_arrays($sql) {
		return $this->getAll($sql);
	}
	//query
	function query($sql) {
		if ($this->pre) {
			$sql = $this->db_sql_pre($sql);
		}
		if (defined('_SQL_DEBUG_')) {
			echo $sql;
		}
		return $this->db->query($sql);
	}
	//dill the  pre
	function db_sql_pre($sql) {
		while (preg_match('/\{([a-zA-Z0-9_-]+)\}/', $sql, $regs)) {
			$found = $regs[1];
			$sql = str_replace("{" . $found . "}", $this->pre . $found, $sql);
		}
		return $sql;
	}

	//del  from table where id= , or  $this->where
	function delete($id = '', $table = "") {
		if (!$id) {
			return false;
		}
		if (!$table) {
			$table = $this->name;
		}
		$sql = 'DELETE  FROM  ' . $table . "  WHERE `" . $this->pk . "`='" . $id . "'";
		return $this->getOne($sql);
	}
	//get all  result
	function getAll($sql, $page = 0, $num = 20) {
		if ($page > 0) {
			$limit = ' limit ' . (($page - 1) * $num) . ',' . $num;
		} else {
			$limit = "";
		}

		$this->query($sql . $limit);
		while ($this->db->fetch_row()) {
			$re[] = $this->db->row;
		}
		$this->result = $re;
		return $re;
	}

	//get on  result
	function getOne($sql, $page = 0, $num = 20) {
		$re = $this->getAll($sql, $page, $num);
		return $re[0];
	}
	//get a row from table
	function selectOne($array, $table = null) {
		if (!$table) {
			$table = $this->name;
		}
		$whString = ' where 1 ';
		foreach ($array as $key => $value) {
			$whString .= ' and ' . '`' . $key . '`=' . "'" . $value . "' ";
		}
		$sql = 'select * from `' . $table . '` ' . $whString;
		return $this->getOne($sql);
	}
	//last id
	function last_insert_id() {
		return $this->db->last_id();
	}
	// get_affected_rows for  update and delete
	function affected_rows() {
		return $this->db->get_affected_rows();
	}
	//ge t get_renum for  select
	function get_renum() {
		return $this->db->get_renum();
	}
	function __call($method, $args) {
		echo 'wrong model method!' . $method;
	}
	//set_names
	function set_names($ss = 'set names utf8 ') {
		$sql = $ss;
		$a = $this->query($sql);
		return $a;
	}
}