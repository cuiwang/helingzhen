!function(a, b) {
    a.ActionButton = b()
}(this, function() {
    function a() {
        mqq.compare("5.3") < 0 || -1 !== navigator.userAgent.indexOf("QQHD") ? m ? c(m.option, m.callback) : c({
            hidden: !0
        }, function() {}) : mqq.android ? mqq.invoke("TroopMemberApiPlugin", "getUploadInfo", {
            callback: mqq.callback(b)
        }) : mqq.compare("6.3.1") > 0 ? m && m.option && c(m.option, m.callback) : mqq.invoke("tribe", "getUploadInfo", {
            callback: mqq.callback(b)
        })
    }
    function b(a) {
        var b = {}
          , e = parseInt(a.status)
          , f = location.href.indexOf("/barindex.html") > -1;
        e ? m ? (b.option = o.option,
        b.option.cornerID = 2 === e ? "6" : "0",
        b.callback = function() {
            d(e)
        }
        ) : (b = o,
        b.option.cornerID = 2 === e ? "6" : "0") : (m ? b = m : (b.option = {
            iconID: "3",
            cornerID: "0",
            hidden: !0
        },
        b.callback = function() {}
        ),
        f && $(".uploading-video").remove()),
        n = a.info || [],
        l = a,
        c(b.option, b.callback),
        j && j()
    }
    function c(a, b) {
        if (-1 !== navigator.userAgent.indexOf("QQHD")) {
            var c = ~~a.iconID;
            return a.title || (4 === c ? a.title = "分享" : a.title = "更多"),
            a.isDetail && (a.title = "分享",
            delete a.isDetail),
            a.hidden && (a.title = ""),
            mqq.invokeClient("nav", "setActionButton", a, b)
        }
        return mqq.ui.setActionButton ? mqq.ui.setActionButton(a, b) : void 0
    }
    function d(a) {
        if (k = $(".action-button-menu-mask"),
        !k.length) {
            var b = m.option.type
              , c = '<div class="action-button-menu-mask"><ul id="_js_action_button_menu" class="action-button-menu">';
            c += '<li class="action-button-upload section-1px"><span id="js_ac_upload_text"></span><span id="js_ac_upload_err" class="tip-error"></span></li>',
            c += '<li class="action-button-custom action-button-' + b + '">',
            c += "share" === b ? "分享" : "更多",
            c += "</li></ul></div>",
            k = $(c).appendTo("body")
        }
        2 === a ? ($("#js_ac_upload_text").text("上传失败"),
        $("#js_ac_upload_err").show()) : ($("#js_ac_upload_text").text("正在上传"),
        $("#js_ac_upload_err").hide()),
        k.show()
    }
    function e(a, b) {
        var c, d, e, f, g = [];
        for (d = n.length; d > 0; d--)
            e = n[d - 1],
            e.bid && e.bid === a && (f = {
                isMock: !0,
                type: 201,
                bid: a,
                pid: e.pid,
                title: e.title,
                brief: "视频正在上传中...",
                post: {
                    content: e.content,
                    image1: e.video_pic,
                    pic_list: []
                },
                time: e.time
            },
            b && e.pid === b && !e.cid && (c = f),
            b || e.cid || g.push(f));
        return b ? c : g
    }
    function f(b, c) {
        m = {
            option: b,
            callback: c
        },
        a()
    }
    function g() {
        $(document).on("tap", ".action-button-upload", function() {
            k.hide(),
            o.callback()
        }),
        $(document).on("tap", ".action-button-custom", function() {
            k.hide(),
            m.callback()
        }),
        $(document).on("touchmove", ".action-button-menu-mask", function(a) {
            a.preventDefault(),
            a.stopPropagation()
        }),
        $(document).on("tap", ".action-button-menu-mask", function() {
            k.hide()
        }),
        document.addEventListener("qbrowserVisibilityChange", function(b) {
            b.hidden || a()
        }),
        mqq.addEventListener("kTribeUploadStatusChangeNotifcation", function() {
            a()
        })
    }
    function h(a) {
        j = a
    }
    function i() {
        mqq.compare("5.3") > 0 && g(),
        a()
    }
    var j, k, l, m, n = [], o = {
        option: {
            iconID: "5"
        },
        callback: function() {
            mqq.iOS && mqq.ui.openView({
                name: "QQGTUploadListViewController"
            }),
            mqq.android && mqq.ui.openView({
                name: "com.tencent.mobileqq.troop.activity.TroopBarUploadManagerActivity"
            })
        }
    }, p = location.href.indexOf("/personal.html") > -1, q = location.href.indexOf("/personal_edit.html") > -1;
    return p || q ? void 0 : (i(),
    {
        build: f,
        refresh: a,
        showMenu: d,
        getUploadVideo: e,
        setCallback: h
    })
});