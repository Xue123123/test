<?php
define('WEB_PATH', './');
#define  system dir ;
define('ROOT', dirname(__FILE__));
define('SYSDIR', substr(ROOT, 0, strrpos(ROOT, '/')) . '/system');
ob_start();
require SYSDIR . '/base.php';
if ($_GET['m'] == 'imgcode') {
	router::load_lib('checkcode');
	$imgcode = new checkcode();
	$imgcode->doimage();
	$_SESSION['imgcode'] = $imgcode->get_code();
	exit;
}