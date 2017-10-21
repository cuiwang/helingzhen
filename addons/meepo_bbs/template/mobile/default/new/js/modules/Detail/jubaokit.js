!function(a, b) {
    a.jubaokit = b()
}(this, function() {
    function a(a) {
        DB.cgiHttp({
            ssoCmd: "jubao",
            param: a,
            url: "/cgi-bin/bar/admin/jubao",
            succ: function(b) {
                var d = function() {
                    Tip.show("举报成功，客服人员将尽快处理。", {
                        type: "ok"
                    }),
                    "function" == typeof c && c()
                }
                ;
                b.result && 1 === b.result.vflag ? Checkcode.show("report", function() {
                    d()
                }, a) : 0 == b.retcode ? d() : Tip.show("举报失败，请稍后重试。", {
                    type: "warning"
                })
            },
            err: function(a) {
                Tip.show("举报失败，请稍后重试。", {
                    type: "warning"
                })
            }
        })
    }
    function b(b) {
        var e = b.barId
          , f = b.pid
          , g = b.eviluin
          , h = b.impeachuin
          , i = $.os.ios ? "ios" : $.os.android ? "android" : "";
        c = b.callback || "";
        var j = {
            appname: "KQQ",
            subapp: "qunbuluo",
            jubaotype: "article",
            langcode: "0",
            impeach_origin_uin: g,
            app_param: encodeURIComponent("bid=" + e + "&eviluin=" + g + "&impeachuin=" + h + "&pid=" + f + "&system=" + i),
            sbm: "确定",
            bid: +e,
            pid: f,
            sso_key: $.cookie("verifysession")
        };
        $.os.ios && 0 !== mqq.QQVersion && $.os.version && 1 * $.os.version.charAt(0) > 7 ? mqq.ui.showActionSheet({
            items: d,
            cancel: "取消"
        }, function(b, c) {
            0 === Number(b) && (j.impeach_content = Number(c) + 1,
            a(j))
        }) : ActionSheet.show({
            useH5: !0,
            items: d,
            cancleText: "取消",
            onItemClick: function(b) {
                j.impeach_content = Number(b) + 1,
                a(j)
            }
        })
    }
    var c, d = ["欺诈骗钱", "色情暴力", "广告骚扰", "其他"];
    return {
        init: b
    }
});