$(function(){
	var el = $('#nav105'),
		item = el.find('li'),
		length = item.length,
		eventName = ("ontouchend" in document.documentElement ? "touchend" : "click");

	el.find('li').width($(window).width()/ 3);
	el.find('ul').width(item.outerWidth(true) * length);

	new iScroll('nav105', {vScroll: false, hScrollbar: false, vScrollbar: false});

	item.each(function(i){
		if($(this).find('.nav_sub_m a').length){
			$(this).find('>a').append('<span class="ico_more"></span>').on(eventName, function(){
				$(this).next().toggle().parent().siblings().find('.nav_sub').hide();
				$(this).toggleClass('on').parent().siblings().find('.on').removeClass('on');

				return false;
			}).attr('href', 'javascript:void(0);');
			$(this).find('.nav_sub').append('<i class="ico_arrow"></i>');
		}
	});

});