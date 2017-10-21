function CreateAudio(e) {
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

function initContentPosition(e, t, n) {
    var r = parseInt(e.css("font-size")),
        i = parseInt(t.css("line-height")),
        s = e.prop("offsetTop") + r,
        o = n.prop("offsetTop");
    t.css("top", s + r * 1 + "px");
    var u = o - r * 2 - s;
    t.css("max-height", parseInt(u / i) * i), n.css("left", t.prop("offsetLeft") + t.width() - n.width()), n.css("top", t.prop("offsetTop") + t.height() + r)
}


function waitReady() {
    typeof window.parent.stopRoll != "undefined" && window.parent.stopRoll()
}

function createimage(e, t, n, r, i) {
    if (r == null || r == "") r = "fade, drop";
    var s = document.createElement("div"),
        o = document.createElement("img");
    o.src = e, o.className = i, s.style.top = "-10%", s.style.left = randomInteger(t, n) + "%";
    var u = Math.random() < .5 ? "clockwiseSpin" : "counterclockwiseSpinAndFlip";
    s.style.webkitAnimationName = r, s.style.animationName = r, s.style.msAnimationName = r, o.style.webkitAnimationName = u, o.style.animationName = u, o.style.msAnimationName = u;
    var a = randomFloat(5, 11) + "s",
        f = randomFloat(4, 8) + "s";
    s.style.webkitAnimationDuration = a + ", " + a, s.style.animationDuration = a + ", " + a, s.style.msAnimationDuration = a + ", " + a;
    var l = randomFloat(0, 5) + "s";
    return s.style.webkitAnimationDelay = l + ", " + l, o.style.webkitAnimationDuration = f, o.style.animationDuration = f, o.style.msAnimationDuration = f, s.appendChild(o), s
}

function randomInteger(e, t) {
    return e + Math.floor(Math.random() * (t - e))
}

function randomFloat(e, t) {
    return e + Math.random() * (t - e)
}

function createImage(e, t) {
    var n = document.createElement("div"),
        r = document.createElement("img");
    r.src = e, r.className = t, n.style.top = "-10%", n.style.left = randomInteger(0, 100) + "%";
    var i = Math.random() < .5 ? "clockwiseSpin" : "counterclockwiseSpinAndFlip";
    n.style.webkitAnimationName = "fade, drop", n.style.animationName = "fade, drop", n.style.msAnimationName = "fade, drop", r.style.webkitAnimationName = i, r.style.animationName = i, r.style.msAnimationName = i;
    var s = randomFloat(5, 11) + "s",
        o = randomFloat(4, 8) + "s";
    n.style.webkitAnimationDuration = s + ", " + s, n.style.animationDuration = s + ", " + s, n.style.msAnimationDuration = s + ", " + s;
    var u = randomFloat(0, 5) + "s";
    return n.style.webkitAnimationDelay = u + ", " + u, n.style.animationDelay = u + ", " + u, n.style.msAnimationDelay = u + ", " + u, r.style.webkitAnimationDuration = o, r.style.animationDuration = o, r.style.msAnimationDuration = o, n.appendChild(r), n
}

function ReplaceText() {
    var e = document.location.search.substr(1, 1);
    if (e != 1 && window.parent.params) {
        var t = window.parent.params.cardTo,
            n = window.parent.params.cardBody,
            r = window.parent.params.cardFrom;
        typeof n != "undefined" && n != "" && $("#cardBody").html(n), typeof t != "undefined" && (t == null ? $("#cardTo").html(" ") : $("#cardTo").html(t)), typeof r != "undefined" && (r == null ? $("#cardFrom").html(" ") : $("#cardFrom").html(r))
    }
}

function createStaticImage(e, t, n) {
    var r = document.createElement("div");
    $(r).addClass(n).css("position", "absolute").css(e).html("<img src='" + t + "'>"), $("#stage").append(r)
}

function randomFloatPosition(e, t) {
    return e > t ? 0 : Math.round(Math.random() * (t - e) + e)
}
$(function(e) {
    e.fn.typewriter = function(t) {
        return this.each(function() {
            var n = e(this),
                r = n.html(),
                i = 0;
            n.html(""), n.css("visibility", "visible");
            var s = setInterval(function() {
                var e = r.substr(i, 1);
                e == "<" ? i = r.indexOf(">", i) + 1 : i++, i == r.length ? n.html(r.substring(0, i)) : n.html(r.substring(0, i) + (i & 1 ? "_" : "")), n.scrollTop(n.prop("scrollHeight")), i >= r.length && clearInterval(s)
            }, t)
        }), this
    }
}), $(function() {
    $.fn.ShowSkip = function() {
        $(this).delay(2e3).show(function() {
            var e = document.location.search.substr(1, 1);
            if (e == 1) document.location.href = "../../../../ad/promote2.html";
            else {
                var t = window.parent,
                    n = t.params,
                    r = t.isReceive,
                    i = n.source,
                    s = n.skipFlag,
                    o = n.orderId;
                n && (r ? window.parent.params.company && window.parent.params.company.id != 0 ? window.parent.document.location.href = window.parent.params.company.redirect ? window.parent.params.company.redirect : "#" : i == 1 || i == 2 || i == 3 ? top.location.href = "../../mobile/acknowledge.html" : i == 4 && s == 1 && (window.parent.document.location.href = "../../mobile/adShow.html?orderId=" + o) : i == 4 && s == 1 && (window.parent.document.location.href = "../../mobile/adShow.html?orderId=" + o))
            }
        })
    }
}), $.fn.extend({
    textLineShow: function(e, t) {
        var n = parseInt($(this).css("line-height")),
            r = parseInt($(this).css("font-size"));
        t = t == undefined ? 300 : t;
        var i = $(this),
            s = parseInt($(this).css("max-height")),
            o = $(this).prop("scrollHeight"),
            u = 0;
        $(this).css("height", 0), $(this).css("visibility", "visible"), $(this).css("overflow", "hidden");
        var a = setInterval(function() {
            u += n, u <= s ? i.animate({
                height: u
            }, t) : u > s && u <= o ? i.animate({
                scrollTop: u - s
            }, t) : clearInterval(a)
        }, e);
        return this
    }
}), $.fn.extend({
    writeText: function(e, t) {
        var n = $(this).html(),
            r = $(this).height();
        t = t == undefined ? "" : t, $(this).html(""), $(this).css("visibility", "visible");
        var i = 0,
            s = 0,
            o = $(this),
            u = setInterval(function() {
                var e = n.substring(i, 1);
                e == "<" ? i = n.indexOf(">", i) + 1 : i++, o.html(n.substring(0, i) + (i & 1 ? t : "")), o.scrollTop(o.prop("scrollHeight")), i >= n.length && clearInterval(u)
            }, e);
        return this
    }
})