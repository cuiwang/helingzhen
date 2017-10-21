!
function(a, b) {
    a.Config = b(a.$, a.Config)
} (this,
function() {
    var a = {
        globalOnError: !0,
        isOffline: !1,
        checkOpen: !1
    };
    return a.FACE_2_TEXT = ["微笑", "撇嘴", "色", "发呆", "得意", "流泪", "害羞", "闭嘴", "睡", "大哭", "尴尬", "发怒", "调皮", "呲牙", "惊讶", "难过", "酷", "冷汗", "抓狂", "吐", "偷笑", "可爱", "白眼", "傲慢", "饥饿", "困", "惊恐", "流汗", "憨笑", "大兵", "奋斗", "咒骂", "疑问", "(嘘[.]{3}|嘘)", "晕", "折磨", "衰", "骷髅", "敲打", "再见", "擦汗", "抠鼻", "鼓掌", "糗大了", "坏笑", "左哼哼", "右哼哼", "哈欠", "鄙视", "委屈", "快哭了", "阴险", "亲亲", "吓", "可怜", "菜刀", "西瓜", "啤酒", "篮球", "乒乓", "咖啡", "饭", "猪头", "玫瑰", "凋谢", "示爱", "爱心", "心碎", "蛋糕", "闪电", "炸弹", "刀", "足球", "瓢虫", "便便", "月亮", "太阳", "礼物", "拥抱", "强", "弱", "握手", "胜利", "抱拳", "勾引", "拳头", "差劲", "爱你", "NO", "OK", "爱情", "飞吻", "跳跳", "发抖", "怄火", "转圈", "磕头", "回头", "跳绳", "挥手", "激动", "街舞", "献吻", "左太极", "右太极", "双喜", "鞭炮", "灯笼", "发财", "K歌", "购物", "邮件", "帅", "喝彩", "祈祷", "爆筋", "棒棒糖", "喝奶", "下面", "香蕉", "飞机", "开车", "高铁左车头", "车厢", "高铁右车头", "多云", "下雨", "钞票", "熊猫", "灯泡", "风车", "闹钟", "打伞", "彩球", "钻戒", "沙发", "纸巾", "药", "手枪", "青蛙", "左车头", "右车头", "嘘", "嘘..."],
    a
}),
$.str = function() {
    function a(a) {
        var b = {};
        return a >= 0 && (b["&quot;"] = 34, b["&amp;"] = 38, b["&apos;"] = 39, b["&lt;"] = 60, b["&gt;"] = 62, b["&nbsp;"] = 160),
        a >= 1 && (b["&iexcl;"] = 161, b["&cent;"] = 162, b["&pound;"] = 163, b["&curren;"] = 164, b["&yen;"] = 165, b["&brvbar;"] = 166, b["&sect;"] = 167, b["&uml;"] = 168, b["&copy;"] = 169, b["&ordf;"] = 170, b["&laquo;"] = 171, b["&not;"] = 172, b["&shy;"] = 173, b["&reg;"] = 174, b["&macr;"] = 175, b["&deg;"] = 176, b["&plusmn;"] = 177, b["&sup2;"] = 178, b["&sup3;"] = 179, b["&acute;"] = 180, b["&micro;"] = 181, b["&para;"] = 182, b["&middot;"] = 183, b["&cedil;"] = 184, b["&sup1;"] = 185, b["&ordm;"] = 186, b["&raquo;"] = 187, b["&frac14;"] = 188, b["&frac12;"] = 189, b["&frac34;"] = 190, b["&iquest;"] = 191, b["&Agrave;"] = 192, b["&Aacute;"] = 193, b["&Acirc;"] = 194, b["&Atilde;"] = 195, b["&Auml;"] = 196, b["&Aring;"] = 197, b["&AElig;"] = 198, b["&Ccedil;"] = 199, b["&Egrave;"] = 200, b["&Eacute;"] = 201, b["&Ecirc;"] = 202, b["&Euml;"] = 203, b["&Igrave;"] = 204, b["&Iacute;"] = 205, b["&Icirc;"] = 206, b["&Iuml;"] = 207, b["&ETH;"] = 208, b["&Ntilde;"] = 209, b["&Ograve;"] = 210, b["&Oacute;"] = 211, b["&Ocirc;"] = 212, b["&Otilde;"] = 213, b["&Ouml;"] = 214, b["&times;"] = 215, b["&Oslash;"] = 216, b["&Ugrave;"] = 217, b["&Uacute;"] = 218, b["&Ucirc;"] = 219, b["&Uuml;"] = 220, b["&Yacute;"] = 221, b["&THORN;"] = 222, b["&szlig;"] = 223, b["&agrave;"] = 224, b["&aacute;"] = 225, b["&acirc;"] = 226, b["&atilde;"] = 227, b["&auml;"] = 228, b["&aring;"] = 229, b["&aelig;"] = 230, b["&ccedil;"] = 231, b["&egrave;"] = 232, b["&eacute;"] = 233, b["&ecirc;"] = 234, b["&euml;"] = 235, b["&igrave;"] = 236, b["&iacute;"] = 237, b["&icirc;"] = 238, b["&iuml;"] = 239, b["&eth;"] = 240, b["&ntilde;"] = 241, b["&ograve;"] = 242, b["&oacute;"] = 243, b["&ocirc;"] = 244, b["&otilde;"] = 245, b["&ouml;"] = 246, b["&divide;"] = 247, b["&oslash;"] = 248, b["&ugrave;"] = 249, b["&uacute;"] = 250, b["&ucirc;"] = 251, b["&uuml;"] = 252, b["&yacute;"] = 253, b["&thorn;"] = 254, b["&yuml;"] = 255),
        b
    }
    var b = {},
    c = {},
    d = {},
    e = {},
    f = function(c) {
        return b[c] || (b[c] = a(c)),
        b[c]
    },
    g = function(a) {
        if (!d[a]) {
            var b = f(a),
            c = {};
            for (var e in b) b.hasOwnProperty(e) && (c[String.fromCharCode(b[e])] = "&#" + b[e] + ";");
            c[" "] = "&#32;",
            d[a] = c
        }
        return d[a]
    },
    h = function(a) {
        return c[a] || (c[a] = new RegExp("(" + j(f(a)).join("|") + ")", "g")),
        c[a]
    },
    i = function(a) {
        return e[a] || (e[a] = new RegExp("[" + j(g(a)).join("") + "]", "g")),
        e[a]
    },
    j = function(a) {
        var b = [];
        for (var c in a) a.hasOwnProperty(c) && b.push(c);
        return b
    },
    k = {
        '"': "#34",
        "<": "#60",
        ">": "#62",
        "&": "#38",
        " ": "#160"
    };
    return k[String.fromCharCode(160)] = "#160",
    {
        decodeHtml: function(a, b) {
            b = isNaN(b) ? 0 : b,
            a += "";
            var c = h(b),
            d = f(b);
            return a.replace(c,
            function(a, b) {
                return "&#" + d[b] + ";"
            }).replace(/&#x([a-f\d]+);/g,
            function(a, b) {
                return "&#" + parseInt("0x" + b) + ";"
            }).replace(/&#(\d+);/g,
            function(a, b) {
                return String.fromCharCode( + b)
            })
        },
        encodeHtml: function(a, b) {
            b = isNaN(b) ? 0 : b,
            a += "";
            var c = i(b),
            d = g(b);
            return a.replace(c,
            function(a) {
                return d[a]
            })
        }
    }
} (),
window.YybJsBridge = function(a) {
    function b() {
        var a = document.createElement("iframe");
        return d.push(a),
        a.style.cssText = "position:absolute;left:0;top:0;width:0;height:0;visibility:hidden;",
        a.frameBorder = "0",
        document.body.appendChild(a),
        a
    }
    function c() {
        var b = g;
        g = [],
        h = 0,
        a.getAppInstalledVersion(b,
        function(a) {
            var b;
            for (var c in a)(b = i._instances[c]) && ((!b.args.queryInstalledVersionCode || a[c] >= b.args.queryInstalledVersionCode) && b.state == i.STATE_READY && b._callback(i.STATE_INSTALLED), b.args.versionCode && b.args.versionCode > a[c] && (b.args.isUpdate = !0))
        })
    }
    a = a || {},
    a.SCENE_NONE = 0,
    a.SCENE_DOWNLOADER = 1,
    a.SCENE_DOWNLOADER_DETAIL = 2 | a.SCENE_DOWNLOADER,
    a.SCENE_DOWNLOADER_EXTERNAL = 4 | a.SCENE_DOWNLOADER,
    a.SCENE_DOWNLOADER_SDK = 8 | a.SCENE_DOWNLOADER,
    a.SCENE_MOBILEQ = 16,
    a.SCENE_WECHAT = 32,
    a.SCENE = a.SCENE_NONE,
    a._greaterThanOrEqual = function(a, b) {
        a = String(a).split("."),
        b = String(b).split(".");
        try {
            for (var c = 0,
            d = Math.max(a.length, b.length); d > c; c++) {
                var e = isFinite(a[c]) && Number(a[c]) || 0,
                f = isFinite(b[c]) && Number(b[c]) || 0;
                if (f > e) return ! 1;
                if (e > f) return ! 0
            }
        } catch(g) {
            return ! 1
        }
        return ! 0
    };
    var d = [],
    e = {};
    a._callWithScheme = function(a, c) {
        console.log("YybJsBridge._callWithScheme: ", a);
        for (var f, g = 0; (f = d[g]) && f._busy; g++); (!f || f._busy) && (f = b()),
        f._busy = !0,
        e[c] = f,
        f.src = a
    },
    a._callback = function(a) {
        e[a] && (e[a]._busy = !1, delete e[a])
    },
    a.ready = !1;
    var f = [];
    a.onReady = function(b) {
        a.ready ? b && b() : b && f.push(b)
    },
    a._readyCallback = function() {
        if (!a.ready) {
            a.ready = !0;
            for (var b, c = 0; b = f[c]; c++) b()
        }
    };
    var g = [],
    h = 0,
    i = function(a, b, d) {
        this.args = a || {},
        this.callback = b,
        this.context = d,
        this.identifier = 0,
        this.state = 1,
        this.percentage = 0,
        i._instances[this.args.packageName] = this,
        this._init(),
        g.push(this.args.packageName),
        h || (h = setTimeout(c, 0))
    };
    return i._instances = {},
    i.HAS_PERCENTAGE = !0,
    i.STATE_READY = 1,
    i.STATE_QUEUING = 2,
    i.STATE_DOWNLOADING = 3,
    i.STATE_PAUSED = 4,
    i.STATE_DOWNLOADED = 5,
    i.STATE_INSTALLING = 6,
    i.STATE_INSTALLED = 7,
    i.STATE_FAILED = 8,
    i._getDownloadState = function(a, b) {
        return a = i._stateMap[a],
        b && a == i.STATE_FAILED && (a = i.STATE_READY),
        a
    },
    i.prototype._callback = function(a, b) {
        if (a) {
            if (this.state == i.STATE_INSTALLED && a >= i.STATE_QUEUING && a <= i.STATE_INSTALLED) return;
            switch (this.state = a, a) {
            case i.STATE_READY:
            case i.STATE_FAILED:
                this.percentage = 0;
                break;
            case i.STATE_DOWNLOADED:
            case i.STATE_INSTALLING:
            case i.STATE_INSTALLED:
                this.percentage = 100
            }
        }
        b && ((this.state == i.STATE_DOWNLOADING || this.state == i.STATE_PAUSED) && i.HAS_PERCENTAGE && isFinite(b.percentage) && (this.percentage = b.percentage), b.identifier && (this.identifier != b.identifier ? (delete i._instances[this.identifier], i._instances[this.identifier = b.identifier] = this) : i._instances[this.identifier] != this && (i._instances[this.identifier] = this))),
        this.callback && this.callback.call(this, this.state, this.percentage, this.context, b)
    },
    i.prototype.doAction = function() {
        switch (this.state) {
        case i.STATE_QUEUING:
        case i.STATE_DOWNLOADING:
            this.stop();
            break;
        case i.STATE_DOWNLOADED:
            this.install();
            break;
        case i.STATE_INSTALLED:
            this.args.isUpdate ? this.start() : a.startApp(this.args.packageName);
            break;
        default:
            this.start()
        }
    },
    i.prototype.dispose = function() {
        return delete i._instances[this.identifier],
        delete i._instances[this.args.packageName],
        !0
    },
    a.Download = i,
    a.SHARE_USER_SELECTION = 0,
    a.SHARE_MOBILEQ = 1,
    a.SAHRE_QZONE = 2,
    a.SAHRE_WECHAT = 3,
    a.SAHRE_WECHAT_TIMELINE = 4,
    a.SHARE_USER_SELECTION_POPUP = 5,
    a._shareInfo = {
        iconUrl: "",
        jumpUrl: location.href,
        title: document.title,
        summary: location.href,
        message: "",
        appBarInfo: ""
    },
    a.setShareInfo = function(b) {
        b = b || {},
        1 == b.allowShare || b.allowShare === !0 ? a._showShareButton && a._showShareButton() : (0 == b.allowShare || b.allowShare === !1) && a._hideShareButton && a._hideShareButton();
        var c = a._shareInfo;
        c.iconUrl = b.iconUrl || c.iconUrl,
        c.jumpUrl = b.jumpUrl || c.jumpUrl,
        c.title = b.title || c.title,
        c.summary = b.summary || c.summary,
        c.message = b.message || c.message,
        c.appBarInfo = b.appBarInfo || c.appBarInfo,
        a._setShareInfo && a._setShareInfo(b)
    },
    a
} (window.YybJsBridge, window),
function(a, b, c) {
    function d(b, c, d) {
        var e = ["jsb:/", b, g, "YybJsBridge.callback?"].join("/"),
        f = [];
        for (var i in c) f.push(encodeURIComponent(i) + "=" + encodeURIComponent(c[i] + ""));
        e += f.join("&"),
        h[g] = {
            cb: d
        },
        a._callWithScheme(e, g++)
    }
    function e() {
        a._callBatch("report", o),
        o = [],
        p = 0
    }
    if (a && a.SCENE == a.SCENE_NONE) {
        var f = navigator.userAgent.match(/\/qqdownloader\/(\d+)(?:\/(appdetail|external|sdk))?/);
        if (f) {
            switch (f[2]) {
            case "appdetail":
                a.SCENE = a.SCENE_DOWNLOADER_DETAIL;
                break;
            case "external":
                a.SCENE = a.SCENE_DOWNLOADER_EXTERNAL;
                break;
            case "sdk":
                a.SCENE = a.SCENE_DOWNLOADER_SDK;
                break;
            default:
                a.SCENE = a.SCENE_DOWNLOADER
            }
            a.allowBatchCall = !0;
            var g = 1,
            h = {},
            i = [],
            j = 0,
            k = a._call = function(b, c, e) {
                a.allowBatchCall ? (i.push({
                    name: b,
                    args: c,
                    onCallback: e
                }), j || (j = setTimeout(function() {
                    if (j = 0, 1 == i.length) d(i[0].name, i[0].args, i[0].onCallback);
                    else {
                        for (var b, c = [], e = 0; b = i[e]; e++) c.push({
                            method: b.name,
                            seqid: g,
                            args: b.args,
                            callback: "YybJsBridge.callback"
                        }),
                        h[g++] = {
                            cb: b.onCallback
                        };
                        var f = ["jsb://callBatch", g, "YybJsBridge.callback?param="].join("/");
                        f += encodeURIComponent(JSON.stringify(c)),
                        a._callWithScheme(f, g++)
                    }
                    i = []
                },
                0))) : d(b, c, e)
            };
            a.callback = function(b) {
                console.log("YybJsBridge.callback: ", b),
                h[b.seqid] && (a._callback(b.seqid), h[b.seqid].cb && h[b.seqid].cb(b), delete h[b.seqid])
            };
            var l = a.Download;
            l._stateMap = {
                QUEUING: l.STATE_QUEUING,
                DOWNLOADING: l.STATE_DOWNLOADING,
                PAUSED: l.STATE_PAUSED,
                DOWNLOADED: l.STATE_DOWNLOADED,
                INSTALLING: l.STATE_INSTALLING,
                INSTALLED: l.STATE_INSTALLED,
                FAIL: l.STATE_FAILED
            };
            var m = [];
            l.prototype._init = function() {
                m.push(this);
                var a = this;
                k("queryDownload", {
                    apkid: this.args.apkid,
                    appid: this.args.hnAppId,
                    packagename: this.args.packageName
                },
                function(b) {
                    if (0 == b.result) {
                        var c = JSON.parse(b.data),
                        d = l._getDownloadState(c.appstate, !0);
                        if (d == l.STATE_INSTALLED) return void a._callback(d);
                        var e = c.downpercent;
                        k("createDownload", {
                            appid: a.args.hnAppId,
                            packagename: a.args.packageName
                        },
                        function(b) {
                            if (0 == b.result) {
                                var c = JSON.parse(b.data);
                                a._callback(d, {
                                    identifier: c.apkid,
                                    percentage: e
                                })
                            } else a._callback(l.STATE_FAILED)
                        })
                    } else delete l._instances[a.identifier],
                    a.identifier = 0,
                    a._callback(l.STATE_READY)
                })
            },
            l.prototype._onResume = function() {
                this._init()
            },
            l.prototype.start = function() {
                switch (this.state) {
                case l.STATE_INSTALLED:
                    if (!this.args.isUpdate) break;
                case l.STATE_READY:
                case l.STATE_FAILED:
                    if (!this.identifier || this.state == l.STATE_INSTALLED) {
                        var a = this;
                        k("createDownload", {
                            appid: this.args.hnAppId,
                            packagename: this.args.packageName,
                            reCreate: this.state == l.STATE_INSTALLED ? 1 : 0
                        },
                        function(b) {
                            var c = l.STATE_READY;
                            if (0 == b.result) {
                                var d = JSON.parse(b.data);
                                a._callback(c, {
                                    identifier: d.apkid
                                }),
                                a.start()
                            } else a._callback(l.STATE_FAILED)
                        });
                        break
                    }
                case l.STATE_PAUSED:
                case l.STATE_DOWNLOADED:
                    k("startDownload", {
                        apkid: this.identifier
                    });
                    break;
                default:
                    this._callback()
                }
                return ! 0
            },
            l.prototype.stop = function() {
                return k("pauseDownload", {
                    apkid: this.identifier
                }),
                !0
            },
            l.prototype.install = function() {
                return k("startDownload", {
                    apkid: this.identifier
                }),
                !0
            },
            b.stateCallback = function(a) {
                if (console.log("window.stateCallback: ", a), 0 == a.result) {
                    var b = JSON.parse(a.data),
                    c = l._getDownloadState(b.appstate);
                    if (b.apkid && c) {
                        var d = l._instances[b.apkid];
                        d && d._callback(c, {
                            percentage: b.down_percent,
                            speed: b.speed
                        })
                    }
                }
            },
            b.appInstallUninstall = function(a) {
                if (0 == a.result) {
                    var b = JSON.parse(a.data),
                    c = l._instances[b.packageName];
                    c && c._callback(1 == b.state ? l.STATE_INSTALLED: l.STATE_READY)
                }
            },
            a.getAppInstalledVersion = function(a, b) {
                return k("getAppInfo", {
                    packagenames: a.join(","),
                    noupdateinfo: 1
                },
                function(a) {
                    var c = {};
                    if (0 == a.result) {
                        var d = JSON.parse(a.data);
                        for (var e in d) 1 == d[e].install && (c[e] = d[e].verCode)
                    }
                    b && b(c)
                }),
                !0
            },
            a.startApp = function(a, b) {
                return k("startOpenApp", {
                    packageName: a,
                    scene: b
                }),
                !0
            },
            a._showShareButton = function() {
                k("setWebView", {
                    buttonVisible: 1
                })
            },
            a._hideShareButton = function() {
                k("setWebView", {
                    buttonVisible: 0
                })
            },
            a.share = function(b) {
                var c = a._shareInfo;
                return k("share", {
                    title: c.title,
                    summary: c.summary,
                    iconUrl: c.iconUrl,
                    jumpUrl: c.jumpUrl,
                    type: b || a.SHARE_USER_SELECTION,
                    message: c.message,
                    appBarInfo: c.appBarInfo
                }),
                !0
            },
            b.clickCallback = function() {
                a.share()
            },
            a.showPictures = function(a, b) {
                return k("showPics", {
                    urls: JSON.stringify(a),
                    position: isFinite(b) && b >= 0 && b < a.length ? b: 0
                }),
                !0
            },
            a.openNewWindow = function(a, b) {
                return b = b || {},
                b.url = a,
                k("openNewWindow", b),
                !0
            };
            var n;
            a.login = function(a) {
                return k("openLoginActivity", {
                    logintype: "QMOBILEQ"
                }),
                n = a,
                !0
            },
            b.loginCallback = function(a) {
                n && n(a),
                n = c
            };
            var o = [],
            p = 0;
            a._callBatch = function(b, c) {
                for (var d, e = [], f = 0; d = c[f]; f++) e.push({
                    method: b,
                    seqid: g++,
                    args: d
                });
                var h = ["jsb://callBatch", g++, "YybJsBridge.callback?param="].join("/");
                h += encodeURIComponent(JSON.stringify(e)),
                a._callWithScheme(h)
            },
            a.report = function(a) {
                o.push(a || {}),
                !p && (p = setTimeout(e, 1e3))
            },
            a.reportImmediate = function(a) {
                k("report", a)
            };
            var q;
            a.onResume = function(a) {
                q = a
            },
            b.activityStateCallback = function(a) {
                if ("onResume" == a.data) {
                    "function" == typeof q && q();
                    var b = m;
                    m = [];
                    for (var c, d = 0; c = b[d]; d++) c._onResume()
                }
            },
            b.userFitCallback = function() {},
            b.readyCallback = a._readyCallback
        }
    }
} (window.YybJsBridge, window),
function(a, b) {
    a.Login = b(a.$)
} (this,
function() {
    function a() {
        mqq.data.getUserInfo(function() {
            for (var a = 0; a < h.length; a++) h[a]();
            h = [],
            i = Date.now()
        })
    }
    function b(b, c) {
        if (h.push(b), ($.os.ios || $.os.android) && mqq.compare("4.7.0") >= 0) {
            var d = Date.now() - i;
            d >= j ? a() : (clearTimeout(e), e = setTimeout(function() {
                a()
            },
            j - d))
        } else if (navigator.userAgent.match(/\/qqdownloader\/(\d+)?/)) YybJsBridge.login();
        else if (navigator.userAgent.match(/\bMicroMessenger\/[\d\.]+/)) {
            var f = "http://" + (c || "xiaoqu.qq.com") + "/cgi-bin/bar/extra/wx/auth?redirect_uri=" + encodeURIComponent(window.location.href),
            g = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxaaec71d24bf34f1d&redirect_uri=" + encodeURIComponent(f) + "&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
            window.location.href = g
        } else {
            var k = encodeURIComponent("http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/login-logo.png");
            window.location.href = "http://ui.ptlogin2.qq.com/cgi-bin/login?pt_no_onekey=1&style=9&appid=1006102&s_url=" + encodeURIComponent(window.location.href) + "&low_login=0&hln_css=" + k
        }
    }
    function c() {
        DB.cgiHttp({
            url: f,
            type: "GET"
        })
    }
    function d() {
        $.os.ios || $.os.android || (c(), setTimeout(function() {
            d()
        },
        g))
    }
    var e, f = "http://xiaoqu.qq.com/cgi-bin/bar/user/sync_auth_state",
    g = 9e5,
    h = [],
    i = 0,
    j = 1e3,
    k = function() {
        var a = $.cookie("uin");
        return a = a ? parseInt(a.substring(1, a.length), 10) : $.cookie("BL_ID"),
        a ? a + "": null
    };
    return {
        continueLogin: d,
        notLoginCallback: b,
        getUin: k
    }
}),
function(a, b) {
    a.Util = b(a.$)
} (this,
function() {
    function a(a, b, d) {
        var e = "http://buluo.qq.com/mobile/detail.html";
        d = Number(d),
        400 === d && (e = "http://buluo.qq.com/mobile/pho_detail.html"),
        401 === d && (e = "http://buluo.qq.com/mobile/article_detail.html");
        var f = [],
        g = [];
        for (var h in a) / ^# / .test(h) ? f.push([h.replace(/^#/, ""), a[h]].join("=")) : g.push([h, a[h]].join("="));
        f = f.length ? "#" + f.join("&") : "",
        g = g.length ? "?" + g.join("&") : "";
        var i = [e, g, f].join("");
        b ? c(i) : c(i, !0)
    }
    function b(a) {
        Util.openUrl("http://qqweb.qq.com/m/qunactivity/details.html?_wv=3&_bid=244&from=tribe&activityid=" + a, !0),
        window.Q && Q.monitor && Q.monitor(521181)
    }
    function c(a, b, c, d) {
        a = a.replace(/^http:\/\/xiaoqu\.qq\.com\/mobile\//g, "http://buluo.qq.com/mobile/");
        var e, f = a.indexOf(G) > -1;
        if (f && (e = J(a), e.query || (e.query = ""), e.query += "&_bid=" + H, e.query += -1 !== a.indexOf("/mobile/activity/") ? "&_wv=1025": "&_wv=" + I, e.fragment || (e.fragment = ""), e.fragment += "&from=" + F, a = K(e)), d) return void window.location.replace(a);
        if (v) return void YybJsBridge.openNewWindow(a);
        if (c = 1, b && window.mqq && mqq.iOS && mqq.compare("4.4") >= 0) f ? a += "&webview=1": c = 0,
        mqq.ui.openUrl({
            url: a,
            target: 1,
            style: c
        });
        else if (b && window.mqq && mqq.android)"search" === F ? (f ? a += "&webview=1": c = 0, mqq.invoke("Troop", "openUrl", {
            url: a
        },
        function(b) {
            b === mqq.ERROR_NO_SUCH_METHOD && mqq.ui.openUrl({
                url: a,
                target: 1,
                style: c
            })
        })) : "pa" === F || "dongtai" === F ? (f ? a += "&webview=1": (c = 0, e = J(a), e.query || (e.query = ""), e.query += "&_wv=" + I, a = K(e)), -1 !== a.indexOf("/mobile/activity/") && (c = 0), mqq.ui.openUrl({
            url: a,
            target: 1,
            style: c
        })) : (f ? a += "&webview=1": c = 0, mqq.ui.openUrl({
            url: a,
            target: 1,
            style: c
        }));
        else { - 1 !== a.indexOf(window.location.pathname) && (a = a.replace(/#/, "&t=" + +new Date + "#"));
            try {
                var g = document.createElement("a");
                g.href = a,
                g.click()
            } catch(h) {
                window.location.href = a
            }
        }
    }
    function d(a, b, c) {
        var d = J(a),
        e = mqq.mapQuery(d.query);
        if (e[b] = c, d.query = mqq.toQuery(e), d.fragment) {
            var f = mqq.mapQuery(d.fragment);
            delete f[b],
            d.fragment = mqq.toQuery(f)
        }
        return K(d)
    }
    function e(a, b) {
        var c = J(a),
        d = mqq.mapQuery(c.query);
        return delete d[b],
        delete d[""],
        c.query = mqq.toQuery(d),
        K(c)
    }
    function f(a, b) {
        a = a.replace(/^http:\/\/xiaoqu\.qq\.com\/mobile\//g, "http://buluo.qq.com/mobile/"),
        a = encodeURIComponent(a);
        var c = {
            type: "GET",
            url: "/cgi-bin/bar/extra/gen_short_url",
            param: {
                urls: JSON.stringify([a])
            },
            succ: function(a) {
                var c;
                a.result.ls[0] && a.result.ls[0].url_code ? (c = d(a.result.ls[0].url_code, "_wv", "1027"), b && b(c)) : b && b(c)
            },
            err: function() {
                b && b(a)
            }
        };
        DB.cgiHttp(c)
    }
    function g(a, b) {
        a.share_url && (a.share_url = a.share_url.replace(/^http:\/\/xiaoqu\.qq\.com\/mobile\//g, "http://buluo.qq.com/mobile/")),
        a.page_url && (a.page_url = a.page_url.replace(/^http:\/\/xiaoqu\.qq\.com\/mobile\//g, "http://buluo.qq.com/mobile/"));
        var c = $.cookie("vkey"),
        d = {
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
        f(d.targetUrl,
        function(a) {
            d.targetUrl = a,
            c && (d.sid = c),
            a = "http://openmobile.qq.com/api/check?page=shareindex.html&style=9&status_os=0&sdkp=0& pt_no_onekey=1";
            for (var e in d) d.hasOwnProperty(e) && (a += "&" + e + "=" + encodeURIComponent(d[e]));
            b(a)
        })
    }
    function h(a) {
        return a.indexOf("ugc.qpic.cn") > -1 && /\/$/.test(a) && (a += "200"),
        a
    }
    function i(a, b, c) {
        function i(a) {
            var b = function(a) {
                0 === a.retCode && (k.succHandler && k.succHandler(k.share_type), Q.tdw({
                    opername: "tribe_cgi",
                    module: "post",
                    action: "share",
                    ver1: Util.getHash("bid") || Util.queryString("bid"),
                    obj1: Util.getHash("pid") || Util.queryString("pid")
                }), Tip.show("已发送"))
            };
            a ? mqq.invoke("ui", "shareMessage", k, b) : mqq.ui.shareMessage(k, b)
        }
        function j() {
            if (a.imageInfo && a.imageInfo.pic && a.imageInfo.pic.w >= 100 && a.imageInfo.pic.h >= 100) i();
            else {
                var b = new Image;
                b.src = k.image_url;
                var c = "http://p.qpic.cn/qqconadmin/0/795b1405de9e46fd85fdcab7c56b4909/0";
                b.onload = b.onerror = function(b) {
                    b.target.width >= 100 && b.target.height >= 100 ? i() : window.location.pathname.indexOf("barindex.html") > -1 ? (k.image_url = c, i()) : DB.cgiHttp({
                        type: "GET",
                        url: "/cgi-bin/bar/get_bar_logo",
                        param: {
                            bid: a.imageInfo.bid
                        },
                        succ: function(a) {
                            var b = 0 === a.retcode ? a.result.pic: c;
                            k.image_url = b,
                            i()
                        },
                        err: function() {
                            k.image_url = c,
                            i()
                        }
                    })
                }
            }
        }
        a.content = filterImgTag(a.content).replace(/<br>/g, "").replace(/\&nbsp;/g, " ");
        var k = {
            title: String(a.title).length > 16 ? String(a.title).substring(0, 16) + "...": String(a.title),
            desc: String(a.content || a.title).substring(0, 50),
            share_url: a.shareUrl,
            image_url: h(a.imageUrl),
            back: a.noback ? !1 : !0,
            oaUin: "472839098",
            sourceName: "兴趣部落",
            puin: "472839098",
            page_url: a.pageUrl,
            barName: a.barName,
            succHandler: a.succHandler,
            localSaveCallback: a.localSaveCallback
        };
        k.share_url = e(k.share_url, "sid"),
        k.page_url = e(k.page_url, "sid");
        var l;
        if (l = /spring_rank/.test(window.location.href) ? ["share_rankqq", "share_rankqzone", "share_rankwechat", "share_rankcircle", "share_rankweibo", "share_ranklink"] : ["share_qq", "share_qzone", "share_wechat", "share_circle", "share_weibo", "share_link"], mqq.compare("4.7.2") > -1) {
            var m;
            RichShare.show(function(a) {
                k.share_url = d(k.share_url, "from", l[a]),
                b(0, a, k),
                a > -1 && 4 > a && ((2 === a || 3 === a) && (k.share_url += "#wechat_redirect"), k.share_type = a, c ? i() : f(k.share_url,
                function(b) {
                    k.share_url = b,
                    1 === a ? j() : i()
                })),
                4 === a && f(k.share_url,
                function(a) {
                    var b;
                    b = k.barName ? "#兴趣部落#" + k.barName + "部落-" + k.desc: "#兴趣部落#【" + k.title + "】" + k.desc,
                    a = encodeURIComponent(k.share_url);
                    var c = encodeURIComponent(b),
                    d = encodeURIComponent(k.image_url),
                    e = "http://v.t.sina.com.cn/share/share.php?title=" + c + "&url=" + a + "&pic=" + d;
                    mqq.ui.openUrl({
                        url: e,
                        target: 1,
                        style: 1
                    })
                }),
                5 === a && f(k.share_url,
                function(a) {
                    mqq.data.setClipboard({
                        text: a
                    },
                    function(a) {
                        a && Tip.show("已复制到剪切板")
                    })
                }),
                7 === a && (new RegExp("barindex").test(location.pathname) && mqq.support("mqq.ui.openUrl") ? (E("from", "desktop"), m = "http://xiaoqu.qq.com/mobile/shortcut.html?bid=" + (B("bid") || D("bid")) + "&url=" + encodeURIComponent(window.location.href) + "&name=" + encodeURIComponent(k.barName) + "&img=" + encodeURIComponent(k.image_url) + "&uin=" + Login.getUin() + "#shortcut=mqq", mqq.ui.openUrl({
                    url: m,
                    target: 2
                })) : Tip.show("抱歉，暂不支持此功能")),
                8 === a && (m = "http://xiaoqu.qq.com/mobile/bar_qrcode.html?bid=" + (B("bid") || D("bid")), mqq.ui.openUrl({
                    url: m,
                    target: 1,
                    style: 1
                })),
                9 === a && mqq.media.saveImage({
                    content: k.image_url
                },
                k.localSaveCallback)
            })
        } else mqq.compare("4.7.0") > -1 ? (b(0, 0), c ? i() : f(k.share_url,
        function(a) {
            k.share_url = a,
            mqq.ui.shareRichMessage(k,
            function() {})
        })) : navigator.userAgent.match(/\bMicroMessenger\/[\d\.]+/) ? RichShare.show() : -1 !== navigator.userAgent.indexOf("QQHD") ? i(!0) : (b(1, 0), g(k,
        function(a) {
            f(a,
            function(b) {
                a = b,
                Util.openUrl(a)
            })
        }))
    }
    function j(a) {
        a = a || "";
        var b = a.match(/<br>/g) || [],
        c = b.length <= 3 ? "": "large";
        return c
    }
    function k(a, b, c) {
        var d = window.requestAnimationFrame || window.webkitRequestAnimationFrame ||
        function(a) {
            setTimeout(a, 0)
        };
        $.os.ios ? 6500 > a ? (c.css({
            transition: "-webkit-transform 0.2s linear",
            "-webkit-transform": "translateY(" + -1 * a + "px)"
        }), b.scrollTop(0), d(function() {
            c.css({
                "-webkit-transform": "translateY(0)"
            })
        },
        0)) : b.scrollTop(0) : (b = b.scrollTop() ? b: $(window), b.scrollTop(0))
    }
    function l(a) {
        return a = parseInt(a),
        10037 === a || 22631 === a || 23308 === a
    }
    function m(a) {
        return a = parseInt(a),
        10037 === a || 23308 === a
    }
    function n() {
        var a = +new Date(2015, 1, 17, 18, 0, 0),
        b = new Date,
        c = +new Date(b.getTime() + 60 * b.getTimezoneOffset() * 1e3 + 288e5);
        return a > c
    }
    function o() {}
    function p(a) {
        return a.path = a.path || "buluo",
        a.host = a.host || "",
        a.callid = a.callid || +new Date % 1024,
        a
    }
    function q(a, b) {
        if ("string" == typeof b) return a.hasOwnProperty(b) && void 0 !== a[b];
        if ("[object Array]" === Object.prototype.toString.call(b)) return b.forEach(function(b) {
            return Object.hasOwnProperty(b) && void 0 !== a[b] ? void 0 : !1
        }),
        !0;
        throw Error("objectHasKeys args err")
    }
    function r(a) {
        return x ? (a = p(a), q(a, ["key", "data"]) ? (a.data = JSON.stringify(a.data), void z(a,
        function(b) {
            0 === b.ret && b.response ? (a.success || o)(b.data) : (a.error || o)(b.data)
        })) : void 0) : void 0
    }
    function s(a) {
        return x ? (a = p(a), q(a, ["key"]) ? void y(a,
        function(b) {
            0 === b.ret && b.response ? (a.success || o)(b.data) : (a.error || o)(b.data)
        }) : void 0) : void 0
    }
    function t(a) {
        return x ? (a = p(a), q(a, ["key"]) ? void A(a,
        function(b) {
            0 === b.ret && b.response ? (a.success || o)(b.data) : (a.error || o)(b.data)
        }) : void 0) : void 0
    }
    var u = /\bPA\b/.test(navigator.userAgent),
    v = !1,
    w = navigator.userAgent.match(/\/qqdownloader\/(\d+)?/),
    x = mqq && mqq.data && mqq.support("mqq.data.readH5Data") && mqq.support("mqq.data.writeH5Data"),
    y = mqq.data.readH5Data,
    z = mqq.data.writeH5Data,
    A = mqq.data.deleteH5Data;
    w && (v = !0);
    var B = function(a) {
        var b = window.location.search.match(new RegExp("(?:\\?|&)" + a + "=([^&]*)(&|$)"));
        return b ? decodeURIComponent(b[1]) : ""
    },
    C = function(a, b) {
        mqq.support("mqq.ui.showDialog") ? mqq.ui.showDialog({
            title: "重要声明",
            text: "本部落严禁发布反动，色情，广告，诈骗等违法信息，一经发现，一律删帖，并将发布人永久拉黑！",
            needOkBtn: !0,
            needCancelBtn: !1
        },
        function(c) {
            0 === c.button && (localStorage.setItem("pho_alert" + b, "1"), a && a())
        }) : a && a()
    },
    D = function(a) {
        var b = window.location.hash.match(new RegExp("(?:#|&)" + a + "=([^&]*)(&|$)"));
        return b ? decodeURIComponent(b[1]) : ""
    },
    E = function(a, b) {
        var c = D(a);
        c ? window.location.hash = window.location.hash.replace(new RegExp("(?:#|&)" + a + "=([^&]*)(&|$)"),
        function(a, c) {
            return a.replace("=" + c, "=" + b)
        }) : window.location.hash += "&" + [a, b].join("=")
    },
    F = u ? "pa": encodeURIComponent(D("from") || B("from")),
    G = "http://buluo.qq.com/mobile/",
    H = B("_bid"),
    I = 1027,
    J = function(a) {
        var b = null;
        if (null !== (b = J.RE.exec(a))) {
            for (var c = {},
            d = 0,
            e = J.SPEC.length; e > d; d++) {
                var f = J.SPEC[d];
                c[f] = b[d + 1]
            }
            b = c,
            c = null
        }
        return b
    },
    K = function(a) {
        for (var b = "",
        c = {},
        d = {},
        e = 0,
        f = J.SPEC.length; f > e; e++) {
            var g = J.SPEC[e];
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
        return c = null,
        d = null,
        a = null,
        b
    },
    L = function(a) {
        if (a && 0 === a.length) return [];
        for (var b = [], c = 0, d = a.length; d > c; c++) {
            var e = a[c]; - 1 === b.indexOf(e) && b.push(e)
        }
        return b
    },
    M = function(a, b) {
        if (a && 0 === a.length) return [];
        for (var c = [], d = [], e = 0, f = a.length; f > e; e++) {
            var g = a[e];
            g[b] && -1 === c.indexOf(g[b]) && (c.push(g[b]), d.push(g))
        }
        return d
    };
    return J.SPEC = ["scheme", "user", "pass", "host", "port", "path", "query", "fragment"],
    J.RE = /^([^:]+):\/\/(?:([^:@]+):?([^@]*)@)?(?:([^/ ? #: ] + ) : ?(\d * ))([ ^ ?#] * )( ? :\ ? ([ ^ #] + )) ? ( ? :# (. + )) ? $ / ,
    {
        queryString: B,
        getHash: D,
        setHash: E,
        openUrl: c,
        openDetail: a,
        openQunAct: b,
        shareMessage: i,
        unique: L,
        uniqueKey: M,
        showStatement: C,
        getTextType: j,
        setExterParam: d,
        scrollElTop: k,
        isFestival: l,
        isQQbar: m,
        isBeforeFestival: n,
        h5Data: {
            h5DataSupport: x,
            setItem: r,
            getItem: s,
            clear: t
        }
    }
}),
function() {
    var a = $.ajax,
    b = !!window.localStorage,
    c = "buluo_cacheKeyList",
    d = function(a) {
        var b = +new Date;
        return b - a.timestamp <= a.timeout ? !0 : !1
    },
    e = function(a, b) {
        return a.version === b ? !0 : !1
    },
    f = function() {
        var a = [];
        try {
            return a = localStorage.getItem(c),
            a = JSON.parse(a) || []
        } catch(b) {}
    },
    g = function(a, b) {
        var d, e = f();
        d = e.indexOf(a),
        "add" === b && -1 === d ? e.push(a) : "remove" === b && -1 !== d && e.splice(d, 1);
        try {
            localStorage.setItem(c, JSON.stringify(e))
        } catch(g) {}
    },
    h = function() {
        var a, b = f();
        if (b.length) for (var c = b.length; c--;) a = b[c],
        /^im_/.test(a) || (localStorage.removeItem(a), g(a, "remove"))
    },
    i = function(a, b, c, d, e) {
        a.fromCache = 1;
        var f = {
            data: a,
            timestamp: +new Date,
            timeout: c,
            version: d
        };
        e && (b = "im_" + b);
        try {
            localStorage.setItem(b, JSON.stringify(f)),
            g(b, "add")
        } catch(i) {
            h(),
            localStorage.setItem(b, JSON.stringify(f)),
            g(b, "add")
        }
    };
    $.ajax = function(c) {
        var f = c.localCache,
        g = c.cacheKey || c.url + "-" + JSON.stringify(c.data),
        h = c.cacheTimeout || 864e5,
        j = c.success,
        k = c.notThisTime || !1,
        l = c.defaultData,
        m = c.cacheVersion || "1",
        n = c.important,
        o = null,
        p = !1;
        if (b && f) try {
            o = JSON.parse(localStorage.getItem(g))
        } catch(q) {}
        o && d(o) && e(o, m) ? (k || c.success(o.data), p = !0) : l && (c.success(l), p = !0);
        var r = function(a, d, e) {
            var k = function() {
                window.Badjs.cgiErrMinitor()
            };
            return "undefined" == typeof a ? void k() : null === a ? void k() : "number" == typeof a && -1 === String(a).indexOf(".") ? void k() : ("string" == typeof a && (a = JSON.parse(a)), p && c.update ? c.update(a, d, e) : j(a, d, e), void(f && 0 === a.retcode && b && a.result && i(a, g, h, m, n)))
        };
        c.success = r,
        a(c)
    }
} (),
function(a, b) {
    a.DB = b()
} (this,
function() {
    function extend(a, b, c) {
        if ("object" != typeof a) return a;
        b = b || {};
        for (var d in b) if (b.hasOwnProperty(d)) {
            var e, f;
            a[d] = c && "function" == typeof(e = a[d]) && "function" == typeof(f = b[d]) ?
            function() {
                f.apply(a, arguments) || e.apply(a, arguments)
            }: b[d]
        }
    }
    function encryptSkey(a) {
        if (!a) return "";
        for (var b = 5381,
        c = 0,
        d = a.length; d > c; ++c) b += (b << 5) + a.charAt(c).charCodeAt();
        return 2147483647 & b
    }
    function parseData(data) {
        if ("object" != typeof data) {
            if (!data) return {
                ec: 997,
                text: data,
                msg: "data is null"
            };
            if ("{" !== data.charAt(0) || "}" !== data.charAt(data.length - 1)) return {
                ec: 998,
                text: data,
                msg: "data is hijack"
            };
            try {
                return window.JSON && JSON.parse ? JSON.parse(data) : eval("(" + data + ")")
            } catch(e) {
                return {
                    ec: 999,
                    text: data,
                    msg: "data is not json"
                }
            }
        }
        return data
    }
    var requestQueue = {},
    retryCallBackArr = [],
    hasShowDialog = !1,
    ssoEnable = mqq && mqq.compare("5.3.2") > -1,
    isLoginError = !1,
    ajax = function() {
        return function(a, b, c, d, e) {
            var f, g, h = +new Date;
            a.indexOf("http") < 0 && (a = window.location.origin ? window.location.origin + a: window.location.host ? "http://" + window.location.host + a: "http://xiaoqu.qq.com" + a);
            var i = {
                type: d,
                url: a,
                data: b,
                localCache: e.localCache,
                cacheKey: e.cacheKey,
                cacheTimeout: e.cacheTimeout,
                defaultData: e.defaultData,
                cacheVersion: e.cacheVersion,
                update: e.update,
                impotant: e.impotant,
                notThisTime: e.notThisTime,
                success: function(a) {
                    f = +new Date,
                    c && c(a)
                },
                error: function(a) {
                    f = +new Date,
                    c && c({
                        ec: a.status || -1
                    })
                },
                complete: function(b) {
                    var c = null;
                    try {
                        var d = JSON.parse(b.responseText);
                        c = d.retcode,
                        "undefined" == typeof c && (c = d.retcode = d.ec),
                        "result" in d || Q.monitor(449716)
                    } catch(e) {
                        c = b.status ? b.status: 1234
                    }
                    g = a.indexOf("?") > -1 ? a.slice(0, a.indexOf("?")) : a,
                    g.indexOf("http") < 0 && (g = location.protocol + "//" + location.host + g),
                    Q.mmReport(g, c, f - h)
                }
            };
            0 !== a.indexOf(location.origin) && (i.xhrFields = {
                withCredentials: !0
            }),
            $.ajax(i)
        }
    } ();
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
            for (var f in b) b.hasOwnProperty(f) && e.push(f + "=" + b[f]);
            return e.push("r=" + Math.random()),
            a.indexOf("?") < 0 && (a += "?"),
            a += e.join("&"),
            ajax(a, null, c, "GET", d)
        },
        post: function(a, b, c, d) {
            var e = [];
            for (var f in b) b.hasOwnProperty(f) && e.push(f + "=" + b[f]);
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
                    if (e = +new Date, console.log("sso " + d.ssoCmd + " get data: ", a), a.data) {
                        var b = {};
                        try {
                            b = JSON.parse(a.data),
                            g = b.retcode
                        } catch(i) {
                            g = 1234
                        }
                        c(b)
                    } else g = isNaN(a) ? a.retcode || -42701 : a,
                    c({
                        ec: -1
                    });
                    setTimeout(function() {
                        Q.mmReport(f, g, e - h)
                    },
                    0)
                })
            })
        },
        cgiHttp: function(a) {
            if (a && a.url) {
                var b = "",
                c = DB.wrapGroup(a.param),
                d = 0;
                if (b = a.url + "/" + encodeURIComponent(JSON.stringify(c)), requestQueue[b]) return void requestQueue[b].push(a);
                requestQueue[b] = [a];
                var e, f = this,
                g = {
                    url: a.url
                },
                h = function(i) {
                    g.time = +new Date;
                    var j, k = requestQueue[b] || [];
                    try {
                        i && i.result && i.result.public_account_post && 301 === i.result.type ? (j = i.result.public_account_post, i = JSON.stringify(i), i = i.replace(/<br>/g, "\\n"), i = i.replace(/</g, "&lt;").replace(/>/g, "&gt;"), i = JSON.parse(i), i.result.public_account_post = j) : i && i.result && 302 === i.result.type ? console.log("rich post") : (i = JSON.stringify(i), i = i.replace(/<br>/g, "\\n"), i = i.replace(/</g, "&lt;").replace(/>/g, "&gt;"), i = JSON.parse(i))
                    } catch(l) {}
                    i = parseData(i);
                    var m;
                    m = i.retcode = "undefined" != typeof i.retcode ? i.retcode: "undefined" != typeof i.ec ? i.ec: i.ret,
                    a.url.indexOf("http://gamecenter.qq.com/") > -1 && (m = i.ecode, i.data && i.data.key && i.data.key.retBody && -12e4 === i.data.key.retBody.result && (m = 1e5)),
                    0 !== m && (Badjs("cgiErrDes:the retcode is not 0,but " + m + "!cgiUrl:" + a.url, location.href, 0, 387377, 2, 464198), console.error("cgiErrDes:the retcode is not 0,but " + m + "!cgiUrl:" + a.url), console.report && console.report({
                        type: "error",
                        category: "",
                        content: "cgiErrDes:the retcode is not 0,but " + m + "!cgiUrl:" + a.url
                    }));
                    var n, o;
                    switch (1e5 !== m && delete requestQueue[b], m) {
                    case 0:
                        n = !1,
                        k.forEach(function(a) {
                            a.succ && a.succ(i)
                        });
                        break;
                    case 1e5:
                        isLoginError = !0;
                        var p = (i || {}).host || "xiaoqu.qq.com";
                        if (a.noNeedLogin) n = !0,
                        delete requestQueue[b];
                        else if ("undefined" != typeof Login) {
                            var q = function() {
                                c.bkn = f.status.bkn = f.encryptSkey($.cookie("skey")),
                                e(a.url, c, h, a)
                            };
                            3 > d ? (Login.notLoginCallback(q, p), d++) : mqq.ui && mqq.ui.showDialog && (hasShowDialog ? retryCallBackArr.push(q) : (mqq.ui.showDialog({
                                title: "提示",
                                text: "登录态失效，请点击重试",
                                needOkBtn: !0,
                                needCancelBtn: !0
                            },
                            function(a) {
                                0 === a.button ? Login.notLoginCallback(function() {
                                    for (var a = 0,
                                    b = retryCallBackArr.length; b > a; a++) retryCallBackArr[a]();
                                    retryCallBackArr = [],
                                    hasShowDialog = !1
                                }) : mqq.ui.popBack()
                            }), retryCallBackArr.push(q), hasShowDialog = !0))
                        } else n = !0;
                        break;
                    case 100001:
                        n = !0;
                        break;
                    case 100003:
                        n = !0,
                        o = !0;
                        break;
                    default:
                        n = !0,
                        o = !0
                    }
                    n && k.forEach(function(a) {
                        a.err && a.err(i)
                    })
                };
                e = f.get,
                console.log("ssoEnable:" + ssoEnable + ", ssoCmd:" + a.ssoCmd),
                ssoEnable && a.ssoCmd && "" !== a.ssoCmd && "undefined" !== a.ssoCmd ? e = f.sso: a.type && "POST" !== a.type || (f.status.bkn || (f.status.bkn = f.encryptSkey($.cookie("skey"))), c.bkn = f.status.bkn, e = f.post);
                var i = e(a.url, c, h, a);
                return i
            }
        },
        extend: function(a, b, c, d) {
            var e = Object.prototype.toString.call(a);
            if ("[object String]" === e) {
                c = c || this;
                var f = {};
                f[a] = b,
                extend(c, f, d)
            } else "[object Object]" === e && ((null === b || void 0 === b) && arguments.length >= 4 ? (c = c || this, extend(c, a, d)) : (d = c, c = b || this, extend(c, a, d)))
        },
        wrapGroup: function(a) {
            return a || {}
        }
    }
}),
function(a, b) {
    "function" == typeof define && define.amd ? define([], b) : a.FormatTime = b()
} (this,
function() {
    function a(a) {
        var b = new Date;
        return b.setDate(b.getDate() - 1),
        b.getDate() === a.getDate() && b.getFullYear() === a.getFullYear() ? !0 : !1
    }
    return function(b, c) {
        if (b = Number(b), !b || 0 > b) return "";
        c = c || new Date / 1e3;
        var d = c - b,
        e = new Date(1e3 * b),
        f = new Date(1e3 * c),
        g = f.getFullYear(),
        h = e.getFullYear(),
        i = e.getMonth() + 1,
        j = e.getDate();
        return a(e) ? "昨天": 300 > d ? "刚刚": 3600 > d ? Math.floor(d / 60) + "分钟前": 86400 > d ? Math.floor(d / 3600) + "小时前": g === h ? i + "-" + j: h + "-" + i
    }
}),
function() {
    var a = /\{\{([^\}]*)\}\}/g,
    b = function(a) {
        return new RegExp("(^|\\s+)" + a + "(\\s+|$)", "g")
    },
    c = function(a, c) {
        return a.className ? void(a.className.match(b(c)) || (a.className += " " + c)) : void(a.className = c)
    },
    d = function(a, b) {
        var c = function(d, e) {
            var f = e.indexOf(".");
            if (f > -1) {
                var g = e.substr(0, f);
                if (e = e.substr(f + 1), "undefined" != typeof d[g]) return c(d[g], e);
                var h = {
                    name: b,
                    data: a
                };
                return s("nullvalue", {
                    type: "nullattr",
                    data: h
                },
                h),
                ""
            }
            var i;
            if ("undefined" != typeof d[e]) i = d[e];
            else {
                var h = {
                    name: b,
                    data: a
                };
                s("nullvalue", {
                    type: "nullvalue",
                    data: h
                },
                h),
                i = ""
            }
            return i
        };
        return c(a, b)
    },
    e = /"([^"]*)"|'([^']*)'/g,
    f = /[a-zA-Z_\$]+[\w\$]*(?:\s*\.\s*(?:[a-zA-Z_\$]+[\w\$]*|\d+))*/g,
    g = /\[([^\[\]]*)\]/g,
    h = /\|\|/g,
    i = "OR_OPERATOR",
    j = function() {
        return "$$" + ~~ (1e6 * Math.random())
    },
    k = function(a, b) {
        a = a.replace(h, i).split("|");
        for (var c = 0; c < a.length; c++) a[c] = (a[c].replace(new RegExp(i, "g"), "||") || "").trim();
        var l = a[0] || "",
        m = a.slice(1);
        for (l = l.replace(e,
        function(a, c, d) {
            var e = j();
            return b[e] = c || d,
            e
        }); g.test(l);) g.lastIndex = 0,
        l = l.replace(g,
        function(a, c) {
            return "." + k(c, b)
        });
        l = l.replace(f,
        function(a) {
            return "getValue(scope,'" + a.trim() + "')"
        });
        var o = function() {
            var a = m.shift();
            if (a) {
                for (var a = a.split(":"), b = a.slice(1) || [], c = a[0] || "", d = 0; d < b.length; d++) f.test(b[d]) && (b[d] = "getValue(scope,'" + b[d] + "')");
                n[c] && (b.unshift(l), b = b.join(","), l = "sodaFilterMap['" + c + "'](" + b + ")"),
                o()
            }
        };
        o();
        var p = new Function("getValue", "sodaFilterMap", "return function sodaExp(scope){ return " + l + "}")(d, n);
        return p(b)
    },
    l = function(b, c) { [].map.call([].slice.call(b.childNodes, []),
        function(b) {
            if (3 === b.nodeType && (b.nodeValue = b.nodeValue.replace(a,
            function(a, b) {
                return k(b, c)
            })), b.attributes) if (/in/.test(b.getAttribute("soda-repeat") || "")) m["soda-repeat"].link(c, b, b.attributes);
            else {
                if ((b.getAttribute("soda-if") || "").trim() && (m["soda-if"].link(c, b, b.attributes), "removed" === b.getAttribute("removed"))) return;
                var d; [].map.call(b.attributes,
                function(e) {
                    if ("soda-if" !== e.name) if (/^soda-/.test(e.name)) {
                        if (m[e.name]) {
                            var f = m[e.name],
                            g = f.link(c, b, b.attributes);
                            g && "childDone" === g.command && (d = 1)
                        }
                    } else e.value = e.value.replace(a,
                    function(a, b) {
                        return k(b, c)
                    })
                }),
                d || l(b, c)
            }
        })
    },
    m = {},
    n = {},
    o = function(a, b) {
        m["soda-" + a] = b()
    },
    p = function(a, b) {
        n[a] = b
    };
    p("date",
    function(a, b) {
        return b
    }),
    o("repeat",
    function() {
        return {
            compile: function() {},
            link: function(b, c) {
                var e, f, g, h = c.getAttribute("soda-repeat"),
                i = /\s+track\s+by\s+([^\s]+)$/;
                h = h.replace(i,
                function(a, b) {
                    return b && (g = (b || "").trim()),
                    ""
                }),
                g = g || "$index";
                var j = /([^\s]+)\s+in\s+([^\s]+)/,
                n = j.exec(h);
                if (n && (e = (n[1] || "").trim(), f = (n[2] || "").trim(), e && f)) {
                    for (var o = d(b, f) || [], p = c, q = 0; q < o.length; q++) {
                        var r = c.cloneNode(),
                        s = {};
                        s[g] = q,
                        s[e] = o[q],
                        s.__proto__ = b,
                        r.innerHTML = c.innerHTML,
                        (r.getAttribute("soda-if") || "").trim() && (m["soda-if"].link(s, r, r.attributes), "removed" === r.getAttribute("removed")) || ([].map.call(r.attributes,
                        function(b) {
                            if ("removed" !== r.getAttribute("removed") && "soda-repeat" !== b.name.trim() && "soda-if" !== b.name.trim()) if (/^soda-/.test(b.name)) {
                                if (m[b.name]) {
                                    var c = m[b.name];
                                    c.link(s, r, r.attributes)
                                }
                            } else b.value = b.value.replace(a,
                            function(a, b) {
                                return k(b, s)
                            })
                        }), "removed" !== r.getAttribute("removed") && (l(r, s), c.parentNode.insertBefore(r, p.nextSibling), p = r))
                    }
                    c.parentNode.removeChild(c)
                }
            }
        }
    }),
    o("if",
    function() {
        return {
            link: function(a, b) {
                var c = b.getAttribute("soda-if"),
                d = k(c, a);
                d || (b.setAttribute("removed", "removed"), b.parentNode && b.parentNode.removeChild(b))
            }
        }
    }),
    o("class",
    function() {
        return {
            link: function(a, b) {
                var d = b.getAttribute("soda-class"),
                e = k(d, a);
                e && c(b, e)
            }
        }
    }),
    o("src",
    function() {
        return {
            link: function(b, c) {
                var d = c.getAttribute("soda-src"),
                e = d.replace(a,
                function(a, c) {
                    return k(c, b)
                });
                e && c.setAttribute("src", e)
            }
        }
    }),
    o("bind-html",
    function() {
        return {
            link: function(a, b) {
                var c = b.getAttribute("soda-bind-html"),
                d = k(c, a);
                return d ? (b.innerHTML = d, {
                    command: "childDone"
                }) : void 0
            }
        }
    });
    var q = function(a, b) {
        var c = document.createElement("div");
        c.innerHTML = a,
        l(c, b);
        var d = document.createDocumentFragment();
        d.innerHTML = c.innerHTML;
        for (var e; e = c.childNodes[0];) d.appendChild(e);
        return d
    },
    r = {};
    q.addEventListener = function(a, b) {
        r[a] || (r[a] = []),
        r[a].push(b)
    };
    var s = function(a, b, c) {
        for (var d = r[a] || [], e = 0; e < d.length; e++) {
            var f = d[e];
            f && f(b, c)
        }
    };
    window.sodaRender = q,
    window.sodaFilter = p
} (),
function(a, b) {
    "function" == typeof define && define.amd ? define([], b) : a.Tmpl || (a.Tmpl = b())
} (this,
function() {
    function a(c, d, e) {
        return this instanceof a ? (this.tmplFunction = b[c], void(d && this.render(d, e))) : new a(c, d, e)
    }
    var b = {};
    return a.addTmpl = function(a, c) {
        b[a] = c
    },
    a.prototype = {
        render: function(a, b) {
            return "string" == typeof this.tmplFunction ? this.tmplEle = sodaRender(this.tmplFunction, a) : this.tmplFunction && (this.tmplString = this.tmplFunction(a, b)),
            this
        },
        appendTo: function(a, b) {
            var c;
            if (this.tmplEle) c = this.tmplEle;
            else {
                var d = document.createElement("div");
                d.innerHTML = this.tmplString,
                c = document.createDocumentFragment();
                for (var e = null; e = d.childNodes[0];) c.appendChild(e)
            }
            return "fadeIn" === b && $(c).children().addClass("ui-fade").css({
                "-webkit-transition": "opacity 0.15s ease-in",
                opacity: 0
            }),
            a.append(c),
            "fadeIn" === b && setTimeout(function() {
                a.children(".ui-fade").css("opacity", 1).removeClass("ui-fade")
            },
            0),
            this
        },
        update: function(a) {
            a.empty(),
            this.appendTo(a)
        },
        valueOf: function() {
            return this.tmplString || this.tmplEle && this.tmplEle.innerHTML || ""
        },
        toString: function() {
            return this.tmplString || this.tmplEle && this.tmplEle.innerHTML || ""
        }
    },
    a
}),
function(a, b) {
    a.Tip = b(a.Simple)
} (this,
function() {
    var a, b, c, d, e, f = !1,
    g = function() {
        a = $('<div class="tip"></div>'),
        a.html("<i></i><span></span>"),
        $(document.body).append(a),
        c = a.children()[0],
        b = a.children()[1]
    };
    return {
        show: function(h, i) {
            f || (g(), f = !0),
            i = $.extend({
                interval: 2e3,
                top: 0,
                type: "ok"
            },
            i);
            var j = i.interval;
            if (c.className = i.type, b.innerHTML = h, setTimeout(function() {
                a.addClass("show")
            },
            100), d = !0, j) {
                var k = this;
                clearTimeout(e),
                e = setTimeout(function() {
                    k.hide()
                },
                j)
            }
        },
        hide: function() {
            a && (a.removeClass("show"), d = !1)
        }
    }
}),
function(a, b) {
    a.$ = b(a.$)
} (this,
function(a) {
    return a.storage = function() {
        function a(a, b) {
            if (window.localStorage) {
                var d, e = localStorage.getItem(c);
                if (e) try {
                    d = JSON.parse(e)
                } catch(f) {
                    d = {}
                } else d = {};
                d[a] = b;
                try {
                    localStorage.setItem(c, JSON.stringify(d))
                } catch(f) {}
            }
        }
        function b(a) {
            if (window.localStorage) {
                var b, d = localStorage.getItem(c);
                if (d) try {
                    return b = JSON.parse(d),
                    b[a]
                } catch(e) {}
            }
            return ""
        }
        var c = Login.getUin() + "buluoInfo";
        return {
            set: a,
            get: b
        }
    } (),
    window.localStorage.clear = function() {
        for (var a in window.localStorage) if (window.localStorage.hasOwnProperty(a)) {
            if (/^im_/.test(a)) continue;
            window.localStorage.removeItem(a)
        }
    },
    a
}),
function(a, b) {
    a.pollRefreshUi = b()
} (this,
function() {
    var a, b, c, d, e, f = 100,
    g = 180,
    h = 33,
    i = h,
    j = h,
    k = i,
    l = [f / 2, h],
    m = l.concat([]),
    n = 0,
    o = function() {},
    p = 0,
    q = '<div class="poll-wrapper"><canvas id="poll"></canvas><span id="reloadIcon"></span><div class="spinner" id="loadingIcon"></div><div class="poll-font"><span></span>刷新成功</div></div>',
    r = function(e) {
        if (!p) {
            b.beginPath(),
            b.arc(l[0], l[1], j, -Math.PI, 0);
            var f = [l[0] + j, l[1]],
            g = [m[0] + k, m[1]],
            i = s(f, g, e);
            b.quadraticCurveTo(i[0], i[1], m[0] + k, m[1]),
            b.arc(m[0], m[1], k, 0, Math.PI),
            f = [l[0] - j, l[1]],
            g = [m[0] - k, m[1]],
            i = s(f, g, e, 1),
            b.quadraticCurveTo(i[0], i[1], l[0] - j, l[1]),
            b.closePath(),
            b.fill(),
            b.stroke(),
            c.style.webkitTransform = "scale(" + j / h + ")",
            8 > k && (p = 1, $(d).show(), $(a).hide(), $(c).hide(), o())
        }
    },
    s = function(a, b, c, d) {
        var e, f = (b[1] - a[1]) / (b[0] - a[0]),
        g = -(1 / f),
        h = (b[0] + a[0]) / 2,
        i = (b[1] + a[1]) / 2,
        j = function(a) {
            return g * (a - h) + i
        };
        e = d ? h + .1 * c: h - .1 * c;
        var k = j(e);
        return [e, k]
    },
    t = function(a) {
        return function() {
            p && ($(a).css("-webkit-transition", "all ease-out 0.5s").css("-webkit-transform", "translateY(" + (n - 40) + "px)"), setTimeout(function() {
                $(a).css("-webkit-transform", "translateY(0px)"),
                $(".poll-font").css("-webkit-transform", "translateY(-40px)"),
                p = 0
            },
            1e3))
        }
    },
    u = 0,
    v = function(d, o) {
        n = d,
        d -= 60,
        u || ($(o).on("touchend", t(o)), u = 1),
        0 > d && ($(o).css("-webkit-transition", "none"), $(".poll-font").hide(), $(".poll-font").css("-webkit-transform", "translateY(0px)"), e.css("-webkit-transform", "translateY(" + d / 2 + "px)"), d = 0, $(a).show(), $(c).show()),
        d *= 3.3,
        0 > d && (d = 0),
        b.clearRect(0, 0, f, g),
        j = h - .1 * d,
        k = i - .3 * d,
        m[1] = d + l[1],
        r(d, o)
    },
    w = function() {
        if (!$.os.android) {
            $("body").prepend(q),
            e = $(".poll-wrapper"),
            e.show(),
            a = document.getElementById("poll"),
            b = a.getContext("2d"),
            a.width = f,
            a.height = g,
            a.style.width = ~~ (f / 2) + "px",
            a.style.height = ~~ (g / 2) + "px",
            b.fillStyle = "#b1b1b1",
            b.strokeStyle = "rgb(118,113,108)",
            b.shadowColor = "#ccc",
            b.shadowOffsetX = 0,
            b.shadowOffsetY = 2,
            b.shadowBlur = 1,
            b.lineWidth = 1,
            c = document.getElementById("reloadIcon"),
            d = document.getElementById("loadingIcon");
            var h = a.offsetLeft,
            i = a.offsetTop;
            c.style.left = ~~ (h + l[0] / 2 - 7.5) + "px",
            c.style.top = ~~ (i + l[1] / 2 - 9) + "px",
            d.style.left = ~~ (h + l[0] / 2 - 7.5) + "px",
            d.style.top = ~~ (i + l[1] / 2 - 9) + "px"
        }
    },
    x = function(a) {
        o = a
    },
    y = function() {
        $(a).hide(),
        $(c).hide(),
        $(d).hide(),
        $(".poll-font").show()
    };
    return {
        init: w,
        ok: x,
        reset: y,
        step: v
    }
}),
function(a, b) {
    a.Refresh = b()
} (this,
function() {
    function a(a) {
        if (!w && (D = $.os.ios && E[0] === document.body ? window.scrollY: $(a.currentTarget).scrollTop(), !t)) {
            q = +new Date;
            var b = a.touches[0];
            b && (B = b.pageX, C = b.pageY)
        }
    }
    function b(a, b) {
        a = a || B,
        b = b || C;
        var c = Math.abs(a - B),
        d = Math.abs(b - C);
        return {
            horizontal: c > 0 ? (a - B) / c: 0,
            vertical: d > 0 ? (b - C) / d: 0
        }
    }
    function c(a) {
        var c = E.scrollTop();
        if ($.os.ios && E[0] === document.body && (c = window.scrollY), !w && !t && !D && 0 >= c) {
            if (y && $.os.ios) return void pollRefreshUi.step( - c, E);
            F = !1;
            var e = a.touches.length;
            if (1 === e) {
                var f = a.touches[0];
                if (Math.abs(f.pageY - f.pageY) > z.supressionThreshold) a.preventDefault();
                else if (f.pageY > C + z.verticalThreshold - 5) {
                    var g = b(f.pageX, f.pageY);
                    g.vertical > 0 && (F = !0, j(), $.os.android && d())
                }
            }
        }
    }
    function d(a) {
        if (!w && t && !D) {
            var b = this;
            F && (p = !1, x.forEach(function(c) {
                c.call(b, a) && (p = !0)
            }), p || k(), F = !1)
        }
    }
    function e() {
        G++,
        mqq.android && 2 > G || (r = setTimeout(function() {
            var a = mqq.invoke("ui", "pageVisibility");
            mqq.android && null === a && (a = !0),
            o && o(a),
            a && setTimeout(function() {
                var a, b = JSON.parse(window.localStorage.getItem("refreshData") || "{}"),
                c = 0;
                for (a in b) b.hasOwnProperty(a) && (c = 1);
                c && j();
                for (a in b) b.hasOwnProperty(a) && A[a] && (A[a](), delete b[a]);
                b = JSON.stringify(b),
                window.localStorage.setItem("refreshData", b),
                setTimeout(function() {
                    c && k()
                },
                100)
            },
            100)
        },
        100))
    }
    function f() {
        var b = E;
        $.os.ios && b[0] === document.body && mqq.compare("5.3") >= 0 ? (mqq.ui.setPullDown({
            enable: !0
        }), mqq.addEventListener("qbrowserPullDown",
        function() {
            x.forEach(function(a) {
                a.call(b[0] || document, event) && (p = !0)
            }),
            mqq.ui.setPullDown({
                success: !0,
                text: "刷新成功"
            })
        })) : (b.on("touchstart", a), b.on("touchmove", c), b.on("touchend", d), document.addEventListener("qbrowserVisibilityChange", e))
    }
    function g(a, b) {
        A[a] = b
    }
    function h() {
        var b = E;
        b.off("touchstart", a),
        b.off("touchmove", c),
        b.off("touchend", d)
    }
    function i() {
        u = $('<div class="refresh-loading"></div>'),
        $(document.body).append(u)
    }
    function j() {
        t || (t = !0, u && u.show())
    }
    function k() {
        t = !1,
        u && u.hide()
    }
    function l(a) {
        E = $(a)
    }
    function m(a) {
        a = a || {},
        s || (s = !0, i(), a.dom && l(a.dom), f()),
        a.reload && x.push(a.reload),
        o = a.onPageVisiblityChange,
        y = a.usingPollRefresh || 0,
        y && (pollRefreshUi.init(), pollRefreshUi.ok(function() {
            F = 1,
            t = 1
        }))
    }
    function n() {
        h(),
        u && (u.remove(), u = null),
        D = B = C = 0,
        F = t = s = !1
    }
    var o, p, q, r, s = !1,
    t = !1,
    u = null,
    v = $(document),
    w = 0,
    x = [],
    y = 0,
    z = {
        supressionThreshold: 10,
        horizontalThreshold: 10,
        verticalThreshold: 30
    },
    A = {},
    B = 0,
    C = 0,
    D = 0,
    E = $("body"),
    F = !1,
    G = 0,
    H = function(a, b) {
        var c = JSON.parse(window.localStorage.getItem("refreshData") || "{}");
        c[b] = "needFresh",
        c = JSON.stringify(c);
        try {
            window.localStorage.setItem("refreshData", c)
        } catch(d) {
            window.localStorage.clear(),
            window.localStorage.setItem("refreshData", c)
        }
    };
    return v.on("refreshPage", H),
    {
        init: m,
        destroy: n,
        show: j,
        hide: k,
        freeze: function() {
            w = 1
        },
        melt: function() {
            w = 0
        },
        listen: function(a) {
            return a.del ? x.splice(x.indexOf(a.reload)) : a.reload && x.push(a.reload)
        },
        change$scrolLdom: l,
        register: g,
        pauseTouchMove: function() {
            h()
        },
        restoreTouchMove: function() {
            h(),
            f()
        }
    }
}),
function(a, b) {
    "function" == typeof define && define.amd ? define([], b) : a.numHelper = b()
} (this,
function() {
    return function(a) {
        if ("number" != typeof a) return a;
        a = +a;
        var b, c = "",
        d = 1e4,
        e = 1e8;
        return d > a ? c = "" + a: a >= d && e > a ? (b = ("" + (a / d).toFixed(1)).split("."), c = ("0" !== b[1] ? b.join(".") : b[0]) + "万") : a >= e && (b = ("" + (a / e).toFixed(2)).split("."), c = ("0" !== b[1] ? b.join(".") : b[0]) + "亿"),
        c
    }
}),
function() {
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
                return a = this.isIOS ? "IPH": this.isAndroid ? "AND": "PC",
                a + "_MQ_BULUO"
            }
        },
        getNetwork: function(b) { ($.os.ios || $.os.android) && mqq.compare("4.6") >= 0 ? a.network ? b(a.network) : mqq.device.getNetworkType(function(c) {
                var d = {
                    1 : "wifi",
                    2 : "2g",
                    3 : "3g",
                    4 : "unknow"
                };
                a.network = d[c] || "unknow",
                b(a.network)
            }) : b("unknow")
        }
    },
    a.init(),
    b.mix = function(a, b, c, d) {
        if (!b || !a) return a;
        void 0 === c && (c = !0);
        var e, f = {};
        if (d && (e = d.length)) for (var g = 0; e > g; g++) f[d[g]] = !0;
        for (var h in b)(!c || h in f) && h in a || (a[h] = b[h]);
        return a
    },
    b.getTimestamp = function() {
        return + new Date
    },
    b.template = function(a, b) {
        return a.replace(/\$\{(\w+)\}/g,
        function(a, c) {
            return b[c] ? b[c] : ""
        })
    },
    b.on = function(a, b, c) {
        a.attachEvent ? a.attachEvent("on" + b, c) : a.addEventListener(b, c, !1)
    };
    var d = [],
    e = !1;
    b.report = function(a, c) {
        var e, f = d.length;
        if (e = 0, c = c || 2e3, d[0]) {
            for (var g = 0; f > g; g++) {
                var h = d.shift();
                h && (h.isTicking = !1, h(function() {++e === f && a && !a.isCalled && (a.isCalled = !0, a())
                }))
            }
            a && setTimeout(function() {
                a.isCalled || (a.isCalled = !0, a())
            },
            c)
        } else a && a();
        b.tick.isTicking = !1
    },
    b.report.delay = 500,
    b.tick = function(a) {
        a.isTicking || (a.isTicking = !0, d.push(a)),
        b.tick.isTicking || (setTimeout(b.report, b.report.delay), b.tick.isTicking = !0),
        e || (b.on(window, "beforeunload",
        function() {
            b.report()
        }), e = !0)
    };
    var f = "__tc_global_image_",
    g = b.getTimestamp();
    b.send = function(a, b) {
        g += 1,
        a += "&ts=" + g;
        var d = f + g;
        c[d] = new Image,
        b && (c[d].onload = c[d].onerror = function() {
            b();
            try {
                delete c[d]
            } catch(a) {
                c[d] = null
            }
        }),
        c[d].src = a
    }
} (),
function() {
    function a(a, b, c) {
        if ("number" == typeof a) c(a);
        else if ("allWithCache" === a) EnvInfo.getNetwork(function(a) {
            var d = "unknow" === a ? "wifi": a,
            e = EnvInfo.isOffline,
            f = j[d][e ? "offline": "online"][b ? "cache": "nocache"];
            c(f)
        });
        else if ("all" === a) EnvInfo.getNetwork(function(a) {
            var b = "unknow" === a ? "wifi": a,
            d = EnvInfo.isOffline,
            e = l[b][d ? "offline": "online"];
            c(e)
        });
        else if ("network" === a) EnvInfo.getNetwork(function(a) {
            var b = "unknow" === a ? "wifi": a,
            d = k[b];
            c(d)
        });
        else {
            var d = EnvInfo.isOffline,
            e = k[d ? "offline": "online"];
            c(e)
        }
    }
    function b(b, c, e, f, g, h, j, k) {
        var l, m, n, o = f[0];
        m = 1,
        n = f.length;
        var p = {
            flag1: b,
            flag2: c,
            flag3: e
        },
        q = function(a) {
            var f = [b, c, e].join("-");
            i[f] = Q.mix(i[f] || {},
            a),
            h ? d() : Q.tick(d)
        };
        if ("undefined" == typeof g || null === g) {
            for (; n > m; m++) l = f[m],
            l = l ? l - o: 0,
            l > 0 && (p[m] = l);
            q(p)
        } else j ? a(j, k,
        function(a) {
            p[parseInt(g + a)] = f,
            q(p)
        }) : (p[g] = f, q(p))
    }
    function c(a, b, c, d, e) {
        var f, g = {
            appid: 10012
        },
        i = {
            flag1: a,
            flag2: b,
            flag3: c
        };
        i[e] = d;
        var j = mqq.toQuery(i);
        g.speedparams = j,
        f = h + mqq.toQuery(g),
        Q.send(f)
    }
    function d() {
        var a, b, c;
        for (a in i) i.hasOwnProperty(a) && (b = i[a], c = g + mqq.toQuery(b), Q.send(c));
        i = {}
    }
    function e(a, b, c) {
        return this instanceof e ? (this.f1 = a, this.f2 = b, this.f3 = c, void(this.timing = [])) : new e(a, b, c)
    }
    function f(c, d, e) {
        var f, g = window.webkitPerformance ? window.webkitPerformance: window.msPerformance,
        h = ["navigationStart", "fetchStart", "connectEnd", "domComplete", "loadEventEnd"],
        i = e;
        if (g = g ? g: window.performance, g && (f = g.timing)) {
            for (var j = [], k = 0, l = h.length; l > k; k++) j[k] = f[h[k]];
            a("all", null,
            function(a) {
                for (var e = [j[0]], f = 0; 4 > f; f++) {
                    for (var g = [], h = 0; 6 > h; h++) g.push(h === a ? j[f + 1] : 0);
                    e = e.concat(g)
                }
                b(c, d, i, e)
            })
        }
    }
    var g, h, i, j, k;
    g = "http://isdspeed.qq.com/cgi-bin/r.cgi?",
    h = "http://report.huatuo.qq.com/report.cgi?",
    i = {},
    j = {
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
    var l = {
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
    k = {
        "2g": 0,
        "3g": 1,
        wifi: 2
    },
    e.prototype.mark = function(a) {
        return this.timing.push(a || Q.getTimestamp())
    },
    e.prototype.report = function() {
        b(this.f1, this.f2, this.f3, this.timing)
    },
    Q.mix(Q, {
        huatuo: c,
        isd: b,
        performance: f,
        speed: e
    })
} (),
function() {
    function a(a) {
        if (d[0]) {
            var b = "[" + d + "]",
            e = c + "monitors=" + b + "&t=" + Q.getTimestamp();
            d = [],
            Q.send(e, a)
        } else a && a()
    }
    function b(b, e, f) {
        if (e) {
            var g = c + "monitors=[" + b + "]&t=" + Q.getTimestamp();
            Q.send(g, f)
        } else d.push(b),
        Q.tick(a)
    }
    var c = "http://cgi.connect.qq.com/report/report_vm?",
    d = [];
    Q.mix(Q, {
        monitor: b
    });
    var e = {};
    Q.setMonitorMap = function(a) {
        e = a
    },
    Q.monitorMap = function(a) {
        e[a] && Q.monitor(e[a])
    }
} (),
function() {
    var a = "http://buluo.qq.com/cgi-bin/bar/tdw/report?",
    b = Object.prototype.toString,
    c = {},
    d = [],
    e = [],
    f = "",
    g = function(b, c, d, e, f) {
        if (b && c && d) {
            var g = {
                table: b,
                fields: JSON.stringify(c),
                datas: JSON.stringify(d),
                pr_ip: e || "obj3",
                pr_t: f || "ts",
                t: +new Date
            };
            Q.send(a + mqq.toQuery(g))
        }
    },
    h = function(a, h, j) {
        if (!a) throw "params can not be null";
        a.uin = Login.getUin(),
        a.ver2 = Util.queryString("activity_from") || Util.queryString("from") || Util.getHash("from") || "other";
        var k = "";
        if (k = $.os.ios ? "ios": $.os.android ? "android": "other", mqq.QQVersion) {
            var l = mqq.QQVersion.split(".");
            l.length > 3 && l.pop(),
            l = l.join("."),
            k += "-" + l
        }
        EnvInfo.network && (k += "-" + EnvInfo.network),
        k && (a.obj2 = k),
        f = j || f || "dc00141";
        var m, n, o;
        switch (b.call(a)) {
        case "[object Array]":
            m = a;
            break;
        case "[object Object]":
            if (h) {
                var p = [],
                q = [];
                for (n in a) if (a.hasOwnProperty(n)) {
                    if ("obj3" === n || "ts" === n) continue;
                    p.push(n),
                    q.push(a[n])
                }
                return g(f, p, [q], a.obj3, a.ts)
            }
            m = [a]
        }
        for (n = 0, o = m.length; o > n; n++) {
            var r = m[n],
            s = [];
            e.push(s);
            for (var t in r) if (r.hasOwnProperty(t)) {
                var u;
                t in c ? (u = c[t], d[u] = t) : (d.push(t), c[t] = u = d.length - 1),
                s[u] = r[t] || ""
            }
        }
        h ? i() : Q.tick(i)
    },
    i = function() {
        var a = e.length;
        if (a > 1) for (var b = 0; a > b; b++) for (var h = 0,
        i = d.length; i > h; h++) e[b][h] || 0 === e[b][h] || (e[b][h] = "");
        g(f, d, e),
        d.length = e.length = 0,
        c = {},
        f = ""
    };
    Q.mix(Q, {
        tdw: h
    })
} (),
function() {
    var a = "http://wspeed.qq.com/w.cgi?",
    b = 1000212,
    c = [],
    d = navigator.userAgent.indexOf("MQQBrowser") > -1 ? "_X5": "",
    e = function(a, b, d, e) {
        a = a.replace("http://buluo.qq.com/cgi-bin", ""),
        c.push([a, b, d]),
        e ? f(b) : Q.tick(function() {
            f(b)
        })
    },
    f = function(e) {
        var f = {},
        g = EnvInfo.getVersion();
        if (0 === e) {
            f.frequency = 20;
            var h = Math.floor(100 * Math.random() + 1);
            if (h > 5) return
        } else f.frequency = 1;
        f.appid = b,
        f.key = ["commandid", "resultcode", "tmcost"].join(","),
        c.forEach(function(a, b) {
            f[b + 1 + "_1"] = a[0],
            f[b + 1 + "_2"] = a[1],
            f[b + 1 + "_3"] = a[2]
        }),
        "PC_MQ_BULUO" === g && (mqq.QQVersion = ""),
        f.releaseversion = g + "_" + mqq.QQVersion + d,
        f.touin = EnvInfo.uin || null,
        f.t = Q.getTimestamp(),
        c = [],
        Q.send(a + mqq.toQuery(f))
    };
    Q.mix(Q, {
        mmReport: e
    })
} (),
function() { !
    function() {
        for (var a = 0,
        b = ["ms", "moz", "webkit", "o"], c = 0; c < b.length && !window.requestAnimationFrame; ++c) window.requestAnimationFrame = window[b[c] + "RequestAnimationFrame"],
        window.cancelAnimationFrame = window[b[c] + "CancelAnimationFrame"] || window[b[c] + "CancelRequestAnimationFrame"];
        window.requestAnimationFrame || (window.requestAnimationFrame = function(b) {
            var c = (new Date).getTime(),
            d = Math.max(0, 16 - (c - a)),
            e = window.setTimeout(function() {
                b(c + d)
            },
            d);
            return a = c + d,
            e
        }),
        window.cancelAnimationFrame || (window.cancelAnimationFrame = function(a) {
            clearTimeout(a)
        })
    } ()
} (),
function(a, b) {
    "function" == typeof define && define.amd ? define(["imgHandle"], b) : a.imgHandle = b(a.imgHandle)
} (this,
function(a) {
    a = a || {};
    var b, c, d, e, f, g, h, i, j, k, l;
    b = function(a, b, c) {
        b.removeAttribute(c),
        g(a, b)
    },
    c = "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/hot-error.png",
    d = function(a, b, d) {
        c && d !== c && b.parentNode && (b.src = c, b.className += " error", b.style.marginTop = "0px", b.parentNode.className += " err-img"),
        Badjs("image error: " + d, location.href, 0, 387647, 2, 504991),
        console.error("image error: " + d)
    },
    e = function(a, b, c, d) {
        if (!a) return {
            url: "img/error.png",
            w: 0,
            h: 0,
            t: "",
            marginTop: 0
        };
        b = b || 328,
        c = c || 228,
        d = d || 50;
        var e = a.w,
        f = a.h;
        if (b > e) return d > f && (a.marginTop = (d - f) / 2 + "px"),
        a;
        var g = f / e * b,
        h = 0;
        return g > c && (h = (c - g) / 2 + "px"),
        {
            url: a.url,
            w: b,
            h: g,
            t: a.t,
            marginTop: h
        }
    },
    f = function(a, b, c, d, e) {
        if (a && a.length) {
            var f;
            if (1 !== a.length || b) for (var g = 0,
            h = a.length; h > g; g++) f = a[g],
            c = e ? c || 77 : Math.min(f.w || c, c) || 77,
            d = e ? d || 77 : Math.min(f.h || d, d) || 77,
            f.boxWidth = f.width = c,
            f.boxHeight = f.height = d,
            f.marginTop = 0,
            f.marginLeft = 0,
            f.w && f.h && (f.h * c / f.w > d ? (f.height = f.h * c / f.w, f.marginTop = .382 * (f.boxHeight - f.height)) : (f.width = f.w * d / f.h, f.marginLeft = (f.boxWidth - f.width) / 2));
            else f = a[0],
            c = Math.min(f.w || c, c) || 150,
            d = Math.min(f.h || d, d) || 225,
            f.boxWidth = f.width = c,
            f.boxHeight = f.height = d,
            f.marginTop = 0,
            f.marginLeft = 0,
            f.w && f.h && (f.h * c / f.w > d ? (f.width = f.w * d / f.h, f.boxWidth = f.width) : (f.height = f.h * c / f.w, f.boxHeight = f.height))
        }
        return a
    },
    g = function(a, b) {
        if (b.getAttribute("noSize") && "" !== b.getAttribute("noSize")) {
            for (var c = b.getAttribute("noSize").split("&"), d = {},
            e = 0; e < c.length; e++) d[c[e].split(":")[0]] = c[e].split(":")[1];
            try { (a.target.width < 50 || a.target.height < 50) && window.location.pathname.indexOf("/index.html") > -1 && b.parentNode && $(b).parent().hasClass("feed-img") && (b.parentNode.style.display = "none", $(b).parents(".img-wrap").find(".total-img").hide())
            } catch(g) {}
            var h = d.w,
            i = [{
                w: a.target.width,
                h: a.target.height,
                url: a.target.src
            }],
            j = d.h;
            i = f(i, !0, h, j, !0),
            b.style.cssText = "width:" + i[0].width + "px; height:" + i[0].height + "px;margin-left:" + i[0].marginLeft + "px; margin-top:" + i[0].marginTop + "px;"
        }
        "true" === b.getAttribute("needHideBg") && b.parentNode && (b.parentNode.className += " hide-bg"),
        "true" === b.getAttribute("hidebg") && b.parentNode && (b.parentNode.style.background = "none")
    };
    var m = function(a, b) {
        a = a || "";
        var c = a;
        "object" == typeof c && (c.t && "gif" === c.t && (a += "0"), a = c.url),
        a.indexOf("mmbiz.qpic.cn") > -1 && $.os.ios && (a = a.replace("tp=webp", ""));
        var d = a.split("/");
        return ! d[d.length - 1] && "0" !== d[d.length - 1] || -1 !== a.indexOf("ugc.qpic.cn/gbar_pic") ? (a.indexOf("p.qlogo.cn") > -1 ? a += "0": a.indexOf("p.qpic.cn") > -1 || a.indexOf("ugc.qpic.cn/qqac/") > -1 ? a += "160": a.indexOf("ugc.qpic.cn/gbar_pic") > -1 && (a = a.replace(/\/(\d+)$/, "/"), a += b || "200"), a) : (a.indexOf("ugc.qpic.cn/qqac/") > -1 && (d[d.length - 1] = "160", a = d.join("/")), a)
    };
    return k = document.documentElement.clientHeight,
    $(document).on("touchmove",
    function() {
        l = document.body.scrollTop;
        for (var a, b, c = 0; c < i.length && (a = i[c], b = a.getBoundingClientRect().top, 1.5 * k > b - l); c++) j(a),
        i.splice(c, 1)
    }),
    i = [],
    j = function(a, c) {
        c = c || "lazy-src";
        var e = a.getAttribute(c);
        if (e) {
            a.setAttribute("page-lazyload-time", Date.now());
            var f = [951766002, 1310720359, 1591771530, 1873705937, 2024781614, 2062695960, 2359834172, 2410808470, 2716387061, 2794353141, 2795169601, 2795341204, 2842800574, 2892764449, 2894406678, 2915266032, 2923136367, 2978718130, 2982660671, 2995344389];
            a.onerror = function(b) {
                var c = a.getAttribute("data-festival-uin"),
                g = c % 20;
                if (c) {
                    var h = $(a).addClass("offline-pic avatar-" + f[g]);
                    h.removeAttr("src")
                } else d(b, a, e)
            },
            a.onload = function(d) {
                b(d, a, c)
            },
            a.src = e
        }
    },
    h = function(a) {
        if (a) {
            var b;
            if (l = document.body.scrollTop, "IMG" === a.tagName) return b = a.getBoundingClientRect().top,
            void(l + k > b ? j(a) : i.push(a));
            for (var c = a.getElementsByTagName("img"), d = 0, e = c.length; e > d; d++) a = c[d],
            b = a.getBoundingClientRect().top,
            l + k > b ? j(a) : i.push(a)
        }
    },
    a.setErrImg = function(a) {
        c = a
    },
    a.lazy = h,
    a.format = e,
    a.formatThumb = f,
    a.getThumbUrl = m,
    a.dealWithFeedImage = g,
    a
}),
function(a, b) {
    "function" == typeof define && define.amd ? define([],
    function() {
        return a.returnExportsGlobal = b()
    }) : "object" == typeof exports ? module.exports = b() : a.bouncefix = b()
} (this,
function() {
    var a;
    a = function() {
        function a(a, b, c, d) {
            this.el = a,
            this.eventName = b,
            this.handler = c,
            this.context = d,
            this.add()
        }
        return a.prototype._handler = function(a, b) {
            var c = {};
            for (var d in a) c[d] = a[d];
            c.stopPropagation = function() {
                a.stopPropagation ? a.stopPropagation() : a.cancelBubble = !0
            },
            c.preventDefault = function() {
                a.preventDefault ? a.preventDefault() : a.returnValue = !1
            },
            this.handler.call(this.context || b, c)
        },
        a.prototype.add = function() {
            var a = this;
            a.cachedHandler = function(b) {
                a._handler.call(a, b, this)
            },
            a.el.addEventListener(a.eventName, a.cachedHandler, !1)
        },
        a.prototype.remove = function() {
            this.el.removeEventListener(this.eventName, this.cachedHandler)
        },
        a
    } ();
    var b;
    b = function() {
        var a = {};
        return a.getTargetedEl = function(a, b) {
            for (;;) {
                if (a.classList.contains(b)) break;
                if (! (a = a.parentElement)) break
            }
            return a
        },
        a.isScrollable = function(a) {
            return a.scrollHeight > a.offsetHeight
        },
        a.scrollToEnd = function(a, b) {
            var c = a.scrollTop,
            d = a.offsetHeight,
            e = a.scrollHeight;
            0 >= c && !b && (a.scrollTop = 1),
            c + d >= e && (a.scrollTop = e - d - 1)
        },
        a
    } ();
    var c;
    c = function(a, b) {
        var c = function(b, d) {
            if (! (this instanceof c)) return new c(b, d);
            if (!b) throw new Error('"className" argument is required');
            this.className = b,
            this.flag = d,
            this.startListener = new a(document, "touchstart", this.touchStart, this),
            this.endListener = new a(document, "touchend", this.touchEnd, this)
        };
        return c.prototype.touchStart = function(c) {
            var d = b.getTargetedEl(c.target, this.className);
            return d && b.isScrollable(d) ? b.scrollToEnd(d, this.flag) : void(d && !this.moveListener && (this.moveListener = new a(d, "touchmove", this.touchMove, this)))
        },
        c.prototype.touchMove = function(a) {
            a.preventDefault()
        },
        c.prototype.touchEnd = function() {
            this.moveListener && (this.moveListener.remove(), delete this.moveListener)
        },
        c.prototype.remove = function() {
            this.startListener.remove(),
            this.endListener.remove()
        },
        c
    } (a, b);
    var d;
    return d = function(a) {
        var b = {
            cache: {}
        };
        return b.add = function(b, c) {
            this.cache[b] || (this.cache[b] = new a(b, c))
        },
        b.remove = function(a) {
            this.cache[a] && (this.cache[a].remove(), delete this.cache[a])
        },
        b
    } (c)
}),
function() {
    $(document).on("tap", ".user_avatar,.user-avatar,.user-nick",
    function() {
        var a = $(this),
        b = a.data("profile-uin");
        if (b) {
            if ((b + "").match(/\*/)) return;
            var c = /([^/] + )\.html ? \ ? /.exec(window.location.href);c=c?c[1]:"other",Util.openUrl("http:/ / xiaoqu.qq.com / mobile / personal.html#uin = "+b+" & scene = "+c,1)}}).on("tap ",".openGroup ",function(){var a=$(this).data("groupcode "),b={uinType:1,uin:a};$(this).hasClass("booked - tribes ")&&(Q.tdw({opername:"Grp_tribe ",module:"post_detail ",action:"Clk_grpsign ",obj1:a,uin:Login.getUin()}),b.wSourceSubID=29),mqq&&mqq.ui.showProfile(b)}),Q.monitor(Config.isOffline?419380:419379),$.os.android&&parseFloat($.os.version)<4&&Q.monitor(459356),mqq.QQVersion>0&&mqq.compare("4.7.2 ")<1&&Q.monitor(617537),$.os.ios?$("html,
            body ").addClass("ios "):$.os.android&&$("html, body ").addClass("android "),setTimeout(function(){mqq.offline.isCached({bid:128},function(a){if(-1!==a){Config.offlineVersion=a;var b,c={opername:"Grp_tribe ",module:"tech_data ",action:"offline_data ",ver1:a};b=$.os.ios?"ios ":$.os.android?"android ":"other ",c.ver3=b,"other "!==b&&(c.ver4=$.os.version),Q.tdw(c)}}),($.os.ios||$.os.android)&&mqq.compare("4.6 ")>=0&&mqq.data.getPageLoadStamp(function(a){var b;if(0===a.ret){var c={ios_520:2,ios_521:3,ios_530:4,ios_532:5,and_520:6,and_521:7,and_530:8,and_531:9,and_532:10,ios_540:11,and_541:12,ios_551:13,and_551:14},d=mqq.QQVersion.split(".");Q.isd(7832,47,3,a.startLoadUrlTime-a.onCreateTime,1),b=($.os.ios?"ios_ ":"and_ ")+d[0]+d[1]+d[2],c[b]&&Q.isd(7832,47,3,a.startLoadUrlTime-a.onCreateTime,c[b])}})},1e3),mqq.ui.setLoading({visible:!1}),$.os.ios&&mqq.ui.setRightDragToGoBackParams({enable:!0,width:window.innerWidth});var a=navigator.userAgent.match(/\/qqdownloader\/(\d+)?/);a&&YybJsBridge._call("setWebView ",{toolbar:0}),mqq.compare("5.8 ")>-1&&mqq.invoke("ui ","disableLongPress ",{enable:!0})}(),function(a){function b(a,b){return a.replace(b?/&/g:/&(?!#?\w+;)/g," & amp;
            ").replace(/</g," & lt;
            ").replace(/>/g," & gt;
            ").replace(/" / g, "&quot;").replace(/'/g, "&#39;")
        }
        function c(a) {
            return a.replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&quot;/g, '"').replace(/&#39;/g, "'")
        }
        function d(a, d, e) {
            a = c(a);
            var f = '<a href="javascript:void(0);" rel="openURL" url="' + a + '"',
            g = null;
            if (d) for (var h = 0; h < d.length; h++) {
                var i = d[h];
                if (!i.flag && c(i.url) === a) {
                    g = i,
                    i.flag = 1;
                    break
                }
            }
            if (f += e ? ' class="link disabled"': ' class="link"', f += ">", g && g.type) {
                var j = b(g.content) || "网页链接";
                if (j = j.replace(/&nbsp;/g, ""), j.length > 20 && (j = j.substring(0, 20) + "..."), "keyword" === g.type) if (e) f = j;
                else {
                    var k = g.bid || "";
                    f = '<a href="javascript:void(0);" class="link link-keyword" rel="openURL" url="' + a + '" data-bid="' + k + '" >' + j + "</a>"
                } else f += '<span class="post-link link-' + g.type + '"></span>' + j + "</a>"
            } else f += '<span class="post-link link-normal"></span>网页链接</a>';
            return f
        }
        function e(a) {
            var b = [];
            for (var c in a) b.push(a[c]);
            return b
        }
        function f(a) {
            return "function" == typeof Array.isArray ? Array.isArray(a) : "[object Array]" === Object.prototype.toString.call(a)
        }
        function g(a, b, c) {
            if ("string" != typeof a) return "";
            for (var d = c ? "disabled": "", e = b.length, f = 0; e > f; f++) {
                var g = b[f].content,
                h = b[f].bid,
                i = new RegExp(g, "gi");
                a = a.replace(i,
                function() {
                    return '<a href="javascript:void(0);" class="link link-keyword ' + d + '" rel="openURL" url="http://xiaoqu.qq.com/mobile/barindex.html?_wv=1027&bid=' + h + '" >' + g + "</a>"
                })
            }
            return a
        }
        var h = /&lt;img.*?(?:&gt;|\/&gt;)/gi,
        i = /src=[\'\"]?([^\'\"]*)[\'\"]?/i,
        j = Config.FACE_2_TEXT,
        k = /\{\{(https?)\:\/\/(.*?)\}\}/gi,
        l = /\{\{\s*(\d+)\s*:\s*(\d)\s*\}\}/gi,
        m = /\{\{(tencentvideo?)\:\/\/(.*?)\}\}/gi,
        n = /(\[([^\[\]]+)\])/gi,
        o = new RegExp("\\/" + Config.FACE_2_TEXT.join("|\\/"), "g"), p = "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/face/$index$.png", q = 26;a.replaceFaceCode = function(a) {
            return "string" != typeof a ? "": a.replace(n,
            function(a, b, c) {
                var d = j.indexOf(c);
                return j.indexOf(c) > -1 ? '<img class="face" width="' + q + '" height="' + q + '" alt="' + c + '" src="' + p.replace("$index$", d) + '" >': b
            }).replace(o,
            function(a) {
                var b = a.substring(1),
                c = j.indexOf(b);
                return c > -1 ? (140 == c && (c = 122), 141 == c && (c = 124), (142 == c || 143 == c) && (c = 33), '<img class="face" width="' + q + '" height="' + q + '" alt="' + b + '" src="' + p.replace("$index$", c) + '" >') : a
            })
        },
        a.plain2rich = function(a, h, i, m) {
            var r = [];
            if ("object" == typeof a && a && "number" != typeof a.length) {
                var s = a;
                a = s.hasOwnProperty("text") ? s.text: "",
                h = s.hasOwnProperty("search") ? s.search: "",
                i = s.hasOwnProperty("urlInfos") ? s.urlInfos: [],
                r = s.hasOwnProperty("keyInfos") ? s.keyInfos: [],
                m = s.hasOwnProperty("onlyText") ? s.onlyText: ""
            }
            return "string" != typeof a ? "": (f(i) || (i = e(i)), a = (h ? a: b(a)).replace(/\n/g, "<br>").replace(/(<br>){2,}/gi, "<br>").replace(k,
            function(a, b, e) {
                var f = b + "://" + e;
                return d(c(f), i, m)
            }).replace(l,
            function(a, b, c) {
                if (c = Number(c)) {
                    if (!Config.checkOpen) {
                        if (1 != c && 3 != c) return b;
                        c = 1
                    }
                    return '<a href="javascript:void(0);" rel="showProfile" type="' + c + '" code="' + b + '" >' + b + "</a>"
                }
                return b
            }).replace(n,
            function(a, b, c) {
                var d = j.indexOf(c);
                return j.indexOf(c) > -1 ? '<img class="face" width="' + q + '" height="' + q + '" alt="' + c + '" src="' + p.replace("$index$", d) + '" >': b
            }).replace(o,
            function(a) {
                var b = a.substring(1),
                c = j.indexOf(b);
                return c > -1 ? (140 == c && (c = 122), 141 == c && (c = 124), (142 == c || 143 == c) && (c = 33), '<img class="face" width="' + q + '" height="' + q + '" alt="' + b + '" src="' + p.replace("$index$", c) + '" >') : a
            }), g(a, r, m))
        },
        a.rich2plain = function(a, b) {
            if (a = a || "", "object" == typeof a && "number" != typeof a.length) {
                var g = a;
                a = g.hasOwnProperty("text") ? g.text: "",
                b = g.hasOwnProperty("urlInfos") ? g.urlInfos: []
            }
            return "string" != typeof a ? "": (f(b) || (b = e(b)), a.replace(/(\n){3,}/gi, "\n").replace(/\n/g, " ").replace(k,
            function(a, e, f) {
                var g = e + "://" + f;
                return d(c(g), b, !0)
            }).replace(l,
            function(a, b) {
                return b
            }).replace(o, "").replace(n, "").replace(m, ""))
        },
        a.pureText = function(a) {
            return "string" != typeof a ? "": a.replace(/(\n){3,}/gi, "\n").replace(/\n/g, " ").replace(/(<br>)/gi, " ").replace(l,
            function(a, b) {
                return b
            })
        },
        a.filterImgTag = function(a) {
            return a = a || "",
            a.replace(/<img[^>]*>/g, "")
        },
        a.getRSSContent = function(a) {
            for (var b = a.match(h) ? a.match(h) : [], c = [], d = ["p", "b"].join("|"), e = new RegExp("&lt;(" + d + ")&gt;|&lt;\\/(" + d + ")&gt;", "ig"), f = 0; f < b.length; f++) c.push(b[f].match(i)[1]);
            for (var g = 0; g < c.length; g++) a = a.replace(/&lt;img.*?(?:&gt;|\/&gt;)/, "<image>src:" + c[g] + "<image>");
            return a.replace(e, "").replace(/(\n|\r){2,}/g, "\n").split("<image>")
        }
    } (this),
    function(a, b) {
        a.ScrollHandle = b(a.$)
    } (this,
    function() {
        var a, b = 300,
        c = 0,
        d = {
            init: function(a) {
                var b = $(a.container);
                this.container = b,
                this.scollEndCallback = a.scollEndCallback,
                this.scrollToBottomCallback = a.scrollToBottomCallback,
                this.scrollToHalfCallback = a.scrollToHalfCallback,
                this.bindHandler()
            },
            bindHandler: function() {
                this.container.on("scroll", $.proxy(this.onScroll, this))
            },
            onScroll: function(d) {
                var e, f, g, h = this,
                i = d.target;
                if (i === document) e = window.scrollY,
                g = window.innerHeight,
                f = document.body.scrollHeight;
                else {
                    var j = window.getComputedStyle(i);
                    e = i.scrollTop,
                    g = parseInt(j.height) + parseInt(j.paddingTop) + parseInt(j.paddingBottom) + parseInt(j.marginTop) + parseInt(j.marginBottom),
                    f = i.scrollHeight
                }
                e + g >= 2 * f / 3 && e > c ? this.scrollToHalfCallback && this.scrollToHalfCallback(d) : e + g >= f && this.scrollToBottomCallback && this.scrollToBottomCallback(d),
                c = e,
                clearTimeout(a),
                a = setTimeout(function() {
                    h.scollEndCallback && h.scollEndCallback(d)
                },
                b)
            }
        };
        return d
    }),
    function() {
        var a;
        sodaRender.addEventListener("nullvalue",
        function(b) {
            "nullattr" === b.type && a && a(b.data)
        });
        var b = {},
        c = function(a, b) {
            var c = {};
            for (var d in b)"from" !== d && "sid" !== d && "bkn" !== d && (c[d] = b[d]);
            var e = a + "_" + JSON.stringify(c);
            return e
        },
        d = function() {},
        e = {},
        f = {
            bindEvent: function(a, b, c) {
                var d = a;
                $.os.ios && $(d)[0] == document.body && (d = window),
                $(d).on("scroll",
                function() {
                    var a, b = 0;
                    return function(d) {
                        function e() {
                            var a, e, f, g = d.target,
                            h = 300;
                            if (g == document) a = window.scrollY,
                            f = window.innerHeight,
                            e = document.body.scrollHeight;
                            else {
                                var i = window.getComputedStyle(g);
                                a = g.scrollTop,
                                f = parseInt(i.height) + parseInt(i.paddingTop) + parseInt(i.paddingBottom) + parseInt(i.marginTop) + parseInt(i.marginBottom),
                                e = g.scrollHeight
                            }
                            a + f + h >= e && c && c(d),
                            b = a
                        }
                        window.clearTimeout(a),
                        a = window.setTimeout(e, 200)
                    }
                } ())
            },
            removeModel: function(a) {
                var b = a._scrollEl;
                if (b) {
                    var c = b;
                    "string" == typeof b && (c = $(b));
                    var d;
                    if (d = c == window ? "__window__": c.attr("id"), c && c.length && d) {
                        for (var f = 0; f < e[d].length && e[d] !== a; f++);
                        e[d].splice(f, 1)
                    }
                }
            },
            addModel: function(a) {
                var b = a._scrollEl,
                c = b;
                "string" == typeof b && (c = $(b));
                var d;
                c == window ? d = "__window__": (d = c.attr("id"), d || (d = "d_" + ~~ (1e5 * Math.random()), c.attr("id", d))),
                e[d] ? e[d].push(a) : (e[d] = [a], this.bindEvent(c, d,
                function() {
                    e[d].map(function(a) {
                        a.canScrollInMTB && ("scrollModel" === a.type ? !a.freezed && a.scrollEnable && a.rock() : a.freezed || "scrollModel" !== a.currModel.type || a.currModel.freezed || !a.currModel.scrollEnable || a.currModel.rock())
                    })
                }))
            }
        },
        g = {},
        h = {},
        i = function(a) {
            var b = function() {};
            if (b.renderTmpl = a.renderTmpl, b.cgiName = a.cgiName, b.renderContainer = a.renderContainer, b.param = a.param, b.beforeRequest = a.beforeRequest ||
            function() {},
            b.beforeRender = a.beforeRender ||
            function() {},
            b.processData = a.processData, b.data = a.data, b.renderTool = a.renderTool, b.onreset = a.onreset, b.events = a.events, b.complete = a.complete, b.myData = a.myData, b.error = a.error, b.noRefresh = a.noRefresh || 0, b.scrollEl = a.scrollEl, b.noCache = a.noCache, b.prefetch = a.prefetch, b.effect = a.effect, b.comment = a.comment, b.cacheKey = a.cacheKey || c, b.method = a.method, b.ssoCmd = a.ssoCmd, b.remember = a.remember, b.paramCache = [], b.cgiCount = 0, b.dataCache = [], b.feedPool = [], b.isFirstRender = 1, b.eventsBinded = 0, b.dead = 0, b.isFirstDataRequestRender = 0, b.usePreLoad = a.usePreLoad, b.__proto__ = i.prototype, b.prototype = b, "function" != typeof b.cacheKey && (b.cacheKey = function() {
                return a.cacheKey || ""
            }), a.renderContainer && (g[a.renderContainer] ? g[a.renderContainer]++:g[a.renderContainer] = 1), b.prefetch && !this._passedFirstRender) try {
                var d = "object" == typeof b.param && b.param || b.param.call(b);
                b.paramCache[0] = d;
                var e = {
                    url: b.cgiName,
                    param: d,
                    type: b.method || "POST",
                    ssoCmd: b.ssoCmd,
                    succ: function(a) {
                        if ("function" == typeof b.dataCache[0]) {
                            var c = b.dataCache[0];
                            b.dataCache[0] = a,
                            c()
                        } else b.dataCache[0] = a
                    },
                    err: function(a) {
                        if ("function" == typeof b.dataCache[0]) {
                            var c = b.dataCache[0];
                            b.dataCache[0] = a,
                            c("error")
                        } else b.dataCache[0] = null
                    }
                },
                f = b.beforeRequest && b.beforeRequest();
                if ("boolean" == typeof f && !f) return;
                DB.cgiHttp(e),
                b.dataCache[0] = "@prefeching"
            } catch(h) {
                b._prefetchError = 1
            }
            return b
        };
        i.prototype = {
            type: "renderModel",
            exportTab: function(a, b) {
                var c = "recieveModel" + a,
                d = "realizeModel" + a,
                e = this;
                window[c] && "function" == typeof window[c] ? window[c](a, this, b) : window[d] = function(c) {
                    c(a, e, b),
                    delete window[d]
                }
            },
            info: function(a) {
                if ("string" == typeof a) {
                    var b = window.location.pathname.replace("/mobile/", "") + " >> " + this.comment;
                    console.info(b + " : " + a),
                    console.report && console.report({
                        type: "info",
                        category: b,
                        content: a
                    })
                } else console.info(a)
            },
            tell: function(a, b, c) {
                var d = ["__", b, "__"].join("");
                "undefined" != typeof this[b] && (this[d] = this[b]),
                Object.defineProperty(this, b, {
                    get: function() {
                        return this[d]
                    },
                    set: function(e) {
                        this[d] = e,
                        c ? c.call(a, e) : a[b] = e
                    }
                })
            },
            watch: function(a, b, c) {
                var d = ["__", b, "__"].join("");
                "undefined" != typeof a[b] && (a[d] = a[b]);
                var e = this;
                Object.defineProperty(a, b, {
                    get: function() {
                        return this[d]
                    },
                    set: function(a) {
                        this[d] = a,
                        c ? c.call(e, a) : e[b] = a
                    }
                })
            },
            getCache: function(a) {
                return 0 === a ? this.localData: this.dataCache[a - 1]
            },
            getData: function(a) {
                var b, d = this,
                e = d.paramCache[d.cgiCount] || "object" == typeof this.param && this.param || this.param.call(this),
                f = e;
                if (this._args) {
                    for (var g = JSON.stringify(e), i = 0; i < this._args.length; i++) {
                        var j = i + 1;
                        g = g.replace(new RegExp("@param" + j, "g"), this._args[i] || "")
                    }
                    g = g.replace(/@param\d+/g, ""),
                    f = JSON.parse(g)
                }
                var k = ~~ (1e6 * Math.random());
                h[k] = 0;
                var l = {
                    url: this.cgiName,
                    type: this.method || "POST",
                    ssoCmd: this.ssoCmd,
                    param: f,
                    succ: function(b, g) {
                        if (g) return void a(b, k);
                        if (d.dataCache[d.cgiCount] = b, d.paramCache[d.cgiCount] = e, d.cgiCount++, 1 == d.cgiCount) {
                            var h = (d.cacheKey || c)(d.cgiName, f);
                            if (h) try {
                                window.localStorage.setItem(h, JSON.stringify(b))
                            } catch(i) {
                                window.localStorage.clear(),
                                window.localStorage.setItem(h, JSON.stringify(b))
                            }
                        }
                        console.info("返回数据：", b),
                        a(b, k, f)
                    },
                    err: function(a) {
                        d.info("cgi返回数据失败 cgiCount：" + d.cgiCount + ", url->" + d.cgiName),
                        console.info("返回数据：", a),
                        d.paramCache[d.cgiCount] = e,
                        d.cgiCount++,
                        d.error && d.error.call(d, a, d.cgiCount)
                    }
                };
                if (this.usePreLoad && this.preLoadData) {
                    if (this.info("使用预加载数据相关逻辑"), this.usePreLoad = !1, "error" !== this.preLoadData.type && 0 == this.preLoadData.retcode) return void l.succ(this.preLoadData);
                    l.err(this.preLoadData)
                }
                if (!this.dead) {
                    if (!d.noCache && 0 == d.cgiCount && d.isFirstRender) {
                        var m = (d.cacheKey || c)(this.cgiName, f);
                        b = null;
                        try {
                            b = JSON.parse(window.localStorage.getItem(m))
                        } catch(n) {}
                        if (b && b.result) try {
                            l.succ(b, 1)
                        } catch(n) {
                            throw window.localStorage.removeItem(m),
                            $(window).trigger("renderError", [d.cgiName]),
                            Q.monitor(453668),
                            n.category = window.location.pathname.replace("/mobile/", "") + " >> " + this.comment,
                            n
                        }
                        d.isFirstRender = 0,
                        this.localData = b
                    }
                    if (this.dataCache[d.cgiCount]) d.info("@prefeching使用缓存数据"),
                    "@prefeching" === this.dataCache[d.cgiCount] ? this.dataCache[d.cgiCount] = function(a) {
                        a ? l.err(d.dataCache[d.cgiCount]) : l.succ(d.dataCache[d.cgiCount])
                    }: l.succ(this.dataCache[d.cgiCount]);
                    else if (this.usePreLoad) d.paramCache[d.cgiCount] = e;
                    else {
                        var o = this.beforeRequest && this.beforeRequest();
                        if ("boolean" == typeof o && !o) return;
                        this.info("ct->" + (Number(this.cgiCount) + 1) + ", url->" + l.url),
                        console.info("请求参数：", f),
                        DB.cgiHttp(l)
                    }
                }
            },
            clearLocalData: function(a) {
                var b = this.paramCache[a || 0],
                d = (this.cacheKey || c)(this.cgiName, b);
                window.localStorage.removeItem(d)
            },
            setPreLoadData: function(a) {
                this.preLoadData = a
            },
            reset: function() {
                this.cgiCount = 0,
                this.melt(),
                this.onreset && this.onreset()
            },
            stop: function() {
                for (var a in h) h.hasOwnProperty(a) && (h[a] = 1)
            },
            die: function() {
                "scrollModel" === this.type && f.removeModel(this),
                this.dead = 1
            },
            render: function(b) {
                var c, d = this;
                this.scrollEnable = 0,
                "string" == typeof b && (b = $(b));
                var e = this.beforeRender && this.beforeRender();
                if ("boolean" != typeof e || e) if ("scrollModel" === this.type, this.cgiName) this.getData(function(e, f, g) {
                    if (!d.dead) {
                        if (1 == d.cgiCount && (d.onreset && d.onreset(), b.html("")), d.processData && d.processData.call(d, e, d.cgiCount), h[f]);
                        else {
                            var i = [];
                            if (a = function(a) {
                                var b = a.name;
                                i.push(b)
                            },
                            0 === d.cgiCount || $.os.android ? d.useInfiniteScroller ? (c = Tmpl(d.renderTmpl, e.result || e, d.renderTool || {}), InfiniteScrollList.render(c.toString(), d.cgiCount)) : Tmpl(d.renderTmpl, e.result || e, d.renderTool || {}).appendTo(b) : d.useInfiniteScroller ? (c = Tmpl(d.renderTmpl, e.result || e, d.renderTool || {}), InfiniteScrollList.render(c.toString(), d.cgiCount)) : Tmpl(d.renderTmpl, e.result || e, d.renderTool || {}).appendTo(b), i.length) {
                                var j = ["CGI Field Missing ", i.join(", "), "cgi:" + d.cgiName, "request: " + JSON.stringify(g)].join("|");
                                console.warn("CGI Field Missing", "\nnames:" + i.join(", "), "\ncgi: " + d.cgiName, "\nrequest:", g, "\nresponse", e),
                                Badjs(j, window.location.href, "sodaRender")
                            }
                            a = null
                        }
                        d.cgiCount > 0 && (d.scrollEnable = 1),
                        d.eventsBinded || (d.events && d.events(), d.hasOwnProperty("eventsBinded") ? d.eventsBinded = 1 : d.__proto__.eventsBinded = 1),
                        d.feedPool.map(function(a) {
                            a.noFeed || (a.setFeedData(e, d.cgiCount), a.rock())
                        }),
                        d.complete && d.complete(e, d.cgiCount),
                        d.isFirstDataRequestRender++,
                        delete h[f]
                    }
                });
                else if (this.data ? "function" == typeof this.data && (this.data = this.data()) : this.data = {},
                this.data) {
                    d.processData && d.processData.call(this, this.data, d.cgiCount),
                    b.html("");
                    var f = [];
                    a = function(a) {
                        var b = a.name;
                        f.push(b)
                    },
                    Tmpl(d.renderTmpl, this.data.result || this.data, d.renderTool).update(b);
                    try {
                        if (f.length) {
                            var g = ["CGI Field Missing, data given ", f.join(", "), "modelBlock:" + d.comment || d.renderContainer].join("|");
                            console.warn("CGI Field Missing, data given", "\nnames:" + f.join(", "), "\nmodelBlock: " + d.comment || d.renderContainer, "\ndata", this.data),
                            Badjs(g, window.location.href, "sodaRender")
                        }
                    } catch(i) {}
                    a = null,
                    d.eventsBinded || (d.events && d.events(), d.hasOwnProperty("eventsBinded") ? d.eventsBinded = 1 : d.__proto__.eventsBinded = 1),
                    d.scrollEnable = 1,
                    d.feedPool.map(function(a) {
                        a.noFeed || (a.setFeedData(this.data, d.cgiCount), a.rock())
                    }),
                    d.complete && d.complete(this.data, d.cgiCount),
                    d.isFirstDataRequestRender++
                }
            },
            rock: function() {
                if (this._passedFirstRender && !window.fireDragon) {
                    if (0 === this.cgiCount) {
                        var a = this.paramCache[this.cgiCount] || "object" == typeof this.param && this.param || this.param.call(this);
                        this.paramCache[this.cgiCount] = a
                    }
                    return this.cgiCount = 1,
                    this.isFirstDataRequestRender = 1,
                    this._passedFirstRender = 0,
                    void(this.events && this.events())
                }
                this.render(this.renderContainer, 1)
            },
            processData: function() {},
            feed: function(a) {
                this.feedPool.push(a),
                a.feeded = 1
            },
            setFeedData: function(a, b) {
                this.data = a,
                this.cgiCount = b
            },
            update: function(a) {
                this.data = a,
                this.reset(),
                this.rock()
            },
            resetData: function() {
                this.dataCache = [],
                this.cgiCount = 0,
                this.onreset && this.onreset()
            },
            refresh: function() {
                this.noRefresh || (this.dataCache = [], this.reset(), this.rock())
            },
            hide: function() {
                var a = this.renderContainer;
                "string" == typeof a && (a = $(a)),
                a.hide()
            },
            show: function() {
                var a = this.renderContainer;
                "string" == typeof a && (a = $(a)),
                a.show()
            },
            extend: function(a) {
                a || (a = {});
                var b = function() {},
                c = a.events;
                b.prototype = this;
                var d = new b;
                d.feedPool = [],
                d.cgiCount = 0,
                d.dataCache = [],
                d.isFirstDataRequestRender = 0,
                d.isFirstRender = 1,
                d._addedToModel = 0,
                d.canScrollInMTB = 1,
                d.dead = 0,
                a.param && (d.paramCache = []);
                for (var e in a) d[e] = a[e];
                return c && (d.events = function() {
                    c && c.call(this)
                },
                d.eventsBinded = 0),
                d.renderContainer && (g[d.renderContainer] ? g[d.renderContainer]++:g[d.renderContainer] = 1),
                d
            },
            freeze: function() {
                this.freezed = 1
            },
            melt: function() {
                this.freezed = 0
            },
            supportFireDragon: function() {
                this._passedFirstRender = 1
            }
        };
        var j = function(a) {
            var b = new i(a);
            b.scrollEnable = 1,
            b.__proto__.eventsBinded = 0,
            b.type = "scrollModel",
            b._scrollEl = a.scrollEl || ($.os.ios ? "#js_bar_main": window),
            b._ctlByMutitab = 0;
            var c = b.events;
            return b.events = function() {
                c && c.call(this)
            },
            b.renderContent = function() {
                this.render(this.renderContainer, 1)
            },
            b._addedToModel = 0,
            b.canScrollInMTB = 1,
            b.rock = function() {
                if (this._passedFirstRender && !window.fireDragon) {
                    if (0 === this.cgiCount) {
                        var a = this.paramCache[this.cgiCount] || "object" == typeof this.param && this.param || this.param.call(this);
                        this.paramCache[this.cgiCount] = a
                    }
                    this.cgiCount = 1,
                    this.isFirstDataRequestRender = 1,
                    this._passedFirstRender = 0,
                    this.events && this.events()
                } else this.render(this.renderContainer, 1);
                this._addedToModel || "scrollModel" !== this.type || (f.addModel(this), this._addedToModel = 1)
            },
            b
        },
        k = function() {};
        k.prototype = {
            type: "abstractModel",
            hide: d,
            show: d,
            rock: function() {},
            reset: function() {},
            refresh: function() {},
            freeze: function() {},
            resetData: function() {}
        };
        var l = function(a) {
            this.cgiName = a.cgiName,
            this.comment = a.comment,
            this.param = a.param,
            this.ssoCmd = a.ssoCmd,
            this.processData = a.processData,
            this.complete = a.complete,
            this.error = a.error,
            this.paramCache = [],
            this.cgiCount = 0,
            this.dataCache = [],
            this.feedPool = [],
            this.isFirstRender = 1,
            this.eventsBinded = 0,
            this.isFirstDataRequestRender = 0
        };
        l.prototype = {
            getData: i.prototype.getData,
            info: i.prototype.info,
            noCache: 1,
            rock: function() {
                var a = this;
                this._args = arguments,
                this.getData(function(b) {
                    a.processData && a.processData(b, a.cgiCount),
                    a.complete && a.complete(b, a.cgiCount)
                })
            },
            reset: function() {
                this.dataCache = []
            },
            refresh: function() {
                this.dataCache = [],
                this.rock()
            },
            show: function() {},
            hide: function() {},
            extend: i.prototype.extend
        };
        var m = function() {
            this.pool = [],
            this.currModel = null,
            this.eventBinded = 0
        };
        m.prototype = {
            tell: i.prototype.tell,
            info: i.prototype.info,
            watch: i.prototype.watch,
            supportFireDragon: function() {
                this._passedFirstRender = 1
            },
            _loadJs: function(a, b) {
                var c = this.pool[a][0],
                d = this.pool[a][1],
                e = "recieveModel" + c,
                f = "realizeModel" + c,
                g = this,
                h = function(c, d, f) {
                    c && d && (g.pool[a] = [c, d, f], window[e] = null, delete window[e], b && b())
                };
                window[f] ? window[f](h) : window[e] = h,
                loadjs.loadModule(d)
            },
            _bindSwitchEvent: function() {
                var a = this;
                this.eventBinded || ($("body").on("tap",
                function(b) {
                    for (var c = $(b.target), d = g, e = [], f = [], h = 0; h < a.pool.length; h++) {
                        var i = a.pool[h][0],
                        j = c.closest(i);
                        j && j.length && (e.push(a.pool[h]), f.push(h))
                    }
                    if (e.length) {
                        var k = e[0],
                        i = k[0],
                        l = k[1],
                        m = k[2];
                        if ("string" == typeof l) {
                            var n = "recieveModel" + i;
                            return window[n] = function(b, c, d) {
                                b && c && (a.pool[f[0]] = [b, c, d], window[n] = null, delete window[n], a.rock(b, {
                                    name: "switch"
                                }))
                            },
                            void loadjs.loadModule(l)
                        }
                        if (a.currModel = l, l.canScrollInMTB = 1, a.beforeTabHandler && a.beforeTabHandler.call(a, i, "switch"), l.renderContainer ? d[l.renderContainer] > 1 ? (a.currModel.reset(), a.currModel.rock(), $(a.currModel.renderContainer)[0].scrollTop = 0) : l.isFirstDataRequestRender > 0 ? "scrollModel" === l.type: l.rock() : "pageModel" === l.type && a.currModel._switchedToPage(), "linkModel" === a.currModel.type) return l.rock(),
                        void(a.tabHander && a.tabHander.call(a, i, "switch"));
                        a.pool.map(function(a) {
                            var b = a[0];
                            a[1] !== l && "string" != typeof a[1] && (a[1].hide(), a[1].canScrollInMTB = 0, $(b).removeClass("active").removeClass("selected"))
                        }),
                        $(i).addClass("active").addClass("selected"),
                        l.show(),
                        console.log("tab切换" + i),
                        m && m.call(a, "switch"),
                        a.tabHander && a.tabHander.call(a, i, "switch")
                    }
                }), this.eventBinded = 1)
            },
            stop: function() {
                this.currModel.stop && this.currModel.stop()
            },
            rock: function(a, b) {
                for (var c, d, e, f, g, h = this,
                i = 0; i < this.pool.length; i++) d = this.pool[i],
                e = d[0],
                f = d[1],
                g = d[2],
                a ? e === a && (c = [e, f, g, i]) : this.initTab ? e === this.initTab && (c = [e, f, g, i]) : 0 === i && (c = [e, f, g, i]);
                if (!c) {
                    console.info("Model cannot init mutitab, check if selector exists!");
                    var j = this.pool[0];
                    if (!j) return;
                    c = [j[0], j[1], j[2], 0]
                }
                if (this._bindSwitchEvent(), e = c[0], f = c[1], g = c[2], i = c[3], "string" == typeof f) return void this._loadJs(i,
                function() {
                    h.rock(e),
                    f.scrollEnable = 0;
                    for (var a = 0; a < h.pool.length; a++)"string" == typeof h.pool[a][1] && h._loadJs(a)
                });
                this.beforeTabHandler && this.beforeTabHandler.call(this, e, "switch"),
                f.canScrollInMTB = 1,
                this._passedFirstRender && (f.supportFireDragon(), this._passedFirstRender = 0),
                this.currModel = f;
                var k = b && b.name || "init";
                g && g.call(this, k),
                this.currModel.reset(),
                this.currModel.rock(),
                this.pool.map(function(a) {
                    var b = a[1];
                    b !== f && ("string" == typeof b || ($(a[0]).removeClass("active").removeClass("selected"), b.hide(), b.canScrollInMTB = 0))
                }),
                $(e).addClass("active").addClass("selected"),
                this.currModel.show(),
                this.scrollEnable = this.currModel.scrollEnable,
                this.tabHander && this.tabHander.call(this, e, k)
            },
            add: function(a, b, c) {
                b._modelSelector = a,
                this.pool.push([a, b, c]),
                b.controller = this,
                "string" == typeof b ? window.loadJsConfig && window.loadJsConfig.modules && (window.loadJsConfig.modules[b] || console.info("mutitab connot load lazymodel while " + b + " not exists in loadJsConfig modules!")) : b.scrollEnable = 0
            },
            beforetabswitch: function(a) {
                console.log("beforetabswitch"),
                this.beforeTabHandler = a
            },
            ontabswitch: function(a) {
                this.tabHander = a
            },
            switchTo: function(a) {
                if (console.log("switchTo"), $(a)[0]) $(a).trigger("tap");
                else {
                    var b;
                    b = $(/^#/.test(a) ? "<div style='display: none;' id='" + a.replace("#", "") + "'></div>": "<div style='display: none;' class='" + a.replace(".", "") + "'></div>"),
                    $("body").append(b),
                    b.trigger("tap")
                }
            },
            init: function(a) {
                this.initTab = a
            },
            freeze: function() {
                this.freezed = 1
            },
            melt: function() {
                this.freezed = 0
            },
            freezeCurrent: function() {
                this.currModel.freeze()
            },
            refresh: function() {
                this.currModel.refresh()
            },
            refreshList: function(a) {
                this.currModel._modelSelector === a ? this.currModel.refresh() : this.currModel.resetData()
            }
        };
        var n = function(a) {
            a && a.renderContainer && (this.renderContainer = a.renderContainer),
            this.models = []
        };
        n.prototype = {
            info: i.prototype.info,
            tell: i.prototype.tell,
            watch: i.prototype.watch,
            set canScrollInMTB(a) {
                this.models.map(function(b) {
                    b.canScrollInMTB = a
                })
            },
            supportFireDragon: function() {
                this.models.map(function(a) {
                    a.supportFireDragon()
                })
            },
            type: "pageModel",
            exportTab: i.prototype.exportTab,
            add: function(a) {
                this.models.push(a),
                a.controller = this
            },
            stop: function() {
                this.models.map(function(a) {
                    a.stop && a.stop()
                })
            },
            remove: function(a) {
                var b;
                this.models.map(function(c, d) {
                    c == a && (b = d, a.controller = null)
                }),
                "number" == typeof b && this.models.splice(b, 1)
            },
            _switchedToPage: function() {
                this.models.map(function(a) {
                    "page" === a.type ? a._switchedToPage() : a.renderContainer && (g[a.renderContainer] > 1 ? (a.reset(), a.rock(), a.renderContainer && ($(a.renderContainer)[0].scrollTop = 0)) : a.isFirstDataRequestRender > 0 ? "scrollModel" === a.type: a.rock())
                })
            },
            rock: function() {
                this.models.map(function(a) {
                    a.feeded || a.rock()
                })
            },
            extend: function() {
                var a = function() {};
                a.prototype = this;
                var b = new a;
                b.parent = this;
                for (var c in b) b[c].parent = parent[c];
                return b
            },
            reset: function() {
                this.models.map(function(a) {
                    a.reset()
                })
            },
            refresh: function() {
                this.models.map(function(a) {
                    a.refresh()
                })
            },
            hide: function() {
                this.renderContainer ? $(this.renderContainer).hide() : this.models.map(function(a) {
                    a.hide()
                })
            },
            show: function() {
                this.renderContainer ? $(this.renderContainer).show() : this.models.map(function(a) {
                    a.show()
                })
            }
        };
        var o = function(a) {
            this.param = a.param,
            this.url = a.url,
            this.newWindow = a.newWindow,
            this.popBack = a.popBack,
            this.checkBack = a.checkBack
        };
        o.prototype = {
            type: "linkModel",
            hide: d,
            show: d,
            rock: function() {
                var a = "",
                b = this.param;
                if ("function" == typeof this.param && (b = this.param.call(this)), this.popBack && mqq.ui.popBack(), b) {
                    var c = [];
                    for (var d in b) c.push(d + "=" + (b[d] || ""));
                    a = c.join("&")
                }
                var e;
                if (e = a ? this.url + "?" + a: this.url) {
                    if (this.checkBack) {
                        var f = document.referrer;
                        if (f.indexOf(this.url) > -1) return void history.back()
                    }
                    this.newWindow ? Util.openUrl(e, !0) : Util.openUrl(e)
                }
            },
            reset: function() {}
        },
        window.renderModel = i,
        window.scrollModel = j,
        window.linkModel = o,
        window.mutitabModel = m,
        window.pageModel = n,
        window.cgiModel = l,
        window.abstractModel = k,
        window.Model = b,
        console.info("Model: Ready!")
    } (),
    function(a, b) {
        a.ActionButton = b()
    } (this,
    function() {
        function a() {
            mqq.compare("5.3") < 0 || -1 !== navigator.userAgent.indexOf("QQHD") ? m ? c(m.option, m.callback) : c({
                hidden: !0
            },
            function() {}) : mqq.android ? mqq.invoke("TroopMemberApiPlugin", "getUploadInfo", {
                callback: mqq.callback(b)
            }) : mqq.invoke("tribe", "getUploadInfo", {
                callback: mqq.callback(b)
            })
        }
        function b(a) {
            var b = {},
            e = parseInt(a.status),
            f = location.href.indexOf("/barindex.html") > -1;
            e ? m ? (b.option = o.option, b.option.cornerID = 2 === e ? "6": "0", b.callback = function() {
                d(e)
            }) : (b = o, b.option.cornerID = 2 === e ? "6": "0") : (m ? b = m: (b.option = {
                iconID: "3",
                cornerID: "0",
                hidden: !0
            },
            b.callback = function() {}), f && $(".uploading-video").remove()),
            n = a.info || [],
            l = a,
            c(b.option, b.callback),
            j && j()
        }
        function c(a, b) {
            if ( - 1 !== navigator.userAgent.indexOf("QQHD")) {
                var c = ~~a.iconID;
                return a.title || (a.title = 4 === c ? "分享": "更多"),
                a.isDetail && (a.title = "分享", delete a.isDetail),
                a.hidden && (a.title = ""),
                mqq.invokeClient("nav", "setActionButton", a, b)
            }
            return mqq.ui.setActionButton(a, b)
        }
        function d(a) {
            if (k = $(".action-button-menu-mask"), !k.length) {
                var b = m.option.type,
                c = '<div class="action-button-menu-mask"><ul id="_js_action_button_menu" class="action-button-menu">';
                c += '<li class="action-button-upload section-1px"><span id="js_ac_upload_text"></span><span id="js_ac_upload_err" class="tip-error"></span></li>',
                c += '<li class="action-button-custom action-button-' + b + '">',
                c += "share" === b ? "分享": "更多",
                c += "</li></ul></div>",
                k = $(c).appendTo("body")
            }
            2 === a ? ($("#js_ac_upload_text").text("上传失败"), $("#js_ac_upload_err").show()) : ($("#js_ac_upload_text").text("正在上传"), $("#js_ac_upload_err").hide()),
            k.show()
        }
        function e(a, b) {
            var c, d, e, f, g = [];
            for (d = n.length; d > 0; d--) e = n[d - 1],
            e.bid && e.bid === a && (f = {
                isMock: !0,
                type: 201,
                bid: a,
                pid: e.pid,
                title: e.title,
                brief: "视频正在上传中...",
                post: {
                    content: e.content,
                    image1: e.video_pic,
                    pic_list: []
                },
                time: e.time
            },
            b && e.pid === b && !e.cid && (c = f), b || e.cid || g.push(f));
            return b ? c: g
        }
        function f(b, c) {
            m = {
                option: b,
                callback: c
            },
            a()
        }
        function g() {
            $(document).on("tap", ".action-button-upload",
            function() {
                k.hide(),
                o.callback()
            }),
            $(document).on("tap", ".action-button-custom",
            function() {
                k.hide(),
                m.callback()
            }),
            $(document).on("touchmove", ".action-button-menu-mask",
            function(a) {
                a.preventDefault(),
                a.stopPropagation()
            }),
            $(document).on("tap", ".action-button-menu-mask",
            function() {
                k.hide()
            }),
            document.addEventListener("qbrowserVisibilityChange",
            function(b) {
                b.hidden || a()
            }),
            mqq.addEventListener("kTribeUploadStatusChangeNotifcation",
            function() {
                a()
            })
        }
        function h(a) {
            j = a
        }
        function i() {
            mqq.compare("5.3") > 0 && g(),
            a()
        }
        var j, k, l, m, n = [],
        o = {
            option: {
                iconID: "5"
            },
            callback: function() {
                mqq.iOS && mqq.ui.openView({
                    name: "QQGTUploadListViewController"
                }),
                mqq.android && mqq.ui.openView({
                    name: "com.tencent.mobileqq.troop.activity.TroopBarUploadManagerActivity"
                })
            }
        };
        return i(),
        {
            build: f,
            refresh: a,
            showMenu: d,
            getUploadVideo: e,
            setCallback: h
        }
    }),
    function(a, b) {
        a.Alert = b(a.$, a.Tmpl)
    } (this,
    function(a, b) {
        function c() {
            h = a('<div class="alert"></div>'),
            a(document.body).append(h),
            h.on("tap",
            function(a) {
                a.target !== h[0] || m || e.call(this, a)
            }),
            l = !0
        }
        function d(a, d, g, m) {
            l || c(),
            h.html(""),
            h.on("touchmove", f);
            var n = "",
            o = {};
            m ? (n = window.TmplInline_alert.textarea, o = {
                title: a,
                placeholder: g.placeholder,
                content: d,
                confirm: g && g.confirm || "确认",
                cancel: g && g.cancel
            }) : (n = window.TmplInline_alert.frame, o = {
                title: a,
                content: d,
                confirm: g && g.confirm || "确认",
                cancel: g && g.cancel,
                confirmAtRight: g && g.confirmAtRight || !1
            }),
            b(n, o).appendTo(h),
            h.show(),
            h.find(".btn").on("tap", e),
            g && g.callback && (i = g.callback),
            g && g.cancelCallback && (j = g.cancelCallback),
            g && g.onTap && (k = g.onTap)
        }
        function e(b) {
            var c = null;
            "BUTTON" === b.target.tagName && (c = "right"),
            h.off("touchmove", f),
            a(this).hasClass(".btn") && (this.style.backgroundColor = "#E5E5E5");
            var d = this;
            h.find(".edit").blur(),
            setTimeout(function() {
                a(this).hasClass(".btn") && (d.style.backgroundColor = "#ffffff"),
                h.hide()
            },
            100),
            "confirm-btn" === this.id ? (c = "left", i && i()) : j && j(),
            k && k.apply(this, [c, b, h[0]]),
            m = !1
        }
        function f(a) {
            var b = a.target;
            b === h[0] && a.preventDefault()
        }
        function g() {
            h.hide(),
            h.off("touchmove", f),
            "confirm-btn" === this.id ? i && i() : j && j()
        }
        var h, i, j, k, l = !1,
        m = !1;
        return {
            textarea: function(a, b, c) {
                d(a, b, c, 1),
                c.hasOwnProperty("preventAutoHide") && (m = c.preventAutoHide),
                c.renderSuccess && c.renderSuccess.call(h[0])
            },
            show: d,
            hide: g
        }
    }),
    function(a, b) {
        "function" == typeof define && define.amd ? define(b) : a.TmplInline_alert = b()
    } (this,
    function() {
        var a = {},
        b = function(a) {
            function b(b) {
                return c("undefined" == typeof a[b] ? this[b] : a[b])
            }
            function c(a) {
                return "undefined" == typeof a ? "": a
            }
            a = a || {};
            var d = b("title"),
            e = b("content"),
            f = b("confirmAtRight"),
            g = b("cancel"),
            h = b("confirm"),
            i = "";
            return i += '<div class="frame"> <h3 class="title">',
            i += c(d),
            i += '</h3> <p class="content">',
            i += c(e),
            i += '</p> <div class="btn-group section-1px"> ',
            f ? (i += " ", g && (i += ' <button class="btn">', i += c(g), i += "</button> "), i += ' <button class="btn" id="confirm-btn">', i += c(h), i += "</button> ") : (i += ' <button class="btn" id="confirm-btn">', i += c(h), i += "</button> ", g && (i += ' <button class="btn">', i += c(g), i += "</button> "), i += " "),
            i += " </div> </div> "
        };
        a.frame = "TmplInline_alert.frame",
        Tmpl.addTmpl(a.frame, b);
        var c = function(a) {
            function b(b) {
                return c("undefined" == typeof a[b] ? this[b] : a[b])
            }
            function c(a) {
                return "undefined" == typeof a ? "": a
            }
            a = a || {};
            var d = b("title"),
            e = b("placeholder"),
            f = b("content"),
            g = b("confirm"),
            h = b("cancel"),
            i = "";
            return i += '<div class="frame a-forwards" > <h3 class="split-title">',
            i += c(d),
            i += '</h3> <div class="a-edit-wrapper"> <div class="a-edit-border border-1px"> <textarea spellcheck="false" class="edit" placeholder="',
            i += c(e),
            i += '" type=\'text\'></textarea> </div> </div> <div class="thumbnail"> ',
            i += c(f),
            i += ' </div> <div class="btn-group section-1px"> <button class="btn" id="confirm-btn">',
            i += c(g),
            i += "</button> ",
            h && (i += ' <button class="btn btn-import">', i += c(h), i += "</button> "),
            i += " </div> </div>"
        };
        return a.textarea = "TmplInline_alert.textarea",
        Tmpl.addTmpl(a.textarea, c),
        a
    }),
    function(a, b, c) {
        function d(a, c) {
            this.wrapper = "string" == typeof a ? b.querySelector(a) : a,
            this.scroller = this.wrapper.children[0],
            this.scrollerStyle = this.scroller.style,
            this.options = {
                startX: 0,
                startY: 0,
                scrollY: !0,
                directionLockThreshold: 5,
                momentum: !0,
                bounce: !0,
                bounceTime: 600,
                bounceEasing: "",
                preventDefault: !0,
                preventDefaultException: {
                    tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT)$/
                },
                HWCompositing: !0,
                useTransition: !0,
                useTransform: !0
            };
            for (var d in c) this.options[d] = c[d];
            this.translateZ = this.options.HWCompositing && f.hasPerspective ? " translateZ(0)": "",
            this.options.useTransition = f.hasTransition && this.options.useTransition,
            this.options.useTransform = f.hasTransform && this.options.useTransform,
            this.options.eventPassthrough = this.options.eventPassthrough === !0 ? "vertical": this.options.eventPassthrough,
            this.options.preventDefault = !this.options.eventPassthrough && this.options.preventDefault,
            this.options.scrollY = "vertical" == this.options.eventPassthrough ? !1 : this.options.scrollY,
            this.options.scrollX = "horizontal" == this.options.eventPassthrough ? !1 : this.options.scrollX,
            this.options.freeScroll = this.options.freeScroll && !this.options.eventPassthrough,
            this.options.directionLockThreshold = this.options.eventPassthrough ? 0 : this.options.directionLockThreshold,
            this.options.bounceEasing = "string" == typeof this.options.bounceEasing ? f.ease[this.options.bounceEasing] || f.ease.circular: this.options.bounceEasing,
            this.options.resizePolling = void 0 === this.options.resizePolling ? 60 : this.options.resizePolling,
            this.options.tap === !0 && (this.options.tap = "tap"),
            this.x = 0,
            this.y = 0,
            this.directionX = 0,
            this.directionY = 0,
            this._events = {},
            this._init(),
            this.refresh(),
            this.scrollTo(this.options.startX, this.options.startY),
            this.enable()
        }
        var e = a.requestAnimationFrame || a.webkitRequestAnimationFrame || a.mozRequestAnimationFrame || a.oRequestAnimationFrame || a.msRequestAnimationFrame ||
        function(b) {
            a.setTimeout(b, 1e3 / 60)
        },
        f = function() {
            function d(a) {
                return g === !1 ? !1 : "" === g ? a: g + a.charAt(0).toUpperCase() + a.substr(1)
            }
            var e = {},
            f = b.createElement("div").style,
            g = function() {
                for (var a, b = ["t", "webkitT", "MozT", "msT", "OT"], c = 0, d = b.length; d > c; c++) if (a = b[c] + "ransform", a in f) return b[c].substr(0, b[c].length - 1);
                return ! 1
            } ();
            e.getTime = Date.now ||
            function() {
                return (new Date).getTime()
            },
            e.extend = function(a, b) {
                for (var c in b) a[c] = b[c]
            },
            e.addEvent = function(a, b, c, d) {
                a.addEventListener(b, c, !!d)
            },
            e.removeEvent = function(a, b, c, d) {
                a.removeEventListener(b, c, !!d)
            },
            e.prefixPointerEvent = function(b) {
                return a.MSPointerEvent ? "MSPointer" + b.charAt(9).toUpperCase() + b.substr(10) : b
            },
            e.momentum = function(a, b, d, e, f, g) {
                var h, i, j = a - b,
                k = c.abs(j) / d;
                return g = void 0 === g ? 6e-4: g,
                h = a + k * k / (2 * g) * (0 > j ? -1 : 1),
                i = k / g,
                e > h ? (h = f ? e - f / 2.5 * (k / 8) : e, j = c.abs(h - a), i = j / k) : h > 0 && (h = f ? f / 2.5 * (k / 8) : 0, j = c.abs(a) + h, i = j / k),
                {
                    destination: c.round(h),
                    duration: i
                }
            };
            var h = d("transform");
            return e.extend(e, {
                hasTransform: h !== !1,
                hasPerspective: d("perspective") in f,
                hasTouch: "ontouchstart" in a,
                hasPointer: a.PointerEvent || a.MSPointerEvent,
                hasTransition: d("transition") in f
            }),
            e.isBadAndroid = /Android /.test(a.navigator.appVersion) && !/Chrome\/\d/.test(a.navigator.appVersion),
            e.extend(e.style = {},
            {
                transform: h,
                transitionTimingFunction: d("transitionTimingFunction"),
                transitionDuration: d("transitionDuration"),
                transitionDelay: d("transitionDelay"),
                transformOrigin: d("transformOrigin")
            }),
            e.hasClass = function(a, b) {
                var c = new RegExp("(^|\\s)" + b + "(\\s|$)");
                return c.test(a.className)
            },
            e.addClass = function(a, b) {
                if (!e.hasClass(a, b)) {
                    var c = a.className.split(" ");
                    c.push(b),
                    a.className = c.join(" ")
                }
            },
            e.removeClass = function(a, b) {
                if (e.hasClass(a, b)) {
                    var c = new RegExp("(^|\\s)" + b + "(\\s|$)", "g");
                    a.className = a.className.replace(c, " ")
                }
            },
            e.offset = function(a) {
                for (var b = -a.offsetLeft,
                c = -a.offsetTop; a = a.offsetParent;) b -= a.offsetLeft,
                c -= a.offsetTop;
                return {
                    left: b,
                    top: c
                }
            },
            e.preventDefaultException = function(a, b) {
                for (var c in b) if (b[c].test(a[c])) return ! 0;
                return ! 1
            },
            e.extend(e.eventType = {},
            {
                touchstart: 1,
                touchmove: 1,
                touchend: 1,
                mousedown: 2,
                mousemove: 2,
                mouseup: 2,
                pointerdown: 3,
                pointermove: 3,
                pointerup: 3,
                MSPointerDown: 3,
                MSPointerMove: 3,
                MSPointerUp: 3
            }),
            e.extend(e.ease = {},
            {
                quadratic: {
                    style: "cubic-bezier(0.25, 0.46, 0.45, 0.94)",
                    fn: function(a) {
                        return a * (2 - a)
                    }
                },
                circular: {
                    style: "cubic-bezier(0.1, 0.57, 0.1, 1)",
                    fn: function(a) {
                        return c.sqrt(1 - --a * a)
                    }
                },
                back: {
                    style: "cubic-bezier(0.175, 0.885, 0.32, 1.275)",
                    fn: function(a) {
                        var b = 4;
                        return (a -= 1) * a * ((b + 1) * a + b) + 1
                    }
                },
                bounce: {
                    style: "",
                    fn: function(a) {
                        return (a /= 1) < 1 / 2.75 ? 7.5625 * a * a: 2 / 2.75 > a ? 7.5625 * (a -= 1.5 / 2.75) * a + .75 : 2.5 / 2.75 > a ? 7.5625 * (a -= 2.25 / 2.75) * a + .9375 : 7.5625 * (a -= 2.625 / 2.75) * a + .984375
                    }
                },
                elastic: {
                    style: "",
                    fn: function(a) {
                        var b = .22,
                        d = .4;
                        return 0 === a ? 0 : 1 == a ? 1 : d * c.pow(2, -10 * a) * c.sin(2 * (a - b / 4) * c.PI / b) + 1
                    }
                }
            }),
            e.tap = function(a, c) {
                var d = b.createEvent("Event");
                d.initEvent(c, !0, !0),
                d.pageX = a.pageX,
                d.pageY = a.pageY,
                a.target.dispatchEvent(d)
            },
            e.click = function(a) {
                var c, d = a.target;
                /(SELECT|INPUT|TEXTAREA)/i.test(d.tagName) || (c = b.createEvent("MouseEvents"), c.initMouseEvent("click", !0, !0, a.view, 1, d.screenX, d.screenY, d.clientX, d.clientY, a.ctrlKey, a.altKey, a.shiftKey, a.metaKey, 0, null), c._constructed = !0, d.dispatchEvent(c))
            },
            e
        } ();
        d.prototype = {
            version: "5.1.2",
            _init: function() {
                this._initEvents()
            },
            destroy: function() {
                this._initEvents(!0),
                this._execEvent("destroy")
            },
            _transitionEnd: function(a) {
                a.target == this.scroller && this.isInTransition && (this._transitionTime(), this.resetPosition(this.options.bounceTime) || (this.isInTransition = !1, this._execEvent("scrollEnd")))
            },
            _start: function(a) {
                if (! (1 != f.eventType[a.type] && 0 !== a.button || !this.enabled || this.initiated && f.eventType[a.type] !== this.initiated)) { ! this.options.preventDefault || f.isBadAndroid || f.preventDefaultException(a.target, this.options.preventDefaultException) || a.preventDefault();
                    var b, d = a.touches ? a.touches[0] : a;
                    this.initiated = f.eventType[a.type],
                    this.moved = !1,
                    this.distX = 0,
                    this.distY = 0,
                    this.directionX = 0,
                    this.directionY = 0,
                    this.directionLocked = 0,
                    this._transitionTime(),
                    this.startTime = f.getTime(),
                    this.options.useTransition && this.isInTransition ? (this.isInTransition = !1, b = this.getComputedPosition(), this._translate(c.round(b.x), c.round(b.y)), this._execEvent("scrollEnd")) : !this.options.useTransition && this.isAnimating && (this.isAnimating = !1, this._execEvent("scrollEnd")),
                    this.startX = this.x,
                    this.startY = this.y,
                    this.absStartX = this.x,
                    this.absStartY = this.y,
                    this.pointX = d.pageX,
                    this.pointY = d.pageY,
                    this._execEvent("beforeScrollStart")
                }
            },
            _move: function(a) {
                if (this.enabled && f.eventType[a.type] === this.initiated) {
                    this.options.preventDefault && a.preventDefault();
                    var b, d, e, g, h = a.touches ? a.touches[0] : a,
                    i = h.pageX - this.pointX,
                    j = h.pageY - this.pointY,
                    k = f.getTime();
                    if (this.pointX = h.pageX, this.pointY = h.pageY, this.distX += i, this.distY += j, e = c.abs(this.distX), g = c.abs(this.distY), !(k - this.endTime > 300 && 10 > e && 10 > g)) {
                        if (this.directionLocked || this.options.freeScroll || (this.directionLocked = e > g + this.options.directionLockThreshold ? "h": g >= e + this.options.directionLockThreshold ? "v": "n"), "h" == this.directionLocked) {
                            if ("vertical" == this.options.eventPassthrough) a.preventDefault();
                            else if ("horizontal" == this.options.eventPassthrough) return void(this.initiated = !1);
                            j = 0
                        } else if ("v" == this.directionLocked) {
                            if ("horizontal" == this.options.eventPassthrough) a.preventDefault();
                            else if ("vertical" == this.options.eventPassthrough) return void(this.initiated = !1);
                            i = 0
                        }
                        i = this.hasHorizontalScroll ? i: 0,
                        j = this.hasVerticalScroll ? j: 0,
                        b = this.x + i,
                        d = this.y + j,
                        (b > 0 || b < this.maxScrollX) && (b = this.options.bounce ? this.x + i / 3 : b > 0 ? 0 : this.maxScrollX),
                        (d > 0 || d < this.maxScrollY) && (d = this.options.bounce ? this.y + j / 3 : d > 0 ? 0 : this.maxScrollY),
                        this.directionX = i > 0 ? -1 : 0 > i ? 1 : 0,
                        this.directionY = j > 0 ? -1 : 0 > j ? 1 : 0,
                        this.moved || this._execEvent("scrollStart"),
                        this.moved = !0,
                        this._translate(b, d),
                        k - this.startTime > 300 && (this.startTime = k, this.startX = this.x, this.startY = this.y)
                    }
                }
            },
            _end: function(a) {
                if (this.enabled && f.eventType[a.type] === this.initiated) {
                    this.options.preventDefault && !f.preventDefaultException(a.target, this.options.preventDefaultException) && a.preventDefault();
                    var b, d, e = (a.changedTouches ? a.changedTouches[0] : a, f.getTime() - this.startTime),
                    g = c.round(this.x),
                    h = c.round(this.y),
                    i = c.abs(g - this.startX),
                    j = c.abs(h - this.startY),
                    k = 0,
                    l = "";
                    if (this.isInTransition = 0, this.initiated = 0, this.endTime = f.getTime(), !this.resetPosition(this.options.bounceTime)) return this.scrollTo(g, h),
                    this.moved ? this._events.flick && 200 > e && 100 > i && 100 > j ? void this._execEvent("flick") : (this.options.momentum && 300 > e && (b = this.hasHorizontalScroll ? f.momentum(this.x, this.startX, e, this.maxScrollX, this.options.bounce ? this.wrapperWidth: 0, this.options.deceleration) : {
                        destination: g,
                        duration: 0
                    },
                    d = this.hasVerticalScroll ? f.momentum(this.y, this.startY, e, this.maxScrollY, this.options.bounce ? this.wrapperHeight: 0, this.options.deceleration) : {
                        destination: h,
                        duration: 0
                    },
                    g = b.destination, h = d.destination, k = c.max(b.duration, d.duration), this.isInTransition = 1), g != this.x || h != this.y ? ((g > 0 || g < this.maxScrollX || h > 0 || h < this.maxScrollY) && (l = f.ease.quadratic), void this.scrollTo(g, h, k, l)) : void this._execEvent("scrollEnd")) : (this.options.tap && f.tap(a, this.options.tap), this.options.click && f.click(a), void this._execEvent("scrollCancel"))
                }
            },
            _resize: function() {
                var a = this;
                clearTimeout(this.resizeTimeout),
                this.resizeTimeout = setTimeout(function() {
                    a.refresh()
                },
                this.options.resizePolling)
            },
            resetPosition: function(a) {
                var b = this.x,
                c = this.y;
                return a = a || 0,
                !this.hasHorizontalScroll || this.x > 0 ? b = 0 : this.x < this.maxScrollX && (b = this.maxScrollX),
                !this.hasVerticalScroll || this.y > 0 ? c = 0 : this.y < this.maxScrollY && (c = this.maxScrollY),
                b == this.x && c == this.y ? !1 : (this.scrollTo(b, c, a, this.options.bounceEasing), !0)
            },
            disable: function() {
                this.enabled = !1
            },
            enable: function() {
                this.enabled = !0
            },
            refresh: function() {
                this.wrapper.offsetHeight;
                this.wrapperWidth = this.wrapper.clientWidth,
                this.wrapperHeight = this.wrapper.clientHeight,
                this.scrollerWidth = this.scroller.offsetWidth,
                this.scrollerHeight = this.scroller.offsetHeight,
                this.maxScrollX = this.wrapperWidth - this.scrollerWidth,
                this.maxScrollY = this.wrapperHeight - this.scrollerHeight,
                this.hasHorizontalScroll = this.options.scrollX && this.maxScrollX < 0,
                this.hasVerticalScroll = this.options.scrollY && this.maxScrollY < 0,
                this.hasHorizontalScroll || (this.maxScrollX = 0, this.scrollerWidth = this.wrapperWidth),
                this.hasVerticalScroll || (this.maxScrollY = 0, this.scrollerHeight = this.wrapperHeight),
                this.endTime = 0,
                this.directionX = 0,
                this.directionY = 0,
                this.wrapperOffset = f.offset(this.wrapper),
                this._execEvent("refresh"),
                this.resetPosition()
            },
            on: function(a, b) {
                this._events[a] || (this._events[a] = []),
                this._events[a].push(b)
            },
            off: function(a, b) {
                if (this._events[a]) {
                    var c = this._events[a].indexOf(b);
                    c > -1 && this._events[a].splice(c, 1)
                }
            },
            _execEvent: function(a) {
                if (this._events[a]) {
                    var b = 0,
                    c = this._events[a].length;
                    if (c) for (; c > b; b++) this._events[a][b].apply(this, [].slice.call(arguments, 1))
                }
            },
            scrollBy: function(a, b, c, d) {
                a = this.x + a,
                b = this.y + b,
                c = c || 0,
                this.scrollTo(a, b, c, d)
            },
            scrollTo: function(a, b, c, d) {
                d = d || f.ease.circular,
                this.isInTransition = this.options.useTransition && c > 0,
                !c || this.options.useTransition && d.style ? (this._transitionTimingFunction(d.style), this._transitionTime(c), this._translate(a, b)) : this._animate(a, b, c, d.fn)
            },
            scrollToElement: function(a, b, d, e, g) {
                if (a = a.nodeType ? a: this.scroller.querySelector(a)) {
                    var h = f.offset(a);
                    h.left -= this.wrapperOffset.left,
                    h.top -= this.wrapperOffset.top,
                    d === !0 && (d = c.round(a.offsetWidth / 2 - this.wrapper.offsetWidth / 2)),
                    e === !0 && (e = c.round(a.offsetHeight / 2 - this.wrapper.offsetHeight / 2)),
                    h.left -= d || 0,
                    h.top -= e || 0,
                    h.left = h.left > 0 ? 0 : h.left < this.maxScrollX ? this.maxScrollX: h.left,
                    h.top = h.top > 0 ? 0 : h.top < this.maxScrollY ? this.maxScrollY: h.top,
                    b = void 0 === b || null === b || "auto" === b ? c.max(c.abs(this.x - h.left), c.abs(this.y - h.top)) : b,
                    this.scrollTo(h.left, h.top, b, g)
                }
            },
            _transitionTime: function(a) {
                a = a || 0,
                this.scrollerStyle[f.style.transitionDuration] = a + "ms",
                !a && f.isBadAndroid && (this.scrollerStyle[f.style.transitionDuration] = "0.001s")
            },
            _transitionTimingFunction: function(a) {
                this.scrollerStyle[f.style.transitionTimingFunction] = a
            },
            _translate: function(a, b) {
                this.options.useTransform ? this.scrollerStyle[f.style.transform] = "translate(" + a + "px," + b + "px)" + this.translateZ: (a = c.round(a), b = c.round(b), this.scrollerStyle.left = a + "px", this.scrollerStyle.top = b + "px"),
                this.x = a,
                this.y = b
            },
            _initEvents: function(b) {
                var c = b ? f.removeEvent: f.addEvent,
                d = this.options.bindToWrapper ? this.wrapper: a;
                c(a, "orientationchange", this),
                c(a, "resize", this),
                this.options.click && c(this.wrapper, "click", this, !0),
                this.options.disableMouse || (c(this.wrapper, "mousedown", this), c(d, "mousemove", this), c(d, "mousecancel", this), c(d, "mouseup", this)),
                f.hasPointer && !this.options.disablePointer && (c(this.wrapper, f.prefixPointerEvent("pointerdown"), this), c(d, f.prefixPointerEvent("pointermove"), this), c(d, f.prefixPointerEvent("pointercancel"), this), c(d, f.prefixPointerEvent("pointerup"), this)),
                f.hasTouch && !this.options.disableTouch && (c(this.wrapper, "touchstart", this), c(d, "touchmove", this), c(d, "touchcancel", this), c(d, "touchend", this)),
                c(this.scroller, "transitionend", this),
                c(this.scroller, "webkitTransitionEnd", this),
                c(this.scroller, "oTransitionEnd", this),
                c(this.scroller, "MSTransitionEnd", this)
            },
            getComputedPosition: function() {
                var b, c, d = a.getComputedStyle(this.scroller, null);
                return this.options.useTransform ? (d = d[f.style.transform].split(")")[0].split(", "), b = +(d[12] || d[4]), c = +(d[13] || d[5])) : (b = +d.left.replace(/[^-\d.]/g, ""), c = +d.top.replace(/[^-\d.]/g, "")),
                {
                    x: b,
                    y: c
                }
            },
            _animate: function(a, b, c, d) {
                function g() {
                    var m, n, o, p = f.getTime();
                    return p >= l ? (h.isAnimating = !1, h._translate(a, b), void(h.resetPosition(h.options.bounceTime) || h._execEvent("scrollEnd"))) : (p = (p - k) / c, o = d(p), m = (a - i) * o + i, n = (b - j) * o + j, h._translate(m, n), void(h.isAnimating && e(g)))
                }
                var h = this,
                i = this.x,
                j = this.y,
                k = f.getTime(),
                l = k + c;
                this.isAnimating = !0,
                g()
            },
            handleEvent: function(a) {
                switch (a.type) {
                case "touchstart":
                case "pointerdown":
                case "MSPointerDown":
                case "mousedown":
                    this._start(a);
                    break;
                case "touchmove":
                case "pointermove":
                case "MSPointerMove":
                case "mousemove":
                    this._move(a);
                    break;
                case "touchend":
                case "pointerup":
                case "MSPointerUp":
                case "mouseup":
                case "touchcancel":
                case "pointercancel":
                case "MSPointerCancel":
                case "mousecancel":
                    this._end(a);
                    break;
                case "orientationchange":
                case "resize":
                    this._resize();
                    break;
                case "transitionend":
                case "webkitTransitionEnd":
                case "oTransitionEnd":
                case "MSTransitionEnd":
                    this._transitionEnd(a);
                    break;
                case "wheel":
                case "DOMMouseScroll":
                case "mousewheel":
                    this._wheel(a);
                    break;
                case "keydown":
                    this._key(a);
                    break;
                case "click":
                    a._constructed || (a.preventDefault(), a.stopPropagation())
                }
            }
        },
        d.utils = f,
        "undefined" != typeof module && module.exports ? module.exports = d: a.IScroll = d
    } (window, document, Math),
    function(a, b) {
        a.RichShare = b(a.$, a.Tmpl, a.IScroll)
    } (this,
    function(a, b, c) {
        function d() {
            r ? b(window.TmplInline_share.share_wechat, {
                barId: s
            }).appendTo(a(document.body)) : b(window.TmplInline_share.share, {
                barId: s
            }).appendTo(a(document.body)),
            k = a(".rich-share-mask"),
            k.on("tap",
            function(a) {
                a.target === k[0] && g()
            }).on("touchend",
            function(a) {
                a.preventDefault()
            }),
            r ? k.on("tap", ".btn-wechat-confirm",
            function() {
                g()
            }) : (l = k.find(".rich-share"), a(".btn-share-cancel").on("tap",
            function() {
                var b = a(this);
                b.addClass("active"),
                setTimeout(function() {
                    b.removeClass("active"),
                    g()
                },
                100)
            }).on("touchend",
            function(a) {
                a.preventDefault()
            }), k.on("tap", ".share-btn",
            function() {
                if (!q) {
                    var b = a(this),
                    c = b.data("index");
                    setTimeout(function() {
                        m && m.call(b, c),
                        o[c - t] && o[c - t].onTap.call(b, c),
                        g()
                    },
                    100)
                }
            })),
            n = !0
        }
        function e() {
            if (!r) {
                mqq.iOS && mqq.ui.setWebViewBehavior({
                    swipeBack: 0
                });
                var a = new c("#shareScroller", {
                    scrollX: !0,
                    scrollY: !1,
                    bindToWrapper: !0,
                    preventDefault: !1,
                    click: !1
                });
                a.on("scrollStart",
                function() {
                    q = !0
                }),
                a.on("scrollEnd",
                function() {
                    q = !1
                });
                var b = new c("#funcScroller", {
                    scrollX: !0,
                    scrollY: !1,
                    bindToWrapper: !0,
                    preventDefault: !1,
                    click: !1
                });
                b.on("scrollStart",
                function() {
                    q = !0
                }),
                b.on("scrollEnd",
                function() {
                    q = !1
                })
            }
            p = !0
        }
        function f(a) {
            n || d(),
            mqq.compare("5.8") > -1 && mqq.invoke("ui", "disableLongPress", {
                enable: !0
            }),
            k.show(),
            r || (setTimeout(function() {
                l.addClass("show"),
                p || e()
            },
            0), m = a ||
            function() {}),
            k.on("touchmove.share", h)
        }
        function g() {
            n && (r ? k.hide() : n && l && k && (l.removeClass("show"), setTimeout(function() {
                k.hide()
            },
            200)), mqq.compare("5.8") > -1 && mqq.invoke("ui", "disableLongPress", {
                enable: !1
            }), k.off("touchmove.share", h))
        }
        function h(a) {
            a.preventDefault(),
            a.stopPropagation()
        }
        function i(c, e) {
            var f = "";
            e && "object" == typeof e && "number" != typeof e.length && (f = e.hasOwnProperty("insertMode") ? e.insertMode: ""),
            n || (d(), o = c, setTimeout(function() {
                b.addTmpl("rich_share_btn", '<li class="share-btn" data-index="{{index}}"><div class="share-icon"><img soda-src="{{img}}"></div><p>{{text}}</p></li>');
                var d = a("#funcScroller").find("ul"),
                e = c.length,
                g = 75,
                h = j(d.find("li")) + 1 + 10;
                for (t = h; --e >= 0;) switch (c[e].index = h + e, f) {
                case "after":
                    var i = d.find("li"),
                    k = i.length;
                    a(b("rich_share_btn", c[e]).toString()).insertAfter(i[k - 1]);
                    break;
                default:
                    a(b("rich_share_btn", c[e]).toString()).insertBefore(d.find("li")[0])
                }
                d[0].style.width = g * d.find("li").length + "px"
            },
            0))
        }
        function j(b) {
            var c = 0;
            return b.each(function() {
                var b = a(this).data("index");
                c = b > c ? b: c
            }),
            c
        }
        var k, l, m, n = !1,
        o = [],
        p = !1,
        q = !1,
        r = navigator.userAgent.match(/\bMicroMessenger\/[\d\.]+/),
        s = Util.queryString("bid") || Util.getHash("bid"),
        t = 0;
        return {
            build: i,
            show: f,
            hide: g
        }
    }),
    function(a, b) {
        "function" == typeof define && define.amd ? define(b) : a.TmplInline_share = b()
    } (this,
    function() {
        var a = {},
        b = function(a) {
            function b(b) {
                return c("undefined" == typeof a[b] ? this[b] : a[b])
            }
            function c(a) {
                return "undefined" == typeof a ? "": a
            }
            a = a || {};
            var d = b("Util"),
            e = b("barId"),
            f = b("RegExp"),
            g = b("location"),
            h = b("$"),
            i = (b("barindex"), b("ios"), "");
            return i += '<div class="rich-share-mask"> <div class="rich-share"> <div class="rich-share-body"> <div id="shareScroller" class="rich-scroll-wrapper"> <ul class="share-btn-container"> <li class="share-btn" data-index="3"> <div class="share-icon"> <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAB4CAMAAAAOusbgAAAAWlBMVEUAAAAY0iIY0iEY0iEY0yEY0yEY0iAY0yEZ0yL///8d1SUSzh0f1ycY0iETzx4X0SAa0yMV0B8c1CQRzRy+88CG6Yt95oLw/fFM3VOh7qW48rvW99cz2Dtk4Wu7yqRCAAAACXRSTlMA8IjAV9wDICqM1nMdAAAEYklEQVRo3sXbjXKiMBSGYUgAPYGiUH6Sgvd/m0vd7X6rJJ4cXPGF6fTH+vSEqTPtkMRXccyU0jrP06VqU+lSnmutVHYskqgOSjOYtFSrA6cWma5eks4ezp3l1cvKs/Ai6+ql6UNg3LR6cal3aLV6nFmOymw6lryfVSu2UOuH/v5uaTDvu8rF/bxm6Tohzt8fG/GJdcL582RGsS7eyE7Mux7D3MtZZf7IODaw+F68g/OHzuAe0m/W/wzC4+aN56tLKX6rtLmG6/H0EXgmc5X134WGi5/zqSP4TEtY7CI3SAw8OgKuyQsMzKjbDq+LkbXZPf3tHswbOiywMm9I7Q9jrVPzhtIiOZq3dEwyI66yg+um8drUucFWRlwmvsS270a6a+x6a2QpGTxXpid/Uz8bQUry8mG/6AvyOhNfpeNh2xERZC/cRa+4TvLIRXZEBDkAE7nIBc8j4ctI9EAGTOMlEk5bvuo6LuTWL7ctfeeqls2kMfA8EbEyYJrmli0GtiMhyGGYRvvcxHAZGTBkdmKBy8mAedkkchdyGOZlBp7hPpIBQ56fgauJKEYGjKbqCdgRxcmAkdsOXwj5ZAMZMLqIYVxggQw46jIn53COiJXPkOl8ptvcOdwD2BJJZMDIboI7EspruJPA8nrAzMgMLJeD8NcZRcIzhepDMmA0i+E+wkV9AO6MGB6jXMiAGZeBbbQLGTDcwQrhPuiGZcBwqRfCU7QLGTBcmkLwh7fK734w3bofAy1V/ocGYCtxkcclK4IHiYs8Lg0i2Elc5HHJieDO4xoe9rjUieDJ43Y87HFpEsGjxyUe9rg0boThRsBwt8IeNwqGizZNDDcKhvskDDcW7lq4268x3EgYLg+fvE1wTz8undi68+nepenkLQB3KzcKbtcudSLY/XXbjuJhuMiJ4MHjksRFgwi2cJHERVYEG7hI4iITA6OJ4AIe5O50ksE9wQVMg9SlXgjb/oS/2gDTIHTJRsIILgGGHOmOJwZmXMA0SFzqg3ATaKZVTUNXuVkVdGluAgG+78sHQxYkhK0PFshSGHVBeJEj/1GxCbYeWChbCYzcGpbJrtkGV+MKFsljJYPRZQWL5EsjhJFbwQLZNdthM4VgXp6MHEbz6Id5eZybDTCyow/m5dE2DFwzQaa6ppVcQ751a6YkZR6AmQHfyB/dFjcFzMwMmJHhcjDbPAGOkae55ioBP6p1gHnZtTVfmuR1TJcR8GN5vNQx5YAfZ9wIOCyPztSRsK4jsx3gkNzZOq5SA46JHspfYNl0oiTw3E9hea7jaxTgyEI3oNSyVJLV4to/t9zQzy03bS0uS47lWzomSVq+obRIElW+IZ28CVYLfCjf0CFZ0uXu6eS7rNy97AoXeblzeZHsPzIG3v8q639u7C53DDd277zYGdxdX0UU1D3lWq02aHzukiqS+3aRlX8TzueLS7PQtqPPl6YPDzZafb6sPGO2ln2+JJ0VMZvp/i/q3UzHbh8st2Elt33wF7tIsNurPjupAAAAAElFTkSuQmCC"/> </div> <p>朋友圈</p> </li> <li class="share-btn" data-index="2"> <div class="share-icon"> <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAB4CAMAAAAOusbgAAAAYFBMVEUAAAAY0yEY0yEY0yEY0yEY0yEX0iAY0iEY0iH///8Szh0a1CMf1iYTzx4X0iAe1SUV0B8RzRwd1SUg1yf4/vjc+d6h7qWC6IdS3lns/Owr1zQ+2kZk4Wqy8bXG9ckj1Cz89queAAAACXRSTlMA8cBXeCPdA5nBuV0vAAAES0lEQVRo3sWb2ZajIBBAEVfUYJJWNrf//8uJMzlDFrQAW7xP3RD6WlWQDpyATORpnBQY4yx68ANRmxqjBxnGuEjiNEdWpAkGZK5EOEkhax7jn0PA8WbccfZzGFm8nmT8cyg4XQk3qg8mMgad1AFIvmdVUQeh+JxjR3u1OUSe4WzHdUBi7U2jOiCRXlW4Dgo+NtFwsvOsDkyW64DDEp9QYV3ltDmBZWInzQkkS6abE1hyHTUnEOWhS6yLHDenEFvOLTVwNnWSkFZ2E+ODanaSoOIOUQvWkU86Jur7DgqEgVf0TBIzkvV3XxoMiPuRbDF6q7fFPSMQTHl5fzDK1tPBWwIjeeNjzlC01qUmYsfkEXQdrYqFJLZI4W5+iCsTd9ESe9qhcqRZEw/ae4w5QsZmHa+tWbh573ejuJfEFdlXbpjEc0fc6eb9YkZ8YLvFgvghdoqb7j2Fg1KDbtromHaKh/c/r5Y2pQUbHcM+8btjeHkaqKNzEgMVVs+JTh6AHcJFDEzp2Sw2d7Ad4kYS71QT2fiLxcYcgjuEv5h/r5p51qsG6OD+Ykb2wPzFk5dQTpyLXs2Vv1i6WyfegxpY3LrGyhZrLf5uNNrnRkPU7mJHLX9kV/Hp83HbiasDI24XrRhbc+cotsX0HelQ255SMW29QNB1PsWTw9KhaiTbjMpaPFp/rqR0aC1eZivmll5BG2aXmMZOLGy9s/UOZ7YSN5JYMABeyKzFGkZgOK1GYs9Y2YiFRQgVZcQFZiOuOrDAPR1eDwX0scFq22AhpgP8/LPUDr0IN9rkDIrhkFv1uuZ6utATjbGNW4ipgAJWrf7tThfuRGNsaxUkhie20AGDEWu4jXhztygp1d1QjTWdjZj2civT/ccMrpYZDLb1NmK6cSLAKSc+cBvx1j8eQUcv8QiIwZgV7bzEHSAG69xQ6SWWgFizcrJHKfGiBcUa41nmHEBs/HSvfivV13UEMSCunpPr+s6WmBED/Oq5nOzFc/t8Vs661z/g+QZiLx6eu5Tq8bP6vznqaO8l7u3F079dyndHt6fEsFg9wmPK1MP3ZBoWczL2q4/kSqvsxaxffyafgGExzCwdvXL+HTEVjmJxBcTWOH6gv/6amLptYUziix+Vw6atuhhAkafZYZtqGF1GWuxKxSxPLIyjtdgDq6MI89ArIAZQI5jntaERyi57EBMQ8WweV2aQGFaPmwkfLmYyhC97MR8pDpIsMPOYEmvxHqqPQ9RqaeOLWlLzCIyKy2HMrCVEmPsKlFwORLG1XCcovhxKvyKOUVqeQopQVJ5AlCOEyxPACKGkPIEEocBF1iU+JdcYLcRlcOLnV2DLwGQ5Ch+yDjh8lfHLF7vLgOgvdgdOdqy9Qd9FEvRGUQai+LqgcQtCkaNPklsAEvMlnNvBRPHataPboeB046LV7TCyGLhadjsEHOc2l+l+Vwpcpvu+Pphl0QNP2YMs27g++AeC5KHP9Kwr8wAAAABJRU5ErkJggg=="/> </div> <p>微信</p> </li> <li class="share-btn" data-index="1"> <div class="share-icon"> <img src="data:image/png;base64,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"/> </div> <p>QQ空间</p> </li> <li class="share-btn" data-index="0"> <div class="share-icon"> <img src="data:image/png;base64,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"/> </div> <p>好友/群</p> </li> ',
            d.isFestival(e) || (i += ' <li class="share-btn" data-index="4"> <div class="share-icon"> <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAB4CAMAAAAOusbgAAAB2lBMVEUAAAD955773m/+6aT51kf+6af41kT510r+6KP95pT52Vb+6J7+6KL41kb+6aT51kf510n+6KH+6aX+6aj51kf510v51kn41kb41kT+6KL510n52FEEBAT+6KT41kb+6KHRAAj5103+6af510v41kn41UP95pj955z////95ZL944v74Hn955/52FDlIiD95ZT95I395pv41UX+//zgGRrjHh73oCf4//P733X84oT844j622H95JD52VbdExbmJCP+6aj6//f52FLhHB36//X73nHUBAv8//n52FT63Gf74Xz95Zb63Wr95pf84X7ZDBHaDxPoKCX52lr52Vj84YD8//v62lz62177oB3eFxnpKyf3oCT73m773Wz63GT5oCHXCg/gGxzWBw3SAQn3//L8nxhPUE77xlz4uEr70XL5pyzrior4rDiHiYbbMjXjXSn0083tdEXfRSIICAjriTjVERDz9PK/wb/wt7L824DldXT6y2jjQUMyMjHnPC3XGhT83Ij82Xj2qGjjX2D88vD27uPqTzjm5ubZ3db0wL76xHz0mmLzkFzwhVTtXEHfLyEaGxraKhf63t33sXL70oDvmkbxqkH2x03Q1M3sn53yp1empqWipp5ydXAKCYVOAAAAHHRSTlMA/gNXV/Hxw3rt7saa3yAgubbfy26hz5N+KiqA+z3TEAAAB4dJREFUaN680L1qwzAUBeBbCYGChMH25MEYPDg0BEIWTR6ymzxBh462hF4gdDEds6YP3EAS1z9ymgRJ33wuh3PBBCWY8jCOI3K2ewk5i+I45BQnCB4S0JDsrCIhDeAfCMe5EzG+uxtHuTMRnn8yy51iwcxckjtGjKNp6gGFMcRTLziCIZ56wn3/2fxtnHqEoROQzCMSwA3LvGLdozPPrs9GUeZZhC6D195dJrO1PVIrIZRumvpneyfG4Cx4s0eKP6qR1WwwAAC6tEeLAdW0M0EKAHxpj5hoVsZgCACLpT1aTKi6MAQJgmRrkRQGujUkE8Bbm6QWU0pOgxhoYVu5Oshaf/SIr0mIAi/caAfddTHCISxcqWSverR5HwLbu7OpRdcs9wMMWOXSoRut2qqPwWLj1Kq5Nety07N4trg8nr5Px/KJg+bzqh4Vvz/ul9SyWU0e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"/> </div> <p>新浪微博</p> </li> '),
            i += " </ul> </div> ",
            new f("bar_qrcode.html").test(g.pathname) || (i += ' <div class="share-sperator"></div> <div id="funcScroller" class="rich-scroll-wrapper" > <ul class="share-btn-container"> <li class="share-btn" data-index="6"> <div class="share-icon"> <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAB4CAMAAAAOusbgAAAAS1BMVEUAAACJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYl+mfk4AAAAGHRSTlMA8Q9WmqUDIb3H1YBnNxUrsuBFcgr4644OZM2wAAAEFUlEQVRo3tSZ2Y6jMBBFrzFe8YLZcv//S0dIEyF1J6E7gx3NeQMkDlXY5TLgETrLoR+dL5sQK99iFWIr3o39ILPGjzDzKHgpws4GJ2jpSNKrIUwxm5TSgrdYUkomxykMypOkky/jloWkkh0upZOKZJF4hrGkDRoV0MGS1jwJV9AHVCN4iodBD6TqUJFOkcP3XPRkj8rsDv393ITqTN+iG8iMBuQv2Zb14z1ilseREezRiJ7C4I6lQjMU7ZFo36EZi78nWxcGNCSw6L8BWzTF/g3ZMaApgQ4ADKnRFE0aADMVGqM4Axgp0ZBjVG1MaExHoZHp0RzPDEmF5ihKDBzQnF3aM6A5gT0sJzRnooVjxGuiKvwVRZ3ekg6e+WwBfYP+rBHxKDQnD8eb0fgNZjhraAzLaf1QvL0zake8InGDYIdXFJq3StPZ9VPxe2uXYPfvYlwvXriCRHsxyP9S3KXPiLNY40fEirSfEKeVZP6AeKDq2bcX641T5to1F0taYOTcXOwYdnvRjcWR7tgGNRMfq+VM11acVqZ7h9xUPNy78RtVG/Exl+5NzJraibuZFsfLTnXFXY4y7N/Py0pSHsObXMv+RXyWU06Xi2fBAzFK4E4cNx6st4vFG4XfwwoymgVfWMyejJuyfiP1teKB7HGKJPur3/H8A3NP3vTVYsiVSp955xrTKQqOCU9ZFFdZZx7nQmfwhGS5xVoFJDmWjIcYT2/qVa5lfLIPjBtdQgXxya+LSI5L5Vo9k+ZBO3LTqCyGony0wUd1sWN8sPn7077ZrTgIA2G0mvhvNJpozvs/6dLaJXQJpFs2prDNVZWBA59aNHOmTg62GzYQtUoOVqG3LIdJDjahnUBBlRysEcFdnORgv0s0Vt0Q2KJJA/ZPjtIAnfDPWFrweFxNK2ERqgfdHFfeJQZLxitnYXP22ggvQPp7PSG4Z76sLbTzcTzJW941m00KnqCUj6KB6kDPLWtS8Ipe2Ab7KB7sIBmSgkdAN/7Y541MG3XXi1CVanv1D3b2PuAP+AP+M7C1WcDNsBeuOR28VhsAen0R/FL7x449UClVAf1YvtL+eQHcuAK6Ybr+noYOfOIpG16rBlrhT4jbibX+LXhneh5c3jN+LJklsJjyefDEHm1aQjDjx2VNC4WcnwU3LNHG9fc3Wn3LWItQjU9c1Efxho00rmOt+rtDUJoekMFSn3gPi5mOt91Yqz4qJxh2oWQB7VBG7SHTwiaVWHAxOSGuY2jwGUfXei+P6xhxAWWsOjf/whBwnTZxASWbcpNNMsqmVeUSybKpc9lkwVx6ZDYhNJsCm0v6zaU55xe7L4L0/yJh0gjqcsJS/LyR3VnjCu5dBjROGEmxFQzvNIRzxtjRuw1a5Rkt84Gb9oxhumzjg1/FdI8qFJiiRQAAAABJRU5ErkJggg=="> </div> <p>收藏</p> </li> <li class="share-btn" data-index="5"> <div class="share-icon"> <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAB4BAMAAADLSivhAAAALVBMVEUAAACJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYkmznqQAAAADnRSTlMAEdfwQyVaowOUa8SAuqV0iIwAAAI7SURBVFjD7NW/SwJxGMfxz5W/ModTiLZIWoLghixoOoSotjBqaBKHhoYQg8YKCQpsamw6HFuK6A84nGqTtqBB6A4VO33+hvh6w3F9E+6ewCF6bwovPno8cPC6PDp7WsjpNDI7t7z09rwPuei9ToGy3wvf7ZVJ1unBxk5axciU9Mze5otGziF8xTQ6UREoZYusku83m84dAnfdcgrwWrfqCFFSW/E+pOgYoZqlBuCWMHoImdGrwm2K8mFxhG7g1mwjdMU+hkXCDwtUgmi3B0bGKkStCgfHB8PjslQOVrQSgItPsHpYBFB+5OHpDgCzzsNJB4hSlYcTVECyC2bndcQ7XLz9iskPLp5bQ3GeiyfaKFeYVvzhZoOLU33xzJjFumjluTgygMnHDrQCF0et32Fdha+aLr+dbn/Eii1hg+T6QbGel5+MHRQT5GgUJnAx6C9jjAvLh5YNjuVDs9SgWD400rKhMPm+y1gqHytalo8hptlYTLOxmGZjMc3HYpqNxTQfi2k2FtMs7N5qxmFhg9xYuKZzsde4Mf3jr/bM2ARAIAiCmbHVGB72YjU2Y1VGitZgtIiIwe2A8M9fPoEgys5UD586BJf1zA1GcFHfsN/gGG1YvxYTnsOGdQ3+hKM3Ye0qDieG3RtOTErB9wTOjVm+n9HsR8IBqQ4kWZDeQWIJKS0k05DGQwKRqEskTZGuRaIYKOqdyXEZ9vR1cvnLarxeG0oRKIKw/KKbsuFneCSnw0hOIHaBzMYDH0qLFzbSsFq2pIuCAAAAAElFTkSuQmCC"/> </div> <p>复制链接</p> </li> ', new f("barindex").test(g.pathname) && (i += ' <li class="share-btn" data-index="8"> <div class="share-icon"> <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAB4BAMAAADLSivhAAAAKlBMVEUAAACJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYm80rTtAAAADXRSTlMA8BEl11pDo2vEgLqULpK/ewAAAgFJREFUWMPt2b9LAmEYB/BvXqZFB0U/NkFqaAqsoS2oseGgloYgKCKagmhoKAL3CBpahZZoEtpqEfoHBM0zPfX5X/LHvR0qwr3PY5dW3+V4xY8/uPc47vvAi7F3db85HaOeyU/Pbr08JtAd4yZGvpJ/7eLhFNmXu0erC3H0TGhhce34ySJnu8NadBGHr4ROyG7TRspJwneiGScBL4e2sv60Ne8tJugcWlmiB6hkaxqwQ0zSgS4eoze0ki5AO+sV9SlaX6xQ63St1MBIdqZ5yJxxcKTa3Fx2nINDVuN3j5bByu0GgP07Hh4vAkgleTjqAAaBGUogWuLi6yQiRS4+fcbIOxcvT2F9g4tHC9g/4+JIEekHLp6o4DrJxeESMgdcPFZFio8dWAkuNmwZjsW5OJQfVkxgh/qNSQVmzsx5y0HHuebBdLFaDgEmIoXd5T/2h70l41R9LYcAu1HYzaDjXhlwTCodr30n7o5ZR+4uCQC33t5Q/cN/ZpOY5CVQ7EX7LinHXgLBRCTHZk6ISSVgHPR/rmdwsOaFIcbuUYp/913SPx7WhzIJ/rnnZ9Fjv6hwEFUdopJFVO+IiiVRpSUq00Q1nqhAlFSXotJUVNeKimJBRe3IynHVsGsnrLr82w/G9iqLRhHSIYh8/IId3cHPXNvIqcoYOXGHXbIxm3zAJxotfgJfUQp9b27e0QAAAABJRU5ErkJggg=="/> </div> <p>部落二维码</p> </li> ', h.os.ios && (i += ' <li class="share-btn" data-index="7"> <div class="share-icon"> <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAB4CAMAAAAOusbgAAAAS1BMVEUAAACJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYmJiYl+mfk4AAAAGHRSTlMA7xJ/vdQD4mzGVEioXSML/vculhyxnzzerajoAAAEBUlEQVRo3uzVyY7bMBBF0UdxHkRq1vv/L03spINuWG43DKlWORsB2lyIRZRwxE1Jb2u0Xik18i2jUsrbuG46TQ4/UoNRPJUyoeIFl+JA0hbd532qXdc1vKV1XVenPfe6WJJD7B2+0XuSW2o4VUsbSd/jmcWQJjhcwAVDmgWHkqINuEywVAkH9MDScKFWOOjHs5jJGRe7Ndzju4zL5Yev0+QEAROp8UkamCEic0j4Z1GcIWSmqvhgWCCm0OCvRNsgplkm3DnPAEGB3uGmp4Eowx43kQGiAiN+qwMdRDkO9d7fIGxjAFCYICzRAFDsIKxxdKi0EGc5IbFAXGEPTQ1p9+jGHuJ6bjDMEJdpELlD3M54v2DiJlp4Vohb6O/7Q1xH9T/8M9qMJgHIv5/6vWUN8p3f2s2MmTfbG2XynfDCMbcwcOOgWx65SIV7FgBp5JgAFCap8ETrAOx+B+AsJ6kwIteGP9pKA7Fw9YwdbrpIX+XCqJYrblbaCrlwZ2gX3CyWphMLt/Xe/SivTSq80i/4sHiuQuFCX7/etCIS3qgmfDYpbgLhmWPGV3nkfHl459A/7tCB+eJwVdR4pKnqpeEWWXCkMLYrw4W24UizLBeGZ6r6fAbzZeGdQ8YzeeB+UbhZajynads14cLo8JyLLJeEA9WC7yyK4YLwotjjez3Vcn54ZcErhevp4UDf4ZXOM5wc7jwTXkv03Znh40N02h0N5NRwOro2mvroCqYzw5HhYFUdLbLAeGJY07qDqfNgos5SnxS+N/LBNH9Vb247DsIwEG2BbJOGQC5c5v+/dKtlpbaxpZUSFtO89aE5GewYCY+DC0zkLW7jXuCF2d8B+qLBhKBD2gkcEQbmLdj9QOh7MnoM+4B7RkK3pfn04DMPqN8FvCJMzIN2v0lMK/gUsO4BnmmaNg+hT+kNvQRzCZgKJjsvz6gPAQs5V8BaD+7RMunTvsjzkZFcDY5MhM3rVR1vMIzkWAtekGjhxj3/ma2EpRLcwA95UcwlGtzygjp4NBVgPloasHkbXDNXoQ5sYIlgUjM6KtmiqwLfoRjBK7lx0PR4tgY8Q7OC/5bcoq8AT7R4uE0wkezyv3o0hWD+2AqGTQXDpFc5mLaGLHBncwErTa9ycMBIdGzRpJFf6LfwcrBBJJFr+aCQyhqhysEJKub78ynThOxEUSFVXCeD8mWqKldS75vZ+cpSrrN9P6SieilYYAEyfacR4cMaXh8NlmrjijWuxVr1YuYEMTuGnAHFCVluxExGYrYqKSOZmHVOzCwoZY8UM4SKWWCFTL9OyuYsb+y+WH/UO8plH7n1ceZ9nT8Bf8S4gkc6y4DGUSMpZxrCOWDsaDjboNVjfbn/Gy075zDdceOD3/rmehFhRIOxAAAAAElFTkSuQmCC"/> </div> <p>添加到桌面</p> </li> '), i += " "), i += " </ul> </div> "),
            i += ' <div style="clear:both;"></div> <div class="btn-share-cancel">取消</div> </div> </div> </div> '
        };
        a.share = "TmplInline_share.share",
        Tmpl.addTmpl(a.share, b);
        var c = function(a) {
            a = a || {};
            var b = "";
            return b += '<div class="rich-share-mask wechat"> <div class="wechat-arrow"></div> <div class="share-wechat-title"></div> <div class="btn-wechat-confirm">我知道了</div> </div>'
        };
        return a.share_wechat = "TmplInline_share.share_wechat",
        Tmpl.addTmpl(a.share_wechat, c),
        a
    }),
    function(a, b) {
        a.ABTest = b()
    } (this,
    function() {
        function a() {
            var a = +Date.now(),
            b = Number(window.localStorage.getItem(e));
            b && d >= a - b || DB.cgiHttp({
                type: "GET",
                url: c,
                param: {},
                succ: function(a) {
                    0 === a.retcode && a.result && (window.localStorage.setItem(e, +Date.now()), 1 === ~~a.result.isGray ? window.localStorage.setItem(f, 1) : window.localStorage.removeItem(f))
                }
            })
        }
        function b() {
            var a = ~~window.localStorage.getItem(f);
            return a && Q.monitor(630457),
            console.log("当前用户是否灰度用户：", a ? "是": "否"),
            a
        }
        var c = "/cgi-bin/bar/extra/is_gray",
        d = 108e6,
        e = "_ab_test_check_time",
        f = "_is_in_ab_test";
        return a(),
        {
            isInTest: b
        }
    }),
    function(a, b) {
        window[a] = b()
    } ("XYPAY",
    function() {
        var a = "http://i.gtimg.cn/club/platform/lib/pay/smartpay.simplify.js",
        b = "http://imgcache.gtimg.cn/club/platform/lib/pay/smartpay.lib.util.js?_bid=193",
        c = null,
        d = function(a) {
            var b = localStorage.getItem("__payment_result");
            try {
                b = JSON.parse(b)
            } catch(d) {
                b = ""
            }
            a.hidden === !1 && b && $.isPlainObject(b) && (localStorage.setItem("__payment_result", ""), c && c(b))
        };
        return {
            openVip: function(e, f) {
                $.isFunction(e) && (f = e, e = void 0);
                var g = e && e.aid || "xylm.pingtai.client.qianbao.kaitong",
                h = encodeURIComponent(e && e.from || ""),
                i = encodeURIComponent(e && e.privilege || "");
                "ios" === e.device ? (c = f, mqq.ui.openUrl({
                    url: "http://xiaoqu.qq.com/mobile/xy_payment.html?_wv=1027&aid=" + g + "&from=" + h + "&privilege=" + i,
                    target: 1
                }), mqq.removeEventListener("qbrowserVisibilityChange", d), setTimeout(function() {
                    mqq.addEventListener("qbrowserVisibilityChange", d)
                },
                100)) : loadjs.load(b,
                function() {
                    loadjs.load(a,
                    function() {
                        qw.smartPay.openService({
                            aid: g,
                            type: "xyvip",
                            month: e && e.month || "3",
                            onPayCallback: function(a) {
                                f && f(a)
                            }
                        })
                    })
                })
            }
        }
    });