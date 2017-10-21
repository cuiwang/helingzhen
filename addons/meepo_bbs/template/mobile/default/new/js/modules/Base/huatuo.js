!function() {
    function a(a, b, c) {
        if ("number" == typeof a)
            c(a);
        else if ("allWithCache" === a)
            EnvInfo.getNetwork(function(a) {
                var d = "4g" === a || "unknow" === a ? "wifi" : a
                  , e = EnvInfo.isOffline
                  , f = i[d][e ? "offline" : "online"][b ? "cache" : "nocache"];
                c(f)
            });
        else if ("all" === a)
            EnvInfo.getNetwork(function(a) {
                var b = "4g" === a || "unknow" === a ? "wifi" : a
                  , d = EnvInfo.isOffline
                  , e = k[b][d ? "offline" : "online"];
                c(e)
            });
        else if ("network" === a)
            EnvInfo.getNetwork(function(a) {
                var b = "4g" === a || "unknow" === a ? "wifi" : a
                  , d = j[b];
                c(d)
            });
        else {
            var d = EnvInfo.isOffline
              , e = j[d ? "offline" : "online"];
            c(e)
        }
    }
    function b(b, d, e, f, g, i, j, k) {
        var l, m, n, o = f[0];
        m = 1,
        n = f.length;
        var p = {
            flag1: b,
            flag2: d,
            flag3: e
        }
          , q = function(a) {
            var f = [b, d, e].join("-");
            h[f] = Q.mix(h[f] || {}, a),
            i ? c(!0) : Q.tick(c)
        }
        ;
        if ("undefined" == typeof g || null  === g) {
            for (; n > m; m++)
                l = f[m],
                l = l ? l - o : 0,
                l > 0 && (p[m] = l);
            q(p)
        } else
            j ? a(j, k, function(a) {
                p[parseInt(g + a)] = f,
                q(p)
            }) : (p[g] = f,
            q(p))
    }
    function c(a) {
        var b, c, d;
        for (b in h)
            h.hasOwnProperty(b) && (c = h[b],
            d = f + mqq.toQuery(c),
            a ? Q.send(d, null , !0) : Q.send(d));
        h = {}
    }
    function d(a, b, c) {
        return this instanceof d ? (this.f1 = a,
        this.f2 = b,
        this.f3 = c,
        void (this.timing = [])) : new d(a,b,c)
    }
    function e(c, d, e) {
        var f, g = window.webkitPerformance ? window.webkitPerformance : window.msPerformance, h = ["navigationStart", "fetchStart", "connectEnd", "domComplete", "loadEventEnd"], i = e;
        if (g = g ? g : window.performance,
        g && (f = g.timing)) {
            for (var j = [], k = 0, l = h.length; l > k; k++)
                j[k] = f[h[k]];
            a("all", null , function(a) {
                for (var e = [j[0]], f = 0; 4 > f; f++) {
                    for (var g = [], h = 0; 6 > h; h++)
                        h === a ? g.push(j[f + 1]) : g.push(0);
                    e = e.concat(g)
                }
                b(c, d, i, e)
            })
        }
    }
    var f, g, h, i, j;
    f = "http://isdspeed.qq.com/cgi-bin/r.cgi?",
    g = "http://report.huatuo.qq.com/report.cgi?",
    h = {},
    i = {
        "2g": {
            online: {
                nocache: 0,
                cache: 1
            },
            offline: {
                nocache: 2,
                cache: 3
            }
        },
        "3g": {
            online: {
                nocache: 4,
                cache: 5
            },
            offline: {
                nocache: 6,
                cache: 7
            }
        },
        wifi: {
            online: {
                nocache: 8,
                cache: 9
            },
            offline: {
                nocache: 10,
                cache: 11
            }
        }
    };
    var k = {
        "2g": {
            online: 0,
            offline: 1
        },
        "3g": {
            online: 2,
            offline: 3
        },
        wifi: {
            online: 4,
            offline: 5
        }
    };
    j = {
        "2g": 0,
        "3g": 1,
        wifi: 2
    };
    var l = function() {
        function a() {
            var a;
            for (var c in d)
                d.hasOwnProperty(c) && (a = d[c],
                b(a.flag1, a.flag2, a.flag3, a.timeObj),
                delete d[c])
        }
        function b(a, b, c, d) {
            var e, f = {
                appid: 10012
            }, h = {
                flag1: a,
                flag2: b,
                flag3: c
            };
            Q.mix(h, d);
            var i = mqq.toQuery(h);
            f.speedparams = i,
            EnvInfo.getNetwork(function(a) {
                f.apn = a,
                f.platform = $.os.ios ? "ios" : $.os.android ? "android" : "unknown",
                e = g + mqq.toQuery(f),
                Q.send(e)
            })
        }
        var c, d = {};
        return function(b, e, f, g, h) {
            var i = d[b + "_" + e + "_" + f]
              , j = Config.isOffline ? 100 : 500;
            i || (i = d[b + "_" + e + "_" + f] = {
                flag1: b,
                flag2: e,
                flag3: f,
                timeObj: {}
            }),
            i.timeObj[h] = g,
            window.clearTimeout(c),
            c = window.setTimeout(a, j)
        }
    }();
    d.prototype.mark = function(a) {
        return this.timing.push(a || Q.getTimestamp())
    }
    ,
    d.prototype.report = function() {
        b(this.f1, this.f2, this.f3, this.timing)
    }
    ,
    Q.mix(Q, {
        huatuo: l,
        isd: b,
        performance: e,
        speed: d
    })
}();!function() {
    function a(a) {
        if (d[0]) {
            var b = "[" + d + "]"
              , e = c + "monitors=" + b + "&t=" + Q.getTimestamp();
            d = [],
            Q.send(e, a)
        } else
            a && a()
    }
    function b(b, e, f) {
        if (e) {
            var g = c + "monitors=[" + b + "]&t=" + Q.getTimestamp();
            Q.send(g, f, !0)
        } else
            d.push(b),
            Q.tick(a)
    }
    var c = "http://cgi.connect.qq.com/report/report_vm?"
      , d = [];
    Q.mix(Q, {
        monitor: b
    });
    var e = {};
    Q.setMonitorMap = function(a) {
        e = a
    }
    ,
    Q.monitorMap = function(a) {
        e[a] && Q.monitor(e[a])
    }
}()