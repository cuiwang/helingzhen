!function(a, b) {
    a.Util = b(a.$)
}(this, function() {
    function a(a, c, d) {
        var e = "http://buluo.qq.com/mobile/detail.html";
        d = Number(d),
        400 === d && (e = "http://buluo.qq.com/mobile/pho_detail.html"),
        401 === d && (e = "http://buluo.qq.com/mobile/article_detail.html");
        var f = [];
        for (var g in a)
            /^#/.test(g) ? f.push([g.replace(/^#/, ""), a[g]].join("=")) : f.push([g, a[g]].join("="));
        f = f.length ? "?" + f.join("&") : "";
        var h = [e, f].join("");
        c ? b(h) : b(h, !0)
    }
    
    function b(a, b, c, d, e) {
        a = a.replace(/^http:\/\/xiaoqu\.qq\.com\/mobile\//g, "http://buluo.qq.com/mobile/");
        var f, g, h, i = a.indexOf(H) > -1;
        if (g = L(a),
        g.query || (g.query = ""),
        g.fragment || (g.fragment = ""),
        i && (g.fragment && (h = g.fragment,
        g.fragment = ""),
        h && (g.query ? g.query += "&" + h : g.query = h),
        console.info("=======start redirect========="),
        f = (new Date).valueOf(),
        g.fragment += "time_redirect=" + f,
        a = M(g),
        mqq && mqq.compare("4.4") >= 0 && b && window.CGI_Preload && (localStorage.setItem("internal_preload", "1"),
        window.setTimeout(function() {
            window.CGI_Preload.preload(a)
        }, 0)),
        window.useOpen = !0,
        I && (g.query += "&_bid=" + I),
        G && (g.query += "&from=" + G),
        -1 !== a.indexOf("/mobile/personal.html") ? g.query += "&_wv=16777219" : -1 !== a.indexOf("/mobile/activity/") ? g.query += "&_wv=1025" : e ? g.query += "&_wv=16778243" : g.query += "&_wv=" + J,
        a = M(g)),
        d)
            return void window.location.replace(a);
        if (w)
            return void YybJsBridge.openNewWindow(a);
        var j = u();
        if (c = 1,
        b && window.mqq && mqq.iOS && mqq.compare("4.4") >= 0)
            i ? a += "&webview=1" : c = 0,
            mqq.ui.openUrl({
                url: a,
                target: 1,
                style: c
            });
        else if (b && window.mqq && j && j[1] >= "5.6")
            i ? a += "&webview=1" : c = 0,
            mqq.invoke("nav", "openLinkInNewWebView", {
                url: a,
                target: 1,
                style: c
            });
        else if (b && window.mqq && mqq.android) {
            if (!K(a))
                return;
            "search" === G ? (i ? a += "&webview=1" : c = 0,
            mqq.invoke("Troop", "openUrl", {
                url: a
            }, function(b) {
                b === mqq.ERROR_NO_SUCH_METHOD && mqq.ui.openUrl({
                    url: a,
                    target: 1,
                    style: c
                })
            })) : "pa" === G || "dongtai" === G ? (i ? a += "&webview=1" : (c = 0,
            g = L(a),
            g.query || (g.query = ""),
            g.query += "&_wv=" + J,
            a = M(g)),
            -1 !== a.indexOf("/mobile/activity/") && (c = 0),
            mqq.ui.openUrl({
                url: a,
                target: 1,
                style: c
            })) : (i ? a += "&webview=1" : c = 0,
            mqq.ui.openUrl({
                url: a,
                target: 1,
                style: c
            }))
        } else {
            -1 !== a.indexOf(window.location.pathname) && (a = a.replace(/#/, "&t=" + +new Date + "#"));
            try {
                var k = document.createElement("a");
                k.href = a,
                k.click()
            } catch (l) {
                window.location.href = a
            }
        }
    }
    
    function c(a, b, c) {
        var d = L(a)
          , e = mqq.mapQuery(d.query || "");
        if (e[b] = c,
        d.query = mqq.toQuery(e),
        d.fragment) {
            var f = mqq.mapQuery(d.fragment);
            delete f[b],
            d.fragment = mqq.toQuery(f)
        }
        return M(d)
    }
    
    function d(a, b) {
        var c = L(a)
          , d = mqq.mapQuery(c.query);
        return delete d[b],
        delete d[""],
        c.query = mqq.toQuery(d),
        M(c)
    }
    
    function e(a, b) {
        a = a.replace(/^http:\/\/xiaoqu\.qq\.com\/mobile\//g, "http://buluo.qq.com/mobile/");
        var d = L(a)
          , e = mqq.mapQuery(d.query)
          , f = Number(e && e._wv) || 0;
        a = encodeURIComponent(a);
        var g = {
            type: "GET",
            url: "/cgi-bin/bar/extra/gen_short_url",
            param: {
                urls: JSON.stringify([a])
            },
            succ: function(a) {
                var d;
                a.result.ls[0] && a.result.ls[0].url_code ? (d = c(a.result.ls[0].url_code, "_wv", 1027 | f),
                b && b(d)) : b && b(d)
            },
            err: function() {
                b && b(a)
            }
        };
        DB.cgiHttp(g)
    }
    
    function f(a, b) {
        a.share_url && (a.share_url = a.share_url.replace(/^http:\/\/xiaoqu\.qq\.com\/mobile\//g, "http://buluo.qq.com/mobile/")),
        a.page_url && (a.page_url = a.page_url.replace(/^http:\/\/xiaoqu\.qq\.com\/mobile\//g, "http://buluo.qq.com/mobile/"));
        var c = $.cookie("vkey")
          , d = {
            site: "兴趣部落",
            summary: a.desc,
            title: a.title,
            appid: 101051061,
            imageUrl: a.image_url,
            targetUrl: a.share_url,
            page_url: a.page_url,
            nobar: 1,
            clientid: 1001
        };
        e(d.targetUrl, function(a) {
            d.targetUrl = a,
            c && (d.sid = c),
            a = "http://openmobile.qq.com/api/check?page=shareindex.html&style=9&status_os=0&sdkp=0& pt_no_onekey=1";
            for (var e in d)
                d.hasOwnProperty(e) && (a += "&" + e + "=" + encodeURIComponent(d[e]));
            b(a)
        })
    }
    
    function g(a) {
        return a.indexOf("ugc.qpic.cn") > -1 && /\/$/.test(a) && (a += "200"),
        a
    }
    
    function h(a, b, h) {
        function i() {
            DB.cgiHttp({
                url: "/cgi-bin/bar/user/fbar",
                ssoCmd: "follow_bar",
                type: "POST",
                param: {
                    bid: l.barId,
                    op: 2
                },
                succ: function(a) {
                    a && 0 === a.retcode ? (Tip.show("取消关注成功", {
                        type: "ok"
                    }),
                    l.onCancelFocusSuccess && l.onCancelFocusSuccess()) : Tip.show("取消关注失败，请稍后重试", {
                        type: "warning"
                    })
                },
                err: function() {
                    Tip.show("取消关注失败，请稍后重试", {
                        type: "warning"
                    })
                }
            })
        }
        
        function j(a) {
            var b = function(a) {
                0 === a.retCode && (l.succHandler && l.succHandler(l.share_type),
                Q.tdw({
                    opername: "tribe_cgi",
                    module: "post",
                    action: "share",
                    ver1: Util.getHash("bid") || Util.queryString("bid"),
                    obj1: Util.getHash("pid") || Util.queryString("pid")
                }),
                Tip.show("已分享"))
            };
            a ? mqq.invoke("ui", "shareMessage", l, b) : mqq.ui.shareMessage(l, b)
        }
        
        function k() {
            if (a.imageInfo && a.imageInfo.pic && a.imageInfo.pic.w >= 100 && a.imageInfo.pic.h >= 100)
                j();
            else {
                var b = new Image;
                b.src = l.image_url;
                var c = "http://p.qpic.cn/qqconadmin/0/795b1405de9e46fd85fdcab7c56b4909/0";
                b.onload = b.onerror = function(b) {
                    b.target.width >= 100 && b.target.height >= 100 ? j() : window.location.pathname.indexOf("barindex.html") > -1 ? (l.image_url = c,
                    j()) : DB.cgiHttp({
                        type: "GET",
                        url: "/cgi-bin/bar/get_bar_logo",
                        param: {
                            bid: a.imageInfo.bid
                        },
                        succ: function(a) {
                            var b = 0 === a.retcode ? a.result.pic : c;
                            l.image_url = b,
                            j()
                        },
                        err: function() {
                            l.image_url = c,
                            j()
                        }
                    })
                }
            }
        }
        
        a.content = filterImgTag(a.content).replace(/<br>/g, "").replace(/\&nbsp;/g, " ");
        var l = {
            title: String(a.title).length > 16 ? String(a.title).substring(0, 16) + "..." : String(a.title),
            desc: String(a.content || a.title).substring(0, 50),
            share_url: a.shareUrl,
            image_url: g(a.imageUrl),
            back: a.noback ? !1 : !0,
            oaUin: "472839098",
            sourceName: "兴趣部落",
            puin: "472839098",
            page_url: a.pageUrl,
            barId: a.barId,
            barName: a.barName,
            succHandler: a.succHandler,
            localSaveCallback: a.localSaveCallback,
            showCancelFocusBtn: a.showCancelFocusBtn,
            onCancelFocusSuccess: a.onCancelFocusSuccess
        };
        l.share_url = d(l.share_url, "sid"),
        l.page_url = d(l.page_url, "sid"),
        l.page_url = d(l.page_url, "ds"),
        l.share_url = d(l.share_url, "ds"),
        l.page_url = d(l.page_url, "time_redirect"),
        l.share_url = d(l.share_url, "time_redirect");
        var m;
        if (m = /spring_rank/.test(window.location.href) ? ["share_rankqq", "share_rankqzone", "share_rankwechat", "share_rankcircle", "share_rankweibo", "share_ranklink"] : ["share_qq", "share_qzone", "share_wechat", "share_circle", "share_weibo", "share_link"],
        mqq.compare("4.7.2") > -1) {
            var n;
            RichShare.show(function(a) {
                l.share_url = c(l.share_url, "from", m[a]),
                b(0, a, l),
                l.share_type = a,
                a > -1 && 4 > a && ((2 === a || 3 === a) && (l.share_url += "#wechat_redirect"),
                h ? j() : e(l.share_url, function(b) {
                    l.share_url = b,
                    1 === a ? k() : j()
                })),
                4 === a && e(l.share_url, function(a) {
                    var b;
                    b = l.barName ? "#兴趣部落#" + l.barName + "部落-" + l.desc : "#兴趣部落#【" + l.title + "】" + l.desc,
                    a = encodeURIComponent(l.share_url);
                    var c = encodeURIComponent(b)
                      , d = encodeURIComponent(l.image_url)
                      , e = "http://v.t.sina.com.cn/share/share.php?title=" + c + "&url=" + a + "&pic=" + d;
                    mqq.ui.openUrl({
                        url: e,
                        target: 1,
                        style: 1
                    })
                }),
                5 === a && mqq.data.setClipboard({
                    text: l.share_url
                }, function(a) {
                    a && Tip.show("已复制到剪切板")
                }),
                7 === a && (new RegExp("barindex").test(location.pathname) && mqq.support("mqq.ui.openUrl") ? (F("from", "desktop"),
                n = "http://buluo.qq.com/mobile/shortcut.html?bid=" + (C("bid") || E("bid")) + "&url=" + encodeURIComponent(window.location.href) + "&name=" + encodeURIComponent(l.barName) + "&img=" + encodeURIComponent(l.image_url) + "&uin=" + Login.getUin() + "#shortcut=mqq",
                mqq.ui.openUrl({
                    url: n,
                    target: 2
                })) : Tip.show("抱歉，暂不支持此功能")),
                8 === a && (n = "http://xiaoqu.qq.com/mobile/bar_qrcode.html?bid=" + (C("bid") || E("bid")),
                mqq.ui.openUrl({
                    url: n,
                    target: 1,
                    style: 1
                })),
                9 === a && i()
            }, l.showCancelFocusBtn)
        } else
            mqq.compare("4.7.0") > -1 ? (b(0, 0),
            h ? j() : e(l.share_url, function(a) {
                l.share_url = a,
                mqq.ui.shareRichMessage(l, function() {})
            })) : navigator.userAgent.match(/\bMicroMessenger\/[\d\.]+/) ? RichShare.show() : -1 !== navigator.userAgent.indexOf("QQHD") ? j(!0) : (b(1, 0),
            f(l, function(a) {
                e(a, function(b) {
                    a = b,
                    Util.openUrl(a)
                })
            }))
    }
    
    function i(a) {
        a = a || "";
        var b = a.match(/<br>/g) || []
          , c = b.length <= 3 ? "" : "large";
        return c
    }
    
    function j(a, b, c) {
        var d = window.requestAnimationFrame || window.webkitRequestAnimationFrame || function(a) {
            setTimeout(a, 0)
        }
        ;
        $.os.ios ? 6500 > a ? (c.css({
            transition: "-webkit-transform 0.2s linear",
            "-webkit-transform": "translateY(" + -1 * a + "px)"
        }),
        b.scrollTop(0),
        d(function() {
            c.css({
                "-webkit-transform": "translateY(0)"
            })
        }, 0)) : (b.scrollTop(0),
        b.css("display", "none").height(),
        b.css("display", "block")) : (b = b.scrollTop() ? b : $(window),
        b.scrollTop(0),
        b[0] !== window && (b.css("display", "none").height(),
        b.css("display", "block")))
    }
    
    function k() {
        return !1
    }
    
    function l(a) {
        return a = parseInt(a),
        10037 === a || 23308 === a
    }
    
    function m() {
        var a = +new Date(2015,1,17,18,0,0)
          , b = new Date
          , c = +new Date(b.getTime() + 60 * b.getTimezoneOffset() * 1e3 + 288e5);
        return a > c
    }
    
    function n() {}
    
    function o(a) {
        return a.path = a.path || "buluo",
        a.host = a.host || "",
        a.callid = a.callid || +new Date % 1024,
        a
    }
    
    function p(a, b) {
        if ("string" == typeof b)
            return a.hasOwnProperty(b) && void 0 !== a[b];
        if ("[object Array]" === Object.prototype.toString.call(b))
            return b.forEach(function(b) {
                return Object.hasOwnProperty(b) && void 0 !== a[b] ? void 0 : !1
            }),
            !0;
        throw Error("objectHasKeys args err")
    }
    
    function q(a) {
        return y ? (a = o(a),
        p(a, ["key", "data"]) ? (a.data = JSON.stringify(a.data),
        void A(a, function(b) {
            0 === b.ret && b.response ? (a.success || n)(b.data) : (a.error || n)(b.data)
        })) : void 0) : void 0
    }
    
    function r(a) {
        return y ? (a = o(a),
        p(a, ["key"]) ? void z(a, function(b) {
            0 === b.ret && b.response ? (a.success || n)(b.data) : (a.error || n)(b.data)
        }) : void 0) : void 0
    }
    
    function s(a) {
        return y ? (a = o(a),
        p(a, ["key"]) ? void B(a, function(b) {
            0 === b.ret && b.response ? (a.success || n)(b.data) : (a.error || n)(b.data)
        }) : void 0) : void 0
    }
    
    function t(a, b) {
        return b ? a.replace(/^(http:\/\/i\.gtimg\.cn\/qqlive\/.*?)(?:_\w)?\.(jpg|jpeg)$/g, "$1_" + b + ".$2") : a
    }
    
    function u() {
        return navigator.userAgent.match(/iPadQQ\/([\d\.]+)/i)
    }
    
    var v = /\bPA\b/.test(navigator.userAgent)
      , w = !1
      , x = navigator.userAgent.match(/\/qqdownloader\/(\d+)?/)
      , y = mqq && mqq.data && mqq.support("mqq.data.readH5Data") && mqq.support("mqq.data.writeH5Data");
    if (y)
        var z = mqq.data.readH5Data
          , A = mqq.data.writeH5Data
          , B = mqq.data.deleteH5Data;
    x && (w = !0);
    
    var C = function(a) {
        var b = window.location.search.match(new RegExp("(?:\\?|&)" + a + "=([^&]*)(&|$)"))
          , c = b ? decodeURIComponent(b[1]) : "";
        return c
    }
      , D = function(a, b) {
        mqq.support("mqq.ui.showDialog") ? mqq.ui.showDialog({
            title: "重要声明",
            text: "本部落严禁发布反动，色情，广告，诈骗等违法信息，一经发现，一律删帖，并将发布人永久拉黑！",
            needOkBtn: !0,
            needCancelBtn: !1
        }, function(c) {
            0 === c.button && (localStorage.setItem("pho_alert" + b, "1"),
            a && a())
        }) : a && a()
    }
      , E = function(a) {
        var b = window.location.hash.match(new RegExp("(?:#|&)" + a + "=([^&]*)(&|$)"))
          , c = b ? decodeURIComponent(b[1]) : "";
        return c || C(a)
    }
      , F = function(a, b) {
        var c = E(a);
        c ? window.location.hash = window.location.hash.replace(new RegExp("(?:#|&)" + a + "=([^&]*)(&|$)"), function(a, c) {
            return a.replace("=" + c, "=" + b)
        }) : window.location.hash += "&" + [a, b].join("=")
    }
      , G = v ? "pa" : encodeURIComponent(E("from") || C("from"))
      , H = "http://buluo.qq.com/mobile/"
      , I = C("_bid")
      , J = 1027
      , K = function() {
        var a, b = 1e3;
        return function() {
            var c = +new Date;
            return a && b > c - a ? (a = c,
            !1) : (a = c,
            !0)
        }
    }()
      , L = function(a) {
        var b = null ;
        if (null  !== (b = L.RE.exec(a))) {
            for (var c = {}, d = 0, e = L.SPEC.length; e > d; d++) {
                var f = L.SPEC[d];
                c[f] = b[d + 1]
            }
            b = c,
            c = null 
        }
        return b
    }
      , M = function(a) {
        for (var b = "", c = {}, d = {}, e = 0, f = L.SPEC.length; f > e; e++) {
            var g = L.SPEC[e];
            if (a[g]) {
                switch (g) {
                case "scheme":
                    d[g] = "://";
                    break;
                case "pass":
                    c[g] = ":";
                    break;
                case "user":
                    c.host = "@";
                    break;
                case "port":
                    c[g] = ":";
                    break;
                case "query":
                    c[g] = "?";
                    break;
                case "fragment":
                    c[g] = "#"
                }
                g in c && (b += c[g]),
                g in a && (b += a[g]),
                g in d && (b += d[g])
            }
        }
        return c = null ,
        d = null ,
        a = null ,
        b
    }
      , N = function(a) {
        if (a && 0 === a.length)
            return [];
        for (var b = [], c = 0, d = a.length; d > c; c++) {
            var e = a[c];
            -1 === b.indexOf(e) && b.push(e)
        }
        return b
    }
      , O = function(a, b) {
        if (a && 0 === a.length)
            return [];
        for (var c = [], d = [], e = 0, f = a.length; f > e; e++) {
            var g = a[e];
            g[b] && -1 === c.indexOf(g[b]) && (c.push(g[b]),
            d.push(g))
        }
        return d
    }
    ;
    return L.SPEC = ["scheme", "user", "pass", "host", "port", "path", "query", "fragment"],
    L.RE = /^([^:]+):\/\/(?:([^:@]+):?([^@]*)@)?(?:([^/?#:]+):?(\d*))([^?#]*)(?:\?([^#]+))?(?:#(.+))?$/,
    {
        queryString: C,
        getHash: E,
        setHash: F,
        openUrl: b,
        openDetail: a,
        shareMessage: h,
        unique: N,
        uniqueKey: O,
        showStatement: D,
        getTextType: i,
        setExterParam: c,
        removeExterParam: d,
        scrollElTop: j,
        isFestival: k,
        isQQbar: l,
        isBeforeFestival: m,
        qqLiveImageResizer: t,
        h5Data: {
            h5DataSupport: y,
            setItem: q,
            getItem: r,
            clear: s
        },
        getIPadVersion: u
    }
});