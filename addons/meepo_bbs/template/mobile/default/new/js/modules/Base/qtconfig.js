!function() {
    $(document).on("tap", ".js-user-info,.user_avatar,.user-avatar,.user-nick", function(a) {
        var b, c, d = $(this), e = d.data("profile-uin"), f = $(a.target), g = d.parents("li").attr("data-lz");
        if ((!f.hasClass("honour") || !f.data("url")) && !f.hasClass("prevent_default") && e) {
            if ((e + "").match(/\*/))
                return;
            var h = /([^/]+)\.html?\?/.exec(window.location.href);
            h = h ? h[1] : "other";
            var i = function(a) {
                return Util.queryString(a) || Util.getHash(a)
            }
            ;
            b = i("pid"),
            c = i("bid"),
            g ? Q.tdw({
                opername: "Grp_tribe",
                module: "post_detail",
                action: "Clk_head_reply",
                obj1: b,
                ver1: c,
                ver3: e,
                ver4: g
            }) : Q.tdw({
                opername: "Grp_tribe",
                module: "post_detail",
                action: "Clk_head_pub",
                obj1: b,
                ver1: c,
                ver3: e
            }),
            window.ViewPreloader && mqq.compare("5.0") >= 0 && $.os.android ? (window.ViewPreloader.open({
                url: "http://xiaoqu.qq.com/mobile/personal.html?_wv=16777219&uin=" + e + "#scene=" + h,
                cgiMap: {
                    personal_top_data: {
                        cgi: "/cgi-bin/bar/card/merge_top",
                        param: {
                            targetuin: e
                        }
                    }
                }
            }),
            Q.monitor(507850)) : Util.openUrl("http://xiaoqu.qq.com/mobile/personal.html#_wv=16777219&uin=" + e + "&scene=" + h, 1)
        }
    }).on("tap", ".openGroup", function() {
        var a = $(this).data("groupcode")
          , b = {
            uinType: 1,
            uin: a
        };
        $(this).hasClass("booked-tribes") && (Q.tdw({
            opername: "Grp_tribe",
            module: "post_detail",
            action: "Clk_grpsign",
            obj1: a,
            uin: Login.getUin()
        }),
        b.wSourceSubID = 29),
        mqq && mqq.ui.showProfile(b)
    }),
    Config.isOffline ? Q.monitor(419380, !0) : Q.monitor(419379, !0),
    $.os.android && parseFloat($.os.version) < 4 && Q.monitor(459356),
    mqq.QQVersion > 0 && mqq.compare("4.7.2") < 0 && Q.monitor(617537),
    mqq.compare("4.7.2") > -1 && mqq.compare("5.2") < 0 && (Q.monitor(507835),
    Q.tdw({
        opername: "tribe_cgi",
        module: "secure",
        action: "login_5",
        ver3: Login.getUin()
    })),
    $.os.ios ? ($("html,body").addClass("ios"),
    $.os.version >= "9" && $("html,body").addClass("ios9")) : $.os.android && $("html,body").addClass("android"),
    setTimeout(function() {
        mqq && mqq.offline && mqq.offline.isCached({
            bid: 128
        }, function(a) {
            if (-1 !== a) {
                Config.offlineVersion = a;
                var b, c = {
                    opername: "Grp_tribe",
                    module: "tech_data",
                    action: "offline_data",
                    ver1: a
                };
                b = $.os.ios ? "ios" : $.os.android ? "android" : "other",
                c.ver3 = b,
                "other" !== b && (c.ver4 = $.os.version),
                Q.tdw(c)
            }
        }),
        ($.os.ios || $.os.android) && mqq.compare("4.6") >= 0 && mqq.data.getPageLoadStamp(function(a) {
            var b;
            if (0 === a.ret) {
                var c = {
                    ios_520: 2,
                    ios_521: 3,
                    ios_530: 4,
                    ios_532: 5,
                    and_520: 6,
                    and_521: 7,
                    and_530: 8,
                    and_531: 9,
                    and_532: 10,
                    ios_540: 11,
                    and_541: 12,
                    ios_551: 13,
                    and_551: 14
                }
                  , d = mqq.QQVersion.split(".");
                Q.isd(7832, 47, 3, a.startLoadUrlTime - a.onCreateTime, 1),
                b = ($.os.ios ? "ios_" : "and_") + d[0] + d[1] + d[2],
                c[b] && Q.isd(7832, 47, 3, a.startLoadUrlTime - a.onCreateTime, c[b])
            }
        })
    }, 1e3),
    mqq && mqq.ui && mqq.ui.setLoading && mqq.ui.setLoading({
        visible: !1
    }),
    $.os.ios && mqq.ui.setRightDragToGoBackParams({
        enable: !0,
        width: window.innerWidth
    });
    var a = navigator.userAgent.match(/\/qqdownloader\/(\d+)?/);
    if (a && YybJsBridge._call("setWebView", {
        toolbar: 0
    }),
    mqq.compare("5.8") > -1 && mqq.invoke("ui", "disableLongPress", {
        enable: !0
    }),
    !window.IsPersonalPage && mqq.ui.setWebViewBehavior && mqq.ui.setWebViewBehavior({
        bottomBar: !1
    }),
    mqq.compare("5.5") > 0) {
        var b = function(a) {
            if (a.time < 0)
                return void Q.monitor(468243);
            if (a.time > 36e5)
                return void Q.monitor(468244);
            if ("detail" === a.key)
                Q.tdw({
                    opername: "Grp_tribe",
                    module: "visit_time",
                    action: "post",
                    obj1: "3",
                    ver3: a.time,
                    ver5: a.pid,
                    ver6: a.bid
                }, !0),
                Q.tdw({
                    opername: "Grp_tribe",
                    module: "visit_time",
                    action: "entry",
                    ver3: a.time,
                    ver5: a.pid,
                    ver6: a.bid,
                    ver7: "post_detail"
                }, !0);
            else {
                var b = {
                    opername: "Grp_tribe",
                    module: "visit_time",
                    action: "entry",
                    ver3: a.time
                };
                $.extend(b, c({
                    lstab: a.lastab
                })),
                Q.tdw(b, !0)
            }
        }
          , c = function(a) {
            var b, c = {
                ".discover": "tab_dyn",
                ".recentVisited": "tab_recent",
                ".rank": "tab_cla",
                ".find": "tab_find",
                barindex: "tribe_hp",
                find: "dis_page",
                search: "search",
                card_host: "card_host",
                card_guest: "card_guest",
                other: "other"
            }, d = window.location.pathname, e = a.lstab || window.lastSelectTab;
            return b = d.indexOf("/index.html") > -1 ? {
                ver7: c[e]
            } : d.indexOf("/barindex.html") > -1 ? {
                ver7: c.barindex,
                ver6: Util.queryString("bid") || Util.getHash("bid")
            } : d.indexOf("/find.html") > -1 ? {
                ver7: c.find
            } : d.indexOf("/search_result.html") > -1 ? {
                ver7: c.search
            } : d.indexOf("/personal.html") > -1 ? window.personIsHost ? {
                ver7: c.card_host
            } : {
                ver7: c.card_guest
            } : {
                ver7: c.other
            }
        }
        ;
        $(window).on("reportIndex", function(a) {
            a._args && b({
                time: (new Date).getTime() - window.ds,
                lstab: a._args[0]
            }),
            window.ds = (new Date).getTime()
        }),
        window.location.pathname.indexOf("detail.html") > -1 && (window.detailds = (new Date).getTime()),
        window.ds = (new Date).getTime(),
        mqq.addEventListener("qbrowserVisibilityChange", function(a) {
            if (a.hidden === window.lastStatus)
                return void (window.lastStatus = !a.hidden);
            if (window.lastStatus = a.hidden,
            window.location.pathname.indexOf("detail.html") > -1) {
                var c = Util.queryString("bid") || Util.getHash("bid")
                  , d = Util.queryString("pid") || Util.getHash("pid");
                a.hidden === !0 ? b({
                    key: "detail",
                    pid: d,
                    bid: c,
                    time: (new Date).getTime() - window.detailds
                }) : window.detailds = (new Date).getTime()
            } else
                a.hidden === !0 ? window.useImage || window.usePublish || b({
                    time: (new Date).getTime() - window.ds
                }) : (window.useImage || window.usePublish || (window.ds = (new Date).getTime()),
                window.useImage = !1,
                window.usePublish = !1)
        })
    }
    window.qtconfig = function() {}
}();