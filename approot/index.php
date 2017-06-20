<?php
#$t1 = microtime(1);
define('WEB_PATH', '/');
#define  system dir ;

define('ROOT', dirname(__FILE__));
define('SYSDIR', substr(ROOT, 0, strrpos(ROOT, '/')) . '/system');

require SYSDIR . '/base.php';
try
{
	$mysql = new model();
	#ob_start();
	router::loadApp();
	#$_OPCC = ob_get_clean();
	#@file_put_contents($OPNAME,$_OPCC);
	#echo $_OPCC ;
} catch (Exception $e) {
	#	echo ' it is nothing , good luck ! ' ;
	echo $e->getMessage();
}
#$t2 = microtime(1) ;
#echo  '<br>' . ( ( $t2 - $t1 )*1000 ). " ms" ;
?>