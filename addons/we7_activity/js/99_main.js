/*动画框架函数*/
var rAF = window.requestAnimationFrame	||
	window.webkitRequestAnimationFrame	||
	window.mozRequestAnimationFrame		||
	window.oRequestAnimationFrame		||
	window.msRequestAnimationFrame		||
	function (callback) { window.setTimeout(callback, 1000 / 60); };

/**
 * [ylLightAPP14 description]
 * @type {Object}
 */
var ylLightAPP14 = {
/*
** 对象私有DOM、变量
*/		
	_events 		: {},						//自定义事件---this._execEvent('scrollStart');

	map 			: $('.ylmap'),				//地图对象
	mapBtn			: $('.mapBtn'),				//地图按钮
	mapValue		: null,						//地图打开时，存储最近打开的一个地图
	mapIndex		: null,						//开启地图的坐标位置

	transformNode	: $('.transformNode'),		//旋转对象
	subPage			: $('.subPage'),			//旋转下面的对象
	bigPic			: $('.m-bigPic'),			//封面大图
	bigPicBtn		: $('.m-bigPic').find('.btn02 img'),	//封面打开切换按钮
	tansitionValue	: true,						//触摸是否可以旋转
	
	sigeUpWin		: true,						//报名窗口可否打开
	scrollSigeUp	: 0,						//报名传开打开记录滚动条上高
	
	windowHeight	: $(window).height(),		//设备屏幕高度
	headerTime		: 0,						//头像出现的interval时间值
	timeControl		: '',						//头像出现的interval控制键
	headerIndex		: '',						//点击头像的id值
	touchStartValX	: '',						//触摸开始获取的第一个值
	touchStartValY	: '',						//触摸开始获取的第一个值
	moveStar		: '',						
	UC 				: RegExp("Android").test(navigator.userAgent)&&RegExp("UC").test(navigator.userAgent)? true : false,
	weixin			: RegExp("MicroMessenger").test(navigator.userAgent)? true : false,
	iPhoen			: RegExp("iPhone").test(navigator.userAgent)||RegExp("iPod").test(navigator.userAgent)||RegExp("iPad").test(navigator.userAgent)? true : false,
	Android			: RegExp("Android").test(navigator.userAgent)? true : false,
	IsPC			: function(){ 
						var userAgentInfo = navigator.userAgent; 
						var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"); 
						var flag = true; 
						for (var v = 0; v < Agents.length; v++) { 
							if (userAgentInfo.indexOf(Agents[v]) > 0) { flag = false; break; } 
						} 
						return flag; 
					} ,
	isOwnEmpty		: function (obj) { 
						for(var name in obj) { 
							if(obj.hasOwnProperty(name)) { 
								return false; 
							} 
						} 
						return true; 
					},
	WXinit			: function(callback){
						if(typeof window.WeixinJSBridge == 'undefined' || typeof window.WeixinJSBridge.invoke == 'undefined'){
							setTimeout(function(){
								ylLightAPP14.WXinit(callback);
							},200);
						}else{
							callback();
						}
					},

/*样式兼容判断******************************************************************************************************************/
	_elementStyle	: document.createElement('div').style,
	_vendor			: function () {
						var vendors = ['t', 'webkitT', 'MozT', 'msT', 'OT'],
							transform,
							i = 0,
							l = vendors.length;
				
						for ( ; i < l; i++ ) {
							transform = vendors[i] + 'ransform';
							if ( transform in this._elementStyle ) return vendors[i].substr(0, vendors[i].length-1);
						}
						return false;
					},
	_prefixStyle	: function (style) {
						if ( this._vendor() === false ) return false;
						if ( this._vendor() === '' ) return style;
						return this._vendor() + style.charAt(0).toUpperCase() + style.substr(1);
					},
	hasPerspective	: function(){
						var ret = this._prefixStyle('perspective') in this._elementStyle;
						if ( ret && 'webkitPerspective' in this._elementStyle ) {
							this.injectElementWithStyles('@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}', function( node, rule ) {
								ret = node.offsetLeft === 9 && node.offsetHeight === 3;
							});
						}
						return !!ret;
					},	

	injectElementWithStyles : function( rule, callback, nodes, testnames ) {
								
								var style, ret, node, docOverflow,
									div = document.createElement('div'),
									body = document.body,
									fakeBody = body || document.createElement('body'),
									mod = 'modernizr';

								if ( parseInt(nodes, 10) ) {
									while ( nodes-- ) {
										node = document.createElement('div');
										node.id = testnames ? testnames[nodes] : mod + (nodes + 1);
										div.appendChild(node);
										}
								}

								style = ['&#173;','<style id="s', mod, '">', rule, '</style>'].join('');
								div.id = mod;
								(body ? div : fakeBody).innerHTML += style;
								fakeBody.appendChild(div);
								if ( !body ) {
									fakeBody.style.background = '';
									fakeBody.style.overflow = 'hidden';
									docOverflow = docElement.style.overflow;
									docElement.style.overflow = 'hidden';
									docElement.appendChild(fakeBody);
								}

								ret = callback(div, rule);
								if ( !body ) {
									fakeBody.parentNode.removeChild(fakeBody);
									docElement.style.overflow = docOverflow;
								} else {
									div.parentNode.removeChild(div);
								}

								return !!ret;
							},
	// 给自定义事件绑定函数
	ylEvenFn				: function (type, fn) {
								if ( !this._events[type] ) {
									this._events[type] = [];
								}

								this._events[type].push(fn);
							},
	// 打开封页回调的函数
 	_execEvent 				: function (type) {
								if ( !this._events[type] ) {
									return;
								}

								var i = 0,
									l = this._events[type].length;

								if ( !l ) {
									return;
								}

								for ( ; i < l; i++ ) {
									this._events[type][i].call(this);
								}
							},
/*样式兼容判断**************************************************************************************************************end*/
/*
** 对象私有变量控制值
*/
	//滚动条的值
	windowScrollTop			: $(window).scrollTop(),
	introHeight				: $('.m-speaker').prev().length>0 ? $('.m-speaker').prev().height() : 0,
	addressHeight			: $('.m-process').prev().length>0 ? $('.m-process').prev().height() : 0,
	introOffsetTop			: $('.m-speaker').prev().length>0 ? $('.m-speaker').prev().position().top : 0,
	addressOffsetTop		: $('.m-process').prev().length>0 ? $('.m-process').prev().position().top : 0,
	speakerOffsetBottom		: $('.m-speaker').next().length>0 ? $('.m-speaker').next().position().top-150 : 0,
	processOffsetBottom		: $('.m-process').next().length>0 ? $('.m-process').next().position().top-100 : 0,
	
/*
** 滚动条显示内容功能
*	
*/
	/**
	 * [scrollSlider description]
	 * @return {[type]} [description]
	 */
	scrollSlider	: function(){
		
		//演讲头像出现的判断
		var scrollSpeaker;
		if(this.windowHeight>this.introHeight){
			this.windowHeight-this.introHeight>=250?scrollSpeaker=this.introOffsetTop+250-this.windowHeight+this.introHeight:scrollSpeaker=250-(this.windowHeight-this.introHeight)+this.introOffsetTop;
		}else{
			scrollSpeaker=this.introHeight-this.windowHeight+250+this.introOffsetTop;
		}

		//事件流程的出现判断
		var srcollProcess;
		if(this.windowHeight>this.introHeight){
			this.windowHeight-this.addressHeight>=180?srcollProcess=this.addressOffsetTop+180-this.windowHeight+this.addressHeight:srcollProcess=180-(this.windowHeight-this.introHeight)+this.addressOffsetTop;
		}else{
			srcollProcess=this.addressHeight-this.windowHeight+180+this.addressOffsetTop;
		}
		
		//滚动条事件触发
		$(window).on('scroll',function(e){
			//获取滚动条上边的值
			var scroolTop = $(window).scrollTop();
			
			if(scroolTop<=0){
				e.preventDefault();
			}

			//翻页后，滚动条事件，统计发异步请求
			if(ylLightAPP14.bigPic.css('display')=='none'){
				ylLightAPP14.scrollAjaxSubmit();
			}
			//演讲者头像的显示
			if(scroolTop>=scrollSpeaker&&parseInt(ylLightAPP14.speakerOffsetBottom-scroolTop)>=10&&ylLightAPP14.bigPic.css('display')=='none'){
				var maxL = Math.max($('.headerBox.left').find("ul li").size(),$('.headerBox.right').find("ul li").size());

				$listLeft = $('.headerBox.left').find("ul li");
				$.each($listLeft,function(i,n){
					function a(b){
						$(n).addClass("show");
					}
					var time = i*500;
					setTimeout(a,time);
				});
				
				$listRight = $('.headerBox.right').find("ul li");
				$.each($listRight,function(i,n){
					function a(){
						$(n).addClass("show");
					}
					var time = i*500;
					setTimeout(a,time);
				});
			}else if(ylLightAPP14.bigPic.css('display')=='none'){
				//移出去
				/*
				$listLeft = $('.headerBox.left').find("ul li");
				$.each($listLeft,function(i,n){
					function a(){
						$(n).removeClass("show");
					}
					var time = i*500;
					setTimeout(a,time);
				});
				
				$listRight = $('.headerBox.right').find("ul li");
				$.each($listRight,function(i,n){
					function a(){
						$(n).removeClass("show");
					}
					var time = i*500;
					setTimeout(a,time);
				});*/
				
			}
			
			//事件流程显示
			if(scroolTop>=srcollProcess&&parseInt(ylLightAPP14.processOffsetBottom-scroolTop)>=10&&ylLightAPP14.bigPic.css('display')=='none'){
				$processLi = $('.m-process').find("ul li")
				$.each($processLi,function(i,n){
					function a(){
						$(n).addClass("show");
					}
					var time = i*500;
					setTimeout(a,time);
				});
				
			}else if(ylLightAPP14.bigPic.css('display')=='none'){
				//移出去
				
				// $processLi = $('.m-process').find("ul li")
				// $.each($processLi,function(i,n){
				// 	function a(){
				// 		$(n).removeClass("show");
				// 	}
				// 	var time = i*500;
				// 	setTimeout(a,time);
				// });
				
			}
			
			//大封面出现
			if(scroolTop==0&&ylLightAPP14.bigPic.css('display')=='none'){
				if(!ylLightAPP14.map.hasClass("mapOpen")){
					if(ylLightAPP14.hasPerspective()){
						ylLightAPP14.bigPicClose3D();	
					}else{
						ylLightAPP14.bigPicClose2D();	
					}
				}
			}

			//地图icon图片跳动提示
			var iconBox = ylLightAPP14.mapBtn.parents('.m-submitAjax');
		
			iconBox.each(function(){
				var self = $(this),
					scrollTop = $(window).scrollTop();

				if(self.position().top-scrollTop<=ylLightAPP14.windowHeight&&self.next().position().top-scrollTop>=0&&self.find('.mapBtn').attr('data-iconMove')=='true'){
					self.find('.mapBtn span.icon').addClass('move');
				}else{
					self.find('.mapBtn span.icon').removeClass('move');
				}
			})
		})
	},

	scrollBackBigPicClose	: function(){
		$(window).off('scroll');
	},


/*
** 滚动条统计异步发请求
*/
	scrollAjaxSubmit	: function(){
		var submitOject	= $('.subPage').find('.m-submitAjax');
		submitOject.each(function(){
			var self 		= $(this),
				scrollTop 	= $(window).scrollTop(),
				windowH 	= ylLightAPP14.windowHeight;

			//第一个模块的处理
			if(self.prev().length<=0){
				if(scrollTop < windowH/2){
					if(self.attr('data-ajax')=='true'){
						var dataLayout = self.attr('data-layout');
						//报名统计请求
						ylLightAPP14.ajaxTongji(dataLayout);
						self.attr('data-ajax',false);
					}
				}else{
					self.attr('data-ajax','true');
				}
			//最后一个模块的处理
			}else if(self.next().hasClass('footer')){
				if(self.position().top-scrollTop<=windowH){
					if(self.attr('data-ajax')=='true'){
						var dataLayout = self.attr('data-layout');
						//报名统计请求
						ylLightAPP14.ajaxTongji(dataLayout);
						self.attr('data-ajax',false);
					}
				}else{
					self.attr('data-ajax','true');
				}
			//其他模块的处理
			}else{
				if(self.position().top-scrollTop<=windowH*2/3&&self.next().position().top-scrollTop>=windowH*2/3){
					if(self.attr('data-ajax')=='true'){
						var dataLayout = self.attr('data-layout');
						//报名统计请求
						ylLightAPP14.ajaxTongji(dataLayout);
						self.attr('data-ajax',false);
					}
				}else{
					self.attr('data-ajax','true');
				}
			}
		})
	},

/*
** 弹出框打开控制浏览器滚动条移动
*/
	//禁止滚动条
	scrollStop	: function(){
		//锁定页面高度
		$(document.body).addClass("fixed");

		//禁止滚动
		//人物介绍//报名//封面
		$(window).on('touchmove',ylLightAPP14.scrollControl);

		//人物介绍，演讲的内容滚动
		$('.speakCon').on('touchstart',ylLightAPP14.windowScrollget);
		$('.speakCon').on('touchmove',ylLightAPP14.scrollControl);
		$('.speakCon').on('touchend',ylLightAPP14.windowScrollBack);
	},
	
	//启动滚动条
	scrollStart : function(){		
		//解除页面高度锁定
		$(document.body).removeClass("fixed");	
		
		//开启屏幕禁止
		$(window).off('touchmove');
		
		//人物介绍，演讲的内容滚动
		$('.speakCon').off('touchstart');
		$('.speakCon').off('touchmove');
		$('.speakCon').off('touchend');
	},
	
	//滚动条设置值
	windowScrollget : function(e){
		// e.stopPropagation();
		var startP;
		startP = window.event.touches[0].pageY;
		ylLightAPP14.touchStartValY = startP;	
	},	
	
	//滚动条控制事件
	scrollControl	: function(e){
		e.stopPropagation();	
		e.preventDefault();
		var moveP;
		
		//冒泡处理，单独处理speakCon的move移动事件
		if($(this).hasClass('speakCon')){
			ylLightAPP14.moveStar=true;
		}else{
			ylLightAPP14.moveStar=false;	
		}
		
		if(ylLightAPP14.moveStar){
			moveP = window.event.touches[0].pageY;
			var scrollTop = $(this).scrollTop();
			$(this).scrollTop(scrollTop+ylLightAPP14.touchStartValY-moveP);
			ylLightAPP14.touchStartValY = moveP;
		}else{
			return false;	
		}
	},
	
	//滚定条结束设置
	windowScrollBack	: function(){

	},
/*
** m-bigPic 封面打开的功能
*/
	bigPicOpen3D	: function(){
		//滚动条启动
		ylLightAPP14.scrollStart();

		//显示下面的内容
		$('.subPage').css('opacity',1);

		//封面切换效果
		ylLightAPP14.transformNode.addClass('transitionOpen');
		setTimeout(function(){ylLightAPP14.transformNode.addClass('open');},50)
		
		/*过渡效果完后的调用函数*/
		$(window).on('webkitTransitionEnd transitionend',bigPicOpen3d);
		
		function bigPicOpen3d(){
			$(document.body).removeClass('perspective');
			ylLightAPP14.transformNode.removeClass('transitionOpen');
			
			//移除3d效果控制的ID，并解锁页面的锁定
			ylLightAPP14.transformNode.attr('id','');	
			ylLightAPP14.transformNode.css('height','auto');
			$(document.body).css('height','auto');

			ylLightAPP14.bigPic.removeClass('open');		//隐藏封面

			/*重新计算滚动条函数的参数值*/
			//滚动条的值
			ylLightAPP14.windowScrollTop		= $(window).scrollTop();
			ylLightAPP14.introHeight			=  $('.m-speaker').prev().length>0?$('.m-speaker').prev().height():0;
			ylLightAPP14.addressHeight			= $('.m-process').prev().length>0?$('.m-process').prev().height():0;
			ylLightAPP14.introOffsetTop			= $('.m-speaker').prev().length>0?$('.m-speaker').prev().position().top:0;
			ylLightAPP14.addressOffsetTop		= $('.m-process').prev().length>0?$('.m-process').prev().position().top:0;
			ylLightAPP14.speakerOffsetBottom	= $('.m-speaker').next().length>0?$('.m-speaker').next().position().top-150:0;
			ylLightAPP14.processOffsetBottom	= $('.m-process').next().length>0?$('.m-process').next().position().top-100:0;

			ylLightAPP14.touchBigPicClose();

			//绑定打开事件
			ylLightAPP14._execEvent('open');
			$(window).off('webkitTransitionEnd transitionend');
		}
	},
	
	//关闭封面
	bigPicClose3D	: function(){

		//滚动条关闭
		ylLightAPP14.scrollStop();

		//封面切换效果
		ylLightAPP14.transformNode.css('height','100%');
		$(document.body).css('height',ylLightAPP14.windowHeight);
		ylLightAPP14.transformNode.attr('id','transformNode');	
		$(document.body).addClass('perspective');
		ylLightAPP14.bigPic.addClass('open');
		
		setTimeout(function(){
			ylLightAPP14.transformNode.addClass('transitionOpen');
			ylLightAPP14.transformNode.removeClass('open')
		},200);
		
		/*过渡效果完后的调用函数*/
		$(window).on('webkitTransitionEnd transitionend',bigPicClose3d);
		
		function bigPicClose3d(){
			ylLightAPP14.tansitionValue = true;
			ylLightAPP14.touchBigPicOpen();
			$(window).off('webkitTransitionEnd transitionend');
		}
		
	},
	
	//打开封面
	bigPicOpen2D	: function(){
		//滚动条启动
		ylLightAPP14.scrollStart();
		
		//显示下面的内容
		$('.subPage').css('opacity',1);

		//加上2D的效果
		ylLightAPP14.bigPic.addClass('bigPicStart');
		ylLightAPP14.bigPic.find('.bigPicCon').clone().appendTo(ylLightAPP14.bigPic);
		
		setTimeout(function(){
			ylLightAPP14.bigPic.addClass('move out');
		},100)
		
		ylLightAPP14.bigPic.on('touchstart',function(e){
			return false;
		})
		
		$(window).on('webkitTransitionEnd transitionend',bigPicOpen2d);
		
		function bigPicOpen2d(){
			//隐藏封面
			ylLightAPP14.bigPic.hide();
			
			//3D的效果去掉
			ylLightAPP14.subPage.css('height','auto');
			ylLightAPP14.transformNode.css('height','auto');
			$(document.body).css('height','auto');
			
			ylLightAPP14.touchBigPicClose();

			/*重新计算滚动条函数的参数值*/
			//滚动条的值
			ylLightAPP14.windowScrollTop		= $(window).scrollTop();
			ylLightAPP14.introHeight			= $('.m-speaker').prev().length>0?$('.m-speaker').prev().height():0;
			ylLightAPP14.addressHeight			= $('.m-process').prev().length>0?$('.m-process').prev().height():0;
			ylLightAPP14.introOffsetTop			= $('.m-speaker').prev().length>0?$('.m-speaker').prev().position().top:0;
			ylLightAPP14.addressOffsetTop		= $('.m-process').prev().length>0?$('.m-process').prev().position().top:0;
			ylLightAPP14.speakerOffsetBottom	= $('.m-speaker').next().length>0?$('.m-speaker').next().position().top-150:0;
			ylLightAPP14.processOffsetBottom	= $('.m-process').next().length>0?$('.m-process').next().position().top-100:0;
			
			//绑定打开事件
			ylLightAPP14._execEvent('open');
			$(window).off('webkitTransitionEnd transitionend');
		}
	},

	//关闭封面
	bigPicClose2D	: function(){
		//滚动条启动
		ylLightAPP14.scrollStop();

		//显示封面
		ylLightAPP14.bigPic.show();
	
		//加上2D的效果
		setTimeout(function(){
			//加上3D效果
			ylLightAPP14.subPage.css('height','100%');
			ylLightAPP14.transformNode.css('height','100%');
			$(document.body).css('height','100%');
			ylLightAPP14.bigPic.removeClass('out');
		},100)
		
		ylLightAPP14.bigPic.off('touchstart');
		
		$(window).on('webkitTransitionEnd transitionend',bigPicClose2d);
		
		function bigPicClose2d(){
			ylLightAPP14.bigPic.removeClass('bigPicStart move');
			ylLightAPP14.bigPic.find('.bigPicCon').eq(1).remove();
			
			ylLightAPP14.tansitionValue = true;
			ylLightAPP14.touchBigPicOpen();
			$(window).off('webkitTransitionEnd transitionend');
		}
	},
	
	//清楚所有动画
	clearAnimate	: function(){
		ylLightAPP14.touchBigPicClose();
		ylLightAPP14.scrollStart();
		$('.subPage').css('opacity',1);
		ylLightAPP14.transformNode.attr('id','');
		ylLightAPP14.transformNode.css('height','auto');
		$(document.body).css('height','auto');

		if(ylLightAPP14.hasPerspective()){
			ylLightAPP14.transformNode.addClass('open');
			$(document.body).removeClass('perspective');
		}else{
			ylLightAPP14.bigPic.hide();
			ylLightAPP14.transformNode.attr('id','transformNode2D');
			ylLightAPP14.bigPic.on('touchstart',function(e){
				return false;
			})
			ylLightAPP14.subPage.css('height','auto');
			ylLightAPP14.bigPic.addClass('move out bigPicStart');
			ylLightAPP14.bigPic.find('.bigPicCon').clone().appendTo(ylLightAPP14.bigPic);
		}
	},

	//背景手势打开
	touchBigPicOpen	: function(){
		//取消屏幕禁止事件
		window.removeEventListener('touchmove',fnScrollStop,false);

		//触摸开始事件设置初始值
		$('.m-bigPic').on('touchstart',function(e){
			var startY,startX;
			startY = window.event.touches[0].pageY;
			startX = window.event.touches[0].pageX;
			ylLightAPP14.touchStartValY = startY;
			ylLightAPP14.touchStartValX = startX;
		})
		
		//触摸移动，判断手势，处理动画
		$('.m-bigPic').on('touchmove',function(e){
			e.preventDefault();
			var moveY,moveX;
			moveY = window.event.touches[0].pageY;
			moveX = window.event.touches[0].pageX;
			if(ylLightAPP14.touchStartValY-moveY>50&&ylLightAPP14.hasPerspective()&&ylLightAPP14.tansitionValue){
				ylLightAPP14.bigPicOpen3D();
				ylLightAPP14.tansitionValue = false;
			}
			if(Math.abs(ylLightAPP14.touchStartValX-moveX)>50&&!ylLightAPP14.hasPerspective()&&ylLightAPP14.tansitionValue){
				ylLightAPP14.bigPicOpen2D();
				ylLightAPP14.tansitionValue = false;
			}
		})
	},
	
	//背景手势关闭
	touchBigPicClose	: function(){
		$('.m-bigPic').off('touchstart');
		$('.m-bigPic').off('touchmove');
	},
	
/*   
** u-sigeUp报名弹出框的功能
*/
	//窗口打开
	sigeUpPopupOpen : function(){
		// 判断报名是否结束了
		if($('.u-sigeUp').length <= 0)return;  //不存在标签则直接返回

		//按钮点击报名函数处理
		$('.u-sigeUp').on('click',function(){
			
			//绑定点击外面可以关闭窗口事件
			// $('.mwrap').each(function(){
			// 	$(this).on('click', ylLightAPP14.sigeUpPopupCloseFun);
			// });
			$('.m-sigeUp').find('.u-close').on('click',ylLightAPP14.sigeUpPopupCloseFun);
			
			var sw = $(this).attr('data-switch');
			if(sw=='false'){   //报名时间结束后如果已报名可以查看二维码
				
				return;
			};

			var dataSigeUp = $(this).attr('data-sigeUp'),
				sigeUp 	   = $('.m-sigeUp[data-sigeUp='+dataSigeUp+']'),
				dataLayout = sigeUp.attr('data-layout'),
				dataName   = sigeUp.attr('data-name');

			if(sigeUp.length>=1){
				//静止滚动
				ylLightAPP14.scrollStop();
				if(ylLightAPP14.weixin) $('.m-sigeUp').height($(window).height());
				
				//开启打开动画
				if(ylLightAPP14.sigeUpWin){
					if(ylLightAPP14.transformNode.attr('id')=='transformNode') sigeUp.addClass('m-popup');
					$('.m-sigeUp').addClass('show');
					setTimeout(function(){
						sigeUp.find(".mwrap").addClass('show');
						setTimeout(function(){
							sigeUp.find(".wrapSige").addClass('show');
							sigeUp.find(".wrapSige").css('top', '50%');
						},500)
						sigeUp.find('.u-close').removeClass('start');
						ylLightAPP14.sigeUpWin = false;
					},100)
				}else return;
				
				
				
			}
		
			
			return ;
			
		
		});
	},

	//关闭报名窗口方法
	//函数中的this为 .mwrap 对象
	sigeUpPopupCloseFun : function(e){
		e.stopPropagation();
		if(e.target.className.indexOf('u-close')>-1 || e.target.className.indexOf('mwrap')>-1){
			var sigeUp = $(this).parents('.m-sigeUp');

			//开启移除动画
			sigeUp.find('.u-close').addClass('start');
			//$(".sex p").find('strong').removeClass('open');
			sigeUp.find(".wrapSige").removeClass('show');
			sigeUp.find(".mwrap").removeClass('show');

			//效果结束的动作
			$(window).on('webkitTransitionEnd transitionend',function(){
				sigeUp.removeClass('show m-popup');
				ylLightAPP14.sigeUpWin = true;
				//页面高度自动
				if(ylLightAPP14.bigPic.css('display')=='none') $(document.body).css('height','auto');

				//开启滚动
				ylLightAPP14.scrollStart();

				$('.mwrap').each(function(){
					$(this).off('click');
				});
				$(window).off('webkitTransitionEnd transitionend');
			});
		}

	},
	
	//性别选择
	sexSeclt	:function(){
		$(".sex p").on('click',function(){
			$(".sex p").find('strong').removeClass('open');
			$(this).find('strong').addClass('open');	
			$(this.parentNode).find('input').val($(this).attr('data-sex'));
		})	
	},

	//提示下载二维码
	qrPopup	: function(e){
		e.stopPropagation();
		if(e.target.parentNode.className.indexOf('saveImg')==-1 && e.target.className.indexOf('arrow')==-1 && e.target.className.indexOf('erweima')){
			return;
		}

		//关闭弹窗
		$('.erweima').removeClass('show m-popup');
		$('.erweima .erweima-content').removeClass('show');
		$('.erweima .erweima-content').find('.arrow').addClass('start');

		//图片下载函数
		if($(this).attr('data-type')!='close'&&!$(this).hasClass('erweima')){
			var path = $('.erweima').find('.img img').attr('src');
			if(ylLightAPP14.weixin){
				var tempArray = [];
				path = $('.erweima').find('.img img').find('img')[0].src;
				tempArray.push(path);
				ylLightAPP14.imgScale(path,tempArray);
			}else{
				window.location.href = '/meeting/downpic?path='+path;
			}
		}

		$(window).on('webkitTransitionEnd transitionend',qrPopup);

		function qrPopup(){
			$('.erweima .erweima-content').find('.arrow').removeClass('start');
			
			//开启滚动
			ylLightAPP14.scrollStart();

			$('.erweima').css('display','none');
			$('.erweima .erweima-content').find('.saveImg').off('click');

			//解除事件绑定
			$('.erweima .erweima-content').find('.saveImg a').off('click');
			$('.erweima .erweima-content').find('.arrow').off('click');
			$('.erweima').off('click');
			$(window).off('webkitTransitionEnd transitionend');
		}
	},

	//报名成功后activity_id打开弹窗
	activityPopupOpen	: function(){
		//静止滚动
		ylLightAPP14.scrollStop();

		if(ylLightAPP14.transformNode.attr('id')=='transformNode') $('.sigeSuccess').addClass('m-popup');
		$('.sigeSuccess').css('display','block');
		setTimeout(function(){
			$('.sigeSuccess').addClass('show');
			$('.sigeSuccess').find('.sigeSuccess-content').addClass('show');

			$(window).on('webkitTransitionEnd transitionend',popupOpen);

			function popupOpen(){
				var imgSrc = $('#check_img').val();
				$('.sigeSuccess .sigeSuccess-content').find('.img img').attr('src',imgSrc);

				//提示图片的保存方式
				$('.sigeSuccess .sigeSuccess-content').find('.saveImg a').on('click',ylLightAPP14.activityPopupClose);
				$('.sigeSuccess .sigeSuccess-content').find('.arrow').on('click',ylLightAPP14.activityPopupClose);
				$('.sigeSuccess .erweima-content').on('click',function(e){e.stopPropagation()});
				$('.sigeSuccess').on('click',ylLightAPP14.activityPopupClose);

				$(window).off('webkitTransitionEnd transitionend');
			}
		},200)
	},

	//报名成功后activity_id点击关闭弹窗
	activityPopupClose	: function(e){
		e.stopPropagation();
		e.preventDefault();
		if(e.target.parentNode.className.indexOf('saveImg')==-1 && e.target.className.indexOf('arrow')==-1 && e.target.className.indexOf('sigeSuccess')){
			return;
		}

		// 关闭弹窗
		$('.sigeSuccess').removeClass('show');
		$('.sigeSuccess').find('.sigeSuccess-content').removeClass('show');
		if($(this).hasClass('arrow')) $(this).addClass('start');

		// 保存图片
		if($(this).attr('data-type')!='close'&&!$(this).hasClass('sigeSuccess')){
			var path = $('.sigeSuccess').find('.img img').attr('src');
			if(ylLightAPP14.weixin){
				path = $('.sigeSuccess').find('.img img')[0].src;
				var tempArray = [];
				tempArray.push(path);
				ylLightAPP14.imgScale(path,tempArray);
			}else{
				window.location.href = '/meeting/downpic?path='+path;
			}
		}

		$(window).on('webkitTransitionEnd transitionend',activityPopupClose);

		function activityPopupClose(){
			$('.sigeSuccess .sigeSuccess-content').find('.arrow').removeClass('start');

			//开启滚动
			ylLightAPP14.scrollStart();

			$('.sigeSuccess').removeClass('m-popup');
			$('.sigeSuccess').css('display','none');

			$('.sigeSuccess .sigeSuccess-content').find('.saveImg a').off('click');
			$('.sigeSuccess .sigeSuccess-content').find('.arrow').off('click');
			$('.sigeSuccess .erweima-content').off('click');
			$('.sigeSuccess').off('click');
			$(window).off('webkitTransitionEnd transitionend');
		}

		
	},
	
/*
** 表单输入时，解除绑定事件,绑定当前输入页面的滑动事件
*/	
	inputMove	: function(){
		$('.m-sigeUp input').on('focus',function(){
			if(ylLightAPP14.bigPic.css('display')=='none'){
				ylLightAPP14.signUpOpen();

			}else{
				ylLightAPP14.scrollStart();
			}
		});
		$('input').on('blur',function(){
			ylLightAPP14.scrollStop();
			ylLightAPP14.signUpClose();	
			// $('.wrapSige').css('top', '50%');
		});
	},
	
	//输入页面滑动开始
	signUpOpen	: function(){
		//触摸开始事件设置初始值
		$('.m-sigeUp').on('touchstart',function(e){
			var startY;
			startY = window.event.touches[0].pageY;
			ylLightAPP14.touchStartValY = startY;
		})
		
		//触摸移动，判断手势，处理动画
		$('.m-sigeUp').on('touchmove',function(e){
			e.preventDefault();
			var moveY;
			moveY = window.event.touches[0].pageY;

			var top = parseInt($('.wrapSige').css('top'));

			var moveValue = top+moveY-ylLightAPP14.touchStartValY;
			$('.wrapSige').css('top', moveValue);

			ylLightAPP14.touchStartValY = moveY;
		})
	},
	
	//输入页面滑动关闭
	signUpClose	: function(){
		$('.m-sigeUp').off('touchstart');
		$('.m-sigeUp').off('touchmove');
	},

/**
 *  表单验证函数控制
 */
 	/**
 	 *  提交按钮点击，进行验证函数
 	 */
 	signUp_submit 	: function(){
 		$('.formVal').find('p.submit').on('click',function(e){
	 		e.preventDefault();
			var form = $(this).parents('form');
	 		var valid = ylLightAPP14.signUpCheck_input(form);
	 		if(valid) {
			
	 			ylLightAPP14.signUpCheck_submit(form);
	 		}
	 		else return;
	 	})
 	},

 	/**
 	 * 我要报名表单验证函数
 	 */
 	signUpCheck_input	: function (form, type){
		var valid = true;
		var inputs = form.find('fieldset').find('input');

		inputs.each(function(i, e){
			if(this.name != '' && this.name != 'undefined'){
				//函数验证
				var name = this.name;
				if(name=='company' || name=='job' || name=='email') return true;
				var backData	= ylLightAPP14.regFunction(name);
					
				var empty_tip = backData.empty_tip,
					reg       = backData.reg,
					reg_tip   = backData.reg_tip;
					
						
				//根据结果处理
				if ($.trim($(e).val()) == '') {
					ylLightAPP14.showCheckMessage(empty_tip, true);
					$(e).focus();
					valid = false;
					return false;	
				}
				if (reg != undefined && reg != '') {
					if(!$(e).val().match(reg)){
						$(e).focus();
						ylLightAPP14.showCheckMessage(reg_tip, true);
						valid = false;
						return false;		
					}
				}
				$('.popup_error').html('');	
			}
		});
		if (valid == false) {
			return false;
		}else{
			return true;
		}
	},

	/**
	 *  正则函数验证
	 */
	regFunction	: function(inputName){
		var empty_tip = '请填入报名信息',
			reg_tip = '',
			reg = '';

		//判断
		switch (inputName) {
			case 'name':
				reg = /^[\u4e00-\u9fa5|a-z|A-Z|\s]{1,20}$/;
				empty_tip = '不能落下姓名哦！';
				reg_tip = '这名字太怪了！';
				break;
			case 'tel':
				reg = /^1[0-9][0-9]\d{8}$/;
				empty_tip = '有个联系方式，就更好了！';
				reg_tip = '这号码,可打不通... ';
				break;
			case 'radio':
				empty_tip = '想想，该怎么称呼您呢？';
				reg_tip = '';
				break;
			case 'email':
				reg = /(^[a-z\d]+(\.[a-z\d]+)*@([\da-z](-[\da-z])?)+(\.{1,2}[a-z]+)+$)/i;
				empty_tip = '都21世纪了，应该有个电子邮箱吧！';
				reg_tip = '邮箱格式有问题哦！';
				break;
			case 'company':
				reg = /[\s\S]*/;
				empty_tip = '填个公司吧！';
				reg_tip = '公司名称有点奇怪哦!';
				break;
			case 'job':
				reg = /^[\u4e00-\u9fa5|a-z|A-Z|\s]{1,20}$/;
				empty_tip = '请您填个职位';
				reg_tip = '这个职位太奇怪了！';
				break;
			case 'date':
				empty_tip = '给个日期吧！';
				reg_tip = '';
				break;
			case 'time':
				empty_tip = '填下具体时间更好哦！' ;
				reg_tip = '' ;
				break;
			case 'age':
				reg = /^([3-9])|([1-9][0-9])|([1][0-3][0-9])$/;
				empty_tip = '有个年龄就更好了！';
				reg_tip = '这年龄可不对哦！' ;
				break;
		}
		return {
			empty_tip	:empty_tip,
			reg_tip		:reg_tip,
			reg 		:reg
		}
	},

	/**
	 * ajax异步提交表单数据
	 * object form 表单jquery对象
	 */
	signUpCheck_submit	: function (form){
		
		//alert(form.serialize());
		//return ;
		
	 	loadingPageShow();
		//$('input[name=fakeid]').val(getPar('code'));
		//alert($('input[name=fakeid]').val());
		var url = $("#submiturl").val();
	
		
		/**
		 *  ajax提交数据
		 */
		$.ajax({
			url: url,
			cache: false,
			dataType: 'json',
			async: true,
			type:'POST',
			data: form.serialize(),
			success: function(msg){
				
				loadingPageHide();
				if(msg.code=='200'){   
					var cussess_msg = $('.popup_sucess').eq(0).text();
					/*
					if(!cussess_msg){
						cussess_msg = '报名成功!';
					}*/
					
					cussess_msg = '报名成功!';
					$('.popup_sucess').html(cussess_msg);
					$(".popup_sucess").addClass("on");
					$(".popup_error").removeClass("on");
					
					$('#check_img').val(msg.msg);
					//提交成功，报名按钮变样式
					$('.u-sigeUp').addClass('success').find('a').text('已报名，查看');
				}else if(msg.code=='201'){
					$('.popup_sucess').html('已经报过名!');
					$(".popup_sucess").addClass("on");
					$(".popup_error").removeClass("on");
					$('#check_img').val(msg.msg);
					//提交成功，报名按钮变样式
					$('.u-sigeUp').addClass('success').find('a').text('已报名，查看');
				}else if(msg.code=='402'){
					$('.popup_error').html('抱歉，已达到报名人数限制！');
					$(".popup_sucess").removeClass("on");
					$(".popup_error").addClass("on");
					
					//alert(msg.msg);
				}
				setTimeout(function(){
					if($('.popup_sucess').hasClass('on')){
						//关闭窗口，打开生成的二维码
						$('.m-sigeUp').find('.u-close').trigger('click');
						//setTimeout(function(){ylLightAPP14.activityPopupOpen();},500);
					}
					$(".popup").removeClass("on");
				},2500);
			},
			error : function (XMLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		})
	},

	/*
	** 显示验证信息
	*/
	showCheckMessage	: function (msg, error) {
		if (error) {
			$('.popup_error').html(msg);
			$(".popup_error").addClass("on");
			$(".popup_sucess").removeClass("on");

			setTimeout(function(){
				$(".popup").removeClass("on");
			},2000);
		} else {
			$(".popup_sucess").addClass("on");
			$(".popup_error").removeClass("on");

			setTimeout(function(){
				$(".popup").removeClass("on");
			},2000);
		}
	},

/*
** 点击名字和头像显示人物介绍窗口
*/
	//窗口打开
	peoplePopupOpen	: function(){
		$('.peopleOpen').on('click',function(){
			
			$('.m-people').each(function(){
				$(this).bind('click', ylLightAPP14.peoplePopupCloseFunc);
			});
			
			//获取点击头像的ID，对应显示ID的内容
			ylLightAPP14.headerIndex = $(this).attr('data-headId');
			
			//判断是否有对应的ID演讲者
			if($('.headerBox').find('.u-imgHeader[data-headId='+ylLightAPP14.headerIndex+']').length==0) return false;

			//静止滚动
			ylLightAPP14.scrollStop();

			//点击时当前滚动条的高度
			var	scrollT = $(window).scrollTop(),
				//获取到目标头像的对象
				tagertHeader = $('.headerBox').find('.u-imgHeader[data-headId='+ylLightAPP14.headerIndex+']'),
				offsetT = ylLightAPP14.iPhoen ? tagertHeader.offset().top:tagertHeader.offset().top-scrollT,
				offsetT = ylLightAPP14.UC ? offsetT = tagertHeader.position().top: offsetT=offsetT,
				offsetL = tagertHeader.offset().left,

				//获取其他标签的对象
				idNode			= $('.m-people').find('.peopleId[data-headId='+ylLightAPP14.headerIndex+']'),
				liNode			= idNode.parent(),
				ulNode			= idNode.parent().parent(),
				peopleSection	= idNode.parent().parent().parent(),
				header			= idNode.find('.u-imgHeader'),
				headerClone		= tagertHeader.parent().clone().appendTo(tagertHeader.parent().parent().parent().parent()),
				closeNode		= liNode.find('.u-close');
			
			/********************************/
			// 修改的内容 iphone4头像获取positionTOP的适配
			var version;
			if (/CPU (?:iPhone )?OS (\d+_\d+)/.test(navigator.userAgent)){
	            version = parseFloat(RegExp.$1.replace("_", "."));
	        } else {
	            version = 2;  //can't really detect - so guess
	        }
	        if(version<6.0){
	        	offsetT = tagertHeader.offset().top-scrollT;
	        }
			// 修改的内容
			/********************************/

			//克隆头像移动	
			headerClone.find(".name").remove();				//头像克隆不需要名字
			headerClone.find(".arrow").remove();			//头像箭头不clone
			headerClone.css({
				'position'	: 'fixed',
				'top'		: offsetT,
				'left'		: offsetL,
				'z-index'	: 250,
			})
			
			//初始化设置克隆头像的大小
			headerClone[0].style.transform = 'scale(0.1)';
			headerClone[0].style.OTransform = 'scale(0.1)';
			headerClone[0].style.msTransform = 'scale(0.1)';
			headerClone[0].style.MozTransform = 'scale(0.1)';
			headerClone[0].style.WebkitTransform = 'scale(0.1)';
						 
			//头像位置的移动
			headerClone.animate({
				'top'	: '50%',
				'left'	: 83,
				'margin-left': 20,
				'margin-top' : -307,
			},1500,'easeOutSine',function(){
			});
			
			//人物介绍框出来
			peopleSection.addClass('show');
			liNode.css('display','block');
			setTimeout(function(){
				ulNode.addClass('show');
				closeNode.removeClass('start');
				//根据时间改变头像大小
				ylLightAPP14.headMove(liNode,headerClone);
			},200)
		})
	},
	
	//头像移动函数
	headMove	: function(liNode,headerClone){
		function step(){
			ylLightAPP14.headerTime+=1000/60;
			if(ylLightAPP14.headerTime<=400){
				//头像变大
				var scale = 'scale('+ ylLightAPP14.headerTime/400 +')';
	
				headerClone[0].style.transform = scale;
				headerClone[0].style.OTransform = scale;
				headerClone[0].style.msTransform = scale;
				headerClone[0].style.MozTransform = scale;
				headerClone[0].style.WebkitTransform = scale;
				
				//循环
				rAF(step)
				
			}else{
				//人物出现
				ylLightAPP14.headerTime = 0;					//让控制时间初始化为0
				$('.m-people').find('.u-imgHeader').removeClass('show');
				liNode.addClass('show');				//人物介绍出现
				setTimeout(function(){
					//头像切换
					liNode.find('.u-imgHeader').addClass('show');
					headerClone.remove();
				},1000);
			}
		}(step())
	},
	
	//窗口关闭
	peoplePopupClose : function(){
		$('.m-people').find('.u-close').on('click',ylLightAPP14.peoplePopupCloseFunc)	
	},
	
	//关闭任务头像
	peoplePopupCloseFunc : function(e){
		e.stopPropagation();
		if(e.target.className.indexOf('u-close')==-1 && e.target.className.indexOf('allow-close')==-1){
			return;
		}		
		
		//开启移除动画
		$('.m-people').find('.u-close').addClass('start');	

		$('.m-people').find('ul').removeClass('show');
		$('.m-people').find('ul li').removeClass('show');
		$('.m-people').find('ul li .u-imgHeader').removeClass('show');
		
		//效果结束的动作
		$(window).on('webkitTransitionEnd transitionend',peoplePopupClose);
		function peoplePopupClose(){
			$('.m-people').removeClass('show');
			$('.m-people').find('ul li').hide();

			//开启滚动
			ylLightAPP14.scrollStart();

			$(window).off('webkitTransitionEnd transitionend');
		}
	},

/**
 *  报名成功回调函数，返回当初位置
 */
 	sigeUpBack	: function(bigPicOpen,scrollTop){
 		if(bigPicOpen) ylLightAPP14.clearAnimate();
 		//ylLightAPP14.scrollBackBigPicClose();
 		$(document.body).animate({scrollTop: scrollTop}, 1000, "easeOutSine");
 		//ylLightAPP14.scrollSlider();
 		ylLightAPP14.activityPopupOpen();
 	},

/**
 *  微信插件图片预览功能
 */
 	//图片调用微信的点击放大的功能
 	imgScale	: function() {
		// 调用微信内置的图片浏览
		if(arguments.length>0){
			var srcu = arguments[0];
			var urlsu = arguments[1]
		}
		function initImg(){
			if(typeof(srcu)!='undefined' && typeof(urlsu)!='undefined'){	
				src = srcu;
				urls = urlsu;
		        WeixinJSBridge.invoke('imagePreview',{
		            'current':src,
		            'urls':urls
		       	});
			}else{
				$('img.imgScale').on('click',function(e){
					var urls = [];
					imgs = $('img.imgScale');
					for (var i = 0; i < imgs.length; i++) {
						if(imgs[i].src!='') urls.push(imgs[i].src);
					}
					src = e.target.src;
			        WeixinJSBridge.invoke('imagePreview',{
			            'current':src,
			            'urls':urls
			       	});
				});
			}
		};

		//微信才调用
		if(ylLightAPP14.weixin) ylLightAPP14.WXinit(initImg);
	},
	
	//微信分享到朋友圈，微信发送给朋友内容设置
	wxshare: function(){
		var img = $('#wx-share-img').val();
		var title = $('#wx-share-title').val();
		var desc = $('#wx-share-desc1').html() +"\r\n"+ $('#wx-share-desc2').val();
		var json = {};
		img?json['img_url'] = img:'';
		title?json['title'] = title:'';
		desc?json['desc'] = desc:window.location.href;
		json['link'] = window.location.href;
		json['img_width'] = '640';
		json['img_height'] = '640';
		
		
	},
/* 
** 插件加载
*/
	/**
	 * [pluin description]
	 * @return {[type]} [description]
	 */
	pluin : function(){
		
		var posInit = function(obj){
			obj.options.ulBtn.find('li').find('a').css({'width':'20px','height':'20px'});
		};
		var posSet = function(obj){
			obj.options.ulBtn.find('li').find('a').css('background', 'rgba(255,255,255,0.3)');
			obj.options.ulBtn.find('li').eq(obj.options.pos-1).find('a').css('background', 'rgba(31,181,191,1)');
		};
		//介绍轮播图加载
		$(".imgBox").each(function(){
			var urls = $(this).find('input').val();
			new slidepic(this,{urls:urls,posInit:posInit,posSet:posSet});
		});

		

		/* 图片延迟加载 */		
		$("img.lazy-image").lazyload({
		    failure_limit	: 10,
		    threshold		: 200,
		    callEle			: ylLightAPP14
		});

		//绑定地图出现函数
		//option地图函数的参数
		var option ={
			fnOpen	: ylLightAPP14.scrollStop,
			fnClose	: ylLightAPP14.mapSave
		};	
		this.mapAddEventHandler(this.mapBtn,'click',this.mapShow,option);

		//微信图片插件
		ylLightAPP14.imgScale();
	},
	
/*
** 地图显示
*/	
	//绑定地图开启函数
	/**
	 * [mapAddEventHandler 地图函数对应元素绑定事件]
	 * @param  {[type]}   obj       [点击地图btn对象]
	 * @param  {[type]}   eventType [启动地图的事件]
	 * @param  {Function} fn        [绑定地图开启的函数]
	 * @param  {[type]}   option    [传入的参数]
	 */
	mapAddEventHandler	 : function(obj,eventType,fn,option){
	    var fnHandler = fn;
	    if(!ylLightAPP14.isOwnEmpty(option)){
	        fnHandler = function(e){
	            fn.call(this, option);  //继承监听函数,并传入参数以初始化;
	        }
	    }
	    obj.each(function(){
	  	  $(this).on(eventType,fnHandler);
	    })
	},

	//点击地图按钮显示地图
	mapShow : function(option){
		
		var mapOption = $(this).attr('data-mapPara');
		if(mapOption){
			mapOption = eval('('+mapOption+')');
			mapOption.detal = eval('('+mapOption.detal+')');
			option = $.extend(option,mapOption);
		}
		
		//统计异步请求
		var data = $(this).find('a').attr('data-layout'),
			name = $(this).find('a').attr('data-name');
		ylLightAPP14.ajaxTongji(data,name);

		//点击后关闭动画的参数
		$(this).attr('data-iconMove',false);
		$(this).find('.icon').removeClass('move');
		
		//地图添加
		var detal		= option.detal,
			latitude	= option.latitude,
			longitude	= option.longitude,
		 	fnOpen		= option.fnOpen,
			fnClose		= option.fnClose;

		ylLightAPP14.scrollStop();
		ylLightAPP14.map.addClass('show');
		
		//判断开启地图的位置是否是当前的
		if($(this).attr('data-mapIndex')!=ylLightAPP14.mapIndex){
			ylLightAPP14.map.html($('<div class="bk"><span class="css_sprite01"></span></div>'));
			ylLightAPP14.mapValue = false;
			ylLightAPP14.mapIndex = $(this).attr('data-mapIndex');
		}else{
			ylLightAPP14.mapValue = true;	
		} 

		//如果开启地图的位置不一样则，创建新的地图
		if(!ylLightAPP14.mapValue) ylLightAPP14.addMap(detal,latitude,longitude,fnOpen,fnClose);

		//将地图显示出来
		setTimeout(function(){
			if(ylLightAPP14.map.find('div').length>=1){
				ylLightAPP14.map.toggleClass("mapOpen");
				$('.ylmap .tit').css('position','fixed');
			}else return;
		},100)
	},	
	
	//地图关闭，将里面的内容清空（优化DON结构）
	mapSave	: function(){
		$(window).on('webkitTransitionEnd transitionend',mapClose);
		ylLightAPP14.scrollStart();
		if(!ylLightAPP14.mapValue) ylLightAPP14.mapValue = true;
		function mapClose(){
			ylLightAPP14.map.removeClass('show');
			$(window).off('webkitTransitionEnd transitionend');
		}
	},

	//地图函数传值，创建地图
	addMap	: function (detal,latitude,longitude,fnOpen,fnClose){
		var detal		= detal,
			latitude	= Number(latitude),
			longitude	= Number(longitude);

		var fnOpen		= typeof(fnOpen)==='function'? fnOpen : '',
			fnClose		= typeof(fnClose)==='function'? fnClose : '';

		//默认值设定
		var a = {sign_name:'',contact_tel:'',address:'天安门'};

		//检测传值是否为空，设置传值
		ylLightAPP14.isOwnEmpty(detal)	? detal=a:detal=detal;
		!latitude? latitude=39.915:latitude=latitude;
		!longitude? longitude=116.404:longitude=longitude;
		
		//创建地图
		$('.ylmap').ylmap({
			/*参数传递，默认为天安门坐标*/
			//需要执行的函数（回调）
			detal		: detal,		//地址值
			latitude	: latitude,		//纬度
			longitude	: longitude,	//经度
			fnOpen		: fnOpen,		//回调函数，地图开启前
			fnClose		: fnClose		//回调函数，地图关闭后
		});	
	},

/*
** 统计异步请求函数
 */
 	ajaxTongji	: function(data,name){
 		/*
 		var url = "/meeting/plugin";
 		var activity_id = $('#activity_id').val();
 		var fakeid = $('#fakeid').val();
		//报名统计请求
 		
		if(arguments.length==1){
			//$.post(url,{plugin_type:data, activity_id:activity_id, fakeid:fakeid});
		}else if(arguments.length==2){
			//$.post(url,{plugin_type:data, activity_id:activity_id, plugin_name:name,fakeid:fakeid});
		}else{
			return;
		}*/
 	},
/*
** 页面内容加载loading显示
*/	
	//显示
	loadingPageShow : function(){
		if(ylLightAPP14.transformNode.attr('id')=='transformNode') $('.pageLoading').addClass('m-popup');
		$('.pageLoading').show();
	},
	
	//隐藏
	loadingPageHide : function (){
		$('.pageLoading').hide();	
		$('.pageLoading').removeClass('m-popup');
	},
/**
 * 延迟加载图片
 */	
   lazy_img: function(obj){
		if($(obj).is("img")){
			$(obj).attr('src',$(obj).attr('data-src'));
		}else{
			$(obj).css({
				'background-image'	: 'url('+$(obj).attr('data-src')+')'
			});
		}
		$(obj).removeClass('lazy-img');
		$(obj).bind('touchstart', function(){
			if($(this).is("img")){
				$(this).attr('src',$(this).attr('data-src'));
			}else{
				$(this).css({
					'background-image'	: 'url('+$(this).attr('data-src')+')'
				});
			}
		});
	},
/*
** 页面初始化
*/	
	/**
	 * [init description]
	 * @return {[type]} [description]
	 */
	init : function(){
		
		//统计
		var data = $('.m-bigPic').attr('data-layout');
		//ylLightAPP14.ajaxTongji(data);

		$('p.tel').on('click',tongji);
		$('p.mail').on('click',tongji);
		
		function tongji(e){
			var data = $(this).find('a').attr('data-layout'),
				name = $(this).find('a').attr('data-name');
			ylLightAPP14.ajaxTongji(data,name);
		}

		//初始化封面滚动条禁止
		this.scrollStop();
		
		//初始化封面切换功能
		this.touchBigPicOpen();
	
		//切换效果的函数启动
		$(document.body).css('height',ylLightAPP14.windowHeight);
		if(this.hasPerspective()){
			$('.m-bigPic').find('.btn02 img').eq(1).hide();
			$(document.body).addClass('perspective');
			this.transformNode.attr('id','transformNode');
			this.bigPicBtn.on('click',this.bigPicOpen3D);	
		}else{
			$('.m-bigPic').find('.btn02 img').eq(0).hide();
			this.transformNode.attr('id','transformNode2D');
			this.bigPicBtn.on('click',this.bigPicOpen2D);	
		}

		//初始化滚动条事件
		this.scrollSlider();
		
		//初始化报名弹出框事件
		this.sigeUpPopupOpen();
		
		//报名表单提交
		this.signUp_submit();

		//性别选择
		this.sexSeclt();
		
		//人物介绍窗口
		this.peoplePopupOpen();
		this.peoplePopupClose();
		
		//表单输入事件
		this.inputMove();
		
		//初始化插件
		this.pluin();
		
		//将loading层值设为全局变量
		window.loadingPageShow = this.loadingPageShow;
		window.loadingPageHide = this.loadingPageHide;
		window.IsPC	= this.IsPC;
		
		$('.m-aleat').hide();
		if(ylLightAPP14.weixin) ylLightAPP14.WXinit(ylLightAPP14.wxshare);

		/* top = -20px解决andiord微信上的白线问题 */
		if(ylLightAPP14.Android && ylLightAPP14.Android){
			$('.m-bigPic').css('top','-20px');
			$(window).on('resize',function(){
				ylLightAPP14.windowHeight = $(window).height();
				/* height+20px解决andiord微信上的白线问题 */
				$('.m-bigPic').height(ylLightAPP14.windowHeight+20);
			});
		}
		
		/* 弹出框头像的延迟加载 */
		$(window).on('load',function(){
			$('.lazy-img').each(function(){
				ylLightAPP14.lazy_img(this);
			});
		})
	}
};

/*初始化对象函数*/
ylLightAPP14.init();

