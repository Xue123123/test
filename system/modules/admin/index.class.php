<?php
/**
 *   user class
 */
router::load_module_class('admin', 'admin');

class index extends admin {
	function __construct() {
		parent::__construct();
	}
# login index
	function index() {
		$this->checkLogin();
		#print_r($mlist);

		include $this->getView('admin/index');
	}
# login
	function login() {
		if ($_POST) {
			$_P = $_POST;unset($_POST);
			if (strtolower($_SESSION['imgcode']) != strtolower($_P['verify'])) {
				showMessage(" 验证码错误 !");
			} else {
				$u = quickModel('admin', 'ch_');
				$re = $u->selectOne(array('loginname' => $_P['user']));
				if ($re and $re['password'] == $_P['passwd']) {
					$_SESSION['username'] = $_P['user'];
					$_SESSION['role'] = 'admin';
					$_SESSION['uid'] = $re['adminid'];
					redirect(mca('admin', 'index', 'index'), 301);
				} else {
					showMessage("用户或密码不正确 !");
				}
			}
		}
		templateShow('admin/login');
	}
	//add a adminstror
	function add() {

		if ($_POST['A']) {
			$u = quickModel('admin', 'ch_');
			unset($_POST['A']['adminid']);
			$id = $u->insert($_POST['A']);
			if ($id > 0) {
				showMessage('add  success !');
			} else {
				showMessage('something  is  error  !');
			}
			#redirect(mca('admin', 'index', 'add'), 301);
		}
		templateShow('admin/admin_add');
	}
	//edit the admin
	function edit() {
		if ($_POST['A']) {
			redirect(mca('admin', 'index', 'index'), 301);
		}
	}
	//list  admin
	function alist() {

		$u = quickModel('admin', 'ch_');
		$page = intval($_GET['page']) ? intval($_GET['page']) : 1;

		$ret = $u->getOne('select count(*) as dd from {admin}');
		$page_total = ceil($ret['dd'] / 12);

		$re = $u->getAll('select * from {admin}', $page, 12);
		//print_r($re);
		include $this->getView('admin/admin_list');
	}
	//chenge userinfo for admin
	function chenge() {
		$u = quickModel('admin');
		$id = $_GET['adminid'];
		if ($id == '') {
			showMessage('Error!');
		}
		if ($_POST['A']) {
			$re = $u->update($_POST['A'], 'adminid');
			#redirect(mca('admin', 'index', 'index'), 301);
			if ($re) {
				showMessage('chenggong');
			} else {
				showMessage('shibai');
			}
		}
		$ad = $u->selectOne(['adminid' => $id]);

		include $this->getView('admin/admin_add');

	}
	function welcom() {
		templateShow('admin/welcom');
	}
# logout
	function logout() {
		session_destroy();
		redirect(mca('admin', 'index', 'login'), 301);
	}
}