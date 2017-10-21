!function(a, b) {
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
        (10055 === u || 10064 === u || 10210 === u) && (b.gameAct = !0,
        $("#to_join").html("预约"));
        var c = ~~a.getParam("lnum");
        c > 20 && $("#top_post_wrapper").hide(),
        b.bar_class === z.qunSubscription && (a.isOfficialAccountTribe = b.isOfficialAccountTribe = !0,
        b.public_number && (q = b.public_number.toString()))
    }
    function d(b, c) {
        var d = b.type
          , g = f;
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
            domains: ["*.qq.com"]
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
})