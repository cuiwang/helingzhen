!function(a, b) {
    a.ActionSheet = b(a.$, a.Tmpl)
}(this, function(a, b) {
    function c() {
        i = a('<div class="action-sheet"></div>'),
        h = a('<div class="action-sheet-mask"></div>'),
        a(document.body).append(i).append(h),
        h.on("tap", function() {
            f()
        }).on("webkitTransitionEnd", function() {
            h.hasClass("show") || h.hide()
        }),
        i.on("tap", ".sheet-item", e).on("webkitTransitionEnd", function() {
            i.hasClass("show") || (h.removeClass("show"),
            i.hide())
        }),
        k = !0
    }
    function d(d) {
        mqq.compare("4.7") >= 0 && !d.useH5 ? mqq.ui.showActionSheet({
            items: d.items,
            cancel: "取消"
        }, function(a, b) {
            0 === a ? d.onItemClick && d.onItemClick(b) : d.onCancel && d.onCancel(b)
        }) : (k || c(),
        j = d,
        document.body.style.overflow = "hidden",
        i.html(""),
        l.on("touchmove", g),
        b(window.TmplInline_actionsheet.frame, d).appendTo(i),
        h.show(),
        i.show(),
        setTimeout(function() {
            h.addClass("show"),
            i.addClass("show")
        }, 50)),
        d.cancle && a('.sheet-item[value="-1"] .sheet-item-text').html(d.cancle)
    }
    function e() {
        var b = a(this).attr("value");
        b = Number(b),
        -1 === b ? j.onCancel && j.onCancel() : j.onItemClick && j.onItemClick(b),
        f()
    }
    function f(a) {
        i && (a ? (h.hide().removeClass("show"),
        i.hide().removeClass("show")) : i.removeClass("show"),
        l.off("touchmove", g),
        document.body.style.overflow = "")
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
function() {
    window.honourHelper = {
        renderHonours: function(a, b) {
            var c, d, e = a.admin_ext, f = a["continue"], g = '<span class="honour vip-icon" data-url="http://buluo.qq.com/xylm/business/personal_center/center.html?from=exp_icon"></span>', h = "", i = "";
            return a.vipno && (i = "&nbsp;",
            h = g),
            8 === (8 & e) ? (c = a.title || "大明星",
            h += i + '<span class="honour vip" data-report-action="star" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=admin&from=icon">' + c + "</span>") : 2 === (2 & e) ? (d = a.owner ? "首席酋长" : "大酋长",
            h += i + '<span class="honour admin" data-report-action="big" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=admin&from=icon">' + d + "</span>") : 4 === (4 & e) ? h += i + '<span class="honour border-1px admin" data-report-action="small" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=admin&from=icon">小酋长</span>' : 256 === (256 & e) ? h += i + '<span class="honour admin xiaobian" data-report-action="xiaobian" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=admin&from=icon">小编</span>' : 2048 === (2048 & e) ? h += i + '<span class="honour rich" data-report-action="tuhao" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=rich&from=icon">土豪</span>' : 1024 === (1024 & e) ? h += i + '<span class="honour expert" data-report-action="daren" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=expert&from=icon">达人</span>' : f >= 7 && (h += i + '<span class="honour fans" data-report-action="iron" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=fans&from=icon">铁杆粉</span>'),
            h
        },
        renderPoster: function(a, b) {
            var c, d = arguments.length;
            return 1 === d ? c = !!a : 2 === d && (c = a && b ? a === b : !1),
            c ? '<span class="honour poster">楼主</span>' : ""
        },
        isAdmin: function(a) {
            return 1 === (1 & a) || 2 === (2 & a) || 4 === (4 & a) || 32 === (32 & a)
        },
        renderVip: function(a) {
            var b = '<span class="vip-icon" data-url="http://buluo.qq.com/xylm/business/personal_center/center.html?from=exp_icon"></span>';
            return 1 === a ? b : ""
        }
    }
}(),
function(a, b) {
    a.MediaPlayer = b(a.$)
}(this, function() {
    function a() {
        return a.__seed || (a.__seed = 1),
        a.__seed++
    }
    function b(b) {
        var c, d, e = a();
        t || (t = $('<div class=".hide"><audio></audio></div>').appendTo(document.body).children(),
        t.on("pause error", function() {
            var a = $(this).data("playId")
              , b = y[a];
            $(x).trigger("stop", b)
        }).on("play", function() {
            var a = $(this).data("playId")
              , b = y[a];
            $(x).trigger("play", b)
        }).on("canplay", function() {
            var a = $(this).data("playId")
              , b = y[a];
            $(x).trigger("canplay", b)
        }));
        var f = t.get(0);
        return c = t.data("playId"),
        f.pause(),
        c && (d = y[c],
        delete y[c],
        $(x).trigger("stop", d)),
        f.src = b.url,
        f.play(),
        t.data("playId", e),
        d = {
            url: b.url,
            type: b.type,
            id: e
        },
        y[e] = d,
        e
    }
    function c() {
        t && t.get(0).pause()
    }
    function d() {
        u && u.pause()
    }
    function e(a, b) {
        var c = $(a).find(".post-video");
        c.length && c.each(function() {
            var a = $(this).show()
              , c = a.data("vid")
              , d = a.data("image");
            d || (d = "http://shp.qpic.cn/qqvideo/0/" + c + "/400"),
            f(c, d, 3, b)
        })
    }
    function f(a, b, c, d) {
        switch (mqq.ui.setWebViewBehavior({
            swipeBack: 0
        }),
        A) {
        case "wait":
            z.push([a, b, c]),
            g(d);
            break;
        case "loading":
            z.push([a, b, c]);
            break;
        case "complete":
            k(a, b, c, d)
        }
    }
    function g(a) {
        A = "loading";
        var b = $.cookie;
        loadjs.load(["http://imgcache.gtimg.cn/tencentvideo_v1/tvp/js/tvp.player_v2_zepto.js"], [function() {
            b.get = $.cookie.get,
            b.set = $.cookie.set,
            b.del = $.cookie.del,
            $.cookie = b,
            A = "complete",
            z.forEach(function(b) {
                k(b[0], b[1], b[2], a)
            }),
            z = []
        }
        ])
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
    function i(a) {
        if (!(mqq.android || mqq.compare("6.3.0") < 0)) {
            var b = a.$videomod;
            b.find(".tvp_overlay_play").off("click").on("click", function() {
                j(a)
            }),
            b.find(".tvp_playpause_button").off("touchend").on("touchend", function() {
                $(this).hasClass("tvp_play") ? j(a) : a.pause()
            })
        }
    }
    function j(a) {
        mqq.invoke("media", "getIsVideoChat", {
            callback: mqq.callback(function(b) {
                1 === b ? Tip.show("通话中，请结束通话后再试", {
                    type: "warning"
                }) : a.getPlayer().play()
            })
        })
    }
    function k(a, b, c, d) {
        var e = new tvp.VideoInfo;
        1 === c ? e.setChannelId(a) : e.setVid(a),
        d || (d = {}),
        e.setTitle("兴趣部落");
        var f, g, j, k = new tvp.Player, l = 15, m = d.ratio || 320 / 240;
        f = d.width || document.body.offsetWidth + 2 - 2 * l,
        g = ~~(f / m),
        B || (B = !0,
        $(window).on("resize", function() {
            k.$video && k.$video.length && (k.$video[0].width = document.body.offsetWidth - 2 * l,
            g = ~~(f / m),
            k.$video[0].height = g,
            k.$video[0].controls = "controls")
        })),
        j = 3 === c ? "vplayer_" + a : "mod_player";
        var n = {
            width: f,
            height: g,
            video: e,
            modId: j,
            appid: "10008",
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
                }, 500),
                i(this)
            },
            onplay: function() {},
            onplaying: function() {
                $(".tvp_video").find("video").show(),
                u = this,
                MediaPlayer.stop({
                    type: "music"
                }),
                MediaPlayer.stop({
                    type: "audio"
                }),
                h("start_video")
            }
        };
        1 === c && (n.type = 1),
        k.create(n)
    }
    function l(b) {
        var c = a()
          , d = {
            id: b.id + "",
            title: b.name + "",
            desc: b.singer + "",
            audio_url: b.url,
            image_url: b.img,
            share_url: b.href
        };
        return mqq.media.startPlayMusic(d),
        y[c] = b,
        c
    }
    function m() {
        mqq.media.stopPlayMusic()
    }
    function n(a) {
        return "audio" === a.type ? (m(),
        d(),
        b(a)) : "music" === a.type ? (c(),
        m(),
        d(),
        w ? l(a) : b(a)) : void 0
    }
    function o(a) {
        a = y[a.playId] || a,
        delete y[a.playId],
        a && ("audio" === a.type ? c() : "music" === a.type ? w ? m(a) : c() : "video" === a.type && d())
    }
    function p(a) {
        $(MediaPlayer).on("stop", function(b) {
            var c = b._args;
            if (c) {
                var d = $("#" + a + ' div[data-playId="' + c.id + '"]');
                d && (d.attr("data-playId", ""),
                d.removeClass("playing"),
                r(d))
            }
        }),
        $(MediaPlayer).on("canplay", function(b) {
            var c = b._args;
            if (c) {
                var d = $("#" + a + ' div[data-playId="' + c.id + '"]');
                d && (d.attr("data-playId", c.id),
                d.removeClass("waiting").addClass("playing"),
                q(d))
            }
        }),
        mqq.addEventListener("qbrowserMusicStateChange", function(b) {
            var c = b;
            if (c) {
                var d = $("#" + a + ' div[data-id="' + c.id + '"]');
                d && (2 === Number(c.state) ? (d.attr("data-playId", c.id),
                d.addClass("playing")) : (d.attr("data-playId", ""),
                d.removeClass("playing")))
            }
        }),
        document.addEventListener("qbrowserVisibilityChange", function(a) {
            a.hidden && (c(),
            d())
        }),
        $("#" + a).on("tap", function(a) {
            var b, c, d = $(a.target);
            d.attr("rel") || (d = d.parent("[rel]"));
            var e = d.attr("rel");
            d.data("target") && (d = d.parent(d.data("target")));
            var f = d.data();
            "playAudio" === e ? (c = d.attr("data-playId"),
            c ? (MediaPlayer.stop({
                type: "audio",
                id: c
            }),
            d.attr("data-playId", ""),
            d.removeClass("playing"),
            r(d)) : (c = MediaPlayer.play({
                type: "audio",
                url: f.url
            }),
            c && (d.addClass("waiting"),
            d.attr("data-playId", c),
            "detail" === f.page && h("start_record")))) : "playMusic" === e ? (c = d.attr("data-playId"),
            c ? (MediaPlayer.stop({
                type: "music",
                id: c
            }),
            w || (d.attr("data-playId", ""),
            d.removeClass("playing")),
            "detail" === f.page && h("pause_music")) : (f.type = "music",
            c = MediaPlayer.play(f),
            b = d.data("special"),
            b && h("play_type_music"),
            d.addClass("playing"),
            d.attr("data-playId", c),
            "detail" === f.page && h("start_music"))) : "openMusic" === e && f.id && Util.openUrl(f.href, !0)
        })
    }
    function q(a) {
        var b = +a.data("duration")
          , c = a.find(".length");
        v = setInterval(function() {
            b ? c.text(--b + '"') : r(a)
        }, 1e3)
    }
    function r(a) {
        v && clearInterval(v);
        var b = +a.data("duration")
          , c = a.find(".length");
        c.text(b + '"')
    }
    function s(a) {
        C && clearTimeout(C),
        C = setTimeout(function() {
            C = null ,
            mqq.media.getPlayState(function(b) {
                2 === Number(b) && mqq.media.getCurrentSong(function(b) {
                    var c = $("#" + a + ' div[data-id="' + b.id + '"]');
                    c && c.length && (c.attr("data-playId", b.id),
                    c.addClass("playing"))
                })
            })
        }, 500)
    }
    var t, u, v, w = mqq.compare("5.2") > 0, x = {}, y = {}, z = [], A = "wait", B = !1;
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
    var C = null ;
    return x.play = n,
    x.stop = o,
    x.attachPlayer = p,
    x.checkMusicState = s,
    x.initVPlayer = f,
    x.initVPlayerMuti = e,
    x
}),
!function(a, b) {
    a.BARTYPE = b(a)
}(window, function() {
    return {
        BARCLASS: {
            qunSubscription: 87
        }
    }
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_ad = b()
}(this, function() {
    var a = {}
      , b = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("type")
          , f = c("uiType")
          , g = c("title")
          , h = c("desc")
          , i = c("imgHandle")
          , j = c("img")
          , k = c("document")
          , l = c("reply")
          , m = c("imgHeight")
          , n = c("style")
          , o = c("starHtml")
          , p = c("wording")
          , q = "";
        if (q += "",
        "barindex" === e) {
            if (q += " ",
            1 === f)
                q += ' <div class="detail-text-content haspic"> <div class="text-container"> <h3 class="text">',
                q += d(g),
                q += '<span class="list-ad-mark-top">广告</span></h3> <div class="list-content">',
                q += d(h),
                q += '</div> </div> <div class="act-img img-gallary img-ph"> <img class="ad-img" src="',
                q += d(i.getThumbUrl(j)),
                q += '" style="width:',
                q += d(j.width),
                q += "px; height:",
                q += d(j.height),
                q += "px; margin-left:",
                q += d(j.marginLeft),
                q += "px; margin-top:",
                q += d(j.marginTop),
                q += 'px;" /> </div> </div> ';
            else if (2 === f) {
                (k.documentElement.clientWidth || k.body.clientWidth) - 30;
                q += ' <div class="detail-text-content haspic"> <div class="report-content"> <h3 class="grouptitle feed-two-line"><span class="ad-mark-new">广告</span>',
                q += d(g),
                q += '</h3> <div class="groupbrief feed-two-line">',
                q += d(h),
                q += "</div> </div> ";
                var r = window.feedRenderTool.getAdFeeds(j);
                q += ' <div class="img-wrap clearfix"> ';
                for (var s = 0; s < r.length; s++)
                    q += ' <div class="img-ph feed-img ',
                    q += d(l ? "report" : ""),
                    q += '" style="',
                    q += d(r[s].styleStr),
                    q += '"> <img hidebg="true" noSize="',
                    q += d(r[s].noSize),
                    q += '" src="',
                    q += d(r[s].picList[s].url),
                    q += '" style="width:100%;height:100%;"/> </div> ';
                q += " </div> </div> "
            } else
                q += ' <div class="gdt-ad-wrapper"> <div class="detail-text-content haspic"> <div class="text-container"> <h3 class="text">',
                q += d(g),
                q += '</h3> <div class="list-content">',
                q += d(h),
                q += '</div> </div> <div class="act-img img-gallary img-ph"> <img class="ad-img" src="',
                q += d(i.getThumbUrl(j)),
                q += '" style="width:',
                q += d(j.width),
                q += "px; height:",
                q += d(j.height),
                q += "px; margin-left:",
                q += d(j.marginLeft),
                q += "px; margin-top:",
                q += d(j.marginTop),
                q += 'px;" /> </div> <span class="ad-ribbon">广告</span> </div> </div> ';
            q += " "
        } else
            ("post" === e || "recommend" === e) && (q += " ",
            j = i.formatThumb(j, !0, 80, 80),
            j = j[0],
            q += ' <div class="ad-banner-img-wrapper img-ph" style="height: ',
            q += d(m),
            q += 'px"> <img src="',
            q += d(i.getThumbUrl(j)),
            q += '" class="ad-banner-img"/> <div class="ad-tag"></div> </div> <div class="ad-banner-wording-wrapper"> ',
            n && o ? (q += ' <p class="app-stars">',
            q += d(o),
            q += '</p> <div class="app-title">今日热门推荐</div> ') : (q += ' <div class="wording">',
            q += d(p[0]),
            q += "</div> "),
            q += ' <div class="ad-btn">',
            q += d(p[1]),
            q += "</div> </div> ");
        return q += " "
    }
    ;
    return a.ad = "TmplInline_ad.ad",
    Tmpl.addTmpl(a.ad, b),
    a
}),
function(a, b) {
    a.LazyExpose = b()
}(this, function() {
    var a = function(a, b, c) {
        var d;
        return !1 !== c && (c = !0),
        function() {
            function e() {
                c || a.apply(f, g),
                d = null 
            }
            var f = this
              , g = arguments;
            d ? clearTimeout(d) : c && a.apply(f, g),
            d = setTimeout(e, b || 100)
        }
    }
      , b = 0
      , c = {}
      , d = window.innerHeight
      , e = {
        start: !0,
        defer: 300,
        handler: function() {},
        container: window
    };
    return {
        init: function(b, c) {
            console.debug("广告LazyExpose init..."),
            this.elements = b,
            this.options = c = $.extend({}, e, "object" == typeof c && c),
            this.handler = c.handler,
            this.delay = c.delay || 0,
            this.container = $.os.ios ? c.container : window,
            this.$container = $(this.container),
            this.onScroll = a($.proxy(this._onScroll, this), c.defer, !1),
            this.status = 1,
            setTimeout(function() {
                this.inited || (this.inited = !0,
                this.containerHeight = this._getContainerHeight(),
                this.$container.on("scroll", this.onScroll))
            }
            .bind(this), this.options.defer)
        },
        _getContainerHeight: function() {
            var a = this.container;
            if (a.document)
                return d;
            var b = window.getComputedStyle(a);
            return parseInt(b.height) + parseInt(b.paddingTop) + parseInt(b.paddingBottom) + parseInt(b.marginTop) + parseInt(b.marginBottom)
        },
        _onScroll: function() {
            if (console.debug(">>>>>>>>>onScroll"),
            this.status) {
                var a = this.elements;
                if (!a || !a.length)
                    return void this._destory();
                var c, d, e, f = this;
                for (d = 0,
                e = a.length; e > d; d++) {
                    c = a[d],
                    c.cacheId = c.cacheId || ++b;
                    var g = f._elementInViewport(c);
                    c && f.isWebViewHidden !== !0 && g && setTimeout(function() {
                        this._elementInViewport(c) && (this.handler(),
                        Array.prototype.splice.call(this.elements, d, 1),
                        1 === e && this._destory())
                    }
                    .bind(f), f.delay)
                }
            }
        },
        _elementInViewport: function(a) {
            if ("none" === $(a).css("display"))
                return !1;
            var b = this.container
              , d = a.cacheId
              , e = c[d] || $(a).offset()
              , f = 0;
            return b ? (f += b.document ? b.scrollY || b.pageYOffset : (b.scrollTop || window.scrollY) + (b.offsetTop || window.pageYOffset),
            c[d] = e,
            f >= e.top - e.height) : !1
        },
        _destory: function() {
            console.debug(">>>>ad scroll off"),
            this.$container.off("scroll", this.onScroll),
            c = {},
            this.status = 0,
            this.elements = null ,
            this.container = null ,
            this.$container = null 
        }
    }
}),
function(a) {
    "use strict";
    function b(a, b) {
        var c = (65535 & a) + (65535 & b)
          , d = (a >> 16) + (b >> 16) + (c >> 16);
        return d << 16 | 65535 & c
    }
    function c(a, b) {
        return a << b | a >>> 32 - b
    }
    function d(a, d, e, f, g, h) {
        return b(c(b(b(d, a), b(f, h)), g), e)
    }
    function e(a, b, c, e, f, g, h) {
        return d(b & c | ~b & e, a, b, f, g, h)
    }
    function f(a, b, c, e, f, g, h) {
        return d(b & e | c & ~e, a, b, f, g, h)
    }
    function g(a, b, c, e, f, g, h) {
        return d(b ^ c ^ e, a, b, f, g, h)
    }
    function h(a, b, c, e, f, g, h) {
        return d(c ^ (b | ~e), a, b, f, g, h)
    }
    function i(a, c) {
        a[c >> 5] |= 128 << c % 32,
        a[(c + 64 >>> 9 << 4) + 14] = c;
        var d, i, j, k, l, m = 1732584193, n = -271733879, o = -1732584194, p = 271733878;
        for (d = 0; d < a.length; d += 16)
            i = m,
            j = n,
            k = o,
            l = p,
            m = e(m, n, o, p, a[d], 7, -680876936),
            p = e(p, m, n, o, a[d + 1], 12, -389564586),
            o = e(o, p, m, n, a[d + 2], 17, 606105819),
            n = e(n, o, p, m, a[d + 3], 22, -1044525330),
            m = e(m, n, o, p, a[d + 4], 7, -176418897),
            p = e(p, m, n, o, a[d + 5], 12, 1200080426),
            o = e(o, p, m, n, a[d + 6], 17, -1473231341),
            n = e(n, o, p, m, a[d + 7], 22, -45705983),
            m = e(m, n, o, p, a[d + 8], 7, 1770035416),
            p = e(p, m, n, o, a[d + 9], 12, -1958414417),
            o = e(o, p, m, n, a[d + 10], 17, -42063),
            n = e(n, o, p, m, a[d + 11], 22, -1990404162),
            m = e(m, n, o, p, a[d + 12], 7, 1804603682),
            p = e(p, m, n, o, a[d + 13], 12, -40341101),
            o = e(o, p, m, n, a[d + 14], 17, -1502002290),
            n = e(n, o, p, m, a[d + 15], 22, 1236535329),
            m = f(m, n, o, p, a[d + 1], 5, -165796510),
            p = f(p, m, n, o, a[d + 6], 9, -1069501632),
            o = f(o, p, m, n, a[d + 11], 14, 643717713),
            n = f(n, o, p, m, a[d], 20, -373897302),
            m = f(m, n, o, p, a[d + 5], 5, -701558691),
            p = f(p, m, n, o, a[d + 10], 9, 38016083),
            o = f(o, p, m, n, a[d + 15], 14, -660478335),
            n = f(n, o, p, m, a[d + 4], 20, -405537848),
            m = f(m, n, o, p, a[d + 9], 5, 568446438),
            p = f(p, m, n, o, a[d + 14], 9, -1019803690),
            o = f(o, p, m, n, a[d + 3], 14, -187363961),
            n = f(n, o, p, m, a[d + 8], 20, 1163531501),
            m = f(m, n, o, p, a[d + 13], 5, -1444681467),
            p = f(p, m, n, o, a[d + 2], 9, -51403784),
            o = f(o, p, m, n, a[d + 7], 14, 1735328473),
            n = f(n, o, p, m, a[d + 12], 20, -1926607734),
            m = g(m, n, o, p, a[d + 5], 4, -378558),
            p = g(p, m, n, o, a[d + 8], 11, -2022574463),
            o = g(o, p, m, n, a[d + 11], 16, 1839030562),
            n = g(n, o, p, m, a[d + 14], 23, -35309556),
            m = g(m, n, o, p, a[d + 1], 4, -1530992060),
            p = g(p, m, n, o, a[d + 4], 11, 1272893353),
            o = g(o, p, m, n, a[d + 7], 16, -155497632),
            n = g(n, o, p, m, a[d + 10], 23, -1094730640),
            m = g(m, n, o, p, a[d + 13], 4, 681279174),
            p = g(p, m, n, o, a[d], 11, -358537222),
            o = g(o, p, m, n, a[d + 3], 16, -722521979),
            n = g(n, o, p, m, a[d + 6], 23, 76029189),
            m = g(m, n, o, p, a[d + 9], 4, -640364487),
            p = g(p, m, n, o, a[d + 12], 11, -421815835),
            o = g(o, p, m, n, a[d + 15], 16, 530742520),
            n = g(n, o, p, m, a[d + 2], 23, -995338651),
            m = h(m, n, o, p, a[d], 6, -198630844),
            p = h(p, m, n, o, a[d + 7], 10, 1126891415),
            o = h(o, p, m, n, a[d + 14], 15, -1416354905),
            n = h(n, o, p, m, a[d + 5], 21, -57434055),
            m = h(m, n, o, p, a[d + 12], 6, 1700485571),
            p = h(p, m, n, o, a[d + 3], 10, -1894986606),
            o = h(o, p, m, n, a[d + 10], 15, -1051523),
            n = h(n, o, p, m, a[d + 1], 21, -2054922799),
            m = h(m, n, o, p, a[d + 8], 6, 1873313359),
            p = h(p, m, n, o, a[d + 15], 10, -30611744),
            o = h(o, p, m, n, a[d + 6], 15, -1560198380),
            n = h(n, o, p, m, a[d + 13], 21, 1309151649),
            m = h(m, n, o, p, a[d + 4], 6, -145523070),
            p = h(p, m, n, o, a[d + 11], 10, -1120210379),
            o = h(o, p, m, n, a[d + 2], 15, 718787259),
            n = h(n, o, p, m, a[d + 9], 21, -343485551),
            m = b(m, i),
            n = b(n, j),
            o = b(o, k),
            p = b(p, l);
        return [m, n, o, p]
    }
    function j(a) {
        var b, c = "";
        for (b = 0; b < 32 * a.length; b += 8)
            c += String.fromCharCode(a[b >> 5] >>> b % 32 & 255);
        return c
    }
    function k(a) {
        var b, c = [];
        for (c[(a.length >> 2) - 1] = void 0,
        b = 0; b < c.length; b += 1)
            c[b] = 0;
        for (b = 0; b < 8 * a.length; b += 8)
            c[b >> 5] |= (255 & a.charCodeAt(b / 8)) << b % 32;
        return c
    }
    function l(a) {
        return j(i(k(a), 8 * a.length))
    }
    function m(a, b) {
        var c, d, e = k(a), f = [], g = [];
        for (f[15] = g[15] = void 0,
        e.length > 16 && (e = i(e, 8 * a.length)),
        c = 0; 16 > c; c += 1)
            f[c] = 909522486 ^ e[c],
            g[c] = 1549556828 ^ e[c];
        return d = i(f.concat(k(b)), 512 + 8 * b.length),
        j(i(g.concat(d), 640))
    }
    function n(a) {
        var b, c, d = "0123456789abcdef", e = "";
        for (c = 0; c < a.length; c += 1)
            b = a.charCodeAt(c),
            e += d.charAt(b >>> 4 & 15) + d.charAt(15 & b);
        return e
    }
    function o(a) {
        return unescape(encodeURIComponent(a))
    }
    function p(a) {
        return l(o(a))
    }
    function q(a) {
        return n(p(a))
    }
    function r(a, b) {
        return m(o(a), o(b))
    }
    function s(a, b) {
        return n(r(a, b))
    }
    function t(a, b, c) {
        return b ? c ? r(b, a) : s(b, a) : c ? p(a) : q(a)
    }
    "function" == typeof define && define.amd ? define(function() {
        return t
    }) : a.md5 = t
}(this),
function(a, b) {
    a.ad = b(a.TmplInline_ad, a.LazyExpose, a.Q)
}(this, function(a, b, c) {
    function d(a) {
        var b, c, d = Login.getUin(), e = {
            page_type: 0,
            _uin: Number(d),
            bid: Util.getHash("bid") || Util.queryString("bid")
        }, f = {
            adposcount: 1,
            posid: "recommend" === a ? u[a][0] : u[a],
            count: "1",
            datatype: 2,
            datafmt: "json",
            charset: "utf8"
        }, g = {
            wapos: $.os.android ? "ADR" : $.os.ios ? "IPH" : "",
            carrier: 0
        };
        try {
            b = localStorage.getItem(d + "-lat"),
            c = localStorage.getItem(d + "-lon")
        } catch (h) {}
        b && c && (g.lat = b,
        g.lng = c),
        console.debug("deviceInfo: ", C),
        console.debug(r);
        for (var i in C)
            C.hasOwnProperty(i) && (g[i] = C[i]);
        return f.ext = g,
        e._param = JSON.stringify(f),
        e
    }
    function e(a) {
        console.log(a, "暂时无广告数据，清除广告容器"),
        l(t[a].container),
        F.monitor("noAd", a)
    }
    function f(a) {
        var b, f;
        console.debug(a, "准备拉取CGI广告数据");
        try {
            DB.cgiHttp({
                url: "/cgi-bin/bar/extra/get_ads",
                param: d(a),
                succ: function(d) {
                    if (0 !== d.retcode || !d.result || void 0 !== d.result.ret && 0 !== d.result.ret || $.isEmptyObject(d.result))
                        return void e(a);
                    console.info("/cgi-bin/bar/extra/get_ads data: ", d);
                    var j, k, l, m, o = 1 != d.result.ads_from && 2 != d.result.ads_from, p = 2 === d.result.ads_from;
                    if (p) {
                        if (0 === d.result.ads.length)
                            return void e(a);
                        l = d.result.ads[0],
                        l.trl = l.url,
                        m = {
                            id: l.id,
                            type: a,
                            uiType: v,
                            wording: g(l.producttype),
                            style: h(l.producttype),
                            appGrade: l.ext && l.ext.appscore,
                            title: n(l.title),
                            desc: n(l.desc),
                            img: l.pic[0],
                            rl: l.url,
                            apurl: l.e_url
                        },
                        console.log("捕获到来自新系统的广告", m)
                    } else if (o) {
                        if (j = d.result.data,
                        !j)
                            return void e(a);
                        if (k = j[u[a]],
                        "recommend" === a && (k = j[u[a][0]],
                        w = 2,
                        k || (k = j[u[a][1]],
                        w = 1)),
                        !(k && 0 === k.ret && k.list && k.list.length > -1))
                            return void e(a);
                        l = k.list[0];
                        var q = k.cfg
                          , r = q.id;
                        m = {
                            id: r,
                            type: a,
                            uiType: "barindex" === a ? v : w,
                            wording: g(l.producttype),
                            style: h(l.producttype),
                            appGrade: l.ext && l.ext.appscore,
                            cl: l.cl,
                            title: n(l.txt),
                            desc: n(l.desc),
                            img: l.img || l.img_s || l.img2,
                            rl: l.rl,
                            apurl: l.apurl
                        }
                    } else
                        l = d.result,
                        m = {
                            id: l.id,
                            type: a,
                            uiType: v,
                            title: n(l.title),
                            desc: n(l.desc),
                            img: l.pic,
                            rl: l.link
                        };
                    if (m.appGrade) {
                        for (m.starHtml = "",
                        f = 0,
                        b = m.appGrade; b > 1; b -= 2)
                            m.starHtml += "<i></i>",
                            f++;
                        for (1 === b && (m.starHtml += '<i class="half"></i>',
                        f++),
                        b = 5 - f; b > 0; b--)
                            m.starHtml += '<i class="gray"></i>'
                    }
                    if ("post" === a || "recommend" === a) {
                        var s = D - 30;
                        m.imgHeight = Math.round(166 * s / 582)
                    }
                    m.img = [{
                        url: m.img,
                        h: 90,
                        w: 90
                    }],
                    "barindex" !== a || p || (l.pic2 && m.img.push({
                        url: l.pic2,
                        h: 90,
                        w: 90
                    }),
                    l.pic3 && m.img.push({
                        url: l.pic3,
                        h: 90,
                        w: 90
                    })),
                    "barindex" === a && p && (l.pic[1] && m.img.push({
                        url: l.pic[1],
                        h: 90,
                        w: 90
                    }),
                    l.pic[2] && m.img.push({
                        url: l.pic[2],
                        h: 90,
                        w: 90
                    })),
                    console.debug(a, "CGI拉取完广告数据"),
                    z.isSpecific && c.monitor(2021682, !1),
                    t[a].data = m,
                    t[a].hasData = !0,
                    t[a].container && i(a, l.producttype, l.trl)
                },
                err: function() {
                    console.error("广告拉取CGI报错！", j),
                    l(t[a].container)
                }
            })
        } catch (j) {
            console.error(a + "广告拉取失败:", j)
        }
        F.monitor("total", a)
    }
    function g(a) {
        return [12, 19].indexOf(a) > -1 ? x[0] : [1, 2, 25].indexOf(a) > -1 ? x[1] : 26 === a ? x[2] : x[3]
    }
    function h(a) {
        return [12, 19].indexOf(a) > -1 ? y[0] : y[1]
    }
    function i(c, d, e) {
        if (console.log("准备展示广告", c, d, e),
        !t[c].data)
            return void (t[c].hasData && (console.debug(c, "暂时无广告数据"),
            l(t[c].container)));
        var f = t[c].data
          , g = t[c].container
          , h = "barindex" === c ? 0 : 200;
        Tmpl(a.ad, f).update(g),
        g.data("href", f.rl),
        g.show(),
        console.debug(c, "已展示广告"),
        j(g, c, d, e),
        b.init($(".gdt-ad"), {
            container: t[c].page,
            handler: function() {
                F.exposure(f.apurl, c)
            },
            delay: h
        }),
        "recommend" !== c && m(c)
    }
    function j(a, b, c, d) {
        var e = !0;
        a.on("tap", function(a) {
            var d = $(a.currentTarget)
              , f = d.data("href")
              , g = Util.queryString("from") || Util.getHash("from")
              , h = "xingquhao" === g;
            if (F.click(b),
            e) {
                var i = a.srcElement || a.target;
                12 === c && ($(i).hasClass("ad-btn") || $(i).hasClass("btn")) ? k(f, h) : $.os.android && h ? Util.openUrl("http://buluo.qq.com/mobile/ad.html?gdturl=" + encodeURIComponent(f), !0) : $.os.ios && 19 === c ? $.getJSON(f + "&acttype=1&callback=?", function(a) {
                    a.data && a.data.dstlink && (mqq.invoke && mqq.compare("6.3.1") >= 0 ? mqq.invoke("ui", "showAppstoreProduct", {
                        appUrl: a.data.dstlink,
                        jump: "1"
                    }) : window.location = a.data.dstlink)
                }) : Util.openUrl(f + (f.indexOf("?") > 0 ? "" : "?") + "&_wv=4", !0),
                setTimeout(function() {
                    e = !0
                }, 1e3),
                e = !1
            }
            a.stopPropagation()
        })
    }
    function k(a, b) {
        mqq && mqq.support("mqq.device.getNetworkType") && mqq.device.getNetworkType(function(c) {
            1 === c && (a += "&acttype=42"),
            b ? Util.openUrl("http://buluo.qq.com/mobile/ad.html?gdturl=" + encodeURIComponent(a), !0) : Util.openUrl(a + (a.indexOf("?") > 0 ? "" : "?") + "&_wv=4", !0)
        })
    }
    function l(a) {
        a && a.remove()
    }
    function m(a) {
        t[a] = {
            container: null ,
            hasData: !1,
            data: null 
        }
    }
    function n(a) {
        return a ? a.replace("&nbsp;", " ") : a
    }
    function o(a) {
        if (!a)
            return !1;
        var b = $(a)
          , c = b.offset();
        return c ? c.top + c.height > 10 : !1
    }
    function p(a) {
        var b = a.height()
          , c = $(".bottom-bar");
        return "block" === c.css("display") && "visible" === c.css("visibility") && (E -= c.height()),
        console.log("postHeight=" + b + " screenHeight=" + E),
        b > E
    }
    function q(a) {
        var b, c, d = $("#top_post_wrapper"), e = '<div class="gdt-ad-banner gdt-ad section-1px"></div>';
        return a || (a = p(d)),
        c = a ? "post" : "recommend",
        console.debug("帖子详情里显示广告isAfterPost =" + a),
        $("#detail_ad_wrapper").html(e),
        b = $(".gdt-ad-banner"),
        t[c].container = b,
        c
    }
    var r, s, t = {
        barindex: {
            container: null ,
            hasData: !1,
            data: null 
        },
        post: {
            container: null ,
            hasData: !1,
            data: null 
        },
        recommend: {
            container: null ,
            hasData: !1,
            data: null 
        }
    }, u = {
        barindex: "7000703150186964",
        post: "4030808509165743",
        recommend: ["4090805065898364"]
    }, v = 2, w = 2, x = {
        0: ["热门应用", "下载"],
        1: ["精选商品", "去看看"],
        2: ["吃喝玩乐", "去体验"],
        3: ["推荐", "查看"]
    }, y = {
        0: "app-info",
        1: ""
    }, z = {}, A = Util.queryString("from") || Util.getHash("from"), B = "xingquhao" === A, C = {}, D = $(document).width(), E = $(document).height();
    !function() {
        mqq && mqq.support("mqq.device.getDeviceInfo") && mqq.device.getDeviceInfo(function(a) {
            C.c_os = $.os.android ? "android" : $.os.ios ? "ios" : "other",
            C.c_osver = a.systemVersion,
            C.c_device = a.modelVersion || a.model,
            C.muidtype = $.os.android ? 1 : $.os.ios ? 2 : -1,
            1 === C.muidtype ? C.muid = md5(a.identifier) : C.muid = md5(a.idfa),
            r = !0
        }),
        mqq && mqq.support("mqq.device.getNetworkType") && mqq.device.getNetworkType(function(a) {
            C.conn = -1 === a ? a + 1 : a,
            s = !0
        })
    }();
    var F = {
        startTime: 0,
        endTime: 0,
        _init: function() {
            this.endTime = Date.now()
        },
        exposure: function(a, b) {
            if (a) {
                var c = new Image;
                c.src = a + "&adt=" + Date.now(),
                c = null 
            }
            console.debug(b + "广告曝光上报"),
            this.tdw("expose", b),
            this.monitor("expose", b)
        },
        click: function(a) {
            this.tdw("click", a),
            this.monitor("click", a)
        },
        tdw: function(a, b) {
            var d = {
                obj1: Util.getHash("pid") || Util.queryString("pid"),
                ver1: Util.queryString("bid") || Util.getHash("bid"),
                ver6: $.os.ios ? 2 : $.os.android ? 1 : ""
            }
              , e = {
                barindex: "post_list",
                post: "post_detail",
                recommend: "post_detail"
            }
              , f = {
                barindex: "tribe_ad",
                post: "Grp_tribe",
                recommend: "Grp_tribe"
            }
              , g = {
                barindex: {
                    click: "Clk_ad",
                    expose: "Pv_ad"
                },
                post: {
                    click: "Clk_ad_com_mid",
                    expose: "exp_ad_com_mid"
                },
                recommend: {
                    click: "Clk_ad_recom",
                    expose: "exp_ad_recom"
                }
            };
            d.opername = f[b],
            d.module = e[b],
            d.action = g[b][a],
            "barindex" === b && (d.ver3 = z.barName,
            d.ver4 = z.category,
            d.ver5 = z.categoryName),
            c.tdw(d)
        },
        monitor: function(a, b) {
            if (a && b) {
                var d = {
                    total: {
                        barindex: 2004784,
                        post: 2004929,
                        recommend: 2004786
                    },
                    noAd: {
                        barindex: 2004785,
                        post: 2004930,
                        recommend: 2004787
                    },
                    expose: {
                        barindex: 2004768,
                        post: 2004770,
                        recommend: 2004769
                    },
                    click: {
                        barindex: 2004900,
                        post: 2004902,
                        recommend: 2004901
                    }
                }
                  , e = {
                    total: 2012454,
                    expose: 2012455,
                    click: 2012456
                }
                  , f = {
                    total: 2021681,
                    expose: 2021683,
                    click: 2021684
                }
                  , g = d[a][b];
                g && (c.monitor(g, !1),
                B && c.monitor(e[a], !1),
                z.isSpecific && c.monitor(f[a], !1))
            }
        }
    };
    return {
        init: function(a, b) {
            var c = /\bMicroMessenger\/[\d\.]+/.test(navigator.userAgent);
            c || (F.startTime = Date.now(),
            console.debug(a, "广告模块初始化..."),
            "detail" === a && (a = q()),
            b && (t[a].page = $(b)[0]),
            console.debug("AD container", t[a].container),
            window.setTimeout(function() {
                f(a)
            }, 1e3))
        },
        show: function(a, b) {
            b && (t[a].container = $(b)),
            i(a),
            console.log("ad show()", a)
        },
        justShow: function(a) {
            t[a].container && t[a].container.removeClass("ui-hide"),
            t[a].hasData === !0 && m(a)
        },
        silentFetch: function(a, c) {
            o(c) && (F.startTime = Date.now(),
            t[a].container = $(c),
            console.debug(a, "开始静默获取广告..."),
            "barindex" === a && document.addEventListener("qbrowserVisibilityChange", function(a) {
                b.isWebViewHidden = a.hidden,
                b._onScroll(),
                console.debug("qbrowserVisibilityChange", a.hidden, b.isWebViewHidden)
            }),
            f(a))
        },
        setBarInfo: function(a) {
            z = a,
            console.debug("BarInfo:", z)
        }
    }
}),
function(a, b) {
    a.ViewPreloader = b(a.$, a.DB)
}(this, function() {
    var a, b = 15e3;
    return a = {
        canUseViewPreload: $.os.android && mqq.compare("5.0") >= 0,
        open: function(a) {
            if (this.canUseViewPreload) {
                var b = this
                  , c = a.cgiMap;
                localStorage.setItem("storageConfirm", 1),
                $.each(c, function(a, c) {
                    b.__removeDataStorage(a),
                    b.__send(a, c)
                })
            }
            this.__openView(a)
        },
        __openView: function(a) {
            if (a.url) {
                var b = a.url;
                this.canUseViewPreload && (b += b.indexOf("?") > -1 ? "&viewPreLoad=1" : "?viewPreLoad=1"),
                Util.openUrl(b, !0)
            } else
                a.callback && a.callback(this.canUseViewPreload ? 1 : 0)
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
                    } catch (e) {
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
            return ~~(Util.queryString("viewPreLoad") || Util.getHash("viewPreLoad"))
        },
        receive: function(a, c) {
            var d = this
              , e = this.useViewPreload();
            if (a = a || [],
            e) {
                var f, g, h = {}, i = localStorage.getItem("storageConfirm");
                if (localStorage.removeItem("storageConfirm"),
                "1" === i) {
                    for (var j = 0; j < a.length; j++)
                        g = a[j],
                        f = localStorage.getItem("viewPreloadData-" + g),
                        f && (f = JSON.parse(f),
                        a.splice(j, 1),
                        j--,
                        h[g] = 1,
                        "error" !== f.type ? d.__callbackHandler(0, g, c, f) : d.__callbackHandler(2, g, c)),
                        this.__removeDataStorage(g);
                    a.length && ($.each(a, function(a, b) {
                        d.__callbackHandler(1, b, c)
                    }),
                    mqq.addEventListener("preloadDataSuccess", function(a) {
                        var b = a.dataName;
                        if (d.__removeDataStorage(b),
                        !h[b]) {
                            h[b] = 1;
                            try {
                                f = a.preloadData,
                                f ? d.__callbackHandler(0, b, c, f) : d.__callbackHandler(2, b, c)
                            } catch (e) {
                                d.__callbackHandler(2, b, c)
                            }
                        }
                    }),
                    mqq.addEventListener("preloadDataError", function(a) {
                        var b = a.dataName;
                        d.__removeDataStorage(b),
                        h[b] = 1,
                        d.__callbackHandler(2, b, c)
                    }),
                    setTimeout(function() {
                        $.each(a, function(a, b) {
                            h[b] || (d.__removeDataStorage(b),
                            d.__callbackHandler(3, b, c))
                        })
                    }, b))
                } else
                    $.each(a, function(a, b) {
                        h[b] || (d.__callbackHandler(4, b, c),
                        d.__removeDataStorage(b),
                        h[b] = 1)
                    })
            }
        }
    }
}),
function(a, b) {
    a.ApplyAdmin = b()
}(this, function() {
    function a(a, b) {
        var e = {
            opername: "tribe_cgi",
            module: "post",
            ver1: c,
            ver3: d,
            action: a
        };
        for (var f in b)
            b.hasOwnProperty(f) && (e[f] = b[f]);
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
                if (1 === c.result.violated)
                    return void Tip.show(c.result.violate_msg, {
                        type: "warning"
                    });
                var g = c.result.small_admins
                  , h = c.result.big_admins
                  , i = "";
                if (0 === g && (i += "&sna=1"),
                0 === h && (i += "&bna=1"),
                !c.result.qq_level || c.result.qq_level <= 12)
                    Tip.show("您的QQ等级太低，暂时无法申请酋长！", {
                        type: "warning"
                    }),
                    a("reject_chief", {
                        ver4: 1
                    });
                else if (c.result.isban)
                    mqq.ui.showDialog({
                        title: "",
                        text: "您的帐号因在部落被举报有违规操作，暂时无法申请酋长！",
                        needOkBtn: !0,
                        okBtnText: "我知道了",
                        needCancelBtn: !1
                    }, function() {}),
                    a("reject_chief", {
                        ver4: 2
                    });
                else if (1 === c.result.has_submit || 2 === c.result.has_submit || 3 === c.result.has_submit)
                    3 === c.result.has_submit ? Tip.show("您的酋长申请还在审核流程中，请勿重复申请！", {
                        type: "warning"
                    }) : (1 === c.result.has_submit || 2 === c.result.has_submit) && Tip.show("您在一个月内无法重复申请该部落的酋长！", {
                        type: "warning"
                    }),
                    a("reject_chief", {
                        ver3: 4
                    });
                else if (2 & c.result.admin)
                    Tip.show("您已经是大酋长了，无法申请！", {
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
    a.jubaokit = b()
}(this, function() {
    function a(a) {
        DB.cgiHttp({
            ssoCmd: "jubao",
            param: a,
            url: "/cgi-bin/bar/admin/jubao",
            succ: function(b) {
                var d = function() {
                    Tip.show("举报成功，客服人员将尽快处理。", {
                        type: "ok"
                    }),
                    "function" == typeof c && c()
                }
                ;
                b.result && 1 === b.result.vflag ? Checkcode.show("report", function() {
                    d()
                }, a) : 0 == b.retcode ? d() : Tip.show("举报失败，请稍后重试。", {
                    type: "warning"
                })
            },
            err: function(a) {
                Tip.show("举报失败，请稍后重试。", {
                    type: "warning"
                })
            }
        })
    }
    function b(b) {
        var e = b.barId
          , f = b.pid
          , g = b.eviluin
          , h = b.impeachuin
          , i = $.os.ios ? "ios" : $.os.android ? "android" : "";
        c = b.callback || "";
        var j = {
            appname: "KQQ",
            subapp: "qunbuluo",
            jubaotype: "article",
            langcode: "0",
            impeach_origin_uin: g,
            app_param: encodeURIComponent("bid=" + e + "&eviluin=" + g + "&impeachuin=" + h + "&pid=" + f + "&system=" + i),
            sbm: "确定",
            bid: +e,
            pid: f,
            sso_key: $.cookie("verifysession")
        };
        $.os.ios && 0 !== mqq.QQVersion && $.os.version && 1 * $.os.version.charAt(0) > 7 ? mqq.ui.showActionSheet({
            items: d,
            cancel: "取消"
        }, function(b, c) {
            0 === Number(b) && (j.impeach_content = Number(c) + 1,
            a(j))
        }) : ActionSheet.show({
            useH5: !0,
            items: d,
            cancleText: "取消",
            onItemClick: function(b) {
                j.impeach_content = Number(b) + 1,
                a(j)
            }
        })
    }
    var c, d = ["欺诈骗钱", "色情暴力", "广告骚扰", "其他"];
    return {
        init: b
    }
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_detail = b()
}(this, function() {
    var a = {}
      , b = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("list")
          , f = c("myuin")
          , g = c("Date")
          , h = "";
        h += "";
        for (var i = e.isAtvCGI, j = 0; j < e.length; j++) {
            var k = e[j];
            j > 0 && f === k.uin || (k.ban = 0,
            i && (k.nick_name = k.nick_name || k.name,
            k.ban = k.status || 0,
            1 === k.ban ? k.pic = "http://q.qlogo.cn/g?b=qq&nk=1&s=100" : k.pic = k.pic || "http://q.qlogo.cn/g?b=qq&nk=" + k.uin + "&s=100&t=" + g.now()),
            h += ' <li class="face" data-uin="',
            h += d(k.uin),
            h += '" data-ban="',
            h += d(k.ban),
            h += '"> <a> <img src="',
            h += d(k.pic),
            h += '" class="list-img"> <p>',
            h += d(k.nick_name),
            h += "</p> </a> </li> ")
        }
        return h += " "
    }
    ;
    a.join_list = "TmplInline_detail.join_list",
    Tmpl.addTmpl(a.join_list, b);
    var c = '\r\n<div soda-if="show_pay" class="appreciation-wrap">\r\n    <div class="appreciation">\r\n        <a class="btn-appreciation" data-bid={{bid}} data-pid={{pid}}>赞赏</a>\r\n        <span data-bid={{bid}} data-pid={{pid}} class="head-count {{pay_num_class}}"><i>{{appreciated_count}}</i><b>人赞赏</b></span>\r\n    </div>\r\n    <div class="appreciation-list-wrapper"><div class="appreciation-list {{pay_num_class}}"></div></div>\r\n</div>\r\n';
    a.appreciation = "TmplInline_detail.appreciation",
    Tmpl.addTmpl(a.appreciation, c);
    var d = '<img soda-repeat="item in users" soda-src="{{|getDefaultAvatar}}" lazy-src="{{item.pic}}" data-uin="{{item.uin}}" alt="">\r\n';
    a.appreciation_list = "TmplInline_detail.appreciation_list",
    Tmpl.addTmpl(a.appreciation_list, d);
    var e = '<div class="bar-nav section-1px" id="bar_nav">\r\n    <img soda-src="{{bar_pic}}" class="bar-logo">\r\n    <div class="bar-info">\r\n        <p class="bar-name">{{bar_name}}</p>\r\n        <p class="bar-intro">{{bar_intro || bar_name}}</p>\r\n    </div>\r\n    \r\n    <div  class="detail-game " id="detail_game">\r\n        <div><a id="detail_game_btn" class="detail-game-btn">启动游戏</a></div>\r\n    </div>\r\n</div>\r\n';
    a.bar_nav = "TmplInline_detail.bar_nav",
    Tmpl.addTmpl(a.bar_nav, e);
    var f = '\r\n<div soda-if="show_pay" class="appreciation">\r\n    <a class="btn-appreciation" data-bid={{bid}} data-pid={{pid}}>赞赏</a>\r\n    <span data-bid={{bid}} data-pid={{pid}} class="head-count {{pay_num_class}}"><i>{{appreciated_count}}</i><b>人赞赏</b></span>\r\n</div>\r\n';
    a.detail_appreciation = "TmplInline_detail.detail_appreciation",
    Tmpl.addTmpl(a.detail_appreciation, f);
    var g = '<div id="recommend-title" soda-if="postlist && postlist.length" class="recommend-title section-1px">\r\n  <i class="left-side"></i>\r\n  <span>推荐话题</span>\r\n</div>\r\n<ul class="recommend-post-list">\r\n  <li class="section-1px" soda-repeat="item in postlist" data-bid="{{item.bid}}" data-pid="{{item.pid}}" data-type="{{item.type}}" openactid="{{item.post.openact_id}}">\r\n    <div class="post-title text-overflow-ellipsis" soda-bind-html="(item.title)|plain2rich">\r\n    </div>\r\n  </li>\r\n  <li class="section-1px" data-bid="{{bid}}">\r\n    <div class="post-bar">查看更多<span>{{barName}}</span>话题</div>\r\n  </li>\r\n</ul>\r\n';
    a.recommend = "TmplInline_detail.recommend",
    Tmpl.addTmpl(a.recommend, g);
    var h = ' <div class="user-avatar" data-profile-uin="{{uin}}">\r\n    <img soda-src="{{user.pic|defaultAvatar}}">\r\n\r\n</div>\r\n\r\n\r\n<div class="name-wrap">\r\n    <span class="author user-nick {{user.vipno ? \'user-nick-vipno\' : \'\'}}" data-profile-uin="{{uin}}" soda-bind-html="user|showUserName"></span>\r\n\r\n    <label  soda-if=\'!isOfficialAccountTribe\' class="l-level lv{{user.level}}" >LV.{{user.level}}</label>\r\n\r\n    <label soda-if="user.gender != 0" class="author-{{user.gender==1?\'\':\'fe\'}}male"></label>\r\n     \r\n    <span soda-bind-html="user|renderHonours:barId"></span>\r\n    \r\n    <span soda-bind-html="ispostor|showPoster"></span>\r\n\r\n    \r\n    <span class="floor">{{index|realFloor}}楼</span>\r\n</div>\r\n\r\n\r\n<div class="content-wrapper">\r\n\r\n    <div class="content allow-copy" soda-bind-html="comment|processContent"></div>\r\n\r\n     \r\n    <div soda-repeat="pic in comment.pic_list" class="img-box pre-size" style="{{pic|getImgOffset}}" >\r\n        <img data-src="{{pic.url||pic}}" soda-src="{{pic|getImgSrc}}" >\r\n    </div>\r\n\r\n    \r\n    <div soda-repeat="audio in comment.audio_list" class="audio" rel="playAudio" data-url="{{audio.url|decodeStr}}" data-duration="{{audio.duration}}" data-pid="{{pid}}" data-index="{{$index}}" style="width: {{audio|getAudioWrapperWidth}}px">\r\n        <span class="icon"></span>\r\n        <span class="length">{{audio.duration || 0}}"</span>\r\n    </div>\r\n\r\n    \r\n    <div soda-repeat="music in comment.qqmusic_list"  class="music"  rel="openMusic" data-url="{{music.audio_url|decodeStr}}" data-pid="{{pid}}" data-id="{{music.id}}" data-index="{{$index}}" data-href="{{music.share_url|decodeStr}}" data-img="{{music.image_url|decodeStr}}" data-name="{{music.title}}" data-singer="{{music.desc}}">\r\n        <div class="avator" rel="playMusic" data-target=".music">\r\n            <img soda-src="{{music.image_url|decodeStr}}">\r\n            <span class="icon"></span>\r\n        </div>\r\n        <div class="info" rel="openMusic" data-target=".music">\r\n            <div class="name"><span soda-bind-html="music.title"></span><i class="playing-icon"></i></div>\r\n            <div class="desc" soda-bind-html="music.desc"></div>\r\n        </div>\r\n    </div>\r\n\r\n    \r\n    <div soda-repeat="video in comment.video_list" class="post-video" id="vplayer_{{video.vid}}" data-vid="{{video.vid}}" data-pid="{{pid}}"></div>\r\n\r\n</div>\r\n';
    a.ref_comment = "TmplInline_detail.ref_comment",
    Tmpl.addTmpl(a.ref_comment, h);
    var i = '<div class="qunact-basic" id="js_act_basic">\r\n    <div class="qunact-cover-wrap">\r\n        <div class="cover-bg-mask"></div>\r\n        <div class="cover-bg" style="background-image: url(\'{{post.pic_list[0].url}}\')"></div>\r\n\r\n        <div class="qunact-info">\r\n\r\n            <div class="cover-img-wrap">\r\n                <div class="cover-img-fix">\r\n                    <div class="cover-img"  style="background-image: url(\'{{post.pic_list[0].url}}\')">\r\n                    </div>\r\n                    <div soda-if="post.statusClass" class="qunact-status {{post.statusClass}}"></div>\r\n                </div>\r\n            </div>\r\n            <div class="qunact-info-content">\r\n                <h3 class="qunact-title"><span class="js-detail-title" soda-bind-html="title|plain2rich"></span></h3>\r\n\r\n                <p class="qunact-detail">\r\n                    <span class="qunact-i-count"><span><span id="js-peopleNum-cover">0</span>人报名</span></span>\r\n                </p>\r\n                <span soda-if="post.price" class="price">{{post.price + (post.price !== \'免费\'? \'（人均）\': \'\')}}</span>\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <div class="qunact-tools-wrap">\r\n        <div class="qunact-tools-list qunact-transy">\r\n                <a href="javascript:void(0)" id="js-qunact-like" class="qunact-like qunact-transx qunact-i-like">点赞</a>\r\n                <a  soda-if="hasQGroup" href="javascript:void(0)" id="js-qunact-comment" class="qunact-comment qunact-transx qunact-i-add" code="{{gid}}">加群讨论</a>\r\n                <a href="javascript:void(0)" id="js-qunact-share" class="qunact-share qunact-transx qunact-i-share">分享</a>\r\n        </div>\r\n    </div>\r\n    <div class="qunact-info-wrap">\r\n        <div soda-if="post.addr" class="qunact-info-item qunact-i-addr" id="js-qunact-addr">\r\n            <span class="qunact-info-item-content qunact-transy">{{post.addr}}</span>\r\n        </div>\r\n        <div soda-if="post.time" class="qunact-info-item qunact-i-time">\r\n            <span class="qunact-info-item-content qunact-transy">{{post.time}}</span>\r\n        </div>\r\n        <div soda-if="isOpenAct || post.tag" class="qunact-info-item qunact-i-class">\r\n            <span class="qunact-info-item-content qunact-transy">{{isOpenAct && !post.tag? \'同城活动\': post.tag}}</span>\r\n        </div>\r\n    </div>\r\n</div>\r\n<div class="introduce">\r\n    <h3>活动介绍</h3>\r\n    <div id="more_introduce" class="introduce-content">\r\n        <p class="more_introduce qunact-intro" soda-bind-html="post|processContent|matchSearch"></p>\r\n    </div>\r\n    <div class="qunact-show-more">\r\n        <a id="js-qunact-showmore">展开</a>\r\n    </div>\r\n</div>\r\n<div class="people-joined ui-item" id="js_act_people">\r\n    <h3 class="people-joined-title">{{gameAct?\'预约\':\'参加\'}}的人</h3>\r\n    <div class="right-val"><span id="peopleNum"></span>人</div>\r\n    <div class="arrow"></div>\r\n    <div class="people-header" id="people_header_list"></div>\r\n</div>\r\n<div soda-if="post.from === \'openact\'" id="atvCreater" class="ui-item ui-item-oneline" rel="showProfile" type="2" code="{{user_info.uin}}">\r\n    <label>发起人</label>\r\n    <div class="right-val{{user_info.vipno ? \' right-val-vipno\' : \'\'}}">{{user_info.nick_name}}</div>\r\n    <div class="arrow"></div>\r\n</div>\r\n\r\n\r\n<div soda-if="hasQGroup" class="ui-item ui-item-oneline" id="js_act_qun" rel="showProfile" type="1" code="{{gid}}">\r\n    <label>关联群组</label>\r\n    <div class="right-val">{{ginfo.name}}</div>\r\n    <div class="arrow"></div>\r\n</div>\r\n';
    a.top_activity = "TmplInline_detail.top_activity",
    Tmpl.addTmpl(a.top_activity, i);
    var j = '\r\n<div class="content-wrapper allow-copy">\r\n\r\n    \r\n    <div soda-if="post.rss == 1">\r\n        <div soda-repeat="item in contentList" soda-bind-html="item|rssRender|matchSearch"></div>\r\n    </div>\r\n\r\n    \r\n    <div soda-if="type === 300 && post.rss != 1" soda-repeat="item in posts">\r\n        <div class="content" style="padding-top:15px" soda-bind-html="item|processContent|matchSearch"></div>\r\n        <div soda-repeat="pic in item.pic_list" class="img-box pre-size" style="{{pic|getImgOffset}}" >\r\n            <img data-src="{{pic.url||pic}}" lazy-src="{{pic|getImgSrc}}" >\r\n        </div>\r\n    </div>\r\n\r\n    \r\n    <div soda-if="type === 301" class="content rich_media_content" id="wechat_content" soda-bind-html="public_account_post.content"></div>\r\n\r\n    \r\n    <div soda-if="type === 302" class="richpost-new" soda-bind-html="post|richPostCompile"></div>\r\n\r\n\r\n    \r\n    <div soda-if="type !== 300 && type !== 301 && type !== 302">\r\n        <div class="content" soda-bind-html="post|processContent|matchSearch"></div>\r\n\r\n        \r\n        <div soda-repeat="pic in post.pic_list" class="img-box pre-size" style="{{pic|getImgOffset}}" >\r\n            <img data-src="{{pic.url||pic}}" lazy-src="{{pic|getImgSrc:$index}}" >\r\n        </div>\r\n\r\n        \r\n        <div soda-repeat="audio in post.audio_list" class="audio" rel="playAudio" data-page="detail" data-url="{{audio.url|decodeStr}}" data-duration="{{audio.duration}}" data-pid="{{pid}}" data-index="{{$index}}" style="width: {{audio|getAudioWrapperWidth}}px">\r\n            <span class="icon"></span>\r\n            <span class="length">{{audio.duration||0}}"</span>\r\n        </div>\r\n\r\n        \r\n        <div soda-repeat="music in post.qqmusic_list"  class="music"  rel="openMusic" data-page="detail" data-url="{{music.audio_url|decodeStr}}" data-pid="{{pid}}" data-id="{{music.id}}" data-index="{{$index}}" data-href="{{music.share_url|decodeStr}}" data-img="{{music.image_url|decodeStr}}" data-name="{{music.title}}" data-singer="{{music.desc}}">\r\n            <div class="avator" rel="playMusic" data-target=".music">\r\n                <img soda-src="{{music.image_url|decodeStr}}">\r\n                <span class="icon"></span>\r\n            </div>\r\n            <div class="info" rel="openMusic" data-target=".music">\r\n                <div class="name"><span soda-bind-html="music.title"><i class="playing-icon"></i></div>\r\n                <div class="desc" soda-bind-html="music.desc"></div>\r\n            </div>\r\n        </div>\r\n\r\n        \r\n        <div class="video-preview">\r\n            <div class="icon-play"><div class="triangle"></div></div>\r\n            <div class="preview-box"></div>\r\n        </div>\r\n\r\n        \r\n        <div soda-repeat="video in post.video_list" class="post-video" id="vplayer_{{video.vid}}" data-vid="{{video.vid}}" data-pid="{{pid}}"></div>\r\n    </div>\r\n\r\n    \r\n    <div soda-if="_isQQ && (post.is_recruit == 1 ||(posts && posts[1] && posts[1].is_recruit == 1))" class="apply-admin-apply-btn" id="js_detail_apply_post">点击申请酋长</div>\r\n\r\n    \r\n    <div soda-if="_isQQ && gname && related_group" class="booked-tribes-wraper">\r\n        <div class="booked-tribes border-1px openGroup" data-groupcode="{{related_group}}">\r\n            <span class="label border-1px">来自群</span><span class="groupname">{{gname}}</span>\r\n        </div>\r\n    </div>\r\n\r\n\r\n\r\n</div>\r\n\r\n<div class="actions">\r\n\r\n    <a soda-if="public_account_post && public_account_post.site" class="btn-action read-content" href="{{public_account_post.site|decodeStr}}">阅读原文</a>\r\n\r\n    <div soda-if="addr && addr.city" class="location-area text-overflow-ellipsis">\r\n        <span href="javascript:;" class="location" data-lat="{{addr.latitude}}" data-lon="{{addr.longitude}}" data-buzid="{{addr.buzId || \'\' }}">{{addr|getAddress}}</span>\r\n    </div>\r\n\r\n    <span class="read-num" ><i class="read-num-icon"></i>{{readnum + 1}}</span>\r\n\r\n    <a soda-if="_isAdmin || isposter" class="btn-action delete {{_isAdmin ? \'isAdmin\':\'\'}}" href="javascript:void(0)" id="js_detail_delete_post" title="删除">删除</a>\r\n\r\n    <a soda-if="!_isAdmin && !isposter && can_report" class="btn-action report" href="javascript:void(0)" id="js_detail_report" title="举报">举报</a>\r\n</div>\r\n';
    a.top_detail = "TmplInline_detail.top_detail",
    Tmpl.addTmpl(a.top_detail, j);
    var k = '\r\n\r\n<div soda-if="post.content" class="live-content">\r\n  <p soda-bind-html="post|processContent|matchSearch"></p>\r\n</div>\r\n\r\n<div soda-if="!isVideo" class="summarize">\r\n  <div class="right">\r\n    <div class="live-status-button live_pre" id="live_status_button">\r\n      <div class="live-status-icon"></div>\r\n      <span id="live_status_wording">直播中</span>\r\n    </div>\r\n    <div class="message-button" id="detail_message_button" rel="publish_comment">\r\n      <div class="message-icon"></div>\r\n      <span class="replies" id="detail_replies_total">{{total_comment}}</span>\r\n    </div>\r\n  </div>\r\n</div>\r\n<div class="live-video">\r\n  <div id="mod_player"></div>\r\n</div>\r\n\r\n\r\n\r\n<div soda-if="post.content" class="live-content">\r\n  <p class="live-read-num-container"><span class="read-num"><i class="read-num-icon"></i>{{readnum}}</span></p>\r\n</div>\r\n\r\n<div soda-if="!isVideo" class="people-joined section-1px" id="js_act_people">\r\n  <a class="quit-link" id="quit_link" href="javascript:;">取消报名</a>\r\n  <h3 id="people_joined_title">参加的人(<span class="num" id="peopleNum"></span>)</h3>\r\n  <div class="people-header" id="people_header_list"></div>\r\n  <div class="arrow-wrap">\r\n    <a href="javascript:void(0)" class="right-arrow"></a>\r\n  </div>\r\n</div>\r\n\r\n<div class="actions">\r\n    <a soda-if="_isAdmin" class="btn-action delete isAdmin" href="javascript:void(0)" id="js_detail_delete_post" title="删除">删除</a>\r\n</div>\r\n';
    a.top_live = "TmplInline_detail.top_live",
    Tmpl.addTmpl(a.top_live, k);
    var l = '<div class="qqmusic-bg" style="background-image: url({{music.image_url|decodeStr}})" ></div>\r\n<div class="qqmusic-bg-mask"></div>\r\n<div class="music-info">\r\n   <div class="qqmusic">\r\n        <span class="disc" style="background-image: url({{music.image_url|decodeStr}})" ></span>\r\n        <div class="qqmusic-player" rel="playMusic" data-special="1" data-url="{{music.audio_url|decodeStr}}" data-href="{{music.share_url|decodeStr}}" data-img="{{music.image_url|decodeStr}}" data-pid="{{pid}}" data-id="{{music.id}}"  data-name="{{music.title}}" data-singer="{{music.desc}}">\r\n            <span class="icon"></span>\r\n        </div>\r\n</div>\r\n\r\n<div class="qqmusic-info">\r\n    <div class="song-name">{{music.title}}</div>\r\n    <div class="singer"><span>{{music.desc}}</span></div>\r\n</div>\r\n<div  class="qqmusic-download-wrap">\r\n    <span id="js_download_music" class="qqmusic-download" data-id="{{music.id}}">下载这首歌</span>\r\n</div>\r\n</div>\r\n\r\n';
    a.top_music = "TmplInline_detail.top_music",
    Tmpl.addTmpl(a.top_music, l);
    var m = '<div class="pk-top">\r\n    <h2 class="title" soda-bind-html="title|plain2rich"></h2>\r\n    <p class="pk-text" soda-bind-html="post|processContent|matchSearch"></p>\r\n</div>\r\n\r\n<div class="pk-content">\r\n    <div class="pk-desc-info">\r\n        <span soda-if="!post.time_type && progress_state === 1">{{post.end_time|getPKEndtime}}</span>\r\n        <span class="info-count">{{vote_result.total_vote_result.ops[1].count + vote_result.total_vote_result.ops[0].count}}人参与</span>\r\n        <i class="info-icon"></i>\r\n    </div>\r\n    \r\n    <div class="block" soda-if="!type">\r\n        <div class="left-img">\r\n            <div class="result-num-mask"></div>\r\n            <div class="result-num" data-role="left" data-num="{{vote_result.total_vote_result.ops[0].count}}">{{(vote_state > 0 || progress_state === 2) ? vote_result.total_vote_result.ops[0].count : 0}}</div>\r\n            <img soda-src="{{post.aSide.pic.url}}" />\r\n        </div>\r\n        <div class="right-img">\r\n            <div class="result-num-mask"></div>\r\n            <div class="result-num" data-role="right" data-num="{{vote_result.total_vote_result.ops[1].count}}">{{(vote_state > 0 || progress_state == 2) ? vote_result.total_vote_result.ops[1].count : 0}}</div>\r\n            <img soda-src="{{post.bSide.pic.url}}" />\r\n        </div>\r\n    </div>\r\n\r\n    \r\n    <div class="block" soda-if="type">\r\n        <div class="left block-content">\r\n            <span><p>{{post.aSide.content}}</p></span>\r\n        </div>\r\n        <div class="right block-content">\r\n            <span><p>{{post.bSide.content}}</p></span>\r\n        </div>\r\n    </div>\r\n\r\n    \r\n    <div class="button">\r\n        \r\n        <div id="left" class="left {{progress_state|getVoteState}}">\r\n            \r\n            <div soda-if="progress_state === 0" class="zan unprogress"></div>\r\n\r\n            \r\n            <div soda-if="progress_state === 1 && vote_state === 0" class="zan unvoted {{type?\'result-num-text\':\'\'}}" data-role="left" data-num="{{vote_result.total_vote_result.ops[0].count}}"></div>\r\n\r\n            \r\n            <div soda-if="progress_state === 2 || vote_state > 0" class="zan {{type?\'result-num-text-voted\':\'\'}}">\r\n                {{type?vote_result.total_vote_result.ops[0].count:\'\'}}\r\n            </div>\r\n\r\n            <div class="zan-effect"></div>\r\n            <div class="con">{{type?\'支持\':post.aSide.content}}</div>\r\n        </div>\r\n        \r\n        <div id="right" class="right {{progress_state|getVoteState}}">\r\n            <div class="con">{{type?\'支持\':post.bSide.content}}</div>\r\n            <div class="zan-effect"></div>\r\n\r\n            \r\n            <div soda-if="progress_state === 0" class="zan unprogress"></div>\r\n\r\n            \r\n            <div soda-if="progress_state === 1 && vote_state === 0" class="zan unvoted {{type?\'result-num-text\':\'\'}}" data-role="right" data-num="{{vote_result.total_vote_result.ops[1].count}}"></div>\r\n\r\n            \r\n            <div soda-if="progress_state === 2 || vote_state > 0" class="zan {{type?\'result-num-text-voted\':\'\'}}">\r\n                {{type?vote_result.total_vote_result.ops[1].count:\'\'}}\r\n            </div>\r\n        </div>\r\n        \r\n        <div class="middle">\r\n            <div class="mask {{progress_state|getVoteState}}">\r\n                <span>{{progress_state|getVoteStateText}}</span>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n';
    a.top_pk = "TmplInline_detail.top_pk",
    Tmpl.addTmpl(a.top_pk, m);
    var n = '<div class="postarray-content content-wrapper allow-copy">\r\n    <div soda-repeat="item in post.post_array" class="array-item">\r\n\r\n        <div soda-if="item.type === \'qqmusic\'" class="music"  rel="openMusic" data-page="detail" data-url="{{item.audio_url|decodeStr}}" data-pid="{{pid}}" data-id="{{item.id}}" data-index="{{$index}}" data-href="{{item.share_url|decodeStr}}" data-img="{{item.image_url|decodeStr}}" data-name="{{item.title}}" data-singer="{{item.desc}}">\r\n            <div class="avator" rel="playMusic" data-target=".music">\r\n                <img soda-src="{{item.image_url|decodeStr}}">\r\n                <span class="icon"></span>\r\n            </div>\r\n            <div class="info" rel="openMusic" data-target=".music">\r\n                <div class="name"><span soda-bind-html="item.title"><i class="playing-icon"></i></div>\r\n                <div class="desc" soda-bind-html="item.desc"></div>\r\n            </div>\r\n        </div>\r\n\r\n        <div soda-if="item.type === \'pic\'" class="img-box pre-size" style="{{item,\'pa\'|getImgOffset}}" >\r\n            <img data-src="{{item.url||pic}}" lazy-src="{{item|getImgSrc:$index}}" >\r\n        </div>\r\n\r\n\r\n        <div soda-if="item.type === \'text\'" class="content" soda-bind-html="item,post|processContent|matchSearch"></div>\r\n\r\n        <div soda-if="item.type === \'video\'" class="post-video" id="vplayer_{{item.vid}}" data-vid="{{item.vid}}" data-pid="{{pid}}"></div>\r\n\r\n        <div soda-if="item.type === \'audio\'" class="audio" rel="playAudio" data-page="detail" data-url="{{item.url|decodeStr}}" data-duration="{{item.duration}}" data-pid="{{pid}}" data-index="{{$index}}" style="width: {{item|getAudioWrapperWidth}}px">\r\n            <span class="icon"></span>\r\n            <span class="length">{{item.duration}}"</span>\r\n        </div>\r\n    </div>\r\n</div>\r\n\r\n<div class="actions">\r\n\r\n    <div soda-if="addr && addr.city" class="location-area text-overflow-ellipsis">\r\n        <span href="javascript:;" class="location" data-lat="{{addr.latitude}}" data-lon="{{addr.longitude}}" data-buzid="{{addr.buzId || \'\' }}">{{addr|getAddress}}</span>\r\n    </div>\r\n\r\n    <span class="read-num" ><i class="read-num-icon"></i>{{readnum + 1}}</span>\r\n\r\n    <a soda-if="_isAdmin || isposter" class="btn-action delete {{_isAdmin ? \'isAdmin\':\'\'}}" href="javascript:void(0)" id="js_detail_delete_post" title="删除">删除</a>\r\n\r\n    <a soda-if="!_isAdmin && !isposter && can_report" class="btn-action report" href="javascript:void(0)" id="js_detail_report" title="举报">举报</a>\r\n</div>\r\n';
    a.top_postarray = "TmplInline_detail.top_postarray",
    Tmpl.addTmpl(a.top_postarray, n);
    var o = '<div class="qunact-basic" id="js_act_basic">\r\n    <div class="qunact-cover-wrap">\r\n        <div class="cover-bg-mask"></div>\r\n        <div class="cover-bg" style="background-image: url(\'{{post.cover}}\')"></div>\r\n\r\n        <div class="qunact-info">\r\n\r\n            <div class="cover-img-wrap">\r\n                <div class="cover-img-fix">\r\n                    <div class="cover-img" style="background-image: url(\'{{post.cover}}\')" >\r\n                    </div>\r\n                    <div class="qunact-status {{post.statusClass}}"></div>\r\n                </div>\r\n            </div>\r\n            <div class="qunact-info-content">\r\n                <h3 class="qunact-title"><span class="js-detail-title" soda-bind-html="title|plain2rich"></span></h3>\r\n\r\n                <p class="qunact-detail">\r\n                    <span class="qunact-i-count"><span><span id="js-peopleNum-cover">0</span>人报名</span></span>\r\n                    <span soda-if="post.grp_certify" class="qunact-i-stat"><span>发布者已认证</span></span>\r\n                </p>\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <div class="qunact-tools-wrap">\r\n        <div class="qunact-tools-list qunact-transy">\r\n                <a href="javascript:void(0)" id="js-qunact-forward" class="qunact-transx qunact-i-forward icon-forward">转发</a>\r\n                <a soda-if="hasQGroup" href="javascript:void(0)" id="js-qunact-comment" class="qunact-comment qunact-transx qunact-i-add" code="{{post.gid}}">加群讨论</a>\r\n                <a href="javascript:void(0)" id="js-qunact-share" class="qunact-share qunact-transx qunact-i-share">分享</a>\r\n        </div>\r\n    </div>\r\n    <div class="qunact-info-wrap">\r\n        <div class="qunact-info-item qunact-i-addr" id="js-qunact-addr">\r\n            <span class="qunact-info-item-content qunact-transy">\r\n                <span class="qunact-info-item-text">{{post.addr}}</span>\r\n                <span soda-if="post.lat" class="qunact-i-arrow"></span>\r\n            </span>\r\n        </div>\r\n        <div class="qunact-info-item qunact-i-time">\r\n            <span class="qunact-info-item-content qunact-transy">{{post.time}}</span>\r\n        </div>\r\n        <div class="qunact-info-item qunact-i-class">\r\n            <span class="qunact-info-item-content qunact-transy">{{post.openact_class}}</span>\r\n        </div>\r\n    </div>\r\n</div>\r\n\r\n<div class="introduce">\r\n    <h3>活动介绍</h3>\r\n    <div id="more_introduce" class="introduce-content">\r\n        <div class="more_introduce qunact-intro" soda-bind-html="post|richPostCompile"></div>\r\n    </div>\r\n    <div class="qunact-show-more">\r\n        <a id="js-qunact-showmore">展开</a>\r\n    </div>\r\n</div>\r\n\r\n<div soda-if="hasCreator" class="ui-item ui-item-oneline" id="js_act_qun" rel="showProfile" type="1" code="{{post.create_uin}}">\r\n    <label>主办方</label>\r\n    <div class="right-val" soda-bind-html="post.create_name"></div>\r\n    <div class="arrow"></div>\r\n</div>\r\n\r\n<div class="people-joined ui-item" id="js_act_people">\r\n    <h3 class="people-joined-title">{{gameAct?\'预约\':\'报名\'}}人数</h3>\r\n    <div class="right-val"><span id="peopleNum"></span>人</div>\r\n    <div soda-if="!isWX" class="arrow"></div>\r\n    <div class="people-header" id="people_header_list"></div>\r\n</div>\r\n\r\n<div class="qunact-barindex-wrap qunact-transy" id="js_jump_barindex">\r\n    <div class=\'qunact-barindex\'>\r\n        <label>{{bar_name}}部落</label>\r\n        <div class="arrow"></div>\r\n    </div>\r\n</div>\r\n\r\n\r\n\r\n';
    return a.top_qunactivity = "TmplInline_detail.top_qunactivity",
    Tmpl.addTmpl(a.top_qunactivity, o),
    a
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_commentList = b()
}(this, function() {
    var a = {}
      , b = '<ul class="lists" id="comment_page_{{currentPage}}">\r\n    \r\n    <li soda-repeat="comment in comments" id="comment_{{comment.index}}" class="list  section-1px {{comment.ad?\'gdt-ad\':\'\'}}" cid="{{comment.cid}}" nick_name="{{comment.user.nick_name}}" data-lz="{{comment.index|realFloor}}">\r\n        <div class="comment-wrapper">\r\n            <div class="user-avatar" data-profile-uin="{{comment.uin}}">\r\n                <img soda-src="{{|getDefaultAvatar}}" lazy-src="{{comment.user.pic|defaultAvatar}}" soda-src="{{0|defaultAvatar}}">\r\n            </div>\r\n\r\n            \r\n            <div class="name-wrap">\r\n                <div class=\'name-section1\' >\r\n                <span class="author user-nick {{(comment.user.vipno && !isOfficialAccountTribe) ? \'user-nick-vipno\' : \'\'}}" data-profile-uin="{{comment.uin}}" soda-bind-html="comment.user|showUserName" style="{{comment._needShort ? \'max-width:65px;\' : \'\' }}" ></span>\r\n\r\n                <label soda-if=\'comment.user.level  && !isOfficialAccountTribe\'  class="l-level lv{{comment.user.level}}" >LV.{{comment.user.level}}</label>\r\n\r\n                <label soda-if="comment.user.gender != 0" class="author-{{comment.user.gender==1?\'\':\'fe\'}}male"></label>\r\n                \r\n                <span soda-if="!isOfficialAccountTribe" soda-bind-html="comment.user|renderHonours:barId"></span>\r\n                \r\n                <span soda-bind-html="comment.ispostor|showPoster"></span>\r\n\r\n                \r\n                <span class="floor">{{comment.index|realFloor}}楼</span>\r\n\r\n                \r\n                \r\n                </div>\r\n            </div>\r\n\r\n            \r\n            <div class="content-wrapper">\r\n\r\n                <div class="content allow-copy" id="detail_comment_{{comment.cid}}" soda-bind-html="comment.comment|processContent"></div>\r\n\r\n                \r\n                <div class="img-box-container" soda-if="comment.comment.pic_list && comment.comment.pic_list.length">\r\n                    <div soda-repeat="pic in comment.comment.pic_list" class="img-box" style="width:{{pic.boxWidth}}px;height:{{pic.boxHeight}}px;" >\r\n                        <img data-src="{{pic.url||pic}}" style="margin-top:{{pic.marginTop}}px;margin-left:{{pic.marginLeft}}px;width:{{pic.width}}px;height:{{pic.height}}px" lazy-src="{{pic|getImgSrc:comment.cid}}" >\r\n                        <span soda-if="pic.type_info && pic.type_info.type===\'gif\'" class="img-gif"></span>\r\n                    </div>\r\n                </div>\r\n\r\n                \r\n                <div soda-repeat="audio in comment.comment.audio_list" class="audio" rel="playAudio" data-page="detail" data-url="{{audio.url|decodeStr}}" data-duration="{{audio.duration}}" data-pid="{{pid}}" data-index="{{$index}}" style="width: {{audio|getAudioWrapperWidth}}px">\r\n                    <span class="icon"></span>\r\n                    <span class="length">{{audio.duration || 0}}"</span>\r\n                </div>\r\n\r\n                \r\n                <div soda-repeat="music in comment.comment.qqmusic_list"  class="music"  rel="openMusic" data-page="detail" data-url="{{music.audio_url|decodeStr}}" data-pid="{{pid}}" data-id="{{music.id}}" data-index="{{$index}}" data-href="{{music.share_url|decodeStr}}" data-img="{{music.image_url|decodeStr}}" data-name="{{music.title}}" data-singer="{{music.desc}}">\r\n                    <div class="avator" rel="playMusic" data-target=".music">\r\n                        <img soda-src="{{music.image_url|decodeStr}}">\r\n                        <span class="icon"></span>\r\n                    </div>\r\n                    <div class="info" rel="openMusic" data-target=".music">\r\n                        <div class="name"><span soda-bind-html="music.title"></span><i class="playing-icon"></i></div>\r\n                        <div class="desc" soda-bind-html="music.desc"></div>\r\n                    </div>\r\n                </div>\r\n\r\n                \r\n                <div soda-repeat="video in comment.comment.video_list" class="post-video" id="vplayer_{{video.vid}}" data-vid="{{video.vid}}" data-pid="{{pid}}"></div>\r\n\r\n                \r\n                <div soda-if="comment.ref_comment" class="ref-comment" data-id="{{comment.ref_comment.cid}}" data-isdel="{{comment.ref_comment.isdel}}" >\r\n                    <span soda-if="!comment.ref_comment.isdel"><span soda-bind-html="comment.ref_comment.user|showUserName"></span>: </span>\r\n                    <span>{{comment.ref_comment.comment.textPrefix}}</span>\r\n                    <span soda-bind-html="comment.ref_comment.comment|processContent"></span>\r\n                </div>\r\n            </div>\r\n\r\n            \r\n            <div class="actions">\r\n                \r\n                <span class="btn-action time">{{comment.time|formatTime}}</span>\r\n\r\n                \r\n                \r\n\r\n                \r\n                <span soda-if="comment.iscommentor || _isPoster || _isAdmin" class="js-btn-action btn-action delete {{_isAdmin?\'isAdmin\':\'\'}}">删除</span>\r\n\r\n                \r\n                <span class="btn-action reply"></span>\r\n\r\n                \r\n                <span class="js-btn-action btn-action like {{comment.like == 1 ? \'liked\':\'\'}}">{{comment.liketotal|formatNum}}</span>\r\n            </div>\r\n        </div>\r\n    </li>\r\n</ul>\r\n';
    return a.normal = "TmplInline_commentList.normal",
    Tmpl.addTmpl(a.normal, b),
    a
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_actionsheet = b()
}(this, function() {
    var a = {}
      , b = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("title")
          , f = c("items")
          , g = c("cancleText")
          , h = "";
        h += " ",
        e && (h += ' <div class="sheet-item sheet-title" value="-1">',
        h += d(e),
        h += "</div> "),
        h += " ";
        for (var i = 0; i < f.length; i++)
            h += ' <div class="sheet-item ',
            h += d(f[i].type || ""),
            h += '" value="',
            h += d(i),
            h += '"> <div class="sheet-item-text" >',
            h += d(f[i].text || f[i]),
            h += "</div> </div> ";
        return h += ' <div class="sheet-item sheet-cancle" value="-1" > <div class="sheet-item-text">',
        h += d(g || "取消"),
        h += "</div> </div> "
    }
    ;
    return a.frame = "TmplInline_actionsheet.frame",
    Tmpl.addTmpl(a.frame, b),
    a
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_peopleList = b()
}(this, function() {
    var a = {}
      , b = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("list")
          , f = c("admin_ext")
          , g = "";
        g += "";
        for (var h = 0; h < e.length; h++) {
            var i = e[h]
              , j = "";
            j = 1 == i.gender ? " male" : " female",
            g += ' <li class="focusList link_person_profile" data-profile-uin="',
            g += d(i.uin),
            g += '"> <a> <img src="',
            g += d(i.pic),
            g += '" class="list-img"> <div class="list-content"> <strong class="name">',
            g += d(i.nick_name),
            g += '</strong> <i class="sex-icon',
            g += d(j),
            g += '"></i> <p class="msg"> 话题:<span style="margin-right: 5px;">',
            g += d(i.threads),
            g += "</span> 部落:<span>",
            g += d(i.follow_bar),
            g += "</span> </p> </div> ",
            f && (g += ' <a class="delete-second-comment ',
            g += d(f ? "isAdmin" : ""),
            g += '" href="javascript:void(0)" title="删除"></a> '),
            g += " </a> </li> "
        }
        return g += " "
    }
    ;
    return a.normal = "TmplInline_peopleList.normal",
    Tmpl.addTmpl(a.normal, b),
    a
}),
function(a, b) {
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
}),
function(a, b) {
    var c = a.Detail;
    c.UI = b(c)
}(this, function(a) {
    function b(b, c) {
        try {
            if (Number(a.postData.user_info.uin) !== Number(a.myuin))
                return
        } catch (d) {}
        b && ($("#lz_level")[0].className = "prevent_default l-level lv" + b,
        $("#lz_level").html("LV." + b + " " + c))
    }
    return {
        updateLevel: b
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Post = b(c)
}(this, function(a) {
    function b() {
        return {
            bid: u,
            pid: v,
            barlevel: 1,
            start: 0,
            num: 10,
            src: 1,
            get_like_ul: 1
        }
    }
    function c(b) {
        b.time && (b.time_str = A(new Date(1e3 * Number(b.time)), "yyyy-M-d h:mm")),
        b._isAdmin = honourHelper.isAdmin(b.admin_ext),
        b._isQQ = a.isQQ,
        b.zan = b.zan || 0,
        b.likes = b.likes || 0,
        b.zan && !b.likes && (b.likes = 1),
        b.bar_pic = imgHandle.getThumbUrl(b.bar_pic),
        b.bar_intro = $.trim(b.bar_intro),
        b.isStarGroup = a.isStarGroup,
        (10055 === u || 10064 === u || 10210 === u) && (b.gameAct = !0,$("#to_join").html("预约"));
        var c = ~~a.getParam("lnum");console.log(c);c > 20 && $("#top_post_wrapper").hide(),
        b.bar_class === z.qunSubscription && (a.isOfficialAccountTribe = b.isOfficialAccountTribe = !0,
        b.public_number && (q = b.public_number.toString()))
    }
    function d(b, c) {
        var d = b.type, g = f;
        a.postData = b,
        a.postType = d,
        a.isLocked = b.locking,
        a.isBest = b.best,
        a.isSticky = b.is_top,
        a.postUin = b.uin,
        c && (g = null ),
        100 === d ? a.Post.Activity.render(b, g) : 101 === d ? a.Post.QunActivity.render(b, g) : 600 === d ? a.Post.PK.render(b, g) : 200 === d || 201 === d ? a.Post.Video.render(b, g) : 900 === d ? a.Post.Music.render(b, g) : 800 === d ? a.Post.Postarray.render(b, g) : a.Post.Normal.render(b, g),
        window.mqq && mqq.dispatchEvent && "yes" === Util.getHash("justPublished") && (e(),
        mqq.dispatchEvent("publich_complete_back", b, {
            domains: ["*"]
        }))
    }
    function e() {
        var a, b, c, d = localStorage.getItem("upgrade_tip_info");
        d && (d = d.toString().split("|"),
        a = d[0],
        b = d[1],
        c = d[2],
        Tip.show("发表成功" + (b > 0 ? "，经验值+" + b : ""), {
            type: "ok"
        }),
        a > 0 && window.UpgradeTip && window.UpgradeTip.show({
            level: a,
            level_title: c
        })),
        localStorage.removeItem("upgrade_tip_info")
    }
    function f(b) {
        n(),
        a.Appreciation.render(b),
        h(b),
        b.total_comment_v2 >= 20 && $("#btnShowInturn").show(),
        "9564870-1436190258" === b.pid ? g({
            gameid: 1000001286,
            bar_name: "全民突击"
        }) : 1 === b.game_activate && g(b),
        101 !== b.type && b.bar_class !== z.qunSubscription && y && y.init("detail", "#js_detail_main"),
        a.Recommend.rock(),
        a.Comment.rock(),
        a.Share.init(b),
        a.Join.init(b),
        a.Events.init(),
        j(),
        imgHandle.setErrImg("http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/default-big.png"),
        imgHandle.lazy($("#detail_top_info")[0], $.os.ios ? "#js_detail_main" : document),
        m(b),
        b.bar_class === z.qunSubscription ? a.report("exp_post", {
            opername: "Grp_public",
            module: "oper",
            ver1: u,
            ver2: Util.queryString("from")
        }) : a.report("visit", {
            obj1: v,
            ver3: a.postType,
            ver4: a.gid
        })
    }
    function g(a) {
        0 !== Number(mqq.QQVersion) && $.getScript("http://imgcache.qq.com/club/gamecenter/widgets/gameBtn.js?_bid=" + u, function() {
            var b;
            a.gameid && (i(a),
            window.gameBtn.init({
                appId: a.gameid,
                btn: document.getElementById("detail_game_btn"),
                src: "buluo",
                downloadTxt: "下载游戏",
                launchTxt: "启动游戏",
                callback: function(a) {
                    var c = $("#detail_game_btn");
                    if (0 === a.result) {
                        switch (a.data.gameStatus) {
                        case 0:
                            b = "game_down",
                            c.text("我也要玩");
                            break;
                        case 1:
                            b = "game_order",
                            c.text("预约游戏");
                            break;
                        case 2:
                            b = "game_start",
                            c.text("再战一局");
                            break;
                        case 5:
                        }
                        a.data.gameName && $(".bar-name").text(a.data.gameName),
                        a.data.icon && $(".bar-logo").attr("src", a.data.icon),
                        b && ($(".detail-game").show(),
                        w("game_exp", {
                            ver1: u,
                            obj1: v
                        }),
                        c.on("tap", function(a) {
                            a.stopPropagation(),
                            w(b, {
                                ver1: u,
                                obj1: v
                            }, !0)
                        }))
                    }
                }
            }))
        })
    }
    function h(b) {
        var c = $(".bottom-bar")
          , d = $(".detail-main")
          , e = []
          , f = 101 === a.postType ? "点赞" : "赞";
        100 === a.postType ? e.push('<a href="javascript:;" class="item icon-qunact-join" id="to_join">报名</a>') : 101 === a.postType ? e.push('<a href="javascript:;" class="item icon-qunact-join" id="to_join_qunact">报名</a>') : (a.postData.bar_class !== z.qunSubscription && e.push('<a href="javascript:;" class="item icon-forward" id="to_forward"></a>'),
        e.push('<a href="javascript:;" class="item icon-reply" id="to_reply"></a>'),
        e.push('<a href="javascript:;" class="item icon-like" id="to_like"></a>'),
        e.push('<a href="javascript:;" class="item icon-join" id="to_join">报名</a>')),
        c.html(e.join(""));
        var g = $(".icon-like,.qunact-i-like")
          , h = $(".icon-reply")
          , i = $(".icon-forward");
        600 === a.postType && g.hide(),
        2 === b.post.status ? c.addClass("has-joined") : 1 === b.is_joined ? c.addClass("has-joined") : c.removeClass("has-joined"),
        b.liveStatus > 0 && c.addClass("has-joined"),
        b.zan ? g.addClass("liked") : b.father_pid && "1" === b.expireStatus && g.addClass("disabled"),
        g.html(b.likes || f),
        h.html(b.total_comment_v2 || "评论"),
        i.html(b.forwards || "转发"),
        c.css("visibility", "visible"),
        100 !== a.postType && 101 !== a.postType || !c.hasClass("has-joined") || d.addClass("no-bottom")
    }
    function i(a) {
        var b = new t({
            comment: "bar_nav",
            renderTmpl: window.TmplInline_detail.bar_nav,
            renderContainer: "#game_download_wrapper",
            data: a
        });
        b.rock(),
        a.bar_name.length > 7 && $(".bar-nav .bar-info").addClass("single")
    }
    function j() {
        var b = document.getElementById("detail_top_info");
        b.addEventListener("load", function(b) {
            var c = $(b.target)
              , d = c.attr("page-lazyload-time")
              , e = c.attr("src");
            /\/200$/.test(e) && e.indexOf("ugc.qpic.cn") > -1 ? (d && (d = Number(d),
            Q.isd(7832, 47, 11, +new Date - d, 25)),
            c.attr("src", e.replace(/\/200/g, 1 === a.network ? "/1000" : "/640"))) : d && Q.isd(7832, 47, 11, +new Date - d, 26)
        }, !0),
        b.addEventListener("error", function(a) {
            $(a.target).remove()
        }, !0),
        $(window).on("hashchange", function() {
            var a = Util.getHash("poi");
            a || !Publish || Publish.isNative || Publish.hidePoiList()
        }),
        $("#people_header_list").on("tap", ".user-avatar", function() {
            var b = $(this)
              , c = b.data("uin")
              , d = 1 === b.data("ban");
            if (a.postData.isOpenAct && 101 === a.postData.type) {
                if (d)
                    return;
                if (a.postData.is_joined)
                    mqq.ui.showProfile({
                        uin: c
                    });
                else if (3 !== a.postData.post.act_status || a.isClose) {
                    var e;
                    1 === a.postData.post.act_status ? e = "报名之后才可以查看小伙伴的资料卡，赶紧报名加入他们吧" : 0 === a.postData.post.act_status ? e = "活动已结束，赶紧加群找小伙伴们聊天吧" : a.isClose && (e = "活动已取消，赶紧加群找小伙伴们聊天吧"),
                    e && Alert.show("提醒", e, {
                        cancel: "取消",
                        confirm: "加群讨论",
                        confirmAtRight: !0,
                        callback: function() {
                            mqq.ui.showProfile({
                                uin: a.postData.post.gid,
                                uinType: 1
                            })
                        }
                    })
                } else
                    Alert.show("提醒", "报名之后才可以查看小伙伴的资料卡，赶紧报名认识他们吧", {
                        cancel: "取消",
                        confirm: "报名",
                        confirmAtRight: !0,
                        callback: function() {
                            var b = {
                                _wv: 3,
                                _bid: 244,
                                activityid: a.postData.post.openact_id,
                                fromlist: 0,
                                hasJoinLimit: a.postData.join_limit,
                                from: Util.queryString("from"),
                                state: Util.queryString("state"),
                                source: "MemberClick",
                                clktime: +new Date
                            }
                              , c = "http://qqweb.qq.com/m/qunactivity/apply.html?" + $.param(b);
                            Util.openUrl(c, !0)
                        }
                    });
                a.openActNewReport("Clk_mber"),
                a.openActReport("Clk_uindata")
            } else
                Util.openUrl("http://xiaoqu.qq.com/mobile/personal.html#_wv=16777219&uin=" + c, 1)
        })
    }
    function k() {
        try {
            var b = JSON.parse(localStorage.getItem("fastContent"));
            if (localStorage.removeItem("fastContent"),
            b) {
                if (b.user_info = b.user,
                b._fromcache = !0,
                a.isRenderFromLocal = 1,
                0 !== b.type)
                    return;
                b.post.content = b.post.content.replace(/<br>/g, "\n"),
                window.fastImgTime = Date.now(),
                d(b, !0),
                h(b),
                Q.isd(7832, 47, 4, +new Date - window.pageStartTime, 21),
                Q.isd(7832, 47, 11, +new Date - window.pageStartTime, 10, null , "all")
            }
        } catch (c) {
            Q.monitor(454455)
        }
    }
    function l(a, b, c) {
        $(".post-error").show().find("span").text(a),
        $("body,html").css("overflow", "hidden"),
        b && Tip.show(a, {
            type: "warning"
        }),
        c && ($(".post-error .emo").addClass("netemo"),
        $(".post-error").append('<div><a href="javascript:window.location.reload();" id="reloadPage">点击重新加载</a></div>'))
    }
    function m(a) {
        if (!x) {
            var b, c, d = a.post.urlInfo || {}, e = 0, f = "", g = "", h = {
                "add-group": 1,
                tribe: 2,
                personal: 3,
                post: 4,
                normal: 5
            };
            for (b in d)
                d.hasOwnProperty(b) && (c = d[b],
                "keyword" === c.type ? (e++,
                f += c.bid || c.content + "/") : g += h[c.type] + "/");
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
        var b, c = Date.now() - window.pageStartTime, d = window.performance, e = 0, f = a.isIOS;
        window.CGI_Preload && (window.CGI_Preload.reportOpenWebView(),
        window.CGI_Preload.reportUsable(),
        window.CGI_Preload.reportUsableWithOpen()),
        Q.huatuo(1486, 1, 3, c, 2),
        d && d.timing && Q.huatuo(1486, 1, 3, d.timing.responseStart - d.timing.navigationStart, 1),
        window.fastHtmlEndTime && (b = window.fastHtmlEndTime - window.pageStartTime,
        Q.isd(7832, 47, 11, b, 23),
        Q.isd(7832, 47, 11, b, 16, null , "all"),
        delete window.fastHtmlEndTime),
        Q.isd(7832, 47, 4, c, 20),
        Q.isd(7832, 47, 11, c, 4, null , "all"),
        e = a.isQQ ? f ? 9 : 11 : f ? 10 : 12,
        Q.isd(7832, 47, 31, c, e)
    }
    function o() {
        ViewPreloader.useViewPreload() ? (Q.monitor(612158),
        ViewPreloader.receive(["_data_detail_content"], function(a) {
            var b, c = a.status, d = a.dataName;
            console.log("预加载数据：", a),
            d && "_data_detail_content" !== d || 1 !== c && (0 === c ? (b = a.preloadData,
            0 === b.retcode ? r.complete(b) : r.error(b)) : (r.rock(),
            Q.monitor(612159),
            4 === c && Q.monitor(516236)))
        })) : r.rock()
    }
    function p() {
        k(),
        o()
    }
    var q, r, s = cgiModel, t = renderModel, u = a.bid, v = a.pid, w = a.report, x = !1, y = window.ad || null , z = window.BARTYPE.BARCLASS, A = function(a, b) {
        var c = {
            "M+": a.getMonth() + 1,
            "d+": a.getDate(),
            "h+": a.getHours(),
            "m+": a.getMinutes(),
            "s+": a.getSeconds()
        };
        /(y+)/.test(b) && (b = b.replace(RegExp.$1, (a.getFullYear() + "").substr(4 - RegExp.$1.length)));
        for (var d in c)
            new RegExp("(" + d + ")").test(b) && (b = b.replace(RegExp.$1, 1 === RegExp.$1.length ? c[d] : ("00" + c[d]).substr(("" + c[d]).length)));
        return b
    }
    ;
    return r = new s({
        comment: "post_main_model",
        cgiName: _domain + "/cgi-bin/bar/post/content",
        param: b,
        complete: function(b) {
            a.isRenderFromLocal = 0,
            b = b.result || {},
            c(b),
            d(b)
        },
        error: function(b) {
            return 10 === b.retcode ? (l("该话题已被删除", !0),
            Q.monitor(630408),
            Util.queryString("qunredirect") && (Q.monitor(2057330),
            window.badjsReport("qunactivity-del-" + u + "-" + v)),
            w("delete_exp"),
            void setTimeout(function() {
                return Boolean(a.isNewWebView) && window.mqq ? void mqq.ui.popBack() : void Util.openUrl(a.base + "barindex.html#bid=" + u + "&scene=detail_delete", !1, 0, !0)
            }, 3e3)) : 101001 === b.retcode ? void l("您没有权限查看该内容") : void l("网络请求错误[" + b.retcode + "]", !0, !0)
        }
    }),
    $.getScript = function(a, b) {
        var c = document.createElement("script");
        c.async = "async",
        c.src = a,
        b && (c.onload = b),
        document.getElementsByTagName("head")[0].appendChild(c)
    }
    ,
    a.bindNormalEvents = function(b, c) {
        $("#js_detail_delete_post").tap(function() {
            var b, c = {
                bid: u,
                pid: v
            };
            MediaPlayer.stop({
                type: "audio"
            }),
            b = {
                url: "/cgi-bin/bar/post/delete",
                type: "POST",
                param: c,
                ssoCmd: "del_post",
                succ: function() {
                    if (w("Clk_delpub"),
                    mqq.dispatchEvent("delete_post", c),
                    Boolean(a.isNewWebView) && window.mqq && "barindex" === a.source)
                        return void mqq.ui.popBack();
                    var b = document.referrer;
                    b.indexOf("barindex.html") > -1 ? history.back() : Util.openUrl(a.base + "barindex.html#bid=" + u + "&scene=detail_delete", !1, 0, !0)
                },
                err: function(a) {
                    var b = "删除失败[" + a.retcode + "]";
                    101e3 === a.retcode ? b = "此为管理员发表，无法删除" : 4004 === a.retcode && (b = "该话题已被推荐为热门，请于2天后删除"),
                    Tip.show(b, {
                        type: "warning"
                    })
                }
            },
            $(this).hasClass("isAdmin") ? window.setTimeout(function() {
                ActionSheet.show({
                    items: ["同时将该用户拉黑", "只删除不拉黑"],
                    onItemClick: function(a) {
                        0 === a ? c.black = 1 : c.black = 0,
                        DB.cgiHttp({
                            param: {
                                bid: u,
                                pid: v,
                                black: c.black
                            },
                            url: "/cgi-bin/bar/post/delete",
                            ssoCmd: "del_post",
                            succ: function() {
                                Tip.show("删除成功", {
                                    type: "ok"
                                }),
                                setTimeout(function() {
                                    mqq.ui.popBack()
                                }, 500)
                            },
                            err: function(a) {
                                var b = "删除失败，请稍后重试。";
                                4e3 === a.retcode && (b = "该话题受保护 无法删除"),
                                Tip.show(b, {
                                    type: "warning"
                                })
                            }
                        })
                    }
                })
            }, 0) : (DB.cgiHttp(b),
            w("del_own_post", {
                ver3: a.postData.time,
                ver4: {
                    forwards: a.postData.forwards,
                    replys: a.postData.total_comment_v2,
                    likes: a.postData.likes
                }
            }))
        }),
        $("#js_detail_report").on("tap", function() {
            return w("Clk_report"),
            isNaN(Number(b)) || Number(b) <= 0 ? void Alert.show("", "楼主帐号异常，无法举报", {
                confirm: "我知道了"
            }) : void window.jubaokit.init({
                barId: u,
                pid: v,
                eviluin: b,
                impeachuin: a.myuin
            })
        }),
        $(document).on("tap", ".icon-play", function() {
            Tip.show("视频上传及转码中，暂不能播放", {
                type: "warning"
            })
        }),
        $("#js_detail_apply_post").on("tap", function() {
            c ? ApplyAdmin.doApply(u, 5, c) : mqq.ui.showDialog({
                title: "提示",
                text: "部落酋长数已达到上限，暂时不支持申请酋长",
                needOkBtn: !0,
                needCancelBtn: !1
            })
        }),
        $(".detail-from").on("tap", function() {
            q ? mqq.ui.showOfficialAccountProfile({
                uin: q
            }) : (w("Clk_topentry", {
                obj1: v
            }),
            "barindex" === a.source && "0" !== mqq.QQVersion ? mqq.ui.popBack() : Util.openUrl(a.base + "barindex.html#bid=" + u + "&scene=detail_titleNav", !0))
        }),
        $("#detail_body").on("tap", ".honour", function(a) {
            var b = $(this).data("url")
              , c = $(this).data("report-action");
            b && (a.stopImmediatePropagation(),
            c && w("Clk_tag_" + c),
            b && mqq.ui.openUrl({
                target: 1,
                url: b
            }))
        })
    }
    ,
    {
        init: p
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Post.Normal = b(c, window.TmplInline_detail_header, window.TmplInline_detail)
}(this, function(a, b, c) {
    function d(a) {
        var b, c = document.body.clientWidth - 20, d = .75 * c;
        if (a.public_account_post && a.public_account_post.content)
            try {
                b = a.public_account_post,
                b.content = b.content.replace(/<iframe (.*?)height\="(.*?)"(.*?)<\/iframe>/g, '<iframe $1height="' + d + '"$3</iframe>').replace(/<iframe (.*?)width\="(.*?)"(.*?)<\/iframe>/g, '<iframe $1width="' + c + '"$3</iframe>').replace(/<iframe (.*?) src\="http:\/\/v.qq.com\/iframe\/player\.html(.*?)width\=([\d\.]*?)(["&])(.*?)<\/iframe>/g, '<iframe $1 src="http://v.qq.com/iframe/player.html$2width=' + c + "$4$5</iframe>").replace(/<iframe (.*?) src\="http:\/\/v.qq.com\/iframe\/player\.html(.*?)height\=([\d\.]*?)(["&])(.*?)<\/iframe>/g, '<iframe $1 src="http://v.qq.com/iframe/player.html$2height=' + d + "$4$5</iframe>").replace(/<iframe (.*?)style\="(.*?)height(\s*?):(.*?)px(.*?)"(.*?)<\/iframe>/g, '<iframe $1style="$2height:' + d + 'px$5"$6</iframe>').replace(/\?tp\=webp/g, "").replace(/(<img[^>]*>)/g, '<div class="img-box">$1</div>')
            } catch (e) {}
        loadjs.loadCss(window.loadCssConfig.wechatCss)
    }
    function e(b) {
        return l ? ($(".video-preview .preview-box").html(l),
        $(".video-preview").show(),
        $("#detail_top_info .post-video").hide(),
        void (a.isUploading = !0)) : 302 === k ? void MediaPlayer.initVPlayerMuti("#detail_top_info") : void (b.post.video_list && b.post.video_list.length && (b.vstat && 2 === b.vstat ? (MediaPlayer.initVPlayerMuti("#detail_top_info"),
        i(b)) : (a.isUploading = !0,
        h(b))))
    }
    function f() {
        var b = ActionButton.getUploadVideo(p, q);
        if (console.log("upload info", b),
        b) {
            a.isUploading = !0;
            var c = b.post.image1
              , d = '<img src="' + c + '" />'
              , e = $(".video-preview .preview-box");
            g(c),
            e.length ? (e.html(d),
            $(".video-preview").show(),
            $("#detail_top_info .post-video").hide()) : l = d,
            Refresh.freeze()
        } else
            console.log("没有正在上传的视频")
    }
    function g(a) {
        a && mqq.data.writeH5Data({
            callid: q,
            path: "/buluo/video",
            key: "" + p + q,
            data: a
        })
    }
    function h(a) {
        mqq.data.readH5Data({
            callid: q,
            path: "/buluo/video",
            key: "" + p + q
        }, function(b) {
            var c = "http://shp.qpic.cn/qqvideo/0/" + a.post.video_list[0].vid + "/400";
            0 === b.ret && b.response && b.response.callid === q && (c = b.response.data),
            $(".video-preview .preview-box").html('<img src="' + c + '" >'),
            $(".video-preview").show().siblings(".post-video").hide()
        })
    }
    function i(a) {
        a.isposter && mqq.data.deleteH5Data({
            callid: q,
            path: "/buluo/video",
            key: "" + p + q
        }, function() {})
    }
    function j(b, c) {
        k = a.postType,
        $(document.body).addClass("topic-detail"),
        m.data = b,
        m.rock(),
        n.data = b,
        n.rock(),
        c && c(b)
    }
    var k, l, m, n, o = renderModel, p = a.bid, q = a.pid, r = a.report, s = 0, t = 0;
    return m = new o({
        comment: "post_header_model",
        renderTmpl: b.top_detail_header,
        renderContainer: "#detail_top_info_header",
        renderTool: honourHelper,
        complete: function() {
            var a = $(".detail-from").width();
            $(".title-bottom-wrapper .name-wrap").css("padding-right", a),
            r("exp_nav_top")
        }
    }),
    n = new o({
        comment: "post_model",
        renderTmpl: c.top_detail,
        renderContainer: "#detail_top_info",
        renderTool: honourHelper,
        processData: function(a) {
            if (a.post.pic_list = a.post.pic_list || [],
            s = a.uin,
            301 === k ? d(a) : 300 === k && 1 === a.post.rss && (a.posts && a.posts[1] ? a.contentList = window.getRSSContent(a.posts[1].content.trim()) : (a.contentList = [],
            console.error('缺少"posts"字段'))),
            1 === ~~a.post.is_recruit) {
                var b = a.big_admin_num
                  , c = a.sml_admin_num;
                3 > b && (t = 1),
                7 > c && (t = 2),
                3 > b && 7 > c && (t = 3),
                0 === b && (t = 11),
                "undefined" == typeof b && (t = 3)
            }
        },
        events: function() {},
        complete: function(b) {
            301 === k && $(".img-box").css("background", "none"),
            a.isRenderFromLocal || (e(b),
            a.bindNormalEvents(s, t),
            b.gname && a.isQQ && r("exp_grpsign"))
        }
    }),
    {
        render: j,
        triggerUploading: f
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Post.Activity = b(c, window.TmplInline_detail)
}(this, function(a, b) {
    function c() {
        DB.cgiHttp({
            url: "http://qqweb.qq.com/cgi-bin/qqactivity/get_uin_grp_info",
            param: {
                gc: k.gid
            },
            succ: function(a) {
                k.role = a.role
            }
        })
    }
    function d() {
        if (mqq && mqq.ui && !a.isInYyb) {
            var b = {
                isDetail: !0,
                iconID: "3",
                type: "more"
            };
            ActionButton.build(b, function() {
                var a, b = [], c = [];
                b.push("举报"),
                c.push(f),
                k.is_joined && (b.push("取消报名"),
                c.push(e)),
                a = function(a) {
                    switch (a) {
                    case 0:
                        c[0]();
                        break;
                    case 1:
                        c[1]()
                    }
                }
                ,
                ActionSheet.show({
                    items: b,
                    onItemClick: a
                })
            })
        }
    }
    function e() {
        ActionSheet.hide(!0),
        a.Join.quitAct(),
        v("Clk_quit")
    }
    function f() {
        var b = r.uin;
        return isNaN(Number(b)) || Number(b) <= 0 ? void Alert.show("", "楼主帐号异常，无法举报", {
            confirm: "我知道了"
        }) : (u("Clk_report"),
        void Util.openUrl("http://buluo.qq.com/mobile/report.html#bid=" + o + "&pid=" + p + "&eviluin=" + b + "&impeachuin=" + a.myuin, !0))
    }
    function g() {
        var a = {
            url: "http://qqweb.qq.com/cgi-bin/qqactivity/get_activity_member_list",
            param: {
                type: 1,
                id: k.post.openact_id,
                from: 0,
                number: 30,
                flag: 1
            },
            succ: function(a) {
                0 === a.ec && (k.joined_ul = {
                    uinnum: a.count || a.list.length,
                    uins: a.list,
                    isAtvCGI: !0
                }),
                m.data = k,
                m.rock()
            },
            err: function() {
                m.data = k,
                m.rock()
            }
        };
        DB.cgiHttp(a)
    }
    function h() {
        a.isOpenAct = !0,
        k.isOpenAct = !0,
        k.fromOpenAct = "openact" === a.from,
        k.post.pic_list[0].url = k.post.pic_list[0].url.replace(/\/0$/, "/160"),
        $(document.body).addClass("openact"),
        c();
        var b = a.getParam("openact");
        v(b ? "exp_" + b.split("_")[0] : "exp_tribe", {
            module: "detail_open"
        }),
        v("exp")
    }
    function i() {
        var b = k.post;
        b.start && b.end && (b.time = t(b.start, b.end)),
        k.hasQGroup = !!k.ginfo && 0 !== k.gid,
        b.purchase_link && (a.purchaseLink = $.str.decodeHtml(k.post.purchase_link),
        $("#to_join").html("购票")),
        a.setOpenActStatus(k),
        b.pic_list[0].url = imgHandle.getThumbUrl(b.pic_list[0].url)
    }
    function j(b, c) {
        r = a.postData,
        q = a.postType,
        document.title = "活动",
        mqq.ui.refreshTitle(),
        $(document.body).addClass("topic-activity"),
        k = b,
        l = c,
        "openact" === k.post.from ? (s = 1,
        g()) : (m.data = k,
        m.rock()),
        k.post.purchase_link && $("#to_join").html("购票"),
        d()
    }
    var k, l, m, n = renderModel, o = a.bid, p = a.pid, q = 0, r = {}, s = 0, t = a.formatActDate, u = a.report, v = a.openActReport;
    return m = new n({
        comment: "post_activity_model",
        renderTmpl: b.top_activity,
        renderContainer: "#detail_top_info",
        renderTool: honourHelper,
        processData: function() {
            s && h(k),
            i(k)
        },
        events: function() {
            $("body").on("tap", ".purchase_button", function() {
                var a = $(this).data("src");
                a && Util.openUrl(a, 1)
            }),
            $(".people-joined-title").tap(a.Join.showList),
            $("#js-qunact-showmore").tap(function() {
                var a = $("#more_introduce");
                a.hasClass("show") ? (a.removeClass("show"),
                $(this).text("展开")) : (a.addClass("show"),
                $(this).text("收起"))
            })
        },
        complete: function() {
            a.Join.showListInPost(k),
            $("#js_act_qun").on("tap", function() {
                var b = $(this).attr("code");
                mqq.ui.showProfile({
                    uin: b,
                    uinType: 1
                }),
                u("Clk_grpsign"),
                a.openActReport("Clk_grpname")
            });
            var b;
            $("#js-qunact-comment").on("tap", function() {
                b = a.Join.joinGroupBySSO(b)
            }),
            l && l(k),
            $(".bottom-bar").addClass("qunact-bottom-bar"),
            $(".qunact-intro").height() <= $("#more_introduce").height() && ($(".qunact-show-more").hide(),
            $("#more_introduce").addClass("show"))
        }
    }),
    {
        render: j
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Post.QunActivity = b(c, window.TmplInline_detail)
}(this, function(a, b) {
    function c(a) {
        var b = window.location.search.match(new RegExp("(?:\\?|&)" + a + "=([^&]*)(&|$)"));
        return b ? decodeURIComponent(b[1]) : ""
    }
    function d() {
        var a = c("from")
          , b = c("share")
          , d = {
            ver2: "1",
            ver4: a,
            module: "detail"
        };
        "qrcode" === a ? (Q.monitorMap("qrcodeSuc"),
        d.module = "transfer_page",
        D("transfer_page", "jump2ac")) : a || (d.ver4 = "buluo"),
        b && (d.ver5 = b),
        D("exp", d),
        Q.monitor(2053044),
        Q.monitor(2054379),
        Q.monitor(435885)
    }
    function e() {
        DB.cgiHttp({
            url: E + "/cgi-bin/qqactivity/send_activity_msg",
            param: {
                cmd: "sendMsg",
                id: v.post.openact_id,
                gc: v.post.gid
            },
            succ: function(a) {
                f(a),
                D("Clk_remind")
            },
            err: function(a) {
                f(a)
            }
        })
    }
    function f(a) {
        switch (a.ec) {
        case 0:
            Tip.show("提醒成功", {
                type: "ok"
            });
            break;
        case 1001:
            Tip.show("活动已结束", {
                type: "warning"
            });
            break;
        case 1e3:
            Tip.show("你没有权限", {
                type: "warning"
            });
            break;
        case 1002:
            Tip.show("十五分钟内你已经提醒过了", {
                type: "warning"
            });
            break;
        default:
            Tip.show("网络异常", {
                type: "warning"
            })
        }
    }
    function g() {
        if (mqq && mqq.ui && !a.isInYyb) {
            var b = {
                isDetail: !0,
                iconID: "3",
                type: "more"
            };
            ActionButton.build(b, function() {
                var b, c = [], d = [];
                c.push("举报"),
                d.push(m),
                a.isOpenActHost ? (a.isClose || a.isDelete || 0 === v.post.act_status || 1 === v.post.act_status || (c.push("取消活动"),
                d.push(k)),
                a.isClose || a.isDelete || 0 === v.post.act_status || 1 === v.post.act_status || (c.push("编辑活动"),
                d.push(i))) : v.is_joined && 0 !== v.post.act_status && 1 !== v.post.act_status && !a.isDelete && (c.push("取消报名"),
                d.push(h)),
                b = function(a) {
                    switch (a) {
                    case 0:
                        d[0]();
                        break;
                    case 1:
                        d[1]();
                        break;
                    case 2:
                        d[2]()
                    }
                }
                ,
                ActionSheet.show({
                    items: c,
                    onItemClick: b
                })
            })
        }
    }
    function h() {
        ActionSheet.hide(!0),
        j()
    }
    function i() {
        var a = v.post.grpact_start
          , b = new Date;
        2 > (a - b) / 864e5 ? Tip.show("活动开始前2天内无法修改活动", {
            type: "warning"
        }) : Util.openUrl(E + "/m/qunactivity/form.html?type=modify&atvid=" + v.post.openact_id + "&open=1&_wv=7&_bid=244", !0)
    }
    function j() {
        Alert.show("取消报名", "取消报名后将不再收到活动开始通知，确定要取消报名吗？", {
            confirm: "确定",
            cancel: "取消",
            callback: function() {
                DB.cgiHttp({
                    url: E + "/cgi-bin/qqactivity/cancel_activity",
                    type: "POST",
                    param: {
                        type: 1,
                        id: v.post.openact_id
                    },
                    succ: function() {
                        $(".bottom-bar").removeClass("has-joined");
                        var b = ""
                          , c = $("#peopleNum")
                          , d = $("#js-peopleNum-cover")
                          , e = $("#people_header_list .u" + a.myuin)
                          , f = parseInt(c.html()) - 1;
                        Tip.show("取消" + b + "成功！", {
                            type: "warning"
                        }),
                        v.is_joined = 0,
                        a.setOpenActStatus(v),
                        e.length && e.remove(),
                        f = 0 > f ? 0 : f,
                        c.html(f),
                        d.html(f),
                        !f && $("#js_act_people").hide(),
                        a.Join.syncActivity("quit")
                    },
                    err: function() {}
                })
            }
        })
    }
    function k() {
        DB.cgiHttp({
            url: E + "/cgi-bin/qqactivity/close_activity",
            param: {
                type: 1,
                id: v.post.openact_id,
                gc: v.post.gid
            },
            succ: function(a) {
                0 === a.ec ? (Tip.show("取消活动成功", {
                    type: "ok"
                }),
                p("qunact-i-cancel")) : Tip.show("取消失败，请稍后再试", {
                    type: "warning"
                })
            },
            err: function() {
                Tip.show("取消失败，请稍后再试", {
                    type: "warning"
                })
            }
        })
    }
    function l(a, b) {
        var c = Math.pow(2, b - 1);
        return (a & c) === c ? 1 : 0
    }
    function m() {
        C("Clk_report"),
        D("Clk_report"),
        Util.openUrl(E + "/m/qunactivity/corruption.html?_wv=1027&_bid=244&atvid=" + v.post.openact_id, !0)
    }
    function n() {
        var b = $("#to_join_qunact")
          , c = $(".bottom-bar")
          , d = $(".detail-main");
        a.isDelete ? (c.addClass("has-joined"),
        d.addClass("no-bottom")) : a.isClose || 0 === v.post.act_status ? (c.removeClass("has-joined"),
        d.removeClass("no-bottom"),
        b.html("重新发布")) : (c.removeClass("has-joined"),
        d.removeClass("no-bottom"),
        b.html("提醒报名"))
    }
    function o() {
        var b = $(".bottom-bar")
          , c = $(".detail-main")
          , d = a.myuin;
        b.addClass("has-joined"),
        c.addClass("no-bottom"),
        v.is_joined = 1,
        a.setOpenActStatus(v, "qunact-i-registed");
        var e = $("#peopleNum")
          , f = $("#js-peopleNum-cover")
          , g = $("<img />")
          , h = parseInt(e.html() || 0) + 1;
        g.addClass("u" + d).addClass("user-avatar").attr("data-uin", d).attr("src", "http://q.qlogo.cn/g?b=qq&nk=0" + d + "&s=100").prependTo("#people_header_list"),
        e.html(h),
        f.html(h)
    }
    function p(b) {
        r(),
        a.setOpenActStatus(v, b)
    }
    function q() {
        var b = a.isOpenActHost ? "重新发布" : "更多精彩活动";
        $(".post-error").show().find("span").text("活动已删除，请选择其它活动"),
        $(".post-error").append('<p class="qunact-delete"><a href="javascript:;" class="item qunact-jump-btn" id="to_jump_qunact">' + b + "</a></p>"),
        $("#to_jump_qunact").on("tap", function() {
            var b, c;
            a.isOpenActHost ? (b = {
                _wv: 1027,
                _bid: 244,
                type: "relgroup",
                open: 1,
                from: Util.queryString("from") || "buluo",
                source: "RePublishClick",
                clktime: +new Date
            },
            c = E + "/m/qunactivity/form.html?" + $.param(b),
            Util.openUrl(c, !0)) : (b = {
                from: Util.queryString("from") || "buluo",
                clktime: +new Date
            },
            c = E + "/m/qunactivity/index.html?" + $.param(b),
            Util.openUrl(c, !0))
        }),
        mqq && mqq.ui && mqq.ui.setTitleButtons({
            right: {}
        })
    }
    function r() {
        var a = $(".bottom-bar")
          , b = $(".detail-main");
        a.addClass("has-joined"),
        b.addClass("no-bottom")
    }
    function s(a, b) {
        b = b || {};
        var c = 0
          , d = b.imgMaxCount || 3
          , e = /\[img:(\d+)\|(\d+)\|([^\]]+)\]/gi
          , f = /(https?:\/\/)?([\w\-]+(\.[\w\-]+)*\.(cn|com|net|me|io|org|hk|jp))(\/[\w\-+%.]+)*\/?(\??[\w\-.+&=#%]*)?/gi;
        return a = a.replace(e, function(a) {
            var e, g, h = RegExp.$1, i = RegExp.$2, j = RegExp.$3;
            if (j = j.replace(/\/auto$/, "/0"),
            c >= d)
                return a;
            if (e = j.match(new RegExp(f.source,"i")),
            g = b.maxWidth || document.body.clientWidth,
            e && "ugc.qpic.cn" === e[2]) {
                var k = parseInt(i / 2)
                  , l = parseInt(h / 2);
                return l > g && (k = parseInt(k * (g / l)),
                l = g),
                c++,
                ["<p><img ", " src=" + j, " data-size=" + h + "|" + i, " /></p>"].join("")
            }
            return a
        })
    }
    function t() {
        var b, c = v.post;
        a.isOpenActHost = v.user_info.uin + "" == Login.getUin() + "",
        v.isOpenAct = 1,
        (c.grpact_start && c.grpact_end || c.start && c.end) && (c.grpact_start = c.start = 1e3 * (c.grpact_start || c.start),
        c.grpact_end = c.end = 1e3 * (c.grpact_end || c.end),
        c.time = B(c.grpact_start, c.grpact_end)),
        c.richText ? (c.richText = s(c.richText),
        c.content = c.richText) : c.richText = c.content,
        v.joined_ul = {},
        v.joined_list ? v.joined_ul.uins = v.joined_list : v.joined_ul.uins = [],
        v.joined_ul.uinnum = v.post.openact_enroll,
        v.joined_ul.isAtvCGI = !0,
        v.hasQGroup = !!c.gid,
        v.hasCreator = !!c.create_uin,
        a.isDelete = l(v.post.openact_flag, 5),
        a.isClose = l(v.post.openact_flag, 6),
        a.isDelete ? q() : a.isClose ? p("qunact-i-cancel") : a.setOpenActStatus(v, b),
        c.cover = imgHandle.getThumbUrl(c.cover),
        d()
    }
    function u(b, c) {
        A = a.postData,
        z = a.postType,
        document.title = "活动",
        mqq.ui.refreshTitle(),
        $(document.body).addClass("topic-activity"),
        v = b,
        w = c,
        x.data = v,
        x.rock()
    }
    var v, w, x, y = renderModel, z = 0, A = {}, B = a.formatActDate, C = a.report, D = a.openActNewReport, E = "http://qqweb.qq.com", F = {};
    return F.isFrom365Calendar = function() {
        return "365calendar" === c("from").toLowerCase()
    }
    ,
    F.backTo365 = function() {
        var a = document.createElement("iframe");
        a.style.cssText = "display:none;width:0px;height:0px;",
        document.body.appendChild(a),
        a.src = "coco://365rili.com"
    }
    ,
    F.addWebViewEventFor365 = function() {
        mqq.addEventListener("applySuccessFor365", function(b) {
            b.success && b.atvId === a.postData.post.openact_id && mqq.ui.setTitleButtons({
                left: {
                    title: "返回"
                }
            })
        })
    }
    ,
    x = new y({
        comment: "post_activity_model",
        renderTmpl: b.top_qunactivity,
        renderContainer: "#detail_top_info",
        renderTool: honourHelper,
        processData: function() {
            t(v)
        },
        events: function() {
            $(".people-joined-title").tap(function() {
                if (!a.isWX) {
                    var b = {
                        _wv: 3,
                        _bid: 4,
                        activityid: v.post.openact_id
                    }
                      , c = E + "/m/qunactivity/joinlist.html?" + $.param(b);
                    Util.openUrl(c, !0),
                    D("Clk_member_list")
                }
            }),
            $(document).on("tap", "#to_join_qunact", function() {
                var b, c;
                a.isOpenActHost ? a.isClose || 0 === v.post.act_status ? (b = {
                    _wv: 1027,
                    _bid: 244,
                    type: "relgroup",
                    open: 1,
                    from: Util.queryString("from") || "buluo",
                    source: "RePublishClick",
                    clktime: +new Date
                },
                c = E + "/m/qunactivity/form.html?" + $.param(b),
                Util.openUrl(c, !0)) : e() : (Q.monitor(2057682),
                b = {
                    _wv: 3,
                    _bid: 244,
                    activityid: v.post.openact_id,
                    fromlist: 0,
                    from: Util.queryString("from") || "buluo",
                    state: Util.queryString("state") || "",
                    source: "JoinClick",
                    createuin: v.post.create_uin,
                    clktime: +new Date
                },
                v.join_limit && (b.hasJoinLimit = v.join_limit),
                c = E + "/m/qunactivity/apply.html?" + $.param(b),
                Util.openUrl(c, !0),
                D("Clk_join"))
            }),
            $("#js-qunact-showmore").tap(function() {
                var a = $("#more_introduce");
                a.hasClass("show") ? (a.removeClass("show"),
                $(this).text("展开")) : (a.addClass("show"),
                $(this).text("收起"))
            }),
            $("#js_act_qun").on("tap", function() {
                var a = $(this).attr("code");
                mqq.ui.showProfile({
                    uin: a
                }),
                D("exp_data_host")
            });
            var b;
            $("#js-qunact-comment").on("tap", function() {
                DB.cgiHttp({
                    url: "http://qqweb.qq.com/cgi-bin/activity/interface/group_pay_check",
                    param: {
                        act_id: v.post.openact_id
                    },
                    succ: function(c) {
                        0 === c.ec ? 0 !== c.pay_check || 1 !== c.group_option && 2 !== c.group_option ? 0 === c.pay_check && 3 === c.group_option ? mqq.ui.showTips({
                            text: "该群无法加入"
                        }) : mqq.ui.showProfile({
                            uin: v.post.gid,
                            uinType: 1
                        }) : b = a.Join.joinGroupBySSO(b) : console.log("报错", c.ec)
                    },
                    err: function() {
                        console.log("报错")
                    }
                })
            }),
            $("#js-qunact-addr").on("tap", function() {
                if (v.post.lat && v.post.lon) {
                    var a = ["coord:" + v.post.lat / 1e6 + "," + v.post.lon / 1e6, "title:" + (v.post.title || " "), "addr:" + (v.post.addr || " ")].join(";")
                      , b = "http://3gimg.qq.com/lightmap/v1/marker/?" + $.param({
                        marker: a,
                        referer: "qunhuodong"
                    });
                    Util.openUrl(b, !0),
                    D("Clk_address")
                }
            }),
            mqq.addEventListener("atv_apply_success", function(b) {
                b.atvId === v.post.openact_id && (o(),
                a.Join.syncActivity("join"),
                D("join_suc"))
            })
        },
        complete: function() {
            a.Join.showListInPost(v),
            w && w(v),
            a.isOpenActHost && n(),
            g(),
            a.isClose && !a.isOpenActHost && r(),
            (a.isClose || a.isDelete || 0 === v.post.act_status) && $("#js-qunact-forward").hide(),
            $(".bottom-bar").addClass("qunact-bottom-bar"),
            $(".qunact-intro").height() <= $("#more_introduce").height() && ($(".qunact-show-more").hide(),
            $("#more_introduce").addClass("show")),
            $("#js_jump_barindex").show(),
            $("#qunact-reply").before($("#js_jump_barindex")),
            $("#js_jump_barindex  .qunact-barindex").tap(function(a) {
                D("Clk_bl"),
                Util.openUrl("http://buluo.qq.com/mobile/barindex.html?bid=" + v.bid, !0),
                a.preventDefault()
            }),
            0 !== v.post.act_status || a.isOpenActHost ? 1 === v.post.act_status && r() : r(),
            "1" === c("qunredirect") && mqq.ui.setTitleButtons({
                left: {
                    title: "返回",
                    callback: function() {
                        mqq.ui.popBack()
                    }
                }
            }),
            F.isFrom365Calendar() && (mqq.ui.setTitleButtons({
                left: {
                    title: "返回",
                    callback: function() {
                        F.backTo365(),
                        mqq.ui.popBack()
                    }
                }
            }),
            F.addWebViewEventFor365())
        }
    }),
    {
        render: u
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Post.Video = b(c)
}(this, function(a) {
    function b(b, c) {
        var d = new Date
          , e = +d
          , f = {
            wording: "",
            status: b > e ? 0 : e > c ? 2 : 1
        };
        switch (a.postData.liveStatus = f.status,
        f.status) {
        case 0:
            var g = new Date(b);
            864e5 > b - e && d.getDate() === g.getDate() ? f.wording = (g.getHours() < 10 ? "0" : "") + g.getHours() + ":" + (g.getMinutes() < 10 ? "0" : "") + g.getMinutes() : f.wording = a.formatDate(b);
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
        200 === d ? (document.title = "直播",
        mqq.ui.refreshTitle()) : $(document.body).addClass("topic-video"),
        e.data = b,
        e.rock(),
        f.data = b,
        f.rock(),
        c && c(b)
    }
    var d, e, f, g = renderModel, h = a.report, i = a.bid, j = a.pid;
    return e = new g({
        comment: "post_header_model",
        renderTmpl: window.TmplInline_detail_header.top_detail_header,
        renderContainer: "#detail_top_info_header",
        renderTool: honourHelper,
        complete: function() {
            var b = $(".detail-from").width();
            $(".title-bottom-wrapper .name-wrap").css("padding-right", b),
            $(".detail-from").on("tap", function() {
                h("Clk_topentry", {
                    obj1: j
                }),
                "barindex" === a.source && "0" !== mqq.QQVersion ? mqq.ui.popBack() : Util.openUrl(a.base + "barindex.html#bid=" + i + "&scene=detail_titleNav", !0)
            }),
            h("exp_nav_top")
        }
    }),
    f = new g({
        comment: "post_video_model",
        renderTmpl: window.TmplInline_detail.top_live,
        renderContainer: "#detail_top_info",
        processData: function(a) {
            a.post.image2 || (a.post.image2 = a.post.image1),
            a.post.image1 = imgHandle.getThumbUrl(a.post.image1),
            a.post.image2 = imgHandle.getThumbUrl(a.post.image2),
            201 === d && (a.isVideo = !0)
        },
        events: function() {
            $("#people_joined_title").tap(a.Join.showList),
            a.bindNormalEvents(a.postUin)
        },
        complete: function(c) {
            var e = ""
              , f = 1;
            if (c.post.channel ? (e = "" + c.post.channel,
            f = 1) : (f = 2,
            e = "" + c.post.vid),
            MediaPlayer.initVPlayer(e, c.post.image1, f),
            200 === d) {
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
}(this, function(a) {
    function b(a, b) {
        c.data = a,
        c.rock(),
        b && b(a)
    }
    var c, d = {}, e = navigator.userAgent, f = e.match(/QQMUSIC\/(\d[\.\d]*)/i), g = a.isWX, h = renderModel;
    return f && ($.browser.music = !0,
    f[1] && ($.browser.version = parseFloat(f[1].replace("0", ".")))),
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
            $("#js_download_music").on("tap", function() {
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
            window.WeixinJSBridge ? a() : document.addEventListener("WeixinJSBridgeReady", function() {
                a()
            })
        }
        ,
        a.checkInstall = function(b) {
            if (b = b || function() {}
            ,
            g)
                a.weixinReady(function() {
                    WeixinJSBridge.invoke("getInstallState", {
                        packageName: "com.tencent.qqmusic",
                        packageUrl: "qqmusic://"
                    }, function(a) {
                        var c = a.err_msg;
                        b(c.indexOf("get_install_state:yes") > -1 ? 1 : -1)
                    })
                });
            else if ("undefined" != typeof mqq)
                if (mqq.app && mqq.app.isAppInstalled) {
                    var c = "com.tencent.qqmusic";
                    $.os.ios && (c = "qqmusic"),
                    mqq.app.isAppInstalled(c, function(a) {
                        b(a ? 1 : -1)
                    })
                } else
                    b(0);
            else
                b($.browser.music ? 1 : 0)
        }
    }(d),
    function(a) {
        function b(a) {
            var b;
            if ($.os.ios)
                b = +new Date,
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
            }, 1500)
        }
        function c() {
            $.os.ios ? location.href = a.openMusic.downloadUrl.ios : "undefined" != typeof mqq && mqq.compare && mqq.compare("4.5") >= 0 ? mqq.app.downloadApp({
                appid: "1101079856",
                url: a.openMusic.downloadUrl.android,
                packageName: "com.tencent.qqmusic",
                actionCode: "2",
                via: "ANDROIDQQ.QQMUSIC.GENE",
                appName: "QQMUSIC"
            }, function() {}) : location.href = a.openMusic.downloadUrl.android
        }
        a.openMusic = function(d, e) {
            e && (a.openMusic.downloadUrl.android = "http://misc.wcd.qq.com/app?packageName=com.tencent.qqmusic&channelId=" + e);
            var f = $.param(d || {})
              , g = "androidqqmusic://form=webpage&" + f;
            $.os.ios && (g = "qqmusic://qq.com?form=webpage&" + f),
            a.checkInstall(function(a) {
                1 === a ? location.href = g : -1 === a ? c() : b(g)
            })
        }
        ,
        a.openMusic.downloadUrl = {
            ios: "itms-apps://itunes.apple.com/cn/app/qq-yin-le/id414603431?mt=8",
            android: "http://misc.wcd.qq.com/app?packageName=com.tencent.qqmusic&channelId=10000609"
        },
        a.download = c
    }(d),
    {
        render: b
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Post.PK = b(c, window.TmplInline_detail)
}(this, function(a, b) {
    function c(b) {
        return new o({
            cgiName: "/cgi-bin/bar/dailyact/vote",
            ssoCmd: "vote",
            param: {
                bid: p,
                pid: q,
                op: b
            },
            complete: function(c) {
                var d = c.result.errno
                  , g = c.result.add_credits;
                0 === d ? (1 === b ? r("Clk_optiona", {
                    obj1: q
                }) : 2 === b && r("Clk_optionb", {
                    obj1: q
                }),
                f(j),
                e(),
                c.result.new_level > 0 && (window.UpgradeTip && window.UpgradeTip.show({
                    level: c.result.new_level,
                    level_title: c.result.new_title
                }),
                a.UI.updateLevel(c.result.new_level, c.result.new_title)),
                mqq.dispatchEvent("event_tribe_credit_change", $.extend({
                    bid: p
                }, c.result.level), {
                    domains: ["*.qq.com"]
                }),
                g > 0 && Tip.show("投票成功" + (g > 0 ? "，经验值+" + g : ""), {
                    type: "warning"
                })) : Tip.show("投票失败", {
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
            b.hasClass("unprogress") || b.hasClass("end") || b.hasClass("voted") || (j = b,
            r("Clk_vote", {
                ver3: a
            }),
            c(a + 1).rock())
        }
        k.on("tap", function() {
            a(0, $(this))
        }),
        l.on("tap", function() {
            a(1, $(this))
        })
    }
    function e() {
        k.off("tap"),
        l.off("tap")
    }
    function f(a) {
        var b, c = a.attr("id"), d = a.closest(".pk-content");
        0 === s ? ("left" === c ? ($(".left-img").addClass("img-active"),
        l.addClass("unvoted")) : ($(".right-img").addClass("img-active"),
        k.addClass("unvoted")),
        a.addClass("voting")) : (b = d.find("." + c),
        b.addClass("img-active"),
        "left" === c ? (k.addClass("text-voted"),
        l.addClass("text-unvoted")) : (k.addClass("text-unvoted"),
        l.addClass("text-voted")),
        $.os.android && setTimeout(function() {
            k.addClass("hide-zan"),
            l.addClass("hide-zan")
        }, 700)),
        d.addClass("voting");
        var e = 550;
        1 === s && (e = 700),
        setTimeout(function() {
            $(".result-num, .result-num-text").each(function() {
                var a = ~~$(this).data("num");
                g($(this), $(this).data("role") === c ? a + 1 : a)
            })
        }, e)
    }
    function g(a, b, c) {
        function d(j) {
            f = f || j,
            g = j;
            var k = j - f;
            i = c - k,
            h = e(k, 0, b, c),
            h = Math.ceil(h),
            h = h > b ? b : h,
            a.text(h),
            c > k && window.requestAnimationFrame(d)
        }
        function e(a, b, c, d) {
            return a === d ? b + c : c * (-Math.pow(2, -10 * a / d) + 1) + b
        }
        var f = null 
          , g = null 
          , h = null 
          , i = null ;
        c = 1e3 * c || 1e3,
        window.requestAnimationFrame(d)
    }
    function h(b, c) {
        i = a.postType,
        $(document.body).addClass("topic-pk"),
        document.title = "投票",
        mqq.ui.refreshTitle(),
        m.data = b,
        m.rock(),
        c && c(b),
        r("exp_vote", {
            obj1: q,
            ver3: u
        })
    }
    var i, j, k, l, m, n = renderModel, o = cgiModel, p = a.bid, q = a.pid, r = a.report, s = 0, t = 0, u = 0, v = Util.getHash("from"), w = Util.queryString("source") || Util.getHash("source"), x = Util.queryString("bid") || Util.getHash("bid");
    return m = new n({
        comment: "post_pk_model",
        renderTmpl: b.top_pk,
        renderContainer: "#detail_top_info",
        processData: function(a) {
            t = ~~a.vote_result.user_vote_result.op,
            a.vote_state = t;
            var b = parseInt((new Date).getTime().toString().substring(0, 10), 10);
            u = b < a.post.start_time ? 0 : b > a.post.end_time ? 2 : 1,
            a.post.time_type && u && (u = 1),
            a.progress_state = u,
            s = "undefined" == typeof a.post.aSide.pic ? 1 : 0,
            a.type = s
        },
        events: function() {
            k = $("#left"),
            l = $("#right"),
            0 === t && 1 === u && d()
        },
        complete: function() {
            (1 === u && 0 !== t || 2 === u) && (1 === t ? (k.addClass("voted").css("background-color", "#3b78ce"),
            l.addClass("voted").css({
                "background-color": "#686868",
                color: "rgba(255,255,255,.5)"
            }),
            0 === s && k.find(".zan").addClass("voted")) : 2 === t && (k.addClass("voted").css({
                "background-color": "#686868",
                color: "rgba(255,255,255,.5)"
            }),
            l.addClass("voted").css("background-color", "#2fca9d"),
            0 === s && l.find(".zan").addClass("voted")),
            0 === s && $(".result-num, .result-num-mask").css("visibility", "visible")),
            "barindex" !== w && "openact" !== v && ($("#js_detail_main").before('<div class="top-nav item-key-value" id="top_nav" href="javascript:;"><span id="top_nav_barname">更多“' + a.postData.bar_name + '”的话题</span> <span id="top_nav_info"></span> <a id="enter_bar_btn" class="border-1px" href="javascript:;">立即查看</a></div>'),
            $("#top_nav").show(),
            $("#js_detail_main").css("top", "44px"),
            $("#enter_bar_btn").on("tap", function(a) {
                a.preventDefault(),
                Util.openUrl("http://buluo.qq.com/mobile/barindex.html#bid=" + x, !0)
            }))
        }
    }),
    {
        render: h
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Post.Postarray = b(c)
}(this, function(a) {
    function b(a, b) {
        d.data = a,
        d.rock(),
        c.data = a,
        c.rock(),
        b && b(a)
    }
    var c, d, e = renderModel, f = a.report, g = 0;
    return d = new e({
        comment: "post_header_model",
        renderTmpl: window.TmplInline_detail_header.top_detail_header,
        renderContainer: "#detail_top_info_header",
        renderTool: honourHelper,
        complete: function() {
            var a = $(".detail-from").width();
            $(".title-bottom-wrapper .name-wrap").css("padding-right", a),
            f("exp_nav_top")
        }
    }),
    c = new e({
        comment: "post_array_model",
        renderTmpl: window.TmplInline_detail.top_postarray,
        renderContainer: "#detail_top_info",
        processData: function(a) {
            if (1 === ~~a.post.is_recruit) {
                var b = a.big_admin_num
                  , c = a.sml_admin_num;
                3 > b && (g = 1),
                7 > c && (g = 2),
                3 > b && 7 > c && (g = 3),
                0 === b && (g = 11),
                "undefined" == typeof b && (g = 3)
            }
        },
        events: function() {
            a.bindNormalEvents(a.postUin, g)
        },
        complete: function(a) {
            if (MediaPlayer.initVPlayerMuti("#detail_top_info"),
            $.os.ios && a.post) {
                for (var b = a.post.post_array || [], c = 0, d = b.length - 1; d >= 0; d--)
                    "video" === b[d].type && c++;
                c >= 2 && $(".post-video").addClass("hack-video")
            }
        }
    }),
    {
        render: b
    }
}),
function(a, b) {
    var c = a.Detail;
    console.log('ccc');
    console.log(c);
    c.Appreciation = b(c, window.TmplInline_detail)
}(this, function(a, b) {
    function c(b) {
        var c = b.user_info.admin_ext;
        $("#detail_top_info .actions").length ? $("#detail_top_info .actions").before($("#detail_appreciation")) : $(".live-read-num-container").length && $(".live-read-num-container").before($("#detail_appreciation")),
        b.pay_white_bid && !b.post.is_recruit && (b.appreciated_count = 0,
        1002 === b.uin || "1002" === b.uin ? (b.show_pay = 0,
        $("#detail_appreciation").hide()) : b.award ? b.show_pay = 1 : 2 === (2 & c) || 4 === (4 & c) || 256 === (256 & c) || b.interest_no ? b.show_pay = 1 : 1 === b.best || 1 === b.is_top ? b.show_pay = 1 : (b.show_pay = 0,
        $("#detail_appreciation").hide()),
        a.postData.bar_class === window.BARTYPE.BARCLASS.qunSubscription && (b.show_pay = 0,
        $("#detail_appreciation").hide()),
        1 === b.show_pay && d(function(a) {
            var b = Util.queryString("is_anon");
            "1" === Util.queryString("pay_suc") && g(a, b),
            e(a)
        })),
        f(),
        m.data = b,
        m.rock()
    }
    function d(b) {
        var c = a.pid
          , d = a.bid
          , e = new l({
            comment: "getPayedUsersModel",
            cgiName: _domain + "/cgi-bin/bar/pay/get_post_pay_users",
            param: {
                bid: d,
                pid: c,
                count: 30,
                page: 0
            },
            processData: function(a) {
                var b;
                if (a.result.total_num && a.result.user_faces) {
                    b = a.result.user_faces;
                    for (var c = b.length - 1; c >= 0; c--)
                        b[c].pic ? b[c].pic = imgHandle.getThumbUrl(b[c].pic) : b[c].pic = i();
                    a.result.users = b
                }
            },
            complete: function(a) {
                b && b(a)
            },
            error: function() {
                Tip.show("获取已经赞赏的用户失败,请稍后重试", {
                    type: "warning"
                })
            }
        });
        e.rock()
    }
    function e(a) {
        a.result.total_num > 0 && (a.result.total_num > 999 ? $(".appreciation .head-count i").text("999+") : $(".appreciation .head-count i").text(a.result.total_num),
        $(".appreciation .head-count").addClass("show")),
        n.data = a,
        n.rock()
    }
    function f() {
        function b(b, c, d) {
            Alert.show("赞赏成功", '<p class="a-comment">评论一句鼓励下作者吧~</p><div class="a-in-text-border" ><textarea placeHolder="200个字以内" class="a-in-text a_edit" ></textarea></div>', {
                confirm: "取消",
                theme: "a-commemt",
                cancel: "<strong>发送</strong>",
                template: "basic",
                preventDefaultUI: !0,
                preventAutoHide: !0,
                onTap: function(b, c, e) {
                    if ("right" === b) {
                        var f = $(e).find(".a_edit")
                          , g = {
                            params: {
                                is_reward: 1
                            }
                        };
                        return "1" === d && (g = {}),
                        a.Publish.wordsReply(f.val(), g)
                    }
                }
            }),
            a.report("return_suc", {
                module: "admire",
                ver3: b
            })
        }
        "1" === Util.queryString("pay_suc") && b(Util.queryString("pay_amount"), Util.queryString("tokenid"), Util.queryString("is_anon")),
        "0" !== mqq.QQVersion && mqq.addEventListener("pay_suc", function(a) {
            var c = a.is_anon;
            b(a.amount, a.tokenid, c),
            d(function(a) {
                a.result && +a.result.total_num >= 1e3 && (a.result.total_num = "999+"),
                g(a, c),
                e(a)
            })
        })
    }
    function g(a, b) {
        var c, d, e, f;
        a && a.result && (c = a.result.total_num,
        "1" === b ? d = 0 : (d = a.result.current_uin || 0,
        e = a.result.current_pic),
        f = a.result.user_faces,
        1 !== c && (a.result.user_faces = h(f, d, e)))
    }
    function h(a, b, c) {
        for (var d = -1, e = a.length - 1; e >= 0; e--)
            if (a[e].uin === b) {
                d = e;
                break
            }
        return d >= 0 ? a.unshift(a.splice(d, 1)[0]) : (0 === b && (c = i()),
        a.unshift({
            pic: c,
            uin: b
        })),
        a
    }
    function i() {
        var a = "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/anonymous";
        return a = a + (1 + parseInt(4 * Math.random(), "10")) + ".png"
    }
    function j() {
        $(".btn-appreciation").on("tap", function() {
            var b = $(this)
              , c = b.data("bid")
              , d = b.data("pid")
              , e = a.pid
              , f = a.bid;
            +c === +f && e === d && ("0" === mqq.QQVersion ? window.setTimeout(function() {
                b.removeClass("active"),
                window.location.href = "/cgi-bin/bar/jump?bid=" + c + "&pid=" + d + "&from=pay"
            }, 100) : (b.addClass("active"),
            a.report("Clk_button", {
                module: "admire"
            }),
            window.setTimeout(function() {
                b.removeClass("active"),
                Util.openUrl(a.base + "appreciation_pay.html?bid=" + c + "&pid=" + d, 1)
            }, 100)))
        }),
        $(".head-count, .appreciation-list").on("tap", function() {
            var b = $(".head-count")
              , c = b.data("bid")
              , d = b.data("pid");
            a.report("Clk_ple", {
                module: "admire"
            }),
            Util.openUrl(a.base + "appreciation_list.html?bid=" + c + "&pid=" + d, 1)
        })
    }
    var k = renderModel
      , l = l || cgiModel
      , m = new k({
        comment: "appreciation_model",
        renderTmpl: b.appreciation,
        renderContainer: "#detail_appreciation",
        complete: function() {
            j()
        }
    })
      , n = new k({
        comment: "appreciation_list_model",
        renderTmpl: b.appreciation_list,
        renderContainer: ".appreciation-list",
        complete: function() {
            $(".appreciation-list").height() > 42 ? $(".appreciation-list").removeClass("vcenter").addClass("vleft") : $(".appreciation-list").removeClass("vleft").addClass("vcenter"),
            imgHandle.lazy($(".appreciation-list")[0])
        }
    });
    return {
        render: c
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Comment = b(c, window.TmplInline_commentList)
}(this, function(a, b) {
    function c(b) {
        var c = -R;
        return function() {
            return c += R,
            {
                bid: O,
                pid: P,
                num: R,
                start: c + b,
                liveorder: a.commentOrder,
                barlevel: 1
            }
        }
    }
    function d() {
        var a = R;
        return R > S ? (a = S,
        S = 0) : S -= R,
        {
            bid: O,
            pid: P,
            num: a,
            start: S,
            barlevel: 1
        }
    }
    function e() {
        K.cgiName = 200 === y ? da : ca,
        K.param = d(),
        K.rock()
    }
    function f(a) {
        if (a && a.post_array) {
            for (var b = a.post_array, c = {
                audio_list: [],
                video_list: [],
                pic_list: [],
                qqmusic_list: [],
                content: ""
            }, d = 0; d < b.length; d++)
                "text" === b[d].type && (c.content = b[d].content),
                "audio" === b[d].type && c.audio_list.push(b[d]),
                "video" === b[d].type && c.video_list.push(b[d]),
                "qqmusic" === b[d].type && c.qqmusic_list.push(b[d]),
                "pic" === b[d].type && c.pic_list.push({
                    w: b[d].width,
                    h: b[d].height,
                    url: b[d].url
                });
            for (var e in c)
                c.hasOwnProperty(e) && $.isArray(c[e]) && !c[e].length && delete c[e];
            a = c
        }
        return a
    }
    function g(b, c) {
        var d, e, g = b.result || {};
        for (g.comments = g.comments || [],
        T = c - 1,
        g.currentPage = T,
        200 === y && (g.comments = o(g.comments)),
        d = 0,
        e = g.comments.length; e > d; d++) {
            var h, i = g.comments[d], j = i.ref_comment;
            if (window.innerWidth <= 320 && (i.user.vipno && i.user.admin_ext > 0 || i.user.vipno && i.user["continue"] > 7 ? i._needShort = !0 : i._needShort = !1),
            i.comment = f(i.comment),
            i.reward_money && (i.comment.content = "{$=赞赏" + Number(i.reward_money) / 100 + "元}" + i.comment.content),
            h = i.comment.pic_list,
            h && h.length) {
                var k;
                k = h.length > 1 ? imgHandle.formatThumb(h, !0, _, _) : imgHandle.formatThumb(h, !1, 180, 180),
                i.comment.pic_list = k
            }
            if (j) {
                j.comment = f(j.comment);
                var l = j.comment.pic_list
                  , m = j.comment.audio_list
                  , n = j.comment.qqmusic_list
                  , p = j.comment.video_list
                  , q = "";
                if (j.comment.isRefComment = !0,
                l && l.length)
                    for (var r = 0; r < l.length; r++)
                        q += "[图片]";
                m && m.length && (q = "[语音]"),
                n && n.length && (q = "[音乐]"),
                p && p.length && (q = "[视频]"),
                j.comment.textPrefix = q,
                j.isdel && (j.comment.textPrefix = "",
                j.comment.content = "评论已删除"),
                aa[j.cid] = j
            }
        }
        b.result.comments.length ? ($(".empty-comment").hide(),
        $("#js_detail_scroll_top").removeClass("no-comment")) : $("#js_detail_scroll_top").addClass("no-comment"),
        g._isAdmin = honourHelper.isAdmin(g.admin_ext),
        g._myuin = U,
        g._isPoster = z,
        g._isStarGroup = a.isStarGroup,
        g.barId = O,
        g.isOfficialAccountTribe = a.isOfficialAccountTribe
    }
    function h() {
        var b = "";
        return b = 1 === a.commentType ? a.commentOrder ? da : ca : ea
    }
    function i(a, b) {
        Z = !1,
        j(a),
        J.melt(),
        H.hide(),
        I.show(),
        F.show(),
        Y.off("scroll.down").off("scroll.up"),
        G.empty(),
        J.cgiName = h(),
        J.refresh(),
        A = b
    }
    function j(b) {
        "undefined" == typeof b && (X = 0),
        S = b ? 200 === y ? a.postData.total_comment_v2 - b - 1 : b - 1 : 0,
        J.paramCache = [];
        var d = $(".show-more-before");
        S ? (J.param = c(S),
        d.show()) : (J.param = c(0),
        d.hide())
    }
    function k(b) {
        if (console.log("评论成功", b),
        b.new_level > 0 && (window.UpgradeTip && window.UpgradeTip.show({
            level: b.new_level,
            level_title: b.new_title
        }),
        a.UI.updateLevel(b.new_level, b.new_title)),
        mqq.dispatchEvent("event_tribe_credit_change", $.extend({
            bid: O
        }, b.level), {
            domains: ["*.qq.com"]
        }),
        Y.off("scroll.up").off("scroll.down"),
        G.empty(),
        Z && (T = 0),
        1 === a.commentOrder || 2 === a.commentType)
            return void J.refresh();
        if (b.post) {
            var c = b.new_index || b.post.total + 1
              , d = parseInt((c - 1) / R);
            d === T ? l(d, function(a) {
                $("#comment_page_" + d).html(a),
                imgHandle.lazy($("#comment_page_" + d)[0]),
                r(c, !1, function() {})
            }) : l(d, function(b, e) {
                n(e, c) && (G.append(b),
                J.freeze(),
                r(c, !1, function() {
                    m(d),
                    B.addClass("spinner").html("载入中，请稍候...").show(),
                    I.hide(),
                    F.hide(),
                    Z || 0 === a.currentCommentFloor ? H.text("回到顶部").show() : H.text("回到" + a.currentCommentFloor + "楼").show()
                }))
            })
        }
    }
    function l(a, c) {
        var d = new N({
            comment: "comment_page_model",
            cgiName: ca,
            param: {
                bid: O,
                pid: P,
                num: R,
                start: a * R,
                barlevel: 1
            },
            noCache: 1,
            renderTmpl: b.normal,
            renderContainer: $(document.createDocumentFragment()),
            processData: function(b) {
                g.call(this, b, a + 1),
                b.result.barId = O
            },
            complete: function(a) {
                c(this.renderContainer, a)
            }
        });
        d.rock()
    }
    function m(a) {
        function b() {
            if (Y.scrollTop() < 60) {
                if (f)
                    return;
                if (console.log("加载上一页"),
                d === J.cgiCount - 1) {
                    B.hide(),
                    I.show(),
                    F.show(),
                    H.hide(),
                    Y.off("scroll.down");
                    var a = $("#comment_page_" + (d + 1)).position().top;
                    return void Y.scrollTop(a)
                }
                B.addClass("spinner").html("载入中，请稍候...").show(),
                f = !0,
                l(d, function(a) {
                    f = !1,
                    G.prepend(a);
                    var b = $("#comment_page_" + (d + 1)).position().top;
                    imgHandle.lazy(C[0]),
                    Y.scrollTop(b - 63),
                    d--
                })
            }
        }
        function c() {
            var a = Y.scrollTop()
              , b = $.os.ios ? Y[0].scrollHeight - 100 : $("body")[0].scrollHeight - 160
              , c = $(window).height();
            100 > b - a - c && (g || (g = !0,
            l(e + 1, function(a, b) {
                g = !1,
                G.append(a),
                imgHandle.lazy(C[0]),
                e++,
                b.result.isend && (Y.off("scroll.up"),
                B.removeClass("spinner").html("已经到底了").show())
            })))
        }
        var d = a - 1
          , e = a
          , f = !1
          , g = !1;
        Y.off("scroll.down").on("scroll.down", b).off("scroll.up").on("scroll.up", c),
        Y.scrollTop() < 60 && b()
    }
    function n(a, b) {
        for (var c = a.result.comments || [], d = c.length, e = 0, f = !1; d > e; e++)
            if (c[e].index === b) {
                f = !0;
                break
            }
        return f
    }
    function o(a) {
        return a
    }
    function p(a) {
        return a ? a.replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&quot;/g, '"').replace(/&apos;/g, "'") : a
    }
    function q() {
        var b = ["a", ".user-avatar", ".user-nick", ".honour", ".ref-comment", ".js-btn-action", ".music", ".link", ".audio", ".post-video", ".img-box img", ".richpost-new img", ".l-level"].join(",");
        $("#btnShowInturn").tap(function() {
            var b = $(this);
            a.commentOrder ? (a.commentOrder = 0,
            b.text("倒序查看").parent().addClass("reverse"),
            V("Clk_inturn")) : (a.commentOrder = 1,
            b.text("顺序查看").parent().removeClass("reverse"),
            V("Clk_reverse")),
            i()
        }),
        $("#show-more-before").tap(function() {
            e()
        }),
        C.on("tap", ".js-btn-action", function(a) {
            var b = $(this)
              , c = b.closest("li")
              , d = c.attr("cid");
            if (d)
                return a.stopImmediatePropagation(),
                b.hasClass("delete") ? void L.delWithConfirm("delComment", {
                    cid: d
                }, b, c) : void (b.hasClass("like") && L.likeComment(b, c))
        }).on("tap", ".ref-comment", function(a) {
            var b = $(this).data("id")
              , c = ~~$(this).data("isdel");
            b && 1 !== c && (V("Clk_quote_layer"),
            u(b),
            a.stopImmediatePropagation())
        }),
        C.on("tap", "li", function(a) {
            var c = $(a.target)
              , d = $(this);
            c.is(b) || c.parents(b).length || (d.addClass("list-active"),
            setTimeout(function() {
                d.removeClass("list-active")
            }, 200))
        }),
        C.on("click", "li", function(c) {
            if (!Alert || !Alert.alertStatus) {
                var d = $(c.target)
                  , e = $(this)
                  , f = e.attr("cid")
                  , g = ~~e.data("lz")
                  , h = "commentReply"
                  , i = p(e.attr("nick_name"));
                d.is(b) || d.parents(b).length || (a.currentCommentFloor = g,
                a.currentCommentID = f,
                e.find(".ref-comment").length ? (V("Clk_reply_layer"),
                h = "refReply") : V("Clk_reply_own"),
                V("Clk_reply_one"),
                a.Publish.reply(f, h, i, ~~g))
            }
        }),
        D.on("tap", function(a) {
            var b = $(a.target);
            return b.closest(".user-avatar").length && V("layer_head"),
            b.closest(".audio").length ? void V("layer_voice") : b.closest(".music").length ? void V("layer_music") : b.closest(".post-video").length ? void V("layer_video") : void v()
        }),
        H.on("tap", function() {
            if ($(this).hide(),
            I.show(),
            F.show(),
            Y.off("scroll.down").off("scroll.up"),
            B.addClass("spinner").html("载入中，请稍候...").show(),
            "回到顶部" === $(this).text())
                V("return_top"),
                Y.scrollTop(0),
                J.melt(),
                G.empty(),
                0 === J.cgiCount && J.rock();
            else {
                var b = $.os.ios ? Y[0].scrollHeight - 100 : $("body")[0].scrollHeight - 160;
                V("return_floor"),
                Y.scrollTop(b),
                r(a.currentCommentFloor - 1, !0, function() {
                    J.melt(),
                    G.empty()
                })
            }
        }).on("touchend", function(a) {
            a.preventDefault()
        })
    }
    function r(a, b, c) {
        function d(a) {
            j = j || a,
            k = a;
            var f = a - j;
            m = h - f,
            l = e(f, n, o, h),
            b && (l = i - l),
            Y.scrollTop(l),
            h > f ? window.requestAnimationFrame(d) : (c && c(),
            console.log("scroll done! scrollTop:", l),
            imgHandle.lazy(C[0]))
        }
        function e(a, b, c, d) {
            return c * ((a = a / d - 1) * a * a + 1) + b
        }
        var f = $("#comment_" + a);
        if (f.length) {
            var g = f.position().top - 44
              , h = 1e3
              , i = Y.scrollTop()
              , j = null 
              , k = null 
              , l = null 
              , m = null 
              , n = i
              , o = g;
            return b && (o = i - g + 64,
            n = 0),
            Z ? (Y.scrollTop(g),
            c && c(),
            void imgHandle.lazy(C[0])) : void window.requestAnimationFrame(d)
        }
    }
    function s(a) {
        if (Z = !0,
        console.log("直接跳转到", a + 1, "楼"),
        a > 0 && R > a)
            J.cgiName = h(),
            J.myData = a,
            J.rock();
        else if (a >= R) {
            var b = parseInt((a - 1) / R);
            l(b, function(c, d) {
                return n(d, a) ? (J.freeze(),
                G.append(c),
                B.addClass("spinner").html("载入中，请稍候...").show(),
                I.hide(),
                F.hide(),
                void r(a, !1, function() {
                    t(a),
                    m(b),
                    H.text("回到顶部").show(),
                    d.result.isend && B.removeClass("spinner").html("已经到底了").show()
                })) : (I.show(),
                F.show(),
                Y.scrollTop(0),
                J.melt(),
                G.empty(),
                void (0 === J.cgiCount && J.rock()))
            })
        }
    }
    function t(a) {
        console.log(a),
        setTimeout(function() {
            var b = $("#comment_" + a);
            b.addClass("highlight")
        }, 150)
    }
    function u(b) {
        var c = aa[b];
        c && (a.isRefDlgOpen = !0,
        fa.data = c,
        fa.data.barId = O,
        fa.rock())
    }
    function v() {
        $(document).off("touchmove.dialog"),
        E.addClass("close-anim-out"),
        D.removeClass("fade-in").addClass("fade-out"),
        setTimeout(function() {
            $(".detail-main,.bottom-bar").removeClass("blur")
        }, 50),
        D.on("webkitAnimationEnd", function() {
            a.isRefDlgOpen = !1,
            D.hide().removeClass("fade-out"),
            E.removeClass("close-anim-out dlg-anim"),
            $(".comment-dialog-content .user-avatar,.comment-dialog-content .name-wrap").removeClass("dlg-anim"),
            D.off("webkitAnimationEnd")
        })
    }
    function w() {
        B = $("#js_comment_loading"),
        C = $("#js_detail_list"),
        D = $("#comment_dialog"),
        E = $(".comment-dialog-close"),
        F = $("#top_comment_wrapper"),
        G = $("#bottom_comment_wrapper"),
        I = $("#top_post_wrapper"),
        H = $("#back_to_top")
    }
    function x() {
        y = a.postType,
        z = a.postData.isposter,
        w(),
        $(".show-inturn").show(),
        (100 === y || 101 === y) && $("#qunact-reply").show(),
        "true" === a.getParam("nojump") && (W = !0),
        W ? i(X) : X ? (a.commentOrder = 0,
        $("#btnShowInturn").html("倒序查看"),
        $(".show-inturn").addClass("reverse"),
        s(X)) : i(0),
        q()
    }
    var y, z, A, B, C, D, E, F, G, H, I, J, K, L, M = scrollModel, N = renderModel, O = a.bid, P = a.pid, R = 20, S = 0, T = 0, U = Login.getUin(), V = a.report, W = !1, X = ~~a.getParam("lnum"), Y = $.os.ios ? $("#js_detail_main") : $(window), Z = !1, _ = Math.floor(($(document).width() - 75) / 3) - 4, aa = {}, ba = "_v2", ca = "/cgi-bin/bar/post/get_comment_by_page" + ba, da = "/cgi-bin/bar/post/get_comment_by_page_reverse" + ba, ea = "/cgi-bin/bar/post/get_comment_by_page_with_user" + ba;
    J = new M({
        comment: "comment_model",
        cgiName: ca,
        renderTmpl: b.normal,
        renderContainer: "#top_comment_wrapper",
        scrollEl: $.os.ios ? $("#js_detail_main") : $(window),
        scrollThreshold: .25,
        param: c(0),
        noCache: 1,
        renderTool: honourHelper,
        processData: g,
        events: function() {
            $("body").on("tap", "#reloadCommonets", function() {
                B.addClass("spinner").html("载入中，请稍候..."),
                J.rock()
            })
        },
        complete: function(a, b) {
            MediaPlayer.initVPlayerMuti(this.renderContainer, {
                width: $(window).width() - 75
            }),
            MediaPlayer.checkMusicState("top_comment_wrapper"),
            imgHandle.lazy(C[0]),
            a.result.isend ? (this.freeze(),
            1 !== b || 0 !== a.result.comments.length || S ? (B.removeClass("spinner").html("已经到底了").show(),
            $(".empty-comment").hide()) : (B.hide(),
            $(".empty-comment").show())) : a.result.comments.length < 10 && Q.monitor(609401),
            A && A(a);
            var c = this.myData;
            c && (r(this.myData, !1, function() {
                t(c)
            }),
            this.myData = null )
        },
        onreset: function() {
            $(this.renderContainer).empty(),
            B.addClass("spinner").html("载入中，请稍候...")
        },
        error: function(a) {
            B.removeClass("spinner").html("拉取失败[" + a.retcode + ']，<a href="javascript:;" id="reloadCommonets">点击重试</a>')
        }
    }),
    K = new N({
        comment: "prepage_comment_model",
        cgiName: "",
        noCache: 1,
        renderTmpl: b.normal,
        renderTool: honourHelper,
        renderContainer: $(document.createDocumentFragment()),
        processData: g,
        complete: function() {
            var a = $(J.renderContainer).find("li").first()
              , b = a.position().top
              , c = Y.scrollTop()
              , d = 0;
            this.renderContainer.prependTo(J.renderContainer),
            d = a.position().top,
            Y.scrollTop(c + d - b),
            imgHandle.lazy(C[0]),
            0 === S && $(".show-more-before").hide()
        }
    }),
    L = {
        error: function(a) {
            var b = "删除失败[" + a.retcode + "]";
            101e3 === a.retcode && (b = "此为管理员发表，无法删除"),
            Tip.show(b, {
                type: "warning"
            })
        },
        delWithConfirm: function(a, b, c, d) {
            var e = {
                bid: O,
                pid: P
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
                        L[a](e, c, d)
                    }
                })
            }, 0) : (z ? V("del_other_com", {
                ver3: e.cid
            }) : V("del_own_com", {
                ver3: e.cid
            }),
            void L[a](e, c, d))
        },
        delComment: function(b, c, d) {
            DB.cgiHttp({
                type: "POST",
                url: "/cgi-bin/bar/post/del_comment",
                ssoCmd: "del_comment",
                param: b,
                succ: function() {
                    d.remove(),
                    mqq.dispatchEvent("delete_comment"),
                    z && V("del_reply", {
                        ver3: P
                    }),
                    a.openActReport("del_reply_suc")
                },
                err: L.error
            })
        },
        likeComment: function(a, b) {
            if (!a.hasClass("liked") && !a.hasClass("liked-active")) {
                var c = b.data("lz")
                  , d = b.attr("cid")
                  , e = b.find(".comment-user-info .user-nick").data("profile-uin");
                a.addClass("liked-active"),
                a.text(~~a.text() + 1),
                V("like_comment", {
                    obj1: P,
                    ver3: c
                }),
                DB.cgiHttp({
                    type: "POST",
                    url: "/cgi-bin/bar/post/like_comment",
                    ssoCmd: "like_comment",
                    param: {
                        bid: O,
                        pid: P,
                        cid: d,
                        tuin: e,
                        like: 1
                    }
                })
            }
        }
    };
    var fa = new N({
        comment: "ref_comment_dialog",
        renderTmpl: window.TmplInline_detail.ref_comment,
        renderContainer: "#comment_dialog_content",
        events: function() {
            bouncefix.add("comment-dialog")
        },
        complete: function() {
            $(".detail-main,.bottom-bar").addClass("blur"),
            D.show().addClass("fade-in"),
            E.addClass("dlg-anim"),
            setTimeout(function() {
                $(".comment-dialog-content .user-avatar,.comment-dialog-content .name-wrap").addClass("dlg-anim")
            }, 300),
            setTimeout(function() {
                $(".comment-dialog-content .content-wrapper").addClass("dlg-anim")
            }, 400),
            MediaPlayer.initVPlayerMuti(D[0]),
            MediaPlayer.checkMusicState("comment_dialog_content");
            var a = $(".comment-dialog");
            a[0].scrollHeight <= a.height() && $(document).on("touchmove.dialog", function(a) {
                a.preventDefault()
            })
        }
    });
    return {
        rock: x,
        refresh: i,
        loadPrevPage: e,
        afterReply: k,
        hideRefDialog: v
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Recommend = b(c)
}(this, function(a) {
    function b(b, c) {
        c.addClass("active"),
        setTimeout(function() {
            c.removeClass("active"),
            "barindex" === a.source && "0" !== mqq.QQVersion ? mqq.ui.popBack() : Util.openUrl(a.base + "barindex.html#bid=" + b + "&scene=detail_recommend", !0)
        }, 100)
    }
    function c(a, b, c) {
        c.addClass("read active"),
        setTimeout(function() {
            c.removeClass("active"),
            Util.openDetail({
                "#bid": a,
                "#pid": b
            }, !1, c.data("type"))
        }, 100)
    }
    function d() {
        var a, b, c = window.innerHeight, d = +new Date, e = !1;
        g[0].getBoundingClientRect().top < c && (k("exp_rela"),
        e = !0),
        e || (a = function() {
            +new Date - d > 160 && (e || (b = g[0].getBoundingClientRect().top,
            c > b && (k("exp_rela"),
            e = !0)),
            d = +new Date,
            e && $(document).off("touchmove", a))
        }
        ,
        $(document).on("touchmove", a))
    }
    function e() {
        var b = window.BARTYPE.BARCLASS;
        100 !== a.postType && 101 !== a.postType && a.postData.bar_class !== b.qunSubscription && (g.show(),
        f || (h.rock(),
        f = !0))
    }
    var f, g, h, i = renderModel, j = a.bid, k = a.report;
    return g = $("#recommend-list"),
    h = new i({
        comment: "recommend_model",
        cgiName: _domain + "/cgi-bin/bar/post/related_posts",
        renderTmpl: window.TmplInline_detail.recommend,
        renderContainer: g,
        param: {
            bid: j,
            pid: a.pid,
            needbar: 1
        },
        noCache: 1,
        processData: function(b) {
            b.result.barName = a.postData.bar_name,
            b.result.bid = a.postData.bid
        },
        events: function() {
            g.on("tap", ".recommend-post-list > li", function() {
                var a = $(this)
                  , d = a.data("bid")
                  , e = a.data("pid");
                e ? (k("Clk_rela", {
                    ver3: 1,
                    ver4: e
                }),
                c(d, e, a)) : (k("Clk_rela", {
                    ver3: 2
                }),
                b(d, a))
            }),
            d()
        },
        complete: function() {}
    }),
    {
        rock: e
    };
}),
function(a, b) {
    var c = a.Detail;
    c.Events = b(c)
}(this, function(a) {
    function b() {
        c(),
        $("#js_detail_main").on("tap", ".img-box img,.richpost-new img", function() {
            if (Q.monitor(648125),
            !window.ImageView)
                return Tip.show("图片查看器加载中，请稍后点击图片重试", {
                    type: "warning"
                }),
                void Q.monitor(648126);
            var a, b, c, d, e, f = $(this), g = [], h = !1, i = !1;
            if (f.closest(".richpost-new").length && (h = !0),
            f.closest("#detail_top_info").length && (i = !0),
            a = h ? $(".richpost-new") : f.parent(),
            a.length) {
                b = h ? a.find("img") : a.closest(".content-wrapper").find(".img-box img"),
                c = 0,
                b.each(function(a) {
                    this === f[0] && (c = a),
                    d = $(this).data("src") || $(this).attr("lazy-src") || this.src,
                    h ? g.push({
                        name: "",
                        mbimg: d
                    }) : (d.indexOf("?") > -1 && (d = d.slice(0, d.indexOf("?"))),
                    g.push({
                        name: "",
                        mbimg: $(this).attr("data-src") ? $(this).attr("data-src") + "1000" : d
                    }))
                }),
                e = {
                    useNavigate: !0,
                    maxZoom: 3,
                    onClose: function() {
                        Refresh.melt()
                    }
                };
                var j = J.admin_ext
                  , k = 2 === (2 & j) || 4 === (4 & j);
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
                                    ssoCmd: "add_photo",
                                    param: {
                                        bid: F,
                                        flag: 0,
                                        pid: G,
                                        url: b
                                    },
                                    succ: function(a) {
                                        a.result && 999 === a.result.errCode ? Tip.show("图片已在相册中") : Tip.show("已加入部落相册")
                                    },
                                    err: function() {
                                        Tip.show("操作失败", {
                                            type: "warning"
                                        })
                                    }
                                }),
                                H("one_album")
                            }
                        })
                    }
                })),
                ImageView.init(g, c, e),
                Refresh.freeze(),
                H("Clk_big_pic")
            }
        })
    }
    function c() {
        setTimeout(function() {
            window.ImageView && ImageView.onLongTap && (N = !0);
            var a = J.admin_ext;
            2 !== (2 & a) && 4 !== (4 & a) || N || (N = !0,
            loadjs.loadModule("image_view", function() {}))
        }, 500)
    }
    function d() {
        if (mqq && mqq.ui && !a.isInYyb) {
            var b = {
                isDetail: !0,
                iconID: "4",
                type: "more"
            };
            ActionButton.build(b, function() {
                f(),
                e(),
                H("Clk_right")
            }),
            ActionButton.setCallback(a.Post.Normal.triggerUploading)
        }
    }
    function e() {
        var b, c = [], d = [], e = J.uin === a.myuin, f = J.post.status, q = J.admin_ext;
        a.postData.bar_class !== L.qunSubscription && c.push({
            text: "查看部落",
            img: O.tribe,
            onTap: g
        }),
        d.push(g),
        "detail" === a.getTplName(I) && (c.push({
            text: 1 === a.commentType ? "只看楼主" : "查看全部",
            img: O.poster,
            onTap: h
        }),
        d.push(h)),
        c.push({
            text: a.commentOrder ? "顺序查看" : "倒序查看",
            img: a.commentOrder ? O.asc : O.desc,
            onTap: i
        }),
        d.push(i),
        a.isOpenAct && 2 !== f && e && (0 === f && (c.push({
            text: "编辑活动",
            img: O.join,
            onTap: k
        }),
        d.push(k)),
        c.push({
            text: "取消活动",
            img: O.unjoin,
            onTap: l
        }),
        d.push(l)),
        !e && 2 !== f && $(".bottom-bar").hasClass("has-joined") && (c.push({
            text: "取消报名",
            img: O.unjoin,
            onTap: j
        }),
        d.push(j)),
        e || (c.push({
            text: "投诉举报",
            img: O.report,
            onTap: m
        }),
        d.push(m)),
        (1 === (1 & q) || 2 === (2 & q)) && (0 === a.isBest ? (c.push({
            text: "加精话题",
            img: O.best,
            onTap: n
        }),
        d.push(n)) : (c.push({
            text: "取消加精",
            img: O.unbest,
            onTap: n
        }),
        d.push(n)),
        c.push({
            text: a.isSticky ? "取消置顶" : "置顶",
            img: O.sticky,
            onTap: o
        }),
        d.push(o)),
        (2 === (2 & q) || 4 === (4 & q)) && J.post.pic_list && J.post.pic_list[0] && (c.push({
            text: "添加至相册",
            img: O.addphoto,
            onTap: p
        }),
        d.push(p)),
        200 === I && $.os.ios && (b = document.querySelector("video"),
        b && (b.style.display = "none")),
        RichShare.build(c),
        x()
    }
    function f() {
        $(document).off("touchmove", function(a) {
            a.preventDefault()
        }),
        window.Publish && Publish.destroy(),
        $("#join_activity_win").hide(),
        $("#quit_activity_win").hide(),
        $("video").show(),
        window.location.hash.indexOf("imageview") > -1 && window.history.go(-1),
        window.location.hash.indexOf("peopleliked") > -1 && window.history.go(-1),
        window.location.hash.indexOf("peoplejoined") > -1 && window.history.go(-1),
        window.location.hash.indexOf("poi") > -1 && window.history.go(-1)
    }
    function g() {
        H("Clk_more_tribe"),
        Util.openUrl(a.base + "barindex.html#bid=" + F + "&scene=detail_share", !0)
    }
    function h() {
        var b = "";
        1 === a.commentType ? (a.commentType = 2,
        b = "查看全部",
        H("Clk_onlyhost")) : (a.commentType = 1,
        b = "只看楼主"),
        $(this).find("p").text(b),
        a.Comment.refresh()
    }
    function i() {
        var b, c, d = $("#btnShowInturn");
        a.commentOrder ? (a.commentOrder = 0,
        b = "倒序查看",
        c = O.desc,
        H("Clk_inturn")) : (a.commentOrder = 1,
        b = "顺序查看",
        c = O.asc,
        H("Clk_reverse")),
        d.text(b),
        $(this).find("img").attr("src", c),
        $(this).find("p").text(b),
        a.Comment.refresh()
    }
    function j() {
        ActionSheet.hide(!0),
        a.Join.quitAct(),
        K("Clk_quit")
    }
    function k() {
        K("Clk_edit_local"),
        +new Date + 1728e5 > J.post.start ? (Alert.show("", "同城活动在开始前2天内无法编辑！"),
        K("refuse_edit_local")) : Util.openUrl("http://qqweb.qq.com/m/qunactivity/form.html?type=modify&atvid=" + J.post.openact_id + "&_wv=7&_bid=244&open=1", !0)
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
                        id: J.post.openact_id,
                        type: 1
                    },
                    succ: function() {
                        Tip.show("取消活动成功"),
                        K("Clk_un_suc4open"),
                        K(435889),
                        a.Join.syncActivity("cancel"),
                        setTimeout(function() {
                            window.mqq && mqq.ui.popBack()
                        }, 1500)
                    },
                    err: function() {
                        Tip.show("取消活动失败", {
                            type: "warning"
                        }),
                        K(435937)
                    }
                })
            }
        }),
        K("Clk_un")
    }
    function m() {
        var b = J.uin;
        return isNaN(Number(b)) || Number(b) <= 0 ? void Alert.show("", "楼主帐号异常，无法举报", {
            confirm: "我知道了"
        }) : (H("Clk_report"),
        void window.jubaokit.init({
            barId: F,
            pid: G,
            eviluin: b,
            impeachuin: a.myuin
        }))
    }
    function n() {
        var b = {}
          , c = this;
        0 === a.isBest ? (b = {
            title: "确认加精"
        },
        H("right_top")) : b = {
            title: "确认取消",
            bestFlag: 0
        },
        ActionSheet.show({
            items: [b.title],
            onItemClick: function() {
                var d = {
                    bid: F,
                    pid: G
                };
                0 === b.bestFlag && (d.isbest = 0),
                DB.cgiHttp({
                    url: "/cgi-bin/bar/post/best",
                    type: "POST",
                    ssoCmd: "post_best",
                    param: d,
                    succ: function() {
                        if (0 === b.bestFlag)
                            $(".best").remove(),
                            a.isBest = 0,
                            Tip.show("取消加精成功", {
                                type: "ok"
                            }),
                            c.find("p").html("加精话题"),
                            c.find("img")[0].src = O.best;
                        else {
                            var d = document.createElement("label");
                            d.className = "best",
                            d.innerHTML = "精",
                            $(".post-title").prepend(d),
                            a.isBest = 1,
                            Tip.show("加精成功", {
                                type: "ok"
                            }),
                            c.find("p").html("取消加精"),
                            c.find("img")[0].src = O.unbest
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
        var b = {
            title: a.isSticky ? "确认取消置顶" : "确认置顶"
        }
          , c = this;
        ActionSheet.show({
            items: [b.title],
            onItemClick: function() {
                var b = {
                    bid: F,
                    pid: G
                };
                mqq.dispatchEvent("event_post_sticky", {}, {
                    domains: ["*.qq.com"]
                });
                var d = 1 === a.isSticky ? "/cgi-bin/bar/post/del_top" : "/cgi-bin/bar/post/add_top";
                1 !== a.isSticky && H("right_esse"),
                DB.cgiHttp({
                    url: d,
                    type: "POST",
                    param: b,
                    succ: function(b) {
                        0 === b.retcode && (1 === a.isSticky ? (a.isSticky = 0,
                        Tip.show("取消置顶成功", {
                            type: "ok"
                        }),
                        c.find("p").html("置顶")) : (a.isSticky = 1,
                        Tip.show("置顶成功", {
                            type: "ok"
                        }),
                        c.find("p").html("取消置顶")),
                        mqq.dispatchEvent("event_post_sticky", {}, {
                            domains: ["*.qq.com"]
                        }))
                    },
                    err: function(a) {
                        var b = "操作失败,错误码:" + a.retcode;
                        a.retcode + "" == "100225" && (b = "置顶已达上限"),
                        Tip.show(b, {
                            type: "warning"
                        })
                    }
                })
            }
        })
    }
    function p() {
        ActionSheet.show({
            items: ["添加至部落相册"],
            onItemClick: function() {
                var a = {
                    bid: F,
                    flag: 1,
                    pid: G
                };
                DB.cgiHttp({
                    url: "/cgi-bin/bar/photo/add",
                    type: "POST",
                    ssoCmd: "add_photo",
                    param: a,
                    succ: function(a) {
                        a.result && 999 === a.result.errCode ? Tip.show("图片已在相册中") : Tip.show("已加入部落相册")
                    },
                    err: function() {
                        Tip.show("操作失败", {
                            type: "warning"
                        })
                    }
                }),
                H("all_album")
            }
        })
    }
    function q() {
        var a, b = "", c = "w:65&h:65&notFeed:true", d = !1;
        if (J.post.pic_list && J.post.pic_list.length > 0) {
            var e = J.post.pic_list[0];
            if (e.w && e.h) {
                var f = imgHandle.formatThumb([e], !0, 75, 75, !0)[0];
                a = e.url,
                b = "width:" + f.width + "px; height:" + f.height + "px;margin-left:" + f.marginLeft + "px; margin-top:" + f.marginTop + "px;"
            } else
                a = e.url || e,
                d = !0
        } else
            J.post.image1 ? (a = J.post.image1,
            d = !0) : J.post.cover && (a = J.post.cover,
            d = !0);
        return a ? {
            url: imgHandle.getThumbUrl(a, 1e3),
            style: b,
            nosize: d ? c : ""
        } : null 
    }
    function r() {
        if (mqq && parseInt(mqq.QQVersion) > 0 && mqq.compare("5.2") < 0)
            return void Alert.show("", "抱歉！由于您的手机QQ版本过低，\n敬请升级后再使用", {
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
            });
        if (a.isUploading)
            return void Tip.show("视频上传及转码中，暂不能转发", {
                type: "warning"
            });
        H("Clk_repost", {
            module: "post_detail"
        }),
        101 === I && a.openActNewReport("Clk_like"),
        J.thumbImg = q();
        var b = "";
        201 === I && (b = '<div class="c-img-mask"><i class="c-type-icon c-video"></i></div>'),
        (J.post.qqmusic_list || J.post.audio_list) && (b = '<div class="c-img-mask"><i class="c-type-icon c-music"></i></div>');
        var c = '<div class="th-cover" soda-if="thumbImg" ><img lazy-src="{{thumbImg.url}}" nosize="{{thumbImg.nosize}}" style="{{thumbImg.style}}">' + b + '</div><div class="th-text" ><h4 soda-bind-html="title|plain2rich"></h4><p soda-if="post.content" soda-bind-html="post.content|plain2rich"></p></div>';
        Tmpl.addTmpl("forward_template", c),
        Alert.textarea("转发到兴趣圈", Tmpl("forward_template", J).toString(), {
            placeholder: "说说转发理由…",
            confirm: "取消",
            cancel: "发送",
            preventAutoHide: !0,
            onTap: function(b, c, d) {
                var e = $(d).find(".edit").val();
                "right" === b ? (DB.cgiHttp({
                    url: "http://buluo.qq.com/cgi-bin/bar/impt/forward",
                    type: "POST",
                    param: {
                        pid: G,
                        bid: Number(F),
                        content: e
                    },
                    ssoCmd: "forward",
                    succ: function(b) {
                        var c = $("#js-qunact-forward").length ? $("#js-qunact-forward") : $("#to_forward")
                          , d = ~~c.text()
                          , e = b.result && b.result.add_credits
                          , f = function(f) {
                            Tip.show("转发成功" + (e > 0 ? "，经验值+" + e : "")),
                            c.html(d + 1),
                            b.result.new_level && (window.UpgradeTip && window.UpgradeTip.show({
                                level: b.result.new_level,
                                level_title: b.result.new_title
                            }),
                            a.UI.updateLevel(b.result.new_level, b.result.new_title));
                            var g = $.extend({
                                bid: F
                            }, b.result.level);
                            mqq.dispatchEvent("event_tribe_credit_change", g, {
                                domains: ["*.qq.com"]
                            }),
                            f ? f.post && a.Comment.afterReply(f) : b.result.post && a.Comment.afterReply(b.result),
                            window.mqq && mqq.dispatchEvent && (b.result.bar_name = a.postData.bar_name,
                            b.result.post ? (b.result.post.post = JSON.parse(b.result.post.post),
                            mqq.dispatchEvent("forword_complete_back", b.result, {
                                domains: ["*.qq.com"]
                            })) : s(b.result))
                        }
                        ;
                        b.result && b.result.__vcode_flag ? Checkcode.show("verify_v2", function(a) {
                            f(a)
                        }, {
                            type: 4,
                            code: b.result.code
                        }) : f()
                    },
                    err: function(a) {
                        return 100006 === a.retcode && mqq && parseInt(mqq.QQVersion) > 0 && mqq.compare("5.2") < 0 ? void Alert.show("", "抱歉！由于您的手机QQ版本过低，\n敬请升级后再使用", {
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
                        }) : void Tip.show(a.msg ? a.msg : "转发失败: 错误码" + a.retcode, {
                            type: "warning"
                        })
                    }
                }),
                H("repost_sure"),
                101 === I && a.openActNewReport("Clk_like_sure")) : H("repost_cancel")
            },
            renderSuccess: function() {
                var a, b, c, d = this;
                imgHandle.lazy($(".th-cover")[0]),
                $(this).find(".edit").on("focus", function() {
                    $(d).find(".a-forwards").css({
                        top: 5
                    })
                }),
                101 === I && (c = $(this).find(".th-text p"),
                a = /<p[^>]*>(.*?)<\/p>/g,
                b = c.html().replace(/&lt;p&gt;/g, "<p>"),
                b = b.replace(/&lt;\/p&gt;/g, "</p>"),
                c.html(b.replace(a, ""))),
                $(this).find(".edit").on("blur", function() {
                    $(d).find(".a-forwards").css({
                        top: 100
                    }),
                    $(document.body).hide(),
                    $(document.body).show()
                })
            }
        })
    }
    function s(b) {
        window.mqq.data.getUserInfo(function(c) {
            c.nick && (b.nick = c.nick,
            b.post = {},
            b.post.post = a.postData.post,
            b.post.title = a.postData.title,
            b.post.pid = a.pid,
            b.uin = a.myuin,
            b.pid = a.pid,
            b.bid = a.postData.bid,
            b.post.bid = a.postData.bid,
            b.bar_name = a.postData.bar_name,
            b.post.post.vid && (b.post.type = 201),
            b.comment = '{"content":""}\n',
            mqq.dispatchEvent("forword_complete_back", b, {
                domains: ["*.qq.com"]
            }))
        })
    }
    function t() {
        $("#to_like").tap(u),
        $("#js-qunact-like").tap(u),
        $("#js-qunact-forward").tap(r),
        $("#to_join").tap(v),
        $("#to_reply").click(w),
        $("#js-qunact-reply").click(w),
        $("#to_forward").tap(r),
        $("#to_share").tap(x),
        $("#js-qunact-share").tap(x),
        $("#js_detail_main").on("tap", 'a[rel="showProfile"]', function() {
            y($(this))
        }).on("tap", 'a[rel="openUrl"]', function() {
            z($(this))
        }).on("tap", ".l-level", function() {
            Util.openUrl(a.base + "bar_level_rank.html?bid=" + F, !0)
        })
    }
    function u() {
        if (!a.showLockTip()) {
            var b = $("#js-qunact-like").length ? $("#js-qunact-like") : $("#to_like");
            if (a.isUploading)
                return void Tip.show("视频上传及转码中，暂不能点赞", {
                    type: "warning"
                });
            if (!b.hasClass("disabled") && !b.hasClass("liked")) {
                if (mqq && parseInt(mqq.QQVersion) > 0 && mqq.compare("5.2") < 0)
                    return void Alert.show("", "抱歉！由于您的手机QQ版本过低，\n敬请升级后再使用", {
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
                    });
                b.addClass("liked animating"),
                window.mqq && mqq.dispatchEvent && mqq.dispatchEvent("detailDoLike", {
                    bid: F,
                    pid: G
                }, {
                    domains: ["*.qq.com"]
                }),
                b.html(~~b.html() + 1),
                setTimeout(function() {
                    b.removeClass("animating")
                }, 1e3),
                DB.cgiHttp({
                    url: "/cgi-bin/bar/post/like",
                    type: "POST",
                    ssoCmd: "like",
                    param: {
                        bid: +F,
                        pid: G,
                        like: 1
                    },
                    succ: function() {},
                    err: function(a) {
                        var c = "顶失败了，麻烦再试一次吧"
                          , d = ~~b.html() - 1;
                        return 100006 === a.retcode ? (b.html(d || 0),
                        b.removeClass("liked animating"),
                        void (parseInt(mqq.QQVersion) > 0 && mqq.compare("5.2") < 0 ? (c = "抱歉！由于您的手机QQ版本过低，\n敬请升级后再使用",
                        Alert.show("", c, {
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
                        })) : Alert.show("", c, {
                            confirm: "好"
                        }))) : (b.html(d || 0),
                        b.removeClass("liked animating"),
                        Alert.show("", c, {
                            confirm: "好"
                        }),
                        void 0)
                    }
                }),
                H("Clk_like", {
                    ver3: I
                })
            }
        }
    }
    function v() {
        if (!a.showLockTip()) {
            if (a.isUploading)
                return void Tip.show("视频上传及转码中，暂不能报名", {
                    type: "warning"
                });
            if (a.purchaseLink)
                return void (B(a.purchaseLink) ? Util.openUrl(a.purchaseLink, !0) : Tip.show("暂时不能购票", {
                    type: "warning"
                }));
            a.Join.joinAct(),
            H(200 === I ? "Clk_video" : "Clk_activity"),
            K("Clk_join4open")
        }
    }
    function w(b) {
        if (!(a.showLockTip() || Alert && Alert.alertStatus)) {
            if (a.isUploading)
                return void Tip.show("视频上传及转码中，暂不能评论", {
                    type: "warning"
                });
            if (!a.isRefDlgOpen) {
                b.preventDefault();
                var c = $("#top_comment_wrapper ul").last().find("li").first();
                c.length && (a.currentCommentFloor = c.data("lz"),
                a.currentCommentID = c.attr("cid")),
                a.Publish.reply(),
                101 === a.postData.type && a.openActNewReport("comment"),
                H("Clk_reply"),
                K("Clk_reply")
            }
        }
    }
    function x() {
        return a.isUploading ? void Tip.show("视频上传及转码中，暂不能分享", {
            type: "warning"
        }) : void a.Share.callHandler()
    }
    function y(a) {
        var b = a.attr("code")
          , c = ~~a.attr("type");
        c = Number(c),
        b && c && (1 === c ? (mqq.ui.showProfile({
            uin: b,
            uinType: 1
        }),
        H("Clk_grpuin", {
            obj1: b
        })) : 2 === c ? (mqq.ui.showProfile({
            uin: b
        }),
        H("Clk_uin")) : 3 === c && ActionSheet.show({
            items: ["加入群组", "加为好友"],
            onItemClick: function(a) {
                0 === a ? (mqq.ui.showProfile({
                    uin: b,
                    uinType: 1
                }),
                H("Clk_joingrp", {
                    obj1: b
                })) : 1 === a && (mqq.ui.showProfile({
                    uin: b
                }),
                H("Clk_friend"))
            }
        })),
        "atvCreater" === a.attr("id") && K("Clk_create_uin")
    }
    function z(a) {
        if (!a.hasClass("disabled")) {
            if (a.hasClass("link")) {
                if (a.closest(".second-comment").length)
                    return;
                A(a)
            }
            Util.openUrl(a.attr("url"), !0, 2)
        }
    }
    function A(a) {
        if (a.hasClass("link-keyword")) {
            var b = a.data("bid")
              , c = a.text();
            b && H("Clk_keyword", {
                ver3: b,
                ver4: c
            })
        } else {
            var d = "";
            d = a.hasClass("add-group") ? 1 : a.hasClass("tribe") ? 2 : a.hasClass("post") ? 4 : 5,
            H("Clk_link", {
                ver3: d,
                ver4: 1
            })
        }
    }
    function B(a) {
        return a.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/)
    }
    function C() {
        mqq.addEventListener("qbrowserTitleBarClick", function() {
            var a = $("#js_detail_main")
              , b = a.scrollTop()
              , c = $("#js_detail_scroll_top");
            Util.scrollElTop(b, a, c)
        }),
        mqq.addEventListener("personel_edit_complete", function(b) {
            a.postData.uin.toString() === a.myuin && $(".user-avatar img").attr("src", b.headimgurl)
        })
    }
    function D() {
        Refresh && Refresh.init({
            dom: a.isIOS ? $("#js_detail_main")[0] : document.body,
            reload: function() {
                H("Clk_refresh"),
                Q.monitor(475347);
                var b = 1;
                return a.Comment.refresh(0, function() {
                    b && (Refresh.hide(),
                    pollRefreshUi.reset(),
                    b = 0)
                }),
                H("visit", {
                    obj1: G,
                    ver3: a.postType,
                    ver4: a.gid,
                    ver5: 2
                }),
                window.setTimeout(function() {
                    Refresh.hide()
                }, 1e4),
                !0
            },
            usingPollRefresh: 1
        })
    }
    function E() {
        J = a.postData,
        I = a.postType,
        b(),
        C(),
        t(),
        d(),
        MediaPlayer.attachPlayer("detail_body"),
        MediaPlayer.checkMusicState("detail_body"),
        D()
    }
    var F = a.bid
      , G = a.pid
      , H = a.report
      , I = 0
      , J = {}
      , K = a.openActReport
      , L = window.BARTYPE.BARCLASS
      , M = "http://pub.idqqimg.com/qqun/xiaoqu/mobile"
      , N = !1
      , O = {
        tribe: M + "/img/share/tribe.png",
        poster: M + "/img/share/poster.png",
        asc: M + "/img/share/asc.png",
        desc: M + "/img/share/desc.png",
        report: M + "/img/share/report.png",
        best: M + "/img/share/best.png",
        unbest: M + "/img/share/unbest.png",
        join: M + "/img/share/join.png",
        unjoin: M + "/img/share/unjoin.png",
        fav: M + "/img/share/fav.png",
        addphoto: M + "/img/share/addphoto.png",
        copy: M + "/img/share/copy.png",
        sticky: M + "/img/share/sticky.png"
    };
    return {
        init: E,
        show: e,
        postSticky: o
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Join = b(c)
}(this, function(a) {
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
        if (o.isOpenAct && window.mqq && mqq.dispatchEvent) {
            var b = {
                id: o.post.openact_id,
                bid: q,
                pid: r,
                name: o.title,
                loc_name: o.post.addr,
                cover: o.post.cover || o.post.pic_list[0].url,
                is_join: $(".bottom-bar").hasClass("has-joined") ? 1 : 0,
                enroll: parseInt($("#peopleNum").html()),
                start_time: e(o.post.start),
                end_time: e(o.post.end),
                flag: 1 + ("cancel" === a ? 32 : 0) + 384
            };
            mqq.dispatchEvent("avt_refresh_page", {
                type: "item_" + ("quit" === a ? "cancel" : "cancel" === a ? "close" : a),
                data: b
            }, {
                domains: ["*.qq.com"]
            })
        }
    }
    function g() {
        if (o) {
            var b, e = $("#join_activity_win"), f = e.children(), g = $("#join_activity_win_post");
            u && $.os.ios && (b = document.querySelector("video"),
            b && (b.style.display = "none")),
            p || (o && 0 === o.role && e.addClass("join-group"),
            e.on("tap", function(c) {
                c.preventDefault(),
                c.stopPropagation();
                var g = c.target;
                "joinActivityReply" === g.id ? (e.hide(),
                b && (b.style.display = "block"),
                d(),
                setTimeout(function() {
                    a.Publish.reply()
                }, 100),
                s(u ? "Clk_discussvideo_suc" : "Clk_discuss_suc")) : "joinActivityFinish" === g.id ? (e.hide(),
                b && (b.style.display = "block"),
                d()) : "joinActivityGroup" === g.id ? (DB.cgiHttp({
                    url: "http://qqweb.qq.com/cgi-bin/qqactivity/apply_join_group",
                    type: "POST",
                    param: {
                        id: o.post.openact_id,
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
                }),
                e.hide(),
                b && (b.style.display = "block"),
                d()) : "shareActivity" === g.id ? (a.Share.callHandler(u ? "" : "我参加了“" + $.str.decodeHtml(o.title) + "”，感兴趣就一起来吧！"),
                s(u ? "Clk_sharevideo_suc" : "Clk_share_suc", {
                    obj1: r
                })) : f[0].contains(g) || (e.hide(),
                b && (b.style.display = "block"),
                d())
            }),
            p = !0),
            $("#join_activity_win_name").html(plain2rich(o.title) || ""),
            $("#join_activity_win_time").text(o.post.time || ""),
            u && o.post.image1 ? g.children().attr("src", o.post.image1) : !u && o.post.pic_list && o.post.pic_list[0] && o.post.pic_list[0].url ? g.children().attr("src", o.post.pic_list[0].url) : (g.hide(),
            d()),
            e.show(),
            c()
        }
    }
    function h() {
        DB.cgiHttp({
            url: "/cgi-bin/bar/post/join",
            type: "POST",
            param: {
                bid: q,
                pid: r
            },
            succ: function(b) {
                var c = ~~b.retcode
                  , d = 0;
                if (b.result && (d = ~~b.result.errCode),
                (190 !== d && A || 190 === d && y === z && A) && ($(".alert").hide(),
                y = 0,
                A = !1,
                clearTimeout(B),
                B = null ),
                0 === c)
                    if (0 === d) {
                        $(".bottom-bar").addClass("has-joined"),
                        100 === a.postType && $("#js_detail_main").addClass("no-bottom"),
                        g(),
                        o.is_joined = 1,
                        w(o);
                        var e = $("#peopleNum")
                          , i = $("#js-peopleNum-cover")
                          , j = $("<img />")
                          , k = parseInt(e.html() || 0) + 1;
                        j.addClass("u" + t).addClass("user-avatar").attr("data-uin", t).attr("src", "http://q.qlogo.cn/g?b=qq&nk=0" + t + "&s=100").prependTo("#people_header_list"),
                        e.html(k),
                        i.html(k),
                        $("#js_act_people").show(),
                        o.isOpenAct && v(435887),
                        f("join")
                    } else
                        190 === d ? z > y && (A || (Alert.show("", "报名抢注中，请稍候~", {
                            confirm: "取消等待",
                            callback: function() {
                                clearTimeout(B),
                                B = null ,
                                y = 0
                            }
                        }),
                        A = !0),
                        B = setTimeout(h, 1e3 * ~~b.result.delayTime),
                        y++) : Tip.show("报名失败，请稍后重试", {
                            type: "warning"
                        });
                else
                    Tip.show("报名失败，请稍后重试", {
                        type: "warning"
                    })
            },
            err: function(a) {
                var b = ~~a.retcode;
                o.isOpenAct && v(435935),
                A && ($(".alert").hide(),
                y = 0,
                A = !1,
                clearTimeout(B),
                B = null ),
                99999 === b ? Tip.show("操作过于频繁，请稍后重试", {
                    type: "warning"
                }) : Tip.show("报名失败，请稍后重试", {
                    type: "warning"
                })
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
                        bid: q,
                        pid: r
                    },
                    succ: function() {
                        $(".bottom-bar").removeClass("has-joined");
                        var a = ""
                          , b = $("#peopleNum")
                          , c = $("#js-peopleNum-cover")
                          , d = $("#people_header_list .u" + t)
                          , e = parseInt(b.html()) - 1;
                        a = 10055 === q || 10064 === q || 10210 === q ? "预约" : "报名",
                        Tip.show("取消" + a + "成功！", {
                            type: "warning"
                        }),
                        o.is_joined = 0,
                        w(o),
                        d.length && d.remove(),
                        e = 0 > e ? 0 : e,
                        b.html(e),
                        c.html(e),
                        !e && $("#js_act_people").hide(),
                        o.isOpenAct && (v("Clk_quit_suc4open"),
                        v(435888)),
                        f("quit")
                    },
                    err: function() {
                        o.isOpenAct && v(435936)
                    }
                }),
                s(u ? "Clk_unvideo" : "Clk_unactivity")
            }
        })
    }
    function j() {
        C.show()
    }
    function k() {
        C.hide()
    }
    function l(b) {
        o = b,
        u = 200 === a.postType ? 1 : 0
    }
    function m(a) {
        if (a.joined_ul) {
            $("#peopleNum").html(a.joined_ul.uinnum || 0),
            $("#js-peopleNum-cover").html(a.joined_ul.uinnum || 0);
            var b, c, d, e = [], f = a.joined_ul.uins.concat(), g = f.length, h = Math.min(10, g), i = g > 100;
            for (1 === a.is_joined && (e.push('<img class="u' + t + ' user-avatar" data-ban="0" data-uin="' + t + '"  src="http://q.qlogo.cn/g?b=qq&nk=' + t + "&s=100&t=" + Date.now() + '" />'),
            h--),
            c = 0; h > c; c++)
                d = i ? Math.floor(Math.random() * g--) : 0,
                b = f.splice(d, 1)[0],
                b.uin + "" !== t ? (b.ban = 0,
                a.joined_ul.isAtvCGI && (b.ban = b.status,
                1 === b.ban ? b.url = "http://q.qlogo.cn/g?b=qq&nk=1&s=100" : b.url = "http://q.qlogo.cn/g?b=qq&nk=" + b.uin + "&s=100&t=" + Date.now()),
                e.push('<img class="u' + b.uin + ' user-avatar" data-ban="' + b.ban + '" data-uin="' + b.uin + '"  src="' + b.url + '" />')) : c--;
            $("#people_header_list").html(e.join("")),
            e.length && $("#js_act_people").show()
        }
    }
    function n(a) {
        if (Date.now() - a < 5e3)
            return Tip.show("加群操作频率过高，请稍候再试"),
            Date.now();
        var b = o.post.openact_id
          , c = b.split("_")[0];
        return Q.monitor(2054649),
        x("Clk_openac_joingrp_click", {
            module: "detail",
            obj1: c
        }),
        DB.cgiHttp({
            url: "http://qqweb.qq.com/cgi-bin/qqactivity/apply_join_group",
            ssoCmd: "QunActJoinGroup",
            param: {
                id: b,
                type: 1,
                client: $.os.ios ? 2 : 3,
                client_sub: 27
            },
            succ: function(a) {
                if (console.log("QunActJoinGroup", a),
                a)
                    if (0 === a.ec) {
                        Tip.show("加群成功");
                        var b = $("#js-qunact-comment").attr("code");
                        b && mqq.ui.openAIO({
                            uin: b,
                            chat_type: "group"
                        })
                    } else
                        Tip.show("加群失败");
                x("Clk_openac_joingrp", {
                    module: "detail",
                    obj1: c
                })
            },
            err: function(a) {
                if (console.error("QunActJoinGroup", a),
                a = a || {},
                4 === a.ec || 20003 === a.ec)
                    Tip.show("加群操作频率过高，请稍候再试");
                else if (20001 === a.ec)
                    Tip.show("该群需要审核,请等待处理"),
                    x("Clk_openac_joingrp", {
                        module: "detail",
                        obj1: c
                    });
                else {
                    var b = "";
                    switch (a.ec) {
                    case 20002:
                        b = "该群不允许任何人加入";
                        break;
                    case 20004:
                        b = "非法的群号码";
                        break;
                    case 20005:
                        b = "群不存在或者群被删除";
                        break;
                    case 20006:
                        b = "该群因系统设置原因，不准任何人加入";
                        break;
                    default:
                        b = "发送申请失败"
                    }
                    Tip.show(b + "[" + JSON.stringify(a) + "]"),
                    Q.monitor(2054650),
                    x("Clk_openac_joingrp_fail", {
                        module: "detail",
                        obj1: c,
                        ver3: a.ec || -1
                    })
                }
            }
        }),
        Date.now()
    }
    var o, p, q = a.bid, r = a.pid, s = a.report, t = a.myuin, u = 0, v = a.openActReport, w = a.setOpenActStatus, x = a.openActNewReport, y = 0, z = 2, A = !1, B = null , C = {
        next: 0,
        start: 0,
        scrollDomWin: null ,
        numPerPage: 20,
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
            $.os.ios ? (bouncefix.add("people-join-win"),
            b = this.$win) : b = $(document),
            this.scrollDom = b;
            var e = $(document).width()
              , f = 768 > e ? 4 : 8
              , g = Math.floor((e - 54 - 56 * f) / (f - 1))
              , h = document.styleSheets[document.styleSheets.length - 1];
            a(".people-win .face", "margin-right:" + g + "px;"),
            a(".people-win .face:nth-child(" + f + "n)", "margin-right:27px;"),
            $(window).on("hashchange", function() {
                -1 === location.hash.indexOf("peoplejoined") && d.hide()
            }),
            this.scrollDom.on("scroll", function(a) {
                c && window.clearTimeout(c),
                c = window.setTimeout(function() {
                    d.onScroll(a)
                }, 100)
            }),
            $(d.$win).on("tap", function(a) {
                var b = $(a.target).closest("li");
                if (0 !== b.length) {
                    var c = b.data("uin")
                      , d = 1 === b.data("ban");
                    if (o.isOpenAct) {
                        if (d)
                            return;
                        mqq.ui.showProfile({
                            uin: c
                        }),
                        v("Clk_uindata")
                    } else
                        Util.openUrl("http://xiaoqu.qq.com/mobile/personal.html#_wv=16777219&uin=" + c, 1)
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
            if (this.isend || this.isLoading)
                return !1;
            var a = document.documentElement.clientHeight
              , b = this.scrollDom[0] === document ? document.body : this.$win[0]
              , c = b.scrollTop
              , d = b.scrollHeight;
            c + 2 * a + 20 >= d && this.getList()
        },
        render: function(a) {
            Tmpl(window.TmplInline_detail.join_list, {
                list: a,
                myuin: t
            }).appendTo(this.$list)
        },
        getList: function(a) {
            function b(a, b, c) {
                a.render(b.uins),
                a.start += a.numPerPage,
                a.isEnd = b.isend || 0,
                c && c(b),
                a.isLoading = !1,
                a.isEnd && a.$loading.html(""),
                e || (e = a.scrollDomWin = a.scrollDom[0] === document ? document.body : a.$win[0]),
                e.scrollHeight + e.scrollTop <= document.documentElement.clientHeight && a.getList()
            }
            var c = this
              , d = !(!o.isOpenAct || !o.is_joined || 0 !== c.start)
              , e = c.scrollDomWin;
            if (!c.isLoading && !c.isEnd) {
                c.isLoading = !0,
                c.$loading.html("加载中，请稍候...");
                var f, g;
                o.isOpenAct ? (c.numPerPage = 48,
                f = "http://qqweb.qq.com/cgi-bin/qqactivity/get_activity_member_list",
                g = {
                    type: 1,
                    id: o.post.openact_id,
                    from: c.next,
                    number: d ? c.numPerPage - 1 : c.numPerPage,
                    flag: 0
                }) : (f = "/cgi-bin/bar/openact/users/list2",
                g = {
                    bid: q,
                    pid: r,
                    start: c.start,
                    num: d ? c.numPerPage - 1 : c.numPerPage
                });
                var h = {
                    url: f,
                    param: g,
                    succ: function(e) {
                        var f = {};
                        o.isOpenAct ? (f.uins = {},
                        c.next = e.next,
                        e.list ? (f.uinnum = e.count || e.list.length,
                        f.uins = e.list,
                        f.uins.isAtvCGI = !0) : 0 !== e.next || e.list || (f.isend = 1)) : f = e.result || {},
                        d && f.uins ? $.ajax({
                            url: "http://cgi.connect.qq.com/qqconnectopen/openapi/get_nick",
                            dataType: "jsonp",
                            success: function(d) {
                                f.uins.unshift({
                                    uin: t,
                                    nick_name: d.result.nick || "",
                                    pic: "http://q.qlogo.cn/g?b=qq&nk=" + t + "&s=100&t=" + Date.now()
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
                DB.cgiHttp(h)
            }
        },
        show: function() {
            this.hasInited || (this.init(),
            this.hasInited = !0),
            this.hasContent || (this.getList(),
            this.hasContent = !0);
            var a = this.$win;
            a.css("opacity", 0).css("background", "#F8F8F8").show(),
            setTimeout(function() {
                a.css("opacity", 1)
            }, 20),
            $.os.android && a.css("position", "fixed");
            var b = function() {
                $("#js_detail_main").hide(),
                $(".bottom-bar").hide(),
                $.os.ios ? a.css("background", "") : (document.body.scrollTop = 0,
                a.css("position", "static")),
                a.off("webkitTransitionEnd", b)
            }
            ;
            a.on("webkitTransitionEnd", b),
            location.hash = location.hash ? location.hash.replace(/peoplejoined/g, "") + "&peoplejoined" : "#peoplejoined",
            Refresh && Refresh.pauseTouchMove()
        },
        hide: function() {
            function a() {
                b.hide(),
                b.off("webkitTransitionEnd", a)
            }
            var b = this.$win;
            $.os.ios ? (b.css("background", "#F8F8F8"),
            b.on("webkitTransitionEnd", a)) : b.hide(),
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
        showListInPost: m,
        syncActivity: f,
        joinGroupBySSO: n
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Publish = b(c)
}(this, function(a) {
    function b(b, c, d, j) {
        Publish.init({
            isReply: !0,
            bid: e,
            flag: h,
            pid: f,
            ref_cid: b,
            ctxNode: $("#js_detail_main"),
            pubulishType: "reply",
            postType: g,
            nick_name: d,
            onhidden: function() {},
            succ: function(b) {
                a.Comment.afterReply(b);
                var d = $("#to_reply")
                  , e = Number(d.html()) || 0;
                d.html(e + 1),
                i("reply_suc"),
                "commentReply" === c && i("suc_reply_own"),
                "refReply" === c && i("suc_reply_layer"),
                101 === a.postType && a.openActNewReport("comment_suc")
            },
            cancel: function() {
                window.history.go(-1)
            },
            config: {
                isReply: !0,
                from: "detail",
                extparam: {
                    ref_cid: b,
                    floor: j || 0
                }
            }
        })
    }
    function c(c, d, f, g) {
        a.showLockTip() || (localStorage.getItem("pho_alert" + e) || 10364 !== e && 10679 !== e ? b(c, d, f, g) : Util.showStatement(function() {
            b(c, d, f, g)
        }, e))
    }
    function d(b, c) {
        if (!a.showLockTip()) {
            var d = $.trim(b);
            return d.length > 200 ? (Tip.show("输入字符请在200个字以内", {
                type: "warning"
            }),
            !1) : (Publish.init({
                isReply: !0,
                bid: e,
                pid: f,
                pubulishType: "reply",
                postType: g,
                preventDefaultUI: !0,
                config: {
                    minLength: 1,
                    maxLength: 200
                }
            }),
            Publish.sendData("", b, {
                callback: function(b) {
                    a.Comment.afterReply(b.result);
                    var c = $("#to_reply")
                      , d = Number(c.html()) || 0;
                    c.html(d + 1),
                    i("reply_suc"),
                    i("suc_reply_own"),
                    a.openActReport("pub_suc", {
                        module: "reply"
                    })
                },
                params: c.params
            }),
            !0)
        }
    }
    var e = a.bid
      , f = a.pid
      , g = a.postType
      , h = a.flag
      , i = a.report;
    return {
        reply: c,
        wordsReply: d
    }
}),
function(a, b) {
    var c = a.Detail;
    c.Share = b(c)
}(this, function(a) {
    function b(a) {
        var b, c = $.cookie("vkey"), d = ["share_qq", "share_qzone", "share_wechat", "share_circle", "share_weibo", "share_link"];
        c && (j.sid = c),
        p("Clk_share"),
        b = j,
        a && (b = $.extend({}, b, {
            content: a
        })),
        b.succHandler = function(a) {
            p(["qq_suc", "qzone_suc", "wechat_suc", "circle_suc"][a])
        }
        ,
        Util.shareMessage(b, function(a, b) {
            6 === b ? i() : 6 > b && (k.refresh(),
            p(d[b], {
                obj1: n
            }))
        })
    }
    function c(a, b) {
        var c = "http://buluo.qq.com/mobile/detail.html?_bid=128&_wv=1027&bid=" + m + "&pid=" + n
          , d = $.str.decodeHtml(rich2plain(a.title)).replace(/<(.|\n)+?>/gi, "")
          , e = $.str.decodeHtml(rich2plain(a.post.content, a.post.urlInfo)).replace(/<(.|\n)+?>/gi, "");
        900 === a.type && (d += "——兴趣部落"),
        j = {
            shareUrl: c,
            pageUrl: c,
            imageUrl: "",
            title: d,
            content: e || d
        };
        var f = "";
        200 !== o && 201 !== o || !a.post.image1 ? a.post.pic_list && a.post.pic_list[0] && (f = a.post.pic_list[0].url ? a.post.pic_list[0].url : a.post.pic_list[0],
        j.imageInfo = {
            bid: m,
            pic: a.post.pic_list[0]
        }) : f = a.post.image1,
        f = f || a.bar_pic,
        j.imageUrl = imgHandle.getThumbUrl(f, 200),
        b && b()
    }
    function d() {
        var a = {
            mid: "callshare",
            img_url: j.imageUrl,
            link: j.shareUrl,
            desc: j.content,
            title: j.title
        };
        q.callHandler("callshare", a, function() {
            console.log("分享成功")
        })
    }
    function e() {
        var a = {
            link: j.shareUrl + "&from=wechat",
            title: j.title,
            desc: j.content,
            handleShareTimelineSuccess: function() {
                k.refresh(),
                p("share_circle", {
                    obj1: n
                })
            },
            handleShareAppMessageSuccess: function() {
                k.refresh(),
                p("share_wechat", {
                    obj1: n
                })
            }
        };
        a.imgUrl = j.imageUrl,
        j.imageInfo && j.imageInfo.url && (a.imgUrl = j.imageInfo.url),
        window.WechatShare.init(a)
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
        c(b, function() {
            return a.isYYB ? void f() : a.isWX ? void e() : void 0
        })
    }
    function h(a) {
        return q ? void d() : void b(a)
    }
    function i() {
        p("Clk_collect", {
            obj1: n,
            os: a.isIOS ? "ios" : "android"
        });
        var b = $(".js-detail-title").text()
          , c = "";
        if (100 === o || 101 === o)
            $("#actDetailImage")[0] ? c = $("#actDetailImage")[0].src : $(".tvp_poster_img")[0] && (c = $(".tvp_poster_img")[0].src);
        else if (200 === o || 201 === o)
            c = $(".tvp_poster_img")[0] && $(".tvp_poster_img")[0].src;
        else {
            var d = $("#detail_top_info").find(".img-box img");
            d.length > 0 && d[0].src ? c = d[0].src : $(".tvp_poster_img")[0] && (c = $(".tvp_poster_img")[0].src)
        }
        c = c || "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/recommend-blue-icon.png",
        DB.cgiHttp({
            url: "/cgi-bin/bar/extra/add_mqq_fave",
            type: "POST",
            ssoCmd: "add_mqq_fave",
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
    var j, k, l = cgiModel, m = a.bid, n = a.pid, o = a.postType, p = a.report, q = null ;
    return document.addEventListener("WebViewJavascriptBridgeReady", function(a) {
        a = a || window.event,
        q = a.bridge,
        q.init()
    }),
    k = new l({
        cgiName: "/cgi-bin/bar/extra/share_add",
        ssoCmd: "share_add",
        param: {
            bid: m,
            pid: n
        }
    }),
    window.pmdCampusShare = function() {
        PmdCampus.shareMessage({
            title: j.title,
            desc: j.content,
            share_url: j.shareUrl,
            image_url: j.imageUrl
        })
    }
    ,
    {
        init: g,
        callHandler: h
    }
});
