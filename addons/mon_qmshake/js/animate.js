(function($) {
	/*摇奖池*/
	var ShakePrize = function(options) {
		this.$btn = options.$btn;
		this.$zoomIn = options.$zoomIn;
		this.$dotDefault = options.$dotDefault;
		this.$dotHighlight = options.$dotHighlight;
		this.$gesture = options.$gesture;
		this.$doorL = options.$doorL;
		this.$doorR = options.$doorR;
		this.$ruleBtn = options.$ruleBtn;
		this.$linkItem = options.$linkItem;
		this.$winPrize = options.$winPrize;
		this.$successDialog = options.$successDialog;
		this.$endErrorDialog = options.$endErrorDialog;
		this.$errorDialog = options.$errorDialog;
		this.$dialogCove = options.$dialogCove;
		this.$btnReset = options.$btnReset;
		this.audios = options.audios;
		this.isShaked = false;

		this.INITDEG = 15;
		this.DEGINTERVAL = null;
		this.SHAKEEVENT = null;

		this.api = options.api;

		var _this = this;
		this.$btnReset.click(function() {
			_this.isShaked = false;
			_this.start();
			_this.openDoor();
			_this.$dotDefault.show();
			_this.$dotHighlight.hide();
			_this.alertError(false);
			//_this.api.setBtnText(_this.free);
		});
		
	};

	ShakePrize.prototype = {
		start: function() {
			if(!this.free) {
				return;
			}


			var _this = this;

			_this.startGesture(); // 开始手势动画

			_this.createShake(); // 初始化摇一摇功能

			// 绑定点击摇一摇功能

			// _this.$btn.on('touchend', function() {
			// 	alert("卧槽");
			// 	return;
			// 	_this.unbindAnimate(); // 取消动画
            //
			// 	_this.shakeCallBack();
			// });

			_this.$btn.click(function () {

				_this.unbindAnimate(); // 取消动画

				_this.shakeCallBack();
			});
		},
		createShake: function() {
			var _this = this;


			_this.SHAKEEVENT = new Shake({
				threshold: 15,
				timeout: 1000
			});

			_this.SHAKEEVENT.start();

			window.addEventListener('shake', function() {
				if(_this.isShaked) {
					return;
				}

				_this.isShaked = true;
				_this.unbindAnimate(); // 取消动画
				_this.playAudio(_this.audios.$startShake); // 播放摇一摇声音
				_this.shakeCallBack();
			}, false);

		},
		shakeCallBack: function(obj) {
			var _this = this;

			window.scrollTo(0, 0);
			// 摇一摇或按开始执行动画
			_this.stopGesture();
			_this.zoomIn();
			setTimeout(function() {
				_this.closeDoor();
				_this.zoomInOver();
				_this.startCircle();
			}, 600);
			

			// 开始请求奖品
			setTimeout(function() {
				_this.api.getGiftSubmit.call(_this.api, function(data) {
					// 中奖
					data = data || {};

					_this.stopCircle();
					_this.openDoor();
					
					_this.playAudio(_this.audios.$startWin); // 播放摇一摇声音
				//	_this.free = parseInt(data.free, 10);
					$('#success_dialog').attr('src', SuccessUrl);
					_this.alertResult(data);
				}, function(data) {
					// 失败

					data = data || {};	
					//if(data.free || data.free === 0) {
					//	_this.free = parseInt(data.free, 10);
					//}

					_this.stopCircle();
					_this.alertResult(data);

				});
			}, 2400);
		},
		
		postMessage: function(data) {
			var id = this.$successDialog.attr('id');
			//data.goods_img = this.api.getImageUrl(data.welfare.pic, 300);
			//data.mid = this.api.cacheData.near_merchant.mid;
			//data.merchant = this.api.cacheData.near_merchant.merchant;
			
			window.slotData = data;
			console.log(data, 888)
			document.getElementById(id).contentWindow.postMessage(data, 'http://'+window.location.host+'/'); 
		},
		startGesture: function() {
			// 开始手势的晃动
			this.$gesture.addClass("animation");
		},
		stopGesture: function() {
			// 结束手势的晃动
			this.$gesture.removeClass("animation");
		},
		zoomIn: function() {
			// 放大的圆圈
			this.$zoomIn.addClass("animation");
		},
		zoomInOver: function() {
			this.$zoomIn.removeClass("animation");
		},
		openDoor: function(callback) {
			var _this = this;
			// 打开动画的门
			_this.$doorL.css({"width":"51%", "display": "block", "border": 0});
			_this.$doorR.css({"width":"51%", "display": "block", "border": 0});

			_this.$doorL.animate({
				width: 0
			}, 400, "linear", function() {
				_this.$doorL.css({"display": "none"});
			});

			_this.$doorR.animate({
				width: 0
			}, 400, "linear", function() {
				_this.$doorR.css({"display": "none"});
			});
		},
		closeDoor: function(callback) {
			var _this = this;
			// 关闭动画的门
			_this.$doorL.css({"width":"0", "display": "block", "border": ""});
			_this.$doorR.css({"width":"0", "display": "block", "border": ""});

			_this.$doorL.animate({
				width: "51%"
			}, 400);
			_this.$doorR.animate({
				width: "51%"
			}, 400);

		},
		startCircle: function() {
			var _this = this;
			// 亮灯开始旋转
			_this.$dotDefault.hide();
			_this.$dotHighlight.show();

			_this.DEGINTERVAL = setInterval(function() {
				_this.runCircle();
			}, 80);
		},
		stopCircle: function() {
			// 亮灯停止旋转
			clearInterval(this.DEGINTERVAL);
		},
		runCircle: function() {
			 //亮灯的位置旋转函数, 旋转的步数是15deg(共24盏灯)
			this.$dotHighlight.animate({
				rotate: (this.INITDEG - 15) + "deg"
			}, 0);

			if(this.INITDEG <= -360) {
				this.INITDEG = 0;
			}
			this.INITDEG -= 15;
		},
		unbindAnimate: function() {
			// 开始动画后，解除点击事件、手势动画和摇一摇
			//this.$btn.off("touchend");
			this.stopGesture();
			//window.removeEventListener('shake');
		},
		bindAnimate: function() {
			// 恢复点击事件、手势动画和摇一摇
			var _this = this;
			_this.$btn.on('touchend', function() {
				_this.shakeCallBack();
			});
			_this.startGesture();
			window.addEventListener('shake');
		},
		alertResult: function(data) {
			if(data && data.flag) {
				this.postMessage(data);
				this.alertSuccess();
			}
			else {
				this.alertError(true);
			}
		},
		displayDialog: function($dialog, cssOptions, isShow, isCover) {
			var _this = this, options, display,time = 0;
			if(this.displayDialogTimeValue) {
				clearTimeout(this.displayDialogTimeValue);
				this.displayDialogTimeValue = null;
			}

			if(isShow) {
				display = 'block';
				$dialog.css({
					display: display
				});
				options = cssOptions;
			}
			else {
				display = 'none';
				$dialog.css(cssOptions);
				options = {
					display: display
				}
				time = 1000;
			}
			this.$dialogCove.css({
				display: display
			})
			this.displayDialogTimeValue = setTimeout(function() {
				clearTimeout(_this.displayDialogTimeValue);
				_this.displayDialogTimeValue = null;
				$dialog.css(options);
			}, time);
		},
		alertError: function(isShow) {
			if(this.free) {
				var _this = this, top, display;
				if(isShow) {
					top = '0';
					display = 'block';
				}
				else {
					top = '-150%';
					display = 'none';
				}
				this.displayDialog(this.$errorDialog, {
					top: top
				}, isShow, true);	
			}
			else {
				this.$endErrorDialog.css({
					left: 0
				});
				this.disabledBodyScoll(this.$endErrorDialog);
				$('.tx_pageLoading').hide();
			}				
		},
		slotStop: function(targeUrl) {
			this.$endErrorDialog.attr('src', targeUrl);
			this.$endErrorDialog.css({
				'left': 0,
				'transition': 'left 0s',
				'-moz-transition': 'left 0',
				'-webkit-transition': 'left 0s'
			});
			this.disabledBodyScoll(this.$endErrorDialog);
		},
		setOpenUrl: function($dialog) {
			var id = $dialog.attr('id'), url;
			var href = window.location.href.indexOf('#') > 0 ? window.location.href.split('#')[0] : window.location.href;
			
			$dialog.bind('load', function() {
				url = $dialog[0].contentWindow.location.href;
				if(url.indexOf('win_prize.html') === -1) {
					window.location.href = href + '#' + encodeURIComponent(url);
				}
			});
		},
		disabledBodyScoll: function($dialog) {
			this.setOpenUrl($dialog);

			$("#body").animate({
				opacity: 0
			}, 500, 'linear', function() {
				$('#body').remove();
			});

			$('html').css({
				background: '#ee3320'
			});
			
			var height = $dialog.height();
			setTimeout(function() {
				$dialog.css({
					'position': 'absolute',
					"min-height": height + 'px'
				});
			}, 1000);
		},
		alertSuccess: function() {
			this.$successDialog.css({
				left:'0'
			});
			this.disabledBodyScoll(this.$successDialog);
		},
		stopAudio: function($el) {
			var audio = document.getElementById($el.attr('id'));
			audio.pause();
		},
		playAudio: function($el, isAutoPlay) {
			var audio = document.getElementById($el.attr('id'));
			audio.play();
			if(isAutoPlay) {
				document.body.addEventListener('touchstart', function() {
					audio.play();
					audio.pause();
				}, false);
			}			
		}
	}
	window.ShakePrize = ShakePrize;
})(Zepto);