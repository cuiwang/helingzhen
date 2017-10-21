!function(a, b) {
    var c = a.Detail;
    c.Post.Postarray = b(c)
}(this, function(a) {
    function b(a, b) {
        d.data = a,
        d.rock(),
        c.data = a,
        c.rock(),
        b && b(a)
    }
    var c, d, e = renderModel, f = a.report, g = 0;
    return d = new e({
        comment: "post_header_model",
        renderTmpl: window.TmplInline_detail_header.top_detail_header,
        renderContainer: "#detail_top_info_header",
        renderTool: honourHelper,
        complete: function() {
            var a = $(".detail-from").width();
            $(".title-bottom-wrapper .name-wrap").css("padding-right", a),
            f("exp_nav_top")
        }
    }),
    c = new e({
        comment: "post_array_model",
        renderTmpl: window.TmplInline_detail.top_postarray,
        renderContainer: "#detail_top_info",
        processData: function(a) {
            if (1 === ~~a.post.is_recruit) {
                var b = a.big_admin_num
                  , c = a.sml_admin_num;
                3 > b && (g = 1),
                7 > c && (g = 2),
                3 > b && 7 > c && (g = 3),
                0 === b && (g = 11),
                "undefined" == typeof b && (g = 3)
            }
        },
        events: function() {
            a.bindNormalEvents(a.postUin, g)
        },
        complete: function(a) {
            if (MediaPlayer.initVPlayerMuti("#detail_top_info"),
            $.os.ios && a.post) {
                for (var b = a.post.post_array || [], c = 0, d = b.length - 1; d >= 0; d--)
                    "video" === b[d].type && c++;
                c >= 2 && $(".post-video").addClass("hack-video")
            }
        }
    }),
    {
        render: b
    }
});