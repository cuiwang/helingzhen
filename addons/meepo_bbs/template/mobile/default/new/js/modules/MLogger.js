!function(t, e) {
                t.MLogger = e()
            } (this,
            function() {
                var t = {
                    useLogLine: !1,
                    logExtJs: "js/log.min.js",
                    logExtCss: "css/log.min.css"
                },
                e = ["DEBUG", "LOG", "INFO", "REPORT", "ERROR"];
                window.Logger = {
                    data: {}
                },
                window.Logger.data.IS_CONSOLE_OPEN = !1,
                window.Logger.data.CONSOLE_LOG_ARR = [];
                var n = function(t) {
                    window.Logger.data.CONSOLE_LOG_ARR.push(t)
                },
                o = {
                    _record: function(t) {
                        for (var e = "",
                        o = {
                            level: t,
                            time: Date.now(),
                            location: e
                        },
                        r = [], i = 1; i < arguments.length; i++) try {
                            r.push(arguments[i])
                        } catch(a) {
                            r.push(arguments[i])
                        }
                        o.content = r,
                        c(r, t),
                        "REPORT" !== t && (window.Logger.data.IS_CONSOLE_OPEN ? window.Logger.Console.renderLog(o, !0) : n(o))
                    },
                    log: function() {
                        Array.prototype.unshift.call(arguments, e[1]),
                        o._record.apply(o, arguments)
                    },
                    info: function() {
                        Array.prototype.unshift.call(arguments, e[2]),
                        o._record.apply(o, arguments)
                    },
                    report: function() {
                        Array.prototype.unshift.call(arguments, e[3]),
                        o._record.apply(o, arguments)
                    },
                    error: function() {
                        Array.prototype.unshift.call(arguments, e[4]),
                        o._record.apply(o, arguments)
                    }
                },
                r = function() {
                    window.Logger.data.reportCount = 0,
                    window.Logger.data.reportArr = [],
                    window.Logger.data.cgiArr = [];
                    var t = window.XMLHttpRequest.prototype.open;
                    window.XMLHttpRequest.prototype.open = function() {
                        var e = arguments,
                        n = this.onreadystatechange ||
                        function() {};
                        return this.start = Date.now(),
                        this.onreadystatechange = function(t) {
                            if (4 == t.target.readyState) {
                                var o = {
                                    url: e[1],
                                    time: Date.now() - t.target.start,
                                    text: t.target.responseText,
                                    status: t.target.status,
                                    headers: t.target.getAllResponseHeaders(),
                                    params: e[5] || e[1]
                                };
                                if (delete t.target.start, window.Logger.data.IS_CONSOLE_OPEN) {
                                    var r = window.Logger.data.cgiArr.length;
                                    window.Logger.data.cgiArr.push(o),
                                    window.Logger.Console.renderCgi(r)
                                } else window.Logger.data.cgiArr.push(o)
                            }
                            return n.apply(this, arguments)
                        },
                        t.apply(this, arguments)
                    }
                },
                i = function(t) {
                    if ("object" != typeof t) return t;
                    for (var e, n, o = 1,
                    r = arguments.length; r > o; o++) {
                        e = arguments[o];
                        for (n in e) e.hasOwnProperty(n) && !
                        function(t, n) {
                            if ("function" == typeof t[n]) {
                                var o = t[n];
                                t[n] = function() {
                                    e[n].apply(e, arguments);
                                    o.apply(t, arguments);
                                }
                            } else t[n] = e[n]
                        } (t, n)
                    }
                    return t
                },
                a = !0,
                d = !1,
                g = function() {
                    if (a) {
                        a = !1;
                        var e = "<div id='log-preload' class='log-preload slideLeft'><p>启用log?</p><div><div class='btn' id='yesLog'>yes</div><div class='btn' id='noLog'>no</div></div></div>",
                        n = document.createElement("div");
                        n.innerHTML = e,
                        document.body.appendChild(n),
                        document.getElementById("yesLog").addEventListener("touchstart",
                        function() {
                            document.getElementById("log-preload").innerHTML = "<div class='spinner'></div>";
                            var e = document.createElement("script");
                            e.src = t.logExtJs,
                            window.Logger.data.LOG_CSS_URL = t.logExtCss,
                            document.body.appendChild(e),
                            e.onload = function() {
                                d = !0,
                                window.Logger.Console.createLog()
                            }
                        }),
                        document.getElementById("noLog").addEventListener("touchstart",
                        function() {
                            document.getElementById("log-preload").style.display = "none"
                        })
                    } else d ? (d = !0, window.Logger.Console.createLog()) : document.getElementById("log-preload").style.display = "block"
                },
                s = function(t) {
                    var e, n, o = {
                        x: 0,
                        y: document.documentElement.clientHeight
                    },
                    r = {
                        x: document.documentElement.clientWidth / 2,
                        y: 0
                    },
                    i = {
                        x: document.documentElement.clientWidth,
                        y: document.documentElement.clientHeight
                    },
                    a = 50;
                    document.addEventListener("touchmove",
                    function(o) {
                        e && Math.abs(o.targetTouches[0].clientX - r.x) < a && Math.abs(o.targetTouches[0].clientY - r.y) < a && (n = !0),
                        n && Math.abs(o.targetTouches[0].clientX - i.x) < a && Math.abs(o.targetTouches[0].clientY - i.y) < a && (t(), e = n = !1)
                    }),
                    document.addEventListener("touchend",
                    function() {
                        e = n = !1
                    }),
                    document.addEventListener("touchstart",
                    function(t) {
                        e = n = !1,
                        Math.abs(t.targetTouches[0].clientX - o.x) < a && Math.abs(t.targetTouches[0].clientY - o.y) < a && (e = !0, t.preventDefault())
                    });
                    var d = setInterval(function() {
                        if (window.mqq && "0" != mqq.QQVersion) {
                            clearInterval(d);
                            var e = 0;
                            mqq.addEventListener("qbrowserTitleBarClick",
                            function() {
                                e++,
                                1 === e && setTimeout(function() {
                                    e = 0
                                },
                                3e3),
                                5 === e && (e = 0, t())
                            })
                        }
                    },
                    200)
                };
                window.Logger.Ajax = function() {
                    function t(t, n) {
                        function o() {}
                        var r = n.async !== !1,
                        i = n.method || "GET",
                        a = n.data || null,
                        d = n.success || o,
                        g = n.failure || o;
                        i = i.toUpperCase(),
                        "GET" == i && a && (t += ( - 1 == t.indexOf("?") ? "?": "&") + a, a = null);
                        var s = window.XMLHttpRequest ? new XMLHttpRequest: new ActiveXObject("Microsoft.XMLHTTP");
                        return s.onreadystatechange = function() {
                            e(s, d, g)
                        },
                        s.open(i, t, r),
                        "POST" == i && s.setRequestHeader("Content-type", "application/x-www-form-urlencoded;"),
                        s.send(a),
                        s
                    }
                    function e(t, e, n) {
                        if (4 == t.readyState) {
                            var o = t.status;
                            o >= 200 && 300 > o ? e(t) : n(t)
                        }
                    }
                    return {
                        request: t
                    }
                } (),
                window.Logger.checkWrightList = function() {
                    var t = window.localStorage.getItem("hasPid")?window.localStorage.getItem("hasPid"):{},
                    e = !1;
                    if (t) {
                        var n = JSON.parse(t),
                        o = Date.now() - n.ptime;
                        o > 864e5 ? e = !0 : (window.Logger.data.isReport = n.pid ? !0 : !1, window.Logger.data.reportId = n.pid || "", window.Logger.data.reportKey = n.keySet || "")
                    } else e = !0;
                    if (e) try {
                        var r = window.location.host;
                        window.Logger.Ajax.request("http://" + r + "/cgi-bin/feedback/re/check", {
                            success: function(t) {
                                try {
                                    var e = JSON.parse(t.responseText);
                                    0 == e.ret ? (window.Logger.data.isReport = !0, window.Logger.data.reportId = e.results.id, window.Logger.data.reportKey = e.results.data, window.localStorage.setItem("hasPid", JSON.stringify({
                                        pid: window.Logger.data.reportId,
                                        keySet: window.Logger.data.reportKey,
                                        ptime: Date.now()
                                    }))) : window.localStorage.setItem("hasPid", JSON.stringify({
                                        pid: "",
                                        keySet: null,
                                        ptime: Date.now()
                                    }))
                                } catch(n) {}
                            },
                            failure: function() {
                                window.localStorage.setItem("hasPid", JSON.stringify({
                                    pid: "",
                                    keySet: null,
                                    ptime: Date.now()
                                }))
                            }
                        })
                    } catch(i) {}
                },
                window.Logger.checkWrightList();
                
                var c = function(t, e) {
                    if (window.Logger.data.isReport && "REPORT" == e) {
                        var n = window.Logger.data.reportId;
                        setTimeout(function() {
                            var e = "info" == t[0].type ? "0": "1",
                            o = window.Logger.data.reportKey[t[0].category] || "0",
                            r = "";
                            try {
                                var i = window.Config || {},
                                a = i.offlineVersion || 0,
                                d = i.isOffline;
                                t[0].content = "[" + d + "][" + a + "]" + t[0].content,
                                r = t[0].content.replace("#", "").trim()
                            } catch(g) {} (new Image).src = "http://buluo.qq.com/cgi-bin/feedback/re/report?id=" + n + "&t=" + Date.now() + "&l=" + e + "&b=" + o + "&c=" + r
                        },
                        400)
                    }
                },
                u = function(e) {
                    t.useLogLine = e.useLogLine || !1,
                    t.logExtJs = e.logExtJs || _url("{MODULE_URL}template/mobile/default/new/js/log.min.f8676fff.js"),
                    t.logExtCss = e.logExtCss || _url("{MODULE_URL}template/mobile/default/new/css/log.min.8102f5eb.css"),
                    t.triggerLog = e.triggerLog || s,
                    t.beforeInit = e.beforeInit ||
                    function() {},
                    t.myEvent = e.myEvent ||
                    function() {},
                    t.widgetList = e.widgetList || ["location", "env", "cgi", "resource", "dom", "localStorage"],
                    t.myWidget = e.myWidget || [{
                        getHtml: function() {}
                    }],
                    t.myButton = e.myButton || [{
                        getHtml: function() {}
                    }],
                    t.beforeInit(),
                    i(window.console, o),
                    r(),
                    t.triggerLog(function() {
                        g()
                    }),
                    window.Logger.data.LoggerOption = t
                };
                return u({}),
                {
                    init: u
                }
            });