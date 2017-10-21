function createbjMusic(e) {
    try {
        if (e == null || e == "") return !1;
        var t = $(this),
            n = document.createElement("audio"),
            r = document.createElement("source");
        r.type = "audio/mpeg", r.src = e, r.autoplay = !0, r.controls = "controls", n.loop = "-1", n.appendChild(r), n.onloadstart = !0, n.autoplay = !0;
        var i = $('<div style="position:fixed;width:120px;height:120px;top:0px;right:0px;z-index:20;"></div>'),
            s = $('<img src="'+APP_PUBLIC+'/images/play.png" style="position:fixed; top:10px;right:10px;">');
        s.appendTo(i), n.addEventListener("play", function() {
            s.attr("src", ""+APP_PUBLIC+"/images/stop.png")
        }), document.addEventListener("touchstart", function(e) {
            try {
                var t = e || window.event,
                    r = e.touches[0],
                    s = Number(r.clientX),
                    o = Number(r.clientY);
                i.prop("offsetLeft") < s && s < i.prop("offsetLeft") + i.width() && 0 < o && o < i.height() || n.play()
            } catch (t) {
                console.log(t.message)
            }
        }, !1), i.click(function() {
            n.paused ? n.play() : (n.pause(), s.attr("src", ""+APP_PUBLIC+"/images/play.png"))
        }), i.appendTo($(document.body)), n.play(),
        function() {
            var e = function() {
                n.play()
            };
            window.parent.document.addEventListener ? window.parent.document.addEventListener("WeixinJSBridgeReady", e, !1) : window.parent.document.attachEvent && (window.parent.document.attachEvent("WeixinJSBridgeReady", e), window.parent.document.attachEvent("onWeixinJSBridgeReady", e))
        }()
    } catch (o) {
        return
    }
}

function cardWaitReady() {
    try {
        typeof window.parent.stopRoll != "undefined" && window.parent.stopRoll()
    } catch (e) {
        console.log(e.message)
    }
}

function CardAutoAnimation(e, t, n, r) {
    var i = $(e),
        s = $(n),
        o = $(t),
        u = $(t).html(),
        a = $(r),
        f = parseInt($(e).css("font-size")),
        l = parseInt($(t).css("line-height")),
        c = 4,
        h = 4,
        p = new Array,
        d = function() {
            var e = i.prop("offsetTop") + f,
                t = s.prop("offsetTop");
            i.css("left", i.prop("offsetLeft") + "px"), i.css("top", i.prop("offsetTop") + "px"), o.css("top", e + f + "px"), o.css("left", i.css("left"));
            var n = t - f * 2 - e;
            c = parseInt(n / l), o.css("max-height", parseInt(n / l) * l), s.css("left", o.prop("offsetLeft") + o.width() - s.width() - f), s.css("top", o.prop("offsetTop") + o.height() + f)
        }, v = function() {
            u = u.replace(/>/g, "> "), u.substring(0, 1) != " " && o.css("text-indent", "0"), o.html(""), o.css("visibility", "hidden");
            var e = new RegExp("^[0-9a-zA-Zһ-��]+$"),
                t = o.prop("offsetLeft"),
                n = o.prop("offsetTop"),
                r = i.height() > l ? i.height() : l;
            r += 6;
            var s = 0,
                a = 0;
            for (var f = 1; f <= u.length; f++) {
                u.substr(f - 1, 1) == "<" && (f = u.indexOf(">", f) + 1), o.html(u.substring(0, f));
                var d = parseInt(o.prop("scrollHeight"));
                if (f != u.length) {
                    if (d > r) {
                        var v = e.test(u.substr(f - 1, 1)),
                            m = "";
                        u.substr(f - 1, 1) == ">" ? (m = u.substr(a, f - a), a = f) : (m = u.substr(a, v ? f - a - 1 : f - a - 2), a = v ? f - 1 : f - 2), r = d;
                        var g = '<div style="position:absolute;width:auto;height:auto;white-space:nowrap;opacity:0;' + (u.substring(0, 1) == " " && s == 0 ? "text-indent:0em;" : "") + '" data-left="' + t + '" data-top="' + (n + (s < c - 1 ? s * l : (c - 1) * l)) + '" id="split_' + +s + '">' + m + "</div>";
                        p[s] = $(g), s += 1
                    }
                } else {
                    var m = u.substr(a, u.length - a),
                        g = '<div style="position:absolute;width:auto;height:auto;white-space:nowrap;opacity:0;' + (u.substring(0, 1) == " " && s == 0 ? "text-indent:0em;" : "") + '" data-left="' + t + '" data-top="' + (n + (s < c - 1 ? s * l : (c - 1) * l)) + '" id="split_' + +s + '">' + m + "</div>";
                    p[s] = $(g), o.html(u)
                }
            }
            return h = p.length, p
        }, m = function() {};
    this.anmitionLines = function(e, t, n, r) {
        d(), m = r, g(e, t, n)
    };
    var g = function(e, t, n) {
        e.type != "writerEffect" && (e.type == "leftInShow" ? (endStyle = {
            left: "+=150px",
            opacity: 1
        }, i.css({
            left: "-=150px",
            opacity: 0
        }).delay(e.delay).transition(endStyle, e.time, function() {
            y(t, n)
        })) : e.type == "topInShow" ? (endStyle = {
            top: "+=150px",
            opacity: 1
        }, i.css({
            top: "-=150px",
            opacity: 0
        }).delay(e.delay).transition(endStyle, e.time, function() {
            y(t, n)
        })) : e.type == "rightInShow" ? (endStyle = {
            left: "-=150px",
            opacity: 1
        }, i.css({
            left: "+=150px",
            opacity: 0
        }).delay(e.delay).transition(endStyle, e.time, function() {
            y(t, n)
        })) : e.type == "bottomInShow" ? (endStyle = {
            top: "-=150px",
            opacity: 1
        }, i.css({
            top: "+=150px",
            opacity: 0
        }).delay(e.delay).transition(endStyle, e.time, function() {
            y(t, n)
        })) : e.type == "lineShow" ? (endStyle = {
            height: i.css("height"),
            opacity: 1
        }, i.css({
            height: 0
        }).delay(e.delay).animate(endStyle, e.time, function() {
            y(t, n)
        })) : (endStyle = {
            left: "+=150px",
            opacity: 1
        }, i.css({
            left: "-=150px",
            opacity: 0
        }).delay(e.delay).transition(endStyle, e.time, function() {
            y(t, n)
        })))
    }, y = function(e, t) {
            var n = 0,
                r, i, s, u;
            e.type != "writerEffect" && e.type != "fadeIn" && (e.type == "lineShow" ? (v(), r = function() {
                return $.Deferred(function(e) {
                    a.children().length > 0 ? a.children().each(function(t) {
                        var n = t == 0 ? {
                            opacity: 0,
                            scale: .1
                        } : {
                            top: parseInt($(this).css("top")) - l + "px"
                        };
                        $(this).transition(n, 500, e.resolve)
                    }) : e.reject()
                }).promise()
            }, i = function() {
                $(p[n]).appendTo(a), s = {
                    height: l + "px",
                    opacity: 1
                }, u = {
                    left: $(p[n]).data("left") + "px",
                    top: $(p[n]).data("top") + "px",
                    height: "0px",
                    opacity: 0
                }, n < c ? $(p[n]).css(u).delay(e.delay).transition(s, e.time, function() {
                    n += 1, n == h ? b(t) : i()
                }) : n >= c && n <= h && $.when(r()).done(function() {
                    a.children().first().remove(), $(p[n]).css(u).delay(e.delay).transition(s, e.time, function() {
                        n += 1, n == h ? b(t) : i()
                    })
                })
            }, i()) : e.type == "crossDisplay" ? (v(), r = function() {
                return $.Deferred(function(e) {
                    a.children().length > 0 ? a.children().each(function(t) {
                        var n = t == 0 ? {
                            opacity: 0,
                            scale: 6
                        } : {
                            top: parseInt($(this).css("top")) - l + "px"
                        };
                        $(this).transition(n, 500, e.resolve)
                    }) : e.reject()
                }).promise()
            }, i = function() {
                $(p[n]).appendTo(a), s = {
                    left: $(p[n]).data("left") + "px",
                    top: $(p[n]).data("top") + "px",
                    opacity: 1
                }, u = {
                    left: n % 2 == 0 ? "-" + $(p[n]).css("width") : window.screen.width + "px",
                    top: $(p[n]).data("top") + "px",
                    opacity: 0
                }, n < c ? $(p[n]).css(u).delay(e.delay).transition(s, e.time, function() {
                    n += 1, n == h ? b(t) : i()
                }) : n >= c && n <= h && $.when(r()).done(function() {
                    a.children().first().remove(), $(p[n]).css(u).delay(e.delay).transition(s, e.time, function() {
                        n += 1, n == h ? b(t) : i()
                    })
                })
            }, i()) : e.type == "floatBottomIn" ? (v(), r = function() {
                return $.Deferred(function(e) {
                    a.children().length > 0 ? a.children().each(function(t) {
                        var n = t == 0 ? {
                            opacity: 0,
                            top: "-=50px"
                        } : {
                            top: "-=" + l + "px"
                        };
                        $(this).transition(n, 500, e.resolve)
                    }) : e.reject()
                }).promise()
            }, i = function() {
                $(p[n]).appendTo(a), s = {
                    top: $(p[n]).data("top") + "px",
                    opacity: 1
                }, u = {
                    left: $(p[n]).data("left") + "px",
                    top: $(p[n]).data("top") + 50 + "px",
                    opacity: 0
                }, n < c ? $(p[n]).css(u).delay(e.delay).transition(s, e.time, function() {
                    n += 1, n == h ? b(t) : i()
                }) : n >= c && n <= h && $.when(r()).done(function() {
                    a.children().first().remove(), $(p[n]).css(u).delay(e.delay).transition(s, e.time, function() {
                        n += 1, n == h ? b(t) : i()
                    })
                })
            }, i()) : e.type != "typeWriter" && (h = parseInt($(o).prop("scrollHeight") / l), o.css({
                height: 0,
                visibility: "visible",
                "overflow-y": "scroll"
            }), i = function() {
                s = {
                    height: (n + 1) * l + "px"
                }, n < c ? o.delay(e.delay).transition(s, e.time, function() {
                    n += 1, n == h ? b(t) : i()
                }) : n >= c && n < h && o.delay(e.delay).animate({
                    scrollTop: (n - c + 1) * l + "px"
                }, e.time, function() {
                    n += 1, n == h ? b(t) : i()
                })
            }, i()))
        }, b = function(e) {
            var t = {
                opacity: 1
            };
            e.type == "leftInShow" ? (t = {
                left: "+=150px",
                opacity: 1
            }, s.css({
                left: "-=150px",
                opacity: 0
            }).delay(e.delay).transition(t, e.time, function() {
                typeof m == "function" && m()
            })) : e.type == "topInShow" ? (t = {
                top: "+=150px",
                opacity: 1
            }, s.css({
                top: "-=150px",
                opacity: 0
            }).delay(e.delay).transition(t, e.time, function() {
                typeof m == "function" && m()
            })) : e.type == "rightInShow" ? (t = {
                left: "-=150px",
                opacity: 1
            }, s.css({
                left: "+=150px",
                opacity: 0
            }).delay(e.delay).transition(t, e.time, function() {
                typeof m == "function" && m()
            })) : e.type == "bottomInShow" ? (t = {
                top: "-=150px",
                opacity: 1
            }, s.css({
                top: "+=150px",
                opacity: 0
            }).delay(e.delay).transition(t, e.time, function() {
                typeof m == "function" && m()
            })) : e.type == "lineShow" ? (t = {
                height: i.css("height"),
                opacity: 1
            }, s.css({
                height: 0,
                opacity: 0
            }).delay(e.delay).animate(t, e.time, function() {
                typeof m == "function" && m()
            })) : (t = {
                height: i.css("height"),
                opacity: 1
            }, s.css({
                height: 0,
                opacity: 0
            }).delay(e.delay).animate(t, e.time, function() {
                typeof m == "function" && m()
            }))
        }
}

function cardReplaceText() {
    try {
        var e = document.location.search.substr(1, 1);
        if (e != 1 && window.parent.params) {
            var t = window.parent.params.cardTo,
                n = window.parent.params.cardBody,
                r = window.parent.params.cardFrom;
            typeof n != "undefined" && n != "" && $("#cardBody").html(n), typeof t != "undefined" && t != "" && $("#cardTo").html(t), typeof r != "undefined" && r != "" && $("#cardFrom").html(r)
        }
    } catch (i) {
        console.log(i.message)
    }
}