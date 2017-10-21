!function(a, b) {
    var c = a.Detail;
    c.Post.Music = b(c)
}(this, function(a) {
    function b(a, b) {
        c.data = a,
        c.rock(),
        b && b(a)
    }
    var c, d = {}, e = navigator.userAgent, f = e.match(/QQMUSIC\/(\d[\.\d]*)/i), g = a.isWX, h = renderModel;
    return f && ($.browser.music = !0,
    f[1] && ($.browser.version = parseFloat(f[1].replace("0", ".")))),
    c = new h({
        comment: "post_music_model",
        renderTmpl: window.TmplInline_detail.top_music,
        renderContainer: "#detail_top_info",
        processData: function(a) {
            var b = a.post.qqmusic_list[0];
            a.music = b,
            a.isWeiXin = g
        },
        events: function() {
            $("#js_download_music").on("tap", function() {
                var a = $(this).data("id");
                return d.openMusic({
                    mid: 23,
                    k2: a
                }),
                !1
            })
        },
        complete: function() {
            $("#detail_top_info").addClass("qqmusic-wrap"),
            $("#detail_top_info_header").hide(),
            $("#js_detail_scroll_top").addClass("qqmusic-post")
        }
    }),
    function(a) {
        a.weixinReady = function(a) {
            window.WeixinJSBridge ? a() : document.addEventListener("WeixinJSBridgeReady", function() {
                a()
            })
        }
        ,
        a.checkInstall = function(b) {
            if (b = b || function() {}
            ,
            g)
                a.weixinReady(function() {
                    WeixinJSBridge.invoke("getInstallState", {
                        packageName: "com.tencent.qqmusic",
                        packageUrl: "qqmusic://"
                    }, function(a) {
                        var c = a.err_msg;
                        b(c.indexOf("get_install_state:yes") > -1 ? 1 : -1)
                    })
                });
            else if ("undefined" != typeof mqq)
                if (mqq.app && mqq.app.isAppInstalled) {
                    var c = "com.tencent.qqmusic";
                    $.os.ios && (c = "qqmusic"),
                    mqq.app.isAppInstalled(c, function(a) {
                        b(a ? 1 : -1)
                    })
                } else
                    b(0);
            else
                b($.browser.music ? 1 : 0)
        }
    }(d),
    function(a) {
        function b(a) {
            var b;
            if ($.os.ios)
                b = +new Date,
                location.href = a;
            else {
                var d = document.createElement("iframe");
                d.style.width = "1px",
                d.style.height = "1px",
                d.style.display = "none",
                d.src = a,
                b = +new Date,
                document.body.appendChild(d)
            }
            setTimeout(function() {
                var a = +new Date;
                1550 > a - b && c()
            }, 1500)
        }
        function c() {
            $.os.ios ? location.href = a.openMusic.downloadUrl.ios : "undefined" != typeof mqq && mqq.compare && mqq.compare("4.5") >= 0 ? mqq.app.downloadApp({
                appid: "1101079856",
                url: a.openMusic.downloadUrl.android,
                packageName: "com.tencent.qqmusic",
                actionCode: "2",
                via: "ANDROIDQQ.QQMUSIC.GENE",
                appName: "QQMUSIC"
            }, function() {}) : location.href = a.openMusic.downloadUrl.android
        }
        a.openMusic = function(d, e) {
            e && (a.openMusic.downloadUrl.android = "http://misc.wcd.qq.com/app?packageName=com.tencent.qqmusic&channelId=" + e);
            var f = $.param(d || {})
              , g = "androidqqmusic://form=webpage&" + f;
            $.os.ios && (g = "qqmusic://qq.com?form=webpage&" + f),
            a.checkInstall(function(a) {
                1 === a ? location.href = g : -1 === a ? c() : b(g)
            })
        }
        ,
        a.openMusic.downloadUrl = {
            ios: "itms-apps://itunes.apple.com/cn/app/qq-yin-le/id414603431?mt=8",
            android: "http://misc.wcd.qq.com/app?packageName=com.tencent.qqmusic&channelId=10000609"
        },
        a.download = c
    }(d),
    {
        render: b
    }
});