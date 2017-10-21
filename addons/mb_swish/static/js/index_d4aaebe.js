!function() {
    function e(e, o, l, a, s, d, g) {
        function m(i) {
            var t = i.error - 0;
            switch (t) {
                case 1002:
                    e.clear(),
                        alert("你的身份信息已过期，点击确定刷新页面"),
                        window.location.reload();
                    break;
                default:
                    alert(i.error_msg)
            }
        }
        function u(e, i, t) {
            a(e, i, config.gameid,
                function(i) {
                    console.log(e + ":" + JSON.stringify(i));
                    var n = i.error - 0;
                    switch (n) {
                        case 0:
                            t && t(i);
                            break;
                        default:
                            m(i)
                    }
                },
                config.apiopenid, config.apitoken)
        }
        function b(e, i) {
            g.initWx(e, config.gameid, config.apiopenid, config.apitoken, i, null, null, null)
        }
        function h(e) {
            e && u("sayhello/send", {
                    isupload: 0,
                    type: t[e],
                    mediaid: null
                },
                function(e) {
                    console.log("sharelog: " + e.type)
                })
        }
        var r, f, v, w, _, x = {},
            y = {},
            z = {},
            I = {},
            k = {},
            B = navigator.userAgent,
            P = (B.indexOf("Android") > -1 || B.indexOf("Adr") > -1, !!B.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/));
            window.oAudio0 = document.getElementById("audio0"),
            window.oAudio1 = document.getElementById("audio1"),
            window.oAudio2 = document.getElementById("audio2"),
            window.oAudio3 = document.getElementById("audio3"),
            window.oAudio4 = document.getElementById("audio4");
        var j = [oAudio0, oAudio1, oAudio2, oAudio3, oAudio4];
        x.act = {
            $dom: d("#indexPage"),
            init: function() {
                var e = this,
                    i = d("body").height();
                d("#main").css("height", i),
                    d("#main").show(),
                    d("#loadPage").show();
                var t = 0,
                    c = n.length;
                s.load(n,
                    function() {
                        b(config.shareInfo),
                            e.$dom.show(),
                            e.checkTouch(),
                            d("#loadPage").hide()
                    },
                    function() {
                        t++;
                        var e = parseInt(t / c * 100);
                        d(".percent span").text(e),
                            d(".process div").css("width", e + "%")
                    })
            },
            checkTouch: function() {
                var e = this;
                d("#startBtn").on(config.touch,
                    function() {
                        if(mbCore.subscribeUrl) {
                            gotoUrl(mbCore.subscribeUrl);
                        } else {
                            y.act.init(), e.out()
                        }
                    })
            },
            out: function() {
                this.$dom.hide(),
                    d("#startBtn").off()
            }
        },
            y.act = {
                $dom: d("#choosePage"),
                id: 0,
                myInterval: null,
                init: function() {
                    var e = this;
                    l.loading(1, 2),
                        e.shareLink(),
                        b(config.shareInfo,
                            function() {
                                h(e.id)
                            }),
                        e.checkTouch(),
                        l.loadingHide(),
                        e.$dom.show();
                    var i = new Swiper(".swiper-container", {
                        loop: !0
                    });
                    d(".arrow-left").on("click",
                        function(t) {
                            t.preventDefault(),
                                i.swipePrev(),
                                e.slideChange()
                        }),
                        d(".arrow-right").on("click",
                            function(t) {
                                t.preventDefault(),
                                    i.swipeNext(),
                                    e.slideChange()
                            })
                },
                shareLink: function() {
                    var e = this;
                    config.shareInfo.link = config.htmlUrl + "&id=" + e.id
                },
                slideChange: function() {
                    var e = this;
                    for (e.id = d(".swiper-slide-active").attr("data-id"), d(".language").attr("src", c[e.id]), d(".txt").attr("src", p[e.id]), d(".voice img").attr("src", mbCore.resourceUrl + "/static/images/yy0_ec15246.png"), i = 0; 5 > i; i++) j[i].currentTime = 0,
                        j[i].pause();
                    e.shareLink(),
                        b(config.shareInfo,
                            function() {
                                h(e.id)
                            })
                },
                checkTouch: function() {
                    var e = this;
                    d("#voice").on(config.touch,
                        function() {
                            j[e.id].paused ? (j[e.id].play(), d(".voice img").attr("src", mbCore.resourceUrl + "/static/images/yy_cb3ba10.gif"), e.myInterval = setInterval(function() {
                                    j[e.id].ended && (d(".voice img").attr("src", mbCore.resourceUrl + "/static/images/yy0_ec15246.png"), clearInterval(e.myInterval))
                                },
                                500)) : (j[e.id].pause(), d(".voice img").attr("src", mbCore.resourceUrl + "/static/images/yy0_ec15246.png"))
                        }),
                        d(".wh").on(config.touch,
                            function() {
                                d("#txtPage").show()
                            }),
                        d(".close").on(config.touch,
                            function() {
                                d("#txtPage").hide()
                            }),
                        d("#shareBtn").on(config.touch,
                            function() {
                                d("#sharePage").show()
                            }),
                        d("#sharePage").on(config.touch,
                            function() {
                                d("#sharePage").hide()
                            }),
                        d("#meBtn").on(config.touch,
                            function() {
                                z.act.init(),
                                    e.out()
                            })
                },
                out: function() {
                    var e = this;
                    for (this.$dom.hide(), d("#voice").off(), d(".wh").off(), d(".close").off(), d("#shareBtn").off(), d("#sharePage").off(), d("#meBtn").off(), clearInterval(e.myInterval), i = 0; 5 > i; i++) j[i].currentTime = 0,
                        j[i].pause()
                }
            },
            z.act = {
                $dom: d("#lyPage"),
                myInterval: null,
                interval: null,
                time: 0,
                init: function() {
                    var e = this;
                    config.shareInfo.link = config.htmlUrl,
                        b(config.shareInfo),
                        e.checkTouch(),
                        e.time = 0,
                        d("#sec").text("00"),
                        d("#min").text("00"),
                        d(".ly").attr("src", mbCore.resourceUrl + "/static/images/ly_4c5da6b.png"),
                        d(".tt1").attr("src", mbCore.resourceUrl + "/static/images/f_ly_6904ee7.png"),
                        d("#record img").attr("src", mbCore.resourceUrl + "/static/images/lyan_83af0e5.png"),
                        e.$dom.show()
                },
                checkTouch: function() {
                    var e = this,
                        i = !0;
                    d("#record").on(config.touch,
                        function() {
                            i ? (i = !1, d(".tt1").attr("src", mbCore.resourceUrl + "/static/images/f_zt_ce0e419.png"), d("#record img").attr("src", mbCore.resourceUrl + "/static/images/zt_f361012.png"), d(".ly").attr("src", mbCore.resourceUrl + "/static/images/yinyue_3a2df0b.gif"), g.startRecord(), e.myInterval = setInterval(function() {
                                    e.time++,
                                    e.time < 60 && d("#sec").text(e.time <= 9 ? "0" + e.time: e.time)
                                },
                                1e3)) : (i = !0, l.loading(1, 2), d(".ly").attr("src", mbCore.resourceUrl + "/static/images/ly_4c5da6b.png"), d(".tt1").attr("src", mbCore.resourceUrl + "/static/images/f_ly_6904ee7.png"), d("#record img").attr("src", mbCore.resourceUrl + "/static/images/lyan_83af0e5.png"), clearInterval(e.myInterval), clearInterval(e.interval), g.stopRecord(function(i) {
                                r = i.localId,
                                    g.uploadVoice(r,
                                        function(i) {
                                            f = i.serverId,
                                                I.act.init(),
                                                e.out()
                                        })
                            }))
                        }),
                        e.interval = setInterval(function() {
                                var t = parseInt(d("#sec").text());
                                t >= 59 && (i = !0, l.loading(1, 2), d(".ly").attr("src", mbCore.resourceUrl + "/static/images/ly_4c5da6b.png"), d(".tt1").attr("src", mbCore.resourceUrl + "/static/images/f_ly_6904ee7.png"), d("#record img").attr("src", mbCore.resourceUrl + "/static/images/lyan_83af0e5.png"), g.stopRecord(function(i) {
                                    r = i.localId,
                                        g.uploadVoice(r,
                                            function(i) {
                                                f = i.serverId,
                                                    I.act.init(),
                                                    e.out()
                                            })
                                }))
                            },
                            1e3),
                        d("#back").on(config.touch,
                            function() {
                                y.act.init(),
                                    clearInterval(e.myInterval),
                                    clearInterval(e.interval),
                                    e.out(),
                                    g.stopRecord(function(e) {
                                        r = e.localId
                                    })
                            })
                },
                out: function() {
                    this.$dom.hide(),
                        d("#record").off(),
                        d("#back").off()
                }
            },
            I.act = {
                $dom: d("#successPage"),
                init: function() {
                    I.data.send()
                },
                checkTouch: function() {
                    var e = this,
                        i = !0;
                    d("#play").on(config.touch,
                        function() {
                            i ? (g.playVoice(r), d(".voice img").attr("src", mbCore.resourceUrl + "/static/images/yy_cb3ba10.gif"), i = !1) : (g.pauseVoice(r), d(".voice img").attr("src", mbCore.resourceUrl + "/static/images/yy0_ec15246.png"), i = !0)
                        }),
                        d("#fanhui").on(config.touch,
                            function() {
                                z.act.init(),
                                    d(".voice img").attr("src", mbCore.resourceUrl + "/static/images/yy0_ec15246.png"),
                                    g.stopVoice(r),
                                    e.out()
                            }),
                        g.onVoicePlayEnd(function(e) {
                            r = e.localId,
                                d(".voice img").attr("src", mbCore.resourceUrl + "/static/images/yy0_ec15246.png"),
                                i = !0
                        }),
                        d("#sendBtn").on(config.touch,
                            function() {
                                d("#sharePage").show()
                            }),
                        d("#sharePage").on(config.touch,
                            function() {
                                d("#sharePage").hide()
                            }),
                        d("#giftBtn").on(config.touch,
                            function() {
                                gotoUrl("https://wap.koudaitong.com/v2/showcase/goods?alias=3f3zt2r3jxugf")
                            })
                },
                sendCb: function(e) {
                    var i = this;
                    v = e.id,
                        config.shareInfo.link = config.htmlUrl + "&enid=" + v,
                        b(config.shareInfo),
                        g.downloadVoice(f,
                            function(e) {
                                r = e.localId,
                                    i.checkTouch(),
                                    i.$dom.show(),
                                    l.loadingHide()
                            })
                },
                out: function() {
                    this.$dom.hide(),
                        d("#play").off(),
                        d("#fanhui").off(),
                        d("#sendBtn").off(),
                        d("#sharePage").off(),
                        d("#giftBtn").off()
                }
            },
            I.data = {
                send: function() {
                    u("sayhello/send", {
                            isupload: 1,
                            type: null,
                            mediaid: f
                        },
                        function(e) {
                            I.act.sendCb(e)
                        })
                }
            },
            k.act = {
                $dom: d("#friendPage"),
                myInterval: null,
                init: function(e, i) {
                    var t = this,
                        c = d("body").height();
                    d("#main").css("height", c),
                        d("#main").show(),
                        d("#loadPage").show();
                    var p = 0,
                        o = n.length;
                    s.load(n,
                        function() {
                            config.shareInfo.link = config.htmlUrl,
                                b(config.shareInfo),
                                i ? k.data.get(e, i) : (j[e].currentTime = 0, j[e].paused = !0, t.checkTouch(e, i), t.$dom.show(), d("#loadPage").hide())
                        },
                        function() {
                            p++;
                            var e = parseInt(p / o * 100);
                            d(".percent span").text(e),
                                d(".process div").css("width", e + "%")
                        })
                },
                checkTouch: function(e, i) {
                    var t = this;
                    d("#playBtn").on(config.touch,
                        function() {
                            e && (j[e].paused ? (P && (console.log(P), j[e].load()), d("#playBtn").attr("src", mbCore.resourceUrl + "/static/images/zt_f361012.png"), j[e].play(), t.interval(j[e])) : (d("#playBtn").attr("src", mbCore.resourceUrl + "/static/images/bf_46f3043.png"), j[e].pause())),
                            i && (_.paused ? (P && (console.log(P), _.load()), d("#playBtn").attr("src", mbCore.resourceUrl + "/static/images/zt_f361012.png"), _.play(), t.interval(_)) : (d("#playBtn").attr("src", mbCore.resourceUrl + "/static/images/bf_46f3043.png"), _.pause()))
                        }),
                        d("#tryBtn").on(config.touch,
                            function() {
                                if(mbCore.subscribeUrl) {
                                    gotoUrl(mbCore.subscribeUrl);
                                } else {
                                    gotoUrl(mbCore.shareObject.url)
                                }
                            }),
                        d("#lbBtn").on(config.touch,
                            function() {
                                gotoUrl("https://wap.koudaitong.com/v2/showcase/goods?alias=3f3zt2r3jxugf")
                            })
                },
                interval: function(e) {
                    var i = this;
                    i.myInterval = setInterval(function() {
                            var t = parseInt(e.currentTime / e.duration * 100);
                            d(".yellow").css("width", t + "%"),
                                d(".white").css("width", 100 - t + "%"),
                            100 == t && (d("#playBtn").attr("src", mbCore.resourceUrl + "/static/images/bf_46f3043.png"), e.pause(), clearInterval(i.myInterval))
                        },
                        67)
                },
                getCb: function(e, i, t) {
                    var n = this;
                    w = e.mediaurl,
                        _ = document.createElement("AUDIO"),
                        _.setAttribute("src", w),
                        _.setAttribute("preload", "auto"),
                        _.style.display = "none",
                        _.style.width = "0",
                        _.style.height = "0",
                        document.getElementById("main").appendChild(_),
                        _.currentTime = 0,
                        _.paused = !0,
                        n.checkTouch(i, t),
                        n.$dom.show(),
                        d("#loadPage").hide()
                },
                out: function() {
                    var e = this;
                    e.$dom.hide(),
                        d("#playBtn").off(),
                        d("#tryBtn").off(),
                        d("#lbBtn").off()
                }
            },
            k.data = {
                get: function(e, i) {
                    u("sayhello/get", {
                            id: i
                        },
                        function(t) {
                            k.act.getCb(t, e, i)
                        })
                }
            },
            (function() {
                var e = o.getUrlParam().id,
                    i = o.getUrlParam().enid;
                e || i ? k.act.init(e, i) : x.act.init()
            })();
    }
    require([config.configUrl],
        function() {
            var i = ["auth", "MHJ", "loading", "ngapi", "imgpreload", "jquery", "wxshare_bff07c1", "swiper"];
            require(i, e)
        });
    var t = [4, 2, 0, 1, 3],
        n = [mbCore.resourceUrl + "/static/images/1db_5df3367.jpg", mbCore.resourceUrl + "/static/images/1sc_cad7c52.jpg", mbCore.resourceUrl + "/static/images/1tw_147288e.jpg", mbCore.resourceUrl + "/static/images/1wh_ee34d8c.jpg", mbCore.resourceUrl + "/static/images/1yy_4ef214b.jpg", mbCore.resourceUrl + "/static/images/b_fh_17963df.png", mbCore.resourceUrl + "/static/images/b_fx_87c1150.png", mbCore.resourceUrl + "/static/images/b_ks_864f8da.png", mbCore.resourceUrl + "/static/images/b_lb_c7d7407.png", mbCore.resourceUrl + "/static/images/b_lb1_9139a44.png", mbCore.resourceUrl + "/static/images/b_me_e3c8b0f.png", mbCore.resourceUrl + "/static/images/b_try_680a95d.png", mbCore.resourceUrl + "/static/images/b_zfx_e8b9cf9.png", mbCore.resourceUrl + "/static/images/bf_46f3043.png", mbCore.resourceUrl + "/static/images/bfjdt_fbb8c6d.png", mbCore.resourceUrl + "/static/images/bg_13e3b3c.jpg", mbCore.resourceUrl + "/static/images/close_b7e536e.png", mbCore.resourceUrl + "/static/images/cq_5a1a20f.png", mbCore.resourceUrl + "/static/images/djyysys_d3aed48.png", mbCore.resourceUrl + "/static/images/dsj_0c47275.png", mbCore.resourceUrl + "/static/images/f_db_ebd6acf.png", mbCore.resourceUrl + "/static/images/f_ly_6904ee7.png", mbCore.resourceUrl + "/static/images/f_sc_43b3d95.png", mbCore.resourceUrl + "/static/images/f_tw_9aa3566.png", mbCore.resourceUrl + "/static/images/f_wh_38cf7ae.png", mbCore.resourceUrl + "/static/images/f_yy_16e60ef.png", mbCore.resourceUrl + "/static/images/f_zt_ce0e419.png", mbCore.resourceUrl + "/static/images/fx_47546ed.png", mbCore.resourceUrl + "/static/images/fx_img_e82cb17.jpg", mbCore.resourceUrl + "/static/images/hsl1_1f38285.png", mbCore.resourceUrl + "/static/images/hz_dea3a89.png", mbCore.resourceUrl + "/static/images/jindu_a7ab440.png", mbCore.resourceUrl + "/static/images/jt1_95604e8.png", mbCore.resourceUrl + "/static/images/jt2_f73fd3c.png", mbCore.resourceUrl + "/static/images/k_94f4b52.png", mbCore.resourceUrl + "/static/images/lh_61ab320.png", mbCore.resourceUrl + "/static/images/loading1_dad9565.png", mbCore.resourceUrl + "/static/images/logo_1110c45.png", mbCore.resourceUrl + "/static/images/ly_4c5da6b.png", mbCore.resourceUrl + "/static/images/lyan_83af0e5.png", mbCore.resourceUrl + "/static/images/lzcg_2a4fdda.png", mbCore.resourceUrl + "/static/images/shouyinji_f80dea8.png", mbCore.resourceUrl + "/static/images/t_db_be032a1.png", mbCore.resourceUrl + "/static/images/t_sc_aacb2b0.png", mbCore.resourceUrl + "/static/images/t_tw_0346b6b.png", mbCore.resourceUrl + "/static/images/t_wh_f3e9785.png", mbCore.resourceUrl + "/static/images/t_yy_608670b.png", mbCore.resourceUrl + "/static/images/tt_ec091f9.png", mbCore.resourceUrl + "/static/images/tt1_0dd1aca.png", mbCore.resourceUrl + "/static/images/tt2_93447dc.png", mbCore.resourceUrl + "/static/images/wh_6fb0b95.png", mbCore.resourceUrl + "/static/images/yinyue_3a2df0b.gif", mbCore.resourceUrl + "/static/images/yy_cb3ba10.gif", mbCore.resourceUrl + "/static/images/yy_ebf13ba.png", mbCore.resourceUrl + "/static/images/yy0_ec15246.png", mbCore.resourceUrl + "/static/images/zfbbx_4ff4622.png", mbCore.resourceUrl + "/static/images/zt_f361012.png"],
        c = [mbCore.resourceUrl + "/static/images/f_yy_16e60ef.png", mbCore.resourceUrl + "/static/images/f_tw_9aa3566.png", mbCore.resourceUrl + "/static/images/f_db_ebd6acf.png", mbCore.resourceUrl + "/static/images/f_sc_43b3d95.png", mbCore.resourceUrl + "/static/images/f_wh_38cf7ae.png"],
        p = [mbCore.resourceUrl + "/static/images/t_yy_608670b.png", mbCore.resourceUrl + "/static/images/t_tw_0346b6b.png", mbCore.resourceUrl + "/static/images/t_db_be032a1.png", mbCore.resourceUrl + "/static/images/t_sc_aacb2b0.png", mbCore.resourceUrl + "/static/images/t_wh_f3e9785.png"]
} ();