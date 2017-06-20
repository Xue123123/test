<?php
#router the request#

class router {
	public static $C; //controler
	public static $M; //dir or moduels
	public static $A; //function
	#public static $SESSION;
	function loadApp() {
		//add
		fiter_request();
		self::routeUrl($_SERVER['REQUEST_URI']);
		#c is controler default is index
		self::$C = $_GET['c'] ? $_GET['c'] : 'index';
		#m is modules ,is dir for module
		self::$M = $_GET['m'] ? $_GET['m'] : 'sale';
		#a is action default is index
		self::$A = $_GET['a'] ? $_GET['a'] : 'index';
		#load module and controler
		self::load_module_class(self::$M, self::$C);
		# get instents of controler
		$controler = new self::$C();
		$method = self::$A;
		#call the  right function
		return $controler->$method();
		#call_user_func_array($U, $self::$C);
	}

	/* load sys file ,it is can load any file*/
	function load_sys($filename) {
		include_once SYSDIR . '/' . $filename . '.class.php';
	}
	/* load sys class dir only*/
	function load_class($filename) {
		include_once CLASS_PATH . '/' . $filename . '.class.php';
	}
	/* load model class dir only*/
	function load_model($filename) {
		include_once MODEL_PATH . '/' . $filename . '.class.php';
	}
	/*load user or final class*/
	function load_module_class($dir, $filename) {
		if (!is_dir(MODULES_PATH . '/' . $dir)) {
			die('no module');
		}
		if (!is_file(MODULES_PATH . '/' . $dir . '/' . $filename . '.class.php')) {
			die('no controler');
		}
		include_once MODULES_PATH . '/' . $dir . '/' . $filename . '.class.php';
	}
	/*load user lib*/
	function load_lib($filename) {
		include_once LIB_PATH . '/' . $filename . '.class.php';
	}
	//route the url
	function routeUrl($url) {
		global $_URL_REWRITE, $_URL_CF;
		if (stripos($url, '.php') > 0) {define('_URL_', $url);return true;}
		foreach ($_URL_REWRITE as $k => $v) {
			//change $v to regex
			$v = preg_replace('/\{[a-zA-Z]*\}/', '([a-z0-9]+)', $v);
			$v = str_replace('/', '\/', $v);
			//get the positon from array
			if (preg_match('/' . $v . '/', strtolower($url), $m)) {
				unset($m[0]);
				$str = $_URL_CF[$k];
				foreach ($m as $val) {
					$pm[] = '/\{([a-zA-Z]+)\}/';
				}
				$str = @preg_replace($pm, $m, $str, 1);
				define('_URL_', $str);
				$str = substr($str, strpos($str, '.php?') + 5);
				#echo  $str ;
				foreach (explode('&', $str) as $key => $val) {
					$re = explode('=', $val);
					$_GET[$re[0]] = $re[1];
				}
				return true;
			}
		}
	}
	function __call($m, $a) {
		echo 'wrong url!';
	}
}