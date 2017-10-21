var nav97 = {
	init: function(id){
		this.el = $(id);

		var _this = this,
			_item = _this.el.find('li'),
			length = _item.length,
			defaultSize = 4,
			bodyW = $(document).width(),
			itemW = bodyW / (length > defaultSize ? defaultSize : length);
			
		this.el.find('ul').width(itemW * length);
		_item.width(itemW);

		if(length > defaultSize){
			_this.scroll();
		}

		// $('body').append('<div style="height:'+ this.el.height() +'px"></div>');

		_this.el.find('li').each(function(){
			var _subNav = $(this).find('.sub_nav'),
				_mainNav = $(this).find('.main_nav');

			if($(this).find('.sub_nav a').length){
				$(this).find('.main_nav p').append('<span class="ico_has_sub"></span>')
					.parent().on('click', _this.showNav);
			}
		});
	},

	// 展开二级导航
	showNav: function(){
		var _li = $(this).parents('li'),
			_subNav = _li.find('.sub_nav');

		if(_li.hasClass('on')){
			_li.removeClass('on');
			_subNav.css('top', 0);
		}else{
			_li.addClass('on').siblings().removeClass('on').find('.sub_nav').css('top', 0);
			_subNav.css('top', -_subNav.height());
		}
		
		return false;
	},

	//滑动
	scroll: function(){
		new iScroll(this.el[0].id, {hScrollbar: false, vScrollbar: false});
	}
}

$(function(){
	nav97.init('#nav97');
});