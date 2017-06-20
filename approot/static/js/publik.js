//设为首页
function SetHome(obj,url){
    try{
       obj.style.behavior='url(#default#homepage)';
       obj.setHomePage(url);
    }catch(e){
       if(window.netscape){
          try{
              netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
         }catch(e){
              alert("抱歉，此操作被浏览器拒绝！\n\n请在浏览器地址栏输入'about:config'并回车然后将[signed.applets.codebase_principal_support]设置为'true'");
          }
       }else{
        alert("抱歉，您所使用的浏览器无法完成此操作。\n\n您需要手动将【"+url+"】设置为首页。");
       }
    }
}
//收藏本站 
function AddFavorite(title, url) {
  try {
      window.external.addFavorite(url, title);
  }
catch (e) {
     try {
       window.sidebar.addPanel(title, url, "");
    }
     catch (e) {
         alert("抱歉，您所使用的浏览器无法完成此操作。\n\n加入收藏失败，请使用Ctrl+D进行添加");
    }
  }
}

$(document).ready(function(){ 
   //TAB标签
   $(".tab-menu li a").hover(function(e) { 
	if (e.target == this) { 
		  var tabs = $(this).parent().parent().parent().find("li"); 
		  var panels = $(this).parent().parent().parent().find(".tab-content"); 
		  var index = $.inArray(this, $(this).parent().parent().find("a")); 
		  function way(){
		  if (panels.eq(index)[0]) { 
		  tabs.removeClass("current").eq(index).addClass("current"); 
		  panels.addClass("hide").eq(index).removeClass("hide"); 
		} 
	}
	} 
	timer=setTimeout(way,400);  
	},function(){  
	  clearTimeout(timer);  
	});
	
	//文章排行
	$(".article-ranking").each(function (j) {
	 	$(this).find("i:lt(3)").removeClass("ranking-icon-blue").addClass("ramlomg-icon-orange");
	})
	
	//表格隔行换色
	$(".table-style tr:gt(1):even").css("background","#f6fdff");
	
	//返回顶部
	$(window).bind('scroll', {
    fixedOffsetBottom: parseInt($('#fixed').css('bottom'))
	},function(e) {
		var scrollTop = $(window).scrollTop();
		var referFooter =$('.btm-info-box');
		scrollTop > 100 ? $('#go-top').show() : $('#go-top').hide();
		if (!/msie 6/i.test(navigator.userAgent)) {
			if ($(window).height() - (referFooter.offset().top - $(window).scrollTop()) > e.data.fixedOffsetBottom) {
				$('#fixed').css('bottom', $(window).height() - (referFooter.offset().top - $(window).scrollTop()))
			} else {
				$('#fixed').css('bottom', e.data.fixedOffsetBottom)
			}
		}
	});
	$('#go-top').click(function() {
		$('body,html').scrollTop(0)
	});
	
	//评论回复
	var linkN = $(".replay-link");
	var show = $(".reply");
	$(".replay-link").each(function (i) {
			$(this).click(function () {
				if ($(show[i]).css("display") == "block") {
					$(show[i]).css("display","none");
				} else {
					for (var j = 0; j < linkN.length; j++) {
						$(show[j]).css("display","none");
					}
					$(show[i]).css("display","block");
				}
			});
	});
	
	//个人简介
	$(".personal-about-text").hover(function(e) { 
		var aHeight = $(this).find("p").height();
		$(this).css({"overflow":"visible","height":aHeight+"px","border":"1px solid #EEE"})
	},function(){  
	    $(this).css({"overflow":"hidden","height":"50px","border":"1px solid #FFF"})
	});
	
	//折叠菜单
	var linkN = $(".user-left-menu-title");
	var show = $(".user-left-sub-menu");
	$(".user-left-menu-title").each(function (i) {
			$(this).unbind('click').click(function () {
				if ($(show[i]).css("display") == "block") {
					$(show[i]).addClass("menu-hide").removeClass("menu-show");
					//$(linkN[i]).removeClass("arrLinkActive");
				} else {
					for (var j = 0; j < linkN.length; j++) {
						$(show[j]).addClass("menu-hide").removeClass("menu-show");
						//$(linkN[j]).removeClass("arrLinkActive");
					}
					$(show[i]).removeClass("menu-hide").addClass("menu-show");
					//$(linkN[i]).addClass("arrLinkActive");
				}
			});
	});
	//个人中心左侧高度
	var lh =$(".user-left-menu").height();
	var rh =$(".user-right").height()
	if (lh<rh){
		$(".user-left-menu").css("height",rh+"px");
	} else {
		$(".user-left-menu").css("height",lh+"px");
	}
});  
