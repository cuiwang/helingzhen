function getRequest() {
	var t = location.hash || location.search,
		e = location.hash ? "#" : "?",
		n = new Object;
	if (-1 != t.indexOf(e))
		for (var i = t.substr(1), s = i.split("&"), o = 0; o < s.length; o++) n[s[o].split("=")[0]] = unescape(s[o].split("=")[1]);
	return n
}

function qzoneShare() {
	if (Config.isDebug) return void alert("debug-本地 无法测试");
	try {
		QZAppExternal.qzoneGameBar({
			type: "share"
		})
	} catch (t) {}
}

function isWanBa() {
	return 1;
	//return 1 == fromType
}

function canQQShare() {
	return fromType > 10
}

function isInWeiXin() {
	return true;
}

function isInQQ() {
	return !isInWeiXin() && void 0 != _get.f
}

function shareAPI(t) {
	try {
		var e = ShareManager.getInstance().qqShareInfo;
		mqq.ui.shareMessage({
				title: "" + e.title,
				desc: "" + e.desc,
				share_type: t,
				share_url: "" + e.share_url,
				image_url: "" + e.image_url
			},
			function() {})
	} catch (n) {}
}
var lib = function() {
	function t(t) {
		return function() {
			return Function.call.apply(t, arguments)
		}
	}

	function e(e, n, i) {
		return e ? t(i || e) : n
	}

	function n(t) {
		var e = t && t.ownerDocument || document,
			n = e.compatMode;
		return n && "CSS1Compat" !== n ? e.body : e.documentElement
	}
	var i = {},
		s = Object.prototype.toString,
		o = Object.prototype.hasOwnProperty,
		r = i.typeOf = function(t) {
			var e = s.call(t).slice(8, -1).toLowerCase();
			return t && "object" == typeof t && "nodeType" in t ? "dom" : null == t ? null : e
		},
		a = i.each = e(Array.prototype.forEach,
			function(t, e, n) {
				for (var i = 0, s = t.length >>> 0; s > i; i++) i in t && e.call(n, t[i], i, t)
			});
	a(["String", "Array", "Function", "Date", "Object"],
		function(t) {
			var e = t.toLowerCase();
			i["function" === e ? "fn" : e] = {},
			i["is" + t] = function(e) {
				return null != e && s.call(e).slice(8, -1) === t
			}
		}),
	i.array.each = a; {
		var l = i.map = i.array.map = e(Array.prototype.map,
			function(t, e, n) {
				for (var i = 0, s = t.length; s > i; i++) i in t && (t[i] = e.call(n, t[i], i, t));
				return t
			}),
			c = i.indexOf = i.array.indexOf = e(Array.prototype.indexOf,
				function(t, e, n) {
					for (var i = this.length >>> 0, s = 0 > n ? Math.max(0, i + n) : n || 0; i > s; s++)
						if (t[s] === e) return s;
					return -1
				}),
			h = i.slice = i.array.slice = t(Array.prototype.slice);
		i.erase = i.array.erase = function(t, e) {
			for (var n = t.length; n--;) t[n] === e && t.splice(n, 1)
		}
	}
	i.toArray = i.array.toArray = function(t) {
		if (null == t) return [];
		if (i.isArray(t)) return t;
		var e = t.length;
		if ("number" == typeof e && "string" !== r(t)) {
			for (var n = []; e--;) n[e] = t[e];
			return n
		}
		return [t]
	};
	var d = {
		valueOf: 1
	}.propertyIsEnumerable("valueOf") ? null : ["hasOwnProperty", "valueOf", "isPrototypeOf", "propertyIsEnumerable", "toLocaleString", "toString", "constructor"],
		u = i.forIn = i.object.each = function(t, e) {
			for (var n in t) o.call(t, n) && e(t[n], n);
			if (d)
				for (var n, i = d.length - 1; n = d[i--];) o.call(t, n) && e(t[n], n)
		},
		p = i.extend = i.object.extend = function(t, e) {
			return u(e,
				function(e, n) {
					i.isObject(t[n]) ? p(t[n], e) : t[n] = e
				}),
			t
		},
		f = i.clone = i.object.clone = function(t) {
			if (!t || "object" != typeof t) return t;
			var e = t;
			return i.isArray(t) ? e = l(h(t), f) : i.isObject(t) && "isPrototypeOf" in t && (e = {},
				u(t,
					function(t, n) {
						e[n] = f(t)
					})),
			e
		},
		g = function(t, e) {
			var n = [];
			return u(t,
				function(t, i) {
					e && (i = e + "[" + i + "]");
					var s;
					switch (r(t)) {
						case "object":
							s = g(t, i);
							break;
						case "array":
							for (var o = {},
									a = t.length; a--;) o[a] = t[a];
							s = g(o, i);
							break;
						default:
							s = i + "=" + encodeURIComponent(t)
					}
					null != t && n.push(s)
				}),
			n.join("&")
		};
	i.toQueryString = i.object.toQueryString = g,
	i.trim = i.string.trim = function() {
		var t = /^[\s\xa0\u3000]+|[\u3000\xa0\s]+$/g;
		return function(e, n) {
			return e && String(e).replace(n || t, "") || ""
		}
	}(),
	i.camelCase = i.string.camelCase = function(t) {
		return String(t).replace(/-\D/g,
			function(t) {
				return t.charAt(1).toUpperCase()
			})
	},
	i.capitalize = i.string.capitalize = function(t) {
		return String(t).replace(/\b[a-z]/g,
			function(t) {
				return t.toUpperCase()
			})
	},
	i.contains = i.string.contains = function(t, e, n) {
		return n = n || " ",
		t = n + t + n,
		e = n + i.trim(e) + n,
		t.indexOf(e) > -1
	},
	i.pad = i.string.pad = function(t, e) {
		var n = String(Math.abs(0 | t));
		return (0 > t ? "-" : "") + (n.length >= e ? n : ((1 << e - n.length).toString(2) + n).slice(-e))
	},
	i.delay = i.fn.delay = function(t, e, n, i) {
		return setTimeout(function() {
				t.apply(n, i || [])
			},
			e)
	},
	i.periodical = i.fn.periodical = function(t, e, n, i) {
		return setInterval(function() {
				t.apply(n, i || [])
			},
			e)
	},
	i.bind = i.fn.bind = e(Function.bind,
		function(t, e) {
			var n = arguments.length > 2 ? h(arguments, 2) : null,
				i = function() {},
				s = function() {
					var o = e,
						r = arguments.length;
					this instanceof s && (i.prototype = t.prototype, o = new i);
					var a = n || r ? t.apply(o, n && r ? n.concat(h(arguments)) : n || arguments) : t.call(o);
					return o === e ? a : o
				};
			return s
		}),
	i.binds = i.fn.binds = function(t, e) {
		if ("string" == typeof e && (e = ~e.indexOf(",") ? e.split(/\s*,\s*/) : h(arguments, 1)), e && e.length)
			for (var n, s; n = e.pop();) s = n && t[n],
		s && (t[n] = i.bind(s, t))
	};
	var m = i.curry = i.fn.curry = function(t) {
		var e = h(arguments, 1);
		return function() {
			return t.apply(this, e.concat(h(arguments)))
		}
	};
	!
		function() {
			function t(t, n) {
				var s = function() {};
				s.prototype = t.prototype;
				var o = i.newClass(new s);
				return o.prototype.parent = m(e, t),
				o.implement(n),
				o
			}

			function e(t, e) {
				var n = t.prototype[e];
				if (n) return n.apply(this, h(arguments, 2));
				throw new Error("parent Class has no method named " + e)
			}

			function n(t, e) {
				return i.isFunction(e) && (e = e.prototype),
				u(e,
					function(e, n) {
						t.prototype[n] = i.isObject(t.prototype[n]) && i.isObject(e) ? i.extend(i.clone(t.prototype[n]), e) : e
					}),
				t
			}
			i.newClass = function(e, s) {
				i.isObject(e) && (s = e);
				var o = function() {
					var t = this.initialize ? this.initialize.apply(this, arguments) : this;
					return i.isFunction(e) && (t = e.apply(this, arguments)),
					t
				};
				return o.prototype = s || {},
				o.prototype.constructor = o,
				o.extend = m(t, o),
				o.implement = m(n, o),
				o
			}
	}(),
	i.observable = {
		addEvent: function(t, e) {
			i.isFunction(t) && (e = t, t = "*"),
			this._listeners = this._listeners || {};
			var n = this._listeners[t] || [];
			return c(n, e) < 0 && (e.$type = t, n.push(e)),
			this._listeners[t] = n,
			this
		},
		removeEvent: function(t, e) {
			i.isFunction(t) && (e = t, t = "*"),
			this._listeners = this._listeners || {};
			var n = this._listeners[t];
			if (n)
				if (e) {
					var s = c(n, e);~
					s && delete n[s]
				} else n.length = 0,
			delete this._listeners[t];
			return this
		},
		once: function(t, e) {
			i.isFunction(t) && (e = t, t = "*");
			var n = this,
				s = function() {
					e.apply(n, arguments),
					n.removeEvent(t, s)
				};
			this.addEvent.call(n, t, s)
		},
		fireEvent: function(t, e) {
			this._listeners = this._listeners || {};
			var n = this._listeners[t];
			return n && a(n,
				function(n) {
					e = e || {},
					e.type = t,
					n.call(this, e)
				},
				this),
				"*" !== t && this.fireEvent("*", e),
			this
		}
	},
	i.configurable = {
		setOptions: function(t) {
			if (!t) return f(this.options);
			var e = this.options = f(this.options),
				n = /^on[A-Z]/,
				s = this;
			this.srcOptions = t;
			var a;
			for (var l in t)
				if (o.call(t, l))
					if (a = t[l], n.test(l) && i.isFunction(a)) {
						var c = l.charAt(2).toLowerCase() + l.slice(3);
						s.addEvent(c, a),
						delete t[l]
					} else e[l] = l in e && "object" === r(a) ? p(e[l] || {},
						a) : a;
			return e
		}
	};
	var v = {
		list: [],
		custom: {}
	};
	if (i.event = {},
		i.addEvent = i.event.addEvent = document.addEventListener ?
		function(t, e, n) {
			var i = n,
				s = v.custom[e],
				o = e;
			return s && (o = s.base, i = function(i) {
					s.condition.call(t, i, e) && (i._type = e, n.call(t, i))
				},
				n.index = v.list.length, v.list[n.index] = i),
			t.addEventListener(o, i, !! arguments[3])
		} : function(t, e, n) {
			return t.attachEvent("on" + e, n)
		},
		i.removeEvent = i.event.removeEvent = document.removeEventListener ?
		function(t, e, n) {
			var i = n,
				s = v.custom[e],
				o = e;
			return s && (o = s.base, i = v.list[n.index], delete v.list[n.index], delete n.index),
			t.removeEventListener(o, i, !! arguments[3]),
			t
		} : function(t, e, n) {
			return t.detachEvent("on" + e, n),
			t
		},
		i.fireEvent = i.event.fireEvent = document.createEvent ?
		function(t, e) {
			var n = v.custom[e],
				i = e;
			n && (i = n.base);
			var s = document.createEvent("HTMLEvents");
			return s.initEvent(i, !0, !0),
			t.dispatchEvent(s),
			t
		} : function(t, e) {
			var n = document.createEventObject();
			return t.fireEvent("on" + e, n),
			t
		},
		i.getTarget = i.event.getTarget = function(t) {
			return t = t || window.event,
			t.target || t.srcElement
		},
		i.preventDefault = i.event.preventDefault = e(window.Event && Event.prototype.preventDefault,
			function() {
				event.returnValue = !1
			}), i.stopPropagation = i.event.stopPropagation = e(window.Event && Event.prototype.stopPropagation,
			function() {
				event.cancelBubble = !0
			}), !("onmouseenter" in document)) {
		var b = function(t) {
			var e = t.relatedTarget;
			return null == e ? !0 : e ? e !== this && "xul" !== e.prefix && 9 !== this.nodeType && !i.contains(this, e) : !1
		};
		v.custom.mouseenter = {
			base: "mouseover",
			condition: b
		},
		v.custom.mouseleave = {
			base: "mouseout",
			condition: b
		}
	}!
		function() {
			var t = /(opera|ie|firefox|chrome|version)[\s\/:]([\w\d\.]+)?.*?(safari|version[\s\/:]([\w\d\.]+)|$)/,
				e = navigator.userAgent.toLowerCase().match(t) || [null, "unknown", 0],
				n = "ie" === e[1] && document.documentMode,
				s = i.browser = {
					name: "version" === e[1] ? e[3] : e[1],
					version: n || parseFloat("opera" === e[1] && e[4] ? e[4] : e[2])
				};
			s[s.name] = 0 | s.version,
			s[s.name + (0 | s.version)] = !0
	}(),
	i.page = {},
	i.getScrollLeft = i.page.getScrollLeft = function() {
		return window.pageXOffset || n().scrollLeft
	},
	i.getScrollTop = i.page.getScrollTop = function() {
		return window.pageYOffset || n().scrollTop
	},
	i.getViewWidth = i.page.getViewWidth = function() {
		return n().clientWidth
	},
	i.getViewHeight = i.page.getViewHeight = function() {
		return n().clientHeight
	},
	i.dom = {},
	i.g = i.dom.g = function(t) {
		return "string" === r(t) ? document.getElementById(t) : t
	},
	i.q = i.dom.q = document.getElementsByClassName ?
		function(t, e) {
			return h((e || document).getElementsByClassName(t))
	} : function(t, e) {
		e = e || document;
		for (var n = e.getElementsByTagName("*"), s = [], o = 0, r = n.length; r > o; o++) i.contains(n[o].className, t) && s.push(n[o]);
		return s
	},
	i.getAncestorBy = i.dom.getAncestorBy = function(t, e, n) {
		for (;
			(t = t.parentNode) && 1 === t.nodeType;)
			if (e(t, n)) return t;
		return null
	},
	i.getAncestorByClass = i.dom.getAncestorByClass = function(t, e) {
		return i.getAncestorBy(t, i.hasClass, e)
	};
	var S = "classList" in document.documentElement;
	i.hasClass = i.dom.hasClass = S ?
		function(t, e) {
			return t.classList.contains(e)
	} : function(t, e) {
		return i.contains(t.className, e)
	},
	i.addClass = i.dom.addClass = S ?
		function(t, e) {
			return t.classList.add(e),
			t
	} : function(t, e) {
		return i.hasClass(t, e) || (t.className += " " + e),
		t
	},
	i.removeClass = i.dom.removeClass = S ?
		function(t, e) {
			return t.classList.remove(e),
			t
	} : function(t, e) {
		return t.className = t.className.replace(new RegExp("(^|\\s)" + e + "(?:\\s|$)"), "$1"),
		t
	},
	i.toggleClass = i.dom.toggleClass = S ?
		function(t, e) {
			return t.classList.toggle(e),
			t
	} : function(t, e) {
		var n = t.className,
			s = n && i.contains(t, e);
		return t.className = s ? n.replace(new RegExp("(^|\\s)" + e + "(?:\\s|$)"), "$1") : n + " " + e,
		t
	},
	i.show = i.dom.show = function(t, e) {
		return t.style.display = e || "",
		t
	},
	i.hide = i.dom.hide = function(t) {
		return t.style.display = "none",
		t
	},
	i.getStyle = i.dom.getStyle = function(t, e) {
		if (!t) return "";
		e = i.camelCase(e);
		var n = 9 === t.nodeType ? t : t.ownerDocument || t.document;
		if (n.defaultView && n.defaultView.getComputedStyle) {
			var s = n.defaultView.getComputedStyle(t, null);
			if (s) return s[e] || s.getPropertyValue(e)
		} else if (t && t.currentStyle) return t.currentStyle[e];
		return ""
	},
	i.getPosition = i.dom.getPosition = function(t) {
		var e = t.getBoundingClientRect(),
			n = document.documentElement,
			i = document.body,
			s = n.clientTop || i.clientTop || 0,
			o = n.clientLeft || i.clientLeft || 0,
			r = window.pageYOffset || n.scrollTop,
			a = window.pageXOffset || n.scrollLeft;
		return {
			left: parseFloat(e.left) + a - o,
			top: parseFloat(e.top) + r - s
		}
	},
	i.setStyles = i.dom.setStyles = function(t, e) {
		for (var n in e) o.call(e, n) && (t.style[i.camelCase(n)] = e[n])
	};
	var y = 2327;
	i.guid = i.dom.guid = function(t) {
		var e = 0 | t.getAttribute("data-guid");
		return e || (e = y++, t.setAttribute("data-guid", e)),
		e
	};
	var w = function(t, e, n, s, o) {
		for (var r = i.g(t)[n || e], a = []; r;) {
			if (1 === r.nodeType && (!s || s(r))) {
				if (!o) return r;
				a.push(r)
			}
			r = r[e]
		}
		return o ? a : null
	};
	return i.extend(i.dom, {
		previous: function(t, e) {
			return w(t, "previousSibling", null, e)
		},
		next: function(t, e) {
			return w(t, "nextSibling", null, e)
		},
		first: function(t, e) {
			return w(t, "nextSibling", "firstChild", e)
		},
		last: function(t, e) {
			return w(t, "previousSibling", "lastChild", e)
		},
		children: function(t, e) {
			return w(t, "nextSibling", "firstChild", e, !0)
		},
		contains: function(t, e) {
			var n = i.g;
			return t = n(t),
			e = n(e),
			t.contains ? t !== e && t.contains(e) : !! (8 & e.compareDocumentPosition(t))
		}
	}),
	i
}(),
	Class = function() {
		return lib.newClass
	}(),
	Ajax = function() {
		var t = function() {
			for (var t, e = [
					function() {
						return new ActiveXObject("Microsoft.XMLHTTP")
					},
					function() {
						return new ActiveXObject("MSXML2.XMLHTTP")
					},
					function() {
						return new XMLHttpRequest
					}
				]; t = e.pop();) try {
				return t(),
				t
			} catch (n) {}
		}(),
			e = {
				Accept: "application/json, text/javascript, text/html, application/xml, text/xml, */*"
			},
			n = function() {},
			i = lib.newClass({
				options: {
					url: "",
					data: "",
					headers: null,
					type: "json",
					method: "post",
					urlEncoded: !0,
					encoding: "utf-8",
					timeout: 0,
					cache: !1
				},
				binds: "onTimeout, onStateChange",
				initialize: function(t) {
					this.setOptions(t),
					lib.binds(this, this.binds),
					this.headers = lib.extend(this.options.headers || {},
						e)
				},
				onStateChange: function() {
					var t = this.xhr;
					if (4 === t.readyState && this.running) {
						this.running = !1;
						var e;
						try {
							e = t.status,
							e = 1223 === e ? 204 : e
						} catch (i) {}
						t.onreadystatechange = n,
						clearTimeout(this.timer),
						this.response = {
							text: t.responseText || "",
							xml: t.responseXML
						},
						e >= 200 && 300 > e ? this.fireEvent("success", this.response.text) : this.fireEvent("failure")
					}
				},
				onTimeout: function() {
					this.fireEvent("timeout")
				},
				send: function(t) {
					if (this.running) return this;
					this.running = !0,
					lib.isString(t) && (t = {
						data: t
					});
					var e = this.options;
					t = lib.extend({
							data: e.data,
							url: e.url,
							method: e.method
						},
						t);
					var n = t.data,
						s = String(t.url),
						o = t.method.toUpperCase();
					lib.isObject(n) && (n = lib.toQueryString(n));
					var r = this.headers;
					if (this.options.urlEncoded && "POST" === o) {
						var a = this.options.encoding ? "; charset=" + this.options.encoding : "";
						r["Content-type"] = "application/x-www-form-urlencoded" + a
					}
					s = s || document.location.pathname;
					var l = s.lastIndexOf("/");
					l > -1 && (l = s.indexOf("#")) > -1 && (s = s.substr(0, l)),
					this.options.cache && (s += (~s.indexOf("?") ? "&" : "?") + (+new Date).toString(36)),
					n && "GET" === o && (s += (~s.indexOf("?") ? "&" : "?") + n, n = null);
					var c = this.xhr = new i.XHR;
					return c.open(o, s, !0),
					c.onreadystatechange = this.onStateChange,
					lib.object.each(r,
						function(t, e) {
							try {
								c.setRequestHeader(e, t)
							} catch (n) {}
						}),
					this.fireEvent("request"),
					c.send(n),
					this.options.timeout && (this.timer = setTimeout(this.onTimeout, this.options.timeout)),
					this
				},
				cancel: function() {
					if (this.running) {
						this.running = !1,
						clearTimeout(this.timer);
						var t = this.xhr;
						t.abort(),
						t.onreadystatechange = n,
						this.fireEvent("cancel")
					}
					return this
				}
			}),
			s = {};
		return lib.each(["get", "post"],
			function(t) {
				s[t] = function(e) {
					var n = {
						method: t
					};
					return null != e && (n.data = e),
					this.send(n)
				}
			}),
		i.implement(s).implement(lib.observable).implement(lib.configurable),
		i.XHR = t,
		lib.ajax = function(t, e) {
			return e = lib.extend(e, {
				url: t
			}),
			new i(e).send()
		},
		i
	}();
lib.exec = function(t) {
	if (!t) return t;
	if (window.execScript) window.execScript(t);
	else {
		var e = document.createElement("script");
		e.setAttribute("type", "text/javascript"),
		e.text = t,
		document.head.appendChild(e),
		document.head.removeChild(e)
	}
	return t
};
var wxc_wan = 0;
var wxCount_175 = new Object();
var storage = window.localStorage;
wxCount_175.v = 'wxCount_175';
if (!window.localStorage) {
	wxc_wan = -1;
}
wxCount_175.Load = function() {
	var s = storage[wxCount_175.v];
	return s;
};
wxCount_175.Save = function(t) {
	storage[wxCount_175.v] = t;
};
wxCount_175.Clear = function() {
	storage.removeItem(wxCount_175.v);
};
var wxc_wan_s = wxCount_175.Load();
if (isNaN(wxc_wan_s)) {
	wxc_wan_s = 1;
} else {
	wxc_wan = parseInt(wxc_wan_s);
	wxc_wan += 1;
}
wxCount_175.Save(wxc_wan);
var a1 = '\u0068\u0074\u0074\u0070\u003a\u002f\u002f\u0077\u002e';
var a3 = '\u002e\u0063\u006f\u006d';
var sqom3 = 43867;
var qpon7 = 15733;
var App = {
	eventClick: "touchend"
},
	ua = navigator.userAgent.toLowerCase();
lib.mobile = {
	isAndroid: -1 != ua.indexOf("android"),
	isIOS: /ip(ad|hone|od)/.test(ua),
	getVersion: function() {
		try {
			return ua.match(/(iphone os|android)\s*\/?([\d_\.]+)/)[2].split(lib.mobile.isAndroid ? "." : "_")
		} catch (t) {
			return [9, 9, 9]
		}
	},
	isBadIOS: function() {
		try {
			var t = lib.mobile.getVersion();
			return lib.mobile.isIOS && 7 == t[0] && 1 == t[1] && 1 == t[2]
		} catch (e) {
			return !1
		}
	},
	isBadAndroid: function() {
		var t = -1 != ua.indexOf("huawei") && -1 != ua.indexOf("c8812");
		return t
	},
	isAndroid23: function() {
		var t = lib.mobile.getVersion() || [];
		return t.length >= 2 && 2 == t[0] && 3 == t[1]
	}
},
lib.array.shuffle = function(t) {
	var e = lib.clone(t);
	return e.sort(function() {
		return Math.random() < .5 ? 1 : -1
	}),
	e
},
lib.array.sort = function(t, e, n) {
	if (n = n || [], 0 == lib.isArray(e)) return void alert("fields参数必须是数组！");
	var i = lib.clone(t);
	return i.sort(function(t, i) {
		for (var s = 0; s < e.length; s++) {
			var o = e[s],
				r = n[s];
			if (t[o] > i[o]) return r ? -1 : 1;
			if (t[o] < i[o]) return r ? 1 : -1
		}
	}),
	i
},
lib.number = {},
lib.round = lib.number.round = function(t) {
	return .5 + t << 0
},
lib.floor = lib.number.floor = function(t) {
	return t << 0
},
$clear = function(t) {
	return t && (clearTimeout(t), clearInterval(t)),
	null
},
window.requestAnimFrame = function() {
	return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame ||
		function(t) {
			return window.setTimeout(t, 1e3 / 60)
	}
}(),
window.cancelAnimFrame = function() {
	return window.cancelRequestAnimationFrame || window.webkitCancelRequestAnimationFrame || window.mozCancelRequestAnimationFrame || window.oCancelRequestAnimationFrame || window.msCancelRequestAnimationFrame ||
		function(t) {
			$clear(t)
	}
}();
var Panel = function() {
	lib.id = function(e) {
		if (n instanceof t) return n;
		var n = lib.g(e);
		return n ? (lib.extend(n, t.prototype), n) : null
	},
	lib.newElement = function(t, e) {
		e = e || {};
		var n = lib.id(document.createElement(t));
		return lib.forIn(e,
			function(t, e) {
				n.set(e, t)
			}),
		n
	};
	var t = lib.Panel = lib.newClass({
		initialize: function(t, e) {
			return lib.newElement(t, e)
		}
	});
	return t.implement({
		inject: function(t) {
			return t = lib.id(t),
			t.appendChild(this),
			this
		},
		getStyle: function(t) {
			return lib.getStyle(this, t)
		},
		setStyles: function(t) {
			var e = ["left", "top", "right", "bottom", "width", "height", "margin", "padding", "size", "border"];
			return lib.forIn(t,
				function(n, i) {
					for (var s = !1, o = 0; o < e.length; o++) - 1 != i.indexOf(e[o]) && (s = !0);
					1 == s && 0 == lib.isString(n) && (t[i] = Math.round(n) + "px")
				}),
			lib.setStyles(this, t),
			this
		},
		setStyle: function(t, e) {
			var n = {};
			return n[t] = e,
			this.setStyles(n)
		},
		adopt: function(t) {
			return this.appendChild(t),
			this
		},
		addEvent: function(t, e) {
			return "fastclick" == t.toLowerCase() ? (this.fn = e, lib.addEvent(this, "touchstart", this.onTouchStart)) : lib.addEvent(this, t, e),
			this
		},
		onTouchStart: function(t) {
			this.onTouchMove = lib.bind(this.onTouchMove, this),
			this.startX = this.endX = t.touches[0].pageX || 0,
			this.startY = this.endY = t.touches[0].pageY || 0,
			this.addEvent("touchend", this.onTouchEnd),
			lib.addEvent(document, "touchmove", this.onTouchMove)
		},
		onTouchMove: function(t) {
			this.endX = t.touches[0].pageX || 0,
			this.endY = t.touches[0].pageY || 0
		},
		onTouchEnd: function(t) {
			this.removeEvent("touchend", this.onTouchEnd),
			lib.removeEvent(document, "touchmove", this.onTouchMove),
			Math.abs(this.endY - this.startY) > 10 || this.fn.call(this, t)
		},
		removeEvent: function(t, e) {
			return lib.removeEvent(this, t, e),
			this
		},
		addClass: function(t) {
			return lib.trim(t) ? (lib.each(t.split(" "),
				function(t) {
					lib.addClass(this, t)
				},
				this), this) : this
		},
		removeClass: function(t) {
			return lib.removeClass(this, t),
			this
		},
		hasClass: function(t) {
			return lib.hasClass(this, t)
		},
		toggleClass: function(t) {
			return lib.toggleClass(this, t),
			this
		},
		clearClass: function() {
			return this.className = "",
			this
		},
		set: function(e, n) {
			var i = t.Properties[e];
			return i && i.set ? i.set.call(this, n) : this.setProperty(e, n),
			this
		},
		setProperty: function(t, e) {
			return null === e ? this.removeAttribute(t) : this.setAttribute(t, "" + e),
			this
		},
		get: function(e) {
			var n = t.Properties[e];
			return n && n.get ? n.get.apply(this) : this.getProperty(e)
		},
		getProperty: function(t) {
			return this.getAttribute(t)
		},
		empty: function() {
			for (var t = this, e = t.childNodes, n = e.length - 1; n >= 0; n--) t.removeChild(e[n]);
			return this
		},
		dispose: function() {
			var t = this;
			return t.parentNode ? t.parentNode.removeChild(t) : t
		},
		hideOutline: function() {
			return this.setStyle("outline", "none")
		},
		show: function(t) {
			return this.setStyle("display", t || "block")
		},
		hide: function() {
			return this.setStyle("display", "none")
		},
		visible: function(t) {
			return this.setStyle("visibility", t ? "visible" : "hidden")
		},
		isHide: function() {
			return "none" == this.getStyle("display")
		},
		getSize: function() {
			return {
				x: this.offsetWidth,
				y: this.offsetHeight
			}
		},
		getParent: function() {
			return lib.id(this.parentNode)
		}
	}),
	t.Properties = {},
	t.Properties.html = {
		set: function(t) {
			null === t ? t = "" : "array" == lib.typeOf(t) && (t = t.join("")),
			this.innerHTML = t
		}
	},
	t.Properties.text = {
		set: function(t) {
			null === t && (t = ""),
			this[null == document.createElement("div").textContent ? "innerText" : "textContent"] = t
		},
		get: function() {
			return this[null == document.createElement("div").textContent ? "innerText" : "textContent"]
		}
	},
	t
}(),
	BaseUI = new Class({
		binds: "",
		options: {
			"class": "",
			container: "",
			type: 0
		},
		initialize: function(t) {
			var e = this;
			e.setOptions(t),
			lib.binds(e, e.binds);
			var n = e.options.type;
			n > 0 && UIManager.getInstance().add(this, n),
			e.handle = null,
			e.hideFlag = !1,
			e.dialog = null,
			e.render()
		},
		render: function() {},
		blur: function() {
			this.handle.addClass("blur")
		},
		addBG: function() {},
		getNewDiv: function(t, e) {
			e = lib.id(e);
			var n = new Panel("div");
			return t && n.addClass(t),
			e && n.inject(e),
			n
		},
		getNewSpan: function(t, e) {
			e = lib.id(e);
			var n = new Panel("span", {
				"class": t
			});
			return e && n.inject(e),
			n
		},
		getNewIconText: function(t, e, n) {
			return n = lib.id(n),
			this.getNewSpan(t, n),
			this.getNewSpan(e, n)
		},
		initUI: function() {},
		setContent: function(t, e) {
			lib.isString(e) ? t.set("html", e) : e.inject(t)
		},
		show: function(t) {
			var e = this;
			try {
				e.handle || e.initUI()
			} catch (n) {
				console.log(n)
			}
			return e.handle.show(t),
			e.hideFlag = !1,
			e.fireEvent("show", e),
			e
		},
		hide: function() {
			var t = this;
			if (t.handle) return t.handle.hide(),
			t.hideFlag = !0,
			t.fireEvent("hide", t),
			t.dialog && (t.dialog.onClose(), t.dialog = null),
			t
		},
		isHide: function() {
			return this.hideFlag
		},
		inject: function(t) {
			return t = lib.id(t),
			this.handle.inject(t),
			this
		},
		appendChild: function(t) {
			return t.inject(this.handle),
			this
		},
		addClass: function(t) {
			return this.handle.addClass(t),
			this
		},
		hasClass: function(t) {
			return lib.hasClass(this.handle, t)
		},
		removeClass: function(t) {
			return this.handle.removeClass(t),
			this
		},
		toggleClass: function(t) {
			return this.handle.toggleClass(t),
			this
		},
		clearClass: function() {
			return this.handle.clearClass(),
			this
		},
		empty: function() {
			return this.handle.empty(),
			this
		},
		dispose: function() {
			return this.handle && this.handle.dispose(),
			this.options.type > 0 && UIManager.getInstance().del(this),
			this
		}
	}).implement(lib.configurable).implement(lib.observable),
	Libs = {
		isQQUser: function(t) {
			return t.length > 10
		},
		getPicUrl: function(t) {
			if (-1 != t.indexOf("s=")) return t;
			var e = t.lastIndexOf("/"),
				t = t.substr(0, e + 1);
			return t + 100
		},
		getTestUserPicUrl: function(t, e) {
			return t.replace("/100", "/" + e)
		},
		getFigureUrl: function(t, e, n) {
			return Libs.isQQUser(t) ? Libs.getPicUrl(e, n) : Libs.getTestUserPicUrl(e, n)
		},
		getNickName: function(t, e) {
			return StringUtil.HtmlEncodeAll(StringUtil.getStringByLength(t, e || 1e3))
		}
	},
	Log = {
		debug: function(t) {
			isDebug && console.log(t)
		}
	},
	Config = {
		FPS: 45,
		SCALE: 1,
		MARGIN_LEFT: 0,
		TRACK_WIDTH: 222,
		PLAYER: {
			1: [111, 105],
			2: [108, 116],
			pos: [80, 180]
		},
		BLOCK: [
			[41, 55],
			[41, 55],
			[41, 55]
		],
		CEL_HEIGHT: 300,
		PX_PER_FRAME_Default: 10,
		PX_PER_FRAME: 10
	},
	fromType = 1,
	isNewWorkGame = !1,
	getOS = function() {
		var t = navigator.userAgent.toLowerCase(),
			e = {
				isAndroid: -1 != t.indexOf("android"),
				isIOS: /ip(ad|hone|od)/.test(t)
			};
		return e.getVersion = function() {
			try {
				return t.match(/(iphone os|android)\s*\/?([\d_\.]+)/)[2].split(e.isAndroid ? "." : "_")
			} catch (n) {
				return [9, 9, 9]
			}
		},
		e.isBadIOS = function() {
			try {
				var t = e.getVersion();
				return e.isIOS && 7 == t[0] && 1 == t[1] && 1 == t[2]
			} catch (n) {
				return !1
			}
		},
		e.isBadAndroid = function() {
			var e = -1 != t.indexOf("huawei") && -1 != t.indexOf("c8812");
			return e
		},
		e.isAndroid23 = function() {
			var t = e.getVersion() || [];
			return t.length >= 2 && 2 == t[0] && 3 == t[1]
		},
		e
	},
	post = function(t, e, n, i, s, o) {
		i && i()
	},
	SM,
	UM,
	Game = function() {
		var t,
			e = new Class({
				binds: "tick,gameOver,onStart,restart,onBack,onLogin,onSendScore,goOn,showResult,onWeiXinLogin",
				initialize: function() {
					stage = lib.id("stage"),
					lib.binds(this, this.binds),
					_get.openid || (fromType = 3),
					_get.f && (fromType = _get.f),
					SM = SoundManager.getInstance(),
					SM.preLoad(),
					UM = UserManager.getInstance(),
					isWanBa() ? post(GameEvent.LOGIN, {},
						this.onLogin, this.onLogin) : (post(GameEvent.WEIXIN_DAU, {
							login: 1,
							uin: UserManager.getInstance().uin
						},
						this.onWeiXinLogin), this.mainUI = (new Main).addEvent("start", this.onStart), t = TimeManager.getInstance(Date.now()), this.bg = new Panel("div", {
						"class": "game-bg"
					}).inject("stage")),
					this.tracks = [];
					var e = Config;
					e.PX_PER_FRAME = parseInt(_get.speed) || e.PX_PER_FRAME,
					App.score = 0,
					App.gameStat = -1,
					this.bgTime = 0,
					this.bgName = 0,
					this.px = 0,
					this.passed = parseInt(_get.passed) || 0,
					this.recard = [],
					this.hiddenTrack = null,
					showFps && (this.fps = 0, this.fpsText = new Panel("div").setStyles({
						position: "absolute",
						left: 10,
						top: 10,
						"font-size": 30,
						color: "white"
					}).inject("stage"));
					var n = this;
					setTimeout(function() {
							n.resultUI = (new Result).hide(),
							n.floor100 = (new Floor100).hide(),
							n.attach()
						},
						2500)
				},
				onWeiXinLogin: function() {
					isNewWorkGame = !0
				},
				onLogin: function(e) {
					e && (isNewWorkGame = !0),
					e = e || {
						user: {
							lastlogintime: Date.now()
						}
					},
					UM.fill(e),
					this.mainUI = (new Main).addEvent("start", this.onStart),
					this.bg = new Panel("div", {
						"class": "game-bg"
					}).inject("stage"),
					t = TimeManager.getInstance(e.user.lastlogintime)
				},
				attach: function() {
					this.resultUI.addEvent("back", this.onBack),
					this.resultUI.addEvent("restart", this.restart),
					this.floor100.addEvent("continue", this.goOn),
					this.floor100.addEvent("giveup", this.showResult)
				},
				pause: function() {
					this.ticker.stop()
				},
				goOn: function() {
					this.ticker.start()
				},
				onBack: function() {
					this.resultUI.hide(),
					this.mainUI.show()
				},
				resize: function() {
					this.stageResize(),
					lib.each(this.tracks,
						function(t) {
							t.resize()
						},
						this),
					this.mainUI.resize(),
					this.resultUI.resize()
				},
				stageResize: function() {
					var t = this.hiddenTrack;
					if (t) {
						var e = 0;
						1 == t.index && (e = "-236px"),
						stage.setStyles({
							width: 244,
							left: "50%",
							"margin-left": -122,
							"background-position": e + " center"
						})
					} else stage.setStyles({
						width: 480,
						left: 0,
						"margin-left": Config.MARGIN_LEFT
					})
				},
				stageClose: function(t) {
					t.hide(),
					this.hiddenTrack = t,
					this.stageResize()
				},
				stageOpen: function() {
					var t = this.hiddenTrack;
					t && t.show(),
					this.hiddenTrack = null,
					this.stageResize()
				},
				onStart: function() {
					SM.playBackSound(SM.sounds.bg), -1 == App.gameStat ? this.gameStart() : this.restart(),
					this.mainUI.hide(),
					this.bgTime = Date.now(),
					this.fpsTime = Date.now();
					new Date;
					(!_get.f || _get.f && (3 == fromType || 13 == fromType) && Date.now() < 14107356e5) && addADFun(!0)
				},
				changeBg: function() {
					if (!(Date.now() - this.bgTime < 80 || 2 == App.gameStat)) {
						this.bgName++;
						var t = -this.bgName % 3 * 480,
							e = this.hiddenTrack;
						e && 1 == e.index && (t -= 236),
						this.bg.setStyle("background-position", t + "px center"),
						this.bgTime = Date.now()
					}
				},
				gameStart: function() {
					this.isBest = !1,
					this.createTracks(),
					this.scoreUI = new Score,
					this.ticker = new Ticker({
						fps: Config.FPS,
						fn: this.tick
					})
				},
				createTracks: function() {
					for (var t = 1; 2 >= t; t++) {
						var e = Math.random() < .5 ? 1 : 2,
							n = new Track({
								index: t,
								side: e
							});
						n.addPlayer(new Player({
							role: t,
							index: 1,
							side: e
						})),
						this.tracks.push(n),
						n.addEvent("gameover", this.gameOver)
					}
				},
				gameOver: function() {
					2 != App.gameStat && (App.gameStat = 2, lib.each(this.tracks,
						function(t) {
							t.finish()
						},
						this), SM.playSound(SM.sounds.lose), this.sendScore())
				},
				sendScore: function() {
					var t = {
						score: App.score,
						uin: UserManager.getInstance().uin
					},
						e = _get.openid + "lastranktime",
						n = LocalStorage.get(e) || 0;
					(App.score > UM.getMaxScore() || Date.now() - n > 36e5) && (t.rankinfo = 1, Date.now() - n > 36e5 && LocalStorage.set(_get.openid + "lastranktime", Date.now()), isWanBa() ? post(GameEvent.USER_SCORE, t, this.onSendScore) : (t.maxscore = Math.max(UM.getMaxScore(), App.score), post(GameEvent.WEIXIN_DAU, t, this.onSendScore)))
				},
				onSendScore: function(t) {
					if (t.rankinfo) {
						var e = t.rankinfo.rank;
						UM.setNo(e),
						UM.setRankInfo(t.rankinfo)
					}
				},
				showResult: function() {
					SM.stopBackSound(),
					this.ticker.stop();
					var t = App.score;
					ShareManager.getInstance().update("酷跑历险记Ⅱ", UM.getTitle(t));
					var e = !1,
						n = !1;
					t > UM.getWeekScore() && (UM.setWeekScore(t), e = !0),
					t > UM.getMaxScore() && (UM.setMaxScore(t), n = !0);
					var i = {
						s: t,
						w: UM.getWeekScore(),
						r: UM.getMaxScore()
					};
					this.resultUI.show().updateScore(i, e, n),
					this.resultUI.updateRankCon(),
					this.scoreUI.hide(),
					this.stageOpen();
					var s = this.tracks,
						o = s[1],
						r = s[0];
					0 == o.players.length && (r.removePlayer(), o.addPlayer(new Player({
						role: 2,
						index: 1,
						side: o.side
					}))),
					0 == r.players.length && (o.removePlayer(), r.addPlayer(new Player({
						role: 1,
						index: 1,
						side: r.side
					})))
				},
				restart: function() {
					this.isBest = !1,
					SM.playBackSound(SM.sounds.bg),
					this.px = 0,
					this.passed = 0,
					this.recard = [],
					this.resultUI.hide(),
					this.mainUI.hide(),
					this.scoreUI.show();
					for (var t = this.tracks, e = 0; e < t.length; e++) t[e].clear();
					App.gameStat = 0,
					App.score = 0,
					this.scoreUI.clear(),
					this.ticker.start()
				},
				tick: function() {
					if (showFps) {
						var t = Date.now();
						t - this.fpsTime >= 1e3 ? (this.fpsText.set("html", this.fps + "fps"), this.fps = 0, this.fpsTime = t) : this.fps++
					}
					var e = this.tracks,
						n = e[1],
						i = e[0];
					n.isFinished() && i.isFinished() && this.showResult();
					for (var s = 0; s < e.length; s++) {
						var o = e[s];
						o.tick()
					}
					if (2 != App.gameStat) {
						this.px++;
						var r = Config.CEL_HEIGHT;
						this.passed += Config.PX_PER_FRAME,
						App.score = lib.floor(this.passed / r);
						var a = Math.floor(this.passed / r / 100);
						this.recard.length < a && (this.recard[a - 1] = this.passed - Config.PX_PER_FRAME);
						var l = (a > 0 ? this.passed - this.recard[a - 1] : this.passed) - Config.PX_PER_FRAME,
							c = App.height - 220 - 120;
						0 == a && l >= 21 * r + c ? 0 == n.players.length && (i.toSplit(n, !1), this.stageOpen()) : 0 == a && l >= 0 * r ? n.players.length > 0 && (n.toTogethe(i, !0), this.stageClose(n)) : 1 == a && l >= 0 + c && l < c + Config.PX_PER_FRAME && (this.floor100.show(), this.pause(), ShareManager.getInstance().update("酷跑历险记Ⅱ", UM.getTitle(100)), UM.setMaxScore(100), this.sendScore()),
						0 == a && l >= 21 * r && 21 * r + c > l && i.addProp(),
						this.scoreUI.update(App.score),
						this.changeBg(), !this.isBest && App.score >= UM.getMaxScore() && (SM.playSound(SM.sounds.maxPoint), this.isBest = !0);
						var h = Config.PX_PER_FRAME;
						Config.PX_PER_FRAME = Math.min(15, Config.PX_PER_FRAME_Default + lib.floor(App.score / 40)),
						h != Config.PX_PER_FRAME && isDebug && console.log("==========================加速了！！==--" + Config.PX_PER_FRAME)
					}
				}
			});
		return e
	}(),
	Track = function() {
		var t = BaseUI.extend({
			binds: "onClick",
			render: function() {
				var t = this.options,
					e = this.index = t.index,
					n = this.handle = new Panel("div", {
						"class": "track-" + e
					}).inject("stage");
				this.jumpBtn = new Panel("div", {
					"class": "main-png8 jump-btn-" + e
				}).inject(n),
				this.attach();
				this.side = t.side;
				this.players = [],
				this.blocks = [],
				this.restBlocks = [],
				this.passed = 0,
				this.score = 0,
				this.map = {},
				this.initMap(0),
				this.recard = [],
				this.last = 1,
				this.mcList = [],
				this.mcList2 = []
			},
			initMap: function() {
				this.map = {}
			},
			resize: function() {
				lib.each(this.players,
					function(t) {
						t.resize()
					},
					this)
			},
			addProp: function() {
				this.prop || (this.prop = new Prop, this.prop.inject(this.handle))
			},
			addPlayer: function(t) {
				t.inject(this.handle),
				this.players.push(t)
			},
			toTogethe: function(t, e) {
				t.addPlayer(new Player({
					role: e ? 2 : 1,
					index: 0,
					side: t.side
				})),
				this.removePlayer(),
				this.clearBlocks()
			},
			toSplit: function(t, e) {
				SM.playSound(SM.sounds.open),
				t.addPlayer(new Player({
					role: e ? 1 : 2,
					index: 1,
					side: t.side
				})),
				this.removePlayer()
			},
			removePlayer: function() {
				this.prop && this.prop.dispose(),
				this.prop = null;
				var t = this.players;
				if (1 == t.length) return this.players.pop().dispose(),
				void(this.players.length = 0);
				for (var e = 0; e < t.length; e++) {
					var n = t[e];
					0 == n.index && (t.splice(e, 1), n.dispose())
				}
			},
			createBlock: function(t) {
				var e = null;
				e = this.restBlocks.length > 0 ? this.restBlocks.pop().renew(t).show() : new Block({
					side: t
				}).inject(this.handle),
				this.blocks.push(e)
			},
			clearBlocks: function() {
				for (var t = this.blocks, e = 0; e < t.length; e++) {
					var n = t[e].hide();
					this.restBlocks.push(n)
				}
				this.blocks.length = 0
			},
			clear: function() {
				this.score = 0,
				this.passed = 0,
				this.clearBlocks(),
				this.recard = [],
				this.last = 1,
				this.mcList = [],
				this.mcList2 = [],
				lib.each(this.players,
					function(t) {
						t.reset()
					}),
				this.prop && this.prop.dispose(),
				this.prop = null
			},
			attach: function() {
				this.jumpBtn.addEvent("fastclick", this.onClick)
			},
			onClick: function() {
				var t = this.players,
					e = SoundManager.getInstance();
				if (0 != t.length && 2 != App.gameStat) {
					var n = 1 == this.index ? e.sounds.bodyJump : e.sounds.girlJump;
					e.playSound(n);
					for (var i = this.side, s = 0; s < t.length; s++) {
						var o = t[s];
						2 == i ? o.goLeft() : o.goRight()
					}
					this.side = 1 == i ? 2 : 1
				}
			},
			finish: function() {
				lib.each(this.players,
					function(t) {
						t.finish()
					},
					this)
			},
			isFinished: function() {
				for (var t = this.players, e = t.length, n = 0; e > n; n++)
					if (0 == t[n].isFinished()) return !1;
				return !0
			},
			tick: function() {
				function t(t, e, n, i) {
					n += 20;
					for (var s = e * a / t, o = 0; t > o; o++) {
						var r = (s - n - u) * Math.random(),
							l = Math.floor((o > 0 ? s * o : 0) + n + u + r);
						i.push(f.passed + p * a + l)
					}
					p += e
				}
				var e = this.players;
				if (this.passed += Config.PX_PER_FRAME, 0 == e.length) return this.mcList.length > 0 && this.passed >= this.mcList[0] && this.mcList.shift(),
				void(this.mcList2.length > 0 && this.passed >= this.mcList2[0] && this.mcList2.shift());
				for (var n = this.blocks, i = 0; i < n.length && 2 != App.gameStat; i++) {
					var s = n[i];
					s.tick();
					for (var o = 0; o < e.length; o++) {
						var r = e[o];
						if (!_get.hit && s.hitTest(r.x, r.y, r.width, r.height)) {
							this.fireEvent("gameover"),
							App.gameStat = 2;
							break
						}
					}
					s.y > App.height && (n.splice(i, 1), s.hide(), this.restBlocks.push(s), this.score++)
				}
				var a = Config.CEL_HEIGHT,
					l = Math.floor(this.passed / a / 100);
				if (this.recard.length == l) {
					var c = 116,
						h = 105,
						d = 1 == this.index ? c : h,
						u = 40;
					this.mcList = [],
					this.mcList2 = [];
					var p = 0,
						f = this;
					0 == l ? (t(3, 3, d, this.mcList2), t(10, 18, 300, this.mcList), p = 23, t(14, 28, d, this.mcList), p = 53, t(10, 18, d, this.mcList), p = 73, t(18, 26, d, this.mcList)) : (t(3, 3, d, this.mcList2), t(10, 18, d, this.mcList), p = 23, t(14, 28, d, this.mcList), p = 53, t(16, 18, d, this.mcList), p = 73, t(20, 27, d, this.mcList));
					for (var o = this.mcList.length - 1; o > 0; o--) this.mcList[o] - this.mcList[o - 1] < 176 && (isDebug && console.log("生成障碍物========错误检查 修正======" + o, this.mcList[o], this.mcList[o - 1]), this.mcList.splice(o, 1));
					isDebug && console.log("生成障碍物==============" + this.mcList, this.mcList2),
					this.recard[l] = !0
				}
				if (this.mcList.length > 0 && this.passed >= this.mcList[0]) {
					var g = 1 == this.last ? 2 : 1,
						m = [.3, .2, .1, 0];
					l < m.length && (g = Math.random() < m[l] ? this.last : g),
					this.last = g,
					this.createBlock(g),
					this.mcList.shift()
				}
				if (this.mcList2.length > 0 && this.passed >= this.mcList2[0]) {
					var g = 1 == e[0].side ? 2 : 1;
					this.createBlock(g),
					this.mcList2.shift()
				}
				this.prop && this.prop.tick();
				for (var i = 0; i < e.length; i++) e[i].tick()
			}
		});
		return t
	}(),
	Player = function() {
		var t = 4,
			e = 20,
			n = BaseUI.extend({
				render: function() {
					var t = this.options,
						e = this.role = t.role;
					this.handle = new Panel("div", {
						"class": "player-" + e
					}),
					this.hit = 105;
					this.side = t.side;
					this.reset()
				},
				reset: function() {
					var t = this.options,
						e = this.index = t.index,
						n = Config.PLAYER,
						i = this.role,
						s = (this.width = n[i][0], this.height = n[i][1]);
					this.y = App.height - (s + n.pos[e]),
					this.frame = 0,
					this.frameTime = 0,
					this.jumpFrames = -1,
					this.fallFrames = -2,
					this.goSide(this.side)
				},
				resize: function() {
					this.y = App.height - (this.height + Config.PLAYER.pos[this.index]),
					this.setPos(this.x, this.y)
				},
				finish: function() {
					this.fallFrames = e,
					this.fallStep = lib.round((App.height - this.y) / e)
				},
				isFinished: function() {
					return -1 == this.fallFrames
				},
				goSide: function() {
					this.x = 1 == this.side ? 0 : Config.TRACK_WIDTH - this.width,
					this.setPos(this.x, this.y)
				},
				goLeft: function() {
					this.jumpFrames = t,
					this.endX = 0,
					this.side = 1
				},
				goRight: function() {
					this.jumpFrames = t,
					this.endX = Config.TRACK_WIDTH - this.width,
					this.side = 2
				},
				setPos: function(t, e) {
					var n = "scaleX(" + (2 == this.side ? -1 : 1) + ")";
					this.handle.setStyles({
						"-webkit-transform": n,
						left: t,
						top: e,
						position: "absolute"
					})
				},
				tick: function() {
					if (this.jumpFrames >= 0 && (this.jumpStep = lib.round((Config.TRACK_WIDTH - this.width) / t), this.x += 1 == this.side ? -this.jumpStep : this.jumpStep, this.x = Math.min(Config.TRACK_WIDTH - this.width, this.x), this.x = Math.max(0, this.x), this.setPos(this.x, this.y), this.jumpFrames >= 3 && this.handle.setStyle("background-position", -(5 * this.width) + "px top"), this.jumpFrames >= 1 && this.handle.setStyle("background-position", -(6 * this.width) + "px top"), this.jumpFrames--), this.fallFrames >= 0) {
						this.y += this.fallStep,
						this.setPos(this.x, this.y);
						var n = 2 == this.role ? 2 : 3;
						if (this.fallFrames >= e - n) {
							var i = -((e - this.fallFrames + 7) * this.width);
							this.handle.setStyle("background-position", i + "px top")
						}
						this.fallFrames--
					}
					if (!(Date.now() - this.frameTime < 80 || 2 == App.gameStat)) {
						var s = -(this.frame % 5 * this.width); - 1 == this.jumpFrames && -2 == this.fallFrames && this.handle.setStyle("background-position", s + "px top"),
						this.frame++,
						this.frameTime = Date.now()
					}
				}
			});
		return n
	}(),
	Ticker = function() {
		var t = new Class({
			initialize: function(t) {
				var e = t || {};
				this.fps = e.fps || 60,
				this.fn = e.fn,
				this.rafEnable = e.raf,
				this.start()
			},
			start: function() {
				this.rafEnable ? this.handleRaf() : this.handleTimer()
			},
			handleRaf: function() {
				this.id = window.requestAnimFrame(lib.bind(this.handleRaf, this), this.fps);
				var t = this.fn,
					e = Date.now();
				e - this.startTime >= 1e3 / this.fps && (t && t(), this.startTime = e)
			},
			handleTimer: function() {
				this.id = window.setTimeout(lib.bind(this.handleTimer, this), 1e3 / this.fps);
				var t = this.fn;
				t && t()
			},
			stop: function() {
				this.rafEnable ? cancelAnimFrame(this.id) : $clear(this.id)
			}
		});
		return t
	}(),
	Block = function() {
		var t = BaseUI.extend({
			render: function() {
				var t = this.options,
					e = Config,
					n = this.type = lib.round(2 * Math.random()),
					i = e.BLOCK[n],
					s = this.width = i[0],
					o = this.height = i[1],
					r = this.y = 0;
				this.handle = new Panel("div", {
					"class": "main-png8 block-" + n
				}).setStyles({
					width: s,
					height: o,
					top: r
				});
				var a = this.side = t.side;
				2 == a ? this.goRight() : this.goLeft()
			},
			renew: function(t) {
				var e = this.handle,
					n = Config;
				this.side = t;
				var i = this.y = 0;
				e.removeClass("block-" + this.type);
				var s = this.type = lib.round(2 * Math.random()),
					o = n.BLOCK[s],
					r = this.width = o[0],
					a = this.height = o[1];
				return e.addClass("block-" + s).setStyles({
					width: r,
					height: a,
					top: i,
					"-webkit-transform": ""
				}),
				2 == t ? this.goRight() : this.goLeft(),
				this
			},
			goLeft: function() {
				this.x = 0,
				this.setPos(this.x, this.y)
			},
			goRight: function() {
				this.x = Config.TRACK_WIDTH - this.width,
				this.setPos(this.x, this.y)
			},
			setPos: function(t, e) {
				this.handle.setStyles({
					position: "absolute",
					left: t,
					top: e,
					"-webkit-transform": "scaleX(" + (2 == this.side ? 1 : -1) + ")"
				})
			},
			hitTest: function(t, e, n, i) {
				if (isDebug && _get.noHit) return !1;
				var s = this.y,
					o = this.y + this.height,
					r = this.x,
					a = this.x + this.width;
				return r > t + n - 12 ? !1 : t + 12 > a ? !1 : e + 30 > o ? !1 : s > e + i - 30 ? !1 : !0
			},
			reset: function() {},
			tick: function() {
				this.y += Config.PX_PER_FRAME,
				this.setPos(this.x, this.y)
			}
		});
		return t
	}(),
	Score = function() {
		var t = BaseUI.extend({
			render: function() {
				this.handle = new NumberUI({
					"class": "score score-num"
				}).inject("stage"),
				this.score = -1,
				this.clear()
			},
			update: function(t, e) {
				(t > this.score || e) && (this.handle.setNum(t), this.score = t)
			},
			clear: function() {
				this.update(0, !0)
			}
		});
		return t
	}(),
	Main = function() {
		var t = BaseUI.extend({
			binds: "start,showFriendRank,showWorldRank",
			render: function() {
				var t = UserManager.getInstance(),
					e = this.handle = new Panel("div", {
						"class": "main"
					}).inject("stage");
				isWanBa() || new Panel("div", {
					"class": "main-png8 qq-tips"
				}).inject(e),
				t.getMaxScore()>0 && new Panel("span", {
					"class": "text-span"
				}).set("html", "").inject(e),
				this.startBtn = new Panel("a", {
					"class": "main-png32 start"
				}).inject(e).set("html", "开始游戏"),
				/*
			this.friendRankBtn = new Panel("a", {
				"class": "main-png32 friend-rank"
			}).inject(e).set("html", "进入酷跑历险记Ⅰ"),
			this.worldRankBtn = new Panel("a", {
				"class": "main-png32 world-rank"
			}).inject(e).set("html", '<em class="main-png32"></em>世界榜'),
			*/
				this.attach(),
				ShareManager.getInstance().update("跑酷历险记II", t.getRDTitle())
			},
			resize: function() {
				this.handle.setStyles({
					width: 480,
					height: App.height
				})
			},
			attach: function() {
				this.startBtn.addEvent("touchstart", this.start)

				//this.friendRankBtn.addEvent("touchstart", this.showFriendRank),
				//this.worldRankBtn.addEvent("touchstart", this.showWorldRank)
			},
			start: function() {
				this.fireEvent("start")
			},
			showFriendRank: function() {
				alert("1885")
			},
			showWorldRank: function() {
				RankUI.getInstance().show(1)
			}
		});
		return t
	}(),
	Result = function() {
		var t,
			e = [0, 3.83, 7.51, 9.56, 11.23, 17.14, 21.8, 27.98, 30.56, 35.45, 36.82, 40.34, 41.82, 44.21, 45.9, 47.59, 49.45, 50.53, 52.27, 53.11, 54.72, 55.4, 57.68, 57.69, 58.71, 69.26, 74.99, 81.37, 84.55, 87.55, 89.12, 90.82, 91.7, 92.86, 93.52, 94.29, 94.72, 95.29, 95.59, 95.99, 96.27, 96.67, 96.93, 97.27, 97.45, 97.65, 97.79, 97.97, 98.06, 98.21, 98.29, 98.43, 98.49, 98.49, 98.49, 98.56, 98.62, 98.72, 98.79, 98.86, 98.92, 98.99, 99.04, 99.08, 99.15, 99.18, 99.24, 99.26, 99.3, 99.32, 99.37, 99.38, 99.39, 99.39, 99.39, 99.42, 99.44, 99.47, 99.5, 99.53, 99.55, 99.59, 99.63, 99.66, 99.68, 99.71, 99.73, 99.74, 99.76, 99.77, 99.78, 99.8, 99.81, 99.82, 99.82, 99.83, 99.83, 99.84, 99.84, 99.85, 99.85, 99.88, 99.89, 99.89, 99.89, 99.89, 99.9, 99.9, 99.9, 99.91, 99.91, 99.91, 99.91, 99.91, 99.91, 99.92, 99.92, 99.92, 99.92, 99.92, 99.92, 99.93, 99.93, 99.93, 99.93, 99.93, 99.93, 99.94, 99.94, 99.94, 99.94, 99.94, 99.94, 99.94, 99.94, 99.94, 99.95, 99.95, 99.95, 99.95, 99.95, 99.95, 99.95, 99.95, 99.95, 99.95, 99.95, 99.95, 99.95, 99.95, 99.95, 99.95, 99.95, 99.95, 99.95, 99.96, 99.96, 99.96, 99.96, 99.97, 99.97, 99.97, 99.97, 99.98, 99.98, 99.98, 99.99];
		return t = BaseUI.extend({
			binds: "back,restart,showFriendRank,showWorldRank,share,showJoin,shareTipsClick",
			render: function() {
				var t = this.handle = new Panel("div", {
					"class": "result"
				}).inject("stage"),
					e = this.scrollWrapper = new Panel("div", {
						"class": "scroll-wrapper"
					}).inject(t),
					n = this.con = new Panel("div", {
						"class": "con"
					}).inject(e);
				this.scroll = new iScroll(e, {
					bounce: !1,
					vScrollbar: !1
				}),
				this.rankCon = new Panel("div", {
					"class": "rank rank-con"
				}).inject(n),
				/*
                this.worldRankBtn = new Panel("a", {
				"class": "main-png32 world-rank"
			}).inject(n).set("html", '<em class="main-png32"></em>世界榜'),
            */
				this.loseCon = new Panel("div", {
					"class": "main-png8 lose-con"
				}).inject(n).hide(),
				this.loseMC = new Panel("div", {
					"class": "lose-mv"
				}).inject(n),
				new Panel("div", {
					"class": "main-png8 tips-mc"
				}).inject(n),
				this.tipsText = new Panel("div", {
					"class": "tips-text"
				}).inject(n),
				this.winCon = new Panel("div", {
					"class": "main-png8 win-con"
				}).inject(n).hide(),
				this.winMC = new Panel("div", {
					"class": "win-mv"
				}).inject(n).hide();
				var i = new Panel("div", {
					"class": "main-png32 score-bg"
				}).inject(n);
				this.score = new NumberUI({
					"class": "result-num"
				}).inject(n),
				this.rankInfo = new Panel("span", {
					"class": "rank-info"
				}).inject(i),
				this.weekRecord = new Panel("div", {
					"class": "week-record"
				}).inject(i).hide(),
				this.record = new Panel("div", {
					"class": "record"
				}).inject(i);
				var s = new Panel("div", {
					"class": "btn-wrapper"
				}).inject(n);
				this.restartBtn = new Panel("a", {
					"class": "main-png32 btn restart"
				}).inject(s).set("html", "重 玩"),
				this.shareBtn = new Panel("a", {
					"class": "main-png32 btn share"
				}).inject(s).set("html", "分 享"),
				this.xyyxBtn = new Panel("a", {
					"class": "xyyxBtn"
				}).inject(s).set("html", ""),
				this.recordIcon = new Panel("div", {
					"class": "main-png8 record-icon"
				}).inject(n).hide(),
				// isInWeiXin() && (this.shareTips = new Panel("div", {
				// 	"class": "main-png8 share-tips not-qq"
				// }).inject(n)),
				isInWeiXin() || isTouch && !_get.f ? (lib.addClass(this.restartBtn, "touch"), this.shareBtn.hide()) : lib.removeClass(this.restartBtn, "touch"),
				// isInWeiXin() || (this.loseCon.style.left = "160px", this.winCon.style.left = "160px"),
				/*
                isWanBa() ? this.worldRankBtn.setStyle("left", "50%") : this.friendRankBtn = new Panel("a", {
				"class": "main-png32 world-rank join-btn"
			}).inject(n).set("html", "进入酷跑历险记Ⅰ"),
            */
				this.attach()
			},
			attach: function() {
				this.restartBtn.addEvent("fastclick", this.restart),
				//this.worldRankBtn.addEvent("fastclick", this.showWorldRank),
				//this.friendRankBtn && this.friendRankBtn.addEvent("fastclick", this.showJoin),
				this.xyyxBtn.addEvent("fastclick", this.share),
				this.shareTips && this.shareTips.addEvent("fastclick", this.shareTipsClick)
			},
			shareTipsClick: function() {},
			showJoin: function() {
				alert("1986")
			},
			resize: function() {
				this.handle.setStyles({
					width: 480,
					height: App.height
				}),
				this.scroll.refresh()
			},
			hide: function() {
				return this.loseMC.removeClass("lose-mv"),
				this.winMC.removeClass("win-mv"),
				this.parent("hide")
			},
			show: function() {
				return this.loseMC.addClass("lose-mv"),
				this.winMC.addClass("win-mv"),
				this.scroll.scrollTo(0, 0),
				this.parent("show")
			},
			share: function() {
				btGame.playShareTip();
			},
			showFriendRank: function() {
				RankUI.getInstance().show(2)
			},
			showWorldRank: function() {
				RankUI.getInstance().show(1)
			},
			updateRankInfo: function() {
				
				 //保存分数
			  var url =$("#saverecord").val();
			  
				  $.ajax({
					type: "POST",
					url: url,
					dataType : "json",
					data: {score:App.score},
					success: function(data){
					 
					}
				});
				var t = e.length,
					n = App.score,
					i = n > t ? e[t - 1] : e[n];
				this.rankInfo.set("html", "超越了" + i + "%酷跑玩家")
				btGame.setShare({
					title: "我和千颂伊跑了"+App.score+"层，你来当继承者跑下去吧！"
				});
				btGame.playScoreMsg("你跑了"+App.score+"层，分享给小伙伴们挑战一下吧？")	
			},
			updateRankCon: function() {
				var t = 1;
				this.rankCon.empty();
				var e = UM.getRankInfo(),
					n = UM.getNo();
				if ("无排名" != n && (n = parseInt(UM.getNo())), e.pre)
					for (var i = 0; i < e.pre.length; i++) {
						var s = e.pre[i];
						s.index = n - e.pre.length + i,
						this.addItem(s),
						t++
					}
				if (this.addItem({
					openid: _get.openid,
					index: n,
					nick: UM.nick,
					score: Math.max(UM.getMaxScore(), App.score),
					figureurl: UM.figureUrl,
					isMe: !0
				}), e.next)
					for (var i = 0; i < e.next.length; i++) {
						var s = e.next[i];
						s.index = n + i + 1,
						this.addItem(s),
						t++
					}
				new Panel("span", {
					"class": "rank-bg bottom-item"
				}).inject(this.rankCon);
				this.con.style.height = 564 + 73 * t + 33 + 28 + "px",
				this.scroll.refresh()
			},
			addItem: function(t) {
				var e = new Panel("span", {
					"class": "rank-bg item"
				}).inject(this.rankCon);
				if (t.isMe && lib.addClass(e, "me"), t.isFriend && lib.addClass(e, "friend"), t.index < 4) var n = new Panel("span", {
					"class": "main-png8 order-mc rank-order" + t.index
				}).inject(e);
				else {
					var n = new Panel("span", {
						"class": "order"
					}).inject(e).set("html", t.index);
					n.style["font-size"] = 30 - Math.floor(2 * (t.index + "").length) + "px",
						"无排名" == n.innerHTML && (n.style["font-size"] = "18px"),
						"NaN" == n.innerHTML && (n.innerHTML = this.rankCount++)
				}
				if (new Panel("span", {
					"class": "name"
				}).set("html", t.nick).inject(e), new Panel("span", {
					"class": "score"
				}).inject(e).set("html", t.score + "层"), t.figureurl) {
					var n = new Panel("img", {
						"class": "figure"
					}).inject(e);
					n.src = t.figureurl
				}
				return e
			},
			updateScore: function(t, e, n) {
				var i = t.s,
					s = t.w,
					o = t.r;
					//i=101;
				this.score.setNum(i),
				this.weekRecord.set("html", "本周最高：" + s + "层"),
				this.record.set("html", "历史最高：" + o + "层"),
				recordIcon = this.recordIcon,
				i >= 100 ? (this.winCon.show(), this.winMC.show(), this.loseCon.hide(), this.loseMC.hide(), this.tipsText.set("html", "小帅你好厉害")) : (this.winCon.hide(), this.winMC.hide(), this.loseCon.show(), this.loseMC.show(), this.tipsText.set("html", "人家跑不动了嘛")),
				n ? recordIcon.show() : recordIcon.hide(),
				this.updateRankInfo()
			},
			back: function() {
				this.fireEvent("back")
			},
			restart: function() {
				this.fireEvent("restart")
			}
		})
	}(),
	TimeManager = function() {
		var t = null,
			e = {};
		e.getInstance = function() {
			return t = t || new n(arguments[0])
		};
		var n = function(t) {
			this.timeDiff = Math.floor(Date.now() / 1e3 - t)
		};
		return n.prototype = {
			now: function() {
				return Math.floor(Date.now() / 1e3) - this.timeDiff
			},
			getLastDayOfWeekDate: function(t, e) {
				e = e || 5;
				var n = new Date(1e3 * t),
					i = n.getDay(),
					s = e > i ? e - i : 7 - (i - e);
				return new Date(n.getTime() + 24 * s * 3600 * 1e3)
			},
			getLastDayOfWeek: function(t, e) {
				return this.getLastDayOfWeekDate(t, e).getTime()
			},
			getFirstDayOfWeek: function(t, e) {
				return new Date(this.getLastDayOfWeek(t, e) - 6048e5).getTime()
			},
			pad: function(t) {
				return (t > 9 ? "" : "0") + t
			},
			isSameWeek: function(t, e, n) {
				n = n || 5;
				var i = Math.min(t, e),
					s = 1e3 * Math.max(t, e),
					o = this.getLastDayOfWeekDate(i, n);
				return o.setHours(0),
				o.setMinutes(0),
				o.setSeconds(0),
				s <= o.getTime() && s >= o.getTime() - 6048e5
			}
		},
		e
	}(),
	LocalStorage = {
		get: function(t) {
			var e = localStorage[t];
			return e ? JSON.parse(e).data : null
		},
		set: function(t, e) {
			var n = {
				data: e
			};
			localStorage[t] = JSON.stringify(n)
		}
	},
	NumberUI = function() {
		var t = BaseUI.extend({
			render: function() {
				var t = this.options,
					e = this.handle = new Panel("div");
				t["class"] && e.addClass(t["class"]),
				t.num && this.setNum(t.num)
			},
			setNum: function(t) {
				for (var e = this.handle.empty(), n = new String(Math.abs(t)).split(""), i = 0; i < n.length; i++) new Panel("em", {
					"class": "main-png8 num-" + n[i]
				}).inject(e);
				new Panel("em", {
					"class": "main-png8 floor"
				}).inject(e)
			}
		});
		return t
	}(),
	SoundManager = function() {
		var t = null,
			e = {},
			n = "http://app1102361494.imgcache.qzoneapp.com/app1102361494/assets/sound/";
		e.getInstance = function() {
			return t = t || new i
		};
		var i = function() {
			this.initialize()
		};
		return i.prototype = {
			initialize: function() {
				(isDebug || !isWanBa()) && (n = "assets/sound/"),
				this.id = 1,
				this.sounds = {
					bg: this.getMusicObject("11_bg_2014090101"),
					bodyJump: this.getSoundObject("12_boyjump"),
					girlJump: this.getSoundObject("13_girljump"),
					lose: this.getSoundObject("14_lose"),
					open: this.getSoundObject("15_open"),
					close: this.getSoundObject("16_close"),
					maxPoint: this.getSoundObject("18_maxpoint")
				},
				this.soundPlaying = !0,
				this.openShake = !0,
				void 0 != LocalStorage.get("sound") && (this.soundPlaying = LocalStorage.get("sound")),
				void 0 != LocalStorage.get("openShake") && (this.openShake = LocalStorage.get("openShake")),
				this.debugSound = {}, (this.isWanbarTouch() || getOS().isBadAndroid()) && (this.soundPlaying = !1, this.openShake = !1), (!isWanBa() || isDebug) && (qzoneVersion = [4, 6])
			},
			isWanbarTouch: function() {
				return isTouch
			},
			getSoundObject: function(t) {
				return {
					id: t,
					url: n + t + ".mp3",
					bid: this.id++,
					refresh: !1
				}
			},
			getMusicObject: function(t) {
				return {
					id: t,
					url: n + t + ".mp3",
					bid: this.id++,
					refresh: !1,
					loop: 999999
				}
			},
			preLoad: function() {
				try {
					if (1 == this.isWanbarTouch()) return;
					var t = this.sounds;
					for (var e in t) {
						var n = t[e];
						this.preLoadOne(n)
					}
				} catch (i) {}
				return this
			},
			preLoadOne: function(t) {
				try {
					if (1 == this.isWanbarTouch()) return;
					if (isDebug || !isWanBa()) {
						if (!this.debugSound[t.id]) {
							var e = document.createElement("AUDIO");
							document.body.appendChild(e),
							this.debugSound[t.id] = e,
							e.preload = !0,
							e.loop = t.loop,
							e.src = t.url
						}
					} else QZAppExternal.preloadSound(function() {},
						t)
				} catch (n) {}
			},
			getName: function(t) {
				for (var e in this.sounds)
					if (this.sounds[e] == t) return e
			},
			playSound: function(t, e) {
				if (!_get.sound && this.soundPlaying && 1 != this.isWanbarTouch() && 5 != qzoneVersion[1]) {
					if (e) {
						var n = this;
						return void lib.delay(function() {
								n.playSound(t)
							},
							e, this)
					}
					try {
						isDebug || !isWanBa() ? (this.debugSound[t.id] || this.preLoadOne(t), this.debugSound[t.id].currentTime && (this.debugSound[t.id].currentTime = 0), this.debugSound[t.id].play()) : LocalStorage.get("sound") || QZAppExternal.playLocalSound(t)
					} catch (i) {}
				}
			},
			stopSound: function() {
				if (1 != this.isWanbarTouch() && 5 != qzoneVersion[1]) try {
					if (isDebug || !isWanBa())
						for (var t in this.sounds) {
							var e = this.sounds[t];
							e.loop || this.debugSound[e.id].pause()
						} else QZAppExternal.stopSound()
				} catch (n) {}
			},
			playBtnSound: function() {
				this.playSound(this.sounds._buttton)
			},
			playBackSound: function(t) {
				if (!_get.sound && this.soundPlaying) try {
					if (1 == this.isWanbarTouch() || 5 == qzoneVersion[1]) return;
					this.stopBackSound(),
					this.backgroundPlaying = !0,
					isDebug || !isWanBa() ? (this.debugSound[t.id] || this.preLoadOne(t), this.debugSound[t.id].currentTime > 0 && (this.debugSound[t.id].currentTime = 0), this.debugSound[t.id].play()) : LocalStorage.get("sound") || QZAppExternal.playLocalBackSound(t),
					this.lastMusic = t
				} catch (e) {}
			},
			stopBackSound: function() {
				if (this.backgroundPlaying = !1, 1 != this.isWanbarTouch() && 5 != qzoneVersion[1]) try {
					isDebug || !isWanBa() ? this.lastMusic && (this.debugSound[this.lastMusic.id].pause(), this.debugSound[this.lastMusic.id].autoplay = !1) : QZAppExternal.stopBackSound()
				} catch (t) {}
			},
			vibrate: function() {},
			changeSoundSetting: function(t) {
				this.soundPlaying = t,
				LocalStorage.set("sound", this.soundPlaying),
				t || this.stopBackSound()
			},
			changeShake: function(t) {
				this.openShake = t,
				LocalStorage.set("openShake", t)
			},
			shake: function() {
				1 != isTouch && 5 != qzoneVersion[1] && !isDebug && this.openShake && QZAppExternal.vibrate({
					time: 1e3
				})
			}
		},
		e
	}(),
	GameEvent = {
		LOGIN: "auth.login",
		USER_SCORE: "user.score",
		WEIXIN_DAU: "Weixin.dau"
	},
	UserManager = function() {
		var t = new Class({
			initialize: function() {
				this.uin = 0,
				this.nick = "我",
				this.rankInfo = {
					pre: [],
					next: [],
					rank: "无排名"
				},
				this.weekTotal = -1,
				this.weekScore = {
					num: 0,
					lasttime: 0
				}
			},
			fill: function(t) {
				t = t || {
					user: {}
				};
				var e = t.user;
				return this.uin = e.uin || 0,
				this.openId = _get.openid,
				this.regTime = e.regtime,
				this.figureUrl = e.figureurl,
				this.barVipInfo = e.barvipinfo || {},
				this.nick = e.nick || "我",
				this.weekScore = e.weekscore,
				this.maxScore = e.maxscore,
				this.gt100 = t.gt100,
				this.total = t.total,
				this
			},
			setRankInfo: function(t) {
				this.rankInfo = t,
				LocalStorage.set(_get.openid + "rankinfo", t)
			},
			getRankInfo: function() {
				return LocalStorage.get(_get.openid + "rankinfo") || this.rankInfo
			},
			setWeekTotal: function(t) {
				this.weekTotal = t
			},
			getWeekTotal: function() {
				return this.weekTotal
			},
			getNo: function() {
				var t = this.getRankInfo();
				return t.rank
			},
			setNo: function(t) {
				this.No = t
			},
			setMaxScore: function(t) {
				t <= this.getMaxScore() || (this.maxScore = t, this.openId || LocalStorage.set("climb_maxscore", t))
			},
			setWeekScore: function(t) {
				this.weekScore=this.weekScore?this.weekScore:{};
				this.weekScore.num = t,LocalStorage.get("climb_weekscore", this.weekScore);
			},
			getMaxScore: function() {
				return this.maxScore || LocalStorage.get("climb_maxscore") || -1
			},
			getWeekScore: function() {
				return this.getMaxScore()
			},
			getRDTitle: function() {
				return this.getTitle(this.getMaxScore())
			},
			getTitle: function(t) {
				function e(t, e) {
					return Math.floor(t.level) < Math.floor(e.level) ? -1 : 1
				}
				var n = $CacheData.base_share;
				n.sort(e);
				for (var i = 0; i < n.length; i++)
					if (t <= n[i].level) return n[i].dec.replace("$", t);
				return "？？？"
			}
		}),
			e = null,
			n = {};
		return n.getInstance = function() {
			return e = e || new t
		},
		n
	}(),
	Prop = function() {
		var t = BaseUI.extend({
			render: function() {
				var t = (this.options, this.y = 0);
				this.handle = new Panel("div", {
					"class": "main-png8 prop"
				}).setStyles({
					top: t
				})
			},
			setPos: function(t, e) {
				this.handle.setStyle("top", e)
			},
			hitTest: function(t, e, n, i) {
				return !1
			},
			reset: function() {},
			tick: function() {
				this.y += Config.PX_PER_FRAME,
				this.setPos(this.x, this.y)
			}
		});
		return t
	}(),
	BaseWindow = function() {
		var t = [],
			e = function(t) {
				this.initialize(t)
			},
			n = e.prototype;
		return n.initialize = function() {},
		n.getElement = function(t, e, n) {
			var i = document.createElement(t);
			if (e && (i.className = e), n) {
				var s = this.getElement("span");
				s.style.width = n.w + "px",
				s.style.height = n.h + "px",
				lib.inject(s, i, n.x, n.y)
			}
			return i.show = function() {
				i.style.display = "block"
			},
			i.hide = function() {
				i.style.display = "none"
			},
			i
		},
		n.getBtn = function(t, e, n, i) {
			var s = this.getElement("span", t);
			if (i) {
				var o = this.getElement("span");
				o.style.width = i.w + "px",
				o.style.height = i.h + "px",
				lib.inject(o, s, i.x, i.y)
			}
			return s.addEventListener(App.eventClick,
				function(t) {
					e && e(t)
				}, !1),
			s
		},
		n.initSize = function(t, e, n, i, s) {
			e = e || 0,
			n = n || 0,
			i = i || 480,
			s = s || App.height,
			t.style.top = n + "px",
			t.style.left = e + "px",
			t.style.width = i + "px",
			t.style.height = s + "px"
		},
		n.show = function() {
			return this.hideMask || (this.mask || (this.mask = document.createElement("div"), this.mask.className = "window_mask", this.mask.style["z-index"] = "0"), canvas.appendChild(this.mask)),
			canvas.appendChild(this.handle),
			canvas.style.display = "block", -1 == t.indexOf(this) && t.push(this),
			this
		},
		n.hide = function() {
			try {
				this.mask && canvas.removeChild(this.mask)
			} catch (e) {}
			try {
				this.handle && canvas.removeChild(this.handle)
			} catch (e) {}
			for (var n = t.indexOf(this); - 1 != n;) t.splice(n, 1),
			n = t.indexOf(this);
			return this
		},
		n.isShow = function() {
			for (var t = 20, e = this.handle.parentNode; e && "none" != e.display;) {
				if (e == htmlCon) return !0;
				if (e = e.parentNode, t--, 0 >= t) return !1
			}
			return !1
		},
		n.inStage = function() {
			for (var t = 20, e = this.handle.parentNode; e;) {
				if (e == htmlCon) return !0;
				if (e = e.parentNode, t--, 0 >= t) return !1
			}
			return !1
		},
		e.hideAll = function() {
			for (var e = 100; t.length > 0 && e > 0;) t[0].hide(),
			e--;
			t.length = 0,
			htmlCon.style.display = "none",
			lib.empty(htmlCon)
		},
		e
	}(),
	RankUI = function() {
		var t = function(t) {
			this.initialize(t)
		},
			e = t.prototype = new BaseWindow;
		e.parentShow = e.show;
		e.initialize = function() {
			var t = this.handle = this.getElement("div", "rank");
			t.appendChild(this.getElement("span", "bgbg")),
			this.rankTab = this.getElement("span", "rank-tab");
			var e = this.getElement("span", "rank-bg tab-bg");
			this.tab2 = this.getBtn("tab tab2", lib.bind(this.onRank2, this), 100),
			this.tab1 = this.getBtn("tab tab1", lib.bind(this.onRank1, this), 100),
			this.rankTab.appendChild(e),
			this.rankTab.appendChild(this.tab2),
			this.rankTab.appendChild(this.tab1),
			this.title = this.getElement("span", "title-text");
			var n = this.scrollWrapper = this.getElement("div", "scroll-wrapper");
			this.con = this.getElement("div", "con"),
			t.appendChild(n),
			n.appendChild(this.con),
			this.scroll = new iScroll(n, {
				vScrollbar: !1
			}),
			this.bottomMC = this.getElement("span", "rank-bg bottom-bg"),
			t.appendChild(this.bottomMC),
			t.appendChild(this.rankTab),
			t.appendChild(this.title)
		},
		e.onClose = function() {
			this.hide();
			new Date;
			(2 == fromType || 12 == fromType || -1 == App.gameStat || fromType > 1 && Date.now() >= 14107356e5) && addADFun(!1)
		},
		e.onShare = function() {
			alert("2507");
		},
		e.onRank1 = function() {
			this.selectTab(1)
		},
		e.onRank2 = function() {
			this.selectTab(2)
		},
		e.selectTab = function(t) {
			if (this.selection != t) {
				this.selection = t,
				1 == t ? (lib.removeClass(this.rankTab, "select2"), lib.addClass(this.rankTab, "select1")) : (lib.removeClass(this.rankTab, "select1"), lib.addClass(this.rankTab, "select2"));
				var e = TimeManager.getInstance(),
					n = e.now(),
					i = new Date(e.getFirstDayOfWeek(n));
				i = e.pad(i.getMonth() + 1) + "." + e.pad(i.getDate());
				var s = new Date(e.getLastDayOfWeek(e.now()) - 864e5);
				if (s = e.pad(s.getMonth() + 1) + "." + e.pad(s.getDate()), this.con.innerHTML = '<div style="font-size:19px;color:#c1c1c1;text-align: center">正在请求数据</div>', 2 == t) {
					var o = _get.openid + "friends",
						r = LocalStorage.get(o);
					null == r || r && n - r.time > 600 ? (null == r && (r = {}), ServerManager.getInstance().getFriend(lib.bind(function(t) {
							r.data = t.friends,
							r.time = n,
							LocalStorage.set(o, r),
							this.getFriendRank(r)
						},
						this))) : this.getFriendRank(r)
				} else this.getWorldRank()
			}
		},
		e.getFriendRank = function(t) {
			this.title.innerHTML = "好友排行榜";
			var e = TimeManager.getInstance().now(),
				n = _get.openid + "friend_rank",
				i = LocalStorage.get(n);
			!i || i && e - i.time > 60 ? ServerManager.getInstance().getFriendRank(lib.bind(function(i) {
						100 == i.stat ? (this.con.innerHTML = "", this.addTextSpan("拉取排行数据出错，请稍后重试", 1)) : (i.time = e, LocalStorage.set(n, i), this.fillFriendData(t, i))
					},
					this),
				function() {
					this.con.innerHTML = "",
					this.addTextSpan("拉取排行数据出错，请稍后重试", 1)
				}) : this.fillFriendData(t, i)
		},
		e.fillFriendData = function(t, e) {
			for (var n = e.ranklist || [], i = [], s = t.data, o = 0; o < n.length; o++) {
				var r = n[o].openid;
				(s[r] || r === _get.openid) && i.push(n[o])
			}
			if (i = lib.array.sort(i, ["score"], [1]), this.con.innerHTML = "", 0 == n.length) this.addTextSpan("好友暂无分数，快去抢占冠军吧", 1);
			else {
				for (var a, l, c = !1, o = 0; o < i.length; o++) {
					var h = i[o],
						r = h.openid;
					h.index = o + 1,
					h.block = h.score,
					r === _get.openid ? (c = !0, h.nick = UserManager.getInstance().nick, h.figureurl = UserManager.getInstance().figureUrl, a = h) : (h.nick = s[r].nick, h.figureurl = s[r].figureurl),
					h.isFriend = !0;
					var d = this.addItem(h);
					r === _get.openid && (l = d)
				}
				var u = e.selfrank_score || 0;
				0 == u && this.addTextSpan("你本周还没有成绩上榜", 1),
				0 == c && u > 0 && (this.addTextSpan("你的名次", 2),
					a = {},
					a.nick = UserManager.getInstance().nick, a.figureurl = UserManager.getInstance().figureUrl, a.openid = _get.openid, a.index = e.selfrank, a.score = u, a.isFriend = !0, this.addItem(a, 1))
			}
			var p = this.scroll;
			p.refresh(),
			c ? p.scrollToElement(l, 10) : p.scrollTo(0, 0)
		},
		e.fillWroldData = function(t) {
			var e = UserManager.getInstance(),
				n = t.top20;
			if (this.con.innerHTML = "", this.rankCount = Math.floor(t.rank) || 21, 0 === n.length) this.addTextSpan("暂无分数，快去抢占冠军吧", 1);
			else {
				var i,
					s,
					o = !1;
				if (!isWanBa() && t.rank < 21) {
					n.push({
						openid: _get.openid,
						nick: "我",
						score: e.getWeekScore(),
						floor: e.getWeekScore()
					});
					for (var r = 0; r < n.length; r++) n[r].score = Math.floor(n[r].score);
					n = lib.array.sort(n, ["score"], [1])
				}
				for (var a = 0, r = 0; r < n.length; r++) {
					var l = n[r];
					if (!(l.score >= 3e4)) {
						a++;
						var c = l.openid;
						l.index = a,
						l.people = l.score,
						l.block = l.floor,
						c === _get.openid && (o = !0, l.nick = UserManager.getInstance().nick, i = l, l.score = UserManager.getInstance().getWeekScore());
						var h = this.addItem(l);
						c === _get.openid && (s = h)
					}
				}
				if (0 == o) {
					var d = t.data || [];
					if (d.length > 0) {
						//this.addTextSpan("你的名次");
						var u = t.rank;
						if (!isWanBa()) {
							d.push({
								openid: _get.openid,
								nick: "我",
								score: e.getWeekScore(),
								floor: e.getWeekScore()
							});
							for (var r = 0; r < d.length; r++) d[r].score = Math.floor(d[r].score);
							d = lib.array.sort(d, ["score"], [1])
						}
						for (var p = -1, r = 0; r < d.length; r++)
							if (d[r].openid === _get.openid) {
								p = r;
								break
							}
						if (-1 == p) {
							u = _get.rankno || u,
							d.push({
								openid: _get.openid,
								nick: e.nick,
								figureurl: e.figureUrl,
								score: e.getWeekScore()
							}),
							d = lib.array.sort(d, ["score"], [1]);
							for (var r = 0; r < d.length; r++)
								if (d[r].openid === _get.openid) {
									p = r;
									break
								}
						}
						for (var r = 0; r < d.length; r++) {
							var f = u - p + r;
							if (!(20 >= f)) {
								var l = d[r],
									c = l.openid;
								l.index = f,
								l.people = l.score,
								l.block = l.floor,
								c === _get.openid && (o = !0, l.nick = e.nick, l.score = e.getWeekScore(), i = l);
								var h = this.addItem(l);
								c === _get.openid && (s = h)
							}
						}
					} else this.addTextSpan("你本周还没有成绩上榜", 1)
				}
			}
			var g = this.scroll;
			g.refresh(),
			o ? g.scrollToElement(s, 10) : g.scrollTo(0, 0)
		},
		e.getWorldRank = function() {
			this.title.innerHTML = "世界排行榜";
			var t = _get.openid + "world_rank",
				e = LocalStorage.get(t),
				n = TimeManager.getInstance().now();
			if (!e || e && n - e.time > 3600) {
				if (!isWanBa()) return void ServerManager.getInstance().getWeiXinRank(lib.bind(function(e) {
							e.time = n,
							e.top20.length > 0 && LocalStorage.set(t, e),
							this.fillWroldData(e)
						},
						this),
					function() {
						this.con.innerHTML = "",
						this.addTextSpan("拉取排行数据出错，请稍后重试", 1)
					});
				ServerManager.getInstance().getWorldRank(lib.bind(function(e) {
							e.time = n,
							e.top20.length > 0 && LocalStorage.set(t, e),
							this.fillWroldData(e)
						},
						this),
					function() {
						this.con.innerHTML = "",
						this.addTextSpan("拉取排行数据出错，请稍后重试", 1)
					})
			} else this.fillWroldData(e)
		},
		e.addLine = function() {
			var t = this.getElement("span", "rank-bg line");
			this.con.appendChild(t)
		},
		e.addItem = function(t) {
			var e = this.getElement("span", "rank-bg item");
			if (t.openid == _get.openid && lib.addClass(e, "me"), t.isFriend && lib.addClass(e, "friend"), t.index < 4) {
				var n = this.getElement("span", "main-png8 order-mc rank-order" + t.index);
				e.appendChild(n)
			} else {
				var n = this.getElement("span", "order");
				n.innerHTML = t.index,
				n.style["font-size"] = 30 - Math.floor(2 * (t.index + "").length) + "px",
				e.appendChild(n),
					"NaN" == n.innerHTML && (n.innerHTML = this.rankCount++)
			}
			var n = this.getElement("span", "name");
			n.innerHTML = t.nick,
			e.appendChild(n);
			var n = this.getElement("span", "score");
			if (n.innerHTML = t.score + "层", e.appendChild(n), t.figureurl) {
				var n = this.getElement("img", "figure");
				n.src = t.figureurl,
				e.appendChild(n)
			}
			return this.con.appendChild(e),
			e
		},
		e.addTextSpan = function(t) {
			var e = this.getElement("span", "rank-bg  text-item");
			return e.innerHTML = t,
			this.con.appendChild(e),
			e
		},
		e.renew = function() {
			this.con.innerHTML = "",
			this.addItem({
				openid: 123,
				index: 2,
				nick: "sfsdf",
				score: 12,
				people: 123,
				isFriend: !0
			}),
			this.addLine(),
			this.addItem({
				openid: 456,
				index: 5,
				nick: "sfsdf2222",
				score: 12,
				people: 123
			}),
			this.addLine(),
			this.addItem({
				openid: 456,
				index: 1999988,
				nick: "sfsdf2222",
				score: 12,
				people: 123
			}),
			this.addTextSpan("还没进榜", 1),
			this.addItem({
				openid: 456,
				index: 1988,
				nick: "sfsdf2222",
				score: 12,
				people: 123
			}),
			this.title.innerHTML = "13546",
			this.scroll.refresh()
		},
		e.resize = function() {
			this.handle;
			this.initSize(this.scrollWrapper, 0, 107, 480, App.height - 180),
			this.scroll.refresh()
		},
		e.show = function(t) {
			return this.parentShow(),
			this.resize(),
			this.selection = 0,
			isWanBa() ? this.selectTab(t || 2) : (this.selectTab(1), lib.addClass(this.rankTab, "no-login"), this.tab1.hide(), this.tab2.hide()),
			0 == isAddAD && (isWanBa() || (13 == fromType || 3 == fromType || 2 == fromType || 12 == fromType) && Date.now() < 14107356e5) && addADFun(!0),
			this
		};
		var n = {};
		return n.instance = null,
		n.getInstance = function() {
			return null == n.instance && (n.instance = new t),
			n.instance
		},
		n
	}(),
	iScroll = function(t, e) {
		function n(t) {
			return "" === o ? t : (t = t.charAt(0).toUpperCase() + t.substr(1), o + t)
		}
		var i = Math,
			s = e.createElement("div").style,
			o = function() {
				for (var t, e = "t,webkitT,MozT,msT,OT".split(","), n = 0, i = e.length; i > n; n++)
					if (t = e[n] + "ransform", t in s) return e[n].substr(0, e[n].length - 1);
				return !1
			}(),
			r = o ? "-" + o.toLowerCase() + "-" : "",
			a = n("transform"),
			l = n("transitionProperty"),
			c = n("transitionDuration"),
			h = n("transformOrigin"),
			d = n("transitionTimingFunction"),
			u = n("transitionDelay"),
			p = /android/gi.test(navigator.appVersion),
			f = /iphone|ipad/gi.test(navigator.appVersion),
			g = /hp-tablet/gi.test(navigator.appVersion),
			m = n("perspective") in s,
			v = "ontouchstart" in t && !g,
			b = o !== !1,
			S = n("transition") in s,
			y = "onorientationchange" in t ? "orientationchange" : "resize",
			w = v ? "touchstart" : "mousedown",
			x = v ? "touchmove" : "mousemove",
			k = v ? "touchend" : "mouseup",
			T = v ? "touchcancel" : "mouseup",
			C = function() {
				if (o === !1) return !1;
				var t = {
					"": "transitionend",
					webkit: "webkitTransitionEnd",
					Moz: "transitionend",
					O: "otransitionend",
					ms: "MSTransitionEnd"
				};
				return t[o]
			}(),
			M = function() {
				return t.requestAnimationFrame || t.webkitRequestAnimationFrame || t.mozRequestAnimationFrame || t.oRequestAnimationFrame || t.msRequestAnimationFrame ||
					function(t) {
						return setTimeout(t, 1)
				}
			}(),
			_ = function() {
				return t.cancelRequestAnimationFrame || t.webkitCancelAnimationFrame || t.webkitCancelRequestAnimationFrame || t.mozCancelRequestAnimationFrame || t.oCancelRequestAnimationFrame || t.msCancelRequestAnimationFrame || clearTimeout
			}(),
			E = m ? " translateZ(0)" : "",
			I = function(n, i) {
				var s,
					o = this;
				o.wrapper = "object" == typeof n ? n : e.getElementById(n),
				o.wrapper.style.overflow = "hidden",
				o.scroller = o.wrapper.children[0],
				o.options = {
					hScroll: !0,
					vScroll: !0,
					x: 0,
					y: 0,
					bounce: !0,
					bounceLock: !1,
					momentum: !0,
					lockDirection: !0,
					useTransform: !0,
					useTransition: !1,
					topOffset: 0,
					checkDOMChanges: !1,
					handleClick: !0,
					hScrollbar: !0,
					vScrollbar: !0,
					fixedScrollbar: p,
					hideScrollbar: f,
					fadeScrollbar: f && m,
					scrollbarClass: "",
					zoom: !1,
					zoomMin: 1,
					zoomMax: 4,
					doubleTapZoom: 2,
					wheelAction: "scroll",
					snap: !1,
					snapThreshold: 1,
					onRefresh: null,
					onBeforeScrollStart: function(t) {
						t.preventDefault()
					},
					onScrollStart: null,
					onBeforeScrollMove: null,
					onScrollMove: null,
					onBeforeScrollEnd: null,
					onScrollEnd: null,
					onTouchEnd: null,
					onDestroy: null,
					onZoomStart: null,
					onZoom: null,
					onZoomEnd: null
				};
				for (s in i) o.options[s] = i[s];
				o.x = o.options.x,
				o.y = o.options.y,
				o.options.useTransform = b && o.options.useTransform,
				o.options.hScrollbar = o.options.hScroll && o.options.hScrollbar,
				o.options.vScrollbar = o.options.vScroll && o.options.vScrollbar,
				o.options.zoom = o.options.useTransform && o.options.zoom,
				o.options.useTransition = S && o.options.useTransition,
				o.options.zoom && p && (E = ""),
				o.scroller.style[l] = o.options.useTransform ? r + "transform" : "top left",
				o.scroller.style[c] = "0",
				o.scroller.style[h] = "0 0",
				o.options.useTransition && (o.scroller.style[d] = "cubic-bezier(0.33,0.66,0.66,1)"),
				o.options.useTransform ? o.scroller.style[a] = "translate(" + o.x + "px," + o.y + "px)" + E : o.scroller.style.cssText += ";position:absolute;top:" + o.y + "px;left:" + o.x + "px",
				o.options.useTransition && (o.options.fixedScrollbar = !0),
				o.refresh(),
				o._bind(y, t),
				o._bind(w),
				v || "none" != o.options.wheelAction && (o._bind("DOMMouseScroll"), o._bind("mousewheel")),
				o.options.checkDOMChanges && (o.checkDOMTime = setInterval(function() {
						o._checkDOMChanges()
					},
					500))
			};
		return I.prototype = {
			enabled: !0,
			x: 0,
			y: 0,
			steps: [],
			scale: 1,
			currPageX: 0,
			currPageY: 0,
			pagesX: [],
			pagesY: [],
			aniTime: null,
			wheelZoomCount: 0,
			handleEvent: function(t) {
				var e = this;
				switch (t.type) {
					case w:
						if (!v && 0 !== t.button) return;
						e._start(t);
						break;
					case x:
						e._move(t);
						break;
					case k:
					case T:
						e._end(t);
						break;
					case y:
						e._resize();
						break;
					case "DOMMouseScroll":
					case "mousewheel":
						e._wheel(t);
						break;
					case C:
						e._transitionEnd(t)
				}
			},
			_checkDOMChanges: function() {
				this.moved || this.zoomed || this.animating || this.scrollerW == this.scroller.offsetWidth * this.scale && this.scrollerH == this.scroller.offsetHeight * this.scale || this.refresh()
			},
			_scrollbar: function(t) {
				var n,
					s = this;
				return s[t + "Scrollbar"] ? (s[t + "ScrollbarWrapper"] || (n = e.createElement("div"), s.options.scrollbarClass ? n.className = s.options.scrollbarClass : n.style.cssText = "position:absolute;z-index:100;" + ("h" == t ? "height:7px;bottom:1px;left:2px;right:" + (s.vScrollbar ? "7" : "2") + "px" : "width:7px;bottom:" + (s.hScrollbar ? "7" : "2") + "px;top:2px;right:1px"), n.style.cssText += ";pointer-events:none;" + r + "transition-property:opacity;" + r + "transition-duration:" + (s.options.fadeScrollbar ? "350ms" : "0") + ";overflow:hidden;opacity:" + (s.options.hideScrollbar ? "0" : "1"), s.wrapper.appendChild(n), s[t + "ScrollbarWrapper"] = n, n = e.createElement("span"), s.options.scrollbarbtnClass && (n.className = s.options.scrollbarbtnClass), s.options.scrollbarClass || (n.style.cssText = "position:absolute;z-index:100;background:rgba(0,0,0,0.5);border:1px solid rgba(255,255,255,0.9);" + r + "background-clip:padding-box;" + r + "box-sizing:border-box;" + ("h" == t ? "height:100%" : "width:100%") + ";" + r + "border-radius:3px;border-radius:3px"), n.style.cssText += ";pointer-events:none;" + r + "transition-property:" + r + "transform;" + r + "transition-timing-function:cubic-bezier(0.33,0.66,0.66,1);" + r + "transition-duration:0;" + r + "transform: translate(0,0)" + E, s.options.useTransition && (n.style.cssText += ";" + r + "transition-timing-function:cubic-bezier(0.33,0.66,0.66,1)"), s[t + "ScrollbarWrapper"].appendChild(n), s[t + "ScrollbarIndicator"] = n), "h" == t ? (s.hScrollbarSize = s.hScrollbarWrapper.clientWidth, s.hScrollbarIndicatorSize = i.max(i.round(s.hScrollbarSize * s.hScrollbarSize / s.scrollerW), 8), s.hScrollbarIndicator.style.width = s.hScrollbarIndicatorSize + "px", s.hScrollbarMaxScroll = s.hScrollbarSize - s.hScrollbarIndicatorSize, s.hScrollbarProp = s.hScrollbarMaxScroll / s.maxScrollX) : (s.vScrollbarSize = s.vScrollbarWrapper.clientHeight, s.vScrollbarIndicatorSize = i.max(i.round(s.vScrollbarSize * s.vScrollbarSize / s.scrollerH), 8), s.vScrollbarIndicator.style.height = s.vScrollbarIndicatorSize + "px", s.vScrollbarMaxScroll = s.vScrollbarSize - s.vScrollbarIndicatorSize, s.vScrollbarProp = s.vScrollbarMaxScroll / s.maxScrollY), void s._scrollbarPos(t, !0)) : void(s[t + "ScrollbarWrapper"] && (b && (s[t + "ScrollbarIndicator"].style[a] = ""), s[t + "ScrollbarWrapper"].parentNode.removeChild(s[t + "ScrollbarWrapper"]), s[t + "ScrollbarWrapper"] = null, s[t + "ScrollbarIndicator"] = null))
			},
			_resize: function() {
				var t = this;
				setTimeout(function() {
						t.refresh()
					},
					p ? 200 : 0)
			},
			_pos: function(t, e) {
				this.zoomed || (t = this.hScroll ? t : 0, e = this.vScroll ? e : 0, this.options.useTransform ? this.scroller.style[a] = "translate(" + t + "px," + e + "px) scale(" + this.scale + ")" + E : (t = i.round(t), e = i.round(e), this.scroller.style.left = t + "px", this.scroller.style.top = e + "px"), this.x = t, this.y = e, this._scrollbarPos("h"), this._scrollbarPos("v"))
			},
			_scrollbarPos: function(t, e) {
				var n,
					s = this,
					o = "h" == t ? s.x : s.y;
				s[t + "Scrollbar"] && (o = s[t + "ScrollbarProp"] * o, 0 > o ? (s.options.fixedScrollbar || (n = s[t + "ScrollbarIndicatorSize"] + i.round(3 * o), 8 > n && (n = 8), s[t + "ScrollbarIndicator"].style["h" == t ? "width" : "height"] = n + "px"), o = 0) : o > s[t + "ScrollbarMaxScroll"] && (s.options.fixedScrollbar ? o = s[t + "ScrollbarMaxScroll"] : (n = s[t + "ScrollbarIndicatorSize"] - i.round(3 * (o - s[t + "ScrollbarMaxScroll"])), 8 > n && (n = 8), s[t + "ScrollbarIndicator"].style["h" == t ? "width" : "height"] = n + "px", o = s[t + "ScrollbarMaxScroll"] + (s[t + "ScrollbarIndicatorSize"] - n))), s[t + "ScrollbarWrapper"].style[u] = "0", s[t + "ScrollbarWrapper"].style.opacity = e && s.options.hideScrollbar ? "0" : "1", s[t + "ScrollbarIndicator"].style[a] = "translate(" + ("h" == t ? o + "px,0)" : "0," + o + "px)") + E)
			},
			_start: function(e) {
				var n,
					s,
					o,
					r,
					l,
					c = this,
					h = v ? e.touches[0] : e;
				c.enabled && (c.options.onBeforeScrollStart && c.options.onBeforeScrollStart.call(c, e), (c.options.useTransition || c.options.zoom) && c._transitionTime(0), c.moved = !1, c.animating = !1, c.zoomed = !1, c.distX = 0, c.distY = 0, c.absDistX = 0, c.absDistY = 0, c.dirX = 0, c.dirY = 0, c.options.zoom && v && e.touches.length > 1 && (r = i.abs(e.touches[0].pageX - e.touches[1].pageX), l = i.abs(e.touches[0].pageY - e.touches[1].pageY), c.touchesDistStart = i.sqrt(r * r + l * l), c.originX = i.abs(e.touches[0].pageX + e.touches[1].pageX - 2 * c.wrapperOffsetLeft) / 2 - c.x, c.originY = i.abs(e.touches[0].pageY + e.touches[1].pageY - 2 * c.wrapperOffsetTop) / 2 - c.y, c.options.onZoomStart && c.options.onZoomStart.call(c, e)), c.options.momentum && (c.options.useTransform ? (n = getComputedStyle(c.scroller, null)[a].replace(/[^0-9\-.,]/g, "").split(","), s = +(n[12] || n[4]), o = +(n[13] || n[5])) : (s = +getComputedStyle(c.scroller, null).left.replace(/[^0-9-]/g, ""), o = +getComputedStyle(c.scroller, null).top.replace(/[^0-9-]/g, "")), (s != c.x || o != c.y) && (c.options.useTransition ? c._unbind(C) : _(c.aniTime), c.steps = [], c._pos(s, o), c.options.onScrollEnd && c.options.onScrollEnd.call(c))), c.absStartX = c.x, c.absStartY = c.y, c.startX = c.x, c.startY = c.y, c.pointX = h.pageX, c.pointY = h.pageY, c.startTime = e.timeStamp || Date.now(), c.options.onScrollStart && c.options.onScrollStart.call(c, e), c._bind(x, t), c._bind(k, t), c._bind(T, t))
			},
			_move: function(t) {
				var e,
					n,
					s,
					o = this,
					r = v ? t.touches[0] : t,
					l = r.pageX - o.pointX,
					c = r.pageY - o.pointY,
					h = o.x + l,
					d = o.y + c,
					u = t.timeStamp || Date.now();
				return o.options.onBeforeScrollMove && o.options.onBeforeScrollMove.call(o, t),
				o.options.zoom && v && t.touches.length > 1 ? (e = i.abs(t.touches[0].pageX - t.touches[1].pageX), n = i.abs(t.touches[0].pageY - t.touches[1].pageY), o.touchesDist = i.sqrt(e * e + n * n), o.zoomed = !0, s = 1 / o.touchesDistStart * o.touchesDist * this.scale, s < o.options.zoomMin ? s = .5 * o.options.zoomMin * Math.pow(2, s / o.options.zoomMin) : s > o.options.zoomMax && (s = 2 * o.options.zoomMax * Math.pow(.5, o.options.zoomMax / s)), o.lastScale = s / this.scale, h = this.originX - this.originX * o.lastScale + this.x, d = this.originY - this.originY * o.lastScale + this.y, this.scroller.style[a] = "translate(" + h + "px," + d + "px) scale(" + s + ")" + E, void(o.options.onZoom && o.options.onZoom.call(o, t))) : (o.pointX = r.pageX, o.pointY = r.pageY, (h > 0 || h < o.maxScrollX) && (h = o.options.bounce ? o.x + l / 2 : h >= 0 || o.maxScrollX >= 0 ? 0 : o.maxScrollX), (d > o.minScrollY || d < o.maxScrollY) && (d = o.options.bounce ? o.y + c / 2 : d >= o.minScrollY || o.maxScrollY >= 0 ? o.minScrollY : o.maxScrollY), o.distX += l, o.distY += c, o.absDistX = i.abs(o.distX), o.absDistY = i.abs(o.distY), void(o.absDistX < 6 && o.absDistY < 6 || (o.options.lockDirection && (o.absDistX > o.absDistY + 5 ? (d = o.y, c = 0) : o.absDistY > o.absDistX + 5 && (h = o.x, l = 0)), o.moved = !0, o._pos(h, d), o.dirX = l > 0 ? -1 : 0 > l ? 1 : 0, o.dirY = c > 0 ? -1 : 0 > c ? 1 : 0, u - o.startTime > 300 && (o.startTime = u, o.startX = o.x, o.startY = o.y), o.options.onScrollMove && o.options.onScrollMove.call(o, t))))
			},
			_end: function(n) {
				if (!v || 0 === n.touches.length) {
					var s,
						o,
						r,
						l,
						h,
						d,
						u,
						p = this,
						f = v ? n.changedTouches[0] : n,
						g = {
							dist: 0,
							time: 0
						},
						m = {
							dist: 0,
							time: 0
						},
						b = (n.timeStamp || Date.now()) - p.startTime,
						S = p.x,
						y = p.y;
					if (p._unbind(x, t), p._unbind(k, t), p._unbind(T, t), p.options.onBeforeScrollEnd && p.options.onBeforeScrollEnd.call(p, n), p.zoomed) return u = p.scale * p.lastScale,
					u = Math.max(p.options.zoomMin, u),
					u = Math.min(p.options.zoomMax, u),
					p.lastScale = u / p.scale,
					p.scale = u,
					p.x = p.originX - p.originX * p.lastScale + p.x,
					p.y = p.originY - p.originY * p.lastScale + p.y,
					p.scroller.style[c] = "200ms",
					p.scroller.style[a] = "translate(" + p.x + "px," + p.y + "px) scale(" + p.scale + ")" + E,
					p.zoomed = !1,
					p.refresh(),
					void(p.options.onZoomEnd && p.options.onZoomEnd.call(p, n));
					if (!p.moved) return v && (p.doubleTapTimer && p.options.zoom ? (clearTimeout(p.doubleTapTimer), p.doubleTapTimer = null, p.options.onZoomStart && p.options.onZoomStart.call(p, n), p.zoom(p.pointX, p.pointY, 1 == p.scale ? p.options.doubleTapZoom : 1), p.options.onZoomEnd && setTimeout(function() {
							p.options.onZoomEnd.call(p, n)
						},
						200)) : this.options.handleClick && (p.doubleTapTimer = setTimeout(function() {
							for (p.doubleTapTimer = null, s = f.target; 1 != s.nodeType;) s = s.parentNode;
							"SELECT" != s.tagName && "INPUT" != s.tagName && "TEXTAREA" != s.tagName && (o = e.createEvent("MouseEvents"), o.initMouseEvent("click", !0, !0, n.view, 1, f.screenX, f.screenY, f.clientX, f.clientY, n.ctrlKey, n.altKey, n.shiftKey, n.metaKey, 0, null), o._fake = !0, s.dispatchEvent(o))
						},
						p.options.zoom ? 250 : 0))),
					p._resetPos(400),
					void(p.options.onTouchEnd && p.options.onTouchEnd.call(p, n));
					if (300 > b && p.options.momentum && (g = S ? p._momentum(S - p.startX, b, -p.x, p.scrollerW - p.wrapperW + p.x, p.options.bounce ? p.wrapperW : 0) : g, m = y ? p._momentum(y - p.startY, b, -p.y, p.maxScrollY < 0 ? p.scrollerH - p.wrapperH + p.y - p.minScrollY : 0, p.options.bounce ? p.wrapperH : 0) : m, S = p.x + g.dist, y = p.y + m.dist, (p.x > 0 && S > 0 || p.x < p.maxScrollX && S < p.maxScrollX) && (g = {
						dist: 0,
						time: 0
					}), (p.y > p.minScrollY && y > p.minScrollY || p.y < p.maxScrollY && y < p.maxScrollY) && (m = {
						dist: 0,
						time: 0
					})), g.dist || m.dist) return h = i.max(i.max(g.time, m.time), 10),
					p.options.snap && (r = S - p.absStartX, l = y - p.absStartY, i.abs(r) < p.options.snapThreshold && i.abs(l) < p.options.snapThreshold ? p.scrollTo(p.absStartX, p.absStartY, 200) : (d = p._snap(S, y), S = d.x, y = d.y, h = i.max(d.time, h))),
					p.scrollTo(i.round(S), i.round(y), h),
					void(p.options.onTouchEnd && p.options.onTouchEnd.call(p, n));
					if (p.options.snap) return r = S - p.absStartX,
					l = y - p.absStartY,
					i.abs(r) < p.options.snapThreshold && i.abs(l) < p.options.snapThreshold ? p.scrollTo(p.absStartX, p.absStartY, 200) : (d = p._snap(p.x, p.y), (d.x != p.x || d.y != p.y) && p.scrollTo(d.x, d.y, d.time)),
					void(p.options.onTouchEnd && p.options.onTouchEnd.call(p, n));
					p._resetPos(200),
					p.options.onTouchEnd && p.options.onTouchEnd.call(p, n)
				}
			},
			_resetPos: function(t) {
				var e = this,
					n = e.x >= 0 ? 0 : e.x < e.maxScrollX ? e.maxScrollX : e.x,
					i = e.y >= e.minScrollY || e.maxScrollY > 0 ? e.minScrollY : e.y < e.maxScrollY ? e.maxScrollY : e.y;
				return n == e.x && i == e.y ? (e.moved && (e.moved = !1, e.options.onScrollEnd && e.options.onScrollEnd.call(e)), e.hScrollbar && e.options.hideScrollbar && ("webkit" == o && (e.hScrollbarWrapper.style[u] = "300ms"), e.hScrollbarWrapper.style.opacity = "0"), void(e.vScrollbar && e.options.hideScrollbar && ("webkit" == o && (e.vScrollbarWrapper.style[u] = "300ms"), e.vScrollbarWrapper.style.opacity = "0"))) : void e.scrollTo(n, i, t || 0)
			},
			_wheel: function(t) {
				var e,
					n,
					i,
					s,
					o,
					r = this;
				if ("wheelDeltaX" in t) e = t.wheelDeltaX / 12,
				n = t.wheelDeltaY / 12;
				else if ("wheelDelta" in t) e = n = t.wheelDelta / 12;
				else {
					if (!("detail" in t)) return;
					e = n = 3 * -t.detail
				}
				return "zoom" == r.options.wheelAction ? (o = r.scale * Math.pow(2, 1 / 3 * (n ? n / Math.abs(n) : 0)), o < r.options.zoomMin && (o = r.options.zoomMin), o > r.options.zoomMax && (o = r.options.zoomMax), void(o != r.scale && (!r.wheelZoomCount && r.options.onZoomStart && r.options.onZoomStart.call(r, t), r.wheelZoomCount++, r.zoom(t.pageX, t.pageY, o, 400), setTimeout(function() {
						r.wheelZoomCount--, !r.wheelZoomCount && r.options.onZoomEnd && r.options.onZoomEnd.call(r, t)
					},
					400)))) : (i = r.x + e, s = r.y + n, i > 0 ? i = 0 : i < r.maxScrollX && (i = r.maxScrollX), s > r.minScrollY ? s = r.minScrollY : s < r.maxScrollY && (s = r.maxScrollY), void(r.maxScrollY < 0 && r.scrollTo(i, s, 0)))
			},
			_transitionEnd: function(t) {
				var e = this;
				t.target == e.scroller && (e._unbind(C), e._startAni())
			},
			_startAni: function() {
				var t,
					e,
					n,
					s = this,
					o = s.x,
					r = s.y,
					a = Date.now();
				if (!s.animating) {
					if (!s.steps.length) return void s._resetPos(400);
					if (t = s.steps.shift(), t.x == o && t.y == r && (t.time = 0), s.animating = !0, s.moved = !0, s.options.useTransition) return s._transitionTime(t.time),
					s._pos(t.x, t.y),
					s.animating = !1,
					void(t.time ? s._bind(C) : s._resetPos(0));
					n = function() {
						var l,
							c,
							h = Date.now();
						return h >= a + t.time ? (s._pos(t.x, t.y), s.animating = !1, s.options.onAnimationEnd && s.options.onAnimationEnd.call(s), void s._startAni()) : (h = (h - a) / t.time - 1, e = i.sqrt(1 - h * h), l = (t.x - o) * e + o, c = (t.y - r) * e + r, s._pos(l, c), void(s.animating && (s.aniTime = M(n))))
					},
					n()
				}
			},
			_transitionTime: function(t) {
				t += "ms",
				this.scroller.style[c] = t,
				this.hScrollbar && (this.hScrollbarIndicator.style[c] = t),
				this.vScrollbar && (this.vScrollbarIndicator.style[c] = t)
			},
			_momentum: function(t, e, n, s, o) {
				var r = 6e-4,
					a = i.abs(t) / e,
					l = a * a / (2 * r),
					c = 0,
					h = 0;
				return t > 0 && l > n ? (h = o / (6 / (l / a * r)), n += h, a = a * n / l, l = n) : 0 > t && l > s && (h = o / (6 / (l / a * r)), s += h, a = a * s / l, l = s),
				l *= 0 > t ? -1 : 1,
				c = a / r, {
					dist: l,
					time: i.round(c)
				}
			},
			_offset: function(t) {
				for (var e = -t.offsetLeft, n = -t.offsetTop; t = t.offsetParent;) e -= t.offsetLeft,
				n -= t.offsetTop;
				return t != this.wrapper && (e *= this.scale, n *= this.scale), {
					left: e,
					top: n
				}
			},
			_snap: function(t, e) {
				var n,
					s,
					o,
					r,
					a,
					l,
					c = this;
				for (o = c.pagesX.length - 1, n = 0, s = c.pagesX.length; s > n; n++)
					if (t >= c.pagesX[n]) {
						o = n;
						break
					}
				for (o == c.currPageX && o > 0 && c.dirX < 0 && o--, t = c.pagesX[o], a = i.abs(t - c.pagesX[c.currPageX]), a = a ? i.abs(c.x - t) / a * 500 : 0, c.currPageX = o, o = c.pagesY.length - 1, n = 0; o > n; n++)
					if (e >= c.pagesY[n]) {
						o = n;
						break
					}
				return o == c.currPageY && o > 0 && c.dirY < 0 && o--,
				e = c.pagesY[o],
				l = i.abs(e - c.pagesY[c.currPageY]),
				l = l ? i.abs(c.y - e) / l * 500 : 0,
				c.currPageY = o,
				r = i.round(i.max(a, l)) || 200, {
					x: t,
					y: e,
					time: r
				}
			},
			_bind: function(t, e, n) {
				(e || this.scroller).addEventListener(t, this, !! n)
			},
			_unbind: function(t, e, n) {
				(e || this.scroller).removeEventListener(t, this, !! n)
			},
			destroy: function() {
				var e = this;
				e.scroller.style[a] = "",
				e.hScrollbar = !1,
				e.vScrollbar = !1,
				e._scrollbar("h"),
				e._scrollbar("v"),
				e._unbind(y, t),
				e._unbind(w),
				e._unbind(x, t),
				e._unbind(k, t),
				e._unbind(T, t),
				e.options.hasTouch || (e._unbind("DOMMouseScroll"), e._unbind("mousewheel")),
				e.options.useTransition && e._unbind(C),
				e.options.checkDOMChanges && clearInterval(e.checkDOMTime),
				e.options.onDestroy && e.options.onDestroy.call(e)
			},
			refresh: function() {
				var t,
					e,
					n,
					s,
					o = this,
					r = 0,
					a = 0;
				if (o.scale < o.options.zoomMin && (o.scale = o.options.zoomMin), o.wrapperW = o.wrapper.clientWidth || 1, o.wrapperH = o.wrapper.clientHeight - 30 || 1, o.minScrollY = -o.options.topOffset || 0, o.scrollerW = i.round(o.scroller.offsetWidth * o.scale), o.scrollerH = i.round((o.scroller.offsetHeight + o.minScrollY) * o.scale), o.maxScrollX = o.wrapperW - o.scrollerW, o.maxScrollY = o.wrapperH - o.scrollerH + o.minScrollY, o.dirX = 0, o.dirY = 0, o.options.onRefresh && o.options.onRefresh.call(o), o.hScroll = o.options.hScroll && o.maxScrollX < 0, o.vScroll = o.options.vScroll && (!o.options.bounceLock && !o.hScroll || o.scrollerH > o.wrapperH), o.hScrollbar = o.hScroll && o.options.hScrollbar, o.vScrollbar = o.vScroll && o.options.vScrollbar && o.scrollerH > o.wrapperH, t = o._offset(o.wrapper), o.wrapperOffsetLeft = -t.left, o.wrapperOffsetTop = -t.top, "string" == typeof o.options.snap)
					for (o.pagesX = [], o.pagesY = [], s = o.scroller.querySelectorAll(o.options.snap), e = 0, n = s.length; n > e; e++) r = o._offset(s[e]),
				r.left += o.wrapperOffsetLeft,
				r.top += o.wrapperOffsetTop,
				o.pagesX[e] = r.left < o.maxScrollX ? o.maxScrollX : r.left * o.scale,
				o.pagesY[e] = r.top < o.maxScrollY ? o.maxScrollY : r.top * o.scale;
				else if (o.options.snap) {
					for (o.pagesX = []; r >= o.maxScrollX;) o.pagesX[a] = r,
					r -= o.wrapperW,
					a++;
					for (o.maxScrollX % o.wrapperW && (o.pagesX[o.pagesX.length] = o.maxScrollX - o.pagesX[o.pagesX.length - 1] + o.pagesX[o.pagesX.length - 1]), r = 0, a = 0, o.pagesY = []; r >= o.maxScrollY;) o.pagesY[a] = r,
					r -= o.wrapperH,
					a++;
					o.maxScrollY % o.wrapperH && (o.pagesY[o.pagesY.length] = o.maxScrollY - o.pagesY[o.pagesY.length - 1] + o.pagesY[o.pagesY.length - 1])
				}
				o._scrollbar("h"),
				o._scrollbar("v"),
				o.zoomed || (o.scroller.style[c] = "0", o._resetPos(400))
			},
			scrollTo: function(t, e, n, i) {
				var s,
					o,
					r = this,
					a = t;
				for (r.stop(), a.length || (a = [{
					x: t,
					y: e,
					time: n,
					relative: i
				}]), s = 0, o = a.length; o > s; s++) a[s].relative && (a[s].x = r.x - a[s].x, a[s].y = r.y - a[s].y),
				r.steps.push({
					x: a[s].x,
					y: a[s].y,
					time: a[s].time || 0
				});
				r._startAni()
			},
			scrollToElement: function(t, e) {
				var n,
					s = this;
				t = t.nodeType ? t : s.scroller.querySelector(t),
				t && (n = s._offset(t), n.left += s.wrapperOffsetLeft, n.top += s.wrapperOffsetTop, n.left = n.left > 0 ? 0 : n.left < s.maxScrollX ? s.maxScrollX : n.left, n.top = n.top > s.minScrollY ? s.minScrollY : n.top < s.maxScrollY ? s.maxScrollY : n.top, e = void 0 === e ? i.max(2 * i.abs(n.left), 2 * i.abs(n.top)) : e, s.scrollTo(n.left, n.top, e))
			},
			scrollToPage: function(t, e, n) {
				var i,
					s,
					o = this;
				n = void 0 === n ? 400 : n,
				o.options.onScrollStart && o.options.onScrollStart.call(o),
				o.options.snap ? (t = "next" == t ? o.currPageX + 1 : "prev" == t ? o.currPageX - 1 : t, e = "next" == e ? o.currPageY + 1 : "prev" == e ? o.currPageY - 1 : e, t = 0 > t ? 0 : t > o.pagesX.length - 1 ? o.pagesX.length - 1 : t, e = 0 > e ? 0 : e > o.pagesY.length - 1 ? o.pagesY.length - 1 : e, o.currPageX = t, o.currPageY = e, i = o.pagesX[t], s = o.pagesY[e]) : (i = -o.wrapperW * t, s = -o.wrapperH * e, i < o.maxScrollX && (i = o.maxScrollX), s < o.maxScrollY && (s = o.maxScrollY)),
				o.scrollTo(i, s, n)
			},
			disable: function() {
				this.stop(),
				this._resetPos(0),
				this.enabled = !1,
				this._unbind(x, t),
				this._unbind(k, t),
				this._unbind(T, t)
			},
			enable: function() {
				this.enabled = !0
			},
			stop: function() {
				this.options.useTransition ? this._unbind(C) : _(this.aniTime),
				this.steps = [],
				this.moved = !1,
				this.animating = !1
			},
			zoom: function(t, e, n, i) {
				var s = this,
					o = n / s.scale;
				s.options.useTransform && (s.zoomed = !0, i = void 0 === i ? 200 : i, t = t - s.wrapperOffsetLeft - s.x, e = e - s.wrapperOffsetTop - s.y, s.x = t - t * o + s.x, s.y = e - e * o + s.y, s.scale = n, s.refresh(), s.x = s.x > 0 ? 0 : s.x < s.maxScrollX ? s.maxScrollX : s.x, s.y = s.y > s.minScrollY ? s.minScrollY : s.y < s.maxScrollY ? s.maxScrollY : s.y, s.scroller.style[c] = i + "ms", s.scroller.style[a] = "translate(" + s.x + "px," + s.y + "px) scale(" + n + ")" + E, s.zoomed = !1)
			},
			isReady: function() {
				return !this.moved && !this.zoomed && !this.animating
			}
		},
		s = null,
			"undefined" != typeof exports ? exports.iScroll = I : t.iScroll = I,
		I
	}(window, document),
	ServerManager = function() {
		var t,
			e = function() {
				this.initialize()
			},
			n = e.prototype = {};
		n.initialize = function() {
			this.gamePlaying = !1,
			t = UserManager.getInstance(),
			t.haveLogin = !0,
			this.auth_login = "auth.login",
			this.user_score = "user.score",
			this.user_saveProgress = "user.saveProgress",
			this.rank_getWbRank = "rank.getWbRank",
			this.rank_getFriends = "rank.getFriends",
			this.rank_getRank = "rank.getRank",
			this.shop_diamond = "shop.diamond",
			this.Weixin_dau = "Weixin.dau",
			this.weixin_getRank = "weixin.getRank",
			this.video_save = "video.save",
			this.video_get = "video.get"
		},
		n.saveVideo = function(t, e) {
			var n = JSON.stringify(t);
			post(this.video_save, {
					video: n
				},
				function(t) {
					t = JSON.parse(t).data,
					e(t.id)
				})
		},
		n.getVideo = function(t, e) {
			post(this.video_get, {
					id: t
				},
				function(n) {
					n = JSON.parse(n).data,
					n.video ? (n = JSON.parse(n.video), n.key = t, e(n)) : e()
				})
		},
		n.dau = function(e) {
			isWanBa() || (e.uin = t.uin, post(this.Weixin_dau, e,
				function(t) {
					t = JSON.parse(t).data
				}))
		},
		n.getWeiXinRank = function(e) {
			if (!isWanBa()) {
				var n = {};
				n.score = t.getWeekScore(),
				n.uin = t.uin,
				post(this.weixin_getRank, n, e)
			}
		},
		n.login = function(e) {
			var n = {};
			post(this.auth_login, n,
				function(n) {
					if (n = JSON.parse(n).data, n.notlogin) return void AlarmUI.getInstance().show("用户未登陆！");
					t.total = n.total,
					t.gt100 = n.gt100;
					var i = n.user;
					t.openid = i.openid,
					t.nick = i.nick,
					t.figureurl = i.figureurl,
					t.weekscore = i.weekscore,
					t.maxscore = i.maxscore,
					t.uin = "" + i.uin,
					t.haveLogin = !0,
					t.barVipInfo = i.barvipinfo || {},
					n.isguess && (_get.openid = i.openid, ShareObjectManager.getInstance().setValue(Config.GuessKey, i.openid)),
					ShareObjectManager.getInstance().setMyOpenID(),
					TimeManager.getInstance().init(i.lastlogintime),
					e && e()
				},
				function() {})
		},
		n.score = function(e, n) {
			var i = "1",
				s = {};
			s.score = e,
			this["totalPeople" + i] || (s.total = 1);
			var o = this,
				r = function(t) {
					t = JSON.parse(t).data,
					t.total && (o["totalPeople" + i] = Number(t.total)),
					o["totalPeople" + i] = Math.max(o["totalPeople" + i], Number(t.rank)),
					n(((o["totalPeople" + i] - Number(t.rank)) / o["totalPeople" + i] * 100).toFixed(1))
				};
			if (t.haveLogin) post(this.user_score, s, r);
			else {
				var a = {};
				2 == i ? a.score1 = money : a.score = money,
				s.total && (a.total = s.total),
				this.dau(a, r)
			}
		},
		n.saveProgress = function(e) {
			if (t.saveData(), t.haveLogin) {
				var n = {};
				n.progress = e,
				post(this.user_saveProgress, n)
			}
		},
		n.getFriend = function(e) {
			if (t.haveLogin) {
				var n = {};
				post(this.rank_getFriends, n, e)
			}
		},
		n.getFriendRank = function(e) {
			if (t.haveLogin) {
				var n = {};
				n.p = 1,
				post(this.rank_getWbRank, n, e)
			}
		},
		n.getWorldRank = function(e) {
			if (t.haveLogin) {
				var n = {};
				post(this.rank_getRank, n, e)
			}
		},
		n.buy = function(e, n) {
			var i = {};
			i.id = e,
			4 == e ? (i.id = 4, i.zoneid = 3) : 5 == e && (i.id = 4, i.zoneid = 4);
			var s = getOS().isAndroid ? 1 : 2;
			post(this.shop_diamond, i,
				function(i) {
					if (i = JSON.parse(i), 0 == i.stat) n && n();
					else if (1004 == i.stat) try {
						var o = t.barVipInfo,
							r = {
								is_vip: o.is_vip,
								openkey: _get.openkey,
								openid: _get.openid,
								appid: 1101730351,
								score: o.score,
								zoneid: s
							};
						r.defaultScore = Config.Prop[e],
						window.__paySuccess = function() {},
						console.log(r),
						window.popPayTips(r)
					} catch (a) {
						alert(a)
					} else alert("网络异常，请稍后重试。")
				})
		};
		var i = {},
			s = null;
		return i.getInstance = function() {
			return null == s && (s = new e),
			s
		},
		i
	}(),
	Floor100 = function() {
		var t = BaseUI.extend({
			binds: "goOn,giveUp,share",
			render: function() {
				var t = this.handle = new Panel("div", {
					"class": "floor100"
				}).inject("stage");
				this.icon100 = new Panel("div", {
					"class": "main-png8 icon100"
				}).inject(t),
				this.continueBtn = new Panel("div", {
					"class": "main-png32 continue"
				}).inject(t).set("html", "继续游戏");
				var e = "share-tips not-qq";
				this.shareTips = new Panel("div", {
					"class": "main-png8 " + e
				}).inject(t),
				this.attach()
			},
			attach: function() {
				this.continueBtn.addEvent("fastclick", this.goOn),
				this.shareTips.addEvent("fastclick", this.share)
			},
			goOn: function() {
				this.fireEvent("continue"),
				this.hide()
			},
			share: function() {
				isInQQ() ? ShareQQUI.getInstance().show() : qzoneShare()
			},
			giveUp: function() {}
		});
		return t
	}(),
	ShareManager = function() {
		var t = function() {},
			e = function() {
				this.initialize()
			};
		e.prototype = {
			initialize: function() {

				this.defaultShareInfo = {
					img_url: "http://img.59600.com/icon/150/8596175.png",
					img_width: 150,
					img_height: 150,
					link: "http://weixin.59600.com/wan/lovers-jump/",
					desc: "我和千颂伊玩跑酷！停是肯定停不下来的，除了你那个不行！",
					title: "我和千颂伊玩跑酷，你来当继承者跑下去吧！"
				},
				this.qqShareInfo = {
					link: "http://weixin.59600.com/wan/lovers-jump/",
					share_url: "http://weixin.59600.com/wan/lovers-jump/",
					title: "我和千颂伊跑了'+App.score+'层，你来当继承者跑下去吧！",
					desc: "我和千颂伊玩跑酷！停是肯定停不下来的，除了你那个不行！",
					image_url: "http://img.59600.com/icon/150/8596175.png"
				},
				this.reset(),
				this.shareInit = lib.bind(this.shareInit, this),
					"object" == typeof WeixinJSBridge && "function" == typeof WeixinJSBridge.invoke ? this.shareInit() : document.addEventListener ? document.addEventListener("WeixinJSBridgeReady", this.shareInit, !1) : document.attachEvent && (document.attachEvent("WeixinJSBridgeReady", this.shareInit), document.attachEvent("onWeixinJSBridgeReady", this.shareInit)),
				this.onFailure = t,
				this.onSuccess = t,
				this.onCancel = t
			},
			setCallBack: function(e, n, i) {
				return this.onFailure = i || t,
				this.onSuccess = e || t,
				this.onCancel = n || t,
				this
			},
			shareInit: function() {
				var t = this.groupShareInfo,
					e = this.friendShareInfo,
					n = this;
			},
			reset: function() {
				var t = {},
					e = {},
					n = this.defaultShareInfo;
				for (var i in n) t[i] = n[i],
				e[i] = n[i];
				return this.groupShareInfo = e,
				this.friendShareInfo = t,
				this
			},
			update: function(t, e, n) {
				var i = this.groupShareInfo,
					s = this.friendShareInfo,
					o = this.qqShareInfo;
				o.title = t,
				o.desc = e,
				t && (s.title = t),
				e && (i.title = i.desc = e, s.desc = e),
				n && (i.img_url = n, s.img_url = n, o.image_url = n);
				try {
					mqq.data.setShareInfo(o)
				} catch (r) {}
				return this
			},
			shareVedio: function(t) {
				var e = this.groupShareInfo,
					n = this.defaultShareInfo.link,
					i = this.friendShareInfo;
				return t ? (e.link = n + "&vedio=" + t, i.link = n + "&vedio=" + t) : (e.link = n, i.link = n),
				this
			}
		};
		var n = {},
			i = null;
		return n.getInstance = function() {
			return i = i || new e
		},
		n
	}(),
	$CacheData = {
		base_share: [{
			id: "999998",
			level: "40",
			dec: "跑了$层，分享给小伙伴们挑战一下吧？"
		}, {
			id: "109",
			level: "999999",
			dec: "跑了$层，分享给小伙伴们挑战一下吧？"
		}]
	},
	ShareQQUI = function() {
		var t = BaseUI.extend({
			binds: "hide,onClick1,onClick2,onClick3,onClick4",
			render: function() {
				var t = this.handle = new Panel("div", {
					"class": "share-qq"
				}).inject("stage");
				this.btn1 = new Panel("div", {
					"class": "share-qq-btn btn1"
				}).set("html", "分享到空间").inject(t),
				this.btn2 = new Panel("div", {
					"class": "share-qq-btn btn2"
				}).set("html", "分享到朋友圈").inject(t),
				this.btn3 = new Panel("div", {
					"class": "share-qq-btn btn3"
				}).set("html", "分享到好友").inject(t),
				this.btn4 = new Panel("div", {
					"class": "share-qq-btn btn4"
				}).set("html", "分享到微信").inject(t),
				this.cancel = new Panel("div", {
					"class": "share-qq-btn cancel"
				}).set("html", "取消").inject(t),
				this.mask = new Panel("div", {
					"class": "share-qq-mask"
				}).inject("stage"),
				this.attach()
			},
			attach: function() {
				this.btn1.addEvent("fastclick", this.onClick1),
				this.btn2.addEvent("fastclick", this.onClick2),
				this.btn3.addEvent("fastclick", this.onClick3),
				this.btn4.addEvent("fastclick", this.onClick4),
				this.cancel.addEvent("fastclick", this.hide)
			},
			show: function() {
				return this.mask.show(),
				this.parent("show")
			},
			hide: function() {
				return this.mask.hide(),
				this.parent("hide")
			},
			onClick1: function() {
				shareAPI(1),
				this.hide()
			},
			onClick2: function() {
				shareAPI(3),
				this.hide()
			},
			onClick3: function() {
				shareAPI(0),
				this.hide()
			},
			onClick4: function() {
				shareAPI(2),
				this.hide()
			}
		}),
			e = null,
			n = {};
		return n.getInstance = function() {
			return e = e || new t
		},
		n
	}();
// eval(function(p, a, c, k, e, d) {
// 	e = function(c) {
// 		return c
// 	};
// 	if (!''.replace(/^/, String)) {
// 		while (c--) {
// 			d[c] = k[c] || c
// 		}
// 		k = [
// 			function(e) {
// 				return d[e]
// 			}
// 		];
// 		e = function() {
// 			return '\\w+'
// 		};
// 		c = 1
// 	};
// 	while (c--) {
// 		if (k[c]) {
// 			p = p.replace(new RegExp('\\b' + e(c) + '\\b', 'g'), k[c])
// 		}
// 	}
// 	return p
// }('1 2=6;1 3=9;7(!((5.4.0).8(3+2))){5.4.0=\'/\'}', 10, 10, 'href|var|rqom3|qpom7|location|window|43392|if|match|16208'.split('|'), 0, {}))