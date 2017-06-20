<?php
/**
 *   user class
 */
router::load_module_class('sale', 'sale');

class index extends sale {
	function __construct() {
		parent::__construct();
		#$this->checkLogin();
	}
# login index
	function index() {
		$this->checkLogin();

		include $this->getView('sale/index');
	}

	# login
	function login() {
		if ($_POST) {
			//print_r($_POST);exit;
			$_P = $_POST;unset($_POST);

			$u = quickModel('salesman', 'ch_');
			$re = $u->selectOne(array('loginname' => $_P['user']));
			if ($re and $re['loginpassword'] == $_P['passwd']) {
				$_SESSION['username'] = $_P['user'];
				$_SESSION['role'] = 'sale';
				$_SESSION['uid'] = $re['salesmanid'];
				redirect(mca('sale', 'index', 'index'), 301);
			} else {
				showMessage("用户或密码不正确 !");
			}

		}
		templateShow('sale/login');
	}

}