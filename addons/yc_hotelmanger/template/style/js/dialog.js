/*from tccdn minify at 2015-6-25 8:22:10,file：/touch/public/dialog/0.0.1/dialog.js?v=201506241933*/
!function () {
    function e() {
        var a = f.attr("hl-cls");
        clearTimeout(g), f.removeClass(a).removeAttr("hl-cls"), f = null, h.off("touchend touchmove touchcancel", e)
    }

    var f, g, h = $(document);
    $.fn.highlight = function (a, b) {
        return this.each(function () {
            var c = $(this);
            c.css({"-webkit-tap-highlight-color": "rgba(255,255,255,0)"}).off("touchstart.hl"), a && c.on("touchstart.hl", function (d) {
                var j;
                f = b ? (j = $(d.target).closest(b, this)) && j.length && j : c, f && (f.attr("hl-cls", a), g = setTimeout(function () {
                    f.addClass(a)
                }, 100), h.on("touchend touchmove touchcancel", e))
            })
        })
    }
}(), function () {
    function e(b) {
        this._options = this._options || {}, $.extend(this._options, h), $.extend(this._options, b), this.init()
    }

    function f(i) {
        var j, k, l, m = this, n = m._options;
        switch (i.type) {
            case n.RotateChangeEvent:
                n._isOpen && this.refresh();
                break;
            case"touchmove":
                n.scrollMove && i.preventDefault();
                break;
            case"click":
                if (n._mask && ($.contains(n._mask[0], i.target) || n._mask[0] === i.target)) {
                    return "function" == typeof n.maskClick && n.maskClick()
                }
                k = n._wrap.get(0), (j = $(i.target).closest(".close", k)) && j.length ? m.close() : (j = $(i.target).closest(".ui-dialog-btns .ui-btn", k)) && j.length && (l = n.buttons[j.attr("data-key")], l && l.apply(m, arguments))
        }
    }

    var g = {
        close: '<a class="close" title="关闭"></a>',
        mask: '<div class="ui-mask"></div>',
        title: '<div class="ui-dialog-title"></div>',
        wrap: '<div class="ui-dialog"><div class="ui-dialog-content"></div>BTNSTRING</div> '
    }, h = {
        autoOpen: !1,
        className: "",
        buttons: null,
        closeBtn: !1,
        mask: !0,
        width: 300,
        height: "auto",
        title: void 0,
        content: null,
        scrollMove: !0,
        container: null,
        maskClick: null,
        beforeOpen: null,
        afterOpen: null,
        beforeClose: null,
        afterClose: null,
        style: null,
        closeTime: 2000,
        className: ""
    };
    e.prototype.getWrap = function () {
        return this._options._wrap
    }, e.prototype.init = function () {
        var b, c = this, k = c._options, l = 0, m = {};
        k.eventHand = $.proxy(f, c), k._container = $(k.container || document.body), (k._cIsBody = k._container.is("body")) || k._container.addClass("ui-dialog-container`"), m.btns = b = [], k.buttons && $.each(k.buttons, function (a) {
            b.push({index: ++l, text: a, key: a})
        }), k._mask = k.mask ? $(g.mask).appendTo(k._container) : null;
        var n = "";
        if (b[0]) {
            n = '<div class="ui-dialog-btns">';
            for (var l = 0, o = b.length; o > l; l++) {
                var p = b[l];
                n += '<a class="ui-btn ui-btn-' + l + '" data-key="' + p.key + '">' + p.text + "</a>"
            }
            n += "</div>"
        }
        k._wrap = $(g.wrap.replace("BTNSTRING", n)).appendTo(k._container), k._content = $(".ui-dialog-content", k._wrap), k._title = $(g.title), k._close = k.closeBtn && $(g.close).appendTo(k._title).highlight("close-hover").on("click", function () {
            c.close()
        }), c.title(k.title), c.content(k.content), b.length && $(".ui-dialog-btns .ui-btn", k._wrap).highlight("ui-state-hover"), k._wrap.css({
            width: k.width,
            height: k.height
        }).addClass(k.className), k.RotateChangeEvent = "onorientationchange" in window ? "orientationchange" : "resize", $(window).on(k.RotateChangeEvent, k.eventHand), k._wrap.on("click", k.eventHand), k._mask && k._mask.on("click", k.eventHand), k.autoOpen && c.open()
    }, e.prototype.calculate = function () {
        var i, j, k = this, l = k._options, m = document.body, n = {}, o = l._cIsBody, p = Math.round;
        return l.mask && (n.mask = o ? {
            width: "100%",
            height: Math.max(m.scrollHeight, m.clientHeight)
        } : {width: "100%", height: "100%"}), i = l._wrap.offset(), j = $(window), n.wrap = {
            left: "50%",
            marginLeft: -p(l._wrap.width() / 2) + "px",
            top: o ? p(j.height() / 2) + window.pageYOffset : "50%",
            marginTop: -p(l._wrap.height() / 2) + "px"
        }, n
    }, e.prototype.refresh = function () {
        var i, j, k = this, l = k._options;
        return j = function () {
            i = k.calculate(), i.mask && l._mask.css(i.mask), l._wrap.css(i.wrap)
        }, $.os && $.os.ios && document.activeElement && /input|textarea|select/i.test(document.activeElement.tagName) ? (document.body.scrollLeft = 0, setTimeout(j, 200)) : setTimeout(j, 200), k
    }, e.prototype.open = function (i) {
        var j, k = this._options, l = this;
        if (!k._isOpen) {
            if (i && l.content(i), k._isOpen = !0, "tip" == k.style) {
                k.mask && k._mask.addClass("ui-dialog-tran-03"), k._wrap.addClass("ui-dialog-black");
                var m = l.getWrap();
                setTimeout(function () {
                    k.mask && k._mask.animate({opacity: 0}, 1000, "ease-out", function () {
                        k._mask.css({opacity: ""})
                    }), m.animate({opacity: 0}, 1000, "ease-out", function () {
                        m.css({opacity: "1"}), l.close()
                    })
                }, k.closeTime)
            }
            if ("function" == typeof k.beforeOpen && (j = k.beforeOpen()), j) {
                return this
            }
            k._wrap.css({display: "block"}), k._mask && k._mask.css({display: "block"}), this.refresh(), $(document).on("touchmove", k.eventHand), "function" == typeof k.afterOpen && k.afterOpen()
        }
    }, e.prototype.close = function (c) {
        var d = this._options;
        return "tip" == d.style && d.mask && d._mask.removeClass("ui-dialog-tran-03"), "function" == typeof d.beforeClose && d.beforeClose(), d._isOpen = !1, d._wrap.css({display: "none"}), c ? this : (d._mask && d._mask.css({display: "none"}), $(document).off("touchmove", d.eventHand), "function" == typeof d.afterClose && d.afterClose(this), this)
    }, e.prototype.title = function (d) {
        var i = this._options, j = void 0 !== d;
        return j && (d = (i.title = d) ? "<h3>" + d + "</h3>" : d, i._title.html(d)[d ? "prependTo" : "remove"](i._wrap), i._close && i._close.prependTo(i.title ? i._title : i._wrap)), j ? this : i.title
    }, e.prototype.content = function (d) {
        var i = this._options, j = void 0 !== d;
        return j && i._content.empty().append(i.content = d), j ? this : i.content
    }, e.prototype.destroy = function () {
        var b = this._options;
        return $(window).off(b.RotateChangeEvent, b.eventHand), $(document).off("touchmove", b.eventHand), b._wrap.off("click", b.eventHand).remove(), b._mask && b._mask.off("click", b.eventHand).remove(), b._close && b._close.highlight(), this
    }, $.dialog = function (a) {
        return new e(a)
    }
}(), function () {
    function e(d, i) {
        var j = {autoOpen: !0, closeBtn: !1, style: "tip", mask: i, closeTime: 1000, content: d};
        return $.dialog(j)
    }

    function f(a, c) {
        c ? "object" == typeof h ? h.open(a) : h = e(a, !0) : "object" == typeof g ? g.open(a) : g = e(a, !1)
    }

    var g, h;
    $.tip = f
}();