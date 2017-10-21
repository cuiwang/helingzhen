!function(a, b) {
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
});