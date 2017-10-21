!
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : a.TmplInline_actionsheet = b()
} (this,
function() {
    var a = {},
    b = function(a) {
        function b(b) {
            return c("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function c(a) {
            return "undefined" == typeof a ? "": a
        }
        a = a || {};
        var d = b("title"),
        e = b("items"),
        f = "";
        f += " ",
        d && (f += ' <div class="sheet-item sheet-title">', f += c(d), f += "</div> "),
        f += " ";
        for (var g = 0; g < e.length; g++) f += ' <div class="sheet-item ',
        f += c(e[g].type || ""),
        f += '" value="',
        f += c(g),
        f += '"> <div class="sheet-item-text" >',
        f += c(e[g].text || e[g]),
        f += "</div> </div> ";
        return f += ' <div class="sheet-item" value="-1" > <div class="sheet-item-text">取消</div> </div> '
    };
    return a.frame = "TmplInline_actionsheet.frame",
    Tmpl.addTmpl(a.frame, b),
    a
}),
function(a, b) {
    a.ActionSheet = b(a.$, a.Tmpl)
} (this,
function(a, b) {
    function c() {
        i = a('<div class="action-sheet"></div>'),
        h = a('<div class="action-sheet-mask"></div>'),
        a(document.body).append(i).append(h),
        h.on("tap",
        function() {
            f()
        }).on("webkitTransitionEnd",
        function() {
            h.hasClass("show") || h.hide()
        }),
        i.on("tap", ".sheet-item", e).on("webkitTransitionEnd",
        function() {
            i.hasClass("show") || (h.removeClass("show"), i.hide())
        }),
        k = !0
    }
    function d(a) {
        mqq.compare("4.7") >= 0 && !a.useH5 ? mqq.ui.showActionSheet({
            items: a.items,
            cancel: "取消"
        },
        function(b, c) {
            0 === b ? a.onItemClick && a.onItemClick(c) : a.onCancel && a.onCancel(c)
        }) : (k || c(), j = a, document.body.style.overflow = "hidden", i.html(""), l.on("touchmove", g), b(window.TmplInline_actionsheet.frame, a).appendTo(i), h.show(), i.show(), setTimeout(function() {
            h.addClass("show"),
            i.addClass("show")
        },
        50))
    }
    function e() {
        var b = a(this).attr("value");
        b = Number(b),
        -1 === b ? j.onCancel && j.onCancel() : j.onItemClick && j.onItemClick(b),
        f()
    }
    function f(a) {
        i && (a ? (h.hide().removeClass("show"), i.hide().removeClass("show")) : i.removeClass("show"), l.off("touchmove", g), document.body.style.overflow = "")
    }
    function g(a) {
        a.preventDefault()
    }
    var h, i, j, k, l = a(document);
    return {
        show: d,
        hide: f
    }
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : a.TmplInline_checkcode = b()
} (this,
function() {
    var a = {},
    b = function(a) {
        a = a || {};
        var b = "";
        return b += '<div class="checkcode-content"> <div class="close-checkcode"></div> <h3 class="content-title">请输入验证码</h3> <div class="content-middle"> <img class="content-img" src=""> <input class="content-input" id="inputCheckcode" placeholder="验证码" type="email" maxlength="6"/> <a class="content-button" href="javascript:;" id="submitCheckcode">确定</a> </div> </div> '
    };
    return a.frame = "TmplInline_checkcode.frame",
    Tmpl.addTmpl(a.frame, b),
    a
}),
function(a, b) {
    a.Checkcode = b(a.$, a.Tmpl)
} (this,
function(a, b) {
    function c() {
        i = a('<div class="checkcode"></div>'),
        a(document.body).append(i),
        i.on("tap",
        function(a) {
            a.target === i[0] && g(),
            "close-checkcode" === a.target.className && g()
        }),
        j = !0
    }
    function d() {
        i.find(".content-img").attr("src", "http://captcha.qq.com/getimage?aid=716013036&v=" + Math.random())
    }
    function e(e, f, g) {
        j || c(),
        Q.monitor(594773),
        i.html(""),
        a(document).on("touchmove", h),
        b(window.TmplInline_checkcode.frame, {}).appendTo(i),
        setTimeout(function() {
            i.find("#inputCheckcode").focus()
        },
        500),
        i.show(),
        d(),
        i.find(".content-button").on("tap",
        function() {
            l(e, i.find("#inputCheckcode").val(), f, g)
        }),
        i.find(".content-img").on("tap",
        function() {
            d()
        })
    }
    function f() {
        Alert.show("", "验证码输入错误，请重新输入", {
            confirm: "重新输入",
            cancel: "取消",
            callback: function() {
                i.find("#inputCheckcode").focus(),
                i.find("#inputCheckcode").val(""),
                d()
            }
        }),
        i.siblings(".alert").find(".frame").css("top", "120px").css("position", "fixed")
    }
    function g() {
        i.hide(),
        i.find("#inputCheckcode").blur(),
        a(document).off("touchmove", h)
    }
    function h(a) {
        a.preventDefault()
    }
    var i, j = !1,
    k = {
        festival_verify: "/cgi-bin/bar/cwact/post/captcha/verify_v2",
        verify_v2: "/cgi-bin/bar/post/captcha/verify_v2",
        verify: "/cgi-bin/bar/post/captcha/verify",
        report: "/cgi-bin/bar/admin/jubao"
    },
    l = function(b, c, d, e) {
        var h = {
            type: "POST",
            url: k[b],
            param: {
                vcode: c
            },
            succ: function(a) {
                0 === a.retcode ? (g(), d && d(a.result)) : f()
            },
            err: function() {
                f()
            }
        };
        "report" === b && (h.ssoCmd = "jubao"),
        a.extend(h.param, e),
        console.log("jubao cgi http:", h),
        DB.cgiHttp(h)
    };
    return {
        show: e
    }
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : a.TmplInline_publish = b()
} (this,
function() {
    var a = {},
    b = function(a) {
        function b(b) {
            return c("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function c(a) {
            return "undefined" == typeof a ? "": a
        }
        a = a || {};
        var d = b("len"),
        e = b("list"),
        f = "";
        f += '<div class="pub-cates"> <div class="close-button"></div> ';
        for (var g = 0,
        d = e.length; d > g; g++) f += ' <div class="pub-cate-item" value="',
        f += c(e[g].value),
        f += '"><span class="pub-cate-text">',
        f += c(e[g].text),
        f += "</span></div> ";
        return f += " </div>"
    };
    a.category = "TmplInline_publish.category",
    Tmpl.addTmpl(a.category, b);
    var c = function(a) {
        function b(b) {
            return c("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function c(a) {
            return "undefined" == typeof a ? "": a
        }
        a = a || {};
        var d = b("i"),
        e = b("j"),
        f = "";
        f += '<div class="ui-carousel js-slide" data-ride="carousel"> ';
        var d, e, g = 0;
        for (f += ' <ul class="ui-carousel-inner face-panel-wrap"> ', d = 1; 5 >= d; d++) {
            for (f += ' <li class="ui-carousel-item face-panel face-panel-', f += c(d), f += " ", f += c(1 === d ? "js-active": ""), f += '" > ', e = 0; 21 > e; e++) f += ' <span index="',
            f += c(20 != e ? g++:-1),
            f += '"></span> ';
            f += " </li> "
        }
        for (f += ' </ul> <ol class="ui-carousel-indicators"> ', d = 1; 5 >= d; d++) f += ' <li class="',
        f += c(1 === d ? "js-active": ""),
        f += '"></li> ';
        return f += " </ol> </div>"
    };
    a.face_panel = "TmplInline_publish.face_panel",
    Tmpl.addTmpl(a.face_panel, c);
    var d = function(a) {
        a = a || {};
        var b = "";
        return b += '<div class="clip"><img src="" style=""/></div> <div class="up-mask"></div> <div class="up-progress"> <div class="pos"></div> </div> <a class="btn-del" href="javascript:void(0)" title="关闭">&nbsp;</a> '
    };
    a.preview = "TmplInline_publish.preview",
    Tmpl.addTmpl(a.preview, d);
    var e = function(a) {
        function b(b) {
            return c("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function c(a) {
            return "undefined" == typeof a ? "": a
        }
        a = a || {};
        var d = b("replyMode"),
        e = b("config"),
        f = b("contentPlaceholder"),
        g = "";
        return g += ' <div class="pub-con"> <div class="pub-wrap section-1px ',
        g += c(d ? "edit-mode": ""),
        g += '"> <div class="pub-theme section-1px ',
        g += c(e.needTitle ? "": "hide"),
        g += '"> <input maxlength="200" spellcheck="false" class="ipt-theme" type="text" placeholder="标题，',
        g += c(e.minTitleLength),
        g += "-",
        g += c(e.maxTitleLength),
        g += '个字"/> </div> <div class="editor-outer"> <textarea spellcheck="false" class="editor" placeholder="',
        g += c(f),
        g += '"></textarea> <textarea class="cp-editor"></textarea> </div> ',
        e.needLocation && (g += ' <div class="pub-location text-overflow-ellipsis"> <span class="location-text">所在城市</span> </div> '),
        g += ' <div class="pub-line border-1px"></div> <ul class="pub-type"> ',
        e.needCategory && (g += ' <div class="pub-cate"> <span class="icon"></span><span class="cag-text">分类</span> </div> '),
        g += " ",
        e.needPhoto && (g += ' <li id="selectPic" class="pic-type" title="添加图片"></li> '),
        g += " ",
        e.needFace && (g += ' <li id="selectFace" class="pub-face" title="添加表情"></li> '),
        g += ' <li class="pub-flex"></li> ',
        e.needCancelBtn && (g += ' <li class="pub-btn "> <button class="pub-cancel">取消</button> </li> '),
        g += ' <li class="pub-btn "> <button class="pub-publish">发表</button> </li> <li class="loading "></li> <li class="pub-remain "> <p class="pub-remain-wording" style="display:none"></p> </li> </ul> ',
        e.needPhoto && (g += ' <ul class="pub-pics" > <li class="up-entry"> <input class="upfile up-entry-two" type="file" accept="image/*" multiple/> </li> </ul> '),
        g += " ",
        e.needFace && (g += ' <div class="pub-faces" > </div> '),
        g += " </div> </div> "
    };
    return a.publish = "TmplInline_publish.publish",
    Tmpl.addTmpl(a.publish, e),
    a
}),
function() {
    function a(a, b) {
        this.$element = $(a),
        this.$indicators = this.$element.find(".ui-carousel-indicators"),
        this.options = b,
        this.containerWidth = b.containerWidth || this.$element.width() || window.innerWidth,
        this.paused = this.sliding = this.interval = this.$active = this.$items = null,
        this.swipeable()
    }
    var b = "js-active",
    c = "slid:carousel",
    d = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame;
    a.DEFAULTS = {
        interval: 5e3,
        wrap: !0
    },
    a.prototype.cycle = function(a) {
        return a || (this.paused = !1),
        this.interval && clearInterval(this.interval),
        this.options.interval && !this.paused && (this.interval = setInterval($.proxy(this.next, this), this.options.interval)),
        this
    },
    a.prototype.getActiveIndex = function() {
        return this.$active = this.$element.find(".ui-carousel-item." + b),
        this.$items = this.$active.parent().children(),
        this.$items.index(this.$active)
    },
    a.prototype.to = function(a) {
        var b = this,
        d = this.getActiveIndex();
        return a > this.$items.length - 1 || 0 > a ? void 0 : this.sliding ? this.$element.one(c,
        function() {
            b.to(a)
        }) : d == a ? this.pause().cycle() : this.slide(a > d ? "next": "prev", $(this.$items[a]))
    },
    a.prototype.pause = function(a) {
        return a || (this.paused = !0),
        this.$element.find(".js-next, .js-prev").length && $.support.transition && (this.$element.trigger($.support.transition.end), this.cycle(!0)),
        this.interval = clearInterval(this.interval),
        this
    },
    a.prototype.next = function() {
        return this.sliding ? void 0 : this.slide("next")
    },
    a.prototype.prev = function() {
        return this.sliding ? void 0 : this.slide("prev")
    },
    a.prototype.slide = function(a, d) {
        var e = this.$element.find(".ui-carousel-item." + b),
        f = d || e[a](),
        g = "next" == a ? "left": "right",
        h = "next" == a ? "first": "last",
        i = this;
        if (!f.length) {
            if (!this.options.wrap) return;
            f = this.$element.find(".ui-carousel-item")[h]()
        }
        if (f.hasClass(b)) return this.sliding = !1;
        var j = $.Event(c, {
            relatedTarget: f[0],
            direction: g
        });
        if (this.$element.trigger(j), !j.isDefaultPrevented()) {
            if (this.sliding = !0, this.pause(), this.$indicators.length && (this.$indicators.find("." + b).removeClass(b), this.$element.one(c,
            function() {
                var a = $(i.$indicators.children()[i.getActiveIndex()]);
                a && a.addClass(b)
            })), $.support.transition && this.$element.hasClass("js-slide") && !this.swiping) {
                var k = "js-" + g,
                l = "js-" + a;
                f.addClass(l),
                f[0].offsetWidth,
                e.addClass(k),
                f.addClass(k),
                e.one($.support.transition.end,
                function() {
                    f.removeClass([l, k].join(" ")).addClass(b),
                    e.removeClass([b, k].join(" ")),
                    i.sliding = !1,
                    setTimeout(function() {
                        i.$element.trigger(c)
                    },
                    0)
                }).emulateTransitionEnd(1e3 * e.css("transition-duration").slice(0, -1))
            } else e.removeClass(b),
            f.addClass(b),
            this.sliding = !1,
            this.$element.trigger(c);
            this.cycle(),
            this.$center = f;
            var m = function() {
                var b = f[a]();
                if (!b.length) {
                    if (!i.options.wrap) return;
                    b = i.$element.find(".ui-carousel-item")[h]()
                }
                return b
            } ();
            return this.$left = "next" == a ? e: m,
            this.$right = "next" == a ? m: e,
            this
        }
    },
    a.prototype.swipeable = function() {
        function a(a) {
            return a.targetTouches && a.targetTouches.length >= 1 ? a.targetTouches[0].clientX: a.clientX
        }
        function c() {
            var a, b, c, d;
            a = Date.now(),
            b = a - m,
            m = a,
            c = o - l,
            l = o,
            d = 800 * c / (1 + b),
            k = .8 * d + .2 * k
        }
        function e(a) {
            o = a,
            a = -Math.round(a),
            Math.abs(a) > p && (a = 0 > a ? -p: p),
            v.$left && (v.$left[0].style[s] = "translate(" + (a - p) + "px, 0)"),
            v.$center[0].style[s] = "translate(" + a + "px, 0)",
            v.$right && (v.$right[0].style[s] = "translate(" + (a + p) + "px, 0)")
        }
        function f() {
            var a, b;
            if (i) {
                if (a = Date.now() - m, b = i * Math.exp( - a / r), b > 10 || -10 > b) return e(j - b),
                d(f);
                x = !1,
                e(0),
                v.cycle(),
                [v.$left, v.$right].forEach(function(a) {
                    a && (a[0].style[s] = "translate(0, 0)", a.removeClass("js-show"))
                }),
                t && v.slide(u),
                v.swiping = !1,
                v.$right || $(document).trigger("wsq_carousel_end")
            } else x = !1
        }
        var g = this.$element.find(".ui-carousel-item." + b);
        this.$center = g,
        this.$left = this.options.wrap ? this.$element.find(".ui-carousel-item").last() : null,
        this.$right = g.next();
        var h, i, j, k, l, m, n, o = 0,
        p = this.containerWidth,
        q = !1,
        r = 125,
        s = "webkitTransform",
        t = !1,
        u = "",
        v = this,
        w = !1,
        x = !1;
        this.$element.on("touchstart",
        function(b) {
            return x ? void b.preventDefault() : (w = !1, v.sliding || (q = !0, h = a(b), k = i = 0, l = o, m = Date.now(), clearInterval(n), n = setInterval(c, 100), v.pause(), v.$left && v.$left.addClass("js-show"), v.$right && v.$right.addClass("js-show"), e(1)), void b.preventDefault())
        }),
        this.$element.on("touchmove",
        function(b) {
            if (!x) {
                var c, d;
                if (q && (c = a(b), d = h - c, d > 2 || -2 > d)) {
                    if (!v.options.wrap && d > 0 && !v.$right || 0 > d && !v.$left) return;
                    w = !0,
                    h = c,
                    e(o + d)
                }
            }
        }),
        this.$element.on("touchend",
        function() {
            x || v.sliding || (q = !1, x = !0, clearInterval(n), j = o, (k > 10 || -10 > k) && (i = 1.2 * k, j = o + i), j = Math.round(j / p * 3) * p, j = -p > j ? -p: j > p ? p: j, i = j - o, m = Date.now(), v.swiping = !0, t = 0 !== j, u = o > 0 ? "next": "prev", f(), w && (w = !1))
        })
    },
    $.Carousel = a,
    $.fn.carousel = function(b) {
        return this.each(function() {
            var c = $(this),
            d = c.data("carousel"),
            e = $.extend({},
            a.DEFAULTS, c.data(), "object" == typeof b && b),
            f = "string" == typeof b ? b: e.slide;
            d || c.data("carousel", d = new a(this, e)),
            "number" == typeof b ? d.to(b) : f ? d[f]() : e.interval && d.pause().cycle()
        })
    }
} (),
function(a, b) {
    a.PublishFace = b()
} (this,
function() {
    function a(a) {
        h = a.facePanel,
        i = a.input,
        j = a.onSelect,
        l = !1,
        m = !1
    }
    function b() {
        h.html(Tmpl(TmplInline_publish.face_panel, {}).toString()),
        h.on("tap",
        function(a) {
            var b = a.target;
            if ("SPAN" === b.tagName) {
                var e = Number(b.getAttribute("index"));
                isNaN(e) || ( - 1 === e ? d() : c(e))
            }
        }),
        h.find(".ui-carousel").carousel({
            interval: 0,
            wrap: !1,
            containerWidth: 256
        }),
        m = !0
    }
    function c(a) {
        var b = i.get(0),
        c = k[a];
        if (c) {
            var d = b.value;
            c = "/" + c;
            var e = b.selectionStart,
            f = b.selectionEnd;
            b.value = d.substring(0, e) + c + d.substring(f),
            b.selectionStart = b.selectionEnd = e + c.length,
            b.blur()
        }
    }
    function d() {
        var a = i.get(0),
        b = a.value,
        c = a.selectionStart,
        d = a.selectionEnd;
        if (c === d) {
            var e = b.substring(0, c).match(/\/([^\/]+)$/);
            e && k.indexOf(e[1]) > -1 ? (c = e.index, a.value = b.substring(0, c) + b.substring(d), a.selectionStart = a.selectionEnd = c) : (a.value = b.substring(0, c - 1) + b.substring(d), a.selectionStart = a.selectionEnd = c - 1)
        } else a.value = b.substring(0, c) + b.substring(d),
        a.selectionStart = a.selectionEnd = c;
        a.blur()
    }
    function e() {
        m || b(),
        h.show(),
        l = !0
    }
    function f() {
        h.hide(),
        l = !1
    }
    function g() {
        return l
    }
    var h, i, j, k = Config.FACE_2_TEXT,
    l = !1,
    m = !1;
    return {
        init: a,
        show: e,
        hide: f,
        isShow: g
    }
}),
function(a, b) {
    a.Publish = b(a.$, a.DB, a.Upload)
} (this,
function() {
    function a(a) {
        for (var b in ob) if (ob[b].type === a) return ob[b].name;
        return null
    }
    function b(b) {
        vb = b;
        var c = a(b);
        b ? (qb.selectCateBtn.addClass("selected"), qb.selectCateText.html(c)) : (qb.selectCateBtn.removeClass("selected"), qb.selectCateText.html("分类"))
    }
    function c(a) {
        a.preventDefault(),
        document.body.scrollTop = eb.css("top").replace("px", "")
    }
    function d(a) {
        wb && xb ? a(wb, xb) : mqq.sensor.getLocation(function(b, c, d) {
            0 === b && (wb = parseInt(1e6 * (c || 0), 10), xb = parseInt(1e6 * (d || 0), 10)),
            a(wb, xb)
        })
    }
    function e(a) {
        db ? a(db) : d(function(b, c) {
            return b && c ? void h({
                start: 0,
                num: 1
            },
            function(b) {
                0 === b.code ? (yb = b.poilist.length ? b.poilist[0].uid: 0, zb = b.city, a({
                    code: 0,
                    city: zb,
                    building: b.poilist.length ? b.poilist[0].name: ""
                })) : a(b)
            },
            !0) : a({
                code: -1,
                msg: "网络异常，无法定位，请检查网络后重试"
            })
        })
    }
    function f() {
        var a = qb.locationBtn;
        a.html("正在定位..."),
        e(function(b) {
            0 === b.code ? (a.html(b.city.replace("市", "") + " · " + b.building), qb.locationBtn && qb.locationBtn.addClass("located")) : (Tip.show(b.msg, {
                type: "warning"
            }), a.html("所在城市"))
        })
    }
    function g() {
        db = null,
        wb = xb = 0;
        var a = qb.locationBtn;
        a.html("所在城市"),
        qb.locationBtn.removeClass("located"),
        yb = 0,
        zb = "",
        hb = !1,
        ib = !1,
        Ab = 0,
        fb.empty()
    }
    function h(a, b, c) {
        $.isFunction(a) && (b = a, a = null),
        ib = !0;
        var d = {
            type: "GET",
            url: "/cgi-bin/bar/user/poilist",
            param: {
                lat: wb,
                lon: xb,
                coordinate: 1,
                start: a ? a.start: Ab,
                num: a ? a.num: Bb
            },
            succ: function(a) {
                var d = a.result || {};
                d.poilist && d.poilist.length ? (d.code = 0, c !== !0 && (gb.hide(), i(d.poilist), Ab += Bb)) : d = {
                    code: -2,
                    msg: "网络异常，无法定位，请检查网络后重试"
                },
                hb = d.isend || 0,
                b && b(d),
                ib = !1
            },
            err: function(a) {
                b && b({
                    code: a.retcode,
                    msg: "服务器繁忙，请稍后再试"
                }),
                ib = !1
            }
        };
        DB.cgiHttp(d)
    }
    function i(a) {
        for (var b, c, d = "",
        e = 0,
        f = a.length; f > e; e++) b = parseInt(1e6 * (a[e].latitude || 0), 10),
        c = parseInt(1e6 * (a[e].longitude || 0), 10),
        d += '<li data-uid="' + a[e].uid + '" data-lat="' + b + '" data-lon="' + c + '"><a href="javascript:;"><span class="poi-building text-overflow-ellipsis">' + a[e].name + '</span><span class="poi-addr text-overflow-ellipsis">' + a[e].addr + "</span></a></li>";
        $(d).appendTo(fb)
    }
    function j() {
        eb = $('<div class="pub-float pub poi-list-mask"></div>'),
        eb.hide().appendTo(document.body).html('<div id="poiLoading" class="poi-loading spinner"> 正在加载中...</div><ul id="poiList" class="poi-list"></ul>'),
        $.os.ios && bouncefix.add("poi-list-mask"),
        gb = $("#poiLoading"),
        fb = $("#poiList"),
        qb.locationBtn.off().on("tap",
        function() {
            Ib(11),
            qb.locationBtn.hasClass("located") ? ($.os.ios && (qb.iptTitle[0].blur(), qb.editor[0].blur()), window.setTimeout(function() {
                ActionSheet.show({
                    items: ["修改地点", "删除地点"],
                    onItemClick: function(a) {
                        if (0 === a) {
                            $(document.body).css("overflow", "hidden"),
                            $(document).on("scroll", c);
                            var b = Util.getHash("poi");
                            "show" !== b && (window.location.hash += "&poi=show"),
                            o(),
                            eb.show(),
                            fb.find("li").length < 1 && (Ab = 0, h())
                        } else 1 === a && g()
                    }
                })
            },
            0)) : f()
        }),
        fb.on("tap", I),
        eb.on("scroll", J)
    }
    function k() {
        $(document).off("scroll", c),
        $(document.body).css("overflow", ""),
        Ab = 0,
        eb && eb.hide()
    }
    function l() {
        $(document).off("scroll", c),
        $(document.body).css("overflow", ""),
        Ab = 0,
        fb && fb.off("tap", I),
        eb && eb.off("scroll", J).remove()
    }
    function m(a) {
        a.preventDefault()
    }
    function n() {
        $(document).on("touchmove", m)
    }
    function o() {
        $(document).off("touchmove", m)
    }
    function p() {
        Refresh && Refresh.pauseTouchMove(),
        qb.btnPublish.on("tap", u),
        qb.$btnCancel.on("tap", t);
        var a = qb.upfileOne;
        a && a.on("change", C).on("touchstart",
        function(a) {
            if (Ib(6), Lb) return void a.preventDefault();
            if (mqq && mqq.media && mqq.media.getPicture && Gb) {
                a.preventDefault();
                var b = y(),
                c = b.imgs;
                Eb.max = Cb - c,
                mqq.media.getPicture(Eb,
                function(a, b) {
                    0 === a && C(b)
                })
            }
        });
        var c = qb.upfileTwo;
        c && c.on("change", C).on("click",
        function(a) {
            if (Ib(6), Lb) return void a.preventDefault();
            if (mqq && mqq.media && mqq.media.getPicture && Gb) {
                a.preventDefault();
                var b = y(),
                c = b.imgs;
                Eb.max = Cb - c,
                mqq.media.getPicture(Eb,
                function(a, b) {
                    0 === a && C(b)
                })
            }
        });
        var d = qb.pubPics;
        if (d && (d.on("touchend", w), d.on("tap", x)), "pub" === V) {
            var e = $(".pub-cate");
            qb.selectCateBtn = e,
            qb.selectCateText = e.find(".cag-text"),
            "nearby" === jb ? e.on("tap",
            function() {
                qb.editor.blur(),
                qb.iptTitle.blur(),
                $("#publish-panel").hide(),
                o(),
                window.NearbyPublish && window.NearbyPublish.show()
            }) : e.on("tap",
            function() {
                Ib(10),
                window.setTimeout(function() {
                    ActionSheet.show({
                        items: bb,
                        onItemClick: function(a) {
                            a = ob[a].name || 0,
                            b(a)
                        }
                    })
                },
                0)
            })
        }
        qb.selectPhotoBtn && qb.selectPhotoBtn.on("tap",
        function() {
            qb.$selectFaceBtn && (window.PublishFace && window.PublishFace.hide(), qb.$selectFaceBtn.removeClass("active")),
            qb.editor.blur(),
            qb.iptTitle.blur(),
            qb.selectPhotoBtn.addClass("active"),
            qb.pubPics.show()
        }),
        qb.$selectFaceBtn && qb.$selectFaceBtn.on("tap",
        function() {
            qb.selectPhotoBtn && (qb.selectPhotoBtn.removeClass("active"), qb.pubPics.hide()),
            qb.editor.blur(),
            qb.iptTitle.blur(),
            qb.$selectFaceBtn.addClass("active"),
            window.PublishFace && window.PublishFace.show()
        }),
        qb.editor.on("input", r),
        n(),
        X.needLocation && f()
    }
    function q(a) {
        return ("" + a).length
    }
    function r() {
        if (qb.cpEditor.val(qb.editor.val()), qb.editor.css("height", Math.min(72, qb.cpEditor[0].scrollHeight + qb.cpEditor.css("height")) + "px"), "pub" === V) {
            var a = q($.trim(qb.editor.val())),
            b = X.maxLength - a;
            qb.remainTxt.html(b),
            0 > b ? qb.remainTxt.css("display", "inline-block") : qb.remainTxt.hide(),
            qb.remainTxt[0 > b ? "addClass": "removeClass"]("red")
        }
    }
    function s() {
        qb.$btnCancel && qb.$btnCancel.off("tap", t),
        qb.btnPublish.off("tap", u),
        qb.upfileOne && qb.upfileOne.off("change", C),
        qb.upfileTwo.off("change", C),
        qb.pubPics.off("touchend", x),
        qb.editor.off("input", r),
        o(),
        Refresh && Refresh.restoreTouchMove()
    }
    function t() {
        $.os.ios && setTimeout(function() {
            document.body.scrollTop = 0
        },
        0),
        Publish.destroy(),
        mb.onhidden && mb.onhidden(),
        Ib(13)
    }
    function u() {
        if (!sb && v() && ub) {
            var a = $(this);
            sb = 1;
            var b = $.trim(qb.iptTitle.val()),
            c = $.trim(qb.editor.val()),
            d = [];
            a.css("opacity", "0.4"),
            pb.forEach(function(a) {
                var b = tb[a],
                c = b.metaData;
                0 === b.state && c && d.push(c)
            });
            var e = {
                bid: ab.bid || 10020,
                lat: wb,
                lon: xb,
                coordinate: 1,
                uid: yb
            };
            vb && (e.type = vb),
            ab.father_pid && (e.father_pid = ab.father_pid),
            ab.subParam && $.extend(e, ab.subParam);
            var f = {
                type: "POST",
                url: "/cgi-bin/bar/post/publish_v2",
                param: e,
                succ: function(b) {
                    b.result.vflag ? Checkcode.show("verify_v2",
                    function(c) {
                        $.extend(b.result, c),
                        Jb(a, b)
                    },
                    {
                        type: ab.cid ? 3 : ab.pid ? 2 : 1,
                        code: b.result.code
                    }) : Jb(a, b)
                },
                err: function(b) {
                    var c = "";
                    a.css("opacity", ""),
                    c = 99999 === b.retcode ? "您的操作太频繁，请稍后再试": 214 === b.retcode ? "内容中含有敏感词汇，请重新输入": 215 === b.retcode ? "标题中含有敏感词汇，请重新输入": 4002 === b.retcode ? "发表内容重复度太高，请重新输入": 100117 === b.retcode ? "内容字数不满足要求": 100118 === b.retcode ? "标题字数不满足要求": 10000188 === b.retcode ? "内容中含有恶意链接，请重新输入": 10 === b.retcode ? "该评论或该话题已被删除": "发表失败，请稍后再试",
                    Tip.show(c, {
                        type: "warning"
                    }),
                    Ib(8),
                    sb = 0
                }
            };
            ab.cid ? (e.pid = ab.pid, e.cid = ab.cid, e.comment = encodeURIComponent(JSON.stringify({
                content: c
            })), ab.rid && (e.target_rid = ab.rid), f.url = "/cgi-bin/bar/post/recomment") : ab.pid ? (e.pid = ab.pid, ab.ref_cid && (e.ref_cid = ab.ref_cid), e.comment = encodeURIComponent(JSON.stringify({
                content: c,
                pic_list: d
            })), f.url = "/cgi-bin/bar/post/comment_v2") : (e.title = encodeURIComponent(b), e.post = encodeURIComponent(JSON.stringify({
                content: c,
                pic_list: d
            }))),
            DB.cgiHttp(f),
            ("reply" === V || "comment_reply" === V || "nearby" === jb) && Ib(12)
        }
    }
    function v() {
        var a = ab.flag;
        if (X.needTitle) {
            var b = $.trim(qb.iptTitle.val()),
            c = q(b);
            if (b.length < X.minTitleLength) return Tip.show("请输入话题", {
                type: "warning"
            }),
            Ib(7),
            void qb.iptTitle.focus();
            if (c > X.maxTitleLength) return Tip.show("标题字数不能超过" + X.maxTitleLength + "个汉字", {
                type: "warning"
            }),
            Ib(7),
            void qb.iptTitle.focus();
            if (c < X.minTitleLength) return Tip.show("标题字数至少" + X.minTitleLength + "个汉字", {
                type: "warning"
            }),
            Ib(7),
            void qb.iptTitle.focus()
        }
        var d = y(),
        e = $.trim(qb.editor.val()),
        f = q(e);
        if (f > X.maxLength) return Tip.show("内容字数不能超过" + X.maxLength + "个汉字", {
            type: "warning"
        }),
        Ib(7),
        Ib(16),
        void qb.editor.focus();
        if (X.photoOrContent) {
            if (f < X.minLength && !d.imgs) return Tip.show("输入至少" + X.minLength + "个汉字或图片", {
                type: "warning"
            }),
            Ib(7),
            void qb.editor.focus()
        } else if (f < X.minLength) return Tip.show("输入至少" + X.minLength + "个汉字", {
            type: "warning"
        }),
        Ib(7),
        Ib(16),
        void qb.editor.focus();
        return "pho_detail" !== jb || d.imgs ? "pub" === V && X.needPhoto && 1 & a && !d.imgs ? (Tip.show("本部落为图片部落，发表话题必须带图", {
            type: "warning"
        }), void Ib(7)) : d.uping ? (Tip.show("图片上传中，请稍候", {
            type: "warning"
        }), void Ib(7)) : 0 === ab.bid ? void Tip.show("请先选择部落再发表话题", {
            type: "warning"
        }) : !0 : (Tip.show("参与晒图,发表话题必须带图哦", {
            type: "warning"
        }), void Ib(7))
    }
    function w(a) {
        var b = $(a.target),
        c = b.parent(".up-pic"),
        d = parseInt(c.attr("data-gid"));
        b.hasClass("btn-del") && (Lb = !0, setTimeout(function() {
            Lb = !1
        },
        500), c.remove(), delete tb[d], pb = pb.filter(function(a) {
            return a !== d
        }), Kb = !0, z())
    }
    function x(a) {
        return Kb ? (Kb = !1, a.preventDefault(), !1) : void 0
    }
    function y() {
        var a = 0,
        b = 0,
        c = 0;
        for (var d in tb) if (tb.hasOwnProperty(d)) {
            var e = tb[d].state;
            1 === e ? b++:2 === e && c++,
            a++
        }
        return {
            imgs: a - c,
            uping: b,
            err: c
        }
    }
    function z() {
        var a = qb.upfileOne;
        if (a) {
            var b = y();
            b.imgs >= Cb ? a.attr("disabled", "disabled") : a.removeAttr("disabled")
        }
    }
    function A(a) {
        return Array.prototype.slice.call(a)
    }
    function B(a, b) {
        if (b) return 0 === a.match ? !0 : !1;
        var c = a.type,
        d = a.name,
        e = d.split("."),
        f = e[e.length - 1].toLowerCase();
        return c && !/^image/.test(c) ? !1 : a.size > 5242880 ? !1 : "mp4" === f ? !1 : !0
    }
    function C(a) {
        var b = this.files,
        c = [],
        d = !1;
        a = a || [],
        a.length > 0 && (b = a, d = !0),
        $.isArray(b) || (b = A(b));
        var e = qb.upfileOne,
        f = qb.upfileTwo;
        if (e) {
            var g = y(),
            h = g.imgs + b.length;
            h > Cb && (b.length = Cb - g.imgs, Alert.show("一次最多上传" + Cb + "张图片", "", {
                confirm: "我知道了"
            })),
            h >= Cb ? e.attr("disabled", "disabled") : e.removeAttr("disabled")
        }
        if (b && b.length > 0) {
            var i, j = _.find(".pub-wrap"),
            k = j.find(".pub-pics"),
            l = rb;
            e.css("pointer-events", "none"),
            f.css("pointer-events", "none"),
            j.addClass("img-preview"),
            k.show(),
            qb.selectPhotoBtn.addClass("active"),
            qb.$selectFaceBtn && (qb.$selectFaceBtn.removeClass("active"), window.PublishFace && window.PublishFace.hide()),
            $("input.upfile").val("");
            for (var m = 0; m < b.length; m++) B(b[m], d) && (c.push(b[m]), i = $('<li class="up-pic" id="upPic' + l + '"></li>'), Tmpl(window.TmplInline_publish.preview, {}).appendTo(i), i.insertBefore(k.children().last()), l++);
            E(c, d)
        }
    }
    function D() {
        var a = qb.upfileOne,
        b = qb.upfileTwo;
        a.css("pointer-events", "auto"),
        b.css("pointer-events", "auto")
    }
    function E(a, b) {
        var c = a[0];
        if (!c) return void D();
        var d = rb;
        rb++;
        var e = $("#upPic" + d),
        f = {
            canUpload: function() {
                tb[d] = {},
                pb.push(d),
                c.gid = d,
                tb[d].state = 1
            },
            complete: function(c, e) {
                var f = 38;
                $("input.upfile").val("");
                var g = tb[d].dom.find("div.pos");
                g.css("-webkit-transition", "all 1s ease-out"),
                g.css("width", f + "px"),
                setTimeout(function() {
                    a.shift(),
                    E(a, b);
                    var f, g = tb[d];
                    if (g) if (0 === c.retcode) {
                        f = c.result,
                        g.state = 0,
                        g.metaData = {
                            url: f.url,
                            w: f.w,
                            h: f.h
                        };
                        var h = e.type;
                        h && (h = h.split(/\//), h = h && h[1] || "", h = h.toLowerCase(), "gif" === h && (g.metaData.t = h)),
                        g.dom.addClass("up-over")
                    } else g.state = 2,
                    g.dom.addClass("up-error")
                },
                1500)
            },
            progress: function() {},
            error: function() {
                var c = tb[d];
                c.state = 2,
                c.dom.find("img").hide(),
                c.dom.addClass("up-error"),
                a.shift(),
                E(a, b)
            },
            abort: function() {
                var c = tb[d];
                c.state = 2,
                c.dom.find("img").hide(),
                c.dom.addClass("up-error"),
                a.shift(),
                E(a, b)
            },
            compress: function(a) {
                a.gid = d,
                F(a, e);
                var b = 38,
                c = tb[d].dom,
                f = c.find("div.pos");
                f.css("-webkit-transition", "all 2.5s ease-out"),
                f.css("width", 3 * b / 4 + "px")
            }
        },
        g = {
            callbacks: f,
            param: {
                type: 2
            }
        };
        Gb ? kb ? (Upload(kb[0], g), kb = null) : mqq.invoke("media", "getLocalImage", {
            outMaxWidth: 480,
            outMaxHeight: 480,
            inMinWidth: 50,
            inMinHeight: 50,
            imageID: c.imageID,
            callback: mqq.callback(function(a, c) {
                0 === a && (b && (c.isNative = !0), Upload(c, g))
            })
        }) : (b && (c.isNative = !0), Upload(c, g))
    }
    function F(a, b) {
        var c, d, e = a.gid;
        a.w < a.h ? (c = parseInt(65 / a.w * a.h), d = {
            width: 65,
            height: c,
            margin: (c - 65) / 2,
            dir: "top"
        }) : (c = parseInt(65 / a.h * a.w), d = {
            height: 65,
            width: c,
            margin: (c - 65) / 2,
            dir: "left"
        }),
        b.attr("data-gid", e);
        var f = b.find("img");
        f.attr("src", a.base64),
        f.css({
            width: d.width + "px",
            height: d.height + "px",
            display: "block"
        }),
        f.css("margin-" + d.dir, "-" + d.margin + "px"),
        tb[e].dom = b
    }
    function G(a, b) {
        return a += "",
        new Array(b > a.length ? b - a.length + 1 : 0).join(0) + a
    }
    function H(a) {
        bb = [],
        ob = [];
        for (var b = 0; b < a.length; b++) bb.push(a[b].name),
        ob.push({
            name: a[b].name,
            type: a[b].type
        })
    }
    function I(a) {
        a.preventDefault();
        var b = $(a.target).closest("li"),
        c = b.find(".poi-building").html(),
        d = b.find(".poi-addr").html(),
        e = qb.locationBtn;
        0 === c.indexOf(zb) && (c = c.slice(zb.length)),
        e.html( - 1 === d.indexOf(zb) ? c: zb.replace("市", "") + " · " + c),
        wb = b.attr("data-lat"),
        xb = b.attr("data-lon"),
        yb = b.attr("data-uid"),
        n(),
        eb.hide(),
        window.history.go( - 1)
    }
    function J(a) {
        Nb && window.clearTimeout(Nb);
        var b = this;
        Nb = window.setTimeout(function() {
            K.call(b, a)
        },
        100)
    }
    function K(a) {
        if (a.preventDefault(), hb || ib) return ! 1;
        var b = document.documentElement.clientHeight,
        c = eb[0],
        d = c.scrollTop,
        e = c.scrollHeight;
        d + 2 * b + 20 >= e && h()
    }
    function L() {
        $(".pic-type").hide(),
        $(".up-entry").hide()
    }
    function M(a, b) {
        T[a] = $.extend({},
        T[a], b)
    }
    function N(a) {
        a = a || {},
        V = a.pubulishType || "pub",
        X = $.extend({},
        S, T[V], a.config || {}),
        ab = $.extend({},
        a),
        ab.replyMode = "pub" !== V,
        !$.os.android || 0 !== $.os.version.indexOf("4.4.1") && 0 !== $.os.version.indexOf("4.4.0") || (Fb = !0),
        mqq && mqq.compare("4.7.2") >= 0 && (Gb = !0),
        mb = a,
        cb = a.isReply,
        a.onhidden && (mb.onhidden = a.onhidden, mb.ondestroy = mb.onhidden),
        Mb || (W = {
            pub: "pub_page",
            reply: "reply_page",
            comment_reply: "two_comment"
        } [V], jb = a.fromType || "home", "nearby" === jb && (W = "nearby_page"), "comment_reply" === V && (lb = "detail" === a.page ? "floor" === a.action ? 1 : 0 : "floor" === a.action ? 3 : 2), lb = a.postType, Ab = 0, ub = !1, "nearby" === jb ? P(a) : Rb(function() {
            P(a),
            a.outsideFile ? C(a.outsideFile) : a.outsideBase64Obj && (kb = a.outsideBase64Obj, C(kb)),
            o()
        }))
    }
    function O(a) {
        var b = mqq.compare("5.3") > -1;
        1 === a ? (mqq.iOS || mqq.android && b) && Tip.show("发表成功", {
            type: "ok"
        }) : b && Tip.show("回复成功", {
            type: "ok"
        })
    }
    function P(a) {
        var b, c = function(a) {
            var b;
            return b = a,
            b += "",
            b = b.replace(/&/g, "&amp;"),
            b = b.replace(/>/g, "&gt;"),
            b = b.replace(/</g, "&lt;"),
            b = b.replace(/"/g, "&quot;"),
            b = b.replace(/'/g, "&#39;"),
            b = b.replace(/=/g, "&#61;"),
            b = b.replace(/`/g, "&#96;")
        };
        if ("comment_reply" === V ? b = "回复 " + c(a.nick_name) : (b = "内容，" + X.minLength + "-" + X.maxLength + "个字", 2 & a.flag && (b += "(本部落所有发言将匿名发表)")), mqq && mqq.compare("5.0") > -1 && !a.webOnly) {
            var d = {
                pub: "GroupTribePublish",
                reply: "GroupTribeReply",
                comment_reply: "GroupTribeComment"
            } [V];
            if ("comment_reply" === V) b = "回复 " + a.nick_name;
            else if ("reply" === V) b = "我也说一句...";
            else {
                b = "内容，" + X.minLength + "-" + X.maxLength + "个字";
                var e = "";
                Y && Y.requireType && (e = "必须带" + {
                    1 : "图片",
                    2 : "录音",
                    4 : "音乐",
                    8 : "视频"
                } [Y.requireType]),
                2 & a.flag ? b += "(本部落所有发言将匿名发表" + (e ? "，且" + e: "") + ")": e && (b += "(本部落" + e + ")")
            }
            var f = X;
            f.maxContentLength = X.maxLength,
            f.minContentLength = X.minLength,
            f.titlePlaceholder = "标题，" + X.minTitleLength + "-" + X.maxTitleLength + "个字",
            f.contentPlaceholder = b,
            f.bid = a.bid,
            f.pid = a.pid,
            f.cid = a.cid,
            f.rid = a.rid,
            f.flag = a.flag,
            f.barName = a.barName,
            f.needCategory = !1,
            $(document.body).addClass("native-" + V),
            $.extend(f, Y),
            f.options = {
                audio: {
                    cgi: "http://upload.buluo.qq.com/cgi-bin/bar/upload/base64image",
                    maxRecord: 123,
                    data: {
                        aaa: "bb",
                        ccc: "ddd"
                    }
                },
                pic: {
                    cgi: "http://upload.buluo.qq.com/cgi-bin/bar/upload/meida",
                    data: {
                        aaa: "bb",
                        ccc: "ddd"
                    }
                }
            };
            var g = {
                name: d,
                animation: 1001,
                options: f,
                onclose: mqq.callback(function(b) {
                    $(document.body).removeClass("native-" + V);
                    var c = a.succ;
                    if (Publish.destroy(), 0 === b.code) {
                        Ib(9),
                        "string" == typeof b.data && (b.data = JSON.parse(b.data));
                        var d = a.cid ? 3 : a.pid ? 2 : 1;
                        if (b.data.result.vflag) {
                            var e = "verify_v2";
                            Checkcode.show(e,
                            function(a) {
                                $.extend(b.data.result, a),
                                O(d),
                                c && c(b.data.result)
                            },
                            {
                                type: d,
                                code: b.data.result.code
                            })
                        } else O(d),
                        c && c(b.data.result)
                    } else Ib( - 1 === b.code ? 13 : 8)
                },
                !1, !0)
            };
            return "comment_reply" === V && (g.viewType = "popWindow"),
            void mqq.ui.openView(g)
        }
        var h = {
            flag: a.flag,
            replyMode: ab.replyMode,
            personal: $.storage.get("personal") || {},
            config: X,
            contentPlaceholder: b
        };
        _ = $('<div id="publish-panel" class="pub-float ' + V.replace(/_/g, "-") + '" />'),
        $(document.body).append(_),
        _.html(new Tmpl(window.TmplInline_publish.publish, h).toString()),
        qb = {
            $btnCancel: $(".pub-cancel"),
            btnPublish: $(".pub-publish"),
            upfileOne: $(".up-entry-one"),
            locationBtn: $(".location-text"),
            selectPhotoBtn: $("#selectPic"),
            upfileTwo: $(".up-entry-two"),
            pubPics: $(".pub-pics"),
            pubWrap: _.find(".pub-wrap"),
            iptTitle: _.find(".ipt-theme"),
            editor: _.find(".editor"),
            cpEditor: _.find(".cp-editor"),
            remainTxt: _.find(".pub-remain-wording")
        };
        var i = Math.max(document.body.clientHeight, document.documentElement.clientHeight, window.innerHeight);
        if (_.css("height", i + "px"), qb.pubWrap.addClass("show"), Fb && !Gb && L(), /android 2.3/i.test(navigator.userAgent) && navigator.userAgent.match(/\/qqdownloader\/(\d+)?/) && L(), X.needFace && (qb.$selectFaceBtn = $("#selectFace"), qb.$facePanel = $(".pub-faces"), window.PublishFace && window.PublishFace.init({
            facePanel: qb.$facePanel,
            input: qb.editor
        })), X.needPhoto && "pub" === V && qb.selectPhotoBtn.addClass("active"), "nearby" !== jb) if (cb) {
            var j = qb.editor[0];
            $.os.ios && j && j.focus()
        } else {
            var k = qb.iptTitle[0];
            $.os.ios && k && k.focus()
        }
        Ub(a.ctxNode),
        p()
    }
    function R(a) {
        _ && (s(), _.remove(), l(), _ = null, pb = [], tb = {},
        a || ab.ctxNode && ab.ctxNode.show(), sb = 0, qb = {}),
        ActionSheet.hide(),
        mb.ondestroy && mb.ondestroy()
    }
    var S = {
        needLocation: !1,
        needCategory: !1,
        needPhoto: !1,
        needTitle: !1,
        needFace: !0,
        photoOrContent: !1,
        needCancelBtn: !1,
        contentPlaceholder: "",
        maxLength: 700,
        minLength: 10,
        maxTitleLength: 25,
        minTitleLength: 4
    },
    T = {
        pub: {
            needLocation: !0,
            needCategory: !0,
            needPhoto: !0,
            needTitle: !0
        },
        reply: {
            needLocation: !0,
            needPhoto: !0,
            photoOrContent: !0,
            needCancelBtn: !0,
            minLength: 3
        },
        comment_reply: {
            needCancelBtn: !0,
            minLength: 3
        }
    },
    U = navigator.userAgent.match(/\bMicroMessenger\/([\d\.]+)/);
    U && (T.reply.needPhoto = !1);
    var V, W, X, Y, Z, _, ab, bb, cb, db, eb, fb, gb, hb, ib, jb, kb, lb, mb = {},
    nb = "http://xiaoqu.qq.com/mobile/",
    ob = [{
        type: 3,
        name: "原创"
    },
    {
        type: 4,
        name: "招募"
    }],
    pb = [],
    qb = {},
    rb = 1,
    sb = 0,
    tb = {},
    ub = !1,
    vb = 0,
    wb = 0,
    xb = 0,
    yb = 0,
    zb = "",
    Ab = 0,
    Bb = 20,
    Cb = 8,
    Db = 2252.8,
    Eb = {
        source: 0,
        max: 8,
        outMaxWidth: Db,
        urlOnly: !0,
        outMaxHeight: Db
    },
    Fb = !1,
    Gb = !1,
    Hb = {
        1 : {
            action: "visit"
        },
        2 : {
            action: "Clk_un_text"
        },
        3 : {
            action: "Clk_un_nottext"
        },
        4 : {
            action: "input_name"
        },
        5 : {
            action: "input_text"
        },
        6 : {
            action: "Clk_pic"
        },
        7 : {
            action: "pub_fail_rule"
        },
        8 : {
            action: "pub_fail_other"
        },
        9 : {
            action: "pub_suc"
        },
        10 : {
            action: "Clk_choose"
        },
        11 : {
            action: "Clk_place"
        },
        12 : {
            action: "Clk_pub"
        },
        13 : {
            action: "Clk_un"
        },
        14 : {
            action: "refuse_rank",
            module: "tribe_hp"
        },
        15 : {
            action: "refuse_rank",
            module: "post_detail"
        },
        16 : {
            action: "pub_fail_number"
        },
        17 : {
            action: "exp_comment"
        }
    },
    Ib = function(a, b, c) {
        var d = {
            opername: "Grp_tribe",
            module: W,
            ver1: ab && ab.bid
        };
        ab && ab.pid && (d.obj1 = ab.pid),
        "number" == typeof lb && (d.ver3 = lb);
        for (var e in Hb[a]) Hb[a].hasOwnProperty(e) && (d[e] = Hb[a][e]);
        for (e in c) c.hasOwnProperty(e) && (d[e] = c[e]);
        Q.tdw(d)
    },
    Jb = function(a, b) {
        var c = b.result.level.addscore;
        Tip.show("发表成功" + (c ? "，获得" + c + "个等级积分": ""), {
            type: "ok"
        }),
        Ib(9),
        $.os.ios && setTimeout(function() {
            document.body.scrollTop = 0
        },
        0),
        a.css("opacity", ""),
        setTimeout(function() {
            var a, c = ab.succ;
            $("#rankTab")[0] && "block" === $("#rankTab").css("display") && (a = !0),
            Publish.destroy(a),
            c && c(b.result)
        },
        2e3)
    },
    Kb = !1,
    Lb = !1,
    Mb = !1,
    Nb = null,
    Ob = function(a, b) {
        ab.bid = a,
        $(".cag-text").text(b),
        Rb(function() {
            $("#publish-panel").show(),
            n(),
            window.NearbyPublish && window.NearbyPublish.hide(),
            qb.selectCateBtn.addClass("selected")
        })
    },
    Pb = function(a) {
        var b = {
            type: "POST",
            url: "/cgi-bin/bar/post/publishable_v2",
            localCache: !1,
            cacheKey: Login.getUin() + "-publishable_v2-" + a.param.bid + "-" + a.param.pub_type,
            cacheTimeout: 6e5,
            cacheVersion: "1",
            update: null
        };
        a = $.extend({},
        b, a),
        (a.param || (a.param = {})).version = mqq.QQVersion,
        DB.cgiHttp(a)
    },
    Qb = function(a, b) {
        var c = !0;
        return a && 2 === parseInt(a.requireType, 10) ? mqq && mqq.compare("5.2") < 0 && !b && (Alert.show("", "本部落必须发表语音，即将在新版本支持，敬请期待", {
            confirm: "我知道了"
        }), c = !1) : a && 4 === parseInt(a.requireType, 10) ? mqq && mqq.compare("5.2") < 0 && !b && (Alert.show("", "本部落必须发表音乐，即将在新版本支持，敬请期待", {
            confirm: "我知道了"
        }), c = !1) : a && 8 === parseInt(a.requireType, 10) && mqq && mqq.compare("5.3") < 0 && !b && (Alert.show("", "本部落必须发表视频，即将在新版本支持，敬请期待", {
            confirm: "我知道了"
        }), c = !1),
        c
    },
    Rb = function(a) {
        function b(b) {
            ub = !0;
            var c, d = b.result,
            e = parseInt(d.can_grievance, 10),
            f = d[ab.replyMode ? "can_reply": "can_send"],
            g = parseInt(d.vcode, 10);
            if (Y = d && d.publish_condition || {},
            c = Y.optionType, mqq && mqq.compare("5.3") < 0 && 8 === (8 & c) && (Y.optionType = c - 8), Qb(Y, ab.replyMode)) if ("post_limit" in d && (d.post_limit || (X.minLength = 1, X.minTitleLength = 1)), 0 === f) {
                var h = function() {
                    a && a(),
                    b.fromCache || (Ib(1), "comment_reply" === V && Ib(17))
                };
                0 === g ? h() : Checkcode.show("verify",
                function() {
                    setTimeout(function() {
                        h()
                    },
                    500)
                })
            } else {
                "nearby" !== jb && R();
                var i = function() {
                    "nearby" === jb && mqq.ui.popBack()
                };
                if (1 === f) {
                    var j = parseInt(d.desc.split("-")[0]),
                    k = parseInt(d.desc.split("-")[1] % 86400);
                    Alert.show("本部落只有在" + G(Math.floor(j / 3600), 2) + ":" + G(Math.floor(j % 3600 / 60), 2) + "-" + (j > k ? "次日": "") + G(Math.floor(k / 3600), 2) + ":" + G(Math.floor(k % 3600 / 60), 2) + "才能发表话题", "", {
                        confirm: "我知道了"
                    })
                } else if (2 === f) Alert.show("本部落只有相应地区的用户才能发表话题", "", {
                    confirm: "我知道了",
                    callback: i
                });
                else if (3 === f) {
                    var l = {
                        confirm: "我知道了",
                        callback: i
                    };
                    1 === e && (l.cancel = "我要申诉", l.cancelCallback = function() {
                        Util.openUrl(nb + "complain.html", !0)
                    }),
                    Alert.show("您的帐号有可疑记录，暂时被系统封停", "", l)
                } else if (4 === f) {
                    var m = d.publish_level || 12;
                    Alert.show("暂时没有发表权限", "您当前的QQ等级过低, QQ" + m + "级以上的用户才能" + {
                        pub: "发表新话题",
                        reply: "回复话题",
                        comment_reply: "评论话题"
                    } [V], {
                        confirm: "我知道了",
                        callback: i
                    }),
                    Ib("pub" !== V || b.fromCache ? 15 : 14)
                } else 5 === f ? Alert.show("本部落内测中，只有群主或管理员才能发表话题", "", {
                    confirm: "我知道了",
                    callback: i
                }) : Alert.show("系统繁忙", "", {
                    confirm: "我知道了",
                    callback: i
                });
                "nearby" === jb && window.NearbyPublish && window.NearbyPublish.clearActive()
            }
        }
        Z ? (b(Z), Mb = !1) : Pb({
            param: {
                bid: ab.bid,
                pub_type: "pub" === V ? 0 : 1
            },
            succ: function(a) {
                b(a),
                Mb = !1
            },
            err: function() {
                Mb = !1
            }
        })
    },
    Sb = function(a) {
        a = a || {};
        var b, c = a.pubulishType,
        d = $.extend({},
        a),
        e = $.cookie("uin"),
        f = "localPubCheckData_" + (c || "reply");
        return e && localStorage.getItem(f) && (b = JSON.parse(localStorage.getItem(f))),
        b && b.uin === e && b.bid === d.bid && b.timespan + 36e5 >= (new Date).valueOf() ? void(Z = b.data) : void Pb({
            param: {
                bid: d.bid,
                pub_type: "pub" === c ? 0 : 1
            },
            noNeedLogin: !0,
            succ: function(a) {
                Z = a,
                e && localStorage.setItem(f, JSON.stringify({
                    uin: e,
                    bid: d.bid,
                    timespan: (new Date).valueOf(),
                    data: Z
                }))
            }
        })
    },
    Tb = !1,
    Ub = function(a) {
        Tb || (Tb = !0, Kb = !1, !ab.replyMode && a && a.hide(), "nearby" === jb && $(".cag-text").html("部落"), j())
    };
    return {
        init: N,
        initSelector: H,
        destroy: R,
        hidePoiList: k,
        destoryPoiList: l,
        setBid: Ob,
        setConfig: M,
        getPreCheckData: Sb
    }
}),
function() {
    var a = Util.queryString("bid") || Util.getHash("bid"),
    b = "";
    location.href.toLowerCase().indexOf("barindex.html") >= 0 && (b = "pub"),
    a && window.Publish.getPreCheckData({
        bid: a,
        pubulishType: b
    })
} ();