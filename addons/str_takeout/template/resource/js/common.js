$(function(){
	$('.notice-box').each(function(){
		var left = 0, notice = $(this).find('.js-scroll-notice'), wrap = $(this);
		setInterval(function(){
			left--;
			0 > left + notice.width() && (left = wrap.width());
			notice.css({
				'left': left
			});
		}, 25);
	});
});
