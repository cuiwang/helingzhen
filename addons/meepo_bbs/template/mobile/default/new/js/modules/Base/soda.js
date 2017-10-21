!function() {
    var a = /\{\{([^\}]*)\}\}/g
      , b = function(a) {
        return new RegExp("(^|\\s+)" + a + "(\\s+|$)","g")
    }
      , c = function(a, c) {
        return a.className ? void (a.className.match(b(c)) || (a.className += " " + c)) : void (a.className = c)
    }
      , d = function(a, b) {
        m.lastIndex = 0;
        var c = b.replace(m, function(b) {
            return "undefined" == typeof a[b] ? b : a[b]
        });
        if ("true" === b)
            return !0;
        if ("false" === b)
            return !1;
        var d = function(b, e) {
            var f = e.indexOf(".");
            if (f > -1) {
                var g = e.substr(0, f);
                if (e = e.substr(f + 1),
                "undefined" != typeof a[g] && l.test(g) && (g = a[g]),
                "undefined" != typeof b[g])
                    return d(b[g], e);
                var h = {
                    name: c,
                    data: a
                };
                return w("nullvalue", {
                    type: "nullattr",
                    data: h
                }, h),
                ""
            }
            "undefined" != typeof a[e] && l.test(e) && (e = a[e]);
            var i;
            if ("undefined" != typeof b[e])
                i = b[e];
            else {
                var h = {
                    name: c,
                    data: a
                };
                w("nullvalue", {
                    type: "nullvalue",
                    data: h
                }, h),
                i = ""
            }
            return i
        }
        ;
        return d(a, b)
    }
      , e = /"([^"]*)"|'([^']*)'/g
      , f = /[a-zA-Z_\$]+[\w\$]*(?:\s*\.\s*(?:[a-zA-Z_\$]+[\w\$]*|\d+))*/g
      , g = /\[([^\[\]]*)\]/g
      , h = /\|\|/g
      , i = "OR_OPERATOR"
      , j = function() {
        return "$$" + ~~(1e6 * Math.random())
    }
      , k = "_$C$_"
      , l = /^_\$C\$_/
      , m = /_\$C\$_[^\.]+/g
      , n = function() {
        return k + ~~(1e6 * Math.random())
    }
      , o = function(a, b) {
        a = a.replace(h, i).split("|");
        for (var c = 0; c < a.length; c++)
            a[c] = (a[c].replace(new RegExp(i,"g"), "||") || "").trim();
        var k = a[0] || ""
          , l = a.slice(1);
        for (k = k.replace(e, function(a, c, d) {
            var e = j();
            return b[e] = c || d,
            e
        }); g.test(k); )
            g.lastIndex = 0,
            k = k.replace(g, function(a, c) {
                var d = n()
                  , e = o(c, b);
                return b[d] = e,
                "." + d
            });
        k = k.replace(f, function(a) {
            return "getValue(scope,'" + a.trim() + "')"
        });
        var m = function() {
            var a = l.shift();
            if (a) {
                for (var a = a.split(":"), b = a.slice(1) || [], c = a[0] || "", d = 0; d < b.length; d++)
                    f.test(b[d]) && (b[d] = "getValue(scope,'" + b[d] + "')");
                r[c] && (b.unshift(k),
                b = b.join(","),
                k = "sodaFilterMap['" + c + "'](" + b + ")"),
                m()
            }
        }
        ;
        m();
        var p = new Function("getValue","sodaFilterMap","return function sodaExp(scope){ return " + k + "}")(d, r);
        return p(b)
    }
      , p = function(b, c) {
        [].map.call([].slice.call(b.childNodes, []), function(b) {
            if (3 === b.nodeType && (b.nodeValue = b.nodeValue.replace(a, function(a, b) {
                return o(b, c)
            })),
            b.attributes)
                if (/in/.test(b.getAttribute("soda-repeat") || ""))
                    q["soda-repeat"].link(c, b, b.attributes);
                else {
                    if ((b.getAttribute("soda-if") || "").trim() && (q["soda-if"].link(c, b, b.attributes),
                    "removed" === b.getAttribute("removed")))
                        return;
                    var d;
                    [].map.call(b.attributes, function(e) {
                        if ("soda-if" !== e.name)
                            if (/^soda-/.test(e.name))
                                if (q[e.name]) {
                                    var f = q[e.name]
                                      , g = f.link(c, b, b.attributes);
                                    g && "childDone" === g.command && (d = 1)
                                } else {
                                    var h = e.name.replace(/^soda-/, "");
                                    if (h) {
                                        var i = e.value.replace(a, function(a, b) {
                                            return o(b, c)
                                        });
                                        b.setAttribute(h, i)
                                    }
                                }
                            else
                                e.value = e.value.replace(a, function(a, b) {
                                    return o(b, c)
                                })
                    }),
                    d || p(b, c)
                }
        })
    }
      , q = {}
      , r = {}
      , s = function(a, b) {
        q["soda-" + a] = b()
    }
      , t = function(a, b) {
        r[a] = b
    }
    ;
    t.get = function(a) {
        return r[a]
    }
    ,
    t("date", function(a, b) {
        return b
    }),
    s("repeat", function() {
        return {
            compile: function(a, b, c) {},
            link: function(b, c, e) {
                var f, g, h, i = c.getAttribute("soda-repeat"), j = /\s+track\s+by\s+([^\s]+)$/;
                i = i.replace(j, function(a, b) {
                    return b && (h = (b || "").trim()),
                    ""
                });
                var k = /([^\s]+)\s+in\s+([^\s]+)|\(([^,]+)\s*,\s*([^)]+)\)\s+in\s+([^\s]+)/
                  , l = k.exec(i);
                if (l) {
                    if (l[1] && l[2]) {
                        if (f = (l[1] || "").trim(),
                        g = (l[2] || "").trim(),
                        !f || !g)
                            return
                    } else
                        l[3] && l[4] && l[5] && (h = (l[3] || "").trim(),
                        f = (l[4] || "").trim(),
                        g = (l[5] || "").trim());
                    h = h || "$index";
                    var m = d(b, g) || []
                      , n = c
                      , r = function(d) {
                        var e = c.cloneNode()
                          , g = {};
                        g[h] = d,
                        g[f] = m[d],
                        g.__proto__ = b,
                        e.innerHTML = c.innerHTML,
                        (e.getAttribute("soda-if") || "").trim() && (q["soda-if"].link(g, e, e.attributes),
                        "removed" === e.getAttribute("removed")) || ([].map.call(e.attributes, function(c) {
                            if ("removed" !== e.getAttribute("removed") && "soda-repeat" !== c.name.trim() && "soda-if" !== c.name.trim())
                                if (/^soda-/.test(c.name))
                                    if (q[c.name]) {
                                        var d = q[c.name];
                                        d.link(g, e, e.attributes)
                                    } else {
                                        var f = c.name.replace(/^soda-/, "");
                                        if (f) {
                                            var h = c.value.replace(a, function(a, c) {
                                                return o(c, b)
                                            });
                                            e.setAttribute(f, h)
                                        }
                                    }
                                else
                                    c.value = c.value.replace(a, function(a, b) {
                                        return o(b, g)
                                    })
                        }),
                        "removed" !== e.getAttribute("removed") && (p(e, g),
                        c.parentNode.insertBefore(e, n.nextSibling),
                        n = e))
                    }
                    ;
                    if ("length" in m)
                        for (var s = 0; s < m.length; s++)
                            r(s);
                    else
                        for (var s in m)
                            m.hasOwnProperty(s) && r(s);
                    c.parentNode.removeChild(c)
                }
            }
        }
    }),
    s("if", function() {
        return {
            link: function(a, b, c) {
                var d = b.getAttribute("soda-if")
                  , e = o(d, a);
                e || (b.setAttribute("removed", "removed"),
                b.parentNode && b.parentNode.removeChild(b))
            }
        }
    }),
    s("class", function() {
        return {
            link: function(a, b, d) {
                var e = b.getAttribute("soda-class")
                  , f = o(e, a);
                f && c(b, f)
            }
        }
    }),
    s("src", function() {
        return {
            link: function(b, c, d) {
                var e = c.getAttribute("soda-src")
                  , f = e.replace(a, function(a, c) {
                    return o(c, b)
                });
                f && c.setAttribute("src", f)
            }
        }
    }),
    s("bind-html", function() {
        return {
            link: function(a, b, c) {
                var d = b.getAttribute("soda-bind-html")
                  , e = o(d, a);
                return e ? (b.innerHTML = e,
                {
                    command: "childDone"
                }) : void 0
            }
        }
    }),
    s("style", function() {
        return {
            link: function(a, b, c) {
                var d = b.getAttribute("soda-style")
                  , e = o(d, a)
                  , f = function(a, b) {
                    var c = /opacity|z-index/;
                    return c.test(a) ? parseFloat(b) : isNaN(b) ? b : b + "px"
                }
                ;
                if (e) {
                    var g = [];
                    for (var h in e)
                        if (e.hasOwnProperty(h)) {
                            var i = f(h, e[h]);
                            g.push([h, i].join(":"))
                        }
                    for (var j = b.style, h = 0; h < j.length; h++) {
                        var k = j[h];
                        e[k] || g.push([k, j[k]].join(":"))
                    }
                    var l = g.join(";");
                    b.setAttribute("style", l)
                }
            }
        }
    });
    var u = function(a, b) {
        var c = document.createElement("div");
        c.innerHTML = a,
        p(c, b);
        var d = document.createDocumentFragment();
        d.innerHTML = c.innerHTML;
        for (var e; e = c.childNodes[0]; )
            d.appendChild(e);
        return d
    }
      , v = {};
    u.addEventListener = function(a, b) {
        v[a] || (v[a] = []),
        v[a].push(b)
    }
    ,
    u.author = "dorsy";
    var w = function(a, b, c) {
        for (var d = v[a] || [], e = 0; e < d.length; e++) {
            var f = d[e];
            f && f(b, c)
        }
    }
    ;
    window.sodaRender && "dorsy" === window.sodaRender.author || (window.sodaRender = u,
    window.sodaFilter = t)
}();