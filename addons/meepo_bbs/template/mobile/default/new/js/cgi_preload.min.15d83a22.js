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
    var d = c(13)
      , e = c(15)
      , f = c(21)
      , g = c(22);
    window.CGI_Preload = a.exports = function() {
        var a, b = {}, c = e(d);
        return b.reach3G = c.reach3G,
        b.isCacheTarget = function() {
            return c.isCacheTarget
        }
        ,
        b.isProviderCacheReady = f.cacheReady,
        b.preload = c.preload,
        b.getData = f.getData,
        b.enable = c.config.enable,
        b.lsEnable = !f.noLocalStorage,
        c.config.configItem.huatuo && (a = g(c.config.configItem, b),
        b.reportHuatuo = a.reportHuatuo,
        b.reportUsable = a.reportUsable,
        b.reportUsableWithOpen = a.reportUsableWithOpen,
        b.reportOpenWebView = a.reportOpenWebView),
        b
    }()
}
, , , , , , , , , , , , , function(a, b, c) {
    var d = c(14);
    a.exports = function() {
        var a = {
            enable: function() {
                return !mqq || mqq.compare("4.4") < 0 ? !1 : !0
            }(),
            cgiHost: "http://buluo.qq.com",
            configs: [{
                url: "/mobile/detail.html?#bid={{bid}}&pid={{pid}} | /mobile/detail.html?bid={{bid}}&pid={{pid}}",
                huatuo: {
                    online: {
                        tOpenWebViewPreload: "1486-1-11-2",
                        tOpenWebView: "1486-1-11-1",
                        tUsable: {
                            tUseableLS: "1486-1-11-4",
                            tUsable: "1486-1-11-3"
                        },
                        tUsableWithOpen: {
                            LS: "1486-1-11-12",
                            noLS: "1486-1-11-11",
                            noCache: "1486-1-11-15",
                            total: "1486-1-11-5"
                        }
                    },
                    offline: {
                        tOpenWebViewPreload: "1486-1-11-7",
                        tOpenWebView: "1486-1-11-6",
                        tUsable: {
                            tUseableLS: "1486-1-11-9",
                            tUsable: "1486-1-11-8"
                        },
                        tUsableWithOpen: {
                            LS: "1486-1-11-14",
                            noLS: "1486-1-11-13",
                            noCache: "1486-1-11-16",
                            total: "1486-1-11-10"
                        }
                    }
                },
                CGIs: [{
                    url: "/cgi-bin/bar/post/content",
                    type: "POST",
                    getParams: function(a) {
                        return {
                            bid: a.bid,
                            pid: a.pid,
                            barlevel: 1,
                            start: 0,
                            num: 10,
                            src: 1,
                            get_like_ul: 1
                        }
                    }
                }]
            }, {
                url: "/mobile/bar_rank.html?bid={{bid}}",
                huatuo: {
                    online: {
                        tOpenWebViewPreload: "1486-1-9-1",
                        tOpenWebView: "1486-1-9-2",
                        tUsable: {
                            tUseableLS: "1486-1-9-3",
                            tUsable: "1486-1-9-4"
                        },
                        tUsableWithOpen: {
                            LS: "1486-1-9-12",
                            noLS: "1486-1-9-11",
                            noCache: "1486-1-9-15",
                            total: "1486-1-9-5"
                        }
                    },
                    offline: {
                        tOpenWebViewPreload: "1486-1-9-6",
                        tOpenWebView: "1486-1-9-7",
                        tUsable: {
                            tUseableLS: "1486-1-9-8",
                            tUsable: "1486-1-9-9"
                        },
                        tUsableWithOpen: {
                            LS: "1486-1-9-14",
                            noLS: "1486-1-9-13",
                            noCache: "1486-1-9-16",
                            total: "1486-1-9-10"
                        }
                    }
                },
                CGIs: [{
                    url: "/cgi-bin/bar/apply/get_hire_switch",
                    type: "POST",
                    getParams: function(a) {
                        return {
                            bid: a.bid
                        }
                    }
                }, {
                    url: "/cgi-bin/bar/get_bar_list_by_category_with_id_v2",
                    type: "POST",
                    getParams: function(a) {
                        return {
                            bid: a.bid,
                            n: 10,
                            s: 0
                        }
                    }
                }, {
                    url: "/cgi-bin/bar/info_category",
                    type: "POST",
                    getParams: function(a) {
                        return {
                            bid: a.bid,
                            n: 15,
                            s: 0,
                            cateid: 10,
                            gflag: 1,
                            sflag: 0
                        }
                    }
                }]
            }, {
                url: "/mobile/barindex.html?bid={{bid}} | /mobile/barindex.html#bid={{bid}}",
                huatuo: {
                    online: {
                        tOpenWebViewPreload: "1486-1-12-2",
                        tOpenWebView: "1486-1-12-1",
                        tUsable: {
                            tUseableLS: "1486-1-12-4",
                            tUsable: "1486-1-12-3"
                        },
                        tUsableWithOpen: {
                            LS: "1486-1-12-12",
                            noLS: "1486-1-12-11",
                            noCache: "1486-1-12-15",
                            total: "1486-1-12-5"
                        }
                    },
                    offline: {
                        tOpenWebViewPreload: "1486-1-12-7",
                        tOpenWebView: "1486-1-12-6",
                        tUsable: {
                            tUseableLS: "1486-1-12-9",
                            tUsable: "1486-1-12-8"
                        },
                        tUsableWithOpen: {
                            LS: "1486-1-12-14",
                            noLS: "1486-1-12-13",
                            noCache: "1486-1-12-16",
                            total: "1486-1-12-10"
                        }
                    }
                },
                CGIs: [{
                    url: "http://buluo.qq.com/cgi-bin/bar/page",
                    type: "POST",
                    getParams: function(a) {
                        return {
                            bid: a.bid,
                            platform: d.barIndexPlat
                        }
                    }
                }, {
                    url: "http://buluo.qq.com/cgi-bin/bar/post/get_post_by_page",
                    type: "POST",
                    getParams: function(a) {
                        return {
                            bid: a.bid,
                            start: 0,
                            num: 10
                        }
                    }
                }]
            }, {
                url: "/mobile/personal.html#uin={{uin}} | /mobile/personal.html?uin={{uin}}",
                huatuo: {
                    online: {
                        tOpenWebViewPreload: "1486-1-8-2",
                        tOpenWebView: "1486-1-8-1",
                        tUsable: {
                            tUseableLS: "1486-1-8-4",
                            tUsable: "1486-1-8-3"
                        },
                        tUsableWithOpen: {
                            LS: "1486-1-8-12",
                            noLS: "1486-1-8-11",
                            noCache: "1486-1-8-15",
                            total: "1486-1-8-5"
                        }
                    },
                    offline: {
                        tOpenWebViewPreload: "1486-1-8-7",
                        tOpenWebView: "1486-1-8-6",
                        tUsable: {
                            tUseableLS: "1486-1-8-9",
                            tUsable: "1486-1-8-8"
                        },
                        tUsableWithOpen: {
                            LS: "1486-1-8-14",
                            noLS: "1486-1-8-13",
                            noCache: "1486-1-8-16",
                            total: "1486-1-8-10"
                        }
                    }
                },
                CGIs: [{
                    url: "/cgi-bin/bar/card/topic_fix",
                    type: "POST",
                    getParams: function(a) {
                        return {
                            fixtotal: 1,
                            neednum: 3,
                            startnum: 0,
                            type: 1,
                            targetuin: a.uin
                        }
                    }
                }, {
                    url: "/cgi-bin/bar/card/forward",
                    type: "POST",
                    getParams: function(a) {
                        return {
                            fixtotal: 1,
                            neednum: 3,
                            startnum: 0,
                            type: 100,
                            targetuin: a.uin
                        }
                    }
                }, {
                    url: "/cgi-bin/bar/card/merge_top",
                    type: "POST",
                    getParams: function(a) {
                        return {
                            need_bar_num: 5,
                            targetuin: a.uin,
                            plat: d.plat
                        }
                    }
                }, {
                    url: "/cgi-bin/bar/chat/is_ban",
                    type: "POST",
                    getParams: function(a) {
                        return {
                            puin: a.uin
                        }
                    }
                }]
            }]
        };
        return a
    }()
}
, function(a, b) {
    a.exports = function() {
        var a = {};
        return a.getUin = function() {
            return window.Login.getUin()
        }
        ,
        a.plat = function() {
            var a;
            return $.os.ios ? a = 3 : $.os.android && (a = 2),
            a
        }(),
        a.barIndexPlat = function() {
            var a = navigator.userAgent.match(/\/qqdownloader\/(\d+)?/);
            return a ? 1 : 0
        }(),
        a.barHistoryfrom = Util.queryString("historyfrom") || Util.getHash("historyfrom") || "",
        a
    }()
}
, function(a, b, c) {
    var d = c(16)
      , e = c(17)
      , f = c(18)
      , g = c(20)
      , h = window.localStorage;
    a.exports = function(a) {
        function b() {
            mqq && mqq.device && mqq.device.getNetworkInfo(function(a) {
                2 === a.type || -1 === a.type ? h.setItem("reach3G", !1) : h.setItem("reach3G", !0)
            })
        }
        function c() {
            h.setItem("preload_ls_test", (new Date).valueOf())
        }
        function i(a) {
            var b = g.getKey(a.url, a.type);
            g.CGIDic[b] = 1
        }
        function j(a) {
            var b = n(o(a.url).path);
            if (m.config.preConfigList[b] || (m.config.preConfigList[b] = []),
            m.isCacheTarget)
                for (var c = a.CGIs.length - 1; c >= 0; c--)
                    i(a.CGIs[c]);
            m.config.preConfigList[b].push(a)
        }
        function k(a, b) {
            for (var c, d, e = a.CGIs.length - 1; e >= 0; e--)
                d = a.CGIs[e],
                c = g.getKey(d.url, d.type, d.getParams(b)),
                f.load(d, b, c, a.CGIs.length)
        }
        function l() {
            var c, d, e, f, h = window.location.href, i = [];
            for (b(),
            d = a.configs.length - 1; d >= 0; d--)
                if (c = a.configs[d],
                c && c.url && c.url.indexOf("|") > 0) {
                    e = c.url.split("|");
                    for (var k = e.length - 1; k >= 0; k--)
                        f = $.extend({}, c, {
                            url: $.trim(e[k])
                        }),
                        i.push(f)
                } else
                    i.push(c);
            for (a.configs = i,
            d = a.configs.length - 1; d >= 0; d--)
                c = a.configs[d],
                o(h).path === o(c.url).path && (m.isCacheTarget = !0,
                m.config.configItem = c,
                g.config.isCacheTarget = m.isCacheTarget,
                g.config.enable = p),
                j(c)
        }
        var m = {
            reach3G: function() {
                var a = window.localStorage.getItem("reach3G");
                return a = a && "true" === a ? !0 : !1
            },
            isCacheTarget: !1,
            config: {
                enable: a.enable,
                preConfigList: {},
                configItem: {}
            }
        }
          , n = d.getHashCode
          , o = e.getUrlObj
          , p = m.config.enable;
        return l(),
        m.preload = function(a, b) {
            var d = (new Date).valueOf()
              , f = h.getItem("preload_ls_enable");
            if (c(),
            h.removeItem("use_preload"),
            console.info("=======check preload!======="),
            console.info(h.getItem("preload_ls_test")),
            console.info(p),
            !f || d - f > 18e5)
                return console.info("=======skip preload with no local storage======="),
                void h.removeItem("preload_ls_enable");
            if (p && m.reach3G()) {
                var g, i, j, l = o(a), q = n(l.path), r = m.config.preConfigList[q], s = $.extend(l.query, l.hash);
                if (r)
                    for (var t = r.length - 1; t >= 0; t--)
                        if (g = o(r[t].url, s),
                        e.isEqualUrlRule(g, l, b)) {
                            j = !0,
                            i = r[t];
                            break
                        }
                j && (k(i, s),
                h.setItem("use_preload", "1"))
            }
        }
        ,
        m
    }
}
, function(a, b) {
    a.exports = function() {
        var a = {};
        return a.getHashCode = function(a, b) {
            function c(a) {
                var b, d = [], e = {};
                for (b in a)
                    a.hasOwnProperty(b) && !f[b] && ("object" == typeof a[b] && (a[b] = c(a[b])),
                    d.push(b));
                d.sort();
                for (var g = d.length - 1; g >= 0; g--)
                    e[d[g]] = a[d[g]];
                return e
            }
            function d(a) {
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
            var e = ""
              , f = {};
            if (b)
                for (var g = b.length - 1; g >= 0; g--)
                    f[b[g]] = 1;
            return "object" == typeof a ? (a = c(a),
            e = JSON.stringify(a)) : e = JSON.stringify({
                value: a
            }),
            d(e)
        }
        ,
        a.isMatchPairObj = function(a, b, c) {
            var d, e;
            if (!a && !b)
                return !0;
            if (d = Object.keys(a),
            e = Object.keys(b),
            d.length > e.length)
                return !1;
            for (var f = d.length - 1; f >= 0; f--)
                if (c && 0 === a[d[f]].indexOf(c)) {
                    if (!(d[f] in b))
                        return !1
                } else if (b[d[f]] !== a[d[f]])
                    return !1;
            return !0
        }
        ,
        a
    }()
}
, function(a, b, c) {
    var d = c(16);
    a.exports = function() {
        var a = {};
        return a.getUrlObj = function(a, b) {
            var c, d, e, f, g, h = "", i = {}, j = {}, k = "", l = "", m = [], n = "http://", o = {};
            if (a.indexOf("://") > -1 && (n = a.substring(0, a.indexOf("://") + 3),
            l = a.substring(a.indexOf("://") + 3),
            k = l.indexOf("/") > -1 ? l.substring(0, l.indexOf("/")) : l),
            f = k ? a.substring(a.indexOf(k) + k.length) : a,
            h = f.indexOf("?") > -1 ? f.substring(0, f.indexOf("?")) : f.indexOf("#") > -1 ? f.substring(0, f.indexOf("#")) : f,
            g = n + k + h,
            f.indexOf("?") > -1)
                for (l = f.indexOf("#") > -1 ? f.substring(f.indexOf("?") + 1, f.indexOf("#")) : f.substring(f.indexOf("?") + 1),
                m = l.split("&"),
                e = m.length - 1; e >= 0; e--)
                    m[e].indexOf("=") > 0 && (c = m[e].split("=")[0],
                    d = m[e].split("=")[1],
                    d && 0 === d.indexOf("{{") && b && (l = d.substring(d.indexOf("{{") + 2, d.indexOf("}}")),
                    d = b[l]),
                    c && (i[c] = d));
            if (f.indexOf("#") > -1)
                for (l = f.substring(f.indexOf("#") + 1),
                m = l.split("&"),
                e = m.length - 1; e >= 0; e--)
                    c = m[e].split("=")[0],
                    d = m[e].split("=")[1],
                    d && 0 === d.indexOf("{{") && b && (l = d.substring(d.indexOf("{{") + 2, d.indexOf("}}")),
                    d = b[l]),
                    c && (j[c] = d);
            return o.host = k || "buluo.qq.com",
            o.urlstring = g,
            o.hash = j,
            o.path = h,
            o.query = i,
            o.urlstring = g,
            o
        }
        ,
        a.isEqualUrlRule = function(a, b, c, e) {
            if (c)
                for (var f = c.length - 1; f >= 0; f--)
                    delete a[c[f]],
                    delete b[c[f]];
            return console.info("======compare url rule========"),
            console.info(a.hash),
            console.info(b.hash),
            console.info(a.query),
            console.info(b.query),
            a.host !== b.host || a.path !== b.path ? !1 : d.isMatchPairObj(a.hash, b.hash, e) && d.isMatchPairObj(a.query, b.query, e) ? !0 : !1
        }
        ,
        a
    }()
}
, function(a, b, c) {
    var d = c(17)
      , e = c(19)
      , f = c(13)
      , g = window.localStorage;
    a.exports = function() {
        function a() {
            var c = (new Date).valueOf();
            window.mqq && window.mqq.addEventListener && (console.info("========send data========="),
            console.info(k),
            console.info(c - b),
            0 === k && 3e3 >= c - b ? mqq.dispatchEvent("updateCache", {
                data: j
            }, {
                echo: !1,
                broadcast: !0
            }) : window.setTimeout(a, 10))
        }
        var b, c = {}, h = d.getUrlObj, i = function() {
            var a, b, c, d, e = 0;
            return function(f) {
                d = h(window.location.href).path,
                f ? (e--,
                0 === e && (b = (new Date).valueOf(),
                c = b - a,
                console.info("CGI 预加载耗时:"),
                console.info(c))) : (0 === e && (a = (new Date).valueOf()),
                e++)
            }
        }(), j = {}, k = 0, l = !1;
        return window.mqq && window.mqq.addEventListener && (mqq.addEventListener("updateCache", function(a) {
            a = a.data;
            for (var b in a)
                a.hasOwnProperty(b) && (j[b].data = a[b].data,
                j[b].loading = !1)
        }),
        mqq.addEventListener("queryCache", function() {
            console.info("==============query data=============="),
            b = (new Date).valueOf(),
            a()
        })),
        c.getDataByCW = function(a) {
            var b, c = (new Date).valueOf();
            return j[a] ? j[a].data && 0 === j[a].data.retcode ? (b = j[a].data,
            b.loading = !1,
            console.info("=========cross webview cgi preload success=========")) : !j[a].data || 0 === j[a].data.retcode && "retcode" in j[a].data ? c - j[a].cgi_start_time < 3e3 ? window.mqq && window.mqq.dispatchEvent ? (l || (console.info("===========trigger query cache==========="),
            mqq.dispatchEvent("queryCache", {
                data: 1
            }, {
                echo: !1,
                broadcast: !0
            }),
            l = !0),
            b = {
                loading: !0
            }) : b = void 0 : (console.info("=========cross webview cgi preload timeout========="),
            b = void 0) : (console.info("=========cross webview cgi preload recode error========="),
            b = void 0) : (j[a] = {},
            j[a].cgi_start_time = c,
            b = {
                loading: !0
            }),
            b
        }
        ,
        c.getData = function(a) {
            var b, c, d = (new Date).valueOf();
            if (c = g.getItem(a)) {
                try {
                    c = JSON.parse(c),
                    b = c.data
                } catch (e) {
                    b = void 0
                }
                b && 0 === b.retcode && d - c.cgi_preload_time < 3e4 ? (console.info("=========cgi preload success========="),
                g.removeItem(a),
                b.loading = !1) : !b || "retcode" in b && 0 === b.retcode ? d - c.cgi_preload_time >= 3e4 ? (console.info("=========cgi preload expired========="),
                g.removeItem(a),
                b = void 0) : d - c.cgi_start_time < 3e3 ? b = {
                    loading: !0
                } : (console.info("=========cgi preload timeout========="),
                b = void 0) : (console.info("=========cgi preload recode error========="),
                b = void 0)
            }
            return b
        }
        ,
        c.load = function(a, b, c, d) {
            var h = {};
            a.url.indexOf("http://") < 0 && (a.url = f.cgiHost + a.url),
            h.cgi_start_time = (new Date).valueOf(),
            g.setItem(c, JSON.stringify(h)),
            j[c] = {
                cgi_start_time: h.cgi_start_time
            },
            0 === k && (k = d),
            i(0),
            e(a.url, a.getParams(b), function(a) {
                console.info("=======preload request complete========"),
                console.info(c),
                h.cgi_preload_time = (new Date).valueOf(),
                h.data = a,
                j[c].cgi_preload_time = h.cgi_preload_time,
                j[c].data = h.data,
                g.setItem(c, JSON.stringify(h)),
                k--,
                i(1)
            }, a.type)
        }
        ,
        c
    }()
}
, function(a, b) {
    a.exports = function() {
        function a(a) {
            if (!a)
                return "";
            for (var b = 5381, c = 0, d = a.length; d > c; ++c)
                b += (b << 5) + a.charAt(c).charCodeAt();
            return 2147483647 & b
        }
        var b;
        return b = function() {
            return function(b, c, d, e) {
                var f;
                f = mqq && mqq.compare("5.8") > -1 ? "http://buluo.qq.com" : "http://xiaoqu.qq.com",
                b.indexOf("http") < 0 && (b = window.location.origin ? window.location.origin + b : window.location.host ? "http://" + window.location.host + b : f + b),
                $.extend(c, {
                    cgi_preload: 1,
                    bkn: a($.cookie("skey")),
                    r: Math.random()
                });
                var g = {
                    type: e,
                    url: b,
                    data: c,
                    success: function(a) {
                        d && d(a)
                    },
                    error: function(a) {
                        d && d({
                            ec: a.status || -1
                        })
                    }
                };
                0 !== b.indexOf(location.origin) && (g.xhrFields = {
                    withCredentials: !0
                }),
                $.ajax(g)
            }
        }()
    }()
}
, function(a, b, c) {
    var d = c(16)
      , e = c(17)
      , f = d.getHashCode
      , g = c(13);
    a.exports = function() {
        var a = {
            CGIDic: {},
            config: {}
        };
        return a.getKey = function(a, b, c, d) {
            var h, i;
            if (a.indexOf("http://") < 0 && (a = g.cgiHost + a),
            "GET" === b && (a = e.getUrlObj(a).urlstring),
            c && (delete c.bkn,
            delete c.r),
            d)
                for (var j = d.length - 1; j >= 0; j--)
                    delete c[d[j]];
            return i = {
                url: a,
                type: b,
                param: c
            },
            h = "cgi_cache_" + f(i)
        }
        ,
        a
    }()
}
, function(a, b, c) {
    var d = window.localStorage
      , e = c(18)
      , f = c(20);
    a.exports = function() {
        function a() {
            var a = (new Date).valueOf()
              , b = d.getItem("preload_ls_test");
            !b || a - b > 5e3 ? c.noLocalStorage = !0 : (d.setItem("preload_ls_enable", a),
            c.noLocalStorage = !1,
            d.getItem("use_preload") && (c.cacheReady = !0),
            console.info("========localstorage 可用=======")),
            d.removeItem("preload_ls_test")
        }
        function b() {
            a(),
            localStorage.removeItem("internal_preload", "1")
        }
        var c = {
            noLocalStorage: !1,
            cacheReady: !1
        }
          , g = f.CGIDic;
        return b(),
        c.getData = function(a, b, d, h) {
            var i, j;
            return f.config.isCacheTarget && f.config.enable && g[f.getKey(a, b)] ? (i = f.getKey(a, b, d, h),
            c.noLocalStorage ? void 0 : j = e.getData(i)) : void 0
        }
        ,
        c
    }()
}
, function(a, b, c) {
    var d = c(17);
    a.exports = function(a, b) {
        function c() {
            return a.huatuo && k && j ? !0 : void 0
        }
        var e = {}
          , f = d.getUrlObj(window.location.href).hash.time_redirect
          , g = window.pageStartTime.valueOf()
          , h = b.enable
          , i = b.isProviderCacheReady
          , j = b.reach3G()
          , k = b.isCacheTarget();
        return window.Config.isOffline ? e.huatuo = a.huatuo.offline : e.huatuo = a.huatuo.online,
        e.reportOpenWebView = function() {
            var a = g - f;
            console.log("==========打开webview耗时==========="),
            console.log(a),
            i && h ? e.reportHuatuo(e.huatuo.tOpenWebViewPreload, a) : e.reportHuatuo(e.huatuo.tOpenWebView, a)
        }
        ,
        e.reportUsable = function() {
            var a = (new Date).valueOf()
              , c = a - g;
            console.log("==========首屏渲染时间==========="),
            console.log(c),
            i && h ? e.reportHuatuo(e.huatuo.tUsable.tUseableLS, c) : b.lsEnable && e.reportHuatuo(e.huatuo.tUsable.tUsable, c)
        }
        ,
        e.reportUsableWithOpen = function() {
            var a = (new Date).valueOf()
              , c = a - f;
            console.log("==========打开webview到首屏渲染总耗时==========="),
            console.log(c),
            i && h ? e.reportHuatuo(e.huatuo.tUsableWithOpen.LS, c) : b.lsEnable ? e.reportHuatuo(e.huatuo.tUsableWithOpen.noCache, c) : e.reportHuatuo(e.huatuo.tUsableWithOpen.noLS, c),
            b.lsEnable && (h && i ? e.reportHuatuo(e.huatuo.tUsableWithOpen.total, c) : h || i || e.reportHuatuo(e.huatuo.tUsableWithOpen.total, c))
        }
        ,
        e.reportHuatuo = function(a, b) {
            var d;
            c() && a && (d = a.split("-"),
            4 === d.length && Q.huatuo(d[0], d[1], d[2], b, d[3]))
        }
        ,
        e
    }
}
]);
