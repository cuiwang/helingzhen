function loadimg(a) {
    var b = $(a.target).height();
    $(".message-wrapper")[0].scrollTop = $(".message-wrapper")[0].scrollTop + b
}
function JoinGroup() {
    DB.cgiHttp({
        url: "http://buluo.qq.com/cgi-bin/bar/user/join_qun",
        ssoCmd: "join_qun",
        type: "POST",
        param: {
            bid: Util.queryString("bid"),
            group_code: Util.queryString("gcode"),
            owner_uin: ownerUin,
            agent_type: $.os.android ? 3 : $.os.ios ? 2 : 0
        },
        succ: function(a) {
            return a.retcode ? void Q.tdw({
                opername: "Grp_tribe",
                module: "tribe_grp",
                action: "join_fail",
                obj1: Util.queryString("gcode")
            }) : (Q.tdw({
                opername: "Grp_tribe",
                module: "tribe_grp",
                action: "join_grp",
                obj1: Util.queryString("gcode")
            }),
            void setTimeout(function() {
                mqq.ui.openAIO({
                    uin: Util.queryString("gcode"),
                    chat_type: "group"
                }),
                mqq.ui.closeWebViews({
                    mode: 0,
                    exclude: !1
                })
            }, 100))
        },
        err: function() {
            Q.tdw({
                opername: "Grp_tribe",
                module: "tribe_grp",
                action: "join_fail",
                obj1: Util.queryString("gcode")
            });
            var a = "";
            switch (data.retcode) {
            case 1:
            case 2:
            case 3:
            case 6:
            case 8:
                a = "该群不能加入，请返回首页再看看吧";
                break;
            case 4:
            case 5:
                a = "该群已解散，请返回首页再看看吧";
                break;
            case 11:
                a = "该群已满员，请返回首页再看看吧";
                break;
            default:
                a = "该群不能加入，请返回首页再看看吧"
            }
            Tip.show(a, {
                type: "warning"
            })
        }
    })
}
function scrollToBottom() {
    $(".message-wrapper")[0].scrollTop = $(".message-wrapper")[0].scrollHeight
}
function domUtil(a, b, c) {
    var d = (c.scrollTop,
    a.height())
      , e = ($(b).prependTo(a),
    a.height() - d);
    c.scrollTop = e
}
function setButton() {
    return isJoin ? (JoinButton.data.word = wording.join,
    JoinButton.data.active = "active",
    void JoinButton.rock()) : (JoinButton.data.word = buttonTime-- + wording.wait,
    0 > buttonTime && (JoinButton.data.word = wording.join,
    JoinButton.data.active = "active",
    Q.tdw({
        opername: "Grp_tribe",
        module: "tribe_grp",
        action: "exp_ready",
        obj1: Util.queryString("gcode")
    })),
    JoinButton.rock(),
    void (buttonTime >= 0 && setTimeout(function() {
        setButton()
    }, 1e3)))
}
function bindEvent() {
    function a() {
        return isEnd ? void $("#messageLoading").text("没有更多消息了").removeClass("spinner") : void ($(".message-wrapper")[0].scrollTop < 200 && MoreMessage.rock())
    }
    var b;
    $(".message-wrapper").scroll(function() {
        b && (clearTimeout(b),
        b = null ),
        b = setTimeout(a, 500)
    }),
    $("#buttonBox").on("tap", function(a) {
        var b = $(a.target);
        b.hasClass("active") && (isJoin ? (mqq.ui.openAIO({
            uin: Util.queryString("gcode"),
            chat_type: "group"
        }),
        mqq.ui.closeWebViews({
            mode: 0,
            exclude: !1
        })) : JoinGroup())
    })
}
!function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_buluoqun = b()
}(this, function() {
    var a = {}
      , b = '<div class="button" soda-class="active? active : \'\'">{{word}}</div>';
    a.button = "TmplInline_buluoqun.button",
    Tmpl.addTmpl(a.button, b);
    var c = '<li soda-repeat="item in list">\r\n    <div soda-if="item.type === 0" class="message">\r\n        <img soda-src="{{item.avatar}}" class="avatar">\r\n        <div class="word-box">\r\n            <div class="name"><span v-if="item.level" class="level">{{item.level}}</span>{{item.name}}</div>\r\n            <div class="content" soda-bind-html="item.content"></div>\r\n        </div>\r\n        <div class="empty-box"></div>\r\n    </div>\r\n    <div soda-if="item.type === 1" class="join-box">\r\n        <div class="join"><span class="join-name">{{item.name}}</span>加入聊天室</div>\r\n    </div>\r\n    <div soda-if="item.type === 2" class="time">{{(item.time * 1000) | formatTime}}</div>\r\n</li>\r\n';
    return a.chat = "TmplInline_buluoqun.chat",
    Tmpl.addTmpl(a.chat, c),
    a
});
var wording = {
    join: "加入讨论",
    wait: "秒后可以加入",
    no: "人数已满，暂时不能加入"
}
  , buttonTime = 10
  , isMember = !1
  , isEnd = !1
  , isJoin = !1
  , time = 0
  , ownerUin = 0;
sodaFilter("formatTime", function(a) {
    if (!a)
        return "刚刚";
    var b = new Date(a)
      , c = b.getHours()
      , d = b.getMinutes()
      , e = b.getSeconds()
      , f = b.getFullYear()
      , g = b.getMonth() + 1
      , h = b.getDate();
    10 > c && (c = "0" + c),
    10 > d && (d = "0" + d),
    10 > e && (e = "0" + e),
    10 > g && (g = "0" + g),
    10 > h && (h = "0" + h);
    var i = new Date;
    i.setHours(0),
    i.setMinutes(0),
    i.setSeconds(0);
    var j = +i;
    +new Date;
    return a > j ? [c, d].join(":") : a > j - 864e5 ? "昨天 " + [c, d].join(":") : b.getFullYear() == i.getFullYear() ? [g, "月", h, "日"].join("") + " " + [c, d].join(":") : [f, "年", g, "月", h, "日"].join("") + " " + [c, d].join(":")
});
var FirstMessage = new renderModel({
    renderContainer: ".message-list",
    renderTmpl: "TmplInline_buluoqun.chat",
    cgiName: "/cgi-bin/bar/post/content",
    param: function() {
        var a = 20
          , b = -a;
        return function() {
            return b += a,
            {
                bid: Util.queryString("bid"),
                pid: Util.queryString("pid"),
                barlevel: 1,
                start: b,
                num: 20,
                src: 1,
                get_like_url: 1,
                group_code: Util.queryString("gcode")
            }
        }
    }(),
    noCache: 1,
    processData: function(a) {
        if (!a.retcode && a.result) {
            var b = a.result.post.record_userinfo
              , c = a.result.post.record
              , d = []
              , e = {}
              , f = {}
              , g = !1;
            b.map(function(a) {
                e[a.su] = {
                    pic: a.pic,
                    level: a.diy_level,
                    name: a.nick
                }
            }),
            $("title").text(a.result.post.group_name),
            mqq.ui.refreshTitle(),
            c.length || $("#messageLoading").hide();
            for (var h = 0; h < c.length; h++) {
                f = c[h],
                (f.attr.time - time) / 60 >= 2 && (d.push({
                    type: 2,
                    time: f.attr.time
                }),
                g = !0),
                time = f.attr.time;
                for (var i = "", j = !1, k = 0; k < f.elems.length; k++)
                    elem = f.elems[k],
                    elem.text && (j ? i = elem.text.str && /http:\/\/maps.google.com\/maps/.test(elem.text.str) ? "我在这里：" + elem.text.str.match(/[(（]([^\(\)（）]+)[）)]/)[1] : "[链接]" : i += elem.text.str.replace(/\r/g, "<br/>")),
                    elem.custom_face && (i = i + '<img src="' + elem.custom_face.url + '" class="chat-img load-img" />'),
                    elem.face && (i = i + '<img src="http://imgcache.qq.com/club/chatmsg/face/' + elem.face.index + '.gif" class="face-img load-img"/>'),
                    elem.rich_msg && (i && /http:\/\/maps.google.com\/maps/.test(i) ? i = "我在这里：" + i.match(/[(（]([^\(\)（）]+)[）)]/)[1] : i && (i = "[链接]"),
                    j = !0);
                i && "[链接]" !== i ? d.push({
                    content: i,
                    name: e[f.su].name,
                    type: 0,
                    avatar: e[f.su].pic,
                    level: e[f.su].level
                }) : g && (d.splice(d.length - 1, 1),
                g = !1)
            }
            a.result.list = d,
            isJoin = a.result.post.is_joined,
            isEnd = a.result.post.isend,
            ownerUin = a.result.post.owner_uin
        }
    },
    complete: function(a, b) {
        1 == b && (scrollToBottom(),
        $(".load-img").on("load", loadimg)),
        isEnd && $("#messageLoading").hide(),
        $("#buttonBox").show(),
        setButton()
    },
    events: function() {
        $(this.renderContainer).on("tap", ".chat-img", function(a) {
            mqq.media.showPicture({
                imageIDs: [a.target.src]
            })
        })
    }
})
  , JoinButton = new renderModel({
    renderContainer: ".button-box",
    renderTmpl: "TmplInline_buluoqun.button",
    data: {
        word: "",
        active: ""
    },
    processData: function() {},
    complete: function(a, b) {}
})
  , MoreMessage = FirstMessage.extend({
    renderContainer: $(document.createDocumentFragment()),
    param: function() {
        var a = 20
          , b = 0;
        return function() {
            return b += a,
            {
                bid: Util.queryString("bid"),
                pid: Util.queryString("pid"),
                barlevel: 1,
                start: b,
                num: 20,
                src: 1,
                get_like_url: 1,
                group_code: Util.queryString("gcode")
            }
        }
    }(),
    complete: function() {
        domUtil($(".message-list"), this.renderContainer, $(".message-wrapper")[0])
    }
});
FirstMessage.rock(),
JoinButton.rock(),
bindEvent(),
mqq.ui.setPullDown({
    enable: !1
}),
Q.tdw({
    opername: "Grp_tribe",
    module: "tribe_grp",
    action: "exp_mid",
    obj1: Util.queryString("gcode")
});
var isWeChat = /\bMicroMessenger\/[\d\.]+/.test(navigator.userAgent);
isWeChat && (location.href = "mqqapi://forward/url?version=1&src_type=web&url_prefix=" + btoa(location.href));
