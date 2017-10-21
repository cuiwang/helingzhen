var imgLoad = (function(){
	return function(url, callback, errorCb){
		var img = new Image();

		img.src = url;

		if (img.complete) {
            callback.call(img);
            return;
        };

        img.onload = function () {
        	callback.call(this);
            img = img.onload = img.onerror = null;
        };

        img.onerror = errorCb || function(){};
	}
})();

$(function(){
	var lotteryEnd = false,
		ua = navigator.userAgent,
		curObj = $('#swipe li').eq(config.swipeCur),
		curSrc = getSrc(curObj.find('div')),
		audioPlayer = new Player($('#audioBtn'));

	// 解决ios safari 不自动播放的问题
    $(document).one('touchstart', function(e){
    	if(e.target.id == 'audioBtn') return;
    	if(config.coverUrl){
    		if(!/ipad/i.test(ua)){ //iPad下自动播放会导致"未知错误"等提示的出现
    			audioPlayer._audio.play();
				audioPlayer._audio.pause();
    		}
    	}else{
    		audioPlayer._play();
    	}
    });

	// 判断是否需要立刻 模糊初始化
	if(curObj.data('role') == 'blur'){
		imgLoad(curSrc, function(){
			curObj.stackBlur({img: this, radius: 10, callback: function(){
				curObj.data('role', '');
				initLottery();
			}});
		}, initLottery);
	}else{
		imgLoad(curSrc, initLottery, initLottery);
	}
	

	function getSrc(obj){
		return obj.css('background-image').replace(/^url\(|\)$/g, '');
	}
	function loadImg(){
		$('#swipe li').each(function(i){
			if(i == config.swipeCur) return;

			var src = getSrc($(this).find('div')),
				img = new Image(),
				_this = this;

			img.src = src;

			if($(this).data('role') == 'blur'){
				img.onload = function(){
					$(_this).stackBlur({img: img, radius: 10, callback: function(){
						$(_this).data('role', '');
					}});
				}				
			}
		});
	}

	function initLottery(){
		if(config.coverUrl){
			imgLoad(config.coverUrl, function(){
				$('#lottery').lottery({
					coverType: 'image',
		            cover: config.coverUrl,
		            width: 640,
		            height: $(window).height(),
		            //cbdelay: 300,
		            callback: function(percent){
		            	if(percent > 50 && !lotteryEnd){
		            		lotteryEnd = true;
		            		$(this).parent().removeClass('show').on('webkitTransitionEnd', function(){
		            			initSwipe();

			            		$(this).hide();
		            		});
		            	}
		            },
		            success: function(){
		            	setTimeout(loadImg, 500);
		            	$(this.conNode).addClass('show').on('webkitTransitionEnd', function(){
		            		$('#swipe li').eq(config.swipeCur).show();
		            	});
		            }
				});
			}, failLottery);
		}else{
			failLottery();
		}
	}

	function failLottery(){
		loadImg();
		initSwipe();
		$('#lottery').hide();
	}

	function initSwipe(){
		var isInitSwipe = $('#swipe li').length > 1;

		if(isInitSwipe){
			$('#swipe').swipe({
				cur: config.swipeCur,
				dir: config.swipeDir,
				success: function(){
					$(this).find('li').eq(config.swipeCur).removeAttr('style');
					// $('.f-hide').removeClass('f-hide');

					initPage(isInitSwipe);
				}
			});
		}else{
			$('#swipe li').eq(0).show();
			initPage(isInitSwipe);
		}
	}

	function initPage(isInitSwipe){
		// $('#audioBtn').player(true);

		if(config.coverUrl){
			audioPlayer._audio.play();
		}else{
			audioPlayer.isPlay = false;
			$(audioPlayer.el).removeClass('on');
		}

		$('#musicWrap').removeClass('f-hide');
		if(isInitSwipe) $('#arrow' + (config.swipeDir == 'vertical' ? 'V' : 'H' )).removeClass('f-hide').children().addClass('move');

		$('[data-role="video"]').each(function(){
			new Media(this, function(){
				if(audioPlayer.isPlay) audioPlayer._play();
			} ,function(){
				audioPlayer._play();
			});
		});
	}

	//下载
	var _downloadMask = $('#downloadMask');
	$('.links').each(function(){
		if(/itunes.apple.com/.test(this.href) && /(ipod|iphone|ipad).*micromessenger/i.test(ua)){
			$(this).on('click', function(){
				_downloadMask.show();
				return false;
			});

			_downloadMask.on('click', function(){
				$(this).hide();
			});
		}
	});
});