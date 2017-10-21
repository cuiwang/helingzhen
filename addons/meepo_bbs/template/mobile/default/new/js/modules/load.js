loadjs = function() {
                var e, t, n = [],
                o = [],
                r = [],
                a = [],
                i = [],
                l = [],
                c = {},
                s = {
                    retry: 2,
                    err: function() {}
                },
                d = document.head || document.getElementsByTagName("head")[0];
                t = function(r, c, m) {
                    var u, f, p = document.createElement("script");
                    p.async = !1,
                    p.type = "text/javascript",
                    p.index = c,
                    p._src = m || r,
                    f = window.location.origin ? window.location.origin: window.location.host ? window.location.host: "xiaoqu.qq.com",
                    h(p, r) && (p.onerror = p.onload = function() {
                        p.onload = p.onerror = null;
                        var r = this.index,
                        c = this._src || this.src,
                        h = null;
                        if (i[c] && (h = i[c](u, c, l[c]))) {
                            if (a[r] < s.retry) {
                                a[r] = a[r] + 1,
                                p && d.removeChild(p);
                                var m = h.toString().match(/http:\/\//) ? h: this.src;
                                return void
                                function(e, n, o) {
                                    t(e, n, o)
                                } (m, r, c)
                            }
                            o[r] = 1,
                            s.err(n[r])
                        } else o[r] = 1;
                        e(p, c),
                        p = null
                    },
                    p.onerror = function(e) {
                        console.report && console.report({
                            type: "error",
                            category: "",
                            content: e.target.src + " load fail"
                        }),
                        console.log(e.target.src + " load fail")
                    },
                    p.src = r, u = Date.now(), d.appendChild(p))
                },
                e = function() {
                    var e = o.join(""),
                    t = parseInt(e, 2),
                    l = Math.pow(2, e.length) - 1,
                    s = n[e.length - 1];
                    if (t === l && s) {
                        i.length = r.length = o.length = a.length = n.length = 0;
                        var d = "";
                        for (d in c) c.hasOwnProperty(d) && (c[d](), delete c[d])
                    }
                };
                var h = function(e, t) {
                    for (var n, o, r = ["index_recover", "index_my", "index_category", "index_find"], a = 0; a < r.length; a++) if (t.indexOf(r[a]) > 0) {
                        o = !0;
                        break
                    }
                    if ("/app/index.php" === window.location.pathname && o) try {
                        var i = /(\/[^\/]+)$/,
                        l = /\/(\w*)\.min/,
                        c = /min\.(\w*)\.js/,
                        s = t.match(i)[1],
                        h = s.match(l)[0],
                        m = s.match(c) ? s.match(c)[1] : "";
                        if (m) {
                            var u = window.localStorage.getItem(s);
                            if (u) e.appendChild(document.createTextNode(u)),
                            d.appendChild(e);
                            else {
                                var f = new XMLHttpRequest;
                                f.onreadystatechange = function() {
                                    try {
                                        if (4 === f.readyState) {
                                            var n = f.status;
                                            if (n >= 200 && 300 > n || 0 === n && f.responseText) {
                                                var o = f.responseText;
                                                e.appendChild(document.createTextNode(o));
                                                var r = window.localStorage.getItem(h + "cache");
                                                window.localStorage.removeItem(h + "." + r + ".js", o),
                                                window.localStorage.setItem(s, o),
                                                window.localStorage.setItem(h + "cache", m),
                                                d.appendChild(e)
                                            } else { 
                                            	//(new Image).src = "http://cgi.pub.qq.com/report/report_vm?monitors=[650182]&t=" + Date.now(),
                                                window.localStorage.removeItem(s),
                                                window.localStorage.removeItem(h + "cache");
                                                var a = document.createElement("script");
                                                a.src = t,
                                                d.appendChild(a),
                                                console.log("index xhr error")
                                            }
                                        }
                                    } catch(i) { 
                                    	//(new Image).src = "http://cgi.pub.qq.com/report/report_vm?monitors=[650182]&t=" + Date.now(),
                                        window.localStorage.removeItem(s),
                                        window.localStorage.removeItem(h + "cache");
                                        var l = document.createElement("script");
                                        l.src = t,
                                        d.appendChild(l)
                                    }
                                },
                                f.open("GET", t + "?_bid=128", !0),
                                f.send()
                            }
                        } else n = !0
                    } catch(p) {
                        n = !0
                    } else n = !0;
                    return n
                },
                m = function(e, t, n) {
                    t = t.slice(t.lastIndexOf("/") + 1),
                    t = t.split("."),
                    4 === t.length && t.splice(2, 1),
                    t = t.join("."),
                    n && !window[n] && Badjs("file error: " + t, location.href, 0, 387645, 2)
                };
                return {
                    load: function(e, c) {
                        var s = e instanceof Array ? e: [e];
                        c = c ? c instanceof Array ? c: [c] : null;
                        for (var d = 0,
                        h = s.length; h > d; d++) n.push(s[d]),
                        o.push(0),
                        a.push(0),
                        r.push(null),
                        i[s[d]] = c && c[d] && "function" == typeof c[d] ? c[d] : m,
                        l[s[d]] = null,
                        t(s[d], n.length - 1);
                        return this
                    },
                    loadModule: function(e, t) {
                        var n = window.loadJsConfig,
                        o = n && n.modules && n.modules[e];
                        if (o) {
                            var r = 0,
                            a = o.list.length,
                            i = this,
                            l = function() {
                                i.load(o.list[r], null).wait(function() {
                                    r === a - 1 ? t && t() : (r++, l())
                                })
                            };
                            l()
                        }
                    },
                    wait: function(e) {
                        return e = e ||
                        function() {},
                        c[n[n.length - 1]] = e,
                        this
                    },
                    config: function(e) {
                        for (var t in e) e.hasOwnProperty(t) && (s[t] = e[t]);
                        return this
                    },
                    preload: function(e) {
                        for (var t, n = e instanceof Array ? e: [e], o = 0, r = n.length; r > o; o++) t = new Image,
                        t.src = n[o];
                        return this
                    },
                    loadCss: function(e) {
                        var t = window.document.createElement("link"),
                        n = window.document.getElementsByTagName("script")[0];
                        return t.rel = "stylesheet",
                        t.href = e,
                        t.media = "all",
                        n.parentNode.insertBefore(t, n),
                        t
                    }
                }
            } ();