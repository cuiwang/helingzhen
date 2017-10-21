// JavaScript Document
function cTab(tab_controler,tab_con){
	this.tab_controler = tab_controler;
	this.tab_con = tab_con;
	var tabs = $(tab_controler).find("a");
	var panels = $(tab_con).children("div");
	$(tab_con).children("div").css("display","none");
	$(tab_con).children("div:first").css("display","block");
	$(tabs).bind("click", function(){
		var index = $.inArray(this,tabs);
		tabs.removeClass("tab-cur")
		.eq(index).addClass("tab-cur");
		panels.css("display","none")
		.eq(index).css("display","block");
		return false;
	});
};
//点击清空搜索框
$(".clear-txt").click(function(){
   $(this).siblings("input").val("");
});
//弹出切换class
$(".apply-pup").click(function(){
	$(this).toggleClass("apply-pup-hover");
	$("#apply-pup-icon").slideToggle(350);	
	return false;
});
//返回顶部
//页面滚动显示下载按钮
$(window).scroll(function(){
	var scroolh = $(window).scrollTop();	
	if(scroolh >= 400){
	     $(".return-top").addClass("return-topTo");
	}
	else{		
	     $(".return-top").removeClass("return-topTo");
	};	
	if(scroolh > 300){
		$(".float-download").fadeIn(150);
	}
	else{
		$(".float-download").fadeOut(150);
	};
});

$(".return-top").click(function(){
	  $('body,html').animate({scrollTop:0},220); 	 
	  return false;
});

//下载按钮点击变色
$(".downBtn").click(function(){
    $(this).addClass("hot-download2");
});

window.addEventListener("DOMContentLoaded", function () {
	$("#toMenu").click(function(){
		$(".info-nr-phone").toggleClass("info-nr-phone2");
		$(".menu_01").toggleClass("to_01");
		$(".menu_02").toggleClass("to_02");
		$(".menu_03").toggleClass("to_03");
		$(".menu_04").toggleClass("to_04");
		$(".menu_05").toggleClass("to_05");
	});
}, false);



  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  