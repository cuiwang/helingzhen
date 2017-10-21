!function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_complain = b()
}(this, function() {
    var a = {}
      , b = function(a, b) {
        a = a || {};
        var c = "";
        return c += '<div class="complain-outer-container"> <div class="complain-container"> <div class="send-vfcode-text">验证码已发送至您的手机:<div class="pho-num-text"></div></div> <div class="validate-code-input-container border-1px"> <input class="validate-code-input" placeholder="请输入手机收到的验证码" type="tel"> </div> <div class="phone-warn-tips">您的帐号可能存在异常，请先完成手机验证！</div> <div class="phone-num-validate-input-container border-1px"> <input class="phone-num-validate-input" placeholder="请输入手机号码" type="tel"> </div> <div class="validate-fail-tips">请输入正确的手机号码</div> <button class="confirm-vfcode-btn">确认</button> <button class="get-vfcode-btn">获取验证码</button> <button class="complain-close-btn"></button> </div> <div class="success-alert">解封成功！</div> </div> '
    }
    ;
    return a.complain = "TmplInline_complain.complain",
    Tmpl.addTmpl(a.complain, b),
    a
}),
function(a, b) {
    a.Complain = b()
}(this, function() {
    function a(a, b) {
        DB.cgiHttp({
            url: v ? "/cgi-bin/bar/post/send_code" : "/cgi-bin/bar/admin/grievance",
            param: {
                phone_num: a
            },
            succ: function(a) {
                b && b(a)
            },
            err: function(a) {
                2003 === a.retcode ? Tip.show("未被拉黑，无法申诉", {
                    type: "warning"
                }) : 2007 === a.retcode ? Tip.show("短信验证码超时", {
                    type: "warning"
                }) : 2008 === a.retcode ? v && Tip.show("请在1分钟以后再进行验证", {
                    type: "warning"
                }) : 2009 === a.retcode ? Tip.show("请在1分钟以后再进行申诉", {
                    type: "warning"
                }) : 2005 === a.retcode ? e(v ? "该手机已绑定其他帐号，请重新输入新的手机号码" : "该手机已申请解封其他帐号，请重新输入新的手机号码") : 2012 === a.retcode && v ? Tip.show("验证次数太频繁，请稍后再试", {
                    type: "warning"
                }) : v ? Tip.show("验证失败,请稍后重试", {
                    type: "warning"
                }) : Tip.show("申诉失败,请稍后重试", {
                    type: "warning"
                })
            }
        })
    }
    function b(a, b, c) {
        var d = {
            phone_num: a
        };
        v ? (d.vcode_type = 2,
        d.vcode = b,
        d.type = A,
        d.code = B) : d.verify_code = b,
        DB.cgiHttp({
            url: v ? "/cgi-bin/bar/post/captcha/verify_v2" : "/cgi-bin/bar/admin/grievance_verify",
            param: d,
            succ: function(a) {
                0 === a.retcode && c && c(a.result)
            },
            err: function(a) {
                var b = a.retcode;
                2008 === b || v && 2007 === b ? Tip.show("验证码输入错误，请输入正确的验证码[" + b + "]", {
                    type: "warning"
                }) : Tip.show("验证失败[" + b + "]", {
                    type: "warning"
                })
            }
        })
    }
    function c(a) {
        q = a,
        k.addClass("waiting"),
        clearTimeout(z),
        d()
    }
    function d() {
        k.text(q + "秒后重新获取"),
        0 === q ? (k.removeClass("waiting"),
        k.text("获取验证码")) : (q--,
        z = setTimeout(d, 1e3))
    }
    function e(a) {
        l.text(a),
        l.show()
    }
    function f(d) {
        d = d || {},
        v = d.isInsidePage,
        w = d.userDefaultPhoneNum,
        x = d.onSuccess,
        A = d.type,
        B = d.code,
        s = $(".complain-wrap"),
        s.show(),
        s.html(Tmpl(window.TmplInline_complain.complain, {}, {}).toString()),
        g = $(".phone-num-validate-input"),
        h = $(".phone-num-validate-input-container"),
        i = $(".validate-code-input"),
        j = $(".validate-code-input-container"),
        k = $(".get-vfcode-btn"),
        l = $(".validate-fail-tips"),
        m = $(".pho-num-text"),
        n = $(".send-vfcode-text"),
        o = $(".confirm-vfcode-btn"),
        p = $(".success-alert"),
        t = $(".complain-close-btn"),
        u = $(".complain-wrap"),
        y = $(".phone-warn-tips"),
        v && y.show(),
        w ? g.val(w) : k.addClass("empty-input"),
        mqq && mqq.ui && mqq.ui.setWebViewBehavior({
            keyboardDisplayRequiresUserAction: !1
        }),
        setTimeout(function() {
            g.focus()
        }, 500);
        var f = localStorage.getItem("vfcode-waiting-start-time");
        if (f) {
            f = Number(f);
            var q, z = (Date.now() - f) / 1e3;
            60 > z && (q = 60 - z,
            c(~~q))
        }
        t.on("tap", function() {
            s.hide()
        }),
        u.on("touchmove", function(a) {
            a.preventDefault()
        }),
        g.on("input", function(a) {
            var b = $(a.target)
              , c = b.val();
            "" === c || 11 !== c.length ? k.addClass("empty-input") : k.removeClass("empty-input")
        }),
        k.on("tap", function() {
            k.hasClass("waiting") || k.hasClass("empty-input") || (k.addClass("active"),
            setTimeout(function() {
                return k.removeClass("active"),
                C.test(g.val()) ? (l.hide(),
                r = g.val(),
                void a(r, function() {
                    c(60),
                    localStorage.setItem("vfcode-waiting-start-time", Date.now()),
                    m.text(r),
                    n.show(),
                    h.hide(),
                    j.show(),
                    o.show(),
                    y.hide()
                })) : void e("请输入正确的手机号码")
            }, 500))
        }),
        o.on("tap", function() {
            var a = i.val();
            o.addClass("active"),
            a && setTimeout(function() {
                o.removeClass("active"),
                b(r, a, function(a) {
                    v ? (s.hide(),
                    x && x(a)) : (p.show(),
                    setTimeout(function() {
                        -1 !== mqq.compare("4.6") ? mqq.ui.popBack() : window.history && window.history.back(),
                        mqq.dispatchEvent("complainSuccess", {})
                    }, 2e3))
                })
            }, 500)
        })
    }
    var g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z, A, B, C = /^\d{11}$/;
    return {
        init: f
    }
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_actionsheet = b()
}(this, function() {
    var a = {}
      , b = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("title")
          , f = c("items")
          , g = c("cancleText")
          , h = "";
        h += " ",
        e && (h += ' <div class="sheet-item sheet-title" value="-1">',
        h += d(e),
        h += "</div> "),
        h += " ";
        for (var i = 0; i < f.length; i++)
            h += ' <div class="sheet-item ',
            h += d(f[i].type || ""),
            h += '" value="',
            h += d(i),
            h += '"> <div class="sheet-item-text" >',
            h += d(f[i].text || f[i]),
            h += "</div> </div> ";
        return h += ' <div class="sheet-item sheet-cancle" value="-1" > <div class="sheet-item-text">',
        h += d(g || "取消"),
        h += "</div> </div> "
    }
    ;
    return a.frame = "TmplInline_actionsheet.frame",
    Tmpl.addTmpl(a.frame, b),
    a
}),
function(a, b) {
    a.ActionSheet = b(a.$, a.Tmpl)
}(this, function(a, b) {
    function c() {
        i = a('<div class="action-sheet"></div>'),
        h = a('<div class="action-sheet-mask"></div>'),
        a(document.body).append(i).append(h),
        h.on("tap", function() {
            f()
        }).on("webkitTransitionEnd", function() {
            h.hasClass("show") || h.hide()
        }),
        i.on("tap", ".sheet-item", e).on("webkitTransitionEnd", function() {
            i.hasClass("show") || (h.removeClass("show"),
            i.hide())
        }),
        k = !0
    }
    function d(d) {
        mqq.compare("4.7") >= 0 && !d.useH5 ? mqq.ui.showActionSheet({
            items: d.items,
            cancel: "取消"
        }, function(a, b) {
            0 === a ? d.onItemClick && d.onItemClick(b) : d.onCancel && d.onCancel(b)
        }) : (k || c(),
        j = d,
        document.body.style.overflow = "hidden",
        i.html(""),
        l.on("touchmove", g),
        b(window.TmplInline_actionsheet.frame, d).appendTo(i),
        h.show(),
        i.show(),
        setTimeout(function() {
            h.addClass("show"),
            i.addClass("show")
        }, 50)),
        d.cancle && a('.sheet-item[value="-1"] .sheet-item-text').html(d.cancle)
    }
    function e() {
        var b = a(this).attr("value");
        b = Number(b),
        -1 === b ? j.onCancel && j.onCancel() : j.onItemClick && j.onItemClick(b),
        f()
    }
    function f(a) {
        i && (a ? (h.hide().removeClass("show"),
        i.hide().removeClass("show")) : i.removeClass("show"),
        l.off("touchmove", g),
        document.body.style.overflow = "")
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
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_checkcode = b()
}(this, function() {
    var a = {}
      , b = function(a, b) {
        a = a || {};
        var c = "";
        return c += '<div class="checkcode-content"> <div class="close-checkcode"></div> <h3 class="content-title">请输入验证码</h3> <div class="content-middle"> <img class="content-img" src=""> <input class="content-input" id="inputCheckcode" placeholder="验证码" type="email" maxlength="6"/> <a class="content-button" href="javascript:;" id="submitCheckcode">确定</a> </div> </div> '
    }
    ;
    return a.frame = "TmplInline_checkcode.frame",
    Tmpl.addTmpl(a.frame, b),
    a
}),
function(a, b) {
    a.Checkcode = b(a.$, a.Tmpl)
}(this, function(a, b) {
    function c() {
        i = a('<div class="checkcode"></div>'),
        a(document.body).append(i),
        i.on("tap", function(a) {
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
        }, 500),
        i.show(),
        d(),
        i.find(".content-button").on("tap", function() {
            l(e, i.find("#inputCheckcode").val(), f, g)
        }),
        i.find(".content-img").on("tap", function() {
            d()
        })
    }
    function f() {
        var c = a('<div class="error-code alert"></div>')
          , e = window.TmplInline_alert.frame
          , f = {
            title: "",
            content: "验证码输入错误，请重新输入",
            confirm: "重新输入",
            cancel: "取消"
        };
        a(document.body).find(".error-code").remove(),
        a(document.body).append(c),
        b(e, f).appendTo(c),
        c.find(".btn").on("tap", function() {
            i.find("#inputCheckcode").focus(),
            i.find("#inputCheckcode").val(""),
            d(),
            c.hide()
        }),
        i.siblings(".error-code").find(".frame").css("top", "120px").css("position", "fixed")
    }
    function g() {
        i.hide(),
        i.find("#inputCheckcode").blur(),
        a(document).off("touchmove", h)
    }
    function h(a) {
        a.preventDefault()
    }
    var i, j = !1, k = {
        festival_verify: "/cgi-bin/bar/cwact/post/captcha/verify_v2",
        verify_v2: "/cgi-bin/bar/post/captcha/verify_v2",
        verify: "/cgi-bin/bar/post/captcha/verify",
        report: "/cgi-bin/bar/admin/jubao"
    }, l = function(b, c, d, e) {
        var h = {
            type: "POST",
            url: k[b],
            param: {
                vcode: c
            },
            succ: function(a) {
                0 === a.retcode ? (g(),
                d && d(a.result)) : f()
            },
            err: function() {
                f()
            }
        };
        "report" === b && (h.ssoCmd = "jubao"),
        a.extend(h.param, e),
        console.log("jubao cgi http:", h),
        DB.cgiHttp(h)
    }
    ;
    return {
        show: e
    }
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_publish = b()
}(this, function() {
    var a = {}
      , b = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("len")
          , f = c("list")
          , g = "";
        g += '<div class="pub-cates"> <div class="close-button"></div> ';
        for (var h = 0, e = f.length; e > h; h++)
            g += ' <div class="pub-cate-item" value="',
            g += d(f[h].value),
            g += '"><span class="pub-cate-text">',
            g += d(f[h].text),
            g += "</span></div> ";
        return g += " </div>"
    }
    ;
    a.category = "TmplInline_publish.category",
    Tmpl.addTmpl(a.category, b);
    var c = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("i")
          , f = c("j")
          , g = "";
        g += '<div class="ui-carousel js-slide" data-ride="carousel"> ';
        var e, f, h = 0;
        for (g += ' <ul class="ui-carousel-inner face-panel-wrap"> ',
        e = 1; 5 >= e; e++) {
            for (g += ' <li class="ui-carousel-item face-panel face-panel-',
            g += d(e),
            g += " ",
            g += d(1 === e ? "js-active" : ""),
            g += '" > ',
            f = 0; 21 > f; f++)
                g += ' <span index="',
                g += d(20 != f ? h++ : -1),
                g += '"></span> ';
            g += " </li> "
        }
        for (g += ' </ul> <ol class="ui-carousel-indicators"> ',
        e = 1; 5 >= e; e++)
            g += ' <li class="',
            g += d(1 === e ? "js-active" : ""),
            g += '"></li> ';
        return g += " </ol> </div>"
    }
    ;
    a.face_panel = "TmplInline_publish.face_panel",
    Tmpl.addTmpl(a.face_panel, c);
    var d = function(a, b) {
        a = a || {};
        var c = "";
        return c += '<div class="clip"><img src="" style=""/></div> <div class="up-mask"></div> <div class="up-progress"> <div class="pos"></div> </div> <a class="btn-del" href="javascript:void(0)" title="关闭">&nbsp;</a> '
    }
    ;
    a.preview = "TmplInline_publish.preview",
    Tmpl.addTmpl(a.preview, d);
    var e = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("replyMode")
          , f = c("config")
          , g = c("contentPlaceholder")
          , h = "";
        return h += ' <div class="pub-con"> <div class="pub-wrap section-1px ',
        h += d(e ? "edit-mode" : ""),
        h += '"> <div class="pub-theme section-1px ',
        h += d(f.needTitle ? "" : "hide"),
        h += '"> <input maxlength="200" spellcheck="false" class="ipt-theme" type="text" placeholder="标题，',
        h += d(f.minTitleLength),
        h += "-",
        h += d(f.maxTitleLength),
        h += '个字"/> </div> <div class="editor-outer"> <textarea spellcheck="false" class="editor" placeholder="',
        h += d(g),
        h += '"></textarea> <textarea class="cp-editor"></textarea> </div> ',
        f.needLocation && (h += ' <div class="pub-location text-overflow-ellipsis"> <span class="location-text">所在城市</span> </div> '),
        h += ' <div class="pub-line border-1px"></div> <ul class="pub-type"> ',
        f.needCategory && (h += ' <div class="pub-cate"> <span class="icon"></span><span class="cag-text">分类</span> </div> '),
        h += " ",
        f.needPhoto && (h += ' <li id="selectPic" class="pic-type" title="添加图片"></li> '),
        h += " ",
        f.needFace && (h += ' <li id="selectFace" class="pub-face" title="添加表情"></li> '),
        h += ' <li class="pub-flex"></li> ',
        f.needCancelBtn && (h += ' <li class="pub-btn "> <button class="pub-cancel">取消</button> </li> '),
        h += ' <li class="pub-btn "> <button class="pub-publish">发表</button> </li> <li class="loading "></li> <li class="pub-remain "> <p class="pub-remain-wording" style="display:none"></p> </li> </ul> ',
        f.needPhoto && (h += ' <ul class="pub-pics" > <li class="up-entry"> <input class="upfile up-entry-two" type="file" accept="image/*" multiple/> </li> </ul> '),
        h += " ",
        f.needFace && (h += ' <div class="pub-faces" > </div> '),
        h += " </div> </div> "
    }
    ;
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
        this.paused = this.sliding = this.interval = this.$active = this.$items = null ,
        this.swipeable()
    }
    var b = "js-active"
      , c = "slid:carousel"
      , d = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame;
    a.DEFAULTS = {
        interval: 5e3,
        wrap: !0
    },
    a.prototype.cycle = function(a) {
        return a || (this.paused = !1),
        this.interval && clearInterval(this.interval),
        this.options.interval && !this.paused && (this.interval = setInterval($.proxy(this.next, this), this.options.interval)),
        this
    }
    ,
    a.prototype.getActiveIndex = function() {
        return this.$active = this.$element.find(".ui-carousel-item." + b),
        this.$items = this.$active.parent().children(),
        this.$items.index(this.$active)
    }
    ,
    a.prototype.to = function(a) {
        var b = this
          , d = this.getActiveIndex();
        return a > this.$items.length - 1 || 0 > a ? void 0 : this.sliding ? this.$element.one(c, function() {
            b.to(a)
        }) : d == a ? this.pause().cycle() : this.slide(a > d ? "next" : "prev", $(this.$items[a]))
    }
    ,
    a.prototype.pause = function(a) {
        return a || (this.paused = !0),
        this.$element.find(".js-next, .js-prev").length && $.support.transition && (this.$element.trigger($.support.transition.end),
        this.cycle(!0)),
        this.interval = clearInterval(this.interval),
        this
    }
    ,
    a.prototype.next = function() {
        return this.sliding ? void 0 : this.slide("next")
    }
    ,
    a.prototype.prev = function() {
        return this.sliding ? void 0 : this.slide("prev")
    }
    ,
    a.prototype.slide = function(a, d) {
        var e = this.$element.find(".ui-carousel-item." + b)
          , f = d || e[a]()
          , g = "next" == a ? "left" : "right"
          , h = "next" == a ? "first" : "last"
          , i = this;
        if (!f.length) {
            if (!this.options.wrap)
                return;
            f = this.$element.find(".ui-carousel-item")[h]()
        }
        if (f.hasClass(b))
            return this.sliding = !1;
        var j = $.Event(c, {
            relatedTarget: f[0],
            direction: g
        });
        if (this.$element.trigger(j),
        !j.isDefaultPrevented()) {
            if (this.sliding = !0,
            this.pause(),
            this.$indicators.length && (this.$indicators.find("." + b).removeClass(b),
            this.$element.one(c, function() {
                var a = $(i.$indicators.children()[i.getActiveIndex()]);
                a && a.addClass(b)
            })),
            $.support.transition && this.$element.hasClass("js-slide") && !this.swiping) {
                var k = "js-" + g
                  , l = "js-" + a;
                f.addClass(l),
                f[0].offsetWidth,
                e.addClass(k),
                f.addClass(k),
                e.one($.support.transition.end, function() {
                    f.removeClass([l, k].join(" ")).addClass(b),
                    e.removeClass([b, k].join(" ")),
                    i.sliding = !1,
                    setTimeout(function() {
                        i.$element.trigger(c)
                    }, 0)
                }),
                $.os.ios && e.emulateTransitionEnd(1e3 * e.css("transition-duration").slice(0, -1))
            } else
                e.removeClass(b),
                f.addClass(b),
                this.sliding = !1,
                this.$element.trigger(c);
            this.cycle(),
            this.$center = f;
            var m = function() {
                var b = f[a]();
                if (!b.length) {
                    if (!i.options.wrap)
                        return;
                    b = i.$element.find(".ui-carousel-item")[h]()
                }
                return b
            }();
            return this.$left = "next" == a ? e : m,
            this.$right = "next" == a ? m : e,
            this
        }
    }
    ,
    a.prototype.swipeable = function() {
        function a(a) {
            return a.targetTouches && a.targetTouches.length >= 1 ? a.targetTouches[0].clientX : a.clientX
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
            Math.abs(a) > p && (a = 0 > a ? -p : p),
            v.$left && v.$right && v.$left[0] === v.$right[0] ? (a > 0 ? v.$left[0].style[s] = "translate(" + (a - p) + "px, 0)" : v.$right[0].style[s] = "translate(" + (a + p) + "px, 0)",
            v.$center[0].style[s] = "translate(" + a + "px, 0)") : (v.$left && (v.$left[0].style[s] = "translate(" + (a - p) + "px, 0)"),
            v.$center[0].style[s] = "translate(" + a + "px, 0)",
            v.$right && (v.$right[0].style[s] = "translate(" + (a + p) + "px, 0)"))
        }
        function f() {
            var a, b;
            if (i) {
                if (a = Date.now() - m,
                b = i * Math.exp(-a / r),
                b > 10 || -10 > b)
                    return e(j - b),
                    d(f);
                x = !1,
                e(0),
                v.cycle(),
                $(document).trigger("carousel_end"),
                [v.$left, v.$right].forEach(function(a) {
                    a && (a[0].style[s] = "translate(0, 0)",
                    a.removeClass("js-show"))
                }),
                t && v.slide(u),
                v.swiping = !1,
                v.$right || $(document).trigger("wsq_carousel_end")
            } else
                x = !1
        }
        var g = this.$element.find(".ui-carousel-item." + b);
        this.$center = g,
        this.options.wrap ? this.$left = this.$element.find(".ui-carousel-item").last() : this.$left = null ,
        this.$right = g.next();
        var h, i, j, k, l, m, n, o = 0, p = this.containerWidth, q = !1, r = 125, s = "webkitTransform", t = !1, u = "", v = this, w = !1, x = !1;
        this.$element.on("touchstart", function(b) {
            return x ? void b.preventDefault() : ($(document).trigger("carousel_touchstart"),
            w = !1,
            v.sliding || (q = !0,
            h = a(b),
            k = i = 0,
            l = o,
            m = Date.now(),
            clearInterval(n),
            n = setInterval(c, 100),
            v.pause(),
            v.$left && v.$left.addClass("js-show"),
            v.$right && v.$right.addClass("js-show"),
            e(1)),
            void b.preventDefault())
        }),
        this.$element.on("touchmove", function(b) {
            if (!x) {
                var c, d;
                if (q && (c = a(b),
                d = h - c,
                d > 2 || -2 > d)) {
                    if (!v.options.wrap && d > 0 && !v.$right || 0 > d && !v.$left)
                        return;
                    w = !0,
                    h = c,
                    e(o + d)
                }
            }
        }),
        this.$element.on("touchend", function(a) {
            x || v.sliding || (q = !1,
            x = !0,
            clearInterval(n),
            j = o,
            (k > 10 || -10 > k) && (i = 1.2 * k,
            j = o + i),
            j = Math.round(j / p * 3) * p,
            j = -p > j ? -p : j > p ? p : j,
            i = j - o,
            m = Date.now(),
            v.swiping = !0,
            t = 0 !== j,
            u = o > 0 ? "next" : "prev",
            f(),
            w && (w = !1))
        })
    }
    ,
    $.Carousel = a,
    $.fn.carousel = function(b) {
        return this.each(function() {
            var c = $(this)
              , d = c.data("carousel")
              , e = $.extend({}, a.DEFAULTS, c.data(), "object" == typeof b && b)
              , f = "string" == typeof b ? b : e.slide;
            d || c.data("carousel", d = new a(this,e)),
            "number" == typeof b ? d.to(b) : f ? d[f]() : e.interval && d.pause().cycle()
        })
    }
}(),
function(a, b) {
    a.PublishFace = b()
}(this, function() {
    function a(a) {
        i = a.facePanel,
        j = a.input,
        k = a.onSelect,
        "undefined" != typeof a.needRenderTmpl && (o = a.needRenderTmpl),
        "undefined" != typeof a.needBindCarousel && (p = a.needBindCarousel),
        m = !1,
        n = !1
    }
    function b() {
        o && i.html(Tmpl(TmplInline_publish.face_panel, {}).toString()),
        i.on("tap", function(a) {
            var b = a.target;
            if ("SPAN" === b.tagName) {
                var e = Number(b.getAttribute("index"));
                isNaN(e) || (-1 === e ? d() : c(e))
            }
        }),
        p && i.find(".ui-carousel").carousel({
            interval: 0,
            wrap: !1,
            containerWidth: 256
        }),
        n = !0
    }
    function c(a) {
        var b = j.get(0)
          , c = l[a];
        if (c) {
            var d = b.value;
            c = "/" + c;
            var e = b.selectionStart
              , f = b.selectionEnd;
            b.value = d.substring(0, e) + c + d.substring(f),
            b.selectionStart = b.selectionEnd = e + c.length,
            b.blur(),
            j.trigger("insertFace")
        }
    }
    function d() {
        var a = j.get(0)
          , b = a.value
          , c = a.selectionStart
          , d = a.selectionEnd;
        if (c === d) {
            var e = b.substring(0, c).match(/\/([^\/]+)$/);
            e && l.indexOf(e[1]) > -1 ? (c = e.index,
            a.value = b.substring(0, c) + b.substring(d),
            a.selectionStart = a.selectionEnd = c) : (a.value = b.substring(0, c - 1) + b.substring(d),
            a.selectionStart = a.selectionEnd = c - 1)
        } else
            a.value = b.substring(0, c) + b.substring(d),
            a.selectionStart = a.selectionEnd = c;
        a.blur(),
        j.trigger("deleteFace")
    }
    function e() {
        n || b(),
        i.show(),
        m = !0
    }
    function f() {
        n || b()
    }
    function g() {
        i.hide(),
        m = !1
    }
    function h() {
        return m
    }
    var i, j, k, l = Config.FACE_2_TEXT_FOR_PUBLISH, m = !1, n = !1, o = 1, p = 1;
    return {
        init: a,
        show: e,
        hide: g,
        isShow: h,
        bind: f
    }
}),
function(a, b) {
    a.Publish = b(a.$, a.DB, a.Upload)
}(this, function() {
    function a(a) {
        for (var b in sa)
            if (sa[b].type === a)
                return sa[b].name;
        return null 
    }
    function b(b) {
        za = b;
        var c = a(b);
        b ? (ua.selectCateBtn.addClass("selected"),
        ua.selectCateText.html(c)) : (ua.selectCateBtn.removeClass("selected"),
        ua.selectCateText.html("分类"))
    }
    function c(a) {
        a.preventDefault(),
        document.body.scrollTop = ha.css("top").replace("px", "")
    }
    function d(a) {
        Aa && Ba ? a(Aa, Ba) : mqq.sensor.getLocation(function(b, c, d) {
            0 === b && (Aa = parseInt(1e6 * (c || 0), 10),
            Ba = parseInt(1e6 * (d || 0), 10)),
            a(Aa, Ba)
        })
    }
    function e(a) {
        ga ? a(ga) : d(function(b, c) {
            return b && c ? void h({
                start: 0,
                num: 1
            }, function(b) {
                0 === b.code ? (Ca = b.poilist.length ? b.poilist[0].uid : 0,
                Da = b.poilist.length ? b.poilist[0].city : "",
                a({
                    code: 0,
                    city: Da,
                    building: b.poilist.length ? b.poilist[0].name : ""
                })) : a(b)
            }, !0) : a({
                code: -1,
                msg: "网络异常，无法定位，请检查网络后重试"
            })
        })
    }
    function f() {
        var a = ua.locationBtn;
        a.html("正在定位..."),
        e(function(b) {
            0 === b.code ? (a.html(b.city.replace("市", "") + " · " + b.building),
            ua.locationBtn && ua.locationBtn.addClass("located")) : (Tip.show(b.msg, {
                type: "warning"
            }),
            a.html("所在城市"))
        })
    }
    function g() {
        ga = null ,
        Aa = Ba = 0;
        var a = ua.locationBtn;
        a.html("所在城市"),
        ua.locationBtn.removeClass("located"),
        Ca = 0,
        Da = "",
        ka = !1,
        la = !1,
        Ea = 0,
        ia.empty()
    }
    function h(a, b, c) {
        $.isFunction(a) && (b = a,
        a = null ),
        la = !0;
        var d = {
            type: "GET",
            url: "/cgi-bin/bar/user/poilist",
            param: {
                lat: Aa,
                lon: Ba,
                coordinate: 1,
                start: a ? a.start : Ea,
                num: a ? a.num : Fa
            },
            succ: function(a) {
                var d = a.result || {};
                d.poilist && d.poilist.length ? (d.code = 0,
                c !== !0 && (ja.hide(),
                i(d.poilist),
                Ea += Fa)) : d = {
                    code: -2,
                    msg: "网络异常，无法定位，请检查网络后重试"
                },
                ka = d.isend || 0,
                b && b(d),
                la = !1
            },
            err: function(a) {
                b && b({
                    code: a.retcode,
                    msg: "服务器繁忙，请稍后再试"
                }),
                la = !1
            }
        };
        DB.cgiHttp(d)
    }
    function i(a) {
        for (var b, c, d = "", e = 0, f = a.length; f > e; e++)
            b = parseInt(1e6 * (a[e].latitude || 0), 10),
            c = parseInt(1e6 * (a[e].longitude || 0), 10),
            d += '<li data-uid="' + a[e].uid + '" data-lat="' + b + '" data-lon="' + c + '"><a href="javascript:;"><span class="poi-building text-overflow-ellipsis">' + a[e].name + '</span><span class="poi-addr text-overflow-ellipsis">' + a[e].addr + "</span></a></li>";
        $(d).appendTo(ia)
    }
    function j() {
        ha = $('<div class="pub-float pub poi-list-mask"></div>'),
        ha.hide().appendTo(document.body).html('<div id="poiLoading" class="poi-loading spinner"> 正在加载中...</div><ul id="poiList" class="poi-list"></ul>'),
        $.os.ios && bouncefix.add("poi-list-mask"),
        ja = $("#poiLoading"),
        ia = $("#poiList")
    }
    function k() {
        ua.locationBtn.off().on("tap", function() {
            Ma(11),
            ua.locationBtn.hasClass("located") ? ($.os.ios && (ua.iptTitle[0].blur(),
            ua.editor[0].blur()),
            window.setTimeout(function() {
                ActionSheet.show({
                    items: ["修改地点", "删除地点"],
                    onItemClick: function(a) {
                        if (0 === a) {
                            $(document.body).css("overflow", "hidden"),
                            $(document).on("scroll", c);
                            var b = Util.getHash("poi");
                            "show" !== b && (window.location.hash += "&poi=show"),
                            p(),
                            ha.show(),
                            ia.find("li").length < 1 && (Ea = 0,
                            h(function(a) {
                                a.code && ja.text(a.msg + "[" + a.code + "]")
                            }))
                        } else
                            1 === a && g()
                    }
                })
            }, 0)) : f()
        }),
        ia.on("tap", K),
        ha.on("scroll", L)
    }
    function l() {
        $(document).off("scroll", c),
        $(document.body).css("overflow", ""),
        Ea = 0,
        ha && ha.hide()
    }
    function m() {
        $(document).off("scroll", c),
        $(document.body).css("overflow", ""),
        Ea = 0,
        ia && ia.off("tap", K),
        ha && ha.off("scroll", L).remove()
    }
    function n(a) {
        a.preventDefault()
    }
    function o() {
        $(document).on("touchmove", n)
    }
    function p() {
        $(document).off("touchmove", n)
    }
    function q() {
        Refresh && Refresh.pauseTouchMove(),
        ua.btnPublish.on("tap", w).on("touchend", function(a) {
            a.preventDefault()
        }),
        ua.$btnCancel.on("tap", u).on("touchend", function(a) {
            a.preventDefault()
        });
        var a = ua.upfileOne;
        a && a.on("change", E).on("touchstart", function(a) {
            if (Ma(6),
            Pa)
                return void a.preventDefault();
            if (mqq && mqq.media && mqq.media.getPicture && Ka) {
                a.preventDefault();
                var b = A()
                  , c = b.imgs;
                Ia.max = Ga - c,
                mqq.media.getPicture(Ia, function(a, b) {
                    0 === a && E(b)
                })
            }
        });
        var c = ua.upfileTwo;
        c && c.on("change", E).on("click", function(a) {
            if (Ma(6),
            Pa)
                return void a.preventDefault();
            if (mqq && mqq.media && mqq.media.getPicture && Ka) {
                a.preventDefault();
                var b = A()
                  , c = b.imgs;
                Ia.max = Ga - c,
                mqq.media.getPicture(Ia, function(a, b) {
                    0 === a && E(b)
                })
            }
        });
        var d = ua.pubPics;
        if (d && (d.on("touchend", y),
        d.on("tap", z)),
        "pub" === X) {
            var e = $(".pub-cate");
            ua.selectCateBtn = e,
            ua.selectCateText = e.find(".cag-text"),
            "nearby" === ma ? e.on("tap", function() {
                ua.editor.blur(),
                ua.iptTitle.blur(),
                $("#publish-panel").hide(),
                p(),
                window.NearbyPublish && window.NearbyPublish.show()
            }) : e.on("tap", function() {
                Ma(10),
                window.setTimeout(function() {
                    ActionSheet.show({
                        items: ea,
                        onItemClick: function(a) {
                            a = sa[a].name || 0,
                            b(a)
                        }
                    })
                }, 0)
            })
        }
        ua.selectPhotoBtn && ua.selectPhotoBtn.on("tap", function() {
            ua.$selectFaceBtn && (window.PublishFace && window.PublishFace.hide(),
            ua.$selectFaceBtn.removeClass("active")),
            ua.editor.blur(),
            ua.iptTitle.blur(),
            ua.selectPhotoBtn.addClass("active"),
            ua.pubPics.show()
        }),
        ua.$selectFaceBtn && ua.$selectFaceBtn.on("tap", function() {
            ua.selectPhotoBtn && (ua.selectPhotoBtn.removeClass("active"),
            ua.pubPics.hide()),
            ua.editor.blur(),
            ua.iptTitle.blur(),
            ua.$selectFaceBtn.addClass("active"),
            window.PublishFace && window.PublishFace.show()
        }),
        ua.editor.on("input", s),
        o(),
        _.needLocation && k()
    }
    function r(a) {
        return ("" + a).length
    }
    function s() {
        if (ua.cpEditor.val(ua.editor.val()),
        ua.editor.css("height", Math.min(72, ua.cpEditor[0].scrollHeight + ua.cpEditor.css("height")) + "px"),
        "pub" === X) {
            var a = r($.trim(ua.editor.val()))
              , b = _.maxLength - a;
            ua.remainTxt.html(b),
            0 > b ? ua.remainTxt.css("display", "inline-block") : ua.remainTxt.hide(),
            ua.remainTxt[0 > b ? "addClass" : "removeClass"]("red")
        }
    }
    function t() {
        ua.$btnCancel && ua.$btnCancel.off("tap", u),
        ua.btnPublish.off("tap", w),
        ua.upfileOne && ua.upfileOne.off("change", E),
        ua.upfileTwo.off("change", E),
        ua.pubPics.off("touchend", z),
        ua.editor.off("input", s),
        p(),
        Refresh && Refresh.restoreTouchMove()
    }
    function u() {
        $.os.ios && setTimeout(function() {
            document.body.scrollTop = 0
        }, 0),
        Publish.destroy(),
        qa.onhidden && qa.onhidden(),
        Ma(13)
    }
    function v(a, b, c) {
        var d = {
            bid: da.bid || 10020,
            lat: Aa,
            lon: Ba,
            coordinate: 1,
            uid: Ca
        };
        c.params && $.extend(d, c.params);
        var e = a
          , f = b
          , g = [];
        ta.forEach(function(a) {
            var b = xa[a]
              , c = b.metaData;
            0 === b.state && c && g.push(c)
        });
        var h = {
            type: "POST",
            url: "/cgi-bin/bar/post/publish_v2",
            param: d,
            succ: function(a) {
                a.result.sms_vflag ? (a.result.sms_has_phone && (oa = a.result.sms_phone),
                window.Complain.init({
                    isInsidePage: !0,
                    userDefaultPhoneNum: oa,
                    type: da.cid ? 3 : da.pid ? 2 : 1,
                    code: a.result.code,
                    onSuccess: function(b) {
                        $.extend(a.result, b),
                        c.callback && c.callback(a)
                    }
                })) : a.result.vflag ? Checkcode.show("verify_v2", function(b) {
                    $.extend(a.result, b),
                    c.callback && c.callback(a)
                }, {
                    type: da.cid ? 3 : da.pid ? 2 : 1,
                    code: a.result.code
                }) : c.callback && c.callback(a)
            },
            err: function(a) {
                var b = "";
                100006 === a.retcode && parseInt(mqq.QQVersion) > 0 && mqq.compare("5.2") < 0 ? Alert.show("", "抱歉！由于您的手机QQ版本过低，\n敬请升级后再使用", {
                    cancel: "确认",
                    confirm: "立即升级",
                    confirmAtRight: !0,
                    callback: function() {
                        mqq.ui.openUrl({
                            url: "http://im.qq.com/immobile/index.html",
                            target: 1,
                            style: 3
                        })
                    }
                }) : (b = 99999 === a.retcode ? "您的操作太频繁，请稍后再试" : 214 === a.retcode ? "内容中含有敏感词汇，请重新输入" : 215 === a.retcode ? "标题中含有敏感词汇，请重新输入" : 4002 === a.retcode ? "发表内容重复度太高，请重新输入" : 100117 === a.retcode ? "内容字数不满足要求" : 100118 === a.retcode ? "标题字数不满足要求" : 10000188 === a.retcode ? "内容中含有恶意链接，请重新输入" : 10 === a.retcode || 4008 === a.retcode ? "该评论或该话题已被删除" : 100600 === a.retcode ? "您暂时无权限在本部落发帖" : a.msg ? a.msg : "发表失败，请稍后再试",
                Tip.show(b, {
                    type: "warning"
                })),
                Ma(8),
                wa = 0
            }
        };
        da.cid ? (d.pid = da.pid,
        d.cid = da.cid,
        d.comment = encodeURIComponent(JSON.stringify({
            content: f
        })),
        da.rid && (d.target_rid = da.rid),
        h.url = "/cgi-bin/bar/post/recomment") : da.pid ? (d.pid = da.pid,
        da.ref_cid && (d.ref_cid = da.ref_cid),
        d.comment = encodeURIComponent(JSON.stringify({
            content: f,
            pic_list: g
        })),
        h.url = "/cgi-bin/bar/post/comment_v2") : (d.title = encodeURIComponent(e),
        d.post = encodeURIComponent(JSON.stringify({
            content: f,
            pic_list: g
        }))),
        DB.cgiHttp(h)
    }
    function w() {
        if (!wa && x() && ya) {
            var a = $(this);
            if (!Z) {
                wa = 1,
                a.css("opacity", "0.4"),
                za && (b.type = za);
                var b = {};
                da.father_pid && (b.father_pid = da.father_pid),
                da.subParam && $.extend(b, da.subParam),
                v($.trim(ua.iptTitle.val()), $.trim(ua.editor.val()), {
                    params: b,
                    callback: function(b) {
                        Na(a, b)
                    }
                }),
                ("reply" === X || "comment_reply" === X || "nearby" === ma) && Ma(12)
            }
        }
    }
    function x() {
        var a = da.flag;
        if (_.needTitle) {
            var b = $.trim(ua.iptTitle.val())
              , c = r(b);
            if (!b)
                return Tip.show("请输入话题", {
                    type: "warning"
                }),
                Ma(7),
                void ua.iptTitle.focus();
            if (c > _.maxTitleLength)
                return Tip.show("标题字数不能超过" + _.maxTitleLength + "个汉字", {
                    type: "warning"
                }),
                Ma(7),
                void ua.iptTitle.focus();
            if (c < _.minTitleLength)
                return Tip.show("标题字数至少" + _.minTitleLength + "个汉字", {
                    type: "warning"
                }),
                Ma(7),
                void ua.iptTitle.focus()
        }
        var d = A()
          , e = $.trim(ua.editor.val())
          , f = r(e);
        if (f > _.maxLength)
            return Tip.show("内容字数不能超过" + _.maxLength + "个汉字", {
                type: "warning"
            }),
            Ma(7),
            Ma(16),
            void ua.editor.focus();
        if (_.photoOrContent) {
            if (f < _.minLength && !d.imgs)
                return Tip.show("输入至少" + _.minLength + "个汉字或图片", {
                    type: "warning"
                }),
                Ma(7),
                void ua.editor.focus()
        } else if (f < _.minLength)
            return Tip.show("输入至少" + _.minLength + "个汉字", {
                type: "warning"
            }),
            Ma(7),
            Ma(16),
            void ua.editor.focus();
        return "pho_detail" !== ma || d.imgs ? "pub" === X && _.needPhoto && 1 & a && !d.imgs ? (Tip.show("本部落为图片部落，发表话题必须带图", {
            type: "warning"
        }),
        void Ma(7)) : d.uping ? (Tip.show("图片上传中，请稍候", {
            type: "warning"
        }),
        void Ma(7)) : 0 === da.bid ? void Tip.show("请先选择部落再发表话题", {
            type: "warning"
        }) : !0 : (Tip.show("参与晒图,发表话题必须带图哦", {
            type: "warning"
        }),
        void Ma(7))
    }
    function y(a) {
        var b = $(a.target)
          , c = b.parent(".up-pic")
          , d = parseInt(c.attr("data-gid"));
        b.hasClass("btn-del") && (Pa = !0,
        setTimeout(function() {
            Pa = !1
        }, 500),
        c.remove(),
        delete xa[d],
        ta = ta.filter(function(a) {
            return a !== d
        }),
        Oa = !0,
        B())
    }
    function z(a) {
        return Oa ? (Oa = !1,
        a.preventDefault(),
        !1) : void 0
    }
    function A() {
        var a = 0
          , b = 0
          , c = 0;
        for (var d in xa)
            if (xa.hasOwnProperty(d)) {
                var e = xa[d].state;
                1 === e ? b++ : 2 === e && c++,
                a++
            }
        return {
            imgs: a - c,
            uping: b,
            err: c
        }
    }
    function B() {
        var a = ua.upfileOne;
        if (a) {
            var b = A();
            b.imgs >= Ga ? a.attr("disabled", "disabled") : a.removeAttr("disabled")
        }
    }
    function C(a) {
        return Array.prototype.slice.call(a)
    }
    function D(a, b) {
        if (b)
            return 0 === a.match ? !0 : !1;
        var c = a.type
          , d = a.name
          , e = d.split(".")
          , f = e[e.length - 1].toLowerCase();
        return c && !/^image/.test(c) ? !1 : a.size > 5242880 ? !1 : "mp4" === f ? !1 : !0
    }
    function E(a) {
        var b = this.files
          , c = []
          , d = !1;
        a = a || [],
        a.length > 0 && (b = a,
        d = !0),
        $.isArray(b) || (b = C(b));
        var e = ua.upfileOne
          , f = ua.upfileTwo;
        if (e) {
            var g = A()
              , h = g.imgs + b.length;
            h > Ga && (b.length = Ga - g.imgs,
            Alert.show("一次最多上传" + Ga + "张图片", "", {
                confirm: "我知道了"
            })),
            h >= Ga ? e.attr("disabled", "disabled") : e.removeAttr("disabled")
        }
        if (b && b.length > 0) {
            var i, j = ca.find(".pub-wrap"), k = j.find(".pub-pics"), l = va;
            e.css("pointer-events", "none"),
            f.css("pointer-events", "none"),
            j.addClass("img-preview"),
            k.show(),
            ua.selectPhotoBtn.addClass("active"),
            ua.$selectFaceBtn && (ua.$selectFaceBtn.removeClass("active"),
            window.PublishFace && window.PublishFace.hide()),
            $("input.upfile").val("");
            for (var m = 0; m < b.length; m++)
                D(b[m], d) && (c.push(b[m]),
                i = $('<li class="up-pic" id="upPic' + l + '"></li>'),
                Tmpl(window.TmplInline_publish.preview, {}).appendTo(i),
                i.insertBefore(k.children().last()),
                l++);
            G(c, d)
        }
    }
    function F() {
        var a = ua.upfileOne
          , b = ua.upfileTwo;
        a.css("pointer-events", "auto"),
        b.css("pointer-events", "auto")
    }
    function G(a, b) {
        var c = a[0];
        if (!c)
            return void F();
        var d = va;
        va++;
        var e = $("#upPic" + d)
          , f = {
            canUpload: function() {
                xa[d] = {},
                ta.push(d),
                c.gid = d,
                xa[d].state = 1
            },
            complete: function(c, e) {
                var f = 38;
                $("input.upfile").val("");
                var g = xa[d].dom.find("div.pos");
                g.css("-webkit-transition", "all 1s ease-out"),
                g.css("width", f + "px"),
                setTimeout(function() {
                    a.shift(),
                    G(a, b);
                    var f, g = xa[d];
                    if (g)
                        if (0 === c.retcode) {
                            f = c.result,
                            g.state = 0,
                            g.metaData = {
                                url: f.url,
                                w: f.w,
                                h: f.h
                            };
                            var h = e.type;
                            h && (h = h.split(/\//),
                            h = h && h[1] || "",
                            h = h.toLowerCase(),
                            "gif" === h && (g.metaData.t = h)),
                            g.dom.addClass("up-over")
                        } else
                            g.state = 2,
                            g.dom.addClass("up-error")
                }, 1500)
            },
            progress: function() {},
            error: function() {
                var c = xa[d];
                c.state = 2,
                c.dom.find("img").hide(),
                c.dom.addClass("up-error"),
                a.shift(),
                G(a, b)
            },
            abort: function() {
                var c = xa[d];
                c.state = 2,
                c.dom.find("img").hide(),
                c.dom.addClass("up-error"),
                a.shift(),
                G(a, b)
            },
            compress: function(a) {
                a.gid = d,
                H(a, e);
                var b = 38
                  , c = xa[d].dom
                  , f = c.find("div.pos");
                f.css("-webkit-transition", "all 2.5s ease-out"),
                f.css("width", 3 * b / 4 + "px")
            }
        }
          , g = {
            callbacks: f,
            param: {
                type: 2
            }
        };
        Ka ? na ? (Upload(na[0], g),
        na = null ) : mqq.invoke("media", "getLocalImage", {
            outMaxWidth: 480,
            outMaxHeight: 480,
            inMinWidth: 50,
            inMinHeight: 50,
            imageID: c.imageID,
            callback: mqq.callback(function(a, c) {
                0 === a && (b && (c.isNative = !0),
                Upload(c, g))
            })
        }) : (b && (c.isNative = !0),
        Upload(c, g))
    }
    function H(a, b) {
        var c, d, e = a.gid;
        a.w < a.h ? (c = parseInt(65 / a.w * a.h),
        d = {
            width: 65,
            height: c,
            margin: (c - 65) / 2,
            dir: "top"
        }) : (c = parseInt(65 / a.h * a.w),
        d = {
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
        xa[e].dom = b
    }
    function I(a, b) {
        return a += "",
        new Array(b > a.length ? b - a.length + 1 : 0).join(0) + a
    }
    function J(a) {
        ea = [],
        sa = [];
        for (var b = 0; b < a.length; b++)
            ea.push(a[b].name),
            sa.push({
                name: a[b].name,
                type: a[b].type
            })
    }
    function K(a) {
        a.preventDefault();
        var b = $(a.target).closest("li")
          , c = b.find(".poi-building").html()
          , d = b.find(".poi-addr").html()
          , e = ua.locationBtn;
        0 === c.indexOf(Da) && (c = c.slice(Da.length)),
        -1 === d.indexOf(Da) ? e.html(c) : e.html(Da.replace("市", "") + " · " + c),
        Aa = b.attr("data-lat"),
        Ba = b.attr("data-lon"),
        Ca = b.attr("data-uid"),
        o(),
        ha.hide(),
        window.history.go(-1)
    }
    function L(a) {
        Ra && window.clearTimeout(Ra);
        var b = this;
        Ra = window.setTimeout(function() {
            M.call(b, a)
        }, 100)
    }
    function M(a) {
        if (a.preventDefault(),
        ka || la)
            return !1;
        var b = document.documentElement.clientHeight
          , c = ha[0]
          , d = c.scrollTop
          , e = c.scrollHeight;
        d + 2 * b + 20 >= e && h()
    }
    function N() {
        $(".pic-type").hide(),
        $(".up-entry").hide()
    }
    function O(a, b) {
        V[a] = $.extend({}, V[a], b)
    }
    function P(a) {
        return mqq && parseInt(mqq.QQVersion) > 0 && mqq.compare("5.2") < 0 ? void Alert.show("", "抱歉！由于您的手机QQ版本过低，\n敬请升级后再使用", {
            cancel: "确认",
            confirm: "立即升级",
            confirmAtRight: !0,
            callback: function() {
                mqq.ui.openUrl({
                    url: "http://im.qq.com/immobile/index.html",
                    target: 1,
                    style: 3
                })
            }
        }) : (a = a || {},
        X = a.pubulishType || "pub",
        _ = $.extend({}, U, V[X], a.config || {}),
        da = $.extend({}, a),
        da.replyMode = "pub" !== X,
        !$.os.android || 0 !== $.os.version.indexOf("4.4.1") && 0 !== $.os.version.indexOf("4.4.0") || (Ja = !0),
        mqq && mqq.compare("4.7.2") >= 0 && (Ka = !0),
        qa = a,
        fa = a.isReply,
        a.onhidden && (qa.onhidden = a.onhidden,
        qa.ondestroy = qa.onhidden),
        void (Qa || (Y = {
            pub: "pub_page",
            reply: "reply_page",
            comment_reply: "two_comment"
        }[X],
        ma = a.fromType || "home",
        "nearby" === ma && (Y = "nearby_page"),
        "comment_reply" === X && (pa = "detail" === a.page ? "floor" === a.action ? 1 : 0 : "floor" === a.action ? 3 : 2),
        pa = a.postType,
        Ea = 0,
        ya = !1,
        "nearby" === ma ? S(a) : Va(function() {
            a.preventDefaultUI || (S(a),
            a.outsideFile ? E(a.outsideFile) : a.outsideBase64Obj && (na = a.outsideBase64Obj,
            E(na))),
            p()
        }))))
    }
    function R(a, b) {
        var c = b.add_credits
          , d = b.level && b.level.level_title
          , e = b.new_level;
        a = parseInt(a);
        var f = mqq.compare("5.3") > -1;
        1 === a ? (mqq.iOS || mqq.android && f) && ("barindex" === ma ? localStorage.setItem("upgrade_tip_info", e + "|" + c + "|" + d) : Tip.show("发表成功" + (c > 0 ? "，经验值+" + c : ""), {
            type: "ok"
        })) : f && Tip.show("回复成功" + (c > 0 ? "，经验值+" + c : ""), {
            type: "ok"
        })
    }
    function S(a) {
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
        }
        ;
        if ("comment_reply" === X ? b = "回复 " + c(a.nick_name) : (b = "内容，" + _.minLength + "-" + _.maxLength + "个字",
        2 & a.flag && (b += "(本部落所有发言将匿名发表)")),
        mqq && mqq.compare("5.0") > -1 && !a.webOnly) {
            var d = {
                pub: "GroupTribePublish",
                reply: "GroupTribeReply",
                comment_reply: "GroupTribeComment"
            }[X];
            if ("comment_reply" === X)
                b = "回复 " + a.nick_name;
            else if ("reply" === X)
                b = "发表评论";
            else {
                b = "内容，" + _.minLength + "-" + _.maxLength + "个字";
                var e = "";
                aa && aa.requireType && (e = "必须带" + {
                    1: "图片",
                    2: "录音",
                    4: "音乐",
                    8: "视频"
                }[aa.requireType]),
                2 & a.flag ? b += "(本部落所有发言将匿名发表" + (e ? "，且" + e : "") + ")" : e && (b += "(本部落" + e + ")")
            }
            var f = _;
            f.maxContentLength = _.maxLength,
            f.minContentLength = _.minLength,
            f.titlePlaceholder = "标题，" + _.minTitleLength + "-" + _.maxTitleLength + "个字",
            f.contentPlaceholder = b,
            f.bid = a.bid,
            f.pid = a.pid,
            f.cid = a.cid,
            f.rid = a.rid,
            f.flag = a.flag,
            f.barName = a.barName,
            f.needCategory = !1,
            $(document.body).addClass("native-" + X),
            $.extend(f, aa),
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
                    $(document.body).removeClass("native-" + X);
                    var c = a.succ;
                    if (Publish.destroy(),
                    0 === b.code) {
                        Ma(9),
                        "string" == typeof b.data && (b.data = JSON.parse(b.data));
                        var d = a.cid ? 3 : a.pid ? 2 : 1;
                        if (b.data.result.vflag) {
                            var e = "verify_v2";
                            Checkcode.show(e, function(a) {
                                $.extend(b.data.result, a),
                                R(d, b.data.result),
                                c && c(b.data.result)
                            }, {
                                type: d,
                                code: b.data.result.code
                            })
                        } else
                            R(d, b.data.result),
                            c && c(b.data.result)
                    } else
                        Ma(-1 === b.code ? 13 : 8)
                }, !1, !0)
            };
            return "comment_reply" === X && (g.viewType = "popWindow"),
            void mqq.ui.openView(g)
        }
        var h = {
            flag: a.flag,
            replyMode: da.replyMode,
            personal: $.storage.get("personal") || {},
            config: _,
            contentPlaceholder: b
        };
        ca = $('<div id="publish-panel" class="pub-float ' + X.replace(/_/g, "-") + '" />'),
        $(document.body).append(ca),
        ca.html(new Tmpl(window.TmplInline_publish.publish,h).toString()),
        ua = {
            $btnCancel: $(".pub-cancel"),
            btnPublish: $(".pub-publish"),
            upfileOne: $(".up-entry-one"),
            locationBtn: $(".location-text"),
            selectPhotoBtn: $("#selectPic"),
            upfileTwo: $(".up-entry-two"),
            pubPics: $(".pub-pics"),
            pubWrap: ca.find(".pub-wrap"),
            iptTitle: ca.find(".ipt-theme"),
            editor: ca.find(".editor"),
            cpEditor: ca.find(".cp-editor"),
            remainTxt: ca.find(".pub-remain-wording")
        };
        var i = Math.max(document.body.clientHeight, document.documentElement.clientHeight, window.innerHeight);
        if (ca.css("height", i + "px"),
        ua.pubWrap.addClass("show"),
        Ja && !Ka && N(),
        /android 2.3/i.test(navigator.userAgent) && navigator.userAgent.match(/\/qqdownloader\/(\d+)?/) && N(),
        _.needFace && (ua.$selectFaceBtn = $("#selectFace"),
        ua.$facePanel = $(".pub-faces"),
        window.PublishFace && window.PublishFace.init({
            facePanel: ua.$facePanel,
            input: ua.editor
        })),
        _.needPhoto && "pub" === X && ua.selectPhotoBtn.addClass("active"),
        "nearby" !== ma)
            if (fa) {
                var k = ua.editor[0];
                $.os.ios && k && k.focus()
            } else {
                var l = ua.iptTitle[0];
                $.os.ios && l && l.focus()
            }
        Ya(a.ctxNode),
        j(),
        q()
    }
    function T(a) {
        ca && (t(),
        ca.remove(),
        m(),
        ca = null ,
        ta = [],
        xa = {},
        a || da.ctxNode && da.ctxNode.show(),
        wa = 0,
        ua = {}),
        ActionSheet.hide(),
        qa.ondestroy && qa.ondestroy()
    }
    var U = {
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
    }
      , V = {
        pub: {
            needLocation: !0,
            needCategory: !0,
            needPhoto: !0,
            needTitle: !0
        },
        reply: {
            needLocation: !1,
            needPhoto: !0,
            photoOrContent: !0,
            needCancelBtn: !0,
            minLength: 2
        },
        comment_reply: {
            needCancelBtn: !0,
            minLength: 2
        }
    }
      , W = navigator.userAgent.match(/\bMicroMessenger\/([\d\.]+)/);
    W && (V.reply.needPhoto = !1);
    var X, Y, Z, _, aa, ba, ca, da, ea, fa, ga, ha, ia, ja, ka, la, ma, na, oa, pa, qa = {}, ra = "http://buluo.qq.com/mobile/", sa = [{
        type: 3,
        name: "原创"
    }, {
        type: 4,
        name: "招募"
    }], ta = [], ua = {}, va = 1, wa = 0, xa = {}, ya = !1, za = 0, Aa = 0, Ba = 0, Ca = 0, Da = "", Ea = 0, Fa = 20, Ga = 8, Ha = 2252.8, Ia = {
        source: 0,
        max: 8,
        outMaxWidth: Ha,
        urlOnly: !0,
        outMaxHeight: Ha
    }, Ja = !1, Ka = !1, La = {
        1: {
            action: "visit"
        },
        2: {
            action: "Clk_un_text"
        },
        3: {
            action: "Clk_un_nottext"
        },
        4: {
            action: "input_name"
        },
        5: {
            action: "input_text"
        },
        6: {
            action: "Clk_pic"
        },
        7: {
            action: "pub_fail_rule"
        },
        8: {
            action: "pub_fail_other"
        },
        9: {
            action: "pub_suc"
        },
        10: {
            action: "Clk_choose"
        },
        11: {
            action: "Clk_place"
        },
        12: {
            action: "Clk_pub"
        },
        13: {
            action: "Clk_un"
        },
        14: {
            action: "refuse_rank",
            module: "tribe_hp"
        },
        15: {
            action: "refuse_rank",
            module: "post_detail"
        },
        16: {
            action: "pub_fail_number"
        },
        17: {
            action: "exp_comment"
        }
    }, Ma = function(a, b, c) {
        var d = {
            opername: "Grp_tribe",
            module: Y,
            ver1: da && da.bid
        };
        da && da.pid && (d.obj1 = da.pid),
        "number" == typeof pa && (d.ver3 = pa);
        for (var e in La[a])
            La[a].hasOwnProperty(e) && (d[e] = La[a][e]);
        for (e in c)
            c.hasOwnProperty(e) && (d[e] = c[e]);
        Q.tdw(d)
    }
    , Na = function(a, b) {
        var c = b.result.add_credits
          , d = b.result.new_title
          , e = b.result.new_level;
        "barindex" === ma ? localStorage.setItem("upgrade_tip_info", e + "|" + c + "|" + d) : Tip.show("发表成功" + (c > 0 ? "，经验值+" + c : ""), {
            type: "ok"
        }),
        Ma(9),
        $.os.ios && setTimeout(function() {
            document.body.scrollTop = 0
        }, 0),
        a.css("opacity", ""),
        setTimeout(function() {
            var a, c = da.succ;
            $("#rankTab")[0] && "block" === $("#rankTab").css("display") && (a = !0),
            Publish.destroy(a),
            c && c(b.result)
        }, 2e3)
    }
    , Oa = !1, Pa = !1, Qa = !1, Ra = null , Sa = function(a, b) {
        da.bid = a,
        $(".cag-text").text(b),
        Va(function() {
            $("#publish-panel").show(),
            o(),
            window.NearbyPublish && window.NearbyPublish.hide(),
            ua.selectCateBtn.addClass("selected")
        })
    }
    , Ta = function(a) {
        var b = {
            type: "POST",
            url: "/cgi-bin/bar/post/publishable_v2",
            localCache: !1,
            cacheKey: Login.getUin() + "-publishable_v2-" + a.param.bid + "-" + a.param.pub_type,
            cacheTimeout: 6e5,
            cacheVersion: "1",
            update: null 
        };
        a = $.extend({}, b, a),
        (a.param || (a.param = {})).version = mqq.QQVersion,
        DB.cgiHttp(a)
    }
    , Ua = function(a, b) {
        var c = !0;
        return a && 2 === parseInt(a.requireType, 10) ? mqq && mqq.compare("5.2") < 0 && !b && (Alert.show("", "本部落必须发表语音，即将在新版本支持，敬请期待", {
            confirm: "我知道了"
        }),
        c = !1) : a && 4 === parseInt(a.requireType, 10) ? mqq && mqq.compare("5.2") < 0 && !b && (Alert.show("", "本部落必须发表音乐，即将在新版本支持，敬请期待", {
            confirm: "我知道了"
        }),
        c = !1) : a && 8 === parseInt(a.requireType, 10) && mqq && mqq.compare("5.3") < 0 && !b && (Alert.show("", "本部落必须发表视频，即将在新版本支持，敬请期待", {
            confirm: "我知道了"
        }),
        c = !1),
        c
    }
    , Va = function(a) {
        function b(b) {
            ya = !0;
            var c, d = b.result, e = parseInt(d.can_grievance, 10), f = d[da.replyMode ? "can_reply" : "can_send"], g = parseInt(d.vcode, 10);
            if (aa = d && d.publish_condition || {},
            c = aa.optionType,
            mqq && mqq.compare("5.3") < 0 && 8 === (8 & c) && (aa.optionType = c - 8),
            Ua(aa, da.replyMode))
                if ("post_limit" in d && (d.post_limit || (_.minLength = 1,
                _.minTitleLength = 1)),
                0 === f) {
                    var h = function() {
                        a && a(),
                        b.fromCache || (Ma(1),
                        "comment_reply" === X && Ma(17))
                    }
                    ;
                    0 === g ? h() : Checkcode.show("verify", function() {
                        setTimeout(function() {
                            h()
                        }, 500)
                    })
                } else {
                    "nearby" !== ma && T();
                    var i = function() {
                        "nearby" === ma && mqq.ui.popBack()
                    }
                    ;
                    if (1 === f) {
                        var j = parseInt(d.desc.split("-")[0])
                          , k = parseInt(d.desc.split("-")[1] % 86400);
                        Alert.show("本部落只有在" + I(Math.floor(j / 3600), 2) + ":" + I(Math.floor(j % 3600 / 60), 2) + "-" + (j > k ? "次日" : "") + I(Math.floor(k / 3600), 2) + ":" + I(Math.floor(k % 3600 / 60), 2) + "才能发表话题", "", {
                            confirm: "我知道了"
                        })
                    } else if (2 === f)
                        Alert.show("本部落只有相应地区的用户才能发表话题", "", {
                            confirm: "我知道了",
                            callback: i
                        });
                    else if (3 === f) {
                        var l = {
                            confirm: "我知道了",
                            callback: i
                        };
                        Z = !0,
                        1 === e && (l.cancel = "我要申诉",
                        l.cancelCallback = function() {
                            Util.openUrl(ra + "complain.html", !0)
                        }
                        ),
                        Alert.show("您的帐号有可疑记录，暂时被系统封停", "", l)
                    } else if (4 === f) {
                        var m = d.publish_level || 8;
                        Alert.show("暂时没有发表权限", "您当前的QQ等级过低, QQ" + m + "级以上的用户才能" + {
                            pub: "发表新话题",
                            reply: "回复话题",
                            comment_reply: "评论话题"
                        }[X], {
                            confirm: "我知道了",
                            callback: i
                        }),
                        Ma("pub" !== X || b.fromCache ? 15 : 14)
                    } else
                        5 === f ? Alert.show("本部落内测中，只有群主或管理员才能发表话题", "", {
                            confirm: "我知道了",
                            callback: i
                        }) : 6 === f ? Alert.show(aa.forbiddenMsg || "您暂时无权限在本部落发帖", "", {
                            confirm: "我知道了",
                            callback: i
                        }) : Alert.show(aa.forbiddenMsg || "系统繁忙", "", {
                            confirm: "我知道了",
                            callback: i
                        });
                    "nearby" === ma && window.NearbyPublish && window.NearbyPublish.clearActive()
                }
        }
        ba ? (b(ba),
        Qa = !1) : Ta({
            param: {
                bid: da.bid,
                pub_type: "pub" === X ? 0 : 1
            },
            succ: function(a) {
                b(a),
                Qa = !1
            },
            err: function() {
                Qa = !1
            }
        })
    }
    , Wa = function(a) {
        a = a || {};
        var b, c = a.pubulishType, d = $.extend({}, a), e = $.cookie("uin"), f = "localPubCheckData_" + (c || "reply");
        return e && localStorage.getItem(f) && (b = JSON.parse(localStorage.getItem(f))),
        b && b.uin === e && b.bid === d.bid && b.timespan + 36e5 >= (new Date).valueOf() ? void (ba = b.data) : void Ta({
            param: {
                bid: d.bid,
                pub_type: "pub" === c ? 0 : 1
            },
            noNeedLogin: !0,
            succ: function(a) {
                ba = a,
                e && localStorage.setItem(f, JSON.stringify({
                    uin: e,
                    bid: d.bid,
                    timespan: (new Date).valueOf(),
                    data: ba
                }))
            }
        })
    }
    , Xa = !1, Ya = function(a) {
        Xa || (Xa = !0,
        Oa = !1,
        !da.replyMode && a && a.hide(),
        "nearby" === ma && $(".cag-text").html("部落"))
    }
    ;
    return {
        init: P,
        initSelector: J,
        destroy: T,
        hidePoiList: l,
        destoryPoiList: m,
        setBid: Sa,
        setConfig: O,
        getPreCheckData: Wa,
        sendData: function(a, b, c) {
            v(a, b, c || {})
        }
    }
}),
function() {
    var a, b = Util.queryString("bid") || Util.getHash("bid"), c = "", d = ["barindex.html", "article_detail.html", "pho_detail.html"];
    for (a = 0; a < d.length; a++)
        if (location.href.toLowerCase().indexOf(d[a]) >= 0) {
            c = "pub";
            break
        }
    b && window.Publish.getPreCheckData({
        bid: b,
        pubulishType: c
    })
}();
