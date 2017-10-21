function configWxshare(n, t, e, i, o) {
    window.wx && (wx.onMenuShareTimeline({
        title: t,
        link: e,
        imgUrl: i,
        success: function() {
            _hmt.push(["_trackEvent", "share", "Timeline", "分享到朋友圈"]),
            o && o()
        },
        cancel: function() {}
    }), wx.onMenuShareAppMessage({
        title: n,
        desc: t,
        link: e,
        imgUrl: i,
        type: "",
        dataUrl: "",
        success: function() {
            _hmt.push(["_trackEvent", "share", "AppMessage", "分享给好友"]),
            o && o()
        },
        cancel: function() {}
    }))
}

$(function() {
    function n() {}
    function t() {
        _hmt.push(["_trackEvent", "page", "page2", "进入第1页"])
    }
    function e() {
        $(".p1-selectbtn-boy").click(function() {
            _hmt.push(["_trackEvent", "page", "page1", "选择了和女神对话"]),
            $(".p1").hide(),
            $(".p2").show(),
            $(".p2-nanshen").hide(),
            $(".p2-nvshen").show(),
            i()
        }),
        $(".p1-selectbtn-girl").click(function() {
            _hmt.push(["_trackEvent", "page", "page1", "选择了和男神对话"]),
            $(".p1").hide(),
            $(".p2").show(),
            $(".p2-nvshen").hide(),
            $(".p2-nanshen").show(),
            o()
        })
    }
    function i() {
        d(A, "nv"),
        f(),
        $(".p2-nvshen .p2-content-left").hide().fadeIn(1e3,
        function() {
            $(".p2-nvshen .p2-bottom").hide().delay(2500).show(),
            $(".p2-text-inner").css("padding-bottom", $(".p2-bottom").height() + 100)
        })
    }
    function o() {
        $(".p2-nanshen .p2-bottom").hide(),
        d(A, "nan"),
        g(),
        $(".p2-nanshen .p2-content-left").hide().fadeIn(1e3,
        function() {
            $(".p2-nanshen .p2-bottom").hide().delay(2500).show(),
            $(".p2-text-inner").css("padding-bottom", $(".p2-nanshen .p2-bottom").height() + 100)
        })
    }
    function a() {
        _hmt.push(["_trackEvent", "page", "page2", "进入第2页"])
    }
    function p() {
        $(".p2-nvshen .p2-bottom-center").on("click", ".p2-ans",
        function() {
            s($(this), "nv"),
            _hmt.push(["_trackEvent", "page", "page2", "点击了女神的第" + A + "题,第" + S + "个选项"])
        }),
        $(".p2-nvshen .p2-bottom-send").on("click",
        function() {
            return _hmt.push(["_trackEvent", "page", "page2", "向女神对话里插入了第" + A + "题,第" + S + "个选项"]),
            F ? (F = 0, c(), $(".p2-nvshen .p2-input").html(""), void 0) : !1
        }),
        $(".p2-nanshen .p2-bottom-center").on("click", ".p2-ans",
        function() {
            s($(this), "nan"),
            _hmt.push(["_trackEvent", "page", "page2", "点击了男神的第" + A + "题,第" + S + "个选项"])
        }),
        $(".p2-nanshen .p2-bottom-send").on("click",
        function() {
            return _hmt.push(["_trackEvent", "page", "page2", "向男神对话里插入了第" + A + "题,第" + S + "个选项"]),
            F ? (F = 0, r(), $(".p2-nanshen .p2-input").html(""), void 0) : !1
        })
    }
    function s(n, t) {
        S = n.index() + 1,
        $(".p2-input").html("").append($('<div class="p2-input-img ' + t + "_p2_" + A + "_input" + S + '"></div>'));
        var e = .69 * C,
        i = e / 414;
        $(".p2-input").find(".p2-input-img").css({
            "-webkit-transform": "scale(" + i + "," + i + ")",
            "-webkit-transform-origin": "0 0"
        })
    }
    function c() {
        $(".p2-text-inner").append(l("photo_boy.png?v=4", "nv"));
        var n = .75 * C,
        t = n / 414;
        if ($(".p2-text-inner").find(".p2-answord").css({
            "-webkit-transform": "scale(" + t + "," + t + ")",
            "-webkit-transform-origin": "0 0"
        }), $(".p2-wrapper").scrollTop(1e4), A++, 8 > A) if (5 == A) $(".p2-bottom").hide(),
        $(".p2-text-inner").css("padding-bottom", 50),
        $(".p2-oneday-wrapper").delay(1e3).fadeIn(1e3,
        function() {
            $(".p2-oneday-wrapper").delay(2e3).fadeOut(1e3,
            function() {
                $(".p2-bottom").delay(1e3).show(1,
                function() {
                    $(".p2-nvshen .p2-text-inner").css("padding-bottom", $(".p2-nvshen .p2-bottom").height() + 100),
                    d(A, "nv"),
                    $(".p2-nvshen .p2-wrapper").scrollTop(1e4)
                })
            })
        }),
        F = 1;
        else {
            d(A, "nv");
            var e = setTimeout(function() {
                $(".p2-text-inner").append(u("nvshen.png?v=4", "nv"));
                var n = .72 * C,
                t = n / 414;
                $(".p2-text-inner").find(".p2-ques").css({
                    "-webkit-transform": "scale(" + t + "," + t + ")",
                    "-webkit-transform-origin": "0 0"
                }),
                $(".p2-wrapper").scrollTop(1e4),
                F = 1
            },
            1e3)
        } else A = 1,
        d(A, "nv"),
        $(".p2-bottom-center").off("click", ".p2-ans"),
        $(".p2-bottom-send").off("click"),
        $(".p2-bottom").hide(),
        clearInterval(e),
        setTimeout(function() {
            $(".p2").hide(),
            $(".p8").show(),
            $(".p8-alert").delay(1e3).fadeOut(),
            k()
        },
        2e3)
    }
    function r() {
        $(".p2-text-inner").append(l("p2_photo_girl.png?v=4", "nan"));
        var n = .7 * C,
        t = n / 414;
        if ($(".p2-text-inner").find(".p2-answord").css({
            "-webkit-transform": "scale(" + t + "," + t + ")",
            "-webkit-transform-origin": "0 0"
        }), $(".p2-wrapper").scrollTop(1e4), A++, 8 > A) if (4 == A) $(".p2-bottom").hide(),
        $(".p2-text-inner").css("padding-bottom", 50),
        $(".p2-oneday-wrapper").delay(1e3).fadeIn(1e3,
        function() {
            $(".p2-oneday-wrapper").delay(2e3).fadeOut(1e3,
            function() {
                $(".p2-bottom").delay(1e3).show(1,
                function() {
                    $(".p2-nanshen .p2-text-inner").css("padding-bottom", $(".p2-nanshen .p2-bottom").height() + 100),
                    d(A, "nan"),
                    $(".p2-wrapper").scrollTop(1e4)
                })
            })
        }),
        F = 1;
        else if (d(A, "nan"), 3 == A || 5 == A || 7 == A || 6 == A) var e = setTimeout(function() {
            $(".p2-text-inner").append(h("nanshen.png?v=4", "nan"));
            var n = .7 * C,
            t = n / 414;
            $(".p2-text-inner").find(".p2-ques").css({
                "-webkit-transform": "scale(" + t + "," + t + ")",
                "-webkit-transform-origin": "0 0"
            }),
            $(".p2-wrapper").scrollTop(1e4)
        },
        1e3),
        e = setTimeout(function() {
            $(".p2-text-inner").append(u("nanshen.png?v=4", "nan"));
            var n = .7 * C,
            t = n / 414;
            $(".p2-text-inner").find(".p2-ques").css({
                "-webkit-transform": "scale(" + t + "," + t + ")",
                "-webkit-transform-origin": "0 0"
            }),
            $(".p2-wrapper").scrollTop(1e4),
            F = 1
        },
        3e3);
        else var e = setTimeout(function() {
            $(".p2-text-inner").append(u("nanshen.png?v=4", "nan"));
            var n = .7 * C,
            t = n / 414;
            $(".p2-text-inner").find(".p2-ques").css({
                "-webkit-transform": "scale(" + t + "," + t + ")",
                "-webkit-transform-origin": "0 0"
            }),
            $(".p2-wrapper").scrollTop(1e4),
            F = 1
        },
        1e3);
        else A = 1,
        d(A, "nan"),
        $(".p2-bottom-center").off("click", ".p2-ans"),
        $(".p2-bottom-send").off("click"),
        $(".p2-bottom").hide(),
        clearInterval(e),
        setTimeout(function() {
            $(".p2").hide(),
            $("body").css("background", "#43DCFF"),
            b(),
            $(".p7").show(),
            $(".p7-alert").delay(1e3).fadeOut()
        },
        2e3)
    }
    function u(n, t) {
        var e = $('<div class="p2-content-left"><img class="p2-nanshen-photo" src="http://7xnjal.com2.z0.glb.qiniucdn.com/img/' + n + '"><div class="ans-content"><div class="p2-ques ' + t + "_p2_" + A + '_ques"></div></div></div>');
        return e
    }
    function h(n, t) {
        var e = $('<div class="p2-content-left"><img class="p2-nanshen-photo" src="http://7xnjal.com2.z0.glb.qiniucdn.com/img/' + n + '"><div class="ans-content"><div class="p2-ques ' + t + "_p2_" + (A - 1) + "_ques" + S + '"></div></div></div>');
        return e
    }
    function l(n, t) {
        var e = $('<div class="p2-content-right"><div class="ans-content"><div class="p2-answord ' + t + "_p2_" + A + "_dialog" + S + '"></div></div><img class="p2-myphoto" src="http://7xnjal.com2.z0.glb.qiniucdn.com/img/' + n + '"></div>');
        return e
    }
    function d(n, t) {
        for (var e = "",
        i = 1; 4 > i; i++) e += '<div class="p2-ans ' + t + "_p2_" + n + "_ans" + i + '"></div>';
        $(".p2-bottom-center").html(e),
        m()
    }
    function m() {
        var n = .9 * C,
        t = n / 414;
        $(".p2-bottom-center").find(".p2-ans").css({
            "-webkit-transform": "scale(" + t + "," + t + ")",
            "-webkit-transform-origin": "0 0"
        })
    }
    function f() {
        var n = $(".p2-nvshen .p2-title").height(),
        t = P - n;
        $(".p2-nvshen .p2-wrapper").height(t)
    }
    function g() {
        var n = $(".p2-nanshen .p2-title").height(),
        t = ($(".p2-nanshen .p2-bottom").height(), P - n);
        $(".p2-nanshen .p2-wrapper").height(t)
    }
    function v(n, t) {
        return parseInt(Math.round(Math.random() * (t - n) + n))
    }
    function b() {
        var n = v(1, 16);
        $(".p7-pic" + n).css({
            transform: "scale(0.1)",
            "transform-origin": "50% 50%"
        }).delay(1500).fadeIn(200,
        function() {
            $(".p7-pic" + n).css({
                transform: "scale(1)",
                "transform-origin": "50% 50%",
                "transition-duration": "1s"
            })
        });
        var t = $(".p7-pic" + n).attr("src");
        w("男", L[n], t)
    }
    function w(n, t, e) {
        var i = "测测你能约到的" + n + "神",
        o = "我才不会告诉你,我是这样约到我的" + n + "神" + t + "的呢 ≥﹏≤",
        a = SHARE_ROOT,
        p = e;
        configWxshare(i, o, a, p, null)
    }
    function k() {
        var n = v(1, 15);
        $(".p8-pic" + n).css({
            transform: "scale(0.1)",
            "transform-origin": "50% 50%"
        }).delay(1500).fadeIn(200,
        function() {
            $(".p8-pic" + n).css({
                transform: "scale(1)",
                "transform-origin": "50% 50%",
                "transition-duration": "1s"
            })
        });
        var t = $(".p8-pic" + n).attr("src");
        w("女", R[n], t)
    }
    function _() {}
    function x() {
        _hmt.push(["_trackEvent", "page", "page7", "进入第7页"])
    }
    function y() {
        $(".p7-btn1").click(function() {
            $(".page-share").show(),
            _hmt.push(["_trackEvent", "page", "page7", "分享链接"])
        }),
        $(".page-share").click(function() {
            $(this).hide(),
            _hmt.push(["_trackEvent", "page", "page7", "点击浮层"])
        }),
        $(".p7-alert").click(function(n) {
            $(this).hide()
        })
    }
    function q() {}
    function j() {
        _hmt.push(["_trackEvent", "page", "page8", "进入第8页"])
    }
    function z() {
        $(".p8-btn1").click(function() {
            _hmt.push(["_trackEvent", "page", "page8", "分享链接"]),
            $(".page-share").show()
        }),
        $(".page-share").click(function() {
            _hmt.push(["_trackEvent", "page", "page8", "点击浮层"]),
            $(this).hide()
        }),
        $(".once-time").click(function() {
            T($(this))
        }),
        $(".p8-alert").click(function(n) {
            $(this).hide()
        })
    }
    function T(n) {
        n.parent().parent().hide(),
        $(".result").hide(),
        $(".p1").show(),
        A = 1,
        F = 1,
        p(),
        $(".p7-alert").show(),
        $(".p8-alert").show(),
        $(".p2-input").html(""),
        $(".p2-nanshen .p2-text-inner").html('<img src="http://7xnjal.com2.z0.glb.qiniucdn.com/img/p2_time.png?v=4" class="p2-time"><div class="p2-content-left"><img class="p2-nanshen-photo" src="http://7xnjal.com2.z0.glb.qiniucdn.com/img/nanshen.png?v=4"><img class="p2-ques1" src="http://7xnjal.com2.z0.glb.qiniucdn.com/img/p2_1_ques.png?v=4"></div>'),
        $(".p2-nvshen .p2-text-inner").html('<img src="http://7xnjal.com2.z0.glb.qiniucdn.com/img/p2_time.png?v=4" class="p2-time"><div class="p2-content-left"><img class="p2-nanshen-photo" src="http://7xnjal.com2.z0.glb.qiniucdn.com/img/nvshen.png?v=4"><img class="p2-ques1" src="http://7xnjal.com2.z0.glb.qiniucdn.com/img/p2_1_ques.png?v=4"></div>')
    }
    function E() {
        n(),
        _(),
        q()
    }
    function M() {
        e(),
        p(),
        y(),
        z()
    }
    function O(n) {
        var t = new RegExp("(^|&)" + n + "=([^&]*)(&|$)"),
        e = window.location.search.substr(1).match(t);
        return null != e ? unescape(e[2]) : null
    }
    function I(n) {
        switch ($(".page").hide(), $(".p" + n).show(), n) {
        case 0:
            break;
        case 1:
            t();
            break;
        case 2:
            a();
            break;
        case 7:
            x();
            break;
        case 8:
            j()
        }
    }
    var S, C = $(window).width(),
    P = $(window).height(),
    A = 1,
    F = 1,
    L = ["", "春哥", "曾哥", "马云", "叶良辰", "庞麦郎", "高晓松", "黄渤", "毛新宇", "林永健", "大鹏", "杨洋", "鹿晗", "李易峰", "李彦宏", "张翰", "timcook"],
    R = ["", "如花", "凤姐", "芙蓉姐姐", "包租婆", "hold住姐", "六界第一美杀阡陌", "肖骁", "金星", "林志玲", "张翰", "Tim cook", "柳岩", "苍老师", "高圆圆", "angelababy"];
    app.showPage = I,
    app.initPage = E,
    app.eventCenter = M,
    _hmt.push(["_trackEvent", "来源", "来自" + O("from"), "来自" + O("from")])
}),
function() {
    function n() {
        for (var n = $(".loading img"), e = [], i = 0; i < n.length; ++i) e.push(n[i].src);
        e.push("http://7xnjal.com2.z0.glb.qiniucdn.com/font/miaowu.ttf");
        var o = new createjs.LoadQueue;
        o.loadManifest(e),
        o.on("complete",
        function(n) {
            bgMusic.init(),
            t()
        })
    }
    function t() {
        for (var n = $("img"), t = [], e = 0; e < n.length; ++e) t.push(n[e].src);
        var i = new createjs.LoadQueue;
        i.installPlugin(createjs.Sound),
        t.push(audio),
        t.push("http://7xnjal.com2.z0.glb.qiniucdn.com/img/weixin.png?v=10"),
        t.push("http://7xnjal.com2.z0.glb.qiniucdn.com/img/select_bg.png?v=10"),
        t.push("http://7xnjal.com2.z0.glb.qiniucdn.com/img/input_bg.png?v=10"),
        t.push("http://7xnjal.com2.z0.glb.qiniucdn.com/img/p7_bg.jpg?v=10"),
        t.push("http://7xnjal.com2.z0.glb.qiniucdn.com/img/nan_ans.png?v=10"),
        t.push("http://7xnjal.com2.z0.glb.qiniucdn.com/img/nan_ques.png?v=10"),
        i.loadManifest(t),
        i.installPlugin(createjs.Sound),
        i.loadManifest([]),
        i.on("fileload",
        function(n) {}),
        i.on("error",
        function(n) {}),
        i.on("progress",
        function(n) {
            var t = 100 * n.progress;
            $(".loading-percent").html(t.toFixed(0) + "%")
        }),
        i.on("complete",
        function(n) {
            app.initPage(),
            app.eventCenter(),
            FastClick.attach(document.body),
            app.showPage(1)
        })
    }
    $(window).width(),
    $(window).height();
    n()
} ();
var bgMusic = function() {
    function n() {
        a.play(),
        p[0].src = o.root + o.iOn,
        p.removeClass("bg-music-off"),
        p.addClass("bg-music-on")
    }
    function t() {
        a.pause(),
        p[0].src = o.root + o.iOff,
        p.removeClass("bg-music-on"),
        p.addClass("bg-music-off")
    }
    function e() {
        p.appendTo($("body")),
        a.src = o.root + o.music,
        p.on("click",
        function() {
            a.paused ? n() : t()
        }),
        $(document).one("touchstart",
        function() {
            n()
        })
    }
    function i(t) {
        o = $.extend(o, t),
        e(),
        n()
    }
    var o = {
        iOn: "../addons/jing_ns/template/style/img/musicOn.png",
        iOff: "../addons/jing_ns/template/style/img/musicOff.png",
        music: audio,
        root: ""
    },
    a = new Audio;
    a.volume = 1,
    a.loop = !0,
    a.autoPlay = !0;
    var p = $('<img id="bg-music">');
    return p.css({
        position: "fixed",
        right: "3%",
        top: "3%",
        width: "10%",
        "z-index": "9999"
    }),
    {
        init: i,
        audio: a,
        play: n,
        pause: t
    }
} (); !
function() {
    function n() {
        if (!o) {
            var n = '<div class="landscape-tip" style="background: black; color: white; font-size: 2em; text-align: center; position: fixed; width: 100%; height:100%;right:0; top: 0; left: 0; bottom: 0; z-index: 10000; overflow: hidden;"><div class="content" style="height: 45%"></div><div class="content style="text-align: center">请竖屏浏览</div></div>';
            o = $(n),
            $(document.body).append(o),
            $(".landscape-tip").on("touchstart",
            function() {
                return ! 1
            })
        }
        o.show()
    }
    function t() {
        o && o.hide()
    }
    function e() {
        var e = window.orientation;
        switch (e) {
        case 90:
        case - 90 : e = "landscape",
            n();
            break;
        default:
            e = "portrait",
            t()
        }
    }
    function i() {
        window.addEventListener("orientationchange", e, !1),
        e()
    }
    var o;
    i()
} ();