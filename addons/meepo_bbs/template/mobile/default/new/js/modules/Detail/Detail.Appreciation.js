!function(a, b) {
    var c = a.Detail;
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
});