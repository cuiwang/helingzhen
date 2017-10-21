!function(a, b) {
    a.Detail = new b
}(this, function() {
    function a() {
        ("share_app" === Util.queryString("source") || "share_app" === Util.getHash("source")) && (console.log("显示下载引导模块"),
        window.InvokeApp.buildGuide("post_detail")),
        c.isstargroup && $("body").addClass("stargroup"),
        $("#to_like").addClass("bid_" + c.bid),
        $.os.ios ? ($("#js_detail_main").css("position", "absolute"),
        bouncefix.add("detail-main"),
        bouncefix.add("page-icon-left")) : document.body.style.overflowY = "scroll"
    }
    function b(a, b) {
        mqq.device.getDeviceInfo(function(c) {
            var d = c.identifier;
            mqq.invoke("sso", "sendRequest", {
                cmd: "video_token",
                data: JSON.stringify({
                    vid: a,
                    guid: d
                }),
                callback: mqq.callback(function(a) {
                    console.log("视频sso获取key返回数据"),
                    console.log(a),
                    b && b(a)
                })
            })
        })
    }
    var c = this
      , d = window.Q
      , e = {
        0: "detail",
        100: "activity",
        101: "qunactivity",
        200: "live",
        201: "live",
        600: "pk",
        900: "music"
    };
    this.getTplName = function(a) {
        return e[a] || "detail"
    }
    ,
    this.report = function(a, b, e) {
        if (!this.isRenderFromLocal) {
            var f = {
                opername: "Grp_tribe",
                module: "post_detail",
                ver1: c.bid,
                obj1: c.pid,
                action: a
            };
            for (var g in b)
                b.hasOwnProperty(g) && (f[g] = b[g]);
            d.tdw(f, e)
        }
    }
    ,
    this.openActReport = function(a, b) {
        if (!this.isOpenAct)
            if ("number" == typeof a)
                d.monitor(a);
            else {
                var c = {
                    opername: "Grp_ac_mobile",
                    module: "detail",
                    action: a,
                    obj2: "M_WEB"
                };
                for (var e in b)
                    b.hasOwnProperty(e) && (c[e] = b[e]);
                d.tdw(c)
            }
    }
    ,
    this.openActNewReport = function(a, b) {
        var e = {
            opername: "Grp_ac_mobile",
            module: "detail_open",
            action: a,
            uin: c.postData.user_info.uin,
            obj1: c.postData.post.gid,
            ver4: Util.queryString("from")
        };
        $.extend(e, b),
        d.tdw(e)
    }
    ,
    this.setOpenActStatus = function(a, b) {
        var c = +new Date
          , d = a.post;
        d.act_status = c > d.end ? 0 : c > d.start ? 1 : a.is_joined ? 2 : 3,
        b ? d.statusClass = b : d.statusClass = ["qunact-i-end", "qunact-i-holding", "qunact-i-registed", "qunact-i-registing"][d.act_status],
        $(".qunact-status").prop("className", "qunact-status " + d.statusClass)
    }
    ,
    this.formatDate = function(a) {
        var b = new Date(a);
        return b.getMonth() + 1 + "月" + b.getDate() + "日" + (b.getHours() < 10 ? "0" : "") + b.getHours() + ":" + (b.getMinutes() < 10 ? "0" : "") + b.getMinutes()
    }
    ,
    this.formatActDate = function(a, b) {
        var c = new Date(a)
          , d = c.getMonth() + 1
          , e = c.getDate()
          , f = new Date(b)
          , g = f.getMonth() + 1
          , h = f.getDate();
        return d + "月" + e + "日" + (c.getHours() < 10 ? "0" : "") + c.getHours() + ":" + (c.getMinutes() < 10 ? "0" : "") + c.getMinutes() + " - " + (d === g && e === h ? "" : g + "月" + h + "日") + (f.getHours() < 10 ? "0" : "") + f.getHours() + ":" + (f.getMinutes() < 10 ? "0" : "") + f.getMinutes()
    }
    ,
    this.getParam = function(a) {
        return Util.queryString(a) || Util.getHash(a)
    }
    ,
    this._initVar = function() {
        var a = this
          , b = navigator.userAgent;
        this.network = 1,
        mqq.device.getNetworkType(function(b) {
            a.network = b
        }),
        this.base = _domain + "";
        var c = this.getParam;
        this.bid = c("bid"),
        this.pid = c("pid"),
        this.gid = c("gid"),
        this.isStarGroup = c("stargroup"),
        this.source = c("source"),
        this.from = c("from"),
        this.isNewWebView = c("webview"),
        this.isUploading = 0,
        this.purchaseLink = "",
        this.myuin = Login.getUin(),
        this.postData = null ,
        this.postType = 0,
        this.currentCommentFloor = 0,
        this.currentCommentID = null ,
        this.commentType = 1,
        this.commentOrder = 0,
        this.flag = 0,
        this.isOpenAct = !1,
        this.isLocked = 0,
        this.isBest = 0,
        this.isRenderFromLocal = 0,
        this.isQQ = "0" !== mqq.QQVersion,
        this.isWX = b.match(/\bMicroMessenger\/[\d\.]+/),
        this.isYYB = b.match(/\/qqdownloader\/(\d+)?/),
        this.isIOS = $.os.ios
    }
    ,
    this.initSodaFilter = function() {
        var a = this
          , b = decodeURI(this.getParam("searchkw"))
          , c = this.getParam("useCacheImg")
          , d = $(document).width() - 30
          , e = {
            plain2rich: function(a) {
                return plain2rich(a).replace(/&amp;/g, "&")
            },
            replaceBr: function(a) {
                return a.replace(/<br>/g, "&lt;br&gt;")
            },
            changeBr: function(a) {
                return a.replace(/<br>/g, '<p class="ph"></p>')
            },
            matchSearch: function(a) {
                if (!b)
                    return a;
                var c = new RegExp(b,"gmi")
                  , d = function(a, b, c) {
                    return /(<img)|(<a)/.test(c) ? a : ['<span class="keyword-match">', a, "</span>"].join("")
                }
                ;
                return a.replace(c, d)
            },
            defaultAvatar: function(a) {
                return a = a || "http://q.qlogo.cn/g?b=qq&nk=0&s=100",
                a.replace(/&amp;/g, "&")
            },
            showUserName: function(a) {
                return a ? (a.uin + "").indexOf("*") > -1 ? a.nick_name + "(" + a.uin + ")" : a.nick_name : ""
            },
            renderHonours: honourHelper.renderHonours,
            showPoster: function(b) {
                return "detail" === a.getTplName(a.postType) ? honourHelper.renderPoster(b) : ""
            },
            formatTime: FormatTime,
            realFloor: function(b) {
                return 200 === a.postType ? b : b + 1
            },
            getStatusName: function(a) {
                return ["报名中", "进行中", "已结束"][a]
            },
            rssRender: function(a) {
                if (/^src:/.test(a))
                    return '<div class="img-box"> <img src="' + a.replace(/^src:/, "") + '"></div>';
                var b = plain2rich(a);
                return '<div class="content">' + b.replace(/<br>/g, '<p class="ph"></p>') + "</div>"
            },
            getImgSrc: function(b, d) {
                if ("string" == typeof b)
                    return b;
                var e = b.url;
                return e = c && 0 === d ? imgHandle.getThumbUrl(e, "200") : "string" == typeof d ? imgHandle.getThumbUrl(e, "200") : imgHandle.getThumbUrl(e, 1 === a.network ? "1000" : "640")
            },
            getImgOffset: function(a, b) {
                if ("pa" === b && (a = {
                    w: a.width,
                    h: a.height,
                    url: a.url
                }),
                "string" == typeof a || !a.w || !a.h)
                    return "";
                var c = a.w
                  , e = a.h;
                return c > 200 && (e = d / c * e,
                c = d),
                b && a.h > 300 && a.h / a.w > 50 && (e = 300),
                "width:" + c + "px;height:" + e + "px"
            },
            decodeStr: function(a) {
                return $.str.decodeHtml(a)
            },
            getAudioWrapperWidth: function(a) {
                var b = 142 / 60
                  , c = a.duration > 60 ? 226 : Math.round(84 + a.duration * b);
                return c
            },
            toSecond: function(a) {
                return (a / 1e3).toFixed()
            },
            getPKEndtime: function(a) {
                var b = new Date(parseInt(a + "000"))
                  , c = "PK结束时间:";
                return c + b.getFullYear() + "/" + (b.getMonth() + 1) + "/" + b.getDate() + " " + (b.getHours() < 10 ? "0" : "") + b.getHours() + ":" + (b.getMinutes() < 10 ? "0" : "") + b.getMinutes()
            },
            getAddress: function(a) {
                if ("object" != typeof a)
                    return "";
                var b = a.city.replace("市", "");
                return b
            },
            processContent: function(a, b) {
                b && (a.urlInfo = b.urlInfo,
                a.keyInfo = b.keyInfo);
                var c = !1;
                a.isRefComment && (c = !0);
                var d = plain2rich({
                    text: a.content,
                    urlInfos: a.urlInfo || [],
                    onlyText: c
                }).replace(/<br>/g, '<p class="ph"></p>').replace(/{/g, "{").replace(/}/g, "}").replace(/{\$\=([^\}]+)}/g, '<span class="comment-appreciation-mark" >$1</span>');
                return d
            },
            formatNum: function(a) {
                return 0 === a ? "" : numHelper(a)
            },
            getVoteState: function(a) {
                return ["unprogress", "progress", "end"][a]
            },
            getVoteStateText: function(a) {
                return ["暂未开始", "VS", "结束"][a]
            },
            richPostCompile: function(a) {
                var b = a.richText;
                if (b)
                    return b = b.replace(/<img.*?>/gm, function(a) {
                        return a = a.replace(/\{|\}/gm, "").replace(/\s{2,}/gm, " ").replace(/data\-size="(.*?)"/gm, function() {
                            return ""
                        }).replace(/src="(.*?)"/gm, function(a) {
                            return imgHandle.getThumbUrl(a)
                        }).replace("src=", "lazy-src=")
                    }).replace(/<iframe.*?<\/iframe>/gm, function(a) {
                        return a.replace(/\{|\}/gm, "")
                    }),
                    b = plain2rich({
                        search: !0,
                        text: b,
                        urlInfos: a.urlInfo || [],
                        isRichPost: !0
                    })
            }
        };
        for (var f in e)
            e.hasOwnProperty(f) && window.sodaFilter(f, e[f])
    }
    ,
    this.showLockTip = function() {
        return this.isLocked ? (Tip.show("该话题已被锁定，不支持赞和评论"),
        !0) : !1
    }
    ,
    this.init = function() {
    	return this.bid && this.pid ? (this.initSodaFilter(),
        a(),
        this.Post.init(),
        Login.continueLogin(),
        void (window.mqq && mqq.addEventListener && mqq.dispatchEvent("addreadnum", {
            bid: c.bid,
            pid: c.pid
        }))) : ( window.location.href.indexOf("s.p.qq.com") > -1 && (window.badjsReport("id-error"),
        d.monitor(676545)),
        void Tip.show("部落或者话题ID错误", {
            type: "warning"
        }));
    }
    ,
    mqq.compare("5.3.2") >= 1 && (window.getBrowserSignature = b),
    this._initVar()
});