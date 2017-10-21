var dataForWeixin = {
	MsgImg : "http://game.2yixin.com/image/1.jpg",
	TLImg : "http://game.2yixin.com/image/1.jpg",
	link : window.location.href,
	title : "优秀微信公众号推荐！！",
	desc : "2014最优秀的微信公众号你关注了吗？",
	callback : function() {
	}
};

(function() {
	var onBridgeReady = function() {
		WeixinJSBridge.on('menu:share:appmessage', function(argv) {
			WeixinJSBridge.invoke('sendAppMessage', {
				"img_url" : dataForWeixin.MsgImg,
				"img_width" : "120",
				"img_height" : "120",
				"link" : dataForWeixin.link,
				"desc" : dataForWeixin.desc,
				"title" : dataForWeixin.title
			}, function(res) {
				(dataForWeixin.callback)();
			});
		});

		WeixinJSBridge.on('menu:share:timeline', function(argv) {
			(dataForWeixin.callback)();
			WeixinJSBridge.invoke('shareTimeline', {
				"img_url" : dataForWeixin.TLImg,
				"img_width" : "120",
				"img_height" : "120",
				"link" : dataForWeixin.link,
				"desc" : dataForWeixin.desc,
				"title" : dataForWeixin.title
			}, function(res) {
			});
		});

		WeixinJSBridge.on('menu:share:weibo', function(argv) {
			WeixinJSBridge.invoke('shareWeibo', {
				"content" : dataForWeixin.title,
				"url" : dataForWeixin.link
			}, function(res) {
				(dataForWeixin.callback)();
			});
		});

		WeixinJSBridge.on('menu:share:facebook', function(argv) {
			(dataForWeixin.callback)();
			WeixinJSBridge.invoke('shareFB', {
				"img_url" : dataForWeixin.MsgImg,
				"img_width" : "180",
				"img_height" : "180",
				"link" : dataForWeixin.link,
				"desc" : dataForWeixin.desc,
				"title" : dataForWeixin.title
			}, function(res) {
			});
		});

	};

	if (document.addEventListener) {
		document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
	} else if (document.attachEvent) {
		document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
		document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
	}
})();