function(a,b){
	a.Config = b(a.$,a.Config)
}(this,function(){
	var a = {
			globalOnError: !0,
			isOffline: !1,
			checkOpen: !1
	};
	return a.FACE_2_TEXT = ["微笑", "撇嘴", "色", "发呆", "得意", "流泪", "害羞", "闭嘴", "睡", "大哭", "尴尬", "发怒", "调皮", "呲牙", "惊讶", "难过", "酷", "冷汗", "抓狂", "吐", "偷笑", "可爱", "白眼", "傲慢", "饥饿", "困", "惊恐", "流汗", "憨笑", "大兵", "奋斗", "咒骂", "疑问", "(嘘[.]{3}|嘘)", "晕", "折磨", "衰", "骷髅", "敲打", "再见", "擦汗", "抠鼻", "鼓掌", "糗大了", "坏笑", "左哼哼", "右哼哼", "哈欠", "鄙视", "委屈", "快哭了", "阴险", "亲亲", "吓", "可怜", "菜刀", "西瓜", "啤酒", "篮球", "乒乓", "咖啡", "饭", "猪头", "玫瑰", "凋谢", "示爱", "爱心", "心碎", "蛋糕", "闪电", "炸弹", "刀", "足球", "瓢虫", "便便", "月亮", "太阳", "礼物", "拥抱", "强", "弱", "握手", "胜利", "抱拳", "勾引", "拳头", "差劲", "爱你", "NO", "OK", "爱情", "飞吻", "跳跳", "发抖", "怄火", "转圈", "磕头", "回头", "跳绳", "挥手", "激动", "街舞", "献吻", "左太极", "右太极", "双喜", "鞭炮", "灯笼", "发财", "K歌", "购物", "邮件", "帅", "喝彩", "祈祷", "爆筋", "棒棒糖", "喝奶", "下面", "香蕉", "飞机", "开车", "高铁左车头", "车厢", "高铁右车头", "多云", "下雨", "钞票", "熊猫", "灯泡", "风车", "闹钟", "打伞", "彩球", "钻戒", "沙发", "纸巾", "药", "手枪", "青蛙", "左车头", "右车头", "嘘", "嘘..."],a
}),
$.str = function(){
	function a(a){
		var b = {};
		return a >= 0 && (b["&quot;"] = 34,b["&amp;"] = 38,b["&apos;"] = 39, b["&lt;"] = 60, b["&gt;"] = 62, b["&nbsp;"] = 160),
		a >= 1 && (b["&iexcl;"] = 161, b["&cent;"] = 162, b["&pound;"] = 163, b["&curren;"] = 164, b["&yen;"] = 165, b["&brvbar;"] = 166, b["&sect;"] = 167, b["&uml;"] = 168, b["&copy;"] = 169, b["&ordf;"] = 170, b["&laquo;"] = 171, b["&not;"] = 172, b["&shy;"] = 173, b["&reg;"] = 174, b["&macr;"] = 175, b["&deg;"] = 176, b["&plusmn;"] = 177, b["&sup2;"] = 178, b["&sup3;"] = 179, b["&acute;"] = 180, b["&micro;"] = 181, b["&para;"] = 182, b["&middot;"] = 183, b["&cedil;"] = 184, b["&sup1;"] = 185, b["&ordm;"] = 186, b["&raquo;"] = 187, b["&frac14;"] = 188, b["&frac12;"] = 189, b["&frac34;"] = 190, b["&iquest;"] = 191, b["&Agrave;"] = 192, b["&Aacute;"] = 193, b["&Acirc;"] = 194, b["&Atilde;"] = 195, b["&Auml;"] = 196, b["&Aring;"] = 197, b["&AElig;"] = 198, b["&Ccedil;"] = 199, b["&Egrave;"] = 200, b["&Eacute;"] = 201, b["&Ecirc;"] = 202, b["&Euml;"] = 203, b["&Igrave;"] = 204, b["&Iacute;"] = 205, b["&Icirc;"] = 206, b["&Iuml;"] = 207, b["&ETH;"] = 208, b["&Ntilde;"] = 209, b["&Ograve;"] = 210, b["&Oacute;"] = 211, b["&Ocirc;"] = 212, b["&Otilde;"] = 213, b["&Ouml;"] = 214, b["&times;"] = 215, b["&Oslash;"] = 216, b["&Ugrave;"] = 217, b["&Uacute;"] = 218, b["&Ucirc;"] = 219, b["&Uuml;"] = 220, b["&Yacute;"] = 221, b["&THORN;"] = 222, b["&szlig;"] = 223, b["&agrave;"] = 224, b["&aacute;"] = 225, b["&acirc;"] = 226, b["&atilde;"] = 227, b["&auml;"] = 228, b["&aring;"] = 229, b["&aelig;"] = 230, b["&ccedil;"] = 231, b["&egrave;"] = 232, b["&eacute;"] = 233, b["&ecirc;"] = 234, b["&euml;"] = 235, b["&igrave;"] = 236, b["&iacute;"] = 237, b["&icirc;"] = 238, b["&iuml;"] = 239, b["&eth;"] = 240, b["&ntilde;"] = 241, b["&ograve;"] = 242, b["&oacute;"] = 243, b["&ocirc;"] = 244, b["&otilde;"] = 245, b["&ouml;"] = 246, b["&divide;"] = 247, b["&oslash;"] = 248, b["&ugrave;"] = 249, b["&uacute;"] = 250, b["&ucirc;"] = 251, b["&uuml;"] = 252, b["&yacute;"] = 253, b["&thorn;"] = 254, b["&yuml;"] = 255),
		b
	}
	
	var b = {},
	c = {},
	d = {},
	e = {},
	f = function(c){
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
}(),
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
