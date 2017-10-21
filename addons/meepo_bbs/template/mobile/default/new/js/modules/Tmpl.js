!function(t, n) {
                "function" == typeof define && define.amd ? define([], n) : t.Tmpl || (t.Tmpl = n())
            } (this,
            function() {
                function t(i, e, r) {
                    return this instanceof t ? (this.tmplFunction = n[i], void(e && this.render(e, r))) : new t(i, e, r)
                }
                var n = {};
                return t.addTmpl = function(t, i) {
                    n[t] = i
                },
                t.prototype = {
                    render: function(t, n) {
                        return "string" == typeof this.tmplFunction ? this.tmplEle = sodaRender(this.tmplFunction, t) : this.tmplFunction && (this.tmplString = this.tmplFunction(t, n)),
                        this
                    },
                    appendTo: function(t, n) {
                        var i;
                        if (this.tmplEle) i = this.tmplEle;
                        else {
                            var e = document.createElement("div");
                            e.innerHTML = this.tmplString,
                            i = document.createDocumentFragment();
                            for (var r = null; r = e.childNodes[0];) i.appendChild(r)
                        }
                        return "fadeIn" === n && $(i).children().addClass("ui-fade").css({
                            "-webkit-transition": "opacity 0.15s ease-in",
                            opacity: 0
                        }),
                        t.append(i),
                        "fadeIn" === n && setTimeout(function() {
                            t.children(".ui-fade").css("opacity", 1).removeClass("ui-fade")
                        },
                        0),
                        this
                    },
                    update: function(t) {
                        t.empty(),
                        this.appendTo(t)
                    },
                    valueOf: function() {
                        return this.tmplString || this.tmplEle && this.tmplEle.innerHTML || ""
                    },
                    toString: function() {
                        return this.tmplString || this.tmplEle && this.tmplEle.innerHTML || ""
                    }
                },
                t
            });