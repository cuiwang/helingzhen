!function(a, b) {
    a.RichShare = b(a.$, a.Tmpl, a.IScroll)
}(this, function(a, b, c) {
    function d() {
        r ? b(window.TmplInline_share.share_wechat, {
            barId: s
        }).appendTo(a(document.body)) : b(window.TmplInline_share.share, {
            barId: s
        }).appendTo(a(document.body)),
        k = a(".rich-share-mask"),
        k.on("tap", function(a) {
            a.target === k[0] && g()
        }).on("touchend", function(a) {
            a.preventDefault()
        }),
        r ? k.on("tap", ".btn-wechat-confirm", function() {
            g()
        }) : (l = k.find(".rich-share"),
        a(".btn-share-cancel").on("tap", function() {
            var b = a(this);
            b.addClass("active"),
            setTimeout(function() {
                b.removeClass("active"),
                g()
            }, 100)
        }).on("touchend", function(a) {
            a.preventDefault()
        }),
        k.on("tap", ".share-btn", function() {
            if (!q) {
                var b = a(this)
                  , c = b.data("index");
                setTimeout(function() {
                    m && m.call(b, c),
                    o[c - t] && o[c - t].onTap.call(b, c),
                    g()
                }, 100)
            }
        })),
        n = !0
    }
    function e() {
        if (!r) {
            mqq.iOS && mqq.ui.setWebViewBehavior({
                swipeBack: 0
            });
            var a = new c("#shareScroller",{
                scrollX: !0,
                scrollY: !1,
                bindToWrapper: !0,
                preventDefault: !1,
                click: !1
            });
            a.on("scrollStart", function() {
                q = !0
            }),
            a.on("scrollEnd", function() {
                q = !1
            });
            var b = new c("#funcScroller",{
                scrollX: !0,
                scrollY: !1,
                bindToWrapper: !0,
                preventDefault: !1,
                click: !1
            });
            b.on("scrollStart", function() {
                q = !0
            }),
            b.on("scrollEnd", function() {
                q = !1
            })
        }
        p = !0
    }
    function f(b, c) {
        n || d(),
        c ? a(".cancel-focus-btn").show() : a(".cancel-focus-btn").hide(),
        mqq.compare("5.8") > -1 && mqq.invoke("ui", "disableLongPress", {
            enable: !0
        }),
        k.show(),
        r || (setTimeout(function() {
            l.addClass("show"),
            p || e()
        }, 0),
        m = b || function() {}
        ),
        k.on("touchmove.share", h)
    }
    function g() {
        n && (r ? k.hide() : n && l && k && (l.removeClass("show"),
        setTimeout(function() {
            k.hide()
        }, 200)),
        mqq.compare("5.8") > -1 && mqq.invoke("ui", "disableLongPress", {
            enable: !1
        }),
        k.off("touchmove.share", h))
    }
    function h(a) {
        a.preventDefault(),
        a.stopPropagation()
    }
    function i(c, e) {
        var f = "";
        e && "object" == typeof e && "number" != typeof e.length && (f = e.hasOwnProperty("insertMode") ? e.insertMode : ""),
        n || (d(),
        o = c,
        setTimeout(function() {
            b.addTmpl("rich_share_btn", '<li class="share-btn" data-index="{{index}}" id="{{id}}"><div class="share-icon"><img soda-src="{{img}}"></div><p><span class="share-btn-msg">{{text}}</span></p></li>');
            var d = a("#funcScroller").find("ul")
              , e = c.length
              , g = 75
              , h = j(d.find("li")) + 1 + 10;
            for (t = h; --e >= 0; )
                switch (c[e].index = h + e,
                f) {
                case "after":
                    var i = d.find("li")
                      , k = i.length;
                    a(b("rich_share_btn", c[e]).toString()).insertAfter(i[k - 1]);
                    break;
                default:
                    a(b("rich_share_btn", c[e]).toString()).insertBefore(d.find("li")[0])
                }
            d[0].style.width = g * d.find("li").length + "px"
        }, 0))
    }
    function j(b) {
        var c = 0;
        return b.each(function() {
            var b = a(this).data("index");
            c = b > c ? b : c
        }),
        c
    }
    var k, l, m, n = !1, o = [], p = !1, q = !1, r = navigator.userAgent.match(/\bMicroMessenger\/[\d\.]+/), s = Util.queryString("bid") || Util.getHash("bid"), t = 0;
    return {
        build: i,
        show: f,
        hide: g
    }
});