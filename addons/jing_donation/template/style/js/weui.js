/**
 * 
 * @authors Roc (rochuang@xtremeprog.com)
 * @date    2016-01-13 14:38:29
 * @version 0.1.0
 */
var Weui;
;(function (factory) {
	if (typeof define === "function" && define.amd) {
		// AMD模式
		define([ "jquery" ], factory);
	} else {
		// 全局模式
		factory(jQuery);
	}
}(function ($) {
	Weui = function(){
		/**
		*function stopBubble
		*@parms event
		*@return void
		*/
		var stopBubble = function(event) {
			if(event && event.stopPropagation){
				event.stopPropagation();
			}else{
				window.event.cancelBubble = true;
			}
		};
		var alert = function(option,callback){
			$(".weui_dialog_alert").remove();
			if (typeof(option) == "string") {
				var option = $.extend(true,{
					title:"",
					content:""
				},{
					content:option
				});
			}else{
				var option = $.extend(true,{
					title:"",
					content:""
				},option);
			}
			var title = option.title;
			var titleHtml = "";
			if(typeof(title) == "object"){
				if(title.length >=0){
					titleHtml = title[0].outerHTML;
				}else{
					titleHtml = title.outerHTML;
				}
			}else if(typeof(title) == "string"){
				var titleHtml = option.title;
			}

			var content = option.content;
			var contentHtml = "";
			if(typeof(content) == "object"){
				if(content.length >=0){
					contentHtml = content[0].outerHTML;
				}else{
					contentHtml = content.outerHTML;
				}
			}else if(typeof(content) == "string"){
				var contentHtml = content;
			}
			var weui_alert = [
				'<div class="weui_dialog_alert" id="dialog_alert" style="display: none;">',
				    '<div class="weui_mask"></div>',
				    '<div class="weui_dialog">',
				        '<div class="weui_dialog_hd"><strong class="weui_dialog_title">'+titleHtml+'</strong></div>',
				        '<div class="weui_dialog_bd">'+ contentHtml +'</div>',
				        '<div class="weui_dialog_ft">',
				            '<a href="javascript:;" class="weui_btn_dialog weui_btn_sure primary">确定</a>',
				        '</div>',
				    '</div>',
				'</div>'
			].join("");
			$weuiAlert = $(weui_alert);
			$weuiAlert.find("a.weui_btn_sure").on("click",function(){
				stopBubble(event);
				if(callback instanceof Function){
					if(callback() != false){
						$weuiAlert.remove();
					}
				}else{
					$weuiAlert.remove();
				}
			});
			$("body").append($weuiAlert);
			$weuiAlert.show();
		};
		var confirm = function(option){
			$(".weui_dialog_confirm").remove();
			var option = $.extend(true,{
				title:"",
				content:"",
				cancelCallback:function(event){ stopBubble(event); },
				sureCallback:function(event){ stopBubble(event); }
			},option);
			var title = option.title;
			var titleHtml = "";
			if(typeof(title) == "object"){
				if(title.length >=0){
					titleHtml = title[0].outerHTML;
				}else{
					titleHtml = title.outerHTML;
				}
			}else if(typeof(title) == "string"){
				var titleHtml = option.title;
			}

			var content = option.content;
			var contentHtml = "";
			if(typeof(content) == "object"){
				if(content.length >=0){
					contentHtml = content[0].outerHTML;
				}else{
					contentHtml = content.outerHTML;
				}
			}else if(typeof(content) == "string"){
				var contentHtml = content;
			}
			var weui_confirm = [
				'<div class="weui_dialog_confirm" id="dialog_confirm" style="display: none;">',
				    '<div class="weui_mask"></div>',
				    '<div class="weui_dialog">',
				        '<div class="weui_dialog_hd"><strong class="weui_dialog_title">'+titleHtml+'</strong></div>',
				        '<div class="weui_dialog_bd">'+contentHtml+'</div>',
				        '<div class="weui_dialog_ft">',
				            '<a href="javascript:;" class="weui_btn_dialog weui_btn_cancel default">取消</a>',
				            '<a href="javascript:;" class="weui_btn_dialog weui_btn_sure primary">确定</a>',
				        '</div>',
				    '</div>',
				'</div>'
			].join("");
			$weuiConfirm = $(weui_confirm);
			$weuiConfirm.find("a.weui_btn_sure").on("click",function(event){
				stopBubble(event);
				if(option.sureCallback instanceof Function){
					if(option.sureCallback(event) != false){
						$weuiConfirm.remove();
					}
				}else{
					$weuiConfirm.remove();
				}
			});
			$weuiConfirm.find("a.weui_btn_cancel").on("click",function(event){
				stopBubble(event);
				if(option.cancelCallback instanceof Function){
					if(option.cancelCallback(event) != false){
						$weuiConfirm.remove();
					}
				}else{
					$weuiConfirm.remove();
				}
			});
			$("body").append($weuiConfirm);
			$weuiConfirm.show();
		};
		var dialog = function(option){
			$(".weui_dialog_dialog").remove();
			var option = $.extend(true,{
				title:"",
				content:"",
				button:["取消","确认"]
			},option);
			var title = option.title;
			var titleHtml = "";
			if(typeof(title) == "object"){
				if(title.length >=0){
					titleHtml = title[0].outerHTML;
				}else{
					titleHtml = title.outerHTML;
				}
			}else if(typeof(title) == "string"){
				var titleHtml = option.title;
			}

			var content = option.content;
			var contentHtml = "";
			if(typeof(content) == "object"){
				if(content.length >=0){
					contentHtml = content[0].outerHTML;
				}else{
					contentHtml = content.outerHTML;
				}
			}else if(typeof(content) == "string"){
				var contentHtml = content;
			}
			var weui_dialog = [
				'<div class="weui_dialog_dialog" id="dialog_dialog" style="display: none;">',
				    '<div class="weui_mask"></div>',
				    '<div class="weui_dialog">',
				        '<div class="weui_dialog_hd"><strong class="weui_dialog_title">'+titleHtml+'</strong></div>',
				        '<div class="weui_dialog_bd">'+contentHtml+'</div>',
				        '<div class="weui_dialog_ft">',
				            /*'<a href="javascript:;" class="weui_btn_dialog weui_btn_cancel default">取消</a>',
				            '<a href="javascript:;" class="weui_btn_dialog weui_btn_sure primary">确定</a>',*/
				        '</div>',
				    '</div>',
				'</div>'
			].join("");
			$weuiDialog = $(weui_dialog);

			/*创建按钮*/
			var button = option.button;
			var buttonHtml = "";
			var creatButton = function(buttonOption){
				var a = document.createElement("a");
				a.setAttribute("href","javascript:void(0)");
				if (typeof(buttonOption) == 'string') {
					a.innerHTML = buttonOption;
					a.addEventListener("click",function(){
						$weuiDialog.remove();
					},false);
				}else{
					var buttonOption = $.extend(true,{
						text:"",
						class:"",
						style:"",
						callback:function(){}
					},buttonOption);
					if (!buttonOption.text) {
						return "";
					}else{
						var text = buttonOption.text;
						var textHtml = "";
						if(typeof(text) == "object"){
							if(text.length >=0){
								textHtml = text[0].outerHTML;
							}else{
								textHtml = text.outerHTML;
							}
						}else if(typeof(text) == "string"){
							var textHtml = text;
						}
						a.innerHTML = textHtml;
						a.setAttribute("class","weui_btn_dialog " + buttonOption.class);
						a.setAttribute("style",buttonOption.style);
						a.addEventListener("click",function(){
							if(buttonOption.callback() != false){
								$weuiDialog.remove();
							}
						},false);
					}
				}
				return a;
			}
			$weuiDialogFt = $weuiDialog.find(".weui_dialog_ft");
			for (var i = 0; i < button.length; i++) {
				if(button[i]){
					$weuiDialogFt.append(creatButton(button[i]));
				}
			};
			$buttion = $weuiDialogFt.find("a");
			var buttonWidth = (100.00/$buttion.length).toFixed(2);
			$buttion.css("width",buttonWidth+"%");
			$("body").append($weuiDialog);
			$weuiDialog.show();
		};
		var tooltips = function(option){
			$(".weui_toptips").remove();
			var option = $.extend(true,{
				content:"",
				time:2000,
				type:"error",
			},option);
			if (option.type == "success") {
				var complete = [
					'<div class="weui_toptips" style="background:#04BE02;">' + option.content + '</div>'
				].join("");
			}else{
				var complete = [
					'<div class="weui_toptips weui_warn" style="background:' + option.background + '">' + option.content + '</div>'
				].join("");
			}
			$complete = $(complete);
			$("body").append($complete);
			$complete.show();
			setTimeout(function(){
				$complete.remove();
			},option.time);
		}
		var loadingToast = function(){
			$("#loadingToast.weui_loading_toast").remove();
			var loading = [
				'<div id="loadingToast" class="weui_loading_toast" style="display:none;">',
	                '<div class="weui_mask_transparent"></div>',
	                '<div class="weui_toast">',
	                    '<div class="weui_loading">',
	                        '<div class="weui_loading_leaf weui_loading_leaf_0"></div>',
	                        '<div class="weui_loading_leaf weui_loading_leaf_1"></div>',
	                        '<div class="weui_loading_leaf weui_loading_leaf_2"></div>',
	                        '<div class="weui_loading_leaf weui_loading_leaf_3"></div>',
	                        '<div class="weui_loading_leaf weui_loading_leaf_4"></div>',
	                        '<div class="weui_loading_leaf weui_loading_leaf_5"></div>',
	                        '<div class="weui_loading_leaf weui_loading_leaf_6"></div>',
	                        '<div class="weui_loading_leaf weui_loading_leaf_7"></div>',
	                        '<div class="weui_loading_leaf weui_loading_leaf_8"></div>',
	                        '<div class="weui_loading_leaf weui_loading_leaf_9"></div>',
	                        '<div class="weui_loading_leaf weui_loading_leaf_10"></div>',
	                        '<div class="weui_loading_leaf weui_loading_leaf_11"></div>',
	                    '</div>',
	                    '<p class="weui_toast_content">数据加载中</p>',
	                '</div>',
	            '</div>'
			].join("");
			$loading = $(loading);
			var show = function(){
				$("body").append($loading);
				$loading.show();
			};
			var hide = function(){
				$loading.remove();
			};
			return {
				show:show,
				hide:hide
			};
		}();
		var completeToast = function(millisec,callback){
			var millisec = millisec || 3000;
			var complete = [
				'<div id="toast" style="display: none;">',
				    '<div class="weui_mask_transparent"></div>',
				    '<div class="weui_toast">',
				        '<i class="weui_icon_toast"></i>',
				        '<p class="weui_toast_content">已完成</p>',
				    '</div>',
				'</div>'
			].join("");
			$complete = $(complete);
			$("body").append($complete);
			$complete.show();
			setTimeout(function(){
				$complete.remove();
				if (callback instanceof Function) {
					callback();
				};
			},millisec);
		};
		var actionSheet = function(option) {
			$("#actionSheet_wrap.actionSheet_wrap").remove();
			var option = $.extend(true,{
				menu:[],
				action:{
					text: "取消",
					callback: function(){}
				}
			},(option || {}));
			var actionSheetWrapHtml = [
				'<div class="actionSheet_wrap" id="actionSheet_wrap">',
				    '<div class="weui_mask_transition" id="mask"></div>',
				    '<div class="weui_actionsheet" id="weui_actionsheet">',
				        '<div class="weui_actionsheet_menu">',
				        '</div>',
				        '<div class="weui_actionsheet_action">',
				        '</div>',
				    '</div>',
				'</div>'
			].join("");
			var $actionSheetWrap = $(actionSheetWrapHtml);
			var $actionSheet = $actionSheetWrap.find(".weui_actionsheet");
			var $actionSheetMask = $actionSheetWrap.find(".weui_mask_transition");
			var $actionSheetMenu = $actionSheet.find(".weui_actionsheet_menu");
			var $actionSheetAction = $actionSheet.find(".weui_actionsheet_action");

			var showActionSheet = function() {
				if(!$($actionSheetWrap,"body")){
					$("body").append($actionSheetWrap);
				}
				$actionSheet.addClass('weui_actionsheet_toggle');
				$actionSheetMask.show().addClass('weui_fade_toggle')
				$actionSheet.unbind('transitionend').unbind('webkitTransitionEnd');
			};
			var hideActionSheet = function() {
				$actionSheet.removeClass('weui_actionsheet_toggle');
				$actionSheetMask.removeClass('weui_fade_toggle');
				$actionSheet.on('transitionend', function () {
				    $actionSheetMask.hide();
				}).on('webkitTransitionEnd', function () {
				    $actionSheetMask.hide();
				})
			};

			$actionSheetMask.on("click",function () {
				hideActionSheet();
			});

			var menu = option.menu || [];
			var $actionSheetCellItem = "";
			for (var i = 0; i < menu.length; i++) {
				(function(){
					if(typeof(menu[i]) == "string"){
						var action = function() {
							hideActionSheet();
						};
						$actionSheetCellItem = $('<div class="weui_actionsheet_cell">'+menu[i]+'</div>');
					}else{
						menu[i] = menu[i] || {};
						menu[i] = $.extend(true,{
							"text": "",
							"action": function(event) {}
						},menu[i]);
						var actionItem = menu[i].action;
						if(typeof(actionItem) == "function"){
							var action = function() {
								if(actionItem() != false){
									hideActionSheet();
								}
							};
						}else if (typeof(actionItem) == "string") {
							var action = function() {
								window.location.href = actionItem;
							};
						}
						$actionSheetCellItem = $('<div class="weui_actionsheet_cell">'+menu[i].text+'</div>');
					}
					$actionSheetCellItem.on("click",function(event){
						stopBubble(event);
						if(action instanceof Function){
							action();
						}
					});
					$actionSheetMenu.append($actionSheetCellItem);
				})();
			};
			$action = $('<div class="weui_actionsheet_cell" id="actionsheet_action">'+option.action.text+'</div>').on("click",function(){
				if(option.action.callback instanceof Function && option.action.callback() != false){
					hideActionSheet();
				}
			}).appendTo($actionSheetAction);

			$("body").append($actionSheetWrap);
			return {
				show:showActionSheet,
				hide:hideActionSheet
			}
		};
		return {
			alert:alert,
			confirm:confirm,
			dialog:dialog,
			loadingToast:loadingToast,
			completeToast:completeToast,
			tooltips:tooltips,
			actionSheet:actionSheet
		};
	}();
	return Weui;
}));