!function(a, b) {
    a.LazyExpose = b()
}(this, function() {
    var a = function(a, b, c) {
        var d;
        return !1 !== c && (c = !0),
        function() {
            function e() {
                c || a.apply(f, g),
                d = null 
            }
            var f = this
              , g = arguments;
            d ? clearTimeout(d) : c && a.apply(f, g),
            d = setTimeout(e, b || 100)
        }
    }
      , b = 0
      , c = {}
      , d = window.innerHeight
      , e = {
        start: !0,
        defer: 300,
        handler: function() {},
        container: window
    };
    return {
        init: function(b, c) {
            console.debug("广告LazyExpose init..."),
            this.elements = b,
            this.options = c = $.extend({}, e, "object" == typeof c && c),
            this.handler = c.handler,
            this.delay = c.delay || 0,
            this.container = $.os.ios ? c.container : window,
            this.$container = $(this.container),
            this.onScroll = a($.proxy(this._onScroll, this), c.defer, !1),
            this.status = 1,
            setTimeout(function() {
                this.inited || (this.inited = !0,
                this.containerHeight = this._getContainerHeight(),
                this.$container.on("scroll", this.onScroll))
            }
            .bind(this), this.options.defer)
        },
        _getContainerHeight: function() {
            var a = this.container;
            if (a.document)
                return d;
            var b = window.getComputedStyle(a);
            return parseInt(b.height) + parseInt(b.paddingTop) + parseInt(b.paddingBottom) + parseInt(b.marginTop) + parseInt(b.marginBottom)
        },
        _onScroll: function() {
            if (console.debug(">>>>>>>>>onScroll"),
            this.status) {
                var a = this.elements;
                if (!a || !a.length)
                    return void this._destory();
                var c, d, e, f = this;
                for (d = 0,
                e = a.length; e > d; d++) {
                    c = a[d],
                    c.cacheId = c.cacheId || ++b;
                    var g = f._elementInViewport(c);
                    c && f.isWebViewHidden !== !0 && g && setTimeout(function() {
                        this._elementInViewport(c) && (this.handler(),
                        Array.prototype.splice.call(this.elements, d, 1),
                        1 === e && this._destory())
                    }
                    .bind(f), f.delay)
                }
            }
        },
        _elementInViewport: function(a) {
            if ("none" === $(a).css("display"))
                return !1;
            var b = this.container
              , d = a.cacheId
              , e = c[d] || $(a).offset()
              , f = 0;
            return b ? (f += b.document ? b.scrollY || b.pageYOffset : (b.scrollTop || window.scrollY) + (b.offsetTop || window.pageYOffset),
            c[d] = e,
            f >= e.top - e.height) : !1
        },
        _destory: function() {
            console.debug(">>>>ad scroll off"),
            this.$container.off("scroll", this.onScroll),
            c = {},
            this.status = 0,
            this.elements = null ,
            this.container = null ,
            this.$container = null 
        }
    }
});