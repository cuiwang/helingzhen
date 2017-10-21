!function() {
    var a = "http://wspeed.qq.com/w.cgi?"
      , b = 1000212
      , c = []
      , d = navigator.userAgent.indexOf("MQQBrowser") > -1 ? "_X5" : ""
      , e = function(a, b, d, e) {
        a = a.replace("http://buluo.qq.com/cgi-bin", ""),
        c.push([a, b, d]),
        e ? f(b, !0) : Q.tick(function() {
            f(b)
        })
    }
      , f = function(e, f) {
        var g = {}
          , h = EnvInfo.getVersion();
        if (0 === e) {
            g.frequency = 20;
            var i = Math.floor(100 * Math.random() + 1);
            if (i > 5)
                return
        } else
            g.frequency = 1;
        g.appid = b,
        g.key = ["commandid", "resultcode", "tmcost"].join(","),
        c.forEach(function(a, b) {
            g[b + 1 + "_1"] = a[0],
            g[b + 1 + "_2"] = a[1],
            g[b + 1 + "_3"] = a[2]
        }),
        "PC_MQ_BULUO" === h && (mqq.QQVersion = ""),
        g.releaseversion = h + "_" + mqq.QQVersion + d,
        g.touin = EnvInfo.uin || null ,
        g.t = Q.getTimestamp(),
        c = [],
        f ? Q.send(a + mqq.toQuery(g), null , !0) : Q.send(a + mqq.toQuery(g))
    }
    ;
    Q.mix(Q, {
        mmReport: e
    })
}();