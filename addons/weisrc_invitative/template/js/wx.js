(function() {
	var onBridgeReady = function() {
		if (wxSetting) {
			if (wxSetting.hideToolbar) {
				WeixinJSBridge.invoke("hideToolbar");
			}

			if (wxSetting.hideOptionMenu) {
				WeixinJSBridge.invoke("hideOptionMenu");
			}
			if (wxSetting.share) {
				if (wxSetting.share.send2Friend) {
					// 发送给好友;
					WeixinJSBridge.on('menu:share:appmessage', function(argv) {
						var data = wxSetting.share.send2Friend;
						WeixinJSBridge.invoke('sendAppMessage', {
							"img_url" : data.img,
							"img_width" : "640",
							"img_height" : "640",
							"link" : data.link,
							"desc" : data.content,
							"title" : data.title
						}, function(res) {
							if (_paq) {
								_paq.push(['trackPageView', '[分享给朋友]:' + data.title]);
								_paq.push(['setCustomVariable', 1, '分享', '好友', 'visit']);
							}
						});
					});
				}

				if (wxSetting.share.share2Friend) {
					// 分享到朋友圈;
					WeixinJSBridge.on('menu:share:timeline', function(argv) {
						//分享到朋友圈
						var data = wxSetting.share.share2Friend;
						WeixinJSBridge.invoke('shareTimeline', {
							"img_url" : data.img,
							"img_width" : "640",
							"img_height" : "640",
							"link" : data.link,
							"desc" : data.title,
							"title" : data.title
						}, function(res) {
							if (_paq) {
								_paq.push(['trackPageView', '[分享到朋友圈]:' + data.title]);
								_paq.push(['setCustomVariable', 1, '分享', '朋友圈', 'visit']);
							}
						});

					});

				}

				if (wxSetting.share.share2QQBlog) {
					//分享到微博
					WeixinJSBridge.on("menu:share:weibo", function() {
						var data = wxSetting.share.share2QQBlog;
						WeixinJSBridge.invoke("shareWeibo", {
							"content" : data.content + data.link,
							"url" : data.link
						}, function(res) {
							if (_paq) {
								_paq.push(['trackPageView', '[分享到微博]:' + data.content]);
								_paq.push(['setCustomVariable', 1, '分享', '微博', 'visit']);
							}
						});
					});

				}

				if (wxSetting.share.share2Email) {
					//分享到微博
					WeixinJSBridge.on("menu:share:email", function() {
						var data = wxSetting.share.share2Email;
						WeixinJSBridge.invoke("sendEmail", {
							"content" : data.content + data.link,
							"title" : data.title
						}, function(res) {
							if (_paq) {
								_paq.push(['trackPageView', '[分享到邮件]:' + data.content]);
								_paq.push(['setCustomVariable', 1, '分享', '邮件', 'visit']);
							}
						});
					});

				}
			}
		}
	};
	if (document.addEventListener) {
		document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
	} else if (document.attachEvent) {
		document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
		document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
	}
})();
