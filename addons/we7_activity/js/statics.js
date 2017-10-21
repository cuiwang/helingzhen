$(function(){
	/*
	analyse();  //发送页面统计
	if(RegExp("MicroMessenger").test(navigator.userAgent)){
		//get_wx_msg();
	}*/
});

/**
 * 发送页面统计
 */
function analyse(){
	/*
	var channel_id = location.search.substr(location.search.indexOf("channel=") + 8);
	channel_id= channel_id.match(/^\d+/) ; 
	if (!channel_id || isNaN(channel_id) || channel_id<0) {
		channel_id = 1;
	}
	var activity_id = $('#activity_id').val();
	var url = "/meeting/analyse/" + activity_id + "?channel="+channel_id;
	$.post(url);
	*/
}

/**
 * 获得微信授权信息
 */
function get_wx_msg(){
	var activity_id = $('#activity_id').val();
	var url = "/meeting/wx_auth/"+activity_id;
	//var code = $('#wxcode').val();
	var btn_text = $('.u-sigeUp a:first').text();
	//$('.u-sigeUp a').html('<span class="authing">正在获取授权信息，请稍候<span>.</span></span>');
	$('.u-sigeUp').css('pointer-events', 'none');
	var code = getPar('code');
	var authing = setInterval(function(){
		var len = $('span.authing span').html().length;
		var html = '.';
		if (len == 1) {
			html = '..';
		} if (len == 2) {
			html = '...';
		}
		$('span.authing span').html(html);
	},600);
	$.post(url,{code:code},function(msg){
		clearInterval(authing);
		$('.u-sigeUp a').html(btn_text);
		$('.u-sigeUp').css('pointer-events', 'auto');
		
		$('#check_img').val(msg.img);
		$('#fakeid').val(msg.fakeid);
		if (msg.fakeid == 0 || msg.fakeid == undefined) {
			var link = window.location.href;
			link = link.replace(/([&\?])code=[\d\w]+&?/,"$1");
			link = link.replace('&weixin.qq.com=', '');
			link = link.replace('#wechat_webview_type=1', '');
			//window.open(link);
			window.history.pushState(null, 0, link);
			document.location.reload();
		}
		$('input[name="fakeid"]').length>0?$('input[name="fakeid"]').val(msg.fakeid):'';
		
		//已经报名，按钮变样式
		if($('.u-sigeUp').length>0){
			var valSigeUp = $('.u-sigeUp').attr('data-switch'); 
			if($('#check_img').val()){
				// 提交成功，报名按钮变样式
				$('.u-sigeUp').addClass('success');
				$('.u-sigeUp a').html('已报名，查看');
			}
			if(valSigeUp == 'false'){
				$('.u-sigeUp').addClass('success');
			}
		}
	},'json');
}

function getPar(par){
	var local_url = document.location.href;
	//获取要取得的get参数位置
	var get = local_url.indexOf(par +"=");
	if(get == -1){
		return false;
	}
	//截取字符串
	var get_par = local_url.slice(par.length + get + 1);
	//判断截取后的字符串是否还有其他get参数
	var nextPar = get_par.indexOf("&");
	if(nextPar != -1){
		get_par = get_par.slice(0, nextPar);
	}
	return get_par;
}