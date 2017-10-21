!function(a, b) {
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
});