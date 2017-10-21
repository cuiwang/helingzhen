// JavaScript Document  
				//导航
				
$(document).ready(function(){
	var bottom=0;
				 
	$("#tank").click(function(){
	if(bottom==0){
		$(".nav_content").animate({ left:'-25%'},200);
		bottom=1;
		return false;
		}
		if(bottom==1){
		$(".nav_content").animate({ left:'-100%'},200);
		bottom=0;
		return false;
		}
	 });
});
