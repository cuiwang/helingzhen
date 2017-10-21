!function() {
    var a = $.ajax
      , b = !!window.localStorage
      , c = "buluo_cacheKeyList"
      , d = function(a) {
        var b = +new Date;
        return b - a.timestamp <= a.timeout ? !0 : !1
    }
      , e = function(a, b) {
        return a.version === b ? !0 : !1
    }
      , f = function() {
        var a = [];
        try {
            return a = localStorage.getItem(c),
            a = JSON.parse(a) || []
        } catch (b) {}
    }
      , g = function(a, b) {
        var d, e = f();
        d = e.indexOf(a),
        "add" === b && -1 === d ? e.push(a) : "remove" === b && -1 !== d && e.splice(d, 1);
        try {
            localStorage.setItem(c, JSON.stringify(e))
        } catch (g) {}
    }
      , h = function() {
        var a, b = f();
        if (b.length)
            for (var c = b.length; c--; )
                a = b[c],
                /^im_/.test(a) || (localStorage.removeItem(a),
                g(a, "remove"))
    }
      , i = function(a, b, c, d, e) {
        a.fromCache = 1;
        var f = {
            data: a,
            timestamp: +new Date,
            timeout: c,
            version: d
        };
        e && (b = "im_" + b);
        try {
            localStorage.setItem(b, JSON.stringify(f)),
            g(b, "add")
        } catch (i) {
            h(),
            localStorage.setItem(b, JSON.stringify(f)),
            g(b, "add")
        }
    }
    ;
    $.ajax = function(c) {
        var f = c.localCache
          , g = c.cacheKey || c.url + "-" + JSON.stringify(c.data)
          , h = c.cacheTimeout || 864e5
          , j = c.success
          , k = c.notThisTime || !1
          , l = c.defaultData
          , m = c.cacheVersion || "1"
          , n = c.important
          , o = null 
          , p = !1;
        if (b && f)
            try {
                o = JSON.parse(localStorage.getItem(g))
            } catch (q) {}
        o && d(o) && e(o, m) ? (k || c.success(o.data),
        p = !0) : l && (c.success(l),
        p = !0);
        var r = function(a, d, e) {
            var k = function() {
                window.Badjs.cgiErrMinitor()
            }
            ;
            return "undefined" == typeof a ? void k() : null  === a ? void k() : "number" == typeof a && -1 === String(a).indexOf(".") ? void k() : ("string" == typeof a && (a = JSON.parse(a)),
            p && c.update ? c.update(a, d, e) : j(a, d, e),
            void (f && 0 === a.retcode && b && a.result && i(a, g, h, m, n)))
        }
        ;
        c.success = r,
        a(c)
    }
}();