!window.YybJsBridge = function(a, b, c) {
    function d() {
        var a = document.createElement("iframe");
        return f.push(a),
        a.style.cssText = "position:absolute;left:0;top:0;width:0;height:0;visibility:hidden;",
        a.frameBorder = "0",
        document.body.appendChild(a),
        a
    }
    function e() {
        var b = i;
        i = [],
        j = 0,
        a.getAppInstalledVersion(b, function(a) {
            var b;
            for (var c in a)
                (b = k._instances[c]) && ((!b.args.queryInstalledVersionCode || a[c] >= b.args.queryInstalledVersionCode) && b.state == k.STATE_READY && b._callback(k.STATE_INSTALLED),
                b.args.versionCode && b.args.versionCode > a[c] && (b.args.isUpdate = !0))
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
            for (var c = 0, d = Math.max(a.length, b.length); d > c; c++) {
                var e = isFinite(a[c]) && Number(a[c]) || 0
                  , f = isFinite(b[c]) && Number(b[c]) || 0;
                if (f > e)
                    return !1;
                if (e > f)
                    return !0
            }
        } catch (g) {
            return !1
        }
        return !0
    }
    ;
    var f = []
      , g = {};
    a._callWithScheme = function(a, b) {
        console.log("YybJsBridge._callWithScheme: ", a);
        for (var c, e = 0; (c = f[e]) && c._busy; e++)
            ;
        (!c || c._busy) && (c = d()),
        c._busy = !0,
        g[b] = c,
        c.src = a
    }
    ,
    a._callback = function(a) {
        g[a] && (g[a]._busy = !1,
        delete g[a])
    }
    ,
    a.ready = !1;
    var h = [];
    a.onReady = function(b) {
        a.ready ? b && b() : b && h.push(b)
    }
    ,
    a._readyCallback = function() {
        if (!a.ready) {
            a.ready = !0;
            for (var b, c = 0; b = h[c]; c++)
                b()
        }
    }
    ;
    var i = []
      , j = 0
      , k = function(a, b, c) {
        this.args = a || {},
        this.callback = b,
        this.context = c,
        this.identifier = 0,
        this.state = 1,
        this.percentage = 0,
        k._instances[this.args.packageName] = this,
        this._init(),
        i.push(this.args.packageName),
        j || (j = setTimeout(e, 0))
    }
    ;
    return k._instances = {},
    k.HAS_PERCENTAGE = !0,
    k.STATE_READY = 1,
    k.STATE_QUEUING = 2,
    k.STATE_DOWNLOADING = 3,
    k.STATE_PAUSED = 4,
    k.STATE_DOWNLOADED = 5,
    k.STATE_INSTALLING = 6,
    k.STATE_INSTALLED = 7,
    k.STATE_FAILED = 8,
    k._getDownloadState = function(a, b) {
        return a = k._stateMap[a],
        b && a == k.STATE_FAILED && (a = k.STATE_READY),
        a
    }
    ,
    k.prototype._callback = function(a, b) {
        if (a) {
            if (this.state == k.STATE_INSTALLED && a >= k.STATE_QUEUING && a <= k.STATE_INSTALLED)
                return;
            switch (this.state = a,
            a) {
            case k.STATE_READY:
            case k.STATE_FAILED:
                this.percentage = 0;
                break;
            case k.STATE_DOWNLOADED:
            case k.STATE_INSTALLING:
            case k.STATE_INSTALLED:
                this.percentage = 100
            }
        }
        b && ((this.state == k.STATE_DOWNLOADING || this.state == k.STATE_PAUSED) && k.HAS_PERCENTAGE && isFinite(b.percentage) && (this.percentage = b.percentage),
        b.identifier && (this.identifier != b.identifier ? (delete k._instances[this.identifier],
        k._instances[this.identifier = b.identifier] = this) : k._instances[this.identifier] != this && (k._instances[this.identifier] = this))),
        this.callback && this.callback.call(this, this.state, this.percentage, this.context, b)
    }
    ,
    k.prototype.doAction = function() {
        switch (this.state) {
        case k.STATE_QUEUING:
        case k.STATE_DOWNLOADING:
            this.stop();
            break;
        case k.STATE_DOWNLOADED:
            this.install();
            break;
        case k.STATE_INSTALLED:
            this.args.isUpdate ? this.start() : a.startApp(this.args.packageName);
            break;
        default:
            this.start()
        }
    }
    ,
    k.prototype.dispose = function() {
        return delete k._instances[this.identifier],
        delete k._instances[this.args.packageName],
        !0
    }
    ,
    a.Download = k,
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
    }
    ,
    a
}(window.YybJsBridge, window);