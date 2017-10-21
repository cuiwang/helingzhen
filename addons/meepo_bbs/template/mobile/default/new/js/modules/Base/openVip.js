!function(a, b) {
    window[a] = b()
}("XYPAY", function() {
    var a = null 
      , b = function(b) {
        var c = localStorage.getItem("__payment_result");
        try {
            c = JSON.parse(c)
        } catch (d) {
            c = ""
        }
        b.hidden === !1 && c && $.isPlainObject(c) && (localStorage.setItem("__payment_result", ""),
        a && a(c))
    }
    ;
    return {
        openVip: function(c, d) {
            $.isFunction(c) && (d = c,c = void 0);
            var e = c && c.aid || "xylm.pingtai.client.qianbao.kaitong"
              , f = encodeURIComponent(c && c.from || "")
              , g = encodeURIComponent(c && c.privilege || "");
            "ios" === c.device ? (a = d,
            mqq.ui.openUrl({
                url: "http://buluo.qq.com/xylm/business/pay/index.html?_wv=1027&_bid=2186&aid=" + e + "&from=" + f + "&privilege=" + g,
                target: 1
            }),
            mqq.removeEventListener("qbrowserVisibilityChange", b),
            setTimeout(function() {
                mqq.addEventListener("qbrowserVisibilityChange", b)
            }, 100)) : loadjs.load("http://s1.url.cn/qqun/xiaoqu/buluo/xylm/business/common/lazy/payment.js", function() {
                Pay.openService({
                    aid: e,
                    type: "xyvip",
                    month: c && c.month || "3",
                    onPayCallback: function(a) {
                        d && d(a)
                    }
                })
            })
        }
    }
});