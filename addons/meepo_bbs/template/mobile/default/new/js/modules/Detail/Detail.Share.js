!function(a, b) {
    var c = a.Detail;
    c.Share = b(c)
}(this, function(a) {
    function b(a) {
        var b, c = $.cookie("vkey"), d = ["share_qq", "share_qzone", "share_wechat", "share_circle", "share_weibo", "share_link"];
        c && (j.sid = c),
        p("Clk_share"),
        b = j,
        a && (b = $.extend({}, b, {
            content: a
        })),
        b.succHandler = function(a) {
            p(["qq_suc", "qzone_suc", "wechat_suc", "circle_suc"][a])
        }
        ,
        Util.shareMessage(b, function(a, b) {
            6 === b ? i() : 6 > b && (k.refresh(),
            p(d[b], {
                obj1: n
            }))
        })
    }
    function c(a, b) {
        var c = "http://buluo.qq.com/mobile/detail.html?_bid=128&_wv=1027&bid=" + m + "&pid=" + n
          , d = $.str.decodeHtml(rich2plain(a.title)).replace(/<(.|\n)+?>/gi, "")
          , e = $.str.decodeHtml(rich2plain(a.post.content, a.post.urlInfo)).replace(/<(.|\n)+?>/gi, "");
        900 === a.type && (d += "——兴趣部落"),
        j = {
            shareUrl: c,
            pageUrl: c,
            imageUrl: "",
            title: d,
            content: e || d
        };
        var f = "";
        200 !== o && 201 !== o || !a.post.image1 ? a.post.pic_list && a.post.pic_list[0] && (f = a.post.pic_list[0].url ? a.post.pic_list[0].url : a.post.pic_list[0],
        j.imageInfo = {
            bid: m,
            pic: a.post.pic_list[0]
        }) : f = a.post.image1,
        f = f || a.bar_pic,
        j.imageUrl = imgHandle.getThumbUrl(f, 200),
        b && b()
    }
    function d() {
        var a = {
            mid: "callshare",
            img_url: j.imageUrl,
            link: j.shareUrl,
            desc: j.content,
            title: j.title
        };
        q.callHandler("callshare", a, function() {
            console.log("分享成功")
        })
    }
    function e() {
        var a = {
            link: j.shareUrl + "&from=wechat",
            title: j.title,
            desc: j.content,
            handleShareTimelineSuccess: function() {
                k.refresh(),
                p("share_circle", {
                    obj1: n
                })
            },
            handleShareAppMessageSuccess: function() {
                k.refresh(),
                p("share_wechat", {
                    obj1: n
                })
            }
        };
        a.imgUrl = j.imageUrl,
        j.imageInfo && j.imageInfo.url && (a.imgUrl = j.imageInfo.url),
        window.WechatShare.init(a)
    }
    function f() {
        var a = window.YybJsBridge;
        a && a.setShareInfo({
            allowShare: 1,
            iconUrl: j.imageUrl,
            jumpUrl: j.shareUrl,
            title: j.title,
            summary: j.content
        })
    }
    function g(b) {
        c(b, function() {
            return a.isYYB ? void f() : a.isWX ? void e() : void 0
        })
    }
    function h(a) {
        return q ? void d() : void b(a)
    }
    function i() {
        p("Clk_collect", {
            obj1: n,
            os: a.isIOS ? "ios" : "android"
        });
        var b = $(".js-detail-title").text()
          , c = "";
        if (100 === o || 101 === o)
            $("#actDetailImage")[0] ? c = $("#actDetailImage")[0].src : $(".tvp_poster_img")[0] && (c = $(".tvp_poster_img")[0].src);
        else if (200 === o || 201 === o)
            c = $(".tvp_poster_img")[0] && $(".tvp_poster_img")[0].src;
        else {
            var d = $("#detail_top_info").find(".img-box img");
            d.length > 0 && d[0].src ? c = d[0].src : $(".tvp_poster_img")[0] && (c = $(".tvp_poster_img")[0].src)
        }
        c = c || "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/recommend-blue-icon.png",
        DB.cgiHttp({
            url: "/cgi-bin/bar/extra/add_mqq_fave",
            type: "POST",
            ssoCmd: "add_mqq_fave",
            param: {
                bid: m,
                pic: c,
                pid: n,
                sub_title: "兴趣部落",
                title: b
            },
            succ: function() {
                Tip.show("已收藏")
            },
            err: function() {
                Tip.show("操作失败，请重试！", {
                    type: "warning"
                })
            }
        })
    }
    var j, k, l = cgiModel, m = a.bid, n = a.pid, o = a.postType, p = a.report, q = null ;
    return document.addEventListener("WebViewJavascriptBridgeReady", function(a) {
        a = a || window.event,
        q = a.bridge,
        q.init()
    }),
    k = new l({
        cgiName: "/cgi-bin/bar/extra/share_add",
        ssoCmd: "share_add",
        param: {
            bid: m,
            pid: n
        }
    }),
    window.pmdCampusShare = function() {
        PmdCampus.shareMessage({
            title: j.title,
            desc: j.content,
            share_url: j.shareUrl,
            image_url: j.imageUrl
        })
    }
    ,
    {
        init: g,
        callHandler: h
    }
});