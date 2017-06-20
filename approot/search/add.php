<?php
# xsearch :  enginer 
require 'ArticleOP.class.php';
	$xs  = new  ArticleOP('demo') ;
$xs->update( mt_rand(11,1111),'请检查输入字词有无错误',"heloo!"  ) ;
?>
