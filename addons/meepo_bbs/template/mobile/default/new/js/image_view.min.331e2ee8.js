!function(a, b) {
    function c(a) {
        return a.replace(/([a-z])([A-Z])/, "$1-$2").toLowerCase()
    }
    function d(a) {
        return e ? e + a : a.toLowerCase()
    }
    var e, f, g, h, i, j, k, l, m, n, o = "", p = {
        Webkit: "webkit",
        Moz: "",
        O: "o"
    }, q = window.document, r = q.createElement("div"), s = /^((translate|rotate|scale)(X|Y|Z|3d)?|matrix(3d)?|perspective|skew(X|Y)?)$/i, t = {};
    a.each(p, function(a, c) {
        return r.style[a + "TransitionProperty"] !== b ? (o = "-" + a.toLowerCase() + "-",
        e = c,
        !1) : void 0
    }),
    f = o + "transform",
    t[g = o + "transition-property"] = t[h = o + "transition-duration"] = t[j = o + "transition-delay"] = t[i = o + "transition-timing-function"] = t[k = o + "animation-name"] = t[l = o + "animation-duration"] = t[n = o + "animation-delay"] = t[m = o + "animation-timing-function"] = "",
    a.fx = {
        off: e === b && r.style.transitionProperty === b,
        speeds: {
            _default: 400,
            fast: 200,
            slow: 600
        },
        cssPrefix: o,
        transitionEnd: d("TransitionEnd"),
        animationEnd: d("AnimationEnd")
    },
    a.fn.animate = function(c, d, e, f, g) {
        return a.isFunction(d) && (f = d,
        e = b,
        d = b),
        a.isFunction(e) && (f = e,
        e = b),
        a.isPlainObject(d) && (e = d.easing,
        f = d.complete,
        g = d.delay,
        d = d.duration),
        d && (d = ("number" == typeof d ? d : a.fx.speeds[d] || a.fx.speeds._default) / 1e3),
        g && (g = parseFloat(g) / 1e3),
        this.anim(c, d, e, f, g)
    }
    ,
    a.fn.anim = function(d, e, o, p, q) {
        var r, u, v, w = {}, x = "", y = this, z = a.fx.transitionEnd, A = !1;
        if (e === b && (e = a.fx.speeds._default / 1e3),
        q === b && (q = 0),
        a.fx.off && (e = 0),
        "string" == typeof d)
            w[k] = d,
            w[l] = e + "s",
            w[n] = q + "s",
            w[m] = o || "linear",
            z = a.fx.animationEnd;
        else {
            u = [];
            for (r in d)
                s.test(r) ? x += r + "(" + d[r] + ") " : (w[r] = d[r],
                u.push(c(r)));
            x && (w[f] = x,
            u.push(f)),
            e > 0 && "object" == typeof d && (w[g] = u.join(", "),
            w[h] = e + "s",
            w[j] = q + "s",
            w[i] = o || "linear")
        }
        return v = function(b) {
            if ("undefined" != typeof b) {
                if (b.target !== b.currentTarget)
                    return;
                a(b.target).unbind(z, v)
            } else
                a(this).unbind(z, v);
            A = !0,
            a(this).css(t),
            p && p.call(this)
        }
        ,
        e > 0 && (this.bind(z, v),
        setTimeout(function() {
            A || v.call(y)
        }, 1e3 * e + 25)),
        this.size() && this.get(0).clientLeft,
        this.css(w),
        0 >= e && setTimeout(function() {
            y.each(function() {
                v.call(this)
            })
        }, 0),
        this
    }
    ,
    r = null 
}(Zepto);
var v = 0
  , lastTouchMoveTime = 0
  , lastTouchMovePositionX = 0
  , ImageView = {
    photos: null ,
    index: 0,
    el: null ,
    config: null ,
    lastContainerScroll: 0,
    zoom: 1,
    advancedSupport: !1,
    lastTapDate: 0,
    doubleZoomOrg: 1,
    doubleDistOrg: 1,
    isDoubleZoom: !1,
    resizeTimer: null ,
    init: function(a, b, c) {
        var d = this;
        if (b = +b || 0,
        this.config = $.extend({
            fade: !0,
            maxZoom: 4,
            useNavigate: !1,
            tapClose: !0
        }, c),
        this.lastContainerScroll = document.body.scrollTop,
        ($.os.iphone || $.os.ipod || $.os.android && parseFloat($.os.version) >= 4) && (this.advancedSupport = !0),
        this.config.count) {
            this.photos = new Array(this.config.count);
            for (var e = a.length, f = this.config.idx_space || 0, g = f; f + e > g; g++)
                this.photos[g] = a[g - f];
            this.index = f + b
        } else
            this.photos = a || [],
            this.index = b || 0;
        this.initNavigate(),
        setTimeout(function() {
            d.clearStatus(),
            d.render(!0),
            d.bind(),
            d.changeIndex(d.index, {
                force: !0
            })
        }, 0),
        this.dx = 10,
        $.os.ios && mqq && mqq.ui.setWebViewBehavior({
            swipeBack: 0
        }),
        this.config.onLongTap && mqq.compare("5.8") > -1 ? mqq.invoke("ui", "disableLongPress", {
            enable: !0
        }) : mqq.compare("5.8") > -1 && mqq.invoke("ui", "disableLongPress", {
            enable: !1
        })
    },
    clearStatus: function() {
        this.width = Math.max(window.innerWidth, document.body.scrollWidth, document.documentElement.scrollWidth) + this.dx,
        this.height = window.innerHeight,
        this.zoom = 1,
        this.zoomX = 0,
        this.zoomY = 0
    },
    render: function(a) {
        $("[data-image-view-hide]").hide();
        var b = document.createElement("div");
        b.id = "imageView",
        b.className = "slide-view",
        b.style.cssText = "width:" + (this.width - this.dx) + "px;",
        document.body.appendChild(b),
        this.el = $(b),
        this.el.html(this.tmpl({
            photos: this.photos,
            index: this.index,
            width: this.width - this.dx,
            height: this.height
        })),
        a && this.el.css({
            opacity: 0,
            height: this.height + 2 + "px",
            top: this.lastContainerScroll - 1 + "px"
        }).show().animate({
            opacity: 1
        }, 300)
    },
    encodeHTML: function(a) {
        return a = a.replace(/&/g, "&amp;"),
        a = a.replace(/>/g, "&gt;"),
        a = a.replace(/</g, "&lt;"),
        a = a.replace(/"/g, "&quot;"),
        a = a.replace(/'/g, "&#39;")
    },
    tmpl: function(a) {
        function b(a) {
            c.push(a)
        }
        var c = [""];
        if (a) {
            b('<ul class="pv-inner" style="line-height:' + a.height + 'px;">');
            for (var d = a.photos, e = 0; e < d.length; e++)
                b('<li class="pv-img" style="width:' + a.width + "px;height:" + a.height + 'px;"></li>');
            b("</ul>");
            var f = !1;
            f = this.config.descTmpl && $.isFunction(this.config.descTmpl),
            this.config.hideInfo || f || (b('<p class="counts"><span class="value" id="J_index">'),
            b(a.index + 1 + "/" + d.length),
            b("</span><span>" + this.encodeHTML(d[a.index].name) + "</span></p>")),
            f && (b('<div id="J_desc" style="position:absolute;bottom:0;left:0;right:0;">'),
            b("</div>"))
        }
        return c.join("")
    },
    initNavigate: function() {
        function a() {
            -1 === location.hash.indexOf("imageview") && b.close()
        }
        if (this.config.useNavigate) {
            var b = this;
            "onhashchange" in window ? window.onhashchange = a : this.hashChangeIntervalId = setInterval(a, 500),
            location.hash = location.hash ? location.hash.replace(/imageview/g, "") + "&imageview" : "#imageview"
        }
    },
    closeNavigate: function() {
        this.config.useNavigate && this.hashChangeIntervalId && (clearInterval(this.hashChangeIntervalId),
        delete this.hashChangeIntervalId)
    },
    topFix: function() {
        ImageView.el && ImageView.el.css("top", window.scrollY + "px")
    },
    bind: function() {
        var a = this;
        this.unbind(),
        $(window).on("scroll", this.topFix),
        this.el.on("touchstart touchmove touchend touchcancel", function(b) {
            a.handleEvent(b)
        }),
        this.el.on("singleTap", function(b) {
            b.preventDefault();
            var c = new Date;
            c - this.lastTapDate < 500 || (this.lastTapDate = c,
            a.onSingleTap(b))
        }).on("doubleTap", function(b) {
            b.preventDefault(),
            a.onDoubleTap(b)
        }).on("longTap", function(b) {
            b.preventDefault(),
            a.onLongTap(b)
        }),
        "onorientationchange" in window ? window.addEventListener("orientationchange", this, !1) : window.addEventListener("resize", this, !1),
        this.config.descTmpl && $.isFunction(this.config.descTmpl) && this.el.find("#J_desc").on("singleTap", function(b) {
            return a.config.onDescTap(a.index, $(b.target)),
            !1
        })
    },
    unbind: function() {
        this.el.off(),
        $(window).off("scroll", this.topFix),
        "onorientationchange" in window ? window.removeEventListener("orientationchange", this, !1) : window.removeEventListener("resize", this, !1)
    },
    handleEvent: function(a) {
        switch (a.type) {
        case "touchstart":
            this.onTouchStart(a);
            break;
        case "touchmove":
            a.preventDefault(),
            this.onTouchMove(a);
            break;
        case "touchcancel":
        case "touchend":
            this.onTouchEnd(a);
            break;
        case "orientationchange":
        case "resize":
            this.resize(a)
        }
    },
    onSingleTap: function(a) {
        this.config.useNavigate && history.go(-1),
        this.close(a)
    },
    onLongTap: function(a) {
        this.config.onLongTap && $.isFunction(this.config.onLongTap) && this.config.onLongTap(this.index || 0)
    },
    getDist: function(a, b, c, d) {
        return Math.sqrt(Math.pow(c - a, 2) + Math.pow(d - b, 2), 2)
    },
    onTouchStart: function(a) {
        if (this.advancedSupport && a.touches && a.touches.length >= 2) {
            var b = this.getImg();
            if (b) {
                b.style.webkitTransitionDuration = "0",
                this.isDoubleZoom = !0,
                this.doubleZoomOrg = this.zoom,
                this.doubleDistOrg = this.getDist(a.touches[0].pageX, a.touches[0].pageY, a.touches[1].pageX, a.touches[1].pageY);
                var c = (this.width - b.offsetWidth) / 2
                  , d = (this.lastTransOrigin,
                [this.zoomX || 0, this.zoomY || 0],
                this.zoom || 1,
                [~~((a.touches[0].pageX + a.touches[1].pageX) / 2 - c) + "px", ~~((a.touches[0].pageY + a.touches[1].pageY) / 2 - b.offsetTop) + "px"]);
                this.lastTransOrigin = d
            }
        } else if (a = a.touches ? a.touches[0] : a,
        this.isDoubleZoom = !1,
        this.startX = a.pageX,
        this.startY = a.pageY,
        this.orgX = a.pageX,
        this.orgY = a.pageY,
        this.hasMoved = !1,
        1 != this.zoom) {
            this.zoomX = this.zoomX || 0,
            this.zoomY = this.zoomY || 0;
            var b = this.getImg();
            b && (b.style.webkitTransitionDuration = "0"),
            this.drag = !0
        } else
            1 === this.photos.length,
            this.el.find(".pv-inner").css("-webkitTransitionDuration", "0"),
            this.transX = -this.index * this.width,
            this.slide = !0
    },
    onTouchMove: function(a) {
        if (this.advancedSupport && a.touches && a.touches.length >= 2) {
            var b = this.getImg();
            if (b) {
                var c = this.getDist(a.touches[0].pageX, a.touches[0].pageY, a.touches[1].pageX, a.touches[1].pageY);
                this.zoom = c * this.doubleZoomOrg / this.doubleDistOrg,
                b.style.webkitTransitionDuration = "0",
                this.zoom < 1 ? (this.zoom = 1,
                this.zoomX = 0,
                this.zoomY = 0,
                b.style.webkitTransitionDuration = "200ms") : this.zoom > this.getScale(b) * this.config.maxZoom && (this.zoom = this.getScale(b) * this.config.maxZoom),
                b.style.webkitTransform = "scale(" + this.zoom + ") translate(" + this.zoomX + "px," + this.zoomY + "px)"
            }
        } else if (!this.isDoubleZoom)
            if (a = a.touches ? a.touches[0] : a,
            !this.hasMoved && (Math.abs(a.pageX - this.orgX) > 5 || Math.abs(a.pageY - this.orgY) > 5) && (this.hasMoved = !0),
            1 != this.zoom) {
                var b = this.getImg();
                if (b) {
                    var d = (a.pageX - this.startX) / this.zoom
                      , e = (a.pageY - this.startY) / this.zoom;
                    this.startX = a.pageX,
                    this.startY = a.pageY;
                    var f = b.width * this.zoom
                      , g = b.height * this.zoom
                      , h = (f - this.width) / 2 / this.zoom
                      , i = (g - this.height) / 2 / this.zoom;
                    h >= 0 && (this.zoomX < -h || this.zoomX > h) && (d /= 3),
                    i > 0 && (this.zoomY < -i || this.zoomY > i) && (e /= 3),
                    this.zoomX += d,
                    this.zoomY += e,
                    1 === this.photos.length && f < this.width ? this.zoomX = 0 : g < this.height && (this.zoomY = 0),
                    b.style.webkitTransform = "scale(" + this.zoom + ") translate(" + this.zoomX + "px," + this.zoomY + "px)"
                }
            } else {
                if (!this.slide)
                    return;
                var d = a.pageX - this.startX;
                v = (a.pageX - lastTouchMovePositionX) / (+new Date - lastTouchMoveTime),
                lastTouchMovePositionX = a.pageX,
                lastTouchMoveTime = +new Date,
                (this.transX > 0 || this.transX < -this.width * (this.photos.length - 1)) && (d /= 2),
                this.transX = -this.index * this.width + d,
                this.el.find(".pv-inner").css("-webkitTransform", "translateX(" + this.transX + "px)")
            }
    },
    onTouchEnd: function(a) {
        if (!this.isDoubleZoom && this.hasMoved) {
            var b = 3;
            if (1 != this.zoom) {
                if (!this.drag)
                    return;
                var c = this.getImg();
                if (c) {
                    c.style.webkitTransitionDuration = "200ms";
                    var d = c.width * this.zoom
                      , e = c.height * this.zoom
                      , f = (d - this.width) / b / this.zoom
                      , g = (e - this.height) / b / this.zoom
                      , h = this.photos.length;
                    if (h > 1 && f >= 0) {
                        var i = 0
                          , j = this.width / 5;
                        if (this.zoomX < -f - j / this.zoom && this.index < h - 1 ? i = 1 : this.zoomX > f + j / this.zoom && this.index > 0 && (i = -1),
                        0 != i)
                            return
                    }
                    if (f >= 0 && (this.zoomX < -f ? this.zoomX = -f : this.zoomX > f && (this.zoomX = f)),
                    g > 0 && (this.zoomY < -g ? this.zoomY = -g : this.zoomY > g && (this.zoomY = g)),
                    this.isLongPic(c) && Math.abs(this.zoomX) < 10)
                        return void (c.style.webkitTransform = "scale(" + this.zoom + ") translate(0px," + this.zoomY + "px)");
                    c.style.webkitTransform = "scale(" + this.zoom + ") translate(" + this.zoomX + "px," + this.zoomY + "px)",
                    this.drag = !1
                }
            } else {
                if (!this.slide)
                    return;
                var k = this.transX - -this.index * this.width
                  , i = 0;
                k > this.width / b ? i = -1 : k < 0 - this.width / b && (i = 1),
                Math.abs(v) > .4 && (i = 0 > v ? 1 : -1),
                this.changeIndex(this.index + i, {
                    direction: i
                }),
                this.slide = !1
            }
        }
    },
    getImg: function(a) {
        "undefined" == typeof a && (a = this.index);
        var b = this.el.find("li").eq(a).find("img");
        return 1 == b.size() ? b[0] : null 
    },
    getScale: function(a) {
        return 2.86
    },
    onDoubleTap: function(a) {
        var b = new Date;
        if (!(b - this.lastTapDate < 500)) {
            this.lastTapDate = b;
            var c = this.getImg();
            if (c) {
                var d = a._args
                  , e = [];
                if (d[0] && d[0].changedTouches && d[0].changedTouches[0]) {
                    var f = d[0].changedTouches[0];
                    e[0] = f.pageX,
                    e[1] = f.pageY
                }
                1 != this.zoom ? this.scaleDown(c, e) : this.scaleUp(c, e),
                this.afterZoom(c)
            }
        }
    },
    scaleUp: function(a, b) {
        var c = this.getScale(a)
          , d = (this.width - a.offsetWidth) / 2;
        b.length && (b[0] = ~~(b[0] - d) + "px",
        b[1] = ~~(b[1] - a.offsetTop) + "px"),
        c > 1 && (a.style.webkitTransformOrigin = b.join(" "),
        a.style.webkitTransform = "scale(" + c + ")",
        a.style.webkitTransition = "-webkit-transform 300ms ease-in-out"),
        this.zoom = c,
        this.afterZoom(a)
    },
    scaleDown: function(a, b) {
        this.zoom = 1,
        this.zoomX = 0,
        this.zoomY = 0,
        this.doubleDistOrg = 1,
        this.doubleZoomOrg = 1,
        a.style.webkitTransition = "-webkit-transform 300ms ease-in-out",
        a.style.webkitTransform = "",
        this.afterZoom(a)
    },
    afterZoom: function(a) {
        if (this.zoom > 1 && this.isLongPic(a)) {
            var b = a.height * this.zoom
              , c = (b - this.height) / 2 / this.zoom;
            c > 0 && (this.zoomY = c,
            a.style.webkitTransform = "scale(" + this.zoom + ") translate(0px," + c + "px)")
        }
    },
    isLongPic: function(a) {
        return a.height / a.width >= 3.5
    },
    resize: function(a) {
        clearTimeout(this.resizeTimer);
        var b = this;
        this.resizeTimer = setTimeout(function() {
            document.body.style.minHeight = window.innerHeight + 1 + "px",
            1 != b.zoom && b.scaleDown(b.getImg()),
            b.clearStatus(),
            b.render(),
            b.el.height(b.height).css("top", window.scrollY + "px"),
            b.changeIndex(b.index, {
                force: !0
            })
        }, 600)
    },
    changeIndex: function(a, b) {
        if (b || (b = {}),
        "undefined" == typeof b.direction && (b.direction = 0),
        !this.indexChangeLock) {
            0 > a ? a = 0 : a >= this.photos.length && (a = this.photos.length - 1);
            var c = this.index != a;
            this.index = a;
            var d = this.el.find(".pv-inner");
            this.transX > 0 || this.transX < -this.width * (this.photos.length - 1) ? d.css({
                "-webkitTransition": b.force ? "0" : "500ms ease",
                "-webkitTransform": "translateX(-" + a * this.width + "px)"
            }) : d.css({
                "-webkitTransition": b.force ? "0" : "300ms cubic-bezier(.1,.5,.5,1)",
                "-webkitTransform": "translateX(-" + a * this.width + "px)"
            });
            var e = d.find("li").eq(a)
              , f = e.find("img");
            if (!f.size())
                if ("undefined" != typeof this.photos[a]) {
                    e.addClass("spinner");
                    var g = this.getNewImg(a, function() {
                        e.removeClass("spinner")
                    });
                    e.append(g),
                    this.config.onRequestMore && this.index > 0 && "undefined" == typeof this.photos[a - 1] ? this.config.onRequestMore(this.photos[a], -1, a) : this.config.onRequestMore && this.index < this.photos.length - 1 && "undefined" == typeof this.photos[this.index + 1] && this.config.onRequestMore(this.photos[a], 1, a)
                } else
                    this.indexChangeLock = !0;
            0 != b.direction ? this.preload(a, b.direction) : (this.preload(a, -1),
            this.preload(a, 1)),
            (c || b.force) && (this.el.find("#J_index").html(a + 1 + "/" + this.photos.length).next().html(this.encodeHTML(this.photos[a].name)),
            this.config.descTmpl && $.isFunction(this.config.descTmpl) && this.el.find("#J_desc").html(this.config.descTmpl(a)),
            this.config.onIndexChange && this.config.onIndexChange(img, this.photos, a)),
            this.config.onChange && this.config.onChange(a);
            var h = this;
            setTimeout(function() {
                h.memoryClear()
            }, 0)
        }
    },
    getNewImg: function(a, b) {
        this.config.onLoadBefore && this.config.onLoadBefore(this.photos[a].name);
        var c = this
          , d = new Image;
        return d.onload = function() {
            if (null  != c.el) {
                d.onload = null ,
                d.style.webkitTransform = "",
                d.style.opacity = "";
                var a = d.naturalWidth || d.width
                  , e = d.naturalHeight || d.height
                  , f = c.width - c.dx
                  , g = c.height
                  , h = a / f
                  , i = e / g;
                1 >= h && 1 >= i ? (d.style.width = a + "px",
                d.style.height = e + "px") : e > a ? i > h ? (d.style.height = g + "px",
                d.style.width = a / i + "px") : (d.style.height = e / h + "px",
                d.style.width = f + "px") : (d.style.width = f + "px",
                d.style.height = f * e / a + "px"),
                c.isLongPic(d) && setTimeout(function() {}, 0),
                b && b()
            }
        }
        ,
        d.ontimeout = d.onerror = function() {
            this.parentNode.innerHTML = '<i style="color:white;z-index:1;background-color:black;">图片加载失败，请稍后再试</i>',
            b && b()
        }
        ,
        this.advancedSupport && (d.style.webkitBackfaceVisibility = "hidden"),
        d.style.opacity = "0",
        d.src = this.getImgUrl(a),
        d
    },
    memoryClear: function() {
        for (var a = this.el.find(".pv-img"), b = this.index - 10; b >= 0 && "" != a.eq(b).html(); )
            a.eq(b).html(""),
            b--;
        for (b = this.index + 10; b < a.size() && "" != a.eq(b).html(); )
            a.eq(b).html(""),
            b++
    },
    getImgUrl: function(a, b) {
        return 0 > a || a >= this.photos.length || !this.photos[a] ? "" : this.photos[a].mbimg
    },
    preload: function(a, b) {
        var c = a + b;
        if (!(0 > c || c >= this.photos.length || this.getImg(c))) {
            var d = this.el.find(".pv-inner")
              , e = d.find("li").eq(c)
              , f = e.find("img");
            f.size() || "undefined" != typeof this.photos[c] && e.html("").append(this.getNewImg(c))
        }
    },
    update: function(a, b) {
        if (b < this.photos.length) {
            for (var c = a.length, d = b; b + c > d; d++)
                this.photos[d] = a[d - b];
            this.indexChangeLock && (this.indexChangeLock = !1,
            this.changeIndex(this.index))
        }
    },
    destroy: function() {
        if (this.closeNavigate(),
        this.el) {
            $("[data-image-view-hide]").show();
            var a = this;
            this.unbind(),
            this.el.find("img").css("-webkit-backface-visibility", "visible"),
            this.el.animate({
                opacity: 0
            }, 300, "linear", function() {
                a.el && (a.el.html("").hide(),
                a.el = a.el.remove(),
                a.el = null )
            }),
            this.config.onClose && this.config.onClose(this.img, this.photos, this.index)
        }
    },
    close: function() {
        this.config.tapClose && ($.os.ios && mqq && mqq.ui.setWebViewBehavior({
            swipeBack: 1
        }),
        mqq.compare("5.8") > -1 && mqq.invoke("ui", "disableLongPress", {
            enable: !1
        }),
        this.destroy())
    },
    del: function() {
        if (!this.photos.length)
            return void this.close();
        var a = this.el.find(".pv-inner");
        a.find("li").eq(this.index).remove(),
        this.photos.splice(this.index, 1),
        this.index = Math.max(Math.min(this.index, this.photos.length - 1), 0),
        this.photos.length ? this.changeIndex(this.index, {
            force: !0
        }) : this.close()
    }
};
