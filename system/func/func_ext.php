<?php
/*******************************
 *  user functons
 ********************************/

/********************************
 * $total datas $num is for one page
 ********************************/
function showPages_old($page, $total, $urlParm = array(), $num = 20) {
	$max = ceil($total / $num);
	$nget = $_GET;
	if (is_array($urlParm)) {
		foreach ($url as $key => $value) {$nget[$key] = $value;}
	}

	$nget[page] = numRange($page - 1, $max);
	$pagef = url($nget);
	$nget[page] = numRange($page + 1, $max);
	$pagen = url($nget);
	$pagesString = file_get_contents(TPL_PATH . '/page.html');
	$pagesString = str_replace('{$pagef}', $pagef, $pagesString);
	$pagesString = str_replace('{$pagen}', $pagen, $pagesString);
	echo $pagesString;
}

// splite content ! as  page
//$num zishu ,
function showContentPages($cont, $cpage, $num = 500, $urlname = "") {
	$re = spliteContent($cont, $cpage, $num);
	$mx = $re['mx'];
	$cpage = numRange($cpage, $mx);
	if ($mx < 2) {
		return false;
	}

	$pre = $nxt = $_GET;
	$pre['cpage'] = $cpage - 1;
	$nxt['cpage'] = $cpage + 1;
	$pre['cpage'] = numRange($pre['cpage'], $mx);
	$nxt['cpage'] = numRange($nxt['cpage'], $mx);
	$url1 = url($pre, $urlname);
	$url2 = url($nxt, $urlname);
	echo '<div class="wenxue_xiaoshuo_con_fanye">';
	echo '<form action="" method="post" target="_self">';
	echo '<input class="wenxue_tj_fanye2" name="cpage" value="' . $cpage . '" type="text" />';
	echo '<div class="wenxue_tj_fanye6">';
	echo '<span class="wenxue_tj_fanye1">/</span>';
	echo $mx;
	echo '</div><input class="wenxue_tj_fanye3"  onclick="this.form.submit(); "  type="button" />';
	if ($cpage == $mx) {
		echo '<a class="wenxue_tj_fanye4" href="' . $url2 . '"></a>';
	} else {
		echo '<a class="wenxue_tj_fanye8" href="' . $url2 . '"></a>';
	}

	if ($cpage > 1) {
		echo '<a class="wenxue_tj_fanye5" href="' . $url1 . '"></a>';
	} else {
		echo '<a class="wenxue_tj_fanye9" href="' . $url1 . '"></a>';
	}

	echo '<p style="clear:both;"></p></div>';
}
// splite content with mbstring
function spliteContent($cont, $page, $num = 500) {
	$cont = strip_tags($cont, '<br>');
	$cont = str_replace('#', ':', $cont);
	$cont = preg_replace('/<\s{0,2}br\s{0,2}\/>/i', '#', $cont);
	$total = mb_strlen($cont, CHARSET);
	$mx = ceil($total / $num);
	$page = numRange($page, $mx);
	$rcont = mb_substr($cont, ($page - 1) * $num, $num, CHARSET);
	$RE['mx'] = $mx;
	$RE['content'] = str_replace('#', '<br/>', $rcont);
	return $RE;
}

// ceil number to  rang
function numRange($n, $e = 10, $s = 1) {
	$n = $n < $s ? $s : $n;
	$n = $n > $e ? $e : $n;
	return $n;
}