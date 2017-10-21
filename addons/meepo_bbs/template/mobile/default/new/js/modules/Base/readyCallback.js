!function(a, b, c) {
    function d(b, c, d) {
        var e = ["jsb:/", b, g, "YybJsBridge.callback?"].join("/")
          , f = [];
        for (var i in c)
            f.push(encodeURIComponent(i) + "=" + encodeURIComponent(c[i] + ""));
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
            var g = 1
              , h = {}
              , i = []
              , j = 0
              , k = a._call = function(b, c, e) {
                a.allowBatchCall ? (i.push({
                    name: b,
                    args: c,
                    onCallback: e
                }),
                j || (j = setTimeout(function() {
                    if (j = 0,
                    1 == i.length)
                        d(i[0].name, i[0].args, i[0].onCallback);
                    else {
                        for (var b, c = [], e = 0; b = i[e]; e++)
                            c.push({
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
                }, 0))) : d(b, c, e)
            }
            ;
            a.callback = function(b) {
                console.log("YybJsBridge.callback: ", b),
                h[b.seqid] && (a._callback(b.seqid),
                h[b.seqid].cb && h[b.seqid].cb(b),
                delete h[b.seqid])
            }
            ;
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
                }, function(b) {
                    if (0 == b.result) {
                        var c = JSON.parse(b.data)
                          , d = l._getDownloadState(c.appstate, !0);
                        if (d == l.STATE_INSTALLED)
                            return void a._callback(d);
                        var e = c.downpercent;
                        k("createDownload", {
                            appid: a.args.hnAppId,
                            packagename: a.args.packageName
                        }, function(b) {
                            if (0 == b.result) {
                                var c = JSON.parse(b.data);
                                a._callback(d, {
                                    identifier: c.apkid,
                                    percentage: e
                                })
                            } else
                                a._callback(l.STATE_FAILED)
                        })
                    } else
                        delete l._instances[a.identifier],
                        a.identifier = 0,
                        a._callback(l.STATE_READY)
                })
            }
            ,
            l.prototype._onResume = function() {
                this._init()
            }
            ,
            l.prototype.start = function() {
                switch (this.state) {
                case l.STATE_INSTALLED:
                    if (!this.args.isUpdate)
                        break;
                case l.STATE_READY:
                case l.STATE_FAILED:
                    if (!this.identifier || this.state == l.STATE_INSTALLED) {
                        var a = this;
                        k("createDownload", {
                            appid: this.args.hnAppId,
                            packagename: this.args.packageName,
                            reCreate: this.state == l.STATE_INSTALLED ? 1 : 0
                        }, function(b) {
                            var c = l.STATE_READY;
                            if (0 == b.result) {
                                var d = JSON.parse(b.data);
                                a._callback(c, {
                                    identifier: d.apkid
                                }),
                                a.start()
                            } else
                                a._callback(l.STATE_FAILED)
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
                return !0
            }
            ,
            l.prototype.stop = function() {
                return k("pauseDownload", {
                    apkid: this.identifier
                }),
                !0
            }
            ,
            l.prototype.install = function() {
                return k("startDownload", {
                    apkid: this.identifier
                }),
                !0
            }
            ,
            b.stateCallback = function(a) {
                if (console.log("window.stateCallback: ", a),
                0 == a.result) {
                    var b = JSON.parse(a.data)
                      , c = l._getDownloadState(b.appstate);
                    if (b.apkid && c) {
                        var d = l._instances[b.apkid];
                        d && d._callback(c, {
                            percentage: b.down_percent,
                            speed: b.speed
                        })
                    }
                }
            }
            ,
            b.appInstallUninstall = function(a) {
                if (0 == a.result) {
                    var b = JSON.parse(a.data)
                      , c = l._instances[b.packageName];
                    c && c._callback(1 == b.state ? l.STATE_INSTALLED : l.STATE_READY)
                }
            }
            ,
            a.getAppInstalledVersion = function(a, b) {
                return k("getAppInfo", {
                    packagenames: a.join(","),
                    noupdateinfo: 1
                }, function(a) {
                    var c = {};
                    if (0 == a.result) {
                        var d = JSON.parse(a.data);
                        for (var e in d)
                            1 == d[e].install && (c[e] = d[e].verCode)
                    }
                    b && b(c)
                }),
                !0
            }
            ,
            a.startApp = function(a, b) {
                return k("startOpenApp", {
                    packageName: a,
                    scene: b
                }),
                !0
            }
            ,
            a._showShareButton = function() {
                k("setWebView", {
                    buttonVisible: 1
                })
            }
            ,
            a._hideShareButton = function() {
                k("setWebView", {
                    buttonVisible: 0
                })
            }
            ,
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
            }
            ,
            b.clickCallback = function() {
                a.share()
            }
            ,
            a.showPictures = function(a, b) {
                return k("showPics", {
                    urls: JSON.stringify(a),
                    position: isFinite(b) && b >= 0 && b < a.length ? b : 0
                }),
                !0
            }
            ,
            a.openNewWindow = function(a, b) {
                return b = b || {},
                b.url = a,
                k("openNewWindow", b),
                !0
            }
            ;
            var n;
            a.login = function(a) {
                return k("openLoginActivity", {
                    logintype: "QMOBILEQ"
                }),
                n = a,
                !0
            }
            ,
            b.loginCallback = function(a) {
                n && n(a),
                n = c
            }
            ;
            var o = []
              , p = 0;
            a._callBatch = function(b, c) {
                for (var d, e = [], f = 0; d = c[f]; f++)
                    e.push({
                        method: b,
                        seqid: g++,
                        args: d
                    });
                var h = ["jsb://callBatch", g++, "YybJsBridge.callback?param="].join("/");
                h += encodeURIComponent(JSON.stringify(e)),
                a._callWithScheme(h)
            }
            ,
            a.report = function(a) {
                o.push(a || {}),
                !p && (p = setTimeout(e, 1e3))
            }
            ,
            a.reportImmediate = function(a) {
                k("report", a)
            }
            ;
            var q;
            a.onResume = function(a) {
                q = a
            }
            ,
            b.activityStateCallback = function(a) {
                if ("onResume" == a.data) {
                    "function" == typeof q && q();
                    var b = m;
                    m = [];
                    for (var c, d = 0; c = b[d]; d++)
                        c._onResume()
                }
            }
            ,
            b.userFitCallback = function() {}
            ,
            b.readyCallback = a._readyCallback
        }
    }
}(window.YybJsBridge, window);