!function(a, b) {
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
})