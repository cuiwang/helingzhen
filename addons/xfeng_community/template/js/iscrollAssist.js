/**
 * 滚动辅助模块
 * @author 564539969@qq.com
 * @since 2013-07-26
 */
;(function($,window){
	
	//模块对外提供的公共方法
	var exportsMethods = {
	
		/**
		 * 新建一个竖直滚动实例,并做一些处理,整合上拉下拉的功能
		 * wrapper        要渲染滚动实例的位置
		 * pulldownAction 下拉执行的逻辑
		 * pullupAction   上拉执行的逻辑
		 * opts           滚动个性化参数 
		 * pullText       拉动时不同状态要显示的文字
		 */
		newVerScrollForPull : function(wrapper,pulldownAction,pullupAction,opts,pullText){
			
			var $wrapper ;
			if(typeof wrapper === 'string'){
				$wrapper = $(wrapper);
			}else if(typeof wrapper === 'object'){
				$wrapper = wrapper;
			}
			
			var pulldownRefresh   = pullText && pullText['pulldownRefresh'] ? pullText['pulldownRefresh'] : '下拉刷新...',
				pullupLoadingMore = pullText && pullText['pullupLoadingMore'] ? pullText['pullupLoadingMore'] : '上拉加载更多...',
				releaseToRefresh  = pullText && pullText['releaseToRefresh'] ? pullText['releaseToRefresh'] : '松手开始刷新...',
				releaseToLoading  = pullText && pullText['releaseToLoading'] ? pullText['releaseToLoading'] : '松手开始加载...',
				loading 		  = pullText && pullText['loading'] ? pullText['loading'] : '加载中...';
			
			var $pulldown = $wrapper.find('#pulldown'),
				$pullup   = $wrapper.find('#pullup'),
				pullupOffset   = 0,
				pulldownOffset = 0;
			
			if($pulldown.length>0){
				pulldownOffset = $pulldown.outerHeight();
				$pulldown.find('#pulldown-label').html(pulldownRefresh);
			}
			
			if($pullup.length>0){
				pullupOffset = $pullup.outerHeight();
				$pullup.find('#pullup-label').html(pullupLoadingMore);
			}
			
			//这个属性很重要,目前V5版本不支持,需修改源码
			var options = {
				topOffset : pulldownOffset
			};
			
			$.extend(true,options,opts);
			
			var scrollObj = this.newVerScroll($wrapper[0],options);
			
			//滚动刷新触发的事件
			scrollObj.on('refresh',function(){
				
				var $pulldown = $wrapper.find('#pulldown'),
					$pullup   = $wrapper.find('#pullup');
				
				if ($pulldown.length>0 && $pulldown.hasClass('loading')) {
					$pulldown.removeClass();
					$pulldown.find('#pulldown-label').html(pulldownRefresh);
				} else if ($pullup.length>0){
					$pullup.find('#pullup-icon').show();
					if($pullup.hasClass('loading')){
						$pullup.find('#pullup-icon').show();
						$pullup.removeClass();
						$pullup.find('#pullup-label').html(pullupLoadingMore);
					}

				}
			});
			
			//滚动的时候触发的事件
			scrollObj.on('scrollMove',function(){
				
				var $pulldown = $wrapper.find('#pulldown'),
					$pullup   = $wrapper.find('#pullup');
					
				if ($pulldown.length>0 && this.y > 5 && !$pulldown.hasClass('flip')) {
					$pulldown.removeClass().addClass('flip');
					$pulldown.find('#pulldown-label').html(releaseToRefresh);
					this.minScrollY = 0;
					
				} else if ($pulldown.length>0 && this.y < 5 && $pulldown.hasClass('flip')) {
					$pulldown.removeClass();
					$pulldown.find('#pulldown-label').html(pulldownRefresh);
					this.minScrollY = -pulldownOffset;
				//this.y < this.minScrollY代表是上拉,以防下拉的时候未拉到尽头时进入上拉的逻辑中
				} else if ($pullup.length>0 && this.y < this.minScrollY && this.y < (this.maxScrollY - 5) && !$pullup.hasClass('flip')) {
					$pullup.removeClass().addClass('flip');
					$pullup.find('#pullup-label').html(releaseToLoading);
					this.maxScrollY = this.maxScrollY;
					
				} else if ($pullup.length>0 && (this.y > (this.maxScrollY + 5)) && $pullup.hasClass('flip')) {
					$pullup.removeClass();
					$pullup.find('#pullup-label').html(pullupLoadingMore);
					this.maxScrollY = pullupOffset;
				}
			});
			
			//滚动结束之后触发的事件
			scrollObj.on('scrollEnd',function(){
				
				var $pulldown = $wrapper.find('#pulldown'),
					$pullup   = $wrapper.find('#pullup');
					
				if ($pulldown.length>0 && $pulldown.hasClass('flip')) {
					$pulldown.removeClass().addClass('loading');
					$pulldown.find('#pulldown-label').html(loading);
					if(typeof pulldownAction === 'function'){
						pulldownAction.call(scrollObj);	
					}
				} else if ($pullup.length>0 && $pullup.hasClass('flip')) {
					$pullup.removeClass().addClass('loading');
					$pullup.find('#pullup-label').html(loading);
					if(typeof pullupAction === 'function' && $pullup.parent().length>0){
						pullupAction.call(scrollObj);	
					}				
				}
			});
			
			return scrollObj;
		},
		/**
		 * 创建一个竖直方向的滚动实例
		 * @param obj    dom对象或者选择字符串
		 * @param option 滚动其他属性
		 * @return IScroll实例对象
		 */
		newVerScroll : function(dom,option){
			var opt = {
				scrollbars : true, //是否有滚动条
				useTransition: false
			};
			if(option){
				$.extend(opt,option);
			}
			var iSObj = new IScroll(dom,opt);
			
			//滚动条在滚动时显示出来,滚动结束隐藏
			//V5以前版本有个参数可以设置,V5之后目前只能手动处理滚动条的显示隐藏或者可从外部传个参数进来判断
			iSObj.on("scrollEnd",function(){
				if(this.indicator1){
					this.indicator1.indicatorStyle['transition-duration'] = '350ms';
					this.indicator1.indicatorStyle['opacity'] = '0';
				}
			});
			iSObj.on("scrollMove",function(){
				if(this.indicator1){
					this.indicator1.indicatorStyle['transition-duration'] = '0ms';
					this.indicator1.indicatorStyle['opacity'] = '0.8';
				}
			});
			return iSObj;
		}
	};
	
	window.iscrollAssist = exportsMethods;
	
})(jQuery,window);