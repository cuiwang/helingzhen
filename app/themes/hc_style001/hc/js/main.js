$(function(){
	window.swipe = new Swipe($('#imgSwipe')[0], {
		speed: 500, 
		auto: 5000, 
		callback: function(index){
			$('#swipeNum li').eq(index).addClass("on").siblings().removeClass("on");
		}
	});
});