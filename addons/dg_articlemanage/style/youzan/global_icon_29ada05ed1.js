define("wap/showcase/global_icon/views/class_strong", ["zenjs/events"], function (t) {
    return t.extend({
        init: function () {
            this.initialize && this.initialize.apply(this, arguments)
        }, bind: function (t, n) {
            return $.proxy(t, n)
        }
    })
}),
define("text!wap/showcase/global_icon/templates/cart.html", [], function () {
    return '<a id="global-cart" href="<%=getCartUrl() %>" class="icon hide" style="">\n    <p class="icon-img"></p>\n    <p class="icon-txt">购物车</p>\n</a>\n'
}), window.zenjs = window.zenjs || {}, function (t) {
    var n = function () {
        var t = {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;",
            "'": "&#x27;"
        }, n = ["&", "<", ">", '"', "'"], i = new RegExp("[" + n.join("") + "]", "g");
        return function (n) {
            return null == n ? "" : ("" + n).replace(i, function (n) {
                return t[n]
            })
        }
    }(), i = {evaluate: /<%([\s\S]+?)%>/g, interpolate: /<%=([\s\S]+?)%>/g, escape: /<%-([\s\S]+?)%>/g}, e = /(.)^/, s = {
        "'": "'",
        "\\": "\\",
        "\r": "r",
        "\n": "n",
        "	": "t",
        "\u2028": "u2028",
        "\u2029": "u2029"
    }, o = /\\|'|\r|\n|\t|\u2028|\u2029/g, h = function (t, h, a) {
        var r;
        a = $.extend({}, i, a);
        var c = new RegExp([(a.escape || e).source, (a.interpolate || e).source, (a.evaluate || e).source].join("|") + "|$", "g"), l = 0, d = "__p+='";
        t.replace(c, function (n, i, e, h, a) {
            return d += t.slice(l, a).replace(o, function (t) {
                return "\\" + s[t]
            }), i && (d += "'+\n((__t=(" + i + "))==null?'':escapeFunc(__t))+\n'"), e && (d += "'+\n((__t=(" + e + "))==null?'':__t)+\n'"), h && (d += "';\n" + h + "\n__p+='"), l = a + n.length, n
        }), d += "';\n", a.variable || (d = "with(obj||{}){\n" + d + "}\n"), d = "var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};\n" + d + "return __p;\n";
        try {
            r = new Function(a.variable || "obj", "escapeFunc", d)
        } catch (w) {
            throw w.source = d, w
        }
        if (h)return r(h, n);
        var u = function (t) {
            return r.call(this, t, n)
        };
        return u.source = "function(" + (a.variable || "obj") + "){\n" + d + "}", u
    };
    return window.zenjs.template = h
}(window.zenjs), define("zenjs/util/template", function () {
}), define("wap/showcase/base/batch", ["zenjs/util/ready"], function () {
    function t(t) {
        t = t || {};
        var n = t.key, a = t.url, r = t.type || "GET", c = t.para || {}, l = t.handler || h;
        n && a && (s[n] || (s[n] = {url: a, param: $.extend({}, c), type: r}, e[n] = []), o && (o = !1, i()), e[n].push(l))
    }

    function n() {
        if ($.isEmptyObject(s))return void(o = !0);
        var t = $.extend({}, s);
        s = {}, $.ajax({
            url: "/v2/batch", type: "post", dataType: "json", data: {query: t}, success: function (t) {
                var n = t.code, i = t.data || {};
                0 == n && $.each(i, function (t, n) {
                    var s = e[t] || [], o = null;
                    try {
                        o = JSON.parse(i[t])
                    } catch (h) {
                        o = {}
                    }
                    $.each(s, function (n, i) {
                        i(o, t)
                    })
                })
            }, error: function (t, n) {
            }, complete: function () {
                i()
            }
        })
    }

    function i() {
        setTimeout(n, 1e3)
    }

    var e = {}, s = {}, o = !1, h = function () {
    };
    window.zenjs.ready && window.zenjs.ready(function () {
        n()
    }), window.queryBatch = window.queryBatch || t
}), define("wap/showcase/global_icon/views/cart", ["wap/showcase/global_icon/views/class_strong", "text!wap/showcase/global_icon/templates/cart.html", "zenjs/util/template", "wap/showcase/base/batch"], function (t, n, i) {
    var e = function () {
    }, s = window.zenjs.template(n);
    return t.extend({
        initialize: function (t) {
            t = t || {};
            this.onShowIcon = t.onShowIcon || e, this.onHideIcon = t.onHideIcon || e, this.highlightIcon = t.highlightIcon || e, this.url = "/v2/trade/cart/count.json", this.cart_style = (window._global.mp_data || {}).shopping_cart_style || 0, window.eventHandler.on("cart:add", function () {
                this.$el.addClass("new"), this.highlightIcon(), this.refreshCartIcon()
            }, this), window.eventHandler.on("right_icon:icon_status", this.setIconStatus, this)
        }, render: function () {
            return window._global.hide_shopping_cart ? (this.$el = $(""), this) : (this.$el = $(s({
                getCartUrl: function () {
                    return window._global.url.wap + "/trade/cart?kdt_id=" + window._global.kdt_id
                }
            })), window._global.have_goods ? (window.queryBatch && window.queryBatch({
                key: "global_cart",
                url: this.url,
                para: {kdt_id: window._global.kdt_id},
                handler: this.bind(this.refreshCartHandler, this)
            }), this) : this)
        }, refreshCartHandler: function (t) {
            var n = t.code, i = t.data || {};
            if (0 == n) {
                var e = this.count = i.count;
                e > 0 ? (this.onShowIcon({type: "cart"}), this.$el.removeClass("hide")) : (this.onHideIcon({type: "cart"}), this.$el.addClass("hide"))
            }
        }, refreshCartIcon: function () {
            !!$ && $.ajax({
                url: this.url,
                type: "GET",
                timeout: 5e3,
                dataType: "json",
                data: {kdt_id: window._global.kdt_id},
                success: this.bind(this.refreshCartHandler, this)
            })
        }, setIconStatus: function (t) {
            2 > t ? this.$el.addClass("s" + this.cart_style) : this.$el.removeClass("s" + this.cart_style)
        }
    })
}), define("wap/components/tosser", [], function () {
    function t(t, n, i) {
        this.from = t, this.to = n, this.running = !1, this.option = $.extend({
            offsetX: 0, offsetY: 0, onFinish: function () {
            }
        }, i), this.init()
    }

    var n = function () {
        return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || function (t) {
                return window.setTimeout(t, 1e3 / 60)
            }
    }();
    return t.prototype = {
        getPositionInScreen: function (t) {
            var n = t.offset();
            return {x: n.left + this.option.offsetX, y: n.top + this.option.offsetY}
        }, init: function () {
            var t = this.getPositionInScreen(this.from), n = this.getPositionInScreen(this.to), i = $("<div>").css({
                position: "absolute",
                left: t.x,
                top: t.y,
                display: "none"
            }).addClass(this.option.klass).appendTo(document.body);
            this.fromPt = t, this.toPt = n, this.heart = i
        }, startAnm: function () {
            function t() {
                if (c.running) {
                    if (e = (new Date - i) / f, 0 == e)return void n(t);
                    o = 100 * e + 200 * e * e, r = o / d, s = u * (1 * r - r * r) + w * r, a = -360 * e, h = "translate3d(" + s + "px," + o + "px, 0) rotate(" + a + "deg)", l.style.transform = h, l.style.webkitTransform = h, o > d && c.destroy(), n(t)
                }
            }

            if (!this.running) {
                this.running = !0;
                var i, e, s, o, h, a, r, c = this, l = this.heart[0], d = this.toPt.y - this.fromPt.y, w = this.toPt.x - this.fromPt.x, u = 150 > d ? 0 : -120, f = 150 > d ? 1500 : 1e3;
                i = new Date, l.style.display = "block", t()
            }
        }, destroy: function () {
            this.option.onFinish(), this.heart.remove(), this.running = !1
        }
    }, t
}), define("text!wap/showcase/global_icon/templates/wish.html", [], function () {
    return '<a id="global-wish" href="<%= window._global.wishUrl %>" class="icon hide">\n    <p class="icon-img"></p>\n    <p class="icon-txt">心愿单</p>\n</a>'
}), define("wap/showcase/global_icon/views/wish", ["wap/showcase/global_icon/views/class_strong", "wap/components/tosser", "text!wap/showcase/global_icon/templates/wish.html", "zenjs/util/template", "wap/showcase/base/batch"], function (t, n, i, e) {
    var s = function () {
    }, o = window.zenjs.template(i);
    return t.extend({
        initialize: function (t) {
            t = t || {}, this.onShowIcon = t.onShowIcon || s, this.onHideIcon = t.onHideIcon || s, this.highlightIcon = t.highlightIcon || s, this.url = "/v2/trade/wish/IsShowWishIcon.json?kdt_id=" + window._global.kdt_id, this.postData = {}
        }, setEventListener: function () {
            var t = this, i = !1;
            window.eventHandler.on("wish:add", function () {
                t.refreshWishHandler({code: 0, msg: "success", data: {wishGoodsTotal: 1, isHighlight: 1}}), t.refreshWishIcon(), i = !0
            }), window.eventHandler.on("wishScrollEnd", function () {
                if (i) {
                    var t = new n($(".js-wish-animate"), $(".js-right-icon"), {
                        offsetX: 5, offsetY: 8, klass: "wish-add-drop", onFinish: function () {
                            motify.log("添加成功")
                        }
                    });
                    t.startAnm(), i = !1
                }
            })
        }, render: function () {
            return window._global.isWishOpen ? (this.$el = $(o()), this.setEventListener(), window._global.have_goods ? (window.queryBatch && window.queryBatch({
                key: "global_wish",
                url: this.url,
                type: "POST",
                para: this.postData,
                handler: this.bind(this.refreshWishHandler, this)
            }), this) : this) : (this.$el = $(""), this)
        }, refreshWishIcon: function () {
            !!$ && $.ajax({url: this.url, type: "POST", timeout: 5e3, dataType: "json", data: this.postData, success: this.bind(this.refreshWishHandler, this)})
        }, refreshWishHandler: function (t) {
            var n = t.code, i = t.data;
            0 == n && (i.wishGoodsTotal > 0 ? (this.onShowIcon({type: "wish"}), this.$el.removeClass("hide")) : (this.onHideIcon({type: "wish"}), this.$el.addClass("hide")), i.isHighlight && (this.$el.addClass("new"), this.highlightIcon()))
        }
    })
}), define("text!wap/showcase/global_icon/templates/container.html", [], function () {
    return '<div id="right-icon" class="js-right-icon hide">\n	<div class="js-right-icon-container right-icon-container clearfix">\n		<a class=\'js-show-more-btn icon show-more-btn hide\'></a>\n	</div>\n</div>'
}), require(["wap/showcase/global_icon/views/cart", "wap/showcase/global_icon/views/wish", "wap/showcase/global_icon/views/class_strong", "zenjs/events", "zenjs/util/template", "text!wap/showcase/global_icon/templates/container.html"], function (t, n, i, e, s, o) {
    window.eventHandler = window.eventHandler || new e;
    var h = window.zenjs.template(o), a = i.extend({
        initialize: function () {
            this.showList = [], this.$el = $("body"), this.el = this.$el[0], this.cartView = new t({
                onShowIcon: this.bind(this.showIcon, this),
                onHideIcon: this.bind(this.hideIcon, this),
                highlightIcon: this.bind(this.highlightIcon, this)
            }), this.wishView = new n({
                onShowIcon: this.bind(this.showIcon, this),
                onHideIcon: this.bind(this.hideIcon, this),
                highlightIcon: this.bind(this.highlightIcon, this)
            })
        }, setEventListener: function () {
            var t = this;
            0 != t.nRightIcon.length && (this.$el.on("click", ".js-show-more-btn", function (n) {
                t.nRightIcon.hasClass("show") ? t.nRightIcon.removeClass("show").removeAttr("style") : t.nRightIcon.addClass("show").css("width", 50 * t.count)
            }), this.$el.on("click", function (n) {
                n.target == t.nMoreIconBtn[0] || $.contains(t.nRightIcon[0], n.target) || t.nRightIcon.removeClass("show").removeAttr("style")
            }), $(window).on("scroll", function (n) {
                t.nRightIcon.removeClass("show").removeAttr("style")
            }), 0 == this.nMoreIconBtn.length)
        }, render: function () {
            return this.$el.append(h()), this.nRightIcon = this.$el.find(".js-right-icon"), this.nIconContainer = this.$el.find(".js-right-icon-container"), this.nMoreIconBtn = this.nRightIcon.find(".js-show-more-btn"), this.wishView.render(), this.nIconContainer.prepend(this.wishView.$el), this.cartView.render(), this.nIconContainer.prepend(this.cartView.$el), this.setEventListener(), this
        }, showIcon: function (t) {
            t.type && (this.showList.indexOf(t.type) > -1 || (this.showList.push(t.type), this.refreshIconContainer()))
        }, hideIcon: function (t) {
            if (t.type) {
                var n = this.showList.indexOf(t.type);
                0 > n || (this.showList.splice(n, 1), this.refreshIconContainer())
            }
        }, refreshIconContainer: function () {
            var t = this.showList.length;
            t > 0 ? this.nRightIcon.removeClass("hide") : this.nRightIcon.addClass("hide"), t > 1 ? (this.nRightIcon.removeClass("no-text"), this.nMoreIconBtn.removeClass("hide")) : (this.nRightIcon.addClass("no-text"), this.nMoreIconBtn.addClass("hide")), window.eventHandler.trigger("right_icon:icon_status", t), this.count = t > 1 ? t + 1 : 1, this.nIconContainer.width(50 * this.count)
        }, highlightIcon: function () {
            this.nMoreIconBtn.addClass("new")
        }
    });
    window.global_icon = (new a).render()
}), define("main", function () {
});