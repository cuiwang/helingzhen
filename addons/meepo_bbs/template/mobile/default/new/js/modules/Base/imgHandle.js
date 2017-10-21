!function(a, b) {
    "function" == typeof define && define.amd ? define(["imgHandle"], b) : a.imgHandle = b(a.imgHandle)
}(this, function(a) {
    function b(a, b) {
        function c(a, b) {
            var c = [0, 40, 60, 100, 120, 140, 160, 200, 640]
              , d = c.map(function(a) {
                return Math.abs(b - a)
            });
            return a + c[d.indexOf(Math.min.apply(null , d))]
        }
        var d = /(?:\/\d*)$/;
        return -1 === a.indexOf("p.qlogo.cn/gbar_heads/") ? a : d.test(a) ? b ? (b = parseInt(b.toString()),
        c(a.replace(d, "/"), b)) : a.replace(d, "/0") : a
    }
    a = a || {};
    var c, d, e, f, g, h, i, j, k, l, m, n, o = "lazy-src", p = 2;
    c = function(a, b) {
        a.target.hasAttribute("needFade") && $(a.target).css("opacity", "1"),
        b.removeAttribute(o),
        i(a, b)
    }
    ,
    d = "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/hot-error.png",
    f = function(a, b, c) {
        a.target.hasAttribute("needFade") && $(a.target).css("opacity", "1"),
        b.removeAttribute(o),
        d && c !== d && b.parentNode && (b.src = d,
        b.className += " error",
        b.style.marginTop = "0px",
        b.parentNode.className += " err-img"),
        Q && Q.monitor && Q.monitor(504991),
        console.error("image error: " + c)
    }
    ,
    g = function(a, b, c, d) {
        if (!a)
            return {
                url: "img/error.png",
                w: 0,
                h: 0,
                t: "",
                marginTop: 0
            };
        b = b || 328,
        c = c || 228,
        d = d || 50;
        var e = a.w
          , f = a.h;
        if (b > e)
            return d > f && (a.marginTop = (d - f) / 2 + "px"),
            a;
        var g = f / e * b
          , h = 0;
        return g > c && (h = (c - g) / 2 + "px"),
        {
            url: a.url,
            w: b,
            h: g,
            t: a.t,
            marginTop: h
        }
    }
    ,
    h = function(a, b, c, d, e) {
        if (a && a.length) {
            var f, g, h;
            if (1 !== a.length || b)
                for (var i = 0, j = a.length; j > i; i++)
                    f = a[i],
                    g = e ? c || 77 : Math.min(f.w || c, c) || 77,
                    h = e ? d || 77 : Math.min(f.h || d, d) || 77,
                    f.boxWidth = f.width = g,
                    f.boxHeight = f.height = h,
                    f.marginTop = 0,
                    f.marginLeft = 0,
                    f.w && f.h && (f.h * g / f.w > h ? (f.height = f.h * g / f.w,
                    f.marginTop = .382 * (f.boxHeight - f.height)) : (f.width = f.w * h / f.h,
                    f.marginLeft = (f.boxWidth - f.width) / 2));
            else
                f = a[0],
                c = Math.min(f.w || c, c) || 150,
                d = Math.min(f.h || d, d) || 225,
                f.boxWidth = f.width = c,
                f.boxHeight = f.height = d,
                f.marginTop = 0,
                f.marginLeft = 0,
                f.w && f.h && (f.h * c / f.w > d ? (f.width = f.w * d / f.h,
                f.boxWidth = f.width) : (f.height = f.h * c / f.w,
                f.boxHeight = f.height))
        }
        return a
    }
    ,
    i = function(a, b) {
        if (b.getAttribute("noSize") && "" !== b.getAttribute("noSize")) {
            for (var c = b.getAttribute("noSize").split("&"), d = a.target.width, e = a.target.height, f = window.location.pathname, g = {}, i = 0; i < c.length; i++)
                g[c[i].split(":")[0]] = c[i].split(":")[1];
            try {
                (200 > d || 100 > e) && (f.indexOf("/index.html") > -1 || f.indexOf("/barindex.html") > -1) && b.parentNode && $(b).parent().hasClass("feed-img") && (b.parentNode.style.display = "none",
                $(b).parents(".img-wrap").find(".total-img").hide())
            } catch (j) {}
            var k = g.w
              , l = [{
                w: d,
                h: e,
                url: a.target.src
            }]
              , m = g.h;
            l = h(l, !0, k, m, !0),
            b.style.cssText = "width:" + l[0].width + "px; height:" + l[0].height + "px;margin-left:" + l[0].marginLeft + "px; margin-top:" + l[0].marginTop + "px;"
        }
        "true" === b.getAttribute("needHideBg") && b.parentNode && (b.parentNode.className += " hide-bg"),
        "true" === b.getAttribute("hidebg") && b.parentNode && (b.parentNode.style.background = "none")
    }
    ;
    var q = function(a, b) {
        a = a || "";
        var c = a;
        "object" == typeof c && (c.t && "gif" === c.t && (a += "0"),
        a = c.url),
        a.indexOf("mmbiz.qpic.cn") > -1 && $.os.ios && (a = a.replace("tp=webp", ""));
        var d = a.split("/");
        return !d[d.length - 1] && "0" !== d[d.length - 1] || -1 !== a.indexOf("ugc.qpic.cn/gbar_pic") ? (a.indexOf("p.qlogo.cn") > -1 ? a += "0" : a.indexOf("p.qpic.cn") > -1 || a.indexOf("ugc.qpic.cn/qqac/") > -1 ? a += "160" : a.indexOf("ugc.qpic.cn/gbar_pic") > -1 && (a = a.replace(/\/(\d+)$/, "/"),
        a += b || "200"),
        a) : (a.indexOf("ugc.qpic.cn/qqac/") > -1 && (d[d.length - 1] = "160",
        a = d.join("/")),
        a)
    }
    ;
    m = document.documentElement.clientHeight,
    k = [],
    l = function(a) {
        var b = a.getAttribute(o);
        b && (a.setAttribute("page-lazyload-time", Date.now()),
        a.onerror = function(c) {
            f(c, a, b)
        }
        ,
        a.onload = function(b) {
            c(b, a, o)
        }
        ,
        a.src = b)
    }
    ;
    var r = function(a) {
        n = a.data[0] === document ? 0 : $(a.data[0]).offset().top;
        for (var b, c, d = 0; d < k.length; d++)
            b = k[d],
            c = b.getBoundingClientRect().top,
            m * p > c - n && l(b)
    }
    ;
    return j = function(a, b) {
        if (b = b || document,
        e || (e = !0,
        $(b).on("scroll", [b], r),
        $(b).on("touchmove", [b], r)),
        a) {
            var c;
            n = b === document ? 0 : $(b).offset().top;
            var d = [];
            d = "IMG" === a.tagName ? $(a) : $(a).find("img[lazy-src]");
            for (var f = 0, g = d.length; g > f; f++)
                a = d[f],
                c = a.getBoundingClientRect().top,
                n + m > c ? l(a) : k.push(a)
        }
    }
    ,
    a.setErrImg = function(a) {
        d = a
    }
    ,
    a.lazy = j,
    a.defaultAvatar = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEYAAABGCAMAAABG8BK2AAAAS1BMVEUAAADr6ujr6ujr6ujr6ujr6ujr6ujr6ujr6ujr6ujr6ujr6ujr6ujr6ujr6ujr6ujr6ujr6ujr6ujr6ujU1NTf3t3a2trm5eTj4uFyXPSXAAAAE3RSTlMA2mHxqhwSskjS5/eSxy1XUoecUeHPdwAAAgpJREFUWMO9mN2SgyAMhRGoVK3aNkF8/yfd2e5PNVmTtDp7Lh3nG3JCIMEJGvwtNm0ACG0Tb35wr6v2sQWiNvr6Jcg5dfCnunQ2Q3wFgipvWwmFcJC+olMEg+JJplwDmBSuEuUCZl22k9wDVcl5QsQp5wJEfb1hC/N2xqVm6vTJRClIVXROTSkZuTLh8Lh6TtE5PcsRp6gcnq+r4Ivoz3VlbyAY3BbZh0ubo5hplPKeFtUIRCgJtsKqhMWoy6l+KCMQTSJmgrXGb0wjxaRH1XBneLb1nMOZpYlvPX0LQnwUU7cX032WlgfusCIg8s65xDCoY/gWDPsxwbkB9mNgcP4IjHeXIzAXl47AJF4JkFVMBlYPrb4YfTktz/eMumaWcWEP288K4Bi0iGGCbrBucmAWo03U4kY4sgQVkvCkx6RHlWgxoFWkGLwekx6Vd4Mekx7VQI4ttGt9bLm0H5PIkS5YI5njHxcMsUb35+u39QXj4hqTZ73A8woT6eU7IU6GyMrjR3L5umaJMR6iC0zDGpOMxXj5lmdQI2uTir0xKbRN4q1Jlhez2YvG93u/JDS0RUrTdkPr/LvtNRk874Qz2Yy5S6NHmaTLpQijh6ur15u2qpbGMtSkjXfPAfNXOec5rz8UQhFGVl19ffAAzTVax/nx4MeF4586jn940Z+B/vNRij+RddCFSnsi+wBCpCHzHkMffwAAAABJRU5ErkJggg==",
    window.sodaFilter("getDefaultAvatar", function() {
        return a.defaultAvatar
    }),
    a.format = g,
    a.formatThumb = h,
    a.getThumbUrl = q,
    a.getBarAvatarBySize = b,
    a.dealWithFeedImage = i,
    a
});