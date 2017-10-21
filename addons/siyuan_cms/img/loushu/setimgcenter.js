;(function($){

	$.fn.setimgcenter=function(settings){
		settings = $.extend({}, $.fn.setimgcenter.defaults, settings);
		$.fn.setimgcenter.settings = settings;
		var _this=$(this);
		$.fn.setimgcenter.settings.img_mark=_this.find(settings.img_mark);
		//allImgs = _this.find(settings.img_mark);


		function _img_center(){
			//allImgs = settings.imageContainer + ' IMG';
			$(window).resize(_resize);
			_resize();
		};

		function _resize(){
			//var winW = $(settings.imageContainer).width();
			//var winH = $(settings.imageContainer).height();
			
			var winW = _this.width();
			var winH = _this.height();

			var imgW = 0, imgH = 0;


			//	Non-proportional resize
			if(!settings.resizeProportionally){
				imgW = winW;
				imgH = winH;
			} else {
				var initW = settings.imageWidth, initH = settings.imageHeight;
				var ratio = initH / initW;
				
				imgW = winW;
				imgH = winW * ratio;
				
				if(imgH < winH){
					imgH = winH;
					imgW = imgH / ratio;
				}
			}
			
			//使背景图主体居中
			var off_left=imgW>winW?(winW-imgW)*.5:0;
			//var off_top=imgH>winH?(winH-imgH):0;
			var off_top=imgH>winH?(winH-imgH)*0.5:0;



			if(!settings.resizeAnimate){
				//settings.img_mark.width(imgW).height(imgH).css({'margin-left':off_left+'px','margin-top':off_top+'px'});
				settings.img_mark.width(imgW).height(imgH).css({'margin-left':off_left+'px','margin-top':'0'});	

			} else {
				//settings.img_mark.animate({width: imgW, height: imgH, marginLeft:off_left+'px',marginTop:off_top+'px'}, 'normal');
				settings.img_mark.animate({width: imgW, height: imgH, marginLeft:off_left+'px',marginTop:'0'}, 'normal');
			};
			
		};

		_img_center();

	};


	/*  Default Settings  */
	$.fn.setimgcenter.defaults = {
		img_mark: 					'img',
		resizeProportionally:       true,
		resizeAnimate:              false,
		images:                     [],
		imageWidth:                 1024,
		imageHeight:                768,
		resize_flash_position:      null,   //新添加的
		img_finish_callback:		 null,   //新添加的 （背景加载完成后的回调函数）	
		nextSlideDelay:             3000,
		slideShowSpeed:             'normal',
		slideShow:                  true,
		isloop: 					false   //图片是否循环切换
	};

})(jQuery);