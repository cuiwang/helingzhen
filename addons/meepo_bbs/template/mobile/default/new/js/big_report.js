!function(a) {
    function b(d) {
        if (c[d])
            return c[d].exports;
        var e = c[d] = {
            exports: {},
            id: d,
            loaded: !1
        };
        return a[d].call(e.exports, e, e.exports, b),
        e.loaded = !0,
        e.exports
    }
    var c = {};
    return b.m = a,
    b.c = c,
    b.p = "",
    b(0)
}([function(a, b, c) {
    function d(a) {
        var b = a && a.constructor.toString();
        return b ? b.substring(9, b.indexOf("(")) : ""
    }
    function e(a, b) {
        var c;
        if (a && b)
            for (c in a)
                a.hasOwnProperty(c) && b(c)
    }
    function f(a, b) {
        var c, f = i.getObj(a).reportObj, g = d(f), j = function(b, c) {
            var d, g = {
                sourceKey: a,
                fname: b
            }, j = "original_" + b, k = i.getObj(a).imParamPos, l = (i.getObj(a).callbackParamPos,
            i.getObj(a).urlParamPos), m = {};
            return f[j] = c,
            e(i.getObj(a).platDic, function(b) {
                var c = i.getObj(a).platDic[b];
                c.name = b,
                m[c.url] = c
            }),
            d = function() {
                var a, b, c, d, e, i, n = !1, o = window.location.href;
                if (!l || !arguments[l - 1])
                    return void f[j].apply(f, arguments);
                if (b = arguments[l - 1],
                c = b.indexOf("?") > 0 ? b.indexOf("?") : b.length,
                a = b.substring(0, c),
                !m[a])
                    return void f[j].apply(f, arguments);
                if ((0 === o.indexOf("http://buluo.qq.com/node/page/node-cli/app/barinfo") || 0 === o.indexOf("http://buluo.qq.com/mobile/bar_rank.html") || 0 === o.indexOf("http://buluo.qq.com/mobile/personal.html")) && (n = !0),
                d = m[a].forceReportPrevfixs,
                e = m[a].excludeReportPrevfixs,
                h.isRetry || !n)
                    f[j].apply(f, arguments);
                else if (k && arguments[k - 1]) {
                    if (d)
                        for (i = d.length - 1; i >= 0; i--)
                            if (b.indexOf(d[i]) >= 0)
                                return void h.report(g, arguments, m[a]);
                    f[j].apply(f, arguments)
                } else {
                    if (e)
                        for (i = e.length - 1; i >= 0; i--)
                            if (b.indexOf(e[i]) >= 0)
                                return void f[j].apply(f, arguments);
                    h.report(g, arguments, m[a])
                }
            }
        }
        ;
        "Object" === g && "Function" === d(f[b]) && (c = f[b],
        f[b] = j(b, c))
    }
    var g = c(8)
      , h = c(9)
      , i = c(12);
    e(g.funcConfigDic, function(a) {
        var b = i.addObj(g.funcConfigDic[a]);
        f(b, a)
    }),
    window.use_big_report = !0
}
, , , , , , , , function(a, b) {
    var c = {};
    c.funcConfigDic = {
        send: {
            reportObj: window.Q,
            imParamPos: 3,
            urlParamPos: 1,
            platDic: {
                tdw: {
                    index: 2,
                    url: "http://buluo.qq.com/cgi-bin/bar/tdw/report",
                    ignoreParams: ["t"],
                    forceReportPrevfixs: ["tribe_head_data_test_new1", "person_data_test_new1"]
                },
                monitor: {
                    index: 3,
                    url: "http://cgi.connect.qq.com/report/report_vm",
                    ignoreParams: ["t"]
                },
                huatuo: {
                    index: 1,
                    url: "http://report.huatuo.qq.com/report.cgi",
                    ignoreParams: ["ts"],
                    excludeReportPrevfixs: ["flag1%3D1486%26flag2%3D1%26flag3%3D5"]
                }
            }
        }
    },
    c.appid = 1001,
    c.cgiURL = "http://buluo.qq.com/node/report",
    a.exports = c
}
, function(a, b, c) {
    function d(a) {
        var b, c, d, f = {
            reqs: []
        };
        for (b = a.length - 1; b >= 0; b--)
            f.reqs.push(e(a[b]));
        try {
            a.length <= 1 ? c = "" : (d = k.compressToEncodedURIComponent(JSON.stringify(f)),
            c = j.cgiURL + "?t=" + +new Date + "&q=" + d)
        } catch (g) {
            c = ""
        }
        return c
    }
    function e(a) {
        var b = {};
        return b.index = a.config.index,
        b.args = k.compressToEncodedURIComponent(a.original_args[0].replace(a.config.url + "?", "")),
        b
    }
    function f() {
        var a, b, c = i.getInBatch(), e = "", j = function() {
            var a, b, d, e;
            for (h.isRetry = !0,
            a = c.length - 1; a >= 0; a--)
                b = c[a].context,
                d = c[a].original_args,
                c[a].retry = c[a].retry ? c[a].retry + 1 : 1,
                d && c[a].retry <= 2 && (e = l.getObj(b.sourceKey).reportObj,
                e && e[b.fname].apply(e, d)),
                i.finishItem(c[a].pos);
            h.isRetry = !1,
            g = setTimeout(function() {
                f()
            }, m())
        }
        ;
        g && (window.clearTimeout(g),
        g = null ),
        c.length > 0 ? (e = d(c),
        e ? (a = new Image,
        a.onerror = function() {
            j()
        }
        ,
        a.onload = function() {
            for (console.info("succ!"),
            b = c.length - 1; b >= 0; b--)
                i.finishItem(c[b].pos);
            g = setTimeout(function() {
                f()
            }, m())
        }
        ,
        a.src = e) : j()) : g = setTimeout(function() {
            f()
        }, m())
    }
    var g, h = {}, i = c(10), j = c(8), k = c(11), l = c(12), m = function() {
        var a = 0
          , b = [1e3, 300, 500, 800, 1e3, 1500, 3e3, 6e3, 1e4];
        return function(c) {
            return c ? a = 0 : a >= b.length - 1 ? a = b.length - 1 : a++,
            b[a]
        }
    }();
    h.isRetry = !1,
    window.setTimeout(function() {
        f()
    }, 1500),
    h.report = function(a, b, c) {
        g && clearTimeout(g),
        g = setTimeout(function() {
            f()
        }, m(!0)),
        i.addToBatch(a, b, c)
    }
    ,
    a.exports = h
}
, function(a, b) {
    function c() {
        var a, b, c = window.localStorage.getItem(e), f = +new Date;
        if (c)
            try {
                c = JSON.parse(c)
            } catch (g) {
                c = []
            }
        else
            c = [];
        for (b = [],
        a = c.length - 1; a >= 0; a--)
            c[a].deleted || (c[a].pending && f - c[a].startTime > 1e4 ? (c[a].startTime = f,
            c[a].pending = !1) : c[a].pending || (c[a].tryTimes = c[a].tryTimes || 0,
            c[a].tryTimes = c[a].tryTimes + 1),
            c[a].tryTimes && c[a].tryTimes < 4 && b.push(c[a]));
        return c = b,
        d(c),
        c
    }
    function d(a) {
        window.localStorage.setItem(e, JSON.stringify(a))
    }
    var e = "report_queue"
      , f = c();
    ret = {},
    ret.getInBatch = function() {
        var a, b, e = [];
        for (f = c(),
        a = f.length - 1; a >= 0; a--)
            b = f[a],
            b.pending || (b.pending = !0,
            b.pos = a,
            b.startTime = +new Date,
            e.push(b));
        return d(f),
        e
    }
    ,
    ret.addToBatch = function(a, b, c) {
        var e;
        try {
            e = Array.prototype.slice.call(b) || []
        } catch (g) {
            e = []
        }
        f.push({
            context: a,
            original_args: e,
            config: c
        }),
        d(f)
    }
    ,
    ret.finishItem = function(a) {
        f[a].deleted = !0,
        d(f)
    }
    ,
    a.exports = ret
}
, function(a, b) {
    var c = function() {
        function a(a, b) {
            if (!e[a]) {
                e[a] = {};
                for (var c = 0; c < a.length; c++)
                    e[a][a.charAt(c)] = c
            }
            return e[a][b]
        }
        var b = String.fromCharCode
          , c = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/="
          , d = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+-$"
          , e = {}
          , f = {
            compressToBase64: function(a) {
                if (null  == a)
                    return "";
                var b = f._compress(a, 6, function(a) {
                    return c.charAt(a)
                });
                switch (b.length % 4) {
                default:
                case 0:
                    return b;
                case 1:
                    return b + "===";
                case 2:
                    return b + "==";
                case 3:
                    return b + "="
                }
            },
            decompressFromBase64: function(b) {
                return null  == b ? "" : "" == b ? null  : f._decompress(b.length, 32, function(d) {
                    return a(c, b.charAt(d))
                })
            },
            compressToUTF16: function(a) {
                return null  == a ? "" : f._compress(a, 15, function(a) {
                    return b(a + 32)
                }) + " "
            },
            decompressFromUTF16: function(a) {
                return null  == a ? "" : "" == a ? null  : f._decompress(a.length, 16384, function(b) {
                    return a.charCodeAt(b) - 32
                })
            },
            compressToUint8Array: function(a) {
                for (var b = f.compress(a), c = new Uint8Array(2 * b.length), d = 0, e = b.length; e > d; d++) {
                    var g = b.charCodeAt(d);
                    c[2 * d] = g >>> 8,
                    c[2 * d + 1] = g % 256
                }
                return c
            },
            decompressFromUint8Array: function(a) {
                if (null  === a || void 0 === a)
                    return f.decompress(a);
                for (var c = new Array(a.length / 2), d = 0, e = c.length; e > d; d++)
                    c[d] = 256 * a[2 * d] + a[2 * d + 1];
                var g = [];
                return c.forEach(function(a) {
                    g.push(b(a))
                }),
                f.decompress(g.join(""))
            },
            compressToEncodedURIComponent: function(a) {
                return null  == a ? "" : f._compress(a, 6, function(a) {
                    return d.charAt(a)
                })
            },
            decompressFromEncodedURIComponent: function(b) {
                return null  == b ? "" : "" == b ? null  : (b = b.replace(/ /g, "+"),
                f._decompress(b.length, 32, function(c) {
                    return a(d, b.charAt(c))
                }))
            },
            compress: function(a) {
                return f._compress(a, 16, function(a) {
                    return b(a)
                })
            },
            _compress: function(a, b, c) {
                if (null  == a)
                    return "";
                var d, e, f, g = {}, h = {}, i = "", j = "", k = "", l = 2, m = 3, n = 2, o = [], p = 0, q = 0;
                for (f = 0; f < a.length; f += 1)
                    if (i = a.charAt(f),
                    Object.prototype.hasOwnProperty.call(g, i) || (g[i] = m++,
                    h[i] = !0),
                    j = k + i,
                    Object.prototype.hasOwnProperty.call(g, j))
                        k = j;
                    else {
                        if (Object.prototype.hasOwnProperty.call(h, k)) {
                            if (k.charCodeAt(0) < 256) {
                                for (d = 0; n > d; d++)
                                    p <<= 1,
                                    q == b - 1 ? (q = 0,
                                    o.push(c(p)),
                                    p = 0) : q++;
                                for (e = k.charCodeAt(0),
                                d = 0; 8 > d; d++)
                                    p = p << 1 | 1 & e,
                                    q == b - 1 ? (q = 0,
                                    o.push(c(p)),
                                    p = 0) : q++,
                                    e >>= 1
                            } else {
                                for (e = 1,
                                d = 0; n > d; d++)
                                    p = p << 1 | e,
                                    q == b - 1 ? (q = 0,
                                    o.push(c(p)),
                                    p = 0) : q++,
                                    e = 0;
                                for (e = k.charCodeAt(0),
                                d = 0; 16 > d; d++)
                                    p = p << 1 | 1 & e,
                                    q == b - 1 ? (q = 0,
                                    o.push(c(p)),
                                    p = 0) : q++,
                                    e >>= 1
                            }
                            l--,
                            0 == l && (l = Math.pow(2, n),
                            n++),
                            delete h[k]
                        } else
                            for (e = g[k],
                            d = 0; n > d; d++)
                                p = p << 1 | 1 & e,
                                q == b - 1 ? (q = 0,
                                o.push(c(p)),
                                p = 0) : q++,
                                e >>= 1;
                        l--,
                        0 == l && (l = Math.pow(2, n),
                        n++),
                        g[j] = m++,
                        k = String(i)
                    }
                if ("" !== k) {
                    if (Object.prototype.hasOwnProperty.call(h, k)) {
                        if (k.charCodeAt(0) < 256) {
                            for (d = 0; n > d; d++)
                                p <<= 1,
                                q == b - 1 ? (q = 0,
                                o.push(c(p)),
                                p = 0) : q++;
                            for (e = k.charCodeAt(0),
                            d = 0; 8 > d; d++)
                                p = p << 1 | 1 & e,
                                q == b - 1 ? (q = 0,
                                o.push(c(p)),
                                p = 0) : q++,
                                e >>= 1
                        } else {
                            for (e = 1,
                            d = 0; n > d; d++)
                                p = p << 1 | e,
                                q == b - 1 ? (q = 0,
                                o.push(c(p)),
                                p = 0) : q++,
                                e = 0;
                            for (e = k.charCodeAt(0),
                            d = 0; 16 > d; d++)
                                p = p << 1 | 1 & e,
                                q == b - 1 ? (q = 0,
                                o.push(c(p)),
                                p = 0) : q++,
                                e >>= 1
                        }
                        l--,
                        0 == l && (l = Math.pow(2, n),
                        n++),
                        delete h[k]
                    } else
                        for (e = g[k],
                        d = 0; n > d; d++)
                            p = p << 1 | 1 & e,
                            q == b - 1 ? (q = 0,
                            o.push(c(p)),
                            p = 0) : q++,
                            e >>= 1;
                    l--,
                    0 == l && (l = Math.pow(2, n),
                    n++)
                }
                for (e = 2,
                d = 0; n > d; d++)
                    p = p << 1 | 1 & e,
                    q == b - 1 ? (q = 0,
                    o.push(c(p)),
                    p = 0) : q++,
                    e >>= 1;
                for (; ; ) {
                    if (p <<= 1,
                    q == b - 1) {
                        o.push(c(p));
                        break
                    }
                    q++
                }
                return o.join("")
            },
            decompress: function(a) {
                return null  == a ? "" : "" == a ? null  : f._decompress(a.length, 32768, function(b) {
                    return a.charCodeAt(b)
                })
            },
            _decompress: function(a, c, d) {
                var e, f, g, h, i, j, k, l, m = [], n = 4, o = 4, p = 3, q = "", r = [], s = {
                    val: d(0),
                    position: c,
                    index: 1
                };
                for (f = 0; 3 > f; f += 1)
                    m[f] = f;
                for (h = 0,
                j = Math.pow(2, 2),
                k = 1; k != j; )
                    i = s.val & s.position,
                    s.position >>= 1,
                    0 == s.position && (s.position = c,
                    s.val = d(s.index++)),
                    h |= (i > 0 ? 1 : 0) * k,
                    k <<= 1;
                switch (e = h) {
                case 0:
                    for (h = 0,
                    j = Math.pow(2, 8),
                    k = 1; k != j; )
                        i = s.val & s.position,
                        s.position >>= 1,
                        0 == s.position && (s.position = c,
                        s.val = d(s.index++)),
                        h |= (i > 0 ? 1 : 0) * k,
                        k <<= 1;
                    l = b(h);
                    break;
                case 1:
                    for (h = 0,
                    j = Math.pow(2, 16),
                    k = 1; k != j; )
                        i = s.val & s.position,
                        s.position >>= 1,
                        0 == s.position && (s.position = c,
                        s.val = d(s.index++)),
                        h |= (i > 0 ? 1 : 0) * k,
                        k <<= 1;
                    l = b(h);
                    break;
                case 2:
                    return ""
                }
                for (m[3] = l,
                g = l,
                r.push(l); ; ) {
                    if (s.index > a)
                        return "";
                    for (h = 0,
                    j = Math.pow(2, p),
                    k = 1; k != j; )
                        i = s.val & s.position,
                        s.position >>= 1,
                        0 == s.position && (s.position = c,
                        s.val = d(s.index++)),
                        h |= (i > 0 ? 1 : 0) * k,
                        k <<= 1;
                    switch (l = h) {
                    case 0:
                        for (h = 0,
                        j = Math.pow(2, 8),
                        k = 1; k != j; )
                            i = s.val & s.position,
                            s.position >>= 1,
                            0 == s.position && (s.position = c,
                            s.val = d(s.index++)),
                            h |= (i > 0 ? 1 : 0) * k,
                            k <<= 1;
                        m[o++] = b(h),
                        l = o - 1,
                        n--;
                        break;
                    case 1:
                        for (h = 0,
                        j = Math.pow(2, 16),
                        k = 1; k != j; )
                            i = s.val & s.position,
                            s.position >>= 1,
                            0 == s.position && (s.position = c,
                            s.val = d(s.index++)),
                            h |= (i > 0 ? 1 : 0) * k,
                            k <<= 1;
                        m[o++] = b(h),
                        l = o - 1,
                        n--;
                        break;
                    case 2:
                        return r.join("")
                    }
                    if (0 == n && (n = Math.pow(2, p),
                    p++),
                    m[l])
                        q = m[l];
                    else {
                        if (l !== o)
                            return null ;
                        q = g + g.charAt(0)
                    }
                    r.push(q),
                    m[o++] = g + q.charAt(0),
                    n--,
                    g = q,
                    0 == n && (n = Math.pow(2, p),
                    p++)
                }
            }
        };
        return f
    }();
    a.exports = c
}
, function(a, b) {
    var c = {}
      , d = {};
    c.getKey = function(a) {
        function b(a) {
            var b, c, d, e = 0;
            if (0 === a.length)
                return e;
            for (b = 0,
            d = a.length; d > b; b++)
                c = a.charCodeAt(b),
                e = (e << 5) - e + c,
                e |= 0;
            return Math.abs(e)
        }
        var c = "";
        return c = "object" == typeof a ? JSON.stringify(a) : JSON.stringify({
            value: a
        }),
        b(c)
    }
    ,
    c.addObj = function(a) {
        var b = c.getKey(a);
        return d[b] = a,
        b
    }
    ,
    c.getObj = function(a) {
        return d[a]
    }
    ,
    a.exports = c
}
]);
