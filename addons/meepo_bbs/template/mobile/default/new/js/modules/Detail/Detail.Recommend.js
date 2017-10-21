!function(a, b) {
    var c = a.Detail;
    c.Recommend = b(c)
}(this, function(a) {
    function b(b, c) {
        c.addClass("active"),
        setTimeout(function() {
            c.removeClass("active"),
            "barindex" === a.source && "0" !== mqq.QQVersion ? mqq.ui.popBack() : Util.openUrl(a.base + "barindex.html#bid=" + b + "&scene=detail_recommend", !0)
        }, 100)
    }
    function c(a, b, c) {
        c.addClass("read active"),
        setTimeout(function() {
            c.removeClass("active"),
            Util.openDetail({
                "#bid": a,
                "#pid": b
            }, !1, c.data("type"))
        }, 100)
    }
    function d() {
        var a, b, c = window.innerHeight, d = +new Date, e = !1;
        g[0].getBoundingClientRect().top < c && (k("exp_rela"),
        e = !0),
        e || (a = function() {
            +new Date - d > 160 && (e || (b = g[0].getBoundingClientRect().top,
            c > b && (k("exp_rela"),
            e = !0)),
            d = +new Date,
            e && $(document).off("touchmove", a))
        }
        ,
        $(document).on("touchmove", a))
    }
    function e() {
        var b = window.BARTYPE.BARCLASS;
        100 !== a.postType && 101 !== a.postType && a.postData.bar_class !== b.qunSubscription && (g.show(),
        f || (h.rock(),
        f = !0))
    }
    var f, g, h, i = renderModel, j = a.bid, k = a.report;
    return g = $("#recommend-list"),
    h = new i({
        comment: "recommend_model",
        cgiName: _domain + "/cgi-bin/bar/post/related_posts",
        renderTmpl: window.TmplInline_detail.recommend,
        renderContainer: g,
        param: {
            bid: j,
            pid: a.pid,
            needbar: 1
        },
        noCache: 1,
        processData: function(b) {
            b.result.barName = a.postData.bar_name,
            b.result.bid = a.postData.bid
        },
        events: function() {
            g.on("tap", ".recommend-post-list > li", function() {
                var a = $(this)
                  , d = a.data("bid")
                  , e = a.data("pid");
                e ? (k("Clk_rela", {
                    ver3: 1,
                    ver4: e
                }),
                c(d, e, a)) : (k("Clk_rela", {
                    ver3: 2
                }),
                b(d, a))
            }),
            d()
        },
        complete: function() {}
    }),
    {
        rock: e
    };
});