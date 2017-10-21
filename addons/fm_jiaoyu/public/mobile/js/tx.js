$(function () {
	$("#saoma").click(function () {
          
		total =document.documentElement.clientHeight;
		colHeight =(total-parseInt($("#saoma_info01").css("height").replace("px","")))/2;
		//$("#saoma_info01").css("margin-top",colHeight+"px");
		
		$("#bg").css("display", "block");
		$("#saoma_info01").css("display", "block");
		//因为页面很长，有纵向滚动条，先让页面滚动到最顶端，然后禁止滑动事件，这样可以使遮罩层锁住整个屏幕
		//$('html,body').animate({scrollTop: '0px'}, 0);
    	$('#bg').bind("touchmove",function(e){  
            e.preventDefault && e.preventDefault();
            e.returnValue=false;
            e.stopPropagation && e.stopPropagation();
            return false;
        });  
        $('#saoma_info01').bind("touchmove",function(e){  
            e.preventDefault && e.preventDefault();
            e.returnValue=false;
            e.stopPropagation && e.stopPropagation();
            return false; 
        });  
		
	})
	$("#span_cha").click(function () {
		 window.ontouchmove=function(e){
            e.preventDefault && e.preventDefault();
            e.returnValue=true;
            e.stopPropagation && e.stopPropagation();
            return true;
        }
	    $("#bg").css("display", "none");
		$(".saoma_info").css("display", "none");
		
	})

})