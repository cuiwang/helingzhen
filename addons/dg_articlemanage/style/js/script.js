$(document).ready(function(){
	
	//首页分类	

	function ulWidth() {
		var liW = 0;
		$(".homeTags li").each(function() {
			liW += $(".homeTags li").width() + 20;
		})
		return liW;
	}
	$(".homeTags ul").css("width",ulWidth());
	
	$(".homeTags .more").click(function() {
		$(this).closest(".homeTags").toggleClass("active");	
		$(".main,.homeTags .bg").toggleClass("active");	
	    $(".homeTags ul").css("width","");
	})
	$(".homeTags li").click(function() {		
		$(this).addClass("active").siblings().removeClass("active");
		$(".homeTags,.homeTags .bg,.main").removeClass("active");
		var capLi = $(".homeTags li.active").position().left - 100;
		$(".homeTags .list").animate({scrollLeft:capLi},500);
		//alert(capLi)
	});
	$(".homeTags .bg").click(function(){
		$(".homeTags,.homeTags .bg,.main").removeClass("active");
	})
	
})

