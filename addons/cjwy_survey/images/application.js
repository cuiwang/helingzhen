(function() {
	"use strict";

	function e(t, r) {
		function s(e, t) {
			return function() {
				return e.apply(t, arguments)
			}
		}
		var i;
		r = r || {}, this.trackingClick = !1, this.trackingClickStart = 0, this.targetElement = null, this.touchStartX = 0, this.touchStartY = 0, this.lastTouchIdentifier = 0, this.touchBoundary = r.touchBoundary || 10, this.layer = t, this.tapDelay = r.tapDelay || 200, this.tapTimeout = r.tapTimeout || 700;
		if (e.notNeeded(t)) return;
		var o = ["onMouse", "onClick", "onTouchStart", "onTouchMove", "onTouchEnd", "onTouchCancel"],
			u = this;
		for (var a = 0, f = o.length; a < f; a++) u[o[a]] = s(u[o[a]], u);
		n && (t.addEventListener("mouseover", this.onMouse, !0), t.addEventListener("mousedown", this.onMouse, !0), t.addEventListener("mouseup", this.onMouse, !0)), t.addEventListener("click", this.onClick, !0), t.addEventListener("touchstart", this.onTouchStart, !1), t.addEventListener("touchmove", this.onTouchMove, !1), t.addEventListener("touchend", this.onTouchEnd, !1), t.addEventListener("touchcancel", this.onTouchCancel, !1), Event.prototype.stopImmediatePropagation || (t.removeEventListener = function(e, n, r) {
			var i = Node.prototype.removeEventListener;
			e === "click" ? i.call(t, e, n.hijacked || n, r) : i.call(t, e, n, r)
		}, t.addEventListener = function(e, n, r) {
			var i = Node.prototype.addEventListener;
			e === "click" ? i.call(t, e, n.hijacked || (n.hijacked = function(e) {
				e.propagationStopped || n(e)
			}), r) : i.call(t, e, n, r)
		}), typeof t.onclick == "function" && (i = t.onclick, t.addEventListener("click", function(e) {
			i(e)
		}, !1), t.onclick = null)
	}
	var t = navigator.userAgent.indexOf("Windows Phone") >= 0,
		n = navigator.userAgent.indexOf("Android") > 0 && !t,
		r = /iP(ad|hone|od)/.test(navigator.userAgent) && !t,
		i = r && /OS 4_\d(_\d)?/.test(navigator.userAgent),
		s = r && /OS [6-7]_\d/.test(navigator.userAgent),
		o = navigator.userAgent.indexOf("BB10") > 0;
	e.prototype.needsClick = function(e) {
		switch (e.nodeName.toLowerCase()) {
		case "button":
		case "select":
		case "textarea":
			if (e.disabled) return !0;
			break;
		case "input":
			if (r && e.type === "file" || e.disabled) return !0;
			break;
		case "label":
		case "iframe":
		case "video":
			return !0
		}
		return /\bneedsclick\b/.test(e.className)
	}, e.prototype.needsFocus = function(e) {
		switch (e.nodeName.toLowerCase()) {
		case "textarea":
			return !0;
		case "select":
			return !1;
		case "input":
			switch (e.type) {
			case "button":
			case "checkbox":
			case "file":
			case "image":
			case "radio":
			case "submit":
				return !1
			}
			return !e.disabled && !e.readOnly;
		default:
			return /\bneedsfocus\b/.test(e.className)
		}
	}, e.prototype.sendClick = function(e, t) {
		var n, r, i, s;
		document.activeElement && document.activeElement !== e && document.activeElement.blur(), s = t.changedTouches[0], i = document.createEvent("MouseEvents"), i.initMouseEvent("mousedown", !0, !0, window, 1, s.screenX, s.screenY, s.clientX, s.clientY, !1, !1, !1, !1, 0, null), i.forwardedTouchEvent = !0, e.dispatchEvent(i), r = document.createEvent("MouseEvents"), r.initMouseEvent("mouseup", !0, !0, window, 1, s.screenX, s.screenY, s.clientX, s.clientY, !1, !1, !1, !1, 0, null), r.forwardedTouchEvent = !0, e.dispatchEvent(r), n = document.createEvent("MouseEvents"), n.initMouseEvent(this.determineEventType(e), !0, !0, window, 1, s.screenX, s.screenY, s.clientX, s.clientY, !1, !1, !1, !1, 0, null), n.forwardedTouchEvent = !0, e.dispatchEvent(n)
	}, e.prototype.determineEventType = function(e) {
		return n && e.tagName.toLowerCase() === "select" ? "mousedown" : "click"
	}, e.prototype.focus = function(e) {
		var t;
		r && e.setSelectionRange && e.type.indexOf("date") !== 0 && e.type !== "time" && e.type !== "month" ? (t = e.value.length, e.setSelectionRange(t, t)) : e.focus()
	}, e.prototype.updateScrollParent = function(e) {
		var t, n;
		t = e.fastClickScrollParent;
		if (!t || !t.contains(e)) {
			n = e;
			do {
				if (n.scrollHeight > n.offsetHeight) {
					t = n, e.fastClickScrollParent = n;
					break
				}
				n = n.parentElement
			} while (n)
		}
		t && (t.fastClickLastScrollTop = t.scrollTop)
	}, e.prototype.getTargetElementFromEventTarget = function(e) {
		return e.nodeType === Node.TEXT_NODE ? e.parentNode : e
	}, e.prototype.onTouchStart = function(e) {
		var t, n, s;
		if (e.targetTouches.length > 1) return !0;
		t = this.getTargetElementFromEventTarget(e.target), n = e.targetTouches[0];
		if (r) {
			s = window.getSelection();
			if (s.rangeCount && !s.isCollapsed) return !0;
			if (!i) {
				if (n.identifier && n.identifier === this.lastTouchIdentifier) return e.preventDefault(), !1;
				this.lastTouchIdentifier = n.identifier, this.updateScrollParent(t)
			}
		}
		return this.trackingClick = !0, this.trackingClickStart = e.timeStamp, this.targetElement = t, this.touchStartX = n.pageX, this.touchStartY = n.pageY, e.timeStamp - this.lastClickTime < this.tapDelay && e.preventDefault(), !0
	}, e.prototype.touchHasMoved = function(e) {
		var t = e.changedTouches[0],
			n = this.touchBoundary;
		return Math.abs(t.pageX - this.touchStartX) > n || Math.abs(t.pageY - this.touchStartY) > n ? !0 : !1
	}, e.prototype.onTouchMove = function(e) {
		if (!this.trackingClick) return !0;
		if (this.targetElement !== this.getTargetElementFromEventTarget(e.target) || this.touchHasMoved(e)) this.trackingClick = !1, this.targetElement = null;
		return !0
	}, e.prototype.findControl = function(e) {
		return e.control !== undefined ? e.control : e.htmlFor ? document.getElementById(e.htmlFor) : e.querySelector("button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea")
	}, e.prototype.onTouchEnd = function(e) {
		var t, o, u, a, f, l = this.targetElement;
		if (!this.trackingClick) return !0;
		if (e.timeStamp - this.lastClickTime < this.tapDelay) return this.cancelNextClick = !0, !0;
		if (e.timeStamp - this.trackingClickStart > this.tapTimeout) return !0;
		this.cancelNextClick = !1, this.lastClickTime = e.timeStamp, o = this.trackingClickStart, this.trackingClick = !1, this.trackingClickStart = 0, s && (f = e.changedTouches[0], l = document.elementFromPoint(f.pageX - window.pageXOffset, f.pageY - window.pageYOffset) || l, l.fastClickScrollParent = this.targetElement.fastClickScrollParent), u = l.tagName.toLowerCase();
		if (u === "label") {
			t = this.findControl(l);
			if (t) {
				this.focus(l);
				if (n) return !1;
				l = t
			}
		} else if (this.needsFocus(l)) {
			if (e.timeStamp - o > 100 || r && window.top !== window && u === "input") return this.targetElement = null, !1;
			this.focus(l), this.sendClick(l, e);
			if (!r || u !== "select") this.targetElement = null, e.preventDefault();
			return !1
		}
		if (r && !i) {
			a = l.fastClickScrollParent;
			if (a && a.fastClickLastScrollTop !== a.scrollTop) return !0
		}
		return this.needsClick(l) || (e.preventDefault(), this.sendClick(l, e)), !1
	}, e.prototype.onTouchCancel = function() {
		this.trackingClick = !1, this.targetElement = null
	}, e.prototype.onMouse = function(e) {
		return this.targetElement ? e.forwardedTouchEvent ? !0 : e.cancelable ? !this.needsClick(this.targetElement) || this.cancelNextClick ? (e.stopImmediatePropagation ? e.stopImmediatePropagation() : e.propagationStopped = !0, e.stopPropagation(), e.preventDefault(), !1) : !0 : !0 : !0
	}, e.prototype.onClick = function(e) {
		var t;
		return this.trackingClick ? (this.targetElement = null, this.trackingClick = !1, !0) : e.target.type === "submit" && e.detail === 0 ? !0 : (t = this.onMouse(e), t || (this.targetElement = null), t)
	}, e.prototype.destroy = function() {
		var e = this.layer;
		n && (e.removeEventListener("mouseover", this.onMouse, !0), e.removeEventListener("mousedown", this.onMouse, !0), e.removeEventListener("mouseup", this.onMouse, !0)), e.removeEventListener("click", this.onClick, !0), e.removeEventListener("touchstart", this.onTouchStart, !1), e.removeEventListener("touchmove", this.onTouchMove, !1), e.removeEventListener("touchend", this.onTouchEnd, !1), e.removeEventListener("touchcancel", this.onTouchCancel, !1)
	}, e.notNeeded = function(e) {
		var t, r, i, s;
		if (typeof window.ontouchstart == "undefined") return !0;
		r = +(/Chrome\/([0-9]+)/.exec(navigator.userAgent) || [, 0])[1];
		if (r) {
			if (!n) return !0;
			t = document.querySelector("meta[name=viewport]");
			if (t) {
				if (t.content.indexOf("user-scalable=no") !== -1) return !0;
				if (r > 31 && document.documentElement.scrollWidth <= window.outerWidth) return !0
			}
		}
		if (o) {
			i = navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/);
			if (i[1] >= 10 && i[2] >= 3) {
				t = document.querySelector("meta[name=viewport]");
				if (t) {
					if (t.content.indexOf("user-scalable=no") !== -1) return !0;
					if (document.documentElement.scrollWidth <= window.outerWidth) return !0
				}
			}
		}
		if (e.style.msTouchAction === "none" || e.style.touchAction === "manipulation") return !0;
		s = +(/Firefox\/([0-9]+)/.exec(navigator.userAgent) || [, 0])[1];
		if (s >= 27) {
			t = document.querySelector("meta[name=viewport]");
			if (t && (t.content.indexOf("user-scalable=no") !== -1 || document.documentElement.scrollWidth <= window.outerWidth)) return !0
		}
		return e.style.touchAction === "none" || e.style.touchAction === "manipulation" ? !0 : !1
	}, e.attach = function(t, n) {
		return new e(t, n)
	}, typeof define == "function" && typeof define.amd == "object" && define.amd ? define(function() {
		return e
	}) : typeof module != "undefined" && module.exports ? (module.exports = e.attach, module.exports.FastClick = e) : window.FastClick = e
})(), function() {
	function x(e) {
		function t(t, n, r, i, s, o) {
			for (; s >= 0 && s < o; s += e) {
				var u = i ? i[s] : s;
				r = n(r, t[u], u, t)
			}
			return r
		}
		return function(n, r, i, s) {
			r = v(r, s, 4);
			var o = !S(n) && d.keys(n),
				u = (o || n).length,
				a = e > 0 ? 0 : u - 1;
			return arguments.length < 3 && (i = n[o ? o[a] : a], a += e), t(n, r, i, o, a, u)
		}
	}
	function C(e) {
		return function(t, n, r) {
			n = m(n, r);
			var i = E(t),
				s = e > 0 ? 0 : i - 1;
			for (; s >= 0 && s < i; s += e) if (n(t[s], s, t)) return s;
			return -1
		}
	}
	function k(e, t, n) {
		return function(r, i, s) {
			var u = 0,
				a = E(r);
			if (typeof s == "number") e > 0 ? u = s >= 0 ? s : Math.max(s + a, u) : a = s >= 0 ? Math.min(s + 1, a) : s + a + 1;
			else if (n && s && a) return s = n(r, i), r[s] === i ? s : -1;
			if (i !== i) return s = t(o.call(r, u, a), d.isNaN), s >= 0 ? s + u : -1;
			for (s = e > 0 ? u : a - 1; s >= 0 && s < a; s += e) if (r[s] === i) return s;
			return -1
		}
	}
	function M(e, t) {
		var n = O.length,
			i = e.constructor,
			s = d.isFunction(i) && i.prototype || r,
			o = "constructor";
		d.has(e, o) && !d.contains(t, o) && t.push(o);
		while (n--) o = O[n], o in e && e[o] !== s[o] && !d.contains(t, o) && t.push(o)
	}
	var e = this,
		t = e._,
		n = Array.prototype,
		r = Object.prototype,
		i = Function.prototype,
		s = n.push,
		o = n.slice,
		u = r.toString,
		a = r.hasOwnProperty,
		f = Array.isArray,
		l = Object.keys,
		c = i.bind,
		h = Object.create,
		p = function() {},
		d = function(e) {
			if (e instanceof d) return e;
			if (!(this instanceof d)) return new d(e);
			this._wrapped = e
		};
	typeof exports != "undefined" ? (typeof module != "undefined" && module.exports && (exports = module.exports = d), exports._ = d) : e._ = d, d.VERSION = "1.8.3";
	var v = function(e, t, n) {
			if (t === void 0) return e;
			switch (n == null ? 3 : n) {
			case 1:
				return function(n) {
					return e.call(t, n)
				};
			case 2:
				return function(n, r) {
					return e.call(t, n, r)
				};
			case 3:
				return function(n, r, i) {
					return e.call(t, n, r, i)
				};
			case 4:
				return function(n, r, i, s) {
					return e.call(t, n, r, i, s)
				}
			}
			return function() {
				return e.apply(t, arguments)
			}
		},
		m = function(e, t, n) {
			return e == null ? d.identity : d.isFunction(e) ? v(e, t, n) : d.isObject(e) ? d.matcher(e) : d.property(e)
		};
	d.iteratee = function(e, t) {
		return m(e, t, Infinity)
	};
	var g = function(e, t) {
			return function(n) {
				var r = arguments.length;
				if (r < 2 || n == null) return n;
				for (var i = 1; i < r; i++) {
					var s = arguments[i],
						o = e(s),
						u = o.length;
					for (var a = 0; a < u; a++) {
						var f = o[a];
						if (!t || n[f] === void 0) n[f] = s[f]
					}
				}
				return n
			}
		},
		y = function(e) {
			if (!d.isObject(e)) return {};
			if (h) return h(e);
			p.prototype = e;
			var t = new p;
			return p.prototype = null, t
		},
		b = function(e) {
			return function(t) {
				return t == null ? void 0 : t[e]
			}
		},
		w = Math.pow(2, 53) - 1,
		E = b("length"),
		S = function(e) {
			var t = E(e);
			return typeof t == "number" && t >= 0 && t <= w
		};
	d.each = d.forEach = function(e, t, n) {
		t = v(t, n);
		var r, i;
		if (S(e)) for (r = 0, i = e.length; r < i; r++) t(e[r], r, e);
		else {
			var s = d.keys(e);
			for (r = 0, i = s.length; r < i; r++) t(e[s[r]], s[r], e)
		}
		return e
	}, d.map = d.collect = function(e, t, n) {
		t = m(t, n);
		var r = !S(e) && d.keys(e),
			i = (r || e).length,
			s = Array(i);
		for (var o = 0; o < i; o++) {
			var u = r ? r[o] : o;
			s[o] = t(e[u], u, e)
		}
		return s
	}, d.reduce = d.foldl = d.inject = x(1), d.reduceRight = d.foldr = x(-1), d.find = d.detect = function(e, t, n) {
		var r;
		S(e) ? r = d.findIndex(e, t, n) : r = d.findKey(e, t, n);
		if (r !== void 0 && r !== -1) return e[r]
	}, d.filter = d.select = function(e, t, n) {
		var r = [];
		return t = m(t, n), d.each(e, function(e, n, i) {
			t(e, n, i) && r.push(e)
		}), r
	}, d.reject = function(e, t, n) {
		return d.filter(e, d.negate(m(t)), n)
	}, d.every = d.all = function(e, t, n) {
		t = m(t, n);
		var r = !S(e) && d.keys(e),
			i = (r || e).length;
		for (var s = 0; s < i; s++) {
			var o = r ? r[s] : s;
			if (!t(e[o], o, e)) return !1
		}
		return !0
	}, d.some = d.any = function(e, t, n) {
		t = m(t, n);
		var r = !S(e) && d.keys(e),
			i = (r || e).length;
		for (var s = 0; s < i; s++) {
			var o = r ? r[s] : s;
			if (t(e[o], o, e)) return !0
		}
		return !1
	}, d.contains = d.includes = d.include = function(e, t, n, r) {
		S(e) || (e = d.values(e));
		if (typeof n != "number" || r) n = 0;
		return d.indexOf(e, t, n) >= 0
	}, d.invoke = function(e, t) {
		var n = o.call(arguments, 2),
			r = d.isFunction(t);
		return d.map(e, function(e) {
			var i = r ? t : e[t];
			return i == null ? i : i.apply(e, n)
		})
	}, d.pluck = function(e, t) {
		return d.map(e, d.property(t))
	}, d.where = function(e, t) {
		return d.filter(e, d.matcher(t))
	}, d.findWhere = function(e, t) {
		return d.find(e, d.matcher(t))
	}, d.max = function(e, t, n) {
		var r = -Infinity,
			i = -Infinity,
			s, o;
		if (t == null && e != null) {
			e = S(e) ? e : d.values(e);
			for (var u = 0, a = e.length; u < a; u++) s = e[u], s > r && (r = s)
		} else t = m(t, n), d.each(e, function(e, n, s) {
			o = t(e, n, s);
			if (o > i || o === -Infinity && r === -Infinity) r = e, i = o
		});
		return r
	}, d.min = function(e, t, n) {
		var r = Infinity,
			i = Infinity,
			s, o;
		if (t == null && e != null) {
			e = S(e) ? e : d.values(e);
			for (var u = 0, a = e.length; u < a; u++) s = e[u], s < r && (r = s)
		} else t = m(t, n), d.each(e, function(e, n, s) {
			o = t(e, n, s);
			if (o < i || o === Infinity && r === Infinity) r = e, i = o
		});
		return r
	}, d.shuffle = function(e) {
		var t = S(e) ? e : d.values(e),
			n = t.length,
			r = Array(n);
		for (var i = 0, s; i < n; i++) s = d.random(0, i), s !== i && (r[i] = r[s]), r[s] = t[i];
		return r
	}, d.sample = function(e, t, n) {
		return t == null || n ? (S(e) || (e = d.values(e)), e[d.random(e.length - 1)]) : d.shuffle(e).slice(0, Math.max(0, t))
	}, d.sortBy = function(e, t, n) {
		return t = m(t, n), d.pluck(d.map(e, function(e, n, r) {
			return {
				value: e,
				index: n,
				criteria: t(e, n, r)
			}
		}).sort(function(e, t) {
			var n = e.criteria,
				r = t.criteria;
			if (n !== r) {
				if (n > r || n === void 0) return 1;
				if (n < r || r === void 0) return -1
			}
			return e.index - t.index
		}), "value")
	};
	var T = function(e) {
			return function(t, n, r) {
				var i = {};
				return n = m(n, r), d.each(t, function(r, s) {
					var o = n(r, s, t);
					e(i, r, o)
				}), i
			}
		};
	d.groupBy = T(function(e, t, n) {
		d.has(e, n) ? e[n].push(t) : e[n] = [t]
	}), d.indexBy = T(function(e, t, n) {
		e[n] = t
	}), d.countBy = T(function(e, t, n) {
		d.has(e, n) ? e[n]++ : e[n] = 1
	}), d.toArray = function(e) {
		return e ? d.isArray(e) ? o.call(e) : S(e) ? d.map(e, d.identity) : d.values(e) : []
	}, d.size = function(e) {
		return e == null ? 0 : S(e) ? e.length : d.keys(e).length
	}, d.partition = function(e, t, n) {
		t = m(t, n);
		var r = [],
			i = [];
		return d.each(e, function(e, n, s) {
			(t(e, n, s) ? r : i).push(e)
		}), [r, i]
	}, d.first = d.head = d.take = function(e, t, n) {
		return e == null ? void 0 : t == null || n ? e[0] : d.initial(e, e.length - t)
	}, d.initial = function(e, t, n) {
		return o.call(e, 0, Math.max(0, e.length - (t == null || n ? 1 : t)))
	}, d.last = function(e, t, n) {
		return e == null ? void 0 : t == null || n ? e[e.length - 1] : d.rest(e, Math.max(0, e.length - t))
	}, d.rest = d.tail = d.drop = function(e, t, n) {
		return o.call(e, t == null || n ? 1 : t)
	}, d.compact = function(e) {
		return d.filter(e, d.identity)
	};
	var N = function(e, t, n, r) {
			var i = [],
				s = 0;
			for (var o = r || 0, u = E(e); o < u; o++) {
				var a = e[o];
				if (S(a) && (d.isArray(a) || d.isArguments(a))) {
					t || (a = N(a, t, n));
					var f = 0,
						l = a.length;
					i.length += l;
					while (f < l) i[s++] = a[f++]
				} else n || (i[s++] = a)
			}
			return i
		};
	d.flatten = function(e, t) {
		return N(e, t, !1)
	}, d.without = function(e) {
		return d.difference(e, o.call(arguments, 1))
	}, d.uniq = d.unique = function(e, t, n, r) {
		d.isBoolean(t) || (r = n, n = t, t = !1), n != null && (n = m(n, r));
		var i = [],
			s = [];
		for (var o = 0, u = E(e); o < u; o++) {
			var a = e[o],
				f = n ? n(a, o, e) : a;
			t ? ((!o || s !== f) && i.push(a), s = f) : n ? d.contains(s, f) || (s.push(f), i.push(a)) : d.contains(i, a) || i.push(a)
		}
		return i
	}, d.union = function() {
		return d.uniq(N(arguments, !0, !0))
	}, d.intersection = function(e) {
		var t = [],
			n = arguments.length;
		for (var r = 0, i = E(e); r < i; r++) {
			var s = e[r];
			if (d.contains(t, s)) continue;
			for (var o = 1; o < n; o++) if (!d.contains(arguments[o], s)) break;
			o === n && t.push(s)
		}
		return t
	}, d.difference = function(e) {
		var t = N(arguments, !0, !0, 1);
		return d.filter(e, function(e) {
			return !d.contains(t, e)
		})
	}, d.zip = function() {
		return d.unzip(arguments)
	}, d.unzip = function(e) {
		var t = e && d.max(e, E).length || 0,
			n = Array(t);
		for (var r = 0; r < t; r++) n[r] = d.pluck(e, r);
		return n
	}, d.object = function(e, t) {
		var n = {};
		for (var r = 0, i = E(e); r < i; r++) t ? n[e[r]] = t[r] : n[e[r][0]] = e[r][1];
		return n
	}, d.findIndex = C(1), d.findLastIndex = C(-1), d.sortedIndex = function(e, t, n, r) {
		n = m(n, r, 1);
		var i = n(t),
			s = 0,
			o = E(e);
		while (s < o) {
			var u = Math.floor((s + o) / 2);
			n(e[u]) < i ? s = u + 1 : o = u
		}
		return s
	}, d.indexOf = k(1, d.findIndex, d.sortedIndex), d.lastIndexOf = k(-1, d.findLastIndex), d.range = function(e, t, n) {
		t == null && (t = e || 0, e = 0), n = n || 1;
		var r = Math.max(Math.ceil((t - e) / n), 0),
			i = Array(r);
		for (var s = 0; s < r; s++, e += n) i[s] = e;
		return i
	};
	var L = function(e, t, n, r, i) {
			if (r instanceof t) {
				var s = y(e.prototype),
					o = e.apply(s, i);
				return d.isObject(o) ? o : s
			}
			return e.apply(n, i)
		};
	d.bind = function(e, t) {
		if (c && e.bind === c) return c.apply(e, o.call(arguments, 1));
		if (!d.isFunction(e)) throw new TypeError("Bind must be called on a function");
		var n = o.call(arguments, 2),
			r = function() {
				return L(e, r, t, this, n.concat(o.call(arguments)))
			};
		return r
	}, d.partial = function(e) {
		var t = o.call(arguments, 1),
			n = function() {
				var r = 0,
					i = t.length,
					s = Array(i);
				for (var o = 0; o < i; o++) s[o] = t[o] === d ? arguments[r++] : t[o];
				while (r < arguments.length) s.push(arguments[r++]);
				return L(e, n, this, this, s)
			};
		return n
	}, d.bindAll = function(e) {
		var t, n = arguments.length,
			r;
		if (n <= 1) throw new Error("bindAll must be passed function names");
		for (t = 1; t < n; t++) r = arguments[t], e[r] = d.bind(e[r], e);
		return e
	}, d.memoize = function(e, t) {
		var n = function(r) {
				var i = n.cache,
					s = "" + (t ? t.apply(this, arguments) : r);
				return d.has(i, s) || (i[s] = e.apply(this, arguments)), i[s]
			};
		return n.cache = {}, n
	}, d.delay = function(e, t) {
		var n = o.call(arguments, 2);
		return setTimeout(function() {
			return e.apply(null, n)
		}, t)
	}, d.defer = d.partial(d.delay, d, 1), d.throttle = function(e, t, n) {
		var r, i, s, o = null,
			u = 0;
		n || (n = {});
		var a = function() {
				u = n.leading === !1 ? 0 : d.now(), o = null, s = e.apply(r, i), o || (r = i = null)
			};
		return function() {
			var f = d.now();
			!u && n.leading === !1 && (u = f);
			var l = t - (f - u);
			return r = this, i = arguments, l <= 0 || l > t ? (o && (clearTimeout(o), o = null), u = f, s = e.apply(r, i), o || (r = i = null)) : !o && n.trailing !== !1 && (o = setTimeout(a, l)), s
		}
	}, d.debounce = function(e, t, n) {
		var r, i, s, o, u, a = function() {
				var f = d.now() - o;
				f < t && f >= 0 ? r = setTimeout(a, t - f) : (r = null, n || (u = e.apply(s, i), r || (s = i = null)))
			};
		return function() {
			s = this, i = arguments, o = d.now();
			var f = n && !r;
			return r || (r = setTimeout(a, t)), f && (u = e.apply(s, i), s = i = null), u
		}
	}, d.wrap = function(e, t) {
		return d.partial(t, e)
	}, d.negate = function(e) {
		return function() {
			return !e.apply(this, arguments)
		}
	}, d.compose = function() {
		var e = arguments,
			t = e.length - 1;
		return function() {
			var n = t,
				r = e[t].apply(this, arguments);
			while (n--) r = e[n].call(this, r);
			return r
		}
	}, d.after = function(e, t) {
		return function() {
			if (--e < 1) return t.apply(this, arguments)
		}
	}, d.before = function(e, t) {
		var n;
		return function() {
			return --e > 0 && (n = t.apply(this, arguments)), e <= 1 && (t = null), n
		}
	}, d.once = d.partial(d.before, 2);
	var A = !{
		toString: null
	}.propertyIsEnumerable("toString"),
		O = ["valueOf", "isPrototypeOf", "toString", "propertyIsEnumerable", "hasOwnProperty", "toLocaleString"];
	d.keys = function(e) {
		if (!d.isObject(e)) return [];
		if (l) return l(e);
		var t = [];
		for (var n in e) d.has(e, n) && t.push(n);
		return A && M(e, t), t
	}, d.allKeys = function(e) {
		if (!d.isObject(e)) return [];
		var t = [];
		for (var n in e) t.push(n);
		return A && M(e, t), t
	}, d.values = function(e) {
		var t = d.keys(e),
			n = t.length,
			r = Array(n);
		for (var i = 0; i < n; i++) r[i] = e[t[i]];
		return r
	}, d.mapObject = function(e, t, n) {
		t = m(t, n);
		var r = d.keys(e),
			i = r.length,
			s = {},
			o;
		for (var u = 0; u < i; u++) o = r[u], s[o] = t(e[o], o, e);
		return s
	}, d.pairs = function(e) {
		var t = d.keys(e),
			n = t.length,
			r = Array(n);
		for (var i = 0; i < n; i++) r[i] = [t[i], e[t[i]]];
		return r
	}, d.invert = function(e) {
		var t = {},
			n = d.keys(e);
		for (var r = 0, i = n.length; r < i; r++) t[e[n[r]]] = n[r];
		return t
	}, d.functions = d.methods = function(e) {
		var t = [];
		for (var n in e) d.isFunction(e[n]) && t.push(n);
		return t.sort()
	}, d.extend = g(d.allKeys), d.extendOwn = d.assign = g(d.keys), d.findKey = function(e, t, n) {
		t = m(t, n);
		var r = d.keys(e),
			i;
		for (var s = 0, o = r.length; s < o; s++) {
			i = r[s];
			if (t(e[i], i, e)) return i
		}
	}, d.pick = function(e, t, n) {
		var r = {},
			i = e,
			s, o;
		if (i == null) return r;
		d.isFunction(t) ? (o = d.allKeys(i), s = v(t, n)) : (o = N(arguments, !1, !1, 1), s = function(e, t, n) {
			return t in n
		}, i = Object(i));
		for (var u = 0, a = o.length; u < a; u++) {
			var f = o[u],
				l = i[f];
			s(l, f, i) && (r[f] = l)
		}
		return r
	}, d.omit = function(e, t, n) {
		if (d.isFunction(t)) t = d.negate(t);
		else {
			var r = d.map(N(arguments, !1, !1, 1), String);
			t = function(e, t) {
				return !d.contains(r, t)
			}
		}
		return d.pick(e, t, n)
	}, d.defaults = g(d.allKeys, !0), d.create = function(e, t) {
		var n = y(e);
		return t && d.extendOwn(n, t), n
	}, d.clone = function(e) {
		return d.isObject(e) ? d.isArray(e) ? e.slice() : d.extend({}, e) : e
	}, d.tap = function(e, t) {
		return t(e), e
	}, d.isMatch = function(e, t) {
		var n = d.keys(t),
			r = n.length;
		if (e == null) return !r;
		var i = Object(e);
		for (var s = 0; s < r; s++) {
			var o = n[s];
			if (t[o] !== i[o] || !(o in i)) return !1
		}
		return !0
	};
	var _ = function(e, t, n, r) {
			if (e === t) return e !== 0 || 1 / e === 1 / t;
			if (e == null || t == null) return e === t;
			e instanceof d && (e = e._wrapped), t instanceof d && (t = t._wrapped);
			var i = u.call(e);
			if (i !== u.call(t)) return !1;
			switch (i) {
			case "[object RegExp]":
			case "[object String]":
				return "" + e == "" + t;
			case "[object Number]":
				if (+e !== +e) return +t !== +t;
				return +e === 0 ? 1 / +e === 1 / t : +e === +t;
			case "[object Date]":
			case "[object Boolean]":
				return +e === +t
			}
			var s = i === "[object Array]";
			if (!s) {
				if (typeof e != "object" || typeof t != "object") return !1;
				var o = e.constructor,
					a = t.constructor;
				if (o !== a && !(d.isFunction(o) && o instanceof o && d.isFunction(a) && a instanceof a) && "constructor" in e && "constructor" in t) return !1
			}
			n = n || [], r = r || [];
			var f = n.length;
			while (f--) if (n[f] === e) return r[f] === t;
			n.push(e), r.push(t);
			if (s) {
				f = e.length;
				if (f !== t.length) return !1;
				while (f--) if (!_(e[f], t[f], n, r)) return !1
			} else {
				var l = d.keys(e),
					c;
				f = l.length;
				if (d.keys(t).length !== f) return !1;
				while (f--) {
					c = l[f];
					if (!d.has(t, c) || !_(e[c], t[c], n, r)) return !1
				}
			}
			return n.pop(), r.pop(), !0
		};
	d.isEqual = function(e, t) {
		return _(e, t)
	}, d.isEmpty = function(e) {
		return e == null ? !0 : S(e) && (d.isArray(e) || d.isString(e) || d.isArguments(e)) ? e.length === 0 : d.keys(e).length === 0
	}, d.isElement = function(e) {
		return !!e && e.nodeType === 1
	}, d.isArray = f ||
	function(e) {
		return u.call(e) === "[object Array]"
	}, d.isObject = function(e) {
		var t = typeof e;
		return t === "function" || t === "object" && !! e
	}, d.each(["Arguments", "Function", "String", "Number", "Date", "RegExp", "Error"], function(e) {
		d["is" + e] = function(t) {
			return u.call(t) === "[object " + e + "]"
		}
	}), d.isArguments(arguments) || (d.isArguments = function(e) {
		return d.has(e, "callee")
	}), typeof / . / != "function" && typeof Int8Array != "object" && (d.isFunction = function(e) {
		return typeof e == "function" || !1
	}), d.isFinite = function(e) {
		return isFinite(e) && !isNaN(parseFloat(e))
	}, d.isNaN = function(e) {
		return d.isNumber(e) && e !== +e
	}, d.isBoolean = function(e) {
		return e === !0 || e === !1 || u.call(e) === "[object Boolean]"
	}, d.isNull = function(e) {
		return e === null
	}, d.isUndefined = function(e) {
		return e === void 0
	}, d.has = function(e, t) {
		return e != null && a.call(e, t)
	}, d.noConflict = function() {
		return e._ = t, this
	}, d.identity = function(e) {
		return e
	}, d.constant = function(e) {
		return function() {
			return e
		}
	}, d.noop = function() {}, d.property = b, d.propertyOf = function(e) {
		return e == null ?
		function() {} : function(t) {
			return e[t]
		}
	}, d.matcher = d.matches = function(e) {
		return e = d.extendOwn({}, e), function(t) {
			return d.isMatch(t, e)
		}
	}, d.times = function(e, t, n) {
		var r = Array(Math.max(0, e));
		t = v(t, n, 1);
		for (var i = 0; i < e; i++) r[i] = t(i);
		return r
	}, d.random = function(e, t) {
		return t == null && (t = e, e = 0), e + Math.floor(Math.random() * (t - e + 1))
	}, d.now = Date.now ||
	function() {
		return (new Date).getTime()
	};
	var D = {
		"&": "&amp;",
		"<": "&lt;",
		">": "&gt;",
		'"': "&quot;",
		"'": "&#x27;",
		"`": "&#x60;"
	},
		P = d.invert(D),
		H = function(e) {
			var t = function(t) {
					return e[t]
				},
				n = "(?:" + d.keys(e).join("|") + ")",
				r = RegExp(n),
				i = RegExp(n, "g");
			return function(e) {
				return e = e == null ? "" : "" + e, r.test(e) ? e.replace(i, t) : e
			}
		};
	d.escape = H(D), d.unescape = H(P), d.result = function(e, t, n) {
		var r = e == null ? void 0 : e[t];
		return r === void 0 && (r = n), d.isFunction(r) ? r.call(e) : r
	};
	var B = 0;
	d.uniqueId = function(e) {
		var t = ++B + "";
		return e ? e + t : t
	}, d.templateSettings = {
		evaluate: /<%([\s\S]+?)%>/g,
		interpolate: /<%=([\s\S]+?)%>/g,
		escape: /<%-([\s\S]+?)%>/g
	};
	var j = /(.)^/,
		F = {
			"'": "'",
			"\\": "\\",
			"\r": "r",
			"\n": "n",
			"\u2028": "u2028",
			"\u2029": "u2029"
		},
		I = /\\|'|\r|\n|\u2028|\u2029/g,
		q = function(e) {
			return "\\" + F[e]
		};
	d.template = function(e, t, n) {
		!t && n && (t = n), t = d.defaults({}, t, d.templateSettings);
		var r = RegExp([(t.escape || j).source, (t.interpolate || j).source, (t.evaluate || j).source].join("|") + "|$", "g"),
			i = 0,
			s = "__p+='";
		e.replace(r, function(t, n, r, o, u) {
			return s += e.slice(i, u).replace(I, q), i = u + t.length, n ? s += "'+\n((__t=(" + n + "))==null?'':_.escape(__t))+\n'" : r ? s += "'+\n((__t=(" + r + "))==null?'':__t)+\n'" : o && (s += "';\n" + o + "\n__p+='"), t
		}), s += "';\n", t.variable || (s = "with(obj||{}){\n" + s + "}\n"), s = "var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};\n" + s + "return __p;\n";
		try {
			var o = new Function(t.variable || "obj", "_", s)
		} catch (u) {
			throw u.source = s, u
		}
		var a = function(e) {
				return o.call(this, e, d)
			},
			f = t.variable || "obj";
		return a.source = "function(" + f + "){\n" + s + "}", a
	}, d.chain = function(e) {
		var t = d(e);
		return t._chain = !0, t
	};
	var R = function(e, t) {
			return e._chain ? d(t).chain() : t
		};
	d.mixin = function(e) {
		d.each(d.functions(e), function(t) {
			var n = d[t] = e[t];
			d.prototype[t] = function() {
				var e = [this._wrapped];
				return s.apply(e, arguments), R(this, n.apply(d, e))
			}
		})
	}, d.mixin(d), d.each(["pop", "push", "reverse", "shift", "sort", "splice", "unshift"], function(e) {
		var t = n[e];
		d.prototype[e] = function() {
			var n = this._wrapped;
			return t.apply(n, arguments), (e === "shift" || e === "splice") && n.length === 0 && delete n[0], R(this, n)
		}
	}), d.each(["concat", "join", "slice"], function(e) {
		var t = n[e];
		d.prototype[e] = function() {
			return R(this, t.apply(this._wrapped, arguments))
		}
	}), d.prototype.value = function() {
		return this._wrapped
	}, d.prototype.valueOf = d.prototype.toJSON = d.prototype.value, d.prototype.toString = function() {
		return "" + this._wrapped
	}, typeof define == "function" && define.amd && define("underscore", [], function() {
		return d
	})
}.call(this), window.Modernizr = function(e, t, n) {
	function A(e) {
		f.cssText = e
	}
	function O(e, t) {
		return A(p.join(e + ";") + (t || ""))
	}
	function M(e, t) {
		return typeof e === t
	}
	function _(e, t) {
		return !!~ ("" + e).indexOf(t)
	}
	function D(e, t) {
		for (var r in e) {
			var i = e[r];
			if (!_(i, "-") && f[i] !== n) return t == "pfx" ? i : !0
		}
		return !1
	}
	function P(e, t, r) {
		for (var i in e) {
			var s = t[e[i]];
			if (s !== n) return r === !1 ? e[i] : M(s, "function") ? s.bind(r || t) : s
		}
		return !1
	}
	function H(e, t, n) {
		var r = e.charAt(0).toUpperCase() + e.slice(1),
			i = (e + " " + v.join(r + " ") + r).split(" ");
		return M(t, "string") || M(t, "undefined") ? D(i, t) : (i = (e + " " + m.join(r + " ") + r).split(" "), P(i, t, n))
	}
	function B() {
		i.input = function(n) {
			for (var r = 0, i = n.length; r < i; r++) w[n[r]] = n[r] in l;
			return w.list && (w.list = !! t.createElement("datalist") && !! e.HTMLDataListElement), w
		}("autocomplete autofocus list placeholder max min multiple pattern required step".split(" ")), i.inputtypes = function(e) {
			for (var r = 0, i, s, u, a = e.length; r < a; r++) l.setAttribute("type", s = e[r]), i = l.type !== "text", i && (l.value = c, l.style.cssText = "position:absolute;visibility:hidden;", /^range$/.test(s) && l.style.WebkitAppearance !== n ? (o.appendChild(l), u = t.defaultView, i = u.getComputedStyle && u.getComputedStyle(l, null).WebkitAppearance !== "textfield" && l.offsetHeight !== 0, o.removeChild(l)) : /^(search|tel)$/.test(s) || (/^(url|email)$/.test(s) ? i = l.checkValidity && l.checkValidity() === !1 : i = l.value != c)), b[e[r]] = !! i;
			return b
		}("search tel url email datetime date month week time datetime-local number range color".split(" "))
	}
	var r = "2.8.3",
		i = {},
		s = !0,
		o = t.documentElement,
		u = "modernizr",
		a = t.createElement(u),
		f = a.style,
		l = t.createElement("input"),
		c = ":)",
		h = {}.toString,
		p = " -webkit- -moz- -o- -ms- ".split(" "),
		d = "Webkit Moz O ms",
		v = d.split(" "),
		m = d.toLowerCase().split(" "),
		g = {
			svg: "http://www.w3.org/2000/svg"
		},
		y = {},
		b = {},
		w = {},
		E = [],
		S = E.slice,
		x, T = function(e, n, r, i) {
			var s, a, f, l, c = t.createElement("div"),
				h = t.body,
				p = h || t.createElement("body");
			if (parseInt(r, 10)) while (r--) f = t.createElement("div"), f.id = i ? i[r] : u + (r + 1), c.appendChild(f);
			return s = ["&#173;", '<style id="s', u, '">', e, "</style>"].join(""), c.id = u, (h ? c : p).innerHTML += s, p.appendChild(c), h || (p.style.background = "", p.style.overflow = "hidden", l = o.style.overflow, o.style.overflow = "hidden", o.appendChild(p)), a = n(c, e), h ? c.parentNode.removeChild(c) : (p.parentNode.removeChild(p), o.style.overflow = l), !! a
		},
		N = function(t) {
			var n = e.matchMedia || e.msMatchMedia;
			if (n) return n(t) && n(t).matches || !1;
			var r;
			return T("@media " + t + " { #" + u + " { position: absolute; } }", function(t) {
				r = (e.getComputedStyle ? getComputedStyle(t, null) : t.currentStyle)["position"] == "absolute"
			}), r
		},
		C = function() {
			function r(r, i) {
				i = i || t.createElement(e[r] || "div"), r = "on" + r;
				var s = r in i;
				return s || (i.setAttribute || (i = t.createElement("div")), i.setAttribute && i.removeAttribute && (i.setAttribute(r, ""), s = M(i[r], "function"), M(i[r], "undefined") || (i[r] = n), i.removeAttribute(r))), i = null, s
			}
			var e = {
				select: "input",
				change: "input",
				submit: "form",
				reset: "form",
				error: "img",
				load: "img",
				abort: "img"
			};
			return r
		}(),
		k = {}.hasOwnProperty,
		L;
	!M(k, "undefined") && !M(k.call, "undefined") ? L = function(e, t) {
		return k.call(e, t)
	} : L = function(e, t) {
		return t in e && M(e.constructor.prototype[t], "undefined")
	}, Function.prototype.bind || (Function.prototype.bind = function(t) {
		var n = this;
		if (typeof n != "function") throw new TypeError;
		var r = S.call(arguments, 1),
			i = function() {
				if (this instanceof i) {
					var e = function() {};
					e.prototype = n.prototype;
					var s = new e,
						o = n.apply(s, r.concat(S.call(arguments)));
					return Object(o) === o ? o : s
				}
				return n.apply(t, r.concat(S.call(arguments)))
			};
		return i
	}), y.flexbox = function() {
		return H("flexWrap")
	}, y.flexboxlegacy = function() {
		return H("boxDirection")
	}, y.canvas = function() {
		var e = t.createElement("canvas");
		return !!e.getContext && !! e.getContext("2d")
	}, y.canvastext = function() {
		return !!i.canvas && !! M(t.createElement("canvas").getContext("2d").fillText, "function")
	}, y.webgl = function() {
		return !!e.WebGLRenderingContext
	}, y.touch = function() {
		var n;
		return "ontouchstart" in e || e.DocumentTouch && t instanceof DocumentTouch ? n = !0 : T(["@media (", p.join("touch-enabled),("), u, ")", "{#modernizr{top:9px;position:absolute}}"].join(""), function(e) {
			n = e.offsetTop === 9
		}), n
	}, y.geolocation = function() {
		return "geolocation" in navigator
	}, y.postmessage = function() {
		return !!e.postMessage
	}, y.websqldatabase = function() {
		return !!e.openDatabase
	}, y.indexedDB = function() {
		return !!H("indexedDB", e)
	}, y.hashchange = function() {
		return C("hashchange", e) && (t.documentMode === n || t.documentMode > 7)
	}, y.history = function() {
		return !!e.history && !! history.pushState
	}, y.draganddrop = function() {
		var e = t.createElement("div");
		return "draggable" in e || "ondragstart" in e && "ondrop" in e
	}, y.websockets = function() {
		return "WebSocket" in e || "MozWebSocket" in e
	}, y.rgba = function() {
		return A("background-color:rgba(150,255,150,.5)"), _(f.backgroundColor, "rgba")
	}, y.hsla = function() {
		return A("background-color:hsla(120,40%,100%,.5)"), _(f.backgroundColor, "rgba") || _(f.backgroundColor, "hsla")
	}, y.multiplebgs = function() {
		return A("background:url(https://),url(https://),red url(https://)"), /(url\s*\(.*?){3}/.test(f.background)
	}, y.backgroundsize = function() {
		return H("backgroundSize")
	}, y.borderimage = function() {
		return H("borderImage")
	}, y.borderradius = function() {
		return H("borderRadius")
	}, y.boxshadow = function() {
		return H("boxShadow")
	}, y.textshadow = function() {
		return t.createElement("div").style.textShadow === ""
	}, y.opacity = function() {
		return O("opacity:.55"), /^0.55$/.test(f.opacity)
	}, y.cssanimations = function() {
		return H("animationName")
	}, y.csscolumns = function() {
		return H("columnCount")
	}, y.cssgradients = function() {
		var e = "background-image:",
			t = "gradient(linear,left top,right bottom,from(#9f9),to(white));",
			n = "linear-gradient(left top,#9f9, white);";
		return A((e + "-webkit- ".split(" ").join(t + e) + p.join(n + e)).slice(0, -e.length)), _(f.backgroundImage, "gradient")
	}, y.cssreflections = function() {
		return H("boxReflect")
	}, y.csstransforms = function() {
		return !!H("transform")
	}, y.csstransforms3d = function() {
		var e = !! H("perspective");
		return e && "webkitPerspective" in o.style && T("@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}", function(t, n) {
			e = t.offsetLeft === 9 && t.offsetHeight === 3
		}), e
	}, y.csstransitions = function() {
		return H("transition")
	}, y.fontface = function() {
		var e;
		return T('@font-face {font-family:"font";src:url("https://")}', function(n, r) {
			var i = t.getElementById("smodernizr"),
				s = i.sheet || i.styleSheet,
				o = s ? s.cssRules && s.cssRules[0] ? s.cssRules[0].cssText : s.cssText || "" : "";
			e = /src/i.test(o) && o.indexOf(r.split(" ")[0]) === 0
		}), e
	}, y.generatedcontent = function() {
		var e;
		return T(["#", u, "{font:0/0 a}#", u, ':after{content:"', c, '";visibility:hidden;font:3px/1 a}'].join(""), function(t) {
			e = t.offsetHeight >= 3
		}), e
	}, y.video = function() {
		var e = t.createElement("video"),
			n = !1;
		try {
			if (n = !! e.canPlayType) n = new Boolean(n), n.ogg = e.canPlayType('video/ogg; codecs="theora"').replace(/^no$/, ""), n.h264 = e.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/, ""), n.webm = e.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/, "")
		} catch (r) {}
		return n
	}, y.audio = function() {
		var e = t.createElement("audio"),
			n = !1;
		try {
			if (n = !! e.canPlayType) n = new Boolean(n), n.ogg = e.canPlayType('audio/ogg; codecs="vorbis"').replace(/^no$/, ""), n.mp3 = e.canPlayType("audio/mpeg;").replace(/^no$/, ""), n.wav = e.canPlayType('audio/wav; codecs="1"').replace(/^no$/, ""), n.m4a = (e.canPlayType("audio/x-m4a;") || e.canPlayType("audio/aac;")).replace(/^no$/, "")
		} catch (r) {}
		return n
	}, y.localstorage = function() {
		try {
			return localStorage.setItem(u, u), localStorage.removeItem(u), !0
		} catch (e) {
			return !1
		}
	}, y.sessionstorage = function() {
		try {
			return sessionStorage.setItem(u, u), sessionStorage.removeItem(u), !0
		} catch (e) {
			return !1
		}
	}, y.webworkers = function() {
		return !!e.Worker
	}, y.applicationcache = function() {
		return !!e.applicationCache
	}, y.svg = function() {
		return !!t.createElementNS && !! t.createElementNS(g.svg, "svg").createSVGRect
	}, y.inlinesvg = function() {
		var e = t.createElement("div");
		return e.innerHTML = "<svg/>", (e.firstChild && e.firstChild.namespaceURI) == g.svg
	}, y.smil = function() {
		return !!t.createElementNS && /SVGAnimate/.test(h.call(t.createElementNS(g.svg, "animate")))
	}, y.svgclippaths = function() {
		return !!t.createElementNS && /SVGClipPath/.test(h.call(t.createElementNS(g.svg, "clipPath")))
	};
	for (var j in y) L(y, j) && (x = j.toLowerCase(), i[x] = y[j](), E.push((i[x] ? "" : "no-") + x));
	return i.input || B(), i.addTest = function(e, t) {
		if (typeof e == "object") for (var r in e) L(e, r) && i.addTest(r, e[r]);
		else {
			e = e.toLowerCase();
			if (i[e] !== n) return i;
			t = typeof t == "function" ? t() : t, typeof s != "undefined" && s && (o.className += " " + (t ? "" : "no-") + e), i[e] = t
		}
		return i
	}, A(""), a = l = null, function(e, t) {
		function c(e, t) {
			var n = e.createElement("p"),
				r = e.getElementsByTagName("head")[0] || e.documentElement;
			return n.innerHTML = "x<style>" + t + "</style>", r.insertBefore(n.lastChild, r.firstChild)
		}
		function h() {
			var e = y.elements;
			return typeof e == "string" ? e.split(" ") : e
		}
		function p(e) {
			var t = f[e[u]];
			return t || (t = {}, a++, e[u] = a, f[a] = t), t
		}
		function d(e, n, r) {
			n || (n = t);
			if (l) return n.createElement(e);
			r || (r = p(n));
			var o;
			return r.cache[e] ? o = r.cache[e].cloneNode() : s.test(e) ? o = (r.cache[e] = r.createElem(e)).cloneNode() : o = r.createElem(e), o.canHaveChildren && !i.test(e) && !o.tagUrn ? r.frag.appendChild(o) : o
		}
		function v(e, n) {
			e || (e = t);
			if (l) return e.createDocumentFragment();
			n = n || p(e);
			var r = n.frag.cloneNode(),
				i = 0,
				s = h(),
				o = s.length;
			for (; i < o; i++) r.createElement(s[i]);
			return r
		}
		function m(e, t) {
			t.cache || (t.cache = {}, t.createElem = e.createElement, t.createFrag = e.createDocumentFragment, t.frag = t.createFrag()), e.createElement = function(n) {
				return y.shivMethods ? d(n, e, t) : t.createElem(n)
			}, e.createDocumentFragment = Function("h,f", "return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&(" + h().join().replace(/[\w\-]+/g, function(e) {
				return t.createElem(e), t.frag.createElement(e), 'c("' + e + '")'
			}) + ");return n}")(y, t.frag)
		}
		function g(e) {
			e || (e = t);
			var n = p(e);
			return y.shivCSS && !o && !n.hasCSS && (n.hasCSS = !! c(e, "article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}mark{background:#FF0;color:#000}template{display:none}")), l || m(e, n), e
		}
		var n = "3.7.0",
			r = e.html5 || {},
			i = /^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,
			s = /^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,
			o, u = "_html5shiv",
			a = 0,
			f = {},
			l;
		(function() {
			try {
				var e = t.createElement("a");
				e.innerHTML = "<xyz></xyz>", o = "hidden" in e, l = e.childNodes.length == 1 ||
				function() {
					t.createElement("a");
					var e = t.createDocumentFragment();
					return typeof e.cloneNode == "undefined" || typeof e.createDocumentFragment == "undefined" || typeof e.createElement == "undefined"
				}()
			} catch (n) {
				o = !0, l = !0
			}
		})();
		var y = {
			elements: r.elements || "abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output progress section summary template time video",
			version: n,
			shivCSS: r.shivCSS !== !1,
			supportsUnknownElements: l,
			shivMethods: r.shivMethods !== !1,
			type: "default",
			shivDocument: g,
			createElement: d,
			createDocumentFragment: v
		};
		e.html5 = y, g(t)
	}(this, t), i._version = r, i._prefixes = p, i._domPrefixes = m, i._cssomPrefixes = v, i.mq = N, i.hasEvent = C, i.testProp = function(e) {
		return D([e])
	}, i.testAllProps = H, i.testStyles = T, i.prefixed = function(e, t, n) {
		return t ? H(e, t, n) : H(e, "pfx")
	}, o.className = o.className.replace(/(^|\s)no-js(\s|$)/, "$1$2") + (s ? " js " + E.join(" ") : ""), i
}(this, this.document), window.Detectizr = function(e, t, n, r) {
	function p(e, t) {
		var n, r, i;
		if (arguments.length > 2) for (n = 1, r = arguments.length; n < r; n += 1) p(e, arguments[n]);
		else for (i in t) t.hasOwnProperty(i) && (e[i] = t[i]);
		return e
	}
	function d(e) {
		return i.browser.userAgent.indexOf(e) > -1
	}
	function v(e) {
		return e.test(i.browser.userAgent)
	}
	function m(e) {
		return e.exec(i.browser.userAgent)
	}
	function g(e) {
		return e.replace(/^\s+|\s+$/g, "")
	}
	function y(e) {
		return e === null || e === r ? "" : String(e).replace(/((\s|\-|\.)+[a-z0-9])/g, function(e) {
			return e.toUpperCase().replace(/(\s|\-|\.)/g, "")
		})
	}
	function b(e, t) {
		var n = t || "",
			r = e.nodeType === 1 && (e.className ? (" " + e.className + " ").replace(f, " ") : "");
		if (r) {
			while (r.indexOf(" " + n + " ") >= 0) r = r.replace(" " + n + " ", " ");
			e.className = t ? g(r) : ""
		}
	}
	function w(e, t, n) {
		!e || (e = y(e), !t || (t = y(t), E(e + t, !0), !n || E(e + t + "_" + n, !0)))
	}
	function E(e, t) { !! e && !! s && (u.addAllFeaturesAsClass ? s.addTest(e, t) : (t = typeof t == "function" ? t() : t, t ? s.addTest(e, !0) : (delete s[e], b(l, e))))
	}
	function S(e, t) {
		e.version = t;
		var n = t.split(".");
		n.length > 0 ? (n = n.reverse(), e.major = n.pop(), n.length > 0 ? (e.minor = n.pop(), n.length > 0 ? (n = n.reverse(), e.patch = n.join(".")) : e.patch = "0") : e.minor = "0") : e.major = "0"
	}
	function x() {
		e.clearTimeout(c), c = e.setTimeout(function() {
			h = i.device.orientation, e.innerHeight > e.innerWidth ? i.device.orientation = "portrait" : i.device.orientation = "landscape", E(i.device.orientation, !0), h !== i.device.orientation && E(h, !1)
		}, 10)
	}
	function T(e) {
		var n = t.plugins,
			r, i, s, o, u;
		for (o = n.length - 1; o >= 0; o--) {
			r = n[o], i = r.name + r.description, s = 0;
			for (u = e.length; u >= 0; u--) i.indexOf(e[u]) !== -1 && (s += 1);
			if (s === e.length) return !0
		}
		return !1
	}
	function N(e) {
		var t;
		for (t = e.length - 1; t >= 0; t--) try {
			new ActiveXObject(e[t])
		} catch (n) {}
		return !1
	}
	function C(r) {
		var f, l, c, h, g, b, C;
		u = p({}, u, r || {});
		if (u.detectDevice) {
			i.device = {
				type: "",
				model: "",
				orientation: ""
			}, c = i.device, v(/googletv|smarttv|smart-tv|internet.tv|netcast|nettv|appletv|boxee|kylo|roku|dlnadoc|roku|pov_tv|hbbtv|ce\-html/) ? (c.type = o[0], c.model = "smartTv") : v(/xbox|playstation.3|wii/) ? (c.type = o[0], c.model = "gameConsole") : v(/ip(a|ro)d/) ? (c.type = o[1], c.model = "ipad") : v(/tablet/) && !v(/rx-34/) || v(/folio/) ? (c.type = o[1], c.model = String(m(/playbook/) || "")) : v(/linux/) && v(/android/) && !v(/fennec|mobi|htc.magic|htcX06ht|nexus.one|sc-02b|fone.945/) ? (c.type = o[1], c.model = "android") : v(/kindle/) || v(/mac.os/) && v(/silk/) ? (c.type = o[1], c.model = "kindle") : v(/gt-p10|sc-01c|shw-m180s|sgh-t849|sch-i800|shw-m180l|sph-p100|sgh-i987|zt180|htc(.flyer|\_flyer)|sprint.atp51|viewpad7|pandigital(sprnova|nova)|ideos.s7|dell.streak.7|advent.vega|a101it|a70bht|mid7015|next2|nook/) || v(/mb511/) && v(/rutem/) ? (c.type = o[1], c.model = "android") : v(/bb10/) ? (c.type = o[1], c.model = "blackberry") : (c.model = m(/iphone|ipod|android|blackberry|opera mini|opera mobi|skyfire|maemo|windows phone|palm|iemobile|symbian|symbianos|fennec|j2me/), c.model !== null ? (c.type = o[2], c.model = String(c.model)) : (c.model = "", v(/bolt|fennec|iris|maemo|minimo|mobi|mowser|netfront|novarra|prism|rx-34|skyfire|tear|xv6875|xv6975|google.wireless.transcoder/) ? c.type = o[2] : v(/opera/) && v(/windows.nt.5/) && v(/htc|xda|mini|vario|samsung\-gt\-i8000|samsung\-sgh\-i9/) ? c.type = o[2] : v(/windows.(nt|xp|me|9)/) && !v(/phone/) || v(/win(9|.9|nt)/) || v(/\(windows 8\)/) ? c.type = o[3] : v(/macintosh|powerpc/) && !v(/silk/) ? (c.type = o[3], c.model = "mac") : v(/linux/) && v(/x11/) ? c.type = o[3] : v(/solaris|sunos|bsd/) ? c.type = o[3] : v(/cros/) ? c.type = o[3] : v(/bot|crawler|spider|yahoo|ia_archiver|covario-ids|findlinks|dataparksearch|larbin|mediapartners-google|ng-search|snappy|teoma|jeeves|tineye/) && !v(/mobile/) ? (c.type = o[3], c.model = "crawler") : c.type = o[2]));
			for (f = 0, l = o.length; f < l; f += 1) E(o[f], c.type === o[f]);
			u.detectDeviceModel && E(y(c.model), !0)
		}
		u.detectScreen && (c.screen = {}, !! s && !! s.mq && (s.mq("only screen and (max-width: 240px)") ? (c.screen.size = "veryVerySmall", E("veryVerySmallScreen", !0)) : s.mq("only screen and (max-width: 320px)") ? (c.screen.size = "verySmall", E("verySmallScreen", !0)) : s.mq("only screen and (max-width: 480px)") && (c.screen.size = "small", E("smallScreen", !0)), (c.type === o[1] || c.type === o[2]) && s.mq("only screen and (-moz-min-device-pixel-ratio: 1.3), only screen and (-o-min-device-pixel-ratio: 2.6/2), only screen and (-webkit-min-device-pixel-ratio: 1.3), only screen  and (min-device-pixel-ratio: 1.3), only screen and (min-resolution: 1.3dppx)") && (c.screen.resolution = "high", E("highresolution", !0))), c.type === o[1] || c.type === o[2] ? (e.onresize = function(e) {
			x(e)
		}, x()) : (c.orientation = "landscape", E(c.orientation, !0))), u.detectOS && (i.os = {}, h = i.os, c.model !== "" && (c.model === "ipad" || c.model === "iphone" || c.model === "ipod" ? (h.name = "ios", S(h, (v(/os\s([\d_]+)/) ? RegExp.$1 : "").replace(/_/g, "."))) : c.model === "android" ? (h.name = "android", S(h, v(/android\s([\d\.]+)/) ? RegExp.$1 : "")) : c.model === "blackberry" ? (h.name = "blackberry", S(h, v(/version\/([^\s]+)/) ? RegExp.$1 : "")) : c.model === "playbook" && (h.name = "blackberry", S(h, v(/os ([^\s]+)/) ? RegExp.$1.replace(";", "") : ""))), h.name || (d("win") || d("16bit") ? (h.name = "windows", d("windows nt 6.3") ? S(h, "8.1") : d("windows nt 6.2") || v(/\(windows 8\)/) ? S(h, "8") : d("windows nt 6.1") ? S(h, "7") : d("windows nt 6.0") ? S(h, "vista") : d("windows nt 5.2") || d("windows nt 5.1") || d("windows xp") ? S(h, "xp") : d("windows nt 5.0") || d("windows 2000") ? S(h, "2k") : d("winnt") || d("windows nt") ? S(h, "nt") : d("win98") || d("windows 98") ? S(h, "98") : (d("win95") || d("windows 95")) && S(h, "95")) : d("mac") || d("darwin") ? (h.name = "mac os", d("68k") || d("68000") ? S(h, "68k") : d("ppc") || d("powerpc") ? S(h, "ppc") : d("os x") && S(h, (v(/os\sx\s([\d_]+)/) ? RegExp.$1 : "os x").replace(/_/g, "."))) : d("webtv") ? h.name = "webtv" : d("x11") || d("inux") ? h.name = "linux" : d("sunos") ? h.name = "sun" : d("irix") ? h.name = "irix" : d("freebsd") ? h.name = "freebsd" : d("bsd") && (h.name = "bsd")), !h.name || (E(h.name, !0), !h.major || (w(h.name, h.major), !h.minor || w(h.name, h.major, h.minor))), v(/\sx64|\sx86|\swin64|\swow64|\samd64/) ? h.addressRegisterSize = "64bit" : h.addressRegisterSize = "32bit", E(h.addressRegisterSize, !0)), u.detectBrowser && (g = i.browser, !v(/opera|webtv/) && (v(/msie\s([\d\w\.]+)/) || d("trident")) ? (g.engine = "trident", g.name = "ie", !e.addEventListener && n.documentMode && n.documentMode === 7 ? S(g, "8.compat") : v(/trident.*rv[ :](\d+)\./) ? S(g, RegExp.$1) : S(g, v(/trident\/4\.0/) ? "8" : RegExp.$1)) : d("firefox") ? (g.engine = "gecko", g.name = "firefox", S(g, v(/firefox\/([\d\w\.]+)/) ? RegExp.$1 : "")) : d("gecko/") ? g.engine = "gecko" : d("opera") ? (g.name = "opera", g.engine = "presto", S(g, v(/version\/([\d\.]+)/) ? RegExp.$1 : v(/opera(\s|\/)([\d\.]+)/) ? RegExp.$2 : "")) : d("konqueror") ? g.name = "konqueror" : d("chrome") ? (g.engine = "webkit", g.name = "chrome", S(g, v(/chrome\/([\d\.]+)/) ? RegExp.$1 : "")) : d("iron") ? (g.engine = "webkit", g.name = "iron") : d("crios") ? (g.name = "chrome", g.engine = "webkit", S(g, v(/crios\/([\d\.]+)/) ? RegExp.$1 : "")) : d("applewebkit/") ? (g.name = "safari", g.engine = "webkit", S(g, v(/version\/([\d\.]+)/) ? RegExp.$1 : "")) : d("mozilla/") && (g.engine = "gecko"), !g.name || (E(g.name, !0), !g.major || (w(g.name, g.major), !g.minor || w(g.name, g.major, g.minor))), E(g.engine, !0), g.language = t.userLanguage || t.language, E(g.language, !0));
		if (u.detectPlugins) {
			g.plugins = [];
			for (f = a.length - 1; f >= 0; f--) b = a[f], C = !1, e.ActiveXObject ? C = N(b.progIds) : t.plugins && (C = T(b.substrs)), C && (g.plugins.push(b.name), E(b.name, !0));
			t.javaEnabled() && (g.plugins.push("java"), E("java", !0))
		}
	}
	var i = {},
		s = e.Modernizr,
		o = ["tv", "tablet", "mobile", "desktop"],
		u = {
			addAllFeaturesAsClass: !1,
			detectDevice: !0,
			detectDeviceModel: !0,
			detectScreen: !0,
			detectOS: !0,
			detectBrowser: !0,
			detectPlugins: !0
		},
		a = [{
			name: "adobereader",
			substrs: ["Adobe", "Acrobat"],
			progIds: ["AcroPDF.PDF", "PDF.PDFCtrl.5"]
		}, {
			name: "flash",
			substrs: ["Shockwave Flash"],
			progIds: ["ShockwaveFlash.ShockwaveFlash.1"]
		}, {
			name: "wmplayer",
			substrs: ["Windows Media"],
			progIds: ["wmplayer.ocx"]
		}, {
			name: "silverlight",
			substrs: ["Silverlight"],
			progIds: ["AgControl.AgControl"]
		}, {
			name: "quicktime",
			substrs: ["QuickTime"],
			progIds: ["QuickTime.QuickTime"]
		}],
		f = /[\t\r\n]/g,
		l = n.documentElement,
		c, h;
	return i.detect = function(e) {
		return C(e)
	}, i.init = function() {
		i !== r && (i.browser = {
			userAgent: (t.userAgent || t.vendor || e.opera).toLowerCase()
		}, i.detect())
	}, i.init(), i
}(this, this.navigator, this.document), typeof JSON != "object" && (JSON = {}), function() {
	"use strict";

	function f(e) {
		return e < 10 ? "0" + e : e
	}
	function this_value() {
		return this.valueOf()
	}
	function quote(e) {
		return rx_escapable.lastIndex = 0, rx_escapable.test(e) ? '"' + e.replace(rx_escapable, function(e) {
			var t = meta[e];
			return typeof t == "string" ? t : "\\u" + ("0000" + e.charCodeAt(0).toString(16)).slice(-4)
		}) + '"' : '"' + e + '"'
	}
	function str(e, t) {
		var n, r, i, s, o = gap,
			u, a = t[e];
		a && typeof a == "object" && typeof a.toJSON == "function" && (a = a.toJSON(e)), typeof rep == "function" && (a = rep.call(t, e, a));
		switch (typeof a) {
		case "string":
			return quote(a);
		case "number":
			return isFinite(a) ? String(a) : "null";
		case "boolean":
		case "null":
			return String(a);
		case "object":
			if (!a) return "null";
			gap += indent, u = [];
			if (Object.prototype.toString.apply(a) === "[object Array]") {
				s = a.length;
				for (n = 0; n < s; n += 1) u[n] = str(n, a) || "null";
				return i = u.length === 0 ? "[]" : gap ? "[\n" + gap + u.join(",\n" + gap) + "\n" + o + "]" : "[" + u.join(",") + "]", gap = o, i
			}
			if (rep && typeof rep == "object") {
				s = rep.length;
				for (n = 0; n < s; n += 1) typeof rep[n] == "string" && (r = rep[n], i = str(r, a), i && u.push(quote(r) + (gap ? ": " : ":") + i))
			} else for (r in a) Object.prototype.hasOwnProperty.call(a, r) && (i = str(r, a), i && u.push(quote(r) + (gap ? ": " : ":") + i));
			return i = u.length === 0 ? "{}" : gap ? "{\n" + gap + u.join(",\n" + gap) + "\n" + o + "}" : "{" + u.join(",") + "}", gap = o, i
		}
	}
	var rx_one = /^[\],:{}\s]*$/,
		rx_two = /\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,
		rx_three = /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
		rx_four = /(?:^|:|,)(?:\s*\[)+/g,
		rx_escapable = /[\\\"\u0000-\u001f\u007f-\u009f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
		rx_dangerous = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
	typeof Date.prototype.toJSON != "function" && (Date.prototype.toJSON = function() {
		return isFinite(this.valueOf()) ? this.getUTCFullYear() + "-" + f(this.getUTCMonth() + 1) + "-" + f(this.getUTCDate()) + "T" + f(this.getUTCHours()) + ":" + f(this.getUTCMinutes()) + ":" + f(this.getUTCSeconds()) + "Z" : null
	}, Boolean.prototype.toJSON = this_value, Number.prototype.toJSON = this_value, String.prototype.toJSON = this_value);
	var gap, indent, meta, rep;
	typeof JSON.stringify != "function" && (meta = {
		"\b": "\\b",
		"	": "\\t",
		"\n": "\\n",
		"\f": "\\f",
		"\r": "\\r",
		'"': '\\"',
		"\\": "\\\\"
	}, JSON.stringify = function(e, t, n) {
		var r;
		gap = "", indent = "";
		if (typeof n == "number") for (r = 0; r < n; r += 1) indent += " ";
		else typeof n == "string" && (indent = n);
		rep = t;
		if (!t || typeof t == "function" || typeof t == "object" && typeof t.length == "number") return str("", {
			"": e
		});
		throw new Error("JSON.stringify")
	}), typeof JSON.parse != "function" && (JSON.parse = function(text, reviver) {
		function walk(e, t) {
			var n, r, i = e[t];
			if (i && typeof i == "object") for (n in i) Object.prototype.hasOwnProperty.call(i, n) && (r = walk(i, n), r !== undefined ? i[n] = r : delete i[n]);
			return reviver.call(e, t, i)
		}
		var j;
		text = String(text), rx_dangerous.lastIndex = 0, rx_dangerous.test(text) && (text = text.replace(rx_dangerous, function(e) {
			return "\\u" + ("0000" + e.charCodeAt(0).toString(16)).slice(-4)
		}));
		if (rx_one.test(text.replace(rx_two, "@").replace(rx_three, "]").replace(rx_four, ""))) return j = eval("(" + text + ")"), typeof reviver == "function" ? walk({
			"": j
		}, "") : j;
		throw new SyntaxError("JSON.parse")
	})
}(), window.url = function() {
	function e() {}
	function t(e) {
		return decodeURIComponent(e.replace(/\+/g, " "))
	}
	function n(e, t) {
		var n = e.charAt(0),
			r = t.split(n);
		return n === e ? r : (e = parseInt(e.substring(1), 10), r[0 > e ? r.length + e : e - 1])
	}
	function r(e, n) {
		for (var r = e.charAt(0), i = n.split("&"), s = [], o = {}, u = [], a = e.substring(1), f = 0, l = i.length; l > f; f++) if (s = i[f].match(/(.*?)=(.*)/), s || (s = [i[f], i[f], ""]), "" !== s[1].replace(/\s/g, "")) {
			if (s[2] = t(s[2] || ""), a === s[1]) return s[2];
			u = s[1].match(/(.*)\[([0-9]+)\]/), u ? (o[u[1]] = o[u[1]] || [], o[u[1]][u[2]] = s[2]) : o[s[1]] = s[2]
		}
		return r === e ? o : o[a]
	}
	return function(t, i) {
		var s, o = {};
		if ("tld?" === t) return e();
		if (i = i || window.location.toString(), !t) return i;
		if (t = t.toString(), s = i.match(/^mailto:([^\/].+)/)) o.protocol = "mailto", o.email = s[1];
		else {
			if ((s = i.match(/(.*?)\/#\!(.*)/)) && (i = s[1] + s[2]), (s = i.match(/(.*?)#(.*)/)) && (o.hash = s[2], i = s[1]), o.hash && t.match(/^#/)) return r(t, o.hash);
			if ((s = i.match(/(.*?)\?(.*)/)) && (o.query = s[2], i = s[1]), o.query && t.match(/^\?/)) return r(t, o.query);
			if ((s = i.match(/(.*?)\:?\/\/(.*)/)) && (o.protocol = s[1].toLowerCase(), i = s[2]), (s = i.match(/(.*?)(\/.*)/)) && (o.path = s[2], i = s[1]), o.path = (o.path || "").replace(/^([^\/])/, "/$1").replace(/\/$/, ""), t.match(/^[\-0-9]+$/) && (t = t.replace(/^([^\/])/, "/$1")), t.match(/^\//)) return n(t, o.path.substring(1));
			if (s = n("/-1", o.path.substring(1)), s && (s = s.match(/(.*?)\.(.*)/)) && (o.file = s[0], o.filename = s[1], o.fileext = s[2]), (s = i.match(/(.*)\:([0-9]+)$/)) && (o.port = s[2], i = s[1]), (s = i.match(/(.*?)@(.*)/)) && (o.auth = s[1], i = s[2]), o.auth && (s = o.auth.match(/(.*)\:(.*)/), o.user = s ? s[1] : o.auth, o.pass = s ? s[2] : void 0), o.hostname = i.toLowerCase(), "." === t.charAt(0)) return n(t, o.hostname);
			e() && (s = o.hostname.match(e()), s && (o.tld = s[3], o.domain = s[2] ? s[2] + "." + s[3] : void 0, o.sub = s[1] || void 0)), o.port = o.port || ("https" === o.protocol ? "443" : "80"), o.protocol = o.protocol || ("443" === o.port ? "https" : "http")
		}
		return t in o ? o[t] : "{}" === t ? o : void 0
	}
}(), "undefined" != typeof jQuery && jQuery.extend({
	url: function(e, t) {
		return window.url(e, t)
	}
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery"], e) : e(jQuery)
}(function(e) {
	function t(t, r) {
		var i, s, o, u = t.nodeName.toLowerCase();
		return "area" === u ? (i = t.parentNode, s = i.name, !t.href || !s || i.nodeName.toLowerCase() !== "map" ? !1 : (o = e("img[usemap='#" + s + "']")[0], !! o && n(o))) : (/^(input|select|textarea|button|object)$/.test(u) ? !t.disabled : "a" === u ? t.href || r : r) && n(t)
	}
	function n(t) {
		return e.expr.filters.visible(t) && !e(t).parents().addBack().filter(function() {
			return e.css(this, "visibility") === "hidden"
		}).length
	}
	e.ui = e.ui || {}, e.extend(e.ui, {
		version: "1.11.4",
		keyCode: {
			BACKSPACE: 8,
			COMMA: 188,
			DELETE: 46,
			DOWN: 40,
			END: 35,
			ENTER: 13,
			ESCAPE: 27,
			HOME: 36,
			LEFT: 37,
			PAGE_DOWN: 34,
			PAGE_UP: 33,
			PERIOD: 190,
			RIGHT: 39,
			SPACE: 32,
			TAB: 9,
			UP: 38
		}
	}), e.fn.extend({
		scrollParent: function(t) {
			var n = this.css("position"),
				r = n === "absolute",
				i = t ? /(auto|scroll|hidden)/ : /(auto|scroll)/,
				s = this.parents().filter(function() {
					var t = e(this);
					return r && t.css("position") === "static" ? !1 : i.test(t.css("overflow") + t.css("overflow-y") + t.css("overflow-x"))
				}).eq(0);
			return n === "fixed" || !s.length ? e(this[0].ownerDocument || document) : s
		},
		uniqueId: function() {
			var e = 0;
			return function() {
				return this.each(function() {
					this.id || (this.id = "ui-id-" + ++e)
				})
			}
		}(),
		removeUniqueId: function() {
			return this.each(function() {
				/^ui-id-\d+$/.test(this.id) && e(this).removeAttr("id")
			})
		}
	}), e.extend(e.expr[":"], {
		data: e.expr.createPseudo ? e.expr.createPseudo(function(t) {
			return function(n) {
				return !!e.data(n, t)
			}
		}) : function(t, n, r) {
			return !!e.data(t, r[3])
		},
		focusable: function(n) {
			return t(n, !isNaN(e.attr(n, "tabindex")))
		},
		tabbable: function(n) {
			var r = e.attr(n, "tabindex"),
				i = isNaN(r);
			return (i || r >= 0) && t(n, !i)
		}
	}), e("<a>").outerWidth(1).jquery || e.each(["Width", "Height"], function(t, n) {
		function o(t, n, i, s) {
			return e.each(r, function() {
				n -= parseFloat(e.css(t, "padding" + this)) || 0, i && (n -= parseFloat(e.css(t, "border" + this + "Width")) || 0), s && (n -= parseFloat(e.css(t, "margin" + this)) || 0)
			}), n
		}
		var r = n === "Width" ? ["Left", "Right"] : ["Top", "Bottom"],
			i = n.toLowerCase(),
			s = {
				innerWidth: e.fn.innerWidth,
				innerHeight: e.fn.innerHeight,
				outerWidth: e.fn.outerWidth,
				outerHeight: e.fn.outerHeight
			};
		e.fn["inner" + n] = function(t) {
			return t === undefined ? s["inner" + n].call(this) : this.each(function() {
				e(this).css(i, o(this, t) + "px")
			})
		}, e.fn["outer" + n] = function(t, r) {
			return typeof t != "number" ? s["outer" + n].call(this, t) : this.each(function() {
				e(this).css(i, o(this, t, !0, r) + "px")
			})
		}
	}), e.fn.addBack || (e.fn.addBack = function(e) {
		return this.add(e == null ? this.prevObject : this.prevObject.filter(e))
	}), e("<a>").data("a-b", "a").removeData("a-b").data("a-b") && (e.fn.removeData = function(t) {
		return function(n) {
			return arguments.length ? t.call(this, e.camelCase(n)) : t.call(this)
		}
	}(e.fn.removeData)), e.ui.ie = !! /msie [\w.]+/.exec(navigator.userAgent.toLowerCase()), e.fn.extend({
		focus: function(t) {
			return function(n, r) {
				return typeof n == "number" ? this.each(function() {
					var t = this;
					setTimeout(function() {
						e(t).focus(), r && r.call(t)
					}, n)
				}) : t.apply(this, arguments)
			}
		}(e.fn.focus),
		disableSelection: function() {
			var e = "onselectstart" in document.createElement("div") ? "selectstart" : "mousedown";
			return function() {
				return this.bind(e + ".ui-disableSelection", function(e) {
					e.preventDefault()
				})
			}
		}(),
		enableSelection: function() {
			return this.unbind(".ui-disableSelection")
		},
		zIndex: function(t) {
			if (t !== undefined) return this.css("zIndex", t);
			if (this.length) {
				var n = e(this[0]),
					r, i;
				while (n.length && n[0] !== document) {
					r = n.css("position");
					if (r === "absolute" || r === "relative" || r === "fixed") {
						i = parseInt(n.css("zIndex"), 10);
						if (!isNaN(i) && i !== 0) return i
					}
					n = n.parent()
				}
			}
			return 0
		}
	}), e.ui.plugin = {
		add: function(t, n, r) {
			var i, s = e.ui[t].prototype;
			for (i in r) s.plugins[i] = s.plugins[i] || [], s.plugins[i].push([n, r[i]])
		},
		call: function(e, t, n, r) {
			var i, s = e.plugins[t];
			if (!s) return;
			if (!r && (!e.element[0].parentNode || e.element[0].parentNode.nodeType === 11)) return;
			for (i = 0; i < s.length; i++) e.options[s[i][0]] && s[i][1].apply(e.element, n)
		}
	}
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery"], e) : e(jQuery)
}(function(e) {
	var t = 0,
		n = Array.prototype.slice;
	return e.cleanData = function(t) {
		return function(n) {
			var r, i, s;
			for (s = 0;
			(i = n[s]) != null; s++) try {
				r = e._data(i, "events"), r && r.remove && e(i).triggerHandler("remove")
			} catch (o) {}
			t(n)
		}
	}(e.cleanData), e.widget = function(t, n, r) {
		var i, s, o, u, a = {},
			f = t.split(".")[0];
		return t = t.split(".")[1], i = f + "-" + t, r || (r = n, n = e.Widget), e.expr[":"][i.toLowerCase()] = function(t) {
			return !!e.data(t, i)
		}, e[f] = e[f] || {}, s = e[f][t], o = e[f][t] = function(e, t) {
			if (!this._createWidget) return new o(e, t);
			arguments.length && this._createWidget(e, t)
		}, e.extend(o, s, {
			version: r.version,
			_proto: e.extend({}, r),
			_childConstructors: []
		}), u = new n, u.options = e.widget.extend({}, u.options), e.each(r, function(t, r) {
			if (!e.isFunction(r)) {
				a[t] = r;
				return
			}
			a[t] = function() {
				var e = function() {
						return n.prototype[t].apply(this, arguments)
					},
					i = function(e) {
						return n.prototype[t].apply(this, e)
					};
				return function() {
					var t = this._super,
						n = this._superApply,
						s;
					return this._super = e, this._superApply = i, s = r.apply(this, arguments), this._super = t, this._superApply = n, s
				}
			}()
		}), o.prototype = e.widget.extend(u, {
			widgetEventPrefix: s ? u.widgetEventPrefix || t : t
		}, a, {
			constructor: o,
			namespace: f,
			widgetName: t,
			widgetFullName: i
		}), s ? (e.each(s._childConstructors, function(t, n) {
			var r = n.prototype;
			e.widget(r.namespace + "." + r.widgetName, o, n._proto)
		}), delete s._childConstructors) : n._childConstructors.push(o), e.widget.bridge(t, o), o
	}, e.widget.extend = function(t) {
		var r = n.call(arguments, 1),
			i = 0,
			s = r.length,
			o, u;
		for (; i < s; i++) for (o in r[i]) u = r[i][o], r[i].hasOwnProperty(o) && u !== undefined && (e.isPlainObject(u) ? t[o] = e.isPlainObject(t[o]) ? e.widget.extend({}, t[o], u) : e.widget.extend({}, u) : t[o] = u);
		return t
	}, e.widget.bridge = function(t, r) {
		var i = r.prototype.widgetFullName || t;
		e.fn[t] = function(s) {
			var o = typeof s == "string",
				u = n.call(arguments, 1),
				a = this;
			return o ? this.each(function() {
				var n, r = e.data(this, i);
				if (s === "instance") return a = r, !1;
				if (!r) return e.error("cannot call methods on " + t + " prior to initialization; " + "attempted to call method '" + s + "'");
				if (!e.isFunction(r[s]) || s.charAt(0) === "_") return e.error("no such method '" + s + "' for " + t + " widget instance");
				n = r[s].apply(r, u);
				if (n !== r && n !== undefined) return a = n && n.jquery ? a.pushStack(n.get()) : n, !1
			}) : (u.length && (s = e.widget.extend.apply(null, [s].concat(u))), this.each(function() {
				var t = e.data(this, i);
				t ? (t.option(s || {}), t._init && t._init()) : e.data(this, i, new r(s, this))
			})), a
		}
	}, e.Widget = function() {}, e.Widget._childConstructors = [], e.Widget.prototype = {
		widgetName: "widget",
		widgetEventPrefix: "",
		defaultElement: "<div>",
		options: {
			disabled: !1,
			create: null
		},
		_createWidget: function(n, r) {
			r = e(r || this.defaultElement || this)[0], this.element = e(r), this.uuid = t++, this.eventNamespace = "." + this.widgetName + this.uuid, this.bindings = e(), this.hoverable = e(), this.focusable = e(), r !== this && (e.data(r, this.widgetFullName, this), this._on(!0, this.element, {
				remove: function(e) {
					e.target === r && this.destroy()
				}
			}), this.document = e(r.style ? r.ownerDocument : r.document || r), this.window = e(this.document[0].defaultView || this.document[0].parentWindow)), this.options = e.widget.extend({}, this.options, this._getCreateOptions(), n), this._create(), this._trigger("create", null, this._getCreateEventData()), this._init()
		},
		_getCreateOptions: e.noop,
		_getCreateEventData: e.noop,
		_create: e.noop,
		_init: e.noop,
		destroy: function() {
			this._destroy(), this.element.unbind(this.eventNamespace).removeData(this.widgetFullName).removeData(e.camelCase(this.widgetFullName)), this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName + "-disabled " + "ui-state-disabled"), this.bindings.unbind(this.eventNamespace), this.hoverable.removeClass("ui-state-hover"), this.focusable.removeClass("ui-state-focus")
		},
		_destroy: e.noop,
		widget: function() {
			return this.element
		},
		option: function(t, n) {
			var r = t,
				i, s, o;
			if (arguments.length === 0) return e.widget.extend({}, this.options);
			if (typeof t == "string") {
				r = {}, i = t.split("."), t = i.shift();
				if (i.length) {
					s = r[t] = e.widget.extend({}, this.options[t]);
					for (o = 0; o < i.length - 1; o++) s[i[o]] = s[i[o]] || {}, s = s[i[o]];
					t = i.pop();
					if (arguments.length === 1) return s[t] === undefined ? null : s[t];
					s[t] = n
				} else {
					if (arguments.length === 1) return this.options[t] === undefined ? null : this.options[t];
					r[t] = n
				}
			}
			return this._setOptions(r), this
		},
		_setOptions: function(e) {
			var t;
			for (t in e) this._setOption(t, e[t]);
			return this
		},
		_setOption: function(e, t) {
			return this.options[e] = t, e === "disabled" && (this.widget().toggleClass(this.widgetFullName + "-disabled", !! t), t && (this.hoverable.removeClass("ui-state-hover"), this.focusable.removeClass("ui-state-focus"))), this
		},
		enable: function() {
			return this._setOptions({
				disabled: !1
			})
		},
		disable: function() {
			return this._setOptions({
				disabled: !0
			})
		},
		_on: function(t, n, r) {
			var i, s = this;
			typeof t != "boolean" && (r = n, n = t, t = !1), r ? (n = i = e(n), this.bindings = this.bindings.add(n)) : (r = n, n = this.element, i = this.widget()), e.each(r, function(r, o) {
				function u() {
					if (!t && (s.options.disabled === !0 || e(this).hasClass("ui-state-disabled"))) return;
					return (typeof o == "string" ? s[o] : o).apply(s, arguments)
				}
				typeof o != "string" && (u.guid = o.guid = o.guid || u.guid || e.guid++);
				var a = r.match(/^([\w:-]*)\s*(.*)$/),
					f = a[1] + s.eventNamespace,
					l = a[2];
				l ? i.delegate(l, f, u) : n.bind(f, u)
			})
		},
		_off: function(t, n) {
			n = (n || "").split(" ").join(this.eventNamespace + " ") + this.eventNamespace, t.unbind(n).undelegate(n), this.bindings = e(this.bindings.not(t).get()), this.focusable = e(this.focusable.not(t).get()), this.hoverable = e(this.hoverable.not(t).get())
		},
		_delay: function(e, t) {
			function n() {
				return (typeof e == "string" ? r[e] : e).apply(r, arguments)
			}
			var r = this;
			return setTimeout(n, t || 0)
		},
		_hoverable: function(t) {
			this.hoverable = this.hoverable.add(t), this._on(t, {
				mouseenter: function(t) {
					e(t.currentTarget).addClass("ui-state-hover")
				},
				mouseleave: function(t) {
					e(t.currentTarget).removeClass("ui-state-hover")
				}
			})
		},
		_focusable: function(t) {
			this.focusable = this.focusable.add(t), this._on(t, {
				focusin: function(t) {
					e(t.currentTarget).addClass("ui-state-focus")
				},
				focusout: function(t) {
					e(t.currentTarget).removeClass("ui-state-focus")
				}
			})
		},
		_trigger: function(t, n, r) {
			var i, s, o = this.options[t];
			r = r || {}, n = e.Event(n), n.type = (t === this.widgetEventPrefix ? t : this.widgetEventPrefix + t).toLowerCase(), n.target = this.element[0], s = n.originalEvent;
			if (s) for (i in s) i in n || (n[i] = s[i]);
			return this.element.trigger(n, r), !(e.isFunction(o) && o.apply(this.element[0], [n].concat(r)) === !1 || n.isDefaultPrevented())
		}
	}, e.each({
		show: "fadeIn",
		hide: "fadeOut"
	}, function(t, n) {
		e.Widget.prototype["_" + t] = function(r, i, s) {
			typeof i == "string" && (i = {
				effect: i
			});
			var o, u = i ? i === !0 || typeof i == "number" ? n : i.effect || n : t;
			i = i || {}, typeof i == "number" && (i = {
				duration: i
			}), o = !e.isEmptyObject(i), i.complete = s, i.delay && r.delay(i.delay), o && e.effects && e.effects.effect[u] ? r[t](i) : u !== t && r[u] ? r[u](i.duration, i.easing, s) : r.queue(function(n) {
				e(this)[t](), s && s.call(r[0]), n()
			})
		}
	}), e.widget
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery", "./widget"], e) : e(jQuery)
}(function(e) {
	var t = !1;
	return e(document).mouseup(function() {
		t = !1
	}), e.widget("ui.mouse", {
		version: "1.11.4",
		options: {
			cancel: "input,textarea,button,select,option",
			distance: 1,
			delay: 0
		},
		_mouseInit: function() {
			var t = this;
			this.element.bind("mousedown." + this.widgetName, function(e) {
				return t._mouseDown(e)
			}).bind("click." + this.widgetName, function(n) {
				if (!0 === e.data(n.target, t.widgetName + ".preventClickEvent")) return e.removeData(n.target, t.widgetName + ".preventClickEvent"), n.stopImmediatePropagation(), !1
			}), this.started = !1
		},
		_mouseDestroy: function() {
			this.element.unbind("." + this.widgetName), this._mouseMoveDelegate && this.document.unbind("mousemove." + this.widgetName, this._mouseMoveDelegate).unbind("mouseup." + this.widgetName, this._mouseUpDelegate)
		},
		_mouseDown: function(n) {
			if (t) return;
			this._mouseMoved = !1, this._mouseStarted && this._mouseUp(n), this._mouseDownEvent = n;
			var r = this,
				i = n.which === 1,
				s = typeof this.options.cancel == "string" && n.target.nodeName ? e(n.target).closest(this.options.cancel).length : !1;
			if (!i || s || !this._mouseCapture(n)) return !0;
			this.mouseDelayMet = !this.options.delay, this.mouseDelayMet || (this._mouseDelayTimer = setTimeout(function() {
				r.mouseDelayMet = !0
			}, this.options.delay));
			if (this._mouseDistanceMet(n) && this._mouseDelayMet(n)) {
				this._mouseStarted = this._mouseStart(n) !== !1;
				if (!this._mouseStarted) return n.preventDefault(), !0
			}
			return !0 === e.data(n.target, this.widgetName + ".preventClickEvent") && e.removeData(n.target, this.widgetName + ".preventClickEvent"), this._mouseMoveDelegate = function(e) {
				return r._mouseMove(e)
			}, this._mouseUpDelegate = function(e) {
				return r._mouseUp(e)
			}, this.document.bind("mousemove." + this.widgetName, this._mouseMoveDelegate).bind("mouseup." + this.widgetName, this._mouseUpDelegate), n.preventDefault(), t = !0, !0
		},
		_mouseMove: function(t) {
			if (this._mouseMoved) {
				if (e.ui.ie && (!document.documentMode || document.documentMode < 9) && !t.button) return this._mouseUp(t);
				if (!t.which) return this._mouseUp(t)
			}
			if (t.which || t.button) this._mouseMoved = !0;
			return this._mouseStarted ? (this._mouseDrag(t), t.preventDefault()) : (this._mouseDistanceMet(t) && this._mouseDelayMet(t) && (this._mouseStarted = this._mouseStart(this._mouseDownEvent, t) !== !1, this._mouseStarted ? this._mouseDrag(t) : this._mouseUp(t)), !this._mouseStarted)
		},
		_mouseUp: function(n) {
			return this.document.unbind("mousemove." + this.widgetName, this._mouseMoveDelegate).unbind("mouseup." + this.widgetName, this._mouseUpDelegate), this._mouseStarted && (this._mouseStarted = !1, n.target === this._mouseDownEvent.target && e.data(n.target, this.widgetName + ".preventClickEvent", !0), this._mouseStop(n)), t = !1, !1
		},
		_mouseDistanceMet: function(e) {
			return Math.max(Math.abs(this._mouseDownEvent.pageX - e.pageX), Math.abs(this._mouseDownEvent.pageY - e.pageY)) >= this.options.distance
		},
		_mouseDelayMet: function() {
			return this.mouseDelayMet
		},
		_mouseStart: function() {},
		_mouseDrag: function() {},
		_mouseStop: function() {},
		_mouseCapture: function() {
			return !0
		}
	})
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery", "./core", "./mouse", "./widget"], e) : e(jQuery)
}(function(e) {
	return e.widget("ui.sortable", e.ui.mouse, {
		version: "1.11.4",
		widgetEventPrefix: "sort",
		ready: !1,
		options: {
			appendTo: "parent",
			axis: !1,
			connectWith: !1,
			containment: !1,
			cursor: "auto",
			cursorAt: !1,
			dropOnEmpty: !0,
			forcePlaceholderSize: !1,
			forceHelperSize: !1,
			grid: !1,
			handle: !1,
			helper: "original",
			items: "> *",
			opacity: !1,
			placeholder: !1,
			revert: !1,
			scroll: !0,
			scrollSensitivity: 20,
			scrollSpeed: 20,
			scope: "default",
			tolerance: "intersect",
			zIndex: 1e3,
			activate: null,
			beforeStop: null,
			change: null,
			deactivate: null,
			out: null,
			over: null,
			receive: null,
			remove: null,
			sort: null,
			start: null,
			stop: null,
			update: null
		},
		_isOverAxis: function(e, t, n) {
			return e >= t && e < t + n
		},
		_isFloating: function(e) {
			return /left|right/.test(e.css("float")) || /inline|table-cell/.test(e.css("display"))
		},
		_create: function() {
			this.containerCache = {}, this.element.addClass("ui-sortable"), this.refresh(), this.offset = this.element.offset(), this._mouseInit(), this._setHandleClassName(), this.ready = !0
		},
		_setOption: function(e, t) {
			this._super(e, t), e === "handle" && this._setHandleClassName()
		},
		_setHandleClassName: function() {
			this.element.find(".ui-sortable-handle").removeClass("ui-sortable-handle"), e.each(this.items, function() {
				(this.instance.options.handle ? this.item.find(this.instance.options.handle) : this.item).addClass("ui-sortable-handle")
			})
		},
		_destroy: function() {
			this.element.removeClass("ui-sortable ui-sortable-disabled").find(".ui-sortable-handle").removeClass("ui-sortable-handle"), this._mouseDestroy();
			for (var e = this.items.length - 1; e >= 0; e--) this.items[e].item.removeData(this.widgetName + "-item");
			return this
		},
		_mouseCapture: function(t, n) {
			var r = null,
				i = !1,
				s = this;
			if (this.reverting) return !1;
			if (this.options.disabled || this.options.type === "static") return !1;
			this._refreshItems(t), e(t.target).parents().each(function() {
				if (e.data(this, s.widgetName + "-item") === s) return r = e(this), !1
			}), e.data(t.target, s.widgetName + "-item") === s && (r = e(t.target));
			if (!r) return !1;
			if (this.options.handle && !n) {
				e(this.options.handle, r).find("*").addBack().each(function() {
					this === t.target && (i = !0)
				});
				if (!i) return !1
			}
			return this.currentItem = r, this._removeCurrentsFromItems(), !0
		},
		_mouseStart: function(t, n, r) {
			var i, s, o = this.options;
			this.currentContainer = this, this.refreshPositions(), this.helper = this._createHelper(t), this._cacheHelperProportions(), this._cacheMargins(), this.scrollParent = this.helper.scrollParent(), this.offset = this.currentItem.offset(), this.offset = {
				top: this.offset.top - this.margins.top,
				left: this.offset.left - this.margins.left
			}, e.extend(this.offset, {
				click: {
					left: t.pageX - this.offset.left,
					top: t.pageY - this.offset.top
				},
				parent: this._getParentOffset(),
				relative: this._getRelativeOffset()
			}), this.helper.css("position", "absolute"), this.cssPosition = this.helper.css("position"), this.originalPosition = this._generatePosition(t), this.originalPageX = t.pageX, this.originalPageY = t.pageY, o.cursorAt && this._adjustOffsetFromHelper(o.cursorAt), this.domPosition = {
				prev: this.currentItem.prev()[0],
				parent: this.currentItem.parent()[0]
			}, this.helper[0] !== this.currentItem[0] && this.currentItem.hide(), this._createPlaceholder(), o.containment && this._setContainment(), o.cursor && o.cursor !== "auto" && (s = this.document.find("body"), this.storedCursor = s.css("cursor"), s.css("cursor", o.cursor), this.storedStylesheet = e("<style>*{ cursor: " + o.cursor + " !important; }</style>").appendTo(s)), o.opacity && (this.helper.css("opacity") && (this._storedOpacity = this.helper.css("opacity")), this.helper.css("opacity", o.opacity)), o.zIndex && (this.helper.css("zIndex") && (this._storedZIndex = this.helper.css("zIndex")), this.helper.css("zIndex", o.zIndex)), this.scrollParent[0] !== this.document[0] && this.scrollParent[0].tagName !== "HTML" && (this.overflowOffset = this.scrollParent.offset()), this._trigger("start", t, this._uiHash()), this._preserveHelperProportions || this._cacheHelperProportions();
			if (!r) for (i = this.containers.length - 1; i >= 0; i--) this.containers[i]._trigger("activate", t, this._uiHash(this));
			return e.ui.ddmanager && (e.ui.ddmanager.current = this), e.ui.ddmanager && !o.dropBehaviour && e.ui.ddmanager.prepareOffsets(this, t), this.dragging = !0, this.helper.addClass("ui-sortable-helper"), this._mouseDrag(t), !0
		},
		_mouseDrag: function(t) {
			var n, r, i, s, o = this.options,
				u = !1;
			this.position = this._generatePosition(t), this.positionAbs = this._convertPositionTo("absolute"), this.lastPositionAbs || (this.lastPositionAbs = this.positionAbs), this.options.scroll && (this.scrollParent[0] !== this.document[0] && this.scrollParent[0].tagName !== "HTML" ? (this.overflowOffset.top + this.scrollParent[0].offsetHeight - t.pageY < o.scrollSensitivity ? this.scrollParent[0].scrollTop = u = this.scrollParent[0].scrollTop + o.scrollSpeed : t.pageY - this.overflowOffset.top < o.scrollSensitivity && (this.scrollParent[0].scrollTop = u = this.scrollParent[0].scrollTop - o.scrollSpeed), this.overflowOffset.left + this.scrollParent[0].offsetWidth - t.pageX < o.scrollSensitivity ? this.scrollParent[0].scrollLeft = u = this.scrollParent[0].scrollLeft + o.scrollSpeed : t.pageX - this.overflowOffset.left < o.scrollSensitivity && (this.scrollParent[0].scrollLeft = u = this.scrollParent[0].scrollLeft - o.scrollSpeed)) : (t.pageY - this.document.scrollTop() < o.scrollSensitivity ? u = this.document.scrollTop(this.document.scrollTop() - o.scrollSpeed) : this.window.height() - (t.pageY - this.document.scrollTop()) < o.scrollSensitivity && (u = this.document.scrollTop(this.document.scrollTop() + o.scrollSpeed)), t.pageX - this.document.scrollLeft() < o.scrollSensitivity ? u = this.document.scrollLeft(this.document.scrollLeft() - o.scrollSpeed) : this.window.width() - (t.pageX - this.document.scrollLeft()) < o.scrollSensitivity && (u = this.document.scrollLeft(this.document.scrollLeft() + o.scrollSpeed))), u !== !1 && e.ui.ddmanager && !o.dropBehaviour && e.ui.ddmanager.prepareOffsets(this, t)), this.positionAbs = this._convertPositionTo("absolute");
			if (!this.options.axis || this.options.axis !== "y") this.helper[0].style.left = this.position.left + "px";
			if (!this.options.axis || this.options.axis !== "x") this.helper[0].style.top = this.position.top + "px";
			for (n = this.items.length - 1; n >= 0; n--) {
				r = this.items[n], i = r.item[0], s = this._intersectsWithPointer(r);
				if (!s) continue;
				if (r.instance !== this.currentContainer) continue;
				if (i !== this.currentItem[0] && this.placeholder[s === 1 ? "next" : "prev"]()[0] !== i && !e.contains(this.placeholder[0], i) && (this.options.type === "semi-dynamic" ? !e.contains(this.element[0], i) : !0)) {
					this.direction = s === 1 ? "down" : "up";
					if (this.options.tolerance !== "pointer" && !this._intersectsWithSides(r)) break;
					this._rearrange(t, r), this._trigger("change", t, this._uiHash());
					break
				}
			}
			return this._contactContainers(t), e.ui.ddmanager && e.ui.ddmanager.drag(this, t), this._trigger("sort", t, this._uiHash()), this.lastPositionAbs = this.positionAbs, !1
		},
		_mouseStop: function(t, n) {
			if (!t) return;
			e.ui.ddmanager && !this.options.dropBehaviour && e.ui.ddmanager.drop(this, t);
			if (this.options.revert) {
				var r = this,
					i = this.placeholder.offset(),
					s = this.options.axis,
					o = {};
				if (!s || s === "x") o.left = i.left - this.offset.parent.left - this.margins.left + (this.offsetParent[0] === this.document[0].body ? 0 : this.offsetParent[0].scrollLeft);
				if (!s || s === "y") o.top = i.top - this.offset.parent.top - this.margins.top + (this.offsetParent[0] === this.document[0].body ? 0 : this.offsetParent[0].scrollTop);
				this.reverting = !0, e(this.helper).animate(o, parseInt(this.options.revert, 10) || 500, function() {
					r._clear(t)
				})
			} else this._clear(t, n);
			return !1
		},
		cancel: function() {
			if (this.dragging) {
				this._mouseUp({
					target: null
				}), this.options.helper === "original" ? this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper") : this.currentItem.show();
				for (var t = this.containers.length - 1; t >= 0; t--) this.containers[t]._trigger("deactivate", null, this._uiHash(this)), this.containers[t].containerCache.over && (this.containers[t]._trigger("out", null, this._uiHash(this)), this.containers[t].containerCache.over = 0)
			}
			return this.placeholder && (this.placeholder[0].parentNode && this.placeholder[0].parentNode.removeChild(this.placeholder[0]), this.options.helper !== "original" && this.helper && this.helper[0].parentNode && this.helper.remove(), e.extend(this, {
				helper: null,
				dragging: !1,
				reverting: !1,
				_noFinalSort: null
			}), this.domPosition.prev ? e(this.domPosition.prev).after(this.currentItem) : e(this.domPosition.parent).prepend(this.currentItem)), this
		},
		serialize: function(t) {
			var n = this._getItemsAsjQuery(t && t.connected),
				r = [];
			return t = t || {}, e(n).each(function() {
				var n = (e(t.item || this).attr(t.attribute || "id") || "").match(t.expression || /(.+)[\-=_](.+)/);
				n && r.push((t.key || n[1] + "[]") + "=" + (t.key && t.expression ? n[1] : n[2]))
			}), !r.length && t.key && r.push(t.key + "="), r.join("&")
		},
		toArray: function(t) {
			var n = this._getItemsAsjQuery(t && t.connected),
				r = [];
			return t = t || {}, n.each(function() {
				r.push(e(t.item || this).attr(t.attribute || "id") || "")
			}), r
		},
		_intersectsWith: function(e) {
			var t = this.positionAbs.left,
				n = t + this.helperProportions.width,
				r = this.positionAbs.top,
				i = r + this.helperProportions.height,
				s = e.left,
				o = s + e.width,
				u = e.top,
				a = u + e.height,
				f = this.offset.click.top,
				l = this.offset.click.left,
				c = this.options.axis === "x" || r + f > u && r + f < a,
				h = this.options.axis === "y" || t + l > s && t + l < o,
				p = c && h;
			return this.options.tolerance === "pointer" || this.options.forcePointerForContainers || this.options.tolerance !== "pointer" && this.helperProportions[this.floating ? "width" : "height"] > e[this.floating ? "width" : "height"] ? p : s < t + this.helperProportions.width / 2 && n - this.helperProportions.width / 2 < o && u < r + this.helperProportions.height / 2 && i - this.helperProportions.height / 2 < a
		},
		_intersectsWithPointer: function(e) {
			var t = this.options.axis === "x" || this._isOverAxis(this.positionAbs.top + this.offset.click.top, e.top, e.height),
				n = this.options.axis === "y" || this._isOverAxis(this.positionAbs.left + this.offset.click.left, e.left, e.width),
				r = t && n,
				i = this._getDragVerticalDirection(),
				s = this._getDragHorizontalDirection();
			return r ? this.floating ? s && s === "right" || i === "down" ? 2 : 1 : i && (i === "down" ? 2 : 1) : !1
		},
		_intersectsWithSides: function(e) {
			var t = this._isOverAxis(this.positionAbs.top + this.offset.click.top, e.top + e.height / 2, e.height),
				n = this._isOverAxis(this.positionAbs.left + this.offset.click.left, e.left + e.width / 2, e.width),
				r = this._getDragVerticalDirection(),
				i = this._getDragHorizontalDirection();
			return this.floating && i ? i === "right" && n || i === "left" && !n : r && (r === "down" && t || r === "up" && !t)
		},
		_getDragVerticalDirection: function() {
			var e = this.positionAbs.top - this.lastPositionAbs.top;
			return e !== 0 && (e > 0 ? "down" : "up")
		},
		_getDragHorizontalDirection: function() {
			var e = this.positionAbs.left - this.lastPositionAbs.left;
			return e !== 0 && (e > 0 ? "right" : "left")
		},
		refresh: function(e) {
			return this._refreshItems(e), this._setHandleClassName(), this.refreshPositions(), this
		},
		_connectWith: function() {
			var e = this.options;
			return e.connectWith.constructor === String ? [e.connectWith] : e.connectWith
		},
		_getItemsAsjQuery: function(t) {
			function f() {
				o.push(this)
			}
			var n, r, i, s, o = [],
				u = [],
				a = this._connectWith();
			if (a && t) for (n = a.length - 1; n >= 0; n--) {
				i = e(a[n], this.document[0]);
				for (r = i.length - 1; r >= 0; r--) s = e.data(i[r], this.widgetFullName), s && s !== this && !s.options.disabled && u.push([e.isFunction(s.options.items) ? s.options.items.call(s.element) : e(s.options.items, s.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), s])
			}
			u.push([e.isFunction(this.options.items) ? this.options.items.call(this.element, null, {
				options: this.options,
				item: this.currentItem
			}) : e(this.options.items, this.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), this]);
			for (n = u.length - 1; n >= 0; n--) u[n][0].each(f);
			return e(o)
		},
		_removeCurrentsFromItems: function() {
			var t = this.currentItem.find(":data(" + this.widgetName + "-item)");
			this.items = e.grep(this.items, function(e) {
				for (var n = 0; n < t.length; n++) if (t[n] === e.item[0]) return !1;
				return !0
			})
		},
		_refreshItems: function(t) {
			this.items = [], this.containers = [this];
			var n, r, i, s, o, u, a, f, l = this.items,
				c = [
					[e.isFunction(this.options.items) ? this.options.items.call(this.element[0], t, {
						item: this.currentItem
					}) : e(this.options.items, this.element), this]
				],
				h = this._connectWith();
			if (h && this.ready) for (n = h.length - 1; n >= 0; n--) {
				i = e(h[n], this.document[0]);
				for (r = i.length - 1; r >= 0; r--) s = e.data(i[r], this.widgetFullName), s && s !== this && !s.options.disabled && (c.push([e.isFunction(s.options.items) ? s.options.items.call(s.element[0], t, {
					item: this.currentItem
				}) : e(s.options.items, s.element), s]), this.containers.push(s))
			}
			for (n = c.length - 1; n >= 0; n--) {
				o = c[n][1], u = c[n][0];
				for (r = 0, f = u.length; r < f; r++) a = e(u[r]), a.data(this.widgetName + "-item", o), l.push({
					item: a,
					instance: o,
					width: 0,
					height: 0,
					left: 0,
					top: 0
				})
			}
		},
		refreshPositions: function(t) {
			this.floating = this.items.length ? this.options.axis === "x" || this._isFloating(this.items[0].item) : !1, this.offsetParent && this.helper && (this.offset.parent = this._getParentOffset());
			var n, r, i, s;
			for (n = this.items.length - 1; n >= 0; n--) {
				r = this.items[n];
				if (r.instance !== this.currentContainer && this.currentContainer && r.item[0] !== this.currentItem[0]) continue;
				i = this.options.toleranceElement ? e(this.options.toleranceElement, r.item) : r.item, t || (r.width = i.outerWidth(), r.height = i.outerHeight()), s = i.offset(), r.left = s.left, r.top = s.top
			}
			if (this.options.custom && this.options.custom.refreshContainers) this.options.custom.refreshContainers.call(this);
			else for (n = this.containers.length - 1; n >= 0; n--) s = this.containers[n].element.offset(), this.containers[n].containerCache.left = s.left, this.containers[n].containerCache.top = s.top, this.containers[n].containerCache.width = this.containers[n].element.outerWidth(), this.containers[n].containerCache.height = this.containers[n].element.outerHeight();
			return this
		},
		_createPlaceholder: function(t) {
			t = t || this;
			var n, r = t.options;
			if (!r.placeholder || r.placeholder.constructor === String) n = r.placeholder, r.placeholder = {
				element: function() {
					var r = t.currentItem[0].nodeName.toLowerCase(),
						i = e("<" + r + ">", t.document[0]).addClass(n || t.currentItem[0].className + " ui-sortable-placeholder").removeClass("ui-sortable-helper");
					return r === "tbody" ? t._createTrPlaceholder(t.currentItem.find("tr").eq(0), e("<tr>", t.document[0]).appendTo(i)) : r === "tr" ? t._createTrPlaceholder(t.currentItem, i) : r === "img" && i.attr("src", t.currentItem.attr("src")), n || i.css("visibility", "hidden"), i
				},
				update: function(e, i) {
					if (n && !r.forcePlaceholderSize) return;
					i.height() || i.height(t.currentItem.innerHeight() - parseInt(t.currentItem.css("paddingTop") || 0, 10) - parseInt(t.currentItem.css("paddingBottom") || 0, 10)), i.width() || i.width(t.currentItem.innerWidth() - parseInt(t.currentItem.css("paddingLeft") || 0, 10) - parseInt(t.currentItem.css("paddingRight") || 0, 10))
				}
			};
			t.placeholder = e(r.placeholder.element.call(t.element, t.currentItem)), t.currentItem.after(t.placeholder), r.placeholder.update(t, t.placeholder)
		},
		_createTrPlaceholder: function(t, n) {
			var r = this;
			t.children().each(function() {
				e("<td>&#160;</td>", r.document[0]).attr("colspan", e(this).attr("colspan") || 1).appendTo(n)
			})
		},
		_contactContainers: function(t) {
			var n, r, i, s, o, u, a, f, l, c, h = null,
				p = null;
			for (n = this.containers.length - 1; n >= 0; n--) {
				if (e.contains(this.currentItem[0], this.containers[n].element[0])) continue;
				if (this._intersectsWith(this.containers[n].containerCache)) {
					if (h && e.contains(this.containers[n].element[0], h.element[0])) continue;
					h = this.containers[n], p = n
				} else this.containers[n].containerCache.over && (this.containers[n]._trigger("out", t, this._uiHash(this)), this.containers[n].containerCache.over = 0)
			}
			if (!h) return;
			if (this.containers.length === 1) this.containers[p].containerCache.over || (this.containers[p]._trigger("over", t, this._uiHash(this)), this.containers[p].containerCache.over = 1);
			else {
				i = 1e4, s = null, l = h.floating || this._isFloating(this.currentItem), o = l ? "left" : "top", u = l ? "width" : "height", c = l ? "clientX" : "clientY";
				for (r = this.items.length - 1; r >= 0; r--) {
					if (!e.contains(this.containers[p].element[0], this.items[r].item[0])) continue;
					if (this.items[r].item[0] === this.currentItem[0]) continue;
					a = this.items[r].item.offset()[o], f = !1, t[c] - a > this.items[r][u] / 2 && (f = !0), Math.abs(t[c] - a) < i && (i = Math.abs(t[c] - a), s = this.items[r], this.direction = f ? "up" : "down")
				}
				if (!s && !this.options.dropOnEmpty) return;
				if (this.currentContainer === this.containers[p]) {
					this.currentContainer.containerCache.over || (this.containers[p]._trigger("over", t, this._uiHash()), this.currentContainer.containerCache.over = 1);
					return
				}
				s ? this._rearrange(t, s, null, !0) : this._rearrange(t, null, this.containers[p].element, !0), this._trigger("change", t, this._uiHash()), this.containers[p]._trigger("change", t, this._uiHash(this)), this.currentContainer = this.containers[p], this.options.placeholder.update(this.currentContainer, this.placeholder), this.containers[p]._trigger("over", t, this._uiHash(this)), this.containers[p].containerCache.over = 1
			}
		},
		_createHelper: function(t) {
			var n = this.options,
				r = e.isFunction(n.helper) ? e(n.helper.apply(this.element[0], [t, this.currentItem])) : n.helper === "clone" ? this.currentItem.clone() : this.currentItem;
			return r.parents("body").length || e(n.appendTo !== "parent" ? n.appendTo : this.currentItem[0].parentNode)[0].appendChild(r[0]), r[0] === this.currentItem[0] && (this._storedCSS = {
				width: this.currentItem[0].style.width,
				height: this.currentItem[0].style.height,
				position: this.currentItem.css("position"),
				top: this.currentItem.css("top"),
				left: this.currentItem.css("left")
			}), (!r[0].style.width || n.forceHelperSize) && r.width(this.currentItem.width()), (!r[0].style.height || n.forceHelperSize) && r.height(this.currentItem.height()), r
		},
		_adjustOffsetFromHelper: function(t) {
			typeof t == "string" && (t = t.split(" ")), e.isArray(t) && (t = {
				left: +t[0],
				top: +t[1] || 0
			}), "left" in t && (this.offset.click.left = t.left + this.margins.left), "right" in t && (this.offset.click.left = this.helperProportions.width - t.right + this.margins.left), "top" in t && (this.offset.click.top = t.top + this.margins.top), "bottom" in t && (this.offset.click.top = this.helperProportions.height - t.bottom + this.margins.top)
		},
		_getParentOffset: function() {
			this.offsetParent = this.helper.offsetParent();
			var t = this.offsetParent.offset();
			this.cssPosition === "absolute" && this.scrollParent[0] !== this.document[0] && e.contains(this.scrollParent[0], this.offsetParent[0]) && (t.left += this.scrollParent.scrollLeft(), t.top += this.scrollParent.scrollTop());
			if (this.offsetParent[0] === this.document[0].body || this.offsetParent[0].tagName && this.offsetParent[0].tagName.toLowerCase() === "html" && e.ui.ie) t = {
				top: 0,
				left: 0
			};
			return {
				top: t.top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0),
				left: t.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0)
			}
		},
		_getRelativeOffset: function() {
			if (this.cssPosition === "relative") {
				var e = this.currentItem.position();
				return {
					top: e.top - (parseInt(this.helper.css("top"), 10) || 0) + this.scrollParent.scrollTop(),
					left: e.left - (parseInt(this.helper.css("left"), 10) || 0) + this.scrollParent.scrollLeft()
				}
			}
			return {
				top: 0,
				left: 0
			}
		},
		_cacheMargins: function() {
			this.margins = {
				left: parseInt(this.currentItem.css("marginLeft"), 10) || 0,
				top: parseInt(this.currentItem.css("marginTop"), 10) || 0
			}
		},
		_cacheHelperProportions: function() {
			this.helperProportions = {
				width: this.helper.outerWidth(),
				height: this.helper.outerHeight()
			}
		},
		_setContainment: function() {
			var t, n, r, i = this.options;
			i.containment === "parent" && (i.containment = this.helper[0].parentNode);
			if (i.containment === "document" || i.containment === "window") this.containment = [0 - this.offset.relative.left - this.offset.parent.left, 0 - this.offset.relative.top - this.offset.parent.top, i.containment === "document" ? this.document.width() : this.window.width() - this.helperProportions.width - this.margins.left, (i.containment === "document" ? this.document.width() : this.window.height() || this.document[0].body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top];
			/^(document|window|parent)$/.test(i.containment) || (t = e(i.containment)[0], n = e(i.containment).offset(), r = e(t).css("overflow") !== "hidden", this.containment = [n.left + (parseInt(e(t).css("borderLeftWidth"), 10) || 0) + (parseInt(e(t).css("paddingLeft"), 10) || 0) - this.margins.left, n.top + (parseInt(e(t).css("borderTopWidth"), 10) || 0) + (parseInt(e(t).css("paddingTop"), 10) || 0) - this.margins.top, n.left + (r ? Math.max(t.scrollWidth, t.offsetWidth) : t.offsetWidth) - (parseInt(e(t).css("borderLeftWidth"), 10) || 0) - (parseInt(e(t).css("paddingRight"), 10) || 0) - this.helperProportions.width - this.margins.left, n.top + (r ? Math.max(t.scrollHeight, t.offsetHeight) : t.offsetHeight) - (parseInt(e(t).css("borderTopWidth"), 10) || 0) - (parseInt(e(t).css("paddingBottom"), 10) || 0) - this.helperProportions.height - this.margins.top])
		},
		_convertPositionTo: function(t, n) {
			n || (n = this.position);
			var r = t === "absolute" ? 1 : -1,
				i = this.cssPosition !== "absolute" || this.scrollParent[0] !== this.document[0] && !! e.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent,
				s = /(html|body)/i.test(i[0].tagName);
			return {
				top: n.top + this.offset.relative.top * r + this.offset.parent.top * r - (this.cssPosition === "fixed" ? -this.scrollParent.scrollTop() : s ? 0 : i.scrollTop()) * r,
				left: n.left + this.offset.relative.left * r + this.offset.parent.left * r - (this.cssPosition === "fixed" ? -this.scrollParent.scrollLeft() : s ? 0 : i.scrollLeft()) * r
			}
		},
		_generatePosition: function(t) {
			var n, r, i = this.options,
				s = t.pageX,
				o = t.pageY,
				u = this.cssPosition !== "absolute" || this.scrollParent[0] !== this.document[0] && !! e.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent,
				a = /(html|body)/i.test(u[0].tagName);
			return this.cssPosition === "relative" && (this.scrollParent[0] === this.document[0] || this.scrollParent[0] === this.offsetParent[0]) && (this.offset.relative = this._getRelativeOffset()), this.originalPosition && (this.containment && (t.pageX - this.offset.click.left < this.containment[0] && (s = this.containment[0] + this.offset.click.left), t.pageY - this.offset.click.top < this.containment[1] && (o = this.containment[1] + this.offset.click.top), t.pageX - this.offset.click.left > this.containment[2] && (s = this.containment[2] + this.offset.click.left), t.pageY - this.offset.click.top > this.containment[3] && (o = this.containment[3] + this.offset.click.top)), i.grid && (n = this.originalPageY + Math.round((o - this.originalPageY) / i.grid[1]) * i.grid[1], o = this.containment ? n - this.offset.click.top >= this.containment[1] && n - this.offset.click.top <= this.containment[3] ? n : n - this.offset.click.top >= this.containment[1] ? n - i.grid[1] : n + i.grid[1] : n, r = this.originalPageX + Math.round((s - this.originalPageX) / i.grid[0]) * i.grid[0], s = this.containment ? r - this.offset.click.left >= this.containment[0] && r - this.offset.click.left <= this.containment[2] ? r : r - this.offset.click.left >= this.containment[0] ? r - i.grid[0] : r + i.grid[0] : r)), {
				top: o - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + (this.cssPosition === "fixed" ? -this.scrollParent.scrollTop() : a ? 0 : u.scrollTop()),
				left: s - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + (this.cssPosition === "fixed" ? -this.scrollParent.scrollLeft() : a ? 0 : u.scrollLeft())
			}
		},
		_rearrange: function(e, t, n, r) {
			n ? n[0].appendChild(this.placeholder[0]) : t.item[0].parentNode.insertBefore(this.placeholder[0], this.direction === "down" ? t.item[0] : t.item[0].nextSibling), this.counter = this.counter ? ++this.counter : 1;
			var i = this.counter;
			this._delay(function() {
				i === this.counter && this.refreshPositions(!r)
			})
		},
		_clear: function(e, t) {
			function i(e, t, n) {
				return function(r) {
					n._trigger(e, r, t._uiHash(t))
				}
			}
			this.reverting = !1;
			var n, r = [];
			!this._noFinalSort && this.currentItem.parent().length && this.placeholder.before(this.currentItem), this._noFinalSort = null;
			if (this.helper[0] === this.currentItem[0]) {
				for (n in this._storedCSS) if (this._storedCSS[n] === "auto" || this._storedCSS[n] === "static") this._storedCSS[n] = "";
				this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper")
			} else this.currentItem.show();
			this.fromOutside && !t && r.push(function(e) {
				this._trigger("receive", e, this._uiHash(this.fromOutside))
			}), (this.fromOutside || this.domPosition.prev !== this.currentItem.prev().not(".ui-sortable-helper")[0] || this.domPosition.parent !== this.currentItem.parent()[0]) && !t && r.push(function(e) {
				this._trigger("update", e, this._uiHash())
			}), this !== this.currentContainer && (t || (r.push(function(e) {
				this._trigger("remove", e, this._uiHash())
			}), r.push(function(e) {
				return function(t) {
					e._trigger("receive", t, this._uiHash(this))
				}
			}.call(this, this.currentContainer)), r.push(function(e) {
				return function(t) {
					e._trigger("update", t, this._uiHash(this))
				}
			}.call(this, this.currentContainer))));
			for (n = this.containers.length - 1; n >= 0; n--) t || r.push(i("deactivate", this, this.containers[n])), this.containers[n].containerCache.over && (r.push(i("out", this, this.containers[n])), this.containers[n].containerCache.over = 0);
			this.storedCursor && (this.document.find("body").css("cursor", this.storedCursor), this.storedStylesheet.remove()), this._storedOpacity && this.helper.css("opacity", this._storedOpacity), this._storedZIndex && this.helper.css("zIndex", this._storedZIndex === "auto" ? "" : this._storedZIndex), this.dragging = !1, t || this._trigger("beforeStop", e, this._uiHash()), this.placeholder[0].parentNode.removeChild(this.placeholder[0]), this.cancelHelperRemoval || (this.helper[0] !== this.currentItem[0] && this.helper.remove(), this.helper = null);
			if (!t) {
				for (n = 0; n < r.length; n++) r[n].call(this, e);
				this._trigger("stop", e, this._uiHash())
			}
			return this.fromOutside = !1, !this.cancelHelperRemoval
		},
		_trigger: function() {
			e.Widget.prototype._trigger.apply(this, arguments) === !1 && this.cancel()
		},
		_uiHash: function(t) {
			var n = t || this;
			return {
				helper: n.helper,
				placeholder: n.placeholder || e([]),
				position: n.position,
				originalPosition: n.originalPosition,
				offset: n.positionAbs,
				item: n.currentItem,
				sender: t ? t.element : null
			}
		}
	})
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery", "./core", "./mouse", "./widget"], e) : e(jQuery)
}(function(e) {
	return e.widget("ui.draggable", e.ui.mouse, {
		version: "1.11.4",
		widgetEventPrefix: "drag",
		options: {
			addClasses: !0,
			appendTo: "parent",
			axis: !1,
			connectToSortable: !1,
			containment: !1,
			cursor: "auto",
			cursorAt: !1,
			grid: !1,
			handle: !1,
			helper: "original",
			iframeFix: !1,
			opacity: !1,
			refreshPositions: !1,
			revert: !1,
			revertDuration: 500,
			scope: "default",
			scroll: !0,
			scrollSensitivity: 20,
			scrollSpeed: 20,
			snap: !1,
			snapMode: "both",
			snapTolerance: 20,
			stack: !1,
			zIndex: !1,
			drag: null,
			start: null,
			stop: null
		},
		_create: function() {
			this.options.helper === "original" && this._setPositionRelative(), this.options.addClasses && this.element.addClass("ui-draggable"), this.options.disabled && this.element.addClass("ui-draggable-disabled"), this._setHandleClassName(), this._mouseInit()
		},
		_setOption: function(e, t) {
			this._super(e, t), e === "handle" && (this._removeHandleClassName(), this._setHandleClassName())
		},
		_destroy: function() {
			if ((this.helper || this.element).is(".ui-draggable-dragging")) {
				this.destroyOnClear = !0;
				return
			}
			this.element.removeClass("ui-draggable ui-draggable-dragging ui-draggable-disabled"), this._removeHandleClassName(), this._mouseDestroy()
		},
		_mouseCapture: function(t) {
			var n = this.options;
			return this._blurActiveElement(t), this.helper || n.disabled || e(t.target).closest(".ui-resizable-handle").length > 0 ? !1 : (this.handle = this._getHandle(t), this.handle ? (this._blockFrames(n.iframeFix === !0 ? "iframe" : n.iframeFix), !0) : !1)
		},
		_blockFrames: function(t) {
			this.iframeBlocks = this.document.find(t).map(function() {
				var t = e(this);
				return e("<div>").css("position", "absolute").appendTo(t.parent()).outerWidth(t.outerWidth()).outerHeight(t.outerHeight()).offset(t.offset())[0]
			})
		},
		_unblockFrames: function() {
			this.iframeBlocks && (this.iframeBlocks.remove(), delete this.iframeBlocks)
		},
		_blurActiveElement: function(t) {
			var n = this.document[0];
			if (!this.handleElement.is(t.target)) return;
			try {
				n.activeElement && n.activeElement.nodeName.toLowerCase() !== "body" && e(n.activeElement).blur()
			} catch (r) {}
		},
		_mouseStart: function(t) {
			var n = this.options;
			return this.helper = this._createHelper(t), this.helper.addClass("ui-draggable-dragging"), this._cacheHelperProportions(), e.ui.ddmanager && (e.ui.ddmanager.current = this), this._cacheMargins(), this.cssPosition = this.helper.css("position"), this.scrollParent = this.helper.scrollParent(!0), this.offsetParent = this.helper.offsetParent(), this.hasFixedAncestor = this.helper.parents().filter(function() {
				return e(this).css("position") === "fixed"
			}).length > 0, this.positionAbs = this.element.offset(), this._refreshOffsets(t), this.originalPosition = this.position = this._generatePosition(t, !1), this.originalPageX = t.pageX, this.originalPageY = t.pageY, n.cursorAt && this._adjustOffsetFromHelper(n.cursorAt), this._setContainment(), this._trigger("start", t) === !1 ? (this._clear(), !1) : (this._cacheHelperProportions(), e.ui.ddmanager && !n.dropBehaviour && e.ui.ddmanager.prepareOffsets(this, t), this._normalizeRightBottom(), this._mouseDrag(t, !0), e.ui.ddmanager && e.ui.ddmanager.dragStart(this, t), !0)
		},
		_refreshOffsets: function(e) {
			this.offset = {
				top: this.positionAbs.top - this.margins.top,
				left: this.positionAbs.left - this.margins.left,
				scroll: !1,
				parent: this._getParentOffset(),
				relative: this._getRelativeOffset()
			}, this.offset.click = {
				left: e.pageX - this.offset.left,
				top: e.pageY - this.offset.top
			}
		},
		_mouseDrag: function(t, n) {
			this.hasFixedAncestor && (this.offset.parent = this._getParentOffset()), this.position = this._generatePosition(t, !0), this.positionAbs = this._convertPositionTo("absolute");
			if (!n) {
				var r = this._uiHash();
				if (this._trigger("drag", t, r) === !1) return this._mouseUp({}), !1;
				this.position = r.position
			}
			return this.helper[0].style.left = this.position.left + "px", this.helper[0].style.top = this.position.top + "px", e.ui.ddmanager && e.ui.ddmanager.drag(this, t), !1
		},
		_mouseStop: function(t) {
			var n = this,
				r = !1;
			return e.ui.ddmanager && !this.options.dropBehaviour && (r = e.ui.ddmanager.drop(this, t)), this.dropped && (r = this.dropped, this.dropped = !1), this.options.revert === "invalid" && !r || this.options.revert === "valid" && r || this.options.revert === !0 || e.isFunction(this.options.revert) && this.options.revert.call(this.element, r) ? e(this.helper).animate(this.originalPosition, parseInt(this.options.revertDuration, 10), function() {
				n._trigger("stop", t) !== !1 && n._clear()
			}) : this._trigger("stop", t) !== !1 && this._clear(), !1
		},
		_mouseUp: function(t) {
			return this._unblockFrames(), e.ui.ddmanager && e.ui.ddmanager.dragStop(this, t), this.handleElement.is(t.target) && this.element.focus(), e.ui.mouse.prototype._mouseUp.call(this, t)
		},
		cancel: function() {
			return this.helper.is(".ui-draggable-dragging") ? this._mouseUp({}) : this._clear(), this
		},
		_getHandle: function(t) {
			return this.options.handle ? !! e(t.target).closest(this.element.find(this.options.handle)).length : !0
		},
		_setHandleClassName: function() {
			this.handleElement = this.options.handle ? this.element.find(this.options.handle) : this.element, this.handleElement.addClass("ui-draggable-handle")
		},
		_removeHandleClassName: function() {
			this.handleElement.removeClass("ui-draggable-handle")
		},
		_createHelper: function(t) {
			var n = this.options,
				r = e.isFunction(n.helper),
				i = r ? e(n.helper.apply(this.element[0], [t])) : n.helper === "clone" ? this.element.clone().removeAttr("id") : this.element;
			return i.parents("body").length || i.appendTo(n.appendTo === "parent" ? this.element[0].parentNode : n.appendTo), r && i[0] === this.element[0] && this._setPositionRelative(), i[0] !== this.element[0] && !/(fixed|absolute)/.test(i.css("position")) && i.css("position", "absolute"), i
		},
		_setPositionRelative: function() {
			/^(?:r|a|f)/.test(this.element.css("position")) || (this.element[0].style.position = "relative")
		},
		_adjustOffsetFromHelper: function(t) {
			typeof t == "string" && (t = t.split(" ")), e.isArray(t) && (t = {
				left: +t[0],
				top: +t[1] || 0
			}), "left" in t && (this.offset.click.left = t.left + this.margins.left), "right" in t && (this.offset.click.left = this.helperProportions.width - t.right + this.margins.left), "top" in t && (this.offset.click.top = t.top + this.margins.top), "bottom" in t && (this.offset.click.top = this.helperProportions.height - t.bottom + this.margins.top)
		},
		_isRootNode: function(e) {
			return /(html|body)/i.test(e.tagName) || e === this.document[0]
		},
		_getParentOffset: function() {
			var t = this.offsetParent.offset(),
				n = this.document[0];
			return this.cssPosition === "absolute" && this.scrollParent[0] !== n && e.contains(this.scrollParent[0], this.offsetParent[0]) && (t.left += this.scrollParent.scrollLeft(), t.top += this.scrollParent.scrollTop()), this._isRootNode(this.offsetParent[0]) && (t = {
				top: 0,
				left: 0
			}), {
				top: t.top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0),
				left: t.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0)
			}
		},
		_getRelativeOffset: function() {
			if (this.cssPosition !== "relative") return {
				top: 0,
				left: 0
			};
			var e = this.element.position(),
				t = this._isRootNode(this.scrollParent[0]);
			return {
				top: e.top - (parseInt(this.helper.css("top"), 10) || 0) + (t ? 0 : this.scrollParent.scrollTop()),
				left: e.left - (parseInt(this.helper.css("left"), 10) || 0) + (t ? 0 : this.scrollParent.scrollLeft())
			}
		},
		_cacheMargins: function() {
			this.margins = {
				left: parseInt(this.element.css("marginLeft"), 10) || 0,
				top: parseInt(this.element.css("marginTop"), 10) || 0,
				right: parseInt(this.element.css("marginRight"), 10) || 0,
				bottom: parseInt(this.element.css("marginBottom"), 10) || 0
			}
		},
		_cacheHelperProportions: function() {
			this.helperProportions = {
				width: this.helper.outerWidth(),
				height: this.helper.outerHeight()
			}
		},
		_setContainment: function() {
			var t, n, r, i = this.options,
				s = this.document[0];
			this.relativeContainer = null;
			if (!i.containment) {
				this.containment = null;
				return
			}
			if (i.containment === "window") {
				this.containment = [e(window).scrollLeft() - this.offset.relative.left - this.offset.parent.left, e(window).scrollTop() - this.offset.relative.top - this.offset.parent.top, e(window).scrollLeft() + e(window).width() - this.helperProportions.width - this.margins.left, e(window).scrollTop() + (e(window).height() || s.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top];
				return
			}
			if (i.containment === "document") {
				this.containment = [0, 0, e(s).width() - this.helperProportions.width - this.margins.left, (e(s).height() || s.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top];
				return
			}
			if (i.containment.constructor === Array) {
				this.containment = i.containment;
				return
			}
			i.containment === "parent" && (i.containment = this.helper[0].parentNode), n = e(i.containment), r = n[0];
			if (!r) return;
			t = /(scroll|auto)/.test(n.css("overflow")), this.containment = [(parseInt(n.css("borderLeftWidth"), 10) || 0) + (parseInt(n.css("paddingLeft"), 10) || 0), (parseInt(n.css("borderTopWidth"), 10) || 0) + (parseInt(n.css("paddingTop"), 10) || 0), (t ? Math.max(r.scrollWidth, r.offsetWidth) : r.offsetWidth) - (parseInt(n.css("borderRightWidth"), 10) || 0) - (parseInt(n.css("paddingRight"), 10) || 0) - this.helperProportions.width - this.margins.left - this.margins.right, (t ? Math.max(r.scrollHeight, r.offsetHeight) : r.offsetHeight) - (parseInt(n.css("borderBottomWidth"), 10) || 0) - (parseInt(n.css("paddingBottom"), 10) || 0) - this.helperProportions.height - this.margins.top - this.margins.bottom], this.relativeContainer = n
		},
		_convertPositionTo: function(e, t) {
			t || (t = this.position);
			var n = e === "absolute" ? 1 : -1,
				r = this._isRootNode(this.scrollParent[0]);
			return {
				top: t.top + this.offset.relative.top * n + this.offset.parent.top * n - (this.cssPosition === "fixed" ? -this.offset.scroll.top : r ? 0 : this.offset.scroll.top) * n,
				left: t.left + this.offset.relative.left * n + this.offset.parent.left * n - (this.cssPosition === "fixed" ? -this.offset.scroll.left : r ? 0 : this.offset.scroll.left) * n
			}
		},
		_generatePosition: function(e, t) {
			var n, r, i, s, o = this.options,
				u = this._isRootNode(this.scrollParent[0]),
				a = e.pageX,
				f = e.pageY;
			if (!u || !this.offset.scroll) this.offset.scroll = {
				top: this.scrollParent.scrollTop(),
				left: this.scrollParent.scrollLeft()
			};
			return t && (this.containment && (this.relativeContainer ? (r = this.relativeContainer.offset(), n = [this.containment[0] + r.left, this.containment[1] + r.top, this.containment[2] + r.left, this.containment[3] + r.top]) : n = this.containment, e.pageX - this.offset.click.left < n[0] && (a = n[0] + this.offset.click.left), e.pageY - this.offset.click.top < n[1] && (f = n[1] + this.offset.click.top), e.pageX - this.offset.click.left > n[2] && (a = n[2] + this.offset.click.left), e.pageY - this.offset.click.top > n[3] && (f = n[3] + this.offset.click.top)), o.grid && (i = o.grid[1] ? this.originalPageY + Math.round((f - this.originalPageY) / o.grid[1]) * o.grid[1] : this.originalPageY, f = n ? i - this.offset.click.top >= n[1] || i - this.offset.click.top > n[3] ? i : i - this.offset.click.top >= n[1] ? i - o.grid[1] : i + o.grid[1] : i, s = o.grid[0] ? this.originalPageX + Math.round((a - this.originalPageX) / o.grid[0]) * o.grid[0] : this.originalPageX, a = n ? s - this.offset.click.left >= n[0] || s - this.offset.click.left > n[2] ? s : s - this.offset.click.left >= n[0] ? s - o.grid[0] : s + o.grid[0] : s), o.axis === "y" && (a = this.originalPageX), o.axis === "x" && (f = this.originalPageY)), {
				top: f - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + (this.cssPosition === "fixed" ? -this.offset.scroll.top : u ? 0 : this.offset.scroll.top),
				left: a - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + (this.cssPosition === "fixed" ? -this.offset.scroll.left : u ? 0 : this.offset.scroll.left)
			}
		},
		_clear: function() {
			this.helper.removeClass("ui-draggable-dragging"), this.helper[0] !== this.element[0] && !this.cancelHelperRemoval && this.helper.remove(), this.helper = null, this.cancelHelperRemoval = !1, this.destroyOnClear && this.destroy()
		},
		_normalizeRightBottom: function() {
			this.options.axis !== "y" && this.helper.css("right") !== "auto" && (this.helper.width(this.helper.width()), this.helper.css("right", "auto")), this.options.axis !== "x" && this.helper.css("bottom") !== "auto" && (this.helper.height(this.helper.height()), this.helper.css("bottom", "auto"))
		},
		_trigger: function(t, n, r) {
			return r = r || this._uiHash(), e.ui.plugin.call(this, t, [n, r, this], !0), /^(drag|start|stop)/.test(t) && (this.positionAbs = this._convertPositionTo("absolute"), r.offset = this.positionAbs), e.Widget.prototype._trigger.call(this, t, n, r)
		},
		plugins: {},
		_uiHash: function() {
			return {
				helper: this.helper,
				position: this.position,
				originalPosition: this.originalPosition,
				offset: this.positionAbs
			}
		}
	}), e.ui.plugin.add("draggable", "connectToSortable", {
		start: function(t, n, r) {
			var i = e.extend({}, n, {
				item: r.element
			});
			r.sortables = [], e(r.options.connectToSortable).each(function() {
				var n = e(this).sortable("instance");
				n && !n.options.disabled && (r.sortables.push(n), n.refreshPositions(), n._trigger("activate", t, i))
			})
		},
		stop: function(t, n, r) {
			var i = e.extend({}, n, {
				item: r.element
			});
			r.cancelHelperRemoval = !1, e.each(r.sortables, function() {
				var e = this;
				e.isOver ? (e.isOver = 0, r.cancelHelperRemoval = !0, e.cancelHelperRemoval = !1, e._storedCSS = {
					position: e.placeholder.css("position"),
					top: e.placeholder.css("top"),
					left: e.placeholder.css("left")
				}, e._mouseStop(t), e.options.helper = e.options._helper) : (e.cancelHelperRemoval = !0, e._trigger("deactivate", t, i))
			})
		},
		drag: function(t, n, r) {
			e.each(r.sortables, function() {
				var i = !1,
					s = this;
				s.positionAbs = r.positionAbs, s.helperProportions = r.helperProportions, s.offset.click = r.offset.click, s._intersectsWith(s.containerCache) && (i = !0, e.each(r.sortables, function() {
					return this.positionAbs = r.positionAbs, this.helperProportions = r.helperProportions, this.offset.click = r.offset.click, this !== s && this._intersectsWith(this.containerCache) && e.contains(s.element[0], this.element[0]) && (i = !1), i
				})), i ? (s.isOver || (s.isOver = 1, r._parent = n.helper.parent(), s.currentItem = n.helper.appendTo(s.element).data("ui-sortable-item", !0), s.options._helper = s.options.helper, s.options.helper = function() {
					return n.helper[0]
				}, t.target = s.currentItem[0], s._mouseCapture(t, !0), s._mouseStart(t, !0, !0), s.offset.click.top = r.offset.click.top, s.offset.click.left = r.offset.click.left, s.offset.parent.left -= r.offset.parent.left - s.offset.parent.left, s.offset.parent.top -= r.offset.parent.top - s.offset.parent.top, r._trigger("toSortable", t), r.dropped = s.element, e.each(r.sortables, function() {
					this.refreshPositions()
				}), r.currentItem = r.element, s.fromOutside = r), s.currentItem && (s._mouseDrag(t), n.position = s.position)) : s.isOver && (s.isOver = 0, s.cancelHelperRemoval = !0, s.options._revert = s.options.revert, s.options.revert = !1, s._trigger("out", t, s._uiHash(s)), s._mouseStop(t, !0), s.options.revert = s.options._revert, s.options.helper = s.options._helper, s.placeholder && s.placeholder.remove(), n.helper.appendTo(r._parent), r._refreshOffsets(t), n.position = r._generatePosition(t, !0), r._trigger("fromSortable", t), r.dropped = !1, e.each(r.sortables, function() {
					this.refreshPositions()
				}))
			})
		}
	}), e.ui.plugin.add("draggable", "cursor", {
		start: function(t, n, r) {
			var i = e("body"),
				s = r.options;
			i.css("cursor") && (s._cursor = i.css("cursor")), i.css("cursor", s.cursor)
		},
		stop: function(t, n, r) {
			var i = r.options;
			i._cursor && e("body").css("cursor", i._cursor)
		}
	}), e.ui.plugin.add("draggable", "opacity", {
		start: function(t, n, r) {
			var i = e(n.helper),
				s = r.options;
			i.css("opacity") && (s._opacity = i.css("opacity")), i.css("opacity", s.opacity)
		},
		stop: function(t, n, r) {
			var i = r.options;
			i._opacity && e(n.helper).css("opacity", i._opacity)
		}
	}), e.ui.plugin.add("draggable", "scroll", {
		start: function(e, t, n) {
			n.scrollParentNotHidden || (n.scrollParentNotHidden = n.helper.scrollParent(!1)), n.scrollParentNotHidden[0] !== n.document[0] && n.scrollParentNotHidden[0].tagName !== "HTML" && (n.overflowOffset = n.scrollParentNotHidden.offset())
		},
		drag: function(t, n, r) {
			var i = r.options,
				s = !1,
				o = r.scrollParentNotHidden[0],
				u = r.document[0];
			if (o !== u && o.tagName !== "HTML") {
				if (!i.axis || i.axis !== "x") r.overflowOffset.top + o.offsetHeight - t.pageY < i.scrollSensitivity ? o.scrollTop = s = o.scrollTop + i.scrollSpeed : t.pageY - r.overflowOffset.top < i.scrollSensitivity && (o.scrollTop = s = o.scrollTop - i.scrollSpeed);
				if (!i.axis || i.axis !== "y") r.overflowOffset.left + o.offsetWidth - t.pageX < i.scrollSensitivity ? o.scrollLeft = s = o.scrollLeft + i.scrollSpeed : t.pageX - r.overflowOffset.left < i.scrollSensitivity && (o.scrollLeft = s = o.scrollLeft - i.scrollSpeed)
			} else {
				if (!i.axis || i.axis !== "x") t.pageY - e(u).scrollTop() < i.scrollSensitivity ? s = e(u).scrollTop(e(u).scrollTop() - i.scrollSpeed) : e(window).height() - (t.pageY - e(u).scrollTop()) < i.scrollSensitivity && (s = e(u).scrollTop(e(u).scrollTop() + i.scrollSpeed));
				if (!i.axis || i.axis !== "y") t.pageX - e(u).scrollLeft() < i.scrollSensitivity ? s = e(u).scrollLeft(e(u).scrollLeft() - i.scrollSpeed) : e(window).width() - (t.pageX - e(u).scrollLeft()) < i.scrollSensitivity && (s = e(u).scrollLeft(e(u).scrollLeft() + i.scrollSpeed))
			}
			s !== !1 && e.ui.ddmanager && !i.dropBehaviour && e.ui.ddmanager.prepareOffsets(r, t)
		}
	}), e.ui.plugin.add("draggable", "snap", {
		start: function(t, n, r) {
			var i = r.options;
			r.snapElements = [], e(i.snap.constructor !== String ? i.snap.items || ":data(ui-draggable)" : i.snap).each(function() {
				var t = e(this),
					n = t.offset();
				this !== r.element[0] && r.snapElements.push({
					item: this,
					width: t.outerWidth(),
					height: t.outerHeight(),
					top: n.top,
					left: n.left
				})
			})
		},
		drag: function(t, n, r) {
			var i, s, o, u, a, f, l, c, h, p, d = r.options,
				v = d.snapTolerance,
				m = n.offset.left,
				g = m + r.helperProportions.width,
				y = n.offset.top,
				b = y + r.helperProportions.height;
			for (h = r.snapElements.length - 1; h >= 0; h--) {
				a = r.snapElements[h].left - r.margins.left, f = a + r.snapElements[h].width, l = r.snapElements[h].top - r.margins.top, c = l + r.snapElements[h].height;
				if (g < a - v || m > f + v || b < l - v || y > c + v || !e.contains(r.snapElements[h].item.ownerDocument, r.snapElements[h].item)) {
					r.snapElements[h].snapping && r.options.snap.release && r.options.snap.release.call(r.element, t, e.extend(r._uiHash(), {
						snapItem: r.snapElements[h].item
					})), r.snapElements[h].snapping = !1;
					continue
				}
				d.snapMode !== "inner" && (i = Math.abs(l - b) <= v, s = Math.abs(c - y) <= v, o = Math.abs(a - g) <= v, u = Math.abs(f - m) <= v, i && (n.position.top = r._convertPositionTo("relative", {
					top: l - r.helperProportions.height,
					left: 0
				}).top), s && (n.position.top = r._convertPositionTo("relative", {
					top: c,
					left: 0
				}).top), o && (n.position.left = r._convertPositionTo("relative", {
					top: 0,
					left: a - r.helperProportions.width
				}).left), u && (n.position.left = r._convertPositionTo("relative", {
					top: 0,
					left: f
				}).left)), p = i || s || o || u, d.snapMode !== "outer" && (i = Math.abs(l - y) <= v, s = Math.abs(c - b) <= v, o = Math.abs(a - m) <= v, u = Math.abs(f - g) <= v, i && (n.position.top = r._convertPositionTo("relative", {
					top: l,
					left: 0
				}).top), s && (n.position.top = r._convertPositionTo("relative", {
					top: c - r.helperProportions.height,
					left: 0
				}).top), o && (n.position.left = r._convertPositionTo("relative", {
					top: 0,
					left: a
				}).left), u && (n.position.left = r._convertPositionTo("relative", {
					top: 0,
					left: f - r.helperProportions.width
				}).left)), !r.snapElements[h].snapping && (i || s || o || u || p) && r.options.snap.snap && r.options.snap.snap.call(r.element, t, e.extend(r._uiHash(), {
					snapItem: r.snapElements[h].item
				})), r.snapElements[h].snapping = i || s || o || u || p
			}
		}
	}), e.ui.plugin.add("draggable", "stack", {
		start: function(t, n, r) {
			var i, s = r.options,
				o = e.makeArray(e(s.stack)).sort(function(t, n) {
					return (parseInt(e(t).css("zIndex"), 10) || 0) - (parseInt(e(n).css("zIndex"), 10) || 0)
				});
			if (!o.length) return;
			i = parseInt(e(o[0]).css("zIndex"), 10) || 0, e(o).each(function(t) {
				e(this).css("zIndex", i + t)
			}), this.css("zIndex", i + o.length)
		}
	}), e.ui.plugin.add("draggable", "zIndex", {
		start: function(t, n, r) {
			var i = e(n.helper),
				s = r.options;
			i.css("zIndex") && (s._zIndex = i.css("zIndex")), i.css("zIndex", s.zIndex)
		},
		stop: function(t, n, r) {
			var i = r.options;
			i._zIndex && e(n.helper).css("zIndex", i._zIndex)
		}
	}), e.ui.draggable
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery", "./core", "./widget", "./mouse", "./draggable"], e) : e(jQuery)
}(function(e) {
	return e.widget("ui.droppable", {
		version: "1.11.4",
		widgetEventPrefix: "drop",
		options: {
			accept: "*",
			activeClass: !1,
			addClasses: !0,
			greedy: !1,
			hoverClass: !1,
			scope: "default",
			tolerance: "intersect",
			activate: null,
			deactivate: null,
			drop: null,
			out: null,
			over: null
		},
		_create: function() {
			var t, n = this.options,
				r = n.accept;
			this.isover = !1, this.isout = !0, this.accept = e.isFunction(r) ? r : function(e) {
				return e.is(r)
			}, this.proportions = function() {
				if (!arguments.length) return t ? t : t = {
					width: this.element[0].offsetWidth,
					height: this.element[0].offsetHeight
				};
				t = arguments[0]
			}, this._addToManager(n.scope), n.addClasses && this.element.addClass("ui-droppable")
		},
		_addToManager: function(t) {
			e.ui.ddmanager.droppables[t] = e.ui.ddmanager.droppables[t] || [], e.ui.ddmanager.droppables[t].push(this)
		},
		_splice: function(e) {
			var t = 0;
			for (; t < e.length; t++) e[t] === this && e.splice(t, 1)
		},
		_destroy: function() {
			var t = e.ui.ddmanager.droppables[this.options.scope];
			this._splice(t), this.element.removeClass("ui-droppable ui-droppable-disabled")
		},
		_setOption: function(t, n) {
			if (t === "accept") this.accept = e.isFunction(n) ? n : function(e) {
				return e.is(n)
			};
			else if (t === "scope") {
				var r = e.ui.ddmanager.droppables[this.options.scope];
				this._splice(r), this._addToManager(n)
			}
			this._super(t, n)
		},
		_activate: function(t) {
			var n = e.ui.ddmanager.current;
			this.options.activeClass && this.element.addClass(this.options.activeClass), n && this._trigger("activate", t, this.ui(n))
		},
		_deactivate: function(t) {
			var n = e.ui.ddmanager.current;
			this.options.activeClass && this.element.removeClass(this.options.activeClass), n && this._trigger("deactivate", t, this.ui(n))
		},
		_over: function(t) {
			var n = e.ui.ddmanager.current;
			if (!n || (n.currentItem || n.element)[0] === this.element[0]) return;
			this.accept.call(this.element[0], n.currentItem || n.element) && (this.options.hoverClass && this.element.addClass(this.options.hoverClass), this._trigger("over", t, this.ui(n)))
		},
		_out: function(t) {
			var n = e.ui.ddmanager.current;
			if (!n || (n.currentItem || n.element)[0] === this.element[0]) return;
			this.accept.call(this.element[0], n.currentItem || n.element) && (this.options.hoverClass && this.element.removeClass(this.options.hoverClass), this._trigger("out", t, this.ui(n)))
		},
		_drop: function(t, n) {
			var r = n || e.ui.ddmanager.current,
				i = !1;
			return !r || (r.currentItem || r.element)[0] === this.element[0] ? !1 : (this.element.find(":data(ui-droppable)").not(".ui-draggable-dragging").each(function() {
				var n = e(this).droppable("instance");
				if (n.options.greedy && !n.options.disabled && n.options.scope === r.options.scope && n.accept.call(n.element[0], r.currentItem || r.element) && e.ui.intersect(r, e.extend(n, {
					offset: n.element.offset()
				}), n.options.tolerance, t)) return i = !0, !1
			}), i ? !1 : this.accept.call(this.element[0], r.currentItem || r.element) ? (this.options.activeClass && this.element.removeClass(this.options.activeClass), this.options.hoverClass && this.element.removeClass(this.options.hoverClass), this._trigger("drop", t, this.ui(r)), this.element) : !1)
		},
		ui: function(e) {
			return {
				draggable: e.currentItem || e.element,
				helper: e.helper,
				position: e.position,
				offset: e.positionAbs
			}
		}
	}), e.ui.intersect = function() {
		function e(e, t, n) {
			return e >= t && e < t + n
		}
		return function(t, n, r, i) {
			if (!n.offset) return !1;
			var s = (t.positionAbs || t.position.absolute).left + t.margins.left,
				o = (t.positionAbs || t.position.absolute).top + t.margins.top,
				u = s + t.helperProportions.width,
				a = o + t.helperProportions.height,
				f = n.offset.left,
				l = n.offset.top,
				c = f + n.proportions().width,
				h = l + n.proportions().height;
			switch (r) {
			case "fit":
				return f <= s && u <= c && l <= o && a <= h;
			case "intersect":
				return f < s + t.helperProportions.width / 2 && u - t.helperProportions.width / 2 < c && l < o + t.helperProportions.height / 2 && a - t.helperProportions.height / 2 < h;
			case "pointer":
				return e(i.pageY, l, n.proportions().height) && e(i.pageX, f, n.proportions().width);
			case "touch":
				return (o >= l && o <= h || a >= l && a <= h || o < l && a > h) && (s >= f && s <= c || u >= f && u <= c || s < f && u > c);
			default:
				return !1
			}
		}
	}(), e.ui.ddmanager = {
		current: null,
		droppables: {
			"default": []
		},
		prepareOffsets: function(t, n) {
			var r, i, s = e.ui.ddmanager.droppables[t.options.scope] || [],
				o = n ? n.type : null,
				u = (t.currentItem || t.element).find(":data(ui-droppable)").addBack();
			e: for (r = 0; r < s.length; r++) {
				if (s[r].options.disabled || t && !s[r].accept.call(s[r].element[0], t.currentItem || t.element)) continue;
				for (i = 0; i < u.length; i++) if (u[i] === s[r].element[0]) {
					s[r].proportions().height = 0;
					continue e
				}
				s[r].visible = s[r].element.css("display") !== "none";
				if (!s[r].visible) continue;
				o === "mousedown" && s[r]._activate.call(s[r], n), s[r].offset = s[r].element.offset(), s[r].proportions({
					width: s[r].element[0].offsetWidth,
					height: s[r].element[0].offsetHeight
				})
			}
		},
		drop: function(t, n) {
			var r = !1;
			return e.each((e.ui.ddmanager.droppables[t.options.scope] || []).slice(), function() {
				if (!this.options) return;
				!this.options.disabled && this.visible && e.ui.intersect(t, this, this.options.tolerance, n) && (r = this._drop.call(this, n) || r), !this.options.disabled && this.visible && this.accept.call(this.element[0], t.currentItem || t.element) && (this.isout = !0, this.isover = !1, this._deactivate.call(this, n))
			}), r
		},
		dragStart: function(t, n) {
			t.element.parentsUntil("body").bind("scroll.droppable", function() {
				t.options.refreshPositions || e.ui.ddmanager.prepareOffsets(t, n)
			})
		},
		drag: function(t, n) {
			t.options.refreshPositions && e.ui.ddmanager.prepareOffsets(t, n), e.each(e.ui.ddmanager.droppables[t.options.scope] || [], function() {
				if (this.options.disabled || this.greedyChild || !this.visible) return;
				var r, i, s, o = e.ui.intersect(t, this, this.options.tolerance, n),
					u = !o && this.isover ? "isout" : o && !this.isover ? "isover" : null;
				if (!u) return;
				this.options.greedy && (i = this.options.scope, s = this.element.parents(":data(ui-droppable)").filter(function() {
					return e(this).droppable("instance").options.scope === i
				}), s.length && (r = e(s[0]).droppable("instance"), r.greedyChild = u === "isover")), r && u === "isover" && (r.isover = !1, r.isout = !0, r._out.call(r, n)), this[u] = !0, this[u === "isout" ? "isover" : "isout"] = !1, this[u === "isover" ? "_over" : "_out"].call(this, n), r && u === "isout" && (r.isout = !1, r.isover = !0, r._over.call(r, n))
			})
		},
		dragStop: function(t, n) {
			t.element.parentsUntil("body").unbind("scroll.droppable"), t.options.refreshPositions || e.ui.ddmanager.prepareOffsets(t, n)
		}
	}, e.ui.droppable
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery"], e) : e(jQuery)
}(function(e) {
	var t = "ui-effects-",
		n = e;
	return e.effects = {
		effect: {}
	}, function(e, t) {
		function h(e, t, n) {
			var r = u[t.type] || {};
			return e == null ? n || !t.def ? null : t.def : (e = r.floor ? ~~e : parseFloat(e), isNaN(e) ? t.def : r.mod ? (e + r.mod) % r.mod : 0 > e ? 0 : r.max < e ? r.max : e)
		}
		function p(t) {
			var n = s(),
				r = n._rgba = [];
			return t = t.toLowerCase(), c(i, function(e, i) {
				var s, u = i.re.exec(t),
					a = u && i.parse(u),
					f = i.space || "rgba";
				if (a) return s = n[f](a), n[o[f].cache] = s[o[f].cache], r = n._rgba = s._rgba, !1
			}), r.length ? (r.join() === "0,0,0,0" && e.extend(r, l.transparent), n) : l[t]
		}
		function d(e, t, n) {
			return n = (n + 1) % 1, n * 6 < 1 ? e + (t - e) * n * 6 : n * 2 < 1 ? t : n * 3 < 2 ? e + (t - e) * (2 / 3 - n) * 6 : e
		}
		var n = "backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor",
			r = /^([\-+])=\s*(\d+\.?\d*)/,
			i = [{
				re: /rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
				parse: function(e) {
					return [e[1], e[2], e[3], e[4]]
				}
			}, {
				re: /rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
				parse: function(e) {
					return [e[1] * 2.55, e[2] * 2.55, e[3] * 2.55, e[4]]
				}
			}, {
				re: /#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/,
				parse: function(e) {
					return [parseInt(e[1], 16), parseInt(e[2], 16), parseInt(e[3], 16)]
				}
			}, {
				re: /#([a-f0-9])([a-f0-9])([a-f0-9])/,
				parse: function(e) {
					return [parseInt(e[1] + e[1], 16), parseInt(e[2] + e[2], 16), parseInt(e[3] + e[3], 16)]
				}
			}, {
				re: /hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
				space: "hsla",
				parse: function(e) {
					return [e[1], e[2] / 100, e[3] / 100, e[4]]
				}
			}],
			s = e.Color = function(t, n, r, i) {
				return new e.Color.fn.parse(t, n, r, i)
			},
			o = {
				rgba: {
					props: {
						red: {
							idx: 0,
							type: "byte"
						},
						green: {
							idx: 1,
							type: "byte"
						},
						blue: {
							idx: 2,
							type: "byte"
						}
					}
				},
				hsla: {
					props: {
						hue: {
							idx: 0,
							type: "degrees"
						},
						saturation: {
							idx: 1,
							type: "percent"
						},
						lightness: {
							idx: 2,
							type: "percent"
						}
					}
				}
			},
			u = {
				"byte": {
					floor: !0,
					max: 255
				},
				percent: {
					max: 1
				},
				degrees: {
					mod: 360,
					floor: !0
				}
			},
			a = s.support = {},
			f = e("<p>")[0],
			l, c = e.each;
		f.style.cssText = "background-color:rgba(1,1,1,.5)", a.rgba = f.style.backgroundColor.indexOf("rgba") > -1, c(o, function(e, t) {
			t.cache = "_" + e, t.props.alpha = {
				idx: 3,
				type: "percent",
				def: 1
			}
		}), s.fn = e.extend(s.prototype, {
			parse: function(n, r, i, u) {
				if (n === t) return this._rgba = [null, null, null, null], this;
				if (n.jquery || n.nodeType) n = e(n).css(r), r = t;
				var a = this,
					f = e.type(n),
					d = this._rgba = [];
				r !== t && (n = [n, r, i, u], f = "array");
				if (f === "string") return this.parse(p(n) || l._default);
				if (f === "array") return c(o.rgba.props, function(e, t) {
					d[t.idx] = h(n[t.idx], t)
				}), this;
				if (f === "object") return n instanceof s ? c(o, function(e, t) {
					n[t.cache] && (a[t.cache] = n[t.cache].slice())
				}) : c(o, function(t, r) {
					var i = r.cache;
					c(r.props, function(e, t) {
						if (!a[i] && r.to) {
							if (e === "alpha" || n[e] == null) return;
							a[i] = r.to(a._rgba)
						}
						a[i][t.idx] = h(n[e], t, !0)
					}), a[i] && e.inArray(null, a[i].slice(0, 3)) < 0 && (a[i][3] = 1, r.from && (a._rgba = r.from(a[i])))
				}), this
			},
			is: function(e) {
				var t = s(e),
					n = !0,
					r = this;
				return c(o, function(e, i) {
					var s, o = t[i.cache];
					return o && (s = r[i.cache] || i.to && i.to(r._rgba) || [], c(i.props, function(e, t) {
						if (o[t.idx] != null) return n = o[t.idx] === s[t.idx], n
					})), n
				}), n
			},
			_space: function() {
				var e = [],
					t = this;
				return c(o, function(n, r) {
					t[r.cache] && e.push(n)
				}), e.pop()
			},
			transition: function(e, t) {
				var n = s(e),
					r = n._space(),
					i = o[r],
					a = this.alpha() === 0 ? s("transparent") : this,
					f = a[i.cache] || i.to(a._rgba),
					l = f.slice();
				return n = n[i.cache], c(i.props, function(e, r) {
					var i = r.idx,
						s = f[i],
						o = n[i],
						a = u[r.type] || {};
					if (o === null) return;
					s === null ? l[i] = o : (a.mod && (o - s > a.mod / 2 ? s += a.mod : s - o > a.mod / 2 && (s -= a.mod)), l[i] = h((o - s) * t + s, r))
				}), this[r](l)
			},
			blend: function(t) {
				if (this._rgba[3] === 1) return this;
				var n = this._rgba.slice(),
					r = n.pop(),
					i = s(t)._rgba;
				return s(e.map(n, function(e, t) {
					return (1 - r) * i[t] + r * e
				}))
			},
			toRgbaString: function() {
				var t = "rgba(",
					n = e.map(this._rgba, function(e, t) {
						return e == null ? t > 2 ? 1 : 0 : e
					});
				return n[3] === 1 && (n.pop(), t = "rgb("), t + n.join() + ")"
			},
			toHslaString: function() {
				var t = "hsla(",
					n = e.map(this.hsla(), function(e, t) {
						return e == null && (e = t > 2 ? 1 : 0), t && t < 3 && (e = Math.round(e * 100) + "%"), e
					});
				return n[3] === 1 && (n.pop(), t = "hsl("), t + n.join() + ")"
			},
			toHexString: function(t) {
				var n = this._rgba.slice(),
					r = n.pop();
				return t && n.push(~~ (r * 255)), "#" + e.map(n, function(e) {
					return e = (e || 0).toString(16), e.length === 1 ? "0" + e : e
				}).join("")
			},
			toString: function() {
				return this._rgba[3] === 0 ? "transparent" : this.toRgbaString()
			}
		}), s.fn.parse.prototype = s.fn, o.hsla.to = function(e) {
			if (e[0] == null || e[1] == null || e[2] == null) return [null, null, null, e[3]];
			var t = e[0] / 255,
				n = e[1] / 255,
				r = e[2] / 255,
				i = e[3],
				s = Math.max(t, n, r),
				o = Math.min(t, n, r),
				u = s - o,
				a = s + o,
				f = a * .5,
				l, c;
			return o === s ? l = 0 : t === s ? l = 60 * (n - r) / u + 360 : n === s ? l = 60 * (r - t) / u + 120 : l = 60 * (t - n) / u + 240, u === 0 ? c = 0 : f <= .5 ? c = u / a : c = u / (2 - a), [Math.round(l) % 360, c, f, i == null ? 1 : i]
		}, o.hsla.from = function(e) {
			if (e[0] == null || e[1] == null || e[2] == null) return [null, null, null, e[3]];
			var t = e[0] / 360,
				n = e[1],
				r = e[2],
				i = e[3],
				s = r <= .5 ? r * (1 + n) : r + n - r * n,
				o = 2 * r - s;
			return [Math.round(d(o, s, t + 1 / 3) * 255), Math.round(d(o, s, t) * 255), Math.round(d(o, s, t - 1 / 3) * 255), i]
		}, c(o, function(n, i) {
			var o = i.props,
				u = i.cache,
				a = i.to,
				f = i.from;
			s.fn[n] = function(n) {
				a && !this[u] && (this[u] = a(this._rgba));
				if (n === t) return this[u].slice();
				var r, i = e.type(n),
					l = i === "array" || i === "object" ? n : arguments,
					p = this[u].slice();
				return c(o, function(e, t) {
					var n = l[i === "object" ? e : t.idx];
					n == null && (n = p[t.idx]), p[t.idx] = h(n, t)
				}), f ? (r = s(f(p)), r[u] = p, r) : s(p)
			}, c(o, function(t, i) {
				if (s.fn[t]) return;
				s.fn[t] = function(s) {
					var o = e.type(s),
						u = t === "alpha" ? this._hsla ? "hsla" : "rgba" : n,
						a = this[u](),
						f = a[i.idx],
						l;
					return o === "undefined" ? f : (o === "function" && (s = s.call(this, f), o = e.type(s)), s == null && i.empty ? this : (o === "string" && (l = r.exec(s), l && (s = f + parseFloat(l[2]) * (l[1] === "+" ? 1 : -1))), a[i.idx] = s, this[u](a)))
				}
			})
		}), s.hook = function(t) {
			var n = t.split(" ");
			c(n, function(t, n) {
				e.cssHooks[n] = {
					set: function(t, r) {
						var i, o, u = "";
						if (r !== "transparent" && (e.type(r) !== "string" || (i = p(r)))) {
							r = s(i || r);
							if (!a.rgba && r._rgba[3] !== 1) {
								o = n === "backgroundColor" ? t.parentNode : t;
								while ((u === "" || u === "transparent") && o && o.style) try {
									u = e.css(o, "backgroundColor"), o = o.parentNode
								} catch (f) {}
								r = r.blend(u && u !== "transparent" ? u : "_default")
							}
							r = r.toRgbaString()
						}
						try {
							t.style[n] = r
						} catch (f) {}
					}
				}, e.fx.step[n] = function(t) {
					t.colorInit || (t.start = s(t.elem, n), t.end = s(t.end), t.colorInit = !0), e.cssHooks[n].set(t.elem, t.start.transition(t.end, t.pos))
				}
			})
		}, s.hook(n), e.cssHooks.borderColor = {
			expand: function(e) {
				var t = {};
				return c(["Top", "Right", "Bottom", "Left"], function(n, r) {
					t["border" + r + "Color"] = e
				}), t
			}
		}, l = e.Color.names = {
			aqua: "#00ffff",
			black: "#000000",
			blue: "#0000ff",
			fuchsia: "#ff00ff",
			gray: "#808080",
			green: "#008000",
			lime: "#00ff00",
			maroon: "#800000",
			navy: "#000080",
			olive: "#808000",
			purple: "#800080",
			red: "#ff0000",
			silver: "#c0c0c0",
			teal: "#008080",
			white: "#ffffff",
			yellow: "#ffff00",
			transparent: [null, null, null, 0],
			_default: "#ffffff"
		}
	}(n), function() {
		function i(t) {
			var n, r, i = t.ownerDocument.defaultView ? t.ownerDocument.defaultView.getComputedStyle(t, null) : t.currentStyle,
				s = {};
			if (i && i.length && i[0] && i[i[0]]) {
				r = i.length;
				while (r--) n = i[r], typeof i[n] == "string" && (s[e.camelCase(n)] = i[n])
			} else for (n in i) typeof i[n] == "string" && (s[n] = i[n]);
			return s
		}
		function s(t, n) {
			var i = {},
				s, o;
			for (s in n) o = n[s], t[s] !== o && !r[s] && (e.fx.step[s] || !isNaN(parseFloat(o))) && (i[s] = o);
			return i
		}
		var t = ["add", "remove", "toggle"],
			r = {
				border: 1,
				borderBottom: 1,
				borderColor: 1,
				borderLeft: 1,
				borderRight: 1,
				borderTop: 1,
				borderWidth: 1,
				margin: 1,
				padding: 1
			};
		e.each(["borderLeftStyle", "borderRightStyle", "borderBottomStyle", "borderTopStyle"], function(t, r) {
			e.fx.step[r] = function(e) {
				if (e.end !== "none" && !e.setAttr || e.pos === 1 && !e.setAttr) n.style(e.elem, r, e.end), e.setAttr = !0
			}
		}), e.fn.addBack || (e.fn.addBack = function(e) {
			return this.add(e == null ? this.prevObject : this.prevObject.filter(e))
		}), e.effects.animateClass = function(n, r, o, u) {
			var a = e.speed(r, o, u);
			return this.queue(function() {
				var r = e(this),
					o = r.attr("class") || "",
					u, f = a.children ? r.find("*").addBack() : r;
				f = f.map(function() {
					var t = e(this);
					return {
						el: t,
						start: i(this)
					}
				}), u = function() {
					e.each(t, function(e, t) {
						n[t] && r[t + "Class"](n[t])
					})
				}, u(), f = f.map(function() {
					return this.end = i(this.el[0]), this.diff = s(this.start, this.end), this
				}), r.attr("class", o), f = f.map(function() {
					var t = this,
						n = e.Deferred(),
						r = e.extend({}, a, {
							queue: !1,
							complete: function() {
								n.resolve(t)
							}
						});
					return this.el.animate(this.diff, r), n.promise()
				}), e.when.apply(e, f.get()).done(function() {
					u(), e.each(arguments, function() {
						var t = this.el;
						e.each(this.diff, function(e) {
							t.css(e, "")
						})
					}), a.complete.call(r[0])
				})
			})
		}, e.fn.extend({
			addClass: function(t) {
				return function(n, r, i, s) {
					return r ? e.effects.animateClass.call(this, {
						add: n
					}, r, i, s) : t.apply(this, arguments)
				}
			}(e.fn.addClass),
			removeClass: function(t) {
				return function(n, r, i, s) {
					return arguments.length > 1 ? e.effects.animateClass.call(this, {
						remove: n
					}, r, i, s) : t.apply(this, arguments)
				}
			}(e.fn.removeClass),
			toggleClass: function(t) {
				return function(n, r, i, s, o) {
					return typeof r == "boolean" || r === undefined ? i ? e.effects.animateClass.call(this, r ? {
						add: n
					} : {
						remove: n
					}, i, s, o) : t.apply(this, arguments) : e.effects.animateClass.call(this, {
						toggle: n
					}, r, i, s)
				}
			}(e.fn.toggleClass),
			switchClass: function(t, n, r, i, s) {
				return e.effects.animateClass.call(this, {
					add: n,
					remove: t
				}, r, i, s)
			}
		})
	}(), function() {
		function n(t, n, r, i) {
			e.isPlainObject(t) && (n = t, t = t.effect), t = {
				effect: t
			}, n == null && (n = {}), e.isFunction(n) && (i = n, r = null, n = {});
			if (typeof n == "number" || e.fx.speeds[n]) i = r, r = n, n = {};
			return e.isFunction(r) && (i = r, r = null), n && e.extend(t, n), r = r || n.duration, t.duration = e.fx.off ? 0 : typeof r == "number" ? r : r in e.fx.speeds ? e.fx.speeds[r] : e.fx.speeds._default, t.complete = i || n.complete, t
		}
		function r(t) {
			return !t || typeof t == "number" || e.fx.speeds[t] ? !0 : typeof t == "string" && !e.effects.effect[t] ? !0 : e.isFunction(t) ? !0 : typeof t == "object" && !t.effect ? !0 : !1
		}
		e.extend(e.effects, {
			version: "1.11.4",
			save: function(e, n) {
				for (var r = 0; r < n.length; r++) n[r] !== null && e.data(t + n[r], e[0].style[n[r]])
			},
			restore: function(e, n) {
				var r, i;
				for (i = 0; i < n.length; i++) n[i] !== null && (r = e.data(t + n[i]), r === undefined && (r = ""), e.css(n[i], r))
			},
			setMode: function(e, t) {
				return t === "toggle" && (t = e.is(":hidden") ? "show" : "hide"), t
			},
			getBaseline: function(e, t) {
				var n, r;
				switch (e[0]) {
				case "top":
					n = 0;
					break;
				case "middle":
					n = .5;
					break;
				case "bottom":
					n = 1;
					break;
				default:
					n = e[0] / t.height
				}
				switch (e[1]) {
				case "left":
					r = 0;
					break;
				case "center":
					r = .5;
					break;
				case "right":
					r = 1;
					break;
				default:
					r = e[1] / t.width
				}
				return {
					x: r,
					y: n
				}
			},
			createWrapper: function(t) {
				if (t.parent().is(".ui-effects-wrapper")) return t.parent();
				var n = {
					width: t.outerWidth(!0),
					height: t.outerHeight(!0),
					"float": t.css("float")
				},
					r = e("<div></div>").addClass("ui-effects-wrapper").css({
						fontSize: "100%",
						background: "transparent",
						border: "none",
						margin: 0,
						padding: 0
					}),
					i = {
						width: t.width(),
						height: t.height()
					},
					s = document.activeElement;
				try {
					s.id
				} catch (o) {
					s = document.body
				}
				return t.wrap(r), (t[0] === s || e.contains(t[0], s)) && e(s).focus(), r = t.parent(), t.css("position") === "static" ? (r.css({
					position: "relative"
				}), t.css({
					position: "relative"
				})) : (e.extend(n, {
					position: t.css("position"),
					zIndex: t.css("z-index")
				}), e.each(["top", "left", "bottom", "right"], function(e, r) {
					n[r] = t.css(r), isNaN(parseInt(n[r], 10)) && (n[r] = "auto")
				}), t.css({
					position: "relative",
					top: 0,
					left: 0,
					right: "auto",
					bottom: "auto"
				})), t.css(i), r.css(n).show()
			},
			removeWrapper: function(t) {
				var n = document.activeElement;
				return t.parent().is(".ui-effects-wrapper") && (t.parent().replaceWith(t), (t[0] === n || e.contains(t[0], n)) && e(n).focus()), t
			},
			setTransition: function(t, n, r, i) {
				return i = i || {}, e.each(n, function(e, n) {
					var s = t.cssUnit(n);
					s[0] > 0 && (i[n] = s[0] * r + s[1])
				}), i
			}
		}), e.fn.extend({
			effect: function() {
				function o(n) {
					function u() {
						e.isFunction(i) && i.call(r[0]), e.isFunction(n) && n()
					}
					var r = e(this),
						i = t.complete,
						o = t.mode;
					(r.is(":hidden") ? o === "hide" : o === "show") ? (r[o](), u()) : s.call(r[0], t, u)
				}
				var t = n.apply(this, arguments),
					r = t.mode,
					i = t.queue,
					s = e.effects.effect[t.effect];
				return e.fx.off || !s ? r ? this[r](t.duration, t.complete) : this.each(function() {
					t.complete && t.complete.call(this)
				}) : i === !1 ? this.each(o) : this.queue(i || "fx", o)
			},
			show: function(e) {
				return function(t) {
					if (r(t)) return e.apply(this, arguments);
					var i = n.apply(this, arguments);
					return i.mode = "show", this.effect.call(this, i)
				}
			}(e.fn.show),
			hide: function(e) {
				return function(t) {
					if (r(t)) return e.apply(this, arguments);
					var i = n.apply(this, arguments);
					return i.mode = "hide", this.effect.call(this, i)
				}
			}(e.fn.hide),
			toggle: function(e) {
				return function(t) {
					if (r(t) || typeof t == "boolean") return e.apply(this, arguments);
					var i = n.apply(this, arguments);
					return i.mode = "toggle", this.effect.call(this, i)
				}
			}(e.fn.toggle),
			cssUnit: function(t) {
				var n = this.css(t),
					r = [];
				return e.each(["em", "px", "%", "pt"], function(e, t) {
					n.indexOf(t) > 0 && (r = [parseFloat(n), t])
				}), r
			}
		})
	}(), function() {
		var t = {};
		e.each(["Quad", "Cubic", "Quart", "Quint", "Expo"], function(e, n) {
			t[n] = function(t) {
				return Math.pow(t, e + 2)
			}
		}), e.extend(t, {
			Sine: function(e) {
				return 1 - Math.cos(e * Math.PI / 2)
			},
			Circ: function(e) {
				return 1 - Math.sqrt(1 - e * e)
			},
			Elastic: function(e) {
				return e === 0 || e === 1 ? e : -Math.pow(2, 8 * (e - 1)) * Math.sin(((e - 1) * 80 - 7.5) * Math.PI / 15)
			},
			Back: function(e) {
				return e * e * (3 * e - 2)
			},
			Bounce: function(e) {
				var t, n = 4;
				while (e < ((t = Math.pow(2, --n)) - 1) / 11);
				return 1 / Math.pow(4, 3 - n) - 7.5625 * Math.pow((t * 3 - 2) / 22 - e, 2)
			}
		}), e.each(t, function(t, n) {
			e.easing["easeIn" + t] = n, e.easing["easeOut" + t] = function(e) {
				return 1 - n(1 - e)
			}, e.easing["easeInOut" + t] = function(e) {
				return e < .5 ? n(e * 2) / 2 : 1 - n(e * -2 + 2) / 2
			}
		})
	}(), e.effects
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery", "./effect"], e) : e(jQuery)
}(function(e) {
	return e.effects.effect.blind = function(t, n) {
		var r = e(this),
			i = /up|down|vertical/,
			s = /up|left|vertical|horizontal/,
			o = ["position", "top", "bottom", "left", "right", "height", "width"],
			u = e.effects.setMode(r, t.mode || "hide"),
			a = t.direction || "up",
			f = i.test(a),
			l = f ? "height" : "width",
			c = f ? "top" : "left",
			h = s.test(a),
			p = {},
			d = u === "show",
			v, m, g;
		r.parent().is(".ui-effects-wrapper") ? e.effects.save(r.parent(), o) : e.effects.save(r, o), r.show(), v = e.effects.createWrapper(r).css({
			overflow: "hidden"
		}), m = v[l](), g = parseFloat(v.css(c)) || 0, p[l] = d ? m : 0, h || (r.css(f ? "bottom" : "right", 0).css(f ? "top" : "left", "auto").css({
			position: "absolute"
		}), p[c] = d ? g : m + g), d && (v.css(l, 0), h || v.css(c, g + m)), v.animate(p, {
			duration: t.duration,
			easing: t.easing,
			queue: !1,
			complete: function() {
				u === "hide" && r.hide(), e.effects.restore(r, o), e.effects.removeWrapper(r), n()
			}
		})
	}
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery", "./effect"], e) : e(jQuery)
}(function(e) {
	return e.effects.effect.bounce = function(t, n) {
		var r = e(this),
			i = ["position", "top", "bottom", "left", "right", "height", "width"],
			s = e.effects.setMode(r, t.mode || "effect"),
			o = s === "hide",
			u = s === "show",
			a = t.direction || "up",
			f = t.distance,
			l = t.times || 5,
			c = l * 2 + (u || o ? 1 : 0),
			h = t.duration / c,
			p = t.easing,
			d = a === "up" || a === "down" ? "top" : "left",
			v = a === "up" || a === "left",
			m, g, y, b = r.queue(),
			w = b.length;
		(u || o) && i.push("opacity"), e.effects.save(r, i), r.show(), e.effects.createWrapper(r), f || (f = r[d === "top" ? "outerHeight" : "outerWidth"]() / 3), u && (y = {
			opacity: 1
		}, y[d] = 0, r.css("opacity", 0).css(d, v ? -f * 2 : f * 2).animate(y, h, p)), o && (f /= Math.pow(2, l - 1)), y = {}, y[d] = 0;
		for (m = 0; m < l; m++) g = {}, g[d] = (v ? "-=" : "+=") + f, r.animate(g, h, p).animate(y, h, p), f = o ? f * 2 : f / 2;
		o && (g = {
			opacity: 0
		}, g[d] = (v ? "-=" : "+=") + f, r.animate(g, h, p)), r.queue(function() {
			o && r.hide(), e.effects.restore(r, i), e.effects.removeWrapper(r), n()
		}), w > 1 && b.splice.apply(b, [1, 0].concat(b.splice(w, c + 1))), r.dequeue()
	}
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery", "./effect"], e) : e(jQuery)
}(function(e) {
	return e.effects.effect.clip = function(t, n) {
		var r = e(this),
			i = ["position", "top", "bottom", "left", "right", "height", "width"],
			s = e.effects.setMode(r, t.mode || "hide"),
			o = s === "show",
			u = t.direction || "vertical",
			a = u === "vertical",
			f = a ? "height" : "width",
			l = a ? "top" : "left",
			c = {},
			h, p, d;
		e.effects.save(r, i), r.show(), h = e.effects.createWrapper(r).css({
			overflow: "hidden"
		}), p = r[0].tagName === "IMG" ? h : r, d = p[f](), o && (p.css(f, 0), p.css(l, d / 2)), c[f] = o ? d : 0, c[l] = o ? 0 : d / 2, p.animate(c, {
			queue: !1,
			duration: t.duration,
			easing: t.easing,
			complete: function() {
				o || r.hide(), e.effects.restore(r, i), e.effects.removeWrapper(r), n()
			}
		})
	}
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery", "./effect"], e) : e(jQuery)
}(function(e) {
	return e.effects.effect.drop = function(t, n) {
		var r = e(this),
			i = ["position", "top", "bottom", "left", "right", "opacity", "height", "width"],
			s = e.effects.setMode(r, t.mode || "hide"),
			o = s === "show",
			u = t.direction || "left",
			a = u === "up" || u === "down" ? "top" : "left",
			f = u === "up" || u === "left" ? "pos" : "neg",
			l = {
				opacity: o ? 1 : 0
			},
			c;
		e.effects.save(r, i), r.show(), e.effects.createWrapper(r), c = t.distance || r[a === "top" ? "outerHeight" : "outerWidth"](!0) / 2, o && r.css("opacity", 0).css(a, f === "pos" ? -c : c), l[a] = (o ? f === "pos" ? "+=" : "-=" : f === "pos" ? "-=" : "+=") + c, r.animate(l, {
			queue: !1,
			duration: t.duration,
			easing: t.easing,
			complete: function() {
				s === "hide" && r.hide(), e.effects.restore(r, i), e.effects.removeWrapper(r), n()
			}
		})
	}
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery", "./effect"], e) : e(jQuery)
}(function(e) {
	return e.effects.effect.explode = function(t, n) {
		function y() {
			c.push(this), c.length === r * i && b()
		}
		function b() {
			s.css({
				visibility: "visible"
			}), e(c).remove(), u || s.hide(), n()
		}
		var r = t.pieces ? Math.round(Math.sqrt(t.pieces)) : 3,
			i = r,
			s = e(this),
			o = e.effects.setMode(s, t.mode || "hide"),
			u = o === "show",
			a = s.show().css("visibility", "hidden").offset(),
			f = Math.ceil(s.outerWidth() / i),
			l = Math.ceil(s.outerHeight() / r),
			c = [],
			h, p, d, v, m, g;
		for (h = 0; h < r; h++) {
			v = a.top + h * l, g = h - (r - 1) / 2;
			for (p = 0; p < i; p++) d = a.left + p * f, m = p - (i - 1) / 2, s.clone().appendTo("body").wrap("<div></div>").css({
				position: "absolute",
				visibility: "visible",
				left: -p * f,
				top: -h * l
			}).parent().addClass("ui-effects-explode").css({
				position: "absolute",
				overflow: "hidden",
				width: f,
				height: l,
				left: d + (u ? m * f : 0),
				top: v + (u ? g * l : 0),
				opacity: u ? 0 : 1
			}).animate({
				left: d + (u ? 0 : m * f),
				top: v + (u ? 0 : g * l),
				opacity: u ? 1 : 0
			}, t.duration || 500, t.easing, y)
		}
	}
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery", "./effect"], e) : e(jQuery)
}(function(e) {
	return e.effects.effect.fade = function(t, n) {
		var r = e(this),
			i = e.effects.setMode(r, t.mode || "toggle");
		r.animate({
			opacity: i
		}, {
			queue: !1,
			duration: t.duration,
			easing: t.easing,
			complete: n
		})
	}
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery", "./effect"], e) : e(jQuery)
}(function(e) {
	return e.effects.effect.fold = function(t, n) {
		var r = e(this),
			i = ["position", "top", "bottom", "left", "right", "height", "width"],
			s = e.effects.setMode(r, t.mode || "hide"),
			o = s === "show",
			u = s === "hide",
			a = t.size || 15,
			f = /([0-9]+)%/.exec(a),
			l = !! t.horizFirst,
			c = o !== l,
			h = c ? ["width", "height"] : ["height", "width"],
			p = t.duration / 2,
			d, v, m = {},
			g = {};
		e.effects.save(r, i), r.show(), d = e.effects.createWrapper(r).css({
			overflow: "hidden"
		}), v = c ? [d.width(), d.height()] : [d.height(), d.width()], f && (a = parseInt(f[1], 10) / 100 * v[u ? 0 : 1]), o && d.css(l ? {
			height: 0,
			width: a
		} : {
			height: a,
			width: 0
		}), m[h[0]] = o ? v[0] : a, g[h[1]] = o ? v[1] : 0, d.animate(m, p, t.easing).animate(g, p, t.easing, function() {
			u && r.hide(), e.effects.restore(r, i), e.effects.removeWrapper(r), n()
		})
	}
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery", "./effect"], e) : e(jQuery)
}(function(e) {
	return e.effects.effect.highlight = function(t, n) {
		var r = e(this),
			i = ["backgroundImage", "backgroundColor", "opacity"],
			s = e.effects.setMode(r, t.mode || "show"),
			o = {
				backgroundColor: r.css("backgroundColor")
			};
		s === "hide" && (o.opacity = 0), e.effects.save(r, i), r.show().css({
			backgroundImage: "none",
			backgroundColor: t.color || "#ffff99"
		}).animate(o, {
			queue: !1,
			duration: t.duration,
			easing: t.easing,
			complete: function() {
				s === "hide" && r.hide(), e.effects.restore(r, i), n()
			}
		})
	}
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery", "./effect"], e) : e(jQuery)
}(function(e) {
	return e.effects.effect.pulsate = function(t, n) {
		var r = e(this),
			i = e.effects.setMode(r, t.mode || "show"),
			s = i === "show",
			o = i === "hide",
			u = s || i === "hide",
			a = (t.times || 5) * 2 + (u ? 1 : 0),
			f = t.duration / a,
			l = 0,
			c = r.queue(),
			h = c.length,
			p;
		if (s || !r.is(":visible")) r.css("opacity", 0).show(), l = 1;
		for (p = 1; p < a; p++) r.animate({
			opacity: l
		}, f, t.easing), l = 1 - l;
		r.animate({
			opacity: l
		}, f, t.easing), r.queue(function() {
			o && r.hide(), n()
		}), h > 1 && c.splice.apply(c, [1, 0].concat(c.splice(h, a + 1))), r.dequeue()
	}
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery", "./effect", "./effect-size"], e) : e(jQuery)
}(function(e) {
	return e.effects.effect.scale = function(t, n) {
		var r = e(this),
			i = e.extend(!0, {}, t),
			s = e.effects.setMode(r, t.mode || "effect"),
			o = parseInt(t.percent, 10) || (parseInt(t.percent, 10) === 0 ? 0 : s === "hide" ? 0 : 100),
			u = t.direction || "both",
			a = t.origin,
			f = {
				height: r.height(),
				width: r.width(),
				outerHeight: r.outerHeight(),
				outerWidth: r.outerWidth()
			},
			l = {
				y: u !== "horizontal" ? o / 100 : 1,
				x: u !== "vertical" ? o / 100 : 1
			};
		i.effect = "size", i.queue = !1, i.complete = n, s !== "effect" && (i.origin = a || ["middle", "center"], i.restore = !0), i.from = t.from || (s === "show" ? {
			height: 0,
			width: 0,
			outerHeight: 0,
			outerWidth: 0
		} : f), i.to = {
			height: f.height * l.y,
			width: f.width * l.x,
			outerHeight: f.outerHeight * l.y,
			outerWidth: f.outerWidth * l.x
		}, i.fade && (s === "show" && (i.from.opacity = 0, i.to.opacity = 1), s === "hide" && (i.from.opacity = 1, i.to.opacity = 0)), r.effect(i)
	}
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery", "./effect"], e) : e(jQuery)
}(function(e) {
	return e.effects.effect.shake = function(t, n) {
		var r = e(this),
			i = ["position", "top", "bottom", "left", "right", "height", "width"],
			s = e.effects.setMode(r, t.mode || "effect"),
			o = t.direction || "left",
			u = t.distance || 20,
			a = t.times || 3,
			f = a * 2 + 1,
			l = Math.round(t.duration / f),
			c = o === "up" || o === "down" ? "top" : "left",
			h = o === "up" || o === "left",
			p = {},
			d = {},
			v = {},
			m, g = r.queue(),
			y = g.length;
		e.effects.save(r, i), r.show(), e.effects.createWrapper(r), p[c] = (h ? "-=" : "+=") + u, d[c] = (h ? "+=" : "-=") + u * 2, v[c] = (h ? "-=" : "+=") + u * 2, r.animate(p, l, t.easing);
		for (m = 1; m < a; m++) r.animate(d, l, t.easing).animate(v, l, t.easing);
		r.animate(d, l, t.easing).animate(p, l / 2, t.easing).queue(function() {
			s === "hide" && r.hide(), e.effects.restore(r, i), e.effects.removeWrapper(r), n()
		}), y > 1 && g.splice.apply(g, [1, 0].concat(g.splice(y, f + 1))), r.dequeue()
	}
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery", "./effect"], e) : e(jQuery)
}(function(e) {
	return e.effects.effect.slide = function(t, n) {
		var r = e(this),
			i = ["position", "top", "bottom", "left", "right", "width", "height"],
			s = e.effects.setMode(r, t.mode || "show"),
			o = s === "show",
			u = t.direction || "left",
			a = u === "up" || u === "down" ? "top" : "left",
			f = u === "up" || u === "left",
			l, c = {};
		e.effects.save(r, i), r.show(), l = t.distance || r[a === "top" ? "outerHeight" : "outerWidth"](!0), e.effects.createWrapper(r).css({
			overflow: "hidden"
		}), o && r.css(a, f ? isNaN(l) ? "-" + l : -l : l), c[a] = (o ? f ? "+=" : "-=" : f ? "-=" : "+=") + l, r.animate(c, {
			queue: !1,
			duration: t.duration,
			easing: t.easing,
			complete: function() {
				s === "hide" && r.hide(), e.effects.restore(r, i), e.effects.removeWrapper(r), n()
			}
		})
	}
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery", "./effect"], e) : e(jQuery)
}(function(e) {
	return e.effects.effect.transfer = function(t, n) {
		var r = e(this),
			i = e(t.to),
			s = i.css("position") === "fixed",
			o = e("body"),
			u = s ? o.scrollTop() : 0,
			a = s ? o.scrollLeft() : 0,
			f = i.offset(),
			l = {
				top: f.top - u,
				left: f.left - a,
				height: i.innerHeight(),
				width: i.innerWidth()
			},
			c = r.offset(),
			h = e("<div class='ui-effects-transfer'></div>").appendTo(document.body).addClass(t.className).css({
				top: c.top - u,
				left: c.left - a,
				height: r.innerHeight(),
				width: r.innerWidth(),
				position: s ? "fixed" : "absolute"
			}).animate(l, t.duration, t.easing, function() {
				h.remove(), n()
			})
	}
}), function() {}.call(this), function(e, t) {
	"use strict";
	e.rails !== t && e.error("jquery-ujs has already been loaded!");
	var n, r = e(document);
	e.rails = n = {
		linkClickSelector: "a[data-confirm], a[data-method], a[data-remote], a[data-disable-with], a[data-disable]",
		buttonClickSelector: "button[data-remote]:not(form button), button[data-confirm]:not(form button)",
		inputChangeSelector: "select[data-remote], input[data-remote], textarea[data-remote]",
		formSubmitSelector: "form",
		formInputClickSelector: "form input[type=submit], form input[type=image], form button[type=submit], form button:not([type]), input[type=submit][form], input[type=image][form], button[type=submit][form], button[form]:not([type])",
		disableSelector: "input[data-disable-with]:enabled, button[data-disable-with]:enabled, textarea[data-disable-with]:enabled, input[data-disable]:enabled, button[data-disable]:enabled, textarea[data-disable]:enabled",
		enableSelector: "input[data-disable-with]:disabled, button[data-disable-with]:disabled, textarea[data-disable-with]:disabled, input[data-disable]:disabled, button[data-disable]:disabled, textarea[data-disable]:disabled",
		requiredInputSelector: "input[name][required]:not([disabled]),textarea[name][required]:not([disabled])",
		fileInputSelector: "input[type=file]",
		linkDisableSelector: "a[data-disable-with], a[data-disable]",
		buttonDisableSelector: "button[data-remote][data-disable-with], button[data-remote][data-disable]",
		csrfToken: function() {
			return e("meta[name=csrf-token]").attr("content")
		},
		csrfParam: function() {
			return e("meta[name=csrf-param]").attr("content")
		},
		CSRFProtection: function(e) {
			var t = n.csrfToken();
			t && e.setRequestHeader("X-CSRF-Token", t)
		},
		refreshCSRFTokens: function() {
			e('form input[name="' + n.csrfParam() + '"]').val(n.csrfToken())
		},
		fire: function(t, n, r) {
			var i = e.Event(n);
			return t.trigger(i, r), i.result !== !1
		},
		confirm: function(e) {
			return confirm(e)
		},
		ajax: function(t) {
			return e.ajax(t)
		},
		href: function(e) {
			return e[0].href
		},
		isRemote: function(e) {
			return e.data("remote") !== t && e.data("remote") !== !1
		},
		handleRemote: function(r) {
			var i, s, o, u, a, f;
			if (n.fire(r, "ajax:before")) {
				u = r.data("with-credentials") || null, a = r.data("type") || e.ajaxSettings && e.ajaxSettings.dataType;
				if (r.is("form")) {
					i = r.attr("method"), s = r.attr("action"), o = r.serializeArray();
					var l = r.data("ujs:submit-button");
					l && (o.push(l), r.data("ujs:submit-button", null))
				} else r.is(n.inputChangeSelector) ? (i = r.data("method"), s = r.data("url"), o = r.serialize(), r.data("params") && (o = o + "&" + r.data("params"))) : r.is(n.buttonClickSelector) ? (i = r.data("method") || "get", s = r.data("url"), o = r.serialize(), r.data("params") && (o = o + "&" + r.data("params"))) : (i = r.data("method"), s = n.href(r), o = r.data("params") || null);
				return f = {
					type: i || "GET",
					data: o,
					dataType: a,
					beforeSend: function(e, i) {
						i.dataType === t && e.setRequestHeader("accept", "*/*;q=0.5, " + i.accepts.script);
						if (!n.fire(r, "ajax:beforeSend", [e, i])) return !1;
						r.trigger("ajax:send", e)
					},
					success: function(e, t, n) {
						r.trigger("ajax:success", [e, t, n])
					},
					complete: function(e, t) {
						r.trigger("ajax:complete", [e, t])
					},
					error: function(e, t, n) {
						r.trigger("ajax:error", [e, t, n])
					},
					crossDomain: n.isCrossDomain(s)
				}, u && (f.xhrFields = {
					withCredentials: u
				}), s && (f.url = s), n.ajax(f)
			}
			return !1
		},
		isCrossDomain: function(e) {
			var t = document.createElement("a");
			t.href = location.href;
			var n = document.createElement("a");
			try {
				return n.href = e, n.href = n.href, !n.protocol || !n.host || t.protocol + "//" + t.host != n.protocol + "//" + n.host
			} catch (r) {
				return !0
			}
		},
		handleMethod: function(r) {
			var i = n.href(r),
				s = r.data("method"),
				o = r.attr("target"),
				u = n.csrfToken(),
				a = n.csrfParam(),
				f = e('<form method="post" action="' + i + '"></form>'),
				l = '<input name="_method" value="' + s + '" type="hidden" />';
			a !== t && u !== t && !n.isCrossDomain(i) && (l += '<input name="' + a + '" value="' + u + '" type="hidden" />'), o && f.attr("target", o), f.hide().append(l).appendTo("body"), f.submit()
		},
		formElements: function(t, n) {
			return t.is("form") ? e(t[0].elements).filter(n) : t.find(n)
		},
		disableFormElements: function(t) {
			n.formElements(t, n.disableSelector).each(function() {
				n.disableFormElement(e(this))
			})
		},
		disableFormElement: function(e) {
			var n, r;
			n = e.is("button") ? "html" : "val", r = e.data("disable-with"), e.data("ujs:enable-with", e[n]()), r !== t && e[n](r), e.prop("disabled", !0)
		},
		enableFormElements: function(t) {
			n.formElements(t, n.enableSelector).each(function() {
				n.enableFormElement(e(this))
			})
		},
		enableFormElement: function(e) {
			var t = e.is("button") ? "html" : "val";
			e.data("ujs:enable-with") && e[t](e.data("ujs:enable-with")), e.prop("disabled", !1)
		},
		allowAction: function(e) {
			var t = e.data("confirm"),
				r = !1,
				i;
			if (!t) return !0;
			if (n.fire(e, "confirm")) {
				try {
					r = n.confirm(t)
				} catch (s) {
					(console.error || console.log).call(console, s.stack || s)
				}
				i = n.fire(e, "confirm:complete", [r])
			}
			return r && i
		},
		blankInputs: function(t, n, r) {
			var i = e(),
				s, o, u = n || "input,textarea",
				a = t.find(u);
			return a.each(function() {
				s = e(this), o = s.is("input[type=checkbox],input[type=radio]") ? s.is(":checked") : !! s.val();
				if (o === r) {
					if (s.is("input[type=radio]") && a.filter('input[type=radio]:checked[name="' + s.attr("name") + '"]').length) return !0;
					i = i.add(s)
				}
			}), i.length ? i : !1
		},
		nonBlankInputs: function(e, t) {
			return n.blankInputs(e, t, !0)
		},
		stopEverything: function(t) {
			return e(t.target).trigger("ujs:everythingStopped"), t.stopImmediatePropagation(), !1
		},
		disableElement: function(e) {
			var r = e.data("disable-with");
			e.data("ujs:enable-with", e.html()), r !== t && e.html(r), e.bind("click.railsDisable", function(e) {
				return n.stopEverything(e)
			})
		},
		enableElement: function(e) {
			e.data("ujs:enable-with") !== t && (e.html(e.data("ujs:enable-with")), e.removeData("ujs:enable-with")), e.unbind("click.railsDisable")
		}
	}, n.fire(r, "rails:attachBindings") && (e.ajaxPrefilter(function(e, t, r) {
		e.crossDomain || n.CSRFProtection(r)
	}), e(window).on("pageshow.rails", function() {
		e(e.rails.enableSelector).each(function() {
			var t = e(this);
			t.data("ujs:enable-with") && e.rails.enableFormElement(t)
		}), e(e.rails.linkDisableSelector).each(function() {
			var t = e(this);
			t.data("ujs:enable-with") && e.rails.enableElement(t)
		})
	}), r.delegate(n.linkDisableSelector, "ajax:complete", function() {
		n.enableElement(e(this))
	}), r.delegate(n.buttonDisableSelector, "ajax:complete", function() {
		n.enableFormElement(e(this))
	}), r.delegate(n.linkClickSelector, "click.rails", function(t) {
		var r = e(this),
			i = r.data("method"),
			s = r.data("params"),
			o = t.metaKey || t.ctrlKey;
		if (!n.allowAction(r)) return n.stopEverything(t);
		!o && r.is(n.linkDisableSelector) && n.disableElement(r);
		if (n.isRemote(r)) {
			if (o && (!i || i === "GET") && !s) return !0;
			var u = n.handleRemote(r);
			return u === !1 ? n.enableElement(r) : u.fail(function() {
				n.enableElement(r)
			}), !1
		}
		if (i) return n.handleMethod(r), !1
	}), r.delegate(n.buttonClickSelector, "click.rails", function(t) {
		var r = e(this);
		if (!n.allowAction(r) || !n.isRemote(r)) return n.stopEverything(t);
		r.is(n.buttonDisableSelector) && n.disableFormElement(r);
		var i = n.handleRemote(r);
		return i === !1 ? n.enableFormElement(r) : i.fail(function() {
			n.enableFormElement(r)
		}), !1
	}), r.delegate(n.inputChangeSelector, "change.rails", function(t) {
		var r = e(this);
		return !n.allowAction(r) || !n.isRemote(r) ? n.stopEverything(t) : (n.handleRemote(r), !1)
	}), r.delegate(n.formSubmitSelector, "submit.rails", function(r) {
		var i = e(this),
			s = n.isRemote(i),
			o, u;
		if (!n.allowAction(i)) return n.stopEverything(r);
		if (i.attr("novalidate") === t) {
			o = n.blankInputs(i, n.requiredInputSelector, !1);
			if (o && n.fire(i, "ajax:aborted:required", [o])) return n.stopEverything(r)
		}
		if (s) {
			u = n.nonBlankInputs(i, n.fileInputSelector);
			if (u) {
				setTimeout(function() {
					n.disableFormElements(i)
				}, 13);
				var a = n.fire(i, "ajax:aborted:file", [u]);
				return a || setTimeout(function() {
					n.enableFormElements(i)
				}, 13), a
			}
			return n.handleRemote(i), !1
		}
		setTimeout(function() {
			n.disableFormElements(i)
		}, 13)
	}), r.delegate(n.formInputClickSelector, "click.rails", function(t) {
		var r = e(this);
		if (!n.allowAction(r)) return n.stopEverything(t);
		var i = r.attr("name"),
			s = i ? {
				name: i,
				value: r.val()
			} : null;
		r.closest("form").data("ujs:submit-button", s)
	}), r.delegate(n.formSubmitSelector, "ajax:send.rails", function(t) {
		this === t.target && n.disableFormElements(e(this))
	}), r.delegate(n.formSubmitSelector, "ajax:complete.rails", function(t) {
		this === t.target && n.enableFormElements(e(this))
	}), e(function() {
		n.refreshCSRFTokens()
	}))
}(jQuery), function(e) {
	"use strict";
	typeof define == "function" && define.amd ? define(["jquery"], e) : typeof exports == "object" ? e(require("jquery")) : e(window.jQuery)
}(function(e) {
	"use strict";
	var t = 0;
	e.ajaxTransport("iframe", function(n) {
		if (n.async) {
			var r = n.initialIframeSrc || "javascript:false;",
				i, s, o;
			return {
				send: function(u, a) {
					i = e('<form style="display:none;"></form>'), i.attr("accept-charset", n.formAcceptCharset), o = /\?/.test(n.url) ? "&" : "?", n.type === "DELETE" ? (n.url = n.url + o + "_method=DELETE", n.type = "POST") : n.type === "PUT" ? (n.url = n.url + o + "_method=PUT", n.type = "POST") : n.type === "PATCH" && (n.url = n.url + o + "_method=PATCH", n.type = "POST"), t += 1, s = e('<iframe src="' + r + '" name="iframe-transport-' + t + '"></iframe>').bind("load", function() {
						var t, o = e.isArray(n.paramName) ? n.paramName : [n.paramName];
						s.unbind("load").bind("load", function() {
							var t;
							try {
								t = s.contents();
								if (!t.length || !t[0].firstChild) throw new Error
							} catch (n) {
								t = undefined
							}
							a(200, "success", {
								iframe: t
							}), e('<iframe src="' + r + '"></iframe>').appendTo(i), window.setTimeout(function() {
								i.remove()
							}, 0)
						}), i.prop("target", s.prop("name")).prop("action", n.url).prop("method", n.type), n.formData && e.each(n.formData, function(t, n) {
							e('<input type="hidden"/>').prop("name", n.name).val(n.value).appendTo(i)
						}), n.fileInput && n.fileInput.length && n.type === "POST" && (t = n.fileInput.clone(), n.fileInput.after(function(e) {
							return t[e]
						}), n.paramName && n.fileInput.each(function(t) {
							e(this).prop("name", o[t] || n.paramName)
						}), i.append(n.fileInput).prop("enctype", "multipart/form-data").prop("encoding", "multipart/form-data"), n.fileInput.removeAttr("form")), i.submit(), t && t.length && n.fileInput.each(function(n, r) {
							var i = e(t[n]);
							e(r).prop("name", i.prop("name")).attr("form", i.attr("form")), i.replaceWith(r)
						})
					}), i.append(s).appendTo(document.body)
				},
				abort: function() {
					s && s.unbind("load").prop("src", r), i && i.remove()
				}
			}
		}
	}), e.ajaxSetup({
		converters: {
			"iframe text": function(t) {
				return t && e(t[0].body).text()
			},
			"iframe json": function(t) {
				return t && e.parseJSON(e(t[0].body).text())
			},
			"iframe html": function(t) {
				return t && e(t[0].body).html()
			},
			"iframe xml": function(t) {
				var n = t && t[0];
				return n && e.isXMLDoc(n) ? n : e.parseXML(n.XMLDocument && n.XMLDocument.xml || e(n.body).html())
			},
			"iframe script": function(t) {
				return t && e.globalEval(e(t[0].body).text())
			}
		}
	})
}), function(e) {
	"use strict";
	typeof define == "function" && define.amd ? define(["jquery", "jquery.ui.widget"], e) : typeof exports == "object" ? e(require("jquery"), require("./vendor/jquery.ui.widget")) : e(window.jQuery)
}(function(e) {
	"use strict";

	function t(t) {
		var n = t === "dragover";
		return function(r) {
			r.dataTransfer = r.originalEvent && r.originalEvent.dataTransfer;
			var i = r.dataTransfer;
			i && e.inArray("Files", i.types) !== -1 && this._trigger(t, e.Event(t, {
				delegatedEvent: r
			})) !== !1 && (r.preventDefault(), n && (i.dropEffect = "copy"))
		}
	}
	e.support.fileInput = !(new RegExp("(Android (1\\.[0156]|2\\.[01]))|(Windows Phone (OS 7|8\\.0))|(XBLWP)|(ZuneWP)|(WPDesktop)|(w(eb)?OSBrowser)|(webOS)|(Kindle/(1\\.0|2\\.[05]|3\\.0))")).test(window.navigator.userAgent) && !e('<input type="file">').prop("disabled"), e.support.xhrFileUpload = !! window.ProgressEvent && !! window.FileReader, e.support.xhrFormDataFileUpload = !! window.FormData, e.support.blobSlice = window.Blob && (Blob.prototype.slice || Blob.prototype.webkitSlice || Blob.prototype.mozSlice), e.widget("blueimp.fileupload", {
		options: {
			dropZone: e(document),
			pasteZone: undefined,
			fileInput: undefined,
			replaceFileInput: !0,
			paramName: undefined,
			singleFileUploads: !0,
			limitMultiFileUploads: undefined,
			limitMultiFileUploadSize: undefined,
			limitMultiFileUploadSizeOverhead: 512,
			sequentialUploads: !1,
			limitConcurrentUploads: undefined,
			forceIframeTransport: !1,
			redirect: undefined,
			redirectParamName: undefined,
			postMessage: undefined,
			multipart: !0,
			maxChunkSize: undefined,
			uploadedBytes: undefined,
			recalculateProgress: !0,
			progressInterval: 100,
			bitrateInterval: 500,
			autoUpload: !0,
			messages: {
				uploadedBytes: "Uploaded bytes exceed file size"
			},
			i18n: function(t, n) {
				return t = this.messages[t] || t.toString(), n && e.each(n, function(e, n) {
					t = t.replace("{" + e + "}", n)
				}), t
			},
			formData: function(e) {
				return e.serializeArray()
			},
			add: function(t, n) {
				if (t.isDefaultPrevented()) return !1;
				(n.autoUpload || n.autoUpload !== !1 && e(this).fileupload("option", "autoUpload")) && n.process().done(function() {
					n.submit()
				})
			},
			processData: !1,
			contentType: !1,
			cache: !1,
			timeout: 0
		},
		_specialOptions: ["fileInput", "dropZone", "pasteZone", "multipart", "forceIframeTransport"],
		_blobSlice: e.support.blobSlice &&
		function() {
			var e = this.slice || this.webkitSlice || this.mozSlice;
			return e.apply(this, arguments)
		},
		_BitrateTimer: function() {
			this.timestamp = Date.now ? Date.now() : (new Date).getTime(), this.loaded = 0, this.bitrate = 0, this.getBitrate = function(e, t, n) {
				var r = e - this.timestamp;
				if (!this.bitrate || !n || r > n) this.bitrate = (t - this.loaded) * (1e3 / r) * 8, this.loaded = t, this.timestamp = e;
				return this.bitrate
			}
		},
		_isXHRUpload: function(t) {
			return !t.forceIframeTransport && (!t.multipart && e.support.xhrFileUpload || e.support.xhrFormDataFileUpload)
		},
		_getFormData: function(t) {
			var n;
			return e.type(t.formData) === "function" ? t.formData(t.form) : e.isArray(t.formData) ? t.formData : e.type(t.formData) === "object" ? (n = [], e.each(t.formData, function(e, t) {
				n.push({
					name: e,
					value: t
				})
			}), n) : []
		},
		_getTotal: function(t) {
			var n = 0;
			return e.each(t, function(e, t) {
				n += t.size || 1
			}), n
		},
		_initProgressObject: function(t) {
			var n = {
				loaded: 0,
				total: 0,
				bitrate: 0
			};
			t._progress ? e.extend(t._progress, n) : t._progress = n
		},
		_initResponseObject: function(e) {
			var t;
			if (e._response) for (t in e._response) e._response.hasOwnProperty(t) && delete e._response[t];
			else e._response = {}
		},
		_onProgress: function(t, n) {
			if (t.lengthComputable) {
				var r = Date.now ? Date.now() : (new Date).getTime(),
					i;
				if (n._time && n.progressInterval && r - n._time < n.progressInterval && t.loaded !== t.total) return;
				n._time = r, i = Math.floor(t.loaded / t.total * (n.chunkSize || n._progress.total)) + (n.uploadedBytes || 0), this._progress.loaded += i - n._progress.loaded, this._progress.bitrate = this._bitrateTimer.getBitrate(r, this._progress.loaded, n.bitrateInterval), n._progress.loaded = n.loaded = i, n._progress.bitrate = n.bitrate = n._bitrateTimer.getBitrate(r, i, n.bitrateInterval), this._trigger("progress", e.Event("progress", {
					delegatedEvent: t
				}), n), this._trigger("progressall", e.Event("progressall", {
					delegatedEvent: t
				}), this._progress)
			}
		},
		_initProgressListener: function(t) {
			var n = this,
				r = t.xhr ? t.xhr() : e.ajaxSettings.xhr();
			r.upload && (e(r.upload).bind("progress", function(e) {
				var r = e.originalEvent;
				e.lengthComputable = r.lengthComputable, e.loaded = r.loaded, e.total = r.total, n._onProgress(e, t)
			}), t.xhr = function() {
				return r
			})
		},
		_isInstanceOf: function(e, t) {
			return Object.prototype.toString.call(t) === "[object " + e + "]"
		},
		_initXHRData: function(t) {
			var n = this,
				r, i = t.files[0],
				s = t.multipart || !e.support.xhrFileUpload,
				o = e.type(t.paramName) === "array" ? t.paramName[0] : t.paramName;
			t.headers = e.extend({}, t.headers), t.contentRange && (t.headers["Content-Range"] = t.contentRange);
			if (!s || t.blob || !this._isInstanceOf("File", i)) t.headers["Content-Disposition"] = 'attachment; filename="' + encodeURI(i.name) + '"';
			s ? e.support.xhrFormDataFileUpload && (t.postMessage ? (r = this._getFormData(t), t.blob ? r.push({
				name: o,
				value: t.blob
			}) : e.each(t.files, function(n, i) {
				r.push({
					name: e.type(t.paramName) === "array" && t.paramName[n] || o,
					value: i
				})
			})) : (n._isInstanceOf("FormData", t.formData) ? r = t.formData : (r = new FormData, e.each(this._getFormData(t), function(e, t) {
				r.append(t.name, t.value)
			})), t.blob ? r.append(o, t.blob, i.name) : e.each(t.files, function(i, s) {
				(n._isInstanceOf("File", s) || n._isInstanceOf("Blob", s)) && r.append(e.type(t.paramName) === "array" && t.paramName[i] || o, s, s.uploadName || s.name)
			})), t.data = r) : (t.contentType = i.type || "application/octet-stream", t.data = t.blob || i), t.blob = null
		},
		_initIframeSettings: function(t) {
			var n = e("<a></a>").prop("href", t.url).prop("host");
			t.dataType = "iframe " + (t.dataType || ""), t.formData = this._getFormData(t), t.redirect && n && n !== location.host && t.formData.push({
				name: t.redirectParamName || "redirect",
				value: t.redirect
			})
		},
		_initDataSettings: function(e) {
			this._isXHRUpload(e) ? (this._chunkedUpload(e, !0) || (e.data || this._initXHRData(e), this._initProgressListener(e)), e.postMessage && (e.dataType = "postmessage " + (e.dataType || ""))) : this._initIframeSettings(e)
		},
		_getParamName: function(t) {
			var n = e(t.fileInput),
				r = t.paramName;
			return r ? e.isArray(r) || (r = [r]) : (r = [], n.each(function() {
				var t = e(this),
					n = t.prop("name") || "files[]",
					i = (t.prop("files") || [1]).length;
				while (i) r.push(n), i -= 1
			}), r.length || (r = [n.prop("name") || "files[]"])), r
		},
		_initFormSettings: function(t) {
			if (!t.form || !t.form.length) t.form = e(t.fileInput.prop("form")), t.form.length || (t.form = e(this.options.fileInput.prop("form")));
			t.paramName = this._getParamName(t), t.url || (t.url = t.form.prop("action") || location.href), t.type = (t.type || e.type(t.form.prop("method")) === "string" && t.form.prop("method") || "").toUpperCase(), t.type !== "POST" && t.type !== "PUT" && t.type !== "PATCH" && (t.type = "POST"), t.formAcceptCharset || (t.formAcceptCharset = t.form.attr("accept-charset"))
		},
		_getAJAXSettings: function(t) {
			var n = e.extend({}, this.options, t);
			return this._initFormSettings(n), this._initDataSettings(n), n
		},
		_getDeferredState: function(e) {
			return e.state ? e.state() : e.isResolved() ? "resolved" : e.isRejected() ? "rejected" : "pending"
		},
		_enhancePromise: function(e) {
			return e.success = e.done, e.error = e.fail, e.complete = e.always, e
		},
		_getXHRPromise: function(t, n, r) {
			var i = e.Deferred(),
				s = i.promise();
			return n = n || this.options.context || s, t === !0 ? i.resolveWith(n, r) : t === !1 && i.rejectWith(n, r), s.abort = i.promise, this._enhancePromise(s)
		},
		_addConvenienceMethods: function(t, n) {
			var r = this,
				i = function(t) {
					return e.Deferred().resolveWith(r, t).promise()
				};
			n.process = function(t, s) {
				if (t || s) n._processQueue = this._processQueue = (this._processQueue || i([this])).pipe(function() {
					return n.errorThrown ? e.Deferred().rejectWith(r, [n]).promise() : i(arguments)
				}).pipe(t, s);
				return this._processQueue || i([this])
			}, n.submit = function() {
				return this.state() !== "pending" && (n.jqXHR = this.jqXHR = r._trigger("submit", e.Event("submit", {
					delegatedEvent: t
				}), this) !== !1 && r._onSend(t, this)), this.jqXHR || r._getXHRPromise()
			}, n.abort = function() {
				return this.jqXHR ? this.jqXHR.abort() : (this.errorThrown = "abort", r._trigger("fail", null, this), r._getXHRPromise(!1))
			}, n.state = function() {
				if (this.jqXHR) return r._getDeferredState(this.jqXHR);
				if (this._processQueue) return r._getDeferredState(this._processQueue)
			}, n.processing = function() {
				return !this.jqXHR && this._processQueue && r._getDeferredState(this._processQueue) === "pending"
			}, n.progress = function() {
				return this._progress
			}, n.response = function() {
				return this._response
			}
		},
		_getUploadedBytes: function(e) {
			var t = e.getResponseHeader("Range"),
				n = t && t.split("-"),
				r = n && n.length > 1 && parseInt(n[1], 10);
			return r && r + 1
		},
		_chunkedUpload: function(t, n) {
			t.uploadedBytes = t.uploadedBytes || 0;
			var r = this,
				i = t.files[0],
				s = i.size,
				o = t.uploadedBytes,
				u = t.maxChunkSize || s,
				a = this._blobSlice,
				f = e.Deferred(),
				l = f.promise(),
				c, h;
			return !(this._isXHRUpload(t) && a && (o || u < s)) || t.data ? !1 : n ? !0 : o >= s ? (i.error = t.i18n("uploadedBytes"), this._getXHRPromise(!1, t.context, [null, "error", i.error])) : (h = function() {
				var n = e.extend({}, t),
					l = n._progress.loaded;
				n.blob = a.call(i, o, o + u, i.type), n.chunkSize = n.blob.size, n.contentRange = "bytes " + o + "-" + (o + n.chunkSize - 1) + "/" + s, r._initXHRData(n), r._initProgressListener(n), c = (r._trigger("chunksend", null, n) !== !1 && e.ajax(n) || r._getXHRPromise(!1, n.context)).done(function(i, u, a) {
					o = r._getUploadedBytes(a) || o + n.chunkSize, l + n.chunkSize - n._progress.loaded && r._onProgress(e.Event("progress", {
						lengthComputable: !0,
						loaded: o - n.uploadedBytes,
						total: o - n.uploadedBytes
					}), n), t.uploadedBytes = n.uploadedBytes = o, n.result = i, n.textStatus = u, n.jqXHR = a, r._trigger("chunkdone", null, n), r._trigger("chunkalways", null, n), o < s ? h() : f.resolveWith(n.context, [i, u, a])
				}).fail(function(e, t, i) {
					n.jqXHR = e, n.textStatus = t, n.errorThrown = i, r._trigger("chunkfail", null, n), r._trigger("chunkalways", null, n), f.rejectWith(n.context, [e, t, i])
				})
			}, this._enhancePromise(l), l.abort = function() {
				return c.abort()
			}, h(), l)
		},
		_beforeSend: function(e, t) {
			this._active === 0 && (this._trigger("start"), this._bitrateTimer = new this._BitrateTimer, this._progress.loaded = this._progress.total = 0, this._progress.bitrate = 0), this._initResponseObject(t), this._initProgressObject(t), t._progress.loaded = t.loaded = t.uploadedBytes || 0, t._progress.total = t.total = this._getTotal(t.files) || 1, t._progress.bitrate = t.bitrate = 0, this._active += 1, this._progress.loaded += t.loaded, this._progress.total += t.total
		},
		_onDone: function(t, n, r, i) {
			var s = i._progress.total,
				o = i._response;
			i._progress.loaded < s && this._onProgress(e.Event("progress", {
				lengthComputable: !0,
				loaded: s,
				total: s
			}), i), o.result = i.result = t, o.textStatus = i.textStatus = n, o.jqXHR = i.jqXHR = r, this._trigger("done", null, i)
		},
		_onFail: function(e, t, n, r) {
			var i = r._response;
			r.recalculateProgress && (this._progress.loaded -= r._progress.loaded, this._progress.total -= r._progress.total), i.jqXHR = r.jqXHR = e, i.textStatus = r.textStatus = t, i.errorThrown = r.errorThrown = n, this._trigger("fail", null, r)
		},
		_onAlways: function(e, t, n, r) {
			this._trigger("always", null, r)
		},
		_onSend: function(t, n) {
			n.submit || this._addConvenienceMethods(t, n);
			var r = this,
				i, s, o, u, a = r._getAJAXSettings(n),
				f = function() {
					return r._sending += 1, a._bitrateTimer = new r._BitrateTimer, i = i || ((s || r._trigger("send", e.Event("send", {
						delegatedEvent: t
					}), a) === !1) && r._getXHRPromise(!1, a.context, s) || r._chunkedUpload(a) || e.ajax(a)).done(function(e, t, n) {
						r._onDone(e, t, n, a)
					}).fail(function(e, t, n) {
						r._onFail(e, t, n, a)
					}).always(function(e, t, n) {
						r._onAlways(e, t, n, a), r._sending -= 1, r._active -= 1;
						if (a.limitConcurrentUploads && a.limitConcurrentUploads > r._sending) {
							var i = r._slots.shift();
							while (i) {
								if (r._getDeferredState(i) === "pending") {
									i.resolve();
									break
								}
								i = r._slots.shift()
							}
						}
						r._active === 0 && r._trigger("stop")
					}), i
				};
			return this._beforeSend(t, a), this.options.sequentialUploads || this.options.limitConcurrentUploads && this.options.limitConcurrentUploads <= this._sending ? (this.options.limitConcurrentUploads > 1 ? (o = e.Deferred(), this._slots.push(o), u = o.pipe(f)) : (this._sequence = this._sequence.pipe(f, f), u = this._sequence), u.abort = function() {
				return s = [undefined, "abort", "abort"], i ? i.abort() : (o && o.rejectWith(a.context, s), f())
			}, this._enhancePromise(u)) : f()
		},
		_onAdd: function(t, n) {
			var r = this,
				i = !0,
				s = e.extend({}, this.options, n),
				o = n.files,
				u = o.length,
				a = s.limitMultiFileUploads,
				f = s.limitMultiFileUploadSize,
				l = s.limitMultiFileUploadSizeOverhead,
				c = 0,
				h = this._getParamName(s),
				p, d, v, m, g = 0;
			if (!u) return !1;
			f && o[0].size === undefined && (f = undefined);
			if (!(s.singleFileUploads || a || f) || !this._isXHRUpload(s)) v = [o], p = [h];
			else if (!s.singleFileUploads && !f && a) {
				v = [], p = [];
				for (m = 0; m < u; m += a) v.push(o.slice(m, m + a)), d = h.slice(m, m + a), d.length || (d = h), p.push(d)
			} else if (!s.singleFileUploads && f) {
				v = [], p = [];
				for (m = 0; m < u; m += 1) {
					c += o[m].size + l;
					if (m + 1 === u || c + o[m + 1].size + l > f || a && m + 1 - g >= a) v.push(o.slice(g, m + 1)), d = h.slice(g, m + 1), d.length || (d = h), p.push(d), g = m + 1, c = 0
				}
			} else p = h;
			return n.originalFiles = o, e.each(v || o, function(s, o) {
				var u = e.extend({}, n);
				return u.files = v ? o : [o], u.paramName = p[s], r._initResponseObject(u), r._initProgressObject(u), r._addConvenienceMethods(t, u), i = r._trigger("add", e.Event("add", {
					delegatedEvent: t
				}), u), i
			}), i
		},
		_replaceFileInput: function(t) {
			var n = t.fileInput,
				r = n.clone(!0);
			t.fileInputClone = r, e("<form></form>").append(r)[0].reset(), n.after(r).detach(), e.cleanData(n.unbind("remove")), this.options.fileInput = this.options.fileInput.map(function(e, t) {
				return t === n[0] ? r[0] : t
			}), n[0] === this.element[0] && (this.element = r)
		},
		_handleFileTreeEntry: function(t, n) {
			var r = this,
				i = e.Deferred(),
				s = function(e) {
					e && !e.entry && (e.entry = t), i.resolve([e])
				},
				o = function(e) {
					r._handleFileTreeEntries(e, n + t.name + "/").done(function(e) {
						i.resolve(e)
					}).fail(s)
				},
				u = function() {
					a.readEntries(function(e) {
						e.length ? (f = f.concat(e), u()) : o(f)
					}, s)
				},
				a, f = [];
			return n = n || "", t.isFile ? t._file ? (t._file.relativePath = n, i.resolve(t._file)) : t.file(function(e) {
				e.relativePath = n, i.resolve(e)
			}, s) : t.isDirectory ? (a = t.createReader(), u()) : i.resolve([]), i.promise()
		},
		_handleFileTreeEntries: function(t, n) {
			var r = this;
			return e.when.apply(e, e.map(t, function(e) {
				return r._handleFileTreeEntry(e, n)
			})).pipe(function() {
				return Array.prototype.concat.apply([], arguments)
			})
		},
		_getDroppedFiles: function(t) {
			t = t || {};
			var n = t.items;
			return n && n.length && (n[0].webkitGetAsEntry || n[0].getAsEntry) ? this._handleFileTreeEntries(e.map(n, function(e) {
				var t;
				return e.webkitGetAsEntry ? (t = e.webkitGetAsEntry(), t && (t._file = e.getAsFile()), t) : e.getAsEntry()
			})) : e.Deferred().resolve(e.makeArray(t.files)).promise()
		},
		_getSingleFileInputFiles: function(t) {
			t = e(t);
			var n = t.prop("webkitEntries") || t.prop("entries"),
				r, i;
			if (n && n.length) return this._handleFileTreeEntries(n);
			r = e.makeArray(t.prop("files"));
			if (!r.length) {
				i = t.prop("value");
				if (!i) return e.Deferred().resolve([]).promise();
				r = [{
					name: i.replace(/^.*\\/, "")
				}]
			} else r[0].name === undefined && r[0].fileName && e.each(r, function(e, t) {
				t.name = t.fileName, t.size = t.fileSize
			});
			return e.Deferred().resolve(r).promise()
		},
		_getFileInputFiles: function(t) {
			return t instanceof e && t.length !== 1 ? e.when.apply(e, e.map(t, this._getSingleFileInputFiles)).pipe(function() {
				return Array.prototype.concat.apply([], arguments)
			}) : this._getSingleFileInputFiles(t)
		},
		_onChange: function(t) {
			var n = this,
				r = {
					fileInput: e(t.target),
					form: e(t.target.form)
				};
			this._getFileInputFiles(r.fileInput).always(function(i) {
				r.files = i, n.options.replaceFileInput && n._replaceFileInput(r), n._trigger("change", e.Event("change", {
					delegatedEvent: t
				}), r) !== !1 && n._onAdd(t, r)
			})
		},
		_onPaste: function(t) {
			var n = t.originalEvent && t.originalEvent.clipboardData && t.originalEvent.clipboardData.items,
				r = {
					files: []
				};
			n && n.length && (e.each(n, function(e, t) {
				var n = t.getAsFile && t.getAsFile();
				n && r.files.push(n)
			}), this._trigger("paste", e.Event("paste", {
				delegatedEvent: t
			}), r) !== !1 && this._onAdd(t, r))
		},
		_onDrop: function(t) {
			t.dataTransfer = t.originalEvent && t.originalEvent.dataTransfer;
			var n = this,
				r = t.dataTransfer,
				i = {};
			r && r.files && r.files.length && (t.preventDefault(), this._getDroppedFiles(r).always(function(r) {
				i.files = r, n._trigger("drop", e.Event("drop", {
					delegatedEvent: t
				}), i) !== !1 && n._onAdd(t, i)
			}))
		},
		_onDragOver: t("dragover"),
		_onDragEnter: t("dragenter"),
		_onDragLeave: t("dragleave"),
		_initEventHandlers: function() {
			this._isXHRUpload(this.options) && (this._on(this.options.dropZone, {
				dragover: this._onDragOver,
				drop: this._onDrop,
				dragenter: this._onDragEnter,
				dragleave: this._onDragLeave
			}), this._on(this.options.pasteZone, {
				paste: this._onPaste
			})), e.support.fileInput && this._on(this.options.fileInput, {
				change: this._onChange
			})
		},
		_destroyEventHandlers: function() {
			this._off(this.options.dropZone, "dragenter dragleave dragover drop"), this._off(this.options.pasteZone, "paste"), this._off(this.options.fileInput, "change")
		},
		_setOption: function(t, n) {
			var r = e.inArray(t, this._specialOptions) !== -1;
			r && this._destroyEventHandlers(), this._super(t, n), r && (this._initSpecialOptions(), this._initEventHandlers())
		},
		_initSpecialOptions: function() {
			var t = this.options;
			t.fileInput === undefined ? t.fileInput = this.element.is('input[type="file"]') ? this.element : this.element.find('input[type="file"]') : t.fileInput instanceof e || (t.fileInput = e(t.fileInput)), t.dropZone instanceof e || (t.dropZone = e(t.dropZone)), t.pasteZone instanceof e || (t.pasteZone = e(t.pasteZone))
		},
		_getRegExp: function(e) {
			var t = e.split("/"),
				n = t.pop();
			return t.shift(), new RegExp(t.join("/"), n)
		},
		_isRegExpOption: function(t, n) {
			return t !== "url" && e.type(n) === "string" && /^\/.*\/[igm]{0,3}$/.test(n)
		},
		_initDataAttributes: function() {
			var t = this,
				n = this.options,
				r = this.element.data();
			e.each(this.element[0].attributes, function(e, i) {
				var s = i.name.toLowerCase(),
					o;
				/^data-/.test(s) && (s = s.slice(5).replace(/-[a-z]/g, function(e) {
					return e.charAt(1).toUpperCase()
				}), o = r[s], t._isRegExpOption(s, o) && (o = t._getRegExp(o)), n[s] = o)
			})
		},
		_create: function() {
			this._initDataAttributes(), this._initSpecialOptions(), this._slots = [], this._sequence = this._getXHRPromise(!0), this._sending = this._active = 0, this._initProgressObject(this), this._initEventHandlers()
		},
		active: function() {
			return this._active
		},
		progress: function() {
			return this._progress
		},
		add: function(t) {
			var n = this;
			if (!t || this.options.disabled) return;
			t.fileInput && !t.files ? this._getFileInputFiles(t.fileInput).always(function(e) {
				t.files = e, n._onAdd(null, t)
			}) : (t.files = e.makeArray(t.files), this._onAdd(null, t))
		},
		send: function(t) {
			if (t && !this.options.disabled) {
				if (t.fileInput && !t.files) {
					var n = this,
						r = e.Deferred(),
						i = r.promise(),
						s, o;
					return i.abort = function() {
						return o = !0, s ? s.abort() : (r.reject(null, "abort", "abort"), i)
					}, this._getFileInputFiles(t.fileInput).always(function(e) {
						if (o) return;
						if (!e.length) {
							r.reject();
							return
						}
						t.files = e, s = n._onSend(null, t), s.then(function(e, t, n) {
							r.resolve(e, t, n)
						}, function(e, t, n) {
							r.reject(e, t, n)
						})
					}), this._enhancePromise(i)
				}
				t.files = e.makeArray(t.files);
				if (t.files.length) return this._onSend(null, t)
			}
			return this._getXHRPromise(!1, t && t.context)
		}
	})
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery"], e) : typeof exports == "object" ? e(require("jquery")) : e(jQuery)
}(function(e, t) {
	function n(e, t, n, r) {
		return e.selector == t.selector && e.context == t.context && (!n || n.$lqguid == t.fn.$lqguid) && (!r || r.$lqguid == t.fn2.$lqguid)
	}
	e.extend(e.fn, {
		livequery: function(t, i) {
			var s = this,
				o;
			return e.each(r.queries, function(e, r) {
				if (n(s, r, t, i)) return (o = r) && !1
			}), o = o || new r(s.selector, s.context, t, i), o.stopped = !1, o.run(), s
		},
		expire: function(t, i) {
			var s = this;
			return e.each(r.queries, function(e, o) {
				n(s, o, t, i) && !s.stopped && r.stop(o.id)
			}), s
		}
	});
	var r = e.livequery = function(t, n, i, s) {
			var o = this;
			return o.selector = t, o.context = n, o.fn = i, o.fn2 = s, o.elements = e([]), o.stopped = !1, o.id = r.queries.push(o) - 1, i.$lqguid = i.$lqguid || r.guid++, s && (s.$lqguid = s.$lqguid || r.guid++), o
		};
	r.prototype = {
		stop: function() {
			var t = this;
			if (t.stopped) return;
			t.fn2 && t.elements.each(t.fn2), t.elements = e([]), t.stopped = !0
		},
		run: function() {
			var t = this;
			if (t.stopped) return;
			var n = t.elements,
				r = e(t.selector, t.context),
				i = r.not(n),
				s = n.not(r);
			t.elements = r, i.each(t.fn), t.fn2 && s.each(t.fn2)
		}
	}, e.extend(r, {
		guid: 0,
		queries: [],
		queue: [],
		running: !1,
		timeout: null,
		registered: [],
		checkQueue: function() {
			if (r.running && r.queue.length) {
				var e = r.queue.length;
				while (e--) r.queries[r.queue.shift()].run()
			}
		},
		pause: function() {
			r.running = !1
		},
		play: function() {
			r.running = !0, r.run()
		},
		registerPlugin: function() {
			e.each(arguments, function(t, n) {
				if (!e.fn[n] || e.inArray(n, r.registered) > 0) return;
				var i = e.fn[n];
				e.fn[n] = function() {
					var e = i.apply(this, arguments);
					return r.run(), e
				}, r.registered.push(n)
			})
		},
		run: function(n) {
			n !== t ? e.inArray(n, r.queue) < 0 && r.queue.push(n) : e.each(r.queries, function(t) {
				e.inArray(t, r.queue) < 0 && r.queue.push(t)
			}), r.timeout && clearTimeout(r.timeout), r.timeout = setTimeout(r.checkQueue, 20)
		},
		stop: function(n) {
			n !== t ? r.queries[n].stop() : e.each(r.queries, r.prototype.stop)
		}
	}), r.registerPlugin("append", "prepend", "after", "before", "wrap", "attr", "removeAttr", "addClass", "removeClass", "toggleClass", "empty", "remove", "html", "prop", "removeProp"), e(function() {
		r.play()
	})
}), function(e) {
	"use strict";

	function n(t, n, r, i) {
		function a(e, t) {
			return e -= i, t -= i, e < 0 || e >= o || t < 0 || t >= o ? !1 : s.isDark(e, t)
		}
		var s = e(r, n);
		s.addData(t), s.make(), i = i || 0;
		var o = s.getModuleCount(),
			u = s.getModuleCount() + 2 * i,
			f = function(e, t, n, r) {
				var i = this.isDark,
					s = 1 / u;
				this.isDark = function(o, u) {
					var a = u * s,
						f = o * s,
						l = a + s,
						c = f + s;
					return i(o, u) && (e > l || a > n || t > c || f > r)
				}
			};
		this.text = t, this.level = n, this.version = r, this.moduleCount = u, this.isDark = a, this.addBlank = f
	}
	function s(e, t, r, i, s) {
		r = Math.max(1, r || 1), i = Math.min(40, i || 40);
		for (var o = r; o <= i; o += 1) try {
			return new n(e, t, o, s)
		} catch (u) {}
	}
	function o(e, n, r) {
		var i = r.size,
			s = "bold " + r.mSize * i + "px " + r.fontname,
			o = t("<canvas/>")[0].getContext("2d");
		o.font = s;
		var u = o.measureText(r.label).width,
			a = r.mSize,
			f = u / i,
			l = (1 - f) * r.mPosX,
			c = (1 - a) * r.mPosY,
			h = l + f,
			p = c + a,
			d = .01;
		r.mode === 1 ? e.addBlank(0, c - d, i, p + d) : e.addBlank(l - d, c - d, h + d, p + d), n.fillStyle = r.fontcolor, n.font = s, n.fillText(r.label, l * i, c * i + .75 * r.mSize * i)
	}
	function u(e, t, n) {
		var r = n.size,
			i = n.image.naturalWidth || 1,
			s = n.image.naturalHeight || 1,
			o = n.mSize,
			u = o * i / s,
			a = (1 - u) * n.mPosX,
			f = (1 - o) * n.mPosY,
			l = a + u,
			c = f + o,
			h = .01;
		n.mode === 3 ? e.addBlank(0, f - h, r, c + h) : e.addBlank(a - h, f - h, l + h, c + h), t.drawImage(n.image, a * r, f * r, u * r, o * r)
	}
	function a(e, n, r) {
		t(r.background).is("img") ? n.drawImage(r.background, 0, 0, r.size, r.size) : r.background && (n.fillStyle = r.background, n.fillRect(r.left, r.top, r.size, r.size));
		var i = r.mode;
		i === 1 || i === 2 ? o(e, n, r) : (i === 3 || i === 4) && u(e, n, r)
	}
	function f(e, t, n, r, i, s, o, u) {
		e.isDark(o, u) && t.rect(r, i, s, s)
	}
	function l(e, t, n, r, i, s, o, u, a, f) {
		o ? e.moveTo(t + s, n) : e.moveTo(t, n), u ? (e.lineTo(r - s, n), e.arcTo(r, n, r, i, s)) : e.lineTo(r, n), a ? (e.lineTo(r, i - s), e.arcTo(r, i, t, i, s)) : e.lineTo(r, i), f ? (e.lineTo(t + s, i), e.arcTo(t, i, t, n, s)) : e.lineTo(t, i), o ? (e.lineTo(t, n + s), e.arcTo(t, n, r, n, s)) : e.lineTo(t, n)
	}
	function c(e, t, n, r, i, s, o, u, a, f) {
		o && (e.moveTo(t + s, n), e.lineTo(t, n), e.lineTo(t, n + s), e.arcTo(t, n, t + s, n, s)), u && (e.moveTo(r - s, n), e.lineTo(r, n), e.lineTo(r, n + s), e.arcTo(r, n, r - s, n, s)), a && (e.moveTo(r - s, i), e.lineTo(r, i), e.lineTo(r, i - s), e.arcTo(r, i, r - s, i, s)), f && (e.moveTo(t + s, i), e.lineTo(t, i), e.lineTo(t, i - s), e.arcTo(t, i, t + s, i, s))
	}
	function h(e, t, n, r, i, s, o, u) {
		var a = e.isDark,
			f = r + s,
			h = i + s,
			p = n.radius * s,
			d = o - 1,
			v = o + 1,
			m = u - 1,
			g = u + 1,
			y = a(o, u),
			b = a(d, m),
			w = a(d, u),
			E = a(d, g),
			S = a(o, g),
			x = a(v, g),
			T = a(v, u),
			N = a(v, m),
			C = a(o, m);
		y ? l(t, r, i, f, h, p, !w && !C, !w && !S, !T && !S, !T && !C) : c(t, r, i, f, h, p, w && C && b, w && S && E, T && S && x, T && C && N)
	}
	function p(e, n, r) {
		var s = e.moduleCount,
			o = r.size / s,
			u = f,
			a, l;
		i && r.radius > 0 && r.radius <= .5 && (u = h), n.beginPath();
		for (a = 0; a < s; a += 1) for (l = 0; l < s; l += 1) {
			var c = r.left + l * o,
				p = r.top + a * o,
				d = o;
			u(e, n, r, c, p, d, a, l)
		}
		if (t(r.fill).is("img")) {
			n.strokeStyle = "rgba(0,0,0,0.5)", n.lineWidth = 2, n.stroke();
			var v = n.globalCompositeOperation;
			n.globalCompositeOperation = "destination-out", n.fill(), n.globalCompositeOperation = v, n.clip(), n.drawImage(r.fill, 0, 0, r.size, r.size), n.restore()
		} else n.fillStyle = r.fill, n.fill()
	}
	function d(e, n) {
		var r = s(n.text, n.ecLevel, n.minVersion, n.maxVersion, n.quiet);
		if (!r) return null;
		var i = t(e).data("qrcode", r),
			o = i[0].getContext("2d");
		return a(r, o, n), p(r, o, n), i
	}
	function v(e) {
		var n = t("<canvas/>").attr("width", e.size).attr("height", e.size);
		return d(n, e)
	}
	function m(e) {
		return t("<img/>").attr("src", v(e)[0].toDataURL("image/png"))
	}
	function g(e) {
		var n = s(e.text, e.ecLevel, e.minVersion, e.maxVersion, e.quiet);
		if (!n) return null;
		var r = e.size,
			i = e.background,
			o = Math.floor,
			u = n.moduleCount,
			a = o(r / u),
			f = o(.5 * (r - a * u)),
			l, c, h = {
				position: "relative",
				left: 0,
				top: 0,
				padding: 0,
				margin: 0,
				width: r,
				height: r
			},
			p = {
				position: "absolute",
				padding: 0,
				margin: 0,
				width: a,
				height: a,
				"background-color": e.fill
			},
			d = t("<div/>").data("qrcode", n).css(h);
		i && d.css("background-color", i);
		for (l = 0; l < u; l += 1) for (c = 0; c < u; c += 1) n.isDark(l, c) && t("<div/>").css(p).css({
			left: f + c * a,
			top: f + l * a
		}).appendTo(d);
		return d
	}
	function y(e) {
		return r && e.render === "canvas" ? v(e) : r && e.render === "image" ? m(e) : g(e)
	}
	var t = jQuery,
		r = function() {
			var e = document.createElement("canvas");
			return Boolean(e.getContext && e.getContext("2d"))
		}(),
		i = Object.prototype.toString.call(window.opera) !== "[object Opera]",
		b = {
			render: "canvas",
			minVersion: 1,
			maxVersion: 40,
			ecLevel: "L",
			left: 0,
			top: 0,
			size: 200,
			fill: "#000",
			background: null,
			text: "no text",
			radius: 0,
			quiet: 0,
			mode: 0,
			mSize: .1,
			mPosX: .5,
			mPosY: .5,
			label: "no label",
			fontname: "sans",
			fontcolor: "#000",
			image: null
		};
	t.fn.qrcode = function(e) {
		var n = t.extend({}, b, e);
		return this.each(function() {
			this.nodeName.toLowerCase() === "canvas" ? d(this, n) : t(this).append(y(n))
		})
	}
}(function() {
	var e = function() {
			function o(e, t) {
				if (typeof e.length == "undefined") throw new Error(e.length + "/" + t);
				var n = function() {
						var n = 0;
						while (n < e.length && e[n] == 0) n += 1;
						var r = new Array(e.length - n + t);
						for (var i = 0; i < e.length - n; i += 1) r[i] = e[i + n];
						return r
					}(),
					r = {};
				return r.getAt = function(e) {
					return n[e]
				}, r.getLength = function() {
					return n.length
				}, r.multiply = function(e) {
					var t = new Array(r.getLength() + e.getLength() - 1);
					for (var n = 0; n < r.getLength(); n += 1) for (var i = 0; i < e.getLength(); i += 1) t[n + i] ^= s.gexp(s.glog(r.getAt(n)) + s.glog(e.getAt(i)));
					return o(t, 0)
				}, r.mod = function(e) {
					if (r.getLength() - e.getLength() < 0) return r;
					var t = s.glog(r.getAt(0)) - s.glog(e.getAt(0)),
						n = new Array(r.getLength());
					for (var i = 0; i < r.getLength(); i += 1) n[i] = r.getAt(i);
					for (var i = 0; i < e.getLength(); i += 1) n[i] ^= s.gexp(s.glog(e.getAt(i)) + t);
					return o(n, 0).mod(e)
				}, r
			}
			var e = function(e, t) {
					var r = 236,
						s = 17,
						l = e,
						c = n[t],
						h = null,
						p = 0,
						v = null,
						m = new Array,
						g = {},
						y = function(e, t) {
							p = l * 4 + 17, h = function(e) {
								var t = new Array(e);
								for (var n = 0; n < e; n += 1) {
									t[n] = new Array(e);
									for (var r = 0; r < e; r += 1) t[n][r] = null
								}
								return t
							}(p), b(0, 0), b(p - 7, 0), b(0, p - 7), S(), E(), T(e, t), l >= 7 && x(e), v == null && (v = k(l, c, m)), N(v, t)
						},
						b = function(e, t) {
							for (var n = -1; n <= 7; n += 1) {
								if (e + n <= -1 || p <= e + n) continue;
								for (var r = -1; r <= 7; r += 1) {
									if (t + r <= -1 || p <= t + r) continue;
									0 <= n && n <= 6 && (r == 0 || r == 6) || 0 <= r && r <= 6 && (n == 0 || n == 6) || 2 <= n && n <= 4 && 2 <= r && r <= 4 ? h[e + n][t + r] = !0 : h[e + n][t + r] = !1
								}
							}
						},
						w = function() {
							var e = 0,
								t = 0;
							for (var n = 0; n < 8; n += 1) {
								y(!0, n);
								var r = i.getLostPoint(g);
								if (n == 0 || e > r) e = r, t = n
							}
							return t
						},
						E = function() {
							for (var e = 8; e < p - 8; e += 1) {
								if (h[e][6] != null) continue;
								h[e][6] = e % 2 == 0
							}
							for (var t = 8; t < p - 8; t += 1) {
								if (h[6][t] != null) continue;
								h[6][t] = t % 2 == 0
							}
						},
						S = function() {
							var e = i.getPatternPosition(l);
							for (var t = 0; t < e.length; t += 1) for (var n = 0; n < e.length; n += 1) {
								var r = e[t],
									s = e[n];
								if (h[r][s] != null) continue;
								for (var o = -2; o <= 2; o += 1) for (var u = -2; u <= 2; u += 1) o == -2 || o == 2 || u == -2 || u == 2 || o == 0 && u == 0 ? h[r + o][s + u] = !0 : h[r + o][s + u] = !1
							}
						},
						x = function(e) {
							var t = i.getBCHTypeNumber(l);
							for (var n = 0; n < 18; n += 1) {
								var r = !e && (t >> n & 1) == 1;
								h[Math.floor(n / 3)][n % 3 + p - 8 - 3] = r
							}
							for (var n = 0; n < 18; n += 1) {
								var r = !e && (t >> n & 1) == 1;
								h[n % 3 + p - 8 - 3][Math.floor(n / 3)] = r
							}
						},
						T = function(e, t) {
							var n = c << 3 | t,
								r = i.getBCHTypeInfo(n);
							for (var s = 0; s < 15; s += 1) {
								var o = !e && (r >> s & 1) == 1;
								s < 6 ? h[s][8] = o : s < 8 ? h[s + 1][8] = o : h[p - 15 + s][8] = o
							}
							for (var s = 0; s < 15; s += 1) {
								var o = !e && (r >> s & 1) == 1;
								s < 8 ? h[8][p - s - 1] = o : s < 9 ? h[8][15 - s - 1 + 1] = o : h[8][15 - s - 1] = o
							}
							h[p - 8][8] = !e
						},
						N = function(e, t) {
							var n = -1,
								r = p - 1,
								s = 7,
								o = 0,
								u = i.getMaskFunction(t);
							for (var a = p - 1; a > 0; a -= 2) {
								a == 6 && (a -= 1);
								for (;;) {
									for (var f = 0; f < 2; f += 1) if (h[r][a - f] == null) {
										var l = !1;
										o < e.length && (l = (e[o] >>> s & 1) == 1);
										var c = u(r, a - f);
										c && (l = !l), h[r][a - f] = l, s -= 1, s == -1 && (o += 1, s = 7)
									}
									r += n;
									if (r < 0 || p <= r) {
										r -= n, n = -n;
										break
									}
								}
							}
						},
						C = function(e, t) {
							var n = 0,
								r = 0,
								s = 0,
								u = new Array(t.length),
								a = new Array(t.length);
							for (var f = 0; f < t.length; f += 1) {
								var l = t[f].dataCount,
									c = t[f].totalCount - l;
								r = Math.max(r, l), s = Math.max(s, c), u[f] = new Array(l);
								for (var h = 0; h < u[f].length; h += 1) u[f][h] = 255 & e.getBuffer()[h + n];
								n += l;
								var p = i.getErrorCorrectPolynomial(c),
									d = o(u[f], p.getLength() - 1),
									v = d.mod(p);
								a[f] = new Array(p.getLength() - 1);
								for (var h = 0; h < a[f].length; h += 1) {
									var m = h + v.getLength() - a[f].length;
									a[f][h] = m >= 0 ? v.getAt(m) : 0
								}
							}
							var g = 0;
							for (var h = 0; h < t.length; h += 1) g += t[h].totalCount;
							var y = new Array(g),
								b = 0;
							for (var h = 0; h < r; h += 1) for (var f = 0; f < t.length; f += 1) h < u[f].length && (y[b] = u[f][h], b += 1);
							for (var h = 0; h < s; h += 1) for (var f = 0; f < t.length; f += 1) h < a[f].length && (y[b] = a[f][h], b += 1);
							return y
						},
						k = function(e, t, n) {
							var o = u.getRSBlocks(e, t),
								f = a();
							for (var l = 0; l < n.length; l += 1) {
								var c = n[l];
								f.put(c.getMode(), 4), f.put(c.getLength(), i.getLengthInBits(c.getMode(), e)), c.write(f)
							}
							var h = 0;
							for (var l = 0; l < o.length; l += 1) h += o[l].dataCount;
							if (f.getLengthInBits() > h * 8) throw new Error("code length overflow. (" + f.getLengthInBits() + ">" + h * 8 + ")");
							f.getLengthInBits() + 4 <= h * 8 && f.put(0, 4);
							while (f.getLengthInBits() % 8 != 0) f.putBit(!1);
							for (;;) {
								if (f.getLengthInBits() >= h * 8) break;
								f.put(r, 8);
								if (f.getLengthInBits() >= h * 8) break;
								f.put(s, 8)
							}
							return C(f, o)
						};
					return g.addData = function(e) {
						var t = f(e);
						m.push(t), v = null
					}, g.isDark = function(e, t) {
						if (e < 0 || p <= e || t < 0 || p <= t) throw new Error(e + "," + t);
						return h[e][t]
					}, g.getModuleCount = function() {
						return p
					}, g.make = function() {
						y(!1, w())
					}, g.createTableTag = function(e, t) {
						e = e || 2, t = typeof t == "undefined" ? e * 4 : t;
						var n = "";
						n += '<table style="', n += " border-width: 0px; border-style: none;", n += " border-collapse: collapse;", n += " padding: 0px; margin: " + t + "px;", n += '">', n += "<tbody>";
						for (var r = 0; r < g.getModuleCount(); r += 1) {
							n += "<tr>";
							for (var i = 0; i < g.getModuleCount(); i += 1) n += '<td style="', n += " border-width: 0px; border-style: none;", n += " border-collapse: collapse;", n += " padding: 0px; margin: 0px;", n += " width: " + e + "px;", n += " height: " + e + "px;", n += " background-color: ", n += g.isDark(r, i) ? "#000000" : "#ffffff", n += ";", n += '"/>';
							n += "</tr>"
						}
						return n += "</tbody>", n += "</table>", n
					}, g.createImgTag = function(e, t) {
						e = e || 2, t = typeof t == "undefined" ? e * 4 : t;
						var n = g.getModuleCount() * e + t * 2,
							r = t,
							i = n - t;
						return d(n, n, function(t, n) {
							if (r <= t && t < i && r <= n && n < i) {
								var s = Math.floor((t - r) / e),
									o = Math.floor((n - r) / e);
								return g.isDark(o, s) ? 0 : 1
							}
							return 1
						})
					}, g
				};
			e.stringToBytes = function(e) {
				var t = new Array;
				for (var n = 0; n < e.length; n += 1) {
					var r = e.charCodeAt(n);
					t.push(r & 255)
				}
				return t
			}, e.createStringToBytes = function(e, t) {
				var n = function() {
						var n = h(e),
							r = function() {
								var e = n.read();
								if (e == -1) throw new Error;
								return e
							},
							i = 0,
							s = {};
						for (;;) {
							var o = n.read();
							if (o == -1) break;
							var u = r(),
								a = r(),
								f = r(),
								l = String.fromCharCode(o << 8 | u),
								c = a << 8 | f;
							s[l] = c, i += 1
						}
						if (i != t) throw new Error(i + " != " + t);
						return s
					}(),
					r = "?".charCodeAt(0);
				return function(e) {
					var t = new Array;
					for (var i = 0; i < e.length; i += 1) {
						var s = e.charCodeAt(i);
						if (s < 128) t.push(s);
						else {
							var o = n[e.charAt(i)];
							typeof o == "number" ? (o & 255) == o ? t.push(o) : (t.push(o >>> 8), t.push(o & 255)) : t.push(r)
						}
					}
					return t
				}
			};
			var t = {
				MODE_NUMBER: 1,
				MODE_ALPHA_NUM: 2,
				MODE_8BIT_BYTE: 4,
				MODE_KANJI: 8
			},
				n = {
					L: 1,
					M: 0,
					Q: 3,
					H: 2
				},
				r = {
					PATTERN000: 0,
					PATTERN001: 1,
					PATTERN010: 2,
					PATTERN011: 3,
					PATTERN100: 4,
					PATTERN101: 5,
					PATTERN110: 6,
					PATTERN111: 7
				},
				i = function() {
					var e = [
						[],
						[6, 18],
						[6, 22],
						[6, 26],
						[6, 30],
						[6, 34],
						[6, 22, 38],
						[6, 24, 42],
						[6, 26, 46],
						[6, 28, 50],
						[6, 30, 54],
						[6, 32, 58],
						[6, 34, 62],
						[6, 26, 46, 66],
						[6, 26, 48, 70],
						[6, 26, 50, 74],
						[6, 30, 54, 78],
						[6, 30, 56, 82],
						[6, 30, 58, 86],
						[6, 34, 62, 90],
						[6, 28, 50, 72, 94],
						[6, 26, 50, 74, 98],
						[6, 30, 54, 78, 102],
						[6, 28, 54, 80, 106],
						[6, 32, 58, 84, 110],
						[6, 30, 58, 86, 114],
						[6, 34, 62, 90, 118],
						[6, 26, 50, 74, 98, 122],
						[6, 30, 54, 78, 102, 126],
						[6, 26, 52, 78, 104, 130],
						[6, 30, 56, 82, 108, 134],
						[6, 34, 60, 86, 112, 138],
						[6, 30, 58, 86, 114, 142],
						[6, 34, 62, 90, 118, 146],
						[6, 30, 54, 78, 102, 126, 150],
						[6, 24, 50, 76, 102, 128, 154],
						[6, 28, 54, 80, 106, 132, 158],
						[6, 32, 58, 84, 110, 136, 162],
						[6, 26, 54, 82, 110, 138, 166],
						[6, 30, 58, 86, 114, 142, 170]
					],
						n = 1335,
						i = 7973,
						u = 21522,
						a = {},
						f = function(e) {
							var t = 0;
							while (e != 0) t += 1, e >>>= 1;
							return t
						};
					return a.getBCHTypeInfo = function(e) {
						var t = e << 10;
						while (f(t) - f(n) >= 0) t ^= n << f(t) - f(n);
						return (e << 10 | t) ^ u
					}, a.getBCHTypeNumber = function(e) {
						var t = e << 12;
						while (f(t) - f(i) >= 0) t ^= i << f(t) - f(i);
						return e << 12 | t
					}, a.getPatternPosition = function(t) {
						return e[t - 1]
					}, a.getMaskFunction = function(e) {
						switch (e) {
						case r.PATTERN000:
							return function(e, t) {
								return (e + t) % 2 == 0
							};
						case r.PATTERN001:
							return function(e, t) {
								return e % 2 == 0
							};
						case r.PATTERN010:
							return function(e, t) {
								return t % 3 == 0
							};
						case r.PATTERN011:
							return function(e, t) {
								return (e + t) % 3 == 0
							};
						case r.PATTERN100:
							return function(e, t) {
								return (Math.floor(e / 2) + Math.floor(t / 3)) % 2 == 0
							};
						case r.PATTERN101:
							return function(e, t) {
								return e * t % 2 + e * t % 3 == 0
							};
						case r.PATTERN110:
							return function(e, t) {
								return (e * t % 2 + e * t % 3) % 2 == 0
							};
						case r.PATTERN111:
							return function(e, t) {
								return (e * t % 3 + (e + t) % 2) % 2 == 0
							};
						default:
							throw new Error("bad maskPattern:" + e)
						}
					}, a.getErrorCorrectPolynomial = function(e) {
						var t = o([1], 0);
						for (var n = 0; n < e; n += 1) t = t.multiply(o([1, s.gexp(n)], 0));
						return t
					}, a.getLengthInBits = function(e, n) {
						if (1 <= n && n < 10) switch (e) {
						case t.MODE_NUMBER:
							return 10;
						case t.MODE_ALPHA_NUM:
							return 9;
						case t.MODE_8BIT_BYTE:
							return 8;
						case t.MODE_KANJI:
							return 8;
						default:
							throw new Error("mode:" + e)
						} else if (n < 27) switch (e) {
						case t.MODE_NUMBER:
							return 12;
						case t.MODE_ALPHA_NUM:
							return 11;
						case t.MODE_8BIT_BYTE:
							return 16;
						case t.MODE_KANJI:
							return 10;
						default:
							throw new Error("mode:" + e)
						} else {
							if (!(n < 41)) throw new Error("type:" + n);
							switch (e) {
							case t.MODE_NUMBER:
								return 14;
							case t.MODE_ALPHA_NUM:
								return 13;
							case t.MODE_8BIT_BYTE:
								return 16;
							case t.MODE_KANJI:
								return 12;
							default:
								throw new Error("mode:" + e)
							}
						}
					}, a.getLostPoint = function(e) {
						var t = e.getModuleCount(),
							n = 0;
						for (var r = 0; r < t; r += 1) for (var i = 0; i < t; i += 1) {
							var s = 0,
								o = e.isDark(r, i);
							for (var u = -1; u <= 1; u += 1) {
								if (r + u < 0 || t <= r + u) continue;
								for (var a = -1; a <= 1; a += 1) {
									if (i + a < 0 || t <= i + a) continue;
									if (u == 0 && a == 0) continue;
									o == e.isDark(r + u, i + a) && (s += 1)
								}
							}
							s > 5 && (n += 3 + s - 5)
						}
						for (var r = 0; r < t - 1; r += 1) for (var i = 0; i < t - 1; i += 1) {
							var f = 0;
							e.isDark(r, i) && (f += 1), e.isDark(r + 1, i) && (f += 1), e.isDark(r, i + 1) && (f += 1), e.isDark(r + 1, i + 1) && (f += 1);
							if (f == 0 || f == 4) n += 3
						}
						for (var r = 0; r < t; r += 1) for (var i = 0; i < t - 6; i += 1) e.isDark(r, i) && !e.isDark(r, i + 1) && e.isDark(r, i + 2) && e.isDark(r, i + 3) && e.isDark(r, i + 4) && !e.isDark(r, i + 5) && e.isDark(r, i + 6) && (n += 40);
						for (var i = 0; i < t; i += 1) for (var r = 0; r < t - 6; r += 1) e.isDark(r, i) && !e.isDark(r + 1, i) && e.isDark(r + 2, i) && e.isDark(r + 3, i) && e.isDark(r + 4, i) && !e.isDark(r + 5, i) && e.isDark(r + 6, i) && (n += 40);
						var l = 0;
						for (var i = 0; i < t; i += 1) for (var r = 0; r < t; r += 1) e.isDark(r, i) && (l += 1);
						var c = Math.abs(100 * l / t / t - 50) / 5;
						return n += c * 10, n
					}, a
				}(),
				s = function() {
					var e = new Array(256),
						t = new Array(256);
					for (var n = 0; n < 8; n += 1) e[n] = 1 << n;
					for (var n = 8; n < 256; n += 1) e[n] = e[n - 4] ^ e[n - 5] ^ e[n - 6] ^ e[n - 8];
					for (var n = 0; n < 255; n += 1) t[e[n]] = n;
					var r = {};
					return r.glog = function(e) {
						if (e < 1) throw new Error("glog(" + e + ")");
						return t[e]
					}, r.gexp = function(t) {
						while (t < 0) t += 255;
						while (t >= 256) t -= 255;
						return e[t]
					}, r
				}(),
				u = function() {
					var e = [
						[1, 26, 19],
						[1, 26, 16],
						[1, 26, 13],
						[1, 26, 9],
						[1, 44, 34],
						[1, 44, 28],
						[1, 44, 22],
						[1, 44, 16],
						[1, 70, 55],
						[1, 70, 44],
						[2, 35, 17],
						[2, 35, 13],
						[1, 100, 80],
						[2, 50, 32],
						[2, 50, 24],
						[4, 25, 9],
						[1, 134, 108],
						[2, 67, 43],
						[2, 33, 15, 2, 34, 16],
						[2, 33, 11, 2, 34, 12],
						[2, 86, 68],
						[4, 43, 27],
						[4, 43, 19],
						[4, 43, 15],
						[2, 98, 78],
						[4, 49, 31],
						[2, 32, 14, 4, 33, 15],
						[4, 39, 13, 1, 40, 14],
						[2, 121, 97],
						[2, 60, 38, 2, 61, 39],
						[4, 40, 18, 2, 41, 19],
						[4, 40, 14, 2, 41, 15],
						[2, 146, 116],
						[3, 58, 36, 2, 59, 37],
						[4, 36, 16, 4, 37, 17],
						[4, 36, 12, 4, 37, 13],
						[2, 86, 68, 2, 87, 69],
						[4, 69, 43, 1, 70, 44],
						[6, 43, 19, 2, 44, 20],
						[6, 43, 15, 2, 44, 16],
						[4, 101, 81],
						[1, 80, 50, 4, 81, 51],
						[4, 50, 22, 4, 51, 23],
						[3, 36, 12, 8, 37, 13],
						[2, 116, 92, 2, 117, 93],
						[6, 58, 36, 2, 59, 37],
						[4, 46, 20, 6, 47, 21],
						[7, 42, 14, 4, 43, 15],
						[4, 133, 107],
						[8, 59, 37, 1, 60, 38],
						[8, 44, 20, 4, 45, 21],
						[12, 33, 11, 4, 34, 12],
						[3, 145, 115, 1, 146, 116],
						[4, 64, 40, 5, 65, 41],
						[11, 36, 16, 5, 37, 17],
						[11, 36, 12, 5, 37, 13],
						[5, 109, 87, 1, 110, 88],
						[5, 65, 41, 5, 66, 42],
						[5, 54, 24, 7, 55, 25],
						[11, 36, 12, 7, 37, 13],
						[5, 122, 98, 1, 123, 99],
						[7, 73, 45, 3, 74, 46],
						[15, 43, 19, 2, 44, 20],
						[3, 45, 15, 13, 46, 16],
						[1, 135, 107, 5, 136, 108],
						[10, 74, 46, 1, 75, 47],
						[1, 50, 22, 15, 51, 23],
						[2, 42, 14, 17, 43, 15],
						[5, 150, 120, 1, 151, 121],
						[9, 69, 43, 4, 70, 44],
						[17, 50, 22, 1, 51, 23],
						[2, 42, 14, 19, 43, 15],
						[3, 141, 113, 4, 142, 114],
						[3, 70, 44, 11, 71, 45],
						[17, 47, 21, 4, 48, 22],
						[9, 39, 13, 16, 40, 14],
						[3, 135, 107, 5, 136, 108],
						[3, 67, 41, 13, 68, 42],
						[15, 54, 24, 5, 55, 25],
						[15, 43, 15, 10, 44, 16],
						[4, 144, 116, 4, 145, 117],
						[17, 68, 42],
						[17, 50, 22, 6, 51, 23],
						[19, 46, 16, 6, 47, 17],
						[2, 139, 111, 7, 140, 112],
						[17, 74, 46],
						[7, 54, 24, 16, 55, 25],
						[34, 37, 13],
						[4, 151, 121, 5, 152, 122],
						[4, 75, 47, 14, 76, 48],
						[11, 54, 24, 14, 55, 25],
						[16, 45, 15, 14, 46, 16],
						[6, 147, 117, 4, 148, 118],
						[6, 73, 45, 14, 74, 46],
						[11, 54, 24, 16, 55, 25],
						[30, 46, 16, 2, 47, 17],
						[8, 132, 106, 4, 133, 107],
						[8, 75, 47, 13, 76, 48],
						[7, 54, 24, 22, 55, 25],
						[22, 45, 15, 13, 46, 16],
						[10, 142, 114, 2, 143, 115],
						[19, 74, 46, 4, 75, 47],
						[28, 50, 22, 6, 51, 23],
						[33, 46, 16, 4, 47, 17],
						[8, 152, 122, 4, 153, 123],
						[22, 73, 45, 3, 74, 46],
						[8, 53, 23, 26, 54, 24],
						[12, 45, 15, 28, 46, 16],
						[3, 147, 117, 10, 148, 118],
						[3, 73, 45, 23, 74, 46],
						[4, 54, 24, 31, 55, 25],
						[11, 45, 15, 31, 46, 16],
						[7, 146, 116, 7, 147, 117],
						[21, 73, 45, 7, 74, 46],
						[1, 53, 23, 37, 54, 24],
						[19, 45, 15, 26, 46, 16],
						[5, 145, 115, 10, 146, 116],
						[19, 75, 47, 10, 76, 48],
						[15, 54, 24, 25, 55, 25],
						[23, 45, 15, 25, 46, 16],
						[13, 145, 115, 3, 146, 116],
						[2, 74, 46, 29, 75, 47],
						[42, 54, 24, 1, 55, 25],
						[23, 45, 15, 28, 46, 16],
						[17, 145, 115],
						[10, 74, 46, 23, 75, 47],
						[10, 54, 24, 35, 55, 25],
						[19, 45, 15, 35, 46, 16],
						[17, 145, 115, 1, 146, 116],
						[14, 74, 46, 21, 75, 47],
						[29, 54, 24, 19, 55, 25],
						[11, 45, 15, 46, 46, 16],
						[13, 145, 115, 6, 146, 116],
						[14, 74, 46, 23, 75, 47],
						[44, 54, 24, 7, 55, 25],
						[59, 46, 16, 1, 47, 17],
						[12, 151, 121, 7, 152, 122],
						[12, 75, 47, 26, 76, 48],
						[39, 54, 24, 14, 55, 25],
						[22, 45, 15, 41, 46, 16],
						[6, 151, 121, 14, 152, 122],
						[6, 75, 47, 34, 76, 48],
						[46, 54, 24, 10, 55, 25],
						[2, 45, 15, 64, 46, 16],
						[17, 152, 122, 4, 153, 123],
						[29, 74, 46, 14, 75, 47],
						[49, 54, 24, 10, 55, 25],
						[24, 45, 15, 46, 46, 16],
						[4, 152, 122, 18, 153, 123],
						[13, 74, 46, 32, 75, 47],
						[48, 54, 24, 14, 55, 25],
						[42, 45, 15, 32, 46, 16],
						[20, 147, 117, 4, 148, 118],
						[40, 75, 47, 7, 76, 48],
						[43, 54, 24, 22, 55, 25],
						[10, 45, 15, 67, 46, 16],
						[19, 148, 118, 6, 149, 119],
						[18, 75, 47, 31, 76, 48],
						[34, 54, 24, 34, 55, 25],
						[20, 45, 15, 61, 46, 16]
					],
						t = function(e, t) {
							var n = {};
							return n.totalCount = e, n.dataCount = t, n
						},
						r = {},
						i = function(t, r) {
							switch (r) {
							case n.L:
								return e[(t - 1) * 4 + 0];
							case n.M:
								return e[(t - 1) * 4 + 1];
							case n.Q:
								return e[(t - 1) * 4 + 2];
							case n.H:
								return e[(t - 1) * 4 + 3];
							default:
								return undefined
							}
						};
					return r.getRSBlocks = function(e, n) {
						var r = i(e, n);
						if (typeof r == "undefined") throw new Error("bad rs block @ typeNumber:" + e + "/errorCorrectLevel:" + n);
						var s = r.length / 3,
							o = new Array;
						for (var u = 0; u < s; u += 1) {
							var a = r[u * 3 + 0],
								f = r[u * 3 + 1],
								l = r[u * 3 + 2];
							for (var c = 0; c < a; c += 1) o.push(t(f, l))
						}
						return o
					}, r
				}(),
				a = function() {
					var e = new Array,
						t = 0,
						n = {};
					return n.getBuffer = function() {
						return e
					}, n.getAt = function(t) {
						var n = Math.floor(t / 8);
						return (e[n] >>> 7 - t % 8 & 1) == 1
					}, n.put = function(e, t) {
						for (var r = 0; r < t; r += 1) n.putBit((e >>> t - r - 1 & 1) == 1)
					}, n.getLengthInBits = function() {
						return t
					}, n.putBit = function(n) {
						var r = Math.floor(t / 8);
						e.length <= r && e.push(0), n && (e[r] |= 128 >>> t % 8), t += 1
					}, n
				},
				f = function(n) {
					var r = t.MODE_8BIT_BYTE,
						i = n,
						s = e.stringToBytes(n),
						o = {};
					return o.getMode = function() {
						return r
					}, o.getLength = function(e) {
						return s.length
					}, o.write = function(e) {
						for (var t = 0; t < s.length; t += 1) e.put(s[t], 8)
					}, o
				},
				l = function() {
					var e = new Array,
						t = {};
					return t.writeByte = function(t) {
						e.push(t & 255)
					}, t.writeShort = function(e) {
						t.writeByte(e), t.writeByte(e >>> 8)
					}, t.writeBytes = function(e, n, r) {
						n = n || 0, r = r || e.length;
						for (var i = 0; i < r; i += 1) t.writeByte(e[i + n])
					}, t.writeString = function(e) {
						for (var n = 0; n < e.length; n += 1) t.writeByte(e.charCodeAt(n))
					}, t.toByteArray = function() {
						return e
					}, t.toString = function() {
						var t = "";
						t += "[";
						for (var n = 0; n < e.length; n += 1) n > 0 && (t += ","), t += e[n];
						return t += "]", t
					}, t
				},
				c = function() {
					var e = 0,
						t = 0,
						n = 0,
						r = "",
						i = {},
						s = function(e) {
							r += String.fromCharCode(o(e & 63))
						},
						o = function(e) {
							if (!(e < 0)) {
								if (e < 26) return 65 + e;
								if (e < 52) return 97 + (e - 26);
								if (e < 62) return 48 + (e - 52);
								if (e == 62) return 43;
								if (e == 63) return 47
							}
							throw new Error("n:" + e)
						};
					return i.writeByte = function(r) {
						e = e << 8 | r & 255, t += 8, n += 1;
						while (t >= 6) s(e >>> t - 6), t -= 6
					}, i.flush = function() {
						t > 0 && (s(e << 6 - t), e = 0, t = 0);
						if (n % 3 != 0) {
							var i = 3 - n % 3;
							for (var o = 0; o < i; o += 1) r += "="
						}
					}, i.toString = function() {
						return r
					}, i
				},
				h = function(e) {
					var t = e,
						n = 0,
						r = 0,
						i = 0,
						s = {};
					s.read = function() {
						while (i < 8) {
							if (n >= t.length) {
								if (i == 0) return -1;
								throw new Error("unexpected end of file./" + i)
							}
							var e = t.charAt(n);
							n += 1;
							if (e == "=") return i = 0, -1;
							if (e.match(/^\s$/)) continue;
							r = r << 6 | o(e.charCodeAt(0)), i += 6
						}
						var s = r >>> i - 8 & 255;
						return i -= 8, s
					};
					var o = function(e) {
							if (65 <= e && e <= 90) return e - 65;
							if (97 <= e && e <= 122) return e - 97 + 26;
							if (48 <= e && e <= 57) return e - 48 + 52;
							if (e == 43) return 62;
							if (e == 47) return 63;
							throw new Error("c:" + e)
						};
					return s
				},
				p = function(e, t) {
					var n = e,
						r = t,
						i = new Array(e * t),
						s = {};
					s.setPixel = function(e, t, r) {
						i[t * n + e] = r
					}, s.write = function(e) {
						e.writeString("GIF87a"), e.writeShort(n), e.writeShort(r), e.writeByte(128), e.writeByte(0), e.writeByte(0), e.writeByte(0), e.writeByte(0), e.writeByte(0), e.writeByte(255), e.writeByte(255), e.writeByte(255), e.writeString(","), e.writeShort(0), e.writeShort(0), e.writeShort(n), e.writeShort(r), e.writeByte(0);
						var t = 2,
							i = u(t);
						e.writeByte(t);
						var s = 0;
						while (i.length - s > 255) e.writeByte(255), e.writeBytes(i, s, 255), s += 255;
						e.writeByte(i.length - s), e.writeBytes(i, s, i.length - s), e.writeByte(0), e.writeString(";")
					};
					var o = function(e) {
							var t = e,
								n = 0,
								r = 0,
								i = {};
							return i.write = function(e, i) {
								if (e >>> i != 0) throw new Error("length over");
								while (n + i >= 8) t.writeByte(255 & (e << n | r)), i -= 8 - n, e >>>= 8 - n, r = 0, n = 0;
								r = e << n | r, n += i
							}, i.flush = function() {
								n > 0 && t.writeByte(r)
							}, i
						},
						u = function(e) {
							var t = 1 << e,
								n = (1 << e) + 1,
								r = e + 1,
								s = a();
							for (var u = 0; u < t; u += 1) s.add(String.fromCharCode(u));
							s.add(String.fromCharCode(t)), s.add(String.fromCharCode(n));
							var f = l(),
								c = o(f);
							c.write(t, r);
							var h = 0,
								p = String.fromCharCode(i[h]);
							h += 1;
							while (h < i.length) {
								var d = String.fromCharCode(i[h]);
								h += 1, s.contains(p + d) ? p += d : (c.write(s.indexOf(p), r), s.size() < 4095 && (s.size() == 1 << r && (r += 1), s.add(p + d)), p = d)
							}
							return c.write(s.indexOf(p), r), c.write(n, r), c.flush(), f.toByteArray()
						},
						a = function() {
							var e = {},
								t = 0,
								n = {};
							return n.add = function(r) {
								if (n.contains(r)) throw new Error("dup key:" + r);
								e[r] = t, t += 1
							}, n.size = function() {
								return t
							}, n.indexOf = function(t) {
								return e[t]
							}, n.contains = function(t) {
								return typeof e[t] != "undefined"
							}, n
						};
					return s
				},
				d = function(e, t, n, r) {
					var i = p(e, t);
					for (var s = 0; s < t; s += 1) for (var o = 0; o < e; o += 1) i.setPixel(o, s, n(o, s));
					var u = l();
					i.write(u);
					var a = c(),
						f = u.toByteArray();
					for (var h = 0; h < f.length; h += 1) a.writeByte(f[h]);
					a.flush();
					var d = "";
					return d += "<img", d += ' src="', d += "data:image/gif;base64,", d += a, d += '"', d += ' width="', d += e, d += '"', d += ' height="', d += t, d += '"', r && (d += ' alt="', d += r, d += '"'), d += "/>", d
				};
			return e
		}();
	return function(e) {
		typeof define == "function" && define.amd ? define([], e) : typeof exports == "object" && (module.exports = e())
	}(function() {
		return e
	}), !
	function(e) {
		e.stringToBytes = function(e) {
			function t(e) {
				var t = [];
				for (var n = 0; n < e.length; n++) {
					var r = e.charCodeAt(n);
					r < 128 ? t.push(r) : r < 2048 ? t.push(192 | r >> 6, 128 | r & 63) : r < 55296 || r >= 57344 ? t.push(224 | r >> 12, 128 | r >> 6 & 63, 128 | r & 63) : (n++, r = 65536 + ((r & 1023) << 10 | e.charCodeAt(n) & 1023), t.push(240 | r >> 18, 128 | r >> 12 & 63, 128 | r >> 6 & 63, 128 | r & 63))
				}
				return t
			}
			return t(e)
		}
	}(e), e
}()), function(e) {
	typeof define == "function" && define.amd ? define(["jquery"], e) : typeof exports == "object" ? e(require("jquery")) : e(jQuery)
}(function(e) {
	function n(e) {
		return u.raw ? e : encodeURIComponent(e)
	}
	function r(e) {
		return u.raw ? e : decodeURIComponent(e)
	}
	function i(e) {
		return n(u.json ? JSON.stringify(e) : String(e))
	}
	function s(e) {
		e.indexOf('"') === 0 && (e = e.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, "\\"));
		try {
			return e = decodeURIComponent(e.replace(t, " ")), u.json ? JSON.parse(e) : e
		} catch (n) {}
	}
	function o(t, n) {
		var r = u.raw ? t : s(t);
		return e.isFunction(n) ? n(r) : r
	}
	var t = /\+/g,
		u = e.cookie = function(t, s, a) {
			if (s !== undefined && !e.isFunction(s)) {
				a = e.extend({}, u.defaults, a);
				if (typeof a.expires == "number") {
					var f = a.expires,
						l = a.expires = new Date;
					l.setTime(+l + f * 864e5)
				}
				return document.cookie = [n(t), "=", i(s), a.expires ? "; expires=" + a.expires.toUTCString() : "", a.path ? "; path=" + a.path : "", a.domain ? "; domain=" + a.domain : "", a.secure ? "; secure" : ""].join("")
			}
			var c = t ? undefined : {},
				h = document.cookie ? document.cookie.split("; ") : [];
			for (var p = 0, d = h.length; p < d; p++) {
				var v = h[p].split("="),
					m = r(v.shift()),
					g = v.join("=");
				if (t && t === m) {
					c = o(g, s);
					break
				}!t && (g = o(g)) !== undefined && (c[m] = g)
			}
			return c
		};
	u.defaults = {}, e.removeCookie = function(t, n) {
		return e.cookie(t) === undefined ? !1 : (e.cookie(t, "", e.extend({}, n, {
			expires: -1
		})), !e.cookie(t))
	}
}), function(e) {
	typeof define == "function" && define.amd ? define(["jquery"], e) : typeof module == "object" && module.exports ? e(require("jquery")) : e(jQuery)
}(function(e) {
	function f(t) {
		var n = {},
			r = /^jQuery\d+$/;
		return e.each(t.attributes, function(e, t) {
			t.specified && !r.test(t.name) && (n[t.name] = t.value)
		}), n
	}
	function l(t, n) {
		var r = this,
			i = e(r);
		if (r.value == i.attr("placeholder") && i.hasClass(a.customClass)) if (i.data("placeholder-password")) {
			i = i.hide().nextAll('input[type="password"]:first').show().attr("id", i.removeAttr("id").data("placeholder-id"));
			if (t === !0) return i[0].value = n;
			i.focus()
		} else r.value = "", i.removeClass(a.customClass), r == h() && r.select()
	}
	function c() {
		var t, n = this,
			r = e(n),
			i = this.id;
		if (n.value === "") {
			if (n.type === "password") {
				if (!r.data("placeholder-textinput")) {
					try {
						t = r.clone().prop({
							type: "text"
						})
					} catch (s) {
						t = e("<input>").attr(e.extend(f(this), {
							type: "text"
						}))
					}
					t.removeAttr("name").data({
						"placeholder-password": r,
						"placeholder-id": i
					}).bind("focus.placeholder", l), r.data({
						"placeholder-textinput": t,
						"placeholder-id": i
					}).before(t)
				}
				r = r.removeAttr("id").hide().prevAll('input[type="text"]:first').attr("id", i).show()
			}
			r.addClass(a.customClass), r[0].value = r.attr("placeholder")
		} else r.removeClass(a.customClass)
	}
	function h() {
		try {
			return document.activeElement
		} catch (e) {}
	}
	var t = Object.prototype.toString.call(window.operamini) == "[object OperaMini]",
		n = "placeholder" in document.createElement("input") && !t,
		r = "placeholder" in document.createElement("textarea") && !t,
		i = e.valHooks,
		s = e.propHooks,
		o, u;
	if (n && r) u = e.fn.placeholder = function() {
		return this
	}, u.input = u.textarea = !0;
	else {
		var a = {};
		u = e.fn.placeholder = function(t) {
			var r = {
				customClass: "placeholder"
			};
			a = e.extend({}, r, t);
			var i = this;
			return i.filter((n ? "textarea" : ":input") + "[placeholder]").not("." + a.customClass).bind({
				"focus.placeholder": l,
				"blur.placeholder": c
			}).data("placeholder-enabled", !0).trigger("blur.placeholder"), i
		}, u.input = n, u.textarea = r, o = {
			get: function(t) {
				var n = e(t),
					r = n.data("placeholder-password");
				return r ? r[0].value : n.data("placeholder-enabled") && n.hasClass(a.customClass) ? "" : t.value
			},
			set: function(t, n) {
				var r = e(t),
					i = r.data("placeholder-password");
				return i ? i[0].value = n : r.data("placeholder-enabled") ? (n === "" ? (t.value = n, t != h() && c.call(t)) : r.hasClass(a.customClass) ? l.call(t, !0, n) || (t.value = n) : t.value = n, r) : t.value = n
			}
		}, n || (i.input = o, s.value = o), r || (i.textarea = o, s.value = o), e(function() {
			e(document).delegate("form", "submit.placeholder", function() {
				var t = e("." + a.customClass, this).each(l);
				setTimeout(function() {
					t.each(c)
				}, 10)
			})
		}), e(window).bind("beforeunload.placeholder", function() {
			e("." + a.customClass).each(function() {
				this.value = ""
			})
		})
	}
}), function(e) {
	e.fn.mailAutoComplete = function(t) {
		var n = {
			boxClass: "mailListBox",
			listClass: "mailListDefault",
			focusClass: "mailListFocus",
			markCalss: "mailListHlignt",
			zIndex: 1,
			autoClass: !0,
			mailArr: ["qq.com", "gmail.com", "126.com", "163.com", "hotmail.com", "outlook.com", "sohu.com", "sina.com"],
			textHint: !1,
			hintText: "",
			focusColor: "#333",
			blurColor: "#999"
		},
			r = e.extend({}, n, t || {});
		r.autoClass && e("#mailListAppendCss").size() === 0 && e('<style id="mailListAppendCss" type="text/css">.mailListBox{border:1px solid #369; background:#fff; }.mailListDefault{padding:0 5px;cursor:pointer;white-space:nowrap;}.mailListFocus{padding:0 5px;cursor:pointer;white-space:nowrap;background:#369;color:white;}.mailListHlignt{color:red;}.mailListFocus .mailListHlignt{color:#fff;}</style>').appendTo(e("head"));
		var i = r.boxClass,
			s = r.listClass,
			o = r.focusClass,
			u = r.markCalss,
			a = r.zIndex,
			f = mailArr = r.mailArr,
			l = r.textHint,
			c = r.hintText,
			h = r.focusColor,
			p = r.blurColor;
		e.createHtml = function(t, n, r) {
			var i = "";
			return e.isArray(n) && (n.length > 0 ? (e(".justForJs").show(), e.each(n, function(e, a) {
				e === r ? i += '<div class="mailHover ' + o + '" id="mailList_' + e + '"><span class="' + u + '">' + t + "</span>@" + n[e] + "</div>" : i += '<div class="mailHover ' + s + '" id="mailList_' + e + '"><span class="' + u + '">' + t + "</span>@" + n[e] + "</div>"
			})) : e(".justForJs").hide()), i
		};
		var d = -1,
			m;
		e(this).each(function() {
			var t = e(this),
				n = e(".justForJs").size(),
				r = "100%",
				u = t.outerHeight();
			t.wrap('<span style="display:block;position:relative;"></span>').before('<div id="mailListBox_' + n + '" class="justForJs ' + i + '" style="font-size: 15px; min-width:' + r + ";_width:" + r + "px;position:absolute;left:-6000px;top:" + u + "px;z-index:" + a + ';"></div>');
			var g = e("#mailListBox_" + n),
				y;
			t.focus(function() {
				e(this).css("color", h).parent().css("z-index", a);
				if (l && c) {
					var n = e.trim(e(this).val());
					n === c && e(this).val("")
				}
				e(this).keydown(function(n) {
					m = v = e.trim(e(this).val()), v.length > 0 && n.keyCode === 13 && (d > -1 && d < f.length && (e(this).val(e("#mailList_" + d).text()), g.css("left", "-6000px"), t.parent().parent().next().find("input").focus()), n.preventDefault())
				}), e(this).keyup(function(t) {
					m = v = e.trim(e(this).val()), /@/.test(v) && (m = v.replace(/@.*/, ""));
					if (v.length > 0) {
						if (t.keyCode === 38) d <= 0 && (d = f.length), d--;
						else if (t.keyCode === 40) d >= f.length - 1 && (d = -1), d++;
						else if (/@/.test(v)) {
							d = -1;
							var n = v.replace(/.*@/, "");
							f = e.map(mailArr, function(e) {
								var t = new RegExp(n);
								if (t.test(e)) return e
							})
						} else f = mailArr;
						g.html(e.createHtml(m, f, d)).css("left", 0)
					} else g.css("left", "-6000px")
				}).blur(function() {
					if (l && c) {
						var t = e.trim(e(this).val());
						t === "" && e(this).val(c)
					}
					e(this).css("color", p).unbind("keyup").parent().css("z-index", 0), g.css("left", "-6000px")
				}), e(".mailHover").livequery(function() {
					e(this).on("mouseover", function() {
						d = Number(e(this).attr("id").split("_")[1]), y = e("#mailList_" + d).text(), g.children("." + o).removeClass(o).addClass(s), e(this).addClass(o).removeClass(s)
					})
				})
			}), g.bind("mousedown", function() {
				t.val(y)
			})
		})
	}
}(jQuery), function(e, t, n) {
	function s(t, n) {
		this.bodyOverflowX, this.callbacks = {
			hide: [],
			show: []
		}, this.checkInterval = null, this.Content, this.$el = e(t), this.$elProxy, this.elProxyPosition, this.enabled = !0, this.options = e.extend({}, i, n), this.mouseIsOverProxy = !1, this.namespace = "tooltipster-" + Math.round(Math.random() * 1e5), this.Status = "hidden", this.timerHide = null, this.timerShow = null, this.$tooltip, this.options.iconTheme = this.options.iconTheme.replace(".", ""), this.options.theme = this.options.theme.replace(".", ""), this._init()
	}
	function o(t, n) {
		var r = !0;
		return e.each(t, function(e, i) {
			if (typeof n[e] == "undefined" || t[e] !== n[e]) return r = !1, !1
		}), r
	}
	function f() {
		return !a && u
	}
	function l() {
		var e = n.body || n.documentElement,
			t = e.style,
			r = "transition";
		if (typeof t[r] == "string") return !0;
		v = ["Moz", "Webkit", "Khtml", "O", "ms"], r = r.charAt(0).toUpperCase() + r.substr(1);
		for (var i = 0; i < v.length; i++) if (typeof t[v[i] + r] == "string") return !0;
		return !1
	}
	var r = "tooltipster",
		i = {
			animation: "fade",
			arrow: !0,
			arrowColor: "",
			autoClose: !0,
			content: null,
			contentAsHTML: !1,
			contentCloning: !0,
			debug: !0,
			delay: 200,
			minWidth: 0,
			maxWidth: null,
			functionInit: function(e, t) {},
			functionBefore: function(e, t) {
				t()
			},
			functionReady: function(e, t) {},
			functionAfter: function(e) {},
			hideOnClick: !1,
			icon: "(?)",
			iconCloning: !0,
			iconDesktop: !1,
			iconTouch: !1,
			iconTheme: "tooltipster-icon",
			interactive: !1,
			interactiveTolerance: 350,
			multiple: !1,
			offsetX: 0,
			offsetY: 0,
			onlyOne: !1,
			position: "top",
			positionTracker: !1,
			positionTrackerCallback: function(e) {
				this.option("trigger") == "hover" && this.option("autoClose") && this.hide()
			},
			restoration: "current",
			speed: 350,
			timer: 0,
			theme: "tooltipster-default",
			touchDevices: !0,
			trigger: "hover",
			updateAnimation: !0
		};
	s.prototype = {
		_init: function() {
			var t = this;
			if (n.querySelector) {
				var r = null;
				t.$el.data("tooltipster-initialTitle") === undefined && (r = t.$el.attr("title"), r === undefined && (r = null), t.$el.data("tooltipster-initialTitle", r)), t.options.content !== null ? t._content_set(t.options.content) : t._content_set(r);
				var i = t.options.functionInit.call(t.$el, t.$el, t.Content);
				typeof i != "undefined" && t._content_set(i), t.$el.removeAttr("title").addClass("tooltipstered"), !u && t.options.iconDesktop || u && t.options.iconTouch ? (typeof t.options.icon == "string" ? (t.$elProxy = e('<span class="' + t.options.iconTheme + '"></span>'), t.$elProxy.text(t.options.icon)) : t.options.iconCloning ? t.$elProxy = t.options.icon.clone(!0) : t.$elProxy = t.options.icon, t.$elProxy.insertAfter(t.$el)) : t.$elProxy = t.$el, t.options.trigger == "hover" ? (t.$elProxy.on("mouseenter." + t.namespace, function() {
					if (!f() || t.options.touchDevices) t.mouseIsOverProxy = !0, t._show()
				}).on("mouseleave." + t.namespace, function() {
					if (!f() || t.options.touchDevices) t.mouseIsOverProxy = !1
				}), u && t.options.touchDevices && t.$elProxy.on("touchstart." + t.namespace, function() {
					t._showNow()
				})) : t.options.trigger == "click" && t.$elProxy.on("click." + t.namespace, function() {
					(!f() || t.options.touchDevices) && t._show()
				})
			}
		},
		_show: function() {
			var e = this;
			e.Status != "shown" && e.Status != "appearing" && (e.options.delay ? e.timerShow = setTimeout(function() {
				(e.options.trigger == "click" || e.options.trigger == "hover" && e.mouseIsOverProxy) && e._showNow()
			}, e.options.delay) : e._showNow())
		},
		_showNow: function(n) {
			var r = this;
			r.options.functionBefore.call(r.$el, r.$el, function() {
				if (r.enabled && r.Content !== null) {
					n && r.callbacks.show.push(n), r.callbacks.hide = [], clearTimeout(r.timerShow), r.timerShow = null, clearTimeout(r.timerHide), r.timerHide = null, r.options.onlyOne && e(".tooltipstered").not(r.$el).each(function(t, n) {
						var r = e(n),
							i = r.data("tooltipster-ns");
						e.each(i, function(e, t) {
							var n = r.data(t),
								i = n.status(),
								s = n.option("autoClose");
							i !== "hidden" && i !== "disappearing" && s && n.hide()
						})
					});
					var i = function() {
							r.Status = "shown", e.each(r.callbacks.show, function(e, t) {
								t.call(r.$el)
							}), r.callbacks.show = []
						};
					if (r.Status !== "hidden") {
						var s = 0;
						r.Status === "disappearing" ? (r.Status = "appearing", l() ? (r.$tooltip.clearQueue().removeClass("tooltipster-dying").addClass("tooltipster-" + r.options.animation + "-show"), r.options.speed > 0 && r.$tooltip.delay(r.options.speed), r.$tooltip.queue(i)) : r.$tooltip.stop().fadeIn(i)) : r.Status === "shown" && i()
					} else {
						r.Status = "appearing";
						var s = r.options.speed;
						r.bodyOverflowX = e("body").css("overflow-x"), e("body").css("overflow-x", "hidden");
						var o = "tooltipster-" + r.options.animation,
							a = "-webkit-transition-duration: " + r.options.speed + "ms; -webkit-animation-duration: " + r.options.speed + "ms; -moz-transition-duration: " + r.options.speed + "ms; -moz-animation-duration: " + r.options.speed + "ms; -o-transition-duration: " + r.options.speed + "ms; -o-animation-duration: " + r.options.speed + "ms; -ms-transition-duration: " + r.options.speed + "ms; -ms-animation-duration: " + r.options.speed + "ms; transition-duration: " + r.options.speed + "ms; animation-duration: " + r.options.speed + "ms;",
							f = r.options.minWidth ? "min-width:" + Math.round(r.options.minWidth) + "px;" : "",
							c = r.options.maxWidth ? "max-width:" + Math.round(r.options.maxWidth) + "px;" : "",
							h = r.options.interactive ? "pointer-events: auto;" : "";
						r.$tooltip = e('<div class="tooltipster-base ' + r.options.theme + '" style="' + f + " " + c + " " + h + " " + a + '"><div class="tooltipster-content"></div></div>'), l() && r.$tooltip.addClass(o), r._content_insert(), r.$tooltip.appendTo("body"), r.reposition(), r.options.functionReady.call(r.$el, r.$el, r.$tooltip), l() ? (r.$tooltip.addClass(o + "-show"), r.options.speed > 0 && r.$tooltip.delay(r.options.speed), r.$tooltip.queue(i)) : r.$tooltip.css("display", "none").fadeIn(r.options.speed, i), r._interval_set(), e(t).on("scroll." + r.namespace + " resize." + r.namespace, function() {
							r.reposition()
						});
						if (r.options.autoClose) {
							e("body").off("." + r.namespace);
							if (r.options.trigger == "hover") {
								u && setTimeout(function() {
									e("body").on("touchstart." + r.namespace, function() {
										r.hide()
									})
								}, 0);
								if (r.options.interactive) {
									u && r.$tooltip.on("touchstart." + r.namespace, function(e) {
										e.stopPropagation()
									});
									var p = null;
									r.$elProxy.add(r.$tooltip).on("mouseleave." + r.namespace + "-autoClose", function() {
										clearTimeout(p), p = setTimeout(function() {
											r.hide()
										}, r.options.interactiveTolerance)
									}).on("mouseenter." + r.namespace + "-autoClose", function() {
										clearTimeout(p)
									})
								} else r.$elProxy.on("mouseleave." + r.namespace + "-autoClose", function() {
									r.hide()
								});
								r.options.hideOnClick && r.$elProxy.on("click." + r.namespace + "-autoClose", function() {
									r.hide()
								})
							} else r.options.trigger == "click" && (setTimeout(function() {
								e("body").on("click." + r.namespace + " touchstart." + r.namespace, function() {
									r.hide()
								})
							}, 0), r.options.interactive && r.$tooltip.on("click." + r.namespace + " touchstart." + r.namespace, function(e) {
								e.stopPropagation()
							}))
						}
					}
					r.options.timer > 0 && (r.timerHide = setTimeout(function() {
						r.timerHide = null, r.hide()
					}, r.options.timer + s))
				}
			})
		},
		_interval_set: function() {
			var t = this;
			t.checkInterval = setInterval(function() {
				if (e("body").find(t.$el).length === 0 || e("body").find(t.$elProxy).length === 0 || t.Status == "hidden" || e("body").find(t.$tooltip).length === 0)(t.Status == "shown" || t.Status == "appearing") && t.hide(), t._interval_cancel();
				else if (t.options.positionTracker) {
					var n = t._repositionInfo(t.$elProxy),
						r = !1;
					o(n.dimension, t.elProxyPosition.dimension) && (t.$elProxy.css("position") === "fixed" ? o(n.position, t.elProxyPosition.position) && (r = !0) : o(n.offset, t.elProxyPosition.offset) && (r = !0)), r || (t.reposition(), t.options.positionTrackerCallback.call(t, t.$el))
				}
			}, 200)
		},
		_interval_cancel: function() {
			clearInterval(this.checkInterval), this.checkInterval = null
		},
		_content_set: function(e) {
			typeof e == "object" && e !== null && this.options.contentCloning && (e = e.clone(!0)), this.Content = e
		},
		_content_insert: function() {
			var e = this,
				t = this.$tooltip.find(".tooltipster-content");
			typeof e.Content == "string" && !e.options.contentAsHTML ? t.text(e.Content) : t.empty().append(e.Content)
		},
		_update: function(e) {
			var t = this;
			t._content_set(e), t.Content !== null ? t.Status !== "hidden" && (t._content_insert(), t.reposition(), t.options.updateAnimation && (l() ? (t.$tooltip.css({
				width: "",
				"-webkit-transition": "all " + t.options.speed + "ms, width 0ms, height 0ms, left 0ms, top 0ms",
				"-moz-transition": "all " + t.options.speed + "ms, width 0ms, height 0ms, left 0ms, top 0ms",
				"-o-transition": "all " + t.options.speed + "ms, width 0ms, height 0ms, left 0ms, top 0ms",
				"-ms-transition": "all " + t.options.speed + "ms, width 0ms, height 0ms, left 0ms, top 0ms",
				transition: "all " + t.options.speed + "ms, width 0ms, height 0ms, left 0ms, top 0ms"
			}).addClass("tooltipster-content-changing"), setTimeout(function() {
				t.Status != "hidden" && (t.$tooltip.removeClass("tooltipster-content-changing"), setTimeout(function() {
					t.Status !== "hidden" && t.$tooltip.css({
						"-webkit-transition": t.options.speed + "ms",
						"-moz-transition": t.options.speed + "ms",
						"-o-transition": t.options.speed + "ms",
						"-ms-transition": t.options.speed + "ms",
						transition: t.options.speed + "ms"
					})
				}, t.options.speed))
			}, t.options.speed)) : t.$tooltip.fadeTo(t.options.speed, .5, function() {
				t.Status != "hidden" && t.$tooltip.fadeTo(t.options.speed, 1)
			}))) : t.hide()
		},
		_repositionInfo: function(e) {
			return {
				dimension: {
					height: e.outerHeight(!1),
					width: e.outerWidth(!1)
				},
				offset: e.offset(),
				position: {
					left: parseInt(e.css("left")),
					top: parseInt(e.css("top"))
				}
			}
		},
		hide: function(n) {
			var r = this;
			n && r.callbacks.hide.push(n), r.callbacks.show = [], clearTimeout(r.timerShow), r.timerShow = null, clearTimeout(r.timerHide), r.timerHide = null;
			var i = function() {
					e.each(r.callbacks.hide, function(e, t) {
						t.call(r.$el)
					}), r.callbacks.hide = []
				};
			if (r.Status == "shown" || r.Status == "appearing") {
				r.Status = "disappearing";
				var s = function() {
						r.Status = "hidden", typeof r.Content == "object" && r.Content !== null && r.Content.detach(), r.$tooltip.remove(), r.$tooltip = null, e(t).off("." + r.namespace), e("body").off("." + r.namespace).css("overflow-x", r.bodyOverflowX), e("body").off("." + r.namespace), r.$elProxy.off("." + r.namespace + "-autoClose"), r.options.functionAfter.call(r.$el, r.$el), i()
					};
				l() ? (r.$tooltip.clearQueue().removeClass("tooltipster-" + r.options.animation + "-show").addClass("tooltipster-dying"), r.options.speed > 0 && r.$tooltip.delay(r.options.speed), r.$tooltip.queue(s)) : r.$tooltip.stop().fadeOut(r.options.speed, s)
			} else r.Status == "hidden" && i();
			return r
		},
		show: function(e) {
			return this._showNow(e), this
		},
		update: function(e) {
			return this.content(e)
		},
		content: function(e) {
			return typeof e == "undefined" ? this.Content : (this._update(e), this)
		},
		reposition: function() {
			var n = this;
			if (e("body").find(n.$tooltip).length !== 0) {
				n.$tooltip.css("width", ""), n.elProxyPosition = n._repositionInfo(n.$elProxy);
				var r = null,
					i = e(t).width(),
					s = n.elProxyPosition,
					o = n.$tooltip.outerWidth(!1),
					u = n.$tooltip.innerWidth() + 1,
					a = n.$tooltip.outerHeight(!1);
				if (n.$elProxy.is("area")) {
					var f = n.$elProxy.attr("shape"),
						l = n.$elProxy.parent().attr("name"),
						c = e('img[usemap="#' + l + '"]'),
						h = c.offset().left,
						p = c.offset().top,
						d = n.$elProxy.attr("coords") !== undefined ? n.$elProxy.attr("coords").split(",") : undefined;
					if (f == "circle") {
						var v = parseInt(d[0]),
							m = parseInt(d[1]),
							g = parseInt(d[2]);
						s.dimension.height = g * 2, s.dimension.width = g * 2, s.offset.top = p + m - g, s.offset.left = h + v - g
					} else if (f == "rect") {
						var v = parseInt(d[0]),
							m = parseInt(d[1]),
							y = parseInt(d[2]),
							b = parseInt(d[3]);
						s.dimension.height = b - m, s.dimension.width = y - v, s.offset.top = p + m, s.offset.left = h + v
					} else if (f == "poly") {
						var w = [],
							E = [],
							S = 0,
							x = 0,
							T = 0,
							N = 0,
							C = "even";
						for (var k = 0; k < d.length; k++) {
							var L = parseInt(d[k]);
							C == "even" ? (L > T && (T = L, k === 0 && (S = T)), L < S && (S = L), C = "odd") : (L > N && (N = L, k == 1 && (x = N)), L < x && (x = L), C = "even")
						}
						s.dimension.height = N - x, s.dimension.width = T - S, s.offset.top = p + x, s.offset.left = h + S
					} else s.dimension.height = c.outerHeight(!1), s.dimension.width = c.outerWidth(!1), s.offset.top = p, s.offset.left = h
				}
				var A = 0,
					O = 0,
					M = 0,
					_ = parseInt(n.options.offsetY),
					D = parseInt(n.options.offsetX),
					P = n.options.position;

				function H() {
					var n = e(t).scrollLeft();
					A - n < 0 && (r = A - n, A = n), A + o - n > i && (r = A - (i + n - o), A = i + n - o)
				}
				function B(n, r) {
					s.offset.top - e(t).scrollTop() - a - _ - 12 < 0 && r.indexOf("top") > -1 && (P = n), s.offset.top + s.dimension.height + a + 12 + _ > e(t).scrollTop() + e(t).height() && r.indexOf("bottom") > -1 && (P = n, M = s.offset.top - a - _ - 12)
				}
				if (P == "top") {
					var j = s.offset.left + o - (s.offset.left + s.dimension.width);
					A = s.offset.left + D - j / 2, M = s.offset.top - a - _ - 12, H(), B("bottom", "top")
				}
				P == "top-left" && (A = s.offset.left + D, M = s.offset.top - a - _ - 12, H(), B("bottom-left", "top-left")), P == "top-right" && (A = s.offset.left + s.dimension.width + D - o, M = s.offset.top - a - _ - 12, H(), B("bottom-right", "top-right"));
				if (P == "bottom") {
					var j = s.offset.left + o - (s.offset.left + s.dimension.width);
					A = s.offset.left - j / 2 + D, M = s.offset.top + s.dimension.height + _ + 12, H(), B("top", "bottom")
				}
				P == "bottom-left" && (A = s.offset.left + D, M = s.offset.top + s.dimension.height + _ + 12, H(), B("top-left", "bottom-left")), P == "bottom-right" && (A = s.offset.left + s.dimension.width + D - o, M = s.offset.top + s.dimension.height + _ + 12, H(), B("top-right", "bottom-right"));
				if (P == "left") {
					A = s.offset.left - D - o - 12, O = s.offset.left + D + s.dimension.width + 12;
					var F = s.offset.top + a - (s.offset.top + s.dimension.height);
					M = s.offset.top - F / 2 - _;
					if (A < 0 && O + o > i) {
						var I = parseFloat(n.$tooltip.css("border-width")) * 2,
							q = o + A - I;
						n.$tooltip.css("width", q + "px"), a = n.$tooltip.outerHeight(!1), A = s.offset.left - D - q - 12 - I, F = s.offset.top + a - (s.offset.top + s.dimension.height), M = s.offset.top - F / 2 - _
					} else A < 0 && (A = s.offset.left + D + s.dimension.width + 12, r = "left")
				}
				if (P == "right") {
					A = s.offset.left + D + s.dimension.width + 12, O = s.offset.left - D - o - 12;
					var F = s.offset.top + a - (s.offset.top + s.dimension.height);
					M = s.offset.top - F / 2 - _;
					if (A + o > i && O < 0) {
						var I = parseFloat(n.$tooltip.css("border-width")) * 2,
							q = i - A - I;
						n.$tooltip.css("width", q + "px"), a = n.$tooltip.outerHeight(!1), F = s.offset.top + a - (s.offset.top + s.dimension.height), M = s.offset.top - F / 2 - _
					} else A + o > i && (A = s.offset.left - D - o - 12, r = "right")
				}
				if (n.options.arrow) {
					var R = "tooltipster-arrow-" + P;
					if (n.options.arrowColor.length < 1) var U = n.$tooltip.css("background-color");
					else var U = n.options.arrowColor;
					r ? r == "left" ? (R = "tooltipster-arrow-right", r = "") : r == "right" ? (R = "tooltipster-arrow-left", r = "") : r = "left:" + Math.round(r) + "px;" : r = "";
					if (P == "top" || P == "top-left" || P == "top-right") var z = parseFloat(n.$tooltip.css("border-bottom-width")),
						W = n.$tooltip.css("border-bottom-color");
					else if (P == "bottom" || P == "bottom-left" || P == "bottom-right") var z = parseFloat(n.$tooltip.css("border-top-width")),
						W = n.$tooltip.css("border-top-color");
					else if (P == "left") var z = parseFloat(n.$tooltip.css("border-right-width")),
						W = n.$tooltip.css("border-right-color");
					else if (P == "right") var z = parseFloat(n.$tooltip.css("border-left-width")),
						W = n.$tooltip.css("border-left-color");
					else var z = parseFloat(n.$tooltip.css("border-bottom-width")),
						W = n.$tooltip.css("border-bottom-color");
					z > 1 && z++;
					var X = "";
					if (z !== 0) {
						var V = "",
							J = "border-color: " + W + ";";
						R.indexOf("bottom") !== -1 ? V = "margin-top: -" + Math.round(z) + "px;" : R.indexOf("top") !== -1 ? V = "margin-bottom: -" + Math.round(z) + "px;" : R.indexOf("left") !== -1 ? V = "margin-right: -" + Math.round(z) + "px;" : R.indexOf("right") !== -1 && (V = "margin-left: -" + Math.round(z) + "px;"), X = '<span class="tooltipster-arrow-border" style="' + V + " " + J + ';"></span>'
					}
					n.$tooltip.find(".tooltipster-arrow").remove();
					var K = '<div class="' + R + ' tooltipster-arrow" style="' + r + '">' + X + '<span style="border-color:' + U + ';"></span></div>';
					n.$tooltip.append(K)
				}
				n.$tooltip.css({
					top: Math.round(M) + "px",
					left: Math.round(A) + "px"
				})
			}
			return n
		},
		enable: function() {
			return this.enabled = !0, this
		},
		disable: function() {
			return this.hide(), this.enabled = !1, this
		},
		destroy: function() {
			var t = this;
			t.hide(), t.$el[0] !== t.$elProxy[0] && t.$elProxy.remove(), t.$el.removeData(t.namespace).off("." + t.namespace);
			var n = t.$el.data("tooltipster-ns");
			if (n.length === 1) {
				var r = null;
				t.options.restoration === "previous" ? r = t.$el.data("tooltipster-initialTitle") : t.options.restoration === "current" && (r = typeof t.Content == "string" ? t.Content : e("<div></div>").append(t.Content).html()), r && t.$el.attr("title", r), t.$el.removeClass("tooltipstered").removeData("tooltipster-ns").removeData("tooltipster-initialTitle")
			} else n = e.grep(n, function(e, n) {
				return e !== t.namespace
			}), t.$el.data("tooltipster-ns", n);
			return t
		},
		elementIcon: function() {
			return this.$el[0] !== this.$elProxy[0] ? this.$elProxy[0] : undefined
		},
		elementTooltip: function() {
			return this.$tooltip ? this.$tooltip[0] : undefined
		},
		option: function(e, t) {
			return typeof t == "undefined" ? this.options[e] : (this.options[e] = t, this)
		},
		status: function() {
			return this.Status
		}
	}, e.fn[r] = function() {
		var t = arguments;
		if (this.length === 0) {
			if (typeof t[0] == "string") {
				var n = !0;
				switch (t[0]) {
				case "setDefaults":
					e.extend(i, t[1]);
					break;
				default:
					n = !1
				}
				return n ? !0 : this
			}
			return this
		}
		if (typeof t[0] == "string") {
			var r = "#*$~&";
			return this.each(function() {
				var n = e(this).data("tooltipster-ns"),
					i = n ? e(this).data(n[0]) : null;
				if (!i) throw new Error("You called Tooltipster's \"" + t[0] + '" method on an uninitialized element');
				if (typeof i[t[0]] != "function") throw new Error('Unknown method .tooltipster("' + t[0] + '")');
				var s = i[t[0]](t[1], t[2]);
				if (s !== i) return r = s, !1
			}), r !== "#*$~&" ? r : this
		}
		var o = [],
			u = t[0] && typeof t[0].multiple != "undefined",
			a = u && t[0].multiple || !u && i.multiple,
			f = t[0] && typeof t[0].debug != "undefined",
			l = f && t[0].debug || !f && i.debug;
		return this.each(function() {
			var n = !1,
				r = e(this).data("tooltipster-ns"),
				i = null;
			r ? a ? n = !0 : l && console.log('Tooltipster: one or more tooltips are already attached to this element: ignoring. Use the "multiple" option to attach more tooltips.') : n = !0, n && (i = new s(this, t[0]), r || (r = []), r.push(i.namespace), e(this).data("tooltipster-ns", r), e(this).data(i.namespace, i)), o.push(i)
		}), a ? o : this
	};
	var u = "ontouchstart" in t,
		a = !1;
	e("body").one("mousemove", function() {
		a = !0
	})
}(jQuery, window, document), +
function(e) {
	"use strict";

	function n(n, r) {
		return this.each(function() {
			var i = e(this),
				s = i.data("bs.modal"),
				o = e.extend({}, t.DEFAULTS, i.data(), typeof n == "object" && n);
			s || i.data("bs.modal", s = new t(this, o)), typeof n == "string" ? s[n](r) : o.show && s.show(r)
		})
	}
	var t = function(t, n) {
			this.options = n, this.$body = e(document.body), this.$element = e(t), this.$dialog = this.$element.find(".modal-dialog"), this.$backdrop = null, this.isShown = null, this.originalBodyPad = null, this.scrollbarWidth = 0, this.ignoreBackdropClick = !1, this.options.remote && this.$element.find(".modal-content").load(this.options.remote, e.proxy(function() {
				this.$element.trigger("loaded.bs.modal")
			}, this))
		};
	t.VERSION = "3.3.6", t.TRANSITION_DURATION = 300, t.BACKDROP_TRANSITION_DURATION = 150, t.DEFAULTS = {
		backdrop: !0,
		keyboard: !0,
		show: !0
	}, t.prototype.toggle = function(e) {
		return this.isShown ? this.hide() : this.show(e)
	}, t.prototype.show = function(n) {
		var r = this,
			i = e.Event("show.bs.modal", {
				relatedTarget: n
			});
		this.$element.trigger(i);
		if (this.isShown || i.isDefaultPrevented()) return;
		this.isShown = !0, this.checkScrollbar(), this.setScrollbar(), this.$body.addClass("modal-open"), this.escape(), this.resize(), this.$element.on("click.dismiss.bs.modal", '[data-dismiss="modal"]', e.proxy(this.hide, this)), this.$dialog.on("mousedown.dismiss.bs.modal", function() {
			r.$element.one("mouseup.dismiss.bs.modal", function(t) {
				e(t.target).is(r.$element) && (r.ignoreBackdropClick = !0)
			})
		}), this.backdrop(function() {
			var i = e.support.transition && r.$element.hasClass("fade");
			r.$element.parent().length || r.$element.appendTo(r.$body), r.$element.show().scrollTop(0), r.adjustDialog(), i && r.$element[0].offsetWidth, r.$element.addClass("in"), r.enforceFocus();
			var s = e.Event("shown.bs.modal", {
				relatedTarget: n
			});
			i ? r.$dialog.one("bsTransitionEnd", function() {
				r.$element.trigger("focus").trigger(s)
			}).emulateTransitionEnd(t.TRANSITION_DURATION) : r.$element.trigger("focus").trigger(s)
		})
	}, t.prototype.hide = function(n) {
		n && n.preventDefault(), n = e.Event("hide.bs.modal"), this.$element.trigger(n);
		if (!this.isShown || n.isDefaultPrevented()) return;
		this.isShown = !1, this.escape(), this.resize(), e(document).off("focusin.bs.modal"), this.$element.removeClass("in").off("click.dismiss.bs.modal").off("mouseup.dismiss.bs.modal"), this.$dialog.off("mousedown.dismiss.bs.modal"), e.support.transition && this.$element.hasClass("fade") ? this.$element.one("bsTransitionEnd", e.proxy(this.hideModal, this)).emulateTransitionEnd(t.TRANSITION_DURATION) : this.hideModal()
	}, t.prototype.enforceFocus = function() {
		e(document).off("focusin.bs.modal").on("focusin.bs.modal", e.proxy(function(e) {
			this.$element[0] !== e.target && !this.$element.has(e.target).length && this.$element.trigger("focus")
		}, this))
	}, t.prototype.escape = function() {
		this.isShown && this.options.keyboard ? this.$element.on("keydown.dismiss.bs.modal", e.proxy(function(e) {
			e.which == 27 && this.hide()
		}, this)) : this.isShown || this.$element.off("keydown.dismiss.bs.modal")
	}, t.prototype.resize = function() {
		this.isShown ? e(window).on("resize.bs.modal", e.proxy(this.handleUpdate, this)) : e(window).off("resize.bs.modal")
	}, t.prototype.hideModal = function() {
		var e = this;
		this.$element.hide(), this.backdrop(function() {
			e.$body.removeClass("modal-open"), e.resetAdjustments(), e.resetScrollbar(), e.$element.trigger("hidden.bs.modal")
		})
	}, t.prototype.removeBackdrop = function() {
		this.$backdrop && this.$backdrop.remove(), this.$backdrop = null
	}, t.prototype.backdrop = function(n) {
		var r = this,
			i = this.$element.hasClass("fade") ? "fade" : "";
		if (this.isShown && this.options.backdrop) {
			var s = e.support.transition && i;
			this.$backdrop = e(document.createElement("div")).addClass("modal-backdrop " + i).appendTo(this.$body), this.$element.on("click.dismiss.bs.modal", e.proxy(function(e) {
				if (this.ignoreBackdropClick) {
					this.ignoreBackdropClick = !1;
					return
				}
				if (e.target !== e.currentTarget) return;
				this.options.backdrop == "static" ? this.$element[0].focus() : this.hide()
			}, this)), s && this.$backdrop[0].offsetWidth, this.$backdrop.addClass("in");
			if (!n) return;
			s ? this.$backdrop.one("bsTransitionEnd", n).emulateTransitionEnd(t.BACKDROP_TRANSITION_DURATION) : n()
		} else if (!this.isShown && this.$backdrop) {
			this.$backdrop.removeClass("in");
			var o = function() {
					r.removeBackdrop(), n && n()
				};
			e.support.transition && this.$element.hasClass("fade") ? this.$backdrop.one("bsTransitionEnd", o).emulateTransitionEnd(t.BACKDROP_TRANSITION_DURATION) : o()
		} else n && n()
	}, t.prototype.handleUpdate = function() {
		this.adjustDialog()
	}, t.prototype.adjustDialog = function() {
		var e = this.$element[0].scrollHeight > document.documentElement.clientHeight;
		this.$element.css({
			paddingLeft: !this.bodyIsOverflowing && e ? this.scrollbarWidth : "",
			paddingRight: this.bodyIsOverflowing && !e ? this.scrollbarWidth : ""
		})
	}, t.prototype.resetAdjustments = function() {
		this.$element.css({
			paddingLeft: "",
			paddingRight: ""
		})
	}, t.prototype.checkScrollbar = function() {
		var e = window.innerWidth;
		if (!e) {
			var t = document.documentElement.getBoundingClientRect();
			e = t.right - Math.abs(t.left)
		}
		this.bodyIsOverflowing = document.body.clientWidth < e, this.scrollbarWidth = this.measureScrollbar()
	}, t.prototype.setScrollbar = function() {
		var e = parseInt(this.$body.css("padding-right") || 0, 10);
		this.originalBodyPad = document.body.style.paddingRight || "", this.bodyIsOverflowing && this.$body.css("padding-right", e + this.scrollbarWidth)
	}, t.prototype.resetScrollbar = function() {
		this.$body.css("padding-right", this.originalBodyPad)
	}, t.prototype.measureScrollbar = function() {
		var e = document.createElement("div");
		e.className = "modal-scrollbar-measure", this.$body.append(e);
		var t = e.offsetWidth - e.clientWidth;
		return this.$body[0].removeChild(e), t
	};
	var r = e.fn.modal;
	e.fn.modal = n, e.fn.modal.Constructor = t, e.fn.modal.noConflict = function() {
		return e.fn.modal = r, this
	}, e(document).on("click.bs.modal.data-api", '[data-toggle="modal"]', function(t) {
		var r = e(this),
			i = r.attr("href"),
			s = e(r.attr("data-target") || i && i.replace(/.*(?=#[^\s]+$)/, "")),
			o = s.data("bs.modal") ? "toggle" : e.extend({
				remote: !/#/.test(i) && i
			}, s.data(), r.data());
		r.is("a") && t.preventDefault(), s.one("show.bs.modal", function(e) {
			if (e.isDefaultPrevented()) return;
			s.one("hidden.bs.modal", function() {
				r.is(":visible") && r.trigger("focus")
			})
		}), n.call(s, o, this)
	})
}(jQuery), +
function(e) {
	"use strict";

	function n(t) {
		var n, r = t.attr("data-target") || (n = t.attr("href")) && n.replace(/.*(?=#[^\s]+$)/, "");
		return e(r)
	}
	function r(n) {
		return this.each(function() {
			var r = e(this),
				i = r.data("bs.collapse"),
				s = e.extend({}, t.DEFAULTS, r.data(), typeof n == "object" && n);
			!i && s.toggle && /show|hide/.test(n) && (s.toggle = !1), i || r.data("bs.collapse", i = new t(this, s)), typeof n == "string" && i[n]()
		})
	}
	var t = function(n, r) {
			this.$element = e(n), this.options = e.extend({}, t.DEFAULTS, r), this.$trigger = e('[data-toggle="collapse"][href="#' + n.id + '"],' + '[data-toggle="collapse"][data-target="#' + n.id + '"]'), this.transitioning = null, this.options.parent ? this.$parent = this.getParent() : this.addAriaAndCollapsedClass(this.$element, this.$trigger), this.options.toggle && this.toggle()
		};
	t.VERSION = "3.3.6", t.TRANSITION_DURATION = 350, t.DEFAULTS = {
		toggle: !0
	}, t.prototype.dimension = function() {
		var e = this.$element.hasClass("width");
		return e ? "width" : "height"
	}, t.prototype.show = function() {
		if (this.transitioning || this.$element.hasClass("in")) return;
		var n, i = this.$parent && this.$parent.children(".panel").children(".in, .collapsing");
		if (i && i.length) {
			n = i.data("bs.collapse");
			if (n && n.transitioning) return
		}
		var s = e.Event("show.bs.collapse");
		this.$element.trigger(s);
		if (s.isDefaultPrevented()) return;
		i && i.length && (r.call(i, "hide"), n || i.data("bs.collapse", null));
		var o = this.dimension();
		this.$element.removeClass("collapse").addClass("collapsing")[o](0).attr("aria-expanded", !0), this.$trigger.removeClass("collapsed").attr("aria-expanded", !0), this.transitioning = 1;
		var u = function() {
				this.$element.removeClass("collapsing").addClass("collapse in")[o](""), this.transitioning = 0, this.$element.trigger("shown.bs.collapse")
			};
		if (!e.support.transition) return u.call(this);
		var a = e.camelCase(["scroll", o].join("-"));
		this.$element.one("bsTransitionEnd", e.proxy(u, this)).emulateTransitionEnd(t.TRANSITION_DURATION)[o](this.$element[0][a])
	}, t.prototype.hide = function() {
		if (this.transitioning || !this.$element.hasClass("in")) return;
		var n = e.Event("hide.bs.collapse");
		this.$element.trigger(n);
		if (n.isDefaultPrevented()) return;
		var r = this.dimension();
		this.$element[r](this.$element[r]())[0].offsetHeight, this.$element.addClass("collapsing").removeClass("collapse in").attr("aria-expanded", !1), this.$trigger.addClass("collapsed").attr("aria-expanded", !1), this.transitioning = 1;
		var i = function() {
				this.transitioning = 0, this.$element.removeClass("collapsing").addClass("collapse").trigger("hidden.bs.collapse")
			};
		if (!e.support.transition) return i.call(this);
		this.$element[r](0).one("bsTransitionEnd", e.proxy(i, this)).emulateTransitionEnd(t.TRANSITION_DURATION)
	}, t.prototype.toggle = function() {
		this[this.$element.hasClass("in") ? "hide" : "show"]()
	}, t.prototype.getParent = function() {
		return e(this.options.parent).find('[data-toggle="collapse"][data-parent="' + this.options.parent + '"]').each(e.proxy(function(t, r) {
			var i = e(r);
			this.addAriaAndCollapsedClass(n(i), i)
		}, this)).end()
	}, t.prototype.addAriaAndCollapsedClass = function(e, t) {
		var n = e.hasClass("in");
		e.attr("aria-expanded", n), t.toggleClass("collapsed", !n).attr("aria-expanded", n)
	};
	var i = e.fn.collapse;
	e.fn.collapse = r, e.fn.collapse.Constructor = t, e.fn.collapse.noConflict = function() {
		return e.fn.collapse = i, this
	}, e(document).on("click.bs.collapse.data-api", '[data-toggle="collapse"]', function(t) {
		var i = e(this);
		i.attr("data-target") || t.preventDefault();
		var s = n(i),
			o = s.data("bs.collapse"),
			u = o ? "toggle" : i.data();
		r.call(s, u)
	})
}(jQuery), function(e, t) {
	function n() {
		return new Date(Date.UTC.apply(Date, arguments))
	}
	function r() {
		var e = new Date;
		return n(e.getFullYear(), e.getMonth(), e.getDate())
	}
	function i(e, t) {
		return e.getUTCFullYear() === t.getUTCFullYear() && e.getUTCMonth() === t.getUTCMonth() && e.getUTCDate() === t.getUTCDate()
	}
	function s(e) {
		return function() {
			return this[e].apply(this, arguments)
		}
	}
	function f(t, n) {
		function u(e, t) {
			return t.toLowerCase()
		}
		var r = e(t).data(),
			i = {},
			s, o = new RegExp("^" + n.toLowerCase() + "([A-Z])");
		n = new RegExp("^" + n.toLowerCase());
		for (var a in r) n.test(a) && (s = a.replace(o, u), i[s] = r[a]);
		return i
	}
	function l(t) {
		var n = {};
		if (!v[t]) {
			t = t.split("-")[0];
			if (!v[t]) return
		}
		var r = v[t];
		return e.each(d, function(e, t) {
			t in r && (n[t] = r[t])
		}), n
	}
	var o = function() {
			var t = {
				get: function(e) {
					return this.slice(e)[0]
				},
				contains: function(e) {
					var t = e && e.valueOf();
					for (var n = 0, r = this.length; n < r; n++) if (this[n].valueOf() === t) return n;
					return -1
				},
				remove: function(e) {
					this.splice(e, 1)
				},
				replace: function(t) {
					if (!t) return;
					e.isArray(t) || (t = [t]), this.clear(), this.push.apply(this, t)
				},
				clear: function() {
					this.length = 0
				},
				copy: function() {
					var e = new o;
					return e.replace(this), e
				}
			};
			return function() {
				var n = [];
				return n.push.apply(n, arguments), e.extend(n, t), n
			}
		}(),
		u = function(t, n) {
			this._process_options(n), this.dates = new o, this.viewDate = this.o.defaultViewDate, this.focusDate = null, this.element = e(t), this.isInline = !1, this.isInput = this.element.is("input"), this.component = this.element.hasClass("date") ? this.element.find(".add-on, .input-group-addon, .btn") : !1, this.hasInput = this.component && this.element.find("input").length, this.component && this.component.length === 0 && (this.component = !1), this.picker = e(m.template), this._buildEvents(), this._attachEvents(), this.isInline ? this.picker.addClass("datepicker-inline").appendTo(this.element) : this.picker.addClass("datepicker-dropdown dropdown-menu"), this.o.rtl && this.picker.addClass("datepicker-rtl"), this.viewMode = this.o.startView, this.o.calendarWeeks && this.picker.find("tfoot .today, tfoot .clear").attr("colspan", function(e, t) {
				return parseInt(t) + 1
			}), this._allow_update = !1, this.setStartDate(this._o.startDate), this.setEndDate(this._o.endDate), this.setDaysOfWeekDisabled(this.o.daysOfWeekDisabled), this.setDatesDisabled(this.o.datesDisabled), this.fillDow(), this.fillMonths(), this._allow_update = !0, this.update(), this.showMode(), this.isInline && this.show()
		};
	u.prototype = {
		constructor: u,
		_process_options: function(i) {
			this._o = e.extend({}, this._o, i);
			var s = this.o = e.extend({}, this._o),
				o = s.language;
			v[o] || (o = o.split("-")[0], v[o] || (o = p.language)), s.language = o;
			switch (s.startView) {
			case 2:
			case "decade":
				s.startView = 2;
				break;
			case 1:
			case "year":
				s.startView = 1;
				break;
			default:
				s.startView = 0
			}
			switch (s.minViewMode) {
			case 1:
			case "months":
				s.minViewMode = 1;
				break;
			case 2:
			case "years":
				s.minViewMode = 2;
				break;
			default:
				s.minViewMode = 0
			}
			s.startView = Math.max(s.startView, s.minViewMode), s.multidate !== !0 && (s.multidate = Number(s.multidate) || !1, s.multidate !== !1 && (s.multidate = Math.max(0, s.multidate))), s.multidateSeparator = String(s.multidateSeparator), s.weekStart %= 7, s.weekEnd = (s.weekStart + 6) % 7;
			var u = m.parseFormat(s.format);
			s.startDate !== -Infinity && (s.startDate ? s.startDate instanceof Date ? s.startDate = this._local_to_utc(this._zero_time(s.startDate)) : s.startDate = m.parseDate(s.startDate, u, s.language) : s.startDate = -Infinity), s.endDate !== Infinity && (s.endDate ? s.endDate instanceof Date ? s.endDate = this._local_to_utc(this._zero_time(s.endDate)) : s.endDate = m.parseDate(s.endDate, u, s.language) : s.endDate = Infinity), s.daysOfWeekDisabled = s.daysOfWeekDisabled || [], e.isArray(s.daysOfWeekDisabled) || (s.daysOfWeekDisabled = s.daysOfWeekDisabled.split(/[,\s]*/)), s.daysOfWeekDisabled = e.map(s.daysOfWeekDisabled, function(e) {
				return parseInt(e, 10)
			}), s.datesDisabled = s.datesDisabled || [];
			if (!e.isArray(s.datesDisabled)) {
				var a = [];
				a.push(m.parseDate(s.datesDisabled, u, s.language)), s.datesDisabled = a
			}
			s.datesDisabled = e.map(s.datesDisabled, function(e) {
				return m.parseDate(e, u, s.language)
			});
			var f = String(s.orientation).toLowerCase().split(/\s+/g),
				l = s.orientation.toLowerCase();
			f = e.grep(f, function(e) {
				return /^auto|left|right|top|bottom$/.test(e)
			}), s.orientation = {
				x: "auto",
				y: "auto"
			};
			if ( !! l && l !== "auto") if (f.length === 1) switch (f[0]) {
			case "top":
			case "bottom":
				s.orientation.y = f[0];
				break;
			case "left":
			case "right":
				s.orientation.x = f[0]
			} else l = e.grep(f, function(e) {
				return /^left|right$/.test(e)
			}), s.orientation.x = l[0] || "auto", l = e.grep(f, function(e) {
				return /^top|bottom$/.test(e)
			}), s.orientation.y = l[0] || "auto";
			if (s.defaultViewDate) {
				var c = s.defaultViewDate.year || (new Date).getFullYear(),
					h = s.defaultViewDate.month || 0,
					d = s.defaultViewDate.day || 1;
				s.defaultViewDate = n(c, h, d)
			} else s.defaultViewDate = r();
			s.showOnFocus = s.showOnFocus !== t ? s.showOnFocus : !0
		},
		_events: [],
		_secondaryEvents: [],
		_applyEvents: function(e) {
			for (var n = 0, r, i, s; n < e.length; n++) r = e[n][0], e[n].length === 2 ? (i = t, s = e[n][1]) : e[n].length === 3 && (i = e[n][1], s = e[n][2]), r.on(s, i)
		},
		_unapplyEvents: function(e) {
			for (var n = 0, r, i, s; n < e.length; n++) r = e[n][0], e[n].length === 2 ? (s = t, i = e[n][1]) : e[n].length === 3 && (s = e[n][1], i = e[n][2]), r.off(i, s)
		},
		_buildEvents: function() {
			var t = {
				keyup: e.proxy(function(t) {
					e.inArray(t.keyCode, [27, 37, 39, 38, 40, 32, 13, 9]) === -1 && this.update()
				}, this),
				keydown: e.proxy(this.keydown, this)
			};
			this.o.showOnFocus === !0 && (t.focus = e.proxy(this.show, this)), this.isInput ? this._events = [
				[this.element, t]
			] : this.component && this.hasInput ? this._events = [
				[this.element.find("input"), t],
				[this.component,
				{
					click: e.proxy(this.show, this)
				}]
			] : this.element.is("div") ? this.isInline = !0 : this._events = [
				[this.element,
				{
					click: e.proxy(this.show, this)
				}]
			], this._events.push([this.element, "*",
			{
				blur: e.proxy(function(e) {
					this._focused_from = e.target
				}, this)
			}], [this.element,
			{
				blur: e.proxy(function(e) {
					this._focused_from = e.target
				}, this)
			}]), this._secondaryEvents = [
				[this.picker,
				{
					click: e.proxy(this.click, this)
				}],
				[e(window),
				{
					resize: e.proxy(this.place, this)
				}],
				[e(document),
				{
					"mousedown touchstart": e.proxy(function(e) {
						this.element.is(e.target) || this.element.find(e.target).length || this.picker.is(e.target) || this.picker.find(e.target).length || this.hide()
					}, this)
				}]
			]
		},
		_attachEvents: function() {
			this._detachEvents(), this._applyEvents(this._events)
		},
		_detachEvents: function() {
			this._unapplyEvents(this._events)
		},
		_attachSecondaryEvents: function() {
			this._detachSecondaryEvents(), this._applyEvents(this._secondaryEvents)
		},
		_detachSecondaryEvents: function() {
			this._unapplyEvents(this._secondaryEvents)
		},
		_trigger: function(t, n) {
			var r = n || this.dates.get(-1),
				i = this._utc_to_local(r);
			this.element.trigger({
				type: t,
				date: i,
				dates: e.map(this.dates, this._utc_to_local),
				format: e.proxy(function(e, t) {
					arguments.length === 0 ? (e = this.dates.length - 1, t = this.o.format) : typeof e == "string" && (t = e, e = this.dates.length - 1), t = t || this.o.format;
					var n = this.dates.get(e);
					return m.formatDate(n, t, this.o.language)
				}, this)
			})
		},
		show: function() {
			if (this.element.attr("readonly") && this.o.enableOnReadonly === !1) return;
			return this.isInline || this.picker.appendTo(this.o.container), this.place(), this.picker.show(), this._attachSecondaryEvents(), this._trigger("show"), (window.navigator.msMaxTouchPoints || "ontouchstart" in document) && this.o.disableTouchKeyboard && e(this.element).blur(), this
		},
		hide: function() {
			return this.isInline ? this : this.picker.is(":visible") ? (this.focusDate = null, this.picker.hide().detach(), this._detachSecondaryEvents(), this.viewMode = this.o.startView, this.showMode(), this.o.forceParse && (this.isInput && this.element.val() || this.hasInput && this.element.find("input").val()) && this.setValue(), this._trigger("hide"), this) : this
		},
		remove: function() {
			return this.hide(), this._detachEvents(), this._detachSecondaryEvents(), this.picker.remove(), delete this.element.data().datepicker, this.isInput || delete this.element.data().date, this
		},
		_utc_to_local: function(e) {
			return e && new Date(e.getTime() + e.getTimezoneOffset() * 6e4)
		},
		_local_to_utc: function(e) {
			return e && new Date(e.getTime() - e.getTimezoneOffset() * 6e4)
		},
		_zero_time: function(e) {
			return e && new Date(e.getFullYear(), e.getMonth(), e.getDate())
		},
		_zero_utc_time: function(e) {
			return e && new Date(Date.UTC(e.getUTCFullYear(), e.getUTCMonth(), e.getUTCDate()))
		},
		getDates: function() {
			return e.map(this.dates, this._utc_to_local)
		},
		getUTCDates: function() {
			return e.map(this.dates, function(e) {
				return new Date(e)
			})
		},
		getDate: function() {
			return this._utc_to_local(this.getUTCDate())
		},
		getUTCDate: function() {
			var e = this.dates.get(-1);
			return typeof e != "undefined" ? new Date(e) : null
		},
		clearDates: function() {
			var e;
			this.isInput ? e = this.element : this.component && (e = this.element.find("input")), e && e.val("").change(), this.update(), this._trigger("changeDate"), this.o.autoclose && this.hide()
		},
		setDates: function() {
			var t = e.isArray(arguments[0]) ? arguments[0] : arguments;
			return this.update.apply(this, t), this._trigger("changeDate"), this.setValue(), this
		},
		setUTCDates: function() {
			var t = e.isArray(arguments[0]) ? arguments[0] : arguments;
			return this.update.apply(this, e.map(t, this._utc_to_local)), this._trigger("changeDate"), this.setValue(), this
		},
		setDate: s("setDates"),
		setUTCDate: s("setUTCDates"),
		setValue: function() {
			var e = this.getFormattedDate();
			return this.isInput ? this.element.val(e).change() : this.component && this.element.find("input").val(e).change(), this
		},
		getFormattedDate: function(n) {
			n === t && (n = this.o.format);
			var r = this.o.language;
			return e.map(this.dates, function(e) {
				return m.formatDate(e, n, r)
			}).join(this.o.multidateSeparator)
		},
		setStartDate: function(e) {
			return this._process_options({
				startDate: e
			}), this.update(), this.updateNavArrows(), this
		},
		setEndDate: function(e) {
			return this._process_options({
				endDate: e
			}), this.update(), this.updateNavArrows(), this
		},
		setDaysOfWeekDisabled: function(e) {
			return this._process_options({
				daysOfWeekDisabled: e
			}), this.update(), this.updateNavArrows(), this
		},
		setDatesDisabled: function(e) {
			this._process_options({
				datesDisabled: e
			}), this.update(), this.updateNavArrows()
		},
		place: function() {
			if (this.isInline) return this;
			var t = this.picker.outerWidth(),
				n = this.picker.outerHeight(),
				r = 10,
				i = e(this.o.container).width(),
				s = e(this.o.container).height(),
				o = e(this.o.container).scrollTop(),
				u = e(this.o.container).offset(),
				a = [];
			this.element.parents().each(function() {
				var t = e(this).css("z-index");
				t !== "auto" && t !== 0 && a.push(parseInt(t))
			});
			var f = Math.max.apply(Math, a) + 10,
				l = this.component ? this.component.parent().offset() : this.element.offset(),
				c = this.component ? this.component.outerHeight(!0) : this.element.outerHeight(!1),
				h = this.component ? this.component.outerWidth(!0) : this.element.outerWidth(!1),
				p = l.left - u.left,
				d = l.top - u.top;
			this.picker.removeClass("datepicker-orient-top datepicker-orient-bottom datepicker-orient-right datepicker-orient-left"), this.o.orientation.x !== "auto" ? (this.picker.addClass("datepicker-orient-" + this.o.orientation.x), this.o.orientation.x === "right" && (p -= t - h)) : l.left < 0 ? (this.picker.addClass("datepicker-orient-left"), p -= l.left - r) : p + t > i ? (this.picker.addClass("datepicker-orient-right"), p = l.left + h - t) : this.picker.addClass("datepicker-orient-left");
			var v = this.o.orientation.y,
				m, g;
			v === "auto" && (m = -o + d - n, g = o + s - (d + c + n), Math.max(m, g) === g ? v = "top" : v = "bottom"), this.picker.addClass("datepicker-orient-" + v), v === "top" ? d += c : d -= n + parseInt(this.picker.css("padding-top"));
			if (this.o.rtl) {
				var y = i - (p + h);
				this.picker.css({
					top: d,
					right: y,
					zIndex: f
				})
			} else this.picker.css({
				top: d,
				left: p,
				zIndex: f
			});
			return this
		},
		_allow_update: !0,
		update: function() {
			if (!this._allow_update) return this;
			var t = this.dates.copy(),
				n = [],
				r = !1;
			return arguments.length ? (e.each(arguments, e.proxy(function(e, t) {
				t instanceof Date && (t = this._local_to_utc(t)), n.push(t)
			}, this)), r = !0) : (n = this.isInput ? this.element.val() : this.element.data("date") || this.element.find("input").val(), n && this.o.multidate ? n = n.split(this.o.multidateSeparator) : n = [n], delete this.element.data().date), n = e.map(n, e.proxy(function(e) {
				return m.parseDate(e, this.o.format, this.o.language)
			}, this)), n = e.grep(n, e.proxy(function(e) {
				return e < this.o.startDate || e > this.o.endDate || !e
			}, this), !0), this.dates.replace(n), this.dates.length ? this.viewDate = new Date(this.dates.get(-1)) : this.viewDate < this.o.startDate ? this.viewDate = new Date(this.o.startDate) : this.viewDate > this.o.endDate && (this.viewDate = new Date(this.o.endDate)), r ? this.setValue() : n.length && String(t) !== String(this.dates) && this._trigger("changeDate"), !this.dates.length && t.length && this._trigger("clearDate"), this.fill(), this
		},
		fillDow: function() {
			var e = this.o.weekStart,
				t = "<tr>";
			if (this.o.calendarWeeks) {
				this.picker.find(".datepicker-days thead tr:first-child .datepicker-switch").attr("colspan", function(e, t) {
					return parseInt(t) + 1
				});
				var n = '<th class="cw">&#160;</th>';
				t += n
			}
			while (e < this.o.weekStart + 7) t += '<th class="dow">' + v[this.o.language].daysMin[e++ % 7] + "</th>";
			t += "</tr>", this.picker.find(".datepicker-days thead").append(t)
		},
		fillMonths: function() {
			var e = "",
				t = 0;
			while (t < 12) e += '<span class="month">' + v[this.o.language].monthsShort[t++] + "</span>";
			this.picker.find(".datepicker-months td").html(e)
		},
		setRange: function(t) {
			!t || !t.length ? delete this.range : this.range = e.map(t, function(e) {
				return e.valueOf()
			}), this.fill()
		},
		getClassNames: function(t) {
			var n = [],
				r = this.viewDate.getUTCFullYear(),
				s = this.viewDate.getUTCMonth(),
				o = new Date;
			return t.getUTCFullYear() < r || t.getUTCFullYear() === r && t.getUTCMonth() < s ? n.push("old") : (t.getUTCFullYear() > r || t.getUTCFullYear() === r && t.getUTCMonth() > s) && n.push("new"), this.focusDate && t.valueOf() === this.focusDate.valueOf() && n.push("focused"), this.o.todayHighlight && t.getUTCFullYear() === o.getFullYear() && t.getUTCMonth() === o.getMonth() && t.getUTCDate() === o.getDate() && n.push("today"), this.dates.contains(t) !== -1 && n.push("active"), (t.valueOf() < this.o.startDate || t.valueOf() > this.o.endDate || e.inArray(t.getUTCDay(), this.o.daysOfWeekDisabled) !== -1) && n.push("disabled"), this.o.datesDisabled.length > 0 && e.grep(this.o.datesDisabled, function(e) {
				return i(t, e)
			}).length > 0 && n.push("disabled", "disabled-date"), this.range && (t > this.range[0] && t < this.range[this.range.length - 1] && n.push("range"), e.inArray(t.valueOf(), this.range) !== -1 && n.push("selected")), n
		},
		fill: function() {
			var r = new Date(this.viewDate),
				i = r.getUTCFullYear(),
				s = r.getUTCMonth(),
				o = this.o.startDate !== -Infinity ? this.o.startDate.getUTCFullYear() : -Infinity,
				u = this.o.startDate !== -Infinity ? this.o.startDate.getUTCMonth() : -Infinity,
				a = this.o.endDate !== Infinity ? this.o.endDate.getUTCFullYear() : Infinity,
				f = this.o.endDate !== Infinity ? this.o.endDate.getUTCMonth() : Infinity,
				l = v[this.o.language].today || v.en.today || "",
				c = v[this.o.language].clear || v.en.clear || "",
				h;
			if (isNaN(i) || isNaN(s)) return;
			this.picker.find(".datepicker-days thead .datepicker-switch").text(v[this.o.language].months[s] + " " + i), this.picker.find("tfoot .today").text(l).toggle(this.o.todayBtn !== !1), this.picker.find("tfoot .clear").text(c).toggle(this.o.clearBtn !== !1), this.updateNavArrows(), this.fillMonths();
			var p = n(i, s - 1, 28),
				d = m.getDaysInMonth(p.getUTCFullYear(), p.getUTCMonth());
			p.setUTCDate(d), p.setUTCDate(d - (p.getUTCDay() - this.o.weekStart + 7) % 7);
			var g = new Date(p);
			g.setUTCDate(g.getUTCDate() + 42), g = g.valueOf();
			var y = [],
				b;
			while (p.valueOf() < g) {
				if (p.getUTCDay() === this.o.weekStart) {
					y.push("<tr>");
					if (this.o.calendarWeeks) {
						var w = new Date(+p + (this.o.weekStart - p.getUTCDay() - 7) % 7 * 864e5),
							E = new Date(Number(w) + (11 - w.getUTCDay()) % 7 * 864e5),
							S = new Date(Number(S = n(E.getUTCFullYear(), 0, 1)) + (11 - S.getUTCDay()) % 7 * 864e5),
							x = (E - S) / 864e5 / 7 + 1;
						y.push('<td class="cw">' + x + "</td>")
					}
				}
				b = this.getClassNames(p), b.push("day");
				if (this.o.beforeShowDay !== e.noop) {
					var T = this.o.beforeShowDay(this._utc_to_local(p));
					T === t ? T = {} : typeof T == "boolean" ? T = {
						enabled: T
					} : typeof T == "string" && (T = {
						classes: T
					}), T.enabled === !1 && b.push("disabled"), T.classes && (b = b.concat(T.classes.split(/\s+/))), T.tooltip && (h = T.tooltip)
				}
				b = e.unique(b), y.push('<td class="' + b.join(" ") + '"' + (h ? ' title="' + h + '"' : "") + ">" + p.getUTCDate() + "</td>"), h = null, p.getUTCDay() === this.o.weekEnd && y.push("</tr>"), p.setUTCDate(p.getUTCDate() + 1)
			}
			this.picker.find(".datepicker-days tbody").empty().append(y.join(""));
			var N = this.picker.find(".datepicker-months").find("th:eq(1)").text(i).end().find("span").removeClass("active");
			e.each(this.dates, function(e, t) {
				t.getUTCFullYear() === i && N.eq(t.getUTCMonth()).addClass("active")
			}), (i < o || i > a) && N.addClass("disabled"), i === o && N.slice(0, u).addClass("disabled"), i === a && N.slice(f + 1).addClass("disabled");
			if (this.o.beforeShowMonth !== e.noop) {
				var C = this;
				e.each(N, function(t, n) {
					if (!e(n).hasClass("disabled")) {
						var r = new Date(i, t, 1),
							s = C.o.beforeShowMonth(r);
						s === !1 && e(n).addClass("disabled")
					}
				})
			}
			y = "", i = parseInt(i / 10, 10) * 10;
			var k = this.picker.find(".datepicker-years").find("th:eq(1)").text(i + "-" + (i + 9)).end().find("td");
			i -= 1;
			var L = e.map(this.dates, function(e) {
				return e.getUTCFullYear()
			}),
				A;
			for (var O = -1; O < 11; O++) A = ["year"], O === -1 ? A.push("old") : O === 10 && A.push("new"), e.inArray(i, L) !== -1 && A.push("active"), (i < o || i > a) && A.push("disabled"), y += '<span class="' + A.join(" ") + '">' + i + "</span>", i += 1;
			k.html(y)
		},
		updateNavArrows: function() {
			if (!this._allow_update) return;
			var e = new Date(this.viewDate),
				t = e.getUTCFullYear(),
				n = e.getUTCMonth();
			switch (this.viewMode) {
			case 0:
				this.o.startDate !== -Infinity && t <= this.o.startDate.getUTCFullYear() && n <= this.o.startDate.getUTCMonth() ? this.picker.find(".prev").css({
					visibility: "hidden"
				}) : this.picker.find(".prev").css({
					visibility: "visible"
				}), this.o.endDate !== Infinity && t >= this.o.endDate.getUTCFullYear() && n >= this.o.endDate.getUTCMonth() ? this.picker.find(".next").css({
					visibility: "hidden"
				}) : this.picker.find(".next").css({
					visibility: "visible"
				});
				break;
			case 1:
			case 2:
				this.o.startDate !== -Infinity && t <= this.o.startDate.getUTCFullYear() ? this.picker.find(".prev").css({
					visibility: "hidden"
				}) : this.picker.find(".prev").css({
					visibility: "visible"
				}), this.o.endDate !== Infinity && t >= this.o.endDate.getUTCFullYear() ? this.picker.find(".next").css({
					visibility: "hidden"
				}) : this.picker.find(".next").css({
					visibility: "visible"
				})
			}
		},
		click: function(t) {
			t.preventDefault();
			var r = e(t.target).closest("span, td, th"),
				i, s, o;
			if (r.length === 1) switch (r[0].nodeName.toLowerCase()) {
			case "th":
				switch (r[0].className) {
				case "datepicker-switch":
					this.showMode(1);
					break;
				case "prev":
				case "next":
					var u = m.modes[this.viewMode].navStep * (r[0].className === "prev" ? -1 : 1);
					switch (this.viewMode) {
					case 0:
						this.viewDate = this.moveMonth(this.viewDate, u), this._trigger("changeMonth", this.viewDate);
						break;
					case 1:
					case 2:
						this.viewDate = this.moveYear(this.viewDate, u), this.viewMode === 1 && this._trigger("changeYear", this.viewDate)
					}
					this.fill();
					break;
				case "today":
					var a = new Date;
					a = n(a.getFullYear(), a.getMonth(), a.getDate(), 0, 0, 0), this.showMode(-2);
					var f = this.o.todayBtn === "linked" ? null : "view";
					this._setDate(a, f);
					break;
				case "clear":
					this.clearDates()
				}
				break;
			case "span":
				r.hasClass("disabled") || (this.viewDate.setUTCDate(1), r.hasClass("month") ? (o = 1, s = r.parent().find("span").index(r), i = this.viewDate.getUTCFullYear(), this.viewDate.setUTCMonth(s), this._trigger("changeMonth", this.viewDate), this.o.minViewMode === 1 && this._setDate(n(i, s, o))) : (o = 1, s = 0, i = parseInt(r.text(), 10) || 0, this.viewDate.setUTCFullYear(i), this._trigger("changeYear", this.viewDate), this.o.minViewMode === 2 && this._setDate(n(i, s, o))), this.showMode(-1), this.fill());
				break;
			case "td":
				r.hasClass("day") && !r.hasClass("disabled") && (o = parseInt(r.text(), 10) || 1, i = this.viewDate.getUTCFullYear(), s = this.viewDate.getUTCMonth(), r.hasClass("old") ? s === 0 ? (s = 11, i -= 1) : s -= 1 : r.hasClass("new") && (s === 11 ? (s = 0, i += 1) : s += 1), this._setDate(n(i, s, o)))
			}
			this.picker.is(":visible") && this._focused_from && e(this._focused_from).focus(), delete this._focused_from
		},
		_toggle_multidate: function(e) {
			var t = this.dates.contains(e);
			e || this.dates.clear(), t !== -1 ? (this.o.multidate === !0 || this.o.multidate > 1 || this.o.toggleActive) && this.dates.remove(t) : this.o.multidate === !1 ? (this.dates.clear(), this.dates.push(e)) : this.dates.push(e);
			if (typeof this.o.multidate == "number") while (this.dates.length > this.o.multidate) this.dates.remove(0)
		},
		_setDate: function(e, t) {
			(!t || t === "date") && this._toggle_multidate(e && new Date(e));
			if (!t || t === "view") this.viewDate = e && new Date(e);
			this.fill(), this.setValue(), (!t || t !== "view") && this._trigger("changeDate");
			var n;
			this.isInput ? n = this.element : this.component && (n = this.element.find("input")), n && n.change(), this.o.autoclose && (!t || t === "date") && this.hide()
		},
		moveMonth: function(e, n) {
			if (!e) return t;
			if (!n) return e;
			var r = new Date(e.valueOf()),
				i = r.getUTCDate(),
				s = r.getUTCMonth(),
				o = Math.abs(n),
				u, a;
			n = n > 0 ? 1 : -1;
			if (o === 1) {
				a = n === -1 ?
				function() {
					return r.getUTCMonth() === s
				} : function() {
					return r.getUTCMonth() !== u
				}, u = s + n, r.setUTCMonth(u);
				if (u < 0 || u > 11) u = (u + 12) % 12
			} else {
				for (var f = 0; f < o; f++) r = this.moveMonth(r, n);
				u = r.getUTCMonth(), r.setUTCDate(i), a = function() {
					return u !== r.getUTCMonth()
				}
			}
			while (a()) r.setUTCDate(--i), r.setUTCMonth(u);
			return r
		},
		moveYear: function(e, t) {
			return this.moveMonth(e, t * 12)
		},
		dateWithinRange: function(e) {
			return e >= this.o.startDate && e <= this.o.endDate
		},
		keydown: function(e) {
			if (!this.picker.is(":visible")) {
				e.keyCode === 27 && this.show();
				return
			}
			var t = !1,
				n, i, s, o = this.focusDate || this.viewDate;
			switch (e.keyCode) {
			case 27:
				this.focusDate ? (this.focusDate = null, this.viewDate = this.dates.get(-1) || this.viewDate, this.fill()) : this.hide(), e.preventDefault();
				break;
			case 37:
			case 39:
				if (!this.o.keyboardNavigation) break;
				n = e.keyCode === 37 ? -1 : 1, e.ctrlKey ? (i = this.moveYear(this.dates.get(-1) || r(), n), s = this.moveYear(o, n), this._trigger("changeYear", this.viewDate)) : e.shiftKey ? (i = this.moveMonth(this.dates.get(-1) || r(), n), s = this.moveMonth(o, n), this._trigger("changeMonth", this.viewDate)) : (i = new Date(this.dates.get(-1) || r()), i.setUTCDate(i.getUTCDate() + n), s = new Date(o), s.setUTCDate(o.getUTCDate() + n)), this.dateWithinRange(s) && (this.focusDate = this.viewDate = s, this.setValue(), this.fill(), e.preventDefault());
				break;
			case 38:
			case 40:
				if (!this.o.keyboardNavigation) break;
				n = e.keyCode === 38 ? -1 : 1, e.ctrlKey ? (i = this.moveYear(this.dates.get(-1) || r(), n), s = this.moveYear(o, n), this._trigger("changeYear", this.viewDate)) : e.shiftKey ? (i = this.moveMonth(this.dates.get(-1) || r(), n), s = this.moveMonth(o, n), this._trigger("changeMonth", this.viewDate)) : (i = new Date(this.dates.get(-1) || r()), i.setUTCDate(i.getUTCDate() + n * 7), s = new Date(o), s.setUTCDate(o.getUTCDate() + n * 7)), this.dateWithinRange(s) && (this.focusDate = this.viewDate = s, this.setValue(), this.fill(), e.preventDefault());
				break;
			case 32:
				break;
			case 13:
				o = this.focusDate || this.dates.get(-1) || this.viewDate, this.o.keyboardNavigation && (this._toggle_multidate(o), t = !0), this.focusDate = null, this.viewDate = this.dates.get(-1) || this.viewDate, this.setValue(), this.fill(), this.picker.is(":visible") && (e.preventDefault(), typeof e.stopPropagation == "function" ? e.stopPropagation() : e.cancelBubble = !0, this.o.autoclose && this.hide());
				break;
			case 9:
				this.focusDate = null, this.viewDate = this.dates.get(-1) || this.viewDate, this.fill(), this.hide()
			}
			if (t) {
				this.dates.length ? this._trigger("changeDate") : this._trigger("clearDate");
				var u;
				this.isInput ? u = this.element : this.component && (u = this.element.find("input")), u && u.change()
			}
		},
		showMode: function(e) {
			e && (this.viewMode = Math.max(this.o.minViewMode, Math.min(2, this.viewMode + e))), this.picker.children("div").hide().filter(".datepicker-" + m.modes[this.viewMode].clsName).css("display", "block"), this.updateNavArrows()
		}
	};
	var a = function(t, n) {
			this.element = e(t), this.inputs = e.map(n.inputs, function(e) {
				return e.jquery ? e[0] : e
			}), delete n.inputs, h.call(e(this.inputs), n).bind("changeDate", e.proxy(this.dateUpdated, this)), this.pickers = e.map(this.inputs, function(t) {
				return e(t).data("datepicker")
			}), this.updateDates()
		};
	a.prototype = {
		updateDates: function() {
			this.dates = e.map(this.pickers, function(e) {
				return e.getUTCDate()
			}), this.updateRanges()
		},
		updateRanges: function() {
			var t = e.map(this.dates, function(e) {
				return e.valueOf()
			});
			e.each(this.pickers, function(e, n) {
				n.setRange(t)
			})
		},
		dateUpdated: function(t) {
			if (this.updating) return;
			this.updating = !0;
			var n = e(t.target).data("datepicker"),
				r = n.getUTCDate(),
				i = e.inArray(t.target, this.inputs),
				s = i - 1,
				o = i + 1,
				u = this.inputs.length;
			if (i === -1) return;
			e.each(this.pickers, function(e, t) {
				t.getUTCDate() || t.setUTCDate(r)
			});
			if (r < this.dates[s]) while (s >= 0 && r < this.dates[s]) this.pickers[s--].setUTCDate(r);
			else if (r > this.dates[o]) while (o < u && r > this.dates[o]) this.pickers[o++].setUTCDate(r);
			this.updateDates(), delete this.updating
		},
		remove: function() {
			e.map(this.pickers, function(e) {
				e.remove()
			}), delete this.element.data().datepicker
		}
	};
	var c = e.fn.datepicker,
		h = function(n) {
			var r = Array.apply(null, arguments);
			r.shift();
			var i;
			return this.each(function() {
				var s = e(this),
					o = s.data("datepicker"),
					c = typeof n == "object" && n;
				if (!o) {
					var h = f(this, "date"),
						d = e.extend({}, p, h, c),
						v = l(d.language),
						m = e.extend({}, p, v, h, c);
					if (s.hasClass("input-daterange") || m.inputs) {
						var g = {
							inputs: m.inputs || s.find("input").toArray()
						};
						s.data("datepicker", o = new a(this, e.extend(m, g)))
					} else s.data("datepicker", o = new u(this, m))
				}
				if (typeof n == "string" && typeof o[n] == "function") {
					i = o[n].apply(o, r);
					if (i !== t) return !1
				}
			}), i !== t ? i : this
		};
	e.fn.datepicker = h;
	var p = e.fn.datepicker.defaults = {
		autoclose: !1,
		beforeShowDay: e.noop,
		beforeShowMonth: e.noop,
		calendarWeeks: !1,
		clearBtn: !1,
		toggleActive: !1,
		daysOfWeekDisabled: [],
		datesDisabled: [],
		endDate: Infinity,
		forceParse: !0,
		format: "mm/dd/yyyy",
		keyboardNavigation: !0,
		language: "en",
		minViewMode: 0,
		multidate: !1,
		multidateSeparator: ",",
		orientation: "auto",
		rtl: !1,
		startDate: -Infinity,
		startView: 0,
		todayBtn: !1,
		todayHighlight: !1,
		weekStart: 0,
		disableTouchKeyboard: !1,
		enableOnReadonly: !0,
		container: "body"
	},
		d = e.fn.datepicker.locale_opts = ["format", "rtl", "weekStart"];
	e.fn.datepicker.Constructor = u;
	var v = e.fn.datepicker.dates = {
		en: {
			days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
			daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
			daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
			months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
			monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
			today: "Today",
			clear: "Clear"
		}
	},
		m = {
			modes: [{
				clsName: "days",
				navFnc: "Month",
				navStep: 1
			}, {
				clsName: "months",
				navFnc: "FullYear",
				navStep: 1
			}, {
				clsName: "years",
				navFnc: "FullYear",
				navStep: 10
			}],
			isLeapYear: function(e) {
				return e % 4 === 0 && e % 100 !== 0 || e % 400 === 0
			},
			getDaysInMonth: function(e, t) {
				return [31, m.isLeapYear(e) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][t]
			},
			validParts: /dd?|DD?|mm?|MM?|yy(?:yy)?/g,
			nonpunctuation: /[^ -\/:-@\[\u3400-\u9fff-`{-~\t\n\r]+/g,
			parseFormat: function(e) {
				var t = e.replace(this.validParts, "\0").split("\0"),
					n = e.match(this.validParts);
				if (!t || !t.length || !n || n.length === 0) throw new Error("Invalid date format.");
				return {
					separators: t,
					parts: n
				}
			},
			parseDate: function(r, i, s) {
				function w() {
					var e = this.slice(0, a[c].length),
						t = a[c].slice(0, e.length);
					return e.toLowerCase() === t.toLowerCase()
				}
				if (!r) return t;
				if (r instanceof Date) return r;
				typeof i == "string" && (i = m.parseFormat(i));
				var o = /([\-+]\d+)([dmwy])/,
					a = r.match(/([\-+]\d+)([dmwy])/g),
					f, l, c;
				if (/^[\-+]\d+[dmwy]([\s,]+[\-+]\d+[dmwy])*$/.test(r)) {
					r = new Date;
					for (c = 0; c < a.length; c++) {
						f = o.exec(a[c]), l = parseInt(f[1]);
						switch (f[2]) {
						case "d":
							r.setUTCDate(r.getUTCDate() + l);
							break;
						case "m":
							r = u.prototype.moveMonth.call(u.prototype, r, l);
							break;
						case "w":
							r.setUTCDate(r.getUTCDate() + l * 7);
							break;
						case "y":
							r = u.prototype.moveYear.call(u.prototype, r, l)
						}
					}
					return n(r.getUTCFullYear(), r.getUTCMonth(), r.getUTCDate(), 0, 0, 0)
				}
				a = r && r.match(this.nonpunctuation) || [], r = new Date;
				var h = {},
					p = ["yyyy", "yy", "M", "MM", "m", "mm", "d", "dd"],
					d = {
						yyyy: function(e, t) {
							return e.setUTCFullYear(t)
						},
						yy: function(e, t) {
							return e.setUTCFullYear(2e3 + t)
						},
						m: function(e, t) {
							if (isNaN(e)) return e;
							t -= 1;
							while (t < 0) t += 12;
							t %= 12, e.setUTCMonth(t);
							while (e.getUTCMonth() !== t) e.setUTCDate(e.getUTCDate() - 1);
							return e
						},
						d: function(e, t) {
							return e.setUTCDate(t)
						}
					},
					g, y;
				d.M = d.MM = d.mm = d.m, d.dd = d.d, r = n(r.getFullYear(), r.getMonth(), r.getDate(), 0, 0, 0);
				var b = i.parts.slice();
				a.length !== b.length && (b = e(b).filter(function(t, n) {
					return e.inArray(n, p) !== -1
				}).toArray());
				if (a.length === b.length) {
					var E;
					for (c = 0, E = b.length; c < E; c++) {
						g = parseInt(a[c], 10), f = b[c];
						if (isNaN(g)) switch (f) {
						case "MM":
							y = e(v[s].months).filter(w), g = e.inArray(y[0], v[s].months) + 1;
							break;
						case "M":
							y = e(v[s].monthsShort).filter(w), g = e.inArray(y[0], v[s].monthsShort) + 1
						}
						h[f] = g
					}
					var S, x;
					for (c = 0; c < p.length; c++) x = p[c], x in h && !isNaN(h[x]) && (S = new Date(r), d[x](S, h[x]), isNaN(S) || (r = S))
				}
				return r
			},
			formatDate: function(t, n, r) {
				if (!t) return "";
				typeof n == "string" && (n = m.parseFormat(n));
				var i = {
					d: t.getUTCDate(),
					D: v[r].daysShort[t.getUTCDay()],
					DD: v[r].days[t.getUTCDay()],
					m: t.getUTCMonth() + 1,
					M: v[r].monthsShort[t.getUTCMonth()],
					MM: v[r].months[t.getUTCMonth()],
					yy: t.getUTCFullYear().toString().substring(2),
					yyyy: t.getUTCFullYear()
				};
				i.dd = (i.d < 10 ? "0" : "") + i.d, i.mm = (i.m < 10 ? "0" : "") + i.m, t = [];
				var s = e.extend([], n.separators);
				for (var o = 0, u = n.parts.length; o <= u; o++) s.length && t.push(s.shift()), t.push(i[n.parts[o]]);
				return t.join("")
			},
			headTemplate: '<thead><tr><th class="prev">&#171;</th><th colspan="5" class="datepicker-switch"></th><th class="next">&#187;</th></tr></thead>',
			contTemplate: '<tbody><tr><td colspan="7"></td></tr></tbody>',
			footTemplate: '<tfoot><tr><th colspan="7" class="today"></th></tr><tr><th colspan="7" class="clear"></th></tr></tfoot>'
		};
	m.template = '<div class="datepicker"><div class="datepicker-days"><table class=" table-condensed">' + m.headTemplate + "<tbody></tbody>" + m.footTemplate + "</table>" + "</div>" + '<div class="datepicker-months">' + '<table class="table-condensed">' + m.headTemplate + m.contTemplate + m.footTemplate + "</table>" + "</div>" + '<div class="datepicker-years">' + '<table class="table-condensed">' + m.headTemplate + m.contTemplate + m.footTemplate + "</table>" + "</div>" + "</div>", e.fn.datepicker.DPGlobal = m, e.fn.datepicker.noConflict = function() {
		return e.fn.datepicker = c, this
	}, e.fn.datepicker.version = "1.4.1", e(document).on("focus.datepicker.data-api click.datepicker.data-api", '[data-provide="datepicker"]', function(t) {
		var n = e(this);
		if (n.data("datepicker")) return;
		t.preventDefault(), h.call(n, "show")
	}), e(function() {
		h.call(e('[data-provide="datepicker-inline"]'))
	})
}(window.jQuery), function(e) {
	e.fn.datepicker.dates["zh-CN"] = {
		days: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
		daysShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
		daysMin: ["日", "一", "二", "三", "四", "五", "六"],
		months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
		monthsShort: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
		today: "今日",
		clear: "清除",
		format: "yyyy年mm月dd日",
		titleFormat: "yyyy年mm月",
		weekStart: 1
	}
}(jQuery), function(e) {
	e.fn.datepicker.dates["zh-TW"] = {
		days: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
		daysShort: ["週日", "週一", "週二", "週三", "週四", "週五", "週六"],
		daysMin: ["日", "一", "二", "三", "四", "五", "六"],
		months: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
		monthsShort: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
		today: "今天",
		format: "yyyy年mm月dd日",
		weekStart: 1,
		clear: "清除"
	}
}(jQuery), function(e, t) {
	if (typeof define == "function" && define.amd) define(["exports", "module"], t);
	else if (typeof exports != "undefined" && typeof module != "undefined") t(exports, module);
	else {
		var n = {
			exports: {}
		};
		t(n.exports, n), e.autosize = n.exports
	}
}(this, function(e, t) {
	"use strict";

	function n(e) {
		function a() {
			var t = window.getComputedStyle(e, null);
			t.resize === "vertical" ? e.style.resize = "none" : t.resize === "both" && (e.style.resize = "horizontal"), t.boxSizing === "content-box" ? o = -(parseFloat(t.paddingTop) + parseFloat(t.paddingBottom)) : o = parseFloat(t.borderTopWidth) + parseFloat(t.borderBottomWidth), c()
		}
		function f(t) {
			var n = e.style.width;
			e.style.width = "0px", e.offsetWidth, e.style.width = n, u = t, s && (e.style.overflowY = t), l()
		}
		function l() {
			var t = window.pageYOffset,
				n = document.body.scrollTop,
				r = e.style.height;
			e.style.height = "auto";
			var i = e.scrollHeight + o;
			if (e.scrollHeight === 0) {
				e.style.height = r;
				return
			}
			e.style.height = i + "px", document.documentElement.scrollTop = t, document.body.scrollTop = n
		}
		function c() {
			var t = e.style.height;
			l();
			var n = window.getComputedStyle(e, null);
			n.height !== e.style.height ? u !== "visible" && f("visible") : u !== "hidden" && f("hidden");
			if (t !== e.style.height) {
				var r = document.createEvent("Event");
				r.initEvent("autosize:resized", !0, !1), e.dispatchEvent(r)
			}
		}
		var t = arguments[1] === undefined ? {} : arguments[1],
			n = t.setOverflowX,
			r = n === undefined ? !0 : n,
			i = t.setOverflowY,
			s = i === undefined ? !0 : i;
		if (!e || !e.nodeName || e.nodeName !== "TEXTAREA" || e.hasAttribute("data-autosize-on")) return;
		var o = null,
			u = "hidden",
			h = function(t) {
				window.removeEventListener("resize", c), e.removeEventListener("input", c), e.removeEventListener("keyup", c), e.removeAttribute("data-autosize-on"), e.removeEventListener("autosize:destroy", h), Object.keys(t).forEach(function(n) {
					e.style[n] = t[n]
				})
			}.bind(e, {
				height: e.style.height,
				resize: e.style.resize,
				overflowY: e.style.overflowY,
				overflowX: e.style.overflowX,
				wordWrap: e.style.wordWrap
			});
		e.addEventListener("autosize:destroy", h), "onpropertychange" in e && "oninput" in e && e.addEventListener("keyup", c), window.addEventListener("resize", c), e.addEventListener("input", c), e.addEventListener("autosize:update", c), e.setAttribute("data-autosize-on", !0), s && (e.style.overflowY = "hidden"), r && (e.style.overflowX = "hidden", e.style.wordWrap = "break-word"), a()
	}
	function r(e) {
		if (!e || !e.nodeName || e.nodeName !== "TEXTAREA") return;
		var t = document.createEvent("Event");
		t.initEvent("autosize:destroy", !0, !1), e.dispatchEvent(t)
	}
	function i(e) {
		if (!e || !e.nodeName || e.nodeName !== "TEXTAREA") return;
		var t = document.createEvent("Event");
		t.initEvent("autosize:update", !0, !1), e.dispatchEvent(t)
	}
	var s = null;
	typeof window == "undefined" || typeof window.getComputedStyle != "function" ? (s = function(e) {
		return e
	}, s.destroy = function(e) {
		return e
	}, s.update = function(e) {
		return e
	}) : (s = function(e, t) {
		return e && Array.prototype.forEach.call(e.length ? e : [e], function(e) {
			return n(e, t)
		}), e
	}, s.destroy = function(e) {
		return e && Array.prototype.forEach.call(e.length ? e : [e], r), e
	}, s.update = function(e) {
		return e && Array.prototype.forEach.call(e.length ? e : [e], i), e
	}), t.exports = s
}), function() {
	"use strict";
	var e = "undefined",
		t = "string",
		n = self.navigator,
		r = String,
		i = Object.prototype.hasOwnProperty,
		s = {},
		o = {},
		u = !1,
		a = !0,
		f = /^\s*application\/(?:vnd\.oftn\.|x-)?l10n\+json\s*(?:$|;)/i,
		l, c = "locale",
		h = "defaultLocale",
		p = "toLocaleString",
		d = "toLowerCase",
		v = Array.prototype.indexOf ||
	function(e) {
		var t = this.length,
			n = 0;
		for (; n < t; n++) if (n in this && this[n] === e) return n;
		return -1
	}, m = function(e) {
		var t = new l,
			n = {};
		t.open("GET", e, u), t.send(null);
		try {
			n = JSON.parse(t.responseText)
		} catch (r) {
			setTimeout(function() {
				var t = new Error("Unable to load localization data: " + e);
				throw t.name = "Localization Error", t
			}, 0)
		}
		return n
	}, g = r[p] = function(e) {
		if (arguments.length > 0 && typeof e != "number") if (typeof e === t) g(m(e));
		else if (e === u) o = {};
		else {
			var n, a, f;
			for (n in e) if (i.call(e, n)) {
				a = e[n], n = n[d]();
				if (!(n in o) || a === u) o[n] = {};
				if (a === u) continue;
				if (typeof a === t) {
					if (r[c][d]().indexOf(n) !== 0) {
						n in s || (s[n] = []), s[n].push(a);
						continue
					}
					a = m(a)
				}
				for (f in a) i.call(a, f) && (o[n][f] = a[f])
			}
		}
		return Function.prototype[p].apply(r, arguments)
	}, y = function(e) {
		var t = s[e],
			n = 0,
			r = t.length,
			i;
		for (; n < r; n++) i = {}, i[e] = m(t[n]), g(i);
		delete s[e]
	}, b, w = r.prototype[p] = function() {
		var e = b,
			t = r[e ? h : c],
			n = t[d]().split("-"),
			i = n.length,
			f = this.valueOf(),
			l;
		b = u;
		do {
			l = n.slice(0, i).join("-"), l in s && y(l);
			if (l in o && f in o[l]) return o[l][f]
		} while (i-- > 1);
		return !e && r[h] ? (b = a, w.call(f)) : f
	};
	if (typeof XMLHttpRequest === e && typeof ActiveXObject !== e) {
		var E = ActiveXObject;
		l = function() {
			try {
				return new E("Msxml2.XMLHTTP.6.0")
			} catch (e) {}
			try {
				return new E("Msxml2.XMLHTTP.3.0")
			} catch (t) {}
			try {
				return new E("Msxml2.XMLHTTP")
			} catch (n) {}
			throw new Error("XMLHttpRequest not supported by this browser.")
		}
	} else l = XMLHttpRequest;
	r[h] = r[h] || "", r[c] = n && (n.language || n.userLanguage) || "";
	if (typeof document !== e) {
		var S = document.getElementsByTagName("link"),
			x = S.length,
			T;
		while (x--) {
			var N = S[x],
				C = (N.getAttribute("rel") || "")[d]().split(/\s+/);
			f.test(N.type) && (v.call(C, "localizations") !== -1 ? g(N.getAttribute("href")) : v.call(C, "localization") !== -1 && (T = {}, T[(N.getAttribute("hreflang") || "")[d]()] = N.getAttribute("href"), g(T)))
		}
	}
}(), function(e) {
	var t = document.createElement("div"),
		n = t.getElementsByTagName("i"),
		r = e(document.documentElement);
	t.innerHTML = "<!--[if lte IE 8]><i></i><![endif]-->", n[0] && r.addClass("ie-lte8");
	if (!("querySelector" in document) || window.blackberry && !window.WebKitPoint || window.operamini) return;
	r.addClass("tablesaw-enhanced"), e(function() {
		e(document).trigger("enhance.tablesaw")
	})
}(jQuery), typeof Tablesaw == "undefined" && (Tablesaw = {
	i18n: {
		modes: ["Stack", "Swipe", "Toggle"],
		columns: 'Col<span class="a11y-sm">umn</span>s',
		columnBtnText: "Columns",
		columnsDialogError: "No eligible columns.",
		sort: "Sort"
	}
}), Tablesaw.config || (Tablesaw.config = {}), function(e) {
	var t = "table",
		n = {
			toolbar: "tablesaw-bar"
		},
		r = {
			create: "tablesawcreate",
			destroy: "tablesawdestroy",
			refresh: "tablesawrefresh"
		},
		i = "stack",
		s = "table[data-tablesaw-mode],table[data-tablesaw-sortable]",
		o = function(t) {
			if (!t) throw new Error("Tablesaw requires an element.");
			this.table = t, this.$table = e(t), this.mode = this.$table.attr("data-tablesaw-mode") || i, this.init()
		};
	o.prototype.init = function() {
		this.$table.attr("id") || this.$table.attr("id", t + "-" + Math.round(Math.random() * 1e4)), this.createToolbar();
		var e = this._initCells();
		this.$table.trigger(r.create, [this, e])
	}, o.prototype._initCells = function() {
		var t, n = this.table.querySelectorAll("thead tr"),
			r = this;
		return e(n).each(function() {
			var i = 0;
			e(this).children().each(function() {
				var s = parseInt(this.getAttribute("colspan"), 10),
					o = ":nth-child(" + (i + 1) + ")";
				t = i + 1;
				if (s) for (var u = 0; u < s - 1; u++) i++, o += ", :nth-child(" + (i + 1) + ")";
				this.cells = r.$table.find("tr").not(e(n).eq(0)).not(this).children(o), i++
			})
		}), t
	}, o.prototype.refresh = function() {
		this._initCells(), this.$table.trigger(r.refresh)
	}, o.prototype.createToolbar = function() {
		var t = this.$table.prev("." + n.toolbar);
		t.length || (t = e("<div>").addClass(n.toolbar).insertBefore(this.$table)), this.$toolbar = t, this.mode && this.$toolbar.addClass("mode-" + this.mode)
	}, o.prototype.destroy = function() {
		this.$table.prev("." + n.toolbar).each(function() {
			this.className = this.className.replace(/\bmode\-\w*\b/gi, "")
		});
		var i = this.$table.attr("id");
		e(document).unbind("." + i), e(window).unbind("." + i), this.$table.trigger(r.destroy, [this]), this.$table.removeAttr("data-tablesaw-mode"), this.$table.removeData(t)
	}, e.fn[t] = function() {
		return this.each(function() {
			var n = e(this);
			if (n.data(t)) return;
			var r = new o(this);
			n.data(t, r)
		})
	}, e(document).on("enhance.tablesaw", function(n) {
		e(n.target).find(s)[t]()
	})
}(jQuery), function(e, t, n) {
	var r = {
		stackTable: "tablesaw-stack",
		cellLabels: "tablesaw-cell-label",
		cellContentLabels: "tablesaw-cell-content"
	},
		i = {
			obj: "tablesaw-stack"
		},
		s = {
			labelless: "data-tablesaw-no-labels",
			hideempty: "data-tablesaw-hide-empty"
		},
		o = function(e) {
			this.$table = t(e), this.labelless = this.$table.is("[" + s.labelless + "]"), this.hideempty = this.$table.is("[" + s.hideempty + "]"), this.labelless || (this.allHeaders = this.$table.find("th")), this.$table.data(i.obj, this)
		};
	o.prototype.init = function(e) {
		this.$table.addClass(r.stackTable);
		if (this.labelless) return;
		var n = t(this.allHeaders),
			i = this.hideempty;
		n.each(function() {
			var n = t(this),
				o = t(this.cells).filter(function() {
					return !t(this).parent().is("[" + s.labelless + "]") && (!i || !t(this).is(":empty"))
				}),
				u = o.not(this).filter("thead th").length && " tablesaw-cell-label-top",
				a = n.find(".tablesaw-sortable-btn"),
				f = a.length ? a.html() : n.html();
			if (f !== "") if (u) {
				var l = parseInt(t(this).attr("colspan"), 10),
					c = "";
				l && (c = "td:nth-child(" + l + "n + " + e + ")"), o.filter(c).prepend("<b class='" + r.cellLabels + u + "'>" + f + "</b>")
			} else o.wrapInner("<span class='" + r.cellContentLabels + "'></span>"), o.prepend("<b class='" + r.cellLabels + "'>" + f + "</b>")
		})
	}, o.prototype.destroy = function() {
		this.$table.removeClass(r.stackTable), this.$table.find("." + r.cellLabels).remove(), this.$table.find("." + r.cellContentLabels).each(function() {
			t(this).replaceWith(this.childNodes)
		})
	}, t(document).on("tablesawcreate", function(e, t, n) {
		if (t.mode === "stack") {
			var r = new o(t.table);
			r.init(n)
		}
	}), t(document).on("tablesawdestroy", function(e, n) {
		n.mode === "stack" && t(n.table).data(i.obj).destroy()
	})
}(this, jQuery), function() {
	window.GD || (window.GD = {}), String.prototype.underscore = function() {
		return this.trim().replace(/([a-z\d])([A-Z]+)/g, "$1_$2").replace(/[-\s]+/g, "_").toLowerCase()
	}, String.prototype.dasherize = function() {
		return this.replace(/_/g, "-")
	}, $.fn.removeClassRegExp = function(e) {
		return $(this).removeClass(function(t, n) {
			var r, i, s, o, u;
			i = [], u = n.split(" ");
			for (s = 0, o = u.length; s < o; s++) r = u[s], e.test(r) && i.push(r);
			return i.join(" ")
		}), this
	}, jQuery.extend({
		getQueryParameters: function(e) {
			return e = e || document.location.href, e.indexOf("?") === -1 ? {} : e.split("?")[1].split("&").map(function(e) {
				return e = e.split("="), this[e[0]] = e[1], this
			}.bind({}))[0]
		}
	}), GD.isRetina = function() {
		return window.devicePixelRatio > 1
	}, GD.addParamToUrl = function(e) {
		return function(e, t, n) {
			var r;
			return !t || !n ? e : (r = $.getQueryParameters(e), r[t] = n, e.split("?")[0] + "?" + $.param(r))
		}
	}(this), GD.isIE = function() {
		return Detectizr.browser.name === "ie"
	}, GD.isFirefox = function() {
		return Detectizr.browser.name === "firefox"
	}, GD.isMac = function() {
		return /Mac/i.test(navigator.userAgent)
	}, GD.formatDateTime = function(e) {
		return moment(e, moment.ISO_8601).format("YYYY-MM-DD HH:mm:ss")
	}, GD.delay = function(e, t) {
		return setTimeout(t, e)
	}, GD.recalcFormHeight = function() {
		return $(window).trigger("customLoad")
	}, GD.htmlSafe = function(e) {
		return _.escape(e)
	}, GD.htmlSafeWithLineBreak = function(e) {
		return GD.htmlSafe(e).replace(/\n/g, "<br/>")
	}, GD.removeFileSuffix = function(e) {
		return e.substr(0, e.lastIndexOf("."))
	}, GD.isEmpty = function(e) {
		return e === null || e === ""
	}, GD.isNumber = function(e) {
		return !isNaN(parseFloat(e)) && isFinite(e)
	}, GD.formatDate = function(e) {
		return _.isDate(e) ? moment(e).format("YYYY-MM-DD ") : e
	}, GD.parseDate = function(e) {
		var t, n;
		return t = new Date, e === "" || e === void 0 || e === null ? null : (e === "yesterday" ? e = "before_1" : e === "tomorrow" && (e = "after_1"), e === "today" ? t : e.indexOf("before") !== -1 ? (n = parseInt(e.split("_")[1]), t.setDate(t.getDate() - n), t) : e.indexOf("after") !== -1 ? (n = parseInt(e.split("_")[1]), t.setDate(t.getDate() + n), t) : new Date(e))
	}, GD.extractExistedQueryParams = function() {
		var e;
		return e = {}, !url("?order") || (e.order = url("?order")), !url("?per_page") || (e.per_page = url("?per_page")), !url("?query") || (e.query = decodeURIComponent(url("?query"))), !url("?serial_number") || (e.serial_number = url("?serial_number")), !url("?start") || (e.start = url("?start")), !url("?end") || (e.end = url("?end")), $.isEmptyObject(GD.filterParams.data) || (e = $.extend(e, GD.filterParams.data)), e
	}, GD.isAndroidDevice = function() {
		return navigator.userAgent.indexOf("Android") > 0
	}, GD.isOpenInWeiXin = function() {
		return (navigator.userAgent.indexOf("MicroMessenger") > 0 || navigator.userAgent.indexOf("wechatdevtools") > 0) && navigator.userAgent.indexOf("WindowsWechat") < 0
	}, GD.createKeyboardEvent = function(e) {
		var t;
		return t = $.Event("keydown"), t.keyCode = e, t.customEvent = !0, t
	}, GD.renderQrcode = function(e, t, n) {
		var r, i;
		return Modernizr.canvas ? typeof(r = e.empty()).qrcode == "function" ? r.qrcode({
			size: n,
			text: t
		}) : void 0 : typeof(i = e.empty()).qrcode == "function" ? i.qrcode({
			render: "div",
			size: n,
			text: t
		}) : void 0
	}, GD.validateFile = function(e, t, n) {
		var r;
		return r = [], e.name.substr(0, 1) === "." && r.push(l("%warn_system_file")), e.name.length > 200 && r.push(l("%file_name_too_long")), n && !n.test(e.name) && r.push(l("%warn_invalid_filetype")), /.*\.(exe|bat)$/gi.test(e.name) && r.push(l("%warn_exec_file")), e.size && e.size > t && r.push(l("%warn_oversize", {
			maxSize: GD.numberToHumanSize(t)
		})), r
	}, GD.validateURL = function(e) {
		var t;
		return $.trim(e) ? (t = /^(https:\/\/|http:\/\/)?(((([a-zA-Z]|\d|-|\.|_|~)|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-zA-Z]|\d)|(([a-zA-Z]|\d)([a-zA-Z]|\d|-|\.|_|~)*([a-zA-Z]|\d)))\.)+(([a-zA-Z])|(([a-zA-Z])([a-zA-Z]|\d|-|\.|_|~)*([a-zA-Z])))\.?)(:\d*)?)(\/((([a-zA-Z]|\d|-|\.|_|~)|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-zA-Z]|\d|-|\.|_|~)|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-zA-Z]|\d|-|\.|_|~)|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?(\#((([a-zA-Z]|\d|-|\.|_|~)|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/, t.test($.trim(e))) : !0
	}, GD.isLowVersionIE = function() {
		return Detectizr.browser.name === "ie" && parseInt(Detectizr.browser.version, 10) < 10
	}, GD.countDown = function(e, t, n) {
		var r;
		return n == null && (n = l("%resend")), t > 0 ? (e.addClass("disabled"), r = n + (" (" + t + ")"), e.is("input") ? (e.prop("disabled", !0), e.val(r)) : e.text(r), t--, setTimeout(function() {
			return GD.countDown(e, t, n)
		}, 1e3)) : (e.is("input") ? (e.prop("disabled", !1), e.val(n)) : e.text(n), GD.enableBtn(e))
	}, GD.countDownRedirect = function(e, t, n, r) {
		if (url("path") === t) return;
		return n > 0 ? (e.text(r + (" (" + n + "s)")), n--, setTimeout(function() {
			return GD.countDownRedirect(e, t, n, r)
		}, 1e3)) : Turbolinks.visit(t)
	}, GD.isMobileNumber = function(e) {
		var t;
		return t = /^1\d{10}$/, t.test(e)
	}, GD.getStringLength = function(e) {
		var t;
		return e ? (t = e.match(/[^x00-xff]/ig), e.length + (t ? t.length : 0)) : 0
	}, GD.numberToHumanSize = function(e) {
		var t, n, r;
		return e ? (n = e / 1024, n < 1024 ? n.toFixed(0) + " KB" : (r = n / 1024, r < 1024 ? r.toFixed(1) + " MB" : (t = r / 1024, t.toFixed(1) + " GB"))) : null
	}, GD.numberToCurrencyFloat = function(e) {
		return parseFloat(e).toFixed(2)
	}, GD.PLACEHOLDER_REPLACE_REGEX = /\$\([^\)]+\)/g, GD.pollingDataFromServer = function(e, t, n, r, i) {
		var s, o, u;
		return i == null && (i = null), s = 0, u = null, o = function() {
			return $.getJSON(n, function(e) {
				if (!(s > t)) return s++, r(e, u);
				clearInterval(u);
				if (i !== null) return i("polling finished without successfully")
			}).fail(function() {
				clearInterval(u);
				if (i !== null) return i()
			})
		}, u = setInterval(o, e)
	}, GD.SYSTEM_TAGS = {
		ALL_FORMS_TAG_ID: "ALL_FORMS_TAG_ID",
		ALL_FOLDERS_TAG_ID: "ALL_FOLDERS_TAG_ID",
		UNTAGGED_ID: "UNTAGGED_ID",
		SHARED_FORMS_TAG_ID: "SHARED_FORMS_TAG_ID"
	}, GD.toSentence = function(e) {
		return e.join(l("%common_separator")).replace(/\s/g, "")
	}, $.fn.serializeObject = function() {
		var e, t;
		return t = {}, e = this.serializeArray(), $.each(e, function() {
			return t[this.name] !== void 0 ? (t[this.name].push || (t[this.name] = [t[this.name]]), t[this.name].push(this.value || "")) : t[this.name] = this.value || ""
		}), t
	}, GD.cacheToLocalStorage = function(e, t, n) {
		var r, i;
		try {
			return typeof localStorage != "undefined" && localStorage !== null ? localStorage.setItem(e, t) : void 0
		} catch (i) {
			return r = i, n ? typeof localStorage != "undefined" && localStorage !== null ? localStorage.clear() : void 0 : (typeof localStorage != "undefined" && localStorage !== null && localStorage.removeItem("columbusCachedformCreateModal"), typeof localStorage != "undefined" && localStorage !== null && localStorage.removeItem("cachedformCreateModal"), GD.cacheToLocalStorage(e, t, !0))
		}
	}, GD.isInOrganiztion = function() {
		return typeof GD.currentOrganization == "string"
	}, GD.cookieExpireMinutes = function(e) {
		var t;
		return t = new Date, t.setTime(t.getTime() + e * 60 * 1e3), t
	}
}.call(this), function() {
	var e;
	window.GD || (window.GD = {}), GD.switcheryMainColor = "#44DB5E", GD.adaptDevice = function(e) {
		var t;
		return t = GD.isMobile ? "mlarge" : "xlarge", e ? "" + e + t : t
	}, GD.addRetinaSuffix = function(e, t) {
		var n;
		return n = GD.isMobile || !GD.isRetina() ? "" : "Retina", e + "@" + t + n
	}, GD.adaptRetina = function() {
		var e, t, n;
		return t = $(".need-adapt-retina"), e = GD.adaptDevice(t.data("img-prefix")), n = GD.addRetinaSuffix(t.data("img-url"), e), t.attr("src", n)
	}, GD.adaptVerticalPoistion = function(e) {
		var t;
		return t = ($(window).height() - e.height()) / 2, e.css({
			top: t
		})
	}, GD.showLoading = function(e, t) {
		return e == null && (e = "#entries_grid"), t == null && (t = "entries"), $(e).find(".cover").length === 0 && $(e).append('<div class="cover"></div>'), $(".loading[data-target=" + t + "]").show()
	}, GD.hideLoading = function(e, t) {
		return $(e).find(".cover").remove(), $(".loading[data-target=" + t + "]").hide()
	}, GD.focusToEnd = function(e) {
		var t;
		return t = e.val(), t !== "" && !/\n$/.test(t) && (t += "\n"), e.focus().val("").val(t)
	}, GD.writeContentToIframe = function(e, t) {
		var n;
		return n = $(e)[0].contentWindow.document, n.open(), n.write(t), n.close()
	}, GD.isTextOverflow = function(e) {
		var t;
		return t = e[0], t ? t.scrollWidth > t.offsetWidth : !1
	}, GD.loadWithLoading = function(e, t, n) {
		return GD.showLoading(t, n), t.load(e, function() {
			return GD.hideLoading(t, n)
		})
	}, GD.showTooltipErrorIfHave = function(e, t) {
		if (t) return e.parent().is(".gd-input-prepend, .gd-input-append") ? e.parent().addClass("has-error") : e.wrap("<div class='has-error'>"), e.gdCustomTooltip({
			content: "<div class='error'><i class='gd-icon-times-circle'></i> " + t + "</div>"
		}).tooltipster("show")
	}, GD.clearTooltipError = function(e) {
		e.parent(".gd-input-prepend, .gd-input-append").is(".has-error") ? e.parent().removeClass("has-error") : e.parent().is(".has-error") && e.unwrap();
		if (e.hasClass("tooltipstered")) return e.tooltipster("destroy")
	}, GD.showTooltipFlashMessage = function(e, t, n, r) {
		var i;
		return r == null && (r = "bottom"), i = function() {
			switch (t) {
			case "success":
				return "<div class='success'><i class='gd-icon-check-circle'></i> " + n + "</div>";
			case "error":
				return "<div class='error'><i class='gd-icon-times-circle'></i> " + n + "</div>";
			default:
				return n
			}
		}(), e.hasClass("tooltipstered") && e.tooltipster("destroy"), e.gdCustomTooltip({
			content: i,
			position: r,
			timer: 2e3
		}).tooltipster("show")
	}, GD.showErrorMessageBelow = function(e, t) {
		var n;
		if (e && t) return n = $("<div class='error-message'>" + t + "</div>"), e.parent().is(".gd-input-prepend, .gd-input-append") && (e = e.parent()), e.wrap("<div class='has-error'>"), e.after(n)
	}, GD.clearErrorMessageBelow = function(e) {
		if (!e) return;
		e.parent().is(".gd-input-prepend, .gd-input-append") && (e = e.parent());
		if (e.parent().is(".has-error")) return e.parent().find(".error-message").remove(), e.unwrap()
	}, GD.showErrorMessageBesides = function(e, t, n, r) {
		var i;
		n == null && (n = !1), r == null && (r = !1), i = r ? "" : "<i class='gd-icon-times-circle'></i>", $(e).find(".inline-error-message").length === 0 && $(e).append("<div class='inline-error-message'>" + i + t + "</div>");
		if (!n) return setTimeout(function() {
			return $(e).find(".inline-error-message").fadeOut().remove()
		}, 3e3)
	}, GD.removeErrorMessageBesides = function(e) {
		return $(e).find(".inline-error-message").fadeOut().remove()
	}, GD.initClipboard = function(e) {
		var t;
		return t = function() {
			return GD.showFlashNotification("您的浏览器不支持直接复制，请手动复制！", "danger")
		}, $(e).mouseenter(function() {
			var t;
			if ($(e).data("clipboardInitialied") || $(e).is(".disabled")) return;
			return t = new Clipboard(e), $(e).data("clipboardInitialied", !0), t.on("success", function(e) {
				return GD.showFlashNotification($(e.trigger).data("message") || "复制成功")
			}), t.on("error", function() {
				var t;
				return t = GD.isMac() ? "使用⌘-C复制" : "使用Ctrl-C复制", $(e).hasClass("tooltipstered") ? $(e).tooltipster("content", t) : $(e).gdClickTooltip({
					content: t,
					theme: "gd-tooltip-mini"
				}).tooltipster("show")
			})
		})
	}, GD.disableBtn = function(e) {
		var t;
		t = $(e);
		if (t.length > 0 && !t.hasClass("disabled")) return t.data("disabled-with") && t.data("disable-with", t.data("disabled-with")), GD.disableElement(t)
	}, GD.enableBtn = function(e) {
		var t;
		t = $(e);
		if (t.length > 0 && t.hasClass("disabled")) return t.removeClass("disabled"), $.rails.enableElement(t)
	}, GD.disableElement = function(e) {
		if (e.length) return $.rails.disableElement(e), e.addClass("disabled")
	}, GD.enableElement = function(e) {
		if (e.length) return $.rails.enableElement(e), e.removeClass("disabled disabled-with-cursor-auto")
	}, GD.syncHeight = function(e, t) {
		return t.css({
			height: e.outerHeight()
		})
	}, e = function(e, t) {
		var n;
		return n = {
			showInput: !0,
			showButtons: !1,
			showAlpha: !0,
			containerClassName: "gd-spectrum-container",
			replacerClassName: "gd-spectrum-replacer",
			preferredFormat: "rgb",
			move: function(t) {
				return e.val(t).trigger("change")
			}
		}, e.spectrum($.extend(n, t != null ? t : {}))
	}, GD.initSpectrums = function(t, n) {
		var r, i, s, o, u;
		n == null && (n = null), o = t.find("[data-role=color-picker]"), u = [];
		for (i = 0, s = o.length; i < s; i++) r = o[i], u.push(e($(r), n));
		return u
	}, GD.updateModalHeight = function(e, t, n, r) {
		var i, s, o, u, a;
		t == null && (t = ".modal-body"), n == null && (n = null), r == null && (r = null), i = $(e).find(t);
		if (i.length === 0) return;
		n || (n = $(e).find(".modal-header").outerHeight() + ((a = $(e).find(".modal-footer").outerHeight()) != null ? a : 0)), o = s = $(window).height() * .8 - n, u = $(e).find(".modal-dialog"), i.get(0).scrollHeight > s ? ($(u).css({
			top: "1%"
		}), o = $(window).height() * .9 - n) : $(u).css({
			top: "10%"
		}), i.css({
			"max-height": o + "px"
		});
		if (r) return r(o)
	}, GD.updateSubmitBtn = function() {
		var e, t, n, r, i;
		return r = $(".submit-field input.submit, .entry-show .actions [data-role='submit']"), i = (t = r.data("fileUploading")) != null ? t : 0, i < 0 && r.data("fileUploading", 0), e = (n = i > 0 || r.data("no-goods-selected") || r.data("weixin-openid-fetching")) != null ? n : !1, r.attr("disabled", e)
	}, GD.showConfirmBox = function(e, t, n) {
		var r;
		return r = "<div id='gd_confirm_modal' class='modal light' tabindex='-1' role='dialog' hidden='true'>\n  <div class='modal-dialog modal-sm'>\n    <div class='modal-content'>\n      <div class='modal-header'><h4 class=\"modal-title\">" + e + "</h4></div>\n      <div class='modal-body clearfix'> " + t + " </div>\n      <div class='modal-footer'>\n        <a data-role='ok' class='gd-btn gd-btn-primary' href='javascript:void(0)'>确定</a>\n        <a data-dismiss='modal' class='gd-btn gd-btn-info' href='javascript:void(0)'>取消</a>\n      </div>\n    </div>\n  </div>\n</div>", $("#gd_confirm_modal").remove(), $("body").append(r), $("#gd_confirm_modal").modal("show"), $("#gd_confirm_modal [data-role=ok]").one("click", function() {
			$(this).closest(".modal").modal("hide");
			if (n) return n()
		})
	}, GD.updateSwitchText = function(e) {
		var t;
		if (!$(e).is("input[type=checkbox]")) return;
		t = $(e).is(":checked") ? $(e).data("on-text") : $(e).data("off-text");
		if ( !! t) return $(e).siblings(".switch-text").remove(), $(e).parent().append($("<span class='switch-text'>" + t + "</span>"))
	}, GD.updateRadioBtnGroupStatus = function(e) {
		var t;
		return t = e.is(".gd-btn") ? e : e.closest(".gd-btn"), t.siblings(".gd-btn.selected").removeClass("selected"), t.addClass("selected")
	}, GD.turbolinksReloadWithCurrentPosition = function() {
		return Turbolinks.enableTransitionCache(!0), Turbolinks.visit(window.location.href), Turbolinks.enableTransitionCache(!1)
	}, GD.insertFieldsBaseTooltip = function(e) {
		return $(e).gdClickTooltip({
			contentCloning: !1,
			autoClose: !1,
			theme: "field-placeholder-selector gd-tooltip-menu-over",
			content: $(e).next(".gd-hide").find("[data-react-class='FieldPlaceholderInsertion']"),
			functionReady: function(e, t) {
				return setTimeout(function() {
					return $(document).one("click", "*:not(.tooltipster-content)", function(t) {
						return e.tooltipster("hide")
					})
				}, 100)
			},
			functionAfter: function(e, t) {
				return $(document).off("click", "*:not(.tooltipster-content)")
			}
		})
	}, GD.bindKeyNav = function(e, t) {
		return t == null && (t = {}), $(document).one("page:fetch", function() {
			return $(document).off("keydown.nav")
		}), $(document).on("keydown.nav", function(n) {
			var r, i;
			if (!$("#lightbox").is(":visible") && !$(n.target).is("input:visible:not(:submit), textarea, select")) {
				i = e ? $(e).find(".nav-arrows a") : $(".nav-arrows a");
				if (i.length === 2) {
					r = function() {
						switch (n.keyCode) {
						case 37:
							return i.first();
						case 39:
							return i.last();
						default:
							return null
						}
					}();
					if (r && r.is(":not(.disabled)")) return t.target ? (t.beforeSend && t.beforeSend(), $(t.target).load(r.attr("href"), t.afterSend)) : Turbolinks.visit(r.attr("href"))
				}
			}
		})
	}, _.templateSettings = {
		evaluate: /\[\[(.+?)\]\]/g,
		interpolate: /\{\{(.+?)\}\}/g
	}, $(document).on("page:load ready ajax:complete", function() {
		var e, t, n, r, i;
		e = $("a[data-role=copy]");
		if (e.length !== 0) {
			r = [];
			for (t = 0, n = e.length; t < n; t++) i = e[t], r.push(GD.initClipboard(i));
			return r
		}
	}), $(document).on("page:load ready ajax:complete", function() {
		var e, t, n;
		e = $(".qrcode");
		if (e.length > 0) {
			n = e.data("text");
			if (n) return t = e.data("size") || 175, GD.renderQrcode(e, n, t)
		}
	}), $(document).on("click", "a[data-disabled-with]", function() {
		return GD.disableBtn($(this))
	}), $(document).on("click", ".search-box .search-icon", function() {
		return $(this).siblings("input[type=search]").focus()
	}), $(document).on("change", ".search-box input[type=search]", function() {
		var e;
		return e = !! $.trim($(this).val()), $(this).toggleClass("has-value", e)
	}), $(document).on("page:load ready ajax:complete", function() {
		return $(".need-switch").each(function(e, t) {
			var n;
			$(t).data("checked", $(t).is(":checked")), GD.updateSwitchText($(t));
			if (!$(t).data("switchery")) return n = {
				color: GD.switcheryMainColor
			}, $(t).data("switchery-size") && (n.size = $(t).data("switchery-size")), n.disabled = $(t).data("disabled"), new Switchery(t, n)
		})
	}), $(document).on("click", ".gd-btn-radio-group .gd-btn", function() {
		return GD.updateRadioBtnGroupStatus($(this))
	}), $(document).on("click", "a.disabled, a.prevent-default", function(e) {
		return e.preventDefault()
	}), $(document).on("click", "#switch_to_mobile", function() {
		return $.removeCookie("platform", {
			path: "/"
		})
	}), $(document).on("ajax:complete", "form.need-ajax-flash", function(e, t, n) {
		return n === "success" ? GD.showFlashNotification($(this).data("success-flash") || "设置保存成功") : GD.showFlashNotification($(this).data("error-flash") || "设置保存失败", "danger")
	}), $(".need-select2:visible").livequery(function() {
		return $(this).select2({
			language: String.locale
		})
	}), $(".need-drop-select").livequery(function() {
		return $(this).dropselect({
			clear: !1,
			icons: !1,
			filter: {
				placeholder: "",
				noresult: l("%no_result")
			}
		})
	}), $(".need-count-down").livequery(function() {
		var e, t, n, r;
		return e = $(this), r = e.data("second") || 5, n = e.attr("href"), t = e.data("basetext") || e.text(), GD.countDownRedirect(e, n, r, t)
	}), $(".select2-search__field").livequery(function() {
		return $(this).on("click", function(e) {
			return $(this).focus()
		})
	}), GD.triggerShowModal = function() {
		var e, t;
		t = url("?modal_trigger_id");
		if (!t) return;
		e = $("#" + decodeURIComponent(t));
		if (e.length) return e.trigger("click")
	}, $(document).on("page:load ready", function() {
		return GD.isMobile = $(".mobile-device").length > 0, GD.isPhone = $(".phone-device").length > 0, setTimeout(function() {
			return GD.triggerShowModal()
		}, 150), GD.adaptRetina()
	}), $("[data-feature-name]").livequery(function() {
		var e, t;
		e = $(this).data("feature-name").underscore(), $(this).is(".paid-feature") && $(this).next(".gd-btn-paid-feature").length === 0 && (t = $(this).data("paid-feature-label") || "付费功能", $(this).after(" <a class='gd-btn gd-btn-paid-feature' href='#paid_feature_" + e + "_modal' data-toggle='modal'>" + t + "</a>"));
		if ($(this).data("need-upgrade")) return $(this).attr({
			"data-toggle": "modal",
			"data-target": "#paid_feature_" + e + "_modal"
		})
	})
}.call(this), function() {
	window.GD || (window.GD = {}), $(function() {
		var e, t;
		return t = "gd-tooltip-light", e = function(e, n, r) {
			var i, s, o, u;
			return i = {
				position: "bottom",
				theme: t,
				delay: 0,
				speed: 10,
				contentAsHTML: !0,
				interactive: !0,
				interactiveTolerance: 0
			}, r.theme && (r.theme = t + " " + r.theme), u = function(e, t) {
				var n;
				t.one("click", "[data-dismiss=tooltipster]", function() {
					return e.tooltipster("hide")
				}), t.on("click change", ".with-impact-on-tooltip-size", function() {
					return setTimeout(function() {
						return e.tooltipster("reposition")
					}, r.repositionDelay || 5)
				}), t.find(".tooltipster-content").css({
					maxHeight: $(window).height() * .9
				}), r.backdrop === !0 && (n = $('<div class="gd-tooltip-backdrop gd-hide"></div>'), $(t).after(n), n.fadeIn(300, "linear")), e.addClass("active");
				if (typeof r.functionReady == "function") return r.functionReady(e, t)
			}, o = function(e, t) {
				var n;
				$(".tooltipster-base").length === 0 && (n = $(".gd-tooltip-backdrop"), n.fadeOut(300, "linear", function() {
					return n.remove()
				})), e.data("tooltipster-ns") && e.tooltipster("status") !== "shown" && e.removeClass("active");
				if (typeof r.functionAfter == "function") return r.functionAfter(e, t)
			}, s = $.extend(i, n, r, {
				functionReady: u,
				functionAfter: o
			}), e.each(function() {
				return $(this).tooltipster(s)
			})
		}, $.fn.gdHoverTooltip = function(t) {
			var n;
			return t == null && (t = {}), n = {
				trigger: "hover",
				interactiveTolerance: 500
			}, e(this, n, t)
		}, $.fn.gdClickTooltip = function(t) {
			var n;
			return t == null && (t = {}), n = {
				trigger: "click"
			}, e(this, n, t)
		}, $.fn.gdCustomTooltip = function(t) {
			var n;
			return t == null && (t = {}), n = {
				trigger: "custom"
			}, e(this, n, t)
		}, $(".with-tooltip").livequery(function() {
			return $(this).gdHoverTooltip({
				multiple: !0,
				position: $(this).data("tooltip-position") || "bottom",
				offsetY: $(this).data("tooltip-offset-y") || 0,
				interactiveTolerance: $(this).data("interactive-tolerance") || 0,
				contentAsHTML: !0,
				maxWidth: $(this).data("tooltip-max-width") || null,
				theme: $(this).data("tooltip-theme") || "gd-tooltip-mini",
				content: $(this).attr("title") || $(this).data("title"),
				functionAfter: function(e) {
					return function() {
						if ($(e).data("need-reset-after-hide")) return $(e).tooltipster("update", $(e).attr("title") || $(e).data("title"))
					}
				}(this)
			})
		}), $(".image-tooltip").livequery(function() {
			var e, t;
			return e = $(this).data("image-path"), t = $(this).data("additional-img-class") || "", $(this).gdHoverTooltip({
				content: $("#nav_qrcode_container"),
				functionReady: function(e, t) {
					return e.tooltipster("reposition")
				}
			})
		}), $("[data-toggle=tooltipster]").livequery(function() {
			return $(this).click(function() {
				return $(".tooltipstered").not(this).tooltipster("hide")
			})
		}), GD.tooltipInitialized = function(e) {
			return e.hasClass("tooltipstered")
		}
	})
}.call(this), function() {
	var e;
	GD.enhanceDateField = function(e) {
		var t, n, r;
		if (!$("body").is(".mobile-device")) return t = e.find("input[type=date]").addClass("transformed-date-input"), t.each(function() {
			var e;
			return e = $(this).val(), $(this).attr({
				type: "text"
			}).val(e)
		}), n = t.css("display"), r = {}, n && (r.display = n), t.closest(".gd-input-container").addClass("gd-input-date").css(r)
	}, e = function() {
		function e(e, t) {
			var n;
			n = {
				language: String.locale || "zh-CN",
				format: "yyyy-mm-dd",
				autoclose: !0,
				orientation: "auto left",
				todayHighlight: !0
			}, t = $.extend(n, t || {}), $(e).datepicker(t).on("show hide", function(e) {
				return e.stopPropagation()
			}), $(e).siblings(".gd-input-icon").click(function() {
				if (!$(e).prop("readonly")) return $(e).focus()
			})
		}
		return e
	}(), GD.initDatePicker = function(t, n) {
		var r, i, s, o, u;
		n == null && (n = null), GD.enhanceDateField(t), o = t.find(".transformed-date-input"), u = [];
		for (i = 0, s = o.length; i < s; i++) r = o[i], u.push(new e(r, n));
		return u
	}, $(function() {
		return $(document).on("ready page:load ajax:complete", function() {
			if (!($(".dashboard").length > 0)) return GD.initDatePicker($(document))
		})
	})
}.call(this), function(e, t, n, r) {
	"use strict";

	function l(e, t, n) {
		return setTimeout(m(e, n), t)
	}
	function c(e, t, n) {
		return Array.isArray(e) ? (h(e, n[t], n), !0) : !1
	}
	function h(e, t, n) {
		var i;
		if (!e) return;
		if (e.forEach) e.forEach(t, n);
		else if (e.length !== r) {
			i = 0;
			while (i < e.length) t.call(n, e[i], i, e), i++
		} else for (i in e) e.hasOwnProperty(i) && t.call(n, e[i], i, e)
	}
	function p(e, t, n) {
		var i = Object.keys(t),
			s = 0;
		while (s < i.length) {
			if (!n || n && e[i[s]] === r) e[i[s]] = t[i[s]];
			s++
		}
		return e
	}
	function d(e, t) {
		return p(e, t, !0)
	}
	function v(e, t, n) {
		var r = t.prototype,
			i;
		i = e.prototype = Object.create(r), i.constructor = e, i._super = r, n && p(i, n)
	}
	function m(e, t) {
		return function() {
			return e.apply(t, arguments)
		}
	}
	function g(e, t) {
		return typeof e == o ? e.apply(t ? t[0] || r : r, t) : e
	}
	function y(e, t) {
		return e === r ? t : e
	}
	function b(e, t, n) {
		h(x(t), function(t) {
			e.addEventListener(t, n, !1)
		})
	}
	function w(e, t, n) {
		h(x(t), function(t) {
			e.removeEventListener(t, n, !1)
		})
	}
	function E(e, t) {
		while (e) {
			if (e == t) return !0;
			e = e.parentNode
		}
		return !1
	}
	function S(e, t) {
		return e.indexOf(t) > -1
	}
	function x(e) {
		return e.trim().split(/\s+/g)
	}
	function T(e, t, n) {
		if (e.indexOf && !n) return e.indexOf(t);
		var r = 0;
		while (r < e.length) {
			if (n && e[r][n] == t || !n && e[r] === t) return r;
			r++
		}
		return -1
	}
	function N(e) {
		return Array.prototype.slice.call(e, 0)
	}
	function C(e, t, n) {
		var r = [],
			i = [],
			s = 0;
		while (s < e.length) {
			var o = t ? e[s][t] : e[s];
			T(i, o) < 0 && r.push(e[s]), i[s] = o, s++
		}
		return n && (t ? r = r.sort(function(n, r) {
			return n[t] > r[t]
		}) : r = r.sort()), r
	}
	function k(e, t) {
		var n, s, o = t[0].toUpperCase() + t.slice(1),
			u = 0;
		while (u < i.length) {
			n = i[u], s = n ? n + o : t;
			if (s in e) return s;
			u++
		}
		return r
	}
	function A() {
		return L++
	}
	function O(e) {
		var t = e.ownerDocument;
		return t.defaultView || t.parentWindow
	}
	function et(e, t) {
		var n = this;
		this.manager = e, this.callback = t, this.element = e.element, this.target = e.options.inputTarget, this.domHandler = function(t) {
			g(e.options.enable, [e]) && n.handler(t)
		}, this.init()
	}
	function tt(e) {
		var t, n = e.options.inputClass;
		return n ? t = n : D ? t = St : P ? t = Ot : _ ? t = _t : t = gt, new t(e, nt)
	}
	function nt(e, t, n) {
		var r = n.pointers.length,
			i = n.changedPointers.length,
			s = t & q && r - i === 0,
			o = t & (U | z) && r - i === 0;
		n.isFirst = !! s, n.isFinal = !! o, s && (e.session = {}), n.eventType = t, rt(e, n), e.emit("hammer.input", n), e.recognize(n), e.session.prevInput = n
	}
	function rt(e, t) {
		var n = e.session,
			r = t.pointers,
			i = r.length;
		n.firstInput || (n.firstInput = ot(t)), i > 1 && !n.firstMultiple ? n.firstMultiple = ot(t) : i === 1 && (n.firstMultiple = !1);
		var s = n.firstInput,
			o = n.firstMultiple,
			u = o ? o.center : s.center,
			a = t.center = ut(r);
		t.timeStamp = f(), t.deltaTime = t.timeStamp - s.timeStamp, t.angle = ct(u, a), t.distance = lt(u, a), it(n, t), t.offsetDirection = ft(t.deltaX, t.deltaY), t.scale = o ? pt(o.pointers, r) : 1, t.rotation = o ? ht(o.pointers, r) : 0, st(n, t);
		var l = e.element;
		E(t.srcEvent.target, l) && (l = t.srcEvent.target), t.target = l
	}
	function it(e, t) {
		var n = t.center,
			r = e.offsetDelta || {},
			i = e.prevDelta || {},
			s = e.prevInput || {};
		if (t.eventType === q || s.eventType === U) i = e.prevDelta = {
			x: s.deltaX || 0,
			y: s.deltaY || 0
		}, r = e.offsetDelta = {
			x: n.x,
			y: n.y
		};
		t.deltaX = i.x + (n.x - r.x), t.deltaY = i.y + (n.y - r.y)
	}
	function st(e, t) {
		var n = e.lastInterval || t,
			i = t.timeStamp - n.timeStamp,
			s, o, u, f;
		if (t.eventType != z && (i > I || n.velocity === r)) {
			var l = n.deltaX - t.deltaX,
				c = n.deltaY - t.deltaY,
				h = at(i, l, c);
			o = h.x, u = h.y, s = a(h.x) > a(h.y) ? h.x : h.y, f = ft(l, c), e.lastInterval = t
		} else s = n.velocity, o = n.velocityX, u = n.velocityY, f = n.direction;
		t.velocity = s, t.velocityX = o, t.velocityY = u, t.direction = f
	}
	function ot(e) {
		var t = [],
			n = 0;
		while (n < e.pointers.length) t[n] = {
			clientX: u(e.pointers[n].clientX),
			clientY: u(e.pointers[n].clientY)
		}, n++;
		return {
			timeStamp: f(),
			pointers: t,
			center: ut(t),
			deltaX: e.deltaX,
			deltaY: e.deltaY
		}
	}
	function ut(e) {
		var t = e.length;
		if (t === 1) return {
			x: u(e[0].clientX),
			y: u(e[0].clientY)
		};
		var n = 0,
			r = 0,
			i = 0;
		while (i < t) n += e[i].clientX, r += e[i].clientY, i++;
		return {
			x: u(n / t),
			y: u(r / t)
		}
	}
	function at(e, t, n) {
		return {
			x: t / e || 0,
			y: n / e || 0
		}
	}
	function ft(e, t) {
		return e === t ? W : a(e) >= a(t) ? e > 0 ? X : V : t > 0 ? $ : J
	}
	function lt(e, t, n) {
		n || (n = Y);
		var r = t[n[0]] - e[n[0]],
			i = t[n[1]] - e[n[1]];
		return Math.sqrt(r * r + i * i)
	}
	function ct(e, t, n) {
		n || (n = Y);
		var r = t[n[0]] - e[n[0]],
			i = t[n[1]] - e[n[1]];
		return Math.atan2(i, r) * 180 / Math.PI
	}
	function ht(e, t) {
		return ct(t[1], t[0], Z) - ct(e[1], e[0], Z)
	}
	function pt(e, t) {
		return lt(t[0], t[1], Z) / lt(e[0], e[1], Z)
	}
	function gt() {
		this.evEl = vt, this.evWin = mt, this.allow = !0, this.pressed = !1, et.apply(this, arguments)
	}
	function St() {
		this.evEl = wt, this.evWin = Et, et.apply(this, arguments), this.store = this.manager.session.pointerEvents = []
	}
	function Ct() {
		this.evTarget = Tt, this.evWin = Nt, this.started = !1, et.apply(this, arguments)
	}
	function kt(e, t) {
		var n = N(e.touches),
			r = N(e.changedTouches);
		return t & (U | z) && (n = C(n.concat(r), "identifier", !0)), [n, r]
	}
	function Ot() {
		this.evTarget = At, this.targetIds = {}, et.apply(this, arguments)
	}
	function Mt(e, t) {
		var n = N(e.touches),
			r = this.targetIds;
		if (t & (q | R) && n.length === 1) return r[n[0].identifier] = !0, [n, n];
		var i, s, o = N(e.changedTouches),
			u = [],
			a = this.target;
		s = n.filter(function(e) {
			return E(e.target, a)
		});
		if (t === q) {
			i = 0;
			while (i < s.length) r[s[i].identifier] = !0, i++
		}
		i = 0;
		while (i < o.length) r[o[i].identifier] && u.push(o[i]), t & (U | z) && delete r[o[i].identifier], i++;
		if (!u.length) return;
		return [C(s.concat(u), "identifier", !0), u]
	}
	function _t() {
		et.apply(this, arguments);
		var e = m(this.handler, this);
		this.touch = new Ot(this.manager, e), this.mouse = new gt(this.manager, e)
	}
	function Rt(e, t) {
		this.manager = e, this.set(t)
	}
	function Ut(e) {
		if (S(e, Ft)) return Ft;
		var t = S(e, It),
			n = S(e, qt);
		return t && n ? It + " " + qt : t || n ? t ? It : qt : S(e, jt) ? jt : Bt
	}
	function Qt(e) {
		this.id = A(), this.manager = null, this.options = d(e || {}, this.defaults), this.options.enable = y(this.options.enable, !0), this.state = zt, this.simultaneous = {}, this.requireFail = []
	}
	function Gt(e) {
		return e & Jt ? "cancel" : e & Vt ? "end" : e & Xt ? "move" : e & Wt ? "start" : ""
	}
	function Yt(e) {
		return e == J ? "down" : e == $ ? "up" : e == X ? "left" : e == V ? "right" : ""
	}
	function Zt(e, t) {
		var n = t.manager;
		return n ? n.get(e) : e
	}
	function en() {
		Qt.apply(this, arguments)
	}
	function tn() {
		en.apply(this, arguments), this.pX = null, this.pY = null
	}
	function nn() {
		en.apply(this, arguments)
	}
	function rn() {
		Qt.apply(this, arguments), this._timer = null, this._input = null
	}
	function sn() {
		en.apply(this, arguments)
	}
	function on() {
		en.apply(this, arguments)
	}
	function un() {
		Qt.apply(this, arguments), this.pTime = !1, this.pCenter = !1, this._timer = null, this._input = null, this.count = 0
	}
	function an(e, t) {
		return t = t || {}, t.recognizers = y(t.recognizers, an.defaults.preset), new cn(e, t)
	}
	function cn(e, t) {
		t = t || {}, this.options = d(t, an.defaults), this.options.inputTarget = this.options.inputTarget || e, this.handlers = {}, this.session = {}, this.recognizers = [], this.element = e, this.input = tt(this), this.touchAction = new Rt(this, this.options.touchAction), hn(this, !0), h(t.recognizers, function(e) {
			var t = this.add(new e[0](e[1]));
			e[2] && t.recognizeWith(e[2]), e[3] && t.requireFailure(e[3])
		}, this)
	}
	function hn(e, t) {
		var n = e.element;
		h(e.options.cssProps, function(e, r) {
			n.style[k(n.style, r)] = t ? e : ""
		})
	}
	function pn(e, n) {
		var r = t.createEvent("Event");
		r.initEvent(e, !0, !0), r.gesture = n, n.target.dispatchEvent(r)
	}
	var i = ["", "webkit", "moz", "MS", "ms", "o"],
		s = t.createElement("div"),
		o = "function",
		u = Math.round,
		a = Math.abs,
		f = Date.now,
		L = 1,
		M = /mobile|tablet|ip(ad|hone|od)|android/i,
		_ = "ontouchstart" in e,
		D = k(e, "PointerEvent") !== r,
		P = _ && M.test(navigator.userAgent),
		H = "touch",
		B = "pen",
		j = "mouse",
		F = "kinect",
		I = 25,
		q = 1,
		R = 2,
		U = 4,
		z = 8,
		W = 1,
		X = 2,
		V = 4,
		$ = 8,
		J = 16,
		K = X | V,
		Q = $ | J,
		G = K | Q,
		Y = ["x", "y"],
		Z = ["clientX", "clientY"];
	et.prototype = {
		handler: function() {},
		init: function() {
			this.evEl && b(this.element, this.evEl, this.domHandler), this.evTarget && b(this.target, this.evTarget, this.domHandler), this.evWin && b(O(this.element), this.evWin, this.domHandler)
		},
		destroy: function() {
			this.evEl && w(this.element, this.evEl, this.domHandler), this.evTarget && w(this.target, this.evTarget, this.domHandler), this.evWin && w(O(this.element), this.evWin, this.domHandler)
		}
	};
	var dt = {
		mousedown: q,
		mousemove: R,
		mouseup: U
	},
		vt = "mousedown",
		mt = "mousemove mouseup";
	v(gt, et, {
		handler: function(t) {
			var n = dt[t.type];
			n & q && t.button === 0 && (this.pressed = !0), n & R && t.which !== 1 && (n = U);
			if (!this.pressed || !this.allow) return;
			n & U && (this.pressed = !1), this.callback(this.manager, n, {
				pointers: [t],
				changedPointers: [t],
				pointerType: j,
				srcEvent: t
			})
		}
	});
	var yt = {
		pointerdown: q,
		pointermove: R,
		pointerup: U,
		pointercancel: z,
		pointerout: z
	},
		bt = {
			2: H,
			3: B,
			4: j,
			5: F
		},
		wt = "pointerdown",
		Et = "pointermove pointerup pointercancel";
	e.MSPointerEvent && (wt = "MSPointerDown", Et = "MSPointerMove MSPointerUp MSPointerCancel"), v(St, et, {
		handler: function(t) {
			var n = this.store,
				r = !1,
				i = t.type.toLowerCase().replace("ms", ""),
				s = yt[i],
				o = bt[t.pointerType] || t.pointerType,
				u = o == H,
				a = T(n, t.pointerId, "pointerId");
			s & q && (t.button === 0 || u) ? a < 0 && (n.push(t), a = n.length - 1) : s & (U | z) && (r = !0);
			if (a < 0) return;
			n[a] = t, this.callback(this.manager, s, {
				pointers: n,
				changedPointers: [t],
				pointerType: o,
				srcEvent: t
			}), r && n.splice(a, 1)
		}
	});
	var xt = {
		touchstart: q,
		touchmove: R,
		touchend: U,
		touchcancel: z
	},
		Tt = "touchstart",
		Nt = "touchstart touchmove touchend touchcancel";
	v(Ct, et, {
		handler: function(t) {
			var n = xt[t.type];
			n === q && (this.started = !0);
			if (!this.started) return;
			var r = kt.call(this, t, n);
			n & (U | z) && r[0].length - r[1].length === 0 && (this.started = !1), this.callback(this.manager, n, {
				pointers: r[0],
				changedPointers: r[1],
				pointerType: H,
				srcEvent: t
			})
		}
	});
	var Lt = {
		touchstart: q,
		touchmove: R,
		touchend: U,
		touchcancel: z
	},
		At = "touchstart touchmove touchend touchcancel";
	v(Ot, et, {
		handler: function(t) {
			var n = Lt[t.type],
				r = Mt.call(this, t, n);
			if (!r) return;
			this.callback(this.manager, n, {
				pointers: r[0],
				changedPointers: r[1],
				pointerType: H,
				srcEvent: t
			})
		}
	}), v(_t, et, {
		handler: function(t, n, r) {
			var i = r.pointerType == H,
				s = r.pointerType == j;
			if (i) this.mouse.allow = !1;
			else if (s && !this.mouse.allow) return;
			n & (U | z) && (this.mouse.allow = !0), this.callback(t, n, r)
		},
		destroy: function() {
			this.touch.destroy(), this.mouse.destroy()
		}
	});
	var Dt = k(s.style, "touchAction"),
		Pt = Dt !== r,
		Ht = "compute",
		Bt = "auto",
		jt = "manipulation",
		Ft = "none",
		It = "pan-x",
		qt = "pan-y";
	Rt.prototype = {
		set: function(e) {
			e == Ht && (e = this.compute()), Pt && (this.manager.element.style[Dt] = e), this.actions = e.toLowerCase().trim()
		},
		update: function() {
			this.set(this.manager.options.touchAction)
		},
		compute: function() {
			var e = [];
			return h(this.manager.recognizers, function(t) {
				g(t.options.enable, [t]) && (e = e.concat(t.getTouchAction()))
			}), Ut(e.join(" "))
		},
		preventDefaults: function(e) {
			if (Pt) return;
			var t = e.srcEvent,
				n = e.offsetDirection;
			if (this.manager.session.prevented) {
				t.preventDefault();
				return
			}
			var r = this.actions,
				i = S(r, Ft),
				s = S(r, qt),
				o = S(r, It);
			if (i || s && n & K || o && n & Q) return this.preventSrc(t)
		},
		preventSrc: function(e) {
			this.manager.session.prevented = !0, e.preventDefault()
		}
	};
	var zt = 1,
		Wt = 2,
		Xt = 4,
		Vt = 8,
		$t = Vt,
		Jt = 16,
		Kt = 32;
	Qt.prototype = {
		defaults: {},
		set: function(e) {
			return p(this.options, e), this.manager && this.manager.touchAction.update(), this
		},
		recognizeWith: function(e) {
			if (c(e, "recognizeWith", this)) return this;
			var t = this.simultaneous;
			return e = Zt(e, this), t[e.id] || (t[e.id] = e, e.recognizeWith(this)), this
		},
		dropRecognizeWith: function(e) {
			return c(e, "dropRecognizeWith", this) ? this : (e = Zt(e, this), delete this.simultaneous[e.id], this)
		},
		requireFailure: function(e) {
			if (c(e, "requireFailure", this)) return this;
			var t = this.requireFail;
			return e = Zt(e, this), T(t, e) === -1 && (t.push(e), e.requireFailure(this)), this
		},
		dropRequireFailure: function(e) {
			if (c(e, "dropRequireFailure", this)) return this;
			e = Zt(e, this);
			var t = T(this.requireFail, e);
			return t > -1 && this.requireFail.splice(t, 1), this
		},
		hasRequireFailures: function() {
			return this.requireFail.length > 0
		},
		canRecognizeWith: function(e) {
			return !!this.simultaneous[e.id]
		},
		emit: function(e) {
			function r(r) {
				t.manager.emit(t.options.event + (r ? Gt(n) : ""), e)
			}
			var t = this,
				n = this.state;
			n < Vt && r(!0), r(), n >= Vt && r(!0)
		},
		tryEmit: function(e) {
			if (this.canEmit()) return this.emit(e);
			this.state = Kt
		},
		canEmit: function() {
			var e = 0;
			while (e < this.requireFail.length) {
				if (!(this.requireFail[e].state & (Kt | zt))) return !1;
				e++
			}
			return !0
		},
		recognize: function(e) {
			var t = p({}, e);
			if (!g(this.options.enable, [this, t])) {
				this.reset(), this.state = Kt;
				return
			}
			this.state & ($t | Jt | Kt) && (this.state = zt), this.state = this.process(t), this.state & (Wt | Xt | Vt | Jt) && this.tryEmit(t)
		},
		process: function(e) {},
		getTouchAction: function() {},
		reset: function() {}
	}, v(en, Qt, {
		defaults: {
			pointers: 1
		},
		attrTest: function(e) {
			var t = this.options.pointers;
			return t === 0 || e.pointers.length === t
		},
		process: function(e) {
			var t = this.state,
				n = e.eventType,
				r = t & (Wt | Xt),
				i = this.attrTest(e);
			if (r && (n & z || !i)) return t | Jt;
			if (r || i) return n & U ? t | Vt : t & Wt ? t | Xt : Wt;
			return Kt
		}
	}), v(tn, en, {
		defaults: {
			event: "pan",
			threshold: 10,
			pointers: 1,
			direction: G
		},
		getTouchAction: function() {
			var e = this.options.direction,
				t = [];
			return e & K && t.push(qt), e & Q && t.push(It), t
		},
		directionTest: function(e) {
			var t = this.options,
				n = !0,
				r = e.distance,
				i = e.direction,
				s = e.deltaX,
				o = e.deltaY;
			return i & t.direction || (t.direction & K ? (i = s === 0 ? W : s < 0 ? X : V, n = s != this.pX, r = Math.abs(e.deltaX)) : (i = o === 0 ? W : o < 0 ? $ : J, n = o != this.pY, r = Math.abs(e.deltaY))), e.direction = i, n && r > t.threshold && i & t.direction
		},
		attrTest: function(e) {
			return en.prototype.attrTest.call(this, e) && (this.state & Wt || !(this.state & Wt) && this.directionTest(e))
		},
		emit: function(e) {
			this.pX = e.deltaX, this.pY = e.deltaY;
			var t = Yt(e.direction);
			t && this.manager.emit(this.options.event + t, e), this._super.emit.call(this, e)
		}
	}), v(nn, en, {
		defaults: {
			event: "pinch",
			threshold: 0,
			pointers: 2
		},
		getTouchAction: function() {
			return [Ft]
		},
		attrTest: function(e) {
			return this._super.attrTest.call(this, e) && (Math.abs(e.scale - 1) > this.options.threshold || this.state & Wt)
		},
		emit: function(e) {
			this._super.emit.call(this, e);
			if (e.scale !== 1) {
				var t = e.scale < 1 ? "in" : "out";
				this.manager.emit(this.options.event + t, e)
			}
		}
	}), v(rn, Qt, {
		defaults: {
			event: "press",
			pointers: 1,
			time: 500,
			threshold: 5
		},
		getTouchAction: function() {
			return [Bt]
		},
		process: function(e) {
			var t = this.options,
				n = e.pointers.length === t.pointers,
				r = e.distance < t.threshold,
				i = e.deltaTime > t.time;
			this._input = e;
			if (!r || !n || e.eventType & (U | z) && !i) this.reset();
			else if (e.eventType & q) this.reset(), this._timer = l(function() {
				this.state = $t, this.tryEmit()
			}, t.time, this);
			else if (e.eventType & U) return $t;
			return Kt
		},
		reset: function() {
			clearTimeout(this._timer)
		},
		emit: function(e) {
			if (this.state !== $t) return;
			e && e.eventType & U ? this.manager.emit(this.options.event + "up", e) : (this._input.timeStamp = f(), this.manager.emit(this.options.event, this._input))
		}
	}), v(sn, en, {
		defaults: {
			event: "rotate",
			threshold: 0,
			pointers: 2
		},
		getTouchAction: function() {
			return [Ft]
		},
		attrTest: function(e) {
			return this._super.attrTest.call(this, e) && (Math.abs(e.rotation) > this.options.threshold || this.state & Wt)
		}
	}), v(on, en, {
		defaults: {
			event: "swipe",
			threshold: 10,
			velocity: .65,
			direction: K | Q,
			pointers: 1
		},
		getTouchAction: function() {
			return tn.prototype.getTouchAction.call(this)
		},
		attrTest: function(e) {
			var t = this.options.direction,
				n;
			return t & (K | Q) ? n = e.velocity : t & K ? n = e.velocityX : t & Q && (n = e.velocityY), this._super.attrTest.call(this, e) && t & e.direction && e.distance > this.options.threshold && a(n) > this.options.velocity && e.eventType & U
		},
		emit: function(e) {
			var t = Yt(e.direction);
			t && this.manager.emit(this.options.event + t, e), this.manager.emit(this.options.event, e)
		}
	}), v(un, Qt, {
		defaults: {
			event: "tap",
			pointers: 1,
			taps: 1,
			interval: 300,
			time: 250,
			threshold: 2,
			posThreshold: 10
		},
		getTouchAction: function() {
			return [jt]
		},
		process: function(e) {
			var t = this.options,
				n = e.pointers.length === t.pointers,
				r = e.distance < t.threshold,
				i = e.deltaTime < t.time;
			this.reset();
			if (e.eventType & q && this.count === 0) return this.failTimeout();
			if (r && i && n) {
				if (e.eventType != U) return this.failTimeout();
				var s = this.pTime ? e.timeStamp - this.pTime < t.interval : !0,
					o = !this.pCenter || lt(this.pCenter, e.center) < t.posThreshold;
				this.pTime = e.timeStamp, this.pCenter = e.center, !o || !s ? this.count = 1 : this.count += 1, this._input = e;
				var u = this.count % t.taps;
				if (u === 0) return this.hasRequireFailures() ? (this._timer = l(function() {
					this.state = $t, this.tryEmit()
				}, t.interval, this), Wt) : $t
			}
			return Kt
		},
		failTimeout: function() {
			return this._timer = l(function() {
				this.state = Kt
			}, this.options.interval, this), Kt
		},
		reset: function() {
			clearTimeout(this._timer)
		},
		emit: function() {
			this.state == $t && (this._input.tapCount = this.count, this.manager.emit(this.options.event, this._input))
		}
	}), an.VERSION = "2.0.4", an.defaults = {
		domEvents: !1,
		touchAction: Ht,
		enable: !0,
		inputTarget: null,
		inputClass: null,
		preset: [
			[sn,
			{
				enable: !1
			}],
			[nn,
			{
				enable: !1
			}, ["rotate"]],
			[on,
			{
				direction: K
			}],
			[tn,
			{
				direction: K
			}, ["swipe"]],
			[un],
			[un,
			{
				event: "doubletap",
				taps: 2
			}, ["tap"]],
			[rn]
		],
		cssProps: {
			userSelect: "none",
			touchSelect: "none",
			touchCallout: "none",
			contentZooming: "none",
			userDrag: "none",
			tapHighlightColor: "rgba(0,0,0,0)"
		}
	};
	var fn = 1,
		ln = 2;
	cn.prototype = {
		set: function(e) {
			return p(this.options, e), e.touchAction && this.touchAction.update(), e.inputTarget && (this.input.destroy(), this.input.target = e.inputTarget, this.input.init()), this
		},
		stop: function(e) {
			this.session.stopped = e ? ln : fn
		},
		recognize: function(e) {
			var t = this.session;
			if (t.stopped) return;
			this.touchAction.preventDefaults(e);
			var n, r = this.recognizers,
				i = t.curRecognizer;
			if (!i || i && i.state & $t) i = t.curRecognizer = null;
			var s = 0;
			while (s < r.length) n = r[s], t.stopped !== ln && (!i || n == i || n.canRecognizeWith(i)) ? n.recognize(e) : n.reset(), !i && n.state & (Wt | Xt | Vt) && (i = t.curRecognizer = n), s++
		},
		get: function(e) {
			if (e instanceof Qt) return e;
			var t = this.recognizers;
			for (var n = 0; n < t.length; n++) if (t[n].options.event == e) return t[n];
			return null
		},
		add: function(e) {
			if (c(e, "add", this)) return this;
			var t = this.get(e.options.event);
			return t && this.remove(t), this.recognizers.push(e), e.manager = this, this.touchAction.update(), e
		},
		remove: function(e) {
			if (c(e, "remove", this)) return this;
			var t = this.recognizers;
			return e = this.get(e), t.splice(T(t, e), 1), this.touchAction.update(), this
		},
		on: function(e, t) {
			var n = this.handlers;
			return h(x(e), function(e) {
				n[e] = n[e] || [], n[e].push(t)
			}), this
		},
		off: function(e, t) {
			var n = this.handlers;
			return h(x(e), function(e) {
				t ? n[e].splice(T(n[e], t), 1) : delete n[e]
			}), this
		},
		emit: function(e, t) {
			this.options.domEvents && pn(e, t);
			var n = this.handlers[e] && this.handlers[e].slice();
			if (!n || !n.length) return;
			t.type = e, t.preventDefault = function() {
				t.srcEvent.preventDefault()
			};
			var r = 0;
			while (r < n.length) n[r](t), r++
		},
		destroy: function() {
			this.element && hn(this, !1), this.handlers = {}, this.session = {}, this.input.destroy(), this.element = null
		}
	}, p(an, {
		INPUT_START: q,
		INPUT_MOVE: R,
		INPUT_END: U,
		INPUT_CANCEL: z,
		STATE_POSSIBLE: zt,
		STATE_BEGAN: Wt,
		STATE_CHANGED: Xt,
		STATE_ENDED: Vt,
		STATE_RECOGNIZED: $t,
		STATE_CANCELLED: Jt,
		STATE_FAILED: Kt,
		DIRECTION_NONE: W,
		DIRECTION_LEFT: X,
		DIRECTION_RIGHT: V,
		DIRECTION_UP: $,
		DIRECTION_DOWN: J,
		DIRECTION_HORIZONTAL: K,
		DIRECTION_VERTICAL: Q,
		DIRECTION_ALL: G,
		Manager: cn,
		Input: et,
		TouchAction: Rt,
		TouchInput: Ot,
		MouseInput: gt,
		PointerEventInput: St,
		TouchMouseInput: _t,
		SingleTouchInput: Ct,
		Recognizer: Qt,
		AttrRecognizer: en,
		Tap: un,
		Pan: tn,
		Swipe: on,
		Pinch: nn,
		Rotate: sn,
		Press: rn,
		on: b,
		off: w,
		each: h,
		merge: d,
		extend: p,
		inherit: v,
		bindFn: m,
		prefixed: k
	}), typeof define == o && define.amd ? define(function() {
		return an
	}) : typeof module != "undefined" && module.exports ? module.exports = an : e[n] = an
}(window, document, "Hammer"), function(e, t) {
	typeof define == "function" && define.amd ? define(["jquery"], t) : typeof exports == "object" ? module.exports = t(require("jquery")) : e.GD.lightbox = t(e.jQuery)
}(this, function(e) {
	function t(t) {
		this.album = [], this.currentImageIndex = void 0, this.init(), this.options = e.extend({}, this.constructor.defaults), this.option(t)
	}
	return t.defaults = {
		albumLabel: "Image %1 of %2",
		alwaysShowNavOnTouchDevices: !1,
		fadeDuration: 500,
		fitImagesInViewport: !0,
		positionFromTop: 50,
		resizeDuration: 700,
		showImageNumberLabel: !0,
		wrapAround: !1
	}, t.prototype.option = function(t) {
		e.extend(this.options, t)
	}, t.prototype.imageCountLabel = function(e, t) {
		return this.options.albumLabel.replace(/%1/g, e).replace(/%2/g, t)
	}, t.prototype.init = function() {
		this.enable(), this.build()
	}, t.prototype.enable = function() {
		var t = this;
		e("body").on("click", "a[rel^=lightbox], area[rel^=lightbox], a[data-lightbox], area[data-lightbox]", function(n) {
			return t.start(e(n.currentTarget)), !1
		})
	}, t.prototype.build = function() {
		var t = this;
		e('<div id="lightboxOverlay" class="lightboxOverlay"></div><div id="lightbox" class="lightbox"><div class="lb-outerContainer"><div class="lb-container"><img class="lb-image" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" /><div class="lb-nav"><a class="lb-prev" href="" ></a><a class="lb-next" href="" ></a></div><div class="lb-loader"><a class="lb-cancel"></a></div></div></div><div class="lb-dataContainer"><div class="lb-data"><div class="lb-details"><span class="lb-caption"></span><span class="lb-number"></span></div><div class="lb-closeContainer"><a class="lb-close"></a></div></div></div></div>').appendTo(e("body")), this.$lightbox = e("#lightbox"), this.$overlay = e("#lightboxOverlay"), this.$outerContainer = this.$lightbox.find(".lb-outerContainer"), this.$container = this.$lightbox.find(".lb-container"), this.containerTopPadding = parseInt(this.$container.css("padding-top"), 10), this.containerRightPadding = parseInt(this.$container.css("padding-right"), 10), this.containerBottomPadding = parseInt(this.$container.css("padding-bottom"), 10), this.containerLeftPadding = parseInt(this.$container.css("padding-left"), 10), this.$overlay.hide().on("click", function() {
			return t.end(), !1
		}), this.$lightbox.hide().on("click", function(n) {
			return e(n.target).attr("id") === "lightbox" && t.end(), !1
		}), this.$outerContainer.on("click", function(n) {
			return e(n.target).attr("id") === "lightbox" && t.end(), !1
		}), this.$lightbox.find(".lb-prev").on("click", function() {
			return t.currentImageIndex === 0 ? t.changeImage(t.album.length - 1) : t.changeImage(t.currentImageIndex - 1), !1
		}), this.$lightbox.find(".lb-next").on("click", function() {
			return t.currentImageIndex === t.album.length - 1 ? t.changeImage(0) : t.changeImage(t.currentImageIndex + 1), !1
		}), this.$lightbox.find(".lb-loader, .lb-close").on("click", function() {
			return t.end(), !1
		})
	}, t.prototype.start = function(t) {
		function s(e) {
			n.album.push({
				link: e.attr("href"),
				title: e.attr("data-title") || e.attr("title")
			})
		}
		var n = this,
			r = e(window);
		r.on("resize", e.proxy(this.sizeOverlay, this)), e("/*select, */object, embed").css({
			visibility: "hidden"
		}), this.sizeOverlay(), this.album = [];
		var i = 0,
			o = t.attr("data-lightbox"),
			u;
		if (o) {
			u = e(t.prop("tagName") + '[data-lightbox="' + o + '"]');
			for (var a = 0; a < u.length; a = ++a) s(e(u[a])), u[a] === t[0] && (i = a)
		} else if (t.attr("rel") === "lightbox") s(t);
		else {
			u = e(t.prop("tagName") + '[rel="' + t.attr("rel") + '"]');
			for (var f = 0; f < u.length; f = ++f) s(e(u[f])), u[f] === t[0] && (i = f)
		}
		var l = r.scrollTop() + this.options.positionFromTop,
			c = r.scrollLeft();
		this.$lightbox.css({
			top: l + "px",
			left: c + "px"
		}).fadeIn(this.options.fadeDuration), this.changeImage(i)
	}, t.prototype.changeImage = function(t) {
		var n = this;
		this.disableKeyboardNav();
		var r = this.$lightbox.find(".lb-image");
		this.$overlay.fadeIn(this.options.fadeDuration), e(".lb-loader").fadeIn("slow"), this.$lightbox.find(".lb-image, .lb-nav, .lb-prev, .lb-next, .lb-dataContainer, .lb-numbers, .lb-caption").hide(), this.$outerContainer.addClass("animating");
		var i = new Image;
		i.onload = function() {
			var s, o, u, a, f, l, c;
			r.attr("src", n.album[t].link), s = e(i), r.width(i.width), r.height(i.height);
			if (n.options.fitImagesInViewport) {
				c = e(window).width(), l = e(window).height(), f = c - n.containerLeftPadding - n.containerRightPadding - 20, a = l - n.containerTopPadding - n.containerBottomPadding - 120, n.options.maxWidth && n.options.maxWidth < f && (f = n.options.maxWidth), n.options.maxHeight && n.options.maxHeight < f && (a = n.options.maxHeight);
				if (i.width > f || i.height > a) i.width / f > i.height / a ? (u = f, o = parseInt(i.height / (i.width / u), 10), r.width(u), r.height(o)) : (o = a, u = parseInt(i.width / (i.height / o), 10), r.width(u), r.height(o))
			}
			n.sizeContainer(r.width(), r.height())
		}, i.src = this.album[t].link, this.currentImageIndex = t
	}, t.prototype.sizeOverlay = function() {
		this.$overlay.width(e(window).width()).height(e(document).height())
	}, t.prototype.sizeContainer = function(e, t) {
		function u() {
			n.$lightbox.find(".lb-dataContainer").width(s), n.$lightbox.find(".lb-prevLink").height(o), n.$lightbox.find(".lb-nextLink").height(o), n.showImage()
		}
		var n = this,
			r = this.$outerContainer.outerWidth(),
			i = this.$outerContainer.outerHeight(),
			s = e + this.containerLeftPadding + this.containerRightPadding,
			o = t + this.containerTopPadding + this.containerBottomPadding;
		r !== s || i !== o ? this.$outerContainer.animate({
			width: s,
			height: o
		}, this.options.resizeDuration, "swing", function() {
			u()
		}) : u()
	}, t.prototype.showImage = function() {
		this.$lightbox.find(".lb-loader").stop(!0).hide(), this.$lightbox.find(".lb-image").fadeIn("slow"), this.updateNav(), this.updateDetails(), this.preloadNeighboringImages(), this.enableKeyboardNav()
	}, t.prototype.updateNav = function() {
		var e = !1;
		try {
			document.createEvent("TouchEvent"), e = this.options.alwaysShowNavOnTouchDevices ? !0 : !1
		} catch (t) {}
		this.$lightbox.find(".lb-nav").show(), this.album.length > 1 && (this.options.wrapAround ? (e && this.$lightbox.find(".lb-prev, .lb-next").css("opacity", "1"), this.$lightbox.find(".lb-prev, .lb-next").show()) : (this.currentImageIndex > 0 && (this.$lightbox.find(".lb-prev").show(), e && this.$lightbox.find(".lb-prev").css("opacity", "1")), this.currentImageIndex < this.album.length - 1 && (this.$lightbox.find(".lb-next").show(), e && this.$lightbox.find(".lb-next").css("opacity", "1"))))
	}, t.prototype.updateDetails = function() {
		var t = this;
		typeof this.album[this.currentImageIndex].title != "undefined" && this.album[this.currentImageIndex].title !== "" && this.$lightbox.find(".lb-caption").html(this.album[this.currentImageIndex].title).fadeIn("fast").find("a").on("click", function(t) {
			e(this).attr("target") !== undefined ? window.open(e(this).attr("href"), e(this).attr("target")) : location.href = e(this).attr("href")
		});
		if (this.album.length > 1 && this.options.showImageNumberLabel) {
			var n = this.imageCountLabel(this.currentImageIndex + 1, this.album.length);
			this.$lightbox.find(".lb-number").text(n).fadeIn("fast")
		} else this.$lightbox.find(".lb-number").hide();
		this.$outerContainer.removeClass("animating"), this.$lightbox.find(".lb-dataContainer").fadeIn(this.options.resizeDuration, function() {
			return t.sizeOverlay()
		})
	}, t.prototype.preloadNeighboringImages = function() {
		if (this.album.length > this.currentImageIndex + 1) {
			var e = new Image;
			e.src = this.album[this.currentImageIndex + 1].link
		}
		if (this.currentImageIndex > 0) {
			var t = new Image;
			t.src = this.album[this.currentImageIndex - 1].link
		}
	}, t.prototype.enableKeyboardNav = function() {
		e(document).on("keyup.keyboard", e.proxy(this.keyboardAction, this))
	}, t.prototype.disableKeyboardNav = function() {
		e(document).off(".keyboard")
	}, t.prototype.keyboardAction = function(e) {
		var t = 27,
			n = 37,
			r = 39,
			i = e.keyCode,
			s = String.fromCharCode(i).toLowerCase();
		if (i === t || s.match(/x|o|c/)) this.end();
		else if (s === "p" || i === n) this.currentImageIndex !== 0 ? this.changeImage(this.currentImageIndex - 1) : this.options.wrapAround && this.album.length > 1 && this.changeImage(this.album.length - 1);
		else if (s === "n" || i === r) this.currentImageIndex !== this.album.length - 1 ? this.changeImage(this.currentImageIndex + 1) : this.options.wrapAround && this.album.length > 1 && this.changeImage(0)
	}, t.prototype.end = function() {
		this.disableKeyboardNav(), e(window).off("resize", this.sizeOverlay), this.$lightbox.fadeOut(this.options.fadeDuration), this.$overlay.fadeOut(this.options.fadeDuration), e("select, object, embed").css({
			visibility: "visible"
		})
	}, new t
}), function() {
	GD.lightbox.option({
		fadeDuration: 200,
		resizeDuration: 100
	}), GD.initLightbox = function() {
		return $(".lightbox-image-link").each(function() {
			var e, t;
			e = $(this).find(".image-loading"), t = e.data("url");
			if (t) return $(this).append("<img src=" + t + " class='hide'>"), $(this).find("img").load(function() {
				return $(this).removeClass("hide"), e.remove()
			})
		}), $("#lightboxOverlay").length === 0 ? GD.lightbox.init() : GD.lightbox.enable()
	}, $(document).on("page:load ready", function() {
		return GD.initLightbox()
	}), $(function() {
		return $(".mobile-device #lightbox").livequery(function() {
			var e, t, n;
			return e = new Hammer.Manager($(".lb-container")[0], {
				recognizers: [
					[Hammer.Pinch,
					{
						enable: !0
					}],
					[Hammer.Swipe,
					{
						direction: Hammer.DIRECTION_HORIZONTAL
					}]
				]
			}), t = 1, n = function(e) {
				return $(".lb-container .lb-image").css({
					transform: "scale(" + e + ", " + e + ")"
				})
			}, e.on("swiperight", function() {
				var e;
				return e = GD.lightbox.currentImageIndex, e !== 0 && GD.lightbox.changeImage(e - 1), t = 1, n(t)
			}), e.on("swipeleft", function() {
				var e;
				return e = GD.lightbox.currentImageIndex, e !== GD.lightbox.album.length - 1 && GD.lightbox.changeImage(e + 1), t = 1, n(t)
			}), e.on("pinch", function(e) {
				return n(Math.max(1, Math.min(t * e.scale, 10)))
			}), e.on("pinchend", function(e) {
				return t = Math.max(1, Math.min(t * e.scale, 10)), n(t)
			})
		})
	})
}.call(this), function(e) {
	typeof define == "function" && define.amd ? define(["jquery"], e) : typeof exports == "object" ? e(require("jquery")) : e(jQuery)
}(function(e) {
	var t = function() {
			if (e && e.fn && e.fn.select2 && e.fn.select2.amd) var t = e.fn.select2.amd;
			var t;
			return function() {
				if (!t || !t.requirejs) {
					t ? n = t : t = {};
					var e, n, r;
					(function(t) {
						function v(e, t) {
							return h.call(e, t)
						}
						function m(e, t) {
							var n, r, i, s, o, u, a, f, c, h, p, v = t && t.split("/"),
								m = l.map,
								g = m && m["*"] || {};
							if (e && e.charAt(0) === ".") if (t) {
								e = e.split("/"), o = e.length - 1, l.nodeIdCompat && d.test(e[o]) && (e[o] = e[o].replace(d, "")), e = v.slice(0, v.length - 1).concat(e);
								for (c = 0; c < e.length; c += 1) {
									p = e[c];
									if (p === ".") e.splice(c, 1), c -= 1;
									else if (p === "..") {
										if (c === 1 && (e[2] === ".." || e[0] === "..")) break;
										c > 0 && (e.splice(c - 1, 2), c -= 2)
									}
								}
								e = e.join("/")
							} else e.indexOf("./") === 0 && (e = e.substring(2));
							if ((v || g) && m) {
								n = e.split("/");
								for (c = n.length; c > 0; c -= 1) {
									r = n.slice(0, c).join("/");
									if (v) for (h = v.length; h > 0; h -= 1) {
										i = m[v.slice(0, h).join("/")];
										if (i) {
											i = i[r];
											if (i) {
												s = i, u = c;
												break
											}
										}
									}
									if (s) break;
									!a && g && g[r] && (a = g[r], f = c)
								}!s && a && (s = a, u = f), s && (n.splice(0, u, s), e = n.join("/"))
							}
							return e
						}
						function g(e, n) {
							return function() {
								var r = p.call(arguments, 0);
								return typeof r[0] != "string" && r.length === 1 && r.push(null), s.apply(t, r.concat([e, n]))
							}
						}
						function y(e) {
							return function(t) {
								return m(t, e)
							}
						}
						function b(e) {
							return function(t) {
								a[e] = t
							}
						}
						function w(e) {
							if (v(f, e)) {
								var n = f[e];
								delete f[e], c[e] = !0, i.apply(t, n)
							}
							if (!v(a, e) && !v(c, e)) throw new Error("No " + e);
							return a[e]
						}
						function E(e) {
							var t, n = e ? e.indexOf("!") : -1;
							return n > -1 && (t = e.substring(0, n), e = e.substring(n + 1, e.length)), [t, e]
						}
						function S(e) {
							return function() {
								return l && l.config && l.config[e] || {}
							}
						}
						var i, s, o, u, a = {},
							f = {},
							l = {},
							c = {},
							h = Object.prototype.hasOwnProperty,
							p = [].slice,
							d = /\.js$/;
						o = function(e, t) {
							var n, r = E(e),
								i = r[0];
							return e = r[1], i && (i = m(i, t), n = w(i)), i ? n && n.normalize ? e = n.normalize(e, y(t)) : e = m(e, t) : (e = m(e, t), r = E(e), i = r[0], e = r[1], i && (n = w(i))), {
								f: i ? i + "!" + e : e,
								n: e,
								pr: i,
								p: n
							}
						}, u = {
							require: function(e) {
								return g(e)
							},
							exports: function(e) {
								var t = a[e];
								return typeof t != "undefined" ? t : a[e] = {}
							},
							module: function(e) {
								return {
									id: e,
									uri: "",
									exports: a[e],
									config: S(e)
								}
							}
						}, i = function(e, n, r, i) {
							var s, l, h, p, d, m = [],
								y = typeof r,
								E;
							i = i || e;
							if (y === "undefined" || y === "function") {
								n = !n.length && r.length ? ["require", "exports", "module"] : n;
								for (d = 0; d < n.length; d += 1) {
									p = o(n[d], i), l = p.f;
									if (l === "require") m[d] = u.require(e);
									else if (l === "exports") m[d] = u.exports(e), E = !0;
									else if (l === "module") s = m[d] = u.module(e);
									else if (v(a, l) || v(f, l) || v(c, l)) m[d] = w(l);
									else {
										if (!p.p) throw new Error(e + " missing " + l);
										p.p.load(p.n, g(i, !0), b(l), {}), m[d] = a[l]
									}
								}
								h = r ? r.apply(a[e], m) : undefined;
								if (e) if (s && s.exports !== t && s.exports !== a[e]) a[e] = s.exports;
								else if (h !== t || !E) a[e] = h
							} else e && (a[e] = r)
						}, e = n = s = function(e, n, r, a, f) {
							if (typeof e == "string") return u[e] ? u[e](n) : w(o(e, n).f);
							if (!e.splice) {
								l = e, l.deps && s(l.deps, l.callback);
								if (!n) return;
								n.splice ? (e = n, n = r, r = null) : e = t
							}
							return n = n ||
							function() {}, typeof r == "function" && (r = a, a = f), a ? i(t, e, n, r) : setTimeout(function() {
								i(t, e, n, r)
							}, 4), s
						}, s.config = function(e) {
							return s(e)
						}, e._defined = a, r = function(e, t, n) {
							if (typeof e != "string") throw new Error("See almond README: incorrect module build, no module name");
							t.splice || (n = t, t = []), !v(a, e) && !v(f, e) && (f[e] = [e, t, n])
						}, r.amd = {
							jQuery: !0
						}
					})(), t.requirejs = e, t.require = n, t.define = r
				}
			}(), t.define("almond", function() {}), t.define("jquery", [], function() {
				var t = e || $;
				return t == null && console && console.error && console.error("Select2: An instance of jQuery or a jQuery-compatible library was not found. Make sure that you are including jQuery before Select2 on your web page."), t
			}), t.define("select2/utils", ["jquery"], function(e) {
				function n(e) {
					var t = e.prototype,
						n = [];
					for (var r in t) {
						var i = t[r];
						if (typeof i != "function") continue;
						if (r === "constructor") continue;
						n.push(r)
					}
					return n
				}
				var t = {};
				t.Extend = function(e, t) {
					function r() {
						this.constructor = e
					}
					var n = {}.hasOwnProperty;
					for (var i in t) n.call(t, i) && (e[i] = t[i]);
					return r.prototype = t.prototype, e.prototype = new r, e.__super__ = t.prototype, e
				}, t.Decorate = function(e, t) {
					function s() {
						var n = Array.prototype.unshift,
							r = t.prototype.constructor.length,
							i = e.prototype.constructor;
						r > 0 && (n.call(arguments, e.prototype.constructor), i = t.prototype.constructor), i.apply(this, arguments)
					}
					function o() {
						this.constructor = s
					}
					var r = n(t),
						i = n(e);
					t.displayName = e.displayName, s.prototype = new o;
					for (var u = 0; u < i.length; u++) {
						var a = i[u];
						s.prototype[a] = e.prototype[a]
					}
					var f = function(e) {
							var n = function() {};
							e in s.prototype && (n = s.prototype[e]);
							var r = t.prototype[e];
							return function() {
								var e = Array.prototype.unshift;
								return e.call(arguments, n), r.apply(this, arguments)
							}
						};
					for (var l = 0; l < r.length; l++) {
						var c = r[l];
						s.prototype[c] = f(c)
					}
					return s
				};
				var r = function() {
						this.listeners = {}
					};
				return r.prototype.on = function(e, t) {
					this.listeners = this.listeners || {}, e in this.listeners ? this.listeners[e].push(t) : this.listeners[e] = [t]
				}, r.prototype.trigger = function(e) {
					var t = Array.prototype.slice;
					this.listeners = this.listeners || {}, e in this.listeners && this.invoke(this.listeners[e], t.call(arguments, 1)), "*" in this.listeners && this.invoke(this.listeners["*"], arguments)
				}, r.prototype.invoke = function(e, t) {
					for (var n = 0, r = e.length; n < r; n++) e[n].apply(this, t)
				}, t.Observable = r, t.generateChars = function(e) {
					var t = "";
					for (var n = 0; n < e; n++) {
						var r = Math.floor(Math.random() * 36);
						t += r.toString(36)
					}
					return t
				}, t.bind = function(e, t) {
					return function() {
						e.apply(t, arguments)
					}
				}, t._convertData = function(e) {
					for (var t in e) {
						var n = t.split("-"),
							r = e;
						if (n.length === 1) continue;
						for (var i = 0; i < n.length; i++) {
							var s = n[i];
							s = s.substring(0, 1).toLowerCase() + s.substring(1), s in r || (r[s] = {}), i == n.length - 1 && (r[s] = e[t]), r = r[s]
						}
						delete e[t]
					}
					return e
				}, t.hasScroll = function(t, n) {
					var r = e(n),
						i = n.style.overflowX,
						s = n.style.overflowY;
					return i !== s || s !== "hidden" && s !== "visible" ? i === "scroll" || s === "scroll" ? !0 : r.innerHeight() < n.scrollHeight || r.innerWidth() < n.scrollWidth : !1
				}, t.escapeMarkup = function(e) {
					var t = {
						"\\": "&#92;",
						"&": "&amp;",
						"<": "&lt;",
						">": "&gt;",
						'"': "&quot;",
						"'": "&#39;",
						"/": "&#47;"
					};
					return typeof e != "string" ? e : String(e).replace(/[&<>"'\/\\]/g, function(e) {
						return t[e]
					})
				}, t.appendMany = function(t, n) {
					if (e.fn.jquery.substr(0, 3) === "1.7") {
						var r = e();
						e.map(n, function(e) {
							r = r.add(e)
						}), n = r
					}
					t.append(n)
				}, t
			}), t.define("select2/results", ["jquery", "./utils"], function(e, t) {
				function n(e, t, r) {
					this.$element = e, this.data = r, this.options = t, n.__super__.constructor.call(this)
				}
				return t.Extend(n, t.Observable), n.prototype.render = function() {
					var t = e('<ul class="select2-results__options" role="tree"></ul>');
					return this.options.get("multiple") && t.attr("aria-multiselectable", "true"), this.$results = t, t
				}, n.prototype.clear = function() {
					this.$results.empty()
				}, n.prototype.displayMessage = function(t) {
					var n = this.options.get("escapeMarkup");
					this.clear(), this.hideLoading();
					var r = e('<li role="treeitem" aria-live="assertive" class="select2-results__option"></li>'),
						i = this.options.get("translations").get(t.message);
					r.append(n(i(t.args))), r[0].className += " select2-results__message", this.$results.append(r)
				}, n.prototype.hideMessages = function() {
					this.$results.find(".select2-results__message").remove()
				}, n.prototype.append = function(e) {
					this.hideLoading();
					var t = [];
					if (e.results == null || e.results.length === 0) {
						this.$results.children().length === 0 && this.trigger("results:message", {
							message: "noResults"
						});
						return
					}
					e.results = this.sort(e.results);
					for (var n = 0; n < e.results.length; n++) {
						var r = e.results[n],
							i = this.option(r);
						t.push(i)
					}
					this.$results.append(t)
				}, n.prototype.position = function(e, t) {
					var n = t.find(".select2-results");
					n.append(e)
				}, n.prototype.sort = function(e) {
					var t = this.options.get("sorter");
					return t(e)
				}, n.prototype.setClasses = function() {
					var t = this;
					this.data.current(function(n) {
						var r = e.map(n, function(e) {
							return e.id.toString()
						}),
							i = t.$results.find(".select2-results__option[aria-selected]");
						i.each(function() {
							var t = e(this),
								n = e.data(this, "data"),
								i = "" + n.id;
							n.element != null && n.element.selected || n.element == null && e.inArray(i, r) > -1 ? t.attr("aria-selected", "true") : t.attr("aria-selected", "false")
						});
						var s = i.filter("[aria-selected=true]");
						s.length > 0 ? s.first().trigger("mouseenter") : i.first().trigger("mouseenter")
					})
				}, n.prototype.showLoading = function(e) {
					this.hideLoading();
					var t = this.options.get("translations").get("searching"),
						n = {
							disabled: !0,
							loading: !0,
							text: t(e)
						},
						r = this.option(n);
					r.className += " loading-results", this.$results.prepend(r)
				}, n.prototype.hideLoading = function() {
					this.$results.find(".loading-results").remove()
				}, n.prototype.option = function(t) {
					var n = document.createElement("li");
					n.className = "select2-results__option";
					var r = {
						role: "treeitem",
						"aria-selected": "false"
					};
					t.disabled && (delete r["aria-selected"], r["aria-disabled"] = "true"), t.id == null && delete r["aria-selected"], t._resultId != null && (n.id = t._resultId), t.title && (n.title = t.title), t.children && (r.role = "group", r["aria-label"] = t.text, delete r["aria-selected"]);
					for (var i in r) {
						var s = r[i];
						n.setAttribute(i, s)
					}
					if (t.children) {
						var o = e(n),
							u = document.createElement("strong");
						u.className = "select2-results__group";
						var a = e(u);
						this.template(t, u);
						var f = [];
						for (var l = 0; l < t.children.length; l++) {
							var c = t.children[l],
								h = this.option(c);
							f.push(h)
						}
						var p = e("<ul></ul>", {
							"class": "select2-results__options select2-results__options--nested"
						});
						p.append(f), o.append(u), o.append(p)
					} else this.template(t, n);
					return e.data(n, "data", t), n
				}, n.prototype.bind = function(t, n) {
					var r = this,
						i = t.id + "-results";
					this.$results.attr("id", i), t.on("results:all", function(e) {
						r.clear(), r.append(e.data), t.isOpen() && r.setClasses()
					}), t.on("results:append", function(e) {
						r.append(e.data), t.isOpen() && r.setClasses()
					}), t.on("query", function(e) {
						r.hideMessages(), r.showLoading(e)
					}), t.on("select", function() {
						if (!t.isOpen()) return;
						r.setClasses()
					}), t.on("unselect", function() {
						if (!t.isOpen()) return;
						r.setClasses()
					}), t.on("open", function() {
						r.$results.attr("aria-expanded", "true"), r.$results.attr("aria-hidden", "false"), r.setClasses(), r.ensureHighlightVisible()
					}), t.on("close", function() {
						r.$results.attr("aria-expanded", "false"), r.$results.attr("aria-hidden", "true"), r.$results.removeAttr("aria-activedescendant")
					}), t.on("results:toggle", function() {
						var e = r.getHighlightedResults();
						if (e.length === 0) return;
						e.trigger("mouseup")
					}), t.on("results:select", function() {
						var e = r.getHighlightedResults();
						if (e.length === 0) return;
						var t = e.data("data");
						e.attr("aria-selected") == "true" ? r.trigger("close", {}) : r.trigger("select", {
							data: t
						})
					}), t.on("results:previous", function() {
						var e = r.getHighlightedResults(),
							t = r.$results.find("[aria-selected]"),
							n = t.index(e);
						if (n === 0) return;
						var i = n - 1;
						e.length === 0 && (i = 0);
						var s = t.eq(i);
						s.trigger("mouseenter");
						var o = r.$results.offset().top,
							u = s.offset().top,
							a = r.$results.scrollTop() + (u - o);
						i === 0 ? r.$results.scrollTop(0) : u - o < 0 && r.$results.scrollTop(a)
					}), t.on("results:next", function() {
						var e = r.getHighlightedResults(),
							t = r.$results.find("[aria-selected]"),
							n = t.index(e),
							i = n + 1;
						if (i >= t.length) return;
						var s = t.eq(i);
						s.trigger("mouseenter");
						var o = r.$results.offset().top + r.$results.outerHeight(!1),
							u = s.offset().top + s.outerHeight(!1),
							a = r.$results.scrollTop() + u - o;
						i === 0 ? r.$results.scrollTop(0) : u > o && r.$results.scrollTop(a)
					}), t.on("results:focus", function(e) {
						e.element.addClass("select2-results__option--highlighted")
					}), t.on("results:message", function(e) {
						r.displayMessage(e)
					}), e.fn.mousewheel && this.$results.on("mousewheel", function(e) {
						var t = r.$results.scrollTop(),
							n = r.$results.get(0).scrollHeight - t + e.deltaY,
							i = e.deltaY > 0 && t - e.deltaY <= 0,
							s = e.deltaY < 0 && n <= r.$results.height();
						i ? (r.$results.scrollTop(0), e.preventDefault(), e.stopPropagation()) : s && (r.$results.scrollTop(r.$results.get(0).scrollHeight - r.$results.height()), e.preventDefault(), e.stopPropagation())
					}), this.$results.on("mouseup", ".select2-results__option[aria-selected]", function(t) {
						var n = e(this),
							i = n.data("data");
						if (n.attr("aria-selected") === "true") {
							r.options.get("multiple") ? r.trigger("unselect", {
								originalEvent: t,
								data: i
							}) : r.trigger("close", {});
							return
						}
						r.trigger("select", {
							originalEvent: t,
							data: i
						})
					}), this.$results.on("mouseenter", ".select2-results__option[aria-selected]", function(t) {
						var n = e(this).data("data");
						r.getHighlightedResults().removeClass("select2-results__option--highlighted"), r.trigger("results:focus", {
							data: n,
							element: e(this)
						})
					})
				}, n.prototype.getHighlightedResults = function() {
					var e = this.$results.find(".select2-results__option--highlighted");
					return e
				}, n.prototype.destroy = function() {
					this.$results.remove()
				}, n.prototype.ensureHighlightVisible = function() {
					var e = this.getHighlightedResults();
					if (e.length === 0) return;
					var t = this.$results.find("[aria-selected]"),
						n = t.index(e),
						r = this.$results.offset().top,
						i = e.offset().top,
						s = this.$results.scrollTop() + (i - r),
						o = i - r;
					s -= e.outerHeight(!1) * 2, n <= 2 ? this.$results.scrollTop(0) : (o > this.$results.outerHeight() || o < 0) && this.$results.scrollTop(s)
				}, n.prototype.template = function(t, n) {
					var r = this.options.get("templateResult"),
						i = this.options.get("escapeMarkup"),
						s = r(t, n);
					s == null ? n.style.display = "none" : typeof s == "string" ? n.innerHTML = i(s) : e(n).append(s)
				}, n
			}), t.define("select2/keys", [], function() {
				var e = {
					BACKSPACE: 8,
					TAB: 9,
					ENTER: 13,
					SHIFT: 16,
					CTRL: 17,
					ALT: 18,
					ESC: 27,
					SPACE: 32,
					PAGE_UP: 33,
					PAGE_DOWN: 34,
					END: 35,
					HOME: 36,
					LEFT: 37,
					UP: 38,
					RIGHT: 39,
					DOWN: 40,
					DELETE: 46
				};
				return e
			}), t.define("select2/selection/base", ["jquery", "../utils", "../keys"], function(e, t, n) {
				function r(e, t) {
					this.$element = e, this.options = t, r.__super__.constructor.call(this)
				}
				return t.Extend(r, t.Observable), r.prototype.render = function() {
					var t = e('<span class="select2-selection" role="combobox"  aria-haspopup="true" aria-expanded="false"></span>');
					return this._tabindex = 0, this.$element.data("old-tabindex") != null ? this._tabindex = this.$element.data("old-tabindex") : this.$element.attr("tabindex") != null && (this._tabindex = this.$element.attr("tabindex")), t.attr("title", this.$element.attr("title")), t.attr("tabindex", this._tabindex), this.$selection = t, t
				}, r.prototype.bind = function(e, t) {
					var r = this,
						i = e.id + "-container",
						s = e.id + "-results";
					this.container = e, this.$selection.on("focus", function(e) {
						r.trigger("focus", e)
					}), this.$selection.on("blur", function(e) {
						r._handleBlur(e)
					}), this.$selection.on("keydown", function(e) {
						r.trigger("keypress", e), e.which === n.SPACE && e.preventDefault()
					}), e.on("results:focus", function(e) {
						r.$selection.attr("aria-activedescendant", e.data._resultId)
					}), e.on("selection:update", function(e) {
						r.update(e.data)
					}), e.on("open", function() {
						r.$selection.attr("aria-expanded", "true"), r.$selection.attr("aria-owns", s), r._attachCloseHandler(e)
					}), e.on("close", function() {
						r.$selection.attr("aria-expanded", "false"), r.$selection.removeAttr("aria-activedescendant"), r.$selection.removeAttr("aria-owns"), r.$selection.focus(), r._detachCloseHandler(e)
					}), e.on("enable", function() {
						r.$selection.attr("tabindex", r._tabindex)
					}), e.on("disable", function() {
						r.$selection.attr("tabindex", "-1")
					})
				}, r.prototype._handleBlur = function(t) {
					var n = this;
					window.setTimeout(function() {
						if (document.activeElement == n.$selection[0] || e.contains(n.$selection[0], document.activeElement)) return;
						n.trigger("blur", t)
					}, 1)
				}, r.prototype._attachCloseHandler = function(t) {
					var n = this;
					e(document.body).on("mousedown.select2." + t.id, function(t) {
						var n = e(t.target),
							r = n.closest(".select2"),
							i = e(".select2.select2-container--open");
						i.each(function() {
							var t = e(this);
							if (this == r[0]) return;
							var n = t.data("element");
							n.select2("close")
						})
					})
				}, r.prototype._detachCloseHandler = function(t) {
					e(document.body).off("mousedown.select2." + t.id)
				}, r.prototype.position = function(e, t) {
					var n = t.find(".selection");
					n.append(e)
				}, r.prototype.destroy = function() {
					this._detachCloseHandler(this.container)
				}, r.prototype.update = function(e) {
					throw new Error("The `update` method must be defined in child classes.")
				}, r
			}), t.define("select2/selection/single", ["jquery", "./base", "../utils", "../keys"], function(e, t, n, r) {
				function i() {
					i.__super__.constructor.apply(this, arguments)
				}
				return n.Extend(i, t), i.prototype.render = function() {
					var e = i.__super__.render.call(this);
					return e.addClass("select2-selection--single"), e.html('<span class="select2-selection__rendered"></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span>'), e
				}, i.prototype.bind = function(e, t) {
					var n = this;
					i.__super__.bind.apply(this, arguments);
					var r = e.id + "-container";
					this.$selection.find(".select2-selection__rendered").attr("id", r), this.$selection.attr("aria-labelledby", r), this.$selection.on("mousedown", function(e) {
						if (e.which !== 1) return;
						n.trigger("toggle", {
							originalEvent: e
						})
					}), this.$selection.on("focus", function(e) {}), this.$selection.on("blur", function(e) {}), e.on("selection:update", function(e) {
						n.update(e.data)
					})
				}, i.prototype.clear = function() {
					this.$selection.find(".select2-selection__rendered").empty()
				}, i.prototype.display = function(e, t) {
					var n = this.options.get("templateSelection"),
						r = this.options.get("escapeMarkup");
					return r(n(e, t))
				}, i.prototype.selectionContainer = function() {
					return e("<span></span>")
				}, i.prototype.update = function(e) {
					if (e.length === 0) {
						this.clear();
						return
					}
					var t = e[0],
						n = this.$selection.find(".select2-selection__rendered"),
						r = this.display(t, n);
					n.empty().append(r), n.prop("title", t.title || t.text)
				}, i
			}), t.define("select2/selection/multiple", ["jquery", "./base", "../utils"], function(e, t, n) {
				function r(e, t) {
					r.__super__.constructor.apply(this, arguments)
				}
				return n.Extend(r, t), r.prototype.render = function() {
					var e = r.__super__.render.call(this);
					return e.addClass("select2-selection--multiple"), e.html('<ul class="select2-selection__rendered"></ul>'), e
				}, r.prototype.bind = function(t, n) {
					var i = this;
					r.__super__.bind.apply(this, arguments), this.$selection.on("click", function(e) {
						i.trigger("toggle", {
							originalEvent: e
						})
					}), this.$selection.on("click", ".select2-selection__choice__remove", function(t) {
						if (i.options.get("disabled")) return;
						var n = e(this),
							r = n.parent(),
							s = r.data("data");
						i.trigger("unselect", {
							originalEvent: t,
							data: s
						})
					})
				}, r.prototype.clear = function() {
					this.$selection.find(".select2-selection__rendered").empty()
				}, r.prototype.display = function(e, t) {
					var n = this.options.get("templateSelection"),
						r = this.options.get("escapeMarkup");
					return r(n(e, t))
				}, r.prototype.selectionContainer = function() {
					var t = e('<li class="select2-selection__choice"><span class="select2-selection__choice__remove" role="presentation">&times;</span></li>');
					return t
				}, r.prototype.update = function(e) {
					this.clear();
					if (e.length === 0) return;
					var t = [];
					for (var r = 0; r < e.length; r++) {
						var i = e[r],
							s = this.selectionContainer(),
							o = this.display(i, s);
						s.append(o), s.prop("title", i.title || i.text), s.data("data", i), t.push(s)
					}
					var u = this.$selection.find(".select2-selection__rendered");
					n.appendMany(u, t)
				}, r
			}), t.define("select2/selection/placeholder", ["../utils"], function(e) {
				function t(e, t, n) {
					this.placeholder = this.normalizePlaceholder(n.get("placeholder")), e.call(this, t, n)
				}
				return t.prototype.normalizePlaceholder = function(e, t) {
					return typeof t == "string" && (t = {
						id: "",
						text: t
					}), t
				}, t.prototype.createPlaceholder = function(e, t) {
					var n = this.selectionContainer();
					return n.html(this.display(t)), n.addClass("select2-selection__placeholder").removeClass("select2-selection__choice"), n
				}, t.prototype.update = function(e, t) {
					var n = t.length == 1 && t[0].id != this.placeholder.id,
						r = t.length > 1;
					if (r || n) return e.call(this, t);
					this.clear();
					var i = this.createPlaceholder(this.placeholder);
					this.$selection.find(".select2-selection__rendered").append(i)
				}, t
			}), t.define("select2/selection/allowClear", ["jquery", "../keys"], function(e, t) {
				function n() {}
				return n.prototype.bind = function(e, t, n) {
					var r = this;
					e.call(this, t, n), this.placeholder == null && this.options.get("debug") && window.console && console.error && console.error("Select2: The `allowClear` option should be used in combination with the `placeholder` option."), this.$selection.on("mousedown", ".select2-selection__clear", function(e) {
						r._handleClear(e)
					}), t.on("keypress", function(e) {
						r._handleKeyboardClear(e, t)
					})
				}, n.prototype._handleClear = function(e, t) {
					if (this.options.get("disabled")) return;
					var n = this.$selection.find(".select2-selection__clear");
					if (n.length === 0) return;
					t.stopPropagation();
					var r = n.data("data");
					for (var i = 0; i < r.length; i++) {
						var s = {
							data: r[i]
						};
						this.trigger("unselect", s);
						if (s.prevented) return
					}
					this.$element.val(this.placeholder.id).trigger("change"), this.trigger("toggle", {})
				}, n.prototype._handleKeyboardClear = function(e, n, r) {
					if (r.isOpen()) return;
					(n.which == t.DELETE || n.which == t.BACKSPACE) && this._handleClear(n)
				}, n.prototype.update = function(t, n) {
					t.call(this, n);
					if (this.$selection.find(".select2-selection__placeholder").length > 0 || n.length === 0) return;
					var r = e('<span class="select2-selection__clear">&times;</span>');
					r.data("data", n), this.$selection.find(".select2-selection__rendered").prepend(r)
				}, n
			}), t.define("select2/selection/search", ["jquery", "../utils", "../keys"], function(e, t, n) {
				function r(e, t, n) {
					e.call(this, t, n)
				}
				return r.prototype.render = function(t) {
					var n = e('<li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="-1" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" /></li>');
					this.$searchContainer = n, this.$search = n.find("input");
					var r = t.call(this);
					return this._transferTabIndex(), r
				}, r.prototype.bind = function(e, t, r) {
					var i = this;
					e.call(this, t, r), t.on("open", function() {
						i.$search.trigger("focus")
					}), t.on("close", function() {
						i.$search.val(""), i.$search.removeAttr("aria-activedescendant"), i.$search.trigger("focus")
					}), t.on("enable", function() {
						i.$search.prop("disabled", !1), i._transferTabIndex()
					}), t.on("disable", function() {
						i.$search.prop("disabled", !0)
					}), t.on("focus", function(e) {
						i.$search.trigger("focus")
					}), t.on("results:focus", function(e) {
						i.$search.attr("aria-activedescendant", e.id)
					}), this.$selection.on("focusin", ".select2-search--inline", function(e) {
						i.trigger("focus", e)
					}), this.$selection.on("focusout", ".select2-search--inline", function(e) {
						i._handleBlur(e)
					}), this.$selection.on("keydown", ".select2-search--inline", function(e) {
						e.stopPropagation(), i.trigger("keypress", e), i._keyUpPrevented = e.isDefaultPrevented();
						var t = e.which;
						if (t === n.BACKSPACE && i.$search.val() === "") {
							var r = i.$searchContainer.prev(".select2-selection__choice");
							if (r.length > 0) {
								var s = r.data("data");
								i.searchRemoveChoice(s), e.preventDefault()
							}
						}
					});
					var s = document.documentMode,
						o = s && s <= 11;
					this.$selection.on("input.searchcheck", ".select2-search--inline", function(e) {
						if (o) {
							i.$selection.off("input.search input.searchcheck");
							return
						}
						i.$selection.off("keyup.search")
					}), this.$selection.on("keyup.search input.search", ".select2-search--inline", function(e) {
						if (o && e.type === "input") {
							i.$selection.off("input.search input.searchcheck");
							return
						}
						var t = e.which;
						if (t == n.SHIFT || t == n.CTRL || t == n.ALT) return;
						if (t == n.TAB) return;
						i.handleSearch(e)
					})
				}, r.prototype._transferTabIndex = function(e) {
					this.$search.attr("tabindex", this.$selection.attr("tabindex")), this.$selection.attr("tabindex", "-1")
				}, r.prototype.createPlaceholder = function(e, t) {
					this.$search.attr("placeholder", t.text)
				}, r.prototype.update = function(e, t) {
					var n = this.$search[0] == document.activeElement;
					this.$search.attr("placeholder", ""), e.call(this, t), this.$selection.find(".select2-selection__rendered").append(this.$searchContainer), this.resizeSearch(), n && this.$search.focus()
				}, r.prototype.handleSearch = function() {
					this.resizeSearch();
					if (!this._keyUpPrevented) {
						var e = this.$search.val();
						this.trigger("query", {
							term: e
						})
					}
					this._keyUpPrevented = !1
				}, r.prototype.searchRemoveChoice = function(e, t) {
					this.trigger("unselect", {
						data: t
					}), this.$search.val(t.text), this.handleSearch()
				}, r.prototype.resizeSearch = function() {
					this.$search.css("width", "25px");
					var e = "";
					if (this.$search.attr("placeholder") !== "") e = this.$selection.find(".select2-selection__rendered").innerWidth();
					else {
						var t = this.$search.val().length + 1;
						e = t * .75 + "em"
					}
					this.$search.css("width", e)
				}, r
			}), t.define("select2/selection/eventRelay", ["jquery"], function(e) {
				function t() {}
				return t.prototype.bind = function(t, n, r) {
					var i = this,
						s = ["open", "opening", "close", "closing", "select", "selecting", "unselect", "unselecting"],
						o = ["opening", "closing", "selecting", "unselecting"];
					t.call(this, n, r), n.on("*", function(t, n) {
						if (e.inArray(t, s) === -1) return;
						n = n || {};
						var r = e.Event("select2:" + t, {
							params: n
						});
						i.$element.trigger(r);
						if (e.inArray(t, o) === -1) return;
						n.prevented = r.isDefaultPrevented()
					})
				}, t
			}), t.define("select2/translation", ["jquery", "require"], function(e, t) {
				function n(e) {
					this.dict = e || {}
				}
				return n.prototype.all = function() {
					return this.dict
				}, n.prototype.get = function(e) {
					return this.dict[e]
				}, n.prototype.extend = function(t) {
					this.dict = e.extend({}, t.all(), this.dict)
				}, n._cache = {}, n.loadPath = function(e) {
					if (!(e in n._cache)) {
						var r = t(e);
						n._cache[e] = r
					}
					return new n(n._cache[e])
				}, n
			}), t.define("select2/diacritics", [], function() {
				var e = {
					"Ⓐ": "A",
					"Ａ": "A",
					"À": "A",
					"Á": "A",
					"Â": "A",
					"Ầ": "A",
					"Ấ": "A",
					"Ẫ": "A",
					"Ẩ": "A",
					"Ã": "A",
					"Ā": "A",
					"Ă": "A",
					"Ằ": "A",
					"Ắ": "A",
					"Ẵ": "A",
					"Ẳ": "A",
					"Ȧ": "A",
					"Ǡ": "A",
					"Ä": "A",
					"Ǟ": "A",
					"Ả": "A",
					"Å": "A",
					"Ǻ": "A",
					"Ǎ": "A",
					"Ȁ": "A",
					"Ȃ": "A",
					"Ạ": "A",
					"Ậ": "A",
					"Ặ": "A",
					"Ḁ": "A",
					"Ą": "A",
					"Ⱥ": "A",
					"Ɐ": "A",
					"Ꜳ": "AA",
					"Æ": "AE",
					"Ǽ": "AE",
					"Ǣ": "AE",
					"Ꜵ": "AO",
					"Ꜷ": "AU",
					"Ꜹ": "AV",
					"Ꜻ": "AV",
					"Ꜽ": "AY",
					"Ⓑ": "B",
					"Ｂ": "B",
					"Ḃ": "B",
					"Ḅ": "B",
					"Ḇ": "B",
					"Ƀ": "B",
					"Ƃ": "B",
					"Ɓ": "B",
					"Ⓒ": "C",
					"Ｃ": "C",
					"Ć": "C",
					"Ĉ": "C",
					"Ċ": "C",
					"Č": "C",
					"Ç": "C",
					"Ḉ": "C",
					"Ƈ": "C",
					"Ȼ": "C",
					"Ꜿ": "C",
					"Ⓓ": "D",
					"Ｄ": "D",
					"Ḋ": "D",
					"Ď": "D",
					"Ḍ": "D",
					"Ḑ": "D",
					"Ḓ": "D",
					"Ḏ": "D",
					"Đ": "D",
					"Ƌ": "D",
					"Ɗ": "D",
					"Ɖ": "D",
					"Ꝺ": "D",
					"Ǳ": "DZ",
					"Ǆ": "DZ",
					"ǲ": "Dz",
					"ǅ": "Dz",
					"Ⓔ": "E",
					"Ｅ": "E",
					"È": "E",
					"É": "E",
					"Ê": "E",
					"Ề": "E",
					"Ế": "E",
					"Ễ": "E",
					"Ể": "E",
					"Ẽ": "E",
					"Ē": "E",
					"Ḕ": "E",
					"Ḗ": "E",
					"Ĕ": "E",
					"Ė": "E",
					"Ë": "E",
					"Ẻ": "E",
					"Ě": "E",
					"Ȅ": "E",
					"Ȇ": "E",
					"Ẹ": "E",
					"Ệ": "E",
					"Ȩ": "E",
					"Ḝ": "E",
					"Ę": "E",
					"Ḙ": "E",
					"Ḛ": "E",
					"Ɛ": "E",
					"Ǝ": "E",
					"Ⓕ": "F",
					"Ｆ": "F",
					"Ḟ": "F",
					"Ƒ": "F",
					"Ꝼ": "F",
					"Ⓖ": "G",
					"Ｇ": "G",
					"Ǵ": "G",
					"Ĝ": "G",
					"Ḡ": "G",
					"Ğ": "G",
					"Ġ": "G",
					"Ǧ": "G",
					"Ģ": "G",
					"Ǥ": "G",
					"Ɠ": "G",
					"Ꞡ": "G",
					"Ᵹ": "G",
					"Ꝿ": "G",
					"Ⓗ": "H",
					"Ｈ": "H",
					"Ĥ": "H",
					"Ḣ": "H",
					"Ḧ": "H",
					"Ȟ": "H",
					"Ḥ": "H",
					"Ḩ": "H",
					"Ḫ": "H",
					"Ħ": "H",
					"Ⱨ": "H",
					"Ⱶ": "H",
					"Ɥ": "H",
					"Ⓘ": "I",
					"Ｉ": "I",
					"Ì": "I",
					"Í": "I",
					"Î": "I",
					"Ĩ": "I",
					"Ī": "I",
					"Ĭ": "I",
					"İ": "I",
					"Ï": "I",
					"Ḯ": "I",
					"Ỉ": "I",
					"Ǐ": "I",
					"Ȉ": "I",
					"Ȋ": "I",
					"Ị": "I",
					"Į": "I",
					"Ḭ": "I",
					"Ɨ": "I",
					"Ⓙ": "J",
					"Ｊ": "J",
					"Ĵ": "J",
					"Ɉ": "J",
					"Ⓚ": "K",
					"Ｋ": "K",
					"Ḱ": "K",
					"Ǩ": "K",
					"Ḳ": "K",
					"Ķ": "K",
					"Ḵ": "K",
					"Ƙ": "K",
					"Ⱪ": "K",
					"Ꝁ": "K",
					"Ꝃ": "K",
					"Ꝅ": "K",
					"Ꞣ": "K",
					"Ⓛ": "L",
					"Ｌ": "L",
					"Ŀ": "L",
					"Ĺ": "L",
					"Ľ": "L",
					"Ḷ": "L",
					"Ḹ": "L",
					"Ļ": "L",
					"Ḽ": "L",
					"Ḻ": "L",
					"Ł": "L",
					"Ƚ": "L",
					"Ɫ": "L",
					"Ⱡ": "L",
					"Ꝉ": "L",
					"Ꝇ": "L",
					"Ꞁ": "L",
					"Ǉ": "LJ",
					"ǈ": "Lj",
					"Ⓜ": "M",
					"Ｍ": "M",
					"Ḿ": "M",
					"Ṁ": "M",
					"Ṃ": "M",
					"Ɱ": "M",
					"Ɯ": "M",
					"Ⓝ": "N",
					"Ｎ": "N",
					"Ǹ": "N",
					"Ń": "N",
					"Ñ": "N",
					"Ṅ": "N",
					"Ň": "N",
					"Ṇ": "N",
					"Ņ": "N",
					"Ṋ": "N",
					"Ṉ": "N",
					"Ƞ": "N",
					"Ɲ": "N",
					"Ꞑ": "N",
					"Ꞥ": "N",
					"Ǌ": "NJ",
					"ǋ": "Nj",
					"Ⓞ": "O",
					"Ｏ": "O",
					"Ò": "O",
					"Ó": "O",
					"Ô": "O",
					"Ồ": "O",
					"Ố": "O",
					"Ỗ": "O",
					"Ổ": "O",
					"Õ": "O",
					"Ṍ": "O",
					"Ȭ": "O",
					"Ṏ": "O",
					"Ō": "O",
					"Ṑ": "O",
					"Ṓ": "O",
					"Ŏ": "O",
					"Ȯ": "O",
					"Ȱ": "O",
					"Ö": "O",
					"Ȫ": "O",
					"Ỏ": "O",
					"Ő": "O",
					"Ǒ": "O",
					"Ȍ": "O",
					"Ȏ": "O",
					"Ơ": "O",
					"Ờ": "O",
					"Ớ": "O",
					"Ỡ": "O",
					"Ở": "O",
					"Ợ": "O",
					"Ọ": "O",
					"Ộ": "O",
					"Ǫ": "O",
					"Ǭ": "O",
					"Ø": "O",
					"Ǿ": "O",
					"Ɔ": "O",
					"Ɵ": "O",
					"Ꝋ": "O",
					"Ꝍ": "O",
					"Ƣ": "OI",
					"Ꝏ": "OO",
					"Ȣ": "OU",
					"Ⓟ": "P",
					"Ｐ": "P",
					"Ṕ": "P",
					"Ṗ": "P",
					"Ƥ": "P",
					"Ᵽ": "P",
					"Ꝑ": "P",
					"Ꝓ": "P",
					"Ꝕ": "P",
					"Ⓠ": "Q",
					"Ｑ": "Q",
					"Ꝗ": "Q",
					"Ꝙ": "Q",
					"Ɋ": "Q",
					"Ⓡ": "R",
					"Ｒ": "R",
					"Ŕ": "R",
					"Ṙ": "R",
					"Ř": "R",
					"Ȑ": "R",
					"Ȓ": "R",
					"Ṛ": "R",
					"Ṝ": "R",
					"Ŗ": "R",
					"Ṟ": "R",
					"Ɍ": "R",
					"Ɽ": "R",
					"Ꝛ": "R",
					"Ꞧ": "R",
					"Ꞃ": "R",
					"Ⓢ": "S",
					"Ｓ": "S",
					"ẞ": "S",
					"Ś": "S",
					"Ṥ": "S",
					"Ŝ": "S",
					"Ṡ": "S",
					"Š": "S",
					"Ṧ": "S",
					"Ṣ": "S",
					"Ṩ": "S",
					"Ș": "S",
					"Ş": "S",
					"Ȿ": "S",
					"Ꞩ": "S",
					"Ꞅ": "S",
					"Ⓣ": "T",
					"Ｔ": "T",
					"Ṫ": "T",
					"Ť": "T",
					"Ṭ": "T",
					"Ț": "T",
					"Ţ": "T",
					"Ṱ": "T",
					"Ṯ": "T",
					"Ŧ": "T",
					"Ƭ": "T",
					"Ʈ": "T",
					"Ⱦ": "T",
					"Ꞇ": "T",
					"Ꜩ": "TZ",
					"Ⓤ": "U",
					"Ｕ": "U",
					"Ù": "U",
					"Ú": "U",
					"Û": "U",
					"Ũ": "U",
					"Ṹ": "U",
					"Ū": "U",
					"Ṻ": "U",
					"Ŭ": "U",
					"Ü": "U",
					"Ǜ": "U",
					"Ǘ": "U",
					"Ǖ": "U",
					"Ǚ": "U",
					"Ủ": "U",
					"Ů": "U",
					"Ű": "U",
					"Ǔ": "U",
					"Ȕ": "U",
					"Ȗ": "U",
					"Ư": "U",
					"Ừ": "U",
					"Ứ": "U",
					"Ữ": "U",
					"Ử": "U",
					"Ự": "U",
					"Ụ": "U",
					"Ṳ": "U",
					"Ų": "U",
					"Ṷ": "U",
					"Ṵ": "U",
					"Ʉ": "U",
					"Ⓥ": "V",
					"Ｖ": "V",
					"Ṽ": "V",
					"Ṿ": "V",
					"Ʋ": "V",
					"Ꝟ": "V",
					"Ʌ": "V",
					"Ꝡ": "VY",
					"Ⓦ": "W",
					"Ｗ": "W",
					"Ẁ": "W",
					"Ẃ": "W",
					"Ŵ": "W",
					"Ẇ": "W",
					"Ẅ": "W",
					"Ẉ": "W",
					"Ⱳ": "W",
					"Ⓧ": "X",
					"Ｘ": "X",
					"Ẋ": "X",
					"Ẍ": "X",
					"Ⓨ": "Y",
					"Ｙ": "Y",
					"Ỳ": "Y",
					"Ý": "Y",
					"Ŷ": "Y",
					"Ỹ": "Y",
					"Ȳ": "Y",
					"Ẏ": "Y",
					"Ÿ": "Y",
					"Ỷ": "Y",
					"Ỵ": "Y",
					"Ƴ": "Y",
					"Ɏ": "Y",
					"Ỿ": "Y",
					"Ⓩ": "Z",
					"Ｚ": "Z",
					"Ź": "Z",
					"Ẑ": "Z",
					"Ż": "Z",
					"Ž": "Z",
					"Ẓ": "Z",
					"Ẕ": "Z",
					"Ƶ": "Z",
					"Ȥ": "Z",
					"Ɀ": "Z",
					"Ⱬ": "Z",
					"Ꝣ": "Z",
					"ⓐ": "a",
					"ａ": "a",
					"ẚ": "a",
					"à": "a",
					"á": "a",
					"â": "a",
					"ầ": "a",
					"ấ": "a",
					"ẫ": "a",
					"ẩ": "a",
					"ã": "a",
					"ā": "a",
					"ă": "a",
					"ằ": "a",
					"ắ": "a",
					"ẵ": "a",
					"ẳ": "a",
					"ȧ": "a",
					"ǡ": "a",
					"ä": "a",
					"ǟ": "a",
					"ả": "a",
					"å": "a",
					"ǻ": "a",
					"ǎ": "a",
					"ȁ": "a",
					"ȃ": "a",
					"ạ": "a",
					"ậ": "a",
					"ặ": "a",
					"ḁ": "a",
					"ą": "a",
					"ⱥ": "a",
					"ɐ": "a",
					"ꜳ": "aa",
					"æ": "ae",
					"ǽ": "ae",
					"ǣ": "ae",
					"ꜵ": "ao",
					"ꜷ": "au",
					"ꜹ": "av",
					"ꜻ": "av",
					"ꜽ": "ay",
					"ⓑ": "b",
					"ｂ": "b",
					"ḃ": "b",
					"ḅ": "b",
					"ḇ": "b",
					"ƀ": "b",
					"ƃ": "b",
					"ɓ": "b",
					"ⓒ": "c",
					"ｃ": "c",
					"ć": "c",
					"ĉ": "c",
					"ċ": "c",
					"č": "c",
					"ç": "c",
					"ḉ": "c",
					"ƈ": "c",
					"ȼ": "c",
					"ꜿ": "c",
					"ↄ": "c",
					"ⓓ": "d",
					"ｄ": "d",
					"ḋ": "d",
					"ď": "d",
					"ḍ": "d",
					"ḑ": "d",
					"ḓ": "d",
					"ḏ": "d",
					"đ": "d",
					"ƌ": "d",
					"ɖ": "d",
					"ɗ": "d",
					"ꝺ": "d",
					"ǳ": "dz",
					"ǆ": "dz",
					"ⓔ": "e",
					"ｅ": "e",
					"è": "e",
					"é": "e",
					"ê": "e",
					"ề": "e",
					"ế": "e",
					"ễ": "e",
					"ể": "e",
					"ẽ": "e",
					"ē": "e",
					"ḕ": "e",
					"ḗ": "e",
					"ĕ": "e",
					"ė": "e",
					"ë": "e",
					"ẻ": "e",
					"ě": "e",
					"ȅ": "e",
					"ȇ": "e",
					"ẹ": "e",
					"ệ": "e",
					"ȩ": "e",
					"ḝ": "e",
					"ę": "e",
					"ḙ": "e",
					"ḛ": "e",
					"ɇ": "e",
					"ɛ": "e",
					"ǝ": "e",
					"ⓕ": "f",
					"ｆ": "f",
					"ḟ": "f",
					"ƒ": "f",
					"ꝼ": "f",
					"ⓖ": "g",
					"ｇ": "g",
					"ǵ": "g",
					"ĝ": "g",
					"ḡ": "g",
					"ğ": "g",
					"ġ": "g",
					"ǧ": "g",
					"ģ": "g",
					"ǥ": "g",
					"ɠ": "g",
					"ꞡ": "g",
					"ᵹ": "g",
					"ꝿ": "g",
					"ⓗ": "h",
					"ｈ": "h",
					"ĥ": "h",
					"ḣ": "h",
					"ḧ": "h",
					"ȟ": "h",
					"ḥ": "h",
					"ḩ": "h",
					"ḫ": "h",
					"ẖ": "h",
					"ħ": "h",
					"ⱨ": "h",
					"ⱶ": "h",
					"ɥ": "h",
					"ƕ": "hv",
					"ⓘ": "i",
					"ｉ": "i",
					"ì": "i",
					"í": "i",
					"î": "i",
					"ĩ": "i",
					"ī": "i",
					"ĭ": "i",
					"ï": "i",
					"ḯ": "i",
					"ỉ": "i",
					"ǐ": "i",
					"ȉ": "i",
					"ȋ": "i",
					"ị": "i",
					"į": "i",
					"ḭ": "i",
					"ɨ": "i",
					"ı": "i",
					"ⓙ": "j",
					"ｊ": "j",
					"ĵ": "j",
					"ǰ": "j",
					"ɉ": "j",
					"ⓚ": "k",
					"ｋ": "k",
					"ḱ": "k",
					"ǩ": "k",
					"ḳ": "k",
					"ķ": "k",
					"ḵ": "k",
					"ƙ": "k",
					"ⱪ": "k",
					"ꝁ": "k",
					"ꝃ": "k",
					"ꝅ": "k",
					"ꞣ": "k",
					"ⓛ": "l",
					"ｌ": "l",
					"ŀ": "l",
					"ĺ": "l",
					"ľ": "l",
					"ḷ": "l",
					"ḹ": "l",
					"ļ": "l",
					"ḽ": "l",
					"ḻ": "l",
					"ſ": "l",
					"ł": "l",
					"ƚ": "l",
					"ɫ": "l",
					"ⱡ": "l",
					"ꝉ": "l",
					"ꞁ": "l",
					"ꝇ": "l",
					"ǉ": "lj",
					"ⓜ": "m",
					"ｍ": "m",
					"ḿ": "m",
					"ṁ": "m",
					"ṃ": "m",
					"ɱ": "m",
					"ɯ": "m",
					"ⓝ": "n",
					"ｎ": "n",
					"ǹ": "n",
					"ń": "n",
					"ñ": "n",
					"ṅ": "n",
					"ň": "n",
					"ṇ": "n",
					"ņ": "n",
					"ṋ": "n",
					"ṉ": "n",
					"ƞ": "n",
					"ɲ": "n",
					"ŉ": "n",
					"ꞑ": "n",
					"ꞥ": "n",
					"ǌ": "nj",
					"ⓞ": "o",
					"ｏ": "o",
					"ò": "o",
					"ó": "o",
					"ô": "o",
					"ồ": "o",
					"ố": "o",
					"ỗ": "o",
					"ổ": "o",
					"õ": "o",
					"ṍ": "o",
					"ȭ": "o",
					"ṏ": "o",
					"ō": "o",
					"ṑ": "o",
					"ṓ": "o",
					"ŏ": "o",
					"ȯ": "o",
					"ȱ": "o",
					"ö": "o",
					"ȫ": "o",
					"ỏ": "o",
					"ő": "o",
					"ǒ": "o",
					"ȍ": "o",
					"ȏ": "o",
					"ơ": "o",
					"ờ": "o",
					"ớ": "o",
					"ỡ": "o",
					"ở": "o",
					"ợ": "o",
					"ọ": "o",
					"ộ": "o",
					"ǫ": "o",
					"ǭ": "o",
					"ø": "o",
					"ǿ": "o",
					"ɔ": "o",
					"ꝋ": "o",
					"ꝍ": "o",
					"ɵ": "o",
					"ƣ": "oi",
					"ȣ": "ou",
					"ꝏ": "oo",
					"ⓟ": "p",
					"ｐ": "p",
					"ṕ": "p",
					"ṗ": "p",
					"ƥ": "p",
					"ᵽ": "p",
					"ꝑ": "p",
					"ꝓ": "p",
					"ꝕ": "p",
					"ⓠ": "q",
					"ｑ": "q",
					"ɋ": "q",
					"ꝗ": "q",
					"ꝙ": "q",
					"ⓡ": "r",
					"ｒ": "r",
					"ŕ": "r",
					"ṙ": "r",
					"ř": "r",
					"ȑ": "r",
					"ȓ": "r",
					"ṛ": "r",
					"ṝ": "r",
					"ŗ": "r",
					"ṟ": "r",
					"ɍ": "r",
					"ɽ": "r",
					"ꝛ": "r",
					"ꞧ": "r",
					"ꞃ": "r",
					"ⓢ": "s",
					"ｓ": "s",
					"ß": "s",
					"ś": "s",
					"ṥ": "s",
					"ŝ": "s",
					"ṡ": "s",
					"š": "s",
					"ṧ": "s",
					"ṣ": "s",
					"ṩ": "s",
					"ș": "s",
					"ş": "s",
					"ȿ": "s",
					"ꞩ": "s",
					"ꞅ": "s",
					"ẛ": "s",
					"ⓣ": "t",
					"ｔ": "t",
					"ṫ": "t",
					"ẗ": "t",
					"ť": "t",
					"ṭ": "t",
					"ț": "t",
					"ţ": "t",
					"ṱ": "t",
					"ṯ": "t",
					"ŧ": "t",
					"ƭ": "t",
					"ʈ": "t",
					"ⱦ": "t",
					"ꞇ": "t",
					"ꜩ": "tz",
					"ⓤ": "u",
					"ｕ": "u",
					"ù": "u",
					"ú": "u",
					"û": "u",
					"ũ": "u",
					"ṹ": "u",
					"ū": "u",
					"ṻ": "u",
					"ŭ": "u",
					"ü": "u",
					"ǜ": "u",
					"ǘ": "u",
					"ǖ": "u",
					"ǚ": "u",
					"ủ": "u",
					"ů": "u",
					"ű": "u",
					"ǔ": "u",
					"ȕ": "u",
					"ȗ": "u",
					"ư": "u",
					"ừ": "u",
					"ứ": "u",
					"ữ": "u",
					"ử": "u",
					"ự": "u",
					"ụ": "u",
					"ṳ": "u",
					"ų": "u",
					"ṷ": "u",
					"ṵ": "u",
					"ʉ": "u",
					"ⓥ": "v",
					"ｖ": "v",
					"ṽ": "v",
					"ṿ": "v",
					"ʋ": "v",
					"ꝟ": "v",
					"ʌ": "v",
					"ꝡ": "vy",
					"ⓦ": "w",
					"ｗ": "w",
					"ẁ": "w",
					"ẃ": "w",
					"ŵ": "w",
					"ẇ": "w",
					"ẅ": "w",
					"ẘ": "w",
					"ẉ": "w",
					"ⱳ": "w",
					"ⓧ": "x",
					"ｘ": "x",
					"ẋ": "x",
					"ẍ": "x",
					"ⓨ": "y",
					"ｙ": "y",
					"ỳ": "y",
					"ý": "y",
					"ŷ": "y",
					"ỹ": "y",
					"ȳ": "y",
					"ẏ": "y",
					"ÿ": "y",
					"ỷ": "y",
					"ẙ": "y",
					"ỵ": "y",
					"ƴ": "y",
					"ɏ": "y",
					"ỿ": "y",
					"ⓩ": "z",
					"ｚ": "z",
					"ź": "z",
					"ẑ": "z",
					"ż": "z",
					"ž": "z",
					"ẓ": "z",
					"ẕ": "z",
					"ƶ": "z",
					"ȥ": "z",
					"ɀ": "z",
					"ⱬ": "z",
					"ꝣ": "z",
					"Ά": "Α",
					"Έ": "Ε",
					"Ή": "Η",
					"Ί": "Ι",
					"Ϊ": "Ι",
					"Ό": "Ο",
					"Ύ": "Υ",
					"Ϋ": "Υ",
					"Ώ": "Ω",
					"ά": "α",
					"έ": "ε",
					"ή": "η",
					"ί": "ι",
					"ϊ": "ι",
					"ΐ": "ι",
					"ό": "ο",
					"ύ": "υ",
					"ϋ": "υ",
					"ΰ": "υ",
					"ω": "ω",
					"ς": "σ"
				};
				return e
			}), t.define("select2/data/base", ["../utils"], function(e) {
				function t(e, n) {
					t.__super__.constructor.call(this)
				}
				return e.Extend(t, e.Observable), t.prototype.current = function(e) {
					throw new Error("The `current` method must be defined in child classes.")
				}, t.prototype.query = function(e, t) {
					throw new Error("The `query` method must be defined in child classes.")
				}, t.prototype.bind = function(e, t) {}, t.prototype.destroy = function() {}, t.prototype.generateResultId = function(t, n) {
					var r = t.id + "-result-";
					return r += e.generateChars(4), n.id != null ? r += "-" + n.id.toString() : r += "-" + e.generateChars(4), r
				}, t
			}), t.define("select2/data/select", ["./base", "../utils", "jquery"], function(e, t, n) {
				function r(e, t) {
					this.$element = e, this.options = t, r.__super__.constructor.call(this)
				}
				return t.Extend(r, e), r.prototype.current = function(e) {
					var t = [],
						r = this;
					this.$element.find(":selected").each(function() {
						var e = n(this),
							i = r.item(e);
						t.push(i)
					}), e(t)
				}, r.prototype.select = function(e) {
					var t = this;
					e.selected = !0;
					if (n(e.element).is("option")) {
						e.element.selected = !0, this.$element.trigger("change");
						return
					}
					if (this.$element.prop("multiple")) this.current(function(r) {
						var i = [];
						e = [e], e.push.apply(e, r);
						for (var s = 0; s < e.length; s++) {
							var o = e[s].id;
							n.inArray(o, i) === -1 && i.push(o)
						}
						t.$element.val(i), t.$element.trigger("change")
					});
					else {
						var r = e.id;
						this.$element.val(r), this.$element.trigger("change")
					}
				}, r.prototype.unselect = function(e) {
					var t = this;
					if (!this.$element.prop("multiple")) return;
					e.selected = !1;
					if (n(e.element).is("option")) {
						e.element.selected = !1, this.$element.trigger("change");
						return
					}
					this.current(function(r) {
						var i = [];
						for (var s = 0; s < r.length; s++) {
							var o = r[s].id;
							o !== e.id && n.inArray(o, i) === -1 && i.push(o)
						}
						t.$element.val(i), t.$element.trigger("change")
					})
				}, r.prototype.bind = function(e, t) {
					var n = this;
					this.container = e, e.on("select", function(e) {
						n.select(e.data)
					}), e.on("unselect", function(e) {
						n.unselect(e.data)
					})
				}, r.prototype.destroy = function() {
					this.$element.find("*").each(function() {
						n.removeData(this, "data")
					})
				}, r.prototype.query = function(e, t) {
					var r = [],
						i = this,
						s = this.$element.children();
					s.each(function() {
						var t = n(this);
						if (!t.is("option") && !t.is("optgroup")) return;
						var s = i.item(t),
							o = i.matches(e, s);
						o !== null && r.push(o)
					}), t({
						results: r
					})
				}, r.prototype.addOptions = function(e) {
					t.appendMany(this.$element, e)
				}, r.prototype.option = function(e) {
					var t;
					e.children ? (t = document.createElement("optgroup"), t.label = e.text) : (t = document.createElement("option"), t.textContent !== undefined ? t.textContent = e.text : t.innerText = e.text), e.id && (t.value = e.id), e.disabled && (t.disabled = !0), e.selected && (t.selected = !0), e.title && (t.title = e.title);
					var r = n(t),
						i = this._normalizeItem(e);
					return i.element = t, n.data(t, "data", i), r
				}, r.prototype.item = function(e) {
					var t = {};
					t = n.data(e[0], "data");
					if (t != null) return t;
					if (e.is("option")) t = {
						id: e.val(),
						text: e.text(),
						disabled: e.prop("disabled"),
						selected: e.prop("selected"),
						title: e.prop("title")
					};
					else if (e.is("optgroup")) {
						t = {
							text: e.prop("label"),
							children: [],
							title: e.prop("title")
						};
						var r = e.children("option"),
							i = [];
						for (var s = 0; s < r.length; s++) {
							var o = n(r[s]),
								u = this.item(o);
							i.push(u)
						}
						t.children = i
					}
					return t = this._normalizeItem(t), t.element = e[0], n.data(e[0], "data", t), t
				}, r.prototype._normalizeItem = function(e) {
					n.isPlainObject(e) || (e = {
						id: e,
						text: e
					}), e = n.extend({}, {
						text: ""
					}, e);
					var t = {
						selected: !1,
						disabled: !1
					};
					return e.id != null && (e.id = e.id.toString()), e.text != null && (e.text = e.text.toString()), e._resultId == null && e.id && this.container != null && (e._resultId = this.generateResultId(this.container, e)), n.extend({}, t, e)
				}, r.prototype.matches = function(e, t) {
					var n = this.options.get("matcher");
					return n(e, t)
				}, r
			}), t.define("select2/data/array", ["./select", "../utils", "jquery"], function(e, t, n) {
				function r(e, t) {
					var n = t.get("data") || [];
					r.__super__.constructor.call(this, e, t), this.addOptions(this.convertToOptions(n))
				}
				return t.Extend(r, e), r.prototype.select = function(e) {
					var t = this.$element.find("option").filter(function(t, n) {
						return n.value == e.id.toString()
					});
					t.length === 0 && (t = this.option(e), this.addOptions(t)), r.__super__.select.call(this, e)
				}, r.prototype.convertToOptions = function(e) {
					function u(e) {
						return function() {
							return n(this).val() == e.id
						}
					}
					var r = this,
						i = this.$element.find("option"),
						s = i.map(function() {
							return r.item(n(this)).id
						}).get(),
						o = [];
					for (var a = 0; a < e.length; a++) {
						var f = this._normalizeItem(e[a]);
						if (n.inArray(f.id, s) >= 0) {
							var l = i.filter(u(f)),
								c = this.item(l),
								h = n.extend(!0, {}, f, c),
								p = this.option(h);
							l.replaceWith(p);
							continue
						}
						var d = this.option(f);
						if (f.children) {
							var v = this.convertToOptions(f.children);
							t.appendMany(d, v)
						}
						o.push(d)
					}
					return o
				}, r
			}), t.define("select2/data/ajax", ["./array", "../utils", "jquery"], function(e, t, n) {
				function r(e, t) {
					this.ajaxOptions = this._applyDefaults(t.get("ajax")), this.ajaxOptions.processResults != null && (this.processResults = this.ajaxOptions.processResults), r.__super__.constructor.call(this, e, t)
				}
				return t.Extend(r, e), r.prototype._applyDefaults = function(e) {
					var t = {
						data: function(e) {
							return n.extend({}, e, {
								q: e.term
							})
						},
						transport: function(e, t, r) {
							var i = n.ajax(e);
							return i.then(t), i.fail(r), i
						}
					};
					return n.extend({}, t, e, !0)
				}, r.prototype.processResults = function(e) {
					return e
				}, r.prototype.query = function(e, t) {
					function o() {
						var r = s.transport(s, function(r) {
							var s = i.processResults(r, e);
							i.options.get("debug") && window.console && console.error && (!s || !s.results || !n.isArray(s.results)) && console.error("Select2: The AJAX results did not return an array in the `results` key of the response."), t(s)
						}, function() {
							i.trigger("results:message", {
								message: "errorLoading"
							})
						});
						i._request = r
					}
					var r = [],
						i = this;
					this._request != null && (n.isFunction(this._request.abort) && this._request.abort(), this._request = null);
					var s = n.extend({
						type: "GET"
					}, this.ajaxOptions);
					typeof s.url == "function" && (s.url = s.url.call(this.$element, e)), typeof s.data == "function" && (s.data = s.data.call(this.$element, e)), this.ajaxOptions.delay && e.term !== "" ? (this._queryTimeout && window.clearTimeout(this._queryTimeout), this._queryTimeout = window.setTimeout(o, this.ajaxOptions.delay)) : o()
				}, r
			}), t.define("select2/data/tags", ["jquery"], function(e) {
				function t(t, n, r) {
					var i = r.get("tags"),
						s = r.get("createTag");
					s !== undefined && (this.createTag = s);
					var o = r.get("insertTag");
					o !== undefined && (this.insertTag = o), t.call(this, n, r);
					if (e.isArray(i)) for (var u = 0; u < i.length; u++) {
						var a = i[u],
							f = this._normalizeItem(a),
							l = this.option(f);
						this.$element.append(l)
					}
				}
				return t.prototype.query = function(e, t, n) {
					function i(e, s) {
						var o = e.results;
						for (var u = 0; u < o.length; u++) {
							var a = o[u],
								f = a.children != null && !i({
									results: a.children
								}, !0),
								l = a.text === t.term;
							if (l || f) {
								if (s) return !1;
								e.data = o, n(e);
								return
							}
						}
						if (s) return !0;
						var c = r.createTag(t);
						if (c != null) {
							var h = r.option(c);
							h.attr("data-select2-tag", !0), r.addOptions([h]), r.insertTag(o, c)
						}
						e.results = o, n(e)
					}
					var r = this;
					this._removeOldTags();
					if (t.term == null || t.page != null) {
						e.call(this, t, n);
						return
					}
					e.call(this, t, i)
				}, t.prototype.createTag = function(t, n) {
					var r = e.trim(n.term);
					return r === "" ? null : {
						id: r,
						text: r
					}
				}, t.prototype.insertTag = function(e, t, n) {
					t.unshift(n)
				}, t.prototype._removeOldTags = function(t) {
					var n = this._lastTag,
						r = this.$element.find("option[data-select2-tag]");
					r.each(function() {
						if (this.selected) return;
						e(this).remove()
					})
				}, t
			}), t.define("select2/data/tokenizer", ["jquery"], function(e) {
				function t(e, t, n) {
					var r = n.get("tokenizer");
					r !== undefined && (this.tokenizer = r), e.call(this, t, n)
				}
				return t.prototype.bind = function(e, t, n) {
					e.call(this, t, n), this.$search = t.dropdown.$search || t.selection.$search || n.find(".select2-search__field")
				}, t.prototype.query = function(e, t, n) {
					function i(e) {
						r.trigger("select", {
							data: e
						})
					}
					var r = this;
					t.term = t.term || "";
					var s = this.tokenizer(t, this.options, i);
					s.term !== t.term && (this.$search.length && (this.$search.val(s.term), this.$search.focus()), t.term = s.term), e.call(this, t, n)
				}, t.prototype.tokenizer = function(t, n, r, i) {
					var s = r.get("tokenSeparators") || [],
						o = n.term,
						u = 0,
						a = this.createTag ||
					function(e) {
						return {
							id: e.term,
							text: e.term
						}
					};
					while (u < o.length) {
						var f = o[u];
						if (e.inArray(f, s) === -1) {
							u++;
							continue
						}
						var l = o.substr(0, u),
							c = e.extend({}, n, {
								term: l
							}),
							h = a(c);
						if (h == null) {
							u++;
							continue
						}
						i(h), o = o.substr(u + 1) || "", u = 0
					}
					return {
						term: o
					}
				}, t
			}), t.define("select2/data/minimumInputLength", [], function() {
				function e(e, t, n) {
					this.minimumInputLength = n.get("minimumInputLength"), e.call(this, t, n)
				}
				return e.prototype.query = function(e, t, n) {
					t.term = t.term || "";
					if (t.term.length < this.minimumInputLength) {
						this.trigger("results:message", {
							message: "inputTooShort",
							args: {
								minimum: this.minimumInputLength,
								input: t.term,
								params: t
							}
						});
						return
					}
					e.call(this, t, n)
				}, e
			}), t.define("select2/data/maximumInputLength", [], function() {
				function e(e, t, n) {
					this.maximumInputLength = n.get("maximumInputLength"), e.call(this, t, n)
				}
				return e.prototype.query = function(e, t, n) {
					t.term = t.term || "";
					if (this.maximumInputLength > 0 && t.term.length > this.maximumInputLength) {
						this.trigger("results:message", {
							message: "inputTooLong",
							args: {
								maximum: this.maximumInputLength,
								input: t.term,
								params: t
							}
						});
						return
					}
					e.call(this, t, n)
				}, e
			}), t.define("select2/data/maximumSelectionLength", [], function() {
				function e(e, t, n) {
					this.maximumSelectionLength = n.get("maximumSelectionLength"), e.call(this, t, n)
				}
				return e.prototype.query = function(e, t, n) {
					var r = this;
					this.current(function(i) {
						var s = i != null ? i.length : 0;
						if (r.maximumSelectionLength > 0 && s >= r.maximumSelectionLength) {
							r.trigger("results:message", {
								message: "maximumSelected",
								args: {
									maximum: r.maximumSelectionLength
								}
							});
							return
						}
						e.call(r, t, n)
					})
				}, e
			}), t.define("select2/dropdown", ["jquery", "./utils"], function(e, t) {
				function n(e, t) {
					this.$element = e, this.options = t, n.__super__.constructor.call(this)
				}
				return t.Extend(n, t.Observable), n.prototype.render = function() {
					var t = e('<span class="select2-dropdown"><span class="select2-results"></span></span>');
					return t.attr("dir", this.options.get("dir")), this.$dropdown = t, t
				}, n.prototype.bind = function() {}, n.prototype.position = function(e, t) {}, n.prototype.destroy = function() {
					this.$dropdown.remove()
				}, n
			}), t.define("select2/dropdown/search", ["jquery", "../utils"], function(e, t) {
				function n() {}
				return n.prototype.render = function(t) {
					var n = t.call(this),
						r = e('<span class="select2-search select2-search--dropdown"><input class="select2-search__field" type="search" tabindex="-1" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" role="textbox" /></span>');
					return this.$searchContainer = r, this.$search = r.find("input"), n.prepend(r), n
				}, n.prototype.bind = function(t, n, r) {
					var i = this;
					t.call(this, n, r), this.$search.on("keydown", function(e) {
						i.trigger("keypress", e), i._keyUpPrevented = e.isDefaultPrevented()
					}), this.$search.on("input", function(t) {
						e(this).off("keyup")
					}), this.$search.on("keyup input", function(e) {
						i.handleSearch(e)
					}), n.on("open", function() {
						i.$search.attr("tabindex", 0), i.$search.focus(), window.setTimeout(function() {
							i.$search.focus()
						}, 0)
					}), n.on("close", function() {
						i.$search.attr("tabindex", -1), i.$search.val("")
					}), n.on("results:all", function(e) {
						if (e.query.term == null || e.query.term === "") {
							var t = i.showSearch(e);
							t ? i.$searchContainer.removeClass("select2-search--hide") : i.$searchContainer.addClass("select2-search--hide")
						}
					})
				}, n.prototype.handleSearch = function(e) {
					if (!this._keyUpPrevented) {
						var t = this.$search.val();
						this.trigger("query", {
							term: t
						})
					}
					this._keyUpPrevented = !1
				}, n.prototype.showSearch = function(e, t) {
					return !0
				}, n
			}), t.define("select2/dropdown/hidePlaceholder", [], function() {
				function e(e, t, n, r) {
					this.placeholder = this.normalizePlaceholder(n.get("placeholder")), e.call(this, t, n, r)
				}
				return e.prototype.append = function(e, t) {
					t.results = this.removePlaceholder(t.results), e.call(this, t)
				}, e.prototype.normalizePlaceholder = function(e, t) {
					return typeof t == "string" && (t = {
						id: "",
						text: t
					}), t
				}, e.prototype.removePlaceholder = function(e, t) {
					var n = t.slice(0);
					for (var r = t.length - 1; r >= 0; r--) {
						var i = t[r];
						this.placeholder.id === i.id && n.splice(r, 1)
					}
					return n
				}, e
			}), t.define("select2/dropdown/infiniteScroll", ["jquery"], function(e) {
				function t(e, t, n, r) {
					this.lastParams = {}, e.call(this, t, n, r), this.$loadingMore = this.createLoadingMore(), this.loading = !1
				}
				return t.prototype.append = function(e, t) {
					this.$loadingMore.remove(), this.loading = !1, e.call(this, t), this.showLoadingMore(t) && this.$results.append(this.$loadingMore)
				}, t.prototype.bind = function(t, n, r) {
					var i = this;
					t.call(this, n, r), n.on("query", function(e) {
						i.lastParams = e, i.loading = !0
					}), n.on("query:append", function(e) {
						i.lastParams = e, i.loading = !0
					}), this.$results.on("scroll", function() {
						var t = e.contains(document.documentElement, i.$loadingMore[0]);
						if (i.loading || !t) return;
						var n = i.$results.offset().top + i.$results.outerHeight(!1),
							r = i.$loadingMore.offset().top + i.$loadingMore.outerHeight(!1);
						n + 50 >= r && i.loadMore()
					})
				}, t.prototype.loadMore = function() {
					this.loading = !0;
					var t = e.extend({}, {
						page: 1
					}, this.lastParams);
					t.page++, this.trigger("query:append", t)
				}, t.prototype.showLoadingMore = function(e, t) {
					return t.pagination && t.pagination.more
				}, t.prototype.createLoadingMore = function() {
					var t = e('<li class="select2-results__option select2-results__option--load-more"role="treeitem" aria-disabled="true"></li>'),
						n = this.options.get("translations").get("loadingMore");
					return t.html(n(this.lastParams)), t
				}, t
			}), t.define("select2/dropdown/attachBody", ["jquery", "../utils"], function(e, t) {
				function n(t, n, r) {
					this.$dropdownParent = r.get("dropdownParent") || e(document.body), t.call(this, n, r)
				}
				return n.prototype.bind = function(e, t, n) {
					var r = this,
						i = !1;
					e.call(this, t, n), t.on("open", function() {
						r._showDropdown(), r._attachPositioningHandler(t), i || (i = !0, t.on("results:all", function() {
							r._positionDropdown(), r._resizeDropdown()
						}), t.on("results:append", function() {
							r._positionDropdown(), r._resizeDropdown()
						}))
					}), t.on("close", function() {
						r._hideDropdown(), r._detachPositioningHandler(t)
					}), this.$dropdownContainer.on("mousedown", function(e) {
						e.stopPropagation()
					})
				}, n.prototype.destroy = function(e) {
					e.call(this), this.$dropdownContainer.remove()
				}, n.prototype.position = function(e, t, n) {
					t.attr("class", n.attr("class")), t.removeClass("select2"), t.addClass("select2-container--open"), t.css({
						position: "absolute",
						top: -999999
					}), this.$container = n
				}, n.prototype.render = function(t) {
					var n = e("<span></span>"),
						r = t.call(this);
					return n.append(r), this.$dropdownContainer = n, n
				}, n.prototype._hideDropdown = function(e) {
					this.$dropdownContainer.detach()
				}, n.prototype._attachPositioningHandler = function(n, r) {
					var i = this,
						s = "scroll.select2." + r.id,
						o = "resize.select2." + r.id,
						u = "orientationchange.select2." + r.id,
						a = this.$container.parents().filter(t.hasScroll);
					a.each(function() {
						e(this).data("select2-scroll-position", {
							x: e(this).scrollLeft(),
							y: e(this).scrollTop()
						})
					}), a.on(s, function(t) {
						var n = e(this).data("select2-scroll-position");
						e(this).scrollTop(n.y)
					}), e(window).on(s + " " + o + " " + u, function(e) {
						i._positionDropdown(), i._resizeDropdown()
					})
				}, n.prototype._detachPositioningHandler = function(n, r) {
					var i = "scroll.select2." + r.id,
						s = "resize.select2." + r.id,
						o = "orientationchange.select2." + r.id,
						u = this.$container.parents().filter(t.hasScroll);
					u.off(i), e(window).off(i + " " + s + " " + o)
				}, n.prototype._positionDropdown = function() {
					var t = e(window),
						n = this.$dropdown.hasClass("select2-dropdown--above"),
						r = this.$dropdown.hasClass("select2-dropdown--below"),
						i = null,
						s = this.$container.offset();
					s.bottom = s.top + this.$container.outerHeight(!1);
					var o = {
						height: this.$container.outerHeight(!1)
					};
					o.top = s.top, o.bottom = s.top + o.height;
					var u = {
						height: this.$dropdown.outerHeight(!1)
					},
						a = {
							top: t.scrollTop(),
							bottom: t.scrollTop() + t.height()
						},
						f = a.top < s.top - u.height,
						l = a.bottom > s.bottom + u.height,
						c = {
							left: s.left,
							top: o.bottom
						},
						h = this.$dropdownParent;
					h.css("position") === "static" && (h = h.offsetParent());
					var p = h.offset();
					c.top -= p.top, c.left -= p.left, !n && !r && (i = "below"), !l && f && !n ? i = "above" : !f && l && n && (i = "below");
					if (i == "above" || n && i !== "below") c.top = o.top - u.height;
					i != null && (this.$dropdown.removeClass("select2-dropdown--below select2-dropdown--above").addClass("select2-dropdown--" + i), this.$container.removeClass("select2-container--below select2-container--above").addClass("select2-container--" + i)), this.$dropdownContainer.css(c)
				}, n.prototype._resizeDropdown = function() {
					var e = {
						width: this.$container.outerWidth(!1) + "px"
					};
					this.options.get("dropdownAutoWidth") && (e.minWidth = e.width, e.width = "auto"), this.$dropdown.css(e)
				}, n.prototype._showDropdown = function(e) {
					this.$dropdownContainer.appendTo(this.$dropdownParent), this._positionDropdown(), this._resizeDropdown()
				}, n
			}), t.define("select2/dropdown/minimumResultsForSearch", [], function() {
				function e(t) {
					var n = 0;
					for (var r = 0; r < t.length; r++) {
						var i = t[r];
						i.children ? n += e(i.children) : n++
					}
					return n
				}
				function t(e, t, n, r) {
					this.minimumResultsForSearch = n.get("minimumResultsForSearch"), this.minimumResultsForSearch < 0 && (this.minimumResultsForSearch = Infinity), e.call(this, t, n, r)
				}
				return t.prototype.showSearch = function(t, n) {
					return e(n.data.results) < this.minimumResultsForSearch ? !1 : t.call(this, n)
				}, t
			}), t.define("select2/dropdown/selectOnClose", [], function() {
				function e() {}
				return e.prototype.bind = function(e, t, n) {
					var r = this;
					e.call(this, t, n), t.on("close", function() {
						r._handleSelectOnClose()
					})
				}, e.prototype._handleSelectOnClose = function() {
					var e = this.getHighlightedResults();
					if (e.length < 1) return;
					var t = e.data("data");
					if (t.element != null && t.element.selected || t.element == null && t.selected) return;
					this.trigger("select", {
						data: t
					})
				}, e
			}), t.define("select2/dropdown/closeOnSelect", [], function() {
				function e() {}
				return e.prototype.bind = function(e, t, n) {
					var r = this;
					e.call(this, t, n), t.on("select", function(e) {
						r._selectTriggered(e)
					}), t.on("unselect", function(e) {
						r._selectTriggered(e)
					})
				}, e.prototype._selectTriggered = function(e, t) {
					var n = t.originalEvent;
					if (n && n.ctrlKey) return;
					this.trigger("close", {})
				}, e
			}), t.define("select2/i18n/en", [], function() {
				return {
					errorLoading: function() {
						return "The results could not be loaded."
					},
					inputTooLong: function(e) {
						var t = e.input.length - e.maximum,
							n = "Please delete " + t + " character";
						return t != 1 && (n += "s"), n
					},
					inputTooShort: function(e) {
						var t = e.minimum - e.input.length,
							n = "Please enter " + t + " or more characters";
						return n
					},
					loadingMore: function() {
						return "Loading more results…"
					},
					maximumSelected: function(e) {
						var t = "You can only select " + e.maximum + " item";
						return e.maximum != 1 && (t += "s"), t
					},
					noResults: function() {
						return "No results found"
					},
					searching: function() {
						return "Searching…"
					}
				}
			}), t.define("select2/defaults", ["jquery", "require", "./results", "./selection/single", "./selection/multiple", "./selection/placeholder", "./selection/allowClear", "./selection/search", "./selection/eventRelay", "./utils", "./translation", "./diacritics", "./data/select", "./data/array", "./data/ajax", "./data/tags", "./data/tokenizer", "./data/minimumInputLength", "./data/maximumInputLength", "./data/maximumSelectionLength", "./dropdown", "./dropdown/search", "./dropdown/hidePlaceholder", "./dropdown/infiniteScroll", "./dropdown/attachBody", "./dropdown/minimumResultsForSearch", "./dropdown/selectOnClose", "./dropdown/closeOnSelect", "./i18n/en"], function(e, t, n, r, i, s, o, u, a, f, l, c, h, p, d, v, m, g, y, b, w, E, S, x, T, N, C, k, L) {
				function A() {
					this.reset()
				}
				A.prototype.apply = function(c) {
					c = e.extend(!0, {}, this.defaults, c);
					if (c.dataAdapter == null) {
						c.ajax != null ? c.dataAdapter = d : c.data != null ? c.dataAdapter = p : c.dataAdapter = h, c.minimumInputLength > 0 && (c.dataAdapter = f.Decorate(c.dataAdapter, g)), c.maximumInputLength > 0 && (c.dataAdapter = f.Decorate(c.dataAdapter, y)), c.maximumSelectionLength > 0 && (c.dataAdapter = f.Decorate(c.dataAdapter, b)), c.tags && (c.dataAdapter = f.Decorate(c.dataAdapter, v));
						if (c.tokenSeparators != null || c.tokenizer != null) c.dataAdapter = f.Decorate(c.dataAdapter, m);
						if (c.query != null) {
							var L = t(c.amdBase + "compat/query");
							c.dataAdapter = f.Decorate(c.dataAdapter, L)
						}
						if (c.initSelection != null) {
							var A = t(c.amdBase + "compat/initSelection");
							c.dataAdapter = f.Decorate(c.dataAdapter, A)
						}
					}
					c.resultsAdapter == null && (c.resultsAdapter = n, c.ajax != null && (c.resultsAdapter = f.Decorate(c.resultsAdapter, x)), c.placeholder != null && (c.resultsAdapter = f.Decorate(c.resultsAdapter, S)), c.selectOnClose && (c.resultsAdapter = f.Decorate(c.resultsAdapter, C)));
					if (c.dropdownAdapter == null) {
						if (c.multiple) c.dropdownAdapter = w;
						else {
							var O = f.Decorate(w, E);
							c.dropdownAdapter = O
						}
						c.minimumResultsForSearch !== 0 && (c.dropdownAdapter = f.Decorate(c.dropdownAdapter, N)), c.closeOnSelect && (c.dropdownAdapter = f.Decorate(c.dropdownAdapter, k));
						if (c.dropdownCssClass != null || c.dropdownCss != null || c.adaptDropdownCssClass != null) {
							var M = t(c.amdBase + "compat/dropdownCss");
							c.dropdownAdapter = f.Decorate(c.dropdownAdapter, M)
						}
						c.dropdownAdapter = f.Decorate(c.dropdownAdapter, T)
					}
					if (c.selectionAdapter == null) {
						c.multiple ? c.selectionAdapter = i : c.selectionAdapter = r, c.placeholder != null && (c.selectionAdapter = f.Decorate(c.selectionAdapter, s)), c.allowClear && (c.selectionAdapter = f.Decorate(c.selectionAdapter, o)), c.multiple && (c.selectionAdapter = f.Decorate(c.selectionAdapter, u));
						if (c.containerCssClass != null || c.containerCss != null || c.adaptContainerCssClass != null) {
							var _ = t(c.amdBase + "compat/containerCss");
							c.selectionAdapter = f.Decorate(c.selectionAdapter, _)
						}
						c.selectionAdapter = f.Decorate(c.selectionAdapter, a)
					}
					if (typeof c.language == "string") if (c.language.indexOf("-") > 0) {
						var D = c.language.split("-"),
							P = D[0];
						c.language = [c.language, P]
					} else c.language = [c.language];
					if (e.isArray(c.language)) {
						var H = new l;
						c.language.push("en");
						var B = c.language;
						for (var j = 0; j < B.length; j++) {
							var F = B[j],
								I = {};
							try {
								I = l.loadPath(F)
							} catch (q) {
								try {
									F = this.defaults.amdLanguageBase + F, I = l.loadPath(F)
								} catch (R) {
									c.debug && window.console && console.warn && console.warn('Select2: The language file for "' + F + '" could not be ' + "automatically loaded. A fallback will be used instead.");
									continue
								}
							}
							H.extend(I)
						}
						c.translations = H
					} else {
						var U = l.loadPath(this.defaults.amdLanguageBase + "en"),
							z = new l(c.language);
						z.extend(U), c.translations = z
					}
					return c
				}, A.prototype.reset = function() {
					function t(e) {
						function t(e) {
							return c[e] || e
						}
						return e.replace(/[^\u0000-\u007E]/g, t)
					}
					function n(r, i) {
						if (e.trim(r.term) === "") return i;
						if (i.children && i.children.length > 0) {
							var s = e.extend(!0, {}, i);
							for (var o = i.children.length - 1; o >= 0; o--) {
								var u = i.children[o],
									a = n(r, u);
								a == null && s.children.splice(o, 1)
							}
							return s.children.length > 0 ? s : n(r, s)
						}
						var f = t(i.text).toUpperCase(),
							l = t(r.term).toUpperCase();
						return f.indexOf(l) > -1 ? i : null
					}
					this.defaults = {
						amdBase: "./",
						amdLanguageBase: "./i18n/",
						closeOnSelect: !0,
						debug: !1,
						dropdownAutoWidth: !1,
						escapeMarkup: f.escapeMarkup,
						language: L,
						matcher: n,
						minimumInputLength: 0,
						maximumInputLength: 0,
						maximumSelectionLength: 0,
						minimumResultsForSearch: 0,
						selectOnClose: !1,
						sorter: function(e) {
							return e
						},
						templateResult: function(e) {
							return e.text
						},
						templateSelection: function(e) {
							return e.text
						},
						theme: "default",
						width: "resolve"
					}
				}, A.prototype.set = function(t, n) {
					var r = e.camelCase(t),
						i = {};
					i[r] = n;
					var s = f._convertData(i);
					e.extend(this.defaults, s)
				};
				var O = new A;
				return O
			}), t.define("select2/options", ["require", "jquery", "./defaults", "./utils"], function(e, t, n, r) {
				function i(t, i) {
					this.options = t, i != null && this.fromElement(i), this.options = n.apply(this.options);
					if (i && i.is("input")) {
						var s = e(this.get("amdBase") + "compat/inputData");
						this.options.dataAdapter = r.Decorate(this.options.dataAdapter, s)
					}
				}
				return i.prototype.fromElement = function(e) {
					var n = ["select2"];
					this.options.multiple == null && (this.options.multiple = e.prop("multiple")), this.options.disabled == null && (this.options.disabled = e.prop("disabled")), this.options.language == null && (e.prop("lang") ? this.options.language = e.prop("lang").toLowerCase() : e.closest("[lang]").prop("lang") && (this.options.language = e.closest("[lang]").prop("lang"))), this.options.dir == null && (e.prop("dir") ? this.options.dir = e.prop("dir") : e.closest("[dir]").prop("dir") ? this.options.dir = e.closest("[dir]").prop("dir") : this.options.dir = "ltr"), e.prop("disabled", this.options.disabled), e.prop("multiple", this.options.multiple), e.data("select2Tags") && (this.options.debug && window.console && console.warn && console.warn('Select2: The `data-select2-tags` attribute has been changed to use the `data-data` and `data-tags="true"` attributes and will be removed in future versions of Select2.'), e.data("data", e.data("select2Tags")), e.data("tags", !0)), e.data("ajaxUrl") && (this.options.debug && window.console && console.warn && console.warn("Select2: The `data-ajax-url` attribute has been changed to `data-ajax--url` and support for the old attribute will be removed in future versions of Select2."), e.attr("ajax--url", e.data("ajaxUrl")), e.data("ajax--url", e.data("ajaxUrl")));
					var i = {};
					t.fn.jquery && t.fn.jquery.substr(0, 2) == "1." && e[0].dataset ? i = t.extend(!0, {}, e[0].dataset, e.data()) : i = e.data();
					var s = t.extend(!0, {}, i);
					s = r._convertData(s);
					for (var o in s) {
						if (t.inArray(o, n) > -1) continue;
						t.isPlainObject(this.options[o]) ? t.extend(this.options[o], s[o]) : this.options[o] = s[o]
					}
					return this
				}, i.prototype.get = function(e) {
					return this.options[e]
				}, i.prototype.set = function(e, t) {
					this.options[e] = t
				}, i
			}), t.define("select2/core", ["jquery", "./options", "./utils", "./keys"], function(e, t, n, r) {
				var i = function(e, n) {
						e.data("select2") != null && e.data("select2").destroy(), this.$element = e, this.id = this._generateId(e), n = n || {}, this.options = new t(n, e), i.__super__.constructor.call(this);
						var r = e.attr("tabindex") || 0;
						e.data("old-tabindex", r), e.attr("tabindex", "-1");
						var s = this.options.get("dataAdapter");
						this.dataAdapter = new s(e, this.options);
						var o = this.render();
						this._placeContainer(o);
						var u = this.options.get("selectionAdapter");
						this.selection = new u(e, this.options), this.$selection = this.selection.render(), this.selection.position(this.$selection, o);
						var a = this.options.get("dropdownAdapter");
						this.dropdown = new a(e, this.options), this.$dropdown = this.dropdown.render(), this.dropdown.position(this.$dropdown, o);
						var f = this.options.get("resultsAdapter");
						this.results = new f(e, this.options, this.dataAdapter), this.$results = this.results.render(), this.results.position(this.$results, this.$dropdown);
						var l = this;
						this._bindAdapters(), this._registerDomEvents(), this._registerDataEvents(), this._registerSelectionEvents(), this._registerDropdownEvents(), this._registerResultsEvents(), this._registerEvents(), this.dataAdapter.current(function(e) {
							l.trigger("selection:update", {
								data: e
							})
						}), e.addClass("select2-hidden-accessible"), e.attr("aria-hidden", "true"), this._syncAttributes(), e.data("select2", this)
					};
				return n.Extend(i, n.Observable), i.prototype._generateId = function(e) {
					var t = "";
					return e.attr("id") != null ? t = e.attr("id") : e.attr("name") != null ? t = e.attr("name") + "-" + n.generateChars(2) : t = n.generateChars(4), t = t.replace(/(:|\.|\[|\]|,)/g, ""), t = "select2-" + t, t
				}, i.prototype._placeContainer = function(e) {
					e.insertAfter(this.$element);
					var t = this._resolveWidth(this.$element, this.options.get("width"));
					t != null && e.css("width", t)
				}, i.prototype._resolveWidth = function(e, t) {
					var n = /^width:(([-+]?([0-9]*\.)?[0-9]+)(px|em|ex|%|in|cm|mm|pt|pc))/i;
					if (t == "resolve") {
						var r = this._resolveWidth(e, "style");
						return r != null ? r : this._resolveWidth(e, "element")
					}
					if (t == "element") {
						var i = e.outerWidth(!1);
						return i <= 0 ? "auto" : i + "px"
					}
					if (t == "style") {
						var s = e.attr("style");
						if (typeof s != "string") return null;
						var o = s.split(";");
						for (var u = 0, a = o.length; u < a; u += 1) {
							var f = o[u].replace(/\s/g, ""),
								l = f.match(n);
							if (l !== null && l.length >= 1) return l[1]
						}
						return null
					}
					return t
				}, i.prototype._bindAdapters = function() {
					this.dataAdapter.bind(this, this.$container), this.selection.bind(this, this.$container), this.dropdown.bind(this, this.$container), this.results.bind(this, this.$container)
				}, i.prototype._registerDomEvents = function() {
					var t = this;
					this.$element.on("change.select2", function() {
						t.dataAdapter.current(function(e) {
							t.trigger("selection:update", {
								data: e
							})
						})
					}), this._sync = n.bind(this._syncAttributes, this), this.$element[0].attachEvent && this.$element[0].attachEvent("onpropertychange", this._sync);
					var r = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;
					r != null ? (this._observer = new r(function(n) {
						e.each(n, t._sync)
					}), this._observer.observe(this.$element[0], {
						attributes: !0,
						subtree: !1
					})) : this.$element[0].addEventListener && this.$element[0].addEventListener("DOMAttrModified", t._sync, !1)
				}, i.prototype._registerDataEvents = function() {
					var e = this;
					this.dataAdapter.on("*", function(t, n) {
						e.trigger(t, n)
					})
				}, i.prototype._registerSelectionEvents = function() {
					var t = this,
						n = ["toggle", "focus"];
					this.selection.on("toggle", function() {
						t.toggleDropdown()
					}), this.selection.on("focus", function(e) {
						t.focus(e)
					}), this.selection.on("*", function(r, i) {
						if (e.inArray(r, n) !== -1) return;
						t.trigger(r, i)
					})
				}, i.prototype._registerDropdownEvents = function() {
					var e = this;
					this.dropdown.on("*", function(t, n) {
						e.trigger(t, n)
					})
				}, i.prototype._registerResultsEvents = function() {
					var e = this;
					this.results.on("*", function(t, n) {
						e.trigger(t, n)
					})
				}, i.prototype._registerEvents = function() {
					var e = this;
					this.on("open", function() {
						e.$container.addClass("select2-container--open")
					}), this.on("close", function() {
						e.$container.removeClass("select2-container--open")
					}), this.on("enable", function() {
						e.$container.removeClass("select2-container--disabled")
					}), this.on("disable", function() {
						e.$container.addClass("select2-container--disabled")
					}), this.on("blur", function() {
						e.$container.removeClass("select2-container--focus")
					}), this.on("query", function(t) {
						e.isOpen() || e.trigger("open", {}), this.dataAdapter.query(t, function(n) {
							e.trigger("results:all", {
								data: n,
								query: t
							})
						})
					}), this.on("query:append", function(t) {
						this.dataAdapter.query(t, function(n) {
							e.trigger("results:append", {
								data: n,
								query: t
							})
						})
					}), this.on("keypress", function(t) {
						var n = t.which;
						if (e.isOpen()) n === r.ESC || n === r.TAB || n === r.UP && t.altKey ? (e.close(), t.preventDefault()) : n === r.ENTER ? (e.trigger("results:select", {}), t.preventDefault()) : n === r.SPACE && t.ctrlKey ? (e.trigger("results:toggle", {}), t.preventDefault()) : n === r.UP ? (e.trigger("results:previous", {}), t.preventDefault()) : n === r.DOWN && (e.trigger("results:next", {}), t.preventDefault());
						else if (n === r.ENTER || n === r.SPACE || n === r.DOWN && t.altKey) e.open(), t.preventDefault()
					})
				}, i.prototype._syncAttributes = function() {
					this.options.set("disabled", this.$element.prop("disabled")), this.options.get("disabled") ? (this.isOpen() && this.close(), this.trigger("disable", {})) : this.trigger("enable", {})
				}, i.prototype.trigger = function(e, t) {
					var n = i.__super__.trigger,
						r = {
							open: "opening",
							close: "closing",
							select: "selecting",
							unselect: "unselecting"
						};
					t === undefined && (t = {});
					if (e in r) {
						var s = r[e],
							o = {
								prevented: !1,
								name: e,
								args: t
							};
						n.call(this, s, o);
						if (o.prevented) {
							t.prevented = !0;
							return
						}
					}
					n.call(this, e, t)
				}, i.prototype.toggleDropdown = function() {
					if (this.options.get("disabled")) return;
					this.isOpen() ? this.close() : this.open()
				}, i.prototype.open = function() {
					if (this.isOpen()) return;
					this.trigger("query", {})
				}, i.prototype.close = function() {
					if (!this.isOpen()) return;
					this.trigger("close", {})
				}, i.prototype.isOpen = function() {
					return this.$container.hasClass("select2-container--open")
				}, i.prototype.hasFocus = function() {
					return this.$container.hasClass("select2-container--focus")
				}, i.prototype.focus = function(e) {
					if (this.hasFocus()) return;
					this.$container.addClass("select2-container--focus"), this.trigger("focus", {})
				}, i.prototype.enable = function(e) {
					this.options.get("debug") && window.console && console.warn && console.warn('Select2: The `select2("enable")` method has been deprecated and will be removed in later Select2 versions. Use $element.prop("disabled") instead.');
					if (e == null || e.length === 0) e = [!0];
					var t = !e[0];
					this.$element.prop("disabled", t)
				}, i.prototype.data = function() {
					this.options.get("debug") && arguments.length > 0 && window.console && console.warn && console.warn('Select2: Data can no longer be set using `select2("data")`. You should consider setting the value instead using `$element.val()`.');
					var e = [];
					return this.dataAdapter.current(function(t) {
						e = t
					}), e
				}, i.prototype.val = function(t) {
					this.options.get("debug") && window.console && console.warn && console.warn('Select2: The `select2("val")` method has been deprecated and will be removed in later Select2 versions. Use $element.val() instead.');
					if (t == null || t.length === 0) return this.$element.val();
					var n = t[0];
					e.isArray(n) && (n = e.map(n, function(e) {
						return e.toString()
					})), this.$element.val(n).trigger("change")
				}, i.prototype.destroy = function() {
					this.$container.remove(), this.$element[0].detachEvent && this.$element[0].detachEvent("onpropertychange", this._sync), this._observer != null ? (this._observer.disconnect(), this._observer = null) : this.$element[0].removeEventListener && this.$element[0].removeEventListener("DOMAttrModified", this._sync, !1), this._sync = null, this.$element.off(".select2"), this.$element.attr("tabindex", this.$element.data("old-tabindex")), this.$element.removeClass("select2-hidden-accessible"), this.$element.attr("aria-hidden", "false"), this.$element.removeData("select2"), this.dataAdapter.destroy(), this.selection.destroy(), this.dropdown.destroy(), this.results.destroy(), this.dataAdapter = null, this.selection = null, this.dropdown = null, this.results = null
				}, i.prototype.render = function() {
					var t = e('<span class="select2 select2-container"><span class="selection"></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>');
					return t.attr("dir", this.options.get("dir")), this.$container = t, this.$container.addClass("select2-container--" + this.options.get("theme")), t.data("element", this.$element), t
				}, i
			}), t.define("jquery-mousewheel", ["jquery"], function(e) {
				return e
			}), t.define("jquery.select2", ["jquery", "jquery-mousewheel", "./select2/core", "./select2/defaults"], function(e, t, n, r) {
				if (e.fn.select2 == null) {
					var i = ["open", "close", "destroy"];
					e.fn.select2 = function(t) {
						t = t || {};
						if (typeof t == "object") return this.each(function() {
							var r = e.extend(!0, {}, t),
								i = new n(e(this), r)
						}), this;
						if (typeof t == "string") {
							var r;
							return this.each(function() {
								var n = e(this).data("select2");
								n == null && window.console && console.error && console.error("The select2('" + t + "') method was called on an " + "element that is not using Select2.");
								var i = Array.prototype.slice.call(arguments, 1);
								r = n[t].apply(n, i)
							}), e.inArray(t, i) > -1 ? this : r
						}
						throw new Error("Invalid arguments for Select2: " + t)
					}
				}
				return e.fn.select2.defaults == null && (e.fn.select2.defaults = r), n
			}), {
				define: t.define,
				require: t.require
			}
		}(),
		n = t.require("jquery.select2");
	return e.fn.select2.amd = t, n
}), function() {
	if (jQuery && jQuery.fn && jQuery.fn.select2 && jQuery.fn.select2.amd) var e = jQuery.fn.select2.amd;
	return e.define("select2/i18n/zh-CN", [], function() {
		return {
			errorLoading: function() {
				return "无法载入结果。"
			},
			inputTooLong: function(e) {
				var t = e.input.length - e.maximum,
					n = "请删除" + t + "个字符";
				return n
			},
			inputTooShort: function(e) {
				var t = e.minimum - e.input.length,
					n = "请再输入至少" + t + "个字符";
				return n
			},
			loadingMore: function() {
				return "载入更多结果…"
			},
			maximumSelected: function(e) {
				var t = "最多只能选择" + e.maximum + "个项目";
				return t
			},
			noResults: function() {
				return "未找到结果"
			},
			searching: function() {
				return "搜索中…"
			}
		}
	}), {
		define: e.define,
		require: e.require
	}
}(), function() {
	if (jQuery && jQuery.fn && jQuery.fn.select2 && jQuery.fn.select2.amd) var e = jQuery.fn.select2.amd;
	return e.define("select2/i18n/zh-TW", [], function() {
		return {
			inputTooLong: function(e) {
				var t = e.input.length - e.maximum,
					n = "請刪掉" + t + "個字元";
				return n
			},
			inputTooShort: function(e) {
				var t = e.minimum - e.input.length,
					n = "請再輸入" + t + "個字元";
				return n
			},
			loadingMore: function() {
				return "載入中…"
			},
			maximumSelected: function(e) {
				var t = "你只能選擇最多" + e.maximum + "項";
				return t
			},
			noResults: function() {
				return "沒有找到相符的項目"
			},
			searching: function() {
				return "搜尋中…"
			}
		}
	}), {
		define: e.define,
		require: e.require
	}
}(), function() {
	window.GD || (window.GD = {}), GD.initWeixinLogin = function(e) {
		if (e.length > 0 && typeof WxLogin != "undefined" && WxLogin !== null && e.data("redirect-uri")) return new WxLogin({
			id: "weixin_login",
			appid: e.data("appid"),
			scope: "snsapi_login,snsapi_userinfo",
			redirect_uri: e.data("redirect-uri"),
			state: e.data("state"),
			href: e.data("css-href")
		})
	}
}.call(this), function() {
	var e, t;
	t = function() {
		return GD.initWeixinLogin($(".social-account.account-weixin")), $("#weixin_login_section").children(".back").data("session-type", $(".login-panel").is(":visible") ? "login" : "signup"), $(".login-switcher, .login-panel, .signup-panel").hide(), $("#weixin_login_section").show()
	}, e = function() {
		return $("#weixin_login_section").children(".back").data("session-type", $(".login-panel").is(":visible") ? "login" : "signup"), $(".login-switcher, .login-panel, .signup-panel").hide(), $("#weixin_login_section").show(), $(".login-box .register-wording").toggle(), GD.pollingDataFromServer(1e3, 30, "/weixin/custom_qrcode", function(e, t) {
			if (e.url != null) return GD.renderQrcode($("#weixin_login"), e.url, 175), $(".weixin-status-text").empty().removeClass("error").text("请使用微信扫描二维码登录金数据").show(), clearInterval(t)
		}, function() {
			return $("#weixin_login img").remove(), $(".weixin-status-text").empty().text("加载二维码失败,请稍后重试").addClass("error").show()
		})
	}, $(document).on("page:load ready", function() {
		if ($(".social-account.account-weixin").data("redirect-to")) return;
		return url("#") === "weixin" && t(), $(".social-account.account-new-weixin").length !== 0 && GD.currentSessionId != null && (GD.channel = GD.pusher.subscribe("session_user_" + GD.currentSessionId), GD.channel.bind("wx_qrcode_done", function(e) {
			return e.message.bind_done != null ? ($("#weixin_user_sign_form input.uid").val(e.message.uid), $("#weixin_user_sign_form input.token").val(e.message.token), $("#weixin_user_sign_form").submit()) : $(".weixin-status-text").empty().text("该微信号已被占用,请更换微信号").addClass("error").show()
		})), $(".social-account.account-weixin").on("click", function() {
			var e;
			return e = $(this).data("redirect-to"), e ? window.location = e : t()
		}), $("#weixin_login_section").on("click", "> .back", function() {
			return $("#weixin_login_section").hide(), $(this).data("session-type") === "login" ? ($(".login-panel, .login-switcher:first").show(), $(".signup-panel").hide()) : ($(".login-panel, .login-switcher + .login-switcher").hide(), $(".signup-panel").show())
		}), $(".social-account.account-new-weixin").on("click", function() {
			return e()
		})
	})
}.call(this), function() {
	$(document).on("click", ".captcha-container img", function() {
		return $(this).attr("src", "/rucaptcha/?" + (new Date).getTime())
	}), $(document).on("click", "form .submit", function(e) {
		var t;
		e.preventDefault(), t = $(this).parents("form"), t.find("input:text").each(function() {
			return $(this).val($.trim($(this).val()))
		});
		if (GD.clientValidator && !GD.clientValidator.validate()) return;
		return $(".captcha-container").length > 0 ? $.ajax({
			url: "/captcha/verify?_rucaptcha=" + encodeURIComponent($(".captcha-box input").val()),
			beforeSend: function() {
				return t.find(".error-message").hide(), t.find(".has-error").removeClass("has-error"), t.find(".error").removeClass("error")
			}
		}).done(function(e) {
			return e === "true" ? t.submit() : (t.find(".captcha-container .captcha-box input").addClass("error"), t.find(".captcha-container .error-message").show(), t.find(".captcha-container img").trigger("click"))
		}) : (t.submit(), $.cookie("formSubmitSuccess", !0, {
			path: location.pathname,
			expires: GD.cookieExpireMinutes(1)
		}))
	})
}.call(this), function() {
	var e;
	e = function() {
		var e;
		if ($(".site.phone-device").length > 0) return e = $("body").height() + $(".social-login").height() < $(window).height(), $(".login-box .social-login").toggleClass("absolute-bottom", e)
	}, $(document).on("ready page:load", function() {
		var t, n, r, i, s, o, u;
		u = function(e, t) {
			return e.closest(".field").addClass("has-error"), e.after("<div class='inline-help error-message'>" + t + "</div>")
		}, r = function(e) {
			return e.find(".has-error").removeClass("has-error"), e.find(".inline-error").remove(), e.find(".captcha-container .error").removeClass("error"), e.find(".captcha-container .error-message").hide()
		}, $(".login-box, .password-reset-panel").on("submit", "form", function(e) {
			var t, n, i, s, o, a;
			r($(this)), o = $(this).find("input[type=email], input:password");
			for (n = 0, s = o.length; n < s; n++) a = o[n], t = $(a), $.trim(t.val()) === "" && (i = function() {
				switch (t.attr("name")) {
				case "email":
				case "auth_key":
				case "identity[email]":
					return "邮箱";
				case "password":
				case "identity[password]":
					return "密码";
				case "identity[password_confirmation]":
					return "确认密码"
				}
			}(), u(t, i + "不能为空"));
			if ($(this).find(".has-error").length > 0) return e.preventDefault()
		}), $(".login-box, .password-reset-panel").on("focus", 'input[type="text"], input[type="password"], input[type="email"]', function(e) {
			var t;
			return t = $(this).closest(".field"), t.find(".error").removeClass("error"), t.find(".error-message").hide()
		}), o = $(".password-reset-page, .signup-verify-page").find(".auto-redirect");
		for (i = 0, s = o.length; i < s; i++) n = o[i], t = $(n), GD.countDownRedirect(t, t.attr("href"), 10, t.text());
		$(".verify-panel form input:submit").on("click", function() {
			return GD.countDown($(this), 60, $(this).val()), $(this).closest("form").submit()
		}), $(".form-organization-join").on("submit", function(e) {
			var t;
			return e.preventDefault(), r($(this)), t = $(e.target), $.ajax({
				url: t.attr("action"),
				type: "POST",
				data: t.serializeObject(),
				beforeSend: function(e) {
					return e.setRequestHeader("Authorization", t.find("input[name=auth_token]").val())
				},
				success: function(e) {
					var t;
					return t = e.organization.subdomain, Turbolinks.visit("/organization/" + t + "/join_success")
				},
				error: function(e) {
					return function(t) {
						var n, r, i, s, o, a, f, l, c;
						f = t.responseJSON, c = [];
						for (i = 0, o = f.length; i < o; i++) r = f[i], l = r.split(" "), s = l[0], a = l[1], n = $(e).find("[placeholder=" + s + "]"), c.push(u(n, [s, a].join("")));
						return c
					}
				}(this)
			})
		});
		if ($(".login-box").length === 0) return;
		return e(), $(window).off("resize.gd.login").on("resize.gd.login", e), $(".login-box").on("click", ".invitation", function() {
			return $(".login-box .alert").hide(), $("#weixin_login_section").hide(), $(".login-box").find(".login-panel, .signup-panel").hide(), $("." + $(this).data("for")).fadeIn(), $(".login-box .login-switcher").toggle()
		}), $(".login-box").on("click", ".social-login [data-role=toggle_social_login]", function() {
			return $(".login-box .social-login-container").slideDown("fast")
		}), $(".login-box").on("change", "#terms_agreed", function() {
			return $(this).closest("form").find("input:submit").prop("disabled", !$(this).is(":checked"))
		}), $(".login-box").on("click", ".login-switcher a", function() {
			return r($(".login-box"))
		})
	})
}.call(this), _.templateSettings = {
	evaluate: /\[\[(.+?)\]\]/g,
	interpolate: /\{\{(.+?)\}\}/g
}, String.toLocaleString({
	en: {
		"%no_result": "No results found",
		"%rechoose": "rechoose",
		"%delete": "delete",
		"%warn_system_file": "system file is not allowed",
		"%attachment_media_type_support": "support: ",
		"%warn_exec_file": "EXE/BAT format file is not allowed",
		"%file_name_too_long": "file name is too long",
		"%warn_oversize": "file size is over {{maxSize}}",
		"%warn_invalid_filetype": "file type is not allowed",
		"%warn_wrong_mobile_number": "<i class='gd-icon-times-circle'></i> please enter correct mobile number of mainland China",
		"%total": "total: ",
		"%download": "download",
		"%sold_out": "sold out",
		"%select_spec": "Please select {{labels}} first",
		"%common_separator": ", ",
		"%send_sms_verification": "Send Code",
		"%resend": "resend SMS",
		"%sendingSMS": "sending SMS",
		"%warn_geo_cannot_get_location": "Can't get your location!",
		"%warn_geo_permission_denied": "You denied the request for Geolocation.",
		"%warn_geo_position_unavailable": "Location information is unavailable.",
		"%warn_geo_timeout": "The request to get your location timed out.",
		"%warn_geo_unknown_error": "An unknown error occurred.",
		"%geo_coord": "Longitude:{{long}},Latitude:{{lat}}",
		"%geo_long": "Longitude:{{long}}",
		"%geo_lat": "Latitude:{{lat}}",
		"%geo_address": "{{address}}",
		"%geo_choose": "Choose location",
		"%geo_locate": "Get current location",
		"%geo_locating": "Locating...",
		"%geo_no_address": "No address information. ",
		"%select_prompt": "Please select",
		"%no_geo_data": "There is no data for this field currently.",
		"%bracket": " ({{content}})",
		"%upload_failed": "failed {{status}}, please contact system admin",
		"%upload_failed_400": "failed, changed to standard mode, please try again",
		"%upload_failed_401": "failed, please refresh page and try again",
		"%uploading": "Uploading...",
		"%upload_done": "Uploading finished!",
		"%max_file_quantity": "File quantity is over {{maxFileQuantity}}",
		"%page_number": "Page {{currentPage}}/{{totalPage}}",
		"%presence_error": "{{label}} is required",
		"%white_list_limit_error": "{{label}} [{{fieldValue}}] is not allowed to submit",
		"%format_error": "format is wrong",
		"%email_format_error": "Please enter a valid email address, such as support@jinshuju.net"
	},
	"zh-CN": {
		"%no_result": "没有匹配的搜索结果",
		"%rechoose": "请重新选择",
		"%delete": "刪除",
		"%warn_system_file": "禁止上传系统文件",
		"%attachment_media_type_support": "仅支持：",
		"%warn_exec_file": "禁止上传EXE、BAT格式的文件",
		"%file_name_too_long": "文件名过长",
		"%warn_oversize": "文件超过了{{maxSize}}",
		"%warn_invalid_filetype": "该类型文件不允许上传",
		"%warn_wrong_mobile_number": "<i class='gd-icon-times-circle'></i> 请输入正确的中国大陆手机号码",
		"%total": "合计：",
		"%download": "下载",
		"%sold_out": "已售空",
		"%select_spec": "请先选择{{labels}}",
		"%common_separator": "、",
		"%send_sms_verification": "发送验证短信",
		"%resend": "重新发送",
		"%sendingSMS": "正在发送短信",
		"%warn_geo_cannot_get_location": "无法定位您的位置！",
		"%warn_geo_permission_denied": "无法定位您的位置！请打开浏览器的位置共享权限",
		"%warn_geo_position_unavailable": "无法定位您的位置！位置信息不可用",
		"%warn_geo_timeout": "无法定位您的位置！获取经纬度超时",
		"%warn_geo_unknown_error": "无法定位您的位置！未知错误",
		"%geo_coord": "经度:{{long}}，纬度:{{lat}}",
		"%geo_long": "经度:{{long}}",
		"%geo_lat": "纬度:{{lat}}",
		"%geo_address": "{{address}}",
		"%geo_choose": "获取地理位置",
		"%geo_locate": "获取当前位置",
		"%geo_locating": "获取位置中...",
		"%geo_no_address": "无法获取地址。",
		"%select_prompt": "请选择",
		"%no_geo_data": "该地理位置字段暂无数据。",
		"%bracket": "({{content}})",
		"%upload_failed": "上传失败{{status}}，请联系管理员",
		"%upload_failed_400": "该浏览器不支持极速上传模式，现已调整为传统模式。请选择文件重新上传。",
		"%upload_failed_401": "上传失败，请刷新页面，并在1小时内重新上传",
		"%uploading": "上传中...",
		"%upload_done": "上传成功！",
		"%max_file_quantity": "最多只能上传{{maxFileQuantity}}个文件",
		"%page_number": "第{{currentPage}}页/共{{totalPage}}页",
		"%presence_error": "请填写{{label}}",
		"%white_list_limit_error": "{{label}} [{{fieldValue}}] 不能提交",
		"%format_error": "格式不对",
		"%email_format_error": "请输入正确的邮箱地址，如support@jinshuju.net"
	},
	"zh-TW": {
		"%no_result": "沒有匹配的搜索結果",
		"%rechoose": "請重新選擇",
		"%delete": "刪除",
		"%warn_system_file": "禁止上傳系統檔案",
		"%attachment_media_type_support": "僅支持：",
		"%warn_exec_file": "禁止上傳EXE、BAT格式的檔案",
		"%file_name_too_long": "檔案名過長",
		"%warn_oversize": "檔案超過了{{maxSize}}",
		"%warn_invalid_filetype": "該檔案類型不允許上傳",
		"%warn_wrong_mobile_number": "請輸入正確的中國大陸手機號碼",
		"%total": "合計：",
		"%download": "下載",
		"%sold_out": "已售罄",
		"%select_spec": "請先選擇{{labels}}",
		"%common_separator": "、",
		"%send_sms_verification": "發送驗證簡訊",
		"%resend": "重新發送",
		"%sendingSMS": "正在發送簡訊",
		"%warn_geo_cannot_get_location": "無法定位您的位置！",
		"%warn_geo_permission_denied": "無法定位您的位置！請打開瀏覽器的位置共用許可權",
		"%warn_geo_position_unavailable": "無法定位您的位置！位置資訊不可用",
		"%warn_geo_timeout": "無法定位您的位置！獲取經緯度超時",
		"%warn_geo_unknown_error": "無法定位您的位置！未知錯誤",
		"%geo_coord": "經度:{{long}}，緯度:{{lat}}",
		"%geo_address": "{{address}}",
		"%geo_choose": "獲取地理位置",
		"%geo_locate": "獲取當前位置",
		"%geo_locating": "獲取位置中...",
		"%geo_no_address": "無法獲取地址。",
		"%select_prompt": "請選擇",
		"%no_geo_data": "該地理位置字段暫無數據。",
		"%bracket": "（{{content}}）",
		"%upload_failed": "上傳失敗{{status}}，請聯繫管理員",
		"%upload_failed_400": "該瀏覽器不支援極速上傳模式，現已調整為傳統模式。請選擇檔案重新上傳。",
		"%upload_failed_401": "上傳失敗，請刷新頁面，並在1小時內重新上傳",
		"%uploading": "上傳中...",
		"%upload_done": "上傳成功！",
		"%max_file_quantity": "最多只能上傳{{maxFileQuantity}}個檔案",
		"%page_number": "第{{currentPage}}頁/共{{totalPage}}頁",
		"%presence_error": "請填寫{{label}}",
		"%white_list_limit_error": "{{label}} [{{fieldValue}}] 不能提交",
		"%format_error": "格式不對",
		"%email_format_error": "請輸入正確的電郵地址，如support@jinshuju.net"
	}
});
var l = function(e, t) {
		return t == null && (t = {}), _.template(e.toLocaleString())(t)
	};
String.locale = "zh-CN", function() {
	GD.transformCheckbox = function(e) {
		return e.find("input[type=checkbox]:not(.no-need-transform):not(.field-transformed)").addClass("field-transformed").wrap(function() {
			return "<div class='check-box-wrapper'></div>"
		}).after("<i class='selected-icon'></i>")
	}
}.call(this), function() {
	GD.transformDropdown = function(e) {
		return e.find("select:not(.field-transformed):not(.need-select2)").addClass("field-transformed").wrap(function() {
			var e;
			return e = "", $(this).data("has-error") && (e += " field-with-errors"), $(this).hasClass("hide") && (e += " hide"), _.contains(["province", "city", "district"], $(this).data("role")) && (e += " " + $(this).data("role") + "-select-wrapper"), "<div class='dropdown-wrapper" + e + "'></div>"
		}).after("<b class='dropdown-bg'></b><i class='dropdown-trigger'></i>")
	}
}.call(this), function() {
	GD.enhanceInputField = function(e) {
		var t, n, r, i, s, o, u;
		o = e.find("input.input-with-icon:not(.enhanced-input)"), u = [];
		for (n = 0, s = o.length; n < s; n++) i = o[n], t = $(i), r = t.data("icon"), u.push(t.addClass("enhanced-input").wrap("<div class='gd-input-container'></div>").after("<i class='gd-input-icon " + r + "'></i>"));
		return u
	}
}.call(this), function() {
	GD.enhanceLikertAndMatrixField = function(e) {
		var t, n, r, i, s;
		e.find(".field-likert-field table, .field-matrix-field table").addClass("tablesaw tablesaw-stack").attr("data-tablesaw-mode", "stack"), e.trigger("enhance.tablesaw"), r = e.find(".field-matrix-field input"), i = [];
		for (t = 0, n = r.length; t < n; t++) s = r[t], i.push($(s).css({
			minHeight: $(s).closest("td").height()
		}));
		return i
	}
}.call(this), function() {
	GD.transformRadioButton = function(e) {
		return e.find("input[type=radio]:not(.no-need-transform):not(.field-transformed)").addClass("field-transformed").wrap(function() {
			return "<div class='radio-button-wrapper'></div>"
		}).after("<i class='selected-icon'></i>")
	}
}.call(this), function() {
	GD.transformAllFields = function(e) {
		e == null && (e = $(".entry-container, .preview-container"));
		if (e.length === 0) return;
		return GD.isIE() || GD.transformRadioButton(e), GD.isIE() || GD.transformCheckbox(e), GD.isIE() || GD.transformDropdown(e), GD.enhanceLikertAndMatrixField(e), GD.enhanceInputField(e)
	}, $(document).on("ready page:load ajax:complete", function() {
		if ($(".dashboard").length > 0) return;
		return GD.transformAllFields()
	})
}.call(this), function() {
	window.GD || (window.GD = {}), GD.resetGeoFieldChooser = function(e) {
		GD.enableBtn(e.find(".geo-field-chooser, .current-location-btn")), e.find(".geo-field-chooser span").text(l("%geo_choose"));
		if (e.hasClass("mobile")) return e.find(".gd-btn.geo-field-chooser").val(l("%geo_locate"))
	}, GD.Geo = function() {
		function e(e, t) {
			this.$el = e, this.options = t != null ? t : {}, this.options = $.extend({
				staticMap: !1,
				mobile: !1,
				localizable: !1
			}, this.options), this.initMap(), this.options.staticMap || this.options.mobile && !this.options.localizable || this.initMapEvent()
		}
		return e.prototype._noOverrideError = function(e) {
			throw "can't find " + e + " in subclass"
		}, e.prototype.initMap = function() {
			return this._initMap(this.$el.find(".map-container")[0])
		}, e.prototype.getGeoMethod = function(e) {
			return this.geoArray || (this.geoArray = this._geoArray()), this.geoArray[e]
		}, e.prototype.onGetLocationSuccess = function(e, t) {
			return this.updateMap(e, null, t)
		}, e.prototype.onGetLocationFail = function() {
			return alert(l("%warn_geo_cannot_get_location")), GD.resetGeoFieldChooser(this.$el)
		}, e.prototype._useNextGeoMethod = function(e) {
			var t;
			t = this.getGeoMethod(this.geoIndex);
			if (!t) return;
			return this.geoIndex++, t(e)
		}, e.prototype.currentLocation = function(e) {
			return e == null && (e = null), e = e != null ? e : {}, this.geoIndex = 0, this._useNextGeoMethod(e)
		}, e.prototype.updateMap = function(e, t, n) {
			return t == null && (t = null), n == null && (n = {}), this.mapObj && this.setMarker(e, n.zoom), this.showAddress(e, t, n), this.$el.find(".geo-map-coord").html("<span>" + l("%geo_long", {
				"long": e.lng.toFixed(8)
			}) + "</span>"), this.$el.find(".geo-map-coord").append("<span class='separator'>" + l("%common_separator") + "</span>"), this.$el.find(".geo-map-coord").append("<span>" + l("%geo_lat", {
				lat: e.lat.toFixed(8)
			}) + "</span>"), this.$el.find("input[name$='[latitude]']").val(e.lat), this.$el.find("input[name$='[longitude]']").val(e.lng)
		}, e.prototype._showAddress = function(e, t) {
			this.$el.find(".geo-map-address").text(l("%geo_address", {
				address: e
			})), this.$el.find("input[name$='[address]']").val(e);
			if (t.onComplete) return t.onComplete()
		}, e.prototype.showAddress = function(e, t, n) {
			return t == null && (t = null), n == null && (n = {}), t ? this._showAddress(t, n) : this._showAddressFromLocation(e, n)
		}, e.prototype._initMap = function(e) {
			return this._noOverrideError("_initMap")
		}, e.prototype.initMapEvent = function() {
			return this._noOverrideError("initMapEvent")
		}, e.prototype._geoArray = function() {
			return this._noOverrideError("_geoArray")
		}, e.prototype.setMarker = function(e, t) {
			return t == null && (t = null), this._noOverrideError("setMarker")
		}, e.prototype._showAddressFromLocation = function(e, t) {
			return this._noOverrideError("_showAddressFromLocation")
		}, e.prototype.searchLocation = function(e) {
			return this._noOverrideError("searchLocation")
		}, e
	}()
}.call(this), function() {
	var e = function(e, n) {
			function i() {
				this.constructor = e
			}
			for (var r in n) t.call(n, r) && (e[r] = n[r]);
			return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
		},
		t = {}.hasOwnProperty;
	GD.GeoAutonavi = function(t) {
		function n() {
			return n.__super__.constructor.apply(this, arguments)
		}
		return e(n, t), n.prototype._initMap = function(e) {
			var t, n;
			try {
				return this.mapObj = new AMap.Map(e, {
					lang: String.locale
				})
			} catch (t) {
				return n = t, console.log(n)
			} finally {
				this.mapObj.plugin("AMap.ToolBar", function(e) {
					return function() {
						var t;
						return t = e.options.localizable && e.options.mobile ? {
							offset: new AMap.Pixel(2, 65)
						} : {
							direction: !1,
							offset: new AMap.Pixel(-10, 5)
						}, e.mapObj.addControl(new AMap.ToolBar(t))
					}
				}(this))
			}
		}, n.prototype.initMapEvent = function() {
			return AMap.event.addListener(this.mapObj, "click", function(e) {
				return function(t) {
					return e.updateMap(t.lnglat)
				}
			}(this))
		}, n.prototype._geoArray = function() {
			var e, t;
			return e = function(e) {
				return function(t) {
					var n, r;
					return r = function(n) {
						return e.onGetLocationSuccess(n.position, t)
					}, n = function(n) {
						n == null && (n = null);
						if (e._useNextGeoMethod(t)) return;
						if (n) switch (n.info) {
						case "PERMISSION_DENIED":
							alert(l("%warn_geo_permission_denied"));
							break;
						case "POSITION_UNAVAILBLE":
							alert(l("%warn_geo_position_unavailable"));
							break;
						case "TIMEOUT":
							alert(l("%warn_geo_timeout"));
							break;
						default:
							alert(l("%warn_geo_unknown_error"))
						}
						return GD.resetGeoFieldChooser(e.$el)
					}, e.mapObj.plugin("AMap.Geolocation", function() {
						var t;
						return t = new AMap.Geolocation({
							timeout: 5e3,
							showButton: !1,
							showCircle: !1,
							zoomToAccuracy: !0
						}), e.mapObj.addControl(t), AMap.event.addListener(t, "complete", r), AMap.event.addListener(t, "error", n), t.getCurrentPosition()
					})
				}
			}(this), t = function(e) {
				return function(t) {
					var n;
					return n = function() {
						if (e._useNextGeoMethod(t)) return;
						return e.onGetLocationFail()
					}, e.mapObj.plugin("AMap.CitySearch", function() {
						var r;
						return r = new AMap.CitySearch, AMap.event.addListener(r, "complete", function(r) {
							var i;
							if (!(r && r.city && r.bounds)) return n();
							i = r.city, e.$el.find(".geo-map-address").text(i), e.$el.find(".geo-map-coord").empty(), e.$el.find("input[name$='[address]']").val(i), e.mapObj.setBounds(r.bounds);
							if (t.onComplete) return t.onComplete()
						}), AMap.event.addListener(r, "error", n), r.getLocalCity()
					})
				}
			}(this), [e, t]
		}, n.prototype.setMarker = function(e, t) {
			var n, r;
			return t == null && (t = null), this.mapObj.clearMap(), r = {
				map: this.mapObj,
				icon: "https://webapi.amap.com/images/marker_sprite.png",
				position: e
			}, this.options.staticMap || this.options.mobile && !this.options.localizable ? new AMap.Marker(r) : (n = new AMap.Marker($.extend(r, {
				draggable: !0,
				animation: "AMAP_ANIMATION_DROP",
				cursor: "move",
				raiseOnDrag: !0
			})), AMap.event.addListener(n, "dragend", function(e) {
				return function(t) {
					return e.updateMap(t.lnglat)
				}
			}(this))), this.mapObj.setZoomAndCenter(t != null ? t : 16, e)
		}, n.prototype._showAddressFromLocation = function(e, t) {
			return this.mapObj.plugin("AMap.Geocoder", function(n) {
				return function() {
					var r;
					return r = new AMap.Geocoder({
						radius: 1e3,
						extensions: "all"
					}), AMap.event.addListener(r, "complete", function(e) {
						var r;
						return r = e.regeocode.formattedAddress, n._showAddress(r, t)
					}), r.getAddress(e)
				}
			}(this))
		}, n.prototype.searchLocation = function(e) {
			return GD.disableBtn(this.$el.find(".map-search-btn")), AMap.service(["AMap.Geocoder"], function(t) {
				return function() {
					var n;
					return n = new AMap.Geocoder, n.getLocation(e, function(e, n) {
						var r, i, s;
						switch (e) {
						case "complete":
							r = n.geocodes[0], r && (i = r.level, s = function() {
								switch (i) {
								case "省":
									return 5;
								case "市":
									return 10;
								case "区县":
									return 12;
								default:
									return 16
								}
							}(), t.updateMap(r.location, r.formattedAddress, {
								zoom: s
							}));
							break;
						case "no_data":
							alert(l("%geo_no_address"));
							break;
						default:
							alert(n)
						}
						return GD.enableBtn(t.$el.find(".map-search-btn"))
					})
				}
			}(this))
		}, n
	}(GD.Geo)
}.call(this), function() {
	var e = function(e, n) {
			function i() {
				this.constructor = e
			}
			for (var r in n) t.call(n, r) && (e[r] = n[r]);
			return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
		},
		t = {}.hasOwnProperty;
	GD.GeoGoogle = function(t) {
		function n() {
			return n.__super__.constructor.apply(this, arguments)
		}
		return e(n, t), n.prototype._initMap = function(e) {
			var t;
			return t = {
				zoom: 4,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				streetViewControl: !1,
				zoomControlOptions: {
					position: google.maps.ControlPosition.LEFT_TOP
				}
			}, this.mapObj = new google.maps.Map(e, t), this.marker = new google.maps.Marker({
				position: this.mapObj.getCenter(),
				map: this.mapObj,
				animation: google.maps.Animation.Drop,
				draggable: !this.options.staticMap
			})
		}, n.prototype.initMapEvent = function() {
			return google.maps.event.addListener(this.mapObj, "click", function(e) {
				return function(t) {
					return e.updateMap(e._latLngFromGoogleLatLng(t.latLng))
				}
			}(this)), google.maps.event.addListener(this.marker, "dragend", function(e) {
				return function(
				t) {
					return e.updateMap(e._latLngFromGoogleLatLng(t.latLng))
				}
			}(this))
		}, n.prototype._geoArray = function() {
			var e;
			return e = function(e) {
				return function(t) {
					var n, r;
					return r = function(n) {
						var r;
						return r = {
							lat: n.coords.latitude,
							lng: n.coords.longitude
						}, e.onGetLocationSuccess(r, t)
					}, n = function(n) {
						n == null && (n = null);
						if (e._useNextGeoMethod(t)) return;
						if (n) switch (n.code) {
						case n.PERMISSION_DENIED:
							alert(l("%warn_geo_permission_denied"));
							break;
						case n.POSITION_UNAVAILABLE:
							alert(l("%warn_geo_position_unavailable"));
							break;
						case n.TIMEOUT:
							alert(l("%warn_geo_timeout"));
							break;
						default:
							alert(l("%warn_geo_unknown_error"))
						}
						return GD.resetGeoFieldChooser(e.$el)
					}, navigator.geolocation ? navigator.geolocation.getCurrentPosition(r, n, {
						timeout: 5e3,
						maximumAge: 0
					}) : n()
				}
			}(this), [e]
		}, n.prototype._googleLatLng = function(e) {
			return new google.maps.LatLng(e.lat, e.lng)
		}, n.prototype._latLngFromGoogleLatLng = function(e) {
			return {
				lat: e.lat(),
				lng: e.lng()
			}
		}, n.prototype.setMarker = function(e, t) {
			var n;
			return t == null && (t = null), n = this._googleLatLng(e), this.mapObj.setZoom(t != null ? t : 16), this.mapObj.setCenter(n), this.marker.setPosition(n)
		}, n.prototype._showAddressFromLocation = function(e, t) {
			var n;
			return n = this._googleLatLng(e), (new google.maps.Geocoder).geocode({
				latLng: n
			}, function(n) {
				return function(r, i) {
					var s;
					i === google.maps.GeocoderStatus.OK && r[0] && (s = $.trim(r[0].formatted_address.replace(/邮政编码.*/, ""))), s ? (n.$el.find(".geo-map-address").text(l("%geo_address", {
						address: s
					})), n.$el.find("input[name$='[address]']").val(s)) : n.$el.find(".geo-map-address").text(l("%geo_no_address") + l("%geo_coord", {
						"long": e.lng,
						lat: e.lat
					}));
					if (t.onComplete) return t.onComplete()
				}
			}(this))
		}, n.prototype.searchLocation = function(e) {
			return GD.disableBtn(this.$el.find(".map-search-btn")), (new google.maps.Geocoder).geocode({
				address: e
			}, function(e) {
				return function(t, n) {
					return n === google.maps.GeocoderStatus.OK ? e.updateMap(e._latLngFromGoogleLatLng(t[0].geometry.location)) : alert(l("%geo_no_address")), GD.enableBtn(e.$el.find(".map-search-btn"))
				}
			}(this))
		}, n
	}(GD.Geo)
}.call(this), function() {
	var e;
	e = function() {
		function e(e, t, n) {
			this.$container = e, this.useGoogle = t != null ? t : !1, this.staticMap = n != null ? n : !1, this.mobile = this.$container.closest(".mobile-device").length > 0, this.localizable = this.$container.hasClass("localizable"), this.initialMap(), this.staticMap || this.initialEvents(), this.$container.data("geo-initialized", !0)
		}
		return e.prototype.initialMap = function() {
			return this.staticMap || GD.resetGeoFieldChooser(this.$container), this.initialExistedPosition()
		}, e.prototype.ensureGeo = function() {
			if (this.geo === void 0) return this.useGoogle ? this.geo = new GD.GeoGoogle(this.$container, {
				mobile: this.mobile,
				staticMap: this.staticMap
			}) : this.geo = new GD.GeoAutonavi(this.$container, {
				mobile: this.mobile,
				staticMap: this.staticMap,
				localizable: this.localizable
			})
		}, e.prototype.initialExistedPosition = function() {
			var e, t, n, r;
			return this.staticMap ? (e = this.$container.find(".map-container"), n = e.data("latitude"), r = e.data("longitude"), t = e.data("address")) : (n = this.$container.find("input[name$='[latitude]']").val(), r = this.$container.find("input[name$='[longitude]']").val(), t = this.$container.find("input[name$='[address]']").val()), this.setLocation(n, r, t)
		}, e.prototype.setLocation = function(e, t, n) {
			var r;
			if (e && t) return this.ensureGeo(), r = this.useGoogle ? {
				lat: parseFloat(e),
				lng: parseFloat(t)
			} : new AMap.LngLat(parseFloat(t), parseFloat(e)), this.geo.updateMap(r, n);
			if (n) return this.ensureGeo(), this.geo.searchLocation(n)
		}, e.prototype.initialEvents = function() {
			return this.$container.find(".geo-field-chooser").click(function(e) {
				return function() {
					return e.ensureGeo(), e.geo.currentLocation(), e.$container.find(".geo-map-container").show(), e.$container.find(".geo-field-chooser").hide(), GD.recalcFormHeight()
				}
			}(this)), this.$container.find(".clear-location-btn").click(function(e) {
				return function() {
					return e.$container.find(".geo-field-chooser").css("display", "inline-block"), e.$container.find(".geo-map-container").hide(), e.$container.find("input[name$='[latitude]']").val(""), e.$container.find("input[name$='[longitude]']").val(""), e.$container.find("input[name$='[address]']").val(""), GD.recalcFormHeight()
				}
			}(this)), this.$container.find(".map-search-btn").click(function(e) {
				return function() {
					var t;
					t = e.$container.find(".map-search").val();
					if (t) return e.geo.searchLocation(t)
				}
			}(this)), this.$container.find(".current-location-btn").click(function(e) {
				return function() {
					return e.ensureGeo(), e.geo.currentLocation({
						onComplete: function() {
							return GD.enableBtn(e.$container.find(".current-location-btn.disabled"))
						}
					})
				}
			}(this))
		}, e
	}(), GD.initGeoView = function(t) {
		var n, r, i, s, o;
		t == null && (t = !1), s = $("[data-role=geo]"), o = [];
		for (r = 0, i = s.length; r < i; r++) n = s[r], $(n).data("geo-initialized") ? o.push(void 0) : o.push(new e($(n), t));
		return o
	}, GD.initStaticGeoView = function(t) {
		var n, r, i, s, o;
		t == null && (t = !1), s = $("[data-role=static-geomap]"), o = [];
		for (r = 0, i = s.length; r < i; r++) n = s[r], o.push(new e($(n), t, !0));
		return o
	}, $(document).on("keypress", ".field-geo-field .map-search", function(e) {
		if (e.keyCode === 13) return $(this).closest(".geo-map-action").find(".map-search-btn").click()
	})
}.call(this), function() {
	window.GD || (window.GD = {}), $(function() {
		return GD.AddressSelector = function() {
			function e(e) {
				var t;
				this.data = e, this.provinces = function() {
					var e;
					e = [];
					for (t in this.data) e.push(t);
					return e
				}.call(this)
			}
			return e.prototype.render = function(e, t, n, r, i, s) {
				var o, u, a, f, l;
				r == null && (r = ""), i == null && (i = ""), s == null && (s = ""), e.html("<option value=''>- 省/自治区/直辖市 -</option>"), l = this.provinces;
				for (o = 0, u = l.length; o < u; o++) f = l[o], a = $("<option value='" + f + "'>" + f + "</option>"), f === r && a.prop("selected", !0), e.append(a);
				return this.renderCityOptions(r, t, i), this.renderDistrictOptions(r, i, n, s), e.on("change", function(r) {
					return function() {
						return r.renderCityOptions(e.val(), t), r.renderDistrictOptions(e.val(), "", n)
					}
				}(this)), t.on("change", function(r) {
					return function() {
						return r.renderDistrictOptions(e.val(), t.val(), n)
					}
				}(this))
			}, e.prototype.renderCityOptions = function(e, t, n) {
				var r, i, s, o, u, a;
				n == null && (n = ""), t.html("<option value=''>- 市 -</option>");
				if (this.data.hasOwnProperty(e)) {
					u = _.keys(this.data[e]), a = [];
					for (i = 0, s = u.length; i < s; i++) r = u[i], o = $("<option value='" + r + "'>" + r + "</option>"), r === n && o.prop("selected", !0), a.push(t.append(o));
					return a
				}
			}, e.prototype.renderDistrictOptions = function(e, t, n, r) {
				var i, s, o, u, a, f, l;
				r == null && (r = ""), n.html("<option value=''>- 区/县 -</option>");
				if ((a = this.data[e]) != null ? a[t] : void 0) {
					f = this.data[e][t], l = [];
					for (s = 0, o = f.length; s < o; s++) i = f[s], u = $("<option value='" + i + "'>" + i + "</option>"), i === r && u.prop("selected", !0), l.push(n.append(u));
					return l
				}
			}, e
		}(), GD.initAddressSelector = function(e) {
			var t, n, r, i, s, o, u, a;
			if (GD.addressSelector) {
				u = $(e).find("[data-role=address]"), a = [];
				for (i = 0, s = u.length; i < s; i++) t = u[i], o = $(t).find("[data-role=province]"), n = $(t).find("[data-role=city]"), r = $(t).find("[data-role=district]"), a.push(GD.addressSelector.render(o, n, r, o.data("value"), n.data("value"), r.data("value")));
				return a
			}
		}, $(document).on("ready page:load ajax:complete", function() {
			if (!($(".dashboard").length > 0)) return GD.initAddressSelector(document)
		})
	})
}.call(this), function() {
	window.GD || (window.GD = {}), $(function() {
		var e;
		return e = function(e, t) {
			var n, r, i, s, o, u, a, f, c;
			c = e.find("option:selected"), t.html("<option value=''>" + l("%select_prompt") + "</option>"), a = (u = c.data("choices")) != null ? u : [];
			for (s = 0, o = a.length; s < o; s++) n = a[s], typeof n == "object" ? (r = n.name, i = (f = n.value) != null ? f : r) : (r = n, i = n), t.append("<option value='" + i + "'>" + r + "</option>");
			t.val(t.data("value"));
			if ($.mobile) return t.selectmenu("refresh", !0)
		}, GD.initCascadeSelector = function(t) {
			var n, r, i, s, o, u;
			o = $(t).find("[data-role=cascade]"), u = [];
			for (r = 0, i = o.length; r < i; r++) n = o[r], s = $(n).find("[data-role=level_1]"), e(s, $(n).find("[data-role=level_2]")), u.push(s.on("change", function() {
				var t;
				return t = $(this).parents("[data-role=cascade]").find("[data-role=level_2]"), t.data("value", null), e($(this), t)
			}));
			return u
		}, $(document).on("ready page:load ajax:complete", function() {
			if (!($(".dashboard").length > 0)) return GD.initCascadeSelector(document)
		})
	})
}.call(this), function() {
	var e, t, n, r, i, s, o, u, a, f, c, h, p, d, v, m, g, y, b, w, E, S, x, T;
	window.GD || (window.GD = {}), T = $.support.xhrFormDataFileUpload, d = function(e, t, n) {
		var r, i;
		return t == null && (t = ""), n == null && (n = null), (i = e.find("[data-role=cancel]").data("jqXHR")) != null && i.abort(), e.removeClass("error"), r = e.find(".status"), r.removeClass("error"), r.find(".file-name").removeClass("error").text(t), r.find(".error").remove(), r.find(".file-name + .error").remove(), r.find(".file-size-status").show(), e.find(".progress-bar").css({
			width: "0%"
		}).show(), r.find(".file-size").text(n), r.show(), e.find(".select-area .preview").empty(), e.find("input[data-role=attachment_id]").val("")
	}, m = function(e, t) {
		return e.addClass("error"), e.find("input[data-role=attachment_id]").val(""), e.find(".status .progress .progress-bar").addClass("initial").text("0%").css("width", "auto"), e.find(".status .file-size-status").hide(), e.find(".actions").show(), e.find(".status").addClass("error"), e.find(".status .file-name").addClass("error").after("<span class='error'>" + l("%bracket", {
			content: t
		}) + "</span>")
	}, o = function(t, n) {
		var r, i, s, o, u, a, f, c, h, p, d, v;
		n == null && (n = ""), t.find(".attachment-select-trigger").hide(), u = t.data("api-code"), c = t.find("input:file"), p = c.data("max-size"), a = c.attr("accept"), i = a !== "*" ? _.map(a.split(","), function(e) {
			return e.split(".").pop()
		}) : [], _.isEmpty(i) || (s = i.join(l("%common_separator"))), v = t.find(".attachment");
		for (f = 0, h = v.length; f < h; f++) o = v[f], r = $(o), d = $("<input class='origin-file-input' type='file' name='entry[" + u + "]' data-max-size='" + p + "' accept='" + a + "'>"), e(t, d), n && d.after($("<div class='help-block inline-error'><i class='gd-icon-times-circle'></i>" + n + "</div>")), r.html(d).show(), _.isEmpty(i) || r.after($("<p class='text-muted accept-file-extensions-text'>" + l("%attachment_media_type_support") + " " + s + "</p>"));
		return t.closest("form").attr("enctype", "multipart/form-data")
	}, f = function(e) {
		var t, n;
		if (!e) return;
		if (e === "*") return;
		return t = _.map(e.toString().split(","), function(e) {
			return e.split(".").pop()
		}).join("|"), n = RegExp(".*\\.(" + t + ")$", "gi")
	}, e = function(e, t) {
		return t.off("change").on("change", function(t) {
			var n, r, i, s, o;
			i = (o = t.target) != null ? o.files[0] : void 0;
			if (!i) return;
			return n = $(this).attr("accept"), n && (s = f(n)), r = GD.validateFile(i, $(this).data("max-size"), s), u(e, r)
		})
	}, u = function(e, t) {
		var n, r;
		r = e.find(".attachment"), n = e.closest("form").find(".submit-field input.submit, .entry-show .actions [data-role='submit']"), GD.clearErrorMessageBelow(r), GD.enableBtn(n);
		if (!_.isEmpty(t)) return GD.disableBtn(n), _.each(t, function(e) {
			return GD.showErrorMessageBelow(r, e)
		})
	}, h = function(e) {
		return e.find(".progress-bar").css("width", "0%"), e.siblings().size() > 0 ? e.remove() : (e.find("[data-role=attachment_id]").val(""), e.hide())
	}, b = function(e) {
		var t, n;
		return n = e.find(".attachment:visible").size(), t = e.find(".attachment-select-trigger"), t.toggle(n < t.data("max-file-quantity"))
	}, w = function(e) {
		var t, n, r;
		return n = e.find(".attachment:visible").size(), r = e.find(".uploaded").size(), t = e.closest("form").find(".submit-field input.submit, .entry-show .actions [data-role='submit']"), t.data("fileUploading", n - r), GD.updateSubmitBtn()
	}, S = function(e, t, n) {
		var r;
		t.find("input[data-role=attachment_id]").val(n.attachment_id);
		if (n.image_url != null) return r = "<a href='" + n.image_url_for_lightbox + "' rel='lightbox[" + e.field + "]'>" + ("<img src='" + n.image_url + "'></a>"), t.find(".preview-area .preview").html(r)
	}, x = function(e, t) {
		var n;
		return n = _.isArray(t) ? t.join(l("%common_separator")) : t, m(e, n)
	}, E = function(e, t, n, r) {
		var i;
		return i = t.context, $.ajax({
			type: "POST",
			url: "/p/a/en",
			data: n,
			dataType: "json"
		}).done(function(t) {
			return setTimeout(function() {
				return i.find(".progress-bar").css("width", "100%").fadeOut(), w(e)
			}, 200), S(n, i, t)
		}).fail(function(e) {
			var t;
			return t = $.parseJSON(e.responseText), x(i, t.errors)
		})
	}, v = function(e, t) {
		var n, r, i, s;
		return n = t.name.split("."), r = n[n.length - 1].toLowerCase(), i = (s = GD.attachmentIconPath[r]) != null ? s : GD.attachmentIconPath["default"], e.find(".preview-area .preview").html("<img src='" + i + "'>")
	}, a = function(e) {
		return setTimeout(function() {
			return e.find(".attachment-error-message").html("")
		}, 3e3)
	}, g = function(e, t) {
		var n, r, i;
		return i = e.find(".attachment.rechoosing"), n = e.find(".attachment").first(), e.find(".attachment:hidden").size() === 1 ? t.context = n.show() : i.size() > 0 ? (t.context = i, i.removeClass("rechoosing")) : (r = n.clone().show(), e.find(".attachments").append(r), t.context = r), t.context.removeClass("uploaded")
	}, p = function(e) {
		var t;
		return t = e.find("input:file"), t.prop("multiple", t.data("origin-multiple"))
	}, y = function(e, t) {
		var n;
		return n = Date.parse(new Date) + "_" + _.random(0, 1e3) + "_" + t, e.formData = {
			accept: "text/plain; charset=utf-8",
			token: GD.xhrUploadToken,
			"x:field_api_code": e.context.closest(".field[data-api-code]").data("api-code"),
			"x:timestamp_with_random_number": n
		}
	}, t = function(e, t) {
		return t.field = e.data("api-code"), t.maxFileQuantity = e.find(".attachment-select-trigger").data("max-file-quantity"), t.availableFileQuantity = t.maxFileQuantity - e.find(".attachment:visible").size()
	}, r = function(e) {
		return e.on("click", ".attachment a[data-role=cancel]", function(t) {
			var n;
			return h($(this).closest(".attachment")), b(e), (n = $(this).data("jqXHR")) != null && n.abort(), w(e)
		})
	}, s = function(e) {
		return e.on("click", ".attachment [data-role=rechoose]", function(t) {
			return e.find("input:file").prop("multiple", !1), $(this).closest(".attachment").addClass("rechoosing")
		})
	}, i = function(e) {
		return r(e), s(e), e.find(".attachment-select-trigger").on("dragleave", function() {
			return $(this).removeClass("drag-over")
		})
	}, n = function(e, t) {
		var n, r, i;
		return n = e.find(".attachment-error-message"), i = e.find(".attachment:visible").hasClass("rechoosing"), r = i || t.availableFileQuantity > 0, r ? n.empty() : n.html(l("%max_file_quantity", {
			maxFileQuantity: t.maxFileQuantity
		})), r
	}, c = function(e) {
		var r, s;
		if (typeof $("input:file").fileupload != "function") return;
		return i(e), s = 0, r = e.find(".attachment-select-trigger"), e.find(".jquery-file-upload-file-input").fileupload({
			dataType: "json",
			dropZone: r,
			paramName: "file",
			sequentialUploads: !0,
			url: "https://up.qbox.me/",
			dragover: function() {
				return r.addClass("drag-over")
			},
			drop: function() {
				return r.removeClass("drag-over")
			},
			add: function(i, o) {
				var u, a, c, h;
				t(e, o);
				if (!n(e, o)) return !1;
				g(e, o), c = o.files[0], v(o.context, c), d(o.context, c.name, GD.numberToHumanSize(c.size)), u = $(this).data("weixin-accepts") || $(this).attr("accept"), u && (h = f(u)), a = GD.validateFile(c, $(this).data("max-size"), h);
				if (a.length > 0) return w(e), r.hide(), m(o.context, a.join(l("%common_separator")));
				if (!o.context.hasClass("preview")) return y(o, s), o.context.find("[data-role=cancel]").data("jqXHR", o.submit()), b(e), s += 1, w(e)
			},
			progress: function(e, t) {
				var n, r;
				return n = parseInt(t.loaded / t.total * 100, 10), r = Math.min(n, 99) + "%", t.context.find(".progress-bar").css("width", r)
			},
			fail: function(t, n) {
				var r;
				return (r = n.jqXHR.status) === 401 ? m(n.context, l("%upload_failed_" + n.jqXHR.status)) : n.jqXHR.status === 400 ? o(e, l("%upload_failed_400")) : (n.jqXHR.status !== 0 && $.post("/p/a/failure", {
					url: url(),
					status: n.jqXHR.status,
					message: n.jqXHR.responseText,
					file_name: n.files[0].name,
					authenticity_token: n.context.closest("form").find("[name=authenticity_token]").val(),
					user_agent: navigator.userAgent,
					comment: "window.FormData = " + T + ", isMobile = " + !! GD.isMobile
				}), m(n.context, l("%upload_failed", {
					status: n.jqXHR.status
				})))
			},
			done: function(t, n) {
				var r;
				return a(e), p(e), n.context.addClass("uploaded"), r = n.result, r ? E(e, n, r) : m(n.context, l("%upload_failed_401"))
			}
		})
	}, GD.initializeFileUploads = function(e) {
		var t, n, r, i, s;
		i = e.find(".field-attachment-field"), s = [];
		for (n = 0, r = i.length; n < r; n++) t = i[n], T ? s.push(c($(t))) : s.push(o($(t)));
		return s
	}, $(document).on("ready page:load ajax:complete", function() {
		return GD.initializeFileUploads($(document))
	}), $(document).on("mouseenter", ".field-attachment-field .attachment:not(.error)", function() {
		return $(this).find(".web-actions").show()
	}), $(document).on("mouseleave", ".field-attachment-field .attachment:not(.error)", function() {
		return $(this).find(".web-actions").hide()
	})
}.call(this), function() {
	var e;
	e = function(e) {
		var t, n;
		return n = $.trim(e.val()), t = e.attr("name").match(/\[(.+)\]$/)[1], [n, t]
	}, $(document).on("click", "[data-role='verification_sender'] a.send-token-link:not(.disabled):not(.preview)", function() {
		var t, n, r, i, s, o, u;
		u = $(this).data("url");
		if (!u) return;
		return GD.clearErrorMessageBelow($(this)), s = e($(this).closest("[data-role='verification_sender']").find(".mobile-input")), r = s[0], i = s[1], GD.isMobileNumber(r) ? (o = {
			mobile: r,
			field_api_code: i
		}, t = $(this).closest("form").find("[name='authenticity_token']").val(), n = {
			field_verification: o,
			authenticity_token: t,
			form_id: $(this).data("form-id")
		}, (new GD.SMSSender($(this), $(this).data("url"))).send(n)) : GD.showErrorMessageBelow($(this), l("%warn_wrong_mobile_number"))
	})
}.call(this), function() {
	var e = [].indexOf ||
	function(e) {
		for (var t = 0, n = this.length; t < n; t++) if (t in this && this[t] === e) return t;
		return -1
	};
	window.GD || (window.GD = {}), $(function() {
		var t, n, r;
		return r = function(e) {
			var t, n, r, i, s;
			n = $("[name='entry[" + e + "]']:visible:not(.logic-hidden)").filter(":radio:checked, :not(:radio)").add("[name='entry[" + e + "][]']:visible:checked"), s = [];
			for (r = 0, i = n.length; r < i; r++) t = n[r], s.push($(t).val());
			return s
		}, n = function() {
			function e(e, t) {
				this.apiCode = t, this.targets = [], this.element = $(e).find("[name='entry[" + this.apiCode + "]']"), this.elementContainer = $(e).find(".field[data-api-code='" + this.apiCode + "']"), this.elementContainer.addClass("logic-trigger")
			}
			return e.prototype._informTargets = function(e, t) {
				var n, r, i, s, o;
				t = t != null ? t : !0, i = this.targets, s = [];
				for (n = 0, r = i.length; n < r; n++) o = i[n], s.push(o.set(this.apiCode, e, t));
				return s
			}, e.prototype.run = function(e) {
				return this.element.on("change", function(t) {
					return function(n) {
						t._informTargets(r(t.apiCode), e);
						if (!$(n.target).hasClass("with-other-choice")) return n.stopPropagation()
					}
				}(this)), this.elementContainer.on("change.formLogic", function(e) {
					return function() {
						return e._informTargets(r(e.apiCode), !1)
					}
				}(this))
			}, e
		}(), t = function() {
			function t(e, t, n) {
				var i, s, o, u, a;
				this.apiCode = t, this.triggerConditions = n, this.element = $(e).find("[name^='entry[" + this.apiCode + "]']"), this.elementContainer = $(e).find(".field[data-api-code='" + this.apiCode + "']"), u = this.element;
				for (s = 0, o = u.length; s < o; s++) i = u[s], $(i).data("disabled", $(i).prop("disabled"));
				for (a in this.triggerConditions) this.set(a, r(a), !1)
			}
			return t.prototype._onElementContainerShowHide = function() {
				return this.elementContainer.trigger("change.formLogic"), GD.recalcFormHeight(), $(window).trigger("formContentShowHide")
			}, t.prototype.set = function(t, n, r) {
				var i, s, o, u;
				r == null && (r = !0), u = (s = this.elementContainer.data("triggered-by")) != null ? s : [], n && _.intersection(n, this.triggerConditions[t]).length > 0 ? e.call(u, t) < 0 && u.push(t) : u = _.without(u, t), this.elementContainer.data("triggered-by", u), i = !this.elementContainer.hasClass("logic-hidden"), o = u.length > 0, o && !i && (this.elementContainer.removeClass("logic-hidden"), this.elementContainer.slideDown(r ? "fast" : 0, function(e) {
					return function() {
						var t, n, r, i;
						e.elementContainer.trigger("shown.logic"), autosize(e.elementContainer.find("textarea")), i = e.element;
						for (n = 0, r = i.length; n < r; n++) t = i[n], $(t).data("disabled") || $(t).prop("disabled", !1);
						return e._onElementContainerShowHide()
					}
				}(this)));
				if (!o && i) return this.elementContainer.addClass("logic-hidden"), this.elementContainer.slideUp(r ? "fast" : 0, function(e) {
					return function() {
						return e.elementContainer.trigger("hidden.logic"), e.element.prop("disabled", !0), e._onElementContainerShowHide()
					}
				}(this))
			}, t
		}(), GD.FormLogic = function() {
			function e(e, r) {
				var i, s, o, u, a;
				r == null && (r = document), this.triggers = {}, this.targets = {};
				for (o in e) {
					a = e[o], (i = this.targets)[o] || (i[o] = new t(r, o, a));
					for (u in a)(s = this.triggers)[u] || (s[u] = new n(r, u)), this.triggers[u].targets.push(this.targets[o])
				}
			}
			return e.prototype.run = function(e) {
				var t, n, r, i;
				r = this.triggers, i = [];
				for (t in r) n = r[t], i.push(n.run(e));
				return i
			}, e
		}(), GD.initFormLogic = function(e, t) {
			return t == null && (t = document), (new GD.FormLogic(e, t)).run()
		}
	})
}.call(this), function() {
	$(function() {
		var e, t, n;
		return e = function() {
			function e(e) {
				var t, n, r, i, s;
				s = e.sku, this.fieldApiCode = e.fieldApiCode, this.goodsApiCode = e.goodsApiCode, s == null && (s = {}), this.specification = (n = s.specification) != null ? n : {}, this.price = parseFloat((r = s.price) != null ? r : e.price), t = (i = s.inventory) != null ? i : e.inventory, this.inventory = t != null ? parseInt(t) : null
			}
			return e.prototype.priceDisplay = function() {
				return "<span class='currency'>&yen;</span>" + this.price.toFixed(2)
			}, e.prototype.inventoryDisplay = function(e, t) {
				return this.inventory == null ? "" : this.inventory > 0 ? e + " " + this.inventory + " " + t : l("%sold_out")
			}, e
		}(), t = function() {
			function t(e, t) {
				this.$el = e, this.shoppingCart = t, this.initialPriceDisplay = this.$el.find(".price").html(), this.initialInventoryDisplay = this.$el.find(".inventory").html(), this.unit = this.$el.data("unit"), this._initializeCurrentSpecification(), this._initializeGoodsSKUs(), this._bindOnDimensionChangeEvent(), this._bindOnIncreaseDecreaseClickEvent(), this._bindOnNumberChangeEvent(), this._bindShowHideEvent(), (this.$el.is(":visible") || this._mobileGoodsVisible()) && this._updateCurrentGoodsSKU()
			}
			return t.prototype._addGoodsSKU = function(t, n, r, i, s) {
				var o;
				return r == null && (r = null), i == null && (i = null), s == null && (s = null), o = new e({
					fieldApiCode: t,
					goodsApiCode: n,
					sku: r,
					price: i,
					inventory: s
				}), this.goodsSKUs.push(o)
			}, t.prototype._initializeGoodsSKUs = function() {
				var e, t, n, r, i, s, o, u, a;
				this.goodsSKUs = [], s = this.$el.find("input.number"), e = s.data("field-api-code"), t = s.data("goods-api-code"), a = this.$el.find(".dimensions").data("skus");
				if (!_.isEmpty(a)) for (n = 0, i = a.length; n < i; n++) u = a[n], this._addGoodsSKU(e, t, u);
				if (this.goodsSKUs.length === 0) return o = s.data("goods-price"), r = s.data("inventory"), this._addGoodsSKU(e, t, null, o, r), this._updateNumberInput(), this._updateDecreaseIncreaseBtn(0)
			}, t.prototype._matchedGoodsSKU = function() {
				return _.find(this.goodsSKUs, function(e) {
					return function(t) {
						return _.isEqual(t.specification, e.currentSpecification)
					}
				}(this))
			}, t.prototype._updateNumberInput = function() {
				var e, t;
				return t = this.$el.find("input.number"), e = this._matchedGoodsSKU(), t.prop("disabled", e == null || e.inventory != null && e.inventory === 0)
			}, t.prototype._updateDecreaseIncreaseBtn = function(e) {
				var t, n, r;
				r = this._matchedGoodsSKU(), n = $(this.$el.find(".increase")), t = $(this.$el.find(".decrease")), n.toggleClass("disabled", r == null || r.inventory != null && r.inventory <= e), t.toggleClass("disabled", e <= 0);
				if (GD.isLowVersionIE()) return n.html(n.html()), t.html(t.html())
			}, t.prototype._updatePriceAndInventoryDisplay = function() {
				var e;
				return e = this._matchedGoodsSKU(), e != null ? (this.$el.find(".price").html(e.priceDisplay()), this.$el.find(".inventory").html(e.inventoryDisplay(this.$el.data("inventory-label"), this.$el.data("unit")))) : (this.$el.find(".price").html(this.initialPriceDisplay), this.$el.find(".inventory").html(this.initialInventoryDisplay))
			}, t.prototype._bindOnDimensionChangeEvent = function() {
				var e;
				return e = this, this.$el.find(".dimension-options input:radio").change(function() {
					var t, n, r;
					return n = e.$el.find("input.number"), n.val(0).change(), t = $(this).closest(".dimension-options"), t.find("label.selected").removeClass("selected"), r = t.find("input:radio:checked"), r.next("label").addClass("selected"), e.currentSpecification[r.data("dimension")] = r.val(), e._updatePriceAndInventoryDisplay(), e._updateNumberInput(), e._updateDecreaseIncreaseBtn(0)
				}), this.$el.find(".dimension-options label").click(function() {
					var t;
					return t = $(this).attr("for"), e.$el.find("#" + t).prop("checked", !0).trigger("change")
				})
			}, t.prototype._bindOnIncreaseDecreaseClickEvent = function() {
				var e;
				return e = this, this.$el.find(".number-container a").click(function(t) {
					var n, r, i;
					if ($(this).hasClass("disabled")) return !1;
					t.preventDefault(), r = e._matchedGoodsSKU();
					if (r != null) {
						i = e._currentNumber(), n = $(this).is(".increase") ? i + 1 : i - 1;
						if (n >= 0 && !(r.inventory != null && n > r.inventory)) return e.$el.find("input.number").val(n).trigger("change")
					}
				})
			}, t.prototype._bindOnNumberChangeEvent = function() {
				var e;
				return e = this, this.$el.find("input.number").on("keydown", function(e) {
					if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 || e.keyCode >= 35 && e.keyCode <= 40) return;
					if ((e.shiftKey || e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105)) return e.preventDefault()
				}), this.$el.find("input.number").change(function() {
					var t, n;
					return t = $(this).val(), t && $.isNumeric(t) && t > 0 ? $(this).val(parseInt(t)) : $(this).val(0), n = e._matchedGoodsSKU(), n != null && n.inventory != null && t > n.inventory && $(this).val(n.inventory), e._updateDecreaseIncreaseBtn(t), e._addCurrentGoodsSKUsToShoppingCart()
				})
			}, t.prototype._bindShowHideEvent = function() {
				return this.$el.on("shown.logic", function(e) {
					return function() {
						return e._addCurrentGoodsSKUsToShoppingCart()
					}
				}(this)), this.$el.on("hidden.logic", function(e) {
					return function() {
						return e._updateShoppingCart(0)
					}
				}(this))
			}, t.prototype._addCurrentGoodsSKUsToShoppingCart = function(e) {
				return e == null && (e = !1), this._updateShoppingCart(this._currentNumber(), e)
			}, t.prototype._updateShoppingCart = function(e, t) {
				var n;
				n = this._matchedGoodsSKU();
				if (n != null) return this.shoppingCart.updateGoodsSKUNumber(n, e, this.unit, t), this.$el.toggleClass("active", e > 0)
			}, t.prototype._updateCurrentGoodsSKU = function() {
				return this._addCurrentGoodsSKUsToShoppingCart(!0), this._updatePriceAndInventoryDisplay(), this._updateNumberInput(), this._updateDecreaseIncreaseBtn(this._currentNumber())
			}, t.prototype._initializeCurrentSpecification = function() {
				var e, t, n, r, i;
				this.currentSpecification = {}, n = this.$el.find(".dimensions input:radio:checked"), r = [];
				for (e = 0, t = n.length; e < t; e++) i = n[e], r.push(this.currentSpecification[$(i).data("dimension")] = $(i).val());
				return r
			}, t.prototype._currentNumber = function() {
				return parseInt(this.$el.find("input.number").val())
			}, t.prototype._mobileGoodsVisible = function() {
				return GD.isPhone && this.$el.parents(".field [data-role=collapsible]").is(":visible")
			}, t
		}(), n = function() {
			function e() {
				this.goodsList = [], this.eventHandlers = {}
			}
			return e.prototype.on = function(e, t) {
				return this.eventHandlers[e] = t
			}, e.prototype.trigger = function(e) {
				var t;
				t = this.eventHandlers[e];
				if (t != null && typeof t == "function") return t(this)
			}, e.prototype.updateGoodsSKUNumber = function(e, t, n, r) {
				var i;
				r == null && (r = !1), i = _.find(this.goodsList, function(t) {
					return _.isEqual(t.goodsSKU, e)
				}), i != null && this.goodsList.splice(_.indexOf(this.goodsList, i), 1), t > 0 && this.goodsList.push({
					goodsSKU: e,
					number: t,
					unit: n
				});
				if (!r) return this.trigger("change")
			}, e.prototype.totalPrice = function() {
				var e, t, n, r, i;
				i = 0, r = this.goodsList;
				for (t = 0, n = r.length; t < n; t++) e = r[t], i += e.goodsSKU.price * e.number;
				return i
			}, e.prototype._findGoodsByFieldApiCode = function(e) {
				return _.filter(this.goodsList, function(t) {
					return t.goodsSKU.fieldApiCode === e
				})
			}, e.prototype.clearOnField = function(e) {
				var t, n, r, i;
				i = this._findGoodsByFieldApiCode(e);
				for (n = 0, r = i.length; n < r; n++) t = i[n], this.goodsList.splice(this.goodsList.indexOf(t), 1);
				return this.trigger("change")
			}, e
		}(), GD.initGoods = function(e) {
			var r, i, s, o, u, a, f, c;
			f = new n, r = function(t) {
				var n;
				return n = $(e).find(".fields .field[data-api-code='" + t + "'] .field-label"), $.trim(n.clone().children().remove().end().text())
			}, i = function(t) {
				var n, r, i;
				return n = $(e).find(".field[data-api-code='" + t.fieldApiCode + "'] [data-goods-api-code='" + t.goodsApiCode + "']").parents("[data-role=goods]"), r = $.trim(n.find(".name").text()), i = n.find(".dimensions input:radio:checked").map(function(e, t) {
					return $.trim($(t).next("label").text())
				}).get().join(l("%common_separator")), i === "" ? "" + r : r + "（" + i + "）"
			}, c = function(t, n) {
				var s, o, u, a, f, c, h, p, d, v, m, g;
				n == null && (n = "update");
				if (_.isEmpty(t.goodsList)) $(e).find("#shopping_cart").empty();
				else {
					g = $("<div class='content'></div>"), m = t.goodsList;
					for (h = 0, p = m.length; h < p; h++) a = m[h], c = a.goodsSKU, u = c.fieldApiCode, s = g.find(".cart-field[data-api-code='" + u + "']"), s.length > 0 ? (o = s, f = o.find("ul")) : (o = $("<div class='cart-field' data-api-code='" + u + "'></div>"), o.append("<div class='cart-field-label'>" + r(u) + "：</div> "), f = $("<ul></ul>"), o.append(f), g.append(o)), d = $("<li></li>"), d.append("<span class='goods-name'>" + i(c) + "</span>"), d.append("<span class='price-number'><span class='currency'>&yen;</span>" + c.price.toFixed(2) + "/" + a.unit + " &times; " + a.number + "</span>"), d.append("<span class='price-result'><span class='currency'>&yen;</span>" + (c.price * a.number).toFixed(2) + "</span>"), f.append(d);
					g.append("<div class='summary'>" + l("%total") + "<span class='total-price'><span class='currency'>&yen;</span>" + t.totalPrice().toFixed(2) + "</span></div>"), $(e).find("#shopping_cart").html(g)
				}
				return v = _.isEmpty(t.goodsList) && $("[data-role=goods]").closest(".form-group").find(".field-label:visible").length > 0, $(".submit-field.payment input.submit").data("no-goods-selected", v), typeof GD.updateSubmitBtn == "function" && GD.updateSubmitBtn(), typeof GD.recalcFormHeight == "function" ? GD.recalcFormHeight() : void 0
			};
			if ($(e).find("[data-role=goods]").length > 0) {
				f.on("change", c), a = $(e).find("[data-role=goods]");
				for (o = 0, u = a.length; o < u; o++) s = a[o], new t($(s), f);
				return c(f, "init"), $(e).on("hidden.logic", ".field[data-api-code]", function() {
					return f.clearOnField($(this).data("api-code"))
				}), $(e).on("shown.logic", ".field[data-api-code]", function() {
					var e, t, n, r, i;
					r = $(this).find("[data-role=goods] input.number"), i = [];
					for (t = 0, n = r.length; t < n; t++) e = r[t], $(e).val() > 0 ? i.push($(e).trigger("change")) : i.push(void 0);
					return i
				})
			}
		}, $(document).on("ready page:load", function() {
			if (!($(".dashboard").length > 0)) return GD.initGoods(document)
		})
	})
}.call(this), function() {
	window.GD || (window.GD = {}), GD.initLikert = function(e) {
		return $(e).on("click", ".likert td", function(e) {
			var t;
			if ($("#form_design").length > 0 || $(e.target).is("input:radio")) return;
			t = $(this).find("input:radio");
			if (t.length > 0) return t.prop("checked", !0)
		})
	}, $(document).on("ready page:load", function() {
		if (!($(".dashboard").length > 0)) return GD.initLikert(document)
	})
}.call(this), function() {
	var e, t;
	t = function(e, t) {
		var n;
		return n = $(e).closest(".field").find("input.other-choice-input"), $(e).find(".other-choice-item").is(":selected") ? (n.show(), n.removeClass("gd-hide"), t && n.focus(), n.closest(".ui-input-text").show()) : (n.hide(), n.addClass("gd-hide"), n.closest(".ui-input-text").hide())
	}, e = function(e) {
		return function(e, t) {
			return $(t).closest(".other-choice-area").find("input." + e)
		}
	}(this), GD.initialDropdownOtherChoice = function(e, n) {
		return $(e).find(".field select.with-other-choice").each(function() {
			return t(this, n)
		})
	}, $(document).on("ready page:load", function() {
		return GD.initialDropdownOtherChoice(document, !1), $(".entry form, #new_entry").on("submit", function() {
			return $(this).find("input.other_choice").each(function() {
				if (!$(this).is(":checked")) return e("other-choice-input", this).val("")
			}), $(this).find("option.other-choice-item").each(function() {
				if (!$(this).is(":selected")) return $(this).closest(".field").find("input.other-choice-input").val("")
			})
		})
	}), $(document).on("click", ".other-choice-input", function() {
		var t;
		t = e("other_choice", this);
		if (!t.is(":checked")) {
			t.click();
			if (t.checkboxradio != null) return $("input[type=radio], input[type=checkbox]").checkboxradio("refresh")
		}
	}), $(document).on("click", "input.other_choice", function() {
		$(this).checkboxradio != null && $(this).checkboxradio("refresh");
		if ($(this).is(":checked")) return e("other-choice-input", this).focus()
	}), $(document).on("change", "form .field select.with-other-choice", function() {
		return t($(this), !0)
	})
}.call(this), function() {
	$(function() {
		var e, t, n, r, i, s;
		return i = !0, r = function(e, t, n, r) {
			$(e).find(".rating-group i").removeClass("hover");
			if (r !== "") return $(e).find(".rating-group[data-field-id=" + t + "] i:lt(" + r + ")").addClass("highlight"), $(e).find(".rating-group[data-field-id=" + t + "] i:gt(" + (r - 1) + ")").removeClass("highlight")
		}, e = function(e) {
			return $(e).off("click touchend", ".rating-group i"), $(e).on("click touchend", ".rating-group i", function(t) {
				var n, s, o;
				return t.stopPropagation(), i = !1, n = $(this).parents(".rating-group").data("field-id"), s = $(this).parents(".rating-group").data("rating-type"), o = $(this).data("value"), $(e).find("#entry_" + n).val($(this).data("value")), r(e, n, s, o)
			})
		}, t = function(e) {
			return $(e).off("mouseenter touchstart", ".rating-group i"), $(e).on("mouseenter touchstart", ".rating-group i", function(t) {
				var n, r, s;
				t.stopPropagation(), GD.isMobile && (i = !0);
				if (i) return n = $(this).parents(".rating-group").data("field-id"), r = $(this).parents(".rating-group").data("rating-type"), s = $(this).data("value"), $(e).find(".rating-group[data-field-id=" + n + "] i:lt(" + s + ")").removeClass("highlight").addClass("hover"), $(e).find(".rating-group[data-field-id=" + n + "] i:gt(" + (s - 1) + ")").removeClass("highlight hover")
			})
		}, n = function(e) {
			return $(e).off("mouseleave touchend", ".rating-group"), $(e).on("mouseleave touchend", ".rating-group", function(t) {
				var n;
				return t.stopPropagation(), n = $(e).find("#entry_" + $(this).data("field-id")).val(), r(e, $(this).data("field-id"), $(this).data("rating-type"), n), i = !0
			})
		}, s = function(e) {
			var t, n, r, i, s, o;
			s = $(e).find(".rating-group"), o = [];
			for (n = 0, r = s.length; n < r; n++) i = s[n], t = $(i).find("#entry_" + $(i).data("field-id")).val(), t ? o.push($(i).find("i[data-value='" + t + "']").click()) : o.push(void 0);
			return o
		}, GD.initRating = function(r) {
			return r == null && (r = document), t(r), e(r), n(r), s(r)
		}, $(document).on("ready page:load", function() {
			if (!($(".dashboard").length > 0)) return GD.initRating(document)
		})
	})
}.call(this), function() {
	window.GD || (window.GD = {}), GD.SMSSender = function() {
		function e(e, t) {
			this.$trigger = e, this.url = t
		}
		return e.prototype.send = function(e) {
			if (this.url) return GD.countDown(this.$trigger, 60), $.post(this.url, e, function(e) {
				return function(t) {
					return e.$trigger.parent().after(t)
				}
			}(this)).fail(function(e) {
				return function(t) {
					return GD.showErrorMessageBelow(e.$trigger, t.responseText), GD.enableBtn(e.$trigger)
				}
			}(this))
		}, e
	}()
}.call(this), function() {
	window.GD || (window.GD = {}), GD.FormPagination = function() {
		function e(e) {
			this.options = e != null ? e : {}, this.container = this.options.container, this.fieldsContainer = $(this.container.find(".fields")), this.currentPage = 1, this.pageCount = 1, this._init()
		}
		return e.prototype.onFormContentShowHide = function() {
			return this._updatePageDisplayedIndex(), this._render()
		}, e.prototype._render = function() {
			var e;
			this.fieldsContainer.find(".form-page").hide(), this._updatePageButton(), e = this.container.find(".field.submit-field"), this.container.find(".form-description").toggleClass("hide", this._hasPreviousPage()), e.find(".page-number").html(l("%page_number", {
				currentPage: this.currentPage,
				totalPage: this.pageCount
			})).toggleClass("hide", this._hasOnlyOnePage()), this.container.find(".field.submit-field .submit, #shopping_cart, .captcha-container").toggleClass("hide", this._hasNextPage()), e.toggleClass("has-pagination-action", !this._hasOnlyOnePage()), this.fieldsContainer.find(".form-page[data-page-index=" + this.currentPage + "]").show();
			if (GD.isMobile) return $(window).resize()
		}, e.prototype._updatePageButton = function() {
			var e, t, n, r;
			return n = this.container.find(".pagination-action.previous-page"), t = this.container.find(".pagination-action.next-page"), e = $(this.container.find(".field-page-break")[this.currentPage - 1]), n.text(e.data("previous-page-text")), t.text(e.data("next-page-text")), r = !this._hasPreviousPage() || e.data("disable-previous-page") === !0, n.toggleClass("hide", r), t.toggleClass("hide", !this._hasNextPage())
		}, e.prototype._goToPreviousPage = function() {
			if (!this._hasPreviousPage()) return;
			return this.currentPage--, this._render(), $(window).scrollTop(0), this._resetPaginationActionBtns()
		}, e.prototype._goToNextPage = function() {
			var e;
			if (!this._hasNextPage()) return;
			return e = this.fieldsContainer.closest("form").data("validate-url"), e ? this._validateCurrentPageOnClient() && this._validateCurrentPage(e, function(e) {
				return function() {
					return e._doGoToNextPage()
				}
			}(this), function(e) {
				return function() {
					return $(window).scrollTop(0)
				}
			}(this)) : this._doGoToNextPage(), this._resetPaginationActionBtns()
		}, e.prototype._doGoToNextPage = function() {
			return this.currentPage++, this._render(), $(window).scrollTop(0)
		}, e.prototype._hasPreviousPage = function() {
			return this.currentPage > 1
		}, e.prototype._hasNextPage = function() {
			return this.currentPage < this.pageCount
		}, e.prototype._hasOnlyOnePage = function() {
			return this.pageCount <= 1
		}, e.prototype._validateCurrentPage = function(e, t, n) {
			var r, i;
			return i = this._getFieldsDataToValidate(), r = $("meta[name=csrf-token]").attr("content"), $.ajax(e, {
				type: "post",
				data: $.param(i),
				beforeSend: function(e) {
					return e.setRequestHeader("X-CSRF-Token", r)
				},
				success: function(e) {
					return function(n, r, i) {
						return e._clearFieldsErrorMessages(), t()
					}
				}(this),
				error: function(e) {
					return function(t) {
						var r;
						if (t.status === 400) return r = $.parseJSON(t.responseText), e._showErrorMessages(r.errors), n()
					}
				}(this)
			})
		}, e.prototype._validateCurrentPageOnClient = function(e, t) {
			return e == null && (e = null), t == null && (t = null), GD.clientValidator.validate()
		}, e.prototype._getFieldsDataToValidate = function() {
			var e, t, n, r, i;
			return t = $(this.fieldsContainer.find(".form-page[data-page-index=" + this.currentPage + "]")).find("input, textarea, select"), i = _.reject(this.fieldsContainer.find(".logic-trigger"), function(e) {
				return function(t) {
					var n;
					return n = $(t).closest(".form-page").data("page-index"), !n || n >= e.currentPage
				}
			}(this)), r = t.add(typeof(e = $(i)).find == "function" ? e.find("input, textarea, select") : void 0), n = r.serializeArray(), n = this._appendUncheckedInputsData(n, r), n
		}, e.prototype._appendUncheckedInputsData = function(e, t) {
			return _.each(t, function(t) {
				return function(t) {
					var n, r, i;
					r = _.pluck(e, "name"), i = $(t).attr("name");
					if (($(t).is("input:radio") || $(t).is("input:checkbox")) && !_.contains(r, i)) {
						n = i.replace(/\[\]$/, "");
						if (!_.contains(r, n)) return e.push({
							name: n,
							value: ""
						})
					}
				}
			}(this)), e
		}, e.prototype._showErrorMessages = function(e) {
			return this._clearFieldsErrorMessages(), _.each(e, function(e) {
				return function(t, n) {
					var r, i;
					return r = t[0], i = t[1], e._addErrorMessageToField(r, i)
				}
			}(this))
		}, e.prototype._clearFieldsErrorMessages = function() {
			var e;
			return e = this.fieldsContainer.find(".form-page[data-page-index=" + this.currentPage + "] .field.has-error"), e.removeClass("has-error"), e.find("> .inline-error").remove(), e.find(".field_with_errors").children().unwrap()
		}, e.prototype._addErrorMessageToField = function(e, t) {
			var n, r, i;
			return n = e.match(/^field_\d+/), i = this.fieldsContainer.find("[data-api-code=" + n + "]"), i.addClass("has-error"), this._wrapErrorInput(i, e, n), r = i.find("> .inline-error"), r.length > 0 ? r.append("" + l("%common_separator") + t) : i.append($("<div class='help-block inline-error'><i class='gd-icon-times-circle'></i> " + t + "</div>"))
		}, e.prototype._wrapErrorInput = function(e, t, n) {
			var r, i;
			return r = e.find("[name^='" + this._inputName(t, n) + "']"), i = ".radio-button-wrapper, .check-box-wrapper, .dropdown-wrapper", r.parent().is(i) && (r = r.parent(i)), r.wrap('<div class="field_with_errors"></div>')
		}, e.prototype._inputName = function(e, t) {
			var n, r, i, s, o;
			i = "entry[" + t + "]", s = e.replace(t, "").split("_");
			for (n = 0, r = s.length; n < r; n++) o = s[n], o && (i += "[" + o + "]");
			return i
		}, e.prototype._wrapFieldsInPage = function() {
			return _.each(this.fieldsContainer.find(".field-page-break"), function(e) {
				return function(e, t, n) {
					var r, i, s;
					return i = t + 1 === n.length, r = i ? $(e).nextAll().addBack() : $(e).nextUntil(".field-page-break", ".field:not(.field-page-break)").addBack(), s = $("<div class='form-page'></div>"), r.wrapAll(s)
				}
			}(this)), this.container.find(".field.submit-field").addClass("has-form-pagination")
		}, e.prototype._updatePageDisplayedIndex = function() {
			var e;
			return e = 0, _.each(this.fieldsContainer.find(".form-page"), function(t) {
				return function(t) {
					return $(t).find(".field:not(.logic-hidden):not(.field-page-break)").length > 0 ? (e++, $(t).attr("data-page-index", e)) : $(t).removeAttr("data-page-index")
				}
			}(this)), this.pageCount = e
		}, e.prototype._goToFirstErrorPage = function() {
			var e;
			e = this.fieldsContainer.find(".field.has-error");
			if (e.length > 0) return this.currentPage = $(e[0]).closest(".form-page").attr("data-page-index") || 1
		}, e.prototype._init = function() {
			return this._wrapFieldsInPage(), this._updatePageDisplayedIndex(), this._goToFirstErrorPage(), this._bindEvents(), this._render()
		}, e.prototype._bindEvents = function() {
			return this.container.find(".pagination-action.previous-page:not(.working)").click(function(e) {
				return function() {
					return e._goToPreviousPage()
				}
			}(this)), this.container.find(".pagination-action.next-page:not(.working)").click(function(e) {
				return function() {
					return e._goToNextPage()
				}
			}(this))
		}, e.prototype._resetPaginationActionBtns = function() {
			return this.container.find(".pagination-action.working").removeClass("working")
		}, e
	}(), GD.initFormPagination = function(e) {
		e == null && (e = null), e || (e = $("body"));
		if (!($(e).find(".fields .field-page-break").length > 0)) return GD.formPagination = null;
		if ($(e).find(".form-page").length === 0) return GD.formPagination = new GD.FormPagination({
			container: e
		})
	}, $(window).on("formContentShowHide", function() {
		if (GD.formPagination) return GD.formPagination.onFormContentShowHide()
	})
}.call(this), function() {}.call(this), function() {
	window.GD || (window.GD = {}), GD.isLowVersionIE = function() {
		return Detectizr.browser.name === "ie" && parseInt(Detectizr.browser.version, 10) < 10
	}
}.call(this), function() {
	window.GD || (window.GD = {}), GD.ShareIt = function() {
		function e() {}
		return e.prototype.setEntry = function(e) {
			return this.title = encodeURIComponent(e.title || document.title), this.url = encodeURIComponent((e.url || document.location).replace("https://", "http://")), this.pic = encodeURIComponent(e.pic || ""), this.description = encodeURIComponent(e.description || "")
		}, e.prototype.weibo = function() {
			var e, t, n;
			return e = 1843447738, t = "http://v.t.sina.com.cn/share/share.php?appkey=" + e + "&title=" + this.title + "&url=" + this.url + "&pic=" + this.pic, n = "width=700,height=480, top=" + (screen.height - 430) / 2 + ", left=" + (screen.width - 440) / 2 + ", toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=1, status=no", window.open(t, "分享到微博", n)
		}, e.prototype.qqmb = function() {
			var e, t;
			return e = "http://v.t.qq.com/share/share.php?title=" + this.title + "&url=" + this.url + "&pic=" + this.pic, t = "width=600, height=480, top=" + (screen.height - 700) / 2 + ", left=" + (screen.width - 580) / 2 + ", toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no", window.open(e, "转播到腾讯微博", t)
		}, e.prototype.qzone = function() {
			var e, t, n;
			return e = this.title + " - " + this.description, t = "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title=" + e + "&url=" + this.url, n = "width=700, height=600, top=" + (screen.height - 700) / 2 + ", left=" + (screen.width - 600) / 2 + ", toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no", window.open(t, "分享到QZONE", n)
		}, e.prototype.renren = function() {
			var e, t;
			return e = "http://widget.renren.com/dialog/share?title=" + this.title + "&resourceUrl=" + this.url + "&images=" + this.pic + "&description=" + this.description, t = "width=700, height=600, top=" + (screen.height - 700) / 2 + ", left=" + (screen.width - 600) / 2 + ", toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no", window.open(e, "分享到人人", t)
		}, e.prototype.mingdao = function() {
			var e, t, n;
			return e = "76AE474AF1B8DC75A387FEC4EECF4CAB", t = "http://www.mingdao.com/share?title=" + this.title + "&url=" + this.url + "&pic=" + this.pic + "&appkey=" + e, n = "width=700, height=600, top=" + (screen.height - 700) / 2 + ", left=" + (screen.width - 600) / 2 + ", toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no", window.open(t, "分享到明道", n)
		}, e
	}(), window.shareIt = new GD.ShareIt
}.call(this), function() {
	window.GD || (window.GD = {}), GD.initWxjs = function(e) {
		return wx.config({
			debug: !1,
			appId: e.appid,
			timestamp: e.timestamp,
			nonceStr: e.noncestr,
			signature: e.signature,
			jsApiList: ["checkJsApi", "onMenuShareTimeline", "onMenuShareAppMessage", "chooseWXPay"]
		}), wx.ready(function() {
			var e, t, n, r, i;
			return $(".wxpay-loading-container").remove(), t = $("#form_share_info"), e = null, $("#form_thumbnail_selected").length > 0 && (e = $("#form_thumbnail_selected img")), e || $("img").each(function() {
				var t;
				t = $(this);
				if (!e && (t.width() >= 300 && t.height() >= 300 || t.parents("#form_thumbnail_default").length > 0)) return e = t
			}), n = e ? e[0].src : "", i = t.data("form-name"), r = t.data("form-url"), wx.onMenuShareTimeline({
				title: i,
				link: r,
				imgUrl: n
			}), wx.onMenuShareAppMessage({
				title: i,
				desc: $.trim(t.text()).substring(0, 100),
				link: r,
				imgUrl: n
			})
		})
	}
}.call(this), function() {
	GD.Field = function() {
		function e(e) {
			this.field = e, this.apiCode = this.field.data("api-code"), this.label = $.trim(this.field.data("label")), this.type = $.trim(this.field.data("type")), this.validations = this.buildValidations(), this.EmailField_REGEXP = /^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/, this.LinkField_REGEXP = /^(http:|https:)\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/, this.TelephoneField_REGEXP = /^(\d{3,4}|\d{3,4}-)?\d{7,8}$/, this.MobileField_REGEXP = /^1\d{10}$/
		}
		return e.prototype.buildValidations = function() {
			var e;
			return e = _.filter(this.field.data("validations"), function(e) {
				return function(e) {
					return GD[e + "Validation"]
				}
			}(this)), _.map(e, function(e) {
				return function(t) {
					return new GD[t + "Validation"](e, e.field)
				}
			}(this))
		}, e.prototype.validate = function() {
			var e, t, n, r;
			this.field.removeClass("need-validate"), this.field.removeClass("editing"), n = this.validations;
			for (e = 0, t = n.length; e < t; e++) {
				r = n[e];
				if (!r.validate()) break
			}
			return this.isValid()
		}, e.prototype.isValid = function() {
			return _.every(this.validations, function(e) {
				return e.isValid()
			})
		}, e.prototype.isPresence = function() {
			return this.isPresenceOn(this.fieldName())
		}, e.prototype.isFormatValid = function() {
			var e;
			return e = this.type + "_REGEXP", !this.isPresence() || !this[e] ? !0 : this[e].test(this.value())
		}, e.prototype.valueLength = function() {
			var e;
			return (e = GD.formData[this.fieldName()]) != null ? e.length : void 0
		}, e.prototype.isPresenceOn = function(e) {
			return GD.formData[e] && GD.formData[e].length !== 0
		}, e.prototype.isAllPresenceOf = function(e) {
			return _.every(e, function(e) {
				return function(t) {
					return e.isPresenceOn("entry[" + e.apiCode + "][" + t + "]")
				}
			}(this))
		}, e.prototype.isAllNotPresenceOf = function(e) {
			return _.every(e, function(e) {
				return function(t) {
					return !e.isPresenceOn("entry[" + e.apiCode + "][" + t + "]")
				}
			}(this))
		}, e.prototype.is = function(e) {
			return this.field.data("type") === e
		}, e.prototype.isNumberOf = function(e) {
			return e.length === 0 || GD.isNumber(e)
		}, e.prototype.isNumber = function() {
			return this.isNumberOf(this.value())
		}, e.prototype.value = function() {
			return GD.formData[this.fieldName()]
		}, e.prototype.fieldName = function() {
			return this.is("ImageCheckBox") || this.is("CheckBox") || this.is("AttachmentField") ? "entry[" + this.apiCode + "][]" : "entry[" + this.apiCode + "]"
		}, e
	}()
}.call(this), function() {
	GD.Validation = function() {
		function e(e, t) {
			this.field = e, this.fieldDom = t
		}
		return e.prototype.validate = function() {
			var e;
			return this.clearError(), e = this.isValid(), e || this.showError(), e
		}, e.prototype.showError = function() {
			return this.wrapFieldWithValidationError(), this.appendErrorMessage()
		}, e.prototype.wrapFieldWithValidationError = function() {
			var e, t;
			this.fieldDom.addClass("has-error");
			if (this.needWrapWithErrorClass()) return e = this.fieldDom.find(".field-content"), t = $("<div class='field_with_errors'></div>").append(e.children()), e.append(t)
		}, e.prototype.appendErrorMessage = function(e) {
			return this.fieldDom.append($("<div class='help-block inline-error'><i class='gd-icon-times-circle'></i>" + (" " + this.errorMessage()) + "</div>"))
		}, e.prototype.needWrapWithErrorClass = function() {
			return this.field.is("TextField") || this.field.is("AddressField") || this.field.is("NumberField")
		}, e.prototype.clearError = function() {
			var e;
			if (!this.fieldDom.hasClass("has-error")) return;
			return this.fieldDom.removeClass("has-error"), this.needWrapWithErrorClass() && (e = this.fieldDom.find(".field_with_errors"), this.fieldDom.find(".field-content").append(e.children()), e.remove()), this.fieldDom.find(".inline-error").remove()
		}, e
	}()
}.call(this), function() {
	var e = function(e, n) {
			function i() {
				this.constructor = e
			}
			for (var r in n) t.call(n, r) && (e[r] = n[r]);
			return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
		},
		t = {}.hasOwnProperty;
	GD.RangeValidation = function(t) {
		function n() {
			return n.__super__.constructor.apply(this, arguments)
		}
		return e(n, t), n.prototype.isBetween = function(e, t, n) {
			return t && n ? t <= e && e <= n : t ? t <= e : n ? e <= n : !0
		}, n.prototype.isValid = function() {
			return this.field.isPresence() ? this.isInValidRange() : !0
		}, n.prototype.isInValidRange = function() {
			return !0
		}, n.prototype.errorMessage = function() {
			return this.field.label + (this.fieldDom.data("range-hint") || this.fieldDom.data("length-hint"))
		}, n
	}(GD.Validation)
}.call(this), function() {
	var e = function(e, n) {
			function i() {
				this.constructor = e
			}
			for (var r in n) t.call(n, r) && (e[r] = n[r]);
			return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
		},
		t = {}.hasOwnProperty;
	GD.DateRangeValidation = function(t) {
		function n() {
			return n.__super__.constructor.apply(this, arguments)
		}
		return e(n, t), n.prototype.isInValidRange = function() {
			var e, t, n, r, i;
			return i = this.fieldDom.find(".field-content input").attr("min"), n = this.fieldDom.find(".field-content input").attr("max"), r = i ? new Date(i) : null, t = n ? new Date(n) : null, e = new Date(this.field.value()), this.isBetween(e, r, t)
		}, n
	}(GD.RangeValidation)
}.call(this), function() {
	var e = function(e, n) {
			function i() {
				this.constructor = e
			}
			for (var r in n) t.call(n, r) && (e[r] = n[r]);
			return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
		},
		t = {}.hasOwnProperty;
	GD.FormatValidation = function(t) {
		function n() {
			return n.__super__.constructor.apply(this, arguments)
		}
		return e(n, t), n.prototype.isValid = function() {
			return this.field.isFormatValid()
		}, n.prototype.errorMessage = function() {
			return this.field.type === "EmailField" ? l("%email_format_error") : this.field.label + l("%format_error")
		}, n
	}(GD.Validation)
}.call(this), function() {
	var e = function(e, n) {
			function i() {
				this.constructor = e
			}
			for (var r in n) t.call(n, r) && (e[r] = n[r]);
			return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
		},
		t = {}.hasOwnProperty;
	GD.LengthValidation = function(t) {
		function n() {
			return n.__super__.constructor.apply(this, arguments)
		}
		return e(n, t), n.prototype.isInValidRange = function() {
			return this.minimumLength = this.fieldDom.data("minimum-length"), this.maximumLength = this.fieldDom.data("maximum-length"), this.isBetween(this.field.valueLength(), this.minimumLength, this.maximumLength)
		}, n
	}(GD.RangeValidation)
}.call(this), function() {
	var e = function(e, n) {
			function i() {
				this.constructor = e
			}
			for (var r in n) t.call(n, r) && (e[r] = n[r]);
			return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
		},
		t = {}.hasOwnProperty;
	GD.NumberRangeValidation = function(t) {
		function n() {
			return n.__super__.constructor.apply(this, arguments)
		}
		return e(n, t), n.prototype.isInValidRange = function() {
			return this.rangeMin = this.fieldDom.data("range-min"), this.rangeMax = this.fieldDom.data("range-max"), this.isBetween(this.field.value(), this.rangeMin, this.rangeMax)
		}, n
	}(GD.RangeValidation)
}.call(this), function() {
	var e = function(e, n) {
			function i() {
				this.constructor = e
			}
			for (var r in n) t.call(n, r) && (e[r] = n[r]);
			return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
		},
		t = {}.hasOwnProperty;
	GD.NumericValidation = function(t) {
		function n() {
			return n.__super__.constructor.apply(this, arguments)
		}
		return e(n, t), n.prototype.isValid = function() {
			return this.field.isNumber()
		}, n.prototype.errorMessage = function() {
			return this.field.label + l("%format_error")
		}, n
	}(GD.Validation)
}.call(this), function() {
	var e = function(e, n) {
			function i() {
				this.constructor = e
			}
			for (var r in n) t.call(n, r) && (e[r] = n[r]);
			return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
		},
		t = {}.hasOwnProperty;
	GD.PresenceValidation = function(t) {
		function n() {
			return n.__super__.constructor.apply(this, arguments)
		}
		return e(n, t), n.prototype.isValid = function() {
			return this.field.isPresence()
		}, n.prototype.errorMessage = function() {
			return l("%presence_error", {
				label: this.field.label
			})
		}, n
	}(GD.Validation)
}.call(this), function() {
	var e = function(e, n) {
			function i() {
				this.constructor = e
			}
			for (var r in n) t.call(n, r) && (e[r] = n[r]);
			return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
		},
		t = {}.hasOwnProperty;
	GD.AddressField = function(t) {
		function n() {
			return n.__super__.constructor.apply(this, arguments)
		}
		return e(n, t), n.prototype.isPresence = function() {
			var e;
			return e = this.field.find("[data-role='address']").data("address-items"), this.isAllPresenceOf(e)
		}, n.prototype.withStreet = function() {
			return this.field.find("[name *='street']:visible").length !== 0
		}, n
	}(GD.Field)
}.call(this), function() {
	var e = function(e, n) {
			function i() {
				this.constructor = e
			}
			for (var r in n) t.call(n, r) && (e[r] = n[r]);
			return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
		},
		t = {}.hasOwnProperty;
	GD.CascadeDropDown = function(t) {
		function n() {
			return n.__super__.constructor.apply(this, arguments)
		}
		return e(n, t), n.prototype.isPresence = function() {
			return this.isAllPresenceOf(["level_1", "level_2"])
		}, n
	}(GD.Field)
}.call(this), function() {
	var e = function(e, n) {
			function i() {
				this.constructor = e
			}
			for (var r in n) t.call(n, r) && (e[r] = n[r]);
			return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
		},
		t = {}.hasOwnProperty;
	GD.CheckBox = function(t) {
		function n() {
			return n.__super__.constructor.apply(this, arguments)
		}
		return e(n, t), n.prototype.valueLength = function() {
			var e;
			return e = GD.formData[this.fieldName()], $.isArray(e) ? e.length : "string" == typeof e ? 1 : void 0
		}, n
	}(GD.Field), GD.ImageCheckBox = function(t) {
		function n() {
			return n.__super__.constructor.apply(this, arguments)
		}
		return e(n, t), n
	}(GD.CheckBox)
}.call(this), function() {
	var e = function(e, n) {
			function i() {
				this.constructor = e
			}
			for (var r in n) t.call(n, r) && (e[r] = n[r]);
			return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
		},
		t = {}.hasOwnProperty;
	GD.GeoField = function(t) {
		function n() {
			return n.__super__.constructor.apply(this, arguments)
		}
		return e(n, t), n.prototype.isPresence = function() {
			return this.isAllPresenceOf(["latitude", "longitude", "address"])
		}, n
	}(GD.Field)
}.call(this), function() {
	var e = function(e, n) {
			function i() {
				this.constructor = e
			}
			for (var r in n) t.call(n, r) && (e[r] = n[r]);
			return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
		},
		t = {}.hasOwnProperty;
	GD.BasicGoodsField = function(t) {
		function n() {
			return n.__super__.constructor.apply(this, arguments)
		}
		return e(n, t), n.prototype.isPresence = function() {
			var e, t, n;
			return e = this.field.find(".goods-items .goods-item").length, _.some(function() {
				n = [];
				for (var t = 0; 0 <= e ? t < e : t > e; 0 <= e ? t++ : t--) n.push(t);
				return n
			}.apply(this), function(e) {
				return function(t) {
					var n;
					return n = "entry[" + e.apiCode + "][" + t + "][number]", GD.formData[n] && GD.formData[n] !== "0"
				}
			}(this))
		}, n
	}(GD.Field), GD.GoodsField = function(t) {
		function n() {
			return n.__super__.constructor.apply(this, arguments)
		}
		return e(n, t), n
	}(GD.BasicGoodsField)
}.call(this), function() {
	var e = function(e, n) {
			function i() {
				this.constructor = e
			}
			for (var r in n) t.call(n, r) && (e[r] = n[r]);
			return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
		},
		t = {}.hasOwnProperty;
	GD.LikertField = function(t) {
		function n() {
			return n.__super__.constructor.apply(this, arguments)
		}
		return e(n, t), n.prototype.isPresence = function() {
			var e;
			return e = this.field.find(".likert").data("statement-api-codes"), _.every(e, function(e) {
				return function(t) {
					return e.isPresenceOn("entry[" + e.apiCode + "][" + t + "]")
				}
			}(this))
		}, n
	}(GD.Field)
}.call(this), function() {
	var e = function(e, n) {
			function i() {
				this.constructor = e
			}
			for (var r in n) t.call(n, r) && (e[r] = n[r]);
			return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
		},
		t = {}.hasOwnProperty;
	GD.MatrixField = function(t) {
		function n() {
			n.__super__.constructor.apply(this, arguments), this.statementApiCodes = this.field.find(".matrix").data("statement-api-codes"), this.dimensionApiCodes = this.field.find(".matrix").data("dimension-api-codes")
		}
		return e(n, t), n.prototype.isPresence = function() {
			return _.every(this.statementApiCodes, function(e) {
				return function(t) {
					return _.every(e.dimensionApiCodes, function(n) {
						return e.isPresenceOn("entry[" + e.apiCode + "][" + t + "][" + n + "]")
					})
				}
			}(this))
		}, n.prototype.isNumber = function() {
			return _.every(this.statementApiCodes, function(e) {
				return function(t) {
					return _.every(e.dimensionApiCodes, function(n) {
						return e.isNumberOf(GD.formData["entry[" + e.apiCode + "][" + t + "][" + n + "]"])
					})
				}
			}(this))
		}, n
	}(GD.Field)
}.call(this), function() {
	var e = function(e, n) {
			function i() {
				this.constructor = e
			}
			for (var r in n) t.call(n, r) && (e[r] = n[r]);
			return i.prototype = n.prototype, e.prototype = new i, e.__super__ = n.prototype, e
		},
		t = {}.hasOwnProperty;
	GD.TimeField = function(t) {
		function n() {
			n.__super__.constructor.apply(this, arguments), this.isIncludeSecond = this.field.find(".time-selects").data("include-second")
		}
		return e(n, t), n.prototype.isPresence = function() {
			return this.isIncludeSecond ? this.isAllPresenceOf(["hour", "minute", "second"]) : this.isAllPresenceOf(["hour", "minute"])
		}, n.prototype.isFormatValid = function() {
			var e;
			return e = this.isIncludeSecond ? ["hour", "minute", "second"] : ["hour", "minute"], this.isAllNotPresenceOf(e) || this.isAllPresenceOf(e)
		}, n
	}(GD.Field)
}.call(this), function() {
	GD.FormClientValidator = function() {
		function e(e) {
			this.form = e, this.fields = this.findOrBuildFields()
		}
		return e.prototype.validate = function() {
			var e;
			return this.fields = this.findOrBuildFields(), this.refreshFormData(), _.each(this.fields, function(e) {
				return e.validate()
			}), e = _.every(this.fields, function(e) {
				return e.isValid()
			}), e || this.highlightFirstErrorField(), e
		}, e.prototype.run = function() {
			return this.form.on("focus, click", ".fields .field input, .fields .field select, .fields .field textarea", function(e) {
				return function(t) {
					return e.markCurrentFieldEditingAndNeedValidate(t)
				}
			}(this)), this.form.on("click", ".fields .field-likert-field .likert table tr td", function(e) {
				return function(t) {
					return e.markCurrentFieldEditingAndNeedValidate(t)
				}
			}(this)), this.form.on("change", ".fields .goods-item input", function(e) {
				return function(t) {
					return e.markCurrentFieldEditingAndNeedValidate(t)
				}
			}(this)), $(document).on("click", function(e) {
				return function(t) {
					return e.validateNeedValidateFields(t)
				}
			}(this)), $(document).on("click", ".fields .field-rating-field .rating-icon", function(e) {
				return function(t) {
					return e.validateNeedValidateFields(t)
				}
			}(this))
		}, e.prototype.markCurrentFieldEditingAndNeedValidate = function(e) {
			var t;
			t = $(e.target).closest(".field"), t.hasClass("need-validate") || t.addClass("need-validate");
			if (!t.hasClass("editing")) return t.addClass("editing")
		}, e.prototype.validateNeedValidateFields = function(e) {
			var t, n;
			t = this.getNeedValidateFeilds(e);
			if (t.length === 0) return;
			return this.refreshFormData(), n = _.map(t, function(e) {
				return function(t) {
					return e.findOrBuildField($(t))
				}
			}(this)), _.each(n, function(e) {
				return e.validate()
			})
		}, e.prototype.getNeedValidateFeilds = function(e) {
			var t, n, r, i;
			return t = $(e.target).parents(".field.editing"), i = "need-validate", r = [], t.length !== 0 ? (n = t.data("api-code"), r = this.form.find(".fields .field." + i + ":visible[data-api-code!='" + n + "']")) : r = this.form.find(".fields .field." + i + ":visible")
		}, e.prototype.findOrBuildFields = function() {
			return _.map(this.form.find(".fields .field:visible"), function(e) {
				return function(t) {
					return e.findOrBuildField($(t))
				}
			}(this))
		}, e.prototype.refreshFormData = function() {
			return GD.formData = this.form.serializeObject()
		}, e.prototype.findOrBuildField = function(e) {
			var t;
			return t = _.find(this.fields || [], function(t) {
				return t.apiCode === e.data("api-code")
			}), t ? t : GD[e.data("type")] ? new(GD[e.data("type")])(e) : new GD.Field(e)
		}, e.prototype.highlightFirstErrorField = function() {
			var e;
			e = $(this.form.find(".field:visible.has-error")[0]);
			if (e.length > 0) return $("html, body").animate({
				scrollTop: e.offset().top
			})
		}, e
	}()
}.call(this), function() {
	var e, t, n;
	GD.showFormSubmitErrorModal = function(e) {
		if ($(".field.has-error").length === 0) return;
		return e.modal("show"), e.on("hide.bs.modal", function() {
			var e;
			e = $($(".field.has-error")[0]);
			if (e.length > 0) return $("html, body").animate({
				scrollTop: e.offset().top
			})
		}), setTimeout(function() {
			return e.modal("hide")
		}, 1e3)
	}, GD.fetchWeixinOauthData = function(e) {
		var t, n, r;
		n = $("#new_entry input[type=submit]");
		if (n.length === 0) return;
		return n.data("weixin-openid-fetching", !0), GD.updateSubmitBtn(), t = 0, r = null, GD.pollingDataFromServer(500, 10, n.data("weixin-oauth-url"), function(e, t) {
			if (e.errmsg) return clearInterval(t), alert("微信信息获取失败，请重新打开表单：" + e.errmsg);
			if (e.openid) return clearInterval(t), $("#new_entry .form-content").append("<input type='hidden' name='x_field_weixin_openid' value='" + e.openid + "'>"), $("#new_entry .form-content").append("<input type='hidden' name='weixin_access_token' value='" + e.access_token + "'>"), n.removeData("weixin-openid-fetching"), GD.updateSubmitBtn()
		}, function() {
			return alert("微信信息获取失败，请重新打开表单")
		})
	}, n = function() {
		var e, t, n, r;
		if ($(".phone-device").length > 0) {
			e = $("form .fields, .success-box"), e.css({
				minHeight: ""
			}), r = $(window).height(), t = $("body").height();
			if (r > t) return n = r - t + e.outerHeight(), e.css({
				minHeight: n
			});
			return
		}
	}, $(document).on("click", ".user-info .logout-link", function(e) {
		return e.preventDefault(), $.ajax({
			url: $(this).attr("href"),
			type: "delete",
			complete: function(e) {
				if (e.status === 200) return window.location.reload()
			}
		})
	}), $(document).on("click", "[data-role=collapse_toggle]", function() {
		var e, t;
		return e = $(this).closest("[data-role=collapsible]"), t = $(this).offset().top - $(window).scrollTop(), e.toggleClass("collapsed"), e.hasClass("collapsed") && $(this).hasClass("collapse-bottom") && $(window).scrollTop(e.offset().top - t), GD.recalcFormHeight()
	}), $(document).on("click", "[data-role=spec_toggle]", function() {
		return $(this).toggleClass("collapsed")
	}), e = function(e) {
		var t, n, r, i, s, o;
		o = 0, s = e.find("input.number");
		for (t = 0, n = s.length; t < n; t++) r = s[t], o += parseInt($(r).val());
		return o > 0 ? (e.closest(".field").addClass("selected"), i = o > 99 ? "⋯" : o, e.find(".badge").length > 0 ? e.find(".badge").text(i) : e.append("<div class='badge'>" + i + "</div>")) : (e.closest(".field").removeClass("selected"), e.find(".badge").remove())
	}, $(document).on("change", ".field [data-role=collapsible] input.number", function() {
		if (GD.isPhone) return e($(this).closest("[data-role=collapsible]"))
	}), t = function(e) {
		var t, n;
		n = function() {
			var n, r, i, s;
			i = e.find("input:checked").next("label"), s = [];
			for (n = 0, r = i.length; n < r; n++) t = i[n], s.push($(t).text());
			return s
		}();
		if (n.length > 0) return e.prev("[data-role=spec_toggle]").find("span").text(n.join(l("%common_separator")))
	}, $(document).on("change", ".field .dimensions input:radio", function() {
		return t($(this).closest(".dimensions"))
	}), $(document).on("change", ".choices.image-choices input", function() {
		return $(this).closest(".image-choices").children(".radio.active").removeClass("active"), $(this).closest(".radio, .checkbox").toggleClass("active")
	}), $(document).on("page:load ready", function() {
		var r, i, s, o, u, a, f, l, c;
		typeof(r = $("input, textarea")).placeholder == "function" && r.placeholder(), l = ["#login_modal", "#submission_password_modal"];
		for (s = 0, u = l.length; s < u; s++) f = l[s], $(f).length > 0 && $(f).modal("show");
		$("form#new_entry input").on("keypress", function(e) {
			if (e.keyCode === 13) return e.preventDefault()
		}), GD.showFormSubmitErrorModal($("#form_error_messages_modal")), autosize($(".field-content textarea:visible")), typeof GD.initWeixinLogin == "function" && GD.initWeixinLogin($(".social-account.account-weixin"));
		if (GD.isPhone) {
			c = $(".field [data-role=collapsible]");
			for (o = 0, a = c.length; o < a; o++) i = c[o], e($(i)), t($(i).find(".collapsible"))
		}
		return n(), $(window).off("resize.gd.pub_form").on("resize.gd.pub_form", n)
	}), $(window).on("customLoad", n), $(function() {
		var e, t;
		$(".mobile-device").length > 0 && !GD.isAndroidDevice() && FastClick.attach(document.body), $(".entry-container-inner > .qrcode-box").length > 0 && (e = $(".entry-container-inner > form"), $(".entry-container-inner > .qrcode-box").css({
			top: e.offset().top,
			marginLeft: e.width() / 2 + 30
		})), $(".field.field-form-association .field-label").attr("for", ""), /^\/f\/\w{6}$/.test(location.pathname) && $.removeCookie("formSubmitSuccess", {
			path: location.pathname
		}) && window.location.reload(!0);
		if (GD.isOpenInWeiXin()) return t = $(".fields .field-attachment-field .attachment-field > .jquery-file-upload-file-input"), _.each(t, function(e) {
			var t, n;
			return n = $(e), t = n.attr("accept"), n.data("weixin-accepts", t), n.removeAttr("accept")
		})
	})
}.call(this), function() {
	$(document).on("click", ".success-social-sharing .share-weixin", function() {
		if ($(".weixin-share-guide").length > 0) return $(".weixin-share-guide").show();
		if ($("#weixin_qrcode_share_modal").length > 0) return $("#weixin_qrcode_share_modal").modal("show")
	}), window.onpopstate = function(e) {
		var t;
		if ((t = e.state) != null ? t.paymentConfirm : void 0) return window.location.url = e.state.url
	}, $(function() {
		if (!$.trim($(".success-actions").text())) return $(".success-separator").hide()
	})
}.call(this);
if (!pv) var pv = {
	map: function(e, t) {
		var n = {};
		return t ? e.map(function(e, r) {
			return n.index = r, t.call(n, e)
		}) : e.slice()
	},
	naturalOrder: function(e, t) {
		return e < t ? -1 : e > t ? 1 : 0
	},
	sum: function(e, t) {
		var n = {};
		return e.reduce(t ?
		function(e, r, i) {
			return n.index = i, e + t.call(n, r)
		} : function(e, t) {
			return e + t
		}, 0)
	},
	max: function(e, t) {
		return Math.max.apply(null, t ? pv.map(e, t) : e)
	}
};
var MMCQ = function() {
		function i(t, n, r) {
			return (t << 2 * e) + (n << e) + r
		}
		function s(e) {
			function r() {
				t.sort(e), n = !0
			}
			var t = [],
				n = !1;
			return {
				push: function(e) {
					t.push(e), n = !1
				},
				peek: function(e) {
					return n || r(), e === undefined && (e = t.length - 1), t[e]
				},
				pop: function() {
					return n || r(), t.pop()
				},
				size: function() {
					return t.length
				},
				map: function(e) {
					return t.map(e)
				},
				debug: function() {
					return n || r(), t
				}
			}
		}
		function o(e, t, n, r, i, s, o) {
			var u = this;
			u.r1 = e, u.r2 = t, u.g1 = n, u.g2 = r, u.b1 = i, u.b2 = s, u.histo = o
		}
		function u() {
			this.vboxes = new s(function(e, t) {
				return pv.naturalOrder(e.vbox.count() * e.vbox.volume(), t.vbox.count() * t.vbox.volume())
			})
		}
		function a(n) {
			var r = 1 << 3 * e,
				s = new Array(r),
				o, u, a, f;
			return n.forEach(function(e) {
				u = e[0] >> t, a = e[1] >> t, f = e[2] >> t, o = i(u, a, f), s[o] = (s[o] || 0) + 1
			}), s
		}
		function f(e, n) {
			var r = 1e6,
				i = 0,
				s = 1e6,
				u = 0,
				a = 1e6,
				f = 0,
				l, c, h;
			return e.forEach(function(e) {
				l = e[0] >> t, c = e[1] >> t, h = e[2] >> t, l < r ? r = l : l > i && (i = l), c < s ? s = c : c > u && (u = c), h < a ? a = h : h > f && (f = h)
			}), new o(r, i, s, u, a, f, n)
		}
		function l(e, t) {
			function v(e) {
				var n = e + "1",
					r = e + "2",
					i, s, o, c, h, p = 0;
				for (l = t[n]; l <= t[r]; l++) if (a[l] > u / 2) {
					o = t.copy(), c = t.copy(), i = l - t[n], s = t[r] - l, i <= s ? h = Math.min(t[r] - 1, ~~ (l + s / 2)) : h = Math.max(t[n], ~~ (l - 1 - i / 2));
					while (!a[h]) h++;
					p = f[h];
					while (!p && a[h - 1]) p = f[--h];
					return o[r] = h, c[n] = o[r] + 1, [o, c]
				}
			}
			if (!t.count()) return;
			var n = t.r2 - t.r1 + 1,
				r = t.g2 - t.g1 + 1,
				s = t.b2 - t.b1 + 1,
				o = pv.max([n, r, s]);
			if (t.count() == 1) return [t.copy()];
			var u = 0,
				a = [],
				f = [],
				l, c, h, p, d;
			if (o == n) for (l = t.r1; l <= t.r2; l++) {
				p = 0;
				for (c = t.g1; c <= t.g2; c++) for (h = t.b1; h <= t.b2; h++) d = i(l, c, h), p += e[d] || 0;
				u += p, a[l] = u
			} else if (o == r) for (l = t.g1; l <= t.g2; l++) {
				p = 0;
				for (c = t.r1; c <= t.r2; c++) for (h = t.b1; h <= t.b2; h++) d = i(c, l, h), p += e[d] || 0;
				u += p, a[l] = u
			} else for (l = t.b1; l <= t.b2; l++) {
				p = 0;
				for (c = t.r1; c <= t.r2; c++) for (h = t.g1; h <= t.g2; h++) d = i(c, h, l), p += e[d] || 0;
				u += p, a[l] = u
			}
			return a.forEach(function(e, t) {
				f[t] = u - e
			}), o == n ? v("r") : o == r ? v("g") : v("b")
		}
		function c(t, i) {
			function v(e, t) {
				var r = 1,
					i = 0,
					s;
				while (i < n) {
					s = e.pop();
					if (!s.count()) {
						e.push(s), i++;
						continue
					}
					var u = l(o, s),
						a = u[0],
						f = u[1];
					if (!a) return;
					e.push(a), f && (e.push(f), r++);
					if (r >= t) return;
					if (i++ > n) return
				}
			}
			if (!t.length || i < 2 || i > 256) return !1;
			var o = a(t),
				c = 1 << 3 * e,
				h = 0;
			o.forEach(function() {
				h++
			}), h <= i;
			var p = f(t, o),
				d = new s(function(e, t) {
					return pv.naturalOrder(e.count(), t.count())
				});
			d.push(p), v(d, r * i);
			var m = new s(function(e, t) {
				return pv.naturalOrder(e.count() * e.volume(), t.count() * t.volume())
			});
			while (d.size()) m.push(d.pop());
			v(m, i - m.size());
			var g = new u;
			while (m.size()) g.push(m.pop());
			return g
		}
		var e = 5,
			t = 8 - e,
			n = 1e3,
			r = .75;
		return o.prototype = {
			volume: function(e) {
				var t = this;
				if (!t._volume || e) t._volume = (t.r2 - t.r1 + 1) * (t.g2 - t.g1 + 1) * (t.b2 - t.b1 + 1);
				return t._volume
			},
			count: function(e) {
				var t = this,
					n = t.histo;
				if (!t._count_set || e) {
					var r = 0,
						s, o, u;
					for (s = t.r1; s <= t.r2; s++) for (o = t.g1; o <= t.g2; o++) for (u = t.b1; u <= t.b2; u++) index = i(s, o, u), r += n[index] || 0;
					t._count = r, t._count_set = !0
				}
				return t._count
			},
			copy: function() {
				var e = this;
				return new o(e.r1, e.r2, e.g1, e.g2, e.b1, e.b2, e.histo)
			},
			avg: function(t) {
				var n = this,
					r = n.histo;
				if (!n._avg || t) {
					var s = 0,
						o = 1 << 8 - e,
						u = 0,
						a = 0,
						f = 0,
						l, c, h, p, d;
					for (c = n.r1; c <= n.r2; c++) for (h = n.g1; h <= n.g2; h++) for (p = n.b1; p <= n.b2; p++) d = i(c, h, p), l = r[d] || 0, s += l, u += l * (c + .5) * o, a += l * (h + .5) * o, f += l * (p + .5) * o;
					s ? n._avg = [~~ (u / s), ~~ (a / s), ~~ (f / s)] : n._avg = [~~ (o * (n.r1 + n.r2 + 1) / 2), ~~ (o * (n.g1 + n.g2 + 1) / 2), ~~ (o * (n.b1 + n.b2 + 1) / 2)]
				}
				return n._avg
			},
			contains: function(e) {
				var n = this,
					r = e[0] >> t;
				return gval = e[1] >> t, bval = e[2] >> t, r >= n.r1 && r <= n.r2 && gval >= n.g1 && r <= n.g2 && bval >= n.b1 && r <= n.b2
			}
		}, u.prototype = {
			push: function(e) {
				this.vboxes.push({
					vbox: e,
					color: e.avg()
				})
			},
			palette: function() {
				return this.vboxes.map(function(e) {
					return e.color
				})
			},
			size: function() {
				return this.vboxes.size()
			},
			map: function(e) {
				var t = this.vboxes;
				for (var n = 0; n < t.size(); n++) if (t.peek(n).vbox.contains(e)) return t.peek(n).color;
				return this.nearest(e)
			},
			nearest: function(e) {
				var t = this.vboxes,
					n, r, i;
				for (var s = 0; s < t.size(); s++) {
					r = Math.sqrt(Math.pow(e[0] - t.peek(s).color[0], 2) + Math.pow(e[1] - t.peek(s).color[1], 2) + Math.pow(e[1] - t.peek(s).color[1], 2));
					if (r < n || n === undefined) n = r, i = t.peek(s).color
				}
				return i
			},
			forcebw: function() {
				var e = this.vboxes;
				e.sort(function(e, t) {
					return pv.naturalOrder(pv.sum(e.color), pv.sum(t.color))
				});
				var t = e[0].color;
				t[0] < 5 && t[1] < 5 && t[2] < 5 && (e[0].color = [0, 0, 0]);
				var n = e.length - 1,
					r = e[n].color;
				r[0] > 251 && r[1] > 251 && r[2] > 251 && (e[n].color = [255, 255, 255])
			}
		}, {
			quantize: c
		}
	}();
(function() {
	var e;
	window.GD || (window.GD = {}), e = function() {
		function e(e, t, n) {
			this.image = e, this.canvas = document.createElement("canvas"), this.context = this.canvas.getContext("2d"), document.body.appendChild(this.canvas), this.width = this.canvas.width = t, this.height = this.canvas.height = n, this.context.drawImage(this.image, 0, 0, this.width, this.height)
		}
		return e.prototype.getImageData = function(e, t, n, r) {
			return this.context.getImageData(e, t, n, r)
		}, e.prototype.removeCanvas = function() {
			return this.canvas.parentNode.removeChild(this.canvas)
		}, e
	}(), GD.ColorExtractor = function() {
		function t(e, t, n) {
			this.image = e, this.imageWidth = t, this.imageHeight = n, this.imageWidth || (this.imageWidth = this.image.width), this.imageHeight || (this.imageHeight = this.image.height)
		}
		return t.prototype.dominateColor = function(t, n, r, i) {
			var s, o, u, a, f, l, c, h, p, d, v, m, g, y, b, w, E, S;
			a = 6, b = 10, t || (t = 0), n || (n = 0), r || (r = this.imageWidth), i || (i = this.imageHeight), c = new e(this.image, this.imageWidth, this.imageHeight), h = c.getImageData(t, n, r, i), y = h.data, g = r * i, m = [];
			for (l = p = 0, E = g, S = b; S > 0 ? p <= E : p >= E; l = p += S) d = l * 4, w = y[d], f = y[d + 1], o = y[d + 2], s = y[d + 3], s >= 125 && !(w > 250 && f > 250 && o > 250) && m.push([w, f, o]);
			u = MMCQ.quantize(m, a), v = u ? u.palette() : null, c.removeCanvas();
			if (v) return v[0]
		}, t
	}()
}).call(this), function() {
	function a(e, t) {
		e = e ? e : "", t = t || {};
		if (e instanceof a) return e;
		if (!(this instanceof a)) return new a(e, t);
		var r = f(e);
		this._originalInput = e, this._r = r.r, this._g = r.g, this._b = r.b, this._a = r.a, this._roundA = i(100 * this._a) / 100, this._format = t.format || r.format, this._gradientType = t.gradientType, this._r < 1 && (this._r = i(this._r)), this._g < 1 && (this._g = i(this._g)), this._b < 1 && (this._b = i(this._b)), this._ok = r.ok, this._tc_id = n++
	}
	function f(e) {
		var t = {
			r: 0,
			g: 0,
			b: 0
		},
			n = 1,
			r = !1,
			i = !1;
		return typeof e == "string" && (e = W(e)), typeof e == "object" && (e.hasOwnProperty("r") && e.hasOwnProperty("g") && e.hasOwnProperty("b") ? (t = l(e.r, e.g, e.b), r = !0, i = String(e.r).substr(-1) === "%" ? "prgb" : "rgb") : e.hasOwnProperty("h") && e.hasOwnProperty("s") && e.hasOwnProperty("v") ? (e.s = q(e.s), e.v = q(e.v), t = d(e.h, e.s, e.v), r = !0, i = "hsv") : e.hasOwnProperty("h") && e.hasOwnProperty("s") && e.hasOwnProperty("l") && (e.s = q(e.s), e.l = q(e.l), t = h(e.h, e.s, e.l), r = !0, i = "hsl"), e.hasOwnProperty("a") && (n = e.a)), n = D(n), {
			ok: r,
			format: e.format || i,
			r: s(255, o(t.r, 0)),
			g: s(255, o(t.g, 0)),
			b: s(255, o(t.b, 0)),
			a: n
		}
	}
	function l(e, t, n) {
		return {
			r: P(e, 255) * 255,
			g: P(t, 255) * 255,
			b: P(n, 255) * 255
		}
	}
	function c(e, t, n) {
		e = P(e, 255), t = P(t, 255), n = P(n, 255);
		var r = o(e, t, n),
			i = s(e, t, n),
			u, a, f = (r + i) / 2;
		if (r == i) u = a = 0;
		else {
			var l = r - i;
			a = f > .5 ? l / (2 - r - i) : l / (r + i);
			switch (r) {
			case e:
				u = (t - n) / l + (t < n ? 6 : 0);
				break;
			case t:
				u = (n - e) / l + 2;
				break;
			case n:
				u = (e - t) / l + 4
			}
			u /= 6
		}
		return {
			h: u,
			s: a,
			l: f
		}
	}
	function h(e, t, n) {
		function o(e, t, n) {
			return n < 0 && (n += 1), n > 1 && (n -= 1), n < 1 / 6 ? e + (t - e) * 6 * n : n < .5 ? t : n < 2 / 3 ? e + (t - e) * (2 / 3 - n) * 6 : e
		}
		var r, i, s;
		e = P(e, 360), t = P(t, 100), n = P(n, 100);
		if (t === 0) r = i = s = n;
		else {
			var u = n < .5 ? n * (1 + t) : n + t - n * t,
				a = 2 * n - u;
			r = o(a, u, e + 1 / 3), i = o(a, u, e), s = o(a, u, e - 1 / 3)
		}
		return {
			r: r * 255,
			g: i * 255,
			b: s * 255
		}
	}
	function p(e, t, n) {
		e = P(e, 255), t = P(t, 255), n = P(n, 255);
		var r = o(e, t, n),
			i = s(e, t, n),
			u, a, f = r,
			l = r - i;
		a = r === 0 ? 0 : l / r;
		if (r == i) u = 0;
		else {
			switch (r) {
			case e:
				u = (t - n) / l + (t < n ? 6 : 0);
				break;
			case t:
				u = (n - e) / l + 2;
				break;
			case n:
				u = (e - t) / l + 4
			}
			u /= 6
		}
		return {
			h: u,
			s: a,
			v: f
		}
	}
	function d(e, t, n) {
		e = P(e, 360) * 6, t = P(t, 100), n = P(n, 100);
		var i = r.floor(e),
			s = e - i,
			o = n * (1 - t),
			u = n * (1 - s * t),
			a = n * (1 - (1 - s) * t),
			f = i % 6,
			l = [n, u, o, o, a, n][f],
			c = [a, n, n, u, o, o][f],
			h = [o, o, a, n, n, u][f];
		return {
			r: l * 255,
			g: c * 255,
			b: h * 255
		}
	}
	function v(e, t, n, r) {
		var s = [I(i(e).toString(16)), I(i(t).toString(16)), I(i(n).toString(16))];
		return r && s[0].charAt(0) == s[0].charAt(1) && s[1].charAt(0) == s[1].charAt(1) && s[2].charAt(0) == s[2].charAt(1) ? s[0].charAt(0) + s[1].charAt(0) + s[2].charAt(0) : s.join("")
	}
	function m(e, t, n, r) {
		var s = [I(R(r)), I(i(e).toString(16)), I(i(t).toString(16)), I(i(n).toString(16))];
		return s.join("")
	}
	function g(e, t) {
		t = t === 0 ? 0 : t || 10;
		var n = a(e).toHsl();
		return n.s -= t / 100, n.s = H(n.s), a(n)
	}
	function y(e, t) {
		t = t === 0 ? 0 : t || 10;
		var n = a(e).toHsl();
		return n.s += t / 100, n.s = H(n.s), a(n)
	}
	function b(e) {
		return a(e).desaturate(100)
	}
	function w(e, t) {
		t = t === 0 ? 0 : t || 10;
		var n = a(e).toHsl();
		return n.l += t / 100, n.l = H(n.l), a(n)
	}
	function E(e, t) {
		t = t === 0 ? 0 : t || 10;
		var n = a(e).toRgb();
		return n.r = o(0, s(255, n.r - i(255 * -(t / 100)))), n.g = o(0, s(255, n.g - i(255 * -(t / 100)))), n.b = o(0, s(255, n.b - i(255 * -(t / 100)))), a(n)
	}
	function S(e, t) {
		t = t === 0 ? 0 : t || 10;
		var n = a(e).toHsl();
		return n.l -= t / 100, n.l = H(n.l), a(n)
	}
	function x(e, t) {
		var n = a(e).toHsl(),
			r = (i(n.h) + t) % 360;
		return n.h = r < 0 ? 360 + r : r, a(n)
	}
	function T(e) {
		var t = a(e).toHsl();
		return t.h = (t.h + 180) % 360, a(t)
	}
	function N(e) {
		var t = a(e).toHsl(),
			n = t.h;
		return [a(e), a({
			h: (n + 120) % 360,
			s: t.s,
			l: t.l
		}), a({
			h: (n + 240) % 360,
			s: t.s,
			l: t.l
		})]
	}
	function C(e) {
		var t = a(e).toHsl(),
			n = t.h;
		return [a(e), a({
			h: (n + 90) % 360,
			s: t.s,
			l: t.l
		}), a({
			h: (n + 180) % 360,
			s: t.s,
			l: t.l
		}), a({
			h: (n + 270) % 360,
			s: t.s,
			l: t.l
		})]
	}
	function k(e) {
		var t = a(e).toHsl(),
			n = t.h;
		return [a(e), a({
			h: (n + 72) % 360,
			s: t.s,
			l: t.l
		}), a({
			h: (n + 216) % 360,
			s: t.s,
			l: t.l
		})]
	}
	function L(e, t, n) {
		t = t || 6, n = n || 30;
		var r = a(e).toHsl(),
			i = 360 / n,
			s = [a(e)];
		for (r.h = (r.h - (i * t >> 1) + 720) % 360; --t;) r.h = (r.h + i) % 360, s.push(a(r));
		return s
	}
	function A(e, t) {
		t = t || 6;
		var n = a(e).toHsv(),
			r = n.h,
			i = n.s,
			s = n.v,
			o = [],
			u = 1 / t;
		while (t--) o.push(a({
			h: r,
			s: i,
			v: s
		})), s = (s + u) % 1;
		return o
	}
	function _(e) {
		var t = {};
		for (var n in e) e.hasOwnProperty(n) && (t[e[n]] = n);
		return t
	}
	function D(e) {
		e = parseFloat(e);
		if (isNaN(e) || e < 0 || e > 1) e = 1;
		return e
	}
	function P(e, t) {
		j(e) && (e = "100%");
		var n = F(e);
		return e = s(t, o(0, parseFloat(e))), n && (e = parseInt(e * t, 10) / 100), r.abs(e - t) < 1e-6 ? 1 : e % t / parseFloat(t)
	}
	function H(e) {
		return s(1, o(0, e))
	}
	function B(e) {
		return parseInt(e, 16)
	}
	function j(e) {
		return typeof e == "string" && e.indexOf(".") != -1 && parseFloat(e) === 1
	}
	function F(e) {
		return typeof e == "string" && e.indexOf("%") != -1
	}
	function I(e) {
		return e.length == 1 ? "0" + e : "" + e
	}
	function q(e) {
		return e <= 1 && (e = e * 100 + "%"), e
	}
	function R(e) {
		return Math.round(parseFloat(e) * 255).toString(16)
	}
	function U(e) {
		return B(e) / 255
	}
	function W(n) {
		n = n.replace(e, "").replace(t, "").toLowerCase();
		var r = !1;
		if (O[n]) n = O[n], r = !0;
		else if (n == "transparent") return {
			r: 0,
			g: 0,
			b: 0,
			a: 0,
			format: "name"
		};
		var i;
		return (i = z.rgb.exec(n)) ? {
			r: i[1],
			g: i[2],
			b: i[3]
		} : (i = z.rgba.exec(n)) ? {
			r: i[1],
			g: i[2],
			b: i[3],
			a: i[4]
		} : (i = z.hsl.exec(n)) ? {
			h: i[1],
			s: i[2],
			l: i[3]
		} : (i = z.hsla.exec(n)) ? {
			h: i[1],
			s: i[2],
			l: i[3],
			a: i[4]
		} : (i = z.hsv.exec(n)) ? {
			h: i[1],
			s: i[2],
			v: i[3]
		} : (i = z.hsva.exec(n)) ? {
			h: i[1],
			s: i[2],
			v: i[3],
			a: i[4]
		} : (i = z.hex8.exec(n)) ? {
			a: U(i[1]),
			r: B(i[2]),
			g: B(i[3]),
			b: B(i[4]),
			format: r ? "name" : "hex8"
		} : (i = z.hex6.exec(n)) ? {
			r: B(i[1]),
			g: B(i[2]),
			b: B(i[3]),
			format: r ? "name" : "hex"
		} : (i = z.hex3.exec(n)) ? {
			r: B(i[1] + "" + i[1]),
			g: B(i[2] + "" + i[2]),
			b: B(i[3] + "" + i[3]),
			format: r ? "name" : "hex"
		} : !1
	}
	function X(e) {
		var t, n;
		return e = e || {
			level: "AA",
			size: "small"
		}, t = (e.level || "AA").toUpperCase(), n = (e.size || "small").toLowerCase(), t !== "AA" && t !== "AAA" && (t = "AA"), n !== "small" && n !== "large" && (n = "small"), {
			level: t,
			size: n
		}
	}
	var e = /^\s+/,
		t = /\s+$/,
		n = 0,
		r = Math,
		i = r.round,
		s = r.min,
		o = r.max,
		u = r.random;
	a.prototype = {
		isDark: function() {
			return this.getBrightness() < 128
		},
		isLight: function() {
			return !this.isDark()
		},
		isValid: function() {
			return this._ok
		},
		getOriginalInput: function() {
			return this._originalInput
		},
		getFormat: function() {
			return this._format
		},
		getAlpha: function() {
			return this._a
		},
		getBrightness: function() {
			var e = this.toRgb();
			return (e.r * 299 + e.g * 587 + e.b * 114) / 1e3
		},
		getLuminance: function() {
			var e = this.toRgb(),
				t, n, r, i, s, o;
			return t = e.r / 255, n = e.g / 255, r = e.b / 255, t <= .03928 ? i = t / 12.92 : i = Math.pow((t + .055) / 1.055, 2.4), n <= .03928 ? s = n / 12.92 : s = Math.pow((n + .055) / 1.055, 2.4), r <= .03928 ? o = r / 12.92 : o = Math.pow((r + .055) / 1.055, 2.4), .2126 * i + .7152 * s + .0722 * o
		},
		setAlpha: function(e) {
			return this._a = D(e), this._roundA = i(100 * this._a) / 100, this
		},
		toHsv: function() {
			var e = p(this._r, this._g, this._b);
			return {
				h: e.h * 360,
				s: e.s,
				v: e.v,
				a: this._a
			}
		},
		toHsvString: function() {
			var e = p(this._r, this._g, this._b),
				t = i(e.h * 360),
				n = i(e.s * 100),
				r = i(e.v * 100);
			return this._a == 1 ? "hsv(" + t + ", " + n + "%, " + r + "%)" : "hsva(" + t + ", " + n + "%, " + r + "%, " + this._roundA + ")"
		},
		toHsl: function() {
			var e = c(this._r, this._g, this._b);
			return {
				h: e.h * 360,
				s: e.s,
				l: e.l,
				a: this._a
			}
		},
		toHslString: function() {
			var e = c(this._r, this._g, this._b),
				t = i(e.h * 360),
				n = i(e.s * 100),
				r = i(e.l * 100);
			return this._a == 1 ? "hsl(" + t + ", " + n + "%, " + r + "%)" : "hsla(" + t + ", " + n + "%, " + r + "%, " + this._roundA + ")"
		},
		toHex: function(e) {
			return v(this._r, this._g, this._b, e)
		},
		toHexString: function(e) {
			return "#" + this.toHex(e)
		},
		toHex8: function() {
			return m(this._r, this._g, this._b, this._a)
		},
		toHex8String: function() {
			return "#" + this.toHex8()
		},
		toRgb: function() {
			return {
				r: i(this._r),
				g: i(this._g),
				b: i(this._b),
				a: this._a
			}
		},
		toRgbString: function() {
			return this._a == 1 ? "rgb(" + i(this._r) + ", " + i(this._g) + ", " + i(this._b) + ")" : "rgba(" + i(this._r) + ", " + i(this._g) + ", " + i(this._b) + ", " + this._roundA + ")"
		},
		toPercentageRgb: function() {
			return {
				r: i(P(this._r, 255) * 100) + "%",
				g: i(P(this._g, 255) * 100) + "%",
				b: i(P(this._b, 255) * 100) + "%",
				a: this._a
			}
		},
		toPercentageRgbString: function() {
			return this._a == 1 ? "rgb(" + i(P(this._r, 255) * 100) + "%, " + i(P(this._g, 255) * 100) + "%, " + i(P(this._b, 255) * 100) + "%)" : "rgba(" + i(P(this._r, 255) * 100) + "%, " + i(P(this._g, 255) * 100) + "%, " + i(P(this._b, 255) * 100) + "%, " + this._roundA + ")"
		},
		toName: function() {
			return this._a === 0 ? "transparent" : this._a < 1 ? !1 : M[v(this._r, this._g, this._b, !0)] || !1
		},
		toFilter: function(e) {
			var t = "#" + m(this._r, this._g, this._b, this._a),
				n = t,
				r = this._gradientType ? "GradientType = 1, " : "";
			if (e) {
				var i = a(e);
				n = i.toHex8String()
			}
			return "progid:DXImageTransform.Microsoft.gradient(" + r + "startColorstr=" + t + ",endColorstr=" + n + ")"
		},
		toString: function(e) {
			var t = !! e;
			e = e || this._format;
			var n = !1,
				r = this._a < 1 && this._a >= 0,
				i = !t && r && (e === "hex" || e === "hex6" || e === "hex3" || e === "name");
			if (i) return e === "name" && this._a === 0 ? this.toName() : this.toRgbString();
			e === "rgb" && (n = this.toRgbString()), e === "prgb" && (n = this.toPercentageRgbString());
			if (e === "hex" || e === "hex6") n = this.toHexString();
			return e === "hex3" && (n = this.toHexString(!0)), e === "hex8" && (n = this.toHex8String()), e === "name" && (n = this.toName()), e === "hsl" && (n = this.toHslString()), e === "hsv" && (n = this.toHsvString()), n || this.toHexString()
		},
		clone: function() {
			return a(this.toString())
		},
		_applyModification: function(e, t) {
			var n = e.apply(null, [this].concat([].slice.call(t)));
			return this._r = n._r, this._g = n._g, this._b = n._b, this.setAlpha(n._a), this
		},
		lighten: function() {
			return this._applyModification(w, arguments)
		},
		brighten: function() {
			return this._applyModification(E, arguments)
		},
		darken: function() {
			return this._applyModification(S, arguments)
		},
		desaturate: function() {
			return this._applyModification(g, arguments)
		},
		saturate: function() {
			return this._applyModification(y, arguments)
		},
		greyscale: function() {
			return this._applyModification(b, arguments)
		},
		spin: function() {
			return this._applyModification(x, arguments)
		},
		_applyCombination: function(e, t) {
			return e.apply(null, [this].concat([].slice.call(t)))
		},
		analogous: function() {
			return this._applyCombination(L, arguments)
		},
		complement: function() {
			return this._applyCombination(T, arguments)
		},
		monochromatic: function() {
			return this._applyCombination(A, arguments)
		},
		splitcomplement: function() {
			return this._applyCombination(k, arguments)
		},
		triad: function() {
			return this._applyCombination(N, arguments)
		},
		tetrad: function() {
			return this._applyCombination(C, arguments)
		}
	}, a.fromRatio = function(e, t) {
		if (typeof e == "object") {
			var n = {};
			for (var r in e) e.hasOwnProperty(r) && (r === "a" ? n[r] = e[r] : n[r] = q(e[r]));
			e = n
		}
		return a(e, t)
	}, a.equals = function(e, t) {
		return !e || !t ? !1 : a(e).toRgbString() == a(t).toRgbString()
	}, a.random = function() {
		return a.fromRatio({
			r: u(),
			g: u(),
			b: u()
		})
	}, a.mix = function(e, t, n) {
		n = n === 0 ? 0 : n || 50;
		var r = a(e).toRgb(),
			i = a(t).toRgb(),
			s = n / 100,
			o = s * 2 - 1,
			u = i.a - r.a,
			f;
		o * u == -1 ? f = o : f = (o + u) / (1 + o * u), f = (f + 1) / 2;
		var l = 1 - f,
			c = {
				r: i.r * f + r.r * l,
				g: i.g * f + r.g * l,
				b: i.b * f + r.b * l,
				a: i.a * s + r.a * (1 - s)
			};
		return a(c)
	}, a.readability = function(e, t) {
		var n = a(e),
			r = a(t);
		return (Math.max(n.getLuminance(), r.getLuminance()) + .05) / (Math.min(n.getLuminance(), r.getLuminance()) + .05)
	}, a.isReadable = function(e, t, n) {
		var r = a.readability(e, t),
			i, s;
		s = !1, i = X(n);
		switch (i.level + i.size) {
		case "AAsmall":
		case "AAAlarge":
			s = r >= 4.5;
			break;
		case "AAlarge":
			s = r >= 3;
			break;
		case "AAAsmall":
			s = r >= 7
		}
		return s
	}, a.mostReadable = function(e, t, n) {
		var r = null,
			i = 0,
			s, o, u, f;
		n = n || {}, o = n.includeFallbackColors, u = n.level, f = n.size;
		for (var l = 0; l < t.length; l++) s = a.readability(e, t[l]), s > i && (i = s, r = a(t[l]));
		return a.isReadable(e, r, {
			level: u,
			size: f
		}) || !o ? r : (n.includeFallbackColors = !1, a.mostReadable(e, ["#fff", "#000"], n))
	};
	var O = a.names = {
		aliceblue: "f0f8ff",
		antiquewhite: "faebd7",
		aqua: "0ff",
		aquamarine: "7fffd4",
		azure: "f0ffff",
		beige: "f5f5dc",
		bisque: "ffe4c4",
		black: "000",
		blanchedalmond: "ffebcd",
		blue: "00f",
		blueviolet: "8a2be2",
		brown: "a52a2a",
		burlywood: "deb887",
		burntsienna: "ea7e5d",
		cadetblue: "5f9ea0",
		chartreuse: "7fff00",
		chocolate: "d2691e",
		coral: "ff7f50",
		cornflowerblue: "6495ed",
		cornsilk: "fff8dc",
		crimson: "dc143c",
		cyan: "0ff",
		darkblue: "00008b",
		darkcyan: "008b8b",
		darkgoldenrod: "b8860b",
		darkgray: "a9a9a9",
		darkgreen: "006400",
		darkgrey: "a9a9a9",
		darkkhaki: "bdb76b",
		darkmagenta: "8b008b",
		darkolivegreen: "556b2f",
		darkorange: "ff8c00",
		darkorchid: "9932cc",
		darkred: "8b0000",
		darksalmon: "e9967a",
		darkseagreen: "8fbc8f",
		darkslateblue: "483d8b",
		darkslategray: "2f4f4f",
		darkslategrey: "2f4f4f",
		darkturquoise: "00ced1",
		darkviolet: "9400d3",
		deeppink: "ff1493",
		deepskyblue: "00bfff",
		dimgray: "696969",
		dimgrey: "696969",
		dodgerblue: "1e90ff",
		firebrick: "b22222",
		floralwhite: "fffaf0",
		forestgreen: "228b22",
		fuchsia: "f0f",
		gainsboro: "dcdcdc",
		ghostwhite: "f8f8ff",
		gold: "ffd700",
		goldenrod: "daa520",
		gray: "808080",
		green: "008000",
		greenyellow: "adff2f",
		grey: "808080",
		honeydew: "f0fff0",
		hotpink: "ff69b4",
		indianred: "cd5c5c",
		indigo: "4b0082",
		ivory: "fffff0",
		khaki: "f0e68c",
		lavender: "e6e6fa",
		lavenderblush: "fff0f5",
		lawngreen: "7cfc00",
		lemonchiffon: "fffacd",
		lightblue: "add8e6",
		lightcoral: "f08080",
		lightcyan: "e0ffff",
		lightgoldenrodyellow: "fafad2",
		lightgray: "d3d3d3",
		lightgreen: "90ee90",
		lightgrey: "d3d3d3",
		lightpink: "ffb6c1",
		lightsalmon: "ffa07a",
		lightseagreen: "20b2aa",
		lightskyblue: "87cefa",
		lightslategray: "789",
		lightslategrey: "789",
		lightsteelblue: "b0c4de",
		lightyellow: "ffffe0",
		lime: "0f0",
		limegreen: "32cd32",
		linen: "faf0e6",
		magenta: "f0f",
		maroon: "800000",
		mediumaquamarine: "66cdaa",
		mediumblue: "0000cd",
		mediumorchid: "ba55d3",
		mediumpurple: "9370db",
		mediumseagreen: "3cb371",
		mediumslateblue: "7b68ee",
		mediumspringgreen: "00fa9a",
		mediumturquoise: "48d1cc",
		mediumvioletred: "c71585",
		midnightblue: "191970",
		mintcream: "f5fffa",
		mistyrose: "ffe4e1",
		moccasin: "ffe4b5",
		navajowhite: "ffdead",
		navy: "000080",
		oldlace: "fdf5e6",
		olive: "808000",
		olivedrab: "6b8e23",
		orange: "ffa500",
		orangered: "ff4500",
		orchid: "da70d6",
		palegoldenrod: "eee8aa",
		palegreen: "98fb98",
		paleturquoise: "afeeee",
		palevioletred: "db7093",
		papayawhip: "ffefd5",
		peachpuff: "ffdab9",
		peru: "cd853f",
		pink: "ffc0cb",
		plum: "dda0dd",
		powderblue: "b0e0e6",
		purple: "800080",
		rebeccapurple: "663399",
		red: "f00",
		rosybrown: "bc8f8f",
		royalblue: "4169e1",
		saddlebrown: "8b4513",
		salmon: "fa8072",
		sandybrown: "f4a460",
		seagreen: "2e8b57",
		seashell: "fff5ee",
		sienna: "a0522d",
		silver: "c0c0c0",
		skyblue: "87ceeb",
		slateblue: "6a5acd",
		slategray: "708090",
		slategrey: "708090",
		snow: "fffafa",
		springgreen: "00ff7f",
		steelblue: "4682b4",
		tan: "d2b48c",
		teal: "008080",
		thistle: "d8bfd8",
		tomato: "ff6347",
		turquoise: "40e0d0",
		violet: "ee82ee",
		wheat: "f5deb3",
		white: "fff",
		whitesmoke: "f5f5f5",
		yellow: "ff0",
		yellowgreen: "9acd32"
	},
		M = a.hexNames = _(O),
		z = function() {
			var e = "[-\\+]?\\d+%?",
				t = "[-\\+]?\\d*\\.\\d+%?",
				n = "(?:" + t + ")|(?:" + e + ")",
				r = "[\\s|\\(]+(" + n + ")[,|\\s]+(" + n + ")[,|\\s]+(" + n + ")\\s*\\)?",
				i = "[\\s|\\(]+(" + n + ")[,|\\s]+(" + n + ")[,|\\s]+(" + n + ")[,|\\s]+(" + n + ")\\s*\\)?";
			return {
				rgb: new RegExp("rgb" + r),
				rgba: new RegExp("rgba" + i),
				hsl: new RegExp("hsl" + r),
				hsla: new RegExp("hsla" + i),
				hsv: new RegExp("hsv" + r),
				hsva: new RegExp("hsva" + i),
				hex3: /^#?([0-9a-fA-F]{1})([0-9a-fA-F]{1})([0-9a-fA-F]{1})$/,
				hex6: /^#?([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})$/,
				hex8: /^#?([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})$/
			}
		}();
	typeof module != "undefined" && module.exports ? module.exports = a : typeof define == "function" && define.amd ? define(function() {
		return a
	}) : window.tinycolor = a
}(), function() {
	var e, t, n, r, i;
	t = function() {
		return $(".entry-container footer.published .powered-by")
	}, r = function(e) {
		var n, r, i;
		return i = typeof e == "string" ? e : "rgb " + e.join(" "), r = tinycolor(i), n = r.getBrightness(), t().addClass(n < 160 ? "light" : "").css("opacity", 1)
	}, i = function() {
		return t().css("opacity", 1)
	}, n = function() {
		var e, n, r, i, s, o;
		return e = t(), r = e.width(), n = e.height(), s = ($(window).width() - r) / 2, i = $(window).height(), $(document).height() > i ? o = i - parseInt($("footer").css("paddingBottom")) - n / 2 : o = e.offset().top, [s, o, r, n]
	}, e = function() {
		var e, t, s, o;
		return s = (o = $("body").css("background-image")) != null ? o.replace("url(", "").replace(")", "").replace(/"/g, "") : void 0, s && s !== "none" ? GD.isIE() ? i() : (e = $("<img />").attr("crossorigin", "anonymous").attr("src", s), e.on("load", function() {
			var e, t, s, o, u, a, f, l, c, h;
			return Modernizr.canvas && (l = $(window).width(), f = $(window).height(), o = $("body").css("background-repeat") === "repeat" && this.width < l && this.height < f, o ? t = (new GD.ColorExtractor(this)).dominateColor() : (u = n(), c = u[0], h = u[1], a = u[2], s = u[3], e = new GD.ColorExtractor(this, l, f), t = e.dominateColor(c, h, a, s))), t ? r(t) : i()
		})) : (t = $("body").css("background-color"), !t || t === "none" ? i() : r(t))
	}, $(document).on("page:load ready", function() {
		if (t().length > 0) return e()
	})
}.call(this), function() {
	var e;
	e = function() {
		var e;
		e = parent.postMessage ? parent : parent.document.postMessage ? parent.document : void 0;
		if (e != null) return e.postMessage({
			event: "heightChanged",
			token: $("#new_entry").data("form-token"),
			value: $("body").height() + 20
		}, "*")
	}, $(window).on("load customLoad", e), $("html").css("visibility", "visible")
}.call(this), function() {
	$(function() {
		return $(document).on("page:load ready", function() {
			if (GD.isLowVersionIE() && $(".goods-warning").length === 0) {
				$("body").prepend($('<div class="browser-version-warning">您使用的浏览器版本较低，请升级到IE10或使用<a href="http://rj.baidu.com/soft/detail/14744.html" target="_blank">谷歌浏览器</a>获得更佳体验。</div>')), $(".browser-version-warning").css("margin-bottom", $("body").css("padding-top")), $("body").css("padding-top", "0");
				if ($("header.navbar.home-page").length > 0) return $("header.navbar.home-page").css("top", $(".browser-version-warning").outerHeight())
			}
		})
	})
}.call(this), function() {
	var e;
	e = function() {
		return $(".mail-auto-complete").mailAutoComplete({
			focusClass: "gd-mail-auto-complete-focus-box",
			listClass: "gd-mail-auto-complete-list-default-box",
			markCalss: "gd-mail-auto-complete-mark-box",
			blurColor: "#333",
			boxClass: "gd-mail-auto-complete-list-box"
		})
	}, $(document).on("ready page:load", e)
}.call(this), function() {
	window.GD || (window.GD = {})
}.call(this);