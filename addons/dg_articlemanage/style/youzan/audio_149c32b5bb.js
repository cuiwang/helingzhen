/**
 * Created by shizhongying on 8/28/15.
 */

!function (t) {
    t($)
}(function (t) {
    function i() {
        var t = document.createElement("input");
        return t.setAttribute("type", "range"), "text" !== t.type
    }

    function e(t, i) {
        var e = Array.prototype.slice.call(arguments, 2);
        return setTimeout(function () {
            return t.apply(null, e)
        }, i)
    }

    function s(t, i) {
        return i = i || 100, function () {
            if (!t.debouncing) {
                var e = Array.prototype.slice.apply(arguments);
                t.lastReturnVal = t.apply(window, e), t.debouncing = !0
            }
            return clearTimeout(t.debounceTimeout), t.debounceTimeout = setTimeout(function () {
                t.debouncing = !1
            }, i), t.lastReturnVal
        }
    }

    function n(t) {
        return 0 !== t.offsetWidth || 0 !== t.offsetHeight ? !1 : !0
    }

    function a(t) {
        for (var i = [], e = t.parentNode; n(e);)i.push(e), e = e.parentNode;
        return i
    }

    function o(t, i) {
        var e = a(t), s = e.length, n = [], o = t[i];
        if (s) {
            for (var r = 0; s > r; r++)n[r] = e[r].style.display, e[r].style.display = "block", e[r].style.height = "0", e[r].style.overflow = "hidden", e[r].style.visibility = "hidden";
            o = t[i];
            for (var h = 0; s > h; h++)e[h].style.display = n[h], e[h].style.height = "", e[h].style.overflow = "", e[h].style.visibility = ""
        }
        return o
    }

    function r(i, n) {
        if (this.$window = t(window), this.$document = t(document), this.$element = t(i), this.options = t.extend({}, p, n), this._defaults = p, this._name = h, this.startEvent = this.options.startEvent.join("." + h + " ") + "." + h, this.moveEvent = this.options.moveEvent.join("." + h + " ") + "." + h, this.endEvent = this.options.endEvent.join("." + h + " ") + "." + h, this.polyfill = this.options.polyfill, this.onInit = this.options.onInit, this.onSlide = this.options.onSlide, this.onSlideEnd = this.options.onSlideEnd, this.polyfill && d)return !1;
        this.identifier = "js-" + h + "-" + u++, this.min = parseFloat(this.$element[0].getAttribute("min") || 0), this.max = parseFloat(this.$element[0].getAttribute("max") || 100), this.value = parseFloat(this.$element[0].value || this.min + (this.max - this.min) / 2), this.step = parseFloat(this.$element[0].getAttribute("step") || 1), this.toFixed = (this.step + "").replace(".", "").length - 1, this.$fill = t('<div class="' + this.options.fillClass + '" />'), this.$handle = t('<div class="' + this.options.handleClass + '" />'), this.$range = t('<div class="' + this.options.rangeClass + '" id="' + this.identifier + '" />').insertAfter(this.$element).prepend(this.$fill, this.$handle), this.$element.css({
            position: "absolute",
            width: "1px",
            height: "1px",
            overflow: "hidden",
            opacity: "0"
        }), this.handleDown = t.proxy(this.handleDown, this), this.handleMove = t.proxy(this.handleMove, this), this.handleEnd = t.proxy(this.handleEnd, this), this.init();
        var a = this;
        this.$window.on("resize." + h, s(function () {
            e(function () {
                a.update()
            }, 300)
        }, 20)), this.$document.on(this.startEvent, "#" + this.identifier + ":not(." + this.options.disabledClass + ")", this.handleDown), this.$element.on("change." + h, function (t, i) {
            if (!i || i.origin !== h) {
                var e = t.target.value, s = a.getPositionFromValue(e);
                a.setPosition(s)
            }
        })
    }

    var h = "rangeslider", l = [], u = 0, d = i(), p = {
        polyfill: !0,
        rangeClass: "rangeslider",
        disabledClass: "rangeslider--disabled",
        fillClass: "rangeslider__fill",
        handleClass: "rangeslider__handle",
        startEvent: ["mousedown", "touchstart", "pointerdown"],
        moveEvent: ["mousemove", "touchmove", "pointermove"],
        endEvent: ["mouseup", "touchend", "pointerup"]
    };
    r.prototype.init = function () {
        this.onInit && "function" == typeof this.onInit && this.onInit(), this.update()
    }, r.prototype.setMax = function (t) {
        this.max = t
    }, r.prototype.update = function () {
        this.handleWidth = o(this.$handle[0], "offsetWidth"), this.rangeWidth = o(this.$range[0], "offsetWidth"), this.maxHandleX = this.rangeWidth - this.handleWidth, this.grabX = this.handleWidth / 2, this.position = this.getPositionFromValue(this.value), this.$element[0].disabled ? this.$range.addClass(this.options.disabledClass) : this.$range.removeClass(this.options.disabledClass), this.setPosition(this.position)
    }, r.prototype.handleDown = function (t) {
        if (t.preventDefault(), this.$document.on(this.moveEvent, this.handleMove), this.$document.on(this.endEvent, this.handleEnd), !((" " + t.target.className + " ").replace(/[\n\t]/g, " ").indexOf(this.options.handleClass) > -1)) {
            var i = this.getRelativePosition(t), e = this.$range[0].getBoundingClientRect().left, s = this.getPositionFromNode(this.$handle[0]) - e;
            this.setPosition(i - this.grabX), i >= s && i < s + this.handleWidth && (this.grabX = i - s)
        }
    }, r.prototype.setStatus = function (t) {
        this.__status = t
    }, r.prototype.getStatus = function (t) {
        return this.__status
    }, r.prototype.handleMove = function (t) {
        t.preventDefault();
        var i = this.getRelativePosition(t);
        this.setPosition(i - this.grabX), this.setStatus("MOVE")
    }, r.prototype.handleEnd = function (t) {
        t.preventDefault(), this.$document.off(this.moveEvent, this.handleMove), this.$document.off(this.endEvent, this.handleEnd), this.setStatus(void 0), this.onSlideEnd && "function" == typeof this.onSlideEnd && this.onSlideEnd(this.position, this.value)
    }, r.prototype.cap = function (t, i, e) {
        return i > t ? i : t > e ? e : t
    }, r.prototype.setPosition = function (t) {
        var i, e;
        i = this.getValueFromPosition(this.cap(t, 0, this.maxHandleX)), e = this.getPositionFromValue(i), this.$fill[0].style.width = e + this.grabX + "px", this.$handle[0].style.left = e + "px", this.setValue(i), this.position = e, this.value = i, this.onSlide && "function" == typeof this.onSlide && this.onSlide(e, i)
    }, r.prototype.getPositionFromNode = function (t) {
        for (var i = 0; null !== t;)i += t.offsetLeft, t = t.offsetParent;
        return i
    }, r.prototype.getRelativePosition = function (t) {
        var i = this.$range[0].getBoundingClientRect().left, e = 0;
        return t.originalEvent = t.originalEvent || t, "undefined" != typeof t.pageX && 0 !== t.pageX ? e = t.pageX : "undefined" != typeof t.originalEvent.clientX ? e = t.originalEvent.clientX : t.originalEvent.touches && t.originalEvent.touches[0] && "undefined" != typeof t.originalEvent.touches[0].clientX ? e = t.originalEvent.touches[0].clientX : t.currentPoint && "undefined" != typeof t.currentPoint.x && (e = t.currentPoint.x), e - i
    }, r.prototype.getPositionFromValue = function (t) {
        var i, e;
        return i = (t - this.min) / (this.max - this.min), e = i * this.maxHandleX
    }, r.prototype.getValueFromPosition = function (t) {
        var i, e;
        return i = t / (this.maxHandleX || 1), e = this.step * Math.round(i * (this.max - this.min) / this.step) + this.min, Number(e.toFixed(this.toFixed))
    }, r.prototype.setValue = function (t) {
        t !== this.value && this.$element.val(t).trigger("change", {origin: h})
    }, r.prototype.destroy = function () {
        this.$document.off(this.startEvent, "#" + this.identifier, this.handleDown), this.$element.off("." + h).removeAttr("style").removeData("plugin_" + h), this.$range && this.$range.length && this.$range[0].parentNode.removeChild(this.$range[0]), l.splice(l.indexOf(this.$element[0]), 1), l.length || this.$window.off("." + h)
    }, t.fn[h] = function (i) {
        return this.each(function () {
            var e = t(this), s = e.data("plugin_" + h);
            s || (e.data("plugin_" + h, s = new r(this, i)), l.push(this)), "string" == typeof i && s[i]()
        })
    }
}), define("wap/showcase/modules/audio/rangeslider", function () {
}), define("wap/showcase/modules/audio/audio", ["require", "wap/showcase/modules/audio/rangeslider"], function (t) {
    t("wap/showcase/modules/audio/rangeslider"), function () {
        var t = "PLAY", i = "STOP", e = "PAUSE", s = "ERROR", n = "custom-audio-status-loading", a = "custom-audio-status-play", o = "custom-audio-status-stop", r = "custom-audio-status-pause", h = function () {
        }, l = function (t) {
            if (!(this instanceof l))return new l(t);
            if (this.target = t.target, this.$target = $(this.target), this.$trigger = this.$target.find(".js-trigger"), this.$trigger.length <= 0 && (this.$trigger = this.$target), this.$duration = this.$target.find(".js-duration"), this.$currentTime = this.$target.find(".js-current-time"), this.$percentage = this.$target.find(".js-percentage"), this.$status = this.$target.find(".js-status"), this.$loading = this.$target.find(".js-loading"), this.$title = this.$target.find(".js-title"), this.pureSrc = this.$target.data("src"), this.src = this.pureSrc + "?avthumb/mp3/ab/64k/writeXing/0", this.reload = this.$target.data("reload"), this.loop = this.$target.data("loop"), this.callback = {}, this.callback.play = t.play || h, this.callback.pause = t.pause || h, this.callback.stop = t.stop || h, this.$target.length <= 0)throw new Error("音频DOM对象不能为空");
            if (!this.src)throw new Error("音频文件地址不能为空");
            this.audio = new Audio, this.$audio = $(this.audio), this.init(), l._cache.indexOf(this) < 0 && l._cache.push(this)
        };
        l._cache = [], l.prototype = {
            init: function () {
                this.addEvent()
            }, initRangeSlider: function () {
                var t = this;
                this.$percentage.attr("max", this._duration), this.$percentage.rangeslider({
                    polyfill: !1, onSlide: function (i, e) {
                        t._updateCurrentTime(e)
                    }, onSlideEnd: function (i, e) {
                        t.audio.currentTime = e
                    }
                })
            }, leading: function (t) {
                var i = 1 === t.toString().length ? "0" : "";
                return i
            }, updateDuration: function () {
                var t = this;
                if (!this._duration) {
                    var i = this.pureSrc;
                    i = i.split("?")[0] + "?avinfo", $.ajax({url: i, dataType: "json"}).done(function (i) {
                        var e = Math.floor(+i.format.duration);
                        t._duration = e, t.initRangeSlider(), t._updateDuration(e)
                    })
                }
            }, _updateDuration: function (t) {
                var i = parseInt(t / 60), e = parseInt(t % 60), s = this.leading(i) + i + ":" + this.leading(e) + e;
                this.$duration.text(s)
            }, updateCurrentTime: function (t) {
                var i = t.target, e = Math.floor(+i.currentTime), s = Math.floor(+i.duration), n = this.$percentage.data("plugin_rangeslider");
                if (s !== this._duration && (this._duration = s, n && n.length > 0 && n.setMax(s)), !isNaN(s)) {
                    if (this.loaded(), n && n.length > 0) {
                        var a = n.getStatus();
                        if (a)return
                    }
                    this._updateCurrentTime(e)
                }
            }, _updateCurrentTime: function (t) {
                var i = parseInt(t / 60), e = parseInt(t % 60), s = this.leading(i) + i + ":" + this.leading(e) + e + "/";
                this.$currentTime.text(s)
            }, updatePercentage: function (t) {
                if (!(this.$percentage.length < 1)) {
                    var i = this.$percentage.data("plugin_rangeslider").getStatus();
                    if (!i) {
                        var e = t.target, s = Math.floor(+e.currentTime);
                        this.$percentage.val(s).change()
                    }
                }
            }, showStatus: function (t) {
                this.$title.hide(), this.$status.text(t).show()
            }, clearStatus: function () {
                this.$title.show(), this.$status.hide(), this.$status.text("")
            }, loading: function () {
                this.$loading.css("display", "block"), this.$target.addClass(n)
            }, loaded: function () {
                this.$loading.css("display", "none"), this._status === t && (this.$target.addClass(a), this.$target.removeClass(r), this.$target.removeClass(o), this.$target.removeClass(n))
            }, addEvent: function () {
                var i = this;
                this.$audio.on("load play", function (t) {
                    i._loaded = !0, i.clearStatus()
                }), this.$audio.on("canplay", function () {
                    i.loaded()
                }), this.$audio.on("timeupdate", function (t) {
                    i.updateCurrentTime(t), i.updatePercentage(t)
                }), this.$audio.on("error", function (t) {
                    i.showStatus("加载失败，点击重试"), i._loaded = !1, i._status = s, i.$loading.css("display", "none"), i.$target.removeClass(a), i.$target.removeClass(r), i.$target.removeClass(o), i.$target.removeClass(n)
                }), this.$audio.on("ended", function (t) {
                    i.stop()
                }), this.$trigger.on("click", function () {
                    i.load(), i._status !== t ? i.play() : i.reload ? i.stop() : i.pause()
                })
            }, load: function () {
                if (!this._loaded) {
                    this.loading(), this.updateDuration();
                    var t = {loop: this.loop, preload: "auto", src: this.src};
                    for (var i in t)t.hasOwnProperty(i) && i in this.audio && (this.audio[i] = t[i])
                }
            }, play: function () {
                if (this._status !== t) {
                    for (var i = 0; i < l._cache.length; i++)l._cache[i] !== this && l._cache[i].stop();
                    this.audio.play(), this._status = t, this.callback.play()
                }
            }, pause: function () {
                this._status === t && (this.audio.pause(), this._status = e, this.callback.pause(), this.$target.addClass(r), this.$target.removeClass(a), this.$target.removeClass(o))
            }, stop: function () {
                this._status === t && (this.audio.pause(), this.audio.currentTime = 0, this._status = i, this.callback.stop(), this.$target.addClass(o), this.$target.removeClass(r), this.$target.removeClass(a))
            }
        }, l.prototype.constructor = l, $.fn.audioPlayer = function (t) {
            t = t || {}, $.each(this, function (i, e) {
                var s = $.extend({target: e}, t);
                l(s)
            })
        }
    }()
}), require(["wap/showcase/modules/audio/audio"], function () {
    $(".js-audio").audioPlayer()
}), define("main", function () {
});
