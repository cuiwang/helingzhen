!function(a, b) {
    a.DB = b()
}(this, function() {
    function extend(a, b, c) {
        if ("object" != typeof a)
            return a;
        b = b || {};
        for (var d in b)
            if (b.hasOwnProperty(d)) {
                var e, f;
                c && "function" == typeof (e = a[d]) && "function" == typeof (f = b[d]) ? a[d] = function() {
                    f.apply(a, arguments) || e.apply(a, arguments)
                }
                 : a[d] = b[d]
            }
    }
    function encryptSkey(a) {
        if (!a)
            return "";
        for (var b = 5381, c = 0, d = a.length; d > c; ++c)
            b += (b << 5) + a.charAt(c).charCodeAt();
        return 2147483647 & b
    }
    function parseData(data) {
        if ("object" != typeof data) {
            if (!data)
                return {
                    ec: 997,
                    text: data,
                    msg: "data is null"
                };
            if ("{" !== data.charAt(0) || "}" !== data.charAt(data.length - 1))
                return {
                    ec: 998,
                    text: data,
                    msg: "data is hijack"
                };
            try {
                return window.JSON && JSON.parse ? JSON.parse(data) : eval("(" + data + ")")
            } catch (e) {
                return {
                    ec: 999,
                    text: data,
                    msg: "data is not json"
                }
            }
        }
        return data
    }
    var requestQueue = {}
      , retryCallBackArr = []
      , hasShowDialog = !1
      , errorCgiCodeArr = [100003, 100012, 2, 4, 21]
      , ssoEnable = mqq && mqq.compare("5.2") > -1
      , isLoginError = !1
      , ajax = function() {
        var a = function() {
            var a, b, c, d = 0, e = 0;
            return function(f) {
                f ? (d--,
                0 === d && (b = (new Date).valueOf(),
                b - a > e && (e = b - a),
                c && window.clearTimeout(c),
                c = window.setTimeout(function(a) {
                    return function() {
                        a === e && (console.info("Model CGI 请求耗时:"),
                        console.info(e))
                    }
                }(e), 300))) : (0 === d && (a = (new Date).valueOf()),
                d++)
            }
        }()
          , b = function(c, d, e, f, g, h) {
            var i, j, k, l, m, n = +new Date, o = window.CGI_Preload;
            if (m = mqq && mqq.compare("5.8") > -1 ? "http://buluo.qq.com" : "http://xiaoqu.qq.com",
            c.indexOf("http") < 0 && (c = window.location.origin ? window.location.origin + c : window.location.host ? "http://" + window.location.host + c : m + c),
            k = $.cookie("uin"),
            k && (k = k.replace("o", "")),
            j = {
                url: c,
                uin: k
            },
            h || a(0),
            o && e && (l = o.getData(c, f, g.param)))
                return l.loading ? void window.setTimeout(function() {
                    b(c, d, e, f, g, !0)
                }, 50) : (e(l),
                void a(1));
            var p = {
                type: f,
                url: c,
                data: d,
                localCache: g.localCache,
                cacheKey: g.cacheKey,
                cacheTimeout: g.cacheTimeout,
                defaultData: g.defaultData,
                cacheVersion: g.cacheVersion,
                update: g.update,
                impotant: g.impotant,
                notThisTime: g.notThisTime,
                timeout: 2e4,
                success: function(a) {
                    i = +new Date,
                    e && e(a)
                },
                error: function(a, b) {
                    i = +new Date,
                    a.status || "timeout" === b || ((new Image).src = "http://cgi.pub.qq.com/report/report_vm?monitors=[626384]&t=" + Date.now()),
                    "timeout" === b && ((new Image).src = "http://cgi.pub.qq.com/report/report_vm?monitors=[2050084]&t=" + Date.now()),
                    e && e({
                        ec: a.status || -1
                    }),
                    a.status && 200 !== a.status && (j.type = 2,
                    j.rate = 1,
                    j.time = Date.now() - n,
                    j.code = a.status,
                    window.reportCgi.report(j))
                },
                complete: function(b) {
                    var c = null ;
                    try {
                        var d = JSON.parse(b.responseText);
                        c = d.retcode,
                        "undefined" == typeof c && (c = d.retcode = d.ec),
                        200 === b.status && (j.time = Date.now() - n,
                        j.code = c,
                        0 === c ? (j.type = 1,
                        j.rate = 20) : -1 === errorCgiCodeArr.indexOf(c) ? (j.type = 3,
                        j.rate = 1) : (j.type = 2,
                        j.rate = 1),
                        window.reportCgi.report(j))
                    } catch (e) {}
                    a(1)
                }
            };
            0 !== c.indexOf(location.origin) && (p.xhrFields = {
                withCredentials: !0
            }),
            $.ajax(p)
        }
        ;
        return b
    }();
    return {
        status: {
            bkn: ""
        },
        "data-cache": {},
        isLoginError: function() {
            return isLoginError
        },
        encryptSkey: encryptSkey,
        get: function(a, b, c, d) {
            var e = [];
            for (var f in b)
                b.hasOwnProperty(f) && e.push(f + "=" + b[f]);
            return e.push("r=" + Math.random()),
            a.indexOf("?") < 0 && (a += "?"),
            a += e.join("&"),
            ajax(a, null , c, "GET", d)
        },
        post: function(a, b, c, d) {
            var e = [];
            for (var f in b)
                b.hasOwnProperty(f) && e.push(f + "=" + b[f]);
            return e.push("r=" + Math.random()),
            ajax(a, e.join("&"), c, "POST", d)
        },
        sso: function(a, b, c, d) {
            var e, f, g, h = +new Date;
            f = "/sso/xiaoqu/web/" + d.ssoCmd,
            mqq.invoke("sso", "sendRequest", {
                cmd: d.ssoCmd,
                data: JSON.stringify(b),
                callback: mqq.callback(function(a) {
                    if (e = +new Date,
                    console.log("sso " + d.ssoCmd + " get data: ", a),
                    a.data) {
                        var b = {};
                        if ("object" == typeof a.data)
                            b = a.data;
                        else
                            try {
                                b = JSON.parse(a.data)
                            } catch (i) {
                                b.retcode = 1234
                            }
                        "undefined" == typeof b.retcode && (b.retcode = -2),
                        g = b.retcode,
                        c(b)
                    } else
                        g = isNaN(a) ? a.retcode || -42701 : a,
                        c({
                            ec: -2
                        });
                    setTimeout(function() {
                        Q.mmReport(f, g, e - h)
                    }, 0)
                })
            })
        },
        cgiHttp: function(a) {
            for (var b = window.urlNotLogin || [], c = 0; c < b.length; c++)
                if (b[c].indexOf(a.url) > -1) {
                    a.noError = 1;
                    break
                }
            if (($.cookie("skey") || !a.noError) && a && a.url) {
                var d = ""
                  , e = DB.wrapGroup(a.param)
                  , f = 0;
                if (d = a.url + "/" + encodeURIComponent(JSON.stringify(e)),
                requestQueue[d])
                    return void requestQueue[d].push(a);
                requestQueue[d] = [a];
                var g, h = this, i = {
                    url: a.url
                }, j = function(b) {
                    i.time = +new Date;
                    var c, k = requestQueue[d] || [];
                    try {
                        b && b.result && b.result.public_account_post && 301 === b.result.type ? (c = b.result.public_account_post,
                        b = JSON.stringify(b),
                        b = b.replace(/<br>/g, "\\n"),
                        b = b.replace(/</g, "&lt;").replace(/>/g, "&gt;"),
                        b = JSON.parse(b),
                        b.result.public_account_post = c) : b && b.result && 302 === b.result.type ? console.log("rich post") : (b = JSON.stringify(b),
                        b = b.replace(/<br>/g, "\\n"),
                        b = b.replace(/</g, "&lt;").replace(/>/g, "&gt;"),
                        b = JSON.parse(b))
                    } catch (l) {}
                    b = parseData(b);
                    var m;
                    m = b.retcode = "undefined" != typeof b.retcode ? b.retcode : "undefined" != typeof b.ec ? b.ec : b.ret,
                    a.url.indexOf("http://info.gamecenter.qq.com/") > -1 && (m = b.ecode,
                    b.data && b.data.key && b.data.key.retBody && -12e4 === b.data.key.retBody.result && (m = 1e5)),
                    0 !== m && (Badjs("cgiErrDes:the retcode is not 0,but " + m + "!cgiUrl:" + a.url, location.href, 0, 387377, 2, 464198),
                    console.error("cgiErrDes:the retcode is not 0,but " + m + "!cgiUrl:" + a.url),
                    console.report && console.report({
                        type: "error",
                        category: "",
                        content: "cgiErrDes:the retcode is not 0,but " + m + "!cgiUrl:" + a.url
                    }));
                    var n, o;
                    switch (1e5 !== m && delete requestQueue[d],
                    m) {
                    case 0:
                        n = !1,
                        k.forEach(function(a) {
                            a.succ && a.succ(b)
                        });
                        break;
                    case 1e5:
                        isLoginError = !0;
                        var p = (b || {}).host || "xiaoqu.qq.com";
                        if (a.noError)
                            n = !1,
                            delete requestQueue[d];
                        else if (a.noNeedLogin)
                            n = !0,
                            delete requestQueue[d];
                        else if ("undefined" != typeof Login) {
                            var q = function() {
                                e.bkn = h.status.bkn = h.encryptSkey($.cookie("skey")),
                                g(a.url, e, j, a)
                            }
                            ;
                            3 > f ? (Login.notLoginCallback(q, p),
                            f++) : mqq.ui && mqq.ui.showDialog && (hasShowDialog ? retryCallBackArr.push(q) : (mqq.ui.showDialog({
                                title: "提示",
                                text: "登录态失效，请点击重试",
                                needOkBtn: !0,
                                needCancelBtn: !0
                            }, function(a) {
                                0 === a.button ? Login.notLoginCallback(function() {
                                    for (var a = 0, b = retryCallBackArr.length; b > a; a++)
                                        retryCallBackArr[a]();
                                    retryCallBackArr = [],
                                    hasShowDialog = !1
                                }) : mqq.ui.popBack()
                            }),
                            retryCallBackArr.push(q),
                            hasShowDialog = !0))
                        } else
                            n = !0;
                        break;
                    case 100001:
                        n = !0;
                        break;
                    case 100003:
                        n = !0,
                        o = !0;
                        break;
                    case 100006:
                        if (n = !0,
                        o = !0,
                        -1 !== navigator.userAgent.indexOf("QQHD")) {
                            var r = encodeURIComponent("http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/login-logo.png");
                            window.location.href = "http://ui.ptlogin2.qq.com/cgi-bin/login?pt_no_onekey=1&style=9&appid=1006102&daid=371&s_url=" + encodeURIComponent(window.location.href) + "&low_login=0&hln_css=" + r
                        }
                        break;
                    default:
                        n = !0,
                        o = !0
                    }
                    n && k.forEach(function(a) {
                        a.err && a.err(b)
                    })
                }
                ;
                if (g = h.get,
                console.log("ssoEnable:" + ssoEnable + ", ssoCmd:" + a.ssoCmd),
                ssoEnable && a.ssoCmd && "" !== a.ssoCmd && "undefined" !== a.ssoCmd)
                    g = h.sso;
                else {
                    if (a.ssoCmd && parseInt(mqq.QQVersion) > 0 && mqq.compare("5.2") < 0) {
                        var k = "抱歉！由于您的手机QQ版本过低，\n敬请升级后再使用";
                        return Alert.show("", k, {
                            cancel: "确认",
                            confirm: "立即升级",
                            confirmAtRight: !0,
                            callback: function() {
                                mqq.ui.openUrl({
                                    url: "http://im.qq.com/immobile/index.html",
                                    target: 1,
                                    style: 3
                                })
                            }
                        }),
                        void (j && j({
                            ec: -1
                        }))
                    }
                    a.type && "POST" !== a.type || (h.status.bkn || (h.status.bkn = h.encryptSkey($.cookie("skey"))),
                    e.bkn = h.status.bkn,
                    g = h.post)
                }
                var l = g(a.url, e, j, a);
                return l
            }
        },
        extend: function(a, b, c, d) {
            var e = Object.prototype.toString.call(a);
            if ("[object String]" === e) {
                c = c || this;
                var f = {};
                f[a] = b,
                extend(c, f, d)
            } else
                "[object Object]" === e && ((null  === b || void 0 === b) && arguments.length >= 4 ? (c = c || this,
                extend(c, a, d)) : (d = c,
                c = b || this,
                extend(c, a, d)))
        },
        wrapGroup: function(a) {
            return a || {}
        }
    }
})