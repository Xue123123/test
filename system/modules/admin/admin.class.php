<?php
/*baseclass for content*/
class admin {
	var $db;
	var $model;

	function __construct() {
		$this->db = db::connect();
	}
	/* parse template and display  */
	function getView($tmp_name) {
		$tmp_run_file = template($tmp_name);
		return $tmp_run_file;
	}

	function loadModel($name) {
		$name = $name . '_model';
		if (is_file(MODEL_PATH . '/' . $name . '.class.php')) {
			router::load_model($name);
			$this->model = new $name();
			return $this->model;
		} else {
			$this->model = new model($name);
		}
	}
	#check logiin
	function checkLogin() {
		if ($_SESSION['role'] != 'admin') {
			redirect(mca('admin', 'index', 'login'), 301);
		}
	}
	#
	function __call($a, $e) {
		#	redirect( "/" , 301 ) ;
		echo $a, $e;
	}

	function index() {
		echo 'welcomee admin !';
	}
}