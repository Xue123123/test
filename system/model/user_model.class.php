<?php
/* a class ext from model */

class user_model extends model {
	function __construct() {
		parent::__construct('user');
		$this->setPk('id');
	}

	function userPvAdd($user) {
		$sql = 'select * from `user` where username="' . $user . '" ';
		$RE = $this->getOne($sql);
		$pv = $RE['pv'] + 1;
		$this->where(array('id' => $RE['id']));
		$this->update(array('pv' => $pv));
	}

	function getUerByName($name) {
		$sql = 'select * from `user` where username="' . $user . '" ';
		$RE = $this->select(array('username' => $name), " limit 1");
		return $RE[0];
	}

	function getUserExt($id) {
		$sql = 'select * from `user_ext` where id="' . $id . '" ';
		return $this->getOne($sql);
	}
	#$page start 1;
	function getUserList($page, $num = 20) {
		$sql = 'select * from `user` order by id limit  ' . (($page - 1) * $num) . ', ' . $num;
		return $this->getAll($sql);
	}

}