!function(a, b) {
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
});