(function(i) {
    var g = {}, b, k, h, e = 750, a;
    function c(m) {
        return "tagName" in m ? m : m.parentNode
    }
    function j(n, m, p, o) {
        var r = Math.abs(n - m), q = Math.abs(p - o);
        return r >= q ? (n - m > 0 ? "Left" : "Right") : (p - o > 0 ? "Up" : "Down")
    }
    function l() {
        a = null;
        if (g.last) {
            g.el.trigger("longTap");
            g = {}
        }
    }
    function d() {
        if (a) {
            clearTimeout(a)
        }
        a = null
    }
    function f() {
        if (b) {
            clearTimeout(b)
        }
        if (k) {
            clearTimeout(k)
        }
        if (h) {
            clearTimeout(h)
        }
        if (a) {
            clearTimeout(a)
        }
        b = k = h = a = null;
        g = {}
    }
    i(document).ready(function() {
        var m, n;
        i(document.body).bind("touchstart", function(o) {
            m = Date.now();
            n = m - (g.last || m);
            g.el = i(c(o.touches[0].target));
            b && clearTimeout(b);
            g.x1 = o.touches[0].pageX;
            g.y1 = o.touches[0].pageY;
            if (n > 0 && n <= 250) {
                g.isDoubleTap = true
            }
            g.last = m;
            a = setTimeout(l, e)
        }).bind("touchmove", function(o) {
            d();
            g.x2 = o.touches[0].pageX;
            g.y2 = o.touches[0].pageY;
            if (Math.abs(g.x1 - g.x2) > 10) {
                o.preventDefault()
            }
        }).bind("touchend", function(o) {
            d();
            if ((g.x2 && Math.abs(g.x1 - g.x2) > 30) || (g.y2 && Math.abs(g.y1 - g.y2) > 30)) {
                h = setTimeout(function() {
                    g.el.trigger("swipe");
                    g.el.trigger("swipe" + (j(g.x1, g.x2, g.y1, g.y2)));
                    g = {}
                }, 0)
            } else {
                if ("last" in g) {
                    k = setTimeout(function() {
                        var p = i.Event("tap");
                        p.cancelTouch = f;
                        g.el.trigger(p);
                        if (g.isDoubleTap) {
                            g.el.trigger("doubleTap");
                            g = {}
                        } else {
                            b = setTimeout(function() {
                                b = null;
                                g.el.trigger("singleTap");
                                g = {}
                            }, 250)
                        }
                    }, 0)
                }
            }
        }).bind("touchcancel", f);
        i(window).bind("scroll", f)
    });
    ["swipe", "swipeLeft", "swipeRight", "swipeUp", "swipeDown", "doubleTap", "tap", "singleTap", "longTap"].forEach(function(n) {
        i.fn[n] = function(m) {
            return this.bind(n, m)
        }
    })
})(Zepto);
