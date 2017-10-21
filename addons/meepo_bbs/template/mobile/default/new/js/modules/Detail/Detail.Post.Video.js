!function(a, b) {
    var c = a.Detail;
    c.Post.Video = b(c)
}(this, function(a) {
    function b(b, c) {
        var d = new Date
          , e = +d
          , f = {
            wording: "",
            status: b > e ? 0 : e > c ? 2 : 1
        };
        switch (a.postData.liveStatus = f.status,
        f.status) {
        case 0:
            var g = new Date(b);
            864e5 > b - e && d.getDate() === g.getDate() ? f.wording = (g.getHours() < 10 ? "0" : "") + g.getHours() + ":" + (g.getMinutes() < 10 ? "0" : "") + g.getMinutes() : f.wording = a.formatDate(b);
            break;
        case 1:
            f.wording = "直播中";
            break;
        case 2:
            f.wording = "已过期"
        }
        return f
    }
    function c(b, c) {
        d = a.postType,
        $(document.body).addClass("topic-live"),
        200 === d ? (document.title = "直播",
        mqq.ui.refreshTitle()) : $(document.body).addClass("topic-video"),
        e.data = b,
        e.rock(),
        f.data = b,
        f.rock(),
        c && c(b)
    }
    var d, e, f, g = renderModel, h = a.report, i = a.bid, j = a.pid;
    return e = new g({
        comment: "post_header_model",
        renderTmpl: window.TmplInline_detail_header.top_detail_header,
        renderContainer: "#detail_top_info_header",
        renderTool: honourHelper,
        complete: function() {
            var b = $(".detail-from").width();
            $(".title-bottom-wrapper .name-wrap").css("padding-right", b),
            $(".detail-from").on("tap", function() {
                h("Clk_topentry", {
                    obj1: j
                }),
                "barindex" === a.source && "0" !== mqq.QQVersion ? mqq.ui.popBack() : Util.openUrl(a.base + "barindex.html#bid=" + i + "&scene=detail_titleNav", !0)
            }),
            h("exp_nav_top")
        }
    }),
    f = new g({
        comment: "post_video_model",
        renderTmpl: window.TmplInline_detail.top_live,
        renderContainer: "#detail_top_info",
        processData: function(a) {
            a.post.image2 || (a.post.image2 = a.post.image1),
            a.post.image1 = imgHandle.getThumbUrl(a.post.image1),
            a.post.image2 = imgHandle.getThumbUrl(a.post.image2),
            201 === d && (a.isVideo = !0)
        },
        events: function() {
            $("#people_joined_title").tap(a.Join.showList),
            a.bindNormalEvents(a.postUin)
        },
        complete: function(c) {
            var e = ""
              , f = 1;
            if (c.post.channel ? (e = "" + c.post.channel,
            f = 1) : (f = 2,
            e = "" + c.post.vid),
            MediaPlayer.initVPlayer(e, c.post.image1, f),
            200 === d) {
                if (c.post.start && c.post.end) {
                    var g = b(c.post.start, c.post.end);
                    $("#live_status_button").addClass(["live_pre", "living", "live_expire"][g.status]),
                    $("#live_status_wording").html(g.wording),
                    $("#live_status_button").show()
                }
                a.Join.showListInPost(c),
                a.commentOrder = 1,
                $("#btnShowInturn").html("顺序查看"),
                $(".show-inturn").removeClass("reverse")
            }
        }
    }),
    {
        render: c
    }
});