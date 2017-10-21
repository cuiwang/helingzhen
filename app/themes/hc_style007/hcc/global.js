$(function(){
	window.swipe = new Swipe(document.getElementById('imgSwipe'), {
		speed: 500,
		auto: 5000,
		callback: function(){
			var lis = $(this.element).next("ol").children();
			lis.removeClass("on").eq(this.index).addClass("on");
		}
	});
});