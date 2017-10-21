!function(a, b) {
    a.InvokeApp = b()
}(this, function() {
    function a(a, c) {
        a = a ? a : {},
        c = c ? c : {};
        var d = {}
          , e = ""
          , f = !0;
        for (var g in a)
            if (a.hasOwnProperty(g)) {
                var h = a[g];
                d[g] = Util.getHash(h) || Util.queryString(h)
            }
        b(d, c);
        for (var i in d)
            a.hasOwnProperty(i) && (f ? (e += i + "=" + d[i],
            f = !1) : e += "&" + i + "=" + d[i]);
        return e += "&from=" + B
    }
    function b(a, b) {
        var c = Object.keys(b);
        return c.forEach(function(c) {
            a[c] = b[c]
        }),
        a
    }
    function c(b, c) {
        c = c ? c : {};
        var d = t
          , e = a(o[b], c.params)
          , f = d + b + "/?" + e;
        return f += "&invoke_app=report"
    }
    function d(a) {
        console.log("invokeApp.js: downloadAppFromQQ");
        var b = v;
        a.isIOS === !0 && (b = !0),
        b ? (console.log("invokeApp.js: downloading ios", a.ios.app_url),
        window.location = q) : (console.log("invokeApp.js: downloading android", a.android.app_url),
        mqq.app.downloadApp ? (console.log("invokeApp.js: downloading android with QQ sdk"),
        mqq.app.downloadApp({
            appid: "1104830192",
            url: a.android.app_url,
            packageName: s,
            actionCode: 2,
            appName: "兴趣部落"
        }, function(b) {
            4 === b.state && mqq.app.downloadApp({
                appid: "1104830192",
                url: a.android.app_url,
                packageName: s,
                actionCode: 5,
                appName: "兴趣部落"
            }, function() {
                console.log("invokeApp.js: downloading android with QQ sdk")
            })
        })) : (console.log("invokeApp.js: downloading android with url"),
        window.location = a.android.app_url))
    }
    function e(a) {
        a = a ? a : {},
        console.log("invokeApp.js: downloadAppFromWeixin");
        var b = v;
        if (a.isIOS === !0 && (b = !0),
        b)
            console.log("invokeApp.js: downloading ios: ", a.ios.app_url),
            window.location = q;
        else if (console.log("invokeApp.js: downloading android: ", a.android.app_url),
        WeixinJSBridge.invoke) {
            var c;
            console.log("invokeApp.js: downloading android with Weixin sdk"),
            WeixinJSBridge.invoke("addDownloadTask", {
                task_name: "兴趣部落",
                task_url: a.android.app_url,
                file_md5: a.android.md5
            }, function(a) {
                c = a.download_id,
                console.log("invokeApp.js: ", a, a.download_id, a.err_msg)
            }),
            WeixinJSBridge.on("wxdownload:state_change", function(a) {
                console.log("wxdownload:state_change: ", a.state),
                "download_succ" === a.state && (console.log("wxdownload success!"),
                console.log("installing apk..."),
                WeixinJSBridge.invoke("installDownloadTask", {
                    download_id: c
                }, function(a) {
                    console.log("installDownloadTask done: ", a.err_msg)
                }))
            })
        } else
            console.log("downloading android with url"),
            window.location = a.android.app_url
    }
    function f(a) {
        v ? (Q.tdw({
            opername: "tribe_app",
            module: "web_view",
            action: "web_see",
            ver3: 3,
            ver4: a || "",
            ver5: C
        }),
        window.location = q) : (Q.tdw({
            opername: "tribe_app",
            module: "web_view",
            action: "web_see",
            ver3: 2,
            ver4: a || "",
            ver5: C
        }),
        Util.openUrl("http://buluo.qq.com/mobile/download_app.html?from=" + B, !0))
    }
    function g(a) {
        if (a = a ? a : {},
        y)
            return void e(a);
        if (x)
            d(a);
        else {
            var b, c = v;
            a.isIOS === !0 && (c = !0),
            c ? (console.log("downloading ios: ", a.ios.app_url),
            b = q) : (console.log("downloading android: ", a.android.app_url),
            b = a.android.app_url),
            window.location = b
        }
    }
    function h(a, b) {
        b = b ? b : {};
        var c;
        v ? c = r : u && (c = s),
        mqq.app.isAppInstalled(c, function(c) {
            console.log("app is installed: " + c),
            c ? (Q.tdw({
                opername: "tribe_app",
                module: "web_view",
                action: "web_see",
                ver3: 1,
                ver4: a,
                ver5: C
            }),
            b.success && b.success()) : b.fail ? b.fail() : f(a)
        })
    }
    function i(a, b) {
        function c() {
            window.WeixinJSBridge.invoke("getInstallState", {
                packageName: s,
                packageUrl: t
            }, function(c) {
                var d = c.err_msg.indexOf("get_install_state:yes") > -1;
                d ? (console.log("app is installed: " + d),
                Q.tdw({
                    opername: "tribe_app",
                    module: "web_view",
                    action: "web_see",
                    ver3: 1,
                    ver4: a,
                    ver5: C
                }),
                b.success && b.success()) : b.fail ? b.fail() : f(a)
            })
        }
        b = b ? b : {},
        window.WeixinJSBridge ? c() : document.addEventListener("WeixinJSBridgeReady", c)
    }
    function j(a, b) {
        b = b ? b : {};
        var c = 2500
          , d = 2550;
        setTimeout(function() {
            E = new Date,
            E - D > d ? (Q.tdw({
                opername: "tribe_app",
                module: "web_view",
                action: "web_see",
                ver3: 1,
                ver4: a,
                ver5: C
            }),
            b.success && b.success()) : b.fail ? b.fail() : f(a)
        }, c)
    }
    function k(a, b) {
        if (b = b ? b : {},
        v || u) {
            var d = c(a, b)
              , e = document.createElement("iframe");
            e.style.display = "none",
            document.body.appendChild(e),
            D = new Date,
            console.log("invokeApp.js: invoking", d),
            x && mqq.app && mqq.app.isAppInstalled ? (console.log("invokeApp.js: invokeAppFromQQ"),
            h(a, b)) : (console.log("invokeApp.js: invokeAppFromOtherBrowser"),
            j(a, b)),
            A ? window.location.href = d : e.src = d,
            setTimeout(function() {
                e.parentNode.removeChild(e)
            }, 5e3)
        }
    }
    function l() {
        var a = window.location.pathname
          , b = p[a]
          , c = "share_app" === Util.queryString("source") || "share_app" === Util.getHash("source");
        if (b && c) {
            console.log("InvokeApp.js: autoInvokeApp", a, b);
            var d = Util.queryString("pid");
            "join_tribe" === b && d && (b = "post_detail"),
            k(b, {
                fail: function() {
                    console.log("auto invoke app fail, do nothing.")
                },
                success: function() {
                    console.log("auto invoke app success, do nothing.")
                }
            })
        }
    }
    function m(a, b) {
        if (console.log("invokeApp.js: invoking QQ ", a),
        b = b ? b : {},
        v || u) {
            var c = document.createElement("iframe");
            c.style.display = "none",
            document.body.appendChild(c);
            var d = new Date
              , e = 2500
              , g = 2550;
            setTimeout(function() {
                var a = new Date;
                a - d > g ? (console.log("invokeApp.js: invoking QQ success"),
                b.success && b.success()) : (console.log("invokeApp.js: invoking QQ fail "),
                b.fail ? b.fail() : f("qq"))
            }, e),
            A ? window.location.href = a : c.src = a,
            setTimeout(function() {
                c.parentNode.removeChild(c)
            }, 5e3)
        }
    }
    function n(a, b) {
        b = b ? b : {},
        console.log("invokeApp.js: buildGuide ", a);
        var c, d;
        return c = b.renderContainer || "#js_invoke_app",
        d = new window.renderModel({
            comment: "invokeApp" + a,
            renderTmpl: window.TmplInline_app.invoke_app,
            renderContainer: c,
            complete: function() {
                $("body").addClass("show-app-banner"),
                $("body").addClass("show-app-banner--" + a)
            },
            events: function() {
                $(c).on("tap", function(c) {
                    c.preventDefault(),
                    $(c.target).hasClass("invoke-btn") && ($(c.target).addClass("active"),
                    setTimeout(function() {
                        $(c.target).removeClass("active")
                    }, 100),
                    k(a, b)),
                    $(c.target).hasClass("close-invoke-btn") && (console.log("close model", d.renderContainer),
                    $(d.renderContainer).hide(),
                    $("body").removeClass("show-app-banner"),
                    $("body").removeClass("show-app-banner--" + a))
                })
            }
        }),
        d.rock(),
        Q.tdw({
            opername: "tribe_app",
            module: "web_view",
            action: "exp_yellow",
            ver4: a,
            ver5: C
        }),
        d
    }
    var o, p, q, r, s, t, u, v, w, x, y, z, A, B, C, D, E;
    return q = "https://itunes.apple.com/cn/app/id1079313757",
    r = "tencenttribe",
    s = "com.tencent.tribe",
    t = "tencenttribe://",
    u = $.os.android,
    v = $.os.ios,
    w = navigator.userAgent.toLowerCase(),
    x = 0 !== Number(mqq.QQVersion),
    y = /micromessenger/.test(w),
    z = /mqqbrowser/.test(w),
    A = v && /safari/.test(w),
    B = C = x ? "qq" : y ? "wechat" : "browser",
    o = {
        gbar_home: {
            bid: "bid"
        },
        post_detail: {
            bid: "bid",
            pid: "pid"
        },
        user: {
            uid: "uin"
        },
        explore: {},
        join_tribe: {
            bid: "bid",
            invitor: "invitor",
            type: "bar_type"
        }
    },
    p = {
        "/mobile/barindex.html": "gbar_home",
        "/mobile/detail.html": "post_detail",
        "/mobile/personal.html": "user",
        "/mobile/private_barindex.html": "join_tribe"
    },
    console.log(i),
    $.os.ios && x ? setTimeout(l, 1e3) : setTimeout(l, 0),
    {
        buildGuide: n,
        invokeApp: k,
        invokeQQ: m,
        downloadApp: g
    }
});