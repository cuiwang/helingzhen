(function(k, e) {
    var d = ["#微笑#", "#喜欢#", "#晕#", "#尴尬#", "#汗#", "#惊讶#", "#郁闷#", "#疑问#", "#书呆子#", "#悲伤#", "#口罩#", "#再见#", "#冷#", "#奸诈#", "#困#", "#被打#", "#财迷#", "#大哭#", "#无聊#", "#中毒#", "#可爱#", "#呲牙#", "#大笑#", "#馋#", "#吵闹#", "#愤怒#", "#怀疑#", "#闭嘴#", "#鄙视#", "#不屑#", "#色#", "#无聊#", "#斜眼#", "#酸#", "#亲#", "#恐吓#", "#左鄙夷#", "#右鄙夷#", "#嘘#", "#委屈#", "#可怜#", "#感动#", "#酷#", "#逗趣#", "#黑#"];
    var i = function(C, B) {
        e.each(d, 
        function(D, E) {
            if (C.indexOf(E) > -1) {
                flg = true;
                C = C.replace(new RegExp(E, "g"), p(D, B))
            }
        });
        return C
    };
    var w;
    var s = 64;
    var p = function(C, B) {
        if (!w) {
            w = e(".faceicon")
        }
        return '<span class="faceicon" style="width:' + B + "px;height: " + B + 'px;display: inline-block"><img style="width:' + (2880 * B/s) + "px;height:" + B + "px;left:-" + B * C + 'px" src="' + w.find(".icon-seed").attr("src") + '" ></span>'
    };
    var A = k.WBActivity.resize = function() {
        if (!g) {
            return
        }
        var C = 20;
        var B = g.height();
        h.each(function(G, I) {
            var M = e(I);
            if (G == x) {
                M.css({
                    "margin-bottom": 0
                })
            } else {
                M.css({
                    "margin-bottom": (B - M.outerHeight() * 3) / 2
                })
            }
            var K = M.width(),
            J = M.height();
            var F = (M.height() - C * 2),
            H = F;
            M.find(".head").css({
                width: F + "px",
                height: H + "px",
                top: C + "px",
                left: C + "px"
            });
            var N = K - F - C * 3,
            D = H / 4;
            M.find(".nickname").css({
                width: N + "px",
                height: D + "px",
                top: C + "px",
                left: F + C * 2 + "px"
            });
            var O = N,
            L = J - C * 3 - D,
            E = D + C * 2;
            M.find(".msgword").css({
                width: O + "px",
                height: L + "px",
                top: E + "px",
                left: M.height() + "px"
            })
        })
    };
    function y(C, E, B, F, D) {
        C.attr("msg_id", D);
        C.find(".head").css({
            "background-image": "url(" + E + ")"
        });
        C.find(".nickname").html(B).toFillText();
        C.find(".msgword").html(F).toFillText();
        C.find(".msgword").html(i(F, parseInt(C.find(".msgword").css("font-size")) + 10))
    }
    var a = 0;
    var h;
    function o(E, C, F, D) {
        var B = e(h[a]);
        B.fadeOut(function() {
            B.css({
                visibility: "hidden",
                display: "block"
            });
            y(B, E, C, F, D);
            B.css({
                visibility: "visible"
            });
            B.addClass("msgin");
            k.setTimeout(function() {
                B.removeClass("msgin")
            },
            2000);
            a++;
            if (a > x) {
                a = 0
            }
        })
    }
    var q = 0;
    function l(E, C, F, D) {
        h = g.children();
        if (q <= x) {
            var B = e(h[q]);
            B.fadeIn();
            y(B, E, C, F, D)
        } else {
            var B = e(h[0]);
            B.fadeOut(function() {
                e(h[2]).css({
                    "margin-bottom": B.css("margin-bottom")
                });
                B.css({
                    "margin-bottom": 0,
                    visibility: "hidden",
                    display: "block"
                });
                y(B, E, C, F, D);
                B.slideUp(function() {
                    B.remove().css({
                        visibility: "visible",
                        display: "none"
                    }).appendTo(g).fadeIn()
                })
            })
        }
        q++
    }
    function t() {
        var B;
        if (r.length > 0) {
            if (z == 0) {
                B = r.pop()
            } else {
                B = r.shift()
            }
            v.push(B)
        } else {
            if (v.length > 0 && j > 3) {
                B = u()
            }
        }
        if (B) {
            if (b == "0") {
                l(B.avatar, B.nick_name, B.content, B.id)
            } else {
                o(B.avatar, B.nick_name, B.content, B.id)
            }
        }
    }
    function u() {
        var C = v.length,
        D = Math.floor(Math.random() * C),
        E = v[D],
        B = e(".MsgItem[msg_id=" + E.id + "]", ".Panel.MsgList");
        if (B.length <= 0) {
            return E
        } else {
            return u()
        }
    }
    var f = 0;
    function n(B) {
        /*e.getJSON("?ac=wall_new_msg&callback=", {
            scene_id: scene_id,
            last_id: f
        },
        function(C) {
            if (C && C.ret == 0 && e.isArray(C.data)) {
                if (C.data.length > 0) {
                    r = C.data.concat(r);
                    f = C.data[0].id
                }
            }
        }).complete(function() {
            if (B && typeof B == "function") {*/
                B.call()
            //}
        //})
    }
    var g,
    x;
    var r = [],
    v = [];
    var m = WALL_INFO.re_time * 1000,
    b = WALL_INFO.show_style,
    z = WALL_INFO.show_type;
    var j = (WALL_INFO.chistory) ? (WALL_INFO.chistory * 1) : 0;
    var c = k.WBActivity.start = function() {
        g = e(".Panel.MsgList"),
        x = g.children().length - 1,
        h = g.children();
        n(function() {
            k.WBActivity.hideLoading();
            e(".Panel.Top").css({
                top: 0
            });
            e(".Panel.Bottom").css({
                bottom: 0
            });
            e(".Panel.MsgList").css({
                display: "block",
                opacity: 1
            });
            A();
            h.hide();
           /* t();
            t();
            t();
            k.setInterval(function() {
                t()
            },
            m);
            k.setInterval(function() {
                n()
            },
            6000)*/
        })
    }
})(window, jQuery);