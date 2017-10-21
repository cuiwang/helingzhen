!function(a, b) {
    a.Alert = b(a.$, a.Tmpl)
}(this, function(a, b) {
    function c() {
        h = a('<div class="alert"></div>'),
        a(document.body).append(h),
        h.on("tap", function(a) {
            a.target !== h[0] || m || e.call(this, a)
        }),
        l = !0
    }
    function d(a, d, g, o) {
        l || c(),
        i = null ,
        j = null ,
        k = null ,
        g.hasOwnProperty("preventAutoHide") && (m = g.preventAutoHide),
        h.html(""),
        h.on("touchmove", f);
        var p = ""
          , q = {};
        o ? (p = window.TmplInline_alert.textarea || {},
        q = {
            title: a,
            placeholder: g.placeholder,
            content: d,
            confirm: g && g.confirm || "确认",
            cancel: g && g.cancel
        }) : (p = window.TmplInline_alert.frame || {},
        q = {
            title: a,
            content: d,
            confirm: g && g.confirm || "确认",
            cancel: g && g.cancel,
            confirmAtRight: g && g.confirmAtRight || !1
        }),
        "basic" === g.template && (q.tplType = "basic"),
        g.theme ? q.theme = g.theme : q.theme = "",
        b(p, q).appendTo(h),
        h.show(),
        h.find(".btn").on("click", e),
        g && g.callback && (i = g.callback),
        g && g.cancelCallback && (j = g.cancelCallback),
        g && g.onTap && (k = g.onTap),
        setTimeout(function() {
            h.find(".a_edit").focus()
        }, 0),
        n.Alert.alertStatus = !0
    }
    function e(b) {
        var c = null ;
        b.preventDefault(),
        b.stopPropagation(),
        h.off("touchmove", f),
        a(this).hasClass("btn") && a(this).addClass("a-pushed");
        var d = this;
        h.find(".edit").blur(),
        h.find(".a_edit").blur(),
        "confirm-btn" === this.id ? (c = "left",
        i && i()) : (c = "right",
        j && j()),
        setTimeout(function() {
            a(d).hasClass("btn") && a(d).removeClass("a-pushed");
            var e = !0;
            k && (e = k.apply(d, [c, b, h[0]])),
            e !== !1 && (h.hide(),
            m = !1,
            setTimeout(function() {
                n.Alert.alertStatus = !1
            }, 400))
        }, 50)
    }
    function f(b) {
        var c = b.target;
        b.stopPropagation(),
        a(c).hasClass("a_edit") || a(c).hasClass("edit") || b.preventDefault()
    }
    function g() {
        h.hide(),
        h.off("touchmove", f),
        "confirm-btn" === this.id ? i && i() : j && j(),
        setTimeout(function() {
            n.Alert.alertStatus = !1
        }, 400)
    }
    var h, i, j, k, l = !1, m = !1, n = this;
    return {
        textarea: function(a, b, c) {
            d(a, b, c, 1),
            c.renderSuccess && c.renderSuccess.call(h[0])
        },
        show: d,
        hide: g
    }
});
