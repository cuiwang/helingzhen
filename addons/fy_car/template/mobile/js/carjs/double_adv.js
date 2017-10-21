// JavaScript Document
lastScrollY=0;
function heartBeat(){ 
var diffY;
if (document.documentElement && document.documentElement.scrollTop)
    diffY = document.documentElement.scrollTop;
else if (document.body)
    diffY = document.body.scrollTop
else
    {/*Netscape stuff*/}
    
percent=.1*(diffY-lastScrollY); 
if(percent>0)percent=Math.ceil(percent); 
else percent=Math.floor(percent);

$(".advshow_float").each(function(){
	display_is=$(this).css("display");
	if (display_is=="block"){
		advshow_top=$(this).position().top;
		$(this).css("top",advshow_top+percent);
	}
});

lastScrollY=lastScrollY+percent; 
}
function window_resize(){
	display_is=false;
	display_left=$("#navBar").offset().left;
	display_width=$("#navBar").width();
	window_width=$(window).width();
	$(".advshow_float").each(function(){
		if ($(this).css("display")=="block") {
			display_is=true;
			advshow_left=0;
			$(this).show();
			this_width=$(this).width()+10;
			this_left=$(this).position().left;
			$(this).hide();
			if (display_left>this_width && this_left<display_width) {//left
				advshow_left=display_left-this_width;
				$(this).css("left",advshow_left);
				$(this).show();
			}
			if (this_left>display_width && (display_left+display_width+this_width)<window_width) {//right
				advshow_left=display_left+display_width+10;
				$(this).css("left",advshow_left);
				$(this).show();
			}
		}
		
 	});
 	return display_is;
}
$(function(){
	display_is=false;
	$(".advshow_float").each(function(){
		$(this).hide();
		advshow_is=$(this).find("script").next().is("a");
		if (advshow_is) {
			display_is=true;
			$(this).show();
			img_width=$(this).find("script").next().find("img").width();
			img_height=$(this).find("script").next().find("img").height();
			$(this).css("width",img_width);
			$(this).css("height",img_height+20);
		}
 	});
 	if (display_is) {
 		window.setInterval("heartBeat()",10);
		window_resize();
		$(window).resize(function(){
	  	window_resize();
		});
	}
});