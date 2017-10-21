define("app/weixinV2.1/book/index", ["gallery/zepto/1.1.3/zepto", "./touch", "./pageflip", "app/weixinV2.1/book/utilities", "./modal", "./imageModal", "app/weixinV2.1/book/validator", "app/weixinV2.1/book/global"],
function(a) {
	function b(a) {
		var b = new PageFlip(j("#myBook"), {
			loadingDomString: j("#loading-container").html(),
			keyboardShortCuts: !1,
			quickFlip: !0,
			touchGesture: !0,
			touchPlugin: "custom",
			dataPageUrlList: a.page
		});
		l.flipObj = b,
		l.dataJson = a,
		b.addEventListener("pageDomLoaded", i),
		b.addEventListener("pageselected", c);
		var d = j("#fixedFooter").find("a.mu");



		d.on("click",
		function() {
		//	var a = q.replace("{city}", n).replace("{hid}", o),
			b = j("#menu-overlay");
			var a=book_index_conver_url;

			return "" == j.trim(b.html()) ? (j.mobileModal.showModal(j("#loading-container").html()), k.getAjaxPageBody(a,
			function(a) {
				var c = k.getDomBody(a);
				b.html(c),
				g()
			},
			function() {})) : g(),
			!1
		}),
		setTimeout(function() {
			var a = /^#page-([0-9]{1,2})$/,
			c = location.hash.match(a),
			d = history.state && history.state.pageId ? history.state.pageId: null,
			e = c && c[1] ? c[1] : d;
			e && b.setCurrentPage(e)
		},
		0)
	}
	
	function c(a) {
		h(a),
		f(a),
		e(a),
		d(a)
	}
	
	function d(a) {
		return history.replaceState && a && a.detail && history.replaceState({
			pageId: a.detail.currentPageIndex + 1
		},
		"new page", "#page-" + (a.detail.currentPageIndex + 1)),
		!1
	}
	
	function e(a) {
		if (a && a.detail) {
			var b = a.detail.currentPageIndex,
			c = l.dataJson.page[b].module_id;
			"video" == c ? j("video").show() : j("video").hide()
		}
	}
	
	function f() {
		var a = j("video");
		a.each(function() {
			this.pause()
		})
	}
	
	function g() {
		var b = j(j("#menu-overlay").html()).clone().attr("id", "menuModal");
		j.mobileModal.showModal(b),
		a.async("./menuModal.js")
	}
	
	function h(a) {
		var b = l.flipObj,
		c = b.getCurrentPage(),
		d = j("#fixedFooter");
		1 != c ? d.show() : d.hide();
		var e = d.find(".bymu .gun"),
		f = e.find("p"),
		g = d.find("div.con >span");
		if (a) {
			var h = a.detail.currentPageIndex,
			i = l.dataJson.page[h];
			if (i) {
				var k = i.module_name;
				g.html(k)
			}
		}
		var m = e.eq(0).width(),
		n = f.eq(0).width(),
		o = m - n,
		p = 1 == c ? 0 : c,
		q = b.count(),
		r = p / q,
		s = r * o;
		return f.html("<em>" + c + "</em>/" + q),
		f.css("left", s + "px"),
		!1
	}
	
	function i(b) {
		b.detail.url,
		b.detail.pageId;
		switch (h(), b.detail.module_id) {
		case "cover":
			break;
		case "book_index":
			a.async("./book_index.js");
			break;
		case "overview":
			break;
		case "news":
			break;
		case "facility":
			a.async("./facility.js");
			break;
		case "unit_type":
			j(".sHouseType img").imageModal();
			break;
			case "xc":
				j(".sHouseType img").imageModal();
				break;
		case "editor_comments":
			break;
		case "user_comments":
			a.async("./userComments.js");
			break;
		case "house_images":
			j(".sHouseType01 img").imageModal();
			break;
		case "order":
			a.async("./kft.js");
			break;
		case "price_analysis":
			break;
		case "video":
			a.async("./video.js");
			break;
		case "view360":
			break;
		case "customer_recommendation":
			a.async("./customerReference.js");
			break;
		case "gift":
			a.async("./gift.js");
			break;
		case "house_agent":
			a.async("./agent.js");
			break;
		case "tehui_list":
			a.async("./couponList.js");
			break;
		case "tehui":
			a.async("./couponForm.js")
		}
	}
	
	
	var j = a("gallery/zepto/1.1.3/zepto");
	a("./touch"),
	a("./pageflip"),
	a("./modal"),
	a("./imageModal"),
	a("app/weixinV2.1/book/validator");
	var k = a("app/weixinV2.1/book/utilities"),
	l = a("app/weixinV2.1/book/global"),
	m = k.urlArgs(),
	n = m.city || "bj",
	o = m.hid || "100023",
	p = "/weixin/house/book_index.html?city={city}&hid={hid}&api=1",
	q = "/weixin/house/book_index.html?type=1&city={city}&hid={hid}";
	/*var mulu={
	    "page": [
	             {
	                 "url": "./mulu.html",
	                 "module_id": "cover",
	                 "module_name": "封面"
	             },
	             {
	                 "url": "./mulu.html",
	                 "module_id": "book_index",
	                 "module_name": "目录"
	             },
	             {
	                 "url": "./gailan.html",
	                 "module_id": "overview",
	                 "module_name": "概览篇"
	             },
	             
	             {
	                 "url": "./huxing.html",
	                 "module_id": "unit_type",
	                 "module_name": "户型篇"
	             },
	             {
	                 "url": "./baoming.html",
	                 "module_id": "order",
	                 "module_name": "预约看房"
	             },
	             {
	                 "url": "./guwen.html",
	                 "module_id": "house_agent",
	                 "module_name": "置业顾问"
	             }
	         ]
	     };*/
	
	b(mulu);
	/*
	j.ajax({
		type: "get",
		url: p.replace("{city}", n).replace("{hid}", o),
		dataType: "json",
		success: b,
		error: function() {}
	}),*/
	
	j("#watch-container").on("click",
	function() {
		return j(this).hide(),
		!1
	}),
	j("#fixedFooter").on("click", "a.zi",
	function(a) {
		a.stopPropagation()
	}),
	j(document).on("click", "a",
	function(a) {
		return a.target.href && 0 == a.target.href.indexOf("tel:") ? void a.stopPropagation() : !1
	})
}),
function(a) {
	if ("undefined" != typeof define && define.cmd) define("app/weixinV2.1/book/touch", ["gallery/zepto/1.1.3/zepto"],
	function(b) {
		var c = b("gallery/zepto/1.1.3/zepto");
		a(c)
	});
	else {
		var b = Zepto;
		a(b)
	}
} (function(a) {
	function b(a) {
		p(),
		q = a.touches[0].clientX,
		r = a.touches[0].clientY
	}
	function c(a) {
		w = a.touches[0].clientX,
		x = a.touches[0].clientY
	}
	function d(b) {
		if (null !== w && null !== x) {
			s = Math.abs(q - w),
			t = Math.abs(r - x);
			var c = a(b.target) || a(document); (s > y || t > z) && (c.trigger("swipeMy"), c.trigger("swipe" + k(q, w, r, x) + "My"))
		}
	}
	function e(a) {
		p(),
		console.info("pointer down", a.clientX, a.clientY),
		q = a.clientX,
		r = a.clientY
	}
	function f(a) {
		console.info("pointerMove", a.clientX, a.clientY),
		w = a.clientX,
		x = a.clientY
	}
	function g(b) {
		if (null !== w && null !== x) {
			console.info("pointerUp", b.clientX, b.clientY),
			s = Math.abs(q - w),
			t = Math.abs(r - x);
			var c = a(b.target) || a(document); (s > y || t > z) && (c.trigger("swipeMy"), c.trigger("swipe" + k(q, w, r, x) + "My"))
		}
	}
	function h(a) {
		g(a)
	}
	function i() {
		var a = navigator.userAgent;
		return a.indexOf("Android") >= 0 || a.indexOf("android") >= 0
	}
	function j() {
		var a = navigator.userAgent;
		return parseFloat(a.slice(a.indexOf("Android") + 8))
	}
	function k(a, b, c, d) {
		return Math.abs(a - b) >= Math.abs(c - d) ? a - b > 0 ? "Left": "Right": c - d > 0 ? "Up": "Down"
	}
	function l(a) {
		a.preventDefault()
	}
	function m(a) {
		p(),
		u = !1,
		v = !1,
		document.addEventListener("touchmove", l, !1),
		q = a.touches[0].clientX,
		r = a.touches[0].clientY
	}
	function n(b) {
		var c = b.touches[0].clientX,
		d = b.touches[0].clientY;
		s = c - q,
		t = d - r;
		var e = a(b.target) || a(document);
		if (!u) {
			var f = Math.abs(s),
			g = Math.abs(t);
			if (f > g) {
				if (y > f) return;
				u = !0,
				e.trigger("swipeMy"),
				e.trigger("swipe" + k(q, c, r, d) + "My")
			} else if (o(b), !v) {
				if (z > g) return;
				v = !0,
				e.trigger("swipeMy"),
				e.trigger("swipe" + k(q, c, r, d) + "My")
			}
		}
	}
	function o() {
		document.removeEventListener("touchmove", l, !1)
	}
	function p() {
		q = 0,
		r = 0,
		s = 0,
		t = 0,
		u = !1,
		v = !1,
		w = null,
		x = null
	}
	var q, r, s, t, u, v, w = null,
	x = null,
	y = 10,
	z = 30;
	i() && j() < 4.1 ? (a(document).on("touchstart", m), a(document).on("touchmove", n), a(document).on("touchend", o), a(document).on("touchleave", o), a(document).on("touchcancel", o)) : window.navigator.msPointerEnabled ? (a(document).on("MSPointerDown", e), a(document).on("MSPointerMove", f), a(document).on("MSPointerUp", g), a(document).on("MSPointerCancel", h)) : (a(document).on("touchstart", b), a(document).on("touchmove", c), a(document).on("touchend", d), a(document).on("touchleave touchcancel", d)),
	["swipeMy", "swipeLeftMy", "swipeRightMy", "swipeUpMy", "swipeDownMy"].forEach(function(b) {
		a.fn[b] = function(a) {
			return this.on(b, a)
		}
	})
}),
function(a) {
	if ("function" == typeof define && define.cmd) define("app/weixinV2.1/book/pageflip", ["gallery/zepto/1.1.3/zepto", "app/weixinV2.1/book/utilities"],
	function(b) {
		var c = b("gallery/zepto/1.1.3/zepto"),
		d = b("app/weixinV2.1/book/utilities");
		a(c, d)
	});
	else {
		var b = window.jQuery ? jQuery: Zepto;
		a(b)
	}
} (function(a, b) {
	function c() {
		var a, b, c = document.createElement("div"),
		d = {
			transition: "transitionend",
			OTransition: "otransitionend",
			MozTransition: "transitionend",
			WebkitTransition: "webkitTransitionEnd"
		};
		for (a in d) if (d.hasOwnProperty(a) && c.style[a] !== b) return d[a];
		return null
	}
	function d() {
		return !! c()
	}
	var e = d(),
	f = c() || "noNativeTranstionEnd",
	g = function(c, d) {
		function g() {
			c.addClass("pageFlipWrapper"),
			B = c.children(),
			C = B.length,
			t(),
			u(),
			s(),
			v(),
			w(),
			A.dataPageUrlList && a.isArray(A.dataPageUrlList) && A.dataPageUrlList.length > 0 && h(A.dataPageUrlList),
			k(),
			r()
		}
		function h(b) {
			{
				var c = C;
				b.length
			}
			a.each(b,
			function(a, b) {
				var d = a + 1,
				e = c + d,
				f = location.origin;
				0 === b.url.indexOf("http") && -1 === b.url.indexOf(f) || (C++, i(b, e), j(b, e))
			})
		}
		function i(b, c) {
			var d = a("<div data-pageId='" + c + "' data-moduleId='" + b.module_id + "'></div>").addClass("page"),
			e = A.loadingDomString;
			d.append(e),
			D.prepend(d)
		}
		function j(d, e) {
			a.ajax({
				type: "GET",
				url: d.url,
				dataType: "text",
				context: a("body"),
				success: function(f) {
					var g = b.getDomBody(f);
					a("div[data-pageId='" + e + "']").html(g),
					c.trigger({
						type: "pageDomLoaded",
						detail: {
							url: d.url,
							module_id: d.module_id,
							pageId: e
						}
					})
				},
				error: function() {}
			})
		}
		function k() {
			if (G.on("click", n), H.on("click", m), A.touchGesture && A.touchPlugin) {
				var b = A.touchPlugin;
				switch (b) {
				case "zepto":
					var d = D.children();
					d.on("swipeLeft", m),
					d.on("swipeRight", n);
					break;
				default:
					a("div.page").on("swipeLeftMy",
					function(a) {
						return m(a),
						!1
					}),
					a("div.page").on("swipeRightMy",
					function(a) {
						return n(a),
						!1
					})
				}
			}
			D.on("transition_start",
			function(a, b) {
				var d = F;
				return "next" === b.slideType ? F++:F--,
				I.slideType = b.slideType,
				I.element = b.element,
				r(),
				e || I.element.trigger(f),
				c.trigger({
					type: "pageselected",
					detail: {
						oldPageIndex: d,
						currentPageIndex: F,
						element: I.element
					}
				}),
				!1
			}),
			D.children().on(f,
			function(b) {
				I.element && "next" === I.slideType && D.prepend(I.element);
				var c = a(b.target) || I.element;
				return c && (c.removeClass("transition").removeClass("slideRight"), c.css({
					"-webkit-transform": "",
					transform: ""
				})),
				l(),
				!1
			})
		}
		function l() {
			I = {
				slideType: null,
				element: null
			}
		}
		function m(a) {
			if (a && a.preventDefault(), F === C - 1) return ! 1;
			if (!p()) {
				if (!A.quickFlip) return ! 1;
				o()
			}
			var b = c.find('div[data-pageId="' + (F + 1) + '"]');
			return q(b, "next"),
			!1
		}
		function n(a) {
			if (a && a.preventDefault(), 0 === F) return ! 1;
			if (!p()) {
				if (!A.quickFlip) return ! 1;
				o()
			}
			var b = c.find('div[data-pageId="' + F + '"]');
			return D.append(b),
			q(b, "previous"),
			!1
		}
		function o() {
			return p() ? !1 : (I.element.trigger(f), !1)
		}
		function p() {
			return null === I.element
		}
		function q(b, c) {
			var d = a(window).width();
			"next" == c ? (b.addClass("transition"), b.css({
				"-webkit-transform": "translateX(-" + d + "px)",
				transform: "translateX(-" + d + "px)"
			}), D.trigger("transition_start", {
				slideType: c,
				element: b
			})) : (D.trigger("transition_start", {
				slideType: c,
				element: b
			}), b.css({
				"-webkit-transform": "translateX(-" + d + "px)",
				transform: "translateX(-" + d + "px)"
			}), setTimeout(function() {
				b.addClass("transition slideRight")
			},
			100))
		}
		function r() {
			0 >= F ? G.addClass("disabledButton") : G.removeClass("disabledButton"),
			F >= C - 1 ? H.addClass("disabledButton") : H.removeClass("disabledButton")
		}
		function s() {
			D = a("<div class='displayContainer'></div>"),
			c.append(D)
		}
		function t() {
			E = a("<div class='originalPagesContainer'></div>"),
			c.append(E)
		}
		function u() {
			E.append(B)
		}
		function v() {
			a.each(B,
			function(b, c) {
				var d = a(c).attr("data-pageId", b + 1).addClass("page");
				D.prepend(d)
			})
		}
		function w() {
			var b = a("<div class='pagerContainer'></div>");
			G = a("<a href='#' class='prevBtn'>Previous Button</a>"),
			H = a("<a href='#' class='nextBtn'>Next Button</a>"),
			b.append(G),
			b.append(H),
			c.append(b)
		}
		function x(b, d) {
			var e = d + 1,
			f = D.children(),
			g = y(e);
			f.sort(function(b, c) {
				var d = a.inArray(parseInt(a(b).attr("data-pageId")), g),
				e = a.inArray(parseInt(a(c).attr("data-pageId")), g);
				return d - e
			}),
			o(),
			D.append(f),
			r(),
			c.trigger({
				type: "pageselected",
				detail: {
					oldPageIndex: b,
					currentPageIndex: d,
					element: c.find("div[data-pageId='" + e + "']")
				}
			})
		}
		function y(b) {
			for (var c = [], d = C; d > 0; d--) c.push(d);
			var e = a.inArray(b, c);
			if ( - 1 === e) return c;
			var f = c.splice(e + 1);
			return c.unshift(f),
			c
		}
		this.element = c;
		var z = {
			keyboardShortCuts: !1,
			loadingDomString: "<div>Loading....</div>",
			quickFlip: !1,
			touchGesture: !1,
			touchPlugin: null,
			dataPageUrlList: null
		},
		A = a.extend({},
		z, d),
		B = null,
		C = 0,
		D = null,
		E = null,
		F = 0,
		G = null,
		H = null,
		I = {
			slideType: null,
			element: null
		};
		this.next = function() {
			m()
		},
		this.previous = function() {
			n()
		},
		this.count = function() {
			return C
		},
		this.getCurrentPage = function() {
			return F + 1
		},
		this.setCurrentPage = function(a) {
			var b = F;
			a > 0 && C >= a && a !== b + 1 && (F = a - 1, x(b, F))
		},
		g()
	};
	g.prototype.addEventListener = function(a, b) {
		return this.element.on(a, b)
	},
	g.prototype.dispatchEvent = function(a, b) {
		return this.element.trigger(a, b)
	},
	g.prototype.removeEventListener = function(a, b) {
		return this.element.off(a, b)
	},
	window.PageFlip = g
}),
function(a) {
	"function" == typeof define && define.cmd ? define("app/weixinV2.1/book/modal", ["gallery/zepto/1.1.3/zepto"],
	function(b) {
		var c = b("gallery/zepto/1.1.3/zepto");
		a(c)
	}) : a(Zepto)
} (function(a) {
	a.mobileModal = function() {
		function b() {
			if (!e) {
				e = a("<div id='" + f + "'>");
				var b = a("<div class='container'>");
				modalContent = a("<div class='content'>"),
				b.append(modalContent),
				e.append(b),
				a("body").prepend(e),
				d()
			}
		}
		function c() {
			return e.hide(),
			a("body").removeClass("modal-open"),
			!1
		}
		function d() {
			e.on("touchmove",
			function() {}),
			e.on("click",
			function() {
				return ! 1
			}),
			e.on("focus", "input, textarea",
			function() {
				e.css("position", "static")
			}),
			e.on("blur", "input, textarea",
			function() {
				e.css("position", "fixed")
			})
		}
		var e, f = "mobileModal";
		return {
			buildContainer: b,
			showModal: function(c) {
				b(),
				c && e.find(".content").html(c),
				e.show(),
				a("body").addClass("modal-open")
			},
			hideModal: c,
			getModalElement: function() {
				return e
			}
		}
	} ()
}),
function(a) {
	"function" == typeof define && define.cmd ? define("app/weixinV2.1/book/imageModal", ["gallery/zepto/1.1.3/zepto"],
	function(b) {
		var c = b("gallery/zepto/1.1.3/zepto");
		a(c)
	}) : a(Zepto)
} (function(a) {
	a.fn.imageModal = function() {
		function b() {
			a.mobileModal.buildContainer(),
			i = a.mobileModal.getModalElement()
		}
		function c(a) {
			var b = new Image,
			c = "http://cache.house.sina.com.cn/esalesleju/mobile/leju_v1/default/320_240.gif";
			b.onload = function() {
				d(a.src, a.alt)
			},
			b.onerror = function() {
				d(c, a.alt)
			},
			setTimeout(function() {
				b.src = a.src
			},
			0)
		}
		function d(b, c) {
			var d = a("<img src='" + b + "' alt='" + c + "' />");
			a.mobileModal.showModal(d),
			d.one("click", a.mobileModal.hideModal)
		}
		function e() {
			var b = a(this).attr("data-largeImgUrl") || a(this).parent().attr("href") || a(this).attr("src"),
			d = a(this).attr("data-largeImgAlt") || "image";
			return a.mobileModal.showModal(a("#loading-container").html()),
			c({
				src: b,
				alt: d
			}),
			!1
		}
		function f() {
			h.on("click", e)
		}
		function g() {
			b(),
			f()
		}
		var h = this,
		i = null;
		return g(),
		h
	}
});