!function(a, b) {
    return "object" == typeof module && "object" == typeof module.exports ? void (module.exports = b(a, {})) : void ("function" == typeof define && (define.amd || define.cmd) ? define([], function(c, d, e) {
        return b(a, d)
    }) : a.reportCgi = b(a, {}))
}(this, function(a, b) {
    var c = {}
      , d = b || {};
    c.keyList = ["domain", "cgi", "type", "code", "time", "rate", "uin", "apn", "device", "signalStrength", "expansion1", "expansion2", "expansion3", "data", "platform"],
    c.apn = null ,
    c.device = "",
    c.signalStrength = "";
    var e = window.mqq;
    return d.report = function(a) {
        if (!a || !a.url)
            return void console.log("cgi retrun code report param error ");
        if (a.rate = a.rate || 1,
        /^(([^:\/?#]+):)?(\/\/([^\/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?$/.test(decodeURIComponent(a.url))) {
            if (Math.random() < 1 / a.rate) {
                var b = RegExp.$4 || ""
                  , d = RegExp.$5 || "";
                RegExp.$6 || "";
                e && e.device && e.device.getNetworkType && e.support("mqq.device.getNetworkType") && !c.apn ? e.device.getNetworkType(function(e) {
                    c.apn = e || "unknown",
                    c.send.call(this, {
                        domain: b,
                        cgi: d || "",
                        type: a.type || 0,
                        code: a.code || 0,
                        time: a.time || 0,
                        apn: c.apn || "",
                        device: c.device || "",
                        signalStrength: c.signalStrength || "",
                        expansion1: a.expansion1 || "",
                        expansion2: a.expansion2 || "",
                        expansion3: a.expansion3 || "",
                        data: a.data || "",
                        platform: a.platform || "",
                        rate: a.rate,
                        uin: a.uin || 0
                    })
                }) : c.send.call(this, {
                    domain: b,
                    cgi: d || "",
                    type: a.type || 0,
                    code: a.code || 0,
                    time: a.time || 0,
                    apn: c.apn || "",
                    device: c.device || "",
                    signalStrength: c.signalStrength || "",
                    expansion1: a.expansion1 || "",
                    expansion2: a.expansion2 || "",
                    expansion3: a.expansion3 || "",
                    data: a.data || "",
                    platform: a.platform || "",
                    rate: a.rate,
                    uin: a.uin || 0
                })
            }
            return !0
        }
        return !1
    }
    ,
    c.cache = {},
    c.send = function(a) {
        var b = c.cache
          , d = 2e3;
        if (b.mapping || (b.mapping = {}),
        a) {
            var f = JSON.stringify({
                domain: a.domain,
                uin: a.uin,
                rate: a.rate
            });
            return b.mapping[f] || (b.mapping[f] = []),
            b.mapping[f].push(a),
            b.timer && clearTimeout(b.timer),
            b.timer = setTimeout(function() {
                c.send.call(this)
            }, d),
            !1
        }
        for (var f in b.mapping)
            if (b.mapping.hasOwnProperty(f))
                if (b.mapping[f] && b.mapping[f].length > 0) {
                    for (var a, g = {
                        key: c.keyList.join(",")
                    }, h = b.mapping[f].splice(0, 10), i = 0, j = h.length; a = h[i],
                    j > i; i++)
                        for (var k, l = 0, m = c.keyList.length; k = c.keyList[l],
                        m > l; l++)
                            g[[i + 1, l + 1].join("_")] = a[k];
                    var n = [];
                    for (var i in g)
                        n.push(i + "=" + encodeURIComponent(g[i]));
                    var o = n.join("&");
                    if (e && e.data && e.data.pbReport && e.support && e.support("mqq.data.pbReport")) {
                        var p = window.navigator.userAgent
                          , q = window.location.host
                          , r = {
                            d: o,
                            h: q,
                            ua: p
                        };
                        e.data.pbReport("104", JSON.stringify(r))
                    } else
                        c.httpSend(o)
                } else
                    delete b.mapping[f];
        return !0
    }
    ,
    c.httpSend = function(a) {
        window._cgiReportStack || (window._cgiReportStack = []);
        var b = new Image;
        window._cgiReportStack.push(b),
        b.src = "http://c.isdspeed.qq.com/code.cgi?" + a
    }
    ,
    d
});