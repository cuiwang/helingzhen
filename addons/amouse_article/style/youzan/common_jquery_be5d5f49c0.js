!function (e, t) {
    function n(e) {
        var t = e.length, n = oe.type(e);
        return oe.isWindow(e) ? !1 : 1 === e.nodeType && t ? !0 : "array" === n || "function" !== n && (0 === t || "number" == typeof t && t > 0 && t - 1 in e)
    }

    function r(e) {
        var t = he[e] = {};
        return oe.each(e.match(ae) || [], function (e, n) {
            t[n] = !0
        }), t
    }

    function i() {
        Object.defineProperty(this.cache = {}, 0, {
            get: function () {
                return {}
            }
        }), this.expando = oe.expando + Math.random()
    }

    function o(e, n, r) {
        var i;
        if (r === t && 1 === e.nodeType)if (i = "data-" + n.replace(ve, "-$1").toLowerCase(), r = e.getAttribute(i), "string" == typeof r) {
            try {
                r = "true" === r ? !0 : "false" === r ? !1 : "null" === r ? null : +r + "" === r ? +r : ye.test(r) ? JSON.parse(r) : r
            } catch (o) {
            }
            ge.set(e, n, r)
        } else r = t;
        return r
    }

    function s() {
        return !0
    }

    function a() {
        return !1
    }

    function u() {
        try {
            return X.activeElement
        } catch (e) {
        }
    }

    function l(e, t) {
        for (; (e = e[t]) && 1 !== e.nodeType;);
        return e
    }

    function c(e, t, n) {
        if (oe.isFunction(t))return oe.grep(e, function (e, r) {
            return !!t.call(e, r, e) !== n
        });
        if (t.nodeType)return oe.grep(e, function (e) {
            return e === t !== n
        });
        if ("string" == typeof t) {
            if (Se.test(t))return oe.filter(t, e, n);
            t = oe.filter(t, e)
        }
        return oe.grep(e, function (e) {
            return te.call(t, e) >= 0 !== n
        })
    }

    function f(e, t) {
        return oe.nodeName(e, "table") && oe.nodeName(1 === t.nodeType ? t : t.firstChild, "tr") ? e.getElementsByTagName("tbody")[0] || e.appendChild(e.ownerDocument.createElement("tbody")) : e
    }

    function p(e) {
        return e.type = (null !== e.getAttribute("type")) + "/" + e.type, e
    }

    function d(e) {
        var t = We.exec(e.type);
        return t ? e.type = t[1] : e.removeAttribute("type"), e
    }

    function h(e, t) {
        for (var n = e.length, r = 0; n > r; r++)me.set(e[r], "globalEval", !t || me.get(t[r], "globalEval"))
    }

    function g(e, t) {
        var n, r, i, o, s, a, u, l;
        if (1 === t.nodeType) {
            if (me.hasData(e) && (o = me.access(e), s = me.set(t, o), l = o.events)) {
                delete s.handle, s.events = {};
                for (i in l)for (n = 0, r = l[i].length; r > n; n++)oe.event.add(t, i, l[i][n])
            }
            ge.hasData(e) && (a = ge.access(e), u = oe.extend({}, a), ge.set(t, u))
        }
    }

    function m(e, n) {
        var r = e.getElementsByTagName ? e.getElementsByTagName(n || "*") : e.querySelectorAll ? e.querySelectorAll(n || "*") : [];
        return n === t || n && oe.nodeName(e, n) ? oe.merge([e], r) : r
    }

    function y(e, t) {
        var n = t.nodeName.toLowerCase();
        "input" === n && Pe.test(e.type) ? t.checked = e.checked : ("input" === n || "textarea" === n) && (t.defaultValue = e.defaultValue)
    }

    function v(e, t) {
        if (t in e)return t;
        for (var n = t.charAt(0).toUpperCase() + t.slice(1), r = t, i = Ze.length; i--;)if (t = Ze[i] + n, t in e)return t;
        return r
    }

    function x(e, t) {
        return e = t || e, "none" === oe.css(e, "display") || !oe.contains(e.ownerDocument, e)
    }

    function b(t) {
        return e.getComputedStyle(t, null)
    }

    function w(e, t) {
        for (var n, r, i, o = [], s = 0, a = e.length; a > s; s++)r = e[s], r.style && (o[s] = me.get(r, "olddisplay"), n = r.style.display, t ? (o[s] || "none" !== n || (r.style.display = ""), "" === r.style.display && x(r) && (o[s] = me.access(r, "olddisplay", N(r.nodeName)))) : o[s] || (i = x(r), (n && "none" !== n || !i) && me.set(r, "olddisplay", i ? n : oe.css(r, "display"))));
        for (s = 0; a > s; s++)r = e[s], r.style && (t && "none" !== r.style.display && "" !== r.style.display || (r.style.display = t ? o[s] || "" : "none"));
        return e
    }

    function T(e, t, n) {
        var r = Ue.exec(t);
        return r ? Math.max(0, r[1] - (n || 0)) + (r[2] || "px") : t
    }

    function C(e, t, n, r, i) {
        for (var o = n === (r ? "border" : "content") ? 4 : "width" === t ? 1 : 0, s = 0; 4 > o; o += 2)"margin" === n && (s += oe.css(e, n + Ke[o], !0, i)), r ? ("content" === n && (s -= oe.css(e, "padding" + Ke[o], !0, i)), "margin" !== n && (s -= oe.css(e, "border" + Ke[o] + "Width", !0, i))) : (s += oe.css(e, "padding" + Ke[o], !0, i), "padding" !== n && (s += oe.css(e, "border" + Ke[o] + "Width", !0, i)));
        return s
    }

    function k(e, t, n) {
        var r = !0, i = "width" === t ? e.offsetWidth : e.offsetHeight, o = b(e), s = oe.support.boxSizing && "border-box" === oe.css(e, "boxSizing", !1, o);
        if (0 >= i || null == i) {
            if (i = Ie(e, t, o), (0 > i || null == i) && (i = e.style[t]), Ye.test(i))return i;
            r = s && (oe.support.boxSizingReliable || i === e.style[t]), i = parseFloat(i) || 0
        }
        return i + C(e, t, n || (s ? "border" : "content"), r, o) + "px"
    }

    function N(e) {
        var t = X, n = Ge[e];
        return n || (n = j(e, t), "none" !== n && n || (ze = (ze || oe("<iframe frameborder='0' width='0' height='0'/>").css("cssText", "display:block !important")).appendTo(t.documentElement), t = (ze[0].contentWindow || ze[0].contentDocument).document, t.write("<!doctype html><html><body>"), t.close(), n = j(e, t), ze.detach()), Ge[e] = n), n
    }

    function j(e, t) {
        var n = oe(t.createElement(e)).appendTo(t.body), r = oe.css(n[0], "display");
        return n.remove(), r
    }

    function E(e, t, n, r) {
        var i;
        if (oe.isArray(t))oe.each(t, function (t, i) {
            n || tt.test(e) ? r(e, i) : E(e + "[" + ("object" == typeof i ? t : "") + "]", i, n, r)
        }); else if (n || "object" !== oe.type(t))r(e, t); else for (i in t)E(e + "[" + i + "]", t[i], n, r)
    }

    function S(e) {
        return function (t, n) {
            "string" != typeof t && (n = t, t = "*");
            var r, i = 0, o = t.toLowerCase().match(ae) || [];
            if (oe.isFunction(n))for (; r = o[i++];)"+" === r[0] ? (r = r.slice(1) || "*", (e[r] = e[r] || []).unshift(n)) : (e[r] = e[r] || []).push(n)
        }
    }

    function D(e, t, n, r) {
        function i(a) {
            var u;
            return o[a] = !0, oe.each(e[a] || [], function (e, a) {
                var l = a(t, n, r);
                return "string" != typeof l || s || o[l] ? s ? !(u = l) : void 0 : (t.dataTypes.unshift(l), i(l), !1)
            }), u
        }

        var o = {}, s = e === vt;
        return i(t.dataTypes[0]) || !o["*"] && i("*")
    }

    function A(e, n) {
        var r, i, o = oe.ajaxSettings.flatOptions || {};
        for (r in n)n[r] !== t && ((o[r] ? e : i || (i = {}))[r] = n[r]);
        return i && oe.extend(!0, e, i), e
    }

    function q(e, n, r) {
        for (var i, o, s, a, u = e.contents, l = e.dataTypes; "*" === l[0];)l.shift(), i === t && (i = e.mimeType || n.getResponseHeader("Content-Type"));
        if (i)for (o in u)if (u[o] && u[o].test(i)) {
            l.unshift(o);
            break
        }
        if (l[0]in r)s = l[0]; else {
            for (o in r) {
                if (!l[0] || e.converters[o + " " + l[0]]) {
                    s = o;
                    break
                }
                a || (a = o)
            }
            s = s || a
        }
        return s ? (s !== l[0] && l.unshift(s), r[s]) : void 0
    }

    function L(e, t, n, r) {
        var i, o, s, a, u, l = {}, c = e.dataTypes.slice();
        if (c[1])for (s in e.converters)l[s.toLowerCase()] = e.converters[s];
        for (o = c.shift(); o;)if (e.responseFields[o] && (n[e.responseFields[o]] = t), !u && r && e.dataFilter && (t = e.dataFilter(t, e.dataType)), u = o, o = c.shift())if ("*" === o)o = u; else if ("*" !== u && u !== o) {
            if (s = l[u + " " + o] || l["* " + o], !s)for (i in l)if (a = i.split(" "), a[1] === o && (s = l[u + " " + a[0]] || l["* " + a[0]])) {
                s === !0 ? s = l[i] : l[i] !== !0 && (o = a[0], c.unshift(a[1]));
                break
            }
            if (s !== !0)if (s && e["throws"])t = s(t); else try {
                t = s(t)
            } catch (f) {
                return {state: "parsererror", error: s ? f : "No conversion from " + u + " to " + o}
            }
        }
        return {state: "success", data: t}
    }

    function H() {
        return setTimeout(function () {
            Et = t
        }), Et = oe.now()
    }

    function O(e, t, n) {
        for (var r, i = (Ht[t] || []).concat(Ht["*"]), o = 0, s = i.length; s > o; o++)if (r = i[o].call(n, t, e))return r
    }

    function F(e, t, n) {
        var r, i, o = 0, s = Lt.length, a = oe.Deferred().always(function () {
            delete u.elem
        }), u = function () {
            if (i)return !1;
            for (var t = Et || H(), n = Math.max(0, l.startTime + l.duration - t), r = n / l.duration || 0, o = 1 - r, s = 0, u = l.tweens.length; u > s; s++)l.tweens[s].run(o);
            return a.notifyWith(e, [l, o, n]), 1 > o && u ? n : (a.resolveWith(e, [l]), !1)
        }, l = a.promise({
            elem: e,
            props: oe.extend({}, t),
            opts: oe.extend(!0, {specialEasing: {}}, n),
            originalProperties: t,
            originalOptions: n,
            startTime: Et || H(),
            duration: n.duration,
            tweens: [],
            createTween: function (t, n) {
                var r = oe.Tween(e, l.opts, t, n, l.opts.specialEasing[t] || l.opts.easing);
                return l.tweens.push(r), r
            },
            stop: function (t) {
                var n = 0, r = t ? l.tweens.length : 0;
                if (i)return this;
                for (i = !0; r > n; n++)l.tweens[n].run(1);
                return t ? a.resolveWith(e, [l, t]) : a.rejectWith(e, [l, t]), this
            }
        }), c = l.props;
        for (P(c, l.opts.specialEasing); s > o; o++)if (r = Lt[o].call(l, e, c, l.opts))return r;
        return oe.map(c, O, l), oe.isFunction(l.opts.start) && l.opts.start.call(e, l), oe.fx.timer(oe.extend(u, {
            elem: e,
            anim: l,
            queue: l.opts.queue
        })), l.progress(l.opts.progress).done(l.opts.done, l.opts.complete).fail(l.opts.fail).always(l.opts.always)
    }

    function P(e, t) {
        var n, r, i, o, s;
        for (n in e)if (r = oe.camelCase(n), i = t[r], o = e[n], oe.isArray(o) && (i = o[1], o = e[n] = o[0]), n !== r && (e[r] = o, delete e[n]), s = oe.cssHooks[r], s && "expand"in s) {
            o = s.expand(o), delete e[r];
            for (n in o)n in e || (e[n] = o[n], t[n] = i)
        } else t[r] = i
    }

    function R(e, n, r) {
        var i, o, s, a, u, l, c = this, f = {}, p = e.style, d = e.nodeType && x(e), h = me.get(e, "fxshow");
        r.queue || (u = oe._queueHooks(e, "fx"), null == u.unqueued && (u.unqueued = 0, l = u.empty.fire, u.empty.fire = function () {
            u.unqueued || l()
        }), u.unqueued++, c.always(function () {
            c.always(function () {
                u.unqueued--, oe.queue(e, "fx").length || u.empty.fire()
            })
        })), 1 === e.nodeType && ("height"in n || "width"in n) && (r.overflow = [p.overflow, p.overflowX, p.overflowY], "inline" === oe.css(e, "display") && "none" === oe.css(e, "float") && (p.display = "inline-block")), r.overflow && (p.overflow = "hidden", c.always(function () {
            p.overflow = r.overflow[0], p.overflowX = r.overflow[1], p.overflowY = r.overflow[2]
        }));
        for (i in n)if (o = n[i], Dt.exec(o)) {
            if (delete n[i], s = s || "toggle" === o, o === (d ? "hide" : "show")) {
                if ("show" !== o || !h || h[i] === t)continue;
                d = !0
            }
            f[i] = h && h[i] || oe.style(e, i)
        }
        if (!oe.isEmptyObject(f)) {
            h ? "hidden"in h && (d = h.hidden) : h = me.access(e, "fxshow", {}), s && (h.hidden = !d), d ? oe(e).show() : c.done(function () {
                oe(e).hide()
            }), c.done(function () {
                var t;
                me.remove(e, "fxshow");
                for (t in f)oe.style(e, t, f[t])
            });
            for (i in f)a = O(d ? h[i] : 0, i, c), i in h || (h[i] = a.start, d && (a.end = a.start, a.start = "width" === i || "height" === i ? 1 : 0))
        }
    }

    function M(e, t, n, r, i) {
        return new M.prototype.init(e, t, n, r, i)
    }

    function W(e, t) {
        var n, r = {height: e}, i = 0;
        for (t = t ? 1 : 0; 4 > i; i += 2 - t)n = Ke[i], r["margin" + n] = r["padding" + n] = e;
        return t && (r.opacity = r.width = e), r
    }

    function $(e) {
        return oe.isWindow(e) ? e : 9 === e.nodeType && e.defaultView
    }

    var B, I, z = typeof t, _ = e.location, X = e.document, U = X.documentElement, Y = e.jQuery, V = e.$, G = {}, Q = [], J = "2.0.3", K = Q.concat, Z = Q.push, ee = Q.slice, te = Q.indexOf, ne = G.toString, re = G.hasOwnProperty, ie = J.trim, oe = function (e, t) {
        return new oe.fn.init(e, t, B)
    }, se = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source, ae = /\S+/g, ue = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/, le = /^<(\w+)\s*\/?>(?:<\/\1>|)$/, ce = /^-ms-/, fe = /-([\da-z])/gi, pe = function (e, t) {
        return t.toUpperCase()
    }, de = function () {
        X.removeEventListener("DOMContentLoaded", de, !1), e.removeEventListener("load", de, !1), oe.ready()
    };
    oe.fn = oe.prototype = {
        jquery: J, constructor: oe, init: function (e, n, r) {
            var i, o;
            if (!e)return this;
            if ("string" == typeof e) {
                if (i = "<" === e.charAt(0) && ">" === e.charAt(e.length - 1) && e.length >= 3 ? [null, e, null] : ue.exec(e), !i || !i[1] && n)return !n || n.jquery ? (n || r).find(e) : this.constructor(n).find(e);
                if (i[1]) {
                    if (n = n instanceof oe ? n[0] : n, oe.merge(this, oe.parseHTML(i[1], n && n.nodeType ? n.ownerDocument || n : X, !0)), le.test(i[1]) && oe.isPlainObject(n))for (i in n)oe.isFunction(this[i]) ? this[i](n[i]) : this.attr(i, n[i]);
                    return this
                }
                return o = X.getElementById(i[2]), o && o.parentNode && (this.length = 1, this[0] = o), this.context = X, this.selector = e, this
            }
            return e.nodeType ? (this.context = this[0] = e, this.length = 1, this) : oe.isFunction(e) ? r.ready(e) : (e.selector !== t && (this.selector = e.selector, this.context = e.context), oe.makeArray(e, this))
        }, selector: "", length: 0, toArray: function () {
            return ee.call(this)
        }, get: function (e) {
            return null == e ? this.toArray() : 0 > e ? this[this.length + e] : this[e]
        }, pushStack: function (e) {
            var t = oe.merge(this.constructor(), e);
            return t.prevObject = this, t.context = this.context, t
        }, each: function (e, t) {
            return oe.each(this, e, t)
        }, ready: function (e) {
            return oe.ready.promise().done(e), this
        }, slice: function () {
            return this.pushStack(ee.apply(this, arguments))
        }, first: function () {
            return this.eq(0)
        }, last: function () {
            return this.eq(-1)
        }, eq: function (e) {
            var t = this.length, n = +e + (0 > e ? t : 0);
            return this.pushStack(n >= 0 && t > n ? [this[n]] : [])
        }, map: function (e) {
            return this.pushStack(oe.map(this, function (t, n) {
                return e.call(t, n, t)
            }))
        }, end: function () {
            return this.prevObject || this.constructor(null)
        }, push: Z, sort: [].sort, splice: [].splice
    }, oe.fn.init.prototype = oe.fn, oe.extend = oe.fn.extend = function () {
        var e, n, r, i, o, s, a = arguments[0] || {}, u = 1, l = arguments.length, c = !1;
        for ("boolean" == typeof a && (c = a, a = arguments[1] || {}, u = 2), "object" == typeof a || oe.isFunction(a) || (a = {}), l === u && (a = this, --u); l > u; u++)if (null != (e = arguments[u]))for (n in e)r = a[n], i = e[n], a !== i && (c && i && (oe.isPlainObject(i) || (o = oe.isArray(i))) ? (o ? (o = !1, s = r && oe.isArray(r) ? r : []) : s = r && oe.isPlainObject(r) ? r : {}, a[n] = oe.extend(c, s, i)) : i !== t && (a[n] = i));
        return a
    }, oe.extend({
        expando: "jQuery" + (J + Math.random()).replace(/\D/g, ""), noConflict: function (t) {
            return e.$ === oe && (e.$ = V), t && e.jQuery === oe && (e.jQuery = Y), oe
        }, isReady: !1, readyWait: 1, holdReady: function (e) {
            e ? oe.readyWait++ : oe.ready(!0)
        }, ready: function (e) {
            (e === !0 ? --oe.readyWait : oe.isReady) || (oe.isReady = !0, e !== !0 && --oe.readyWait > 0 || (I.resolveWith(X, [oe]), oe.fn.trigger && oe(X).trigger("ready").off("ready")))
        }, isFunction: function (e) {
            return "function" === oe.type(e)
        }, isArray: Array.isArray, isWindow: function (e) {
            return null != e && e === e.window
        }, isNumeric: function (e) {
            return !isNaN(parseFloat(e)) && isFinite(e)
        }, type: function (e) {
            return null == e ? String(e) : "object" == typeof e || "function" == typeof e ? G[ne.call(e)] || "object" : typeof e
        }, isPlainObject: function (e) {
            if ("object" !== oe.type(e) || e.nodeType || oe.isWindow(e))return !1;
            try {
                if (e.constructor && !re.call(e.constructor.prototype, "isPrototypeOf"))return !1
            } catch (t) {
                return !1
            }
            return !0
        }, isEmptyObject: function (e) {
            var t;
            for (t in e)return !1;
            return !0
        }, error: function (e) {
            throw new Error(e)
        }, parseHTML: function (e, t, n) {
            if (!e || "string" != typeof e)return null;
            "boolean" == typeof t && (n = t, t = !1), t = t || X;
            var r = le.exec(e), i = !n && [];
            return r ? [t.createElement(r[1])] : (r = oe.buildFragment([e], t, i), i && oe(i).remove(), oe.merge([], r.childNodes))
        }, parseJSON: JSON.parse, parseXML: function (e) {
            var n, r;
            if (!e || "string" != typeof e)return null;
            try {
                r = new DOMParser, n = r.parseFromString(e, "text/xml")
            } catch (i) {
                n = t
            }
            return (!n || n.getElementsByTagName("parsererror").length) && oe.error("Invalid XML: " + e), n
        }, noop: function () {
        }, globalEval: function (e) {
            var t, n = eval;
            e = oe.trim(e), e && (1 === e.indexOf("use strict") ? (t = X.createElement("script"), t.text = e, X.head.appendChild(t).parentNode.removeChild(t)) : n(e))
        }, camelCase: function (e) {
            return e.replace(ce, "ms-").replace(fe, pe)
        }, nodeName: function (e, t) {
            return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase()
        }, each: function (e, t, r) {
            var i, o = 0, s = e.length, a = n(e);
            if (r) {
                if (a)for (; s > o && (i = t.apply(e[o], r), i !== !1); o++); else for (o in e)if (i = t.apply(e[o], r), i === !1)break
            } else if (a)for (; s > o && (i = t.call(e[o], o, e[o]), i !== !1); o++); else for (o in e)if (i = t.call(e[o], o, e[o]), i === !1)break;
            return e
        }, trim: function (e) {
            return null == e ? "" : ie.call(e)
        }, makeArray: function (e, t) {
            var r = t || [];
            return null != e && (n(Object(e)) ? oe.merge(r, "string" == typeof e ? [e] : e) : Z.call(r, e)), r
        }, inArray: function (e, t, n) {
            return null == t ? -1 : te.call(t, e, n)
        }, merge: function (e, n) {
            var r = n.length, i = e.length, o = 0;
            if ("number" == typeof r)for (; r > o; o++)e[i++] = n[o]; else for (; n[o] !== t;)e[i++] = n[o++];
            return e.length = i, e
        }, grep: function (e, t, n) {
            var r, i = [], o = 0, s = e.length;
            for (n = !!n; s > o; o++)r = !!t(e[o], o), n !== r && i.push(e[o]);
            return i
        }, map: function (e, t, r) {
            var i, o = 0, s = e.length, a = n(e), u = [];
            if (a)for (; s > o; o++)i = t(e[o], o, r), null != i && (u[u.length] = i); else for (o in e)i = t(e[o], o, r), null != i && (u[u.length] = i);
            return K.apply([], u)
        }, guid: 1, proxy: function (e, n) {
            var r, i, o;
            return "string" == typeof n && (r = e[n], n = e, e = r), oe.isFunction(e) ? (i = ee.call(arguments, 2), o = function () {
                return e.apply(n || this, i.concat(ee.call(arguments)))
            }, o.guid = e.guid = e.guid || oe.guid++, o) : t
        }, access: function (e, n, r, i, o, s, a) {
            var u = 0, l = e.length, c = null == r;
            if ("object" === oe.type(r)) {
                o = !0;
                for (u in r)oe.access(e, n, u, r[u], !0, s, a)
            } else if (i !== t && (o = !0, oe.isFunction(i) || (a = !0), c && (a ? (n.call(e, i), n = null) : (c = n, n = function (e, t, n) {
                    return c.call(oe(e), n)
                })), n))for (; l > u; u++)n(e[u], r, a ? i : i.call(e[u], u, n(e[u], r)));
            return o ? e : c ? n.call(e) : l ? n(e[0], r) : s
        }, now: Date.now, swap: function (e, t, n, r) {
            var i, o, s = {};
            for (o in t)s[o] = e.style[o], e.style[o] = t[o];
            i = n.apply(e, r || []);
            for (o in t)e.style[o] = s[o];
            return i
        }
    }), oe.ready.promise = function (t) {
        return I || (I = oe.Deferred(), "complete" === X.readyState ? setTimeout(oe.ready) : (X.addEventListener("DOMContentLoaded", de, !1), e.addEventListener("load", de, !1))), I.promise(t)
    }, oe.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function (e, t) {
        G["[object " + t + "]"] = t.toLowerCase()
    }), B = oe(X), function (e, t) {
        function n(e, t, n, r) {
            var i, o, s, a, u, l, c, f, h, g;
            if ((t ? t.ownerDocument || t : $) !== L && q(t), t = t || L, n = n || [], !e || "string" != typeof e)return n;
            if (1 !== (a = t.nodeType) && 9 !== a)return [];
            if (O && !r) {
                if (i = xe.exec(e))if (s = i[1]) {
                    if (9 === a) {
                        if (o = t.getElementById(s), !o || !o.parentNode)return n;
                        if (o.id === s)return n.push(o), n
                    } else if (t.ownerDocument && (o = t.ownerDocument.getElementById(s)) && M(t, o) && o.id === s)return n.push(o), n
                } else {
                    if (i[2])return ee.apply(n, t.getElementsByTagName(e)), n;
                    if ((s = i[3]) && C.getElementsByClassName && t.getElementsByClassName)return ee.apply(n, t.getElementsByClassName(s)), n
                }
                if (C.qsa && (!F || !F.test(e))) {
                    if (f = c = W, h = t, g = 9 === a && e, 1 === a && "object" !== t.nodeName.toLowerCase()) {
                        for (l = p(e), (c = t.getAttribute("id")) ? f = c.replace(Te, "\\$&") : t.setAttribute("id", f), f = "[id='" + f + "'] ", u = l.length; u--;)l[u] = f + d(l[u]);
                        h = de.test(e) && t.parentNode || t, g = l.join(",")
                    }
                    if (g)try {
                        return ee.apply(n, h.querySelectorAll(g)), n
                    } catch (m) {
                    } finally {
                        c || t.removeAttribute("id")
                    }
                }
            }
            return w(e.replace(ce, "$1"), t, n, r)
        }

        function r() {
            function e(n, r) {
                return t.push(n += " ") > N.cacheLength && delete e[t.shift()], e[n] = r
            }

            var t = [];
            return e
        }

        function i(e) {
            return e[W] = !0, e
        }

        function o(e) {
            var t = L.createElement("div");
            try {
                return !!e(t)
            } catch (n) {
                return !1
            } finally {
                t.parentNode && t.parentNode.removeChild(t), t = null
            }
        }

        function s(e, t) {
            for (var n = e.split("|"), r = e.length; r--;)N.attrHandle[n[r]] = t
        }

        function a(e, t) {
            var n = t && e, r = n && 1 === e.nodeType && 1 === t.nodeType && (~t.sourceIndex || G) - (~e.sourceIndex || G);
            if (r)return r;
            if (n)for (; n = n.nextSibling;)if (n === t)return -1;
            return e ? 1 : -1
        }

        function u(e) {
            return function (t) {
                var n = t.nodeName.toLowerCase();
                return "input" === n && t.type === e
            }
        }

        function l(e) {
            return function (t) {
                var n = t.nodeName.toLowerCase();
                return ("input" === n || "button" === n) && t.type === e
            }
        }

        function c(e) {
            return i(function (t) {
                return t = +t, i(function (n, r) {
                    for (var i, o = e([], n.length, t), s = o.length; s--;)n[i = o[s]] && (n[i] = !(r[i] = n[i]))
                })
            })
        }

        function f() {
        }

        function p(e, t) {
            var r, i, o, s, a, u, l, c = _[e + " "];
            if (c)return t ? 0 : c.slice(0);
            for (a = e, u = [], l = N.preFilter; a;) {
                (!r || (i = fe.exec(a))) && (i && (a = a.slice(i[0].length) || a), u.push(o = [])), r = !1, (i = pe.exec(a)) && (r = i.shift(), o.push({
                    value: r,
                    type: i[0].replace(ce, " ")
                }), a = a.slice(r.length));
                for (s in N.filter)!(i = ye[s].exec(a)) || l[s] && !(i = l[s](i)) || (r = i.shift(), o.push({
                    value: r,
                    type: s,
                    matches: i
                }), a = a.slice(r.length));
                if (!r)break
            }
            return t ? a.length : a ? n.error(e) : _(e, u).slice(0)
        }

        function d(e) {
            for (var t = 0, n = e.length, r = ""; n > t; t++)r += e[t].value;
            return r
        }

        function h(e, t, n) {
            var r = t.dir, i = n && "parentNode" === r, o = I++;
            return t.first ? function (t, n, o) {
                for (; t = t[r];)if (1 === t.nodeType || i)return e(t, n, o)
            } : function (t, n, s) {
                var a, u, l, c = B + " " + o;
                if (s) {
                    for (; t = t[r];)if ((1 === t.nodeType || i) && e(t, n, s))return !0
                } else for (; t = t[r];)if (1 === t.nodeType || i)if (l = t[W] || (t[W] = {}), (u = l[r]) && u[0] === c) {
                    if ((a = u[1]) === !0 || a === k)return a === !0
                } else if (u = l[r] = [c], u[1] = e(t, n, s) || k, u[1] === !0)return !0
            }
        }

        function g(e) {
            return e.length > 1 ? function (t, n, r) {
                for (var i = e.length; i--;)if (!e[i](t, n, r))return !1;
                return !0
            } : e[0]
        }

        function m(e, t, n, r, i) {
            for (var o, s = [], a = 0, u = e.length, l = null != t; u > a; a++)(o = e[a]) && (!n || n(o, r, i)) && (s.push(o), l && t.push(a));
            return s
        }

        function y(e, t, n, r, o, s) {
            return r && !r[W] && (r = y(r)), o && !o[W] && (o = y(o, s)), i(function (i, s, a, u) {
                var l, c, f, p = [], d = [], h = s.length, g = i || b(t || "*", a.nodeType ? [a] : a, []), y = !e || !i && t ? g : m(g, p, e, a, u), v = n ? o || (i ? e : h || r) ? [] : s : y;
                if (n && n(y, v, a, u), r)for (l = m(v, d), r(l, [], a, u), c = l.length; c--;)(f = l[c]) && (v[d[c]] = !(y[d[c]] = f));
                if (i) {
                    if (o || e) {
                        if (o) {
                            for (l = [], c = v.length; c--;)(f = v[c]) && l.push(y[c] = f);
                            o(null, v = [], l, u)
                        }
                        for (c = v.length; c--;)(f = v[c]) && (l = o ? ne.call(i, f) : p[c]) > -1 && (i[l] = !(s[l] = f))
                    }
                } else v = m(v === s ? v.splice(h, v.length) : v), o ? o(null, s, v, u) : ee.apply(s, v)
            })
        }

        function v(e) {
            for (var t, n, r, i = e.length, o = N.relative[e[0].type], s = o || N.relative[" "], a = o ? 1 : 0, u = h(function (e) {
                return e === t
            }, s, !0), l = h(function (e) {
                return ne.call(t, e) > -1
            }, s, !0), c = [function (e, n, r) {
                return !o && (r || n !== D) || ((t = n).nodeType ? u(e, n, r) : l(e, n, r))
            }]; i > a; a++)if (n = N.relative[e[a].type])c = [h(g(c), n)]; else {
                if (n = N.filter[e[a].type].apply(null, e[a].matches), n[W]) {
                    for (r = ++a; i > r && !N.relative[e[r].type]; r++);
                    return y(a > 1 && g(c), a > 1 && d(e.slice(0, a - 1).concat({value: " " === e[a - 2].type ? "*" : ""})).replace(ce, "$1"), n, r > a && v(e.slice(a, r)), i > r && v(e = e.slice(r)), i > r && d(e))
                }
                c.push(n)
            }
            return g(c)
        }

        function x(e, t) {
            var r = 0, o = t.length > 0, s = e.length > 0, a = function (i, a, u, l, c) {
                var f, p, d, h = [], g = 0, y = "0", v = i && [], x = null != c, b = D, w = i || s && N.find.TAG("*", c && a.parentNode || a), T = B += null == b ? 1 : Math.random() || .1;
                for (x && (D = a !== L && a, k = r); null != (f = w[y]); y++) {
                    if (s && f) {
                        for (p = 0; d = e[p++];)if (d(f, a, u)) {
                            l.push(f);
                            break
                        }
                        x && (B = T, k = ++r)
                    }
                    o && ((f = !d && f) && g--, i && v.push(f))
                }
                if (g += y, o && y !== g) {
                    for (p = 0; d = t[p++];)d(v, h, a, u);
                    if (i) {
                        if (g > 0)for (; y--;)v[y] || h[y] || (h[y] = K.call(l));
                        h = m(h)
                    }
                    ee.apply(l, h), x && !i && h.length > 0 && g + t.length > 1 && n.uniqueSort(l)
                }
                return x && (B = T, D = b), v
            };
            return o ? i(a) : a
        }

        function b(e, t, r) {
            for (var i = 0, o = t.length; o > i; i++)n(e, t[i], r);
            return r
        }

        function w(e, t, n, r) {
            var i, o, s, a, u, l = p(e);
            if (!r && 1 === l.length) {
                if (o = l[0] = l[0].slice(0), o.length > 2 && "ID" === (s = o[0]).type && C.getById && 9 === t.nodeType && O && N.relative[o[1].type]) {
                    if (t = (N.find.ID(s.matches[0].replace(Ce, ke), t) || [])[0], !t)return n;
                    e = e.slice(o.shift().value.length)
                }
                for (i = ye.needsContext.test(e) ? 0 : o.length; i-- && (s = o[i], !N.relative[a = s.type]);)if ((u = N.find[a]) && (r = u(s.matches[0].replace(Ce, ke), de.test(o[0].type) && t.parentNode || t))) {
                    if (o.splice(i, 1), e = r.length && d(o), !e)return ee.apply(n, r), n;
                    break
                }
            }
            return S(e, l)(r, t, !O, n, de.test(e)), n
        }

        var T, C, k, N, j, E, S, D, A, q, L, H, O, F, P, R, M, W = "sizzle" + -new Date, $ = e.document, B = 0, I = 0, z = r(), _ = r(), X = r(), U = !1, Y = function (e, t) {
            return e === t ? (U = !0, 0) : 0
        }, V = typeof t, G = 1 << 31, Q = {}.hasOwnProperty, J = [], K = J.pop, Z = J.push, ee = J.push, te = J.slice, ne = J.indexOf || function (e) {
                for (var t = 0, n = this.length; n > t; t++)if (this[t] === e)return t;
                return -1
            }, re = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped", ie = "[\\x20\\t\\r\\n\\f]", se = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+", ae = se.replace("w", "w#"), ue = "\\[" + ie + "*(" + se + ")" + ie + "*(?:([*^$|!~]?=)" + ie + "*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|(" + ae + ")|)|)" + ie + "*\\]", le = ":(" + se + ")(?:\\(((['\"])((?:\\\\.|[^\\\\])*?)\\3|((?:\\\\.|[^\\\\()[\\]]|" + ue.replace(3, 8) + ")*)|.*)\\)|)", ce = new RegExp("^" + ie + "+|((?:^|[^\\\\])(?:\\\\.)*)" + ie + "+$", "g"), fe = new RegExp("^" + ie + "*," + ie + "*"), pe = new RegExp("^" + ie + "*([>+~]|" + ie + ")" + ie + "*"), de = new RegExp(ie + "*[+~]"), he = new RegExp("=" + ie + "*([^\\]'\"]*)" + ie + "*\\]", "g"), ge = new RegExp(le), me = new RegExp("^" + ae + "$"), ye = {
            ID: new RegExp("^#(" + se + ")"),
            CLASS: new RegExp("^\\.(" + se + ")"),
            TAG: new RegExp("^(" + se.replace("w", "w*") + ")"),
            ATTR: new RegExp("^" + ue),
            PSEUDO: new RegExp("^" + le),
            CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + ie + "*(even|odd|(([+-]|)(\\d*)n|)" + ie + "*(?:([+-]|)" + ie + "*(\\d+)|))" + ie + "*\\)|)", "i"),
            bool: new RegExp("^(?:" + re + ")$", "i"),
            needsContext: new RegExp("^" + ie + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + ie + "*((?:-\\d)?\\d*)" + ie + "*\\)|)(?=[^-]|$)", "i")
        }, ve = /^[^{]+\{\s*\[native \w/, xe = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/, be = /^(?:input|select|textarea|button)$/i, we = /^h\d$/i, Te = /'|\\/g, Ce = new RegExp("\\\\([\\da-f]{1,6}" + ie + "?|(" + ie + ")|.)", "ig"), ke = function (e, t, n) {
            var r = "0x" + t - 65536;
            return r !== r || n ? t : 0 > r ? String.fromCharCode(r + 65536) : String.fromCharCode(r >> 10 | 55296, 1023 & r | 56320)
        };
        try {
            ee.apply(J = te.call($.childNodes), $.childNodes), J[$.childNodes.length].nodeType
        } catch (Ne) {
            ee = {
                apply: J.length ? function (e, t) {
                    Z.apply(e, te.call(t))
                } : function (e, t) {
                    for (var n = e.length, r = 0; e[n++] = t[r++];);
                    e.length = n - 1
                }
            }
        }
        E = n.isXML = function (e) {
            var t = e && (e.ownerDocument || e).documentElement;
            return t ? "HTML" !== t.nodeName : !1
        }, C = n.support = {}, q = n.setDocument = function (e) {
            var t = e ? e.ownerDocument || e : $, n = t.defaultView;
            return t !== L && 9 === t.nodeType && t.documentElement ? (L = t, H = t.documentElement, O = !E(t), n && n.attachEvent && n !== n.top && n.attachEvent("onbeforeunload", function () {
                q()
            }), C.attributes = o(function (e) {
                return e.className = "i", !e.getAttribute("className")
            }), C.getElementsByTagName = o(function (e) {
                return e.appendChild(t.createComment("")), !e.getElementsByTagName("*").length
            }), C.getElementsByClassName = o(function (e) {
                return e.innerHTML = "<div class='a'></div><div class='a i'></div>", e.firstChild.className = "i", 2 === e.getElementsByClassName("i").length
            }), C.getById = o(function (e) {
                return H.appendChild(e).id = W, !t.getElementsByName || !t.getElementsByName(W).length
            }), C.getById ? (N.find.ID = function (e, t) {
                if (typeof t.getElementById !== V && O) {
                    var n = t.getElementById(e);
                    return n && n.parentNode ? [n] : []
                }
            }, N.filter.ID = function (e) {
                var t = e.replace(Ce, ke);
                return function (e) {
                    return e.getAttribute("id") === t
                }
            }) : (delete N.find.ID, N.filter.ID = function (e) {
                var t = e.replace(Ce, ke);
                return function (e) {
                    var n = typeof e.getAttributeNode !== V && e.getAttributeNode("id");
                    return n && n.value === t
                }
            }), N.find.TAG = C.getElementsByTagName ? function (e, t) {
                return typeof t.getElementsByTagName !== V ? t.getElementsByTagName(e) : void 0
            } : function (e, t) {
                var n, r = [], i = 0, o = t.getElementsByTagName(e);
                if ("*" === e) {
                    for (; n = o[i++];)1 === n.nodeType && r.push(n);
                    return r
                }
                return o
            }, N.find.CLASS = C.getElementsByClassName && function (e, t) {
                    return typeof t.getElementsByClassName !== V && O ? t.getElementsByClassName(e) : void 0
                }, P = [], F = [], (C.qsa = ve.test(t.querySelectorAll)) && (o(function (e) {
                e.innerHTML = "<select><option selected=''></option></select>", e.querySelectorAll("[selected]").length || F.push("\\[" + ie + "*(?:value|" + re + ")"), e.querySelectorAll(":checked").length || F.push(":checked")
            }), o(function (e) {
                var n = t.createElement("input");
                n.setAttribute("type", "hidden"), e.appendChild(n).setAttribute("t", ""), e.querySelectorAll("[t^='']").length && F.push("[*^$]=" + ie + "*(?:''|\"\")"), e.querySelectorAll(":enabled").length || F.push(":enabled", ":disabled"), e.querySelectorAll("*,:x"), F.push(",.*:")
            })), (C.matchesSelector = ve.test(R = H.webkitMatchesSelector || H.mozMatchesSelector || H.oMatchesSelector || H.msMatchesSelector)) && o(function (e) {
                C.disconnectedMatch = R.call(e, "div"), R.call(e, "[s!='']:x"), P.push("!=", le)
            }), F = F.length && new RegExp(F.join("|")), P = P.length && new RegExp(P.join("|")), M = ve.test(H.contains) || H.compareDocumentPosition ? function (e, t) {
                var n = 9 === e.nodeType ? e.documentElement : e, r = t && t.parentNode;
                return e === r || !(!r || 1 !== r.nodeType || !(n.contains ? n.contains(r) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(r)))
            } : function (e, t) {
                if (t)for (; t = t.parentNode;)if (t === e)return !0;
                return !1
            }, Y = H.compareDocumentPosition ? function (e, n) {
                if (e === n)return U = !0, 0;
                var r = n.compareDocumentPosition && e.compareDocumentPosition && e.compareDocumentPosition(n);
                return r ? 1 & r || !C.sortDetached && n.compareDocumentPosition(e) === r ? e === t || M($, e) ? -1 : n === t || M($, n) ? 1 : A ? ne.call(A, e) - ne.call(A, n) : 0 : 4 & r ? -1 : 1 : e.compareDocumentPosition ? -1 : 1
            } : function (e, n) {
                var r, i = 0, o = e.parentNode, s = n.parentNode, u = [e], l = [n];
                if (e === n)return U = !0, 0;
                if (!o || !s)return e === t ? -1 : n === t ? 1 : o ? -1 : s ? 1 : A ? ne.call(A, e) - ne.call(A, n) : 0;
                if (o === s)return a(e, n);
                for (r = e; r = r.parentNode;)u.unshift(r);
                for (r = n; r = r.parentNode;)l.unshift(r);
                for (; u[i] === l[i];)i++;
                return i ? a(u[i], l[i]) : u[i] === $ ? -1 : l[i] === $ ? 1 : 0
            }, t) : L
        }, n.matches = function (e, t) {
            return n(e, null, null, t)
        }, n.matchesSelector = function (e, t) {
            if ((e.ownerDocument || e) !== L && q(e), t = t.replace(he, "='$1']"), !(!C.matchesSelector || !O || P && P.test(t) || F && F.test(t)))try {
                var r = R.call(e, t);
                if (r || C.disconnectedMatch || e.document && 11 !== e.document.nodeType)return r
            } catch (i) {
            }
            return n(t, L, null, [e]).length > 0
        }, n.contains = function (e, t) {
            return (e.ownerDocument || e) !== L && q(e), M(e, t)
        }, n.attr = function (e, n) {
            (e.ownerDocument || e) !== L && q(e);
            var r = N.attrHandle[n.toLowerCase()], i = r && Q.call(N.attrHandle, n.toLowerCase()) ? r(e, n, !O) : t;
            return i === t ? C.attributes || !O ? e.getAttribute(n) : (i = e.getAttributeNode(n)) && i.specified ? i.value : null : i
        }, n.error = function (e) {
            throw new Error("Syntax error, unrecognized expression: " + e)
        }, n.uniqueSort = function (e) {
            var t, n = [], r = 0, i = 0;
            if (U = !C.detectDuplicates, A = !C.sortStable && e.slice(0), e.sort(Y), U) {
                for (; t = e[i++];)t === e[i] && (r = n.push(i));
                for (; r--;)e.splice(n[r], 1)
            }
            return e
        }, j = n.getText = function (e) {
            var t, n = "", r = 0, i = e.nodeType;
            if (i) {
                if (1 === i || 9 === i || 11 === i) {
                    if ("string" == typeof e.textContent)return e.textContent;
                    for (e = e.firstChild; e; e = e.nextSibling)n += j(e)
                } else if (3 === i || 4 === i)return e.nodeValue
            } else for (; t = e[r]; r++)n += j(t);
            return n
        }, N = n.selectors = {
            cacheLength: 50,
            createPseudo: i,
            match: ye,
            attrHandle: {},
            find: {},
            relative: {">": {dir: "parentNode", first: !0}, " ": {dir: "parentNode"}, "+": {dir: "previousSibling", first: !0}, "~": {dir: "previousSibling"}},
            preFilter: {
                ATTR: function (e) {
                    return e[1] = e[1].replace(Ce, ke), e[3] = (e[4] || e[5] || "").replace(Ce, ke), "~=" === e[2] && (e[3] = " " + e[3] + " "), e.slice(0, 4)
                }, CHILD: function (e) {
                    return e[1] = e[1].toLowerCase(), "nth" === e[1].slice(0, 3) ? (e[3] || n.error(e[0]), e[4] = +(e[4] ? e[5] + (e[6] || 1) : 2 * ("even" === e[3] || "odd" === e[3])), e[5] = +(e[7] + e[8] || "odd" === e[3])) : e[3] && n.error(e[0]), e
                }, PSEUDO: function (e) {
                    var n, r = !e[5] && e[2];
                    return ye.CHILD.test(e[0]) ? null : (e[3] && e[4] !== t ? e[2] = e[4] : r && ge.test(r) && (n = p(r, !0)) && (n = r.indexOf(")", r.length - n) - r.length) && (e[0] = e[0].slice(0, n), e[2] = r.slice(0, n)), e.slice(0, 3))
                }
            },
            filter: {
                TAG: function (e) {
                    var t = e.replace(Ce, ke).toLowerCase();
                    return "*" === e ? function () {
                        return !0
                    } : function (e) {
                        return e.nodeName && e.nodeName.toLowerCase() === t
                    }
                }, CLASS: function (e) {
                    var t = z[e + " "];
                    return t || (t = new RegExp("(^|" + ie + ")" + e + "(" + ie + "|$)")) && z(e, function (e) {
                            return t.test("string" == typeof e.className && e.className || typeof e.getAttribute !== V && e.getAttribute("class") || "")
                        })
                }, ATTR: function (e, t, r) {
                    return function (i) {
                        var o = n.attr(i, e);
                        return null == o ? "!=" === t : t ? (o += "", "=" === t ? o === r : "!=" === t ? o !== r : "^=" === t ? r && 0 === o.indexOf(r) : "*=" === t ? r && o.indexOf(r) > -1 : "$=" === t ? r && o.slice(-r.length) === r : "~=" === t ? (" " + o + " ").indexOf(r) > -1 : "|=" === t ? o === r || o.slice(0, r.length + 1) === r + "-" : !1) : !0
                    }
                }, CHILD: function (e, t, n, r, i) {
                    var o = "nth" !== e.slice(0, 3), s = "last" !== e.slice(-4), a = "of-type" === t;
                    return 1 === r && 0 === i ? function (e) {
                        return !!e.parentNode
                    } : function (t, n, u) {
                        var l, c, f, p, d, h, g = o !== s ? "nextSibling" : "previousSibling", m = t.parentNode, y = a && t.nodeName.toLowerCase(), v = !u && !a;
                        if (m) {
                            if (o) {
                                for (; g;) {
                                    for (f = t; f = f[g];)if (a ? f.nodeName.toLowerCase() === y : 1 === f.nodeType)return !1;
                                    h = g = "only" === e && !h && "nextSibling"
                                }
                                return !0
                            }
                            if (h = [s ? m.firstChild : m.lastChild], s && v) {
                                for (c = m[W] || (m[W] = {}), l = c[e] || [], d = l[0] === B && l[1], p = l[0] === B && l[2], f = d && m.childNodes[d]; f = ++d && f && f[g] || (p = d = 0) || h.pop();)if (1 === f.nodeType && ++p && f === t) {
                                    c[e] = [B, d, p];
                                    break
                                }
                            } else if (v && (l = (t[W] || (t[W] = {}))[e]) && l[0] === B)p = l[1]; else for (; (f = ++d && f && f[g] || (p = d = 0) || h.pop()) && ((a ? f.nodeName.toLowerCase() !== y : 1 !== f.nodeType) || !++p || (v && ((f[W] || (f[W] = {}))[e] = [B, p]), f !== t)););
                            return p -= i, p === r || p % r === 0 && p / r >= 0
                        }
                    }
                }, PSEUDO: function (e, t) {
                    var r, o = N.pseudos[e] || N.setFilters[e.toLowerCase()] || n.error("unsupported pseudo: " + e);
                    return o[W] ? o(t) : o.length > 1 ? (r = [e, e, "", t], N.setFilters.hasOwnProperty(e.toLowerCase()) ? i(function (e, n) {
                        for (var r, i = o(e, t), s = i.length; s--;)r = ne.call(e, i[s]), e[r] = !(n[r] = i[s])
                    }) : function (e) {
                        return o(e, 0, r)
                    }) : o
                }
            },
            pseudos: {
                not: i(function (e) {
                    var t = [], n = [], r = S(e.replace(ce, "$1"));
                    return r[W] ? i(function (e, t, n, i) {
                        for (var o, s = r(e, null, i, []), a = e.length; a--;)(o = s[a]) && (e[a] = !(t[a] = o))
                    }) : function (e, i, o) {
                        return t[0] = e, r(t, null, o, n), !n.pop()
                    }
                }), has: i(function (e) {
                    return function (t) {
                        return n(e, t).length > 0
                    }
                }), contains: i(function (e) {
                    return function (t) {
                        return (t.textContent || t.innerText || j(t)).indexOf(e) > -1
                    }
                }), lang: i(function (e) {
                    return me.test(e || "") || n.error("unsupported lang: " + e), e = e.replace(Ce, ke).toLowerCase(), function (t) {
                        var n;
                        do if (n = O ? t.lang : t.getAttribute("xml:lang") || t.getAttribute("lang"))return n = n.toLowerCase(), n === e || 0 === n.indexOf(e + "-"); while ((t = t.parentNode) && 1 === t.nodeType);
                        return !1
                    }
                }), target: function (t) {
                    var n = e.location && e.location.hash;
                    return n && n.slice(1) === t.id
                }, root: function (e) {
                    return e === H
                }, focus: function (e) {
                    return e === L.activeElement && (!L.hasFocus || L.hasFocus()) && !!(e.type || e.href || ~e.tabIndex)
                }, enabled: function (e) {
                    return e.disabled === !1
                }, disabled: function (e) {
                    return e.disabled === !0
                }, checked: function (e) {
                    var t = e.nodeName.toLowerCase();
                    return "input" === t && !!e.checked || "option" === t && !!e.selected
                }, selected: function (e) {
                    return e.parentNode && e.parentNode.selectedIndex, e.selected === !0
                }, empty: function (e) {
                    for (e = e.firstChild; e; e = e.nextSibling)if (e.nodeName > "@" || 3 === e.nodeType || 4 === e.nodeType)return !1;
                    return !0
                }, parent: function (e) {
                    return !N.pseudos.empty(e)
                }, header: function (e) {
                    return we.test(e.nodeName)
                }, input: function (e) {
                    return be.test(e.nodeName)
                }, button: function (e) {
                    var t = e.nodeName.toLowerCase();
                    return "input" === t && "button" === e.type || "button" === t
                }, text: function (e) {
                    var t;
                    return "input" === e.nodeName.toLowerCase() && "text" === e.type && (null == (t = e.getAttribute("type")) || t.toLowerCase() === e.type)
                }, first: c(function () {
                    return [0]
                }), last: c(function (e, t) {
                    return [t - 1]
                }), eq: c(function (e, t, n) {
                    return [0 > n ? n + t : n]
                }), even: c(function (e, t) {
                    for (var n = 0; t > n; n += 2)e.push(n);
                    return e
                }), odd: c(function (e, t) {
                    for (var n = 1; t > n; n += 2)e.push(n);
                    return e
                }), lt: c(function (e, t, n) {
                    for (var r = 0 > n ? n + t : n; --r >= 0;)e.push(r);
                    return e
                }), gt: c(function (e, t, n) {
                    for (var r = 0 > n ? n + t : n; ++r < t;)e.push(r);
                    return e
                })
            }
        }, N.pseudos.nth = N.pseudos.eq;
        for (T in{
            radio: !0, checkbox: !0, file: !0, password: !0, image: !0
        })N.pseudos[T] = u(T);
        for (T in{submit: !0, reset: !0})N.pseudos[T] = l(T);
        f.prototype = N.filters = N.pseudos, N.setFilters = new f, S = n.compile = function (e, t) {
            var n, r = [], i = [], o = X[e + " "];
            if (!o) {
                for (t || (t = p(e)), n = t.length; n--;)o = v(t[n]), o[W] ? r.push(o) : i.push(o);
                o = X(e, x(i, r))
            }
            return o
        }, C.sortStable = W.split("").sort(Y).join("") === W, C.detectDuplicates = U, q(), C.sortDetached = o(function (e) {
            return 1 & e.compareDocumentPosition(L.createElement("div"))
        }), o(function (e) {
            return e.innerHTML = "<a href='#'></a>", "#" === e.firstChild.getAttribute("href")
        }) || s("type|href|height|width", function (e, t, n) {
            return n ? void 0 : e.getAttribute(t, "type" === t.toLowerCase() ? 1 : 2)
        }), C.attributes && o(function (e) {
            return e.innerHTML = "<input/>", e.firstChild.setAttribute("value", ""), "" === e.firstChild.getAttribute("value")
        }) || s("value", function (e, t, n) {
            return n || "input" !== e.nodeName.toLowerCase() ? void 0 : e.defaultValue
        }), o(function (e) {
            return null == e.getAttribute("disabled")
        }) || s(re, function (e, t, n) {
            var r;
            return n ? void 0 : (r = e.getAttributeNode(t)) && r.specified ? r.value : e[t] === !0 ? t.toLowerCase() : null
        }), oe.find = n, oe.expr = n.selectors, oe.expr[":"] = oe.expr.pseudos, oe.unique = n.uniqueSort, oe.text = n.getText, oe.isXMLDoc = n.isXML, oe.contains = n.contains
    }(e);
    var he = {};
    oe.Callbacks = function (e) {
        e = "string" == typeof e ? he[e] || r(e) : oe.extend({}, e);
        var n, i, o, s, a, u, l = [], c = !e.once && [], f = function (t) {
            for (n = e.memory && t, i = !0, u = s || 0, s = 0, a = l.length, o = !0; l && a > u; u++)if (l[u].apply(t[0], t[1]) === !1 && e.stopOnFalse) {
                n = !1;
                break
            }
            o = !1, l && (c ? c.length && f(c.shift()) : n ? l = [] : p.disable())
        }, p = {
            add: function () {
                if (l) {
                    var t = l.length;
                    !function r(t) {
                        oe.each(t, function (t, n) {
                            var i = oe.type(n);
                            "function" === i ? e.unique && p.has(n) || l.push(n) : n && n.length && "string" !== i && r(n)
                        })
                    }(arguments), o ? a = l.length : n && (s = t, f(n))
                }
                return this
            }, remove: function () {
                return l && oe.each(arguments, function (e, t) {
                    for (var n; (n = oe.inArray(t, l, n)) > -1;)l.splice(n, 1), o && (a >= n && a--, u >= n && u--)
                }), this
            }, has: function (e) {
                return e ? oe.inArray(e, l) > -1 : !(!l || !l.length)
            }, empty: function () {
                return l = [], a = 0, this
            }, disable: function () {
                return l = c = n = t, this
            }, disabled: function () {
                return !l
            }, lock: function () {
                return c = t, n || p.disable(), this
            }, locked: function () {
                return !c
            }, fireWith: function (e, t) {
                return !l || i && !c || (t = t || [], t = [e, t.slice ? t.slice() : t], o ? c.push(t) : f(t)), this
            }, fire: function () {
                return p.fireWith(this, arguments), this
            }, fired: function () {
                return !!i
            }
        };
        return p
    }, oe.extend({
        Deferred: function (e) {
            var t = [["resolve", "done", oe.Callbacks("once memory"), "resolved"], ["reject", "fail", oe.Callbacks("once memory"), "rejected"], ["notify", "progress", oe.Callbacks("memory")]], n = "pending", r = {
                state: function () {
                    return n
                }, always: function () {
                    return i.done(arguments).fail(arguments), this
                }, then: function () {
                    var e = arguments;
                    return oe.Deferred(function (n) {
                        oe.each(t, function (t, o) {
                            var s = o[0], a = oe.isFunction(e[t]) && e[t];
                            i[o[1]](function () {
                                var e = a && a.apply(this, arguments);
                                e && oe.isFunction(e.promise) ? e.promise().done(n.resolve).fail(n.reject).progress(n.notify) : n[s + "With"](this === r ? n.promise() : this, a ? [e] : arguments)
                            })
                        }), e = null
                    }).promise()
                }, promise: function (e) {
                    return null != e ? oe.extend(e, r) : r
                }
            }, i = {};
            return r.pipe = r.then, oe.each(t, function (e, o) {
                var s = o[2], a = o[3];
                r[o[1]] = s.add, a && s.add(function () {
                    n = a
                }, t[1 ^ e][2].disable, t[2][2].lock), i[o[0]] = function () {
                    return i[o[0] + "With"](this === i ? r : this, arguments), this
                }, i[o[0] + "With"] = s.fireWith
            }), r.promise(i), e && e.call(i, i), i
        }, when: function (e) {
            var t, n, r, i = 0, o = ee.call(arguments), s = o.length, a = 1 !== s || e && oe.isFunction(e.promise) ? s : 0, u = 1 === a ? e : oe.Deferred(), l = function (e, n, r) {
                return function (i) {
                    n[e] = this, r[e] = arguments.length > 1 ? ee.call(arguments) : i, r === t ? u.notifyWith(n, r) : --a || u.resolveWith(n, r)
                }
            };
            if (s > 1)for (t = new Array(s), n = new Array(s), r = new Array(s); s > i; i++)o[i] && oe.isFunction(o[i].promise) ? o[i].promise().done(l(i, r, o)).fail(u.reject).progress(l(i, n, t)) : --a;
            return a || u.resolveWith(r, o), u.promise()
        }
    }), oe.support = function (t) {
        var n = X.createElement("input"), r = X.createDocumentFragment(), i = X.createElement("div"), o = X.createElement("select"), s = o.appendChild(X.createElement("option"));
        return n.type ? (n.type = "checkbox", t.checkOn = "" !== n.value, t.optSelected = s.selected, t.reliableMarginRight = !0, t.boxSizingReliable = !0, t.pixelPosition = !1, n.checked = !0, t.noCloneChecked = n.cloneNode(!0).checked, o.disabled = !0, t.optDisabled = !s.disabled, n = X.createElement("input"), n.value = "t", n.type = "radio", t.radioValue = "t" === n.value, n.setAttribute("checked", "t"), n.setAttribute("name", "t"), r.appendChild(n), t.checkClone = r.cloneNode(!0).cloneNode(!0).lastChild.checked, t.focusinBubbles = "onfocusin"in e, i.style.backgroundClip = "content-box", i.cloneNode(!0).style.backgroundClip = "", t.clearCloneStyle = "content-box" === i.style.backgroundClip, oe(function () {
            var n, r, o = "padding:0;margin:0;border:0;display:block;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box", s = X.getElementsByTagName("body")[0];
            s && (n = X.createElement("div"), n.style.cssText = "border:0;width:0;height:0;position:absolute;top:0;left:-9999px;margin-top:1px", s.appendChild(n).appendChild(i), i.innerHTML = "", i.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;padding:1px;border:1px;display:block;width:4px;margin-top:1%;position:absolute;top:1%", oe.swap(s, null != s.style.zoom ? {zoom: 1} : {}, function () {
                t.boxSizing = 4 === i.offsetWidth
            }), e.getComputedStyle && (t.pixelPosition = "1%" !== (e.getComputedStyle(i, null) || {}).top, t.boxSizingReliable = "4px" === (e.getComputedStyle(i, null) || {width: "4px"}).width, r = i.appendChild(X.createElement("div")), r.style.cssText = i.style.cssText = o, r.style.marginRight = r.style.width = "0", i.style.width = "1px", t.reliableMarginRight = !parseFloat((e.getComputedStyle(r, null) || {}).marginRight)), s.removeChild(n))
        }), t) : t
    }({});
    var ge, me, ye = /(?:\{[\s\S]*\}|\[[\s\S]*\])$/, ve = /([A-Z])/g;
    i.uid = 1, i.accepts = function (e) {
        return e.nodeType ? 1 === e.nodeType || 9 === e.nodeType : !0
    }, i.prototype = {
        key: function (e) {
            if (!i.accepts(e))return 0;
            var t = {}, n = e[this.expando];
            if (!n) {
                n = i.uid++;
                try {
                    t[this.expando] = {value: n}, Object.defineProperties(e, t)
                } catch (r) {
                    t[this.expando] = n, oe.extend(e, t)
                }
            }
            return this.cache[n] || (this.cache[n] = {}), n
        }, set: function (e, t, n) {
            var r, i = this.key(e), o = this.cache[i];
            if ("string" == typeof t)o[t] = n; else if (oe.isEmptyObject(o))oe.extend(this.cache[i], t); else for (r in t)o[r] = t[r];
            return o
        }, get: function (e, n) {
            var r = this.cache[this.key(e)];
            return n === t ? r : r[n]
        }, access: function (e, n, r) {
            var i;
            return n === t || n && "string" == typeof n && r === t ? (i = this.get(e, n), i !== t ? i : this.get(e, oe.camelCase(n))) : (this.set(e, n, r), r !== t ? r : n)
        }, remove: function (e, n) {
            var r, i, o, s = this.key(e), a = this.cache[s];
            if (n === t)this.cache[s] = {}; else {
                oe.isArray(n) ? i = n.concat(n.map(oe.camelCase)) : (o = oe.camelCase(n), n in a ? i = [n, o] : (i = o, i = i in a ? [i] : i.match(ae) || [])), r = i.length;
                for (; r--;)delete a[i[r]]
            }
        }, hasData: function (e) {
            return !oe.isEmptyObject(this.cache[e[this.expando]] || {})
        }, discard: function (e) {
            e[this.expando] && delete this.cache[e[this.expando]]
        }
    }, ge = new i, me = new i, oe.extend({
        acceptData: i.accepts, hasData: function (e) {
            return ge.hasData(e) || me.hasData(e)
        }, data: function (e, t, n) {
            return ge.access(e, t, n)
        }, removeData: function (e, t) {
            ge.remove(e, t)
        }, _data: function (e, t, n) {
            return me.access(e, t, n)
        }, _removeData: function (e, t) {
            me.remove(e, t)
        }
    }), oe.fn.extend({
        data: function (e, n) {
            var r, i, s = this[0], a = 0, u = null;
            if (e === t) {
                if (this.length && (u = ge.get(s), 1 === s.nodeType && !me.get(s, "hasDataAttrs"))) {
                    for (r = s.attributes; a < r.length; a++)i = r[a].name, 0 === i.indexOf("data-") && (i = oe.camelCase(i.slice(5)), o(s, i, u[i]));
                    me.set(s, "hasDataAttrs", !0)
                }
                return u
            }
            return "object" == typeof e ? this.each(function () {
                ge.set(this, e)
            }) : oe.access(this, function (n) {
                var r, i = oe.camelCase(e);
                if (s && n === t) {
                    if (r = ge.get(s, e), r !== t)return r;
                    if (r = ge.get(s, i), r !== t)return r;
                    if (r = o(s, i, t), r !== t)return r
                } else this.each(function () {
                    var r = ge.get(this, i);
                    ge.set(this, i, n), -1 !== e.indexOf("-") && r !== t && ge.set(this, e, n)
                })
            }, null, n, arguments.length > 1, null, !0)
        }, removeData: function (e) {
            return this.each(function () {
                ge.remove(this, e)
            })
        }
    }), oe.extend({
        queue: function (e, t, n) {
            var r;
            return e ? (t = (t || "fx") + "queue", r = me.get(e, t), n && (!r || oe.isArray(n) ? r = me.access(e, t, oe.makeArray(n)) : r.push(n)), r || []) : void 0
        }, dequeue: function (e, t) {
            t = t || "fx";
            var n = oe.queue(e, t), r = n.length, i = n.shift(), o = oe._queueHooks(e, t), s = function () {
                oe.dequeue(e, t)
            };
            "inprogress" === i && (i = n.shift(), r--), i && ("fx" === t && n.unshift("inprogress"), delete o.stop, i.call(e, s, o)), !r && o && o.empty.fire()
        }, _queueHooks: function (e, t) {
            var n = t + "queueHooks";
            return me.get(e, n) || me.access(e, n, {
                    empty: oe.Callbacks("once memory").add(function () {
                        me.remove(e, [t + "queue", n])
                    })
                })
        }
    }), oe.fn.extend({
        queue: function (e, n) {
            var r = 2;
            return "string" != typeof e && (n = e, e = "fx", r--), arguments.length < r ? oe.queue(this[0], e) : n === t ? this : this.each(function () {
                var t = oe.queue(this, e, n);
                oe._queueHooks(this, e), "fx" === e && "inprogress" !== t[0] && oe.dequeue(this, e)
            })
        }, dequeue: function (e) {
            return this.each(function () {
                oe.dequeue(this, e)
            })
        }, delay: function (e, t) {
            return e = oe.fx ? oe.fx.speeds[e] || e : e, t = t || "fx", this.queue(t, function (t, n) {
                var r = setTimeout(t, e);
                n.stop = function () {
                    clearTimeout(r)
                }
            })
        }, clearQueue: function (e) {
            return this.queue(e || "fx", [])
        }, promise: function (e, n) {
            var r, i = 1, o = oe.Deferred(), s = this, a = this.length, u = function () {
                --i || o.resolveWith(s, [s])
            };
            for ("string" != typeof e && (n = e, e = t), e = e || "fx"; a--;)r = me.get(s[a], e + "queueHooks"), r && r.empty && (i++, r.empty.add(u));
            return u(), o.promise(n)
        }
    });
    var xe, be, we = /[\t\r\n\f]/g, Te = /\r/g, Ce = /^(?:input|select|textarea|button)$/i;
    oe.fn.extend({
        attr: function (e, t) {
            return oe.access(this, oe.attr, e, t, arguments.length > 1)
        }, removeAttr: function (e) {
            return this.each(function () {
                oe.removeAttr(this, e)
            })
        }, prop: function (e, t) {
            return oe.access(this, oe.prop, e, t, arguments.length > 1)
        }, removeProp: function (e) {
            return this.each(function () {
                delete this[oe.propFix[e] || e]
            })
        }, addClass: function (e) {
            var t, n, r, i, o, s = 0, a = this.length, u = "string" == typeof e && e;
            if (oe.isFunction(e))return this.each(function (t) {
                oe(this).addClass(e.call(this, t, this.className))
            });
            if (u)for (t = (e || "").match(ae) || []; a > s; s++)if (n = this[s], r = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(we, " ") : " ")) {
                for (o = 0; i = t[o++];)r.indexOf(" " + i + " ") < 0 && (r += i + " ");
                n.className = oe.trim(r)
            }
            return this
        }, removeClass: function (e) {
            var t, n, r, i, o, s = 0, a = this.length, u = 0 === arguments.length || "string" == typeof e && e;
            if (oe.isFunction(e))return this.each(function (t) {
                oe(this).removeClass(e.call(this, t, this.className))
            });
            if (u)for (t = (e || "").match(ae) || []; a > s; s++)if (n = this[s], r = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(we, " ") : "")) {
                for (o = 0; i = t[o++];)for (; r.indexOf(" " + i + " ") >= 0;)r = r.replace(" " + i + " ", " ");
                n.className = e ? oe.trim(r) : ""
            }
            return this
        }, toggleClass: function (e, t) {
            var n = typeof e;
            return "boolean" == typeof t && "string" === n ? t ? this.addClass(e) : this.removeClass(e) : this.each(oe.isFunction(e) ? function (n) {
                oe(this).toggleClass(e.call(this, n, this.className, t), t)
            } : function () {
                if ("string" === n)for (var t, r = 0, i = oe(this), o = e.match(ae) || []; t = o[r++];)i.hasClass(t) ? i.removeClass(t) : i.addClass(t); else(n === z || "boolean" === n) && (this.className && me.set(this, "__className__", this.className), this.className = this.className || e === !1 ? "" : me.get(this, "__className__") || "")
            })
        }, hasClass: function (e) {
            for (var t = " " + e + " ", n = 0, r = this.length; r > n; n++)if (1 === this[n].nodeType && (" " + this[n].className + " ").replace(we, " ").indexOf(t) >= 0)return !0;
            return !1
        }, val: function (e) {
            var n, r, i, o = this[0];
            {
                if (arguments.length)return i = oe.isFunction(e), this.each(function (r) {
                    var o;
                    1 === this.nodeType && (o = i ? e.call(this, r, oe(this).val()) : e, null == o ? o = "" : "number" == typeof o ? o += "" : oe.isArray(o) && (o = oe.map(o, function (e) {
                        return null == e ? "" : e + ""
                    })), n = oe.valHooks[this.type] || oe.valHooks[this.nodeName.toLowerCase()], n && "set"in n && n.set(this, o, "value") !== t || (this.value = o))
                });
                if (o)return n = oe.valHooks[o.type] || oe.valHooks[o.nodeName.toLowerCase()], n && "get"in n && (r = n.get(o, "value")) !== t ? r : (r = o.value, "string" == typeof r ? r.replace(Te, "") : null == r ? "" : r)
            }
        }
    }), oe.extend({
        valHooks: {
            option: {
                get: function (e) {
                    var t = e.attributes.value;
                    return !t || t.specified ? e.value : e.text
                }
            }, select: {
                get: function (e) {
                    for (var t, n, r = e.options, i = e.selectedIndex, o = "select-one" === e.type || 0 > i, s = o ? null : [], a = o ? i + 1 : r.length, u = 0 > i ? a : o ? i : 0; a > u; u++)if (n = r[u], !(!n.selected && u !== i || (oe.support.optDisabled ? n.disabled : null !== n.getAttribute("disabled")) || n.parentNode.disabled && oe.nodeName(n.parentNode, "optgroup"))) {
                        if (t = oe(n).val(), o)return t;
                        s.push(t)
                    }
                    return s
                }, set: function (e, t) {
                    for (var n, r, i = e.options, o = oe.makeArray(t), s = i.length; s--;)r = i[s], (r.selected = oe.inArray(oe(r).val(), o) >= 0) && (n = !0);
                    return n || (e.selectedIndex = -1), o
                }
            }
        }, attr: function (e, n, r) {
            var i, o, s = e.nodeType;
            if (e && 3 !== s && 8 !== s && 2 !== s)return typeof e.getAttribute === z ? oe.prop(e, n, r) : (1 === s && oe.isXMLDoc(e) || (n = n.toLowerCase(), i = oe.attrHooks[n] || (oe.expr.match.bool.test(n) ? be : xe)), r === t ? i && "get"in i && null !== (o = i.get(e, n)) ? o : (o = oe.find.attr(e, n), null == o ? t : o) : null !== r ? i && "set"in i && (o = i.set(e, r, n)) !== t ? o : (e.setAttribute(n, r + ""), r) : void oe.removeAttr(e, n))
        }, removeAttr: function (e, t) {
            var n, r, i = 0, o = t && t.match(ae);
            if (o && 1 === e.nodeType)for (; n = o[i++];)r = oe.propFix[n] || n, oe.expr.match.bool.test(n) && (e[r] = !1), e.removeAttribute(n)
        }, attrHooks: {
            type: {
                set: function (e, t) {
                    if (!oe.support.radioValue && "radio" === t && oe.nodeName(e, "input")) {
                        var n = e.value;
                        return e.setAttribute("type", t), n && (e.value = n), t
                    }
                }
            }
        }, propFix: {"for": "htmlFor", "class": "className"}, prop: function (e, n, r) {
            var i, o, s, a = e.nodeType;
            if (e && 3 !== a && 8 !== a && 2 !== a)return s = 1 !== a || !oe.isXMLDoc(e), s && (n = oe.propFix[n] || n, o = oe.propHooks[n]), r !== t ? o && "set"in o && (i = o.set(e, r, n)) !== t ? i : e[n] = r : o && "get"in o && null !== (i = o.get(e, n)) ? i : e[n]
        }, propHooks: {
            tabIndex: {
                get: function (e) {
                    return e.hasAttribute("tabindex") || Ce.test(e.nodeName) || e.href ? e.tabIndex : -1
                }
            }
        }
    }), be = {
        set: function (e, t, n) {
            return t === !1 ? oe.removeAttr(e, n) : e.setAttribute(n, n), n
        }
    }, oe.each(oe.expr.match.bool.source.match(/\w+/g), function (e, n) {
        var r = oe.expr.attrHandle[n] || oe.find.attr;
        oe.expr.attrHandle[n] = function (e, n, i) {
            var o = oe.expr.attrHandle[n], s = i ? t : (oe.expr.attrHandle[n] = t) != r(e, n, i) ? n.toLowerCase() : null;
            return oe.expr.attrHandle[n] = o, s
        }
    }), oe.support.optSelected || (oe.propHooks.selected = {
        get: function (e) {
            var t = e.parentNode;
            return t && t.parentNode && t.parentNode.selectedIndex, null
        }
    }), oe.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function () {
        oe.propFix[this.toLowerCase()] = this
    }), oe.each(["radio", "checkbox"], function () {
        oe.valHooks[this] = {
            set: function (e, t) {
                return oe.isArray(t) ? e.checked = oe.inArray(oe(e).val(), t) >= 0 : void 0
            }
        }, oe.support.checkOn || (oe.valHooks[this].get = function (e) {
            return null === e.getAttribute("value") ? "on" : e.value
        })
    });
    var ke = /^key/, Ne = /^(?:mouse|contextmenu)|click/, je = /^(?:focusinfocus|focusoutblur)$/, Ee = /^([^.]*)(?:\.(.+)|)$/;
    oe.event = {
        global: {},
        add: function (e, n, r, i, o) {
            var s, a, u, l, c, f, p, d, h, g, m, y = me.get(e);
            if (y) {
                for (r.handler && (s = r, r = s.handler, o = s.selector), r.guid || (r.guid = oe.guid++), (l = y.events) || (l = y.events = {}), (a = y.handle) || (a = y.handle = function (e) {
                    return typeof oe === z || e && oe.event.triggered === e.type ? t : oe.event.dispatch.apply(a.elem, arguments)
                }, a.elem = e), n = (n || "").match(ae) || [""], c = n.length; c--;)u = Ee.exec(n[c]) || [], h = m = u[1], g = (u[2] || "").split(".").sort(), h && (p = oe.event.special[h] || {}, h = (o ? p.delegateType : p.bindType) || h, p = oe.event.special[h] || {}, f = oe.extend({
                    type: h,
                    origType: m,
                    data: i,
                    handler: r,
                    guid: r.guid,
                    selector: o,
                    needsContext: o && oe.expr.match.needsContext.test(o),
                    namespace: g.join(".")
                }, s), (d = l[h]) || (d = l[h] = [], d.delegateCount = 0, p.setup && p.setup.call(e, i, g, a) !== !1 || e.addEventListener && e.addEventListener(h, a, !1)), p.add && (p.add.call(e, f), f.handler.guid || (f.handler.guid = r.guid)), o ? d.splice(d.delegateCount++, 0, f) : d.push(f), oe.event.global[h] = !0);
                e = null
            }
        },
        remove: function (e, t, n, r, i) {
            var o, s, a, u, l, c, f, p, d, h, g, m = me.hasData(e) && me.get(e);
            if (m && (u = m.events)) {
                for (t = (t || "").match(ae) || [""], l = t.length; l--;)if (a = Ee.exec(t[l]) || [], d = g = a[1], h = (a[2] || "").split(".").sort(), d) {
                    for (f = oe.event.special[d] || {}, d = (r ? f.delegateType : f.bindType) || d, p = u[d] || [], a = a[2] && new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)"), s = o = p.length; o--;)c = p[o], !i && g !== c.origType || n && n.guid !== c.guid || a && !a.test(c.namespace) || r && r !== c.selector && ("**" !== r || !c.selector) || (p.splice(o, 1), c.selector && p.delegateCount--, f.remove && f.remove.call(e, c));
                    s && !p.length && (f.teardown && f.teardown.call(e, h, m.handle) !== !1 || oe.removeEvent(e, d, m.handle), delete u[d])
                } else for (d in u)oe.event.remove(e, d + t[l], n, r, !0);
                oe.isEmptyObject(u) && (delete m.handle, me.remove(e, "events"))
            }
        },
        trigger: function (n, r, i, o) {
            var s, a, u, l, c, f, p, d = [i || X], h = re.call(n, "type") ? n.type : n, g = re.call(n, "namespace") ? n.namespace.split(".") : [];
            if (a = u = i = i || X, 3 !== i.nodeType && 8 !== i.nodeType && !je.test(h + oe.event.triggered) && (h.indexOf(".") >= 0 && (g = h.split("."), h = g.shift(), g.sort()), c = h.indexOf(":") < 0 && "on" + h, n = n[oe.expando] ? n : new oe.Event(h, "object" == typeof n && n), n.isTrigger = o ? 2 : 3, n.namespace = g.join("."), n.namespace_re = n.namespace ? new RegExp("(^|\\.)" + g.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, n.result = t, n.target || (n.target = i), r = null == r ? [n] : oe.makeArray(r, [n]), p = oe.event.special[h] || {}, o || !p.trigger || p.trigger.apply(i, r) !== !1)) {
                if (!o && !p.noBubble && !oe.isWindow(i)) {
                    for (l = p.delegateType || h, je.test(l + h) || (a = a.parentNode); a; a = a.parentNode)d.push(a), u = a;
                    u === (i.ownerDocument || X) && d.push(u.defaultView || u.parentWindow || e)
                }
                for (s = 0; (a = d[s++]) && !n.isPropagationStopped();)n.type = s > 1 ? l : p.bindType || h, f = (me.get(a, "events") || {})[n.type] && me.get(a, "handle"), f && f.apply(a, r), f = c && a[c], f && oe.acceptData(a) && f.apply && f.apply(a, r) === !1 && n.preventDefault();
                return n.type = h, o || n.isDefaultPrevented() || p._default && p._default.apply(d.pop(), r) !== !1 || !oe.acceptData(i) || c && oe.isFunction(i[h]) && !oe.isWindow(i) && (u = i[c], u && (i[c] = null), oe.event.triggered = h, i[h](), oe.event.triggered = t, u && (i[c] = u)), n.result
            }
        },
        dispatch: function (e) {
            e = oe.event.fix(e);
            var n, r, i, o, s, a = [], u = ee.call(arguments), l = (me.get(this, "events") || {})[e.type] || [], c = oe.event.special[e.type] || {};
            if (u[0] = e, e.delegateTarget = this, !c.preDispatch || c.preDispatch.call(this, e) !== !1) {
                for (a = oe.event.handlers.call(this, e, l), n = 0; (o = a[n++]) && !e.isPropagationStopped();)for (e.currentTarget = o.elem, r = 0; (s = o.handlers[r++]) && !e.isImmediatePropagationStopped();)(!e.namespace_re || e.namespace_re.test(s.namespace)) && (e.handleObj = s, e.data = s.data, i = ((oe.event.special[s.origType] || {}).handle || s.handler).apply(o.elem, u), i !== t && (e.result = i) === !1 && (e.preventDefault(), e.stopPropagation()));
                return c.postDispatch && c.postDispatch.call(this, e), e.result
            }
        },
        handlers: function (e, n) {
            var r, i, o, s, a = [], u = n.delegateCount, l = e.target;
            if (u && l.nodeType && (!e.button || "click" !== e.type))for (; l !== this; l = l.parentNode || this)if (l.disabled !== !0 || "click" !== e.type) {
                for (i = [], r = 0; u > r; r++)s = n[r], o = s.selector + " ", i[o] === t && (i[o] = s.needsContext ? oe(o, this).index(l) >= 0 : oe.find(o, this, null, [l]).length), i[o] && i.push(s);
                i.length && a.push({elem: l, handlers: i})
            }
            return u < n.length && a.push({elem: this, handlers: n.slice(u)}), a
        },
        props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        fixHooks: {},
        keyHooks: {
            props: "char charCode key keyCode".split(" "), filter: function (e, t) {
                return null == e.which && (e.which = null != t.charCode ? t.charCode : t.keyCode), e
            }
        },
        mouseHooks: {
            props: "button buttons clientX clientY offsetX offsetY pageX pageY screenX screenY toElement".split(" "), filter: function (e, n) {
                var r, i, o, s = n.button;
                return null == e.pageX && null != n.clientX && (r = e.target.ownerDocument || X, i = r.documentElement, o = r.body, e.pageX = n.clientX + (i && i.scrollLeft || o && o.scrollLeft || 0) - (i && i.clientLeft || o && o.clientLeft || 0), e.pageY = n.clientY + (i && i.scrollTop || o && o.scrollTop || 0) - (i && i.clientTop || o && o.clientTop || 0)), e.which || s === t || (e.which = 1 & s ? 1 : 2 & s ? 3 : 4 & s ? 2 : 0), e
            }
        },
        fix: function (e) {
            if (e[oe.expando])return e;
            var t, n, r, i = e.type, o = e, s = this.fixHooks[i];
            for (s || (this.fixHooks[i] = s = Ne.test(i) ? this.mouseHooks : ke.test(i) ? this.keyHooks : {}), r = s.props ? this.props.concat(s.props) : this.props, e = new oe.Event(o), t = r.length; t--;)n = r[t], e[n] = o[n];
            return e.target || (e.target = X), 3 === e.target.nodeType && (e.target = e.target.parentNode), s.filter ? s.filter(e, o) : e
        },
        special: {
            load: {noBubble: !0}, focus: {
                trigger: function () {
                    return this !== u() && this.focus ? (this.focus(), !1) : void 0
                }, delegateType: "focusin"
            }, blur: {
                trigger: function () {
                    return this === u() && this.blur ? (this.blur(), !1) : void 0
                }, delegateType: "focusout"
            }, click: {
                trigger: function () {
                    return "checkbox" === this.type && this.click && oe.nodeName(this, "input") ? (this.click(), !1) : void 0
                }, _default: function (e) {
                    return oe.nodeName(e.target, "a")
                }
            }, beforeunload: {
                postDispatch: function (e) {
                    e.result !== t && (e.originalEvent.returnValue = e.result)
                }
            }
        },
        simulate: function (e, t, n, r) {
            var i = oe.extend(new oe.Event, n, {type: e, isSimulated: !0, originalEvent: {}});
            r ? oe.event.trigger(i, null, t) : oe.event.dispatch.call(t, i), i.isDefaultPrevented() && n.preventDefault()
        }
    }, oe.removeEvent = function (e, t, n) {
        e.removeEventListener && e.removeEventListener(t, n, !1)
    }, oe.Event = function (e, t) {
        return this instanceof oe.Event ? (e && e.type ? (this.originalEvent = e, this.type = e.type, this.isDefaultPrevented = e.defaultPrevented || e.getPreventDefault && e.getPreventDefault() ? s : a) : this.type = e, t && oe.extend(this, t), this.timeStamp = e && e.timeStamp || oe.now(), void(this[oe.expando] = !0)) : new oe.Event(e, t)
    }, oe.Event.prototype = {
        isDefaultPrevented: a, isPropagationStopped: a, isImmediatePropagationStopped: a, preventDefault: function () {
            var e = this.originalEvent;
            this.isDefaultPrevented = s, e && e.preventDefault && e.preventDefault()
        }, stopPropagation: function () {
            var e = this.originalEvent;
            this.isPropagationStopped = s, e && e.stopPropagation && e.stopPropagation()
        }, stopImmediatePropagation: function () {
            this.isImmediatePropagationStopped = s, this.stopPropagation()
        }
    }, oe.each({mouseenter: "mouseover", mouseleave: "mouseout"}, function (e, t) {
        oe.event.special[e] = {
            delegateType: t, bindType: t, handle: function (e) {
                var n, r = this, i = e.relatedTarget, o = e.handleObj;
                return (!i || i !== r && !oe.contains(r, i)) && (e.type = o.origType, n = o.handler.apply(this, arguments), e.type = t), n
            }
        }
    }), oe.support.focusinBubbles || oe.each({focus: "focusin", blur: "focusout"}, function (e, t) {
        var n = 0, r = function (e) {
            oe.event.simulate(t, e.target, oe.event.fix(e), !0)
        };
        oe.event.special[t] = {
            setup: function () {
                0 === n++ && X.addEventListener(e, r, !0)
            }, teardown: function () {
                0 === --n && X.removeEventListener(e, r, !0)
            }
        }
    }), oe.fn.extend({
        on: function (e, n, r, i, o) {
            var s, u;
            if ("object" == typeof e) {
                "string" != typeof n && (r = r || n, n = t);
                for (u in e)this.on(u, n, r, e[u], o);
                return this
            }
            if (null == r && null == i ? (i = n, r = n = t) : null == i && ("string" == typeof n ? (i = r, r = t) : (i = r, r = n, n = t)), i === !1)i = a; else if (!i)return this;
            return 1 === o && (s = i, i = function (e) {
                return oe().off(e), s.apply(this, arguments)
            }, i.guid = s.guid || (s.guid = oe.guid++)), this.each(function () {
                oe.event.add(this, e, i, r, n)
            })
        }, one: function (e, t, n, r) {
            return this.on(e, t, n, r, 1)
        }, off: function (e, n, r) {
            var i, o;
            if (e && e.preventDefault && e.handleObj)return i = e.handleObj, oe(e.delegateTarget).off(i.namespace ? i.origType + "." + i.namespace : i.origType, i.selector, i.handler), this;
            if ("object" == typeof e) {
                for (o in e)this.off(o, n, e[o]);
                return this
            }
            return (n === !1 || "function" == typeof n) && (r = n, n = t), r === !1 && (r = a), this.each(function () {
                oe.event.remove(this, e, r, n)
            })
        }, trigger: function (e, t) {
            return this.each(function () {
                oe.event.trigger(e, t, this)
            })
        }, triggerHandler: function (e, t) {
            var n = this[0];
            return n ? oe.event.trigger(e, t, n, !0) : void 0
        }
    });
    var Se = /^.[^:#\[\.,]*$/, De = /^(?:parents|prev(?:Until|All))/, Ae = oe.expr.match.needsContext, qe = {children: !0, contents: !0, next: !0, prev: !0};
    oe.fn.extend({
        find: function (e) {
            var t, n = [], r = this, i = r.length;
            if ("string" != typeof e)return this.pushStack(oe(e).filter(function () {
                for (t = 0; i > t; t++)if (oe.contains(r[t], this))return !0
            }));
            for (t = 0; i > t; t++)oe.find(e, r[t], n);
            return n = this.pushStack(i > 1 ? oe.unique(n) : n), n.selector = this.selector ? this.selector + " " + e : e, n
        }, has: function (e) {
            var t = oe(e, this), n = t.length;
            return this.filter(function () {
                for (var e = 0; n > e; e++)if (oe.contains(this, t[e]))return !0
            })
        }, not: function (e) {
            return this.pushStack(c(this, e || [], !0))
        }, filter: function (e) {
            return this.pushStack(c(this, e || [], !1))
        }, is: function (e) {
            return !!c(this, "string" == typeof e && Ae.test(e) ? oe(e) : e || [], !1).length
        }, closest: function (e, t) {
            for (var n, r = 0, i = this.length, o = [], s = Ae.test(e) || "string" != typeof e ? oe(e, t || this.context) : 0; i > r; r++)for (n = this[r]; n && n !== t; n = n.parentNode)if (n.nodeType < 11 && (s ? s.index(n) > -1 : 1 === n.nodeType && oe.find.matchesSelector(n, e))) {
                n = o.push(n);
                break
            }
            return this.pushStack(o.length > 1 ? oe.unique(o) : o)
        }, index: function (e) {
            return e ? "string" == typeof e ? te.call(oe(e), this[0]) : te.call(this, e.jquery ? e[0] : e) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
        }, add: function (e, t) {
            var n = "string" == typeof e ? oe(e, t) : oe.makeArray(e && e.nodeType ? [e] : e), r = oe.merge(this.get(), n);
            return this.pushStack(oe.unique(r))
        }, addBack: function (e) {
            return this.add(null == e ? this.prevObject : this.prevObject.filter(e))
        }
    }), oe.each({
        parent: function (e) {
            var t = e.parentNode;
            return t && 11 !== t.nodeType ? t : null
        }, parents: function (e) {
            return oe.dir(e, "parentNode")
        }, parentsUntil: function (e, t, n) {
            return oe.dir(e, "parentNode", n)
        }, next: function (e) {
            return l(e, "nextSibling")
        }, prev: function (e) {
            return l(e, "previousSibling")
        }, nextAll: function (e) {
            return oe.dir(e, "nextSibling")
        }, prevAll: function (e) {
            return oe.dir(e, "previousSibling")
        }, nextUntil: function (e, t, n) {
            return oe.dir(e, "nextSibling", n)
        }, prevUntil: function (e, t, n) {
            return oe.dir(e, "previousSibling", n)
        }, siblings: function (e) {
            return oe.sibling((e.parentNode || {}).firstChild, e)
        }, children: function (e) {
            return oe.sibling(e.firstChild)
        }, contents: function (e) {
            return e.contentDocument || oe.merge([], e.childNodes)
        }
    }, function (e, t) {
        oe.fn[e] = function (n, r) {
            var i = oe.map(this, t, n);
            return "Until" !== e.slice(-5) && (r = n), r && "string" == typeof r && (i = oe.filter(r, i)), this.length > 1 && (qe[e] || oe.unique(i), De.test(e) && i.reverse()), this.pushStack(i)
        }
    }), oe.extend({
        filter: function (e, t, n) {
            var r = t[0];
            return n && (e = ":not(" + e + ")"), 1 === t.length && 1 === r.nodeType ? oe.find.matchesSelector(r, e) ? [r] : [] : oe.find.matches(e, oe.grep(t, function (e) {
                return 1 === e.nodeType
            }))
        }, dir: function (e, n, r) {
            for (var i = [], o = r !== t; (e = e[n]) && 9 !== e.nodeType;)if (1 === e.nodeType) {
                if (o && oe(e).is(r))break;
                i.push(e)
            }
            return i
        }, sibling: function (e, t) {
            for (var n = []; e; e = e.nextSibling)1 === e.nodeType && e !== t && n.push(e);
            return n
        }
    });
    var Le = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi, He = /<([\w:]+)/, Oe = /<|&#?\w+;/, Fe = /<(?:script|style|link)/i, Pe = /^(?:checkbox|radio)$/i, Re = /checked\s*(?:[^=]|=\s*.checked.)/i, Me = /^$|\/(?:java|ecma)script/i, We = /^true\/(.*)/, $e = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g, Be = {
        option: [1, "<select multiple='multiple'>", "</select>"],
        thead: [1, "<table>", "</table>"],
        col: [2, "<table><colgroup>", "</colgroup></table>"],
        tr: [2, "<table><tbody>", "</tbody></table>"],
        td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
        _default: [0, "", ""]
    };
    Be.optgroup = Be.option, Be.tbody = Be.tfoot = Be.colgroup = Be.caption = Be.thead, Be.th = Be.td, oe.fn.extend({
        text: function (e) {
            return oe.access(this, function (e) {
                return e === t ? oe.text(this) : this.empty().append((this[0] && this[0].ownerDocument || X).createTextNode(e))
            }, null, e, arguments.length)
        }, append: function () {
            return this.domManip(arguments, function (e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = f(this, e);
                    t.appendChild(e)
                }
            })
        }, prepend: function () {
            return this.domManip(arguments, function (e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = f(this, e);
                    t.insertBefore(e, t.firstChild)
                }
            })
        }, before: function () {
            return this.domManip(arguments, function (e) {
                this.parentNode && this.parentNode.insertBefore(e, this)
            })
        }, after: function () {
            return this.domManip(arguments, function (e) {
                this.parentNode && this.parentNode.insertBefore(e, this.nextSibling)
            })
        }, remove: function (e, t) {
            for (var n, r = e ? oe.filter(e, this) : this, i = 0; null != (n = r[i]); i++)t || 1 !== n.nodeType || oe.cleanData(m(n)), n.parentNode && (t && oe.contains(n.ownerDocument, n) && h(m(n, "script")), n.parentNode.removeChild(n));
            return this
        }, empty: function () {
            for (var e, t = 0; null != (e = this[t]); t++)1 === e.nodeType && (oe.cleanData(m(e, !1)), e.textContent = "");
            return this
        }, clone: function (e, t) {
            return e = null == e ? !1 : e, t = null == t ? e : t, this.map(function () {
                return oe.clone(this, e, t)
            })
        }, html: function (e) {
            return oe.access(this, function (e) {
                var n = this[0] || {}, r = 0, i = this.length;
                if (e === t && 1 === n.nodeType)return n.innerHTML;
                if ("string" == typeof e && !Fe.test(e) && !Be[(He.exec(e) || ["", ""])[1].toLowerCase()]) {
                    e = e.replace(Le, "<$1></$2>");
                    try {
                        for (; i > r; r++)n = this[r] || {}, 1 === n.nodeType && (oe.cleanData(m(n, !1)), n.innerHTML = e);
                        n = 0
                    } catch (o) {
                    }
                }
                n && this.empty().append(e)
            }, null, e, arguments.length)
        }, replaceWith: function () {
            var e = oe.map(this, function (e) {
                return [e.nextSibling, e.parentNode]
            }), t = 0;
            return this.domManip(arguments, function (n) {
                var r = e[t++], i = e[t++];
                i && (r && r.parentNode !== i && (r = this.nextSibling), oe(this).remove(), i.insertBefore(n, r))
            }, !0), t ? this : this.remove()
        }, detach: function (e) {
            return this.remove(e, !0)
        }, domManip: function (e, t, n) {
            e = K.apply([], e);
            var r, i, o, s, a, u, l = 0, c = this.length, f = this, h = c - 1, g = e[0], y = oe.isFunction(g);
            if (y || !(1 >= c || "string" != typeof g || oe.support.checkClone) && Re.test(g))return this.each(function (r) {
                var i = f.eq(r);
                y && (e[0] = g.call(this, r, i.html())), i.domManip(e, t, n)
            });
            if (c && (r = oe.buildFragment(e, this[0].ownerDocument, !1, !n && this), i = r.firstChild, 1 === r.childNodes.length && (r = i), i)) {
                for (o = oe.map(m(r, "script"), p), s = o.length; c > l; l++)a = r, l !== h && (a = oe.clone(a, !0, !0), s && oe.merge(o, m(a, "script"))), t.call(this[l], a, l);
                if (s)for (u = o[o.length - 1].ownerDocument, oe.map(o, d), l = 0; s > l; l++)a = o[l], Me.test(a.type || "") && !me.access(a, "globalEval") && oe.contains(u, a) && (a.src ? oe._evalUrl(a.src) : oe.globalEval(a.textContent.replace($e, "")))
            }
            return this
        }
    }), oe.each({appendTo: "append", prependTo: "prepend", insertBefore: "before", insertAfter: "after", replaceAll: "replaceWith"}, function (e, t) {
        oe.fn[e] = function (e) {
            for (var n, r = [], i = oe(e), o = i.length - 1, s = 0; o >= s; s++)n = s === o ? this : this.clone(!0), oe(i[s])[t](n), Z.apply(r, n.get());
            return this.pushStack(r)
        }
    }), oe.extend({
        clone: function (e, t, n) {
            var r, i, o, s, a = e.cloneNode(!0), u = oe.contains(e.ownerDocument, e);
            if (!(oe.support.noCloneChecked || 1 !== e.nodeType && 11 !== e.nodeType || oe.isXMLDoc(e)))for (s = m(a), o = m(e), r = 0, i = o.length; i > r; r++)y(o[r], s[r]);
            if (t)if (n)for (o = o || m(e), s = s || m(a), r = 0, i = o.length; i > r; r++)g(o[r], s[r]); else g(e, a);
            return s = m(a, "script"), s.length > 0 && h(s, !u && m(e, "script")), a
        }, buildFragment: function (e, t, n, r) {
            for (var i, o, s, a, u, l, c = 0, f = e.length, p = t.createDocumentFragment(), d = []; f > c; c++)if (i = e[c], i || 0 === i)if ("object" === oe.type(i))oe.merge(d, i.nodeType ? [i] : i); else if (Oe.test(i)) {
                for (o = o || p.appendChild(t.createElement("div")), s = (He.exec(i) || ["", ""])[1].toLowerCase(), a = Be[s] || Be._default, o.innerHTML = a[1] + i.replace(Le, "<$1></$2>") + a[2], l = a[0]; l--;)o = o.lastChild;
                oe.merge(d, o.childNodes), o = p.firstChild, o.textContent = ""
            } else d.push(t.createTextNode(i));
            for (p.textContent = "", c = 0; i = d[c++];)if ((!r || -1 === oe.inArray(i, r)) && (u = oe.contains(i.ownerDocument, i), o = m(p.appendChild(i), "script"), u && h(o), n))for (l = 0; i = o[l++];)Me.test(i.type || "") && n.push(i);
            return p
        }, cleanData: function (e) {
            for (var n, r, o, s, a, u, l = oe.event.special, c = 0; (r = e[c]) !== t; c++) {
                if (i.accepts(r) && (a = r[me.expando], a && (n = me.cache[a]))) {
                    if (o = Object.keys(n.events || {}), o.length)for (u = 0; (s = o[u]) !== t; u++)l[s] ? oe.event.remove(r, s) : oe.removeEvent(r, s, n.handle);
                    me.cache[a] && delete me.cache[a]
                }
                delete ge.cache[r[ge.expando]]
            }
        }, _evalUrl: function (e) {
            return oe.ajax({url: e, type: "GET", dataType: "script", async: !1, global: !1, "throws": !0})
        }
    }), oe.fn.extend({
        wrapAll: function (e) {
            var t;
            return oe.isFunction(e) ? this.each(function (t) {
                oe(this).wrapAll(e.call(this, t))
            }) : (this[0] && (t = oe(e, this[0].ownerDocument).eq(0).clone(!0), this[0].parentNode && t.insertBefore(this[0]), t.map(function () {
                for (var e = this; e.firstElementChild;)e = e.firstElementChild;
                return e
            }).append(this)), this)
        }, wrapInner: function (e) {
            return this.each(oe.isFunction(e) ? function (t) {
                oe(this).wrapInner(e.call(this, t))
            } : function () {
                var t = oe(this), n = t.contents();
                n.length ? n.wrapAll(e) : t.append(e)
            })
        }, wrap: function (e) {
            var t = oe.isFunction(e);
            return this.each(function (n) {
                oe(this).wrapAll(t ? e.call(this, n) : e)
            })
        }, unwrap: function () {
            return this.parent().each(function () {
                oe.nodeName(this, "body") || oe(this).replaceWith(this.childNodes)
            }).end()
        }
    });
    var Ie, ze, _e = /^(none|table(?!-c[ea]).+)/, Xe = /^margin/, Ue = new RegExp("^(" + se + ")(.*)$", "i"), Ye = new RegExp("^(" + se + ")(?!px)[a-z%]+$", "i"), Ve = new RegExp("^([+-])=(" + se + ")", "i"), Ge = {BODY: "block"}, Qe = {
        position: "absolute",
        visibility: "hidden",
        display: "block"
    }, Je = {letterSpacing: 0, fontWeight: 400}, Ke = ["Top", "Right", "Bottom", "Left"], Ze = ["Webkit", "O", "Moz", "ms"];
    oe.fn.extend({
        css: function (e, n) {
            return oe.access(this, function (e, n, r) {
                var i, o, s = {}, a = 0;
                if (oe.isArray(n)) {
                    for (i = b(e), o = n.length; o > a; a++)s[n[a]] = oe.css(e, n[a], !1, i);
                    return s
                }
                return r !== t ? oe.style(e, n, r) : oe.css(e, n)
            }, e, n, arguments.length > 1)
        }, show: function () {
            return w(this, !0)
        }, hide: function () {
            return w(this)
        }, toggle: function (e) {
            return "boolean" == typeof e ? e ? this.show() : this.hide() : this.each(function () {
                x(this) ? oe(this).show() : oe(this).hide()
            })
        }
    }), oe.extend({
        cssHooks: {
            opacity: {
                get: function (e, t) {
                    if (t) {
                        var n = Ie(e, "opacity");
                        return "" === n ? "1" : n
                    }
                }
            }
        },
        cssNumber: {columnCount: !0, fillOpacity: !0, fontWeight: !0, lineHeight: !0, opacity: !0, order: !0, orphans: !0, widows: !0, zIndex: !0, zoom: !0},
        cssProps: {"float": "cssFloat"},
        style: function (e, n, r, i) {
            if (e && 3 !== e.nodeType && 8 !== e.nodeType && e.style) {
                var o, s, a, u = oe.camelCase(n), l = e.style;
                return n = oe.cssProps[u] || (oe.cssProps[u] = v(l, u)), a = oe.cssHooks[n] || oe.cssHooks[u], r === t ? a && "get"in a && (o = a.get(e, !1, i)) !== t ? o : l[n] : (s = typeof r, "string" === s && (o = Ve.exec(r)) && (r = (o[1] + 1) * o[2] + parseFloat(oe.css(e, n)), s = "number"), null == r || "number" === s && isNaN(r) || ("number" !== s || oe.cssNumber[u] || (r += "px"), oe.support.clearCloneStyle || "" !== r || 0 !== n.indexOf("background") || (l[n] = "inherit"), a && "set"in a && (r = a.set(e, r, i)) === t || (l[n] = r)), void 0)
            }
        },
        css: function (e, n, r, i) {
            var o, s, a, u = oe.camelCase(n);
            return n = oe.cssProps[u] || (oe.cssProps[u] = v(e.style, u)), a = oe.cssHooks[n] || oe.cssHooks[u], a && "get"in a && (o = a.get(e, !0, r)), o === t && (o = Ie(e, n, i)), "normal" === o && n in Je && (o = Je[n]), "" === r || r ? (s = parseFloat(o), r === !0 || oe.isNumeric(s) ? s || 0 : o) : o
        }
    }), Ie = function (e, n, r) {
        var i, o, s, a = r || b(e), u = a ? a.getPropertyValue(n) || a[n] : t, l = e.style;
        return a && ("" !== u || oe.contains(e.ownerDocument, e) || (u = oe.style(e, n)), Ye.test(u) && Xe.test(n) && (i = l.width, o = l.minWidth, s = l.maxWidth, l.minWidth = l.maxWidth = l.width = u, u = a.width, l.width = i, l.minWidth = o, l.maxWidth = s)), u
    }, oe.each(["height", "width"], function (e, t) {
        oe.cssHooks[t] = {
            get: function (e, n, r) {
                return n ? 0 === e.offsetWidth && _e.test(oe.css(e, "display")) ? oe.swap(e, Qe, function () {
                    return k(e, t, r)
                }) : k(e, t, r) : void 0
            }, set: function (e, n, r) {
                var i = r && b(e);
                return T(e, n, r ? C(e, t, r, oe.support.boxSizing && "border-box" === oe.css(e, "boxSizing", !1, i), i) : 0)
            }
        }
    }), oe(function () {
        oe.support.reliableMarginRight || (oe.cssHooks.marginRight = {
            get: function (e, t) {
                return t ? oe.swap(e, {display: "inline-block"}, Ie, [e, "marginRight"]) : void 0
            }
        }), !oe.support.pixelPosition && oe.fn.position && oe.each(["top", "left"], function (e, t) {
            oe.cssHooks[t] = {
                get: function (e, n) {
                    return n ? (n = Ie(e, t), Ye.test(n) ? oe(e).position()[t] + "px" : n) : void 0
                }
            }
        })
    }), oe.expr && oe.expr.filters && (oe.expr.filters.hidden = function (e) {
        return e.offsetWidth <= 0 && e.offsetHeight <= 0
    }, oe.expr.filters.visible = function (e) {
        return !oe.expr.filters.hidden(e)
    }), oe.each({margin: "", padding: "", border: "Width"}, function (e, t) {
        oe.cssHooks[e + t] = {
            expand: function (n) {
                for (var r = 0, i = {}, o = "string" == typeof n ? n.split(" ") : [n]; 4 > r; r++)i[e + Ke[r] + t] = o[r] || o[r - 2] || o[0];
                return i
            }
        }, Xe.test(e) || (oe.cssHooks[e + t].set = T)
    });
    var et = /%20/g, tt = /\[\]$/, nt = /\r?\n/g, rt = /^(?:submit|button|image|reset|file)$/i, it = /^(?:input|select|textarea|keygen)/i;
    oe.fn.extend({
        serialize: function () {
            return oe.param(this.serializeArray())
        }, serializeArray: function () {
            return this.map(function () {
                var e = oe.prop(this, "elements");
                return e ? oe.makeArray(e) : this
            }).filter(function () {
                var e = this.type;
                return this.name && !oe(this).is(":disabled") && it.test(this.nodeName) && !rt.test(e) && (this.checked || !Pe.test(e))
            }).map(function (e, t) {
                var n = oe(this).val();
                return null == n ? null : oe.isArray(n) ? oe.map(n, function (e) {
                    return {name: t.name, value: e.replace(nt, "\r\n")}
                }) : {name: t.name, value: n.replace(nt, "\r\n")}
            }).get()
        }
    }), oe.param = function (e, n) {
        var r, i = [], o = function (e, t) {
            t = oe.isFunction(t) ? t() : null == t ? "" : t, i[i.length] = encodeURIComponent(e) + "=" + encodeURIComponent(t)
        };
        if (n === t && (n = oe.ajaxSettings && oe.ajaxSettings.traditional), oe.isArray(e) || e.jquery && !oe.isPlainObject(e))oe.each(e, function () {
            o(this.name, this.value)
        }); else for (r in e)E(r, e[r], n, o);
        return i.join("&").replace(et, "+")
    }, oe.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function (e, t) {
        oe.fn[t] = function (e, n) {
            return arguments.length > 0 ? this.on(t, null, e, n) : this.trigger(t)
        }
    }), oe.fn.extend({
        hover: function (e, t) {
            return this.mouseenter(e).mouseleave(t || e)
        }, bind: function (e, t, n) {
            return this.on(e, null, t, n)
        }, unbind: function (e, t) {
            return this.off(e, null, t)
        }, delegate: function (e, t, n, r) {
            return this.on(t, e, n, r)
        }, undelegate: function (e, t, n) {
            return 1 === arguments.length ? this.off(e, "**") : this.off(t, e || "**", n)
        }
    });
    var ot, st, at = oe.now(), ut = /\?/, lt = /#.*$/, ct = /([?&])_=[^&]*/, ft = /^(.*?):[ \t]*([^\r\n]*)$/gm, pt = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/, dt = /^(?:GET|HEAD)$/, ht = /^\/\//, gt = /^([\w.+-]+:)(?:\/\/([^\/?#:]*)(?::(\d+)|)|)/, mt = oe.fn.load, yt = {}, vt = {}, xt = "*/".concat("*");
    try {
        st = _.href
    } catch (bt) {
        st = X.createElement("a"), st.href = "", st = st.href
    }
    ot = gt.exec(st.toLowerCase()) || [], oe.fn.load = function (e, n, r) {
        if ("string" != typeof e && mt)return mt.apply(this, arguments);
        var i, o, s, a = this, u = e.indexOf(" ");
        return u >= 0 && (i = e.slice(u), e = e.slice(0, u)), oe.isFunction(n) ? (r = n, n = t) : n && "object" == typeof n && (o = "POST"), a.length > 0 && oe.ajax({
            url: e,
            type: o,
            dataType: "html",
            data: n
        }).done(function (e) {
            s = arguments, a.html(i ? oe("<div>").append(oe.parseHTML(e)).find(i) : e)
        }).complete(r && function (e, t) {
                a.each(r, s || [e.responseText, t, e])
            }), this
    }, oe.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function (e, t) {
        oe.fn[t] = function (e) {
            return this.on(t, e)
        }
    }), oe.extend({
        active: 0,
        lastModified: {},
        etag: {},
        ajaxSettings: {
            url: st,
            type: "GET",
            isLocal: pt.test(ot[1]),
            global: !0,
            processData: !0,
            async: !0,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {"*": xt, text: "text/plain", html: "text/html", xml: "application/xml, text/xml", json: "application/json, text/javascript"},
            contents: {xml: /xml/, html: /html/, json: /json/},
            responseFields: {xml: "responseXML", text: "responseText", json: "responseJSON"},
            converters: {"* text": String, "text html": !0, "text json": oe.parseJSON, "text xml": oe.parseXML},
            flatOptions: {url: !0, context: !0}
        },
        ajaxSetup: function (e, t) {
            return t ? A(A(e, oe.ajaxSettings), t) : A(oe.ajaxSettings, e)
        },
        ajaxPrefilter: S(yt),
        ajaxTransport: S(vt),
        ajax: function (e, n) {
            function r(e, n, r, a) {
                var l, f, v, x, w, C = n;
                2 !== b && (b = 2, u && clearTimeout(u), i = t, s = a || "", T.readyState = e > 0 ? 4 : 0, l = e >= 200 && 300 > e || 304 === e, r && (x = q(p, T, r)), x = L(p, x, T, l), l ? (p.ifModified && (w = T.getResponseHeader("Last-Modified"), w && (oe.lastModified[o] = w), w = T.getResponseHeader("etag"), w && (oe.etag[o] = w)), 204 === e || "HEAD" === p.type ? C = "nocontent" : 304 === e ? C = "notmodified" : (C = x.state, f = x.data, v = x.error, l = !v)) : (v = C, (e || !C) && (C = "error", 0 > e && (e = 0))), T.status = e, T.statusText = (n || C) + "", l ? g.resolveWith(d, [f, C, T]) : g.rejectWith(d, [T, C, v]), T.statusCode(y), y = t, c && h.trigger(l ? "ajaxSuccess" : "ajaxError", [T, p, l ? f : v]), m.fireWith(d, [T, C]), c && (h.trigger("ajaxComplete", [T, p]), --oe.active || oe.event.trigger("ajaxStop")))
            }

            "object" == typeof e && (n = e, e = t), n = n || {};
            var i, o, s, a, u, l, c, f, p = oe.ajaxSetup({}, n), d = p.context || p, h = p.context && (d.nodeType || d.jquery) ? oe(d) : oe.event, g = oe.Deferred(), m = oe.Callbacks("once memory"), y = p.statusCode || {}, v = {}, x = {}, b = 0, w = "canceled", T = {
                readyState: 0,
                getResponseHeader: function (e) {
                    var t;
                    if (2 === b) {
                        if (!a)for (a = {}; t = ft.exec(s);)a[t[1].toLowerCase()] = t[2];
                        t = a[e.toLowerCase()]
                    }
                    return null == t ? null : t
                },
                getAllResponseHeaders: function () {
                    return 2 === b ? s : null
                },
                setRequestHeader: function (e, t) {
                    var n = e.toLowerCase();
                    return b || (e = x[n] = x[n] || e, v[e] = t), this
                },
                overrideMimeType: function (e) {
                    return b || (p.mimeType = e), this
                },
                statusCode: function (e) {
                    var t;
                    if (e)if (2 > b)for (t in e)y[t] = [y[t], e[t]]; else T.always(e[T.status]);
                    return this
                },
                abort: function (e) {
                    var t = e || w;
                    return i && i.abort(t), r(0, t), this
                }
            };
            if (g.promise(T).complete = m.add, T.success = T.done, T.error = T.fail, p.url = ((e || p.url || st) + "").replace(lt, "").replace(ht, ot[1] + "//"), p.type = n.method || n.type || p.method || p.type, p.dataTypes = oe.trim(p.dataType || "*").toLowerCase().match(ae) || [""], null == p.crossDomain && (l = gt.exec(p.url.toLowerCase()), p.crossDomain = !(!l || l[1] === ot[1] && l[2] === ot[2] && (l[3] || ("http:" === l[1] ? "80" : "443")) === (ot[3] || ("http:" === ot[1] ? "80" : "443")))), p.data && p.processData && "string" != typeof p.data && (p.data = oe.param(p.data, p.traditional)), D(yt, p, n, T), 2 === b)return T;
            c = p.global, c && 0 === oe.active++ && oe.event.trigger("ajaxStart"), p.type = p.type.toUpperCase(), p.hasContent = !dt.test(p.type), o = p.url, p.hasContent || (p.data && (o = p.url += (ut.test(o) ? "&" : "?") + p.data, delete p.data), p.cache === !1 && (p.url = ct.test(o) ? o.replace(ct, "$1_=" + at++) : o + (ut.test(o) ? "&" : "?") + "_=" + at++)), p.ifModified && (oe.lastModified[o] && T.setRequestHeader("If-Modified-Since", oe.lastModified[o]), oe.etag[o] && T.setRequestHeader("If-None-Match", oe.etag[o])), (p.data && p.hasContent && p.contentType !== !1 || n.contentType) && T.setRequestHeader("Content-Type", p.contentType), T.setRequestHeader("Accept", p.dataTypes[0] && p.accepts[p.dataTypes[0]] ? p.accepts[p.dataTypes[0]] + ("*" !== p.dataTypes[0] ? ", " + xt + "; q=0.01" : "") : p.accepts["*"]);
            for (f in p.headers)T.setRequestHeader(f, p.headers[f]);
            if (p.beforeSend && (p.beforeSend.call(d, T, p) === !1 || 2 === b))return T.abort();
            w = "abort";
            for (f in{success: 1, error: 1, complete: 1})T[f](p[f]);
            if (i = D(vt, p, n, T)) {
                T.readyState = 1, c && h.trigger("ajaxSend", [T, p]), p.async && p.timeout > 0 && (u = setTimeout(function () {
                    T.abort("timeout")
                }, p.timeout));
                try {
                    b = 1, i.send(v, r)
                } catch (C) {
                    if (!(2 > b))throw C;
                    r(-1, C)
                }
            } else r(-1, "No Transport");
            return T
        },
        getJSON: function (e, t, n) {
            return oe.get(e, t, n, "json")
        },
        getScript: function (e, n) {
            return oe.get(e, t, n, "script")
        }
    }), oe.each(["get", "post"], function (e, n) {
        oe[n] = function (e, r, i, o) {
            return oe.isFunction(r) && (o = o || i, i = r, r = t), oe.ajax({url: e, type: n, dataType: o, data: r, success: i})
        }
    }), oe.ajaxSetup({
        accepts: {script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},
        contents: {script: /(?:java|ecma)script/},
        converters: {
            "text script": function (e) {
                return oe.globalEval(e), e
            }
        }
    }), oe.ajaxPrefilter("script", function (e) {
        e.cache === t && (e.cache = !1), e.crossDomain && (e.type = "GET")
    }), oe.ajaxTransport("script", function (e) {
        if (e.crossDomain) {
            var t, n;
            return {
                send: function (r, i) {
                    t = oe("<script>").prop({async: !0, charset: e.scriptCharset, src: e.url}).on("load error", n = function (e) {
                        t.remove(), n = null, e && i("error" === e.type ? 404 : 200, e.type)
                    }), X.head.appendChild(t[0])
                }, abort: function () {
                    n && n()
                }
            }
        }
    });
    var wt = [], Tt = /(=)\?(?=&|$)|\?\?/;
    oe.ajaxSetup({
        jsonp: "callback", jsonpCallback: function () {
            var e = wt.pop() || oe.expando + "_" + at++;
            return this[e] = !0, e
        }
    }), oe.ajaxPrefilter("json jsonp", function (n, r, i) {
        var o, s, a, u = n.jsonp !== !1 && (Tt.test(n.url) ? "url" : "string" == typeof n.data && !(n.contentType || "").indexOf("application/x-www-form-urlencoded") && Tt.test(n.data) && "data");
        return u || "jsonp" === n.dataTypes[0] ? (o = n.jsonpCallback = oe.isFunction(n.jsonpCallback) ? n.jsonpCallback() : n.jsonpCallback, u ? n[u] = n[u].replace(Tt, "$1" + o) : n.jsonp !== !1 && (n.url += (ut.test(n.url) ? "&" : "?") + n.jsonp + "=" + o), n.converters["script json"] = function () {
            return a || oe.error(o + " was not called"), a[0]
        }, n.dataTypes[0] = "json", s = e[o], e[o] = function () {
            a = arguments
        }, i.always(function () {
            e[o] = s, n[o] && (n.jsonpCallback = r.jsonpCallback, wt.push(o)), a && oe.isFunction(s) && s(a[0]), a = s = t
        }), "script") : void 0
    }), oe.ajaxSettings.xhr = function () {
        try {
            return new XMLHttpRequest
        } catch (e) {
        }
    };
    var Ct = oe.ajaxSettings.xhr(), kt = {0: 200, 1223: 204}, Nt = 0, jt = {};
    e.ActiveXObject && oe(e).on("unload", function () {
        for (var e in jt)jt[e]();
        jt = t
    }), oe.support.cors = !!Ct && "withCredentials"in Ct, oe.support.ajax = Ct = !!Ct, oe.ajaxTransport(function (e) {
        var n;
        return oe.support.cors || Ct && !e.crossDomain ? {
            send: function (r, i) {
                var o, s, a = e.xhr();
                /* if (a.open(e.type, e.url, e.async, e.username, e.password), e.xhrFields)
                    for (o in e.xhrFields)a[o] = e.xhrFields[o];
                e.mimeType && a.overrideMimeType && a.overrideMimeType(e.mimeType), e.crossDomain || r["X-Requested-With"] || (r["X-Requested-With"] = "XMLHttpRequest");
                for (o in r)a.setRequestHeader(o, r[o]);
                n = function (e) {
                    return function () {
                        n && (delete jt[s],
                            n = a.onload = a.onerror = null, "abort" === e ? a.abort() : "error" === e ? i(a.status || 404, a.statusText) : i(kt[a.status] || a.status, a.statusText, "string" == typeof a.responseText ? {text: a.responseText} : t, a.getAllResponseHeaders()))
                    }
                },*/
                    a.onload = n(),
                    a.onerror = n("error"), n = jt[s = Nt++] = n("abort"), a.send(e.hasContent && e.data || null)
            }, abort: function () {
                n && n()
            }
        } : void 0
    });
    var Et, St, Dt = /^(?:toggle|show|hide)$/, At = new RegExp("^(?:([+-])=|)(" + se + ")([a-z%]*)$", "i"), qt = /queueHooks$/, Lt = [R], Ht = {
        "*": [function (e, t) {
            var n = this.createTween(e, t), r = n.cur(), i = At.exec(t), o = i && i[3] || (oe.cssNumber[e] ? "" : "px"), s = (oe.cssNumber[e] || "px" !== o && +r) && At.exec(oe.css(n.elem, e)), a = 1, u = 20;
            if (s && s[3] !== o) {
                o = o || s[3], i = i || [], s = +r || 1;
                do a = a || ".5", s /= a, oe.style(n.elem, e, s + o); while (a !== (a = n.cur() / r) && 1 !== a && --u)
            }
            return i && (s = n.start = +s || +r || 0, n.unit = o, n.end = i[1] ? s + (i[1] + 1) * i[2] : +i[2]), n
        }]
    };
    oe.Animation = oe.extend(F, {
        tweener: function (e, t) {
            oe.isFunction(e) ? (t = e, e = ["*"]) : e = e.split(" ");
            for (var n, r = 0, i = e.length; i > r; r++)n = e[r], Ht[n] = Ht[n] || [], Ht[n].unshift(t)
        }, prefilter: function (e, t) {
            t ? Lt.unshift(e) : Lt.push(e)
        }
    }), oe.Tween = M, M.prototype = {
        constructor: M, init: function (e, t, n, r, i, o) {
            this.elem = e, this.prop = n, this.easing = i || "swing", this.options = t, this.start = this.now = this.cur(), this.end = r, this.unit = o || (oe.cssNumber[n] ? "" : "px")
        }, cur: function () {
            var e = M.propHooks[this.prop];
            return e && e.get ? e.get(this) : M.propHooks._default.get(this)
        }, run: function (e) {
            var t, n = M.propHooks[this.prop];
            return this.options.duration ? this.pos = t = oe.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration) : this.pos = t = e, this.now = (this.end - this.start) * t + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), n && n.set ? n.set(this) : M.propHooks._default.set(this), this
        }
    }, M.prototype.init.prototype = M.prototype, M.propHooks = {
        _default: {
            get: function (e) {
                var t;
                return null == e.elem[e.prop] || e.elem.style && null != e.elem.style[e.prop] ? (t = oe.css(e.elem, e.prop, ""), t && "auto" !== t ? t : 0) : e.elem[e.prop]
            }, set: function (e) {
                oe.fx.step[e.prop] ? oe.fx.step[e.prop](e) : e.elem.style && (null != e.elem.style[oe.cssProps[e.prop]] || oe.cssHooks[e.prop]) ? oe.style(e.elem, e.prop, e.now + e.unit) : e.elem[e.prop] = e.now
            }
        }
    }, M.propHooks.scrollTop = M.propHooks.scrollLeft = {
        set: function (e) {
            e.elem.nodeType && e.elem.parentNode && (e.elem[e.prop] = e.now)
        }
    }, oe.each(["toggle", "show", "hide"], function (e, t) {
        var n = oe.fn[t];
        oe.fn[t] = function (e, r, i) {
            return null == e || "boolean" == typeof e ? n.apply(this, arguments) : this.animate(W(t, !0), e, r, i)
        }
    }), oe.fn.extend({
        fadeTo: function (e, t, n, r) {
            return this.filter(x).css("opacity", 0).show().end().animate({opacity: t}, e, n, r)
        }, animate: function (e, t, n, r) {
            var i = oe.isEmptyObject(e), o = oe.speed(t, n, r), s = function () {
                var t = F(this, oe.extend({}, e), o);
                (i || me.get(this, "finish")) && t.stop(!0)
            };
            return s.finish = s, i || o.queue === !1 ? this.each(s) : this.queue(o.queue, s)
        }, stop: function (e, n, r) {
            var i = function (e) {
                var t = e.stop;
                delete e.stop, t(r)
            };
            return "string" != typeof e && (r = n, n = e, e = t), n && e !== !1 && this.queue(e || "fx", []), this.each(function () {
                var t = !0, n = null != e && e + "queueHooks", o = oe.timers, s = me.get(this);
                if (n)s[n] && s[n].stop && i(s[n]); else for (n in s)s[n] && s[n].stop && qt.test(n) && i(s[n]);
                for (n = o.length; n--;)o[n].elem !== this || null != e && o[n].queue !== e || (o[n].anim.stop(r), t = !1, o.splice(n, 1));
                (t || !r) && oe.dequeue(this, e)
            })
        }, finish: function (e) {
            return e !== !1 && (e = e || "fx"), this.each(function () {
                var t, n = me.get(this), r = n[e + "queue"], i = n[e + "queueHooks"], o = oe.timers, s = r ? r.length : 0;
                for (n.finish = !0, oe.queue(this, e, []), i && i.stop && i.stop.call(this, !0), t = o.length; t--;)o[t].elem === this && o[t].queue === e && (o[t].anim.stop(!0), o.splice(t, 1));
                for (t = 0; s > t; t++)r[t] && r[t].finish && r[t].finish.call(this);
                delete n.finish
            })
        }
    }), oe.each({
        slideDown: W("show"),
        slideUp: W("hide"),
        slideToggle: W("toggle"),
        fadeIn: {opacity: "show"},
        fadeOut: {opacity: "hide"},
        fadeToggle: {opacity: "toggle"}
    }, function (e, t) {
        oe.fn[e] = function (e, n, r) {
            return this.animate(t, e, n, r)
        }
    }), oe.speed = function (e, t, n) {
        var r = e && "object" == typeof e ? oe.extend({}, e) : {
            complete: n || !n && t || oe.isFunction(e) && e,
            duration: e,
            easing: n && t || t && !oe.isFunction(t) && t
        };
        return r.duration = oe.fx.off ? 0 : "number" == typeof r.duration ? r.duration : r.duration in oe.fx.speeds ? oe.fx.speeds[r.duration] : oe.fx.speeds._default, (null == r.queue || r.queue === !0) && (r.queue = "fx"), r.old = r.complete, r.complete = function () {
            oe.isFunction(r.old) && r.old.call(this), r.queue && oe.dequeue(this, r.queue)
        }, r
    }, oe.easing = {
        linear: function (e) {
            return e
        }, swing: function (e) {
            return .5 - Math.cos(e * Math.PI) / 2
        }
    }, oe.timers = [], oe.fx = M.prototype.init, oe.fx.tick = function () {
        var e, n = oe.timers, r = 0;
        for (Et = oe.now(); r < n.length; r++)e = n[r], e() || n[r] !== e || n.splice(r--, 1);
        n.length || oe.fx.stop(), Et = t
    }, oe.fx.timer = function (e) {
        e() && oe.timers.push(e) && oe.fx.start()
    }, oe.fx.interval = 13, oe.fx.start = function () {
        St || (St = setInterval(oe.fx.tick, oe.fx.interval))
    }, oe.fx.stop = function () {
        clearInterval(St), St = null
    }, oe.fx.speeds = {slow: 600, fast: 200, _default: 400}, oe.fx.step = {}, oe.expr && oe.expr.filters && (oe.expr.filters.animated = function (e) {
        return oe.grep(oe.timers, function (t) {
            return e === t.elem
        }).length
    }), oe.fn.offset = function (e) {
        if (arguments.length)return e === t ? this : this.each(function (t) {
            oe.offset.setOffset(this, e, t)
        });
        var n, r, i = this[0], o = {top: 0, left: 0}, s = i && i.ownerDocument;
        if (s)return n = s.documentElement, oe.contains(n, i) ? (typeof i.getBoundingClientRect !== z && (o = i.getBoundingClientRect()), r = $(s), {
            top: o.top + r.pageYOffset - n.clientTop,
            left: o.left + r.pageXOffset - n.clientLeft
        }) : o
    }, oe.offset = {
        setOffset: function (e, t, n) {
            var r, i, o, s, a, u, l, c = oe.css(e, "position"), f = oe(e), p = {};
            "static" === c && (e.style.position = "relative"), a = f.offset(), o = oe.css(e, "top"), u = oe.css(e, "left"), l = ("absolute" === c || "fixed" === c) && (o + u).indexOf("auto") > -1, l ? (r = f.position(), s = r.top, i = r.left) : (s = parseFloat(o) || 0, i = parseFloat(u) || 0), oe.isFunction(t) && (t = t.call(e, n, a)), null != t.top && (p.top = t.top - a.top + s), null != t.left && (p.left = t.left - a.left + i), "using"in t ? t.using.call(e, p) : f.css(p)
        }
    }, oe.fn.extend({
        position: function () {
            if (this[0]) {
                var e, t, n = this[0], r = {top: 0, left: 0};
                return "fixed" === oe.css(n, "position") ? t = n.getBoundingClientRect() : (e = this.offsetParent(), t = this.offset(), oe.nodeName(e[0], "html") || (r = e.offset()), r.top += oe.css(e[0], "borderTopWidth", !0), r.left += oe.css(e[0], "borderLeftWidth", !0)), {
                    top: t.top - r.top - oe.css(n, "marginTop", !0),
                    left: t.left - r.left - oe.css(n, "marginLeft", !0)
                }
            }
        }, offsetParent: function () {
            return this.map(function () {
                for (var e = this.offsetParent || U; e && !oe.nodeName(e, "html") && "static" === oe.css(e, "position");)e = e.offsetParent;
                return e || U
            })
        }
    }), oe.each({scrollLeft: "pageXOffset", scrollTop: "pageYOffset"}, function (n, r) {
        var i = "pageYOffset" === r;
        oe.fn[n] = function (o) {
            return oe.access(this, function (n, o, s) {
                var a = $(n);
                return s === t ? a ? a[r] : n[o] : void(a ? a.scrollTo(i ? e.pageXOffset : s, i ? s : e.pageYOffset) : n[o] = s)
            }, n, o, arguments.length, null)
        }
    }), oe.each({Height: "height", Width: "width"}, function (e, n) {
        oe.each({padding: "inner" + e, content: n, "": "outer" + e}, function (r, i) {
            oe.fn[i] = function (i, o) {
                var s = arguments.length && (r || "boolean" != typeof i), a = r || (i === !0 || o === !0 ? "margin" : "border");
                return oe.access(this, function (n, r, i) {
                    var o;
                    return oe.isWindow(n) ? n.document.documentElement["client" + e] : 9 === n.nodeType ? (o = n.documentElement, Math.max(n.body["scroll" + e], o["scroll" + e], n.body["offset" + e], o["offset" + e], o["client" + e])) : i === t ? oe.css(n, r, a) : oe.style(n, r, i, a)
                }, n, s ? i : t, s, null)
            }
        })
    }), oe.fn.size = function () {
        return this.length
    }, oe.fn.andSelf = oe.fn.addBack, "object" == typeof module && module && "object" == typeof module.exports ? module.exports = oe : "function" == typeof define && define.amd && define("jquery", [], function () {
        return oe
    }), "object" == typeof e && "object" == typeof e.document && (e.jQuery = e.$ = oe)
}(window);
var requirejs, require, define;
!function (e) {
    function t(e, t) {
        return y.call(e, t)
    }

    function n(e, t) {
        var n, r, i, o, s, a, u, l, c, f, p, d = t && t.split("/"), h = g.map, m = h && h["*"] || {};
        if (e && "." === e.charAt(0))if (t) {
            for (d = d.slice(0, d.length - 1), e = e.split("/"), s = e.length - 1, g.nodeIdCompat && x.test(e[s]) && (e[s] = e[s].replace(x, "")), e = d.concat(e), c = 0; c < e.length; c += 1)if (p = e[c], "." === p)e.splice(c, 1), c -= 1; else if (".." === p) {
                if (1 === c && (".." === e[2] || ".." === e[0]))break;
                c > 0 && (e.splice(c - 1, 2), c -= 2)
            }
            e = e.join("/")
        } else 0 === e.indexOf("./") && (e = e.substring(2));
        if ((d || m) && h) {
            for (n = e.split("/"), c = n.length; c > 0; c -= 1) {
                if (r = n.slice(0, c).join("/"), d)for (f = d.length; f > 0; f -= 1)if (i = h[d.slice(0, f).join("/")], i && (i = i[r])) {
                    o = i, a = c;
                    break
                }
                if (o)break;
                !u && m && m[r] && (u = m[r], l = c)
            }
            !o && u && (o = u, a = l), o && (n.splice(0, a, o), e = n.join("/"))
        }
        return e
    }

    function r(t, n) {
        return function () {
            var r = v.call(arguments, 0);
            return "string" != typeof r[0] && 1 === r.length && r.push(null), c.apply(e, r.concat([t, n]))
        }
    }

    function i(e) {
        return function (t) {
            return n(t, e)
        }
    }

    function o(e) {
        return function (t) {
            d[e] = t
        }
    }

    function s(n) {
        if (t(h, n)) {
            var r = h[n];
            delete h[n], m[n] = !0, l.apply(e, r)
        }
        if (!t(d, n) && !t(m, n))throw new Error("No " + n);
        return d[n]
    }

    function a(e) {
        var t, n = e ? e.indexOf("!") : -1;
        return n > -1 && (t = e.substring(0, n), e = e.substring(n + 1, e.length)), [t, e]
    }

    function u(e) {
        return function () {
            return g && g.config && g.config[e] || {}
        }
    }

    var l, c, f, p, d = {}, h = {}, g = {}, m = {}, y = Object.prototype.hasOwnProperty, v = [].slice, x = /\.js$/;
    f = function (e, t) {
        var r, o = a(e), u = o[0];
        return e = o[1], u && (u = n(u, t), r = s(u)), u ? e = r && r.normalize ? r.normalize(e, i(t)) : n(e, t) : (e = n(e, t), o = a(e), u = o[0], e = o[1], u && (r = s(u))), {
            f: u ? u + "!" + e : e,
            n: e,
            pr: u,
            p: r
        }
    }, p = {
        require: function (e) {
            return r(e)
        }, exports: function (e) {
            var t = d[e];
            return "undefined" != typeof t ? t : d[e] = {}
        }, module: function (e) {
            return {id: e, uri: "", exports: d[e], config: u(e)}
        }
    }, l = function (n, i, a, u) {
        var l, c, g, y, v, x, b = [], w = typeof a;
        if (u = u || n, "undefined" === w || "function" === w) {
            for (i = !i.length && a.length ? ["require", "exports", "module"] : i, v = 0; v < i.length; v += 1)if (y = f(i[v], u), c = y.f, "require" === c)b[v] = p.require(n); else if ("exports" === c)b[v] = p.exports(n), x = !0; else if ("module" === c)l = b[v] = p.module(n); else if (t(d, c) || t(h, c) || t(m, c))b[v] = s(c); else {
                if (!y.p)throw new Error(n + " missing " + c);
                y.p.load(y.n, r(u, !0), o(c), {}), b[v] = d[c]
            }
            g = a ? a.apply(d[n], b) : void 0, n && (l && l.exports !== e && l.exports !== d[n] ? d[n] = l.exports : g === e && x || (d[n] = g))
        } else n && (d[n] = a)
    }, requirejs = require = c = function (t, n, r, i, o) {
        if ("string" == typeof t)return p[t] ? p[t](n) : s(f(t, n).f);
        if (!t.splice) {
            if (g = t, g.deps && c(g.deps, g.callback), !n)return;
            n.splice ? (t = n, n = r, r = null) : t = e
        }
        return n = n || function () {
            }, "function" == typeof r && (r = i, i = o), i ? l(e, t, n, r) : setTimeout(function () {
            l(e, t, n, r)
        }, 4), c
    }, c.config = function (e) {
        return c(e)
    }, requirejs._defined = d, define = function (e, n, r) {
        n.splice || (r = n, n = []), t(d, e) || t(h, e) || (h[e] = [e, n, r])
    }, define.amd = {jQuery: !0}
}(), define("text", [], function () {
}), define("backbone", [], function () {
    return Backbone
}), define("underscore", [], function () {
    return _
}), define("zepto", [], function () {
    return Zepto
}), define("jquery", [], function () {
    return Zepto
});