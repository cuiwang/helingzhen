!function(a, b) {
    a.ScrollHandle = b(a.$)
}(this, function() {
    var a, b = 300, c = 0, d = {
        init: function(a) {
            var b = $(a.container);
            this.container = b,
            this.scollEndCallback = a.scollEndCallback,
            this.scrollToBottomCallback = a.scrollToBottomCallback,
            this.scrollToHalfCallback = a.scrollToHalfCallback,
            this.bindHandler()
        },
        bindHandler: function() {
            this.container.on("scroll", $.proxy(this.onScroll, this))
        },
        onScroll: function(d) {
            var e, f, g, h = this, i = d.target;
            if (i === document)
                e = window.scrollY,
                g = window.innerHeight,
                f = document.body.scrollHeight;
            else {
                var j = window.getComputedStyle(i);
                e = i.scrollTop,
                g = parseInt(j.height) + parseInt(j.paddingTop) + parseInt(j.paddingBottom) + parseInt(j.marginTop) + parseInt(j.marginBottom),
                f = i.scrollHeight
            }
            e + g >= 2 * f / 3 && e > c ? this.scrollToHalfCallback && this.scrollToHalfCallback(d) : e + g >= f && this.scrollToBottomCallback && this.scrollToBottomCallback(d),
            c = e,
            clearTimeout(a),
            a = setTimeout(function() {
                h.scollEndCallback && h.scollEndCallback(d)
            }, b)
        }
    };
    return d
})