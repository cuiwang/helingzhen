function wxpershare(shareid) {
	var userid = shareid;
	$.ajax({
		url: "http://weixin.xxsy.net:3000/checksign",
		type: "POST",
		data: {
			url: window.location.href
		},
		success: function(obj) {
			wx.config({
				debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
				appId: 'wx56874ba8a351d76b', // 必填，公众号的唯一标识
				timestamp: obj.timestamp, // 必填，生成签名的时间戳
				nonceStr: obj.nonceStr, // 必填，生成签名的随机串
				signature: obj.signature, // 必填，签名，见附录1
				jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'hideMenuItems', 'showMenuItems'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
			});
			wx.ready(function() {
				//分享到朋友圈
				wx.onMenuShareTimeline({
					title: 'iphone 6s 非我莫属! 亲，请投我一票吧！我的编号是:' + userid, // 分享标题
					link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a1ad2890470d77a&redirect_uri=http%3a%2f%2fweixin.xxsy.net%3a3000%2fsp%2findex.html?numorname=' + userid + '&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect', // 分享链接
					imgUrl: 'http://weixin.xxsy.net:3000/img/sp/pic.jpg', // 分享图标
					success: function() {
						// 用户确认分享后执行的回调函数
					},
					cancel: function() {}
				});
				wx.onMenuShareAppMessage({
					title: 'iphone 6s 非我莫属! 亲，请投我一票吧！我的编号是:' + userid, // 分享标题
					desc: '我得不得iphone6s就看你啦(来自潇湘书院)', // 分享描述
					link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1a1ad2890470d77a&redirect_uri=http%3a%2f%2fweixin.xxsy.net%3a3000%2fsp%2findex.html?numorname=' + userid + '&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect', // 分享链接
					imgUrl: 'http://weixin.xxsy.net:3000/img/sp/pic.jpg', // 分享图标
					type: 'link', // 分享类型,music、video或link，不填默认为link
					dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
					success: function() {
						// 用户确认分享后执行的回调函数
					},
					cancel: function() {
						// 用户取消分享后执行的回调函数
					}
				});
				wx.onMenuShareQQ({
					title: 'iphone 6s 非我莫属! 亲，请投我一票吧！我的编号是:' + userid, // 分享标题
					desc: '我得不得iphone6s就看你啦(来自潇湘书院)', // 分享描述
					link: 'http://weixin.xxsy.net:3000/sp/index.html?numorname=' + userid , // 分享链接
					imgUrl: 'http://weixin.xxsy.net:3000/img/sp/pic.jpg', // 分享图标
					success: function() {
						// 用户确认分享后执行的回调函数
					},
					cancel: function() {
						// 用户取消分享后执行的回调函数
					}
				});


				//隐藏按钮
				wx.hideMenuItems({
					menuList: ["menuItem:exposeArticle", "menuItem:refresh", "menuItem:setFont", "menuItem:copyUrl", "menuItem:readMode", "menuItem:delete", "menuItem:editTag", "menuItem:share:email", "menuItem:share:facebook", "menuItem:share:QZone", "menuItem:favorite", "menuItem:openWithQQBrowser", "menuItem:openWithSafari"] // 要隐藏的菜单项，只能隐藏“传播类”和“保护类”按钮，所有menu项见附录3
				});
				//显示按钮
				wx.showMenuItems({
					menuList: ["menuItem:profile", "menuItem:addContact"] // 要显示的菜单项，所有menu项见附录3
				});
			});

		}
	});

}

function getQueryString(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	if (r != null) return decodeURI(r[2]); //unescape(r[2]);
	return null;
}