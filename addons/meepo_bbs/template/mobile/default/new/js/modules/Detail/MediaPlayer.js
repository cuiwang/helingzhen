!function(a, b) {
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
});