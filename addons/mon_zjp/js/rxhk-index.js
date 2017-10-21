$(function() {
	try{
	$('.swiper-container,.one-box').height($(document).height());
	var mySwiper = new Swiper('.swiper-container', {
		pagination : '.pagination',
		paginationClickable : true,
		mode : 'vertical'
	})
	}catch (e){
	}
	initWeixin();
});
/**
 * 初始化微信分享
 */
function initWeixin() {
	if (typeof WeixinJSBridge == "undefined") {
		if (document.addEventListener) {
			document.addEventListener('WeixinJSBridgeReady', onBridgeReady,false);
		} else if (document.attachEvent) {
			document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
			document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
		}
	} else {
		onBridgeReady();
	}
}
function onBridgeReady() {
	// 显示右上角按钮
	WeixinJSBridge.call('showOptionMenu');
	// 分享到朋友圈
	WeixinJSBridge.on('menu:share:timeline', function(argv) {
		shareTimeline();
	});
	// 发送给好友
	WeixinJSBridge.on('menu:share:appmessage', function(argv) {
		shareFriend();
	});
	// 分享到微博
	WeixinJSBridge.on('menu:share:weibo', function(argv) {
		shareWeibo();
	});
}
/**
 * 分享到朋友圈
 */
function shareTimeline() {
	WeixinJSBridge.invoke('shareTimeline', {
		"appid" : appid,
		"img_url" : imgUrl,
		"link" : lineLink,
		"desc" : descContent,
		"title" : descContent
	}, function(res) {
		if (res.err_msg == "share_timeline:ok") {
			$.post(_webApp + '/activity/rxhk/shareRecord', {
			}, function(data) {
				
			}, 'json');
		}
	});
}

function shareFriend() {
	WeixinJSBridge.invoke('sendAppMessage', {
		"appid" : appid,
		"img_url" : imgUrl,
		"link" : lineLink,
		"desc" : descContent,
		"title" : descContent
	}, function(res) {
		
	});
}

function shareWeibo() {
	WeixinJSBridge.invoke('shareWeibo', {
		"appid" : appid,
		"img_url" : imgUrl,
		"url" : lineLink,
		"content" : descContent,
		"title" : descContent
	}, function(res) {

	});
}