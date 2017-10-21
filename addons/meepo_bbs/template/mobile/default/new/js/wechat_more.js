!function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_wechat_more = b()
}(this, function() {
    var a = {}
      , b = '<div class=\'m-container\'>\r\n    <ul class="m-list">\r\n        <li data-act="jump" data-type="person" ><div class="m-ibox" ><img class="m-pics" soda-src="{{faceurl}}" /><div class="m-caption">我的兴趣名片</div></div></li>\r\n        <li data-act="jump" data-type="msg" ><div class="m-ibox" ><div class="m-icon i-msg"></div><div class="m-caption" >消息通知</div><span id="m_num" class="m-num">{{point}}</span></div></li>\r\n        <li data-act="jump" data-type="data" ><div class="m-ibox" ><div class="m-icon i-data"></div><div class="m-caption" >部落资料</div></div></li>\r\n        <li data-act="jump" data-type="feedback" ><div class="m-ibox" ><div class="m-icon i-warn"></div><div class="m-caption" >意见反馈</div></div></li>\r\n        <li ></li>\r\n        <li ></li>\r\n    </ul>\r\n</div>';
    return a.list = "TmplInline_wechat_more.list",
    Tmpl.addTmpl(a.list, b),
    a
}),
function(a, b) {
    a.WeChatMore = b(a.$, a.DB)
}(this, function(a, b) {
    function c() {
        a("#m_num").hide(),
        a("#m_point").hide()
    }
    var d, e, f, g = Login.getUin(), h = Util.queryString("bid") || Util.getHash("bid"), j = Util.queryString("from") || Util.getHash("from") || "other", k = function(a, b) {
        var c = {
            opername: "Grp_tribe",
            module: "more",
            ver1: h || Util.queryString("bid") || Util.getHash("bid")
        };
        c.action = n[a].action;
        for (i in b)
            b.hasOwnProperty(i) && (c[i] = b[i]);
        Q.tdw(c)
    }
    , l = new cgiModel({
        comment: "getPoints",
        cgiName: "/cgi-bin/bar/user/mybarlist_v2",
        param: {
            num: 1,
            start: 0,
            t: (new Date).getTime()
        },
        processData: function(a, b) {
            d = Number(a.result.point),
            e = Number(a.result.point_sys),
            f = Number(a.result.point_reply)
        },
        complete: function() {
            m.rock()
        }
    }), m = new renderModel({
        renderTmpl: TmplInline_wechat_more.list,
        renderContainer: "#wechat_more",
        cgiName: "/cgi-bin/bar/card/merge_top",
        param: {
            targetuin: g
        },
        processData: function(a, b) {
            a.result.faceurl = a.result.faceurl.replace(/&amp;/g, "&"),
            a.result.point = d
        },
        complete: function() {
            0 === d && c()
        }
    }), n = {
        feedback: {
            url: "http://kf.qq.com/touch/feedback_app.html?code=tribe&fid=1080&tj_src=app&product=tribe&ADTAG=veda.tribe.app&plg_auth=1",
            action: "Clk_feedback"
        },
        person: {
            url: "http://xiaoqu.qq.com/mobile/personal.html?_wv=16777219&uin=" + g + "&from=" + j,
            action: "Clk_person_data"
        },
        msg: {
            url: "http://xiaoqu.qq.com/mobile/my_related.html?point_reply=" + f + "&point_sys=" + e + "&from=" + j,
            action: "Clk_msg"
        },
        data: {
            url: "http://xiaoqu.qq.com/mobile/bar_rank.html?bid=" + h + "&from=" + j,
            action: "Clk_tribe_data"
        },
        exp: {
            action: "exp"
        }
    };
    return a("#wechat_more").on("tap", "li", function(b) {
        var e = a(this).data("type");
        e && ("msg" === e ? (c(),
        k(e, {
            ver3: d > 0 ? 1 : 2
        })) : k(e),
        setTimeout(function() {
            Util.openUrl(n[e].url, !0)
        }, 500))
    }),
    d > 0 ? (a("#m_num").html(d),
    a("#m_num").show()) : c(),
    k("exp"),
    {
        init: function() {
            l.rock()
        }
    }
});
