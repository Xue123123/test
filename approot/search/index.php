<?php
# xsearch :  enginer 
require 'ArticleOP.class.php';
$page = $_GET[page]>0? $_GET[page]:1 ;
$key    =  trim(  strip_tags($_GET[key]) );
if($key=='')  $key="互联" ;
$xs  = new  ArticleOP('hbrcnews') ;
$num = $xs->search( "subject:".$key,$page,10 );
#echo  $xs->search->getQuery();
$total = $xs->search->count();
$pgs=intval($total /10 ) + 1;
if($page>$pgs) $page=$pgs;
$R =  $xs->result ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  xmlns:wb="http://open.weibo.com/wb">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>博才搜索</title>
<meta name="keywords" content="博才搜索" />
<meta name="Description" content="博才搜索" />
<link rel="stylesheet" type="text/css" href="/staticNews/css/css.css"/>
<link rel="stylesheet" type="text/css" href="/staticNews/css/search.css"/>
<script src="/staticNews/js/jquery-1.9.1.min.js"></script>
</head>
<body>
<div id="top">
	<div class="wrap">
   	  <div class="fl"><span id="www_zzjs_net"></span></div>
        <ul class="site-nav-right">
			<li class="wechat"><a href="" onmouseover="display1()" onmouseout="display2()" class="pr">官方微信
      <img id="b" src="/staticNews/images/js_96.jpg" alt="" title="" style="display:none" /></a></li> 
            <li>
                <div id="loginArea" class="login clear">
                    <div class="uerlogin fl" id="j-login-t">
                    <a href="/index.php?m=member&c=index&a=login">登录</a>
                    </div>
                    <div class="fl sp">|</div>
                    <div class="fl"><a href="/index.php?m=member&c=index&a=register">注册</a></div>
              </div>
                 <!--登录之后显示-->
                 <ul class="logout" id="welcomeArea" style="display:none">
                    <li>您好，<span id="welcomeU">499136248</span></li>
                    <li><a>退出</a></li>
                 </ul>
            </li> 
        </ul>
    </div>
</div>
<script type="text/javascript" >
jQuery(function(){	
 jQuery.post("/ajaxSvr.php",
  {
    F:"chkusr" ,
    city:"Duckburg"
  },
  function(data,status){
    if( data != 'false' ) { jQuery("#welcomeU").html(data)  ;jQuery('#loginArea').hide() ;  jQuery('#welcomeArea').show();}
  });
});
</script>

<div class="seaHead">
<div style="clear:both"></div>
	<div class="wrap">
    	<div class="logo"><img src="/staticNews/images/search_03.gif" alt="" width="188" height="31" border="0" usemap="#Map" />
<map name="Map" id="Map"><area shape="rect" coords="3,3,124,31" href="/index.html" /></map></div>
<form action="/search/" method="get">
        	<input type="text" class="s-inpt"  name="key" value="<?php if($_GET[key] ) echo $key; else echo "请输入关键词"; ?>" />
            <input name="" type="submit" class="s-btn" value="搜索" />
        </form>
    </div>
</div>
<div class="wrap">
	<div class="result">
    	<ul class="resLi">
 <?php
 if($num==0  and  $page==1)
 {
 	echo "<span style=\"line-height:80px;\">  引擎共收录数据 ".$xs->sum." 条 ".
                        "<br/> 没有找到您要的内容,换个关键词试试吧! ! <br/></span>" ;
 }
 
 if( $total>0){
 $ii=0;
 foreach ($R as $doc) {
 $ii++ ;
 $doc[subject] = str_replace($key, "<font color=red>$key</font>", $doc[subject] ) ;
 $doc[pid] = str_replace( '-','/',$doc[pid] ) ;
?>
        	<li>
            	<h4><a href="/content/<?php echo $doc[pid]?>_1.html" target="_blank"><?php echo $doc[subject]?></a></h4>
                <p><?php echo strip_tags($doc[message])?></p>
                <div><font color="#1a7b2e"><?php echo  date("Y-m-d H:i",$doc[chrono])?></font></div>
            </li>
 <?php  } }?>
 
        </ul>
    	<div class="pagebox">
            共 <?php echo $pgs?> 页<span class="pagebox_pre_nolink"><a target="_self"  href="?key=<?php echo $key?>&page=<?php echo ($page-1)?>" >上一页</a></span>
 <?php 
 if ($pgs > 9 and $page>5) {
 	$i=$page-4;
 	$mx=$page+4;
 }else{ $i=1 ; $mx=9;}
 if( $mx>$pgs) $mx=$pgs;
 for(;$i<=$mx;$i++) {
   	if( $i==$page) echo   '<span class="pagebox_num_nonce">'.$i.'</span>' ;
              else 	echo    '<span class="pagebox_num"><a href="?key='.$key.'&page='.$i.'">'.$i.'</a></span>';
  }
  if($ii<10) $page=$page-1;
   ?>
        <span class="pagebox_next"><a href="?key=<?php echo $key?>&page=<?php echo ($page+1)?>">下一页</a></span>
        </div>
    <form action="/search/" method="get">
        	<input  type="text" class="s-inpt"  name="key"  value="" />
            <input name="" type="submit" class="s-btn" value="搜索" />
        </form>
    </div>
    <div class="s-left">
    	<div class="hotSearch">
        	<h4>大家都在搜</h4>
            <ul>
<?php  
$g = $xs->hotSearch(9) ;
foreach ($g as $key => $value) {
    # code...
    $value[pid] = str_replace('-','/',$value[pid] );
?>
            	<li><a  target="_blank" href="/content/<?php echo $value[pid]?>_1.html"><?php echo $value[subject]?></a></li>
<?php } ?>
            </ul>
        </div>
        <div class="s-Ad">
            <!--img src="/staticNews/images/js_14.jpg" width="224" height="224" alt="" / -->
        </div>
    </div>
</div>
<div class="wbf boot clearfix">
	<div class="wrap"><a href="#">关于河北博才网</a> | <a href="#">联系我们</a> | <a href="#">企业</a> | <a href="#">法律声明</a> | <a href="#">在线留言</a> | <a href="account/User_Page.html">我要投稿</a><br />
河北博才人力资源服务有限公司 版权所有 Copyright 2012 www.hbrc.com All rights reserved.</div>
</div>

<script src="/staticNews/js/display.js"></script>
<script type="text/javascript" src="/staticNews/js/time.js"></script>
</body>
</html>
