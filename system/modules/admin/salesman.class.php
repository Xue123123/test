<?php
/**
 *   user class
 */
router::load_module_class('admin', 'admin');

class salesman extends admin {
	function __construct() {
		parent::__construct();
		$this->checkLogin();
	}
# login index
	function index() {
		$this->checkLogin();
		#start
		include $this->getView('admin/salesman_list');
	}

	# sujject postion data ;
	function add() {
			if ($_POST['A']) {
			$u = quickModel('salesman', 'ch_');
			unset($_POST['A']['salesmanid']);
			$id = $u->insert($_POST['A']);
			if ($id > 0) {
				showMessage('add  success !');
			} else {
				showMessage('something  is  error  !');
			}
			#redirect(mca('admin', 'index', 'add'), 301);
		}
		templateShow('admin/salesman_add');

	}
	//list  salesman
	function alist() {

		$u = quickModel('salesman', 'ch_');
		$page = intval($_GET['page']) ? intval($_GET['page']) : 1;

		$ret = $u->getOne('select count(*) as dd from {salesman}');
		$page_total = ceil($ret['dd'] / 5);

		$re = $u->getAll('select * from {salesman}', $page, 5);
		

	 include $this->getView('admin/salesman_list');
	}
	//chenge userinfo for admin
	function chenge() {
		$u = quickModel('salesman','ch_');
		if ($_POST['A']) {
			$re = $u->update($_POST['A'], 'salesmanid');
			#redirect(mca('admin', 'index', 'index'), 301);
			if ($re) {
				showMessage('chenggong');
			} else {
				showMessage('shibai');
			}
		}
		$ad = $u->selectOne(['salesmanid' => $_GET['salesmanid']]);

		include $this->getView('admin/salesman_add');

	}
}
?>