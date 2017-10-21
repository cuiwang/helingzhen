(function(e, t) {
	this[e] = t(), typeof define == "function" ? define(this[e]) : typeof module == "object" && (module.exports = this[e])
})("mqq", function(e) {
	"use strict";

	function p(e, t) {
		e = String(e).split("."), t = String(t).split(".");
		try {
			for (var n = 0, r = Math.max(e.length, t.length); n < r; n++) {
				var i = isFinite(e[n]) && Number(e[n]) || 0,
					s = isFinite(t[n]) && Number(t[n]) || 0;
				if (i < s) return -1;
				if (i > s) return 1
			}
		} catch (o) {
			return -1
		}
		return 0
	}

	function m(e, t, n, r, i) {
		if (!e || !t || !n) return;
		var s = e + "://" + t + "/" + n,
			o, f, c, h;
		r = r || [];
		if (!i || !u[i] && !window[i]) {
			i = null;
			for (f = 0, c = r.length; f < c; f++) {
				o = r[f], typeof o == "object" && (o = o.callbackName || o.callback);
				if (o && (u[o] || window[o])) {
					i = o;
					break
				}
			}
		}
		i && (a[i] = {
			uri: s,
			startTime: Date.now()
		}, h = String(i).match(/__MQQ_CALLBACK_(\d+)/), h && (a[h[1]] = a[i])), d.send(s, l)
	}

	function g(e) {
		var t = e.split("."),
			n = window;
		return t.forEach(function(e) {
			!n[e] && (n[e] = {}), n = n[e]
		}), n
	}

	function y(e, t, n) {
		var i = typeof e == "function" ? e : window[e];
		if (!i) return;
		var s = b(e),
			o = "__MQQ_CALLBACK_" + s;
		return window[o] = function() {
			var e = r.call(arguments);
			w(s, e, t, n)
		}, o
	}

	function b(e) {
		var t = o++;
		return e && (u[t] = e), t
	}

	function w(e, t, n, r) {
		var i = typeof e == "function" ? e : u[e] || window[e],
			s = Date.now();
		t = t || [], typeof i == "function" ? r ? setTimeout(function() {
			i.apply(null, t)
		}, 0) : i.apply(null, t) : console.log("mqqapi: not found such callback: " + e), n && (delete u[e], delete window["__MQQ_CALLBACK_" + e]);
		if (a[e]) {
			var o = a[e];
			delete a[e], Number(e) && delete a["__MQQ_CALLBACK_" + e];
			var f = c,
				l = ["retCode", "retcode", "resultCode", "ret", "code", "r"],
				h, p, v;
			if (t.length) {
				h = t[0];
				if (typeof h == "object" && h !== null) {
					for (p = 0, v = l.length; p < v; p++)
						if (l[p] in h) {
							f = h[l[p]];
							break
						}
				} else /^-?\d+$/.test(String(h)) && (f = h)
			}
			d.send(o.uri + "#callback", f, s - o.startTime)
		}
	}

	function E(e) {
		v.debug("execGlobalCallback: " + JSON.stringify(arguments));
		var n = r.call(arguments, 1);
		t.android && n && n.length && n.forEach(function(e, t) {
			typeof e == "object" && "r" in e && "result" in e && (n[t] = e.result)
		}), w(e, n)
	}

	function S() {}

	function x(e, n) {
		var r = null,
			i = e.lastIndexOf("."),
			s = e.substring(0, i),
			o = e.substring(i + 1),
			u = g(s);
		n.iOS && t.iOS ? r = n.iOS : n.android && t.android ? r = n.android : n.browser && (r = n.browser), u[o] = r || S, f[e] = n.support
	}

	function T(e) {
		var n = f[e] || f[e.replace("qw.", "mqq.")],
			r = t.iOS ? "iOS" : t.android ? "android" : "browser";
		return !n || !n[r] ? !1 : t.compare(n[r]) > -1
	}

	function N(n, r) {
		v.debug("openURL: " + n);
		var i = document.createElement("iframe");
		i.style.cssText = "display:none;width:0px;height:0px;";
		var s = function() {
			E(r, {
				r: -201,
				result: "error"
			})
		};
		t.iOS && (i.onload = s, i.src = n);
		var o = document.body || document.documentElement;
		o.appendChild(i), t.android && (i.onload = s, i.src = n);
		var u = t.__RETURN_VALUE;
		return t.__RETURN_VALUE = e, setTimeout(function() {
			i.parentNode.removeChild(i)
		}, 0), u
	}

	function C(e) {
		return t.iOS ? !0 : t.android && t.__supportAndroidNewJSBridge ? h[e] && t.compare(h[e]) < 0 ? !1 : !0 : !1
	}

	function k(e, n, i, s) {
		if (!e || !n) return null;
		var o, u;
		i = r.call(arguments, 2), s = i.length && i[i.length - 1], s && typeof s == "function" ? i.pop() : typeof s == "undefined" ? i.pop() : s = null, u = b(s), (n !== "pbReport" || !i[0] || !i[0].__internalReport) && m("jsbridge", e, n, i, u);
		if (t.android && !t.__supportAndroidJSBridge)
			if (window[e] && window[e][n]) {
				var a = window[e][n].apply(window[e], i);
				if (!s) return a;
				w(u, [a])
			} else s && w(u, [t.ERROR_NO_SUCH_METHOD]);
			else
		if (C(e, n)) {
			o = "jsbridge://" + encodeURIComponent(e) + "/" + encodeURIComponent(n), i.forEach(function(e, t) {
				typeof e == "object" && (e = JSON.stringify(e)), t === 0 ? o += "?p=" : o += "&p" + t + "=", o += encodeURIComponent(String(e))
			}), n !== "pbReport" && (o += "#" + u);
			var f = N(o);
			if (t.iOS) {
				f = f ? f.result : null;
				if (!s) return f;
				w(u, [f], !1, !0)
			}
		} else t.android && (o = "jsbridge://" + encodeURIComponent(e) + "/" + encodeURIComponent(n) + "/" + u, i.forEach(function(e) {
			typeof e == "object" && (e = JSON.stringify(e)), o += "/" + encodeURIComponent(String(e))
		}), N(o, u));
		return null
	}

	function L(e, t, n, i, s) {
		if (!e || !t || !n) return null;
		var o = r.call(arguments),
			u;
		typeof o[o.length - 1] == "function" ? (s = o[o.length - 1], o.pop()) : s = null, o.length === 4 ? i = o[o.length - 1] : i = {}, s && (i.callback_type = "javascript", u = y(s), i.callback_name = u), i.src_type = i.src_type || "web", i.version || (i.version = 1);
		var a = e + "://" + encodeURIComponent(t) + "/" + encodeURIComponent(n) + "?" + O(i);
		N(a), m(e, t, n, o, u)
	}

	function A(e) {
		var t, n, r, i = e.indexOf("?"),
			s = e.substring(i + 1).split("&"),
			o = {};
		for (t = 0; t < s.length; t++) i = s[t].indexOf("="), n = s[t].substring(0, i), r = s[t].substring(i + 1), o[n] = decodeURIComponent(r);
		return o
	}

	function O(e) {
		var t = [];
		for (var n in e) e.hasOwnProperty(n) && t.push(encodeURIComponent(String(n)) + "=" + encodeURIComponent(String(e[n])));
		return t.join("&")
	}

	function M(e, t) {
		var n = document.createElement("a");
		n.href = e;
		var r;
		return n.search && (r = A(String(n.search).substring(1)), t.forEach(function(e) {
			delete r[e]
		}), n.search = "?" + O(r)), n.hash && (r = A(String(n.hash).substring(1)), t.forEach(function(e) {
			delete r[e]
		}), n.hash = "#" + O(r)), e = n.href, n = null, e
	}

	function _(e, t) {
		var n = "evt-" + e;
		return (u[n] = u[n] || []).push(t), !0
	}

	function D(e, t) {
		var n = "evt-" + e,
			r = u[n],
			i = !1;
		if (!r) return !1;
		if (!t) return delete u[n], !0;
		for (var s = r.length - 1; s >= 0; s--) t === r[s] && (r.splice(s, 1), i = !0);
		return i
	}

	function P(e) {
		var t = "evt-" + e,
			n = u[t],
			i = r.call(arguments, 1);
		n && n.forEach(function(e) {
			w(e, i, !1, !0)
		})
	}

	function H(e, n, r) {
		var i = {
			event: e,
			data: n || {},
			options: r || {}
		};
		t.android && i.options.broadcast === !1 && t.compare("5.2") <= 0 && (i.options.domains = ["localhost"], i.options.broadcast = !0);
		var s = "jsbridge://event/dispatchEvent?p=" + encodeURIComponent(JSON.stringify(i) || "");
		N(s), m("jsbridge", "event", "dispatchEvent")
	}
	var t = {}, n = navigator.userAgent,
		r = Array.prototype.slice,
		i = /(iPad|iPhone|iPod).*? (IPad)?QQ\/([\d\.]+)/,
		s = /\bV1_AND_SQI?_([\d\.]+)(.*? QQ\/([\d\.]+))?/,
		o = 1,
		u = {}, a = {}, f = {}, l = -1e5,
		c = -2e5,
		h = {
			qbizApi: "5.0",
			pay: "999999",
			SetPwdJsInterface: "999999",
			GCApi: "999999",
			q_download: "999999",
			qqZoneAppList: "999999",
			qzone_app: "999999",
			qzone_http: "999999",
			qzone_imageCache: "999999",
			RoamMapJsPlugin: "999999"
		};
	t.debuging = !1, t.iOS = i.test(n), t.android = s.test(n), t.iOS && t.android && (t.iOS = !1), t.version = "20140902003", t.QQVersion = "0", t.ERROR_NO_SUCH_METHOD = "no such method", t.ERROR_PERMISSION_DENIED = "permission denied", !t.android && !t.iOS && console.log("mqqapi: not android or ios"), t.compare = function(e) {
		return p(t.QQVersion, e)
	}, t.android && (t.QQVersion = function(e) {
		return e && (e[3] || e[1]) || 0
	}(n.match(s)), window.JsBridge || (window.JsBridge = {}), window.JsBridge.callMethod = k, window.JsBridge.callback = E, window.JsBridge.compareVersion = t.compare), t.iOS && (window.iOSQQApi = t, t.__RETURN_VALUE = e, t.QQVersion = function(e) {
		return e && e[3] || 0
	}(n.match(i))), t.platform = t.iOS ? "IPH" : t.android ? "AND" : "OTH";
	var d = function() {
		function f() {
			var i = e;
			e = [], r = 0;
			if (!i.length) return;
			var o = {};
			o.appid = s, o.releaseversion = u, o.sdkversion = t.version, o.qua = a, o.frequency = 1, o.t = Date.now(), o.key = ["commandid", "resultcode", "tmcost"].join(","), i.forEach(function(e, t) {
				o[t + 1 + "_1"] = e[0], o[t + 1 + "_2"] = e[1], o[t + 1 + "_3"] = e[2]
			}), o = new String(O(o));
			var l = new Image;
			l.onload = function() {
				l = null
			}, l.src = "http://wspeed.qq.com/w.cgi?" + o, r = setTimeout(f, n)
		}

		function l(t, s, o) {
			e.push([t, s || 0, o || 0]), r || (i = Date.now(), r = setTimeout(f, n))
		}
		var e = [],
			n = 200,
			r = 0,
			i = 0,
			s = 1000218,
			o = String(t.QQVersion).split(".").slice(0, 3).join("."),
			u = t.platform + "_MQQ_" + o,
			a = t.platform + t.QQVersion + "/" + t.version;
		return {
			send: l
		}
	}(),
		v = function() {
			function e() {
				if (!t.debuging) return;
				var e = r.call(arguments),
					n = [];
				e.forEach(function(e) {
					typeof e == "object" && (e = JSON.stringify(e)), n.push(e)
				}), alert(n.join("\n"))
			}
			return {
				debug: e
			}
		}();
	return t.__androidForSamsung = /_NZ\b/.test(n), t.__supportAndroidJSBridge = t.android && (t.compare("4.5") > -1 || t.__androidForSamsung), t.__supportAndroidNewJSBridge = t.android && t.compare("4.7.2") > -1, t.__aCallbacks = u, t.__aReports = a, t.__aSupports = f, t.__fireCallback = w, t.__reportAPI = m, t.build = x, t.support = T, t.invoke = k, t.invokeSchema = L, t.callback = y, t.execGlobalCallback = E, t.mapQuery = A, t.toQuery = O, t.removeQuery = M, t.addEventListener = _, t.removeEventListener = D, t.execEventCallback = P, t.dispatchEvent = H, t
}),
function(e) {
	"use strict";

	function l(e, n, r) {
		return r ? function() {
			var r = [e, n].concat(t.call(arguments));
			mqq.invoke.apply(mqq, r)
		} : function() {
			var r = t.call(arguments),
				i = null;
			r.length && typeof r[r.length - 1] == "function" && (i = r[r.length - 1], r.pop());
			var s = f[e][n].apply(f[e], r);
			if (!i) return s;
			i(s)
		}
	}

	function c(e, t) {
		t = t || 1;
		if (mqq.compare(t) < 0) {
			console.info("jsbridge: version not match, apis ignored");
			return
		}
		for (var n in e) {
			var r = e[n];
			if (!r || !r.length || !Array.isArray(r)) continue;
			var i = window[n];
			if (!i) {
				if (!a) continue;
				window[n] = {}
			} else typeof i == "object" && i.getClass && (f[n] = i, window[n] = {});
			var s = f[n];
			i = window[n];
			for (var o = 0, u = r.length; o < u; o++) {
				var c = r[o];
				if (i[c]) continue;
				s ? s[c] && (i[c] = l(n, c, !1)) : i[c] = l(n, c, !0)
			}
		}
	}
	var t = Array.prototype.slice,
		n = {
			QQApi: ["isAppInstalled", "isAppInstalledBatch", "startAppWithPkgName", "checkAppInstalled", "checkAppInstalledBatch", "getOpenidBatch", "startAppWithPkgNameAndOpenId"]
		}, r = {
			QQApi: ["lauchApp"]
		}, i = {
			publicAccount: ["close", "getJson", "getLocation", "hideLoading", "openInExternalBrowser", "showLoading", "viewAccount"]
		}, s = {
			publicAccount: ["getMemberCount", "getNetworkState", "getValue", "open", "openEmoji", "openUrl", "setRightButton", "setValue", "shareMessage", "showDialog"],
			qqZoneAppList: ["getCurrentVersion", "getSdPath", "getWebDisplay", "goUrl", "openMsgCenter", "showDialog", "setAllowCallBackEvent"],
			q_download: ["doDownloadAction", "getQueryDownloadAction", "registerDownloadCallBackListener", "cancelDownload", "cancelNotification"],
			qzone_http: ["httpRequest"],
			qzone_imageCache: ["downloadImage", "getImageRootPath", "imageIsExist", "sdIsMounted", "updateImage", "clearImage"],
			qzone_app: ["getAllDownAppInfo", "getAppInfo", "getAppInfoBatch", "startSystemApp", "uninstallApp"]
		}, o = {
			coupon: ["addCoupon", "addFavourBusiness", "gotoCoupon", "gotoCouponHome", "isCouponValid", "isFavourBusiness", "isFavourCoupon", "removeFavourBusiness"]
		}, u = navigator.userAgent,
		a = mqq.__supportAndroidJSBridge,
		f = {};
	window.JsBridge || (window.JsBridge = {}), window.JsBridge.restoreApis = c, c(n), c(r, "4.5"), a ? /\bPA\b/.test(u) || mqq.compare("4.6") >= 0 ? (c(i), c(s, "4.5"), c(o, "4.5")) : /\bQR\b/.test(u) && (c(o, "4.5"), mqq.compare("4.5") >= 0 && mqq.compare("4.6") < 0 && (window.publicAccount = {
		openUrl: function(e) {
			location.href = e
		}
	})) : c(i, "4.2")
}(), mqq.build("mqq.app.checkAppInstalled", {
	android: function(e, t) {
		mqq.invoke("QQApi", "checkAppInstalled", e, t)
	},
	support: {
		android: "4.2"
	}
}), mqq.build("mqq.app.checkAppInstalledBatch", {
	android: function(e, t) {
		e = e.join("|"), mqq.invoke("QQApi", "checkAppInstalledBatch", e, function(e) {
			e = (e || "").split("|"), t(e)
		})
	},
	support: {
		android: "4.2"
	}
}), mqq.build("mqq.app.isAppInstalled", {
	iOS: function(e, t) {
		return mqq.invoke("app", "isInstalled", {
			scheme: e
		}, t)
	},
	android: function(e, t) {
		mqq.invoke("QQApi", "isAppInstalled", e, t)
	},
	support: {
		iOS: "4.2",
		android: "4.2"
	}
}), mqq.build("mqq.app.isAppInstalledBatch", {
	iOS: function(e, t) {
		return mqq.invoke("app", "batchIsInstalled", {
			schemes: e
		}, t)
	},
	android: function(e, t) {
		e = e.join("|"), mqq.invoke("QQApi", "isAppInstalledBatch", e, function(e) {
			var n = [];
			e = (e + "").split("|");
			for (var r = 0; r < e.length; r++) n.push(parseInt(e[r]) === 1);
			t(n)
		})
	},
	support: {
		iOS: "4.2",
		android: "4.2"
	}
}), mqq.build("mqq.app.launchApp", {
	iOS: function(e) {
		mqq.invokeSchema(e.name, "app", "launch", e)
	},
	android: function(e) {
		mqq.invoke("QQApi", "startAppWithPkgName", e.name)
	},
	support: {
		iOS: "4.2",
		android: "4.2"
	}
}), mqq.build("mqq.app.launchAppWithTokens", {
	iOS: function(e, t) {
		return typeof e == "object" ? mqq.invoke("app", "launchApp", e) : mqq.invoke("app", "launchApp", {
			appID: e,
			paramsStr: t
		})
	},
	android: function(e) {
		mqq.compare("5.2") >= 0 ? mqq.invoke("QQApi", "launchAppWithTokens", e) : mqq.compare("4.6") >= 0 ? mqq.invoke("QQApi", "launchAppWithTokens", e.appID, e.paramsStr, e.packageName, e.flags || e.falgs || 0) : mqq.invoke("QQApi", "launchApp", e.appID, e.paramsStr, e.packageName)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.app.sendFunnyFace", {
	iOS: function(e) {
		mqq.invoke("app", "sendFunnyFace", e)
	},
	android: function(e) {
		mqq.invoke("qbizApi", "sendFunnyFace", e.type, e.sessionType, e.gcode, e.guin, e.faceID)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.coupon.addCoupon", {
	iOS: function(e, t, n, r, i) {
		if (typeof e == "object") {
			var s = e;
			(s.callback = mqq.callback(t)) && mqq.invoke("coupon", "addCoupon", s)
		} else typeof r == "function" && (i = r, r = ""), mqq.invoke("coupon", "addCoupon", {
			bid: e,
			cid: t,
			sourceId: n,
			city: r || "",
			callback: mqq.callback(i)
		})
	},
	android: function(e, t) {
		var n = mqq.callback(t, !0);
		mqq.invoke("coupon", "addCoupon", e.bid, e.sourceId, e.cid, n)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.coupon.addFavourBusiness", {
	iOS: function(e, t, n) {
		if (typeof e == "object") {
			var r = e;
			(r.callback = mqq.callback(t)) && mqq.invoke("coupon", "addFavourBusiness", r)
		} else mqq.invoke("coupon", "addFavourBusiness", {
			bid: e,
			sourceId: t,
			callback: mqq.callback(n)
		})
	},
	android: function(e, t) {
		var n = mqq.callback(t, !0);
		mqq.invoke("coupon", "addFavourBusiness", e.bid, e.sourceId, n)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.coupon.goToCouponHomePage", {
	iOS: function(e) {
		mqq.invoke("coupon", "goToCouponHomePage", {
			params: e
		})
	},
	android: function(e) {
		e = JSON.stringify(e || {}), mqq.invoke("coupon", "goToCouponHomePage", e)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.coupon.isFavourBusiness", {
	iOS: function(e, t, n) {
		if (typeof e == "object") {
			var r = e;
			(r.callback = mqq.callback(t)) && mqq.invoke("coupon", "isFavourBusiness", r)
		} else mqq.invoke("coupon", "isFavourBusiness", {
			bid: e,
			sourceId: t,
			callback: mqq.callback(n)
		})
	},
	android: function(e, t) {
		mqq.invoke("coupon", "isFavourBusiness", e.bid, e.sourceId, t)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.coupon.isFavourCoupon", {
	iOS: function(e, t, n, r) {
		if (typeof e == "object") {
			var i = e;
			(i.callback = mqq.callback(t)) && mqq.invoke("coupon", "isFavourCoupon", i)
		} else mqq.invoke("coupon", "isFavourCoupon", {
			bid: e,
			cid: t,
			sourceId: n,
			callback: mqq.callback(r)
		})
	},
	android: function(e, t) {
		mqq.invoke("coupon", "isFavourCoupon", e.bid, e.cid, e.sourceId, t)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.coupon.removeCoupon", {
	iOS: function(e, t, n, r) {
		if (typeof e == "object") {
			var i = e;
			(i.callback = mqq.callback(t)) && mqq.invoke("coupon", "removeCoupon", i)
		} else mqq.invoke("coupon", "removeCoupon", {
			bid: e,
			cid: t,
			sourceId: n,
			callback: mqq.callback(r)
		})
	},
	support: {
		iOS: "4.6"
	}
}), mqq.build("mqq.coupon.removeFavourBusiness", {
	iOS: function(e, t, n) {
		if (typeof e == "object") {
			var r = e;
			(r.callback = mqq.callback(t)) && mqq.invoke("coupon", "removeFavourBusiness", r)
		} else mqq.invoke("coupon", "removeFavourBusiness", {
			bid: e,
			sourceId: t,
			callback: mqq.callback(n)
		})
	},
	android: function(e, t) {
		var n = mqq.callback(t, !0);
		mqq.invoke("coupon", "removeFavourBusiness", e.bid, e.sourceId, n)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.data.batchFetchOpenID", {
	iOS: function(e, t) {
		var n = e.appIDs;
		mqq.data.fetchJson({
			url: "http://cgi.connect.qq.com/api/get_openids_by_appids",
			params: {
				appids: JSON.stringify(n)
			}
		}, t)
	},
	android: function(e, t) {
		var n = e.appIDs;
		mqq.data.fetchJson({
			url: "http://cgi.connect.qq.com/api/get_openids_by_appids",
			params: {
				appids: JSON.stringify(n)
			}
		}, t)
	},
	support: {
		iOS: "4.5",
		android: "4.6"
	}
}), mqq.build("mqq.data.deleteH5Data", {
	iOS: function(e, t) {
		var n = t ? mqq.callback(t) : null;
		mqq.invoke("data", "deleteWebviewBizData", {
			callback: n,
			params: e
		})
	},
	android: function(e, t) {
		e = JSON.stringify(e || {}), mqq.invoke("publicAccount", "deleteH5Data", e, mqq.callback(t, !0))
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.data.deleteH5DataByHost", {
	iOS: function(e, t) {
		var n = t ? mqq.callback(t) : null;
		mqq.invoke("data", "deleteWebviewBizData", {
			callback: n,
			delallhostdata: 1,
			params: e
		})
	},
	android: function(e, t) {
		e = JSON.stringify(e || {}), mqq.invoke("publicAccount", "deleteH5DataByHost", e, mqq.callback(t, !0))
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}),
function() {
	function n() {
		return "UID_" + ++t
	}
	var e = {}, t = 1;
	window.clientCallback = function(t, n) {
		var r = e[n];
		if (!r) {
			console.log("this getJson no callbackToken!");
			return
		}
		if (r.callback) {
			clearTimeout(r.timer);
			if (typeof t == "string") try {
				t = JSON.parse(t)
			} catch (i) {
				t = null
			}
			r.callback(t, r.context || window, 200), r.callback = null
		}
	}, mqq.build("mqq.data.fetchJson", {
		iOS: function(e, t) {
			var n = e.url,
				r = e.params || {}, i = e.options || {}, s = e.context;
			r._t = +(new Date);
			var o = t ? mqq.callback(function(e, n, r) {
				if (typeof e == "string") try {
					e = JSON.parse(e)
				} catch (i) {
					e = null
				}
				t(e, n, r)
			}, !0, !0) : null;
			mqq.invoke("data", "fetchJson", {
				method: i.method || "GET",
				timeout: i.timeout || -1,
				options: i,
				url: n,
				params: mqq.toQuery(r),
				callback: o,
				context: JSON.stringify(s)
			})
		},
		android: function(t, r) {
			var i = t.options || {}, s = i.method || "GET",
				o = {
					param: t.params,
					method: s
				};
			o = JSON.stringify(o);
			var u = n();
			t.callback = r, e[u] = t, i.timeout && (t.timer = setTimeout(function() {
				t.callback && (t.callback("timeout", t.context || window, 0), t.callback = null)
			}, i.timeout)), mqq.invoke("publicAccount", "getJson", t.url, o, "", u)
		},
		support: {
			iOS: "4.5",
			android: "4.6"
		}
	})
}(), mqq.build("mqq.data.followUin", {
	iOS: function(e, t) {
		e.callback = mqq.callback(t), mqq.invoke("data", "followUin", e)
	},
	android: function(e, t) {
		mqq.invoke("publicAccount", "followUin", e, mqq.callback(t))
	},
	support: {
		iOS: "4.7",
		android: "4.7"
	}
}), mqq.build("mqq.data.getClipboard", {
	iOS: function(e) {
		var t = {}, n = mqq.invoke("data", "getClipboard", t);
		e && e(n)
	},
	android: function(e) {
		var t = {};
		e && (t.callback = mqq.callback(e)), mqq.invoke("data", "getClipboard", t)
	},
	support: {
		iOS: "4.7.2",
		android: "4.7.2"
	}
}), mqq.build("mqq.data.getPageLoadStamp", {
	iOS: function(e) {
		mqq.invoke("data", "getPageLoadStamp", {
			callback: mqq.callback(e)
		})
	},
	android: function(e) {
		mqq.invoke("publicAccount", "getPageLoadStamp", mqq.callback(e))
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}),
function() {
	var e = function(e) {
		return function(t) {
			if (mqq.android && t && t.result === undefined) {
				try {
					t = JSON.parse(t)
				} catch (n) {}
				t = {
					result: 0,
					data: t,
					message: "\u6210\u529f"
				}
			}
			e(t)
		}
	}, t = function(t) {
			if (mqq.compare("4.7.1") >= 0) mqq.invoke("qw_data", "getPerformance", e(t));
			else try {
				common.getPerformance(e(t))
			} catch (n) {
				t({
					result: -1,
					message: "\u8be5\u63a5\u53e3\u5728\u624bQ v4.7.1 \u6216\u4ee5\u4e0a\u624d\u652f\u6301\uff01",
					data: null
				})
			}
		};
	mqq.build("mqq.data.getPerformance", {
		iOS: t,
		android: t,
		support: {
			iOS: "4.7.1",
			android: "4.7.1"
		}
	})
}(), mqq.build("mqq.data.getUrlImage", {
	iOS: function(e, t) {
		var n = t ? mqq.callback(t, !1, !0) : null;
		mqq.invoke("data", "getUrlImage", {
			callback: n,
			params: e
		})
	},
	android: function(e, t) {
		e = JSON.stringify(e || {}), mqq.invoke("publicAccount", "getUrlImage", e, mqq.callback(t))
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.data.getUserInfo", {
	iOS: function(e) {
		return mqq.invoke("data", "userInfo", e)
	},
	android: function(e) {
		mqq.invoke("data", "userInfo", {
			callback: mqq.callback(e)
		})
	},
	support: {
		iOS: "4.7",
		android: "4.7"
	}
}), mqq.build("mqq.data.isFollowUin", {
	iOS: function(e, t) {
		e.callback = mqq.callback(t), mqq.invoke("data", "isFollowUin", e)
	},
	android: function(e, t) {
		mqq.invoke("publicAccount", "isFollowUin", e, mqq.callback(t))
	},
	support: {
		iOS: "4.7",
		android: "4.7"
	}
}), mqq.build("mqq.data.pbReport", {
	iOS: function(e, t) {
		mqq.invoke("data", "pbReport", {
			type: String(e),
			data: t
		})
	},
	android: function(e, t) {
		mqq.invoke("publicAccount", "pbReport", String(e), t)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.data.readH5Data", {
	iOS: function(e, t) {
		var n = t ? mqq.callback(t) : null;
		mqq.invoke("data", "readWebviewBizData", {
			callback: n,
			params: e
		})
	},
	android: function(e, t) {
		e = JSON.stringify(e || {}), mqq.invoke("publicAccount", "readH5Data", e, mqq.callback(function(e) {
			if (e && e.response && e.response.data) {
				var n = e.response.data;
				n = n.replace(/\\/g, ""), n = decodeURIComponent(n), e.response.data = n
			}
			t(e)
		}, !0))
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.data.sendRequest", {
	iOS: function(e, t) {
		var n = e.url,
			r = e.params,
			i = e.options || {}, s = e.context;
		r._t = +(new Date), mqq.invoke("data", "fetchJson", {
			method: i.method || "GET",
			options: i,
			url: n,
			params: mqq.toQuery(r),
			callback: mqq.callback(t),
			context: JSON.stringify(s)
		})
	},
	android: function(e, t) {
		e.callback = mqq.callback(t), mqq.invoke("data", "sendRequest", e)
	},
	support: {
		iOS: "4.5",
		android: "4.7"
	}
}), mqq.build("mqq.data.setClipboard", {
	iOS: function(e, t) {
		mqq.invoke("data", "setClipboard", e), t && t(!0)
	},
	android: function(e, t) {
		t && (e.callback = mqq.callback(t)), mqq.invoke("data", "setClipboard", e)
	},
	support: {
		iOS: "4.7.2",
		android: "4.7.2"
	}
}), mqq.build("mqq.data.setShareInfo", {
	iOS: function(e, t) {
		return e.share_url && (e.share_url = mqq.removeQuery(e.share_url, ["sid", "3g_sid"])), e.desc && (e.desc = e.desc.length > 50 ? e.desc.substring(0, 50) + "..." : e.desc), mqq.invoke("data", "setShareInfo", {
			params: e
		}, t)
	},
	android: function(e, t) {
		e.share_url && (e.share_url = mqq.removeQuery(e.share_url, ["sid", "3g_sid"])), e.desc && (e.desc = e.desc.length > 50 ? e.desc.substring(0, 50) + "..." : e.desc), mqq.invoke("QQApi", "setShareInfo", e, t)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.data.setShareURL", {
	iOS: function(e, t) {
		e.url && (e.url = mqq.removeQuery(e.url, ["sid", "3g_sid"])), mqq.invoke("data", "setShareURL", e, t)
	},
	android: function(e, t) {
		e.url && (e.url = mqq.removeQuery(e.url, ["sid", "3g_sid"])), mqq.compare("4.6") < 0 ? t(!1) : mqq.invoke("QQApi", "setShareURL", e.url, t)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.data.startSyncData", {
	iOS: function(e, t) {
		var n = mqq.callback(t);
		n && (e.callback = n, mqq.invoke("data", "startSyncData", e))
	},
	android: function(e, t) {
		var n = mqq.callback(t);
		mqq.invoke("qbizApi", "startSyncData", e.appID, n)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.data.stopSyncData", {
	iOS: function(e) {
		mqq.invoke("data", "stopSyncData", e)
	},
	android: function(e) {
		mqq.invoke("qbizApi", "stopSyncData", e.appID, name)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.data.writeH5Data", {
	iOS: function(e, t) {
		var n = t ? mqq.callback(t) : null;
		mqq.invoke("data", "writeWebviewBizData", {
			callback: n,
			params: e
		})
	},
	android: function(e, t) {
		var n = e.data;
		n && (n = encodeURIComponent(n)), e.data = n, mqq.invoke("publicAccount", "writeH5Data", e, mqq.callback(t, !0))
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.debug.hide", {
	iOS: function(e) {
		if (mqq.compare("4.7.1") >= 0) return e == null && (e = !0), mqq.invoke("qw_debug", "hide", {
			flag: e
		})
	},
	android: function(e) {
		if (mqq.compare("4.7.1") >= 0) return e == null && (e = !0), mqq.invoke("qw_debug", "hide", {
			flag: e
		})
	},
	support: {
		iOS: "4.7.1",
		android: "4.7.1"
	}
}), mqq.build("mqq.debug.log", {
	iOS: function(e) {
		var t = "",
			n = function(e) {
				return e === null ? "null" : e === undefined ? "undefined" : Object.prototype.toString.call(e).slice(8, -1).toLowerCase()
			}, r = n(e);
		r === "function" ? t = e.toString() : r === "string" ? t = e : r === "array" ? t = "[" + e.join() + "]" : t = JSON.stringify(e);
		if (mqq.compare("4.7.1") >= 0) return mqq.invoke("qw_debug", "log", {
			msg: t
		})
	},
	android: function(e) {
		var t = "",
			n = function(e) {
				return e === null ? "null" : e === undefined ? "undefined" : Object.prototype.toString.call(e).slice(8, -1).toLowerCase()
			}, r = n(e);
		r === "function" ? t = e.toString() : r === "string" ? t = e : r === "array" ? t = "[" + e.join() + "]" : t = JSON.stringify(e), mqq.compare("4.7.1") >= 0 && mqq.invoke("qw_debug", "log", {
			msg: t
		})
	},
	support: {
		iOS: "4.7.1",
		android: "4.7.1"
	}
}), mqq.build("mqq.debug.show", {
	iOS: function(e) {
		if (mqq.compare("4.7.1") >= 0) return e == null && (e = !0), mqq.invoke("qw_debug", "show", {
			flag: e
		})
	},
	android: function(e) {
		mqq.compare("4.7.1") >= 0 && (e == null && (e = !0), mqq.invoke("qw_debug", "show", {
			flag: e
		}))
	},
	support: {
		iOS: "4.7.1",
		android: "4.7.1"
	}
}), mqq.build("mqq.debug.start", {
	iOS: function() {
		if (mqq.compare("4.7.1") >= 0) return mqq.invoke("qw_debug", "start")
	},
	android: function() {
		mqq.compare("4.7.1") >= 0 && mqq.invoke("qw_debug", "start")
	},
	support: {
		iOS: "4.7.1",
		android: "4.7.1"
	}
}), mqq.build("mqq.debug.stop", {
	iOS: function() {
		if (mqq.compare("4.7.1") >= 0) return mqq.invoke("qw_debug", "stop")
	},
	android: function() {
		mqq.compare("4.7.1") >= 0 && mqq.invoke("qw_debug", "stop")
	},
	support: {
		iOS: "4.7.1",
		android: "4.7.1"
	}
}), mqq.build("mqq.device.connectToWiFi", {
	iOS: function(e, t) {
		t && t(mqq.ERROR_NO_SUCH_METHOD)
	},
	android: function(e, t) {
		e.callback = mqq.callback(t), mqq.invoke("qbizApi", "connectToWiFi", e)
	},
	support: {
		android: "4.7"
	}
}), mqq.build("mqq.device.qqVersion", {
	iOS: function(e) {
		return mqq.invoke("device", "qqVersion", e)
	},
	support: {
		iOS: "4.5"
	}
}), mqq.build("mqq.device.qqBuild", {
	iOS: function(e) {
		return mqq.invoke("device", "qqBuild", e)
	},
	support: {
		iOS: "4.5"
	}
}), mqq.build("mqq.device.getClientInfo", {
	iOS: function(e) {
		var t = {
			qqVersion: this.qqVersion(),
			qqBuild: this.qqBuild()
		}, n = mqq.callback(e, !1, !0);
		mqq.__reportAPI("web", "device", "getClientInfo", null, n);
		if (typeof e != "function") return t;
		mqq.__fireCallback(n, [t])
	},
	android: function(e) {
		if (mqq.compare("4.6") >= 0) {
			var t = e;
			e = function(e) {
				try {
					e = JSON.parse(e)
				} catch (n) {}
				t && t(e)
			}, mqq.invoke("qbizApi", "getClientInfo", e)
		} else mqq.__reportAPI("web", "device", "getClientInfo"), e({
			qqVersion: mqq.QQVersion,
			qqBuild: function(e) {
				return e = e && e[1] || 0, e && e.slice(e.lastIndexOf(".") + 1) || 0
			}(navigator.userAgent.match(/\bqq\/([\d\.]+)/i))
		})
	},
	support: {
		iOS: "4.5",
		android: "4.6"
	}
}), mqq.build("mqq.device.systemName", {
	iOS: function(e) {
		return mqq.invoke("device", "systemName", e)
	},
	support: {
		iOS: "4.5"
	}
}), mqq.build("mqq.device.systemVersion", {
	iOS: function(e) {
		return mqq.invoke("device", "systemVersion", e)
	},
	support: {
		iOS: "4.5"
	}
}), mqq.build("mqq.device.model", {
	iOS: function(e) {
		return mqq.invoke("device", "model", e)
	},
	support: {
		iOS: "4.5"
	}
}), mqq.build("mqq.device.modelVersion", {
	iOS: function(e) {
		return mqq.invoke("device", "modelVersion", e)
	},
	support: {
		iOS: "4.5"
	}
}), mqq.build("mqq.device.getDeviceInfo", {
	iOS: function(e) {
		if (mqq.compare(4.7) >= 0) return mqq.invoke("device", "getDeviceInfo", e);
		var t = mqq.callback(e, !1, !0);
		mqq.__reportAPI("web", "device", "getClientInfo", null, t);
		var n = {
			isMobileQQ: this.isMobileQQ(),
			systemName: this.systemName(),
			systemVersion: this.systemVersion(),
			model: this.model(),
			modelVersion: this.modelVersion()
		};
		if (typeof e != "function") return n;
		mqq.__fireCallback(t, [n])
	},
	android: function(e) {
		if (mqq.compare("4.6") >= 0) {
			var t = e;
			e = function(e) {
				try {
					e = JSON.parse(e)
				} catch (n) {}
				t && t(e)
			}, mqq.invoke("qbizApi", "getDeviceInfo", e)
		} else {
			var n = navigator.userAgent;
			mqq.__reportAPI("web", "device", "getClientInfo"), e({
				isMobileQQ: !0,
				systemName: "android",
				systemVersion: function(e) {
					return e && e[1] || 0
				}(n.match(/\bAndroid ([\d\.]+)/i)),
				model: function(e) {
					return e && e[1] || null
				}(n.match(/;\s([^;]+)\s\bBuild\/\w+/i))
			})
		}
	},
	support: {
		iOS: "4.5",
		android: "4.5"
	}
}), mqq.build("mqq.device.getNetworkType", {
	iOS: function(e) {
		var t = mqq.invoke("device", "networkStatus");
		t = Number(t);
		if (typeof e != "function") return t;
		mqq.__fireCallback(e, [t], !1, !0)
	},
	android: function(e) {
		mqq.compare("4.6") >= 0 ? mqq.invoke("qbizApi", "getNetworkType", e) : mqq.invoke("publicAccount", "getNetworkState", function(t) {
			var n = {
				"-1": 0,
				0: 3,
				1: 1
			}, r = t in n ? n[t] : 4;
			e(r)
		})
	},
	support: {
		iOS: "4.5",
		android: "4.6"
	}
}), mqq.build("mqq.device.networkStatus", {
	iOS: mqq.device.getNetworkType,
	support: {
		iOS: "4.5"
	}
}), mqq.build("mqq.device.networkType", {
	iOS: mqq.device.getNetworkType,
	support: {
		iOS: "4.5"
	}
}), mqq.build("mqq.device.getWebViewType", {
	iOS: function(e) {
		return mqq.invoke("device", "webviewType", e)
	},
	android: function(e) {
		var t = 1,
			n = navigator.userAgent;
		return /\bPA\b/.test(n) ? (t = 5, /\bCoupon\b/.test(n) ? t = 2 : /\bMyCoupon\b/.test(n) && (t = 3)) : /\bQR\b/.test(n) && (t = 4), mqq.__reportAPI("web", "device", "getWebViewType"), e ? e(t) : t
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.device.webviewType", {
	iOS: mqq.device.getWebViewType,
	support: {
		iOS: "4.6"
	}
}), mqq.build("mqq.device.isMobileQQ", {
	iOS: function(e) {
		var t = mqq.iOS;
		return e ? e(t) : t
	},
	android: function(e) {
		var t = mqq.android;
		return e ? e(t) : t
	},
	browser: function(e) {
		var t = mqq.android || mqq.iOS;
		return e ? e(t) : t
	},
	support: {
		iOS: "4.2",
		android: "4.2"
	}
}), mqq.build("mqq.device.setScreenStatus", {
	iOS: function(e, t) {
		e = e || {}, e.callback = mqq.callback(t), mqq.invoke("device", "setScreenStatus", e)
	},
	android: function(e, t) {
		e = e || {}, e.callback = mqq.callback(t), mqq.invoke("device", "setScreenStatus", e)
	},
	support: {
		android: "5.0"
	}
}), mqq.build("mqq.media.getLocalImage", {
	iOS: function(e, t) {
		e.callback = mqq.callback(t, !0, !0), mqq.invoke("media", "getLocalImage", e)
	},
	android: function(e, t) {
		e.callback = mqq.callback(t), mqq.invoke("media", "getLocalImage", e)
	},
	support: {
		iOS: "4.7.2",
		android: "4.7.2"
	}
}), mqq.build("mqq.media.getPicture", {
	iOS: function(e, t) {
		!e.outMaxWidth && e.maxWidth && (e.outMaxWidth = e.maxWidth, delete e.maxWidth), !e.outMaxHeight && e.maxHeight && (e.outMaxHeight = e.maxHeight, delete e.maxHeight), e.callback = mqq.callback(function(e, n) {
			n && n.forEach && n.forEach(function(e, t) {
				typeof e == "string" && (n[t] = {
					data: e,
					imageID: "",
					match: 0
				})
			}), t && t(e, n)
		}, !0, !0), mqq.invoke("media", "getPicture", e)
	},
	android: function(e, t) {
		e.callback = mqq.callback(t), mqq.invoke("media", "getPicture", e)
	},
	support: {
		iOS: "4.7",
		android: "4.7"
	}
}), mqq.build("mqq.media.playLocalSound", {
	iOS: function(e) {
		mqq.invoke("sensor", "playLocalSound", e)
	},
	android: function(e) {
		mqq.invoke("qbizApi", "playVoice", e.bid, e.url)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.media.preloadSound", {
	iOS: function(e, t) {
		e.callback = mqq.callback(t, !0), mqq.invoke("sensor", "preloadSound", e)
	},
	android: function(e, t) {
		mqq.invoke("qbizApi", "preloadVoice", e.bid, e.url, mqq.callback(t, !0))
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.offline.checkUpdate", {
	iOS: function(e, t) {
		var n = mqq.callback(t);
		n && (e.callback = n, mqq.invoke("offline", "checkUpdate", e))
	},
	android: function(e, t) {
		mqq.invoke("qbizApi", "checkUpdate", e.bid, mqq.callback(t))
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.offline.downloadUpdate", {
	iOS: function(e, t) {
		var n = mqq.callback(t);
		n && (e.callback = n, mqq.invoke("offline", "downloadUpdate", e))
	},
	android: function(e, t) {
		var n = mqq.callback(t);
		e.fileSize && e.fileSize > 0 ? mqq.invoke("qbizApi", "forceUpdate", e.bid, e.url, e.fileSize, n) : mqq.invoke("qbizApi", "forceUpdate", e.bid, e.url, n)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.offline.isCached", {
	iOS: function(e, t) {
		var n = mqq.callback(t);
		n && (e.callback = n, mqq.invoke("offline", "isCached", e))
	},
	android: function(e, t) {
		mqq.invoke("qbizApi", "isCached", e.bid, mqq.callback(t))
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.pay.enablePay", {
	iOS: function(e) {
		mqq.invoke("pay", "enablePay", {
			params: e
		})
	},
	support: {
		iOS: "4.6"
	}
}), mqq.build("mqq.pay.pay", {
	iOS: function(e, t) {
		var n = t ? mqq.callback(t) : null;
		mqq.invoke("pay", "pay", {
			params: e,
			callback: n
		})
	},
	support: {
		iOS: "4.6"
	}
}), mqq.build("mqq.redpoint.getAppInfo", {
	iOS: function(e, t) {
		var n = mqq.callback(t);
		n && (e.callback = n), mqq.invoke("redpoint", "getAppInfo", e)
	},
	android: function(e, t) {
		var n = mqq.callback(t);
		n && (e.callback = n), mqq.invoke("redpoint", "getAppInfo", e)
	},
	support: {
		iOS: "4.7",
		android: "4.7"
	}
}),
function() {
	function n(n) {
		var r = null;
		if (e === !1) {
			e = location.search == "" ? location.hash == "" ? "" : location.hash.substring(1) : location.search.substring(1), e = e.split("&");
			if (e.length > 0)
				for (var i = 0; i < e.length; i++) {
					r = e[i], r = r.split("=");
					if (r.length > 1) try {
						t[r[0]] = decodeURIComponent(r[1])
					} catch (s) {
						t[r[0]] = ""
					}
				}
		}
		return typeof t[n] != "undefined" ? t[n] : ""
	}

	function f(e) {
		var t = {
			sid: r,
			appid: e.substring(e.lastIndexOf(".") + 1),
			platid: i,
			qqver: s,
			format: "json",
			_: (new Date).getTime()
		}, n = "get_new_msg_cnt";
		try {
			Zepto.ajax({
				type: "get",
				url: o + n,
				dataType: "json",
				data: t,
				timeout: 1e4,
				success: function(e) {
					var t = {
						ret: e.ecode,
						count: 0
					};
					e.ecode == 0 && (t.count = e.new_msg_cnt), a(t)
				},
				error: function() {
					a({
						ret: -1,
						count: 0
					})
				}
			})
		} catch (u) {
			a({
				ret: -2,
				count: 0
			})
		}
	}

	function l(e) {
		if (e.code == 0) {
			var t = {
				ret: e.code,
				count: 0
			}, n = e.data.buffer,
				r = [];
			n = typeof n != "object" && n != "" ? JSON.parse(n) : n;
			if (typeof n.msg != "undefined")
				for (var i in n.msg) n.msg[i].stat == 1 && t.count++;
			a(t)
		} else a({
			ret: e.code,
			list: []
		})
	}
	var e = !1,
		t = {}, r = n("sid"),
		i = mqq.iOS ? 110 : mqq.android ? 109 : 0,
		s = mqq.QQVersion ? mqq.QQVersion : "",
		o = "http://msg.vip.qq.com/cgi-bin/",
		u = function() {
			return mqq.compare("4.7") >= 0
		}(),
		a = null;
	mqq.build("mqq.redpoint.getNewMsgCnt", {
		iOS: function(e, t) {
			appid = String(e.path), a = t;
			if (u) mqq.redpoint.getAppInfo(e, l);
			else {
				if (!Zepto) {
					typeof t == "function" ? t({
						ret: -1e4,
						count: 0
					}) : null;
					return
				}
				f(appid)
			}
		},
		android: function(e, t) {
			appid = String(e.path), a = t;
			if (u) mqq.redpoint.getAppInfo(e, l);
			else {
				if (!Zepto) {
					typeof t == "function" ? t({
						ret: -1e4,
						count: 0
					}) : null;
					return
				}
				f(appid)
			}
		},
		support: {
			iOS: "4.5",
			android: "4.5"
		}
	})
}(),
function() {
	function n(n) {
		var r = null;
		if (e === !1) {
			e = location.search == "" ? location.hash == "" ? "" : location.hash.substring(1) : location.search.substring(1), e = e.split("&");
			if (e.length > 0)
				for (var i = 0; i < e.length; i++) {
					r = e[i], r = r.split("=");
					if (r.length > 1) try {
						t[r[0]] = decodeURIComponent(r[1])
					} catch (s) {
						t[r[0]] = ""
					}
				}
		}
		return typeof t[n] != "undefined" ? t[n] : ""
	}

	function f(e) {
		var t = {
			sid: r,
			appid: e.substring(e.lastIndexOf(".") + 1),
			platid: i,
			qqver: s,
			format: "json",
			_: (new Date).getTime()
		}, n = "read_msg";
		try {
			Zepto.ajax({
				type: "get",
				url: o + n,
				dataType: "json",
				data: t,
				timeout: 1e4,
				success: function(e) {
					var t = {
						ret: e.ecode,
						list: []
					};
					if (e.ecode == 0) {
						var n = e.msg,
							r = [];
						for (var i in n) r.push({
							content: n[i].content ? n[i].content : "",
							link: n[i].link ? n[i].link : "",
							img: n[i].img ? n[i].img : "",
							pubTime: n[i].time ? n[i].time : "",
							title: n[i].title ? n[i].title : "",
							src: n[i].src ? n[i].src : "",
							ext1: n[i].ext1 ? n[i].ext1 : "",
							ext2: n[i].ext2 ? n[i].ext2 : "",
							ext3: n[i].ext3 ? n[i].ext3 : "",
							id: i
						});
						t.list = r
					}
					a(t)
				},
				error: function() {
					a({
						ret: -1,
						list: []
					})
				}
			})
		} catch (u) {
			a({
				ret: -2,
				list: []
			})
		}
	}

	function l(e) {
		if (e.code == 0) {
			var t = {
				ret: e.code,
				list: []
			}, n = e.data.buffer,
				u = [];
			n = typeof n != "object" && n != "" ? JSON.parse(n) : n;
			if (typeof n.msg != "undefined") {
				for (var f in n.msg) n.msg[f].stat == 1 && (u.push({
					content: n.msg[f].content ? n.msg[f].content : "",
					link: n.msg[f].link ? n.msg[f].link : "",
					img: n.msg[f].img ? n.msg[f].img : "",
					pubTime: n.msg[f].time ? n.msg[f].time : "",
					title: n.msg[f].title ? n.msg[f].title : "",
					src: n.msg[f].src ? n.msg[f].src : "",
					ext1: n.msg[f].ext1 ? n.msg[f].ext1 : "",
					ext2: n.msg[f].ext2 ? n.msg[f].ext2 : "",
					ext3: n.msg[f].ext3 ? n.msg[f].ext3 : "",
					id: f
				}), n.msg[f].stat = 2);
				e.data.buffer = JSON.stringify(n);
				if (u.length > 0) {
					t.list = u, mqq.redpoint.setAppInfo({
						appInfo: e.data
					}, function(e) {
						console.log(JSON.stringify(e))
					});
					var l = e.data.appID,
						c = {
							sid: r,
							appid: l,
							platid: i,
							qqver: s,
							format: "json",
							_: (new Date).getTime()
						}, h = "read_msg";
					try {
						Zepto.ajax({
							type: "get",
							url: o + h,
							dataType: "json",
							data: c,
							timeout: 1e4,
							success: function(e) {},
							error: function() {}
						})
					} catch (p) {}
				}
			}
			a(t)
		} else a({
			ret: e.code,
			list: []
		})
	}
	var e = !1,
		t = {}, r = n("sid"),
		i = mqq.iOS ? 110 : mqq.android ? 109 : 0,
		s = mqq.QQVersion ? mqq.QQVersion : "",
		o = "http://msg.vip.qq.com/cgi-bin/",
		u = function() {
			return mqq.compare("4.7") >= 0
		}(),
		a = null;
	mqq.build("mqq.redpoint.getNewMsgList", {
		iOS: function(e, t) {
			appid = String(e.path), a = t;
			if (u) mqq.redpoint.getAppInfo(e, l);
			else {
				if (!Zepto) {
					typeof t == "function" ? t({
						ret: -1e4,
						count: 0
					}) : null;
					return
				}
				f(appid)
			}
		},
		android: function(e, t) {
			appid = String(e.path), a = t;
			if (u) mqq.redpoint.getAppInfo(e, l);
			else {
				if (!Zepto) {
					typeof t == "function" ? t({
						ret: -1e4,
						count: 0
					}) : null;
					return
				}
				f(appid)
			}
		},
		support: {
			iOS: "4.5",
			android: "4.5"
		}
	})
}(), mqq.build("mqq.redpoint.getRedPointShowInfo", {
	iOS: function(e, t) {
		var n = mqq.callback(t);
		n && (e.callback = n), mqq.invoke("redpoint", "getRedPointShowInfo", e)
	},
	android: function(e, t) {
		var n = mqq.callback(t);
		n && (e.callback = n), mqq.invoke("redpoint", "getRedPointShowInfo", e)
	},
	support: {
		iOS: "4.7",
		android: "4.7"
	}
}), mqq.build("mqq.redpoint.reportRedTouch", {
	iOS: function(e, t) {
		var n = mqq.callback(t);
		n && (e.callback = n), mqq.invoke("redpoint", "reportRedTouch", e)
	},
	android: function(e, t) {
		var n = mqq.callback(t);
		n && (e.callback = n), mqq.invoke("redpoint", "reportRedTouch", e)
	},
	support: {
		iOS: "4.7",
		android: "4.7"
	}
}), mqq.build("mqq.redpoint.setAppInfo", {
	iOS: function(e, t) {
		var n = mqq.callback(t);
		n && (e.callback = n), mqq.invoke("redpoint", "setAppInfo", e)
	},
	android: function(e, t) {
		var n = mqq.callback(t);
		n && (e.callback = n), mqq.invoke("redpoint", "setAppInfo", e)
	},
	support: {
		iOS: "4.7",
		android: "4.7"
	}
}), mqq.build("mqq.sensor.getLocation", {
	iOS: function(e) {
		return mqq.invoke("data", "queryCurrentLocation", {
			callback: mqq.callback(e)
		})
	},
	android: function(e) {
		var t = mqq.callback(function(t) {
			var n = -1,
				r = null,
				i = null;
			t && t !== "null" && (t = (t + "").split(","), t.length === 2 && (n = 0, r = parseFloat(t[0] || 0), i = parseFloat(t[1] || 0))), e(n, i, r)
		}, !0);
		mqq.invoke("publicAccount", "getLocation", t)
	},
	browser: function(e) {
		navigator.geolocation ? navigator.geolocation.getCurrentPosition(function(t) {
			var n = t.coords.latitude,
				r = t.coords.longitude;
			e(0, n, r)
		}, function() {
			e(-1)
		}) : e(-1)
	},
	support: {
		iOS: "4.5",
		android: "4.6",
		browser: "0"
	}
}), mqq.build("mqq.sensor.getRealLocation", {
	iOS: function(e, t) {
		var n = t ? mqq.callback(t) : null;
		return mqq.invoke("data", "getOSLocation", {
			params: e,
			callback: n
		})
	},
	android: function(e, t) {
		e = JSON.stringify(e || {}), mqq.invoke("publicAccount", "getRealLocation", e, mqq.callback(t, !0))
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.sensor.getSensorStatus", {
	iOS: function(e, t) {
		e = e || {
			type: "gps"
		}, e.callbackName = mqq.callback(t), mqq.invoke("sensor", "getSensorStatus", e)
	},
	support: {
		iOS: "4.7"
	}
}), mqq.build("mqq.sensor.startAccelerometer", {
	iOS: function(e) {
		var t = mqq.callback(e, !1, !0);
		t && mqq.invoke("sensor", "startAccelerometer", {
			callback: t
		})
	},
	android: function(e) {
		var t = mqq.callback(e, !1, !0);
		mqq.invoke("qbizApi", "startAccelerometer", t)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.sensor.startCompass", {
	iOS: function(e) {
		var t = mqq.callback(e, !1, !0);
		t && mqq.invoke("sensor", "startCompass", {
			callback: t
		})
	},
	android: function(e) {
		var t = mqq.callback(e, !1, !0);
		mqq.invoke("qbizApi", "startCompass", t)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.sensor.startListen", {
	iOS: function(e) {
		var t = mqq.callback(e, !1, !0);
		t && mqq.invoke("sensor", "startListen", {
			callback: t
		})
	},
	android: function(e) {
		var t = mqq.callback(e, !1, !0);
		mqq.invoke("qbizApi", "startListen", t)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.sensor.stopAccelerometer", {
	iOS: function() {
		mqq.invoke("sensor", "stopAccelerometer")
	},
	android: function() {
		mqq.invoke("qbizApi", "stopAccelerometer")
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.sensor.stopCompass", {
	iOS: function() {
		mqq.invoke("sensor", "stopCompass")
	},
	android: function() {
		mqq.invoke("qbizApi", "stopCompass")
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.sensor.stopListen", {
	iOS: function() {
		mqq.invoke("sensor", "stopListen")
	},
	android: function() {
		mqq.invoke("qbizApi", "stopListen")
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.sensor.vibrate", {
	iOS: function(e) {
		e = e || {}, mqq.invoke("sensor", "vibrate", e)
	},
	android: function(e) {
		e = e || {}, mqq.invoke("qbizApi", "phoneVibrate", e.time)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.tenpay.buyGoods", {
	android: function(e, t) {
		mqq.invoke("pay", "buyGoods", JSON.stringify(e), t)
	},
	support: {
		android: "4.6.1"
	}
}), mqq.build("mqq.tenpay.openService", {
	android: function(e, t) {
		mqq.invoke("pay", "openService", JSON.stringify(e), t)
	},
	support: {
		android: "4.6.1"
	}
}), mqq.build("mqq.tenpay.openTenpayView", {
	iOS: function(e, t) {
		var n = t ? mqq.callback(t) : null;
		mqq.invoke("pay", "openTenpayView", {
			params: e,
			callback: n
		})
	},
	android: function(e, t) {
		mqq.invoke("pay", "openTenpayView", JSON.stringify(e), t)
	},
	support: {
		iOS: "4.6.1",
		android: "4.6.1"
	}
}),
function() {
	var e = function(e) {
		return function(t, n) {
			t = Number(t);
			var r = {
				resultCode: t,
				retmsg: "",
				data: {}
			};
			if (t === 0) {
				var i = n;
				n = mqq.mapQuery(n), n.sp_data = i, n.attach && n.attach.indexOf("{") === 0 && (n.attach = JSON.parse(n.attach)), n.time_end && (n.pay_time = n.time_end), r.data = n
			} else t === 1 || t === -1 ? (r.retmsg = "\u7528\u6237\u4e3b\u52a8\u653e\u5f03\u652f\u4ed8", r.resultCode = -1) : r.retmsg = n;
			e && e(r)
		}
	};
	mqq.build("mqq.tenpay.pay", {
		iOS: function(t, n) {
			t.order_no = t.tokenId || t.tokenID, t.app_info = t.app_info || t.appInfo, mqq.compare("4.6.2") >= 0 ? mqq.invokeSchema("mqqapi", "wallet", "pay", t, e(n)) : mqq.invokeSchema("mqqapiwallet", "wallet", "pay", t, e(n))
		},
		android: function(t, n) {
			t.token_id = t.tokenId || t.tokenID, t.app_info = t.app_info || t.appInfo, mqq.compare("4.6.1") >= 0 ? mqq.invoke("pay", "pay", JSON.stringify(t), n) : mqq.invokeSchema("mqqapi", "tenpay", "pay", t, e(n))
		},
		support: {
			iOS: "4.6.1",
			android: "4.6.1"
		}
	})
}(), mqq.build("mqq.tenpay.rechargeGameCurrency", {
	android: function(e, t) {
		mqq.invoke("pay", "rechargeGameCurrency", JSON.stringify(e), t)
	},
	support: {
		android: "4.6.1"
	}
}), mqq.build("mqq.tenpay.rechargeQb", {
	android: function(e, t) {
		mqq.invoke("pay", "rechargeQb", JSON.stringify(e), t)
	},
	support: {
		android: "4.6.1"
	}
}), mqq.build("mqq.ui.openAIO", {
	iOS: function(e) {
		mqq.invokeSchema("mqqapi", "im", "chat", e)
	},
	android: function(e) {
		mqq.invokeSchema("mqqapi", "im", "chat", e)
	},
	support: {
		iOS: "4.5",
		android: "4.5"
	}
}), mqq.build("mqq.ui.openUrl", {
	iOS: function(e) {
		e || (e = {});
		switch (e.target) {
			case 0:
				window.open(e.url, "_self");
				break;
			case 1:
				e.styleCode = {
					1: 4,
					2: 2,
					3: 5
				}[e.style] || 1, mqq.invoke("nav", "openLinkInNewWebView", {
					url: e.url,
					options: e
				});
				break;
			case 2:
				mqq.invoke("nav", "openLinkInSafari", {
					url: e.url
				})
		}
	},
	android: function(e) {
		e.target === 2 ? mqq.compare("4.6") >= 0 ? mqq.invoke("publicAccount", "openInExternalBrowser", e.url) : mqq.compare("4.5") >= 0 && mqq.invoke("openUrlApi", "openUrl", e.url) : e.target === 1 ? (e.style || (e.style = 0), mqq.compare("4.6") >= 0 ? mqq.invoke("qbizApi", "openLinkInNewWebView", e.url, e.style) : mqq.compare("4.5") >= 0 ? mqq.invoke("publicAccount", "openUrl", e.url) : location.href = e.url) : location.href = e.url
	},
	browser: function(e) {
		e.target === 2 ? window.open(e.url, "_blank") : location.href = e.url
	},
	support: {
		iOS: "4.5",
		android: "4.6",
		browser: "0"
	}
}),
function() {
	var e = {}, t = {
			Abount: "com.tencent.mobileqq.activity.AboutActivity",
			GroupTribePublish: "com.tencent.mobileqq.troop.activity.TroopBarPublishActivity",
			GroupTribeReply: "com.tencent.mobileqq.troop.activity.TroopBarReplyActivity",
			GroupTribeComment: "com.tencent.mobileqq.troop.activity.TroopBarCommentActivity"
		};
	mqq.build("mqq.ui.openView", {
		iOS: function(t) {
			t.name = e[t.name] || t.name, typeof t.onclose == "function" && (t.onclose = mqq.callback(t.onclose)), mqq.invoke("nav", "openViewController", t)
		},
		android: function(e) {
			e.name = t[e.name] || e.name, typeof e.onclose == "function" && (e.onclose = mqq.callback(e.onclose)), mqq.compare("5.0") > -1 ? mqq.invoke("ui", "openView", e) : mqq.invoke("publicAccount", "open", e.name)
		},
		support: {
			iOS: "4.5",
			android: "4.6"
		}
	})
}(), mqq.build("mqq.ui.pageVisibility", {
	iOS: function(e) {
		mqq.invoke("ui", "pageVisibility", e)
	},
	android: function(e) {
		mqq.invoke("ui", "pageVisibility", e)
	},
	support: {
		iOS: "4.7",
		android: "4.7"
	}
}), mqq.build("mqq.ui.popBack", {
	iOS: function() {
		mqq.invoke("nav", "popBack")
	},
	android: function() {
		mqq.invoke("publicAccount", "close")
	},
	support: {
		iOS: "4.5",
		android: "4.6"
	}
}), mqq.build("mqq.ui.refreshTitle", {
	iOS: function() {
		mqq.invoke("nav", "refreshTitle")
	},
	support: {
		iOS: "4.6"
	}
}), mqq.build("mqq.ui.returnToAIO", {
	iOS: function() {
		mqq.invoke("nav", "returnToAIO")
	},
	android: function() {
		mqq.invoke("qbizApi", "returnToAIO")
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.ui.scanQRcode", {
	iOS: function(e, t) {
		e = e || {}, t && (e.callback = mqq.callback(t)), mqq.invoke("ui", "scanQRcode", e)
	},
	android: function(e, t) {
		e = e || {}, t && (e.callback = mqq.callback(t)), mqq.invoke("ui", "scanQRcode", e)
	},
	support: {
		iOS: "4.7",
		android: "4.7"
	}
}), mqq.build("mqq.ui.setActionButton", {
	iOS: function(e, t) {
		typeof e != "object" && (e = {
			title: e
		});
		var n = mqq.callback(t, !1, !0);
		e.callback = n, mqq.invoke("nav", "setActionButton", e)
	},
	android: function(e, t) {
		var n = mqq.callback(t);
		e.hidden && (e.title = ""), mqq.compare("4.7") >= 0 ? (e.callback = n, mqq.invoke("ui", "setActionButton", e)) : mqq.invoke("publicAccount", "setRightButton", e.title, "", n)
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.ui.setLoading", {
	iOS: function(e) {
		e && (e.visible === !0 ? mqq.invoke("nav", "showLoading") : e.visible === !1 && mqq.invoke("nav", "hideLoading"), e.color && mqq.invoke("nav", "setLoadingColor", {
			r: e.color[0],
			g: e.color[1],
			b: e.color[2]
		}))
	},
	android: function(e) {
		"visible" in e && (e.visible ? mqq.invoke("publicAccount", "showLoading") : mqq.invoke("publicAccount", "hideLoading"))
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.ui.setOnCloseHandler", {
	iOS: function(e) {
		mqq.invoke("ui", "setOnCloseHandler", {
			callback: mqq.callback(e, !1, !0)
		})
	},
	android: function(e) {
		mqq.invoke("ui", "setOnCloseHandler", {
			callback: mqq.callback(e)
		})
	},
	support: {
		iOS: "4.7",
		android: "4.7"
	}
}), mqq.build("mqq.ui.setOnShareHandler", {
	iOS: function(e) {
		mqq.invoke("nav", "addWebShareListener", {
			callback: mqq.callback(e, !1, !0)
		})
	},
	android: function(e) {
		mqq.invoke("ui", "setOnShareHandler", {
			callback: mqq.callback(e, !1, !0)
		})
	},
	support: {
		iOS: "4.7.2",
		android: "4.7.2"
	}
}), mqq.build("mqq.ui.setWebViewBehavior", {
	iOS: function(e) {
		mqq.invoke("ui", "setWebViewBehavior", e)
	},
	android: function(e) {
		mqq.invoke("ui", "setWebViewBehavior", e)
	},
	support: {
		iOS: "4.7.2",
		android: "5.1"
	}
}), mqq.build("mqq.ui.shareAudio", {
	iOS: function(e, t) {
		var n = mqq.callback(t, !0);
		e.desc && (e.desc = e.desc.length > 50 ? e.desc.substring(0, 50) + "..." : e.desc), mqq.invoke("nav", "shareAudio", {
			params: e,
			callback: n
		})
	},
	android: function(e, t) {
		e.req_type = 2, t && (e.callback = mqq.callback(t, !0)), e.desc && (e.desc = e.desc.length > 50 ? e.desc.substring(0, 50) + "..." : e.desc), mqq.invoke("QQApi", "shareMsg", e)
	},
	support: {
		iOS: "4.7",
		android: "4.7"
	}
}), mqq.build("mqq.ui.shareMessage", {
	iOS: function(e, t) {
		!("needPopBack" in e) && "back" in e && (e.needPopBack = e.back), e.share_url && (e.share_url = mqq.removeQuery(e.share_url, ["sid", "3g_sid"])), e.desc && (e.desc = e.desc.length > 50 ? e.desc.substring(0, 50) + "..." : e.desc), e.callback = mqq.callback(t, !0, !0), mqq.invoke("nav", "shareURLWebRichData", e)
	},
	android: function(e, t) {
		e.share_url && (e.share_url = mqq.removeQuery(e.share_url, ["sid", "3g_sid"])), e.callback = mqq.callback(function(e) {
			t && t({
				retCode: e ? 0 : 1
			})
		}, !0), e.desc && (e.desc = e.desc.length > 50 ? e.desc.substring(0, 50) + "..." : e.desc);
		if (e.share_type && (e.share_type === 2 || e.share_type === 3) && mqq.compare("5.2") < 0 && mqq.support("mqq.app.isAppInstalled")) {
			var n = "\u60a8\u5c1a\u672a\u5b89\u88c5\u5fae\u4fe1\uff0c\u4e0d\u53ef\u4f7f\u7528\u6b64\u529f\u80fd";
			mqq.app.isAppInstalled("com.tencent.mm", function(t) {
				t ? mqq.invoke("QQApi", "shareMsg", e) : mqq.support("mqq.ui.showTips") ? mqq.ui.showTips({
					text: n
				}) : alert(n)
			})
		} else mqq.invoke("QQApi", "shareMsg", e)
	},
	support: {
		iOS: "4.7.2",
		android: "4.7.2"
	}
}), mqq.build("mqq.ui.shareRichMessage", {
	iOS: function(e, t) {
		e.puin = e.oaUin, e.desc = e.desc || e.summary, e.share_url && (e.share_url = mqq.removeQuery(e.share_url, ["sid", "3g_sid"])), e.desc && (e.desc = e.desc.length > 50 ? e.desc.substring(0, 50) + "..." : e.desc), e.callback = mqq.callback(t), mqq.invoke("nav", "officalAccountShareRichMsg2QQ", e)
	},
	android: function(e, t) {
		e.puin = e.oaUin, e.desc = e.desc || e.summary, e.desc && (e.desc = e.desc.length > 50 ? e.desc.substring(0, 50) + "..." : e.desc), mqq.compare("5.0") >= 0 ? (e.share_url = e.share_url || e.targetUrl, e.image_url = e.image_url || e.imageUrl, e.share_url && (e.share_url = mqq.removeQuery(e.share_url, ["sid", "3g_sid"])), e.callback = t ? mqq.callback(function(e) {
			t({
				ret: e ? 0 : 1
			})
		}) : null, mqq.invoke("QQApi", "shareMsg", e)) : (e.targetUrl = e.targetUrl || e.share_url, e.imageUrl = e.imageUrl || e.image_url, e.targetUrl && (e.targetUrl = mqq.removeQuery(e.targetUrl, ["sid", "3g_sid"])), e.callback = mqq.callback(t), mqq.invoke("publicAccount", "officalAccountShareRichMsg2QQ", e))
	},
	support: {
		iOS: "4.7",
		android: "4.7"
	}
}), mqq.build("mqq.data.shareRichMessage", {
	iOS: mqq.ui.shareRichMessage,
	android: mqq.ui.shareRichMessage,
	support: {
		iOS: "4.7",
		android: "4.7"
	}
}), mqq.build("mqq.ui.showActionSheet", {
	iOS: function(e, t) {
		return t && (e.onclick = mqq.callback(t, !1, !0)), mqq.invoke("ui", "showActionSheet", e)
	},
	android: function(e, t) {
		return t && (e.onclick = mqq.callback(t)), mqq.invoke("ui", "showActionSheet", e)
	},
	support: {
		iOS: "4.7",
		android: "4.7"
	}
}), mqq.build("mqq.ui.showDialog", {
	iOS: function(e, t) {
		e && (e.callback = mqq.callback(t, !0, !0), e.title = e.title + "", e.text = e.text + "", "needOkBtn" in e || (e.needOkBtn = !0), "needCancelBtn" in e || (e.needCancelBtn = !0), mqq.invoke("nav", "showDialog", e))
	},
	android: function(e, t) {
		if (mqq.compare("4.8.0") >= 0) e.callback = mqq.callback(t, !0), mqq.invoke("ui", "showDialog", e);
		else {
			var n = "",
				r = "";
			t && (n = mqq.callback(function() {
				t({
					button: 0
				})
			}, !0), r = mqq.callback(function() {
				t({
					button: 1
				})
			}, !0), n += "()", r += "()"), e.title = e.title + "", e.text = e.text + "", "needOkBtn" in e || (e.needOkBtn = !0), "needCancelBtn" in e || (e.needCancelBtn = !0), mqq.invoke("publicAccount", "showDialog", e.title, e.text, e.needOkBtn, e.needCancelBtn, n, r)
		}
	},
	support: {
		iOS: "4.6",
		android: "4.6"
	}
}), mqq.build("mqq.ui.showEQQ", {
	iOS: function(e) {
		mqq.invoke("nav", "showBusinessAccountProfile", e)
	},
	android: function(e) {
		mqq.invoke("eqq", "showEQQ", e)
	},
	support: {
		iOS: "4.7",
		android: "4.7"
	}
}), mqq.build("mqq.ui.showOfficalAccountDetail", {
	iOS: function(e) {
		var t = typeof e == "object" ? e : {
			uin: e
		};
		mqq.invoke("nav", "showOfficalAccountDetail", t)
	},
	android: function(e) {
		mqq.compare("4.6") >= 0 ? mqq.invoke("publicAccount", "viewAccount", e.uin, e.showAIO) : mqq.invoke("publicAccount", "viewAccount", e.uin)
	},
	support: {
		iOS: "4.5",
		android: "4.6"
	}
}), mqq.build("mqq.ui.showProfile", {
	iOS: function(e) {
		mqq.compare("4.7") >= 0 ? mqq.invoke("nav", "showProfile", e) : mqq.compare("4.6") >= 0 && !e.uinType ? mqq.invoke("nav", "showProfile", e) : (e.uinType === 1 && (e.card_type = "group"), mqq.invokeSchema("mqqapi", "card", "show_pslcard", e))
	},
	android: function(e) {
		mqq.compare("4.7") >= 0 ? mqq.invoke("publicAccount", "showProfile", e) : mqq.compare("4.6") >= 0 && !e.uinType ? mqq.invoke("publicAccount", "showProfile", e.uin) : (e.uinType === 1 && (e.card_type = "group"), mqq.invokeSchema("mqqapi", "card", "show_pslcard", e))
	},
	support: {
		iOS: "4.5",
		android: "4.5"
	}
}), mqq.build("mqq.ui.showTips", {
	iOS: function(e) {
		mqq.invoke("ui", "showTips", e)
	},
	android: function(e) {
		mqq.invoke("ui", "showTips", e)
	},
	support: {
		iOS: "4.7",
		android: "4.7"
	}
}), mqq.build("mqq.viewTracks.getTrackInfo", {
	iOS: function(e, t) {
		e = e || {};
		var n = mqq.callback(t);
		n && (e.callback = n), mqq.invoke("viewTracks", "getTrackInfo", e)
	},
	android: function(e, t) {
		e = e || {};
		var n = mqq.callback(t);
		n && (e.callback = n), mqq.invoke("viewTracks", "getTrackInfo", e)
	},
	support: {
		iOS: "5.1",
		android: "5.1"
	}
}), mqq.build("mqq.viewTracks.pop", {
	iOS: function(e, t) {
		e = e || {};
		var n = mqq.callback(t);
		n && (e.callback = n), mqq.invoke("viewTracks", "pop", e)
	},
	android: function(e, t) {
		e = e || {};
		var n = mqq.callback(t);
		n && (e.callback = n), mqq.invoke("viewTracks", "pop", e)
	},
	support: {
		iOS: "5.1",
		android: "5.1"
	}
}), mqq.build("mqq.viewTracks.push", {
	iOS: function(e, t) {
		var n = mqq.callback(t);
		n && (e.callback = n), mqq.invoke("viewTracks", "push", e)
	},
	android: function(e, t) {
		var n = mqq.callback(t);
		n && (e.callback = n), mqq.invoke("viewTracks", "push", e)
	},
	support: {
		iOS: "5.1",
		android: "5.1"
	}
})