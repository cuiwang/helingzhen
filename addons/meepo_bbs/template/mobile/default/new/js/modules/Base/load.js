loadjs = function() {
    var a, b, c = [], d = [], e = [], f = [], g = [], h = [], i = {}, j = {
        retry: 2,
        err: function() {}
    }, k = document.head || document.getElementsByTagName("head")[0];
    b = function(e, i, m) {
        var n, o, p = document.createElement("script");
        p.async = !1,
        p.type = "text/javascript",
        p.index = i,
        p._src = m || e,
        o = window.location.origin ? window.location.origin : window.location.host ? window.location.host : "xiaoqu.qq.com",
        l(p, e) && (p.onerror = p.onload = function() {
            p.onload = p.onerror = null ;
            var e = this.index
              , i = this._src || this.src
              , l = null ;
            if (g[i] && (l = g[i](n, i, h[i]))) {
                if (f[e] < j.retry) {
                    f[e] = f[e] + 1,
                    p && k.removeChild(p);
                    var m = l.toString().match(/http:\/\//) ? l : this.src;
                    return void function(a, c, d) {
                        b(a, c, d)
                    }(m, e, i)
                }
                d[e] = 1,
                j.err(c[e])
            } else
                d[e] = 1;
            a(p, i),
            p = null 
        }
        ,
        p.onerror = function(a) {
            console.report && console.report({
                type: "error",
                category: "",
                content: a.target.src + " load fail"
            }),
            console.log(a.target.src + " load fail")
        }
        ,
        p.src = e,
        n = Date.now(),
        k.appendChild(p))
    }
    ,
    a = function() {
        var a = d.join("")
          , b = parseInt(a, 2)
          , h = Math.pow(2, a.length) - 1
          , j = c[a.length - 1];
        if (b === h && j) {
            g.length = e.length = d.length = f.length = c.length = 0;
            var k = "";
            for (k in i)
                i.hasOwnProperty(k) && (i[k](),
                delete i[k])
        }
    }
    ;
    var l = function(a, b) {
        for (var c, d, e = ["index_recover", "index_my", "index_category", "index_find"], f = 0; f < e.length; f++)
            if (b.indexOf(e[f]) > 0) {
                d = !0;
                break
            }
        if ("/mobile/index.html" === window.location.pathname && d)
            try {
                var g = /(\/[^\/]+)$/
                  , h = /\/(\w*)\.min/
                  , i = /min\.(\w*)\.js/
                  , j = b.match(g)[1]
                  , l = j.match(h)[0]
                  , m = j.match(i) ? j.match(i)[1] : "";
                if (m) {
                    var n = window.localStorage.getItem(j);
                    if (n)
                        a.appendChild(document.createTextNode(n)),
                        k.appendChild(a);
                    else {
                        var o = new XMLHttpRequest;
                        o.onreadystatechange = function() {
                            try {
                                if (4 === o.readyState) {
                                    var c = o.status;
                                    if (c >= 200 && 300 > c || 0 === c && o.responseText) {
                                        var d = o.responseText;
                                        a.appendChild(document.createTextNode(d));
                                        var e = window.localStorage.getItem(l + "cache");
                                        window.localStorage.removeItem(l + "." + e + ".js", d),
                                        window.localStorage.setItem(j, d),
                                        window.localStorage.setItem(l + "cache", m),
                                        k.appendChild(a)
                                    } else {
                                        (new Image).src = "http://cgi.pub.qq.com/report/report_vm?monitors=[650182]&t=" + Date.now(),
                                        window.localStorage.removeItem(j),
                                        window.localStorage.removeItem(l + "cache");
                                        var f = document.createElement("script");
                                        f.src = b,
                                        k.appendChild(f),
                                        console.log("index xhr error")
                                    }
                                }
                            } catch (g) {
                                (new Image).src = "http://cgi.pub.qq.com/report/report_vm?monitors=[650182]&t=" + Date.now(),
                                window.localStorage.removeItem(j),
                                window.localStorage.removeItem(l + "cache");
                                var h = document.createElement("script");
                                h.src = b,
                                k.appendChild(h)
                            }
                        }
                        ,
                        o.open("GET", b + "?_bid=128", !0),
                        o.send()
                    }
                } else
                    c = !0
            } catch (p) {
                c = !0
            }
        else
            c = !0;
        return c
    }
      , m = function(a, b, c) {
        b = b.slice(b.lastIndexOf("/") + 1),
        b = b.split("."),
        4 === b.length && b.splice(2, 1),
        b = b.join("."),
        c && !window[c] && Badjs("file error: " + b, location.href, 0, 387645, 2)
    }
    ;
    return {
        load: function(a, i) {
            var j = a instanceof Array ? a : [a];
            i = i ? i instanceof Array ? i : [i] : null ;
            for (var k = 0, l = j.length; l > k; k++)
                c.push(j[k]),
                d.push(0),
                f.push(0),
                e.push(null ),
                g[j[k]] = i && i[k] && "function" == typeof i[k] ? i[k] : m,
                h[j[k]] = null ,
                b(j[k], c.length - 1);
            return this
        },
        loadModule: function(a, b) {
            var c = window.loadJsConfig
              , d = c && c.modules && c.modules[a];
            if (d) {
                var e = 0
                  , f = d.list.length
                  , g = this
                  , h = function() {
                    g.load(d.list[e], null ).wait(function() {
                        e === f - 1 ? b && b() : (e++,
                        h())
                    })
                }
                ;
                h()
            }
        },
        wait: function(a) {
            return a = a || function() {}
            ,
            i[c[c.length - 1]] = a,
            this
        },
        config: function(a) {
            for (var b in a)
                a.hasOwnProperty(b) && (j[b] = a[b]);
            return this
        },
        preload: function(a) {
            for (var b, c = a instanceof Array ? a : [a], d = 0, e = c.length; e > d; d++)
                b = new Image,
                b.src = c[d];
            return this
        },
        loadCss: function(a) {
            var b = window.document.createElement("link")
              , c = window.document.getElementsByTagName("script")[0];
            return b.rel = "stylesheet",
            b.href = a,
            b.media = "all",
            c.parentNode.insertBefore(b, c),
            b
        }
    }
}();