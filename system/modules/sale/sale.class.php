<?php
/*baseclass for content*/
class sale {
	var $db;
	var $model;
	function __construct() {
		$this->db = db::connect();
	}

	/* parse template and display  */
	function getView($tmp_name) {
		$tmp_run_file = template($tmp_name);
		//var_dump($tmp_run_file );exit;
		return $tmp_run_file;
	}

	function loadModel($name) {
		if (is_file(MODEL_PATH . '/' . $name . '_model.class.php')) {
			router::load_model($name);
			$this->model = new $name();
		} else {
			$this->model = new model($name);
		}

	}

	#check logiin
	function checkLogin() {
		if ($_SESSION['role'] != 'sale') {
			redirect(mca('sale', 'index', 'login'), 301);
		}

	}

	function __call($a, $e) {
		#redirect( "/" , 301 ) ;
		echo $a, $e;
	}
}