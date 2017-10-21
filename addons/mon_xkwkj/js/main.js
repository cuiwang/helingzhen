define(function(require, exports, module){
    var $ = require("../js/zepto"),
    	myDialog = require("../js/myDialog"),
		_APP = window.APP||{};
		window.$ = $,
		$eles = {},
		ele = {};

	//
	ele = (function(){
		function Ele(){
			var loading_bottom = '<div data-role="widget" data-widget="loading_bottom" id="loading_bottom" class="loading_bottom">\
							<div class="widget_wrap">\
								<ul class="tbox">\
									<li>\
										<div class="loading_wrap">\
											<span class="loading">&nbsp;</span>\
										</div>\
									</li>\
									<li>\
										正在加载，请稍后...\
									</li>\
								</ul>\
								<ul class="tbox">\
									<li></li>\
									<li style="text-align:center;">\
										没有更多了\
									</li>\
								</ul>\
							</div>\
						</div>';
			var img_src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVQIW2NkAAIAAAoAAggA9GkAAAAASUVORK5CYII=";
			
			this.loading_bottom = loading_bottom;
			this.img_src = img_src;
		}
		//
		return new Ele();
	})();
	//显示客服按钮和返回顶部按钮
	(function(){
		var _html = "",
			fn_goTop,
			fn_message;
		if(_APP.goTop){
			_html += '<li>\
						<a href="javascript:;" id="tools_widget_goTop" class="tools_widget_goTop hidden">顶部</a>\
					</li>';
			fn_goTop = function($id){
				var Obj = {
					ele: $id[0],
					init: function(){
						this.ele.addEventListener("click", function(){
							var st = document.body.scrollTop, step = 10;
							var timer = setInterval(function(){
								window.scrollBy(0, -(step+=5) );
								if( (st-=step)<=0){
									clearInterval(timer);
								}
							}, 15);
						}, false);
						window.addEventListener("scroll", this, false);
						return this;
					},
					handleEvent:function(evt){
						var top = document.body.scrollTop,
							height = $(window).height();
						this.ele.classList[top<height?"add":"remove"]("hidden");
						return this;
					}
				}
				Obj.init().handleEvent();
			}
		}
		if(_APP.message){
			_html += '<li id="tools_kfli" style="display:none;">\
						<a href="javascript:;" id="tolls_widget_message" class="tolls_widget_message" >客服</a>\
					</li>';
			fn_message = function () {
			    var message = require.async("js_cmd/message");
			}
		}
		if(_html){
			_html = '<div data-widget="tools" data-tools="tools_widget" id="tools_widget" class="tools_widget" >\
				<div class="widget_wrap">\
					<ul class="tools_widget_wrap">'+_html +'</ul></div></div>';
			var $_html = $(_html);
			$_html.appendTo( $(".container") );
			fn_goTop&&fn_goTop($_html.find("#tools_widget_goTop"));
			fn_message&&fn_message($_html.find("#tolls_widget_message"));
		}

	})();
	//限制非粉丝购买
	if(_APP.limit && (1 == _APP.limit.val) ){
		window.dialog_limit_buy = (function(count){
			var d = dialog(null,{
				str:"该店铺只允许粉丝购买，请先关注店铺公众号再购买商品吧~ ",
				TPL:'<div class="widget_wrap" style="z-index:{zIndex2};" >\
						<div class="widget_header"></div>\
						<div class="widget_body">{str}</div>\
						<div class="widget_footer">\
							<ul>\
								<li><a href="javascript:;" class="button" data-widget="data-btn" data-btn="0">取消</a></li>\
								<li><a href="javascript:;" class="button" data-widget="data-btn" data-btn="1">立即关注</a></li>\
							</ul>\
						</div>\
					</div>',
				TPL_MASK:'<div class="widget_mask" style="z-index:{zIndex2};display:block;"></div>',
				classes:'limit_buy_dialog',
				callBack: function(evt){
					var et = evt.target, dataBtn = null;
					if("A" == et.tagName && (dataBtn = et.getAttribute("data-btn")) ){
						if("1" == dataBtn){
							APP.shareInfo.followUrl&&(location.href=APP.shareInfo.followUrl);
						}else{

						}
						this.destroy();
					}
				}
			});
			var count = Number(sessionStorage.getItem("limitBuy") );
			if(!count){
				d.open();
				sessionStorage.setItem("limitBuy", ++count)
			}else{
				console.log("open once");
			}
			return d;
		})(0);
	}
	//分享窗口
	if(window.APP && APP.shareInfo && APP.shareInfo.type){
		var BODY = null, classesArr = ["dialog_guid"], dialogObj = null;
		switch(APP.shareInfo.type){
			case "10":
				BODY = '<li>\
							<span class="img_wrap">\
								<img src="{wImgUrl}" />\
							</span>\
						</li>\
						<li style="width:100%;">\
							<p>{wNickName}</p>\
						</li>\
						<li>\
							<div style="width:110px;text-align:right;"><a href="{followUrl}" >立即关注</a><a href="javascript:;" class="close">&nbsp;</a></div>\
						</li>';
				classesArr.push("dialog_guid_follow");
			break;
			case "20":
				BODY = '<li>\
							<span class="img_wrap">\
								<img src="{friendImgUrl}" />\
							</span>\
						</li>\
						<li style="width:100%;">\
							<p>来自好友{friendName}的推荐</p>\
						</li>\
						<li>\
							<div style="width:110px;text-align:right;"><a href="{followUrl}" >立即关注</a><a href="javascript:;" class="close">&nbsp;</a></div>\
						</li>';
				classesArr.push("dialog_guid_follow_authentication");
			break;
			case "30":
				BODY = '<li style="width:100%;">\
							<p>可得佣金：{commission}</p>\
							<p>立即发送给朋友或分享到朋友圈</p>\
						</li>';
				classesArr.push("dialog_guid_share");
			break;
			//40 copy from 20
			case "40":
				BODY = '<li>\
							<span class="img_wrap">\
								<img src="{friendImgUrl}" />\
							</span>\
						</li>\
						<li style="width:100%;">\
							<p>{content}</p>\
						</li>\
						<li>\
							<div style="width:110px;text-align:right;"><a href="{followUrl}" >立即开通</a><a href="javascript:;" class="close">&nbsp;</a></div>\
						</li>';
				classesArr.push("dialog_guid_follow_authentication");
			break;
		}
		dialogObj = {
			TPL:'<div class="widget_wrap" style="z-index:{zIndex};" ontouchmove="event.preventDefault();">\
					<ul class="tbox" style="z-index:{zIndex2};">'+
						BODY
					+'</ul>\
				</div>',
			TPL_MASK:"",
			callBack: function(evt){
				var et = evt.target;
				if(et.className.indexOf("close")>-1){
					this.destroy();
				}
			},
			classes:classesArr.join(" ")
		};
		for(var k in APP.shareInfo){
			dialogObj[k] = APP.shareInfo[k];
		}
		dialog(null, dialogObj).open();
	}
	//
	$(function(){
		//设置版权信息位置为一屏
		$('*[data-role="body"]').css("min-height", (Math.min(window.screen.height, (document.body.scrollHeight-68),$(window).height()-68) - $('*[data-role="header"]').height() )+"px");
	});

	module.exports = {
		ele: ele
	}
});