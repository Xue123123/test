<?php
#define some const var for sys
if (!defined('SYSDIR') or !defined('WEB_PATH')) {
	die("right path !");
}
#--------------------------------------------------------------
define('LETIN', TRUE);
#define  url
define('_URL_', $_SERVER['REQUEST_URI']);
define('_URL_NO_PAGE_', preg_replace('/&page=[\d]+/', '', _URL_));
#deine  js  css  image  dir
define('JS_PATH', WEB_PATH . 'static/js');
define('CSS_PATH', WEB_PATH . 'static/css');
#define  images path
define('IMG_PATH', WEB_PATH . 'static/images');
#define suer class path
define('MODULES_PATH', SYSDIR . '/modules');
#define lib class path
define('LIB_PATH', SYSDIR . '/libs');
# system class
define('CLASS_PATH', SYSDIR . '/class');
#define  sys model class  path
define('MODEL_PATH', SYSDIR . '/model');
#define  cahce path for template
define('CH_PATH', SYSDIR . '/cache/template');
#define tmplate path
define('TPL_PATH', SYSDIR . '/template');
#define  last url
if (isset($_SERVER['HTTP_REFERER'])) {
	define('_LAST_URL_', $_SERVER['HTTP_REFERER']);
}
#----------------------------------------------------------------
#load functions
include SYSDIR . "/func/functions.php";
#include SYSDIR . "/func/func_ext.php";
include SYSDIR . "/config.inc.php";
#-----------------------------------------------------------------
require SYSDIR . '/class/router.class.php';
# load db   tool  class
router::load_class('db');
router::load_class('model');
#save the session
if (__SESSION_ == 'mysql') {
	router::load_lib('session_mysql');
	$__ = new session_mysql();
} else {
	@session_start();
}

#echo $_SERVER['REQUEST_URI'] ;
//load  comment  class and function