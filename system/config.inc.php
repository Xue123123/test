<?php
#defind the chareset
define('CHARSET', 'utf8');
#rewrite the url
define('_REWRITE_', false);
/* 0 not cache , 1 cache ,more faser , skill parse template*/
define('CH_CACHE', 0);
#save session to mysql
define('_SESSION_', 'file');
# error  display
error_reporting(1);
#define  sql debug- shwo sql string
define('_SQL_DEBUG_', 1);
#define  table  prefix
define('_TAB_FIX_', 'ch_');
#**********config for  database*******************/
$_DBCF = array(
	'host' => '127.0.0.1',
	'user' => 'changhuizichan',
	'passwd' => '123456',
	'dbname' => 'changhuizichan',
);
/* url rule*/
$_URL_CFS = array(
	'novel_list' => 'index.php?c=novel&a=tlist&cid={cid}&page={page} -> novel/list-{cid}-{page}.html',
	'novel_show_index' => 'index.php?c=novel&a=show&id={id} -> novel/show-{id}.html',
	'novel_show_list' => 'index.php?c=novel&a=show&list=1&id={id}&page={page} -> novel/slist-{id}-{page}.html',
	'novel_show_content' => 'index.php?c=novel&a=show&id={id}&show={show}&cpage={cpage} -> novel/show-{id}-{show}-{cpage}.html',
	'novel_recommend' => 'index.php?c=novel&a=recommend&type={type}&page={page} -> novel/recommend-{type}-{page}.html',
	'auth_index' => 'index.php?c=auth&a=index&uid={uid} -> auth/index-{uid}.html',
	'auth_list' => 'index.php?c=auth&a=tlist&uid={uid}&type={type}&page={page} -> auth/list-{uid}-{type}-{page}.html',
	'short_show' => 'index.php?c=short&a=show&id={id}&cpage={cpage} -> short/show-{id}-{cpage}.html',
	'short_list' => 'index.php?c=short&a=tlist&type={type}&cid={cid}&page={page} -> short/list-{type}-{cid}-{page}.html',
	'search' => 'index.php?c=index&a=search&page={page} -> search-{page}.html',
	'php_js' => 'index.php?c=js&a={a}&id={$id} -> js/{a}-{id}.html',
	'index' => 'index.php -> index.html',
);
/* explan url cf */
foreach ($_URL_CFS as $k => $v) {
	$r = explode(' -> ', $v);
	$_URL_CF[$k] = $r[0];
	$_URL_REWRITE[$k] = $r[1];
}