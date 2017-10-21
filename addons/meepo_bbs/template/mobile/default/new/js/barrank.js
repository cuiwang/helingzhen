!function(a, b) {
    a.ActionSheet = b(a.$, a.Tmpl)
}(this, function(a, b) {
    function c() {
        i = a('<div class="action-sheet"></div>'),
        h = a('<div class="action-sheet-mask"></div>'),
        a(document.body).append(i).append(h),
        h.on("tap", function() {
            f()
        }).on("webkitTransitionEnd", function() {
            h.hasClass("show") || h.hide()
        }),
        i.on("tap", ".sheet-item", e).on("webkitTransitionEnd", function() {
            i.hasClass("show") || (h.removeClass("show"),
            i.hide())
        }),
        k = !0
    }
    function d(d) {
        mqq.compare("4.7") >= 0 && !d.useH5 ? mqq.ui.showActionSheet({
            items: d.items,
            cancel: "取消"
        }, function(a, b) {
            0 === a ? d.onItemClick && d.onItemClick(b) : d.onCancel && d.onCancel(b)
        }) : (k || c(),
        j = d,
        document.body.style.overflow = "hidden",
        i.html(""),
        l.on("touchmove", g),
        b(window.TmplInline_actionsheet.frame, d).appendTo(i),
        h.show(),
        i.show(),
        setTimeout(function() {
            h.addClass("show"),
            i.addClass("show")
        }, 50)),
        d.cancle && a('.sheet-item[value="-1"] .sheet-item-text').html(d.cancle)
    }
    function e() {
        var b = a(this).attr("value");
        b = Number(b),
        -1 === b ? j.onCancel && j.onCancel() : j.onItemClick && j.onItemClick(b),
        f()
    }
    function f(a) {
        i && (a ? (h.hide().removeClass("show"),
        i.hide().removeClass("show")) : i.removeClass("show"),
        l.off("touchmove", g),
        document.body.style.overflow = "")
    }
    function g(a) {
        a.preventDefault()
    }
    var h, i, j, k, l = a(document);
    return {
        show: d,
        hide: f
    }
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_actionsheet = b()
}(this, function() {
    var a = {}
      , b = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("title")
          , f = c("items")
          , g = c("cancleText")
          , h = "";
        h += " ",
        e && (h += ' <div class="sheet-item sheet-title" value="-1">',
        h += d(e),
        h += "</div> "),
        h += " ";
        for (var i = 0; i < f.length; i++)
            h += ' <div class="sheet-item ',
            h += d(f[i].type || ""),
            h += '" value="',
            h += d(i),
            h += '"> <div class="sheet-item-text" >',
            h += d(f[i].text || f[i]),
            h += "</div> </div> ";
        return h += ' <div class="sheet-item sheet-cancle" value="-1" > <div class="sheet-item-text">',
        h += d(g || "取消"),
        h += "</div> </div> "
    }
    ;
    return a.frame = "TmplInline_actionsheet.frame",
    Tmpl.addTmpl(a.frame, b),
    a
}),
function(a, b) {
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
