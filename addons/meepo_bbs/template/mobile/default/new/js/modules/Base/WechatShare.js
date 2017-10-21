!function(a, b) {
    a.WechatShare = b()
}(this, function() {
    function a(a, b) {
        var c = Object.keys(b);
        return c.forEach(function(c) {
            a[c] = b[c]
        }),
        a
    }
    function b(b) {
        console.log("weixinshare.js old shareCfg", d),
        a(d, b),
        console.log("weixinshare.js new shareCfg", d),
        loadjs.load("http://res.wx.qq.com/open/js/jweixin-1.0.0.js", function() {
            DB.cgiHttp({
                url: "http://buluo.qq.com/cgi-bin/bar/extra/get_wx_jsapi_signature",
                type: "POST",
                param: {
                    url: encodeURIComponent(location.href.split("#")[0])
                },
                succ: function(a) {
                    console.log("weixinshare.js data", a),
                    window.wx.config({
                        debug: !1,
                        appId: "wxcedea1bc042b00e4",
                        timestamp: a.result.timestamp,
                        nonceStr: a.result.noncestr,
                        signature: a.result.signature,
                        jsApiList: ["onMenuShareTimeline", "onMenuShareAppMessage", "onMenuShareQQ", "onMenuShareQZone"]
                    }),
                    window.wx.ready(function() {
                        function a() {}
                        console.log("验证通过");
                        var b = $.extend({}, d);
                        console.log("shareCfgTimeline", b),
                        b.success = d.handleShareTimelineSuccess || a,
                        b.cancel = d.handleShareTimelineCancel || a,
                        window.wx.onMenuShareTimeline(b);
                        var c = $.extend({}, d);
                        console.log("shareCfgAppMessage", c),
                        c.success = d.handleShareAppMessageSuccess || a,
                        c.cancel = d.handleShareAppMessageCancel || a,
                        window.wx.onMenuShareAppMessage(c);
                        var e = $.extend({}, d);
                        console.log("shareCfgQQ", e),
                        e.success = d.handleShareQQSuccess || a,
                        e.cancel = d.handleShareQQCancel || a,
                        window.wx.onMenuShareQQ(e);
                        var f = $.extend({}, d);
                        console.log("shareCfgQZone", f),
                        f.success = d.handleShareQZoneSuccess || a,
                        f.cancel = d.handleShareQZoneCancel || a,
                        window.wx.onMenuShareQZone(f)
                    }),
                    window.wx.error(function(a) {
                        console.log("验证失败", a)
                    })
                }
            })
        })
    }
    function c(a) {
        a = a ? a : {};
        var c = navigator.userAgent.match(/\bMicroMessenger\/([\d\.]+)/);
        c && b(a)
    }
    var d;
    return d = {
        title: $("meta[itemprop=name]").attr("content"),
        desc: $("meta[itemprop=description]").attr("content"),
        imgUrl: $("meta[itemprop=image]").attr("content"),
        link: window.location.href,
        success: function() {
            console.log("分享成功")
        },
        cancel: function() {
            console.log("分享取消")
        }
    },
    {
        init: c,
        build: b
    }
});