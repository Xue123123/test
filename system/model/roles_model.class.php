<?php
/* a class ext from model */

class roles_model extends model {
	function __construct() {
		parent::__construct('roles');
		$this->setPk('id');
	}

	function getRoles($id) {
		$RE = $this->select(array('id' => $id), " limit 1");
		return $RE[0];
	}
	#$page start 1;
	function getRolesList($page, $num = 20) {
		$sql = 'select * from `roles` order by id limit  ' . (($page - 1) * $num) . ', ' . $num;
		return $this->getAll($sql);
	}

}