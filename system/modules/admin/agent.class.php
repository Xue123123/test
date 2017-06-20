<?php
/**
 *   user class
 */
router::load_module_class('admin', 'admin');

class agent extends admin {
	function __construct() {
		parent::__construct();
		$this->checkLogin();
	}
# login index
	function index() {
		$this->checkLogin();
		#start
		include $this->getView('admin/agent_list');
	}

	# sujject postion data ;
	function add() {
			if ($_POST['A']) {
			$u = quickModel('agent', 'ch_');
			unset($_POST['A']['agentid']);
			$agent=$_POST['A'];
			$agent['admin_id']=$_SESSION['uid'];
			//print_r($_POST['A']);

			$id = $u->insert($agent);
			//echo "$id";
			if ($id > 0) {
				showMessage('add  success !');
			} else {
				showMessage('something  is  error  !');
			}
			#redirect(mca('admin', 'index', 'add'), 301);
		}
		templateShow('admin/agent_add');

	}
	//list  agent
	function alist() {

		// $u = quickModel('agent','ch_');
		// $re = $u->select();
		//print_r($re);
		$u = quickModel('agent', 'ch_');
		$page_1= intval($_GET['page']) ? intval($_GET['page']) : 1;

		$ret_1= $u->getOne('select count(*) as dd from {agent}');
		$page_total_1= ceil($ret_1['dd'] / 5);

		$re_1= $u->getAll('select * from {agent}', $page_1, 5);
		

	 include $this->getView('admin/agent_list');
	}
	//chenge userinfo for admin
	function chenge() {
		$u = quickModel('agent','ch_');
		if ($_POST['A']) {
			$re = $u->update($_POST['A'], 'agentid');
			#redirect(mca('admin', 'index', 'index'), 301);
			if ($re) {
				showMessage('chenggong');
			} else {
				showMessage('shibai');
			}
		}
		$ad = $u->selectOne(['agentid' => $_GET['agentid']]);

		include $this->getView('admin/agent_add');

	}
}
?>