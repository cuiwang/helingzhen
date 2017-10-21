(function(window) {
	var weui = {};
	weui.dialog = {
		alert : function(content, title) {
			var timestamp = Date.parse(new Date());
			var id = 'weui_dialog_alert'+timestamp;
			var tpl = '<div class="weui_dialog_alert" id="'+id+'" style="display: none;">'+
							'<div class="weui_mask"></div>'+
							'<div class="weui_dialog">'+
								'<div class="weui_dialog_hd"><strong class="weui_dialog_title">{TITLE}</strong></div>'+
								'<div class="weui_dialog_bd">{CONTENT}</div>'+
								'<div class="weui_dialog_ft">'+
									'<a href="javascript:;" class="weui_btn_dialog primary">确定</a>'+
								'</div>'+
							'</div>'+
					   '</div>';
                        title = title || '';
                        content = content || '';
			$("body").append(tpl.replace(/{TITLE}/, title).replace(/{CONTENT}/, content));
			$("#"+id).show();
			$("#"+id).find(".weui_btn_dialog").click(function(){
				$("#"+id).remove();
			})
		},
		confirm : function(content, title, callback) {
			var timestamp = Date.parse(new Date());
			var id = 'weui_dialog_confirm'+timestamp;
			var tpl = '<div class="weui_dialog_confirm" id="'+id+'" style="display: none;">'+
							'<div class="weui_mask"></div>'+
							'<div class="weui_dialog">'+
								'<div class="weui_dialog_hd"><strong class="weui_dialog_title">{TITLE}</strong></div>'+
								'<div class="weui_dialog_bd">{CONTENT}</div>'+
								'<div class="weui_dialog_ft">'+
									'<a href="javascript:;" class="weui_btn_dialog default" data-res="0">取消</a>'+
									'<a href="javascript:;" class="weui_btn_dialog primary" data-res="1">确定</a>'+
								'</div>'+
							'</div>'+
					   '</div>';
                        title = title || '';
                        content = content || '';
			$("body").append(tpl.replace(/{TITLE}/, title).replace(/{CONTENT}/, content));
			$("#"+id).show();
			$("#"+id).find(".weui_btn_dialog").click(function(){
				if ($.isFunction(callback)) {
					callback($(this).data('res'))
				}
				$("#"+id).remove();
			})
		}
	};
        window.weui = weui;
        window.dialog = window.weui.dialog;
})(window);