!function(a, b) {
    a.pollRefreshUi = b()
}(this, function() {
    var a, b, c, d, e, f = 100, g = 180, h = 33, i = h, j = h, k = i, l = [f / 2, h], m = l.concat([]), n = 0, o = function() {}
    , p = 0, q = '<div class="poll-wrapper"><canvas id="poll"></canvas><span id="reloadIcon"></span><div class="spinner" id="loadingIcon"></div><div class="poll-font"><span></span>刷新成功</div></div>', r = function(e) {
        if (!p) {
            b.beginPath(),
            b.arc(l[0], l[1], j, -Math.PI, 0);
            var f = [l[0] + j, l[1]]
              , g = [m[0] + k, m[1]]
              , i = s(f, g, e);
            b.quadraticCurveTo(i[0], i[1], m[0] + k, m[1]),
            b.arc(m[0], m[1], k, 0, Math.PI),
            f = [l[0] - j, l[1]],
            g = [m[0] - k, m[1]],
            i = s(f, g, e, 1),
            b.quadraticCurveTo(i[0], i[1], l[0] - j, l[1]),
            b.closePath(),
            b.fill(),
            b.stroke(),
            c.style.webkitTransform = "scale(" + j / h + ")",
            8 > k && (p = 1,
            $(d).show(),
            $(a).hide(),
            $(c).hide(),
            o())
        }
    }
    , s = function(a, b, c, d) {
        var e, f = (b[1] - a[1]) / (b[0] - a[0]), g = -(1 / f), h = (b[0] + a[0]) / 2, i = (b[1] + a[1]) / 2, j = function(a) {
            return g * (a - h) + i
        }
        ;
        e = d ? h + .1 * c : h - .1 * c;
        var k = j(e);
        return [e, k]
    }
    , t = function(a) {
        return function() {
            p && ($(a).css("-webkit-transition", "all ease-out 0.5s").css("-webkit-transform", "translateY(" + (n - 40) + "px)"),
            setTimeout(function() {
                $(a).css("-webkit-transform", "translateY(0px)"),
                $(".poll-font").css("-webkit-transform", "translateY(-40px)"),
                p = 0
            }, 1e3))
        }
    }
    , u = 0, v = function(d, o) {
        n = d,
        d -= 60,
        u || ($(o).on("touchend", t(o)),
        u = 1),
        0 > d && ($(o).css("-webkit-transition", "none"),
        $(".poll-font").hide(),
        $(".poll-font").css("-webkit-transform", "translateY(0px)"),
        e.css("-webkit-transform", "translateY(" + d / 2 + "px)"),
        d = 0,
        $(a).show(),
        $(c).show()),
        d *= 3.3,
        0 > d && (d = 0),
        b.clearRect(0, 0, f, g),
        j = h - .1 * d,
        k = i - .3 * d,
        m[1] = d + l[1],
        r(d, o)
    }
    , w = function() {
        if (!$.os.android) {
            $("body").prepend(q),
            e = $(".poll-wrapper"),
            e.show(),
            a = document.getElementById("poll"),
            b = a.getContext("2d"),
            a.width = f,
            a.height = g,
            a.style.width = ~~(f / 2) + "px",
            a.style.height = ~~(g / 2) + "px",
            b.fillStyle = "#b1b1b1",
            b.strokeStyle = "rgb(118,113,108)",
            b.shadowColor = "#ccc",
            b.shadowOffsetX = 0,
            b.shadowOffsetY = 2,
            b.shadowBlur = 1,
            b.lineWidth = 1,
            c = document.getElementById("reloadIcon"),
            d = document.getElementById("loadingIcon");
            var h = a.offsetLeft
              , i = a.offsetTop;
            c.style.left = ~~(h + l[0] / 2 - 7.5) + "px",
            c.style.top = ~~(i + l[1] / 2 - 9) + "px",
            d.style.left = ~~(h + l[0] / 2 - 7.5) + "px",
            d.style.top = ~~(i + l[1] / 2 - 9) + "px"
        }
    }
    , x = function(a) {
        o = a
    }
    , y = function() {
        $(a).hide(),
        $(c).hide(),
        $(d).hide(),
        $(".poll-font").show()
    }
    ;
    return {
        init: w,
        ok: x,
        reset: y,
        step: v
    }
});