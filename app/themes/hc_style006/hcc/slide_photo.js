
$(document).ready(function(){
	  var slide_w=$(".slide_photo").width();
	  var body_w=$(".body").width();
	  var w=(body_w-slide_w)/2;
	  $(".slide_photo").css("marginLeft",""+w+"px")
	  
	  var h =$(".body").height()-130;
	 var top =( h-$(".slide_photo").height()) /2;
	 $(".slide_photo").css("paddingTop",""+top+"px");
	 $(".slide_photo").css("height",""+h+"px")
	  
	 setInterval(doNext, "5000");  
	  $(".slide_photo span").click(function(event){	
			event.preventDefault();
			var span_type=this.classList;
			if(span_type=="right")
			{
				doNext();
				
			}
			else{
				doPrev();
			}
	  });
});

var doNext= function(li_on,li_next){
	
	var li_on=$(".slide_photo ul li.on");
	if(li_on.length==0){
		var li_first= 	$(".slide_photo ul li:first");
		li_first.addClass("on");
		li_on=li_first;
	}
	
	var li_next=$(li_on).next();
		
	if(li_next.length==0){
		li_next=$(".slide_photo ul li:first");
	}
			
	li_on.animate({'z-index':'1','left':'40px',"right":'-40px'},"slow");
	li_on.animate({'left':'0px',"right":"0"},"slow");
	li_on.animate({'z-index':'-1'},1)
  	li_next.animate({'z-index':'2','left':'-40px',"right":'40px'},"slow");
	li_next.animate({'left':'0px',"right":"0"},"slow");
	li_on.addClass("addtrans");
	li_on.removeClass("remtrans");

	li_next.removeClass("addtrans");
	li_next.addClass("remtrans");
	
	li_on.removeClass("on");	
	li_next.addClass("on");	
	
	var li_next_next=$(li_next).next();				
	if(li_next_next.length==0){
		li_next_next=$(".slide_photo ul li:first");
	}
	li_next_next.animate({'z-index':'1'},1);
	li_next_next.addClass("addtrans");
	li_next_next.removeClass("remtrans");

}

var doPrev =function(li_on,li_prev){
	
	var li_on=$(".slide_photo ul li.on");
	if(li_on.length==0){
		var li_first= 	$(".slide_photo ul li:first");
		li_first.addClass("on");
		li_on=li_first;
	}
	var li_prev =$(li_on).prev();
	if(li_prev.length==0){
		li_prev =	$(".slide_photo ul li:last");
	}
	li_prev.animate({'z-index':'2','left':'40px',"right":'-40px'},"slow");
	li_prev.animate({'left':'0px',"right":"0"},"slow");
  	li_on.animate({'z-index':'1','left':'-40px',"right":'40px'},"slow");
	li_on.animate({'left':'0px',"right":"0"},"slow");
	
	li_on.addClass("addtrans");
	li_on.removeClass("remtrans");

	li_prev.removeClass("addtrans");
	li_prev.addClass("remtrans");
	
	li_on.removeClass("on");	
	li_prev.addClass("on");
	
	var li_prev_prev=$(li_prev).prev();				
	if(li_prev_prev.length==0){
		li_prev_prev=$(".slide_photo ul li:last");
	}
	li_prev_prev.show();
	li_prev_prev.addClass("addtrans");
	li_prev_prev.removeClass("remtrans");

}
