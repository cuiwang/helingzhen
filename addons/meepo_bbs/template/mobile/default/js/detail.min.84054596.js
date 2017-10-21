!
function(a, b) {
    a.ActionSheet = b(a.$, a.Tmpl)
} (this,
function(a, b) {
    function c() {
        i = a('<div class="action-sheet"></div>'),
        h = a('<div class="action-sheet-mask"></div>'),
        a(document.body).append(i).append(h),
        h.on("tap",
        function() {
            f()
        }).on("webkitTransitionEnd",
        function() {
            h.hasClass("show") || h.hide()
        }),
        i.on("tap", ".sheet-item", e).on("webkitTransitionEnd",
        function() {
            i.hasClass("show") || (h.removeClass("show"), i.hide())
        }),
        k = !0
    }
    function d(a) {
        mqq.compare("4.7") >= 0 && !a.useH5 ? mqq.ui.showActionSheet({
            items: a.items,
            cancel: "取消"
        },
        function(b, c) {
            0 === b ? a.onItemClick && a.onItemClick(c) : a.onCancel && a.onCancel(c)
        }) : (k || c(), j = a, document.body.style.overflow = "hidden", i.html(""), l.on("touchmove", g), b(window.TmplInline_actionsheet.frame, a).appendTo(i), h.show(), i.show(), setTimeout(function() {
            h.addClass("show"),
            i.addClass("show")
        },
        50))
    }
    function e() {
        var b = a(this).attr("value");
        b = Number(b),
        -1 === b ? j.onCancel && j.onCancel() : j.onItemClick && j.onItemClick(b),
        f()
    }
    function f(a) {
        i && (a ? (h.hide().removeClass("show"), i.hide().removeClass("show")) : i.removeClass("show"), l.off("touchmove", g), document.body.style.overflow = "")
    }
    function g(a) {
        a.preventDefault()
    }
    var h, i, j, k, l = a(document);
    return {
        show: d,
        hide: f
    }
}),
function(a, b) {
    a.QQRecognise = b(a.$)
} (this,
function() {
    function a(a) {
        var b = {};
        return a ? (a.replace(d,
        function(a, c) {
            return b[c] = 0,
            ""
        }), b) : b
    }
    function b(a) {
        var b = {};
        a = a || {},
        b.param = DB.wrapGroup(a.param),
        b.url = "/cgi-bin/bar/post/numbercheck",
        b.noNeedLogin = !!a.noNeedLogin,
        b.isLocal = !!a.isLocal,
        b.succ = function(b) {
            return (a.succ ||
            function() {})(b)
        },
        b.err = function(b) {
            return (a.err ||
            function() {})(b)
        },
        DB.cgiHttp(b)
    }
    function c(c, d) {
        var e = a(c),
        f = {},
        g = [];
        for (var h in e) if (e.hasOwnProperty(h)) {
            if (h.length < 5 || h.length > 10) {
                f[h] = 0;
                continue
            }
            h = parseInt(h);
            var i = $.storage.get("qqrecognise." + h);
            "[object Number]" === Object.prototype.toString.call(i) ? f[h] = parseInt(i) : g.push(h)
        }
        g.length ? b({
            param: {
                ul: JSON.stringify(Util.unique(g))
            },
            succ: function(a) {
                a = a.result;
                for (var b in a) a.hasOwnProperty(b) && ($.storage.set("qqrecognise." + b, a[b]), f[b] = a[b]);
                d && d(f)
            },
            err: function(a) {
                console.log("qq.recognise.error", a),
                d && d(f)
            }
        }) : d && d(f)
    }
    var d = /([1-9]\d+)/g;
    return {
        REG_QQ_CODE: d,
        recognise: c
    }
}),
function() {
    window.honourHelper = {
        renderHonours: function(a, b) {
            var c, d = a.admin_ext,
            e = a["continue"],
            f = '<span class="honour vipno-icon" data-url="http://imgcache.qq.com/club/themes/mobile/xylm/business/personal_center/center.html?from=exp_icon"></span>',
            g = "";
            return ! b && a.vipno && (g = f),
            8 === (8 & d) ? (c = a.title || "大明星", g += '<span class="honour border-1px vip">' + c + "</span>") : 2 === (2 & d) ? g += '<span class="honour border-1px admin">大酋长</span>': 4 === (4 & d) ? g += '<span class="honour border-1px admin">小酋长</span>': b && a.vipno ? g += f: 64 === (64 & d) ? (c = a.flag || "达人", g += '<span class="honour border-1px expert">' + c + "</span>") : e >= 7 && (g += '<span class="honour border-1px fans">铁杆粉</span>'),
            g
        },
        renderPoster: function(a, b) {
            var c, d = arguments.length;
            return 1 === d ? c = !!a: 2 === d && (c = a && b ? a === b: !1),
            c ? '<span class="honour border-1px poster">楼主</span>': ""
        },
        isAdmin: function(a) {
            return 1 === (1 & a) || 2 === (2 & a) || 4 === (4 & a) || 32 === (32 & a)
        }
    }
} (),
function(a, b) {
    a.MediaPlayer = b(a.$)
} (this,
function() {
    function a() {
        return a.__seed || (a.__seed = 1),
        a.__seed++
    }
    function b(b) {
        var c, d, e = a();
        r || (r = $('<div class=".hide"><audio></audio></div>').appendTo(document.body).children(), r.on("pause error",
        function() {
            var a = $(this).data("playId"),
            b = w[a];
            $(v).trigger("stop", b)
        }).on("play",
        function() {
            var a = $(this).data("playId"),
            b = w[a];
            $(v).trigger("play", b)
        }).on("canplay",
        function() {
            var a = $(this).data("playId"),
            b = w[a];
            $(v).trigger("canplay", b)
        }));
        var f = r.get(0);
        return c = r.data("playId"),
        f.pause(),
        c && (d = w[c], delete w[c], $(v).trigger("stop", d)),
        f.src = b.url,
        f.play(),
        r.data("playId", e),
        d = {
            url: b.url,
            type: b.type,
            id: e
        },
        w[e] = d,
        e
    }
    function c() {
        r && r.get(0).pause()
    }
    function d() {
        s && s.pause()
    }
    function e(a) {
        var b = $(a).find(".post-video");
        b.length && b.each(function() {
            var a = $(this).show(),
            b = a.data("vid"),
            c = a.data("image");
            c || (c = "http://shp.qpic.cn/qqvideo/0/" + b + "/400"),
            f(b, c, 3)
        })
    }
    function f(a, b, c) {
        switch (mqq.ui.setWebViewBehavior({
            swipeBack: 0
        }), y) {
        case "wait":
            x.push([a, b, c]),
            g();
            break;
        case "loading":
            x.push([a, b, c]);
            break;
        case "complete":
            i(a, b, c)
        }
    }
    function g() {
        y = "loading";
        var a = $.cookie;
        loadjs.load(["http://imgcache.gtimg.cn/tencentvideo_v1/tvp/js/tvp.player_v2_zepto.js"], [function() {
            a.get = $.cookie.get,
            a.set = $.cookie.set,
            a.del = $.cookie.del,
            $.cookie = a,
            y = "complete",
            x.forEach(function(a) {
                i(a[0], a[1], a[2])
            }),
            x = []
        }])
    }
    function h(a) {
        Q.tdw({
            opername: "Grp_tribe",
            module: "post_detail",
            action: a,
            obj1: Util.queryString("pid") || Util.getHash("pid"),
            ver1: Util.queryString("bid") || Util.getHash("bid"),
            ver3: $.os.android ? 1 : $.os.ios ? 2 : 0
        })
    }
    function i(a, b, c) {
        var d = new tvp.VideoInfo;
        1 === c ? d.setChannelId(a) : d.setVid(a),
        d.setTitle("兴趣部落");
        var e, f, g, i = new tvp.Player,
        j = 15,
        k = 320 / 240;
        e = document.body.offsetWidth + 2 - 2 * j,
        f = ~~ (e / k),
        $(window).on("resize",
        function() {
            i.$video && i.$video.length && (i.$video[0].width = document.body.offsetWidth - 2 * j, f = ~~ (e / k), i.$video[0].height = f, i.$video[0].controls = "controls")
        }),
        g = 3 === c ? "vplayer_" + a: "mod_player";
        var l = {
            width: e,
            height: f,
            video: d,
            modId: g,
            appid: "10008",
            isHtml5UseFakeFullScreen: !0,
            pic: b,
            plugins: {
                AppBanner: {
                    promotionId: 761,
                    downloadUrl: "http://mcgi.v.qq.com/commdatav2?cmd=4&confid=761&platform=aphone"
                }
            },
            onwrite: function() {
                setTimeout(function() {
                    $(".tvp_promote_download").tap(function() {
                        h("download_vid")
                    })
                },
                500)
            },
            onplaying: function() {
                s = this,
                MediaPlayer.stop({
                    type: "music"
                }),
                MediaPlayer.stop({
                    type: "audio"
                }),
                h("start_video")
            }
        };
        1 === c && (l.type = 1),
        i.create(l)
    }
    function j(b) {
        var c = a(),
        d = {
            id: b.id,
            title: b.name,
            desc: b.singer,
            audio_url: b.url,
            image_url: b.img,
            share_url: b.href
        };
        return mqq.media.startPlayMusic(d),
        w[c] = b,
        c
    }
    function k() {
        mqq.media.stopPlayMusic()
    }
    function l(a) {
        return "audio" === a.type ? (k(), d(), b(a)) : "music" === a.type ? (c(), k(), d(), u ? j(a) : b(a)) : void 0
    }
    function m(a) {
        a = w[a.playId] || a,
        delete w[a.playId],
        a && ("audio" === a.type ? c() : "music" === a.type ? u ? k(a) : c() : "video" === a.type && d())
    }
    function n(a) {
        $(MediaPlayer).on("stop",
        function(b) {
            var c = b._args;
            if (c) {
                var d = $("#" + a + ' div[data-playId="' + c.id + '"]');
                d && (d.attr("data-playId", ""), d.removeClass("playing"), p(d))
            }
        }),
        $(MediaPlayer).on("canplay",
        function(b) {
            var c = b._args;
            if (c) {
                var d = $("#" + a + ' div[data-playId="' + c.id + '"]');
                d && (d.attr("data-playId", c.id), d.removeClass("waiting").addClass("playing"), o(d))
            }
        }),
        mqq.addEventListener("qbrowserMusicStateChange",
        function(b) {
            var c = b;
            if (c) {
                var d = $("#" + a + ' div[data-id="' + c.id + '"]');
                d && (2 === Number(c.state) ? (d.attr("data-playId", c.id), d.addClass("playing")) : (d.attr("data-playId", ""), d.removeClass("playing")))
            }
        }),
        document.addEventListener("qbrowserVisibilityChange",
        function(a) {
            a.hidden && (c(), d())
        }),
        $("#" + a).on("tap",
        function(a) {
            var b, c, d = $(a.target);
            d.attr("rel") || (d = d.parent("[rel]"));
            var e = d.attr("rel");
            d.data("target") && (d = d.parent(d.data("target")));
            var f = d.data();
            "playAudio" === e ? (c = d.attr("data-playId"), c ? (MediaPlayer.stop({
                type: "audio",
                id: c
            }), d.attr("data-playId", ""), d.removeClass("playing"), p(d)) : (c = MediaPlayer.play({
                type: "audio",
                url: f.url
            }), c && (d.addClass("waiting"), d.attr("data-playId", c)))) : "playMusic" === e ? (c = d.attr("data-playId"), c ? (MediaPlayer.stop({
                type: "music",
                id: c
            }), u || (d.attr("data-playId", ""), d.removeClass("playing"))) : (f.type = "music", c = MediaPlayer.play(f), b = d.data("special"), b && h("play_type_music"), d.addClass("playing"), d.attr("data-playId", c))) : "openMusic" === e && f.id && Util.openUrl(f.href, !0)
        })
    }
    function o(a) {
        var b = +a.data("duration"),
        c = a.find(".length");
        t = setInterval(function() {
            b ? c.text(--b + '"') : p(a)
        },
        1e3)
    }
    function p(a) {
        t && clearInterval(t);
        var b = +a.data("duration"),
        c = a.find(".length");
        c.text(b + '"')
    }
    function q(a) {
        z && clearTimeout(z),
        z = setTimeout(function() {
            z = null,
            mqq.media.getPlayState(function(b) {
                2 === Number(b) && mqq.media.getCurrentSong(function(b) {
                    var c = $("#" + a + ' div[data-id="' + b.id + '"]');
                    c && c.length && (c.attr("data-playId", b.id), c.addClass("playing"))
                })
            })
        },
        500)
    }
    var r, s, t, u = mqq.compare("5.2") > 0,
    v = {},
    w = {},
    x = [],
    y = "wait";
    mqq.build("mqq.media.startPlayMusic", {
        iOS: function(a, b) {
            a.callback = mqq.callback(b),
            mqq.invoke("media", "startPlayMusic", a)
        },
        android: function(a, b) {
            a.callback = mqq.callback(b),
            mqq.invoke("qbizApi", "startPlayMusic", a)
        },
        support: {
            iOS: "5.2",
            android: "5.2"
        }
    }),
    mqq.build("mqq.media.stopPlayMusic", {
        iOS: function() {
            mqq.invoke("media", "stopPlayMusic")
        },
        android: function() {
            mqq.invoke("qbizApi", "stopPlayMusic")
        },
        support: {
            iOS: "5.2",
            android: "5.2"
        }
    }),
    mqq.build("mqq.media.getPlayState", {
        iOS: function(a) {
            var b = {};
            b.callback = mqq.callback(a),
            mqq.invoke("media", "getPlayState", b)
        },
        android: function(a) {
            var b = {};
            b.callback = mqq.callback(a),
            mqq.invoke("qbizApi", "getPlayState", b)
        },
        support: {
            iOS: "5.2",
            android: "5.2"
        }
    }),
    mqq.build("mqq.media.getCurrentSong", {
        iOS: function(a) {
            var b = {};
            b.callback = mqq.callback(a),
            mqq.invoke("media", "getCurrentSong", b)
        },
        android: function(a) {
            var b = {};
            b.callback = mqq.callback(a),
            mqq.invoke("qbizApi", "getCurrentSong", b)
        },
        support: {
            iOS: "5.2",
            android: "5.2"
        }
    });
    var z = null;
    return v.play = l,
    v.stop = m,
    v.attachPlayer = n,
    v.checkMusicState = q,
    v.initVPlayer = f,
    v.initVPlayerMuti = e,
    v
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : a.TmplInline_ad = b()
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
        var d = b("img"),
        e = b("imgHandle"),
        f = b("type"),
        g = b("uiType"),
        h = b("title"),
        i = b("desc"),
        j = b("id"),
        k = b("imgHeight"),
        l = (b("banner"), "");
        return l += "",
        d = e.formatThumb(d, !0, 80, 80),
        d = d[0],
        "barindex" === f ? (l += " ", 1 === g ? (l += ' <div class="detail-text-content haspic"> <div class="text-container"> <h3 class="text">', l += c(h), l += '<span class="list-ad-mark-top">推广</span></h3> <div class="list-content">', l += c(i), l += '</div> </div> <div class="act-img img-gallary"> <img class="ad-img" src="', l += c(e.getThumbUrl(d)), l += '" style="width:', l += c(d.width), l += "px; height:", l += c(d.height), l += "px; margin-left:", l += c(d.marginLeft), l += "px; margin-top:", l += c(d.marginTop), l += 'px;" /> </div> </div> ') : 2 === g ? (l += ' <div class="detail-text-content haspic"> <div class="text-container"> <div class="act-img img-gallary"> <img class="ad-img" src="', l += c(e.getThumbUrl(d)), l += '" style="width:', l += c(d.width), l += "px; height:", l += c(d.height), l += "px; margin-left:", l += c(d.marginLeft), l += "px; margin-top:", l += c(d.marginTop), l += 'px;" /> </div> <h3 class="text"><span class="ad-mark-new">推广</span>', l += c(h), l += '</h3> <div class="list-content">', l += c(i), l += "</div> </div> </div> ") : (l += ' <div class="gdt-ad-wrapper"> <div class="detail-text-content haspic"> <div class="text-container"> <h3 class="text">', l += c(h), l += '</h3> <div class="list-content">', l += c(i), l += '</div> </div> <div class="act-img img-gallary"> <img class="ad-img" src="', l += c(e.getThumbUrl(d)), l += '" style="width:', l += c(d.width), l += "px; height:", l += c(d.height), l += "px; margin-left:", l += c(d.marginLeft), l += "px; margin-top:", l += c(d.marginTop), l += 'px;" /> </div> <span class="ad-ribbon">推广</span> </div> </div> '), l += " ") : "comment" === f ? (l += ' <div class="gdt-ad-wrapper comment-wrapper"> <div class="user-avatar" data-profile-uin="', l += c(d || ""), l += '"> <img src="', l += c(e.getThumbUrl(d)), l += '"/> </div> <div class="name-wrap"><span class="author user-nick ad-title">', l += c(h), l += '</span></div> <div class="content-wrapper"> <div class="content" id="detail_comment_', l += c(j), l += '">', l += c(i), l += '</div> </div> </div> <span class="ad-ribbon">推广</span> ') : "recommend" === f ? (g = 2, l += " ", 1 === g ? (l += ' <div class="list-content"> <p class="grouptitle">', l += c(h), l += '</p> <div class="groupbody">', l += c(i), l += '</div> </div> <span class="ad-ribbon">推广</span> ') : 2 === g && (l += ' <div class="ad-title "><div class="ad-title-txt">赞助商提供</div><div class="ad-title-dashed"></div></div> <div class="ad-mark">推广</div> <div class="ad-banner-img-wrapper" style="height: ', l += c(k), l += 'px"> <img src="', l += c(e.getThumbUrl(d)), l += '" class="ad-banner-img"/> </div> '), l += " ") : "10" === f && (l += ' <div class="list-content ad-banner" style="background-image: url(http://xiaoqu.qq.com/mobile/img/personal_banner.jpg?1416541398);"> </div> <span class="ad-ribbon">推广</span> '),
        l += " "
    };
    return a.ad = "TmplInline_ad.ad",
    Tmpl.addTmpl(a.ad, b),
    a
}),
function(a, b) {
    a.ad = b(a.TmplInline_ad)
} (this,
function(a) {
    function b(a) {
        var b, c, d = Login.getUin(),
        e = {
            page_type: 0,
            _uin: Number(d),
            bid: Util.getHash("bid") || Util.queryString("bid"),
            _datafmt: 0,
            _charset: 1
        },
        f = {
            poscount: 1,
            posid: "recommend" === a ? k[a][0] : k[a],
            adcount: "1",
            datatype: 2,
            outformat: 0
        },
        g = {
            wapos: $.os.android ? "ADR": $.os.ios ? "IPH": "",
            wapver: "1"
        },
        h = {};
        mqq.compare("4.5") >= 0 && mqq.device.getDeviceInfo(function(a) {
            g.model = $.os.android ? a.model: $.os.ios ? a.modelVersion: "",
            h.c_device = g.model,
            h.c_osver = a.systemVersion
        });
        try {
            b = localStorage.getItem(d + "-lat"),
            c = localStorage.getItem(d + "-lon")
        } catch(i) {}
        return b && c && (h.lat = b, h.lng = c),
        g.req = h,
        f.ext = g,
        e._param = JSON.stringify(f),
        e
    }
    function c(a, c) {
        console.log(a, "准备拉取CGI广告数据"),
        DB.cgiHttp({
            url: "/cgi-bin/bar/extra/get_ads",
            param: b(a),
            succ: function(b) {
                if (0 !== b.retcode || !b.result || void 0 !== b.result.ret && 0 !== b.result.ret || $.isEmptyObject(b.result)) return void console.log(a, "没有广告数据");
                var e, f, g, i, n = 1 != b.result.ads_from;
                if (n) {
                    if (e = b.result.data, !e) return void console.log(a, "没有广告数据");
                    if (f = e[k[a]], "recommend" === a && (f = e[k[a][0]], m = 2, f || (f = e[k[a][1]], m = 1)), !(f && 0 === f.ret && f.list && f.list.length > -1)) return void console.log(a, "没有广告数据");
                    g = f.list[0];
                    var o = f.cfg,
                    p = o.id;
                    if (i = {
                        id: p,
                        type: a,
                        uiType: "barindex" === a ? l: m,
                        cl: g.cl,
                        title: h(g.txt),
                        desc: h(g.desc),
                        img: g.img || g.img_s || g.img2,
                        rl: g.rl,
                        apurl: g.apurl
                    },
                    "recommend" === a) {
                        var q = $(document).width() - 20;
                        i.imgHeight = Math.round(166 * q / 582)
                    }
                } else g = b.result,
                i = {
                    id: g.id,
                    type: a,
                    uiType: l,
                    title: h(g.title),
                    desc: h(g.desc),
                    img: g.pic,
                    rl: g.link
                };
                i.img = [{
                    url: i.img,
                    h: 90,
                    w: 90
                }],
                console.log(a, "CGI拉取完广告数据"),
                j[a].data = i,
                j[a].hasData = !0,
                j[a].container && d(a, c)
            },
            err: function() {
                f(j[a].container)
            }
        })
    }
    function d(b, c) {
        if (!j[b].data) return void(j[b].hasData && (console.log(b, "暂时无广告数据"), f(j[b].container)));
        var d, h = j[b].data,
        i = j[b].container;
        "recommend" === b && 2 === m && (d = $("#recommend-list"), d.find(".gdt-ad").hide(), d.after('<div class="gdt-ad-banner"></div>'), i = j[b].container = $(".gdt-ad-banner")),
        Tmpl(a.ad, h).update(i),
        i.data("href", h.rl),
        i.show(),
        "recommend" === b && 1 === m && (c = c || 3, console.log(b, "隐藏第", c - 1, "个推荐帖子（从0开始）"), $("li", "#recommend-list .recommend-post-list").eq(c - 1).hide()),
        console.log(b, "已展示广告"),
        e(i, b),
        p.exposure(h.apurl, b),
        p.isd(b),
        g(b)
    }
    function e(a, b) {
        a.on("tap",
        function(a) {
            var c = $(a.currentTarget),
            d = c.data("href");
            p.tdw("click", b),
            Util.openUrl(d + (d.indexOf("?") > 0 ? "": "?") + "&_wv=4", !0),
            a.stopPropagation()
        })
    }
    function f(a) {
        a && a.remove()
    }
    function g(a) {
        j[a] = {
            container: null,
            hasData: !1,
            data: null
        }
    }
    function h(a) {
        return a ? a.replace("&nbsp;", " ") : a
    }
    function i(a) {
        if (!a) return ! 1;
        var b = $(a),
        c = b.offset();
        return c ? c.top + c.height > 10 : !1
    }
    var j = {
        barindex: {
            container: null,
            hasData: !1,
            data: null
        },
        comment: {
            container: null,
            hasData: !1,
            data: null
        },
        recommend: {
            container: null,
            hasData: !1,
            data: null
        }
    },
    k = {
        barindex: "7000703150186964",
        comment: "9050801025398385",
        recommend: ["4090805065898364"]
    },
    l = 2,
    m = 2,
    n = $.os.android ? "android": $.os.ios ? "ios": "unknown",
    o = {},
    p = {
        startTime: 0,
        endTime: 0,
        map: {
            "2g": {
                barindex: {
                    android: 0,
                    ios: 1
                },
                comment: {
                    android: 2,
                    ios: 3
                },
                recommend: {
                    android: 4,
                    ios: 5
                }
            },
            "3g": {
                barindex: {
                    android: 6,
                    ios: 7
                },
                comment: {
                    android: 8,
                    ios: 9
                },
                recommend: {
                    android: 10,
                    ios: 11
                }
            },
            wifi: {
                barindex: {
                    android: 12,
                    ios: 13
                },
                comment: {
                    android: 14,
                    ios: 15
                },
                recommend: {
                    android: 16,
                    ios: 17
                }
            }
        },
        _init: function() {
            this.endTime = Date.now()
        },
        _getNetwork: function(a) {
            var b = ""; ($.os.ios || $.os.android) && mqq.compare("4.6") >= 0 ? mqq.device.getNetworkType(function(c) {
                var d = {
                    1 : "wifi",
                    2 : "2g",
                    3 : "3g",
                    4 : "unknown"
                };
                b = d[c],
                b = "unknown" === b ? "wifi": b,
                a(b)
            }) : b = "unknown",
            b = "unknown" === b ? "wifi": b,
            a(b)
        },
        isd: function(a) {
            this._init();
            var b = this;
            this._getNetwork(function(c) {
                if (b.map[c]) {
                    var d = b.map[c][a][n];
                    "number" == typeof d && (console.log("env =", d), Q.isd(7832, 47, 29, b.endTime - b.startTime, 1, null, d))
                }
            })
        },
        exposure: function(a, b) {
            if (a) {
                var c = new Image;
                c.src = a,
                c = null
            }
            this.tdw("expose", b)
        },
        tdw: function(a, b) {
            var c = {
                obj1: Util.getHash("pid") || Util.queryString("pid"),
                ver1: Util.queryString("bid") || Util.getHash("bid"),
                ver6: $.os.ios ? 2 : $.os.android ? 1 : ""
            },
            d = {
                barindex: "post_list",
                comment: "post_detail",
                recommend: "post_detail"
            },
            e = {
                barindex: "tribe_ad",
                comment: "Grp_tribe",
                recommend: "Grp_tribe"
            },
            f = {
                barindex: {
                    click: "Clk_ad",
                    expose: "Pv_ad"
                },
                comment: {
                    click: "Clk_ad_com",
                    expose: "exp_ad_com"
                },
                recommend: {
                    click: "Clk_ad_recom",
                    expose: "exp_ad_recom"
                }
            };
            c.opername = e[b],
            c.module = d[b],
            c.action = f[b][a],
            "barindex" === b && (c.ver3 = o.barName, c.ver4 = o.category, c.ver5 = o.categoryName),
            Q.tdw(c)
        }
    };
    return {
        init: function(a, b) {
            var d = /\bMicroMessenger\/[\d\.]+/.test(navigator.userAgent);
            if (!d) {
                p.startTime = Date.now(),
                console.log(a, "广告模块初始化..."),
                console.log("ad init()", a);
                try {
                    c(a, b)
                } catch(e) {
                    console.error(e)
                }
            }
        },
        show: function(a, b, c) {
            j[a].container = $(b),
            d(a, c),
            console.log("ad show()", a)
        },
        silentFetch: function(a, b) {
            i(b) && (p.startTime = Date.now(), j[a].container = $(b), console.debug(a, "开始静默获取广告..."), c(a))
        },
        setBarInfo: function(a) {
            o = a,
            console.debug("BarInfo:", o)
        }
    }
}),
function(a, b) {
    a.ViewPreloader = b(a.$, a.DB)
} (this,
function() {
    var a, b = 15e3;
    return a = {
        canUseViewPreload: $.os.android && mqq.compare("5.0") >= 0,
        open: function(a) {
            if (this.canUseViewPreload) {
                var b = this,
                c = a.cgiMap;
                localStorage.setItem("storageConfirm", 1),
                $.each(c,
                function(a, c) {
                    b.__send(a, c)
                })
            }
            this.__openView(a)
        },
        __openView: function(a) {
            if (a.url) {
                var b = a.url;
                this.canUseViewPreload && (b += b.indexOf("?") > -1 ? "&viewPreLoad=1": "?viewPreLoad=1"),
                Util.openUrl(b, !0)
            } else a.callback && a.callback(this.canUseViewPreload ? 1 : 0)
        },
        __send: function(a, b) {
            var c, d = this;
            DB.cgiHttp({
                url: b.cgi,
                param: b.param,
                type: "get",
                succ: function(b) {
                    try {
                        c = b,
                        localStorage.setItem("viewPreloadData-" + a, JSON.stringify(c)),
                        mqq.dispatchEvent("preloadDataSuccess", {
                            dataName: a,
                            preloadData: c
                        })
                    } catch(e) {
                        localStorage.setItem("viewPreloadData-" + a, JSON.stringify({
                            type: "error"
                        })),
                        d.__dispatchError(a)
                    }
                },
                err: function() {
                    localStorage.setItem("viewPreloadData-" + a, JSON.stringify({
                        type: "error"
                    })),
                    d.__dispatchError(a)
                }
            })
        },
        __dispatchError: function(a) {
            mqq.dispatchEvent("preloadDataError", {
                dataName: a
            })
        },
        __removeDataStorage: function(a) {
            localStorage.removeItem("viewPreloadData-" + a)
        },
        __callbackHandler: function(a, b, c, d) {
            c && c({
                dataName: b,
                status: a,
                preloadData: d
            })
        },
        useViewPreload: function() {
            return~~ (Util.queryString("viewPreLoad") || Util.getHash("viewPreLoad"))
        },
        receive: function(a, c) {
            var d = this,
            e = this.useViewPreload();
            if (a = a || [], e) {
                var f, g, h = {},
                i = localStorage.getItem("storageConfirm");
                if (localStorage.removeItem("storageConfirm"), "1" === i) {
                    for (var j = 0; j < a.length; j++) g = a[j],
                    f = localStorage.getItem("viewPreloadData-" + g),
                    f && (f = JSON.parse(f), a.splice(j, 1), j--, h[g] = 1, "error" !== f.type ? d.__callbackHandler(0, g, c, f) : d.__callbackHandler(2, g, c)),
                    this.__removeDataStorage(g);
                    a.length && ($.each(a,
                    function(a, b) {
                        d.__callbackHandler(1, b, c)
                    }), mqq.addEventListener("preloadDataSuccess",
                    function(a) {
                        var b = a.dataName;
                        if (d.__removeDataStorage(b), !h[b]) {
                            h[b] = 1;
                            try {
                                f = a.preloadData,
                                f ? d.__callbackHandler(0, b, c, f) : d.__callbackHandler(2, b, c)
                            } catch(e) {
                                d.__callbackHandler(2, b, c)
                            }
                        }
                    }), mqq.addEventListener("preloadDataError",
                    function(a) {
                        var b = a.dataName;
                        d.__removeDataStorage(b),
                        h[b] = 1,
                        d.__callbackHandler(2, b, c)
                    }), setTimeout(function() {
                        $.each(a,
                        function(a, b) {
                            h[b] || (d.__removeDataStorage(b), d.__callbackHandler(3, b, c))
                        })
                    },
                    b))
                } else d.__callbackHandler(4, g, c)
            }
        }
    }
}),
function(a, b) {
    a.ApplyAdmin = b()
} (this,
function() {
    function a(a, b) {
        var e = {
            opername: "tribe_cgi",
            module: "post",
            ver1: c,
            ver3: d,
            action: a
        };
        for (var f in b) b.hasOwnProperty(f) && (e[f] = b[f]);
        Q.tdw(e)
    }
    function b(b, c, d, e) {
        b = b,
        c = c,
        a("apply_chief"),
        DB.cgiHttp({
            url: "http://xiaoqu.qq.com/cgi-bin/bar/apply/check",
            param: {
                bid: b
            },
            succ: function(c) {
                if (!c.result || c.retcode) {
                    var f = c.retcode || "error";
                    return Tip.show("申请失败(" + f + ")", {
                        type: "warning"
                    }),
                    void a("reject_chief", {
                        ver4: 6
                    })
                }
                if (1 === c.result.violated) return void Tip.show(c.result.violate_msg, {
                    type: "warning"
                });
                var g = c.result.small_admins,
                h = c.result.big_admins,
                i = "";
                if (0 === g && (i += "&sna=1"), 0 === h && (i += "&bna=1"), !c.result.qq_level || c.result.qq_level <= 12) Tip.show("您的QQ等级太低，暂时无法申请酋长！", {
                    type: "warning"
                }),
                a("reject_chief", {
                    ver4: 1
                });
                else if (c.result.isban) mqq.ui.showDialog({
                    title: "",
                    text: "您的帐号因在部落被举报有违规操作，暂时无法申请酋长！",
                    needOkBtn: !0,
                    okBtnText: "我知道了",
                    needCancelBtn: !1
                },
                function() {}),
                a("reject_chief", {
                    ver4: 2
                });
                else if (1 === c.result.has_submit || 2 === c.result.has_submit || 3 === c.result.has_submit) 3 === c.result.has_submit ? Tip.show("您的酋长申请还在审核流程中，请勿重复申请！", {
                    type: "warning"
                }) : (1 === c.result.has_submit || 2 === c.result.has_submit) && Tip.show("您在一个月内无法重复申请该部落的酋长！", {
                    type: "warning"
                }),
                a("reject_chief", {
                    ver3: 4
                });
                else if (2 & c.result.admin) Tip.show("您已经是大酋长了，无法申请！", {
                    type: "warning"
                }),
                a("reject_chief", {
                    ver3: 5
                });
                else {
                    var j = "";
                    1 === d && (j = "&bo=1"),
                    11 === d && (j = "&bo=1&noadmin=1"),
                    2 === d && (j = "&so=1"),
                    4 === c.result.has_submit && (j = "&so=1"),
                    5 === c.result.has_submit && (j = "&bo=1"),
                    i += j,
                    1 === e && (i += "&notip=1"),
                    localStorage.setItem("recruitCurrentState", "1"),
                    Util.openUrl("http://xiaoqu.qq.com/mobile/recruit.html?_wv=1027&bid=" + b + "&bname=" + c.result.bname + "&sa=" + (4 & c.result.admin ? 1 : 0) + i, !0)
                }
            }
        })
    }
    var c, d;
    return {
        doApply: b
    }
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : a.TmplInline_detail = b()
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
        var d = b("list"),
        e = b("myuin"),
        f = b("Date"),
        g = "";
        g += "";
        for (var h = d.isAtvCGI,
        i = 0; i < d.length; i++) {
            var j = d[i];
            i > 0 && e === j.uin || (j.ban = 0, h && (j.nick_name = j.nick_name || j.name, j.ban = j.status || 0, j.pic = 1 === j.ban ? "http://q.qlogo.cn/g?b=qq&nk=1&s=100": j.pic || "http://q.qlogo.cn/g?b=qq&nk=" + j.uin + "&s=100&t=" + f.now()), g += ' <li class="face" data-uin="', g += c(j.uin), g += '" data-ban="', g += c(j.ban), g += '"> <a> <img src="', g += c(j.pic), g += '" class="list-img"> <p>', g += c(j.nick_name), g += "</p> </a> </li> ")
        }
        return g += " "
    };
    a.join_list = "TmplInline_detail.join_list",
    Tmpl.addTmpl(a.join_list, b);
    var c = '\r\n<div class="bar-nav border-1px" id="bar_nav">\r\n    <img soda-src="{{bar_pic}}" class="bar-logo">\r\n    <div class="bar-info {{isfollowed === 0?\'unfocused\':\'\'}}">\r\n        <p class="bar-name">{{bar_name}}部落</p>\r\n        <p class="bar-intro">{{bar_intro || bar_name}}</p>\r\n    </div>\r\n    <div class="nav-arrow"></div>\r\n    <div soda-if="isfollowed === 0" class="btn-focus border-1px">\r\n        <span class="focus-icon"></span>关注\r\n    </div>\r\n</div>\r\n';
    a.bar_nav = "TmplInline_detail.bar_nav",
    Tmpl.addTmpl(a.bar_nav, c);
    var d = '<div id="recommend-title" class="recommend-title section-1px">\r\n  <div class="recommend-icon"></div>\r\n  <span>推荐话题</span>\r\n</div>\r\n<ul class="recommend-post-list section-1px">\r\n  <li soda-repeat="item in postlist" data-bid="{{item.bid}}" class="{{item.ad?\'gdt-ad\':\'\'}}" data-pid="{{item.pid}}" data-type="{{item.type}}" openactid="{{item.post.openact_id}}">\r\n      <div class="list-content" soda-if="!item.ad">\r\n        <p class="grouptitle" soda-bind-html="item.title|plain2rich"></p>\r\n        <div class="groupbody">\r\n          <div class="frombarcontent">\r\n            来自<a class="from-bar-link" bid="{{item.bid}}">{{item.gbar.name}}部落</a>\r\n          </div>\r\n\r\n          <div class="rightgroup">\r\n            <i class="read-icon"></i>\r\n            <span class="readnum">{{item.readnum || 0}}</span>\r\n            <i class="reply-icon"></i>\r\n            <span class="groupreply">{{item.total_comment_v2 ||item.total_comment || 0}}</span>\r\n          </div>\r\n        </div>\r\n      </div>\r\n  </li>\r\n</ul>\r\n';
    a.recommend = "TmplInline_detail.recommend",
    Tmpl.addTmpl(a.recommend, d);
    var e = ' <div class="user-avatar" data-profile-uin="{{uin}}">\r\n    <img soda-src="{{user.pic|defaultAvatar}}">\r\n    <span soda-if="!_isStarGroup" class="g-level level-{{user.level || 1}}"></span>\r\n</div>\r\n\r\n\r\n<div class="name-wrap">\r\n    <span class="author user-nick {{user.vipno ? \'user-nick-vipno\' : \'\'}}" data-profile-uin="{{uin}}" soda-bind-html="user|showUserName"></span>\r\n    <label soda-if="user.gender != 0" class="author-{{user.gender==1?\'\':\'fe\'}}male"></label>\r\n     \r\n    <span soda-bind-html="user|renderHonours"></span>\r\n    \r\n    <span soda-bind-html="ispostor|showPoster"></span>\r\n\r\n    \r\n    <span class="floor">{{index|realFloor}}楼</span>\r\n</div>\r\n\r\n\r\n<div class="content-wrapper">\r\n\r\n    <div class="content allow-copy" soda-bind-html="comment|processContent"></div>\r\n\r\n     \r\n    <div soda-repeat="pic in comment.pic_list" class="img-box pre-size" style="{{pic|getImgOffset}}" >\r\n        <img data-src="{{pic.url||pic}}" soda-src="{{pic|getImgSrc}}" >\r\n    </div>\r\n\r\n    \r\n    <div soda-repeat="audio in comment.audio_list" class="audio" rel="playAudio" data-url="{{audio.url|decodeStr}}" data-duration="{{audio.duration}}" data-pid="{{pid}}" data-index="{{$index}}" style="width: {{audio|getAudioWrapperWidth}}px">\r\n        <span class="icon"></span>\r\n        <span class="length">{{audio.duration || 0}}"</span>\r\n    </div>\r\n\r\n    \r\n    <div soda-repeat="music in comment.qqmusic_list"  class="music"  rel="openMusic" data-url="{{music.audio_url|decodeStr}}" data-pid="{{pid}}" data-id="{{music.id}}" data-index="{{$index}}" data-href="{{music.share_url|decodeStr}}" data-img="{{music.image_url|decodeStr}}" data-name="{{music.title}}" data-singer="{{music.desc}}">\r\n        <div class="avator" rel="playMusic" data-target=".music">\r\n            <img soda-src="{{music.image_url|decodeStr}}">\r\n            <span class="icon"></span>\r\n        </div>\r\n        <div class="info" rel="openMusic" data-target=".music">\r\n            <div class="name"><span soda-bind-html="music.title"></span><i class="playing-icon"></i></div>\r\n            <div class="desc" soda-bind-html="music.desc"></div>\r\n        </div>\r\n    </div>\r\n\r\n    \r\n    <div soda-repeat="video in comment.video_list" class="post-video" id="vplayer_{{video.vid}}" data-vid="{{video.vid}}" data-pid="{{pid}}"></div>\r\n\r\n</div>\r\n';
    a.ref_comment = "TmplInline_detail.ref_comment",
    Tmpl.addTmpl(a.ref_comment, e);
    var f = '<div class="basic" id="js_act_basic">\r\n    <div class="bg"></div>\r\n    <div class="act-info">\r\n        <h3 class="title">\r\n            <label soda-if="!fromOpenAct" class="act">活动</label>\r\n            <span class="js-detail-title" soda-bind-html="title|plain2rich"></span>\r\n\r\n        </h3>\r\n        <div class="act-detail">\r\n            <div class="image-wrapper">\r\n                <img id="actDetailImage" class="image" soda-src="{{post.pic_list[0].url}}" />\r\n            </div>\r\n            <div class="detail-info">\r\n                <div class="wrapper">\r\n                    <p class="day-time">{{post.time}}</p>\r\n                    <p class="address">{{post.addr}}</p>\r\n                    <p class="tags">{{isOpenAct && !post.tag? \'同城活动\': post.tag}}</p>\r\n                    <p class="price">{{post.price + (post.price !== \'免费\'? \'（人均）\': \'\')}}</p>\r\n                </div>\r\n                <div class="block status">{{post.status|getStatusName}}</div>\r\n                <div soda-if="post.purchase_link" class="purchase-button" data-src="{{post.purchase_link}}">我要购票</div>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n<div class="introduce">\r\n    <h3>活动介绍</h3>\r\n    <div id="more_introduce">\r\n        <p class="more_introduce" soda-bind-html="post|processContent|matchSearch"></p>\r\n    </div>\r\n</div>\r\n<div class="people-joined ui-item section-1px" id="js_act_people">\r\n    <h3 class="people-joined-title">{{gameAct?\'预约\':\'参加\'}}的人</h3>\r\n    <div class="right-val"><span id="peopleNum"></span>人</div>\r\n    <div class="arrow"></div>\r\n    <div class="people-header" id="people_header_list"></div>\r\n</div>\r\n<div soda-if="post.from === \'openact\'" id="atvCreater" class="ui-item section-1px ui-item-oneline" rel="showProfile" type="2" code="{{user_info.uin}}">\r\n    <label>发起人</label>\r\n    <div class="right-val{{user_info.vipno ? \' right-val-vipno\' : \'\'}}">{{user_info.nick_name}}</div>\r\n    <div class="arrow"></div>\r\n</div>\r\n\r\n\r\n<div soda-if="hasQGroup" class="ui-item section-1px ui-item-oneline" id="js_act_qun" rel="showProfile" type="1" code="{{gid}}">\r\n    <label>关联群组</label>\r\n    <div class="right-val">{{ginfo.name}}</div>\r\n    <div class="arrow"></div>\r\n</div>\r\n';
    a.top_activity = "TmplInline_detail.top_activity",
    Tmpl.addTmpl(a.top_activity, f);
    var g = '\r\n<div class="content-wrapper allow-copy">\r\n\r\n    \r\n    <div soda-if="post.rss == 1">\r\n        <div soda-repeat="item in contentList" soda-bind-html="item|rssRender|matchSearch"></div>\r\n    </div>\r\n\r\n    \r\n    <div soda-if="type === 300 && post.rss != 1" soda-repeat="item in posts">\r\n        <div class="content" style="padding-top:15px" soda-bind-html="item|processContent|matchSearch"></div>\r\n        <div soda-repeat="pic in item.pic_list" class="img-box pre-size" style="{{pic|getImgOffset}}" >\r\n            <img data-src="{{pic.url||pic}}" lazy-src="{{pic|getImgSrc}}" >\r\n        </div>\r\n    </div>\r\n\r\n    \r\n    <div soda-if="type === 301" class="content rich_media_content" id="wechat_content" soda-bind-html="public_account_post.content"></div>\r\n\r\n    \r\n    <div soda-if="type === 302" class="richpost-new" soda-bind-html="post|richPostCompile"></div>\r\n\r\n\r\n    \r\n    <div soda-if="type !== 300 && type !== 301 && type !== 302">\r\n        <div class="content" soda-bind-html="post|processContent|matchSearch"></div>\r\n\r\n        \r\n        <div soda-repeat="pic in post.pic_list" class="img-box pre-size" style="{{pic|getImgOffset}}" >\r\n            <img data-src="{{pic.url||pic}}" lazy-src="{{pic|getImgSrc:$index}}" >\r\n        </div>\r\n\r\n        \r\n        <div soda-repeat="audio in post.audio_list" class="audio" rel="playAudio" data-url="{{audio.url|decodeStr}}" data-duration="{{audio.duration}}" data-pid="{{pid}}" data-index="{{$index}}" style="width: {{audio|getAudioWrapperWidth}}px">\r\n            <span class="icon"></span>\r\n            <span class="length">{{audio.duration||0}}"</span>\r\n        </div>\r\n\r\n        \r\n        <div soda-repeat="music in post.qqmusic_list"  class="music"  rel="openMusic" data-url="{{music.audio_url|decodeStr}}" data-pid="{{pid}}" data-id="{{music.id}}" data-index="{{$index}}" data-href="{{music.share_url|decodeStr}}" data-img="{{music.image_url|decodeStr}}" data-name="{{music.title}}" data-singer="{{music.desc}}">\r\n            <div class="avator" rel="playMusic" data-target=".music">\r\n                <img soda-src="{{music.image_url|decodeStr}}">\r\n                <span class="icon"></span>\r\n            </div>\r\n            <div class="info" rel="openMusic" data-target=".music">\r\n                <div class="name"><span soda-bind-html="music.title"><i class="playing-icon"></i></div>\r\n                <div class="desc" soda-bind-html="music.desc"></div>\r\n            </div>\r\n        </div>\r\n\r\n        \r\n        <div class="video-preview">\r\n            <div class="icon-play"><div class="triangle"></div></div>\r\n            <div class="preview-box"></div>\r\n        </div>\r\n\r\n        \r\n        <div soda-repeat="video in post.video_list" class="post-video" id="vplayer_{{video.vid}}" data-vid="{{video.vid}}" data-pid="{{pid}}"></div>\r\n    </div>\r\n\r\n    \r\n    <div soda-if="_isQQ && (post.is_recruit == 1 ||(posts && posts[1] && posts[1].is_recruit == 1))" class="apply-admin-apply-btn" id="js_detail_apply_post">点击申请酋长</div>\r\n\r\n    \r\n    <div soda-if="_isQQ && gname && related_group" class="booked-tribes-wraper">\r\n        <div class="booked-tribes border-1px openGroup" data-groupcode="{{related_group}}">\r\n            <span class="label border-1px">来自群</span><span class="groupname">{{gname}}</span>\r\n        </div>\r\n    </div>\r\n\r\n\r\n\r\n</div>\r\n\r\n<div class="actions">\r\n\r\n    <a soda-if="public_account_post && public_account_post.site" class="btn-action read-content" href="{{public_account_post.site|decodeStr}}">阅读原文</a>\r\n\r\n    <div soda-if="addr && addr.city" class="location-area text-overflow-ellipsis">\r\n        <span href="javascript:;" class="location" data-lat="{{addr.latitude}}" data-lon="{{addr.longitude}}" data-buzid="{{addr.buzId || \'\' }}">{{addr|getAddress}}</span>\r\n    </div>\r\n\r\n    <span class="read-num" ><i class="read-num-icon"></i>{{readnum + 1}}</span>\r\n\r\n    <a soda-if="_isAdmin || isposter" class="btn-action delete {{_isAdmin ? \'isAdmin\':\'\'}}" href="javascript:void(0)" id="js_detail_delete_post" title="删除">删除</a>\r\n\r\n    <a soda-if="!_isAdmin && !isposter && can_report" class="btn-action report" href="javascript:void(0)" id="js_detail_report" title="举报">举报</a>\r\n</div>\r\n';
    a.top_detail = "TmplInline_detail.top_detail",
    Tmpl.addTmpl(a.top_detail, g);
    var h = '<div class="live-title">\r\n  <label class="cls live">{{isVideo ? \'视频\' : \'直播\'}}</label>\r\n  <span class="js-detail-title" soda-bind-html="title|plain2rich"></span>\r\n</div>\r\n<div soda-if="!isVideo" class="summarize">\r\n  <div class="right">\r\n    <div class="live-status-button live_pre" id="live_status_button">\r\n      <div class="live-status-icon"></div>\r\n      <span id="live-status-wording">直播中</span>\r\n    </div>\r\n    <div class="message-button" id="detail_message_button" rel="publish_comment">\r\n      <div class="message-icon"></div>\r\n      <span class="replies" id="detail_replies_total">{{total_comment}}</span>\r\n    </div>\r\n  </div>\r\n</div>\r\n<div class="live-video">\r\n  <div id="mod_player"></div>\r\n</div>\r\n\r\n<div soda-if="post.content" class="live-content section-1px">\r\n  <h3>视频介绍</h3>\r\n  <p soda-bind-html="post|processContent|matchSearch"></p>\r\n\r\n  <p class="live-read-num-container"><span class="read-num"><i class="read-num-icon"></i>{{readnum}}</span></p>\r\n</div>\r\n\r\n<div soda-if="!isVideo" class="people-joined section-1px" id="js_act_people">\r\n  <a class="quit-link" id="quit_link" href="javascript:;">取消报名</a>\r\n  <h3 id="people_joined_title">参加的人<span class="num" id="people_num"></span></h3>\r\n  <div class="people-header" id="people_header_list"></div>\r\n  <div class="arrow-wrap">\r\n    <a href="javascript:void(0)" class="right-arrow"></a>\r\n  </div>\r\n</div>\r\n';
    a.top_live = "TmplInline_detail.top_live",
    Tmpl.addTmpl(a.top_live, h);
    var i = '<div class="qqmusic-bg" style="background-image: url({{music.image_url|decodeStr}})" ></div>\r\n<div class="qqmusic-bg-mask"></div>\r\n<div class="music-info">\r\n   <div class="qqmusic">\r\n        <span class="disc" style="background-image: url({{music.image_url|decodeStr}})" ></span>\r\n        <div class="qqmusic-player" rel="playMusic" data-special="1" data-url="{{music.audio_url|decodeStr}}" data-href="{{music.share_url|decodeStr}}" data-img="{{music.image_url|decodeStr}}" data-pid="{{pid}}" data-id="{{music.id}}"  data-name="{{music.title}}" data-singer="{{music.desc}}">\r\n            <span class="icon"></span>\r\n        </div>\r\n</div>\r\n\r\n<div class="qqmusic-info">\r\n    <div class="song-name">{{music.title}}</div>\r\n    <div class="singer"><span>{{music.desc}}</span></div>\r\n</div>\r\n<div  class="qqmusic-download-wrap">\r\n    <span id="js_download_music" class="qqmusic-download" data-id="{{music.id}}">下载这首歌</span>\r\n</div>\r\n</div>\r\n\r\n';
    a.top_music = "TmplInline_detail.top_music",
    Tmpl.addTmpl(a.top_music, i);
    var j = '<div class="pk-top">\r\n    <h2 class="title" soda-bind-html="title|plain2rich"></h2>\r\n    <p class="pk-text" soda-bind-html="post|processContent|matchSearch"></p>\r\n</div>\r\n\r\n<div class="pk-content">\r\n    \r\n    <div class="block" soda-if="!type">\r\n        <div class="left-img">\r\n            <div class="result-num-mask"></div>\r\n            <div class="result-num" data-role="left" data-num="{{vote_result.total_vote_result.ops[0].count}}">{{(vote_state > 0 || progress_state === 2) ? vote_result.total_vote_result.ops[0].count : 0}}</div>\r\n            <img soda-src="{{post.aSide.pic.url}}" />\r\n        </div>\r\n        <div class="right-img">\r\n            <div class="result-num-mask"></div>\r\n            <div class="result-num" data-role="right" data-num="{{vote_result.total_vote_result.ops[1].count}}">{{(vote_state > 0 || progress_state == 2) ? vote_result.total_vote_result.ops[1].count : 0}}</div>\r\n            <img soda-src="{{post.bSide.pic.url}}" />\r\n        </div>\r\n    </div>\r\n\r\n    \r\n    <div class="block" soda-if="type">\r\n        <div class="left block-content">\r\n            <span><p>{{post.aSide.content}}</p></span>\r\n        </div>\r\n        <div class="right block-content">\r\n            <span><p>{{post.bSide.content}}</p></span>\r\n        </div>\r\n    </div>\r\n\r\n    \r\n    <div class="button">\r\n        \r\n        <div id="left" class="left {{progress_state|getVoteState}}">\r\n            \r\n            <div soda-if="progress_state === 0" class="zan unprogress"></div>\r\n\r\n            \r\n            <div soda-if="progress_state === 1 && vote_state === 0" class="zan unvoted {{type?\'result-num-text\':\'\'}}" data-role="left" data-num="{{vote_result.total_vote_result.ops[0].count}}"></div>\r\n\r\n            \r\n            <div soda-if="progress_state === 2 || vote_state > 0" class="zan {{type?\'result-num-text-voted\':\'\'}}">\r\n                {{type?vote_result.total_vote_result.ops[0].count:\'\'}}\r\n            </div>\r\n\r\n            <div class="zan-effect"></div>\r\n            <div class="con">{{type?\'支持\':post.aSide.content}}</div>\r\n        </div>\r\n        \r\n        <div id="right" class="right {{progress_state|getVoteState}}">\r\n            <div class="con">{{type?\'支持\':post.bSide.content}}</div>\r\n            <div class="zan-effect"></div>\r\n\r\n            \r\n            <div soda-if="progress_state === 0" class="zan unprogress"></div>\r\n\r\n            \r\n            <div soda-if="progress_state === 1 && vote_state === 0" class="zan unvoted {{type?\'result-num-text\':\'\'}}" data-role="right" data-num="{{vote_result.total_vote_result.ops[1].count}}"></div>\r\n\r\n            \r\n            <div soda-if="progress_state === 2 || vote_state > 0" class="zan {{type?\'result-num-text-voted\':\'\'}}">\r\n                {{type?vote_result.total_vote_result.ops[1].count:\'\'}}\r\n            </div>\r\n        </div>\r\n        \r\n        <div class="middle">\r\n            <div class="mask {{progress_state|getVoteState}}">\r\n                <span>{{progress_state|getVoteStateText}}</span>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n';
    return a.top_pk = "TmplInline_detail.top_pk",
    Tmpl.addTmpl(a.top_pk, j),
    a
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : a.TmplInline_commentList = b()
} (this,
function() {
    var a = {},
    b = '<ul class="lists" id="comment_page_{{currentPage}}">\r\n    \r\n    <li soda-repeat="comment in comments" id="comment_{{comment.index}}" class="list  section-1px {{comment.ad?\'gdt-ad\':\'\'}}" cid="{{comment.cid}}" nick_name="{{comment.user.nick_name}}" data-lz="{{comment.index|realFloor}}">\r\n        <div soda-if="!comment.ad" class="comment-wrapper">\r\n            <div class="user-avatar" data-profile-uin="{{comment.uin}}">\r\n                <img lazy-src="{{comment.user.pic|defaultAvatar}}" soda-src="{{0|defaultAvatar}}">\r\n                <span soda-if="!_isStarGroup" class="g-level level-{{comment.user.level || 1}}"></span>\r\n            </div>\r\n\r\n            \r\n            <div class="name-wrap">\r\n                <span class="author user-nick {{comment.user.vipno ? \'user-nick-vipno\' : \'\'}}" data-profile-uin="{{comment.uin}}" soda-bind-html="comment.user|showUserName"></span>\r\n                <label soda-if="comment.user.gender != 0" class="author-{{comment.user.gender==1?\'\':\'fe\'}}male"></label>\r\n                 \r\n                <span soda-bind-html="comment.user|renderHonours"></span>\r\n                \r\n                <span soda-bind-html="comment.ispostor|showPoster"></span>\r\n\r\n                \r\n                <span class="floor">{{comment.index|realFloor}}楼</span>\r\n\r\n                    \r\n         \r\n\r\n            </div>\r\n\r\n            \r\n            <div class="content-wrapper">\r\n\r\n                <div class="content allow-copy" id="detail_comment_{{comment.cid}}" soda-bind-html="comment.comment|processContent"></div>\r\n\r\n                \r\n                <div class="img-box-container" soda-if="comment.comment.pic_list && comment.comment.pic_list.length">\r\n                    <div soda-repeat="pic in comment.comment.pic_list" class="img-box" style="width:{{pic.boxWidth}}px;height:{{pic.boxHeight}}px;" >\r\n                        <img data-src="{{pic.url||pic}}" style="margin-top:{{pic.marginTop}}px;margin-left:{{pic.marginLeft}}px;width:{{pic.width}}px;height:{{pic.height}}px" soda-src="{{pic|getImgSrc:comment.cid}}" >\r\n                    </div>\r\n                </div>\r\n\r\n                \r\n                <div soda-repeat="audio in comment.comment.audio_list" class="audio" rel="playAudio" data-url="{{audio.url|decodeStr}}" data-duration="{{audio.duration}}" data-pid="{{pid}}" data-index="{{$index}}" style="width: {{audio|getAudioWrapperWidth}}px">\r\n                    <span class="icon"></span>\r\n                    <span class="length">{{audio.duration || 0}}"</span>\r\n                </div>\r\n\r\n                \r\n                <div soda-repeat="music in comment.comment.qqmusic_list"  class="music"  rel="openMusic" data-url="{{music.audio_url|decodeStr}}" data-pid="{{pid}}" data-id="{{music.id}}" data-index="{{$index}}" data-href="{{music.share_url|decodeStr}}" data-img="{{music.image_url|decodeStr}}" data-name="{{music.title}}" data-singer="{{music.desc}}">\r\n                    <div class="avator" rel="playMusic" data-target=".music">\r\n                        <img soda-src="{{music.image_url|decodeStr}}">\r\n                        <span class="icon"></span>\r\n                    </div>\r\n                    <div class="info" rel="openMusic" data-target=".music">\r\n                        <div class="name"><span soda-bind-html="music.title"></span><i class="playing-icon"></i></div>\r\n                        <div class="desc" soda-bind-html="music.desc"></div>\r\n                    </div>\r\n                </div>\r\n\r\n                \r\n                <div soda-repeat="video in comment.comment.video_list" class="post-video" id="vplayer_{{video.vid}}" data-vid="{{video.vid}}" data-pid="{{pid}}"></div>\r\n\r\n                \r\n                <div soda-if="comment.ref_comment" class="ref-comment" data-id="{{comment.ref_comment.cid}}" data-isdel="{{comment.ref_comment.isdel}}" >\r\n                    <span soda-if="!comment.ref_comment.isdel"><span soda-bind-html="comment.ref_comment.user|showUserName"></span>: </span>\r\n                    <span>{{comment.ref_comment.comment.textPrefix}}</span>\r\n                    <span soda-bind-html="comment.ref_comment.comment|processContent"></span>\r\n                </div>\r\n            </div>\r\n\r\n            \r\n            <div class="actions">\r\n                \r\n                <span class="btn-action time">{{comment.time|formatTime}}</span>\r\n\r\n                \r\n                \r\n\r\n                \r\n                <span soda-if="comment.iscommentor || _isPoster || _isAdmin" class="btn-action delete {{_isAdmin?\'isAdmin\':\'\'}}">删除</span>\r\n\r\n                \r\n                <span class="btn-action reply"></span>\r\n\r\n                \r\n                <span class="btn-action like {{comment.like == 1 ? \'liked\':\'\'}}">{{comment.liketotal|formatNum}}</span>\r\n            </div>\r\n        </div>\r\n\r\n        <div soda-if="comment.ad" class="comment-wrapper">\r\n            <div class="content" id="detail_comment_{{comment.cid}}" soda-bind-html="comment.comment.content|plain2rich"></div>\r\n        </div>\r\n    </li>\r\n</ul>\r\n';
    return a.normal = "TmplInline_commentList.normal",
    Tmpl.addTmpl(a.normal, b),
    a
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : a.TmplInline_actionsheet = b()
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
        e = b("items"),
        f = "";
        f += " ",
        d && (f += ' <div class="sheet-item sheet-title">', f += c(d), f += "</div> "),
        f += " ";
        for (var g = 0; g < e.length; g++) f += ' <div class="sheet-item ',
        f += c(e[g].type || ""),
        f += '" value="',
        f += c(g),
        f += '"> <div class="sheet-item-text" >',
        f += c(e[g].text || e[g]),
        f += "</div> </div> ";
        return f += ' <div class="sheet-item" value="-1" > <div class="sheet-item-text">取消</div> </div> '
    };
    return a.frame = "TmplInline_actionsheet.frame",
    Tmpl.addTmpl(a.frame, b),
    a
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : a.TmplInline_peopleList = b()
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
        var d = b("list"),
        e = b("admin_ext"),
        f = "";
        f += "";
        for (var g = 0; g < d.length; g++) {
            var h = d[g],
            i = "";
            i = 1 == h.gender ? " male": " female",
            f += ' <li class="focusList link_person_profile" data-profile-uin="',
            f += c(h.uin),
            f += '"> <a> <img src="',
            f += c(h.pic),
            f += '" class="list-img"> <div class="list-content"> <strong class="name">',
            f += c(h.nick_name),
            f += '</strong> <i class="sex-icon',
            f += c(i),
            f += '"></i> <p class="msg"> 话题:<span style="margin-right: 5px;">',
            f += c(h.threads),
            f += "</span> 部落:<span>",
            f += c(h.follow_bar),
            f += "</span> </p> </div> ",
            e && (f += ' <a class="delete-second-comment ', f += c(e ? "isAdmin": ""), f += '" href="javascript:void(0)" title="删除"></a> '),
            f += " </a> </li> "
        }
        return f += " "
    };
    return a.normal = "TmplInline_peopleList.normal",
    Tmpl.addTmpl(a.normal, b),
    a
}),
function(a, b) {
    a.Detail = new b
} (this,
function() {
    function a(a) {
        return a.indexOf("mmbiz.qpic.cn") > -1 && d.isIOS && (a = a.replace("&tp=webp", "")),
        a
    }
    function b() {
        d.isstargroup && $("body").addClass("stargroup"),
        $("#to_like").addClass("bid_" + d.bid),
        $.os.ios ? ($("#js_detail_main").css("position", "absolute"), bouncefix.add("detail-main"), bouncefix.add("page-icon-left")) : document.body.style.overflowY = "scroll"
    }
    function c(a, b) {
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
    var d = this,
    e = window.Q,
    f = {
        0 : "detail",
        100 : "activity",
        200 : "live",
        201 : "live",
        600 : "pk",
        900 : "music"
    };
    this.getTplName = function(a) {
        return f[a] || "detail"
    },
    this.report = function(a, b) {
        if (!this.isRenderFromLocal) {
            var c = {
                opername: "Grp_tribe",
                module: "post_detail",
                ver1: d.bid,
                obj1: d.pid,
                action: a
            };
            for (var f in b) b.hasOwnProperty(f) && (c[f] = b[f]);
            e.tdw(c)
        }
    },
    this.openActReport = function(a, b) {
        if (!this.isOpenAct) if ("number" == typeof a) e.monitor(a);
        else {
            var c = {
                opername: "Grp_ac_mobile",
                module: "detail",
                action: a,
                obj2: "M_WEB"
            };
            for (var d in b) b.hasOwnProperty(d) && (c[d] = b[d]);
            e.tdw(c)
        }
    },
    this.formatDate = function(a) {
        var b = new Date(a);
        return b.getMonth() + 1 + "月" + b.getDate() + "日" + (b.getHours() < 10 ? "0": "") + b.getHours() + ":" + (b.getMinutes() < 10 ? "0": "") + b.getMinutes()
    },
    this.getParam = function(a) {
        return Util.queryString(a) || Util.getHash(a)
    },
    this._initVar = function() {
        var a = this,
        b = navigator.userAgent;
        this.network = 1,
        mqq.device.getNetworkType(function(b) {
            a.network = b
        }),
        this.base = "http://xiaoqu.qq.com/mobile/";
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
        this.postData = null,
        this.postType = 0,
        this.currentCommentFloor = 0,
        this.currentCommentID = null,
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
        this.isIOS = $.os.ios,
        this.isABTest = window.ABTest.isInTest()
    },
    this.initSodaFilter = function() {
        var b = this,
        c = decodeURI(this.getParam("searchkw")),
        d = this.getParam("useCacheImg"),
        e = $(document).width() - 30,
        f = {
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
                if (!c) return a;
                var b = new RegExp(c, "gmi"),
                d = function(a, b, c) {
                    return /(<img)|(<a)/.test(c) ? a: ['<span class="keyword-match">', a, "</span>"].join("")
                };
                return a.replace(b, d)
            },
            defaultAvatar: function(a) {
                return a = a || "http://q.qlogo.cn/g?b=qq&nk=0&s=100",
                a.replace(/&amp;/g, "&")
            },
            showUserName: function(a) {
                return a ? (a.uin + "").indexOf("*") > -1 ? a.nick_name + "(" + a.uin + ")": a.nick_name: ""
            },
            renderHonours: honourHelper.renderHonours,
            showPoster: function(a) {
                return "detail" === b.getTplName(b.postType) ? honourHelper.renderPoster(a) : ""
            },
            formatTime: FormatTime,
            realFloor: function(a) {
                return 200 === b.postType ? a: a + 1
            },
            getStatusName: function(a) {
                return ["报名中", "进行中", "已结束"][a]
            },
            rssRender: function(a) {
                if (/^src:/.test(a)) return '<div class="img-box"> <img src="' + a.replace(/^src:/, "") + '"></div>';
                var b = plain2rich(a);
                return '<div class="content">' + b.replace(/<br>/g, '<p class="ph"></p>') + "</div>"
            },
            getImgSrc: function(a, c) {
                if ("string" == typeof a) return a;
                var e = a.url;
                return e = d && 0 === c ? b.getThumbUrl(e, "200") : "string" == typeof c ? b.getThumbUrl(e, "200") : b.getThumbUrl(e, 1 === b.network ? "1000": "640")
            },
            getImgOffset: function(a, b) {
                if ("string" == typeof a || !a.w || !a.h) return "";
                var c = a.w,
                d = a.h;
                return c > 200 && (d = e / c * d, c = e),
                b && a.h > 300 && a.h / a.w > 50 && (d = 300),
                "width:" + c + "px;height:" + d + "px"
            },
            decodeStr: function(a) {
                return $.str.decodeHtml(a)
            },
            getAudioWrapperWidth: function(a) {
                var b = 142 / 60,
                c = a.duration > 60 ? 226 : Math.round(84 + a.duration * b);
                return c
            },
            getAddress: function(a) {
                if ("object" != typeof a) return "";
                var b = a.city.replace("市", "");
                return b
            },
            processContent: function(a) {
                var b = !1;
                a.isRefComment && (b = !0);
                var c = plain2rich({
                    text: a.content,
                    urlInfos: a.urlInfo || [],
                    keyInfos: a.keyInfo || [],
                    onlyText: b
                }).replace(/<br>/g, '<p class="ph"></p>').replace(/{/g, "{").replace(/}/g, "}");
                return c
            },
            formatNum: function(a) {
                return 0 === a ? "": numHelper(a)
            },
            getVoteState: function(a) {
                return ["unprogress", "progress", "end"][a]
            },
            getVoteStateText: function(a) {
                return ["暂未开始", "VS", "结束"][a]
            },
            richPostCompile: function(b) {
                var c = b.richText;
                return c = c.replace(/<img.*?>/gm,
                function(b) {
                    return b = b.replace(/\{|\}/gm, "").replace(/\s{2,}/gm, " ").replace(/data\-size="(.*?)"/gm,
                    function() {
                        return ""
                    }).replace(/src="(.*?)"/gm,
                    function(b) {
                        if ( - 1 === b.indexOf("ugc.qpic.cn")) return b = a(b);
                        var c = b.lastIndexOf("/");
                        return b = b.split(""),
                        "0" !== b[c + 1] && b.splice(c + 1, 0, "0"),
                        b.join("").replace(/"{2,}/gm, '"')
                    }).replace("src=", "lazy-src=")
                }).replace(/<iframe.*?<\/iframe>/gm,
                function(a) {
                    return a.replace(/\{|\}/gm, "")
                }),
                c = plain2rich({
                    search: !0,
                    text: c,
                    urlInfos: b.urlInfo || [],
                    keyInfos: b.keyInfo || []
                })
            }
        };
        for (var g in f) f.hasOwnProperty(g) && window.sodaFilter(g, f[g])
    },
    this.showLockTip = function() {
        return this.isLocked ? (Tip.show("该话题已被锁定，不支持赞和评论"), !0) : !1
    },
    this.getThumbUrl = function(a, b) {
        return b = b || 0,
        a.indexOf("ugc.qpic.cn") > -1 && /\/$/.test(a) && (a += b),
        a
    },
    this.init = function() {
        return this.bid && this.pid ? (this.initSodaFilter(), b(), this.Post.init(), Login.continueLogin(), window.mqq && mqq.addEventListener && mqq.dispatchEvent("addreadnum", {
            bid: d.bid,
            pid: d.pid
        }), void(mqq.compare("5.8") > -1 && mqq.invoke("ui", "disableLongPress", {
            enable: !1
        }))) : void Tip.show("部落或者话题ID错误", {
            type: "warning"
        })
    },
    mqq.compare("5.3.2") >= 1 && (window.getBrowserSignature = c),
    this._initVar()
}),
function(a, b) {
    var c = a.Detail;
    c.Post = b(c)
} (this,
function(a) {
    function b() {
        return {
            bid: u,
            pid: v,
            barlevel: 1,
            start: 0,
            num: 10,
            get_like_ul: 1
        }
    }
    function c(b) {
        b.time && (b.time = FormatTime(b.time)),
        b._isAdmin = honourHelper.isAdmin(b.admin_ext),
        b._isQQ = a.isQQ,
        b.zan = b.zan || 0,
        b.likes = b.likes || 0,
        b.zan && !b.likes && (b.likes = 1),
        b.bar_pic = a.getThumbUrl(b.bar_pic),
        b.bar_intro = $.trim(b.bar_intro),
        b.isStarGroup = a.isStarGroup,
        (10055 === u || 10064 === u || 10210 === u) && (b.gameAct = !0, $("#to_join").html("预约"));
        var c = ~~a.getParam("lnum");
        c > 20 && $("#top_post_wrapper").hide()
    }
    function d(b, c) {
        var d = b.type,
        f = e;
        a.postData = b,
        a.postType = d,
        a.isLocked = b.locking,
        a.isBest = b.best,
        c && (f = null),
        100 === d ? a.Post.Activity.render(b, f) : 600 === d ? a.Post.PK.render(b, f) : 200 === d || 201 === d ? a.Post.Video.render(b, f) : 900 === d ? a.Post.Music.render(b, f) : a.Post.Normal.render(b, f)
    }
    function e(b) {
        n(),
        g(b),
        l(),
        b.total_comment_v2 >= 20 && $("#btnShowInturn").show(),
        h(b),
        1 === b.best && ("9564870-1436190258" === b.pid ? f({
            gameid: 1000001286,
            bar_name: "全民突击"
        }) : 1 === b.game_activate && f(b)),
        a.Comment.rock(),
        a.Share.init(b),
        a.Join.init(b),
        a.Events.init(),
        i(),
        imgHandle.setErrImg("http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/default-big.png"),
        imgHandle.lazy($("#detail_top_info")[0]),
        m(b),
        a.report("visit", {
            obj1: v,
            ver3: a.postType,
            ver4: a.gid
        })
    }
    function f(a) {
        $.getScript("http://imgcache.qq.com/club/gamecenter/widgets/gameBtn.js?_bid=" + u,
        function() {
            a.gameid && (window.gameBtn.init({
                appId: a.gameid,
                btn: document.getElementById("detail_game_btn"),
                src: "buluo",
                downloadTxt: "下载" + a.bar_name,
                launchTxt: "启动" + a.bar_name
            }), setTimeout(function() {
                $(".detail-game").show()
            },
            200))
        })
    }
    function g(b) {
        var c = $(".bottom-bar"),
        d = [];
        a.isABTest ? (d.push('<a href="javascript:;" class="item icon-forward" id="to_forward"></a>'), d.push('<a href="javascript:;" class="item icon-reply" id="to_reply"></a>'), d.push('<a href="javascript:;" class="item icon-like" id="to_like"></a>'), d.push('<a href="javascript:;" class="item icon-join" id="to_join">报名</a>')) : (d.push('<a href="javascript:;" class="item icon-like" id="to_like"></a>'), d.push('<a href="javascript:;" class="item icon-join" id="to_join">报名</a>'), d.push('<a href="javascript:;" class="item icon-reply" id="to_reply"></a>'), d.push('<a href="javascript:;" class="item icon-share" id="to_share"></a>')),
        c.html(d.join(""));
        var e = $(".icon-like"),
        f = $(".icon-reply"),
        g = $(".icon-forward"),
        h = $(".icon-share");
        600 === a.postType && e.hide(),
        2 === b.post.status ? c.addClass("has-joined") : 1 === b.is_joined ? c.addClass("has-joined") : c.removeClass("has-joined"),
        b.zan ? e.addClass("liked") : b.father_pid && "1" === b.expireStatus && e.addClass("disabled"),
        e.html(b.likes || "赞"),
        f.html(b.total_comment_v2 || "评论"),
        a.isABTest ? g.html(b.forwards || "转发") : h.html(b.share || "分享"),
        c.css("visibility", "visible")
    }
    function h(b) {
        if ("barindex" !== a.source && "openact" !== a.from && "work" !== a.source) {
            var c = new t({
                comment: "bar_nav",
                renderTmpl: window.TmplInline_detail.bar_nav,
                renderContainer: "#bar_nav_wrapper",
                data: b
            });
            c.rock(),
            b.bar_name.length > 7 && $(".bar-nav .bar-info").addClass("single"),
            w("exp_nav")
        }
    }
    function i() {
        var b = document.getElementById("detail_top_info");
        b.addEventListener("load",
        function(b) {
            var c = $(b.target),
            d = c.attr("page-lazyload-time"),
            e = c.attr("src");
            /\/200$/.test(e) && e.indexOf("ugc.qpic.cn") > -1 ? (d && (d = Number(d), Q.isd(7832, 47, 11, +new Date - d, 25)), c.attr("src", e.replace(/\/200/g, 1 === a.network ? "/1000": "/640"))) : d && Q.isd(7832, 47, 11, +new Date - d, 26)
        },
        !0),
        b.addEventListener("error",
        function(a) {
            $(a.target).remove()
        },
        !0),
        $(window).on("hashchange",
        function() {
            var a = Util.getHash("poi");
            a || !Publish || Publish.isNative || Publish.hidePoiList()
        }),
        $(document).on("tap", ".bar-nav",
        function(b) {
            var c = $(b.target);
            c.hasClass("btn-focus") ? r.rock() : (w("Clk_nav"), Util.openUrl(a.base + "barindex.html#bid=" + u + "&scene=detail", !0))
        }),
        $("#people_header_list").on("tap", ".user-avatar",
        function() {
            var b = $(this),
            c = b.data("uin"),
            d = 1 === b.data("ban");
            if (a.postData.isOpenAct) {
                if (d) return;
                mqq.ui.showProfile({
                    uin: c
                }),
                a.openActReport("Clk_uindata")
            } else Util.openUrl("http://xiaoqu.qq.com/mobile/personal.html#uin=" + c, 1)
        })
    }
    function j() {
        try {
            var b = JSON.parse(localStorage.getItem("fastContent"));
            if (localStorage.removeItem("fastContent"), b) {
                if (b.user_info = b.user, b._fromcache = !0, a.isRenderFromLocal = 1, 0 !== b.type) return;
                b.post.content = b.post.content.replace(/<br>/g, "\n"),
                window.fastImgTime = Date.now(),
                d(b, !0),
                g(b),
                Q.isd(7832, 47, 4, +new Date - window.pageStartTime, 21),
                Q.isd(7832, 47, 11, +new Date - window.pageStartTime, 10, null, "all")
            }
        } catch(c) {
            Q.monitor(454455)
        }
    }
    function k(a, b) {
        $(".post-error").show().find("span").text(a),
        $("body,html").css("overflow", "hidden"),
        b && Tip.show(a, {
            type: "warning"
        })
    }
    function l() {
        if (a.isABTest) {
            var b = localStorage.getItem("im_detail_foward_guide");
            if (!b) {
                var c = $('<div class="forward-mask"><div class="forward-guide-text"></div><div class="forward-guide-arrow"></div><div class="forward-guide-button">转发</div></div>').appendTo("body");
                600 === a.postType && $(".forward-guide-button").css("width", "50%"),
                c.show(),
                c.on("tap",
                function() {
                    c.remove(),
                    c = null,
                    localStorage.setItem("im_detail_foward_guide", 1)
                })
            }
        }
    }
    function m(a) {
        if (!x) {
            var b, c, d = a.post.urlInfo || {},
            e = 0,
            f = "",
            g = "",
            h = {
                "add-group": 1,
                tribe: 2,
                personal: 3,
                post: 4,
                normal: 5
            };
            for (b in d) d.hasOwnProperty(b) && (c = d[b], "keyword" === c.type ? (e++, f += c.bid || c.content + "/") : g += h[c.type] + "/");
            e && w("visit_keyword", {
                ver3: e,
                ver4: f
            }),
            g && w("exp_link", {
                ver3: g,
                ver4: 1
            }),
            x = !0
        }
    }
    function n() {
        var b, c = Date.now() - window.pageStartTime,
        d = window.performance,
        e = 0,
        f = a.isIOS;
        Q.huatuo(1486, 1, 3, c, 2),
        d && d.timing && Q.huatuo(1486, 1, 3, d.timing.responseStart - d.timing.navigationStart, 1),
        window.fastHtmlEndTime && (b = window.fastHtmlEndTime - window.pageStartTime, Q.isd(7832, 47, 11, b, 23), Q.isd(7832, 47, 11, b, 16, null, "all"), delete window.fastHtmlEndTime),
        Q.isd(7832, 47, 4, c, 20),
        Q.isd(7832, 47, 11, c, 4, null, "all"),
        e = a.isQQ ? f ? 9 : 11 : f ? 10 : 12,
        Q.isd(7832, 47, 31, c, e)
    }
    function o() {
        ViewPreloader.useViewPreload() ? (Q.monitor(612158), ViewPreloader.receive(["_data_detail_content"],
        function(a) {
            var b, c = a.status,
            d = a.dataName;
            console.log("预加载数据：", a),
            ("_data_detail_content" === d || 4 === c) && 1 !== c && (0 === c ? (b = a.preloadData, 0 === b.retcode ? q.complete(b) : q.error(b)) : (q.rock(), Q.monitor(612159), 4 === c && Q.monitor(516236)))
        })) : q.rock()
    }
    function p() {
        j(),
        o()
    }
    var q, r, s = cgiModel,
    t = renderModel,
    u = a.bid,
    v = a.pid,
    w = a.report,
    x = !1;
    return q = new s({
        comment: "post_main_model",
        cgiName: "/cgi-bin/bar/post/content",
        param: b,
        complete: function(b) {
            a.isRenderFromLocal = 0,
            b = b.result || {},
            c(b),
            d(b)
        },
        error: function(b) {
            return 10 === b.retcode ? (k("该话题已被删除", !0), Q.monitor(630408), w("delete_exp"), void setTimeout(function() {
                return Boolean(a.isNewWebView) && window.mqq ? void mqq.ui.popBack() : void Util.openUrl(a.base + "barindex.html#bid=" + u + "&scene=detail", !1, 0, !0)
            },
            3e3)) : 101001 === b.retcode ? void k("您没有权限查看该内容") : void k("加载失败[" + b.retcode + "],请稍后再试")
        }
    }),
    $.getScript = function(a, b) {
        var c = document.createElement("script");
        c.async = "async",
        c.src = a,
        b && (c.onload = b),
        document.getElementsByTagName("head")[0].appendChild(c)
    },
    r = new s({
        comment: "voteCgi",
        cgiName: "/cgi-bin/bar/user/fbar",
        param: {
            bid: u,
            op: 1
        },
        processData: function(a) {
            return a.result.errno ? void Tip.show("关注失败[" + a.result.errno + "]", {
                type: "warning"
            }) : (w("focus_nav"), $(".btn-focus").addClass("focused").html("已关注"), void mqq.dispatchEvent("event_focus_tribe", {
                bid: u,
                focus: !0
            }))
        },
        error: function(a) {
            Tip.show("关注失败[" + a.retcode + "]", {
                type: "warning"
            })
        }
    }),
    {
        init: p
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Post.Normal = b(c, window.TmplInline_detail_header, window.TmplInline_detail)
} (this,
function(a, b, c) {
    function d() {
        $("#js_detail_delete_post").tap(function() {
            var b, c = {
                bid: q,
                pid: r
            };
            MediaPlayer.stop({
                type: "audio"
            }),
            b = {
                url: "/cgi-bin/bar/post/delete",
                type: "POST",
                param: c,
                succ: function() {
                    if (s("Clk_delpub"), Boolean(a.isNewWebView) && window.mqq && "barindex" === a.source) return void mqq.ui.popBack();
                    var b = document.referrer;
                    b.indexOf("barindex.html") > -1 ? history.back() : Util.openUrl(a.base + "barindex.html#bid=" + q + "&scene=detail", !1, 0, !0)
                },
                err: function(a) {
                    var b = "删除失败[" + a.retcode + "]";
                    101e3 === a.retcode ? b = "此为管理员发表，无法删除": 4004 === a.retcode && (b = "该话题已被推荐为热门，请于2天后删除"),
                    Tip.show(b, {
                        type: "warning"
                    })
                }
            },
            $(this).hasClass("isAdmin") ? window.setTimeout(function() {
                ActionSheet.show({
                    items: ["同时将该用户拉黑", "只删除不拉黑"],
                    onItemClick: function(a) {
                        c.black = 0 === a ? 1 : 0,
                        DB.cgiHttp({
                            param: {
                                bid: q,
                                pid: r,
                                black: c.black
                            },
                            url: "/cgi-bin/bar/post/delete",
                            succ: function() {
                                Tip.show("删除成功", {
                                    type: "ok"
                                }),
                                setTimeout(function() {
                                    mqq.ui.popBack()
                                },
                                500)
                            },
                            err: function() {
                                Tip.show("删除失败，请稍后重试。", {
                                    type: "warning"
                                })
                            }
                        })
                    }
                })
            },
            0) : DB.cgiHttp(b)
        }),
        $("#js_detail_report").on("tap",
        function() {
            return s("Clk_report"),
            isNaN(Number(t)) || Number(t) <= 0 ? void Alert.show("", "楼主帐号异常，无法举报", {
                confirm: "我知道了"
            }) : void Util.openUrl("http://xiaoqu.qq.com/mobile/report.html#bid=" + q + "&pid=" + r + "&eviluin=" + t + "&impeachuin=" + a.myuin, !0)
        }),
        $(document).on("tap", ".icon-play",
        function() {
            Tip.show("视频上传及转码中，暂不能播放", {
                type: "warning"
            })
        }),
        $("#js_detail_apply_post").on("tap",
        function() {
            u ? ApplyAdmin.doApply(q, 5, u) : mqq.ui.showDialog({
                title: "提示",
                text: "部落酋长数已达到上限，暂时不支持申请酋长",
                needOkBtn: !0,
                needCancelBtn: !1
            })
        }),
        $(".detail-from").on("tap",
        function() {
            s("Clk_topentry", {
                obj1: r
            }),
            Util.openUrl(a.base + "barindex.html#bid=" + q + "&scene=detail", !0)
        }),
        $("#detail_body").on("tap", ".honour",
        function() {
            var a = $(this).data("url");
            a && a && mqq.ui.openUrl({
                target: 1,
                url: a
            })
        })
    }
    function e(a) {
        var b, c = document.body.clientWidth - 20,
        d = .75 * c;
        if (a.public_account_post && a.public_account_post.content) try {
            b = a.public_account_post,
            b.content = b.content.replace(/<iframe (.*?)height\="(.*?)"(.*?)<\/iframe>/g, '<iframe $1height="' + d + '"$3</iframe>').replace(/<iframe (.*?)width\="(.*?)"(.*?)<\/iframe>/g, '<iframe $1width="' + c + '"$3</iframe>').replace(/<iframe (.*?) src\="http:\/\/v.qq.com\/iframe\/player\.html(.*?)width\=([\d\.]*?)(["&])(.*?)<\/iframe>/g, '<iframe $1 src="http://v.qq.com/iframe/player.html$2width=' + c + "$4$5</iframe>").replace(/<iframe (.*?) src\="http:\/\/v.qq.com\/iframe\/player\.html(.*?)height\=([\d\.]*?)(["&])(.*?)<\/iframe>/g, '<iframe $1 src="http://v.qq.com/iframe/player.html$2height=' + d + "$4$5</iframe>").replace(/<iframe (.*?)style\="(.*?)height(\s*?):(.*?)px(.*?)"(.*?)<\/iframe>/g, '<iframe $1style="$2height:' + d + 'px$5"$6</iframe>').replace(/\?tp\=webp/g, "").replace(/(<img[^>]*>)/g, '<div class="img-box">$1</div>')
        } catch(e) {}
        loadjs.loadCss(window.loadCssConfig.wechatCss)
    }
    function f(b) {
        return m ? ($(".video-preview .preview-box").html(m), $(".video-preview").show(), $("#detail_top_info .post-video").hide(), void(a.isUploading = !0)) : 302 === l ? void MediaPlayer.initVPlayerMuti("#detail_top_info") : void(b.post.video_list && b.post.video_list.length && (b.vstat && 2 === b.vstat ? (MediaPlayer.initVPlayerMuti("#detail_top_info"), j(b)) : (a.isUploading = !0, i(b))))
    }
    function g() {
        var b = ActionButton.getUploadVideo(q, r);
        if (console.log("upload info", b), b) {
            a.isUploading = !0;
            var c = b.post.image1,
            d = '<img src="' + c + '" />',
            e = $(".video-preview .preview-box");
            h(c),
            e.length ? (e.html(d), $(".video-preview").show(), $("#detail_top_info .post-video").hide()) : m = d,
            Refresh.freeze()
        } else console.log("没有正在上传的视频")
    }
    function h(a) {
        a && mqq.data.writeH5Data({
            callid: r,
            path: "/buluo/video",
            key: "" + q + r,
            data: a
        })
    }
    function i(a) {
        mqq.data.readH5Data({
            callid: r,
            path: "/buluo/video",
            key: "" + q + r
        },
        function(b) {
            var c = "http://shp.qpic.cn/qqvideo/0/" + a.post.video_list[0].vid + "/400";
            0 === b.ret && b.callid === r && (c = b.response.data),
            $(".video-preview .preview-box").html('<img src="' + c + '" >'),
            $(".video-preview").show().siblings(".post-video").hide()
        })
    }
    function j(a) {
        a.isposter && mqq.data.deleteH5Data({
            callid: r,
            path: "/buluo/video",
            key: "" + q + r
        },
        function() {})
    }
    function k(b, c) {
        l = a.postType,
        $(document.body).addClass("topic-detail"),
        n.data = b,
        n.rock(),
        o.data = b,
        o.rock(),
        c && c(b)
    }
    var l, m, n, o, p = renderModel,
    q = a.bid,
    r = a.pid,
    s = a.report,
    t = 0,
    u = 0;
    return n = new p({
        comment: "post_header_model",
        renderTmpl: b.top_detail_header,
        renderContainer: "#detail_top_info_header",
        renderTool: honourHelper,
        events: function() {}
    }),
    o = new p({
        comment: "post_model",
        renderTmpl: c.top_detail,
        renderContainer: "#detail_top_info",
        renderTool: honourHelper,
        processData: function(a) {
            if (a.post.pic_list = a.post.pic_list || [], t = a.uin, 301 === l ? e(a) : 300 === l && 1 === a.post.rss && (a.posts && a.posts[1] ? a.contentList = window.getRSSContent(a.posts[1].content.trim()) : (a.contentList = [], console.error('缺少"posts"字段'))), 1 === ~~a.post.is_recruit) {
                var b = a.big_admin_num,
                c = a.sml_admin_num;
                3 > b && (u = 1),
                7 > c && (u = 2),
                3 > b && 7 > c && (u = 3),
                0 === b && (u = 11),
                "undefined" == typeof b && (u = 3)
            }
        },
        events: function() {},
        complete: function(b) {
            301 === l && $(".img-box").css("background", "none"),
            a.isRenderFromLocal || (f(b), d(), b.gname && a.isQQ && s("exp_grpsign"))
        }
    }),
    {
        render: k,
        triggerUploading: g
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Post.Activity = b(c, window.TmplInline_detail)
} (this,
function(a, b) {
    function c() {
        DB.cgiHttp({
            url: "http://qqweb.qq.com/cgi-bin/qqactivity/get_uin_grp_info",
            param: {
                gc: h.gid
            },
            succ: function(a) {
                h.role = a.role
            }
        })
    }
    function d() {
        var a = {
            url: "http://qqweb.qq.com/cgi-bin/qqactivity/get_activity_member_list",
            param: {
                type: 1,
                id: h.post.openact_id,
                from: 0,
                number: 30,
                flag: 1
            },
            succ: function(a) {
                0 === a.ec && (h.joined_ul = {
                    uinnum: a.count || a.list.length,
                    uins: a.list,
                    isAtvCGI: !0
                }),
                j.data = h,
                j.rock()
            },
            err: function() {
                j.data = h,
                j.rock()
            }
        };
        DB.cgiHttp(a)
    }
    function e() {
        a.isOpenAct = !0,
        h.isOpenAct = !0,
        h.fromOpenAct = "openact" === a.from,
        h.post.pic_list[0].url = h.post.pic_list[0].url.replace(/\/0$/, "/160"),
        $(document.body).addClass("openact"),
        c();
        var b = a.getParam("openact");
        o(b ? "exp_" + b.split("_")[0] : "exp_tribe", {
            module: "detail_open"
        }),
        o("exp"),
        o(448940)
    }
    function f() {
        var b, c = h.post;
        c.start && c.end && (c.time = m(c.start) + " - " + m(c.end)),
        h.hasQGroup = !!h.ginfo && 0 !== h.gid,
        c.purchase_link && (a.purchaseLink = $.str.decodeHtml(h.post.purchase_link), $("#to_join").html("购票")),
        b = +new Date,
        c.status = b > c.end ? 2 : b > c.start ? 1 : 0,
        c.pic_list[0].url = a.getThumbUrl(c.pic_list[0].url)
    }
    function g(a, b) {
        document.title = "活动",
        mqq.ui.refreshTitle(),
        $(document.body).addClass("topic-activity"),
        h = a,
        i = b,
        "openact" === h.post.from ? (l = 1, d()) : (j.data = h, j.rock())
    }
    var h, i, j, k = renderModel,
    l = 0,
    m = a.formatDate,
    n = a.report,
    o = a.openActReport;
    return j = new k({
        comment: "post_activity_model",
        renderTmpl: b.top_activity,
        renderContainer: "#detail_top_info",
        renderTool: honourHelper,
        processData: function() {
            l && e(h),
            f(h)
        },
        events: function() {
            $("body").on("tap", ".purchase_button",
            function() {
                var a = $(this).data("src");
                a && Util.openUrl(a, 1)
            }),
            $(".people-joined-title").tap(a.Join.showList)
        },
        complete: function() {
            a.Join.showListInPost(h),
            $("#js_act_qun").on("tap",
            function() {
                var b = $(this).attr("code");
                mqq.ui.showProfile({
                    uin: b,
                    uinType: 1
                }),
                n("Clk_grpsign"),
                a.openActReport("Clk_grpname")
            }),
            i && i(h)
        }
    }),
    {
        render: g
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Post.Video = b(c)
} (this,
function(a) {
    function b(b, c) {
        var d = new Date,
        e = +d,
        f = {
            wording: "",
            status: b > e ? 0 : e > c ? 2 : 1
        };
        switch (f.status) {
        case 0:
            var g = new Date(b);
            f.wording = 864e5 > b - e && d.getDate() === g.getDate() ? (g.getHours() < 10 ? "0": "") + g.getHours() + ":" + (g.getMinutes() < 10 ? "0": "") + g.getMinutes() : a.formateDate(b);
            break;
        case 1:
            f.wording = "直播中";
            break;
        case 2:
            f.wording = "已过期"
        }
        return f
    }
    function c(b, c) {
        d = a.postType,
        $(document.body).addClass("topic-live"),
        200 === d ? (document.title = "直播", mqq.ui.refreshTitle()) : $(document.body).addClass("topic-video"),
        e.data = b,
        e.rock(),
        c && c(b)
    }
    var d, e, f = renderModel;
    return e = new f({
        comment: "post_video_model",
        renderTmpl: window.TmplInline_detail.top_live,
        renderContainer: "#detail_top_info",
        processData: function(b) {
            b.post.image2 || (b.post.image2 = b.post.image1),
            b.post.image1 = a.getThumbUrl(b.post.image1),
            b.post.image2 = a.getThumbUrl(b.post.image2),
            201 === d && (b.isVideo = !0)
        },
        events: function() {
            $("#people_joined_title").tap(a.Join.showList)
        },
        complete: function(c) {
            var e = "",
            f = 1;
            if (c.post.channel ? (e = "" + c.post.channel, f = 1) : (f = 2, e = "" + c.post.vid), MediaPlayer.initVPlayer(e, c.post.image2, f), 200 === d) {
                if (c.post.start && c.post.end) {
                    var g = b(c.post.start, c.post.end);
                    $("#live_status_button").addClass(["live_pre", "living", "live_expire"][g.status]),
                    $("#live_status_wording").html(g.wording),
                    $("#live_status_button").show()
                }
                a.Join.showListInPost(c),
                a.commentOrder = 1,
                $("#btnShowInturn").html("顺序查看"),
                $(".show-inturn").removeClass("reverse")
            }
        }
    }),
    {
        render: c
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Post.Music = b(c)
} (this,
function(a) {
    function b(a, b) {
        c.data = a,
        c.rock(),
        b && b(a)
    }
    var c, d = {},
    e = navigator.userAgent,
    f = e.match(/QQMUSIC\/(\d[\.\d]*)/i),
    g = a.isWX,
    h = renderModel;
    return f && ($.browser.music = !0, f[1] && ($.browser.version = parseFloat(f[1].replace("0", ".")))),
    c = new h({
        comment: "post_music_model",
        renderTmpl: window.TmplInline_detail.top_music,
        renderContainer: "#detail_top_info",
        processData: function(a) {
            var b = a.post.qqmusic_list[0];
            a.music = b,
            a.isWeiXin = g
        },
        events: function() {
            $("#js_download_music").on("tap",
            function() {
                var a = $(this).data("id");
                return d.openMusic({
                    mid: 23,
                    k2: a
                }),
                !1
            })
        },
        complete: function() {
            $("#detail_top_info").addClass("qqmusic-wrap"),
            $("#detail_top_info_header").hide(),
            $("#js_detail_scroll_top").addClass("qqmusic-post")
        }
    }),
    function(a) {
        a.weixinReady = function(a) {
            window.WeixinJSBridge ? a() : document.addEventListener("WeixinJSBridgeReady",
            function() {
                a()
            })
        },
        a.checkInstall = function(b) {
            if (b = b ||
            function() {},
            g) a.weixinReady(function() {
                WeixinJSBridge.invoke("getInstallState", {
                    packageName: "com.tencent.qqmusic",
                    packageUrl: "qqmusic://"
                },
                function(a) {
                    var c = a.err_msg;
                    b(c.indexOf("get_install_state:yes") > -1 ? 1 : -1)
                })
            });
            else if ("undefined" != typeof mqq) if (mqq.app && mqq.app.isAppInstalled) {
                var c = "com.tencent.qqmusic";
                $.os.ios && (c = "qqmusic"),
                mqq.app.isAppInstalled(c,
                function(a) {
                    b(a ? 1 : -1)
                })
            } else b(0);
            else b($.browser.music ? 1 : 0)
        }
    } (d),
    function(a) {
        function b(a) {
            var b;
            if ($.os.ios) b = +new Date,
            location.href = a;
            else {
                var d = document.createElement("iframe");
                d.style.width = "1px",
                d.style.height = "1px",
                d.style.display = "none",
                d.src = a,
                b = +new Date,
                document.body.appendChild(d)
            }
            setTimeout(function() {
                var a = +new Date;
                1550 > a - b && c()
            },
            1500)
        }
        function c() {
            $.os.ios ? location.href = a.openMusic.downloadUrl.ios: "undefined" != typeof mqq && mqq.compare && mqq.compare("4.5") >= 0 ? mqq.app.downloadApp({
                appid: "1101079856",
                url: a.openMusic.downloadUrl.android,
                packageName: "com.tencent.qqmusic",
                actionCode: "2",
                via: "ANDROIDQQ.QQMUSIC.GENE",
                appName: "QQMUSIC"
            },
            function() {}) : location.href = a.openMusic.downloadUrl.android
        }
        a.openMusic = function(d, e) {
            e && (a.openMusic.downloadUrl.android = "http://misc.wcd.qq.com/app?packageName=com.tencent.qqmusic&channelId=" + e);
            var f = $.param(d || {}),
            g = "androidqqmusic://form=webpage&" + f;
            $.os.ios && (g = "qqmusic://qq.com?form=webpage&" + f),
            a.checkInstall(function(a) {
                1 === a ? location.href = g: -1 === a ? c() : b(g)
            })
        },
        a.openMusic.downloadUrl = {
            ios: "itms-apps://itunes.apple.com/cn/app/qq-yin-le/id414603431?mt=8",
            android: "http://misc.wcd.qq.com/app?packageName=com.tencent.qqmusic&channelId=10000609"
        },
        a.download = c
    } (d),
    {
        render: b
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Post.PK = b(c, window.TmplInline_detail)
} (this,
function(a, b) {
    function c(a) {
        return new n({
            cgiName: "/cgi-bin/bar/dailyact/vote",
            param: {
                bid: o,
                pid: p,
                op: a
            },
            complete: function(b) {
                var c = b.result.errno;
                0 === c ? 1 === a ? q("Clk_optiona", {
                    obj1: p
                }) : 2 === a && q("Clk_optionb", {
                    obj1: p
                }) : Tip.show("投票失败", {
                    type: "warning"
                })
            },
            error: function() {
                Tip.show("投票失败", {
                    type: "warning"
                })
            }
        })
    }
    function d() {
        function a(a, b) {
            b.hasClass("unprogress") || b.hasClass("end") || b.hasClass("voted") || (f(b), e(), q("vote", {
                ver3: a,
                opername: "tribe_cgi",
                module: "post"
            }), c(a + 1).rock())
        }
        j.on("tap",
        function() {
            a(0, $(this))
        }),
        k.on("tap",
        function() {
            a(1, $(this))
        })
    }
    function e() {
        j.off("tap"),
        k.off("tap")
    }
    function f(a) {
        var b, c = a.attr("id"),
        d = a.closest(".pk-content");
        0 === r ? ("left" === c ? ($(".left-img").addClass("img-active"), k.addClass("unvoted")) : ($(".right-img").addClass("img-active"), j.addClass("unvoted")), a.addClass("voting")) : (b = d.find("." + c), b.addClass("img-active"), "left" === c ? (j.addClass("text-voted"), k.addClass("text-unvoted")) : (j.addClass("text-unvoted"), k.addClass("text-voted")), $.os.android && setTimeout(function() {
            j.addClass("hide-zan"),
            k.addClass("hide-zan")
        },
        700)),
        d.addClass("voting");
        var e = 550;
        1 === r && (e = 700),
        setTimeout(function() {
            $(".result-num, .result-num-text").each(function() {
                var a = ~~$(this).data("num");
                g($(this), $(this).data("role") === c ? a + 1 : a)
            })
        },
        e)
    }
    function g(a, b, c) {
        function d(j) {
            f = f || j,
            g = j;
            var k = j - f;
            i = c - k,
            h = e(k, 0, b, c),
            h = Math.ceil(h),
            h = h > b ? b: h,
            a.text(h),
            c > k && window.requestAnimationFrame(d)
        }
        function e(a, b, c, d) {
            return a === d ? b + c: c * ( - Math.pow(2, -10 * a / d) + 1) + b
        }
        var f = null,
        g = null,
        h = null,
        i = null;
        c = 1e3 * c || 1e3,
        window.requestAnimationFrame(d)
    }
    function h(b, c) {
        i = a.postType,
        $(document.body).addClass("topic-pk"),
        document.title = "投票",
        mqq.ui.refreshTitle(),
        l.data = b,
        l.rock(),
        c && c(b),
        q("exp_vote", {
            obj1: p,
            ver3: t
        })
    }
    var i, j, k, l, m = renderModel,
    n = cgiModel,
    o = a.bid,
    p = a.pid,
    q = a.report,
    r = 0,
    s = 0,
    t = 0;
    return l = new m({
        comment: "post_pk_model",
        renderTmpl: b.top_pk,
        renderContainer: "#detail_top_info",
        processData: function(a) {
            s = ~~a.vote_result.user_vote_result.op,
            a.vote_state = s;
            var b = parseInt((new Date).getTime().toString().substring(0, 10), 10);
            t = b < a.post.start_time ? 0 : b > a.post.end_time ? 2 : 1,
            a.post.time_type && t && (t = 1),
            a.progress_state = t,
            r = "undefined" == typeof a.post.aSide.pic ? 1 : 0,
            a.type = r
        },
        events: function() {
            j = $("#left"),
            k = $("#right"),
            0 === s && 1 === t && d()
        },
        complete: function() { (1 === t && 0 !== s || 2 === t) && (1 === s ? (j.addClass("voted").css("background-color", "#3b78ce"), k.addClass("voted").css({
                "background-color": "#686868",
                color: "rgba(255,255,255,.5)"
            }), 0 === r && j.find(".zan").addClass("voted")) : 2 === s && (j.addClass("voted").css({
                "background-color": "#686868",
                color: "rgba(255,255,255,.5)"
            }), k.addClass("voted").css("background-color", "#2fca9d"), 0 === r && k.find(".zan").addClass("voted")), 0 === r && $(".result-num, .result-num-mask").css("visibility", "visible"))
        }
    }),
    {
        render: h
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Comment = b(c, window.TmplInline_commentList)
} (this,
function(a, b) {
    function c(b) {
        var c = -O;
        return function() {
            return c += O,
            {
                bid: M,
                pid: N,
                num: O,
                start: c + b,
                liveorder: a.commentOrder,
                barlevel: 1
            }
        }
    }
    function d() {
        var a = O;
        return O > P ? (a = P, P = 0) : P -= O,
        {
            bid: M,
            pid: N,
            num: a,
            start: P,
            barlevel: 1
        }
    }
    function e() {
        I.cgiName = 200 === w ? db: cb,
        I.param = d(),
        I.rock()
    }
    function f(b, c) {
        var d, e, f = b.result || {};
        for (f.comments = f.comments || [], R = c - 1, f.currentPage = R, 200 === w && (f.comments = o(f.comments)), d = 0, e = f.comments.length; e > d; d++) {
            var h = f.comments[d],
            i = h.comment.pic_list,
            j = h.ref_comment;
            if (i && i.length) {
                var k;
                k = i.length > 1 ? imgHandle.formatThumb(i, !0, _, _) : imgHandle.formatThumb(i, !1, 180, 180),
                h.comment.pic_list = k
            }
            if (j) {
                var l = j.comment.pic_list,
                m = j.comment.audio_list,
                n = j.comment.qqmusic_list,
                p = j.comment.video_list,
                q = "";
                j.comment.isRefComment = !0,
                l && l.length && (q = "[图片]"),
                m && m.length && (q = "[语音]"),
                n && n.length && (q = "[音乐]"),
                p && p.length && (q = "[视频]"),
                j.comment.textPrefix = q,
                j.isdel && (j.comment.textPrefix = "", j.comment.content = "评论已删除"),
                ab[j.cid] = j
            }
        }
        1 === c && g(f),
        b.result.comments.length ? ($(".empty-comment").hide(), $("#js_detail_scroll_top").removeClass("no-comment")) : $("#js_detail_scroll_top").addClass("no-comment"),
        f._isAdmin = honourHelper.isAdmin(f.admin_ext),
        f._myuin = S,
        f._isPoster = x,
        f._isStarGroup = a.isStarGroup
    }
    function g(a) {
        var b = P;
        $.each(U,
        function(c, d) {
            V = !1,
            d - 1 >= b && d - 1 < b + a.comments.length && (V = !0, a.comments.splice(d - b - 1, 0, {
                cid: "ad-" + d,
                ad: !0,
                post: {},
                user: {},
                comment: {}
            }))
        }),
        V && ad.init("comment")
    }
    function h() {
        var b = "";
        return b = 1 === a.commentType ? a.commentOrder ? db: cb: eb
    }
    function i(a, b) {
        Z = !1,
        j(a),
        H.melt(),
        F.hide(),
        G.show(),
        D.show(),
        Y.off("scroll.down").off("scroll.up"),
        E.empty(),
        H.cgiName = h(),
        H.refresh(),
        $("#recommend-list").hide(),
        y = b
    }
    function j(b) {
        "undefined" == typeof b && (X = 0),
        P = b ? 200 === w ? a.postData.total_comment_v2 - b: b - 1 : 0,
        H.paramCache = [];
        var d = $(".show-more-before");
        P ? (H.param = c(P), d.show()) : (H.param = c(0), d.hide())
    }
    function k(b) {
        if (console.log("评论成功", b), Y.off("scroll.up").off("scroll.down"), E.empty(), Z && (R = 0), 1 === a.commentOrder || 2 === a.commentType) return void H.refresh();
        var c = b.new_index || b.post.total + 1,
        d = parseInt((c - 1) / O);
        d === R ? l(d,
        function(a) {
            $("#comment_page_" + d).html(a),
            imgHandle.lazy($("#comment_page_" + d)[0]),
            q(c, !1,
            function() {})
        }) : l(d,
        function(b, e) {
            n(e, c) && (E.append(b), H.freeze(), q(c, !1,
            function() {
                m(d),
                z.addClass("spinner").html("载入中，请稍候...").show(),
                G.hide(),
                D.hide(),
                Z || 0 === a.currentCommentFloor ? F.text("回到顶部").show() : F.text("回到" + a.currentCommentFloor + "楼").show(),
                100 !== w && a.Recommend.rock()
            }))
        })
    }
    function l(a, c) {
        var d = new L({
            comment: "comment_page_model",
            cgiName: cb,
            param: {
                bid: M,
                pid: N,
                num: O,
                start: a * O,
                barlevel: 1
            },
            noCache: 1,
            renderTmpl: b.normal,
            renderContainer: $(document.createDocumentFragment()),
            processData: function(b) {
                f.call(this, b, a + 1)
            },
            complete: function(a) {
                c(this.renderContainer, a)
            }
        });
        d.rock()
    }
    function m(b) {
        function c() {
            if (Y.scrollTop() < 60) {
                if (g) return;
                if (console.log("加载上一页"), e === H.cgiCount - 1) {
                    z.hide(),
                    G.show(),
                    D.show(),
                    F.hide(),
                    Y.off("scroll.down");
                    var a = $("#comment_page_" + (e + 1)).position().top;
                    return void Y.scrollTop(a)
                }
                z.addClass("spinner").html("载入中，请稍候...").show(),
                g = !0,
                l(e,
                function(a) {
                    g = !1,
                    E.prepend(a);
                    var b = $("#comment_page_" + (e + 1)).position().top;
                    imgHandle.lazy(A[0]),
                    Y.scrollTop(b - 63),
                    e--
                })
            }
        }
        function d() {
            var b = Y.scrollTop(),
            c = $.os.ios ? Y[0].scrollHeight - 100 : $("body")[0].scrollHeight - 160,
            d = $(window).height();
            100 > c - b - d && (h || (h = !0, l(f + 1,
            function(b, c) {
                h = !1,
                E.append(b),
                imgHandle.lazy(A[0]),
                f++,
                c.result.isend && (Y.off("scroll.up"), a.Recommend.rock())
            })))
        }
        var e = b - 1,
        f = b,
        g = !1,
        h = !1;
        Y.off("scroll.down").on("scroll.down", c).off("scroll.up").on("scroll.up", d),
        Y.scrollTop() < 60 && c()
    }
    function n(a, b) {
        for (var c = a.result.comments || [], d = c.length, e = 0, f = !1; d > e; e++) if (c[e].index === b) {
            f = !0;
            break
        }
        return f
    }
    function o(a) {
        return a
    }
    function p() {
        $("#btnShowInturn").tap(function() {
            var b = $(this);
            a.commentOrder ? (a.commentOrder = 0, b.text("倒序查看").parent().addClass("reverse"), T("Clk_inturn")) : (a.commentOrder = 1, b.text("顺序查看").parent().removeClass("reverse"), T("Clk_reverse")),
            i()
        }),
        $("#show-more-before").tap(function() {
            e()
        }),
        A.on("tap", ".btn-action",
        function() {
            var a = $(this),
            b = a.closest("li"),
            c = b.attr("cid");
            if (c) return a.hasClass("delete") ? void J.delWithConfirm("delComment", {
                cid: c
            },
            a, b) : void(a.hasClass("like") && J.likeComment(a, b))
        }).on("tap", ".ref-comment",
        function() {
            var a = $(this).data("id"),
            b = ~~$(this).data("isdel");
            a && 1 !== b && (T("Clk_quote_layer"), s(a))
        }),
        A.on("click", ".btn-action.reply",
        function() {
            var b = $(this),
            c = b.closest("li"),
            d = c.attr("cid"),
            e = ~~c.data("lz"),
            f = "commentReply";
            a.currentCommentFloor = e,
            a.currentCommentID = d,
            c.find(".ref-comment").length ? (T("Clk_reply_layer"), f = "refReply") : T("Clk_reply_own"),
            T("Clk_reply_one"),
            a.Publish.reply(d, f)
        }),
        B.on("tap",
        function(a) {
            var b = $(a.target);
            return b.closest(".user-avatar").length && T("layer_head"),
            b.closest(".audio").length ? void T("layer_voice") : b.closest(".music").length ? void T("layer_music") : b.closest(".post-video").length ? void T("layer_video") : void t()
        }),
        F.on("tap",
        function() {
            if ($(this).hide(), G.show(), D.show(), Y.off("scroll.down").off("scroll.up"), $("#recommend-list").hide(), z.addClass("spinner").html("载入中，请稍候...").show(), "回到顶部" === $(this).text()) T("return_top"),
            Y.scrollTop(0),
            H.melt(),
            E.empty(),
            0 === H.cgiCount && H.rock();
            else {
                var b = $.os.ios ? Y[0].scrollHeight - 100 : $("body")[0].scrollHeight - 160;
                T("return_floor"),
                Y.scrollTop(b),
                q(a.currentCommentFloor - 1, !0,
                function() {
                    H.melt(),
                    E.empty()
                })
            }
        })
    }
    function q(a, b, c) {
        function d(a) {
            j = j || a,
            k = a;
            var f = a - j;
            m = h - f,
            l = e(f, n, o, h),
            b && (l = i - l),
            Y.scrollTop(l),
            h > f ? window.requestAnimationFrame(d) : (c && c(), console.log("scroll done! scrollTop:", l), imgHandle.lazy(A[0]))
        }
        function e(a, b, c, d) {
            return c * ((a = a / d - 1) * a * a + 1) + b
        }
        var f = $("#comment_" + a);
        if (f.length) {
            var g = f.position().top + 44,
            h = 1e3,
            i = Y.scrollTop(),
            j = null,
            k = null,
            l = null,
            m = null,
            n = i,
            o = g;
            return b && (o = i - g + 64, n = 0),
            Z ? (Y.scrollTop(g), c && c(), void imgHandle.lazy(A[0])) : void window.requestAnimationFrame(d)
        }
    }
    function r(b) {
        if (Z = !0, console.log("直接跳转到", b, "楼"), b > 0 && O > b) H.cgiName = h(),
        H.myData = b - 1,
        H.rock();
        else if (b >= O) {
            var c = parseInt((b - 2) / O);
            l(c,
            function(d, e) {
                return n(e, b - 1) ? (H.freeze(), E.append(d), z.addClass("spinner").html("载入中，请稍候...").show(), G.hide(), D.hide(), void q(b - 1, !1,
                function() {
                    m(c),
                    F.text("回到顶部").show(),
                    e.result.isend && a.Recommend.rock()
                })) : (G.show(), D.show(), Y.scrollTop(0), H.melt(), E.empty(), void(0 === H.cgiCount && H.rock()))
            })
        }
    }
    function s(b) {
        var c = ab[b];
        c && (a.isRefDlgOpen = !0, fb.data = c, fb.rock())
    }
    function t() {
        $(document).off("touchmove.dialog"),
        C.addClass("close-anim-out"),
        B.removeClass("fade-in").addClass("fade-out"),
        setTimeout(function() {
            $(".detail-main,.bottom-bar").removeClass("blur")
        },
        50),
        B.on("webkitAnimationEnd",
        function() {
            a.isRefDlgOpen = !1,
            B.hide().removeClass("fade-out"),
            C.removeClass("close-anim-out dlg-anim"),
            $(".comment-dialog-content .user-avatar,.comment-dialog-content .name-wrap").removeClass("dlg-anim"),
            B.off("webkitAnimationEnd")
        })
    }
    function u() {
        z = $("#js_comment_loading"),
        A = $("#js_detail_list"),
        B = $("#comment_dialog"),
        C = $(".comment-dialog-close"),
        D = $("#top_comment_wrapper"),
        E = $("#bottom_comment_wrapper"),
        G = $("#top_post_wrapper"),
        F = $("#back_to_top")
    }
    function v() {
        w = a.postType,
        x = a.postData.isposter,
        u(),
        $(".show-inturn").show(),
        p(),
        "true" === a.getParam("nojump") && (W = !0),
        W ? i(X) : X ? r(X) : i(0)
    }
    var w, x, y, z, A, B, C, D, E, F, G, H, I, J, K = scrollModel,
    L = renderModel,
    M = a.bid,
    N = a.pid,
    O = 20,
    P = 0,
    R = 0,
    S = Login.getUin(),
    T = a.report,
    U = [4],
    V = !1,
    W = !1,
    X = ~~a.getParam("lnum"),
    Y = $($.os.ios ? "#js_detail_main": window),
    Z = !1,
    _ = Math.floor(($(document).width() - 75) / 3) - 4,
    ab = {},
    bb = "_v2",
    cb = "/cgi-bin/bar/post/get_comment_by_page" + bb,
    db = "/cgi-bin/bar/post/get_comment_by_page_reverse" + bb,
    eb = "/cgi-bin/bar/post/get_comment_by_page_with_user" + bb;
    H = new K({
        comment: "comment_model",
        cgiName: cb,
        renderTmpl: b.normal,
        renderContainer: "#top_comment_wrapper",
        scrollEl: $($.os.ios ? "#js_detail_main": window),
        param: c(0),
        noCache: 1,
        renderTool: honourHelper,
        processData: f,
        events: function() {},
        complete: function(b, c) {
            MediaPlayer.initVPlayerMuti(this.renderContainer),
            imgHandle.lazy(A[0]),
            b.result.isend ? (z.hide(), this.freeze()) : b.result.comments.length < 10 && Q.monitor(609401),
            V && 1 === c && (console.debug("detail comment开始show广告"), ad.show("comment", "#js_detail_list .gdt-ad"), V = !1),
            b.result.isend && 100 !== w && a.Recommend.rock(),
            1 === c && 0 === b.result.comments.length && b.result.isend && !P ? $(".empty-comment").show() : $(".empty-comment").hide(),
            y && y(b),
            this.myData && (q(this.myData, !1), this.myData = null)
        },
        onreset: function() {
            $(this.renderContainer).empty(),
            z.addClass("spinner").html("载入中，请稍候...")
        },
        error: function(a) {
            z.removeClass("spinner").html("拉取失败，请稍后重试[" + a.retcode + "]")
        }
    }),
    I = new L({
        comment: "prepage_comment_model",
        cgiName: "",
        noCache: 1,
        renderTmpl: b.normal,
        renderTool: honourHelper,
        renderContainer: $(document.createDocumentFragment()),
        processData: f,
        complete: function() {
            var a = $(H.renderContainer).find("li").first(),
            b = a.position().top,
            c = Y.scrollTop(),
            d = 0;
            this.renderContainer.prependTo(H.renderContainer),
            d = a.position().top,
            Y.scrollTop(c + d - b),
            imgHandle.lazy(A[0]),
            0 === P && $(".show-more-before").hide()
        }
    }),
    J = {
        error: function(a) {
            var b = "删除失败[" + a.retcode + "]";
            101e3 === a.retcode && (b = "此为管理员发表，无法删除"),
            Tip.show(b, {
                type: "warning"
            })
        },
        delWithConfirm: function(a, b, c, d) {
            var e = {
                bid: M,
                pid: N
            };
            return e = $.extend(e, b),
            c.hasClass("isAdmin") ? void window.setTimeout(function() {
                ActionSheet.show({
                    items: ["同时将该用户拉黑", "只删除不拉黑"],
                    onItemClick: function(b) {
                        switch (b) {
                        case 0:
                            e.black = 1;
                            break;
                        case 1:
                            e.black = 0
                        }
                        J[a](e, c, d)
                    }
                })
            },
            0) : void J[a](e, c, d)
        },
        delComment: function(b, c, d) {
            DB.cgiHttp({
                type: "POST",
                url: "/cgi-bin/bar/post/del_comment",
                param: b,
                succ: function() {
                    d.remove(),
                    x && T("del_reply", {
                        ver3: N
                    }),
                    a.openActReport("del_reply_suc")
                },
                err: J.error
            })
        },
        likeComment: function(a, b) {
            if (!a.hasClass("liked") && !a.hasClass("liked-active")) {
                var c = b.data("lz"),
                d = b.attr("cid"),
                e = b.find(".comment-user-info .user-nick").data("profile-uin");
                a.addClass("liked-active"),
                a.text(~~a.text() + 1),
                T("like_comment", {
                    obj1: N,
                    ver3: c
                }),
                DB.cgiHttp({
                    type: "POST",
                    url: "/cgi-bin/bar/post/like_comment",
                    param: {
                        bid: M,
                        pid: N,
                        cid: d,
                        tuin: e,
                        like: 1
                    }
                })
            }
        }
    };
    var fb = new L({
        comment: "ref_comment_dialog",
        renderTmpl: window.TmplInline_detail.ref_comment,
        renderContainer: "#comment_dialog_content",
        events: function() {
            bouncefix.add("comment-dialog")
        },
        complete: function() {
            $(".detail-main,.bottom-bar").addClass("blur"),
            B.show().addClass("fade-in"),
            C.addClass("dlg-anim"),
            setTimeout(function() {
                $(".comment-dialog-content .user-avatar,.comment-dialog-content .name-wrap").addClass("dlg-anim")
            },
            300),
            setTimeout(function() {
                $(".comment-dialog-content .content-wrapper").addClass("dlg-anim")
            },
            400),
            MediaPlayer.initVPlayerMuti(B[0]);
            var a = $(".comment-dialog");
            a[0].scrollHeight <= a.height() && $(document).on("touchmove.dialog",
            function(a) {
                a.preventDefault()
            })
        }
    });
    return {
        rock: v,
        refresh: i,
        loadPrevPage: e,
        afterReply: k,
        hideRefDialog: t
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Recommend = b(c)
} (this,
function(a) {
    function b() {
        var a, b, c = window.innerHeight,
        d = +new Date,
        f = !1;
        e[0].getBoundingClientRect().top < c && (j("exp_rela"), f = !0),
        f || (a = function() { + new Date - d > 160 && (f || (b = e[0].getBoundingClientRect().top, c > b && (j("exp_rela"), f = !0)), d = +new Date, f && $(document).off("touchmove", a))
        },
        $(document).on("touchmove", a))
    }
    function c() {
        e = $("#recommend-list").show(),
        d || (ad.init("recommend", h[0]), f.rock(), d = !0)
    }
    var d, e, f, g = renderModel,
    h = [3],
    i = a.bid,
    j = a.report;
    return f = new g({
        comment: "recommend_model",
        cgiName: "/cgi-bin/bar/post/related_posts",
        renderTmpl: window.TmplInline_detail.recommend,
        renderContainer: "#recommend-list",
        param: {
            bid: i,
            needbar: 1
        },
        noCache: 1,
        processData: function(a) {
            var b = a.result.postlist || [];
            $.each(h,
            function(a, c) {
                return c - 1 > b.length ? !1 : void b.splice(c, 0, {
                    cid: "ad1",
                    ad: !0,
                    user: {},
                    comment: {},
                    post: {}
                })
            })
        },
        events: function() {
            e.on("tap", ".recommend-post-list > li",
            function() {
                var a = $(this),
                b = a.attr("openactid");
                j("Clk_rela"),
                b ? Util.openQunAct(b) : Util.openDetail({
                    "#bid": a.data("bid"),
                    "#pid": a.data("pid")
                },
                null, a.data("type"))
            }),
            $(".from-bar-link").on("tap",
            function(b) {
                b.stopPropagation();
                var c = $(this),
                d = c.attr("bid");
                Util.openUrl(a.base + "barindex.html#bid=" + d + "&scene=detail", !0)
            }),
            b()
        },
        complete: function() {
            ad.show("recommend", "#recommend-list .gdt-ad", h[0])
        }
    }),
    {
        rock: c
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Events = b(c)
} (this,
function(a) {
    function b() {
        c(),
        $("#js_detail_main").on("tap", ".img-box img,.richpost-new img",
        function() {
            if (Q.monitor(648125), !window.ImageView) return Tip.show("图片查看器加载中，请稍后点击图片重试", {
                type: "warning"
            }),
            void Q.monitor(648126);
            var a, b, c, d, e, f = $(this),
            g = [],
            h = !1,
            i = !1;
            if (f.closest(".richpost-new").length && (h = !0), f.closest("#detail_top_info").length && (i = !0), a = h ? $(".richpost-new") : f.parent(), a.length) {
                b = h ? a.find("img") : a.closest(".content-wrapper").find(".img-box img"),
                c = 0,
                b.each(function(a) {
                    this === f[0] && (c = a),
                    d = $(this).data("src") || $(this).attr("lazy-src") || this.src,
                    h ? g.push({
                        name: "",
                        mbimg: d
                    }) : (d.indexOf("?") > -1 && (d = d.slice(0, d.indexOf("?"))), g.push({
                        name: "",
                        mbimg: $(this).attr("data-src") ? $(this).attr("data-src") + "1000": d
                    }))
                }),
                e = {
                    useNavigate: !0,
                    maxZoom: 3,
                    onClose: function() {
                        Refresh.melt()
                    }
                };
                var j = G.admin_ext,
                k = 2 === (2 & j) || 4 === (4 & j);
                i && !h && k && (e = $.extend(e, {
                    onLongTap: function(a) {
                        ActionSheet.show({
                            items: ["添加至部落相册"],
                            onItemClick: function() {
                                var b = g[a].mbimg;
                                /\/1000$/.test(b) && (b = b.replace(/\/1000$/, "/")),
                                DB.cgiHttp({
                                    url: "/cgi-bin/bar/photo/add",
                                    type: "POST",
                                    param: {
                                        bid: C,
                                        flag: 0,
                                        pid: D,
                                        url: b
                                    },
                                    succ: function(a) {
                                        Tip.show(a.result && 999 === a.result.errCode ? "图片已在相册中": "已加入部落相册")
                                    },
                                    err: function() {
                                        Tip.show("操作失败", {
                                            type: "warning"
                                        })
                                    }
                                }),
                                E("one_album")
                            }
                        })
                    }
                })),
                ImageView.init(g, c, e),
                Refresh.freeze(),
                E("Clk_big_pic")
            }
        })
    }
    function c() {
        setTimeout(function() {
            window.ImageView && ImageView.onLongTap && (J = !0);
            var a = G.admin_ext;
            2 !== (2 & a) && 4 !== (4 & a) || J || (J = !0, loadjs.loadModule("image_view",
            function() {}))
        },
        500)
    }
    function d() {
        if (mqq && mqq.ui && !a.isInYyb) {
            var b = {
                isDetail: !0,
                iconID: a.isABTest ? "4": "3",
                type: "more"
            };
            ActionButton.build(b,
            function() {
                f(),
                e(),
                E("Clk_right")
            }),
            ActionButton.setCallback(a.Post.Normal.triggerUploading)
        }
    }
    function e() {
        var b, c = [],
        d = [],
        e = G.uin === a.myuin,
        f = G.post.status,
        p = G.admin_ext;
        c.push({
            text: "查看部落",
            img: K.tribe,
            onTap: g
        }),
        d.push(g),
        "detail" === a.getTplName(F) && (c.push({
            text: 1 === a.commentType ? "只看楼主": "查看全部",
            img: K.poster,
            onTap: h
        }), d.push(h)),
        c.push({
            text: a.commentOrder ? "顺序查看": "倒序查看",
            img: a.commentOrder ? K.asc: K.desc,
            onTap: i
        }),
        d.push(i),
        a.isOpenAct && 2 !== f && e && (0 === f && (c.push({
            text: "编辑活动",
            img: K.join,
            onTap: k
        }), d.push(k)), c.push({
            text: "取消活动",
            img: K.unjoin,
            onTap: l
        }), d.push(l)),
        !e && 2 !== f && $(".bottom-bar").hasClass("has-joined") && (c.push({
            text: "取消报名",
            img: K.unjoin,
            onTap: j
        }), d.push(j)),
        e || (c.push({
            text: "投诉举报",
            img: K.report,
            onTap: m
        }), d.push(m)),
        (1 === (1 & p) || 2 === (2 & p)) && (0 === a.isBest ? (c.push({
            text: "加精话题",
            img: K.best,
            onTap: n
        }), d.push(n)) : (c.push({
            text: "取消加精",
            img: K.unbest,
            onTap: n
        }), d.push(n))),
        (2 === (2 & p) || 4 === (4 & p)) && G.post.pic_list && G.post.pic_list[0] && (c.push({
            text: "添加至相册",
            img: K.addphoto,
            onTap: o
        }), d.push(o)),
        200 === F && $.os.ios && (b = document.querySelector("video"), b && (b.style.display = "none")),
        a.isABTest ? (RichShare.build(c), v()) : ActionSheet.show({
            useH5: !0,
            items: c,
            onItemClick: function(a) {
                d[a](),
                b && (b.style.display = "block")
            },
            onCancel: function() {
                b && (b.style.display = "block")
            }
        })
    }
    function f() {
        $(document).off("touchmove",
        function(a) {
            a.preventDefault()
        }),
        window.Publish && Publish.destroy(),
        $("#join_activity_win").hide(),
        $("#quit_activity_win").hide(),
        $("video").show(),
        window.location.hash.indexOf("imageview") > -1 && window.history.go( - 1),
        window.location.hash.indexOf("peopleliked") > -1 && window.history.go( - 1),
        window.location.hash.indexOf("peoplejoined") > -1 && window.history.go( - 1),
        window.location.hash.indexOf("poi") > -1 && window.history.go( - 1),
        a.isABTest || RichShare.hide()
    }
    function g() {
        E("Clk_more_tribe"),
        Util.openUrl(a.base + "barindex.html#bid=" + C + "&scene=detail", !0)
    }
    function h() {
        var b = "";
        1 === a.commentType ? (a.commentType = 2, b = "查看全部", E("Clk_onlyhost")) : (a.commentType = 1, b = "只看楼主"),
        a.isABTest && $(this).find("p").text(b),
        a.Comment.refresh()
    }
    function i() {
        var b, c, d = $("#btnShowInturn");
        a.commentOrder ? (a.commentOrder = 0, b = "倒序查看", c = K.desc, E("Clk_inturn")) : (a.commentOrder = 1, b = "顺序查看", c = K.asc, E("Clk_reverse")),
        d.text(b),
        a.isABTest && ($(this).find("img").attr("src", c), $(this).find("p").text(b)),
        a.Comment.refresh()
    }
    function j() {
        ActionSheet.hide(!0),
        a.Join.quitAct(),
        H("Clk_quit")
    }
    function k() {
        H("Clk_edit_local"),
        +new Date + 1728e5 > G.post.start ? (Alert.show("", "同城活动在开始前2天内无法编辑！"), H("refuse_edit_local")) : Util.openUrl("http://qqweb.qq.com/m/qunactivity/form.html?type=modify&atvid=" + G.post.openact_id + "&_wv=7&_bid=244&open=1", !0)
    }
    function l() {
        Alert.show("取消活动", "活动取消后将无法重新开启，确定要取消活动吗？", {
            confirm: "确定",
            cancel: "取消",
            callback: function() {
                DB.cgiHttp({
                    url: "http://qqweb.qq.com/cgi-bin/qqactivity/close_activity",
                    type: "POST",
                    param: {
                        id: G.post.openact_id,
                        type: 1
                    },
                    succ: function() {
                        Tip.show("取消活动成功"),
                        H("Clk_un_suc4open"),
                        H(435889),
                        a.Join.syncActivity("cancel"),
                        setTimeout(function() {
                            window.mqq && mqq.ui.popBack()
                        },
                        1500)
                    },
                    err: function() {
                        Tip.show("取消活动失败", {
                            type: "warning"
                        }),
                        H(435937)
                    }
                })
            }
        }),
        H("Clk_un")
    }
    function m() {
        var b = G.uin;
        return isNaN(Number(b)) || Number(b) <= 0 ? void Alert.show("", "楼主帐号异常，无法举报", {
            confirm: "我知道了"
        }) : (E("Clk_report"), void Util.openUrl("http://xiaoqu.qq.com/mobile/report.html#bid=" + C + "&pid=" + D + "&eviluin=" + b + "&impeachuin=" + a.myuin, !0))
    }
    function n() {
        var b = {};
        b = 0 === a.isBest ? {
            title: "确认加精"
        }: {
            title: "确认取消",
            bestFlag: 0
        },
        ActionSheet.show({
            items: [b.title],
            onItemClick: function() {
                var c = {
                    bid: C,
                    pid: D
                };
                0 === b.bestFlag && (c.isbest = 0),
                DB.cgiHttp({
                    url: "/cgi-bin/bar/post/best",
                    type: "POST",
                    param: c,
                    succ: function() {
                        if (0 === b.bestFlag) $(".best").remove(),
                        a.isBest = 0,
                        Tip.show("取消加精成功", {
                            type: "ok"
                        });
                        else {
                            var c = document.createElement("label");
                            c.className = "best",
                            c.innerHTML = "精",
                            $(".post-title").prepend(c),
                            a.isBest = 1,
                            Tip.show("加精成功", {
                                type: "ok"
                            })
                        }
                    },
                    err: function() {
                        Tip.show("操作失败", {
                            type: "warning"
                        })
                    }
                })
            }
        })
    }
    function o() {
        ActionSheet.show({
            items: ["添加至部落相册"],
            onItemClick: function() {
                var a = {
                    bid: C,
                    flag: 1,
                    pid: D
                };
                DB.cgiHttp({
                    url: "/cgi-bin/bar/photo/add",
                    type: "POST",
                    param: a,
                    succ: function(a) {
                        Tip.show(a.result && 999 === a.result.errCode ? "图片已在相册中": "已加入部落相册")
                    },
                    err: function() {
                        Tip.show("操作失败", {
                            type: "warning"
                        })
                    }
                }),
                E("all_album")
            }
        })
    }
    function p() {
        var a, b = "",
        c = "w:65&h:65&notFeed:true",
        d = !1;
        if (G.post.pic_list && G.post.pic_list.length > 0) {
            var e = G.post.pic_list[0];
            if (e.w && e.h) {
                var f = imgHandle.formatThumb([e], !0, 75, 75, !0)[0];
                a = e.url,
                b = "width:" + f.width + "px; height:" + f.height + "px;margin-left:" + f.marginLeft + "px; margin-top:" + f.marginTop + "px;"
            } else a = e.url || e,
            d = !0
        } else G.post.image1 && (a = G.post.image1, d = !0);
        return a ? {
            url: imgHandle.getThumbUrl(a, 1e3),
            style: b,
            nosize: d ? c: ""
        }: null
    }
    function q() {
        E("Clk_repost", {
            module: "post_detail"
        }),
        G.thumbImg = p();
        var b = "";
        201 === F && (b = '<div class="c-img-mask"><i class="c-type-icon c-video"></i></div>'),
        (G.post.qqmusic_list || G.post.audio_list) && (b = '<div class="c-img-mask"><i class="c-type-icon c-music"></i></div>');
        var c = '<div class="th-cover" soda-if="thumbImg" ><img lazy-src="{{thumbImg.url}}" nosize="{{thumbImg.nosize}}" style="{{thumbImg.style}}">' + b + '</div><div class="th-text" ><h4>{{title}}</h4><p soda-if="post.content" soda-bind-html="post.content|plain2rich"></p></div>';
        Tmpl.addTmpl("forward_template", c),
        Alert.textarea("转发到动态", Tmpl("forward_template", G).toString(), {
            placeholder: "说说转发理由…",
            confirm: "取消",
            cancel: "发送",
            preventAutoHide: !0,
            onTap: function(b, c, d) {
                var e = $(d).find(".edit").val();
                "right" === b ? ($.ajax({
                    url: "http://xiaoqu.qq.com/cgi-bin/bar/impt/forward",
                    type: "POST",
                    data: {
                        pid: D,
                        bid: C,
                        content: e
                    },
                    success: function(b) {
                        if (0 === b.retcode) {
                            Tip.show("转发成功!");
                            var c = $("#to_forward"),
                            d = ~~c.text();
                            c.html(d + 1),
                            a.Comment.afterReply(b.result)
                        } else Tip.show("转发失败: 错误码" + b.retcode)
                    },
                    error: function() {
                        Tip.show("转发失败")
                    }
                }), E("repost_sure")) : E("repost_cancel")
            },
            renderSuccess: function() {
                var a = this;
                imgHandle.lazy($(".th-cover")[0]),
                $(this).find(".edit").on("focus",
                function() {
                    $(a).find(".a-forwards").css({
                        top: 5
                    })
                }),
                $(this).find(".edit").on("blur",
                function() {
                    $(a).find(".a-forwards").css({
                        top: 100
                    }),
                    $(document.body).hide(),
                    $(document.body).show()
                })
            }
        })
    }
    function r() {
        $("#to_like").tap(s),
        $("#to_join").tap(t),
        $("#to_reply").click(u),
        $("#to_forward").tap(q),
        $("#to_share").tap(v),
        $("#js_detail_main").on("tap", 'a[rel="showProfile"]',
        function() {
            w($(this))
        }).on("tap", 'a[rel="openUrl"]',
        function() {
            x($(this))
        })
    }
    function s() {
        if (!a.showLockTip()) {
            var b = $("#to_like");
            return a.isUploading ? void Tip.show("视频上传及转码中，暂不能点赞", {
                type: "warning"
            }) : void(b.hasClass("disabled") || b.hasClass("liked") || (b.addClass("liked animating"), b.html(~~b.html() + 1), setTimeout(function() {
                b.removeClass("animating")
            },
            1e3), DB.cgiHttp({
                url: "/cgi-bin/bar/post/like",
                type: "POST",
                ssoCmd: "like",
                param: {
                    bid: +C,
                    pid: D,
                    like: 1
                },
                succ: function() {
                    H("Clk_like")
                },
                err: function() {
                    var a = "顶失败了，麻烦再试一次吧",
                    c = ~~b.html() - 1;
                    b.html(c || 0),
                    b.removeClass("liked animating"),
                    Alert.show("", a, {
                        confirm: "好"
                    })
                }
            }), E("Clk_like", {
                ver3: F
            })))
        }
    }
    function t() {
        if (!a.showLockTip()) {
            if (a.isUploading) return void Tip.show("视频上传及转码中，暂不能报名", {
                type: "warning"
            });
            if (a.purchaseLink) return void Util.openUrl(a.purchaseLink, !0);
            a.Join.joinAct(),
            E(200 === F ? "Clk_video": "Clk_activity"),
            H("Clk_join4open")
        }
    }
    function u(b) {
        if (!a.showLockTip()) {
            if (a.isUploading) return void Tip.show("视频上传及转码中，暂不能评论", {
                type: "warning"
            });
            if (!a.isRefDlgOpen) {
                b.preventDefault();
                var c = $("#top_comment_wrapper ul").last().find("li").first();
                c.length && (a.currentCommentFloor = c.data("lz"), a.currentCommentID = c.attr("cid")),
                a.Publish.reply(),
                E("Clk_reply"),
                H("Clk_reply")
            }
        }
    }
    function v() {
        return a.isUploading ? void Tip.show("视频上传及转码中，暂不能分享", {
            type: "warning"
        }) : void a.Share.callHandler()
    }
    function w(a) {
        var b = a.attr("code"),
        c = ~~a.attr("type");
        c = Number(c),
        b && c && (1 === c ? (mqq.ui.showProfile({
            uin: b,
            uinType: 1
        }), E("Clk_grpuin", {
            obj1: b
        })) : 2 === c ? (mqq.ui.showProfile({
            uin: b
        }), E("Clk_uin")) : 3 === c && ActionSheet.show({
            items: ["加入群组", "加为好友"],
            onItemClick: function(a) {
                0 === a ? (mqq.ui.showProfile({
                    uin: b,
                    uinType: 1
                }), E("Clk_joingrp", {
                    obj1: b
                })) : 1 === a && (mqq.ui.showProfile({
                    uin: b
                }), E("Clk_friend"))
            }
        })),
        "atvCreater" === a.attr("id") && H("Clk_create_uin")
    }
    function x(a) {
        if (!a.hasClass("disabled")) {
            if (a.hasClass("link")) {
                if (a.closest(".second-comment").length) return;
                y(a)
            }
            Util.openUrl(a.attr("url"), !0, 2)
        }
    }
    function y(a) {
        if (a.hasClass("link-keyword")) {
            var b = a.data("bid"),
            c = a.text();
            b && E("Clk_keyword", {
                ver3: b,
                ver4: c
            })
        } else {
            var d = "";
            d = a.hasClass("add-group") ? 1 : a.hasClass("tribe") ? 2 : a.hasClass("post") ? 4 : 5,
            E("Clk_link", {
                ver3: d,
                ver4: 1
            })
        }
    }
    function z() {
        mqq.addEventListener("qbrowserTitleBarClick",
        function() {
            var a = $("#js_detail_main"),
            b = a.scrollTop(),
            c = $("#js_detail_scroll_top");
            Util.scrollElTop(b, a, c)
        })
    }
    function A() {
        Refresh && Refresh.init({
            dom: a.isIOS ? $("#js_detail_main")[0] : document.body,
            reload: function() {
                E("Clk_refresh"),
                Q.monitor(475347);
                var b = 1;
                return a.Comment.refresh(0,
                function() {
                    b && (Refresh.hide(), pollRefreshUi.reset(), b = 0)
                }),
                E("visit", {
                    obj1: D,
                    ver3: a.postType,
                    ver4: a.gid
                }),
                window.setTimeout(function() {
                    Refresh.hide()
                },
                1e4),
                !0
            },
            usingPollRefresh: 1
        })
    }
    function B() {
        G = a.postData,
        F = a.postType,
        b(),
        z(),
        r(),
        d(),
        MediaPlayer.attachPlayer("detail_body"),
        MediaPlayer.checkMusicState("detail_body"),
        A()
    }
    var C = a.bid,
    D = a.pid,
    E = a.report,
    F = 0,
    G = {},
    H = a.openActReport,
    I = "http://pub.idqqimg.com/qqun/xiaoqu/mobile",
    J = !1,
    K = {
        tribe: I + "/img/share/tribe.png",
        poster: I + "/img/share/poster.png",
        asc: I + "/img/share/asc.png",
        desc: I + "/img/share/desc.png",
        report: I + "/img/share/report.png",
        best: I + "/img/share/best.png",
        unbest: I + "/img/share/unbest.png",
        join: I + "/img/share/join.png",
        unjoin: I + "/img/share/unjoin.png",
        fav: I + "/img/share/fav.png",
        addphoto: I + "/img/share/addphoto.png",
        copy: I + "/img/share/copy.png"
    };
    return {
        init: B,
        show: e
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Join = b(c)
} (this,
function(a) {
    function b(a) {
        a.preventDefault()
    }
    function c() {
        $(document).on("touchmove", b)
    }
    function d() {
        $(document).off("touchmove", b)
    }
    function e(a) {
        var b = new Date(a);
        return b.getFullYear() + "-" + (b.getMonth() + 1) + "-" + b.getDate() + "&nbsp;" + b.getHours() + ":" + b.getMinutes() + ":" + b.getSeconds()
    }
    function f(a) {
        if (n.isOpenAct && window.mqq && mqq.dispatchEvent) {
            var b = {
                id: n.post.openact_id,
                bid: p,
                pid: q,
                name: n.title,
                loc_name: n.post.addr,
                cover: n.post.pic_list[0].url,
                is_join: $(".bottom-bar").hasClass("has-joined") ? 1 : 0,
                enroll: parseInt($("#peopleNum").html()),
                start_time: e(n.post.start),
                end_time: e(n.post.end),
                flag: 1 + ("cancel" === a ? 32 : 0) + 384
            };
            mqq.dispatchEvent("avt_refresh_page", {
                type: "item_" + ("quit" === a ? "cancel": "cancel" === a ? "close": a),
                data: b
            },
            {
                domains: ["*.qq.com"]
            })
        }
    }
    function g() {
        if (n) {
            var b, e = $("#join_activity_win"),
            f = e.children(),
            g = $("#join_activity_win_post");
            t && $.os.ios && (b = document.querySelector("video"), b && (b.style.display = "none")),
            o || (n && 0 === n.role && e.addClass("join-group"), e.on("tap",
            function(c) {
                c.preventDefault(),
                c.stopPropagation();
                var g = c.target;
                "joinActivityReply" === g.id ? (e.hide(), b && (b.style.display = "block"), d(), a.Publish.reply(), r(t ? "Clk_discussvideo_suc": "Clk_discuss_suc")) : "joinActivityFinish" === g.id ? (e.hide(), b && (b.style.display = "block"), d()) : "joinActivityGroup" === g.id ? (DB.cgiHttp({
                    url: "http://qqweb.qq.com/cgi-bin/qqactivity/apply_join_group",
                    type: "POST",
                    param: {
                        id: n.post.openact_id,
                        type: 1,
                        client: $.os.ios ? 2 : 3,
                        client_sub: 27
                    },
                    succ: function() {
                        Tip.show("已发出加群申请，正在等待管理员确认")
                    },
                    err: function(a) {
                        14 === a.ec ? Tip.show("你已经是群成员了") : Tip.show("发送申请失败", {
                            type: "warning"
                        })
                    }
                }), e.hide(), b && (b.style.display = "block"), d(), u("Clk_openac_joingrp")) : "shareActivity" === g.id ? (a.Share.callHandler(t ? "": "我参加了“" + $.str.decodeHtml(n.title) + "”，感兴趣就一起来吧！"), r(t ? "Clk_sharevideo_suc": "Clk_share_suc", {
                    obj1: q
                })) : f[0].contains(g) || (e.hide(), b && (b.style.display = "block"), d())
            }), o = !0),
            $("#join_activity_win_name").html(plain2rich(n.title) || ""),
            $("#join_activity_win_time").text(n.post.time || ""),
            t && n.post.image1 ? g.children().attr("src", n.post.image1) : !t && n.post.pic_list && n.post.pic_list[0] && n.post.pic_list[0].url ? g.children().attr("src", n.post.pic_list[0].url) : (g.hide(), d()),
            e.show(),
            c()
        }
    }
    function h() {
        DB.cgiHttp({
            url: "/cgi-bin/bar/post/join",
            type: "POST",
            param: {
                bid: p,
                pid: q
            },
            succ: function() {
                $(".bottom-bar").addClass("has-joined"),
                g(),
                n.is_joined = 1;
                var a = $("#peopleNum"),
                b = $("<img />");
                b.addClass("u" + s).addClass("user-avatar").attr("data-uin", s).attr("src", "http://q.qlogo.cn/g?b=qq&nk=0" + s + "&s=100").prependTo("#people_header_list"),
                a.html(parseInt(a.html()) + 1),
                $("#js_act_people").show(),
                u(435887),
                f("join")
            },
            err: function() {
                u(435935)
            }
        })
    }
    function i() {
        Alert.show("取消报名", "取消报名后将不再收到活动开始通知，确定要取消报名吗？", {
            confirm: "确定",
            cancel: "取消",
            callback: function() {
                DB.cgiHttp({
                    url: "/cgi-bin/bar/post/quit",
                    type: "POST",
                    param: {
                        bid: p,
                        pid: q
                    },
                    succ: function() {
                        $(".bottom-bar").removeClass("has-joined");
                        var a = "",
                        b = $("#peopleNum"),
                        c = $("#people_header_list .u" + s),
                        d = parseInt(b.html()) - 1;
                        a = 10055 === p || 10064 === p || 10210 === p ? "预约": "报名",
                        Tip.show("取消" + a + "成功！", {
                            type: "warning"
                        }),
                        n.is_joined = 0,
                        c.length && c.remove(),
                        d = 0 > d ? 0 : d,
                        b.html(d),
                        !d && $("#js_act_people").hide(),
                        u("Clk_quit_suc4open"),
                        u(435888),
                        f("quit")
                    },
                    err: function() {
                        u(435936)
                    }
                }),
                r(t ? "Clk_unvideo": "Clk_unactivity")
            }
        })
    }
    function j() {
        v.show()
    }
    function k() {
        v.hide()
    }
    function l(b) {
        n = b,
        t = 200 === a.postType ? 1 : 0
    }
    function m(a) {
        if (a.joined_ul) {
            $("#peopleNum").html(a.joined_ul.uinnum || 0);
            var b, c, d, e = [],
            f = a.joined_ul.uins.concat(),
            g = f.length,
            h = Math.min(10, g),
            i = g > 100;
            for (1 === a.is_joined && (e.push('<img class="u' + s + ' user-avatar" data-ban="0" data-uin="' + s + '"  src="http://q.qlogo.cn/g?b=qq&nk=' + s + "&s=100&t=" + Date.now() + '" />'), h--), c = 0; h > c; c++) d = i ? Math.floor(Math.random() * g--) : 0,
            b = f.splice(d, 1)[0],
            b.uin !== s ? (b.ban = 0, a.joined_ul.isAtvCGI && (b.ban = b.status, b.url = 1 === b.ban ? "http://q.qlogo.cn/g?b=qq&nk=1&s=100": "http://q.qlogo.cn/g?b=qq&nk=" + b.uin + "&s=100&t=" + Date.now()), e.push('<img class="u' + b.uin + ' user-avatar" data-ban="' + b.ban + '" data-uin="' + b.uin + '"  src="' + b.url + '" />')) : c--;
            $("#people_header_list").html(e.join("")),
            e.length && $("#js_act_people").show()
        }
    }
    var n, o, p = a.bid,
    q = a.pid,
    r = a.report,
    s = a.myuin,
    t = 0,
    u = a.openActReport,
    v = {
        next: 0,
        start: 0,
        numPerPage: 64,
        init: function() {
            function a(a, b) {
                h.cssRules ? h.insertRule(a + "{" + b + "}", h.cssRules.length) : h.addRule(a, b)
            }
            var b, c, d = this;
            this.$win = $('<div class="people-win people-join-win"></div>'),
            this.$title = $('<h3 class="people-title">参加活动的小伙伴</h3>'),
            this.$list = $('<ul class="people-list"></ul>'),
            this.$loading = $('<div class="loading">载入中，请稍候...</div>'),
            this.$win.append(this.$title).append(this.$list).append(this.$loading).appendTo(document.body),
            $.os.ios ? (bouncefix.add("people-join-win"), b = this.$win) : b = $(document),
            this.scrollDom = b;
            var e = $(document).width(),
            f = 768 > e ? 4 : 8,
            g = Math.floor((e - 54 - 56 * f) / (f - 1)),
            h = document.styleSheets[document.styleSheets.length - 1];
            a(".people-win .face", "margin-right:" + g + "px;"),
            a(".people-win .face:nth-child(" + f + "n)", "margin-right:27px;"),
            $(window).on("hashchange",
            function() { - 1 === location.hash.indexOf("peoplejoined") && d.hide()
            }),
            this.scrollDom.on("scroll",
            function(a) {
                c && window.clearTimeout(c),
                c = window.setTimeout(function() {
                    d.onScroll(a)
                },
                100)
            }),
            $(d.$win).on("tap",
            function(a) {
                var b = $(a.target).closest("li");
                if (0 !== b.length) {
                    var c = b.data("uin"),
                    d = 1 === b.data("ban");
                    if (n.isOpenAct) {
                        if (d) return;
                        mqq.ui.showProfile({
                            uin: c
                        }),
                        u("Clk_uindata")
                    } else Util.openUrl("http://xiaoqu.qq.com/mobile/personal.html#uin=" + c, 1)
                }
            })
        },
        reset: function() {
            this.$list.empty(),
            this.hasContent = !1,
            this.isLoading = !1,
            this.isEnd = !1,
            this.start = 0,
            this.next = 0
        },
        onScroll: function() {
            if (this.isend || this.isLoading) return ! 1;
            var a = document.documentElement.clientHeight,
            b = this.scrollDom[0] === document ? document.body: this.$win[0],
            c = b.scrollTop,
            d = b.scrollHeight;
            c + 2 * a + 20 >= d && this.getList()
        },
        render: function(a) {
            Tmpl(window.TmplInline_detail.join_list, {
                list: a,
                myuin: s
            }).appendTo(this.$list)
        },
        getList: function(a) {
            function b(a, b, c) {
                a.render(b.uins),
                a.start += a.numPerPage,
                a.isEnd = b.isend || 0,
                c && c(b),
                a.isLoading = !1,
                a.isEnd && a.$loading.html("")
            }
            var c = this,
            d = !(!n.isOpenAct || !n.is_joined || 0 !== c.start);
            if (!c.isLoading && !c.isEnd) {
                c.isLoading = !0,
                c.$loading.html("加载中，请稍候...");
                var e, f;
                n.isOpenAct ? (c.numPerPage = 48, e = "http://qqweb.qq.com/cgi-bin/qqactivity/get_activity_member_list", f = {
                    type: 1,
                    id: n.post.openact_id,
                    from: c.next,
                    number: d ? c.numPerPage - 1 : c.numPerPage,
                    flag: 0
                }) : (e = "/cgi-bin/bar/openact/users/list2", f = {
                    bid: p,
                    pid: q,
                    start: c.start,
                    num: d ? c.numPerPage - 1 : c.numPerPage
                });
                var g = {
                    url: e,
                    param: f,
                    succ: function(e) {
                        var f = {};
                        n.isOpenAct ? (f.uins = {},
                        c.next = e.next, e.list ? (f.uinnum = e.count || e.list.length, f.uins = e.list, f.uins.isAtvCGI = !0) : 0 !== e.next || e.list || (f.isend = 1)) : f = e.result || {},
                        d && f.uins ? $.ajax({
                            url: "http://cgi.connect.qq.com/qqconnectopen/openapi/get_nick",
                            dataType: "jsonp",
                            success: function(d) {
                                f.uins.unshift({
                                    uin: s,
                                    nick_name: d.result.nick || "",
                                    pic: "http://q.qlogo.cn/g?b=qq&nk=" + s + "&s=100&t=" + Date.now()
                                }),
                                b(c, f, a)
                            },
                            error: function() {
                                b(c, f, a)
                            }
                        }) : b(c, f, a)
                    },
                    err: function(b) {
                        a && a({
                            code: b.retcode,
                            msg: "服务器繁忙，请稍后再试"
                        }),
                        c.isLoading = !1,
                        c.$loading.html("网络异常，请稍后再试"),
                        0 === c.start && (c.hasContent = !1)
                    }
                };
                DB.cgiHttp(g)
            }
        },
        show: function() {
            this.hasInited || (this.init(), this.hasInited = !0),
            this.hasContent || (this.getList(), this.hasContent = !0);
            var a = this.$win;
            a.css("opacity", 0).css("background", "#F8F8F8").show(),
            setTimeout(function() {
                a.css("opacity", 1)
            },
            20),
            $.os.android && a.css("position", "fixed");
            var b = function() {
                $("#js_detail_main").hide(),
                $(".bottom-bar").hide(),
                $.os.ios ? a.css("background", "") : (document.body.scrollTop = 0, a.css("position", "static")),
                a.off("webkitTransitionEnd", b)
            };
            a.on("webkitTransitionEnd", b),
            location.hash = location.hash ? location.hash.replace(/peoplejoined/g, "") + "&peoplejoined": "#peoplejoined",
            Refresh && Refresh.pauseTouchMove(),
            u(435892)
        },
        hide: function() {
            function a() {
                b.hide(),
                b.off("webkitTransitionEnd", a)
            }
            var b = this.$win;
            $.os.ios ? (b.css("background", "#F8F8F8"), b.on("webkitTransitionEnd", a)) : b.hide(),
            $("#js_detail_main").show(),
            $(".bottom-bar").show(),
            b.css("opacity", 0),
            Refresh && Refresh.restoreTouchMove()
        }
    };
    return {
        init: l,
        joinAct: h,
        quitAct: i,
        showList: j,
        hideList: k,
        showListInPost: m
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Publish = b(c)
} (this,
function(a) {
    function b(b, c) {
        Publish.init({
            isReply: !0,
            bid: e,
            flag: h,
            pid: f,
            ref_cid: b,
            ctxNode: $("#js_detail_main"),
            pubulishType: "reply",
            postType: g,
            onhidden: function() {},
            succ: function(b) {
                a.Comment.afterReply(b);
                var d = $("#to_reply"),
                e = Number(d.html()) || 0;
                d.html(e + 1),
                i("reply_suc"),
                "commentReply" === c && i("suc_reply_own"),
                "refReply" === c && i("suc_reply_layer"),
                a.openActReport("pub_suc", {
                    module: "reply"
                })
            },
            cancel: function() {
                window.history.go( - 1)
            },
            config: {
                isReply: !0,
                from: "detail",
                extparam: {
                    ref_cid: b
                }
            }
        })
    }
    function c(c, d) {
        a.showLockTip() || (localStorage.getItem("pho_alert" + e) || 10364 !== e && 10679 !== e ? b(c, d) : Util.showStatement(function() {
            b(c, d)
        },
        e))
    }
    function d(b, c, d) {
        if (!a.showLockTip()) {
            var j = {
                isReply: !0,
                page: "detail",
                pubulishType: "comment_reply",
                postType: g,
                bid: e,
                flag: h,
                pid: f,
                cid: b,
                nick_name: c,
                ctxNode: $("#js_detail_main"),
                succ: function() {
                    i(d ? "comment_one_suc": "reply_one_suc"),
                    i("Clk_comment_suc"),
                    Util.openUrl(a.base + "reply_detail.html#bid=" + e + "&pid=" + f + "&cid=" + b + "&type=" + g + "&source=detail&ver4=2", !0, 0)
                },
                config: {
                    from: "detail"
                }
            };
            d && (j.rid = d, j.action = "floor"),
            localStorage.getItem("pho_alert" + e) || 10364 !== e && 10679 !== e ? Publish.init(j) : Util.showStatement(function() {
                Publish.init(j)
            },
            e)
        }
    }
    var e = a.bid,
    f = a.pid,
    g = a.postType,
    h = a.flag,
    i = a.report;
    return {
        reply: c,
        secondReply: d
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Share = b(c)
} (this,
function(a) {
    function b(a) {
        var b, c = $.cookie("vkey"),
        d = ["share_qq", "share_qzone", "share_wechat", "share_circle", "share_weibo", "share_link"];
        c && (j.sid = c),
        p("Clk_share"),
        b = j,
        a && (b = $.extend({},
        b, {
            content: a
        })),
        b.succHandler = function(a) {
            p(["qq_suc", "qzone_suc", "wechat_suc", "circle_suc"][a])
        },
        Util.shareMessage(b,
        function(a, b) {
            6 === b ? i() : 6 > b && (k.refresh(), p(d[b], {
                obj1: n
            }))
        })
    }
    function c(b, c) {
        var d = "http://xiaoqu.qq.com/mobile/detail.html?_bid=128&_wv=1027&bid=" + m + "&pid=" + n,
        e = $.str.decodeHtml(rich2plain(b.title)).replace(/<(.|\n)+?>/gi, ""),
        f = $.str.decodeHtml(rich2plain(b.post.content, b.post.urlInfo)).replace(/<(.|\n)+?>/gi, "");
        900 === b.type && (e += "——兴趣部落"),
        j = {
            shareUrl: d,
            pageUrl: d,
            imageUrl: "",
            title: e,
            content: f || e
        };
        var g = "";
        200 !== o && 201 !== o || !b.post.image1 ? b.post.pic_list && b.post.pic_list[0] && (g = b.post.pic_list[0].url ? b.post.pic_list[0].url: b.post.pic_list[0], j.imageInfo = {
            bid: m,
            pic: b.post.pic_list[0]
        }) : g = b.post.image1,
        g = g || b.bar_pic,
        j.imageUrl = a.getThumbUrl(g, 200),
        c && c()
    }
    function d() {
        var a = {
            mid: "callshare",
            img_url: j.imageUrl,
            link: j.shareUrl,
            desc: j.content,
            title: j.title
        };
        q.callHandler("callshare", a,
        function() {
            console.log("分享成功")
        })
    }
    function e() {
        function a() {
            var a = {
                link: "http://m.qzone.com/l?g=440&from=wechat&_bid=128&_wv=1027&bid=" + m + "&pid=" + n,
                title: j.title,
                desc: j.content
            };
            a.img_url = j.imageUrl,
            j.imageInfo && j.imageInfo.url && (a.img_url = j.imageInfo.url, a.img_width = "120", a.img_height = Math.floor(120 / j.imageInfo.w * j.imageInfo.h)),
            WeixinJSBridge.on("menu:share:timeline",
            function() {
                WeixinJSBridge.invoke("shareTimeline", a,
                function(a) {
                    k.refresh(),
                    p("share_circle", {
                        obj1: n
                    }),
                    WeixinJSBridge.log(a.err_msg)
                })
            }),
            WeixinJSBridge.on("menu:share:appmessage",
            function() {
                WeixinJSBridge.invoke("sendAppMessage", a,
                function(a) {
                    k.refresh(),
                    p("share_wechat", {
                        obj1: n
                    }),
                    WeixinJSBridge.log(a.err_msg)
                })
            })
        }
        window.WeixinJSBridge ? a() : document.addEventListener("WeixinJSBridgeReady",
        function() {
            window.WeixinJSBridge && a()
        })
    }
    function f() {
        var a = window.YybJsBridge;
        a && a.setShareInfo({
            allowShare: 1,
            iconUrl: j.imageUrl,
            jumpUrl: j.shareUrl,
            title: j.title,
            summary: j.content
        })
    }
    function g(b) {
        c(b,
        function() {
            return a.isYYB ? void f() : a.isWX ? void e() : void 0
        })
    }
    function h(a) {
        return q ? void d() : void b(a)
    }
    function i() {
        p("Clk_collect", {
            obj1: n,
            os: a.isIOS ? "ios": "android"
        });
        var b = $(".js-detail-title").text(),
        c = "";
        if (100 === o) $("#actDetailImage")[0] ? c = $("#actDetailImage")[0].src: $(".tvp_poster_img")[0] && (c = $(".tvp_poster_img")[0].src);
        else if (200 === o || 201 === o) c = $(".tvp_poster_img")[0] && $(".tvp_poster_img")[0].src;
        else {
            var d = $("#detail_top_info").find(".img-box img");
            d.length > 0 && d[0].src ? c = d[0].src: $(".tvp_poster_img")[0] && (c = $(".tvp_poster_img")[0].src)
        }
        c = c || "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/recommend-blue-icon.png",
        DB.cgiHttp({
            url: "/cgi-bin/bar/extra/add_mqq_fave",
            type: "POST",
            param: {
                bid: m,
                pic: c,
                pid: n,
                sub_title: "兴趣部落",
                title: b
            },
            succ: function() {
                Tip.show("已收藏")
            },
            err: function() {
                Tip.show("操作失败，请重试！", {
                    type: "warning"
                })
            }
        })
    }
    var j, k, l = cgiModel,
    m = a.bid,
    n = a.pid,
    o = a.postType,
    p = a.report,
    q = null;
    return document.addEventListener("WebViewJavascriptBridgeReady",
    function(a) {
        a = a || window.event,
        q = a.bridge,
        q.init()
    }),
    k = new l({
        cgiName: "/cgi-bin/bar/extra/share_add",
        param: {
            bid: m,
            pid: n
        },
        processData: function(b, c) {
            if (0 !== c && 0 === b.retcode && !a.isABTest) {
                var d = $("#to_share"),
                e = ~~d.text();
                d.html(e + 1)
            }
        }
    }),
    {
        init: g,
        callHandler: h
    }
});