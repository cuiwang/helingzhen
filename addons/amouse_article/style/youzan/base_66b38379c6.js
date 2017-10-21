function FastClick(e, t) {
    function n(e, t) {
        return function () {
            return e.apply(t, arguments)
        }
    }

    var i;
    if (t = t || {}, this.trackingClick = !1, this.trackingClickStart = 0, this.targetElement = null, this.touchStartX = 0, this.touchStartY = 0, this.lastTouchIdentifier = 0, this.touchBoundary = t.touchBoundary || 10, this.layer = e, this.tapDelay = t.tapDelay || 200, !FastClick.notNeeded(e)) {
        for (var o = ["onMouse", "onClick", "onTouchStart", "onTouchMove", "onTouchEnd", "onTouchCancel"], r = this, a = 0, s = o.length; s > a; a++)r[o[a]] = n(r[o[a]], r);
        deviceIsAndroid && (e.addEventListener("mouseover", this.onMouse, !0), e.addEventListener("mousedown", this.onMouse, !0), e.addEventListener("mouseup", this.onMouse, !0)), e.addEventListener("click", this.onClick, !0), e.addEventListener("touchstart", this.onTouchStart, !1), e.addEventListener("touchmove", this.onTouchMove, !1), e.addEventListener("touchend", this.onTouchEnd, !1), e.addEventListener("touchcancel", this.onTouchCancel, !1), Event.prototype.stopImmediatePropagation || (e.removeEventListener = function (t, n, i) {
            var o = Node.prototype.removeEventListener;
            "click" === t ? o.call(e, t, n.hijacked || n, i) : o.call(e, t, n, i)
        }, e.addEventListener = function (t, n, i) {
            var o = Node.prototype.addEventListener;
            "click" === t ? o.call(e, t, n.hijacked || (n.hijacked = function (e) {
                    e.propagationStopped || n(e)
                }), i) : o.call(e, t, n, i)
        }), "function" == typeof e.onclick && (i = e.onclick, e.addEventListener("click", function (e) {
            i(e)
        }, !1), e.onclick = null)
    }
}
window.zenjs = window.zenjs || {}, function (e) {
    var t = navigator.userAgent.toLowerCase();
    e.UA = {
        isIOS: function () {
            return "ios" == window._global.mobile_system
        }, getIOSVersion: function () {
            return parseFloat(("" + (/CPU.*OS ([0-9_]{1,5})|(CPU like).*AppleWebKit.*Mobile/i.exec(navigator.userAgent) || [0, ""])[1]).replace("undefined", "3_2").replace("_", ".").replace("_", "")) || !1
        }, isAndroid: function () {
            return "android" == window._global.mobile_system
        }, isAndroidOld: function () {
            return /android 2.3/gi.test(t) || /android 2.2/gi.test(t)
        }, isWeixin: function () {
            return "weixin" == window._global.platform
        }, isIPad: function () {
            return /ipad/gi.test(t)
        }, isMobile: function () {
            return window._global.is_mobile
        }, isSafari: function () {
            return /safari/gi.test(t) && !/chrome/gi.test(t)
        }, isWxd: function () {
            return "youzanwxd" === _global.platform
        }, getPlatformVersion: function () {
            return _global.platform_version
        }
    }
}(window.zenjs), define && define("zenjs/util/ua", [], function () {
    return window.zenjs.UA
}), define("wap/base/fullguide", ["zenjs/util/ua"], function (e) {
    var t = window.Zepto || window.jQuery || t, n = window._global, i = t("body"), o = zenjs.UA.isWeixin() && n.mp_data && +n.mp_data.quick_subscribe && n.mp_data.quick_subscribe_url, r = {
        fav: function () {
            return '<div id="js-fav-guide" class="js-fullguide fullscreen-guide fav-guide hide"><span class="guide-close">&times;</span><span class="guide-arrow"></span><div class="guide-inner"><div class="step step-1"></div><div class="step step-2"></div></div></div>'
        }, share: function () {
            return '<div id="js-share-guide" class="js-fullguide fullscreen-guide hide" style="font-size: 16px; line-height: 35px; color: #fff; text-align: center;"><span class="js-close-guide guide-close">&times;</span><span class="guide-arrow"></span><div class="guide-inner">请点击右上角<br/>通过【发送给朋友】功能<br>或【分享到朋友圈】功能<br>把消息告诉小伙伴哟～</div></div>'
        }, browser: function (e) {
            var t = e || {}, n = t.isIOS ? '<div id="js-share-guide" class="js-fullguide fullscreen-guide hide" style="font-size: 16px; line-height: 35px; color: #fff; text-align: center;"><span class="js-close-guide guide-close">&times;</span><span class="guide-arrow"></span><div class="guide-inner">请点击右上角<br/>在Safari中打开～</div></div>' : '<div id="js-share-guide" class="js-fullguide fullscreen-guide hide" style="font-size: 16px; line-height: 35px; color: #fff; text-align: center;"><span class="js-close-guide guide-close">&times;</span><span class="guide-arrow"></span><div class="guide-inner">请点击右上角<br/>在浏览器中打开～</div></div>';
            return n
        }, follow: function (e) {
            var t = e || {}, n = ['<div id="js-follow-guide" class="js-fullguide fullscreen-guide follow-guide hide"><span class="js-close-guide guide-close">&times;</span><div class="guide-inner"><div class="step step-2"></div><div class="wxid"><strong>', t.mp_weixin, '</strong></div><div class="step step-3"></div></div></div>'];
            return n.join("")
        }, goodsFollow: function (e) {
            var t = e || {}, n = ['<div id="js-follow-guide" class="js-fullguide fullscreen-guide follow-guide hide"><span class="js-close-guide guide-close">&times;</span><div class="guide-inner"><h3 class="guide-inner-title">你需要关注后才能购买</h3><div class="step step-2"></div><div class="wxid"><strong>', t.mp_weixin, '</strong></div><div class="step step-3"></div></div></div>'];
            return n.join("")
        }, goodsQuickSubscribe: function (e) {
            var t = e || {}, n = ['<div id="js-follow-guide" class="js-fullguide fullscreen-guide follow-guide hide"><div class="quick-subscribe js-quick-subscribe"><h2>请先关注后再购买，享受更好的服务~</h2><div><a class="btn" href="', t.quick_subscribe_url, '">去关注</a ></div></div></div>'];
            return n.join("")
        }, pc: function (e) {
            var t = e || {}, n = ['<div id="js-share-guide" class="js-fullguide fullscreen-guide hide" style="font-size: 20px; line-height: 30px; color: #fff; text-align: center;"> <span class="js-close-guide guide-close">&times;</span> <div class="guide-inner"> 通过微信【扫一扫】功能<br/>扫描二维码关注我们<img style="width:160px; height: 160px;margin-top: 20px;" src="http://open.weixin.qq.com/qr/code/?username=', t.mp_weixin, '" alt="', t.mp_weixin, '"> </div> </div> '];
            return n.join("")
        }
    }, a = {follow: "#js-follow-guide", fav: "#js-fav-guide", share: "#js-share-guide"}, s = function (e, n) {
        var i, o;
        t(a[e]).length ? o = t(a[e]) : (i = r[e](n || {}), o = t(i).appendTo("body")), o.removeClass("hide")
    }, l = {
        fav: function () {
            s("fav")
        }, share: function () {
            s("share")
        }, follow: function (e) {
            var t = n.mp_data;
            if (t)return !(e || {}).goods && o ? void(window.location.href = t.quick_subscribe_url) : void s("follow", t)
        }, browser: function (e) {
            zenjs.UA.isWeixin() && s("browser", e)
        }
    }, c = function (e, n) {
        var i = t(a[e]);
        i && 0 != i.length ? i.removeClass("hide") : l[e](n)
    };
    n.is_mobile ? n && "Showcase_Goods_Controller" === n.controller && (o ? r.follow = r.goodsQuickSubscribe : r.follow = r.goodsFollow) : r.follow = r.pc, i.on("click", ".wxid", function (e) {
        e.stopPropagation()
    }), i.on("click", ".js-open-follow", function (e) {
        e.preventDefault(), c("follow")
    }), i.on("click", ".js-open-browser", function (e) {
        e.preventDefault(), c("browser")
    }), i.on("click", ".js-open-fav", function (e) {
        e.preventDefault(), c("fav")
    }), i.on("click", ".js-open-share", function (e) {
        e.preventDefault(), window._global && window._global.wuxi1_0_0 && window.shareHook ? window.shareHook() : window.YouzanJSBridge ? window.YouzanJSBridge.doShare() : c("share")
    }), t(document).on("click", ".js-fullguide", function () {
        t(this).addClass("hide")
    }), i.on("click", ".js-quick-subscribe", function (e) {
        e.stopPropagation()
    }), window.showGuide = c
}), window.zenjs = window.zenjs || {}, function (e) {
    e.Args = {
        getParameterByName: function (e, t) {
            e = e.replace(/[[]/, "\\[").replace(/[]]/, "\\]"), t = t ? "?" + t.split("#")[0].split("?")[1] : window.location.search;
            var n = RegExp("[?&]" + e + "=([^&#]*)").exec(t);
            return n ? decodeURIComponent(n[1].replace(/\+/g, " ")) : ""
        }, removeParameter: function (e, t) {
            var n = e.split("?");
            if (n.length >= 2) {
                for (var i = encodeURIComponent(t) + "=", o = n[1].split(/[&;]/g), r = o.length; r-- > 0;)-1 !== o[r].lastIndexOf(i, 0) && o.splice(r, 1);
                return e = n[0] + "?" + o.join("&")
            }
            return e
        }, addParameter: function () {
            var e = function (e) {
                var t = "";
                for (var n in e)t += $.trim(n) + "=" + e[n] + "&";
                return t ? "?" + t.slice(0, t.length - 1) : ""
            };
            return function (t, n) {
                if (!t || 0 === t.length || 0 === $.trim(t).indexOf("javascript"))return "";
                var i = t.split("#"), o = i[0].split("?"), r = {};
                return o[1] && $.each(o[1].split("&"), function (e, t) {
                    var n;
                    n = t.split("="), r[n[0]] = n.slice(1).join("=")
                }), $.each(n || {}, function (e, t) {
                    r[$.trim(e)] = encodeURIComponent(t)
                }), t = o[0] + e(r), i[1] ? t += "#" + i[1] : t
            }
        }()
    }, e.Args.get = e.Args.getParameterByName, e.Args.remove = e.Args.removeParameter, e.Args.add = e.Args.addParameter
}(window.zenjs), define("zenjs/util/args", function () {
}), define("wap/base/log", ["zenjs/util/args"], function () {
    var e = window.Zepto || window.jQuery || e, t = {};
    _global.spm = _global.spm || {};
    var n = function () {
        var t = function () {
            return _global.spm.logType + _global.spm.logId || "fake" + _global.kdt_id
        };
        return function () {
            var n = zenjs.Args.get("spm");
            if (n = e.trim(n), "" !== n) {
                var i = n.split("_");
                i.length > 2 && (n = i[0] + "_" + i[i.length - 1]), n += "_" + t()
            } else n = t();
            return n
        }
    }(), i = function (t, n, i) {
        var o = new Image, r = Math.floor(2147483648 * Math.random()).toString(36), a = "log_" + r, s = new e.Deferred;
        return window[a] = o, o.onload = o.onerror = o.onabort = function () {
            o.onload = o.onerror = o.onabort = null, window[a] = null, o = null, s.resolve()
        }, n.link = window.location.href, n.time = (new Date).getTime(), o.src = zenjs.Args.add(t, n), window.setTimeout(s.resolve, 1500), s.promise()
    }, o = function (e) {
        e = e || "default";
        var t = {wxd: "http://fx.tj.youzan.com/3.gif", wxdapp: "http://app.tj.koudaitong.com/1.gif", "default": "//tj.koudaitong.com/1.gif"};
        return t[e]
    };
    t.log = function (n, r) {
        return n.spm || (n.spm = t.getSpm()), n.referer_url || (n.referer_url = encodeURIComponent(document.referrer)), n.title || (n.title = _global.title || e.trim(document.title)), i(o(n.target), n, r)
    }, t.getSpm = function () {
        return t.spm || (t.spm = n()), t.spm
    }, window.Logger = t;
    var r = window.__logs;
    return r && r.length > 0 && r.forEach(t.log), t
}), window.zenjs = window.zenjs || {}, function (e) {
    e.Image = e.Image || {}, e.Image.toWebp = function () {
        var e = /\.([^.!]+)\!([0-9]{1,4})x([0-9]{1,4})(\+2x)?\..+/, t = !1;
        try {
            t = "ok" === window.localStorage.getItem("canwebp")
        } catch (n) {
        }
        return function (n) {
            var i = n, o = 1;
            if (t) {
                var r = i.match(e);
                r && r.length >= 4 && ("+2x" == r[4] && (o = 2), i = i.replace(e, ".") + r[1] + "?imageView2/2/w/" + parseInt(r[2]) * o + "/h/" + parseInt(r[3]) * o + "/q/75/format/" + ("gif" == r[1] ? "gif" : "webp"))
            }
            return i
        }
    }(), e.Image.checkCanWebp = function () {
        var t = function (e) {
            var t = new Image;
            t.onload = t.onerror = function () {
                e(2 == t.height)
            }, t.src = "data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA"
        };
        return function (n) {
            if ("object" == typeof window.localStorage)try {
                var i = localStorage.getItem("canwebp");
                "ok" == i ? e.Browser.cookie("_canwebp", {value: "1", path: "/", domain: location.hostname, expires: 3650}) : "no" != i && t(function (t) {
                    localStorage.setItem("canwebp", t ? "ok" : "no"), t && e.Browser.cookie("_canwebp", {
                        value: "1",
                        path: "/",
                        domain: location.hostname,
                        expires: 3650
                    })
                })
            } catch (o) {
            }
        }
    }()
}(window.zenjs), define && define("zenjs/util/image", [], function () {
    return window.zenjs.Image
}), window.zenjs = window.zenjs || {}, function () {
    if (!window.zenjs.ready) {
        var e = /complete|loaded/;
        window.zenjs.ready = function (t) {
            e.test(document.readyState) && document.body ? setTimeout(t) : window.addEventListener("load", t, !1)
        }
    }
}(), define("zenjs/util/ready", function () {
}), define("wap/base/lazy_load", ["wap/base/log", "zenjs/util/image", "zenjs/util/ready"], function () {
    var e = window.Zepto || window.jQuery || e, t = e(window), n = Logger && Logger.getSpm() || "";
    e.fn.lazyload = function (n) {
        function i() {
            var t = 0;
            r.each(function () {
                var n = e(this);
                if (!a.skip_invisible || n.is(":visible"))if (e.abovethetop(this, a) || e.leftofbegin(this, a)); else if (e.belowthefold(this, a) || e.rightoffold(this, a)) {
                    if (++t > a.failure_limit)return !1
                } else n.trigger("appear"), t = 0
            })
        }

        var o, r = this, a = {
            threshold: 200,
            failure_limit: 0,
            event: "scroll",
            effect: "show",
            container: window,
            data_attribute: "src",
            skip_invisible: !1,
            appear: null,
            load: null,
            placeholder: null
        };
        return n && (void 0 !== n.failurelimit && (n.failure_limit = n.failurelimit, delete n.failurelimit), void 0 !== n.effectspeed && (n.effect_speed = n.effectspeed, delete n.effectspeed), e.extend(a, n)), o = void 0 === a.container || a.container === window ? t : e(a.container), 0 === a.event.indexOf("scroll") && o.bind(a.event, function () {
            return i()
        }), this.each(function () {
            var t = this, n = e(t), i = n[0].nodeName.toLowerCase();
            t.loaded = !1, "img" === i && (void 0 === n.attr("src") || n.attr("src") === !1) && n.is("img") && a.placeholder && n.attr("src", a.placeholder), n.one("appear", function () {
                if (!this.loaded) {
                    if (a.appear) {
                        var o = r.length;
                        a.appear.call(t, o, a)
                    }
                    if ("img" === i) {
                        var s = n.attr("data-" + a.data_attribute);
                        s = zenjs.Image.toWebp(s), s = s.replace(/http:\/\/imgqn.koudaitong.com/gi, "http://img.yzcdn.cn"), e("<img />").bind("load", function () {
                            n.hide(), n.is("img") ? n.attr("src", s) : n.css("background-image", 'url("' + s + '")'), n[a.effect](), t.loaded = !0;
                            var i = e(t).parent();
                            i.hasClass("photo-block") && i.css("background-color", "#fff");
                            var o = e.grep(r, function (e) {
                                return !e.loaded
                            });
                            if (r = e(o), a.load) {
                                var l = r.length;
                                a.load.call(t, l, a)
                            }
                        }).attr("src", s)
                    } else if ("textarea" === i) {
                        var l = n.parent();
                        n.after(n.val()).remove(), e(".js-lazy", l).lazyload(), a.load && a.load.call(t, l, a)
                    }
                }
            }), 0 !== a.event.indexOf("scroll") && n.bind(a.event, function () {
                t.loaded || n.trigger("appear")
            })
        }), t.bind("resize", function () {
            i()
        }), /(?:iphone|ipod|ipad).*os 5/gi.test(navigator.appVersion) && t.bind("pageshow", function (t) {
            t.originalEvent && t.originalEvent.persisted && r.each(function () {
                e(this).trigger("appear")
            })
        }), e(document).ready(function () {
            i()
        }), this
    }, e.fn.goodsLazyLoad = function () {
        this.lazyload({
            appear: function () {
                var t, i = e(this).parents(".js-goods").first().data("goods-id");
                t = n.lastIndexOf("_") === n.length - 1 ? n + "SI" + i : n + "_SI" + i, window.Logger && Logger.log({spm: t, fm: "display"})
            }
        })
    }, e.belowthefold = function (n, i) {
        var o;
        return o = void 0 === i.container || i.container === window ? (window.innerHeight ? window.innerHeight : t.height()) + t.scrollTop() : e(i.container).offset().top + e(i.container).height(), o <= e(n).offset().top - i.threshold
    }, e.rightoffold = function (n, i) {
        var o;
        return o = void 0 === i.container || i.container === window ? t.width() + t.scrollLeft() : e(i.container).offset().left + e(i.container).width(), o <= e(n).offset().left - i.threshold
    }, e.abovethetop = function (n, i) {
        var o;
        return o = void 0 === i.container || i.container === window ? t.scrollTop() : e(i.container).offset().top, o >= e(n).offset().top + i.threshold + e(n).height()
    }, e.leftofbegin = function (n, i) {
        var o;
        return o = void 0 === i.container || i.container === window ? t.scrollLeft() : e(i.container).offset().left, o >= e(n).offset().left + i.threshold + e(n).width()
    }, e.inviewport = function (t, n) {
        return !(e.rightoffold(t, n) || e.leftofbegin(t, n) || e.belowthefold(t, n) || e.abovethetop(t, n))
    }, window.zenjs.ready && window.zenjs.ready(function () {
        e(".js-lazy").lazyload(), e(".js-goods-lazy").goodsLazyLoad()
    })
}), window.zenjs = window.zenjs || {}, function (e) {
    e.Str = e.Str || {}, e.Str.unescape = function (e) {
        var t = {"&amp;": "&", "&lt;": "<", "&gt;": ">", "&quot;": '"', "&#x27;": "'"}, n = /(\&amp;|\&lt;|\&gt;|\&quot;|\&#x27;)/g;
        return ("" + e).replace(n, function (e) {
            return t[e]
        })
    }
}(window.zenjs), define("zenjs/util/str/unescape", function () {
}), define("wap/base/share", ["zenjs/util/args", "zenjs/util/str/unescape"], function () {
    var e = window.Zepto || window.jQuery || e, t = window._global || {}, n = t.share || {}, i = function (e) {
        return e = e.replace(/http:\/\/imgqn.koudaitong.com/gi, "http://img.yzcdn.cn"), 0 === e.indexOf("http://img.yzcdn.cn") || 0 === e.indexOf("http://imgqntest.koudaitong.com") ? e.replace(/(\![0-9]+x[0-9]+.+)/g, "") + "!200x200.jpg" : e
    }, o = function () {
        var t = "http://static.koudaitong.com/v2/image/youzan_mall_logo.jpg", n = e("#wxcover"), i = null;
        return n && n.length > 0 ? (i = n.data("wxcover"), i && 0 !== i.length || (i = n.css("background-image"), i && "none" != i ? (i = /^url\((['"]?)(.*)\1\)$/.exec(i), i = i ? i[2] : null) : i = null)) : (n = null, e(".content img").each(function (t, i) {
            return e(i).hasClass("js-not-share") ? void 0 : (n = e(i), !1)
        }), n && n.length > 0 && (i = n[0].getAttribute("src") || n[0].getAttribute("data-src"))), i || (_global.mp_data || {}).logo || t
    }, r = function (e) {
        e = e || document.documentURI;
        var t, n = Number(_global.kdt_id) || 0, i = [2737501, 618192, 618242, 371189, 1], o = _global.youzan_share, r = Math.floor(9e3 * Math.random()) + 1e3;
        return i.indexOf(n) >= 0 && (n = 0), o ? (t = r + "." + o, e = e.replace(/:\/\/.*\.koudaitong\.com/g, "://" + t + ".koudaitong.com")) : (t = 0 === n ? "192168-" + r : 192168 + n, e = e.replace("://wap.", "://shop" + t + ".")), e = zenjs.Args.remove(e, "redirect_count")
    }, a = function () {
        var t = n.title || _global.title || e("#wxtitle").text() || document.title, a = n.link || r(), s = i(n.cover || o()), l = ((n.desc || e("#wxdesc").val() || e("#wxdesc").text() || e(".custom-richtext").text() || e(".content-body").text() || e(".content").text() || t || "") + "").replace(/\s*/g, "");
        return function () {
            t = window.__title || t, a = window.__link || a, s = window.__cover || s, l = window.__desc || l;
            var i, o = e(".time-line-title");
            return i = o.length > 0 ? o.val() || o.text() : n.timeline_title, {
                title: zenjs.Str.unescape(t),
                link: a,
                img_url: s,
                desc: zenjs.Str.unescape(l).substring(0, 80),
                timeLineTitle: zenjs.Str.unescape((i || "").trim())
            }
        }
    }(), s = function () {
        var e = a(), t = window.zenjs.UA;
        if (t)if (t.isIOS()) {
            var n = "#func=sharePlatsAction&content=" + e.title + e.desc + "&content_url=" + e.link + "&pic=" + e.img_url;
            window.location.hash = "", window.location.href = n
        } else t.isAndroid() && window.android && window.android.sharePlatsAction && window.android.sharePlatsAction(e.title, e.link, e.img_url)
    };
    window.shareHook = s, window.getShareLink = r, window.getShareData = window.getShareData || a
}), define("zenjs/class", ["require", "exports", "module"], function (e, t, n) {
    var i = !1, o = /\b_super\b/, r = function () {
    };
    r.extend = function (e) {
        function t() {
            !i && this.init && this.init.apply(this, arguments)
        }

        var n = this.prototype;
        i = !0;
        var r = new this;
        i = !1;
        for (var a in e)r[a] = "function" == typeof e[a] && "function" == typeof n[a] && o.test(e[a]) ? function (e, t) {
            return function () {
                var i = this._super;
                this._super = n[e];
                var o = t.apply(this, arguments);
                return this._super = i, o
            }
        }(a, e[a]) : e[a];
        return t.prototype = r, t.prototype.constructor = t, t.extend = arguments.callee, t
    }, n.exports = r
}), define("zenjs/core/trigger_method", [], function () {
    var e = function () {
        function e(e, t, n) {
            return n.toUpperCase()
        }

        function t(e, t, n) {
            return [].slice.call(e, null == t || n ? 1 : t)
        }

        var n = /(^|:)(\w)/gi;
        return function (i) {
            var o = "on" + i.replace(n, e), r = this[o];
            return "function" == typeof this.trigger && this.trigger.apply(this, arguments), "function" == typeof r ? r.apply(this, t(arguments)) : void 0
        }
    }();
    return e
}), define("zenjs/events", ["require", "exports", "module", "zenjs/class", "zenjs/core/trigger_method"], function (e, t, n) {
    var i = e("zenjs/class"), o = e("zenjs/core/trigger_method"), r = i.extend({
        on: function (e, t, n) {
            return this._events = this._events || {}, this._events[e] = this._events[e] || [], this._events[e].push({
                callback: t,
                context: n,
                ctx: n || this
            }), this
        }, off: function (e, t, n) {
            var i, o, r, a, s, l, c, d;
            if (!e && !t && !n)return this._events = {}, this;
            for (a = e ? [e] : Object.keys(this._events), s = 0, l = a.length; l > s; s++)if (e = a[s], r = this._events[e]) {
                if (this._events[e] = i = [], t || n)for (c = 0, d = r.length; d > c; c++)o = r[c], (t && t !== o.callback && t !== o.callback._callback || n && n !== o.context) && i.push(o);
                i.length || delete this._events[e]
            }
            return this
        }, trigger: function (e) {
            if (!this._events)return this;
            var t = [].slice.call(arguments, 1), n = this._events[e];
            if (n)for (var i, o = -1; ++o < n.length;)(i = n[o]).callback.apply(i.ctx, t)
        }, triggerMethod: o
    });
    n.exports = r
}), define("wap/base/js_bridge", ["require", "zenjs/util/args", "zenjs/util/ua", "wap/base/share", "zenjs/events"], function (e) {
    var t = window.Zepto || window.jQuery || t;
    e("zenjs/util/args"), e("zenjs/util/ua"), e("wap/base/share");
    var n = e("zenjs/events"), i = window.zenjs.UA, o = window.zenjs.Args, r = window.getShareData, a = n.extend({
        init: function (e) {
            if (this.on("share", this.doShare), this.doCall("webReady"), e.check_login) {
                this.on("userInfoReady", function (n) {
                    n && n.user_id && n.user_id != e.fans_token && t.post(e.kdtunionUrl || "/v2/buyer/kdtunion/index.json", n).done(function (t) {
                        t && 0 === t.code ? e.redirectUrl ? window.location.href = e.redirectUrl : window.location.reload() : alert("登录失败请重试！")
                    })
                });
                var n = this;
                setTimeout(function () {
                    _global.platform_version >= "1.5.0" ? n.doCall("getData", {datatype: "userInfo"}) : "youzanwxd" !== _global.platform && n.doCall("getUserInfo")
                }, 50)
            }
        }, doCall: function (e, n) {
            i && (i.isIOS() ? (n = n || {}, t.each(n, function (e, i) {
                (t.isPlainObject(i) || t.isArray(i)) && (n[e] = JSON.stringify(i))
            }), location.href = o.addParameter("youzanjs://" + e, n)) : i.isAndroid() && window.androidJS && window.androidJS[e] && window.androidJS[e](JSON.stringify(n)))
        }, doShare: function (e) {
            this.doCall("returnShareData", e || r())
        }
    });
    window.onReady("isReadyForYouZanJSBridge", function () {
        var e = window.YouzanJSBridgeOptions || {};
        window.YouzanJSBridge = new a({
            check_login: _global.ajax_acl_check || e.isNeedCheckLogin,
            fans_token: _global.fans_token,
            redirectUrl: e.redirectUrl,
            kdtunionUrl: _global.kdt_union_url
        })
    })
}), define("wap/base/wx", ["wap/base/share"], function () {
    window.wxReady = function (e) {
        if (window.WeixinJSBridge)e && e(); else {
            var t = setTimeout(function () {
                window.WeixinJSBridge && e && e()
            }, 1e3);
            document.addEventListener("WeixinJSBridgeReady", function () {
                clearTimeout(t), e && e()
            })
        }
    };
    var e = window._global || {}, t = e.share || {}, n = function (e, t) {
        window.Logger && Logger.log({fm: "share", title: e.title, link: encodeURIComponent(e.link), from: t})
    };
    wxReady(function () {
        var e = window.WeixinJSBridge;
        e && e.on && (e.call(t.notShare ? "hideOptionMenu" : "showOptionMenu"), e.on("menu:share:timeline", function () {
            if (!t.notShare) {
                window.doWhileShare && window.doWhileShare();
                var i = window.getShareData();
                i.timeLineTitle && (i.title = i.timeLineTitle), e.invoke("shareTimeline", i, function (e) {
                    window.__onShareTimeline && window.__onShareTimeline(e)
                }), n(i, "timeline")
            }
        }), e.on("menu:share:appmessage", function () {
            if (!t.notShare) {
                window.doWhileShare && window.doWhileShare();
                var i = window.getShareData();
                e.invoke("sendAppMessage", i, function () {
                }), n(i, "appmessage")
            }
        }))
    }), function () {
        var e = {};
        e.on = function () {
        }, window.wx = e
    }()
}), function (e, t) {
    function n() {
        return i ? i : (i = e('<div class="motify"><div class="motify-inner"></div></div>'), e("body").append(i), i)
    }

    var i, o, r = t.motify = t.motify || {};
    r.log = function (e, i, r) {
        var a = n(), s = this;
        "number" != typeof i && (i = 2e3), a.show().find(".motify-inner").html(e || " "), i > 0 && (t.clearTimeout(o), o = t.setTimeout(function () {
            r && r.apply(null), s.clear()
        }, "function" != typeof r ? i : i + 300))
    }, r.clear = function () {
        var e = n();
        e.hide()
    }
}(window.Zepto || window.jQuery || $, window), define("wap/base/motify", function () {
}), window.Zepto && function (e) {
    ["width", "height"].forEach(function (t) {
        var n = t.replace(/./, function (e) {
            return e[0].toUpperCase()
        });
        e.fn["outer" + n] = function (e) {
            var n = this;
            if (n && n.length > 0) {
                var i = n[t](), o = {width: ["left", "right"], height: ["top", "bottom"]};
                return o[t].forEach(function (t) {
                    e && (i += parseInt(n.css("margin-" + t), 10))
                }), i
            }
            return null
        }
    })
}(Zepto), define("vendor/zepto/outer", function () {
}), define("wap/components/footer_auto", ["vendor/zepto/outer"], function () {
    var e = navigator.userAgent, t = ["MI", "NX507J", "SM701", "Coolpad"], n = function () {
        for (var n = t.length - 1; n >= 0; n--)if (e.indexOf(t[n]) > -1)return !0;
        return !1
    }(), i = 0 === $(".atuo-footer-off").length ? !1 : !0;
    if (!i && !n) {
        var o = $(window).height(), r = $(".container"), a = ($(".footer").length && $(".footer").outerHeight(!0) || 0, $(".js-footer-auto-ele")), s = o;
        if (0 === r.length)return;
        a.length > 0 && (s -= a.outerHeight(!0)), r.css("min-height", s + "px")
    }
});
var deviceIsAndroid = navigator.userAgent.indexOf("Android") > 0, deviceIsIOS = /iP(ad|hone|od)/.test(navigator.userAgent), deviceIsIOS4 = deviceIsIOS && /OS 4_\d(_\d)?/.test(navigator.userAgent), deviceIsIOSWithBadTarget = deviceIsIOS && /OS ([6-9]|\d{2})_\d/.test(navigator.userAgent), deviceIsBlackBerry10 = navigator.userAgent.indexOf("BB10") > 0;
FastClick.prototype.needsClick = function (e) {
    switch (e.nodeName.toLowerCase()) {
        case"button":
        case"select":
        case"textarea":
            if (e.disabled)return !0;
            break;
        case"input":
            if (deviceIsIOS && "file" === e.type || e.disabled)return !0;
            break;
        case"label":
        case"video":
            return !0
    }
    return /\bneedsclick\b/.test(e.className)
}, FastClick.prototype.needsFocus = function (e) {
    switch (e.nodeName.toLowerCase()) {
        case"textarea":
            return !0;
        case"select":
            return !deviceIsAndroid;
        case"input":
            switch (e.type) {
                case"button":
                case"checkbox":
                case"file":
                case"image":
                case"radio":
                case"submit":
                    return !1
            }
            return !e.disabled && !e.readOnly;
        default:
            return /\bneedsfocus\b/.test(e.className)
    }
}, FastClick.prototype.sendClick = function (e, t) {
    var n, i;
    document.activeElement && document.activeElement !== e && document.activeElement.blur(), i = t.changedTouches[0], n = document.createEvent("MouseEvents"), n.initMouseEvent(this.determineEventType(e), !0, !0, window, 1, i.screenX, i.screenY, i.clientX, i.clientY, !1, !1, !1, !1, 0, null), n.forwardedTouchEvent = !0, e.dispatchEvent(n)
}, FastClick.prototype.determineEventType = function (e) {
    return deviceIsAndroid && "select" === e.tagName.toLowerCase() ? "mousedown" : "click"
}, FastClick.prototype.focus = function (e) {
    var t;
    deviceIsIOS && e.setSelectionRange && 0 !== e.type.indexOf("date") && "time" !== e.type ? (t = e.value.length, e.setSelectionRange(t, t)) : e.focus()
}, FastClick.prototype.updateScrollParent = function (e) {
    var t, n;
    if (t = e.fastClickScrollParent, !t || !t.contains(e)) {
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
}, FastClick.prototype.getTargetElementFromEventTarget = function (e) {
    return e.nodeType === Node.TEXT_NODE ? e.parentNode : e
}, FastClick.prototype.onTouchStart = function (e) {
    var t, n, i;
    if (e.targetTouches.length > 1)return !0;
    if (t = this.getTargetElementFromEventTarget(e.target), n = e.targetTouches[0], deviceIsIOS) {
        if (i = window.getSelection(), i.rangeCount && !i.isCollapsed)return !0;
        if (!deviceIsIOS4) {
            if (n.identifier === this.lastTouchIdentifier)return e.preventDefault(), !1;
            this.lastTouchIdentifier = n.identifier, this.updateScrollParent(t)
        }
    }
    return this.trackingClick = !0, this.trackingClickStart = e.timeStamp, this.targetElement = t, this.touchStartX = n.pageX, this.touchStartY = n.pageY, e.timeStamp - this.lastClickTime < this.tapDelay && e.preventDefault(), !0
}, FastClick.prototype.touchHasMoved = function (e) {
    var t = e.changedTouches[0], n = this.touchBoundary;
    return Math.abs(t.pageX - this.touchStartX) > n || Math.abs(t.pageY - this.touchStartY) > n ? !0 : !1
}, FastClick.prototype.onTouchMove = function (e) {
    return this.trackingClick ? ((this.targetElement !== this.getTargetElementFromEventTarget(e.target) || this.touchHasMoved(e)) && (this.trackingClick = !1, this.targetElement = null), !0) : !0
}, FastClick.prototype.findControl = function (e) {
    return void 0 !== e.control ? e.control : e.htmlFor ? document.getElementById(e.htmlFor) : e.querySelector("button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea")
}, FastClick.prototype.onTouchEnd = function (e) {
    var t, n, i, o, r, a = this.targetElement;
    if (!this.trackingClick)return !0;
    if (e.timeStamp - this.lastClickTime < this.tapDelay)return this.cancelNextClick = !0, !0;
    if (this.cancelNextClick = !1, this.lastClickTime = e.timeStamp, n = this.trackingClickStart, this.trackingClick = !1, this.trackingClickStart = 0, deviceIsIOSWithBadTarget && (r = e.changedTouches[0], a = document.elementFromPoint(r.pageX - window.pageXOffset, r.pageY - window.pageYOffset) || a, a.fastClickScrollParent = this.targetElement.fastClickScrollParent), i = a.tagName.toLowerCase(), "label" === i) {
        if (t = this.findControl(a)) {
            if (this.focus(a), deviceIsAndroid)return !1;
            a = t
        }
    } else if (this.needsFocus(a))return e.timeStamp - n > 100 || deviceIsIOS && window.top !== window && "input" === i ? (this.targetElement = null, !1) : (this.focus(a), this.sendClick(a, e), deviceIsIOS && "select" === i || (this.targetElement = null, e.preventDefault()), !1);
    return deviceIsIOS && !deviceIsIOS4 && (o = a.fastClickScrollParent, o && o.fastClickLastScrollTop !== o.scrollTop) ? !0 : (this.needsClick(a) || (e.preventDefault(), this.sendClick(a, e)), !1)
}, FastClick.prototype.onTouchCancel = function () {
    this.trackingClick = !1, this.targetElement = null
}, FastClick.prototype.onMouse = function (e) {
    return this.targetElement ? e.forwardedTouchEvent ? !0 : e.cancelable && (!this.needsClick(this.targetElement) || this.cancelNextClick) ? (e.stopImmediatePropagation ? e.stopImmediatePropagation() : e.propagationStopped = !0, e.stopPropagation(), e.preventDefault(), !1) : !0 : !0
}, FastClick.prototype.onClick = function (e) {
    var t;
    return this.trackingClick ? (this.targetElement = null, this.trackingClick = !1, !0) : "submit" === e.target.type && 0 === e.detail ? !0 : (t = this.onMouse(e), t || (this.targetElement = null), t)
}, FastClick.prototype.destroy = function () {
    var e = this.layer;
    deviceIsAndroid && (e.removeEventListener("mouseover", this.onMouse, !0), e.removeEventListener("mousedown", this.onMouse, !0), e.removeEventListener("mouseup", this.onMouse, !0)), e.removeEventListener("click", this.onClick, !0), e.removeEventListener("touchstart", this.onTouchStart, !1), e.removeEventListener("touchmove", this.onTouchMove, !1), e.removeEventListener("touchend", this.onTouchEnd, !1), e.removeEventListener("touchcancel", this.onTouchCancel, !1)
}, FastClick.notNeeded = function (e) {
    var t, n, i;
    if ("undefined" == typeof window.ontouchstart)return !0;
    if (n = +(/Chrome\/([0-9]+)/.exec(navigator.userAgent) || [, 0])[1]) {
        if (!deviceIsAndroid)return !0;
        if (t = document.querySelector("meta[name=viewport]")) {
            if (-1 !== t.content.indexOf("user-scalable=no"))return !0;
            if (n > 31 && document.documentElement.scrollWidth <= window.outerWidth)return !0
        }
    }
    if (deviceIsBlackBerry10 && (i = navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/), i[1] >= 10 && i[2] >= 3 && (t = document.querySelector("meta[name=viewport]")))) {
        if (-1 !== t.content.indexOf("user-scalable=no"))return !0;
        if (document.documentElement.scrollWidth <= window.outerWidth)return !0
    }
    return "none" === e.style.msTouchAction ? !0 : !1
}, FastClick.attach = function (e, t) {
    return new FastClick(e, t)
}, define("vendor/fastclick_release", function () {
}), window.zenjs = window.zenjs || {}, function (e) {
    e.Browser = {
        cookie: function () {
            var e = new Date, t = +e, n = 864e5, i = function (e) {
                var t = document.cookie, n = "\\b" + e + "=", i = t.search(n);
                if (0 > i)return "";
                i += n.length - 2;
                var o = t.indexOf(";", i);
                return 0 > o && (o = t.length), t.substring(i, o) || ""
            }, o = function (e, t, n) {
                if (!e)return "";
                var i = [];
                for (var o in e)i.push(encodeURIComponent(o) + "=" + (n ? encodeURIComponent(e[o]) : e[o]));
                return i.join(t || ",")
            };
            return function (r, a) {
                if (void 0 === a)return i(r);
                if ("string" == typeof a || a instanceof String) {
                    if (a)return document.cookie = r + "=" + a + ";", a;
                    a = {expires: -100}
                }
                a = a || {};
                var s = r + "=" + (a.value || "") + ";";
                delete a.value, void 0 !== a.expires && (e.setTime(t + a.expires * n), a.expires = e.toGMTString()), s += o(a, ";"), document.cookie = s
            }
        }()
    }
}(window.zenjs), define && define("zenjs/util/cookie", [], function () {
    return window.zenjs.Browser
}), define("wap/base/base", ["wap/base/log", "zenjs/util/ua", "vendor/fastclick_release", "zenjs/util/cookie", "zenjs/util/ready"], function () {
    document.addEventListener("click", function () {
    }, !0);
    var e = window.Zepto || window.jQuery || e;
    e.kdt = e.kdt || {};
    window.zenjs.UA;
    e.extend(e.kdt, {
        openLink: function (e, t) {
            if (void 0 !== e && null !== e)if (t = t || !1) {
                var n = window.open(e, "_blank");
                n.focus()
            } else location.href = e
        }
    }), e(document).on("click", "a", function (t) {
        var n = e(this), i = n.attr("href"), o = "_blank" === n.attr("target"), r = n.data("goods-id"), a = n.prop("title") || n.text(), s = e.trim(i);
        if ("" !== s && 0 !== s.indexOf("javascript") && 0 !== s.indexOf("tel") && !n.hasClass("js-no-follow")) {
            var l = zenjs.Args.get("kdtfrom"), c = zenjs.Args.get("from");
            (l || c) && (i = zenjs.Args.add(i, {kdtfrom: l, from: c}));
            var d = i, u = "";
            window.Logger && (u = Logger.getSpm()), i.match(/^https?:\/\/\S*\.?(koudaitong\.com|kdt\.im|youzan\.com)/) && (d = zenjs.Args.add(i, {spm: u}));
            var f = {fm: "click", url: i, title: e.trim(a)};
            t.fromMenu && e.extend(f, {click_type: "menu"}), null !== r && void 0 !== r && e.extend(f, {click_id: r}), window.Logger && Logger.log(f).then(function () {
                (zenjs.UA.isMobile() || !o) && e.kdt.openLink(d)
            }), zenjs.UA.isMobile() || !o ? t.preventDefault() : n.attr("href", d)
        }
    }), window.Logger && Logger.log({fm: "display", display_goods: ""});
    var t = (e(document.documentElement), e(".js-mp-info")), n = window.navigator.userAgent, i = n.match(/MicroMessenger\/(\d+(\.\d+)*)/), o = null !== i && i.length, r = o ? i[1] : "", a = r.split("."), s = [5, 2, 0], l = !0;
    for (var c in s) {
        if (!a[c])break;
        if (parseInt(a[c]) < s[c]) {
            l = !0;
            break
        }
        if (parseInt(a[c]) > s[c]) {
            l = !1;
            break
        }
    }
    var d = zenjs.UA.isAndroid() && zenjs.UA.isWeixin() && l;
    d || t.on("click", ".js-follow-mp", function () {
        return window.showGuide && window.showGuide("follow"), !1
    });
    var u = zenjs.Args.get("promote"), f = zenjs.Args.get("from"), g = e("a");
    u && g.each(function () {
        var t = e(this), n = t.attr("href");
        n = zenjs.Args.add(n, {promote: u}), n && 0 !== n.indexOf("tel") && t.attr("href", n)
    }), f && g.each(function () {
        var t = e(this), n = t.attr("href");
        n = zenjs.Args.add(n, {from: f}), n && 0 !== n.indexOf("tel") && t.attr("href", n)
    }), window.zenjs.ready && window.zenjs.ready(function () {
        document.getElementsByClassName("vote-page").length || FastClick && FastClick.attach(document.body)
    });
    var h = function (e) {
        var t = new Image;
        t.onload = t.onerror = function () {
            e(2 == t.height)
        }, t.src = "data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA"
    };
    if ("object" == typeof window.localStorage)try {
        var p = localStorage.getItem("canwebp");
        "ok" == p ? zenjs.Browser.cookie("_canwebp", {value: "1", path: "/", domain: "koudaitong.com", expires: 3650}) : "no" != p && h(function (e) {
            localStorage.setItem("canwebp", e ? "ok" : "no"), e && zenjs.Browser.cookie("_canwebp", {
                value: "1",
                path: "/",
                domain: "koudaitong.com",
                expires: 3650
            })
        })
    } catch (w) {
    }
    /*try {
        window.console && window.console.log && (console.log("%c", "padding:20px 200px; line-height:80px;background:url('http://kdt-static.qiniucdn.com/v2/image/intro/logo.png') no-repeat center center"), console.log("如何让我遇见你，在你最美的时候\n加入有赞，去中心化的消费平台，你，可以让世界更美好一些\n"),
            console.log("请将简历发送至 %c hr@qima-inc.com（ 邮件标题请以“姓名-应聘XX职位-来自console”命名）", "color:red"), console.log("职位介绍：http://job.youzan.com\n\n"))
    } catch (w) {
    }*/
}), require(["wap/base/fullguide", "wap/base/log", "wap/base/lazy_load", "wap/base/js_bridge", "wap/base/wx", "wap/base/motify", "wap/components/footer_auto", "wap/base/base", "zenjs/util/cookie"], function () {
}), define("main", function () {
});