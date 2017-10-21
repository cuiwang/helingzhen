!function(a, b) {
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
});