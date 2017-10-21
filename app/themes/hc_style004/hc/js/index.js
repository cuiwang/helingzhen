$(function(){

	var _imgWrap = $('#imgSwipe'),
		_wrap =_imgWrap.find('li:eq(0) span'),
		_box = _wrap.parent(),
		wrapW = _wrap.width(),
		wrapH = _wrap.height(),
		maxW  = _box.outerWidth(true),
		maxH = _box.outerHeight(true),
		getCss = function(w, h) {
			var rh = h * maxW / w,
                obj = {};
            if(w < maxW || h < maxH){
                obj[rh > maxH ? 'width' : 'height'] = '100%';
            }
            return obj;
		}

	if(2 == _imgWrap.find("li").length){
		var ul = _imgWrap.find("ul")[0];
		ul.innerHTML = new Array(3).join(ul.innerHTML.toString());
	}
	_imgWrap.find('img').each(function(i){
		if(i == 1){
			window.swipe = new Swipe(document.getElementById('imgSwipe'), {
				speed: 500,
				auto: 5000,
				callback: function(index){
					//解决华为 4.0.3 下canvas过大盖住当前图片
					_imgWrap.find('li').eq(index).css('z-index', 2).siblings().css('z-index', 1);
				}
			});
			$('.swipe_btn').show();
		}

		imgReady(this.src, $.noop, this, function(){
			var canvas = $('<canvas>').prependTo($(this).parents('li'));

			$(this).css('margin-left', (wrapW - wrapH * this.width / this.height) / 2);
			//canvas.css('left', (_imgWrap.width() - this.naturalWidth ) / 2 );

			// 图片就绪才能初始化canvas blur
			stackBlurImage(this, canvas[0], 15, false, getCss(this.naturalWidth, this.naturalHeight));
		});
	});

});