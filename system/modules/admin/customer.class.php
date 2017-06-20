<?php
/**
 *   user class
 */
router::load_module_class('admin', 'admin');

class customer extends admin {
	function __construct() {
		parent::__construct();
		$this->checkLogin();
	}
# login index
	function index() {
		//$this->checkLogin();

		include $this->getView('admin/customer_list');
	}

	# sujject postion data ;
	function add() {

		if ($_POST['A']) {
			$u = quickModel('customer', 'ch_');
			unset($_POST['A']['customerid']);
			$id = $u->insert($_POST['A']);
			if ($id > 0) {
				showMessage('add  success !');
			} else {
				showMessage('something  is  error  !');
			}
		}
		templateShow('admin/customer_add');
	}

	//list  customer
	function alist() {

		$u = quickModel('customer', 'ch_');
		$page = intval($_GET['page']) ? intval($_GET['page']) : 1;
		//print_r($_POST);
		if ($_POST['epage'] > 0) {
			$page = 1;
		}
        
       if($_POST['customername']!=''||$_POST['phone']!=''){
             $where=" where  1=1";
       
			if (isset($_POST['customername'])&&$_POST['customername']!='') {
				$where.=' and customername like "' . $_POST['customername'] . '%"  ';				    
			}
	        if (isset($_POST['phone'])&&$_POST['phone']!='') {
				$where.=' and phone ="'.$_POST['phone'].'" ';
			}
	        		
				$sql = 'select count(*) as dd from {customer}'.$where;
				$ret = $u->getOne($sql);
				$page_total = ceil($ret['dd'] / 5);
				$sql1 = 'select * from {customer}'.$where;
				$re = $u->getAll($sql1, $page, 5);
				
		} else {
			$ret = $u->getOne('select count(*) as dd from {customer}');
			$page_total = ceil($ret['dd'] / 5);
			$re = $u->getAll('select * from {customer}', $page, 5);
		}
		include $this->getView('admin/customer_list');
	}
	//chenge userinfo for customer
	function chenge() {
		$u = quickModel('customer', 'ch_');
		if ($_POST['A']) {
			$re = $u->update($_POST['A'], 'customerid');
			#redirect(mca('admin', 'index', 'index'), 301);
			if ($re) {
				showMessage('chenggong');
			} else {
				showMessage('shibai');
			}
		}
		$ad = $u->selectOne(['customerid' => $_GET['customerid']]);

		include $this->getView('admin/customer_add');

	}

}
?>