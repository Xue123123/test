<?php
//供其他站点查询用数据服务
# xsearch :  enginer 
require 'ArticleOP.class.php';

if( $_GET[sccode]  != 'wbw4u8wu4w5w8os9' )  die("") ; 
$key    =    $_GET['key'] ;
$xs  = new  ArticleOP('hbrcnews') ;
#$xs->search->addQueryString( $key) ;
$num = $xs->search( "subject:".$key,1 , 5 );

#echo  $xs->search->getQuery();
$R =  $xs->result ;
#echo  $xs->index->_query ;

echo  serialize( $R ) ;
?>
