!function(a, b) {
    var c = a.Detail;
    c.Publish = b(c)
}(this, function(a) {
    function b(b, c, d, j) {
        Publish.init({
            isReply: !0,
            bid: e,
            flag: h,
            pid: f,
            ref_cid: b,
            ctxNode: $("#js_detail_main"),
            pubulishType: "reply",
            postType: g,
            nick_name: d,
            onhidden: function() {},
            succ: function(b) {
                a.Comment.afterReply(b);
                var d = $("#to_reply")
                  , e = Number(d.html()) || 0;
                d.html(e + 1),
                i("reply_suc"),
                "commentReply" === c && i("suc_reply_own"),
                "refReply" === c && i("suc_reply_layer"),
                101 === a.postType && a.openActNewReport("comment_suc")
            },
            cancel: function() {
                window.history.go(-1)
            },
            config: {
                isReply: !0,
                from: "detail",
                extparam: {
                    ref_cid: b,
                    floor: j || 0
                }
            }
        })
    }
    function c(c, d, f, g) {
        a.showLockTip() || (localStorage.getItem("pho_alert" + e) || 10364 !== e && 10679 !== e ? b(c, d, f, g) : Util.showStatement(function() {
            b(c, d, f, g)
        }, e))
    }
    function d(b, c) {
        if (!a.showLockTip()) {
            var d = $.trim(b);
            return d.length > 200 ? (Tip.show("输入字符请在200个字以内", {
                type: "warning"
            }),
            !1) : (Publish.init({
                isReply: !0,
                bid: e,
                pid: f,
                pubulishType: "reply",
                postType: g,
                preventDefaultUI: !0,
                config: {
                    minLength: 1,
                    maxLength: 200
                }
            }),
            Publish.sendData("", b, {
                callback: function(b) {
                    a.Comment.afterReply(b.result);
                    var c = $("#to_reply")
                      , d = Number(c.html()) || 0;
                    c.html(d + 1),
                    i("reply_suc"),
                    i("suc_reply_own"),
                    a.openActReport("pub_suc", {
                        module: "reply"
                    })
                },
                params: c.params
            }),
            !0)
        }
    }
    var e = a.bid
      , f = a.pid
      , g = a.postType
      , h = a.flag
      , i = a.report;
    return {
        reply: c,
        wordsReply: d
    }
});