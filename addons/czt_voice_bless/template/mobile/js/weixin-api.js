(function(e) {
    "use strict";
    var t = {
        wxData: !1,
        isWeixin: function() {
            var e = navigator.userAgent.toLowerCase();
            return /micromessenger/.test(e) ? !0 : !1
        },
        queryUrl: function(url, key) {
            url = url.replace(/^[^?=]*\?/ig, '').split('#')[0]; //去除网址与hash信息
            var json = {};
            //考虑到key中可能有特殊符号如“[].”等，而[]却有是否被编码的可能，所以，牺牲效率以求严谨，就算传了key参数，也是全部解析url。
            url.replace(/(^|&)([^&=]+)=([^&]*)/g, function (a, b, key, value) {
              //对url这样不可信的内容进行decode，可能会抛异常，try一下；另外为了得到最合适的结果，这里要分别try
              try {
                  key = decodeURIComponent(key);
              } catch (e) {}

              try {
                  value = decodeURIComponent(value);
              } catch (e) {}

              if (!(key in json)) {
                  json[key] = /\[\]$/.test(key) ? [value] : value; //如果参数名以[]结尾，则当作数组
              } else if (json[key] instanceof Array) {
                  json[key].push(value);
              } else {
                  json[key] = [json[key], value];
              }
            });
            return key ? json[key] : json;
        },
        setData: function(e) {
            this.wxData = e, this.setWXShareCallback()
        },
        share: function(t) {
            t ? this.setData(t) : t = this.wxData;
            if (e.AndroidWebview) AndroidWebview.simpleShareToSNS ? AndroidWebview.simpleShareToSNS(JSON.stringify({
                "default": t.text + " " + t.link,
                weixin: t.text,
                weixinUrl: t.link,
                weixinThumbnailUrl: t.img,
                weixinTitle: t.title,
                needMonitor: !0
            })) : AndroidWebview.shareToSNS(JSON.stringify({
                content: t.text + " " + t.link,
                imgUrl: t.img
            }));
            else if (!this.isWeixin()) {
                var n = "http://service.weibo.com/share/share.php?title=" + t.title + "&url=" + t.link + "&pic=" + t.img + "&appkey=4051837633&searchPic=false";
                e.location.href = n
            }
        },
        setWXShareCallback: function() {
            var e = this;
            wx.onMenuShareTimeline({
                title: e.wxData.text,
                link: e.wxData.link,
                imgUrl: e.wxData.img,
                success: e.wxData.success || function() {},
                cancel: e.wxData.cancel || function() {}
            }), wx.onMenuShareAppMessage({
                title: e.wxData.title,
                desc: e.wxData.text,
                link: e.wxData.link,
                imgUrl: e.wxData.img,
                trigger: e.wxData.trigger || function(e) {},
                success: e.wxData.success || function(e) {},
                cancel: e.wxData.cancel || function(e) {},
                fail: e.wxData.fail || function(e) {}
            })
        },
        init: function(e, t) {
            var n = this;
            t && (this.wxData = t), $.ajax({
                url: "/wechat/jsapi",
                data: {
                    url: location.href.split("#")[0],
                },
                dataType: "jsonp",
                success: function(e) {
                    n.bindWxEvent(e);
                }
            })
        },
        bindWxEvent: function(e) {
            this.appid = e.appId;
            wx.config({
                debug: !1,
                appId: e.appId,
                timestamp: e.timestamp,
                nonceStr: e.nonceStr,
                signature: e.signature,
                jsApiList: ["checkJsApi", "onMenuShareTimeline", "onMenuShareAppMessage", "onMenuShareQQ", "onMenuShareWeibo", "hideMenuItems", "showMenuItems", "hideAllNonBaseMenuItem", "showAllNonBaseMenuItem", "scanQRCode", "startRecord", "stopRecord", "onVoiceRecordEnd", "translateVoice", "chooseImage", "previewImage", "uploadImage", "downloadImage","uploadVoice","playVoice"]
            });
            var t = this;
            wx.ready(function() {
                t.wxData && t.setWXShareCallback()
            })
        },
        getUserInfo: function(callback){
            $.ajax({
                type: 'GET',
                url: '/wechat/jsuser',
                dataType: 'json',
                timeout: 15000,
                success: function(res){
                    if(!res.err){
                        callback(null, res);
                    }else{
                        callback(res, null);
                    }
                }
            });
        }
    };
    e.WeixinHelper = t, e.AndroidWebview && (e.AndroidWebview_onShareDone = function(e) {
        e == "true" && t.wxData && t.wxData.success && t.wxData.success()
    })
})(this);