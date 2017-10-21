!function(a, b) {
    a.ApplyAdmin = b()
}(this, function() {
    function a(a, b) {
        var e = {
            opername: "tribe_cgi",
            module: "post",
            ver1: c,
            ver3: d,
            action: a
        };
        for (var f in b)
            b.hasOwnProperty(f) && (e[f] = b[f]);
        Q.tdw(e)
    }
    function b(b, c, d, e) {
        b = b,
        c = c,
        a("apply_chief"),
        DB.cgiHttp({
            url: "http://xiaoqu.qq.com/cgi-bin/bar/apply/check",
            param: {
                bid: b
            },
            succ: function(c) {
                if (!c.result || c.retcode) {
                    var f = c.retcode || "error";
                    return Tip.show("申请失败(" + f + ")", {
                        type: "warning"
                    }),
                    void a("reject_chief", {
                        ver4: 6
                    })
                }
                if (1 === c.result.violated)
                    return void Tip.show(c.result.violate_msg, {
                        type: "warning"
                    });
                var g = c.result.small_admins
                  , h = c.result.big_admins
                  , i = "";
                if (0 === g && (i += "&sna=1"),
                0 === h && (i += "&bna=1"),
                !c.result.qq_level || c.result.qq_level <= 12)
                    Tip.show("您的QQ等级太低，暂时无法申请酋长！", {
                        type: "warning"
                    }),
                    a("reject_chief", {
                        ver4: 1
                    });
                else if (c.result.isban)
                    mqq.ui.showDialog({
                        title: "",
                        text: "您的帐号因在部落被举报有违规操作，暂时无法申请酋长！",
                        needOkBtn: !0,
                        okBtnText: "我知道了",
                        needCancelBtn: !1
                    }, function() {}),
                    a("reject_chief", {
                        ver4: 2
                    });
                else if (1 === c.result.has_submit || 2 === c.result.has_submit || 3 === c.result.has_submit)
                    3 === c.result.has_submit ? Tip.show("您的酋长申请还在审核流程中，请勿重复申请！", {
                        type: "warning"
                    }) : (1 === c.result.has_submit || 2 === c.result.has_submit) && Tip.show("您在一个月内无法重复申请该部落的酋长！", {
                        type: "warning"
                    }),
                    a("reject_chief", {
                        ver3: 4
                    });
                else if (2 & c.result.admin)
                    Tip.show("您已经是大酋长了，无法申请！", {
                        type: "warning"
                    }),
                    a("reject_chief", {
                        ver3: 5
                    });
                else {
                    var j = "";
                    1 === d && (j = "&bo=1"),
                    11 === d && (j = "&bo=1&noadmin=1"),
                    2 === d && (j = "&so=1"),
                    4 === c.result.has_submit && (j = "&so=1"),
                    5 === c.result.has_submit && (j = "&bo=1"),
                    i += j,
                    1 === e && (i += "&notip=1"),
                    localStorage.setItem("recruitCurrentState", "1"),
                    Util.openUrl("http://xiaoqu.qq.com/mobile/recruit.html?_wv=1027&bid=" + b + "&bname=" + c.result.bname + "&sa=" + (4 & c.result.admin ? 1 : 0) + i, !0)
                }
            }
        })
    }
    var c, d;
    return {
        doApply: b
    }
});