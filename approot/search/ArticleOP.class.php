<?php
//讯搜公共类 常用操作封装
/* xs seach functions*/
include_once  dirname(__FILE__).'/sdk/php/lib/XS.php' ;
class ArticleOP
{
	var  $index ;   //yinqing
	var  $doc ; 
	var  $result ;  //result of search
	var  $XS ; 
	var  $sum;
	// chushihua
	function __construct( $P='hbrcnews' )
	{
		$this->XS = new  XS( $P ); 
		$this->doc  = new XSDocument;
		$this->index = $this->XS->index ;
		$this->search = $this->XS->search ;
		$this->sum     = $this->search->getDbTotal();
	}
	// page shi 1
	function buildDB()
	{                               
		$doc = array('pid'=>'111','subject'=>'www','message'=>'QQQQQQQQ','chrono'=>time());
		$this->doc->setFields($doc);
		$this->index->add($this->doc);
	 }
	 /* get the data for key in index */
	function search($key,$page=1,$num=20)
	{
		$i=0; $R= array( );
		$search = $this->search ;
		if( $key )  $search->setQuery( $key );
		#$search->setFuzzy(   );		
		$search->setLimit($num, ($page-1)*$num);
		$search->setSort('chrono');
		$docs = $search->search( );
		if( $docs ) {
			foreach ($docs as $doc) {
				foreach ($doc as $k => $v) {	$R[$i][$k] = stripslashes( $v ) ;	}
				$i++ ;
			}
		}
		$this->result  = $R? $R: array() ; 
		return  $search->getLastCount() ;
	}
	//update data include inseart
	function  update( $id , $title,$cont)
	{
		$data = array(
		    'pid' => $id, // 此字段为主键，是进行文档替换的唯一标识
		    'subject' => $title,
		    'message' => $cont,
		    'chrono' => time()
		);
		// 创建文档对象
		$this->doc->setFields($data);
		// 更新到索引数据库中
		$this->index->update($this->doc);
	}
	function add( $id , $title,$cont )
	{
		$data = array(
		    'pid' => $id, // 此字段为主键，是进行文档替换的唯一标识
		    'subject' => $title,
		    'message' => $cont,
		    'chrono' => time()
		);
		// 创建文档对象
		$this->doc->setFields($data);
		// 更新到索引数据库中
		$this->index->add($this->doc);
	}
	function  hotSearch($num=6)
	{
		$G= array();
		# public array getHotQuery(int $limit=6, string $type='total')
		$hot = $this->search->getHotQuery($num+15);
		# $hot=[`qiche`=>100]
		$II = 0 ;
		foreach ($hot as $key => $value) {
			if( $this->search($key,1,1) )
			{
			       if( $G[ $this->result[0]['pid'] ]  == false )  
			       {   $II++ ;  $G[ $this->result[0]['pid'] ] = $this->result[0] ;  }
			}
			if( $II == $num )  break ;
		}
		#$G = array_unique( $G ) ;
		return $G ;
	}
	/*$key is keywords subjetct is filed*/
	function delidx ($key , $subject ='')
	{
		if( $subject )
		 	$this->index->del($key , 'subject') ;
		 else
		 	$this->index->del( $key ) ;

		$this->index->flushindex() ;
	}
}
