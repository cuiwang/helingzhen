;(function() {
function shihuiShare(){
	if(!window.wx) {
		return;
	}
	// 接口url
	this.api='http://activity.17shihui.com/app.php';
	// 页面当前url
	this.url=encodeURI(window.location.href.split("#")[0]);

	this.deafults={
		debug: false, //调试
		title:'', //标题
		desc:'', //描述
		link:window.location.href, //链接
		callback: function(){}, //分享成功回调函数
		imgUrl:'http://static.17shihui.cn/17shihui/w1.1.50/image/weixin.png' //图片
	}
}

shihuiShare.prototype={
	share: function(params){
		if(!window.wx) {
			return;
		}
		params=params || {};
		for(var i in this.deafults){
			if(typeof params[i] === 'undefined'){
				params[i] = this.deafults[i];
			}
		}
		this.params=params;
		var self=this;
		$.ajax({
			url:self.api,
			data:{"url":self.url},
			type:"post",
			dataType:"json",
			success:function(obj){
				self.doShare(obj);
			},
			error:function(data){
			}
		});
	},
	// 分享
	doShare: function(obj){
		var self=this;
		wx.config({
			debug: self.params.debug, 
			appId: obj.appId, 
			timestamp: obj.timestamp, 
			nonceStr: obj.nonceStr, 
			signature: obj.signature,
			jsApiList: ["onMenuShareTimeline","onMenuShareAppMessage"] 
		});
		wx.ready(function(){
			wx.onMenuShareTimeline({
				title: self.params.title, // 分享标题
				link: encodeURI(self.params.link), // 分享链接
				imgUrl: self.params.imgUrl, // 分享图标
				success: function () { 
					self.params.callback&&self.params.callback();
				},
				cancel: function () { 
			    	}
			});
			wx.onMenuShareAppMessage({
				title: self.params.title, // 分享标题
				desc: self.params.desc, // 分享描述
				link: encodeURI(self.params.link), // 分享链接
				imgUrl: self.params.imgUrl, // 分享图标
				type: '', // 分享类型,music、video或link，不填默认为link
				dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
				success: function () { 
					self.params.callback&&self.params.callback();
				},
				cancel: function () { 
				}
			});
		});
	}
}
window.WxApi = new shihuiShare();

})();