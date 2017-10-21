!function(a, b) {
    var c = a.Detail;
    c.Post.Normal = b(c, window.TmplInline_detail_header, window.TmplInline_detail)
}(this, function(a, b, c) {
    function d(a) {
        var b, c = document.body.clientWidth - 20, d = .75 * c;
        if (a.public_account_post && a.public_account_post.content)
            try {
                b = a.public_account_post,
                b.content = b.content.replace(/<iframe (.*?)height\="(.*?)"(.*?)<\/iframe>/g, '<iframe $1height="' + d + '"$3</iframe>').replace(/<iframe (.*?)width\="(.*?)"(.*?)<\/iframe>/g, '<iframe $1width="' + c + '"$3</iframe>').replace(/<iframe (.*?) src\="http:\/\/v.qq.com\/iframe\/player\.html(.*?)width\=([\d\.]*?)(["&])(.*?)<\/iframe>/g, '<iframe $1 src="http://v.qq.com/iframe/player.html$2width=' + c + "$4$5</iframe>").replace(/<iframe (.*?) src\="http:\/\/v.qq.com\/iframe\/player\.html(.*?)height\=([\d\.]*?)(["&])(.*?)<\/iframe>/g, '<iframe $1 src="http://v.qq.com/iframe/player.html$2height=' + d + "$4$5</iframe>").replace(/<iframe (.*?)style\="(.*?)height(\s*?):(.*?)px(.*?)"(.*?)<\/iframe>/g, '<iframe $1style="$2height:' + d + 'px$5"$6</iframe>').replace(/\?tp\=webp/g, "").replace(/(<img[^>]*>)/g, '<div class="img-box">$1</div>')
            } catch (e) {}
        loadjs.loadCss(window.loadCssConfig.wechatCss)
    }
    function e(b) {
        return l ? ($(".video-preview .preview-box").html(l),
        $(".video-preview").show(),
        $("#detail_top_info .post-video").hide(),
        void (a.isUploading = !0)) : 302 === k ? void MediaPlayer.initVPlayerMuti("#detail_top_info") : void (b.post.video_list && b.post.video_list.length && (b.vstat && 2 === b.vstat ? (MediaPlayer.initVPlayerMuti("#detail_top_info"),
        i(b)) : (a.isUploading = !0,
        h(b))))
    }
    function f() {
        var b = ActionButton.getUploadVideo(p, q);
        if (console.log("upload info", b),
        b) {
            a.isUploading = !0;
            var c = b.post.image1
              , d = '<img src="' + c + '" />'
              , e = $(".video-preview .preview-box");
            g(c),
            e.length ? (e.html(d),
            $(".video-preview").show(),
            $("#detail_top_info .post-video").hide()) : l = d,
            Refresh.freeze()
        } else
            console.log("没有正在上传的视频")
    }
    function g(a) {
        a && mqq.data.writeH5Data({
            callid: q,
            path: "/buluo/video",
            key: "" + p + q,
            data: a
        })
    }
    function h(a) {
        mqq.data.readH5Data({
            callid: q,
            path: "/buluo/video",
            key: "" + p + q
        }, function(b) {
            var c = "http://shp.qpic.cn/qqvideo/0/" + a.post.video_list[0].vid + "/400";
            0 === b.ret && b.response && b.response.callid === q && (c = b.response.data),
            $(".video-preview .preview-box").html('<img src="' + c + '" >'),
            $(".video-preview").show().siblings(".post-video").hide()
        })
    }
    function i(a) {
        a.isposter && mqq.data.deleteH5Data({
            callid: q,
            path: "/buluo/video",
            key: "" + p + q
        }, function() {})
    }
    function j(b, c) {
        k = a.postType,
        $(document.body).addClass("topic-detail"),
        m.data = b,
        m.rock(),
        n.data = b,
        n.rock(),
        c && c(b)
    }
    var k, l, m, n, o = renderModel, p = a.bid, q = a.pid, r = a.report, s = 0, t = 0;
    return m = new o({
        comment: "post_header_model",
        renderTmpl: b.top_detail_header,
        renderContainer: "#detail_top_info_header",
        renderTool: honourHelper,
        complete: function() {
            var a = $(".detail-from").width();
            $(".title-bottom-wrapper .name-wrap").css("padding-right", a),
            r("exp_nav_top")
        }
    }),
    n = new o({
        comment: "post_model",
        renderTmpl: c.top_detail,
        renderContainer: "#detail_top_info",
        renderTool: honourHelper,
        processData: function(a) {
            if (a.post.pic_list = a.post.pic_list || [],
            s = a.uin,
            301 === k ? d(a) : 300 === k && 1 === a.post.rss && (a.posts && a.posts[1] ? a.contentList = window.getRSSContent(a.posts[1].content.trim()) : (a.contentList = [],
            console.error('缺少"posts"字段'))),
            1 === ~~a.post.is_recruit) {
                var b = a.big_admin_num
                  , c = a.sml_admin_num;
                3 > b && (t = 1),
                7 > c && (t = 2),
                3 > b && 7 > c && (t = 3),
                0 === b && (t = 11),
                "undefined" == typeof b && (t = 3)
            }
        },
        events: function() {},
        complete: function(b) {
            301 === k && $(".img-box").css("background", "none"),
            a.isRenderFromLocal || (e(b),
            a.bindNormalEvents(s, t),
            b.gname && a.isQQ && r("exp_grpsign"))
        }
    }),
    {
        render: j,
        triggerUploading: f
    }
});