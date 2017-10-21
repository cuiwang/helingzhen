define("wap/components/wx_image_preview", [], function () {
    function e(e) {
        var n = e.attr("data-src") || e.attr("src"), a = n.replace(/!.*?\.jpg/i, "!640x320.jpg");
        return a
    }

    function n(e) {
        var n, a = e.closest("a");
        return a.length && (n = a.attr("href"), n && /[http|https|tel|mailto]:/i.test(n)) ? !0 : !1
    }

    function a() {
        var n = e($(this));
        o(n, [n])
    }

    var t = [], o = function (e, n) {
        window.WeixinJSBridge && window.WeixinJSBridge.invoke("imagePreview", {current: e, urls: n})
    }, i = {
        init: function () {
            var i = $(".js-view-image"), s = 0;
            i.each(function () {
                var a = $(this), i = e(a);
                n(a) || a.width() >= s && i && (t.push(i), a.on("click", function () {
                    o(i, t)
                }))
            }), $(".js-view-image-list").each(function (a) {
                var t = $(this);
                t.on("click", ".js-view-image-item", function (a) {
                    var i = t.find(".js-view-image-item");
                    if (!n($(a.target))) {
                        i = i.map(function () {
                            var n = $(this);
                            return e(n)
                        }).toArray();
                        var s = e($(this));
                        o(s, i)
                    }
                })
            }), $(document.body).off("click", ".js-view-single-image", a).on("click", ".js-view-single-image", a)
        }, clear: function () {
            t = []
        }
    };
    return window.imagePreview = i, i
}), function () {
    function e() {
        clearTimeout(o), n.addClass("done")
    }

    var n = $(".js-tpl-weixin-list-item"), a = $(".js-tpl-weixin-bg");
    if (!(a.length <= 0)) {
        var t = a[0], o = setTimeout(function () {
            e()
        }, 2e3);
        t.onload = t.onerror = t.onabort = e, t.complete && e()
    }
}(), function () {
    var e = $(".js-tpl-shop"), n = "/v2/showcase/homepage/goodscount.json";
    e.length && $.ajax({Òurl: n, type: "GET", dataType: "json", data: {kdt_id: window._global.kdt_id}}).done(function (n) {
        if (0 === +n.code) {
            var a = e.find(".js-all-goods"), t = e.find(".js-new-goods"), o = e.find(".js-order"), i = n.data, s = "";
            s = (i.all_goods.count + "").length, a.find("a").attr("href", i.all_goods.url), a.find(".count").html(i.all_goods.count).addClass("l-" + s), s = (i.new_goods.count + "").length, t.find("a").attr("href", i.new_goods.url), t.find(".count").html(i.new_goods.count).addClass("l-" + s), o.find("a").attr("href", i.order.url)
        }
    })
}(), function () {
    $(".js-select-coupon").on("click", function () {
        var e = $(this), n = window.motify;
        $.ajax({url: "/v2/ump/promocard/fetchalias.json", type: "POST", data: {kdt_id: e.data("kdt-id"), id: e.data("id")}}).done(function (e) {
            0 === +e.code ? window.location.href = e.data.url : n.log(e.msg || "网络错误")
        }).fail(function () {
            n.log("网络错误")
        })
    })
}(), window.init_custom_notice = function (e) {
    var n = $(".js-scroll-notice", $(e || "body"));
    n.length && n.each(function () {
        function e() {
            i--, 0 > i + t && (i = o), n.css({left: i})
        }

        var n = $(this), a = n.parents(".custom-notice-inner"), t = n.width(), o = a.width(), i = 0;
        o >= t || (n.css({position: "relative"}), setInterval(e, 25))
    })
}, window.init_custom_notice(), define("wap/showcase/homepage/homepage", function () {
}), function () {
    var e = $(".js-custom-level"), n = $(".js-custom-point"), a = $(".js-custom-level-title-section");
    if (!(+_global.fans_id <= 0 && +_global.buyer_id <= 0)) {
        var t = window._global.url.wap + "/showcase/component/point.jsonp?" + $.param({kdt_id: window._global.kdt_id});
        (e.length > 0 || n.length > 0) && $.ajax({
            dataType: "jsonp", type: "GET", url: t, success: function (t) {
                0 === +t.code && (e.html(t.data.level || "会员"), n.html(t.data.point || "暂无数据"), a.removeClass("hide"))
            }
        })
    }
}(), define("wap/uc/title", function () {
}), define("wap/showcase/base/batch", ["zenjs/util/ready"], function () {
    function e(e) {
        e = e || {};
        var n = e.key, r = e.url, c = e.type || "GET", l = e.para || {}, d = e.handler || s;
        n && r && (o[n] || (o[n] = {url: r, param: $.extend({}, l), type: c}, t[n] = []), i && (i = !1, a()), t[n].push(d))
    }

    function n() {
        if ($.isEmptyObject(o))return void(i = !0);
        var e = $.extend({}, o);
        o = {}, $.ajax({
            url: "/v2/batch", type: "post", dataType: "json", data: {query: e}, success: function (e) {
                var n = e.code, a = e.data || {};
                0 == n && $.each(a, function (e, n) {
                    var o = t[e] || [], i = null;
                    try {
                        i = JSON.parse(a[e])
                    } catch (s) {
                        i = {}
                    }
                    $.each(o, function (n, a) {
                        a(i, e)
                    })
                })
            }, error: function (e, n) {
            }, complete: function () {
                a()
            }
        })
    }

    function a() {
        setTimeout(n, 1e3)
    }

    var t = {}, o = {}, i = !1, s = function () {
    };
    window.zenjs.ready && window.zenjs.ready(function () {
        n()
    }), window.queryBatch = window.queryBatch || e
}), define("wap/showcase/shop_nav/main", ["vendor/zepto/outer", "wap/showcase/base/batch"], function (e, n) {
    var a = $("#shop-nav");
    if (a.length && window._global.showcase_type) {
        a.hide();
        var t = $(), o = t.find(".js-nav-pop"), i = function (e) {
            var n = $(e.target), a = n.parents(".nav-item"), t = n.hasClass(".js-mainmenu") ? n : a.find(".js-mainmenu"), o = a.find(".js-submenu"), i = o.find(".arrow"), s = n.parents(".js-navmenu"), r = s.find(".nav-item");
            o.css("opacity", "0").toggle();
            var c = r.length, l = r.index(a), d = t.outerWidth(), u = (o.outerWidth() - t.outerWidth()) / 2, h = o.outerWidth() / 2;
            if (0 === o.size())$(".js-submenu:visible").hide(); else {
                var f = o.outerWidth(), p = a.outerWidth(), m = "auto", v = "auto", w = "auto", g = "auto";
                0 === l ? (m = t.position().left - u, v = h - i.outerWidth() / 2) : l === c - 1 && f > p ? (w = 8, g = d / 2 - w) : (m = t.position().left - u, v = h - i.outerWidth() / 2);
                var y = 5;
                0 > m && (v = v + m + y, m = y), 0 > w && (g = g + w + y, w = y), o.css({left: m, right: w}), i.css({
                    left: v,
                    right: g
                }), $(".js-submenu:visible").not(o).hide(), o.css("opacity", "1")
            }
        };
        $(document).on("click", function (e) {
            t[0] && (e.target == t[0] || $.contains(t[0], e.target) || ($(".js-submenu:visible").hide(0), t.hasClass("nav-show") && (t.removeClass("nav-show").addClass("nav-hide"), setTimeout(function () {
                o.hide(0)
            }, 500))))
        }), $("body").on("click", ".js-navmenu", function (e) {
            var n = $(e.target);
            e.fromMenu = !0, window.Logger && Logger.log({fm: "click", title: n.prop("title") || n.text()})
        }), $("body").on("click", ".js-submenu", function (e) {
            var n = $(e.target);
            e.fromMenu = !0, window.Logger && Logger.log({fm: "click", title: n.prop("title") || n.text()}), e.stopPropagation()
        }), $("body").on("click", ".js-mainmenu", function (e) {
            i(e)
        });
        var s;
        $(window).on("scroll", function (e) {
            e.preventDefault(), t[0] && t.hasClass("nav-show") && (t.removeClass("nav-show").addClass("nav-hide"), setTimeout(function () {
                o.hide(0)
            }, 500))
        }), $("body").on("click", ".js-nav-special", function (e) {
            $(e.target);
            t[0] && "animation" != t.data("animation") && (t.data("animation", "animation"), t.hasClass("nav-show") ? (t.removeClass("nav-show").addClass("nav-hide"), s = setTimeout(function () {
                o.css("display", "none"), t.data("animation", "")
            }, 600)) : (o.css("display", "block"), t.addClass("nav-show").removeClass("nav-hide"), setTimeout(function () {
                t.data("animation", "")
            }, 600)))
        }), window.queryBatch && window.queryBatch({
            key: "shop_nav",
            url: "/v2/showcase/shopnav/nav.json?kdt_id=" + window._global.kdt_id,
            para: {
                showcase_type: window._global.showcase_type,
                url: window.location.host + window.location.pathname,
                css: "version_css.stylesheets/wap/pages/showcase/shopnav_custom/shopnav_custom"
            },
            handler: function (e) {
                if (0 == e.code) {
                    var n = e.data || {}, i = n.html || "", s = n.css || "";
                    window.loader.load([s], function () {
                        a.show()
                    }), a.html(i), t = a.find(".js-navmenu"), o = t.find(".js-nav-pop");
                    var r = t.data("type");
                    4 != r && $("body").addClass("body-fixed-bottom")
                }
            }
        })
    }
}), window.Zepto && function (e) {
    e.fn.serializeArray = function () {
        var n, a, t = [], o = function (e) {
            return e.forEach ? e.forEach(o) : void t.push({name: n, value: e})
        };
        return this[0] && e.each(this[0].elements, function (t, i) {
            a = i.type, n = i.name, n && "fieldset" != i.nodeName.toLowerCase() && !i.disabled && "submit" != a && "reset" != a && "button" != a && "file" != a && ("radio" != a && "checkbox" != a || i.checked) && o(e(i).val())
        }), t
    }, e.fn.serialize = function () {
        var e = [];
        return this.serializeArray().forEach(function (n) {
            e.push(encodeURIComponent(n.name) + "=" + encodeURIComponent(n.value))
        }), e.join("&")
    }, e.fn.submit = function (n) {
        if (0 in arguments)this.bind("submit", n); else if (this.length) {
            var a = e.Event("submit");
            this.eq(0).trigger(a), a.isDefaultPrevented() || this.get(0).submit()
        }
        return this
    }
}(Zepto), define("vendor/zepto/form", function () {
}), define("wap/showcase/search_bar/main", ["vendor/zepto/form"], function () {
    $.fn.searchBar = function () {
        var e = $.fn.searchBar.container;
        return e || (e = $.fn.searchBar.init()), this.each(function () {
            $(this).on("click", function () {
                e.css("display", "block"), e.find(".search-input").focus()
            })
        })
    }, $.fn.searchBar.keywords = function (e, n) {
        $.ajax({
            url: "/v2/showcase/goods/searchSuggest.json",
            type: "GET",
            dataType: "json",
            timeout: 5e3,
            data: {q: e, kdt_id: window._global.kdt_id},
            success: function (e) {
                var a = e.code, t = e.data;
                if (0 === a) {
                    var o = "";
                    keys = t.tips, keysLen = keys.length, $(".js-tag-clear").addClass("hide"), keysLen > 0 && ($.each(keys, function (e, n) {
                        o += "<li><p>" + n + "</p></li>"
                    }), keysLen % 2 && (o += "<li><p>&nbsp;</p></li>"), n.html(o).addClass("search-recom-list"))
                }
            },
            error: function (e, n, a) {
            }
        })
    }, $.fn.searchBar.init = function () {
        var e, n = window.localStorage, a = $('<div class="search-container" style="display:none;"></div>'), t = $(['<form class="search-form" action="/v2/search" method="GET">', '<input type="search" class="search-input" placeholder="搜索本店所有商品" name="q" value="">', '<input type="hidden" name="kdt_id" value="' + window._global.kdt_id + '">', '<a class="js-search-cancel search-cancel" href="javascript:;">取消</a>', '<span class="search-icon"></span>', '<span class="close-icon hide"></span>', "</form>"].join("")), o = $('<div class="history-wrap center"></div>'), i = t.find(".js-search-cancel"), s = $('<ul class="history-list search-recom-list js-history-list clearfix"></ul>'), r = $('<a class="tag tag-clear js-tag-clear c-gray-darker hide" href="javascript:;">清除历史搜索</a>'), c = t.find(".search-input"), l = t.find(".close-icon"), d = "";
        return n && (e = (JSON.parse(n.getItem("searchhistory")) || {}).result, e && ($.each(e, function (e, n) {
            d += "<li>" + n + "</li>"
        }), s.append(d).removeClass("search-recom-list"), r.removeClass("hide"))), o.append(s).append(r), a.append(t).append(o), $("body").append(a), $.fn.searchBar.container = a, t.on("submit", function () {
            var a = $.trim(c.val());
            n && a && (e = e || [], e = $.grep(e, function (e) {
                return e != a
            }), e.unshift(a), n.setItem("searchhistory", JSON.stringify({result: e})))
        }).on("input", function () {
            var e = $.trim(c.val());
            "" !== e ? (l.removeClass("hide"), $.fn.searchBar.keywords(e, s)) : (s.html(d).removeClass("search-recom-list"), o.removeClass("hide"), l.addClass("hide"), r.removeClass("hide"))
        }), l.on("click", function () {
            c.val(""), l.addClass("hide")
        }), i.on("click", function () {
            a.css("display", "none")
        }), s.on("click", "li", function (e) {
            c.val($(e.currentTarget).text()), t.submit()
        }), r.on("click", function () {
            n && (n.removeItem("searchhistory"), e = null), o.html("")
        }), a
    }
}), require(["wap/components/wx_image_preview", "vendor/zepto/outer", "wap/showcase/homepage/homepage", "wap/uc/title", "wap/showcase/shop_nav/main", "wap/showcase/base/batch", "wap/showcase/search_bar/main"], function (e) {
    _global.spm && "h" === _global.spm.logType && _global.spm.logType2 && onReady && onReady("Logger", function () {
        window.Logger && Logger.log({spm: _global.spm.logType2 + _global.spm.logId2, fm: "display"})
    }), $(".js-search").searchBar(), _global.goods_id || e.init(), function () {
        var e = [], n = $.map(window.showcase_js_map || [], function (n) {
            return n.deps && n.deps.length > 0 ? void e.push(n.url) : n.url
        });
        window.loader.load(n || [], function () {
            0 !== e.length && window.loader.load(e)
        })
    }()
}), define("main", function () {
});