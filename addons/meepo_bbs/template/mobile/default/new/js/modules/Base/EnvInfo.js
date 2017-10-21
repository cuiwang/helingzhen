!function() {
    var a, b, c = window;
    b = c.Q = {},
    a = c.EnvInfo = {
        init: function() {
            this.isAndroid = $.os.android,
            this.isIOS = $.os.ios,
            this.isOffline = Config.isOffline,
            this.uin = Login.getUin(),
            this.getVersion = function() {
                var a = "";
                return a = this.isIOS ? "IPH" : this.isAndroid ? "AND" : "PC",
                a + "_MQ_BULUO"
            }
        },
        getNetwork: function(c) {
            if (($.os.ios || $.os.android) && mqq.compare("4.6") >= 0)
                a.network ? c(a.network) : mqq.device.getNetworkType(function(d) {
                    var e = {
                        "-1": "unknow",
                        1: "wifi",
                        2: "2g",
                        3: "3g",
                        4: "4g"
                    };
                    a.network = e[d] || "unknow",
                    c(a.network),
                    -1 === d && b.monitor(2064168)
                });
            else {
                var d = {
                    0: "unknow",
                    2: "wifi",
                    3: "2g",
                    4: "3g"
                };
                c(navigator.connection && navigator.connection.type ? d[navigator.connection.type] || "unknow" : "unknow")
            }
        }
    },
    a.init(),
    b.mix = function(a, b, c, d) {
        if (!b || !a)
            return a;
        void 0 === c && (c = !0);
        var e, f = {};
        if (d && (e = d.length))
            for (var g = 0; e > g; g++)
                f[d[g]] = !0;
        for (var h in b)
            (!c || h in f) && h in a || (a[h] = b[h]);
        return a
    }
    ,
    b.getTimestamp = function() {
        return +new Date
    }
    ,
    b.template = function(a, b) {
        return a.replace(/\$\{(\w+)\}/g, function(a, c) {
            return b[c] ? b[c] : ""
        })
    }
    ,
    b.on = function(a, b, c) {
        a.attachEvent ? a.attachEvent("on" + b, c) : a.addEventListener(b, c, !1)
    }
    ;
    var d = []
      , e = !1;
    b.report = function(a, c) {
        var e, f = d.length;
        if (e = 0,
        c = c || 2e3,
        d[0]) {
            for (var g = 0; f > g; g++) {
                var h = d.shift();
                h && (h.isTicking = !1,
                h(function() {
                    ++e === f && a && !a.isCalled && (a.isCalled = !0,
                    a())
                }))
            }
            a && setTimeout(function() {
                a.isCalled || (a.isCalled = !0,
                a())
            }, c)
        } else
            a && a();
        b.tick.isTicking = !1
    }
    ,
    b.report.delay = 500,
    b.tick = function(a) {
        a.isTicking || (a.isTicking = !0,
        d.push(a)),
        b.tick.isTicking || (setTimeout(b.report, b.report.delay),
        b.tick.isTicking = !0),
        e || (b.on(window, "beforeunload", function() {
            b.report()
        }),
        e = !0)
    }
    ;
    var f = "__tc_global_image_"
      , g = b.getTimestamp();
    b.send = function(a, b, d) {
        g += 1,
        a += "&ts=" + g;
        var e = f + g + (d ? 1 : 0);
        c[e] = new Image,
        b && "function" == typeof b && (c[e].onload = c[e].onerror = function() {
            b();
            try {
                delete c[e]
            } catch (a) {
                c[e] = null 
            }
        }
        ),
        c[e].src = a
    }
}();