!
function(a, b) {
    var c = b(this[a] = this[a] || {});
    "function" == typeof define && (define.amd || define.cmd) ? define(c) : "object" == typeof module && (module.exports = c)
} ("mqq",
function(a, b) {
    "use strict";
    function c(a, b) {
        var c, d, e, f;
        for (a = String(a).split("."), b = String(b).split("."), c = 0, f = Math.max(a.length, b.length); f > c; c++) {
            if (d = isFinite(a[c]) && Number(a[c]) || 0, e = isFinite(b[c]) && Number(b[c]) || 0, e > d) return - 1;
            if (d > e) return 1
        }
        return 0
    }
    function d(b) {
        var c = window.MQQfirebug;
        if (a.debuging && c && c.log && "pbReport" !== b.method) try {
            c.log(b)
        } catch(d) {}
    }
    function e(a, b, c) {
        var d;
        for (d in b)(b.hasOwnProperty(d) && !(d in a) || c) && (a[d] = b[d]);
        return a
    }
    function f(b, c, d, e, f) {
        if (b && c && d) {
            var g, h, i, j, k = b + "://" + c + "/" + d;
            if (e = e || [], !f || !M[f] && !window[f]) for (f = null, h = 0, i = e.length; i > h; h++) if (g = e[h], a.isObject(g) && (g = g.callbackName || g.callback), g && (M[g] || window[g])) {
                f = g;
                break
            }
            f && (N[f] = {
                ns: c,
                method: d,
                uri: k,
                startTime: Date.now()
            },
            j = String(f).match(/__MQQ_CALLBACK_(\d+)/), j && (N[j[1]] = N[f])),
            B.send(k, R)
        }
    }
    function g(a) {
        var b = a.split("."),
        c = window;
        return b.forEach(function(a) { ! c[a] && (c[a] = {}),
            c = c[a]
        }),
        c
    }
    function h(b, c, d) {
        var e, f;
        return (b = a.isFunction(b) ? b: window[b]) ? (e = i(b), f = "__MQQ_CALLBACK_" + e, window[f] = function() {
            var a = E.call(arguments);
            k(e, a, c, d)
        },
        f) : void 0
    }
    function i(a) {
        var b = "" + Q++;
        return a && (M[b] = a),
        b
    }
    function j(a) {
        var b, c, d, e = ["retCode", "retcode", "resultCode", "ret", "code", "r"];
        for (c = 0, d = e.length; d > c; c++) if (e[c] in a) {
            b = a[e[c]];
            break
        }
        return b
    }
    function k(c, f, g, h) {
        var i, k, l, m = a.isFunction(c) ? c: M[c] || window[c],
        n = Date.now();
        f = f || [],
        i = f[0],
        a.isUndefined(h) && (h = !0),
        a.isObject(i) && ("data" in i || (i.data = e({},
        i)), "code" in i || (i.code = j(i) || 0), i.msg = i.msg || ""),
        a.isFunction(m) ? h ? setTimeout(function() {
            m.apply(null, f)
        },
        0) : m.apply(null, f) : console.log("mqqapi: not found such callback: " + c),
        g && (delete M[c], delete window["__MQQ_CALLBACK_" + c]),
        N[c] && (l = N[c], delete N[c], d({
            ns: l.ns,
            method: l.method,
            ret: JSON.stringify(f),
            url: l.uri
        }), Number(c) && delete N["__MQQ_CALLBACK_" + c], i && (i.code !== b ? k = i.code: /^-?\d+$/.test(String(i)) && (k = i)), B.send(l.uri + "#callback", k, n - l.startTime))
    }
    function l(b) {
        var c = E.call(arguments, 1);
        a.android && c && c.length && c.forEach(function(b, d) {
            a.isObject(b) && "r" in b && "result" in b && (c[d] = b.result)
        }),
        k(b, c)
    }
    function m() {}
    function n(b, c) {
        var d = null,
        e = a.platform,
        f = b.split("."),
        h = b.lastIndexOf("."),
        i = f[f.length - 2],
        j = f[f.length - 1],
        k = g(b.substring(0, h)); (!k[j] || a.debuging) && ((d = c[a.platform]) || "browser" === e || ((d = a.iOS && c.iOS) ? e = "iOS": (d = a.android && c.android) && (e = "android")), k[j] = d || m, c.support && c.support[e] && (O[i + "." + j] = c.support[e]))
    }
    function o(b) {
        var c, d, e = b.split("."),
        f = e[e.length - 2] + "." + e[e.length - 1];
        return c = O[f] || O[b.replace("qw.", "mqq.").replace("qa.", "mqq.")],
        a.isObject(c) && (c = c[a.iOS ? "iOS": a.android ? "android": "browser"]),
        c ? (d = c.split("-"), 1 === d.length ? a.compare(d[0]) > -1 : a.compare(d[0]) > -1 && a.compare(d[1]) < 1) : !1
    }
    function p(c, e, f, g) {
        function h() {
            l(g, {
                r: -201,
                result: "error"
            })
        }
        d({
            ns: e,
            method: f,
            url: c
        });
        var i, j = document.createElement("iframe");
        return j.style.cssText = "display:none;width:0px;height:0px;",
        a.iOS && (j.onload = h, j.src = c),
        (document.body || document.documentElement).appendChild(j),
        a.android && (j.onload = h, j.src = c),
        i = a.__RETURN_VALUE,
        a.__RETURN_VALUE = b,
        setTimeout(function() {
            j.parentNode.removeChild(j)
        },
        0),
        i
    }
    function q(b) {
        if ("AndroidQQ" === a.platform) {
            if (a.compare("4.7.2") < 0) return ! 0;
            if (S[b] && a.compare(S[b]) < 0) return ! 0
        }
        return ! 1
    }
    function r(c, d, e, g) {
        if (!c || !d) return null;
        var h, j, l, m;
        if (l = E.call(arguments, 2), g = l.length && l[l.length - 1], a.isFunction(g) ? l.pop() : a.isUndefined(g) ? l.pop() : g = null, e = l[0], j = i(g), -1 === T.indexOf(d) && f("jsbridge", c, d, l, j), g && a.isObject(e) && !e.callback && (window["__MQQ_CALLBACK_AUTO_" + j] = g, e.callback = "__MQQ_CALLBACK_AUTO_" + j), q(c, d)) if (a.compare("4.5") > -1 || /_NZ\b/.test(C)) h = "jsbridge://" + encodeURIComponent(c) + "/" + encodeURIComponent(d) + "/" + j,
        l.forEach(function(b) {
            a.isObject(b) && (b = JSON.stringify(b)),
            h += "/" + encodeURIComponent(String(b))
        }),
        p(h, c, d, j);
        else if (window[c] && window[c][d]) {
            if (m = window[c][d].apply(window[c], l), !g) return m;
            k(j, [m])
        } else g && k(j, [a.ERROR_NO_SUCH_METHOD]);
        else if (h = "jsbridge://" + encodeURIComponent(c) + "/" + encodeURIComponent(d), l.forEach(function(b, c) {
            a.isObject(b) && (b = JSON.stringify(b)),
            h += 0 === c ? "?p=": "&p" + c + "=",
            h += encodeURIComponent(String(b))
        }), "pbReport" !== d && (h += "#" + j), m = p(h, c, d), a.iOS && m !== b && m.result !== b) {
            if (!g) return m.result;
            k(j, [m.result])
        }
        return null
    }
    function s(b, c) {
        var d = P[b + "." + c];
        return a.isFunction(d) ? d.apply(this, E.call(arguments, 2)) : r.apply(this, E.call(arguments))
    }
    function t(b, c, d, e, g) {
        if (!b || !c || !d) return null;
        var i, j, k = E.call(arguments);
        a.isFunction(k[k.length - 1]) ? (g = k[k.length - 1], k.pop()) : g = null,
        e = 4 === k.length ? k[k.length - 1] : {},
        g && (e.callback_type = "javascript", i = h(g), e.callback_name = i),
        e.src_type = e.src_type || "web",
        e.version || (e.version = 1),
        j = b + "://" + encodeURIComponent(c) + "/" + encodeURIComponent(d) + "?" + v(e),
        p(j, c, d),
        f(b, c, d, k, i)
    }
    function u(a) {
        var b, c, d, e = a.indexOf("?"),
        f = a.substring(e + 1).split("&"),
        g = {};
        for (b = 0; b < f.length; b++) e = f[b].indexOf("="),
        c = f[b].substring(0, e),
        d = f[b].substring(e + 1),
        g[c] = decodeURIComponent(d);
        return g
    }
    function v(a) {
        var b, c = [];
        for (b in a) a.hasOwnProperty(b) && c.push(encodeURIComponent(String(b)) + "=" + encodeURIComponent(String(a[b])));
        return c.join("&")
    }
    function w(a, b) {
        var c, d = document.createElement("a");
        return d.href = a,
        d.search && (c = u(String(d.search).substring(1)), b.forEach(function(a) {
            delete c[a]
        }), d.search = "?" + v(c)),
        d.hash && (c = u(String(d.hash).substring(1)), b.forEach(function(a) {
            delete c[a]
        }), d.hash = "#" + v(c)),
        a = d.href,
        d = null,
        a
    }
    function x(a, b) {
        if ("qbrowserVisibilityChange" === a) return document.addEventListener(a, b, !1),
        !0;
        var c = "evt-" + a;
        return (M[c] = M[c] || []).push(b),
        !0
    }
    function y(a, b) {
        var c, d = "evt-" + a,
        e = M[d],
        f = !1;
        if (!e) return ! 1;
        if (!b) return delete M[d],
        !0;
        for (c = e.length - 1; c >= 0; c--) b === e[c] && (e.splice(c, 1), f = !0);
        return f
    }
    function z(a) {
        var b = "evt-" + a,
        c = M[b],
        d = E.call(arguments, 1);
        c && c.forEach(function(a) {
            k(a, d, !1)
        })
    }
    function A(b, c, d) {
        var e, g = {
            event: b,
            data: c || {},
            options: d || {}
        };
        a.android && g.options.broadcast === !1 && a.compare("5.2") <= 0 && (g.options.domains = ["localhost"], g.options.broadcast = !0),
        "browser" !== a.platform && (e = "jsbridge://event/dispatchEvent?p=" + encodeURIComponent(JSON.stringify(g) || ""), p(e, "event", "dispatchEvent"), f("jsbridge", "event", "dispatchEvent"))
    }
    var B, C = navigator.userAgent,
    D = window.MQQfirebug,
    E = Array.prototype.slice,
    F = Object.prototype.toString,
    G = /\b(iPad|iPhone|iPod)\b.*? OS ([\d_]+)/,
    H = /\bAndroid ([^;]+)/,
    I = /\bQQ\/([\d\.]+)/,
    J = /\bIPadQQ\/([\d\.]+).*?\bQQ\/([\d\.]+)/,
    K = /\bV1_AND_SQI?_([\d\.]+)(.*? QQ\/([\d\.]+))?/,
    L = /\bTribe\/([\d\.]+)/,
    M = a.__aCallbacks || {},
    N = a.__aReports || {},
    O = a.__aSupports || {},
    P = a.__aFunctions || {},
    Q = 1,
    R = -1e5,
    S = {
        qbizApi: "5.0",
        pay: "999999",
        SetPwdJsInterface: "999999",
        GCApi: "999999",
        q_download: "999999",
        qqZoneAppList: "999999",
        qzone_app: "999999",
        qzone_http: "999999",
        qzone_imageCache: "999999",
        RoamMapJsPlugin: "999999"
    },
    T = ["pbReport", "popBack", "close"];
    return D ? (a.debuging = !0, C = D.ua || C) : a.debuging = !1,
    a.iOS = G.test(C),
    a.android = H.test(C),
    a.iOS && a.android && (a.iOS = !1),
    a.version = "20150715003",
    a.QQVersion = "0",
    a.clientVersion = "0",
    a.ERROR_NO_SUCH_METHOD = "no such method",
    a.ERROR_PERMISSION_DENIED = "permission denied",
    a.compare = function(b) {
        return c(a.clientVersion, b)
    },
    a.platform = function() {
        var d, e = "browser";
        return a.android && ((d = C.match(K)) && d.length ? (a.QQVersion = a.clientVersion = (c(d[1], d[3]) >= 0 ? d[1] : d[3]) || "0", e = "AndroidQQ") : (d = C.match(L)) && d.length && (a.clientVersion = d[1] || "0", e = "AndroidTribe"), window.JsBridge = window.JsBridge || {},
        window.JsBridge.callMethod = r, window.JsBridge.callback = l, window.JsBridge.compareVersion = a.compare),
        a.iOS && ((d = C.match(J)) && d.length ? (a.clientVersion = d[1] || "0", a.QQVersion = d[2] || a.clientVersion, e = "iPadQQ") : (d = C.match(I)) && d.length ? (a.QQVersion = a.clientVersion = d[1] || "0", e = "iPhoneQQ") : (d = C.match(L)) && d.length && (a.clientVersion = d[1] || "0", e = "iOSTribe"), window.iOSQQApi = a, a.__RETURN_VALUE = b),
        e
    } (),
    Q = function() {
        var a, b = 1;
        for (a in M) M.hasOwnProperty(a) && (a = Number(a), isNaN(a) || (b = Math.max(b, a)));
        return++b
    } (),
    B = function() {
        function b() {
            var c, g = d,
            k = {};
            d = [],
            f = 0,
            g.length && (k.appid = h, k.typeid = i, k.releaseversion = l, k.sdkversion = a.version, k.qua = m, k.frequency = j, k.t = Date.now(), k.key = ["commandid", "resultcode", "tmcost"].join(","), g.forEach(function(a, b) {
                k[b + 1 + "_1"] = a[0],
                k[b + 1 + "_2"] = a[1],
                k[b + 1 + "_3"] = a[2]
            }), k = new String(v(k)), a.compare("4.6") >= 0 ? setTimeout(function() {
                mqq.iOS ? mqq.invokeClient("data", "pbReport", {
                    type: String(10004),
                    data: k
                }) : mqq.invokeClient("publicAccount", "pbReport", String(10004), k)
            },
            0) : (c = new Image, c.onload = function() {
                c = null
            },
            c.src = "http://wspeed.qq.com/w.cgi?" + k), f = setTimeout(b, e))
        }
        function c(a, c, h) {
            var i; (c !== R || (c = 0, i = Math.round(Math.random() * j) % j, 1 === i)) && (d.push([a, c || 0, h || 0]), f || (g = Date.now(), f = setTimeout(b, e)))
        }
        var d = [],
        e = 500,
        f = 0,
        g = 0,
        h = 1000218,
        i = 1000280,
        j = 100,
        k = String(a.QQVersion).split(".").slice(0, 3).join("."),
        l = a.platform + "_MQQ_" + k,
        m = a.platform + a.QQVersion + "/" + a.version;
        return {
            send: c
        }
    } (),
    e(a,
    function() {
        var a = {},
        b = "Object,Function,String,Number,Boolean,Date,Undefined,Null";
        return b.split(",").forEach(function(b) {
            a["is" + b] = function(a) {
                return F.call(a) === "[object " + b + "]"
            }
        }),
        a
    } ()),
    a.__aCallbacks = M,
    a.__aReports = N,
    a.__aSupports = O,
    a.__aFunctions = P,
    a.__fireCallback = k,
    a.__reportAPI = f,
    e(a, {
        invoke: s,
        invokeClient: r,
        invokeSchema: t,
        build: n,
        callback: h,
        support: o,
        execGlobalCallback: l,
        addEventListener: x,
        removeEventListener: y,
        dispatchEvent: A,
        execEventCallback: z,
        mapQuery: u,
        toQuery: v,
        removeQuery: w
    },
    !0),
    a
}),
function() {
    "use strict";
    function a(a, b, d) {
        return d ?
        function() {
            var d = [a, b].concat(c.call(arguments));
            mqq.invoke.apply(mqq, d)
        }: function() {
            var d = c.call(arguments),
            e = null;
            d.length && "function" == typeof d[d.length - 1] && (e = d[d.length - 1], d.pop());
            var f = k[a][b].apply(k[a], d);
            return e ? void e(f) : f
        }
    }
    function b(b, c) {
        if (c = c || 1, mqq.compare(c) < 0) return void console.info("jsbridge: version not match, apis ignored");
        for (var d in b) {
            var e = b[d];
            if (e && e.length && Array.isArray(e)) {
                var f = window[d];
                if (f)"object" == typeof f && f.getClass && (k[d] = f, window[d] = {});
                else {
                    if (!j) continue;
                    window[d] = {}
                }
                var g = k[d];
                f = window[d];
                for (var h = 0,
                i = e.length; i > h; h++) {
                    var l = e[h];
                    f[l] || (g ? g[l] && (f[l] = a(d, l, !1)) : f[l] = a(d, l, !0))
                }
            }
        }
    }
    var c = Array.prototype.slice,
    d = {
        QQApi: ["isAppInstalled", "isAppInstalledBatch", "startAppWithPkgName", "checkAppInstalled", "checkAppInstalledBatch", "getOpenidBatch", "startAppWithPkgNameAndOpenId"]
    },
    e = {
        QQApi: ["lauchApp"]
    },
    f = {
        publicAccount: ["close", "getJson", "getLocation", "hideLoading", "openInExternalBrowser", "showLoading", "viewAccount"]
    },
    g = {
        publicAccount: ["getMemberCount", "getNetworkState", "getValue", "open", "openEmoji", "openUrl", "setRightButton", "setValue", "shareMessage", "showDialog"],
        qqZoneAppList: ["getCurrentVersion", "getSdPath", "getWebDisplay", "goUrl", "openMsgCenter", "showDialog", "setAllowCallBackEvent"],
        q_download: ["doDownloadAction", "getQueryDownloadAction", "registerDownloadCallBackListener", "cancelDownload", "cancelNotification"],
        qzone_http: ["httpRequest"],
        qzone_imageCache: ["downloadImage", "getImageRootPath", "imageIsExist", "sdIsMounted", "updateImage", "clearImage"],
        qzone_app: ["getAllDownAppInfo", "getAppInfo", "getAppInfoBatch", "startSystemApp", "uninstallApp"]
    },
    h = {
        coupon: ["addCoupon", "addFavourBusiness", "gotoCoupon", "gotoCouponHome", "isCouponValid", "isFavourBusiness", "isFavourCoupon", "removeFavourBusiness"]
    },
    i = navigator.userAgent,
    j = /\bV1_AND_SQI?_([\d\.]+)(.*? QQ\/([\d\.]+))?/.test(i) && (mqq.compare("4.5") > -1 || /_NZ\b/.test(i)),
    k = {};
    window.JsBridge || (window.JsBridge = {}),
    window.JsBridge.restoreApis = b,
    b(d),
    b(e, "4.5"),
    j ? /\bPA\b/.test(i) || mqq.compare("4.6") >= 0 ? (b(f), b(g, "4.5"), b(h, "4.5")) : /\bQR\b/.test(i) && (b(h, "4.5"), mqq.compare("4.5") >= 0 && mqq.compare("4.6") < 0 && (window.publicAccount = {
        openUrl: function(a) {
            location.href = a
        }
    })) : b(f, "4.2")
} (),
mqq.build("mqq.app.checkAppInstalled", {
    android: function(a, b) {
        mqq.invokeClient("QQApi", "checkAppInstalled", a, b)
    },
    support: {
        android: "4.2"
    }
}),
mqq.build("mqq.app.checkAppInstalledBatch", {
    android: function(a, b) {
        a = a.join("|"),
        mqq.invokeClient("QQApi", "checkAppInstalledBatch", a,
        function(a) {
            a = (a || "").split("|"),
            b(a)
        })
    },
    support: {
        android: "4.2"
    }
}),
mqq.build("mqq.app.downloadApp", {
    android: function() {
        var a, b = {},
        c = 0,
        d = function(a) {
            if (c > 0) {
                var d, e, f = 0,
                g = a.length;
                if ("object" == typeof a && g) for (; d = a[f]; f++)(e = b[d.appid]) && e(d);
                else(e = b[a.appid]) && e(a)
            }
        };
        return function(e, f) { ! a && f && (a = !0, mqq.invokeClient("q_download", "registerDownloadCallBackListener", mqq.callback(d))),
            f && "function" == typeof f && (c++, b[e.appid] = f),
            mqq.invokeClient("q_download", "doDownloadAction", e)
        }
    } (),
    support: {
        android: "4.5"
    }
}),
mqq.build("mqq.app.isAppInstalled", {
    iOS: function(a, b) {
        return mqq.invokeClient("app", "isInstalled", {
            scheme: a
        },
        b)
    },
    android: function(a, b) {
        mqq.invokeClient("QQApi", "isAppInstalled", a, b)
    },
    support: {
        iOS: "4.2",
        android: "4.2"
    }
}),
mqq.build("mqq.app.isAppInstalledBatch", {
    iOS: function(a, b) {
        return mqq.invokeClient("app", "batchIsInstalled", {
            schemes: a
        },
        b)
    },
    android: function(a, b) {
        a = a.join("|"),
        mqq.invokeClient("QQApi", "isAppInstalledBatch", a,
        function(a) {
            var c = [];
            a = (a + "").split("|");
            for (var d = 0; d < a.length; d++) c.push(1 === parseInt(a[d]));
            b(c)
        })
    },
    support: {
        iOS: "4.2",
        android: "4.2"
    }
}),
mqq.build("mqq.app.launchApp", {
    iOS: function(a) {
        mqq.invokeSchema(a.name, "app", "launch", a)
    },
    android: function(a) {
        mqq.invokeClient("QQApi", "startAppWithPkgName", a.name)
    },
    support: {
        iOS: "4.2",
        android: "4.2"
    }
}),
mqq.build("mqq.app.launchAppWithTokens", {
    iOS: function(a, b) {
        return "object" == typeof a ? mqq.invokeClient("app", "launchApp", a) : mqq.invokeClient("app", "launchApp", {
            appID: a,
            paramsStr: b
        })
    },
    android: function(a) {
        mqq.compare("5.2") >= 0 ? mqq.invokeClient("QQApi", "launchAppWithTokens", a) : mqq.compare("4.6") >= 0 ? mqq.invokeClient("QQApi", "launchAppWithTokens", a.appID, a.paramsStr, a.packageName, a.flags || a.falgs || 0) : mqq.invokeClient("QQApi", "launchApp", a.appID, a.paramsStr, a.packageName)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.app.sendFunnyFace", {
    iOS: function(a) {
        mqq.invokeClient("app", "sendFunnyFace", a)
    },
    android: function(a) {
        mqq.invokeClient("qbizApi", "sendFunnyFace", a.type, a.sessionType, a.gcode, a.guin, a.faceID)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.coupon.addCoupon", {
    iOS: function(a, b, c, d, e) {
        if ("object" == typeof a) {
            var f = a; (f.callback = mqq.callback(b)) && mqq.invokeClient("coupon", "addCoupon", f)
        } else "function" == typeof d && (e = d, d = ""),
        mqq.invokeClient("coupon", "addCoupon", {
            bid: a,
            cid: b,
            sourceId: c,
            city: d || "",
            callback: mqq.callback(e)
        })
    },
    android: function(a, b) {
        var c = mqq.callback(b, !0);
        mqq.invokeClient("coupon", "addCoupon", a.bid, a.sourceId, a.cid, c)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.coupon.addFavourBusiness", {
    iOS: function(a, b, c) {
        if ("object" == typeof a) {
            var d = a; (d.callback = mqq.callback(b)) && mqq.invokeClient("coupon", "addFavourBusiness", d)
        } else mqq.invokeClient("coupon", "addFavourBusiness", {
            bid: a,
            sourceId: b,
            callback: mqq.callback(c)
        })
    },
    android: function(a, b) {
        var c = mqq.callback(b, !0);
        mqq.invokeClient("coupon", "addFavourBusiness", a.bid, a.sourceId, c)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.coupon.goToCouponHomePage", {
    iOS: function(a) {
        mqq.invokeClient("coupon", "goToCouponHomePage", {
            params: a
        })
    },
    android: function(a) {
        a = JSON.stringify(a || {}),
        mqq.invokeClient("coupon", "goToCouponHomePage", a)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.coupon.isFavourBusiness", {
    iOS: function(a, b, c) {
        if ("object" == typeof a) {
            var d = a; (d.callback = mqq.callback(b)) && mqq.invokeClient("coupon", "isFavourBusiness", d)
        } else mqq.invokeClient("coupon", "isFavourBusiness", {
            bid: a,
            sourceId: b,
            callback: mqq.callback(c)
        })
    },
    android: function(a, b) {
        mqq.invokeClient("coupon", "isFavourBusiness", a.bid, a.sourceId, b)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.coupon.isFavourCoupon", {
    iOS: function(a, b, c, d) {
        if ("object" == typeof a) {
            var e = a; (e.callback = mqq.callback(b)) && mqq.invokeClient("coupon", "isFavourCoupon", e)
        } else mqq.invokeClient("coupon", "isFavourCoupon", {
            bid: a,
            cid: b,
            sourceId: c,
            callback: mqq.callback(d)
        })
    },
    android: function(a, b) {
        mqq.invokeClient("coupon", "isFavourCoupon", a.bid, a.cid, a.sourceId, b)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.coupon.removeCoupon", {
    iOS: function(a, b, c, d) {
        if ("object" == typeof a) {
            var e = a; (e.callback = mqq.callback(b)) && mqq.invokeClient("coupon", "removeCoupon", e)
        } else mqq.invokeClient("coupon", "removeCoupon", {
            bid: a,
            cid: b,
            sourceId: c,
            callback: mqq.callback(d)
        })
    },
    support: {
        iOS: "4.6"
    }
}),
mqq.build("mqq.coupon.removeFavourBusiness", {
    iOS: function(a, b, c) {
        if ("object" == typeof a) {
            var d = a; (d.callback = mqq.callback(b)) && mqq.invokeClient("coupon", "removeFavourBusiness", d)
        } else mqq.invokeClient("coupon", "removeFavourBusiness", {
            bid: a,
            sourceId: b,
            callback: mqq.callback(c)
        })
    },
    android: function(a, b) {
        var c = mqq.callback(b, !0);
        mqq.invokeClient("coupon", "removeFavourBusiness", a.bid, a.sourceId, c)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.data.batchFetchOpenID", {
    iOS: function(a, b) {
        var c = a.appIDs;
        mqq.data.fetchJson({
            url: "http://cgi.connect.qq.com/api/get_openids_by_appids",
            params: {
                appids: JSON.stringify(c)
            }
        },
        b)
    },
    android: function(a, b) {
        var c = a.appIDs;
        mqq.data.fetchJson({
            url: "http://cgi.connect.qq.com/api/get_openids_by_appids",
            params: {
                appids: JSON.stringify(c)
            }
        },
        b)
    },
    support: {
        iOS: "4.5",
        android: "4.6"
    }
}),
mqq.build("mqq.data.deleteH5Data", {
    iOS: function(a, b) {
        var c = b ? mqq.callback(b) : null;
        mqq.invokeClient("data", "deleteWebviewBizData", {
            callback: c,
            params: a
        })
    },
    android: function(a, b) {
        a = JSON.stringify(a || {}),
        mqq.invokeClient("publicAccount", "deleteH5Data", a, mqq.callback(b, !0))
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.data.deleteH5DataByHost", {
    iOS: function(a, b) {
        var c = b ? mqq.callback(b) : null;
        mqq.invokeClient("data", "deleteWebviewBizData", {
            callback: c,
            delallhostdata: 1,
            params: a
        })
    },
    android: function(a, b) {
        a = JSON.stringify(a || {}),
        mqq.invokeClient("publicAccount", "deleteH5DataByHost", a, mqq.callback(b, !0))
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
function() {
    function a() {
        return "UID_" + ++c
    }
    var b = {},
    c = 1;
    window.clientCallback = function(a, c) {
        var d = b[c];
        if (!d) return void console.log("this getJson no callbackToken!");
        if (d.callback) {
            if (clearTimeout(d.timer), "string" == typeof a) try {
                a = JSON.parse(a)
            } catch(e) {
                a = null
            }
            d.callback(a, d.context || window, 200),
            d.callback = null
        }
    },
    mqq.build("mqq.data.fetchJson", {
        iOS: function(a, b) {
            var c = a.url,
            d = a.params || {},
            e = a.options || {},
            f = a.context;
            d._t = +new Date;
            var g = b ? mqq.callback(function(a, c, d) {
                if ("string" == typeof a) try {
                    a = JSON.parse(a)
                } catch(e) {
                    a = null
                }
                b(a, c, d)
            },
            !0) : null;
            mqq.invokeClient("data", "fetchJson", {
                method: e.method || "GET",
                timeout: e.timeout || -1,
                options: e,
                url: c,
                params: mqq.toQuery(d),
                callback: g,
                context: JSON.stringify(f)
            })
        },
        android: function(c, d) {
            var e = c.options || {},
            f = e.method || "GET",
            g = {
                param: c.params,
                method: f
            };
            g = JSON.stringify(g);
            var h = a();
            c.callback = d,
            b[h] = c,
            e.timeout && (c.timer = setTimeout(function() {
                c.callback && (c.callback("timeout", c.context || window, 0), c.callback = null)
            },
            e.timeout)),
            mqq.invokeClient("publicAccount", "getJson", c.url, g, "", h)
        },
        support: {
            iOS: "4.5",
            android: "4.6"
        }
    })
} (),
mqq.build("mqq.data.getClipboard", {
    iOS: function(a) {
        var b = {},
        c = mqq.invokeClient("data", "getClipboard", b);
        a && a(c)
    },
    android: function(a) {
        var b = {};
        a && (b.callback = mqq.callback(a)),
        mqq.invokeClient("data", "getClipboard", b)
    },
    support: {
        iOS: "4.7.2",
        android: "4.7.2"
    }
}),
function() {
    var a = function(a) {
        return function(b) {
            a(b.friends || [])
        }
    },
    b = function(b, c) {
        c && (b.callback = mqq.callback(a(c))),
        mqq.invokeClient("qw_data", "getFriendInfo", b)
    };
    mqq.build("mqq.data.getFriendInfo", {
        iOS: b,
        android: b,
        support: {
            iOS: "5.1.0",
            android: "5.1.0"
        }
    })
} (),
function() {
    var a = function(a) {
        return function(b) {
            var c = {};
            b && b.remarks && (c = b.remarks),
            a(c)
        }
    },
    b = function(b, c) {
        c && (b.callback = mqq.callback(a(c), !1, !0)),
        mqq.invoke("qw_data", "getFriendRemark", b)
    };
    mqq.build("mqq.data.getFriendRemark", {
        iOS: b,
        android: b,
        support: {
            iOS: "5.8.0",
            android: "5.8.0"
        }
    })
} (),
mqq.build("mqq.data.getPageLoadStamp", {
    iOS: function(a) {
        mqq.invokeClient("data", "getPageLoadStamp", {
            callback: mqq.callback(a)
        })
    },
    android: function(a) {
        mqq.invokeClient("publicAccount", "getPageLoadStamp", mqq.callback(a))
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
function() {
    var a = function(a) {
        return function(b) {
            if (mqq.android && b && void 0 === b.result) {
                try {
                    b = JSON.parse(b)
                } catch(c) {}
                b = {
                    result: 0,
                    data: b,
                    message: "成功"
                }
            }
            a(b)
        }
    },
    b = function(b) {
        if (mqq.compare("4.7.1") >= 0) mqq.invokeClient("qw_data", "getPerformance", a(b));
        else try {
            common.getPerformance(a(b))
        } catch(c) {
            b({
                result: -1,
                message: "该接口在手Q v4.7.1 或以上才支持！",
                data: null
            })
        }
    };
    mqq.build("mqq.data.getPerformance", {
        iOS: b,
        android: b,
        support: {
            iOS: "4.7.1",
            android: "4.7.1"
        }
    })
} (),
mqq.build("mqq.data.getUrlImage", {
    iOS: function(a, b) {
        var c = b ? mqq.callback(b) : null;
        mqq.invokeClient("data", "getUrlImage", {
            callback: c,
            params: a
        })
    },
    android: function(a, b) {
        a = JSON.stringify(a || {}),
        mqq.invokeClient("publicAccount", "getUrlImage", a, mqq.callback(b))
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.data.getUserInfo", {
    iOS: function(a) {
        return mqq.invokeClient("data", "userInfo", a)
    },
    android: function(a) {
        mqq.invokeClient("data", "userInfo", {
            callback: mqq.callback(a)
        })
    },
    support: {
        iOS: "4.7",
        android: "4.7"
    }
}),
mqq.build("mqq.data.getWebRunEnv", {
    iOS: function(a) {
        mqq.invokeClient("data", "getWebviewRunningEnvironment", mqq.callback(a))
    },
    android: function(a) {
        mqq.invokeClient("data", "getWebviewRunningEnvironment", mqq.callback(a))
    },
    support: {
        iOS: "5.7",
        android: "5.7"
    }
}),
mqq.build("mqq.data.isFollowUin", {
    iOS: function(a, b) {
        a.callback = mqq.callback(b),
        mqq.invokeClient("data", "isFollowUin", a)
    },
    android: function(a, b) {
        mqq.invokeClient("publicAccount", "isFollowUin", a, mqq.callback(b))
    },
    support: {
        iOS: "4.7",
        android: "4.7"
    }
}),
mqq.build("mqq.data.pbReport", {
    iOS: function(a, b) {
        mqq.invokeClient("data", "pbReport", {
            type: String(a),
            data: b
        })
    },
    android: function(a, b) {
        mqq.invokeClient("publicAccount", "pbReport", String(a), b)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.data.readH5Data", {
    iOS: function(a, b) {
        var c = b ? mqq.callback(b) : null;
        mqq.invokeClient("data", "readWebviewBizData", {
            callback: c,
            params: a
        })
    },
    android: function(a, b) {
        a = JSON.stringify(a || {}),
        mqq.invokeClient("publicAccount", "readH5Data", a, mqq.callback(function(a) {
            if (a && a.response && a.response.data) {
                var c = a.response.data;
                c = c.replace(/\\/g, ""),
                c = decodeURIComponent(c),
                a.response.data = c
            }
            b(a)
        },
        !0))
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.data.sendRequest", {
    iOS: function(a, b) {
        var c = a.url,
        d = a.params,
        e = a.options || {},
        f = a.context;
        d._t = +new Date,
        mqq.invokeClient("data", "fetchJson", {
            method: e.method || "GET",
            options: e,
            url: c,
            params: mqq.toQuery(d),
            callback: mqq.callback(b),
            context: JSON.stringify(f)
        })
    },
    android: function(a, b) {
        a.callback = mqq.callback(b),
        mqq.invokeClient("data", "sendRequest", a)
    },
    support: {
        iOS: "4.5",
        android: "4.7"
    }
}),
mqq.build("mqq.data.setClipboard", {
    iOS: function(a, b) {
        mqq.invokeClient("data", "setClipboard", a),
        b && b(!0)
    },
    android: function(a, b) {
        b && (a.callback = mqq.callback(b)),
        mqq.invokeClient("data", "setClipboard", a)
    },
    support: {
        iOS: "4.7.2",
        android: "4.7.2"
    }
}),
mqq.build("mqq.data.setReturnBackResult", {
    iOS: function(a) {
        mqq.invokeClient("data", "setReturnBackResult", a)
    },
    android: function(a) {
        mqq.invokeClient("data", "setReturnBackResult", a)
    },
    support: {
        iOS: "5.8",
        android: "5.8"
    }
}),
mqq.build("mqq.data.setShareInfo", {
    iOS: function(a, b) {
        return a.share_url && (a.share_url = mqq.removeQuery(a.share_url, ["sid", "3g_sid"])),
        a.desc && (a.desc = a.desc.length > 50 ? a.desc.substring(0, 50) + "...": a.desc),
        mqq.invokeClient("data", "setShareInfo", {
            params: a
        },
        b)
    },
    android: function(a, b) {
        a.share_url && (a.share_url = mqq.removeQuery(a.share_url, ["sid", "3g_sid"])),
        a.desc && (a.desc = a.desc.length > 50 ? a.desc.substring(0, 50) + "...": a.desc),
        mqq.invokeClient("QQApi", "setShareInfo", a, b)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.data.setShareURL", {
    iOS: function(a, b) {
        a.url && (a.url = mqq.removeQuery(a.url, ["sid", "3g_sid"])),
        mqq.invokeClient("data", "setShareURL", a, b)
    },
    android: function(a, b) {
        a.url && (a.url = mqq.removeQuery(a.url, ["sid", "3g_sid"])),
        mqq.compare("4.6") < 0 ? b(!1) : mqq.invokeClient("QQApi", "setShareURL", a.url, b)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.data.startSyncData", {
    iOS: function(a, b) {
        var c = mqq.callback(b);
        c && (a.callback = c, mqq.invokeClient("data", "startSyncData", a))
    },
    android: function(a, b) {
        var c = mqq.callback(b);
        mqq.invokeClient("qbizApi", "startSyncData", a.appID, c)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.data.stopSyncData", {
    iOS: function(a) {
        mqq.invokeClient("data", "stopSyncData", a)
    },
    android: function(a) {
        mqq.invokeClient("qbizApi", "stopSyncData", a.appID, name)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.data.writeH5Data", {
    iOS: function(a, b) {
        var c = mqq.callback(b ||
        function() {}),
        d = a.data;
        d && "object" == typeof d && (a.data = JSON.stringify(d)),
        mqq.invokeClient("data", "writeWebviewBizData", {
            callback: c,
            params: a
        })
    },
    android: function(a, b) {
        var c = a.data;
        c && ("object" == typeof c && (c = JSON.stringify(c)), a.data = encodeURIComponent(c)),
        mqq.invokeClient("publicAccount", "writeH5Data", a, mqq.callback(b ||
        function() {},
        !0))
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
function() {
    var a = function(a) {
        return null === a ? "null": void 0 === a ? "undefined": Object.prototype.toString.call(a).slice(8, -1).toLowerCase()
    },
    b = function(b) {
        var c, d = a(b);
        return "object" === d && mqq.compare("5.8.0") >= 0 ? (c = {},
        c.id = "webviewDebugLog_" + b.id, c.subid = b.subid, c.content = b.content, c.isall = !1, mqq.invokeClient("qw_debug", "detailLog", c)) : void 0
    };
    mqq.build("mqq.debug.detailLog", {
        iOS: b,
        android: b,
        support: {
            iOS: "5.8.0",
            android: "5.8.0"
        }
    })
} (),
mqq.build("mqq.debug.hide", {
    iOS: function(a) {
        return mqq.compare("4.7.1") >= 0 ? (null == a && (a = !0), mqq.invokeClient("qw_debug", "hide", {
            flag: a
        })) : void 0
    },
    android: function(a) {
        return mqq.compare("4.7.1") >= 0 ? (null == a && (a = !0), mqq.invokeClient("qw_debug", "hide", {
            flag: a
        })) : void 0
    },
    support: {
        iOS: "4.7.1",
        android: "4.7.1"
    }
}),
mqq.build("mqq.debug.log", {
    iOS: function(a) {
        var b = "",
        c = function(a) {
            return null === a ? "null": void 0 === a ? "undefined": Object.prototype.toString.call(a).slice(8, -1).toLowerCase()
        },
        d = c(a);
        return b = "function" === d ? a.toString() : "string" === d ? a: "array" === d ? "[" + a.join() + "]": JSON.stringify(a),
        mqq.compare("4.7.1") >= 0 ? mqq.invokeClient("qw_debug", "log", {
            msg: b
        }) : void 0
    },
    android: function(a) {
        var b = "",
        c = function(a) {
            return null === a ? "null": void 0 === a ? "undefined": Object.prototype.toString.call(a).slice(8, -1).toLowerCase()
        },
        d = c(a);
        b = "function" === d ? a.toString() : "string" === d ? a: "array" === d ? "[" + a.join() + "]": JSON.stringify(a),
        mqq.compare("4.7.1") >= 0 && mqq.invokeClient("qw_debug", "log", {
            msg: b
        })
    },
    support: {
        iOS: "4.7.1",
        android: "4.7.1"
    }
}),
mqq.build("mqq.debug.show", {
    iOS: function(a) {
        return mqq.compare("4.7.1") >= 0 ? (null == a && (a = !0), mqq.invokeClient("qw_debug", "show", {
            flag: a
        })) : void 0
    },
    android: function(a) {
        mqq.compare("4.7.1") >= 0 && (null == a && (a = !0), mqq.invokeClient("qw_debug", "show", {
            flag: a
        }))
    },
    support: {
        iOS: "4.7.1",
        android: "4.7.1"
    }
}),
mqq.build("mqq.debug.start", {
    iOS: function() {
        return mqq.compare("4.7.1") >= 0 ? mqq.invokeClient("qw_debug", "start") : void 0
    },
    android: function() {
        mqq.compare("4.7.1") >= 0 && mqq.invokeClient("qw_debug", "start")
    },
    support: {
        iOS: "4.7.1",
        android: "4.7.1"
    }
}),
mqq.build("mqq.debug.stop", {
    iOS: function() {
        return mqq.compare("4.7.1") >= 0 ? mqq.invokeClient("qw_debug", "stop") : void 0
    },
    android: function() {
        mqq.compare("4.7.1") >= 0 && mqq.invokeClient("qw_debug", "stop")
    },
    support: {
        iOS: "4.7.1",
        android: "4.7.1"
    }
}),
mqq.build("mqq.device.connectToWiFi", {
    iOS: function(a, b) {
        b && b(mqq.ERROR_NO_SUCH_METHOD)
    },
    android: function(a, b) {
        a.callback = mqq.callback(b),
        a.callback && mqq.compare("5.1") >= 0 && mqq.compare("5.4") < 0 && (a.callback = "javascript:" + a.callback),
        mqq.invokeClient("qbizApi", "connectToWiFi", a)
    },
    support: {
        android: "4.7"
    }
}),
mqq.build("mqq.device.qqVersion", {
    iOS: function(a) {
        return mqq.invokeClient("device", "qqVersion", a)
    },
    support: {
        iOS: "4.5"
    }
}),
mqq.build("mqq.device.qqBuild", {
    iOS: function(a) {
        return mqq.invokeClient("device", "qqBuild", a)
    },
    support: {
        iOS: "4.5"
    }
}),
mqq.build("mqq.device.getClientInfo", {
    iOS: function(a) {
        var b = {
            qqVersion: this.qqVersion(),
            qqBuild: this.qqBuild()
        },
        c = mqq.callback(a);
        return mqq.__reportAPI("web", "device", "getClientInfo", null, c),
        "function" != typeof a ? b: void mqq.__fireCallback(c, [b])
    },
    android: function(a) {
        if (mqq.compare("4.6") >= 0) {
            var b = a;
            a = function(a) {
                try {
                    a = JSON.parse(a)
                } catch(c) {}
                b && b(a)
            },
            mqq.invokeClient("qbizApi", "getClientInfo", a)
        } else mqq.__reportAPI("web", "device", "getClientInfo"),
        a({
            qqVersion: mqq.QQVersion,
            qqBuild: function(a) {
                return a = a && a[1] || 0,
                a && a.slice(a.lastIndexOf(".") + 1) || 0
            } (navigator.userAgent.match(/\bqq\/([\d\.]+)/i))
        })
    },
    support: {
        iOS: "4.5",
        android: "4.6"
    }
}),
mqq.build("mqq.device.systemName", {
    iOS: function(a) {
        return mqq.invokeClient("device", "systemName", a)
    },
    support: {
        iOS: "4.5"
    }
}),
mqq.build("mqq.device.systemVersion", {
    iOS: function(a) {
        return mqq.invokeClient("device", "systemVersion", a)
    },
    support: {
        iOS: "4.5"
    }
}),
mqq.build("mqq.device.model", {
    iOS: function(a) {
        return mqq.invokeClient("device", "model", a)
    },
    support: {
        iOS: "4.5"
    }
}),
mqq.build("mqq.device.modelVersion", {
    iOS: function(a) {
        return mqq.invokeClient("device", "modelVersion", a)
    },
    support: {
        iOS: "4.5"
    }
}),
mqq.build("mqq.device.getDeviceInfo", {
    iOS: function(a) {
        if (mqq.compare(4.7) >= 0) return mqq.invokeClient("device", "getDeviceInfo", a);
        var b = mqq.callback(a);
        mqq.__reportAPI("web", "device", "getClientInfo", null, b);
        var c = {
            isMobileQQ: this.isMobileQQ(),
            systemName: this.systemName(),
            systemVersion: this.systemVersion(),
            model: this.model(),
            modelVersion: this.modelVersion()
        };
        return "function" != typeof a ? c: void mqq.__fireCallback(b, [c])
    },
    android: function(a) {
        if (mqq.compare("4.6") >= 0) {
            var b = a;
            a = function(a) {
                try {
                    a = JSON.parse(a)
                } catch(c) {}
                b && b(a)
            },
            mqq.invokeClient("qbizApi", "getDeviceInfo", a)
        } else {
            var c = navigator.userAgent;
            mqq.__reportAPI("web", "device", "getClientInfo"),
            a({
                isMobileQQ: !0,
                systemName: "android",
                systemVersion: function(a) {
                    return a && a[1] || 0
                } (c.match(/\bAndroid ([\d\.]+)/i)),
                model: function(a) {
                    return a && a[1] || null
                } (c.match(/;\s([^;]+)\s\bBuild\/\w+/i))
            })
        }
    },
    support: {
        iOS: "4.5",
        android: "4.5"
    }
}),
function() {
    function a(a, c) {
        "string" == typeof a && /^{.*?}$/.test(a) && (a = JSON.parse(a)),
        a && "radio" in a && (a.radio = 1 === a.type ? "wifi": a.type >= 2 ? b[a.radio] || a.radio: "unknown"),
        c && c(a)
    }
    var b = {
        CTRadioAccessTechnologyGPRS: "gprs",
        CTRadioAccessTechnologyEdge: "edge",
        CTRadioAccessTechnologyWCDMA: "wcdma",
        CTRadioAccessTechnologyHSDPA: "hsdpa",
        CTRadioAccessTechnologyCDMA1x: "cdma",
        CTRadioAccessTechnologyCDMAEVDORev0: "evdo0",
        CTRadioAccessTechnologyCDMAEVDORevA: "evdoa",
        CTRadioAccessTechnologyCDMAEVDORevB: "evdob",
        CTRadioAccessTechnologyeHRPD: "ehrpd",
        CTRadioAccessTechnologyLTE: "lte",
        NETWORK_TYPE_GPRS: "gprs",
        NETWORK_TYPE_EDGE: "edge",
        NETWORK_TYPE_CDMA: "cdma",
        NETWORK_TYPE_1xRTT: "1xrtt",
        NETWORK_TYPE_EVDO_0: "evdo0",
        NETWORK_TYPE_EVDO_A: "evdoa",
        NETWORK_TYPE_EVDO_B: "evdob",
        NETWORK_TYPE_IDEN: "iden",
        NETWORK_TYPE_UMTS: "umts",
        NETWORK_TYPE_HSDPA: "hsdpa",
        NETWORK_TYPE_HSUPA: "hsupa",
        NETWORK_TYPE_HSPA: "hspa",
        NETWORK_TYPE_EHRPD: "ehrpd",
        NETWORK_TYPE_HSPAP: "hspap",
        NETWORK_TYPE_LTE: "lte",
        NETWORK_TYPE_WIFI: "wifi",
        NETWORK_TYPE_UNKNOWN: "unknown"
    };
    mqq.build("mqq.device.getNetworkInfo", {
        iOS: function(b) {
            mqq.invokeClient("device", "getNetworkInfo", {
                callback: mqq.callback(function(c) {
                    a(c, b)
                },
                !1, !0)
            })
        },
        android: function(b) {
            mqq.invokeClient("qbizApi", "getNetworkInfo",
            function(c) {
                a(c, b)
            })
        },
        support: {
            iOS: "5.2",
            android: "5.2"
        }
    })
} (),
mqq.build("mqq.device.getNetworkType", {
    iOS: function(a) {
        var b = mqq.invokeClient("device", "networkStatus");
        return b = Number(b),
        "function" != typeof a ? b: void mqq.__fireCallback(a, [b])
    },
    android: function(a) {
        mqq.compare("4.6") >= 0 ? mqq.invokeClient("qbizApi", "getNetworkType", a) : mqq.invokeClient("publicAccount", "getNetworkState",
        function(b) {
            var c = {
                "-1": 0,
                0 : 3,
                1 : 1
            },
            d = b in c ? c[b] : 4;
            a(d)
        })
    },
    support: {
        iOS: "4.5",
        android: "4.6"
    }
}),
mqq.build("mqq.device.networkStatus", {
    iOS: mqq.device.getNetworkType,
    support: {
        iOS: "4.5"
    }
}),
mqq.build("mqq.device.networkType", {
    iOS: mqq.device.getNetworkType,
    support: {
        iOS: "4.5"
    }
}),
mqq.build("mqq.device.getWebViewType", {
    iOS: function(a) {
        return mqq.invokeClient("device", "webviewType", a)
    },
    android: function(a) {
        var b = 1,
        c = navigator.userAgent;
        return /\bPA\b/.test(c) ? (b = 5, /\bCoupon\b/.test(c) ? b = 2 : /\bMyCoupon\b/.test(c) && (b = 3)) : /\bQR\b/.test(c) && (b = 4),
        mqq.__reportAPI("web", "device", "getWebViewType"),
        a ? a(b) : b
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.device.webviewType", {
    iOS: mqq.device.getWebViewType,
    support: {
        iOS: "4.6"
    }
}),
mqq.build("mqq.device.isMobileQQ", {
    iOS: function(a) {
        var b = ["iPhoneQQ", "iPadQQ"].indexOf(mqq.platform) > -1;
        return a ? a(b) : b
    },
    android: function(a) {
        var b = "AndroidQQ" === mqq.platform;
        return a ? a(b) : b
    },
    browser: function(a) {
        var b = ["iPhoneQQ", "iPadQQ", "AndroidQQ"].indexOf(mqq.platform) > -1;
        return a ? a(b) : b
    },
    support: {
        iOS: "4.2",
        android: "4.2"
    }
}),
mqq.build("mqq.device.setScreenStatus", {
    iOS: function(a, b) {
        a = a || {},
        a.callback = mqq.callback(b),
        mqq.invokeClient("device", "setScreenStatus", a)
    },
    android: function(a, b) {
        a = a || {},
        a.callback = mqq.callback(b),
        mqq.invokeClient("device", "setScreenStatus", a)
    },
    support: {
        android: "5.0"
    }
}),
mqq.build("mqq.media.getLocalImage", {
    iOS: function(a, b) {
        a.callback = mqq.callback(b),
        mqq.invokeClient("media", "getLocalImage", a)
    },
    android: function(a, b) {
        a.callback = mqq.callback(b),
        mqq.invokeClient("media", "getLocalImage", a)
    },
    support: {
        iOS: "4.7.2",
        android: "4.7.2"
    }
}),
mqq.build("mqq.media.getPicture", {
    iOS: function(a, b) { ! a.outMaxWidth && a.maxWidth && (a.outMaxWidth = a.maxWidth, delete a.maxWidth),
        !a.outMaxHeight && a.maxHeight && (a.outMaxHeight = a.maxHeight, delete a.maxHeight),
        a.callback = mqq.callback(function(a, c) {
            c && c.forEach && c.forEach(function(a, b) {
                "string" == typeof a && (c[b] = {
                    data: a,
                    imageID: "",
                    match: 0
                })
            }),
            b && b(a, c)
        },
        !0),
        mqq.invokeClient("media", "getPicture", a)
    },
    android: function(a, b) {
        a.callback = mqq.callback(b),
        mqq.invokeClient("media", "getPicture", a)
    },
    support: {
        iOS: "4.7",
        android: "4.7"
    }
}),
mqq.build("mqq.media.playLocalSound", {
    iOS: function(a) {
        mqq.invokeClient("sensor", "playLocalSound", a)
    },
    android: function(a) {
        mqq.invokeClient("qbizApi", "playVoice", a.bid, a.url)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.media.preloadSound", {
    iOS: function(a, b) {
        a.callback = mqq.callback(b, !0),
        mqq.invokeClient("sensor", "preloadSound", a)
    },
    android: function(a, b) {
        mqq.invokeClient("qbizApi", "preloadVoice", a.bid, a.url, mqq.callback(b, !0))
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.media.saveImage", {
    iOS: function(a, b) {
        a.callback = mqq.callback(b, !1),
        mqq.invokeClient("media", "saveImage", a)
    },
    android: function(a, b) {
        a.callback = mqq.callback(b, !1),
        mqq.invokeClient("media", "saveImage", a)
    },
    support: {
        iOS: "5.1",
        android: "5.2"
    }
}),
mqq.build("mqq.media.showPicture", {
    iOS: function(a, b) {
        mqq.invokeClient("troopNotice", "showPicture", a, b)
    },
    android: function(a, b) {
        mqq.invokeClient("troopNotice", "showPicture", a, b)
    },
    support: {
        iOS: "5.0",
        android: "5.0"
    }
}),
mqq.build("mqq.offline.batchCheckUpdate", {
    iOS: function(a, b) {
        b && (a.callback = mqq.callback(b)),
        mqq.invokeClient("offline", "batchCheckUpdate", a)
    },
    android: function(a, b) {
        a.callback = mqq.callback(function(a) {
            try {
                a = JSON.parse(a)
            } catch(c) {
                try {
                    a = new Function("return " + a)()
                } catch(c) {}
            }
            b && b(a || {})
        }),
        mqq.invokeClient("offline", "batchCheckUpdate", a)
    },
    support: {
        iOS: "5.4",
        android: "5.4"
    }
}),
mqq.build("mqq.offline.checkUpdate", {
    iOS: function(a, b) {
        var c = mqq.callback(b);
        c && (a.callback = c, mqq.invokeClient("offline", "checkUpdate", a))
    },
    android: function(a, b) {
        mqq.invokeClient("qbizApi", "checkUpdate", a.bid, mqq.callback(b))
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.offline.clearCache", {
    iOS: function(a, b) {
        b && (a.callback = mqq.callback(b)),
        mqq.invokeClient("offline", "clearCache", a)
    },
    android: function(a, b) {
        var c = b;
        b = function(a) {
            try {
                a = JSON.parse(a)
            } catch(b) {
                try {
                    a = new Function("return " + a)()
                } catch(b) {}
            }
            c && c(a || {})
        },
        mqq.invokeClient("offline", "clearCache", a, b)
    },
    support: {
        iOS: "5.4",
        android: "5.4"
    }
}),
mqq.build("mqq.offline.disableCache", {
    iOS: function(a) {
        mqq.invokeClient("offline", "disableCache", {
            callback: mqq.callback(a)
        })
    },
    android: function(a) {
        var b = a;
        a = function(a) {
            try {
                a = JSON.parse(a)
            } catch(c) {
                try {
                    a = new Function("return " + a)()
                } catch(c) {}
            }
            b && b(a || {})
        },
        mqq.invokeClient("offline", "disableCache", {},
        a)
    },
    support: {
        iOS: "5.4",
        android: "5.4"
    }
}),
mqq.build("mqq.offline.downloadUpdate", {
    iOS: function(a, b) {
        var c = mqq.callback(b, !1);
        c && (a.callback = c, mqq.invokeClient("offline", "downloadUpdate", a))
    },
    android: function(a, b) {
        var c = mqq.callback(b, !1);
        a.fileSize && a.fileSize > 0 ? mqq.invokeClient("qbizApi", "forceUpdate", a.bid, a.url, a.fileSize, c) : mqq.invokeClient("qbizApi", "forceUpdate", a.bid, a.url, c)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.offline.isCached", {
    iOS: function(a, b) {
        var c = mqq.callback(b);
        c && (a.callback = c, mqq.invokeClient("offline", "isCached", a))
    },
    android: function(a, b) {
        mqq.invokeClient("qbizApi", "isCached", a.bid, mqq.callback(b))
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.pay.enablePay", {
    iOS: function(a) {
        mqq.invokeClient("pay", "enablePay", {
            params: a
        })
    },
    support: {
        iOS: "4.6"
    }
}),
mqq.build("mqq.pay.pay", {
    iOS: function(a, b) {
        var c = b ? mqq.callback(b) : null;
        mqq.invokeClient("pay", "pay", {
            params: a,
            callback: c
        })
    },
    support: {
        iOS: "4.6"
    }
}),
mqq.build("mqq.redpoint.getAppInfo", {
    iOS: function(a, b) {
        var c = mqq.callback(b);
        c && (a.callback = c),
        mqq.invokeClient("redpoint", "getAppInfo", a)
    },
    android: function(a, b) {
        var c = mqq.callback(b);
        c && (a.callback = c),
        mqq.invokeClient("redpoint", "getAppInfo", a)
    },
    support: {
        iOS: "4.7",
        android: "4.7"
    }
}),
function() {
    function a(a) {
        var b = null;
        if (e === !1 && (e = "" == location.search ? "" == location.hash ? "": location.hash.substring(1) : location.search.substring(1), e = e.split("&"), e.length > 0)) for (var c = 0; c < e.length; c++) if (b = e[c], b = b.split("="), b.length > 1) try {
            f[b[0]] = decodeURIComponent(b[1])
        } catch(d) {
            f[b[0]] = ""
        }
        return "undefined" != typeof f[a] ? f[a] : ""
    }
    function b() {
        return "UID_" + ++m
    }
    function c(a, b) {
        var c = {
            sid: g,
            appid: a.substring(a.lastIndexOf(".") + 1),
            platid: h,
            qqver: i,
            format: "json",
            _: (new Date).getTime()
        },
        d = "get_new_msg_cnt";
        try {
            Zepto.ajax({
                type: "get",
                url: j + d,
                dataType: "json",
                data: c,
                timeout: 1e4,
                success: function(a) {
                    var c = {
                        ret: a.ecode,
                        count: 0
                    };
                    0 == a.ecode && (c.count = a.new_msg_cnt),
                    l[b].call(null, c),
                    delete l[b]
                },
                error: function() {
                    l[b].call(null, {
                        ret: -1,
                        list: []
                    }),
                    delete l[b]
                }
            })
        } catch(e) {
            l[b].call(null, {
                ret: -2,
                list: []
            }),
            delete l[b]
        }
    }
    function d(a, b) {
        if (0 == a.code) {
            var c = {
                ret: a.code,
                count: 0
            },
            d = a.data.buffer;
            if (d = "object" != typeof d && "" != d ? JSON.parse(d) : d, "undefined" != typeof d.msg) for (var e in d.msg) 1 == d.msg[e].stat && c.count++;
            l[b].call(null, c)
        } else l[b].call(null, {
            ret: a.code,
            list: []
        });
        delete l[b]
    }
    var e = !1,
    f = {},
    g = a("sid"),
    h = mqq.iOS ? 110 : mqq.android ? 109 : 0,
    i = mqq.QQVersion ? mqq.QQVersion: "",
    j = "http://msg.vip.qq.com/cgi-bin/",
    k = function() {
        return mqq.compare("4.7") >= 0
    } (),
    l = {},
    m = 1;
    mqq.build("mqq.redpoint.getNewMsgCnt", {
        iOS: function(a, e) {
            appid = String(a.path);
            var f = b();
            if (l[f] = e, k) mqq.redpoint.getAppInfo(a,
            function(a) {
                d(a, f)
            });
            else {
                if (!Zepto) return void("function" == typeof e ? e({
                    ret: -1e4,
                    count: 0
                }) : null);
                c(appid, f)
            }
        },
        android: function(a, e) {
            appid = String(a.path);
            var f = b();
            if (l[f] = e, k) mqq.redpoint.getAppInfo(a,
            function(a) {
                d(a, f)
            });
            else {
                if (!Zepto) return void("function" == typeof e ? e({
                    ret: -1e4,
                    count: 0
                }) : null);
                c(appid, f)
            }
        },
        support: {
            iOS: "4.5",
            android: "4.5"
        }
    })
} (),
function() {
    function a(a) {
        var b = null;
        if (e === !1 && (e = "" == location.search ? "" == location.hash ? "": location.hash.substring(1) : location.search.substring(1), e = e.split("&"), e.length > 0)) for (var c = 0; c < e.length; c++) if (b = e[c], b = b.split("="), b.length > 1) try {
            f[b[0]] = decodeURIComponent(b[1])
        } catch(d) {
            f[b[0]] = ""
        }
        return "undefined" != typeof f[a] ? f[a] : ""
    }
    function b() {
        return "UID_" + ++m
    }
    function c(a, b) {
        var c = {
            sid: g,
            appid: a.substring(a.lastIndexOf(".") + 1),
            platid: h,
            qqver: i,
            format: "json",
            _: (new Date).getTime()
        },
        d = "read_msg";
        try {
            Zepto.ajax({
                type: "get",
                url: j + d,
                dataType: "json",
                data: c,
                timeout: 1e4,
                success: function(a) {
                    var c = {
                        ret: a.ecode,
                        list: []
                    };
                    if (0 == a.ecode) {
                        var d = a.msg,
                        e = [];
                        for (var f in d) e.push({
                            content: d[f].content ? d[f].content: "",
                            link: d[f].link ? d[f].link: "",
                            img: d[f].img ? d[f].img: "",
                            pubTime: d[f].time ? d[f].time: "",
                            title: d[f].title ? d[f].title: "",
                            src: d[f].src ? d[f].src: "",
                            ext1: d[f].ext1 ? d[f].ext1: "",
                            ext2: d[f].ext2 ? d[f].ext2: "",
                            ext3: d[f].ext3 ? d[f].ext3: "",
                            id: f
                        });
                        c.list = e
                    }
                    l[b].call(null, c),
                    delete l[b]
                },
                error: function() {
                    l[b].call(null, {
                        ret: -1,
                        list: []
                    }),
                    delete l[b]
                }
            })
        } catch(e) {
            l[b].call(null, {
                ret: -2,
                list: []
            }),
            delete l[b]
        }
    }
    function d(a, b) {
        if (0 == a.code) {
            var c = {
                ret: a.code,
                list: []
            },
            d = a.data.buffer,
            e = [];
            if (d = "object" != typeof d && "" != d ? JSON.parse(d) : d, "undefined" != typeof d.msg) {
                for (var f in d.msg) 1 == d.msg[f].stat && (e.push({
                    content: d.msg[f].content ? d.msg[f].content: "",
                    link: d.msg[f].link ? d.msg[f].link: "",
                    img: d.msg[f].img ? d.msg[f].img: "",
                    pubTime: d.msg[f].time ? d.msg[f].time: "",
                    title: d.msg[f].title ? d.msg[f].title: "",
                    src: d.msg[f].src ? d.msg[f].src: "",
                    ext1: d.msg[f].ext1 ? d.msg[f].ext1: "",
                    ext2: d.msg[f].ext2 ? d.msg[f].ext2: "",
                    ext3: d.msg[f].ext3 ? d.msg[f].ext3: "",
                    id: f
                }), d.msg[f].stat = 2);
                if (a.data.buffer = JSON.stringify(d), e.length > 0) {
                    c.list = e,
                    mqq.redpoint.setAppInfo({
                        appInfo: a.data
                    },
                    function(a) {
                        console.log(JSON.stringify(a))
                    });
                    var k = a.data.appID,
                    m = {
                        sid: g,
                        appid: k,
                        platid: h,
                        qqver: i,
                        format: "json",
                        _: (new Date).getTime()
                    },
                    n = "read_msg";
                    try {
                        Zepto.ajax({
                            type: "get",
                            url: j + n,
                            dataType: "json",
                            data: m,
                            timeout: 1e4,
                            success: function() {},
                            error: function() {}
                        })
                    } catch(o) {}
                }
            }
            l[b].call(null, c)
        } else l[b].call(null, {
            ret: a.code,
            list: []
        });
        delete l[b]
    }
    var e = !1,
    f = {},
    g = a("sid"),
    h = mqq.iOS ? 110 : mqq.android ? 109 : 0,
    i = mqq.QQVersion ? mqq.QQVersion: "",
    j = "http://msg.vip.qq.com/cgi-bin/",
    k = function() {
        return mqq.compare("4.7") >= 0
    } (),
    l = {},
    m = 1;
    mqq.build("mqq.redpoint.getNewMsgList", {
        iOS: function(a, e) {
            appid = String(a.path);
            var f = b();
            if (l[f] = e, k) mqq.redpoint.getAppInfo(a,
            function(a) {
                d(a, f)
            });
            else {
                if (!Zepto) return void("function" == typeof e ? e({
                    ret: -1e4,
                    count: 0
                }) : null);
                c(appid, f)
            }
        },
        android: function(a, e) {
            appid = String(a.path);
            var f = b();
            if (l[f] = e, k) mqq.redpoint.getAppInfo(a,
            function(a) {
                d(a, f)
            });
            else {
                if (!Zepto) return void("function" == typeof e ? e({
                    ret: -1e4,
                    count: 0
                }) : null);
                c(appid, f)
            }
        },
        support: {
            iOS: "4.5",
            android: "4.5"
        }
    })
} (),
mqq.build("mqq.redpoint.getRedPointShowInfo", {
    iOS: function(a, b) {
        var c = mqq.callback(b);
        c && (a.callback = c),
        mqq.invokeClient("redpoint", "getRedPointShowInfo", a)
    },
    android: function(a, b) {
        var c = mqq.callback(b);
        c && (a.callback = c),
        mqq.invokeClient("redpoint", "getRedPointShowInfo", a)
    },
    support: {
        iOS: "4.7",
        android: "4.7"
    }
}),
mqq.build("mqq.redpoint.isEnterFromRedPoint", {
    iOS: function(a, b) {
        var c = mqq.callback(b, !0);
        c && (a.callback = c),
        mqq.invokeClient("redpoint", "isEnterFromRedPoint", a)
    },
    android: function(a, b) {
        var c = mqq.callback(b, !0);
        c && (a.callback = c),
        mqq.invokeClient("redpoint", "isEnterFromRedPoint", a)
    },
    support: {
        iOS: "5.4",
        android: "5.4"
    }
}),
function() {
    var a = function(a, b) {
        "function" != typeof b && (b = function() {});
        for (var c = ["path", "service_type", "service_id", "act_id", "obj_id", "pay_amt"], d = 0, e = c.length; e > d; ++d) if ("undefined" == typeof a[c[d]]) return b({
            code: -1,
            errorMessage: "params invalid"
        }),
        b = null,
        !1;
        mqq.redpoint.isEnterFromRedPoint({
            path: a.path
        },
        function(c) {
            0 == c.code && 1 == c.data ? (b && (a.callback = mqq.callback(b, !0)), mqq.invokeClient("redpoint", "reportBusinessRedTouch", a)) : (b({
                code: -1,
                errorMessage: c.errorMessage
            }), a = null, b = null)
        })
    };
    mqq.build("mqq.redpoint.reportBusinessRedTouch", {
        iOS: a,
        android: a,
        support: {
            iOS: "5.4",
            android: "5.4"
        }
    })
} (),
mqq.build("mqq.redpoint.reportRedTouch", {
    iOS: function(a, b) {
        var c = mqq.callback(b);
        c && (a.callback = c),
        mqq.invokeClient("redpoint", "reportRedTouch", a)
    },
    android: function(a, b) {
        var c = mqq.callback(b);
        c && (a.callback = c),
        mqq.invokeClient("redpoint", "reportRedTouch", a)
    },
    support: {
        iOS: "4.7",
        android: "4.7"
    }
}),
mqq.build("mqq.redpoint.setAppInfo", {
    iOS: function(a, b) {
        var c = mqq.callback(b);
        c && (a.callback = c),
        mqq.invokeClient("redpoint", "setAppInfo", a)
    },
    android: function(a, b) {
        var c = mqq.callback(b);
        c && (a.callback = c),
        mqq.invokeClient("redpoint", "setAppInfo", a)
    },
    support: {
        iOS: "4.7",
        android: "4.7"
    }
}),
mqq.build("mqq.sensor.getLocation", {
    iOS: function(a) {
        var b = arguments[arguments.length - 1],
        c = "object" == typeof a ? a: {};
        return "function" == typeof b && (c.callback = mqq.callback(b)),
        mqq.invokeClient("data", "queryCurrentLocation", c)
    },
    android: function(a) {
        var b = arguments[arguments.length - 1],
        c = "object" == typeof a ? a: {},
        d = mqq.callback(function(a) {
            var c = -1,
            d = null,
            e = null;
            a && "null" !== a && (a = (a + "").split(","), 2 === a.length && (c = 0, d = parseFloat(a[0] || 0), e = parseFloat(a[1] || 0))),
            b(c, e, d)
        },
        !0);
        "function" == typeof b && (c.callback = d),
        mqq.invokeClient("publicAccount", "getLocation", mqq.compare("5.5") > -1 ? c: d)
    },
    browser: function() {
        var a = arguments[arguments.length - 1];
        navigator.geolocation ? navigator.geolocation.getCurrentPosition(function(b) {
            var c = b.coords.latitude,
            d = b.coords.longitude;
            a(0, c, d)
        },
        function() {
            a( - 1)
        }) : a( - 1)
    },
    support: {
        iOS: "4.5",
        android: "4.6",
        browser: "0"
    }
}),
mqq.build("mqq.sensor.getRealLocation", {
    iOS: function(a, b) {
        var c = b ? mqq.callback(b) : null;
        return mqq.invokeClient("data", "getOSLocation", {
            params: a,
            callback: c
        })
    },
    android: function(a, b) {
        a = JSON.stringify(a || {}),
        mqq.invokeClient("publicAccount", "getRealLocation", a, mqq.callback(b, !0))
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.sensor.getSensorStatus", {
    iOS: function(a, b) {
        a = a || {
            type: "gps"
        },
        a.callbackName = mqq.callback(b),
        mqq.invokeClient("sensor", "getSensorStatus", a)
    },
    support: {
        iOS: "4.7"
    }
}),
mqq.build("mqq.sensor.startAccelerometer", {
    iOS: function(a) {
        var b = mqq.callback(a, !1, !0);
        b && mqq.invokeClient("sensor", "startAccelerometer", {
            callback: b
        })
    },
    android: function(a) {
        var b = mqq.callback(a, !1, !0);
        mqq.invokeClient("qbizApi", "startAccelerometer", b)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.sensor.startCompass", {
    iOS: function(a) {
        var b = mqq.callback(a, !1, !0);
        b && mqq.invokeClient("sensor", "startCompass", {
            callback: b
        })
    },
    android: function(a) {
        var b = mqq.callback(a, !1, !0);
        mqq.invokeClient("qbizApi", "startCompass", b)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.sensor.startListen", {
    iOS: function(a) {
        var b = mqq.callback(a, !1, !0);
        b && mqq.invokeClient("sensor", "startListen", {
            callback: b
        })
    },
    android: function(a) {
        var b = mqq.callback(a, !1, !0);
        mqq.invokeClient("qbizApi", "startListen", b)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.sensor.stopAccelerometer", {
    iOS: function() {
        mqq.invokeClient("sensor", "stopAccelerometer")
    },
    android: function() {
        mqq.invokeClient("qbizApi", "stopAccelerometer")
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.sensor.stopCompass", {
    iOS: function() {
        mqq.invokeClient("sensor", "stopCompass")
    },
    android: function() {
        mqq.invokeClient("qbizApi", "stopCompass")
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.sensor.stopListen", {
    iOS: function() {
        mqq.invokeClient("sensor", "stopListen")
    },
    android: function() {
        mqq.invokeClient("qbizApi", "stopListen")
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.sensor.vibrate", {
    iOS: function(a) {
        a = a || {},
        mqq.invokeClient("sensor", "vibrate", a)
    },
    android: function(a) {
        a = a || {},
        mqq.invokeClient("qbizApi", "phoneVibrate", a.time)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.tenpay.buyGoods", {
    android: function(a, b) {
        mqq.invokeClient("pay", "buyGoods", JSON.stringify(a), b)
    },
    support: {
        android: "4.6.1"
    }
}),
mqq.build("mqq.tenpay.isOpenSecurityPay", {
    android: function(a, b) {
        var a = {};
        b && (a.callback = mqq.callback(b)),
        mqq.invokeClient("qw_charge", "qqpimsecure_safe_isopen_securitypay", a)
    },
    support: {
        android: "5.3.0"
    }
}),
mqq.build("mqq.tenpay.openService", {
    iOS: function(a, b) {
        mqq.invokeClient("pay", "openService", JSON.stringify(a), b)
    },
    android: function(a, b) {
        mqq.invokeClient("pay", "openService", JSON.stringify(a), b)
    },
    support: {
        iOS: "5.7",
        android: "4.6.1"
    }
}),
mqq.build("mqq.tenpay.openTenpayView", {
    iOS: function(a, b) {
        var c = b ? mqq.callback(b) : null;
        mqq.invokeClient("pay", "openTenpayView", {
            params: a,
            callback: c
        })
    },
    android: function(a, b) {
        mqq.invokeClient("pay", "openTenpayView", JSON.stringify(a), b)
    },
    support: {
        iOS: "4.6.1",
        android: "4.6.1"
    }
}),
function() {
    var a = function(a) {
        return function(b, c, d) {
            if (d) try {
                return void(a && a(JSON.parse(d)))
            } catch(e) {}
            b = Number(b);
            var f = {
                resultCode: b,
                retmsg: "",
                data: {}
            };
            if (0 === b) {
                var g = c;
                c = mqq.mapQuery(c),
                c.sp_data = g,
                c.attach && 0 === c.attach.indexOf("{") && (c.attach = JSON.parse(c.attach)),
                c.time_end && (c.pay_time = c.time_end),
                f.data = c
            } else 1 === b || -1 === b ? (f.retmsg = "用户主动放弃支付", f.resultCode = -1) : f.retmsg = c;
            a && a(f)
        }
    };
    mqq.build("mqq.tenpay.pay", {
        iOS: function(b, c) {
            b.order_no = b.tokenId || b.tokenID,
            b.app_info = b.app_info || b.appInfo,
            mqq.compare("4.6.2") >= 0 ? mqq.invokeSchema("mqqapi", "wallet", "pay", b, a(c)) : mqq.invokeSchema("mqqapiwallet", "wallet", "pay", b, a(c))
        },
        android: function(b, c) {
            b.token_id = b.tokenId || b.tokenID,
            b.app_info = b.app_info || b.appInfo,
            mqq.compare("4.6.1") >= 0 ? mqq.invokeClient("pay", "pay", JSON.stringify(b), c) : mqq.invokeSchema("mqqapi", "tenpay", "pay", b, a(c))
        },
        support: {
            iOS: "4.6.1",
            android: "4.6.1"
        }
    })
} (),
mqq.build("mqq.tenpay.rechargeGameCurrency", {
    android: function(a, b) {
        mqq.invokeClient("pay", "rechargeGameCurrency", JSON.stringify(a), b)
    },
    support: {
        android: "4.6.1"
    }
}),
mqq.build("mqq.tenpay.rechargeQb", {
    iOS: function(a, b) {
        mqq.invokeClient("tenpay", "rechargeQb", JSON.stringify(a), b)
    },
    android: function(a, b) {
        mqq.invokeClient("pay", "rechargeQb", JSON.stringify(a), b)
    },
    support: {
        iOS: "5.4",
        android: "4.6.1"
    }
}),
mqq.build("mqq.ui.addShortcut", {
    iOS: function(a) {
        mqq.invokeClient("nav", "openLinkInSafari", {
            url: "http://pub.idqqimg.com/qqmobile/shortcut.ios.html?" + mqq.toQuery(a) + "#from=mqq"
        })
    },
    android: function(a) {
        a.data = {
            title: a.title,
            icon: a.icon,
            url: a.url
        },
        a.callback = mqq.callback(a.callback),
        mqq.invokeClient("ui", "addShortcut", a)
    },
    support: {
        iOS: "5.8",
        android: "5.8"
    }
}),
mqq.build("mqq.ui.closeWebViews", {
    iOS: function(a) {
        mqq.invokeClient("ui", "closeWebViews", a || {})
    },
    android: function(a) {
        mqq.invokeClient("ui", "closeWebViews", a || {})
    },
    support: {
        iOS: "5.2",
        android: "5.2"
    }
}),
mqq.build("mqq.ui.openAIO", {
    iOS: function(a) {
        mqq.invokeSchema("mqqapi", "im", "chat", a)
    },
    android: function(a) {
        mqq.invokeSchema("mqqapi", "im", "chat", a)
    },
    support: {
        iOS: "4.5",
        android: "4.5"
    }
}),
mqq.build("mqq.ui.openGroupCard", {
    iOS: function(a) {
        mqq.invokeClient("nav", "openSpecialView", a)
    },
    android: function(a) {
        mqq.invokeClient("ui", "openSpecialView", {
            viewName: "troopMemberCard",
            param: a
        })
    },
    support: {
        iOS: "5.8",
        android: "5.8"
    }
}),
mqq.build("mqq.ui.openGroupFileView", {
    iOS: function(a) {
        mqq.invokeClient("ui", "openGroupFileView", a)
    },
    android: function(a) {
        mqq.invokeClient("ui", "openSpecialView", {
            viewName: "groupFile",
            param: a
        })
    },
    support: {
        iOS: "5.4",
        android: "5.4"
    }
}),
mqq.build("mqq.ui.openGroupPhotoView", {
    iOS: function(a) {
        mqq.invokeClient("ui", "openGroupPhotoView", a)
    },
    android: function(a) {
        mqq.invokeClient("ui", "openSpecialView", {
            viewName: "groupPhoto",
            param: a
        })
    },
    support: {
        iOS: "5.4",
        android: "5.4"
    }
}),
mqq.build("mqq.ui.openUrl", {
    iOS: function(a) {
        switch (a || (a = {}), a.target) {
        case 0:
            window.open(a.url, "_self");
            break;
        case 1:
            a.styleCode = {
                1 : 4,
                2 : 2,
                3 : 5
            } [a.style] || 1,
            mqq.invokeClient("nav", "openLinkInNewWebView", {
                url: a.url,
                options: a
            });
            break;
        case 2:
            mqq.invokeClient("nav", "openLinkInSafari", {
                url: a.url
            })
        }
    },
    android: function(a) {
        2 === a.target ? mqq.compare("4.6") >= 0 ? mqq.invokeClient("publicAccount", "openInExternalBrowser", a.url) : mqq.compare("4.5") >= 0 && mqq.invokeClient("openUrlApi", "openUrl", a.url) : 1 === a.target ? (a.style || (a.style = 0), mqq.compare("4.7") >= 0 ? mqq.invokeClient("ui", "openUrl", {
            url: a.url,
            options: a
        }) : mqq.compare("4.6") >= 0 ? mqq.invokeClient("qbizApi", "openLinkInNewWebView", a.url, a.style) : mqq.compare("4.5") >= 0 ? mqq.invokeClient("publicAccount", "openUrl", a.url) : location.href = a.url) : location.href = a.url
    },
    browser: function(a) {
        2 === a.target ? window.open(a.url, "_blank") : location.href = a.url
    },
    support: {
        iOS: "4.5",
        android: "4.6",
        browser: "0"
    }
}),
function() {
    var a = {},
    b = {
        Abount: "com.tencent.mobileqq.activity.AboutActivity",
        GroupTribePublish: "com.tencent.mobileqq.troop.activity.TroopBarPublishActivity",
        GroupTribeReply: "com.tencent.mobileqq.troop.activity.TroopBarReplyActivity",
        GroupTribeComment: "com.tencent.mobileqq.troop.activity.TroopBarCommentActivity"
    };
    mqq.build("mqq.ui.openView", {
        iOS: function(b) {
            b.name = a[b.name] || b.name,
            "function" == typeof b.onclose && (b.onclose = mqq.callback(b.onclose)),
            mqq.invokeClient("nav", "openViewController", b)
        },
        android: function(a) {
            a.name = b[a.name] || a.name,
            "function" == typeof a.onclose && (a.onclose = mqq.callback(a.onclose)),
            mqq.compare("5.0") > -1 ? mqq.invokeClient("ui", "openView", a) : mqq.invokeClient("publicAccount", "open", a.name)
        },
        support: {
            iOS: "4.5",
            android: "4.6"
        }
    })
} (),
mqq.build("mqq.ui.pageVisibility", {
    iOS: function(a) {
        mqq.invokeClient("ui", "pageVisibility", a)
    },
    android: function(a) {
        mqq.invokeClient("ui", "pageVisibility", a)
    },
    support: {
        iOS: "4.7",
        android: "4.7"
    }
}),
mqq.build("mqq.ui.popBack", {
    iOS: function() {
        mqq.invokeClient("nav", "popBack")
    },
    android: function() {
        mqq.invokeClient("publicAccount", "close")
    },
    support: {
        iOS: "4.5",
        android: "4.6"
    }
}),
mqq.build("mqq.ui.refreshTitle", {
    iOS: function() {
        mqq.invokeClient("nav", "refreshTitle")
    },
    support: {
        iOS: "4.6"
    }
}),
mqq.build("mqq.ui.returnToAIO", {
    iOS: function() {
        mqq.invokeClient("nav", "returnToAIO")
    },
    android: function() {
        mqq.invokeClient("qbizApi", "returnToAIO")
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.ui.scanQRcode", {
    iOS: function(a, b) {
        a = a || {},
        b && (a.callback = mqq.callback(b)),
        mqq.invokeClient("ui", "scanQRcode", a)
    },
    android: function(a, b) {
        a = a || {},
        b && (a.callback = mqq.callback(b)),
        mqq.invokeClient("ui", "scanQRcode", a)
    },
    support: {
        iOS: "4.7",
        android: "4.7"
    }
}),
mqq.build("mqq.ui.selectContact", {
    iOS: function(a) {
        var b = mqq.callback(a.callback);
        a.callback = b,
        mqq.invokeClient("ui", "selectContact", a)
    },
    android: function(a) {
        var b = mqq.callback(a.callback);
        a.callback = b,
        mqq.invokeClient("ui", "selectContact", a)
    },
    support: {
        iOS: "5.3",
        android: "5.3"
    }
}),
mqq.build("mqq.ui.setActionButton", {
    iOS: function(a, b) {
        "object" != typeof a && (a = {
            title: a
        });
        var c = mqq.callback(b);
        a.callback = c,
        mqq.invokeClient("nav", "setActionButton", a)
    },
    android: function(a, b) {
        var c = mqq.callback(b);
        a.hidden && (a.title = ""),
        mqq.compare("4.7") >= 0 ? (a.callback = c, mqq.invokeClient("ui", "setActionButton", a)) : mqq.invokeClient("publicAccount", "setRightButton", a.title, "", c || null)
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.ui.setLoading", {
    iOS: function(a) {
        a && (a.visible === !0 ? mqq.invokeClient("nav", "showLoading") : a.visible === !1 && mqq.invokeClient("nav", "hideLoading"), a.color && mqq.invokeClient("nav", "setLoadingColor", {
            r: a.color[0],
            g: a.color[1],
            b: a.color[2]
        }))
    },
    android: function(a) {
        "visible" in a && (a.visible ? mqq.invokeClient("publicAccount", "showLoading") : mqq.invokeClient("publicAccount", "hideLoading"))
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.ui.setOnCloseHandler", {
    iOS: function(a) {
        mqq.invokeClient("ui", "setOnCloseHandler", {
            callback: mqq.callback(a)
        })
    },
    android: function(a) {
        mqq.invokeClient("ui", "setOnCloseHandler", {
            callback: mqq.callback(a)
        })
    },
    support: {
        iOS: "4.7",
        android: "4.7"
    }
}),
mqq.build("mqq.ui.setOnShareHandler", {
    iOS: function(a) {
        mqq.invokeClient("nav", "addWebShareListener", {
            callback: mqq.callback(a)
        })
    },
    android: function(a) {
        mqq.invokeClient("ui", "setOnShareHandler", {
            callback: mqq.callback(a)
        })
    },
    support: {
        iOS: "4.7.2",
        android: "4.7.2"
    }
}),
mqq.build("mqq.ui.setPullDown", {
    iOS: function(a) {
        mqq.invokeClient("ui", "setPullDown", a)
    },
    android: function(a) {
        mqq.invokeClient("ui", "setPullDown", a)
    },
    support: {
        iOS: "5.3",
        android: "5.3"
    }
}),
mqq.build("mqq.ui.setRightDragToGoBackParams", {
    iOS: function(a) {
        mqq.invokeClient("ui", "setRightDragToGoBackParams", a)
    },
    support: {
        iOS: "5.3"
    }
}),
mqq.build("mqq.ui.setTitleButtons", {
    iOS: function(a) {
        var b = a.left,
        c = a.right;
        b && (b.callback = mqq.callback(b.callback)),
        c && (c.callback = mqq.callback(c.callback)),
        mqq.compare("5.3") >= 0 ? mqq.invokeClient("ui", "setTitleButtons", a) : (b && (b.title && mqq.invokeClient("ui", "setLeftBtnTitle", {
            title: b.title
        }), b.callback && mqq.invokeClient("ui", "setOnCloseHandler", b)), c && mqq.invokeClient("nav", "setActionButton", c))
    },
    android: function(a) {
        var b = a.left,
        c = a.right;
        b && (b.callback = mqq.callback(b.callback)),
        c && (c.callback = mqq.callback(c.callback)),
        mqq.compare("5.3") >= 0 ? mqq.invokeClient("ui", "setTitleButtons", a) : (b && b.callback && mqq.invokeClient("ui", "setOnCloseHandler", b), c && (c.hidden && (c.title = ""), mqq.compare("4.7") >= 0 ? mqq.invokeClient("ui", "setActionButton", c) : mqq.invokeClient("publicAccount", "setRightButton", c.title, "", c.callback)))
    },
    support: {
        iOS: "5.0",
        android: "4.6"
    }
}),
mqq.build("mqq.ui.setWebViewBehavior", {
    iOS: function(a) {
        mqq.invokeClient("ui", "setWebViewBehavior", a)
    },
    android: function(a) {
        mqq.invokeClient("ui", "setWebViewBehavior", a)
    },
    support: {
        iOS: "4.7.2",
        android: "5.1"
    }
}),
mqq.build("mqq.ui.shareAudio", {
    iOS: function(a, b) {
        var c = mqq.callback(b, !0);
        a.desc && (a.desc = a.desc.length > 50 ? a.desc.substring(0, 50) + "...": a.desc),
        mqq.invokeClient("nav", "shareAudio", {
            params: a,
            callback: c
        })
    },
    android: function(a, b) {
        a.req_type = 2,
        b && (a.callback = mqq.callback(b, !0)),
        a.desc && (a.desc = a.desc.length > 50 ? a.desc.substring(0, 50) + "...": a.desc),
        mqq.invokeClient("QQApi", "shareMsg", a)
    },
    support: {
        iOS: "4.7",
        android: "4.7"
    }
}),
mqq.build("mqq.ui.shareMessage", {
    iOS: function(a, b) { ! ("needPopBack" in a) && "back" in a && (a.needPopBack = a.back),
        a.share_url && (a.share_url = mqq.removeQuery(a.share_url, ["sid", "3g_sid"])),
        a.desc && (a.desc = a.desc.length > 50 ? a.desc.substring(0, 50) + "...": a.desc),
        a.sourceName && (a.srcName = a.sourceName),
        a.callback = mqq.callback(b, !0, !0),
        mqq.invokeClient("nav", "shareURLWebRichData", a)
    },
    android: function(a, b) {
        if (a.share_url && (a.share_url = mqq.removeQuery(a.share_url, ["sid", "3g_sid"])), a.callback = mqq.callback(function(a) {
            b && b({
                retCode: a ? 0 : 1
            })
        },
        !0), a.desc && (a.desc = a.desc.length > 50 ? a.desc.substring(0, 50) + "...": a.desc), a.srcName && (a.sourceName = a.srcName), a.share_type && (2 === a.share_type || 3 === a.share_type) && mqq.compare("5.2") < 0 && mqq.support("mqq.app.isAppInstalled")) {
            var c = "您尚未安装微信，不可使用此功能";
            mqq.app.isAppInstalled("com.tencent.mm",
            function(b) {
                b ? mqq.invokeClient("QQApi", "shareMsg", a) : mqq.support("mqq.ui.showTips") ? mqq.ui.showTips({
                    text: c
                }) : alert(c)
            })
        } else mqq.invokeClient("QQApi", "shareMsg", a)
    },
    support: {
        iOS: "4.7.2",
        android: "4.7.2"
    }
}),
mqq.build("mqq.ui.shareRichMessage", {
    iOS: function(a, b) {
        a.puin = a.oaUin,
        a.desc = a.desc || a.summary,
        a.share_url && (a.share_url = mqq.removeQuery(a.share_url, ["sid", "3g_sid"])),
        a.desc && (a.desc = a.desc.length > 50 ? a.desc.substring(0, 50) + "...": a.desc),
        a.callback = mqq.callback(b),
        mqq.invokeClient("nav", "officalAccountShareRichMsg2QQ", a)
    },
    android: function(a, b) {
        a.puin = a.oaUin,
        a.desc = a.desc || a.summary,
        a.desc && (a.desc = a.desc.length > 50 ? a.desc.substring(0, 50) + "...": a.desc),
        mqq.compare("5.0") >= 0 ? (a.share_url = a.share_url || a.targetUrl, a.image_url = a.image_url || a.imageUrl, a.share_url && (a.share_url = mqq.removeQuery(a.share_url, ["sid", "3g_sid"])), a.callback = b ? mqq.callback(function(a) {
            b({
                ret: a ? 0 : 1
            })
        }) : null, mqq.invokeClient("QQApi", "shareMsg", a)) : (a.targetUrl = a.targetUrl || a.share_url, a.imageUrl = a.imageUrl || a.image_url, a.targetUrl && (a.targetUrl = mqq.removeQuery(a.targetUrl, ["sid", "3g_sid"])), a.callback = mqq.callback(b), mqq.invokeClient("publicAccount", "officalAccountShareRichMsg2QQ", a))
    },
    support: {
        iOS: "4.7",
        android: "4.7"
    }
}),
mqq.build("mqq.data.shareRichMessage", {
    iOS: mqq.ui.shareRichMessage,
    android: mqq.ui.shareRichMessage,
    support: {
        iOS: "4.7",
        android: "4.7"
    }
}),
mqq.build("mqq.ui.showActionSheet", {
    iOS: function(a, b) {
        return b && (a.onclick = mqq.callback(b)),
        mqq.invokeClient("ui", "showActionSheet", a)
    },
    android: function(a, b) {
        return b && (a.onclick = mqq.callback(b)),
        mqq.invokeClient("ui", "showActionSheet", a)
    },
    support: {
        iOS: "4.7",
        android: "4.7"
    }
}),
mqq.build("mqq.ui.showBarAccountDetail", {
    iOS: function(a) {
        var b = "object" == typeof a ? a: {
            uin: a
        };
        b.type = 3,
        mqq.invokeClient("nav", "showOfficalAccountDetail", b)
    },
    android: function(a) {
        mqq.invokeClient("publicAccount", "viewTroopBarAccount", a.uin)
    },
    support: {
        iOS: "5.6",
        android: "5.4"
    }
}),
mqq.build("mqq.ui.showDialog", {
    iOS: function(a, b) {
        a && (a.callback = mqq.callback(b, !0), a.title = a.title + "", a.text = a.text + "", "needOkBtn" in a || (a.needOkBtn = !0), "needCancelBtn" in a || (a.needCancelBtn = !0), a.okBtnStr = a.okBtnText, a.cancelBtnStr = a.cancelBtnText, mqq.invokeClient("nav", "showDialog", a))
    },
    android: function(a, b) {
        if (mqq.compare("4.8.0") >= 0) a.callback = mqq.callback(b, !0),
        mqq.invokeClient("ui", "showDialog", a);
        else {
            var c = "",
            d = "";
            b && (c = mqq.callback(function() {
                b({
                    button: 0
                })
            },
            !0), d = mqq.callback(function() {
                b({
                    button: 1
                })
            },
            !0), c += "()", d += "()"),
            a.title = a.title + "",
            a.text = a.text + "",
            "needOkBtn" in a || (a.needOkBtn = !0),
            "needCancelBtn" in a || (a.needCancelBtn = !0),
            mqq.invokeClient("publicAccount", "showDialog", a.title, a.text, a.needOkBtn, a.needCancelBtn, c, d)
        }
    },
    support: {
        iOS: "4.6",
        android: "4.6"
    }
}),
mqq.build("mqq.ui.showEQQ", {
    iOS: function(a) {
        mqq.invokeClient("nav", "showBusinessAccountProfile", a)
    },
    android: function(a) {
        mqq.invokeClient("eqq", "showEQQ", a)
    },
    support: {
        iOS: "4.7",
        android: "4.7"
    }
}),
mqq.build("mqq.ui.showOfficalAccountDetail", {
    iOS: function(a) {
        var b = "object" == typeof a ? a: {
            uin: a
        };
        mqq.invokeClient("nav", "showOfficalAccountDetail", b)
    },
    android: function(a) {
        mqq.compare("4.6") >= 0 ? mqq.invokeClient("publicAccount", "viewAccount", a.uin, a.showAIO) : mqq.invokeClient("publicAccount", "viewAccount", a.uin)
    },
    support: {
        iOS: "4.5",
        android: "4.6"
    }
}),
mqq.build("mqq.ui.showProfile", {
    iOS: function(a) {
        mqq.compare("4.7") >= 0 ? mqq.invokeClient("nav", "showProfile", a) : mqq.compare("4.6") >= 0 && !a.uinType ? mqq.invokeClient("nav", "showProfile", a) : (1 === a.uinType && (a.card_type = "group"), mqq.invokeSchema("mqqapi", "card", "show_pslcard", a))
    },
    android: function(a) {
        mqq.compare("4.7") >= 0 ? mqq.invokeClient("publicAccount", "showProfile", a) : mqq.compare("4.6") >= 0 && !a.uinType ? mqq.invokeClient("publicAccount", "showProfile", a.uin) : (1 === a.uinType && (a.card_type = "group"), mqq.invokeSchema("mqqapi", "card", "show_pslcard", a))
    },
    support: {
        iOS: "4.5",
        android: "4.5"
    }
}),
mqq.build("mqq.ui.showShareMenu", {
    iOS: function() {
        mqq.invokeClient("ui", "showShareMenu")
    },
    android: function() {
        mqq.invokeClient("ui", "showShareMenu")
    },
    support: {
        iOS: "5.2",
        android: "5.2"
    }
}),
mqq.build("mqq.ui.showTips", {
    iOS: function(a) {
        a.iconMode = a.iconMode || 2,
        mqq.invokeClient("ui", "showTips", a)
    },
    android: function(a) {
        a.iconMode = a.iconMode || 2,
        mqq.invokeClient("ui", "showTips", a)
    },
    support: {
        iOS: "4.7",
        android: "4.7"
    }
}),
mqq.build("mqq.viewTracks.getTrackInfo", {
    iOS: function(a, b) {
        a = a || {};
        var c = mqq.callback(b);
        c && (a.callback = c),
        mqq.invokeClient("viewTracks", "getTrackInfo", a)
    },
    android: function(a, b) {
        a = a || {};
        var c = mqq.callback(b);
        c && (a.callback = c),
        mqq.invokeClient("viewTracks", "getTrackInfo", a)
    },
    support: {
        iOS: "5.1",
        android: "5.1"
    }
}),
mqq.build("mqq.viewTracks.pop", {
    iOS: function(a, b) {
        a = a || {};
        var c = mqq.callback(b);
        c && (a.callback = c),
        mqq.invokeClient("viewTracks", "pop", a)
    },
    android: function(a, b) {
        a = a || {};
        var c = mqq.callback(b);
        c && (a.callback = c),
        mqq.invokeClient("viewTracks", "pop", a)
    },
    support: {
        iOS: "5.1",
        android: "5.1"
    }
}),
mqq.build("mqq.viewTracks.push", {
    iOS: function(a, b) {
        var c = mqq.callback(b);
        c && (a.callback = c),
        mqq.invokeClient("viewTracks", "push", a)
    },
    android: function(a, b) {
        var c = mqq.callback(b);
        c && (a.callback = c),
        mqq.invokeClient("viewTracks", "push", a)
    },
    support: {
        iOS: "5.1",
        android: "5.1"
    }
});