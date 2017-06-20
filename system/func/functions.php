<?php
/****************************
 *  公共函数
 *****************************
/* parse the template */
function template_parse($str) {
	$str = preg_replace("/\{template\s+(.+)\}/", "<?php include template(\\1); ?>", $str);
	$str = preg_replace("/\{include\s+(.+)\}/", "<?php include \\1; ?>", $str);
	$str = preg_replace("/\{php\s+(.+)\}/", "<?php \\1?>", $str);
	$str = preg_replace("/\{if\s+(.+?)\}/", "<?php if(\\1) { ?>", $str);
	$str = preg_replace("/\{else\}/", "<?php } else { ?>", $str);
	$str = preg_replace("/\{elseif\s+(.+?)\}/", "<?php } elseif (\\1) { ?>", $str);
	$str = preg_replace("/\{\/if\}/", "<?php } ?>", $str);
	$str = preg_replace("/\{sqlget:\(([^}]+)\)\}/", "<?php \$datas=sql_tag_get('\\1');?>", $str);
	//for 循环
	$str = preg_replace("/\{for\s+(.+?)\}/", "<?php for(\\1) { ?>", $str);
	$str = preg_replace("/\{\/for\}/", "<?php } ?>", $str);
	//++ --
	$str = preg_replace("/\{\+\+(.+?)\}/", "<?php ++\\1; ?>", $str);
	$str = preg_replace("/\{\-\-(.+?)\}/", "<?php ++\\1; ?>", $str);
	$str = preg_replace("/\{(.+?)\+\+\}/", "<?php \\1++; ?>", $str);
	$str = preg_replace("/\{(.+?)\-\-\}/", "<?php \\1--; ?>", $str);
	$str = preg_replace("/\{loop\s+(\S+)\s+(\S+)\}/", "<?php \$n=1;if(is_array(\\1)) foreach(\\1 AS \\2) { ?>", $str);
	$str = preg_replace("/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}/", "<?php \$n=1; if(is_array(\\1)) foreach(\\1 AS \\2 => \\3) { ?>", $str);
	$str = preg_replace("/\{\/loop\}/", "<?php \$n++;}unset(\$n); ?>", $str);
	//function
	$str = preg_replace("/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $str);
	//
	$str = preg_replace("/\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $str);
	//comment var
	$str = preg_replace("/\{(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff\[\]'\$]*)\}/", "<?php echo \\1;?>", $str);
	//defined var
	$str = preg_replace("/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1;?>", $str);
	return $str;
}
/* include template*/
function template($tmp_name) {
	$file = TPL_PATH . '/' . $tmp_name . '.html';
	$file2 = CH_PATH . '/' . $tmp_name . '.php';
	if (is_file($file2) and CH_CACHE == 1) {
		return $file2;
	}
	if (is_file($file)) {
		$mstr = file_get_contents($file);
	} else {
		die('template is not exists!');
	}
	$pstr = template_parse($mstr);
	chkDir($file2);
	file_put_contents($file2, $pstr);
	return $file2;
}
/*mkdir */
function chkDir($file) {
	$df = dirname($file);
	if (is_dir($df)) {
		return true;
	} else {
		mkdir($df, 0777, true);
	}
}
/*show tmpalte*/
function templateShow($tmp_name) {
	$tempname = template($tmp_name);
	include $tempname;
}
/* dill the url from $get  */
function url($urlParm = array(), $url_name = '') {
	global $_URL_CF, $_URL_REWRITE;
	$nGet = $_GET;
	if (is_array($urlParm)) {
		foreach ($urlParm as $k => $v) {
			$nGet[$k] = $v;
		}
	}

	if ($url_name == "" or $_URL_CF[$url_name] == '') {
		foreach ($nGet as $k => $v) {
			$getParm[] = $k . '=' . $v;
		}

		return WEB_PATH . 'index.php?' . implode('&', $getParm);
	}
	#echo "---". _REWRITE_ ."++++";
	$ourl = _REWRITE_ ? $_URL_REWRITE[$url_name] : $_URL_CF[$url_name];

	foreach ($nGet as $k => $v) {$ourl = str_replace('{' . $k . '}', $v, $ourl);}

	$ourl = preg_replace('/\{\w+\}/', '1', $ourl);
	#echo  $ourl ;
	return WEB_PATH . $ourl;
}
#return mca - base  url ,no parme
function mca($m = '', $c = '', $a = '') {
	$m = $m ? $m : router::$M;
	$c = $c ? $c : router::$C;
	$a = $a ? $a : router::$A;
	return 'index.php?m=' . $m . '&c=' . $c . '&a=' . $a;
}
/*gett  result  from sql string*/
function sql_string_get($sql, $n) {
	$_DB = db::connect();
	if (!preg_match('/limit /i', $sql)) {
		$sql = $sql . " limit $n";
	}
	$_DB->query($sql);
	while ($_DB->fetch_row()) {
		$datas[] = $_DB->row;
	}
	if ($n == 1) {
		return $datas[0];
	} else {
		return $datas;
	}
}
#show sql string ;
function showSql() {
	$_DB = db::connect();
	echo $_DB->SQL;
}

/*function sql array */
function sql_tag_get($name, $wh = array(), $ext = '', $sl = ' * ') {
	$datas = array();
	$_DB = new model();
	$sql = "select  $sl  from `$name`";
	#$sql = preg_replace('/[\'"]/', '', $sql ) ;
	#$ext = preg_replace('/[\'"]/', '', $ext ) ;
	foreach ($wh as $key => $value) {
		$value = str_replace("'", "''", $value);
		$where .= " and `$key`='" . $value . "' ";
	}

	$sql = $sql . " where  1 " . $where . $ext;

	$datas = $_DB->getAll($sql);
	return $datas;
}
/*function sql array */
function sql_tag_getOne($name, $wh = array(), $ext = ' limit 1', $sl = ' * ') {
	$re = sql_tag_get($name, $wh, $ext, $sl);
	return $re[0];
}
/* get count from  sql string  */
function sql_tag_getCount($name, $wh, $ext = '') {
	$re = sql_tag_get($name, $wh, $ext, '  count(*) dd ');
	return $re[0]['dd'];
}
/* get  pre and next id in table*/
function getNearId($table, $wh, $id, $k = 'id') {
	$pre = sql_tag_get($table, $wh, " and `$k` < $id ", " max(`$k`) dd");
	$next = sql_tag_get($table, $wh, " and `$k` > $id ", " min(`$k`) dd");
	$r = array('P' => $pre[0]['dd'], 'N' => $next[0]['dd']);
	if ($r['P'] == 0) {
		$r['P'] = $id;
	}

	if ($r['N'] == 0) {
		$r['N'] = $id;
	}

	return $r;
}

/*filter request*/
function fiter_request() {
	if (get_magic_quotes_gpc() === false) {
		$_GET = array_map('strip_tags', $_GET);
		$_POST = addslashes_deep($_POST);
		$_COOKIE = addslashes_deep($_COOKIE);
	} else {
		return true;
	}
}
#add addslashes recursive
function addslashes_deep($value) {
//史上最经典的递归，一行搞定
	return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
}
/*  substr */
function str_cut($str, $l, $chr = "UTF-8") {
	if ($chr == 'UTF-8') {
		$str = mb_substr($str, 0, $l, $chr);
	} else {
		$str = mb_substr($str, 0, $l, 'GBK');
	}

	return $str;
}
/* no do that */
function nodo($str) {
	return $str;
}
/* get pre or next page */
function getPagePN($page, $total, $num = 10) {
	$mx = ceil($total / $num);
	$page = numRange($page, $mx);
	$pre = $page - 1;
	$next = $page + 1;
	if ($pre < 1) {$pre = 1;}
	if ($next > $mx) {
		$next = $mx;
		$pre = $mx - 1;}
	return array('P' => $pre, 'N' => $next);
}
/************************************/
function redirect($url, $m = 302) {
	if ($m == 301) {
		header('HTTP/1.1 301 Moved Permanently'); //无此句则是302
		header('Location: ' . $url);
	} elseif (strtoupper($m) == 'JS') {
		echo "<script> location.href='$url' ; </script>";
	} else {
		header('Location: ' . $url);
	}

	exit;

}
/* show msg for js or html*/
function showMessage($msg, $method = 'js') {

	if ($msg == "") {
		return false;
	}

	if ($method == 'js') {
		$showMsg = "<script>alert('" . $msg . "'); </script>";
	} elseif ($method == 'html') {
		$showMsg = "<script>$('#msg').val('" . $msg . "'); $('#msg').show();</script>";
	}

	include template('msg');
	exit;
}

/*******************************************
 * 生成select控件的option功能
 ********************************************/
function mk_select_option($Array, $selected = '') {
	foreach ($Array as $a => $b) {
		if ($selected != '' and $selected == $a) {
			echo "<option value='$a'  selected>$b</option>";
		} else {
			echo "<option value='$a' >$b</option>";
		}
	}
}
/*********************************
 *  UTF-8 编码的字符串截断
 **********************************/
function utf8_substr($str, $len) {
	$str = strip_tags($str);
	for ($i = 0; $i < $len; $i++) {
		$temp_str = substr($str, 0, 1);

		if (ORD($temp_str) > 127) {
			$i++;
			if ($i < $len) {
				$new_str[] = substr($str, 0, 3);
				$str = substr($str, 3);
			}
		} else {
			$new_str[] = substr($str, 0, 1);
			$str = substr($str, 1);
		}
	}

	return join($new_str);
}

/**********************************
 * 生成随机数字和字母
 **********************************/
function mk_hash($num = 1) {
	$array = "1Q2W3E4R5T6Y7U8I9O0PLKJHGFDSAZXCVBNM";
	$str = '';
	for ($i = 0; $i < $num; $i++) {
		$str .= $array[mt_rand(0, 35)];
	}
	return $str;
}

/*
 * ◎功能：柱形统计图
 * ◎参数：$statName 统计图的名称
 *        $labelAry 统计项目标签数组
 *        $dataAry  统计项目数据数组
 *        $direct   统计图中柱形的方向，H为横向，V为纵向
 * ◎返回：HTML代码
 * ◎By Longware
 */
function rectStat($statName, $labelAry, $dataAry, $direct = "H") {
	$idx = 0;
	$lenAry = array();
	$sum = array_sum($dataAry);

	$strHTML = "<table width='" . (($direct == "H") ? "500px" : "98%") . "' border='0' cellspacing='1' cellpadding='1' bgcolor='#CCCCCC' align='center'>\n<tr><td bgcolor='#FFFFFF'>\n";
	$strHTML .= "<table width='100%' border='0' cellspacing='2' cellpadding='2'>\n";

	if ($direct == "H") //横向柱形统计图
	{
		$strHTML .= "<tr><td colspan='2' align='center'><b>" . $statName . "</b><hr/></td></tr>\n";

		while (list($key, $val) = each($dataAry)) {
			$strHTML .= "<tr><td width='16%' align='right'>" . $labelAry[$idx] . "</td><td width='84%'><img src='/img/h_line2.gif' border=0 height='7' width='" . (($val / $sum) * 400) . "'>&nbsp;" . $dataAry[$idx] . "</td></tr>\n";
			$idx++;
		}
	} elseif ($direct == "V") //纵向柱形统计图
	{
		$dataHTML = "";
		$labelHTML = "";

		while (list($key, $val) = each($dataAry)) {
			$dataHTML .= "<td>" . $dataAry[$idx] . "<br><img src='/img/v_line2.gif' border=0 width='9' height='" . (($val / $sum) * 400) . "'></td>\n";
			$labelHTML .= "<td>" . $labelAry[$idx] . "</td>\n";
			$idx++;
		}

		$headHTML = "<tr align='center'><td colspan='" . $idx . "'><b>" . $statName . "</b><hr/></td></tr>\n<tr align='center' valign='bottom'>\n";
		$bodyHTML = "</tr>\n<tr align='center'>\n";
		$footHTML = "</tr>\n";

		$strHTML .= $headHTML . $dataHTML . $bodyHTML . $labelHTML . $footHTML;
	}

	$strHTML .= "</table>\n";
	$strHTML .= "</td></tr></table>\n";

	return $strHTML;
}

#get 2 array from 3
function getArray1f2($array, $key, $key2) {
	foreach ($array as $v) {
		$r[$v[$key]] = $v[$key2];
	}
	return $r;
}
# get  ip  address
function ip_address() {
	if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
		$ip_address = $_SERVER["HTTP_CLIENT_IP"];
	} else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		$ip_address = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
	} else if (!empty($_SERVER["REMOTE_ADDR"])) {
		$ip_address = $_SERVER["REMOTE_ADDR"];
	} else {
		$ip_address = '';
	}
	return $ip_address;
}
#  export for  excele style
#  $filename for filename
# $title  for title , $data is a  array ;
function exportData($filename, $title, $data) {
	header("Content-type: application/vnd.ms-excel;charset=gb2312");
	header("Content-disposition: attachment; filename=" . $filename . ".xls");
	echo '<html>';
	echo '<head><meta http-equiv="Content-Type" content="text/html; charset=gb2312" /><style type="text/css">body{font-family:"宋体";} tr{height:40px;} #blue{background-color:#33CCCC;} td{text-align:center}</sytle></head>';
	echo '<body>';
	if (is_array($title)) {
		echo '<table border="1">';
		echo '<tr>';
		foreach ($title as $key => $value) {
			echo '<th>' . $value . '</th>';
		}
		echo '</tr>';
	}
	if (is_array($data)) {
		$i = 0;
		foreach ($data as $key => $value) {
			$i++;
			if ($i % 2 == 0) {
				echo '<tr>';
			} else {
				echo '<tr id="blue">';
			}
			foreach ($value as $_key => $_value) {
				echo '<td>' . $_value . '</td>';
			}
			echo "</tr>";
		}
	}
	echo '</table>';
	echo '</body>';
	echo '</html>';
}

#简单订单hao生成器
function getOrderStr($len = 24, $str1 = '', $str2 = '', $str3 = '') {
	$string1 = date('YmdHis') . $str1 . $str2 . $str3;
	$l = strlen($string1);
	$m = $len - $l;
	$mx = pow(10, $m) - 1;
	$min = pow(10, $m - 1);
	$rand = mt_rand($min, $mx);
	return $string1 . $rand;
}
/********************************************
 *format for money , $str is  number  any type
 ********************************************/
function fmtmoney($str) {
	$str = str_replace(',', '', $str);
	$str = round($str, 2);
	$str = number_format($str, 2, '.', '');
	return $str;
}

/*********************************
 *explan  card_id , $card is card  number
 *********************************/
function expCardId($card) {
	if (strlen($card) != 18) {
		return false;
	}
	$sex = substr($card, 16, 1);
	if ($sex % 2 == 0) {
		$sex = 'W';
	} else {
		$sex = 'M';
	}
	$age = date('Y') - substr($card, 6, 4);
	return array('sex' => $sex, 'age' => $age);
}
/*****************************
 *  $tablename  is table name  not null
 *  $pre   is table  prefix
 ******************************/
function quickModel($tablename, $pre = null) {
	if ($tablename == '') {
		return false;
	}
	return new model($tablename, $pre);
}
/********************************************************
 *  page  function
 * total  number ,page is page number ,num is for one page
 *********************************************************/
function showPages($total, $page, $num = 20) {
	echo '<link rel="stylesheet" type="text/css" href="' . WEB_PATH . 'bootstrap/css/bootstrap.min.css">';
	echo '<script type="text/javascript" language="javascript" src="' . WEB_PATH . 'js/bootstrapPager.js"></script>';
	echo '
		<script>
			document.write(Pager({
			    totalCount:' . $total . ', 		//总条数为150
			    pageSize:' . $num . ',    			//每页显示6条内容，默认10
			    buttonSize:5,   		//显示6个按钮，默认10
			    pageParam:"page",   		//页码的参数名为p，默认为page
			    className:"pagination", //分页的样式
			    prevButton:"上一页",     //上一页按钮
			    nextButton:"下一页",     //下一页按钮
			    firstButton:"首页",      //第一页按钮
			    lastButton:"末页",       //最后一页按钮
			}));
		</script>';
	#echo "<script></script>";
}