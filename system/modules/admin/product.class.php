<?php
/**
 *   user class
 */
router::load_module_class('admin', 'admin');

class product extends admin {
	function __construct() {
		parent::__construct();
		$this->checkLogin();
	}
# login index
	function index() {
		//$this->checkLogin();
       
	   include  $this->getView('admin/product_list');
	}

	# sujject postion data ;
	function add() {

		if ($_POST['A']) {
			$u = quickModel('product', 'ch_');
		    unset($_POST['A']['productid']);
		    $agent=$_POST['A'];
			$agent['admin_id']=$_SESSION['uid'];
			$id = $u->insert($agent);
		if ($id > 0) {
				showMessage('add  success !');
			} else {
				showMessage('something  is  error  !');
			}
	    }
       templateShow('admin/product_add');
	}

	//list  product
	function alist() {

		$u = quickModel('product', 'ch_');
		$page = intval($_GET['page']) ? intval($_GET['page']) : 1;

		$ret = $u->getOne('select count(*) as dd from {product}');
		$page_total = ceil($ret['dd'] / 5);

		$re = $u->getAll('select * from {product}', $page, 5);
		

		include $this->getView('admin/product_list');
	}
	//chenge userinfo for product
	function chenge() {
		$u = quickModel('product','ch_');
		if ($_POST['A']) {
			$re = $u->update($_POST['A'], 'productid');
			#redirect(mca('admin', 'index', 'index'), 301);
			if ($re) {
				showMessage('chenggong');
			} else {
				showMessage('shibai');
			}
		}
		$ad = $u->selectOne(['productid' => $_GET['productid']]);

		include $this->getView('admin/product_add');

	}
    # sujject postion data ;
	function ContentAdd() {
		
          
		if ($_POST['B']) {
			$u = quickModel('productdesc', 'ch_');
		   // unset($_POST['A']['pro_id']);
		   $_POST['B']['pro_id']=$_GET['pro_id'];
			//$agent['admin_id']=$_SESSION['uid'];
			//print_r($_POST['B']);
			$id = $u->insert($_POST['B']);
		if ($id > 0) {
				showMessage('add  success !');
			} else {
				showMessage('something  is  error  !');
			}
	    }
       include $this->getView('admin/procontent_add');
	}
	//chenge userinfo for product
	function ContentChenge() {
		
		$u = quickModel('productdesc','ch_');
		if ($_POST['B']) {
			$re = $u->update($_POST['B'], 'pro_id');
			#redirect(mca('admin', 'index', 'index'), 301);
			if ($re) {
				showMessage('chenggong');
			} else {
				showMessage('shibai');
			}
		}
		$ad = $u->selectOne(['pro_id' => $_GET['pro_id']]);

		include $this->getView('admin/procontent_add');

	}



}
?>