!function(a, b) {
    a.Refresh = b()
}(this, function() {
    function a(a) {
        if (!w && (D = $.os.ios && E[0] === document.body ? window.scrollY : $(a.currentTarget).scrollTop(),
        !t)) {
            q = +new Date;
            var b = a.touches[0];
            b && (B = b.pageX,
            C = b.pageY)
        }
    }
    function b(a, b) {
        a = a || B,
        b = b || C;
        var c = Math.abs(a - B)
          , d = Math.abs(b - C);
        return {
            horizontal: c > 0 ? (a - B) / c : 0,
            vertical: d > 0 ? (b - C) / d : 0
        }
    }
    function c(a) {
        var c = E.scrollTop();
        if ($.os.ios && E[0] === document.body && (c = window.scrollY),
        !w && !t && !D && 0 >= c) {
            if (y && $.os.ios)
                return void pollRefreshUi.step(-c, E);
            F = !1;
            var e = a.touches.length;
            if (1 === e) {
                var f = a.touches[0];
                if (Math.abs(f.pageY - f.pageY) > z.supressionThreshold)
                    a.preventDefault();
                else if (f.pageY > C + z.verticalThreshold - 5) {
                    var g = b(f.pageX, f.pageY);
                    g.vertical > 0 && (F = !0,
                    j(),
                    $.os.android && d())
                }
            }
        }
    }
    function d(a) {
        if (!w && t && !D) {
            var b = this;
            F && (p = !1,
            x.forEach(function(c) {
                c.call(b, a) && (p = !0)
            }),
            p || k(),
            F = !1)
        }
    }
    function e() {
        G++,
        mqq.android && 2 > G || (r = setTimeout(function() {
            var a = mqq.invoke("ui", "pageVisibility");
            mqq.android && null  === a && (a = !0),
            o && o(a),
            a && setTimeout(function() {
                var a, b = JSON.parse(window.localStorage.getItem("refreshData") || "{}"), c = 0;
                for (a in b)
                    b.hasOwnProperty(a) && (c = 1);
                c && j();
                for (a in b)
                    b.hasOwnProperty(a) && A[a] && (A[a](),
                    delete b[a]);
                b = JSON.stringify(b),
                window.localStorage.setItem("refreshData", b),
                setTimeout(function() {
                    c && k()
                }, 100)
            }, 100)
        }, 100))
    }
    function f() {
        var b = E;
        $.os.ios && b[0] === document.body && mqq.compare("5.3") >= 0 ? (mqq.ui.setPullDown({
            enable: !0
        }),
        mqq.addEventListener("qbrowserPullDown", function() {
            x.forEach(function(a) {
                a.call(b[0] || document, event) && (p = !0)
            }),
            mqq.ui.setPullDown({
                success: !0,
                text: "刷新成功"
            })
        })) : (b.on("touchstart", a),
        b.on("touchmove", c),
        b.on("touchend", d),
        document.addEventListener("qbrowserVisibilityChange", e))
    }
    function g(a, b) {
        A[a] = b
    }
    function h() {
        var b = E;
        b.off("touchstart", a),
        b.off("touchmove", c),
        b.off("touchend", d)
    }
    function i() {
        u = $('<div class="refresh-loading"></div>'),
        $(document.body).append(u)
    }
    function j() {
        t || (t = !0,
        u && u.show())
    }
    function k() {
        t = !1,
        u && u.hide()
    }
    function l(a) {
        E = $(a)
    }
    function m(a) {
        a = a || {},
        s || (s = !0,
        i(),
        a.dom && l(a.dom),
        f()),
        a.reload && x.push(a.reload),
        o = a.onPageVisiblityChange,
        y = a.usingPollRefresh || 0,
        y && (pollRefreshUi.init(),
        pollRefreshUi.ok(function() {
            F = 1,
            t = 1
        }))
    }
    function n() {
        h(),
        u && (u.remove(),
        u = null ),
        D = B = C = 0,
        F = t = s = !1
    }
    var o, p, q, r, s = !1, t = !1, u = null , v = $(document), w = 0, x = [], y = 0, z = {
        supressionThreshold: 10,
        horizontalThreshold: 10,
        verticalThreshold: 30
    }, A = {}, B = 0, C = 0, D = 0, E = $("body"), F = !1, G = 0, H = function(a, b) {
        var c = JSON.parse(window.localStorage.getItem("refreshData") || "{}");
        c[b] = "needFresh",
        c = JSON.stringify(c);
        try {
            window.localStorage.setItem("refreshData", c)
        } catch (d) {
            window.localStorage.clear(),
            window.localStorage.setItem("refreshData", c)
        }
    }
    ;
    return v.on("refreshPage", H),
    {
        init: m,
        destroy: n,
        show: j,
        hide: k,
        freeze: function() {
            w = 1
        },
        melt: function() {
            w = 0
        },
        listen: function(a) {
            return a.del ? x.splice(x.indexOf(a.reload)) : a.reload && x.push(a.reload)
        },
        change$scrolLdom: l,
        register: g,
        pauseTouchMove: function() {
            h()
        },
        restoreTouchMove: function() {
            h(),
            f()
        }
    }
});