!function(a, b) {
    "function" == typeof define && define.amd ? define([], b) : a.Tmpl || (a.Tmpl = b())
}(this, function() {
    function a(c, d, e) {
        return this instanceof a ? (this.tmplFunction = b[c],
        void (d && this.render(d, e))) : new a(c,d,e)
    }
    var b = {};
    return a.addTmpl = function(a, c) {
        b[a] = c
    }
    ,
    a.prototype = {
        render: function(a, b) {
            return "string" == typeof this.tmplFunction ? this.tmplEle = sodaRender(this.tmplFunction, a) : this.tmplFunction && (this.tmplString = this.tmplFunction(a, b)),
            this
        },
        appendTo: function(a, b) {
            var c;
            if (this.tmplEle)
                c = this.tmplEle;
            else {
                var d = document.createElement("div");
                d.innerHTML = this.tmplString,
                c = document.createDocumentFragment();
                for (var e = null ; e = d.childNodes[0]; )
                    c.appendChild(e)
            }
            return "fadeIn" === b && $(c).children().addClass("ui-fade").css({
                "-webkit-transition": "opacity 0.15s ease-in",
                opacity: 0
            }),
            a.append(c),
            "fadeIn" === b && setTimeout(function() {
                a.children(".ui-fade").css("opacity", 1).removeClass("ui-fade")
            }, 0),
            this
        },
        update: function(a) {
            a.empty(),
            this.appendTo(a)
        },
        valueOf: function() {
            return this.tmplString || this.tmplEle && this.tmplEle.innerHTML || ""
        },
        toString: function() {
            return this.tmplString || this.tmplEle && this.tmplEle.innerHTML || ""
        }
    },
    a
});