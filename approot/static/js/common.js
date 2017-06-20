var common = function()
{
	var oc = new Object;
	oc.send_reply = function( e )
	{
		var wid = $(e).attr('data-wid');
		var pid = $(e).attr('data-pid');
		var type = $(e).attr('data-type');
		var content = $(e).val();
		$.ajax({
			"url":"/reply/sendReply",
			"type":"post",
			"data":{"wid":wid, "pid":pid, "type":type, "content":encodeURI(encodeURI(content))},
			"dataType":"json",
			"success":function( r ){
				if( r.code == 10010 )
				{
					alert('发表成功,请等待审核');
					$(e).val('');
				}
				else
				{
					alert( r.msg );
				}
			}
		});
	}

	oc.get_reply = function( e )
	{
		var pid = $(e).attr('data-pid');
		var wid = $(e).attr('data-wid');
		var page = $(e).attr('data-page');
		var type = $(e).attr('data-type');
		//var order = $(e).attr('data-order'); //排序方式    1:时间 2:推荐
		$.ajax({
			"url":"/reply/getReply",
			"type":"post",
			"data":{"pid":pid,"wid":wid,"page":page,"type":type},
			"success":function( r ){
				$(e).append( r );
			}
		});
	}

	oc.get_n_pv_ranking = function( e )
	{
		$.ajax({
			"url":"/common/n_pvRanking",
			"type":"post",
			"success":function( r )
			{
				$(e).append( r );
			}
		});
	}

	oc.get_n_recommend_ranking = function( e )
	{
		$.ajax({
			"url":"/common/n_recommendRanking",
			"type":"post",
			"success":function( r )
			{
				$(e).append( r );
			}
		});
	}

	return oc;
}

oc = common();

$(function(){
	$('#favorite_btn').click(function(){
		//alert(134);
		var id = $(this).attr( 'data-id' );
		var type = $(this).attr( 'data-type' );
		$.ajax({
			"url":"/action/favorite",
			"type":"post",
			"data":{"id":id, "type":type},
			"dataType":"json",
			"success":function( data ){
				alert(data.msg);
			}
		});
	});

	$('#recommend_btn').click(function(){
		var id = $(this).attr( 'data-id' );
		var type = $(this).attr( 'data-type' )
		$.ajax({
			"url":"/action/recommend",
			"type":"post",
			"data":{"id":id, "type":type},
			"dataType":"json",
			"success":function( data ){
				alert( data.msg );
			}
		});
	});

	$('#zan_btn').click(function(){
		var id = $(this).attr( 'data-id' );
		$.ajax({
			"url":"/action/zan",
			"type":"post",
			"data":{"id":id},
			"dataType":"json",
			"success":function( data ){
				alert( data.msg );
			}
		});
	});

	$('#cai_btn').click(function(){
		var id = $(this).attr( 'data-id' );
		$.ajax({
			"url":"/action/cai",
			"type":"post",
			"data":{"id":id},
			"dataType":"json",
			"success":function( data ){
				alert( data.msg );
			}
		});
	});

});
